<template>
    <v-layout justify-center >
        <v-flex shrink xs12>
            <RouterView />
        </v-flex>
    </v-layout>
</template>

<script>

    export default {
        name: 'FiasLayout',
        mounted(){
            this.setMenu();
        },
        methods:{
            setMenu(){
                this.$store.commit('setMenu', {
                    title: 'Módulo Cuentas de Alto Costo',
                    items: [
                        { name: 'Riesgo Cardiovascular', path: '/riesgoCardiovascular', can: 'fias.riesgoCardiovascular' },
                        { name: 'Cancer', path: '/cancer', can: 'fias.cancer' },
                        { name: 'Hemofilia', path: '/hemofilia', can: 'fias.hemofilia' },
                        { name: 'Artritis', path: '/artritis', can: 'fias.artritis' },
                        { name: 'VIH', path: '/huerfanas', can: 'fias.huerfanas' },
                        { name: 'Trasplantes', path: '/transplantes', can: 'fias.transplantes' },
                        { name: 'Descarga Fias', path: '/reporte', can: 'rips.reporte.entrar' },
                        { name: 'Cuentas de Alto Costo', path: '/cac', can: 'fias.hemofilia' },
                    ],
                    enabled: true
                })
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
