<template>
    <v-container pa-0 fluid>
        <v-layout align-start justify-start row fill-height>
            <v-flex xs12 md12 sm12>
                <v-container fluid grid-list-md>
                    <v-layout row wrap>
                        <template>
                            <div class="text-center">
                                <v-dialog v-model="preload_certificado" persistent width="300">
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

                        <v-flex xs12 md3 sm6 v-if="can('salida.certificadoss')">
                            <v-card>
                                <v-img src="../images/empleado retirado.png" height="160px" widht="150px">
                                </v-img>
                                <v-card-actions>
                                    <v-layout row wrap justify-center>
                                        <v-card-title>
                                            <v-btn round color="primary" dark @click="dialog1 = true"
                                                style="font-size: 100%">EMPLEADO RETIRADO
                                            </v-btn>
                                        </v-card-title>
                                    </v-layout>
                                </v-card-actions>
                            </v-card>

                            <v-dialog v-model="dialog1" persistent max-width="400px">
                                <v-card>
                                    <v-toolbar style="font-size: 100%">
                                        <v-toolbar-title> EMPLEADO RETIRADO</v-toolbar-title>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-flex xs12 md12 sm12>
                                            <v-text-field label="Cedula" v-model="cedula" type="number">
                                            </v-text-field>
                                        </v-flex>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn color="error" round block @click="dialog1 = false">Cancelar</v-btn>
                                        <v-btn color="success" round block @click="generarPDFRetiro()">Generar</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-flex>

                        <v-flex xs12 md3 sm6 v-if="can('salida.certificadoss')">
                            <v-card>
                                <v-img src="../images/practicas.png" height="160px" widht="150px"></v-img>
                                <v-card-actions>
                                    <v-layout row wrap justify-center>
                                        <v-card-title>
                                            <v-btn round color="primary" dark @click="dialog2 = true"
                                                style="font-size: 100%">
                                                TERMINO DE PRACTICAS</v-btn>
                                        </v-card-title>
                                    </v-layout>
                                </v-card-actions>
                            </v-card>

                            <v-dialog v-model="dialog2" persistent max-width="400px">
                                <v-card>
                                    <v-toolbar style="font-size: 100%">
                                        <v-toolbar-title>
                                            TERMINO DE PRACTICAS</v-toolbar-title>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-flex xs12 md12 sm12>
                                            <v-text-field label="Cedula" v-model="numero" type="number" required>
                                            </v-text-field>
                                        </v-flex>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn color="error" round block @click="dialog2 = false">Cancelar</v-btn>
                                        <v-btn color="success" round block @click="generarPDFPracticas()">Generar
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-flex>

                        <v-flex xs12 md3 sm6>
                            <v-card>
                                <v-img src="../images/certificado laboral.png" height="160px" widht="150px"></v-img>
                                <v-card-actions>
                                    <v-layout row wrap justify-center>
                                        <v-card-title>
                                            <v-btn v-if="can('salida.certificadoss')" round color="primary" dark
                                                @click="dialog3 = true" style="font-size: 100%">
                                                CERTIFICADO LABORAL</v-btn>
                                            <v-btn v-else round color="primary" dark @click="user()"
                                                style="font-size: 100%">
                                                CERTIFICADO LABORAL</v-btn>
                                        </v-card-title>
                                    </v-layout>
                                </v-card-actions>
                            </v-card>

                            <v-dialog v-model="dialog3" persistent max-width="400px">
                                <v-card>
                                    <v-toolbar style="font-size: 100%">
                                        <v-toolbar-title>
                                            CERTFICADO LABORAL</v-toolbar-title>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-flex xs12 md12 sm12>
                                            <v-text-field label="Cedula" v-model="cedula1" type="number" required>
                                            </v-text-field>
                                        </v-flex>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn color="error" round block @click="dialog3 = false">Cancelar</v-btn>
                                        <v-btn color="success" round block @click="generarPDFActivos()">
                                            Generar</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-flex>

                        <v-flex xs12 md3 sm6>
                            <v-card>
                                <v-img src="../images/Hospital Universitario.png" height="160px" widht="150px">
                                </v-img>
                                <v-card-actions>
                                    <v-layout row wrap justify-center>
                                        <v-card-title>
                                            <v-btn v-if="can('salida.certificadodian')" color="primary" dark
                                                @click="dialog5 = true" round style="font-size: 100%">RETENCION INGRESOS
                                            </v-btn>
                                            <v-btn v-else color="primary" dark @click="dialog6 = true" round
                                                style="font-size: 100%">RETENCION INGRESOS</v-btn>
                                        </v-card-title>
                                    </v-layout>
                                </v-card-actions>
                            </v-card>

                            <v-dialog v-model="dialog5" persistent max-width="600px">
                                <v-card >
                                    <v-toolbar style="font-size: 100%">
                                        <v-toolbar-title>
                                            CERTIFICADO RETENCION INGRESOS</v-toolbar-title>
                                    </v-toolbar>
                                    <v-card-text class="pa-0">
                                        <v-form>
                                            <v-container grid-list-md>
                                                <v-layout wrap>
                                                    <v-flex xs12 md4 sm4>
                                                        <v-text-field label="Cedula" v-model="cedula3" type="number"
                                                            required>
                                                        </v-text-field>
                                                    </v-flex>

                                                    <v-flex xs12 md4 sm4>
                                                        <v-text-field label="Fecha Inicio" v-model="fechaI" type="date"
                                                            required>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 md4 sm4>
                                                        <v-text-field label="Fecha Final" v-model="fechaF" type="date"
                                                            required>
                                                        </v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-form>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn color="error" round block @click="dialog5 = false">Cancelar</v-btn>
                                        <v-btn color="success" round block @click="generarPDFRetencionIngresos()">
                                            Generar</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>

                            <v-dialog v-model="dialog6" persistent max-width="400px">
                                <v-card >
                                    <v-toolbar style="font-size: 100%">
                                        <v-toolbar-title>
                                            CERTIFICADO RETENCION INGRESOS</v-toolbar-title>
                                    </v-toolbar>
                                    <v-card-text class="pa-0">
                                        <v-form>
                                            <v-container grid-list-md>
                                                <v-layout wrap>

                                                    <v-flex xs12 md6 sm6>
                                                        <v-text-field label="Fecha Inicio" v-model="fechaI" type="date"
                                                            required>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 md6 sm6>
                                                        <v-text-field label="Fecha Final" v-model="fechaF" type="date"
                                                            required>
                                                        </v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-form>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn color="error" round block @click="dialog6 = false">Cancelar</v-btn>
                                        <v-btn color="success" round block @click="userretencion()">
                                            Generar</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import {
        mapGetters
    } from "vuex";
    export default {
        data() {
            return {
                dialog1: false,
                dialog2: false,
                dialog3: false,
                dialog4: false,
                dialog5: false,
                dialog6:false,

                cedula: "",
                cedula1: "",
                cedula2: "",
                cedula3: "",
                fechaI: "",
                fechaF: "",
                numero: "",
                preload_certificado: false,
                nameRules: [
                    (v) => !!v || "Name is required",
                    (v) => v.length <= 10 || "Name must be less than 10 characters",
                ],
            };
        },
        computed: {
            ...mapGetters(['can']),
            ...mapGetters(['fullName', 'can', 'avatar', 'UserEmail', 'id']),
            nameUser() {
                return this.id;
            }
        },
        methods: {
            generarPDFRetiro() {
                const data = {
                    type: "certificadoRetirado",
                    cedula: this.cedula,
                };
                if (!this.cedula) {
                    swal({
                        title: "Cedula Obligatoria",
                        text: "  ",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                } else {
                    this.preload_certificado = true;
                    axios
                        .post("/api/pdf/print-pdf", data, {
                            responseType: "arraybuffer",
                        })
                        .then((res) => {
                            this.preload_certificado = false;
                            this.auditoriaCertificado()
                            this.dialog1 = false;
                            this.cedula = '';
                            let blob = new Blob([res.data], {
                                type: "application/pdf",
                            });
                            let link = document.createElement("a");
                            link.href = window.URL.createObjectURL(blob);
                            window.open(link.href, "_blank");
                        })
                        .catch(e => {
                            let error = e.response.status;
                            if (error != '421') {
                                this.preload_certificado = false;
                                swal({
                                    title: "El empleado no existe",
                                    text: "  ",
                                    timer: 4000,
                                    icon: "error",
                                    buttons: false
                                });
                                this.dialog1 = false;
                                this.cedula = '';
                            } else {
                                this.preload_certificado = false;
                                swal({
                                    title: " El empleado esta activo",
                                    text: "  ",
                                    timer: 4000,
                                    icon: "error",
                                    buttons: false
                                });
                            }

                        });
                }
            },
            generarPDFPracticas() {
                const data = {
                    type: "FinPracticas",
                    numero: this.numero,
                };
                if (!this.numero) {
                    swal({
                        title: "Cedula Obligatoria",
                        text: "  ",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                } else {
                    this.preload_certificado = true;
                    axios
                        .post("/api/pdf/print-pdf", data, {
                            responseType: "arraybuffer",
                        })
                        .then((res) => {
                            this.preload_certificado = false;
                            this.auditoriaCertificado()
                            this.dialog2 = false;
                            this.numero = '';
                            let blob = new Blob([res.data], {
                                type: "application/pdf",
                            });
                            let link = document.createElement("a");
                            link.href = window.URL.createObjectURL(blob);
                            window.open(link.href, "_blank");
                        })
                        .catch(e => {
                            this.preload_certificado = false;
                            let error = e.response.status;
                            if (error == '421') {
                                this.preload_certificado = false;
                                swal({
                                    title: "El practicante esta activo",
                                    text: "  ",
                                    timer: 4000,
                                    icon: "error",
                                    buttons: false
                                });
                            } else {
                                this.preload_certificado = false;
                                swal({
                                    title: "El empleado no existe",
                                    text: "  ",
                                    timer: 4000,
                                    icon: "error",
                                    buttons: false
                                });
                                this.dialog2 = false;
                                this.numero = '';
                            }
                        });
                }
            },
            generarPDFActivos() {
                const data = {
                    type: "CertificadoActivo",
                    cedula1: this.cedula1,
                };
                if (!this.cedula1) {
                    swal({
                        title: "Cedula Obligatoria",
                        text: "  ",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                } else {
                    this.preload_certificado = true;
                    axios
                        .post("/api/pdf/print-pdf", data, {
                            responseType: "arraybuffer",
                        })
                        .then((res) => {
                            this.preload_certificado = false;
                            this.dialog3 = false;
                            this.cedula1 = '';
                            this.auditoriaCertificado()
                            let blob = new Blob([res.data], {
                                type: "application/pdf",
                            });
                            let link = document.createElement("a");
                            link.href = window.URL.createObjectURL(blob);
                            window.open(link.href, "_blank");
                        })
                        .catch(e => {
                            this.preload_certificado = false;
                            let error = e.response.status;
                            if (error == '422') {
                                this.preload_certificado = false;
                                swal({
                                    title: "El empleado esta inactivo",
                                    text: "  ",
                                    timer: 4000,
                                    icon: "error",
                                    buttons: false
                                });
                                this.dialog3 = false;
                                this.numcedula1 = '';
                            } else {
                                this.preload_certificado = false;
                                swal({
                                    title: "El empleado no existe o tiene estado Retirado",
                                    text: "  ",
                                    timer: 4000,
                                    icon: "error",
                                    buttons: false
                                });
                            }
                        });
                }
            },

            generarPDFRetencionIngresos() {
                const data = {
                    type: "CertificadoRetencionIngresos",
                    cedula3: this.cedula3,
                    fechaI: this.fechaI,
                    fechaF: this.fechaF,
                    tipo: 0
                };
                // console.log(data);
                if (!this.cedula3) {
                    swal({
                        title: "Cedula Obligatoria",
                        text: "  ",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                }else  if (!this.fechaI) {
                    swal({
                        title: "Fecha Inicio Obligatoria",
                        text: "  ",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                }else if (!this.fechaF) {
                    swal({
                        title: "Fecha Final Obligatoria",
                        text: "  ",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                }else {
                    this.preload_certificado = true;
                    axios
                        .post("/api/pdf/print-pdf", data, {
                            responseType: "arraybuffer",
                        })
                        .then((res) => {
                            (this.dialog5 = false), (this.preload_certificado = false);
                            // this.auditoriaCertificado()
                            this.cedula3 = '';
                                this.fechaI="";
                                this.fechaF="";
                            let blob = new Blob([res.data], {
                                type: "application/pdf",
                            });
                            let link = document.createElement("a");
                            link.href = window.URL.createObjectURL(blob);
                            window.open(link.href, "_blank");
                        })
                        .catch((e) => {
                            let error = e.response.status;
                            if (error == 421){
                            this.preload_certificado = false;
                                swal({
                                title: "La fecha no conside con la del sistema",
                                text: " ",
                                timer: 4000,
                                icon: "error",
                                buttons: false
                            });
                            }else{
                            this.preload_certificado = false;
                                swal({
                                title: "La cedula no existe en el sistema",
                                text: " ",
                                timer: 4000,
                                icon: "error",
                                buttons: false
                            });
                            }
                            this.dialog5 = true;
                        });
                }
            },
            auditoriaCertificado() {
                const userGenero = {
                    id: this.id
                };
                axios.post('/api/intranet/auditoriaCertificado', userGenero)
                    .then(res => {
                        this.$alerSuccess('Certificado Generado en exito!');
                    })
            },

            user() {
                const data = {
                    type: "CertificadoActivo",
                    id: this.id,
                };
                axios
                    .post("/api/pdf/print-pdf", data, {
                        responseType: "arraybuffer",
                    })
                    .then(res => {
                        this.preload_certificado = false;
                        this.cedula1 = '';
                        this.auditoriaCertificado()
                        let blob = new Blob([res.data], {
                            type: "application/pdf",
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    }).catch(e => {
                        this.preload_certificado = false;
                        let error = e.response.status;
                        if (error != '422') {
                            this.preload_certificado = false;
                            swal({
                                title: "Comunicate con recursos humanos",
                                text: "  ",
                                timer: 4000,
                                icon: "error",
                                buttons: false
                            });
                            this.dialog3 = false;
                            this.numcedula1 = '';
                        } else {
                            this.preload_certificado = false;
                            swal({
                                title: "El empleado esta inactivo",
                                text: "  ",
                                timer: 4000,
                                icon: "error",
                                buttons: false
                            });
                        }
                    });
            },
            userretencion() {
                const data = {
                    type: "CertificadoRetencionIngresos",
                    id: this.id,
                    fechaI: this.fechaI,
                    fechaF: this.fechaF,
                    tipo: 1
                };
                if (!this.fechaI) {
                    swal({
                        title: "Fecha Inicio Obligatoria",
                        text: "  ",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                }else if (!this.fechaF) {
                    swal({
                        title: "Fecha Final Obligatoria",
                        text: "  ",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                } else {
                    this.preload_certificado = true;
                    axios
                        .post("/api/pdf/print-pdf", data, {
                            responseType: "arraybuffer",
                        })
                        .then((res) => {
                            (this.dialog5 = false), (this.preload_certificado = false);
                            // this.auditoriaCertificado()
                            this.fechaI="";
                            this.fechaF="";
                            let blob = new Blob([res.data], {
                                type: "application/pdf",
                            });
                            let link = document.createElement("a");
                            link.href = window.URL.createObjectURL(blob);
                            window.open(link.href, "_blank");
                        })
                        .catch((e) => {
                            this.preload_certificado = false;
                            let errors = e.response.status;
                            if(errors == 422){
                                swal({
                                title: "Para sacar un nuevo certificado comunicate con",
                                text: "coordinacion.nomina@sumimedical.com",
                                timer: '',
                                icon: "error",
                                buttons: 'OK'
                            });
                            }else{
                                swal({
                                title: "La fecha con conside con la del sistema",
                                text: " ",
                                timer: 4000,
                                icon: "error",
                                buttons: false
                            });
                            }
                            this.dialog5 = false;
                            this.cedula3 = '';
                        });
                }
            }
        },
    };

</script>

<style>
</style>
