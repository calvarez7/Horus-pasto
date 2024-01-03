<template>
    <v-card>
        <v-container pa-1>
            <v-layout row>
                <v-dialog v-model="dialog" max-width="600px">
                    <v-card>
                        <form @submit.prevent="saveform">
                        <v-card-title class="headline success" style="color:white">
                            <span class="headline">{{typeForm.title}}</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field label="Nombre*" v-model="form.nombre" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field label="Direccion*" v-model="form.direccion" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field label="Telefono*"  v-model="form.telefono" required></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                            <v-btn  color="blue darken-1" flat type="submit">Guardar</v-btn>
                        </v-card-actions>
                        </form>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-card-title>
                <v-btn round color="primary" dark @click="createForm('save')" ><v-icon left>add</v-icon>Crear Proveedor</v-btn>
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
                :items="listProveedores"
                :search="search"
            >
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td class="text-xs-left">{{ props.item.nombre }}</td>
                    <td class="text-xs-left">{{ props.item.direccion }}</td>
                    <td class="text-xs-left">{{ props.item.telefono }}</td>
                    <td class="text-xs-left">{{ props.item.estado_id | textEstado }}</td>
                    <td>
                        <v-btn fab outline color="warning" small @click="createForm('edit',props.item)"><v-icon>edit</v-icon></v-btn>
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
export default {
    data(){
        return{
            dialog:false,
            listProveedores: [],
            search:"",
            form:{
              nombre:null,
              direccion:null,
              telefono:null
            },
            typeForm:{
                title:null,
                url:null
            },
            headers: [
                {
                    text: 'id',
                    align: 'left',
                    value: 'id'
                },
                {
                    text: 'Nombre',
                    align: 'left',
                    sortable: false,
                    value: 'nombre'
                },
                {
                    text: 'Direccion',
                    align: 'left',
                    sortable: false,
                    value: 'direccion'
                },{
                    text: 'Telefono',
                    align: 'left',
                    sortable: false,
                    value: 'telefono'
                },{
                    text: 'Estado',
                    align: 'left',
                    sortable: false,
                    value: ''
                },{
                    text: '',
                    sortable: false,
                    value: ''
                }
                ]
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
    methods:{
        getProveedores(){
            axios.get('/api/bodega/proveedores/lista').then(res => {
                this.listProveedores = res.data;
            })
        },
        createForm(type,item = []){
            switch (type){
                case 'save':
                    this.typeForm.title='Nuevo Proveedor'
                    this.typeForm.url= '/api/bodega/proveedores/guardar';
                    for (const prop of Object.getOwnPropertyNames(this.form)) {
                        this.form[prop] = null;
                    }
                    break;
                    case 'edit':
                        this.typeForm.title='Editar Proveedor'
                        this.typeForm.url= '/api/bodega/proveedores/edita/'+item.id;
                        for (const prop of Object.getOwnPropertyNames(this.form)) {
                            this.form[prop] = item[prop];
                        }
                        break
            }
            this.dialog = true;
        },
        saveform(){
            axios.post(this.typeForm.url,this.form).then(res => {
                this.dialog = false;
                this.$alerSuccess(res.data.message);
                this.getProveedores();
            })
        }
    },
    mounted() {
        this.getProveedores();
    }
}
</script>
