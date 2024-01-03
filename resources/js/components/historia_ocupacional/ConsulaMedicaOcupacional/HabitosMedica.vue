<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md2 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.fuma" :items="si_no" label="Fuma"></v-select>
                            </v-flex>
                            <v-flex xs12 md2 class="px-2" v-if="medicoSaludOcupacional.fuma == 'SI'">
                                <v-select v-model="medicoSaludOcupacional.anos_fumador" :items="anosF"
                                    label="Años de Fumador">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md2 class="px-2" v-if="medicoSaludOcupacional.fuma == 'SI'">
                                <v-select v-model="medicoSaludOcupacional.cigarrillos_al_dia" :items="cigarrillos"
                                    label="Cigarrillos al día"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.fumo" :items="si_no" label="Fumo"></v-select>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2" v-if="medicoSaludOcupacional.fumo == 'SI'">
                                <v-text-field label="Hace cuanto no fuma"
                                    v-model="medicoSaludOcupacional.hace_cuanto_no_fuma">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.alcohol" :items="si_no" label="Alcohol">
                                </v-select>
                            </v-flex>
                            <v-layout row wrap v-if="medicoSaludOcupacional.alcohol == 'SI'">
                                <v-flex xs12 sm3 class="px-2">
                                    <v-select v-model="medicoSaludOcupacional.frecuencia" :items="copas"
                                        label="Frecuencia">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm3 class="px-2">
                                    <v-select v-model="medicoSaludOcupacional.tipo" :items="tipos" label="Tipo">
                                    </v-select>
                                </v-flex>
                            </v-layout>
                            <v-flex xs12 sm3 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.sustancias_psico_activas" :items="si_no"
                                    label="Sustancias Psico Activas"></v-select>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2"
                                v-if="medicoSaludOcupacional.sustancias_psico_activas == 'SI'">
                                <v-text-field label="Cuál(es)" v-model="medicoSaludOcupacional.cuales"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.practica_deporte" :items="si_no"
                                    label="Practica Deporte o ejercicio?"></v-select>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2" v-if="medicoSaludOcupacional.practica_deporte == 'SI'">
                                <v-text-field label="Cuál" v-model="medicoSaludOcupacional.cual"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2" v-if="medicoSaludOcupacional.practica_deporte == 'SI'">
                                <v-select v-model="medicoSaludOcupacional.regularidad" :items="regular"
                                    label="Regularidad">
                                </v-select>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveOcupacional()">Guarar y Seguir</v-btn>
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
                regular: ['diario', '1-3 veces por semana', '1 vez a la semana', 'nunca'],
                tipos: ['RON', 'CERVEZA', 'WHISKY', 'VINO', 'AGUARDIENTE', 'OTROS'],
                copas: ['1-3 COPAS', '3-6 COPAS', 'HASTA EMBRIGARSE'],
                cigarrillos: ['1-5', '5-10', '>10'],
                anosF: ['<1', '1-5', '>5'],
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
