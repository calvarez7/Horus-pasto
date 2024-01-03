<template>
    <v-container grid-list-lg>
        <v-layout row wrap>
            <v-flex d-flex lg6 sm6 xs12>
                <v-card color="red" to="/sarlaft/revision/pendientes">
                    <v-card-text class="pa-0">
                        <v-container class="pa-0">
                            <div class="layout row ma-0">
                                <div class="sm4 xs6 flex">
                                    <div class="layout column ma-0 justify-center align-center">
                                        <v-icon size="76px" color="white" style="opacity: 0.8;">alarm
                                        </v-icon>
                                    </div>
                                </div>
                                <div class="layout column ma-0 justify-center" style="color: white; fontSize: 30px;">
                                    <span class="">PENDIENTES</span>
                                </div>
                            </div>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex d-flex lg6 sm6 xs12>
                <v-card color="green" to="/sarlaft/revision/revisados">
                    <v-card-text class="pa-0">
                        <v-container class="pa-0">
                            <div class="layout row ma-0">
                                <div class="sm4 xs6 flex">
                                    <div class="layout column ma-0 justify-center align-center">
                                        <v-icon size="76px" color="white" style="opacity: 0.8;">https</v-icon>
                                    </div>
                                </div>
                                <div class="layout column ma-0 justify-center" style="color: white; fontSize: 30px;">
                                    <span class="">REVISADOS</span>
                                </div>
                            </div>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>

        <v-card class="mt-2">
            <RouterView />
        </v-card>

    </v-container>
</template>
<script>
    export default {
        name: "RevisionSarlaft",
    }
</script>
