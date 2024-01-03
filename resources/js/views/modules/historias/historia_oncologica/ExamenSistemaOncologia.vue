<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-stepper v-model="e6" vertical>
                <v-stepper-step :complete="e6 > 1" editable :edit-icon="$vuetify.icons.complete" step="1">MEDIDAS
                    ANTROPOMÉTRICAS</v-stepper-step>
                <v-stepper-content step="1">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Peso (Kg)" v-model="data.Peso"
                                        type="number"></v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Talla (Centimetros)" v-model="data.Talla"
                                        type="number"></v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="IMC" v-model="data.Imc" type="number" readonly></v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="ASC" v-model="data.ISC" type="number" readonly></v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Clasificación" v-model="data.Clasificacion" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6 px-1>
                                    <v-text-field label="Perímetro Abdominal" type="number"
                                        v-model="data.Perimetroabdominal"></v-text-field>
                                </v-flex>
                                <v-flex xs6 px-1>
                                    <v-text-field v-if="Perimetrocefalico" label="Perímetro Cefálico" type="number"
                                        v-model="data.Perimetrocefalico"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                    <v-btn round color="primary" @click="guardarAntropometricas()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 2" editable :edit-icon="$vuetify.icons.complete" step="2">SIGNOS VITALES
                </v-stepper-step>
                <v-stepper-content step="2">
                    <v-card color="lighten-1" class="mb-5">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs4 px-1>
                                    <v-text-field label="Frecuencia cardiaca" type="number"
                                        v-model="data.Frecucardiaca"></v-text-field>
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <v-text-field label="Pulsos" type="number" v-model="data.Pulsos"></v-text-field>
                                </v-flex>
                                <v-flex xs4 px-1>
                                    <v-text-field label="Frecuencia Respiratoria" type="number"
                                        v-model="data.Frecurespiratoria"></v-text-field>
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <v-text-field label="Temperatura" type="number" v-model="data.Temperatura">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 px-1>
                                    <v-text-field label="Sat. O2" type="number" v-model="data.Saturacionoxigeno">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row wrap>
                                <v-flex xs2 px-1>
                                    <v-select :items="['Sentado','Acostado','De pie']" label="Posición"
                                        v-model="data.Posicion" chips />
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <v-select :items="['Derecha','Izquierda']" label="Lateralidad"
                                        v-model="data.Lateralidad" chips />
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Presión / Sistólica" value type="number"
                                        v-model="data.Presionsistolica"></v-text-field>
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <v-text-field label="Presión / Diastólica" value type="number"
                                        v-model="data.Presiondiastolica"></v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Presión Arterial Media" value type="number"
                                        v-model="data.Presionarterialmedia"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                    <v-btn color="primary" round @click="guardarSignosVitales()">Guardar y seguir</v-btn>
                </v-stepper-content>
                <v-stepper-step :complete="e6 > 3" editable :edit-icon="$vuetify.icons.complete" step="3">EXAMEN FÍSICO
                </v-stepper-step>
                <v-stepper-content step="3">
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-textarea name="input-7-1" label="Condiciones Generales" value
                                v-model="data.Condiciongeneral"></v-textarea>
                        </v-layout>
                    </v-container>
                    <v-btn color="primary" round @click="guardarExamenFisico()">Guardar</v-btn>
                </v-stepper-content>
            </v-stepper>
        </v-flex>
        <template>
            <div class="text-center">
                <v-dialog v-model="preload_examensistema" persistent width="300">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Tranquilo procesamos tu solicitud !
                            <v-progress-linear indeterminate color="white" class="mb-0">
                            </v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </div>
        </template>
    </v-layout>
