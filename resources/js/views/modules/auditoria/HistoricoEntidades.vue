<template>
    <v-layout>
        <v-flex shrink xs12 ml-12>
            <template>
                <v-dialog v-model="preload" persistent width="300">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Tranquilo procesamos tu solicitud !
                            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <v-layout wrap>
                    <v-flex xs12 md12 lg12>
                        <v-card>
                            <v-container>
                                <v-layout row wrap>
                                    <v-flex xs10 md10 lg10>
                                        <v-text-field
                                            label="Numero Identificación"
                                            v-model="numeroIdentificacion"
                                            outline
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-btn color="primary" round @click="buscarCobros">Buscar</v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card>
                    </v-flex>
                    <v-flex shrink xs12 ml-1>
                        <template>
                            <v-layout wrap>
                                <v-flex>
                                    <v-card>
                                        <v-data-table :headers="headers" :items="listCobros" :expand="expand" item-key="created_at">
                                            <template v-slot:items="props">
                                                <tr>
                                                    <td class="text-xs-center"><v-btn v-show="props.item.nombre == 'Otros Servicios'" color="success" @click="props.expanded = !props.expanded">Servicios</v-btn></td>
                                                    <td class="text-xs-center">{{ props.item.idOrden }}</td>
                                                    <td class="text-xs-center">{{ props.item.Fechaorden.substring(0,10) }}</td>
                                                    <td class="text-xs-center">{{ props.item.paciente }}</td>
                                                    <td class="text-xs-center">{{ props.item.nombre }}</td>
                                                    <td class="text-xs-center">{{ props.item.usuario_ordena }}</td>
                                                    <td class="text-xs-center" v-show="props.item.estado_id == 7 && props.item.cobroCita == 7">
                                                        Pagada <strong v-show="props.item.usuario_id"> ({{ props.item.usuario_cobra }})</strong>
                                                    </td>
                                                    <td class="text-xs-center" v-show="props.item.estado_id == 4 || props.item.cobroCita == 4"><v-chip color="red" text-color="white">Por Cobrar</v-chip></td>
                                                    <td class="text-xs-center">$ {{ props.item.valor }}</td>
                                                    <td class="text-xs-center">$ {{ props.item.valor_cita }}</td>
                                                    <td class="text-xs-center"><v-btn color="info" @click="generarCobro(props.item, 'formulas')">Formulas</v-btn></td>
                                                    <td class="text-xs-center"><v-btn color="info" @click="generarCobro(props.item,'recibos')">Recibos</v-btn></td>

                                                </tr>
                                            </template>
                                            <template v-slot:expand="props">
                                                <v-card flat>
                                                    <v-card-text>
                                                        <table>
                                                            <thead>
                                                            <tr>
                                                                <th><h2>Codigo</h2></th>
                                                                <th><h2>Nombre</h2></th>
                                                                <th><h2>Sede</h2></th>
                                                                <th><h2>Cantidad</h2></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody v-for="item in props.item.orden.cupordens">
                                                            <tr>
                                                                <td>{{item.Cup}}</td>
                                                                <td>{{item.Cup_Nombre}}</td>
                                                                <td>{{item.Sede_Nombre}}</td>
                                                                <td>{{item.Cup_Cantidad}}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </v-card-text>
                                                </v-card>
                                            </template>
                                        </v-data-table>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </template>
                    </v-flex>
                </v-layout>
            </template>
        </v-flex>
    </v-layout>
</template>
<style scoped>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }
    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
    tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
