<template>
    <v-layout>
        <v-flex>
            <v-card max-height="100%">
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Bienvenidos a Horus-Health</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout wrap align-center justify-center>
                        <v-container fluid grid-list-md>
                            <v-layout row wrap>
                                <v-flex v-for="card in cards" :key="card.title" v-bind="{ [`xs${card.flex}`]: true }">
                                    <v-flex v-if="can(card.can)" :key="card.title">
                                        <v-card :to="card.path">
                                            <v-img :src="card.src" class="border-top" size="100" height="150px">
                                            </v-img>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-flex justify-center>
                                                    <span class="headline home--text">{{ card.title }}</span>
                                                </v-flex>
                                            </v-card-actions>
                                        </v-card>
                                    </v-flex>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>
<script>
    import {
        mapGetters
    } from 'vuex';

    export default {
        name: 'DashBoard',
        data() {
            return {
                e1: 0,
                cards: [{
                        title: 'Helpdesk',
                        src: '/images/helpdesk.jpg',
                        can: 'any',
                        path: '/helpdesk',
                        flex: 2
                    },
                    {
                        title: 'Gestión de Sucesos',
                        src: '/storage/images/pqrs-f.jpg',
                        can: 'any',
                        path: '/evento',
                        flex: 2
                    },
                    {
                        title: 'Agendas',
                        src: '/storage/images/agendamiento.jpg',
                        can: 'agendas.enter',
                        path: '/agenda',
                        flex: 2
                    },
                    {
                        title: 'Citas',
                        src: '/storage/images/citas.jpg',
                        can: 'citas.enter',
                        path: '/citas',
                        flex: 2
                    },
                    {
                        title: 'Tarifas',
                        src: '/storage/images/tarifas.jpg',
                        can: 'contratacion.enter',
                        path: '/manual-tarifario',
                        flex: 2
                    },
                    {
                        title: 'Medicamentos',
                        src: '/storage/images/medicamentos.jpg',
                        can: 'medicamentos.enter',
                        path: '/dispensacion',
                        flex: 2
                    },
                    {
                        title: 'Bodegas',
                        src: '/storage/images/bodegas.jpg',
                        can: 'bodegas.enter',
                        path: '/bodegas',
                        flex: 2
                    },
                    {
                        title: 'PQRS-F',
                        src: '/storage/images/pqrs-f.jpg',
                        can: 'pqrsf.enter',
                        path: '/pqrsf',
                        flex: 2
                    },
                    {
                        title: 'Admin',
                        src: '/storage/images/administracion.jpg',
                        can: 'admin.enter',
                        path: '/admin',
                        flex: 2
                    },
                    {
                        title: 'Sumintranet',
                        src: '/storage/images/pqrs-f.jpg',
                        can: 'enter.sumintranet',
                        path: '/sumintranet',
                        flex: 2
                    },
                    {
                        title: 'Resultados',
                        src: '/storage/images/citas.jpg',
                        can: 'covid.enter',
                        path: '/resultados',
                        flex: 2
                    },
                    {
                        title: 'Validación Derechos',
                        src: '/storage/images/citas.jpg',
                        can: 'aseguramiento.enter',
                        path: '/aseguramiento/validacion',
                        flex: 2
                    },
                    {
                        title: 'Validador RIPS',
                        src: '/storage/images/tarifas.jpg',
                        can: 'rips.entrar',
                        path: '/rips',
                        flex: 2
                    },
                    {
                        title: 'Referencia',
                        src: '/storage/images/pqrs-f.jpg',
                        can: 'referencia.enter',
                        path: '/referencia',
                        flex: 2
                    },
                ]
            }
        },
        computed: {
            ...mapGetters(['can'])
        },
        mounted() {
            this.setMenu();
        },
        methods: {
            setMenu() {
                this.$store.commit('setMenu', {
                    title: 'Inicio',
                    items: 0,
                    enabled: false
                })
            }
        }
    }

</script>
<style scope>
    .border-card {
        border-radius: 3%;
    }

    .border-top {
        border-radius: 3% 3% 0 0;
    }

</style>
