<template>
    <v-card>
        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Tranquilo procesamos tu solicitud !
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogFilter" scrollable max-width="900px">
            <v-card>
                <v-list two-line subheader>
                    <v-list-tile
                        v-for="item in resultadoFiltro.principales"
                        :key="item.id"
                        avatar
                        @click="redireccionar(item,1)"
                    >
                        <v-list-tile-avatar>
                            <v-icon class="grey lighten-1 white--text">folder</v-icon>
                        </v-list-tile-avatar>

                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.nombre }}</v-list-tile-title>
                            <v-list-tile-sub-title>{{ item.ruta.replace('adjuntos_intranet','Documentos') }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile
                        v-for="item in resultadoFiltro.secundarias"
                        :key="item.id"
                        avatar
                        @click="redireccionar(item,1)"
                    >
                        <v-list-tile-avatar>
                            <v-icon class="grey lighten-1 white--text">folder</v-icon>
                        </v-list-tile-avatar>

                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.nombre }}</v-list-tile-title>
                            <v-list-tile-sub-title>{{ item.ruta.replace('adjuntos_intranet','Documentos') }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile
                        v-for="item in resultadoFiltro.archivos"
                        :key="item.id"
                        avatar
                        @click="redireccionar(item,2)"
                    >
                        <v-list-tile-avatar>
                            <v-icon class="blue white--text">file_present</v-icon>
                        </v-list-tile-avatar>

                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.nombre }}</v-list-tile-title>
                            <v-list-tile-sub-title>{{ item.ruta.replace('adjuntos_intranet','Documentos') }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-alert v-if="resultadoFiltro.archivos.length == 0 && resultadoFiltro.secundarias.length == 0 && resultadoFiltro.principales == 0" :value="true" type="error">
                    No se encontraron elementos que coincidan con los criterios de busqueda
                </v-alert>
            </v-card>
        </v-dialog>
        <template>
            <v-layout row justify-center>
                <v-dialog v-model="dialogCarpeta" persistent max-width="400px">
                    <v-card>
                        <v-card-title class="headline grey lighten-2">
                            <span >Nueva Carpeta</span>
                        </v-card-title>
                        <v-card-text>
                            <form>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12>
                                            <v-text-field outline label="Nombre" v-model="nuevaCarpeta" required></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" dark  @click="dialogCarpeta = false">Cerrar</v-btn>
                            <v-btn color="success" dark @click="guardarCarpeta">Crear</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogSubida" width="500">
                    <v-card>
                        <v-card-title class="headline grey lighten-2" primary-title>Cargar Archivos</v-card-title>
                        <v-card-text>
                            <form>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-btn color="primary" dark outline round @click="uploadFiles">
                                            <v-icon left>backup</v-icon>
                                            Subir archivos
                                        </v-btn>
                                    </v-flex>
                                    <v-flex xs12>
                                        <input hidden multiple name="file" ref="files" type="file" @change="setName" />
                                        <VTextField disabled name="name"
                                                    :rules="[v => !!v || 'Se necesitan cargar archivos para validar']" single-line
                                                    v-model="namefile" @click="uploadFiles" />
                                    </v-flex>
                                </v-layout>
                            </form>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" dark @click="dialogSubida = false">Cerrar</v-btn>
                            <v-btn color="success" @click="archivos" dark>Guardar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
        </template>
        <v-card-title>
            <v-toolbar color="light-blue" light>
                <v-tooltip top>
                    <template v-slot:activator="{ on }">
                        <v-btn fab color="success" small v-on="on" @click="dialogCarpeta = true;" v-if="can('gestiondocumental.edicion.sumintranet')">
                            <v-icon>create_new_folder</v-icon>
                        </v-btn>
                    </template>
                    <span>Nueva Carpeta</span>
                </v-tooltip>

                <v-tooltip top v-if="nivel > 1">
                    <template v-slot:activator="{ on }">
                        <v-btn fab color="warning" small v-on="on" @click="dialogSubida = true" v-if="can('gestiondocumental.edicion.sumintranet')">
                            <v-icon>post_add</v-icon>
                        </v-btn>
                    </template>
                    <span>Añadir Adjunto</span>
                </v-tooltip>
                <v-spacer/>
                <v-text-field @click:append="informacion = ''" solo append-icon="cancel" hide-details single-line v-model="informacion" label="Buscar..." :loading="loading"></v-text-field>
                <v-btn icon color="success" v-if="informacion.length >= 4" @click="getFilesFilters">
                    <v-icon>arrow_forward</v-icon>
                </v-btn>
                <v-spacer></v-spacer>
                <v-toolbar-items class="white--text">
                    <v-breadcrumbs :items="items">
                        <template v-slot:item="props">
                            <a style="color:#fff" @click="carpetaMiga(props.item.id,props.item.ruta,props.item.text,props.item.tipo)">{{ props.item.text.toUpperCase() }}</a>
                        </template>
                    </v-breadcrumbs>
                </v-toolbar-items>
            </v-toolbar>
        </v-card-title>
        <v-card-text class="mt-4 mb-4">
            <v-container grid-list-xl text-xs-center>
                <v-layout row wrap>
                    <v-flex v-for="i in carpetas" md2 sm2 xs12 class="mb-5" @click="mostarsubcarpeta(i.id,i.ruta,i.nombre)" :key="i">
                        <v-card class="mb-5">
                            <div class="folder">
                                <div class="paper one"></div>
                                <div class="paper two"></div>
                                <div class="paper three"></div>
                                <div class="paper four"></div>
                            </div>
                        </v-card>
                        <span><h4>{{i.nombre}}</h4></span>
                    </v-flex>

                    <v-flex v-for="i in subCarpetas" md2 sm2 xs12 class="mb-5" @click="mostarSubcarpetaEnSubcarpeta(i.id,i.ruta,i.nombre)" :key="i">
                        <v-card class="mb-5">
                            <div class="folder">
                                <div class="paper one"></div>
                                <div class="paper two"></div>
                                <div class="paper three"></div>
                                <div class="paper four"></div>
                            </div>
                        </v-card>
                        <span><h4>{{i.nombre}}</h4></span>
                    </v-flex>
                    <v-flex v-for="i in subCarpetas2" md2 sm2 xs12 class="mb-5" @click="mostarSubcarpetaEnSubcarpeta(i.id,i.ruta,i.nombre)" :key="i">
                        <v-card class="mb-5">
                            <div class="folder">
                                <div class="paper one"></div>
                                <div class="paper two"></div>
                                <div class="paper three"></div>
                                <div class="paper four"></div>
                            </div>
                        </v-card>
                        <span><h4>{{i.nombre}}</h4></span>
                    </v-flex>

                    <v-flex md2 sm2 xs12 class="mb-5" v-for="i in listaArchivos" :key="i">
                        <v-card class="mb-5">
                            <v-btn
                                small
                                absolute
                                dark
                                fab
                                icon
                                color="error" @click="eliminarElemento(i.id,'archivo',i.ruta)" style="width: 30px;height: 30px;margin-left: 7%" v-if="can('gestiondocumental.edicion.sumintranet')">
                                <v-icon>delete</v-icon>
                            </v-btn>
                            <div class="document loading" @click="consultarAdjunto(i.ruta)">
                                <div class="top"></div>
                                <div class="bottom">
                                    <div class="icon"><span>Ai</span></div>
                                    <div class="bar"></div>
                                    <div class="bar"></div>
                                    <div class="bar"></div>
                                </div>
                            </div>
                        </v-card>
                        <span><h4 @click="consultarAdjunto(i.ruta)">{{i.nombre}}</h4></span>
                    </v-flex>

                </v-layout>
            </v-container>
        </v-card-text>
        <v-divider></v-divider>
    </v-card>
</template>
<style lang="scss">
$papers: 4;
$colors:(
    "one":#ffadad,
    "two":#ffd6a5,
    "three":#fdffb6,
    "four": #9bf6ff
);

$bg-color: #DFDFDF;
$fold-color: #F7F7F6;
$active-color: #868686;

body {
    //back side of the folder
    .folder{
        cursor: pointer;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -50%);
        background-color: #FFD485;
        width: 110px;
        height: 60px;
        border-radius: 10px;
        //folder label
        &:before {
            width: 55px;
            height: 25px;
            border-radius: 5px;
            content: '';
            background-color: #FFD485;
            position: absolute;
            top: -10px;
            left: 0px;
        }
        //front side of the folder
        &:after {
            display: block;
            width: 110px;
            height: 60px;
            border-radius: 10px;
            content: '';
            transform: skew(0deg);
            background-color: #ffe1a8;
            transition: all .2s;
        }

        .paper {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-right: -50%;
            transform: translate(-50%, -50%);
            background-color: whitesmoke;
            width: 100px;
            height: 50px;
            transition: all .2s;
            //adding bg color for each paper div
            @each $color, $i in $colors {
                &.#{$color} {
                    background-color: $i;
                }
            }
        }

        &:hover{
            //adding front lapel of folder animation
            &:after {
                transform: skew(-20deg);
                margin-left:25px;
            }
            //adding paper animation
            @for $i from 1 through $papers {
                .paper:nth-child(#{$i}) {
                    transform: rotate(#{$i}0deg) translate(-80px, -80px);
                }
            }
        }
    }

    .document {
        cursor: pointer;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -10px;
        margin-right: -50%;
        transform: translate(-50%, -50%);
        width: 70px;
        height: 100px;
        border-radius: 10px;

        //position: absolute;
        //margin: 32px;
        //cursor: pointer;
        //transform: translate(0, -24%);

        &.loading {
            .bottom {
                .bar{
                    &:nth-child(2)::before {
                        animation: loading 1s infinite;
                    }
                    &:nth-child(3)::before {
                        animation: loading 1s .1s infinite;
                    }
                    &:nth-child(4)::before {
                        animation: loading 1s .2s infinite;
                    }
                }

            }
        }

        &:hover {
            .bottom{
                .bar, .icon {
                    background: $active-color;
                }
                .icon.fa {
                    color: $active-color;
                }
            }
        }

        .bottom {
            width: 70px;
            height: 80px;
            background-color: $bg-color;
            padding-top: 32px;
            box-sizing: border-box;
            .icon {
                position: absolute;
                width: 30px;
                height: 30px;
                background: #ccc;
                margin: 0 auto;
                border-radius: 4px;
                top: 14px;
                left: 13px;
                padding: 6px;
                box-sizing: border-box;
                text-align: center;
                span {
                    font-size: 25px;
                    font-weight: bold;
                    color: $bg-color;
                    line-height: 20px;

                }
            }
            .bar, .icon {
                transition: all .5s;
            }

            .fa.icon {
                background-color: transparent;
                color: #ccc;
                font-size: 80px;
                top: 4px;
                left: 0px;
                text-align: left;
            }
            .bar {
                width: 50px;
                height: 5px;
                border-radius: 4px;
                background: #ccc;
                margin: 5px auto;
                position: relative;
                &::before {
                    content: '';
                    position: absolute;
                    left: 0;
                    top: 10%;
                    height: 80%;
                    width: 0%;
                    background-color: $active-color;
                }
            }

            @keyframes loading {
                from {
                    width: 0%;
                }
                to {
                    width: 100%;
                }
            }
        }
        .top {
            width: 50px;
            height: 20px;
            background-color: $bg-color;
            &::before {
                content: '';
                position: absolute;
                width: 0;
                height: 0;
                //background: blue;
                right: 0;
                border-top: solid 20px transparent;
                border-left: 20px solid $fold-color;
                box-shadow: -8px 8px 8px #ccc;
            }
        }

    }
}

