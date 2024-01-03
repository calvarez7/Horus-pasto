<template>
    <v-layout row wrap>
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
                <v-dialog v-model="dialogSolicitud" width="1000px">
                    <v-card>
                        <v-card-title>
                            <span class="text-h5">Solicitud Compra</span>
                        </v-card-title>
                        <v-card-text>
                            <v-data-table v-if="datosSolicitud.length > 0" :headers="headersSolicitud" :items="datosSolicitud" class="elevation-1" hide-headers hide-actions>
                                <template v-slot:items="props">
                                    <td class="text-xs-center">{{ props.item.codigo }}</td>
                                    <td class="text-xs-center">{{ props.item.Producto }}</td>
                                    <td class="text-xs-center">{{ props.item.titular }}</td>
                                    <td class="text-xs-center"><v-text-field v-model="props.item.cantidadSolicitada" type="number"/></td>
                                    <td class="text-xs-center"><v-btn small fab dark color="error" @click="eliminarEstimado(props.index)"><v-icon dark>close</v-icon></v-btn></td>

                                </template>
                            </v-data-table>
                    <v-flex xs12 pb-3>
                        <v-layout row wrap>
                            <v-spacer/>
                            <v-btn v-if="datosSolicitud.length > 0" color="success" @click="guardarSolicitud()" round>Guardar Solicitud</v-btn>
                            <v-spacer/>
                        </v-layout>
                    </v-flex>


                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error"  @click="dialogSolicitud = false">Cerrar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDetalle" width="1000px">
                    <v-card>
                        <v-card-title>
                            <h3>Pedido Final: <strong>{{datosCodesumi.pedido_final}}</strong></h3>
                        </v-card-title>
                        <v-card-text>
                            <v-data-table :headers="headersDetalle" :items="listaDetalles" class="elevation-1">
                                <template v-slot:items="props">
                                    <td class="text-xs-center">{{ props.item.id }}</td>
                                    <td class="text-xs-center">{{ props.item.codigo }}</td>
                                    <td class="text-xs-center">{{ props.item.Nombre }}</td>
                                    <td class="text-xs-center">{{ props.item.Producto }}</td>
                                    <td class="text-xs-center">{{ props.item.titular }}</td>
                                    <td class="text-xs-center">{{ props.item.Stock }}</td>
                                    <td class="text-xs-center">
                                        <v-text-field v-model="props.item.cantidadSolicitada"></v-text-field>
                                    </td>
                                    <td class="text-xs-center"><v-btn small fab dark color="success" @click="agregarSolicitud(props.item)"><v-icon dark>add</v-icon></v-btn></td>

                                </template>
                            </v-data-table>


                        </v-card-text>
                    </v-card>
                </v-dialog>
            </div>
        </template>
        <v-flex xs12>
            <v-card>
                    <v-layout row wrap>
                        <v-flex xs6 pl-2>
                            <v-autocomplete :items="listaBodegas" item-text="Nombre" return-object label="Bodega" required v-model="form.bodega"/>
                        </v-flex>
                        <v-flex xs4 pl-2>
                            <v-btn color="success" @click="generarDatos">Generar</v-btn>
                        </v-flex>
                        <v-flex xs12 pl-2>
                            <v-btn v-if="datosHistoricos.length > 0 && can('bodega.reposicion.exportar')" color="warning" @click="exportar">Exportar</v-btn>
                            <v-divider class="mx-12" inset vertical></v-divider>
                            <v-btn v-if="datosSolicitud.length > 0" round color="indigo" dark @click="dialogSolicitud = true">Solicitudes {{datosSolicitud.length}}</v-btn>
                        </v-flex>
                    </v-layout>
                        <v-card-title>
                            <v-text-field
                                v-model="buscar"
                                append-icon="search"
                                label="Buscar"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-card-title>
                    <v-data-table :headers="headers" :items="datosHistoricos.filter(s => s.reposicion === 'si')" class="elevation-1" :search="buscar">
                        <template v-slot:items="props">
                            <td class="text-xs-center">{{ props.item.Codigo }}</td>
                            <td class="text-xs-center">{{ props.item.Producto }}</td>
                            <td class="text-xs-center">{{props.item.promedio_mes }}</td>
                            <td class="text-xs-center">{{props.item.clasificacion_abc}}</td>
                            <td class="text-xs-center">{{props.item.stock_minimo}}</td>
                            <td class="text-xs-center">{{props.item.stock_maximo}}</td>
                            <td class="text-xs-center">{{props.item.actual}}</td>
                            <td class="text-xs-center">{{props.item.pedido_final }}</td>
                            <td class="text-xs-center"><v-btn small fab dark color="warning" @click="verDetalles(props.item)"><v-icon dark>visibility</v-icon></v-btn></td>
                            <!--                            <td class="text-xs-center"><v-btn small fab dark color="success" @click="agregarSolicitud(props.item)"><v-icon dark>add</v-icon></v-btn></td>-->

                        </template>
                    </v-data-table>
                <br>
            </v-card>
        </v-flex>
    </v-layout>
