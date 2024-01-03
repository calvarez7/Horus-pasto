<template>
    <v-layout justify-center >
        <v-flex shrink xs12>
            <RouterView />
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: 'AuditoriaLayout',
        components: {
        },
        mounted(){
            this.setMenu();
        },
        methods:{
            setMenu(){
                this.$store.commit('setMenu', {
                    title: 'Módulo Historicos',
                    items: [
                        { name: 'Historico Medicamentos', path: '/auditoria/home', can: 'auditoria.medicamentos.enter'},
                        { name: 'Historico Autorizaciones', path: '/auditoria/global', can: 'auditoria.medicamentos.enter'},
                        { name: 'Historico Servicios', path: '/auditoria/service', can: 'auditoria.servicios.enter'},
                        { name: 'Historico', path: '/auditoria/historico', can: 'auditoria.historico.enter' },
                        { name: 'Historico Laboratorio', path: '/auditoria/laboratorio', can: 'auditoria.laboratorio.enter' },
                        { name: 'Consentimiento Informados', path: '/auditoria/consentimientos', can: 'auditoria.historico.enter' },
                        { name: 'Historico Imagenología', path: '/auditoria/historicoImagennologia', can: 'auditoria.historicoImagenologia.enter' },
                        { name: 'Historico Incapacidades', path: '/auditoria/incapacidad', can: 'auditoria.incapacidades.enter' },
                        { name: 'Historico Entidades', path: '/auditoria/entidades', can: 'auditoria.entidades.enter' },
                    ],
                    enabled: true
                })
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
