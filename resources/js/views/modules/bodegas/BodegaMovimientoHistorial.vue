<template>
    <div>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs4>
                                <v-menu
                                    ref="menu1"
                                    v-model="menu1"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="startDate"
                                        label="Fecha inicio"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        color="primary"
                                        locale="es"
                                        :max="finishDate"
                                        no-title
                                        v-model="startDate"
                                        @input="menu1 = false"/>
                                </v-menu>
                            </v-flex>
                            <v-flex xs4 pl-2>
                                <v-menu
                                    ref="menu2"
                                    v-model="menu2"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="finishDate"
                                        label="Fecha final"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        color="primary"
                                        locale="es"
                                        :min="startDate"
                                        :max="today"
                                        no-title
                                        v-model="finishDate"
                                        @input="menu2 = false" />
                                </v-menu>
                            </v-flex>
                            <v-flex xs12>
                                <vAutocomplete
                                    :items="listaBodegas"
                                    v-model="bodega_id"
                                    label="Bodega"
                                    item-value="id"
                                    item-text="Nombre" @change="getUsuarios" />
                            </v-flex>
                            <v-flex xs12>
                                <vAutocomplete
                                    :items="usuarios"
                                    v-model="usuario_id"
                                    label="usuario"
                                    item-value="id"
                                    item-text="name" />
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-flex>
            <v-flex xs12 mt-2>
                <v-card>
                    <v-container fluid>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <RouterView :bodega="bodega_id" :startDate="startDate" :finishDate="finishDate" :usuario="usuario_id" />
                                <!-- <v-data-table
                                    :headers="headerArticulos"
                                    :items="movimientos"
                                    hide-actions
                                    pagination.sync="pagination"
                                    :loading="true"
                                    search="search">

                                    <template v-slot:no-data>
                                        <span class="layout justify-center">No hay movimientos.</span>
                                    </template>
                                </v-data-table> -->
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-flex>
        </v-layout>
        <v-layout row wrap justify-center>
            <v-flex xs12>
                <v-bottom-nav
                    class="buttom-nav-anexo"
                    color="primary"
                    :value="true"
                    fixed
                    dark>
                    <v-btn dark :to="urlDispensado" exact>
                        <span>Dispensado</span>
                    </v-btn>
                </v-bottom-nav>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import moment from 'moment';
    moment.locale('es')
    export default {
        name: 'BodegaMovimientoHistorial',
        data() {
            return {
                bodega_id: null,
                usuarios: [],
                usuario_id: null,
                urlDispensado: '/bodegas/historico/dispensado',
                headerArticulos: [{
                        text: '#',
                        sortable: false
                    },
                    {
                        text: 'CUM',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Medicamento',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Titular',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Cantidad',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: '',
                        sortable: false,
                        align: 'center'
                    },
                ],
                listaBodegas: [],
                menu1: false,
                menu2: false,
                movimientos: [],
                startDate: moment().format('YYYY-MM-DD'),
                finishDate: moment().format('YYYY-MM-DD'),
                today: moment().format('YYYY-MM-DD'),
            }
        },
        mounted() {
            this.fetchBodegas();
        },
        methods: {
            fetchBodegas() {
                axios.get('/api/bodega/getBodegaByRole')
                    .then(res => {
                            this.listaBodegas =  res.data
                    })
            },
            getUsuarios(){

                axios.get('/api/bodega/usuariosDispensa/'+this.bodega_id).then(res => {
                    console.log(res.data);
                    this.usuarios = res.data;
                })
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
