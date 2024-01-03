<template>
    <div>
        <v-tabs centered color="white" icons-and-text>
            <v-tabs-slider color="primary"></v-tabs-slider>
            <v-tab href="#tab-1" color="black--text">
                Pendientes
                <v-icon color="black">done</v-icon>
            </v-tab>
            <v-tab href="#tab-2">
                Solucionados
                <v-icon color="black">done_all</v-icon>
            </v-tab>
            <!-- pendientes -->
            <v-tab-item :value="'tab-1'">
                <v-card flat>
                    <v-card>
                        <v-card-title>
                            <v-combobox :items="['Pendientes', 'Gestionando']" label="Filtrar" single-line hide-details
                                v-model="filter" @input="getPendientes()"></v-combobox>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                            </v-text-field>
                        </v-card-title>
                        <v-data-table :loading="loading" :headers="headers" :items="allPendientes" :search="search">
                            <template v-slot:items="props">
                                <td>{{ props.item.id }}</td>
                                <td>{{ props.item.creado.split('.')[0] }}</td>
                                <td>{{ props.item.sede }}</td>
                                <td>{{ props.item.area }}</td>
                                <td>{{ props.item.categoria }}</td>
                                <td>{{ props.item.asunto }}</td>
                                <td>
                                    <v-chip :class="prioridadTd(props.item)">{{ props.item.prioridad }}</v-chip>
                                </td>
                                <td class="text-xs-center" v-if="props.item.tiempo_estimado >= props.item.fecha_actual">
                                    <v-chip color="success" text-color="white" v-if="props.item.diferencia_dias >= 6">
                                        Faltan {{ props.item.diferencia_dias}} Dias</v-chip>
                                    <v-chip color="warning" text-color="white"
                                        v-if="(props.item.diferencia_dias < 6 && props.item.diferencia_dias > 2)">
                                        Ojo! Faltan {{ props.item.diferencia_dias}} Dias</v-chip>
                                    <v-chip color="error" text-color="white" v-if="props.item.diferencia_dias <=2">
                                        Proximo a Vencer Faltan {{ props.item.diferencia_dias}} Dias</v-chip>
                                </td>
                                <td class="text-xs-center" v-if="props.item.tiempo_estimado < props.item.fecha_actual">
                                    <v-chip color="error" text-color="white">
                                        Dias Vencidos {{ props.item.diferencia_dias}} Dias</v-chip>
                                </td>
                                <td class="text-xs-center" v-if="!props.item.tiempo_estimado">
                                    <v-chip color="warning" text-color="white">
                                        Sin Fecha Estimada</v-chip>
                                </td>
                                <td>
                                    <v-btn color="primary" :disabled="loading" @click="accionesPendientes(props.item)">
                                        Acciones
                                    </v-btn>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Su búsqueda de "{{ search }}" no encontró resultados.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card>

                    <!-- modal de acciones -->
                    <v-dialog v-model="dialogAcciones" v-if="dialogAcciones" persistent width="1000">
                        <v-card>
                            <v-toolbar flat color="primary" dark>
                                <v-toolbar-title>Solicitud # {{ pendientes.id }}</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs6>
                                            <v-text-field v-model="pendientes.nombreUser" readonly label="NOMBRE">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="pendientes.email" readonly label="EMAIL">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="pendientes.sede" readonly label="SEDE">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="pendientes.area" readonly label="ÁREA">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-if="pendientes.categoria" v-model="pendientes.categoria"
                                                readonly label="CATEGORIA">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-if="pendientes.actividad" v-model="pendientes.actividad"
                                                readonly label="ACTIVIDAD">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-text-field v-model="pendientes.asunto" readonly label="ASUNTO">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-textarea v-model="pendientes.descripcion" readonly>
                                                <template v-slot:label>
                                                    <div>
                                                        DESCRIPCIÓN
                                                    </div>
                                                </template>
                                            </v-textarea>
                                        </v-flex>
                                        <!-- for de adjuntos -->
                                        <v-flex xs12 v-for="(GesHelp, index) in pendientes.gestion_helpdesks"
                                            :key="`res-${index}`">
                                            <v-btn v-for="(data, index2) in GesHelp.adjuntos_helpdesks" :key="index2">
                                                <a :href="`${data.Ruta}`" target="_blank" style="text-decoration:none">
                                                    <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                    {{index2+1}}
                                                </a>
                                            </v-btn>
                                            <v-divider class="my-2" v-if="index < 1"></v-divider>
                                        </v-flex>
                                        <!-- for de comentario de asignacion -->
                                        <v-flex xs12 v-for="(HelpAsignado, index5) in showAsignados"
                                            :key="`res4-${index5}`">
                                            <v-flex xs12
                                                v-for="(comentAsignado, index6) in HelpAsignado.gestion_helpdesks"
                                                :key="`res7-${index6}`">
                                                <v-flex xs12 v-if="index6 < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>{{ comentAsignado.Nombretipo }}
                                                        </v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="comentAsignado.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comentAsignado.Nombre }}
                                                    {{ comentAsignado.Apellido }}
                                                    <strong>Fecha: </strong>
                                                    {{ comentAsignado.Faccion.split('.')[0] }}</span>
                                                <v-flex>
                                                    <v-btn
                                                        v-for="(dataSolucionado, index6) in comentAsignado.adjuntos_helpdesks"
                                                        :key="index6">
                                                        <a :href="`${dataSolucionado.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index6+1}}
                                                        </a>
                                                    </v-btn>
                                                    <v-divider class="my-2"></v-divider>
                                                </v-flex>
                                            </v-flex>
                                        </v-flex>
                                        <!-- for de comentario de re apertura -->
                                        <v-flex xs12 v-for="(comenReabierto, i) in comentarioReAbierto"
                                            :key="`res4-${i}`">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="i < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Comentario Re Abierto</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="comenReabierto.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comenReabierto.name }}
                                                    {{ comenReabierto.apellido }}
                                                    <strong>Fecha: </strong>
                                                    {{ comenReabierto.created_at.split('.')[0] }}
                                                </span>
                                                <v-flex>
                                                    <v-btn v-for="(data, index3) in comenReabierto.adjuntos_helpdesks"
                                                        :key="index3">
                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index3+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <!-- for de comentario publico -->
                                        <v-flex xs12 v-for="(comenpublic, i) in comentarioPublico" :key="`res1-${i}`">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="i < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Comentarios al Solicitante</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <span>
                                                    <strong>Categoria: </strong> {{ comenpublic.categoria }}
                                                    <strong>Prioridad: </strong> {{ comenpublic.prioridad}}
                                                    <strong>Fecha estimada de solucion: </strong>
                                                    {{ comenpublic.tiempo_estimado }}
                                                </span>
                                                <v-textarea v-model="comenpublic.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comenpublic.name }}
                                                    {{ comenpublic.apellido }}
                                                    <strong>Fecha: </strong> {{ comenpublic.created_at.split('.')[0] }}
                                                    <strong v-show="comenpublic.responsable">Responsable: </strong>
                                                    {{ comenpublic.responsable }}
                                                </span>
                                                <v-flex>
                                                    <v-btn v-for="(data, index3) in comenpublic.adjuntos_helpdesks"
                                                        :key="index3">
                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index3+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <!-- for de comentario privado -->
                                        <v-flex xs12 v-for="(comenprivate, i) in comentarioPrivado" :key="`res2-${i}`">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="i < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Comentarios Internos</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <span>
                                                    <strong>Categoria: </strong> {{ comenpublic.categoria }}
                                                    <strong>Prioridad: </strong> {{ comenpublic.prioridad}}
                                                    <strong>Fecha estimada de solucion: </strong>
                                                    {{ comenpublic.tiempo_estimado }}
                                                </span>
                                                <v-textarea v-model="comenprivate.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comenprivate.name }}
                                                    {{ comenprivate.apellido }}
                                                    <strong>Fecha: </strong> {{ comenprivate.created_at.split('.')[0] }}
                                                    <strong v-show="comenprivate.responsable">Responsable: </strong>
                                                    {{ comenprivate.responsable }}
                                                </span>
                                                <v-flex>
                                                    <v-btn v-for="(data, index4) in comenprivate.adjuntos_helpdesks"
                                                        :key="index4">
                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index4+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <!-- for de respuesta -->
                                        <v-flex xs12 v-for="(respuestaHelp, indexr) in respuestas" :key="indexr">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="indexr < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Respuesta</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="respuestaHelp.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ respuestaHelp.name }}
                                                    {{ respuestaHelp.apellido }}
                                                    <strong>Fecha: </strong>
                                                    {{ respuestaHelp.created_at.split('.')[0] }}
                                                    <strong v-show="respuestaHelp.responsable">Responsable: </strong>
                                                    {{ respuestaHelp.responsable }}
                                                </span>
                                                <v-flex>
                                                    <v-btn
                                                        v-for="(dataRespuesta, index8) in respuestaHelp.adjuntos_helpdesks"
                                                        :key="index8">
                                                        <a :href="`${dataRespuesta.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index8+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <!-- acciones dentro del modal -->
                                        <v-flex xs12>
                                            <v-toolbar color="primary" dark class="mb-4">
                                                <v-toolbar-title>Acciones</v-toolbar-title>
                                            </v-toolbar>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-select v-model="accion" :items="estadoAcciones" label="ACCIÓN">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12
                                            v-if="accion == 'Comentarios al Solicitante' || accion ==  'Comentarios Internos'">
                                            <v-text-field type="date" v-model="pendientes.tiempo_estimado"
                                                label="Fecha Estimada Solucion"></v-text-field>
                                        </v-flex>
                                        <v-flex xs6
                                            v-if="accion =='Comentarios al Solicitante' || accion ==  'Comentarios Internos'">
                                            <v-select :items="['Alta', 'Media', 'Baja']" v-model="pendientes.prioridad"
                                                label="Prioridad"></v-select>
                                        </v-flex>
                                        <v-flex xs4
                                            v-if="accion == 'Comentario Publico' | accion == 'Comentario Privado'">
                                            <v-switch v-if=" pendientes.estadoAsignado == 1" v-model="switch1"
                                                color="success" label="Esta gestionando?">
                                            </v-switch>
                                        </v-flex>
                                        <v-flex xs6
                                            v-if="switch1 == true | accion == 'Respuesta' | accion == 'Devolver'">
                                            <v-autocomplete :items="permisoHelp" item-text="name" item-value="name"
                                                label="Responsable" v-model="responsablerespuesta"></v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-textarea v-model="motivohelp">
                                                <template v-slot:label>
                                                    <div>
                                                        MOTIVO
                                                    </div>
                                                </template>
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs12 v-if="accion != 'Devolver'">
                                            <input id="adjuntos" multiple ref="adjuntos" type="file" />
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" @click="closeAcciones()">
                                    Cerrar
                                </v-btn>
                                <v-btn color="success" @click="accionhelp(accion)">
                                    Guardar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-card>
            </v-tab-item>

            <!-- Solucionados -->
            <v-tab-item :value="'tab-2'">
                <v-card flat>
                    <v-card>
                        <v-card-title>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search1" append-icon="search" label="Search" single-line
                                hide-details>
                            </v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headersolucionados" :loading="loading" :items="allSolucionados"
                            :search="search1">
                            <template v-slot:items="props">
                                <td>{{ props.item.id }}</td>
                                <td>{{ props.item.creado.split('.')[0] }}</td>
                                <td>{{ props.item.sede }}</td>
                                <td>{{ props.item.area }}</td>
                                <td>{{ props.item.categoria }}</td>
                                <td>{{ props.item.asunto }}</td>
                                <td>
                                    <v-btn color="primary" :disabled="loading"
                                        @click="accionesSolucionados(props.item)">Ver</v-btn>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Su búsqueda de "{{ search1 }}" no encontró resultados.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card>
                    <!-- modal de acciones -->
                    <v-dialog v-model="dialogSolucionado" v-if="dialogSolucionado" width="1000">
                        <v-card>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12>
                                            <v-toolbar color="primary" dark class="mb-3">
                                                <v-toolbar-title>Solicitud # {{ solucionados.id }}</v-toolbar-title>
                                            </v-toolbar>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="solucionados.nombreUser" readonly label="NOMBRE">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="solucionados.email" readonly label="EMAIL">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="solucionados.sede" readonly label="SEDE">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="solucionados.area" readonly label="ÁREA">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-if="solucionados.categoria" v-model="solucionados.categoria"
                                                readonly label="CATEGORIA">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-if="solucionados.actividad" v-model="solucionados.actividad"
                                                readonly label="ACTIVIDAD">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-text-field v-model="solucionados.asunto" readonly label="ASUNTO">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-textarea v-model="solucionados.descripcion" readonly>
                                                <template v-slot:label>
                                                    <div>
                                                        DESCRIPCIÓN
                                                    </div>
                                                </template>
                                            </v-textarea>
                                        </v-flex>
                                        <!-- for adjuntos -->
                                        <v-flex xs12 v-for="(GesHelp, index3) in solucionados.gestion_helpdesks"
                                            :key="`res5-${index3}`">
                                            <v-btn v-for="(data, index2) in GesHelp.adjuntos_helpdesks" :key="index2">
                                                <a :href="`${data.Ruta}`" target="_blank" style="text-decoration:none">
                                                    <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                    {{index2+1}}
                                                </a>
                                            </v-btn>
                                            <v-divider class="my-2" v-if="index3 < 1"></v-divider>
                                        </v-flex>
                                        <!-- for comentario de asignacion -->
                                        <v-flex xs12 v-for="(HelpAsignado, index5) in showAsignados"
                                            :key="`res4-${index5}`">
                                            <v-flex xs12
                                                v-for="(comentAsignado, index6) in HelpAsignado.gestion_helpdesks"
                                                :key="`res7-${index6}`">
                                                <v-flex xs12 v-if="index6 < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>{{ comentAsignado.Nombretipo }}
                                                        </v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="comentAsignado.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comentAsignado.Nombre }}
                                                    {{ comentAsignado.Apellido }}
                                                    <strong>Fecha: </strong>
                                                    {{ comentAsignado.Faccion.split('.')[0] }}</span>
                                                <v-flex>
                                                    <v-btn
                                                        v-for="(dataSolucionado, index6) in comentAsignado.adjuntos_helpdesks"
                                                        :key="index6">
                                                        <a :href="`${dataSolucionado.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index6+1}}
                                                        </a>
                                                    </v-btn>
                                                    <v-divider class="my-2"></v-divider>
                                                </v-flex>
                                            </v-flex>
                                        </v-flex>
                                        <!-- for de comentario reapertura -->
                                        <v-flex xs12 v-for="(comenReabierto, i) in comentarioReAbierto"
                                            :key="`res4-${i}`">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="i < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Comentario Re Abierto</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="comenReabierto.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comenReabierto.name }}
                                                    {{ comenReabierto.apellido }}
                                                    <strong>Fecha: </strong>
                                                    {{ comenReabierto.created_at.split('.')[0] }}
                                                </span>
                                                <v-flex>
                                                    <v-btn v-for="(data, index3) in comenReabierto.adjuntos_helpdesks"
                                                        :key="index3">
                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index3+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <!-- for comentario publico -->
                                        <v-flex xs12 v-for="(comenpublic, i) in comentarioPublico" :key="`res1-${i}`">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="i < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Comentario</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="comenpublic.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comenpublic.name }}
                                                    {{ comenpublic.apellido }}
                                                    <strong>Fecha: </strong> {{ comenpublic.created_at.split('.')[0] }}
                                                    <strong v-show="comenpublic.responsable">Responsable: </strong>
                                                    {{ comenpublic.responsable }}
                                                </span>
                                                <v-flex>
                                                    <v-btn v-for="(data, index3) in comenpublic.adjuntos_helpdesks"
                                                        :key="index3">
                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index3+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <!-- for comentario privado -->
                                        <v-flex xs12 v-for="(comenprivate, i) in comentarioPrivado" :key="`res2-${i}`">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="i < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Comentario privado</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="comenprivate.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ comenprivate.name }}
                                                    {{ comenprivate.apellido }}
                                                    <strong>Fecha: </strong> {{ comenprivate.created_at.split('.')[0] }}
                                                    <strong v-show="comenprivate.responsable">Responsable: </strong>
                                                    {{ comenprivate.responsable }}
                                                </span>
                                                <v-flex>
                                                    <v-btn v-for="(data, index8) in comenprivate.adjuntos_helpdesks"
                                                        :key="index8">
                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index8+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <!-- for respuesta -->
                                        <v-flex xs12 v-for="(respuestaHelp, indexr) in respuestas" :key="indexr">
                                            <v-flex xs12>
                                                <v-flex xs12 v-if="indexr < 1">
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>Respuesta</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="respuestaHelp.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span><strong>Nombre: </strong> {{ respuestaHelp.name }}
                                                    {{ respuestaHelp.apellido }}
                                                    <strong>Fecha: </strong>
                                                    {{ respuestaHelp.created_at.split('.')[0] }}
                                                    <strong v-show="respuestaHelp.responsable">Responsable: </strong>
                                                    {{ respuestaHelp.responsable }}
                                                </span>
                                                <v-flex>
                                                    <v-btn
                                                        v-for="(dataRespuesta, index8) in respuestaHelp.adjuntos_helpdesks"
                                                        :key="index8">
                                                        <a :href="`${dataRespuesta.Ruta}`" target="_blank"
                                                            style="text-decoration:none">
                                                            <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                            {{index8+1}}
                                                        </a>
                                                    </v-btn>
                                                </v-flex>
                                                <v-divider class="my-2"></v-divider>
                                            </v-flex>
                                        </v-flex>
                                        <!-- for solucion final -->
                                        <v-flex xs12 v-for="(gesHelpSolucion, index4) in solucion"
                                            :key="`res8-${index4}`">
                                            <v-flex xs12>
                                                <v-flex>
                                                    <v-toolbar color="primary" dark class="mb-4">
                                                        <v-toolbar-title>{{ gesHelpSolucion.descripcionTipo }}
                                                        </v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-textarea v-model="gesHelpSolucion.Motivo" readonly>
                                                    <template v-slot:label>
                                                        <div>
                                                            MOTIVO
                                                        </div>
                                                    </template>
                                                </v-textarea>
                                                <span>Nombre: {{ gesHelpSolucion.name }} {{ gesHelpSolucion.apellido }}
                                                    Fecha:
                                                    {{ gesHelpSolucion.created_at.split(' ')[0] }}</span>
                                            </v-flex>
                                            <v-flex>
                                                <v-btn
                                                    v-for="(dataSolucionado, index6) in gesHelpSolucion.adjuntos_helpdesks"
                                                    :key="index6">
                                                    <a :href="`${dataSolucionado.Ruta}`" target="_blank"
                                                        style="text-decoration:none">
                                                        <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                        {{index6+1}}
                                                    </a>
                                                </v-btn>
                                            </v-flex>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </v-card>
            </v-tab-item>
        </v-tabs>

        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

    </div>

