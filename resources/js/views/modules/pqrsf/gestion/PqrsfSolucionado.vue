<template>
    <div>
        <v-card>
            <v-card-title>
                <v-layout row wrap>
                    <span>
                        SOLUCIONADOS
                    </span>
                </v-layout>
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="search" label="Buscar por cedula o radicado" single-line hide-details
                    persistent-hint v-on:keyup.enter="solvedGestion()">
                </v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :loading="loading" :items="allSolved" :search="search">
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.cc }}</td>
                    <td>{{ props.item.doc }}</td>
                    <td>{{ props.item.Solicitud }}</td>
                    <td>{{ props.item.Estado }}</td>
                    <td>{{ props.item.created_at.split(' ')[0] }}</td>
                    <td>{{ props.item.updated_at.split(' ')[0] }}</td>
                    <td>{{ props.item.Canal }}</td>
                    <td>
                        <v-btn :disabled="loading" @click="show(props.item),
                            pqrsfSubcategories(props.item),
                            pqrsfDetallearticulos(props.item),
                            pqrsfCups(props.item),
                            pqrsfArea(props.item),
                            pqrsfIps(props.item),
                            pqrsfEmpleados(props.item)" color="blue" flat icon>
                            <v-icon>library_books</v-icon>
                        </v-btn>
                    </td>
                </template>
                <template v-slot:no-results>
                    <v-alert :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="showPqrsf" width="1200">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-flex xs12 text-xs-center flat dark>
                        <v-toolbar-title>HISTORIAL</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-layout row wrap>
                                    <v-flex xs3 v-show="pqrsf.cc">
                                        <v-text-field v-model="pqrsf.cc" readonly label="CEDULA">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 v-show="pqrsf.doc">
                                        <v-text-field v-model="pqrsf.doc" readonly label="CEDULA">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-text-field v-model="pqrsf.Edad_Cumplida" readonly label="EDAD">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-text-field v-model="pqrsf.Nombre" readonly label="NOMBRE">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-text-field v-model="pqrsf.Apellido" readonly label="APELLIDO">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field v-model="pqrsf.Telefono" readonly label="TELEFONO">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-text-field v-model="pqrsf.Email" readonly label="EMAIL">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-text-field v-model="pqrsf.IPS" readonly label="IPS">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrsfSubcategorias.length > 0">
                                        <v-subheader>SUBCATEGORIA ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrsfSubcategorias" :key="`sub-${index}`">
                                                <v-chip label :selected="data.selected">
                                                    {{data.Nombre}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrsfDetallearticulo.length > 0">
                                        <v-subheader>MEDICAMENTO ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrsfDetallearticulo"
                                                :key="`medi-${index}`">
                                                <v-chip label :selected="data.selected">
                                                    {{data.Producto}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrsfcups.length > 0">
                                        <v-subheader>SERVICIO ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrsfcups" :key="`serv-${index}`">
                                                <v-chip label :selected="data.selected">
                                                    {{data.Nombre}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrareas.length > 0">
                                        <v-subheader>AREA ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrareas" :key="`are-${index}`">
                                                <v-chip label :selected="data.selected">
                                                    {{data.Nombre}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrips.length > 0">
                                        <v-subheader>IPS ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrips" :key="`ips-${index}`">
                                                <v-chip label :selected="data.selected">
                                                    {{data.Nombre}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrempleados.length > 0">
                                        <v-subheader>EMPLEADO ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrempleados" :key="`emp-${index}`">
                                                <v-chip label :selected="data.selected">
                                                    {{data.Nombre}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field v-model="pqrsf.Tiposolicitud" readonly label="TIPO DE SOLICITUD">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field readonly v-model="pqrsf.Priorizacion" label="PRIORIZACIÓN">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-text-field readonly v-model="pqrsf.Reabierta" label="REABIERTA">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field readonly v-model="pqrsf.Atr_calidad" label="ATRIBUTO DE CALIDAD">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-textarea label="DESCRIPCIÓN DEL CASO" v-model="pqrsf.Descripcion" readonly>
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 v-for="(GesPqr, index) in pqrsf.gestion_pqrsfs" :key="`adj-${index}`">
                                        <v-flex v-if="GesPqr.Tipo_id == 3">
                                            <v-btn v-for="(data, index) in GesPqr.adjuntos_pqrsf"
                                                :key="`adj1-${index}`">
                                                <a :href="`${data.Ruta}`" target="_blank" style="text-decoration:none">
                                                    <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                    {{index+1}}
                                                </a>
                                            </v-btn>
                                        </v-flex>
                                    </v-flex>
                                    <v-toolbar v-if="pqrsf.Estado !== 'Anulado'" color="primary" dark class="mb-4">
                                        <v-toolbar-title>Respuesta</v-toolbar-title>
                                    </v-toolbar>
                                    <v-flex xs12 v-for="(GesPqrR, index) in pqrsf.gestion_pqrsfs"
                                        :key="`resp-${index}`">
                                        <v-layout wrap>
                                            <v-flex xs12 v-if="GesPqrR.Tipo_id == 8">
                                                <v-flex xs12>
                                                    <v-textarea v-model="GesPqrR.Motivo" readonly>
                                                        <template v-slot:label>
                                                            <div>
                                                                MOTIVO
                                                            </div>
                                                        </template>
                                                    </v-textarea>
                                                    <span><strong>Nombre:</strong> {{ GesPqrR.name }}
                                                        {{ GesPqrR.apellido }}
                                                        <strong>Fecha:</strong>
                                                        {{ GesPqrR.created_at.split('.')[0] }}
                                                        <strong>Responsable:</strong>
                                                        {{ GesPqrR.Responsable }}</span>
                                                </v-flex>
                                                <template>
                                                    <v-container>
                                                        <v-layout>
                                                            <v-flex xs6 md3
                                                                v-for="(data, index) in GesPqrR.adjuntos_pqrsf"
                                                                :key="index">
                                                                <v-btn>
                                                                    <a :href="`${data.Ruta}`" target="_blank"
                                                                        style="text-decoration:none">
                                                                        <v-icon color="dark">mdi-cloud-upload
                                                                        </v-icon>
                                                                        Adjunto
                                                                        {{index+1}}
                                                                    </a>
                                                                </v-btn>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </template>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-toolbar v-if="pqrsf.Estado == 'Anulado'" color="primary" dark class="mb-4">
                                        <v-toolbar-title>Anulado</v-toolbar-title>
                                    </v-toolbar>
                                    <v-flex xs12 v-for="(GesPqrR, index) in pqrsf.gestion_pqrsfs" :key="`r-${index}`">
                                        <v-layout wrap>
                                            <v-flex xs12 v-if="GesPqrR.Tipo_id == 4">
                                                <v-flex xs12>
                                                    <v-textarea v-model="GesPqrR.Motivo" readonly>
                                                        <template v-slot:label>
                                                            <div>
                                                                MOTIVO
                                                            </div>
                                                        </template>
                                                    </v-textarea>
                                                    <span><strong>Nombre:</strong> {{ GesPqrR.name }}
                                                        {{ GesPqrR.apellido }}
                                                        <strong>Fecha:</strong>
                                                        {{ GesPqrR.created_at.split('.')[0] }}</span>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-toolbar v-if="pqrsf.Estado !== 'Anulado'" color="primary" dark class="mb-4">
                                        <v-toolbar-title>Solución</v-toolbar-title>
                                    </v-toolbar>
                                    <v-flex xs12 v-for="(GesPqrR, index) in pqrsf.gestion_pqrsfs" :key="`rs-${index}`">
                                        <v-layout wrap>
                                            <v-flex xs12 v-if="GesPqrR.Tipo_id == 9">
                                                <v-flex xs12>
                                                    <v-textarea v-model="GesPqrR.Motivo" readonly>
                                                        <template v-slot:label>
                                                            <div>
                                                                MOTIVO
                                                            </div>
                                                        </template>
                                                    </v-textarea>
                                                    <span><strong>Nombre:</strong> {{ GesPqrR.name }}
                                                        {{ GesPqrR.apellido }}
                                                        <strong>Fecha:</strong>
                                                        {{ GesPqrR.created_at.split('.')[0] }}</span>
                                                </v-flex>
                                                <template>
                                                    <v-container>
                                                        <v-layout>
                                                            <v-flex xs6 md3
                                                                v-for="(data, index) in GesPqrR.adjuntos_pqrsf"
                                                                :key="index">
                                                                <v-btn>
                                                                    <a :href="`${data.Ruta}`" target="_blank"
                                                                        style="text-decoration:none">
                                                                        <v-icon color="dark">mdi-cloud-upload
                                                                        </v-icon>
                                                                        Adjunto
                                                                        {{index+1}}
                                                                    </a>
                                                                </v-btn>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </template>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>

    </div>
</template>
<script>
    export default {
        name: "PqrsfSolucionado",
        data() {
            return {
                search: '',
                headers: [{
                        text: 'Radicado',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Cedula',
                        align: 'left',
                        value: 'cc'
                    },
                    {
                        text: 'Cedula Super',
                        align: 'left',
                        value: 'doc'
                    },
                    {
                        text: 'Tipo de solicitud',
                        align: 'left',
                        value: 'Solicitud'
                    },
                    {
                        text: 'Estado',
                        align: 'left',
                        value: 'Estado'
                    },
                    {
                        text: 'Fecha Generado',
                        align: 'left',
                        value: 'created_at'
                    },
                    {
                        text: 'Fecha Solu',
                        align: 'left',
                        value: 'updated_at'
                    },
                    {
                        text: 'Canal',
                        align: 'left',
                        value: 'Canal'
                    },
                    {
                        text: '',
                        align: 'Right'
                    }
                ],
                allSolved: [],
                loading: false,
                pqrsf: [],
                showPqrsf: false,
                pqrsfSubcategorias: [],
                pqrsfDetallearticulo: [],
                pqrsfcups: [],
                pqrareas: [],
                pqrips: [],
                pqrempleados: []
            }
        },
        mounted() {

        },
        methods: {
            pqrsfEmpleados(pqr) {
                axios.post(`/api/pqrsf/pqrsfEmpleados/${pqr.id}`).then(res => {
                    this.pqrempleados = res.data;
                });
            },
            pqrsfIps(pqr) {
                axios.post(`/api/pqrsf/pqrsfIps/${pqr.id}`).then(res => {
                    this.pqrips = res.data;
                });
            },
            pqrsfArea(pqr) {
                axios.post(`/api/pqrsf/pqrsfAreas/${pqr.id}`).then(res => {
                    this.pqrareas = res.data;
                });
            },
            pqrsfCups(pqr) {
                axios.post(`/api/pqrsf/pqrsfCups/${pqr.id}`).then(res => {
                    this.pqrsfcups = res.data;
                });
            },
            pqrsfDetallearticulos(pqr) {
                axios.post(`/api/pqrsf/pqrsfDetallearticulos/${pqr.id}`).then(res => {
                    this.pqrsfDetallearticulo = res.data;
                });
            },
            pqrsfSubcategories(pqr) {
                axios.post(`/api/pqrsf/pqrsfsubcategorias/${pqr.id}`).then(res => {
                    this.pqrsfSubcategorias = res.data;
                });
            },
            solvedGestion() {
                this.$emit("updateCount", this.canal);
                this.loading = true;
                this.data = {
                    buscar: this.search
                }
                axios.post('/api/pqrsf/solvedGestion', this.data)
                    .then(res => {
                        this.loading = false
                        this.allSolved = res.data;
                    });
            },
            show(pqr) {
                this.pqrsf = pqr
                this.showPqrsf = true
            }
        },
    }

</script>
