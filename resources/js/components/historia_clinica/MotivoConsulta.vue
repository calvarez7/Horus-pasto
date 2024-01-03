<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Motivo de consulta y enfermedad
                        actual</span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" v-model="motivo.Motivoconsulta"
                            :error-messages="errores.Motivoconsulta" label="Motivo de consulta" autofocus>
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" v-model="motivo.Enfermedadactual"
                            :error-messages="errores.Enfermedadactual" label="Enfermedad actual">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Resultados de Ayudas Diagnosticas" value=""
                            v-model="motivo.Resultayudadiagnostica"></v-textarea>
                    </v-flex>
                    <v-flex xs12 v-if="datosCita.Especialidad == 'Oncologia'">
                        <v-textarea name="input-7-1" label="Tratamiento Oncologico" value=""
                            v-model="motivo.tratamientoncologico"></v-textarea>
                    </v-flex>
                    <v-flex xs12 sm4 md4 v-if="datosCita.Especialidad == 'Oncologia'">
                        <v-checkbox color="primary" v-model="motivo.cirujiaoncologica" value="1"
                            label="Cirugía oncológica"></v-checkbox>
                        <v-flex md3>
                            <v-text-field v-show="motivo.cirujiaoncologica" v-model="motivo.ncirujias"
                                label="cuantas: ">
                            </v-text-field>
                        </v-flex>
                        <v-flex md4>
                            <v-text-field v-show="motivo.cirujiaoncologica" v-model="motivo.iniciocirujia"
                                label="Fecha de inicio: " type="date"></v-text-field>
                        </v-flex>
                        <v-flex md4>
                            <v-text-field v-show="motivo.cirujiaoncologica" v-model="motivo.fincirujia"
                                label="Fecha de finalizacion: " type="date"></v-text-field>
                        </v-flex>
                    </v-flex>
                    <v-flex xs12 sm4 md4 v-if="datosCita.Especialidad == 'Oncologia'">
                        <v-checkbox value="1" color="primary" v-model="motivo.recibioradioterapia"
                            label="Recibio radioterapia"></v-checkbox>
                        <v-flex md4>
                            <v-text-field v-show="motivo.recibioradioterapia" v-model="motivo.nsesiones"
                                label="Número Sesiones: ">
                            </v-text-field>
                        </v-flex>
                        <v-flex md4>
                            <v-text-field v-show="motivo.recibioradioterapia" v-model="motivo.inicioradioterapia"
                                label="Fecha de inicio: " type="date"></v-text-field>
                        </v-flex>
                        <v-flex md4>
                            <v-text-field v-show="motivo.recibioradioterapia" v-model="motivo.finradioterapia"
                                label="Fecha de finalizacion: " type="date"></v-text-field>
                        </v-flex>
                    </v-flex>
                    <v-flex xs12 sm4 md4 v-if="datosCita.Especialidad == 'Oncologia'">
                        <v-flex xs12>
                            <v-select :items="intencion" v-model="motivo.intencion"
                                label="Intencion del tratamiento oncológico inicial"></v-select>
                        </v-flex>
                    </v-flex>
                    <v-flex xs12 v-if="datosCita.Tipo_agenda == '536' || this.datosCita.Tipo_agenda == '4' || this.datosCita.Tipo_agenda == '181' || this.datosCita.Tipo_agenda == '525' || datosCita.Tipo_agenda == '23' ">
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Ultimas Valoraciones</span>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="valoraciones" class="elevation-1">
                            <template v-slot:items="props">
                            <td>{{ props.item.Codigo }}</td>
                                <td class="text-xs-left">{{ props.item.Nombre }}</td>
                                <td class="text-xs-left">{{ props.item.Fechaorden }}</td>
                                <td class="text-xs-left">{{ props.item.Medico }}</td>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
                <v-btn color="success" @click="saveMotivoConsulta()">Guardar y Seguir</v-btn>
            </v-container>
        </v-form>
    </div>
</template>
<script>
    import Swal from 'sweetalert2';
    import moment from "moment";
    export default {
        name: "MotivoConsulta",
        props: {
            datosCita: Object
        },
        data() {
            return {
                motivo: {
                    Motivoconsulta: '',
                    Enfermedadactual: '',
                    Resultayudadiagnostica: '',
                    intencion: '',
                    tratamientoncologico: '',
                    cirujiaoncologica: '',
                    recibioradioterapia: '',
                    ncirujias: '',
                    iniciocirujia: '',
                    fincirujia: '',
                    inicioradioterapia: '',
                    finradioterapia: '',
                    nsesiones: '',
                },
                errores: {
                    Motivoconsulta: '',
                    Enfermedadactual: ''
                },
                intencion: ['Curación', 'Paliación'],

                headers: [{
                        text: 'Codigos',
                        align: 'left',
                    },
                    {
                        text: 'Servicios',
                        value: 'calories'
                    },
                    {
                        text: 'Fecha',
                        value: 'calories'
                    },
                    {
                        text: 'Medico Que Ordena',
                        value: 'fat'
                    }
                ],
                valoraciones: []
            }
        },
        created() {
            this.fetchMotivoConsulta();
            this.fetchValoraciones();
        },
        methods: {

            saveMotivoConsulta() {
                this.clearError()
                this.motivo.cita_paciente_id = this.datosCita.cita_paciente_id
                axios.post('/api/historia/saveMotivoConsulta', this.motivo)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                    }).catch(err => {
                        this.setError(err.response.data)
                    })
            },
            fetchMotivoConsulta() {
                axios.get(`/api/historia/fetchMotivoConsulta/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.motivo = res.data;
                    });
            },
            fetchValoraciones() {
                axios.get(`/api/historia/fetchValoraciones/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.valoraciones = res.data;
                    });
            },
            setError(errors) {
                for (const key in this.errores) {
                    if (key in errors) {
                        this.errores[key] = errors[key].join(',')
                    }
                }
            },
            clearError() {
                for (const key in this.errores) {
                    this.errores[key] = ''
                }
            }
        }
    }

</script>
