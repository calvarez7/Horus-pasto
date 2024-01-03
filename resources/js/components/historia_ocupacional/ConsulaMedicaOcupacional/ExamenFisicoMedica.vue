<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm12 class="px-2">
                                <v-textarea outline name="input-7-4" label="CONDICIONES GENERALES"
                                    v-model="medicoSaludOcupacional.condiciones_generales"></v-textarea>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.dominancia_motora" :items="motora"
                                    label="DOMINANCIA MOTORA"></v-select>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="TALLA" type="number" @change="totalIMC" min="0"
                                    v-model="medicoSaludOcupacional.talla">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="PESO" type="number" @change="totalIMC" min="0"
                                    v-model="medicoSaludOcupacional.peso">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="FC" v-model="medicoSaludOcupacional.f_c">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="FR" v-model="medicoSaludOcupacional.f_r">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="IMC" v-model="medicoSaludOcupacional.imc" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field type="number" label="perimetro abdominal"
                                    v-model="medicoSaludOcupacional.perimetroabdominal">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs2 px-1>
                                <v-text-field label="Presión / Sistólica" value type="number"
                                    v-model="medicoSaludOcupacional.presionsistolica"></v-text-field>
                            </v-flex>
                            <v-flex xs2 px-1>
                                <v-text-field label="Presión / Diastólica" value type="number"
                                    v-model="medicoSaludOcupacional.presiondiastolica"></v-text-field>
                            </v-flex>
                            <v-flex xs2 px-1>
                                <v-text-field label="Presión Arterial Media" value
                                    v-model="medicoSaludOcupacional.presionarterialmedia" readonly></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="TA" v-model="medicoSaludOcupacional.t">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="CABEZA" v-model="medicoSaludOcupacional.cabeza">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="OJOS" v-model="medicoSaludOcupacional.ojos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="FONDO DE OJOS" v-model="medicoSaludOcupacional.fondo_de_ojos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="OIDOS" v-model="medicoSaludOcupacional.oidos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="OTOSCOPIA" v-model="medicoSaludOcupacional.otoscopia">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="NARIZ" v-model="medicoSaludOcupacional.nariz">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="BOCA" v-model="medicoSaludOcupacional.boca">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="DENTADURA" v-model="medicoSaludOcupacional.dentadura">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="FARINGE" v-model="medicoSaludOcupacional.faringe">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="CUELLO" v-model="medicoSaludOcupacional.cuello">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="MAMAS" v-model="medicoSaludOcupacional.mamas">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="TORAX" v-model="medicoSaludOcupacional.torax">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="CORAZON" v-model="medicoSaludOcupacional.corazon">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="PULMONES" v-model="medicoSaludOcupacional.pulmones">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="COLUMNA" v-model="medicoSaludOcupacional.columna">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="ABDOMEN" v-model="medicoSaludOcupacional.abdomen">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="GENITALES EXTERNOS"
                                    v-model="medicoSaludOcupacional.genitales_externos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="MIEMBROS SUPERIORES" v-model="medicoSaludOcupacional.miembros_sup">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="MIEMBROS INFERIORES" v-model="medicoSaludOcupacional.miembros_inf">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="REFLEJOS" v-model="medicoSaludOcupacional.reflejos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="MOTILIDAD" v-model="medicoSaludOcupacional.motilidad">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="FUERZA MUSCULAR" v-model="medicoSaludOcupacional.fuerza_muscular">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="MARCHA" v-model="medicoSaludOcupacional.marcha">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="PIEL Y FRANELAS" v-model="medicoSaludOcupacional.piel_faneras">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 class="px-2">
                                <v-textarea name="input-7-1" label="AMPLIACIÓN DE HALLAZGOS" hint="Hint text"
                                    v-model="medicoSaludOcupacional.ampliacion_hallazgos">
                                </v-textarea>
                            </v-flex>
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
            this.fetchSecretaria();
        },
        data() {
            return {
                motora: ['Derecha', 'Izquierda', 'Ambidiestro'],
                refiere: ['Refiere', 'No refiere'],
                si_no: ['SI', 'NO'],
                secretarias: [],
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
        watch: {
            "medicoSaludOcupacional.presionsistolica": function () {
                this.calcularMediaPresion();
            },
            "medicoSaludOcupacional.presiondiastolica": function () {
                this.calcularMediaPresion();
            }
        },

        methods: {
            totalIMC() {
                let p = Number(this.medicoSaludOcupacional.peso) / Math.pow(this.medicoSaludOcupacional.talla / 100, 2);
                this.medicoSaludOcupacional.imc = parseFloat(p).toFixed(1);
            },
            calcularMediaPresion() {
                this.medicoSaludOcupacional.presionarterialmedia =
                    (parseInt(this.medicoSaludOcupacional.presiondiastolica) * 2 +
                        parseInt(this.medicoSaludOcupacional.presionsistolica)) /
                    3;
                this.medicoSaludOcupacional.presionarterialmedia = Number.parseFloat(
                    this.medicoSaludOcupacional.presionarterialmedia
                ).toFixed(1);
            },
            saveOcupacional() {
                let clasificacionPresion = "Normal";

                if (
                    this.medicoSaludOcupacional.presionsistolica > 180 ||
                    this.medicoSaludOcupacional.presiondiastolica > 120
                ) {
                    clasificacionPresion = "CRISIS DE HIPERTENSION";
                } else if (
                    this.medicoSaludOcupacional.presionsistolica >= 140 ||
                    this.medicoSaludOcupacional.presiondiastolica >= 90
                ) {
                    clasificacionPresion = "PRESION ARTERIAL ALTA (NIVEL 2)";
                } else if (
                    this.medicoSaludOcupacional.presionsistolica >= 130 ||
                    this.medicoSaludOcupacional.presiondiastolica >= 80
                ) {
                    clasificacionPresion = "PRESION ARTERIAL ALTA (NIVEL 1)";
                } else if (
                    this.medicoSaludOcupacional.presionsistolica >= 120 &&
                    this.medicoSaludOcupacional.presiondiastolica < 80
                ) {
                    clasificacionPresion = "ELEVADA";
                } else if (
                    this.medicoSaludOcupacional.presionsistolica < 120 &&
                    this.medicoSaludOcupacional.presiondiastolica < 80
                ) {
                    clasificacionPresion = "NORMAL";
                }
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
                if (this.medicoSaludOcupacional.condiciones_generales == null) {
                    this.medicoSaludOcupacional.condiciones_generales = 'Normal'
                }
                if (this.medicoSaludOcupacional.cabeza == null) {
                    this.medicoSaludOcupacional.cabeza = 'Normal'
                }
                if (this.medicoSaludOcupacional.ojos == null) {
                    this.medicoSaludOcupacional.ojos = 'Normal'
                }

                if (this.medicoSaludOcupacional.fondo_de_ojos == null) {
                    this.medicoSaludOcupacional.fondo_de_ojos = 'Normal'
                }

                if (this.medicoSaludOcupacional.oidos == null) {
                    this.medicoSaludOcupacional.oidos = 'Normal'
                }

                if (this.medicoSaludOcupacional.otoscopia == null) {
                    this.medicoSaludOcupacional.otoscopia = 'Normal'
                }

                if (this.medicoSaludOcupacional.nariz == null) {
                    this.medicoSaludOcupacional.nariz = 'Normal'
                }

                if (this.medicoSaludOcupacional.boca == null) {
                    this.medicoSaludOcupacional.boca = 'Normal'
                }

                if (this.medicoSaludOcupacional.dentadura == null) {
                    this.medicoSaludOcupacional.dentadura = 'Normal'
                }

                if (this.medicoSaludOcupacional.faringe == null) {
                    this.medicoSaludOcupacional.faringe = 'Normal'
                }

                if (this.medicoSaludOcupacional.cuello == null) {
                    this.medicoSaludOcupacional.cuello = 'Normal'
                }

                if (this.medicoSaludOcupacional.mamas == null) {
                    this.medicoSaludOcupacional.mamas = 'Normal'
                }

                if (this.medicoSaludOcupacional.torax == null) {
                    this.medicoSaludOcupacional.torax = 'Normal'
                }

                if (this.medicoSaludOcupacional.corazon == null) {
                    this.medicoSaludOcupacional.corazon = 'Normal'
                }

                if (this.medicoSaludOcupacional.pulmones == null) {
                    this.medicoSaludOcupacional.pulmones = 'Normal'
                }

                if (this.medicoSaludOcupacional.columna == null) {
                    this.medicoSaludOcupacional.columna = 'Normal'
                }

                if (this.medicoSaludOcupacional.abdomen == null) {
                    this.medicoSaludOcupacional.abdomen = 'Normal'
                }

                if (this.medicoSaludOcupacional.genitales_externos == null) {
                    this.medicoSaludOcupacional.genitales_externos = 'Normal'
                }

                if (this.medicoSaludOcupacional.miembros_sup == null) {
                    this.medicoSaludOcupacional.miembros_sup = 'Normal'
                }

                if (this.medicoSaludOcupacional.miembros_inf == null) {
                    this.medicoSaludOcupacional.miembros_inf = 'Normal'
                }

                if (this.medicoSaludOcupacional.reflejos == null) {
                    this.medicoSaludOcupacional.reflejos = 'Normal'
                }

                if (this.medicoSaludOcupacional.motilidad == null) {
                    this.medicoSaludOcupacional.motilidad = 'Normal'
                }

                if (this.medicoSaludOcupacional.fuerza_muscular == null) {
                    this.medicoSaludOcupacional.fuerza_muscular = 'Normal'
                }

                if (this.medicoSaludOcupacional.marcha == null) {
                    this.medicoSaludOcupacional.marcha = 'Normal'
                }

                if (this.medicoSaludOcupacional.piel_faneras == null) {
                    this.medicoSaludOcupacional.piel_faneras = 'Normal'
                }

                if (this.medicoSaludOcupacional.ampliacion_hallazgos == null) {
                    this.medicoSaludOcupacional.ampliacion_hallazgos = 'Normal'                
                }

            },
            fetchSecretaria() {
                axios.get('/api/municipio/secretaria')
                    .then(res => {
                        this.secretarias = res.data
                    })
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
