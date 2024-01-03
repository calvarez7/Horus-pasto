<template>
    <v-layout row justify-center>
        <v-flex xs12>
            <v-card>
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs12 md12 lg12>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-data-table :search="search" :headers="headers" :items="listaSolicitudes">
                    <template v-slot:items="props">
                        <td class="text-xs-left">{{ props.item.id }}</td>
                        <td class="text-xs-left">{{ props.item.paciente }}</td>
                        <td class="text-xs-left">{{ props.item.Num_Doc }}</td>
                        <td class="text-xs-left">{{ props.item.created_at }}</td>
                        <td class="text-xs-left">{{ props.item.IPS }}</td>
                        <td>
                            <v-chip color="red" text-color="white">{{ props.item.estado }}</v-chip>
                        </td>
                        <td class="text-xs-left">{{ props.item.user_crea }}</td>
                        <td class="text-xs-left">
                            <v-btn round small color="warning" @click="cargarDetalles(props.item.id)">Generar admisión
                            </v-btn>
                        </td>

                    </template>
                </v-data-table>
                <v-dialog v-model="dialogDetalle" persistent max-width="1600px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Admisión de pacientes</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <form @submit.prevent="guardarSeguimiento">
                                        <v-container>
                                            <v-layout row wrap>
                                                <v-flex xs12 sm4 md4 required>
                                                    <v-label><strong>Estado del Caso</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.clasificacion_caso">
                                                        <v-radio key="probable" label="Probable" value="probable"
                                                            color="info" >
                                                        </v-radio>
                                                        <v-radio key="descartado" label="Descartado" value="descartado"
                                                            color="info" ></v-radio>
                                                        <v-radio key="confirmado" label="Confirmado" value="confirmado"
                                                            color="info" ></v-radio>
                                                        <v-radio key="Recuperado" label="Recuperado" value="Recuperado"
                                                            color="info" ></v-radio>
                                                        <v-radio key="Positivo Fallecido" label="Positivo Fallecido"
                                                            value="Positivo Fallecido" color="info" ></v-radio>
                                                    </v-radio-group>
                                                </v-flex>
                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Caso</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.caso">
                                                        <v-radio key="1" label="1" value="1" color="info" >
                                                        </v-radio>
                                                        <v-radio key="2" label="2" value="2" color="info" >
                                                        </v-radio>
                                                        <v-radio key="3,1" label="3,1" value="3,1" color="info"
                                                            ></v-radio>
                                                        <v-radio key="3,2" label="3,2" value="3,2" color="info"
                                                            ></v-radio>
                                                        <v-radio key="4" label="4" value="4" color="info" >
                                                        </v-radio>
                                                        <v-radio key="5" label="5" value="5" color="info" >
                                                        </v-radio>

                                                    </v-radio-group>
                                                </v-flex>
                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Toma de Muestra</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.toma_muestra">
                                                        <v-radio v-for="n in afirmacion" :key="n.id"
                                                            :label="`${n.nombre}`" :value="n.id" color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>
                                                <v-layout row wrap v-show="formSeguimiento.toma_muestra == 1">
                                                    <v-flex xs12 sm4 md4>
                                                        <v-select :items="tipomuestra" label="Tipo de Muestra" v-model="formSeguimiento.tipoMuestra"></v-select>
                                                    </v-flex>
                                                    <v-flex xs12 sm4 md4>
                                                        <v-menu v-model="menuFechaRealizacion"
                                                            :close-on-content-click="false" :nudge-right="40" lazy
                                                            transition="scale-transition" offset-y full-width
                                                            min-width="290px">
                                                            <template v-slot:activator="{ on }">
                                                                <v-text-field
                                                                    v-model="formSeguimiento.fecha_realizacion"
                                                                    label="Fecha Realización" prepend-icon="event"
                                                                    readonly v-on="on">
                                                                </v-text-field>
                                                            </template>
                                                            <v-date-picker v-model="formSeguimiento.fecha_realizacion"
                                                                @input="menuFechaRealizacion = false"></v-date-picker>
                                                        </v-menu>
                                                    </v-flex>

                                                    <v-flex xs12 sm4 md4>
                                                        <v-menu v-model="menuFechaResultado"
                                                            :close-on-content-click="false" :nudge-right="40" lazy
                                                            transition="scale-transition" offset-y full-width
                                                            min-width="290px">
                                                            <template v-slot:activator="{ on }">
                                                                <v-text-field v-model="formSeguimiento.fecha_resultado"
                                                                    label="Fecha Resultado" prepend-icon="event"
                                                                    readonly v-on="on">
                                                                </v-text-field>
                                                            </template>
                                                            <v-date-picker v-model="formSeguimiento.fecha_resultado"
                                                                @input="menuFechaResultado = false"></v-date-picker>
                                                        </v-menu>
                                                    </v-flex>

                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Resultado</strong></v-label>
                                                        <v-radio-group v-model="formSeguimiento.resultado">
                                                            <v-radio key="negativo" label="negativo" value="negativo"
                                                                color="info">
                                                            </v-radio>
                                                            <v-radio key="positivo" label="Positivo" value="positivo"
                                                                color="info">
                                                            </v-radio>
                                                        </v-radio-group>
                                                    </v-flex>

                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Quien toma la muestra</strong></v-label>
                                                        <v-radio-group v-model="formSeguimiento.quien_toma_muestra">
                                                            <v-radio key="sumimedical" label="Sumimedical" value="sumimedical" color="info"></v-radio>
                                                            <v-radio key="ips_externa" label="IPS Externa" value="ips_externa" color="info"></v-radio>
                                                            <v-radio key="secretaria_salud" label="Secretaria de Salud" value="secretaria_salud" color="info"></v-radio>
                                                        </v-radio-group>
                                                    </v-flex>

                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Quien procesa la muestra</strong></v-label>
                                                        <v-radio-group v-model="formSeguimiento.quien_procesa_muestra">
                                                            <v-radio key="sumimedical" label="Sumimedical" value="sumimedical" color="info"></v-radio>
                                                            <v-radio key="IPSU" label="IPSU" value="IPSU" color="info"></v-radio>
                                                            <v-radio key="ips_externa" label="IPS Externa" value="ips_externa" color="info"></v-radio>
                                                            <v-radio key="secretaria_salud" label="Secretaria de Salud" value="secretaria_salud" color="info"></v-radio>
                                                        </v-radio-group>
                                                    </v-flex>

                                                    <v-flex xs12 sm12 md12>
                                                        <v-textarea v-model="formSeguimiento.resultado_muestra"
                                                            name="input-7-1" label=" Resultado de Muestra"
                                                            hint="Descripción Resultado de Muestra">
                                                        </v-textarea>
                                                    </v-flex>
                                                </v-layout>
                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Tuvo contacto estrecho en los últimos 14 días ?</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.contacto_estrecho">
                                                        <v-radio v-for="n in afirmacion" :key="n.id"
                                                            :label="`${n.nombre}`" :value="n.id" color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Vacunación Estacional Vigente</strong></v-label>
                                                    <v-radio-group
                                                        v-model="formSeguimiento.vacunacion_estacional_vigente">
                                                        <v-radio v-for="n in afirmacion" :key="n.id"
                                                            :label="`${n.nombre}`" :value="n.id" color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Uso de antibióticos en la última semana.</strong>
                                                    </v-label>
                                                    <v-radio-group v-model="formSeguimiento.antibioticos_ultima_semana">
                                                        <v-radio v-for="n in afirmacion" :key="n.id"
                                                            :label="`${n.nombre}`" :value="n.id" color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Requiere Hospitalización ?</strong>
                                                    </v-label>
                                                    <v-radio-group v-model="formSeguimiento.requiere_hospitalizacion">
                                                        <v-radio v-for="n in afirmacion" :key="n.id"
                                                            :label="`${n.nombre}`" :value="n.id" color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>
                                                <v-layout row wrap v-show="formSeguimiento.requiere_hospitalizacion == 1">
                                                <v-flex xs12 sm4 md4>
                                                    <v-menu v-model="menuFechaIngreso" :close-on-content-click="false"
                                                        :nudge-right="40" lazy transition="scale-transition" offset-y
                                                        full-width min-width="290px">
                                                        <template v-slot:activator="{ on }">
                                                            <v-text-field v-model="formSeguimiento.fecha_ingreso"
                                                                label="Fecha de Ingreso a Hospital/ Clínica/ Urgencias"
                                                                prepend-icon="event" readonly v-on="on"></v-text-field>
                                                        </template>
                                                        <v-date-picker v-model="formSeguimiento.fecha_ingreso"
                                                            @input="menuFechaIngreso = false"></v-date-picker>
                                                    </v-menu>
                                                </v-flex>
                                                <v-flex xs12 sm4 md4>
                                                    <v-text-field label="Nombre de la Institución"
                                                        v-model="formSeguimiento.nombre_institucion"></v-text-field>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Tipo de Hospitalización.</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.tipo_hospitalizacion">
                                                        <v-radio key="UCI" label="UCI" value="uci" color="info"
                                                            ></v-radio>
                                                        <v-radio key="UCE" label="UCE" value="uce" color="info"
                                                            ></v-radio>
                                                        <v-radio key="Hospitalizacion" label="Hospitalizacion"
                                                            value="hospitalizacion" color="info" ></v-radio>
                                                        <v-radio key="Urgencias" label="Urgencias" value="urgencia"
                                                            color="info" ></v-radio>
                                                    </v-radio-group>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-menu v-model="menuFechaSalida" :close-on-content-click="false"
                                                        :nudge-right="40" lazy transition="scale-transition" offset-y
                                                        full-width min-width="290px">
                                                        <template v-slot:activator="{ on }">
                                                            <v-text-field v-model="formSeguimiento.fecha_salida"
                                                                label="Fecha de Salida" prepend-icon="event" readonly
                                                                v-on="on"></v-text-field>
                                                        </template>
                                                        <v-date-picker v-model="formSeguimiento.fecha_salida"
                                                            @input="menuFechaSalida = false"></v-date-picker>
                                                    </v-menu>
                                                </v-flex>


                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Estado al Alta</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.estado_alta">
                                                        <v-radio key="Vivo" label="Vivo" value="vivo" color="info"
                                                            ></v-radio>
                                                        <v-radio key="Fallecido" label="Fallecido" value="fallecido"
                                                            color="info" ></v-radio>
                                                    </v-radio-group>
                                                </v-flex>
                                                </v-layout>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Gestante</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.gestante">
                                                        <v-radio v-for="n in afirmacion" :key="n.id"
                                                            :label="`${n.nombre}`" :value="n.id" color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Pertenece a alguna de las siguientes Poblaciones en
                                                            Riesgo</strong>
                                                    </v-label>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Riesgo Cardiocerebrovascular Metabólicas"
                                                        value="Riesgo Cardiocerebrovascular Metabólicas" >
                                                    </v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Anticoagulados" value="Anticoagulados" >
                                                    </v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Oncológicos" value="Oncológicos" ></v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Salud Mental" value="Salud Mental" ></v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Reumatológicos" value="Reumatológicos" >
                                                    </v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Infectocontagiosos" value="Infectocontagiosos" >
                                                    </v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Nefroprotección" value="Nefroprotección" >
                                                    </v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Respiratorios Crónicos" value="Respiratorios Crónicos"
                                                        ></v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Malnutrición" value="Malnutrición"
                                                        ></v-checkbox>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Se encuentra recibiendo tratamiento con
                                                            Biológicos</strong>
                                                    </v-label>
                                                    <v-radio-group v-model="formSeguimiento.tratamiento_biologico">
                                                        <v-radio v-for="n in afirmacion" :key="n.id"
                                                            :label="`${n.nombre}`" :value="n.id" color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Se realiza Quimioterapia</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.quimioterapia">
                                                        <v-radio v-for="n in afirmacion" :key="n.id"
                                                            :label="`${n.nombre}`" :value="n.id" color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>


                                                <v-flex xs12 sm4 md4>
                                                    <v-menu v-model="menuFechaInicioSintomas"
                                                        :close-on-content-click="false" :nudge-right="40" lazy
                                                        transition="scale-transition" offset-y full-width
                                                        min-width="290px">
                                                        <template v-slot:activator="{ on }">
                                                            <v-text-field
                                                                v-model="formSeguimiento.fecha_inicio_sintomas"
                                                                label="Fecha de inicio de síntomas o de contacto desprotegido"
                                                                prepend-icon="event" readonly v-on="on" required></v-text-field>
                                                        </template>
                                                        <v-date-picker v-model="formSeguimiento.fecha_inicio_sintomas"
                                                            @input="menuFechaInicioSintomas = false"></v-date-picker>
                                                    </v-menu>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Requiere Seguimiento.</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.requiere_seguimiento">
                                                        <v-radio v-for="n in seguimientoAfirmacion" :key="n.id"
                                                            :label="`${n.nombre}`" :value="n.id" color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>
                                                <v-flex xs12 sm4 md4>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn color="red" dark @click="dialogDetalle = false">Cerrar
                                                        </v-btn>
                                                        <v-btn color="success" dark type="submit">Guardar</v-btn>
                                                    </v-card-actions>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </form>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </v-card>
        </v-flex>
    </v-layout>
