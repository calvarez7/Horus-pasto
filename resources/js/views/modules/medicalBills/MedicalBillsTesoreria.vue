<template>
    <div>
        <v-card height="55px">
            <v-bottom-nav :active.sync="bottomNav" :value="true" absolute>
                <v-btn color="primary" flat @click="relacionPagos = true, estadoCuenta = false, carguePagos = false">
                    <span>Relación de Pagos</span>
                    <v-icon>chrome_reader_mode</v-icon>
                </v-btn>

                <v-btn color="primary" flat @click="estadoCuenta = true, relacionPagos = false, carguePagos = false">
                    <span>Estado de Cuenta</span>
                    <v-icon>assessment</v-icon>
                </v-btn>

                <v-btn color="primary" flat @click="carguePagos = true, estadoCuenta = false, relacionPagos = false" v-if="can('cuentasmedicas.cargapagos')">
                    <span>Carga Relación Pagos</span>
                    <v-icon>inventory</v-icon>
                </v-btn>
            </v-bottom-nav>
        </v-card>

        <v-card v-show="relacionPagos">
            <v-card-title>
                <v-layout row wrap>
                    <v-flex xs4 md4 sm4 px-2>
                        <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40" lazy
                            transition="scale-transition" offset-y full-width min-width="290px">
                            <template v-slot:activator="{ on }">
                                <v-text-field v-model="fechaDesde" label="Fecha" prepend-icon="event" readonly
                                    v-on="on">
                                </v-text-field>
                            </template>
                            <v-date-picker locale="es" color="success" v-model="fechaDesde" type="month"
                                @input="menu2 = false">
                            </v-date-picker>
                        </v-menu>
                    </v-flex>
                    <v-flex xs1 md1 sm1 px-2>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn fab color="success" small v-on="on" @click="searchPagosPrestador">
                                    <v-icon>search</v-icon>
                                </v-btn>
                            </template>
                            <span>Filtrar</span>
                        </v-tooltip>
                    </v-flex>
                </v-layout>
                <v-spacer></v-spacer>
                <v-text-field v-model="searchCarga" append-icon="search" label="Buscar" single-line hide-details>
                </v-text-field>
            </v-card-title>
            <v-data-table :search="searchCarga" :headers="headersCarga" :items="pagoCargadosPrestador"
                class="elevation-1">
                <template v-slot:items="props">
                    <td class="text-xs-left">{{ props.item.id }}</td>
                    <td class="text-xs-left">{{ props.item.nombre }}</td>
                    <td class="text-xs-left">{{ props.item.fecha }}</td>
                    <td class="justify-left layout px-0">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="primary" dark v-on="on" :disabled="search_adjunto"
                                    @click="consultarAdjunto(props.item)">
                                    <v-icon>unarchive</v-icon>
                                </v-btn>
                            </template>
                            <span>Descargar</span>
                        </v-tooltip>
                    </td>
                </template>
            </v-data-table>
        </v-card>

        <v-card v-show="estadoCuenta">
            <v-card-title>
                <v-flex xs3 md3 sm3 px-2>
                    <v-text-field label="Fecha de Corte" v-model="fechaCorte" type="date"></v-text-field>
                </v-flex>
                <v-flex xs1 md1 sm1 px-2>
                    <v-btn color="success darken-1" @click="generarEstadoCuenta">Generar</v-btn>
                </v-flex>
            </v-card-title>
        </v-card>

        <v-card v-show="carguePagos">
            <v-layout row wrap>
                <v-flex xs6 md6 sm6 px-2>
                    <v-autocomplete v-model="prestador" :items="allProveedores" item-value="id" item-text="name"
                        label="Nit-Prestador" autocomplete="off">
                    </v-autocomplete>
                </v-flex>
                <v-flex xs2 md2 sm2 px-2>
                    <v-menu v-model="menu" :close-on-content-click="false" :nudge-right="40" lazy
                        transition="scale-transition" offset-y full-width min-width="290px">
                        <template v-slot:activator="{ on }">
                            <v-text-field v-model="fechaDesdeCarga" label="Fecha" prepend-icon="event" readonly
                                v-on="on">
                            </v-text-field>
                        </template>
                        <v-date-picker locale="es" color="success" v-model="fechaDesdeCarga" type="month"
                            @input="menu = false">
                        </v-date-picker>
                    </v-menu>
                </v-flex>
                <v-flex xs1 md1 sm1 px-2>
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn fab color="success" small v-on="on" @click="searchPagos">
                                <v-icon>search</v-icon>
                            </v-btn>
                        </template>
                        <span>Filtrar</span>
                    </v-tooltip>
                </v-flex>
            </v-layout>
            <v-card-title>
                <v-tooltip top>
                    <template v-slot:activator="{ on }">
                        <v-btn text icon color="primary" dark v-on="on" @click="dialogAdjunto = true">
                            <v-icon>add</v-icon>
                        </v-btn>
                    </template>
                    <span>Cargar Adjunto</span>
                </v-tooltip>
                <v-spacer></v-spacer>
                <v-text-field v-model="searchCarga" append-icon="search" label="Buscar" single-line hide-details>
                </v-text-field>
            </v-card-title>
            <v-data-table :search="searchCarga" :items="pagoCargados" :headers="headersCarga" class="elevation-1">
                <template v-slot:items="props">
                    <td class="text-xs-left">{{ props.item.id }}</td>
                    <td class="text-xs-left">{{ props.item.nombre }}</td>
                    <td class="text-xs-left">{{ props.item.fecha }}</td>
                    <td class="justify-left layout px-0">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="primary" dark v-on="on" :disabled="search_adjunto"
                                    @click="consultarAdjunto(props.item)">
                                    <v-icon>unarchive</v-icon>
                                </v-btn>
                            </template>
                            <span>Descargar</span>
                        </v-tooltip>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="error" dark v-on="on" :disabled="search_adjunto"
                                    @click="deleteAdjunto(props.item)">
                                    <v-icon>delete</v-icon>
                                </v-btn>
                            </template>
                            <span>Eliminar</span>
                        </v-tooltip>
                    </td>
                </template>
            </v-data-table>

            <v-dialog v-model="dialogAdjunto" v-if="dialogAdjunto" persistent width="700">
                <v-card>
                    <v-toolbar flat color="primary" dark>
                        <v-toolbar-title>
                            Carga Adjunto Relación Pagos
                        </v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-autocomplete v-model="prestadorCarga" :items="allProveedores" item-value="id"
                                        item-text="name" label="Nit-Prestador" autocomplete="off">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs6>
                                    <v-menu v-model="menu3" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <v-text-field v-model="fechaDesdeAdjunto" label="Fecha" prepend-icon="event"
                                                readonly v-on="on">
                                            </v-text-field>
                                        </template>
                                        <v-date-picker locale="es" color="success" v-model="fechaDesdeAdjunto"
                                            type="month" @input="menu3 = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex xs12>
                                    <input id="adjuntos" multiple ref="adjuntos" type="file" />
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="clearDialogAdjunto">
                            Cerrar
                        </v-btn>
                        <v-btn color="success" @click="cargaPagos">
                            Guardar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

        </v-card>

        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

    </div>