</template>
<script>
    import Swal from 'sweetalert2';
    import {
        EventBus
    } from "../../../../event-bus.js";
    import {
        parse
    } from "path";

    export default {
        created() {
            this.citaPaciente = this.$route.query.cita_paciente;
            EventBus.$on("recibir-paciente-examen-fisico", paciente => {
                this.paciente = paciente;
            });
        },
        data() {
            return {
                preload_examensistema: false,
                e6: 1,
                Perimetrocefalico: false,
                data: {
                    Peso: "",
                    Talla: "",
                    Imc: "",
                    ISC: "",
                    Clasificacion: "",
                    Perimetroabdominal: "",
                    Perimetrocefalico: "",
                    Frecucardiaca: "",
                    Pulsos: "",
                    Frecurespiratoria: "",
                    Temperatura: "",
                    Saturacionoxigeno: "",
                    Posicion: "",
                    Lateralidad: "",
                    Presionsistolica: "",
                    Presiondiastolica: "",
                    Presionarterialmedia: "",
                    Condiciongeneral: "",
                    Cabezacuello: "",
                    Ojosfondojos: "",
                    Agudezavisual: "",
                    Cardiopulmonar: "",
                    Abdomen: "",
                    Osteomuscular: "",
                    Extremidades: "",
                    Pulsosperifericos: "",
                    Neurologico: "",
                    Reflejososteotendinos: "",
                    Pielfraneras: "",
                    Genitourinario: "",
                    Examenmama: "",
                    Tactoretal: "",
                    Examenmental: ""
                },
                citaPaciente: 0
            };
        },
        mounted() {
            this.getLocalStorage();
            this.fetchExamen();
        },
        watch: {
            "data.Peso": function () {
                this.calcularIMC();
            },
            "data.Talla": function () {
                this.calcularIMC();
            },
            "data.Presionsistolica": function () {
                this.calcularMediaPresion();
            },
            "data.Presiondiastolica": function () {
                this.calcularMediaPresion();
            }
        },

        methods: {
            getPaciente() {
                if (this.paciente == null) {
                    EventBus.$emit("enviar-paciente", "recibir-paciente-examen-fisico");
                }
            },
            calcularMediaPresion() {
                this.data.Presionarterialmedia =
                    (parseInt(this.data.Presiondiastolica) * 2 +
                        parseInt(this.data.Presionsistolica)) /
                    3;
                this.data.Presionarterialmedia = Number.parseFloat(
                    this.data.Presionarterialmedia
                ).toFixed(1);
            },
            async  calcularIMC() {
                await this.getPaciente();

                if (this.paciente.Edad_Cumplida > 14) {
                    this.data.ISC = Math.sqrt(this.data.Peso * this.data.Talla / 3600).toFixed(3)
                }

                if (this.paciente.Edad_Cumplida > 17) {
                    this.data.Imc = this.data.Peso / Math.pow(this.data.Talla / 100, 2);

                    this.data.Imc = parseFloat(this.data.Imc).toFixed(1);
                    if (this.data.Imc < 16.0) {
                        this.data.Clasificacion = "Delgadez severa";
                    } else if (this.data.Imc > 16.0 && this.data.Imc < 16.99) {
                        this.data.Clasificacion = "Delgadez moderada";
                    } else if (this.data.Imc > 17.0 && this.data.Imc < 18.49) {
                        this.data.Clasificacion = "Delgadez aceptable";
                    } else if (this.data.Imc > 18.5 && this.data.Imc < 24.99) {
                        this.data.Clasificacion = "Normal";
                    } else if (this.data.Imc > 25.0 && this.data.Imc < 29.99) {
                        this.data.Clasificacion = "Pre-obeso";
                    } else if (this.data.Imc > 30.0 && this.data.Imc < 34.99) {
                        this.data.Clasificacion = "Obeso tipo I (riesgo moderado)";
                    } else if (this.data.Imc > 35.0 && this.data.Imc < 39.99) {
                        this.data.Clasificacion = "Obeso tipo II (riesgo severo)";
                    } else if (this.data.Imc >= 40.0) {
                        this.data.Clasificacion = "Obeso tipo III (riesgo muy severo)";
                    }
                } else if (this.paciente.Edad_Cumplida < 5) {
                    this.Perimetrocefalico = true;
                }
            },
            guardarAntropometricas() {
                if (!this.data.Peso) {
                   Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#383737db">El PESO es requerido!<span>'
                    })
                    return;
                } else if (!this.data.Talla) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#383737db">La TALLA es requerida!<span>'
                    })
                    return;
                } 
                // else if (!this.data.Perimetroabdominal) {
                //   Swal.fire({
                //         icon: 'error',
                //         title: '<span style="color:#ff0000de">El PERIMETRO ABDOMINAL es requerido !<span>'
                //     })
                //     return;
                // }
                this.data.Imc = parseInt(this.data.Imc);
                this.data.Peso = this.data.Peso.replace(/[,;]/g, '.');
                this.preload_examensistema = true,
                axios
                    .post(
                        "/api/examenfisico/antropometricas/" + this.citaPaciente,
                        this.data
                    )
                    .then(res => {
                      this.preload_examensistema = false;
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
                            title: '<span style="color:#fff">Antopometricas Guardadas con exito<span>'
                        });
                        this.e6 = 2;
                        localStorage.setItem("ExamenSistema", JSON.stringify(this.data));
                        EventBus.$emit('change_disabled_list_history', 'EXAMEN FÍSICO')
                    });
            },
            guardarSignosVitales() {
                let clasificacionPresion = "Normal";

                if (
                    this.data.Presionsistolica > 180 ||
                    this.data.Presiondiastolica > 120
                ) {
                    clasificacionPresion = "CRISIS DE HIPERTENSION";
                } else if (
                    this.data.Presionsistolica >= 140 ||
                    this.data.Presiondiastolica >= 90
                ) {
                    clasificacionPresion = "PRESION ARTERIAL ALTA (NIVEL 2)";
                } else if (
                    this.data.Presionsistolica >= 130 ||
                    this.data.Presiondiastolica >= 80
                ) {
                    clasificacionPresion = "PRESION ARTERIAL ALTA (NIVEL 1)";
                } else if (
                    this.data.Presionsistolica >= 120 &&
                    this.data.Presiondiastolica < 80
                ) {
                    clasificacionPresion = "ELEVADA";
                } else if (
                    this.data.Presionsistolica < 120 &&
                    this.data.Presiondiastolica < 80
                ) {
                    clasificacionPresion = "NORMAL";
                }
                this.data.Clasificacionpresion = clasificacionPresion;

                this.preload_examensistema = true,

                axios
                    .post("/api/examenfisico/signosvitales/" + this.citaPaciente, this.data)
                    .then(res => {
                      this.preload_examensistema = false;
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
                            title: '<span style="color:#fff">Signos vitales Guardados con exito<span>'
                        });
                        this.e6 = 3;
                        localStorage.setItem("ExamenSistema", JSON.stringify(this.data));
                    });
            },
            
            guardarExamenFisico() {
                this.getPaciente();
                EventBus.$emit("change_disabled_list_history", "EXAMEN FÍSICO");
                this.preload_examensistema = true,
                axios
                    .post(
                        "/api/examenfisico/" + this.citaPaciente + "/examenfisico",
                        this.data
                    )
                    .then(res => {
                      this.preload_examensistema = false;
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
                            title: '<span style="color:#fff">Examen Fisico Guardados con exito<span>'
                        });
                        this.e6 = 3;
                        localStorage.setItem("ExamenSistema", JSON.stringify(this.data));
                        this.$router.push("/historias/historiaclinica/diagnostico?cita_paciente=" + this.citaPaciente);
                    });
            },
            getLocalStorage() {
                let examen = JSON.parse(localStorage.getItem("ExamenSistema"));
                if (examen) {
                    this.data = examen;
                } else {
                    this.fetchExamen();
                }
            },

            fetchExamen() {
                axios.get('/api/examenfisico/' + this.citaPaciente + '/getExamenFisico')
                    .then(res => {
                        if(res.data.examen){
                            this.data = res.data.examen;
                        }
                        
                    });
            }

        }
    };

</script>
<style scoped>
</style>