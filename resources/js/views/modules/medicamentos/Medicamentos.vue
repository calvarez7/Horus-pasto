<template>
    <v-card>
        <v-container pa-1>
            <v-container pt-3 pb-1>
                <v-layout row>
                    <v-dialog v-model="dialog" max-width="600px">
                        <v-card>
                            <v-card-title>
                                <span v-if="save" class="headline">Crear Medicamento</span>
                                <span v-else class="headline">Editar Medicamento</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Principio Activo*" v-model="data.Principio_Activo"
                                                required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Titular" v-model="data.Titular" required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Registro Sanitario" v-model="data.Registro_Sanitario"
                                                required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Descripción Comercial*"
                                                v-model="data.Descripcion_Comercial" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Atc*" v-model="data.Atc" required></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                                <v-btn v-if="save" color="blue darken-1" flat @click="saveMedicamento()">Guardar</v-btn>
                                <v-btn v-else color="blue darken-1" flat @click="updateMedicamento()">Actualizar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-layout>
            </v-container>
            <v-card-title>
                <v-flex xs12 sm6 md2>
                    <v-text-field type="date" v-model="fecha.fecha_inicio" label="Fecha inicial">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-text-field type="date" v-model="fecha.fecha_fin" label="Fecha Final">
                    </v-text-field>
                </v-flex>
                <v-btn round color="primary" dark @click="exportVademecum()">Descargar</v-btn>
                <!-- <v-btn round @click="createMedicamento()"  color="primary" dark><v-icon left>person_add</v-icon>Nueva Medicamento</v-btn> -->
                <v-spacer></v-spacer>
                <v-flex xs1 sm5>
                    <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line hide-details>
                    </v-text-field>
                </v-flex>
            </v-card-title>
            <v-data-table :headers="headers" :items="detalleMedicamento" :search="search"
                rowsPerPageText="Filas por página">
                <template v-slot:items="props">
                    <tr>
                        <td>{{ props.item.id }}</td>
                        <td>{{ props.item.NombreSumi }}</td>
                        <td>{{ props.item.CodigoSumi }}</td>
                        <td>{{ props.item.CUM_completo }}</td>
                        <td>{{ props.item.Principio_Activo }}</td>
                        <td class="text-xs-center">{{ props.item.Nivel_Ordenamiento }}</td>
                        <td class="text-xs-center">{{ props.item.estadoNombre }}</td>
                        <td>{{ props.item.Titular }}</td>
                        <td>{{ props.item.Registro_Sanitario }}</td>
                        <td>{{ props.item.Cantidad_Cum }}</td>
                        <td>{{ props.item.Descripcion_Comercial }}</td>
                        <td>{{ props.item.Atc }}</td>
                        <td>
                            <v-icon v-if="props.item.colorMed == 'LASA - AMARILLO'" large color="yellow">mdi-pill
                            </v-icon>
                            <v-icon v-else-if="props.item.colorMed == 'LASA - VERDE'" large color="green">mdi-pill
                            </v-icon>
                            <v-icon v-else-if="props.item.colorMed == 'MAXIMA ALERTA'" large color="red">mdi-pill
                            </v-icon>
                            <v-icon v-else-if="props.item.colorMed == 'LASA-AZUL'" large color="blue">mdi-pill</v-icon>

                        </td>
                        <td class="text-xs-right">
                            <v-btn v-if="can('medicamento.edit')" fab outline color="warning" small
                                @click="editMedicamento(props.item)">
                                <v-icon>edit</v-icon>
                            </v-btn>
                        </td>
                    </tr>
                </template>
                <template v-slot:no-results :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </template>
            </v-data-table>
        </v-container>
        <v-dialog v-model="preload_datos" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-card>

