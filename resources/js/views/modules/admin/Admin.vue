<template>
    <v-layout justify-center >
        <v-flex shrink xs12>
            <router-view></router-view>
        </v-flex>
    </v-layout>
</template>

<script>

    import { mapMutations } from 'vuex'
    export default {
        name: 'Admin',
        components: {
        },
        mounted(){
            this.setMenu();
        },
        methods:{
            setMenu(){

                this.$store.commit('setMenu', {
                    title: '',
                    items: [
                        { name: 'Configuraciones', path: '/admin/configuraciones', can: 'configuraciones.enter' },
                        { name: 'Tutelas', path: '/admin/tutelas', can: 'admintutelas.enter' },
                        { name: 'Helpdesk', path: '/admin/helpdesks', can: 'adminhelp.enter' },
                        { name: 'Bodegas', path: '/admin/bodegas', can: 'admin.bodegas.enter' },
                        { name: 'Transacciones', path: '/admin/transacciones', can: 'admin.bodega-trasaccion.enter' },
                        { name: 'Códigos Sumi', path: '/admin/codigos-sumi', can: 'admin.codigos-sumi.enter' },
                        { name: 'Proveedores', path: '/admin/proveedores', can: 'admin.proveedores.enter' },
                        { name: 'Grupos y subgrupos', path: '/admin/grupos', can: 'admin.grupos.enter' },
                        { name: 'Tipos Agenda', path: '/admin/tipos-agenda', can: 'admin.tipos-agenda.enter' },
                        { name: 'Tipos Servicio', path: '/admin/tipos-servicio', can: 'admin.tipo-servicio.enter' },
                        { name: 'Cups', path: '/admin/cups', can: 'admin.cups.enter' },
                        { name: 'Recomendaciones Cups', path: '/admin/recomendaciones-cup', can: 'admin.recomendacionescups.enter' },
                        { name: 'Sedes', path: '/admin/sedes', can: 'admin.sedes.enter' },
                        { name: 'Colegios', path: '/admin/colegios', can: 'admin.colegios.enter' },
                        { name: 'Roles y Permisos', path: '/admin/roles', can: 'admin.roles.enter' },
                        { name: 'Productos', path: '/admin/detallearticulos', can: 'admin.detallearticulos.enter' },
                        { name: 'Esquemas', path: '/admin/esquemas', can: 'admin.esquemas.enter' },
                        { name: 'Paquetes Servicios', path: '/admin/paquetes/servicios', can: 'admin.paquetes.servicios' },
                        { name: 'Usuarios', path: '/admin/users', can: 'admin.users.enter' },
                        { name: 'Entidades', path: '/admin/entidades', can: 'admin.entidades.enter' },

                    ],
                    enabled: true
                })
            }
        }
    }
</script>

<style scoped>

</style>
