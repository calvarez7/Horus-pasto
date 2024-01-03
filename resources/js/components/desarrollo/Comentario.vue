<template>
    <v-layout row wrap>
        <v-flex lg12 mb3>
            <v-list two-line>
                <v-list-tile v-for="comentario in comentarios" :key="comentario.id" avatar>
                    <v-list-tile-avatar>
                        <img :src="comentario.avatar">
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                        <v-list-tile-title v-html="comentario.comentario"></v-list-tile-title>
                        <v-list-tile-title v-html="comentario.user.name"></v-list-tile-title>
                        <v-list-tile-sub-title v-html="comentario.created_at"></v-list-tile-sub-title>
                    </v-list-tile-content>

                    <v-divider></v-divider>

                </v-list-tile>
            </v-list>
        </v-flex>
        <v-flex lg12 mb3>
            <v-form @submit.prevent="guardarComentario()">
                <v-textarea
                    v-model="form.comentario"
                    label="Agregar Comentario"
                ></v-textarea>
                <v-btn type="submit" color="success">
                    Comentar
                </v-btn>
            </v-form>

        </v-flex>
    </v-layout>

</template>

<script>
    export default {
        name: 'Comentario',
        props: {
            actividad_id: Number
        },
        data() {
            return {
                comentarios: [],
                form:{
                    comentario: null
                }
            };
        },

        watch: {

            actividad_id: function () {
                this.consultar()
            }

        },

        methods: {
            /**
             * Consulta los comentario
             */
            consultar: async function () {
                try {
                    const response = await axios.get('/api/desarrollo/comentario/consultar/' + this.actividad_id)
                    this.comentarios = response.data
                } catch (error) {
                    console.log(error.response)
                    console.log(error)
                }
            },

            /**
             * Almacena un comentario
             */
            guardarComentario: async function () {
                try {
                    await axios.post('/api/desarrollo/comentario/guardar/' + this.actividad_id, this.form)
                    this.form.comentario = null
                    this.consultar()
                } catch (error) {
                    console.log(error.response)
                    console.log(error)
                }
            }

            /**
             * Limpiar Form
             */
        },
    };

</script>
