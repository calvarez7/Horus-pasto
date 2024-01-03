<template>
    <div>

        <v-dialog v-model="importFile" max-width="800px">
            <v-card>
                <v-card-title>
                    <span class="headline">Importar archivo</span>
                </v-card-title>

                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap align-center>
                            <v-flex xs12 sm6 md12>
                                <v-flex xs12>
                                    <v-autocomplete v-model="cupfamilia" :items="familias" item-text="Nombre"
                                        item-value="id" chips label="Familia" multiple>
                                        <template v-slot:selection="data">
                                            <v-chip :selected="data.selected" close class="chip--select-multi"
                                                @input="remove_famila(data.item.id)">
                                                {{ data.item.Nombre }}
                                            </v-chip>
                                        </template>
                                        <template v-slot:item="data">
                                            <template v-if="typeof data.item.Nombre !== 'object'">
                                                <v-list-tile-content v-text="data.item.Nombre"></v-list-tile-content>
                                            </template>
                                            <template v-else>
                                                <v-list-tile-content>
                                                    <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                    <v-list-tile-sub-title v-html="data.item"></v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </template>
                                        </template>
                                    </v-autocomplete>
                                </v-flex>
                            </v-flex>
                            <v-flex xs3 sm3 md3>
                                <v-btn color="success" @click="importCup()">
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

                <template v-for="error in existecup">
                    <v-flex xs12 px-12>
                        <v-alert :value="true" type="error">
                            El codigo "{{error.Codigo}}" ya registra en cups.
                        </v-alert>
                    </v-flex>
                </template>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" flat @click="clearImport()">Cancelar</v-btn>
                    <v-btn color="success darken-1" flat @click="saveFile()">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-card min-height="100%">
            <v-container pa-1>
                <v-card-title>
                    <v-flex xs12>
                        <span class="headline layout justify-center">Cups</span>
                    </v-flex>
                    <v-spacer></v-spacer>
                    <v-flex sm5 xs5>
                        <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line hide-details>
                        </v-text-field>
                    </v-flex>
                    <v-tooltip top v-if="can('cup.create')">
                        <template v-slot:activator="{ on }">
                            <v-btn fab outline color="success" small v-on="on" @click="openCreateCup()">
                                <v-icon>add</v-icon>
                            </v-btn>
                        </template>
                        <span>Crear cup</span>
                    </v-tooltip>
                </v-card-title>
                <v-data-table :headers="headers" :items="cups" :search="search">
                    <template v-slot:items="props">
                        <td>{{ props.item.id }}</td>
                        <td class="text-xs-right">{{ props.item.Codigo }}</td>
                        <td class="text-xs-right">
                            <v-edit-dialog :return-value.sync="props.item.Nombre" large lazy persistent
                                @save="save(props.item)" cancel-text="Cancelar" save-text="Cambiar">
                                <div>{{ props.item.Nombre }}</div>
                                <template v-slot:input>
                                    <v-text-field v-model="props.item.Nombre" :disabled="!can('cup.edit')"
                                        label="Editar Nombre" single-line counter autofocus></v-text-field>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-right">
                            <v-edit-dialog :return-value.sync="props.item.Nivelordenamiento" large lazy persistent
                                @save="save(props.item)" cancel-text="Cancelar" save-text="Cambiar">
                                <div>{{ props.item.Nivelordenamiento }}</div>
                                <template v-slot:input>
                                    <div class="mt-3 title">Update cup</div>
                                </template>
                                <template v-slot:input>
                                    <v-select :disabled="!can('cup.edit')" :items="['0','1','2','3','4']"
                                        v-model="props.item.Nivelordenamiento" label="Editar Nivel" autofocus>
                                    </v-select>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-right">
                            <v-edit-dialog :return-value.sync="props.item.nivelportabilidad" large lazy persistent
                                @save="save(props.item)" cancel-text="Cancelar" save-text="Cambiar">
                                <div>{{ props.item.nivelportabilidad }}</div>
                                <template v-slot:input>
                                    <div class="mt-3 title">Update cup</div>
                                </template>
                                <template v-slot:input>
                                    <v-select :disabled="!can('cup.edit')" :items="['0','1','2','3','4']"
                                        v-model="props.item.nivelportabilidad" label="Editar Nivel" autofocus>
                                    </v-select>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-right">
                            <v-edit-dialog :return-value.sync="props.item.Requiere_auditoria" large lazy persistent
                                @save="save(props.item)" cancel-text="Cancelar" save-text="Cambiar">
                                <div>{{ props.item.Requiere_auditoria }}</div>
                                <template v-slot:input>
                                    <div class="mt-3 title">Update cup</div>
                                </template>
                                <template v-slot:input>
                                    <v-select :disabled="!can('cup.edit')" :items="['SI','NO']"
                                        v-model="props.item.Requiere_auditoria" label="Editar autorización" autofocus>
                                    </v-select>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-right">
                            <v-edit-dialog :return-value.sync="props.item.Peridiocidad" large lazy persistent
                                @save="save(props.item)" cancel-text="Cancelar" save-text="Cambiar">
                                <div>{{ props.item.Peridiocidad }}</div>
                                <template v-slot:input>
                                    <div class="mt-3 title">Update cup</div>
                                </template>
                                <template v-slot:input>
                                    <v-text-field v-model="props.item.Peridiocidad" :disabled="!can('cup.edit')"
                                        label="Editar Periodicidad" single-line counter autofocus></v-text-field>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-right">
                            <v-edit-dialog :return-value.sync="props.item.Cantidadmaxordenar" large lazy persistent
                                @save="save(props.item)" cancel-text="Cancelar" save-text="Cambiar">
                                <div>{{ props.item.Cantidadmaxordenar }}</div>
                                <template v-slot:input>
                                    <div class="mt-3 title">Update cup</div>
                                </template>
                                <template v-slot:input>
                                    <v-text-field v-model="props.item.Cantidadmaxordenar" :disabled="!can('cup.edit')"
                                        label="Editar Cantidad Máxima" single-line counter autofocus></v-text-field>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-right">
                            <v-tooltip top v-if="can('cup.edit')">
                                <template v-slot:activator="{ on }">
                                    <v-btn text icon color="success" dark v-on="on" @click="openAddFamilia(props.item)">
                                        <v-icon>add</v-icon>
                                    </v-btn>
                                </template>
                                <span>Agregar familia</span>
                            </v-tooltip>
                        </td>
                    </template>
                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                </v-data-table>
            </v-container>
        </v-card>

        <v-dialog v-model="dialogCreate" max-width="800px" persistent>
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-flex xs12 text-xs-center flat dark>
                        <v-toolbar-title>Crear Cup</v-toolbar-title>
                    </v-flex>
                </v-toolbar>

                <v-tooltip top>
                    <template v-slot:activator="{ on }">
                        <v-btn color="success" v-on="on" @click="importFile = true">
                            <v-icon>cloud_upload</v-icon>
                        </v-btn>
                    </template>
                    <span>Importar</span>
                </v-tooltip>

                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm6 md12>
                                <v-flex xs12>
                                    <v-autocomplete v-model="cupfamilia" :items="familias" item-text="Nombre"
                                        item-value="id" chips label="Familia" multiple>
                                        <template v-slot:selection="data">
                                            <v-chip :selected="data.selected" close class="chip--select-multi"
                                                @input="remove_famila(data.item.id)">
                                                {{ data.item.Nombre }}
                                            </v-chip>
                                        </template>
                                        <template v-slot:item="data">
                                            <template v-if="typeof data.item.Nombre !== 'object'">
                                                <v-list-tile-content v-text="data.item.Nombre"></v-list-tile-content>
                                            </template>
                                            <template v-else>
                                                <v-list-tile-content>
                                                    <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                    <v-list-tile-sub-title v-html="data.item"></v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </template>
                                        </template>
                                    </v-autocomplete>
                                </v-flex>
                            </v-flex>
                            <v-flex xs12 sm6 md12>
                                <v-text-field label="Nombre" v-model="cup.Nombre"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="cup.Codigo" label="Codigo"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-select v-model="cup.Requiere_auditoria" :items="['SI','NO']"
                                    label="Requiere autorización">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md3>
                                <v-text-field v-model="cup.Peridiocidad" label="Periocidad" type="number">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-select v-model="cup.Nivelordenamiento" :items="['1','2','3','4']"
                                    label="Nivel de ordenamiento">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-select v-model="cup.nivelportabilidad" :items="['1','2','3','4']"
                                    label="Nivel de protabilidad">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md5>
                                <v-text-field v-model="cup.Cantidadmaxordenar" label="Cantidad maxima ordenar"
                                    type="number"></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red" dark @click="clear()">Salir</v-btn>
                    <v-btn color="success" @click="createCup()">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogAddfamilia" persistent max-width="800px">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-flex xs12 text-xs-center flat dark>
                        <v-toolbar-title>Agregar familia a cup</v-toolbar-title>
                    </v-flex>
                </v-toolbar>

                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm6 md12>
                                <v-flex xs12 sm6 md5>
                                    <v-text-field label="Codigo" v-model="cupaddfamilia.codigo" readonly></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md12>
                                    <v-text-field label="Nombre" v-model="cupaddfamilia.nombre" readonly></v-text-field>
                                </v-flex>
                                <v-flex xs12 v-if="cuphaveFamilias != ''">
                                    <v-subheader>Familias agregadas</v-subheader>
                                    <v-item-group multiple>
                                        <v-item v-for="(cupf, index) in cuphaveFamilias" :key="index">
                                            <v-chip label>
                                                {{cupf.Nombre}}
                                            </v-chip>
                                        </v-item>
                                    </v-item-group>
                                </v-flex>
                                <v-flex xs12>
                                    <v-autocomplete v-model="cupfamilia" :items="familias" item-text="Nombre"
                                        item-value="id" chips label="Agregar familia" multiple>
                                        <template v-slot:selection="data">
                                            <v-chip :selected="data.selected" close class="chip--select-multi"
                                                @input="remove_famila(data.item.id)">
                                                {{ data.item.Nombre }}
                                            </v-chip>
                                        </template>
                                        <template v-slot:item="data">
                                            <template v-if="typeof data.item.Nombre !== 'object'">
                                                <v-list-tile-content v-text="data.item.Nombre"></v-list-tile-content>
                                            </template>
                                            <template v-else>
                                                <v-list-tile-content>
                                                    <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                    <v-list-tile-sub-title v-html="data.item"></v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </template>
                                        </template>
                                    </v-autocomplete>
                                </v-flex>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red" dark @click="clearFamilia()">Salir</v-btn>
                    <v-btn color="success" @click="saveCupFamilia()">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

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
    import {
        mapGetters
    } from 'vuex';
    import Swal from 'sweetalert2';
    export default {
        name: 'TableCup',
        data() {
            return {
                cups: [],
                search: '',
                headers: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Código',
                        align: 'right',
                        sortable: false,
                        value: 'Codigo'
                    },
                    {
                        text: 'Nombre',
                        align: 'right',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Nivel ordenamiento',
                        align: 'center',
                        sortable: false,
                        value: 'Codigo'
                    },
                    {
                        text: 'Nivel Portabilidad',
                        align: 'center',
                        sortable: false,
                        value: 'nivelportabilidad'
                    },
                    {
                        text: 'Requiere autorización',
                        align: 'center',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Periodicidad',
                        align: 'center',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Cantidad Máxima',
                        align: 'center',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Acción',
                        align: 'center',
                        sortable: false
                    },
                ],
                dialogCreate: false,
                cup: {
                    Nombre: '',
                    Codigo: '',
                    Requiere_auditoria: '',
                    Peridiocidad: '',
                    Nivelordenamiento: '',
                    Cantidadmaxordenar: ''
                },
                importFile: false,
                nameFile: '',
                data: {},
                existecup: [],
                files: [],
                preload: false,
                familias: [],
                cupfamilia: [],
                dialogAddfamilia: false,
                cupaddfamilia: {},
                cuphaveFamilias: []
            }
        },
        computed: {
            ...mapGetters(['can'])
        },
        mounted() {
            this.fetchCups();
        },
        methods: {
            fetchCups() {
                axios.get('/api/cups/all')
                    .then(res => this.cups = res.data);
            },
            fetchFamilia() {
                axios.get('/api/familia/all')
                    .then(res => this.familias = res.data);
            },
            save(cup) {
                axios.put(`/api/cups/edit/${cup.id}`, cup).then(res => {
                    this.$alerSuccess('Cup actualizado con exito!');
                    this.fetchCups();
                })
            },
            openCreateCup() {
                this.dialogCreate = true
                this.fetchFamilia();
            },
            createCup() {
                if (this.cupfamilia.length == 0) {
                    this.$alerError('Seleccione la familia a la que pertenece el cup.')
                    return;
                }
                let formData = new FormData();
                formData.append('Nombre', this.cup.Nombre);
                formData.append('Codigo', this.cup.Codigo);
                formData.append('Requiere_auditoria', this.cup.Requiere_auditoria);
                formData.append('Peridiocidad', this.cup.Peridiocidad);
                formData.append('Nivelordenamiento', this.cup.Nivelordenamiento);
                formData.append('Cantidadmaxordenar', this.cup.Cantidadmaxordenar);
                for (let i = 0; i < this.cupfamilia.length; i++) {
                    formData.append('familia[]', this.cupfamilia[i]);
                }
                axios.post(`/api/cups/create`, formData).then(res => {
                    this.fetchCups();
                    this.$alerSuccess('Cup creado con exito!');
                    this.clear();
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
            clear() {
                this.dialogCreate = false
                this.cupfamilia = []
                for (const prop of Object.getOwnPropertyNames(this.cup)) {
                    this.cup[prop] = null;
                }
            },
            remove_famila(item) {
                const index = this.cupfamilia.indexOf(item)
                if (index >= 0) this.cupfamilia.splice(index, 1)
            },
            importCup() {
                this.nameFile = ''
                this.$refs.file.value = ''
                this.existecup = []
                this.$refs.file.click()
            },
            onFilePicked(e) {
                let files = e.target.files
                this.nameFile = files[0].name
                this.data.file = files[0]
            },
            clearImport() {
                this.importFile = false
                this.nameFile = ''
                this.$refs.file.value = ''
                this.existecup = []
                for (const prop of Object.getOwnPropertyNames(this.cup)) {
                    this.cup[prop] = null;
                }
                this.cupfamilia = []
            },
            saveFile() {
                if (!this.$refs.file.value) {
                    swal({
                        title: 'Error en el archivo',
                        text: "No ha seleccionado ningun archivo.",
                        type: "error",
                        icon: "error",
                    });
                    return
                }
                if (this.cupfamilia.length == 0) {
                    this.$alerError('Seleccione la familia a la que pertenecen los cups.')
                    return;
                }
                this.preload = true
                let formData = new FormData();
                formData.append('data', this.data.file);
                for (let i = 0; i < this.cupfamilia.length; i++) {
                    formData.append('familia[]', this.cupfamilia[i]);
                }
                axios.post('/api/cups/import', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((res) => {
                    swal({
                        title: 'Carga con exito',
                        text: 'Todos los cups fueron cargados con exito.',
                        type: "success",
                        icon: "success",
                    });
                    this.clearImport();
                    this.preload = false
                    this.dialogCreate = false
                    this.fetchCups();
                }).catch(err => {
                    this.preload = false
                    this.existecup = err.response.data[0]
                    if (!err.response.data[0]) {
                        swal({
                            title: 'Error en el archivo',
                            text: "Revisa que los codigos no tengan signos, espacios, etc.",
                            type: "error",
                            icon: "error",
                        });
                        this.existecup = []
                    }
                    if (err.response.data.message) {
                        swal({
                            title: 'Error en el archivo',
                            text: err.response.data.message,
                            type: "error",
                            icon: "error",
                        });
                        this.existecup = []
                    }
                })
            },
            openAddFamilia(item) {
                this.cupaddfamilia = {
                    cup_id: item.id,
                    codigo: item.Codigo,
                    nombre: item.Nombre
                }
                this.dialogAddfamilia = true
                this.fetchFamilia();
                this.fetchCupsFamilia()
            },
            fetchCupsFamilia() {
                axios.post('/api/cups/cupfamilias', {
                        cup_id: this.cupaddfamilia.cup_id
                    })
                    .then(res => this.cuphaveFamilias = res.data);
            },
            clearFamilia(){
                this.dialogAddfamilia = false
                this.cupfamilia = []
            },
            saveCupFamilia() {
                let encuentra = false;
                for (let i = 0; i < this.cupfamilia.length; i++) {
                    let addfamilia = this.cupfamilia[i]
                    for (let j = 0; j < this.cuphaveFamilias.length; j++) {
                        let familia = this.cuphaveFamilias[j].id
                        if (addfamilia == familia) {
                            encuentra = true;
                            break;
                        }
                    }
                    if (encuentra == true) {
                        swal({
                            title: "¡El cup ya tiene agregada esa familia!",
                            text: ` `,
                            timer: 2000,
                            icon: "error",
                            buttons: false
                        });
                        break;
                    }
                }
                if (encuentra == false) {
                    let formData = new FormData();
                    formData.append('cup_id', this.cupaddfamilia.cup_id);
                    for (let i = 0; i < this.cupfamilia.length; i++) {
                        formData.append('familia[]', this.cupfamilia[i]);
                    }
                    axios.post(`/api/cups/addcupfamilias`, formData).then(res => {
                        this.clearFamilia();
                        this.$alerSuccess('Familia agreada al cup con exito!');
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
                }
            }

        },
    }

</script>
<style lang="scss" scoped>

</style>
