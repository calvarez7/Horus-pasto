<template>
    <v-layout row wrap>
        <v-flex xs4>
            <v-select v-model="tipo"
                      label="Tipo" :items="[{id:0,Nombre:'MEDICAMENTOS'},{id:1,Nombre:'SERVICIOS'},{id:2, Nombre: 'OPTOMETRIA'}]" item-text="Nombre"
                      item-value="id" @change="historicoOrdenes = []" hide-details>
            </v-select>
        </v-flex>
        <v-flex xs4>
            <v-text-field v-model="cedula" label="Cédula" outlined></v-text-field>
        </v-flex>
        <v-flex xs4>
            <v-btn color="primary" round @click="find()">Buscar</v-btn>
        </v-flex>
        <template>
            <template>
                <div class="text-center">
                    <v-dialog v-model="preload" persistent width="800">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Tranquilo procesamos tu solicitud !
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </div>
            </template>
            <v-layout wrap>
                <v-flex>
                    <v-card v-show="tipo === 0">
                        <v-card-title>
                            <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line
                                          hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headersMedicamentos" :items="historicoOrdenes" item-key="id" :search="search">
                            <template v-slot:items="props">
                                <tr>
                                    <td>{{props.item.Orden_id}}</td>
                                    <td>{{props.item.paginacion}}</td>
                                    <td>{{ props.item.Fechaorden?props.item.Fechaorden.substring(0,10):''}}</td>
                                    <td>{{props.item.Codigo}}</td>
                                    <td>{{props.item.Nombre}}</td>
                                    <td>{{props.item.Via}}</td>
                                    <td>{{props.item.Cantidad_Dosis+" " + props.item.Unidad_Medida + " cada " + props.item.Frecuencia + " " + props.item.Unidad_Tiempo}}</td>
                                    <td>{{props.item.Duracion+" Dias"}}</td>
                                    <td>{{props.item.Cantidad_Medico}}</td>
                                    <td>{{props.item.Cantidad_Mensual_Disponible}}</td>
                                    <td>{{props.item.Observacion}}</td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7'"><v-chip color="green" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center" v-else-if="props.item.Estado_id == '17'"><v-chip color="info" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center" v-else-if="props.item.Estado_id == '18' || props.item.Estado_id == '35' || props.item.Estado_id == '19' || props.item.Estado_id == '15'"><v-chip color="orange" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center" v-else><v-chip color="error" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center" v-else><v-chip color="primary" v-show="props.item.mipres" text-color="white">MIPRES</v-chip></td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == '25'"><v-btn color="orange" fab dark @click="formatoNegacion(props.item.id,'medicamentos')">
                                        <v-icon>mdi-download</v-icon>
                                    </v-btn></td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7' || props.item.Estado_id == '17'"><v-btn color="info" fab dark @click="imprimir(props.item.Orden_id)">
                                        <v-icon>mdi-download</v-icon>
                                    </v-btn></td>
                                    <td class="text-xs-center" ><v-btn color="info" fab dark @click="Anexo(props.item.id,'anexo3Medicamentos')">
                                        <v-icon>assignment_turned_in</v-icon>
                                    </v-btn></td>

                                    <td class="text-xs-center">
                                        <v-btn color="info" fab dark @click="formulaControl(props.item.id)">
                                        <v-icon>assignment_turned_in</v-icon>
                                    </v-btn></td>

                                </tr>
                            </template>
                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your search for
                                "{{ search }}" found no results.</v-alert>
                        </v-data-table>
                    </v-card>

                    <v-card v-show="tipo === 1">
                        <v-card-title>
                            <v-text-field v-model="search2" append-icon="search" label="Buscar..." single-line
                                          hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headersServicios" :items="historicoOrdenes" item-key="id" :search="search2" :expand="expand">
                            <template v-slot:items="props">
                                <tr>
