<template>
    <v-layout justify-center >
        <v-flex shrink xs12>
            <RouterView />
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name:'BodegaLayout',
        mounted(){
            this.setMenu();
        },
        methods:{
            setMenu(){
                this.$store.commit('setMenu', {
                    title: 'Módulo Bodegas',
                    items: [
                        { name: 'Reposición Inventario', path: '/bodegas/reposicion-inventario', can: 'bodega.reposicion.enter' },
                        { name: 'Solicitudes', path: '/bodegas/solicitud', can: 'bodegas.solicitud.enter' },
                        { name: 'Auditoria de solicitud', path: '/bodegas/auditoria', can: 'bodegas.auditoria.enter' },
                        { name: 'Movimientos', path: '/bodegas/movimientos', can: 'bodegas.movimientos.enter' },
                        { name: 'Existencia', path: '/bodegas/inventario', can: 'bodegas.enter' },
                        { name: 'Kardex', path: '/bodegas/kardex', can: 'any' },
                        { name: 'Dispensado', path: '/bodegas/historico', can: 'bodegas.historico.enter' },
                        { name: 'Conteo Inventario', path: '/bodegas/conteo_inventario', can: 'bodegas.conteo' },
                    ],
                    enabled: true
                });
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
