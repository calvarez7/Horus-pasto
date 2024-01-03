<template>

    <v-container grid-list-xl text-xs-center>
        <v-layout row wrap>

            <Columna
                v-for="estado in estados"
                :key="estado.id"
                :estado="estado"
                :estados="estados"
                :actividades="actividades.filter(item => parseInt(item.estado_id) === estado.id)"
                @actualizar="obtenerActividades()"
            />

        </v-layout>

            <v-dialog v-model="dialog" width="600px">
                <template v-slot:activator="{ on }">
                    <v-btn fixed dark fab bottom right color="pink" v-on="on">
                        <v-icon>add</v-icon>
                    </v-btn>
                </template>

                <v-card class="pa-4">

                    <v-card-title>
                        <h3>Crear actividad</h3>
                    </v-card-title>

                    <FormActividad @ejecutado="dialog = false"/>
                </v-card>

            </v-dialog>

    </v-container>
</template>

<script>
    import Columna from '../../../components/desarrollo/Columna.vue'
    import FormActividad from '../../../components/desarrollo/FormActividad.vue'

    export default {
        name: 'DesarrolloView',
        components: {
            Columna,
            FormActividad
        },

        data() {
            return {
                estados: null,
                actividades: null,
                dialog: false,
            };
        },

        mounted() {
            this.obtenerActividades()
            this.obtenerEstados()
        },

        methods: {

            /**
             * Obtiene los estado para el tema actividades
             * Estados ['5', '18', '', '', '34']
             * Estados ['asignado', 'pendiente', 'proceso', 'pruebas', 'terminado']
             */
            obtenerEstados: async function () {
                try {
                    const response = await axios.get('/api/estado/all')
                    this.estados = response.data.filter(item => [5,18,34].includes(item.id))
                    console.log(response.data)
                } catch (error) {
                    console.log(error)
                }
            },

            /**
             * Obtiene todas las actividades
             */
            obtenerActividades: async function () {
                try {
                    const response = await axios.get('/api/desarrollo/actividad/listar')
                    this.actividades = response.data
                    console.log(response.data)
                } catch (error) {
                    console.log(error)
                }
            },

        },
    };

</script>
