<template>
    <v-form ref="form" @submit.prevent="guardar()">
        <v-text-field v-model="form.titulo" label="Titulo" required></v-text-field>
        <v-text-field type="date" v-model="form.tiempo_inicio" label="Fecha inicial" required></v-text-field>
        <v-text-field type="date" v-model="form.tiempo_fin" label="Fecha de entrega" required></v-text-field>
        <v-textarea v-model="form.detalle" outline label="Detalle de la actividad" required></v-textarea>
        <v-autocomplete :items="usuarios" :item-text="item => item.name +' '+ item.apellido" item-value="id"
            label="Responsables" v-model="form.responsables" multiple> </v-autocomplete>
        <v-btn color="success" type="submit" :loading="cargando">
            Guardar
        </v-btn>
    </v-form>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'FormActividad',

        data() {
            return {
                usuarios: null,
                cargando: false,
                form: {
                    titulo: null,
                    tiempo_inicio: null,
                    tiempo_fin: null,
                    detalle: null,
                    resposables: []
                }
            };
        },

        mounted() {
            this.obtenerUsuarios()
        },

        methods: {
            /**
             * Lista los usuarios
             */
            obtenerUsuarios: async function () {
                try {
                    const response = await axios.get('/api/user/all')
                    this.usuarios = response.data
                } catch (error) {
                    console.log('Error al cargar los usuarios.')
                }
            },

            /**
             * Almacena una actividad
             */
            guardar: async function () {
                try {
                    this.cargando = true;
                    const response = await axios.post('/api/desarrollo/actividad/guardar', this.form)
                    this.$emit('ejecutado')
                    this.limpiar()
                    swal({
                            title: '¡ Exito !',
                            text: response.data.message,
                            type: "success",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    this.cargando = false
                    this.obtenerUsuarios()
                } catch (error) {
                    this.cargando = false;
                    console.log(error.response);
                }
            },

            /**
             * Limpiar Formulario
             */
            limpiar: function(){
                this.form.titulo = null
                this.form.tiempo_inicio = null
                this.form.tiempo_fin = null
                this.form.detalle = null
                this.form.resposables = []
            }
        },
    };

</script>
