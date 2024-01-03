<template>
    <ReferenciaTable :loading="loading" :referencias="referencias" inPendingPage/>
</template>

<script>
    import ReferenciaTable from '../../../components/referencia/ReferenciaTable.vue'

    export default {
        name:'Anexo2Pending',
        components:{
            ReferenciaTable
        },
        data: () => {
            return {
                referencias: [],
                loading: false,
            }
        },
        created(){
            this.showMenu();
            this.fetchAnexos();
        },
        methods:{
            fetchAnexos(){
                this.loading = true;
                axios.get('/api/referencia/pending/2')
                    .then(res => {
                        this.referencias  = res.data.referencias || []
                        this.$emit('referencia', res.data)
                        this.loading = false;
                    })
                    .catch(err => console.log(err.response))
            },
            showMenu(){
                this.$emit('showMenu', true)
            } 
            
        }
    }
</script>

<style lang="scss" scoped>

</style>