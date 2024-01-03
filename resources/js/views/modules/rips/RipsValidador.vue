<template>
    <div>
        <v-dialog max-width="800px" persistent v-model="dialog">
            <v-card>
                <v-form ref="form">
                    <v-card-title>Cargar archivos</v-card-title>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <v-select :items="entidades" item-value="id" item-text="nombre" v-model="entidad" label="Entidad" autocomplete></v-select>
                            </v-flex>
                            <v-flex xs3>
                                <v-btn color="primary" dark outline round @click="uploadFiles">
                                    <v-icon left>backup</v-icon>
                                    Subir archivos
                                </v-btn>
                            </v-flex>
                            <v-flex xs9 px-2>
                                <input hidden multiple name="file" ref="files" type="file" @change="setName" />
                                <VTextField disabled name="name"
                                    :rules="[v => !!v || 'Se necesitan cargar archivos para validar']" single-line
                                    v-model="namefile" @click="uploadFiles" />
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                    <v-card-actions>
                        <VSpacer />
                        <v-btn color="primary" flat @click="dialog = !dialog">
                            Cerrar
                        </v-btn>
                        <v-btn color="primary" flat @click="validarRips">
                            Validar Rips
                        </v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>
        <v-dialog v-model="isLoadingRips" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    {{ stateLoad }}, espere por favor.
                    <VProgressLinear indeterminate color="white" class="mb-0" />
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-card>
            <v-card-text>
                <v-flex xs12>
                    <v-layout row wrap>
                        <v-flex xs2 v-if="isResult">
                            <v-btn color="warning" round @click="clearResultFields">
                                <v-icon left>keyboard_backspace</v-icon>
                                Volver
                            </v-btn>
                        </v-flex>
                        <v-flex xs2 v-else>
                            <v-btn color="primary" round @click="dialog = true">
                                Cargar Archivos
                            </v-btn>
                        </v-flex>
                        <VSpacer />
                        <v-flex xs2>
                            <v-btn color="success" v-show="isSuccessful && can('rips.validador.enviarFacturas')" round
                                @click="sendFilesForTempSave()">
                                Enviar
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-card-text>
        </v-card>
        <v-flex class="white--text py-2 elevation-5" :class="isSuccessful? 'success': 'error'" my-3 xs12
            v-if="isResult">
            <v-layout justify-center row wrap>
                <span>
                    Resultado Validador SUMIMEDICAL: <b>{{ message }}</b>
                </span>
            </v-layout>
        </v-flex>
        <v-flex mt-3 xs12 v-show="inconsistencias.length > 0">
            <v-card>
                <v-data-table class="elevation-1" dense hide-actions hide-headers :items="inconsistencias"
                    :loading="loading">
                    <template v-slot:items="props">
                        <td>{{props.item}}</td>
                    </template>
                </v-data-table>
            </v-card>
        </v-flex>
        <v-flex xs12 mt-3 v-show="af.length > 0">
            <v-card>
                <v-card-text>
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
                                <span class="headline"> Archivo '{{ fileNameAf }}'</span>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                    <vDivider />
                </v-card-text>
                <v-data-table :loading="loading" :headers="headersAf" hide-actions :items="af" class="elevation-1">
                    <template v-slot:items="props">
                        <td class="text-xs-center">{{ props.item[4] }}</td>
                        <td class="text-xs-center">{{ props.item[5] }}</td>
                        <td class="text-xs-center">{{ props.item[6] }}</td>
                        <td class="text-xs-center">{{ props.item[7] }}</td>
                        <td class="text-xs-center">{{ props.item[16] | pesoFormat }}</td>
                    </template>
                </v-data-table>
            </v-card>
        </v-flex>
        <v-flex xs12 mt-3 v-show="af.length > 0">
            <v-card>
                <v-container grid-list-xs>
                    <v-layout row wrap>
                        <v-flex xs4>
                            <span class="title">Número de facturas: {{ af.length }}</span>
                        </v-flex>
                        <VSpacer />
                        <v-flex xs4>
                            <span class="title">Valor de radicación: {{ totalValor | pesoFormat }}</span>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </v-flex>
        <v-snackbar v-model="isActiveSnackbar" color="success" :timeout="timeoutSnackbar">
            {{ textSnackbar }}
            <v-btn dark flat @click="isActiveSnackbar = false">
                Cerrar
            </v-btn>
        </v-snackbar>
    </div>
</template>

