<template>
    <div>
        <v-card>
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Historico Examenes</span>
                <v-btn color="warning" round @click="cerrar()">Cerrar</v-btn>
            </v-card-title>
            <v-data-table :headers="headersServicios" :items="historialpaciente">
                <template v-slot:items="props">
                    <tr>
                        <td class="text-xs-center" @click="props.expanded = !props.expanded"
                            v-if="props.item.cancelacion === 'SI'">
                            <v-chip label color="error" text-color="white">Cancelada</v-chip>
                        </td>
                        <td class="text-xs-center" @click="props.expanded = !props.expanded"
                            v-else-if="props.item.cancelacion === 'NO'">
                            <v-chip label color="success" text-color="white">ASISTENCIA</v-chip>
                        </td>
                        <td class="text-xs-center" @click="props.expanded = !props.expanded"
                            v-else-if="props.item.cancelacion === 'INASISTENCIA'">
                            <v-chip label color="warning" text-color="white">Inasistencia</v-chip>
                        </td>
                        <td v-else></td>
                        <td>{{props.item.orden}}</td>
                        <td>{{props.item.FechaOrdenamiento.substring(0,10)}}</td>
                        <td>{{props.item.Fechaorden.substring(0,10)}}</td>
                        <td>{{props.item.Codigo}}</td>
                        <td>{{props.item.Nombre}}</td>
                        <td>{{props.item.Cantidad}}</td>
                        <td class="text-xs-center" v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7'">
                            <v-chip color="green" text-color="white">{{ props.item.Estado }}</v-chip>
                        </td>
                        <td class="text-xs-center" v-else-if="props.item.Estado_id == '17'">
                            <v-chip color="info" text-color="white">{{ props.item.Estado }}</v-chip>
                        </td>
                        <td class="text-xs-center"
                            v-else-if="props.item.Estado_id == '18' || props.item.Estado_id == '35' || props.item.Estado_id == '19' || props.item.Estado_id == '15'">
                            <v-chip color="orange" text-color="white">{{ props.item.Estado }}</v-chip>
                        </td>
                        <td class="text-xs-center" v-else>
                            <v-chip color="error" text-color="white">{{ props.item.Estado }}</v-chip>
                        </td>
                        <td class="text-xs-center">
                            <v-chip color="primary" v-show="props.item.anexo3" text-color="white">ANEXO 3</v-chip>
                        </td>
                        <td class="text-xs-center" v-if="props.item.Estado_id == '25'">
                            <v-btn small color="orange" fab dark @click="formatoNegacion(props.item.id,'servicios')">
                                <v-icon>mdi-download</v-icon>
                            </v-btn>
                        </td>
                        <td class="text-xs-center"
                            v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7' || props.item.Estado_id == '17'">
                            <v-btn small color="info" fab dark @click="imprimir(props.item.orden)">
                                <v-icon>mdi-download</v-icon>
                            </v-btn>
                        </td>
                        <td v-else></td>
                        <td class="text-xs-center">
                            <v-btn small color="info" fat dark @click="Anexo(props.item.id,'anexo3Servicios')">
                                <v-icon>assignment_turned_in</v-icon>
                            </v-btn>
                        </td>

                        <td class="text-xs-center">
                            <v-btn small color="success" fat dark @click="imprimirRecomendacion(props.item.Codigo)">
                                <v-icon>assignment</v-icon>
                            </v-btn>
                        </td>
                    </tr>
                </template>
                <template v-slot:expand="props">
                    <v-card flat>
                        <table>
                            <tr bgcolor="d3d3d3" v-show="props.item.cancelacion">
                                <td><strong>SEDE: </strong>{{props.item.Sede_Nombre}}</td>
                                <td v-show="props.item.cancelacion === 'NO'"><strong>FECHA SOLICITADA:
                                    </strong>{{props.item.fecha_solicitada}}</td>
                                <td v-show="props.item.cancelacion === 'NO'"><strong>FECHA SUGERIDA:
                                    </strong>{{props.item.fecha_sugerida}}</td>
                                <td v-show="props.item.cancelacion === 'NO'"><strong>FECHA RESULTADO:
                                    </strong>{{props.item.fecha_resultado}}</td>
                                <td v-show="props.item.cancelacion === 'SI'"><strong>FECHA CANCELACION:
                                    </strong>{{props.item.fecha_cancelacion}}</td>
                                <td v-show="props.item.cancelacion === 'SI'"><strong>MOTIVO:
                                    </strong>{{props.item.motivo}}</td>
                                <td v-show="props.item.cancelacion === 'SI'"><strong>CAUSA:
                                    </strong>{{props.item.causa}}</td>
                                <td><strong>RESPONSABLE: </strong>{{props.item.responsable}}</td>
                                <td><strong>OBSERVACIONES: </strong>{{props.item.observaciones}}</td>
                            </tr>
                            <tr bgcolor="d3d3d3" v-show="props.item.cirujano">
                                <td><strong>CIRUJANO: </strong>{{props.item.cirujano}}</td>
                                <td><strong>ESPECIALIDAD: </strong>{{props.item.especialidad}}</td>
                                <td><strong>FECHA PREANESTESIA: </strong>{{props.item.fecha_Preanestesia}}</td>
                                <td><strong>FECHA CIRUGÍA: </strong>{{props.item.fecha_cirugia}}</td>
                                <td><strong>FECHA EJECUCIÓN: </strong>{{props.item.fecha_ejecucion}}</td>
                            </tr>
                        </table>
                    </v-card>
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
            this.buscarDatosExam();
        },
        data() {
            return {
                historialpaciente: [],
                preload: false,
                headersServicios: [{
                        text: "Asistencia",
                        align: 'center',
                        sortable: false,
                        value: ""
                    },
                    {
                        text: "# Orden",
                        align: 'center',
                        value: "orden"
                    }, {
                        text: "Fecha orden",
                        align: 'center',
                        sortable: false,
                        value: "FechaOrdenamiento"
                    }, {
                        text: "Postfechado",
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
                        text: "Cantidad",
                        align: 'center',
                        sortable: false,
                        value: "cantidad"
                    }, {
                        text: "Estado",
                        align: 'center',
                        value: "Estado"
                    }, {
                        text: "",
                        align: 'center',
                        sortable: false
                    }, {
                        text: "Imprimir Orden",
                        align: 'center',
                        sortable: false
                    }, {
                        text: "Imprimir Anexo 3",
                        align: 'center',
                        sortable: false
                    }, {
                        text: "Imprimir Recomendaciones",
                        align: 'center',
                        sortable: false
                    }
                ]
            }
        },
        methods: {
            buscarDatosExam() {
                this.preload = true;
                axios.post('/api/paciente/historialExamenes', this.datosCita).then(res => {
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
                axios.get('/api/orden/getAllServiceOrden/' + orden).then(res => {
                    let hojas = {};
                    let hojasAnexo3 = {};
                    res.data.forEach(s => {
                        hojas[s.Estado] = {
                            'orden_id': s.Orden_id,
                            mensaje: 'N O  V A L I D A',
                            type: "otros",
                            servicios: [],
                            Num_Doc: s.cedula
                        };
                        hojasAnexo3[s.Estado] = {
                            'orden_id': s.Orden_id,
                            mensaje: 'A N E X O  3',
                            type: "otros",
                            servicios: [],
                            Num_Doc: s.cedula
                        };
                    });


                    res.data.forEach(s => {
                        for (const key in hojas) {
                            if (s.Estado == key) {
                                if (!s.anexo3) {
                                    hojas[s.Estado].servicios.push({
                                        codigo: s.Codigo,
                                        nombre: s.Nombre,
                                        cantidad: s.Cantidad,
                                        observacion: s.Observacionorden,
                                        ubicacion: s.ubicacion,
                                        direccion: s.direccion,
                                        telefono: s.telefono,
                                    });
                                } else {
                                    hojasAnexo3[s.Estado].servicios.push({
                                        codigo: s.Codigo,
                                        nombre: s.Nombre,
                                        cantidad: s.Cantidad,
                                        observacion: s.Observacionorden,
                                        ubicacion: s.ubicacion,
                                        direccion: s.direccion,
                                        telefono: s.telefono,
                                    });
                                }
                            }
                        }
                    });



                    for (const key in hojas) {
                        if (hojas[key].servicios.length) {
                            this.getPDF(hojas[key]);
                        }
                        if (hojasAnexo3[key].servicios.length) {
                            this.getPDF(hojasAnexo3[key]);
                        }
                    }
                    this.preload = false;
                }).catch(e => {
                    console.log(e);
                    this.preload = false;
                })

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
            imprimirRecomendacion(codigo) {
                axios.get("/api/orden/recomendaciones/" + codigo).then(res => {
                    console.log(res.data);
                    // window.open(res.data.file[0].nombre)
                    if (res.data.file.length === 0) {
                        this.$alerError('Servicio sin recomendación')
                    } else {
                        res.data.file.forEach(s => {
                            const a = document.createElement('a')
                            a.href = s.nombre
                            a.download = s.nombre.split('/').pop()
                            document.body.appendChild(a)
                            a.click()
                            document.body.removeChild(a)
                        })
                    }
                })
            },
            cerrar(){
                this.$emit('respuestaComponente');
            }
        }
    }

</script>
