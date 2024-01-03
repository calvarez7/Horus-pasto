<template>
    <v-container fluid pa-1 grid-list-md>
        <v-toolbar flat color="white">
            <v-toolbar-title>
                <v-container grid-list-md>
                    <v-layout row wrap>
                        <v-flex xs12>
                    <v-form @submit.prevent="search_paciente()">
                        <v-layout row wrap>
                            <v-flex xs10>
                                <v-text-field v-model="cedula_paciente" label="Número de Documento">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs2>
                                <v-btn @click="search_paciente()" @keyup.enter="search_paciente()" v-if="!paciente.id"
                                    fab outline small color="success">
                                    <v-icon>search</v-icon>
                                </v-btn>
                                <v-btn @click="clearFields()" v-if="paciente.id" fab outline small color="error">
                                    <v-icon>clear</v-icon>
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-form>
                </v-flex>
                    </v-layout>
                </v-container>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="1200px">
                <v-card>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout row wrap>
                                <v-flex sm12>
                                    <v-card max-height="100%" class="mb-3">
                                        <v-card-title class="headline success" style="color:white">
                                            <span class="title layout justify-center font-weight-light">Datos
                                                Paciente</span>
                                        </v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container grid-list-md fluid class="pa-0">
                                                <v-layout wrap>
                                                    <v-flex xs3>
                                                        <v-text-field v-model="paciente.Ut" readonly label="Entidad">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs5>
                                                        <v-text-field v-show="paciente.Ut == 'REDVITAL UT'"
                                                            v-model="paciente.NombreIPS" readonly label="IPS">
                                                        </v-text-field>
                                                        <v-text-field v-show="paciente.Ut == 'MEDIMAS'"
                                                            v-model="paciente.Mpio_Afiliado" readonly label="IPS">
                                                        </v-text-field>
                                                        <v-text-field v-show="paciente.Ut == 'FERROCARRILES NACIONALES'"
                                                            v-model="paciente.Mpio_Afiliado" readonly label="IPS">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-text-field v-model="paciente.tipo_categoria" readonly
                                                            label="Régimen">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-text-field v-model="paciente.Tipo_Afiliado" label="Tipo"
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs1>
                                                        <v-text-field v-model="paciente.nivel" readonly label="Nivel">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="paciente.Medicofamilia" readonly
                                                            label="Medico Familia">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3>
                                                        <v-text-field v-model="paciente.Primer_Nom" readonly
                                                            label="Nombre">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3>
                                                        <v-text-field v-model="paciente.Primer_Ape" readonly
                                                            label="Apellido">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs1>
                                                        <v-text-field v-model="paciente.Sexo" readonly label="Sexo">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs1>
                                                        <v-select :items="Tipo_Doc" label="Tipo Documento"
                                                            v-model="paciente.Tipo_Doc">
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-text-field v-model="paciente.Num_Doc" readonly
                                                            label="Número Documento">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs2 v-if="paciente.Fecha_Naci">
                                                        <v-text-field v-model="paciente.Fecha_Naci.split(' ')[0]"
                                                            readonly label="Fecha Nacimiento"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs1>
                                                        <v-text-field v-model="paciente.Edad_Cumplida" readonly
                                                            label="Edad">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-text-field v-model="paciente.Departamento" readonly
                                                            label="Departamento">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-text-field v-model="paciente.Mpio_Afiliado" readonly
                                                            label="Municipio">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-text-field v-model="paciente.Telefono" label="Telefono">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-select :items="estadoCivil" v-model="paciente.estado_civil"
                                                            label="Estado civil">
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-text-field v-model="paciente.Celular1" label="Celular" type="number" maxlength="10"
                                                        oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                        onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                        min="1">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-text-field v-model="paciente.Celular2"
                                                            label="Celular 2 (opcional)">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3>
                                                        <v-text-field v-model="paciente.Correo1" label="Correo">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3>
                                                        <v-text-field v-model="paciente.Correo2"
                                                            label="Correo 2 (opcional)">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-text-field v-model="paciente.Direccion_Residencia"
                                                            label="Direccion Residencia">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <!-- por revisar -->
                                                    <v-flex xs3>
                                                        <v-select :items="ut" chips label="ut saliente"
                                                            v-model="paciente.ut_saliente" required>
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-select :items="estrato" chips label="Número hijos"
                                                            v-model="paciente.numero_hijos" required>
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs3>
                                                        <v-select :items="escolaridad" label="Nivel escolaridad" chips
                                                            v-model="paciente.nivel_escolaridad" required>
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-select :items="estrato" chips label="estrato"
                                                            v-model="paciente.estrato" required>
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-autocomplete v-model="paciente.dx" append-icon="search"
                                                            :items="cieConcat" item-disabled="customDisabled"
                                                            item-text="nombre" item-value="id" chips
                                                            label="Diagnóstico principal">
                                                        </v-autocomplete>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-select :items="tServicios" label="tipo_servicio"
                                                            v-model="paciente.tipo_servicio" chips required>
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs4
                                                        v-show="paciente.tipo_servicio == 'Servicios' || paciente.tipo_servicio == 'Ambos'">
                                                        <v-autocomplete label="Servicios" v-model="servicios" multiple
                                                            :items="cups"
                                                            :item-text="servicios => servicios.Codigo+'-'+servicios.Nombre"
                                                            item-value="Codigo" prepend-icon="mdi-medical-bag" required>
                                                        </v-autocomplete>
                                                    </v-flex>
                                                    <v-flex xs4
                                                        v-show="paciente.tipo_servicio == 'Medicamentos' || paciente.tipo_servicio == 'Ambos'">
                                                        <vAutocomplete :items="codesumis" v-model="cums"
                                                            label="Molecula" chips multiple item-value="Codigo"
                                                            prepend-icon="mdi-pill" item-text="Nombre" />
                                                    </v-flex>
                                                    <v-flex xs4
                                                        v-show="paciente.tipo_servicio == 'Medicamentos' || paciente.tipo_servicio == 'Ambos'">
                                                        <v-autocomplete label="Código propios IPS" chips multiple
                                                            :items="codePropios"
                                                            :item-text="codePropios => codePropios.Codigo+'-'+codePropios.Descripcion"
                                                            item-value="Codigo" v-model="codePropio" required
                                                            no-data-text="No se encontró codigo propio con esa descripción">
                                                        </v-autocomplete>
                                                    </v-flex>
                                                    <v-flex xs3>
                                                        <v-text-field label="fecha_solicitud"
                                                            v-model="paciente.fecha_solicitud" type="date" required>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3>
                                                        <v-select :items="afirmacion" label="tutela"
                                                            v-model="paciente.tutela" required>
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs3>
                                                        <v-text-field label="anexos" v-model="paciente.anexos" required>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3>
                                                        <v-select :items="afirmacion" label="Acepta represa"
                                                            v-model="paciente.acepta_represa" required>
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs12 v-if="paciente.acepta_represa == 'No'">
                                                        <v-textarea v-model="paciente.justificacion_no_aceptacion"
                                                            name="input-7-1" outline
                                                            label="Justificacion de no aceptacion" auto-grow>
                                                        </v-textarea>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea v-model="paciente.observaciones_contratista"
                                                            name="input-7-1" outline
                                                            label="Observaciones de contratista" auto-grow></v-textarea>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <input id="adjunto" ref="adjunto" multiple type="file" />
                                                    </v-flex>
                                                    <v-flex xs3 class="mt-3">
                                                        <v-btn color="success"
                                                            @click="actualizarDatosPersonales(paciente)" dark>Guardar
                                                        </v-btn>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" @click="dialog=false">Cancel</v-btn>
                        <v-btn color="succes" @click="dialog=false">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-toolbar>
        <v-data-table :headers="headers" :items="allPaciente" class="elevation-1">
            <template v-slot:items="props">
                <td>{{ props.item.id }}</td>
                <td class="text-xs-left">{{ props.item.Num_Doc }}</td>
                <td class="text-xs-left">{{ props.item.Primer_Nom }} {{ props.item.SegundoNom }}
                    {{ props.item.Primer_Ape }} {{ props.item.Segundo_Ape }}</td>
                <td class="text-xs-left">{{ props.item.estado_civil }}</td>
                <td class="text-xs-left">{{ props.item.Ut }}</td>
                <td class="justify-center layout px-0">
                    <v-icon color="red" class="mr-2" @click="adjuntosPacientes(props.item.Num_Doc)">
                        mdi-file-pdf
                    </v-icon>
                </td>
            </template>
        </v-data-table>
    </v-container>

