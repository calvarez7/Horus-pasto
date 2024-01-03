<template>
    <div>
        <v-dialog v-model="dialog" max-width="1400px">
            <v-card>
                <form @submit.prevent="guardarRevision">
                    <v-card-title class="success" style="color:white">
                        <span class="title layout justify-center ">CONTRATO</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-card-title style="color:black">
                                <span class="title layout justify-center ">INFORMACION GENERAL</span>
                            </v-card-title>
                            <v-layout wrap>
                                <v-flex xs4>
                                    <v-text-field type="date" v-model="contrato.fecha_ingreso" label="Fecha Ingreso">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-autocomplete :items="tipoContrato" v-model="contrato.tipo_contrato"
                                        label="Tipo de Contrato">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs4 v-show="contrato.tipo_contrato != 'Indefinido'">
                                    <v-text-field type="number" v-model="contrato.meses" label="Meses">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-autocomplete :items="tipoDedicacion" v-model="contrato.dedicacion"
                                        label="Dedicacion">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field type="number" v-model="contrato.horas" label="horas">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field type="date" v-model="contrato.horas" label="Fecha de Retiro">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-autocomplete :items="causaRetiro" v-model="contrato.causa_retiro"
                                        label="Causa de Retiro">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs4>
                                    <v-autocomplete :items="tipoVinculacion" v-model="contrato.tipo_vinculacion"
                                        label="Vinculacion">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs4>
                                    <v-autocomplete :items="jefeInmediato" v-model="contrato.jefe_inmediato"
                                        label="Jefe Inmediato">
                                    </v-autocomplete>
                                </v-flex>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-card-title style="color:black">
                                            <span class="title layout justify-center ">CARGOS</span>
                                        </v-card-title>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-autocomplete :items="tipoCargos" v-model="contrato.tipo_cargos"
                                            label="Cargos">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-autocomplete :items="tipoAreas" v-model="contrato.tipo_areas"
                                            label="Area de Servicio">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-autocomplete :items="tipoProfesion" v-model="contrato.tipo_profesion"
                                            label="Profesion">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-btn round color="primary" dark>Agregar</v-btn>
                                    </v-flex>
                                </v-layout>
                                <v-flex xs12>
                                    <v-data-table :headers="Cargo" :items="cargos" :search="search" class="elevation-1">
                                        <!-- <template v-slot:items="props">
                                        <td>{{ props.item.name }}</td>
                                        <td class="text-xs-right">
                                            {{ props.item.calories }}</td>
                                        <td class="text-xs-right">{{ props.item.fat }}
                                        </td>
                                        <td class="text-xs-right">{{ props.item.carbs }}
                                        </td>
                                        <td class="text-xs-right">
                                            {{ props.item.protein }}</td>
                                        <td class="text-xs-right">{{ props.item.iron }}
                                        </td>
                                    </template> -->
                                    </v-data-table>
                                    <!-- <div class="text-xs-center pt-2">
                                    <v-pagination v-model="pagination.page" :length="pages">
                                    </v-pagination>
                                </div> -->
                                </v-flex>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-card-title style="color:black">
                                            <span class="title layout justify-center ">INFORMACION SALARIAL</span>
                                        </v-card-title>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field type="number" v-model="contrato.salario"
                                            label="Valor">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-autocomplete :items="['SI', 'NO']" v-model="contrato.salario_integral"
                                            label="Salario Integral">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-btn round color="primary" dark>Agregar</v-btn>
                                    </v-flex>
                                </v-layout>
                                <v-flex xs12>
                                    <v-data-table :headers="Cargo" :items="cargos" :search="search" class="elevation-1">
                                        <!-- <template v-slot:items="props">
                                        <td>{{ props.item.name }}</td>
                                        <td class="text-xs-right">
                                            {{ props.item.calories }}</td>
                                        <td class="text-xs-right">{{ props.item.fat }}
                                        </td>
                                        <td class="text-xs-right">{{ props.item.carbs }}
                                        </td>
                                        <td class="text-xs-right">
                                            {{ props.item.protein }}</td>
                                        <td class="text-xs-right">{{ props.item.iron }}
                                        </td>
                                    </template> -->
                                    </v-data-table>
                                    <!-- <div class="text-xs-center pt-2">
                                    <v-pagination v-model="pagination.page" :length="pages">
                                    </v-pagination>
                                </div> -->
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </form>
            </v-card>
        </v-dialog>
        <v-tabs centered color="primary" dark icons-and-text>
            <v-tabs-slider color="yellow"></v-tabs-slider>

            <v-tab href="#tab-1" @click="all('1')">
                Pendientes Por Contrato
                <v-icon>account_box</v-icon>
            </v-tab>

            <v-tab href="#tab-2" @click="all('2')">
                Contratados
                <v-icon>account_box</v-icon>
            </v-tab>

            <v-tab href="#tab-3" @click="all('3')">
                Retirados
                <v-icon>account_box</v-icon>
            </v-tab>

            <v-tab-item v-for="i in 3" :key="i" :value="'tab-' + i">
                <v-card flat>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                    </v-text-field>
                    <template>
                        <v-data-table :headers="Empleados" :items="dataEmpleado" class="elevation-1" :search="search">
                            <template v-slot:items="props" activator="{ on }">
                                <td>{{ props.item.id }}</td>
                                <td class="text-xs-left">{{ props.item.tipo_documento}}</td>
                                <td class="text-xs-left">{{ props.item.Nombre}}</td>
                                <td class="text-xs-left">{{ props.item.Documento }}</td>
                                <td class="text-xs-left">{{ props.item.Area }}</td>
                                <td class="text-xs-left">{{ props.item.correo }}</td>
                                <td class="text-xs-left">{{ props.item.celular }}</td>
                                <td>
                                    <v-btn color="primary" @click="informacionMostrar(props.item)">
                                        Contratar</v-btn>
                                </td>
                            </template>
                        </v-data-table>
                    </template>
                </v-card>
            </v-tab-item>
        </v-tabs>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tipoContrato: ['Fijo', 'Indefinido', 'Obra Labor'],
                contrato: [],
                cargos: [],
                tipoCargos: [],
                tipoAreas: [],
                tipoProfesion: [],
                jefeInmediato:[],
                tipoDedicacion: ['Tiempo Completo', 'Medio Tiempo', 'Tiempo Parcial', 'Otra Dedicacion'],
                causaRetiro: ['Terminacion Obra', 'Justa Causa Empleador', 'Justa Causa Trabajador',
                    'Clausura Definitiva',
                    'Cese Actividad', 'Abandono Cargo', 'Declaración Insubsistencia', 'Sentencia', 'Revocatoria',
                    'Jubilacion', 'Renuncia', 'Mutuo Acuerdo', 'Muerte Natural', 'Muerte Accidente Laboral',
                    'Destitucion', 'Regreso Titular', 'Reorganizacion', 'Termino Comision', 'Termino Estudio',
                    'Razón Seguridad Nacional', 'Detencion Preventiva', 'Traslado Interinstitucional',
                    'Decision Unilateral Con JustaCausa', 'Decision Unilateral Sin Justa Causa'
                ],
                tipoVinculacion: ['Libre Nombramiento', 'Trabajador Oficial', 'Empleado Publico Carrera',
                    'Empleado Publico Provisional', 'Supernumerario', 'Nombramiento Provisional',
                    'Prestacion Servicio',
                    'Empleado Publico Derecho', 'Jubilado', 'Cooperativo Fijo', 'Cooperativa Variable',
                    'Comision Administracion Publica', 'Periodo Prueba', 'Aprendiz Sena Lectiva',
                    'Aprendiz Sena Productiva', 'Contrato Individual', 'Empleado Temporal'
                ],
                Empleados: [{
                        text: 'ID Registro',
                    },

                    {
                        text: 'Nombre del Empleado',
                    },
                    {
                        text: 'CEDULA',
                    },
                    {
                        text: 'Cargo',
                    },

                    {
                        text: 'Fecha',
                    },
                    {
                        text: 'Acciones',
                    },
                ],
                Cargo: [{
                        text: 'ID Registro',
                    },

                    {
                        text: 'Cargo',
                    },
                    {
                        text: 'Area de Servicio',
                    },
                    {
                        text: 'Profesion',
                    },

                    {
                        text: 'Estado',
                    },
                ],
                dataEmpleado: [],
                dialog: false,
                search: '',

            }
        },
        methods: {

            all(tipo = "1") {
                axios.get('/api/empleados/fethPendientes/').then(res => {
                    this.dataEmpleado = res.data;
                });
            },
            guardarRevision() {
                this.preload = true;
                this.revision.sarlafs_id = this.idSarlaft
                axios.post('/api/sarlaft/guardarRevision/', this.revision)
                    .then(res => {
                        this.guardarAdjuntosrevision(res.data.idRevision)
                        this.$alerSuccess("Revision guardada con exito!");
                        this.preload = false;
                    }).catch(error => {
                        this.preload = false;
                        this.$alerError("Error al guardar!");
                    });

            },
            adjuntosSarlafts() {
                axios.get('/api/sarlaft/adjuntosSarlafts/' + this.idSarlaft).then(res => {
                    this.adjuntos = res.data;
                })

            },
            adjuntosRevision(idRevision) {
                axios.get('/api/sarlaft/adjuntosRevision/' + idRevision).then(res => {
                    this.adjuntoRevision = res.data;
                })
            },
            guardarAdjuntosrevision(idRevision) {
                this.preload = true;
                const data = this.$refs.adjuntoRevision.files
                let formData = new FormData();
                for (let i = 0; i < data.length; i++) {
                    formData.append(`adjuntoRevision[]`, data[i]);
                }


                axios.post('/api/sarlaft/guardarAdjuntosrevision/' + idRevision, formData).then(res => {
                    this.preload = false;
                    this.$alerSuccess("Adjuntos guardados con exito!");

                }).catch(error => {
                    this.preload = false;
                    this.$alerError("Error al guardar!");
                });
            },
            updateRevsion() {
                axios.put('/api/sarlaft/updateRevision/' + this.idSarlaft)
                    .then(res => {
                        this.$alerSuccess("Se Cerro la revision con exito!");
                        this.preload = false;
                        this.dialog = false;
                    }).catch(error => {
                        this.preload = false;
                        this.$alerError("Error al guardar!");
                        this.dialog = false;
                    });
                this.all("Vinculacion Primera Vez");

            },
            envioCorreo() {
                axios.post('/api/sarlaft/envioCorreo/' + this.adjuntos).then(res => {})
            },
            informacionMostrar(item) {
                this.dialog = true;

            },

            show() {
                this.dialog = true
            },
            async imprimir(id) {
                const pdf = {
                    type: 'Sarlafts',
                    id: id
                }
                axios
                    .post("/api/pdf/print-pdf", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    })
                    .catch(err => console.log(err.response));
            }
        },
        mounted() {
            this.all();
        }
    }

</script>
