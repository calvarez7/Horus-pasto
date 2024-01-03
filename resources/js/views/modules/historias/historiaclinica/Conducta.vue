<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-stepper v-model="e6" vertical>
                <v-stepper-step :complete="e6 > 1" editable :edit-icon="$vuetify.icons.complete" step="1">FINAL
                </v-stepper-step>

                <v-stepper-content step="1">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-textarea name="input-7-1" label="Plan de manejo" value="" v-model="data.Planmanejo">
                            </v-textarea>
                            <v-textarea name="input-7-1" label="Recomendaciones" auto-grow
                                v-model="data.Recomendaciones" value></v-textarea>
                            <v-layout row wrap>
                                <v-flex xs12 sm4>
                                    <v-autocomplete :items="[
									'RIA Primera Infancia','RIA Infancia','RIA Adolescencia','RIA Juventud', 'RIA Adulto',
									'RIA Adulto Mayor','RIA Materno Perinatal', 'Control', 'Interconsulta', 'Domiciliaria',
									'Urgencias', 'Hospitalización (Remision)'
								]" label="Destino del paciente" append-icon="search" v-model="data.Destinopaciente"></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm4 v-show="data.Destinopaciente == 'Hospitalización (Remision)'">
                                    <vAutocomplete
                                        :items="especialidades"
                                        label="Especialidad:"
                                        no-data-text="No se encuentra especialidad con ese nombre"
                                        v-model="data.Especialidad_remi"/>
                                </v-flex>
                                <v-flex xs12 sm4>
                                    <v-autocomplete :items="[
									'Atención del parto, del embarazo y posparto', 'Atención del recién nacido',
									'Atención planificación familiar', 'Atención crecimiento y desarrollo',
									'Atención joven Sano', 'Detención alteraciones del embarazo',
									'Detención alteraciones del adulto', 'Detención alteraciones agudeza Visual',
									'Enfermedad Profesional', 'Teleconsulta', 'No aplica'
								]" label="Finalidad" append-icon="search" v-model="data.Finalidad"></v-autocomplete>
                                </v-flex>
                            </v-layout>
                            <template>
                                <div class="text-center">
                                    <v-dialog v-model="preload_conducta" persistent width="300">
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
                        </v-container>
                    </v-card>
                    <v-btn color="primary" @click="guardarConducta()">Guardar y terminar</v-btn>|
                    <!-- <v-btn color="dark" round @click="printRecomendation()">Descargar recomendaciones</v-btn> -->
                </v-stepper-content>
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
            // this.getLocalStorage();
            EventBus.$emit("step-historia", 7);
        },
        data() {
            return {
                preload_conducta: false,
                e6: 1,
                Diagnostico: '',
                data: {
                    Planmanejo: "",
                    Recomendaciones: "",
                    Destinopaciente: "",
                    Finalidad: "",
                    Especialidad_remi :""
                },
                especialidades: [
                    'ALERGOLOGIA',
                    'ANESTESIOLOGIA',
                    'AUDIOLOGIA',
                    'BIOENERGETICA',
                    'CARDIOLOGIA',
                    'CIRUGIA BARIATRICA',
                    'CIRUGIA CARDIOVASCULAR',
                    'CIRUGIA GENERAL',
                    'CIRUGIA MAXILOFACIAL',
                    'CIRUGIA PLASTICA',
                    'COLOPROCTOLOGIA',
                    'COORDINACION MEDICA',
                    'DERMATOLOGIA',
                    'ENDOCRINOLOGIA',
                    'GINECOLOGIA Y OBSTETRICIA',
                    'HEMATOLOGIA',
                    'INFECTOLOGIA',
                    'MASTOLOGIA',
                    'MEDICINA DEL DEPORTE',
                    'MEDICINA DEL DOLOR',
                    'MEDICINA DEL TRABAJO',
                    'MEDICINA FAMILIAR',
                    'MEDICINA FISICA Y REHABILITACION',
                    'MEDICINA GENERAL',
                    'MEDICINA INTERNA',
                    'NEFROLOGIA',
                    'NEUMOLOGIA',
                    'NEUROCIRUGIA',
                    'NEUROLOGIA',
                    'OFTALMOLOGIA',
                    'ONCOLOGIA CLINICA',
                    'ORTOPEDIA Y TRAUMATOLOGIA',
                    'OTORRINOLARINGOLOGIA',
                    'PEDIATRIA',
                    'PSIQUIATRIA',
                    'REUMATOLOGIA',
                    'SALUD FAMILIAR',
                    'TOXICOLOGIA CLINICA',
                    'UROLOGIA',
                ],
                citaPaciente: 0,
                paciente: {}
            };
        },
        mounted() {
            //this.getLocalStorage();
            this.fetchConducta();
        },
        methods: {
            guardarConducta() {
                if (this.data.Planmanejo.length < 5) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Plan de manejo debe ser minimo de 5 caracteres !<span>'
                    })
                    return;
                } else if (this.data.Recomendaciones.length < 5) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Recomendaciones de consulta debe ser minimo de 5 caracteres!<span>'
                    })
                    return;
                } else if (!this.data.Destinopaciente) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Destino del paciente es requerido!<span>'
                    })
                    return;
                } else if (this.data.Destinopaciente == 'Hospitalización (Remision)' && !this.data.Especialidad_remi) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Debe colocar una especialidad para generar una remision!<span>'
                    })
                    return;
                } else if (!this.data.Finalidad) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">La FINALIDAD es requerida!<span>'
                    })
                    return;
                }

                //   if (this.Diagnostico == '') {
                // 	swal({
                // 	  title: "Conducta",
                // 	  text:
                // 		"Por favor ingrese un diagnostico principal!",
                // 	  timer: 2000,
                // 	  icon: "error",
                // 	  buttons: false
                // 	});
                // 	return;
                //   }

                // if (this.data.Destinopaciente.length < 2) {
                //     swal("Destino del paciente debe ser minimo de 2 caracteres")
                //     return;
                // }
                this.preload_conducta = true;
                axios
                    .post("/api/conducta/" + this.citaPaciente + "/final", this.data)
                    .then(res => {
                        this.preload_conducta = false;
                        this.e6 = 3;

                        localStorage.setItem("Conducta", JSON.stringify(this.data));

                        swal({
                            title: res.data.message,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        // this.$router.push('/historiaclinica/gineco?cita_paciente='+this.citaPaciente);
                        EventBus.$emit("change_disabled_list_history", "CONDUCTA");
                        EventBus.$emit("send-recommendation", this.data);
                        EventBus.$emit("call-order");
                    });
            },
            printRecomendation() {
                if (!this.data.Recomendaciones) {
                    swal({
                        title: "Impresión observaciones",
                        text: "Se requiere una recomendación.",
                        icon: "error"
                    });
                } else {
                    axios
                        .get(`/api/paciente/getPacienteWithCita/${this.citaPaciente}`)
                        .then(res => {
                            this.paciente = res.data;

                            if (this.paciente.id) {
                                var pdf = {};

                                pdf = this.getPDFRecomendation();

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
                            }
                        })
                        .catch(err => this.showError(err.response.data));
                }
            },
            getPDFRecomendation() {
                return (this.recomendacion = {
                    Primer_Nom: this.paciente.Primer_Nom,
                    Segundo_Nom: this.paciente.SegundoNom,
                    Primer_Ape: this.paciente.Primer_Ape,
                    Segundo_Ape: this.paciente.Segundo_Ape,
                    Tipo_Doc: this.paciente.Tipo_Doc,
                    Num_Doc: this.paciente.Num_Doc,
                    Edad_Cumplida: this.paciente.Edad_Cumplida,
                    Sexo: this.paciente.Sexo,
                    IPS: this.paciente.NombreIPS,
                    Direccion_Residencia: this.paciente.Direccion_Residencia,
                    Correo: this.paciente.Correo,
                    Telefono: this.paciente.Telefono,
                    Tipo_Afiliado: this.paciente.Tipo_Afiliado,
                    Estado_Afiliado: this.paciente.Estado_Afiliado,
                    orden_id: this.order_id,
                    type: "observacion",
                    observaciones: this.data.Recomendaciones,
                    cita_paciente_id: this.citaPaciente
                });
            },
            getLocalStorage() {
                let conducta = JSON.parse(localStorage.getItem("Conducta"));
                let Diagnostico = JSON.parse(localStorage.getItem("Diagnostico"));
                if (Diagnostico) {
                    this.Diagnostico = Diagnostico;
                }
                if (conducta) {
                    this.data = conducta;
                } else {
                    this.fetchConducta();
                }
            },

            fetchConducta() {
                axios
                    .post('/api/conducta/' + this.citaPaciente + '/getConductaByCita')
                    .then(res => {
                        if(res.data){
                            this.data = res.data
                        }
                    })
                    .catch(err => console.log(err.response));
            },
        }
    };

</script>
