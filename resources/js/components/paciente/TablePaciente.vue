<template>
    <v-container pa-1>
        <v-dialog v-model="modal" width="500" persistent v-show="paciente">
            <v-card>
                <v-card-title class="headline red lighten-2 justify-center" style="color:white" primary-title>
                    Registrar Movimientos - {{ (paciente?paciente.id:"")}}
                </v-card-title>
                <form @submit.prevent="guardarNovedades">
                    <v-card-text style="color:black">
                        <v-container>
                            <v-layout row wrap>
                                <v-flex xs12 sm12 md12>
                                    <v-select label="Novedades" :items="novedades" v-model="novedad.tipoId" autocomplete
                                        item-text="Nombre" item-value="id"></v-select>
                                </v-flex>
                                <v-flex xs12 md5>
                                    <v-text-field prepend-icon="calendar_today" v-model="novedad.fecha"
                                        label="Fecha De Novedad" type="date" color="primary" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12>
                                    <v-textarea outline name="input-7-4" v-model="novedad.causa"
                                        label="Causa de la Novedad" required>
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12>
                                    <input id="novedades" ref="novedades" type="file" />
                                </v-flex>
                                <v-layout>
                                    <v-spacer></v-spacer>
                                    <v-card-actions>
                                        <v-btn color="success" type="submit">Guardar Novedad
                                        </v-btn>
                                    </v-card-actions>
                                </v-layout>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </form>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="modal = false">
                        cerrar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>



        <v-card-title>
            <v-btn v-if="can('paciente.create')" round color="primary" @click="createPaciente()" dark>
                <v-icon left>person_add</v-icon>Crear Paciente
            </v-btn>
            <v-spacer></v-spacer>
            <v-flex sm5 xs12>
                <v-text-field v-model="search" label="Ingresa el documento del paciente" single-line hide-details>
                </v-text-field>
            </v-flex>
            <v-form @submit.prevent="getPaciente">
                <v-btn outline v-if="can('paciente.show')" @click="getPaciente" @keyup.enter="getPaciente()" small fab
                    color="success">
                    <v-icon>search</v-icon>
                </v-btn>
            </v-form>
        </v-card-title>
        <v-flex shrink xs12 mr-1>
            <v-flex xs12>
                <v-card max-height="100%" class="mb-3">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Información General del Usuario</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>
                        <template>
                            <v-expansion-panel>
                                <v-expansion-panel-content v-for="(data, index) in datos" :key="index">
                                    <template v-slot:header>
                                        <v-btn v-if="data.Estado_Afiliado==1" color="success">
                                            <div>{{data.id}} - {{data.Ut}}</div>
                                        </v-btn>
                                        <v-btn v-if="data.Estado_Afiliado==27" color="error">
                                            <div>{{data.id}} - {{data.Ut}}</div>
                                        </v-btn>
                                        <v-btn v-if="data.Estado_Afiliado==28" color="info">
                                            <div>{{data.id}} - {{data.Ut}}</div>
                                        </v-btn>
                                        <v-btn v-if="data.Estado_Afiliado==29" color="info">
                                            <div>{{data.id}} - {{data.Ut}}</div>
                                        </v-btn>
                                    </template>
                                    <v-card>
                                        <v-card-text>
                                            <v-container grid-list-md fluid class="pa-0">
                                                <v-layout wrap align-center justify-center>
                                                    <v-flex xs12>
                                                        <v-layout row wrap>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Region" label="Region">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Ut" label="UT">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Primer_Nom"
                                                                    label="Primer Nombre"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.SegundoNom"
                                                                    label="Segundo Nombre"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Primer_Ape"
                                                                    label="Primer Apellido"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Segundo_Ape"
                                                                    label="Segundo Apellido"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-select :items="Tipo_Doc" v-model="data.Tipo_Doc"
                                                                    label="Tipo de Documento"></v-select>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Num_Doc"
                                                                    label="Numero de Documento" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-select :items="sexo" v-model="data.Sexo" label="Sexo">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Medicofamilia"
                                                                    label="Medico de Familia"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Fecha_Afiliado"
                                                                    label="Fecha de Afiliacion"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Fecha_Naci"
                                                                    label="Fecha de Nacimiento"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Edad_Cumplida"
                                                                    label="Edad Cumplida" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Discapacidad"
                                                                    label="Discapacidad" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Grado_Discapacidad"
                                                                    label="Grado de Discapacidad" readonly>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Tipo_Afiliado"
                                                                    label="Tipo de Afiliado" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Orden_Judicial"
                                                                    label="Orden Judicial" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Num_Folio"
                                                                    label="Numero de Folio" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Fecha_Emision"
                                                                    label="Fecha de Emision" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Parentesco"
                                                                    label="Parentesco"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.TipoDoc_Cotizante"
                                                                    label="Tipo de Doc. Cotizante"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Doc_Cotizante"
                                                                    label="Documento del Cotizante"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Tipo_Cotizante"
                                                                    label="Tipo de Cotizante"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-autocomplete :items="Estados" item-text="Nombre"
                                                                    item-value="estadoId" v-model="data.Estado_Afiliado"
                                                                    label="Estado de Afiliacion 1">
                                                                </v-autocomplete>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Dane_Mpio"
                                                                    label="Dane Municipio"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Mpio_Afiliado"
                                                                    label="Municipio"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Dane_Dpto"
                                                                    label="Dane Departamento"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Departamento"
                                                                    label="Departamento"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Subregion"
                                                                    label="SubRegion"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.departamentoAtencion"
                                                                    label="Departamento de Atencion"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.municipioAtencion"
                                                                    label="Municipio de Atencion"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-select :items="sede" v-model="data.sedeAtencion"
                                                                    label="Ips de Atencion" autocomplete
                                                                    item-text="Nombre" item-value="sedeAtencion">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Sede_Odontologica"
                                                                    label="Sede Odontologia"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md1>
                                                                <v-text-field v-model="data.Medicofamilia"
                                                                    label="ID Familiar"></v-text-field>
                                                            </v-flex>
                                                             <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Medico"
                                                                    label="Medico Familiar" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-select :items="Etnia" v-model="data.Etnia"
                                                                    label="Etnia"></v-select>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-select :items="Nivel_Sisben"
                                                                    v-model="data.Nivel_Sisben" label="Nivel de Sisben">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Laboraen" label="Labor"
                                                                    readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Mpio_Labora"
                                                                    label="Municipio Donde Labora" readonly>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Direccion_Residencia"
                                                                    label="Direccion de Residencia" readonly>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Mpio_Residencia"
                                                                    label="Municipio de Residencia"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Telefono" label="Telefono">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-select :items="Estrato" v-model="data.Estrato"
                                                                    label="Estrato"></v-select>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Celular1" label="Celular 1">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Celular2" label="Celular 2">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Correo1" label="Correo 1">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Correo2" label="Correo 2">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Sexo_Biologico"
                                                                    label="Sexo Biologico" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Tipo_Regimen"
                                                                    label="Tipo de Regimen" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Num_Hijos"
                                                                    label="N° de Hijos" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Vivecon"
                                                                    label="Con Quien Vive" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.RH" label="RH" readonly>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Tienetutela"
                                                                    label="¿Tiene Tutela?" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Nivelestudio"
                                                                    label="Nivel De Estudio" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Nombreacompanante"
                                                                    label="Nombre del Acompañante" readonly>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Telefonoacompanante"
                                                                    label="Telefono Del Acompañante" readonly>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Nombreresponsable"
                                                                    label="Nombre del Responsable" readonly>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Telefonoresponsable"
                                                                    label="Telefono del Responsable" readonly>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Aseguradora"
                                                                    label="Aseguradora" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Tipovinculacion"
                                                                    label="Tipo de Vinculacion" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.Ocupacion" label="Ocupacion"
                                                                    readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-select :items="Portabilidad"
                                                                    v-model="data.portabilidad" text="Nombre" value="id"
                                                                    label="Portabilidad"></v-select>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-select label="Entidad" :items="entidad"
                                                                    v-model="data.entidad_id" autocomplete
                                                                    item-text="nombre" item-value="entidad"></v-select>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="data.tipo_categoria"
                                                                    label="Tipo Categoria"></v-text-field>
                                                            </v-flex>
                                                            <v-flex>
                                                                <v-btn color="success" @click="abrilModal(data)">
                                                                    Actualizar Datos
                                                                </v-btn>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-flex>
                                                </v-layout>
                                                <v-spacer></v-spacer>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </template>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-flex>

    </v-container>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';

    export default {
        data() {
            return {
                sexo: ['M', 'F'],
                modal: false,
                search: '',
                datos: [],
                entidad: [],
                sede: [],
                tipo: '',
                paciente: null,
                novedades: [],
                Estados: [{
                        Nombre: 'Activo',
                        estadoId: '1'
                    },
                    {
                        Nombre: 'Retirado',
                        estadoId: '27'
                    },
                    {
                        Nombre: 'Proteccion Laboral Cotizante',
                        estadoId: '28'
                    },
                    {
                        Nombre: 'Proteccion Laboral  Beneficiario',
                        estadoId: '29'
                    }
                ],
                Tipo_Doc: ['CC', 'CE', 'PA', 'RC', 'TI'],
                Etnia: ['Indígena', 'Gitano', 'Raizal', 'Palenquero', 'Negro(a)', 'Mulato(a)', 'Afrocolombiano(a)',
                    'Afro descendiente'
                ],
                Nivel_Sisben: ['1', '2', '3', '4', '5', '6', '7', '8'],
                Estrato: ['1', '2', '3', '4', '5', '6', '7', '8'],
                Portabilidad: [{
                    text: 'SI',
                    value: '1'
                }, {
                    text: 'NO',
                    value: '0'
                }],
                novedadId: '',
                novedad: {
                    tipoId: null,
                    fecha: null,
                    fechaCreacion: null,
                    causa: null

                }
            }
        },
        computed: {
            ...mapGetters(['can'])
        },
        methods: {
            created() {
                Echo.channel('notificaciones')
                    .listen('NotificacionEvent', (e) => {
                        this.guardarNovedades();
                    });
            },
            createPaciente() {
                this.emptyData();
                this.edit = true;
                this.dialog = true;
                this.save = true;
            },
            registerPaciente() {
                axios.post('/api/paciente/create', this.data)
                    .then((res) => {
                        this.emptyData();
                        this.dialog = false;
                        swal({
                            title: "Paciente Creado!",
                            text: " ",
                            type: "success",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch((err) => this.showError(err.response.data));
            },
            showError(message) {
                swal({
                    title: "¡Paciente con este Número de Documento ya existe!",
                    text: `${message.email}`,
                    icon: "warning",
                });
            },
            updatePaciente() {
                axios.put(`/api/paciente/edit/${this.data.id}`, this.data)
                    .then(res => {
                        this.emptyData();
                        this.dialog = false;
                        swal({
                            title: "¡Usuario Actualizado!",
                            text: " ",
                            type: "success",
                            timer: 2000,
                            icon: "success",
                        });
                    })
                    .catch(err => console.log('err.response.data', err.response.data))
            },
            disablePaciente(paciente) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez el usuario esté deshabilitado, ya no podrá ingresar al sistema!",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.put(`/api/paciente/available/${paciente.id}`, {
                                estado_user: 2
                            })
                            .then(res => {
                                swal("¡Usuario Deshabilitado!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => console.log(err.response.data));
                    }
                })

            },
            getPaciente() {
                axios.get(`/api/paciente/buscarPaciente/${this.search}`)
                    .then(res => {
                        this.datos = res.data.Pacientes;
                        this.novedades = res.data.Novedades;
                        this.entidad = res.data.Entidades;
                        this.sede = res.data.Sedes
                        // this.Estados = res.data.Estados

                    })
                    .catch(err => console.log(err.response.data));
            },
            guardarNovedades() {
                this.preload = true;
                const data = {
                    novedad: this.novedad,
                    paciente: this.paciente
                }
                axios.post('/api/paciente/guardarNovedades', data)
                    .then(res => {
                        this.preload = false;
                        this.modal = false;
                        this.novedadId = res.data.Novedades.id
                        this.guardarAdjuntos(this.novedadId);
                        this.notificacionAlerta();
                    }).catch(error => {
                        this.preload = false;
                    });
            },
            guardarAdjuntos(novedadId) {
                this.preload = true;
                let formData = new FormData();
                formData.append(`novedades`, this.$refs.novedades.files[0]);

                axios.post('/api/paciente/guardarAdjuntos/' + novedadId, formData).then(res => {
                    this.preload = false;
                    this.$alerSuccess("Adjuntos guardados con exito!");

                });
            },
            abrilModal(item) {
                this.paciente = "";
                this.paciente = item;
                this.modal = true;
            },

            notificacionAlerta(){
                this.$alerSuccess('Se a actualizado el usuario en aseguramiento!');
                this.modal = false;
                this.search = '',
                this.datos = []
            }
        },
    }

</script>
<style lang="scss" scoped>

</style>
