<template>
    <div>
        <v-navigation-drawer :clipped="drawer.clipped" :fixed="drawer.fixed" :permanent="drawer.permanent"
            :mini-variant="drawer.mini" class="secondary scroll-down" width="210" app stateless>
            <v-list>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-Flex ma-auto>
                            <img width="50" alt="" src="/storage/images/ojohorus.png">
                        </v-Flex>
                    </v-list-tile-action>
                    <v-list-tile-content>
                    </v-list-tile-content>
                </v-list-tile>
                <VDivider />

                <template v-for="item in items">
                    <v-list-tile :to="item.ruta" :key="item.tittle" v-if="can(item.can)">
                        <v-tooltip right>
                            <v-list-tile-action slot="activator">
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-tile-action>
                            <span>{{ item.title }}</span>
                        </v-tooltip>

                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <VDivider :key="item.tittle" />
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar class="elevation-1" color="primary" dark app height="50" :fixed="toolbar.fixed"
            :clipped-left="toolbar.clippedLeft">
            <v-toolbar-side-icon @click.stop="toggleMiniDrawer" class="hidden-md-and-up"></v-toolbar-side-icon>
            <v-spacer></v-spacer>
            <!-- <v-toolbar-items color="white" v-if="can('index.home')"> -->
            <v-toolbar-items color="white">
                <v-btn icon fab @click.stop="show = !show">
                    <v-badge  overlap color="red">
                        <template v-slot:badge>
                            <span dark>{{notifications.length}}</span>
                        </template>
                        <v-icon>notifications_none</v-icon>
                    </v-badge>
                </v-btn>
            </v-toolbar-items>

            <v-toolbar-items>
                <v-menu offset-y>
                    <template v-slot:activator="{on}">
                        <v-btn v-on="on" small flat>
                            {{ nameUser }}
                            <v-flex ml-2>
                                <v-avatar size="40">
                                    <img :src="avatar" />
                                </v-avatar>
                            </v-flex>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-tile @click="passwordReset()">
                            <v-list-tile-title>Cambio de Contraseña</v-list-tile-title>
                        </v-list-tile>
                        <v-list-tile @click="logout()">
                            <v-list-tile-title>Cerrar Sesión</v-list-tile-title>
                        </v-list-tile>
                    </v-list>
                </v-menu>
            </v-toolbar-items>
        </v-toolbar>
        <template>
            <v-layout row justify-center>
                <v-dialog v-model="cambioPassword" persistent max-width="600px">
                    <v-card>
                        <v-toolbar dark color="primary">
                            <v-toolbar-title>Bienvenido al cambio de Contraseña</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-text-field label="Nueva Contraseña *" type="password" required
                                            v-model="data.password"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-text-field label="Confirma Contraseña *" type="password" required
                                            v-model="data.password_confirmation"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <small style="color:red">Los Campos con * son Requeridos</small>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" @click="cambioPassword = false">cerrar</v-btn>
                            <v-btn color="info" @click="ChangePassword()">Enviar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
        </template>
        <template>
            <v-layout>
                <v-navigation-drawer v-model="show" fixed temporary right>
                    <v-toolbar color="primary" dark>
                        <v-toolbar-title>notificaciones</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn flat @click="leertodas()">Leer todas</v-btn>
                    </v-toolbar>
                    <v-list three-line>
                        <template v-for="item in notifications">
                            <v-subheader v-if="item.header" :key="item.header">
                                {{ item.header }}
                            </v-subheader>
                            <v-divider></v-divider>
                            <v-list-tile>
                                <v-icon>{{item.data.avatar}}</v-icon>
                                <v-list-tile-content class="pa-2" @click="leer(item.id)">
                                    <v-list-tile-title>{{item.data.mensaje }} {{ item.data.Usuario[0].name}}
                                    </v-list-tile-title>
                                    <v-list-tile-sub-title v-html="item.data.creacion"></v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </template>
                    </v-list>
                </v-navigation-drawer>
            </v-layout>
        </template>
    </div>
