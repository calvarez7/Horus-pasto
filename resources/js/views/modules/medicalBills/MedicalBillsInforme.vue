<template>
    <div>

        <v-container fluid grid-list-xl>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card>
                        <v-toolbar color="primary" dark flat>
                            <v-flex xs12 text-xs-center>
                                <v-toolbar-title>Filtros</v-toolbar-title>
                            </v-flex>
                        </v-toolbar>
                        <v-card-text>
                            <v-container class="pa-0">
                                <v-layout row wrap>
                                    <v-flex xs3>
                                        <v-menu ref="menu1" v-model="menu1" :close-on-content-click="false"
                                            :nudge-right="40" lazy transition="scale-transition" offset-y full-width
                                            min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field v-model="dateDesde" label="Fecha Desde"
                                                    prepend-icon="event" readonly v-on="on"></v-text-field>
                                            </template>
                                            <v-date-picker color="primary" locale="es" v-model="dateDesde" no-title
                                                @input="menu1 = false">
                                            </v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false"
                                            :nudge-right="40" lazy transition="scale-transition" offset-y full-width
                                            min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field v-model="dateHasta" label="Fecha Hasta"
                                                    prepend-icon="event" readonly v-on="on"></v-text-field>
                                            </template>
                                            <v-date-picker color="primary" locale="es" v-model="dateHasta" no-title
                                                @input="menu2 = false">
                                            </v-date-picker>
                                        </v-menu>
                                    </v-flex>

                                    <v-flex xs5 sm5 class="text-xs-right">
                                        <VAutocomplete :items="tipos" v-model="tipo" label="Tipo" single-line
                                            hide-details />
                                    </v-flex>

                                    <v-flex xs8 sm8 class="text-xs-right" v-show="pAdmin">
                                        <VAutocomplete :items="allProveedores" item-value="id" item-text="name"
                                            label="Nit-Prestador" v-model="prestador" single-line hide-details />
                                    </v-flex>

                                    <v-flex xs1 sm1>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn text icon color="error" dark v-on="on">
                                                    <v-icon @click="clearFields()">clear</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Limpiar</span>
                                        </v-tooltip>
                                    </v-flex>

                                    <v-flex xs12 sm12 class="text-xs-right">
                                        <v-btn color="success" @click="generarInforme()">Generar</v-btn>
                                    </v-flex>

                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs12 v-show="acta">
                    <v-card>
                        <v-card-title>
                            ACTAS
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details>
                            </v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="actas" :search="search">
                            <template v-slot:items="props">
                                <td>{{ props.item.id }}</td>
                                <td>{{ props.item.nombre }}</td>
                                <td>{{ props.item.created_at.split('.')[0] }}</td>
                                <v-btn color="primary" dark @click="showActa(props.item.ruta)">Ver</v-btn>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>

            </v-layout>
        </v-container>

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
    import moment from 'moment';
    import {
        PrestadorService
    } from '../../../services';
    moment.locale('es');
    export default {
        name: 'Eventoinforme',
        data: () => ({
            dateDesde: moment().format('YYYY-MM-DD'),
            dateHasta: moment().format('YYYY-MM-DD'),
            menu1: false,
            menu2: false,
            preload: false,
            tipo: '',
            listProveedores: [],
            prestador: '',
            actas: [],
            acta: false,
            search: '',
            headers: [{
                    text: "id",
                    align: "left",
                    sortable: false
                },
                {
                    text: "Nombre",
                    align: "left",
                    value: "nombre",
                    sortable: false
                },
                {
                    text: "Fecha Conciliación",
                    align: "left",
                    value: "created_at",
                    sortable: true
                },
                {
                    text: "",
                    align: "left",
                    sortable: false
                },
            ],
            permisoInforme: [],
            pAdmin: false
        }),
        mounted() {
            this.fetchProveedores();
            moment.locale('es');
            this.getPermiso();
        },
        computed: {
            allProveedores() {
                return this.listProveedores.map((proveedor) => {
                    return {
                        id: proveedor.Prestador_id,
                        name: `${proveedor.nit} - ${proveedor.Nombre}`
                    }
                })
            },
            tipos() {
                let permisoAdmin = ['cuentasmedicas.admininforme']
                let tipo = ['Glosas'];
                let tipoAdmin = ['Contabilidad', 'Conciliación', 'Trazabilidad', 'No acuerdo', 'Actas'];
                let findPermission = []
                this.permisoInforme.forEach(p => {
                    let finded = permisoAdmin.find(pa => pa == p.name)
                    if (finded) findPermission.push(finded)
                });
                if (findPermission.length == 1) {
                    this.pAdmin = true
                    return tipoAdmin
                } else {
                    this.pAdmin = false
                    return tipo
                }
            }
        },
        methods: {
            getPermiso() {
                axios.get('/api/medicalBills/permisosCmedicas')
                    .then(res => {
                        this.permisoInforme = res.data;
                    });
            },
            async fetchProveedores() {
                try {
                    this.listProveedores = await PrestadorService.getPrestadores();
                } catch (error) {
                    console.log(error)
                }
            },
            showActa(ruta) {
                window.open(ruta, "_blank");
            },
            clearFields() {
                this.acta = false
                this.prestador = ''
                this.tipo = ''
                this.dateDesde = moment().format('YYYY-MM-DD')
                this.dateHasta = moment().format('YYYY-MM-DD')
            },
            generarInforme() {
                if (this.tipo == '') {
                    this.$alerError('Debe seleccionar un tipo de informe!');
                    return
                }
                if (this.tipo == 'Conciliación' || this.tipo == 'No acuerdo' ||
                    this.tipo == 'Actas') {
                    if (this.prestador == '') {
                        this.$alerError('Debe seleccionar un prestador!');
                        return
                    }
                }
                if (this.tipo == 'Actas') {
                    let data = {
                        prestador: this.prestador,
                        fechaDesde: this.dateDesde,
                        fechaHasta: this.dateHasta
                    }
                    axios.post('/api/medicalBills/actas', data).then(res => {
                        this.actas = res.data
                        if (this.actas) {
                            this.acta = true
                        }
                    })
                } else {
                    this.acta = false
                    this.preload = true
                    axios({
                        method: 'post',
                        params: {
                            prestador_id: this.prestador,
                            tipo: this.tipo,
                            fechaDesde: this.dateDesde,
                            fechaHasta: this.dateHasta
                        },
                        url: '/api/medicalBills/informe',
                        responseType: 'blob'
                    }).then(res => {
                        this.preload = false
                        let blob = new Blob([res.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                        });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement('a');
                        a.download = 'InformeCuentasMedicas' + this.tipo + '.xlsx';
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    }).catch(err => {
                        this.preload = false
                        console.log(err)
                    })
                }
            }
        }

    }

</script>
