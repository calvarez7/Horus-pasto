<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Procedimientos Menores</span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex xs12 sm12 md12>
                        <v-autocomplete v-model="motivo.Cup_id" append-icon="search" :items="cups" item-text="Nombre"
                            item-value="id" label="Procedimiento Ejecutado">
                        </v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" v-model="motivo.ProcedimientosMenores"
                            label="Procedimientos menores">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Insumos y Procedimientos</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6 md6>
                        <v-autocomplete style="font-size: 12px; font-weight: bold;" v-model="medicamento.producto"
                            append-icon="search" :items="codesumi" item-text="Nombre" item-value="id"
                            label="Insumos o Medicamentos">
                        </v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-text-field label="Cantidad" value type="number" v-model="medicamento.cantidad">
                        </v-text-field>
                    </v-flex>
                    <v-flex>
                        <v-btn small fab dark color="indigo" @click="saveInsumoProcedimientos()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
                <v-data-table :headers="headersprocedimientos" :items="procedimientos" class="elevation-1">
                    <template v-slot:items="props">
                        <td class="text-xs">{{ props.item.Nombre }}</td>
                        <td class="text-xs">{{ props.item.cantidad }}</td>
                        <td class="text-xs">
                            <v-btn small fab dark color="red" @click="inahabilitarInsumo(props.item.id)">
                                <v-icon dark>mdi-delete-forever</v-icon>
                            </v-btn>
                        </td>
                    </template>
                </v-data-table>
                <v-btn color="success" @click="saveProcedimientoMenor()">Guardar</v-btn>
            </v-container>
        </v-form>
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
    import Swal from 'sweetalert2';
    import moment from "moment";
    import swal from 'sweetalert';
    export default {
        name: "MotivoConsulta",
        props: {
            datosCita: Object
        },
        data() {
            return {
                preload: false,
                procedimientos: [],
                codesumi: [],
                cups: [],
                motivo: {
                    Cup_id: '',
                    ProcedimientosMenores: ''
                },
                medicamento: {
                    producto: null,
                    cantidad: null,
                },

                headersprocedimientos: [{
                        text: 'Medicamento'
                    },
                    {
                        text: 'Cantidad'
                    },
                    {
                        text: 'Acciones'
                    }
                ]
            }
        },
        created() {
            this.fechtCups();
            this.fechtMedicamentoInsumos();
            this.fethProcedimientoMenor();
            this.fethInsumoProcedimientos();
        },
        methods: {

            fechtMedicamentoInsumos() {
                axios.get("/api/historia/fechtMedicamentoInsumos")
                    .then(res => {
                        this.codesumi = res.data;
                    });
            },

            fethInsumoProcedimientos() {
                axios.get('/api/historia/fethInsumoProcedimientos/' + this.datosCita.cita_paciente_id)
                    .then(res => {
                        this.procedimientos = res.data;
                    });
            },


            saveProcedimientoMenor() {
                if (!this.motivo.Cup_id) {
                    this.$alerError('El campo Procedimiento Ejecutado es requerido')
                    return;
                } else if (!this.motivo.ProcedimientosMenores) {
                    this.$alerError('El campo Procedimiento Menor es requerido')
                    return;
                }
                this.motivo.cita_paciente = this.datosCita.cita_paciente_id
                axios.post("/api/historia/saveProcedimientoMenor", this.motivo)
                    .then(res => {
                        this.motivo = res.data;
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                    });
            },

            fethProcedimientoMenor() {
                this.preload = true;
                axios.get("/api/historia/fethProcedimientoMenor/" + this.datosCita.cita_paciente_id)
                    .then(res => {
                        this.motivo = res.data;
                        this.motivo.Cup_id = parseInt(res.data.Cup_id)
                        this.preload = false;
                    });
            },

            saveInsumoProcedimientos() {
                if (!this.medicamento.producto) {
                    this.$alerError('El campo insumos es requerido')
                    return;
                } else if (!this.medicamento.cantidad) {
                    this.$alerError('El campo cantidad es requerido')
                    return;
                }
                this.medicamento.Citapaciente_id = this.datosCita.cita_paciente_id
                axios.post("/api/historia/saveInsumoProcedimientos", this.medicamento)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fethInsumoProcedimientos();
                        this.clear()
                    })

            },

            inahabilitarInsumo(id) {
                axios.get('/api/historia/medicamento/' + id)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fethInsumoProcedimientos();
                    })

            },

            clear() {
                this.medicamento = {
                    producto: null,
                    cantidad: null,
                }
            },

            fechtCups() {
                axios.get("/api/historia/fechtCups")
                    .then(res => {
                        this.cups = res.data;
                    });
            },

        },

    }

</script>
