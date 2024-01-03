<template>
    <v-card>
        <v-layout v-show="showTeleDialog" row justify-center>
            <v-dialog v-model="showTeleDialog" persistent max-width="1400px">
                <v-card>
                    <v-dialog v-model="preload" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Tranquilo procesamos tu solicitud !
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                    <v-card-text>
                        <v-container>
                            <v-form ref="form">
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-card>
                                            <v-card-title class="headline grey lighten-2" primary-title>
                                                Detalles Teleorientacion
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12 sm12 md12>
                                                            <v-list-tile avatar>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title><strong>Especialidad</strong></v-list-tile-title>
                                                                    <v-list-tile-sub-title>{{detalleTeleconcepto.especialidad}}</v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </v-flex>
                                                        <v-flex xs12 sm6 md6>
                                                            <v-divider></v-divider>
                                                            <v-list-tile avatar>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title><strong>Tipo Solicitud</strong></v-list-tile-title>
                                                                    <v-list-tile-sub-title>{{detalleTeleconcepto.Tipo_Solicitud}}</v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </v-flex>
                                                        <v-flex xs6 sm6 md6>
                                                            <v-divider></v-divider>
                                                            <v-list-tile v-if="detalleTeleconcepto.cup" avatar>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title><strong>CUP</strong></v-list-tile-title>
                                                                    <v-list-tile-sub-title>{{detalleTeleconcepto.cup}}</v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </v-flex>
                                                        <v-flex xs12 sm12 md12>
                                                            <v-divider></v-divider>
                                                            <v-list-tile avatar>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title><strong>Motivo Interconsulta</strong></v-list-tile-title>
                                                                    <v-list-tile-sub-title>{{detalleTeleconcepto.Descripcion}}</v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </v-flex>
                                                        <v-flex xs12 sm12 md12>
                                                            <v-divider></v-divider>
                                                            <v-list-tile avatar>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title><strong>Resumen Historia Clinica</strong></v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            {{detalleTeleconcepto.ResumenHc}}
                                                        </v-flex>
                                                        <v-flex xs12 sm6 md6>
                                                            <v-divider></v-divider>
                                                            <v-list-tile avatar>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title><strong>Diagnosticos</strong></v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            <ul>
                                                                <li v-for="(cie, index) in detalleTeleconcepto.cie10" :key="index">{{ cie.Codigo_CIE10 }} - {{ cie.Descripcion }}</li>
                                                            </ul>
                                                        </v-flex>
                                                        <v-flex xs12 sm6 md6>
                                                            <v-divider></v-divider>
                                                            <v-list-tile avatar>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title><strong>Archivos Adjuntos</strong></v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            <v-btn  color="warning"
                                                                    :key="index"
                                                                    :href="adjunto.Ruta"
                                                                    outline
                                                                    round
                                                                    small
                                                                    target="_blank"
                                                                    v-for="(adjunto, index) in detalleTeleconcepto.adjuntos">
                                                                Abrir archivo
                                                            </v-btn>
                                                        </v-flex>
                                                        <v-flex xs12 sm12 md12>
                                                            <v-divider></v-divider>
                                                            <v-list-tile avatar>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title><strong>Observacion especialista resignacion GIRS</strong></v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            {{detalleTeleconcepto.observacion_reasignacion_girs}}
                                                        </v-flex>

                                                        <v-flex xs12 sm6 md6>
                                                            <v-divider></v-divider>
                                                            <v-list-tile avatar>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title><strong>Prioridad del teleconcepto</strong></v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            {{detalleTeleconcepto.pertinencia_prioridad}}
                                                        </v-flex>

                                                        <v-flex xs12 sm6 md6>
                                                            <v-divider></v-divider>
                                                            <v-list-tile avatar>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title><strong>Evaluación pertinencia de solicitud de Teleorientacion</strong></v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            {{detalleTeleconcepto.pertinencia_evaluacion}}
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-spacer></v-spacer>
                                        </v-card>
                                    </v-flex>


                                    <v-flex xs12 pa-0>
                                        <slot />
                                    </v-flex>

                                    <v-flex xs12 mt-3>
                                        <v-card v-show="detalleTeleconcepto.citaPaciente">
                                            <v-card-title class="headline grey lighten-2" primary-title>
                                                Ordenamiento
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12 sm1 md1 offset-sm11 offset-md11>


                                                            <!--                                    <v-btn color="success">Ordenar</v-btn>-->
                                                            <Ordenamiento :paciente="paciente" :showBottom="true" ref="ordenamiento"
                                                                          :cita_paciente="parseInt(detalleTeleconcepto.citaPaciente)" tipo="teleconcepto" :idReferencia="detalleTeleconcepto.id" @clearDataPaciente="clearDataPaciente"></Ordenamiento>

                                                        </v-flex>
                                                        <v-flex xs12 sm12 md12>
                                                            <v-data-table
                                                                v-model="selected"
                                                                :headers="headersOrdenamiento"
                                                                :items="detalleTeleconcepto.medicamentos"
                                                                item-key="id"
                                                                hide-actions
                                                            >
                                                                <template v-slot:items="props">
                                                                    <td>
                                                                        <v-checkbox v-show="props.item.Estado_id === '15'"
                                                                                    v-model="props.selected"
                                                                                    color="primary"
                                                                                    hide-details
                                                                        ></v-checkbox>
                                                                    </td>
                                                                    <td>{{ props.item.tipo }}</td>
                                                                    <td>{{ props.item.Codigo }}</td>
                                                                    <td class="text-xs-right">{{ props.item.Nombre }}</td>
                                                                    <td class="text-xs-right">{{ props.item.Cantidad }}</td>
                                                                    <td class="text-xs-right">{{ props.item.paginacion }}</td>
                                                                    <td class="text-xs-right">{{ props.item.Observacion }}</td>
                                                                </template>
                                                            </v-data-table>
                                                            <v-spacer></v-spacer>
                                                            <v-flex xs12 v-if="this.selected.length > 0">
                                                                <v-select v-model="accion" append-icon="search" label="Seleccionar acción"
                                                                          :items="acciones" item-text="accion" item-value="value" hide-details></v-select>
                                                            </v-flex>
                                                            <v-flex xs12 v-if="this.selected.length > 0">
                                                                <v-textarea name="input-7-1" label="Observacion Auditor" v-model="observaciones">
                                                                </v-textarea>
                                                            </v-flex>
                                                            <v-flex xs12 v-if="this.selected.length > 0">
                                                                <v-btn color="success" @click="auditar">Enviar</v-btn>
                                                            </v-flex>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>


                                    <v-flex xs12 pa-0>
                                        <slot />
                                    </v-flex>
                                    <v-flex xs12 mt-3>
                                        <v-card>
                                            <v-card-title class="headline grey lighten-2" primary-title>
                                                Respuesta Especialista
                                            </v-card-title>
                                            <v-divider></v-divider>
                                            <v-layout row wrap>
                                                <v-flex xs12 mt-2x>
                                                    <div>
                                                        <p>{{ data.Respuesta }}</p>
                                                    </div>
                                                </v-flex>
                                            </v-layout>
                                        </v-card>
                                    </v-flex>

                                    <v-flex xs12 mt-3>
                                        <v-card>
                                            <v-card-title class="headline grey lighten-2" primary-title>
                                                Junta GIRS
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12 sm12 md12>
                                                            <v-autocomplete
                                                                v-model="junta.integrantes"
                                                                :items="users"
                                                                label="Integrantes"
                                                                item-text="nombre"
                                                                item-value="id"
                                                                chips
                                                                multiple
                                                            >
                                                                <template v-slot:selection="data">
                                                                    <v-chip
                                                                            :selected="data.selected"
                                                                            close
                                                                            class="chip--select-multi"
                                                                            @input="remove(data.item)"
                                                                    >
                                                                        {{ data.item.nombre }}
                                                                    </v-chip>
                                                                </template>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs12 sm6 md6>
                                                            <v-text-field label="Institucion Prestadora" v-model="detalleTeleconcepto.institucion_prestadora" ></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12 sm6 md6>
                                                            <v-text-field label="EAPB" v-model="detalleTeleconcepto.eapb"></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12 sm12 md12>
                                                            <v-textarea name="input-7-1" label="Evaluacion junta" v-model="detalleTeleconcepto.evaluacion_junta" >
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12 sm6 md6>
                                                            <v-label>La junta de profesionales de salud aprueba?</v-label>
                                                            <v-radio-group v-model="detalleTeleconcepto.junta_aprueba">
                                                                <v-radio
                                                                    color="primary"
                                                                    v-for="n in ['Si','No','No requiere junta']"
                                                                    :key="n"
                                                                    :label="n"
                                                                    :value="n"

                                                                ></v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm6 md6>
                                                            <v-label>Clasificación de prioridad de servicio ambulatorio</v-label>
                                                            <v-radio-group v-model="detalleTeleconcepto.Tipo_Solicitud">
                                                                <v-radio
                                                                    color="primary"
                                                                    v-for="n in [{id:'Prioritario',name:'Priorizado (Notificar en 24 horas)'},{id:'Normal',name:'No priorizado (Notificar en 5 días).'}]"
                                                                    :key="n.id"
                                                                    :label="n.name"
                                                                    :value="n.id"

                                                                ></v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs2 offset-xs10>
                                                                                                <v-btn color="success" v-if="can('teleconcepto.guardar.girs')" @click="guardarJuntaGIRS()">Guardar</v-btn>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>
                                </v-layout>
                            </v-form>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" dark @click="showTeleDialog = false">Cerrar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
        <v-card-title>
            Nutrition
            <v-spacer></v-spacer>
            <v-text-field
                v-model="search"
                append-icon="search"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
        </v-card-title>
        <v-data-table
            :headers="headers"
            :items="listTeleconceptos"
            :search="search"
        >
            <template v-slot:no-data>
                <span>No hay teleorientacion</span>
            </template>
            <template v-slot:items="props">
                <td class="text-xs-center">{{ props.item.Num_Doc }}</td>
                <td class="text-xs-center">{{ props.item.especialidad }}</td>
                <td class="text-xs-center">{{ props.item | fullnamePaciente }}</td>
                <td class="text-xs-center">{{ props.item.created_at | date }}</td>
                <td class="text-xs-center">{{ props.item.Tipo_Solicitud }}</td>
                <td class="text-xs-center">{{ props.item | fullnameCreador }}</td>
                <td class="text-xs-center">{{ props.item.Sede }}</td>
                <td class="text-xs-center">
                    <v-btn color="primary" icon @click="showTeleconcepto(props.item)">
                        <v-icon>library_books</v-icon>
                    </v-btn>
                </td>
                <td class="text-xs-center"><v-chip v-if="props.item.girs_auditor" color="success" text-color="white">GIRS</v-chip></td>
            </template>
            <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="warning">
                    la busqueda "{{ search }}" no encontro resultados.
                </v-alert>
            </template>
        </v-data-table>
    </v-card>