</template>
<script>
    import {
        TipoOrdenService,
        PasswordResetService,
        ChangePasswordService
    } from '../../services';
    import {
        mapState,
        mapMutations,
        mapGetters
    } from 'vuex';
    export default {
        name: 'Layout',
        data() {
            return {
                data: {
                    token: '',
                    password: '',
                    password_confirmation: '',
                },
                cambioPassword: false,
                items: [{
                        title: 'Inicio',
                        icon: 'home',
                        ruta: '/',
                        can: 'any'
                    },
                    {
                        title: 'Módulo Agenda',
                        icon: 'event_note',
                        ruta: '/agenda',
                        can: 'agendas.enter'
                    },
                    {
                        title: 'Módulo Citas',
                        icon: 'event',
                        ruta: '/citas',
                        can: 'citas.enter'
                    },
                    {
                        title: 'Módulo de Gestión Sucesos',
                        icon: 'event_available',
                        ruta: '/evento',
                        can: 'any'
                    },
                    {
                        title: 'Módulo Aseguramiento',
                        icon: 'perm_identity',
                        ruta: '/aseguramiento',
                        can: 'aseguramiento.enter'
                    },
                    {
                        title: 'Módulo Contratación',
                        icon: 'mdi-square-inc-cash',
                        ruta: '/manual-tarifario',
                        can: 'contratacion.enter'
                    },
                    {
                        title: 'Módulo Medicamentos',
                        icon: 'mdi-pill',
                        ruta: '/dispensacion',
                        can: 'medicamentos.enter'
                    },
                    {
                        title: 'Módulo Bodegas',
                        icon: 'store',
                        ruta: '/bodegas',
                        can: 'bodegas.enter'
                    },
                    {
                        title: 'Módulo Medico',
                        icon: 'mdi-stethoscope',
                        ruta: '/historiaIntegral',
                        can: 'panel-medico.enter'
                    },
                    {
                        title: 'Módulo Autorización',
                        icon: 'gavel',
                        ruta: '/autorizacion',
                        can: 'autorizacion.enter'
                    },
                    {
                        title: 'Módulo Gestión',
                        icon: 'list_alt',
                        ruta: '/transcripcion',
                        can: 'transcripcion.enter'
                    },
                    {
                        title: 'Módulo Historico',
                        icon: 'mdi-history',
                        ruta: '/auditoria',
                        can: 'auditoria.enter'
                    },
                    {
                        title: 'Módulo Caracterización',
                        icon: 'mdi-account-card-details',
                        ruta: '/caracterizacion/registro',
                        can: 'registro.caracterizacion'
                    },
                    {
                        title: 'Módulo Gestion Del Riesgo',
                        icon: 'record_voice_over',
                        ruta: '/gestionriesgo',
                        can: 'gestionriesgo.enter'
                    },
                    {
                        title: 'Módulo Empalme',
                        icon: 'mdi-account-network',
                        ruta: '/empalme/registro',
                        can: 'empalme.enter'
                    },
                    {
                        title: 'Módulo Helpdesk',
                        icon: 'help',
                        ruta: '/helpdesk',
                        can: 'any'
                    },
                    {
                        title: 'Módulo Imagenologia',
                        icon: 'camera_roll',
                        ruta: '/imagenologia',
                        can: 'imagenologia.enter'
                    },
                    {
                        title: 'Módulo Referencia',
                        icon: 'assignment',
                        ruta: '/referencia',
                        can: 'referencia.enter'
                    },
                    {
                        title: 'Módulo Concurrencia',
                        icon: 'mdi-clipboard-alert',
                        ruta: '/concurrencia',
                        can: 'referencia.enter'
                    },
                    {
                        title: 'Módulo Validador',
                        icon: 'playlist_add_check',
                        ruta: '/rips',
                        can: 'rips.entrar'
                    },
                    {
                        title: 'Módulo Fias',
                        icon: 'mdi-cloud-print',
                        ruta: '/fias',
                        can: 'rips.entrar'
                    },
                    {
                        title: 'Módulo Cuentas Medicas',
                        icon: 'mdi-bank',
                        ruta: '/cuentasmedicas',
                        can: 'cuentasmedicas.entrar'
                    },
                    {
                        title: 'Módulo Tutelas',
                        icon: 'mdi-scale-balance',
                        ruta: '/tutelas',
                        can: 'tutelas.enter'
                    },
                    {
                        title: 'Módulo Teleapoyo',
                        icon: 'mdi-account-switch',
                        ruta: '/teleconcepto',
                        can: 'teleconcepto.enter'
                    },
                    {
                        title: 'Módulo Salud Ocupacional',
                        icon: 'mdi-folder-account',
                        ruta: '/saludocupacional',
                        can: 'saludocupacional.enter'
                    },
                    {
                        title: 'Módulo Oncología',
                        icon: 'mdi-heart',
                        ruta: '/oncologia',
                        can: 'oncologia.enter'
                    },
                    {
                        title: 'Módulo Domiciliaria',
                        icon: 'mdi-ambulance',
                        ruta: '/domiciliaria',
                        can: 'domiciliaria.enter'
                    },
                    {
                        title: 'Módulo Restaurante',
                        icon: 'mdi-food',
                        ruta: '/vagout',
                        can: 'vagout.enter'
                    },
                    {
                        title: 'Módulo Reportes',
                        icon: 'mdi-chart-bar',
                        ruta: '/reportes',
                        can: 'reporte.enter'
                    },
                    {
                        title: 'Módulo Pqrsf',
                        icon: 'mdi-file-powerpoint-box',
                        ruta: '/pqrsf',
                        can: 'pqrsf.enter'
                    },
                    {
                        title: 'Sumintranet',
                        icon: 'mdi-domain',
                        ruta: '/sumintranet',
                        can: 'enter.sumintranet'
                    },
                    {
                        title: 'Gestion de Talento Humano',
                        icon: 'mdi-archive',
                        ruta: '/gestionhumana',
                        can: 'enter.gestionhumana'
                    },
                    {
                        title: 'Lideres',
                        icon: 'supervised_user_circle',
                        ruta: '/lideres',
                        can: 'lideres.enter'
                    },
                    {
                        title: 'Gestión Covid-19',
                        icon: 'mdi-basket-unfill',
                        ruta: '/resultados',
                        can: 'covid.enter'
                    },
                    {
                        title: 'Ruta de atención Covid-19',
                        icon: 'mdi-chemical-weapon',
                        ruta: '/covi',
                        can: 'rutacovid.enter'
                    },
                    {
                        title: 'Módulo Red',
                        icon: 'mdi-access-point-network',
                        ruta: '/red',
                        can: 'red'
                    },
                    {
                        title: 'Módulo Sarlaf',
                        icon: 'assignment_ind',
                        ruta: '/sarlaft',
                        can: 'sarlaft.enter'
                    },
                    {
                        title: 'Módulo Solicitudes',
                        icon: 'content_paste',
                        ruta: '/solicitudes',
                        can: 'solicitudes.entrar'
                    },{
                        title: 'Módulo Desarrollo',
                        icon: 'work',
                        ruta: '/desarrollo',
                        can: 'admin.enter'
                    },
                    {
                        title: 'Módulo Administración',
                        icon: 'settings',
                        ruta: '/admin',
                        can: 'admin.enter'
                    }
                ],
                drawer: {
                    // sets the open status of the drawer
                    open: true,
                    // sets if the drawer is shown above (false) or below (true) the toolbar
                    clipped: false,
                    // sets if the drawer is CSS positioned as 'fixed'
                    fixed: true,
                    // sets if the drawer remains visible all the time (true) or not (false)
                    permanent: true,
                    // sets the drawer to the mini variant, showing only icons, of itself (true)
                    // or showing the full drawer (false)
                    mini: true
                },
                toolbar: {
                    //
                    fixed: true,
                    // sets if the toolbar contents is leaving space for drawer (false) or not (true)
                    clippedLeft: false
                },
                show: null,
                notifications: [],
                conteo: []

            }
        },
        computed: {
            ...mapState(['user']),
            ...mapGetters(['fullName', 'can', 'avatar', 'UserEmail']),
            nameUser() {
                return this.fullName.substring(0, 20);
            },
        },
        // created(){
        //     Echo.channel('notificaciones')
        //         .listen('NotificacionEvent', (e) => {
        //             this.$alerSuccess('Se a actualizado el usuario en aseguramiento!');
        //             this.notificaciones();
        //         });
        // },
        // mounted() {
        //     this.notificaciones();
        // },
        methods: {
            passwordReset: async function () {
                let data = {
                    email: this.UserEmail
                }
                try {
                    await PasswordResetService.create(data);
                    let token = await PasswordResetService.find(data);
                    this.data.token = token.token
                    this.cambioPassword = true;
                } catch (error) {
                    console.log(error)
                }

            },
            ChangePassword: async function () {
                let datos = {
                    token: this.data.token,
                    email: this.UserEmail,
                    password: this.data.password,
                    password_confirmation: this.data.password_confirmation
                }
                if (!this.data.password) {
                    swal("El campo Nueva contraseña es requerido")
                    return;
                } else if (this.data.password.length < 5) {
                    swal("La contraseña debe ser mayor a 5 caracteres")
                    return;
                }
                try {
                    await ChangePasswordService.reset(datos);
                    this.cambioPassword = false;
                } catch (error) {
                    console.log(error)
                }

            },
            // toggles the drawer variant (mini/full)
            toggleMiniDrawer() {
                this.drawer.mini = !this.drawer.mini
                this.permisoAsignadoHelp();
            },
            logout() {
                axios.get('/api/auth/logout')
                    .then(res => {
                        localStorage.removeItem('_token');
                        this.goLogin();
                    });
            },
            goLogin() {
                this.$router.push('/login')
            },
            notificaciones() {
                axios.get('/api/intranet/traer').then(res => {
                    this.notifications = res.data.no_leidos;
                });
            },
            leertodas(item) {
                axios.post("/api/intranet/leertodos").then(() => {
                    this.notificaciones();
                })
            },
            leer(item) {
                axios.put(`/api/intranet/leer/${item}`).then(() => {
                    this.notificaciones();
                })
            }
        },
    }

</script>
<style scope>
    .scroll-down {
        overflow: scroll !important;
        scrollbar-width: none;
    }

    .scroll-down::-webkit-scrollbar {
        display: none;
    }

</style>
