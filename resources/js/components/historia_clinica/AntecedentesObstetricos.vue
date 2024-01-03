<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="obstetrico.g" label="G" type="number" maxlength="12"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="obstetrico.p" label="P" type="number" maxlength="12"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="obstetrico.c" label="C" type="number" maxlength="12"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="obstetrico.a" label="A" type="number" maxlength="12"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="obstetrico.v" label="V" type="number" maxlength="12"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="obstetrico.m" label="M" type="number" maxlength="12"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.ectopicos" label="Ectópicos" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.molas" label="Molas" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.gemelos" label="Gemelos " ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.placenta_previa" label="Placenta previa: " ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.abruptio" label="Abruptio: " ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.hemorragia_posparto" label="Ant. Hemorragia posparto" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.depresion_posparto" label="Ant. Depresión posparto" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.rpm" label="RPM" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="obstetrico.otros" label="Otros"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="obstetrico.intervalo_intergenesico" label="Intervalo intergenesico"
                        type="number" maxlength="12"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="obstetrico.fecha_ultimo_parto" label="Fecha ultimo parto" type="date">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.parto_prematuro" label="Ant. Parto prematuro:" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="obstetrico.semanas_gestacion" label="Semanas gestacionales a la captación"
                        type="number" maxlength="12"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="obstetrico.semana_nacimiento" label="Semana al nacimiento" type="number"
                        maxlength="12"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.preeclampsia" label="Preeclampsia" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.eclampsia" label="Eclampsia" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.sindrome_hellp" label="Sindrome Hellp" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.malformaciones_congenitas" label="Malformaciones congénitas">
                     </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.bajo_peso" label="RN Bajo peso" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.macrosomicos" label="Macrosómicos " ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.incompatibilidad_rh" label="Incompatibilidad RH: " ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.multiparidad" label="Multiparidad" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.consulta_preconcepcional"
                        label="La paciente tuvo consulta preconcepcional" ></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="obstetrico.embarazo_antes_1_anio" label="Planea embarazo antes de 1 año?">
                     </v-checkbox>
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
                obstetrico: {}
            }
        },
        methods: {
            saveAntecenetesObstetricos() {
                this.obstetrico.cita_paciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveAntecenetesObstetricos', this.obstetrico)
                    .then(res => {
                        this.$alertHistoria(res.data.message);
                    });
            },
            fetchAntecenetesObstetricos() {
                axios.get(`/api/historia/fetchAntecenetesObstetricos/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.obstetrico = res.data;
                    });
            },
        }

    }

</script>
