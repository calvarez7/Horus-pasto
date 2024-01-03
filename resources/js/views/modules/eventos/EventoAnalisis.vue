<template>
    <div>
        <v-tabs centered color="white" icons-and-text>
            <v-tabs-slider color="primary"></v-tabs-slider>

            <v-tab href="#tab-1" color="black--text">
                Pendientes
                <v-icon color="black">block</v-icon>
            </v-tab>

            <v-tab href="#tab-2">
                Cerrados
                <v-icon color="black">check_circle</v-icon>
            </v-tab>

            <v-tab-item :value="'tab-1'">
                <v-card flat>
                    <v-card>
                        <v-card-title>
                            <v-layout row wrap>
                                <v-flex xs2 md2 sm2 px-2>
                                    <v-select :items="[
                                        'Pendientes',
                                        'Asignados',
                                        'Seguimiento Plan Mejora',
                                        'Plan de Mejora Cumplido',
                                        'Analizados',
                                    ]" label="Estado" @input="filterState" v-model="filter.estado">
                                    </v-select>
                                </v-flex>

                                <v-flex xs2 md2 sm2 px-2>
                                    <v-text-field label="id" @input="filterId" v-model="filter.id">
                                    </v-text-field>
                                </v-flex>

                                <v-flex xs2 md2 sm2 px-2>
                                    <v-text-field label="Documento" v-model="filter.documento" @input="filterDocumento">
                                    </v-text-field>
                                </v-flex>

                                <v-flex xs4 md6 sm6 px-2>
                                    <v-autocomplete label="Nombre del suceso" :items="alleventos" item-text="nombre"
                                        item-value="nombre" @input="filterSuceso" v-model="filter.suceso">
                                    </v-autocomplete>
                                </v-flex>

                                <v-flex xs3 md6 sm6 px-2>
                                    <v-autocomplete label="Entidad" :items="allentidades" item-text="nombre"
                                        item-value="nombre" @input="filterEntidad" v-model="filter.entidad">
                                    </v-autocomplete>
                                </v-flex>

                                <v-flex xs3 md6 sm6 px-2>
                                    <v-autocomplete label="Sede de Ocurrencia" :items="sedes" item-text="nombre"
                                        item-value="nombre" @input="filterSede" v-model="filter.sede">
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
                                            <v-btn fab color="error" small v-on="on" @click="clearFilter">
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
                                    <td>{{ props.item.nombre_completo }}</td>
                                    <td>{{ props.item.nombreEvento }}</td>
                                    <td>{{ props.item.entidad }}</td>
                                    <td v-if="props.item.estado_id == 5">
                                        {{ props.item.estado }}
                                        <v-menu open-on-hover right offset-y>
                                            <template v-slot:activator="{ on }">
                                                <v-icon v-on="on">message</v-icon>
                                            </template>
                                            <v-list>
                                                <v-list-tile v-for="(data, index) in props.item.asignado_evento"
                                                    :key="index">
                                                    {{ data.nombreAsignado }}
                                                </v-list-tile>
                                            </v-list>
                                        </v-menu>
                                    </td>
                                    <td v-else>{{ props.item.estado }}</td>
                                    <td>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn text icon color="primary" dark v-on="on"
                                                    @click="openAnalisis(props.item)" :disabled="loading">
                                                    <v-icon>search</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Investigación y analisis</span>
                                        </v-tooltip>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn text icon color="success" dark v-on="on"
                                                    @click="imprimirAnalisis(props.item.id)" :disabled="loading">
                                                    <v-icon>mdi-file-pdf-box</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Imprimir</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                            </v-data-table>
                        </template>
                    </v-card>

                    <v-dialog v-model="dialogAnalisis" v-if="dialogAnalisis" persistent max-width="1000px">
                        <v-card max-height="100%">
                            <v-toolbar color="primary" flat dark>
                                <v-flex xs12 text-xs-center>
                                    <v-toolbar-title>Investigación y analisis</v-toolbar-title>
                                </v-flex>
                            </v-toolbar>
                            <v-divider></v-divider>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout row wrap>
                                        <v-flex xs3 px-1 v-show="analisis.paciente_id">
                                            <v-text-field v-model="analisis.nombre_completo" readonly
                                                label="Nombre y apellidos">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs3 px-1 v-show="analisis.Num_Doc">
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
                                            <v-autocomplete v-model="eventoId" :items="alleventos" class="search-field"
                                                item-text="nombre" item-value="id" label="Nombre del suceso"
                                                :placeholder="analisis.nombreEvento" @input="getclasificacion()">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs6 px-1>
                                            <v-autocomplete v-model="clasificacionId" :items="allclasificacion"
                                                class="search-field" item-text="nombre" item-value="id"
                                                label="Clasificación área" :placeholder="analisis.nombreClasificacion"
                                                @input="getTipoevento()">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs6 px-1>
                                            <v-autocomplete v-model="tipoeventoId" :items="alltipoeventos"
                                                class="search-field" item-text="nombre" item-value="id"
                                                label="Tipo de evento" :placeholder="analisis.nombreTipoevento">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs6 px-1 v-show="analisis.otro_evento">
                                            <v-text-field v-model="analisis.otro_evento" readonly
                                                label="Otro (Nombre del suceso)">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6 px-1>
                                            <VAutocomplete :items="sedesCompletas" v-model="analisis.sedeocurrencia_id"
                                                item-value="id" item-text="nombre" label="Sede ocurrencia" />
                                        </v-flex>
                                        <v-flex xs5 px-1 v-show="analisis.sedeocurrencia_id == 704">
                                            <v-autocomplete :items="socurrencias" v-model="analisis.servicio_ocurrencia"
                                                label="Servicio de ocurrencia" class="search-field"
                                                :placeholder="analisis.servicio_ocurrencia">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs1>
                                            <v-tooltip top v-if="analisis.estado == 'Pendiente'">
                                                <template v-slot:activator="{ on }">
                                                    <v-btn text icon color="success" dark v-on="on" @click="updateAreas()"
                                                        :disabled="loading">
                                                        <v-icon>update</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Actualizar</span>
                                            </v-tooltip>
                                        </v-flex>
                                        <v-flex xs6 px-1>
                                            <v-text-field v-model="analisis.sede_reportante" readonly
                                                label="Sede reportante">
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
                                            <v-text-field v-model="analisis.fecha_ocurrencia" readonly
                                                label="Fecha de ocurrencia">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex v-show="analisis.medicamento">
                                            <v-layout row wrap>
                                                <v-flex xs12>
                                                    <v-text-field readonly v-model="analisis.medicamento"
                                                        label="Medicamento">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs3 px-1>
                                                    <v-text-field readonly label="Dosis"
                                                        v-model="analisis.dosis_medicamento">
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
                                        <v-flex xs12 v-show="analisis.relacion == 'Dispositivos' ||
                                            analisis.relacion == 'Ambos'
                                            ">
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
                                                    <v-text-field readonly label="Invima"
                                                        v-model="analisis.invima_dispositivo">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs3 px-1>
                                                    <v-text-field readonly label="Lote" v-model="analisis.lote_dispositivo">
                                                    </v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                        <v-flex xs12 v-show="analisis.relacion == 'Equipo biomédico' ||
                                            analisis.relacion == 'Ambos'
                                            ">
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
                                            <v-textarea readonly label="Acciones que se tomaron"
                                                v-model="analisis.accion_tomada">
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs7 v-show="analisis.profesional">
                                            <v-text-field v-model="analisis.profesional" readonly
                                                label="Profesional tratante">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-btn v-for="(adjuntoR, index) in analisis.adjuntos" :key="index"
                                                :disabled="search_adjunto">
                                                <a @click="consultarAdjunto(adjuntoR.ruta)">
                                                    <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                    {{ index + 1 }}
                                                </a>
                                            </v-btn>
                                            <v-divider class="my-4"></v-divider>
                                        </v-flex>
                                        <v-flex xs12>
                                            <span><strong>Reportante:</strong> {{ analisis.name }}
                                                {{ analisis.apellido }}&nbsp;</span>
                                            <span><strong>Email:</strong> {{ analisis.email }}</span>
                                            <v-divider class="my-4"></v-divider>
                                        </v-flex>
                                        <v-card max-height="100%" v-if="analisis.estado == 'Analizado' ||
                                            analisis.estado == 'Seguimiento Plan Mejora' ||
                                            analisis.estado == 'Plan de Mejora Cumplido'
                                            ">
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout row wrap>
                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>Investigación y Análisis
                                                                </v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-flex xs3 px-1>
                                                            <v-spacer class="mt-3"></v-spacer>
                                                            <v-text-field label="Fecha del analisis"
                                                                v-model="analisis.fecha_analisis" type="date">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Cronología del suceso"
                                                                v-model="analisis.cronologia">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12 v-if="analisis.eventoId == 109">
                                                            <v-textarea label="Aciones inseguras"
                                                                v-model="analisis.acciones_inseguras">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12 v-if="analisis.eventoId == 109">
                                                            <v-select label="Metodologia de Análisis" @input="
                                                                metodologia('select', analisis.metodologia)
                                                                " :items="[
        'Algoritmo OMS',
        'Metodología AMEF',
        'WHO AEFI (EPAV)',
        'FT - VACA-DELASSALAS',
    ]" v-model="analisis.metodologia">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs12
                                                            v-if="analisis.nivel_priorizacion !== 'ALTO' && analisis.eventoId !== 109">
                                                            <v-select label="Metodologia de Analisis"
                                                                @input="metodologia('select', analisis.metodologia)"
                                                                :items="['Protocolo de Londres', 'Respuesta Inmediata']"
                                                                v-model="analisis.metodologia" ></v-select>
                                                        </v-flex>
                                                        <v-flex xs12 v-if="(protocolo_londres || respuesta_inmediata) &&
                                                            analisis.nivel_priorizacion != 'ALTO'
                                                            " />
                                                        <v-flex v-if="protocolo_londres">
                                                            <v-toolbar color="primary" flat dark>
                                                                <v-flex xs12 text-xs-center>
                                                                    <v-toolbar-title>Protocolo de londres
                                                                    </v-toolbar-title>
                                                                </v-flex>
                                                            </v-toolbar>
                                                            <v-layout row wrap>
                                                                <v-flex xs11>
                                                                    <v-autocomplete v-model="analisis.accion_inseguras"
                                                                        :items="accion_inseguras" label="Acciones inseguras"
                                                                        multiple chips>
                                                                    </v-autocomplete>
                                                                </v-flex>
                                                                <v-flex xs1>
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
                                                                <v-flex xs12>
                                                                    <v-expansion-panel v-for="(
                                      ac, index
                                    ) in analisis.accion_inseguras" :key="index">
                                                                        <v-expansion-panel-content>
                                                                            <template v-slot:actions>
                                                                                <v-icon color="success">add</v-icon>
                                                                            </template>
                                                                            <template v-slot:header>
                                                                                <div class="primary--text text--darken-2">
                                                                                    <strong>{{ ac }}</strong>
                                                                                </div>
                                                                            </template>
                                                                            <v-card>
                                                                                <v-card-text>
                                                                                    <v-layout row wrap>
                                                                                        <v-flex xs11>
                                                                                            <v-autocomplete v-model="accion_new.condiciones_paciente
                                                                                                "
                                                                                                :items="condiciones_paciente"
                                                                                                label="Condiciones del paciente"
                                                                                                multiple chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs1>
                                                                                            <v-tooltip top>
                                                                                                <template
                                                                                                    v-slot:activator="{ on }">
                                                                                                    <v-btn text icon
                                                                                                        color="primary" dark
                                                                                                        v-on="on" @click="
                                                                                                            addialog(
                                                                                                                'Condiciones del paciente'
                                                                                                            )
                                                                                                            ">
                                                                                                        <v-icon>add
                                                                                                        </v-icon>
                                                                                                    </v-btn>
                                                                                                </template>
                                                                                                <span>Otro</span>
                                                                                            </v-tooltip>
                                                                                        </v-flex>
                                                                                        <v-flex xs11>
                                                                                            <v-autocomplete
                                                                                                v-model="accion_new.metodos"
                                                                                                :items="metodos_procesos"
                                                                                                label="Metodos/Procesos"
                                                                                                multiple chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs1>
                                                                                            <v-tooltip top>
                                                                                                <template
                                                                                                    v-slot:activator="{ on }">
                                                                                                    <v-btn text icon
                                                                                                        color="primary" dark
                                                                                                        v-on="on" @click="
                                                                                                            addialog(
                                                                                                                'Metodos/Procesos'
                                                                                                            )
                                                                                                            ">
                                                                                                        <v-icon>add
                                                                                                        </v-icon>
                                                                                                    </v-btn>
                                                                                                </template>
                                                                                                <span>Otro</span>
                                                                                            </v-tooltip>
                                                                                        </v-flex>
                                                                                        <v-flex xs11>
                                                                                            <v-autocomplete
                                                                                                v-model="accion_new.colaborador"
                                                                                                :items="colaboradores"
                                                                                                label="Colaborador" multiple
                                                                                                chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs1>
                                                                                            <v-tooltip top>
                                                                                                <template
                                                                                                    v-slot:activator="{ on }">
                                                                                                    <v-btn text icon
                                                                                                        color="primary" dark
                                                                                                        v-on="on" @click="
                                                                                                            addialog('Colaborador')
                                                                                                            ">
                                                                                                        <v-icon>add
                                                                                                        </v-icon>
                                                                                                    </v-btn>
                                                                                                </template>
                                                                                                <span>Otro</span>
                                                                                            </v-tooltip>
                                                                                        </v-flex>
                                                                                        <v-flex xs11>
                                                                                            <v-autocomplete v-model="accion_new.equipo_trabajo
                                                                                                "
                                                                                                :items="equipo_trabajo"
                                                                                                label="Equipo de trabajo"
                                                                                                multiple chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs1>
                                                                                            <v-tooltip top>
                                                                                                <template
                                                                                                    v-slot:activator="{ on }">
                                                                                                    <v-btn text icon
                                                                                                        color="primary" dark
                                                                                                        v-on="on" @click="
                                                                                                            addialog(
                                                                                                                'Equipo de trabajo'
                                                                                                            )
                                                                                                            ">
                                                                                                        <v-icon>add
                                                                                                        </v-icon>
                                                                                                    </v-btn>
                                                                                                </template>
                                                                                                <span>Otro</span>
                                                                                            </v-tooltip>
                                                                                        </v-flex>
                                                                                        <v-flex xs11>
                                                                                            <v-autocomplete
                                                                                                v-model="accion_new.ambiente"
                                                                                                :items="ambientes"
                                                                                                label="Ambiente" multiple
                                                                                                chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs1>
                                                                                            <v-tooltip top>
                                                                                                <template
                                                                                                    v-slot:activator="{ on }">
                                                                                                    <v-btn text icon
                                                                                                        color="primary" dark
                                                                                                        v-on="on" @click="
                                                                                                            addialog('Ambiente')
                                                                                                            ">
                                                                                                        <v-icon>add
                                                                                                        </v-icon>
                                                                                                    </v-btn>
                                                                                                </template>
                                                                                                <span>Otro</span>
                                                                                            </v-tooltip>
                                                                                        </v-flex>
                                                                                        <v-flex xs11>
                                                                                            <v-autocomplete
                                                                                                v-model="accion_new.recursos"
                                                                                                :items="recursos"
                                                                                                label="Recursos/Materiales/Insumos"
                                                                                                multiple chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs1>
                                                                                            <v-tooltip top>
                                                                                                <template
                                                                                                    v-slot:activator="{ on }">
                                                                                                    <v-btn text icon
                                                                                                        color="primary" dark
                                                                                                        v-on="on" @click="
                                                                                                            addialog(
                                                                                                                'Recursos/Materiales/Insumos'
                                                                                                            )
                                                                                                            ">
                                                                                                        <v-icon>add
                                                                                                        </v-icon>
                                                                                                    </v-btn>
                                                                                                </template>
                                                                                                <span>Otro</span>
                                                                                            </v-tooltip>
                                                                                        </v-flex>
                                                                                        <v-flex xs11>
                                                                                            <v-autocomplete
                                                                                                v-model="accion_new.contexto"
                                                                                                :items="contexto_institucional"
                                                                                                label="Contexto institucional"
                                                                                                multiple chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs1>
                                                                                            <v-tooltip top>
                                                                                                <template
                                                                                                    v-slot:activator="{ on }">
                                                                                                    <v-btn text icon
                                                                                                        color="primary" dark
                                                                                                        v-on="on" @click="
                                                                                                            addialog(
                                                                                                                'Contexto institucional'
                                                                                                            )
                                                                                                            ">
                                                                                                        <v-icon>add
                                                                                                        </v-icon>
                                                                                                    </v-btn>
                                                                                                </template>
                                                                                                <span>Otro</span>
                                                                                            </v-tooltip>
                                                                                        </v-flex>
                                                                                        <v-flex xs1>
                                                                                            <v-btn color="success"
                                                                                                @click="saveAccion(ac)">
                                                                                                <span>Guardar acción</span>
                                                                                            </v-btn>
                                                                                        </v-flex>
                                                                                    </v-layout>
                                                                                </v-card-text>
                                                                            </v-card>
                                                                        </v-expansion-panel-content>
                                                                    </v-expansion-panel>
                                                                </v-flex>
                                                                <v-flex xs12 v-for="(
                                    accion, index
                                  ) in analisis.pre_analisis" :key="index">
                                                                    <v-expansion-panel v-for="(
                                      acciones, index1
                                    ) in accion.accion_inseguras" :key="index1">
                                                                        <v-expansion-panel-content>
                                                                            <template v-slot:actions>
                                                                                <v-icon color="success">done</v-icon>
                                                                            </template>
                                                                            <template v-slot:header>
                                                                                <div class="success--text text--darken-2">
                                                                                    <strong>{{ acciones.name }}</strong>
                                                                                </div>
                                                                            </template>
                                                                            <v-card>
                                                                                <v-card-text>
                                                                                    <v-layout row wrap>
                                                                                        <v-flex xs12 v-if="acciones.condiciones_paciente !==
                                                                                            null
                                                                                            ">
                                                                                            <v-autocomplete v-model="acciones.condiciones_paciente
                                                                                                " :items="acciones.condiciones_paciente
        " label="Condiciones del paciente" multiple chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs12
                                                                                            v-if="acciones.metodos !== null">
                                                                                            <v-autocomplete
                                                                                                v-model="acciones.metodos"
                                                                                                :items="acciones.metodos"
                                                                                                label="Metodos/Procesos"
                                                                                                multiple chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs12 v-if="acciones.colaborador !== null
                                                                                            ">
                                                                                            <v-autocomplete
                                                                                                v-model="acciones.colaborador"
                                                                                                :items="acciones.colaborador"
                                                                                                label="Colaborador" multiple
                                                                                                chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs12 v-if="acciones.equipo_trabajo !== null
                                                                                            ">
                                                                                            <v-autocomplete v-model="acciones.equipo_trabajo
                                                                                                "
                                                                                                :items="acciones.equipo_trabajo"
                                                                                                label="Equipo de trabajo"
                                                                                                multiple chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs12
                                                                                            v-if="acciones.ambiente !== null">
                                                                                            <v-autocomplete
                                                                                                v-model="acciones.ambiente"
                                                                                                :items="acciones.ambiente"
                                                                                                label="Ambiente" multiple
                                                                                                chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs12
                                                                                            v-if="acciones.recursos !== null">
                                                                                            <v-autocomplete
                                                                                                v-model="acciones.recursos"
                                                                                                :items="acciones.recursos"
                                                                                                label="Recursos/Materiales/Insumos"
                                                                                                multiple chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs12
                                                                                            v-if="acciones.contexto !== null">
                                                                                            <v-autocomplete
                                                                                                v-model="acciones.contexto"
                                                                                                :items="acciones.contexto"
                                                                                                label="Contexto institucional"
                                                                                                multiple chips>
                                                                                            </v-autocomplete>
                                                                                        </v-flex>
                                                                                        <v-flex xs2>
                                                                                            <v-btn color="warning"
                                                                                                @click="edititems(acciones)">
                                                                                                <span>Editar</span>
                                                                                            </v-btn>
                                                                                        </v-flex>
                                                                                        <v-flex xs2>
                                                                                            <v-btn color="error" @click="
                                                                                                deleteitems(acciones.id)
                                                                                                ">
                                                                                                <span>Eliminar</span>
                                                                                            </v-btn>
                                                                                        </v-flex>
                                                                                    </v-layout>
                                                                                </v-card-text>
                                                                            </v-card>
                                                                        </v-expansion-panel-content>
                                                                    </v-expansion-panel>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-flex>
                                                        <v-flex v-if="respuesta_inmediata">
                                                            <v-toolbar color="primary" flat dark>
                                                                <v-flex xs12 text-xs-center>
                                                                    <v-toolbar-title>Respuesta Inmediata
                                                                    </v-toolbar-title>
                                                                </v-flex>
                                                            </v-toolbar>
                                                            <v-flex xs12>
                                                                <v-textarea label="Qué fallo" v-model="analisis.que_fallo">
                                                                </v-textarea>
                                                            </v-flex>
                                                            <v-flex xs12>
                                                                <v-textarea label="Cómo/por qué falló"
                                                                    v-model="analisis.porque_fallo">
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
                                                        <v-flex v-if="algoritmOMS">
                                                            <v-toolbar color="primary" flat dark>
                                                                <v-flex xs12 text-xs-center>
                                                                    <v-toolbar-title>Algoritmo OMS</v-toolbar-title>
                                                                </v-flex>
                                                            </v-toolbar>
                                                            <!-- <v-flex xs8 class="mt-3">
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.despues_adminmedicamento"
                                                                    label="El evento se presentó después de administrar el medicamento?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.factores_explicarelevento"
                                                                    label="Existen otros factores que puedan explicar el evento (medicamento, patologías, etc.)?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.desaparecio_suspendermedicamento"
                                                                    label="El evento desapareció al disminuir o suspender el medicamento sospechoso?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.reaccion_medicamentosospechoso"
                                                                    label="El paciente ya había presentado la misma reacción al medicamento sospechoso?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.ampliar_informacionpaciente"
                                                                    label="Se puede ampliar la información del paciente relacionando con el evento?">
                                                                </v-select>
                                                            </v-flex> -->
                                                            <v-layout row wrap>
                                                                <v-flex xs6>
                                                                    <v-select :items="[
                                                                        'Definitiva',
                                                                        'Probable',
                                                                        'Posible',
                                                                        'Improbable',
                                                                        'Condicional/No clasificada',
                                                                        'No evaluable/ Inclasificable',
                                                                    ]" v-model="analisis.evaluacion_causalidad"
                                                                        label="Evaluación de causalidad">
                                                                    </v-select>
                                                                </v-flex>
                                                                <v-flex xs6>
                                                                    <v-select :items="['Serio', 'No Serio']"
                                                                        v-model="analisis.clasificacion_invima"
                                                                        label="Clasificación Invima">
                                                                    </v-select>
                                                                </v-flex>
                                                                <v-flex xs6 v-show="analisis.clasificacion_invima == 'Serio'
                                                                    ">
                                                                    <v-select :items="[
                                                                        'Produjo o prolongó hospitalización',
                                                                        'Anomalía congénita',
                                                                        'Amenaza de vida',
                                                                        'Muerte',
                                                                        'Produjo discapacidad o incapacidad permanente / condición médica importante',
                                                                    ]" v-model="analisis.seriedad" label="Seriedad">
                                                                    </v-select>
                                                                </v-flex>
                                                                <v-flex xs6 v-if="analisis.seriedad == 'Muerte'">
                                                                    <v-text-field v-model="analisis.fecha_muerte"
                                                                        label="Fecha Muerte" type="date"></v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-flex>
                                                        <v-flex v-if="amef">
                                                            <v-toolbar color="primary" flat dark>
                                                                <v-flex xs12 text-xs-center>
                                                                    <v-toolbar-title>Metodología AMEF</v-toolbar-title>
                                                                </v-flex>
                                                            </v-toolbar>
                                                            <v-flex xs12>
                                                                <v-textarea label="Elemento/Función"
                                                                    v-model="analisis.elemento_funcion">
                                                                </v-textarea>
                                                            </v-flex>
                                                            <v-flex xs12>
                                                                <v-textarea label="Modo de fallo"
                                                                    v-model="analisis.modo_fallo">
                                                                </v-textarea>
                                                            </v-flex>
                                                            <v-flex xs12>
                                                                <v-textarea label="Efecto" v-model="analisis.efecto">
                                                                </v-textarea>
                                                            </v-flex>
                                                            <v-layout row wrap>
                                                                <v-flex xs4>
                                                                    <v-text-field label="NPR" v-model="analisis.npr"
                                                                        type="number">
                                                                    </v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-flex xs12>
                                                                <v-textarea label="Acciones propuestas"
                                                                    v-model="analisis.acciones_propuestas">
                                                                </v-textarea>
                                                            </v-flex>
                                                        </v-flex>
                                                        <v-flex v-if="algoritmoWHO">
                                                            <v-toolbar color="primary" flat dark>
                                                                <v-flex xs12 text-xs-center>
                                                                    <v-toolbar-title>WHO AEFI (EPAV)</v-toolbar-title>
                                                                </v-flex>
                                                            </v-toolbar>
                                                            <v-flex xs11 class="mt-3">
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.causas_esavi"
                                                                    label="¿Hay evidencia determinante que indique que otras causas originaron el ESAVI?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.asociacion_esavi"
                                                                    label="¿Hay una asociación descrita en la literatura, entre el ESAVI y la vacuna o la vacunación?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.ventana_mayoriesgo"
                                                                    label="¿El evento estuvo dentro de la ventana de tiempo de mayor riesgo?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.evidencia_asociacioncausal"
                                                                    label="¿Hay evidencia en contra de una asociación causal?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.factores_esavi"
                                                                    label="¿Existen otros factores que pudieron haber impactado en el desarrollo del ESAVI?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-layout row wrap>
                                                                <v-flex xs6>
                                                                    <v-select :items="[
                                                                        'Reacción relacionada con la vacuna',
                                                                        'Reacción relacionada con defectos en la calidad de la vacuna',
                                                                        'Reacción relacionada con un error en la inmunización',
                                                                        'Reacción relacionada con la ansiedad generalizada por la inmunización',
                                                                        'La relación temporal es coherente, pero la evidencia no es concluyente',
                                                                        'Tendencias contradictorias de coherencia e inconsistencia para una sociación',
                                                                        'Coincidente (condiciones subyacentes o emergentes)',
                                                                        'Inclasificable',
                                                                    ]" v-model="analisis.evaluacion_causalidad"
                                                                        label="Evaluación de causalidad">
                                                                    </v-select>
                                                                </v-flex>
                                                                <v-flex xs6>
                                                                    <v-select :items="['Serio', 'No Serio']"
                                                                        v-model="analisis.clasificacion_invima"
                                                                        label="Clasificación Invima">
                                                                    </v-select>
                                                                </v-flex>
                                                                <v-flex xs6 v-show="analisis.clasificacion_invima == 'Serio'
                                                                    ">
                                                                    <v-select :items="[
                                                                        'Produjo o prolongó hospitalización',
                                                                        'Anomalía congénita',
                                                                        'Amenaza de vida',
                                                                        'Muerte',
                                                                        'Produjo discapacidad o incapacidad permanente / condición médica importante',
                                                                    ]" v-model="analisis.seriedad" label="Seriedad">
                                                                    </v-select>
                                                                </v-flex>
                                                                <v-flex xs6 v-if="analisis.seriedad == 'Muerte'">
                                                                    <v-text-field v-model="analisis.fecha_muerte"
                                                                        label="Fecha Muerte" type="date"></v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-flex>
                                                        <v-flex v-if="algoritmoVACA">
                                                            <v-toolbar color="primary" flat dark>
                                                                <v-flex xs12 text-xs-center>
                                                                    <v-toolbar-title>FT - VACA-DELASSALAS
                                                                    </v-toolbar-title>
                                                                </v-flex>
                                                            </v-toolbar>
                                                            <v-flex xs11 class="mt-3">
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.farmaco_cinetica"
                                                                    label="¿El FT se refiere a un fármaco de cinética compleja?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.condiciones_farmacocinetica"
                                                                    label="¿El paciente presenta condiciones clínicas que alteren la farmacocinética?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.prescribio_manerainadecuada"
                                                                    label="¿El medicamento se prescribió de manera inadecuada">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas" v-model="analisis.medicamento_manerainadecuada
                                                                    "
                                                                    label="¿El medicamento se usó de manera inadecuada?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.medicamento_entrenamiento"
                                                                    label="¿La administracion del medicamento requiere un entrenamiento especial del paciente?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.potenciales_interacciones"
                                                                    label="¿Existen potenciales interacciones?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas" v-model="analisis.notificacion_refieremedicamento
                                                                    "
                                                                    label="¿La notificación de FT se refiere a un medicamento genérico o una marca comercial?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.problema_biofarmaceutico"
                                                                    label="¿Existe algún problema biofarmacéutico estudiado?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.deficiencias_sistemas"
                                                                    label="¿Existen deficiencias en los sistemas de almacenamiento del medicamento?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs11>
                                                                <v-select :items="respuestas"
                                                                    v-model="analisis.factores_asociados"
                                                                    label="¿Existen otros factores asociados asociados que pudieran explicar el FT?">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-layout row wrap>
                                                                <v-flex xs6>
                                                                    <v-select :items="[
                                                                        'Posiblemente asociado al uso inadecuado del medicamento',
                                                                        'Notificación posiblemente inducida',
                                                                        'Posiblemente asociado a un problema biofarmacéutico (calidad)',
                                                                        'Posiblemente asociado a respuesta idiosincrática u otras razones no establecidas',
                                                                        'No se cuenta con información suficiente para el análisis',
                                                                    ]" v-model="analisis.evaluacion_causalidad"
                                                                        label="Evaluación de causalidad">
                                                                    </v-select>
                                                                </v-flex>
                                                                <v-flex xs6>
                                                                    <v-select :items="['Serio', 'No Serio']"
                                                                        v-model="analisis.clasificacion_invima"
                                                                        label="Clasificación Invima">
                                                                    </v-select>
                                                                </v-flex>
                                                                <v-flex xs6 v-show="analisis.clasificacion_invima == 'Serio'
                                                                    ">
                                                                    <v-select :items="[
                                                                        'Produjo o prolongó hospitalización',
                                                                        'Anomalía congénita',
                                                                        'Amenaza de vida',
                                                                        'Muerte',
                                                                        'Produjo discapacidad o incapacidad permanente / condición médica importante',
                                                                    ]" v-model="analisis.seriedad" label="Seriedad">
                                                                    </v-select>
                                                                </v-flex>
                                                                <v-flex xs6 v-if="analisis.seriedad == 'Muerte'">
                                                                    <v-text-field v-model="analisis.fecha_muerte"
                                                                        label="Fecha Muerte" type="date"></v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-flex>

                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>Consecuencias y acciones de mejora
                                                                </v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-flex xs12>
                                                            <v-textarea v-model="analisis.descripcion_consecuencias"
                                                                label="Descripción consecuencias">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs6>
                                                            <v-select :items="[
                                                                'Recuperado / Resuelto sin secuelas',
                                                                'Recuperado / Resuelto con secuelas',
                                                                'Recuperando / Resolviendo',
                                                                'No recuperado / No resuelto',
                                                                'Fatal',
                                                                'Desconocido',
                                                                'Muerte',
                                                            ]" v-model="analisis.desenlace_evento"
                                                                label="Desenlace del evento">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs6>
                                                            <v-select
                                                                :items="analisis.metodologia === 'Protocolo de Londres' ? clasificaciones : clasificaciones.filter(item => item !== 'Centinela')"
                                                                v-model="analisis.clasificacion"
                                                                label="Clasificación del suceso"></v-select>
                                                            <span v-if="analisis.clasificacion === 'Centinela'"
                                                                class="requiere-plan-text">Es obligatorio formular un plan
                                                                de mejora.</span>
                                                        </v-flex>

                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>Plan de mejora
                                                                </v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-flex xs11>
                                                            <v-autocomplete v-model="analisis.accion_mejora"
                                                                :items="accion_mejoras" label="Acciones de mejora" multiple
                                                                chips>
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-tooltip top>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn text icon color="success" dark v-on="on"
                                                                        @click="dialogAccionesMejora = true">
                                                                        <v-icon>add</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Agregar</span>
                                                            </v-tooltip>
                                                        </v-flex>

                                                        <v-flex xs12>
                                                            <v-expansion-panel
                                                                v-for="(acm, index) in analisis.accion_mejora" :key="index">
                                                                <v-expansion-panel-content>
                                                                    <template v-slot:actions>
                                                                        <v-icon color="success">add</v-icon>
                                                                    </template>
                                                                    <template v-slot:header>
                                                                        <div class="primary--text text--darken-2">
                                                                            <strong>{{ acm }}</strong>
                                                                        </div>
                                                                    </template>
                                                                    <v-card>
                                                                        <v-card-text>
                                                                            <v-layout row wrap>
                                                                                <v-flex xs12>
                                                                                    <v-autocomplete :items="user_activos"
                                                                                        label="Responsable"
                                                                                        item-text="nombre" item-value="name"
                                                                                        multiple
                                                                                        v-model="acc_mejora.responsables" />
                                                                                </v-flex>
                                                                                <v-flex xs4 px-1>
                                                                                    <v-text-field
                                                                                        label="Fecha de cumplimiento"
                                                                                        type="date" v-model="acc_mejora.fecha_cumplimiento
                                                                                            ">
                                                                                    </v-text-field>
                                                                                </v-flex>
                                                                                <v-flex xs4 px-1>
                                                                                    <v-text-field label="Fecha seguimiento"
                                                                                        v-model="acc_mejora.fecha_seguimiento
                                                                                            " type="date">
                                                                                    </v-text-field>
                                                                                </v-flex>
                                                                                <v-flex xs4 px-1>
                                                                                    <v-select
                                                                                        :items="['Pendiente', 'Cumplido']"
                                                                                        v-model="acc_mejora.estado"
                                                                                        label="Estado">
                                                                                    </v-select>
                                                                                </v-flex>
                                                                                <v-flex xs1>
                                                                                    <v-btn color="success"
                                                                                        @click="saveAccionMejora(acm)">
                                                                                        <span>Guardar acción mejora</span>
                                                                                    </v-btn>
                                                                                </v-flex>
                                                                            </v-layout>
                                                                        </v-card-text>
                                                                    </v-card>
                                                                </v-expansion-panel-content>
                                                            </v-expansion-panel>
                                                        </v-flex>

                                                        <v-flex xs12 v-for="(accionm, index2) in analisis.pre_analisis"
                                                            :key="index2">
                                                            <v-expansion-panel v-for="(
                                  accionesmejora, index3
                                ) in accionm.accion_mejoras" :key="index3">
                                                                <v-expansion-panel-content>
                                                                    <template v-slot:actions>
                                                                        <v-icon color="success">done</v-icon>
                                                                    </template>
                                                                    <template v-slot:header>
                                                                        <div class="success--text text--darken-2">
                                                                            <strong>{{
                                                                                accionesmejora.nombre
                                                                            }}</strong>
                                                                        </div>
                                                                    </template>
                                                                    <v-card>
                                                                        <v-card-text>
                                                                            <v-layout row wrap>
                                                                                <v-flex xs12 px-1>
                                                                                    <v-text-field label="titulo" v-model="accionesmejora.nombre
                                                                                            ">
                                                                                    </v-text-field>
                                                                                </v-flex>
                                                                                <v-flex xs12>
                                                                                    <v-autocomplete :items="user_activos"
                                                                                        label="Responsable"
                                                                                        item-text="nombre" item-value="name"
                                                                                        multiple v-model="accionesmejora.responsables
                                                                                            " />
                                                                                </v-flex>
                                                                                <v-flex xs4 px-1>
                                                                                    <v-text-field
                                                                                        label="Fecha de cumplimiento"
                                                                                        type="date" v-model="accionesmejora.fecha_cumplimiento
                                                                                            ">
                                                                                    </v-text-field>
                                                                                </v-flex>
                                                                                <v-flex xs4 px-1>
                                                                                    <v-text-field label="Fecha seguimiento"
                                                                                        v-model="accionesmejora.fecha_seguimiento
                                                                                            " type="date">
                                                                                    </v-text-field>
                                                                                </v-flex>
                                                                                <v-flex xs4 px-1>
                                                                                    <v-select
                                                                                        :items="['Pendiente', 'Cumplido']"
                                                                                        v-model="accionesmejora.estado"
                                                                                        label="Estado">
                                                                                    </v-select>
                                                                                </v-flex>
                                                                                <v-flex xs12>
                                                                                    <v-textarea
                                                                                        v-model="accionesmejora.observacion"
                                                                                        label="Observaciones" readonly>
                                                                                    </v-textarea>
                                                                                </v-flex>
                                                                                <v-flex xs12>
                                                                                    <v-btn v-for="(
                                              adjuntoR, index
                                            ) in accionesmejora.adjuntos" :key="index" :disabled="search_adjunto">
                                                                                        <a @click="
                                                                                            consultarAdjunto(adjuntoR.ruta)
                                                                                            ">
                                                                                            <v-icon color="dark">
                                                                                                mdi-cloud-upload
                                                                                            </v-icon>Adjunto
                                                                                            {{ index + 1 }}
                                                                                        </a>
                                                                                    </v-btn>
                                                                                    <v-divider class="my-4"></v-divider>
                                                                                </v-flex>
                                                                                <v-flex xs2>
                                                                                    <v-btn color="warning" @click="
                                                                                        editAccionesMejora(accionesmejora)
                                                                                        ">
                                                                                        <span>Editar Plan de Mejora</span>
                                                                                    </v-btn>
                                                                                </v-flex>
                                                                                <v-flex xs2
                                                                                    v-if="analisis.estado == 'Analizado'">
                                                                                    <v-btn color="error" @click="
                                                                                        deleteAccionesMejora(
                                                                                            accionesmejora.id
                                                                                        )
                                                                                        ">
                                                                                        <span>Eliminar</span>
                                                                                    </v-btn>
                                                                                </v-flex>
                                                                            </v-layout>
                                                                        </v-card-text>
                                                                    </v-card>
                                                                </v-expansion-panel-content>
                                                            </v-expansion-panel>
                                                        </v-flex>

                                                        <v-flex xs6 v-if="analisis.nombreEvento == 'Farmacovigilancia' ||
                                                            analisis.nombreEvento == 'Reactivovigilancia' ||
                                                            analisis.nombreEvento == 'Tecnovigilancia' ||
                                                            analisis.nombreEvento ==
                                                            'Infecciones asociadas al cuidado de la salud (IAAS)' ||
                                                            analisis.nombreEvento ==
                                                            'Administración de hemoderivados'
                                                            ">
                                                            <v-select :items="['SI', 'NO']"
                                                                v-model="analisis.requiere_reporte_ente"
                                                                label="Requiere reportar al ente?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs6 v-if="analisis.nombreEvento == 'Farmacovigilancia'
                                                            ">
                                                            <v-select :items="['SI', 'NO']"
                                                                v-model="analisis.requiere_marca_especifica"
                                                                label="Requiere marca especifica?">
                                                            </v-select>
                                                        </v-flex>

                                                        <!-- <v-flex xs12>
                                                            <input id="adjuntos" multiple ref="adjuntos"
                                                                type="file" />
                                                        </v-flex> -->
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                        <v-toolbar color="primary" flat dark>
                                            <v-flex xs12 text-xs-center>
                                                <v-toolbar-title>Acciones</v-toolbar-title>
                                            </v-flex>
                                        </v-toolbar>
                                        <v-flex xs4 class="mt-4">
                                            <v-select label="Acción" :items="estadoAcciones" v-model="form.acciones">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs8 />
                                        <v-flex xs4 v-show="form.acciones == 'Asignar'">
                                            <v-select label="Priorización" :items="[
                                                'No prioritario',
                                                'Prioritario',
                                                'Muy prioritario',
                                                'Urgente',
                                            ]" v-model="form.priorizacion">
                                            </v-select>
                                        </v-flex>
                                        <!-- <v-flex xs8 /> -->
                                        <!-- <v-flex xs4 v-show="form.acciones == 'Asignar'">
                                            <v-select label="Probabilidad/Frecuencia" type="number" min="1"
                                                v-model="probabilidad" :items="['1', '2', '3']">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs4 v-show="form.acciones == 'Asignar'">
                                            <v-select label="Impacto/Severidad" type="number" min="1" v-model="impacto"
                                                :items="['1', '2', '3']">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs4 v-show="form.acciones == 'Asignar'">
                                            <v-select label="Previsibilidad" type="number" min="1"
                                                v-model="previsibilidad" :items="['1','3']">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs4 v-show="form.acciones == 'Asignar'">
                                            <v-text-field readonly label="Puntaje de Priorización" type="number"
                                                v-model="form.puntajeTotal">
                                            </v-text-field>
                                        </v-flex> -->
                                        <v-flex xs2 v-show="form.acciones == 'Asignar' || form.acciones == 'Anular'">
                                            <v-select color v-model="form.voluntariedad" :items="['SI', 'NO']"
                                                label="Voluntariedad"></v-select>
                                        </v-flex>
                                        <v-flex xs4 v-show="form.acciones == 'Asignar'">
                                            <v-autocomplete label="Identificación del riesgo"
                                                v-model="form.identificacion_riesgo"
                                                :items="['Riesgo individual', 'Riesgo poblacional']">
                                            </v-autocomplete>
                                        </v-flex>
                                        <!-- <v-flex xs4 v-show="form.acciones == 'Asignar' && form.nivelPriorizacion != ''">
                                            <span>Nivel de Priorización</span>
                                            <v-chip :class="ColorTd(form.nivelPriorizacion)">
                                                {{ form.nivelPriorizacion }}
                                            </v-chip>
                                        </v-flex> -->
                                        <v-flex xs12 v-show="form.acciones == 'Asignar' ||
                                            form.acciones == 'Re Asignar'
                                            ">
                                            <v-autocomplete :items="user_activos" label="Usuario" item-text="nombre"
                                                item-value="id" multiple v-model="form.user" />
                                        </v-flex>
                                        <v-flex xs12 v-show="form.acciones == 'Anular'">
                                            <v-autocomplete v-model="form.causamotivo" :items="motivos" label="Motivo"
                                                @input="form.motivo = ''">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 v-show="form.acciones == 'Anular' && form.causamotivo == 'Otros'
                                            ">
                                            <v-textarea label="Cual ?" v-model="form.motivo">
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs6 v-show="form.acciones == 'Anular' &&
                                            (form.causamotivo == 'Otros' ||
                                                form.causamotivo ==
                                                'Suceso de seguridad del paciente que no amerita análisis' ||
                                                form.causamotivo ==
                                                'Error de medicación - Tipo A: Circunstancias o incidentes con capacidad de causar error' ||
                                                form.causamotivo ==
                                                'Error de medicación - Tipo B: El error se produjo, pero no alcanzó al paciente')
                                            ">
                                            <v-select :items="[
                                                'Evento adverso prevenible',
                                                'Evento adverso no prevenible',
                                                'Incidente',
                                                'Complicación',
                                                'Indicio de atención insegura',
                                            ]" v-model="form.clasificacion" label="Clasificación del suceso">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs6 v-show="showSeveridad">
                                            <v-select v-model="analisis.severidad_evento"
                                                :items="['Leve', 'Moderado', 'Severo']" label="Severidad del evento">
                                            </v-select>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="success" @click="accion()">
                                    <span>Guardar</span>
                                </v-btn>
                                <v-btn color="error" @click="salirAnalisis()">
                                    <span>Salir</span>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-card>
            </v-tab-item>

            <v-tab-item :value="'tab-2'">
                <v-card flat>
                    <v-card>
                        <v-card-title>
                            <VAutocomplete :items="alleventos" v-model="eventocerrado" label="Área" item-value="id"
                                item-text="nombre" @input="getcerrados()" :disabled="loading" single-line hide-details />
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search2" append-icon="search" label="Buscar..." single-line hide-details>
                            </v-text-field>
                        </v-card-title>
                        <v-layout row wrap>
                            <v-flex xs3>
                                <v-menu ref="menu1" v-model="menu1" :close-on-content-click="false" :nudge-right="40" lazy
                                    transition="scale-transition" offset-y full-width min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field v-model="dateDesde" label="Fecha Desde" prepend-icon="event" readonly
                                            v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker color="primary" locale="es" v-model="dateDesde" no-title
                                        @input="menu1 = false">
                                    </v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex xs3>
                                <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false" :nudge-right="40" lazy
                                    transition="scale-transition" offset-y full-width min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field v-model="dateHasta" label="Fecha Hasta" prepend-icon="event" readonly
                                            v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker color="primary" locale="es" v-model="dateHasta" no-title
                                        @input="menu2 = false">
                                    </v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn text icon color="error" dark v-on="on">
                                        <v-icon @click="(eventocerrado = ''), limpiar()">clear</v-icon>
                                    </v-btn>
                                </template>
                                <span>Limpiar</span>
                            </v-tooltip>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn text icon color="primary" dark v-on="on">
                                        <v-icon @click="(eventocerrado = ''), getcerrados()">mdi-account-search</v-icon>
                                    </v-btn>
                                </template>
                                <span>Buscar</span>
                            </v-tooltip>
                        </v-layout>
                        <template>
                            <v-data-table :loading="loading" :headers="headers" :items="cerrados" :search="search2"
                                class="elevation-1" color="primary">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.sede_ocurrencia }}</td>
                                    <td>{{ props.item.fecha_ocurrencia }}</td>
                                    <td>{{ props.item.Num_Doc }}</td>
                                    <td>{{ props.item.nombre_completo }}</td>
                                    <td>{{ props.item.nombreEvento }}</td>
                                    <td>{{ props.item.entidad }}</td>
                                    <td>{{ props.item.estado }}</td>
                                    <td>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn text icon color="primary" dark v-on="on"
                                                    @click="openHistorial(props.item)">
                                                    <v-icon>search</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Historial</span>
                                        </v-tooltip>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn text icon color="success" dark v-on="on"
                                                    @click="imprimirAnalisis(props.item.id)" :disabled="loading">
                                                    <v-icon>mdi-file-pdf-box</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Imprimir</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                            </v-data-table>
                        </template>
                    </v-card>

                    <v-dialog v-model="dialogHistorial" max-width="1000px">
                        <v-card max-height="100%">
                            <v-toolbar color="primary" flat dark>
                                <v-flex xs12 text-xs-center>
                                    <v-toolbar-title>Historial investigación y analisis</v-toolbar-title>
                                </v-flex>
                            </v-toolbar>
                            <v-divider></v-divider>
                            <v-card-text>
                                <v-layout row wrap>
                                    <v-flex xs3 px-1 v-show="analisis.paciente_id">
                                        <v-text-field v-model="analisis.nombre_completo" readonly
                                            label="Nombre y apellidos">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 px-1 v-show="analisis.Num_Doc">
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
                                        <v-autocomplete v-model="eventoId" :items="alleventos" class="search-field"
                                            item-text="nombre" item-value="id" label="Nombre del suceso"
                                            :placeholder="analisis.nombreEvento" @input="getclasificacion()" readonly>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs6 px-1>
                                        <v-autocomplete v-model="clasificacionId" :items="allclasificacion"
                                            class="search-field" item-text="nombre" item-value="id"
                                            label="Clasificación área" :placeholder="analisis.nombreClasificacion"
                                            @input="getTipoevento()" readonly>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs5 px-1>
                                        <v-autocomplete v-model="tipoeventoId" :items="alltipoeventos" class="search-field"
                                            item-text="nombre" item-value="id" label="Tipo de evento"
                                            :placeholder="analisis.nombreTipoevento" readonly>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs6 px-1 v-show="analisis.otro_evento">
                                        <v-text-field v-model="analisis.otro_evento" readonly
                                            label="Otro (Nombre del suceso)">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6 px-1>
                                        <v-text-field v-model="analisis.sede_ocurrencia" readonly label="Sede ocurrencia">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6 px-1 v-show="analisis.servicio_ocurrencia">
                                        <v-autocomplete :items="socurrencias" v-model="analisis.servicio_ocurrencia"
                                            label="Servicio de ocurrencia" class="search-field"
                                            :placeholder="analisis.servicio_ocurrencia" readonly>
                                        </v-autocomplete>
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
                                        <v-text-field v-model="analisis.fecha_ocurrencia" readonly
                                            label="Fecha de ocurrencia">
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
                                    <v-flex xs12 v-show="analisis.relacion == 'Dispositivos' ||
                                        analisis.relacion == 'Ambos'
                                        ">
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
                                    <v-flex xs12 v-show="analisis.relacion == 'Equipo biomédico' ||
                                        analisis.relacion == 'Ambos'
                                        ">
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
                                        <v-textarea readonly label="Acciones que se tomaron"
                                            v-model="analisis.accion_tomada">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-btn v-for="(adjuntoR, index) in analisis.adjuntos" :key="index"
                                            :disabled="search_adjunto">
                                            <a @click="consultarAdjunto(adjuntoR.ruta)">
                                                <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                {{ index + 1 }}
                                            </a>
                                        </v-btn>
                                        <v-divider class="my-4"></v-divider>
                                    </v-flex>
                                    <v-flex xs12>
                                        <span><strong>Reportante:</strong> {{ analisis.name }}
                                            {{ analisis.apellido }}&nbsp;</span>
                                        <span><strong>Email:</strong> {{ analisis.email }}</span>
                                        <v-divider class="my-4"></v-divider>
                                    </v-flex>

                                    <v-card v-if="analisis.relacion">
                                        <v-card-text>
                                            <v-container grid-list-md>
                                                <v-layout row wrap>
                                                    <v-toolbar color="primary" flat dark>
                                                        <v-flex xs12 text-xs-center>
                                                            <v-toolbar-title>Analisis</v-toolbar-title>
                                                        </v-flex>
                                                    </v-toolbar>
                                                    <v-flex xs3 px-1>
                                                        <v-spacer class="mt-3"></v-spacer>
                                                        <v-text-field label="Fecha del analisis" readonly
                                                            v-model="analisis.fecha_analisis" type="date">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea label="Cronología del evento" readonly
                                                            v-model="analisis.cronologia">
                                                        </v-textarea>
                                                    </v-flex>
                                                    <v-flex xs12 px-1>
                                                        <v-textarea label="Consecuencias del evento" readonly
                                                            v-model="analisis.consecuencias">
                                                        </v-textarea>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea label="Acciones inseguras detectadas"
                                                            v-model="analisis.acciones_inseguras" readonly>
                                                        </v-textarea>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-select :items="clasificaciones" v-model="analisis.clasificacion"
                                                            readonly label="Clasificación del suceso">
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="analisis.clasificacion &&
                                                        analisis.clasificacion != 'Complicación' &&
                                                        analisis.clasificacion != 'Incidente'
                                                        ">
                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>Protocolo de londres</v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-flex xs12>
                                                            <v-textarea label="Paciente" readonly
                                                                v-model="analisis.paciente">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Tarea y tecnologia" readonly
                                                                v-model="analisis.tarea_tecnologia">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Individuo" readonly
                                                                v-model="analisis.individuo">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Equipo de trabajo" readonly
                                                                v-model="analisis.equipo_trabajo">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Ambiente" readonly
                                                                v-model="analisis.ambiente">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Organización y gerencia" readonly
                                                                v-model="analisis.organizacion">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Contexto institucional" readonly
                                                                v-model="analisis.contexto">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>Plan de mejoramiento</v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-flex xs12>
                                                            <v-textarea label="Acción de mejora" readonly
                                                                v-model="analisis.accion_mejora">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-text-field v-model="analisis.responsable" readonly
                                                                label="Responsable">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-layout row wrap>
                                                            <v-flex xs3 px-1>
                                                                <v-text-field label="Fecha de inicio" readonly
                                                                    v-model="analisis.fecha_inicio" type="date">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3 px-1>
                                                                <v-text-field label="Fecha finalización"
                                                                    v-model="analisis.fecha_finalizacion" readonly
                                                                    type="date">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 px-1>
                                                                <v-text-field label="Porcentaje de mejora"
                                                                    v-model="analisis.porcentaje_mejora" readonly
                                                                    type="number">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 px-1>
                                                                <v-text-field label="Fecha de seguimiento"
                                                                    v-model="analisis.fecha_seguimiento" readonly
                                                                    type="date">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 px-1>
                                                                <v-text-field label="Porcentaje de cumplimiento"
                                                                    v-model="analisis.porcentaje_cumplimiento" readonly
                                                                    type="number">
                                                                </v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="analisis.clasificacion &&
                                                        analisis.clasificacion == 'Incidente'
                                                        ">
                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>Metodología AMEF</v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-flex xs12>
                                                            <v-textarea label="Elemento/Función" readonly
                                                                v-model="analisis.elemento_funcion">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Modo de fallo" readonly
                                                                v-model="analisis.modo_fallo">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Efecto" readonly v-model="analisis.efecto">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-layout row wrap>
                                                            <v-flex xs12 px-1>
                                                                <v-text-field label="NPR" readonly v-model="analisis.npr"
                                                                    type="number">
                                                                </v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                        <v-flex xs12>
                                                            <v-textarea label="Acciones propuestas" readonly
                                                                v-model="analisis.acciones_propuestas">
                                                            </v-textarea>
                                                        </v-flex>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>

                                    <v-card v-else max-height="100%">
                                        <v-card-text>
                                            <v-container grid-list-md>
                                                <v-layout row wrap>
                                                    <v-toolbar color="primary" flat dark>
                                                        <v-flex xs12 text-xs-center>
                                                            <v-toolbar-title>Analisis Riesgo<v-chip :class="colorChip(analisis.nivel_priorizacion)
                                                                ">
                                                                    {{ analisis.nivel_priorizacion }}
                                                                    <v-icon right>star</v-icon>
                                                                </v-chip>
                                                            </v-toolbar-title>
                                                        </v-flex>
                                                    </v-toolbar>
                                                    <v-flex xs3 px-1>
                                                        <v-spacer class="mt-3"></v-spacer>
                                                        <v-text-field label="Fecha del analisis"
                                                            v-model="analisis.fecha_analisis" type="date" readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea label="Cronología del suceso"
                                                            v-model="analisis.cronologia" readonly>
                                                        </v-textarea>
                                                    </v-flex>
                                                    <v-flex xs12 v-if="analisis.eventoId == 109">
                                                        <v-textarea label="Aciones inseguras"
                                                            v-model="analisis.acciones_inseguras" readonly>
                                                        </v-textarea>
                                                    </v-flex>
                                                    <v-flex xs12 v-if="analisis.eventoId == 109">
                                                        <v-select label="Metodologia de Análisis" @input="
                                                            metodologia('select', analisis.metodologia)
                                                            " :items="[
        'Algoritmo OMS',
        'Metodología AMEF',
        'WHO AEFI (EPAV)',
        'FT - VACA-DELASSALAS',
    ]" v-model="analisis.metodologia" readonly>
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs12 v-if="analisis.nivel_priorizacion != 'ALTO' &&
                                                        analisis.eventoId != 109
                                                        ">4
                                                        <v-select label="Metodologia de Analisis" @input="
                                                            condicionCentinela('select', analisis.metodologia)
                                                            " :items="[
        'Protocolo de Londres',
        'Respuesta Inmediata',
    ]" v-model="analisis.metodologia" >
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs6 v-if="(protocolo_londres || respuesta_inmediata) &&
                                                        analisis.nivel_priorizacion != 'ALTO'
                                                        " />
                                                    <v-flex v-if="protocolo_londres">
                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>Protocolo de londres
                                                                </v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-layout row wrap>
                                                            <v-flex xs11>
                                                                <v-autocomplete v-model="analisis.accion_inseguras"
                                                                    :items="accion_inseguras" label="Acciones inseguras"
                                                                    multiple chips readonly>
                                                                </v-autocomplete>
                                                            </v-flex>
                                                            <v-flex xs12>
                                                                <v-expansion-panel v-for="(
                                    ac, index
                                  ) in analisis.accion_inseguras" :key="index">
                                                                    <v-expansion-panel-content>
                                                                        <template v-slot:actions>
                                                                            <v-icon color="success">add</v-icon>
                                                                        </template>
                                                                        <template v-slot:header>
                                                                            <div class="primary--text text--darken-2">
                                                                                <strong>{{ ac }}</strong>
                                                                            </div>
                                                                        </template>
                                                                        <v-card>
                                                                            <v-card-text>
                                                                                <v-layout row wrap>
                                                                                    <v-flex xs11>
                                                                                        <v-autocomplete v-model="accion_new.condiciones_paciente
                                                                                            "
                                                                                            :items="condiciones_paciente"
                                                                                            label="Condiciones del paciente"
                                                                                            multiple chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs11>
                                                                                        <v-autocomplete
                                                                                            v-model="accion_new.metodos"
                                                                                            :items="metodos_procesos"
                                                                                            label="Metodos/Procesos"
                                                                                            multiple chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs11>
                                                                                        <v-autocomplete
                                                                                            v-model="accion_new.colaborador"
                                                                                            :items="colaboradores"
                                                                                            label="Colaborador" multiple
                                                                                            chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs11>
                                                                                        <v-autocomplete v-model="accion_new.equipo_trabajo
                                                                                            " :items="equipo_trabajo"
                                                                                            label="Equipo de trabajo"
                                                                                            multiple chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs11>
                                                                                        <v-autocomplete
                                                                                            v-model="accion_new.ambiente"
                                                                                            :items="ambientes"
                                                                                            label="Ambiente" multiple chips
                                                                                            readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs11>
                                                                                        <v-autocomplete
                                                                                            v-model="accion_new.recursos"
                                                                                            :items="recursos"
                                                                                            label="Recursos/Materiales/Insumos"
                                                                                            multiple chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs11>
                                                                                        <v-autocomplete
                                                                                            v-model="accion_new.contexto"
                                                                                            :items="contexto_institucional"
                                                                                            label="Contexto institucional"
                                                                                            multiple chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                </v-layout>
                                                                            </v-card-text>
                                                                        </v-card>
                                                                    </v-expansion-panel-content>
                                                                </v-expansion-panel>
                                                            </v-flex>
                                                            <v-flex xs12 v-for="(accion, index) in analisis.pre_analisis"
                                                                :key="index">
                                                                <v-expansion-panel v-for="(
                                    acciones, index1
                                  ) in accion.accion_inseguras" :key="index1">
                                                                    <v-expansion-panel-content>
                                                                        <template v-slot:actions>
                                                                            <v-icon color="success">done</v-icon>
                                                                        </template>
                                                                        <template v-slot:header>
                                                                            <div class="success--text text--darken-2">
                                                                                <strong>{{ acciones.name }}</strong>
                                                                            </div>
                                                                        </template>
                                                                        <v-card>
                                                                            <v-card-text>
                                                                                <v-layout row wrap>
                                                                                    <v-flex xs12 v-if="acciones.condiciones_paciente !==
                                                                                        null
                                                                                        ">
                                                                                        <v-autocomplete v-model="acciones.condiciones_paciente
                                                                                            " :items="acciones.condiciones_paciente
        " label="Condiciones del paciente" multiple chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs12
                                                                                        v-if="acciones.metodos !== null">
                                                                                        <v-autocomplete
                                                                                            v-model="acciones.metodos"
                                                                                            :items="acciones.metodos"
                                                                                            label="Metodos/Procesos"
                                                                                            multiple chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs12
                                                                                        v-if="acciones.colaborador !== null">
                                                                                        <v-autocomplete
                                                                                            v-model="acciones.colaborador"
                                                                                            :items="acciones.colaborador"
                                                                                            label="Colaborador" multiple
                                                                                            chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs12 v-if="acciones.equipo_trabajo !== null
                                                                                        ">
                                                                                        <v-autocomplete
                                                                                            v-model="acciones.equipo_trabajo"
                                                                                            :items="acciones.equipo_trabajo"
                                                                                            label="Equipo de trabajo"
                                                                                            multiple chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs12
                                                                                        v-if="acciones.ambiente !== null">
                                                                                        <v-autocomplete
                                                                                            v-model="acciones.ambiente"
                                                                                            :items="acciones.ambiente"
                                                                                            label="Ambiente" multiple chips
                                                                                            readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs12
                                                                                        v-if="acciones.recursos !== null">
                                                                                        <v-autocomplete
                                                                                            v-model="acciones.recursos"
                                                                                            :items="acciones.recursos"
                                                                                            label="Recursos/Materiales/Insumos"
                                                                                            multiple chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                    <v-flex xs12
                                                                                        v-if="acciones.contexto !== null">
                                                                                        <v-autocomplete
                                                                                            v-model="acciones.contexto"
                                                                                            :items="acciones.contexto"
                                                                                            label="Contexto institucional"
                                                                                            multiple chips readonly>
                                                                                        </v-autocomplete>
                                                                                    </v-flex>
                                                                                </v-layout>
                                                                            </v-card-text>
                                                                        </v-card>
                                                                    </v-expansion-panel-content>
                                                                </v-expansion-panel>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-flex>
                                                    <v-flex v-if="respuesta_inmediata">
                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>Respuesta Inmediata
                                                                </v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-flex xs12>
                                                            <v-textarea label="Qué fallo" v-model="analisis.que_fallo"
                                                                readonly>
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Cómo/por qué falló"
                                                                v-model="analisis.porque_fallo" readonly>
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Qué causó" v-model="analisis.que_causo"
                                                                readonly>
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Qué plan de acción se implementó"
                                                                v-model="analisis.accion_implemento" readonly>
                                                            </v-textarea>
                                                        </v-flex>
                                                    </v-flex>
                                                    <v-flex v-if="algoritmOMS">
                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>Algoritmo OMS</v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-layout row wrap>
                                                            <v-flex xs6>
                                                                <v-select :items="[
                                                                    'Definitiva',
                                                                    'Probable',
                                                                    'Posible',
                                                                    'Improbable',
                                                                    'Condicional/No clasificada',
                                                                    'No evaluable/ Inclasificable',
                                                                ]" v-model="analisis.evaluacion_causalidad"
                                                                    label="Evaluación de causalidad" readonly>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs6>
                                                                <v-select :items="['Serio', 'No Serio']"
                                                                    v-model="analisis.clasificacion_invima" readonly
                                                                    label="Clasificación Invima">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs6 v-show="analisis.clasificacion_invima == 'Serio'
                                                                ">
                                                                <v-select :items="[
                                                                    'Produjo o prolongó hospitalización',
                                                                    'Anomalía congénita',
                                                                    'Amenaza de vida',
                                                                    'Muerte',
                                                                    'Produjo discapacidad o incapacidad permanente / condición médica importante',
                                                                ]" v-model="analisis.seriedad" label="Seriedad"
                                                                    readonly>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs6 v-if="analisis.seriedad == 'Muerte'">
                                                                <v-text-field v-model="analisis.fecha_muerte"
                                                                    label="Fecha Muerte" type="date" readonly>
                                                                </v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-flex>
                                                    <v-flex v-if="amef">
                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>Metodología AMEF</v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-flex xs12>
                                                            <v-textarea label="Elemento/Función" readonly
                                                                v-model="analisis.elemento_funcion">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Modo de fallo" readonly
                                                                v-model="analisis.modo_fallo">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Efecto" readonly v-model="analisis.efecto">
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-layout row wrap>
                                                            <v-flex xs4>
                                                                <v-text-field label="NPR" readonly v-model="analisis.npr"
                                                                    type="number">
                                                                </v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                        <v-flex xs12>
                                                            <v-textarea label="Acciones propuestas" readonly
                                                                v-model="analisis.acciones_propuestas">
                                                            </v-textarea>
                                                        </v-flex>
                                                    </v-flex>
                                                    <v-flex v-if="algoritmoWHO">
                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>WHO AEFI (EPAV)</v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-flex xs11 class="mt-3">
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.causas_esavi"
                                                                label="¿Hay evidencia determinante que indique que otras causas originaron el ESAVI?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.asociacion_esavi"
                                                                label="¿Hay una asociación descrita en la literatura, entre el ESAVI y la vacuna o la vacunación?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.ventana_mayoriesgo"
                                                                label="¿El evento estuvo dentro de la ventana de tiempo de mayor riesgo?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.evidencia_asociacioncausal"
                                                                label="¿Hay evidencia en contra de una asociación causal?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.factores_esavi"
                                                                label="¿Existen otros factores que pudieron haber impactado en el desarrollo del ESAVI?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-layout row wrap>
                                                            <v-flex xs6>
                                                                <v-select :items="[
                                                                    'Reacción relacionada con la vacuna',
                                                                    'Reacción relacionada con defectos en la calidad de la vacuna',
                                                                    'Reacción relacionada con un error en la inmunización',
                                                                    'Reacción relacionada con la ansiedad generalizada por la inmunización',
                                                                    'La relación temporal es coherente, pero la evidencia no es concluyente',
                                                                    'Tendencias contradictorias de coherencia e inconsistencia para una sociación',
                                                                    'Coincidente (condiciones subyacentes o emergentes)',
                                                                    'Inclasificable',
                                                                ]" v-model="analisis.evaluacion_causalidad" readonly
                                                                    label="Evaluación de causalidad">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs6>
                                                                <v-select :items="['Serio', 'No Serio']"
                                                                    v-model="analisis.clasificacion_invima" readonly
                                                                    label="Clasificación Invima">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs6 v-show="analisis.clasificacion_invima == 'Serio'
                                                                ">
                                                                <v-select :items="[
                                                                    'Produjo o prolongó hospitalización',
                                                                    'Anomalía congénita',
                                                                    'Amenaza de vida',
                                                                    'Muerte',
                                                                    'Produjo discapacidad o incapacidad permanente / condición médica importante',
                                                                ]" v-model="analisis.seriedad" label="Seriedad"
                                                                    readonly>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs6 v-if="analisis.seriedad == 'Muerte'">
                                                                <v-text-field v-model="analisis.fecha_muerte" readonly
                                                                    label="Fecha Muerte" type="date"></v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-flex>
                                                    <v-flex v-if="algoritmoVACA">
                                                        <v-toolbar color="primary" flat dark>
                                                            <v-flex xs12 text-xs-center>
                                                                <v-toolbar-title>FT - VACA-DELASSALAS
                                                                </v-toolbar-title>
                                                            </v-flex>
                                                        </v-toolbar>
                                                        <v-flex xs11 class="mt-3">
                                                            <v-select :items="respuestas"
                                                                v-model="analisis.farmaco_cinetica" readonly
                                                                label="¿El FT se refiere a un fármaco de cinética compleja?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.condiciones_farmacocinetica"
                                                                label="¿El paciente presenta condiciones clínicas que alteren la farmacocinética?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.prescribio_manerainadecuada"
                                                                label="¿El medicamento se prescribió de manera inadecuada">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.medicamento_manerainadecuada"
                                                                label="¿El medicamento se usó de manera inadecuada?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.medicamento_entrenamiento"
                                                                label="¿La administracion del medicamento requiere un entrenamiento especial del paciente?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.potenciales_interacciones"
                                                                label="¿Existen potenciales interacciones?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly v-model="analisis.notificacion_refieremedicamento
                                                                "
                                                                label="¿La notificación de FT se refiere a un medicamento genérico o una marca comercial?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.problema_biofarmaceutico"
                                                                label="¿Existe algún problema biofarmacéutico estudiado?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.deficiencias_sistemas"
                                                                label="¿Existen deficiencias en los sistemas de almacenamiento del medicamento?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs11>
                                                            <v-select :items="respuestas" readonly
                                                                v-model="analisis.factores_asociados"
                                                                label="¿Existen otros factores asociados asociados que pudieran explicar el FT?">
                                                            </v-select>
                                                        </v-flex>
                                                        <v-layout row wrap>
                                                            <v-flex xs6>
                                                                <v-select :items="[
                                                                    'Posiblemente asociado al uso inadecuado del medicamento',
                                                                    'Notificación posiblemente inducida',
                                                                    'Posiblemente asociado a un problema biofarmacéutico (calidad)',
                                                                    'Posiblemente asociado a respuesta idiosincrática u otras razones no establecidas',
                                                                    'No se cuenta con información suficiente para el análisis',
                                                                ]" v-model="analisis.evaluacion_causalidad" readonly
                                                                    label="Evaluación de causalidad">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs6>
                                                                <v-select :items="['Serio', 'No Serio']"
                                                                    v-model="analisis.clasificacion_invima" readonly
                                                                    label="Clasificación Invima">
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs6 v-show="analisis.clasificacion_invima == 'Serio'
                                                                ">
                                                                <v-select :items="[
                                                                    'Produjo o prolongó hospitalización',
                                                                    'Anomalía congénita',
                                                                    'Amenaza de vida',
                                                                    'Muerte',
                                                                    'Produjo discapacidad o incapacidad permanente / condición médica importante',
                                                                ]" v-model="analisis.seriedad" label="Seriedad"
                                                                    readonly>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs6 v-if="analisis.seriedad == 'Muerte'">
                                                                <v-text-field v-model="analisis.fecha_muerte" readonly
                                                                    label="Fecha Muerte" type="date"></v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-flex>

                                                    <v-toolbar color="primary" flat dark>
                                                        <v-flex xs12 text-xs-center>
                                                            <v-toolbar-title>Consecuencias y acciones de mejora
                                                            </v-toolbar-title>
                                                        </v-flex>
                                                    </v-toolbar>
                                                    <v-flex xs12>
                                                        <v-textarea v-model="analisis.descripcion_consecuencias" readonly
                                                            label="Descripción consecuencias">
                                                        </v-textarea>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-select :items="[
                                                            'Recuperado / Resuelto sin secuelas',
                                                            'Recuperado / Resuelto con secuelas',
                                                            'Recuperando / Resolviendo',
                                                            'No recuperado / No resuelto',
                                                            'Fatal',
                                                            'Desconocido',
                                                            'Muerte',
                                                        ]" v-model="analisis.desenlace_evento"
                                                            label="Desenlace del evento">
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-select :items="clasificaciones" readonly
                                                            v-model="analisis.clasificacion"
                                                            label="Clasificación del suceso">
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs11>
                                                        <v-autocomplete v-model="analisis.accion_mejora" readonly
                                                            :items="accion_mejoras" label="Acciones de mejora" multiple
                                                            chips>
                                                        </v-autocomplete>
                                                    </v-flex>

                                                    <v-flex xs12>
                                                        <v-expansion-panel v-for="(acm, index) in analisis.accion_mejora"
                                                            :key="index">
                                                            <v-expansion-panel-content>
                                                                <template v-slot:actions>
                                                                    <v-icon color="success">add</v-icon>
                                                                </template>
                                                                <template v-slot:header>
                                                                    <div class="primary--text text--darken-2">
                                                                        <strong>{{ acm }}</strong>
                                                                    </div>
                                                                </template>
                                                                <v-card>
                                                                    <v-card-text>
                                                                        <v-layout row wrap>
                                                                            <v-flex xs12>
                                                                                <v-autocomplete :items="user_activos"
                                                                                    label="Responsable" item-text="nombre"
                                                                                    item-value="name" multiple readonly
                                                                                    v-model="acc_mejora.responsables" />
                                                                            </v-flex>
                                                                            <v-flex xs4 px-1>
                                                                                <v-text-field label="Fecha de cumplimiento"
                                                                                    type="date" readonly v-model="acc_mejora.fecha_cumplimiento
                                                                                        ">
                                                                                </v-text-field>
                                                                            </v-flex>
                                                                            <v-flex xs4 px-1>
                                                                                <v-text-field label="Fecha seguimiento"
                                                                                    readonly
                                                                                    v-model="acc_mejora.fecha_seguimiento"
                                                                                    type="date">
                                                                                </v-text-field>
                                                                            </v-flex>
                                                                            <v-flex xs4 px-1>
                                                                                <v-select :items="['Pendiente', 'Cumplido']"
                                                                                    v-model="acc_mejora.estado" readonly
                                                                                    label="Estado">
                                                                                </v-select>
                                                                            </v-flex>
                                                                        </v-layout>
                                                                    </v-card-text>
                                                                </v-card>
                                                            </v-expansion-panel-content>
                                                        </v-expansion-panel>
                                                    </v-flex>

                                                    <v-flex xs12 v-for="(accionm, index2) in analisis.pre_analisis"
                                                        :key="index2">
                                                        <v-expansion-panel v-for="(
                                accionesmejora, index3
                              ) in accionm.accion_mejoras" :key="index3">
                                                            <v-expansion-panel-content>
                                                                <template v-slot:actions>
                                                                    <v-icon color="success">done</v-icon>
                                                                </template>
                                                                <template v-slot:header>
                                                                    <div class="success--text text--darken-2">
                                                                        <strong>{{ accionesmejora.nombre }}</strong>
                                                                    </div>
                                                                </template>
                                                                <v-card>
                                                                    <v-card-text>
                                                                        <v-layout row wrap>
                                                                            <v-flex xs12>
                                                                                <v-autocomplete :items="user_activos"
                                                                                    label="Responsable" readonly
                                                                                    item-text="nombre" item-value="name"
                                                                                    multiple
                                                                                    v-model="accionesmejora.responsables" />
                                                                            </v-flex>
                                                                            <v-flex xs4 px-1>
                                                                                <v-text-field label="Fecha de cumplimiento"
                                                                                    readonly type="date" v-model="accionesmejora.fecha_cumplimiento
                                                                                        ">
                                                                                </v-text-field>
                                                                            </v-flex>
                                                                            <v-flex xs4 px-1>
                                                                                <v-text-field label="Fecha seguimiento"
                                                                                    readonly v-model="accionesmejora.fecha_seguimiento
                                                                                        " type="date">
                                                                                </v-text-field>
                                                                            </v-flex>
                                                                            <v-flex xs4 px-1>
                                                                                <v-select :items="['Pendiente', 'Cumplido']"
                                                                                    readonly v-model="accionesmejora.estado"
                                                                                    label="Estado">
                                                                                </v-select>
                                                                            </v-flex>
                                                                            <v-flex xs12>
                                                                                <v-textarea
                                                                                    v-model="accionesmejora.observacion"
                                                                                    label="Observaciones" readonly>
                                                                                </v-textarea>
                                                                            </v-flex>
                                                                            <v-flex xs12>
                                                                                <v-btn v-for="(
                                            adjuntoR, index
                                          ) in accionesmejora.adjuntos" :key="index" :disabled="search_adjunto">
                                                                                    <a @click="
                                                                                        consultarAdjunto(adjuntoR.ruta)
                                                                                        ">
                                                                                        <v-icon color="dark">
                                                                                            mdi-cloud-upload
                                                                                        </v-icon>Adjunto
                                                                                        {{ index + 1 }}
                                                                                    </a>
                                                                                </v-btn>
                                                                                <v-divider class="my-4"></v-divider>
                                                                            </v-flex>
                                                                        </v-layout>
                                                                    </v-card-text>
                                                                </v-card>
                                                            </v-expansion-panel-content>
                                                        </v-expansion-panel>
                                                    </v-flex>

                                                    <v-flex xs6 v-if="analisis.nombreEvento == 'Farmacovigilancia' ||
                                                        analisis.nombreEvento == 'Reactivovigilancia' ||
                                                        analisis.nombreEvento == 'Tecnovigilancia' ||
                                                        analisis.nombreEvento ==
                                                        'Infecciones asociadas al cuidado de la salud (IAAS)' ||
                                                        analisis.nombreEvento ==
                                                        'Administración de hemoderivados'
                                                        ">
                                                        <v-select :items="['SI', 'NO']"
                                                            v-model="analisis.requiere_reporte_ente"
                                                            label="Requiere reportar al ente?">
                                                        </v-select>
                                                    </v-flex>
                                                    <v-flex xs6 v-if="analisis.nombreEvento == 'Farmacovigilancia'">
                                                        <v-select :items="['SI', 'NO']"
                                                            v-model="analisis.requiere_marca_especifica"
                                                            label="Requiere marca especifica?">
                                                        </v-select>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                </v-layout>
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

        <v-dialog v-model="dialogAcciones" max-width="500px">
            <v-card>
                <v-card-title> Agregar acción insegura </v-card-title>
                <v-card-text>
                    <v-combobox v-model="newAccion" multiple chips :items="[]"></v-combobox>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="addAccion">Agregar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogAccionesMejora" max-width="500px">
            <v-card>
                <v-card-title> Agregar acción mejora </v-card-title>
                <v-card-text>
                    <v-combobox v-model="newAccionMejora" multiple chips :items="[]"></v-combobox>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="addAccionMejora">Agregar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogItems" max-width="500px">
            <v-card>
                <v-card-title> Agregar {{ campo }} </v-card-title>
                <v-card-text>
                    <v-combobox v-model="newItems" multiple chips :items="[]"></v-combobox>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="addItems">Agregar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import Swal from "sweetalert2";
