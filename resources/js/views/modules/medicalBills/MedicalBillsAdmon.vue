<template>
    <v-container fluid grid-list-xl>

        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-text>
                        <v-container class="pa-0">
                            <v-layout row wrap>
                                <v-container>
                                    <v-layout row wrap>
                                        <v-flex xs6>
                                            <v-autocomplete v-model="filters.prestadorId" :items="allProveedores"
                                                item-value="id" item-text="name" label="Nit-Prestador*" required
                                                @change="reload()">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs2>
                                            <v-menu ref="menu1" v-model="menu1" :close-on-content-click="false"
                                                :nudge-right="40" lazy transition="scale-transition" offset-y full-width
                                                max-width="290px" min-width="290px">
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field prepend-icon="calendar_today"
                                                        v-model="filters.fecha_inicio" label="Fecha inicio"
                                                        color="redvitalAzul" v-on="on" @change="reload()">
                                                    </v-text-field>
                                                </template>
                                                <v-date-picker color="primary" v-model="date" locale="es"
                                                    @change="reload()" no-title @input="menu1 = false">
                                                </v-date-picker>
                                            </v-menu>
                                        </v-flex>

                                        <v-flex xs2>
                                            <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40"
                                                lazy transition="scale-transition" offset-y full-width max-width="290px"
                                                min-width="290px">
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field prepend-icon="calendar_today"
                                                        v-model="filters.fecha_fin" label="Fecha final"
                                                        color="redvitalAzul" v-on="on" @change="reload()">
                                                    </v-text-field>
                                                </template>
                                                <v-date-picker color="primary" v-model="date1" locale="es"
                                                    @change="reload()" no-title @input="menu2 = false">
                                                </v-date-picker>
                                            </v-menu>
                                        </v-flex>

                                        <v-flex xs2>
                                            <v-btn color="info" @click="find()">Filtrar</v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-data-table :headers="headers" :items="listAcumuladoFacturas" class="elevation-1"
                        prev-icon="mdi-menu-left" next-icon="mdi-menu-right" sort-icon="mdi-menu-down">
                        <template v-slot:items="props">
                            <td>{{ props.item.Nombre }}</td>
                            <td>{{ props.item.Nit }}</td>
                            <td>{{ props.item.totalFacturas }}</td>
                            <td><strong> $ {{ props.item.totalNeto }}</strong></td>
                            <v-btn color="#00b297" :loading="loading" :disabled="loading" dark
                                @click="loadFacturas(props.item)">Asignar</v-btn>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>

        <v-dialog v-model="modalAsignarFactura" persistent max-width="1000px">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title>Asignacion de facturas</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-autocomplete :items="permisos" item-text="name" item-value="id"
                                    label="Selecciona el usuario" v-model="permiso_id" multiple></v-autocomplete>
                            </v-flex>
                            <v-flex xs12>
                                <v-card>
                                    <v-card-title>
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="searchFactura" append-icon="search" label="Buscar"
                                            single-line hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table class="elevation-1" v-model="selected" select-all :headers="facturas"
                                        :items="listFacturas" item-key="name" :search="searchFactura"
                                        :rows-per-page-items="[5,10,25,500,700]">
                                        <template v-slot:items="props">
                                            <td>
                                                <v-checkbox color="primary" :input-value="props.selected"
                                                    v-model="selected" :value="props.item" multiple hide-details>
                                                </v-checkbox>
                                            </td>
                                            <td>{{ props.item.numero_factura }}</td>
                                            <td class="text-xs-right">$ {{ props.item.valor_Neto }}</td>
                                            <td class="text-xs-right">
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn text icon color="deep-orange" dark v-on="on"
                                                            @click="show(props.item.numero_factura)">
                                                            <v-icon>file_copy</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Adjunto Factura</span>
                                                </v-tooltip>
                                            </td>
                                        </template>
                                        <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                            Your search for "{{ searchFactura }}" found no results.
                                        </v-alert>
                                    </v-data-table>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="clearAssign()">Cerrar</v-btn>
                    <v-btn color="info" @click="assign()">Asignar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

    </v-container>
</template>

