<template>
    <v-layout>

        <v-flex xs12 md5 px-1>
            <v-card>
                <v-dialog v-model="dialog" persistent max-width="600px">
                    <v-card>
                        <v-toolbar dark color="primary">
                            <v-flex xs12 text-xs-center flat dark>
                                <v-toolbar-title v-if="save">Crear Salario Minimo</v-toolbar-title>
                                <v-toolbar-title v-else>Editar Salario Minimo</v-toolbar-title>
                            </v-flex>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12 sm6 md6 v-for="field in inputs" :key="field.model">
                                        <v-autocomplete v-if="field.items" :items="items[field.items]"
                                            item-text="Nombre" item-value="id" :label="field.label"
                                            v-model="data[field.model]" required></v-autocomplete>
                                        <v-text-field v-else :label="field.label" v-model="data[field.model]" required>
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>

                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" @click="dialog = false, clearFields()">Cancelar</v-btn>
                            <v-btn v-if="save" color="success" @click="saveSalarioMinimo()">Guardar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-card-title>
                    <v-layout row wrap justify-center>
                        <h4 class="headline">Salario Minimo</h4>
                    </v-layout>
                    <v-layout row wrap class="mt-3">
                        <v-spacer></v-spacer>
                        <v-btn round small @click="createSalarioMinimo()" color="primary" dark>
                            <v-icon>exposure_plus_1</v-icon>
                        </v-btn>
                        <v-flex sm12 xs12>
                            <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line
                                hide-details></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-data-table :headers="headers" :items="salariominimos" :search="search">
                    <template v-slot:items="props">
                        <td>{{ props.item.id }}</td>
                        <td>{{ props.item.anio }}</td>
                        <td class="text-xs-right">
                            <v-edit-dialog :return-value.sync="props.item.valor" large lazyg cancel-text="Cancelar"
                                save-text="Cambiar" @save="updateSalarioMinimo(props.item)">
                                <div>{{ props.item.valor }}%</div>
                                <v-icon color="warning">edit</v-icon>
                                <template v-slot:input>
                                    <v-text-field v-model="props.item.valor" label="Edit" single-line counter autofocus>
                                    </v-text-field>
                                </template>
                            </v-edit-dialog>
                        </td>
                    </template>
                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                </v-data-table>
            </v-card>
        </v-flex>

        <v-flex xs12 md7>
            <v-card>
                <v-dialog v-model="dialogSedeM" persistent max-width="600px">
                    <v-card>
                        <v-toolbar dark color="primary">
                            <v-flex xs12 text-xs-center flat dark>
                                <v-toolbar-title>Crear Sedeproveedor Minimo</v-toolbar-title>
                            </v-flex>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12 sm12 md12>
                                        <v-autocomplete :items="fullnameprestador" item-text="fullname" item-value="id"
                                            label="Proveedor" v-model="sedeminimo.proveedor_id"
                                            @input="fetchSedeproveedor()">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm12 md12>
                                        <v-switch v-model="sedeminimo.switch1" color="primary"
                                            label="Aplicar a todas las sedes"></v-switch>
                                    </v-flex>
                                    <v-flex xs12 sm12 md12 v-show="sedeminimo.switch1 == false">
                                        <v-combobox v-model="sedeminimo.sedeproveedor" :items="fullnamesede"
                                            label="Sedeproveedor" multiple item-text="fullname" item-value="id">
                                        </v-combobox>
                                    </v-flex>
                                    <v-flex xs6 sm6 md6>
                                        <v-autocomplete :items="salariominimos" item-text="anio" item-value="id"
                                            label="Año salario minimo" v-model="sedeminimo.salariominimo_id">
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-container>

                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" @click="dialogSedeM = false">Cancelar</v-btn>
                            <v-btn color="success" @click="saveSedeMinimo()">Guardar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogProveedorM" persistent max-width="900px">
                    <v-card>
                        <v-toolbar dark color="primary">
                            <v-flex xs12 text-xs-center flat dark>
                                <v-toolbar-title>Actualizar Proveedor Minimo</v-toolbar-title>
                            </v-flex>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs11 sm11 md11>
                                        <v-autocomplete v-model="proveedorminimo.municipio" :items="municipios"
                                            item-text="Nombre" item-value="id" chips label="Municipio" multiple>
                                            <template v-slot:selection="data">
                                                <v-chip :selected="data.selected" close class="chip--select-multi"
                                                    @input="remove_municipio(data.item.id)">
                                                    {{ data.item.Nombre }}
                                                </v-chip>
                                            </template>
                                            <template v-slot:item="data">
                                                <template v-if="typeof data.item.Nombre !== 'object'">
                                                    <v-list-tile-content v-text="data.item.Nombre">
                                                    </v-list-tile-content>
                                                </template>
                                                <template v-else>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                        <v-list-tile-sub-title v-html="data.item">
                                                        </v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </template>
                                            </template>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs1 md1 sm1>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab color="info" dark small v-on="on" @click="fetchProveedor()">
                                                    <v-icon>search</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Filtrar por municipio</span>
                                        </v-tooltip>
                                    </v-flex>
                                    <v-flex xs12 sm12 md12>
                                        <v-switch v-model="proveedorminimo.switch2" color="primary"
                                            label="Aplicar a todos los proveedores"></v-switch>
                                    </v-flex>
                                    <v-flex xs12 sm12 md12 v-show="proveedorminimo.switch2 == false">
                                        <v-autocomplete v-model="proveedorminimo.proveedor" :items="fullnameproveedor"
                                            item-text="fullname" item-value="id" chips label="Proveedor" multiple>
                                            <template v-slot:selection="data">
                                                <v-chip :selected="data.selected" close class="chip--select-multi"
                                                    @input="remove_proveedor(data.item.id)">
                                                    {{ data.item.fullname.substring(0,28) }}
                                                </v-chip>
                                            </template>
                                            <template v-slot:item="data">
                                                <template v-if="typeof data.item.fullname !== 'object'">
                                                    <v-list-tile-content v-text="data.item.fullname">
                                                    </v-list-tile-content>
                                                </template>
                                                <template v-else>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                        <v-list-tile-sub-title v-html="data.item">
                                                        </v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </template>
                                            </template>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs6 sm6 md6>
                                        <v-autocomplete :items="salariominimos" item-text="anio" item-value="id"
                                            label="Año salario minimo" v-model="proveedorminimo.salariominimo_id">
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-container>

                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" @click="dialogProveedorM = false, clearFields()">Cancelar</v-btn>
                            <v-btn color="success" @click="saveUpdateProveedorMinimo()">Guardar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-card-title>
                    <v-layout row wrap justify-center>
                        <h4 class="headline mb-3">Sedeproveedor Minimo</h4>
                    </v-layout>
                    <v-layout row wrap>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn fab color="info" dark small v-on="on" @click="OpenUpdateSedeMinimo()">
                                    <v-icon>update</v-icon>
                                </v-btn>
                            </template>
                            <span>Actualizar sede minimo</span>
                        </v-tooltip>
                        <v-spacer></v-spacer>
                        <v-btn round small @click="createSedeMinimo()" color="primary" dark>
                            <v-icon>exposure_plus_1</v-icon>
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn fab color="warning" dark small v-on="on" @click="openUpdateProveedorMinimo()">
                                    <v-icon>save_alt</v-icon>
                                </v-btn>
                            </template>
                            <span>Actualizar por proveedor</span>
                        </v-tooltip>
                        <v-spacer></v-spacer>
                        <v-flex sm12 xs12>
                            <v-text-field v-model="searchSede" append-icon="search" label="Buscar..." single-line
                                hide-details></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-data-table class="elevation-1" v-model="selected" select-all :headers="headersSede"
                    :items="sedeminimos" item-key="id" :search="searchSede">
                    <template v-slot:items="props">
                        <td>
                            <v-checkbox color="primary" :input-value="props.selected" v-model="selected"
                                :value="props.item" multiple hide-details>
                            </v-checkbox>
                        </td>
                        <td class="text-xs-right">{{ props.item.id }}</td>
                        <td class="text-xs-right">{{ props.item.Codigo_habilitacion }} - {{ props.item.Nombre }}
                        </td>
                        <td class="text-xs-right">{{ props.item.anio }}</td>
                    </template>
                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                        Your search for "{{ searchSede }}" found no results.
                    </v-alert>
                </v-data-table>

                <v-dialog v-model="dialogSedeUp" persistent max-width="600px">
                    <v-card>
                        <v-toolbar dark color="primary">
                            <v-flex xs12 text-xs-center flat dark>
                                <v-toolbar-title>Actualizar Sedeproveedor Minimo</v-toolbar-title>
                            </v-flex>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12 sm12 md12>
                                        <v-subheader>Sede proveedor</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in selected" :key="index">
                                                <v-chip label :selected="data.selected">
                                                    {{data.Codigo_habilitacion}} - {{data.Nombre}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                    </v-flex>
                                    <v-flex xs6 sm6 md6>
                                        <v-autocomplete :items="salariominimos" item-text="anio" item-value="id"
                                            label="Año salario minimo" v-model="sedeminimo.salariominimo_id">
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-container>

                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" @click="dialogSedeUp = false, clearFields()">Cancelar</v-btn>
                            <v-btn color="success" @click="saveUpdateSedeMinimo()">Guardar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-card>
        </v-flex>

    </v-layout>
</template>

<script>
    export default {
        name: 'SalarioMinimo',
        components: {},
        data() {
            return {
                search: '',
                searchSede: '',
                headers: [{
                        text: 'id',
                        align: 'left',
                        sortable: false,
                        value: 'id'
                    },
                    {
                        text: 'Año',
                        align: 'left',
                        sortable: false,
                        value: 'anio'
                    },
                    {
                        text: 'Valor',
                        align: 'left',
                        sortable: false,
                        value: 'valor'
                    }
                ],
                inputs: [{
                        label: 'Año',
                        model: 'anio',
                        items: 'listanio'
                    },
                    {
                        label: 'Valor',
                        model: 'valor'
                    },
                ],
                headersSede: [{
                        text: 'id',
                        align: 'right',
                        sortable: false,
                        value: 'id'
                    },
                    {
                        text: 'Nombre sede',
                        align: 'right',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Año minimo',
                        align: 'right',
                        sortable: false,
                        value: 'anio'
                    }
                ],
                save: true,
                dialog: false,
                data: {
                    anio: '',
                    valor: '',
                },
                salariominimos: [],
                items: {
                    listanio: []
                },
                dialogSedeM: false,
                sedeminimo: {
                    switch1: false
                },
                sedesprestadores: [],
                prestadores: [],
                sedeminimos: [],
                selected: [],
                dialogSedeUp: false,
                municipios: [],
                dialogProveedorM: false,
                proveedorminimo: {
                    switch2: false
                },
                proveedores: []

            }
        },
        mounted() {
            this.fetchSalarioMinimos();
            this.fetchSedeMinimo();
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
            fullnameproveedor() {
                return this.proveedores.map((prov) => {
                    return {
                        id: prov.Prestador_id,
                        fullname: `${prov.Nit} - ${prov.Nombreprestador}`
                    }
                })
            }
        },
        methods: {
            fetchSalarioMinimos() {
                axios.get('/api/salariominimo/all')
                    .then((res) => this.salariominimos = res.data)
                    .catch((err) => console.log(err));
            },
            fetchMunicipios() {
                axios.get('/api/municipio/all')
                    .then(res => this.municipios = res.data);
            },
            createSalarioMinimo() {
                this.arrayAnios();
                this.save = true;
                this.dialog = true;
                this.clearFields();
            },
            saveSalarioMinimo() {
                axios.post('/api/salariominimo/create', this.data)
                    .then((res) => {
                        this.dialog = false;
                        this.fetchSalarioMinimos();
                        this.clearFields();
                        swal({
                            title: `${res.data.message}`,
                            text: ' ',
                            type: "success",
                            timer: 3000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch((err) => {
                        this.$alerError('Todos los campos son obligatorios');
                    })
            },
            clearFields() {
                this.data = {
                    anio: '',
                    valor: '',
                }
                this.sedeminimo = {
                    proveedor_id: '',
                    switch1: false,
                    sedeproveedor: [],
                    salariominimo_id: ''
                }
                this.proveedorminimo = {
                    municipio: [],
                    proveedor: [],
                    switch2: false,
                    sedeproveedor: [],
                    salariominimo_id: ''
                }
            },
            arrayAnios() {
                let n = (new Date()).getFullYear()
                for (let i = n; i >= 2018; i--) {
                    this.items.listanio.push(i)
                }
            },
            createSedeMinimo() {
                this.clearFields();
                this.dialogSedeM = true
                this.fetchPrestadores();
            },
            fetchPrestadores() {
                axios.get('/api/prestador/all')
                    .then(res => this.prestadores = res.data)
            },
            fetchSedeproveedor() {
                axios.post('/api/sedeproveedore/all', {
                        Prestador_id: this.sedeminimo.proveedor_id
                    })
                    .then((res) => {
                        this.sedesprestadores = res.data
                    })
                    .catch((err) => console.log(err));
            },
            fetchSedeMinimo() {
                axios.get('/api/salariominimo/allsedeminimo')
                    .then((res) => this.sedeminimos = res.data)
                    .catch((err) => console.log(err));
            },
            saveSedeMinimo() {
                axios.post('/api/salariominimo/sede', this.sedeminimo)
                    .then((res) => {
                        this.dialogSedeM = false;
                        this.clearFields();
                        this.fetchSedeMinimo();
                        swal({
                            title: `${res.data.message}`,
                            text: ' ',
                            type: "success",
                            timer: 3000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch((err) => {
                        this.$alerError('Debe llenar los campos obligatorios');
                    })
            },
            OpenUpdateSedeMinimo() {
                if (this.selected.length > 0) {
                    this.dialogSedeUp = true
                }
            },
            saveUpdateSedeMinimo() {
                if (this.sedeminimo.salariominimo_id == undefined) {
                    return;
                }
                let data = {
                    salariominimo_id: this.sedeminimo.salariominimo_id,
                    sedeproveedor: this.selected
                }
                axios.post('/api/salariominimo/updatesedeminimo', data)
                    .then((res) => {
                        this.dialogSedeUp = false;
                        this.fetchSedeMinimo();
                        this.clearFields();
                        swal({
                            title: `${res.data.message}`,
                            text: ' ',
                            type: "success",
                            timer: 3000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch((err) => {
                        this.$alerError('Debe seleccionar año salario minimo');
                    })
            },
            openUpdateProveedorMinimo() {
                this.dialogProveedorM = true
                this.fetchMunicipios();
                this.fetchProveedor();
            },
            remove_municipio(item) {
                this.proveedorminimo.municipio.splice(this.proveedorminimo.municipio.indexOf(item), 1)
                this.proveedorminimo.municipio = [...this.proveedorminimo.municipio]
            },
            remove_proveedor(item) {
                this.proveedorminimo.proveedor.splice(this.proveedorminimo.proveedor.indexOf(item), 1)
                this.proveedorminimo.proveedor = [...this.proveedorminimo.proveedor]
            },
            fetchProveedor() {
                axios.post('/api/salariominimo/municipioproveedor', {
                        municipio: this.proveedorminimo.municipio
                    })
                    .then((res) => {
                        this.proveedores = res.data
                    })
                    .catch((err) => console.log(err));
            },
            saveUpdateProveedorMinimo() {
                if (this.proveedorminimo.salariominimo_id == undefined) {
                    return;
                }
                let data = {
                    municipio: this.proveedorminimo.municipio,
                    salariominimo_id: this.proveedorminimo.salariominimo_id,
                    proveedor: this.proveedorminimo.proveedor,
                    switch2: this.proveedorminimo.switch2
                }
                axios.post('/api/salariominimo/updateproveedorminimo', data)
                    .then((res) => {
                        this.dialogProveedorM = false;
                        this.fetchSedeMinimo();
                        this.clearFields();
                        swal({
                            title: `${res.data.message}`,
                            text: ' ',
                            type: "success",
                            timer: 3000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch((err) => {
                        this.$alerError('Debe llenar los campos obligatorios');
                    })
            },
            updateSalarioMinimo(minimo) {
                axios.put(`/api/salariominimo/edit/${minimo.id}`, minimo)
                    .then((res) => {
                        this.fetchSalarioMinimos();
                        swal({
                            title: `${res.data.message}`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch((err) => console.log(err));
            }

        }
    }

</script>
