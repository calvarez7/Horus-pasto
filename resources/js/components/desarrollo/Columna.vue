<template>

    <v-flex md3>
        <v-card class="pa-3">
            <h3 class="mb-2">{{ estado.Nombre }}</h3>
            <Actividad v-for="actividad in actividades" :key="actividad.id" :actividad="actividad" @verDetalle="abrirDialog" @cambioEstado="$emit('actualizar')"/>
        </v-card>

        <v-dialog v-model="dialog" width="800px">
            <v-card class="pa-4">
                <Detalle :id="actividad_id" :estados="estados" @cambioEstado="[$emit('actualizar'), dialog = false]"/>
            </v-card>
        </v-dialog>

    </v-flex>

</template>

<script>

import Actividad from './Actividad.vue'
import Detalle from './Detalle.vue'

export default {
    name: 'ColumnaActividades',

    components:{
        Actividad,
        Detalle
    },

    props:{
        estado: Object,
        actividades: Array,
        estados: Array
    },

    data() {
        return {
            actividad_id: null,
            dialog: false
        };
    },

    methods: {
        /**
         * Abre y envia la actividad a el drawer
         */
        abrirDialog: function(actividad){
            this.dialog = true
            this.actividad_id = actividad.id
            console.log(actividad.id)
        }
    },
};
</script>
