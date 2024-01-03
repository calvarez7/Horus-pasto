<template>
    <v-card>
        <v-container pa-1>
                <v-container pt-3 pb-1>
                    <v-layout row>
                        <v-dialog v-model="dialog" max-width="600px">
                        <v-card>
                            <v-card-title class="headline success" style="color:white">
                            <span v-if="save" class="headline">Crear Código</span>
                            <span v-else class="headline">Editar Código</span>
                            </v-card-title>
                            <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12 sm12 md12>
                                        <v-text-field label="Nombre*" v-model="data.Nombre" required :readonly="!save"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field label="Código*" v-model="data.Codigo" required :readonly="!save"></v-text-field>
                                    </v-flex>
                                    <!-- <v-flex xs12 sm6 md6>
                                        <v-text-field label="Concentracion*"  v-model="data.concentracion" required></v-text-field>
                                    </v-flex> -->
                                    <!-- <v-flex xs12 sm6 md6>
                                        <v-text-field label="unidadMedida*" v-model="data.unidadMedida" required></v-text-field>
                                    </v-flex> -->
                                    <!-- <v-flex xs12 sm6 md6>
                                        <v-select label="Tipo Codigo*" v-model="data.Tipocodesumi_id" filled required v-if="save"
                                        :items="listTipoCodigo" item-text="Nombre" item-value="id"></v-select>
                                        <v-text-field v-else label="Tipo Codigo*" v-model="codetype.Nombre" required readonly></v-text-field>
                                    </v-flex> -->
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field label="Frecuencia*" type="number" v-model="data.Frecuencia" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field label="Cantidad max ordenar*" type="number"
                                        min="1"
                                        onkeypress="return event.charCode >= 48"
                                        oncopy="return false" onpaste="return false" v-model="data.Cantidadmaxordenar" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-select label="Nivel de ordenamiento*" :items="['0','1','2','3','4']" v-model="data.Nivel_Ordenamiento" required></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-select label="Requiere autorizacion*" :items="['SI','NO']" v-model="data.Requiere_autorizacion" required></v-select>
                                    </v-flex>

                                    <v-flex xs12 sm6 md6>
                                        <v-text-field label="Cantidad max ordenar dia (insulinas)*" type="number"
                                                      min="1"
                                                      onkeypress="return event.charCode >= 48"
                                                      oncopy="return false" onpaste="return false" v-model="data.cantidad_maxima_ordenar_dia" required></v-text-field>
                                    </v-flex>

                                    <!-- <v-flex xs12 sm6 md6 v-if="data.Tipocodesumi_id == 3">
                                        <v-select label="Genero*" :items="['A','M', 'F']" v-model="data.Genero" required></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6 v-if="data.Tipocodesumi_id == 3">
                                        <v-text-field label="Edad Inicial*" type="number" v-model="data.EdadInicial" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6 v-if="data.Tipocodesumi_id == 3">
                                        <v-text-field label="Edad Final*" type="number" v-model="data.EdadFinal" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6 v-if="data.Tipocodesumi_id == 3">
                                        <v-select label="Ambito*" :items="['AMBOS', 'HOSPITALARIO','AMBULATORIO']" v-model="data.Ambito" required></v-select>
                                    </v-flex> -->
                                </v-layout>
                            </v-container>
                            </v-card-text>
                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                            <v-btn v-if="save" color="blue darken-1" flat @click="saveCodigo()">Guardar</v-btn>
                            <v-btn v-else color="blue darken-1" flat @click="updateCodigo()">Actualizar</v-btn>
                            </v-card-actions>
                        </v-card>
                        </v-dialog>
                    </v-layout>
                </v-container>
            <v-card-title>
            <!-- <v-btn round v-if="can('codesumi.create')" @click="createCodigo()" color="primary" dark ><v-icon left>person_add</v-icon>Crear Código</v-btn> -->
            <v-spacer></v-spacer>
            <v-flex
            sm5
            xs12
            >
                <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Buscar..."
                    single-line
                    hide-details
                ></v-text-field>
            </v-flex>
            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="codigos"
                :search="search"
                >
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td class="text-xs-right">{{ props.item.Nombre }}</td>
                    <td class="text-xs-right">{{ props.item.Codigo }}</td>
                    <td class="text-xs-right">{{ props.item.concentracion }}</td>
                    <td class="text-xs-right">{{ props.item.unidadMedida }}</td>
                    <td class="text-xs-right">{{ props.item.Frecuencia }}</td>
                    <td class="text-xs-right">{{ props.item.Cantidadmaxordenar }}</td>
                    <td class="text-xs-right">{{ props.item.Nivel_Ordenamiento }}</td>
                    <td class="text-xs-right">{{ props.item.Requiere_autorizacion }}</td>
                    <td class="text-xs-right">{{ props.item.Estado_id | textEstado }}</td>
                    <td class="text-xs-right">
                        <v-btn  fab outline color="warning" small @click="editCodigo(props.item)"><v-icon>edit</v-icon></v-btn>
                    </td>
                </template>
                <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </v-data-table>
        </v-container>
    </v-card>
