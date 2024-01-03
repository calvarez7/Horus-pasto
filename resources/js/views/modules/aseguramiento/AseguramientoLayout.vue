<template>
    <v-layout justify-center>
        <v-flex shrink xs12>
            <RouterView />
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "AseguramientoLayout",
        mounted() {
            this.setMenu();
        },
        methods: {
            setMenu() {
                this.$store.commit("setMenu", {
                    title: "Módulo Aseguramiento",
                    items: [
                        { name: "Validación de Derechos", path: "/aseguramiento/validacion", can: 'any' },
                        { name: 'Pacientes', path: '/aseguramiento/pacientes', can: 'admin.paciente.enter' },
                        { name: 'Portabilidad Entrada', path: '/aseguramiento/afiliaciones/entrada', can: 'afiliaciones.enter' },
                        { name: 'Portabilidad Salida', path: '/aseguramiento/afiliaciones/salida', can: 'afiliaciones.enter' },
                        { name: 'Remision Programas', path: '/aseguramiento/remisionProgramas', can: 'remisionProgramas.enter' },
                        { name: 'Parametrización', path: '/aseguramiento/parametrizacionAseguramiento', can: 'parametrizacionAseguramiento.enter' },

                    ],
                    enabled: true
                });
            }
        }
    };
</script>

<style lang="scss" scoped>
</style>
