<template>
    <v-flex xs12 px-1>
        <v-card>
            <v-container pa-1>
                <v-container pa-0>
                    <v-layout row>
                        <v-dialog v-model="dialog" max-width="1000px">
                        <v-card>
                            <v-card-title>
                            <span v-if="save" class="headline">Crear Código Propio</span>
                            <span v-else class="headline">Editar Código Propio</span>
                            </v-card-title>
                            <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs1 v-show="save">
                                        <v-checkbox 
                                            color="primary"
                                            v-model="data.other"
                                            label="Otro" />
                                    </v-flex>
                                    <v-flex xs11 v-show="!data.other">
                                        <v-flex xs12 sm12 md12>
                                            <v-autocomplete label="Código propios IPS" :items="codePropios" item-text="Descripcion" item-value="id" v-model="data.Codepropio_id" required no-data-text="No se encontró codigo propio con esa descripción"></v-autocomplete>
                                        </v-flex>
                                    </v-flex>
                                    <v-flex xs12 v-show="data.other" >
                                        <v-layout row wrap>
                                            <v-flex  xs12 sm12 md12>
                                                <v-autocomplete label="Codigo sumi" :items="codesumi" item-text="Nombre" item-value="id" v-model="data.Codesumi_id" required></v-autocomplete>
                                            </v-flex>
                                            <v-flex  xs12 sm4 md4>
                                                <v-text-field label="Código propio IPS" v-model="data.Codigo" required></v-text-field>
                                            </v-flex>
                                            <v-flex  xs12 sm8 md8>
                                                <v-text-field label="Descripción código propio IPS" v-model="data.Descripcion" required></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex  xs12 sm4 md4>
                                        <v-text-field   label="Valor"
                                                        required
                                                        type="number"
                                                        v-model.number="data.Valor" />
                                    </v-flex>
                                    <v-flex  xs12 sm6 md6>
                                        <v-select   :items="ambitos"
                                                    label="Ambito"
                                                    required
                                                    v-model="data.Ambito" />
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            </v-card-text>
                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                            <v-btn v-if="save" color="blue darken-1" flat @click="saveCodigoPropio()">Guardar</v-btn>
                            <v-btn v-else color="blue darken-1" flat @click="updateCodigoPropio()">Actualizar</v-btn>
                            </v-card-actions>
                        </v-card>
                        </v-dialog>
                    </v-layout>
                </v-container>
                <v-card-title>
                    <v-flex xs12>
                        <h4 class="headline layout justify-center">Códigos Propios</h4>
                    </v-flex>
                    
                    <v-flex 
                        sm8
                        xs8>
                        <v-autocomplete
                            v-model="Prestador_id"
                            append-icon="search"
                            label="Buscar prestador..."
                            :items="fullnameprestador"
                            item-text="fullname"
                            item-value="id"
                            hide-details
                            @input="buscarSedesPrestador()"
                        ></v-autocomplete>
                    </v-flex>  
                    <v-flex xs12 mt-3 v-show="Prestador_id">
                        <v-layout row wrap>
                            <v-flex xs6>
                                <v-autocomplete
                                    v-model="data.sedeproveedor_id"
                                    append-icon="search"
                                    label="Buscar sede..."
                                    :items="fullnamesede"
                                    item-text="fullname"
                                    item-value="id"
                                    hide-details
                                    @input="fetchCodePropiosSede();"
                                ></v-autocomplete>
                            </v-flex> 
                            <v-flex xs2 v-show="data.sedeproveedor_id">
                                <v-btn round @click="createCodigoPropio()" color="primary" dark >
                                    <v-icon left>exposure_plus_1</v-icon>
                                    agregar código
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-card-title>
                <v-data-table
                    :headers="headers"
                    :items="codigoPropios"
                    :search="search"
                    >
                    <template v-slot:items="props">
                        <td class="text-xs-right">{{ props.item.Codigo }}</td>
                        <td class="text-xs-right">{{ props.item.Descripcion }}</td>
                        <td class="text-xs-right">{{ props.item.Valor }}</td>
                        <td class="text-xs-right">{{ props.item.Ambito }}</td>
                        <td class="text-xs-right">{{ props.item.Nombrecodesumi }}</td>
                        <td class="text-xs-right">
                            <v-btn fab outline color="warning" small @click="editCodigoPropio(props.item)"><v-icon>edit</v-icon></v-btn>
                        </td>
                    </template>
                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                </v-data-table>
            </v-container>
        </v-card>
    </v-flex>
</template>

