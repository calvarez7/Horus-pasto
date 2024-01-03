<template>
    <v-layout justify-center >
        <v-flex shrink xs12>
            <RouterView />
        </v-flex>
    </v-layout>
</template>

<script>
 
    export default {
        name: 'ManualLayout',
        components: {
        },
        mounted(){
            this.setMenu();
        },
        methods:{
            setMenu(){
                this.$store.commit('setMenu', {
                    title: 'Módulo Contratación',
                    items: [
                        { name: 'Prestadores', path: '/manual-tarifario', can: 'contratacion.enter' },
                        // { name: 'Códigos Propios', path: '/manual-tarifario/codigo-propios', can: 'contratacion.enter' },
                        { name: 'Tarifas', path: '/manual-tarifario/contratos', can: 'contratacion.enter' },
                        { name: 'Parametrización', path: '/manual-tarifario/cup', can: 'contratacion.enter' },
                        { name: 'Códigos Sumi', path: '/manual-tarifario/codigos-sumi', can: 'admin.codigos-sumi.enter' },
                        { name: 'Cups', path: '/manual-tarifario/cups', can: 'admin.cups.enter' },
                    ],
                    enabled: true
                })
            }
        }
    }
</script>

<style lang="scss" scoped>

</style> 