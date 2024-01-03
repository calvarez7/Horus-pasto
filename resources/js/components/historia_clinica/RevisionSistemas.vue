<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Revisión por sistemas</span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1"
                            v-show="datosCita.Tipo_agenda == '23'"
                            v-model="revisionSistemaHC.observaciones_generales" label="Aspecto General">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxOftalmologico"
                            label="Oftalmologico:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxOftalmologico == true"
                            v-model="revisionSistemaHC.Oftalmologico">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxOtorrinoralingologico"
                            label="Otorrinolaringologico:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxOtorrinoralingologico == true"
                            v-model="revisionSistemaHC.Otorrinoralingologico"></v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxLinfatico"
                            label="Hematopoyetico:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxLinfatico == true"
                            v-model="revisionSistemaHC.Linfatico">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxEndocrinologico"
                            label="Endocrinologico:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxEndocrinologico == true"
                            v-model="revisionSistemaHC.Endocrinologico"></v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxCardiovascular"
                            label="Cardiovascular:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxCardiovascular == true"
                            v-model="revisionSistemaHC.Cardiovascular"></v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxRespiratorio"
                            label="Respiratorio:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxRespiratorio == true"
                            v-model="revisionSistemaHC.Respiratorio" label="Descripción">
                        </v-textarea>
                        <v-select v-model="revisionSistemaHC.SintomaticoRespiratorio" v-if="revisionSistemaHC.checkboxRespiratorio == true"
                            :items="sino" label="Sintomatico respiratorio:">
                        </v-select>
                    </v-flex>
                     <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxGastrointestinal"
                            label="Gastrointestinal:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxGastrointestinal == true"
                            v-model="revisionSistemaHC.Gastrointestinal"></v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxGenitourinario"
                            label="Genitourinario:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxGenitourinario == true"
                            v-model="revisionSistemaHC.Genitourinario" label="Descripción"></v-textarea>

                         <v-autocomplete v-model="revisionSistemaHC.FlujoVaginal" v-show="datosCita.sexo == 'F'"  v-if="revisionSistemaHC.checkboxGenitourinario == true"
                            :items="sinoaplica" label="Flujo vaginal:">
                        </v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxOsteomioarticular"
                            label="Osteomuscular:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxOsteomioarticular == true"
                            v-model="revisionSistemaHC.Osteomioarticular"></v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxTegumentario"
                            label="Tegumentario:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxTegumentario == true"
                            v-model="revisionSistemaHC.Tegumentario" label="Descripción">
                        </v-textarea>
                         <v-select v-model="revisionSistemaHC.TegumentarioSiNo" v-if="revisionSistemaHC.checkboxTegumentario == true"
                            :items="sino" label="Sintomatico en piel:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxNeurologico"
                            label="Sistema nervioso:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxNeurologico == true"
                            v-model="revisionSistemaHC.Neurologico">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox value="1" color="primary" v-model="revisionSistemaHC.checkboxNorefiere"
                            label="Otro:">
                        </v-checkbox>
                        <v-textarea name="input-7-1" v-if="revisionSistemaHC.checkboxNorefiere == true"
                            v-model="revisionSistemaHC.Norefiere">
                        </v-textarea>
                    </v-flex>
                </v-layout>
                <v-btn color="success" @click="saveRevisionSistemas()">Guardar y Seguir</v-btn>
            </v-container>
        </v-form>
        <v-dialog v-model="preloadHistoria" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Procesamos su información!
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    import Swal from 'sweetalert2';
    export default {
        name: "RevisionSistemas",
        props: {
            datosCita: Object
        },
        data() {
            return {
                preloadHistoria: false,
                revisionSistemaHC: {
                    observaciones_generales: '',
                    checkboxOftalmologico: false,
                    Oftalmologico: '',
                    checkboxGenitourinario: false,
                    Genitourinario: '',
                    checkboxOtorrinoralingologico: false,
                    Otorrinoralingologico: '',
                    checkboxLinfatico: false,
                    Linfatico: '',
                    checkboxOsteomioarticular: false,
                    Osteomioarticular: '',
                    checkboxNeurologico: false,
                    Neurologico: '',
                    checkboxCardiovascular: false,
                    Cardiovascular: '',
                    checkboxTegumentario: false,
                    Tegumentario: '',
                    checkboxRespiratorio: false,
                    Respiratorio: '',
                    checkboxEndocrinologico: false,
                    Endocrinologico: '',
                    checkboxGastrointestinal: false,
                    Gastrointestinal: '',
                    checkboxNorefiere: false,
                    Norefiere: '',
                    TegumentarioSiNo:'',
                    FlujoVaginal:'',
                    SintomaticoRespiratorio:'',
                },
                sino: ['Si', 'No'],
                sinoaplica: ['Si', 'No','No aplica'],
            }
        },
        created() {
            this.fetchRevisionSistemas();
        },
        methods: {
            saveRevisionSistemas() {
                if (this.revisionSistemaHC.checkboxOftalmologico == true) {
                    if (!this.revisionSistemaHC.Oftalmologico) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Oftalmologico es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Oftalmologico.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Oftalmologico debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                if (this.revisionSistemaHC.checkboxGenitourinario == true) {
                    if (!this.revisionSistemaHC.Genitourinario) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Genitourinario es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Genitourinario.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Genitourinario debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                if (this.revisionSistemaHC.checkboxOtorrinoralingologico == true) {
                    if (!this.revisionSistemaHC.Otorrinoralingologico) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Otorrinoralingologico es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Otorrinoralingologico.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Otorrinoralingologico debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                if (this.revisionSistemaHC.checkboxLinfatico == true) {
                    if (!this.revisionSistemaHC.Linfatico) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Linfatico es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Linfatico.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Linfatico debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                if (this.revisionSistemaHC.checkboxOsteomioarticular == true) {
                    if (!this.revisionSistemaHC.Osteomioarticular) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Osteomioarticular es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Osteomioarticular.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Osteomioarticular debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                if (this.revisionSistemaHC.checkboxNeurologico == true) {
                    if (!this.revisionSistemaHC.Neurologico) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Neurologico es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Neurologico.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Neurologico debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                if (this.revisionSistemaHC.checkboxCardiovascular == true) {
                    if (!this.revisionSistemaHC.Cardiovascular) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Cardiovascular es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Cardiovascular.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Cardiovascular debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                if (this.revisionSistemaHC.checkboxTegumentario == true) {
                    if (!this.revisionSistemaHC.Tegumentario) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Tegumentario es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Tegumentario.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Tegumentario debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                if (this.revisionSistemaHC.checkboxRespiratorio == true) {
                    if (!this.revisionSistemaHC.Respiratorio) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Respiratorio es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Respiratorio.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Respiratorio debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                if (this.revisionSistemaHC.checkboxEndocrinologico == true) {
                    if (!this.revisionSistemaHC.Endocrinologico) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Endocrinologico es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Endocrinologico.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Endocrinologico debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                if (this.revisionSistemaHC.checkboxGastrointestinal == true) {
                    if (!this.revisionSistemaHC.Gastrointestinal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Gastrointestinal es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Gastrointestinal.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Gastrointestinal debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                if (this.revisionSistemaHC.checkboxNorefiere == true) {
                    if (!this.revisionSistemaHC.Norefiere) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Otro es obligatorio!</b>',
                        })
                        return;
                    } else if (this.revisionSistemaHC.Norefiere.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: '<b>El campo Otro debe tener mas de 5 caracteres!</b>',
                        })
                        return;
                    }
                }
                this.revisionSistemaHC.cita_paciente_id = this.datosCita.cita_paciente_id
                axios.post('/api/historia/saveRevisionSistemas', this.revisionSistemaHC)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                    })
            },
            fetchRevisionSistemas() {
                this.preloadHistoria = true;
                axios.get(`/api/historia/fetchRevisionSistemas/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.revisionSistemaHC = res.data;
                        this.preloadHistoria = false
                    });
            },
        }
    }

</script>
