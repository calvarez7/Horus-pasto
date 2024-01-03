<template>
    <v-card>
        <v-card-title>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line hide-details>
            </v-text-field>
        </v-card-title>
        <tele-concepto-show-dialog :data="data" ref="dialog" :showTeleDialog="showTeleDialog"
            @closeDialog="showTeleDialog=false" @responder="responder" :inPendingPage="inPendingPage">
            <PacienteData ref="PacienteData" :paciente="paciente" />
            <template v-if="inPendingPage">
                <br>
                <v-card>
                    <v-card-title class="headline grey lighten-2" primary-title>
                        Detalles Teleorientacion
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm3 md3>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Especialidad</strong></v-list-tile-title>
                                            <v-list-tile-sub-title>{{detalleTeleconcepto.especialidad}}
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-flex>
                                <v-flex xs12 sm2 md2 v-if="!editarEspecialidad">
                                    <v-btn v-if="can('teleconcepto.cambiar.especialidad')" color="warning"
                                        @click="editarEspecialidad = true ">Cambiar</v-btn>
                                </v-flex>
                                <v-flex xs12 sm3 md3>
                                    <VAutocomplete v-show="editarEspecialidad" :items="especialidades"
                                        label="Especialidad:" no-data-text="No se encuentra especialidad"
                                        v-model="nuevaEspecialidad" />
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-btn color="error" v-show="editarEspecialidad"
                                        @click="editarEspecialidad = false;nuevaEspecialidad = null">Cancelar</v-btn>
                                    <v-btn v-show="editarEspecialidad" color="success" @click="cambiarEspecialidad">
                                        Actualizar</v-btn>
                                </v-flex>

                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Tipo Solicitud</strong></v-list-tile-title>
                                            <v-list-tile-sub-title>{{detalleTeleconcepto.Tipo_Solicitud}}
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-flex>
                                <v-flex xs6 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile v-if="detalleTeleconcepto.cup" avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>CUP</strong></v-list-tile-title>
                                            <v-list-tile-sub-title>{{detalleTeleconcepto.cup}}</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Motivo Interconsulta</strong></v-list-tile-title>
                                            <v-list-tile-sub-title>{{detalleTeleconcepto.Descripcion}}
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Resumen Historia Clinica</strong>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    {{detalleTeleconcepto.ResumenHc}}
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Diagnosticos</strong></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <ul>
                                        <li v-for="(cie, index) in detalleTeleconcepto.cie10" :key="index">
                                            {{ cie.Codigo_CIE10 }} - {{ cie.Descripcion }}</li>
                                    </ul>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Archivos Adjuntos</strong></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-btn color="warning" :key="index" :href="adjunto.Ruta" outline round small
                                        target="_blank" v-for="(adjunto, index) in detalleTeleconcepto.adjuntos">
                                        Abrir archivo
                                    </v-btn>
                                </v-flex>

                                <v-flex xs12 sm3 md3>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Junta GIRS</strong></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-radio-group>
                                        <v-checkbox label="Requiere Junta GIRS"
                                            v-model="detalleTeleconcepto.girs_auditor" color="primary"
                                            :readonly="!inPendingPage"></v-checkbox>
                                    </v-radio-group>
                                </v-flex>

                                <v-flex xs12 sm9 md9>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <!--                                            <v-list-tile-title><strong>Observacion No pertienente (Teleconcepto)</strong></v-list-tile-title>-->
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-textarea outline label="Observacion Reasignación GIRS"
                                        v-model="detalleTeleconcepto.observacion_reasignacion_girs"
                                        :readonly="!inPendingPage"></v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <!--                                            <v-list-tile-title><strong>Observacion No pertienente (Teleconcepto)</strong></v-list-tile-title>-->
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-label><strong>Prioridad del teleorientacion</strong></v-label>
                                    <v-radio-group v-model="pertinencia_prioridad">
                                        <v-radio v-for="n in ['Pertinente','No pertinente']" :key="n" :label="n"
                                            :value="n" color="primary"></v-radio>
                                    </v-radio-group>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <!--                                            <v-list-tile-title><strong>Observacion No pertienente (Teleconcepto)</strong></v-list-tile-title>-->
                                        </v-list-tile-content>
                                    </v-list-tile>

                                    <v-label><strong>Evaluación pertinencia de solicitud de Teleorientacion</strong>
                                    </v-label>
                                    <v-radio-group v-model="pertinencia_evaluacion">
                                        <v-radio v-for="n in ['Pertinente','No pertinente']" :key="n" :label="n"
                                            :value="n" color="primary"></v-radio>
                                    </v-radio-group>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-spacer></v-spacer>
                </v-card>
                <br>
                <v-card v-show="detalleTeleconcepto.citaPaciente">
                    <v-card-title class="headline grey lighten-2" primary-title>
                        Ordenamiento
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm1 md1 offset-sm11 offset-md11>


                                    <!--                                    <v-btn color="success">Ordenar</v-btn>-->
                                    <Ordenamiento :paciente="paciente" :showBottom="true" ref="ordenamiento"
                                        :cita_paciente="parseInt(detalleTeleconcepto.citaPaciente)" tipo="teleconcepto"
                                        :idReferencia="detalleTeleconcepto.id" @clearDataPaciente="clearDataPaciente">
                                    </Ordenamiento>

                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-data-table v-model="selected" :headers="headersOrdenamiento"
                                        :items="detalleTeleconcepto.medicamentos" item-key="id" hide-actions>
                                        <template v-slot:items="props">
                                            <td>
                                                <v-checkbox
                                                    v-show="props.item.Estado_id === '15' && inPendingPage === true"
                                                    v-model="props.selected" color="primary" hide-details></v-checkbox>
                                            </td>
                                            <td>{{ props.item.tipo }}</td>
                                            <td>{{ props.item.Codigo }}</td>
                                            <td class="text-xs-right">{{ props.item.Nombre }}</td>
                                            <td class="text-xs-right">{{ props.item.Cantidad }}</td>
                                            <td class="text-xs-right">{{ props.item.paginacion }}</td>
                                            <td class="text-xs-right">{{ props.item.Observacion }}</td>
                                            <td class="text-xs-center" v-if="props.item.Estado_id === '7'">
                                                <v-btn color="info" fab dark small @click="imprimirServicio(props.item)">
                                                    <v-icon>mdi-download</v-icon>
                                                </v-btn>
                                            </td>
                                        </template>
                                    </v-data-table>
                                    <v-spacer></v-spacer>
                                    <v-flex xs12 v-if="this.selected.length > 0">
                                        <v-select v-model="accion" append-icon="search" label="Seleccionar acción"
                                            :items="acciones" item-text="accion" item-value="value" hide-details>
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 v-if="this.selected.length > 0">
                                        <v-textarea name="input-7-1" label="Observacion Auditor"
                                            v-model="observaciones">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 v-if="this.selected.length > 0">
                                        <v-btn color="success" @click="auditar">Enviar</v-btn>
                                    </v-flex>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </template>
            <template v-else>
                <br>
                <v-card>
                    <v-card-title class="headline grey lighten-2" primary-title>
                        Detalles Teleorientacion
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm12 md12>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Especialidad</strong></v-list-tile-title>
                                            <v-list-tile-sub-title>{{detalleTeleconcepto.especialidad}}
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Tipo Solicitud</strong></v-list-tile-title>
                                            <v-list-tile-sub-title>{{detalleTeleconcepto.Tipo_Solicitud}}
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-flex>
                                <v-flex xs6 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile v-if="detalleTeleconcepto.cup" avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>CUP</strong></v-list-tile-title>
                                            <v-list-tile-sub-title>{{detalleTeleconcepto.cup}}</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Motivo Interconsulta</strong></v-list-tile-title>
                                            <v-list-tile-sub-title>{{detalleTeleconcepto.Descripcion}}
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Resumen Historia Clinica</strong>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    {{detalleTeleconcepto.ResumenHc}}
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Diagnosticos</strong></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <ul>
                                        <li v-for="(cie, index) in detalleTeleconcepto.cie10" :key="index">
                                            {{ cie.Codigo_CIE10 }} - {{ cie.Descripcion }}</li>
                                    </ul>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Archivos Adjuntos</strong></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-btn color="warning" :key="index" :href="adjunto.Ruta" outline round small
                                        target="_blank" v-for="(adjunto, index) in detalleTeleconcepto.adjuntos">
                                        Abrir archivo
                                    </v-btn>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Observacion especialista resignacion
                                                    GIRS</strong></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    {{detalleTeleconcepto.observacion_reasignacion_girs}}
                                </v-flex>

                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Prioridad del teleconcepto</strong>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    {{detalleTeleconcepto.pertinencia_prioridad}}
                                </v-flex>

                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Evaluación pertinencia de solicitud de
                                                    Teleorientacion</strong></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    {{detalleTeleconcepto.pertinencia_evaluacion}}
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-spacer></v-spacer>
                </v-card>
                <br>
                <v-card v-show="detalleTeleconcepto.citaPaciente">
                    <v-card-title class="headline grey lighten-2" primary-title>
                        Ordenamiento
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <!--                                <v-flex xs12 sm1 md1 offset-sm11 offset-md11>-->
                                <!--                                    <Ordenamiento :paciente="paciente" :showBottom="true" ref="ordenamiento"-->
                                <!--                                                  :cita_paciente="parseInt(detalleTeleconcepto.citaPaciente)" tipo="teleconcepto" :idReferencia="detalleTeleconcepto.id" @clearDataPaciente="clearDataPaciente"></Ordenamiento>-->
                                <!--                                </v-flex>-->
                                <v-flex xs12 sm12 md12>
                                    <v-data-table v-model="selected" :headers="headersOrdenamiento"
                                        :items="detalleTeleconcepto.medicamentos" item-key="id" hide-actions>
                                        <template v-slot:items="props">
                                            <td>
                                                <!--                                                <v-checkbox v-show="props.item.Estado_id === '15' && inPendingPage === true"-->
                                                <!--                                                            v-model="props.selected"-->
                                                <!--                                                            color="primary"-->
                                                <!--                                                            hide-details-->
                                                <!--                                                ></v-checkbox>-->
                                            </td>
                                            <td>{{ props.item.tipo }}</td>
                                            <td>{{ props.item.Codigo }}</td>
                                            <td class="text-xs-right">{{ props.item.Nombre }}</td>
                                            <td class="text-xs-right">{{ props.item.Cantidad }}</td>
                                            <td class="text-xs-right">{{ props.item.paginacion }}</td>
                                            <td class="text-xs-right">{{ props.item.Observacion }}</td>
                                            <td class="text-xs-center" v-if="props.item.Estado_id === '7'">
                                                <v-btn color="info" fab dark small @click="imprimirServicio(props.item)">
                                                    <v-icon>mdi-download</v-icon>
                                                </v-btn>
                                            </td>
                                        </template>
                                    </v-data-table>
                                    <v-spacer></v-spacer>
                                    <v-flex xs12 v-if="this.selected.length > 0">
                                        <v-select v-model="accion" append-icon="search" label="Seleccionar acción"
                                            :items="acciones" item-text="accion" item-value="value" hide-details>
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 v-if="this.selected.length > 0">
                                        <v-textarea name="input-7-1" label="Observacion Auditor"
                                            v-model="observaciones">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 v-if="this.selected.length > 0">
                                        <v-btn color="success" @click="auditar">Enviar</v-btn>
                                    </v-flex>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
                <br>
                <v-card>
                    <v-card-title class="headline grey lighten-2" primary-title>
                        Informacion Junta GIRS
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm12 md12>
                                    <v-autocomplete v-model="junta.integrantes" :items="users" label="Integrantes"
                                        item-text="nombre" item-value="id" chips readonly multiple>
                                        <template v-slot:selection="data">
                                            <v-chip :selected="data.selected" class="chip--select-multi">
                                                {{ data.item.nombre }}
                                            </v-chip>
                                        </template>
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Institucion Prestadora</strong>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    {{detalleTeleconcepto.institucion_prestadora}}
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>EAPB</strong></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    {{detalleTeleconcepto.eapb}}
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Evaluacion junta</strong></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    {{detalleTeleconcepto.evaluacion_junta}}
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>La junta de profesionales de salud
                                                    aprueba?</strong></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    {{detalleTeleconcepto.junta_aprueba}}
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-divider></v-divider>
                                    <v-list-tile avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title><strong>Clasificación de prioridad de servicio
                                                    ambulatorio</strong></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    {{detalleTeleconcepto.Tipo_Solicitud}}
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </template>
        </tele-concepto-show-dialog>
        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Tranquilo procesamos tu solicitud !
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-data-table :headers="headers" :items="teles" :search="search" hide-actions :pagination.sync="pagination">
            <template v-slot:no-data>
                <span>No hay teleorientacion</span>
            </template>
            <template v-slot:items="props">
                <td class="text-xs-center">{{ props.item.Num_Doc }}-{{ props.item.id }}</td>
                <td class="text-xs-center">{{ props.item.especialidad }}</td>
                <td class="text-xs-center">{{ props.item | fullnamePaciente }}</td>
                <td class="text-xs-center">{{ props.item.created_at | date }}</td>
                <td class="text-xs-center">{{ props.item.Tipo_Solicitud }}</td>
                <td class="text-xs-center">{{ props.item | fullnameCreador }}</td>
                <td class="text-xs-center">{{ props.item.Sede }}</td>
                <td class="text-xs-center">
                    <v-btn color="primary" icon @click="showTeleconcepto(props.item)">
                        <v-icon>library_books</v-icon>
                    </v-btn>
                </td>
                <td class="text-xs-center">
                    <v-chip v-if="parseInt(props.item.girs)" color="success" text-color="white">GIRS</v-chip>
                </td>
            </template>
        </v-data-table>
        <div v-if="pagination.totalItems > 0" class="text-xs-center pt-2">
            <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
        </div>
    </v-card>
