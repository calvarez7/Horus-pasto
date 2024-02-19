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

        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Errores Archivos</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>

                            <v-flex v-for="error in errores" :key="error" xs12 sm12 md12>
                                <v-alert :value="true" type="error">{{error.mensaje}}</v-alert>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="dialog = false">Cerrar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-card>
            <v-card-text>
                <v-container grid-list-md text-xs-center>
                    <v-layout row wrap>
                        <v-flex xs12 sm12>
                            <v-text-field readonly label="Cargar Archivos Planos .TXT RIPS" @click='onPickFile' v-model='fileName' prepend-icon="attach_file" outline clearable @click:clear="fileName = null"></v-text-field>
                            <input type="file" style="display: none" ref="fileInput" accept="*/*" multiple @change="onFilePicked"></v-flex>
                        <v-flex xs12 sm12 md6>
                            <v-autocomplete :items="entidades" item-value="id" item-text="nombre" v-model="entidad" label="Entidad" autocomplete></v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm5 md2>
                            <v-btn class="mb-3" tile color="primary" @click="validar">Validar RIPS&nbsp;</v-btn>
                        </v-flex>
                        <v-spacer class="mt-3"></v-spacer>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex xs12 sm12>
                            <v-data-table :headers="headers" :items="listaPaquetes" hide-actions class="elevation-1">
                                <template v-slot:items="props">
                                    <td class="text-xs-center">{{ props.item.Nombre }}</td>
                                    <td class="text-xs-center">{{ props.item.codigo }}</td>
                                    <td class="text-xs-center">{{ props.item.updated_at }}</td>
                                    <td class="text-xs-center"><v-chip :color="colorEstado(props.item.estado_id)" text-color="white">{{ props.item.estado.Nombre }}</v-chip>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn v-on="on" v-if="parseInt(props.item.estado_id) === 54 || parseInt(props.item.estado_id) === 56" @click="descargarErrores(props.item.id)" color="warning" fab small dark>
                                                    <v-icon>get_app</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Descargar Errores Validación</span>
                                        </v-tooltip>

                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn v-if="parseInt(props.item.estado_id) === 54 || parseInt(props.item.estado_id) === 56"  v-on="on" color="error" fab small dark @click="eliminarValidacion(props.item)">
                                                    <v-icon>delete</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Eliminar Validacion</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                                <template v-slot:no-data>
                                    <v-alert :value="true" type="info">
                                        Sin Registros
                                    </v-alert>
                                </template>
                            </v-data-table>
                        </v-flex>
                        <v-spacer class="mt-3"></v-spacer>
                    </v-layout>
                </v-container>
            </v-card-text>
        </v-card>


    </div>
</template>
<script>
import Swal from "sweetalert2";
export default {
    name:'validador',
    data:()=> ({
        fileName:"",
        errores:[],
        dialog:false,
        preload:false,
        entidad: null,
        listaPaquetes:[],
        entidades: [],
        headers: [
            { text: 'Paquete', value: 'nombre_paquete',sortable:false, align:"center" },
            { text: 'Codigo Habilitación', value: 'codigo',sortable:false, align:"center" },
            { text: 'Fecha Ultima Carga', value: 'created_at',sortable:false,align:"center" },
            { text: 'Estado', value: 'estado_id',sortable:false,align:"center" },
        ],
    }),
    created(){
        this.listaEntidades();
        Echo.channel('horus')
            .listen('EventEstadoRips', (e) => {
                this.listarPaquetesCargados();
            });
    },
    mounted() {
        this.listarPaquetesCargados();
    },
    methods:{
        validar(){
            if (!this.entidad) {
                return this.$alerError('El Campo Entidad es Requerido!');
            }
            if(this.$refs.fileInput.files.length === 0){
                return this.$alerError('Se requiere minimo el archivo "CT" y el archivo "AC" combinados con otro archivo de reporte');
            }
            this.preload = true;
            const formData = new FormData();
            let length = this.$refs.fileInput.files.length;
            for (let i = 0; i < length; i++) {
                if (this.$refs.fileInput.files[i].name.split('.')[1] === 'txt' || this.$refs.fileInput.files[i].name.split('.')[1] === 'TXT') {
                    formData.append('files[]', this.$refs.fileInput.files[i])
                } else {
                    return this.$alerError('Tipo de archivo no válido');
                }
            }
            formData.append('entidad', this.entidad);

            axios.post('/api/rips/validar', formData, {'Content-Type': 'multipart/form-data'}).then(res =>{
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'center-end',
                    background: '#4caf50',
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: false,
                    onOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                Toast.fire({
                    icon: 'success',
                    title: `<span style="color:#fff">${res.data.message}<span>`
                });
                this.limpiarFormularios();
                this.preload = false;
            }).catch(e => {
                this.dialog = true;
                this.errores = e.response.data.errors
                this.limpiarFormularios()
                this.preload = false;
            })
        },
        listarPaquetesCargados(){
            axios.get('/api/rips/enProcesoValidacion').then(res => {
                this.listaPaquetes = res.data;
                console.log(this.listaPaquetes);
            })
        },
        onPickFile () {
            this.$refs.fileInput.click()
        },
        onFilePicked (event) {
            const files = event.target.files
            if (files[0] !== undefined) {
                for (const [key, value] of Object.entries(files)) {
                    this.fileName += value.name+", "
                }

            } else {
                this.fileName = ''
            }
        },
        limpiarFormularios(){
            this.entidad = null;
            this.fileName = '';
            this.$refs.fileInput.value=null;
        },
        colorEstado(estado){
            let color = 'primary'
            switch (parseInt(estado)){
                case 53:
                case 55:
                    color = "orange";
                    break;
                case 54:
                case 56:
                case 57:
                    color = "red";
                    break;
            }
            return color;
        },
        descargarErrores(id){
            this.preload = true;
            axios.get("/api/rips/descargarerrores/"+id ,{
                responseType: "blob"
            })
                .then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = "RegistroErrores"+id
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.preload = false;
                }).catch(err => {
                this.preload = false;
                console.log(err)
            })
        },
        eliminarValidacion(item){
            Swal.fire({
                title: 'Eliminar Validacion?',
                text: 'Una vez eliminado, se perdera la información',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#4CAF50",
                cancelButtonColor: "#FF5252",
                confirmButtonText: "SI",
                cancelButtonText: "NO"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    this.preload = true;
                    axios.get('/api/rips/eliminarProcesoValidacion/'+item.id).then(res => {
                        console.log(res.data,"fd");
                        this.$alerSuccess(res.data.message);
                        this.listarPaquetesCargados();
                        this.preload = false;
                    }).catch(e => {
                        this.$alerError(e.response.data.message);
                        this.preload = false;
                    })

                }
            });
        },
        listaEntidades(){
            axios.get('/api/entidades/getEntidades').then(res => {
                this.entidades = res.data;
            })
        }
    }
}
</script>


