<template>
    <v-container grid-list-md text-xs-center>
        <template>
            <div class="text-center">
                <v-dialog v-model="preload" persistent width="300">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Estamos procesando su información
                            <v-progress-linear indeterminate color="white" class="mb-0">
                            </v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </div>
        </template>
        <v-layout row wrap>

        <v-flex xs12 sm12 md12 >
            <v-card>
                <v-layout row wrap>
            <v-flex xs10 sm10 md10>
                <v-text-field
                    label="Numero Orden"
                    outline
                    v-model="numeroOrden"
                ></v-text-field>
            </v-flex>
                <v-flex xs2 sm2 md2>
                    <v-btn block large color="success" @click="buscarOrden">Buscar</v-btn>
                </v-flex>
                </v-layout>
                <v-layout row wrap v-if="paciente.id">
                    <v-flex xs2 sm2 md2>
                       <b>Numero Orden:</b><br>{{paciente.id}}
                    </v-flex>
                    <v-flex xs2 sm2 md2>
                        <b>Fecha Orden:</b><br>{{paciente.Fechaorden}}
                    </v-flex>
                    <v-flex xs2 sm2 md2>
                        <b>Paciente:</b><br>{{paciente.paciente}}
                    </v-flex>
                </v-layout>
                </v-card>
        </v-flex>

        <v-flex xs12 sm12 >
        <v-card>
        <VueSignaturePad width="100%" :customStyle="customStyle" height="500px" ref="signaturePad" />
        </v-card>
        </v-flex>

            <v-flex xs12 sm12 >
                <v-card>
                    <v-btn color="error" large @click="undo">Borrar</v-btn>
                    <v-btn color="success" large @click="save">Guardar</v-btn>
                </v-card>
            </v-flex>

        </v-layout>
    </v-container>
</template>
<script>
    export default {
        name:'firmaOrdenes',
        data() {
            return {
                numeroOrden:null,
                preload:false,
                paciente:{
                    Fechaorden:null,
                    id:null,
                    paciente:null,
                    Num_Doc:null
                },
                customStyle: {
                    border: 'black 3px solid',
                    'background-color': '#D7D7D7',
                }
            }
        },
        methods: {
            buscarOrden(){
                this.preload = true;
                axios.get('/api/autorizacion/orden/paciente/'+this.numeroOrden).then(res => {
                    if(Object.keys(res.data).length > 0){
                        console.log('aqui');
                        this.paciente = res.data;
                        this.preload = false;
                    }else{
                        this.$alerError('Numero de orden invalido');
                        this.preload = false;
                    }

                }).catch(err => {
                    this.preload = false;
                    console.log(err.response)})
            },
            undo() {
                this.$refs.signaturePad.clearSignature();
            },
            save() {
                this.preload = true;
                const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
                if(!this.paciente.id){
                    this.$alerError('Debe ingresar el numero de orden');
                    this.preload = false;
                    return;
                }
                if(!isEmpty){
                    let formData = new FormData();
                    formData.append("file", data);
                    axios.post('/api/autorizacion/agregarFirmaPaciente/'+this.paciente.id, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(res => {
                        this.imprimir();
                        this.$alerSuccess(res.data);
                        this.$refs.signaturePad.clearSignature();
                        for (const prop of Object.getOwnPropertyNames(this.paciente)) {
                            this.paciente[prop] = null;
                        }
                        this.numeroOrden = null;

                    }).catch(err => {
                        this.preload = false;
                        console.log(err.response)})
                }else{
                    this.$alerError('Debe ingresar una firma');
                    this.preload = false;
                }
            },
            imprimir() {
                const data = {
                    type: "MedicamentoFirmado",
                    orden_id:this.paciente.id,
                    Num_Doc:this.paciente.Num_Doc
                }
                axios.post("/api/pdf/print-pdf", data, {
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
                    .catch(err => console.log(err.response));
            },
        }
    }
</script>
