<template>
    <v-content style="padding: 0px 0px 0px 0px;">
        <v-layout row wrap grid-list-md>
            <v-flex xs12 sm12 md12 lg12 xl12>
                <v-carousel hide-delimiters height="auto">
                    <v-carousel-item id="img" v-for="item in items" :key="item.id"
                        :src="'/storage/imagenesintranet/' + item.imagen">
                        <v-container>
                            <v-layout align-end justify-end column fill-height>
                                <v-flex xs12>
                                    <v-btn round class="animated fadeInUp delay-1s w-50" :href="item.enlace"
                                        v-if="item.enlace != ''">
                                        <span color="error">Click aqui</span>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-carousel-item>
                </v-carousel>
            </v-flex>

            <v-flex xs12 sm12 md8 lg8 xl8 pt-2 pr-1>
                <v-card xs12 md12 sm12>
                    <v-card-title class="headline success" style="color: white">
                        <span class="title layout justify-center" style="border-radius:50px;">NOTICIAS</span>
                    </v-card-title>
                </v-card>
                <v-layout row wrap>
                    <!-- <v-flex pa-1 xs12 md12 sm12>
                        <v-text-field v-model="buscar" prepend-inner-icon="place"></v-text-field>
                    </v-flex> -->
                    <v-flex v-for="card in cards" :key="card.id" sm6 pa-1>
                        <v-card class="sombra_movil rounded" ripple>
                            <v-card-title class="font-weight-black" @click="detalle(card.id)">
                                {{card.titulo}}
                            </v-card-title>
                            <v-img :src="'/storage/imagenesintranet/' + card.imagen" alt="image" max-height="300"
                                max-width="auto" @click="detalle(card.id)">
                            </v-img>
                            <v-card color="#00bcd4!important" class="white--text">
                                <v-card-title @click="detalle(card.id)">
                                    <div class="pa-0">
                                        <div class='line-clamp' v-html="card.subtexto"></div>
                                    </div>
                                </v-card-title>
                            </v-card>

                            <v-card-actions mr-4 pl-5 pr-5>
                                <v-spacer></v-spacer>
                                <div pl-5 pr-5>
                                    <v-btn flat icon color="success" @click="megusta(card)">
                                        <v-icon>thumb_up</v-icon>
                                    </v-btn>
                                    <span class="subheading">{{card.relacion_count}}</span>
                                </div>
                                <v-spacer></v-spacer>
                                <div pl-5 pr-5>
                                    <v-btn flat icon color="red lighten-2" @click="nomegusta(card)">
                                        <v-icon>thumb_down</v-icon>
                                    </v-btn>
                                    <span class="subheading">{{card.relacions_count}}</span>
                                </div>
                                <v-spacer></v-spacer>
                                <v-spacer></v-spacer>

                                <v-btn flat @click="detalle(card.id)">
                                    Leer Mas
                                </v-btn>
                                <v-dialog v-model="dialog1" persistent fullscreen hide-overlay
                                    transition="dialog-bottom-transition">
                                    <v-card>
                                        <v-toolbar dark color="primary">
                                            <v-btn icon dark @click="dialog1 = false">
                                                <v-icon>close</v-icon>
                                            </v-btn>
                                            <v-toolbar-title>{{detalles.titulo}}
                                            </v-toolbar-title>
                                            <v-spacer></v-spacer>
                                        </v-toolbar>
                                        <v-list three-line subheader>
                                            <v-card>
                                                <v-img :src="'/storage/imagenesintranet/' + detalles.imagen" alt="image"
                                                    max-height="400" max-width="auto" v-if="dialog1">
                                                </v-img>
                                            </v-card>
                                        </v-list>
                                        <v-divider></v-divider>
                                        <v-list three-line subheader>
                                            <v-card-text v-html="detalles.subtexto"></v-card-text>

                                            <v-card-text v-html="detalles.texto"></v-card-text>
                                        </v-list>
                                    </v-card>
                                </v-dialog>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
                <div class="text-xs-center">
                    <v-flex ma-2 text-justify-center v-show="pagination.total > '4'">
                        <v-pagination v-model="pagination.current_page" :length="pagination.to" @input="onPageChange">
                        </v-pagination>
                    </v-flex>
                </div>
            </v-flex>

            <v-flex xs12 sm12 md4 lg4 xl4 pt-2 pl-1>
                <v-container fluid pa-0>
                    <v-flex xs12 md12 sm12 v-if="video != ''">
                        <v-card xs12 md12 sm12 v-if="video != ''">
                            <v-card-title class="headline success" style="color: white">
                                <span class="title layout justify-center">MULTIMEDIA</span>
                            </v-card-title>
                        </v-card>
                        <v-layout>
                            <v-flex pa-1 pt-2>
                                <v-tabs dark color="cyan" show-arrows align-with-title>
                                    <v-tabs-slider color="#ffffff"></v-tabs-slider>
                                    <v-tab v-for="i in video" :key="i.id" :href="'#tab-' + i.id">
                                        {{ i.nombre }}
                                    </v-tab>
                                    <v-tabs-items>
                                        <v-tab-item v-for="i in video" :key="i.id" :value="'tab-' + i.id">

                                            <v-container pt-1 pb-1 pl-1>
                                                <v-layout align-center justify-center column fill-height>
                                                    <v-flex xs12 md12 sm12>
                                                        <youtube width="225" height="auto" :video-id="i.link"
                                                            ref="youtube" @playing="playing(i)">
                                                        </youtube>
                                                    </v-flex>
                                                    <v-flex xs12 md12 sm12>
                                                        <span> # visualizaciones</span>
                                                        <v-btn icon>
                                                            <v-icon color="success">visibility
                                                            </v-icon>
                                                            <v-spacer></v-spacer>
                                                            <span class="subheading">{{i.relacion_count}}</span>
                                                        </v-btn>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-tab-item>
                                    </v-tabs-items>
                                </v-tabs>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 md12 sm12>
                        <v-layout>
                            <v-flex xs12 pa-0 pt-0>
                                <v-flex xs12 ml-2 mr-2 pt-2 v-if="dia != ''">
                                    <v-card>
                                        <v-toolbar color="success" class="elevation-0" dark>
                                            <v-toolbar-title class="title layout justify-center">CUMPLEAÑOS
                                            </v-toolbar-title>
                                        </v-toolbar>
                                        <v-list two-line subheader>
                                            <div id="scrolling-techniques" class="scroll-y"
                                                style="max-height: 510px max-width:auto">
                                                <v-container style="height: 450px; width:auto" pa-1>
                                                    <v-flex pa-0 pt-2 v-for="item in dia" :key="item.id">
                                                        <v-card>
                                                            <v-list-tile avatar pa-0>
                                                                <v-list-tile-avatar v-if="item.genero === 'Femenino'">
                                                                    <img
                                                                        src="https://thumbs.dreamstime.com/z/mujer-avatar-con-la-cara-sonriente-personaje-de-dibujos-animados-femenino-empresaria-icono-hermoso-gente-en-fondo-verde-120328294.jpg" />
                                                                </v-list-tile-avatar>
                                                                <v-list-tile-avatar v-else>
                                                                    <img
                                                                        src="https://media.istockphoto.com/vectors/businessman-profile-icon-man-avatar-picture-flat-design-vector-icon-vector-id543042022?k=20&m=543042022&s=170667a&w=0&h=C1Jxvh-xeN3tmfjyo25V6QALWfQvT7NX1QEQ4MaEpa0=" />
                                                                </v-list-tile-avatar>

                                                                <v-list-tile-content pl-2>
                                                                    <v-list-tile-title>
                                                                        {{item.Nombre}}
                                                                    </v-list-tile-title>
                                                                    <v-list-tile-title>
                                                                        {{item.nombreArea}}
                                                                    </v-list-tile-title>
                                                                    <v-list-tile-sub-title>
                                                                        {{item.dia}}
                                                                    </v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                                <v-list-tile-action class="pa-2" wrap>
                                                                    <v-btn icon ripple class="pt-1"
                                                                        @click="felicitacion(item.id)">
                                                                        <img src="images/cake.png"
                                                                            alt="">
                                                                        <!-- <v-icon color="grey lighten-1"  >cake</v-icon> -->
                                                                    </v-btn>
                                                                    <v-snackbar v-model="snackbar"
                                                                        :bottom="y === 'bottom'" :left="x === 'left'"
                                                                        :multi-line="mode === 'multi-line'"
                                                                        :right="x === 'right'" :timeout="timeout"
                                                                        :top="y === 'top'" color="info"
                                                                        :vertical="mode === 'vertical'">
                                                                        {{ text }}
                                                                        <v-btn color="info" flat
                                                                            @click="snackbar = false">
                                                                            Close
                                                                        </v-btn>
                                                                    </v-snackbar>
                                                                    <span
                                                                        class="subheading pt-2 pl-1 pr-1">{{item.felicitacion_count}}</span>
                                                                </v-list-tile-action>
                                                            </v-list-tile>
                                                        </v-card>
                                                    </v-flex>
                                                </v-container>
                                            </div>
                                        </v-list>
                                    </v-card>
                                </v-flex>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-container>
            </v-flex>
        </v-layout>
    </v-content>
