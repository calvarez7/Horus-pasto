<template>
    <div>

        <v-tabs centered color="white" icons-and-text v-model="tabsdefault">
            <v-tabs-slider color="primary"></v-tabs-slider>

            <v-tab href="#tab-1" color="black--text">
                Pendientes
                <v-icon color="black">done</v-icon>
            </v-tab>

            <v-tab href="#tab-2">
                Solucionados
                <v-icon color="black">done_all</v-icon>
            </v-tab>

            <v-tab-item :value="'tab-1'">
                <v-card flat>
                    <v-card>
                        <v-card-title>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                            </v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :loading="loading" :items="allpendientes" :search="search">
                            <template v-slot:items="props">
                                <td>{{ props.item.id }}</td>
                                <td>{{ props.item.creado.split('.')[0] }}</td>
                                <td>{{ props.item.sede }}</td>
                                <td>{{ props.item.area }}</td>
                                <td>{{ props.item.categoria }}</td>
                                <td>
                                    <v-chip color="success" text-color="white" v-show="props.item.prioridad == 'Baja'">
                                        {{  props.item.prioridad }}</v-chip>
                                    <v-chip color="warning" text-color="white" v-show="props.item.prioridad == 'Media'">
                                        {{  props.item.prioridad }}</v-chip>
                                    <v-chip color="error" text-color="white" v-show="props.item.prioridad == 'Alta'">
                                        {{  props.item.prioridad }}</v-chip>
                                </td>
                                <td>{{ props.item.asunto }}</td>
                                <td>{{ props.item.estado }}</td>
                                <td>
                                    <v-btn color="primary" :disabled="loading" @click="VerPendiente(props.item)">Ver
                                    </v-btn>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Su búsqueda de "{{ search }}" no encontró resultados.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card>

                    <v-dialog v-model="verpendientes" v-if="verpendientes" persistent width="950">
                        <v-card>
                            <v-toolbar flat color="primary" dark>
                                <v-toolbar-title>Solicitud # {{ pendientes.id }}</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs6>
                                            <v-text-field v-model="pendientes.sede" readonly label="SEDE">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="pendientes.area" readonly label="ÁREA">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-text-field v-model="pendientes.asunto" readonly label="ASUNTO">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-text-field v-model="pendientes.tiempo_estimado" readonly label="Tiempo Estimado">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-textarea v-model="pendientes.descripcion" readonly>
                                                <template v-slot:label>
                                                    <div>
                                                        DESCRIPCIÓN
                                                    </div>
                                                </template>
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs12 v-for="(GesHelp, index) in pendientes.gestion_helpdesks"
                                            :key="`res-${index}`">
                                            <v-btn v-for="(data, index2) in GesHelp.adjuntos_helpdesks" :key="index2">
                                                <a :href="`${data.Ruta}`" target="_blank" style="text-decoration:none">
                                                    <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto {{index2+1}}
                                                </a>
                                            </v-btn>
                                            <v-divider class="my-2"></v-divider>
                                        </v-flex>
                                        <v-flex xs12 v-for="(comenReabierto, i) in comentarioReAbierto" :key="`res2-${i}`">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="i < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Comentario Re Abierto</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="comenReabierto.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comenReabierto.name }}
                                                    {{ comenReabierto.apellido }}
                                                    <strong>Fecha: </strong> {{ comenReabierto.created_at.split('.')[0] }}
                                                </span>
                                                <v-flex>
                                                    <v-btn v-for="(data, index3) in comenReabierto.adjuntos_helpdesks"
                                                        :key="index3">
                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index3+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <v-flex xs12 v-for="(comenpublic, i) in comentarioPublico" :key="`res1-${i}`">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="i < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Comentario</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="comenpublic.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comenpublic.name }}
                                                    {{ comenpublic.apellido }}
                                                    <strong>Fecha: </strong> {{ comenpublic.created_at.split('.')[0] }}
                                                </span>
                                                <v-flex>
                                                    <v-btn v-for="(data, index3) in comenpublic.adjuntos_helpdesks"
                                                        :key="index3">
                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index3+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-toolbar color="primary" dark class="mb-4">
                                                <v-toolbar-title>Agregar comentario</v-toolbar-title>
                                            </v-toolbar>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-textarea v-model="comentario">
                                                <template v-slot:label>
                                                    <div>
                                                        MOTIVO
                                                    </div>
                                                </template>
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs12>
                                            <input id="adjuntos" multiple ref="adjuntos" type="file" />
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" @click="closePendientes()">
                                    Cerrar
                                </v-btn>
                                <v-btn color="success" @click="saveComentario()">
                                    Guardar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                </v-card>

            </v-tab-item>

            <v-tab-item :value="'tab-2'">
                <v-card flat>

                    <v-card>
                        <v-card-title>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search1" append-icon="search" label="Search" single-line
                                hide-details>
                            </v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headersolucionados" :loading="loading" :items="allsolucionado"
                            :search="search1">
                            <template v-slot:items="props">
                                <td>{{ props.item.id }}</td>
                                <td>{{ props.item.creado.split('.')[0] }}</td>
                                <td>{{ props.item.sede }}</td>
                                <td>{{ props.item.area }}</td>
                                <td>{{ props.item.categoria }}</td>
                                <td>
                                    <v-chip color="success" text-color="white" v-show="props.item.prioridad == 'Baja'">
                                        {{  props.item.prioridad }}</v-chip>
                                    <v-chip color="warning" text-color="white" v-show="props.item.prioridad == 'Media'">
                                        {{  props.item.prioridad }}</v-chip>
                                    <v-chip color="error" text-color="white" v-show="props.item.prioridad == 'Alta'">
                                        {{  props.item.prioridad }}</v-chip>
                                </td>
                                <td>{{ props.item.asunto }}</td>
                                <td>{{ props.item.estado }}</td>
                                <td>
                                    <v-btn color="primary" :disabled="loading"
                                        @click="accionesSolucionados(props.item)">Ver
                                    </v-btn>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Su búsqueda de "{{ search1 }}" no encontró resultados.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card>

                    <v-dialog v-model="dialogSolucionado" v-if="dialogSolucionado" persistent width="1000">
                        <v-card>
                            <v-toolbar flat color="primary" dark>
                                <v-toolbar-title>Solicitud # {{ solucionados.id }}</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <span class="grey--text text--lighten-2 caption mr-2">
                                    Clasificación: ({{ rating }})
                                </span>
                                <v-rating v-model="rating" background-color="white" color="yellow accent-4" dense
                                    half-increments hover size="18"></v-rating>
                                <v-btn color="success" @click="calificar(solucionados.id)">
                                    Calificar Helpdesk
                                </v-btn>
                                <v-spacer></v-spacer>
                                <v-btn color="error" dark @click="modelaApertura(solucionados)">
                                    Re Abrir
                                </v-btn>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs6>
                                            <v-text-field v-model="solucionados.sede" readonly label="SEDE">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="solucionados.area" readonly label="ÁREA">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-if="solucionados.categoria" v-model="solucionados.categoria"
                                                readonly label="CATEGORIA">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-if="solucionados.actividad" v-model="solucionados.actividad"
                                                readonly label="ACTIVIDAD">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-text-field v-model="solucionados.asunto" readonly label="ASUNTO">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-textarea v-model="solucionados.descripcion" readonly>
                                                <template v-slot:label>
                                                    <div>
                                                        DESCRIPCIÓN
                                                    </div>
                                                </template>
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs12 v-for="(GesHelp, index3) in solucionados.gestion_helpdesks"
                                            :key="`res4-${index3}`">
                                            <v-btn v-for="(data, index2) in GesHelp.adjuntos_helpdesks" :key="index2">
                                                <a :href="`${data.Ruta}`" target="_blank" style="text-decoration:none">
                                                    <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                    {{index2+1}}
                                                </a>
                                            </v-btn>
                                            <v-divider class="my-2" v-if="index3 < 1"></v-divider>
                                        </v-flex>
                                        <v-flex xs12 v-for="(comenReabierto, i) in comentarioReAbierto" :key="`res1-${i}`">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="i < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Comentario Re Abierto</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="comenReabierto.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comenReabierto.name }}
                                                    {{ comenReabierto.apellido }}
                                                    <strong>Fecha: </strong> {{ comenReabierto.created_at.split('.')[0] }}
                                                </span>
                                                <v-flex>
                                                    <v-btn v-for="(data, index3) in comenReabierto.adjuntos_helpdesks"
                                                        :key="index3">
                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index3+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <v-flex xs12 v-for="(comenpublic, i) in comentarioPublico" :key="`res1-${i}`">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="i < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Comentario</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="comenpublic.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comenpublic.name }}
                                                    {{ comenpublic.apellido }}
                                                    <strong>Fecha: </strong> {{ comenpublic.created_at.split('.')[0] }}
                                                </span>
                                                <v-flex>
                                                    <v-btn v-for="(data, index3) in comenpublic.adjuntos_helpdesks"
                                                        :key="index3">
                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index3+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <v-flex xs12 v-for="(gesHelpSolucion, index4) in solucion"
                                            :key="`res5-${index4}`">
                                            <v-flex xs12>
                                                <v-flex>
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>{{ gesHelpSolucion.descripcionTipo }}
                                                        </v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="gesHelpSolucion.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span>Nombre: {{ gesHelpSolucion.name }} {{ gesHelpSolucion.apellido }}
                                                    Fecha:
                                                    {{ gesHelpSolucion.created_at.split('.')[0] }}</span>
                                            </v-flex>
                                            <v-flex>
                                                <v-btn
                                                    v-for="(dataSolucionado, index6) in gesHelpSolucion.adjuntos_helpdesks"
                                                    :key="index6">
                                                    <a :href="`${dataSolucionado.Ruta}`" target="_blank"
                                                        style="text-decoration:none">
                                                        <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                        {{index6+1}}
                                                    </a>
                                                </v-btn>
                                            </v-flex>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" @click="dialogSolucionado = false">
                                    Cerrar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <v-dialog v-model="dialogReapertura" width="500">
                        <v-card>
                            <v-toolbar flat color="primary" dark>
                                <v-toolbar-title>Motivo de Reapertura</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12>
                                            <v-textarea v-model="reapertura.motivo" label="Motivo">
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs12>
                                            <input id="adjuntos" multiple ref="adjuntos" type="file" />
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" @click="dialogReapertura = false, reapertura.motivo = '',  $refs.adjuntos.value = ''">
                                    Cerrar
                                </v-btn>
                                <v-btn color="success" @click="Reapertura()">
                                    Guardar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                </v-card>
            </v-tab-item>
        </v-tabs>

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
    export default {
        name: 'HelpdeskSolicitud',
        data() {
            return {
                preload: false,
                loading: false,
                search1: '',
                verpendientes: false,
                search: '',
                headersolucionados: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Fecha',
                        align: 'left',
                        value: ''
                    },
                    {
                        text: 'Sede',
                        align: 'left',
                        value: ''
                    },
                    {
                        text: 'Área',
                        align: 'left',
                        value: 'area'
                    },
                    {
                        text: 'Categoria',
                        align: 'left',
                        value: 'categoria'
                    },
                    {
                        text: 'Prioridad',
                        align: 'left',
                        value: 'prioridad'
                    },
                    {
                        text: 'Asunto',
                        align: 'left',
                        value: ''
                    },
                    {
                        text: 'Estado',
                        align: 'left',
                        value: ''
                    },
                    {
                        text: 'Historial',
                        align: 'left'
                    }
                ],
                headers: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Fecha',
                        align: 'left',
                        value: 'creado'
                    },
                    {
                        text: 'Sede',
                        align: 'left',
                        value: 'sede'
                    },
                    {
                        text: 'Área',
                        align: 'left',
                        value: 'area'
                    },
                    {
                        text: 'Categoria',
                        align: 'left',
                        value: 'categoria'
                    },
                    {
                        text: 'Prioridad',
                        align: 'left',
                        value: 'prioridad'
                    },
                    {
                        text: 'Asunto',
                        align: 'left',
                        value: 'asunto'
                    },
                    {
                        text: 'Estado',
                        align: 'left',
                        value: 'estado'
                    },
                    {
                        text: 'Descripción',
                        align: 'left'
                    }
                ],
                rating: 0,
                allpendientes: [],
                pendientes: [],
                allsolucionado: [],
                solucionados: [],
                dialogSolucionado: false,
                dialogReapertura: false,
                reapertura: [],
                comentarioPublico: [],
                comentarioReAbierto:[],
                comentario: "",
                adjuntos: [],
                helpdesk_id: '',
                solucion: [],
                tabsdefault: '',
                motivo: ''
            }
        },
        mounted() {
            this.getPendientes();
            this.getSolucionados();
            this.urlemail();
        },
        methods: {
            urlemail() {
                let url = window.location.href;
                let id = url.split("misolicitud/");
                if (id[1] !== '') {
                    this.search1 = id[1]
                    this.tabsdefault = 'tab-2'
                }
            },
            getPendientes() {
                this.loading = true
                axios.get('/api/helpdesk/pendientesUser')
                    .then(res => {
                        this.loading = false
                        this.allpendientes = res.data;
                    }).catch(err => {
                        this.loading = false
                    })
            },
            getSolucionados() {
                this.loading = true
                axios.get('/api/helpdesk/solucionadosUser')
                    .then(res => {
                        this.loading = false
                        this.allsolucionado = res.data;
                    }).catch(err => {
                        this.loading = false
                    })
            },
            VerPendiente(pendiente) {
                this.comentarioReAbierto = [],
                this.verpendientes = true
                this.pendientes = pendiente
                this.helpdesk_id = this.pendientes.id
                this.comentariosReabierto()
                this.comentariosPublico()
            },
            accionesSolucionados(solucionado) {
                this.comentarioReAbierto = [],
                this.dialogSolucionado = true
                this.solucionados = solucionado
                this.helpdesk_id = this.solucionados.id
                this.rating = parseFloat(this.solucionados.calificacion)
                this.comentariosPublico()
                this.comentariosReabierto()
                this.showSolucion()
            },
            comentariosPublico() {
                axios.post(`/api/helpdesk/showcomentariosPublicos`, {
                        helpdesk_id: this.helpdesk_id
                    })
                    .then(res => {
                        this.comentarioPublico = res.data
                    })
            },
            comentariosReabierto() {
                axios.post(`/api/helpdesk/comentariosReabierto`, {
                        helpdesk_id: this.helpdesk_id
                    })
                    .then(res => {
                        this.comentarioReAbierto = res.data
                    })
            },
            showSolucion() {
                axios.post(`/api/helpdesk/showSolucion`, {
                        helpdesk_id: this.helpdesk_id
                    })
                    .then(res => {
                        this.solucion = res.data
                    })
            },
            closePendientes() {
                this.comentario = ""
                this.verpendientes = false
                this.$refs.adjuntos.value = ""
            },
            saveComentario() {
                this.preload = true
                this.adjuntos = this.$refs.adjuntos.files
                let formData = new FormData();
                formData.append('helpdesk_id', this.pendientes.id)
                formData.append('motivo', this.comentario)
                formData.append('accion', 'Comentarios al Solicitante')
                formData.append('desde', 'misolicitud')
                formData.append('categoria', this.pendientes.Categorias_id)
                formData.append('actividad', this.pendientes.Actividades_id)
                formData.append('tipo_requerimiento', this.pendientes.Requerimiento)
                formData.append('prioridad', this.pendientes.prioridad)
                formData.append('tiempo_estimado', this.pendientes.tiempo_estimado)
                for (let i = 0; i < this.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.adjuntos[i]);
                }
                axios.post(`/api/helpdesk/comentar`, formData)
                    .then(res => {
                        this.preload = false
                        this.getPendientes();
                        this.getSolucionados();
                        this.closePendientes();
                        swal({
                            title: "¡Ha comentado la solicitud con exito!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
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

            modelaApertura(items) {
                this.dialogReapertura = true,
                    this.reapertura = items
            },

            Reapertura() {
                if (!this.reapertura.motivo) {
                    this.$alerError('Debe escribir un motivo de reapertura!');
                    return
                }
                this.preload = true
                this.adjuntos = this.$refs.adjuntos.files
                let formData = new FormData();
                formData.append('helpdesk_id', this.reapertura.id)
                formData.append('motivo', this.reapertura.motivo)
                for (let i = 0; i < this.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.adjuntos[i]);
                }
                axios.post(`/api/helpdesk/reabrir`, formData)
                    .then(res => {
                        this.preload = false
                        this.getPendientes();
                        this.getSolucionados();
                        this.dialogReapertura = false;
                        this.dialogSolucionado = false
                        swal({
                            title: "¡Ha re abierto la solicitud con exito!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
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

            calificar(items) {
                this.preload = true
                let formData = new FormData();
                formData.append('helpdesk_id', items)
                formData.append('calificacion', this.rating)
                axios.post(`/api/helpdesk/calificar`, formData)
                    .then(res => {
                        this.preload = false
                        this.getPendientes();
                        this.getSolucionados();
                        swal({
                            title: "¡Gracias por la calificacion!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
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
            }
        }
    }

</script>
