<template>
    <v-layout justify-center >
        <v-flex shrink xs12>
            <RouterView />
        </v-flex>
    </v-layout>
</template>

<script>

    export default {
        name: 'RipsLayout',
        mounted(){
            this.setMenu();
        },
        methods:{
            setMenu(){
                this.$store.commit('setMenu', {
                    title: 'Módulo Validador',
                    items: [
                        { name: 'Validador Rips', path: '/rips', can: 'rips.entrar' },
                        { name: 'Receptor Rips', path: '/rips/receptor', can: 'rips.entrar' },
                        { name: 'Radicados', path:'/rips/radicados', can:'rips.entrar' },
                        { name: 'Radicados (JSON)', path:'/rips/json', can:'rips.entrar' },
                        { name: 'conversores', path:'/rips/conversor', can:'rips.entrar' },
                        { name: 'Resolucion 1552', path:'/rips/resolucion', can:'rips.entrar' },
                        { name: 'Pendientes', path: '/rips/pendientes', can: 'rips.entrar' },
                        { name: 'Reportes', path: '/rips/reportes', can: 'rips.reporte.entrar' },
                        { name: 'Rips internos', path: '/rips/descargas', can: 'rips.reporte.entrar' },
                        { name: 'Validador 202', path: '/202', can: '202.entrar' },
                        { name: 'Registros 202', path: '/202/registros', can: '202.registros'},
                        { name: 'Reportes 202', path: '/202/reportes', can: '202.reportes'},
                        { name: 'REPS', path: '/rips/reps', can: 'rips.reps'},
                    ],
                    enabled: true
                })
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
