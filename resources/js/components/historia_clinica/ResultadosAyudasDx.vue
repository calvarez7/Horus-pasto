<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                <span class="title layout justify-center font-weight-light">Resultados ayudas diagnosticas</span>
            </v-card-title>
            <v-container fluid grid-list-xl>
                <v-layout wrap align-center>
                    <v-flex xs12 sm12 md2>
                        <v-autocomplete v-model="resultadosDiagnosticos.Ayuda_Diagnostica" :items="tipoDx"
                            label="Ayudas Diagnosticas" @click="clearPresLab()"></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm12 md3>
                        <v-textarea name="input-7-1" v-model="resultadosDiagnosticos.resultado_dx"
                            v-if="resultadosDiagnosticos.Ayuda_Diagnostica !='Mamografia' & resultadosDiagnosticos.Ayuda_Diagnostica !='Citologia Cervico Vaginal (CCV)' "
                            label="Resultado">
                        </v-textarea>
                        <v-select v-model="resultadosDiagnosticos.resultado_dx"
                            v-if="resultadosDiagnosticos.Ayuda_Diagnostica=='Mamografia'" :items="mamografia"
                            label="Resultado"></v-select>
                        <v-select v-model="resultadosDiagnosticos.calidad_ccv"
                            v-if="resultadosDiagnosticos.Ayuda_Diagnostica=='Citologia Cervico Vaginal (CCV)'"
                            :items="muestraccv" label="Calidad de la muestra CCV"></v-select>
                        <v-select v-model="resultadosDiagnosticos.microorganismos_ccv"
                            v-if="resultadosDiagnosticos.Ayuda_Diagnostica=='Citologia Cervico Vaginal (CCV)'"
                            :items="microorganismosccv" label="Microorganismos en CCV"></v-select>
                        <v-textarea name="input-7-1" v-model="resultadosDiagnosticos.otros_microorganismosccv"
                            v-if="resultadosDiagnosticos.microorganismos_ccv=='Otros'">
                        </v-textarea>
                        <v-select v-model="resultadosDiagnosticos.otros_neoplasicos"
                            v-if="resultadosDiagnosticos.Ayuda_Diagnostica=='Citologia Cervico Vaginal (CCV)'"
                            :items="otrosneoplasicos" label="Otros hallazgos no neoplasicos en la CCV">
                        </v-select>
                        <v-select v-model="resultadosDiagnosticos.anormalidades_celulasescamosas"
                            v-if="resultadosDiagnosticos.Ayuda_Diagnostica=='Citologia Cervico Vaginal (CCV)'"
                            :items="celulasescamosas" label="Anormalidades en celulas escamosas en CCV">
                        </v-select>
                        <v-select v-model="resultadosDiagnosticos.anormalidades_celulasgalndulares"
                            v-if="resultadosDiagnosticos.Ayuda_Diagnostica=='Citologia Cervico Vaginal (CCV)'"
                            :items="celulasgalndulares" label="Anormalidades en celulas glandulares">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm12 md2>
                        <v-text-field type="date" v-model="resultadosDiagnosticos.fecha_ayudadx"
                            label="Fecha de Ayuda Diagnosticas">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm12 md1>
                        <v-btn fab dark color="success" @click="saveAyudasDx()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-flex xs12 sm12 md12>
                <v-data-table :items="fetchAyudasDxPaciente" :headers="headerAyudasDx" class="elevation-1">
                    <template v-slot:items="props">
                        <td class="text-xs">{{props.item.Ayuda_Diagnostica?props.item.Ayuda_Diagnostica: ''}}</td>
                        <td class="text-xs">{{props.item.resultado_dx?props.item.resultado_dx: ''}}</td>
                        <td class="text-xs">{{props.item.calidad_ccv?props.item.calidad_ccv: ''}}</td>
                        <td class="text-xs">{{props.item.microorganismos_ccv?props.item.microorganismos_ccv: ''}}</td>
                        <td class="text-xs">
                            {{props.item.otros_microorganismosccv?props.item.otros_microorganismosccv: ''}}</td>
                        <td class="text-xs">{{props.item.otros_neoplasicos?props.item.otros_neoplasicos: ''}}</td>
                        <td class="text-xs">
                            {{props.item.anormalidades_celulasescamosas?props.item.anormalidades_celulasescamosas: ''}}
                        </td>
                        <td class="text-xs">
                            {{props.item.anormalidades_celulasgalndulares?props.item.anormalidades_celulasgalndulares: ''}}
                        </td>
                        <td class="text-xs">{{props.item.fecha_ayudadx?props.item.fecha_ayudadx: ''}}</td>
                        <td class="text-xs">{{props.item.name?props.item.name: ''}}</td>
                        <td class="text-xs">{{props.item.created_at?props.item.created_at: ''}}</td>
                    </template>
                </v-data-table>
            </v-flex>
            <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchAyudasDx()
        },
        data() {
            return {
                resultadosDiagnosticos: {
                    Ayuda_Diagnostica: '',
                    resultado_dx: '',
                    calidad_ccv: '',
                    microorganismos_ccv: '',
                    otros_microorganismosccv: '',
                    otros_neoplasicos: '',
                    anormalidades_celulasescamosas: '',
                    anormalidades_celulasgalndulares: '',
                    fecha_ayudadx: ''
                },
                tipoDx: ['Tomografia', 'Resonancia', 'Ecocardiograma', 'Electrocardiograma  (EKG)', 'Ecografia Abdomen',
                    'Endoscopia Digestiva Superior', 'Colonoscopia', 'Radiografias ',
                    'Ecografia obstétrica Trasvaginal', 'Ecografia obstétrica transabdominal',
                    'Ecografia de Tejidos Blandos', 'Ecografia Trasfontanelar', 'Ecografia Mamaria',
                    'Ecografia Testicular', 'Ecografia de Pene', 'Ecografia de Prostata', 'Mamografia',
                    'Biopsia de Mama', 'Citologia Cervico Vaginal (CCV)', 'Biopsia de Cervix', 'Colposcopia',
                    'ADN VPH'
                ],
                mamografia: ['Birard 0', 'Birard 1', 'Birard 2', 'Birard 3', 'Birard 4a', 'Birard 4b', 'Birard 4c',
                    'Birard 5', 'NA'
                ],
                muestraccv: ['Satisfactoria con  endocervicales', 'zona de transformación',
                    'Satisfactoria sin endocervicales', 'zona de transformación', 'Rechazada', 'Insatisfactoria'
                ],
                microorganismosccv: ['Trichonomas vaginalis', 'Hongos compatibles con Candida sr',
                    'Cambio en la flora vaginal, sugetivo de vaginosis bacteriana',
                    'Consistente en Actynomices sp.', 'Efectos citopaticos por virus del herpes simple', 'Otros',
                    'No hay presencia de microorganismos'
                ],
                otrosneoplasicos: ['Cambios celulares  reactivos asociados a inflamación',
                    'Cambios celulares reactivos asociados a radiación', 'Cambios celulares a DIU',
                    'Celulas glandulares post-histerectomia', 'Atrofia', 'Celulas endometriales', 'No'
                ],
                celulasescamosas: ['Atipias en células escamosas de significado indeterminado (ASC-US)',
                    'Atipias en células escamosas de significado indeterminado  LEI DE ALTO GRADO (ASC-H)',
                    'Lesión intraepitelial escamosa de Bajo grado LIE BG (NIC I)',
                    'Lesión intraepitelial escamosa de Alto grado LIE AG (NIC II, NIC III, Ca in situ)',
                    'Lesión intraepitelial escamosa Alto grado sospecha infiltración',
                    'Carcinoma Escamocelular Invasivo', 'No'
                ],
                celulasgalndulares: ['Celulas endocervicales atípicas sin ningún otro significado',
                    'Células endometriales atípicas sin ningún otro significado',
                    'Células endocervicales atípicas sospecha de malignidad',
                    'Células endometriales atípicas sospecha de malignidad',
                    'Células glandulares atípicas sospecha de malignidad', 'Adenocarcinoma endocervical in situ',
                    'Adenocarcinoma endocervicales', 'Adenocarninoma endometrial', 'Otras neoplasias', 'No'
                ],
                fetchAyudasDxPaciente: [],
                headerAyudasDx: [{
                        text: 'Ayuda_Diagnostica'
                    },
                    {
                        text: 'resultado_dx'
                    },
                    {
                        text: 'calidad_ccv'
                    },
                    {
                        text: 'microorganismos_ccv'
                    },
                    {
                        text: 'otros_microorganismosccv'
                    },
                    {
                        text: 'otros_neoplasicos'
                    },
                    {
                        text: 'anormalidades_celulasescamosas'
                    },
                    {
                        text: 'anormalidades_celulasgalndulares'
                    },
                    {
                        text: 'fecha_ayudadx'
                    },
                    {
                        text: 'medico'
                    },
                    {
                        text: 'F registro'
                    }
                ]
            }
        },
        methods: {
            saveAyudasDx() {
                this.resultadosDiagnosticos.paciente_id = this.datosCita.paciente_id;
                this.resultadosDiagnosticos.Citapaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveAyudasDx', this.resultadosDiagnosticos)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchAyudasDx();
                        this.cleardata();
                    })
            },
            fetchAyudasDx() {
                axios.get(`/api/historia/fetchAyudasDx/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.fetchAyudasDxPaciente = res.data;
                    });
            },
            clearPresLab() {
                this.resultadosDiagnosticos.resultado_dx = '',
                    this.resultadosDiagnosticos.calidad_ccv = '',
                    this.resultadosDiagnosticos.microorganismos_ccv = '',
                    this.resultadosDiagnosticos.otros_microorganismosccv = '',
                    this.resultadosDiagnosticos.otros_neoplasicos = '',
                    this.resultadosDiagnosticos.anormalidades_celulasescamosas = '',
                    this.resultadosDiagnosticos.anormalidades_celulasgalndulares = '',
                    this.resultadosDiagnosticos.fecha_ayudadx = ''
            },
            cleardata() {
                this.resultadosDiagnosticos.Ayuda_Diagnostica = '',
                    this.resultadosDiagnosticos.resultado_dx = '',
                    this.resultadosDiagnosticos.calidad_ccv = '',
                    this.resultadosDiagnosticos.microorganismos_ccv = '',
                    this.resultadosDiagnosticos.otros_microorganismosccv = '',
                    this.resultadosDiagnosticos.otros_neoplasicos = '',
                    this.resultadosDiagnosticos.anormalidades_celulasescamosas = '',
                    this.resultadosDiagnosticos.anormalidades_celulasgalndulares = '',
                    this.resultadosDiagnosticos.fecha_ayudadx = ''
            },
            guardarSeguir() {
                this.$emit('respuestaComponente')
            }
        }
    }

</script>