<script>
    import moment from 'moment';
    import Swal from 'sweetalert2';
    import Widget from '../../../components/referencia/Widget.vue';
    import {
        PrestadorService,
        UserService,
        AdjuntoService
    } from '../../../services';

    moment.locale('es');

    export default {
        name: 'MedicalBillsAdmon',
        components: {
            Widget,
        },
        data: vm => ({
            loading: false,
            selected: [],
            listProveedores: [],
            listAcumuladoFacturas: [],
            listFacturas: [],
            modalAsignarFactura: false,
            date: new Date().toISOString().substr(0, 10),
            date1: new Date().toISOString().substr(0, 10),
            menu1: false,
            menu2: false,
            headers: [{
                    text: 'Nombre prestador',
                    align: 'left',
                    value: 'Nombre'
                },
                {
                    text: 'Nit',
                    value: 'Nit'
                },
                {
                    text: 'Total facturas',
                    value: 'totalFacturas'
                },
                {
                    text: 'Total valor neto',
                    value: 'totalNeto'
                },
                {
                    text: '',
                    value: ''
                }
            ],
            facturas: [{
                    text: '# Factura',
                    value: 'numero_factura'
                },
                {
                    text: 'Valor neto',
                    align: 'right'
                },
                {
                    text: '',
                    value: ''
                }
            ],
            filters: {
                prestadorId: "",
                fecha_inicio: new Date().toISOString().substr(0, 10),
                fecha_fin: new Date().toISOString().substr(0, 10)
            },
            searchFactura: '',
            preload: false,
            permisos: [],
            permiso_id: [],
            prestador: ''
        }),
        computed: {
            allProveedores() {
                return this.listProveedores.map((proveedor) => {
                    return {
                        id: proveedor.Prestador_id,
                        name: `${proveedor.nit} - ${proveedor.Nombre}`
                    }
                })
            }
        },
        watch: {
            date(val) {
                this.filters.fecha_inicio = this.date
            },
            date1(val) {
                this.filters.fecha_fin = this.date1
            }
        },
        mounted() {
            moment.locale('es');
            this.getPermisos();
            this.fetchProveedores();
        },
        methods: {
            reload() {
                this.listAcumuladoFacturas = []
            },
            getPermisos() {
                axios.get('/api/medicalBills/permisos').then(res => {
                    this.permisos = res.data
                });
            },
            toggleAll() {
                if (this.selected.length) this.selected = []
                else this.selected = this.desserts.slice()
            },
            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            },
            async fetchProveedores() {
                try {
                    this.listProveedores = await PrestadorService.getPrestadores();
                } catch (error) {
                    console.log(error)
                }
            },
            loadFacturas(proveedor) {
                this.preload = true;
                this.prestador = proveedor.Nit
                this.loading = true;
                axios.post('/api/sedeproveedore/facturaPrestador/' + proveedor.id, this.filters).then(res => {
                    this.listFacturas = res.data;
                    this.loading = false;
                    this.modalAsignarFactura = true;
                    this.preload = false;

                }).catch(e => {
                    console.log(e);
                    this.preload = false;
                });
            },
            find() {
                this.preload = true
                axios.post('/api/sedeproveedore/acumuladoPrestador', this.filters).then(res => {
                    this.listAcumuladoFacturas = res.data;
                    this.preload = false
                    if (this.listAcumuladoFacturas == "") {
                        this.$alertInfo('No tiene facturas en ese rango de fechas!');
                    }
                }).catch(e => {
                    this.preload = false
                    this.$alerError('Debe ingresar un prestador!');
                })
            },
            clearAssign() {
                this.permiso_id = []
                this.selected = []
                this.modalAsignarFactura = false
                this.searchFactura = ''
            },
            assign() {
                let formatDate = new FormData();
                for (let i = 0; i < this.selected.length; i++) {
                    formatDate.append('af_id[]', this.selected[i].id)
                }
                for (let i = 0; i < this.permiso_id.length; i++) {
                    formatDate.append('permiso_id[]', this.permiso_id[i])
                }
                axios.post('/api/medicalBills/assign', formatDate).then(res => {
                    this.$alerSuccess('Factura asignada con exito!');
                    this.clearAssign();
                    this.find();
                }).catch(err => {
                    this.$alerError('Debe ingresar todos los campos obligatorios!');
                })
            },
            async show(factura) {
                if (this.prestador == '' || factura == '') {
                    return;
                }
                let ruta = '/facturascuentasmedicas/' + this.prestador + '/' + factura + '.pdf';
                this.preload = true;
                try {
                    let adj = await AdjuntoService.get(ruta);
                    let blob = new Blob([adj[1]], {
                        type: adj[0]
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");
                    this.preload = false
                } catch (error) {
                    this.$alerError('El adjunto de la factura no existe!');
                    this.preload = false
                }
            },
        }
    }

</script>
