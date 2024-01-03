<template>
    <v-tabs centered color="white" icons-and-text>
        <v-tabs-slider color="primary"></v-tabs-slider>
        <v-tab href="#tab-1" color="black--text">
            Pendientes
            <v-icon color="black">block</v-icon>
        </v-tab>
        <v-tab href="#tab-2">
            Cerradas
            <v-icon color="black">check_circle</v-icon>
        </v-tab>
        <v-tab-item :value="'tab-1'">
            <v-layout>
                <v-flex xs12 sm12>
                    <v-card>
                        <v-dialog v-model="dialogCon" width="500">
                            <v-card>
                                <v-card-title
                                    class="headline"
                                >
                                    Agendar Cita
                                </v-card-title>
                                <v-card-text>
                                    Asignar cita de tipo <b> {{ actividad_selected }} </b> al usuario <b>{{ paciente.Primer_Nom }} {{ paciente.SegundoNom }} {{ paciente.Primer_Ape}} {{ paciente.Segundo_Ape }}</b> identificado con <b>{{ paciente.Tipo_Doc }}</b>  N° <b>{{ paciente.Num_Doc }}</b> el día <b>{{ fecha_selected }}</b> a las <b>{{ data.hora_inicio | time}}</b> en la sede <b>{{ sede_selected }}</b>, <b>{{ data.consultorio }}</b> con el médico <b>{{ medico_selected }}</b>
                                </v-card-text>
                                <v-divider></v-divider>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        flat
                                        @click="clearFields"
                                    >
                                        cancelar
                                    </v-btn>
                                    <v-btn
                                        color="primary"
                                        flat
                                        @click="agendarCita()"
                                    >
                                        Aceptar
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-dialog v-model="dialog" max-width="1200px">
                            <v-card>
                                <v-toolbar flat color="primary" dark>
                                    <v-toolbar-title>Detalles Orden</v-toolbar-title>
                                </v-toolbar>
                                <v-data-table class="fb-table-elem" :headers="headerDetalle" :items="listaDetalleOrden">
                                    <template v-slot:items="props">
                                        <td class="text-xs-center">{{ props.item.id }}</td>
                                        <td class="text-xs-center">{{ props.item.Codigo }}</td>
                                        <td class="text-xs-center">{{ props.item.Nombre }}</td>
                                        <td class="text-xs-center">{{ props.item.Via }}</td>
                                        <td class="text-xs-center">{{ Math.round(props.item.Cantidad_Medico) }} {{
                                            props.item.Unidad_Medida }}
                                        </td>
                                        <td class="text-xs-center">{{ props.item.Frecuencia }} / {{
                                            props.item.Unidad_Tiempo }}
                                        </td>
                                        <td class="text-xs-center">{{ props.item.Observacion }}</td>
                                        <td class="text-xs-center">{{ props.item.nota_autorizacion }}</td>
                                        <td class="text-xs-center">{{ props.item.notaFarmacia }}</td>

                                    </template>
                                </v-data-table>
                            </v-card>
                        </v-dialog>
                        <v-dialog v-model="dialogOrdens" max-width="1100px">
                            <v-card>
                                <v-toolbar flat color="primary" dark>
                                    <v-toolbar-title>Ordenes</v-toolbar-title>
                                </v-toolbar>
                                <v-data-table :headers="headersOrdens" :items="listaOrdenesOncologia" item-key="index">
                                    <template v-slot:items="props">
                                        <td class="text-xs-center"><a href="javascript:void(0)"
                                                                      @click="dialog = true,listaDetalleOrden = props.item.detaarticulordens">{{
                                            props.item.id }}</a></td>
                                        <td class="text-xs-center">
                                            <v-btn round color="info" @click="imprimir(props.item.id)">Imprimir</v-btn>
                                        </td>
                                        <td class="text-xs-center">{{ props.item.paginacion }}</td>
                                        <td class="text-xs-center">{{ props.item.day }}</td>
                                        <td class="text-xs-center">{{
                                            props.item.detaarticulordens[0].fechaAutorizacion?props.item.detaarticulordens[0].fechaAutorizacion:"Sin Autorizar" }}
                                        </td>
                                        <td class="text-xs-center" v-if="props.item.Estado_id == 35">
                                            <v-chip color="warning" text-color="white">{{ props.item.estado }}</v-chip>
                                        </td>
                                        <td class="text-xs-center" v-else-if="props.item.Estado_id == 1">
                                            <v-chip color="success" text-color="white">{{ props.item.estado }}</v-chip>
                                        </td>
                                        <td class="text-xs-center" v-else-if="props.item.Estado_id == 7">
                                            <v-chip color="success" text-color="white">Autorizado</v-chip>
                                        </td>
                                        <td class="text-xs-center" v-else>
                                            <v-chip color="danger" text-color="white">{{ props.item.estado }}</v-chip>
                                        </td>
                                        <td class="text-xs-center" v-if="props.item.FechaAgendamiento">{{
                                            props.item.FechaAgendamiento.substring(0,10)}}
                                        </td>
                                        <td class="text-xs-center" v-else>
                                            <v-btn round color="primary"
                                                   @click="dialogDate = true,codigoOrden = props.item.id" dark>Asignar
                                            </v-btn>
                                        </td>
                                        <td class="text-xs-center"
                                            v-if="!props.item.estadoEnfermeria || props.item.estadoEnfermeria === 'Reagendar'">
                                            <v-btn round color="warning"
                                                   @click="validateOrden(props.item.FechaAgendamiento),codigoOrden = props.item.id"
                                                   dark>Finalizar
                                            </v-btn>
                                        </td>
                                        <td class="text-xs-center"
                                            v-else-if="props.item.estadoEnfermeria === 'Aplicado'">
                                            <v-chip color="success" text-color="white">Aplicado</v-chip>
                                        </td>
                                        <td class="text-xs-center"
                                            v-else-if="props.item.estadoEnfermeria === 'Inasistencia'">
                                            <v-chip color="error" text-color="white">Inasistencia</v-chip>
                                        </td>
                                        <td class="text-xs-center" v-else-if="props.item.estadoEnfermeria === 'Otro'">
                                            <v-chip color="cyan" text-color="white">Otro</v-chip>
                                        </td>
                                    </template>

                                    <template v-slot:no-data>
                                        <v-alert :value="true" color="error" icon="warning">No hay movimientos en este
                                            Estado.
                                        </v-alert>
                                    </template>
                                </v-data-table>
                            </v-card>
                        </v-dialog>
                        <v-dialog v-model="dialogDate" persistent max-width="1000px">
                            <v-card max-height="100%" v-show="true">
                                <v-card-title>
                                    <span class="title layout justify-center font-weight-light">Agenda Disponible Médico</span>
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-text>
                                    <v-layout row wrap>
                                        <v-flex
                                            xs3
                                            px-1
                                        > <!-- especialidad -->
                                            <v-autocomplete
                                                v-model="especialidad_selected"
                                                :items="especialidades"
                                                item-text="Nombre"
                                                item-value="id"
                                                label="Especialidades"
                                                disabled
                                                @input="fetchSedes()"
                                            ></v-autocomplete>
                                        </v-flex>
                                        <!-- sede -->
                                        <v-flex
                                            xs3
                                            px-1
                                        >
                                            <v-select
                                                v-model="sede_selected"
                                                :items="sedes"
                                                label="Sede"
                                                item-text="Nombre"
                                                item-value="id"
                                                @input="fetchAgendas()"
                                                disabled
                                            ></v-select>
                                        </v-flex>
                                        <v-flex
                                            xs3
                                            px-1
                                        >
                                            <v-autocomplete
                                                v-model="actividad_selected"
                                                :items="actividades"
                                                item-text="name"
                                                item-value="et_id"
                                                label="Actividad"
                                                @input="fetchAgendas()"
                                            ></v-autocomplete>
                                        </v-flex>
                                        <v-flex
                                            xs3
                                            px-1
                                        >
                                            <v-select
                                                v-model="medico_selected"
                                                :items="medicosSede"
                                                label="Médico"
                                            ></v-select>
                                        </v-flex>
                                        <v-flex xs3>
                                            <!-- fecha -->
                                            <v-menu
                                                v-model="datePicker"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                lazy
                                                transition="scale-transition"
                                                offset-y
                                                full-width
                                                min-width="290px"
                                            >
                                                <template v-slot:activator="{ on }">
                                                    <v-combobox
                                                        v-model="data.fecha_solicitada"
                                                        label="Fecha solicitada"
                                                        prepend-icon="event"
                                                        readonly
                                                        v-on="on"

                                                    ></v-combobox>
                                                </template>
                                                <v-date-picker color="primary" locale="es" v-model="data.fecha_solicitada" :min="today" :show-current="false" @input="agendaSolicitada()"></v-date-picker>
                                            </v-menu>
                                        </v-flex>
                                        <v-flex xs9 px-2>
                                            <v-text-field  label="Observación" v-model="data.observaciones">
                                            </v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-container grid-list-md fluid class="pa-0">
                                        <template>
                                            <div class="text-center">
                                                <v-dialog v-model="preload_cita" persistent width="300">
                                                    <v-card color="primary" dark>
                                                        <v-card-text>
                                                            Estamos procesando su información
                                                            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-dialog>
                                            </div>
                                        </template>
                                        <template>
                                            <v-data-table
                                                :headers="headersAgenda"
                                                :items="agendaDisponible"
                                                item-key="name"
                                                class="elevation-1"
                                                color="primary"
                                                :rows-per-page-items="[20,30,50]"
                                                rows-per-page-text="Citas por página"
                                            >
                                                <template v-slot:items="props">
                                                    <td class="text-xs-center">{{ props.item.hora_inicio | time }}</td>
                                                    <td class="text-xs-center">{{ props.item.consultorio }}</td>
                                                    <td class="text-xs-center">
                                                        <v-btn
                                                            v-show="paciente.id && can('cita.toAssign')"
                                                            color="success"
                                                            fab
                                                            outline
                                                            small
                                                            @click="asignarCita(props.item)"
                                                        >
                                                            <v-icon>how_to_reg</v-icon>
                                                        </v-btn>
                                                    </td>
                                                </template>
                                            </v-data-table>
                                        </template>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="error" flat @click="dialogDate = false">Cancelar
                                        </v-btn>
                                    </v-card-actions>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-dialog v-model="dialogState" persistent max-width="1600px">
                            <v-card>
                                <form @submit.prevent="saveState">
                                    <v-card-title>
                                        <span class="headline">Finalizar Orden</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container fluid grid-list-md>
                                            <v-layout row wrap>
                                                <v-flex xs4>
                                                    <v-autocomplete
                                                        v-model="formState.estadoEnfermeria"
                                                        :items="states"
                                                        label="Estado"
                                                        persistent-hint
                                                        required>
                                                    </v-autocomplete>
                                                </v-flex>
                                                <v-flex xs12
                                                        v-if="formState.estadoEnfermeria && formState.estadoEnfermeria !== 'Reagendar'">
                                                    <v-textarea
                                                        v-model="formState.observacionEnfermeria"
                                                        label="Observación"
                                                        persistent-hint
                                                        required
                                                    ></v-textarea>
                                                </v-flex>
                                                <v-flex xs12 v-else>

                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="error" @click="dialogState = false">Cancelar</v-btn>
                                        <v-btn color="success" type="submit">Guardar</v-btn>
                                    </v-card-actions>
                                </form>
                            </v-card>
                        </v-dialog>
                        <v-dialog v-model="dialogGetNotes" max-width="1000px">
                            <v-card>
                                <v-toolbar flat color="primary" dark>
                                    <v-toolbar-title>Notas Enfermeria</v-toolbar-title>
                                </v-toolbar>
                                <v-card-text>
                                    <div v-show="listaNotas.length < 1"  class="text-xs-center">
                                        <h3>Sin Notas</h3>
                                    </div>
                                    <v-expansion-panel>
                                        <v-expansion-panel-content
                                            v-for="item in listaNotas"
                                            :key="item.id"
                                        >
                                            <template v-slot:header>
                                                <div><h3>{{item.created_at}}</h3></div>
                                            </template>
                                            <v-card>
                                                <v-card-text>
                                                    <div>
                                                        <h4>Alimentación</h4>
                                                    </div>
                                                    <div>
                                                        <p>{{item.alimentacion}}</p>
                                                    </div>
                                                    <div>
                                                        <h4>Caso de Urgencia</h4>
                                                    </div>
                                                    <div>
                                                        <p>{{item.caso_urgencia}}</p>
                                                    </div>
                                                    <div>
                                                        <h4>Cuidados en Casa</h4>
                                                    </div>
                                                    <div>
                                                        <p>{{item.cuidados_casa}}</p>
                                                    </div>
                                                    <div>
                                                        <h4>Derechos y Deberes</h4>
                                                    </div>
                                                    <div>
                                                        <p>{{item.derechos_deberes}}</p>
                                                    </div>
                                                    <div>
                                                        <h4>Efectos Secundarios</h4>
                                                    </div>
                                                    <div>
                                                        <p>{{item.efectos_secundarios}}</p>
                                                    </div>
                                                    <div>
                                                        <h4>Hábitos de Higiene</h4>
                                                    </div>
                                                    <div>
                                                        <p>{{item.habito_higiene}}</p>
                                                    </div>
                                                    <div>
                                                        <h4>Normas Sala Quimioterapia</h4>
                                                    </div>
                                                    <div>
                                                        <p>{{item.normas_sala_quimioterapia}}</p>
                                                    </div>
                                                    <div>
                                                        <h4>Nota</h4>
                                                    </div>
                                                    <div>
                                                        <p>{{item.nota}}</p>
                                                    </div>
                                                    <div>
                                                        <h4>Signos de Alarma</h4>
                                                    </div>
                                                    <div>
                                                        <p>{{item.signos_alarma}}</p>
                                                    </div>
                                                </v-card-text>
                                            </v-card>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                        <v-flex shrink xs12 sm12>
                            <template>
                                <v-layout wrap>
                                    <v-flex>
                                        <v-container>
                                            <v-layout row wrap>
                                                <v-flex xs12 md12 lg12>
                                                    <v-text-field v-model="search" append-icon="search"
                                                                  label="Numero Documento" single-line hide-details>
                                                    </v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                        <v-card>

                                            <v-data-table :search="search" :headers="headers" :items="listaEsquemas"
                                                          item-key="index">

                                                <template v-slot:items="props">
                                                    <td>
                                                        <v-btn color="info"
                                                               @click="getData(props.item.ordens,props.item.Num_Doc,props.item.id)">
                                                            Aplicaciones
                                                        </v-btn>
                                                    </td>
                                                    <td class="text-xs-center">
                                                        <v-tooltip top>
                                                            <template v-slot:activator="{ on }">
                                                                <v-btn text icon color="blue" dark v-on="on">
                                                                    <v-icon @click="getNotas(props.item.id)">
                                                                        list
                                                                    </v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>Notas enfermeria</span>
                                                        </v-tooltip>
                                                    </td>
                                                    <td class="text-xs-center">
                                                        <v-tooltip top>
                                                            <template v-slot:activator="{ on }">
                                                                <v-btn text icon color="blue" dark v-on="on">
                                                                    <v-icon @click="getPDF(props.item.id)">
                                                                        assignment_turned_in
                                                                    </v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>Historial</span>
                                                        </v-tooltip>
                                                    </td>
                                                    <td class="text-xs-center">{{ props.item.Num_Doc }}</td>
                                                    <td class="text-xs-center">{{ props.item.paciente }}</td>
                                                    <td class="text-xs-center">{{ props.item.scheme_name }}</td>
                                                    <td class="text-xs-center">{{ props.item.Fechaorden | date }}</td>
                                                </template>
                                                <template v-slot:no-data>
                                                    <v-alert :value="true" color="error" icon="warning">No hay
                                                        movimientos en este Estado.
                                                    </v-alert>
                                                </template>
                                            </v-data-table>
                                        </v-card>
                                    </v-flex>
                                </v-layout>
                            </template>
                        </v-flex>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-tab-item>
        <v-tab-item :value="'tab-2'">

            <v-dialog v-model="dialogHistoryOrdens" max-width="1100px">
                <v-card>
                    <v-toolbar flat color="primary" dark>
                        <v-toolbar-title>Ordenes</v-toolbar-title>
                    </v-toolbar>
                    <v-data-table :headers="headersOrdens" :items="listaHistoriaOrdenesOncologia" item-key="index">
                        <template v-slot:items="props">
                            <td class="text-xs-center"><a href="javascript:void(0)"
                                                          @click="dialog = true,listaDetalleOrden = props.item.detaarticulordens">{{
                                props.item.id }}</a></td>
                            <td class="text-xs-center">
                                <v-btn round color="info" @click="imprimir(props.item.id)">Imprimir</v-btn>
                            </td>
                            <td class="text-xs-center">{{ props.item.paginacion }}</td>
                            <td class="text-xs-center">{{ props.item.day }}</td>
                            <td class="text-xs-center">{{
                                props.item.detaarticulordens[0].fechaAutorizacion?props.item.detaarticulordens[0].fechaAutorizacion:"Sin Autorizar" }}
                            </td>
                            <td class="text-xs-center" v-if="props.item.Estado_id == 35">
                                <v-chip color="warning" text-color="white">{{ props.item.estado }}</v-chip>
                            </td>
                            <td class="text-xs-center" v-else-if="props.item.Estado_id == 1">
                                <v-chip color="success" text-color="white">{{ props.item.estado }}</v-chip>
                            </td>
                            <td class="text-xs-center" v-else-if="props.item.Estado_id == 7">
                                <v-chip color="success" text-color="white">Autorizado</v-chip>
                            </td>
                            <td class="text-xs-center" v-else>
                                <v-chip color="danger" text-color="white">{{ props.item.estado }}</v-chip>
                            </td>
                            <td class="text-xs-center" v-if="props.item.FechaAgendamiento">{{
                                props.item.FechaAgendamiento.substring(0,10)}}
                            </td>
                            <td class="text-xs-center" v-else>
                                Sin fecha
                            </td>
                            <td class="text-xs-center"
                                v-if="props.item.estadoEnfermeria === 'Aplicado'">
                                <v-chip color="success" text-color="white">Aplicado</v-chip>
                            </td>
                            <td class="text-xs-center"
                                v-else-if="props.item.estadoEnfermeria === 'Inasistencia'">
                                <v-chip color="error" text-color="white">Inasistencia</v-chip>
                            </td>
                            <td class="text-xs-center" v-else-if="props.item.estadoEnfermeria === 'Otro'">
                                <v-chip color="cyan" text-color="white">Otro</v-chip>
                            </td>
                        </template>

                        <template v-slot:no-data>
                            <v-alert :value="true" color="error" icon="warning">No hay movimientos en este
                                Estado.
                            </v-alert>
                        </template>
                    </v-data-table>
                </v-card>
            </v-dialog>

            <v-layout>
                <v-flex xs12 sm12>
                    <v-card>

                        <v-flex shrink xs12 sm12>
                            <template>

                                <v-layout wrap>
                                    <v-flex>
                                        <v-container>
                                            <v-layout row wrap>
                                                <v-flex xs3 md3 lg3>
                                                    <v-text-field v-model="numeroIdentificacion" outline label="Numero Documento" single-line hide-details></v-text-field>
                                                </v-flex>
                                                <v-flex xs3 md3 lg3>
                                                    <v-btn color="info" @click="findHistoryOncology">Buscar</v-btn>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>

                                        <v-card>

                                            <v-data-table :search="search" :headers="headers" :items="listHistoryOncology"
                                                          item-key="index">

                                                <template v-slot:items="props">
                                                    <td>
                                                        <v-btn color="info"
                                                               @click="getDataHistory(props.item.ordens,props.item.id)">
                                                            Aplicaciones
                                                        </v-btn>
                                                    </td>

                                                    <td class="text-xs-center">
                                                        <v-tooltip top>
                                                            <template v-slot:activator="{ on }">
                                                                <v-btn text icon color="blue" dark v-on="on">
                                                                    <v-icon @click="listaNotas=props.item.notaenfermeria,dialogGetNotes=true">
                                                                        list
                                                                    </v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>Notas enfermeria</span>
                                                        </v-tooltip>
                                                    </td>

                                                    <td class="text-xs-center">
                                                        <v-tooltip top>
                                                            <template v-slot:activator="{ on }">
                                                                <v-btn text icon color="blue" dark v-on="on">
                                                                    <v-icon @click="getPDF(props.item.id)">
                                                                        assignment_turned_in
                                                                    </v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>Historial</span>
                                                        </v-tooltip>
                                                    </td>
                                                    <td class="text-xs-center">{{ props.item.Num_Doc }}</td>
                                                    <td class="text-xs-center">{{ props.item.paciente }}</td>
                                                    <td class="text-xs-center">{{ props.item.scheme_name }}</td>
                                                    <td class="text-xs-center">{{ props.item.Fechaorden | date }}</td>
                                                </template>
                                                <template v-slot:no-data>
                                                    <v-alert :value="true" color="error" icon="warning">No hay
                                                        movimientos en este Estado.
                                                    </v-alert>
                                                </template>
                                            </v-data-table>
                                        </v-card>

                                    </v-flex>
                                </v-layout>
                            </template>
                        </v-flex>

                    </v-card>
                </v-flex>
            </v-layout>
        </v-tab-item>
    </v-tabs>
