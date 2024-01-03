<template>
    <div>
        <v-flex sm12 md12>
            <v-card max-height="100%" class="mb-3">
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Buscar paciente</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-md fluid class="pa-0">
                        <v-layout wrap align-center justify-center>
                            <v-flex xs12 sm12 md10>
                                <v-form @submit.prevent="search_paciente()">
                                    <v-layout row wrap>
                                        <v-flex xs8>
                                            <v-text-field v-model="cedula_paciente" label="Número de Documento">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs2>
                                            <v-btn @click="search_paciente()" @keyup.enter="search_paciente()"
                                                v-if="!dialogConcurrencia" fab outline small color="success">
                                                <v-icon>search</v-icon>
                                            </v-btn>
                                            <v-btn @click="clearFields()" v-if="dialogConcurrencia" fab outline small
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
            </v-card>
        </v-flex>

        <v-card v-if="dialogConcurrencia">
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Registrar
                    concurrencia</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md fluid>
                    <v-layout wrap>
                        <v-flex xs12 sm6 md4>
                            <v-text-field label="Entidad" v-model="datosReferencia.Ut" outline readonly>
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field label="IPS del paciente" v-model="datosReferencia.NombreIPS" outline readonly>
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field label="Medico Familiar" v-model="datosReferencia.medico_familia" outline
                                readonly>
                            </v-text-field>
                        </v-flex>

                        <v-flex xs12 sm6 md2>
                            <v-text-field label="Tipo identificacion" v-model="datosReferencia.Tipo_Doc" outline
                                readonly>
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-text-field label="Identificacion" v-model="datosReferencia.Num_Doc" outline readonly>
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md7>
                            <v-text-field label="Nombre completo"
                                v-model="datosReferencia.Primer_Nom+' '+datosReferencia.SegundoNom+' '+datosReferencia.Primer_Ape+' '+datosReferencia.Segundo_Ape"
                                readonly outline>
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md1>
                            <v-text-field label="Años" v-model="datosReferencia.Edad_Cumplida" readonly outline>
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-text-field label="Fecha ingreso" v-model="concurrencia.fecha_ingreso" @change="total"
                                type="date" required>
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-autocomplete label="Diagnostico de ingreso" :items="cieConcat"
                                item-disabled="customDisabled" append-icon="search" item-text="nombre" item-value="id"
                                v-model="concurrencia.cie10_ingreso" required>
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-autocomplete label="Tipo Hospitalización"
                                :items="['Hospitalización médica','Hospitalización quirúrgica']"
                                v-model="concurrencia.tipo_hospitalizacion" required>
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-autocomplete
                                :items="['Hospitalización obstetrica','Hospitalización','Intermedio Adulto','Intermedio Neonatal','Intermedio Pediatría','UCI Adulto','UCI Neonatal','UCI Pediatría']"
                                label="Unidad funcional" v-model="concurrencia.unidad_funcional" required>
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-select
                                :items="['Consulta Externa','Medicina Domiciliaria','Prioritaria','Programado','Referencia','Urgencias','Traslado no regulado']"
                                label="Via de ingreso" v-model="concurrencia.via_ingreso" required>
                            </v-select>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-autocomplete
                                :items="reIngreso15"
                                label="Reingreso hospitalización 15 dias, por el mismo diagnóstico egreso" v-model="concurrencia.reingreso_hospitalización15días"
                                required>
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-autocomplete
                                :items="reIngreso30"
                                label="Reingreso hospitalización 30 dias, por el mismo diagnóstico egreso" v-model="concurrencia.reingreso_hospitalización30días"
                                required>
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-autocomplete v-model="concurrencia.prestador_id" append-icon="search"
                                :items="proveedores" item-text="Nombre" hide-details
                                label="Institución de atención">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-text-field label="Cama/Piso" v-model="concurrencia.cama">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-text-field label="Codigo Hospitalización" v-model="concurrencia.codigo_hoispitalizacion">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-text-field label="Estancia total días" v-model="concurrencia.estancia_total_dias"
                                readonly>
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-select :items="['Anestesiología','Cardiología','Cirugía bariátrica','Cirugía cardiovascular','Cirugía de cabeza y cuello','Cirugía de tórax','Cirugía general','Cirugía hepatobiliar','Cirugía infantil','Cirugía maxilofacial','Cirugía oncológica','Cirugía plástica','Cirugía vascular','Coloproctología','Cuidado critico','Dermatología','Endocrinología','Fisiatría','Gastroenterología','Genética','Ginecología','Hematología','Hematoncología','Hemodinamia','Hepatología','Infectología','Inmunología','Medicina del dolor',
                                'Medicina general','Medicina Interna','Medicina nuclear','Nefrología','Neumología','Neurocirugía','Neurología','Obstetricia','Oftalmología','Oncología','Ortopedia','Otorrinolaringología','Pediatría','Psiquiatría','Radiología','Radioterapia','Reumatología','Toxicología','Urgentología','Urología','Electrofisiología',
                                ]" label="Especialidad tratante" v-model="concurrencia.especialidad_tratante">
                            </v-select>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-text-field label="Peso Neonato" v-model="concurrencia.peso_rn" type="number" min="0">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-text-field label="Edad gestacional
                            " v-model="concurrencia.edad_gestacional"
                                type="number" min="0">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea solo name="input-7-4" label="Nota de Seguimiento"
                                v-model="concurrencia.nota_concurrencia"></v-textarea>
                        </v-flex>
                        <v-flex v-if="concurrenciaRegistrada" xs12 md12 text-xs-center>
                            <v-btn color="success" dark @click="editarRegistrarCocurrencia()">Editar
                            </v-btn>
                        </v-flex>
                        <v-flex v-else xs12 md12 text-xs-center>
                            <v-btn color="success" dark @click="registrarCocurrencia()">Guardar
                            </v-btn>
                        </v-flex>
                    </v-layout>
                    <v-card-title v-if="concurrenciaRegistrada" class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Ordenamiento</span>
                    </v-card-title>
                    <v-layout wrap v-if="concurrenciaRegistrada">
                        <v-flex xs12 sm6 md8>
                            <v-autocomplete v-model="costos.cup_id" label="Seleccione el cups"
                                :item-text="cups => cups.Codigo + ' - ' + cups.Nombre" item-value="id"
                                :items="cups"></v-autocomplete>
                        </v-flex>
                        <v-flex  xs12 sm6 md2>
                            <v-text-field v-model="costos.cantidad" type="number" label="Cantidad">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md2>
                            <v-text-field v-model="costos.precio" type="number" label="Costo"></v-text-field>
                        </v-flex>
                        <v-flex xs12 md12 text-xs-center>
                            <v-btn color="primary" @click="agregarCosto()">Agregar</v-btn>
                        </v-flex>
                    </v-layout>
                    <v-card v-if="concurrenciaRegistrada">
                        <v-card-title>
                            Costo
                            <v-spacer></v-spacer>
                            <span>Total: <b>{{ totalCosto }}</b></span>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line
                                hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headersCosto" :items="costo" no-data-text="Sin datos para mostrar"
                            class="elevation-1">
                            <template v-slot:items="props">
                                <td>{{ props.item.id }}</td>
                                <td>{{ props.item.Codigo }}</td>
                                <td>{{ props.item.servicios }}</td>
                                <td>{{ props.item.cantidad }}</td>
                                <td><b>{{ props.item.valor }}</b></td>
                                <td>{{ props.item.created_at }}</td>
                                <td>{{ props.item.usuario }}</td>
                            </template>
                        </v-data-table>
                    </v-card>
                    <v-flex xs12 md12 text-xs-center v-if="concurrenciaRegistrada">
                        <br />
                        <span><b>Estancia Hospitalizacion:</b> {{ cantidadHospi }} </span><br />
                        <span><b>Estancia UCE:</b> {{ cantidadUce }} </span><br />
                        <span><b>Estancia UCI:</b> {{ cantidadUci }} </span><br />
                        <span><b>Estancia Total:</b> {{ cantidadTotal}} </span>
                    </v-flex>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="red" dark @click="dialogConcurrencia = false,clearFields()">Cerrar</v-btn>
            </v-card-actions>
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
        </v-card>
    </div>
