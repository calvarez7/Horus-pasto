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
        <v-card>
            <v-card-text>
                <v-container grid-list-md text-xs-center>
                    <v-layout row wrap>
                        <v-flex xs12 sm12>
                            <v-text-field
                                readonly
                                label="Cargar Archivos Planos .TXT RIPS"
                                @click='onPickFile'
                                v-model='fileName'
                                prepend-icon="attach_file"
                                outline
                                clearable
                                @click:clear="clearMessage">
                            </v-text-field>
                            <!-- Hidden -->
                            <input
                                type="file"
                                style="display: none"
                                ref="fileInput"
                                accept="*/*"
                                multiple
                                @change="onFilePicked">
                        </v-flex>
                        <v-flex xs12 sm12 md6>
                            <v-select :items="entidades" item-value="id" item-text="nombre" v-model="entidad" label="Entidad" autocomplete></v-select>
                        </v-flex>
                        <v-flex xs12 sm5 md2>
                            <v-btn class="mb-3" tile color="primary" @click="valid">
                                Validar RIPS&nbsp;
                            </v-btn>
                        </v-flex>
                        <v-flex xs12 sm5 md2>
                            <v-btn v-if="isSuccessful" class="mb-3" tile color="success" @click="saveRips">
                                Guardar RIPS
                            </v-btn>
                            <v-btn v-else-if="parcial" class="mb-3" tile color="success" @click="savePartialRips">
                                Guardar RIPS
                            </v-btn>
                        </v-flex>
                        <v-spacer class="mt-3"></v-spacer>
                        <v-flex xs12 sm5 md2>
                            <v-btn v-if="parcial !== false || isResult !== false" tile color="info" @click="clearData">
                                Nuevo Cargue
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>

            <v-card-text v-if="parcial">
                <div class="font-weight-bold ml-8 mb-2">
                    <v-alert :value="true" outlined :type="typeMessage">
                                Resultado Validador: <strong>{{ message }}</strong>
                    </v-alert>
                </div>
                <v-layout row justify-center>
                    <v-btn tile @click="exportsErrors" color="success"><v-icon left>mdi-arrow-down-bold-circle-outline</v-icon>Exportar</v-btn></v-layout>
                <v-card class="mt-5">
                    <v-list three-line>
                        <v-list-tile-content>
                            <div class="text-overline mb-4">
                                <v-list-tile-title class="text-h8 mb-1"> <strong>{{ rep.razonSocial }}</strong></v-list-tile-title>
                                Cod. Hab. {{ rep.code }} - {{ rep.documentType }} {{ rep.documentNumber }}
                            </div>
                            <v-list-tile-title class="text-h8 mb-1 text-left">
                                Fecha de remisión: {{ fechaRemision }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list>



                    <v-data-table :headers="[]" :items="[{}]" hide-actions class="elevation-1">
                        <template v-slot:headers="props">
                            <tr>
                                <th class="text-center">Numero Prestadores</th>
                                <th class="text-center">Archivos</th>
                                <th class="text-center">Numero Registros</th>
                                <th class="text-center">Numero Registros Exitosos</th>
                            </tr>
                        </template>
                        <template v-slot:items="props">
                            <tr v-for="(item, index) in resumenDatos" :key="index">
                                <td class="text-center">{{ item.reps.length }}</td>
                                <td class="text-center">{{ item.file }}</td>
                                <td class="text-center">{{ item.number }}</td>
                                <td class="text-center">{{ item.success }}</td>
                                <!--                  <td class="text-center">{{ item[3] }}</td>-->
                            </tr>
                        </template>
                    </v-data-table>

                    <v-list three-line>
                        <v-list-tile-content>
                            <div class="text-overline">
                                <v-container class="lighten-5">
                                    <v-layout>
                                        <v-flex class="text-left">
                                            <v-card-text class="pa-2 text-caption" outlined tile><strong>Número de registros: {{
                                                    cantRegister
                                                }}</strong></v-card-text>
                                        </v-flex>
                                        <v-flex class="text-right">
                                            <v-card-text class="pa-2 text-caption" outlined tile><strong>Valor de radicación:
                                                {{ totalValor | pesoFormat }}</strong></v-card-text>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </div>
                        </v-list-tile-content>
                    </v-list>
                </v-card>


            </v-card-text>

                <v-card-text v-else>

                    <div v-if="isResult" class="font-weight-bold ml-8 mb-2">
                        <v-alert dense outlined :type="typeMessage" :value="true">
                            <v-layout align="center">
                                <v-flex class="grow">
                                    Resultado Validador: <strong>{{ message }}</strong>
                                </v-flex>
                            </v-layout>
                        </v-alert>
                    </div>

                <v-spacer class="mt-5 mb-5"></v-spacer>
                <v-layout v-show="inconsistencias.length > 0" row justify-center>
                    <v-btn tile @click="exportsErrors" color="success">
                        <v-icon left>
                            mdi-arrow-down-bold-circle-outline
                        </v-icon>
                        Exportar
                    </v-btn>
                </v-layout>

                <!--        <v-timeline v-show="inconsistencias.length > 0" align-top dense>-->
                <!--          <v-timeline-item v-for="error in inconsistencias" :key="error.message" color="error" small>-->
                <!--            <div>-->
                <!--              <div>{{ error }}</div>-->
                <!--            </div>-->
                <!--          </v-timeline-item>-->
                <!--        </v-timeline>-->


                <v-card v-if="ctRips.length > 0">
                    <v-list three-line>
                        <v-list-tile-content>
                            <div class="text-overline mb-4">
                                <v-list-tile-title class="text-h8 mb-1"> <strong>{{ rep.razonSocial }}</strong></v-list-tile-title>
                                Cod. Hab. {{ rep.code }} - {{ rep.documentType }} {{ rep.documentNumber }}
                            </div>
                            <v-list-tile-title class="text-h8 mb-1 text-left">
                                Fecha de remisión: {{ fechaRemision }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list>


                    <v-data-table :headers="[]" :items="[{}]" hide-actions class="elevation-1">
                        <template v-slot:headers="props">
                            <tr>
                                <th class="text-center">Numero Factura</th>
                                <th class="text-center">Valor Factura</th>
                            </tr>
                        </template>
                        <template v-slot:items="props">
                            <tr v-for="(item, index) in AFResponse" :key="index">
                            <td class="text-center">{{ item[4] }}</td>
                                <td class="text-center">{{ item[16] }}</td>


                            <!--                  <td class="text-center">{{ item[3] }}</td>-->
                            </tr>
                        </template>
                    </v-data-table>
                    <v-list three-line>
                        <v-list-tile-content>
                            <div class="text-overline">
                                <v-container class="lighten-5">
                                    <v-layout>
                                        <v-flex class="text-left">
                                            <v-card-text class="pa-2 text-caption" outlined tile><strong>Número de registros: {{
                                                    cantRegister
                                                }}</strong></v-card-text>
                                        </v-flex>
                                        <v-flex class="text-right">
                                            <v-card-text class="pa-2 text-caption" outlined tile><strong>Valor de radicación:
                                                {{ totalValor | pesoFormat }}</strong></v-card-text>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </div>
                        </v-list-tile-content>
                    </v-list>
                </v-card>
            </v-card-text>
        </v-card>

        <v-dialog v-model="dialog" width="350">
            <v-card>
                <v-toolbar dark color="primary mb-5">
                    <v-toolbar-title>REPS sin registro
                    </v-toolbar-title>
                </v-toolbar>

                <v-card-text>
                    <div class="font-weight-bold ml-8 mb-2">
                        Codigo Habilitacion
                    </div>

                    <v-timeline align-top dense>
                        <v-timeline-item v-for="(message, index) in messages" :key="index" color="warning" small>
                            <div>
                                <div>{{ message }}</div>
                            </div>
                        </v-timeline-item>
                    </v-timeline>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="dialog = false">
                        OK
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <div class="text-center">
            <v-dialog v-model="loadingValidacion" persistent width="300">
                <v-card color="primary" dark>
                    <v-card-text>
                        Validando ...
                        <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </div>
        <div class="text-center">
            <v-dialog v-model="loadingValidacion2" persistent width="300">
                <v-card color="primary" dark>
                    <v-card-text>
                        Cargando ...
                        <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </div>
    </div>
</template>
<script>
import Swal from 'sweetalert2';
export default {
    filters: {
        pesoFormat: (valor) => `$${new Intl.NumberFormat().format(valor) || 0}`
    },
    data: () => ({
        preload:false,
        fileName: '',
        anios:null,
        meses:[
            {nombre:'ENERO',id:'01'},
            {nombre:'FEBRERO',id:'02'},
            {nombre:'MARZO',id:'03'},
            {nombre:'ABRIL',id:'04'},
            {nombre:'MAYO',id:'05'},
            {nombre:'JUNIO',id:'06'},
            {nombre:'JULIO',id:'07'},
            {nombre:'AGOSTO',id:'08'},
            {nombre:'SEPTIEMBRE',id:'09'},
            {nombre:'OCTUBRE',id:'10'},
            {nombre:'NOVIEMBRE',id:'11'},
            {nombre:'DICIEMBRE',id:'12'},
        ],
        entidades: [
            {id:1,nombre:'REDVITAL UT'},
            {id:2,nombre:'MEDIMAS'},
            {id:3,nombre:'FONDO DE PASIVO SOCIAL DE FERROCARRILES NACIONALES DE COLOMBIA'}
        ],
        entidad: null,
        info:null,
        parcial:false,
        form:{
            // fechaDesde:null,
            // fechaHasta:null,
            mes:null,
            anio:null
        },
        red:[
            {id:'900033371',nombre:'SUMIMEDICAL'},
            {id:'813005431',nombre:'EMCOSALUD'},
            {id:'830023202',nombre:'COSMITET'},
            {id:'901416452',nombre:'FERRONORTE'}
        ],
        loadingValidacion:false,
        loadingValidacion2:false,
        prestador:null,
        numeroFactura:null,
        resumenDatos: [],
        typeMessage: '',
        isResult: false,
        message: '',
        isSuccessful: false,
        af: [],
        rep: {},
        files: [],
        filesResponse: [],
        ctRips: [],
        inconsistencias: [],
        fechaRemision: '',
        totalValor: 0,
        cantRegister: 0,
        dialog: false,
        AFResponse:[],
        messages: [{
            message: `Sure, I'll see you later.`,
            color: 'warning',
        },
            {
                message: 'Yeah, sure. Does 1:00pm work?',
                color: 'warning',
            },
            {
                message: 'Did you still want to grab lunch today?',
                color: 'warning',
            },
        ],
    }),
    methods: {
         valid() {
             if (!this.entidad) {
                 return this.$alerError('El Campo Entidad es Requerido!');
             }
             if(this.$refs.fileInput.files.length === 0){
                 return this.$alerError('Se requiere minimo el archivo "CT" y el archivo "AC" combinados con otro archivo de reporte');
             }
             this.preload = true;
             axios.get('/api/rips/autorizacionPeriodoRips').then(res => {
                 if(res.data.enabled){
                 this.preload = true;
                 this.isSuccessful = false;
                 this.inconsistencias = [];
                 this.ctRips = [];
                 const formData = new FormData();
                 let length = this.$refs.fileInput.files.length;

                 for (let i = 0; i < length; i++) {
                     if (this.$refs.fileInput.files[i].name.split('.')[1] === 'txt' || this.$refs.fileInput.files[i].name.split('.')[1] === 'TXT') {
                         formData.append('files[]', this.$refs.fileInput.files[i])
                     } else {
                         return this.$alerError('Tipo de archivo no válido');
                     }
                 }

                 formData.append('entidad', this.entidad);

                 this.preload = true;
                 this.$alerWarning('Esta validacion puede tardar unos minutos, puedes minimizar esta ventana mientras se termina el proceso');
                 axios.post('/api/rips/validar', formData, {'Content-Type': 'multipart/form-data'}).then(res =>{
                     if(res.data.type === 200){
                         if(parseInt(res.data.parcial) === 1){
                             this.cantRegister = 0;
                             this.totalValor = 0;
                             this.files = [];
                             this.prestador = null;
                             this.form.anio = null;
                             this.form.mes = null;
                             this.numeroFactura = null
                             this.parcial = 1;
                             this.typeMessage = 'info'
                             this.message = res.data.message;
                             this.inconsistencias = res.data.errors;
                             this.fechaRemision = res.data.files.CT.content[0][1];
                             this.AFResponse = res.data.files.AF.content
                             this.filesResponse = res.data.files;
                             this.info = res.data.info;
                             res.data.files.AF.success.forEach(s => {
                                 this.totalValor += parseInt(s[16])
                             })
                             this.rep = {
                                 code: res.data.provider.Codigo || '',
                                 razonSocial: res.data.provider.Nombre || '',
                                 documentType: 'NIT' || '',
                                 documentNumber: res.data.provider.Codigo || '',
                             };
                             for (const file in res.data.files){
                                 if(file !== 'CT' && file !== 'AF' && res.data.files[file].success !== undefined){
                                     this.cantRegister += res.data.files[file].success.length;
                                 }
                             }
                             this.resumenDatos = res.data.resumen;
                             this.preload = false;
                         }else{
                             this.cantRegister = 0;
                             this.totalValor = 0;
                             this.files = [];
                             this.typeMessage = 'success'
                             this.message = res.data.message;
                             this.isResult = true;
                             this.filesResponse = res.data.files;
                             this.AFResponse = res.data.files.AF.content
                             this.info = res.data.info;
                             this.isSuccessful = true;
                             this.prestador = null;
                             this.form.anio = null;
                             this.form.mes = null;
                             this.numeroFactura = null
                             this.rep = {
                                 code: res.data.provider.Codigo || '',
                                 razonSocial: res.data.provider.Nombre || '',
                                 documentType: 'NIT' || '',
                                 documentNumber: res.data.provider.Codigo || '',
                             };
                             this.resumenDatos = res.data.resumen;
                             this.ctRips = res.data.files.CT.content || [];
                             this.fechaRemision = res.data.files.CT.content[0][1];
                             res.data.files.AF.content.forEach(s => {
                                 this.totalValor += parseInt(s[16])
                             })
                             this.ctRips.forEach(inc => {
                                 this.cantRegister += parseInt(inc[3]);
                             })
                             this.preload = false;
                         }
                     }else if(res.data.type === 400){
                         this.$refs.fileInput.value=null;
                         this.typeMessage = 'error'
                         this.message = res.data.message;
                         this.isResult = true;
                         this.inconsistencias = res.data.errors;
                         this.prestador = null;
                         // this.form.fechaDesde = null;
                         // this.form.fechaHasta = null;
                         this.form.anio = null;
                         this.form.mes = null;
                         this.numeroFactura = null
                         this.loadingValidacion = false
                         this.preload = false;
                     }
                     this.preload = false;
                 }).catch(e => {
                     this.preload = false;
                 })
                 }else{
                     this.preload = false;
                     this.isSuccessful = false;
                     Swal.fire(
                         'Radicación Inactiva',
                         'La radicación solo es funcional en los dias '+res.data.ini_day +' al '+res.data.fin_day +' de cada mes.',
                         'error'
                     )
                     this.clearData();
                 }
             })

        },
        saveRips() {
            Swal.fire({
                title: 'Esta seguro de enviar el RIPS?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#4CAF50",
                cancelButtonColor: "#FF5252",
                confirmButtonText: "SI",
                cancelButtonText: "NO"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        this.preload = true;
                        axios.post('/api/rips/validateExist', this.info).then(res => {
                            if (Object.entries(res.data).length === 0) {
                                const dataInfomation = {
                                    files: this.filesResponse,
                                    info: this.info
                                }
                                // const data = await this.$loadingPostRequest(saveRips(), dataInfomation)
                                axios.post('/api/rips/saveRips',dataInfomation).then(res => {
                                    result = res.data;
                                    this.clearData()
                                    Swal.fire({
                                        title: "Numero de remision " + result.paq_id,
                                        icon: 'success',
                                    })
                                    this.preload = false;
                                    // this.downloadCertificate(result.paq_id, 1);
                                })
                            } else if (res.data.partial === '1') {
                                Swal.fire({
                                    title: 'Ya existe una carga parcial de este prestador con el mismo numero de factura, desea actualizar?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: "#4CAF50",
                                    cancelButtonColor: "#FF5252",
                                    confirmButtonText: "SI",
                                    cancelButtonText: "NO"
                                }).then(async (result2) => {
                                    if (result2.isConfirmed) {
                                        const dataInfomation = {
                                            files: this.filesResponse,
                                            info: this.info
                                        }
                                        axios.post('/api/rips/saveRips',dataInfomation).then(res => {
                                            result = res.data;
                                            this.clearData()
                                            Swal.fire({
                                                title: "Numero de remision " + result.paq_id,
                                                icon: 'success',
                                            })
                                            this.preload = false;
                                            // this.downloadCertificate(result.paq_id, 1);
                                        })
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Ya existe una carga completa de este prestador con el mismo numero de factura',
                                    icon: 'error',
                                })
                                this.clearData()
                            }
                            this.preload = false;
                        })
                    } catch (error) {
                        this.preload = false;
                        console.error(error)
                    }
                }
            })
        },

        clearData(){
            this.isSuccessful = false;
            this.isResult = false;
            this.af = [];
            this.files = [];
            this.ctRips = [];
            this.cantRegister = 0;
            this.totalValor = 0;
            this.info = null;
            this.parcial = false
            this.inconsistencias = [];
            this.entidad = null;
            this.fileName = '';
            this.$refs.fileInput.value=null;
        },

        savePartialRips(){
            Swal.fire({
                title: 'Esta seguro de enviar el RIPS?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#4CAF50",
                cancelButtonColor: "#FF5252",
                confirmButtonText: "SI",
                cancelButtonText: "NO"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        this.preload = true;
                        axios.post('/api/rips/validateExist', this.info).then(res => {
                        if (Object.entries(res.data).length === 0) {
                            const dataInfomation = {
                                files: this.filesResponse,
                            }
                            axios.post('/api/rips/savePartialRips', dataInfomation).then(res => {
                                const result = res.data;
                                this.clearData()
                                Swal.fire({
                                    title: "Numero de remision " + result.paq_id,
                                    icon: 'success',
                                })
                                this.preload = false;
                                this.downloadCertificate(result.data.paq_id,3);
                            })
                        } else if (res.data.partial === '1') {
                            Swal.fire({
                                title: 'Ya existe una carga parcial de este prestador con el mismo numero de factura, desea actualizar?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: "#4CAF50",
                                cancelButtonColor: "#FF5252",
                                confirmButtonText: "SI",
                                cancelButtonText: "NO"
                            }).then(async (result2) => {
                                if (result2.isConfirmed) {
                                    const dataInfomation = {
                                        files: this.filesResponse,
                                        info: this.info
                                    }
                                    axios.post('/api/rips/savePartialRips', dataInfomation).then(res => {
                                        const result = res.data;
                                        this.clearData()
                                        Swal.fire({
                                            title: "Numero de remision " + result.paq_id,
                                            icon: 'success',
                                        })
                                        this.preload = false;
                                        this.downloadCertificate(result.data.paq_id,3);
                                    })
                                }
                            })
                        } else {
                            Swal.fire({
                                title: 'Ya existe una carga completa de este prestador con el mismo numero de factura',
                                icon: 'error',
                            })
                            this.clearData()
                        }
                    })
                        this.preload = false;
                    } catch (error) {
                        this.preload = false;
                        console.error(error)
                    }
                }
            })
        },
        async downloadCertificate(id,tipo) {
            const form = {
                type: tipo
            };
            this.preload = true;
            const data = await this.$axios.$post(certificate(id), form, {
                responseType: 'arraybuffer'
            })

            const blob = new Blob([data], {
                type: "application/pdf"
            });
            const link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            window.open(link.href, "_blank");
            this.preload = false;
        },
        async exportsErrors() {
            this.preload = true;
            try {
                const data = await axios.post('/api/rips/exportError',this.inconsistencias,{
                    responseType: 'blob',
                })
                const blob = new Blob([data.data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.download = `ResultadosValidaciónRIPS.xlsx`;
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
        onPickFile () {
            this.$refs.fileInput.click()
        },
        onFilePicked (event) {
            const files = event.target.files
            if (files[0] !== undefined) {
                for (const [key, value] of Object.entries(files)) {
                    this.fileName += value.name+", "
                }
                // this.fileName = files[0].name

            } else {
                this.fileName = ''
            }
        },
        clearMessage () {
            this.fileName = "";
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
    },
    created() {
        const year = new Date();
        this.anios = [year.getFullYear()-3,year.getFullYear()-2,year.getFullYear()-1,year.getFullYear()]
    }
}

</script>
