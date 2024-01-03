<template>
    <v-layout>
        <v-flex xs12>
            <v-card>
                <v-dialog v-model="dialog" max-width="1000px">
                    <v-card-title class="headline grey lighten-2">Detalle Esquemas</v-card-title>
                    <v-data-table :headers="headersDetalle" :items="getDetallesquema">
                        <template v-slot:items="props">
                            <td class="text-xs-left">{{ props.item.id }}</td>
                            <td class="text-xs-left">{{ props.item.Nombre }}</td>
                            <td class="text-xs-left">{{ props.item.dosis }}</td>
                            <td class="text-xs-left">{{ props.item.unidadMedida }}</td>
                            <td class="text-xs-left">{{ props.item.indiceDosis }}</td>
                            <td class="text-xs-left">{{ props.item.via }}</td>
                            <td class="text-xs-left">{{ props.item.observaciones }}</td>
                            <td class="text-xs-center"><v-flex xs12 sm3>
                                <v-btn flat icon color="primary" @click="editSchemaDetails(props.item)">
                                    <v-icon>edit</v-icon>
                                </v-btn>
                            </v-flex></td>
                        </template>
                    </v-data-table>
                </v-dialog>
                <v-toolbar flat color="white">
                    <v-toolbar-title>
                        <v-btn color="info" @click="modalEsquema=true">Crear Esquema</v-btn>
                    </v-toolbar-title>
                    <v-toolbar-title>
                        <v-btn color="info" @click="modalEsquemaDetalle=true">Crear Detalle Esquema</v-btn>
                    </v-toolbar-title>
                    <v-divider class="mx-2" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <template>
                    <v-layout row justify-center>
                        <v-dialog v-model="modalEsquemaDetalle" persistent max-width="1000px">
                            <v-card>
                                <form @submit.prevent="saveSchemaDetail">
                                <v-card-title>
                                    <span class="headline">{{ titleDetailsSchema?titleDetailsSchema:"Parametrización Detalles Esquema" }}</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs12 sm8>
                                                <v-autocomplete v-model="formDetalleEsquema.codesumi" :items="fullcodesumis" item-value="id" item-text="fullcode" label="Medicamentos*"
                                                    required></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm2>
                                                <v-autocomplete
                                                    :items="esque" v-model="formDetalleEsquema.esquema"
                                                    label="Esquema" item-text="nombreEsquema" item-value="id" required></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm2>
                                                <v-autocomplete
                                                    :items="unidadMedida" v-model="formDetalleEsquema.unidadMedida"
                                                    label="Unidad Medida" required></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-autocomplete
                                                    :items="indiceDosis" v-model="formDetalleEsquema.indiceDosis"
                                                    label="Indice Dosis" required></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs6 sm3 md2>
                                                <v-autocomplete
                                                    :items="via" v-model="formDetalleEsquema.via"
                                                    label="Via" required></v-autocomplete>
                                            </v-flex>

                                            <v-flex xs6 sm3 md2>
                                                <v-text-field type="text" v-model="formDetalleEsquema.dosis"
                                                              label="Dosis" required></v-text-field>
                                            </v-flex>

                                            <v-flex xs12 sm6 md4>
                                                    <v-text-field type="text" v-model="formDetalleEsquema.frecuencia"
                                                        label="Frecuencia" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-text-field type="number" v-model="formDetalleEsquema.cantidadAplicaciones" label="Cantidad Aplicaciones" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-text-field label="Dias Aplicaciones" v-model="formDetalleEsquema.diasAplicaciones" type="text"> required</v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-text-field
                                                    label="Descripción Dosis" v-model="formDetalleEsquema.descripcionDosis"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-textarea
                                                    label="Observaciones" v-model="formDetalleEsquema.observaciones"
                                                ></v-textarea>

                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" flat @click="clearFormDetails(),modalEsquemaDetalle = false,titleDetailsSchema = ''">Cancelar</v-btn>
                                    <v-btn color="blue darken-1" flat type="submit">Guardar</v-btn>
                                </v-card-actions>
                                </form>
                            </v-card>
                        </v-dialog>
                    </v-layout>

                    <v-layout row justify-center>
                        <v-dialog v-model="modalEsquema" persistent max-width="1000px">
                            <v-card>
                                <form @submit.prevent="saveSchema">
                                    <v-card-title>
                                        <span class="headline">{{ titleSchema?titleSchema:"Parametrización Esquema" }}</span>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container grid-list-md>
                                            <v-layout wrap>
                                                <v-flex xs12 sm6 md4>
                                                    <v-text-field type="text" v-model="formEsquema.nombreEsquema"
                                                                  label="Nombre Esquema" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-text-field type="text" v-model="formEsquema.abrevEsquema"
                                                                  label="Abreviatura" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-text-field label="Ciclos" v-model="formEsquema.ciclos" type="number"> required</v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md4>
                                                    <v-text-field label="Repeticion Frecuencia" v-model="formEsquema.frecuenciaRepeat" type="number"> required</v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm12 md12>
                                                    <v-textarea
                                                        label="Biografia" v-model="formEsquema.biografia"
                                                    ></v-textarea>

                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" flat @click="clearForm(),modalEsquema = false,titleSchema = '',accion = ''">Cancelar</v-btn>
                                        <v-btn color="blue darken-1" flat type="submit">Guardar</v-btn>
                                    </v-card-actions>
                                </form>
                            </v-card>
                        </v-dialog>
                    </v-layout>

                </template>
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs12 md12 lg12>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-data-table :search="search" :headers="headers" :items="getEsquema">
                    <template v-slot:items="props">
                        <td class="text-xs-left"><a href="javascript:void(0)" @click="cargarDetalleEsquema(props.item.detallesquemas)">{{ props.item.id }}</a></td>
                        <td class="text-xs-left">{{ props.item.abrevEsquema }}</td>
                        <td class="text-xs-left">{{ props.item.nombreEsquema }}</td>
                        <td class="text-xs-left">{{ props.item.ciclos }}</td>
                        <td class="text-xs-left">{{ props.item.frecuenciaRepeat }}</td>
                        <td class="text-xs-left">{{ props.item.biografia }}</td>
                        <td class="text-xs-center"><v-flex xs12 sm3>
                            <v-btn flat icon color="primary" @click="editSchema(props.item)">
                                <v-icon>edit</v-icon>
                            </v-btn>
                        </v-flex></td>
                    </template>
                </v-data-table>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
