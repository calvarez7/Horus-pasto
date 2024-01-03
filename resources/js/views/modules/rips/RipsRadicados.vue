<template>
    <div>
        <template>
            <div class="text-center">
                <v-dialog v-model="preload" persistent width="300">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Tranquilo procesamos tu solicitud !
                            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </div>
        </template>
        <v-dialog v-model="isActiveAfDialog" persistent transition="dialog-transition">
            <v-card>
                <v-card-title primary-title>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <span class="display-1 font-weight-bold">{{ rep.razonSocial }}</span>
                        </v-flex>
                        <v-flex xs12>
                            <span>
                                <em>Cod. Hab. {{ rep.code }} - {{ rep.documentType }} {{ rep.documentNumber }}</em>
                            </span>
                        </v-flex>
                        <v-flex xs12>
                            <v-layout row wrap justify-center>
                                <span class="title font-weight-medium">Archivo 'AF{{ paqCode }}'</span>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-xs fluid>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-data-table
                                    :headers="afHeader"
                                    :items="afDialog"
                                    hide-actions
                                    item-key="id"
                                >
                                    <template v-slot:items="props">
                                        <td class="text-xs-center">{{ props.item.numero_factura }}</td>
                                        <td class="text-xs-center">{{ props.item.valor_Neto | pesoFormat }}</td>
                                    </template>
                                </v-data-table>
                            </v-flex>
                            <v-flex xs12 mt-3>
                                <v-card>
                                    <v-container grid-list-xs>
                                        <v-layout row wrap>
                                            <v-flex xs4>
                                                <span class="title">Número de facturas: {{ afDialog.length }}</span>
                                            </v-flex>
                                            <VSpacer />
                                            <v-flex xs4>
                                                <span class="title">Valor de radicación: {{ totalValor | pesoFormat }}</span>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                    </v-card>
                                    <v-card v-if="parseInt(estado) === 6">
                                    <v-container grid-list-xs>
                                        <v-layout row wrap>
                                            <v-flex xs12>
                                                <v-label><h4>Motivo Rechazo</h4></v-label>
                                                <p>{{motivoRechazo}}</p>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-btn flat @click="isActiveAfDialog = !isActiveAfDialog">Cerrar</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn v-if="can('rips.pendientes.rechazar') && parseInt(estado) === 4" color="error" flat @click="setRejectMotivo()">Rechazar</v-btn>
                    <v-btn v-if="can('rips.pendientes.guardar') && parseInt(estado) === 4" color="success" flat @click="acceptPendingRips()">Guardar facturas</v-btn>
                    <v-btn v-if="can('rips.pendientes.guardar') && parseInt(estado) === 7" color="warning" flat @click="PendingRips()">Pendiente</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-layout row justify-center>
        <v-card>
<!--            <form @submit.prevent="getPaquetes">-->
            <v-container grid-list-md>
                <v-layout wrap>
                        <v-flex xs12 sm6 md6>
                            <v-menu
                                ref="menu1"
                                v-model="menu1"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                max-width="290px"
                                min-width="290px"
                                color="info"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="filter.fechaDesde"
                                        label="Fecha Desde"
                                        persistent-hint
                                        prepend-icon="event"
                                        v-on="on"
                                        color="info"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="filter.fechaDesde" no-title @input="menu1 = false" color="info"></v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs12 sm6 md6>
                            <v-menu
                                ref="menu2"
                                v-model="menu2"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                max-width="290px"
                                min-width="290px"
                                color="info"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="filter.fechaHasta"
                                        label="Fecha Desde"
                                        persistent-hint
                                        prepend-icon="event"
                                        v-on="on"
                                        color="info"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="filter.fechaHasta" no-title @input="menu2 = false" color="info"></v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs12 sm12 md12>
                            <v-autocomplete v-model="filter.prestadorId" :items="listProveedores"
                                            item-value="id" :item-text="listProveedores => listProveedores.Nit+' - '+listProveedores.Nombre" label="Nit-Prestador*" required>
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-btn color="info" type="submit" @click="getPaquetes">Filtrar</v-btn>
                        </v-flex>
                    </v-layout>
                </v-container>
