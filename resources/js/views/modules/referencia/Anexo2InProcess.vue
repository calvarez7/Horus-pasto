<template>
    <ReferenciaTable :loading="loading" :referencias="referencias" inProcessPage/>
</template>

<script>
    import ReferenciaTable from '../../../components/referencia/ReferenciaTable.vue'

    export default {
        name:'Anexo2InProcess',
        components:{
            ReferenciaTable
        },
        data: () => {
            return {
                referencias: [],
                loading: false
            }
        },
        created(){
            this.showMenu();
            this.fetchAnexos();
        },
        methods:{
            fetchAnexos(){
                this.loading = true;
                axios.get('/api/referencia/inProcess/2')
                    .then(res => {
                        this.referencias  = res.data.referencias
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