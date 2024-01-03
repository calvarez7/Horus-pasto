<template>
    <v-card>
        <v-card-title class="headline success" style="color:white">
            <span class="title layout justify-center font-weight-light">Generar Teleorientacion</span>
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
                        <v-flex xs6>
                            <vSelect
                                :items="tipoSolicitud"
                                label="Tipo solicitud:"
                                no-data-text="No hay tipo de solicitud"
                                :readonly="readonly"
                                :rules="rules.tipoSolicitud"
                                v-model="data.tipoSolicitud"/>
                        </v-flex>
                        <v-flex xs6>
                            <VAutocomplete
                                :items="especialidades"
                                label="Especialidad:"
                                no-data-text="No se encuentra especialidad"
                                :readonly="readonly"
                                :rules="rules.especialidad"
                                v-model="data.especialidad"/>
                        </v-flex>
                        <v-flex xs12>
                            <vTextField
                                label="Motivo Teleorientacion"
                                :readonly="readonly"
                                :rules="rules.motivo"
                                v-model="data.motivo" />
                        </v-flex>
                        <v-flex xs12>
                            <vTextarea
                                label="Resumen de historia clinica"
                                :readonly="readonly"
                                :rules="rules.resumen"
                                v-model="data.resumen"/>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>

<!--                        <v-flex xs12 >-->
<!--                            <v-autocomplete label="Cup*" :items="data.cups" item-text="Nombre" item-value="id" v-model="cup_id" required></v-autocomplete>-->
<!--                        </v-flex>-->

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
                            <v-checkbox
                                v-model="girs" color="primary"
                                label="Junta GIRS"
                            ></v-checkbox>
                        </v-flex>

                        <v-flex xs12>
                            <input
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
                                    @click="saveTeleconcepto"
                                    :disabled="readonly"
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
        name:'PacienteTeleConceptoForm',
        props:{
            data: {
                type: Object,
                default: () => {
                    return {
                        cups: [],
                        adjuntos: [],
                        cie10s: [],
                        especialidad: '',
                        motivo: null,
                        resumen: null,
                        tipoSolicitud: null,
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
                // cup_id:null,
                cie10s: [],
                girs:false,
                especialidades: [
                    'Urologia',
                    'Medico SST',
                    'Ginecología',
                    'Medicina Familiar',
                    'Oftalmología',
                    // 'Ortopedia',
                    // 'Otorrinolaringología',
                    'Neuropsicología',
                    'Psiquiatría',
                    'Dermatologia'
                ],
                tipoSolicitud: [
                    'Normal',
                    'Prioritario'
                ],
                rules: {
                    adjunto: true,
                    especialidad: [v => !!v || 'Es requerido.'],
                    habeasData: [v => !!v || 'Es requerido.'],
                    motivo: [v => !!v || 'Se debe ingresar el motivo del teleorientacion.'],
                    resumen: [v => !!v || 'Se debe ingresar el resumen de la historia clínica.'],
                    ruleCie10: [(v) => v.length > 0 || 'Se debe escoger al menos un diagnóstico cie10.'],
                    tipoSolicitud: [v => !!v || 'Se debe escoger especialidad.'],
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
            this.fetchCie10s();
            this.fetchCups();
        },
        methods:{
            fetchCups() {
                axios.get('/api/cups/all')
                    .then(res => this.data.cups = res.data.map((cup) => { return { id: cup.id, Nombre: `${cup.Codigo} - ${cup.Nombre}` }}));
            },
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
            saveTeleconcepto(){
                if(this.validateForm()){
                    this.data.adjuntos = this.$refs.adjuntos.files;
                    const formData = new FormData();

                    formData.append('tipoSolicitud',this.data.tipoSolicitud)
                    formData.append('motivo',this.data.motivo)
                    formData.append('resumen',this.data.resumen)
                    formData.append('especialidad',this.data.especialidad)
                    // formData.append('cup_id',this.cup_id)
                    formData.append('girs',this.girs)


                    for (let i = 0; i < this.data.cie10s.length; i++) {
                        formData.append('cie10s[]',this.data.cie10s[i])
                    }

                    for (let i = 0; i < this.data.adjuntos.length; i++) {
                        formData.append(`adjuntos[]`, this.data.adjuntos[i]);
                    }

                    this.$emit('saveTeleconcepto',formData);
                }
            },
            validateForm() {
                return this.$refs.form.validate()? true : false;
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
