<template>
    <div>

        <v-card>
            <v-card-text>
                <v-container grid-list-md text-xs-center>
                    <v-layout row wrap>

                        <v-flex xs12 sm6>
                            <v-text-field
                                readonly
                                label="Cargar Archivo Plano .TXT R202"
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
                                @change="onFilePicked">
                        </v-flex>
                        <v-flex xs12 sm3>
                            <v-select v-model="form.trimestre" :items="trimestres" item-text="nombre" item-value="val" label="Seleccionar Trimestre..."></v-select>
                        </v-flex>
                        <v-flex xs12 sm3>
                            <v-select v-model="form.anio" :items="anios" label="Seleccionar año..."></v-select>
                        </v-flex>

                        <v-flex xs12 sm12>
                            <v-autocomplete item-value="id" :item-text="ipss => ipss.Codigo_habilitacion+' / '+ipss.Nombre" :items="ipss" v-model="ips" label="IPS" outline></v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm5 md2>
                            <v-btn tile color="primary" @click="valid">
                                Validar
                            </v-btn>
                            <v-btn v-if="parcial || isSuccessful" dark color="cyan" @click="clearData">
                                Nuevo Cargue
                            </v-btn>
                            <v-btn v-if="isSuccessful" tile color="success" @click="save202">
                                Enviar
                            </v-btn>
                            <v-btn v-else-if="parcial" tile color="success" @click="savePartial202">
                                Enviar
                            </v-btn>
                        </v-flex>

                    </v-layout>
                </v-container>
            </v-card-text>

            <v-card-text v-if="parcial">
                <div class="font-weight-bold ml-8 mb-2">
                    <v-alert dense outlined :type="typeMessage" :value="true">
                        <v-layout align="center">
                            <v-flex class="grow">
                                Resultado Validador: <strong>{{ message }}</strong>
                            </v-flex>
                        </v-layout>
                    </v-alert>
                </div>
                <v-layout v-show="inconsistencias.length > 0" row justify-center>
                    <v-btn tile color="success" @click="exportsErrors">
                        <v-icon left>
                            mdi-arrow-down-bold-circle-outline
                        </v-icon>
                        Exportar
                    </v-btn>
                </v-layout>

                <v-card class="mt-5" v-if="Object.keys(rep).length > 0">
                    <v-list three-line>
                        <v-list-tile-content>
                            <div class="text-overline mb-4">
                                <v-list-tile-title class="text-h8 mb-1"> <strong>{{ rep.razonSocial }}</strong></v-list-tile-title>
                                Cod. Hab. {{ rep.code }} - {{ rep.documentType }} {{ rep.documentNumber }}
                            </div>
                            <v-list-tile-title class="text-h8 mb-1 text-left">
                                <!-- Fecha de remisión: {{ fechaRemision }}-->
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list>
                    <v-data-table :headers="[]" :items="[{}]" class="elevation-1">
                        <template v-slot:headers="props">
                            <tr>
                                <th class="text-center">Cod.Entidad</th>
                                <th class="text-center">Fecha Inicial Período</th>
                                <th class="text-center">Fecha Final Período</th>
                                <th class="text-center">Numero Registros</th>
                                <th class="text-center">Numero Registros Exitosos</th>
                            </tr>
                        </template>
                        <template v-slot:items="props">
                            <tr>
                                <td class="text-center">EAS027</td>
                                <td class="text-center">{{ filesResponse["202"].content[0][2] }}</td>
                                <td class="text-center">{{ filesResponse["202"].content[0][3] }}</td>
                                <td class="text-center">{{ filesResponse["202"].content[0][4] }}</td>
                                <td class="text-center">{{ (filesResponse["202"].success.length)-1 }}</td>
                            </tr>
                        </template>
                    </v-data-table>
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
                <v-layout v-show="inconsistencias.length > 0" justify="space-around">
                    <v-btn tile color="success" @click="exportsErrors">
                        <v-icon left>
                            mdi-arrow-down-bold-circle-outline
                        </v-icon>
                        Exportar
                    </v-btn>
                </v-layout>



                <v-card v-if="Object.keys(rep).length > 0">
                    <v-layout row>
                        <v-list three-line>
                            <v-list-tile-content>
                                <div class="text-overline mb-4">
                                    <v-list-tile-title class="text-h8 mb-1"> <strong>{{ rep.razonSocial }}</strong></v-list-tile-title>
                                    Cod. Hab. {{ rep.code }} - {{ rep.documentType }} {{ rep.documentNumber }}
                                </div>
                                <v-list-tile-title class="text-h8 mb-1 text-left">
                                    <!-- Fecha de remisión: {{ fechaRemision }}-->
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list>
                    </v-layout>

                    <v-data-table :headers="[]" :items="[{}]" class="elevation-1">
                        <template v-slot:headers="props">
                            <tr>
                                <th class="text-center">Cod.Entidad</th>
                                <th class="text-center">Fecha Inicial Período</th>
                                <th class="text-center">Fecha Final Período</th>
                                <th class="text-center">Numero Registros</th>
                            </tr>
                        </template>
                        <template v-slot:items="props">
                            <tr>
                                <td class="text-center">EAS027</td>
                                <td class="text-center">{{ filesResponse["202"].content[0][2] }}</td>
                                <td class="text-center">{{ filesResponse["202"].content[0][3] }}</td>
                                <td class="text-center">{{ filesResponse["202"].content[0][4] }}</td>
                            </tr>
                        </template>
                    </v-data-table>

                </v-card>
            </v-card-text>

        </v-card>

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
    </div>
