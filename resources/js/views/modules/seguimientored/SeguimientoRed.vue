<template>
    <v-layout row wrap>
        <v-container pa-1>
            <v-card>
        <v-card-title justify-center>
            <v-spacer></v-spacer>
            <v-flex sm12 xs12>
                <v-autocomplete
                    append-icon="search"
                    label="Buscar prestador..."
                    :items="prestadores"
                    v-model="prestadorId"
                    item-text="Nombre"
                    item-value="id"
                    hide-details
                    @change="counter(prestadorId)"
                ></v-autocomplete>
            </v-flex>
        </v-card-title>
            </v-card>
        </v-container>
        <v-flex v-for="ref in Seguimiento" :key="ref.color" sm6 xs12>
            <Widget :color="ref.color" :icon="ref.icon" :title="ref.title" :subTitle="ref.subTitle"
                    :supTitle="ref.supTitle" />
        </v-flex>
    </v-layout>
</template>
<script>
import moment from 'moment';
import Widget from '../../../components/referencia/Widget.vue'

moment.locale('es');
export default {
    name: 'SeguimientoRed',
    components: {
        Widget,
    },
    data: () => {
        return {
            Seguimiento: [{
                color: '#00b297',
                icon: 'history',
                title: '0',
                subTitle: 'Por Gestionar',
                supTitle: `Hasta la fecha`,
            }, {
                color: '#f80',
                icon: 'delete_forever',
                title: '0',
                subTitle: 'Canceladas',
                supTitle: `Hasta la fecha`,
            },
                {
                    color: '#00c851',
                    icon: 'done_all',
                    title: '0',
                    subTitle: 'Asistencias',
                    supTitle: `Hasta la fecha`,
                },
                {
                    color: '#dc3545',
                    icon: 'notification_important',
                    title: '0',
                    subTitle: 'Inasistencias',
                    supTitle: `Hasta la fecha`,
                }
            ],
            prestadores:[],
            prestadorId: null,
        }
    },
    mounted() {
        this.prestador();
    },
    methods: {
        counter() {
            axios.get('/api/autorizacion/contadorSeguimiento/'+ this.prestadorId)
                .then(res => {
                    console.log(res.data);
                    this.Seguimiento[0].title = res.data.PorGestionar
                    this.Seguimiento[1].title = res.data.Canceladas
                    this.Seguimiento[2].title = res.data.Asistencia
                    this.Seguimiento[3].title = res.data.inasistencia
                    console.log(this.count)
                })
        },
        prestador(){
            axios.get('/api/prestador/all').then(res => {
              this.prestadores = res.data;
            });
        }

    }
}

</script>

<style lang="scss" scoped>
.buttom-nav-anexo {
    width: 30% !important;
    margin: 0 35% !important;
    border-radius: 40px;
    border-radius: 25px 25px 0 0;
}

</style>