</style>

<script>
import {
    AdjuntoService
} from '../../../services';
import {
    mapGetters
} from "vuex";
export default {
    data() {
        return {
            resultadoFiltro: {
                principales:[],
                secundarias:[],
                archivos:[],
            },
            dialogFilter:false,
            loading:false,
            listaInformacion:[],
            informacion:'',
            carpetas:[],
            subCarpetas:[],
            subCarpetas2:[],
            listaArchivos:[],
            nuevaCarpeta:null,
            preload:false,
            nivel:1,
            direccionPadre:null,
            idPadre:null,
            nombrePadre:null,
            ver: false,
            dialogCarpeta:null,
            dialogSubida:null,
            namefile: 'Seleccionar archivos',
            items: [
                {
                    text: 'Documentos',
                    disabled: false,
                    href: 'breadcrumbs_dashboard',
                    id: null,
                    ruta: null
                }
            ]
        };
    },

    mounted() {
        this.carpeta();
    },
    created() {
    },
    methods: {
        listardatos(item) {
            axios.get(`/api/intranet/buscarcarpeta/${item}`).then((res) => {
                this.verdatos = res.data;
                this.ver = true
            });
        },
        carpeta() {
            this.preload = true;
            this.listaArchivos = [];
            axios.get("api/intranet/ver").then((res) => {
                this.carpetas = res.data;
                this.subCarpetas = [];
                this.subCarpetas2 = [];
                this.preload = false;
                this.nivel = 1;
            }).catch(e => {
                this.preload = false;
                console.log(e);
            })
        },
        mostarsubcarpeta(id,ruta,nombre) {
            this.preload = true;
            this.direccionPadre = ruta;
            this.idPadre = id;
            this.nombrePadre = nombre;
            this.nivel = 2;
            this.listaArchivos = [];
            axios.get("api/intranet/versubcarpetas/"+id).then((res) => {
                this.carpetas = [];
                this.subCarpetas = res.data.carpetas;
                this.listaArchivos = res.data.archivos
                this.subCarpetas2 = [];
                this.preload = false;
                this.getMigas(id,ruta,nombre,'carpeta');
            }).catch(e => {
                this.preload = false;
                console.log(e);
            })
        },
        lista() {
            axios.get("api/intranet/gestion").then((res) => {
                this.documentos = res.data;
            });
        },
        guardarCarpeta() {
            this.preload = true;
            if(this.nivel === 1){
                axios.post("api/intranet/carpeta", {nombre:this.nuevaCarpeta}).then((res) => {
                    this.nuevaCarpeta = null;
                    this.preload = false;
                    this.dialogCarpeta = false;
                    this.carpeta();

                }).catch(e => {
                    this.preload = false;
                    this.dialogCarpeta = false;
                    console.log(e);
                })
            }else if(this.nivel === 2){
                const data = {
                    rutaAnterior: this.direccionPadre,
                    nombre: this.nuevaCarpeta,
                    id: this.idPadre
                };
                axios.post("api/intranet/subCarpetaEnCarpeta",data).then((res) => {
                    this.nuevaCarpeta = null;
                    this.preload = false;
                    this.dialogCarpeta = false;
                    this.items.pop();
                    this.mostarsubcarpeta(this.idPadre,this.direccionPadre,this.nombrePadre)
                }).catch(e => {
                    this.preload = false;
                    this.dialogCarpeta = false;
                    console.log(e);
                })
            }else if(this.nivel === 3){
                const data = {
                    rutaAnterior: this.direccionPadre,
                    nombre: this.nuevaCarpeta,
                    id: this.idPadre
                };
                axios.post("api/intranet/subCarpetaEnSubCarpeta",data).then((res) => {
                    this.nuevaCarpeta = null;
                    this.preload = false;
                    this.dialogCarpeta = false;
                    this.items.pop();
                    this.mostarSubcarpetaEnSubcarpeta(this.idPadre,this.direccionPadre,this.nombrePadre)
                }).catch(e => {
                    this.preload = false;
                    this.dialogCarpeta = false;
                    console.log(e);
                })

            }
        },
        async consultarAdjunto(ruta) {
            this.preload = true
            try {
                let adj = await AdjuntoService.get(ruta);
                let blob = new Blob([adj[1]], {
                    type: adj[0]
                });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                window.open(link.href, "_blank");
                this.preload = false
            } catch (error) {
                this.preload = false
            }
        },
        onPickFile() {
            this.$refs.fileInput.click();
        },
        onFilePicked(e) {
            let file = e.target.files;
            this.archivo.ruta = file;
        },
        onFilePickedarchivo(e) {
            let file = e.target.files;
            this.Agregararchivos.ruta = file;
        },
        guardarsubcarpeta() {
            if (!this.subcarpeta.carpeta_id) {
                swal({
                    title: "carpeta Obligatoria",
                    text: "  ",
                    timer: 2000,
                    icon: "error",
                    buttons: false
                });
            } else if (!this.subcarpeta.nombre) {
                swal({
                    title: "nombre Obligatoria",
                    text: "  ",
                    timer: 2000,
                    icon: "error",
                    buttons: false
                });
            } else {
                axios.post("api/intranet/subcarpeta", this.subcarpeta).then(res => {
                    this.subcarpeta.carpeta_id = '',
                        this.subcarpeta.nombre = '',
                        swal({
                            title: "",
                            text: "Creado con exito",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    this.dialog1 = false;
                })
                this.lista();
                this.carpeta();
                this.mostarsubcarpeta();
            }
        },
        archivos() {
            console.log(this.$refs.files.files);
            this.preload = true;
            let formData = new FormData();
            let length = this.$refs.files.files.length;
            for (let i = 0; i < length; i++) {
                formData.append('adjuntos[]', this.$refs.files.files[i])
            }
            axios.post("api/intranet/archivos/"+this.idPadre+"/"+this.nivel, formData).then(res => {
                this.preload = false;
                this.$refs.files.value = null;
                this.namefile = 'Seleccionar archivos'
                this.dialogSubida = false;
                if(parseInt(this.nivel) === 2){
                    this.items.pop();
                    this.mostarsubcarpeta(this.idPadre,this.direccionPadre,this.nombrePadre)
                }else{
                    this.items.pop();
                    this.mostarSubcarpetaEnSubcarpeta(this.idPadre,this.direccionPadre,this.nombrePadre)
                }
                // swal({
                //     title: "",
                //     text: "Creado con exito",
                //     timer: 2000,
                //     icon: "success",
                //     buttons: false
                // });
                // this.dialog3 = false;
                // this.lista();
                // this.carpeta();
                // this.mostarsubcarpeta();
            }).catch(e => {
                this.preload = false;
            })
        },
        AgregarCarpeta(item) {
            this.dialog4 = true;
            this.Agregar.subcarpeta_id = item.id;
            // this.Agregar.ruta = item.ruta;
        },
        GuardarAgregadoCarpeta() {
            if (!this.Agregar.nombre) {
                swal({
                    title: "",
                    text: "Nombre es obligatorio",
                    timer: 2000,
                    icon: "error",
                    buttons: false
                });
            } else {
                axios.post("api/intranet/AgregarCarpeta", this.Agregar).then(res => {
                    swal({
                        title: "",
                        text: "Creado con exito",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                    this.lista();
                    this.carpeta();
                    this.mostarsubcarpeta();
                    this.dialog4 = false;
                });
            }
        },
        show() {
            axios.get("api/intranet/show").then((res) => {
                this.busca = res.data;
            });
        },
        mostarSubcarpetaEnSubcarpeta(id,ruta,nombre){
            this.preload = true;
            this.direccionPadre = ruta;
            this.idPadre = id;
            this.nombrePadre = nombre;
            this.nivel = 3;
            this.listaArchivos = [];
            axios.get("api/intranet/mostarSubcarpetaEnSubcarpeta/"+id).then((res) => {
                this.getMigas(id,ruta,nombre,'subCarpeta');
                this.carpetas = [];
                this.subCarpetas = [];
                this.subCarpetas2 = res.data.carpetas;
                this.listaArchivos = res.data.archivos
                this.preload = false;
            }).catch(e => {
                this.preload = false;
            })
        },
        getMigas(id,ruta,nombre,tipo){
            this.items.push({
                text: nombre,
                disabled: false,
                href: 'breadcrumbs_dashboard',
                id: id,
                ruta: ruta,
                tipo: tipo
            })
        },
        carpetaMiga(id,ruta,nombre,tipo){
            if(!id){
                this.carpeta();
                this.items = [
                    {
                        text: 'Documentos',
                        disabled: false,
                        href: 'breadcrumbs_dashboard',
                        id: null,
                        ruta: null,
                        tipo: null
                    }
                ];
            }else{
                const obj = this.items.findIndex(s => parseInt(s.id) === parseInt(id) && s.tipo == tipo);
                const ultimo = this.items[obj];
                this.items.splice(parseInt(obj));
                console.log(ultimo);
                if(ultimo.tipo === 'subCarpeta'){
                    this.mostarSubcarpetaEnSubcarpeta(ultimo.id,ultimo.ruta,ultimo.text)
                }else{
                    this.mostarsubcarpeta(ultimo.id,ultimo.ruta,ultimo.text)
                }
            }
        },
        uploadFiles() {
            this.$refs.files.click()
        },
        setName() {
            if (this.$refs.files.files.length === 0) return this.namefile = 'Seleccionar archivos';
            return this.namefile = (this.$refs.files.files.length === 1) ? this.$refs.files.files[0].name :
                `${this.$refs.files.files.length} archivos para cargar`
        },
        eliminarElemento(id,tipo,ruta){
            // console.log(id,tipo,this.nivel);
            const data = {
                id:id,
                tipo:tipo,
                nivel:this.nivel,
                ruta:ruta
            };
            axios.post('api/intranet/eliminarElemento',data).then(res => {
                if(parseInt(this.nivel) === 2){
                    this.items.pop();
                    this.mostarsubcarpeta(this.idPadre,this.direccionPadre,this.nombrePadre)
                }else{
                    this.items.pop();
                    this.mostarSubcarpetaEnSubcarpeta(this.idPadre,this.direccionPadre,this.nombrePadre)
                }
            })
        },
        getFilesFilters(){
            this.preload = true;
            axios.get('api/intranet/getFilesFilters/'+this.informacion).then(res => {
            console.log(res);
            this.resultadoFiltro.principales = res.data.carpetasPrincipales;
            this.resultadoFiltro.secundarias = res.data.carpetaSecundarias;
            this.resultadoFiltro.archivos = res.data.archivos;
            this.preload = false;
            this.dialogFilter = true;
            }).catch(e => {
                console.log(e);
                this.preload = false;
            })
        },
        redireccionar(item,tipo) {
                if (item.carpeta_id) {
                    this.migasAutogeneradas(item.rutaPrimaria);
                    this.mostarsubcarpeta(item.idPrimaria, item.rutaPrimaria, item.nombrePrimaria)
                    this.dialogFilter = false;
                } else if(item.subcarpeta_id) {
                    this.migasAutogeneradas(item.rutaSecundaria);
                    this.mostarSubcarpetaEnSubcarpeta(item.idSecundaria, item.rutaSecundaria, item.nombreSecundaria)
                    this.dialogFilter = false;
                }else{
                    this.items= [
                        {
                            text: 'Documentos',
                            disabled: false,
                            href: 'breadcrumbs_dashboard',
                            id: null,
                            ruta: null
                        }
                    ]
                    this.carpeta();
                    this.dialogFilter = false;

                }
        },
        migasAutogeneradas(ruta){
            const data = {rutas:ruta}
            axios.post('api/intranet/migasAutogeneradas',data).then(res =>{
                this.items= [
                    {
                        text: 'Documentos',
                        disabled: false,
                        href: 'breadcrumbs_dashboard',
                        id: null,
                        ruta: null
                    }
                ]
                res.data.forEach(s =>{
                    this.getMigas(s.id,s.ruta,s.nombre,s.tipo);
                })
                this.items.pop();
            })
        }
    },
    computed: {
        ...mapGetters(['can']),
        Busqueda() {
            return this.busca.filter(item => {
                return item.nombre.toLowerCase().includes(this.buscar.toLowerCase());
            });
        },
    },
};

</script>

<style scoped>
.card {
    width: 250px;
    padding: 0px;
    height: 60px;
}

.v-card.on-hover.theme--dark {
    background-color: rgba(rgb(196, 35, 35), 0.8)
}

.truncate {
    width: 160px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

</style>




