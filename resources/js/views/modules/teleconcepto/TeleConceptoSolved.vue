<template>
    <div>
        <v-layout row wrap justify-center>
            <v-flex xs12>
                <v-form @submit.prevent="getSolved()">
                    <v-layout row wrap>
                        <v-flex xs8>
                            <v-text-field v-model="cedula_paciente" label="Número de Documento">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs2>
                            <v-btn @click="getSolved()" @keyup.enter="getSolved()"
                                   v-if="!datitos" fab outline small color="success">
                                <v-icon>search</v-icon>
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-form>
                <TeleConceptoTable :teles="teles"/>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import TeleConceptoTable from '../../../components/teleconcepto/TeleConceptoTable.vue'

    export default {
        name: 'TeleConceptoSolved',
        components:{
            TeleConceptoTable
        },
        data: () => {
            return {
                teles: [],
                datitos: false,
                cedula_paciente: null
            }
        },
        // mounted(){
        //     this.getSolved();
        // },
        methods:{
            getSolved(){
                axios.get('/api/teleconcepto/solved/' + this.cedula_paciente)
                    .then(res => {
                        console.log('res.data :', res.data);
                        this.datitos = true
                        this.teles = res.data
                    })
                    .catch(err => console.log(err.response.data))
            }
        }
    }
</script>

<style lang="scss" scoped>
</style>
