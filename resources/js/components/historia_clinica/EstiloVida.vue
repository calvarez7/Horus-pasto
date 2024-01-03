<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                <span class="title layout justify-center font-weight-light">Habitos de vida</span>
            </v-card-title>
            <v-container fluid>
                <v-layout row>
                    <v-flex xs12>
                        <v-card color="lighten-1" class="mb-5" height="auto">
                            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                                <span class="title layout justify-center font-weight-light">
                                    Estilos de vida </span>
                            </v-card-title>
                            <v-card-text>
                                <v-flex xs12>
                                    <v-layout row wrap>
                                        <v-checkbox color="success" v-model="data.checkboxDietasaludable" value="1"
                                            label="Dieta">
                                        </v-checkbox>
                                    </v-layout>
                                    <v-select v-model="data.Dietasaludable" v-if="data.checkboxDietasaludable == true"
                                        :items="sino" label="Consume frutas y verduras:">
                                    </v-select>
                                    <v-select v-model="data.DietaFrecuencia" v-if="data.Dietasaludable == 'Si'"
                                        v-show="data.checkboxDietasaludable == true" :items="Frecuecia"
                                        label="Frecuencia:">
                                    </v-select>
                                    <v-select v-model="data.dieta_balanceada"
                                        v-show="data.checkboxDietasaludable == true" :items="sinoaplica"
                                        label="Dieta balanceada baja en azúcares, sal, grasas y carbohidratos:">
                                    </v-select>
                                    <v-text-field label="Cuantas comidas realiza en el dia"
                                        v-if="data.checkboxDietasaludable == true" type="number"
                                        v-model="data.cuantas_comidas">
                                    </v-text-field>
                                    <v-select v-model="data.leche" v-if="data.checkboxDietasaludable == true"
                                        v-show="datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == '1ra Infancia'"
                                        :items="sinoaplica"
                                        label="Durante el dia de ayer recibió leche (vaca, cabra) liquida, polvo, fresca o en bolsa?  :">
                                    </v-select>
                                    <v-select v-model="data.alimento" v-if="data.checkboxDietasaludable == true"
                                        v-show="datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == '1ra Infancia'"
                                        :items="sinoaplica"
                                        label="Durante el dia de ayer o anoche recibió algún alimento como sopa espesa, puré, papilla, o seco? ">
                                    </v-select>
                                    <v-text-field label="Edad en meses de introducción de los diferentes alimentos "
                                        v-if="data.checkboxDietasaludable == true" type="number"
                                        v-model="data.introduccionAlimentos"
                                        v-show="datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == '1ra Infancia'">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs10>
                                    <v-checkbox color="success" v-model="data.checkboxSuenoreparador" value="1"
                                        label="Alteraciones del Sueño">
                                    </v-checkbox>
                                    <v-select v-model="data.Suenoreparador" v-if="data.checkboxSuenoreparador == true"
                                        :items="sino" label="Si / No:">
                                    </v-select>
                                    <v-select v-model="data.TipoSueno" v-if="data.Suenoreparador == 'Si'"
                                        v-show="data.checkboxSuenoreparador == true" :items="Frecuecia"
                                        label="Tipo sueño:">
                                    </v-select>
                                    <v-text-field label="Duración sueño (Horas)"
                                        v-show="data.checkboxSuenoreparador == true"
                                        v-if="data.Suenoreparador == 'Si' || data.Suenoreparador =='No'"
                                        v-model="data.DuracionSueno">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs10>
                                    <v-checkbox color="success" v-model="data.checkboxAltonivelestres" value="1"
                                        label="Maneja Altos Niveles de Estres">
                                    </v-checkbox>
                                    <v-select v-model="data.Altonivelestres" v-if="data.checkboxAltonivelestres == true"
                                        :items="sino" label="Si / No:">
                                    </v-select>
                                </v-flex>
                                <v-flex xs10>
                                    <v-checkbox color="success" v-model="data.checkboxActividadfisica" value="1"
                                        label="Actividad Física">
                                    </v-checkbox>
                                    <v-select label="Actividad Física" v-if="data.checkboxActividadfisica == true"
                                        v-model="data.Actividadfisica" :items="sino"></v-select>
                                    <v-text-field label="Duración" v-show="data.checkboxActividadfisica == true"
                                        v-model="data.Duracion">
                                    </v-text-field>
                                    <v-select v-model="data.frecuenciaActividad"
                                        v-show="data.checkboxActividadfisica == true" :items="Frecuecia"
                                        label="Frecuencia:">
                                    </v-select>
                                </v-flex>
                                <v-flex xs10
                                    v-show="datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == '1ra Infancia'">
                                    <v-checkbox color="success" v-model="data.checkboxtv" value="1"
                                        label="Exposición a Tv, videojuegos e internet">
                                    </v-checkbox>
                                    <v-text-field label="Descripción" v-show="data.checkboxtv == true"
                                        v-model="data.Exposicion">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs10
                                    v-show="datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Adolescencia' ">
                                    <v-checkbox color="success" v-model="data.checkboxjuego" value="1"
                                        label="Tiempo en minutos de juego">
                                    </v-checkbox>
                                    <v-text-field label="Descripción" v-show="data.checkboxjuego == true"
                                        v-model="data.juego">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs10
                                    v-show="datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == '1ra Infancia'">
                                    <v-checkbox color="success" v-model="data.checkboxbano" value="1"
                                        label="¿Cuantas veces se baña al día?">
                                    </v-checkbox>
                                    <v-text-field label="Descripción" v-show="data.checkboxbano == true"
                                        v-model="data.Bano">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs10
                                    v-show="datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == '1ra Infancia'">
                                    <v-checkbox color="success" v-model="data.checkboxbucal" value="1"
                                        label="Cuidado bucal adecuado">
                                    </v-checkbox>
                                    <v-text-field label="Descripción" v-show="data.checkboxbucal == true"
                                        v-model="data.Bucal">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs10
                                    v-show="datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == '1ra Infancia'">
                                    <v-checkbox color="success" v-model="data.checkboxPosiciones" value="1"
                                        label="Frecuencia y caracteristicas de las deposiciones">
                                    </v-checkbox>
                                    <v-text-field label="Descripción" v-show="data.checkboxPosiciones == true"
                                        v-model="data.Posiciones">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs10 v-show="(datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == '1ra Infancia'
                                          || datosCita.ciclo_vital == 'Vejez')&& datosCita.Tipo_agenda != 4">
                                    <v-checkbox color="success" v-model="data.checkboxesfinter" value="1"
                                        label="Control esfinter vesical">
                                    </v-checkbox>
                                    <v-text-field label="Descripción" v-show="data.checkboxesfinter == true"
                                        v-model="data.Esfinter">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs10 v-show="(datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == '1ra Infancia'
                                          || datosCita.ciclo_vital == 'Vejez')&& datosCita.Tipo_agenda != 4">
                                    <v-checkbox color="success" v-model="data.checkboxesfinterRectal" value="1"
                                        label="Control esfinter rectal">
                                    </v-checkbox>
                                    <v-text-field label="Descripción" v-show="data.checkboxesfinterRectal == true"
                                        v-model="data.esfinterRectal">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs10 v-show="(datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == '1ra Infancia'
                                          || datosCita.ciclo_vital == 'Vejez')&& datosCita.Tipo_agenda != 4">
                                    <v-checkbox color="success" v-model="data.checkboxOrina" value="1"
                                        label="Frecuencia y caracteristicas de la orina">
                                    </v-checkbox>
                                    <v-text-field label="Descripción" v-show="data.checkboxOrina == true"
                                        v-model="data.CaracteristicasOrina"></v-text-field>
                                </v-flex>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <template>
                        <div class="text-center">
                            <v-dialog v-model="preload_liveStyle" persistent width="300">
                                <v-card color="primary" dark>
                                    <v-card-text>
                                        Tranquilo procesamos tu solicitud !
                                        <v-progress-linear indeterminate color="white" class="mb-0">
                                        </v-progress-linear>
                                    </v-card-text>
                                </v-card>
                            </v-dialog>
                        </div>
                    </template>
                </v-layout>

                <v-layout row>

                </v-layout>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">
                                Hábitos toxicos </span>
                        </v-card-title>
                        <v-card-text>
                            <v-flex xs10>
                                <v-checkbox color="success" v-model="data.checkboxexposicionhumo" value="1"
                                    label="Exposición al humo">
                                </v-checkbox>
                                <v-select label="¿Expuesto al humo?" :items="expuesto"
                                    v-show="data.checkboxexposicionhumo == true" v-model="data.expuestohumo">
                                </v-select>
                                <v-select label="Número de años que ha estado expuesto al humo"
                                    v-show="data.checkboxexposicionhumo == true" v-model="data.anosexpuesto"
                                    :items="anosexpuesto">
                                </v-select>
                            </v-flex>
                            <v-flex xs10>
                                <v-checkbox color="success" v-model="data.checkboxexposicionpsicoactivos" value="1"
                                    label="Exposición a sustancias psicoactivas">
                                </v-checkbox>
                                <v-select label="¿Expuesto a sustancias psicoactivas?" :items="sino"
                                    v-show="data.checkboxexposicionpsicoactivos == true"
                                    v-model="data.expuesttopsicoactivos">
                                </v-select>
                                <v-select label="Frecuencia"
                                    v-show="data.checkboxexposicionpsicoactivos == true & data.expuesttopsicoactivos == 'Si'"
                                    v-model="data.anosexpuesto_sustancias" :items="frecuenciaspa">
                                </v-select>
                            </v-flex>
                            <v-flex xs10 v-show="datosCita.ciclo_vital != '1ra Infancia'">
                                <v-checkbox color="success" v-model="data.checkboxFuma" value="1" label="¿Fuma?">
                                </v-checkbox>
                                <v-text-field label="Número de Cigarrillos al Dia" v-show="data.checkboxFuma == true"
                                    v-model="data.Fumacantidad"></v-text-field>
                                <v-text-field label="Número de años que fumo" v-show="data.checkboxFuma == true"
                                    v-model="data.Fumanos"></v-text-field>
                                <v-text-field label="Indice Tabáquico" readonly v-show="data.checkboxFuma == true"
                                    v-model="data.tabaquico"></v-text-field>
                                <v-text-field label="Riesgo Epoc" readonly v-show="data.checkboxFuma == true"
                                    v-model="data.riesgoEpoc"></v-text-field>
                            </v-flex>
                            <v-flex xs10 v-show="datosCita.ciclo_vital != '1ra Infancia'">
                                <span></span>
                                <v-checkbox color="success" v-model="data.checkboxConsumopsicoactivo" value="1"
                                    label="Consumo sustancias psicoactivas">
                                </v-checkbox>
                                <v-text-field label="Fecha de Inicio" v-show="data.checkboxConsumopsicoactivo == true"
                                    v-model="data.Consumopsicoactivo"></v-text-field>
                                <v-text-field label="Cantidad" v-show="data.checkboxConsumopsicoactivo == true"
                                    v-model="data.Psicoactivocantidad">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs10 v-show="datosCita.ciclo_vital != '1ra Infancia'">
                                <v-checkbox color="success" v-model="data.checkboxTomalicor" value="1"
                                    label="Consumo Licor">
                                </v-checkbox>
                                <v-autocomplete label="Cantidad de tragos de licor"
                                    v-show="data.checkboxTomalicor == true" v-model="data.Cantidadlicor"
                                    :items="cantidadLicor"></v-autocomplete>
                                <v-autocomplete v-show="data.checkboxTomalicor == true" :items="frecuenciaConsumo"
                                    label="Frecuencia" v-model="data.Licorfrecuencia" />
                            </v-flex>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs12>
                    <v-card>
                        <v-container fill-height fluid>
                            <v-layout fill-height>
                                <v-flex xs12 align-end flexbox>
                                    <v-textarea name="input-7-1" label="Observación" value
                                        v-model="data.Estilovidaobservaciones"></v-textarea>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>
            </v-container>
            <v-btn color="success" round @click="guardarEstilovida()">Guardar y seguir</v-btn>
        </v-flex>
    </v-layout>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchLifeStyle()
        },
        data() {
            return {
                preload_liveStyle: false,
                data: {
                    citapaciente_id: '',
                    paciente_id: '',
                    checkboxexposicionhumo: '',
                    expuestohumo: '',
                    anosexpuesto: '',
                    cuantas_comidas: '',
                    dieta_balanceada: '',
                },
                sino: ['Si', 'No'],
                expuesto: ['Fumador pasivo', 'Fumador activo', 'Exfumador', 'No fumador'],
                anosexpuesto: ['Menor a 10 años', 'Entre 10 a 20 años', 'mas de 20 años'],
                sinoaplica: ['Si', 'No', 'No Aplica'],
                Frecuecia: ['Diario', '2 a 3 veces en la semana', 'cada semana'],
                frecuenciaConsumo: ['Nunca', 'Una o mas veces al mes', 'De 2 a 4 veces al mes',
                    'De 2 a 3 veces a la semana', '4 o mas veces a la semana'
                ],
                cantidadLicor: ['1 o 2', '3 o 4', '5 o 6', '7,8 o 9', '10 o mas'],
                frecuenciaspa: ['Diaria', 'Semanal', 'Mensual', 'Ocasional'],
            };
        },
        watch: {
            "data.Fumanos": function () {
                this.calcularIndiceTabaquico();
            },
        },
        methods: {
            guardarEstilovida() {
                this.data.paciente_id = this.datosCita.paciente_id;
                this.data.citapaciente_id = this.datosCita.cita_paciente_id;
                this.preload_liveStyle = true
                axios.post("/api/estilovida/" + this.datosCita.cita_paciente_id + "/create", this.data)
                    .then(res => {
                        this.preload_liveStyle = false;
                        this.$alertHistoria(res.data.message);
                        this.fetchLifeStyle()
                        this.guardarSeguir()
                    });
            },
            fetchLifeStyle() {
                axios.get('/api/estilovida/' + this.datosCita.cita_paciente_id + '/lifeStyle')
                    .then(res => {
                        if (res.data.estilovida) {
                            this.data = res.data.estilovida;
                        }
                    });
            },
            guardarSeguir() {
                this.$emit('respuestaComponente')
            },
            calcularIndiceTabaquico() {
                this.data.tabaquico =
                    (parseInt(this.data.Fumacantidad) *
                        parseInt(this.data.Fumanos)) /
                    20;

                if (this.data.tabaquico < 10) {
                    this.data.riesgoEpoc = 'Nulo'
                } else if (this.data.tabaquico >= 10 && this.data.tabaquico <= 20) {
                    this.data.riesgoEpoc = 'Moderado'
                } else if (this.data.tabaquico >= 21 && this.data.tabaquico <= 40) {
                    this.data.riesgoEpoc = 'Intenso'
                } else if (this.data.tabaquico > 40) {
                    this.data.riesgoEpoc = 'Alto'
                }

            },
        }
    };

</script>
<style scoped>
</style>
