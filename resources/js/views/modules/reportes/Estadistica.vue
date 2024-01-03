<template>
    <div>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-toolbar dark color="primary">

                    <v-toolbar-title class="white--text">Estadisticas</v-toolbar-title>

                    <v-spacer></v-spacer>

                    <v-btn v-if="can('generar.estadistica')" color="success" class="white--text" @click="nuevaEstadistica = true">
                        Nuevo
                        <v-icon right dark>mdi-file-chart</v-icon>
                    </v-btn>

                </v-toolbar>
                <v-flex xs12 sm4 md2 v-for="card in cards" :key="card.id">
                    <v-card>
                        <v-responsive class="pt-4">
                            <v-img :src="card.imagen"></v-img>
                            <v-card-actions>
                                <v-btn block color="success" @click="verEstadistica(card)"><span
                                        style="font-size: 12px">{{ card.titulo }}</span></v-btn>
                            </v-card-actions>
                        </v-responsive>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{ inframe.titulo }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-list three-line subheader>
                    <iframe width="1324px" height="804" :src="inframe.inframe" frameborder="0"
                        allowFullScreen="true"></iframe>
                    <pre>{{inframe.titulo}}</pre>
                </v-list>
                <v-divider></v-divider>
            </v-card>
        </v-dialog>
        <!-- MODAL PARA LA CREACION DE NUEVA ESTADISTICA -->
        <v-dialog v-model="nuevaEstadistica" persistent max-width="600px">
            <v-card>
                <v-card-title class="headline" style="color:white;background-color:#4caf50">
                    <span>Nueva estadistica</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm12 md12>
                                <v-text-field v-model="newEstadistica.titulo" label="Titulo*" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                                <v-text-field readonly label="imagen*" @click='onPickFile'
                                    v-model='newEstadistica.imagen' prepend-icon="attach_file">
                                </v-text-field>
                                <!-- Hidden -->
                                <input type="file" style="display: none" ref="fileInput" accept="*/*">
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                                <v-text-field v-model="newEstadistica.inframe" label="inframe*" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-autocomplete v-model="newEstadistica.permiso" :items="permisos" item-text="name"
                                    item-value="id" label="Permiso"></v-autocomplete>
                            </v-flex>
                        </v-layout>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red" dark @click="nuevaEstadistica = false">Cerrar</v-btn>
                    <v-btn color="success" @click="crearEstadistica()">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    export default {
        name: 'HorusHealthEstadistica',

        data() {
            return {
                cards: [],
                dialog: false,
                inframe: '',
                nuevaEstadistica: false,
                newEstadistica: {
                    titulo: '',
                    imagen: '',
                    inframe: '',
                    permiso: ''
                },
                permisos: [],
            };
        },

        computed: {
            ...mapGetters(['can'])
        },

        mounted() {
            this.estadistica();
            this.fetchPermissions()
        },

        methods: {
            onPickFile() {
                this.$refs.fileInput.click()
            },
            estadistica() {
                axios.get('/api/estadistica').then(res => {
                    this.cards = res.data.filter(item => this.can(item.permiso));
                })
            },

            verEstadistica(items) {
                this.dialog = true
                this.inframe = items
            },

            crearEstadistica() {
                const formData = new FormData();
                formData.append('titulo', this.newEstadistica.titulo)
                formData.append('inframe', this.newEstadistica.inframe)
                formData.append('permiso', this.newEstadistica.permiso)
                formData.append('file', this.$refs.fileInput.files[0])
                axios.post('/api/estadistica', formData, {
                        'Content-Type': 'form-data'
                    })
                    .then(res => {
                        this.$alerSuccess(res.data.message);
                        this.limpiarCampos();
                        this.nuevaEstadistica = false;
                        this.estadistica()
                    })
                    .catch(err => {
                        console.error(err);
                    })
            },

            limpiarCampos() {
                for (const key in this.newEstadistica) {
                    this.newEstadistica[key] = ''
                }
            },

            fetchPermissions() {
                axios.get('/api/permiso/allWithTipos')
                    .then(res => {
                        this.permisos = res.data
                    })
            },
        },
    };

</script>

<style lang="scss" scoped>

</style>