<script>
 
    export default {
        name: 'CodigoManual',
        components: {
        },
        data(){
            return {
                ambitos:['MIXTO', 'HOSPITALARIO','AMBULATORIO'],
                codePropios: [],
                search: '',
                headers: [
                    {
                        text: 'Código',
                        align: 'right',
                        sortable: false,
                        value: 'Codigo'
                    },
                    {
                        text: 'Descripción',
                        align: 'right',
                        sortable: false,
                        value: 'Descripcion'
                    },
                    {
                        text: 'Valor',
                        align: 'right',
                        sortable: false,
                        value: 'Valor'
                    },
                    {
                        text: 'Ámbito',
                        align: 'right',
                        sortable: false,
                        value: 'Ambito'
                    },
                    {
                        text: 'Código Sumi',
                        align: 'right',
                        sortable: false,
                        value: 'Nombrecodesumi'
                    },
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: ''
                    },
                ],
                save: true,
                dialog: false,
                data:{
                    Codigo: '',
                    sedeproveedor_id: '',
                    Descripcion: '',
                    Codepropio_id: null,
                    Valor: null,
                    Codesumi_id: '',
                    other: false,
                    Ambito: '',
                },
                Prestador_id:'',
                prestadores: [],
                sedesprestadores: [],
                codigoPropios: [],
                codesumi: []
            }
        },
        computed:{
            fullnameprestador(){
                return this.prestadores.map((prestador) => {
                    return {
                        id: prestador.id,
                        fullname: `${prestador.Nit} - ${prestador.Nombre}`
                    }
                })
            },
            fullnamesede(){
                return this.sedesprestadores.map((sede) => {
                    return {
                        id: sede.id,
                        fullname: `${sede.Codigo_habilitacion} - ${sede.Municipio} - ${sede.Nombre}`
                    }
                })
            }
        },
        mounted(){
            this.fetchPrestadores();
            this.fetchCodeSumi();
            this.fetchAllCodePropios();
        },
        methods: {
            fetchCodeSumi(){
                const type = 3
                axios.post('/api/codesumi/codesumiByType', {
                    type
                })
                    .then((res) => this.codesumi = res.data)
                    .catch((err) => console.log(err));
            },
            fetchPrestadores(){
                axios.get('/api/prestador/all')
                        .then((res) => this.prestadores = res.data)
                        .catch((err) => console.log(err));
            },
            buscarSedesPrestador(){
                axios.post('/api/sedeproveedore/all',{ Prestador_id: this.Prestador_id })
                        .then((res) => {
                            this.sedesprestadores = res.data
                        })
                        .catch((err) => console.log(err));
            },
            fetchCodePropiosSede(){
                axios.post('/api/codepropio/allBodega',{ Prestador_id: this.data.sedeproveedor_id })
                        .then((res) => {
                            this.codigoPropios = res.data
                        })
                        .catch((err) => console.log(err));
            },
            fetchAllCodePropios(){
                axios.post('/api/codepropio/all')
                        .then((res) => {
                            this.codePropios = res.data.map(data => {
                                return {
                                    id: data.id,
                                    Descripcion: `${data.Codigo}   ${data.Descripcion}`
                                }
                            })
                        })
                        .catch((err) => console.log(err));
            },
            createCodigoPropio(){
                this.data.Codigo = '';
                this.data.Descripcion = '';
                this.data.Valor = null;
                this.data.Codesumi_id = '';
                this.data.Codepropio_id = '';
                this.data.other = false;
                this.data.Ambito = '';
                this.dialog = true;
                this.save = true;
            },
            saveCodigoPropio(){
                axios.post(`/api/codepropio/${this.data.sedeproveedor_id}/create`, this.data)
                        .then((res) => {
                            this.fetchCodePropiosSede();
                            this.dialog = false;
                            swal({
                                title: `¡${res.data.message}!`,
                                text: " ",
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                        })
                        .catch((err) => console.log(err.response));
            },
            clearFields(){
                this.data = {
                    Codigo: '',
                    Prestador_id: '',
                    Descripcion: '',
                    valor: '',
                    Codesumi_id: '',
                    Codepropio_id: '',
                }
            },
            editCodigoPropio(codigo){
                this.data.id = codigo.id;
                this.data.Codepropio_id = parseInt(codigo.Codigopropio_id);
                this.data.Valor = codigo.Valor;
                this.data.Ambito = codigo.Ambito;
                this.data.other = false;
                this.dialog = true;
                this.save = false;
            },
            updateCodigoPropio(){
                axios.put(`/api/codepropio/edit/${this.data.id}`, this.data )
                        .then((res) => {
                            this.fetchCodePropiosSede();
                            this.dialog = false;
                            swal({
                                title: `¡${res.data.message}!`,
                                text: " ",
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                        })
                        .catch((err) => console.log(err.response));
            }
        }    
    }
</script>

<style lang="scss" scoped>

</style>