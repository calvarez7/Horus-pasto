<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md12 class="px-2">
                                <v-radio-group label="ginecoobstetricos"
                                    v-model="medicoSaludOcupacional.ginecoobstetricos" row>
                                    <v-radio label="APLICA" value="si" color="success"></v-radio>
                                    <v-radio label="NO APLICA" value="no" color="red"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-layout row wrap v-if="medicoSaludOcupacional.ginecoobstetricos == 'si'">
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="MENARQUIA" v-model="medicoSaludOcupacional.menarquia">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="CICLOS" v-model="medicoSaludOcupacional.ciclos">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="FUM" v-model="medicoSaludOcupacional.fum">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="G" v-model="medicoSaludOcupacional.g">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3 class="px-2">
                                    <v-text-field label="P" v-model="medicoSaludOcupacional.p">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="C" v-model="medicoSaludOcupacional.c">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="A" v-model="medicoSaludOcupacional.a">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="V" v-model="medicoSaludOcupacional.v">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="FUP" v-model="medicoSaludOcupacional.fup">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs12 md12 class="px-2">
                                <v-radio-group label="planificacion" v-model="medicoSaludOcupacional.planificacion" row>
                                    <v-radio label="SI" value="si" color="success"></v-radio>
                                    <v-radio label="NO" value="no" color="red"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-layout row wrap v-if="medicoSaludOcupacional.planificacion == 'si'">
                                <v-flex xs12 sm2 class="px-2">
                                    <v-radio-group label="metodo" v-model="medicoSaludOcupacional.metodo" row>
                                        <v-radio label="ANTICONCEPTIVOS ORALES " value="si-1" color="success"></v-radio>
                                        <v-radio label="SUBDERMICO" value="si-2" color="orange"></v-radio>
                                        <v-radio label="DIU" value="si-3" color="yellow"></v-radio>
                                        <v-radio label="PRESERVATIVO" value="si-4" color="blue"></v-radio>
                                        <v-radio label="INYECTABLE MENSUAL" value="si-5" color="pink"></v-radio>
                                        <v-radio label="INYECTABLE TRIMESTRAL" value="si-6" color="primary"></v-radio>
                                        <v-radio label="DEFINITIVO" value="si-7" color="warning"></v-radio>
                                        <v-radio label="MIRENA" value="si-8" color="info"></v-radio>
                                        <v-radio label="ANILLO VAGINAL" value="si-9" color="coffe"></v-radio>
                                        <v-radio label="VASECTOMIA" value="si-10" color="purple"></v-radio>
                                    </v-radio-group>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="ULTIMA CITOLOGIA"
                                        v-model="medicoSaludOcupacional.ultima_citologia">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="RESULTADO" v-model="medicoSaludOcupacional.resultado">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 class="px-2">
                                    <v-select v-model="medicoSaludOcupacional.tratada" :items="si_no" label="TRATADA">
                                    </v-select>
                                </v-flex>
                            </v-layout>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveOcupacional()">Guardar y seguir</v-btn>
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
        created() {
            this.fetchOcupacionale();
        },
        data() {
            return {
                si_no: ['SI', 'NO'],
                medicoSaludOcupacional: {
                    f: '',
                    q: '',
                    b: '',
                    erg: '',
                    psic: '',
                    mec: '',
                    elec: '',
                    otro: '',
                    tiempo: '',
                    uso_e_p_p: '',
                    accidentes_trabajo: '',
                    fecha: '',
                    causa: '',
                    tipo_lesion: '',
                    parte_afectada: '',
                    dias_incap: '',
                    secuelas: '',
                    enfermedades_profesionales: '',
                    fecha: '',
                    empresa: '',
                    diagnostico: '',
                    reubicacion: '',
                    indemnizacion: '',
                    vacunas: '',
                    hepatitis: '',
                    dosis_v: '',
                    ultima_dosis: '',
                    t_t: '',
                    t_d: '',
                    dosi: '',
                    otras: '',
                    observacion_vacunas: '',
                    Fechas: '',
                    ginecoobstetricos: '',
                    menarquia: '',
                    ciclos: '',
                    fum: '',
                    g: '',
                    p: '',
                    c: '',
                    a: '',
                    v: '',
                    fup: '',
                    planificacion: '',
                    metodo: '',
                    ultima_citologia: '',
                    resultado: '',
                    tratada: '',
                    fuma: '',
                    fumo: '',
                    anos_fumador: '',
                    cigarrillos_al_dia: '',
                    hace_cuanto_no_fuma: '',
                    alcohol: '',
                    frecuencia: '',
                    tipo: '',
                    sustancias_psico_activas: '',
                    cuales: '',
                    practica_deporte: '',
                    cual: '',
                    regularidad: '',
                    cabeza_y_orl: 'No refiere',
                    sistema_neurologico: 'No refiere',
                    sistema_cardiopulmonar: 'No refiere',
                    sistema_digestivo: 'No refiere',
                    sistema_musculo_esqueletico: 'No refiere',
                    sistema_genitourinario: 'No refiere',
                    piel_y_faneras: 'No refiere',
                    otros: 'No refiere',
                    condiciones_generales: '',
                    dominancia_motora: '',
                    espirometria: '',
                    espirometria_si: '',
                    espirometria_no: '',
                    talla: '',
                    peso: '',
                    kg_p_a: '',
                    f_c: '',
                    f_r: '',
                    imc: '',
                    perimetroabdominal: '',
                    presionsistolica: '',
                    presiondiastolica: '',
                    presionarterialmedia: '',
                    t: '',
                    c_: '',
                    sato2: '',
                    cabeza: '',
                    ojos: '',
                    fondo_de_ojos: '',
                    oidos: '',
                    otoscopia: '',
                    nariz: '',
                    boca: '',
                    dentadura: '',
                    faringe: '',
                    cuello: '',
                    mamas: '',
                    torax: '',
                    corazon: '',
                    pulmones: '',
                    columna: '',
                    abdomen: '',
                    genitales_externos: '',
                    miembros_sup: '',
                    miembros_inf: '',
                    reflejos: '',
                    motilidad: '',
                    fuerza_muscular: '',
                    marcha: '',
                    piel_faneras: '',
                    ampliacion_hallazgos: '',
                    firma_medico_examinador: '',
                    firma_trabajador: '',
                    aptitud_laboral_medico: '',
                    vigilancia_epidemiologico: '',
                    tipoExamen: '',
                    nombre_de_la_empresa: '',
                    cargo: '',
                    antiguedad: '',
                    factoresRiesgo: '',
                    origenEnfermedades: '',
                    enfermedadComun: '',
                    patologiaAntecedente: '',
                    masaCorporal: '',
                    patologiaOsteomuscular: '',
                    patologiaCardiovascular: '',
                    patologiaMetabolica: '',
                    infecciososParasitaria: '',
                    patologiaSangre: '',
                    trastronosMentales: '',
                    patologiaNervioso: '',
                    patologiaOjo: '',
                    patologiaOido: '',
                    patologiaRespiratorio: '',
                    patologiaDigestivo: '',
                    patologiaPiel: '',
                    patologiaUrinario: '',
                    malformacionCongenitas: '',
                    diagnosticos_neoplasicos: '',
                    antecedentesenfermedad: '',
                    antecedentedetrabajo: '',
                    antecedenteenfermedadlaboral: '',
                    antecedentesfamiliares: '',
                }
            }
        },

        methods: {
            saveOcupacional() {
                axios.post('/api/cita/saveSaludocupacional/' + this.datosCita.cita_paciente_id, this
                    .medicoSaludOcupacional).then((res) => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.$emit('respuestaComponente')
                })
            },
            async fetchOcupacionale() {
                const general = await axios.post('/api/cita/getSaludocupacional/' + this.datosCita
                .cita_paciente_id);
                this.medicoSaludOcupacional = general.data;
            },
        }
    }

</script>
<style lang="scss" scoped>
    #signature {
        border: double 3px transparent;
        border-radius: 5px;
        background-image: linear-gradient(white, white),
            radial-gradient(circle at top left, #4bc5e8, #9f6274);
        background-origin: border-box;
        background-clip: content-box, border-box;
    }

</style>