</template>

<script>
    import { mapGetters } from 'vuex';
    export default {
        name:'CodSumiMedicamentos',
        data() {
            return {
                codigos: [],
                listTipoCodigo: [],
                search: '',
                headers: [
                    {
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'right',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Código',
                        align: 'right',
                        sortable: false,
                        value: 'Codigo'
                    },
                    {
                        text: 'Concentración',
                        align: 'right',
                        sortable: false,
                        value: 'concentracion'
                    },
                    {
                        text: 'U. Medida',
                        align: 'right',
                        sortable: false,
                        value: 'Unidad_aux'
                    },
                    {
                        text: 'Frecuencia',
                        align: 'right',
                        sortable: false,
                        value: 'Frecuencia'
                    },
                    {
                        text: 'Cantidad max ordenar',
                        align: 'right',
                        sortable: false,
                        value: 'Cantidadmaxordenar'
                    },
                    {
                        text: 'Nivel de ordenamiento',
                        align: 'right',
                        sortable: false,
                        value: 'Nivel_Ordenamiento'
                    },
                    {
                        text: 'Requiere autorización',
                        align: 'right',
                        sortable: false,
                        value: 'Requiere_autorizacion'
                    },
                    {
                        text: 'Estado',
                        align: 'right',
                        sortable: false,
                        value: 'Estado_id'
                    },
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: ''
                    }
                ],
                save: true,
                dialog: false,
                codetype: {},
                data:{
                    Nombre: '',
                    Codigo: '',
                    Tipocodesumi_id: '',
                    Nivel_Ordenamiento: '',
                    Requiere_autorizacion: '',
                    Genero: '',
                    EdadInicial: 0,
                    EdadFinal: 0,
                    Ambito: '',
                    Frecuencia: '',
                    Cantidadmaxordenar: '',
                    concentracion: '',
                    unidadMedida: '',
                    cantidad_maxima_ordenar_dia:''
                },
            }
        },
        filters:{
            textEstado: (estado) => {
                switch (estado) {
                case '1':
                    return 'Activo';
                    break;
                case '2':
                    return 'Inactivo';
                    break;
                default:
                }
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        mounted () {
            this.fetchCodigos()
            this.fetchTipoCodigos()
        },
        methods: {
            fetchCodigos() {
                axios.get('/api/codesumi/all')
                    .then(res => {
                        this.codigos = res.data;
                    })
            },
            createCodigo(){
                this.emptyData();
                this.save = true;
                this.dialog = true;
            },
            showError(message){
                swal({
                    title: "¡No puede ser!",
                    text: `${message.name}`,
                    icon: "warning",
                });
            },
            emptyData(){
                this.data = {
                    Nombre: '',
                    Codigo: '',
                    Tipocodesumi_id: '',
                    Nivel_Ordenamiento: '',
                    Requiere_autorizacion: '',
                    Genero: '',
                    EdadInicial: 0,
                    EdadFinal: 0,
                    Ambito: '',
                    Frecuencia: '',
                    Cantidadmaxordenar: '',
                    concentracion: '',
                    unidadMedida: '',
                    cantidad_maxima_ordenar_dia:''
                }
            },
            saveCodigo(){

                let validate = this.validatFields()

                if (validate) {
                    axios.post('/api/codesumi/create',this.data)
                        .then(res => {
                            this.emptyData();
                            this.dialog = false;
                            this.fetchCodigos();
                            swal({
                                title: "¡Código Creado!",
                                text: " ",
                                type: "success",
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                        })
                        .catch(err => this.showError(err.response.data))
                }

            },
            editCodigo(code){
                this.codetype = this.listTipoCodigo.find(type => type.id == code.Tipocodesumi_id)
                this.data = {
                    id: code.id,
                    Nombre: code.Nombre,
                    Codigo: code.Codigo,
                    Tipocodesumi_id: code.Tipocodesumi_id,
                    Nivel_Ordenamiento: code.Nivel_Ordenamiento,
                    Requiere_autorizacion: code.Requiere_autorizacion,
                    Frecuencia: code.Frecuencia,
                    Cantidadmaxordenar: code.Cantidadmaxordenar,
                    concentracion: code.concentracion,
                    unidadMedida: code.unidadMedida,
                    cantidad_maxima_ordenar_dia:code.cantidad_maxima_ordenar_dia
                };
                this.save = false;
                this.dialog = true;
            },
            updateCodigo() {

                let validate = this.validatFields()

                if (validate) {
                    axios.put(`/api/codesumi/edit/${this.data.id}`,this.data)
                        .then(res => {
                            this.emptyData();
                            this.dialog = false;
                            this.fetchCodigos();
                            swal({
                                title: "¡Codigo Actualizado!",
                                text: " ",
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                        })
                        .catch(err => {
                            this.showMessage(err.data.message)
                        })
                }
            },
            showMessage(message){
                swal({
                    title: `${message}`,
                    icon: "warning",
                });
            },
            fetchTipoCodigos() {
                axios.get('/api/tipocodigos/getCodeTypeByRole')
                    .then(res => {
                        this.listTipoCodigo = res.data
                    });
            },
            validatFields() {
                if (this.data.Tipocodesumi_id == '') {
                    swal({
                        title: "Codesumi",
                        text: "Por favor ingrese un Tipo Codesumi!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return false;
                }

                if (this.data.Nombre == '') {
                    swal({
                        title: "Codesumi",
                        text: "Por favor ingrese un Nombre!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return false;
                }

                if (this.data.Codigo == '') {
                    swal({
                        title: "Codesumi",
                        text: "Por favor ingrese un Codigo!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return false;
                }

                if (this.data.Nivel_Ordenamiento == '') {
                    swal({
                        title: "Codesumi",
                        text: "Por favor ingrese un Nivel Ordenamiento!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return false;
                }

                if (this.data.Requiere_autorizacion == '') {
                    swal({
                        title: "Codesumi",
                        text: "Por favor diligencie el campo Requiere Autorizacion!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return false;
                }

                if (this.data.Frecuencia == '') {
                    swal({
                        title: "Codesumi",
                        text: "Por favor ingrese una Frecuencia!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return false;
                }

                if (this.data.Cantidadmaxordenar == '') {
                    swal({
                        title: "Codesumi",
                        text: "Por favor ingrese una Cantidad Max Ordenar!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return false;
                }

                if (this.data.Genero == '' && this.data.Tipocodesumi_id == 3) {
                    swal({
                        title: "Codesumi",
                        text: "Por favor ingrese un Genero!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return false;
                }

                if (this.data.EdadInicial == 0 && this.data.Tipocodesumi_id == 3) {
                    swal({
                        title: "Codesumi",
                        text: "Por favor ingrese una Edad Inicial!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return false;
                }

                if (this.data.EdadFinal == 0 && this.data.Tipocodesumi_id == 3) {
                    swal({
                        title: "Codesumi",
                        text: "Por favor ingrese una Edad Final!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return false;
                }

                if (this.data.Ambito == '' && this.data.Tipocodesumi_id == 3) {
                    swal({
                        title: "Codesumi",
                        text: "Por favor ingrese un Ambito!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return false;
                }

                return true;
            },
            validateType(codetype) {
                let code = this.listTipoCodigo.filter(codigo => codigo.id == codetype)

                if (code.length == 0)
                    return false
                else
                    return true
            }
        },
    }
</script>
<style lang="scss" scoped>

</style>
