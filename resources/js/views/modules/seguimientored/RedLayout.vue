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
                    title: 'Módulo de Seguimiento de Red',
                    items: [
                        { name: 'oportunidad', path: '/red', can: 'red.enter' },
                        { name: 'facturacion', path: '/red/facturacion', can: 'red.enter' },
                        { name: 'ejecucion', path: '/red/ejecucion', can: 'red.enter' },
                        { name: 'seguimineto', path: '/red/seguimiento', can: 'red.enter' },
                    ],
                    enabled: true
                })
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
