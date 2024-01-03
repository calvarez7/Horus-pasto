<template>
    <div>

        <v-card>

            <v-form method="post" autocomplete="off">
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex xs6>
                            <v-text-field v-model="fullName" label="Nombre" readonly></v-text-field>
                        </v-flex>
                        <v-flex xs6>
                            <v-text-field v-model="UserEmail" label="E-mail" readonly>
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4>
                            <v-autocomplete v-model="sede" :items="allSedes" item-text="nombre" item-value="nombre"
                                label="Selecciona la sede a la que perteneces"></v-autocomplete>
                        </v-flex>
                        <v-flex xs3>
                            <v-autocomplete v-model="allAreas.id" :items="allAreas" item-text="Nombre" item-value="id"
                                label="Área" @change="getCategorias(allAreas.id)"></v-autocomplete>
                        </v-flex>
                        <v-flex xs3>
                            <v-autocomplete v-model="categorias.id" :items="allCategorias" item-text="Nombre"
                                item-value="id" label="Categoria" @change="mensajeAlerta()"></v-autocomplete>
                        </v-flex>
                        <v-flex xs2>
                            <v-autocomplete :items="['Alta', 'Media', 'Baja']" v-model="prioridad" label="Prioridad">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12>
                            <v-alert :value="prioridad === 'Alta'" color="error" icon="priority_high" outline>
                                Alta: Se requiere solución inmediata
                                porque el servicio o proceso se suspende
                                o interrumpe a causa de esta falla o ausencia. (Tiempo de solución 24 horas máximo).
                            </v-alert>
                            <v-alert :value="prioridad === 'Media'" color="error" icon="priority_high" outline>
                                Media: La no solución implica
                                incumplimiento normativo, posible evento adverso, riesgo latente de fallo o suspensión
                                del servicio. (Tiempo de solución 2 días a 15 días máximo).
                            </v-alert>
                            <v-alert :value="prioridad === 'Baja'" color="error" icon="priority_high" outline>
                                Baja: La necesidad no está afectando el
                                resultado del servicio, pero significa una mejora del proceso e impacto en el resultado.
                                (tiempo de solución 8 días a 3 meses dependiendo de la solicitud).
                            </v-alert>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field v-model="asunto" label="Asunto" placeholder="¿Cúal es el motivo?">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea label="Descripción" v-model="descripcion"
                                placeholder="Describe detalladamente los requerimientos"></v-textarea>
                        </v-flex>
                        <v-flex xs5>
                            <input id="adjuntos" multiple ref="adjuntos" type="file" />
                        </v-flex>
                        <v-flex xs5>
                            <v-btn color="success" @click="DescargarCartilla()">Descargar Cartilla ABC</v-btn>
                        </v-flex>
                        <v-flex xs2>
                            <v-btn color="primary" @click="sendSolicitud()">Generar solicitud</v-btn>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-form>
        </v-card>
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
<script>
    import Swal from 'sweetalert2';
    import {
        mapGetters
    } from 'vuex';
    export default {
        name: 'helpdesk',
        data: () => ({
            data: {},
            categorias: [],
            adjuntos: [],
            allAreas: [],
            allCategorias: [],
            sede: '',
            asunto: '',
            descripcion: '',
            prioridad: '',
            preload: false,
            alerta: null,
            allSedes: []
        }),
        computed: {
            ...mapGetters(['fullName', 'UserEmail']),
        },

        mounted() {
            this.getAreas();
            this.getSedes();
        },
        methods: {
            getAreas() {
                axios.get('/api/helpdesk/getAreaEnable')
                    .then(res => {
                        this.allAreas = res.data;
                    });
            },
            getSedes() {
                axios.get('/api/helpdesk/sedes')
                    .then(res => {
                        this.allSedes = res.data;
                    });
            },
            getCategorias(id) {
                axios.get(`/api/helpdesk/getcategoria/${id}`)
                    .then(res => {
                        this.allCategorias = res.data;
                    });
            },
            clearSolicitud() {
                this.sede = ""
                this.asunto = ""
                this.descripcion = ""
                this.$refs.adjuntos.value = ""
                this.allAreas.id = ""
                this.prioridad = ""
                this.categorias.id = ""
            },
            sendSolicitud() {
                this.preload = true
                this.data.adjuntos = this.$refs.adjuntos.files
                let formData = new FormData();
                formData.append('sede', this.sede)
                formData.append('asunto', this.asunto)
                formData.append('descripcion', this.descripcion)
                formData.append('area', this.allAreas.id)
                formData.append('prioridad', this.prioridad)
                formData.append('categoria', this.categorias.id)
                for (let i = 0; i < this.data.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.data.adjuntos[i]);
                }
                axios.post(`/api/helpdesk/create`, formData)
                    .then(res => {
                        this.preload = false
                        const datos = res.data.data.id
                        this.clearSolicitud()
                        swal({
                            title: "Solicitud generada con exito, El ID de la solicitud es " + datos,
                            text: `Tener en cuenta que el responsable de la solicitud puede: Aprobar o Anular la solicitud según pertinencia, así mismo cambiar la prioridad de la solicitud según revisión conjunta y reasignar cuando la solicitud fue escalada a un área errónea, lo que implica otros días de retraso`,
                            icon: "success",
                            buttons: ["Confirmar"],
                        });
                    }).catch(err => {
                        this.preload = false
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        swal({
                            title: msg,
                            text: " ",
                            type: "error",
                            icon: "error",
                        });
                    })
            },

            DescargarCartilla() {
                const a = document.createElement("a");
                a.href = '/recomendaciones/cartillaABC/ABC_DE_GESTION_ADMINISTRATIVA.pdf';
                a.download = 'ABC_DE_GESTION_ADMINISTRATIVA.pdf';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

            },

            mensajeAlerta() {
                const index = this.allCategorias.find(item => item.id === this.categorias.id);
                if (index.alerta != null) {
                    swal({
                        title: index.alerta,
                        text: "Para tener en cuenta",
                        icon: "warning",
                        buttons: ["Confirmar"],
                    });
                }
            }
        }
    }

</script>
