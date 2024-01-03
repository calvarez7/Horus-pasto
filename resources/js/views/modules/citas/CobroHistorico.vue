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
                                                <td class="text-xs-center">{{ props.item.created_at.substring(0,10) }}</td>
                                                <td class="text-xs-center">{{ props.item.paciente }}</td>
                                                <td class="text-xs-center">{{ props.item.nombre }}</td>
                                                <td class="text-xs-center">{{ props.item.usuario_ordena }}</td>
                                                <td class="text-xs-center">{{ props.item.usuario_cobra }}</td>
                                                <td class="text-xs-center">$ {{ props.item.valor }}</td>
                                                <td class="text-xs-center"><v-btn color="info" @click="generarCobro(props.item.id)">Imprimir</v-btn></td>

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
                </v-layout>
            </template>
        </v-flex>
    </v-layout>
</template>
<script>
    export default {
        name: "cobroHistorico",
        components: {},
        data(){
            return{
                listCobros: [],
                numeroIdentificacion: null,
                dialog:false,
                idCobro: 0,
                preload: false,
                headers:[{
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
                    text: 'Usuario Cobro',
                    align: 'center',
                },{
                    text: 'Valor Orden',
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
                axios.get('/api/cobro/getHistoryCobroPaciente/'+this.numeroIdentificacion).then(res => {
                    console.log(res.data);
                    this.listCobros = res.data;
                    this.preload = false;
                })
            },
            generarCobro(id){
                this.preload = true;
                const data = {actualizar: false};
                axios.post('/api/cobro/generate/'+id,data).then(res => {
                    this.getPDFs(res.data.data)
                })
            },
            getPDFs(data){
                data.mensaje = "F O R M U L A  M E D I C A";
                axios.post("/api/cobro/getPDF", data, {
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
