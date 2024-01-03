<template>
    <div>
        <template>
            <div class="text-center">
                <v-dialog v-model="preload" persistent width="300">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Tranquilo procesamos tu solicitud !
                            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </div>
        </template>
        <v-dialog v-model="dialog" max-width="800px" persistent>
            <template v-slot:activator="{ on }">
            </template>
            <v-card>
                <v-card-title>
                    <span class="headline">{{ titulo }}</span>
                </v-card-title>

                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="form.nombre" label="Nombre"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <p>Estado</p>
                                <v-radio-group v-model="form.estado_id" row>
                                    <v-radio label="Activo" color="success" :value="1"></v-radio>
                                    <v-radio label="Inactivo" color="error" :value="2"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs12 sm10 md10>
                                <v-autocomplete label="Procedimiento" :items="cups" :item-text="cup => cup.Codigo+' - '+cup.Nombre" v-model="cup" return-object></v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm2 md2>
                                <v-btn color="success" @click="agregarProcedimiento">Agregar</v-btn>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                                <v-data-table
                                    :headers="headersCups"
                                    :items="cupsPaquetes"
                                    class="elevation-1"
                                >
                                    <template v-slot:items="props">
                                        <td class="text-xs-center">{{ props.item.codigo }}</td>
                                        <td class="text-xs-center">{{ props.item.nombre }}</td>
                                        <td class="text-xs-center">
                                            <v-edit-dialog :return-value.sync="props.item.cantidad" large lazy persistent>
                                                <div>{{ props.item.cantidad }}</div>
                                                <template v-slot:input>
                                                    <div class="mt-3 title">Update Iron</div>
                                                </template>
                                                <template v-slot:input>
                                                    <v-text-field v-model="props.item.cantidad" label="Edit" single-line counter autofocus type="number"></v-text-field>
                                                </template>
                                            </v-edit-dialog>
                                        </td>
                                        <td class="text-xs-center">
                                            <v-icon small @click="quitarCup(props.item)">delete</v-icon>
                                        </td>
                                    </template>

                                </v-data-table>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="limpiarCampos">Cancelar</v-btn>
                    <v-btn color="success" @click="guardarPaquete">Guardar Cambios</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-toolbar flat color="white">
            <v-toolbar-title>Paquete Servicios</v-toolbar-title>
            <v-divider
                class="mx-2"
                inset
                vertical
            ></v-divider>
            <v-spacer></v-spacer>
            <v-btn color="primary" dark class="mb-2"  @click="registroPaquete()">Nuevo Paquete</v-btn>
        </v-toolbar>
        <v-data-table
            :headers="headers"
            :items="listaPaquetes"
            class="elevation-1"
        >
            <template v-slot:items="props">
                <td class="text-xs-center">{{ props.item.id }}</td>
                <td class="text-xs-center">{{ props.item.nombre }}</td>
                <td class="text-xs-center">
                    <v-chip v-if="props.item.estado_id === 1" color="green" text-color="white">Activo</v-chip>
                    <v-chip v-else color="red" text-color="white">Inactivo</v-chip>
                </td>
                <td class="text-xs-center">
                    <v-icon small class="mr-2" @click="registroPaquete(props.item)">edit</v-icon>
                </td>
            </template>
            <template v-slot:no-data>
                <v-alert :value="true" color="error" icon="warning">
                    sin Registro
                </v-alert>
            </template>
        </v-data-table>
    </div>
</template>
<script>
import {CupService} from "../../../services";

