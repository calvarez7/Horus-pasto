<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-stepper v-model="e6" vertical>

                <v-stepper-step :complete="e6 > 1" editable :edit-icon="$vuetify.icons.complete" step="1">
                    ATENCIÓN
                </v-stepper-step>
                <v-stepper-content step="1">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-textarea name="input-7-1" label="Motivo de Consulta" value=""
                                v-model="data.Motivoconsulta"></v-textarea>
                        </v-container>
                    </v-card>
                    <v-btn color="primary" round @click="guardarMotivoConsulta()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step @click="fetchAntecedentePersonal(), fetchAntecedentes()" :complete="e6 > 2" editable
                    :edit-icon="$vuetify.icons.complete" step="2">ANTECEDENTES
                    PERSONALES</v-stepper-step>
                <v-stepper-content step="2">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm4 d-flex>
                                            <v-autocomplete label="Selecciona Antecedentes" :items="antecedentes"
                                                item-text="Nombre" item-value="id" v-model="antecedent.antecedente">
                                            </v-autocomplete>
                                        </v-flex>

                                        <v-flex xs12 sm6 d-flex>
                                            <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                                v-model="antecedent.descripcion">
                                            </v-textarea>
                                        </v-flex>

                                        <v-flex xs12 sm1 d-flex>
                                            <v-btn fab dark color="success" @click="guardarAntecedente()">
                                                <v-icon dark>add</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                        <v-card>
                            <v-card-title primary-title>
                                <v-flex xs12 sm12 d-flex>
                                    <v-data-table :items="antecedentePaci" :headers="antecedenteP" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.created_at }}</td>
                                            <td class="text-xs">{{ props.item.name }}</td>
                                            <td class="text-xs">{{ props.item.Nombre }}</td>
                                            <td class="text-xs">{{ props.item.Descripcion }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                    <v-btn color="primary" round @click="nextParentesco()">Seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step @click="fetchAntecedenteFamiliar(), fetchAntecedentes(), fetchParentesco()"
                    :complete="e6 > 3" editable :edit-icon="$vuetify.icons.complete" step="3">ANTECEDENTES
                    FAMILIARES</v-stepper-step>
                <v-stepper-content step="3">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-autocomplete label="Selecciona Antecedentes" :items="antecedentes"
                                                item-text="Nombre" item-value="id" v-model="antecedent.antecedente">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-autocomplete label="Selecciona Familiar" :items="parentesco"
                                                item-text="Nombre" item-value="id" v-model="antecedent.familiar">
                                            </v-autocomplete>
                                        </v-flex>

                                        <v-flex xs12 sm5 d-flex>
                                            <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                                v-model="antecedent.descripcion">
                                            </v-textarea>
                                        </v-flex>

                                        <v-flex xs12 sm1 d-flex>
                                            <v-btn fab dark color="success" @click="guardarAntecedentefamiliar()">
                                                <v-icon dark>add</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                        <v-card>
                            <v-card-title primary-title>
                                <v-flex xs12 sm12 d-flex>
                                    <v-data-table :items="antecedenteFami" :headers="familiare" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.created_at }}</td>
                                            <td class="text-xs">{{ props.item.name }}</td>
                                            <td class="text-xs">{{ props.item.Nombre }}</td>
                                            <td class="text-xs">{{ props.item.familiar }}</td>
                                            <td class="text-xs">{{ props.item.Descripcion }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                    <v-btn color="primary" round @click="nextConigtiva()">Seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 4" editable :edit-icon="$vuetify.icons.complete" step="4">
                    RESPIRACION
                </v-stepper-step>
                <v-stepper-content step="4">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs4 sm3 class="px-5">
                                <v-radio-group v-model="voz.respiracion_modo" label="MODO">
                                    <v-radio v-for="n in modo" :key="n" :label="`${n}`" :value="n" color="primary">
                                    </v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs4 sm3 class="px-5">
                                <v-radio-group v-model="voz.respiracion_tipo" label="TIPO">
                                    <v-radio v-for="n in tipo" :key="n" :label="`${n}`" :value="n" color="primary">
                                    </v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs4 sm3 class="px-5">
                                <v-radio-group v-model="voz.respiracion_fonorespiratoria"
                                    label="COORD. FONORESPIRATORIA">
                                    <v-radio v-for="n in fonorespiratoria" :key="n" :label="`${n}`" :value="n"
                                        color="primary"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs12 md3 class="px-2">
                                <v-radio-group v-model="voz.perimetros_biaxilar" label="PRUEBA GLATZER">
                                    <v-radio v-for="n in glatzer" :key="n" :label="`${n}`" :value="n" color="primary">
                                    </v-radio>
                                </v-radio-group>
                            </v-flex>
                        </v-layout>
                        <hr>
                        <h2 class="text-md-center">PERIMETROS</h2>
                        <v-layout row wrap>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.perimetros_xifoideo" label="biaxilar" required>
                                </v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.perimetros_abdominal" label="xifoideo" required>
                                </v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.respiracion_prueba_glatzer" label="abdominal" required>
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                        <hr>
                        <h2 class="text-md-center">TIEMPOS DE RESPIRACION</h2>
                        <v-layout row wrap>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.tiempos_respiracion_inspiracion" label="inspiración"
                                    required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.tiempos_respiracion_espiracion" label="espiración" required>
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextRespiracion()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 5" editable :edit-icon="$vuetify.icons.complete" step="5">
                    CUALIDADES DE VOZ
                </v-stepper-step>
                <v-stepper-content step="5">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs4 sm3 class="px-5">
                                <v-radio-group v-model="voz.timbre" label="TIMBRE">
                                    <v-radio v-for="n in Calidad" :key="n" :label="`${n}`" :value="n" color="primary">
                                    </v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs4 sm3 class="px-5">
                                <v-radio-group v-model="voz.intensidad" label="INTENSIDAD">
                                    <v-radio v-for="n in Intensidad" :key="n" :label="`${n}`" :value="n"
                                        color="primary">
                                    </v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs4 sm3 class="px-5">
                                <v-radio-group v-model="voz.tono" label="TONO">
                                    <v-radio v-for="n in Tono" :key="n" :label="`${n}`" :value="n" color="primary">
                                    </v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs4 sm3 class="px-5">
                                <v-radio-group v-model="voz.cierre_glotico" label="CIERRE GLOTICO">
                                    <v-radio v-for="n in Glotico" :key="n" :label="`${n}`" :value="n" color="primary">
                                    </v-radio>
                                </v-radio-group>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextCualidadesVoz()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 6" editable :edit-icon="$vuetify.icons.complete" step="6">
                    MANEJO DE RESONADORES
                </v-stepper-step>
                <v-stepper-content step="6">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.lugar_cabeza" label="Cabeza" required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.lugar_frente" label="frente" required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.lugar_nasales" label="nasales" required>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.lugar_mejillas" label="mejillas" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.lugar_cuello" label="cuello" required>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-textarea outline name="input-7-4" v-model="voz.voz_observaciones" label="OBSERVACIONES">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextManejoPersonal()">Guardar y seguir</v-btn>
                </v-stepper-content>
                <v-stepper-step :complete="e6 > 7" editable :edit-icon="$vuetify.icons.complete" step="7">
                    EXAMEN FISICO
                </v-stepper-step>
                <v-stepper-content step="7">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <h2 class="text-md-center">MUSCULATURA LARINGEA</h2>
                        <v-layout row wrap>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_laringea_normal" label="normal" required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_laringea_irritada" label="irritada" required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_laringea_inflamada" label="inflamada" required>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_laringea_placas" label="placas" required></v-text-field>
                            </v-flex>
                        </v-layout>
                        <h2 class="text-md-center">MUSCULATURA EXTRALARINGEA</h2>
                        <v-layout row wrap>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_extralaringea_dolor_palpar" label="dolor al palpar" required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_extralaringea_dolor_deglutir" label="dolor al deglutir" required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_extralaringea_tono_normal" label="tono normal" required>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_extralaringea_tono_aumentado" label="tono aumentado" required></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextDiagnosticos()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 8" @click="fetchCie10s()" editable :edit-icon="$vuetify.icons.complete"
                    step="8">
                    DIAGNÓSTICOS
                </v-stepper-step>
                <v-stepper-content step="8">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm6 class="px-2">
                                <v-autocomplete v-model="Cie10_id" append-icon="search" :items="cieConcat"
                                    item-disabled="customDisabled" item-text="nombre" item-value="id"
                                    label="Diagnóstico">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-select :items="['Impresión diagnóstica', 'Confirmado nuevo','Confirmado repetido']"
                                    label="Tipo Diagnóstico" v-model="Tipodiagnostico" chips></v-select>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-autocomplete v-if="Tipodiagnostico != 'Impresión diagnóstica' && Tipodiagnostico"
                                    :items="['Oncológicos','Anticoagulados','Salud Mental','Nefroprotección ','Respiratorios',
                                    'Reumatologicos','Tramisibles Crónicas','Gestantes','Enfermedades Huerfanas','Trasplantados',
                                    'Polimedicados','Pacientes Tutela','Paciente Alto Costo','Domiciliario','Primera Infancia','Infancia ',
                                    'Adolescencia ','Juventud ','Riesgo Cardiovascular','Grupal Curso Psicoprofilactico',
                                    'Apoyo Lactancia Materna ','Detección Temprana Cáncer Cuello Uterino ','Riesgo Cardiovascular',
                                    'Planificación Familiar','Atención Preconcepcional','Promocion De Alimentacion Y Nutricion','Prenatal',
                                    'Atención Del Posparto','Atención Del Recien Nacido','Detección Temprana Cancer De Mama', 'Adultez', 'Vejez'
						            ]" label="Marcar paciente" v-model="Marcapaciente" attach multiple chips></v-autocomplete>
                            </v-flex>
                            <v-btn color="primary" round dark v-on:click="addDiagnostico()">Ingresar Diagnóstico</v-btn>
                            <v-flex xs12 sm12>
                                <v-data-table :headers="headersDiagnostico" :items="Diagnostico" :search="search">
                                    <template v-slot:items="props">
                                        <tr :active="props.selected" @click="props.selected = !props.selected">
                                            <td>
                                                <v-checkbox color="primary" v-model="selectedItems" hide-details
                                                    :value="props.item">
                                                </v-checkbox>
                                            </td>
                                            <td>{{ props.item.Codigo }}</td>
                                            <td class="text-xs-center">{{ props.item.Tipodiagnostico }}</td>
                                            <td class="text-xs-center">
                                                <v-select :items="props.item.Marcapaciente" label="Marcar paciente"
                                                    v-model="props.item.Marcapaciente" attach multiple chips></v-select>
                                            </td>
                                            <td class="text-xs-center">
                                                <v-btn fab utline color="error" small @click="removeDiagnostico(props)">
                                                    <v-icon>clear</v-icon>
                                                </v-btn>
                                            </td>
                                        </tr>
                                    </template>
                                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your search
                                        for
                                        "{{ search }}"
                                        found no results.</v-alert>
                                </v-data-table>
                            </v-flex>
                            <v-btn color="primary" round dark v-on:click="submitDiagnostico()">Guardar y seguir</v-btn>
                        </v-layout>
                    </v-card>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 9" editable :edit-icon="$vuetify.icons.complete" step="9">
                    CONCEPTO DE APTITUD LABORAL – VOZ
                </v-stepper-step>
                <v-stepper-content step="9">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md3 class="px-2">
                                <v-autocomplete :items="aptitud" v-model="voz.aptitud_laboral_voz"
                                    label="CONCEPTO DE APTITUD LABORAL – VOZ">
                                </v-autocomplete>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextFin()">Guardar y terminar</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 10" editable :edit-icon="$vuetify.icons.complete" step="10">
                    CONDUCTAS
                </v-stepper-step>
                <v-stepper-content step="10">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm6 class="px-2">
                                <v-textarea label="Plan de manejo" v-model="conducta.Planmanejo">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-textarea label="Recomendaciones" v-model="conducta.Recomendaciones"></v-textarea>
                            </v-flex>
                           <v-flex xs12 sm4 class="px-2">
                                <v-autocomplete :items="[
                                'Control', 'Interconsulta', 'Urgencias', 'Consulta externa prioritaria', 'No aplica'
								]" label="Destino del paciente" append-icon="search" v-model="conducta.Destinopaciente"></v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm5>
                                <v-autocomplete :items="[
									'Consulta SST', 'No aplica'
								]" label="Finalidad" append-icon="search" v-model="conducta.Finalidad"></v-autocomplete>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="guardarConducta()">Guardar y terminar</v-btn>
                </v-stepper-content>

            </v-stepper>

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

        </v-flex>
    </v-layout>