</template>
<script>
import moment from 'moment';
import {mapGetters} from 'vuex';
export default {
    name:"min_max",
    computed: {
        ...mapGetters(['can']),
    },
    data: () => {
        return {
            datosCodesumi: {},
            listaDetalles: [],
            dialogDetalle:false,
            dialogSolicitud:false,
            buscar:"",
            preload:false,
            totalDispensados:0,
            listaBodegas:[],
            datosHistoricos:[],
            datosSolicitud:[],
            form:{
                bodega:null
            },
            headersDetalle:[
                {
                    text: '#',
                    align: 'center',
                    sortable: true,
                    value: '',
                },
                {
                    text: 'Codigo',
                    align: 'center',
                    sortable: true,
                    value: '',
                },
                {
                    text: 'Descripción',
                    align: 'center',
                    sortable: true,
                    value: '',
                },
                {
                    text: 'Descripción Comercial',
                    align: 'center',
                    sortable: true,
                    value: '',
                },
                {
                    text: 'Titular',
                    align: 'center',
                    sortable: true,
                    value: '',
                },
                {
                    text: 'Existencias',
                    align: 'center',
                    sortable: true,
                    value: '',
                },
                {
                    text: 'Cantidad Requerida',
                    align: 'center',
                    sortable: true,
                    value: '',
                },
                {
                    text: '',
                    align: 'center',
                    sortable: true,
                    value: ''
                },
            ],
            headersSolicitud:[
                {
                    text: '#',
                    align: 'center',
                    sortable: true,
                    value: '',
                },
                {
                    text: 'Codigo',
                    align: 'center',
                    sortable: true,
                    value: 'Codigo',
                },
                {
                    text: 'Producto',
                    align: 'center',
                    sortable: true,
                    value: 'Producto'
                },
                {
                    text: 'Laboratorio',
                    align: 'center',
                    sortable: true,
                    value: 'Producto'
                },
                {
                    text: 'Cantidad',
                    align: 'center',
                    sortable: true,
                    value: 'Producto'
                },
                {
                    text: '',
                    align: 'center',
                    sortable: true,
                    value: ''
                },
            ],
            headers: [
                {
                    text: 'Codigo',
                    align: 'center',
                    sortable: true,
                    value: 'Codigo',
                },
                {
                    text: 'Producto',
                    align: 'center',
                    sortable: true,
                    value: 'Producto'
                },
                {
                    text: 'Promedio',
                    align: 'center',
                    sortable: true,
                    value: 'promedio_mes'
                },
                {
                    text: 'ABC',
                    align: 'center',
                    sortable: true,
                    value: 'clasificacion_abc'
                },
                {
                    text: 'Stock Minimo',
                    align: 'center',
                    sortable: true,
                    value: 'stock_minimo'
                },
                {
                    text: 'Stock Maximo',
                    align: 'center',
                    sortable: true,
                    value: 'stock_maximo'
                },
                {
                    text: 'Existencias',
                    align: 'center',
                    sortable: true,
                    value: 'actual'
                },
                {
                    text: 'Pedido Final',
                    align: 'center',
                    sortable: true,
                    value: 'pedido_final'
                },
                {
                    text: '',
                    align: 'center',
                    sortable: false,
                    value: ''
                }
            ],
            desserts: [
                {
                    name: 'Frozen Yogurt',
                    calories: 159,
                    fat: 6.0,
                    carbs: 24,
                    protein: 4.0,
                    iron: '1%'
                },
            ]
        }
    },
    mounted() {
        this.fetchBodegas();
    },
    methods:{
        fetchBodegas() {
            axios.get('/api/bodega/getBodegaByRole')
                .then(res => {
                    if (res.data.length > 0) {
                        this.listaBodegas = res.data
                    }
                })
        },
        generarDatos(){
            this.preload = true;
            if(!this.form.bodega){
                this.preload = false;
                return this.$alerError("La bodega es requerida");
            }
            axios.get('/api/bodega/min-max/'+this.form.bodega.id).then(res => {
                this.datosHistoricos = res.data.dispensados.map((s) => {
                    const promedioMes = Math.round(((parseInt(s.v6)*parseInt(s.v6))+
                        (parseInt(s.v5)*parseInt(s.v5))+
                        (parseInt(s.v4)*parseInt(s.v4))+
                        (parseInt(s.v3)*parseInt(s.v3))+
                        (parseInt(s.v2)*parseInt(s.v2))+
                        (parseInt(s.v1)*parseInt(s.v1)))/(
                        parseInt(s.v6)+
                        parseInt(s.v5)+
                        parseInt(s.v4)+
                        parseInt(s.v3)+
                        parseInt(s.v2)+
                        parseInt(s.v1)));
                    const totalGeneral = (parseInt(s.v6)+ parseInt(s.v5)+ parseInt(s.v4)+ parseInt(s.v3)+ parseInt(s.v2)+ parseInt(s.v1));
                    const promedioDia = Math.round(promedioMes/this.form.bodega.cobertura);
                    const valorMaximo = Math.max(parseInt(s.v6),parseInt(s.v5),parseInt(s.v4),parseInt(s.v3),parseInt(s.v2),parseInt(s.v1));
                    const valorMinimo = Math.min(parseInt(s.v6),parseInt(s.v5),parseInt(s.v4),parseInt(s.v3),parseInt(s.v2),parseInt(s.v1));
                    const puntoReposicion = Math.round((this.form.bodega.tiempo_reposicion*promedioMes)/this.form.bodega.cobertura);
                    const stockSeguridadCantidades = Math.round((this.form.bodega.stock_seguridad*promedioMes)/this.form.bodega.cobertura);
                    const stockMinimo = puntoReposicion+stockSeguridadCantidades;
                    const stockMaximo = Math.round(promedioMes+stockSeguridadCantidades);
                    const pedidoFinal =  Math.round(promedioMes+stockSeguridadCantidades+puntoReposicion-parseInt(s.actual))
                    let clasificacionAbc = s.abc?s.abc:"SIN CLASIFICACION";
                        return {
                            Codigo: s.Codigo,
                            Producto: s.Producto,
                            '6': s.v6,
                            '5': s.v5,
                            '4': s.v4,
                            '3': s.v3,
                            '2': s.v2,
                            '1': s.v1,
                            "promedio_mes": promedioMes,
                            "total_general": totalGeneral,
                            "clasificacion_abc": clasificacionAbc,
                            "promedio_dia": promedioDia,
                            "valor_maximo": valorMaximo,
                            "valor_minimo": valorMinimo,
                            "stock_seguridad": this.form.bodega.stock_seguridad,
                            "tiempo_reposicion": this.form.bodega.tiempo_reposicion,
                            "cobertura": this.form.bodega.cobertura,
                            "punto_reposicion": puntoReposicion,
                            "stock_seguridad_cantidades": stockSeguridadCantidades,
                            "stock_minimo": stockMinimo,
                            "stock_maximo": stockMaximo,
                            actual: s.actual,
                            "pedido_final": pedidoFinal,
                            "reposicion": (parseInt(s.actual) <= puntoReposicion?"si":"no"),
                            "critico": (s.critico?"SI":"No")
                        }
                });
                this.totalDispensados = res.data.total_dispensados;
                this.preload = false;
            }).catch(e => {
                this.preload = false;
            })
        },
        async exportar(){
            console.log(this.datosHistoricos);
            this.preload = true;
            try {
                const data = await axios.post('/api/rips/exportError',this.datosHistoricos,{
                    responseType: 'blob',
                })
                const blob = new Blob([data.data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.download = `ReposicionInventario${this.form.bodega.Nombre}${moment().format('YYYY-MM-DD')}.xlsx`;
                a.href = url;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                this.preload = false;
            }catch (error){
                console.log(error)
                this.preload = false;
            }
            this.preload = false;
        },
        agregarSolicitud(item){
            if(item.cantidadSolicitada === undefined || item.cantidadSolicitada <= 0){
                return this.$alerError('La "cantidad requerida" es requerida.');
            }
            if(parseInt(item.cantidadSolicitada) > parseInt(this.datosCodesumi.pedido_final)){
                return this.$alerError('La "cantidad requerida" es superios al pedido final.');
            }
            let data = {};
            for(const val in item){
              data[val] = item[val]
            }
            let objIndex = this.datosSolicitud.findIndex((obj => obj.id === data.id));
            if (objIndex === -1) {
                this.datosSolicitud.push(data);
            }else{
                return this.$alerError("El articulo ya se encuentra seleccionado");
            }
            this.dialogDetalle = false;
        },
        sendOrdenCompra() {

        },
        eliminarEstimado(item){
            this.datosSolicitud.splice(item, 1);
        },
        guardarSolicitud(){
            const valoresErrados = this.datosSolicitud.filter(s => s.cantidadSolicitada === '' || parseInt(s.cantidadSolicitada) <= 0)
            if(valoresErrados.length > 0){
                return this.$alerError("Hay articulos con valores incorrectos en la cantidad");
            }
            const articulos = this.datosSolicitud.map(s => {
                return{
                    Cantidadtotal:s.cantidadSolicitada,
                    bodegaArticulo:{
                        id:s.id
                    }
                }
            })

            this.preload = true
            axios.post('/api/bodega/' + this.form.bodega.id + '/ordencompra/create', {bodegarticulos:articulos})
                .then(res => {
                    swal({
                        title: `¡${res.data.message}!`,
                        text: "Número Solicitud: "+res.data.solicitud,
                        icon: "success",
                    });
                    let pdf = {
                        type: "ordenCompra",
                        id: res.data.solicitud
                    };
                    this.imprimir(pdf);
                    this.preload = false
                    this.datosSolicitud = [];
                    this.dialogSolicitud = false;
                }).catch(e =>{
                this.preload = false
            })
        },
        imprimir(pdf) {
            axios
                .post("/api/pdf/print-pdf", pdf, {
                    responseType: "arraybuffer"
                })
                .then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/pdf"
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");
                });
        },
        verDetalles(item){
            this.datosCodesumi = item;
            this.dialogDetalle = true;
            this.listaDetalles = [];
            this.preload = true;
            axios.get('/api/bodega/detallesCodesumisReposicion/'+item.Codigo+'/'+this.form.bodega.id).then(res => {
                this.listaDetalles = res.data
                this.listaDetalles[0].cantidadSolicitada = item.pedido_final
                this.preload = false;
            }).catch(e => {
                this.preload = false;
                console.log(e.response)
            })
        }
    }
}
</script>