</template>
<script>
    import Swal from "sweetalert2";
    import VueYoutube from 'vue-youtube';
    import {
        LazyYoutube,
        LazyVimeo
    } from "vue-lazytube";
    import {
        mapState,
        mapMutations,
        mapGetters
    } from 'vuex';
    import auth from '../../../router/modules/auth';
    import layout from '../../../components/layout/Layout.vue'
    export default {
        name: "Sumintranet",
        data() {
            return {
                buscar: '',
                y: 'top',
                mode: '',
                x: null,
                ver: false,
                snackbar: false,
                timeout: 2000,
                text: 'Felicitacion enviada',
                dialog1: false,
                comentar: true,
                divider: true,
                inset: true,
                length: 5,
                administrador: false,
                editar: false,
                direction: "left",
                fab: true,
                hover: false,
                tabs: null,
                top: false,
                right: true,
                bottom: true,
                left: false,
                transition: "slide-y-reverse-transition",

                radios: "",
                /* CARRUSEL */
                items: [],
                /* BLOG / NOTICIAS */
                cards: null,
                show: false,
                pagination: {
                    total: 0,
                    current_page: 0,
                    per_page: 0,
                    last_page: 0,
                    from: 0,
                    to: 0,
                },
                detalles: [],
                /* MULTIMEDIA */
                video: [],
                /* Cumpleaños */
                dia: [],
                datosmegusta: {
                    megusta: true,
                },
                datosnomegusta: {
                    nomegusta: false,
                },
                valores: "",
                conteo: {},
                datosfelicitacion: {
                    felcitacion: true,
                },
            };
        },
        watch: {
            top(val) {
                this.bottom = !val;
            },
            right(val) {
                this.left = !val;
            },
            bottom(val) {
                this.top = !val;
            },
            left(val) {
                this.right = !val;
            },
        },
        mounted() {
            /* CARRUSEL */
            this.informacionCarrusel();
            /* BLOG / NOTICIAS */
            this.informacionblog();
            /* MULTIMEDIA */
            this.multimedias();
            /* CUMPLEAÑOS */
            this.cumpleaño();
            /* Directorio */
            this.empleados();
        },
        methods: {
            playing(item) {
                axios.post(`/api/intranet/conteovideo/${item.id}`)
                    .then(() => {
                        this.ver = true;
                        this.multimedias();
                    });
            },
            contadorvideo(item) {
                axios.post(`/api/intranet/conteovideo/${item.id}`)
                    .then(() => {
                        this.ver = true;
                    });
            },
            /* carrusel */
            informacionCarrusel() {
                axios.get("api/intranet/carrusel").then((res) => {
                    this.items = res.data;
                });
            },
            /* BLOG */
            informacionblog() {
                window.axios.get("api/intranet/blogs?page=" + this.pagination.current_page)
                    .then((response) => {
                        this.cards = response.data.datos.data;
                        this.pagination.current_page = response.data.pagination.current_page;
                        this.pagination.to = response.data.pagination.to;
                        this.pagination.total = response.data.pagination.total;
                    });
            },
            onPageChange() {
                this.informacionblog();
            },
            detalle(item) {
                axios.get(`api/intranet/detalle/${item}`).then(res => {
                    this.detalles = res.data;
                    this.dialog1 = true;
                });
            },
            /* Multimedia */
            multimedias() {
                axios.get("api/intranet/multimedia").then((res) => {
                    this.video = res.data;
                });
            },
            /* cumpleaños */
            cumpleaño() {
                axios.get("api/intranet/cumpleaños").then((res) => {
                    this.dia = res.data;
                });
            },
            /* Directorio->Empleado */
            empleados() {
                axios.get("api/intranet/directorio").then((res) => {
                    this.datosDirectorio = res.data;
                });
            },
            megusta(item) {
                axios.post(`/api/intranet/opcion/${item.id}`, this.datosmegusta)
                    .then(() => {
                        this.informacionblog();
                    });
            },
            felicitacion(item) {
                axios.post(`/api/intranet/store/${item}`, this.datosfelicitacion)
                    .then(() => {
                        this.snackbar = true;
                        this.cumpleaño();
                    });
            },
            nomegusta(item) {
                axios.post(`/api/intranet/opcion/${item.id}`, this.datosnomegusta)
                    .then(() => {
                        this.informacionblog();
                    });
            },
        },
        computed: {
            ...mapState(['user']),
            ...mapGetters(['fullName', 'can', 'avatar', 'UserEmail', 'id']),
        },
    };

