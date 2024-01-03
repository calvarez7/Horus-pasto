<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm12 md12>
                    <v-card-title class="headline" style="color:white;background-color:#0074a6">
                        <span class="title layout justify-center font-weight-light">Antecedentes Quirurgicos</span>
                    </v-card-title>
                </v-flex>
                <v-flex xs12 sm12 md12>
                    <v-checkbox color="success" v-model="antQuirurgico.antecedentes_operaciones" value="1"
                        label="Tiene antecedentes de operaciones"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="antQuirurgico.antecedentes_operaciones == true">
                    <v-text-field v-model="antQuirurgico.a_cual"  label="Cirugia"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="antQuirurgico.antecedentes_operaciones == true">
                    <v-text-field v-model="antQuirurgico.edad_quirurgicos" type="number" min="0" label="A que edad">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md1>
                    <v-btn fab dark small color="success" @click="saveAntQuirurgico()">
                        <v-icon dark>add</v-icon>
                    </v-btn>
                </v-flex>
                <v-flex xs12 sm6 md12>
                    <v-data-table :headers="headers" :items="getAntQuirurgico">
                        <template v-slot:items="props">
                            <td class="text-xs">{{ props.item.id }}</td>
                            <td class="text-xs">{{ props.item.antecedentes_operaciones | descripcion}}</td>
                            <td class="text-xs">{{ props.item.a_cual }}</td>
                            <td class="text-xs">{{ props.item.edad_quirurgicos }}</td>
                            <td class="text-xs">{{ props.item.medico }}</td>
                            <td class="text-xs">{{ props.item.created_at }}</td>
                            <td class="text-xs">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon color="red" dark v-on="on">
                                            <v-icon @click="deleteQuirurgi(props.item.id)">
                                                mdi-delete-forever
                                            </v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Historial</span>
                                </v-tooltip>
                            </td>
                        </template>
                        <template v-slot:no-results>
                            <v-alert :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </template>
                    </v-data-table>
                </v-flex>
            </v-layout>
            <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
        </v-container>
    </v-form>
</template>
<script>
    import MedicosVue from '../../views/modules/agendamiento/Medicos.vue';
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        data() {
            return {
                antQuirurgico: {
                    antecedentes_operaciones: '',
                    a_cual: '',
                    edad_quirurgicos: 0,
                },
                getAntQuirurgico: [],
                headers: [{
                        text: 'id'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Cirugia'
                    },
                    {
                        text: 'Edad'
                    },
                    {
                        text: 'Medico registro'
                    },
                    {
                        text: 'Fecha Registro'
                    }
                ]
            }
        },
        created() {
            this.fetchAntQuirurgico()
        },
        filters: {
            descripcion(item) {
                if (parseInt(item) == 1) {
                    return 'Si'
                }
            }
        },
        methods: {
            saveAntQuirurgico() {
                if (!this.antQuirurgico.antecedentes_operaciones) {
                    this.$alerError('El campo antecedentes de operaciones es requerido!')
                    return
                }
                this.antQuirurgico.citapaciente_id = this.datosCita.cita_paciente_id;
                this.antQuirurgico.paciente_id = this.datosCita.paciente_id;
                axios.post('/api/historia/saveAntQuirurgico', this.antQuirurgico)
                    .then(res => {
                        this.$alertHistoria(res.data.message);
                        this.fetchAntQuirurgico()
                        this.clearquirurgicos()
                    })
            },
            fetchAntQuirurgico() {
                axios.get(`/api/historia/fetchAntQuirurgico/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.getAntQuirurgico = res.data;
                    });
            },
            guardarSeguir() {
                this.$emit('respuestaComponente')
            },
            deleteQuirurgi(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El antecedente será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/deleteQuirurg/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchAntQuirurgico();
                });
            },
            clearquirurgicos() {
                for (const key in this.antQuirurgico) {
                    this.antQuirurgico[key] = ''
                }
            }
        }
    }

</script>
