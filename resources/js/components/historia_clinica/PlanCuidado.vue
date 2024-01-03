<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-layout row wrap>
                    <v-flex xs12 sm12 md12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Plan de Cuidado</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6 md7>
                        <v-select style="font-size: 14px" :items="planCuidado" label="Seleccione plan de cuidado"
                            v-model="planesdeCuidado.plan_cuidado"></v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md2>
                        <v-select style="font-size: 14px" :items="siNo" label="Si/No"
                            v-model="planesdeCuidado.tipo_plan_cuidado"></v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="savePlancuidado()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-data-table :headers="headerPlanCuidado" :items="fetchPlanCuidados">
                            <template v-slot:items="props">
                                <td class="text-xs">{{ props.item.id }}</td>
                                <td class="text-xs">{{ props.item.plan_cuidado }}</td>
                                <td class="text-xs">{{ props.item.tipo_plan_cuidado }}</td>
                                <td class="text-xs">{{ props.item.medico }}</td>
                                <td class="text-xs">{{ props.item.created_at }}</td>
                                <td class="text-xs">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn small text icon color="red" dark v-on="on">
                                                <v-icon @click="deletePlancuidado(props.item.id)">
                                                    mdi-delete-forever
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Eliminar Dx</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-flex>
                    <!-- <v-flex xs12 sm12 md7>
                        <label>Educación entregada al Usuario </label>
                        <v-radio-group v-model="planesdeCuidado.entrega_educacion" row>
                            <v-radio label="SI" color="success" value="Si"></v-radio>
                            <v-radio label="NO" color="red" value="No"></v-radio>
                        </v-radio-group>
                    </v-flex> -->
                    <!-- <v-flex xs12 sm12 md12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Evaluación</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6 md7>
                        <v-select style="font-size: 14px" :items="evaluacion" label="Seleccione plan de cuidado"
                            v-model="evaluacionPlan.evaluacion"></v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md2>
                        <v-select style="font-size: 14px" :items="siNoAplica" label="Si/No"
                            v-model="evaluacionPlan.tipo_evaluacion"></v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="savePlanevaluacion()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-data-table :headers="headerevaluacion" :items="fetchPlanevaluacions">
                            <template v-slot:items="props">
                                <td class="text-xs">{{ props.item.id }}</td>
                                <td class="text-xs">{{ props.item.evaluacion }}</td>
                                <td class="text-xs">{{ props.item.tipo_evaluacion }}</td>
                                <td class="text-xs">{{ props.item.medico }}</td>
                                <td class="text-xs">{{ props.item.created_at }}</td>
                                <td class="text-xs">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn small text icon color="red" dark v-on="on">
                                                <v-icon @click="deletePlanevaluacion(props.item.id)">
                                                    mdi-delete-forever
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Eliminar Dx</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-flex> -->
                    <v-flex xs12 sm12 md12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Informacion en Salud</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6 md7>
                        <v-select style="font-size: 14px" :items="salud" label="Seleccione plan de cuidado"
                            v-model="inforSalud.info_salud"></v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md2>
                        <v-select style="font-size: 14px" :items="siNo" label="Si/No"
                            v-model="inforSalud.tipo_info_salud"></v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="saveInforSalud()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-data-table :headers="headerInforSalud" :items="allInforSalud">
                            <template v-slot:items="props">
                                <td class="text-xs">{{ props.item.id }}</td>
                                <td class="text-xs">{{ props.item.info_salud }}</td>
                                <td class="text-xs">{{ props.item.tipo_info_salud }}</td>
                                <td class="text-xs">{{ props.item.medico }}</td>
                                <td class="text-xs">{{ props.item.created_at }}</td>
                                <td class="text-xs">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn small text icon color="red" dark v-on="on">
                                                <v-icon @click="deleteInforSalud(props.item.id)">
                                                    mdi-delete-forever
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Eliminar Dx</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-flex>
                    <v-flex xs12 sm12 md12 v-show="datosCita.Tipo_agenda == '63' || datosCita.Tipo_agenda == '26' || datosCita.Tipo_agenda == '13'">
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Prácticas de Crianza y
                                cuidado</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6 md7 v-show="datosCita.Tipo_agenda == '63' || datosCita.Tipo_agenda == '26' || datosCita.Tipo_agenda == '13'">
                        <v-select style="font-size: 14px" :items="crianza" label="Seleccione plan de cuidado"
                            v-model="practicasCrianza.practica_crianza"></v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md2 v-show="datosCita.Tipo_agenda == '63' || datosCita.Tipo_agenda == '26' || datosCita.Tipo_agenda == '13'">
                        <v-select style="font-size: 14px" :items="siNo" label="Si/No"
                            v-model="practicasCrianza.tipo_practica_crianza"></v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md1 v-show="datosCita.Tipo_agenda == '63' || datosCita.Tipo_agenda == '26' || datosCita.Tipo_agenda == '13'">
                        <v-btn fab dark small color="success" @click="saveCrianzaCuidado()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                    <v-flex xs12 sm12 md12 v-show="datosCita.Tipo_agenda == '63' || datosCita.Tipo_agenda == '26' || datosCita.Tipo_agenda == '13'">
                        <v-data-table :headers="headerCrianzaCuidado" :items="allCrianzaCuidado">
                            <template v-slot:items="props">
                                <td class="text-xs">{{ props.item.id }}</td>
                                <td class="text-xs">{{ props.item.practica_crianza }}</td>
                                <td class="text-xs">{{ props.item.tipo_practica_crianza }}</td>
                                <td class="text-xs">{{ props.item.medico }}</td>
                                <td class="text-xs">{{ props.item.created_at }}</td>
                                <td class="text-xs">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn small text icon color="red" dark v-on="on">
                                                <v-icon @click="deleteCrianzaCuidado(props.item.id)">
                                                    mdi-delete-forever
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Eliminar Dx</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Proximo Control</span>
                        </v-card-title>
                        <v-flex xs12 sm6 md4>
                            <v-text-field label="Ingresa la fecha del proximo control" type="date"
                                v-model="proximo.proximoControl"></v-text-field>
                        </v-flex>
                    </v-flex>
                    <v-btn color="success" round @click="saveProximoControl()">Guardar y seguir</v-btn>
                </v-layout>
            </v-container>
        </v-form>
    </div>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        data() {
            return {
                row: null,
                planCuidado: [
                    'Atención en salud bucal por profesional de odontología. ',
                    'Desparasitación intestinal',
                    'Ordenar prueba de hemoglobina',
                    'Ordenar prueba de hemoglobina y hematocrito',
                    'Vacunación',
                    'Consulta de anticoncepción',
                    'Tamizaje para enfermedades de trasmisión sexual',
                    'Tamizajes para población con identificación de riesgos',
                    'Educación grupal para la salud que incluye a la familia',
                    'Requiere consulta de control por nutrición si riesgo','Inconvenientes e inquietudes sobre la lactancia',
                    'Planes de la madre para continuar con la lactancia durante el retorno al trabajo o estudios'
                ],
                siNo: ['Si', 'No'],
                siNoAplica:['Si', 'No', 'No Aplica'],
                planesdeCuidado: {
                    plan_cuidado: '',
                    tipo_plan_cuidado: '',


                },
                proximo:{
                      proximoControl:'',
                },
                salud: ['Prácticas de crianza protectoras', 'Prácticas basadas en derecho', 'Prevención de violencias',
                    'Promoción de la salud', 'Manipulación de alimentos y enfermedades trasmisibles por los mismos',
                    'Promoción hábitos y estilos de vida', 'Educación de lactancia materna',
                    'Hábitos de higiene personal y cuidado bucal', 'Conductas protectoras para audición segura',
                    'Evitación del Sedentarismo y uso prolongado de medios audiovisuales', 'Promoción salud mental',
                    'Prevención de accidentes', 'Signos de alarma enfermedades prevalentes en el ciclo vital',
                    'Educación para consultar por urgencias',
                    'Educación derechos de salud y mecanismos de exigibilidad de los mismos',
                    'Derechos de las madres', 'Promoción fortalecimiento rol del padre como cuidador de sus hijos',
                    'Se orienta sobre el consumo de fuentes de Vitamina A como',
                    'Se identifica riesgo deficiencia en Vitamina D y Calcio',
                    'Autocuidado', 'Buen relacionamiento con la familia, otras personas y niños',
                    'Actividades para estimular el desarrollo',
                    'Adherencia a medicamentos y habitos de Vida Saludables',
                    'Adecuada expresion de cariño y reconocimiento al niño',
                    'Grupal para la salud que incluye a la familia',
                    'Sobre el derecho a la IVE. Sentencia C355', 'Prevencion de  VIH', 'Prueba Post VIH',
                    'en Anticoncepcion',
                    'Método elegido para iniciar en el posparto', 'Sobrecarga del cuidador y tips para evitarlo',
                    'Orientación a la madre o cuidador en expedición registro civil del menor',
                    'Meta ganancia o perdida de peso',
                    'a la madre o cuidador en expedición registro civil del menor',
                    'Prevencion del Sedentarismo y uso prolongado de medios audiovisuales',
                    'Prevencion de la exposición a hábitos tóxicos',
                    'Avances compromisos de sesiones de educación',
                    'Recomendaciones al cuidador',
                    'Se orienta sobre el consumo de fuentes de Vitamina A',
                    'Se identifica riesgo deficiencia en Vitamina D y Calcio'
                ],
                // evaluacion: [
                //     'Inconvenientes e inquietudes sobre la lactancia',
                //     'Planes de la madre para continuar con la lactancia durante el retorno al trabajo o estudios',
                // ],
                crianza:[
                    'Se le expresa cariño y reconocimiento al niño',
                    'Indagación sobre acciones correctivas',
                    'Actividades para estimular el desarrollo',
                    'Buen relacionamiento con la familia, otras personas y niños',
                    'Educación en autocuidado'
                ],
                fetchPlanCuidados: [],
                headerPlanCuidado: [
                    {text: 'id'},
                    {text: 'Plan y Cuidado'},
                    {text: 'Si / No'},
                    {text: 'Medico'},
                    {text: 'Fecha Registro'},
                    {text: 'Acción'}
                ],
                headerCrianzaCuidado: [
                    {text: 'id'},
                    {text: 'Practica Crianza'},
                    {text: 'Si / No'},
                    {text: 'Medico'},
                    {text: 'Fecha Registro'},
                    {text: 'Acción'}
                ],
                practicasCrianza: {
                    practica_crianza: '',
                    tipo_practica_crianza: ''
                },
                headerevaluacion: [
                    {text: 'id'},
                    {text: 'Evaluación'},
                    {text: 'Si / No'},
                    {text: 'Medico'},
                    {text: 'Fecha Registro'},
                    {text: 'Acción'}
                ],
                headerInforSalud: [
                    {text: 'id'},
                    {text: 'Informacion salud'},
                    {text: 'Si / No'},
                    {text: 'Medico'},
                    {text: 'Fecha Registro'},
                    {text: 'Acción'}
                ],
                evaluacionPlan: {
                    evaluacion: '',
                    tipo_evaluacion: '',
                },
                // fetchPlanevaluacions: [],
                inforSalud: {
                    info_salud: '',
                    tipo_info_salud: ''
                },
                practicasCrianza: {
                    practica_crianza: '',
                    tipo_practica_crianza: ''
                },
                allInforSalud: [],
                allCrianzaCuidado: []
            }
        },
        created(){
            this.fetchPlancuidado();
            // this.fetchPlanevaluacion();
            this.fetchInforSalud();
            this.fetchCrianzaCuidado();
            this.fetchProximoControl();
        },
        methods: {
            savePlancuidado() {
                this.planesdeCuidado.paciente_id = this.datosCita.paciente_id;
                this.planesdeCuidado.Citapaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/savePlanCuidado', this.planesdeCuidado)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.cleardata();
                        this.fetchPlancuidado();
                    })
            },
            fetchPlancuidado() {
                axios.get(`/api/historia/fetchPlancuidado/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.fetchPlanCuidados = res.data;
                    });
            },

            saveProximoControl() {
                this.proximo.Citapaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveProximoControl', this.proximo)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                       this.fetchProximoControl();
                       this.guardarSeguir();
                    })
            },
            fetchProximoControl() {
                axios.get(`/api/historia/fetchProximoControl/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.proximo = res.data;
                    });
            },


            cleardata(){
                this.planesdeCuidado.plan_cuidado = '',
                this.planesdeCuidado.tipo_plan_cuidado = ''
            },
            // clearEvaluacion(){
            //     this.evaluacionPlan.evaluacion = '',
            //     this.evaluacionPlan.tipo_evaluacion = ''
            // },
            clearInforSalud(){
                this.inforSalud.info_salud = '',
                this.inforSalud.tipo_info_salud = ''
            },
            deletePlancuidado(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El elemento será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/deletePlan/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchPlancuidado();
                });
            },
            // savePlanevaluacion() {
            //     this.evaluacionPlan.paciente_id = this.datosCita.paciente_id;
            //     this.evaluacionPlan.Citapaciente_id = this.datosCita.cita_paciente_id;
            //     axios.post('/api/historia/saveEvaluacionPlan', this.evaluacionPlan)
            //         .then(res => {
            //             this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
            //             this.clearEvaluacion();
            //             this.fetchPlanevaluacion();
            //         })
            // },
            // fetchPlanevaluacion() {
            //     axios.get(`/api/historia/fetchPlanevaluacion/${this.datosCita.paciente_id}`)
            //         .then(res => {
            //             this.fetchPlanevaluacions = res.data;
            //         });
            // },
            // deletePlanevaluacion(id) {
            //     swal({
            //         title: "¿Está Seguro(a)?",
            //         text: "El elemento será eliminado",
            //         icon: "warning",
            //         buttons: ["Cancelar", "Confirmar"],
            //         dangerMode: true
            //     }).then(willDelete => {
            //         if (willDelete) {
            //             axios.post(`/api/historia/deleteEvaluacion/${id}`)
            //                 .then(res => {
            //                     this.$alertHistoria('<span style="color:#fff">' + res.data.message +
            //                         '<span>');
            //                 })
            //                 .catch(err => console.log(err.response.data));
            //         }
            //         this.fetchPlanevaluacion();
            //     });
            // },
            saveInforSalud() {
                this.inforSalud.paciente_id = this.datosCita.paciente_id;
                this.inforSalud.Citapaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveinforSalud', this.inforSalud)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.clearInforSalud();
                        this.fetchInforSalud();
                    })
            },
            fetchInforSalud() {
                axios.get(`/api/historia/fetchInforSalud/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.allInforSalud = res.data;
                    });
            },
            deleteInforSalud(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El elemento será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/deleteInforSalud/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchInforSalud();
                });
            },
            saveCrianzaCuidado() {
                this.practicasCrianza.paciente_id = this.datosCita.paciente_id;
                this.practicasCrianza.Citapaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveCrianzaCuidado', this.practicasCrianza)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.clearInforSalud();
                        this.fetchCrianzaCuidado();
                    })
            },
            fetchCrianzaCuidado() {
                axios.get(`/api/historia/fetchCrianzaCuidado/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.allCrianzaCuidado = res.data;
                    });
            },
            deleteCrianzaCuidado(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El elemento será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/deleteCrianzaCuidado/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchCrianzaCuidado();
                });
            },

            guardarSeguir() {
                this.$emit('respuestaComponente')
            },
        }

    }

</script>

