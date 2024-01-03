<template>
    <div>
        <HistoricoServiciosPrestadores  v-if="can('confirmar.datos.servicio')"></HistoricoServiciosPrestadores>
        <HistoricoServicios v-else></HistoricoServicios>

    </div>
</template>
<script>
    import HistoricoServicios from '../../../components/historico/HistoricoServicios';
    import HistoricoServiciosPrestadores     from '../../../components/historico/HistoricoServiciosProveedores';
    import {
        mapGetters
    } from 'vuex';
    export default {
        name:'historicoServicio',
        components:{HistoricoServicios,HistoricoServiciosPrestadores},
        computed:{...mapGetters(['can'])},
        data (){
            return {
            }
        }
    }
</script>