</template>
<script>
    import Swal from 'sweetalert2';
    import {
        EventBus
    } from '../../../../event-bus.js';
    export default {
        created() {
            this.citaPaciente = this.$route.query.cita_paciente;
            this.antecedent.cita_paciente = this.$route.query.cita_paciente;
            this.removeLocalStorage();
            this.getLocalStorage();
        },
        data() {
            return {
                aptitud: ['Sin Recomendaciones', 'Con Recomendaciones', 'Sin Restricciones', 'Con Restricciones'],
                Calidad: ['Normal', 'Disfonico', 'Nasalizado'],
                Intensidad: ['Normal', 'Disminuida', 'Aumentada'],
                Tono: ['Normal', 'Hipertonico', 'Hipotonico'],
                Glotico: ['Completo', 'Incompleto'],
                glatzer: ['adecuada', 'inadecuada'],
                fonorespiratoria: ['Normal', 'Discontinua', 'Fuera de fase'],
                tipo: ['Costal Superior', 'Mixto', 'Abdominal'],
                modo: ['Nasal', 'Bucal', 'Mixto'],
                preload: false,
                e6: 1,
                data: {
                    Motivoconsulta: ''
                },
                antecedent: {
                    antecedente: '',
                    descripcion: '',
                    cita_paciente: '',
                    familiar: ''
                },
                citaPaciente: 0,
                antecedentes: [],
                antecedentePaci: [],
                antecedenteP: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Descripción'
                    }
                ],
                parentesco: [],
                antecedenteFami: [],
                familiare: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Parentesco'
                    },
                    {
                        text: 'Descripción'
                    }
                ],
                Cie10_id: '',
                cie10Array: [],
                cie10s: [],
                Tipodiagnostico: '',
                headersDiagnostico: [{
                        text: "Marcar Principal",
                        align: "center",
                        value: "marcar"
                    },
                    {
                        text: "Diagnóstico",
                        align: "center",
                        value: "diagnostico"
                    },
                    {
                        text: "Tipo Diagnóstico",
                        align: "center",
                        value: "tipo_diagnostico"
                    },
                    {
                        text: "Marca",
                        align: "center",
                        value: "marca"
                    },
                    {
                        text: "",
                        align: "center",
                        value: ""
                    }
                ],
                Diagnostico: [],
                search: '',
                Marcapaciente: [],
                selectedItems: {},
                DiagnosticoSaved: [],
                conducta: {
                    Planmanejo: '',
                    Recomendaciones: '',
                    Destinopaciente: '',
                    Finalidad: ''
                },
                voz: {
                    respiracion_modo: '',
                    respiracion_tipo: '',
                    respiracion_fonorespiratoria: '',
                    perimetros_biaxilar: '',
                    perimetros_xifoideo: '',
                    perimetros_abdominal: '',
                    respiracion_prueba_glatzer: '',
                    tiempos_respiracion_inspiracion: '',
                    tiempos_respiracion_espiracion: '',
                    timbre: '',
                    intensidad: '',
                    tono: '',
                    cierre_glotico: '',
                    lugar_cabeza: '',
                    lugar_frente: '',
                    lugar_nasales: '',
                    lugar_mejillas: '',
                    lugar_cuello: '',
                    voz_observaciones: '',
                    musculatura_laringea_normal: '',
                    musculatura_laringea_irritada: '',
                    musculatura_laringea_inflamada: '',
                    musculatura_laringea_placas: '',
                    musculatura_extralaringea_dolor_palpar: '',
                    musculatura_extralaringea_dolor_deglutir: '',
                    musculatura_extralaringea_tono_normal: '',
                    musculatura_extralaringea_tono_aumentado: '',
                    aptitud_laboral_voz: '',
                    remision_especialista: '',
                },
            }
        },

        computed: {
            cieConcat() {
                if (this.cie10s !== undefined) {
                    return this.cie10Array = this.cie10s.map(cie => {
                        return {
                            id: cie.id,
                            codigo: cie.Codigo_CIE10,
                            nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                            customDisabled: false
                        };
                    });
                }
            }
        },
        mounted() {
            this.fetchCie10s();
        },

        methods: {

            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },

            addDiagnostico() {
                if (this.Cie10_id) {
                    this.cie10Array.find(cie10 => {
                        if (cie10.id == this.Cie10_id) {
                            this.Codigo = cie10.codigo;
                        }
                    });
                }
                if (
                    this.Cie10_id &&
                    this.Tipodiagnostico != "Impresión diagnóstica" &&
                    this.Marcapaciente.length > 0
                ) {
                    this.Diagnostico.push({
                        Cie10_id: this.Cie10_id,
                        Tipodiagnostico: this.Tipodiagnostico,
                        Marcapaciente: this.Marcapaciente,
                        Esprimario: false,
                        Codigo: this.Codigo
                    });
                    this.disable(this.Cie10_id);
                    this.clearDataArticulo();
                } else if (
                    this.Cie10_id &&
                    this.Tipodiagnostico == "Impresión diagnóstica" &&
                    this.Marcapaciente.length == 0
                ) {
                    this.Diagnostico.push({
                        Cie10_id: this.Cie10_id,
                        Tipodiagnostico: this.Tipodiagnostico,
                        Esprimario: false,
                        Codigo: this.Codigo
                    });
                    this.disable(this.Cie10_id);
                    this.clearDataArticulo();
                }
            },

            removeDiagnostico(diagnostico) {
                this.Diagnostico.splice(diagnostico.index, 1);
                if (diagnostico.item.id != null) {
                    axios
                        .get(`/api/cie10/deleteDiagnostic/${diagnostico.item.id}`)
                        .then(res => localStorage.removeItem("Diagnostico"));
                }
                this.cie10Array.map(cie10 => {
                    if (cie10.id == diagnostico.item.Cie10_id) {
                        cie10.customDisabled = false
                    }
                });
            },

            fetchDiagnostico() {
                axios.get(`/api/cie10/diagnostico/${this.citaPaciente}`).then(res => {
                    this.DiagnosticoSaved = res.data.cie10;
                    this.Diagnostico = this.DiagnosticoSaved;
                });
            },

            clearDataArticulo() {
                this.Cie10_id = '';
                this.Tipodiagnostico = '';
                this.Marcapaciente = '';
                this.Codigo = '';
            },

            disable(id) {
                this.cie10Array.map(cie10 => {
                    if (cie10.id == id) {
                        cie10.customDisabled = true
                    }
                });
            },

            fetchAtencion() {
                axios.get('/api/cita/' + this.citaPaciente + '/motivo')
                    .then(res => {
                        this.data = res.data;
                    });
            },

            guardarMotivoConsulta() {
                if (!this.data.Motivoconsulta) {
                    swal("Motivo consulta es REQUERIDO y debe ser mayor a 5 caracteres")
                    return;
                } else if (this.data.Motivoconsulta.length < 5) {
                    swal("Motivo consulta debe ser mayor a 5 caracteres")
                    return;
                }
                this.preload = true;
                axios.post('/api/cita/' + this.citaPaciente + '/motivo', this.data).then((res) => {
                    this.preload = false;
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
                        title: '<span style="color:#fff">Motivo Guardado con exito<span>'
                    });
                    this.e6 = 2;
                    localStorage.setItem("Atencion", JSON.stringify(this.data));
                    this.fetchAntecedentes();
                    this.fetchAntecedentePersonal();
                });

            },
            fetchAntecedentes() {
                axios.get("/api/antecedente/all").then(res => (this.antecedentes = res.data));
            },
            guardarAntecedente() {
                this.preload = true;
                axios.post("/api/pacienteantecedente/create", this.antecedent)
                    .then(res => {
                        this.preload = false;
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
                            title: '<span style="color:#fff">Antecedentes Agregado con exito<span>'
                        });
                        this.clearAntecedente();
                        this.fetchAntecedentePersonal();
                    })
                    .catch(err => console.log(err.response.data));
            },
            fetchAntecedentePersonal() {
                let data = {
                    citaPaciente_id: this.citaPaciente
                }
                axios.post("/api/pacienteantecedente/antecedentes1", data)
                    .then(res => {
                        this.antecedentePaci = res.data
                    });
            },
            nextParentesco() {
                this.e6 = 3;
                this.fetchParentesco();
                this.fetchAntecedenteFamiliar();
            },
            clearAntecedente() {
                this.antecedent.antecedente = ''
                this.antecedent.descripcion = ''
                this.antecedent.familiar = ''
            },
            fetchParentesco() {
                axios.get("/api/parentesco/all").then(res => (this.parentesco = res.data));
            },
            guardarAntecedentefamiliar() {
                this.preload = true;
                axios.post("/api/parentescoantecedente/create", this.antecedent)
                    .then(res => {
                        this.preload = false;
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
                            title: '<span style="color:#fff">Antecedentes Agregado con exito<span>'
                        });
                        this.clearAntecedente();
                        this.fetchAntecedenteFamiliar();
                    })
                    .catch(err => console.log(err.response.data));
            },
            fetchAntecedenteFamiliar() {
                let data = {
                    citaPaciente_id: this.citaPaciente
                }
                axios.post("/api/parentescoantecedente/familiares", data)
                    .then(res => {
                        this.antecedenteFami = res.data
                    });
            },
            getLocalStorage() {
                let Diagnostico = JSON.parse(localStorage.getItem("Diagnostico"));
                if (Diagnostico) {
                    this.Diagnostico = Diagnostico;
                    this.Diagnostico.forEach(diag => {
                        this.disable(diag.Cie10_id);
                    });
                } else {
                    this.fetchDiagnostico();
                }

                let motivo = JSON.parse(localStorage.getItem("Atencion"));
                if (motivo) {
                    this.data = motivo;
                } else {
                    this.fetchAtencion();
                }

                let conducta = JSON.parse(localStorage.getItem("Conducta"));
                if (conducta) {
                    this.conducta = conducta;
                } else {
                    this.fetchConducta();
                }

                let voz = JSON.parse(localStorage.getItem("voz"));
                if (voz) {
                    this.voz = voz;
                } else {
                    this.fetchVoz();
                }
            },
            removeLocalStorage() {
                localStorage.removeItem("Atencion");
                localStorage.removeItem("Diagnostico");
                localStorage.removeItem("Conducta");
                localStorage.removeItem("voz");
            },
            submitDiagnostico() {
                if (this.Diagnostico.length == 0 || !this.selectedItems) {
                    swal({
                        title: "Debe asignar un diagnostico principal",
                        icon: "warning"
                    });
                    return;
                }
                var diag = [];
                this.Diagnostico.forEach(diagnostico => {
                    if (diagnostico.Cie10_id == this.selectedItems.Cie10_id) {
                        diagnostico.Esprimario = true;
                    }
                    if (diagnostico.id == null) {
                        diag.push(diagnostico);
                    }
                });
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El diagnóstico será almacenado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios
                            .post(`/api/cie10/create/${this.citaPaciente}`, diag)
                            .then(res => {
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
                                    title: '<span style="color:#fff">Diagnostico guardado con exito<span>'
                                });
                                localStorage.setItem(
                                    "Diagnostico",
                                    JSON.stringify(this.Diagnostico)
                                );
                                this.nextConducta();
                            })
                            .catch(err => console.log(err.response.data));
                    }
                });
            },
            guardarConducta() {
                if (this.conducta.Planmanejo == undefined) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Plan de manejo debe ser minimo de 5 caracteres !<span>'
                    })
                    return;
                } else if (this.conducta.Recomendaciones == undefined) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Recomendaciones de consulta debe ser minimo de 5 caracteres!<span>'
                    })
                    return;
                } else if (!this.conducta.Destinopaciente) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Destino del paciente es requerido!<span>'
                    })
                    return;
                } else if (!this.conducta.Finalidad) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">La FINALIDAD es requerida!<span>'
                    })
                    return;
                }
                this.preload = true;
                axios
                    .post("/api/conducta/" + this.citaPaciente + "/final", this.conducta)
                    .then(res => {
                        this.preload = false;
                        this.e6 = 12;
                        localStorage.setItem("Conducta", JSON.stringify(this.conducta));
                        swal({
                            title: res.data.message,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        // EventBus.$emit("call-order");
                    });
            },
            fetchConducta() {
                axios.post('/api/conducta/' + this.citaPaciente + '/getConductaByCita')
                    .then(res => {
                        if (res.data) {
                            this.conducta = res.data
                        }
                    })
                    .catch(err => console.log(err.response));
            },
            saveRespiracion() {
                axios.post('/api/cita/saveSaludocupacional/' + this.citaPaciente, this.voz).then((res) => {
                    localStorage.setItem("voz", JSON.stringify(this.voz));
                    console.log('aca', res.data);
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
                        title: '<span style="color:#fff">Guardado con exito!<span>'
                    });
                })
            },
            saveManejoPersona() {
                axios.post('/api/cita/saveSaludocupacional/' + this.citaPaciente, this.saludocupacional).then((res) => {
                    localStorage.setItem("saludocupacional", JSON.stringify(this.saludocupacional));
                    console.log('aca', res.data);
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
                        title: '<span style="color:#fff">Guardado con exito!<span>'
                    });
                })
            },
            saveExamenfisico() {
                // axios.post('/api/cita/saveSaludocupacional/' + this.citaPaciente, this.saludocupacional).then((res) => {
                //     localStorage.setItem("saludocupacional", JSON.stringify(this.saludocupacional));
                //     console.log('aca', res.data);
                //     const Toast = Swal.mixin({
                //         toast: true,
                //         position: 'center-end',
                //         background: '#4caf50',
                //         showConfirmButton: false,
                //         timer: 1000,
                //         timerProgressBar: false,
                //         onOpen: (toast) => {
                //             toast.addEventListener('mouseenter', Swal.stopTimer)
                //             toast.addEventListener('mouseleave', Swal.resumeTimer)
                //         }
                //     })
                //     Toast.fire({
                //         icon: 'success',
                //         title: '<span style="color:#fff">Guardado con exito!<span>'
                //     });
                // })
            },
            fetchVoz() {
                axios.post('/api/cita/getSaludocupacional/' + this.citaPaciente)
                    .then(res => {
                        if (res.data) {
                            this.voz = res.data
                        }
                    })
                    .catch(err => console.log(err.response));
            },
            nextRespiracion() {
                this.saveRespiracion();
                this.e6 = 5
            },
            nextCualidadesVoz() {
                this.saveRespiracion();
                this.e6 = 6
            },
            nextManejoPersonal() {
                this.e6 = 7;
                this.saveRespiracion();
            },
            nextDiagnosticos() {
                this.e6 = 8;
                this.saveRespiracion();
            },
            nextConigtiva() {
                this.e6 = 4;
            },
            nextConducta() {
                this.e6 = 9
            },
            nextFin() {
                this.e6 = 10
                this.saveRespiracion();
            }

        }
    }

</script>
