<template>
    <v-card>
        <v-card-title class="headline success" style="color:white">
            <span class="title layout justify-center font-weight-light">Generar Anexo</span>
        </v-card-title>
        <vDivider />
        <v-card-text>
            <v-container
                fluid
                grid-list-md >
                <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation>
                    <v-layout wrap row>
                        <v-flex xs12>
                            <v-radio-group
                                class="title layout justify-center font-weight-light"
                                v-model="data.tipo_anexo"
                                :mandatory="false"
                                :readonly="readonly"
                                column
                                :rules="rules.tipo_anexo">
                                    <vRadio color="primary"
                                        label="Urgencias (Anexo 2)"
                                        value="2">
                                <template v-slot:label>
                                    <div>Urgencias (Anexo 2) <strong class="error--text">USO EXCLUSIVO PARA CÓDIGOS DE ATENCIÓN DE URGENCIAS POR IPS</strong></div>
                                </template>
                                </vRadio>
                                    <vRadio color="primary"
                                        label="Solicitud de Autorización (Anexo 3)"
                                        value="3" />
                                    <vRadio color="primary"
                                        label="Remisiones (Anexo 9)"
                                        value="9" />
                            </v-radio-group>
                        </v-flex>
                        <v-flex xs6>
                            <vAutocomplete
                                :items="especialidades"
                                label="Especialidad:"
                                no-data-text="No se encuentra especialidad con ese nombre"
                                :readonly="readonly"
                                :rules="rules.especialidad"
                                v-model="data.Especialidad_remi"/>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-autocomplete
                                :items="filteredCie10s"
                                item-text="fullName"
                                item-value="id"
                                label="Diagnostico Cie10:"
                                multiple
                                no-data-text="No se encuentra diagnóstico cie10"
                                :readonly="readonly"
                                :rules="rules.ruleCie10"
                                v-model="data.cie10s">
                                <template v-slot:selection="data">
                                    <v-chip
                                        class="chip--select-multi"
                                        close
                                        @input="remove(data.item)"
                                        :selected="data.selected" >
                                        {{ data.item.fullName }}
                                    </v-chip>
                                </template>
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12>
                            <input
                                @change="validateAdjunto"
                                id="adjuntos"
                                multiple
                                :readonly="readonly"
                                ref="adjuntos"
                                type="file"/>
                        </v-flex>
                        <v-flex xs6>
                            <span class="error--text caption" v-show="!rules.adjunto">Debe adjuntar al menos un archivo</span>
                        </v-flex>
                        <v-flex xs12>
                            <v-checkbox
                                color="primary"
                                label="Recuerde que los datos personales de los usuarios se debe tratar según la ley 1551 de 2012"
                                :disabled="readonly"
                                :rules="rules.habeasData" />
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <vSpacer />
                        <v-flex xs1>
                            <v-btn  color="success"
                                    @click="saveReferencia"
                                    outline
                                    round>
                                    Enviar
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-form>
            </v-container>
        </v-card-text>
    </v-card>
</template>

<script>
    export default {
        name:'PacienteReferenceForm',
        props:{
            data: {
                type: Object,
                default: () => {
                    return {
                        adjuntos: [],
                        cie10s: [],
                        Especialidad_remi: null,
                        tipo_anexo: null,
                    }
                }
            },
            readonly: {
                type: Boolean,
                default: () => false
            }
        },
        data: () => {
            return {
                cie10s: [],
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
                rules: {
                    adjunto: true,
                    especialidad: [v => !!v || 'Se debe escoger especialidad.'],
                    habeasData: [v => !!v || 'Es requerido.'],
                    ruleCie10: [(v) => v.length > 0 || 'Se debe escoger al menos un diagnóstico cie10.'],
                    tipo_anexo: [v => !!v || 'Se debe escoger anexo.']
                },
                valid: true,
            }
        },
        computed: {
            filteredCie10s(){
                return this.cie10s.map(cie => {
                    return {
                        id: cie.id,
                        fullName: `${cie.Codigo_CIE10} - ${cie.Descripcion}`
                    }
                })
            }
        },
        mounted(){
            this.fetchCie10s()
        },
        methods:{
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },
            remove(item){
                const index = this.data.cie10s.findIndex(i =>  i === item.id )
                if (index >= 0) this.data.cie10s.splice(index, 1)
            },
            resetForm(){
                this.$refs.adjuntos.value = ''
                this.$refs.form.reset()
            },
            saveReferencia(){
                if(this.validateForm()){
                    this.data.adjuntos = this.$refs.adjuntos.files;
                    const formData = new FormData();

                    formData.append('Especialidad_remi',this.data.Especialidad_remi)
                    formData.append('tipo_anexo',this.data.tipo_anexo)

                    for (let i = 0; i < this.data.cie10s.length; i++) {
                        formData.append('cie10s[]',this.data.cie10s[i])
                    }

                    for (let i = 0; i < this.data.adjuntos.length; i++) {
                        formData.append(`adjuntos[]`, this.data.adjuntos[i]);
                    }

                    this.$emit('saveReferencia',formData);
                }
            },
            validateAdjunto(){
                if(this.$refs.adjuntos.files.length == 0) return this.rules.adjunto = false;
                return this.rules.adjunto = true

            },
            validateForm() {
                let form = this.$refs.form.validate()
                let adjunto = this.validateAdjunto()
                if(form && adjunto) return true;
                return false
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
