<template>
    <div>
        <v-card>
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Historico Incapacidades</span>
                <v-btn color="warning" round @click="cerrar()">Cerrar</v-btn>
            </v-card-title>
            <v-data-table :headers="headersIncapacidades" :items="historialpaciente" class="elevation-1">
                <template v-slot:items="props">
                    <td class="text-xs-left">{{ props.item.Dias }}</td>
                    <td class="text-xs-left">{{ props.item.Contingencia }}</td>
                    <td class="text-xs-left">{{ props.item.Fechainicio }}</td>
                    <td class="text-xs-left">{{ props.item.Fechafinal }}</td>
                    <td class="text-xs-left">{{ props.item.Prorroga }}</td>
                    <td class="text-xs-left" @click="print(props.item)">
                        <v-chip v-if="props.item.Estado === 'Activo'" class="ma-2" color="green" text-color="white">
                            <v-icon dark small >done </v-icon>{{ props.item.Estado }}
                        </v-chip>
                    </td>
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
            this.buscarDatosInc();
        },
        data() {
            return {
                historialpaciente: [],
                preload: false,
                inc: {},
                headersIncapacidades: [{
                        text: 'Días'
                    },
                    {
                        text: 'Tipo'
                    },
                    {
                        text: 'F. Inicio',
                        value: 'Fechainicio'
                    },
                    {
                        text: 'F. Final',
                    },
                    {
                        text: 'Prorroga',
                        value: 'Prorroga'
                    },
                    {
                        text: 'Estado',
                        value: 'Estado'
                    }
                ]
            }
        },
        methods: {
            buscarDatosInc() {
                this.preload = true;
                axios.post('/api/paciente/historialIncapacidades', this.datosCita).then(res => {
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
            print(inc) {
                this.inc = inc;
                let pdf = this.getPDFIncap();
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
                    });
            },
            getPDFIncap() {
                return (this.pdf = {
                    type: "incapacidad",
                    FechaInicio: this.inc.Fechainicio,
                    Nombre: this.inc.Nombre,
                    Edad_Cumplida: this.inc.Edad_Cumplida,
                    Sexo: this.inc.Sexo,
                    medico_ordena: this.inc.Medicoordeno,
                    Celular: this.inc.Celular,
                    Correo: this.inc.Correo,
                    Num_Doc: this.inc.Cedula,
                    cita_paciente_id: this.inc.citaPaciente_id,
                    orden_id: this.inc.Orden_id,
                    provincapacidad: this.inc.Provincapacidad,
                    Fechacreaincapacidad: this.inc.Fechacreaincapacidad,
                    hj: this.inc.hj,
                    afilia: this.inc.afilia,
                    Ut: this.inc.Ut,
                    ips1: this.inc.ips1,
                    ma: this.inc.ma,
                    TTipod: this.inc.TTipod,
                    Proveedor: this.inc.Proveedor,
                    dx_principal: this.inc.Diagnostico.substring(0, 4),
                    CantidadDias: this.inc.Dias,
                    FechaFinal: this.inc.Fechafinal,
                    Prorroga: this.inc.Prorroga,
                    tel: this.inc.tel,
                    TextCie10: this.inc.Diagnostico.substring(0, 4),
                    Contingencia: this.inc.Contingencia,
                    Descripcion: this.inc.Descripcion,
                    Laboraen: this.inc.Laboraen,
                    Firma: this.inc.Firma,
                    especialidad_medico: this.inc.especialidad_medico,
                    Rmedico: this.inc.Rmedico,
                    SendEmail: false
                });
            },
            cerrar(){
                this.$emit('respuestaComponente');
            }
        }
    }

</script>
