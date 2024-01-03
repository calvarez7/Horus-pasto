<template>
    <v-layout>
        <v-dialog v-model="importFile" max-width="500px">
            <v-card>
                <v-card-title>
                    <span class="headline">Importar archivo</span>
                </v-card-title>

                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap align-center>
                            <v-flex xs12 sm6 md3>
                                <v-text-field v-model="importContrato.contrato_id" readonly label="id contrato">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md9>
                                <v-text-field v-model="importContrato.entidad" readonly label="Entidad">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="importContrato.manual" readonly label="Manual">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-if="importContrato.tarifa == 0" readonly label="Tarifa" value="Pleno">
                                </v-text-field>
                                <v-text-field v-else readonly label="Tarifa" v-model="importContrato.tarifa">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                                <VAutocomplete label="Familia" :items="capitulos" item-text="Nombre" item-value="id"
                                    v-model="importContrato.familia" />
                            </v-flex>
                            <v-flex xs3 sm3 md3>
                                <v-btn color="success" @click="importTarifa()">
                                    <v-icon>attachment</v-icon>
                                </v-btn>
                            </v-flex>
                            <input type="file" v-show="false" @change="onFilePicked" ref="file">
                            <v-flex xs9 sm9 md9>
                                <v-text-field v-model="nameFile" name="name" readonly label="nombre" id="id">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>

                <template v-for="error in noexistecup">
                    <v-flex xs12 px-12>
                        <v-alert :value="true" type="error">
                            El codigo "{{error.Codigo}}" no registra en cups.
                        </v-alert>
                    </v-flex>
                </template>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" flat @click="clearFile()">Cancelar</v-btn>
                    <v-btn color="success darken-1" flat @click="saveFile()">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialog" width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline" v-if="contratoSave">Agregar contrato</span>
                    <span class="headline" v-else>Editar contrato</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap align-center>
                            <v-flex xs12>
                                <v-layout row wrap>
                                    <v-flex xs8>
                                        <v-autocomplete name="name" :items="tipomanuales" item-text="Nombre"
                                            item-value="Nombre" v-model="data.Manual" label="Manual tarifario">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs2 v-show="data.Manual != 'Tarifa Propia'">
                                        <v-checkbox color="primary" v-model="data.pleno" :label="`Pleno`"></v-checkbox>
                                    </v-flex>
                                    <v-flex xs2 v-show="!data.pleno && data.Manual != 'Tarifa Propia'">
                                        <v-text-field name="name" label="Tarifa" autocomplete="off"
                                            v-model="data.Tarifa"></v-text-field>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-select :items="ambitos" label="Ambito" v-model="data.Ambito"></v-select>
                                    </v-flex>
                                    <template v-if="contratoSave">
                                        <v-flex xs6>
                                            <v-text-field :items="anio" label="Año" v-model="data.Anio"></v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-autocomplete :items="entidades" item-text="nombre" item-value="id"
                                                v-model="data.entidad_id" label="Entidad">
                                            </v-autocomplete>
                                        </v-flex>
                                    </template>
                                    <template v-else>
                                        <v-flex xs6>
                                            <v-text-field :items="anio" label="Año" v-model="data.Anio">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-text-field readonly v-model="data.nombre" label="Entidad">
                                            </v-text-field>
                                        </v-flex>
                                    </template>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="clearUpdateContrato()">Cancelar</v-btn>
                    <v-btn color="success" v-if="contratoSave" @click="saveContrato()">Guardar</v-btn>
                    <v-btn color="success" v-else @click="updateContrato()">Actualizar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="contratosTable" width="1000px">
            <v-card>
                <v-card-title>
                    <span class="headline">Contratos</span>
                    <v-spacer></v-spacer>
                    <v-btn round color="primary" @click="createTarifa()">
                        <v-icon left>exposure_plus_1</v-icon>
                        agregar contrato
                    </v-btn>
                </v-card-title>
                <v-card-text>
                    <v-data-table :headers="headersContrato" :items="contratos" class="elevation-1">
                        <template v-slot:items="props">
                            <td>{{ props.item.id }}</td>
                            <td class="text-xs-right">{{ props.item.Tarifa != 0? `${props.item.Tarifa}%` : 'Pleno' }}
                            </td>
                            <td class="text-xs-right">{{ props.item.Manual }}</td>
                            <td class="text-xs-right">{{ props.item.Ambito }}</td>
                            <td class="text-xs-right">{{ props.item.nombre }}</td>
                            <td class="text-xs-right">
                                <v-btn color="warning" icon @click="editContrato(props.item)">
                                    <v-icon>edit</v-icon>
                                </v-btn>
                                <v-btn color="error" icon @click="disableContrato(props.item)">
                                    <v-icon>clear</v-icon>
                                </v-btn>
                                <v-btn color="success" icon @click="openFile(props.item)">
                                    <v-icon>cloud_upload</v-icon>
                                </v-btn>
                            </td>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog v-model="loading" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Un momento por favor...
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-container pa-1>
                        <v-card-title>
                            <v-flex sm8 xs8>
                                <v-autocomplete v-model="Prestador_id" append-icon="search" label="Buscar prestador..."
                                    :items="fullnameprestador" item-text="fullname" item-value="id" hide-details
                                    @input="buscarSedesPrestador()"></v-autocomplete>
                            </v-flex>
                            <v-flex xs12 mt-3 v-show="Prestador_id">
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <v-autocomplete v-model="data.Sedeproveedor_id" append-icon="search"
                                            label="Buscar sede..." :items="fullnamesede" item-text="fullname"
                                            item-value="id" hide-details @input="getContratos()"></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs2 v-show="data.Sedeproveedor_id">
                                        <v-btn round @click="contratosTable = true" color="primary" dark>
                                            ver contratos
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs12 mt-3 v-show="data.Sedeproveedor_id">
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <v-select v-model="contrato.Contrato_id" append-icon="search"
                                            label="seleccionar contrato" :items="fullcontrato" item-text="fullname"
                                            item-value="id" hide-details @input="getCapitulosPrestador()"></v-select>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs12 mt-3 v-show="data.Sedeproveedor_id && contrato.Contrato_id">
                                <v-layout row wrap>
                                    <v-flex xs4 pr-2>
                                        <v-select v-model="contrato.capitulo" append-icon="search" :items="capitulos"
                                            item-text="Nombre" hide-details label="seleccionar unidad funcional"
                                            @input="getCupsCapitulo()" chips multiple return-object>
                                            <v-list-tile slot="prepend-item" ripple @click="toggle"
                                                v-if="capitulos.length > 0">
                                                <v-list-tile-action>
                                                    <v-icon
                                                        :color="contrato.capitulo.length > 0 ? 'indigo darken-4' : ''">
                                                        {{ icon }}</v-icon>
                                                </v-list-tile-action>
                                                <v-list-tile-title>Seleccionar todo</v-list-tile-title>
                                            </v-list-tile>
                                            <v-divider v-if="capitulos.length > 0" slot="prepend-item" class="mt-2">
                                            </v-divider>
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 v-for="opc in contrato.opcUnidadFuncional" :key="opc.label">
                                        <v-layout row wrap>
                                            <v-flex xs2 pr-2>
                                                <v-checkbox color="primary" v-model="opc.all"
                                                    :label="`aplicar a todo ${opc.label}`"></v-checkbox>
                                            </v-flex>
                                            <v-flex xs3 v-show="!opc.all">
                                                <v-autocomplete v-model="opc.cup1" append-icon="search" label="rango 1"
                                                    :items="filtercups(opc.id)" item-text="Codigo" item-value="id"
                                                    hide-details></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs3 pl-2 v-show="!opc.all">
                                                <v-autocomplete v-model="opc.cup2" append-icon="search" label="rango 2"
                                                    :items="filtercups(opc.id)" item-text="Codigo" item-value="id"
                                                    hide-details></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs4 pt-3>
                                                <v-btn round color="warning" small @click="addUnidadFuncional(opc)">
                                                    Agregar
                                                </v-btn>
                                                <v-btn round color="info" small @click="removeRangoFuncional(opc)">
                                                    Quitar rango
                                                </v-btn>
                                                <v-btn round color="error" small @click="removeUnidadFuncional(opc)">
                                                    Quitar unidad funcional</v-btn>
                                                <v-btn round color="success" small @click="getTarifasPrestador(opc)">
                                                    Filtrar
                                                </v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                                <!-- <v-btn color="success" @click="saveCupsContrato()">Guardar</v-btn> -->
                            </v-flex>
                        </v-card-title>
                    </v-container>
                </v-card>
            </v-flex>
            <!-- datatable -->
            <v-flex xs12 pt-3>
                <v-card>
                    <v-container pa-1>
                        <v-card-title>
                            <v-spacer></v-spacer>
                            <v-flex sm5 xs12>
                                <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line
                                    hide-details></v-text-field>
                            </v-flex>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="tarifas" :search="search">
                            <template v-slot:items="props">
                                <td>{{ props.item.id }}</td>
                                <td>{{ props.item.nombre }}</td>
                                <td class="text-xs-center">{{ props.item.Codigo }}</td>
                                <td class="text-xs-center">{{ props.item.Cup }}</td>
                                <td class="text-xs-center">{{ props.item.Manual }}</td>
                                <td class="text-xs-center">
                                    {{ props.item.Tarifa != 0? `${props.item.Tarifa}%` : 'pleno' }}</td>
                                <td class="text-xs-center">{{ props.item.Ambito }}</td>
                                <td class="text-xs-right">
                                    <v-edit-dialog :return-value.sync="props.item.Valor" large lazyg
                                        cancel-text="Cancelar" save-text="Cambiar" @save="save(props.item)">
                                        <div>{{ props.item.Valor }}</div>
                                        <v-icon color="warning">edit</v-icon>
                                        <template v-slot:input>
                                            <div class="mt-3 title">Update Valor</div>
                                        </template>
                                        <template v-slot:input>
                                            <v-text-field v-model="props.item.Valor" label="Edit" single-line counter
                                                autofocus></v-text-field>
                                        </template>
                                    </v-edit-dialog>
                                </td>
                                <td>
                                    <v-btn color="error" icon @click="disableCupTarifa(props.item)">
                                        <v-icon>clear</v-icon>
                                    </v-btn>
                                </td>
                            </template>
                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </v-data-table>
                    </v-container>
                </v-card>
            </v-flex>
        </v-layout>
    </v-layout>