<script>
    export default {
        name: "historicoEntidades",
        components: {},
        data: () => ({
            expand: false,
            listCobros: [],
            numeroIdentificacion: null,
            dialog:false,
            idCobro: 0,
            preload: false,
            headers:[{
                text: '',
                align: 'center',
            },{
                text: 'Numero Orden',
                align: 'center',
            },{
                text: 'Fecha',
                align: 'center',
            },{
                text: 'Nombre Paciente',
                align: 'center',
            },{
                text: 'Tipo',
                align: 'center',
            },{
                text: 'Usuario Genera',
                align: 'center',
            },{
                text: 'Estado',
                align: 'center',
            },{
                text: 'Valor Orden',
                align: 'center',
            },{
                text: 'Valor Cita',
                align: 'center',
            },{
                text: '',
                align: 'center',
            }]
        }),
        methods:{
            buscarCobros(){
                this.preload = true;
                axios.get('/api/cobro/getHistoryCobroPaciente/'+this.numeroIdentificacion).then(res => {
                    this.listCobros = res.data;
                    this.preload = false;
                })
            },
            async generarCobro(item,formato) {
                this.preload = true;
                 if(parseInt(item.idTipo) === 3){
                if ((item.Finalidad === 'No aplica' && (item.estado_id === "7" || item.estado_id === '1')) || (item.Finalidad !== 'No aplica')) {
                     const data = {actualizar: false};
                     axios.post('/api/cobro/generate/'+item.id,data).then( async res => {
                         let anexos = {
                             medicamentos: [],
                             servicios: []
                         };
                         let mipress = {
                             medicamentos: [],
                         };
                         res.data.data.medicamentos.forEach(function (s,index) {
                             if(s.Estado_id == '37'){
                                 anexos.medicamentos.push(s);
                             }
                             if(s.Estado_id == '36'){
                                 mipress.medicamentos.push(s);
                             }
                         });
                         if(formato === 'formulas'){
                             if(res.data.data.medicamentos.length > anexos.medicamentos.length && res.data.data.medicamentos.length > mipress.medicamentos.length ){
                                 await this.getPDFsMedicamentos(res.data.data,"F O R M U L A  M E D I C A")
                             }else{
                                 this.preload = false
                             }
                             if(anexos.medicamentos.length > 0){
                                 this.getPDFsMedicamentos(res.data.data,"N O  C O N V E N I O")
                             }
                             if(mipress.medicamentos.length > 0){
                                 this.getPDFsMedicamentos(res.data.data,"M I P R E S")
                             }
                         }else{
                             const recibo = {
                                 orden: item.orden.id,
                                 paciente: item.paciente_id,
                                 formato: "recibo",
                                 valor: 0,
                             };
                             await this.getPDFCobro(recibo);
                         }
                     });
            }else{
                    this.$alerError("La orden se encuentra pendiente por pagar")
                }
                 }else{
                     const recibo = {
                         orden: item.orden.id,
                         paciente: item.paciente_id,
                         formato: "recibo",
                         valor: 0,
                     };
                      if ((item.Finalidad === 'No aplica' && item.estado_id === "7" && item.cobroCita === "7") || (item.Finalidad !== 'No aplica')) {
                         axios.get('/api/entidades/CalcularDireccionamiento/' + item.orden.id + '/' + item.orden.Cita_id).then(async res => {
                             for (const index in res.data.hojas) {
                                 if (res.data.hojas[index].requiere_cobro === true) {
                                     let data = {
                                         cups: res.data.cupsHojas[index],
                                         orden: item.orden.id,
                                         prestador: index.slice(0, -6),
                                         valor: res.data.hojas[index].valor_cobro,
                                         requiere: res.data.hojas[index].requiere_cobro,
                                         paciente: item.paciente_id,
                                         tipo: index,
                                         formato: "formulas"
                                     };
                                     if(formato === "formulas"){
                                         await this.getPDFs(data, "F O R M U L A  M E D I C A")
                                     }else if(formato === "recibos"){
                                         await this.getPDFCobro(data);
                                     }
                                 } else if (res.data.hojas[index].requiere_cobro === false) {
                                     let data = {
                                         cups: res.data.cupsHojas[index],
                                         orden: item.orden.id,
                                         prestador: index.slice(0, -6),
                                         valor: res.data.hojas[index].valor_cobro,
                                         requiere: res.data.hojas[index].requiere_cobro,
                                         paciente: item.paciente_id,
                                         tipo: index
                                     };
                                     if(formato === "formulas") {
                                         await this.getPDFs(data, "F O R M U L A  M E D I C A")
                                     }
                                 }
                             }
                             if (res.data.anexos.length > 0) {
                                 let anex = {
                                     medicamentos: [],
                                     servicios: res.data.anexos
                                 };
                                 if(formato === "formulas") {
                                     this.getAnexo3(anex)
                                 }
                             }
                             if(formato === "recibos"){
                                 await this.getPDFCobro(recibo);
                             }
                         })
                     }else{
                         this.$alerError("La orden se encuentra pendiente por pagar")
                     }
                }
                 this.preload = false;
            },
            async getPDFs(data,mensaje){
                data.mensaje = mensaje;
             const res = await  axios.post("/api/cobro/getPDF", data, {responseType: "arraybuffer"})
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                        this.preload = false;
            },
            getAnexo3(anexos){
                const data = {
                    anexos:anexos
                };
                axios.post("/api/cobro/getAnexo3", data, {
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
                    });
            },
        async getPDFCobro(data){
         await   axios.post("/api/cobro/getPDFRecibo", data, {
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
            habilitarImpresion(item){
                let respuesta = true;
                if(item.Finalidad === 'No aplica'){
                    respuesta = false;
                }
                return respuesta;
            },
            async getPDFsMedicamentos(data,mensaje){
                data.mensaje = mensaje;
                const res = await  axios.post("/api/cobro/getPDFMedicamentos", data, {responseType: "arraybuffer"})
                let blob = new Blob([res.data], {
                    type: "application/pdf"
                });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                window.open(link.href, "_blank");
            },
        }
    }
</script>
