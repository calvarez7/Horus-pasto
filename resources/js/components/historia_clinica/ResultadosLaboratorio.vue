<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                <span class="title layout justify-center font-weight-light">Resultados de laboratorio</span>
            </v-card-title>
            <v-container fluid grid-list-xl>
                <v-layout wrap align-center>
                    <v-flex xs12 sm6 md3>
                        <v-autocomplete v-model="resultadosLaboratorio.laboratorio" :items="tipolab"
                            label="Laboratorios" @change="clearPresLab()"></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-text-field  v-if="resultadosLaboratorio.laboratorio=='Urocultivo'||
                                        resultadosLaboratorio.laboratorio=='Colesterol Total'||
                                        resultadosLaboratorio.laboratorio=='Trigliceridos'||
                                        resultadosLaboratorio.laboratorio=='HDL'||
                                        resultadosLaboratorio.laboratorio=='LDL Calculado'||
                                        resultadosLaboratorio.laboratorio=='LDL Serico'||
                                        resultadosLaboratorio.laboratorio=='Hemoglobina Glicosilada'||
                                        resultadosLaboratorio.laboratorio=='TSH'||
                                        resultadosLaboratorio.laboratorio=='ADN VPH'||
                                        resultadosLaboratorio.laboratorio=='Microalbuminuria'||
                                        resultadosLaboratorio.laboratorio=='Glucosa en Ayunas'||
                                        resultadosLaboratorio.laboratorio=='Citologia cervicouterina'||
                                        resultadosLaboratorio.laboratorio=='Uroanalisis'||
                                        resultadosLaboratorio.laboratorio=='Hemograma'||
                                        resultadosLaboratorio.laboratorio=='Ecografia obstétrica'||
                                        resultadosLaboratorio.laboratorio=='Hemoglobina'||
                                        resultadosLaboratorio.laboratorio=='Hematocrito'||
                                        resultadosLaboratorio.laboratorio=='Albumina'||
                                        resultadosLaboratorio.laboratorio=='Potasio'||
                                        resultadosLaboratorio.laboratorio=='Creatinina'||
                                        resultadosLaboratorio.laboratorio=='Relación Albumina / Creatinuria'||
                                        resultadosLaboratorio.laboratorio=='PTH'||
                                        resultadosLaboratorio.laboratorio=='Fosforo'||
                                        resultadosLaboratorio.laboratorio=='PSA'||
                                        resultadosLaboratorio.laboratorio=='IgG G Varicela:'||
                                        resultadosLaboratorio.laboratorio=='Prueba Rapida Treponemica'||
                                        resultadosLaboratorio.laboratorio=='Prueba Rapida HB'||
                                        resultadosLaboratorio.laboratorio=='VDRL'||
                                        resultadosLaboratorio.laboratorio=='Antigeno Hep B'||
                                        resultadosLaboratorio.laboratorio=='IgG Rubeola'||
                                        resultadosLaboratorio.laboratorio=='toxo IgG'||
                                        resultadosLaboratorio.laboratorio=='toxo IgM'||
                                        resultadosLaboratorio.laboratorio=='PTOG'||
                                        resultadosLaboratorio.laboratorio=='Colposcopia'||
                                        resultadosLaboratorio.laboratorio=='Prueba Rapida VIH'||
                                        resultadosLaboratorio.laboratorio=='Biopsia de Cervix'||
                                        resultadosLaboratorio.laboratorio=='Estreptococo B (sem 35 y 37 de Gestacion)'||
                                        resultadosLaboratorio.laboratorio=='Antigeno de Hepatitis C'||
                                        resultadosLaboratorio.laboratorio=='Creatinuria'"
                            v-model="resultadosLaboratorio.resultado_lab" label="Resultado">
                        </v-text-field>
                        <v-select v-model="resultadosLaboratorio.resultado_lab" v-if="resultadosLaboratorio.laboratorio=='VIH' ||
                                        resultadosLaboratorio.laboratorio=='CD4' ||
                                        resultadosLaboratorio.laboratorio=='CD8' ||
                                        resultadosLaboratorio.laboratorio=='CD3' ||
                                        resultadosLaboratorio.laboratorio=='Relacion CD4/CD8' ||
                                        resultadosLaboratorio.laboratorio=='Prueba de Embarazo'" :items="otroresultado"
                            label="Resultado">
                        </v-select>
                        <v-select v-model="resultadosLaboratorio.resultado_lab"
                            v-if="resultadosLaboratorio.laboratorio=='Hemoclasificacion'" :items="factor"
                            label="Resultado">
                        </v-select>
                        <v-select v-model="resultadosLaboratorio.factor_rh"
                            v-if="resultadosLaboratorio.laboratorio=='Hemoclasificacion'" :items="otroresultado"
                            label="Factor Rh">
                        </v-select>

                        <v-text-field v-if="resultadosLaboratorio.laboratorio=='Espirometria curva flujo - volumen'" readonly label="Cargar Archivo" @click='onPickFile' v-model='fileName' prepend-icon="attach_file" outline clearable @click:clear="fileName = null"></v-text-field>
                        <input type="file" style="display: none" ref="fileInput" accept="*/*" @change="onFilePicked">

                    </v-flex>
                    <v-flex xs12 sm6 md2>
                        <v-text-field type="date" v-model="resultadosLaboratorio.fecha_laboratorio"
                            label="Fecha de Laboratorio">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-btn small fab dark color="success" @click="saveLaboratorios()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-flex xs12 sm12 md12>
                <v-data-table :items="fetchLaboratoriosPaciente" :headers="headerresultadosLaboratorio" class="elevation-1">
                    <template v-slot:items="props">
                        <td class="text-xs">{{ props.item.laboratorio }}</td>
                        <td class="text-xs" v-if="props.item.adjunto"> <v-btn color="orange" round dark @click="consultarAdjunto(props.item.adjunto)">Ver Adjunto</v-btn></td>
                        <td class="text-xs" v-else>{{ props.item.resultado_lab?props.item.resultado_lab:'' }}</td>
                        <td class="text-xs">{{ props.item.factor_rh?props.item.factor_rh:'' }}</td>
                        <td class="text-xs">{{ props.item.fecha_laboratorio?props.item.fecha_laboratorio:'' }}</td>
                        <td class="text-xs">{{ props.item.medico?props.item.medico:'' }}</td>
                        <td class="text-xs">{{ props.item.fecha_registro?props.item.fecha_registro:'' }}</td>
                    </template>
                </v-data-table>
            </v-flex>
            <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
        </v-flex>
    </v-layout>
