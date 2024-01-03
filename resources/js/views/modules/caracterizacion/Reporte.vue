<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-card-text class="headline success" style="color:white">
                    <h4 style="color:white">Reporte de Caracterización</h4>
                </v-card-text>
                <v-card-text>
                    <v-layout row wrap>

                        <!-- <v-flex xs3 px-2>
                            <v-select :items="Municipios" item-text="departamento" v-model="departamento_id"
                                label="Selecciona el departamento..."></v-select>
                        </v-flex> -->
                        <!-- <v-flex xs3 px-2>
                            <v-select :items="entidad" v-model="IPS" label="Selecciona el IPS primaria..."></v-select>
                        </v-flex> -->

                        <!-- <v-flex xs3 px-2>
                            <v-autocomplete label="Selecciona el municipio..." :items="Municipios" item-text="municipio"
                                item-value="id" v-model="Municipio_id"></v-autocomplete>
                         </v-flex> -->

                        <!-- <v-flex xs3 px-2>
                            <v-autocomplete transition="fab-transition" :items="entidades" item-text="nombre" item-value="id" v-model="entidad_id"
                                label="Selecciona el asegurador...">
                            </v-autocomplete>
                        </v-flex> -->

                        <!-- <v-flex xs3 px-2>
                            <v-select :items="regiones" v-model="region" label="Selecciona la región..."></v-select>
                        </v-flex> -->

                        <!-- Elegir la fecha inicial del reporte -->
                        <v-flex xs4>
                            <v-menu v-model="menu1" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="fab-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="startDate" label="Fecha inicial del reporte" prepend-icon="event" readonly
                                        v-on="on" />
                                </template>
                                <VDatePicker color="primary" :max="finishDate" v-model="startDate" @input="menu1 = false" />
                            </v-menu>
                        </v-flex>
                        <!-- fin -->

                        <!-- Elegir la fecha final del reporte -->
                        <v-flex xs3>
                            <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="fab-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="finishDate" label="Fecha final del reporte" prepend-icon="event" readonly
                                        v-on="on" />
                                </template>
                                <VDatePicker color="primary" :min="startDate" :max="today" v-model="finishDate"
                                    @input="menu2 = false" />
                            </v-menu>
                        </v-flex>
                        <!-- fin -->
                        <v-flex xs1>
                            <!-- Botón para descargar -->
                            <v-btn color="warning" :loading="loading" round @click="consolidatedReport()" small>Descargar</v-btn>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>
<script>
import moment from 'moment';
import Widget from '../../../components/referencia/Widget.vue'

moment.locale('es');
export default {
    name: 'RipsReportes',
    components: {
        Widget,
    },
    data: () => {
        return {
            consolidado: '',
            financiero: '',
            archivos1: '',
            archivo12: '',
            PrestadorTipo: '',
            Prestadors: [{
                nombre: 'Oncologia',
                valor: 7
            }],
            regiones: ['Region 8'],
            Municipios: [],
            entidades: [ 'REDVITAL UT', 'FERROCARRILES'],
            loading: false,
            menu1: false,
            menu2: false,
            menu3: false,
            menu4: false,
            menu5: false,
            menu6: false,
            municipio: '',
            departamento: '',
            Municipio_id: '',
            departamento_id: '',
            entidad_id: '',
            region: '',
            startDate: moment().format('YYYY-MM-DD'),
            finishDate: moment().format('YYYY-MM-DD'),
            today: moment().format('YYYY-MM-DD'),
        }
    },
    mounted() {
        // this.counter();
        // this.fetchMunicipios();
        // this.getentidades();

    },
    methods: {
        //Lista las entidades
        // getentidades() {
        //     axios.get('/api/entidades/getEntidades')
        //         .then((res) => {
        //             this.entidades = res.data
        //         })
        //         .catch((err) => (err));
        // },

        // //Busca y lista los municipios
        // fetchMunicipios() {
        //     axios.get('/api/municipio/lista')
        //         .then(res => {
        //             this.Municipios = res.data
        //         });
        // },


        //Funcion para descargar el reporte en excel sobre caracterización
        consolidatedReport() {
            this.loading = true;
            axios({
                method: 'post',
                data: {
                    startDate: this.startDate,
                    finishDate: this.finishDate,
                },
                url: '/api/pdf/reporteCaracterizacion',
                responseType: 'blob'
            }).then(res => {
                if (res.status === 200) {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');
                    a.download = `reporteCaracterizacion${this.startDate}_${this.finishDate}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.loading = false;
                } else if (res.status === 404) {
                    this.loading = false;
                }
            }).catch(err => {
                this.loading = false;
                this.showMessage('No hay caracterizaciones para descargar');
            })
        },
        showMessage(message) {
            swal({
                title: "¡Aviso!",
                text: message,
                icon: "warning"
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
    /* border-radius: 25px 25px 0 0; */
}
</style>
