<template>
    <v-layout justify-center>
        <v-flex shirnk xs12>
            <router-view/>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "VagoutLayout",
        components: {},
        mounted() {
            this.setMenu();
        },
        methods: {
            setMenu() {
                this.$store.commit("setMenu", {
                    title: "Módulo Vagout",
                    items: [
                        {name: 'Registradora', path: '/vagout', can: 'registrar.vagout'},
                        {name: "Facturas", path: "/vagout/facturas", can: 'facturas.vagout'},
                        {name: "Reportes", path: "/vagout/Reportes", can: 'reporte.vagout'},
                        // {name: "Ciclos", path: "/vagout/menus", can: 'menu.vagout'},
                        {name: "Productos", path: "/vagout/productos", can: 'producto.vagout'},
                        {name: "Inventario", path: "/vagout/ingredientes", can: 'inventario.vagout'}
                    ],
                    enabled: true
                });
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
