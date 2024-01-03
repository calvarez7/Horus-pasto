<template>
    <v-container grid-list-md fluid class="pa-0">
        <v-layout>
            <v-flex xs12>
                <v-card>
                    <v-card-text class="headline success">
                        <h4 style="color:white">Indicadores Fias</h4>
                    </v-card-text>
                    <v-card-title primary-title>
                        <v-layout row wrap>
                            <v-flex xs12 sm12 md12>
                                <iframe title="F - Módulo FIAS" width="1390" height="886" src="https://app.powerbi.com/view?r=eyJrIjoiMDAyN2ViYjktNmIyOS00ZGExLTk4YzgtZmU5YjMwOTE2NmNlIiwidCI6IjQ4NzRlMWJhLTU2ZGItNDc5Mi1iMDUyLTRlMmUyOWJlMjk5MyJ9&pageName=ReportSectioncfd370c67d8d87cc6aaf" frameborder="0" allowFullScreen="true"></iframe>
                            </v-flex>
                        </v-layout>
                    </v-card-title>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
    import moment from 'moment';
    import {
        mapGetters
    } from "vuex";
    moment.locale('es');
    export default {
        data() {
            return {

            }
        },
        computed: {
            ...mapGetters(['can'])
        },
        methods: {

        }
    }

</script>