<!--            </form>-->
        </v-card>
        </v-layout>
<v-divider></v-divider>
        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="listPaquetes" :search="search">
                <template v-slot:items="props">
                    <td class="text-xs-left">{{ props.item.id }}</td>
                    <td class="text-xs-left">{{ props.item.Nombre }}</td>
                    <td class="text-xs-left">{{ props.item.afs[0].codigo_entidad }}</td>
                    <td class="text-xs-left">{{ props.item.sede }}</td>
                    <td class="text-xs-rigth">{{ props.item.valor | pesoFormat }}</td>
                    <td class="text-xs-left">{{ props.item.created_at }}</td>
                    <td class="text-xs-left">{{ props.item.updated_at }}</td>
                    <td class="text-xs-center" v-if="props.item.partial"><v-chip text-color="white" color="deep-purple accent-4">Parcial</v-chip></td>
                    <td class="text-xs-center" v-else>
                        <v-chip color="warning" text-color="white" v-if="parseInt(props.item.estado_id) === 4">Por Autorizar</v-chip>
                        <v-chip color="success" text-color="white" v-else-if="parseInt(props.item.estado_id) === 7">Confirmado</v-chip>
                        <v-chip color="error" text-color="white" v-else-if="parseInt(props.item.estado_id) === 6">Rechazado</v-chip>
                    </td>
                    <td class="text-xs-center">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                        <v-btn flat icon color="primary" v-on="on" @click="loadAf(props.item)">
                            <v-icon dark>visibility</v-icon>
                        </v-btn>
                            </template>
                            <span>Detalle</span>
                        </v-tooltip>


                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn flat icon color="warning" v-on="on" v-if="parseInt(props.item.estado_id) !== 6" @click="print({type:'certificadoRips',id:props.item.id,tipo:(props.item.partial?2:1)})">
                                    <v-icon dark>file_download</v-icon>
                                </v-btn>
                            </template>
                            <span>Imprimir Certificado</span>
                        </v-tooltip>
                    </td>
                </template>
                <template v-slot:no-results>
                    <v-alert :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>
