<template>
    <div>
        <v-layout row wrap justify-center>
            <v-flex xs12>
                <HistoricoTable
                    :loading="loading"
                    :movimientos="movimientos"
                    :page="page"
                    :bodega="bodega"
                    :startDate="startDate"
                    :finishDate="finishDate"
                    :usuario="usuario"
                    @getDispensadoPage="getDispensado" />
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import moment from 'moment';
    moment.locale('es')
    import HistoricoTable from '../../../components/bodega/HistoricoTable.vue';

    export default {
        name: 'BodegaHistorialDispensado',
        props:{
            bodega: Number,
            startDate: String,
            finishDate: String,
            usuario: Number
        },
        components:{
            HistoricoTable
        },
        data: () => {
            return {
                loading: false,
                movimientos: [],
                page: {},
            }
        },
        watch:{
            bodega(){
                if(!this.bodega) return;

                this.getDispensado();
            },
            startDate(){
                if(!this.bodega) return;

                this.getDispensado();
            },
            finishDate(){
                if(!this.bodega) return;

                this.getDispensado();
            },
            usuario(){
                if(!this.bodega) return;

                this.getDispensado();
            },

        },
        methods:{
            getDispensado(){
                this.loading = true;

                axios.post(`/api/bodega/${this.bodega}/historico/dispensado`,{
                    startDate: this.startDate,
                    finishDate: this.finishDate,
                    usuario: this.usuario
                })
                    .then(res => {
                        console.log(res);
                        this.movimientos = res.data;
                        this.loading = false;
                    })
                    .catch(err => console.log(err.response.data))
            }
        }
    }
</script>

<style lang="scss" scoped>
</style>