<!--                                    <td>{{props.item}}</td>-->
                                    <td class="text-xs-center" @click="props.expanded = !props.expanded" v-if="props.item.cancelacion === 'SI'"><v-chip label color="error" text-color="white">Cancelada</v-chip></td>
                                    <td class="text-xs-center" @click="props.expanded = !props.expanded" v-else-if="props.item.cancelacion === 'NO'"><v-chip label color="success" text-color="white">ASISTENCIA</v-chip></td>
                                    <td class="text-xs-center" @click="props.expanded = !props.expanded" v-else-if="props.item.cancelacion === 'INASISTENCIA'"><v-chip label color="warning" text-color="white">Inasistencia</v-chip></td>
                                    <td class="text-xs-center" @click="props.expanded = !props.expanded" v-else-if="props.item.cancelacion === 'nocontactado'"><v-chip label color="warning" text-color="white">No Contactado</v-chip></td>
                                    <td v-else></td>
                                    <td>{{props.item.orden}}</td>
                                    <td>{{props.item.FechaOrdenamiento.substring(0,10)}}</td>
                                    <td>{{props.item.Fechaorden.substring(0,10)}}</td>
                                    <td>{{props.item.Codigo}}</td>
                                    <td>{{props.item.Nombre}}</td>
                                    <td>{{props.item.Cantidad}}</td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7'"><v-chip color="green" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center" v-else-if="props.item.Estado_id == '17'"><v-chip color="info" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center" v-else-if="props.item.Estado_id == '18' || props.item.Estado_id == '35' || props.item.Estado_id == '19' || props.item.Estado_id == '15'"><v-chip color="orange" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center" v-else><v-chip color="error" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center"><v-chip color="primary" v-show="props.item.anexo3" text-color="white">ANEXO 3</v-chip></td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == '25'"><v-btn color="orange" fab dark @click="formatoNegacion(props.item.id,'servicios')">
                                        <v-icon>mdi-download</v-icon>
                                    </v-btn></td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7' || props.item.Estado_id == '17'"><v-btn color="info" fab dark @click="imprimirServicio(props.item)">
                                        <v-icon>mdi-download</v-icon>
                                    </v-btn></td>
                                    <td v-else></td>
                                    <td class="text-xs-center" ><v-btn color="info" fab dark @click="Anexo(props.item.id,'anexo3Servicios')">
                                        <v-icon>assignment_turned_in</v-icon></v-btn></td>

                                    <td class="text-xs-center" ><v-btn color="success" fab dark @click="imprimirRecomendacion(props.item.Codigo)">
                                        <v-icon>assignment</v-icon></v-btn></td>
                                </tr>
                            </template>
                            <template v-slot:expand="props">
                                <v-card flat>
                                    <table>
                                        <tr bgcolor="d3d3d3" v-show="props.item.cancelacion">
                                                <td><strong>SEDE: </strong>{{props.item.Sede_Nombre}}</td>
                                                <td v-show="props.item.cancelacion === 'NO'"><strong>FECHA SOLICITADA: </strong>{{props.item.fecha_solicitada}}</td>
                                                <td v-show="props.item.cancelacion === 'NO'"><strong>FECHA SUGERIDA: </strong>{{props.item.fecha_sugerida}}</td>
                                                <td v-show="props.item.cancelacion === 'NO'"><strong>FECHA RESULTADO: </strong>{{props.item.fecha_resultado}}</td>
                                                <td v-show="props.item.cancelacion === 'SI'"><strong>FECHA CANCELACION: </strong>{{props.item.fecha_cancelacion}}</td>
                                                <td v-show="props.item.cancelacion === 'SI'"><strong>MOTIVO: </strong>{{props.item.motivo}}</td>
                                                <td v-show="props.item.cancelacion === 'SI'"><strong>CAUSA: </strong>{{props.item.causa}}</td>
                                                <td><strong>RESPONSABLE: </strong>{{props.item.responsable}}</td>
                                                <td><strong>OBSERVACIONES: </strong>{{props.item.observaciones}}</td>
                                        </tr>
                                        <tr bgcolor="d3d3d3" v-show="props.item.cirujano">
                                            <td><strong>CIRUJANO: </strong>{{props.item.cirujano}}</td>
                                            <td><strong>ESPECIALIDAD: </strong>{{props.item.especialidad}}</td>
                                            <td><strong>FECHA PREANESTESIA: </strong>{{props.item.fecha_Preanestesia}}</td>
                                            <td><strong>FECHA CIRUGÍA: </strong>{{props.item.fecha_cirugia}}</td>
                                            <td><strong>FECHA EJECUCIÓN: </strong>{{props.item.fecha_ejecucion}}</td>
                                        </tr>
                                    </table>
                                </v-card>
                            </template>

                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your search for
                                "{{ search }}" found no results.</v-alert>
                        </v-data-table>
                    </v-card>
                    <v-card v-show="tipo === 2">
                        <v-card-title>
                            <v-text-field v-model="search2" append-icon="search" label="Buscar..." single-line
                                          hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headersOptometria" :items="historicoOrdenes" item-key="id" :search="search2" :expand="expand">
                            <template v-slot:items="props">
                                <tr>
                                    <td class="text-xs-center">{{props.item.orden}}</td>
                                    <td class="text-xs-center">{{props.item.Fechaorden.substring(0,10)}}</td>
                                    <td class="text-xs-center">{{props.item.tipo_lente}}</td>
                                    <td class="text-xs-center">{{props.item.Observacion}}</td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7'"><v-chip color="green" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center" v-else-if="props.item.Estado_id == '17'"><v-chip color="info" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center" v-else-if="props.item.Estado_id == '18' || props.item.Estado_id == '35' || props.item.Estado_id == '19' || props.item.Estado_id == '15'"><v-chip color="orange" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center" v-else><v-chip color="error" text-color="white">{{ props.item.Estado }}</v-chip></td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7' || props.item.Estado_id == '17'"><v-btn color="info" fab dark @click="imprimirOptometria(props.item.orden)">
                                        <v-icon>mdi-download</v-icon>
                                    </v-btn></td>
                                </tr>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>
            </v-layout>
        </template>
    </v-layout>