</template>

<script>
    import swal from 'sweetalert';

    export default {
        name: 'HomeTarifario',
        components: {},
        data() {
            return {
                loading: '',
                importFile: false,
                pagination: {
                    sortBy: 'name'
                },
                search: '',
                nameFile: '',
                selected: [],
                headersContrato: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Tarifa',
                        align: 'center',
                        value: 'Tarifa'
                    },
                    {
                        text: 'Manual',
                        value: 'Manual',
                        align: 'center',
                    },
                    {
                        text: 'Ambito',
                        value: 'Ambito',
                        align: 'center',
                    },
                    {
                        text: 'Entidad',
                        align: 'center',
                    },
                    {
                        text: '',
                        value: '',
                    },

                ],
                headers: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Entidad',
                        align: 'center',
                        value: 'nombre'
                    },
                    {
                        text: 'Código cup',
                        align: 'center',
                        value: 'Codigo'
                    },
                    {
                        text: 'Descripción cup',
                        value: 'Cup',
                        align: 'center',
                    },
                    {
                        text: 'Manual',
                        value: 'Manual',
                        align: 'center',
                    },
                    {
                        text: 'Tarifa',
                        value: 'Tarifa',
                        align: 'center',
                    },
                    {
                        text: 'Ambito',
                        value: 'Ambito',
                        align: 'center',
                    },
                    {
                        text: 'Valor',
                        value: 'Valor',
                        align: 'center',
                    },
                    {
                        text: '',
                        value: '',
                        align: 'center',
                    },
                ],
                desserts: [],
                data: {
                    Sedeproveedor_id: null,
                    Tarifa: '',
                    Manual: '',
                    Ambito: '',
                    Anio: '',
                    pleno: false,
                    entidad_id: null,
                    nombre: ''
                },
                contratosTable: false,
                dialog: false,
                contratoSave: true,
                prestadores: [],
                Prestador_id: '',
                sedesprestadores: [],
                Sedeproveedor_id: '',
                tipomanuales: [],
                anio: '',
                ambitos: [
                    'Hospitalario',
                    'Ambulatorio',
                    'Mixta'
                ],
                contrato: {
                    capitulo: [],
                    Contrato_id: '',
                    cup1: null,
                    cup2: null,
                    all: true,
                    opcUnidadFuncional: []
                },
                contratos: [],
                capitulos: [],
                cups: [],
                tarifas: [],
                entidades: [],
                importContrato: {},
                noexistecup: []
            }
        },
        computed: {
            fullnameprestador() {
                return this.prestadores.map((prestador) => {
                    return {
                        id: prestador.id,
                        fullname: `${prestador.Nit} - ${prestador.Nombre}`
                    }
                })
            },
            fullnamesede() {
                return this.sedesprestadores.map((sede) => {
                    return {
                        id: sede.id,
                        fullname: `${sede.Codigo_habilitacion} - ${sede.Municipio} - ${sede.Nombre}`
                    }
                })
            },
            fullcontrato() {
                return this.contratos.map((contrato) => {
                    let fullname = null;
                    if (contrato.Manual != 'Tarifa Propia') fullname =
                        `${contrato.Tarifa != 0? contrato.Tarifa : 'Pleno'} ${contrato.Manual} ${contrato.Ambito} - ${contrato.nombre}`;
                    else fullname = ` ${contrato.Manual} ${contrato.Ambito} - ${contrato.nombre}`;
                    return {
                        id: contrato.id,
                        fullname
                    }
                })
            },
            allCapitulo() {
                return this.contrato.capitulo.length === this.capitulos.length
            },
            someCapitulo() {
                return this.contrato.capitulo.length > 0 && !this.allCapitulo
            },
            icon() {
                if (this.allCapitulo) return 'mdi-close-box'
                if (this.someCapitulo) return 'mdi-minus-box'
                return 'mdi-checkbox-blank-outline'
            },
        },
        mounted() {
            this.fetchPrestaContratos();
            this.buscarTipomanuales();
            this.getUnidadFuncional();
        },
        methods: {
            toggle() {
                this.$nextTick(() => {
                    if (this.allCapitulo) {
                        this.contrato.capitulo = [];
                        this.contrato.opcUnidadFuncional = [];
                    } else {
                        this.contrato.capitulo = this.capitulos.map(capitulo => capitulo)
                        this.getCupsCapitulo();
                    }
                })
            },
            fetchPrestaContratos() {
                axios.get('/api/prestador/all')
                    .then(res => this.prestadores = res.data)
            },
            buscarSedesPrestador() {
                this.clearData();
                this.tarifas = [];
                this.contratos = [];
                axios.post('/api/sedeproveedore/all', {
                        Prestador_id: this.Prestador_id
                    })
                    .then((res) => {
                        this.sedesprestadores = res.data
                    })
                    .catch((err) => console.log(err));
            },
            buscarTipomanuales() {
                axios.get('/api/tipomanuales/all')
                    .then((res) => {
                        this.tipomanuales = res.data
                    })
                    .catch((err) => console.log(err));
            },
            getContratos() {
                this.tarifas = [];
                this.contrato = {
                    capitulo: '',
                    Contrato_id: '',
                    cup1: null,
                    cup2: null,
                    all: true,
                    opcUnidadFuncional: []
                }
                this.contratos = [];
                this.getTarifasPrestador();
                this.getentidades();
                axios.post('/api/contrato/all', {
                        Sedeproveedor_id: this.data.Sedeproveedor_id
                    })
                    .then((res) => {
                        this.contratos = res.data
                    })
                    .catch((err) => console.log(err));
            },
            getentidades() {
                axios.get('/api/entidades/getEntidades')
                    .then((res) => {
                        this.entidades = res.data
                    })
                    .catch((err) => console.log(err));
            },
            getUnidadFuncional() {
                axios.get('/api/familia/capitulos')
                    .then((res) => {
                        this.capitulos = res.data
                    })
                    .catch((err) => console.log(err));
            },
            createTarifa() {
                this.contratoSave = true;
                this.dialog = true;
                this.data.Tarifa = '';
                this.data.Manual = '';
                this.data.Ambito = '';
                this.data.Anio = '';
                this.data.entidad_id = '';
            },
            saveContrato() {
                axios.post('/api/contrato/create', this.data)
                    .then(res => {
                        this.getContratos();
                        this.dialog = false;
                        this.data.pleno = null
                        swal({
                            title: `${res.data.message}`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    }).catch(err => {
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
            importTarifa() {
                this.nameFile = ''
                this.$refs.file.value = ''
                this.noexistecup = []
                this.$refs.file.click()
            },
            onFilePicked(e) {
                const files = e.target.files
                this.nameFile = files[0].name
                this.data.file = files[0]
            },
            clearFile() {
                this.importFile = false
                this.nameFile = ''
                this.$refs.file.value = ''
                this.noexistecup = []
            },
            openFile(contrato) {
                this.importFile = true
                this.importContrato = {
                    contrato_id: contrato.id,
                    entidad: contrato.nombre,
                    manual: contrato.Manual,
                    tarifa: contrato.Tarifa
                }
            },
            saveFile() {
                if (!this.importContrato.familia) {
                    swal({
                        title: 'Error',
                        text: "Debe seleccionar la familia a la que pertenecen los Cups a importar.",
                        type: "error",
                        icon: "error",
                    });
                    return;
                }
                this.loading = true
                let formData = new FormData();
                formData.append('data', this.data.file);
                axios.post(
                    `/api/tarifario/importTarifa/${this.importContrato.contrato_id}/${this.importContrato.familia}`,
                    formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then((res) => {
                    this.loading = false
                    this.$alerSuccess('Tarifas masivas cargadas con exito!');
                    this.clearFile();
                    this.clearUpdateContrato();
                }).catch((error) => {
                    this.loading = false
                    this.noexistecup = error.response.data[0]
                    if (!error.response.data[0]) {
                        swal({
                            title: 'Error en el archivo',
                            text: "Revisa que los codigos y valores no tengan signos, espacios, etc.",
                            type: "error",
                            icon: "error",
                        });
                        this.noexistecup = []
                    }
                    if (error.response.data.message) {
                        swal({
                            title: 'Error en el archivo',
                            text: error.response.data.message,
                            type: "error",
                            icon: "error",
                        });
                        this.noexistecup = []
                    }
                });
            },
            clearData() {
                this.data = {
                    Sedeproveedor_id: null,
                    Tarifa: '',
                    Manual: '',
                    Ambito: '',
                    Anio: '',
                    entidad_id: ''
                }
            },
            async getCupsCapitulo() {
                this.contrato.opcUnidadFuncional = this.contrato.capitulo.map((capitulo) => {
                    return {
                        id: capitulo.id,
                        label: capitulo.Nombre,
                        cup1: null,
                        cup2: null,
                        all: true
                    }
                })
                axios.post('/api/cups/cupscapitulo', {
                        Familia_id: this.contrato.capitulo
                    })
                    .then((res) => {
                        this.cups = res.data
                    })
                    .catch((err) => console.log(err));
            },
            saveCupsContrato() {
                this.loading = true;

                axios.post(`/api/contrato/${this.contrato.Contrato_id}/tarifa/create`, this.contrato)
                    .then((res) => {
                        this.loading = false;

                        this.getTarifasPrestador();
                        swal({
                            title: `${res.data.message}`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    }).catch(err => {
                        this.loading = false;
                    })
            },
            getCapitulosPrestador() {
                if (this.contrato.Contrato_id) {
                    axios.post(`/api/contrato/${this.contrato.Contrato_id}/unidadfuncional`)
                        .then((res) => {
                            this.contrato.capitulo = res.data;
                            this.getCupsCapitulo();
                            this.getTarifasPrestador();
                        })
                        .catch((err) => console.log(err));
                }
            },
            getTarifasPrestador(opc) {
                let datos = {
                    contrato_id: this.contrato.Contrato_id,
                    familia: opc
                }
                this.loading = true;
                axios.post(`/api/contrato/${this.data.Sedeproveedor_id}/tarifa/all`, datos)
                    .then((res) => {
                        this.tarifas = res.data
                        this.loading = false;
                    }).catch(err => {
                        this.loading = false;
                    })
            },
            save(cup) {
                axios.post(`/api/contrato/tarifa/${cup.id}/edit`, cup)
                    .then((res) => {
                        this.getTarifasPrestador();
                        swal({
                            title: `${res.data.message}`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch((err) => console.log(err));
            },
            disableContrato(contrato) {
                swal({
                    title: 'Esta seguro de deshabilitar contrato?',
                    icon: 'warning',
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        this.loading = true;
                        axios.put(`/api/contrato/disable/${contrato.id}`)
                            .then((res) => {
                                this.loading = false;
                                this.getContratos();
                                swal({
                                    title: `${res.data.message}`,
                                    text: " ",
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            }).catch(err => {
                                this.loading = false;
                            })
                    }
                });
            },
            editContrato(contrato) {
                this.contrato.opcUnidadFuncional = []
                this.contrato.capitulo = ''
                this.contrato.Contrato_id = ''
                this.tarifas = []
                let pleno = null;
                this.contratoSave = false;
                if (contrato.Tarifa == 0 && contrato.Manual != 'Tarifa Propia') {
                    pleno = true
                } else {
                    pleno = false
                }
                this.data = {
                    id: contrato.id,
                    nombre: contrato.nombre,
                    Sedeproveedor_id: contrato.Sedeproveedor_id,
                    Tarifa: contrato.Tarifa,
                    Manual: contrato.Manual,
                    Ambito: contrato.Ambito,
                    Anio: contrato.Anio,
                    pleno
                }
                this.dialog = true;
            },
            updateContrato() {
                this.loading = true;
                axios.put(`/api/contrato/edit/${this.data.id}`, this.data)
                    .then((res) => {
                        this.loading = false;
                        this.clearUpdateContrato();
                        this.dialog = false;
                        swal({
                            title: `${res.data.message}`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    }).catch(err => {
                        this.loading = false;
                        this.$alerError(err.response.data.message);
                    })
            },
            disableCupTarifa(tarifa) {
                swal({
                    title: 'Deshabilitar cup para este prestador',
                    icon: 'warning',
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        axios.post(`/api/contrato/${tarifa.Contrato_id}/tarifa/${tarifa.id}/disable`, {
                                familia_id: tarifa.Familia_id
                            })
                            .then((res) => {
                                this.getTarifasPrestador();
                                this.getCapitulosPrestador();
                                swal({
                                    title: `¡${res.data.message}!`,
                                    text: " ",
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch((err) => console.log(err.response))
                    }
                });
            },
            filtercups(id) {
                return this.cups.filter((cup) => {
                    if (cup.Familia_id == id) return cup;
                });
            },
            addUnidadFuncional(opc) {
                swal({
                    title: 'Esta seguro de agregar unidad funcional?',
                    icon: 'warning',
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        this.loading = true;
                        axios.post(`/api/contrato/${this.contrato.Contrato_id}/tarifa/add`, opc)
                            .then((res) => {
                                this.loading = false;
                                this.getTarifasPrestador();
                                this.getCapitulosPrestador();
                                swal({
                                    title: `${res.data.message}`,
                                    text: " ",
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            }).catch(err => {
                                this.loading = false;
                                if(err.response.data.messageError){
                                    this.$alerError(err.response.data.messageError);
                                }else{
                                    this.$alerError('¡Debe ingresar rango1 y 2!');
                                }
                            })
                    }
                });
            },
            removeUnidadFuncional(opc) {
                swal({
                    title: 'Esta seguro de desabilitar unidad funcional?',
                    icon: 'warning',
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        this.loading = true;
                        axios.post(`/api/contrato/${this.contrato.Contrato_id}/unidadfuncional/remove`, opc)
                            .then((res) => {
                                this.loading = false;
                                this.getCapitulosPrestador();
                                this.getTarifasPrestador();
                                swal({
                                    title: `${res.data.message}`,
                                    text: " ",
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            }).catch(err => {
                                this.loading = false;
                            })
                    }
                });
            },
            removeRangoFuncional(opc) {
                swal({
                    title: 'Esta seguro de desabilitar unidad funcional por rangos?',
                    icon: 'warning',
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        this.loading = true;
                        axios.post(`/api/contrato/${this.contrato.Contrato_id}/rangounidadfuncional/remove`,
                                opc)
                            .then((res) => {
                                this.loading = false;
                                this.getCapitulosPrestador();
                                this.getTarifasPrestador();
                                swal({
                                    title: `${res.data.message}`,
                                    text: " ",
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            }).catch(err => {
                                this.loading = false;
                                this.$alerError('¡Debe ingresar rango1 y 2!');
                            })
                    }
                });
            },
            clearUpdateContrato() {
                this.dialog = false
                this.contratos = []
                this.contratosTable = false
                this.tarifas = []
                this.data.Sedeproveedor_id = ''
                this.data.pleno = null
            }
        }
    }

</script>|

<style lang="scss" scoped>

</style>