import { TipoOrdenService, DetalleEsquemaService } from '../../../services'
    export default {
        data() {
            return {
                accion: '',
                titleSchema: '',
                titleDetailsSchema: '',
                idSchema: null,
                idSchemaDetails: null,
                getEsquema: [],
                getDetallesquema: [],
                modalEsquemaDetalle: false,
                modalEsquema: false,
                codigosSumi: [],
                esque:[],
                search: "",
                indiceDosis:["ISC","Fija", "N/A"],
                via:["IM","IV","SC","VO", "N/A"],
                unidadMedida:["MG","ML","UI", "UND"],
                detalleMedicamento: [],
                totalDesserts: 0,
                loading: true,
                pagination: {},
                dialog:false,
                headers: [{
                        text: "Id",
                        align: "left",
                        value: "id"
                    },
                    {
                        text: "Abreviatura",
                        value: "abrevEsquema"
                    },
                    {
                        text: "Nombre",
                        value: "nombreEsquema"
                    },
                    {
                        text: "Ciclos",
                        value: "ciclos"
                    },
                    {
                        text: "Frecuancia Repetición",
                        value: "frecuenciaRepeat"
                    },
                    {
                        text: "Biografia",
                        value: "biografia"
                    },
                    {
                        text: "",
                        value: ""
                    }
                ],
                headersDetalle:[{
                    text: "Id",
                    value: "id"
                    },
                    {
                    text: "Nombre",
                    value: "nombre"
                    },
                    {
                        text: "Dosis Teorica",
                        value: "dosis"
                    },
                    {
                        text: "Unidad Medida",
                        value: "unidadMedida"
                    },

                    {
                        text: "Indice Dosis",
                        value: "indiceDosis"
                    },
                    {
                        text: "Vía",
                        value: "via"
                    },
                    {
                        text: "Observaciones",
                        value: "observaciones"
                    },
                ],
                formDetalleEsquema:{
                    codesumi: "",
                    esquema: "",
                    unidadMedida: "",
                    indiceDosis: "",
                    via: "",
                    frecuencia: "",
                    cantidadAplicaciones: "",
                    diasAplicaciones: "",
                    descripcionDosis: "",
                    observaciones: "",
                    dosis: ""
                },
                formEsquema:{
                    ciclos: "",
                    frecuenciaRepeat:"",
                    biografia:"",
                    nombreEsquema:"",
                    abrevEsquema:""
                }
            };
        },
        watch: {
            pagination: {
                handler() {
                    this.getDataFromApi().then(data => {
                        this.desserts = data.items;
                        this.totalDesserts = data.total;
                    });
                },
                deep: true
            }
        },
        computed: {
            fullcodesumis(){
                return this.detalleMedicamento.map((codesumis) => {
                    return {
                        id: codesumis.Codesumi_id,
                        fullcode: `${codesumis.CodigoSumi} - ${codesumis.NombreSumi}`
                    }
                })
            }
        },
        mounted() {
            this.fetchCodigosSumi();
            this.fetchMedicamentos();
            this.fullEsquemas();
            this.fetchDetalleesquema();
        },
        methods: {

            fullEsquemas: async function (){
                try {
                    this.esque = await TipoOrdenService.getEnableEsquemas();
                } catch (error) {
                    console.log(error)
                }
            },
            fetchDetalleesquema: async function () {
                try {
                    this.getEsquema = await DetalleEsquemaService.getDetalleEsquema();
                } catch (error) {
                    console.log(error)
                }
            },
            fetchMedicamentos(){
                axios.get('/api/detallearticulo/all')
                    .then(res => {
                        this.detalleMedicamento = res.data
                        });
            },
            fetchCodigosSumi(){
                axios.get('/api/codesumi/all')
                    .then(res => {
                        this.codigosSumi = res.data
                    });
            },
            saveSchemaDetail(){
                if(this.accion){
                    axios.put('/api/esquemas/editSchema/'+this.idSchemaDetails,this.formDetalleEsquema)
                        .then(res =>{
                            this.modalEsquemaDetalle = false;
                            this.dialog = false;
                            this.fetchDetalleesquema();
                            this.$alerSuccess('Registro exitoso')
                            this.clearFormDetails()
                        })
                }else{
                    axios.post('/api/esquemas/save',this.formDetalleEsquema)
                    .then(res =>{
                        this.modalEsquemaDetalle = false;
                        this.fetchDetalleesquema();
                        this.$alerSuccess('Registro exitoso')
                        this.clearFormDetails()
                    })
                }

            },
            cargarDetalleEsquema:async function(detallesquemas){
                console.log(detallesquemas)
                this.getDetallesquema = detallesquemas;
                this.dialog = true
            },
            saveSchema(){
                if(this.accion){
                    axios.put('/api/esquemas/edit/'+this.idSchema,this.formEsquema)
                        .then(res => {
                            this.modalEsquema = false;
                            this.fullEsquemas();
                            this.fetchDetalleesquema();
                            this.$alerSuccess('Registro exitoso')
                            this.clearForm()
                        })
                }else{
                    axios.post("/api/esquemas/create",this.formEsquema)
                        .then(res => {
                            this.modalEsquema = false;
                            this.fullEsquemas();
                            this.fetchDetalleesquema();
                            this.$alerSuccess('Registro exitoso')
                            this.clearForm()
                        })
                }
            },
            editSchema(esquema){
                console.log(esquema)
                this.idSchema = esquema.id
                this.formEsquema.ciclos = esquema.ciclos;
                this.formEsquema.frecuenciaRepeat = esquema.frecuenciaRepeat;
                this.formEsquema.biografia = esquema.biografia;
                this.formEsquema.nombreEsquema = esquema.nombreEsquema;
                this.formEsquema.abrevEsquema = esquema.abrevEsquema;
                this.accion = "edit";
                this.titleSchema = "Editar Esquema";
                this.modalEsquema = true;
            },
            editSchemaDetails(esquemaDetalle) {
                this.idSchemaDetails = esquemaDetalle.id;
                this.formDetalleEsquema.codesumi = esquemaDetalle.codesumisId;
                this.formDetalleEsquema.esquema = parseInt(esquemaDetalle.esquemaId);
                this.formDetalleEsquema.unidadMedida = esquemaDetalle.unidadMedida.toUpperCase();
                this.formDetalleEsquema.indiceDosis = esquemaDetalle.indiceDosis;
                this.formDetalleEsquema.via = esquemaDetalle.via;
                this.formDetalleEsquema.frecuencia = esquemaDetalle.frecuencia;
                this.formDetalleEsquema.cantidadAplicaciones = esquemaDetalle.cantidadAplicaciones;
                this.formDetalleEsquema.diasAplicaciones = esquemaDetalle.diasAplicacion;
                this.formDetalleEsquema.descripcionDosis = esquemaDetalle.descripcionDosis;
                this.formDetalleEsquema.observaciones = esquemaDetalle.observaciones;
                this.formDetalleEsquema.dosis = esquemaDetalle.dosis;
                this.accion = "edit";
                this.titleDetailsSchema = "Editar Detalle Esquema";
                this.modalEsquemaDetalle = true;
            },
                clearForm(){
                this.formEsquema.ciclos = "";
                this.formEsquema.frecuenciaRepeat = "";
                this.formEsquema.biografia = "";
                this.formEsquema.nombreEsquema = "";
                this.formEsquema.abrevEsquema = "";
            },
            clearFormDetails(){
                this.formDetalleEsquema.codesumi = "";
                this.formDetalleEsquema.esquema = "";
                this.formDetalleEsquema.unidadMedida = "";
                this.formDetalleEsquema.indiceDosis = "";
                this.formDetalleEsquema.via = "";
                this.formDetalleEsquema.frecuencia = "";
                this.formDetalleEsquema.cantidadAplicaciones = "";
                this.formDetalleEsquema.diasAplicaciones = "";
                this.formDetalleEsquema.descripcionDosis = "";
                this.formDetalleEsquema.observaciones = "";
                this.formDetalleEsquema.dosis = "";
            }
        }
    };

</script>
<style>
</style>
