<template>
    <v-container grid-list-lg>

        <v-layout row wrap>
            <v-flex d-flex lg3 sm6 xs12>
                <v-card color="red" to="/pqrsf/gestion-externa/pendientes">
                    <v-card-text class="pa-0">
                        <v-container class="pa-0">
                            <div class="layout row ma-0">
                                <div class="sm4 xs4 flex">
                                    <div class="layout column ma-0 justify-center align-center">
                                        <v-icon size="76px" color="white" style="opacity: 0.8;">mdi-bell</v-icon>
                                    </div>
                                </div>
                                <div class="layout column ma-0 justify-center" style="color: white;">
                                    <span class="caption">PENDIENTES</span>
                                    <div class="headline">{{ countpqr['Pendientes'] }}</div>
                                    <span class="caption">PQRSF</span>
                                </div>
                            </div>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex d-flex lg3 sm6 xs12>
                <v-card color="blue" to="/pqrsf/gestion-externa/asignados">
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
                                    <span class="caption">ASIGNADOS</span>
                                    <div class="headline">{{ countpqr['Asignadas'] }}</div>
                                    <span class="caption">PQRSF</span>
                                </div>
                            </div>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex d-flex lg3 sm6 xs12>
                <v-card color="yellow" to="/pqrsf/gestion-externa/presolucionados">
                    <v-card-text class="pa-0">
                        <v-container class="pa-0">
                            <div class="layout row ma-0">
                                <div class="sm4 xs4 flex">
                                    <div class="layout column ma-0 justify-center align-center">
                                        <v-icon size="76px" color="white" style="opacity: 0.8;">mdi-format-list-bulleted
                                        </v-icon>
                                    </div>
                                </div>
                                <div class="layout column ma-0 justify-center" style="color: white;">
                                    <span class="caption">PRE-SOLUCIONADOS</span>
                                    <div class="headline">{{ countpqr['Pre_solucionadas'] }}</div>
                                    <span class="caption">PQRSF</span>
                                </div>
                            </div>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex d-flex lg3 sm6 xs12>
                <v-card color="green" to="/pqrsf/gestion-externa/solucionados">
                    <v-card-text class="pa-0">
                        <v-container class="pa-0">
                            <div class="layout row ma-0">
                                <div class="sm4 xs4 flex">
                                    <div class="layout column ma-0 justify-center align-center">
                                        <v-icon size="76px" color="white" style="opacity: 0.8;">mdi-account</v-icon>
                                    </div>
                                </div>
                                <div class="layout column ma-0 justify-center" style="color: white;">
                                    <span class="caption">SOLUCIONADOS</span>
                                    <div class="headline">{{ countpqr['Solucionadas'] }}</div>
                                    <span class="caption">PQRSF</span>
                                </div>
                            </div>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>

        <v-card class="mt-2">
            <RouterView @updateCount="count" />
        </v-card>

    </v-container>
</template>
<script>
    import Pendientes from "./PqrsfPendiente"
    export default {
        name: "PqrsfGestion",
        components: {
            Pendientes
        },
        data() {
            return {
                countpqr: '',
                canal: ''
            }
        },
        mounted() {
            this.count();
        },
        methods: {
            count(newValue) {
                if(newValue !== undefined) this.canal = newValue
                this.data = { canal: this.canal}
                axios.post('/api/pqrsf/countPqrsfs', this.data).then(res => {
                    this.countpqr = res.data;
                });
            }
        },
    }

</script>
