<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm6 class="px-2">
                                            <v-text-field label="Empresa"
                                                v-model="antecedenteOcupacional.antecedente_empresa">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 class="px-2">
                                            <v-text-field label="Cargo"
                                                v-model="antecedenteOcupacional.antecedente_cargo">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm1 class="px-2">
                                            <v-select :items="['x', '']" label="F" v-model="antecedenteOcupacional.f">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm1 class="px-2">
                                            <v-select :items="['x', '']" label="Q" v-model="antecedenteOcupacional.q">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm1 class="px-2">
                                            <v-select :items="['x', '']" label="B" v-model="antecedenteOcupacional.b">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm1 class="px-2">
                                            <v-select :items="['x', '']" label="Erg"
                                                v-model="antecedenteOcupacional.erg"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm1 class="px-2">
                                            <v-select :items="['x', '']" label="Psic"
                                                v-model="antecedenteOcupacional.psic"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm1 class="px-2">
                                            <v-select :items="['x', '']" label="Mec"
                                                v-model="antecedenteOcupacional.mec"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm1 class="px-2">
                                            <v-select :items="['x', '']" label="Elec"
                                                v-model="antecedenteOcupacional.elec"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm1 class="px-2">
                                            <v-select :items="['x', '']" label="Otro"
                                                v-model="antecedenteOcupacional.otro"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm2 class="px-2">
                                            <v-text-field label="Tiempo" v-model="antecedenteOcupacional.tiempo">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm2 class="px-2">
                                            <v-text-field label="Uso E.P.P" v-model="antecedenteOcupacional.uso_e_p_p">
                                            </v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout row wrap>
                                        <v-flex xs12 md12 class="px-2">
                                            <v-radio-group label="HA SUFRIDO ACCIDENTES DE TRABAJO?"
                                                v-model="antecedenteOcupacional.accidentes_trabajo" row>
                                                <v-radio label="Si" value="si" color="success"></v-radio>
                                                <v-radio label="No" value="no" color="red"></v-radio>
                                            </v-radio-group>
                                        </v-flex>
                                        <v-layout row wrap v-if="antecedenteOcupacional.accidentes_trabajo == 'si'">
                                            <v-flex xs12 sm2 class="px-2">
                                                <v-text-field label="fecha" type="date"
                                                    v-model="antecedenteOcupacional.fecha_accidentes">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 class="px-2">
                                                <v-text-field label="empresa"
                                                    v-model="antecedenteOcupacional.empresa_accidentes">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 class="px-2">
                                                <v-text-field label="causa" v-model="antecedenteOcupacional.causa">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm2 class="px-2">
                                                <v-text-field label="tipo_lesion"
                                                    v-model="antecedenteOcupacional.tipo_lesion">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 class="px-2">
                                                <v-text-field label="parte_afectada"
                                                    v-model="antecedenteOcupacional.parte_afectada">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm2 class="px-2">
                                                <v-text-field label="dias_incap"
                                                    v-model="antecedenteOcupacional.dias_incap">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 class="px-2">
                                                <v-text-field label="secuelas"
                                                    v-model="antecedenteOcupacional.secuelas">
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-layout>
                                    <v-layout row wrap>
                                        <v-flex xs12 md12 class="px-2">
                                            <v-radio-group label="HA SUFRIDO ENFERMEDADES PROFESIONALES?"
                                                v-model="antecedenteOcupacional.enfermedades_profesionales" row>
                                                <v-radio label="Si" value="SI" color="success"></v-radio>
                                                <v-radio label="No" value="NO" color="red"></v-radio>
                                            </v-radio-group>
                                        </v-flex>
                                        <v-layout row wrap
                                            v-if="antecedenteOcupacional.enfermedades_profesionales == 'SI'">
                                            <v-flex xs12 sm3 class="px-2">
                                                <v-text-field label="fecha" type="date"
                                                    v-model="antecedenteOcupacional.fecha">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 class="px-2">
                                                <v-text-field label="empresa" v-model="antecedenteOcupacional.empresa">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm5 class="px-2">
                                                <v-text-field label="diagnostico"
                                                    v-model="antecedenteOcupacional.diagnostico">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm2 class="px-2">
                                                <v-text-field label="reubicacion"
                                                    v-model="antecedenteOcupacional.reubicacion">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 class="px-2">
                                                <v-text-field label="indemnización"
                                                    v-model="antecedenteOcupacional.indemnizacion">
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-layout>
                                    <v-flex xs2 offset-xs5 offset-md2 offset-lg9>
                                        <v-btn round dark color="success" @click="saveAntecedentesOpucacionales()">
                                            <v-icon>add</v-icon> Agregar
                                        </v-btn>
                                    </v-flex>
                                </v-container>
                            </v-layout>
                        </v-container>
                    </v-card>
                    <v-card>
                        <v-data-table :headers="headers" :items="antOcupacional" class="elevation-1">
                            <template v-slot:items="props">
                                <td>{{ props.item.antecedente_empresa}}</td>
                                <td class="text-xs-right">{{ props.item.antecedente_cargo}}</td>
                                <td class="text-xs-right">{{ props.item.f }}</td>
                                <td class="text-xs-right">{{ props.item.q }}</td>
                                <td class="text-xs-right">{{ props.item.b }}</td>
                                <td class="text-xs-right">{{ props.item.erg }}</td>
                                <td class="text-xs-right">{{ props.item.psic }}</td>
                                <td class="text-xs-right">{{ props.item.mec }}</td>
                                <td class="text-xs-right">{{ props.item.elec }}</td>
                                <td class="text-xs-right">{{ props.item.otro }}</td>
                                <td class="text-xs-right">{{ props.item.tiempo }}</td>
                                <td class="text-xs-right">{{ props.item.uso_e_p_p }}</td>
                                <td class="text-xs-right">{{ props.item.accidentes_trabajo }}</td>
                                <td class="text-xs-right">{{ props.item.fecha_accidentes }}</td>
                                <td class="text-xs-right">{{ props.item.empresa_accidentes }}</td>
                                <td class="text-xs-right">{{ props.item.causa }}</td>
                                <td class="text-xs-right">{{ props.item.tipo_lesion }}</td>
                                <td class="text-xs-right">{{ props.item.parte_afectada }}</td>
                                <td class="text-xs-right">{{ props.item.dias_incap }}</td>
                                <td class="text-xs-right">{{ props.item.secuelas }}</td>
                                <td class="text-xs-right">{{ props.item.enfermedades_profesionales }}</td>
                                <td class="text-xs-right">{{ props.item.fecha }}</td>
                                <td class="text-xs-right">{{ props.item.empresa }}</td>
                                <td class="text-xs-right">{{ props.item.diagnostico }}</td>
                                <td class="text-xs-right">{{ props.item.reubicacion }}</td>
                                <td class="text-xs-right">{{ props.item.indemnizacion }}</td>
                            </template>
                            <template v-slot:pageText="props">
                                Lignes {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>
                <v-btn round dark color="success" @click="NextPage()">Siguiente</v-btn>
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
            this.fetchAntecedentesOpucacionales()
        },
        data() {
            return {
                headers: [{
                        text: 'empresa',
                        value: 'antecedente_empresa'
                    },{
                        text: 'antecedente_cargo',
                        value: 'antecedente_cargo'
                    },
                    {
                        text: 'f',
                        value: 'f'
                    },
                    {
                        text: 'q',
                        value: 'q'
                    },
                    {
                        text: 'b',
                        value: 'b'
                    },
                    {
                        text: 'erg',
                        value: 'erg'
                    },
                    {
                        text: 'psic',
                        value: 'psic'
                    },
                    {
                        text: 'mec',
                        value: 'mec'
                    },
                    {
                        text: 'elec',
                        value: 'elec'
                    },
                    {
                        text: 'otro',
                        value: 'otro'
                    },
                    {
                        text: 'tiempo',
                        value: 'tiempo'
                    },
                    {
                        text: 'uso_e_p_p',
                        value: 'uso_e_p_p'
                    },
                    {
                        text: 'accidentes_trabajo',
                        value: 'accidentes_trabajo'
                    },
                    {
                        text: 'fecha_accidentes',
                        value: 'fecha_accidentes'
                    },
                    {
                        text: 'empresa_accidentes',
                        value: 'empresa_accidentes'
                    },
                    {
                        text: 'causa',
                        value: 'causa'
                    },
                    {
                        text: 'tipo_lesion',
                        value: 'tipo_lesion'
                    },
                    {
                        text: 'parte_afectada',
                        value: 'parte_afectada'
                    },
                    {
                        text: 'dias_incap',
                        value: 'dias_incap'
                    },
                    {
                        text: 'secuelas',
                        value: 'secuelas'
                    },
                    {
                        text: 'enfermedades_profesionales',
                        value: 'enfermedades_profesionales'
                    },
                    {
                        text: 'fecha',
                        value: 'fecha'
                    },
                    {
                        text: 'empresa',
                        value: 'empresa'
                    },
                    {
                        text: 'diagnostico',
                        value: 'diagnostico'
                    },
                    {
                        text: 'reubicacion',
                        value: 'reubicacion'
                    },
                    {
                        text: 'indemnizacion',
                        value: 'indemnizacion'
                    }
                ],
                antecedenteOcupacional: {
                    antecedente_empresa: '',
                    antecedente_cargo: '',
                    f: '',
                    q: '',
                    b: '',
                    erg: '',
                    psic: '',
                    mec: '',
                    elec: '',
                    otro: '',
                    tiempo: '',
                    Uso_E_P_P: '',
                    accidentes_trabajo: '',
                    accidentes_trabajo: '',
                    fecha_accidentes: '',
                    empresa_accidentes: '',
                    causa: '',
                    tipo_lesion: '',
                    parte_afectada: '',
                    dias_incap: '',
                    secuelas: '',
                    enfermedades_profesionales: '',
                    enfermedades_profesionales: '',
                    fecha: '',
                    empresa: '',
                    diagnostico: '',
                    reubicacion: '',
                    indemnizacion: '',
                },
                antOcupacional: [],
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
            clearAntecedenteOcupacional() {
                for (const prop of Object.getOwnPropertyNames(this.antecedenteOcupacional)) {
                    this.antecedenteOcupacional[prop] = "";
                }
            },
            saveAntecedentesOpucacionales() {
                axios.post('/api/cita/saveAntecedentesOpucacionales/' + this.datosCita.cita_paciente_id, this
                        .antecedenteOcupacional)
                    .then((res) => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.clearAntecedenteOcupacional()
                        this.fetchAntecedentesOpucacionales()
                    })
            },

            fetchAntecedentesOpucacionales() {
                axios.post('/api/cita/getAntecedentesOpucacionales/' + this.datosCita.cita_paciente_id)
                    .then(res => {
                        if (res.data) {
                            this.antOcupacional = res.data
                        }
                    })
                    .catch(err => console.log(err.response));
            },
            NextPage() {
                this.$emit('respuestaComponente')
            }

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
