<template>
    <v-card max-height="100%" class="mb-3">
        <v-card-title class="headline" style="color:white;background-color:#0074a6">
            <span class="title layout justify-center font-weight-light">Antecedentes Personales Patologicos</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container fluid grid-list-xl>
                            <v-layout wrap align-center>
                                <v-flex xs12 sm6 md4>
                                    <v-autocomplete :items="antPatologias" v-model="antecedentesPersonales.patologias"
                                        label="Patologias" @change="clearList()">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="antecedentesPersonales.patologias == 'Cancer'">
                                    <v-select v-model="antecedentesPersonales.tipo_cancer" :items="tipoCancer"
                                        label="Tipo">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="antecedentesPersonales.patologias == 'Asma'">
                                    <v-select v-model="antecedentesPersonales.tipo_asma" :items="tipoAsma" label="Tipo">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="antecedentesPersonales.patologias == 'Epoc'">
                                    <v-select v-model="antecedentesPersonales.tipo_epoc" :items="tipoEpo" label="Tipo">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="antecedentesPersonales.patologias == 'Bronquitis Cronica'">
                                    <v-select v-model="antecedentesPersonales.tipo_bronquitis_cronica" :items="tipoEpo"
                                        label="Tipo">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="antecedentesPersonales.patologias == 'Tuberculosis'">
                                    <v-select v-model="antecedentesPersonales.tipo_tuberculosis"
                                        :items="tipoTuberculosis" label="Tipo">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="antecedentesPersonales.patologias == 'Diabetes'">
                                    <v-select v-model="antecedentesPersonales.tipo_diabetes" :items="tipoDiabetes"
                                        label="Tipo">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2
                                    v-if="antecedentesPersonales.patologias == 'Enfermedad Renal Cronica'">
                                    <v-select v-model="antecedentesPersonales.tipo_enfermedad_renal_cronica"
                                        :items="tipoRenal" label="Tipo">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="antecedentesPersonales.patologias == 'Trastorno Mental'">
                                    <v-select v-model="antecedentesPersonales.tipo_trastorno_mental"
                                        :items="tipotrastorno" label="Tipo trastorno">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="antecedentesPersonales.patologias == 'Malnutricion'">
                                    <v-select v-model="antecedentesPersonales.tipo_malnutricion" :items="malNutricion"
                                        label="Tipo Malnutricion">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="antecedentesPersonales.patologias == 'Enfermedad Tiroidea'">
                                    <v-select v-model="antecedentesPersonales.tipo_enfermedad_tiroidea"
                                        :items="enfermedadTiroidea" label="Tipo Enfermedad Tiroidea">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2
                                    v-if="antecedentesPersonales.patologias == 'Enfermedades Trasmision Sexual'">
                                    <v-select v-model="antecedentesPersonales.tipo_enfermedades_trasmision_sexual"
                                        :items="tipoEnfermedadSexual" label="Tipo trasmision sexual">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2
                                    v-if="antecedentesPersonales.patologias == 'Otras Enfermedades Autoinmunes diferente a Artritis Reumatoidea'">
                                    <v-select v-model="antecedentesPersonales.tipo_enfermedades_autoinmunes"
                                        :items="autoinmunes" label="Tipo enfermedad autoinmune">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md3
                                    v-if="antecedentesPersonales.patologias == 'Cancer' && antecedentesPersonales.tipo_cancer == 'Otro cancer'">
                                    <v-textarea name="input-7-1" v-model="antecedentesPersonales.otro_patologia_cancer"
                                        label="Otra Cancer">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6 md3 v-if="antecedentesPersonales.patologias == 'Discapacidad'">
                                    <v-textarea name="input-7-1" v-model="antecedentesPersonales.cual_discapacidad"
                                        label="Cual discapacidad">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6 md3
                                    v-if="antecedentesPersonales.patologias == 'Otras Enfermedades Autoinmunes diferente a Artritis Reumatoidea'
                                    && antecedentesPersonales.tipo_enfermedades_autoinmunes == 'Otras Enfermedades Autoinmunes'">
                                    <v-textarea name="input-7-1"
                                        v-model="antecedentesPersonales.otras_enfermedades_autoinmunes"
                                        label="Otra enfermedad autoinmune">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6 md3
                                    v-if="antecedentesPersonales.patologias == 'Enfermedades Trasmision Sexual'
                                    && antecedentesPersonales.tipo_enfermedades_trasmision_sexual == 'Otra enfermedad Sexual'">
                                    <v-textarea name="input-7-1"
                                        v-model="antecedentesPersonales.otra_enfermedades_trasmision_sexual"
                                        label="Otra enfermedad sexual">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6 md3 v-if="antecedentesPersonales.patologias == 'Otro patologia'">
                                    <v-textarea name="input-7-1" v-model="antecedentesPersonales.otro_patologia"
                                        label="Otra patologia">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6 md3
                                    v-if="antecedentesPersonales.patologias == 'Enfermedad Genética o Congenita Multiple'">
                                    <v-textarea name="input-7-1"
                                        v-model="antecedentesPersonales.cual_enfermedad_genetica" label="Cual">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6 md3
                                    v-if="antecedentesPersonales.patologias == 'Otras Enfermedades Neurologicas'">
                                    <v-textarea name="input-7-1"
                                        v-model="antecedentesPersonales.descripcion_otras_enfermedades_neurologicas"
                                        label="Descripcion">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6 md3
                                    v-if="antecedentesPersonales.patologias == 'Enfermedad o Accidente Laboral'">
                                    <v-textarea name="input-7-1"
                                        v-model="antecedentesPersonales.descripcion_enfermedad_o_accidente_laboral"
                                        label="Descripcion">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="antecedentesPersonales.patologias != 'Bronquitis Cronica' &&
                                                           antecedentesPersonales.patologias != 'Otras Enfermedades Neurologicas' &&
                                                           antecedentesPersonales.patologias != 'Enfermedad o Accidente Laboral' &&
                                                           antecedentesPersonales.patologias != 'Enfermedad Musculo - Esqueleticas' &&
                                                           antecedentesPersonales.patologias != 'Dermatitis Atopica' &&
                                                           antecedentesPersonales.patologias != 'Trastorno Mental' &&
                                                           antecedentesPersonales.patologias != 'Discapacidad'
                                                          ">
                                    <v-text-field type="date" v-model="antecedentesPersonales.fecha_diagnostico"
                                        label="Fecha de Diagnostico">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md1>
                                    <v-btn small fab dark color="success" @click="guardarAntecedentesPersonales()">
                                        <v-icon dark>add</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-flex xs12 sm12 md12>
                            <v-data-table :items="allAntecedentesPaciente" hide-actions
                                :headers="headerOtrosAntecedentes">
                                <template v-slot:items="props">
                                    <td>{{props.item.patologias}}</td>
                                    <td>{{props.item.tipo_cancer}}</td>
                                    <td>{{props.item.tipo_asma}}</td>
                                    <td>{{props.item.tipo_epoc}}</td>
                                    <td>{{props.item.tipo_bronquitis_cronica}}</td>
                                    <td>{{props.item.tipo_tuberculosis}}</td>
                                    <td>{{props.item.tipo_diabetes}}</td>
                                    <td>{{props.item.tipo_enfermedad_renal_cronica}}</td>
                                    <td>{{props.item.tipo_trastorno_mental}}</td>
                                    <td>{{props.item.tipo_malnutricion}}</td>
                                    <td>{{props.item.tipo_enfermedad_tiroidea}}</td>
                                    <td>{{props.item.tipo_enfermedades_trasmision_sexual}}</td>
                                    <td>{{props.item.tipo_enfermedades_autoinmunes}}</td>
                                    <td>{{props.item.otro_patologia_cancer}}</td>
                                    <td>{{props.item.cual_discapacidad}}</td>
                                    <td>{{props.item.otras_enfermedades_autoinmunes}}</td>
                                    <td>{{props.item.otra_enfermedades_trasmision_sexual}}</td>
                                    <td>{{props.item.otro_patologia}}</td>
                                    <td>{{props.item.cual_enfermedad_genetica}}</td>
                                    <td>{{props.item.descripcion_otras_enfermedades_neurologicas}}</td>
                                    <td>{{props.item.descripcion_enfermedad_o_accidente_laboral}}</td>
                                    <td>{{props.item.fecha_diagnostico}}</td>
                                    <td>{{props.item.medico}}</td>
                                    <td class="text-xs">
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn text icon color="red" dark v-on="on">
                                                    <v-icon @click="deleteAntecedente(props.item.id)">
                                                        mdi-delete-forever
                                                    </v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Historial</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                            </v-data-table>
                        </v-flex>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-card-text>
        <v-checkbox class="title layout justify-center font-weight-light"
            v-model="otrasEnfermedadesdelPaciente.checkboxOtras_enfermedades" color="success"
            label="Otras Enfermedades Patologicas" value="1"></v-checkbox>
        <v-layout row wrap v-if="otrasEnfermedadesdelPaciente.checkboxOtras_enfermedades == true">
            <v-flex xs12>
                <v-container grid-list-xs>
                    <v-layout row wrap>
                        <v-container fluid grid-list-xl>
                            <v-layout wrap align-center>
                                <v-flex xs12 sm4 md4>
                                    <v-autocomplete v-model="otrasEnfermedadesdelPaciente.cie10_id" append-icon="search"
                                        :items="cieConcat" item-disabled="customDisabled" item-text="nombre"
                                        item-value="id" label="Diagnostico">
                                    </v-autocomplete>
                                </v-flex>

                                <v-flex xs12 sm6 md6>
                                    <v-textarea solo name="input-1-1" label="Escribir Descripcion Antecedente"
                                        v-model="otrasEnfermedadesdelPaciente.Descripcion">
                                    </v-textarea>
                                </v-flex>

                                <v-flex xs12 sm1 md1>
                                    <v-btn fab dark color="success" @click="guardarOtrosAntecedentePersonales()">
                                        <v-icon dark>add</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-layout>
                </v-container>
                <v-card-title primary-title>
                    <v-flex xs12 sm12 d-flex>
                        <v-data-table :items="otrosantePaciente" :headers="headerAntecedentePaciente" hide-actions
                            class="elevation-1">
                            <template v-slot:items="props">
                                <td>{{ props.item.created_at }}</td>
                                <td class="text-xs">{{ props.item.name }}</td>
                                <td class="text-xs">{{ props.item.cie }}</td>
                                <td class="text-xs">{{ props.item.Descripcion }}</td>
                                <td class="text-xs">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text icon color="red" dark v-on="on">
                                                <v-icon @click="deleteOtrosAntecedentes(props.item.id)">
                                                    mdi-delete-forever
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Historial</span>
                                    </v-tooltip>
                                </td>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-card-title>
            </v-flex>
        </v-layout>
        <v-dialog v-model="preloadHistoria" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
    </v-card>