</template>
<script>
    export default {
        name: "HistoricoGlobalMedicamentos",
        data() {
            return {
                tipo:0,
                cedula: '',
                expand: true,
                preload: false,
                historicoOrdenes: [],
                search: "",
                search2: "",
                headersMedicamentos: [
                    {
                        text: "# Orden",
                        align: 'center',
                        value: "Orden_id"
                    },{
                        text: "Paginacion",
                        align: 'center',
                        sortable: false,
                        value: "paginacion"
                    },{
                        text: "Fecha orden",
                        align: 'center',
                        sortable: false,
                        value: "Fechaorden"
                    },{
                        text: "Codigo",
                        align: 'center',
                        value: "Codigo"
                    },{
                        text: "Nombre",
                        align: 'center',
                        value: "Nombre"
                    },{
                        text: "Via Admin",
                        align: 'center',
                        sortable: false,
                        value: "paginacion"
                    },{
                        text: "Dosificación",
                        align: 'center',
                        sortable: false,
                        value: "tipo"
                    },{
                        text: "Duración",
                        align: 'center',
                        sortable: false,
                        value: "estado"
                    },{
                        text: "Dosis Totales",
                        align: 'center',
                        sortable: false,
                        value: "usuario"
                    },{
                        text: "No Total a Dispensar",
                        align: 'center',
                        sortable: false,
                    },{
                        text: "Observaciones",
                        align: 'center',
                        sortable: false
                    },{
                        text: "Estado",
                        align: 'center',
                        value: 'Estado'
                    },{
                        text: "Imprimir Orden",
                        align: 'center',
                        sortable: false
                    },{
                        text: "Imprimir Anexo 3",
                        align: 'center',
                        sortable: false
                    },{
                        text: "Formula control especial",
                        align: 'center',
                        sortable: false
                    }
                ],
                headersServicios:[
                    {
                        text: "",
                        align: 'center',
                        sortable: false,
                        value: ""
                    },
                    {
                        text: "# Orden",
                        align: 'center',
                        value: "orden"
                    },{
                        text: "Fecha orden",
                        align: 'center',
                        sortable: false,
                        value: "FechaOrdenamiento"
                    },{
                        text: "Postfechado",
                        align: 'center',
                        sortable: false,
                        value: "Fechaorden"
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
                        value: "cantidad"
                    },{
                        text: "Estado",
                        align: 'center',
                        value: "Estado"
                    },{
                        text: "",
                        align: 'center',
                        sortable: false
                    },{
                        text: "Imprimir Orden",
                        align: 'center',
                        sortable: false
                    },{
                        text: "Imprimir Anexo 3",
                        align: 'center',
                        sortable: false
                    },{
                        text: "Imprimir Recomendaciones",
                        align: 'center',
                        sortable: false
                    }
                ],
                headersOptometria:[
                    {
                        text: "# Orden",
                        align: 'center',
                        value: "orden"
                    },{
                        text: "Fecha orden",
                        align: 'center',
                        sortable: false,
                        value: "FechaOrdenamiento"
                    },{
                        text: "Tipo Lente",
                        align: 'center',
                        value: "tipo_lente"
                    },{
                        text: "Observación",
                        align: 'center',
                        value: "tipo_lente"
                    },{
                        text: "Estado",
                        align: 'center',
                        value: "Estado"
                    },{
                        text: "Imprimir Orden",
                        align: 'center',
                        sortable: false
                    }
                ]
            }
        },
        methods:{
            find(){
                this.preload = true;
                if(!this.cedula){
                    this.historicoOrdenes = [];
                    this.preload = false;
                    return;
                }
                this.historicoOrdenes = [];
                if(this.tipo === 0){
                    axios.get('/api/autorizacion/ordenes/'+this.cedula).then(res => {
                        this.historicoOrdenes = res.data;
                        this.preload = false;

                    }).catch(e => {
                        console.log(e);
                        this.preload = false;
                    })
                }else if(this.tipo === 1){
                    axios.get('/api/autorizacion/ordenes/servicios/'+this.cedula).then(res => {
                        this.historicoOrdenes = res.data;
                        this.preload = false;

                    }).catch(e => {
                        console.log(e);
                        this.preload = false;
                    })
                }else{
                     axios.get('/api/autorizacion/ordenes/optometria/'+this.cedula).then(res => {
                        this.historicoOrdenes = res.data;
                        this.preload = false;

                    }).catch(e => {
                        console.log(e);
                        this.preload = false;
                    })
                }
            },
            async formatoNegacion(id,tipo){
                    const pdf = {
                        type: 'Negacion',
                        id: id,
                        tipos: tipo,
                    }
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
                        })
                        .catch(err => console.log(err.response));
                },
            async Anexo(id,tipo){
                const pdf = {
                    type: 'Anexo',
                    id: id,
                    tipos: tipo,
                }
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
                    })
                    .catch(err => console.log(err.response));
            },
            imprimir(orden){
                //this.preload = true;
                if(this.tipo === 0){
                    axios.get('/api/orden/getAllArticulosOrden/'+ orden).then(res => {
                        let hojas = {};
                        let hojasMipres = {};

                        res.data.forEach(s => {
                            hojas[s.Estado] = {'orden_id':s.Orden_id,type: "formula",medicamentos:[],Num_Doc:s.cedula,mensaje:((s.Estado === 'Activo' || s.Estado === 'Confirmada')&& s.estadoOrden === '18'?'Pendiente':s.Estado)};
                            hojasMipres[s.Estado] = {'orden_id':s.Orden_id,type: "formula",medicamentos:[],Num_Doc:s.cedula,mensaje:'M I P R E S'};
                        });
                        res.data.forEach(s => {
                            for (const key in hojas) {
                                if (s.Estado == key) {
                                    if(!s.mipres){
                                        hojas[s.Estado].medicamentos.push({
                                            id: s.codesumi_id,
                                            nombre: s.medicamento,
                                            Codigo: s.Codigo,
                                            Cantidadosis: s.Cantidadosis,
                                            Unidadmedida: s.Unidadmedida,
                                            Via: s.Via,
                                            Frecuencia: s.Frecuencia,
                                            Unidadtiempo: s.Unidadtiempo,
                                            Duracion: s.Duracion,
                                            Cantidadmensual: s.Cantidadpormedico,
                                            NumMeses: s.NumMeses,
                                            Observacion: s.Observacion,
                                            Requiere_autorizacion: s.Requiere_Autorizacion,
                                            Cantidadpormedico: s.Cantidadmensualdisponible,
                                            Auth_Nota: s.nota_autorizacion,
                                        });
                                    }else{
                                        hojasMipres[s.Estado].medicamentos.push({
                                            id: s.codesumi_id,
                                            nombre: s.medicamento,
                                            Codigo: s.Codigo,
                                            Cantidadosis: s.Cantidadosis,
                                            Unidadmedida: s.Unidadmedida,
                                            Via: s.Via,
                                            Frecuencia: s.Frecuencia,
                                            Unidadtiempo: s.Unidadtiempo,
                                            Duracion: s.Duracion,
                                            Cantidadmensual: s.Cantidadpormedico,
                                            NumMeses: s.NumMeses,
                                            Observacion: s.Observacion,
                                            Requiere_autorizacion: s.Requiere_Autorizacion,
                                            Cantidadpormedico: s.Cantidadmensualdisponible,
                                            Auth_Nota: s.nota_autorizacion,
                                        });
                                    }

                                }
                            }
                        });
                        for ( const key in hojas){
                            if(hojas[key].medicamentos.length){
                                this.getPDF(hojas[key]);
                            }
                            if(hojasMipres[key].medicamentos.length){
                                this.getPDF(hojasMipres[key]);
                            }
                        }
                        this.preload = false;
                    }).catch(e => {
                        console.log(e);
                        this.preload = false;
                    });
                }else{
                    axios.get('/api/orden/getAllServiceOrden/'+orden).then(res => {
                        let hojas = {};
                        let hojasAnexo3= {};
                        res.data.forEach(s => {
                            hojas[s.Estado] = {'orden_id':s.Orden_id,mensaje:'N O  V A L I D A',type: "otros",servicios:[],Num_Doc:s.cedula};
                            hojasAnexo3[s.Estado] = {'orden_id':s.Orden_id,mensaje:'A N E X O  3',type: "otros",servicios:[],Num_Doc:s.cedula};
                        });


                        res.data.forEach(s => {
                            for (const key in hojas) {
                                if (s.Estado == key) {
                                    if(!s.anexo3){
                                        hojas[s.Estado].servicios.push({
                                            codigo: s.Codigo,
                                            nombre: s.Nombre,
                                            cantidad: s.Cantidad,
                                            observacion:s.Observacionorden,
                                            ubicacion: s.ubicacion,
                                            direccion: s.direccion,
                                            telefono: s.telefono,
                                        });
                                    }else{
                                        hojasAnexo3[s.Estado].servicios.push({
                                            codigo: s.Codigo,
                                            nombre: s.Nombre,
                                            cantidad: s.Cantidad,
                                            observacion:s.Observacionorden,
                                            ubicacion: s.ubicacion,
                                            direccion: s.direccion,
                                            telefono: s.telefono,
                                        });
                                    }
                                }
                            }
                        });



                        for ( const key in hojas){
                            if(hojas[key].servicios.length){
                                this.getPDF(hojas[key]);
                            }
                            if(hojasAnexo3[key].servicios.length){
                                this.getPDF(hojasAnexo3[key]);
                            }
                        }
                        this.preload = false;
                    }).catch(e => {
                        console.log(e);
                        this.preload = false;
                    })
                }

            },
            async getPDF(pdf) {
                this.preload = true;
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
                        this.preload = false;
                    })
                    .catch(err => {console.log(err.response);this.preload = false;});

            },
            imprimirRecomendacion(codigo){
                axios.get("/api/orden/recomendaciones/"+codigo).then(res => {
                    console.log(res.data);
                    // window.open(res.data.file[0].nombre)
                    if(res.data.file.length === 0){
                        this.$alerError('Servicio sin recomendación')
                    }else{
                        res.data.file.forEach(s => {
                            const a = document.createElement('a')
                            a.href = s.nombre
                            a.download = s.nombre.split('/').pop()
                            document.body.appendChild(a)
                            a.click()
                            document.body.removeChild(a)
                        })
                    }
                })
            },
            imprimirServicio(servicio){
                this.preload = true;
                const pdf = {
                    orden:servicio.orden,
                    type:'otros',
                    servicios:[{identificador:servicio.id}]
                }
                this.getPDF(pdf);

            },

            imprimirOptometria(data){
                this.preload = true;
                const pdf = {
                        type: "optometriaOrden",
                        id: data,
                }
                this.getPDF(pdf);

            },

            formulaControl(id){
                this.preload = true;
                const pdf= {
                    type: "formulaMedicamentos",
                    id: id
                }
                axios.post("/api/pdf/print-pdf", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                        this.preload = false;
                    })
                    .catch(err => {console.log(err.response);this.preload = false;});
            }
        },
    }
</script>
