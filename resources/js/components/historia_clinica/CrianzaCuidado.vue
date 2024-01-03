<template>
<v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="crianzaCuidado.expresa_carinio_al_ninio" value="1"
                        label="Se le expresa cariño y reconocimiento al niño"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="crianzaCuidado.indagacion_acciones_correctivas" value="1"
                        label="Indagación sobre acciones correctivas"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="crianzaCuidado.estimula_desarrollo_del_ninio" value="1"
                        label="Actividades para estimular el desarrollo"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="crianzaCuidado.relacion_familia" value="1"
                        label="Buen relacionamiento con la familia, otras personas y niños"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="crianzaCuidado.educacion_autocuidado" value="1"
                        label="Educación en autocuidado"></v-checkbox>
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
            crianzaCuidado: []
        }
    },
    methods() {

    }
}
</script>
