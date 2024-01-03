<template>
    <div>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }" v-on="on">
                <v-btn icon dark class="white--text" v-show="ver" small color="primary" @click="ver = false">
                    <v-icon>arrow_back</v-icon>
                </v-btn>
            </template>
            <span>Volver</span>
        </v-tooltip>

        <v-layout align-start justify-start fill-height wrap>
            <v-flex v-for="i in datos" :key="i.id" sm3 pb-2 v-show="!ver">
                <v-hover v-slot="{ hover }" open-delay="200" >
                    <v-card flat class="card" @click="abrir(i)" :elevation="hover ? 16 : 2">
                        <v-card-text class="">
                            <v-icon left>folder</v-icon>
                            {{i.nombre}}
                        </v-card-text>
                    </v-card>
                </v-hover>
            </v-flex>

            <!-- <v-flex v-for="i in participantes" :key="i.id" sm3 pb-2 v-show="ver">
                <v-hover v-slot="{ hover }" open-delay="200">
                    <v-card flat class="card" @click="abrirs(i)" :elevation="hover ? 16 : 2">
                        <v-card-text class="">
                            <v-icon left>folder</v-icon>
                            {{i.nombre}} h
                        </v-card-text>
                    </v-card>
                </v-hover>
            </v-flex> -->
        </v-layout>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn v-on="on"  icon color="primary" v-on:click="dialog4 = true" v-show="!ver" class="white--text">
                        <v-icon dark>folder</v-icon>
                    </v-btn>
                </template>
                <span>Agregar carpeta</span>
            </v-tooltip>
            <v-dialog v-model="dialog4" persistent max-width="600px">
                <v-card>
                    <v-toolbar class="success white--text headline justify-center">
                        <span>Agregar Carpeta a Subcarpeta}{</span>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12 sm12 md12>
                                        <v-text-field v-model="Agregar.nombre">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs10 sm10 md10>
                                        <v-autocomplete v-model="Agregar.subcarpeta_id" :items="versubcarpeta"
                                            label="Seleccione Carpeta" item-text="nombre" item-value="id">
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                    </v-card-text>
                    <v-toolbar>
                        <v-spacer></v-spacer>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" dark @click="dialog4 = false">Cancelar
                            </v-btn>
                            <v-btn color="blue" dark @click="GuardarAgregadoCarpetas()">
                                Guardar</v-btn>
                        </v-card-actions>
                    </v-toolbar>
                </v-card>
            </v-dialog>

            <v-tooltip bottom v-show="!ver">
                <template v-slot:activator="{ on }">
                    <v-btn v-on="on" icon color="primary" v-on:click="dialog5 = true" v-show="ver" class="white--text">
                        <v-icon dark>folder</v-icon>
                    </v-btn>
                </template>
                <span>Agregar carpeta</span>
            </v-tooltip>
            <v-dialog v-model="dialog5" persistent max-width="600px">
                <v-card>
                    <v-toolbar class="success white--text headline justify-center">
                        <span>Agregar Carpeta a Subcarpeta</span>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12 sm12 md12>
                                        <v-text-field v-model="Agrega.nombre">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs10 sm10 md10>
                                        <v-autocomplete v-model="Agrega.subcarpeta_id" :items="datossubcarpeta"
                                            label="Seleccione Carpeta" item-text="nombre" item-value="id">
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                    </v-card-text>
                    <v-toolbar>
                        <v-spacer></v-spacer>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" dark @click="dialog5 = false">Cancelar
                            </v-btn>
                            <v-btn color="blue" dark @click="GuardarAgregadoCarpeta()">
                                Guardar</v-btn>
                        </v-card-actions>
                    </v-toolbar>
                </v-card>
            </v-dialog>

        </v-card-actions>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                ver: false,
                dialog4: false,
                dialog5: false,
                counter: 0,
                participantes: [],
                archivos: [],
                Agrega: {},
                Agregar: {
                },
                versubcarpeta: [],
                datossubcarpeta: []
            }
        },
        props: {
            datos: {
                typeof: Object
            },

            mostrar: [Boolean]
        },
        mounted() {
            this.mostarsubcarpeta();
            this.mostarcarpeta();

        },
        methods: {
            mostarsubcarpeta() {
                axios.get("api/intranet/versubcarpetas").then((res) => {
                    this.versubcarpeta = res.data;
                });
            },
            mostarcarpeta() {
                axios.get("api/intranet/vercarpetas").then((res) => {
                    this.datossubcarpeta = res.data;
                });
            },
            abrir(items) {
                axios.get(`/api/intranet/shows/${items.id}`).then((res) => {
                    this.participantes = res.data;
                    this.ver = true;
                });
            },
            abrirs(items) {
                axios.get(`/api/intranet/showsubcarpetas/${items.id}`).then((res) => {
                    this.participantes = res.data;
                    this.ver = true;
                    console.log('gas', this.participantes);
                });
            },
            onFilePickedarchivo(e) {
                let file = e.target.files;
                this.Agregararchivos.ruta = file;
            },
            GuardarAgregadoCarpeta() {
                if (!this.Agrega.nombre) {
                    swal({
                        title: "",
                        text: "Nombre es obligatorio",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                } else {
                    axios.post("api/intranet/subcapetaAgregarCarpeta", this.Agrega).then(res => {
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
                        this.dialog5 = false;
                    });
                }
            },
            GuardarAgregadoCarpetas() {
                if (!this.Agregar.nombre) {
                    swal({
                        title: "",
                        text: "Nombre es obligatorio",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                } else if (!this.Agregar.subcarpeta_id) {
                    swal({
                        title: "",
                        text: "Categoria es obligatorio",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                } else {
                    axios.post("api/intranet/crearcarpetasubcarpeta", this.Agregar).then(res => {
                        this.Agregar.nombre = '',
                            this.Agregar.subcarpeta_id = '',
                            this.dialog4 = false;

                        swal({
                            title: "",
                            text: "Creado con exito",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.dialog4 = false;
                        this.lista();
                        this.carpeta();
                        this.mostarsubcarpeta();
                    });
                }
            },
        }
    }

</script>

<style>

</style>
