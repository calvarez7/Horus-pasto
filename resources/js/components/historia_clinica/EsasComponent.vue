<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="justify-center title layout font-weight-light">SISTEMA DE EVALUACIÓN DE SÍNTOMAS DE
                        EDMONTON-ESAS:
                        <!-- <span><b>{{ecog.valor_scala_ecog}}</b></span></span> -->
                    </span>
                </v-card-title>
                <v-layout row wrap>
                     <v-flex xs12 sm6 md6 v-for="item in campos" :key="item.id">
                        <v-label>{{item.nombre}}</v-label>
                            <v-radio-group v-model="valoresEdmonton[item.id]" row color="primary">
                                <v-radio color="primary" v-for="n in 10" :key="n" :label="`${n}`" :value="n">
                            </v-radio>
                        </v-radio-group>
                    </v-flex>
                    <v-btn color="success" round @click="saveEsas()">Guardar y seguir</v-btn>
                </v-layout>
            </v-container>
        </v-form>
    </div>
</template>

<script>
    export default {
        name: 'HorusHealthEsasComponent',
        props: {
            datosCita: Object
        },
        data() {
            return {
                campos: [
                    {
                        id: 'sin_dolor',
                        nombre: 'Sin dolor',
                    },
                    {
                        id: 'sin_cansancio',
                        nombre: 'Sin cansancio',
                    },
                    {
                        id: 'sin_nauseas',
                        nombre: 'Sin nauseas'
                    },
                    {
                        id: 'sin_tristeza',
                        nombre: 'Sin tristeza'
                    },
                    {
                        id: 'sin_ansiedad',
                        nombre: 'Sin ansiedad'
                    },
                    {
                        id: 'sin_somnolencia',
                        nombre: 'Sin somnolencia'
                    },
                    {
                        id: 'sin_disnea',
                        nombre: 'Sin disnea'
                    },
                    {
                        id: 'buen_apetito',
                        nombre: 'Buen apetito'
                    },
                    {
                        id: 'maximo_bienestar',
                        nombre: 'Maximo bienestar'
                    },
                    {
                        id: 'sin_dificulta_para_dormir',
                        nombre: 'Sin dificulta para dormir'
                    },
                ],
                valoresEdmonton:{}
            };
        },

        created() {
            this.fetchEsas()
        },

        methods: {
            saveEsas() {
                this.valoresEdmonton.citapaciente_id = this.datosCita.cita_paciente_id
                axios.post('/api/historia/saveEsas', this.valoresEdmonton).then(res => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.$emit('respuestaComponente')
                })
            },

            fetchEsas() {
                axios.get(`/api/historia/fetchEsas/${this.datosCita.cita_paciente_id}`).then(res => {
                    this.valoresEdmonton = res.data
                })
            }
        },
    };

</script>

<style lang="scss" scoped>

</style>
