<template>
    <ReferenciaTable :loading="loading" :referencias="referencias" inPendingPage/>
</template>

<script>
    import ReferenciaTable from '../../../components/referencia/ReferenciaTable.vue'

    export default {
        name:'Anexo3Pending',
        components:{
            ReferenciaTable
        },
        data: () => {
            return {
                referencias: [],
                loading: false,
            }
        },
        mounted(){
            this.fetchAnexos();
        },
        methods:{
            fetchAnexos(){
                this.loading = true;
                axios.get('/api/referencia/pending/3')
                    .then(res => {
                        this.referencias = res.data.referencias
                        this.$emit('referencia', res.data)
                        this.loading = false;
                    })
                    .catch(err => console.log(err.response))
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>