</template>

<script>
    import moment from 'moment';
    import {
        mapGetters
    } from 'vuex';
    moment.locale('es');

    export default {
        name: 'ConcurrenciaRegistro',
        data: () => {
            return {
                cie10s: [],
                preload: false,
                datosReferencia: [],
                cie10sReferencia: {
                    codigo: '',
                    descripcion: ''
                },
                proveedores: [],
                concurrencia: {
                    fecha_ingreso : null,
                    cie10_ingreso : null,
                    tipo_hospitalizacion : null,
                    unidad_funcional : null,
                    via_ingreso : null,
                    reingreso_hospitalización15días : null,
                    reingreso_hospitalización30días: null,
                    prestador_id : null,
                    codigo_hoispitalizacion : null,
                    estancia_total_dias : null,
                    especialidad_tratante : null,
                    peso_rn : null,
                    edad_gestacional : null,
                    nota_concurrencia : null,
                    cama: null,
                },
                dialogConcurrencia: false,
                data: {
                    Tipo_anexo: null,
                    cie10s: [],
                },
                cedula_paciente: '',
                search: '',
                showReferenceDialog: false,
                cups: [],
                costos: {
                    cup_id: '',
                    cantidad: '',
                    precio: ''
                },
                costo:[],
                concurrenciaRegistrada: null,
                cantidadHospi: '',
                cantidadUce: '',
                cantidadUci: '',
                cantidadTotal: '',
                totalCosto: '',
                reIngreso15:[
                    'No es un reingreso a hospitalización por la misma causa antes de 15 días',
                    'Altas tempranas no pertinentes.',
                    'Altas voluntarias.',
                    'Complicación posterior a la realización de procedimiento.',
                    'Deterioro del estado clínico.',
                    'Disfuncionalidad de catéteres o sondas (patologías crónicas)',
                    'Eventos relacionados con uso de medicamentos prescritos',
                    'Falta oportunidad en procedimiento ambulatorios posterior al alta (diferidas).',
                    'Infección de sitio operatorio – ISO.',
                    'No adherencia al tratamiento por parte del paciente.',
                    'No dispensación medicamento posterior al alta.',
                    'No ingreso a programas ni captación de gestión de riesgo posterior al alta.',
                ],
                reIngreso30:[
                    'No es un reingreso a hospitalización por la misma causa antes de 30 días',
                    'Altas tempranas no pertinentes.',
                    'Altas voluntarias.',
                    'Complicación posterior a la realización de procedimiento.',
                    'Deterioro del estado clínico.',
                    'Disfuncionalidad de catéteres o sondas (patologías crónicas)',
                    'Eventos relacionados con uso de medicamentos prescritos',
                    'Falta oportunidad en procedimiento ambulatorios posterior al alta (diferidas).',
                    'Infección de sitio operatorio – ISO.',
                    'No adherencia al tratamiento por parte del paciente.',
                    'No dispensación medicamento posterior al alta.',
                    'No ingreso a programas ni captación de gestión de riesgo posterior al alta.',
                    ],
                headersCosto: [{
                        text: 'id',
                        value: 'id'
                    }, {
                        text: 'Codigo',
                        value: 'Codigo'
                    },
                    {
                        text: 'Servicio',
                        value: 'Nombre'
                    },
                    {
                        text: 'Cantidad',
                        value: 'Cantidad'
                    },
                    {
                        text: 'Costo',
                        value: 'valor'
                    },
                    {
                        text: 'Fecha',
                        value: 'created_at'
                    },
                    {
                        text: 'Usuario',
                        value: 'usuario'
                    }
                ],
            }
        },
        computed: {
            ...mapGetters(['can']),
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
        filters: {
            fullnamePaciente: (item) => {
                return `${item.Primer_Nom || ''} ${item.SegundoNom || ''} ${item.Primer_Ape || ''} ${item.Segundo_Ape || ''}`
            },
            fullnamePrestador: (item) => {
                return `${item.name || ''} ${item.apellido || ''}`
            },
            fullcie10s: (item) => {
                let str = '';
                item.cie10s.forEach(cie => str = str.concat(`${cie.Codigo_CIE10} - ${cie.Descripcion}, `));

                if (str) str = str.slice(0, -2);

                return str;
            },
            date: (date) => {
                return moment(date).format('lll')
            }
        },

        mounted() {
            this.sedeProveedores();
            this.fetchCie10s();
            this.fetchCups();
        },

        methods: {
            fetchCups() {
                axios.get('/api/cups/all')
                    .then(res => this.cups = res.data);
            },
            search_paciente() {
                this.preload = true
                if (!this.cedula_paciente) {
                    swal({
                        title: "Debe ingresar un cédula",
                        icon: "warning"
                    });
                    return;
                }

                axios.get(`/api/paciente/pacienteConcurrencia/${this.cedula_paciente}`)
                    .then((res) => {
                        this.datosReferencia = res.data.paciente;
                        this.dialogConcurrencia = true
                        this.preload = false;
                        if (res.data.message) this.showMessage(res.data.message);
                    });
            },

            showMessage(message) {
                swal({
                    title: `${message}`,
                    icon: "warning",
                });
            },

            total() {
                let hoy = new moment();
                let fecha_ingreso = moment(this.concurrencia.fecha_ingreso);
                let p = hoy.diff(fecha_ingreso, 'days')
                return this.concurrencia.estancia_total_dias = p
            },

            verCosto() {
                this.preload = true
                axios.post('/api/referencia/costoConcurrencia/' + this.concurrenciaRegistrada.id)
                    .then(res => {
                        this.preload = false
                        this.costo = res.data.datos;
                        this.totalCosto = res.data.total_suma.total_suma;
                        if(res.data.hospitalizacion[0].Hospitalizacion == null){
                            this.cantidadHospi = 0;
                        }else{
                            this.cantidadHospi = res.data.hospitalizacion[0].Hospitalizacion;
                        }
                        if(res.data.UCE[0].UCE == null){
                            this.cantidadUce = 0;
                        }else{
                            this.cantidadUce = res.data.UCE[0].UCE;
                        }
                        if(res.data.UCI[0].UCI == null){
                            this.cantidadUci= 0;
                        }else{
                            this.cantidadUci = res.data.UCI[0].UCI;
                        }
                        this.cantidadTotal =  parseInt(this.cantidadHospi) + parseInt(this.cantidadUce) + parseInt(this.cantidadUci)
                    })
                    .catch(e => {
                        this.preload = false
                        this.$alerError('Error')
                    })
            },

            agregarCosto() {
                this.preload = true
                axios.post('/api/referencia/guardarCosto/' + this.concurrenciaRegistrada.id, this.costos).then(res => {
                    this.verCosto();
                    this.preload = false
                    this.costos.cup_id = '';
                    this.costo.cantidad = '';
                    this.costo.precio = '';
                    this.$alerSuccess('Agregado correctamente');
                }).catch(e => {
                    this.preload = false
                    this.$alerError('Error')
                })
            },

            registrarCocurrencia() {
                this.preload = true
                if (this.concurrencia.fecha_ingreso == null) {
                    swal({
                        title: "Por favor ingrese la fecha de ingreso!",
                        icon: "error",
                        buttons: true
                    });
                    return this.preload = false;
                }
                if (this.concurrencia.cie10_ingreso == null) {
                    swal({
                        title: "Por favor ingrese el diagnostico de ingreso!",
                        icon: "error",
                        buttons: true
                    });
                    return this.preload = false;
                }
                if (this.concurrencia.tipo_hospitalizacion == null) {
                    swal({
                        title: "Por favor ingrese el tipo de hospitalización!",
                        icon: "error",
                        buttons: true
                    });
                    return this.preload = false;
                }
                if (this.concurrencia.unidad_funcional == null) {
                    swal({
                        title: "Por favor ingrese la unidad funcional!",
                        icon: "error",
                        buttons: true
                    });
                    return this.preload = false;
                }
                if (this.concurrencia.via_ingreso == null) {
                    swal({
                        title: "Por favor ingrese via de ingreso!",
                        icon: "error",
                        buttons: true
                    });
                    return this.preload = false;
                }
                if (this.concurrencia.reingreso_hospitalización15días == null) {
                    swal({
                        title: "Por favor ingrese reingreso hospitalización 15 dias!",
                        icon: "error",
                        buttons: true
                    });
                    return this.preload = false;
                }
                if (this.concurrencia.reingreso_hospitalización30días == null) {
                    swal({
                        title: "Por favor ingrese reingreso hospitalización 30 dias!",
                        icon: "error",
                        buttons: true
                    });
                    return this.preload = false;
                }
                if (this.concurrencia.prestador_id == null) {
                    swal({
                        title: "Por favor ingrese la insitución de atención!",
                        icon: "error",
                        buttons: true
                    });
                    return this.preload = false;
                }
                if (this.concurrencia.especialidad_tratante == null) {
                    swal({
                        title: "Por favor ingrese la especialidad tratante",
                        icon: "error",
                        buttons: true
                    });
                    return this.preload = false;
                }
                if (this.concurrencia.nota_concurrencia == null) {
                    swal({
                        title: "Por favor ingrese la nota de seguimiento",
                        icon: "error",
                        buttons: true
                    });
                    return this.preload = false;
                }
                axios.post('/api/referencia/crearCocurrencia/'+ this.datosReferencia.id, this.concurrencia).then(res => {
                    this.concurrenciaRegistrada = res.data.data
                    this.preload = false
                    this.$alerSuccess('Guardado correctamente');
                }).catch( err =>{
                    this.preload = false
                    this.$alerError('Error al guardar');
                });
            },

            editarRegistrarCocurrencia(){
                this.preload = true
                axios.post('/api/referencia/editarRegistrarCocurrencia/' + this.concurrenciaRegistrada.id, this.concurrencia).then(res => {
                    this.concurrenciaRegistrada = res.data.data
                    this.preload = false
                    this.$alerSuccess('Se actualizo correctamente!');
                }).catch(e => {
                    this.preload = false
                    this.$alerError('Error')
                })
            },

            clearConcurrencia() {
                for (const prop of Object.getOwnPropertyNames(this.concurrencia)) {
                    this.concurrencia[prop] = "";
                }

            },
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },

            concurrenciaRegistro(referencia) {
                this.clearConcurrencia();
                this.datosReferencia = referencia;
                this.cie10sReferencia.codigo = referencia.cie10s[0].Codigo_CIE10;
                this.cie10sReferencia.descripcion = referencia.cie10s[0].Descripcion;
                this.dialogConcurrencia = true;
                this.concurrencia.paciente_id = referencia.paciente_id;
                this.concurrencia.ips_basica = referencia.ips;
                this.concurrencia.id_concurrencia = referencia.id;
                this.concurrencia.ips_atencion = this.datosReferencia.name;
            },

            sedeProveedores() {
                axios.get('/api/sedeproveedore/sedeproveedores')
                    .then((res) => {
                        this.proveedores = res.data
                    })
                    .catch((err) => console.log(err));
            },

            clearFields(){
                 this.clearConcurrencia();
                 this.cedula_paciente = null;
                 this.dialogConcurrencia = false;
            }
        },


    }

</script>

<style lang="scss" scoped>

</style>
