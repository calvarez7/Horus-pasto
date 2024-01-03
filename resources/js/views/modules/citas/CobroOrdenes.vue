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
                                        <v-data-table :headers="headers" :items="listCobros" item-key="index">
                                            <template v-slot:items="props">
                                                <td class="text-xs-center">{{ props.item.idOrden }}</td>
                                                <td class="text-xs-center">{{ props.item.created_at.substring(0,10) }}</td>
                                                <td class="text-xs-center">{{ props.item.paciente }}</td>
                                                <td class="text-xs-center">{{ props.item.nombre }}</td>
                                                <td class="text-xs-center">{{ props.item.usuario }}</td>
                                                <td class="text-xs-center">$ {{ props.item.valor }}</td>
                                                <td class="text-xs-center">$ {{ props.item.valor_cita }}</td>
                                                <td class="text-xs-center">$ {{ (parseInt(props.item.idTipo) === 4 || parseInt(props.item.idTipo) === 2?(parseInt(props.item.valor)+(props.item.cobroCita == 4?parseInt(props.item.valor_cita):0)):props.item.valor_cita)}}</td>
                                                <td class="text-xs-center"><v-btn color="info" @click="abrirDialogCobro(props.item)">Cobrar</v-btn></td>

                                            </template>
                                            <template v-slot:no-data>
                                                <v-alert :value="true" color="error" icon="warning">No hay movimientos en este Estado.</v-alert>
                                            </template>
                                        </v-data-table>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </template>
                    </v-flex>

                    <v-dialog v-model="dialog" persistent max-width="350">
                        <v-card>
                            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                                <span class="title layout justify-center font-weight-light">Generar cobro</span>
                            </v-card-title>
                            <v-card-title class="headline"></v-card-title>
                            <v-card-text>
                                <v-form>
                                <v-flex xs12 sm12 d-flex>
                                    <v-select
                                        :items="items"
                                        label="Tipo Pago"
                                        v-model="tipoPago"
                                        solo
                                    ></v-select>
                                </v-flex>
                                <v-flex xs12 sm12 d-flex v-if="tipoPago === 'Transferencia'">
                                    <v-text-field
                                        label="Numero Referencia"
                                        v-model="numeroReferencia"
                                        type="number"
                                    ></v-text-field>
                                </v-flex>
                            </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" @click="dialog = false">Cancelar</v-btn>
                                <v-btn color="success" @click="generarCobro">Generar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-layout>
            </template>
        </v-flex>
    </v-layout>
</template>
<script>
    export default {
        name: 'CobroCitas',
        components: {},
        data() {
            return {
                listCobros: [],
                items: ['Efectivo','Transferencia'],
                numeroIdentificacion: null,
                numeroReferencia: "",
                dialog:false,
                idCobro: 0,
                tipoPago: "",
                tipoCobro:0,
                finalidad:"",
                orden:0,
                cita:0,
                paciente:0,
                preload: false,
                headers:[{
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
                    text: 'Valor Orden',
                    align: 'center',
                },{
                    text: 'Valor Cita',
                    align: 'center',
                },{
                    text: 'Total',
                    align: 'center',
                },{
                    text: '',
                    align: 'center',
                }]
            }
        },
        methods:{
            buscarCobros(){
                this.preload = true;
                axios.get('/api/cobro/getAllPaciente/'+this.numeroIdentificacion).then(res => {
                    this.listCobros = res.data;
                    this.preload = false;
                })
            },
            abrirDialogCobro(item){
                this.orden = item.orden_id;
                this.cita = item.cita;
                this.idCobro = item.id;
                this.dialog = true;
                this.tipoCobro = item.idTipo;
                this.finalidad = item.Finalidad;
                this.paciente = item.paciente_id;
            },
           async generarCobro(){
                if(this.tipoPago === ""){
                    this.$alerError("El tipo de pago es requerido");
                     return;
                }
                if(this.tipoPago === "Transferencia" && this.numeroReferencia ===  ""){
                    this.$alerError("El tipo de pago 'Transferencia' requiere un numero de referencia");
                    return;
                }
               this.preload = true;
               if(this.tipoPago === "Transferencia"){
                    const validacion = await axios.get('/api/cobro/validarNumeroReferencia/'+this.numeroReferencia);
                    if(validacion.data){
                        this.$alerError("El numero de referencia ya se encuentra registrado en el sistema");
                        this.preload = false;
                        return;
                    }
                }
                const data = {tipo_cobro: this.tipoPago,actualizar: true,numeroReferencia: this.numeroReferencia};
                axios.post('/api/cobro/generate/'+this.idCobro,data).then(async res => {
                    if(parseInt(this.tipoCobro) === 3){
                            let anexos = {
                                medicamentos: [],
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
                            if(res.data.data.medicamentos.length > anexos.medicamentos.length && res.data.data.medicamentos.length > mipress.medicamentos.length ){
                               await this.getPDFsMedicamentos(res.data.data,"F O R M U L A  M E D I C A");
                            }else{
                                this.preload = false
                            }
                            if(anexos.medicamentos.length > 0){
                                await this.getPDFs(res.data.data,"N O  C O N V E N I O")
                            }
                            if(mipress.medicamentos.length > 0){
                                await this.getPDFs(res.data.data,"M I P R E S")
                            }
                        const recibo = {
                            orden: this.orden,
                            paciente: this.paciente,
                            formato: "recibo",
                            valor: 0,
                        };
                        await this.getPDFCobro(recibo);
                    }else{
                        axios.get('/api/entidades/CalcularDireccionamiento/'+this.orden+'/'+this.cita).then(async res => {
                            const recibo = {
                                orden: this.orden,
                                paciente: this.paciente,
                                formato: "recibo",
                                valor: 0,
                            };
                            for (const index in res.data.hojas){
                                if(res.data.hojas[index].requiere_cobro === true){
                                    let data = {
                                        cups: res.data.cupsHojas[index],
                                        orden: this.orden,
                                        prestador: index.slice(0,-6),
                                        valor: res.data.hojas[index].valor_cobro,
                                        requiere:res.data.hojas[index].requiere_cobro,
                                        paciente: this.paciente,
                                        tipo: index,
                                        formato: "formulas"
                                    };
                                    await this.getPDFs(data,"F O R M U L A  M E D I C A");
                                    await this.getPDFCobro(data);
                                }else if(res.data.hojas[index].requiere_cobro === false){
                                    let data = {
                                        cups: res.data.cupsHojas[index],
                                        orden: this.orden,
                                        prestador: index.slice(0,-6),
                                        valor: res.data.hojas[index].valor_cobro,
                                        requiere:res.data.hojas[index].requiere_cobro,
                                        paciente: this.paciente,
                                        tipo: index
                                    };
                                    await this.getPDFs(data,"F O R M U L A  M E D I C A")
                                }
                            }
                            if (res.data.anexos.length > 0) {
                                let anex = {
                                    medicamentos: [],
                                    servicios: res.data.anexos
                                };
                               await this.getAnexo3(anex)
                            }
                            await this.getPDFCobro(recibo);
                        })
                    }
                    this.buscarCobros();
                });
                this.dialog = false;
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
            getPDFCobro(data){
                axios.post("/api/cobro/getPDFRecibo", data, {
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
        async getAnexo3(anexos){
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
        }
        }
    }
</script>