</template>
<script>
import Swal from 'sweetalert2';
export default {
    data: () => ({
        preload:false,
        fileName:null,
        ips:null,
        ipss:[],
        trimestres:[
            {nombre:'Primer Trimestre',val:1},
            {nombre:'Segundo Trimestre',val:2},
            {nombre:'Tercer Trimestre',val:3},
            {nombre:'Cuarto Trimestre',val:4}
        ],
        anios:[],
        form:{
            trimestre:null,
            anio: null
        },
        files: null,
        filesAC:null,
        filesAP:null,
        isResult: false,
        inconsistencias: [],
        isSuccessful: false,
        loadingValidacion:false,
        message:'',
        typeMessage:'',
        rep: {},
        filesResponse:null,
        parcial:null,
        info:null


    }),
    methods: {
        async valid() {
            // const authorization = await this.$axios.$get(authorizationPeriod202())
            if (!this.form.trimestre) {
                return Swal.fire({
                    title: 'El trimestre es requerido.',
                    icon: 'error'
                });
            }
            if (!this.form.anio) {
                return Swal.fire({
                    title: 'El año es requerido.',
                    icon: 'error'
                });
            }

            if(this.$refs.fileInput.files.length === 0){
                return this.$alerError('Se requiere la carga de un archivo.');
            }
            if(!this.ips){
                return this.$alerError('El campo "IPS" es requerido.');
            }

            this.isSuccessful = false;
            this.inconsistencias = [];
            this.loadingValidacion = true;
            const formData = new FormData();
            if (this.$refs.fileInput.files[0].name.split('.')[1] === 'txt' || this.$refs.fileInput.files[0].name.split('.')[1] === 'TXT') {
                formData.append('files', this.$refs.fileInput.files[0])
            } else {
                this.files = null;
                return this.$alerError('Tipo de archivo no válido');
            }

            try {
                let header = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                };
                formData.append('ips',this.ips)
                formData.append('trimestre',this.form.trimestre)
                formData.append('anio',this.form.anio)
                const api = await axios.post('/api/m202/validator', formData, header);
                const data = api.data;
                // const data = await this.$axios.$post(valid(this.form.trimestre, this.form.anio), formData);
                if (parseInt(data.parcial) === 1) {
                    this.parcial = 1;
                    this.message = data.message;
                    this.typeMessage = 'info';
                    this.inconsistencias = data.errors;
                    this.filesResponse = data.files;
                    this.rep = {
                        code: data.provider.Codigo_habilitacion || '',
                        razonSocial: data.provider.Nombre || '',
                        documentType: 'NIT' || '',
                        documentNumber: data.provider.Nit || '',
                    };
                    this.info = data.info;
                } else {
                    this.loadingValidacion = false;
                    this.isResult = true;
                    this.typeMessage = 'success'
                    this.message = data.message;
                    this.isSuccessful = true;
                    this.prestador = null;
                    // this.form.fechaDesde = null;
                    // this.form.fechaHasta = null;
                    this.form.trimestre = null;
                    this.form.anio = null;
                    this.files = null;
                    this.filesResponse = data.files;
                    this.rep = {
                        code: data.provider.Codigo_habilitacion || '',
                        razonSocial: data.provider.Nombre || '',
                        documentType: 'NIT' || '',
                        documentNumber: data.provider.Nit || '',
                    };
                    this.info = data.info;
                }
            } catch (e) {
                console.log(e.response);
                this.message = e.response.data.message;
                this.loadingValidacion = false;
                this.isResult = true;
                this.inconsistencias = e.response.data.errors;
                this.typeMessage = 'error';
                this.prestador = null;
                // this.form.fechaDesde = null;
                // this.form.fechaHasta = null;
                this.form.trimestre = null;
                this.form.anio = null;
                this.files = null;
                this.info = e.response.data.info;
            }
            this.loadingValidacion = false;
            // }else{
            // this.$swal.fire(
            // 'Radicación Inactiva',
            // 'La radicación solo es funcional en las fechas '+authorization.ini_day +' al '+authorization.fin_day +'.',
            // 'error'
            // )
            // }
        },
        save202(){
            Swal.fire({
                title: 'Esta seguro de enviar el archivo 202?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#4CAF50",
                cancelButtonColor: "#FF5252",
                confirmButtonText: "SI",
                cancelButtonText: "NO"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const data1 = await axios.post('/api/m202/validateExist',this.info);
                    if(Object.entries(data1.data).length === 0) {
                        const dataInfomation = {
                            files: this.filesResponse,
                            info: this.info
                        }
                        const data = await axios.post('/api/m202/save202',dataInfomation);
                        this.downloadCertificate(data.data.id);
                        this.$alerSuccess(data.data.message);
                        this.clearData();
                    }else{
                        if(data1.data.partial === '1'){
                            Swal.fire({
                                title: 'Ya existe una carga parcial de este prestador con el mismo periodo, desea actualizar?',
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
                                    const data = await axios.post('/api/m202/save202',dataInfomation);

                                    this.downloadCertificate(data.data.id);
                                    this.$alerSuccess(data.data.message);
                                    this.clearData();
                                }
                            })

                        }else{
                            Swal.fire({
                                title: 'Ya existe una carga completa de este prestador con el mismo periodo',
                                icon: 'error',
                            })
                        }
                    }
                }
            });
        },

        savePartial202(){

            Swal.fire({
                title: 'Esta seguro de enviar el archivo 202?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#4CAF50",
                cancelButtonColor: "#FF5252",
                confirmButtonText: "SI",
                cancelButtonText: "NO"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const data1 = await axios.post('/api/m202/validateExist',this.info);
                    if(Object.entries(data1.data).length === 0) {
                        const dataInfomation = {
                            files: this.filesResponse,
                            info: this.info
                        }
                        const data = await axios.post('/api/m202/savePartial202',dataInfomation);
                        this.downloadCertificate(data.data.id);
                        this.$alerSuccess(data.data.message);
                        this.clearData();
                    }else{
                        if(data1.data.partial === '1'){
                            Swal.fire({
                                title: 'Ya existe una carga parcial de este prestador con el mismo periodo, desea actualizar?',
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
                                    const data = await axios.post('/api/m202/savePartial202',dataInfomation);
                                    this.downloadCertificate(data.data.id);
                                    this.$alerSuccess(data.data.message);
                                    this.clearData();
                                }
                            })
                        }else{
                            Swal.fire({
                                title: 'Ya existe una carga completa de este prestador con el mismo periodo',
                                icon: 'error',
                            })
                        }
                    }
                }
            })
        },

        async downloadCertificate(id) {
            const form = {
                type: 1
            };
            const data = await axios.post("/api/m202/certificate/"+id, form, {responseType: "arraybuffer"})


            const blob = new Blob([data.data], {
                type: "application/pdf"
            });
            const link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            window.open(link.href, "_blank");
        },

        clearData(){
            this.ips = null;
            this.fileName = null;
            this.$refs.fileInput.value=null;
            this.files = null;
            this.filesAC = null;
            this.filesAP = null;
            this.prestador = null;
            // this.form.fechaDesde = false;
            // this.form.fechaHasta = false;
            this.form.trimestre = null;
            this.form.anio = null;
            this.isResult = false;
            this.inconsistencias = [];
            this.isSuccessful = false;
            this.loadingValidacion = false;
            this.message = '';
            this.typeMessage = '';
            this.rep = {};
            this.filesResponse = null;
            this.parcial = null;
            this.info = null;
        },
        onPickFile () {
            this.$refs.fileInput.click()
        },
        clearMessage () {
            this.fileName = "";
        },
        onFilePicked (event) {
            const files = event.target.files
            if (files[0] !== undefined) {
                this.fileName = files[0].name
            } else {
                this.fileName = ''
            }
        },
        getIPS(){
            this.preload = true;
            axios.get('/api/sedeproveedore/sedeproveedores').then(res => {
                this.ipss = res.data;
                this.preload = false;
            }).catch(e => {
                console.log(e);
                this.preload = false;
            })
        },
        async exportsErrors() {
            this.loadingValidacion2 = true;
            try {
                const data = await axios.post("/api/m202/exportError", this.inconsistencias, {responseType: "blob"})
                const blob = new Blob([data.data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.download = `ResultadosValidación202.xlsx`;
                a.href = url;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }catch (error){
                this.loadingValidacion2 = false;
            }
            this.loadingValidacion2 = false;
        },
    },
    created() {
        const year = new Date();
        this.anios = [year.getFullYear()-3,year.getFullYear()-2,year.getFullYear()-1,year.getFullYear()]
        this.getIPS();
    }
}
</script>