<script>
    import {
        mapGetters
    } from "vuex";
    export default {
        name: 'RipsValidador',
        data: () => {
            return {
                entidades: [
                    {id:1,nombre:'REDVITAL UT'},
                    {id:2,nombre:'MEDIMAS'},
                    {id:3,nombre:'FONDO DE PASIVO SOCIAL DE FERROCARRILES NACIONALES DE COLOMBIA'}
                ],
                af: [],
                fileNameAf: '',
                dialog: false,
                files: [],
                headersAf: [{
                        text: 'Número Factura',
                        align: 'center',
                        value: 'id',
                        sortable: false
                    },
                    {
                        text: 'Fecha Expedición',
                        align: 'center',
                        value: 'id',
                        sortable: false
                    },
                    {
                        text: 'Fecha Inicio',
                        align: 'center',
                        value: 'id',
                        sortable: false
                    },
                    {
                        text: 'Fecha Final',
                        align: 'center',
                        value: 'id',
                        sortable: false
                    },
                    {
                        text: 'Valor Neto',
                        align: 'center',
                        value: 'id',
                        sortable: false
                    }
                ],
                entidad: null,
                inconsistencias: [],
                isActiveSnackbar: false,
                isResult: false,
                isSuccessful: false,
                loading: false,
                message: '',
                namefile: 'Seleccionar archivos',
                rep: {},
                stateLoad: 'Validando',
                textSnackbar: 'Enviado exitosamente',
                timeoutSnackbar: 5000,
                totalValor: 0,
                isLoadingRips: false,
            }
        },
        computed: {
            ...mapGetters(['can']),
        },
        filters: {
            pesoFormat: (valor) => `$${new Intl.NumberFormat().format(valor) || 0}`
        },
        methods: {
            clearResultFields() {
                this.stateLoad = 'Validando';
                this.isResult = false;
                this.isSuccessful = false;
                this.$refs.files.value = null;
                this.totalValor = 0;
                this.loading = false;
                this.inconsistencias = [];
                this.af = [];
                this.fileNameAf = '';
                this.af = [];
            },
            sendFilesForTempSave: async function () {
                try {
                    let isSure = await this.$alertQuestion('¿Está seguro que desea enviar las facturas?')
                    if (isSure.value) {
                        this.stateLoad = 'Enviando'
                        this.isLoadingRips = true
                        let {
                            data
                        } = await axios.post('/api/rips/temporarilySave', this.files)
                        this.isLoadingRips = false
                        this.isActiveSnackbar = true
                        this.clearResultFields()
                        this.files = []
                        this.$alertInfo('Número de remisión', data.paq_id)
                        this.showAfInOtherPage(data.paq_id)
                    }
                } catch (error) {
                    console.error(error)
                }
            },
            setName() {
                if (this.$refs.files.files.length === 0) return this.namefile = 'Seleccionar archivos';
                return this.namefile = (this.$refs.files.files.length === 1) ? this.$refs.files.files[0].name :
                    `${this.$refs.files.files.length} archivos para cargar`
            },
            uploadFiles() {
                this.$refs.files.click()
            },
            validarRips: async function () {
                if (!this.entidad) {
                    this.$alerError('El Campo Entidad es Requerido!');
                    return;
                }
                if (this.$refs.form.validate()) {
                    let header = {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    };
                    let formData = new FormData();

                    let length = this.$refs.files.files.length;

                    for (let i = 0; i < length; i++) {
                        formData.append(this.$refs.files.files[i].name.split('.')[0], this.$refs.files.files[i])
                    }

                    this.dialog = false;
                    this.namefile = 'Seleccionar archivos'
                    this.isLoadingRips = true;
                    this.totalValor = 0;
                    try {
                        let {
                            data
                        } = await axios.post('/api/rips/validar/'+this.entidad, formData, header)
                        this.isLoadingRips = false;
                        this.isResult = true;
                        this.isSuccessful = true;
                        this.message = data.message,
                            this.files = data.files,
                            this.rep = {
                                code: data.files.AF['content'][0][0] || '',
                                razonSocial: data.files.AF['content'][0][1] || '',
                                documentType: data.files.AF['content'][0][2] || '',
                                documentNumber: data.files.AF['content'][0][3] || '',
                            }
                        this.af = data.files.AF['content'] || [],
                            this.fileNameAf = data.files.AF['fileName'] || '',
                            this.af.forEach(inc => {
                                this.totalValor += parseInt(inc[16])
                            })
                    } catch (error) {
                        this.message = error.response.data.message,
                            this.isResult = true;
                        this.isSuccessful = false;
                        this.isLoadingRips = false;
                        this.inconsistencias = error.response.data.inconsistencyMessages || error.response.data.errorMessages
                    }
                }
            },
            showAfInOtherPage: async function (paq_id) {
                try {
                    let id = {
                        id: paq_id
                    }
                    let {
                        data
                    } = await axios.post("/api/pdf/printRipVoucher", id, {
                        responseType: "arraybuffer"
                    })
                    let blob = new Blob([data], {
                        type: "application/pdf"
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");

                } catch (error) {
                    console.error(error)
                }
            }
        }
    }

</script>

<style lang="scss" scoped>

</style>
