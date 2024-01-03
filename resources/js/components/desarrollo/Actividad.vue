<template>

    <v-card color="blue-grey lighten-4" class="mb-3">

        <v-btn
            fab
            small
            color="blue-grey darken-1"
            top
            right
            absolute
            :loading="cargando"
            @click="cambiarEstado()"
            v-if="actividad.estado_id != 34"
        >
            <v-icon>check</v-icon>
        </v-btn>

        <v-card-title>
            <h4 class="text-lg-left px-4 pb-0">{{ actividad.titulo }}</h4>
        </v-card-title>

        <v-card-text class="text-lg-left px-3 pb-0" v-if="actividad.estado_id != 34">
            <v-layout row justify-space-between>
                <v-flex>
                    <p>{{ actividad.created_at }}</p>
                </v-flex>
                <v-flex>
                    <p class="text-lg-right" :class="mora ? 'red--text text--lighten-1' : 'green--text text--lighten-1'">{{ diferenciaDias() }}</p>
                </v-flex>
            </v-layout>
        </v-card-text>

        <v-card-text v-else>
            <p class="green--text text--lighten-1 mb-1 text-lg-left">Terminada</p>
        </v-card-text>

        <v-card-actions>
            <v-btn small @click="$emit('verDetalle', actividad)">
                Ver detalle
            </v-btn>
        </v-card-actions>
    </v-card>

</template>

<script>

import moment from 'moment'
export default {
    name: 'Actividad',
    props: {
        actividad: Object,
    },
    data() {
        return {
            cargando: false,
            mora: false
        };
    },

    mounted() {
    },

    methods: {

        /**
         * Cambia a esta de terminado
         */
        cambiarEstado: async function(){
            try {
                this.cargando = true
                const response = await axios.post('/api/desarrollo/actividad/cambiarEstado/' + this.actividad.id, {estado_id: 34})
                swal({
                    title: '¡ Exito !',
                    text: response.data.message,
                    type: "success",
                    timer: 2000,
                    icon: "success",
                    buttons: false
                });
                this.$emit('cambioEstado')
                this.cargando = false
            } catch (error) {
                console.log(error.response)
            }
        },

        /**
         * Diferencia en dias
         */
        diferenciaDias: function(){
            const ahora = new moment()
            const final = moment(this.actividad.tiempo_fin)
            const diferencia =  final.diff(ahora, 'days')
            if(diferencia === 0){
                this.mora = false
                return 'Hoy'
            }
            if(diferencia > 0){
                this.mora = false
                return diferencia + ' dias'
            }
            if(diferencia < 0){
                this.mora = true
                return 'Hace ' + (diferencia * -1) + ' dias'
            }
        }
    }
}
</script>
