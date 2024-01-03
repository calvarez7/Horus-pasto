<template>
    <v-container grid-list-lg>

        <v-layout row wrap>

            <v-flex d-flex lg4 sm4 xs12 v-if="can('cuentasmedicas.asignadas')">
                <v-card color="#00b297" to="/cuentasmedicas/auditoria/asignadas">
                    <v-card-text class="pa-0">
                        <v-container class="pa-0">
                            <div class="layout row ma-0">
                                <div class="sm4 xs4 flex">
                                    <div class="layout column ma-0 justify-center align-center">
                                        <v-icon size="76px" color="white" style="opacity: 0.8;">history</v-icon>
                                    </div>
                                </div>
                                <div class="layout column ma-0 justify-center" style="color: white;">
                                    <span class="caption">Asignadas</span>
                                    <div class="headline">{{ count['asignadas'] }}</div>
                                    <span class="caption">Hasta la fecha</span>
                                </div>
                            </div>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>

            <v-flex d-flex lg4 sm4 xs12 v-if="can('cuentasmedicas.auditadas')">
                <v-card color="#F1C40F" to="/cuentasmedicas/auditoria/auditadas">
                    <v-card-text class="pa-0">
                        <v-container class="pa-0">
                            <div class="layout row ma-0">
                                <div class="sm4 xs4 flex">
                                    <div class="layout column ma-0 justify-center align-center">
                                        <v-icon size="76px" color="white" style="opacity: 0.8;">mdi-file-document
                                        </v-icon>
                                    </div>
                                </div>
                                <div class="layout column ma-0 justify-center" style="color: white;">
                                    <span class="caption">Auditadas</span>
                                    <div class="headline">{{ count['auditadas'] }}</div>
                                    <span class="caption">Hasta la fecha</span>
                                </div>
                            </div>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>

            <v-flex d-flex lg4 sm4 xs12 v-if="can('cuentasmedicas.prestador')">
                <v-card color="#E64F07" to="/cuentasmedicas/auditoria/prestador">
                    <v-card-text class="pa-0">
                        <v-container class="pa-0">
                            <div class="layout row ma-0">
                                <div class="sm4 xs4 flex">
                                    <div class="layout column ma-0 justify-center align-center">
                                        <v-icon size="76px" color="white" style="opacity: 0.8;">gavel
                                        </v-icon>
                                    </div>
                                </div>
                                <div class="layout column ma-0 justify-center" style="color: white;">
                                    <span class="caption">Facturas Prestador</span>
                                    <div class="headline">{{ count['prestador'] }}</div>
                                    <span class="caption">Pendientes Hasta la fecha</span>
                                </div>
                            </div>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>

             <v-flex d-flex lg4 sm4 xs12 v-if="can('cuentasmedicas.auditoriafinal')">
                <v-card color="info" to="/cuentasmedicas/auditoria/auditoriafinal">
                    <v-card-text class="pa-0">
                        <v-container class="pa-0">
                            <div class="layout row ma-0">
                                <div class="sm4 xs4 flex">
                                    <div class="layout column ma-0 justify-center align-center">
                                        <v-icon size="76px" color="white" style="opacity: 0.8;">note_add
                                        </v-icon>
                                    </div>
                                </div>
                                <div class="layout column ma-0 justify-center" style="color: white;">
                                    <span class="caption">Auditoria Final</span>
                                    <div class="headline">{{ count['final'] }}</div>
                                    <span class="caption">Pendientes Hasta la fecha</span>
                                </div>
                            </div>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>

        </v-layout>

        <v-card class="mt-2">
            <RouterView @updateCount="counter" />
        </v-card>

    </v-container>
</template>
<script>
    import {
        mapGetters
    } from 'vuex';
    import Widget from '../../../components/referencia/Widget.vue'
    export default {
        name: "CuentasMedicasGestion",
        components: {
            Widget,
        },
        data() {
            return {
                count: ''
            }
        },
        computed: {
            ...mapGetters(['can']),
        },
        mounted() {
            this.counter();
        },
        methods: {
            counter() {
                axios.get('/api/medicalBills/count')
                    .then(res => {
                        this.count = res.data;
                    })
            },
        },
    }

</script>
