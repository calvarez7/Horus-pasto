<template>
    <div>
        <v-tabs centered color="white" icons-and-text>
            <v-tabs-slider color="primary"></v-tabs-slider>
            <v-tab href="#tab-1" color="black--text" @click="getpendientes()">
                Pendientes
                <v-icon color="black">block</v-icon>
            </v-tab>
            <v-tab href="#tab-2" @click="getcerrados()">
                Cerrados
                <v-icon color="black">check_circle</v-icon>
            </v-tab>
            <!-- pendientes -->
            <v-tab-item :value="'tab-1'">
                <v-card flat>
                    <v-card>
                        <v-card-title>
                            <v-layout row wrap>
                                <v-flex xs2 md2 sm2 px-2>
                                    <v-text-field label="id" @input="filterId" v-model="filter.id">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 md3 sm3 px-2>
                                    <v-text-field label="Documento" v-model="filter.documento" @input="filterDocumento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs7 md6 sm6 px-2>
                                    <v-autocomplete label="Nombre del suceso" :items="alleventos" item-text="nombre"
                                        item-value="nombre" @input="filterSuceso" v-model="filter.suceso">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs3 md6 sm6 px-2>
                                    <v-autocomplete label="Entidad" :items="allentidades" item-text="nombre"
                                        item-value="nombre" @input="filterEntidad" v-model="filter.entidad">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs2 md2 sm2 px-2>
                                    <v-text-field label="Desde" type="date" color="primary" @input="filterStartDate"
                                        v-model="filter.desde">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2 md2 sm2 px-2>
                                    <v-text-field label="Hasta" type="date" color="primary" @input="filterEndDate"
                                        v-model="filter.hasta">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1 md1 sm1 px-2>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn fab color="error" small v-on="on" @click="clearFilter()">
                                                <v-icon>delete</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Quitar Filtros</span>
                                    </v-tooltip>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                        <template>
                            <v-data-table :loading="loading" :headers="headers" :items="pendientes" :search="filters"
                                :custom-filter="customFilter" class="elevation-1" color="primary">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.sede_ocurrencia }}</td>
                                    <td>{{ props.item.fecha_ocurrencia }}</td>
                                    <td>{{ props.item.Num_Doc }}</td>
                                    <td>{{ props.item.nombre_completo }}
                                    </td>
                                    <td>{{ props.item.nombreEvento }}</td>
                                    <td>{{ props.item.entidad }}</td>
                                    <td class="text-xs-left">
                                        <v-chip :class="colorTd(props.item.diasDiferencia, props.item.priorizacion)">
                                            {{  props.item.diasDiferencia }} Día(s)
                                            {{  props.item.priorizacion }}
                                        </v-chip>
                                    </td>
                                    <td>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn :disabled="loading" text icon color="primary" dark v-on="on"
                                                    @click="openAnalisis(props.item.id)">
                                                    <v-icon>search</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Investigación y analisis</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                            </v-data-table>
                        </template>
                    </v-card>
                </v-card>
            </v-tab-item>
            <!-- cerrados -->
            <v-tab-item :value="'tab-2'">
                <v-card flat>
                    <v-card>
                        <v-card-title>
                            <v-layout row wrap>
                                <v-flex xs2 md2 sm2 px-2>
                                    <v-text-field label="id" @input="filterId" v-model="filter.id">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 md3 sm3 px-2>
                                    <v-text-field label="Documento" v-model="filter.documento" @input="filterDocumento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs7 md6 sm6 px-2>
                                    <v-autocomplete label="Nombre del suceso" :items="alleventos" item-text="nombre"
                                        item-value="nombre" @input="filterSuceso" v-model="filter.suceso">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs3 md6 sm6 px-2>
                                    <v-autocomplete label="Entidad" :items="allentidades" item-text="nombre"
                                        item-value="nombre" @input="filterEntidad" v-model="filter.entidad">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs2 md2 sm2 px-2>
                                    <v-text-field label="Desde" type="date" color="primary" @input="filterStartDate"
                                        v-model="filter.desde">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2 md2 sm2 px-2>
                                    <v-text-field label="Hasta" type="date" color="primary" @input="filterEndDate"
                                        v-model="filter.hasta">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1 md1 sm1 px-2>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn fab color="error" small v-on="on" @click="clearFilter()">
                                                <v-icon>delete</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Quitar Filtros</span>
                                    </v-tooltip>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                        <template>
                            <v-data-table :loading="loading" :headers="headers" :items="cerrados" :search="filters"
                                :custom-filter="customFilter" class="elevation-1" color="primary">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.sede_ocurrencia }}</td>
                                    <td>{{ props.item.fecha_ocurrencia }}</td>
                                    <td>{{ props.item.Num_Doc }}</td>
                                    <td>{{ props.item.nombre_completo }}
                                    </td>
                                    <td>{{ props.item.nombreEvento }}</td>
                                    <td>{{ props.item.entidad }}</td>
                                    <td class="text-xs-left">
                                        Cerrado
                                    </td>
                                    <td>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn :disabled="loading" text icon color="primary" dark v-on="on"
                                                    @click="openAnalisis(props.item)">
                                                    <v-icon>search</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Investigación y analisis</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                            </v-data-table>
                        </template>
                    </v-card>
                </v-card>
            </v-tab-item>
        </v-tabs>
        <!-- Item para agregar -->
        <v-dialog v-model="dialogItems" max-width="500px">
            <v-card>
                <v-card-title>
                    Agregar {{ campo }}
                </v-card-title>
                <v-card-text>
                    <v-combobox v-model="newItems" multiple chips :items="[]"></v-combobox>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="addItems">Agregar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- modal de accion -->
        <v-dialog v-model="dialogAnalisis" v-if="dialogAnalisis" persistent max-width="1000px">
            <v-card max-height="100%">
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Reporte</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex xs4 px-1 v-show="analisis.paciente_id">
                                <v-text-field v-model="analisis.nombre_completo" readonly label="Nombre y apellidos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs2 px-1 v-show="analisis.Num_Doc">
                                <v-text-field v-model="analisis.Num_Doc" readonly label="Documento">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs1 px-1 v-show="analisis.Edad_Cumplida">
                                <v-text-field v-model="analisis.Edad_Cumplida" readonly label="Edad">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs1 px-1 v-show="analisis.sexo">
                                <v-text-field v-model="analisis.sexo" readonly label="Sexo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1 v-show="analisis.entidad">
                                <v-text-field v-model="analisis.entidad" readonly label="Entidad">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 px-1>
                                <v-text-field v-model="analisis.nombreEvento" readonly label="Nombre del suceso">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 px-1 v-show="analisis.nombreClasificacion">
                                <v-text-field v-model="analisis.nombreClasificacion" readonly
                                    label="Clasificación área">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 px-1 v-show="analisis.nombreTipoevento">
                                <v-text-field v-model="analisis.nombreTipoevento" readonly label="Tipo de evento">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 px-1 v-show="analisis.otro_evento">
                                <v-text-field v-model="analisis.otro_evento" readonly label="Otro (Nombre del suceso)">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 px-1>
                                <v-text-field v-model="analisis.sede_ocurrencia" readonly label="Sede ocurrencia">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 px-1 v-show="analisis.servicio_ocurrencia">
                                <v-text-field v-model="analisis.servicio_ocurrencia" readonly
                                    label="Servicio ocurrencia">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 px-1>
                                <v-text-field v-model="analisis.sede_reportante" readonly label="Sede reportante">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 px-1 v-show="analisis.servicio_reportante">
                                <v-text-field v-model="analisis.servicio_reportante" readonly
                                    label="Servicio reportante">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-text-field v-model="analisis.created_at" readonly label="Fecha de reporte">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-text-field v-model="analisis.fecha_ocurrencia" readonly label="Fecha de ocurrencia">
                                </v-text-field>
                            </v-flex>
                            <v-flex v-show="analisis.medicamento">
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-text-field readonly v-model="analisis.medicamento" label="Medicamento">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1>
                                        <v-text-field readonly label="Dosis" v-model="analisis.dosis_medicamento">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1>
                                        <v-text-field readonly label="Unidad de medida"
                                            v-model="analisis.medida_medicamento">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1>
                                        <v-text-field readonly label="Via administración"
                                            v-model="analisis.via_medicamento">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1>
                                        <v-text-field readonly label="Frecuencia de administración"
                                            v-model="analisis.frecuencia_medicamento">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1>
                                        <v-text-field readonly label="Tiempo infusión"
                                            v-model="analisis.infusion_medicamento">
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs12 v-show="analisis.relacion == 'Dispositivos' || analisis.relacion == 'Ambos'">
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-text-field readonly label="Dispositivo/Insumo"
                                            v-model="analisis.dispositivo">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1>
                                        <v-text-field readonly label="Referencia" v-model="analisis.referencia">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1>
                                        <v-text-field readonly label="Invima" v-model="analisis.invima_dispositivo">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1>
                                        <v-text-field readonly label="Lote" v-model="analisis.lote_dispositivo">
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs12
                                v-show="analisis.relacion == 'Equipo biomédico' || analisis.relacion == 'Ambos'">
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-text-field readonly label="Equipo biomédico"
                                            v-model="analisis.nombre_equipo">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1>
                                        <v-text-field readonly label="Marca" v-model="analisis.marca_equipo">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1>
                                        <v-text-field readonly label="Modelo" v-model="analisis.modelo_equipo">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1>
                                        <v-text-field readonly label="Serie" v-model="analisis.serie_equipo">
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs12>
                                <v-textarea readonly label="Descripción de los hechos"
                                    v-model="analisis.descripcion_hechos">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12>
                                <v-textarea readonly label="Acciones que se tomaron" v-model="analisis.accion_tomada">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12>
                                <v-btn v-for="(adjuntoR, index) in analisis.adjuntos" :key="index"
                                    :disabled="search_adjunto">
                                    <a @click="consultarAdjunto(adjuntoR.ruta)">
                                        <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                        {{index+1}}
                                    </a>
                                </v-btn>
                                <v-divider class="my-4"></v-divider>
                            </v-flex>
                            <!-- investigacion -->
                            <v-toolbar color="primary" flat dark>
                                <v-flex xs12 text-xs-center>
                                    <v-toolbar-title>Investigación y Análisis
                                    </v-toolbar-title>
                                </v-flex>
                            </v-toolbar>
                            <v-flex xs3 px-1>
                                <v-spacer class="mt-3"></v-spacer>
                                <v-text-field label="Fecha del analisis" v-model="analisis.fecha_analisis" type="date">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-textarea label="Cronología del suceso" v-model="analisis.cronologia">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12>
                                <v-select :items="clasificaciones" v-model="analisis.clasificacion"
                                    label="Clasificación del suceso" @input="metodologia()">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 v-if="analisis.eventoId == 109">
                                <v-textarea label="Aciones inseguras" v-model="analisis.acciones_inseguras">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12
                                v-if="analisis.eventoId == 109  && analisis.clasificacion != 'Evento adverso prevenible'">
                                <v-select label="Metodologia de Análisis"
                                    @input="metodologia('select', analisis.metodologia)"
                                    :items="['Algoritmo OMS', 'Metodología AMEF', 'WHO AEFI (EPAV)', 'FT - VACA-DELASSALAS','Protocolo de Londres']"
                                    v-model="analisis.metodologia">
                                </v-select>
                            </v-flex>
                            <v-flex xs12
                                v-if="analisis.eventoId != 109 && analisis.clasificacion != 'Evento adverso prevenible' && analisis.clasificacion != 'Evento adverso no prevenible'">
                                <v-select label="Metodologia de Analisis"
                                    @input="metodologia('select', analisis.metodologia)"
                                    :items="['Protocolo de Londres', 'Respuesta Inmediata']"
                                    v-model="analisis.metodologia">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 v-if="vshowestado">
                                <v-btn color="success" @click="preAnalisisSave()">
                                    <span>Guardar Investigación y Análisis</span>
                                </v-btn>
                            </v-flex>
                            <!-- Protocolo de londres -->
                            <v-flex xs12
                                v-if="analisis.clasificacion == 'Evento adverso prevenible' || analisis.clasificacion == 'Evento adverso no prevenible'">
                                <v-toolbar color="primary" flat dark>
                                    <v-flex xs12 text-xs-center>
                                        <v-toolbar-title>Protocolo de londres</v-toolbar-title>
                                    </v-flex>
                                    <v-flex xs1 v-if="vshowestado">
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn text icon color="success" dark v-on="on"
                                                    @click="dialogAcciones = true">
                                                    <v-icon>add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Agregar</span>
                                        </v-tooltip>
                                    </v-flex>
                                </v-toolbar>
                            </v-flex>
                            <v-flex>
                                <!-- Agregar Accion insegura -->
                                <v-dialog v-model="dialogAcciones" max-width="700px">
                                    <v-card>
                                        <v-toolbar color="primary" flat dark>
                                            <v-flex xs12 text-xs-center>
                                                <v-toolbar-title>Agregar acción insegura</v-toolbar-title>
                                            </v-flex>
                                        </v-toolbar>
                                        <v-card-text>
                                            <v-flex xs12>
                                                <v-card-text>
                                                    <v-flex xs11>
                                                        <v-text-field v-model="accion_new.name"
                                                            label="Nombre de la acción inseguras">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-layout row wrap>
                                                        <v-flex xs11>
                                                            <v-autocomplete v-model="accion_new.condiciones_paciente"
                                                                :items="condiciones_paciente"
                                                                label="Condiciones del paciente" multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-tooltip top>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn text icon color="primary" dark v-on="on"
                                                                        @click="addialog('Condiciones del paciente')">
                                                                        <v-icon>add</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Otro</span>
                                                            </v-tooltip>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-autocomplete v-model="accion_new.metodos"
                                                                :items="metodos_procesos" label="Metodos/Procesos"
                                                                multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-tooltip top>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn text icon color="primary" dark v-on="on"
                                                                        @click="addialog('Metodos/Procesos')">
                                                                        <v-icon>add</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Otro</span>
                                                            </v-tooltip>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-autocomplete v-model="accion_new.colaborador"
                                                                :items="colaboradores" label="Colaborador" multiple
                                                                chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-tooltip top>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn text icon color="primary" dark v-on="on"
                                                                        @click="addialog('Colaborador')">
                                                                        <v-icon>add</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Otro</span>
                                                            </v-tooltip>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-autocomplete v-model="accion_new.equipo_trabajo"
                                                                :items="equipo_trabajo" label="Equipo de trabajo"
                                                                multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-tooltip top>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn text icon color="primary" dark v-on="on"
                                                                        @click="addialog('Equipo de trabajo')">
                                                                        <v-icon>add</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Otro</span>
                                                            </v-tooltip>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-autocomplete v-model="accion_new.ambiente"
                                                                :items="ambientes" label="Ambiente" multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-tooltip top>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn text icon color="primary" dark v-on="on"
                                                                        @click="addialog('Ambiente')">
                                                                        <v-icon>add</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Otro</span>
                                                            </v-tooltip>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-autocomplete v-model="accion_new.recursos"
                                                                :items="recursos" label="Recursos/Materiales/Insumos"
                                                                multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-tooltip top>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn text icon color="primary" dark v-on="on"
                                                                        @click="addialog('Recursos/Materiales/Insumos')">
                                                                        <v-icon>add</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Otro</span>
                                                            </v-tooltip>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-autocomplete v-model="accion_new.contexto"
                                                                :items="contexto_institucional"
                                                                label="Contexto institucional" multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-tooltip top>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn text icon color="primary" dark v-on="on"
                                                                        @click="addialog('Contexto institucional')">
                                                                        <v-icon>add</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Otro</span>
                                                            </v-tooltip>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-card-text>
                                            </v-flex>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn color="success" @click="saveAccion()">Agregar</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                                <!-- vista de acciones inseguras -->
                                <v-layout row wrap>
                                    <v-expansion-panel v-for="(acciones, index1) in accion_inseguras" :key="index1">
                                        <v-expansion-panel-content>
                                            <template v-slot:actions>
                                                <v-icon color="success">done</v-icon>
                                            </template>
                                            <template v-slot:header>
                                                <div class="success--text text--darken-2">
                                                    <strong>{{ acciones.name }}</strong></div>
                                            </template>
                                            <v-card>
                                                <v-card-text>
                                                    <v-layout row wrap>
                                                        <v-flex xs12 v-if="acciones.condiciones_paciente !== null">
                                                            <v-autocomplete v-model="acciones.condiciones_paciente"
                                                                :items="acciones.condiciones_paciente"
                                                                label="Condiciones del paciente" multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs12 v-if="acciones.metodos !== null">
                                                            <v-autocomplete v-model="acciones.metodos"
                                                                :items="acciones.metodos" label="Metodos/Procesos"
                                                                multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs12 v-if="acciones.colaborador !== null">
                                                            <v-autocomplete v-model="acciones.colaborador"
                                                                :items="acciones.colaborador" label="Colaborador"
                                                                multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs12 v-if="acciones.equipo_trabajo !== null">
                                                            <v-autocomplete v-model="acciones.equipo_trabajo"
                                                                :items="acciones.equipo_trabajo"
                                                                label="Equipo de trabajo" multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs12 v-if="acciones.ambiente !== null">
                                                            <v-autocomplete v-model="acciones.ambiente"
                                                                :items="acciones.ambiente" label="Ambiente" multiple
                                                                chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs12 v-if="acciones.recursos !== null">
                                                            <v-autocomplete v-model="acciones.recursos"
                                                                :items="acciones.recursos"
                                                                label="Recursos/Materiales/Insumos" multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs12 v-if="acciones.contexto !== null">
                                                            <v-autocomplete v-model="acciones.contexto"
                                                                :items="acciones.contexto"
                                                                label="Contexto institucional" multiple chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs2 v-if="vshowestado">
                                                            <v-btn color="warning" @click="edititems(acciones)">
                                                                <span>Editar</span>
                                                            </v-btn>
                                                        </v-flex>
                                                        <v-flex xs2 v-if="vshowestado">
                                                            <v-btn color="error" @click="deleteitems(acciones.id)">
                                                                <span>Eliminar</span>
                                                            </v-btn>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-card-text>
                                            </v-card>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </v-layout>
                            </v-flex>
                            <!-- Modal respuesta inmediata -->
                            <v-flex v-if="respuesta_inmediata">
                                <v-toolbar color="primary" flat dark>
                                    <v-flex xs12 text-xs-center>
                                        <v-toolbar-title>Respuesta Inmediata</v-toolbar-title>
                                    </v-flex>
                                </v-toolbar>
                                <v-flex xs12>
                                    <v-textarea label="Qué fallo" v-model="analisis.que_fallo">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12>
                                    <v-textarea label="Cómo/por qué falló" v-model="analisis.porque_fallo">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12>
                                    <v-textarea label="Qué causó" v-model="analisis.que_causo">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12>
                                    <v-textarea label="Qué plan de acción se implementó"
                                        v-model="analisis.accion_implemento">
                                    </v-textarea>
                                </v-flex>
                            </v-flex>
                            <!-- Modal Algoritmo OMS -->
                            <v-flex v-if="algoritmOMS">
                                <v-toolbar color="primary" flat dark>
                                    <v-flex xs12 text-xs-center>
                                        <v-toolbar-title>Algoritmo OMS</v-toolbar-title>
                                    </v-flex>
                                </v-toolbar>
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <v-select :items="['Definitiva', 'Probable', 'Posible', 'Improbable', 'Condicional/No clasificada',
                                            'No evaluable/ Inclasificable']" v-model="analisis.evaluacion_causalidad"
                                            label="Evaluación de causalidad">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-select :items="['Serio', 'No Serio']" v-model="analisis.clasificacion_invima"
                                            label="Clasificación Invima">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6 v-show="analisis.clasificacion_invima == 'Serio'">
                                        <v-select
                                            :items="['Produjo o prolongó hospitalización', 'Anomalía congénita', 'Amenaza de vida',
                                            'Muerte', 'Produjo discapacidad o incapacidad permanente / condición médica importante']"
                                            v-model="analisis.seriedad" label="Seriedad">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6 v-if="analisis.seriedad == 'Muerte'">
                                        <v-text-field v-model="analisis.fecha_muerte" label="Fecha Muerte" type="date">
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <!-- Modal Metodología AMEF -->
                            <v-flex v-if="amef">
                                <v-toolbar color="primary" flat dark>
                                    <v-flex xs12 text-xs-center>
                                        <v-toolbar-title>Metodología AMEF</v-toolbar-title>
                                    </v-flex>
                                </v-toolbar>
                                <v-flex xs12>
                                    <v-textarea label="Elemento/Función" v-model="analisis.elemento_funcion">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12>
                                    <v-textarea label="Modo de fallo" v-model="analisis.modo_fallo">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12>
                                    <v-textarea label="Efecto" v-model="analisis.efecto">
                                    </v-textarea>
                                </v-flex>
                                <v-layout row wrap>
                                    <v-flex xs4>
                                        <v-text-field label="NPR" v-model="analisis.npr" type="number">
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-flex xs12>
                                    <v-textarea label="Acciones propuestas" v-model="analisis.acciones_propuestas">
                                    </v-textarea>
                                </v-flex>
                            </v-flex>
                            <!-- Modal WHO AEFI (EPAV) -->
                            <v-flex v-if="algoritmoWHO">
                                <v-toolbar color="primary" flat dark>
                                    <v-flex xs12 text-xs-center>
                                        <v-toolbar-title>WHO AEFI (EPAV)</v-toolbar-title>
                                    </v-flex>
                                </v-toolbar>
                                <v-flex xs11 class="mt-3">
                                    <v-select :items="respuestas" v-model="analisis.causas_esavi"
                                        label="¿Hay evidencia determinante que indique que otras causas originaron el ESAVI?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.asociacion_esavi"
                                        label="¿Hay una asociación descrita en la literatura, entre el ESAVI y la vacuna o la vacunación?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.ventana_mayoriesgo"
                                        label="¿El evento estuvo dentro de la ventana de tiempo de mayor riesgo?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.evidencia_asociacioncausal"
                                        label="¿Hay evidencia en contra de una asociación causal?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.factores_esavi"
                                        label="¿Existen otros factores que pudieron haber impactado en el desarrollo del ESAVI?">
                                    </v-select>
                                </v-flex>
                                <!-- FARMACOVIGILANCIA -->
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <v-select
                                            :items="['Reacción relacionada con la vacuna', 'Reacción relacionada con defectos en la calidad de la vacuna', 'Reacción relacionada con un error en la inmunización', 'Reacción relacionada con la ansiedad generalizada por la inmunización', 'La relación temporal es coherente, pero la evidencia no es concluyente',
                                            'Tendencias contradictorias de coherencia e inconsistencia para una sociación','Coincidente (condiciones subyacentes o emergentes)','Inclasificable']"
                                            v-model="analisis.evaluacion_causalidad" label="Evaluación de causalidad">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-select :items="['Serio', 'No Serio']" v-model="analisis.clasificacion_invima"
                                            label="Clasificación Invima">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6 v-show="analisis.clasificacion_invima == 'Serio'">
                                        <v-select
                                            :items="['Produjo o prolongó hospitalización', 'Anomalía congénita', 'Amenaza de vida',
                                            'Muerte', 'Produjo discapacidad o incapacidad permanente / condición médica importante']"
                                            v-model="analisis.seriedad" label="Seriedad">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6 v-if="analisis.seriedad == 'Muerte'">
                                        <v-text-field v-model="analisis.fecha_muerte" label="Fecha Muerte" type="date">
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <!-- Modal algoritmoVACA -->
                            <v-flex v-if="algoritmoVACA">
                                <v-toolbar color="primary" flat dark>
                                    <v-flex xs12 text-xs-center>
                                        <v-toolbar-title>FT - VACA-DELASSALAS</v-toolbar-title>
                                    </v-flex>
                                </v-toolbar>
                                <v-flex xs11 class="mt-3">
                                    <v-select :items="respuestas" v-model="analisis.farmaco_cinetica"
                                        label="¿El FT se refiere a un fármaco de cinética compleja?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.condiciones_farmacocinetica"
                                        label="¿El paciente presenta condiciones clínicas que alteren la farmacocinética?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.prescribio_manerainadecuada"
                                        label="¿El medicamento se prescribió de manera inadecuada">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.medicamento_manerainadecuada"
                                        label="¿El medicamento se usó de manera inadecuada?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.medicamento_entrenamiento"
                                        label="¿La administracion del medicamento requiere un entrenamiento especial del paciente?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.potenciales_interacciones"
                                        label="¿Existen potenciales interacciones?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.notificacion_refieremedicamento"
                                        label="¿La notificación de FT se refiere a un medicamento genérico o una marca comercial?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.problema_biofarmaceutico"
                                        label="¿Existe algún problema biofarmacéutico estudiado?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.deficiencias_sistemas"
                                        label="¿Existen deficiencias en los sistemas de almacenamiento del medicamento?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs11>
                                    <v-select :items="respuestas" v-model="analisis.factores_asociados"
                                        label="¿Existen otros factores asociados asociados que pudieran explicar el FT?">
                                    </v-select>
                                </v-flex>
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <v-select :items="['Posiblemente asociado al uso inadecuado del medicamento', 'Notificación posiblemente inducida',
                                                'Posiblemente asociado a un problema biofarmacéutico (calidad)', 'Posiblemente asociado a respuesta idiosincrática u otras razones no establecidas',
                                                'No se cuenta con información suficiente para el análisis']"
                                            v-model="analisis.evaluacion_causalidad" label="Evaluación de causalidad">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-select :items="['Serio', 'No Serio']" v-model="analisis.clasificacion_invima"
                                            label="Clasificación Invima">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6 v-show="analisis.clasificacion_invima == 'Serio'">
                                        <v-select
                                            :items="['Produjo o prolongó hospitalización', 'Anomalía congénita', 'Amenaza de vida',
                                            'Muerte', 'Produjo discapacidad o incapacidad permanente / condición médica importante']"
                                            v-model="analisis.seriedad" label="Seriedad">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs6 v-if="analisis.seriedad == 'Muerte'">
                                        <v-text-field v-model="analisis.fecha_muerte" label="Fecha Muerte" type="date">
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <!-- Modal Consecuencias y acciones de mejora -->
                            <v-toolbar color="primary" flat dark>
                                <v-flex xs12 text-xs-center>
                                    <v-toolbar-title>Consecuencias y acciones de mejora</v-toolbar-title>
                                </v-flex>
                            </v-toolbar>
                            <v-flex xs12>
                                <v-textarea v-model="analisis.descripcion_consecuencias"
                                    label="Descripción consecuencias">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12>
                                <v-textarea v-model="analisis.accion_resarcimiento" label="Acciones de Resarcimiento">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12>
                                <v-select
                                    :items="['Recuperado / Resuelto sin secuelas', 'Recuperado / Resuelto con secuelas',
                                            'Recuperando / Resolviendo','No recuperado / No resuelto', 'Fatal', 'Desconocido', 'Muerte']"
                                    v-model="analisis.desenlace_evento" label="Desenlace del evento">
                                </v-select>
                            </v-flex>
                            <v-toolbar color="primary" flat dark>
                                <v-flex xs12 text-xs-center>
                                    <v-toolbar-title>Plan de mejora
                                    </v-toolbar-title>
                                </v-flex>
                                <v-flex xs1 v-if="vshowestado">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text icon color="success" dark v-on="on"
                                                @click="dialogAccionesMejora = true">
                                                <v-icon>add</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Agregar acción mejora</span>
                                    </v-tooltip>
                                </v-flex>
                            </v-toolbar>

                            <!-- agregar acciones de mejora -->
                            <v-dialog v-model="dialogAccionesMejora" max-width="700px">
                                <v-card>
                                    <v-toolbar color="primary" flat dark>
                                        <v-flex xs12 text-xs-center>
                                            <v-toolbar-title>Agregar acción mejora
                                            </v-toolbar-title>
                                        </v-flex>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-layout row wrap>
                                            <v-flex xs11>
                                                <v-text-field label="Nombre del acción de mejora"
                                                    v-model="acc_mejora.nombre">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-autocomplete :items="user_activos" label="Responsable"
                                                    item-text="nombre" item-value="name" multiple
                                                    v-model="acc_mejora.responsables" />
                                            </v-flex>
                                            <v-flex xs4 px-1>
                                                <v-text-field label="Fecha de cumplimiento" type="date"
                                                    v-model="acc_mejora.fecha_cumplimiento">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs4 px-1 v-if="analisis.estado_id != 5">
                                                <v-select :items="['Pendiente', 'Cumplido']" v-model="acc_mejora.estado"
                                                    label="Estado">
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs12 v-if="analisis.estado_id != 5">
                                                <v-textarea v-model="acc_mejora.observacion" label="Observaciones"
                                                    readonly>
                                                </v-textarea>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-btn v-for="(adjuntoR, index) in acc_mejora.adjuntos" :key="index"
                                                    :disabled="search_adjunto">
                                                    <a @click="consultarAdjunto(adjuntoR.ruta)">
                                                        <v-icon color="dark">mdi-cloud-upload
                                                        </v-icon>Adjunto
                                                        {{index+1}}
                                                    </a>
                                                </v-btn>
                                                <v-divider class="my-4"></v-divider>
                                            </v-flex>
                                        </v-layout>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="success" @click="saveAccionMejora()">Agregar</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>

                            <!-- modal acciones de mejora -->
                            <v-expansion-panel v-for="(accionesmejora, index3) in accion_mejoras" :key="index3">
                                <v-expansion-panel-content>
                                    <template v-slot:actions>
                                        <v-icon color="success">done</v-icon>
                                    </template>
                                    <template v-slot:header>
                                        <div class="success--text text--darken-2">
                                            <strong>{{ accionesmejora.nombre }}</strong></div>
                                    </template>
                                    <v-card>
                                        <v-card-text>
                                            <v-layout row wrap>
                                                <v-flex xs12>
                                                    <v-autocomplete :items="user_activos" label="Responsable"
                                                        item-text="nombre" item-value="name" multiple
                                                        v-model="accionesmejora.responsables" />
                                                </v-flex>
                                                <v-flex xs4 px-1>
                                                    <v-text-field label="Fecha de cumplimiento" type="date"
                                                        v-model="accionesmejora.fecha_cumplimiento">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs2 v-if="vshowestado">
                                                    <v-btn color="warning" @click="editAccionesMejora(accionesmejora)">
                                                        <span>Editar</span>
                                                    </v-btn>
                                                </v-flex>
                                                <v-flex xs2 v-if="vshowestado">
                                                    <v-btn color="error"
                                                        @click="deleteAccionesMejora(accionesmejora.id)">
                                                        <span>Eliminar</span>
                                                    </v-btn>
                                                </v-flex>
                                            </v-layout>
                                        </v-card-text>
                                    </v-card>
                                </v-expansion-panel-content>
                            </v-expansion-panel>

                            <v-flex xs6 v-if="analisis.nombreEvento == 'Farmacovigilancia' || analisis.nombreEvento == 'Reactivovigilancia' ||
                                analisis.nombreEvento == 'Tecnovigilancia' ||  analisis.nombreEvento == 'Infecciones asociadas al cuidado de la salud (IAAS)' ||
                                analisis.nombreEvento == 'Administración de hemoderivados'">
                                <v-select :items="['SI', 'NO']" v-model="analisis.requiere_reporte_ente"
                                    label="Requiere reportar al ente?">
                                </v-select>
                            </v-flex>
                            <v-flex xs6 v-if="analisis.nombreEvento == 'Farmacovigilancia'">
                                <v-select :items="['SI', 'NO']" v-model="analisis.requiere_marca_especifica"
                                    label="Requiere marca especifica?">
                                </v-select>
                            </v-flex>

                            <!-- adjuntos -->
                            <!-- <v-flex xs12 v-if="vshowestado">
                                <input id="adjuntos" multiple ref="adjuntos" type="file" />
                            </v-flex> -->
                        </v-layout>
                    </v-container>
                </v-card-text>
                <!-- acciones finales -->
                <v-card-actions>
                    <v-btn v-if="vshowestado" color="primary" @click="finalizar()">
                        <span>Finalizar</span>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn v-if="vshowestado" color="success" @click="preAnalisis()">
                        <span>Guardar</span>
                    </v-btn>
                    <v-btn color="error" @click="closeAnalisis()">
                        <span>Salir</span>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </div>
