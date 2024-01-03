<template>
    <div>
        <v-layout>
            <v-flex xs12 sm12 md12>
                <v-card>

                    <v-card>
                        <v-bottom-nav :value="true" color="transparent">
                            <v-btn color="primary" flat
                                @click="tipomanual = true, codigomanual = false, salariominimo = false">
                                <span>Tipo Manual</span>
                                <v-icon>list_alt</v-icon>
                            </v-btn>
                            <v-btn color="primary" flat
                                @click="codigomanual = true, tipomanual = false, salariominimo = false">
                                <span>Codigo Manual</span>
                                <v-icon>style</v-icon>
                            </v-btn>
                            <v-btn color="primary" flat
                                @click="salariominimo = true, codigomanual = false, tipomanual = false">
                                <span>Salario Minimo</span>
                                <v-icon>account_balance</v-icon>
                            </v-btn>
                        </v-bottom-nav>
                    </v-card>

                    <v-card v-show="tipomanual">
                        <v-layout row wrap>
                            <TipoManual />
                        </v-layout>
                    </v-card>

                    <v-card v-show="codigomanual">
                        <v-layout row wrap>
                            <CodigoManual />
                        </v-layout>
                    </v-card>

                    <v-card v-show="salariominimo">
                        <v-layout>
                            <SalarioMinimo />
                        </v-layout>
                    </v-card>

                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import TipoManual from './TipoManual.vue'
    import CodigoManual from './CodigoManual.vue'
    import SalarioMinimo from './SalarioMinimo.vue'

    export default {
        name: 'CupTarifario',
        components: {
            TipoManual,
            CodigoManual,
            SalarioMinimo,
        },
        data() {
            return {
                tipomanual: false,
                salariominimo: false,
                codigomanual: false,
                importFile: false,
                permisos: [],
                search: '',
                headers: [{
                        text: 'Nombre',
                        align: 'right',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Valor',
                        align: 'right',
                        sortable: false,
                        value: 'Nit'
                    },
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: 'Nit'
                    },
                ],
                inputs: [{
                        label: 'Nombre',
                        model: 'Nombre'
                    },
                    {
                        label: 'Nit',
                        model: 'Nit'
                    },
                    {
                        label: 'Dirección',
                        model: 'Direccion'
                    },
                    {
                        label: 'Correo',
                        model: 'Correo1'
                    },
                    {
                        label: 'Correo (opcional)',
                        model: 'Correo2'
                    },
                    {
                        label: 'Teléfono',
                        model: 'Telefono1'
                    },
                    {
                        label: 'Teléfono (opcional)',
                        model: 'Telefono2'
                    },
                    {
                        label: 'Municipio',
                        model: 'Municipio_id',
                        items: 'municipios'
                    },
                    {
                        label: 'Código habilitacion',
                        model: 'Codigo_habilitacion'
                    },
                ],
                save: true,
                dialog: false,
                data: {
                    Nombre: '',
                    Nit: '',
                    Direccion: '',
                    Correo1: '',
                    Correo2: '',
                    Telefono1: '',
                    Telefono2: '',
                    Municipio_id: '',
                    Codigo_habilitacion: '',
                },
                municipios: [],
                prestadores: [],
                nameFile: '',
                file: ''
            }
        },
        mounted() {
            //this.fetchPrestadores();
            //this.fetchMunicipios();
        },
        methods: {
            fetchPrestadores() {
                axios.get('/api/prestador/prestadores')
                    .then((res) => this.prestadores = res.data)
                    .catch((err) => console.log(err));
            },
            createPrestador() {
                this.save = true;
                this.dialog = true;
                this.clearFields();
            },
            savePrestador() {
                axios.post('/api/prestador/create', this.data)
                    .then((res) => {
                        this.dialog = false;
                        this.fetchPrestadores();
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
                    .catch((err) => console.log(err))
            },
            editPrestador(prestador) {
                this.save = false;
                this.dialog = true;
                this.data = {
                    id: prestador.id,
                    Nombre: prestador.Nombre,
                    Nit: prestador.Nit,
                    Direccion: prestador.Direccion,
                    Correo1: prestador.Correo1,
                    Correo2: prestador.Correo2,
                    Telefono1: prestador.Telefono1,
                    Telefono2: prestador.Telefono2,
                    Municipio_id: prestador.Municipio_id,
                    Codigo_habilitacion: prestador.Codigo_habilitacion,
                };
            },
            updatePrestador() {
                axios.put(`/api/prestador/edit/${this.data.id}`, this.data)
                    .then((res) => {
                        this.fetchPrestadores();
                        this.clearFields();
                        this.dialog = false;
                        swal({
                            title: `¡${res.data.message}!`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
            },
            clearFields() {
                this.data = {
                    Nombre: '',
                    Nit: '',
                    Direccion: '',
                    Correo1: '',
                    Correo2: '',
                    Telefono1: '',
                    Telefono2: '',
                    Municipio_id: '',
                    Codigo_habilitacion: '',
                }
            },
            importPrestadores() {
                this.$refs.file.click()
            },
            onFilePicked(e) {
                const files = e.target.files
                this.nameFile = files[0].name
                this.file = files[0] // this is an image file that can be sent to server...
            },
            saveFile() {
                let formData = new FormData();
                formData.append('data', this.file);

                axios.post('/api/prestador/import', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((res) => {
                        this.importFile = false;
                        this.nameFile = '';
                        this.file = null;
                        swal({
                            title: `${res.data.message}`,
                            text: ' ',
                            type: "success",
                            timer: 3000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(() => console.log('FAILURE!!'));
            }
        }
    }

</script>

<style lang="scss" scoped>

</style>