</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    export default {
        name: 'Medicamentos',
        data() {
            return {
                preload_datos: false,
                loadingVademecum: false,
                expand: false,
                grupos: [],
                subGrupos: [],
                codigosSumi: [],
                detalleMedicamento: [],
                search: '',
                headers: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre sumi',
                        align: 'left',
                        value: 'NombreSumi'
                    },
                    {
                        text: 'Código sumi',
                        align: 'left',
                        value: 'CodigoSumi'
                    },
                    {
                        text: 'CUM',
                        align: 'left',
                        value: 'CUM_completo'
                    },
                    {
                        text: 'Principio_Activo',
                        align: 'left',
                        value: 'Principio_Activo'
                    },
                    {
                        text: 'Nivel_Ordenamiento',
                        align: 'left',
                        value: 'Nivel_Ordenamiento'
                    },
                    {
                        text: 'Estado',
                        align: 'left',
                        value: 'estadoNombre'
                    },
                    {
                        text: 'Titular',
                        align: 'left',
                        value: 'Titular'
                    },
                    {
                        text: 'Registro Sanitario',
                        align: 'left',
                        value: 'Registro_Sanitario'
                    },
                    {
                        text: 'Cantidad Cum',
                        align: 'left',
                        value: 'Cantidad_Cum'
                    },
                    {
                        text: 'Descripción Comercial',
                        align: 'left',
                        value: 'Descripcion_Comercial'
                    },
                    {
                        text: 'Atc',
                        align: 'left',
                        value: 'Atc'
                    },
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: ''
                    }
                ],
                save: true,
                subSave: true,
                dialog: false,
                subDialog: false,
                data: {
                    Principio_Activo: '',
                    Titular: '',
                    Registro_Sanitario: '',
                    Descripcion_Comercial: '',
                    Atc: '',
                },
                fecha: {
                    fecha_inicio: '',
                    fecha_fin: '',
                },
            }
        },
        computed: {
            ...mapGetters(['can'])
        },
        mounted() {
            this.fetchMedicamentos();
            this.fetchSubgrupos();
            this.fetchCodigosSumi();
        },
        methods: {
            fetchMedicamentos() {
                axios.get('/api/detallearticulo/all')
                    .then(res => this.detalleMedicamento = res.data);
            },
            fetchSubgrupos() {
                axios.get('/api/subgrupo/enabled')
                    .then(res => this.subGrupos = res.data);
            },
            fetchCodigosSumi() {
                axios.get('/api/codesumi/all')
                    .then(res => this.codigosSumi = res.data);
            },
            createMedicamento() {
                this.emptyData();
                this.save = true;
                this.dialog = true;
            },
            showError(message) {
                swal({
                    title: "¡No puede ser!",
                    text: `${message.name}`,
                    icon: "warning",
                });
            },
            getDetalle(Medicamento_id) {
                // console.log('entra')
                axios.get(`/api/detallearticulo/detalleMedicamento`, {
                        params: {
                            Medicamento_id
                        }
                    })
                    .then(res => this.detalleMedicamento = res.data)
                    .catch(err => this.showError(err.response.data))
            },
            emptyData() {
                this.data = {
                    Subgrupo_id: '',
                    Producto: '',
                    Titular: '',
                    Registro_Sanitario: '',
                    Invima: '',
                    Fecha_Expedicion: '',
                    Fecha_Vencimiento: '',
                    Estado_Registro: '',
                    Expediente_Cum: '',
                    Consecutivo: '',
                    Cantidad_Cum: '',
                    Descripción_Comercial: '',
                    Estado_Cum: '',
                    Fecha_Activo: '',
                    Fecha_Inactivo: '',
                    Muestra_Medica: '',
                    Unidad: '',
                    Atc: '',
                    Descripcion_Atc: '',
                    Via_Administracion: '',
                    Concentracion: '',
                    Principio_Activo: '',
                    Unidad_Medida: '',
                    Cantidad: '',
                    Unidad_Referencia: '',
                    Forma_Farmaceutica: '',
                    Cum_Validacion: '',
                    CUM_completo: '',
                    Activo_HORUS: '',
                    Regulado: '',
                    Precio_maximo: '',
                    Unidad_aux: '',
                    POS: '',
                    Alto_Costo: '',
                    Acuerdo_228: '',
                    Nivel_Ordenamiento: '',
                    Requiere_autorizacion: '',
                    Frecuencia: '',
                    Nombresumi: '',
                }
            },
            editMedicamento(medicamento) {
                this.data = medicamento;
                this.save = false;
                this.dialog = true;
            },
            editDetalle(detalle) {
                this.data = detalle;
                this.subSave = false;
                this.subDialog = true;
            },
            updateMedicamento() {
                axios.put(`/api/detallearticulo/edit/${this.data.id}`, this.data)
                    .then(res => {
                        this.emptyData();
                        this.subDialog = false;
                        this.dialog = false;
                        this.fetchMedicamentos();
                        swal({
                            title: "¡Detalle Actualizado!",
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => console.log(err.response.data));
            },

            exportVademecum() {
                this.preload_datos = true
                axios.post('/api/detallearticulo/exportVademecum', this.fecha, {
                    responseType: "arraybuffer"
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `Reporte_Compras_${this.fecha.fecha_inicio}_${this.fecha.fecha_fin}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.preload_datos = false;
                    this.clearFilter();
                });
            }
        },
    }

</script>

<style lang="scss" scoped>

</style>