</template>

<script>
    import { mapGetters } from 'vuex' 
    import {
        PrestadorService
    } from '../../../services';
    import {
        AdjuntoService
    } from '../../../services';
    export default {
        data() {
            return {
                relacionPagos: true,
                estadoCuenta: false,
                carguePagos: false,
                searchCarga: '',
                fechaDesde: '',
                fechaDesdeCarga: '',
                fechaDesdeAdjunto: '',
                fechaCorte: new Date().toISOString().substr(0, 10),
                listProveedores: [],
                prestador: '',
                menu: false,
                menu2: false,
                menu3: false,
                bottomNav: 0,
                dialogAdjunto: false,
                adjuntos: [],
                prestadorCarga: '',
                preload: false,
                pagoCargados: [],
                search_adjunto: false,
                pagoCargadosPrestador: [],
                headersCarga: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'nombre'
                    },
                    {
                        text: 'Fecha',
                        align: 'left',
                        value: 'fecha'
                    },
                    {
                        text: '',
                        align: 'left'
                    }
                ],
            }
        },

        computed: {
            ...mapGetters(['can']),
            allProveedores() {
                return this.listProveedores.map((proveedor) => {
                    return {
                        id: proveedor.Prestador_id,
                        name: `${proveedor.nit} - ${proveedor.Nombre}`
                    }
                })
            }
        },

        mounted() {
            this.fetchProveedores();
        },

        methods: {
            async fetchProveedores() {
                try {
                    this.listProveedores = await PrestadorService.getPrestadores();
                } catch (error) {
                    console.log(error)
                }
            },
            searchPagosPrestador() {
                if (this.fechaDesde == '') {
                    this.$alerError("Debe seleccionar una fecha.");
                    return;
                }
                this.preload = true
                axios.post(`/api/medicalBills/searchCargaPagosPrestador`, {
                        fecha: this.fechaDesde
                    })
                    .then(res => {
                        this.preload = false
                        this.pagoCargadosPrestador = res.data
                    }).catch(err => {
                        this.$alerError(err.response.data.message);
                        this.preload = false
                    })
            },
            searchPagos() {
                if (this.fechaDesdeCarga == '') {
                    this.$alerError("Debe seleccionar una fecha.");
                    return;
                }
                if (this.prestador == '') {
                    this.$alerError("Debe seleccionar un prestador.");
                    return;
                }
                let data = {
                    fecha: this.fechaDesdeCarga,
                    prestador_id: this.prestador
                }
                this.preload = true
                axios.post(`/api/medicalBills/searchCargaPagos`, data)
                    .then(res => {
                        this.preload = false
                        this.pagoCargados = res.data
                    }).catch(err => {
                        this.preload = false
                    })
            },
            clearDialogAdjunto() {
                this.dialogAdjunto = false
                this.$refs.adjuntos.value = ''
                this.fechaDesdeAdjunto = ''
                this.prestadorCarga = ''
            },
            cargaPagos() {
                this.adjuntos = this.$refs.adjuntos.files
                if (this.adjuntos.length <= 0) {
                    this.$alerError("Debe seleccionar un adjunto como minimo.");
                    return;
                }
                if (this.fechaDesdeAdjunto == '') {
                    this.$alerError("Debe seleccionar una fecha.");
                    return;
                }
                if (this.prestadorCarga == '') {
                    this.$alerError("Debe seleccionar un prestador.");
                    return;
                }
                this.preload = true
                let formData = new FormData();
                formData.append('prestador_id', this.prestadorCarga)
                formData.append('fecha', this.fechaDesdeAdjunto)
                for (let i = 0; i < this.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.adjuntos[i]);
                }
                axios.post(`/api/medicalBills/cargaPagos`, formData)
                    .then(res => {
                        this.preload = false
                        this.clearDialogAdjunto()
                        swal({
                            title: "¡Carga de adjunto relación pagos exitosa!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    }).catch(err => {
                        this.preload = false
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        swal({
                            title: msg,
                            text: " ",
                            type: "error",
                            icon: "error",
                        });
                    })
            },
            async consultarAdjunto(item) {
                this.search_adjunto = true
                try {
                    let adj = await AdjuntoService.get(item.ruta);
                    let blob = new Blob([adj[1]], {
                        type: adj[0]
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');
                    a.download = `${item.nombre}`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.search_adjunto = false
                } catch (error) {
                    this.search_adjunto = false
                }
            },
            deleteAdjunto(item) {
                this.search_adjunto = true
                axios.get('/api/medicalBills/deletePagosPrestador/' + item.id).then(res => {
                    this.searchPagos();
                    this.alerSuccess('Adjunto relación pagos eliminado con exito!');
                    this.search_adjunto = false
                }).catch(err => {
                    this.search_adjunto = false
                })
            },
            generarEstadoCuenta() {
                this.preload = true
                axios({
                    method: 'post',
                    url: '/api/medicalBills/generarEstadoCuenta',
                    responseType: 'blob',
                    params: {
                        fecha: this.fechaCorte
                    }
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');
                    a.download = 'EstadodeCuenta';
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.preload = false
                }).catch(err => {
                    this.$alerError(err.response.data.message);
                    this.preload = false
                })
            }
        }
    }

</script>
