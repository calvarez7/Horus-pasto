<template>
    <v-container fluid grid-list-xl>
        <v-layout row wrap>
            <v-flex v-for="ref in referencias" :key="ref.color" d-flex lg3 sm6 xs12>
                <Widget :color="ref.color" :icon="ref.icon" :title="ref.title" :subTitle="ref.subTitle"
                    :supTitle="ref.supTitle" />
            </v-flex>
            <v-flex xs12>
                <v-card>
                    <v-card-title>
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                        </v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="desserts" :search="search">
                        <template v-slot:items="props">
                            <td>{{ props.item.name }}</td>
                            <td class="text-xs-right">{{ props.item.calories }}</td>
                            <td class="text-xs-right">{{ props.item.fat }}</td>
                            <td class="text-xs-right">{{ props.item.carbs }}</td>
                            <td class="text-xs-right">{{ props.item.protein }}</td>
                            <td class="text-xs-right">{{ props.item.iron }}</td>
                        </template>
                        <template v-slot:no-results>
                            <v-alert :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import moment from 'moment';
    import Widget from '../../../components/referencia/Widget.vue'

    moment.locale('es');

    export default {
        name: 'ReferenciaReporte',
        components: {
            Widget,
        },
        data: () => {
            return {
                count: {
                    _a2: 0,
                    _a3: 0,
                    _a9: 0
                },
                finishDate: moment().format('YYYY-MM-DD'),
                menu1: false,
                menu2: false,
                referencias: [{
                        color: '#00b297',
                        icon: 'history',
                        title: '0',
                        subTitle: 'Total ingresados',
                        supTitle: `Hasta la fecha`,
                    }, {
                        color: '#f80',
                        icon: 'watch_later',
                        title: '0',
                        subTitle: 'En proceso',
                        supTitle: `Gestión`,
                    },
                    {
                        color: '#00c851',
                        icon: 'archive',
                        title: '0',
                        subTitle: 'Proceso interno',
                        supTitle: `En concurrencia`,
                    },
                    {
                        color: '#dc3545',
                        icon: 'notification_important',
                        title: '0',
                        subTitle: 'Pendiente',
                        supTitle: `Pendiente`,
                    }
                ],
                startDate: moment().format('YYYY-MM-DD'),
                today: moment().format('YYYY-MM-DD'),
                search: '',
        headers: [
          {
            text: 'Dessert (100g serving)',
            align: 'left',
            sortable: false,
            value: 'name'
          },
          { text: 'Calories', value: 'calories' },
          { text: 'Fat (g)', value: 'fat' },
          { text: 'Carbs (g)', value: 'carbs' },
          { text: 'Protein (g)', value: 'protein' },
          { text: 'Iron (%)', value: 'iron' }
        ],
        desserts: [
          {
            name: 'Frozen Yogurt',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
            iron: '1%'
          },
          {
            name: 'Ice cream sandwich',
            calories: 237,
            fat: 9.0,
            carbs: 37,
            protein: 4.3,
            iron: '1%'
          },
          {
            name: 'Eclair',
            calories: 262,
            fat: 16.0,
            carbs: 23,
            protein: 6.0,
            iron: '7%'
          },
          {
            name: 'Cupcake',
            calories: 305,
            fat: 3.7,
            carbs: 67,
            protein: 4.3,
            iron: '8%'
          },
          {
            name: 'Gingerbread',
            calories: 356,
            fat: 16.0,
            carbs: 49,
            protein: 3.9,
            iron: '16%'
          },
          {
            name: 'Jelly bean',
            calories: 375,
            fat: 0.0,
            carbs: 94,
            protein: 0.0,
            iron: '0%'
          },
          {
            name: 'Lollipop',
            calories: 392,
            fat: 0.2,
            carbs: 98,
            protein: 0,
            iron: '2%'
          },
          {
            name: 'Honeycomb',
            calories: 408,
            fat: 3.2,
            carbs: 87,
            protein: 6.5,
            iron: '45%'
          },
          {
            name: 'Donut',
            calories: 452,
            fat: 25.0,
            carbs: 51,
            protein: 4.9,
            iron: '22%'
          },
          {
            name: 'KitKat',
            calories: 518,
            fat: 26.0,
            carbs: 65,
            protein: 7,
            iron: '6%'
          }
        ]
            }
        },
        mounted() {
            this.counter();
        },
        methods: {
            counter(anexo) {
                axios.get('/api/referencia/counter')
                    .then(res => {
                        this.referencias[0].title = `${res.data.total}`;
                        this.referencias[1].title = `${res.data.gestion}`;
                        this.referencias[2].title = `${res.data.concurrencia}`;
                        this.referencias[3].title = `${res.data.pendientes}`;
                    })
            },
            consolidatedReport() {
                axios({
                    method: 'get',
                    params: {
                        startDate: this.startDate,
                        finishDate: this.finishDate,
                    },
                    url: '/api/referencia/consolidado',
                    responseType: 'blob'
                }).then(res => {


                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `referenciaConsolidado${this.startDate}_${this.finishDate}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }).catch(err => this.showMessage('No hay referencias para descargar'))
            },
            showMessage(message) {
                swal({
                    title: "¡Aviso!",
                    text: message,
                    icon: "warning"
                });
            },

        }
    }

</script>

<style lang="scss" scoped>
    .buttom-nav-anexo {
        width: 30% !important;
        margin: 0 35% !important;
        border-radius: 40px;
        /* border-radius: 25px 25px 0 0; */
    }

</style>
