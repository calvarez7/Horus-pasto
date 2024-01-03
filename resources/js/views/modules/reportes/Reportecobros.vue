<template>
    <v-container fluid grid-list-xl>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title primary-title>
                        <h3 class="headline mb-0">Filtros</h3>
                    </v-card-title>
                    <v-card-text>
                        <v-container class="pa-0">
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
                                                :disabled="actuales"
                                                v-model="dateDesde"
                                                label="Fecha Desde"
                                                prepend-icon="event"
                                                readonly
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker color="primary" v-model="dateDesde" no-title @input="menu1 = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex xs4>
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
                                                :disabled="actuales"
                                                v-model="dateHasta"
                                                label="Fecha Hasta"
                                                prepend-icon="event"
                                                readonly
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker color="primary" v-model="dateHasta" no-title  @input="menu2 = false">
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>

                                <v-flex xs4 sm4 class="text-xs-right" >
                                    <v-checkbox color="primary"
                                                v-model="actuales"
                                                label="Filtrar Actuales"
                                    ></v-checkbox>
                                </v-flex>

                                <v-flex xs12 sm12 class="text-xs-right" >
                                    <v-btn round color="success" @click="generarInforme">Generar</v-btn>
                                </v-flex>

                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: 'reporteCobros',

        data: () => ({
            listaSedes: [],
            estados: [{id:4,nombre:'Pendientes'},{id:7,nombre:'Confirmadas'}],
            sede: null,
            estado: null,
            dateDesde: new Date().toISOString().substr(0, 10),
            dateHasta: new Date().toISOString().substr(0, 10),
            menu1: false,
            menu2: false,
            actuales: false,

        }),
        methods:{
            listarSedes (){
                axios.get('/api/sede/all').then(res =>{
                    this.listaSedes = res.data;
                    console.log(this.listaSedes)
                })
            },
            generarInforme(){
                axios({
                    method: 'post',
                    params: {
                        sede: this.sede,
                        estado: this.estado,
                        actual: this.actuales,
                        fechaDesde:  this.dateDesde,
                        fechaHasta: this.dateHasta
                    },
                    url: '/api/cobro/informe',
                    responseType: 'blob'
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `InformeCobros.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                    this.dialog = false
                }).catch(err => {
                    console.log(err)
                })
            }
        },
        beforeMount() {
            this.listarSedes();
        }

    }

</script>

<style>

</style>
