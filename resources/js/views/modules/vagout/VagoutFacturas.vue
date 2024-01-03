<template>
    <v-container fluid grid-list-xl>
        <v-layout row wrap>
            <template>
                <div class="text-center">
                    <v-dialog v-model="preload" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Tranquilo procesamos tu solicitud !
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </div>
            </template>
            <v-dialog v-model="dialog" persistent max-width="600px">
                <v-card>
                    <v-toolbar flat color="primary" dark>
                        <v-toolbar-title>Cobro de Factura</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout row wrap>
                                <v-flex xs12 class="text-xs-center">
                                    <div class="subheading">
                                        <h4>Fecha de Cobro</h4>
                                    </div>
                                    <v-date-picker v-model="datosFactura.fecha_cobro" locale="es" color="primary"
                                        scrollable>
                                    </v-date-picker>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-textarea box name="input-7-4" label="Observación"
                                        v-model="datosFactura.observacion_final"></v-textarea>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="dialog = false">Cerrar</v-btn>
                        <v-btn color="success" @click="saveDate">Finalizar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>


            <v-dialog v-model="dialogAnular" persistent max-width="600px">
                <v-card>
                    <v-toolbar flat color="error" dark>
                        <v-toolbar-title>Anular Factura</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout row wrap>
                                <v-flex xs12 sm12 md12>
                                    <v-textarea box name="input-7-4" label="Observación"
                                        v-model="datosFactura.observacion_final"></v-textarea>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="dialogAnular = false">Cerrar</v-btn>
                        <v-btn color="success" @click="anularFactura">Anular</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-flex xs12>
                <v-card>
                    <v-card-title primary-title>
                        <h3 class="headline mb-0">Filtros</h3>
                    </v-card-title>
                    <v-card-text>
                        <v-container class="pa-0">
                            <v-layout row wrap>
                                <v-flex xs4 sm4 d-flex>
                                    <v-text-field v-model="identificacion" label="Identificación" outline autofocus>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-menu ref="menu1" v-model="menu1" :close-on-content-click="false"
                                        :nudge-right="40" lazy transition="scale-transition" offset-y full-width
                                        min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <v-text-field :disabled="actuales" v-model="dateDesde" label="Fecha Desde"
                                                prepend-icon="event" readonly v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker color="primary" v-model="dateDesde" no-title
                                            @input="menu1 = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex xs4>
                                    <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false"
                                        :nudge-right="40" lazy transition="scale-transition" offset-y full-width
                                        min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <v-text-field :disabled="actuales" v-model="dateHasta" label="Fecha Hasta"
                                                prepend-icon="event" readonly v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker color="primary" v-model="dateHasta" no-title
                                            @input="menu2 = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>

                                <v-flex xs4 sm4 class="text-xs-right">
                                    <v-select :items="itemsStates" item-text="nombre" item-value="id" v-model="idState"
                                        box label="Estados"></v-select>
                                </v-flex>

                                <v-flex xs4 sm4 class="text-xs-right">
                                    <v-checkbox color="primary" v-model="actuales" label="Filtrar Actuales">
                                    </v-checkbox>
                                </v-flex>

                                <v-flex xs12 sm12 class="text-xs-right">
                                    <v-btn color="info" @click="filtrar">Filtrar</v-btn>
                                </v-flex>
                                <!--                                <v-jumbotron>-->
                                <v-flex>

                                    <v-divider class="my-3"></v-divider>
                                    <!--                                                <div class="title mb-3"></div>-->
                                    <v-btn class="mx-0" color="warning" large @click="generarCorte">Generar Corte
                                        Mensual</v-btn>
                                </v-flex>
                                <!--                                </v-jumbotron>-->

                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>

            <v-flex xs12>
                <v-card>
                    <v-data-table :headers="headers" :items="facturas">
                        <template v-slot:items="props">
                            <td>{{ props.item.id }} <v-btn color="info" @click="imprimir(props.item.id)">imprimir
                                </v-btn>
                            </td>
                            <td>{{ props.item.usuario }}</td>
                            <td>{{ props.item.tipo }}</td>
                            <td><strong>$ {{ props.item.total }}</strong></td>
                            <td class="text-xs-center">{{ props.item.fechaCreacion.substring(0,19) }}</td>

                            <td class="text-xs-center" v-show="props.item.estado_id == 7">
                                <v-chip color="info" text-color="white">Por Cobrar</v-chip>
                            </td>
                            <td class="text-xs-center" v-show="props.item.estado_id == 18">
                                <v-chip color="orange" text-color="white">Pendiente Entregar</v-chip>
                            </td>
                            <td class="text-xs-center" v-show="props.item.estado_id == 6">
                                <v-chip color="success" text-color="white">Cancelada</v-chip>
                            </td>
                            <td class="text-xs-center" v-show="props.item.estado_id == 26">
                                <v-chip color="error" text-color="white">Anulada</v-chip>
                            </td>

                            <td class="justify-center layout px-0">
                                <v-btn
                                    v-show="(parseInt(props.item.estado_id) === 7 || parseInt(props.item.estado_id) === 18) && tiempoAnulado(props.item)"
                                    color="error" text-color="white"
                                    @click="idFactura = props.item.id,dialogAnular=true">Anular</v-btn>
                                <v-btn v-show="props.item.estado_id == 7" color="success" text-color="white"
                                    @click="idFactura = props.item.id,dialog=true">Cobrar</v-btn>
                            </td>

                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import swal from "sweetalert";

    export default {
        name: 'vagoutFacturas',

        components: {},
        data: () => {
            return {
                preload: false,
                idState: 0,
                identificacion: "",
                dateDesde: new Date().toISOString().substr(0, 10),
                dateHasta: new Date().toISOString().substr(0, 10),
                menu1: false,
                menu2: false,
                actuales: false,
                fechaDesde: "",
                fechaHasta: "",
                dialogAnular: false,
                facturas: [],
                idFactura: 0,
                datosFactura: {
                    estado_id: 6,
                    fecha_cobro: new Date().toISOString().substr(0, 10),
                    observacion_final: ""
                },
                dialog: false,
                headers: [{
                    text: "Codigo",
                    value: "id"
                }, {
                    text: "usuario",
                    value: "usuario"
                }, {
                    text: "Tipo Factura",
                    value: "tipo"
                }, {
                    text: "Valor",
                    align: 'center',
                }, {
                    text: "Fecha Generada",
                    align: 'center',
                    value: ""
                }, {
                    text: "Estado",
                    align: 'center',
                    value: "estado_id"
                }, {
                    text: "",
                    value: ""
                }],
                itemsStates: [{
                        id: 0,
                        nombre: "Seleccionar Estado"
                    },
                    {
                        id: 7,
                        nombre: "Por Cobrar"
                    },
                    {
                        id: 18,
                        nombre: "Pendiente Entregar"
                    },
                    {
                        id: 6,
                        nombre: "Cancelada"
                    },
                    {
                        id: 26,
                        nombre: "Anulada"
                    }
                ]
            }
        },
        beforeMount() {
            this.getFacturas()
        },
        methods: {
            getFacturas() {
                const data = {
                    identificacion: this.identificacion,
                    estados: this.idState,
                    fechaDesde: this.dateDesde,
                    fechaHasta: this.dateHasta,
                    fechaActual: false
                };
                if (this.actuales === true) {
                    data.fechaDesde = "";
                    data.fechaHasta = "";
                    data.fechaActual = true;
                }
                axios.post("/api/facturas/getInvoices", data).then(res => {
                    this.facturas = res.data;
                })
            },
            saveDate() {
                if (this.datosFactura.observacion_final === "") {
                    swal({
                        title: "Debe ingresar una observación",
                        icon: "warning"
                    });
                    return;
                }
                axios.post('/api/facturas/update/' + this.idFactura, this.datosFactura).then(res => {
                    this.clearData();
                    this.dialog = false;
                    this.$alerSuccess(res.data.message);
                    this.getFacturas()
                })
            },
            anularFactura() {
                if (this.datosFactura.observacion_final === "") {
                    swal({
                        title: "Debe ingresar una observación",
                        icon: "warning"
                    });
                    return;
                }
                const data = {
                    estado_id: 26,
                    observacion_final: this.datosFactura.observacion_final
                }
                axios.post('/api/facturas/update/' + this.idFactura, data).then(res => {
                    this.clearData();
                    this.dialogAnular = false;
                    this.$alerSuccess(res.data.message);
                    this.getFacturas();
                })
            },
            filtrar() {
                const d1 = new Date(this.dateDesde)
                const d2 = new Date(this.dateHasta)
                if (d1.getTime() === d2.getTime()) {
                    this.actuales = true;
                } else if (d1 > d2) {
                    this.$alerWarning('La fecha desde no puede ser mayor a la fecha hasta')
                    return;
                }
                this.getFacturas()
            },
            clearData() {
                this.idFactura = 0;
                this.datosFactura.fecha_cobro = new Date().toISOString().substr(0, 10);
                this.datosFactura.observacion_final = "";
                this.datosFactura.estado_id = 6;
            },
            imprimir(id) {
                const data = {
                    type: "vagout",
                    orden_id: id,
                }
                axios.post("/api/pdf/print-pdf", data, {
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
            },
            tiempoAnulado(item) {
                const date1 = new Date(item.fechaCreacion);
                const date2 = new Date;
                const diffMs = (date2 - date1);
                const diffMins = Math.round((diffMs / 1000) / 60);
                return (diffMins > 5 ? false : true);
            },
            generarCorte() {
                swal({
                    title: 'Esta seguro de generar el corte del mes?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then(result => {
                    if (result) {
                        this.preload = true;
                        axios({
                            method: 'get',
                            url: '/api/facturas/generarCorte',
                            responseType: 'blob'
                        }).then(res => {

                                let blob = new Blob([res.data], {
                                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                });
                                let url = window.URL.createObjectURL(blob);
                                let a = document.createElement('a');

                                a.download = `ReporteCorte.xlsx`;
                                a.href = url;
                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);
                                this.preload = false;

                        }).catch(err => {
                            this.preload = false;
                            this.showMessage('EL REPORTE YA HA SIDO GENERADO')
                        })
                    }
                })
            },
            showMessage(message) {
                swal({
                    title: "¡Aviso!",
                    text: message,
                    icon: "warning"
                });
            },

        }
    }

</script>
