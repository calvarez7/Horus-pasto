<template>
    <div>
        <v-form v-if="datosCita.Especialidad != 'Quimica Farmacologica'">
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Diagnosticas de la Consulta</span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex xs12 sm6 md6>
                        <v-autocomplete style="font-size: 12px; font-weight: bold;" v-model="impresion.Cie10_id"
                            append-icon="search" :items="cieConcat" item-disabled="customDisabled" item-text="nombre"
                            item-value="id" label="Diagnóstico">
                        </v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-autocomplete style="font-size: 14px" small-chips :items="tipoDx" label="Tipo Diagnóstico"
                            v-model="impresion.Tipodiagnostico" @change="chageDx()"></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm6 md2
                        v-if="impresion.Tipodiagnostico  == 'Confirmado nuevo' || impresion.Tipodiagnostico  == 'Confirmado repetido'">
                        <v-autocomplete style="font-size: 14px" label="Marcación Paciente" v-model="impresion.marca"
                            :items="marcasPaciente"></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="saveImpresionDX()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-data-table :headers="headerDx" :items="allImpresionDX">
                            <template v-slot:items="props">
                                <td class="text-xs">{{ props.item.id }}</td>
                                <td class="text-xs">{{ props.item.Codigo_CIE10 }}</td>
                                <td class="text-xs">{{ props.item.Descripcion }}</td>
                                <td class="text-xs">{{ props.item.Esprimario }}</td>
                                <td class="text-xs">{{ props.item.Tipodiagnostico }}</td>
                                <td class="text-xs">{{ props.item.marca }}</td>
                                <td class="text-xs">{{ props.item.name }}</td>
                                <td class="text-xs">{{ props.item.created_at }}</td>
                                <td class="text-xs">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn small text icon color="red" dark v-on="on">
                                                <v-icon @click="borraDX(props.item.id)">
                                                    mdi-delete-forever
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Eliminar Dx</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-flex>
                    <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
                </v-layout>
            </v-container>
        </v-form>
        <v-form v-if="datosCita.Especialidad == 'Quimica Farmacologica'">
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Diagnosticos de Consultas
                        Anteriores</span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex xs12 sm6 md6>
                        <v-autocomplete style="font-size: 12px; font-weight: bold;" v-model="impresion.Cie10_id"
                            append-icon="search" :items="cieAnte" item-disabled="customDisabled" item-text="nombre"
                            item-value="id" label="Diagnóstico">
                        </v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-autocomplete style="font-size: 14px" small-chips :items="tipoDx" label="Tipo Diagnóstico"
                            v-model="impresion.Tipodiagnostico" @change="chageDx()"></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm6 md2
                        v-if="impresion.Tipodiagnostico  == 'Confirmado nuevo' || impresion.Tipodiagnostico  == 'Confirmado repetido'">
                        <v-autocomplete label="Marcación Paciente" v-model="impresion.marca" :items="marcasPaciente">
                        </v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="saveImpresionDX()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-data-table :headers="headerDx" :items="allImpresionDX">
                            <template v-slot:items="props">
                                <td class="text-xs">{{ props.item.id }}</td>
                                <td class="text-xs">{{ props.item.Codigo_CIE10 }}</td>
                                <td class="text-xs">{{ props.item.Descripcion }}</td>
                                <td class="text-xs">{{ props.item.Esprimario }}</td>
                                <td class="text-xs">{{ props.item.Tipodiagnostico }}</td>
                                <td class="text-xs">{{ props.item.marca }}</td>
                                <td class="text-xs">{{ props.item.name }}</td>
                                <td class="text-xs">{{ props.item.created_at }}</td>
                                <td class="text-xs">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn small text icon color="red" dark v-on="on">
                                                <v-icon @click="borraDX(props.item.id)">
                                                    mdi-delete-forever
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Eliminar Dx</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-flex>
                    <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
                </v-layout>
            </v-container>
        </v-form>
        <v-dialog v-model="preloadHistoria" persistent width="300">
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
        data() {
            return {
                preloadHistoria: false,
                tipoDx: ['Impresión diagnóstica', 'Confirmado nuevo', 'Confirmado repetido'],
                cie10s: [],
                cie10Array: [],
                impresion: {
                    Cie10_id: "",
                    Tipodiagnostico: '',
                    marca: ''
                },
                marcasPaciente: [
                    'Oncológicos', 'Anticoagulados', 'Salud Mental', 'Nefroprotección', 'Respiratorios',
                    'Reumatologicos', 'Tramisibles Crónicas', 'Gestantes', 'Enfermedades Huerfanas',
                    'Trasplantados', 'Polimedicados', 'Pacientes Tutela', 'Paciente Alto Costo',
                    'Domiciliario', 'Primera Infancia', 'Infancia ', 'Adolescencia ', 'Juventud ',
                    'Riesgo Cardiovascular', 'Grupal Curso Psicoprofilactico', 'Apoyo Lactancia Materna ',
                    'Detección Temprana Cáncer Cuello Uterino ', 'Riesgo Cardiovascular',
                    'Planificación Familiar', 'Atención Preconcepcional', 'Promocion De Alimentacion Y Nutricion',
                    'Prenatal', 'Atención Del Posparto', 'Atención Del Recien Nacido',
                    'Detección Temprana Cancer De Mama', 'Adultez', 'Vejez', 'No Aplica'
                ],
                allImpresionDX: [],
                headerDx: [{
                        text: 'id'
                    },
                    {
                        text: 'Dx'
                    },
                    {
                        text: 'Descripcion'
                    },
                    {
                        text: 'Esprimario'
                    },
                    {
                        text: 'Tipodiagnostico'
                    },
                    {
                        text: 'Marca'
                    },
                    {
                        text: 'Medico'
                    },
                    {
                        text: 'Registrado'
                    },
                    {
                        text: 'Accion'
                    }
                ],
                dxRequediso: ''
            }
        },
        created() {
            this.fetchCie10s();
            this.fetchImpresionDx();
            if (this.datosCita.Especialidad == 'Quimica Farmacologica') {
                this.fetchCie10sAnteriores();
            }

        },
        computed: {
            cieConcat() {
                return this.cie10Array = this.cie10s.map(cie => {
                    return {
                        id: cie.id,
                        codigo: cie.Codigo_CIE10,
                        nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                        customDisabled: false
                    };
                });
            },

            cieAnte() {
                return this.cie10Array = this.cie10s.map(cie => {
                    return {
                        id: cie.id,
                        codigo: cie.Codigo_CIE10,
                        nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                        customDisabled: false
                    };
                });
            }
        },
        methods: {
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },
            fetchCie10sAnteriores() {
                axios.get("/api/cie10/DxAnteriores/" + this.datosCita.paciente_id).then(res => {
                    this.cie10s = res.data;
                });
            },
            chageDx() {
                this.impresion.marca = ''
            },
            saveImpresionDX() {
                if (!this.impresion.Cie10_id) {
                    this.$alerError('El campo diagnostico es requerido!')
                    return
                } else if (!this.impresion.Tipodiagnostico) {
                    this.$alerError('El campo tipo diagnostico es requerido!')
                    return
                } else if (this.impresion.Tipodiagnostico == 'Confirmado nuevo' || this.impresion.Tipodiagnostico ==
                    'Confirmado repetido') {
                    if (!this.impresion.marca) {
                        this.$alerError('El campo marca es requerido!')
                        return
                    }
                }
                this.preloadHistoria = true
                this.impresion.Citapaciente_id = this.datosCita.cita_paciente_id;
                this.impresion.paciente_id = this.datosCita.paciente_id;
                axios.post('/api/historia/saveImpresionDx', this.impresion).then(res => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.clearData();
                    this.fetchImpresionDx();
                    this.preloadHistoria = false
                });

            },
            clearData() {
                for (const key in this.impresion) {
                    this.impresion[key] = ''
                }
            },
            fetchImpresionDx() {
                this.preloadHistoria = true
                axios.get(`/api/historia/fetchImpresionDx/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.allImpresionDX = res.data;
                        this.preloadHistoria = false
                        this.DxExistente();
                    });
            },
            DxExistente() {
                this.preloadHistoria = true
                axios.get(`/api/historia/dxExistente/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.dxRequediso = res.data;
                        this.preloadHistoria = false
                    });
            },
            borraDX(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El Diagnostico será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        this.preloadHistoria = true
                        axios.post(`/api/historia/deleteDX/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:red">' + res.data.message +
                                    '<span>');
                                this.preloadHistoria = false
                                this.fetchImpresionDx();
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchImpresionDx();
                });
            },
            guardarSeguir() {
                this.DxExistente();
                if (this.dxRequediso == false) {
                    this.$alerError('No a guardado el diagnostico de la consulta!')
                    return
                }

                this.allImpresionDX.forEach((ser) => {
                            if(ser.ficha_epidemiologica == true){
                                let pdf = {
                                    type: "fichasEpidemiologa",
                                    id: ser.CIE10_id,
                                    paciente_id: this.datosCita.paciente_id,
                                    cita_paciente_id: this.datosCita.cita_paciente_id,
                                };
                                axios.post("/api/pdf/print-pdf", pdf, {
                                    responseType: "arraybuffer",
                                }).then((res) => {
                                    let blob = new Blob([res.data], {
                                        type: "application/pdf",
                                    });
                                    let link = document.createElement("a");
                                    link.href = window.URL.createObjectURL(blob);
                                    window.open(link.href, "_blank");
                                });
                            }
                        });
                this.$emit('respuestaComponente')
            },
        }
    }

</script>