<script>
import Swal from "sweetalert2";
import {
    mapGetters
} from "vuex";
import moment from 'moment';
import {PrestadorService} from "../../../services";
export default {
    computed:{
        ...mapGetters(['can']),
    },
    filters: {
        pesoFormat: (valor) => `$${new Intl.NumberFormat().format(valor) || 0}`
    },
    data() {
        return {
            listProveedores: [],
            preload:false,
            menu1:false,
            menu2:false,
            filter:{
                fechaDesde:moment().format('YYYY-MM-')+'01',
                fechaHasta:moment().format('YYYY-MM-')+moment().daysInMonth(),
                prestadorId:null
            },
            id:null,
            parcialFacturas:null,
            motivoRechazo:'',
            estado:null,
            dialog:false,
            search:"",
            dialogReps:false,
            isActiveAfDialog:false,
            messages:[],
            afDialog:[],
            afHeader: [
                {
                    text: "Número de factura",
                    align: "center",
                    value: "billNumber",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Valor neto",
                    align: "center",
                    value: "netoValue",
                    width: "5px",
                    sortable: false,
                    fixed: true
                }
            ],
            headers: [
                {
                    text: '# Radicado',
                    align: 'left',
                    sortable: false,
                    value: 'id'
                },
                {
                    text: 'Paquete',
                    align: 'left',
                    sortable: false,
                    value: 'Nombre'
                },
                {
                    text: 'Entidad',
                    align: 'left',
                    sortable: false,
                    value: 'codigo_entidad'
                },
                {
                    text: 'Prestador',
                    align: 'left',
                    sortable: false,
                    value: 'sede'
                },
                {
                    text: 'Valor',
                    align: 'rigth',
                    sortable: false,
                    value: 'valor'
                },
                {
                    text: 'Fecha Carga',
                    align: 'rigth',
                    sortable: false,
                    value: 'created_at'
                },
                {
                    text: 'Fecha Auditoria',
                    align: 'rigth',
                    sortable: false,
                    value: 'update_at'
                },
                {
                    text: 'Estado',
                    align: 'center',
                    sortable: false,
                    value: ''
                },
            ],
            listPaquetes:[],
            rep:{},
            paqCode: '',
            totalValor: 0,
        }
    },
    methods:{
        getPaquetes(){
            this.preload = true;
            axios.post('/api/rips/getRadicados',this.filter).then(res => {
                this.listPaquetes = res.data;
                this.preload = false;
            }).catch(e =>{
                this.preload = false;
            })
        },
        loadAf(item){
            this.motivoRechazo = item.motivo;
            this.parcialFacturas = item.parcial
            this.estado = item.estado_id;
            this.id = item.id;
            this.rep.razonSocial = item.sede;
            this.rep.code = item.Codigo_habilitacion;
            this.rep.documentType = 'NIT';
            this.rep.documentNumber = item.Nit;
            this.paqCode = item.Nombre
            this.afDialog = item.afs.sort()
            this.totalValor = item.valor
            this.isActiveAfDialog = true;
        },
        print: async function (pdf) {
            this.preload = true;
            await axios.post("/api/pdf/print-pdf", pdf, {
                responseType: "arraybuffer",
            }).then(async (res) => {
                let blob = new Blob([res.data], {
                    type: "application/pdf",
                });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                window.open(link.href, "_blank");
                this.preload = false;
            }).catch((err) => {
                this.preload = false;
                console.log(err.response)
            });
        },
        acceptPendingRips(){
            this.preload = true;
            Swal.fire({
                title: 'Esta seguro de aceptar los archivos RIPS?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#4CAF50",
                cancelButtonColor: "#FF5252",
                confirmButtonText: "SI",
                cancelButtonText: "NO"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    axios.get('/api/rips/aceptarRips/'+this.id).then(res => {
                        Swal.fire({
                            title: res.data.message,
                            icon: 'success',
                        })
                        this.isActiveAfDialog = false;
                        this.getPaquetes();
                        this.preload = false;
                    }).catch(e => {
                        this.preload = false;
                    })
                }
            })
        },
        async setRejectMotivo(){
            this.preload = true;
            Swal.fire({
                title: 'Esta seguro de rechazar los archivos RIPS?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#4CAF50",
                cancelButtonColor: "#FF5252",
                confirmButtonText: "SI",
                cancelButtonText: "NO",
                input: 'textarea',
                inputPlaceholder: 'Motivo rechazo',
                inputAttributes: {
                    'aria-label': 'Motivo rechazo'
                },
            }).then(async (result) => {
                if (result.isConfirmed) {
                    if(result.value){
                        axios.post('/api/rips/rechazarRips/'+this.id,{motivo:result.value}).then(res => {
                            Swal.fire({
                                title: res.data.message,
                                icon: 'success',
                            })
                            this.isActiveAfDialog = false;
                            this.getPaquetes();
                            this.preload = false;
                        }).catch(e => {
                            this.preload = false;
                        })
                    }else{
                        Swal.fire({
                            title: 'El campo "motivo es requerido"',
                            icon: 'error',
                        })
                        this.preload = false;
                    }
                }else{
                    this.preload = false;
                }
            })
        },
        PendingRips(){
            this.preload = true;
            Swal.fire({
                title: 'Esta seguro de devolver a pendiente los archivos RIPS?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#4CAF50",
                cancelButtonColor: "#FF5252",
                confirmButtonText: "SI",
                cancelButtonText: "NO"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    axios.get('/api/rips/pendienteRips/'+this.id).then(res => {
                        Swal.fire({
                            title: res.data.message,
                            icon: 'success',
                        })
                        this.isActiveAfDialog = false;
                        this.getPaquetes();
                        this.preload = false;
                    }).catch(e => {
                        this.preload = false;
                    })
                }
            })

        },
        async fetchProveedores() {
            axios.get('/api/prestador/all').then(res => {
                this.listProveedores = res.data;
                console.log(res.data)
            }).catch(e => {
                console.log(e.response)
            })
        }
    },
    created() {
        this.fetchProveedores();
        this.getPaquetes();
    }
}
</script>
