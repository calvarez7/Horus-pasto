<template>
    <div>
        <v-form>
            <v-layout row wrap>
                    <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Tratamientos crónicos</span>
                        </v-card-title>
                         </v-flex>
                         <v-flex xs12 sm6 md5 >
                        <v-select v-model="antecedentesFarma.tratamientos_cronicos" label="Si/No" :items="sino">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md5 >
                        <v-text-field v-model="antecedentesFarma.descripcion_tratamientos_cronicos" label="Descripciòn">
                        </v-text-field>
                    </v-flex>
                     <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="guardarcronicos()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
        </v-form>
             <v-flex xs12 sm6 md12>
            <v-data-table :items="getCronicos" :headers="cronicos" hide-actions class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.created_at }}</td>
                    <td class="text-xs">{{ props.item.tratamientos_cronicos }}</td>
                    <td class="text-xs">{{ props.item.descripcion_tratamientos_cronicos }}</td>
                    <td class="text-xs">{{ props.item.medico }}</td>
                     <td class="text-xs">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="red" dark v-on="on">
                                    <v-icon @click="inabilitarcronico(props.item.id)">
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
          <v-layout row wrap>
                    <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Tratamiento con biológicos</span>
                        </v-card-title>
                         </v-flex>
                         <v-flex xs12 sm6 md5 >
                        <v-select v-model="antecedentesFarma.tratamientos_biologicos" label="Si/No" :items="sino">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md5 >
                        <v-text-field v-model="antecedentesFarma.descripcion_tratamientos_biologicos" label="Descripción">
                        </v-text-field>
                    </v-flex>
                     <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="guardarBiologicos()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
                 <v-flex xs12 sm6 md12>
            <v-data-table :items="getBiologicos" :headers="biologicos" hide-actions class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.created_at }}</td>
                    <td class="text-xs">{{ props.item.tratamientos_biologicos }}</td>
                    <td class="text-xs">{{ props.item.descripcion_tratamientos_biologicos }}</td>
                    <td class="text-xs">{{ props.item.medico }}</td>
                     <td class="text-xs">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="red" dark v-on="on">
                                    <v-icon @click="inabilitarbiologico(props.item.id)">
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
         <v-layout row wrap>
                    <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Recibe Quimioterapia</span>
                        </v-card-title>
                         </v-flex>
                         <v-flex xs12 sm6 md5 >
                        <v-select v-model="antecedentesFarma.quimioterapia" label="Si/No" :items="sino">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md5 >
                        <v-text-field v-model="antecedentesFarma.descripcion_quimioterapia" label="Descripción">
                        </v-text-field>
                    </v-flex>
                     <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="guardarquimioterapia()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
                 <v-flex xs12 sm6 md12>
            <v-data-table :items="getquimio" :headers="quimioterapia" hide-actions class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.created_at }}</td>
                    <td class="text-xs">{{ props.item.quimioterapia }}</td>
                    <td class="text-xs">{{ props.item.descripcion_quimioterapia }}</td>
                    <td class="text-xs">{{ props.item.medico }}</td>
                     <td class="text-xs">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="red" dark v-on="on">
                                    <v-icon @click="inabilitarquimio(props.item.id)">
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
            this.fetchTratamientosCronicos(),
            this.fetchBiologicos(),
            this.fetchQuimio()
        },
        data() {
            return {
                antecedentesFarma: {
                    tratamientos_cronicos: '',
                    descripcion_tratamientos_cronicos: '',
                    tratamientos_biologicos: '',
                    descripcion_tratamientos_biologicos: '',
                    quimioterapia: '',
                    descripcion_quimioterapia: ''
                },
                loading: false,
                sino: ['SI', 'NO'],
                getCronicos: [],
                getBiologicos:[],
                getquimio:[],
                cronicos:[{
                    text:'Fecha'
                },
                {
                    text:'Tratamiento crónico'
                },
                {
                    text:'Descripciòn'
                },
                    {
                        text: 'Medico'
                    }
                ],
                biologicos:[{
                    text:'Fecha'

                },
                {
                    text:'Tratamiento biológico'
                },
                {
                    text:'Descripción'
                },
                    {
                        text: 'Medico'
                    }
                ],
                quimioterapia:[{
                    text:'Fecha'

                },
                {
                    text:'Recibe quimioterapia'
                },
                {
                    text:'Descripción'
                },
                    {
                        text: 'Medico'
                    }
                ]

            }
        },
        methods: {

            guardarcronicos(){
                if (!this.antecedentesFarma.tratamientos_cronicos) {
                    this.$alerError("El campo Si/No es requerido!");
                    return
                } else if(!this.antecedentesFarma.descripcion_tratamientos_cronicos){
                     this.$alerError("El campo descripciòn es requerido!");
                    return
                }
                this.antecedentesFarma.paciente_id=this.datosCita.paciente_id;
                this.antecedentesFarma.citaPaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/savecronicos',this.antecedentesFarma).then((res)=>{
                     this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.fetchTratamientosCronicos();
                    this.clearCronico();
                });
            },

            guardarBiologicos(){
                if (!this.antecedentesFarma.tratamientos_biologicos) {
                    this.$alerError("El campo Si/No es requerido!");
                    return
                } else if(!this.antecedentesFarma.descripcion_tratamientos_biologicos){
                     this.$alerError("El campo descripción es requerido!");
                    return
                }
                this.antecedentesFarma.paciente_id=this.datosCita.paciente_id;
                this.antecedentesFarma.citaPaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveBiologicos',this.antecedentesFarma).then((res)=>{
                     this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.fetchBiologicos();
                    this.clearBiologico();
                });
            },

            guardarquimioterapia(){
                if (!this.antecedentesFarma.quimioterapia) {
                    this.$alerError("El campo Si/No alergias es requerido!");
                    return
                } else if(!this.antecedentesFarma.descripcion_quimioterapia){
                     this.$alerError("El campo descripción es requerido!");
                    return
                }
                this.antecedentesFarma.paciente_id=this.datosCita.paciente_id;
                this.antecedentesFarma.citaPaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveQuimio',this.antecedentesFarma).then((res)=>{
                     this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.fetchQuimio();
                    this.clearQuimio();
                });
            },



            fetchTratamientosCronicos(){
                const farm = {
                    paciente_id: this.datosCita.paciente_id
                }
                axios.post('/api/historia/getCronicos', farm)
                   .then(res => {
                       this.getCronicos = res.data
                   });
            },

            fetchBiologicos(){
                const farm = {
                    paciente_id: this.datosCita.paciente_id
                }
                axios.post('/api/historia/getBiologicos', farm)
                   .then(res => {
                       this.getBiologicos = res.data
                   });
            },

              fetchQuimio(){
                const farm = {
                    paciente_id: this.datosCita.paciente_id
                }
                axios.post('/api/historia/getQuimio', farm)
                   .then(res => {
                       this.getquimio = res.data
                   });
            },



            inabilitarcronico(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El tratamiento será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/cronico/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchTratamientosCronicos();
                });
            },

            inabilitarbiologico(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "el Tratamiento será eliminada",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/biologico/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchBiologicos();
                });
            },

            inabilitarquimio(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "La quimioterapia será eliminada",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/quimio/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchQuimio();
                });
            },

            clearCronico(){
                this.antecedentesFarma.tratamientos_cronicos = "";
                this.antecedentesFarma.descripcion_tratamientos_cronicos='';
            },

            clearBiologico(){
                this.antecedentesFarma.tratamientos_biologicos = "";
                this.antecedentesFarma.descripcion_tratamientos_biologicos='';
            },

            clearQuimio(){
                this.antecedentesFarma.quimioterapia = "";
                this.antecedentesFarma.descripcion_quimioterapia='';
            },

            guardarSeguir() {
                this.$emit('respuestaComponente')
            }
        }
    }

</script>
