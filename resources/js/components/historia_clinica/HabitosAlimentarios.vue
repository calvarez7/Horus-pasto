<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.lactando_actualmente" value="1"
                        label="Lactando actualment"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.lactancia_materna_exclusiva" value="1"
                        label="Lactancia materna exclusiva?:"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.postura_madre_nino" value="1"
                        label="Postura de la madre y niño"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.agarre" value="1"
                        label="Agarre"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.succion" value="1"
                        label="Succión"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.madre_reconoce_hambre_saciedad_bebe" value="1"
                        label="La madre reconoce el hambre y saciedad del bebe? "></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.necesidades_madre_lactancia_materna" value="1"
                        label="Necesidades de la madre sobre la práctica de la lactancia materna"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.recibio_preparacion_prenatal" value="1"
                        label="Recibió preparación prenatal para la lactancia materna?"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.suministra_leche_hospitalario_neonatal" value="1"
                        label="Se suministra leche de formula antes del egreso hospitalario neonatal?:  "></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.expectativas_madre_familia" value="1"
                        label="Expectativas de la madre y familia frente a la lactancia materna?"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.frecuencia_lactancia" value="1"
                        label="Frecuencia de lactancia"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.duracion_lactancia" value="1"
                        label="Duración de lactancia"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.dificultades_lactancia_materna" value="1"
                        label="Dificultades con la lactancia materna"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.madre_extrae_conserva_leche" value="1"
                        label="La madre se extrae y conserva la leche?:"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.como_realiza_extraccion_conservacion_leche" value="1"
                        label="Como realiza la extracción, conservación y transporte de la leche"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.alimentado_leche_formula" value="1"
                        label="Alimentado con leche de fórmula"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.inicio_alimentos_agua_otra_bebida" value="1"
                        label="Inicio otros alimentos, agua, aromatica u otra bebida?"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.durante_dia_ayer_recibio_liquidos" value="1"
                        label="Durante el día de ayer  recibió alguno de los siguientes liquidos: agua, aromatica, jugo, té? "></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.durante_dia_recibio_leche" value="1"
                        label="Durante el dia de ayer recibió leche de formula?"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.durante_dia_recibio_leche_vaca" value="1"
                        label="Durante el dia de ayer recibió leche (vaca, cabra) liquida, polvo, fresca o en bolsa? "></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.durante_dia_recibio_sopa" value="1"
                        label="Durante el dia de ayer o anoche recibió algún alimento como sopa espesa, puré, papilla, o seco?"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.edad_meses_diferentes_alimentos" value="1"
                        label="Edad en meses de introducción de los diferentes alimentos"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.consumo_dieta_familiar " value="1"
                        label="Consumo de la dieta familiar"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.cuantas_comidas_dia" value="1"
                        label="Cuantas comidas realiza en el día"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.consumo_5_porciones_frutas" value="1"
                        label="Consumo de 5 o más porciones de frutas y vegetales en el día"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="habitos.dieta_balanceada_baja_azucares" value="1"
                        label="Dieta balanceada baja en azúcares, sal, grasas y carbohidrato"></v-checkbox>
                </v-flex>
            </v-layout>
        </v-container>
    </v-form>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        data() {
            return {
                habitos: {}
            }
        },
        methods: {
            saveHabitosAlimentarios() {
                this.habitos.cita_paciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveHabitosAlimentarios', this.habitos)
                    .then(res => {
                        this.$alertHistoria(res.data.message);
                    })
            },
            fetchHabitosAlimentarios() {
                axios.get(`/api/historia/fetchHabitosAlimentarios/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.habitos = res.data;
                    });
            },
        }
    }

</script>
