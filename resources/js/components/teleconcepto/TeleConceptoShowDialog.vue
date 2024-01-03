<template>
    <v-layout v-show="showTeleDialog" row justify-center>
        <v-dialog v-model="showTeleDialog" persistent max-width="1400px">
            <v-card>
                <v-card-text>
                    <v-container>
                        <v-form ref="form">
                            <v-layout wrap>
                                <v-flex xs12 pa-0>
                                    <slot />
                                </v-flex>
                                <v-flex xs12 mt-3 v-if="inPendingPage">
                                    <v-card>
                                        <v-card-title class="headline grey lighten-2" primary-title>
                                            Respuesta especialista
                                        </v-card-title>
<!--                                        <v-card-title primary-title>-->
<!--                                            <span class="layout justify-center font-weight-black"> Motivo: {{ data.descripcion }}</span>-->
<!--                                        </v-card-title>-->
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-layout row wrap>
<!--                                                <v-flex xs12>-->
<!--                                                    <p>{{ data.ResumenHc }}</p>-->
<!--                                                </v-flex>-->
<!--                                                <v-flex xs12>-->
<!--                                                    <ul>-->
<!--                                                        <li v-for="(cie, index) in data.cie10s" :key="index">{{ cie.Codigo_CIE10 }} - {{ cie.Descripcion }}</li>-->
<!--                                                    </ul>-->
<!--                                                </v-flex>-->
<!--                                                <v-flex xs12>-->
<!--                                                    <v-btn  color="warning"-->
<!--                                                            :key="index"-->
<!--                                                            :href="adjunto.Ruta"-->
<!--                                                            outline-->
<!--                                                            round-->
<!--                                                            small-->
<!--                                                            target="_blank"-->
<!--                                                            v-for="(adjunto, index) in data.adjuntos">-->
<!--                                                        Abrir archivo-->
<!--                                                    </v-btn>-->
<!--                                                </v-flex>-->
                                                <v-flex xs12 mt-2x>
                                                    <vTextarea
                                                        label="Escribe una observación"
                                                        v-model="data.Respuesta"
                                                        :rules="[v => !!v || 'Por favor escribir una respuesta']"/>
                                                </v-flex>
                                            </v-layout>
                                        </v-card-text>
                                    </v-card>
                                </v-flex>
                                <v-flex xs12 mt-3 v-if="!inPendingPage">
                                    <v-card>
                                        <v-card-title primary-title>
                                            <span class="layout justify-center font-weight-black">Respuesta especialista</span>
                                        </v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-layout row wrap>
                                                <v-flex xs12 mt-2x>
                                                    <div>
                                                        <p>{{ data.Respuesta }}</p>
                                                    </div>
                                                </v-flex>
                                            </v-layout>
                                        </v-card-text>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-form>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" dark @click="printPDF()">Imprimir</v-btn>
                    <v-btn color="red" dark @click="closeDialog()">Cerrar</v-btn>
                    <v-btn color="blue darken-1" flat @click="responder()" v-if="inPendingPage && can('teleconcepto.reply')">Responder</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        name: 'TeleConceptoShowDialog',
        props: {
            showTeleDialog: Boolean,
            data: Object,
            inPendingPage: Boolean
        },
        data: () => {
            return {
                teleconcepto: {
                    type: 'teleconcepto',
                    motivo: '',
                    ResumenHc: '',
                    Respuesta: '',
                    Firma: '',
                    paciente: {},
                    id: null,
                },


            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        methods:{
            printPDF() {
                this.teleconcepto.motivo = this.data.descripcion;
                this.teleconcepto.ResumenHc = this.data.ResumenHc;
                this.teleconcepto.Respuesta = this.data.Respuesta;
                this.teleconcepto.Firma = this.data.Firma;
                this.teleconcepto.Registromedico = this.data.Registromedico;
                this.teleconcepto.especialidad_medico = this.data.especialidad_medico;
                this.teleconcepto.Ut = this.data.Ut;
                this.teleconcepto.id = this.data.id;

                axios
                    .post("/api/pdf/print-pdf", this.teleconcepto, {
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
            closeDialog(){
                this.$refs.form.reset()
                this.$emit('closeDialog')
            },
            resetForm(){
                this.$refs.form.reset()
            },
            responder(){
                if(this.$refs.form.validate()){
                    this.$emit('responder',this.data.Respuesta);
                }
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
