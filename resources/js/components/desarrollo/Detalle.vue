<template>
    <v-layout row wrap>

        <v-flex lg12 mb-3>
            <h2>{{ actividad.titulo }}</h2>
        </v-flex>

        <v-flex lg12 mb-3>
            <p class="mb-1">Asignada a</p>
            <v-flex v-for="responsable in actividad.responsables" :key="responsable.id" mr-2 d-flex left>
                <v-avatar size="36px">
                    <img src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460" alt="Avatar">
                </v-avatar>
                <div class="ml-1">
                    <p class="mb-0 font-weight-bold">{{ responsable.name }} {{ responsable.apellido }}</p>
                    <p>{{ responsable.especialidad_medico ? responsable.especialidad_medico : 'Sin especialidad'}}</p>
                </div>
            </v-flex>
        </v-flex>

        <v-flex lg12 mb-3>
            <v-layout>
                <v-layout align-center fill-height>
                    <v-icon>event</v-icon>
                    <div class="ml-1">
                        <p class="mb-0">Fecha de entrega</p>
                        <p class="mb-0 font-weight-bold">{{ actividad.tiempo_fin }}</p>
                    </div>
                </v-layout>
                <v-flex>
                    <v-select
                        label="Cambiar estado a"
                        outline
                        :items="estados"
                        item-text="Nombre"
                        item-value="id"
                        v-model="actividad.estado_id"
                        @change="cambiarEstado()"
                    ></v-select>
                </v-flex>
            </v-layout>
        </v-flex>

        <v-flex lg12 mb-3>
            <p class="mb-1 font-weight-bold">Detalle</p>
            <p class="mb-1" aling="justify">{{ actividad.detalle }}</p>
        </v-flex>

        <Comentario mb-3 :actividad_id="actividad.id"/>

        <v-flex lg12>
            <v-layout align-center fill-height>
                <v-avatar size="36px">
                    <img src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460" alt="Avatar">
                </v-avatar>
                <div class="ml-1">
                    <p class="mb-0 font-weight-bold">{{ actividad.creador.name }} {{ actividad.creador.apellido }} creó esta actividad</p>
                    <p class="mb-0">{{ actividad.created_at }}</p>
                </div>
            </v-layout>
        </v-flex>

    </v-layout>
</template>

<script>
import Comentario from './Comentario.vue'

    export default {
        name: 'Detalle',
        components:{
            Comentario
        },
        props: {
            id: Number,
            estados: Array
        },

        data() {
            return {
                actividad: {
                    titulo: null,
                    detalle: null,
                    tiempo_inicial: null,
                    tiempo_fin: null,
                    estado_id:null,
                    created_at: null,
                    creador: {
                        name: null,
                        apellido: null
                    },
                    responsables: [],
                    archivos: [],
                    comentario: []
                },
            };
        },

        watch: {

            id: function () {
                this.consultar()
            }

        },

        methods: {
            /**
             * Consulta una actividad en especifico
             */
            consultar: async function () {
                try {
                    const response = await axios.get('/api/desarrollo/actividad/consultar/' + this.id)
                    this.actividad = response.data
                    this.estado_id = response.data.estado_id
                } catch (error) {
                    console.log(error.response)
                }
            },

            /**
             * cambia el estado de una actividad
             */
            cambiarEstado: async function(){
                try{
                    const request = {
                        'estado_id': this.actividad.estado_id
                    }
                    const response = await axios.post('/api/desarrollo/actividad/cambiarEstado/' + this.id, request)
                    this.consultar();
                    this.$emit('cambioEstado');
                }catch(error){
                    console.log(error.response)
                }
            }
        },
    };

</script>
