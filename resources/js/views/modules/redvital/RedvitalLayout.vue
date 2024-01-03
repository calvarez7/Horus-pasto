<template>
    <v-app id="inspire">
        <v-toolbar :clipped-left="$vuetify.breakpoint.lgAndUp" color="#FFFFFF">
            <v-toolbar-title>
                <!-- <router-link v-if="paciente.entidad_id == 1" to="/gestion" tag="img" width="160px" src="/images/Logo_sumimedical.png"></router-link> -->
                <router-link to="/gestion" tag="img" width="50px" height="30px" src="/images/logosu.png"></router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon color="redvitalAzul" dark to="/gestion">
                <v-icon>home</v-icon>
            </v-btn>
            <span>{{ paciente.Primer_Nom }} {{ paciente.Primer_Ape }} {{ paciente.Segundo_Ape }} </span>
        </v-toolbar>

        <RouterView :paciente="paciente" @dialogoChat="dialogoChat" />

        <v-footer height="auto" color="redvitalAzul">
            <v-layout justify-center row wrap>
                <v-flex color="redvitalAzul" py-2 text-xs-center white--text xs12>
                    &copy;{{ new Date().getFullYear() }} — <strong v-if="paciente.Ut == 'REDVITAL UT'">Redvital</strong><strong v-else>Sumimedical</strong>
                </v-flex>
            </v-layout>
        </v-footer>


        <!-- <v-btn fab bottom right color="redvitalVerde" dark fixed @click="dialogChat = !dialogChat">
            <v-icon>mdi-headset</v-icon>
        </v-btn>
        <v-dialog v-model="dialogChat" width="800px">
            <v-card>
                <v-card-title class="grey lighten-4 py-4 title">
                    Chat
                </v-card-title>
                <v-container grid-list-sm class="pa-4">
                    <v-layout row wrap>
                        <v-flex xs12 align-center justify-space-between>
                            <v-layout align-center>
                                <v-avatar size="40px" class="mr-3">
                                    <img src="//ssl.gstatic.com/s2/oz/images/sge/grey_silhouette.png" alt="">
                                </v-avatar>
                                <v-text-field placeholder="Name"></v-text-field>
                            </v-layout>
                        </v-flex>
                        <v-flex xs6>
                            <v-text-field prepend-icon="business" placeholder="Company"></v-text-field>
                        </v-flex>
                        <v-flex xs6>
                            <v-text-field placeholder="Job title"></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field prepend-icon="mail" placeholder="Email"></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field type="tel" prepend-icon="phone" placeholder="(000) 000 - 0000" mask="phone">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field prepend-icon="notes" placeholder="Notes"></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red" dark @click="dialogChat = false">Cancel</v-btn>
                    <v-btn color="redvitalVerde" dark @click="dialogChat = false">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog> -->

    </v-app>
</template>

<script>
    import Pqrsf from "./Pqrsf";
    export default {
        name: "RedvitalLayout",
        components: {
            Pqrsf
        },
        props: {
            paciente: Object,
            source: String
        },
        data() {
            return {
                dialogChat: false
            }
        },
        methods: {
            dialogoChat(dialogoChat) {
                this.dialogChat = dialogoChat
            },
        },

    };

</script>
