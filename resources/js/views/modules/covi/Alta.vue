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
                        <td>{{ props.item.registro_id }}</td>
                        <td>{{ props.item.Primer_Nom }} {{ props.item.SegundoNom }} {{ props.item.Primer_Ape }}
                            {{ props.item.Segundo_Ape }}</td>
                        <td>{{ props.item.Num_Doc }}</td>
                        <td>{{ props.item.fecharegistro }}</td>
                        <td>
                            <v-chip color="warning" text-color="white">{{ props.item.estado }}</v-chip>
                        </td>
                        <td>{{ props.item.name }} {{ props.item.apellido }}</td>
                        <td class="text-xs-left">
                            <v-btn round color="success" @click="imprimir(props.item.registro_id,'ficha')">Ficha</v-btn>
                        </td>
                        <!-- <td>
                            <v-btn depressed small round color="success"
                                @click="cargarDetalles(props.item.registro_id)">Generar
                                Seguimiento</v-btn>
                        </td> -->
                        <td>
                            <v-btn round small color="info" @click="verseguimiento(props.item.registro_id)">Ver
                                seguimiento</v-btn>
                        </td>

                    </template>
                </v-data-table>
                <v-dialog v-model="dialogDetalle" persistent max-width="1900px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Seguimiento del paciente</span>
                        </v-card-title>
                        <v-card-text>
                            <form @submit.prevent="guardarSeguimientos">
                                <v-container fluid>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-select :items="contacto" label="El contacto fue"
                                                v-model="formSeguimiento.contacto_fue"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex v-if="formSeguimiento.contacto_fue == 'EFECTIVO'">
                                            <v-select :items="sino" label="Actualizar información del paciente?"
                                                v-model="formSeguimiento.actualizar_info_hospitalizaciones_paciente">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.actualizar_info_hospitalizaciones_paciente == 'SI'">
                                            <v-text-field label="Institución donde se realiza la hospitalización"
                                                v-model="formSeguimiento.institucion_realiza_hospitalizacion">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.actualizar_info_hospitalizaciones_paciente == 'SI'">
                                            <v-select :items="hospitalizacion" label="Tipo de Hospitalización "
                                                v-model="formSeguimiento.tipo_hospitalizacion">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.actualizar_info_hospitalizaciones_paciente == 'SI'">
                                            <v-select :items="ventilatorio" label="Soporte ventilatorio"
                                                v-model="formSeguimiento.soporte_ventilatorio">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.actualizar_info_hospitalizaciones_paciente == 'SI'">
                                            <v-select :items="sino" label="Soporte Hemodinámico"
                                                v-model="formSeguimiento.soporte_hemodinamico">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.actualizar_info_hospitalizaciones_paciente == 'SI'">
                                            <v-text-field label="Fecha de Ingreso a la hospitalización"
                                                v-model="formSeguimiento.fecha_ingreso_hospitalizacion" type="date">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex v-if="formSeguimiento.contacto_fue != 'EFECTIVO'">
                                            <v-select :items="sino" label="Actualmente se encuentra hospitalizado"
                                                v-model="formSeguimiento.se_encuentra_hospitalizado">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.se_encuentra_hospitalizado == 'NO'">
                                            <v-text-field label="Fecha de egreso a la hospitalización"
                                                v-model="formSeguimiento.fecha_egreso_hospitalizacion" type="date">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.se_encuentra_hospitalizado == 'NO'">
                                            <v-select :items="alta" label="Estado al alta"
                                                v-model="formSeguimiento.estado_alta">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex v-if="formSeguimiento.contacto_fue != 'EFECTIVO'">
                                            <v-select :items="sino" label="Paciente con Síntomas"
                                                v-model="formSeguimiento.paciente_sintomas"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.paciente_sintomas == 'SI'">
                                            <v-select :items="sino"
                                                label="Ha presentado las últimas horas fiebre cuantificada ≥ 38°C"
                                                v-model="formSeguimiento.ha_presentado_fiebre">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.paciente_sintomas == 'SI'">
                                            <v-select :items="sino" label="Ha presentado las últimas horas tos"
                                                v-model="formSeguimiento.ha_presentado_tos"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.paciente_sintomas == 'SI'">
                                            <v-select :items="sino"
                                                label="Ha presentado las últimas horas dificultad respiratoria"
                                                v-model="formSeguimiento.ha_presentado_dificultad_respiratoria">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.paciente_sintomas == 'SI'">
                                            <v-select :items="sino"
                                                label="Ha presentado las últimas horas dolor de garganta"
                                                v-model="formSeguimiento.ha_presentado_dolor_garganta">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.paciente_sintomas == 'SI'">
                                            <v-select :items="sino" label="Ha presentado las últimas horas fatiga"
                                                v-model="formSeguimiento.ha_presentado_fatiga">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.paciente_sintomas == 'SI'">
                                            <v-select :items="sino" label="Ha presentado las últimas horas cefalea"
                                                v-model="formSeguimiento.ha_presentado_cefalea">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.paciente_sintomas == 'SI'">
                                            <v-select :items="sino" label="Qué otros síntomas ha presentado"
                                                v-model="formSeguimiento.que_otros_sintomas"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex v-if="formSeguimiento.contacto_fue != 'EFECTIVO'">
                                            <v-select :items="sino" label="Ha presentado signos o síntomas de alarma"
                                                v-model="formSeguimiento.ha_presentado_sintomas_alarma">
                                            </v-select>
                                        </v-flex>
                                        <!-- signos o síntomas de alarma -->
                                        <v-container fluid
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.paciente_sintomas == 'SI'">
                                            <v-label>Seleccione signos o síntomas de alarma?</v-label>
                                            <v-layout row wrap>
                                                <v-flex xs12 sm4 md3>
                                                    <v-checkbox v-model="formSeguimiento.detalles_signos_alarmas"
                                                        label="detalles signos alarmas" color="red" hide-details>
                                                    </v-checkbox>
                                                    <v-checkbox v-model="formSeguimiento.respiracion_rapida_taquipnea"
                                                        label="respiracion rapida taquipnea" color="red" hide-details>
                                                    </v-checkbox>
                                                    <v-checkbox v-model="formSeguimiento.fiebre_mas_2_dias"
                                                        label="fiebre mas 2 dias" color="indigo" hide-details>
                                                    </v-checkbox>
                                                    <v-checkbox v-model="formSeguimiento.pecho_suena_sibilancias"
                                                        label="pecho suena sibilancias" color="indigo" hide-details>
                                                    </v-checkbox>
                                                </v-flex>
                                                <v-flex xs12 sm4 md3>
                                                    <v-checkbox v-model="formSeguimiento.somnolencia_letargia"
                                                        label="somnolencia letargia" color="orange" hide-details>
                                                    </v-checkbox>
                                                    <v-checkbox v-model="formSeguimiento.convulsiones"
                                                        label="convulsiones" color="orange" hide-details>
                                                    </v-checkbox>
                                                    <v-checkbox
                                                        v-model="formSeguimiento.deterioro_rapido_estado_general"
                                                        label="deterioro rapido estado general" color="primary"
                                                        hide-details></v-checkbox>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.contacto_fue != 'EFECTIVO' || formSeguimiento.paciente_sintomas == 'NO'">
                                            <v-select :items="sino" label="Se encuentra registrado en SEGCOVID"
                                                v-model="formSeguimiento.registrado_segcovid"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-select :items="sino"
                                                label="Le han realizado pruebas para Diagnóstico de Covid"
                                                v-model="formSeguimiento.pruebas_diagnostico_covid">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.pruebas_diagnostico_covid == 'SI'">
                                            <v-select :items="pruebadiagnostica"
                                                label="Tipo prueba diagnóstica para Covid-19"
                                                v-model="formSeguimiento.tipo_prueba_diagnostica_covid">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.pruebas_diagnostico_covid == 'SI'">
                                            <v-text-field label="fecha de realización de la prueba Covid-19"
                                                v-model="formSeguimiento.fecha_realizacion_prueba" type="date">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.pruebas_diagnostico_covid == 'SI'">
                                            <v-text-field label="fecha de recepción de la muestra"
                                                v-model="formSeguimiento.fecha_recepcion_prueba" type="date">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.pruebas_diagnostico_covid == 'SI'">
                                            <v-select :items="ipsprueba" label="IPS que toma la muestra"
                                                v-model="formSeguimiento.ips_toma_muestra"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-select :items="resultado"
                                                label="Resultado de Pruebas diagnósticas Covid-19"
                                                v-model="formSeguimiento.resultado_pruebas"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.resultado_pruebas == 'POSITIVO' || formSeguimiento.resultado_pruebas == 'NEGATIVO'">
                                            <v-text-field label="la fecha de reporte de resultado"
                                                v-model="formSeguimiento.fecha_reporte_resultado_covid" type="date">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.resultado_pruebas == 'POSITIVO' || formSeguimiento.resultado_pruebas == 'NEGATIVO'">
                                            <v-select :items="ipsprocesa" label="IPS que procesa la muestra"
                                                v-model="formSeguimiento.ips_procesa_muestra"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.ips_procesa_muestra == 'OTRA IPS'">
                                            <v-text-field label="Cual IPS?" v-model="formSeguimiento.otra_ips">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex
                                            v-if="formSeguimiento.ips_procesa_muestra == 'OTRA IPS'">
                                            <v-text-field label="Número de nit de otras ips "
                                                v-model="formSeguimiento.numero_nit"></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm12 d-flex>
                                            <v-textarea name="input-7-1" label="Observaciones"
                                                v-model="formSeguimiento.observaciones"></v-textarea>
                                        </v-flex>
                                        <v-flex xs12 sm4>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="success" dark type="submit">Guardar Seguimiento
                                                </v-btn>
                                                <v-btn color="warning" dark @click="dardealta()">Dar de Alta
                                                </v-btn>
                                                <v-btn color="red" dark @click="dialogDetalle = false">Cerrar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </form>
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
                                    <v-expansion-panel-content v-for="(item, index) in covisuguimientos" :key="index">
                                        <template v-slot:header>
                                            <span><b># Seguimiento:</b> {{item.id}}</span>
                                            <span><b>Realizado por:</b> {{item.user_crea}}</span>
                                            <span><b>fecha seguimiento:</b> {{item.created_at}}</span>
                                        </template>
                                        <v-card>
                                            <v-layout row wrap>
                                                <v-flex xs12 sm3 d-flex v-if="item.contacto_fue != null">
                                                    <v-select readonly :items="contacto" label="El contacto fue"
                                                        v-model="item.contacto_fue"></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex
                                                    v-if="item.actualizar_info_hospitalizaciones_paciente != null">
                                                    <v-select readonly :items="sino"
                                                        label="Actualizar información del paciente?"
                                                        v-model="item.actualizar_info_hospitalizaciones_paciente">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex
                                                    v-if="item.institucion_realiza_hospitalizacion != null">
                                                    <v-text-field readonly
                                                        label="Institución donde se realiza la hospitalización"
                                                        v-model="item.institucion_realiza_hospitalizacion">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.tipo_hospitalizacion != null">
                                                    <v-select readonly :items="hospitalizacion"
                                                        label="Tipo de Hospitalización "
                                                        v-model="item.tipo_hospitalizacion">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.soporte_ventilatorio != null">
                                                    <v-select readonly :items="ventilatorio"
                                                        label="Soporte ventilatorio"
                                                        v-model="item.soporte_ventilatorio">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.soporte_hemodinamico != null">
                                                    <v-select readonly :items="sino" label="Soporte Hemodinámico"
                                                        v-model="item.soporte_hemodinamico">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex
                                                    v-if="item.fecha_ingreso_hospitalizacion != null">
                                                    <v-text-field readonly label="Fecha de Ingreso a la hospitalización"
                                                        v-model="item.fecha_ingreso_hospitalizacion" type="date">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.se_encuentra_hospitalizado != null">
                                                    <v-select readonly :items="sino"
                                                        label="Actualmente se encuentra hospitalizado"
                                                        v-model="item.se_encuentra_hospitalizado">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex
                                                    v-if="item.fecha_egreso_hospitalizacion != null">
                                                    <v-text-field readonly label="Fecha de egreso a la hospitalización"
                                                        v-model="item.fecha_egreso_hospitalizacion" type="date">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.estado_alta != null">
                                                    <v-select readonly :items="alta" label="Estado al alta"
                                                        v-model="item.estado_alta">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.paciente_sintomas != null">
                                                    <v-select readonly :items="sino" label="Paciente con Síntomas"
                                                        v-model="item.paciente_sintomas"></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex>
                                                    <v-text-field label="Saruración" readonly type="number"
                                                        v-model="item.saturacion">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex>
                                                    <v-text-field label="Pulso" readonly type="number" v-model="item.pulso">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex>
                                                    <v-text-field label="Temperatura" readonly v-model="item.temperatura">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex>
                                                    <v-text-field label="Tensión Arterial" readonly
                                                        v-model="item.tensionarterial">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex>
                                                    <v-text-field label="Frecuencia Respiratoria" readonly
                                                        v-model="item.frecuenciarespiratoria">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex>
                                                    <v-select :items="escalanews2" label="Escala News 2" readonly
                                                        v-model="item.escalanews2">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.ha_presentado_fiebre != null">
                                                    <v-select readonly :items="sino"
                                                        label="Ha presentado las últimas horas fiebre cuantificada ≥ 38°C"
                                                        v-model="item.ha_presentado_fiebre">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.ha_presentado_tos != null">
                                                    <v-select readonly :items="sino"
                                                        label="Ha presentado las últimas horas tos"
                                                        v-model="item.ha_presentado_tos"></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex
                                                    v-if="item.ha_presentado_dificultad_respiratoria != null">
                                                    <v-select readonly :items="sino"
                                                        label="Ha presentado las últimas horas dificultad respiratoria"
                                                        v-model="item.ha_presentado_dificultad_respiratoria">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex
                                                    v-if="item.ha_presentado_dolor_garganta != null">
                                                    <v-select readonly :items="sino"
                                                        label="Ha presentado las últimas horas dolor de garganta"
                                                        v-model="item.ha_presentado_dolor_garganta">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.ha_presentado_fatiga != null">
                                                    <v-select readonly :items="sino"
                                                        label="Ha presentado las últimas horas fatiga"
                                                        v-model="item.ha_presentado_fatiga">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.ha_presentado_cefalea != null">
                                                    <v-select readonly :items="sino"
                                                        label="Ha presentado las últimas horas cefalea"
                                                        v-model="item.ha_presentado_cefalea">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.que_otros_sintomas != null">
                                                    <v-select readonly :items="sino"
                                                        label="Qué otros síntomas ha presentado"
                                                        v-model="item.que_otros_sintomas"></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex
                                                    v-if="item.ha_presentado_sintomas_alarma != null">
                                                    <v-select readonly :items="sino"
                                                        label="Ha presentado signos o síntomas de alarma"
                                                        v-model="item.ha_presentado_sintomas_alarma">
                                                    </v-select>
                                                </v-flex>
                                                <!-- signos o síntomas de alarma -->
                                                <v-container fluid>
                                                    <v-label>Seleccione signos o síntomas de alarma?</v-label>
                                                    <v-layout row wrap>
                                                        <v-flex xs12 sm4 md3>
                                                            <v-checkbox v-if="item.detalles_signos_alarmas != 0"
                                                                v-model="item.detalles_signos_alarmas"
                                                                label="detalles signos alarmas" color="red" hide-details
                                                                readonly>
                                                            </v-checkbox>
                                                            <v-checkbox v-if="item.respiracion_rapida_taquipnea != 0"
                                                                v-model="item.respiracion_rapida_taquipnea"
                                                                label="respiracion rapida taquipnea" color="red"
                                                                hide-details readonly>
                                                            </v-checkbox>
                                                            <v-checkbox v-if="item.fiebre_mas_2_dias != 0"
                                                                v-model="item.fiebre_mas_2_dias"
                                                                label="fiebre mas 2 dias" color="indigo" hide-details
                                                                readonly>
                                                            </v-checkbox>
                                                            <v-checkbox v-if="item.pecho_suena_sibilancias != 0"
                                                                v-model="item.pecho_suena_sibilancias"
                                                                label="pecho suena sibilancias" color="indigo"
                                                                hide-details readonly>
                                                            </v-checkbox>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md3>
                                                            <v-checkbox v-if="item.somnolencia_letargia != 0"
                                                                v-model="item.somnolencia_letargia"
                                                                label="somnolencia letargia" color="orange" hide-details
                                                                readonly>
                                                            </v-checkbox>
                                                            <v-checkbox v-if="item.convulsiones != 0"
                                                                v-model="item.convulsiones" label="convulsiones"
                                                                color="orange" hide-details readonly>
                                                            </v-checkbox>
                                                            <v-checkbox v-if="item.deterioro_rapido_estado_general != 0"
                                                                v-model="item.deterioro_rapido_estado_general"
                                                                label="deterioro rapido estado general" color="primary"
                                                                hide-details readonly></v-checkbox>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                                <v-flex xs12 sm3 d-flex v-if="item.registrado_segcovid != null">
                                                    <v-select readonly :items="sino"
                                                        label="Se encuentra registrado en SEGCOVID"
                                                        v-model="item.registrado_segcovid"></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.pruebas_diagnostico_covid != null">
                                                    <v-select readonly :items="sino"
                                                        label="Le han realizado pruebas para Diagnóstico de Covid"
                                                        v-model="item.pruebas_diagnostico_covid">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex
                                                    v-if="item.tipo_prueba_diagnostica_covid != null">
                                                    <v-select readonly :items="pruebadiagnostica"
                                                        label="Tipo prueba diagnóstica para Covid-19"
                                                        v-model="item.tipo_prueba_diagnostica_covid">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.fecha_realizacion_prueba != null">
                                                    <v-text-field readonly
                                                        label="fecha de realización de la prueba Covid-19"
                                                        v-model="item.fecha_realizacion_prueba" type="date">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.fecha_recepcion_prueba != null">
                                                    <v-text-field readonly label="fecha de recepción de la muestra"
                                                        v-model="item.fecha_recepcion_prueba" type="date">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.ips_toma_muestra != null">
                                                    <v-select readonly :items="ipsprueba"
                                                        label="IPS que toma la muestra" v-model="item.ips_toma_muestra">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.resultado_pruebas != null">
                                                    <v-select readonly :items="resultado"
                                                        label="Resultado de Pruebas diagnósticas Covid-19"
                                                        v-model="item.resultado_pruebas"></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex
                                                    v-if="item.fecha_reporte_resultado_covid != null">
                                                    <v-text-field readonly label="la fecha de reporte de resultado"
                                                        v-model="item.fecha_reporte_resultado_covid" type="date">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.ips_procesa_muestra != null">
                                                    <v-select readonly :items="ipsprocesa"
                                                        label="IPS que procesa la muestra"
                                                        v-model="item.ips_procesa_muestra"></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.otra_ips != null">
                                                    <v-text-field readonly label="Cual IPS?" v-model="item.otra_ips">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex v-if="item.numero_nit != null">
                                                    <v-text-field label="Número de nit de otras ips "
                                                        v-model="item.numero_nit" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex>
                                                    <v-select :items="causaseguimiento" label="CAUSA DEL SEGUIMIENTO"
                                                        v-model="item.causaseguimiento" readonly></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex>
                                                    <v-select :items="tiposeguimiento" label="TIPO DE SEGUIMIENTO"
                                                        v-model="item.tiposeguimiento" readonly></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm3 d-flex>
                                                    <v-text-field label="Call Score" readonly v-model="item.callscore">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm12 d-flex v-if="item.observaciones != null">
                                                    <v-textarea name="input-7-1" label="Observaciones"
                                                        v-model="item.observaciones" readonly></v-textarea>
                                                </v-flex>
                                                <!-- ¿INDICACIONES? -->
                                                <v-label>¿INDICACIONES?
                                                </v-label>
                                                <v-container fluid>
                                                    <v-layout row wrap>
                                                        <v-flex xs12 sm4 md3>
                                                            <v-checkbox v-model="item.esperarencasa"
                                                                label="Se Recomienda Esperar En Casa Y Se Informa Signos Y Sintomas De Alarma"
                                                                color="black" value="x" hide-details readonly>
                                                            </v-checkbox>
                                                            <v-checkbox v-model="item.asignarconsulta"
                                                                label="Se Asignará Consulta Médica" color="red"
                                                                value="x" hide-details readonly>
                                                            </v-checkbox>
                                                            <v-checkbox v-model="item.seguimientotelefonico" readonly
                                                                label="Se Realizará Seguimiento Telefónico Para Confirmación De Estado De Salud Por Parte Del Personal Médico Del Contratista"
                                                                color="blue" value="x" hide-details>
                                                            </v-checkbox>
                                                            <v-checkbox v-model="item.consultaprioritaria"
                                                                label="Se Requiere Consulta Prioritaria" color="indigo"
                                                                value="x" hide-details readonly>
                                                            </v-checkbox>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md3>
                                                            <v-checkbox v-model="item.teleorientacion"
                                                                label="Teleorientación Salud Mental" color="orange"
                                                                value="x" hide-details readonly>
                                                            </v-checkbox>
                                                            <v-checkbox v-model="item.otrasindicaciones" label="Otros"
                                                                readonly color="green" value="x" hide-details>
                                                            </v-checkbox>
                                                            <v-checkbox v-model="item.concentracionoxigeno"
                                                                label="Concentrador de Oxígeno" readonly color="pink"
                                                                value="x" hide-details>
                                                            </v-checkbox>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-layout>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="warning" dark red @click="imprimirSeguimiento(item.id,'seguimiento')">Imprimir Seguimiento</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-layout>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" dark red @click="dialogverCovi = false">Cerrar</v-btn>
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
                escalanews2: ['Riesgo Bajo', 'Riesgo Bajo Medio', 'Riesgo Medio y Ato'],
                 tiposeguimiento: ['AUDITORIA CONCURRENTE', 'SEGUIMIENTO TELEFONICO', 'VISITA DOMICILIARIA',
                    'TELECONSULTA', 'NINGUNO'
                ],
                causaseguimiento: ['CASO SOSPECHOSO', 'CASO CONFIRMADO', 'CASO PROBABLE', 'EGRESO HOSPITALARIO'],
                contacto: [
                    'EFECTIVO', 'FALLIDO PORQUE NO CONTESTA',
                    'FALLIDO PORQUE NO SE ENCUENTRA EN EL LUGAR DE RESIDENCIA',
                    'FALLIDO POR TELÉFONO ERRADO', 'FALLIDO POR OTRAS CAUSAS'
                ],
                sino: ['SI', 'NO'],
                hospitalizacion: ['UCI', 'UCE', 'PISO', 'URGENCIAS'],
                ventilatorio: ['CANULA NASAL', 'MASCARA', 'VENTURY', 'INTUBACION OROTRAQUEAL', 'CPAP', 'TRAQUEOSTOMIA',
                    'SIN SOPORTE'
                ],
                alta: ['VIVO', 'FALLECIDO'],
                signosalarma: [
                    'Detalles de Signos de Alarmas. ',
                    'Respiración rápida-taquipnea',
                    'Fiebre por más de 2 días',
                    'Pecho que suena, sibilancias, estertores',
                    'Somnolencia o letargia',
                    'Convulsiones',
                    'Deterioro rápido del estado en general'
                ],
                pruebadiagnostica: ['PCR', 'ANTÍGENO', 'SEROLÓGICAS'],
                ipsprueba: ['SUMIMEDICAL', 'SECRETARIAS DE SALUD', 'OTRAS IPS'],
                resultado: ['PENDIENTE', 'POSITIVO', 'NEGATIVO'],
                tipomuestra: ['PCR', 'Antígeno', 'Serológicas'],
                ipsprocesa: ['LIME. 890.980.040-8', 'SUMIMEDICAL. 890.980.040-8',
                    'FUNDACION UNIVERSITARIA SAN VICENTE DE PAUL. 890.900.518-4',
                    'SYNLAB. 800.087.565-5', 'INSTITUTO NACIONAL DE SALUD. 899.999.403-4', 'ADILAB. 900.341.857-2',
                    'COLCAN. 800.066.001-3 1', 'LABORATORIO CLÍNICA SOMER. 890.939.936-9',
                    'LABORATORIO DEPARTAMENTAL DE SALUD PUBLICA DE ANTIOQUIA. 890.900.286-0',
                    'LABORATORIO DEPARTAMENTAL DE SALUD PUBLICA DE CHOCO. 891.680.010-3',
                    'LABORATORIO DEPARTAMENTAL DE SALUD PUBLICA DE SANTANDER. 890.201.235-6',
                    'LABORATORIO ECHAVARRIA. 890.906.793-0',
                    'LABORATORIO SANIDAD DE LA POLICIA NACIONAL. 830.041.314-4',
                    'LABORATORIO HOSPITAL PABLO TOBON URIBE', 'OTRA IPS',
                ],
                search: "",
                dialogDetalle: false,
                dialogverCovi: false,
                covisuguimientos: [],
                listaSolicitudes: [],
                idSolicitud: null,
                poblacion_riesgo: [],
                formSeguimiento: {
                    registrocovi_id: null,
                    contacto_fue: null,
                    institucion_realiza_hospitalizacion: null,
                    institucion_realiza_hospitalizacion: null,
                    tipo_hospitalizacion: null,
                    soporte_ventilatorio: null,
                    soporte_hemodinamico: null,
                    fecha_ingreso_hospitalizacion: null,
                    se_encuentra_hospitalizado: null,
                    fecha_egreso_hospitalizacion: null,
                    estado_alta: null,
                    paciente_sintomas: null,
                    ha_presentado_fiebre: null,
                    ha_presentado_tos: null,
                    ha_presentado_dificultad_respiratoria: null,
                    ha_presentado_dolor_garganta: null,
                    ha_presentado_fatiga: null,
                    ha_presentado_cefalea: null,
                    que_otros_sintomas: null,
                    ha_presentado_sintomas_alarma: null,
                    detalles_signos_alarmas: null,
                    respiracion_rapida_taquipnea: null,
                    fiebre_mas_2_dias: null,
                    pecho_suena_sibilancias: null,
                    somnolencia_letargia: null,
                    convulsiones: null,
                    deterioro_rapido_estado_general: null,
                    registrado_segcovid: null,
                    pruebas_diagnostico_covid: null,
                    tipo_prueba_diagnostica_covid: null,
                    fecha_realizacion_prueba: null,
                    fecha_recepcion_prueba: null,
                    ips_toma_muestra: null,
                    resultado_pruebas: null,
                    fecha_reporte_resultado_covid: null,
                    ips_procesa_muestra: null,
                    otra_ips: null,
                    numero_nit: null,
                    observaciones: null,
                    destino_paciente: null,
                    esperarencasa: null,
                    asignarconsulta: null,
                    seguimientotelefonico: null,
                    consultaprioritaria: null,
                    teleorientacion: null,
                    otrasindicaciones: null,
                    tiposeguimiento: null,
                    causaseguimiento: null,
                    saturacion: null,
                    pulso: null,
                    temperatura: null,
                    tensionarterial: null,
                    frecuenciarespiratoria: null,
                    escalanews2: null,
                    callscore: null,
                    concentracionoxigeno: null,
                    laboratoriodomicilio: null,
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
                        text: "Estado",
                        value: "estado"
                    },
                    {
                        text: "Usuario Crea",
                        value: "user_crea"
                    }, {
                        text: "",
                        value: ""
                    }, {
                        text: "Seguimiento",
                        value: ""
                    }, {
                        text: "Ingresados",
                        value: ""
                    }
                ],
            }
        },
        computed: {
            ...mapGetters(['can']),
        },
        methods: {
            verseguimiento(registro) {

                axios.get(`/api/covid/verseguimientos/${registro}`).then(res => {
                    console.log('id', res.data);
                    this.dialogverCovi = true;
                    this.covisuguimientos = res.data;
                })
            },
            guardarSeguimientos() {
                if (this.formSeguimiento.contacto_fue == null) {
                    this.$alerError('Seleccione el contacto fue.')
                    return;
                }
                axios.post('/api/covid/guardarSeguimientos', this.formSeguimiento).then(res => {
                    this.clearData();
                    this.$alerSuccess(res.data.message);
                    this.listarSolicitudes();
                })
            },
            listarSolicitudes() {
                axios.get('/api/covid/alta').then(res => {
                    this.listaSolicitudes = res.data;

                })
            },
            cargarDetalles(id) {
                this.formSeguimiento.registrocovi_id = id;
                this.dialogDetalle = true;
            },
            dardealta() {
                if (this.formSeguimiento.contacto_fue == null) {
                    this.$alerError('Seleccione el contacto fue.')
                    return;
                }
                axios.post('/api/covid/dealtaCovi', this.formSeguimiento).then(res => {
                    this.clearData();
                    this.$alerSuccess(res.data.message);
                    this.listarSolicitudes();
                })
            },
            // guardarControl() {
            //     axios.post('/api/seguimiento/control', this.formSeguimiento).then(res => {
            //             this.clearData();
            //             this.$alerSuccess(res.data.message);
            //             this.listarSolicitudes();
            //     })
            // },
            clearData() {
                this.poblacion_riesgo = [];
                for (const prop of Object.getOwnPropertyNames(this.formSeguimiento)) {
                    this.formSeguimiento[prop] = null;
                }
                this.dialogDetalle = false;
            },
            async imprimir(id, tipo) {
                const pdf = {
                    type: 'covid',
                    id: id,
                    tipo: tipo
                }
                axios
                    .post("/api/pdf/print-pdf", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    })
                    .catch(err => console.log(err.response));
            },
            imprimirSeguimiento(id, tipo){
                const pdf = {
                    type: 'covid',
                    id: id,
                    tipo: tipo
                }
                axios
                    .post("/api/pdf/print-pdf", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    })
                    .catch(err => console.log(err.response));

            },
        },
        mounted() {
            this.listarSolicitudes();
        }
    }

</script>