</template>

<script>
    import {
        mapGetters
    } from "vuex";
    import moment from "moment";
    import {
        EventBus
    } from "../../../event-bus.js";

    export default {
        components: {},
        data() {
            return {
                allPaciente: [],
                dialog: false,
                data: {},
                nameFile: '',
                file: '',
                adjuntos: [],
                codePropios: [],
                tipoI: ['CC', 'TI', 'CE', 'RC', 'PASAPORTE'],
                ut: ['FMP Antioquia', 'FMP Cesar', 'FMP Santander', 'FMP Norte de Santander'],
                genero: ['Femenino', 'Masculino', 'Intermedio'],
                tipoAfiliado: ['Cotizante', 'Beneficiario'],
                estadoCivil: ['Soltero', 'Casado', 'Viudo', 'Union Libre ', 'Separado'],
                escolaridad: ['Primaria', 'Bachiller', 'Tecnologo', 'Tecnico', 'Universitario', 'Pos grado'],
                estrato: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
                afirmacion: [0, 1],
                tServicios: ['Servicios', 'Medicamentos', 'Ambos'],
                Tipo_Doc: ['CC', 'CE', 'PA', 'RC', 'TI'],
                ambitos: [
                    'Ambulatorio',
                    'Hospitalario',
                    'Urgencias',
                ],
                cedula_paciente: "",
                object: {},
                citaPaciente: "",
                // paciente: {
                //     Primer_Nom: "",
                //     Primer_Ape: "",
                //     Tipo_Doc: "",
                //     Num_Doc: "",
                //     entidad_id: 0
                // },
                paciente: {
                    Telefono: "",
                    Celular1: "",
                    Celular2: "",
                    Correo1: "",
                    Correo2: "",
                    Edad_Cumplida: "",
                    Direccion_Residencia: "",
                    estado_civil: "",
                    Tipo_Doc: "",
                    ut_saliente: '',
                    tipo_identificacion: '',
                    documento_identificacion: '',
                    primer_nombre: '',
                    segundo_nombre: '',
                    primer_apellido: '',
                    segundo_apellido: '',
                    genero: '',
                    fecha_nacimiento: '',
                    edad: '',
                    tipo_afiliacion: '',
                    estado_civil: '',
                    numero_hijos: '',
                    nivel_escolaridad: '',
                    estrato: '',
                    municipio_id: '',
                    direccion: '',
                    telefono: '',
                    celular: '',
                    dx: '',
                    servicios: [],
                    cums: [],
                    correo_electronico: '',
                    fecha_solicitud: '',
                    tipo_servicio: '',
                    tutela: '',
                    anexos: '',
                    observaciones_contratista: '',
                    acepta_represa: '',
                    justificacion_no_aceptacion: '',
                    Codepropio: []
                },
                cie10s: [],
                cups: [],
                cums: [],
                codePropio: [],
                cie10Array: [],
                tipo_citas: [],
                cup_search: '',
                Prestador_id: '',
                prestadores: [],
                municipios: [],
                codesumis: [],
                servicios: [],
                adjuntos: [],
                paciente_id: '',
                anexos: [],
                headers: [{
                        text: 'id',
                        align: 'left',
                        sortable: false,
                        value: 'id'
                    },
                    {
                        text: 'Cédula',
                        align: 'left',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                    },
                    {
                        text: 'Estado civil',
                        align: 'left',
                        value: 'carbs'
                    },
                    {
                        text: 'Entidad',
                        align: 'left',
                        value: 'protein'
                    },
                    {
                        text: 'Actions',
                        align: 'left',
                        value: 'name',
                        sortable: false
                    }
                ],

            };
        },
        computed: {
            ...mapGetters(['can']),
            fullnameprestador() {
                return this.prestadores.map((prestador) => {
                    return {
                        id: prestador.id,
                        fullname: `${prestador.Nit} - ${prestador.Nombre}`
                    }
                })
            },
            cieConcat() {
                return this.cie10Array = this.cie10s.map(cie => {
                    return {
                        id: cie.id,
                        codigo: cie.Codigo_CIE10,
                        nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                        customDisabled: false
                    };
                });
            },
        },
        mounted() {
            this.fetchCie10s();
            this.fetchCups();
            this.fetchCodesumis();
            this.fetchAllCodePropios();
            this.fechsPacientes();
        },
        watch: {
            cup_search() {
                if (this.cup_search && this.cup_search.length === 3) {
                    this.search_cup()
                }
            }
        },
        methods: {
            fetchAllCodePropios() {
                axios.post('/api/codepropio/all')
                    .then((res) => {
                        this.codePropios = res.data
                    })
                    .catch((err) => console.log(err));
            },
            fetchCodesumis() {
                axios.get('/api/codesumi/all')
                    .then(res => {
                        this.codesumis = res.data;
                    })
            },
            fetchMunicipios() {
                axios.get('/api/municipio/lista')
                    .then(res => {
                        this.municipios = res.data
                    })
            },
            search_paciente() {
                if (!this.cedula_paciente) return;
                axios
                    .get(`/api/paciente/showEnabledferrocarril/${this.cedula_paciente}`)
                    .then(res => {
                        // console.log('lleve pes', res.data.paciente);
                        if (res.data.paciente) {
                            this.dialog = true;
                            this.paciente = res.data.paciente;
                            let arrCup = res.data.paciente.cups.slice(1, -1)
                            this.servicios = arrCup.replace(/"/gi, '').split(',')

                        } else {
                            this.showMessage(res.data.message);
                        }
                    });
            },
            showMessage(message){
                swal({
                    title: `${message}`,
                    icon: "warning",
                });
            },
            fetchCups() {
                axios.get('/api/cups/all')
                    .then(res => {
                        this.cups = res.data;
                    })
            },
            importPrestadores() {
                this.$refs.file.click()
            },
            onFilePicked(e) {
                const files = e.target.files
                this.nameFile = files[0].name
                this.file = files[0]
            },
            actualizarDatosPersonales() {
                this.updatePaciente();
                this.dialog=false;
                this.data.adjunto = this.$refs.adjunto.files
                let formData = new FormData();
                for (let i = 0; i < this.data.adjunto.length; i++) {
                    formData.append(`adjunto[]`, this.data.adjunto[i])
                }
                for (let i = 0; i < this.servicios.length; i++) {
                    formData.append(`cups[]`, this.servicios[i])
                }
                for (let i = 0; i < this.cums.length; i++) {
                    formData.append(`cums[]`, this.cums[i])
                }
                for (let i = 0; i < this.codePropio.length; i++) {
                    formData.append(`codes[]`, this.codePropio[i])
                }
                axios.post(`/api/paciente/prueba/${this.paciente.id}`, formData).then(res => {
                        this.$alerSuccess("empalme generado con exito!");
                        this.clearFields();
                    })
                    .catch(() => console.log('Error'));
            },
            updatePaciente() {
                axios.put(`/api/paciente/prueba2/${this.paciente.id}`, this.paciente).then(res => {})
                    .catch(() => console.log('Error'));
            },
            fetchGestion() {
                axios.get('/api/tipocita/all')
                    .then(res => this.tipo_citas = res.data.filter(f => f.id !== 7))
                    .catch(err => console.log(err.response))
            },
            clearFields() {
                this.cedula_paciente = "";
                for (const prop of Object.getOwnPropertyNames(this.paciente)) {
                    this.paciente[prop] = null;
                    this.servicios = [];
                    this.cums = [];
                    this.codePropio = [];
                }
            },
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },
            setDiagnostico() {

                let data = {
                    Cie10_id: this.data.Cie10_id
                }

                axios.post(`/api/cie10/setDiagnostic/${this.citaPaciente.id}`, data).then(res => {});

            },
            setCie10() {
                let object = this.cie10s.find(cie => cie.id == this.data.Cie10_id);
                this.paciente.Cie10_id = object.Codigo_CIE10;
            },
            fechMedicos() {
                axios.get('/api/user/medicoSumi')
                    .then((res) => {
                        this.medicoSumi = res.data
                    })
                    .catch((err) => console.log(err));
            },
            fechsPacientes() {
                axios.get('/api/paciente/pacientesEmpalme')
                    .then((res) => {
                        this.allPaciente = res.data
                    })
                    .catch((err) => console.log(err));
            },
            adjuntosPacientes(cedula) {
                axios.get(`/api/paciente/adjuntosPaciente/${cedula}`)
                    .then((res) => {
                            this.paciente_id = res.data.id
                            let arrAdjuntos = res.data.anexo.slice(1, -1)
                            this.adjuntos = arrAdjuntos.replace(/"/gi, '').split(',')
                            let baseUrl = '/storage/adjuntosempalme'
                            for (let i = 0; i < this.adjuntos.length; i++) {
                                window.open(baseUrl+'/'+this.paciente_id+'/'+this.adjuntos[i], '_blank');
                            }
                    })
                    .catch((err) => console.log(err));
            },
        }
    };

</script>

<style lang="scss" scoped>
</style>