</template>

<script>
    import PacienteData from '../../components/paciente/PacienteData.vue'
    import TeleConceptoShowDialog from '../../components/teleconcepto/TeleConceptoShowDialog.vue'
    import Ordenamiento from "../medicamento/Ordenamiento.vue";
    import moment from 'moment';
    moment.locale('es');
    import {
        mapGetters
    } from 'vuex';

    export default {
        name: 'TeleConceptoTable',
        components: {
            PacienteData,
            TeleConceptoShowDialog,
            Ordenamiento
        },
        props: {
            teles: Array,
            inPendingPage: Boolean,
        },
        data: () => {
            return {
                pertinencia_prioridad: null,
                pertinencia_evaluacion: null,
                habilitarGirs: true,
                editarEspecialidad: false,
                nuevaEspecialidad: null,
                requiereJunta: false,
                junta: {
                    integrantes: []
                },
                users: [],
                accion: null,
                observaciones: null,
                cups: null,
                preload: false,
                selected: [],
                detalleTeleconcepto: {
                    Tipo_Solicitud: null,
                    Descripcion: null,
                    ResumenHc: null,
                    Respuesta: null,
                    especialidad: null,
                    cup: null,
                    cie10: [],
                    adjuntos: null,
                    citaPaciente: null,
                    medicamentos: [],
                    girs: null,
                    girs_auditor: false,
                    observacion_reasignacion_girs: null,
                    id: null,
                    pertinencia_evaluacion: "",
                    pertinencia_prioridad: ""
                },
                especialidades: [
                    'Urologia',
                    'Medico SST',
                    'Ginecología',
                    'Medicina Familiar',
                    'Oftalmología',
                    'Ortopedia',
                    'Otorrinolaringología',
                    'Psiquiatría',
                    'Dermatologia'
                ],
                search: '',
                Pertinente: '',
                data: {
                    Tipo_anexo: null,
                    cie10s: []
                },
                headers: [{
                        text: 'Identificación',
                        value: 'Num_Doc',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Especialidad',
                        value: 'especialidad',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Nombre',
                        value: 'Primer_Nom',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Registro',
                        value: 'string',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Prioridad',
                        value: 'Tipo_Solicitud',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Médico solicitante',
                        value: 'string',
                        align: 'center',
                        sortable: false
                    },

                    {
                        text: 'Sede',
                        value: 'Sede',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Gestión',
                        value: 'string',
                        align: 'center',
                        sortable: false
                    }, {
                        text: '',
                        value: 'string',
                        align: 'center',
                        sortable: false
                    }
                ],
                headersOrdenamiento: [{
                    text: "",
                    align: 'center',
                    sortable: false,
                    value: ""
                }, {
                    text: "Tipo",
                    align: 'center',
                    value: "tipo"
                }, {
                    text: "Codigo",
                    align: 'center',
                    value: "Codigo"
                }, {
                    text: "Nombre",
                    align: 'center',
                    value: "Nombre"
                }, {
                    text: "Cantidad",
                    align: 'center',
                    sortable: false,
                    value: "tipo"
                }, {
                    text: "Paginacion",
                    align: 'center',
                    sortable: false,
                    value: "paginacion"
                }, {
                    text: "Observaciones",
                    align: 'center',
                    sortable: false
                },{
                    text: "Imprimir",
                    align: 'center',
                    sortable: false
                }],
                acciones: [{
                        accion: "APROBADO",
                        value: "Aprobado"
                    },
                    {
                        accion: "INADECUADO",
                        value: "Inadecuado"
                    },
                    {
                        accion: "NEGADO",
                        value: "Negado"
                    },
                    {
                        accion: "ANULADO",
                        value: "Anulado"
                    }
                ],
                paciente: {

                },
                pagination: {
                    rowsPerPage: 20,
                },
                showTeleDialog: false
            }
        },
        computed: {
            ...mapGetters(['can']),

            pages() {
                if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0

                return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
            },
        },
        filters: {
            fullnamePaciente: (item) => {
                return `${item.Primer_Nom || ''} ${item.SegundoNom || ''} ${item.Primer_Ape || ''} ${item.Segundo_Ape || ''}`
            },
            fullnameCreador: (item) => {
                return `${item.name || ''} ${item.apellido || ''}`
            },
            date: (date) => {
                return moment(date).format('lll')
            }
        },
        watch: {
            teles() {
                this.pagination.totalItems = this.teles.length;
            }
        },
        methods: {
            showTeleconcepto(tele) {
                this.$refs.dialog.resetForm();
                this.paciente = {
                    id: tele.id,
                    Primer_Nom: tele.Primer_Nom,
                    Primer_Ape: tele.Primer_Ape,
                    Tipo_Doc: tele.Tipo_Doc,
                    Num_Doc: tele.Num_Doc,
                    Edad_Cumplida: tele.Edad_Cumplida,
                    Telefono: tele.Telefono,
                    Celular1: tele.Celular1,
                    Celular2: tele.Celular2,
                    Correo1: tele.Correo1,
                    Correo2: tele.Correo2,
                }
                this.data = {
                    Tipo_Solicitud: tele.Tipo_Solicitud,
                    cie10s: tele.cie10s,
                    adjuntos: tele.adjuntos,
                    descripcion: tele.descripcion,
                    ResumenHc: tele.ResumenHc,
                    Respuesta: tele.Respuesta,
                    Sede: tele.Sede,
                    Firma: tele.Firma,
                    Registromedico: tele.Registromedico,
                    especialidad_medico: tele.especialidad_medico,
                    Ut: tele.Ut,
                    id: tele.id
                }
                this.detalleTeleconcepto.citaPaciente = tele.cita_paciente_id;
                if (tele.cita_paciente_id) {
                    this.medicamentosOrdenados(tele.cita_paciente_id);
                } else {
                    this.detalleTeleconcepto.medicamentos = [];
                }
                this.detalleTeleconcepto.id = tele.id;
                this.detalleTeleconcepto.especialidad = tele.especialidad;
                this.detalleTeleconcepto.Descripcion = tele.descripcion;
                this.detalleTeleconcepto.Tipo_Solicitud = tele.Tipo_Solicitud;
                this.detalleTeleconcepto.ResumenHc = tele.ResumenHc;
                this.detalleTeleconcepto.cie10 = tele.cie10s;
                this.detalleTeleconcepto.adjuntos = tele.adjuntos;
                this.detalleTeleconcepto.girs = !!parseInt(tele.girs);
                this.detalleTeleconcepto.girs_auditor = parseInt(tele.girs_auditor) === 1;
                this.habilitarGirs = parseInt(tele.girs) !== 1;
                this.detalleTeleconcepto.observacion_reasignacion_girs = tele.observacion_reasignacion_girs;
                this.detalleTeleconcepto.institucion_prestadora = tele.institucion_prestadora;
                this.detalleTeleconcepto.eapb = tele.eapb
                this.detalleTeleconcepto.evaluacion_junta = tele.evaluacion_junta
                this.detalleTeleconcepto.junta_aprueba = tele.junta_aprueba;
                this.detalleTeleconcepto.pertinencia_evaluacion = tele.pertinencia_evaluacion;
                this.detalleTeleconcepto.pertinencia_prioridad = tele.pertinencia_prioridad;
                tele.integrantes_girs.forEach(s => {
                    this.junta.integrantes.push(parseInt(s.usuario_id))
                })

                if (tele.Cup_id) {
                    const found = this.cups.find(element => parseInt(element.id) === parseInt(tele.Cup_id));
                    this.detalleTeleconcepto.cup = found.Nombre;
                } else {
                    this.detalleTeleconcepto.cup = null;
                }
                this.$refs.dialog.teleconcepto.paciente = this.paciente;
                this.showTeleDialog = true;
            },
            async responder(respuesta) {

                if (this.detalleTeleconcepto.girs_auditor && this.detalleTeleconcepto
                    .observacion_reasignacion_girs) {
                    const ReasignacionGirs = {
                        id: this.detalleTeleconcepto.id,
                        observacion: this.detalleTeleconcepto.observacion_reasignacion_girs
                    };
                    await axios.post('/api/teleconcepto/reasignarGIRS', ReasignacionGirs)
                }
                let data = {
                    Respuesta: respuesta,
                    pertinencia_prioridad: this.pertinencia_prioridad,
                    pertinencia_evaluacion: this.pertinencia_evaluacion,
                }
                axios.put(`/api/teleconcepto/reply/${this.paciente.id}`, data)
                    .then(res => {
                        this.showTeleDialog = false;
                        this.showMessage(res.data.message);
                        this.$emit('getPending');
                    })
                    .catch(err => console.log(err.response))
            },
            fetchCups() {
                axios.get('/api/cups/all')
                    .then(res => this.cups = res.data.map((cup) => {
                        return {
                            id: cup.id,
                            Nombre: `${cup.Codigo} - ${cup.Nombre}`
                        }
                    }));
            },
            medicamentosOrdenados(id) {
                this.detalleTeleconcepto.medicamentos = [];
                this.selected = [];
                this.accion = null;
                this.observaciones = null
                this.preload = true;
                axios.get('/api/orden/getDetalleOrdenamientoForCita/' + id).then(res => {
                    this.detalleTeleconcepto.medicamentos = res.data;
                    this.preload = false;
                }).catch(err => {
                    this.preload = false;
                    console.log(err)
                })

            },
            showMessage(message) {
                swal({
                    title: `${message}`,
                    icon: "success",
                });
            },
            auditar() {
                if (!this.accion) {
                    this.$alerError("Debe selecciona una accion")
                    return;
                }
                if (!this.observaciones) {
                    this.$alerError("El campo observaciones es requrido")
                    return;
                }
                let ordenes = [{
                    datos: [],
                    tipo: 'Medicamentos'
                }, {
                    datos: [],
                    tipo: 'Servicios'
                }]

                this.selected.forEach(s => {
                    if (s.tipo === 'medicamento') {
                        ordenes[0].datos.push({
                            Codesumi_id: s.referencia,
                            Nombre: s.Nombre,
                            id: s.id,
                            Tiporden_id: s.Tiporden_id,
                            orden: s.Orden_id
                        });
                    }
                    if (s.tipo === 'servicio') {
                        ordenes[1].datos.push({
                            Orden_id: s.Orden_id,
                            id: s.id,
                            posFechar: false
                        });
                    }
                })
                ordenes.forEach(s => {
                    if (s.datos.length > 0) {
                        const dataSend = {
                            observaciones: this.observaciones,
                            autorizaciones: s.datos,
                            type: s.tipo,
                            id: s.orden,
                            Tiporden_id: s.datos[0].Tiporden_id,
                        }

                        if (this.accion === "Aprobado") {
                            axios.post("/api/autorizacion/StateAprobOne", dataSend)
                                .then(res => {
                                    swal("¡Aprobación generada de manera exitosa!", {
                                        timer: 2000,
                                        icon: "success",
                                        buttons: false
                                    });
                                    this.medicamentosOrdenados(this.detalleTeleconcepto.citaPaciente);
                                })
                                .catch(err => console.log(err.response));
                        } else if (this.accion === "Inadecuado") {
                            axios
                                .post("/api/autorizacion/StateInad", dataSend)
                                .then(res => {
                                    this.$alerSuccess(res.data)
                                    this.medicamentosOrdenados(this.detalleTeleconcepto.citaPaciente);
                                });
                        } else if (this.accion === "Negado") {
                            axios
                                .post("/api/autorizacion/StateNeg", dataSend)
                                .then(res => {
                                    this.$alerSuccess(res.data)
                                    this.medicamentosOrdenados(this.detalleTeleconcepto.citaPaciente);
                                });
                        } else if (this.accion === "Anulado") {
                            axios
                                .post("/api/autorizacion/StateAnu", dataSend)
                                .then(res => {
                                    this.$alerSuccess(res.data)
                                    this.medicamentosOrdenados(this.detalleTeleconcepto.citaPaciente);
                                });
                        }

                    }
                })

            },
            getUser() {
                axios.get('/api/user/all')
                    .then(res => this.users = res.data.map((us) => {
                        return {
                            id: us.id,
                            nombre: `${us.cedula} - ${us.name} ${us.apellido}`,
                            estado: us.estado_user
                        }
                    }));
            },
            remove(item) {
                const index = this.junta.integrantes.indexOf(item.id)
                if (index >= 0) this.junta.integrantes.splice(index, 1)
            },
            clearDataPaciente() {
                this.medicamentosOrdenados(this.detalleTeleconcepto.citaPaciente);
            },
            cambiarEspecialidad() {
                const especilidad = this.nuevaEspecialidad;
                axios.post('/api/teleconcepto/guardar', {
                    id: this.detalleTeleconcepto.id,
                    especialidad: especilidad
                }).then(res => {
                    this.$alerSuccess('Especilidad actulizada')
                    this.detalleTeleconcepto.especialidad = especilidad;
                    this.nuevaEspecialidad = null;
                    this.editarEspecialidad = false;
                    this.showTeleDialog = false;
                })
            },

            imprimirServicio(servicio){
                this.preload = true;
                const pdf = {
                    orden:servicio.orden,
                    type:'otros',
                    servicios:[{identificador:servicio.id}]
                }
                this.getPDF(pdf);

            },

            async getPDF(pdf) {
                this.preload = true;
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
                        this.preload = false;
                    })
                    .catch(err => {console.log(err.response);this.preload = false;});

            },
        },
        mounted() {
            this.getUser();
            this.fetchCups();
        }
    }

</script>

<style lang="scss" scoped>

</style>