import { UserService } from "../../../services";
import { AdjuntoService } from "../../../services";
import moment from "moment";
export default {
    data() {
        return {
            search: "",
            search2: "",
            protocoLondresSelecccionado: false,
            menu1: false,
            menu2: false,
            headers: [
                {
                    text: "id",
                    align: "left",
                    value: "id",
                },
                {
                    text: "Sede ocurrencia",
                    align: "left",
                    value: "sede_ocurrencia",
                },
                {
                    text: "Fecha ocurrencia",
                    align: "left",
                    value: "fecha_ocurrencia",
                },
                {
                    text: "Documento",
                    align: "left",
                    value: "Num_Doc",
                },
                {
                    text: "Nombre",
                    align: "left",
                    value: "Primer_Nom",
                },
                {
                    text: "Nombre suceso",
                    align: "left",
                    value: "nombreEvento",
                },
                {
                    text: "Entidad",
                    align: "left",
                    value: "entidad",
                },
                {
                    text: "Estado",
                    align: "left",
                    value: "estado",
                },
                {
                    text: "Accion",
                    align: "left",
                },
            ],
            dateDesde: moment().format("YYYY-MM-DD"),
            dateHasta: moment().format("YYYY-MM-DD"),
            pendientes: [],
            dialogAnalisis: false,
            data: {},
            clasificaciones: [
                "Evento adverso prevenible",
                "Evento adverso no prevenible",
                'Centinela',
                "Incidente",
                "Complicación",
                "Indicio de atención insegura",
            ],
            campos_evento: false,
            s: "",
            o: "",
            d: "",
            alleventos: [],
            evento: "",
            loading: false,
            analisis: [],
            cerrados: [],
            eventocerrado: "",
            dialogHistorial: false,
            allclasificacion: [],
            eventoId: "",
            clasificacionId: "",
            tipoeventoId: "",
            alltipoeventos: [],
            filter: {},
            filters: {
                state: "",
                start_date: null,
                end_date: null,
                suceso: "",
                id: "",
                documento: "",
                entidad: "",
                sede: "",
            },
            allentidades: [],
            users: [],
            form: {
                acciones: "",
                priorizacion: "",
                user: [],
                puntajeTotal: 0,
                nivelPriorizacion: "",
                motivo: "",
                causamotivo: "",
                clasificacion: "",
                identificacion_riesgo: "",
                voluntariedad: "",
            },
            preload: false,
            acciones: ["Asignar", "Re Asignar", "Anular", "Actualizar", "Finalizar"],
            probabilidad: "",
            impacto: "",
            previsibilidad: "",
            socurrencias: [
                "Hospitalización tercer piso",
                "Hospitalización cuarto piso",
                "Hospitalización quinto piso",
                "Hospitalización sexto piso",
                "Unidad de cuidados intensivos (UCI)",
                "Unidad de cuidados especiales (UCE)",
                "Cirugía",
                "Endoscopia",
                "Urgencias",
                "Servicio farmacéutico",
                "Laboratorio",
                "Alimentación",
                "Centro regulador",
                "Quimioterapia",
                "Diagnóstico cardiovascular",
                "Imagenología",
                "Vacunación",
            ],
            protocolo_londres: false,
            respuesta_inmediata: false,
            algoritmOMS: false,
            amef: false,
            algoritmoWHO: false,
            algoritmoVACA: false,
            respuestas: ["SI", "NO", "NO SABE"],
            dialogAccionesFarmaco: false,
            accion_inseguras: [],
            dialogAcciones: false,
            newAccion: [],
            condiciones_paciente: [
                "Condiciones anatomofisiologicas",
                "Patologia en curso",
                "Edad",
                "Su lenguaje y comunicacion",
                "No dherencia al tratamiento indicado",
                "No adherencia a las normas y recomendaciones",
            ],
            metodos_procesos: [
                "No existen procesos documentados",
                "Los procesos documentados no son claros y/o no se adaptan a la dinamica intitucional",
                "No adherencia a los procesos documentados",
                "El sistema de la institucion limita la ejecucion de los procesos",
            ],
            colaboradores: [
                "Desconocimiento",
                "Falta de habilidades",
                "Debilidad de competencias",
                "No adherencia a los lineamientos establecidos",
                "Lapsus, distraccion",
            ],
            equipo_trabajo: [
                "Falta de capacitacion o entrenamiento al equipo",
                "Fallas en la comunicacion (comunicacion inefectiva)",
                "Falta de liderazgo y/o supervision de quien lidera el proceso",
                "El equipo no se encuentra adecuadamente estructurado para la ejecucion de los procesos",
                "No se encuentran definidas las tareas entre los miebros del equipo",
            ],
            ambientes: [
                "Insuficiencia del personal",
                "Sobrecarga laboral / cansancio / estres",
                "Inadecuada distribucion de turnos",
                "Clima laboral",
                "Ambiente fisico (iluminacion inadecuada, contaminacion visual o auditiva, obstaculos en los pasillos)",
            ],
            recursos: [
                "No se han solicitado los recursos necesarios",
                "No asignacion del recurso por parte de la institucion",
                "Recursos financieros limitados",
                "No disponibilidad de medicamento / dispositivo medico / reactivo",
                "Medicamento / dispositivo médico / reactivo vencido",
                "Mal uso de medicamento / dispositivo medico / reactivo",
            ],
            contexto_institucional: [
                "Decisiones gerenciales",
                "No estan definidas las políticas para la operativizacion de los procesos",
                "Desconocimento de las politicas establecidas",
                "Percepción de falta de cultura organizacional",
            ],
            accion_new: {},
            dialogItems: false,
            newItems: [],
            campo: "",
            users: [],
            dialogAccionesMejora: false,
            newAccionMejora: [],
            accion_mejoras: [],
            acc_mejora: {},
            voluntariedad: "",
            identificacion_riesgo: "",
            motivos: [
                "Duplicidad en el reporte",
                "No corresponde a un suceso de seguridad del paciente",
                "No hay suficiente información para gestionar el suceso",
                "Suceso de seguridad del paciente que no amerita análisis",
                "Error de medicación - Tipo A: Circunstancias o incidentes con capacidad de causar error",
                "Error de medicación - Tipo B: El error se produjo, pero no alcanzó al paciente",
                "Otros",
            ],
            sedes: [],
            allsedes: [],
            search_adjunto: false,
            adjuntos: [],
        };
    },
    watch: {
        probabilidad: function () {
            this.form.puntajeTotal =
                parseInt(this.probabilidad) +
                parseInt(this.impacto) +
                parseInt(this.previsibilidad);

            if (parseInt(this.form.puntajeTotal) >= 7) {
                this.form.nivelPriorizacion = "ALTO";
            } else if (parseInt(this.form.puntajeTotal) >= 5) {
                this.form.nivelPriorizacion = "MODERADO";
            } else if (parseInt(this.form.puntajeTotal) >= 3) {
                this.form.nivelPriorizacion = "BAJO";
            }
        },
        impacto: function () {
            this.form.puntajeTotal =
                parseInt(this.probabilidad) +
                parseInt(this.impacto) +
                parseInt(this.previsibilidad);

            if (parseInt(this.form.puntajeTotal) >= 7) {
                this.form.nivelPriorizacion = "ALTO";
            } else if (parseInt(this.form.puntajeTotal) >= 5) {
                this.form.nivelPriorizacion = "MODERADO";
            } else if (parseInt(this.form.puntajeTotal) >= 3) {
                this.form.nivelPriorizacion = "BAJO";
            }
        },
        previsibilidad: function () {
            this.form.puntajeTotal =
                parseInt(this.probabilidad) +
                parseInt(this.impacto) +
                parseInt(this.previsibilidad);

            if (parseInt(this.form.puntajeTotal) >= 7) {
                this.form.nivelPriorizacion = "ALTO";
            } else if (parseInt(this.form.puntajeTotal) >= 5) {
                this.form.nivelPriorizacion = "MODERADO";
            } else if (parseInt(this.form.puntajeTotal) >= 3) {
                this.form.nivelPriorizacion = "BAJO";
            }
        },
    },
    computed: {
        user_activos() {
            let finded = [];
            this.users.forEach((u) => {
                if (u.estado == 1) {
                    finded.push(u);
                }
            });
            return finded;
        },
        sedesCompletas() {
            return this.allsedes.map((sede) => {
                return {
                    id: `${sede.id}`,
                    nombre: `${sede.Nombre} - ${sede.Direccion}`,
                };
            });
        },
        showSeveridad() {
            if (
                this.form.acciones == "Finalizar" &&
                (this.analisis.clasificacion == "Evento adverso prevenible" ||
                    this.analisis.clasificacion == "Evento adverso no prevenible")
            ) {
                return true;
            }
            return false;
        },
        estadoAcciones() {
            if (this.analisis.estado == "Asignado") {
                let forDeletion = ["Asignar", "Actualizar", "Finalizar"];
                let array = this.acciones.filter((item) => !forDeletion.includes(item));
                return array;
            }
            if (this.analisis.estado == "Pendiente") {
                let forDeletion = ["Actualizar", "Finalizar", "Re Asignar"];
                let array = this.acciones.filter((item) => !forDeletion.includes(item));
                return array;
            }
            if (this.analisis.estado == "Analizado") {
                let forDeletion = ["Asignar", "Re Asignar"];
                let array = this.acciones.filter((item) => !forDeletion.includes(item));
                return array;
            }
            if (this.analisis.estado == "Seguimiento Plan Mejora") {
                let forDeletion = ["Finalizar", "Re Asignar", "Asignar"];
                let array = this.acciones.filter((item) => !forDeletion.includes(item));
                return array;
            }
            if (this.analisis.estado == "Plan de Mejora Cumplido") {
                let forDeletion = ["Re Asignar", "Asignar"];
                let array = this.acciones.filter((item) => !forDeletion.includes(item));
                return array;
            }
            return this.acciones;
        },
    },
    mounted() {
        this.getpendientes();
        this.geteventos();
        this.getUser();
        this.getsedes();
    },
    methods: {

        condicionCentinela(tipo, valor) {
            if (valor === 'Protocolo de Londres') {
                this.protocoloLondresSeleccionado = true;
            } else {
                this.protocoloLondresSeleccionado = false;
            }
        },

        metodologia(tipo, metodo) {
            if (this.analisis.metodologia == "Protocolo de Londres") {
                this.protocolo_londres = true;
                this.respuesta_inmediata = false;
                this.amef = false;
                return;
            } else if (this.analisis.metodologia == "Respuesta Inmediata") {
                this.protocolo_londres = false;
                this.respuesta_inmediata = true;
            } else if (this.analisis.metodologia == "Algoritmo OMS") {
                this.algoritmOMS = true;
                this.amef = false;
                this.algoritmoWHO = false;
                this.algoritmoVACA = false;
            } else if (this.analisis.metodologia == "Metodología AMEF") {
                this.algoritmOMS = false;
                this.amef = true;
                this.algoritmoWHO = false;
                this.algoritmoVACA = false;
            } else if (this.analisis.metodologia == "WHO AEFI (EPAV)") {
                this.algoritmOMS = false;
                this.amef = false;
                this.algoritmoWHO = true;
                this.algoritmoVACA = false;
            } else if (this.analisis.metodologia == "FT - VACA-DELASSALAS") {
                this.algoritmOMS = false;
                this.amef = false;
                this.algoritmoWHO = false;
                this.algoritmoVACA = true;
            }
            if (tipo == "select") {
                if (metodo == "Protocolo de Londres") {
                    this.algoritmOMS = false;
                    this.protocolo_londres = true;
                    this.respuesta_inmediata = false;
                } else if (metodo == "Respuesta Inmediata") {
                    this.algoritmOMS = false;
                    this.protocolo_londres = false;
                    this.respuesta_inmediata = true;
                } else if (metodo == "Algoritmo OMS") {
                    this.protocolo_londres = false;
                    this.algoritmOMS = true;
                    this.amef = false;
                    this.algoritmoWHO = false;
                    this.algoritmoVACA = false;
                } else if (metodo == "Metodología AMEF") {
                    this.protocolo_londres = false;
                    this.algoritmOMS = false;
                    this.amef = true;
                    this.algoritmoWHO = false;
                    this.algoritmoVACA = false;
                } else if (metodo == "WHO AEFI (EPAV)") {
                    this.protocolo_londres = false;
                    this.algoritmOMS = false;
                    this.amef = false;
                    this.algoritmoWHO = true;
                    this.algoritmoVACA = false;
                } else if (metodo == "FT - VACA-DELASSALAS") {
                    this.protocolo_londres = false;
                    this.algoritmOMS = false;
                    this.amef = false;
                    this.algoritmoWHO = false;
                    this.algoritmoVACA = true;
                }
            } else {
                if (this.analisis.eventoId != 109) {
                    if (this.analisis.nivel_priorizacion == "ALTO") {
                        this.protocolo_londres = true;
                        this.analisis.metodologia = "Protocolo de Londres";
                        this.amef = false;
                    } else if (this.analisis.nivel_priorizacion == "MODERADO") {
                        this.protocolo_londres = false;
                    } else {
                        this.protocolo_londres = false;
                    }
                }
            }
        },
        addAccion() {
            if (this.newAccion.length <= 0) return;
            for (let i = 0; i < this.newAccion.length; i++) {
                let str =
                    this.newAccion[i].charAt(0).toUpperCase() +
                    this.newAccion[i].slice(1);
                this.accion_inseguras.push(str);
            }
            this.newAccion = [];
            this.dialogAcciones = false;
        },
        addAccionMejora() {
            if (this.newAccionMejora.length <= 0) return;
            for (let i = 0; i < this.newAccionMejora.length; i++) {
                let str =
                    this.newAccionMejora[i].charAt(0).toUpperCase() +
                    this.newAccionMejora[i].slice(1);
                this.accion_mejoras.push(str);
            }
            this.newAccionMejora = [];
            this.dialogAccionesMejora = false;
        },
        preAnalisis() {
            if (
                this.analisis.fecha_analisis == "" ||
                this.analisis.fecha_analisis == null ||
                this.analisis.cronologia == "" ||
                this.analisis.cronologia == null ||
                this.analisis.metodologia == "" ||
                this.analisis.metodologia == null
            ) {
                this.$alerError("Debe ingresar los campos obligatorios!");
                return;
            }
            axios
                .post("/api/evento/preAnalisis", this.analisis)
                .then((res) => {
                    this.$alerSuccess("Analisis guardado con exito!");
                    this.salirAnalisis();
                })
                .catch((err) => {
                    this.$alerError("Hubo un error!");
                });
        },
        saveAccion(item) {
            this.accion_new.analisisevento_id = this.analisis.analisisevento_id;
            this.accion_new.name = item;
            axios
                .post("/api/evento/addAcciones", this.accion_new)
                .then((res) => {
                    this.analisis.accion_inseguras.splice(
                        this.analisis.accion_inseguras.indexOf(item),
                        1
                    );
                    this.accion_new = {};
                    this.$alerSuccess("Acción guardada con exito!");
                })
                .catch((err) => {
                    this.$alerError("Hubo un error, o ya existe esta acción!");
                });
        },
        saveAccionMejora(item) {
            this.acc_mejora.analisisevento_id = this.analisis.analisisevento_id;
            this.acc_mejora.nombre = item;
            axios
                .post("/api/evento/addAccionesMejoras", this.acc_mejora)
                .then((res) => {
                    this.analisis.accion_mejora.splice(
                        this.analisis.accion_mejora.indexOf(item),
                        1
                    );
                    this.acc_mejora = {};
                    this.$alerSuccess("Acción de mejora guardada con exito!");
                })
                .catch((err) => {
                    this.$alerError("Hubo un error, o ya existe esta acción de mejora!");
                });
        },
        addialog(item) {
            this.dialogItems = true;
            this.campo = item;
        },
        addItems() {
            if (this.newItems.length <= 0) return;
            if (this.campo == "Condiciones del paciente") {
                this.newItems.forEach((it) => {
                    this.condiciones_paciente.push(it);
                });
                this.newItems = [];
            }
            if (this.campo == "Metodos/Procesos") {
                this.newItems.forEach((it) => {
                    this.metodos_procesos.push(it);
                });
                this.newItems = [];
            }
            if (this.campo == "Colaborador") {
                this.newItems.forEach((it) => {
                    this.colaboradores.push(it);
                });
                this.newItems = [];
            }
            if (this.campo == "Equipo de trabajo") {
                this.newItems.forEach((it) => {
                    this.equipo_trabajo.push(it);
                });
                this.newItems = [];
            }
            if (this.campo == "Ambiente") {
                this.newItems.forEach((it) => {
                    this.ambientes.push(it);
                });
                this.newItems = [];
            }
            if (this.campo == "Recursos/Materiales/Insumos") {
                this.newItems.forEach((it) => {
                    this.recursos.push(it);
                });
                this.newItems = [];
            }
            if (this.campo == "Contexto institucional") {
                this.newItems.forEach((it) => {
                    this.contexto_institucional.push(it);
                });
                this.newItems = [];
            }
            this.dialogItems = false;
        },
        edititems(item) {
            axios
                .post("/api/evento/editAcciones/" + item.id, item)
                .then((res) => {
                    this.$alerSuccess("Acción editada con exito!");
                    this.salirAnalisis();
                })
                .catch((err) => {
                    this.$alerError("Hubo un error, o ya existe esta acción!");
                });
        },
        deleteitems(item) {
            axios
                .post("/api/evento/deleteAcciones/" + item)
                .then((res) => {
                    this.$alerSuccess("Acción eliminada con exito!");
                    this.salirAnalisis();
                })
                .catch((err) => {
                    this.$alerError("Hubo un error!");
                });
        },
        editAccionesMejora(item) {
            console.log(item);
            axios
                .post("/api/evento/editAccionMejora/" + item.id, item)
                .then((res) => {
                    this.$alerSuccess("Acción de mejora editada con exito!");
                })
                .catch((err) => {
                    this.$alerError("Hubo un error, o ya existe esta acción de mejora!");
                });
        },
        deleteAccionesMejora(item) {
            axios
                .post("/api/evento/deleteAccioneMejora/" + item)
                .then((res) => {
                    this.$alerSuccess("Acción de mejora eliminada con exito!");
                    this.salirAnalisis();
                })
                .catch((err) => {
                    this.$alerError("Hubo un error!");
                });
        },
        colorChip(nivel) {
            if (nivel == "ALTO") {
                return "error white--text";
            } else if (nivel == "MODERADO") {
                return "yellow black--text";
            } else {
                return "success white--text";
            }
        },
        getUser() {
            axios.get("/api/user/all").then(
                (res) =>
                (this.users = res.data.map((us) => {
                    return {
                        id: us.id,
                        nombre: `${us.cedula} - ${us.name} ${us.apellido}`,
                        estado: us.estado_user,
                        name: `${us.name} ${us.apellido}`,
                    };
                }))
            );
        },
        geteventos() {
            axios.get("/api/evento/allsumimedical").then((res) => {
                this.alleventos = res.data;
            });
        },
        getclasificacion() {
            if (this.eventoId != "") {
                this.allclasificacion = [];
                this.alltipoeventos = [];
                this.analisis.nombreClasificacion = "";
                this.analisis.nombreTipoevento = "";
                this.clasificacionId = "";
                this.tipoeventoId = "";
                axios
                    .get("/api/evento/getclasificacion/" + this.eventoId)
                    .then((res) => {
                        this.allclasificacion = res.data;
                    });
            }
        },
        getTipoevento() {
            if (this.clasificacionId != "") {
                axios
                    .get("/api/evento/getTipoevento/" + this.clasificacionId)
                    .then((res) => {
                        this.alltipoeventos = res.data;
                    });
            }
        },
        getpendientes() {
            this.loading = true;
            this.preload = true;
            axios
                .post("/api/evento/getpendientes", {
                    evento_id: this.evento,
                })
                .then((res) => {
                    this.pendientes = res.data;
                    this.allentidades = [];
                    this.pendientes.map((p) => {
                        if (p.entidad != null || p.entidad != undefined) {
                            this.allentidades.push(p.entidad);
                        }
                        this.sedes.push(p.sede_ocurrencia);
                    });
                    this.loading = false;
                    this.preload = false;
                })
                .catch((err) => {
                    this.loading = false;
                    this.preload = false;
                });
        },
        openAnalisis(datos) {
            this.eventoId = datos.eventoId;
            this.clasificacionId = datos.clasificacionId;
            this.tipoeventoId = datos.tipoeventoId;
            this.getclasificacion();
            this.analisis = datos;
            this.metodologia();
            this.dialogAnalisis = true;
        },
        accion() {
            if (this.form.acciones == "") {
                this.$alerError("Debe seleccionar una acción.");
                return;
            }
            if (this.form.acciones == "Asignar") {
                this.asignar();
            }
            if (this.form.acciones == "Re Asignar") {
                this.reAsignar();
            }
            if (this.form.acciones == "Anular") {
                this.anular();
            }
            if (this.form.acciones == "Actualizar") {
                this.preAnalisis();
            }
            if (this.form.acciones == "Finalizar") {
                this.closeAnalisis();
            }
        },
        asignar() {
            if (this.form.priorizacion == "") {
                this.$alerError("Debe seleccionar una priorización.");
                return;
            }
            if (this.form.user.length == 0) {
                this.$alerError("Debe seleccionar un usuario almenos.");
                return;
            }
            // if (this.form.nivelPriorizacion == '') {
            //     this.$alerError(
            //         'El nivel de priorización debe estar definido, llene los campos necesarios para calcular el "Puntaje de Priorización".'
            //     )
            //     return;
            // }
            // if (this.form.puntajeTotal == '' || this.form.puntajeTotal == null || isNaN(this.form.puntajeTotal)) {
            //     this.$alerError(
            //         'El nivel de priorización debe estar definido, llene los campos necesarios para calcular el "Puntaje de Priorización".'
            //     )
            //     return;
            // }
            if (this.form.voluntariedad == "") {
                this.$alerError("No se puede dejar el campo Voluntariedad vacio!");
                return;
            }
            if (this.form.identificacion_riesgo == "") {
                this.$alerError(
                    "No se puede dejar el campo identificación del riesgo vacio!"
                );
                return;
            }
            this.form.probabilidad = this.probabilidad;
            this.form.previsibilidad = this.previsibilidad;
            this.form.impacto = this.impacto;
            this.preload = true;
            axios
                .post("/api/evento/asignar/" + this.analisis.id, this.form)
                .then((res) => {
                    this.getpendientes();
                    this.preload = false;
                    this.$alerSuccess(res.data.message);
                    this.salirAnalisis();
                })
                .catch((err) => {
                    this.preload = false;
                    this.$alerError("Debe llenar los campos obligatorios.");
                });
        },
        reAsignar() {
            if (this.form.user.length == 0) {
                this.$alerError("Debe seleccionar un usuario almenos.");
                return;
            }
            this.preload = true;
            axios
                .post("/api/evento/reasignar/" + this.analisis.id, this.form)
                .then((res) => {
                    this.getpendientes();
                    this.preload = false;
                    this.$alerSuccess(res.data.message);
                    this.salirAnalisis();
                })
                .catch((err) => {
                    this.preload = false;
                    this.$alerError("Debe llenar los campos obligatorios.");
                });
        },
        anular() {
            if (this.form.causamotivo == "") {
                this.$alerError("Debe ingresar un motivo.");
                return;
            }
            if (this.form.voluntariedad == "") {
                this.$alerError("Debe definir la voluntariedad.");
                return;
            }
            if (this.form.causamotivo == "Otros") {
                if (this.form.motivo == "") {
                    this.$alerError("Debe ingresar un motivo.");
                    return;
                }
                this.form.motivo = this.form.motivo;
            } else {
                this.form.motivo = this.form.causamotivo;
            }
            if (
                this.form.motivo ==
                "Error de medicación - Tipo A: Circunstancias o incidentes con capacidad de causar error" ||
                this.form.motivo ==
                "Error de medicación - Tipo B: El error se produjo, pero no alcanzó al paciente"
            ) {
                if (this.form.clasificacion == "") {
                    this.$alerError("Debe ingresar una clasificación!");
                    return;
                }
            }
            this.preload = true;
            axios
                .post("/api/evento/anular/" + this.analisis.id, this.form)
                .then((res) => {
                    this.getpendientes();
                    this.preload = false;
                    this.$alerSuccess(res.data.message);
                    this.salirAnalisis();
                })
                .catch((err) => {
                    this.preload = false;
                    this.$alerError("Debe llenar los campos obligatorios.");
                });
        },
        saveAnalisis() {
            let data = {
                eventoadverso_id: this.analisis.id,
                fecha_analisis: this.analisis.fecha_analisis,
                cronologia: this.analisis.cronologia,
                consecuencias: this.analisis.consecuencias,
                acciones_inseguras: this.analisis.acciones_inseguras,
                clasificacion_evento: this.analisis.clasificacion,
                paciente: this.analisis.paciente,
                tarea_tecnologia: this.analisis.tarea_tecnologia,
                individuo: this.analisis.individuo,
                equipo_trabajo: this.analisis.equipo_trabajo,
                ambiente: this.analisis.ambiente,
                organizacion: this.analisis.organizacion,
                contexto: this.analisis.contexto,
                accion_mejora: this.analisis.accion_mejora,
                responsable: this.analisis.responsable,
                fecha_inicio: this.analisis.fecha_inicio,
                fecha_finalizacion: this.analisis.fecha_finalizacion,
                porcentaje_mejora: this.analisis.porcentaje_mejora,
                fecha_seguimiento: this.analisis.fecha_seguimiento,
                porcentaje_cumplimiento: this.analisis.porcentaje_cumplimiento,
                elemento_funcion: this.analisis.elemento_funcion,
                modo_fallo: this.analisis.modo_fallo,
                efecto: this.analisis.efecto,
                npr: this.analisis.npr,
                acciones_propuestas: this.analisis.acciones_propuestas,
            };
            axios.post("/api/evento/saveAnalisis", data).then((res) => {
                this.$alerSuccess(res.data.message);
                this.getAnalisis();
            });
        },
        closeAnalisis() {
            swal({
                title: "Esta seguro de cerrar definitivamente el evento?",
                icon: "info",
                buttons: ["Cancelar", "Confirmar"],
            }).then((willDelete) => {
                if (willDelete) {
                    axios
                        .post("/api/evento/closeAnalisis", {
                            eventoadverso_id: this.analisis.id,
                            severidad: this.analisis.severidad_evento,
                        })
                        .then((res) => {
                            this.dialogAnalisis = false;
                            this.getpendientes();
                            this.$alerSuccess(res.data.message);
                        })
                        .catch((err) => {
                            this.$alerError("Hubo un error!");
                        });
                }
            });
        },
        preAnalisis() {
            if (
                this.analisis.fecha_analisis == "" ||
                this.analisis.fecha_analisis == null ||
                this.analisis.cronologia == "" ||
                this.analisis.cronologia == null ||
                this.analisis.metodologia == "" ||
                this.analisis.metodologia == null
            ) {
                this.$alerError("Debe ingresar los campos obligatorios!");
                return;
            }
            axios
                .post("/api/evento/preAnalisis", this.analisis)
                .then((res) => {
                    this.$alerSuccess("Analisis guardado con exito!");
                    this.salirAnalisis();
                })
                .catch((err) => {
                    this.$alerError("Hubo un error!");
                });
        },
        getcerrados() {
            this.loading = true;
            axios({
                method: "post",
                params: {
                    fechaDesde: this.dateDesde,
                    fechaHasta: this.dateHasta,
                    evento: this.eventocerrado,
                },
                url: "/api/evento/getcerrados",
            }).then((res) => {
                console.log(res.data);
                this.cerrados = res.data;
                this.loading = false;
            });
        },
        openHistorial(datos) {
            this.metodologia();
            this.analisis = datos;
            this.metodologia();
            this.dialogHistorial = true;
        },
        updateAreas() {
            if (this.analisis.sedeocurrencia_id == 704) {
                if (
                    this.analisis.servicio_ocurrencia == "" ||
                    this.analisis.servicio_ocurrencia == null
                ) {
                    this.$alerError("Debe llenar el campo servicio de ocurrencia!");
                    return;
                }
            }
            let data = {
                eventoadverso_id: this.analisis.id,
                evento_id: this.eventoId,
                clasificacionevento_id: this.clasificacionId,
                tipoevento_id: this.tipoeventoId,
                sedeocurrencia_id: this.analisis.sedeocurrencia_id,
                servicio_ocurrencia: this.analisis.servicio_ocurrencia,
            };
            this.loading = true;
            axios.post("/api/evento/updateArea", data).then((res) => {
                this.getpendientes();
                this.loading = false;
                this.$alerSuccess(res.data.message);
                this.dialogAnalisis = false;
            });
        },

        salirAnalisis() {
            for (const prop of Object.getOwnPropertyNames(this.form)) {
                this.form[prop] = "";
            }
            this.dialogAnalisis = false;
            this.tipoeventoId = "";
            this.alltipoeventos = [];
            this.clasificacionId = "";
            this.probabilidad = "";
            this.impacto = "";
            this.previsibilidad = "";
            this.protocolo_londres = false;
            this.respuesta_inmediata = false;
            this.algoritmOMS = false;
            this.amef = false;
            this.algoritmoWHO = false;
            this.algoritmoVACA = false;
            this.accion_inseguras = [];
            this.analisis.accion_inseguras = [];
            this.newAccionMejora = [];
            this.accion_mejoras = [];
            this.acc_mejora = {};
            this.newAccionMejora = [];
            this.accion_new = {};
            this.voluntariedad = "";
            this.identificacion_riesgo = "";
            this.getpendientes();
        },

        customFilter(items, filters, filter, headers) {
            const cf = new this.$MultiFilters(items, filters, filter, headers);

            cf.registerFilter("state", function (state, items) {
                if (state != undefined) {
                    if (state.trim() === "") return items;
                    return items.filter((item) => {
                        return item.estado_id.toLowerCase().includes(state.toLowerCase());
                    }, state);
                } else {
                    return items;
                }
            });

            cf.registerFilter("id", function (rad, items) {
                if (rad != undefined) {
                    if (rad.trim() === "") return items;
                    return items.filter((item) => {
                        let id = String(item.id);
                        return id.toLowerCase().includes(rad.toLowerCase());
                    }, rad);
                } else {
                    return items;
                }
            });

            cf.registerFilter("documento", function (doc, items) {
                if (doc != undefined) {
                    if (doc.trim() === "") return items;
                    return items.filter((item) => {
                        if (item.Num_Doc != undefined) {
                            return item.Num_Doc.toLowerCase().includes(doc.toLowerCase());
                        }
                    }, doc);
                } else {
                    return items;
                }
            });

            cf.registerFilter("suceso", function (suc, items) {
                if (suc != undefined) {
                    if (suc.trim() === "") return items;
                    return items.filter((item) => {
                        return item.nombreEvento.toLowerCase().includes(suc.toLowerCase());
                    }, suc);
                } else {
                    return items;
                }
            });

            cf.registerFilter("entidad", function (ent, items) {
                if (ent != undefined) {
                    if (ent.trim() === "") return items;
                    return items.filter((item) => {
                        if (item.entidad != undefined) {
                            return item.entidad.toLowerCase().includes(ent.toLowerCase());
                        }
                    }, ent);
                } else {
                    return items;
                }
            });

            cf.registerFilter("sede", function (sed, items) {
                if (sed != undefined) {
                    if (sed.trim() === "") return items;
                    return items.filter((item) => {
                        if (item.sede_ocurrencia != undefined) {
                            return item.sede_ocurrencia
                                .toLowerCase()
                                .includes(sed.toLowerCase());
                        }
                    }, sed);
                } else {
                    return items;
                }
            });

            cf.registerFilter("start_date", function (start_date, items) {
                if (start_date != undefined) {
                    if (start_date === null) return items;
                    return items.filter((item) => {
                        return item.created_at.split(" ")[0] >= start_date;
                    }, start_date);
                } else {
                    return items;
                }
            });

            cf.registerFilter("end_date", function (end_date, items) {
                if (end_date != undefined) {
                    if (end_date === null) return items;
                    return items.filter((item) => {
                        return item.created_at.split(" ")[0] <= end_date;
                    }, end_date);
                } else {
                    return items;
                }
            });

            return cf.runFilters();
        },

        filterState(val) {
            let newVal;
            if (val == "Pendientes") newVal = "18";
            if (val == "Asignados") newVal = "5";
            if (val == "Analizados") newVal = "46";
            if (val == "Seguimiento Plan Mejora") newVal = "48";
            if (val == "Plan de Mejora Cumplido") newVal = "49";
            this.filters = this.$MultiFilters.updateFilters(this.filters, {
                state: newVal,
            });
        },

        filterId(val) {
            this.filters = this.$MultiFilters.updateFilters(this.filters, {
                id: val,
            });
        },

        filterDocumento(val) {
            this.filters = this.$MultiFilters.updateFilters(this.filters, {
                documento: val,
            });
        },

        filterSuceso(val) {
            this.filters = this.$MultiFilters.updateFilters(this.filters, {
                suceso: val,
            });
        },

        filterEntidad(val) {
            this.filters = this.$MultiFilters.updateFilters(this.filters, {
                entidad: val,
            });
        },

        filterSede(val) {
            this.filters = this.$MultiFilters.updateFilters(this.filters, {
                sede: val,
            });
        },

        filterStartDate(val) {
            this.filters = this.$MultiFilters.updateFilters(this.filters, {
                start_date: val,
            });
        },

        filterEndDate(val) {
            this.filters = this.$MultiFilters.updateFilters(this.filters, {
                end_date: val,
            });
        },

        clearFilter() {
            for (const prop of Object.getOwnPropertyNames(this.filter)) {
                this.filter[prop] = "";
            }
            this.filterId();
            this.filterState();
            this.filterStartDate();
            this.filterEndDate();
            this.filterDocumento();
            this.filterEntidad();
            this.filterSuceso();
            this.filterSede();
        },

        ColorTd(nivel) {
            if (nivel == "ALTO") {
                return "error white--text";
            } else if (nivel == "MODERADO") {
                return "yellow black--text";
            } else if (nivel == "BAJO") {
                return "success white--text";
            }
        },

        getsedes() {
            axios.get("/api/sedeproveedore/getSedePrestador").then((res) => {
                this.allsedes = res.data;
            });
        },
        async consultarAdjunto(ruta) {
            this.search_adjunto = true;
            try {
                let adj = await AdjuntoService.get(ruta);
                let blob = new Blob([adj[1]], {
                    type: adj[0],
                });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                window.open(link.href, "_blank");
                this.search_adjunto = false;
            } catch (error) {
                this.search_adjunto = false;
            }
        },

        imprimirAnalisis(items) {
            this.preload = false;
            let pdf = {
                type: "Analisis",
                id: items,
            };
            axios
                .post("/api/pdf/print-pdf", pdf, {
                    responseType: "arraybuffer",
                })
                .then((res) => {
                    this.preload = false;
                    let blob = new Blob([res.data], {
                        type: "application/pdf",
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");
                })
                .catch((err) => {
                    this.preload = false;
                });
        },

        limpiar() {
            this.dateDesde = moment().format("YYYY-MM-DD");
            this.dateHasta = moment().format("YYYY-MM-DD");
            this.eventocerrado = null;
        },
    },
};
</script>

<style>
.search-field input::placeholder {
    color: black !important;
}

.requiere-plan {
    color: red;
    /* Cambiar el color del texto o del campo */
}

.requiere-plan-text {
    color: red;
    /* Estilo del texto adicional */
}</style>
