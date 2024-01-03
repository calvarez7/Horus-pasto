<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-layout row wrap>
                    <v-flex xs12 sm12 md12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Analisis y plan de manejo</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea solo name="input-7-4" v-model="planesdeManejo.Planmanejo" label="">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Recomendaciones</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea solo name="input-7-4" v-model="planesdeManejo.Recomendaciones"
                            label="Recomendaciones"></v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12 v-show="datosCita.Tipo_agenda == '23'">
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Curso profiláctico y asesoria en
                                interrupción voluntaria del embarazo</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6 md6 v-show="datosCita.Tipo_agenda == '23'">
                        <v-select v-model="planesdeManejo.cursoprofilactico" :items="sino"
                            label="¿Recibio usted el curso profiláctico?:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6 v-show="datosCita.Tipo_agenda == '23'">
                        <v-select v-model="planesdeManejo.asesoriaive" :items="sino"
                            label="¿Recibio usted asesoria en interrupción voluntaria del embarazo:">
                        </v-select>
                    </v-flex>

                    <v-flex xs12 sm12 md12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Finalidad y destino del
                                paciente</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm12 md4>
                        <v-autocomplete :items="destinoPaciente" label="Destino del paciente" append-icon="search"
                            v-model="planesdeManejo.Destinopaciente"></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm4 v-show="planesdeManejo.Destinopaciente == 'Hospitalización (Remision)'">
                        <vAutocomplete :items="especialidades" label="Especialidad:"
                            no-planesdeManejo-text="No se encuentra especialidad con ese nombre"
                            v-model="planesdeManejo.Especialidad" />
                    </v-flex>
                    <v-flex xs12 sm4>
                        <v-autocomplete :items="finalidad" label="Finalidad" append-icon="search"
                            v-model="planesdeManejo.Finalidad"></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm4>
                        <v-autocomplete :items="externa" label="Consulta Externa" item-value="value" item-text="nombre"
                            item-label="Consulta Externa" append-icon="search" v-model="planesdeManejo.consultaExterna">
                        </v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm4 md12>
                        <v-btn color="success" round @click="guardarPlanManejo()">Guardar Plan de Manejo</v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-form>
        <v-dialog v-model="preloadHistoria" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchConducta();
        },
        data() {
            return {
                preloadHistoria: false,
                planesdeManejo: {
                    Planmanejo: '',
                    Recomendaciones: '',
                    Destinopaciente: '',
                    Finalidad: '',
                    Especialidad: '',
                    cursoprofilactico: '',
                    asesoriaive: '',
                    externa:'',
                },
                sino: ['Si', 'No'],
                externa: [{
                        value: '01',
                        nombre: 'Accidente de trabajo'
                    },
                    {
                        value: '02',
                        nombre: 'Accidente de tránsito'
                    },
                    {
                        value: '03',
                        nombre: 'Accidente rábico'
                    },
                    {
                        value: '04',
                        nombre: 'Accidente ofídico'
                    },
                    {
                        value: '05',
                        nombre: 'Otro tipo de accidente'
                    },
                    {
                        value: '06',
                        nombre: 'Evento catastrófico'
                    },
                    {
                        value: '07',
                        nombre: 'Lesión por agresión'
                    },
                    {
                        value: '08',
                        nombre: 'Lesión auto inflingida'
                    },
                    {
                        value: '09',
                        nombre: 'Sospecha de maltrato físico'
                    },
                    {
                        value: '10',
                        nombre: 'Sospecha de abuso sexual'
                    },
                    {
                        value: '11',
                        nombre: 'Sospecha de violencia sexual'
                    },
                    {
                        value: '12',
                        nombre: 'Sospecha de maltrato emocional'
                    },
                    {
                        value: '13',
                        nombre: 'Enfermedad general'
                    },
                    {
                        value: '14',
                        nombre: 'Enfermedad profesional'
                    },
                    {
                        value: '15',
                        nombre: 'Otra'
                    },
                ],
                especialidades: [
                    'ALERGOLOGIA',
                    'ANESTESIOLOGIA',
                    'AUDIOLOGIA',
                    'BIOENERGETICA',
                    'CARDIOLOGIA',
                    'CIRUGIA BARIATRICA',
                    'CIRUGIA CARDIOVASCULAR',
                    'CIRUGIA GENERAL',
                    'CIRUGIA MAXILOFACIAL',
                    'CIRUGIA PLASTICA',
                    'COLOPROCTOLOGIA',
                    'COORDINACION MEDICA',
                    'DERMATOLOGIA',
                    'ENDOCRINOLOGIA',
                    'GINECOLOGIA Y OBSTETRICIA',
                    'HEMATOLOGIA',
                    'INFECTOLOGIA',
                    'MASTOLOGIA',
                    'MEDICINA DEL DEPORTE',
                    'MEDICINA DEL DOLOR',
                    'MEDICINA DEL TRABAJO',
                    'MEDICINA FAMILIAR',
                    'MEDICINA FISICA Y REHABILITACION',
                    'MEDICINA GENERAL',
                    'MEDICINA INTERNA',
                    'NEFROLOGIA',
                    'NEUMOLOGIA',
                    'NEUROCIRUGIA',
                    'NEUROLOGIA',
                    'OFTALMOLOGIA',
                    'ONCOLOGIA CLINICA',
                    'ORTOPEDIA Y TRAUMATOLOGIA',
                    'OTORRINOLARINGOLOGIA',
                    'PEDIATRIA',
                    'PSIQUIATRIA',
                    'REUMATOLOGIA',
                    'SALUD FAMILIAR',
                    'TOXICOLOGIA CLINICA',
                    'UROLOGIA',
                ],
                destinoPaciente: [
                    'RIA Primera Infancia', 'RIA Infancia', 'RIA Adolescencia', 'RIA Juventud', 'RIA Adulto',
                    'RIA Riesgo Cardiovascular',
                    'RIA Adulto Mayor', 'RIA Materno Perinatal', 'Control', 'Interconsulta', 'Domiciliaria',
                    'Urgencias', 'Hospitalización (Remision)', 'Contrarreferencia (Anexo 10)'
                ],
                finalidad: [
                    'Atención del parto, del embarazo y posparto', 'Atención del recién nacido',
                    'Atención planificación familiar', 'Atención crecimiento y desarrollo',
                    'Atención joven Sano', 'Detección alteraciones del embarazo',
                    'Detección alteraciones del adulto', 'Detección alteraciones agudeza Visual',
                    'Enfermedad Profesional', 'Teleconsulta', 'No aplica'
                ]
            }
        },
        methods: {
            guardarPlanManejo() {
                if (!this.planesdeManejo.Planmanejo) {
                    this.$alerError("El campo plan de manejo es requerido!");
                    return
                } else if (!this.planesdeManejo.Recomendaciones) {
                    this.$alerError("El campo recomendación es requerido!");
                    return
                } else if (!this.planesdeManejo.Destinopaciente) {
                    this.$alerError("El campo destino del paciente es requerido!");
                    return
                } else if (!this.planesdeManejo.Finalidad) {
                    this.$alerError("El campo finalidad es requerido!");
                    return
                } else if (!this.planesdeManejo.consultaExterna) {
                    this.$alerError("El campo consulta externa es requerido!");
                    return
                }
                this.preloadHistoria = true
                this.planesdeManejo.paciente_id = this.datosCita.paciente_id;
                this.planesdeManejo.Citapaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/savePlanManejo', this.planesdeManejo)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                        this.preloadHistoria = false
                    })
            },
            fetchConducta() {
                this.preloadHistoria = true
                axios.get(`/api/historia/fetchConducta/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.planesdeManejo = res.data;
                        this.preloadHistoria = false
                    });
            }
        }
    }

</script>