</template>

<script>
    import {AdjuntoService} from "../../services";

    export default {
        name: "",
        props: {
            datosCita: Object
        },
        data() {
            return {
                fileName:"",
                resultadosLaboratorio: {
                    laboratorio: '',
                    resultado_lab: '',
                    factor_rh: '',
                    fecha_laboratorio: ''
                },
                tipolab: ['Urocultivo', 'Colesterol Total', 'Trigliceridos', 'HDL', 'LDL Calculado', 'LDL Serico',
                    'Hemoglobina Glicosilada', 'Creatinina', 'TSH', 'Microalbuminuria', 'Glucosa en Ayunas',
                    'Ecografia obstétrica','Citologia cervicouterina','ADN VPH','Colposcopia','Biopsia de Cervix','Prueba Rapida VIH',
                    'Uroanalisis', 'Hemograma', 'Hemoglobina', 'Hematocrito', 'Albumina', 'Potasio', 'Creatinuria',
                    'Relación Albumina / Creatinuria', 'PTH', 'Fosforo', 'PSA', 'VIH', 'CD4', 'CD8', 'CD3',
                    'Relacion CD4/CD8', 'Prueba de Embarazo', 'IgG G Varicela', 'Prueba Rapida Treponemica',
                    'Prueba Rapida HB', 'VDRL', 'Hemoclasificacion', 'Antigeno Hep B', 'IgG Rubeola', 'toxo IgG',
                    'toxo IgM', 'PTOG ',
                    'Estreptococo B (sem 35 y 37 de Gestacion)',
                    'Antigeno de Hepatitis C',
                    'Espirometria curva flujo - volumen'
                ],
                otroresultado: ['Positivo', 'Negativo'],
                factor: ['A', 'B', 'AB', 'O'],
                fetchLaboratoriosPaciente: [],
                headerresultadosLaboratorio: [{
                        text: 'laboratorio'
                    },
                    {
                        text: 'Resultado'
                    },
                    {
                        text: 'Factor Rh'
                    },
                    {
                        text: 'Fecha lab'
                    },
                    {
                        text: 'Regitrado por'
                    },
                    {
                        text: 'Fecha Registo'
                    }
                ]
            }
        },
        created() {
            this.fetchLaboratorios()
        },
        methods: {
            saveLaboratorios() {
                this.resultadosLaboratorio.paciente_id = this.datosCita.paciente_id;
                this.resultadosLaboratorio.Citapaciente_id = this.datosCita.cita_paciente_id;
                const formData = new FormData();
                if(this.$refs.fileInput.files[0] !== undefined){
                    formData.append('adjunto', this.$refs.fileInput.files[0]);
                }
                for ( const key in this.resultadosLaboratorio ) {
                    formData.append(key, this.resultadosLaboratorio[key]);
                }
                axios.post('/api/historia/saveLaboratorios', formData)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchLaboratorios();
                        this.cleardata();
                    })
            },
            fetchLaboratorios() {
                axios.get(`/api/historia/fetchLaboratorios/${this.datosCita.paciente_id}`)
                    .then(res => {
                        console.log(res.data);
                        this.fetchLaboratoriosPaciente = res.data;
                    });
            },
            clearPresLab() {
                this.resultadosLaboratorio.resultado_lab = '',
                    this.resultadosLaboratorio.factor_rh = '',
                    this.resultadosLaboratorio.fecha_laboratorio = ''
            },
            cleardata() {
                this.resultadosLaboratorio.laboratorio = '',
                    this.resultadosLaboratorio.resultado_lab = '',
                    this.resultadosLaboratorio.factor_rh = '',
                    this.resultadosLaboratorio.fecha_laboratorio = ''
            },
            guardarSeguir() {
                this.$emit('respuestaComponente')
            },
            onPickFile () {
                this.$refs.fileInput.click()
            },
            onFilePicked (event) {
                const files = event.target.files
                if (files[0] !== undefined) {
                    for (const [key, value] of Object.entries(files)) {
                        this.fileName += value.name+", "
                    }

                } else {
                    this.fileName = ''
                }
            },
            async consultarAdjunto(ruta) {
                this.search_adjunto = true
                try {
                    let adj = await AdjuntoService.get(ruta);
                    let blob = new Blob([adj[1]], {
                        type: adj[0]
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");
                    // this.search_adjunto = false
                } catch (error) {
                    // this.search_adjunto = false
                }
            },
        }
    }

</script>
