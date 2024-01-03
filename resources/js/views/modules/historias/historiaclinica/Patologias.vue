<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-stepper v-model="e6" vertical>
                <v-stepper-step :complete="e6 > 1" editable :edit-icon="$vuetify.icons.complete" step="1">ANTECEDENTES
                    PERSONALES</v-stepper-step>
                <v-stepper-content step="1">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm4 d-flex>
                                            <v-autocomplete label="Selecciona Antecedentes" :items="antecedentes"
                                                item-text="Nombre" item-value="id" v-model="data.antecedente">
                                            </v-autocomplete>
                                        </v-flex>

                                        <v-flex xs12 sm6 d-flex>
                                            <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                                v-model="data.descripcion">
                                            </v-textarea>
                                        </v-flex>

                                        <v-flex xs12 sm1 d-flex>
                                            <v-btn fab dark color="success" @click="guardarAntecedente()">
                                                <v-icon dark>add</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                        <v-card>
                            <v-card-title primary-title>
                                <v-flex xs12 sm12 d-flex>
                                    <v-data-table :items="antecedentePaci" :headers="hola" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.created_at }}</td>
                                            <td class="text-xs">{{ props.item.name }}</td>
                                            <td class="text-xs">{{ props.item.Nombre }}</td>
                                            <td class="text-xs">{{ props.item.Descripcion }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                    <v-btn color="primary" round @click="Antecendente()">Guardar y seguir</v-btn>
                </v-stepper-content>
                <v-stepper-step :complete="e6 > 2" editable :edit-icon="$vuetify.icons.complete" step="2">ANTECEDENTES
                    FAMILIARES</v-stepper-step>

                <v-stepper-content step="2">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-autocomplete label="Selecciona Antecedentes" :items="antecedentes"
                                                item-text="Nombre" item-value="id" v-model="data.antecedente">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-autocomplete label="Selecciona Familiar" :items="parentesco"
                                                item-text="Nombre" item-value="id" v-model="data.familiar">
                                            </v-autocomplete>
                                        </v-flex>

                                        <v-flex xs12 sm5 d-flex>
                                            <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                                v-model="data.descripcion">
                                            </v-textarea>
                                        </v-flex>

                                        <v-flex xs12 sm1 d-flex>
                                            <v-btn fab dark color="success" @click="guardarAntecedentefamiliar()">
                                                <v-icon dark>add</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                        <v-card>
                            <v-card-title primary-title>
                                <v-flex xs12 sm12 d-flex>
                                    <v-data-table :items="antecedenteFami" :headers="familiare" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.created_at }}</td>
                                            <td class="text-xs">{{ props.item.name }}</td>
                                            <td class="text-xs">{{ props.item.Nombre }}</td>
                                            <td class="text-xs">{{ props.item.familiar }}</td>
                                            <td class="text-xs">{{ props.item.Descripcion }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                        <v-btn color="primary" round @click="guardarParentesco()">Guardar</v-btn>
                    </v-card>
                </v-stepper-content>
                <template>
                    <div class="text-center">
                        <v-dialog v-model="preload_patologia" persistent width="300">
                            <v-card color="primary" dark>
                                <v-card-text>
                                    Tranquilo procesamos tu solicitud !
                                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </div>
                </template>
            </v-stepper>
        </v-flex>
    </v-layout>
</template>
<script>
    import Swal from 'sweetalert2';
    import {
        EventBus
    } from "../../../../event-bus.js";

    export default {
        created() {
            this.citaPaciente = this.$route.query.cita_paciente;
            this.data.cita_paciente = this.$route.query.cita_paciente;
            EventBus.$on("recibir-paciente-examen-fisico", paciente => {
                this.paciente = paciente;
            });
            EventBus.$emit("step-historia", 2
            );
        },
        data() {
            return {
                dialog: false,
                preload_patologia: false,
                e6: 1,
                antecedentes: [],
                parentesco: [],
                pacienteantecedente: [],
                parentescoantecedentes: [],
                citaPaciente: 0,
                antecedentePaci: [],
                antecedenteFami: [],
                data: {
                    antecedente: "",
                    descripcion: "",
                    cita_paciente: "",
                    familiar: "",
                },
                hola: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Descripción'
                    }
                ],
                familiare: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Parentesco'
                    },
                    {
                        text: 'Descripción'
                    }
                ],

            };
        },

        mounted() {
            this.fetchAntecedentes();
            this.fetchParentesco();
            this.fetchAntecedentePersonal();
            this.fetchAntecedenteFamiliar();
        },
        methods: {

            getPaciente() {
                if (this.paciente == null) {
                    EventBus.$emit("enviar-paciente", "recibir-paciente-examen-fisico");
                }
            },
            fetchAntecedentes() {
                axios
                    .get("/api/antecedente/all")
                    .then(res => (this.antecedentes = res.data));
            },
            fetchParentesco() {
                axios
                    .get("/api/parentesco/all")
                    .then(res => (this.parentesco = res.data));
            },
            fetchAntecedentePersonal() {
                const data = {
                    citaPaciente_id: this.citaPaciente
                }
                axios.post("/api/pacienteantecedente/antecedentes1", data)
                    .then(res => {
                        this.antecedentePaci = res.data
                    });
            },
            fetchAntecedenteFamiliar() {
                const data = {
                    citaPaciente_id: this.citaPaciente
                }
                axios.post("/api/parentescoantecedente/familiares", data)
                    .then(res => {
                        this.antecedenteFami = res.data
                    });
            },
            guardarAntecedente() {
                this.preload_patologia = true;
                axios.post("/api/pacienteantecedente/create", this.data)
                    .then(res => {
                        this.preload_patologia = false;
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'center-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: '<span style="color:#fff">Antecedentes Agregado con exito<span>'
                        });
                        this.clearAntecedente();
                        this.fetchAntecedentePersonal();
                    })
                    .catch(err => console.log(err.response.data));
            },
            guardarAntecedentefamiliar() {
                this.preload_patologia = true;
                axios.post("/api/parentescoantecedente/create", this.data)
                    .then(res => {
                        this.preload_patologia = false;
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'center-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: '<span style="color:#fff">Antecedentes Agregado con exito<span>'
                        });
                        this.clearAntecedente();
                        this.fetchAntecedenteFamiliar();
                    })
                    .catch(err => console.log(err.response.data));
            },
            clearAntecedente() {
                this.data.antecedente = "",
                    this.data.descripcion = ""
            },
            Antecendente() {

                this.e6 = 2;
            },
            guardarParentesco() {
                this.getPaciente();
                EventBus.$emit("change_disabled_list_history", "ANTECEDENTES");
                this.preload_patologia = true;

                this.e6 = 3;
                if (this.paciente.Sexo == "F") {
                    this.$router.push(
                        "/historias/historiaclinica/gineco?cita_paciente=" + this.citaPaciente
                    );
                    EventBus.$emit(
                        "change_disabled_list_history",
                        "ANTECEDENTES GINECO OBSTÉTRICOS"
                    );
                } else {
                    this.$router.push(
                        "/historias/historiaclinica/stylelive?cita_paciente=" + this.citaPaciente
                    );
                    EventBus.$emit("change_disabled_list_history", "ESTILOS DE VIDA");
                }
            }
        }
    };

</script>
<style scoped>
    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        font-family: arial, sans-serif;
        border-collapse: collapse;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

</style>
