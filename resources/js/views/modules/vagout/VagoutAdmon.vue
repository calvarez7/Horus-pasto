<template>
    <v-container fluid grid-list-xl>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-text>
                        <v-container class="pa-0">
                            <v-layout row wrap>
                                <v-container>
                                    <v-layout row wrap>

                                        <v-flex xs12 sm6 md3>
                                            <v-text-field label="Nit-Prestador"></v-text-field>
                                        </v-flex>

                                        <v-flex xs12 sm6 md2>
                                            <v-menu ref="menu1" v-model="menu1" :close-on-content-click="false"
                                                :nudge-right="40" lazy transition="scale-transition" offset-y full-width
                                                max-width="290px" min-width="290px">
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field v-model="dateFormatted" label="Fecha Inicio"
                                                        persistent-hint prepend-icon="event"
                                                        @blur="date = parseDate(dateFormatted)" v-on="on">
                                                    </v-text-field>
                                                </template>
                                                <v-date-picker color="primary" v-model="date" no-title
                                                    @input="menu1 = false">
                                                </v-date-picker>
                                            </v-menu>
                                        </v-flex>

                                        <v-flex xs12 sm6 md3>
                                            <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40"
                                                lazy transition="scale-transition" offset-y full-width max-width="290px"
                                                min-width="290px">
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field v-model="computedDateFormatted" label="Fecha Fin"
                                                        persistent-hint prepend-icon="event" readonly v-on="on">
                                                    </v-text-field>
                                                </template>
                                                <v-date-picker color="primary" v-model="date" no-title
                                                    @input="menu2 = false">
                                                </v-date-picker>
                                            </v-menu>
                                        </v-flex>

                                        <v-flex xs12 sm6 md2>
                                            <v-text-field label="# Factura"></v-text-field>
                                        </v-flex>

                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-data-table :headers="headers" :items="desserts" class="elevation-1" prev-icon="mdi-menu-left"
                        next-icon="mdi-menu-right" sort-icon="mdi-menu-down">
                        <template v-slot:items="props">
                            <td>{{ props.item.name }}</td>
                            <td class="text-xs-right">{{ props.item.calories }}</td>
                            <td class="text-xs-right">{{ props.item.fat }}</td>
                            <td class="text-xs-right">{{ props.item.carbs }}</td>
                            <td class="text-xs-right">{{ props.item.protein }}</td>
                            <td class="text-xs-right">{{ props.item.iron }}</td>
                            <v-btn color="#00b297" dark @click="modalAsignarFactura = true">Asignar</v-btn>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
        <v-dialog v-model="modalAsignarFactura" scrollable max-width="500px">
            <v-card>
                <v-card-title>Asignacion de Facturas</v-card-title>
                <v-card-text>
                    <v-select :items="items" label="Selecciona el usuario"></v-select>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-text style="height: 300px;">
                    <v-data-table v-model="selected" :headers="headersFacturas" :items="facturas" :pagination.sync="pagination"
                        select-all item-key="name" class="elevation-1">
                        <template v-slot:headers="props">
                            <tr>
                                <th>
                                    <v-checkbox color="primary" :input-value="props.all" :indeterminate="props.indeterminate" primary
                                        hide-details @click.stop="toggleAll"></v-checkbox>
                                </th>
                                <th v-for="header in props.headers" :key="header.text"
                                    :class="['column sortable', pagination.descending ? 'desc' : 'asc', header.value === pagination.sortBy ? 'active' : '']"
                                    @click="changeSort(header.value)">
                                    <v-icon small>arrow_upward</v-icon>
                                    {{ header.text }}
                                </th>
                            </tr>
                        </template>
                        <template v-slot:items="props">
                            <tr :active="props.selected" @click="props.selected = !props.selected">
                                <td>
                                    <v-checkbox color="primary" :input-value="props.selected" primary hide-details>
                                    </v-checkbox>
                                </td>
                                <td>{{ props.item.name }}</td>
                                <td class="text-xs-right">{{ props.item.calories }}</td>
                                <td class="text-xs-right">{{ props.item.fat }}</td>
                            </tr>
                        </template>
                    </v-data-table>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn color="blue darken-1" flat @click="dialog = false">Close</v-btn>
                    <v-btn color="blue darken-1" flat @click="dialog = false">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
    import moment from 'moment';
    import Widget from '../../../components/referencia/Widget.vue';
    import { UserService } from '../../../services';

    moment.locale('es');

    export default {
        name: 'ReferenciaReporte',
        components: {
            Widget,
        },
        data: vm => ({
            selected: [],
            modalAsignarFactura: false,
            date: new Date().toISOString().substr(0, 10),
            dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
            menu1: false,
            menu2: false,
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
            headers: [{
                    text: 'Dessert (100g serving)',
                    align: 'left',
                    sortable: false,
                    value: 'name'
                },
                {
                    text: 'Calories',
                    value: 'calories'
                },
                {
                    text: 'Fat (g)',
                    value: 'fat'
                },
                {
                    text: 'Carbs (g)',
                    value: 'carbs'
                },
                {
                    text: 'Protein (g)',
                    value: 'protein'
                },
                {
                    text: 'Iron (%)',
                    value: 'iron'
                },
                {
                    text: 'Asignar',
                    value: 'Asignar'
                }
            ],
            headersFacturas: [{
                    text: 'Dessert (100g serving)',
                    align: 'left',
                    sortable: false,
                    value: 'name'
                },
                {
                    text: 'Calories',
                    value: 'calories'
                },
                {
                    text: '',
                    value: ''
                }
            ],
            desserts: [{
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
            ],
            facturas: [{
                    name: 'Frozen Yogurt',
                    calories: 159
                },
                {
                    name: 'Ice cream sandwich',
                    calories: 237
                }
            ]
        }),

        computed: {
            computedDateFormatted() {
                return this.formatDate(this.date)
            }
        },

        watch: {
            date(val) {
                this.dateFormatted = this.formatDate(this.date)
            }
        },
        mounted() {
            this.counter();
            this.fetchUsers();
        },
        methods: {
            fetchUsers: async function () {
                try {
                    this.users = await UserService.activos();
                } catch (err) {
                    console.log(err)
                }
            },
            formatDate(date) {
                if (!date) return null

                const [year, month, day] = date.split('-')
                return `${month}/${day}/${year}`
            },
            parseDate(date) {
                if (!date) return null

                const [month, day, year] = date.split('/')
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
            },
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
            toggleAll() {
                if (this.selected.length) this.selected = []
                else this.selected = this.desserts.slice()
            },
            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            }

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
