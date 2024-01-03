<template>

    <div>
        <v-layout>
            <v-flex shrink xs12 mr-1>
                <v-card max-height="100%" class="mb-3">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Buscar resultados del paciente</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-container grid-list-md fluid class="pa-0">
                            <v-layout wrap align-center justify-center>
                                <v-flex xs12>
                                    <v-form @submit.prevent="search_paciente()">
                                        <v-layout row wrap>
                                            <v-flex xs3>
                                                <v-text-field v-model="cedula_paciente" label="Número de Documento">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs2>
                                                <v-btn @click="search_paciente()" @keyup.enter="search_paciente()" v-if="!datos" fab
                                                    outline small color="success">
                                                    <v-icon>search</v-icon>
                                                </v-btn>
                                                <v-btn @click="clearFields()" v-if="datos" fab outline small
                                                    color="error">
                                                    <v-icon>clear</v-icon>
                                                </v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </v-form>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-flex xs12 v-show="datos" >
                        <v-card>
                            <v-card-title>
                                Consulta de Resultados
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search" append-icon="search" label="Search" single-line
                                    hide-details>
                                </v-text-field>
                            </v-card-title>
                            <v-data-table :headers="headers" :items="consulta" :search="search">
                                <template v-slot:items="props">
                                    <td class="text-xs-left">{{ props.item.cedula }}</td>
                                    <td class="text-xs-left">{{ props.item.Primer_Nom }} {{ props.item.Primer_Ape }}
                                        {{ props.item.Segundo_Ape }}</td>
                                    <td class="text-xs-left">{{ props.item.entidad }}</td>
                                    <td class="text-xs-left">{{ props.item.creado.split('.')[0] }}</td>
                                    <td>
                                        <v-btn>
                                            <a :href="`${props.item.ruta}`" target="_blank"
                                                style="text-decoration:none">
                                                <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                            </a>
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
                    </v-flex>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>
<script>
    export default {
        data: () => ({
            search: '',
            cedula_paciente: '',
            paciente: '',
            consulta: [],
            headers: [{
                    text: 'Cédula',
                    align: 'left',
                    sortable: false,
                    value: 'cedula'
                },
                {
                    text: 'Nombre',
                    align: 'left',
                    sortable: false,
                    value: 'Primer_Nom'
                },
                {
                    text: 'Entidad',
                    align: 'left',
                    sortable: false,
                    value: 'Num_Doc'
                },
                {
                    text: 'Fecha Carga',
                    align: 'left',
                    sortable: false,
                    value: 'creado'
                },
                {
                    text: 'Ver',
                    align: 'left',
                    sortable: false,
                },
            ],
            datos: false
        }),
        methods: {
             search_paciente() {
                if (!this.cedula_paciente) {
                    swal({
                        title: "Debe ingresar un cédula",
                        icon: "warning"
                    });
                    return;
                }
                axios.get(`/api/paciente/showEnabled/${this.cedula_paciente}`)
                    .then((res) => {
                        if (res.data.paciente) {
                           this.find();
                        }
                        if (res.data.message) this.showMessage(res.data.message);
                    });
            },
            showMessage(message) {
                swal({
                    title: `${message}`,
                    icon: "warning",
                });
            },
            find() {
                axios
                    .post("/api/covid/consulta", {
                        Num_Doc: this.cedula_paciente
                    })
                    .then(res => {
                        this.datos = true
                        this.consulta = res.data;
                    });
            },
            clearFields() {
                this.cedula_paciente = ''
                this.datos = false
            },
        }
    };

</script>

<style lang="scss" scoped>
</style>
