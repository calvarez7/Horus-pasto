<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                <span class="title layout justify-center font-weight-light">Antecedentes familiares</span>
            </v-card-title>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container fluid grid-list-xl>
                            <v-layout wrap align-center>
                                <v-flex xs12 sm6 md3>
                                    <v-autocomplete :items="patologias" v-model="otrasantFamiliares.patologias"
                                        label="Patologias">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="otrasantFamiliares.patologias == 'Cancer'">
                                    <v-select v-model="otrasantFamiliares.tipo_cancer" :items="tipoCancer" label="Tipo">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="otrasantFamiliares.patologias == 'Trastorno Mental'">
                                    <v-select v-model="otrasantFamiliares.tipo_trastorno_mental" :items="tipotrastorno"
                                        label="Tipo trastorno">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2
                                    v-if="otrasantFamiliares.patologias == 'Enfermedades Trasmision Sexual'">
                                    <v-select v-model="otrasantFamiliares.tipo_transmision_sexual"
                                        :items="tipoEnfermedadSexual" label="Tipo trasmision sexual">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="otrasantFamiliares.patologias == 'Enfermedades Autoinmunes'">
                                    <v-select v-model="otrasantFamiliares.tipo_enfermedad_autoinmune"
                                        :items="autoinmunes" label="Tipo enfermedad autoinmune">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2
                                    v-if="otrasantFamiliares.patologias == 'Cancer' && otrasantFamiliares.tipo_cancer == 'Otro cancer'">
                                    <v-text-field v-model="otrasantFamiliares.otro_patologia_cancer"
                                        label="Otra Cancer">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md2
                                    v-if="otrasantFamiliares.patologias == 'Enfermedades Autoinmunes' && otrasantFamiliares.tipo_enfermedad_autoinmune == 'Otras enfermedad autoinmune'">
                                    <v-text-field v-model="otrasantFamiliares.otro_enfermedad_autoinmune"
                                        label="Otra enfermedad autoinmune">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md2
                                    v-if="otrasantFamiliares.patologias == 'Enfermedades Trasmision Sexual' && otrasantFamiliares.tipo_transmision_sexual == 'Otro enfermedad sexual'">
                                    <v-text-field v-model="otrasantFamiliares.otro_patologia_sexual"
                                        label="Otra enfermedad sexual">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="otrasantFamiliares.patologias == 'Otro patologia'">
                                    <v-text-field v-model="otrasantFamiliares.otro_patologia" label="Otra patologia">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-select v-model="otrasantFamiliares.parentesco" :items="parentescos"
                                        label="Parentesco">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-text-field type="number" min="0" v-model="otrasantFamiliares.fecha_diagnostico"
                                        label="Edad del DX">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-select v-model="otrasantFamiliares.fallecio" :items="sino" label="Fallecio">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-btn small fab dark color="success" @click="saveAntecedenteFamiliar()">
                                        <v-icon dark>add</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-card>
                            <v-card-title primary-title>
                                <v-flex xs12 sm12 md12>
                                    <v-data-table :items="antFamiliares" :headers="headerOtrosAntecedentes" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td class="text-xs">{{props.item.patologias}}</td>
                                            <td class="text-xs">
                                                {{props.item.otro_patologia?props.item.otro_patologia: ''}}</td>
                                            <td class="text-xs">{{props.item.tipo_cancer?props.item.tipo_cancer: ''}}
                                            </td>
                                            <td class="text-xs">
                                                {{props.item.otro_patologia_cancer?props.item.otro_patologia_cancer: ''}}
                                            </td>
                                            <td class="text-xs">
                                                {{props.item.tipo_trastorno_mental?props.item.tipo_trastorno_mental: ''}}
                                            </td>
                                            <td class="text-xs">
                                                {{props.item.tipo_transmision_sexual?props.item.tipo_transmision_sexual: ''}}
                                            </td>
                                            <td class="text-xs">
                                                {{props.item.otro_patologia_sexual?props.item.otro_patologia_sexual: ''}}
                                            </td>
                                            <td class="text-xs">
                                                {{props.item.tipo_enfermedad_autoinmune?props.item.tipo_enfermedad_autoinmune: ''}}
                                            </td>
                                            <td class="text-xs">
                                                {{props.item.otro_enfermedad_autoinmune?props.item.otro_enfermedad_autoinmune: ''}}
                                            </td>
                                            <td class="text-xs">{{props.item.parentesco}}</td>
                                            <td class="text-xs">{{props.item.fecha_diagnostico}}</td>
                                            <td class="text-xs">{{props.item.Fallecio}}</td>
                                            <td class="text-xs">{{props.item.name}}</td>
                                            <td class="text-xs">{{ props.item.created_at}}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-container fluid grid-list-xl>
                    <v-layout wrap align-center>
                        <v-flex xs12 sm3 d-flex>
                            <v-autocomplete v-model="antecedent.cie10_id" append-icon="search" :items="cieConcat"
                                item-disabled="customDisabled" item-text="nombre" item-value="id" label="Diagnóstico">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm3 d-flex>
                            <v-autocomplete label="Selecciona Familiar" :items="parentesco" item-text="Nombre"
                                item-value="id" v-model="antecedent.familiar">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm5 d-flex>
                            <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                v-model="antecedent.descripcion">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs12 sm1 d-flex>
                            <v-btn fab dark color="success" @click="guardarAntecedentefamiliar()">
                                <v-icon dark>add</v-icon>
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-layout>
            <v-card>
                <v-card-title primary-title>
                    <v-flex xs12 sm12 d-flex>
                        <v-data-table :items="antecedenteFami" :headers="familiare" hide-actions class="elevation-1">
                            <template v-slot:items="props">
                                <td>{{ props.item.created_at }}</td>
                                <td class="text-xs">{{ props.item.name }}</td>
                                <td class="text-xs">{{ props.item.cie }}</td>
                                <td class="text-xs">{{ props.item.familiar }}</td>
                                <td class="text-xs">{{ props.item.Descripcion }}</td>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-card-title>
            </v-card>
            <v-dialog v-model="preload_datos" persistent width="300">
                <v-card color="primary" dark>
                    <v-card-text>
                        Estamos procesando su información
                        <v-progress-linear indeterminate color="white" class="mb-0">
                        </v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-container>
        <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
    </v-form>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchAntecedenteFamiliar();
            this.fetchCie10s();
            this.fetchParentesco();
            this.listarAntecedenteFamiliar();
        },
        data() {
            return {
                preload_datos: false,
                otrasantFamiliares: {
                    patologias: '',
                    tipo_cancer: '',
                    tipo_trastorno_mental: '',
                    tipo_transmision_sexual: '',
                    tipo_enfermedad_autoinmune: '',
                    otro_patologia_cancer: '',
                    otro_enfermedad_autoinmune: '',
                    otro_patologia_sexual: '',
                    otro_patologia: '',
                    parentesco: '',
                    fallecio: '',
                    fecha_diagnostico: 0
                },
                otrasAntecedentesF: [],
                antecedent: [],
                antecedenteFami: [],
                headerOtrosAntecedentes: [{
                        text: 'patologias',
                    },
                    {
                        text: 'otro patologia',
                    },
                    {
                        text: 'cancer',
                    },
                    {
                        text: 'otro cancer',
                    },
                    {
                        text: 'trastorno',
                    },
                    {
                        text: 'sexual',
                    },
                    {
                        text: 'otro sexual',
                    },
                    {
                        text: 'autoinmune',
                    },
                    {
                        text: 'otro autoinmune',
                    },
                    {
                        text: 'parentesco',
                    },
                    {
                        text: 'edad dx',
                    },
                    {
                        text: 'Fallecio',
                    },
                    {
                        text: 'Medico',
                    },
                    {
                        text: 'fecha',
                    },
                ],
                sino: ['Si', 'No'],
                tipoCancer: [
                    'Ca de Mama',
                    'Ca de Prostata',
                    'Ca Gastrico',
                    'Ca de Colon',
                    'Ca Hematopoyetico ( Leucemia, Linfoma y Mieloma)',
                    'Ca de Cervix',
                    'Ca de Cabeza y  Cuello ( Tiroides, Lengua, Paladr, Paratiroides, Ocular)',
                    'Ca de Pancreas',
                    'Ca de Hepatico',
                    'Ca Renal',
                    'Ca Oseo',
                    'Piel',
                    'Pene',
                    'Otro cancer'
                ],
                tipoEnfermedadSexual: [
                    'Sifilis',
                    'Blenorragia',
                    'Clamidia',
                    'VIH',
                    'Otro enfermedad sexual'
                ],
                neoplasias: ['Mama', 'Prostata', 'Pulmón', 'Gastrointestinales', 'Cervicouterino', 'Piel', 'Otros',
                    'No antecedentes'
                ],
                trastorno: ['Ansiedad', 'Depresión', 'Esquizofrenia', 'Trastorno del ánimo bipolar',
                    'Déficit de atención por Hiperactividad', 'Trastorno de la conducta alimentaria',
                    'Trastorno de la Adaptación', 'No tiene'
                ],
                patologias: [
                    'Asma',
                    'Epoc',
                    'Diabetes ',
                    'Tuberculosis',
                    'Cancer',
                    'Hipertension Arterial',
                    'Enfermedad Renal Cronica',
                    'Enfermedad Cerebrovascular',
                    'Enfermedad Genética',
                    'Enfermedad Anemica',
                    'Enfermedades Trasmision Sexual',
                    'Sindrome Convulsivo',
                    'Enfermedades hereditarias ',
                    'Problemas del desarrollo Infantil ',
                    'Trastorno Mental',
                    'Discapacidad',
                    'Consumo Alcohol',
                    'Conducta Suicida',
                    'Consumo SPA',
                    'Duelo o muerte de una persona significativa',
                    'Divorcio de los padres',
                    'Problemas relaciones entre padres',
                    'Alteraciones del comportamiento en la familia',
                    'Antecedentes problemas desarrollo infantil en la familia',
                    'Victima de Maltrato Fisico y/o Psicologico',
                    'Victima Abuso/Violencia Sexual',
                    'Victima de Conflicto Armado',
                    'Enfermedad de Hansen',
                    'VIH',
                    'Enfermedad Cardiopatia Isquémica',
                    'Malnutricion',
                    'Dislipidemia',
                    'Hipotirodismo Congénito',
                    'Enfermedad Artritis reumatoide',
                    'Enfermedad Cardiovascular',
                    'Enfermedades Autoinmunes',
                    'Enfermedad Acido Peptica',
                    'Sindrome de Apnea Hipoapnea del sueño',
                    'Dermatitis Atopica',
                    'Neoplasias',
                    'Otro patologia'

                ],
                autoinmunes: [
                    'Lupus Eritematosos Sistemico (LES)',
                    'Artritis Reumatoidea (AR)',
                    'Esclerosis',
                    'Sjogren',
                    'Espondilitis anquilosante',
                    'Otras enfermedad autoinmune'
                ],
                sino: ['Si', 'No'],
                parentescos: ['Padre', 'Madre', 'hermano', 'Hermana', 'Tio', 'Tia', 'Abuelo', 'Abuela', 'Prima',
                    'Primo'
                ],
                tipocancer: ['Ca de Mama', 'Ca de Prostata', 'Ca Gastrico', 'Ca de Colon',
                    'Ca Hematopoyetico ( Leucemia, Linfoma y Mieloma)', 'Ca de Cervix',
                    'Ca de Cabeza y  Cuello ( Tiroides, Lengua, Paladr, Paratiroides, Ocular)', 'Ca de Pancreas',
                    'Ca de Hepatico', 'Ca Renal', 'Ca Oseo', 'Piel', 'Pene'
                ],
                tipotrastorno: ['Ansiedad', 'Depresion', 'Esquezofrenia', 'Trastorno Afectivo Bipolar',
                    'Deficit de Atencion por Hiperactividad ', 'Trastorno de la conducta alimentaria ',
                    'Trastorno de la Adaptacion ', 'Conducta Suicida',
                ],
                antFamiliares: [],
                antecedent: {
                    antecedente: '',
                    cie10_id: '',
                    descripcion: '',
                    cita_paciente: this.datosCita.cita_paciente_id,
                    familiar: ''
                },
                familiare: [],
                otrosantePaciente: [],
                otrasEnfermedadesdelPaciente: [],
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
                Cie10_id: '',
                cie10Array: [],
                cie10s: [],
                parentesco: []
            }
        },
        computed: {
            cieConcat() {
                if (this.cie10s !== undefined) {
                    return this.cie10Array = this.cie10s.map(cie => {
                        return {
                            id: cie.id,
                            codigo: cie.Codigo_CIE10,
                            nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                            customDisabled: false
                        };
                    });
                }
            }
        },
        methods: {
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },

            fetchParentesco() {
                axios.get("/api/parentesco/all").then(res => (this.parentesco = res.data));
            },

            saveAntecedenteFamiliar() {
                if (!this.otrasantFamiliares.patologias) {
                    this.$alerError('El campo patologias es requerido!')
                    return
                } else if (!this.otrasantFamiliares.parentesco) {
                    this.$alerError('El campo parentesco es requerido!')
                    return
                } else if (this.otrasantFamiliares.patologias == 'Cancer') {
                    if (!this.otrasantFamiliares.tipo_cancer) {
                        this.$alerError('El campo tipo cancer es requerido!')
                        return
                    }
                } else if (this.otrasantFamiliares.patologias == 'Enfermedades Trasmision Sexual') {
                    if (!this.otrasantFamiliares.tipo_transmision_sexual) {
                        this.$alerError('El campo tipo trasmision sexual es requerido!')
                        return
                    }
                } else if (this.otrasantFamiliares.patologias == 'Enfermedades Autoinmunes') {
                    if (!this.otrasantFamiliares.tipo_enfermedad_autoinmune) {
                        this.$alerError('El campo tipo enfermedad autoinmune es requerido!')
                        return
                    }
                } else if (this.otrasantFamiliares.patologias == 'Otro patologia') {
                    if (!this.otrasantFamiliares.otro_patologia) {
                        this.$alerError('El campo otra patologia es requerido!')
                        return
                    }
                }
                this.otrasantFamiliares.paciente_id = this.datosCita.paciente_id;
                this.otrasantFamiliares.citapaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveAntecedentesFamiliares', this.otrasantFamiliares)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.listarAntecedenteFamiliar()
                        this.clearDate()
                    })
            },

            listarAntecedenteFamiliar() {
                this.preload_datos = true
                axios.get(`/api/historia/fetcAntecedentesFamiliares/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.antFamiliares = res.data;
                        this.preload_datos = false
                    });
            },

            guardarSeguir() {
                this.$emit('respuestaComponente')
            },

            clearDate() {
                for (const prop of Object.getOwnPropertyNames(this.otrasantFamiliares)) {
                    this.otrasantFamiliares[prop] = null;
                }
            },

            fetchAntecedenteFamiliar() {
                axios.get('/api/parentescoantecedente/familiares/' + this.datosCita.cita_paciente_id)
                    .then(res => {
                        this.antecedenteFami = res.data
                    });
            },

            guardarAntecedentefamiliar() {
                axios.post('/api/parentescoantecedente/create', this.antecedent)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.clearAntecedente();
                        this.fetchAntecedenteFamiliar();
                    })
            },

            clearAntecedente() {
                this.antecedent.cie10_id = ''
                this.antecedent.descripcion = ''
                this.antecedent.familiar = ''
            },
        }
    }

</script>
