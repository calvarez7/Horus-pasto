<template>
    <div>
        <v-form>
            <v-layout row wrap>
                    <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Antecedentes traumaticos</span>
                        </v-card-title>
                         </v-flex>
                         <v-flex xs12 sm6 md5 >
                        <v-select v-model="antecedentesTraumaticos.traumaticos" label="Traumatismos" :items="sino">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md5 >
                        <v-text-field v-model="antecedentesTraumaticos.descripcion_traumaticos" label="Descripciòn">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md5 >
                        <v-select v-model="antecedentesTraumaticos.accidentes" label="Accidentes" :items="sino">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md5 >
                        <v-text-field v-model="antecedentesTraumaticos.descripcion_accidentes" label="Descripciòn">
                        </v-text-field>
                    </v-flex>

                     <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="guardarTraumaticos()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
        </v-form>
             <v-flex xs12 sm6 md12>
            <v-data-table :items="getTraumaticos" :headers="traumaticos" hide-actions class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.created_at }}</td>
                    <td class="text-xs">{{ props.item.traumaticos }}</td>
                    <td class="text-xs">{{ props.item.descripcion_traumaticos }}</td>
                    <td class="text-xs">{{ props.item.accidentes }}</td>
                    <td class="text-xs">{{ props.item.descripcion_accidentes }}</td>
                    <td class="text-xs">{{ props.item.medico }}</td>
                     <td class="text-xs">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="red" dark v-on="on">
                                    <v-icon @click="inabilitarTraumatico(props.item.id)">
                                        mdi-delete-forever
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Historial</span>
                        </v-tooltip>
                    </td>
                </template>
            </v-data-table>
        </v-flex>

        <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
    </div>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchTraumaticos()
        },
        data() {
            return {
                antecedentesTraumaticos: {
                    traumaticos: '',
                    accidentes: '',
                    descripcion_accidentes: '',
                    descripcion_traumaticos: ''
                },
                loading: false,
                sino: ['SI', 'NO'],
                getTraumaticos: [],

                traumaticos:[{
                    text:'Fecha'
                },
                {
                    text:'Traumatismo'
                },
                {
                    text:'Descripciòn'
                },
                 {
                    text:'Accidentes'
                },
                {
                    text:'Descripciòn'
                },
                    {
                        text: 'Medico'
                    }
                ]

            }
        },
        methods: {

            guardarTraumaticos(){
                if (!this.antecedentesTraumaticos.traumaticos) {
                    this.$alerError("El campo traumatismo es requerido!");
                    return
                } else if(!this.antecedentesTraumaticos.descripcion_traumaticos){
                     this.$alerError("El campo descripciòn es requerido!");
                    return
                }else if(!this.antecedentesTraumaticos.accidentes){
                     this.$alerError("El campo accidente es requerido!");
                    return
                }else if(!this.antecedentesTraumaticos.descripcion_accidentes){
                     this.$alerError("El campo descripciòn es requerido!");
                    return
                }
                this.antecedentesTraumaticos.paciente_id=this.datosCita.paciente_id;
                this.antecedentesTraumaticos.citaPaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveTraumaticos',this.antecedentesTraumaticos).then((res)=>{
                     this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.fetchTraumaticos();
                    this.clearTraumatico();
                });
            },

            fetchTraumaticos(){
                const farm = {
                    paciente_id: this.datosCita.paciente_id
                }
                axios.post('/api/historia/getTraumaticos', farm)
                   .then(res => {
                       this.getTraumaticos = res.data
                   });
            },

            inabilitarTraumatico(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El antecedente traumatico será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/traumatico/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchTraumaticos();
                });
            },

            clearTraumatico(){
                this.antecedentesTraumaticos.traumaticos = "";
                this.antecedentesTraumaticos.accidentes='';
                this.antecedentesTraumaticos.descripcion_accidentes='';
                 this.antecedentesTraumaticos.descripcion_traumaticos='';
            },

            guardarSeguir() {
                this.$emit('respuestaComponente')
            }
        }
    }

</script>