</script>
<style lang="scss" scoped>
    #create .v-speed-dial {
        position: absolute;
    }

    .iframe {
        width: 50%;
        max-width: 300px;
        /* Also helpful. Optional. */
    }

    #create .v-btn--floating {
        position: relative;
    }



    .animated {
        -webkit-animation-duration: 2s;
        animation-duration: 2s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
    }

    .animated.delay-1s {
        -webkit-animation-delay: 1s;
        animation-delay: 1s;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            -webkit-transform: translate3d(0, 100%, 0);
            transform: translate3d(0, 100%, 0);
        }

        to {
            opacity: 1;
            -webkit-transform: none;
            transform: none;
        }
    }

    .fadeInUp {
        -webkit-animation-name: fadeInUp;
        animation-name: fadeInUp;
    }

    .sombra_movil:before {
        content: "";
        position: absolute;
        width: 90%;
        left: 5%;
        top: 100%;
        height: 10px;

        background-image: radial-gradient(ellipse, rgba(0, 0, 0, 0.35) 35%, rgb(255, 255, 255) 80%);
        transition: all 0.3s;
        opacity: 0;
    }

    .sombra_movil:hover {
        /* Mueve la caja hacia arriba */
        transform: translateY(-5px);
        animation: movercaja 1.5s infinite;
    }


    @keyframes movercaja {

        50% {
            transform: translateY(6px);
        }
    }

    .line-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        width: 100%;
        text-emphasis-color: white;
    }

    .color {
        text-decoration-color: #000000;
    }

</style>