</template>
<script>
    import moment from "moment";
    import {mapGetters} from "vuex";

    moment.locale("es");
    export default {
        name: 'enfermera',
        data() {
            return {
                dialog: false,
                dialogDate: false,
                dialogState: false,
                dialogOrdens: false,
                dialogHistoryOrdens: false,
                dialogNote: false,
                dialogGetNotes: false,
                dialogCon: false,
                listaNotas: [],
                listaEsquemas: [],
                listHistoryOncology: [],
                listaOrdenesOncologia: [],
                listaDetalleOrden: [],
                listaHistoriaOrdenesOncologia:[],
                codigoOrden: null,
                history: {},
                idOrders: 0,
                search: "",
                especialidades:[],
                especialidad_selected:null,
                sedes:[],
                sede_selected:null,
                actividad_selected: null,
                medico_selected: null,
                agendas: [],
                datePicker: false,
                today: moment().format('YYYY-MM-DD'),
                dateSolicitada: false,
                fecha_selected: null,
                fecha: null,
                preload_cita: false,
                numeroIdentificacion:'',
                states: [
                    'Aplicado', 'Inasistencia', 'Otro', 'Reagendar'
                ],
                form: {
                    FechaAgendamiento: null
                },
                formState: {
                    estadoEnfermeria: null,
                    observacionEnfermeria: ""
                },
                headersOrdens: [{
                    text: "Orden",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Imprimir Ordenes",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Ciclos",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Dia",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "F.Auditoria",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Estado",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Fecha Agendamiento",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "",
                    sortable: false,
                    align: "center",
                    value: ""
                }],
                headerDetalle: [{
                    text: "Id",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "CodeSumi",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Medicamento",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Via",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Cantidad",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Frecuencia",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Observación",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Nota Auditoria",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Nota farmacia",
                    sortable: false,
                    align: "center",
                    value: ""
                }],

                headers: [{
                    text: "",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Ver Notas",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Ver Historia",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "N.Documento",
                    sortable: false,
                    align: "center",
                    value: "Num_Doc"
                }, {
                    text: "Paciente",
                    sortable: false,
                    align: "center",
                    value: "paciente"
                }, {
                    text: "Esquema",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Fecha Autorización",
                    sortable: false,
                    align: "center",
                    value: ""
                }],
                headersAgenda: [
                    { text: 'Hora', align: 'center', sortable: false, value: 'Agenda_id' },
                    { text: 'Consultorio', sortable: false, align: 'center', value: 'consultorio' },
                    { text: '', align: 'center', value: '' },
                ],
                formNotaEnfermeria: {
                    orden_id: 0,
                    nota: "",
                    signos_alarma: "",
                    cuidados_casa: "",
                    caso_urgencia: "",
                    alimentacion: "",
                    efectos_secundarios: "",
                    habito_higiene: "",
                    derechos_deberes: "",
                    normas_sala_quimioterapia: ""
                },
                data:{
                    cita_id: null,
                    hora_inicio: null,
                    consultorio: null,
                    Paciente_id: null,
                    fecha_solicitada: null,
                    observaciones: null,
                    cita_paciente_padre:null
                },
                paciente: {
                    Primer_Nom: '',
                    Primer_Ape: '',
                    Tipo_Doc: '',
                    Num_Doc: '',
                    Edad_Cumplida: ''
                },
            }
        },
        filters:{
            date: Fechaorden => {
                return moment(Fechaorden).format("LL");
            },
            time(date){
                return moment(date).format('HH:mm:ss');
            }
        },
        computed:{
            ...mapGetters(['can']),
            agendaDisponible(){
                let citasEnable = [];
                let citas = [];
                for (let i = 0; i < this.agendas.length; i++) {
                    let citas = [];
                    let medico = `${this.agendas[i].nombreMedico} ${this.agendas[i].apellidoMedico}`
                    if (medico === this.medico_selected &&
                        this.agendas[i].fecha == this.fecha ) {
                        citas = this.agendas[i].citas.map(cita =>  {
                            return {
                                id: cita.id,
                                hora_inicio: cita.Hora_Inicio,
                                consultorio: this.agendas[i].nombreConsultorio
                            }
                        });
                        citasEnable = citasEnable.concat(citas);
                    }
                }

                return citasEnable.sort((a, b) =>  moment(a.hora_inicio) - moment(b.hora_inicio));
            },
            actividades(){
                return this.especialidades.filter(e => this.especialidad_selected === e.id && this.sede_selected == e.sede)
            },
            medicosSede(){
                return this.agendas.map((agenda) => `${agenda.nombreMedico} ${agenda.apellidoMedico}`)
            },
        },
        methods: {
            agendaSolicitada(){
                this.dateSolicitada = false;
                this.fecha = this.data.fecha_solicitada;
                this.fetchAgendas();
            },
            fetchEspecialidades(){
                axios.get(`/api/agenda/agendaDisponible/especialidades`)
                    .then((res) => {
                        this.especialidades = res.data;
                        this.especialidad_selected = 49;
                        this.fetchSedes();
                        this.sede_selected = 7;
                        this.fetchAgendas();
                    });
            },
            fetchSedes(){
                axios.post(`/api/agenda/agendaDisponible/sedes`,{ especialidad: this.especialidad_selected })
                    .then((res) => {
                        this.sedes = res.data
                        console.log(res.data)
                    });
            },
            fetchAgendas(){
                this.datePicker = false;
                if(this.especialidad_selected && this.sede_selected && this.actividad_selected){
                    axios.post('/api/agenda/agendaDisponible', { fecha: this.fecha, sede: this.sede_selected, tipo_agenda: this.actividad_selected })
                        .then(res => {
                            this.agendas = res.data
                        });
                }

            },
            find() {
                try {
                    axios.get('/api/orden/getOrdersOncologyForNursing')
                        .then(res => {
                            this.listaEsquemas = res.data;
                        })
                } catch (e) {
                    console.log(e);
                }
            },
            findHistoryOncology(){
                if (this.numeroIdentificacion === '') {
                    swal({
                        title: "Debe ingresar un número de documento",
                        icon: "warning"
                    });
                    return;
                }

                axios.get('/api/orden/getHistoryOncology/'+this.numeroIdentificacion).then(res => {
                    if(res.data.length >= 1){
                        this.listHistoryOncology = res.data;
                    }else{
                        this.$alerError('El número de documento ingresado no se encuentra registrado en el sistema');
                    }
                })
            },
            assignDate() {
                try {
                    axios.post('/api/orden/updadateOrden/' + this.codigoOrden, this.form)
                        .then(res => {
                            if (res.status === 200) {
                                this.$alerSuccess(res.data.message);
                                this.dialogDate = false;
                                this.dialogOrdens = false;
                                this.find();
                            }
                        })
                } catch (e) {
                    console.log(e)
                }
            },
            validateOrden(fechaAgendamiento) {
                if (fechaAgendamiento) {
                    this.dialogState = true
                } else {
                    this.$alerWarning("La orden debe estar agendada");
                }
            },
            saveState() {
                let data = {};
                data.estadoEnfermeria = this.formState.estadoEnfermeria;
                data.observacionEnfermeria = this.formState.observacionEnfermeria;
                if (this.formState.estadoEnfermeria === "Reagendar") {
                    data.observacionEnfermeria = null;
                    data.FechaAgendamiento = null;
                }
                try {
                    axios.post('/api/orden/updadateOrden/' + this.codigoOrden, data)
                        .then(res => {
                            if (res.status === 200) {
                                this.$alerSuccess(res.data.message);
                                this.dialogState = false;
                                this.dialogOrdens = false;
                                this.find();
                            }
                        })
                } catch (e) {
                    console.log(e)
                }

            },
            saveNote() {
                axios.post('/api/esquemas/saveNote', this.formNotaEnfermeria).then(res => {
                    this.clearFormNote();
                    this.dialogNote = false;
                    if (res.status === 200) {
                        this.$alerSuccess(res.data.message)
                    }
                }).catch(e => {
                    console.log(e)
                })
            },
            clearFormNote() {
                for (const prop of Object.getOwnPropertyNames(this.formNotaEnfermeria)) {
                    this.formNotaEnfermeria[prop] = "";
                }
            },
            imprimir(id) {
                const data = {
                    type: 'oncologia',
                    orden: id,
                };
                axios.post("/api/pdf/print-pdf", data, {
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
            getPDFHistorial(historia) {
                return {
                    type: "test",
                    FechaInicio: historia.fechasolicita,
                    Nombre: historia.nombre,
                    Edad_Cumplida: historia.edad,
                    Sexo: historia.sexo,
                    Antropometricas: historia.antropometricas,
                    Atendido_Por: historia.atendido_por,
                    Cardiovascular: historia.cardiovascular,
                    telefono: historia.celular,
                    Condiciongeneral: historia.condiciongeneral,
                    Cédula: historia.cedula,
                    Datetimeingreso: historia.datetimeingreso,
                    Datetimesalida: historia.datetimesalida,
                    Departamento: historia.departamento,
                    Destinopaciente: historia.destinopaciente,
                    Diagnostico_Principal: historia.diagnosticoprincipal,
                    Diagnostico_Secundario: historia.Diagnostico_Secundario,
                    Direccion_Residencia: historia.direccion_Residencia,
                    Edad: historia.edad,
                    Endocrinologico: historia.endocrinologico,
                    Enfermedadactual: historia.enfermedadactual,
                    Entidademite: historia.entidademite,
                    Fecha_Nacimiento: historia.fechanacimiento,
                    Fecha_solicita: historia.fecha_solicita,
                    Finalidad: historia.Finalidad,
                    finalidadTrans: historia.finalidadTrans,
                    Gastrointestinal: historia.gastrointestinal,
                    Genitourinario: historia.genitourinario,
                    Laboraen: historia.laboraen,
                    Linfatico: historia.linfatico,
                    Medicoordeno: historia.medicoordeno,
                    Motivoconsulta: historia.motivoconsulta,
                    Observaciones: historia.observaciones,
                    Municipio_Afiliado: historia.municipio_Afiliado,
                    Neurologico: historia.neurologico,
                    Nombre: historia.nombre,
                    Norefiere: historia.norefiere,
                    Oftalmologico: historia.oftalmologico,
                    Osteomioarticular: historia.osteomioarticular,
                    Osteomuscular: historia.osteomuscular,
                    Otorrinoralingologico: historia.otorrinoralingologico,
                    Otros_Signos_Vitales: historia.otrossignosvitales,
                    Planmanejo: historia.planmanejo,
                    Presión_Arterial: historia.presionarterial,
                    Recomendaciones: historia.recomendaciones,
                    Respiratorio: historia.respiratorio,
                    Resultayudadiagnostica: historia.resultayudadiagnostica,
                    tratamientoncologico: historia.tratamientoncologico,
                    Tipocita_id: historia.Tipocita_id,
                    Tipocita: historia.Tipocita,
                    cirujiaoncologica: historia.cirujiaoncologica,
                    ncirujias: historia.ncirujias,
                    iniciocirujia: historia.iniciocirujia,
                    fincirujia: historia.fincirujia,
                    recibioradioterapia: historia.recibioradioterapia,
                    inicioradioterapia: historia.inicioradioterapia,
                    finradioterapia: historia.finradioterapia,
                    nsesiones: historia.nsesiones,
                    intencion: historia.intencion,
                    Sexo: historia.sexo,
                    Signos_Vitales: historia.signosvitales,
                    Tegumentario: historia.tegumentario,
                    Telefono: historia.telefono,
                    Timeconsulta: historia.timeconsulta,
                    Tipo_Afiliado: historia.tipoafiliado,
                    Tipo_Orden: historia.tipo_Orden,
                    Tipodiagnostico: historia.tipodiagnostico,
                    IPS: historia.IPS,
                    Antecedentes: historia.antecedentes,
                    Abdomen: historia.abdomen,
                    Agudezavisual: historia.agudezavisual,
                    Cabezacuello: historia.cabezacuello,
                    Cardiopulmonar: historia.cardiopulmonar,
                    Examenmama: historia.examenmama,
                    Examenmental: historia.examenmental,
                    Extremidades: historia.extremidades,
                    Frecucardiaca: historia.frecucardiaca,
                    Frecurespiratoria: historia.frecurespiratoria,
                    Genitourinario: historia.genitourinario,
                    Pulsosperifericos: historia.pulsosperifericos,
                    Ojosfondojos: historia.ojosfondojos,
                    Osteomuscular: historia.osteomuscular,
                    Pielfraneras: historia.pielfraneras,
                    Reflejososteotendinos: historia.reflejososteotendinos,
                    Tactoretal: historia.tactoretal,
                    Dietasaludable: historia.dietasaludable,
                    Suenoreparador: historia.suenoreparador,
                    Duermemenoseishoras: historia.duermemenoseishoras,
                    Altonivelestres: historia.altonivelestres,
                    Actividadfisica: historia.actividadfisica,
                    Frecuenciasemana: historia.frecuenciasemana,
                    Duracion: historia.duracion,
                    Fumacantidad: historia.fumacantidad,
                    Fumainicio: historia.fumainicio,
                    Fumadorpasivo: historia.fumadorpasivo,
                    Cantidadlicor: historia.cantidadlicor,
                    Licorfrecuencia: historia.licorfrecuencia,
                    Consumopsicoactivo: historia.consumopsicoactivo,
                    Psicoactivocantidad: historia.psicoactivocantidad,
                    Estilovidaobservaciones: historia.estilovidaobservaciones,
                    Cedulamedico: historia.cedulamedico,
                    Registromedico: historia.registromedico,
                    Firma: historia.firma,
                    Fechaultimamenstruacion: historia.fechaultimamenstruacion,
                    marcapaciente: historia.marcapaciente,
                    Especialidad: historia.especialidad,
                    id: historia.id
                };
            },
            getHistoryObj(historia) {
                return {
                    nombre: historia.Nombre,
                    edad: historia.Edad,
                    sexo: historia.Sexo,
                    antropometricas: historia.Antropometricas,
                    atendido_por: historia.Atendido_Por,
                    cardiovascular: historia.Cardiovascular,
                    celular: historia.Celular,
                    condiciongeneral: historia.Condiciongeneral,
                    cedula: historia.Cédula,
                    datetimeingreso: historia.Datetimeingreso,
                    datetimesalida: historia.Datetimesalida,
                    departamento: historia.Departamento,
                    destinopaciente: historia.Destinopaciente,
                    diagnosticoprincipal: historia.Diagnostico_Principal,
                    Diagnostico_Secundario: historia.Diagnostico_Secundario,
                    direccionresidencia: historia.Direccion_Residencia,
                    endocrinologico: historia.Endocrinologico,
                    enfermedadactual: historia.Enfermedadactual,
                    entidademite: historia.Entidademite,
                    fechanacimiento: historia.Fecha_Nacimiento,
                    fechasolicita: historia.Fecha_solicita,
                    Finalidad: historia.Finalidad,
                    finalidadTrans: historia.finalidadTrans,
                    gastrointestinal: historia.Gastrointestinal,
                    genitourinario: historia.Genitourinario,
                    laboraen: historia.Laboraen,
                    linfatico: historia.Linfatico,
                    medicoordeno: historia.Medicoordeno,
                    motivoconsulta: historia.Motivoconsulta,
                    observaciones: historia.Observaciones,
                    municipioafiliado: historia.Municipio_Afiliado,
                    neurologico: historia.Neurologico,
                    norefiere: historia.Norefiere,
                    observaciones: historia.Observaciones,
                    oftalmologico: historia.Oftalmologico,
                    osteomioarticular: historia.Osteomioarticular,
                    osteomuscular: historia.Osteomuscular,
                    otorrinoralingologico: historia.Otorrinoralingologico,
                    otrossignosvitales: historia.Otros_Signos_Vitales,
                    planmanejo: historia.Planmanejo,
                    presionarterial: historia.Presión_Arterial,
                    recomendaciones: historia.Recomendaciones,
                    respiratorio: historia.Respiratorio,
                    resultayudadiagnostica: historia.Resultayudadiagnostica,
                    tratamientoncologico: historia.tratamientoncologico,
                    Tipocita_id: historia.Tipocita_id,
                    Tipocita: historia.Tipocita,
                    cirujiaoncologica: historia.cirujiaoncologica,
                    ncirujias: historia.ncirujias,
                    iniciocirujia: historia.iniciocirujia,
                    fincirujia: historia.fincirujia,
                    recibioradioterapia: historia.recibioradioterapia,
                    inicioradioterapia: historia.inicioradioterapia,
                    finradioterapia: historia.finradioterapia,
                    nsesiones: historia.nsesiones,
                    intencion: historia.intencion,
                    signosvitales: historia.Signos_Vitales,
                    tegumentario: historia.Tegumentario,
                    telefono: historia.Telefono,
                    timeconsulta: historia.Timeconsulta,
                    tipoafiliado: historia.Tipo_Afiliado,
                    tipoorden: historia.Tipo_Orden,
                    tipodiagnostico: historia.Tipodiagnostico,
                    antecedentes: historia.Antecedentes,
                    abdomen: historia.Abdomen,
                    agudezavisual: historia.Agudezavisual,
                    cabezacuello: historia.Cabezacuello,
                    cardiopulmonar: historia.Cardiopulmonar,
                    examenmama: historia.Examenmama,
                    examenmental: historia.Examenmental,
                    extremidades: historia.Extremidades,
                    frecucardiaca: historia.Frecucardiaca,
                    frecurespiratoria: historia.Frecurespiratoria,
                    genitourinario: historia.Genitourinario,
                    neurologico: historia.Neurologico,
                    ojosfondojos: historia.Ojosfondojos,
                    osteomuscular: historia.Osteomuscular,
                    pielfraneras: historia.Pielfraneras,
                    reflejososteotendinos: historia.Reflejososteotendinos,
                    tactoretal: historia.Tactoretal,
                    dietasaludable: historia.Dietasaludable,
                    suenoreparador: historia.Suenoreparador,
                    duermemenoseishoras: historia.Duermemenoseishoras,
                    altonivelestres: historia.Altonivelestres,
                    actividadfisica: historia.Actividadfisica,
                    frecuenciasemana: historia.Frecuenciasemana,
                    duracion: historia.Duracion,
                    fumacantidad: historia.Fumacantidad,
                    fumainicio: historia.Fumainicio,
                    fumadorpasivo: historia.Fumadorpasivo,
                    cantidadlicor: historia.Cantidadlicor,
                    licorfrecuencia: historia.Licorfrecuencia,
                    consumopsicoactivo: historia.Consumopsicoactivo,
                    psicoactivocantidad: historia.Psicoactivocantidad,
                    estilovidaobservaciones: historia.Estilovidaobservaciones,
                    cedulamedico: historia.Cedulamedico,
                    registromedico: historia.Registromedico,
                    firma: historia.Firma,
                    fechaultimamenstruacion: historia.Fechaultimamenstruacion,
                    marcapaciente: historia.marcapaciente,
                    especialidad: historia.Especialidad,
                    id: historia.id
                };
            },
            async getPDF(id) {
                await axios.get('/api/historiapaciente/getHistoryByCita/' + id).then(res => {
                    this.history = this.getHistoryObj(res.data[0]);
                })
                this.printhc()
            },
            printhc() {
                if (this.history) {
                    let pdf = this.getPDFHistorial(this.history);
                    pdf.type = "test";
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
                        });
                }
            },
            getNotas(id) {
                axios.get('/api/esquemas/getNotas/' + id).then(res => {
                    this.listaNotas = res.data;
                    this.dialogGetNotes = true;
                })
            },
            reloadOrders() {
                this.find()
                let objIndex = this.listaEsquemas.findIndex((obj => obj.id === this.idOrders));
                if (objIndex > -1) {
                    this.listaOrdenesOncologia = [];
                }
                //this.listaEsquemas
            },
            getDataHistory(orders, id) {
                this.dialogHistoryOrdens = true;
                this.listaHistoriaOrdenesOncologia = orders;
                this.idOrders = id;
            },
            getData(orders,Num_Doc,id){

                axios.get(`/api/paciente/showEnabled/${Num_Doc}`)
                    .then((res) => {
                        if(res.data.paciente) {
                            this.data.cita_paciente_padre = id,
                            this.paciente = res.data.paciente;
                            this.dialogOrdens = true;
                            this.listaOrdenesOncologia = orders;
                        }
                        if(res.data.message) this.showMessage(res.data.message);
                    });
            },
            asignarCita(cita){

                this.dialogCon = true;
                this.data.cita_id = cita.id;
                this.data.hora_inicio = cita.hora_inicio;
                this.data.consultorio = cita.consultorio;
                this.data.Paciente_id = this.paciente.id;
                this.form.FechaAgendamiento = moment(this.fecha).format('Y-MM-DD');
                this.fecha_selected = moment(this.fecha).format('dddd, D MMMM, YYYY');
            },
            agendarCita(){
                this.preload_cita = true;
                axios.put(`/api/cita/asignarcita/${this.data.cita_id}`, this.data )
                    .then((res) => {
                        axios.post('/api/orden/updadateOrden/' + this.codigoOrden, this.form)
                            .then(res => {
                                if (res.status === 200) {
                                    this.dialogCon = false;
                                    this.dialogDate = false
                                    this.dialogOrdens = false;
                                    this.clearFields();
                                    this.preload_cita = false;
                                    this.find();
                                }
                            });

                    })
                    .catch((err) => this.showMessage(err.response.data.message))
            },
            clearFields(){
                    this.medico_selected = null,
                    this.sede_selected = null,
                    this.actividad_selected = null,
                    this.especialidad_selected = null,
                    this.fecha_selected = null,
                    this.fecha = moment().format('YYYY-MM-DD'),
                    this.paciente = {
                        Primer_Nom: '',
                        Primer_Ape: '',
                        Tipo_Doc: '',
                        Num_Doc: '',
                        Edad_Cumplida: ''
                    }
                this.data = {
                    Paciente_id: null,
                    cita_id: null,
                    hora_inicio: null,
                    consultorio: null,
                    cita_paciente_padre:null,
                    fecha_solicitada: moment().format('YYYY-MM-DD'),
                }
            },
        },
        mounted() {
            this.find();
            this.fetchEspecialidades();
        }
    }
</script>