</template>
<script>
    import {
        mapGetters
    } from "vuex";
    export default {
        name: "segumientoCovid",
        data() {
            return {
                tipomuestra: ['PCR', 'Antígeno', 'Serológicas'],
                search: "",
                dialogDetalle: false,
                listaSolicitudes: [],
                menuFechaRealizacion: false,
                menuFechaResultado: false,
                menuFechaIngreso: false,
                menuFechaSalida: false,
                menuFechaInicioSintomas: false,
                idSolicitud: null,
                afirmacion: [{
                    id: 1,
                    nombre: 'SI'
                }, {
                    id: 0,
                    nombre: 'NO'
                }],
                seguimientoAfirmacion: [{
                    id: 0,
                    nombre: 'NO'
                }, {
                    id: 1,
                    nombre: 'PMU'
                }, {
                    id: 2,
                    nombre: 'OXIMETRIAS'
                }],
                poblacion_riesgo: [],
                formSeguimiento: {
                    seguimiento_atencion_contingencia_id: null,
                    clasificacion_caso: null,
                    caso: null,
                    toma_muestra: null,
                    fecha_realizacion: null,
                    fecha_resultado: null,
                    resultado: null,
                    quien_toma_muestra: null,
                    quien_procesa_muestra: null,
                    resultado_muestra: null,
                    contacto_estrecho: null,
                    vacunacion_estacional_vigente: null,
                    antibioticos_ultima_semana: null,
                    fecha_ingreso: null,
                    nombre_institucion: null,
                    tipo_hospitalizacion: null,
                    fecha_salida: null,
                    estado_alta: null,
                    gestante: null,
                    poblacion_riesgo: [],
                    tratamiento_biologico: null,
                    quimioterapia: null,
                    fecha_inicio_sintomas: null,
                    requiere_seguimiento: null,
                    tipoMuestra: null,
                    requiere_hospitalizacion: null,
                    estado_id: 1
                },
                headers: [{
                        text: "# Solicitud",
                        value: "id"
                    },
                    {
                        text: "Paciente",
                        value: "paciente"
                    },
                    {
                        text: "Cédula",
                        value: "Num_Doc"
                    },
                    {
                        text: "Fecha Creacion",
                        value: "Fecha"
                    },
                    {
                        text: "IPS",
                        value: "IPS"
                    },
                    {
                        text: "Estado",
                        value: "estado"
                    },
                    {
                        text: "Usuario Crea",
                        value: "user_crea"
                    }, {
                        text: "",
                        value: ""
                    }
                ],
            }
        },
        computed: {
            ...mapGetters(['can']),
        },
        methods: {
            listarSolicitudes() {
                axios.get('/api/seguimiento/lista').then(res => {
                    this.listaSolicitudes = res.data;
                })
            },
            cargarDetalles(id) {
                this.formSeguimiento.seguimiento_atencion_contingencia_id = id;
                this.dialogDetalle = true;
            },
            guardarSeguimiento() {
                this.formSeguimiento.poblacion_riesgo = JSON.stringify(this.poblacion_riesgo);
                axios.post('/api/seguimiento/guardarSeguimiento', this.formSeguimiento).then(res => {
                    if (res.status === 200) {
                        this.clearData();
                        this.$alerSuccess(res.data.message);
                        this.listarSolicitudes();
                    }
                })
            },
            clearData() {
                this.poblacion_riesgo = [];
                for (const prop of Object.getOwnPropertyNames(this.formSeguimiento)) {
                    this.formSeguimiento[prop] = null;
                }
                this.dialogDetalle = false;
            }
        },
        mounted() {
            this.listarSolicitudes();
        }
    }

</script>
