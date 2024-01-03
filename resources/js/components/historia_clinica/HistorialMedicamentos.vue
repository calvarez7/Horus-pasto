<template>
    <div>
        <v-card>
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Historico Farmacologicos</span>
                <v-btn color="warning" round @click="cerrar()">Cerrar</v-btn>
            </v-card-title>
            <v-data-table :headers="headersMedicamentos" :items="historialpaciente">
                <template v-slot:items="props">
                    <tr>
                        <td class="text-xs">{{props.item.Orden_id}}</td>
                        <td class="text-xs">{{props.item.paginacion}}</td>
                        <td class="text-xs">{{ props.item.Fechaorden?props.item.Fechaorden.substring(0,10):''}}</td>
                        <td class="text-xs">{{props.item.Codigo}}</td>
                        <td class="text-xs">{{props.item.Nombre}}</td>
                        <td class="text-xs">{{props.item.Via}}</td>
                        <td class="text-xs">{{props.item.Cantidad_Dosis+" " + props.item.Unidad_Medida + " cada " + props.item.Frecuencia + " " + props.item.Unidad_Tiempo}}
                        </td>
                        <td class="text-xs">{{props.item.Duracion+" Dias"}}</td>
                        <td class="text-xs">{{props.item.Cantidad_Medico}}</td>
                        <td class="text-xs">{{props.item.Cantidad_Mensual_Disponible}}</td>
                        <td class="text-xs">{{props.item.Observacion}}</td>
                        <td class="text-xs" v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7'">
                            <v-chip color="green" text-color="white">{{ props.item.Estado }}</v-chip>
                        </td>
                        <td class="text-xs" v-else-if="props.item.Estado_id == '17'">
                            <v-chip color="info" text-color="white">{{ props.item.Estado }}</v-chip>
                        </td>
                        <td class="text-xs"
                            v-else-if="props.item.Estado_id == '18' || props.item.Estado_id == '35' || props.item.Estado_id == '19' || props.item.Estado_id == '15'">
                            <v-chip color="orange" text-color="white">{{ props.item.Estado }}</v-chip>
                        </td>
                        <td class="text-xs-center" v-else>
                            <v-chip color="error" text-color="white">{{ props.item.Estado }}</v-chip>
                        </td>
                        <td class="text-xs-center"
                            v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7' || props.item.Estado_id == '17'">
                            <v-btn  small color="info" fab dark @click="imprimir(props.item.Orden_id)">
                                <v-icon>mdi-download</v-icon>
                            </v-btn>
                        </td>
                        <td class="text-xs-center"
                            v-if="props.item.Estado_id == '18'">
                            <v-btn  small color="error" fab dark>
                                <v-icon>remove_circle</v-icon>
                            </v-btn>
                        </td>
                        <td class="text-xs-center">
                            <v-btn small color="info" fab dark @click="Anexo(props.item.id,'anexo3Medicamentos')">
                                <v-icon>assignment_turned_in</v-icon>
                            </v-btn>
                        </td>
                    </tr>
                </template>
            </v-data-table>
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
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.buscarDatosFarma();
        },
        data() {
            return {
                historialpaciente: [],
                preload: false,
                headersMedicamentos: [{
                    text: "# Orden",
                    align: 'center',
                    value: "Orden_id"
                }, {
                    text: "Paginacion",
                    align: 'center',
                    sortable: false,
                    value: "paginacion"
                }, {
                    text: "Fecha orden",
                    align: 'center',
                    sortable: false,
                    value: "Fechaorden"
                }, {
                    text: "Codigo",
                    align: 'center',
                    value: "Codigo"
                }, {
                    text: "Nombre",
                    align: 'center',
                    value: "Nombre"
                }, {
                    text: "Via Admin",
                    align: 'center',
                    sortable: false,
                    value: "paginacion"
                }, {
                    text: "Dosificación",
                    align: 'center',
                    sortable: false,
                    value: "tipo"
                }, {
                    text: "Duración",
                    align: 'center',
                    sortable: false,
                    value: "estado"
                }, {
                    text: "Dosis Totales",
                    align: 'center',
                    sortable: false,
                    value: "usuario"
                }, {
                    text: "No Total a Dispensar",
                    align: 'center',
                    sortable: false,
                }, {
                    text: "Observaciones",
                    align: 'center',
                    sortable: false
                }, {
                    text: "Estado",
                    align: 'center',
                    value: 'Estado'
                }, {
                    text: "Imprimir Orden",
                    align: 'center',
                    sortable: false
                }, {
                    text: "Imprimir Anexo 3",
                    align: 'center',
                    sortable: false
                }],
            }
        },
        methods: {
            buscarDatosFarma() {
                this.preload = true;
                axios.post('/api/paciente/historialFarmacologico', this.datosCita).then(res => {
                    this.historialpaciente = res.data;
                    this.preload = false;
                }).catch(err => {
                    this.preload = false;
                    console.log(err);
                })
            },
            async Anexo(id, tipo) {
                const pdf = {
                    type: 'Anexo',
                    id: id,
                    tipos: tipo,
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
            },
            imprimir(orden) {
                axios.get('/api/orden/getAllArticulosOrden/' + orden).then(res => {
                    let hojas = {};
                    let hojasMipres = {};

                    res.data.forEach(s => {
                        hojas[s.Estado] = {
                            'orden_id': s.Orden_id,
                            type: "formula",
                            medicamentos: [],
                            Num_Doc: s.cedula,
                            mensaje: ((s.Estado === 'Activo' || s.Estado === 'Confirmada') && s
                                .estadoOrden === '18' ? 'Pendiente' : s.Estado)
                        };
                        hojasMipres[s.Estado] = {
                            'orden_id': s.Orden_id,
                            type: "formula",
                            medicamentos: [],
                            Num_Doc: s.cedula,
                            mensaje: 'M I P R E S'
                        };
                    });
                    res.data.forEach(s => {
                        for (const key in hojas) {
                            if (s.Estado == key) {
                                if (!s.mipres) {
                                    hojas[s.Estado].medicamentos.push({
                                        id: s.codesumi_id,
                                        nombre: s.medicamento,
                                        Codigo: s.Codigo,
                                        Cantidadosis: s.Cantidadosis,
                                        Unidadmedida: s.Unidadmedida,
                                        Via: s.Via,
                                        Frecuencia: s.Frecuencia,
                                        Unidadtiempo: s.Unidadtiempo,
                                        Duracion: s.Duracion,
                                        Cantidadmensual: s.Cantidadpormedico,
                                        NumMeses: s.NumMeses,
                                        Observacion: s.Observacion,
                                        Requiere_autorizacion: s.Requiere_Autorizacion,
                                        Cantidadpormedico: s.Cantidadmensualdisponible,
                                        Auth_Nota: s.nota_autorizacion,
                                    });
                                } else {
                                    hojasMipres[s.Estado].medicamentos.push({
                                        id: s.codesumi_id,
                                        nombre: s.medicamento,
                                        Codigo: s.Codigo,
                                        Cantidadosis: s.Cantidadosis,
                                        Unidadmedida: s.Unidadmedida,
                                        Via: s.Via,
                                        Frecuencia: s.Frecuencia,
                                        Unidadtiempo: s.Unidadtiempo,
                                        Duracion: s.Duracion,
                                        Cantidadmensual: s.Cantidadpormedico,
                                        NumMeses: s.NumMeses,
                                        Observacion: s.Observacion,
                                        Requiere_autorizacion: s.Requiere_Autorizacion,
                                        Cantidadpormedico: s.Cantidadmensualdisponible,
                                        Auth_Nota: s.nota_autorizacion,
                                    });
                                }

                            }
                        }
                    });
                    for (const key in hojas) {
                        if (hojas[key].medicamentos.length) {
                            this.getPDF(hojas[key]);
                        }
                        if (hojasMipres[key].medicamentos.length) {
                            this.getPDF(hojasMipres[key]);
                        }
                    }
                    this.preload = false;
                }).catch(e => {
                    console.log(e);
                    this.preload = false;
                });
            },
            async getPDF(pdf) {
                this.preload = true;
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
                        this.preload = false;
                    })
                    .catch(err => {
                        console.log(err.response);
                        this.preload = false;
                    });

            },
            async formatoNegacion(id, tipo) {
                const pdf = {
                    type: 'Negacion',
                    id: id,
                    tipos: tipo,
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
            },
             cerrar(){
                this.$emit('respuestaComponente');
            }
        }
    }

</script>