</template>
<script>
    import Swal from 'sweetalert2';
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        data() {
            return {
                preloadHistoria: false,
                otrasEnfermedadesdelPaciente: {
                    cie10_id: '',
                    Descripcion: '',
                    citapaciente_id: '',
                    checkboxOtras_enfermedades: ''
                },
                otrosantePaciente: [],
                cie10s: [],
                headerAntecedentePaciente: [

                    {
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Descripcion'
                    }
                ],
                headerOtrosAntecedentes: [{
                        text: 'patologias'
                    },
                    {
                        text: 'T cancer'
                    },
                    {
                        text: 'T asma'
                    },
                    {
                        text: 'T epoc'
                    },
                    {
                        text: 'T bronquitis'
                    },
                    {
                        text: 'T tuberculosis'
                    },
                    {
                        text: 'T diabetes'
                    },
                    {
                        text: 'T renal'
                    },
                    {
                        text: 'T trastorno'
                    },
                    {
                        text: 'T malnutricion'
                    },
                    {
                        text: 'T tiroidea'
                    },
                    {
                        text: 'T trasmision sexual'
                    },
                    {
                        text: 'T autoinmunes'
                    },
                    {
                        text: 'Otro cancer'
                    },
                    {
                        text: 'Cual discapacidad'
                    },
                    {
                        text: 'Otras autoinmunes'
                    },
                    {
                        text: 'Otra trasmision_sexual'
                    },
                    {
                        text: 'Otro patologia'
                    },
                    {
                        text: 'cual enfermedad genetica'
                    },
                    {
                        text: 'Descripcion neurologicas'
                    },
                    {
                        text: 'Descripcion laboral'
                    },
                    {
                        text: 'fecha DX'
                    },
                ],
                antecedentesPatologicos: [
                    'COVID',
                    'Cardiopatia Isquemica',
                    'Hipertension Arterial',
                    'Enfermedad Cardiovascular',
                    'Enfermedad Cerebrovascular',
                    'Enfermedad Renal Cronica',
                    'Diabetes',
                    'Obesidad',
                    'Asma',
                    'Epoc',
                    'Anemia',
                    'Gastritis',
                    'Enfermedad Tiroidea',
                    'Dislipidemia',
                    'Artritis reumatoide',
                    'Enfermedades transmisión sexual',
                    'Cancer',
                    'VIH',
                    'Tuberculosis',
                    'Malnutricion',
                    'Discapacidad',
                    'Sindrome de Apnea Hipoapnea del sueño',
                    'Enfermedad Tromboembolica',
                    'Enfermedad Arterial Oclusiva Cronica',
                    'Enfermedades Trasmision Sexual',
                    'Otras Enfermedades Neurologicas',
                    'Convulsiones/Epilepsia',
                    'Bronquitis Cronica',
                    'Enfermedades Musculoesqueleticas',
                    'Enfermedad o Accidente Laboral',
                    'Enfermedad Genetica',
                    'Otras Enfermedades Autoinmunes diferente a Artritis Reumatoidea',
                    'Dermatitis Atopica',
                    'Anomalia congenita mayor o multiple',
                    'Patologia Perinatal o Neonatal significativa',
                    'Presenta algun evento de interes en Salud Publica',
                    'Niños con sospecha de problemas del desarrollo infantil',
                    'Familiar manifiesta alguna preocupacion referente a la salud, puericultura o cuidado extra-escolar',
                    'Problemas visuales',
                    'Alteraciones sensoriales',
                    'Trastorno Mental',
                    'Conducta Suicida',
                    'Victima de Maltrato Fisico y/o Psicologico',
                    'Victima Abuso/Violencia Sexual',
                    'Victima de Conflicto Armado',
                    'Hijo de madre con sospecha o diagnostico de depresion postparto',
                    'Hijo de padres con consumo de sustancias psicoactivas',
                    'Hijo de padres con enfermedad mental',
                    'Niño acompañante de mujer en privacion de la libertad en centro carcelario',
                ],
                antecedentesPersonales: {
                    patologias: '',
                    tipo_cancer: '',
                    tipo_asma: '',
                    tipo_epoc: '',
                    tipo_bronquitis_cronica: '',
                    tipo_tuberculosis: '',
                    tipo_diabetes: '',
                    tipo_enfermedad_renal_cronica: '',
                    tipo_trastorno_mental: '',
                    tipo_malnutricion: '',
                    tipo_enfermedad_tiroidea: '',
                    tipo_enfermedades_trasmision_sexual: '',
                    tipo_enfermedades_autoinmunes: '',
                    otro_patologia_cancer: '',
                    cual_discapacidad: '',
                    otras_enfermedades_autoinmunes: '',
                    otra_enfermedades_trasmision_sexual: '',
                    otro_patologia: '',
                    cual_enfermedad_genetica: '',
                    descripcion_otras_enfermedades_neurologicas: '',
                    descripcion_enfermedad_o_accidente_laboral: '',
                    fecha_diagnostico: ''
                },
                tipoCancer: [
                    'Ca de Mama',
                    'Ca de Prostata',
                    'Ca Gastrico',
                    'Ca de Colon',
                    'Ca Hematopoyetico',
                    'Ca de Cervix',
                    'Ca de Cabeza y  Cuello',
                    'Ca de Pancreas',
                    'Ca de Hepatico',
                    'Ca Renal',
                    'Ca Oseo',
                    'Piel',
                    'Pene',
                    'Otro cancer',
                ],
                tipoAsma: [
                    'Bien controlado',
                    'Parcialmente controlado',
                    'No controlado',
                ],
                tipoEpo: [
                    'Leve',
                    'Moderado',
                    'Grave',
                    'Muy grave'
                ],
                tipoDiabetes: [
                    'Tipo I',
                    'Tipo II',
                    'Gestacional'
                ],
                tipoTuberculosis: [
                    'Pulmonar',
                    'Osea',
                    'Meniengea',
                    'Extrapulmonar'
                ],
                tipoRenal: [
                    'Estadio 1',
                    'Estadio 2',
                    'Estadio 3A',
                    'Estadio 3B',
                    'Estadio 4',
                    'Estadio 5'
                ],
                tipotrastorno: [
                    'Ansiedad',
                    'Depresion',
                    'Esquezofrenia',
                    'Trastorno Afectivo Bipolar',
                    'Deficit de Atencion por Hiperactividad',
                    'Trastorno de la conducta alimentaria',
                    'Trastorno de la Adaptacion',
                    'Conducta Suicida'
                ],
                tipoEnfermedadSexual: [
                    'Sifilis',
                    'Blenorragia',
                    'Condilomas',
                    'VIH',
                    'Hepatitis B',
                    'Hepatitis C',
                    'Otra enfermedad Sexual'
                ],
                malNutricion: [
                    'Obesidad',
                    'Desnutricion'
                ],
                enfermedadTiroidea: [
                    'Hipertiroidismo',
                    'Hipotiroidismo',
                    'Hipotiroidismo Congenito'
                ],
                autoinmunes: [
                    'Lupus Eritematosos Sistemico (LES)',
                    'Esclerosis',
                    'Sjogren',
                    'Espondilitis anquilosante',
                    'Otras Enfermedades Autoinmunes',
                ],
                allAntecedentesPaciente: []
            }
        },
        created() {
            this.fetchCie10s();
            this.fetchAntecedentes();
            this.fetchOtrosAntecedentesPersonales()
        },
        computed: {
            cieConcat() {
                return this.cie10Array = this.cie10s.map(cie => {
                    return {
                        id: cie.id,
                        codigo: cie.Codigo_CIE10,
                        nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                        customDisabled: false
                    };
                });
            },
            antPatologias() {
                if (this.datosCita.ciclo_vital == '1ra Infancia') {
                    let deleteitem = ['Cardiopatia Isquémica', 'Epoc', 'Enfermedad Tromboembolica',
                        'Enfermedad Arterial Oclusiva Cronica', 'Enfermedades Trasmision Sexual',
                        'Enfermedad o Accidente Laboral',
                    ]
                    let array = this.antecedentesPatologicos.filter(item => !deleteitem.includes(item))
                    return array;
                } else if (this.datosCita.ciclo_vital == 'Infancia') {
                    let deleteitem = ['Cardiopatia Isquémica', 'Epoc', 'Enfermedad Tromboembolica',
                        'Enfermedad Arterial Oclusiva Cronica', 'Enfermedad o Accidente Laboral',
                    ]
                    let array = this.antecedentesPatologicos.filter(item => !deleteitem.includes(item))
                    return array;
                } else if (this.datosCita.ciclo_vital == 'Adolescencia') {
                    let deleteitem = [
                        'Epoc',
                        'Enfermedad Tromboembolica',
                        'Enfermedad o Accidente Laboral',
                        'Anomalia congénita mayor o multiple',
                        'Patologia Perinatal o Neonatal significativa',
                        'Niños con sospecha de problemas del desarrollo infantil',
                        'Familiar manifiesta alguna preocupacion referente a la salud, puericultura o cuidado extra-escolar',
                        'Alteraciones sensoriales',
                        'Hijo de madre con sospecha o diagnostico de depresion postparto',
                        'Hijo de padres con consumo de sustancias psicoactivas',
                        'Hijo de padres con enfermedad mental',
                        'Niño acompañante de mujer en privacion de la libertad en centro carcelario',
                    ]
                    let array = this.antecedentesPatologicos.filter(item => !deleteitem.includes(item))
                    return array;
                } else if (this.datosCita.ciclo_vital == 'Juventud') {
                    let deleteitem = [
                        'Anomalia congénita mayor o multiple',
                        'Patologia Perinatal o Neonatal significativa',
                        'Niños con sospecha de problemas del desarrollo infantil',
                        'Familiar manifiesta alguna preocupacion referente a la salud, puericultura o cuidado extra-escolar',
                        'Alteraciones sensoriales',
                        'Hijo de madre con sospecha o diagnostico de depresion postparto',
                        'Hijo de padres con consumo de sustancias psicoactivas',
                        'Hijo de padres con enfermedad mental',
                        'Niño acompañante de mujer en privacion de la libertad en centro carcelario',
                    ]
                    let array = this.antecedentesPatologicos.filter(item => !deleteitem.includes(item))
                    return array;
                } else if (this.datosCita.ciclo_vital == 'Adultez') {
                    let deleteitem = [
                        'Anomalia congénita mayor o multiple',
                        'Patologia Perinatal o Neonatal significativa',
                        'Niños con sospecha de problemas del desarrollo infantil',
                        'Familiar manifiesta alguna preocupacion referente a la salud, puericultura o cuidado extra-escolar',
                        'Alteraciones sensoriales',
                        'Hijo de madre con sospecha o diagnostico de depresion postparto',
                        'Hijo de padres con consumo de sustancias psicoactivas',
                        'Hijo de padres con enfermedad mental',
                        'Niño acompañante de mujer en privacion de la libertad en centro carcelario',
                    ]
                    let array = this.antecedentesPatologicos.filter(item => !deleteitem.includes(item))
                    return array;
                } else if (this.datosCita.ciclo_vital == 'Vejez') {
                    let deleteitem = [
                        'Anomalia congénita mayor o multiple',
                        'Patologia Perinatal o Neonatal significativa',
                        'Niños con sospecha de problemas del desarrollo infantil',
                        'Familiar manifiesta alguna preocupacion referente a la salud, puericultura o cuidado extra-escolar',
                        'Alteraciones sensoriales',
                        'Hijo de madre con sospecha o diagnostico de depresion postparto',
                        'Hijo de padres con consumo de sustancias psicoactivas',
                        'Hijo de padres con enfermedad mental',
                        'Niño acompañante de mujer en privacion de la libertad en centro carcelario',
                    ]
                    let array = this.antecedentesPatologicos.filter(item => !deleteitem.includes(item))
                    return array;
                }
            }
        },
        methods: {
            guardarAntecedentesPersonales() {
                if (!this.antecedentesPersonales.patologias) {
                    swal("La patologia es requerida!")
                    return;
                }
                this.preloadHistoria = true
                this.antecedentesPersonales.paciente_id = this.datosCita.paciente_id
                this.antecedentesPersonales.citapaciente_id = this.datosCita.cita_paciente_id
                axios.post("/api/historia/saveAntecedentesPersonal", this.antecedentesPersonales)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchAntecedentes();
                        this.clearAntecedentesPersonales();
                        this.preloadHistoria = false;
                    })
                    .catch(err => console.log(err));
            },

            fetchAntecedentes() {
                this.preloadHistoria = true
                const data = {
                    paciente_id: this.datosCita.paciente_id
                }
                axios.post("/api/historia/fetchAntecedentePersonal", data)
                    .then(res => {
                        this.allAntecedentesPaciente = res.data;
                        this.preloadHistoria = false
                    });
            },

            guardarOtrosAntecedentePersonales() {
                if (!this.otrasEnfermedadesdelPaciente.cie10_id) {
                    swal("Debe seleccionar un Dx!")
                    return;
                } else if (!this.otrasEnfermedadesdelPaciente.Descripcion) {
                    swal("La descripcion del Dx es requerida!")
                    return;
                }
                this.preloadHistoria = true
                this.otrasEnfermedadesdelPaciente.citapaciente_id = this.datosCita.cita_paciente_id
                this.otrasEnfermedadesdelPaciente.paciente_id = this.datosCita.paciente_id
                axios.post("/api/pacienteantecedente/otrosAntecedentes", this.otrasEnfermedadesdelPaciente)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchOtrosAntecedentesPersonales();
                        this.clearAntecedente();
                        this.preloadHistoria = false;
                    })
                    .catch(err => console.log(err));
            },

            fetchOtrosAntecedentesPersonales() {
                this.preloadHistoria = true
                const data = {
                    paciente_id: this.datosCita.paciente_id
                }
                axios.post("/api/pacienteantecedente/fetchOtrosAntecedentes", data)
                    .then(res => {
                        this.otrosantePaciente = res.data
                        this.otrasEnfermedadesdelPaciente.checkboxOtras_enfermedades = parseInt(this.otrosantePaciente[0].checkboxOtras_enfermedades)
                        this.preloadHistoria = false
                    });
            },

            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },

            guardarSeguir() {
                this.$emit('respuestaComponente')
            },

            clearAntecedente() {
                for (const key in this.otrasEnfermedadesdelPaciente) {
                    this.otrasEnfermedadesdelPaciente[key] = ''
                }
            },
            clearAntecedentesPersonales() {
                for (const key in this.antecedentesPersonales) {
                    this.antecedentesPersonales[key] = ''
                }
            },
            deleteAntecedente(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El antecedente será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/antePersonal/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchAntecedentes();
                });
            },
            deleteOtrosAntecedentes(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El antecedente será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/deleteOtrosAntece/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchOtrosAntecedentesPersonales();
                });
            },
            clearList() {
                this.antecedentesPersonales.tipo_cancer = '',
                    this.antecedentesPersonales.tipo_asma = '',
                    this.antecedentesPersonales.tipo_epoc = '',
                    this.antecedentesPersonales.tipo_bronquitis_cronica = '',
                    this.antecedentesPersonales.tipo_tuberculosis = '',
                    this.antecedentesPersonales.tipo_diabetes = '',
                    this.antecedentesPersonales.tipo_enfermedad_renal_cronica = '',
                    this.antecedentesPersonales.tipo_trastorno_mental = '',
                    this.antecedentesPersonales.tipo_malnutricion = '',
                    this.antecedentesPersonales.tipo_enfermedad_tiroidea = '',
                    this.antecedentesPersonales.tipo_enfermedades_trasmision_sexual = '',
                    this.antecedentesPersonales.tipo_enfermedades_autoinmunes = '',
                    this.antecedentesPersonales.otro_patologia_cancer = '',
                    this.antecedentesPersonales.cual_discapacidad = '',
                    this.antecedentesPersonales.otras_enfermedades_autoinmunes = '',
                    this.antecedentesPersonales.otra_enfermedades_trasmision_sexual = '',
                    this.antecedentesPersonales.otro_patologia = '',
                    this.antecedentesPersonales.cual_enfermedad_genetica = '',
                    this.antecedentesPersonales.descripcion_otras_enfermedades_neurologicas = '',
                    this.antecedentesPersonales.descripcion_enfermedad_o_accidente_laboral = '',
                    this.antecedentesPersonales.fecha_diagnostico = ''
            }
        }
    }

</script>
