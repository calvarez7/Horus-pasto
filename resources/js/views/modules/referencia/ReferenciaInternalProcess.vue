<template>
    <div>
        <template>
            <div class="text-center">
                <v-dialog v-model="preload" persistent width="300">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Tranquilo procesamos tu solicitud !
                            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </div>
        </template>
        <v-layout row wrap>
            <v-spacer></v-spacer>
            <v-flex xs6>
                <v-text-field label="Buscar..."
                              v-model="documento"
                ></v-text-field>
            </v-flex>
            <v-flex xs6>
                <v-btn color="info" round @click="fetchAnexos(1)">Buscar</v-btn>
            </v-flex>
            <v-flex xs12>
                <ReferenciaTable :referencias="referencias" :showFilter="false" :loading="loading" internalProcess @updateReferencia="fetchAnexos()"/>
            </v-flex>
            <div class="text-xs-center">
                <v-pagination
                    v-model="paginate.page"
                    :length="paginate.last_page"
                    :total-visible="7"
                    @input="fetchAnexos"
                ></v-pagination>
            </div>
        </v-layout>
    </div>
</template>

<script>
    import ReferenciaTable from '../../../components/referencia/ReferenciaTable.vue'
    export default {
        name: 'ReferenciaInternalProcess',
        components:{
            ReferenciaTable
        },
        data: () => {
            return {
                referencias: [],
                loading: false,
                paginate:{
                    last_page:1,
                    page:1
                },
                preload: false,
                documento: "",
            }
        },
        mounted(){
            this.fetchAnexos();
        },
        methods:{
            fetchAnexos(page = 1){
                this.preload = true;
                this.loading = true;
                const data = {
                    documento: this.documento
                }
                axios.post('/api/referencia/internal_process?page='+page,data)
                    .then(res => {
                        this.referencias  = res.data.data;
                        this.paginate.page = res.data.current_page;
                        this.paginate.last_page = res.data.last_page;

                        this.loading = false;
                        this.preload =false;
                    })
                    .catch(err => {console.log(err.response);this.preload =false;})
            },
        }
    }
</script>

<style lang="scss" scoped>
</style>
