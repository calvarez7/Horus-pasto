<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                <span class="title layout justify-center font-weight-light">Habitos toxicos</span>
            </v-card-title>
            <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="toxicos.checkboxTabaquismo" value="1" label="Tabaquismo">
                    </v-checkbox>
                    <v-select v-model="toxicos.presente_tabaquismo" v-if="toxicos.checkboxTabaquismo== true"
                        :items="sino" label="Presente"></v-select>
                    <v-select v-model="toxicos.tipo_tabaquismo"
                        v-if="toxicos.checkboxTabaquismo== true & toxicos.presente_tabaquismo=='Si'" :items="humo"
                        label="Tipo de Fumador"></v-select>
                    <v-text-field type="number"
                        v-if="toxicos.checkboxTabaquismo== true & toxicos.presente_tabaquismo=='Si'"
                        v-model="toxicos.n_cigarrillo" label="N° cigarrillo Diarios"></v-text-field>
                    <v-text-field type="number"
                        v-if="toxicos.checkboxTabaquismo== true & toxicos.presente_tabaquismo=='Si'"
                        v-model="toxicos.paquetes_cigarrillo" label="N° de Paquetes Diarios"></v-text-field>
                    <v-text-field type="date"
                        v-if="toxicos.checkboxTabaquismo== true & toxicos.presente_tabaquismo=='Si'"
                        v-model="toxicos.fecha_cigarrillo" label="Fechas de Inicio"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="toxicos.checkboxAlcohol" value="1" label="Consulmo de Alcohol">
                    </v-checkbox>
                    <v-select v-model="toxicos.presente_alcohol" v-if="toxicos.checkboxAlcohol== true" :items="sino"
                        label="Presente"></v-select>
                    <v-select v-model="toxicos.tipo_alcohol"
                        v-if="toxicos.checkboxTabaquismo== true & toxicos.presente_alcohol=='Si'" :items="tipoalcohol"
                        label="Tipo de Tomador"></v-select>
                    <v-select v-model="toxicos.frecuencia_alcohol"
                        v-if="toxicos.checkboxAlcohol== true & toxicos.presente_alcohol=='Si'" :items="frecuencia"
                        label="Frecuencia de Consumo de Alcohol"></v-select>
                    <v-text-field type="date" v-if="toxicos.checkboxAlcohol== true & toxicos.presente_alcohol=='Si'"
                        v-model="toxicos.fecha_alcohol" label="Fechas de Inicio"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="toxicos.checkboxPsicofarmacos" value="1"
                        label="Consumo de Psicofarmacos"></v-checkbox>
                    <v-select v-model="toxicos.presente_psicofarmacos" v-if="toxicos.checkboxPsicofarmacos== true"
                        :items="sino" label="Presente"></v-select>
                    <v-select v-model="toxicos.tipo_psicofarmacos"
                        v-if="toxicos.checkboxPsicofarmacos== true & toxicos.presente_psicofarmacos=='Si'"
                        :items="tipopsicofarmacos" label="Tipo de SPA"></v-select>
                    <v-textarea outlined name="input-7-4" v-if="toxicos.tipo_psicofarmacos=='Otros'"></v-textarea>
                    <v-select v-model="toxicos.frecuencia_psicofarmacos"
                        v-if="toxicos.checkboxPsicofarmacos== true & toxicos.presente_psicofarmacos=='Si'"
                        :items="frecuencia" label="Frecuencia de Consumo de SPA"></v-select>
                    <v-text-field type="date"
                        v-if="toxicos.checkboxPsicofarmacos== true & toxicos.presente_psicofarmacos=='Si'"
                        v-model="toxicos.fecha_psicofarmacos" label="Fechas de Inicio"></v-text-field>
                </v-flex>
            </v-layout>
            <v-btn color="success" round @click="saveHabitosToxicos()">Guardar y seguir</v-btn>
        </v-container>
    </v-form>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created(){
            this.fetchHabitosToxicos();
        },
        data() {
            return {
                humo: ['Fumador pasivo', 'Fumador activo', 'Exfumador', 'No fumador'],
                frecuencia: ['Diaria', 'Semanal', 'Mensual', 'Ocasional'],
                tipopsicofarmacos: ['Marihuana', 'Perico', 'Cocaina', 'Otros'],
                toxicos: {
                    paciente_id: '',
                    citapaciente_id: '',
                    checkboxTabaquismo: '',
                    presente_tabaquismo: '',
                    checkboxAlcohol: '',
                    presente_alcohol: '',
                    checkboxPsicofarmacos: '',
                    presente_psicofarmacos: '',
                    tipo_tabaquismo: '',
                    tipo_alcohol: '',
                    tipo_psicofarmacos: '',
                    n_cigarrillo: '',
                    frecuencia_alcohol: '',
                    frecuencia_psicofarmacos: '',
                    paquetes_cigarrillo: '',
                    fecha_alcohol: '',
                    fecha_psicofarmacos: '',
                    fecha_cigarrillo: '',
                },
                sino: ['Si', 'No'],
                tipoalcohol: ['Social', 'Diario']
            }
        },
        methods: {
            saveHabitosToxicos() {
                this.toxicos.citapaciente_id = this.datosCita.cita_paciente_id;
                this.toxicos.paciente_id = this.datosCita.paciente_id;
                axios.post('/api/historia/saveHabitosToxicos', this.toxicos)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente');
                    })
            },
            fetchHabitosToxicos() {
                axios.get(`/api/historia/fetchHabitosToxicos/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.toxicos = res.data;
                    });
            },
        }
    }

</script>
