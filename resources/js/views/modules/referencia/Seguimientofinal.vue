<template>
    <v-card>
        <v-card-title>
            <h3>Seguimiento Concurrencia</h3>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="seguimiento" :search="search">
            <template v-slot:items="props">
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.Primer_Nom }} {{ props.item.SegundoNom }} {{ props.item.Primer_Ape }}
                    {{ props.item.Segundo_Ape }}</td>
                <td>{{ props.item.Num_Doc }}</td>
                <td>{{ props.item.Especialidad_remi }}</td>
                <td>{{ props.item.ips_atencion }}</td>
                <td>{{ props.item.created_at }}</td>
                <td v-if="props.item.estadoconcurrencia_id == '43'">
                    <v-chip color="warning" text-color="white">En seguimiento</v-chip>
                </td>
                <td>
                    <v-btn fab dark small color="purple" @click="openRegistro(props.item)"
                        v-if="can('editar.concurrencia')">
                        <v-icon dark>mdi-pencil</v-icon>
                    </v-btn>
                </td>
                <td>
                    <v-btn fab dark small color="primary" @click="verConcurrencias(props.item.referencia_id)"
                        v-if="can('ver.seguimientosConcurrencia')">
                        <v-icon dark>mdi-eye</v-icon>
                    </v-btn>
                </td>
            </template>
            <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </template>
        </v-data-table>
        <v-dialog v-model="seguimientoReferencia" persistent max-width="1100px">
            <v-card>
                <v-card-title class="headline info" style="color:white">
                    <span class="title layout justify-center font-weight-light">Generar nuevo seguimiento</span>
                </v-card-title>
                <v-card-text>
                    <form @submit.prevent="guardarseguimiento()">
                        <v-card-text>
                            <v-container fluid grid-list-md>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-textarea v-model="seguimientoCon.seguimientoConcurrencia" name="input-7-1"
                                            label="Ingrese seguimiento al paciente" color="success"
                                            hint="Los seguimientos no se pueden editar una vez guardados"></v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 d-flex>
                                        <v-select :items="estados" v-model="seguimientoCon.estado"
                                            label="Destino de paciente">
                                        </v-select>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" dark @click="seguimientoReferencia = false, clearData()">Cerrar</v-btn>
                            <v-btn color="success" dark type="submit">Guardar</v-btn>
                        </v-card-actions>
                    </form>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog scrollable v-model="editregistro" v-if="editregistro" persistent max-width="1200px">
            <v-card>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Editar registro concurrencia</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm6 md2>
                                <v-text-field label="Tipo identificacion" v-model="data_seguimiento.Tipo_Doc" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md2>
                                <v-text-field label="Identificacion" v-model="data_seguimiento.Num_Doc" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md7>
                                <v-text-field label="Nombre completo"
                                    v-model="data_seguimiento.Primer_Nom+' '+data_seguimiento.SegundoNom+' '+data_seguimiento.Primer_Ape+' '+data_seguimiento.Segundo_Ape"
                                    readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md1>
                                <v-text-field label="Edad" v-model="data_seguimiento.Edad_Cumplida" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field label="Asegurador"
                                    v-model="data_seguimiento.name+' '+data_seguimiento.apellido" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field label="Ips Primaria"
                                    v-model="data_seguimiento.name+' '+data_seguimiento.apellido" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field label="Medico Familia"
                                    v-model="data_seguimiento.name+' '+data_seguimiento.apellido" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field label="Auditor"
                                    v-model="data_seguimiento.name+' '+data_seguimiento.apellido" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-select
                                    :items="['Consulta Externa', 'Medicina Domiciliaria', 'Prioritaria', 'Programado', 'Referencia', 'Reingreso', 'Urgencias', 'Traslado primario ']"
                                    label="Via de ingreso" v-model="data_seguimiento.via_ingreso">
                                </v-select>
                                <!-- <v-text-field label="Via de ingreso" v-model="data_seguimiento.via_ingreso" readonly>
                                </v-text-field> -->
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-select
                                    :items="['Altas tempranas no pertinentes.', 'Altas voluntarias.', 'Complicación posterior a la realización de procedimiento.', 'Deterioro del estado clínico.', 'Disfuncionalidad de catéteres o sondas (patologías crónicas)', 'Eventos relacionados con uso de medicamentos prescritos', 'Falta oportunidad en procedimiento ambulatorios posterior al alta (diferidas).', 'Infección de sitio operatorio – ISO.', 'No adherencia al tratamiento por parte del paciente.', 'No dispensación medicamento posterior al alta.', 'No ingreso a programas ni captación de gestión de riesgo posterior al alta.', 'No es un reingreso a hospitalización por la misma causa antes de 15 días']"
                                    label="Reingreso hospitalización"
                                    v-model="data_seguimiento.reingreso_hospitalización15días">
                                </v-select>
                                <!-- <v-text-field label="Reingreso hospitalización"
                                    v-model="data_seguimiento.reingreso_hospitalización15días" readonly>
                                </v-text-field> -->
                            </v-flex>
                            <v-flex xs12 sm6 md2 v-if="data_seguimiento.created_at">
                                <v-text-field label="Fecha registro" v-model="data_seguimiento.created_at.split('.')[0]"
                                    readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md2>
                                <v-text-field label="Fecha ingreso concurrencia" type="date"
                                    v-model="data_seguimiento.fecha_ingreso_concurrencia">
                                </v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6 md2>
                                <v-text-field label="Fecha egreso" v-model="data_seguimiento.fecha_egreso" type="date">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-select :items="[
                                                    'Cesárea',
                                                    'Hospitalización Médica',
                                                    'Hospitalización Quirúrgica',
                                                    'Intermedio Adulto',
                                                    'Intermedio Neonatal',
                                                    'Intermedio Pediatría',
                                                    'Observación ',
                                                    'Otras catastróficas',
                                                    'Parto',
                                                    'UCI Adulto',
                                                    'UCI Neonatal',
                                                    'UCI Pediatría',
                                                    'Urgencias'
                                                ]" label="Tipo Hospitalizacion"
                                    v-model="data_seguimiento.unidad_funcional">
                                </v-select>
                            </v-flex>
                            <!-- Edit -->
                            <v-flex xs12 sm6 md3>
                                <v-text-field label="Dias estancia observacion" @change="total"
                                    v-model="data_seguimiento.dias_estancia_observacion" type="number" min="0">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md3>
                                <v-text-field label="Dias estancia intensivo" @change="total"
                                    v-model="data_seguimiento.dias_estancia_intensivo" type="number" min="0">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md2>
                                <v-text-field label="Dias estancia intermedio" @change="total"
                                    v-model="data_seguimiento.dias_estancia_intermedio" type="number" min="0">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md2>
                                <v-text-field label="Dias estancia basico" @change="total"
                                    v-model="data_seguimiento.dias_estancia_basicos" type="number" min="0">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md2>
                                <v-text-field label="Estancia total días" v-model="data_seguimiento.estancia_total_dias"
                                    readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md12>
                                <v-autocomplete v-model="data_seguimiento.cie10_id" :items="cieConcat"
                                    class="search-field" item-text="nombre" item-value="id"
                                    label="Diagnóstico de egreso"
                                    :placeholder="data_seguimiento.Codigo_CIE10+' - '+data_seguimiento.Descripcion">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm6 md12>
                                <v-autocomplete v-model="data_seguimiento.dx_concurrencia" :items="cieConcat"
                                    class="search-field" item-text="nombre" item-value="id"
                                    label="Diagnóstico concurrencia">
                                </v-autocomplete>
                            </v-flex>

                            <v-flex xs12 sm6 md6>
                                <v-autocomplete v-model="data_seguimiento.cie10_id_egresoAsociado" append-icon="search"
                                    :items="cieConcat" item-disabled="customDisabled" item-text="nombre"
                                    item-value="Nombre" label="Diagnóstico de egreso asociado">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-select :items="d_egreso" v-model="data_seguimiento.destino_egreso"
                                    label="Destino egreso"></v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field label="Costo atencion" v-model="data_seguimiento.costo_atencion"
                                    type="number" min="0">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-select :items="especialidad_t" label="Especialidad tratante"
                                    v-model="data_seguimiento.especialidad_tratante">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md2>
                                <v-text-field label="Peso" v-model="data_seguimiento.peso_rn">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md2>
                                <v-text-field label="Edad gestacional" v-model="data_seguimiento.edad_gestacional">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md8>
                                <v-select :items="reporte_patologia" label="Reporte patologia dx"
                                    v-model="data_seguimiento.reporte_patologia_diagnostica">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md12>
                                <v-select :items="hospitalizacion_e" label="Hospitalización evitable"
                                    v-model="data_seguimiento.hospitalizacion_evitable">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md12>
                                <v-checkbox v-model="eventoSeguridad" label="Evento de seguridad" color="success"
                                    value="si" hide-details>
                                </v-checkbox>
                            </v-flex>
                            <v-flex xs12 sm6 md4 v-if="eventoSeguridad == 'si'">
                                <v-select :items="eventos_seguridad" label="Eventos de seguridad"
                                    v-model="data_evento.eventos_seguridad">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md12 v-if="eventoSeguridad == 'si'">
                                <v-textarea label="Observaciones de seguridad"
                                    v-model="data_evento.observacion_seguridad"></v-textarea>
                            </v-flex>
                            <v-btn v-if="eventoSeguridad == 'si'" fab dark color="info" @click="eventos('seguridad')">
                                <v-icon dark>add</v-icon>
                            </v-btn>
                            <v-flex xs12 v-if="eventoSeguridad == 'si'">
                                <v-card>
                                    <v-data-table :items="arrEventoSeguridad" class="elevation-1" hide-actions
                                        :headers="headerseguridad">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.eventos_seguridad }}</td>
                                            <td>{{ props.item.observacion_seguridad }}</td>
                                            <td>
                                                <v-btn fab dark v-if="!props.item.actualizar" color="error" small
                                                    @click="eventosClear('seguridad',props.item.eventos_seguridad)">
                                                    <v-icon dark>close</v-icon>
                                                </v-btn>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card>
                                <v-card-actions
                                    v-if="arrEventoSeguridad.length > 0 && !arrEventoSeguridad[arrEventoSeguridad.length-1].actualizar">
                                    <v-spacer></v-spacer>
                                    <v-btn color="success" @click="guardarEventoSeguridad(23)">
                                        Guardar Evento Seguridad
                                    </v-btn>
                                </v-card-actions>
                            </v-flex>
                            <v-flex xs12 sm6 md12>
                                <v-checkbox v-model="eventoCentinela" label="Evento de centinelas" color="warning"
                                    value="si" hide-details>
                                </v-checkbox>
                            </v-flex>
                            <v-flex xs12 sm6 md4 v-if="eventoCentinela == 'si'">
                                <v-select :items="eventos_centinelas" label="Eventos centinelas"
                                    v-model="data_evento.eventos_centinelas">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md6 v-if="eventoCentinela == 'si'">
                                <v-textarea label="Observaciones de centinela"
                                    v-model="data_evento.observacion_centinela"></v-textarea>
                            </v-flex>
                            <v-btn v-if="eventoCentinela == 'si'" fab dark color="success"
                                @click="eventos('centinela')">
                                <v-icon dark>add</v-icon>
                            </v-btn>
                            <v-flex xs12 v-if="eventoCentinela == 'si'">
                                <v-card>
                                    <v-data-table :items="arrEventoCentinela" class="elevation-1" hide-actions
                                        :headers="headerseguridad">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.eventos_seguridad }}</td>
                                            <td>{{ props.item.observacion_seguridad }}</td>
                                            <td>
                                                <v-btn fab dark v-if="!props.item.actualizar" color="error" small
                                                    @click="eventosClear('centinela',props.item.eventos_seguridad)">
                                                    <v-icon dark>close</v-icon>
                                                </v-btn>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card>
                                <v-card-actions
                                    v-if="arrEventoCentinela.length > 0 && !arrEventoCentinela[arrEventoCentinela.length-1].actualizar">
                                    <v-spacer></v-spacer>
                                    <v-btn color="success" @click="guardarEventoSeguridad(24)">
                                        Guardar Notificaciones Centinelas
                                    </v-btn>
                                </v-card-actions>
                            </v-flex>
                            <v-flex xs12 sm6 md12>
                                <v-checkbox v-model="eventoNotificacion" label="Eventos de notificacion inmedita"
                                    color="info" value="si" hide-details>
                                </v-checkbox>
                            </v-flex>
                            <v-flex xs12 sm6 md4 v-if="eventoNotificacion == 'si'">
                                <v-select :items="eventos_notificacion" label="Eventos de notificacion inmedita"
                                    v-model="data_evento.eventos_notificacion_inmediata">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field label="descripcion gestion" v-if="eventoNotificacion == 'si'"
                                    v-model="data_evento.descripicion_gestion_realizada">
                                </v-text-field>
                            </v-flex>
                            <v-btn v-if="eventoNotificacion == 'si'" fab dark color="success"
                                @click="eventos('notificacion')">
                                <v-icon dark>add</v-icon>
                            </v-btn>
                            <v-flex xs12 v-if="eventoNotificacion == 'si'">
                                <v-card>
                                    <v-data-table :items="arrEventoNotificacion" class="elevation-1" hide-actions
                                        :headers="headerseguridad">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.eventos_seguridad }}</td>
                                            <td>{{ props.item.observacion_seguridad }}</td>
                                            <td>
                                                <v-btn fab dark v-if="!props.item.actualizar" color="error" small
                                                    @click="eventosClear('notificacion',props.item.eventos_seguridad)">
                                                    <v-icon dark>close</v-icon>
                                                </v-btn>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card>
                                <v-card-actions
                                    v-if="arrEventoNotificacion.length > 0 && !arrEventoNotificacion[arrEventoNotificacion.length-1].actualizar">
                                    <v-spacer></v-spacer>
                                    <v-btn color="success" @click="guardarEventoSeguridad(25)">
                                        Guardar Evento Notificacion
                                    </v-btn>
                                </v-card-actions>
                            </v-flex>
                            <!-- INICIO COSTO EVITADO -->
                            <v-flex xs12 sm6 md12>
                                <v-checkbox v-model="costoEvitado" label="Costo evitado" color="info" value="si"
                                    hide-details>
                                </v-checkbox>
                            </v-flex>
                            <v-flex xs12 sm6 md12 v-if="costoEvitado == 'si'">
                                <v-select :items="costo_evitado" label="Costo evitado"
                                    v-model="data_evento.costo_evitado">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md6 v-if="costoEvitado == 'si'">
                                <v-text-field label="Descripcion costo" v-model="data_evento.descripcion_costo_evitado">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6 v-if="costoEvitado == 'si'">
                                <v-text-field label="Valor costo evitado" v-model="data_evento.valor_costo_evitado"
                                    type="number" min="0">
                                </v-text-field>
                            </v-flex>

                            <v-btn v-if="costoEvitado == 'si'" fab dark color="success"
                                @click="eventos('costoevitado')">
                                <v-icon dark>add</v-icon>
                            </v-btn>
                            <v-flex xs12 v-if="costoEvitado == 'si'">
                                <v-card>
                                    <v-data-table :items="arrCostoEvitado" class="elevation-1" hide-actions
                                        :headers="headerseguridad">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.eventos_seguridad }}</td>
                                            <td>{{ props.item.observacion_seguridad }}</td>
                                            <td>{{ props.item.valor_costo_evitado }}</td>
                                            <td>
                                                <v-btn fab dark v-if="!props.item.actualizar" color="error" small
                                                    @click="eventosClear('costoevitado',props.item.eventos_seguridad)">
                                                    <v-icon dark>close</v-icon>
                                                </v-btn>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card>
                                <v-card-actions
                                    v-if="arrCostoEvitado.length > 0 && !arrCostoEvitado[arrCostoEvitado.length-1].actualizar">
                                    <v-spacer></v-spacer>
                                    <v-btn color="success" @click="guardarEventoSeguridad(26)">
                                        Guardar Costo Evitado
                                    </v-btn>
                                </v-card-actions>
                            </v-flex>
                            <!-- INICIO COSTO EVITABLE -->
                            <v-flex xs12 sm6 md12>
                                <v-checkbox v-model="costoEvitable" label="Costo evitable" color="info" value="si"
                                    hide-details>
                                </v-checkbox>
                            </v-flex>
                            <v-flex xs12 sm6 md4 v-if="costoEvitable == 'si'">
                                <v-select :items="costo_evitables" label="Costo evitable"
                                    v-model="data_evento.costo_evitable">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md8 v-if="costoEvitable == 'si'">
                                <v-text-field label="Descripcion costo"
                                    v-model="data_evento.descripcion_costo_evitable">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4 v-if="costoEvitable == 'si'">
                                <v-text-field label="Valor costo evitables" v-model="data_evento.valor_costo_evitable"
                                    type="number" min="0">
                                </v-text-field>
                            </v-flex>
                            <v-btn v-if="costoEvitable == 'si'" fab dark color="success"
                                @click="eventos('costoevitable')">
                                <v-icon dark>add</v-icon>
                            </v-btn>
                            <v-flex xs12 v-if="costoEvitable == 'si'">
                                <v-card>
                                    <v-data-table :items="arrCostoEvitable" class="elevation-1" hide-actions
                                        :headers="headerseguridad">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.eventos_seguridad }}</td>
                                            <td>{{ props.item.observacion_seguridad }}</td>
                                            <td>{{ props.item.valor_costo_evitable }}</td>
                                            <td>
                                                <v-btn fab dark v-if="!props.item.actualizar" color="error" small
                                                    @click="eventosClear('costoevitable',props.item.eventos_seguridad)">
                                                    <v-icon dark>close</v-icon>
                                                </v-btn>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card>
                                <v-card-actions
                                    v-if="arrCostoEvitable.length > 0 && !arrCostoEvitable[arrCostoEvitable.length-1].actualizar">
                                    <v-spacer></v-spacer>
                                    <v-btn color="success" @click="guardarEventoSeguridad(27)">
                                        Guardar Costo Evitable
                                    </v-btn>
                                </v-card-actions>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-select :items="alto_costos" label="Alto costo" v-model="data_seguimiento.alto_costo">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-select :items="incumplimientos" label="Incumplimiento habilitación"
                                    v-model="data_seguimiento.incumplimiento_habilitación">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md5>
                                <v-text-field label="Descripcion habilitación"
                                    v-model="data_seguimiento.descripcion_habilitación">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md5>
                                <v-text-field label="Asesoria especialista"
                                    v-model="data_seguimiento.asesoria_especialista">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md2>
                                <v-text-field label="Número factura" v-model="data_seguimiento.numero_factura"
                                    type="number">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-textarea label="Observaciones de concurrencia"
                                    v-model="data_seguimiento.lider_concurrencia"></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red" dark @click="editregistro = false, seguimientoConcurrencia()">Cerrar</v-btn>
                    <v-btn color="success" dark @click="updateRegistro()">Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogverSeguimiento" v-if="dialogverSeguimiento" max-width="1200px" persistent>
            <v-card>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Seguimientos del paciente</span>
                </v-card-title>
                <v-card-text>
                    <v-layout wrap>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md2>
                                    <v-text-field label="Tipo identificacion" v-model="datosSeguimientos[0].Tipo_Doc"
                                        readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-text-field label="Identificacion" v-model="datosSeguimientos[0].Num_Doc"
                                        readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md7>
                                    <v-text-field label="Nombre completo"
                                        v-model="datosSeguimientos[0].Primer_Nom+' '+datosSeguimientos[0].SegundoNom+' '+datosSeguimientos[0].Primer_Ape+' '+datosSeguimientos[0].Segundo_Ape"
                                        readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md1>
                                    <v-text-field label="Edad" v-model="datosSeguimientos[0].Edad_Cumplida" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field label="Auditor"
                                        v-model="datosSeguimientos[0].name+' '+datosSeguimientos[0].apellido" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select
                                        :items="['Consulta Externa', 'Medicina Domiciliaria', 'Prioritaria', 'Programado', 'Referencia', 'Reingreso', 'Urgencias', 'Traslado primario ']"
                                        label="Via de ingreso" v-model="datosSeguimientos[0].via_ingreso">
                                    </v-select>
                                    <!-- <v-text-field label="Via de ingreso" v-model="datosSeguimientos[0].via_ingreso" readonly>
                                </v-text-field> -->
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select
                                        :items="['Altas tempranas no pertinentes.', 'Altas voluntarias.',
                                                    'Complicación posterior a la realización de procedimiento.',
                                                    'Deterioro del estado clínico.', 'Disfuncionalidad de catéteres o sondas (patologías crónicas)',
                                                     'Eventos relacionados con uso de medicamentos prescritos',
                                                      'Falta oportunidad en procedimiento ambulatorios posterior al alta (diferidas).',
                                                      'Infección de sitio operatorio – ISO.', 'No adherencia al tratamiento por parte del paciente.',
                                                       'No dispensación medicamento posterior al alta.',
                                                        'No ingreso a programas ni captación de gestión de riesgo posterior al alta.',
                                                        'No es un reingreso a hospitalización por la misma causa antes de 15 días',
                                                        'reingreso por la misma causa o causa asociada antes de 30 dias']"
                                        label="Reingreso hospitalización"
                                        v-model="datosSeguimientos[0].reingreso_hospitalización15días">
                                    </v-select>
                                    <!-- <v-text-field label="Reingreso hospitalización"
                                    v-model="datosSeguimientos[0].reingreso_hospitalización15días" readonly>
                                </v-text-field> -->
                                </v-flex>
                                <v-flex xs12 sm6 md2 v-if="datosSeguimientos[0].created_at">
                                    <v-text-field label="Fecha registro"
                                        v-model="datosSeguimientos[0].created_at.split('.')[0]" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-text-field label="Fecha ingreso concurrencia" type="date"
                                        v-model="datosSeguimientos[0].fecha_ingreso_concurrencia">
                                    </v-text-field>
                                </v-flex>

                                <v-flex xs12 sm6 md2>
                                    <v-text-field label="Fecha egreso" v-model="datosSeguimientos[0].fecha_egreso"
                                        type="date">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="[
                                                    'Cesárea',
                                                    'Hospitalización Médica',
                                                    'Hospitalización Quirúrgica',
                                                    'Intermedio Adulto',
                                                    'Intermedio Neonatal',
                                                    'Intermedio Pediatría',
                                                    'Observación ',
                                                    'Otras catastróficas',
                                                    'Parto',
                                                    'UCI Adulto',
                                                    'UCI Neonatal',
                                                    'UCI Pediatría',
                                                    'Urgencias'
                                                ]" label="Tipo Hospitalización"
                                        v-model="datosSeguimientos[0].unidad_funcional">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-text-field label="Estancia total días"
                                        v-model="datosSeguimientos[0].estancia_total_dias" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md12>
                                    <v-autocomplete v-model="datosSeguimientos[0].cie10_id" :items="cieConcat"
                                        class="search-field" item-text="nombre" item-value="id"
                                        label="Diagnóstico de egreso">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md12>
                                    <v-autocomplete v-model="datosSeguimientos[0].dx_concurrencia" :items="cieConcat"
                                        class="search-field" item-text="nombre" item-value="id"
                                        label="Diagnóstico concurrencia">
                                    </v-autocomplete>
                                </v-flex>

                                <v-flex xs12 sm6 md6>
                                    <v-autocomplete v-model="datosSeguimientos[0].cie10_id_egresoAsociado"
                                        append-icon="search" :items="cieConcat" item-disabled="customDisabled"
                                        item-text="nombre" item-value="Nombre" label="Diagnóstico de egreso asociado">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select :items="d_egreso" v-model="datosSeguimientos[0].destino_egreso"
                                        label="Destino egreso"></v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select :items="especialidad_t" label="Especialidad tratante"
                                        v-model="datosSeguimientos[0].especialidad_tratante">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-text-field label="Edad gestacional"
                                        v-model="datosSeguimientos[0].edad_gestacional">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12>
                                    <v-textarea label="Observaciones de concurrencia"
                                        v-model="datosSeguimientos[0].lider_concurrencia"></v-textarea>
                                </v-flex>
                            </v-layout>
                            <v-card-title class="headline success" style="color:white">
                                <span class="title layout justify-center font-weight-light">Costo y Notas</span>
                            </v-card-title>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md8>
                                    <v-autocomplete v-model="costos.cup_id" label="Seleccione el cups"
                                        :item-text="cups => cups.Codigo + ' - ' + cups.Nombre" item-value="id"
                                        :items="cups"></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
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
                            <v-card>
                                <v-card-title>
                                    Costo
                                    <v-spacer></v-spacer>
                                    <span>Total: <b>{{ totalCosto }}</b></span>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line
                                        hide-details></v-text-field>
                                </v-card-title>
                                <v-data-table :headers="headersCosto" :items="costo"
                                    no-data-text="Sin datos para mostrar" class="elevation-1">
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
                            <v-flex xs12 md12 text-xs-center>
                                <br/>
                                <span><b>Estancia Hospitalizacion:</b> {{ cantidadHospi }} </span><br/>
                                <span><b>Estancia UCE:</b> {{ cantidadUce }} </span><br/>
                                <span><b>Estancia UCI:</b> {{ cantidadUci }} </span><br/>
                                <span><b>Estancia Total:</b> {{ cantidadTotal}} </span>
                            </v-flex>
                            <v-flex xs12 md12 text-xs-center>
                                <v-btn dark color="success" v-if="can('crearSeguimiento.concurrencia')"
                                    @click="abrirModalNota()">
                                    Agregar nuevo seguimiento
                                </v-btn>
                            </v-flex>
                            <v-spacer></v-spacer>
                        </v-container>
                        <v-flex v-for="(item, index) in concurrenciaseguimientos" :key="index">
                            <v-container grid-list-md>
                                <v-layout row wrap>
                                    <v-flex xs12 md12>
                                        <span v-if="item.nota_dss != null" class="title layout justify-center ">Nota
                                            Dirección Servicios de Salud</span>
                                        <v-spacer></v-spacer>
                                        <span v-if="item.nota_dss != null"><b>Realizado por:</b> {{item.nameDss}}
                                            {{item.apellidoDss}}</span>
                                        <span v-if="item.nota_dss != null"><b>fecha seguimiento:</b>
                                            {{item.updated_at.split('.')[0]}}</span>
                                        <v-textarea v-if="item.nota_dss != null" readonly outlined label="Nota"
                                            :value="item.nota_dss">
                                        </v-textarea>
                                        <span class="title layout justify-center ">Nota Seguimiento
                                            {{item.created_at.split('.')[0]}}</span>
                                        <v-spacer></v-spacer>
                                        <span><b># Seguimiento:</b> {{item.id}}</span>
                                        <span><b>Realizado por:</b> {{item.name}} {{item.apellido}}</span>
                                        <span><b>fecha seguimiento:</b> {{item.created_at.split('.')[0]}}</span>
                                        <v-textarea readonly outlined label="Nota"
                                            :value="item.seguimientoConcurrencia">
                                        </v-textarea>
                                        <v-btn color="primary" v-if="item.nota_dss == null" dark
                                            @click="abrirDSS(item)">Nota Dirección de
                                            servicios de salud</v-btn>
                                        <v-spacer></v-spacer>
                                        <v-divider></v-divider>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-flex>
                        <v-flex v-if="!concurrenciaseguimientos.length">
                            <v-card class="mx-auto" color="error" dark max-width="400">
                                <v-card-text>
                                    El paciente actualmente no cuenta con ningun seguimiento, para agregar uno dar click
                                    en
                                    el icono + verde.
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-layout>
                    <v-dialog v-model="dialogNotaDSS" width="700" persistent>
                        <v-card>
                            <v-card-title class="headline success" style="color:white">
                                <span class="title layout justify-center font-weight-light">Nota Dirección de Servicios
                                    de Salud</span>
                            </v-card-title>

                            <v-card-text>
                                <v-textarea label="Nota" v-model="notaDSS.nota"></v-textarea>
                            </v-card-text>

                            <v-divider></v-divider>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" text @click="dialogNotaDSS = false">
                                    Cerrar
                                </v-btn>
                                <v-btn color="primary" text @click="guardarDSS()">
                                    Guardar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-btn color="error" @click="dialogverSeguimiento = false">Cerrar</v-btn>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    export default {
        name: 'seguimientoConcurrencia',
        data() {
            return {
                data_evento: [],
                costoEvitable: '',
                costoEvitado: '',
                eventoNotificacion: '',
                eventoSeguridad: '',
                eventoCentinela: '',
                tipoIngreso: '',
                concurrenciaseguimientos: [],
                seguimientoCon: {
                    seguimientoConcurrencia: '',
                    estado: '',
                    referencia_id: '',
                    id_referencia: ''
                },
                costos: {
                    cup_id: '',
                    cantidad: '',
                    precio: ''
                },
                costo:[],
                cantidadHospi: '',
                cantidadUce: '',
                cantidadUci: '',
                cantidadTotal: '',
                totalCosto: 0,
                cups: [],
                datosSeguimientos: [],
                arrEventoNotificacion: [],
                arrEventoSeguridad: [],
                arrEventoCentinela: [],
                arrCostoEvitado: [],
                arrCostoEvitable: [],
                estados: ['En seguimiento', 'De alta'],
                editregistro: false,
                dialogNotaDSS: false,
                seguimientoReferencia: false,
                dialogverSeguimiento: false,
                loading: false,
                seguimiento: [],
                notaDSS: [],
                costo: [],
                search: '',
                headers: [{
                        text: 'id',
                        align: 'left',
                        sortable: false,
                        value: 'id'
                    },
                    {
                        text: 'Paciente',
                        value: 'calories'
                    },
                    {
                        text: 'Cédula',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'Especialidad',
                        value: 'Especialidad_remi'
                    },
                    {
                        text: 'Atendido en',
                        value: 'ips_atencion'
                    },
                    {
                        text: 'F. inicio registro',
                        align: 'left',
                        sortable: false,
                        value: 'create_at'
                    },
                    {
                        text: 'Estado',
                        value: 'state'
                    },
                    {
                        text: 'Registro'
                    },
                    {
                        text: 'Ver seguimientos'
                    }
                ],
                headerseguridad: [{
                        text: 'Evento',
                    },
                    {
                        text: 'Observación'
                    },
                    {
                        text: 'Costo Evitado'
                    },
                    {
                        text: 'Costo Evitable'
                    },
                    {
                        text: 'Acciones'
                    }
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
                data_seguimiento: [],
                d_egreso: ['Muerte en menor de 5 años', 'Alta', 'Fuga', 'Medicina domiciliaria',
                    'Muerte Materna', 'Muerte Perinatal', 'Muerte por Maltrato', 'Muerto',
                    'Refencia a mayor nivel', 'Refencia a menor nivel', 'Referencia a menor tarifa pactada',
                    'Salida Voluntaria'
                ],
                cie10s: [],
                especialidad_t: ['Anestesiología', 'Cardiología', 'Cirugía bariátrica', 'Cirugía cardiovascular',
                    'Cirugía de cabeza y cuello', 'Cirugía de tórax', 'Cirugía general', 'Cirugía hepatobiliar',
                    'Cirugía infantil', 'Cirugía maxilofacial', 'Cirugía oncológica', 'Cirugía plástica',
                    'Cirugía vascular', 'Coloproctología', 'Cuidado critico', 'Dermatología', 'Endocrinología',
                    'Fisiatría', 'Gastroenterología', 'Genética', 'Ginecología', 'Hematología',
                    'Hematoncología', 'Hemodinamia', 'Hepatología', 'Infectología',
                    'Inmunología', 'Medicina del dolor', 'Medicina general', 'Medicina Interna', 'Medicina nuclear',
                    'Nefrología', 'Neumología', 'Neurocirugía', 'Neurología', 'Obstetricia', 'Oftalmología',
                    'Oncología', 'Ortopedia', 'Otorrinolaringología', 'Pediatría', 'Psiquiatría', 'Radiología',
                    'Radioterapia', 'Reumatología', 'Toxicología', 'Urgentología', 'Urología', 'Electrofisiología'
                ],
                hospitalizacion_e: ['AIT', 'Amenaza de parto pretérmino', 'Angina inestable', 'Anticoagulados',
                    'Asma', 'Cáncer de cérvix', 'Cáncer de próstata', 'Cáncer de seno', 'Cetoacidosis diabética',
                    'Coma hiperosmolar', 'DM descompensada', 'Eclampsia', 'ECV Hemorrágico', 'ECV Isquémico',
                    'Emergencia HTA', 'Enfermedad Coronaria Severa', 'Enfermedad renal Crónica',
                    'Enfermedad renal crónica agudizada', 'EPOC descompensado', 'Falla de remisión oportuna del RN',
                    'IAM', 'ICC descompensada', 'Infección del tracto urinario (ITU)',
                    'Infecciones neonatales tempranas en madres con antecedente de patología infecciosa evitables como IVU, corioamnionitis, neumonía.',
                    'Macrosomía', 'Neumonía', 'No dispensación de medicamentos', 'Pre-eclampsia', 'RCIU',
                    'Recién nacido con TORCH', 'RN pretérmino', 'RPM', 'SDR de RN (TTRN, pulmón húmedo)',
                    'TBC congénita', 'Tétanos neonatal', 'Trastorno de electrolitos en paciente polimedicado',
                    'Trastorno electrolítico con madre diabética', 'Urgencia dialítica',
                    'Urgencia HTA'
                ],
                reporte_patologia: ['No aplica', 'Anticoagulados (Novo)', 'Anticoagulados - Z921',
                    'Anticoagulados (Complicación)',
                    'Consumo de sustancias Psicoactivas', 'Diabetes Mellitus - E109', 'DM (Descompensada)',
                    'DM (Novo)', 'Enfermedad renal crónica - N189', 'EPOC - J449', 'EPOC (Descompensado)',
                    'EPOC (Novo)', 'Hipertensión Arterial - I10x', 'HTA (Descompensada)',
                    'HTA (Novo)', 'Paciente Polimedicado', 'Tuberculosis', 'Victima de violencia sexual',
                    'VIH (Hospitalización por patología asociada a VIH)', 'VIH / Novo'
                ],
                eventos_seguridad: ['Asalto sexual en la institución', 'Asfixia perinatal',
                    'Cirugía en parte equivocada o en paciente equivocado',
                    'Cirugías o procedimientos cancelados por factores atribuibles al desempeño de la organización o de los profesionales',
                    'Consumo intra - institucional de Psicoactivos',
                    'Deterioro del paciente en la clasificación en la escala de Glasgow sin tratamiento',
                    'Distocia inadvertida',
                    'Entrega equivocada de reportes de laboratorio',
                    'Entrega equivocada de un neonato',
                    'Error en el diagnostico',
                    'Estancia prolongada por no disponibilidad de insumos o medicamentos',
                    'Eventos asociados al uso de dispositivos médicos y equipos biomédicos',
                    'Eventos postransfusionales',
                    'Eventos relacionados con la administración de medicamentos',
                    'Eventración o evisceración o dehiscencia de sutura',
                    'Extubación o decanulación no programada',
                    'Flebitis mecánica - Flebitis en sitios de venopunción',
                    'Fuga de pacientes siquiátricos y menores de 14 años hospitalizados',
                    'Infección del Sitio Operatorio - ISO',
                    'Infección del Torrente Sanguineo Asociado a catéter en UCI',
                    'Infección del Tracto Urinario Asociado a Catéter en UCI',
                    'Infecciones asociadas a la atención en salud - IAAS (Nosocomiales)',
                    'Ingreso no programado a UCI luego de procedimiento que implica la administración de anestesia',
                    'Lesión de órgano secundario a procedimiento',
                    'Lesiones causadas por caídas institucional',
                    'Luxación post - quirúrgica en reemplazo de cadera',
                    'Maternas con convulsión intrahospitalaria',
                    'Neumonia Asociada a Ventilador Mecanico en UCI',
                    'Neumotórax por ventilación mecánica o asociado a paso de catéter venoso central',
                    'Otros eventos de seguridad del paciente identificados',
                    'Pacientes con diagnóstico que apendicitis que no son atendidos después de 12 horas de realizado el diagnostico',
                    'Pacientes con hipotensión severa en post – quirúrgico',
                    'Pacientes con infarto en las siguientes 72 horas post – quirúrgico',
                    'Pacientes con neumonías broncoaspirativas en pediatría o UCI neonatal',
                    'Pacientes con trombosis venosa profunda a quienes no se les realiza control de pruebas de coagulación',
                    'Pacientes con úlceras de posición',
                    'Pérdida de pertenencias de usuarios',
                    'Problemas relacionados con el uso de medicamentos (PRUM)',
                    'Quemaduras por equipos biomédicos',
                    'Quemaduras por lámparas de fototerapia y para electrocauterio',
                    'Reacción adversa a los medicamentos',
                    'Reingreso a hospitalización por la misma causa antes de 15 días',
                    'Reingreso al servicio de urgencias por misma causa antes de 72 Horas',
                    'reingreso por la misma causa o causa asociada antes de 30 dias',
                    'Retención de cuerpos extraños en pacientes internados',
                    'Retiro accidental o autoretiro de dispositivos invasivos',
                    'Revisión de reemplazos articulares por inicio tardío de la rehabilitación',
                    'Robo intra – institucional de niños',
                    'Ruptura prematura de membranas sin conducta definida',
                    'Secuelas post – reanimación',
                    'Shock hipovolémico post – parto (Código rojo)',
                    'Suicidio de pacientes internados'
                ],
                eventos_centinelas: ['Bajo Peso al nacer', 'Hospitalización por EDA en niños de 3 a 5 años',
                    'Hospitalización por neumonia en niños y niñas de 3 a 5 años', 'Muerte Materna',
                    'Muerte por Dengue', 'Muerte por Malaria', 'Otitis Media Supurativa en menores de 5 años',
                ],
                eventos_notificacion: ['Anomalías congénitas', 'Hipotiroidismo congénito', 'Mortalidad EDA <5 años',
                    'Mortalidad infantil <1 año', 'Mortalidad IRA/neumonía <5', 'Mortalidad IRA/neumonía >65',
                    'Mortalidad perinatal', 'Mortalidad por otra EISP', 'Sífilis congénita', 'Sífilis gestacional',
                    'VIH/gestante infectada', 'VIH/mortalidad por VIH-SIDA', 'VIH/RN vivo gestante infectada'
                ],
                costo_evitado: ['Alta temprana', 'Cambios de tratamiento',
                    'Intervención temprana y potenciales complicaciones', 'Otro asegurador',
                    'Renegociación de tarifas en insumos, medicamentos, procedimientos y ayudas diagnosticas',
                    'Tecnología no realizada', 'Traslado a IPS con menor tarifa',
                    'Traslado a IPS de nivel inferior', 'Servicios y/o procedimientos diferidos (ambulatorios)',
                    'N/A'
                ],
                costo_evitables: ['Pertinencia de estancia', 'Calidad - seguridad', 'Oportunidad',
                    'Pertinencia de ayudas diagnosticas', 'Pertinencia de medicamentos ', 'Pertinencia de otra',
                    'Pertinencia de procedimientos'
                ],
                alto_costos: ['Cáncer', 'Cirugía cardiovascular', 'Cirugía enfermedades congénitas',
                    'Diálisis (CAPD/hemo)', 'Electrofisiología', 'Gran quemado', 'Hemodinamia',
                    'Insuficiencia renal', 'Neurocirugía', 'Quimioterapia / radioterapia',
                    'Remplazos articulares', 'Trasplante', 'Trauma mayor', 'VIH'
                ],
                incumplimientos: ['Dotación', 'Historia Clínica Y Registros', 'Infraestructura',
                    'Interdependencia', 'Medicamentos, Dispositivos Médicos e Insumos',
                    'Procesos Prioritarios ', 'Talento Humano'
                ]
            }
        },
        mounted() {
            this.seguimientoConcurrencia();
            this.fetchCie10s();
            this.fetchCups();
        },
        computed: {
            ...mapGetters(['can']),
            cieConcat() {
                return this.cie10Array = this.cie10s.map(cie => {
                    return {
                        id: String(cie.id),
                        codigo: cie.Codigo_CIE10,
                        nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`
                    };
                });
            },
        },
        methods: {
            fetchseguridad(seguimiento) {
                axios.get('/api/referencia/concurrenciaseguridad/' + seguimiento.id).then(res => {
                    this.arrEventoSeguridad = res.data;
                });
            },
            fetchcentinela(seguimiento) {
                axios.get('/api/referencia/concurrenciacentinela/' + seguimiento.id).then(res => {
                    this.arrEventoCentinela = res.data;
                });
            },
            fetchnotificacion(seguimiento) {
                axios.get('/api/referencia/concurrencianotificacion/' + seguimiento.id).then(res => {
                    this.arrEventoNotificacion = res.data;
                });
            },
            fetchevitado(seguimiento) {
                axios.get('/api/referencia/costoevitado/' + seguimiento.id).then(res => {
                    this.arrCostoEvitado = res.data;
                });
            },
            fetchevitable(seguimiento) {
                axios.get('/api/referencia/costoevitable/' + seguimiento.id).then(res => {
                    this.arrCostoEvitable = res.data;
                });
            },
            guardarEventoSeguridad(tipo) {
                if (tipo === 23) {
                    let data = {
                        type: tipo,
                        datos: Object.values(this.arrEventoSeguridad),
                        registroconcurrencia_id: this.data_seguimiento.id
                    }
                    axios.post('/api/referencia/eventoConcurrencia', data).then(res => {
                        this.$alerSuccess("Guardado con exito!");
                        this.arrEventoSeguridad = [];
                        this.fetchseguridad(this.data_seguimiento);
                    });
                } else if (tipo === 24) {
                    let data = {
                        type: tipo,
                        datos: Object.values(this.arrEventoCentinela),
                        registroconcurrencia_id: this.data_seguimiento.id
                    }
                    axios.post('/api/referencia/eventoConcurrencia', data).then(res => {
                        this.$alerSuccess("Guardado con exito!");
                        this.arrEventoCentinela = [];
                        this.fetchcentinela(this.data_seguimiento);
                    });
                } else if (tipo === 25) {
                    let data = {
                        type: tipo,
                        datos: Object.values(this.arrEventoNotificacion),
                        registroconcurrencia_id: this.data_seguimiento.id
                    }
                    axios.post('/api/referencia/eventoConcurrencia', data).then(res => {
                        this.$alerSuccess("Guardado con exito!");
                        this.arrEventoNotificacion = [];
                        this.fetchnotificacion(this.data_seguimiento);
                    });
                } else if (tipo === 26) {
                    let data = {
                        type: tipo,
                        datos: Object.values(this.arrCostoEvitado),
                        registroconcurrencia_id: this.data_seguimiento.id
                    }
                    axios.post('/api/referencia/eventoConcurrencia', data).then(res => {
                        this.$alerSuccess("Guardado con exito!");
                        this.arrCostoEvitado = [];
                        this.fetchevitado(this.data_seguimiento);
                    });
                } else if (tipo === 27) {
                    let data = {
                        type: tipo,
                        datos: Object.values(this.arrCostoEvitable),
                        registroconcurrencia_id: this.data_seguimiento.id
                    }
                    axios.post('/api/referencia/eventoConcurrencia', data).then(res => {
                        this.$alerSuccess("Guardado con exito!");
                        this.arrCostoEvitable = [];
                        this.fetchevitable(this.data_seguimiento);
                    });
                }

            },
            eventosClear(tipo, evento) {
                if (tipo === 'seguridad') {
                    let objIndex = this.arrEventoSeguridad.findIndex((obj => obj.eventos_seguridad === evento));
                    this.arrEventoSeguridad.splice(objIndex, 1);
                } else if (tipo === 'centinela') {
                    let objIndex = this.arrEventoCentinela.findIndex((obj => obj.eventos_centinelas === evento));
                    this.arrEventoCentinela.splice(objIndex, 1);
                } else if (tipo === 'notificacion') {
                    let objIndex = this.arrEventoNotificacion.findIndex((obj => obj.eventos_notificacion_inmediata ===
                        evento));
                    this.arrEventoNotificacion.splice(objIndex, 1);
                } else if (tipo === 'costoevitado') {
                    let objIndex = this.arrCostoEvitado.findIndex((obj => obj.costo_evitado === evento));
                    this.arrCostoEvitado.splice(objIndex, 1);
                } else if (tipo === 'costoevitable') {
                    let objIndex = this.arrCostoEvitable.findIndex((obj => obj.costo_evitable === evento));
                    this.arrCostoEvitable.splice(objIndex, 1);
                }
            },
            eventos(tipo) {
                if (tipo === 'seguridad') {
                    this.arrEventoSeguridad.push({
                        eventos_seguridad: this.data_evento.eventos_seguridad,
                        observacion_seguridad: this.data_evento.observacion_seguridad
                    });
                } else if (tipo === 'centinela') {
                    this.arrEventoCentinela.push({
                        eventos_seguridad: this.data_evento.eventos_centinelas,
                        observacion_seguridad: this.data_evento.observacion_centinela
                    });

                } else if (tipo === 'notificacion') {
                    this.arrEventoNotificacion.push({
                        eventos_seguridad: this.data_evento.eventos_notificacion_inmediata,
                        observacion_seguridad: this.data_evento.descripicion_gestion_realizada
                    });
                } else if (tipo === 'costoevitado') {
                    this.arrCostoEvitado.push({
                        eventos_seguridad: this.data_evento.costo_evitado,
                        observacion_seguridad: this.data_evento.descripcion_costo_evitado,
                        valor_costo_evitado: this.data_evento.valor_costo_evitado,

                    });
                } else if (tipo === 'costoevitable') {
                    this.arrCostoEvitable.push({
                        eventos_seguridad: this.data_evento.costo_evitable,
                        observacion_seguridad: this.data_evento.descripcion_costo_evitable,
                        valor_costo_evitable: this.data_evento.valor_costo_evitable,

                    });
                }
                this.clearEventos();

            },
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },
            total() {
                let p = Number(this.data_seguimiento.dias_estancia_observacion) +
                    Number(this.data_seguimiento.dias_estancia_intensivo) +
                    Number(this.data_seguimiento.dias_estancia_intermedio) +
                    Number(this.data_seguimiento.dias_estancia_basicos)
                return this.data_seguimiento.estancia_total_dias = p
            },
            seguimientoConcurrencia() {
                axios.get('/api/referencia/seguimiento').then(res => {
                        this.seguimiento = res.data;
                    })
                    .catch(e => {
                        this.$alerError('Error')
                    })
            },
            guardarseguimiento() {
                axios.post('/api/referencia/seguimientoConcurrencia/'+this.seguimientoCon.referencia_id, this.seguimientoCon).then(res => {
                        this.$alerSuccess("Seguimiento guardado con exito!");
                        this.verConcurrencias(this.seguimientoCon.referencia_id);
                        this.seguimientoReferencia = false
                        this.clearData()
                    })
                    .catch(e => {
                        this.$alerError('Error')
                    })
            },
            openRegistro(seguimiento) {
                this.data_seguimiento = seguimiento
                this.editregistro = true
                this.fetchCie10s()
                this.fetchseguridad(seguimiento)
                this.fetchcentinela(seguimiento)
                this.fetchnotificacion(seguimiento)
                this.fetchevitado(seguimiento)
                this.fetchevitable(seguimiento)
            },
            updateRegistro() {
                swal({
                    title: 'Esta seguro de actualizar los cambios?',
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then((confirm) => {
                    if (confirm) {
                        axios.post('/api/referencia/editregistro/', this.data_seguimiento).then(res => {
                            this.$alerSuccess("Registro actualizado con exito!");
                            this.seguimientoConcurrencia();
                            this.editregistro = false
                        })
                    }
                });
            },
            verConcurrencias(referencia_id) {
                axios.get('/api/referencia/verseguimientos/' + referencia_id).then(res => {
                    this.concurrenciaseguimientos = res.data.seguimientos;
                    this.datosSeguimientos = res.data.concurrencias;
                    this.dialogverSeguimiento = true;
                })
            },
            cargarDetalles(referencia_id) {
                this.seguimientoCon.id_referencia = referencia_id;
                this.dialogverSeguimiento = true;
            },
            clearData() {
                for (const prop of Object.getOwnPropertyNames(this.seguimientoCon)) {
                    this.seguimientoCon[prop] = null;
                }
            },
            clearEventos() {
                for (const prop of Object.getOwnPropertyNames(this.data_evento)) {
                    this.data_evento[prop] = null;
                }
            },

            abrirDSS(items) {
                this.dialogNotaDSS = true
                this.notaDSS = items
            },

            guardarDSS() {
                axios.post('/api/referencia/notaDss/' + this.notaDSS.id, this.notaDSS).then(res => {
                        this.$alerSuccess("Nota guardada con exito!");
                        this.seguimientoConcurrencia();
                        this.dialogNotaDSS = false;
                        this.dialogverSeguimiento = false;
                        this.notaDSS = [];
                    })
                    .catch(e => {
                        this.$alerError('Error')
                    })
            },

            verCosto() {
                axios.post('/api/referencia/costoConcurrencia/' + this.datosSeguimientos[0].registroconcurrencias_id)
                    .then(res => {
                        this.costo = res.data.datos;
                        this.totalCosto = res.data.total_suma.total_suma;
                        this.cantidadHospi = res.data.hospitalizacion[0].Hospitalizacion;
                        this.cantidadUce = res.data.UCE[0].UCE;
                        this.cantidadUci = res.data.UCI[0].UCI;
                        this.cantidadTotal =  parseInt(this.cantidadHospi) + parseInt(this.cantidadUce) + parseInt(this.cantidadUci)
                    })
                    .catch(e => {
                        this.$alerError('Error')
                    })
            },

            fetchCups() {
                axios.get('/api/cups/all')
                    .then(res => this.cups = res.data);
            },

            agregarCosto() {
                axios.post('/api/referencia/guardarCosto/' + this.datosSeguimientos[0].registroconcurrencias_id, this
                    .costos).then(res => {
                    this.verCosto();
                    this.costos.cup_id = '';
                    this.costo.cantidad = '';
                    this.costo.precio = '';
                    this.$alerSuccess('Agregado correctamente');
                }).catch(e => {
                    this.$alerError('Error')
                })
            },
            abrirModalNota() {
                this.seguimientoReferencia = true,
                    this.seguimientoCon.referencia_id = this.datosSeguimientos[0].referencia_id
            }
        }
    }

</script>

<style>
    .search-field input::placeholder {
        color: black !important;
    }

</style>
