<template>
    <div>
        <v-card>
            <v-card-title>
                <v-toolbar flat color="white">
                    <v-toolbar-title>AFILIACIONES PORTABILIDAD SALIDA</v-toolbar-title>
                    <v-divider class="mx-2" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                        <template v-slot:activator="{ on }">
                            <v-btn color="primary" dark v-on="on">Nueva Portabilidad</v-btn>
                        </template>
                        <v-card>
                            <v-toolbar dark color="primary">
                                <v-btn icon dark @click="dialog = false, clearCampos()">
                                    <v-icon>close</v-icon>
                                </v-btn>
                                <v-toolbar-title>Portabilidad</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-items>
                                    <v-btn dark flat @click="validacionPaciente()">Guardar</v-btn>
                                </v-toolbar-items>
                            </v-toolbar>
                            <v-form>
                                <v-container fluid>
                                    <v-card-title class="headline success" style="color:white">
                                        <span class="title layout justify-center font-weight-light">Datos
                                            Usuarios</span>
                                    </v-card-title>
                                    <v-card-title>
                                        <v-spacer></v-spacer>
                                        <v-flex sm5 xs12>
                                            <v-text-field v-model="searchPaciente"
                                                label="Ingresa el documento del paciente" single-line hide-details>
                                            </v-text-field>
                                        </v-flex>
                                        <v-form @submit.prevent="getPaciente">
                                            <v-btn outline @click="getPaciente" @keyup.enter="getPaciente()" small fab
                                                color="success">
                                                <v-icon>search</v-icon>
                                            </v-btn>
                                        </v-form>
                                    </v-card-title>
                                    <v-layout wrap>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Num_Doc" label="Numero de Documento"
                                                readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Tipo_Doc" label="Tipo de Documento"
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Region" readonly label="Region">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Ut" readonly label="UT"></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Primer_Nom" readonly
                                                label="Primer Nombre">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.SegundoNom" readonly
                                                label="Segundo Nombre">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Primer_Ape" readonly
                                                label="Primer Apellido">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Segundo_Ape" readonly
                                                label="Segundo Apellido">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Sexo" readonly label="Sexo">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Tipo_Afiliado" readonly
                                                label="Tipo de Afiliado">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.sedeAtencionNombre" readonly
                                                label="Ips Primaria">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Fecha_Naci" readonly
                                                label="Fecha de Nacimiento">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Fecha_Afiliado" readonly
                                                label="Fecha de Afiliacion">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Parentesco" readonly label="Parentesco">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.TipoDoc_Cotizante" readonly
                                                label="Tipo de Doc. Cotizante">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Doc_Cotizante" readonly
                                                label="Documento del Cotizante">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.Tipo_Cotizante" readonly
                                                label="Tipo de Cotizante">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field v-model="portabilidad.estadoNombre" readonly
                                                label="Estado de Afiliacion">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field readonly v-model="portabilidad.Discapacidad"
                                                label="Discapacidad">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-text-field readonly v-model="portabilidad.Grado_Discapacidad"
                                                label="Grado de Discapacidad">
                                            </v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-card-title class="headline success" style="color:white">
                                        <span class="title layout justify-center font-weight-light">Datos
                                            Portabilidad
                                        </span>
                                    </v-card-title>
                                    <v-layout wrap>
                                        <v-flex xs12 md2>
                                            <v-autocomplete :items="departamentos" item-text="nombre" item-value="id"
                                                v-model="portabilidad.departamentoReceptor"
                                                label="Departamento Receptora">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-autocomplete append-icon="search" :items="municipio"
                                                item-text="municipio" item-value="id"
                                                v-model="portabilidad.municipio_receptor" label="Municipio Receptor">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-autocomplete :items="utPortabilidad" item-text="nombre" item-value="id"
                                                v-model="portabilidad.entidadReceptora" label="Entidad Receptora">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 md2>
                                            <v-text-field type="date" v-model="portabilidad.fechaInicio_portabilidad"
                                                label="Fecha inicial Portabilidad">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md2>
                                            <v-text-field type="date" v-model="portabilidad.fechaFinal_portabilidad"
                                                label="Fecha Final Portabilidad">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md3>
                                            <v-autocomplete :items="motivos" item-text="nombre" item-value="id"
                                                v-model="portabilidad.causa" label="Motivo">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 md12 v-show="portabilidad.motivo == 5">
                                            <v-textarea v-model="portabilidad.otra_causa" label="Cual">
                                            </v-textarea>
                                        </v-flex>
                                    </v-layout>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-flex xs12>
                                            <label for="name">Carta de Portabilidad:</label>
                                            <input id="adjunto" ref="adjunto" multiple type="file" />
                                        </v-flex>
                                    </v-card-actions>
                                </v-container>
                            </v-form>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </v-card-title>
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Datos del paciente</span>
            </v-card-title>
            <v-card>
                <v-card-title>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                    </v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :key="headers.id" :items="pacientes" :search="search"
                    class="elevation-1">
                    <template v-slot:items="props">
                        <td class="text-xs-left">{{ props.item.id }}</td>
                        <td class="text-xs-left">{{ props.item.Tipo_Doc }}</td>
                        <td class="text-xs-left">{{ props.item.Num_Doc }}</td>
                        <td class="text-xs-left">{{ props.item.Nombre }}</td>
                        <td class="text-xs-left">{{ props.item.Ut }}</td>
                        <td class="text-xs-left">{{ props.item.fechaInicio_portabilidad }}</td>
                        <td class="text-xs-left">{{ props.item.fechaFinal_portabilidad }}</td>
                        <td class="text-xs-left">{{ props.item.ipsdestino_portabilidad }}</td>
                        <td>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn small text icon color="info" dark v-on="on">
                                        <v-icon @click="modal=true,detallepaciente(props.item.id)">visibility
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Ver Mas</span>
                            </v-tooltip>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn small text icon color="error" dark v-on="on">
                                        <v-icon @click="inactivarPortabilidad(props.item.novedadId)">person_add_disabled
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Inactivar</span>
                            </v-tooltip>
                        </td>
                    </template>
                </v-data-table>
            </v-card>
            <v-dialog v-model="modal" persistent max-width="1400">
                <v-card>
                    <v-container fluid>
                        <v-card-title class="headline success" style="color:white" primary-title>
                            <span class="title layout justify-center font-weight-light">Paciente</span>
                        </v-card-title>
                        <v-card-text>
                            <v-layout wrap>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Num_Doc" label="Numero de Documento"
                                        readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Tipo_Doc" label="Tipo de Documento"
                                        readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Region" readonly label="Region">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Ut" readonly label="UT"></v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Primer_Nom" readonly label="Primer Nombre">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.SegundoNom" readonly label="Segundo Nombre">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Primer_Ape" readonly
                                        label="Primer Apellido">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Segundo_Ape" readonly
                                        label="Segundo Apellido">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Sexo" readonly label="Sexo">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Tipo_Afiliado" readonly
                                        label="Tipo de Afiliado">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.sedeAtencionNombre" readonly
                                        label="Ips Primaria">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Fecha_Naci" readonly
                                        label="Fecha de Nacimiento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Fecha_Afiliado" readonly
                                        label="Fecha de Afiliacion">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Parentesco" readonly label="Parentesco">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.TipoDoc_Cotizante" readonly
                                        label="Tipo de Doc. Cotizante">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Doc_Cotizante" readonly
                                        label="Documento del Cotizante">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.Tipo_Cotizante" readonly
                                        label="Tipo de Cotizante">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field v-model="detallePacientes.estadoNombre" readonly
                                        label="Estado de Afiliacion">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field readonly v-model="detallePacientes.Discapacidad" label="Discapacidad">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-text-field readonly v-model="detallePacientes.Grado_Discapacidad"
                                        label="Grado de Discapacidad">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Datos
                                Portabilidad
                            </span>
                        </v-card-title>
                        <v-card-text>
                            <v-layout wrap>
                                <v-flex xs12 md3>
                                    <v-autocomplete :items="departamentos" item-text="nombre" item-value="id"
                                        v-model="detallePacientes.departamentoReceptor" label="Departamento Receptora">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-autocomplete append-icon="search" :items="municipio" item-text="municipio"
                                        item-value="id" v-model="detallePacientes.municipio_receptor"
                                        label="Municipio Receptor">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-autocomplete :items="utPortabilidad" item-text="nombre" item-value="id"
                                        v-model="detallePacientes.entidadReceptora" label="Entidad Receptora">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 md2>
                                    <v-text-field type="date" v-model="detallePacientes.fechaInicio_portabilidad"
                                        label="Fecha inicial Portabilidad">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md2>
                                    <v-text-field type="date" v-model="detallePacientes.fechaFinal_portabilidad"
                                        label="Fecha Final Portabilidad">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md2>
                                    <v-autocomplete :items="motivos" item-text="nombre" item-value="id"
                                        v-model="detallePacientes.causa" label="Motivo">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 md12 v-show="detallePacientes.causa == 5">
                                    <v-textarea v-model="detallePacientes.otra_causa" label="Cual">
                                    </v-textarea>
                                </v-flex>
                                <v-flex>
                                    <v-btn color="success"
                                        @click="modal = false,actualizarPacientePortabilidad(detallePacientes.id_novedad)">
                                        Actualizar
                                    </v-btn>
                                    <v-btn color="error" @click="modal = false,clear()">cerrar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-container>
                </v-card>
            </v-dialog>
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
    import Swal from 'sweetalert2';
    export default {
        data() {
            return {
                dialog: false,
                modal: false,
                preload: false,
                search: '',
                searchPaciente: '',
                portabilidad: [],
                utPortabilidad: [{
                        'id': 1,
                        'nombre': 'Unión Temporal Tolihuila'
                    },
                    {
                        'id': 2,
                        'nombre': 'Cosmitet Región 2'
                    },
                    {
                        'id': 3,
                        'nombre': 'Unión Temporal Salud Sur 2'
                    },
                    {
                        'id': 4,
                        'nombre': 'Unión Temporal Medisalud'
                    },
                    {
                        'id': 5,
                        'nombre': 'Unión Temporal del Norte Región 5'
                    },
                    {
                        'id': 6,
                        'nombre': 'Organización clínica general del norte'
                    },
                    {
                        'id': 7,
                        'nombre': 'Unión Temporal Red Integrada Foscal-CUB'
                    },
                    {
                        'id': 8,
                        'nombre': 'Red Vital UT'
                    },
                    {
                        'id': 9,
                        'nombre': 'Cosmitet Región 9'
                    },
                    {
                        'id': 10,
                        'nombre': 'Unión Temporal Servisalud San Jose'
                    }
                ],
                departamentos: [{
                        'id': '05',
                        'nombre': 'ANTIOQUIA'
                    },
                    {
                        'id': '08',
                        'nombre': 'ATLANTICO'
                    },
                    {
                        'id': 11,
                        'nombre': 'BOGOTA'
                    },
                    {
                        'id': 13,
                        'nombre': 'BOLIVAR'
                    },
                    {
                        'id': 15,
                        'nombre': 'BOYACA'
                    },
                    {
                        'id': 17,
                        'nombre': 'CALDAS'
                    },
                    {
                        'id': 18,
                        'nombre': 'CAQUETA'
                    },
                    {
                        'id': 19,
                        'nombre': 'CAUCA'
                    },
                    {
                        'id': 20,
                        'nombre': 'CESAR'
                    },
                    {
                        'id': 23,
                        'nombre': 'CORDOBA'
                    },
                    {
                        'id': 25,
                        'nombre': 'CUNDINAMARCA'
                    },
                    {
                        'id': 27,
                        'nombre': 'CHOCO'
                    },
                    {
                        'id': 41,
                        'nombre': 'HUILA'
                    },
                    {
                        'id': 44,
                        'nombre': 'LA GUAJIRA'
                    },
                    {
                        'id': 47,
                        'nombre': 'MAGDALENA'
                    },
                    {
                        'id': 50,
                        'nombre': 'META'
                    },
                    {
                        'id': 52,
                        'nombre': 'NARINO'
                    },
                    {
                        'id': 54,
                        'nombre': 'NORTE DE SANTANDER'
                    },
                    {
                        'id': 63,
                        'nombre': 'QUINDIO'
                    },
                    {
                        'id': 66,
                        'nombre': 'RISARALDA'
                    },
                    {
                        'id': 68,
                        'nombre': 'SANTANDER'
                    },
                    {
                        'id': 70,
                        'nombre': 'SUCRE'
                    },
                    {
                        'id': 73,
                        'nombre': 'TOLIMA'
                    },
                    {
                        'id': 76,
                        'nombre': 'VALLE DEL CAUCA'
                    },
                    {
                        'id': 81,
                        'nombre': 'ARAUCA'
                    },
                    {
                        'id': 85,
                        'nombre': 'CASANARE'
                    },
                    {
                        'id': 86,
                        'nombre': 'PUTUMAYO'
                    },
                    {
                        'id': 88,
                        'nombre': 'SAN ANDRES'
                    },
                    {
                        'id': 91,
                        'nombre': 'AMAZONAS'
                    },
                    {
                        'id': 94,
                        'nombre': 'GUAINIA'
                    },
                    {
                        'id': 95,
                        'nombre': 'GUAVIARE'
                    },
                    {
                        'id': 97,
                        'nombre': 'VAUPES'
                    },
                    {
                        'id': 99,
                        'nombre': 'VICHADA'
                    }
                ],
                motivos: [{
                        'id': 1,
                        'nombre': 'Comision de Estudios'
                    },
                    {
                        'id': 2,
                        'nombre': 'Vacaciones'
                    },
                    {
                        'id': 3,
                        'nombre': 'Licencia de maternidad'
                    },
                    {
                        'id': 4,
                        'nombre': 'Razones Familiares'
                    },
                    {
                        'id': 5,
                        'nombre': 'Otra'
                    }
                ],
                pacientes: [],
                municipio: [],
                detallePacientes: [{
                    Num_Doc: '',
                    Tipo_Doc: '',
                    Region: '',
                    Ut: '',
                    Primer_Nom: '',
                    SegundoNom: '',
                    Primer_Ape: '',
                    Segundo_Ape: '',
                    Sexo: '',
                    Tipo_Afiliado: '',
                    sedeAtencionNombre: '',
                    Fecha_Naci: '',
                    Fecha_Afiliado: '',
                    Parentesco: '',
                    TipoDoc_Cotizante: '',
                    Doc_Cotizante: '',
                    Tipo_Cotizante: '',
                    estadoNombre: '',
                    Discapacidad: '',
                    Grado_Discapacidad: '',
                    entidadReceptora: '',
                    fechaInicio_portabilidad: '',
                    fechaFinal_portabilidad: '',
                    causa: '',
                    otra_causa: '',
                    departamentoReceptor: ''
                }],
                adjunto: [],
                headers: [{
                        text: 'Id Paciente',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Tipo Doc',
                        value: 'Tipo_Doc'
                    },
                    {
                        text: '# Documento',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'Paciente',
                        value: 'Nombre'
                    },
                    {
                        text: 'Ut',
                        value: 'Ut'
                    },
                    {
                        text: 'Fecha Inicia',
                        value: 'fechaInicio_portabilidad'
                    },
                    {
                        text: 'Fecha Final',
                        value: 'fechaFinal_portabilidad'
                    },
                    {
                        text: 'Ips Portabilidad salida',
                        value: 'ipsdestino_portabilidad'
                    },
                ],
            }
        },
        created() {
            this.fechtPacientes();
            this.fetchMunicipios();
        },
        methods: {
            validacionPaciente() {
                if (!this.portabilidad.entidadReceptora) {
                    this.$alerError('el campo de entidad receptora no puede estar vacio')
                    return
                } else if (!this.portabilidad.fechaInicio_portabilidad) {
                    this.$alerError('El campo fecha de inicio de la portabilidad No puede estar vacio')
                    return
                } else if (!this.portabilidad.departamentoReceptor) {
                    this.$alerError('El campo departamento receptora no puede estar vacio')
                    return
                } else if (!this.portabilidad.fechaFinal_portabilidad) {
                    this.$alerError('El campo fecha final de portabilidad no puede estar vacio')
                    return
                } else if (!this.portabilidad.causa) {
                    this.$alerError('El campo motivo no puede estar vacio')
                    return
                }
                this.guardarPacientePortabilidad()
            },

            guardarPacientePortabilidad() {
                this.preload = true
                axios.post('/api/paciente/portabilidadSalida', this.portabilidad)
                    .then(res => {
                        this.preload = false;
                        this.$alerSuccess(res.data.mensaje);
                        this.dialog = false;
                        this.fechtPacientes();
                        this.clearCampos();
                    }).catch(error => {
                        this.dialog = false;
                        this.$alerError("Error al guardar!");
                    });

            },

            fetchMunicipios() {
                axios.get('/api/municipio/lista')
                    .then(res => {
                        this.municipio = res.data
                    })
            },

            getPaciente() {
                axios.get(`/api/paciente/buscarPaciente/${this.searchPaciente}`)
                    .then(res => {
                        this.portabilidad = res.data.Pacientes[0];
                    })
                    .catch(err => console.log(err.response.data));
            },

            fechtPacientes() {
                this.preload = true
                axios.get('/api/paciente/fechtPacientesPortabilidadSalida')
                    .then(res => {
                        this.preload = false
                        this.pacientes = res.data
                    }).catch(err => console.log(err.response));
            },

            detallepaciente(id) {
                this.preload = true
                axios.get('/api/paciente/detallepacientePortabilidad/' + id)
                    .then(res => {
                        this.preload = false
                        this.detallePacientes = res.data
                        this.detallePacientes.entidadReceptora = parseInt(res.data.ipsdestino_portabilidad)
                        this.detallePacientes.causa = parseInt(res.data.causa)
                        this.detallePacientes.municipio_receptor = parseInt(res.data.municipio_receptor)
                        if (this.detallePacientes.departamentoReceptor != '05' && this.detallePacientes
                            .departamentoReceptor != '08') {
                            this.detallePacientes.departamentoReceptor = parseInt(res.data.departamentoReceptor)
                        }
                    }).catch(err => {
                        this.preload = false
                    });
            },

            actualizarPacientePortabilidad(item) {
                this.preload = true
                axios.post('/api/paciente/actualizarPacientePortabilidad/' + item, this.detallePacientes)
                    .then(res => {
                        this.preload = false;
                        this.$alerSuccess(res.data.mensaje);
                        this.dialog = false;
                        this.fechtPacientes();
                        this.clearCampos();
                    }).catch(error => {
                        this.dialog = false;
                        this.$alerError("Error al guardar!");
                    });

            },

            inactivarPortabilidad(id) {
                Swal.fire({
                    title: 'Esta seguro que desea inactivar la portabilidad del paciente ?',
                    text: "Recuerde que despues de inactivar la portabilidad, solo puede activarlo con autorizacion",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4caf50',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Inactivar'
                }).then((result) => {
                    this.preload = true
                    if (result.isConfirmed) {
                        axios.post('/api/paciente/inactivaPrortabilidad/' + id)
                            .then(res => {
                                this.preload = false
                                this.$alerSuccess(res.data.mensaje);
                                this.fechtPacientes();
                            })
                            .catch(err => console.log(err.response));
                    } else {
                        this.preload = false
                    }
                })
            },

            clearCampos() {
                for (const key in this.portabilidad) {
                    this.portabilidad[key] = '';
                }
            },

            clear() {
                for (const key in this.detallePacientes) {
                    this.detallePacientes[key] = '';
                }
            }

        }
    }

</script>
