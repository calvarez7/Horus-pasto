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
                        <td class="text-xs-left">{{ props.item.Num_Doc }}</td>
                        <td class="text-xs-left">{{ props.item.paciente }}</td>
                        <td class="text-xs-left">{{ props.item.IPS }}</td>
                        <td class="text-xs-left">{{ props.item.created_at }}</td>
                        <td>
                            <v-chip color="success" text-color="white">{{ props.item.estado }}</v-chip>
                        </td>
                        <td class="text-xs-left">{{ props.item.user_crea }}</td>
                        <td class="text-xs-left">
                            <v-btn round small color="info" @click="seguimientoCovi(props.item.id)">Ver seguimiento
                            </v-btn>
                        </td>

                    </template>
                </v-data-table>
                <v-dialog v-model="dialogDetalle" persistent max-width="800px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Detalle de registro</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <form @submit.prevent="guardarSeguimiento">
                                        <v-container>
                                            <v-layout row wrap>
                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Estado del Caso</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.clasificacion_caso">
                                                        <v-radio key="probable" label="Probable" value="probable"
                                                            color="info" required>
                                                        </v-radio>
                                                        <v-radio key="descartado" label="Descartado" value="descartado"
                                                            color="info" required></v-radio>
                                                        <v-radio key="confirmado" label="Confirmado" value="confirmado"
                                                            color="info" required></v-radio>
                                                    </v-radio-group>
                                                </v-flex>
                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Caso</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.caso">
                                                        <v-radio key="1" label="1" value="1" color="info" required>
                                                        </v-radio>
                                                        <v-radio key="2" label="2" value="2" color="info" required>
                                                        </v-radio>
                                                        <v-radio key="3,1" label="3,1" value="3,1" color="info"
                                                            required></v-radio>
                                                        <v-radio key="3,2" label="3,2" value="3,2" color="info"
                                                            required></v-radio>
                                                        <v-radio key="4" label="4" value="4" color="info" required>
                                                        </v-radio>
                                                        <v-radio key="5" label="5" value="5" color="info" required>
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

                                                <v-flex xs12 sm4 md4>
                                                    <v-menu v-model="menuFechaRealizacion"
                                                        :close-on-content-click="false" :nudge-right="40" lazy
                                                        transition="scale-transition" offset-y full-width
                                                        min-width="290px">
                                                        <template v-slot:activator="{ on }">
                                                            <v-text-field v-model="formSeguimiento.fecha_realizacion"
                                                                label="Fecha Realización" prepend-icon="event" readonly
                                                                v-on="on">
                                                            </v-text-field>
                                                        </template>
                                                        <v-date-picker v-model="formSeguimiento.fecha_realizacion"
                                                            @input="menuFechaRealizacion = false"></v-date-picker>
                                                    </v-menu>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-menu v-model="menuFechaResultado" :close-on-content-click="false"
                                                        :nudge-right="40" lazy transition="scale-transition" offset-y
                                                        full-width min-width="290px">
                                                        <template v-slot:activator="{ on }">
                                                            <v-text-field v-model="formSeguimiento.fecha_resultado"
                                                                label="Fecha Resultado" prepend-icon="event" readonly
                                                                v-on="on">
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
                                                            color="info" required>
                                                        </v-radio>
                                                        <v-radio key="positivo" label="Positivo" value="positivo"
                                                            color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Quien toma la muestra</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.quien_toma_muestra">
                                                        <v-radio key="sumimedical" label="Sumimedical"
                                                            value="sumimedical" color="info" required></v-radio>
                                                        <v-radio key="ips_externa" label="IPS Externa"
                                                            value="ips_externa" color="info" required></v-radio>
                                                        <v-radio key="secretaria_salud" label="Secretaria de Salud"
                                                            value="secretaria_salud" color="info" required></v-radio>
                                                    </v-radio-group>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Quien procesa la muestra</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.quien_procesa_muestra">
                                                        <v-radio key="sumimedical" label="Sumimedical"
                                                            value="sumimedical" color="info" required></v-radio>
                                                        <v-radio key="ips_externa" label="IPS Externa"
                                                            value="ips_externa" color="info" required></v-radio>
                                                        <v-radio key="secretaria_salud" label="Secretaria de Salud"
                                                            value="secretaria_salud" color="info" required></v-radio>
                                                    </v-radio-group>
                                                </v-flex>

                                                <v-flex xs12 sm12 md12>
                                                    <v-textarea v-model="formSeguimiento.resultado_muestra"
                                                        name="input-7-1" label=" Resultado de Muestra"
                                                        hint="Descripción Resultado de Muestra">
                                                    </v-textarea>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Tuvo contacto estrecho en los últimos 14
                                                            días?</strong></v-label>
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
                                                            required></v-radio>
                                                        <v-radio key="UCE" label="UCE" value="uce" color="info"
                                                            required></v-radio>
                                                        <v-radio key="Hospitalizacion" label="Hospitalizacion"
                                                            value="hospitalizacion" color="info" required></v-radio>
                                                        <v-radio key="Urgencias" label="Urgencias" value="urgencia"
                                                            color="info" required></v-radio>
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
                                                            required></v-radio>
                                                        <v-radio key="Fallecido" label="Fallecido" value="fallecido"
                                                            color="info" required></v-radio>
                                                    </v-radio-group>
                                                </v-flex>

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
                                                        value="Riesgo Cardiocerebrovascular Metabólicas" required>
                                                    </v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Anticoagulados" value="Anticoagulados" required>
                                                    </v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Oncológicos" value="Oncológicos" required></v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Salud Mental" value="Salud Mental" required></v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Reumatológicos" value="Reumatológicos" required>
                                                    </v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Infectocontagiosos" value="Infectocontagiosos" required>
                                                    </v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Nefroprotección" value="Nefroprotección" required>
                                                    </v-checkbox>
                                                    <v-checkbox v-model="poblacion_riesgo" color="info"
                                                        label="Respiratorios Crónicos" value="Respiratorios Crónicos"
                                                        required></v-checkbox>
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
                                                                label="Fecha de Inicio de los síntomas"
                                                                prepend-icon="event" readonly v-on="on"></v-text-field>
                                                        </template>
                                                        <v-date-picker v-model="formSeguimiento.fecha_inicio_sintomas"
                                                            @input="menuFechaInicioSintomas = false"></v-date-picker>
                                                    </v-menu>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Requiere Seguimiento.</strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.requiere_seguimiento">
                                                        <v-radio v-for="n in afirmacion" :key="n.id"
                                                            :label="`${n.nombre}`" :value="n.id" color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Requiere Seguimiento a Domicilio con
                                                            Oximetrías</strong></v-label>
                                                    <v-radio-group
                                                        v-model="formSeguimiento.requiere_seguimiento_oximetrias">
                                                        <v-radio v-for="n in afirmacion" :key="n.id"
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
                <v-dialog v-model="dialogverCovi" persistent max-width="1600px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Seguimientos del paciente</span>
                        </v-card-title>
                        <v-card-text>
                            <v-layout>
                                <v-expansion-panel>
                                    <v-expansion-panel-content v-for="(item, index) in coviseguimientos" :key="index">
                                        <template v-slot:header>
                                            <span><b># Seguimiento:</b> {{item.id}}</span>
                                            <span><b>Realizado por:</b> {{item.user_crea}}</span>
                                            <span><b>fecha seguimiento:</b> {{item.created_at}}</span>
                                        </template>
                                        <v-card>
                                            <v-layout row wrap>
                                                <v-flex xs12 md2 v-if="item.contacto_fallido != null">
                                                    <v-text-field class="px-2" v-if="item.contacto_fallido == '0'"
                                                        value="Atendido" label="contacto:" readonly>
                                                    </v-text-field>
                                                    <v-text-field class="px-2" v-if="item.contacto_fallido == '1'"
                                                        value="No contesta" label="contacto:" readonly>
                                                    </v-text-field>
                                                    <v-text-field class="px-2" v-if="item.contacto_fallido == '2'"
                                                        value="Teléfono errado" label="contacto:" readonly>
                                                    </v-text-field>
                                                    <v-text-field class="px-2" v-if="item.contacto_fallido == '3'"
                                                        value="No se encuentra en residencia" label="contacto:"
                                                        readonly>
                                                    </v-text-field>
                                                    <v-text-field class="px-2" v-if="item.contacto_fallido == '4'"
                                                        value="Otros" label="contacto:" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.clasificacion_caso != null">
                                                    <v-text-field class="px-2" v-model="item.clasificacion_caso"
                                                        label="clasificacion_caso:" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.caso != null">
                                                    <v-text-field class="px-2" v-model="item.caso" label="caso:"
                                                        readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.toma_muestra != null">
                                                    <v-text-field class="px-2" v-if="item.toma_muestra == '0'"
                                                        value="No" label="Toma muestra:" readonly>
                                                    </v-text-field>
                                                    <v-text-field class="px-2" v-if="item.toma_muestra == '1'"
                                                        value="Si" label="Toma muestra:" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fecha_realizacion != null">
                                                    <v-text-field class="px-2" v-model="item.fecha_realizacion"
                                                        label="fecha_realizacion:" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fecha_resultado != null">
                                                    <v-text-field class="px-2" v-model="item.fecha_resultado"
                                                        label="fecha_resultado" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fecha_inicio_sintomas != null">
                                                    <v-text-field class="px-2" v-model="item.fecha_inicio_sintomas"
                                                        label="inicio sintomas" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.resultado != null">
                                                    <v-text-field class="px-2" v-model="item.resultado"
                                                        label="resultado" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.quien_toma_muestra != null">
                                                    <v-text-field class="px-2" v-model="item.quien_toma_muestra"
                                                        label="quien_toma_muestra" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.quien_procesa_muestra != null">
                                                    <v-text-field class="px-2" v-model="item.quien_procesa_muestra"
                                                        label="quien_procesa_muestra" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.resultado_muestra != null">
                                                    <v-text-field class="px-2" v-model="item.resultado_muestra"
                                                        label="resultado_muestra" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.requiere_hospitalizacion != null">
                                                    <v-text-field class="px-2" v-model="item.requiere_hospitalizacion"
                                                        label="requiere_hospitalizacion" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fecha_ingreso != null">
                                                    <v-text-field class="px-2" v-model="item.fecha_ingreso"
                                                        label="fecha_ingreso" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.nombre_institucion != null">
                                                    <v-text-field class="px-2" v-model="item.nombre_institucion"
                                                        label="nombre_institucion" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.tipo_hospitalizacion != null">
                                                    <v-text-field class="px-2" v-model="item.tipo_hospitalizacion"
                                                        label="tipo_hospitalizacion" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fecha_salida != null">
                                                    <v-text-field class="px-2" v-model="item.fecha_salida"
                                                        label="fecha_salida" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.estado_alta != null">
                                                    <v-text-field class="px-2" v-model="item.estado_alta"
                                                        label="estado_alta" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.asintomatico != null">
                                                    <v-text-field class="px-2" v-model="item.asintomatico"
                                                        label="asintomatico" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fiebre_mayor38 != null">
                                                    <v-text-field class="px-2" v-model="item.fiebre_mayor38"
                                                        label="fiebre_mayor38" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.tos != null">
                                                    <v-text-field class="px-2" v-model="item.tos" label="tos" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.odinofagia != null">
                                                    <v-text-field class="px-2" v-model="item.odinofagia"
                                                        label="odinofagia" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.alteracion_olfato != null">
                                                    <v-text-field class="px-2" v-model="item.alteracion_olfato"
                                                        label="alteracion_olfato" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.alteracion_gusto != null">
                                                    <v-text-field class="px-2" v-model="item.alteracion_gusto"
                                                        label="alteracion_gusto" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.anorexia != null">
                                                    <v-text-field class="px-2" v-model="item.anorexia" label="anorexia"
                                                        readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.cefalea != null">
                                                    <v-text-field class="px-2" v-model="item.cefalea" label="cefalea"
                                                        readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fatiga_adinamia != null">
                                                    <v-text-field class="px-2" v-model="item.fatiga_adinamia"
                                                        label="fatiga_adinamia" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.dificultad_espiratoria != null">
                                                    <v-text-field class="px-2" v-model="item.dificultad_espiratoria"
                                                        label="dificultad_espiratoria" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.somnolencia != null">
                                                    <v-text-field class="px-2" v-model="item.somnolencia"
                                                        label="somnolencia" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.expectoracion != null">
                                                    <v-text-field class="px-2" v-model="item.expectoracion"
                                                        label="expectoracion" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.vomito_diarrea_intratable != null">
                                                    <v-text-field class="px-2" v-model="item.vomito_diarrea_intratable"
                                                        label="vomito_diarrea_intratable" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.paciente_adomicilio != null">
                                                    <v-text-field class="px-2" v-model="item.paciente_adomicilio"
                                                        label="paciente_adomicilio" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.frecuencia_respiratoria != null">
                                                    <v-text-field class="px-2" v-model="item.frecuencia_respiratoria"
                                                        label="frecuencia_respiratoria" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.saturacion_oxigeno != null">
                                                    <v-text-field class="px-2" v-model="item.saturacion_oxigeno"
                                                        label="saturacion_oxigeno" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.presion_sistolica != null">
                                                    <v-text-field class="px-2" v-model="item.presion_sistolica"
                                                        label="presion_sistolica" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.pulso_minuto != null">
                                                    <v-text-field class="px-2" v-model="item.pulso_minuto"
                                                        label="pulso_minuto" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.temperatura != null">
                                                    <v-text-field class="px-2" v-model="item.temperatura"
                                                        label="temperatura" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.requiere_oxigenoterapia != null">
                                                    <v-text-field class="px-2" v-model="item.requiere_oxigenoterapia"
                                                        label="requiere_oxigenoterapia" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.oxigeno_indicado != null">
                                                    <v-text-field class="px-2" v-model="item.oxigeno_indicado"
                                                        label="oxigeno_indicado" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.destino_paciente != null">
                                                    <v-text-field class="px-2" v-model="item.destino_paciente"
                                                        label="destino_paciente" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.suguimiento != null">
                                                    <v-text-field class="px-2" v-model="item.suguimiento"
                                                        label="suguimiento" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.frecuencia != null">
                                                    <v-text-field class="px-2" v-model="item.frecuencia"
                                                        label="frecuencia" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.tipoMuestra != null">
                                                    <v-text-field class="px-2" v-model="item.tipoMuestra"
                                                        label="tipoMuestra" readonly></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-card>
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-layout>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" dark @click="dialogverCovi = false">Cerrar</v-btn>
                        </v-card-actions>
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
                coviseguimientos: [],
                search: "",
                dialogDetalle: false,
                dialogverCovi: false,
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
                    requiere_seguimiento_oximetrias: null,
                    estado_id: 1
                },
                headers: [{
                        text: "# Solicitud",
                        value: "id"
                    },
                    {
                        text: "Cédula",
                        value: "Num_Doc"
                    },
                    {
                        text: "Paciente",
                        value: "paciente"
                    },
                    {
                        text: "IPS",
                        value: "IPS"
                    },
                    {
                        text: "Fecha Creacion",
                        value: "Fecha"
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
            seguimientoCovi(id) {
                axios.get('/api/seguimiento/verseguimientos/' + id).then(res => {
                    this.dialogverCovi = true;
                    this.coviseguimientos = res.data;
                })
            },
            listarSolicitudes() {
                axios.get('/api/seguimiento/pacienteAlta').then(res => {
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