</template>
<script>
import moment from 'moment';
import Ordenamiento from "../../../components/medicamento/Ordenamiento.vue";
import {mapGetters} from "vuex";
moment.locale('es');
export default {
    name: "TeleConceptoLayout",
    components: {
        Ordenamiento
    },
    data: () => {
        return {
            showTeleDialog:false,
            preload:false,
            listTeleconceptos: [],
            search: '',
            users:[],
            selected:[],
            junta:{
                integrantes:[]
            },
            headers: [{
                text: 'Identificación',
                value: 'Num_Doc',
                align: 'center',
                sortable: false
            },
                {
                    text: 'Especialidad',
                    value: 'Especialidad',
                    align: 'center',
                    sortable: false
                },
                {
                    text: 'Nombre',
                    value: 'Primer_Nom',
                    align: 'center',
                    sortable: false
                },
                {
                    text: 'Registro',
                    value: 'string',
                    align: 'center',
                    sortable: false
                },
                {
                    text: 'Prioridad',
                    value: 'Tipo_Solicitud',
                    align: 'center',
                    sortable: false
                },
                {
                    text: 'Médico solicitante',
                    value: 'string',
                    align: 'center',
                    sortable: false
                },

                {
                    text: 'Sede',
                    value: 'Sede',
                    align: 'center',
                    sortable: false
                },
                {
                    text: 'Gestión',
                    value: 'string',
                    align: 'center',
                    sortable: false
                },{
                    text: '',
                    value: 'string',
                    align: 'center',
                    sortable: false
                }
            ],
            headersOrdenamiento :[
                {
                    text: "",
                    align: 'center',
                    sortable: false,
                    value: ""
                },{
                    text: "Tipo",
                    align: 'center',
                    value: "tipo"
                },{
                    text: "Codigo",
                    align: 'center',
                    value: "Codigo"
                },{
                    text: "Nombre",
                    align: 'center',
                    value: "Nombre"
                },{
                    text: "Cantidad",
                    align: 'center',
                    sortable: false,
                    value: "tipo"
                },{
                    text: "Paginacion",
                    align: 'center',
                    sortable: false,
                    value: "paginacion"
                },{
                    text: "Observaciones",
                    align: 'center',
                    sortable: false
                }
            ],
            pagination: {
                rowsPerPage: 20,
            },
            data: {
                Tipo_anexo: null,
                cie10s: []
            },
            acciones: [{
                accion: "APROBADO",
                value: "Aprobado"
            },
                {
                    accion: "INADECUADO",
                    value: "Inadecuado"
                },
                {
                    accion: "NEGADO",
                    value: "Negado"
                },
                {
                    accion: "ANULADO",
                    value: "Anulado"
                }
            ],
            paciente: {},
            detalleTeleconcepto:{
                Tipo_Solicitud: null,
                Descripcion:null,
                ResumenHc:null,
                Respuesta:null,
                especialidad:null,
                cup:null,
                cie10:[],
                adjuntos:null,
                citaPaciente:null,
                medicamentos:[],
                girs:null,
                id:null,
                girs_auditor:false,
                pertinencia_evaluacion:"",
                pertinencia_prioridad:""
            },
            cups:[]
        }},
    filters: {
        fullnamePaciente: (item) => {
            return `${item.Primer_Nom || ''} ${item.SegundoNom || ''} ${item.Primer_Ape || ''} ${item.Segundo_Ape || ''}`
        },
        fullnameCreador: (item) => {
            return `${item.name || ''} ${item.apellido || ''}`
        },
        date: (date) => {
            return moment(date).format('lll')
        }
    },
    computed: {
        ...mapGetters(['can']),
    },
    methods: {
        getTeleconceptosGirs(){
            axios.get('/api/teleconcepto/girsAuditados').then(res => {
                this.listTeleconceptos = res.data;
            })
        },
        showTeleconcepto(tele) {
            this.paciente = {
                id: tele.id,
                Primer_Nom: tele.Primer_Nom,
                Primer_Ape: tele.Primer_Ape,
                Tipo_Doc: tele.Tipo_Doc,
                Num_Doc: tele.Num_Doc,
                Edad_Cumplida: tele.Edad_Cumplida,
                Telefono: tele.Telefono,
                Celular1: tele.Celular1,
                Celular2: tele.Celular2,
                Correo1: tele.Correo1,
                Correo2: tele.Correo2,
            }
            this.data = {
                Tipo_Solicitud: tele.Tipo_Solicitud,
                cie10s: tele.cie10s,
                adjuntos: tele.adjuntos,
                descripcion: tele.descripcion,
                ResumenHc: tele.ResumenHc,
                Respuesta: tele.Respuesta,
                Sede: tele.Sede,
                Firma: tele.Firma,
                Registromedico: tele.Registromedico,
                especialidad_medico: tele.especialidad_medico,
                Ut: tele.Ut,
            }
            this.detalleTeleconcepto.citaPaciente = tele.cita_paciente_id;
            if(tele.cita_paciente_id){
                this.medicamentosOrdenados(tele.cita_paciente_id);
            }else{
                this.detalleTeleconcepto.medicamentos = [];
            }
            this.detalleTeleconcepto.id = tele.id;
            this.detalleTeleconcepto.especialidad = tele.especialidad;
            this.detalleTeleconcepto.Descripcion = tele.descripcion;
            this.detalleTeleconcepto.Tipo_Solicitud = tele.Tipo_Solicitud;
            this.detalleTeleconcepto.ResumenHc = tele.ResumenHc;
            this.detalleTeleconcepto.cie10 = tele.cie10s;
            this.detalleTeleconcepto.adjuntos = tele.adjuntos;
            this.detalleTeleconcepto.girs = !!parseInt(tele.girs);
            this.detalleTeleconcepto.girs_auditor = !!parseInt(tele.girs_auditor);
            this.detalleTeleconcepto.observacion_reasignacion_girs = tele.observacion_reasignacion_girs;
            this.detalleTeleconcepto.institucion_prestadora = tele.institucion_prestadora;
            this.detalleTeleconcepto.eapb = tele.eapb
            this.detalleTeleconcepto.evaluacion_junta = tele.evaluacion_junta
            this.detalleTeleconcepto.junta_aprueba = tele.junta_aprueba;
            this.detalleTeleconcepto.pertinencia_evaluacion = tele.pertinencia_evaluacion;
            this.detalleTeleconcepto.pertinencia_prioridad = tele.pertinencia_prioridad;
            tele.integrantes_girs.forEach(s => {
                this.junta.integrantes.push(parseInt(s.usuario_id))
            })

            if(tele.Cup_id){
                const found = this.cups.find(element => parseInt(element.id) === parseInt(tele.Cup_id));
                this.detalleTeleconcepto.cup = found.Nombre;
            }else{
                this.detalleTeleconcepto.cup = null;
            }
            this.showTeleDialog = true;
        },
        medicamentosOrdenados(id){
            this.detalleTeleconcepto.medicamentos = [];
            this.selected = [];
            this.accion = null;
            this.observaciones = null
            this.preload = true;
            axios.get('/api/orden/getDetalleOrdenamientoForCita/'+id).then(res =>{
                this.detalleTeleconcepto.medicamentos = res.data;
                this.preload = false;
            }).catch(err => {
                this.preload = false;
                console.log(err)
            })

        },
        fetchCups() {
            axios.get('/api/cups/all')
                .then(res => this.cups = res.data.map((cup) => { return { id: cup.id, Nombre: `${cup.Codigo} - ${cup.Nombre}` }}));
        },
        getUser() {
            axios.get('/api/user/all')
                .then(res => this.users = res.data.map((us) => {
                    return {
                        id: us.id,
                        nombre: `${us.cedula} - ${us.name} ${us.apellido}`,
                        estado: us.estado_user
                    }
                }));
        },
        auditar(){
            if(!this.accion){
                this.$alerError("Debe selecciona una accion")
                return;
            }
            if(!this.observaciones){
                this.$alerError("El campo observaciones es requrido")
                return;
            }
            let ordenes = [{datos:[],tipo:'Medicamentos'},{datos:[],tipo:'Servicios'}]

            this.selected.forEach(s => {
                if(s.tipo === 'medicamento'){
                    ordenes[0].datos.push({Codesumi_id:s.referencia,Nombre:s.Nombre,id:s.id,Tiporden_id:s.Tiporden_id,orden:s.Orden_id});
                }
                if(s.tipo === 'servicio'){
                    ordenes[1].datos.push({Orden_id:s.Orden_id,id:s.id,posFechar:false});
                }
            })
            ordenes.forEach(s => {
                if(s.datos.length > 0){
                    const dataSend = {
                        observaciones: this.observaciones,
                        autorizaciones: s.datos,
                        type: s.tipo,
                        id:s.orden,
                        Tiporden_id:s.datos[0].Tiporden_id,
                    }

                    if (this.accion === "Aprobado") {
                        axios.post("/api/autorizacion/StateAprobOne", dataSend)
                            .then(res => {
                                swal("¡Aprobación generada de manera exitosa!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.medicamentosOrdenados(this.detalleTeleconcepto.citaPaciente);
                            })
                            .catch(err => console.log(err.response));
                    }else if (this.accion === "Inadecuado") {
                        axios
                            .post("/api/autorizacion/StateInad", dataSend)
                            .then(res => {
                                this.$alerSuccess(res.data)
                                this.medicamentosOrdenados(this.detalleTeleconcepto.citaPaciente);
                            });
                    } else if (this.accion === "Negado") {
                        axios
                            .post("/api/autorizacion/StateNeg", dataSend)
                            .then(res => {
                                this.$alerSuccess(res.data)
                                this.medicamentosOrdenados(this.detalleTeleconcepto.citaPaciente);
                            });
                    } else if (this.accion === "Anulado") {
                        axios
                            .post("/api/autorizacion/StateAnu", dataSend)
                            .then(res => {
                                this.$alerSuccess(res.data)
                                this.medicamentosOrdenados(this.detalleTeleconcepto.citaPaciente);
                            });
                    }

                }
            })

        },
        async guardarJuntaGIRS(){
                await axios.post('/api/teleconcepto/guardarIntegrantes/'+this.detalleTeleconcepto.id,this.junta.integrantes);
                const data = {
                    id:this.detalleTeleconcepto.id,
                    'Tipo_Solicitud':this.detalleTeleconcepto.Tipo_Solicitud,
                    'institucion_prestadora':this.detalleTeleconcepto.institucion_prestadora,
                    'eapb':this.detalleTeleconcepto.eapb,
                    'evaluacion_junta':this.detalleTeleconcepto.evaluacion_junta,
                    'junta_aprueba':this.detalleTeleconcepto.junta_aprueba,
                    'Estado_id':9
                };
                await axios.post('/api/teleconcepto/guardar',data)
            this.showTeleDialog = false
            this.getTeleconceptosGirs();
            this.$alerSuccess('Registro Exitoso!');
        },
        clearDataPaciente(){
            this.medicamentosOrdenados(this.detalleTeleconcepto.citaPaciente);
        },
    },
    mounted() {
        this.getTeleconceptosGirs();
        this.fetchCups();
        this.getUser();
    }
};
</script>

<style lang="scss" scoped>
</style>
