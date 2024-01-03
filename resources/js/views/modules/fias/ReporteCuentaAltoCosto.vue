<template>
    <v-layout column>
        <v-card :disabled="loading">

            <v-card-title v-if="!loading">
                <h1>Reporte de cuenta de alto costo</h1>
            </v-card-title>

            <v-card-text class="text-xs-center" v-if="loading">
                <v-progress-circular :size="70" :width="7" color="primary" indeterminate></v-progress-circular>
            </v-card-text>

            <v-card-text v-else>
                <form @submit.prevent="descargar">

                    <v-layout row wrap>
                        <v-flex xs12 md5>
                            <v-select v-model="form.programa" label="Selecciona el tipo de cuenta a reportar"
                                :items="tipo_cuenta" solo required></v-select>
                        </v-flex>

                        <v-flex xs12 md3>
                            <v-select v-model="form.mes" label="Mes" :items="meses" item-value="id" item-text="nombre"
                                solo required></v-select>
                        </v-flex>

                        <v-flex xs12 md3>
                            <v-select v-model="form.anio" label="Año" :items="anios" solo required></v-select>
                        </v-flex>
                    </v-layout>


                    <v-card-actions>
                        <v-btn type="submit" color="success">Descargar</v-btn>
                    </v-card-actions>

                </form>
            </v-card-text>
        </v-card>
    </v-layout>
</template>
<script>
    import {
        descargarFile
    } from '../../../api/descargas';
    export default {

        created() {
            const year = new Date();
            this.anios = [
                year.getFullYear(),
                year.getFullYear() - 1,
            ]
        },

        data() {
            return {
                preload: false,
                anios: null,
                tipos_cuentas: [],
                tipo_cuenta: ['Riesgo Cardiovascular',
                    'Anticoagulados',
                    'Artritis',
                    'Coagulopatias',
                    'Hemofilia',
                    'Huerfanas',
                    'Quimioterapia',
                    'Respiratorio',
                    'Salud Mental',
                    'Vih'
                ],
                meses: [{
                        id: '1',
                        nombre: 'Enero'
                    },
                    {
                        id: '2',
                        nombre: 'Febrero'
                    },
                    {
                        id: '3',
                        nombre: 'Marzo'
                    },
                    {
                        id: '4',
                        nombre: 'Abril'
                    },
                    {
                        id: '5',
                        nombre: 'Mayo'
                    },
                    {
                        id: '6',
                        nombre: 'Junio'
                    },
                    {
                        id: '7',
                        nombre: 'Julio'
                    },
                    {
                        id: '8',
                        nombre: 'Agosto'
                    },
                    {
                        id: '9',
                        nombre: 'Septiembre'
                    },
                    {
                        id: '10',
                        nombre: 'Octubre'
                    },
                    {
                        id: '11',
                        nombre: 'Noviembre'
                    },
                    {
                        id: '12',
                        nombre: 'Diciembre'
                    },
                ],
                form: {
                    programa: null,
                    mes: null,
                    anio: null,
                },
                loading: false,
            }
        },

        methods: {
            /** Descarga un archivo fias */
            descargar() {
                this.loading = true;
                    axios({
                    method: 'post',
                    params: {
                        programa: this.form.programa,
                        mes: this.form.mes,
                        anio: this.form.anio,
                    },
                    url: '/api/fias/descargarCac',
                    responseType: 'blob'
                }).then(res => {
                        this.preload = false;
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `reporte.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.loading = false;
                    this.limpiar();
                }).catch (error => {
                    console.log(error.response);
                    this.loading = false
                    this.$alerError('Error al generar el reporte.');
                    this.limpiar();
                })
            },

            /** Limpia el formulario */
            limpiar() {
                this.form.programa = null
                this.form.mes = null
                this.form.anio = null
            },

            /** Valida que los tipo_fias y el departamento_id esten seleccionados */
            hayError() {
                if (!this.form.programa) {
                    this.$alerError('Todos los campos son necesarios.')
                    return true;
                }
                return false;
            },
        }
    }

</script>
