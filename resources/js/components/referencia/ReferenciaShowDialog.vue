<template>
    <v-layout v-show="showReferenceDialog" row justify-center>
        <v-dialog v-model="showReferenceDialog" persistent max-width="1200px">
            <v-card>
                <v-card-text>
                <v-container>
                    <v-form ref="form">
                        <v-layout wrap>
                            <v-flex xs12 pa-0>
                                <slot />
                            </v-flex>
                            <v-flex xs12>
                                <v-radio-group
                                    class="title layout justify-center font-weight-light"
                                    v-model="data.Tipo_anexo"
                                    :mandatory="false"
                                    row>
                                        <vRadio color="primary"
                                            label="Urgencias (Anexo 2)"
                                            value="2" />
                                        <vRadio color="primary"
                                            label="Solicitud de Autorización (Anexo 3)"
                                            value="3" />
                                        <vRadio color="primary"
                                            label="Remisiones (Anexo 9)"
                                            value="9" />
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs12>
                                <ul>
                                    <li v-for="(cie, index) in data.cie10s" :key="index">{{ cie.Codigo_CIE10 }} - {{ cie.Descripcion }}</li>
                                </ul>
                            </v-flex>
                            <v-flex xs12>
                                <vAutocomplete
                                    :items="tipo_solicitud_list"
                                    :readonly="!inPendingPage"
                                    label="Tipo Solicitud"
                                    no-data-text="tipo de solicitud no encontrada"
                                    v-model="data.tipo_solicitud"
                                    :rules="[v => !!v || 'Se debe escoger tipo de solicitud.']" />
                            </v-flex>
                            <v-flex xs2>
                                <v-btn  color="warning"
                                        :key="index"
                                        :href="adjunto.Ruta"
                                        outline
                                        round
                                        small
                                        target="_blank"
                                        v-for="(adjunto, index) in data.adjuntos">
                                    Abrir archivo
                                </v-btn>
                            </v-flex>
                            <v-flex xs2 >
                                <v-btn v-show="data.cita_paciente != null" color="info"
                                        round
                                        small
                                        outline @click="find(data.referenciaid)">
                                    Historia Clinica
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-form>
                </v-container>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="red" dark @click="closeDialog()"><v-icon>mdi-close-circle</v-icon> Cerrar</v-btn>
                <v-btn color="blue darken-1" flat @click="gestionar()" v-if="inPendingPage && can('referencia.manage')">Gestionar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        name: 'ReferenciaShowDialog',
        props: {
            showReferenceDialog: Boolean,
            data: Object,
            inPendingPage: Boolean
        },

        data: () => {
            return {
                referenciaid:'',
                historiapaciente: [],
                tipo_solicitud: '',
                tipo_solicitud_list: [
                    'Ambulancia',
                    'Aval servicios intrahospitalarios',
                    'Ayuda diagnostica externa',
                    'Contraremision',
                    'Codigo atencion urgencias',
                    'Codigo hospitalizacion',
                    'Interconsulta red externa',
                    'Medicina domiciliaria',
                    'Remision Mayor complejidad',
                    'Remision Menor complejidad'
                ]
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        methods:{
            closeDialog(){
                this.$refs.form.reset()
                this.$emit('closeDialog')
            },
            gestionar(){
                if(this.$refs.form.validate()){
                    this.$emit('gestionar',this.data.tipo_solicitud);
                }
            },
            printhc() {
                    let pdf = [];
                        pdf = this.historiapaciente;
                        pdf.type = 'historia';
                    axios.post("/api/pdf/print-pdf", pdf, {
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
            find(id) {
                this.referenciaid = id;
                axios.get("/api/historiapaciente/historiaid/" + this.referenciaid)
                    .then(res => {
                        this.historiapaciente = res.data[0];
                        this.printhc(this.historiapaciente);
                    });
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
