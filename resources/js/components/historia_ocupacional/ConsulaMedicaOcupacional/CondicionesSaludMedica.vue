<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md2 class="px-2">
                                <v-select :items="secretarias" item-text="nombre" item-value="nombre"
                                    v-model="medicoSaludOcupacional.nombre_de_la_empresa" label="Nombre de la empresa">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-select
                                    :items="['Coordinador(a)','Directivo(a)','Docente','Orientador(a)','Rector(a)']"
                                    label="cargo laboral" required v-model="medicoSaludOcupacional.cargo"></v-select>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-select :items="['< 1 año','1-a 5 años','5-10 años','10-15 años','> 15 años']"
                                    label="antiguedad" required v-model="medicoSaludOcupacional.antiguedad"></v-select>
                            </v-flex>
                            <v-flex xs12 md2 class="px-2">
                                <v-select :items="tipoExamen" v-model="medicoSaludOcupacional.tipoExamen"
                                    label="tipoExamen">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="factoresRiesgo" v-model="medicoSaludOcupacional.factoresRiesgo"
                                    label="factoresRiesgo"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.antecedentesenfermedad" :items="si_no"
                                    label="antecedentes de enfermedad">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="origenEnfermedades"
                                    v-model="medicoSaludOcupacional.origenEnfermedades" label="origenEnfermedades">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.antecedentedetrabajo" :items="si_no"
                                    label="antecedente de accidente de trabajo">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.antecedenteenfermedadlaboral" :items="si_no"
                                    label="antecedente de enfermedad laboral">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="enfermedadComun" v-model="medicoSaludOcupacional.enfermedadComun"
                                    label="enfermedadComun"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.antecedentesfamiliares" :items="si_no"
                                    label="antecedentes familiares">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaAntecedente"
                                    v-model="medicoSaludOcupacional.patologiaAntecedente" label="patologiaAntecedente">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="Corporal" v-model="medicoSaludOcupacional.masaCorporal"
                                    label="masaCorporal">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaOsteomuscular"
                                    v-model="medicoSaludOcupacional.patologiaOsteomuscular"
                                    label="patologiaOsteomuscular"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaCardiovascular"
                                    v-model="medicoSaludOcupacional.patologiaCardiovascular"
                                    label="patologiaCardiovascular"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaMetabolica"
                                    v-model="medicoSaludOcupacional.patologiaMetabolica" label="patologiaMetabolica">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="infecciososParasitaria"
                                    v-model="medicoSaludOcupacional.infecciososParasitaria"
                                    label="infecciososParasitaria"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaSangre" v-model="medicoSaludOcupacional.patologiaSangre"
                                    label="patologiaSangre"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="trastronosMentales"
                                    v-model="medicoSaludOcupacional.trastronosMentales" label="trastronosMentales">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaNervioso" v-model="medicoSaludOcupacional.patologiaNervioso"
                                    label="patologiaNervioso"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaOjo" v-model="medicoSaludOcupacional.patologiaOjo"
                                    label="patologiaOjo">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaOido" v-model="medicoSaludOcupacional.patologiaOido"
                                    label="patologiaOido">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaRespiratorio"
                                    v-model="medicoSaludOcupacional.patologiaRespiratorio"
                                    label="patologiaRespiratorio"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaDigestivo"
                                    v-model="medicoSaludOcupacional.patologiaDigestivo" label="patologiaDigestivo">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaPiel" v-model="medicoSaludOcupacional.patologiaPiel"
                                    label="patologiaPiel">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaUrinario" v-model="medicoSaludOcupacional.patologiaUrinario"
                                    label="patologiaUrinario"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="malformacionCongenitas"
                                    v-model="medicoSaludOcupacional.malformacionCongenitas"
                                    label="malformacionCongenitas"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="diagnosticoNeoplasicos"
                                    v-model="medicoSaludOcupacional.diagnosticos_neoplasicos"
                                    label="Diagnosticos Neoplasicos"></v-select>
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
                tipoExamen: ['Ingreso', 'Egreso', 'Periodico', 'Deportivo', 'Post incapacidad', 'Reintegro laboral', ],
                factoresRiesgo: [
                    'Riesgo Físico',
                    'Riesgo Químico',
                    'Riesgo Biológico',
                    'Riesgo de Carga Física y Psicosocial',
                    'Riesgo de Inseguridad',
                    'Riesgo del Medio Ambiente Físico y Social',
                    'Riesgo de Saneamiento Ambiental.',
                ],
                diagnosticoNeoplasicos: [
                    'Sin patologías',
                    'Lipomas',
                    'Neoplasias benignas',
                    'Neoplasias malignas',
                    'Neoplasias malignas de mamá y genitales',
                    'Miomatosis',
                ],
                origenEnfermedades: ['Origen Común', 'Origen Laboral'],
                enfermedadComun: [
                    'Patológicos',
                    'Psiquiatricos',
                    'Quirúrgicos',
                    'Alérgicos',
                    'Traumaticos',
                    'Farmacologicos',
                    'Ninguna',
                ],
                patologiaAntecedente: [
                    'Patología osteomuscular',
                    'Patología cardiovascular',
                    'Diagnósticos infecciosos o parasitaria',
                    'Diagnósticos neoplasicos',
                    'Patología  de  sangre y organos hematopoyeticos',
                    'Trastornos mentales y del comportamiento',
                    'Patología del sistema nervioso',
                    'Patología de ojo y sus anexos',
                    'Patología de oido y la apofisis mastoides',
                    'Patología del sistema respiratorio',
                    'Patología del aparato digestivo',
                    'Patología de la piel o tejido subcutaneo',
                    'Patología del aparato urinario',
                    'Malformaciones congénitas, deformidades y anomalías cromosomicas',
                ],
                Corporal: [
                    'Bajo',
                    'Normal',
                    'Sobrepeso',
                    'Obesidad Tipo I',
                    'Obesidad Tipo II',
                    'Obesidad Tipo III',
                ],
                patologiaOsteomuscular: [
                    'Sin Patología osteomuscular',
                    'Esguines y torceduras del tobillo',
                    'Costocondritis',
                    'Osteoporosis',
                    'Fibromialgía',
                    'Mialgía',
                    'Trastornos de los tejidos blandos',
                    'Neuralgía Y Neuritis No Especificada',
                    'Fascitis Plantar',
                    'Epicondilitis Lateral',
                    'Espolón calcaneo',
                    'Epicondilitis Media',
                    'Tendinitis rotuliana',
                    'Entesopatias del miembro inferior',
                    'Tendinitis De Bíceps',
                    'Síndrome Del Manguito Rotador',
                    'Tendinitis radial',
                    'Bursopatía',
                    'Sinovitis y tenosinovitis',
                    'Dedo en gatillo',
                    'Ganglión',
                    'Otros Trastornos De Los Músculos',
                    'Lumbago/Lumbalgía Mecánica',
                    'Cervicalgía',
                    'Dorsalgía',
                    'Otras Dorsopatias No Clasificadas En Otras',
                    'Trastornos De Los Discos Intervertebrales',
                    'Trastorno De Disco Lumbar con radiculopatia',
                    'Trastorno Del Disco Cervical',
                    'Osteocondrosis Espinal',
                    'Espondilosis',
                    'Escoliosis',
                    'Cifosis y lordosis',
                    'Trastornos articulares',
                    'trastornos aTrastornos De Los Ligamentos',
                    'Trastornos  De La Rodilla',
                    'Deformidad en varo/ en Flexión',
                    'Muñeca om pie caido',
                    'Pie Plano',
                    'Otras Patologías Óseas Sin Especificar',
                    'Hallux Valgus',
                    'Artrosis Degenerativa',
                    'Poliartrosis',
                    'Artralgias',
                    'Gota',
                    'Artritis',
                    'Síndrome Del Túnel Del Carpo',
                ],
                patologiaCardiovascular: [
                    'Sin Patología Vascular',
                    'Soplo Cardiaco No Especificado',
                    'Bradicardia, no especificada',
                    'Hipotensión',
                    'Linfadenopatía',
                    'Otros trastornos de la venas',
                    'Varices en otros sitios',
                    'Hemorroides',
                    'Varices Sin Ulceración',
                    'Telangiectasia  Hereditaria',
                    'otras enfermedades vasculares periféricas',
                    'Aterosclerosis',
                    'Aneurisma cerebral',
                    'Insuficiencia Cardiaca',
                    'Arritmia Cardiaca Sin Especificar',
                    'Taquicardia',
                    'Estenosis de la válvula aortica',
                    'pericarditis',
                    'Enfermedad cardiaca izquémica',
                    'trombosis',
                    'Otros Trastornos De La Conducción',
                    'Bloqueo De La Rama Izquierda Y Bloqueo Atrio Ventricular',
                    'Infarto Agudo De Miocardio',
                    'Angina de pecho',
                    'Hipertensión Arterial',
                    'Miocarditis',
                    'Otras cardiopatias reumáticas',
                ],
                patologiaMetabolica: [
                    'Sin patología metabólica',
                    'otros desordenes metabolicos',
                    ' Dislipidemia',
                    'Tejido adiposo localizado',
                    'Menopausia',
                    'Hiperprolactinemia',
                    'Hiperparatiroidismo',
                    'Hipoglicemia',
                    'Diabetes mellitus tipo II',
                    'Diabetes  mellitus tipo I',
                    'Otros desórdenes de la tiroides',
                    'Otros bocios no tóxicos',
                    'Tirotoxicosis(Hipertiroidismo)',
                    '(Hipotiroidismo)',

                ],
                infecciososParasitaria: [
                    'Sin patología Infecciosa o Parasitaria',
                    'otras patologias infecciosas',
                    'Virus del papiloma',
                    'Secuela Poliomelitis',
                    'Toxoplasmosis',
                    'enfermedad del chagas',
                    'VIH',
                    'Poliomelitis',
                    'Eszema herpetico',
                    'Sifilis',
                    'Dengue Clasico',
                    'Tuberculosis',
                    'Gastroentreritis',
                ],
                patologiaSangre: [
                    'Sin patología de la sangre y de los órganos hematopoyéticos',
                    'Sarcoidosis',
                    'leucemia y reacción leucemoide',
                    'Púrpura y hemorragias',
                    'Otros defectos de la coagulación',
                    'Hemofilia',
                    'Aplasia y otras anemias',
                    'Anemia hemolítica',
                    'Anemia nutricional',
                    'Síndrome anémico ',
                ],
                trastronosMentales: [
                    'Sin patologia de trastornos mentales',
                    'Temblor Esencial',
                    'Insomnio',
                    'Trastornos del sueño',
                    'Trastorno de la ingestion de alimentos',
                    'Trastorno de estrés grave y de adpatación',
                    'Trastorno obsesivo-compulsivo',
                    'Trastorno De Ansiedad(fóbicos)',
                    'Distimia',
                    'Trastorno Depresivo Recurrente',
                    'Depresión',
                    'Trastorno Bipolar Afectivo',
                    'Otros Trastornos Mentales De Comportamiento',
                    'Alcoholismo',
                    'Trastorno mental y de la personalidad',
                    'Demencia  sin especificar',
                    'Farmacodependencia',
                ],
                patologiaNervioso: [
                    'Sin Patología del Sistema Nervioso',
                    'Mareo',
                    'Páralisis de Bell',
                    'Encefalopatia no especificada',
                    'Paraplejía Sin Especificar',
                    'Hemiplejia sin especificar',
                    'Miastenia gravis y trastornos neuromusculares',
                    'Otros trastornos del sistema nervioso periferico',
                    'Polineuropatía',
                    'Neuralgía del trigemino',
                    'Sindrome de apnea-hipoapnea',
                    'Ataques de isquemia cerebral transitoria',
                    'Otros sindromes de cefalea',
                    'Migraña',
                    'Movimientos Extrapiramidales',
                    'Otras atrofias neuro-musculares',
                    'esclerosis multiple',
                    'TICS',
                    'Enfermedad de Parkinson',
                ],
                patologiaOjo: [
                    'Sin Patología del ojo y sus anexos',
                    'Trauma ocular',
                    'Queratosis Folicular',
                    'Nistagmo',
                    'Disminucion de l agudeza visual',
                    'Ceguera y baja Visión',
                    'Defectos del campo visual',
                    ' Ambliopía',
                    'Fotofobia',
                    'Otros trastornos  de refraccion',
                    'Presbicia',
                    'Astigmatismo',
                    ' Miopía',
                    'Hipermetropía',
                    'Estrabismo',
                    'Neuritis optica',
                    'Glaucoma',
                    'Trastosnos de la retina',
                    'Catarata, no especificada',
                    'Catarata senil',
                    'Queratocono',
                    'Pterigio',
                    'Conjuntivitis',
                    'Sindrome de sequedad Ocular',
                    'Blefaroptosis / Xantelesmas',
                    'Blefaritis',
                    'Chalazion(orzuelo)',
                ],
                patologiaOido: [
                    'Sin patologias del oido y de la apófisis mastoide',
                    'Acufenos',
                    'Otras perdidas de la audición / trastorno del oído',
                    'Otalgía',
                    'Presbiacusia',
                    'Hipoacusia neurosensorial',
                    'Pérdida Del Oído Conductivo Unilateral',
                    'Pérdida Del Oído Conductiva Bilateral',
                    'Vertigo  No Especificado',
                    'Otosclerosis',
                    'Enf. Meniere',
                    'Trastornos del sistema vestibular',
                    'Perforacion del timpano',
                    'Otitis',
                    'Cerumen',
                ],
                patologiaRespiratorio: [
                    'Sin Patología Del Sistema Respiratorio',
                    'Disfonia',
                    'Asma',
                    'EPOC',
                    'Nodulos/páralisis  de las cuerdas vocales y la laringe',
                    'Polipo de las cuerdas vocales',
                    'Pólipo Nasal',
                    'Rinitis Alérgica Y Vasomotora',
                    'Bronquitis  aguda',
                    'Bronconeumonía',
                    'Trastornos respiratorios (gripe-virus)',
                    'Laringitis',
                    'Faringitis Aguda',
                    'Amigdalitis',
                    'Sinusitis',
                ],
                patologiaDigestivo: [
                    'Sin Patologías Del Aparato Digestivo',
                    'Enf . del sistema digestivo',
                    'Anodoncia parcial / Alteracion en los dientes',
                    'Colelitiasis',
                    'Colecistitis',
                    'Otras enfermedades del higado',
                    'Cirrosis',
                    'Hernias',
                    'Estreñimiento',
                    'Diarrea/trastorno funcional',
                    'Síndrome Del Intestino Irritable',
                    'Dispepsia',
                    'Gastritis Aguda',
                    'Gastritis Crónica ',
                    'Ulcera gastrica',
                    'Enfermedades del esofago',
                ],
                patologiaPiel: [
                    'Sin patología de la piel y el tejido Subcutáneo',
                    'Ulcera/cicatriz de la piel',
                    'Lupus eritematoso',
                    'Cloasma',
                    'Vitiligio',
                    'Rosacea',
                    'Acne',
                    'Alopecia Areata',
                    'Eritema multiforme',
                    'Uña Golpeada',
                    'Urticaria',
                    'Psoriasis',
                    'Otras Dermatitis',
                    'Dermatitis Alérgica De Contacto ',
                    'Quiste pilomidal',
                    'Herpes zoster',
                    'Absceso Cutaneo',
                    'Pitiriasis versicolor/hipertricosis',
                ],
                patologiaUrinario: [
                    'Sin patología del aparato genitourinario',
                    'Embarazo Alto Riesgo',
                    'Embarazo Bajo Riesgo',
                    'Dismenorrea',
                    'Enf. Quistica del riñon',
                    'Diabetes en el embarazo',
                    'Hemorragia uterina anormal',
                    'Prolapso genital femenino',
                    'Endometriosis',
                    'Trastornos de la mama',
                    'Trastornos de la  Próstata',
                    'IVU',
                    'Trastornos de la vejiga/utero/vagina',
                    'Cistitis',
                    'Urolitiasis',
                    'Insuficiencia renal',
                    'Hidrocele',
                    'Ovario poliquístico',
                    'Ca. de próstata',
                ],
                malformacionCongenitas: [
                    'Sin malformaciones congénitas deformidades y anomalías cromosómicas',
                    'Labio de porino',
                    'Sindactilia',
                    'Ectropion congenito',
                    'Microtia',
                    'Defectos / ausencia de miembro No Especificado',
                ],
                epidemiologico: ['sistema de vigilancia epidemiológico osteomuscular',
                    'sistema de vigilancia epidemiológico voz', 'sistema de vigilancia epidemiológico psicosocial',
                    'no aplica'
                ],
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
