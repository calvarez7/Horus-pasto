<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" label="Deterioro cognoscitivo"
                        v-model="sindromeGeriatrico.deterioro_cognoscitivo" value="1"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" label="Inmovilidad" v-model="sindromeGeriatrico.inmovilidad" value="1">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" label="Inestabilidad y caídas"
                        v-model="sindromeGeriatrico.inestabilidad_caidas" value="1"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" label="Fragilidad" v-model="sindromeGeriatrico.fragilidad" value="1">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" label="Incontinencia de esfínteres"
                        v-model="sindromeGeriatrico.incontinencia_esfinteres" value="1"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" label="Depresión" v-model="sindromeGeriatrico.depresion" value="1">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" label="Iatrogenia medicmentosa"
                        v-model="sindromeGeriatrico.iatrogenia_medicmentosa" value="1"></v-checkbox>
                </v-flex>
            </v-layout>
        </v-container>
    </v-form>
</template>
<script>
    export default {
        name: "",
        data() {
            return {
                sindromeGeriatrico: {}
            }
        },
    }

</script>
