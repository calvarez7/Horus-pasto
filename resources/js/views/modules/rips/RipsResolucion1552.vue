<template>
    <div>
        <v-dialog max-width="800px" persistent v-model="dialog">
            <v-card>
                <v-form >
                    <v-card-title>Cargar archivos de resolucion</v-card-title>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <v-select :items="mes" v-model="cargaInicial.mes" item-value="id" item-text="nombre" label="Mes"></v-select>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-text-field v-model="cargaInicial.anio"  label="Año"></v-text-field>
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
                        <v-btn color="primary" @click="dialog = false,clear()">
                            Cerrar
                        </v-btn>
                        <v-btn color="primary" @click="cargar()">
                            Validar
                        </v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>
        <v-card>
            <v-card-text>
                <v-flex xs12>
                    <v-layout row wrap>
                        <v-flex>
                            <v-btn color="primary" round @click="dialog = true">
                                Cargar Archivos Resolucion
                            </v-btn>
                        </v-flex>
                        <v-flex>
                            <v-btn color="success" round @click="imprimir">
                                Descargar Instructivo
                            </v-btn>
                        </v-flex>
                        <v-flex>
                            <v-btn color="success" round @click="descarga">
                                Descargar Formato
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-card-text>
        </v-card>

        <v-card>
            <v-card-text>
                <v-flex xs12  v-for="(error,index) in errores" :key="index">
                    <v-alert
                        :value="true"
                        :type="type"
                    >
                        {{error}}
                    </v-alert>
                </v-flex>
            </v-card-text>
        </v-card>

    </div>
</template>

<script>
import {
    mapGetters
} from "vuex";
import auth from "../../../router/modules/auth";
export default {
    data: () => {
        return {
            type:"",
            errores: [],
            ruta: '',
            dialog: false,
            files: [],
            loading: false,
            message: '',
            idResolucion: null,
            namefile: 'Seleccionar archivos',
            mes: [
                {id:1,nombre:'ENERO'},
                {id:2,nombre:'FEBRERO'},
                {id:3,nombre:'MARZO'},
                {id:4,nombre:'ABRIL'},
                {id:5,nombre:'MAYO'},
                {id:6,nombre:'JUNIO'},
                {id:7,nombre:'JULIO'},
                {id:8,nombre:'AGOSTO'},
                {id:9,nombre:'SEPTIEMBRE'},
                {id:10,nombre:'OCTUBRE'},
                {id:11,nombre:'NOVIEMBRE'},
                {id:12,nombre:'DICIEMBRE'}
            ],
            cargaInicial:{
                mes:null,
                anio:null,
            },
            fileName: 'Formato',
        }
    },
    methods: {
        setName() {
            if (this.$refs.files.files.length === 0) return this.namefile = 'Seleccionar archivos';
            return this.namefile = (this.$refs.files.files.length === 1) ? this.$refs.files.files[0].name :
                `${this.$refs.files.files.length} archivos para cargar`
        },
        uploadFiles() {
            this.$refs.files.click()
        },
        descarga(){
            axios({
                method: 'post',
                url: '/api/rips/formato',
                responseType: 'blob',
            })
                .then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = 'Formato';
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.preload = false,
                        this.dialog = false
                    this.clear();
                }).catch(err => {
                this.preload = false,
                    console.log(err)
            })
        },
        async cargar() {
            if (this.$refs.files.files.length === 0) {
                this.$alerError("El archivo de carga es requerido");
                return;
            }
            let formData = new FormData();
            formData.append('data', this.$refs.files.files[0]);
            formData.append('mes', this.cargaInicial.mes);
            formData.append('anio', this.cargaInicial.anio);
            const data = await axios.post('/api/rips/resolucion' , formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            this.errores = data.data.message;
            this.type = (data.data.status === 402?'error':'success')
            this.clear();
            this.dialog = false;

        },
        async imprimir() {
            const pdf = {
                type: 'validador1552',
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
                })
                .catch(err => console.log(err.response));
        },
        clear() {
            for (const prop of Object.getOwnPropertyNames(this.cargaInicial)) {
                this.cargaInicial[prop] = null;
            }
            this.$refs.files.value=null;
            this.namefile = 'Seleccionar archivos'
        }

    }
}

</script>

<style lang="scss" scoped>

</style>