</template>
<script>
    import {
        AdjuntoService
    } from '../../../services';
    export default {
        data() {
            return {
                allentidades: [],
                pendientes: [],
                loading: false,
                headers: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Sede ocurrencia',
                        align: 'left',
                        value: 'sede_ocurrencia'
                    },
                    {
                        text: 'Fecha ocurrencia',
                        align: 'left',
                        value: 'fecha_ocurrencia'
                    },
                    {
                        text: 'Documento',
                        align: 'left',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'Primer_Nom'
                    },
                    {
                        text: 'Nombre suceso',
                        align: 'left',
                        value: 'nombreEvento'
                    },
                    {
                        text: 'Entidad',
                        align: 'left',
                        value: 'entidad'
                    },
                    {
                        text: 'Priorización',
                        align: 'left',
                        value: ''
                    },
                    {
                        text: 'Accion',
                        align: 'left'
                    }
                ],
                analisis: {},
                dialogAnalisis: false,
                filter: {},
                filters: {
                    state: '',
                    start_date: null,
                    end_date: null,
                    suceso: '',
                    id: '',
                    documento: '',
                    entidad: '',
                },
                alleventos: [],
                protocolo_londres: false,
                respuesta_inmediata: false,
                algoritmOMS: false,
                amef: false,
                algoritmoWHO: false,
                algoritmoVACA: false,
                respuestas: ['SI', 'NO', 'NO SABE'],
                clasificaciones: ['Evento adverso prevenible', 'Evento adverso no prevenible',
                    'Incidente', 'Complicación', 'Indicio de atención insegura'
                ],
                dialogAccionesFarmaco: false,
                accion_inseguras: [],
                dialogAcciones: false,
                newAccion: [],
                condiciones_paciente: ['Condiciones anatomofisiologicas', 'Patologia en curso', 'Edad',
                    'Su lenguaje y comunicacion',
                    'No dherencia al tratamiento indicado', 'No adherencia a las normas y recomendaciones'
                ],
                metodos_procesos: ['No existen procesos documentados',
                    'Los procesos documentados no son claros y/o no se adaptan a la dinamica intitucional',
                    'No adherencia a los procesos documentados',
                    'El sistema de la institucion limita la ejecucion de los procesos'
                ],
                colaboradores: ['Desconocimiento', 'Falta de habilidades', 'Debilidad de competencias',
                    'No adherencia a los lineamientos establecidos',
                    'Lapsus, distraccion'
                ],
                equipo_trabajo: ['Falta de capacitacion o entrenamiento al equipo',
                    'Fallas en la comunicacion (comunicacion inefectiva)',
                    'Falta de liderazgo y/o supervision de quien lidera el proceso',
                    'El equipo no se encuentra adecuadamente estructurado para la ejecucion de los procesos',
                    'No se encuentran definidas las tareas entre los miebros del equipo'
                ],
                ambientes: ['Insuficiencia del personal', 'Sobrecarga laboral / cansancio / estres',
                    'Inadecuada distribucion de turnos',
                    'Clima laboral',
                    'Ambiente fisico (iluminacion inadecuada, contaminacion visual o auditiva, obstaculos en los pasillos)'
                ],
                recursos: ['No se han solicitado los recursos necesarios',
                    'No asignacion del recurso por parte de la institucion',
                    'Recursos financieros limitados',
                    'No disponibilidad de medicamento / dispositivo medico / reactivo',
                    'Medicamento / dispositivo médico / reactivo vencido',
                    'Mal uso de medicamento / dispositivo medico / reactivo'
                ],
                contexto_institucional: ['Decisiones gerenciales',
                    'No estan definidas las políticas para la operativizacion de los procesos',
                    'Desconocimento de las politicas establecidas', 'Percepción de falta de cultura organizacional'
                ],
                accion_new: {},
                dialogItems: false,
                newItems: [],
                campo: '',
                users: [],
                dialogAccionesMejora: false,
                newAccionMejora: [],
                accion_mejoras: [],
                acc_mejora: {},
                cerrados: [],
                search_adjunto: false,
                adjuntos: [],
                accion_inseguras: []

            }
        },
        mounted() {
            this.geteventos();
            this.getpendientes();
            this.getUser();
        },
        computed: {
            user_activos() {
                let finded = [];
                this.users.forEach(u => {
                    if (u.estado == 1) {
                        finded.push(u)
                    }
                });
                return finded;
            },
            vshowestado() {
                if (this.analisis.estado_id == 13 || this.analisis.estado_id == 46) {
                    return false;
                }
                return true;
            }
        },
        methods: {
            getUser() {
                axios.get('/api/user/all')
                    .then(res => this.users = res.data.map((us) => {
                        return {
                            id: us.id,
                            nombre: `${us.cedula} - ${us.name} ${us.apellido}`,
                            estado: us.estado_user,
                            name: `${us.name} ${us.apellido}`,
                        }
                    }));
            },
            getpendientes() {
                this.loading = true;
                axios.get('/api/evento/getpendientesAsignado').then((res) => {
                    this.pendientes = res.data
                    this.allentidades = [];
                    this.pendientes.map(p => {
                        if (p.entidad != null || p.entidad != undefined) {
                            this.allentidades.push(p.entidad)
                        }
                    })
                    this.loading = false;
                })
            },
            getcerrados() {
                this.loading = true;
                axios.get('/api/evento/getcerradosAsignado').then((res) => {
                    this.cerrados = res.data
                    this.allentidades = [];
                    this.cerrados.map(p => {
                        if (p.entidad != null || p.entidad != undefined) {
                            this.allentidades.push(p.entidad)
                        }
                    })
                    this.loading = false;
                })
            },
            geteventos() {
                axios.get('/api/evento/allsumimedical').then(res => {
                    this.alleventos = res.data
                });
            },
            openAnalisis(item) {
                axios.get('/api/evento/buscarAnalisis/' + item).then(res => {
                    this.analisis = res.data
                    this.getAccion_inseguras(this.analisis.analisisevento_id)
                    this.getAccion_mejoras(this.analisis.analisisevento_id)
                });
                this.metodologia();
                this.dialogAnalisis = true
            },

            getAccion_inseguras($id) {
                axios.get('/api/evento/getAccion_inseguras/' + $id).then(res => {
                    this.accion_inseguras = res.data
                });
            },

            getAccion_mejoras($id) {
                axios.get('/api/evento/getAccion_mejoras/' + $id).then(res => {
                    this.accion_mejoras = res.data
                });
            },
            metodologia(tipo, metodo) {
                if (this.analisis.metodologia == 'Protocolo de Londres') {
                    this.protocolo_londres = true
                    this.respuesta_inmediata = false
                    return
                } else if (this.analisis.clasificacion == 'Evento adverso prevenible' || this.analisis.clasificacion ==
                    'Evento adverso no prevenible') {
                    tipo = 'select',
                        metodo = 'Protocolo de Londres'
                } else if (this.analisis.metodologia == 'Respuesta Inmediata') {
                    this.protocolo_londres = false
                    this.respuesta_inmediata = true
                } else if (this.analisis.metodologia == 'Algoritmo OMS') {
                    this.algoritmOMS = true
                    this.amef = false
                    this.algoritmoWHO = false
                    this.algoritmoVACA = false
                } else if (this.analisis.metodologia == 'Metodología AMEF') {
                    this.algoritmOMS = false
                    this.amef = true
                    this.algoritmoWHO = false
                    this.algoritmoVACA = false
                } else if (this.analisis.metodologia == 'WHO AEFI (EPAV)') {
                    this.algoritmOMS = false
                    this.amef = false
                    this.algoritmoWHO = true
                    this.algoritmoVACA = false
                } else if (this.analisis.metodologia == 'FT - VACA-DELASSALAS') {
                    this.algoritmOMS = false
                    this.amef = false
                    this.algoritmoWHO = false
                    this.algoritmoVACA = true
                }
                if (tipo == 'select') {
                    if (metodo == 'Protocolo de Londres') {
                        this.protocolo_londres = true
                        this.respuesta_inmediata = false
                    } else if (metodo == 'Respuesta Inmediata') {
                        this.protocolo_londres = false
                        this.respuesta_inmediata = true
                    } else if (metodo == 'Algoritmo OMS') {
                        this.algoritmOMS = true
                        this.amef = false
                        this.algoritmoWHO = false
                        this.algoritmoVACA = false
                    } else if (metodo == 'Metodología AMEF') {
                        this.algoritmOMS = false
                        this.amef = true
                        this.algoritmoWHO = false
                        this.algoritmoVACA = false
                    } else if (metodo == 'WHO AEFI (EPAV)') {
                        this.algoritmOMS = false
                        this.amef = false
                        this.algoritmoWHO = true
                        this.algoritmoVACA = false
                    } else if (metodo == 'FT - VACA-DELASSALAS') {
                        this.algoritmOMS = false
                        this.amef = false
                        this.algoritmoWHO = false
                        this.algoritmoVACA = true
                    }
                } else {
                    if (this.analisis.eventoId != 109) {
                        if (this.analisis.nivel_priorizacion == 'ALTO') {
                            this.protocolo_londres = true;
                            this.analisis.metodologia = 'Protocolo de Londres'
                        } else if (this.analisis.nivel_priorizacion == 'MODERADO') {
                            this.protocolo_londres = false;
                        } else {
                            this.protocolo_londres = false;
                        }
                    }
                }
            },
            colorChip(nivel) {
                if (nivel == 'ALTO') {
                    return 'error white--text';
                } else if (nivel == 'MODERADO') {
                    return 'yellow black--text';
                } else {
                    return 'success white--text';
                }
            },
            colorTd(dias, tipo) {
                if (tipo == 'Urgente') {
                    if (dias <= 1) {
                        return 'error white--text';
                    } else {
                        return 'yellow black--text';
                    }
                } else if (tipo == 'Muy prioritario') {
                    if (dias <= 2) {
                        return 'error white--text';
                    } else if (dias <= 3) {
                        return 'yellow black--text';
                    } else {
                        return 'success white--text';
                    }
                } else if (tipo == 'Prioritario') {
                    if (dias <= 2) {
                        return 'error white--text';
                    } else if (dias <= 6) {
                        return 'yellow black--text';
                    } else {
                        return 'success white--text';
                    }
                } else if (tipo == 'No prioritario') {
                    if (dias <= 3) {
                        return 'error white--text';
                    } else if (dias <= 7) {
                        return 'yellow black--text';
                    } else {
                        return 'success white--text';
                    }
                }
            },
            // addAccion() {
            //     if (this.newAccion.length <= 0) return
            //     for (let i = 0; i < this.newAccion.length; i++) {
            //         let str = this.newAccion[i].charAt(0).toUpperCase() + this.newAccion[i].slice(1);
            //         this.accion_inseguras.push(str);
            //     }
            //     this.newAccion = []
            //     this.dialogAcciones = false
            // },
            // addAccionMejora() {
            //     if (this.newAccionMejora.length <= 0) return
            //     for (let i = 0; i < this.newAccionMejora.length; i++) {
            //         let str = this.newAccionMejora[i].charAt(0).toUpperCase() + this.newAccionMejora[i].slice(1);
            //         this.accion_mejoras.push(str);
            //     }
            //     this.newAccionMejora = []
            //     this.dialogAccionesMejora = false
            // },
            closeAnalisis() {
                this.dialogAnalisis = false
                this.protocolo_londres = false
                this.respuesta_inmediata = false
                this.algoritmOMS = false
                this.amef = false
                this.algoritmoWHO = false
                this.algoritmoVACA = false
                this.accion_inseguras = []
                this.analisis.accion_inseguras = []
                this.newAccionMejora = []
                this.accion_mejoras = []
                this.acc_mejora = {}
                this.newAccionMejora = []
                this.accion_new = {}
                this.getpendientes();
                this.getcerrados();
            },

            // realiza un pre guadrado del reporte
            preAnalisis() {
                if (this.analisis.fecha_analisis == '' || this.analisis.fecha_analisis ==
                    null) {
                    this.$alerError('Debe ingresar una fecha de analisis!');
                    return
                }
                if (this.analisis.cronologia == '' || this.analisis.cronologia == null) {
                    this.$alerError('No puede estar el campo cronología vacio!');
                    return
                }
                if (this.analisis.clasificacion == '' || this.analisis.clasificacion == null) {
                    this.$alerError('Debe seleccionar una clasificación!');
                    return
                }
                this.analisis.accion_inseguras = null
                this.analisis.accion_mejora = null
                axios.post('/api/evento/preAnalisis', this.analisis).then(res => {
                    this.$alerSuccess('Analisis guardado con exito!');
                    this.closeAnalisis()
                }).catch(err => {
                    this.$alerError(err.response.data.message);
                })
            },

            //Guarda la investigación y analisis
            preAnalisisSave() {
                if (this.analisis.fecha_analisis == '' || this.analisis.fecha_analisis ==
                    null) {
                    this.$alerError('Debe ingresar una fecha de analisis!');
                    return
                }
                if (this.analisis.cronologia == '' || this.analisis.cronologia == null) {
                    this.$alerError('No puede estar el campo cronología vacio!');
                    return
                }
                if (this.analisis.clasificacion == '' || this.analisis.clasificacion == null) {
                    this.$alerError('Debe seleccionar una clasificación!');
                    return
                }
                axios.post('/api/evento/preAnalisis', this.analisis).then(res => {
                    this.analisis.analisisevento_id = res.data.analisisevento_id
                    this.$alerSuccess('Analisis guardado con exito!');
                }).catch(err => {
                    this.$alerError(err.response.data.message);
                })
            },

            //Guarda las acciones inseguras
            saveAccion() {
                this.accion_new.analisisevento_id = this.analisis.analisisevento_id;
                if (this.accion_new.analisisevento_id == null) {
                    this.$alerError('Debe primero  guardar la investigación y análisis');
                    return;
                }
                axios.post('/api/evento/addAcciones', this.accion_new).then(res => {
                    this.accion_new = {}
                    this.getAccion_inseguras(this.analisis.analisisevento_id)
                    this.dialogAcciones = false
                    this.$alerSuccess(res.data.mensaje);
                }).catch(err => {
                    this.$alerError(err.response.data.message);
                })
            },

            //Guarda las acciones de mejora
            saveAccionMejora() {
                this.acc_mejora.analisisevento_id = this.analisis.analisisevento_id;
                if (this.acc_mejora.analisisevento_id == null) {
                    this.$alerError('Debe primero  guardar la investigación y análisis');
                    return;
                }
                if (this.acc_mejora.fecha_cumplimiento == null) {
                    this.$alerError('Debe ingresar una fecha de cumplimiento');
                    return;
                }
                axios.post('/api/evento/addAccionesMejoras', this.acc_mejora).then(res => {
                    this.acc_mejora = {}
                    this.getAccion_mejoras(this.analisis.analisisevento_id)
                    this.dialogAccionesMejora = false
                    this.$alerSuccess(res.data.mensaje);
                }).catch(err => {
                    this.$alerError(err.response.data.message);
                })
            },
            addialog(item) {
                this.dialogItems = true
                this.campo = item
            },
            addItems() {
                if (this.newItems.length <= 0) return
                if (this.campo == 'Condiciones del paciente') {
                    this.newItems.forEach(it => {
                        this.condiciones_paciente.push(it)
                    })
                    this.newItems = []
                }
                if (this.campo == 'Metodos/Procesos') {
                    this.newItems.forEach(it => {
                        this.metodos_procesos.push(it)
                    })
                    this.newItems = []
                }
                if (this.campo == 'Colaborador') {
                    this.newItems.forEach(it => {
                        this.colaboradores.push(it)
                    })
                    this.newItems = []
                }
                if (this.campo == 'Equipo de trabajo') {
                    this.newItems.forEach(it => {
                        this.equipo_trabajo.push(it)
                    })
                    this.newItems = []
                }
                if (this.campo == 'Ambiente') {
                    this.newItems.forEach(it => {
                        this.ambientes.push(it)
                    })
                    this.newItems = []
                }
                if (this.campo == 'Recursos/Materiales/Insumos') {
                    this.newItems.forEach(it => {
                        this.recursos.push(it)
                    })
                    this.newItems = []
                }
                if (this.campo == 'Contexto institucional') {
                    this.newItems.forEach(it => {
                        this.contexto_institucional.push(it)
                    })
                    this.newItems = []
                }
                this.dialogItems = false
            },
            edititems(item) {
                axios.post('/api/evento/editAcciones/' + item.id, item).then(res => {
                    this.$alerSuccess('Acción editada con exito!');
                    this.closeAnalisis()
                }).catch(err => {
                    this.$alerError('Hubo un error, o ya existe esta acción!');
                })
            },
            deleteitems(item) {
                axios.post('/api/evento/deleteAcciones/' + item).then(res => {
                    this.$alerSuccess('Acción eliminada con exito!');
                    this.closeAnalisis()
                }).catch(err => {
                    this.$alerError('Hubo un error!');
                })
            },
            editAccionesMejora(item) {
                axios.post('/api/evento/editAccionMejora/' + item.id, item).then(res => {
                    this.$alerSuccess('Acción de mejora editada con exito!');
                }).catch(err => {
                    this.$alerError('Hubo un error, o ya existe esta acción de mejora!');
                })
            },
            deleteAccionesMejora(item) {
                axios.post('/api/evento/deleteAccioneMejora/' + item).then(res => {
                    this.$alerSuccess('Acción de mejora eliminada con exito!');
                    this.closeAnalisis()
                }).catch(err => {
                    this.$alerError('Hubo un error!');
                })
            },
            async finalizar() {
                swal({
                    title: 'Esta seguro de finalizar el analisis ?',
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(async willDelete => {
                    if (willDelete) {
                        if (this.analisis.fecha_analisis == '' || this.analisis.fecha_analisis ==
                            null) {
                            this.$alerError('Debe ingresar una fecha de analisis!');
                            return
                        }
                        if (this.analisis.cronologia == '' || this.analisis.cronologia == null) {
                            this.$alerError('No puede estar el campo cronología vacio!');
                            return
                        }
                        if (this.analisis.clasificacion == '' || this.analisis.clasificacion == null) {
                            this.$alerError('Debe seleccionar una clasificación!');
                            return
                        }
                        if (this.analisis.metodologia == 'Protocolo de Londres') {
                            this.analisis.pre_analisis.forEach(element => {
                                if (element.accion_inseguras.length <= 0) {
                                    this.$alerError(
                                        'Debe ingresar por lo menos una acción insegura!');
                                    return
                                }
                            });
                        }
                        if (this.analisis.clasificacion == 'Evento adverso prevenible') {
                            if (this.analisis.accion_resarcimiento == null) {
                                this.$alerError(
                                    'No puede dejar el campo acción resarcimiento vacío!');
                                return
                            }
                            if (this.accion_inseguras.length <= 0) {
                                this.$alerError(
                                    'Debe ingresar por lo menos una acción insegura!');
                                return
                            }
                            if (this.accion_mejoras.length <= 0) {
                                this.$alerError(
                                    'Debe ingresar por lo menos una acción de mejora!');
                                return
                            }
                        }
                        if (this.analisis.metodologia == 'Respuesta Inmediata') {
                            if (this.analisis.porque_fallo == '' || this.analisis.porque_fallo ==
                                null ||
                                this.analisis.que_fallo == '' || this.analisis.que_fallo == null ||
                                this.analisis.que_causo == '' || this.analisis.que_causo == null ||
                                this.analisis.accion_implemento == '' || this.analisis
                                .accion_implemento ==
                                null) {
                                this.$alerError('Debe ingresar los campos obligatorios!');
                                return
                            }
                        }
                        await axios.post('/api/evento/preAnalisis', this.analisis)
                        await axios.post('/api/evento/savepreAnalisis/' + this.analisis.id).then(
                            res => {
                                this.$alerSuccess('Analisis del evento finalizado con exito!');
                                this.closeAnalisis()
                            }).catch(err => {
                            this.$alerError('Hubo un error!');
                        })
                    }
                });
            },
            customFilter(items, filters, filter, headers) {
                const cf = new this.$MultiFilters(items, filters, filter, headers);

                cf.registerFilter('id', function (rad, items) {
                    if (rad != undefined) {
                        if (rad.trim() === '') return items;
                        return items.filter(item => {
                            let id = String(item.id)
                            return id.toLowerCase().includes(rad.toLowerCase());
                        }, rad);
                    } else {
                        return items;
                    }
                });

                cf.registerFilter('documento', function (doc, items) {
                    if (doc != undefined) {
                        if (doc.trim() === '') return items;
                        return items.filter(item => {
                            if (item.Num_Doc != undefined) {
                                return item.Num_Doc.toLowerCase().includes(doc.toLowerCase());
                            }
                        }, doc);
                    } else {
                        return items;
                    }
                });

                cf.registerFilter('suceso', function (suc, items) {
                    if (suc != undefined) {
                        if (suc.trim() === '') return items;
                        return items.filter(item => {
                            return item.nombreEvento.toLowerCase().includes(suc.toLowerCase());
                        }, suc);
                    } else {
                        return items;
                    }
                });

                cf.registerFilter('entidad', function (ent, items) {
                    if (ent != undefined) {
                        if (ent.trim() === '') return items;
                        return items.filter(item => {
                            if (item.entidad != undefined) {
                                return item.entidad.toLowerCase().includes(ent.toLowerCase());
                            }
                        }, ent);
                    } else {
                        return items;
                    }
                });


                cf.registerFilter('start_date', function (start_date, items) {
                    if (start_date != undefined) {
                        if (start_date === null) return items;
                        return items.filter(item => {
                            return item.created_at.split(' ')[0] >= start_date;
                        }, start_date);
                    } else {
                        return items;
                    }
                });

                cf.registerFilter('end_date', function (end_date, items) {
                    if (end_date != undefined) {
                        if (end_date === null) return items;
                        return items.filter(item => {
                            return item.created_at.split(' ')[0] <= end_date;
                        }, end_date);
                    } else {
                        return items;
                    }
                });

                return cf.runFilters();
            },

            filterId(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    id: val
                });
            },

            filterDocumento(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    documento: val
                });
            },

            filterSuceso(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    suceso: val
                });
            },

            filterEntidad(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    entidad: val
                });
            },

            filterStartDate(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    start_date: val
                });
            },

            filterEndDate(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    end_date: val
                });
            },

            clearFilter() {
                for (const prop of Object.getOwnPropertyNames(this.filter)) {
                    this.filter[prop] = '';
                }
                this.filterId();
                this.filterStartDate()
                this.filterEndDate()
                this.filterDocumento()
                this.filterEntidad()
                this.filterSuceso()
            },

            async consultarAdjunto(ruta) {
                this.search_adjunto = true
                try {
                    let adj = await AdjuntoService.get(ruta);
                    let blob = new Blob([adj[1]], {
                        type: adj[0]
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");
                    this.search_adjunto = false
                } catch (error) {
                    this.search_adjunto = false
                }
            },
        },
    }

</script>
