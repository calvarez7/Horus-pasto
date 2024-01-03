<template>
    <v-card>
        <template>

            <v-card height="55px">
                <v-bottom-nav :active.sync="bottomNav" :color="color" :value="true" dark shift>
                    <v-btn dark @click="plantillas()">
                        <span>Plantilla</span>
                        <v-icon>chrome_reader_mode</v-icon>
                    </v-btn>
                </v-bottom-nav>
            </v-card>

            <v-card-title v-show="plantilla">
                <v-tooltip top>
                    <template v-slot:activator="{ on }">
                        <v-btn text icon color="primary" dark v-on="on" @click="dialogPlantilla = true">
                            <v-icon>add</v-icon>
                        </v-btn>
                    </template>
                    <span>Crear plantilla</span>
                </v-tooltip>
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                </v-text-field>
            </v-card-title>
            <v-data-table v-show="plantilla" :search="search" :headers="headers" :items="users" class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.cedula }}</td>
                    <td>{{ props.item.email }}</td>
                    <td class="justify-center layout px-0">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="green" dark v-on="on" @click="adduserPlantilla(props.item)">
                                    <v-icon>add</v-icon>
                                </v-btn>
                            </template>
                            <span>Agregar plantilla</span>
                        </v-tooltip>
                    </td>
                </template>
            </v-data-table>

            <v-dialog v-model="dialogPlantilla" persistent max-width="1000px">
                <v-card max-height="100%" v-show="true">
                    <v-toolbar color="primary" flat dark>
                        <v-flex xs12 text-xs-center>
                            <v-toolbar-title>Crear plantilla</v-toolbar-title>
                        </v-flex>
                    </v-toolbar>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-text-field label="Nombre" v-model="datosplantilla.nombre"></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="Indicación" textarea v-model="datosplantilla.indicacion">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="Tecnica" textarea v-model="datosplantilla.tecnica"></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="Hallazgos" textarea v-model="datosplantilla.hallazgos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="Conclusión" textarea v-model="datosplantilla.conclusion">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success" @click="savePlantilla()">Guardar</v-btn>
                        <v-btn color="error" @click="closeCrearplantilla()">Salir</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialogAddplantilla" persistent max-width="600px">
                <v-card>
                    <v-card-title class="headline primary" style="color:white">
                        <span class="headline">Agregar plantilla a usuario</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm12 md12 v-if="plantillaUserguardadas != ''">
                                    <v-subheader>PLANTILLAS AGREGADAS</v-subheader>
                                    <v-item-group multiple>
                                        <v-item v-for="(data, index) in plantillaUserguardadas" :key="index">
                                            <v-chip label>
                                                {{data.nombre}}
                                            </v-chip>
                                        </v-item>
                                    </v-item-group>
                                    <v-divider class="my-2"></v-divider>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-autocomplete v-model="plantilla_user" :items="plantillas_guardadas"
                                        item-text="nombre" item-value="id" chips label="Nombre" multiple>
                                        <template v-slot:selection="data">
                                            <v-chip :selected="data.selected" close class="chip--select-multi"
                                                @input="remove_plantilla(data.item.id)">
                                                {{ data.item.nombre }}
                                            </v-chip>
                                        </template>
                                        <template v-slot:item="data">
                                            <template v-if="typeof data.item.nombre !== 'object'">
                                                <v-list-tile-content v-text="data.item.nombre"></v-list-tile-content>
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
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success" @click="savePlantilla_user()">Guardar</v-btn>
                        <v-btn color="error" @click="dialogAddplantilla = false, plantilla_user = []">Salir</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>


        </template>
    </v-card>

</template>

<script>
    import Swal from 'sweetalert2';
    export default {
        data() {
            return {
                bottomNav: 3,
                plantilla: false,
                search: '',
                headers: [{
                        text: "id",
                        value: "id",
                        align: "left",
                        sortable: true
                    },
                    {
                        text: "Nombre",
                        value: "name",
                        align: "left",
                        sortable: true
                    },
                    {
                        text: "Documento",
                        value: "cedula",
                        align: "left"
                    },
                    {
                        text: "Email",
                        value: "email",
                        align: "left"
                    },
                    {
                        text: "",
                        align: "center"
                    }
                ],
                users: [],
                datosplantilla: {
                    nombre: '',
                    indicacion: '',
                    tecnica: '',
                    hallazgos: '',
                    conclusion: '',
                    nota: ''
                },
                dialogPlantilla: false,
                plantillas_guardadas: [],
                dialogAddplantilla: false,
                plantilla_user: [],
                plantillaUserguardadas: []
            }
        },

        computed: {
            color() {
                switch (this.bottomNav) {
                    case 0:
                        return 'teal'
                    case 1:
                        return 'primary'
                    case 2:
                        return 'brown'
                    case 3:
                        return 'indigo'
                }
            }
        },
        methods: {
            plantillas() {
                this.usersImagenologia();
                this.plantilla = true
                this.allPlantilla();
            },
            usersImagenologia() {
                axios.get('/api/imagenologia/userImagenologia').then(res => {
                    this.users = res.data
                });
            },
            closeCrearplantilla() {
                this.dialogPlantilla = false
                this.datosplantilla = {}
            },
            savePlantilla() {
                axios.post(`/api/imagenologia/createPlantilla/`, this.datosplantilla).then((res) => {
                    this.closeCrearplantilla();
                    this.allPlantilla();
                    this.$alerSuccess("Plantilla creada con exito!");
                }).catch(err => {
                    let msg = '';
                    for (const pro in err.response.data) {
                        if (msg) msg += `${err.response.data[pro]}`
                        else msg = err.response.data[pro]
                    }
                    this.$alerError(msg);
                })
            },
            allPlantilla() {
                axios.get('/api/imagenologia/allPlantilla').then(res => {
                    this.plantillas_guardadas = res.data
                });
            },
            adduserPlantilla(user) {
                this.user = {
                    id: user.id
                }
                this.dialogAddplantilla = true
                axios.post(`/api/imagenologia/allPlantillaUsers/`, this.user).then((res) => {
                    this.plantillaUserguardadas = res.data
                });
            },
            remove_plantilla(item) {
                this.plantilla_user.splice(this.plantilla_user.indexOf(item), 1)
                this.plantilla_user = [...this.plantilla_user]
            },
            savePlantilla_user() {
                if (this.plantilla_user.length == 0) return
                let formData = new FormData();
                for (let i = 0; i < this.plantilla_user.length; i++) {
                    formData.append(`plantilla_id[]`, this.plantilla_user[i]);
                }
                formData.append('user_id', this.user.id)
                axios.post(`/api/imagenologia/addplantillaUser/`, formData).then((res) => {
                    this.plantilla_user = []
                    this.dialogAddplantilla = false
                    this.$alerSuccess("Plantilla agregada a usuario con exito!");
                }).catch(err => {
                    let msg = '';
                    for (const pro in err.response.data) {
                        if (msg) msg += `${err.response.data[pro]}`
                        else msg = err.response.data[pro]
                    }
                    this.$alerError(msg);
                })
            }
        }
    }

</script>
