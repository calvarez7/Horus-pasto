<template>
    <v-layout justify-center >
        <v-flex shrink xs12>
            <RouterView />
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: 'DesarrolloLayout',
        mounted(){
            this.setMenu();
        },
        methods:{
            setMenu(){
                this.$store.commit('setMenu', {
                    title: 'Módulo Desarrollo',
                    items: [
                        { name: 'Asignación Citas', path: '/citas/asignacion', can: 'asignacion-citas' },
                        { name: 'Cobro Ordenes', path: '/citas/cobros/activos', can: 'cobro-activo' },
                        { name: 'Parametrización', path: '/citas/parametrizacion', can: 'parametrizacion-citas' },
                        //{ name: 'Historico Cobro', path: '/citas/cobros/historico', can: 'cobro-historico' },
                    ],
                    enabled: true
                })
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