export default {

    data: () => ({
        dialog: false,
        preload:false,
        titulo: "",
        headers: [
            {text: '# Registro', align: 'center', sortable: false, value: 'id'},
            {text: 'Nombre', align: 'center', sortable: false, value: 'nombre'},
            {text: 'Estado', align: 'center', sortable: false, value: 'estado_id'},
            {text: 'Accion', align: 'center', value: 'name', sortable: false }
        ],
        headersCups:[
            {text: 'Codigo', align: 'center', sortable: false, value: 'id'},
            {text: 'Nombre', align: 'center', sortable: false, value: 'id'},
            {text: 'Cantidad', align: 'center', sortable: false, value: 'id'},
            {text: 'Accion', align: 'center', sortable: false, value: 'id'},
        ],
        form:{
            id:null,
            nombre:null,
            estado_id: 1,
        },
        cup:null,
        cups:[],
        cupsPaquetes:[],
        listaPaquetes: [],
    }),
    mounted() {
        this.listarPaquetes();
        this.fetchCupsOtherService();

    },
    methods:{
        listarPaquetes(){
            this.preload = true;
          axios.get('/api/cups/paquetes/servicios').then(res => {
              this.listaPaquetes = res.data;
              this.preload = false;
          })
        },
        registroPaquete(item = {}){
            this.preload = true;
            if(item.detalle_paquete === undefined){
                this.titulo = "Crear Paquete Servicios"
            }else{
                this.titulo = "Editar Paquete Servicios"
                this.form.nombre = item.nombre;
                this.form.estado_id = item.estado_id;
                this.form.id = item.id;
                this.cupsPaquetes = item.detalle_paquete.map((res) =>{
                    return{
                        cup_id: res.cup_id,
                        nombre: res.cup.Nombre,
                        codigo: res.cup.Codigo,
                        cantidad: parseInt(res.cantidad),
                    }
                })
            }
            this.preload = false;
            this.dialog = true;
        },
        fetchCupsOtherService: async function () {
            this.preload = true;
            try {
                this.cups = await CupService.getCupsOtherServiceByUserRoleLevel();
                this.preload = false;
            } catch (error) {
                this.preload = false;
                console.log(error);
            }
        },
        agregarProcedimiento(){
            this.preload = true;
            if(!this.cup){
                this.preload = false;
                return this.$alerError("El campo 'procedimiento' es requerido.")
            }
            let objIndex = this.cupsPaquetes.findIndex((obj => parseInt(obj.cup_id) === parseInt(this.cup.id)));
            if (objIndex > -1) {
                this.preload = false;
                return this.$alerError("El procedimiento ya se encuentra registrado.")
            }
            this.cupsPaquetes.push({
                cup_id: this.cup.id,
                nombre: this.cup.Nombre,
                codigo: this.cup.Codigo,
                cantidad: 1,
            })
            this.preload = false;
        },
        quitarCup(item){
            this.preload = true;
            let objIndex = this.cupsPaquetes.findIndex((obj => parseInt(obj.cup_id) === parseInt(item.cup_id)));
            this.cupsPaquetes.splice(objIndex, 1);
            this.preload = false;
        },
        limpiarCampos(){
            this.preload = true;
            this.cup = null;
            this.cupsPaquetes = [];
            this.form.nombre = null;
            this.form.estado_id = 1;
            this.form.id =  null;
            this.dialog = false;
            this.preload = false;
            this.listarPaquetes();
        },
        guardarPaquete(){
            this.preload = true;
            if(this.cupsPaquetes.filter(cup => parseInt(cup.cantidad) < 1).length > 0){
                this.preload = false;
                return this.$alerError("Hay procedimientos con una cantidad inferior a 1.")
            }
            if(this.cupsPaquetes.length === 0){
                this.preload = false;
                return this.$alerError("Se requiere minimo un procedimiento asociado.")
            }
            if(!this.form.nombre){
                this.preload = false;
                return this.$alerError("El campo 'Nombre' es requerido.")
            }
            const data = {
                cabezera: this.form,
                procedimientos: this.cupsPaquetes
            }
            axios.post('/api/cups/guardarPaquete',data).then(res => {
                this.$alerSuccess(res.data.message);
                this.preload = false;
                this.limpiarCampos();
            })
        }
    }
}

</script>