</template>
<script>
    import Swal from 'sweetalert2';
    export default {
        data: () => ({
            loading: false,
            allPendientes: [],
            filter: 'Pendientes',
            search: '',
            search1: '',
            headers: [{
                    text: 'id',
                    align: 'left',
                    value: 'id'
                },
                {
                    text: 'Fecha',
                    align: 'left',
                    value: ''
                },
                {
                    text: 'Sede',
                    align: 'left',
                    value: ''
                },
                {
                    text: 'Área',
                    align: 'left',
                    value: 'area'
                },
                {
                    text: 'Categoria',
                    align: 'left',
                    value: 'categoria'
                },
                {
                    text: 'Asunto',
                    align: 'left',
                    value: 'asunto'
                },
                {
                    text: 'Prioridad',
                    align: 'left',
                    value: 'prioridad'
                },
                {
                    text: 'Tiempo',
                    align: 'center',
                    value: 'diferencia_dias'
                },
                {
                    text: '',
                    align: 'left'
                }
            ],
            dialogAcciones: false,
            showAsignados: [],
            pendientes: [],
            helpdesk_id: '',
            comentarioPublico: [],
            comentarioPrivado: [],
            comentarioReAbierto: [],
            accion: '',
            motivohelp: '',
            Categorias_id: '',
            Actividades_id: '',
            tipo_reque: '',
            prioridad: '',
            tiempo_estimado: '',
            adjuntos: [],
            data: {},
            switch1: false,
            permisosUser: [],
            permisosHelpdesk: [],
            responsablerespuesta: '',
            acciones: ['Comentarios al Solicitante', 'Comentarios Internos', 'Respuesta', 'Devolver'],
            respuestas: [],
            allSolucionados: [],
            headersolucionados: [{
                    text: 'id',
                    align: 'left',
                    value: 'id'
                },
                {
                    text: 'Fecha',
                    align: 'left',
                    value: ''
                },
                {
                    text: 'Sede',
                    align: 'left',
                    value: ''
                },
                {
                    text: 'Área',
                    align: 'left',
                    value: 'area'
                },
                {
                    text: 'Categoria',
                    align: 'left',
                    value: 'categoria'
                },
                {
                    text: 'Asunto',
                    align: 'left',
                    value: 'asunto'
                },
                {
                    text: 'Historial',
                    align: 'left'
                }
            ],
            dialogSolucionado: false,
            solucionados: [],
            solucion: [],
            preload: false

        }),
        mounted() {
            this.getPendientes();
            this.getPermission();
            this.getSolucionados();
            this.urlemail();
        },
        computed: {
            estadoAcciones() {
                if (this.pendientes.estadoAsignado == 41) {
                    let forDeletion = ['Devolver']
                    let array = this.acciones.filter(item => !forDeletion.includes(item))
                    return array;
                }
                return this.acciones
            },
            permisoHelp() {
                let pUser = this.permisosUser
                let pHelpdesk = this.permisosHelpdesk
                let findPermission = []
                pHelpdesk.forEach(help => {
                    let finded = pUser.find(user => user.name == help.name)
                    if (finded) findPermission.push(finded)
                });
                if (findPermission.length == 1) this.responsablerespuesta = findPermission[0].name;
                return findPermission
            }
        },
        methods: {
            urlemail() {
                let url = window.location.href;
                let id = url.split("miasignada/");
                if (id[1] !== '') {
                    this.search = id[1]
                }
            },
            getPermission() {
                axios.get('/api/helpdesk/permisoUser')
                    .then(res => {
                        this.permisosUser = res.data;
                    });
            },
            permisoAsignadoHelp() {
                axios.post(`/api/helpdesk/permisoAsignadoHelp`, {
                        helpdesk_id: this.helpdesk_id
                    })
                    .then(res => {
                        this.permisosHelpdesk = res.data
                    })
            },
            getPendientes() {
                this.loading = true
                axios.post('/api/helpdesk/pendientesAsignada', {
                        estado: this.filter
                    })
                    .then(res => {
                        this.loading = false
                        this.allPendientes = res.data;
                    }).catch(err => {
                        this.loading = false
                    })
            },
            showAsignadosinArea() {
                axios.post(`/api/helpdesk/showAsignadosinArea`, {
                        helpdesk_id: this.helpdesk_id
                    })
                    .then(res => {
                        this.showAsignados = res.data
                    })
            },
            getSolucionados() {
                this.loading = true
                axios.get('/api/helpdesk/solucionadasAsignada')
                    .then(res => {
                        this.loading = false
                        this.allSolucionados = res.data;
                    }).catch(err => {
                        this.loading = false
                    })
            },
            accionesPendientes(pendiente) {
                this.dialogAcciones = true
                this.pendientes = pendiente
                this.helpdesk_id = this.pendientes.id
                this.comentariosPublico();
                this.comentariosPrivado();
                this.comentariosReabierto();
                this.permisoAsignadoHelp();
                this.showRespuestas();
                this.showAsignadosinArea()
            },
            accionesSolucionados(solucionado) {
                this.dialogSolucionado = true
                this.solucionados = solucionado
                this.helpdesk_id = this.solucionados.id
                this.comentariosPublico();
                this.comentariosPrivado();
                this.comentariosReabierto();
                this.showSolucion();
                this.showRespuestas();
                this.showAsignadosinArea()
            },
            comentariosPublico() {
                axios.post(`/api/helpdesk/showcomentariosPublicos`, {
                        helpdesk_id: this.helpdesk_id
                    })
                    .then(res => {
                        this.comentarioPublico = res.data
                    })
            },
            comentariosPrivado() {
                axios.post(`/api/helpdesk/showcomentariosPrivados`, {
                        helpdesk_id: this.helpdesk_id
                    })
                    .then(res => {
                        this.comentarioPrivado = res.data
                    })
            },
            comentariosReabierto() {
                axios.post(`/api/helpdesk/comentariosReabierto`, {
                        helpdesk_id: this.helpdesk_id
                    })
                    .then(res => {
                        this.comentarioReAbierto = res.data
                    })
            },
            prioridadTd(pendiente) {
                if (pendiente.prioridad == 'Alta') {
                    return 'error white--text';
                } else if (pendiente.prioridad == 'Media') {
                    return 'yellow black--text';
                } else {
                    return 'success white--text';
                }
            },
            accionhelp(msg) {
                if (msg == 'Comentarios al Solicitante' | msg == 'Comentarios Internos') {
                    this.comentar();
                } else if (msg == 'Respuesta') {
                    this.respuesta();
                } else if (msg == 'Devolver') {
                    this.devolver();
                }
            },
            closeAcciones() {
                this.dialogAcciones = false
                this.motivohelp = ''
                this.accion = ''
                this.$refs.adjuntos.value = ''
                this.responsablerespuesta = ''
                this.switch1 = false
            },
            comentar() {
                if (this.switch1 == true) {
                    if (this.responsablerespuesta.length == 0) {
                        this.$alerError('Debe seleccionar un responsable!');
                        return
                    }
                }
                this.preload = true
                this.data.adjuntos = this.$refs.adjuntos.files
                let formData = new FormData();
                formData.append('helpdesk_id', this.pendientes.id)
                formData.append('motivo', this.motivohelp)
                formData.append('accion', this.accion)
                formData.append('gestionando', this.switch1)
                formData.append('responsable', this.responsablerespuesta)
                formData.append('desde', 'miasignada')
                formData.append('gestionandoArea', this.switch1)
                formData.append('categoria', this.pendientes.Categorias_id)
                formData.append('actividad', this.pendientes.Actividades_id)
                formData.append('tipo_requerimiento', this.pendientes.tipo_reque)
                formData.append('prioridad', this.pendientes.prioridad)
                formData.append('tiempo_estimado', this.pendientes.tiempo_estimado)
                for (let i = 0; i < this.data.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.data.adjuntos[i]);
                }
                axios.post(`/api/helpdesk/comentar`, formData)
                    .then(res => {
                        this.preload = false
                        this.getSolucionados();
                        this.getPendientes();
                        this.closeAcciones();
                        swal({
                            title: "¡Ha comentado la solicitud con exito!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    }).catch(err => {
                        this.preload = false
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        swal({
                            title: msg,
                            text: " ",
                            type: "error",
                            icon: "error",
                        });
                    })
            },
            respuesta() {
                if (this.responsablerespuesta.length == 0) {
                    this.$alerError('Debe seleccionar un responsable!');
                    return
                }
                this.preload = true
                this.data.adjuntos = this.$refs.adjuntos.files
                let formData = new FormData();
                formData.append('helpdesk_id', this.pendientes.id)
                formData.append('motivo', this.motivohelp)
                formData.append('accion', this.accion)
                formData.append('responsable', this.responsablerespuesta)
                for (let i = 0; i < this.data.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.data.adjuntos[i]);
                }
                axios.post(`/api/helpdesk/respuesta/${this.pendientes.id}`, formData)
                    .then(res => {
                        this.preload = false
                        this.getSolucionados();
                        this.getPendientes();
                        this.closeAcciones();
                        swal({
                            title: "¡Ha dado respuesta a la solicitud con exito!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    }).catch(err => {
                        this.preload = false
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        swal({
                            title: msg,
                            text: " ",
                            type: "error",
                            icon: "error",
                        });
                    })
            },
            showRespuestas() {
                axios.post(`/api/helpdesk/showRespuestas`, {
                        helpdesk_id: this.helpdesk_id
                    })
                    .then(res => {
                        this.respuestas = res.data
                    })
            },
            showSolucion() {
                axios.post(`/api/helpdesk/showSolucion`, {
                        helpdesk_id: this.helpdesk_id
                    })
                    .then(res => {
                        this.solucion = res.data
                    })
            },
            devolver() {
                if (this.responsablerespuesta.length == 0) {
                    this.$alerError('Debe seleccionar un responsable!');
                    return
                }
                let formData = new FormData();
                formData.append('helpdesk_id', this.pendientes.id)
                formData.append('motivo', this.motivohelp)
                formData.append('accion', this.accion)
                formData.append('responsable', this.responsablerespuesta)
                axios.post(`/api/helpdesk/devolver`, formData)
                    .then(res => {
                        this.getSolucionados();
                        this.getPendientes();
                        this.closeAcciones();
                        swal({
                            title: "¡Ha devuelto la solicitud con exito!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    }).catch(err => {
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        swal({
                            title: msg,
                            text: " ",
                            type: "error",
                            icon: "error",
                        });
                    })
            }
        }
    }

</script>
