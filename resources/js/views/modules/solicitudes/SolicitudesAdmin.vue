<template>
    <div>
        <v-layout>
            <v-flex xs12 sm12 md12>
                <v-card>

                    <v-card>
                        <v-bottom-nav :value="true" color="transparent">
                            <v-btn color="primary" flat @click="solicitudes = true, tiposolicitud = false">
                                <span>Solicitudes</span>
                                <v-icon>style</v-icon>
                            </v-btn>
                            <v-btn color="primary" flat @click="tiposolicitud = true, solicitudes = false"
                                v-if="can('solicitudes.admintiposolicitudes')">
                                <span>Tipo Solicitudes</span>
                                <v-icon>list_alt</v-icon>
                            </v-btn>
                        </v-bottom-nav>
                    </v-card>

                    <v-card v-show="solicitudes">
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

                            <v-tab-item :value="'tab-1'">
                                <v-card flat>

                                    <v-card>
                                        <v-card-title>
                                            PENDIENTES
                                        </v-card-title>
                                        <v-card-text>
                                            <v-layout row wrap>

                                                <v-flex xs2 md2 sm2 px-2>
                                                    <v-select :items="['Pendientes', 'Gestionando']" label="Estado"
                                                        @input="filterState" v-model="form.estado">
                                                    </v-select>
                                                </v-flex>

                                                <v-flex xs2 md2 sm2 px-2>
                                                    <v-text-field label="Desde" type="date" color="primary"
                                                        @input="filterStartDate" v-model="form.desde">
                                                    </v-text-field>
                                                </v-flex>

                                                <v-flex xs2 md2 sm2 px-2>
                                                    <v-text-field label="Hasta" type="date" color="primary"
                                                        @input="filterEndDate" v-model="form.hasta">
                                                    </v-text-field>
                                                </v-flex>

                                                <v-flex xs2 md2 sm2 px-2>
                                                    <v-text-field label="Documento" v-model="form.documento"
                                                        @input="filterDocumento">
                                                    </v-text-field>
                                                </v-flex>

                                                <v-flex xs4 md4 sm4 px-2>
                                                    <v-autocomplete label="Tipo Solicitud" :items="tipoSolicitudes"
                                                        item-text="nombre" item-value="nombre" @input="filterTipo"
                                                        v-model="form.solicitud">
                                                    </v-autocomplete>
                                                </v-flex>

                                                <v-flex xs2 md2 sm2 px-2>
                                                    <v-text-field label="Radicado" @input="filterRadicado"
                                                        v-model="form.radicado">
                                                    </v-text-field>
                                                </v-flex>

                                                <v-flex xs3 md3 sm3 px-2>
                                                    <v-autocomplete label="Departamento" :items="allDepartamentos"
                                                        item-text="nombre" item-value="nombre"
                                                        @input="filterDepartamento" v-model="form.departamento">
                                                    </v-autocomplete>
                                                </v-flex>

                                                <v-flex xs2 md2 sm2 px-2>
                                                    <v-autocomplete label="Municipio" :items="allMunicipios"
                                                        item-text="nombre" item-value="nombre" @input="filterMunicipio"
                                                        v-model="form.municipio">
                                                    </v-autocomplete>
                                                </v-flex>

                                                <v-flex xs4 md4 sm4 px-2>
                                                    <v-autocomplete label="IPS" :items="allIPS" item-text="nombre"
                                                        item-value="nombre" @input="filterIPS" v-model="form.ips">
                                                    </v-autocomplete>
                                                </v-flex>

                                                <v-flex xs1 md1 sm1 px-2>
                                                    <v-tooltip top>
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn fab color="error" small v-on="on"
                                                                @click="clearFilter">
                                                                <v-icon>delete</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Quitar Filtros</span>
                                                    </v-tooltip>
                                                </v-flex>

                                                <v-flex xs12 md12 sm12>
                                                    <v-data-table :headers="headersSolicitud" :items="allPendientes"
                                                        item-key="id" :search="filters" :custom-filter="customFilter"
                                                        v-model="selected">
                                                        <template v-slot:items="props">
                                                            <td>
                                                                <v-checkbox v-model="props.selected" color="primary"
                                                                    hide-details>
                                                                    <template v-slot:label>
                                                                        <div>
                                                                            <strong class=" success--text">
                                                                                {{props.item.id}}</strong>
                                                                        </div>
                                                                    </template>
                                                                </v-checkbox>
                                                            </td>
                                                            <td>
                                                                <v-menu open-on-hover right offset-y>
                                                                    <template v-slot:activator="{ on }">
                                                                        <v-icon v-on="on">menu</v-icon>
                                                                    </template>
                                                                    <v-list
                                                                        v-if="Object.keys(props.item.gestion).length === 0">
                                                                        <v-list-tile>
                                                                            Gestión
                                                                        </v-list-tile>
                                                                    </v-list>
                                                                    <v-list v-else>
                                                                        <v-list-tile
                                                                            v-for="(data, index) in props.item.gestion"
                                                                            :key="index">
                                                                            {{ data.name }} {{ data.apellido }}
                                                                        </v-list-tile>
                                                                    </v-list>
                                                                </v-menu>
                                                            </td>
                                                            <td>{{ props.item.created_at.split('.')[0] }}</td>
                                                            <td>{{ props.item.documento }}</td>
                                                            <td>{{ props.item.pnombre + ' ' + props.item.papellido }}
                                                            </td>
                                                            <td>{{ props.item.departamento }}</td>
                                                            <td>{{ props.item.municipio }}</td>
                                                            <td>{{ props.item.ips }}</td>
                                                            <td>{{ props.item.nombreSolicitud }}</td>
                                                            <td>{{ props.item.estado }}</td>
                                                            <td>
                                                                <v-tooltip top>
                                                                    <template v-slot:activator="{ on }">
                                                                        <v-btn :loading="loading" fab color="primary"
                                                                            small v-on="on"
                                                                            @click="openPendientes(props.item)">
                                                                            <v-icon>question_answer</v-icon>
                                                                        </v-btn>
                                                                    </template>
                                                                    <span>Ver</span>
                                                                </v-tooltip>
                                                            </td>
                                                        </template>
                                                    </v-data-table>
                                                </v-flex>

                                                <v-flex xs12 md12 sm12>
                                                    <div class="text-xs-center">
                                                        <v-btn round color="warning" dark @click="openGestion"
                                                            v-if="can('solicitudes.admingestion')">
                                                            Gestión
                                                        </v-btn>
                                                    </div>
                                                </v-flex>

                                            </v-layout>
                                        </v-card-text>

                                    </v-card>

                                    <v-dialog v-model="dialogAcciones" v-if="dialogAcciones" persistent width="1000">
                                        <v-card>
                                            <v-toolbar flat color="primary" dark>
                                                <v-toolbar-title>Radicado # {{ pendientes.id }}</v-toolbar-title>
                                            </v-toolbar>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="pendientes.tipodoc" readonly
                                                                label="Tipo Documento">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="pendientes.documento" readonly
                                                                label="Documento">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3>
                                                            <v-text-field v-model="pendientes.pnombre" readonly
                                                                label="Nombre">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3>
                                                            <v-text-field v-model="pendientes.papellido" readonly
                                                                label="Apellido">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="pendientes.sapellido" readonly
                                                                label="Segundo Apellido">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="pendientes.telefono" readonly
                                                                label="Telefono">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="pendientes.celular" readonly
                                                                label="Celular">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="pendientes.direccion" readonly
                                                                label="Dirección">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3>
                                                            <v-text-field v-model="pendientes.departamento" readonly
                                                                label="Departamento">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3>
                                                            <v-text-field v-model="pendientes.municipio" readonly
                                                                label="Municipio">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs6>
                                                            <v-text-field v-model="pendientes.ips" readonly label="IPS">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs6>
                                                            <v-text-field v-model="pendientes.nombreSolicitud" readonly
                                                                label="Tipo Solicitud">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea v-model="pendientes.descripcion" readonly
                                                                label="Observación">
                                                            </v-textarea>
                                                            <span><strong>Fecha: </strong>
                                                                {{ pendientes.created_at.split('.')[0] }}</span>
                                                            <span v-if="pendientes.colaborador"><strong>Colaborador:
                                                                </strong>
                                                                {{ pendientes.colaborador }}</span>
                                                        </v-flex>
                                                        <v-flex>
                                                            <v-btn
                                                                v-for="(adjuntoR, index) in pendientes.adjuntoradicado"
                                                                :key="index" :disabled="search_adjunto">
                                                                <a @click="consultarAdjunto(adjuntoR.ruta)">
                                                                    <v-icon color="dark">mdi-cloud-upload</v-icon>
                                                                    Adjunto
                                                                    {{index+1}}
                                                                </a>
                                                            </v-btn>
                                                        </v-flex>
                                                        <v-flex xs12 v-for="(comenprivate, i) in comentarioPrivado"
                                                            :key="`res2-${i}`">
                                                            <v-flex xs12>
                                                                <v-flex xs12 v-if="i < 1">
                                                                    <v-toolbar color="primary" dark class="mb-4">
                                                                        <v-toolbar-title>Comentario privado
                                                                        </v-toolbar-title>
                                                                    </v-toolbar>
                                                                </v-flex>
                                                                <v-textarea v-model="comenprivate.motivo" readonly>
                                                                    <template v-slot:label>
                                                                        <div>
                                                                            MOTIVO
                                                                        </div>
                                                                    </template>
                                                                </v-textarea>
                                                                <span><strong>Nombre: </strong> {{ comenprivate.name }}
                                                                    {{ comenprivate.apellido }}
                                                                    <strong>Fecha: </strong>
                                                                    {{ comenprivate.created_at.split('.')[0] }}
                                                                </span>
                                                                <v-flex>
                                                                    <v-btn
                                                                        v-for="(data, index4) in comenprivate.adjuntosgestion"
                                                                        :key="index4" :disabled="search_adjunto">
                                                                        <a @click="consultarAdjunto(data.ruta)">
                                                                            <v-icon color="dark">mdi-cloud-upload
                                                                            </v-icon>Adjunto
                                                                            {{index4+1}}
                                                                        </a>
                                                                    </v-btn>
                                                                </v-flex>
                                                                <v-divider class="my-2"></v-divider>
                                                            </v-flex>
                                                        </v-flex>
                                                        <v-flex xs12 v-for="(comenpublic, i) in comentarioPublico"
                                                            :key="`res1-${i}`">
                                                            <v-flex xs12>
                                                                <v-flex xs12 v-if="i < 1">
                                                                    <v-toolbar color="primary" dark class="mb-4">
                                                                        <v-toolbar-title>Comentario</v-toolbar-title>
                                                                    </v-toolbar>
                                                                </v-flex>
                                                                <v-textarea v-model="comenpublic.motivo" readonly>
                                                                    <template v-slot:label>
                                                                        <div>
                                                                            MOTIVO
                                                                        </div>
                                                                    </template>
                                                                </v-textarea>
                                                                <span><strong v-if="comenpublic.name">Nombre: </strong>
                                                                    {{ comenpublic.name }}
                                                                    {{ comenpublic.apellido }}
                                                                    <strong v-if="!comenpublic.name">Paciente </strong>
                                                                    <strong>Fecha: </strong>
                                                                    {{ comenpublic.created_at.split('.')[0] }}
                                                                </span>
                                                                <v-flex>
                                                                    <v-btn
                                                                        v-for="(data, index3) in comenpublic.adjuntosgestion"
                                                                        :key="index3" :disabled="search_adjunto">
                                                                        <a @click="consultarAdjunto(data.ruta)">
                                                                            <v-icon color="dark">mdi-cloud-upload
                                                                            </v-icon>Adjunto
                                                                            {{index3+1}}
                                                                        </a>
                                                                    </v-btn>
                                                                </v-flex>
                                                                <v-divider class="my-2"></v-divider>
                                                            </v-flex>
                                                        </v-flex>
                                                        <v-flex xs12 v-for="(dev, i) in devoluciones" :key="`dev-${i}`">
                                                            <v-flex xs12>
                                                                <v-flex xs12 v-if="i < 1">
                                                                    <v-toolbar color="warning" dark class="mb-4">
                                                                        <v-toolbar-title>Devolución</v-toolbar-title>
                                                                    </v-toolbar>
                                                                </v-flex>
                                                                <v-textarea v-model="dev.motivo" readonly>
                                                                    <template v-slot:label>
                                                                        <div>
                                                                            MOTIVO
                                                                        </div>
                                                                    </template>
                                                                </v-textarea>
                                                                <span><strong>Nombre: </strong> {{ dev.name }}
                                                                    {{ dev.apellido }}
                                                                    <strong>Fecha: </strong>
                                                                    {{ dev.created_at.split('.')[0] }}
                                                                </span>
                                                                <v-divider class="my-2"></v-divider>
                                                            </v-flex>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-divider></v-divider>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="error" @click="dialogAcciones = false">
                                                    Cerrar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>

                                    <v-dialog v-model="dialogGestion" v-if="dialogGestion" persistent width="800">
                                        <v-card>
                                            <v-toolbar flat color="primary" dark>
                                                <v-toolbar-title>Gestión</v-toolbar-title>
                                            </v-toolbar>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12>
                                                            <v-flex xs4>
                                                                <v-combobox v-model="formGestion.tipo"
                                                                    :items="['Reasignar', 'Compartir', 'Devolver']"
                                                                    chips label="Tipo Gestión">
                                                                    <template v-slot:selection="data">
                                                                        <v-chip :key="JSON.stringify(data.item)"
                                                                            :selected="data.selected"
                                                                            :disabled="data.disabled"
                                                                            class="v-chip--select-multi"
                                                                            @click.stop="data.parent.selectedIndex = data.index"
                                                                            @input="data.parent.selectItem(data.item)">
                                                                            <v-avatar class="primary white--text">
                                                                                {{ data.item.slice(0, 1).toUpperCase() }}
                                                                            </v-avatar>
                                                                            {{ data.item }}
                                                                        </v-chip>
                                                                    </template>
                                                                </v-combobox>
                                                            </v-flex>
                                                        </v-flex>
                                                        <v-flex v-if="selected.length > 0">
                                                            <v-flex xs12>
                                                                <v-autocomplete readonly v-model="selected"
                                                                    :items="selected" item-text="id" item-value="id"
                                                                    label="Radicado" multiple>
                                                                </v-autocomplete>
                                                            </v-flex>
                                                            <v-flex xs12 v-show="formGestion.tipo != 'Devolver'">
                                                                <v-autocomplete :items="user_activos" label="Usuario"
                                                                    item-text="nombre" item-value="id"
                                                                    v-model="formGestion.usuarios" multiple />
                                                            </v-flex>
                                                        </v-flex>
                                                        <v-flex v-else>
                                                            <v-flex xs12>
                                                                <v-autocomplete :items="user_activos"
                                                                    label="Del Usuario" item-text="nombre"
                                                                    item-value="id" v-model="formGestion.delusuarios"
                                                                    multiple />
                                                            </v-flex>
                                                            <v-flex xs12 v-show="formGestion.tipo != 'Devolver'">
                                                                <v-autocomplete :items="user_activos" label="Al Usuario"
                                                                    item-text="nombre" item-value="id"
                                                                    v-model="formGestion.alusuarios" multiple />
                                                            </v-flex>
                                                        </v-flex>
                                                        <v-flex xs12 v-show="formGestion.tipo == 'Devolver'">
                                                            <v-text-field label="Motivo" v-model="formGestion.motivo"
                                                                textarea>
                                                            </v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-divider></v-divider>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="error" @click="dialogGestion = false, selected = []">
                                                    Cerrar
                                                </v-btn>
                                                <v-btn color="success" @click="saveGestion">
                                                    Guardar
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
                                            SOLUCIONADOS
                                            <v-spacer></v-spacer>
                                        </v-card-title>
                                        <v-card-text>
                                            <v-layout row wrap>

                                                <v-flex xs2 md2 sm2 px-2>
                                                    <v-text-field label="Desde" type="date" color="primary"
                                                        v-model="dateDesde">
                                                    </v-text-field>
                                                </v-flex>

                                                <v-flex xs2 md2 sm2 px-2>
                                                    <v-text-field label="Hasta" type="date" color="primary"
                                                        v-model="dateHasta">
                                                    </v-text-field>
                                                </v-flex>

                                                <v-flex xs4 sm4 px-2 class="text-xs-right">
                                                    <VAutocomplete :items="tipoSolicitudes" v-model="solicitud"
                                                        label="Tipo Solicitud" item-text="nombre" item-value="nombre" />
                                                </v-flex>

                                                <v-flex xs4 sm4 px-2>
                                                    <VAutocomplete :items="departamentos" v-model="departamento"
                                                        item-value="Nombre" item-text="Nombre" label="Departamento" />
                                                </v-flex>

                                                <v-flex xs4 sm4 px-2>
                                                    <VAutocomplete :items="municipios" v-model="municipio"
                                                        item-value="Nombre" item-text="Nombre" label="Municipio" />
                                                </v-flex>

                                                <v-flex xs4 sm4 px-2>
                                                    <v-text-field v-model="documento" label="Documento" />
                                                </v-flex>

                                                <v-flex xs1 sm1 px-2>
                                                    <v-tooltip top>
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn fab color="success" small v-on="on"
                                                                @click="getSolvedFilter()">
                                                                <v-icon>search</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Buscar</span>
                                                    </v-tooltip>
                                                </v-flex>

                                                <v-flex xs1 md1 sm1 px-2>
                                                    <v-tooltip top>
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn fab color="error" small v-on="on"
                                                                @click="clearFilterSolved">
                                                                <v-icon>delete</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Quitar Filtros</span>
                                                    </v-tooltip>
                                                </v-flex>

                                                <v-flex xs12 md12 sm12>
                                                    <v-data-table :headers="headersSolicitudSolucionadas"
                                                        :loading="loading" :items="allSolved">
                                                        <template v-slot:items="props">
                                                            <td>{{ props.item.id }}</td>
                                                            <td>{{ props.item.created_at.split('.')[0] }}</td>
                                                            <td>{{ props.item.documento }}</td>
                                                            <td>{{ props.item.pnombre + ' ' + props.item.papellido }}
                                                            </td>
                                                            <td>{{ props.item.departamento }}</td>
                                                            <td>{{ props.item.municipio }}</td>
                                                            <td>{{ props.item.ips }}</td>
                                                            <td>{{ props.item.nombreSolicitud }}</td>
                                                            <td>{{ props.item.estado }}</td>
                                                            <td>
                                                                <v-tooltip top>
                                                                    <template v-slot:activator="{ on }">
                                                                        <v-btn :loading="loading" fab color="primary"
                                                                            small v-on="on"
                                                                            @click="openSolucionados(props.item)">
                                                                            <v-icon>question_answer</v-icon>
                                                                        </v-btn>
                                                                    </template>
                                                                    <span>Ver</span>
                                                                </v-tooltip>
                                                            </td>
                                                        </template>
                                                    </v-data-table>
                                                </v-flex>

                                            </v-layout>
                                        </v-card-text>
                                    </v-card>

                                    <v-dialog v-model="dialogSolucionados" v-if="dialogSolucionados" persistent
                                        width="1000">
                                        <v-card>
                                            <v-toolbar flat color="primary" dark>
                                                <v-toolbar-title>Radicado # {{ solucionados.id }}</v-toolbar-title>
                                            </v-toolbar>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="solucionados.tipodoc" readonly
                                                                label="Tipo Documento">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="solucionados.documento" readonly
                                                                label="Documento">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3>
                                                            <v-text-field v-model="solucionados.pnombre" readonly
                                                                label="Nombre">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3>
                                                            <v-text-field v-model="solucionados.papellido" readonly
                                                                label="Apellido">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="solucionados.sapellido" readonly
                                                                label="Segundo Apellido">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="solucionados.telefono" readonly
                                                                label="Telefono">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="solucionados.celular" readonly
                                                                label="Celular">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field v-model="solucionados.direccion" readonly
                                                                label="Dirección">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3>
                                                            <v-text-field v-model="solucionados.departamento" readonly
                                                                label="Departamento">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3>
                                                            <v-text-field v-model="solucionados.municipio" readonly
                                                                label="Municipio">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs6>
                                                            <v-text-field v-model="solucionados.ips" readonly
                                                                label="IPS">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs6>
                                                            <v-text-field v-model="solucionados.nombreSolicitud"
                                                                readonly label="Tipo Solicitud">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea v-model="solucionados.descripcion" readonly
                                                                label="Observación">
                                                            </v-textarea>
                                                            <span><strong>Fecha: </strong>
                                                                {{ solucionados.created_at.split('.')[0] }}</span>
                                                            <span v-if="solucionados.colaborador"><strong>Colaborador:
                                                                </strong>
                                                                {{ solucionados.colaborador }}</span>
                                                        </v-flex>
                                                        <v-flex>
                                                            <v-btn
                                                                v-for="(adjuntoR, index) in solucionados.adjuntoradicado"
                                                                :key="index" :disabled="search_adjunto">
                                                                <a @click="consultarAdjunto(adjuntoR.ruta)">
                                                                    <v-icon color="dark">mdi-cloud-upload</v-icon>
                                                                    Adjunto
                                                                    {{index+1}}
                                                                </a>
                                                            </v-btn>
                                                            <v-divider class="my-4"></v-divider>
                                                        </v-flex>
                                                        <v-flex xs12 v-for="(comenprivate, i) in comentarioPrivado"
                                                            :key="`res2-${i}`">
                                                            <v-flex xs12>
                                                                <v-flex xs12 v-if="i < 1">
                                                                    <v-toolbar color="primary" dark class="mb-4">
                                                                        <v-toolbar-title>Comentario privado
                                                                        </v-toolbar-title>
                                                                    </v-toolbar>
                                                                </v-flex>
                                                                <v-textarea v-model="comenprivate.motivo" readonly>
                                                                    <template v-slot:label>
                                                                        <div>
                                                                            MOTIVO
                                                                        </div>
                                                                    </template>
                                                                </v-textarea>
                                                                <span><strong>Nombre: </strong> {{ comenprivate.name }}
                                                                    {{ comenprivate.apellido }}
                                                                    <strong>Fecha: </strong>
                                                                    {{ comenprivate.created_at.split('.')[0] }}
                                                                </span>
                                                                <v-flex>
                                                                    <v-btn
                                                                        v-for="(data, index4) in comenprivate.adjuntosgestion"
                                                                        :key="index4" :disabled="search_adjunto">
                                                                        <a @click="consultarAdjunto(data.ruta)">
                                                                            <v-icon color="dark">mdi-cloud-upload
                                                                            </v-icon>Adjunto
                                                                            {{index4+1}}
                                                                        </a>
                                                                    </v-btn>
                                                                </v-flex>
                                                                <v-divider class="my-2"></v-divider>
                                                            </v-flex>
                                                        </v-flex>
                                                        <v-flex xs12 v-for="(comenpublic, i) in comentarioPublico"
                                                            :key="`res1-${i}`">
                                                            <v-flex xs12>
                                                                <v-flex xs12 v-if="i < 1">
                                                                    <v-toolbar color="primary" dark class="mb-4">
                                                                        <v-toolbar-title>Comentario</v-toolbar-title>
                                                                    </v-toolbar>
                                                                </v-flex>
                                                                <v-textarea v-model="comenpublic.motivo" readonly>
                                                                    <template v-slot:label>
                                                                        <div>
                                                                            MOTIVO
                                                                        </div>
                                                                    </template>
                                                                </v-textarea>
                                                                <span><strong v-if="comenpublic.name">Nombre: </strong>
                                                                    {{ comenpublic.name }}
                                                                    {{ comenpublic.apellido }}
                                                                    <strong v-if="!comenpublic.name">Paciente </strong>
                                                                    <strong>Fecha: </strong>
                                                                    {{ comenpublic.created_at.split('.')[0] }}
                                                                </span>
                                                                <v-flex>
                                                                    <v-btn
                                                                        v-for="(data, index3) in comenpublic.adjuntosgestion"
                                                                        :key="index3" :disabled="search_adjunto">
                                                                        <a @click="consultarAdjunto(data.ruta)">
                                                                            <v-icon color="dark">mdi-cloud-upload
                                                                            </v-icon>Adjunto
                                                                            {{index3+1}}
                                                                        </a>
                                                                    </v-btn>
                                                                </v-flex>
                                                                <v-divider class="my-2"></v-divider>
                                                            </v-flex>
                                                        </v-flex>
                                                        <v-flex xs12 v-for="(dev, i) in devoluciones" :key="`dev-${i}`">
                                                            <v-flex xs12>
                                                                <v-flex xs12 v-if="i < 1">
                                                                    <v-toolbar color="warning" dark class="mb-4">
                                                                        <v-toolbar-title>Devolución</v-toolbar-title>
                                                                    </v-toolbar>
                                                                </v-flex>
                                                                <v-textarea v-model="dev.motivo" readonly>
                                                                    <template v-slot:label>
                                                                        <div>
                                                                            MOTIVO
                                                                        </div>
                                                                    </template>
                                                                </v-textarea>
                                                                <span><strong>Nombre: </strong> {{ dev.name }}
                                                                    {{ dev.apellido }}
                                                                    <strong>Fecha: </strong>
                                                                    {{ dev.created_at.split('.')[0] }}
                                                                </span>
                                                                <v-divider class="my-2"></v-divider>
                                                            </v-flex>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-flex xs12>
                                                                <v-toolbar color="success" dark class="mb-4">
                                                                    <v-toolbar-title>Respuesta</v-toolbar-title>
                                                                </v-toolbar>
                                                            </v-flex>
                                                            <v-flex v-for="(res, i) in solucionados.gestion"
                                                                :key="`res-${i}`">
                                                                <v-textarea v-model="res.motivo" readonly>
                                                                    <template v-slot:label>
                                                                        <div>
                                                                            MOTIVO
                                                                        </div>
                                                                    </template>
                                                                </v-textarea>
                                                                <span><strong>Nombre: </strong> {{ res.name }}
                                                                    {{ res.apellido }}
                                                                    <strong>Fecha: </strong>
                                                                    {{ res.created_at.split('.')[0] }}
                                                                </span>
                                                                <v-flex>
                                                                    <v-btn
                                                                        v-for="(datag, index7) in res.adjuntosgestion"
                                                                        :key="index7" :disabled="search_adjunto">
                                                                        <a @click="consultarAdjunto(datag.ruta)">
                                                                            <v-icon color="dark">mdi-cloud-upload
                                                                            </v-icon>Adjunto
                                                                            {{index7+1}}
                                                                        </a>
                                                                    </v-btn>
                                                                </v-flex>
                                                            </v-flex>
                                                            <v-divider class="my-2"></v-divider>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-divider></v-divider>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="error" @click="dialogSolucionados = false">
                                                    Cerrar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>

                                </v-card>
                            </v-tab-item>

                        </v-tabs>
                    </v-card>

                    <v-card v-show="tiposolicitud">

                        <v-dialog v-model="dialogCrearSolicitud" persistent max-width="800px">
                            <v-card>
                                <v-toolbar dark color="primary">
                                    <v-flex xs12 text-xs-center flat dark>
                                        <v-toolbar-title v-if="saveTipoSolicitud">Crear Tipo Solicitud</v-toolbar-title>
                                        <v-toolbar-title v-else>Editar Tipo Solicitud</v-toolbar-title>
                                    </v-flex>
                                </v-toolbar>
                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs12 md6 sm6>
                                                <v-text-field label="Nombre" v-model="formSolicitud.nombre"
                                                    :readonly="!saveTipoSolicitud">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 md12 sm12>
                                                <v-textarea label="Descripción" rows="2" :rules="maxDescripcion"
                                                    :counter="170" v-model="formSolicitud.descripcion"></v-textarea>
                                            </v-flex>
                                            <v-flex xs12 md4 sm4>
                                                <v-select label="Opción 1" v-model="formSolicitud.opcion1"
                                                    :items="['Medico Familia', 'Gestión', 'Usuario']"></v-select>
                                            </v-flex>
                                            <v-flex xs12 md4 sm4 v-show="formSolicitud.opcion1 == 'Medico Familia'">
                                                <v-select :items="['Gestión', 'Usuario']" label="Opción 2"
                                                    v-model="formSolicitud.opcion2">
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs12 md4 sm4 v-if="!saveTipoSolicitud">
                                                <v-select label="Estado" :items="['Activo', 'Inactivo']"
                                                    v-model="formSolicitud.estado">
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs12 md12 sm12
                                                v-show="(!saveTipoSolicitud) && (formSolicitud.opcion1 == 'Usuario' || formSolicitud.opcion2 == 'Usuario')">
                                                <v-autocomplete :items="user_activos" label="Agregar Usuario"
                                                    item-text="nombre" item-value="id" v-model="formSolicitud.usuarios"
                                                    multiple />
                                                <v-text-field v-model="searchTipoUser" prepend-icon="search"
                                                    label="Buscar">
                                                </v-text-field>
                                                <v-data-table :headers="headersUsers" :items="tipoSolicitudUsers"
                                                    class="elevation-1" :search="searchTipoUser">
                                                    <template v-slot:items="props">
                                                        <td>{{ props.item.id }}</td>
                                                        <td>{{ props.item.cedula }}</td>
                                                        <td>{{ props.item.name + ' ' + props.item.apellido}}</td>
                                                        <td>
                                                            <v-tooltip top>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn fab color="error" small v-on="on"
                                                                        @click="inactivarUserSolicitud(props.item)">
                                                                        <v-icon>delete</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Inactivar</span>
                                                            </v-tooltip>
                                                        </td>
                                                    </template>
                                                </v-data-table>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="error" @click="dialogCrearSolicitud = false">Cerrar
                                    </v-btn>
                                    <v-btn v-if="saveTipoSolicitud" @click="saveSolicitud" color="success">Guardar
                                    </v-btn>
                                    <v-btn v-else @click="updateSolicitud" color="warning">Actualizar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>

                        <v-layout row wrap>
                            <v-flex xs12 sm12 md12>
                                <v-card>
                                    <v-card-title>
                                        <v-btn color="primary"
                                            @click="dialogCrearSolicitud = true, saveTipoSolicitud = true, formSolicitud = {descripcion: ''}"
                                            round>Crear Tipo
                                            Solicitud</v-btn>
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="searchTipo" prepend-icon="search" label="Buscar">
                                        </v-text-field>
                                    </v-card-title>

                                    <v-data-table :headers="headers" :items="tipoSolicitudes" class="elevation-1"
                                        :search="searchTipo">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.id }}</td>
                                            <td>{{ props.item.nombre }}</td>
                                            <td>{{ props.item.descripcion }}</td>
                                            <td>{{ props.item.estado }}</td>
                                            <td>
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn fab color="warning" small v-on="on"
                                                            @click="openEditSolicitud(props.item)">
                                                            <v-icon>edit</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Editar</span>
                                                </v-tooltip>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-card>

                    <v-dialog v-model="preload" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Estamos procesando su información
                                <v-progress-linear indeterminate color="white" class="mb-0">
                                </v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>

                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import moment from 'moment';
    import {
        mapGetters
    } from 'vuex';
    import {
        AdjuntoService
    } from '../../../services';
    export default {
        data: () => ({
            filters: {
                state: '',
                start_date: null,
                end_date: null,
                tipo: '',
                radicado: '',
                departamento: '',
                municipio: '',
                ips: '',
                documento: '',
            },
            form: {},
            searchTipoUser: '',
            tiposolicitud: false,
            solicitudes: true,
            searchTipo: '',
            dialogCrearSolicitud: false,
            tipoSolicitudes: [],
            saveTipoSolicitud: true,
            maxDescripcion: [
                v => v.length <= 170 || 'Maximo de caracteres 170',
            ],
            formSolicitud: {
                descripcion: '',
            },
            users: [],
            tipoSolicitudUsers: [],
            allPendientes: [],
            dialogAcciones: false,
            pendientes: [],
            search_adjunto: false,
            loading: false,
            comentarioPublico: [],
            comentarioPrivado: [],
            devoluciones: [],
            search: '',
            allSolved: [],
            dialogSolucionados: false,
            selected: [],
            dialogGestion: false,
            selectedRadicado: [],
            formGestion: {
                tipo: ''
            },
            preload: false,
            headers: [{
                    text: 'id',
                    align: 'left',
                    value: 'id'
                },
                {
                    text: 'Nombre',
                    align: 'left',
                    value: 'nombre'
                },
                {
                    text: 'Descripción',
                    align: 'left',
                    value: 'descripcion'
                },
                {
                    text: 'Estado',
                    align: 'left',
                    value: 'estado'
                },
                {
                    text: '',
                    align: 'left',
                    sortable: false
                }
            ],
            headersSolicitudSolucionadas: [{
                    text: 'Radicado',
                    align: 'left',
                    value: 'id'
                },
                {
                    text: 'Fecha',
                    align: 'left',
                    value: 'created_at'
                },
                {
                    text: 'Documento',
                    align: 'left',
                    value: 'documento',
                    sortable: false
                },
                {
                    text: 'Nombre',
                    align: 'left',
                    value: 'nombre',
                    sortable: false
                },
                {
                    text: 'Departamento',
                    align: 'left',
                    value: 'departamento',
                    sortable: false
                },
                {
                    text: 'Municipio',
                    align: 'left',
                    value: 'municipio',
                    sortable: false
                },
                {
                    text: 'IPS',
                    align: 'left',
                    value: 'ips',
                    sortable: false
                },
                {
                    text: 'Solicitud',
                    align: 'left',
                    value: 'nombreSolicitud',
                    sortable: false
                },
                {
                    text: 'Estado',
                    align: 'left',
                    value: 'estado',
                    sortable: false
                },
                {
                    text: '',
                    align: 'left',
                    sortable: false
                }
            ],
            headersSolicitud: [{
                    text: 'Radicado',
                    align: 'left',
                    value: 'id'
                },
                {
                    text: '',
                    align: 'left',
                },
                {
                    text: 'Fecha',
                    align: 'left',
                    value: 'created_at'
                },
                {
                    text: 'Documento',
                    align: 'left',
                    value: 'documento',
                    sortable: false
                },
                {
                    text: 'Nombre',
                    align: 'left',
                    value: 'nombre',
                    sortable: false
                },
                {
                    text: 'Departamento',
                    align: 'left',
                    value: 'departamento',
                    sortable: false
                },
                {
                    text: 'Municipio',
                    align: 'left',
                    value: 'municipio',
                    sortable: false
                },
                {
                    text: 'IPS',
                    align: 'left',
                    value: 'ips',
                    sortable: false
                },
                {
                    text: 'Solicitud',
                    align: 'left',
                    value: 'nombreSolicitud',
                    sortable: false
                },
                {
                    text: 'Estado',
                    align: 'left',
                    value: 'estado',
                    sortable: false
                },
                {
                    text: '',
                    align: 'left',
                    sortable: false
                }
            ],
            headersUsers: [{
                    text: 'id',
                    align: 'left',
                    value: 'id'
                },
                {
                    text: 'Documento',
                    align: 'left',
                    value: 'cedula'
                },
                {
                    text: 'Nombre',
                    align: 'left',
                    value: 'name'
                },
                {
                    text: '',
                    align: 'left',
                    sortable: false
                }
            ],
            departamentos: [],
            municipios: [],
            solicitud: '',
            departamento: '',
            municipio: '',
            documento: '',
            dateDesde: moment().format('YYYY-MM-DD'),
            dateHasta: moment().format('YYYY-MM-DD'),

        }),
        computed: {
            ...mapGetters(['can']),
            user_activos() {
                let finded = [];
                this.users.forEach(u => {
                    if (u.estado == 1) {
                        finded.push(u)
                    }
                });
                return finded;
            },
            allDepartamentos() {
                return this.allPendientes.map(ap => {
                    return {
                        nombre: ap.departamento,
                    };
                });
            },
            allMunicipios() {
                return this.allPendientes.map(ap => {
                    return {
                        nombre: ap.municipio,
                    };
                });
            },
            allIPS() {
                return this.allPendientes.map(ap => {
                    return {
                        nombre: ap.ips,
                    };
                });
            },
        },
        mounted() {
            this.getTipos();
            this.getUser();
            this.getPendientes();
            this.fetchDepartamentos()
            this.fetchMunicipios()
        },
        methods: {
            fetchDepartamentos() {
                axios.get('/api/departamento/all')
                    .then((res) => {
                        this.departamentos = res.data
                    })
            },

            fetchMunicipios() {
                axios.get('/api/municipio/all')
                    .then(res => {
                        this.municipios = res.data
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

            getTipos() {
                axios.get('/api/solicitud/allTipoSolicitud')
                    .then(res => {
                        this.tipoSolicitudes = res.data
                    });
            },

            getTiposUsers(solicitud) {
                axios.get('/api/solicitud/getSolicitudUsers/' + solicitud)
                    .then(res => {
                        this.tipoSolicitudUsers = res.data
                    });
            },

            openEditSolicitud(value) {
                this.formSolicitud = value
                this.dialogCrearSolicitud = true
                this.saveTipoSolicitud = false
                this.getTiposUsers(value.id)
            },

            saveSolicitud() {
                if (this.formSolicitud.nombre == null || this.formSolicitud.descripcion == '' ||
                    this.formSolicitud.opcion1 == null) {
                    this.$alerError('Debe llenar los campos obligatorios')
                    return;
                }
                if (this.formSolicitud.opcion1 == 'Medico Familia' && this.formSolicitud.opcion2 == null) {
                    this.$alerError('Debe ingresar la opción 2')
                    return;
                }
                axios.post('/api/solicitud/createSolicitud', this.formSolicitud)
                    .then(res => {
                        this.getTipos();
                        this.$alerSuccess(res.data.message);
                        this.dialogCrearSolicitud = false
                    }).catch(err => {
                        this.$alerError(err.response.data.message)
                    })
            },

            updateSolicitud() {
                if (this.formSolicitud.descripcion == '') {
                    this.$alerError('Debe llenar los campos obligatorios')
                    return;
                }
                if (this.formSolicitud.opcion1 == 'Medico Familia' && this.formSolicitud.opcion2 == null) {
                    this.$alerError('Debe ingresar la opción 2')
                    return;
                }
                axios.post('/api/solicitud/updateSolicitud', this.formSolicitud)
                    .then(res => {
                        this.getTipos();
                        this.$alerSuccess(res.data.message);
                        this.dialogCrearSolicitud = false
                    })
            },

            inactivarUserSolicitud(user) {
                axios.post('/api/solicitud/inactivarUserSolicitud/' + user.id, {
                        solicitud_id: this.formSolicitud.id
                    })
                    .then(res => {
                        this.getTipos();
                        this.$alerSuccess(res.data.message);
                        this.dialogCrearSolicitud = false
                    })
            },

            getPendientes() {
                axios.post('/api/solicitud/pendientes')
                    .then(res => {
                        this.allPendientes = res.data;
                    })
            },

            customFilter(items, filters, filter, headers) {
                const cf = new this.$MultiFilters(items, filters, filter, headers);

                cf.registerFilter('state', function (state, items) {
                    if (state != undefined) {
                        if (state.trim() === '') return items;
                        return items.filter(item => {
                            return item.estado_id.toLowerCase().includes(state.toLowerCase());
                        }, state);
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

                cf.registerFilter('tipo', function (tipo, items) {
                    if (tipo != undefined) {
                        if (tipo.trim() === '') return items;
                        return items.filter(item => {
                            return item.nombreSolicitud.toLowerCase().includes(tipo.toLowerCase());
                        }, tipo);
                    } else {
                        return items;
                    }
                });

                cf.registerFilter('radicado', function (rad, items) {
                    if (rad != undefined) {
                        if (rad.trim() === '') return items;
                        return items.filter(item => {
                            let radicado = String(item.id)
                            return radicado.toLowerCase().includes(rad.toLowerCase());
                        }, rad);
                    } else {
                        return items;
                    }
                });

                cf.registerFilter('departamento', function (dep, items) {
                    if (dep != undefined) {
                        if (dep.trim() === '') return items;
                        return items.filter(item => {
                            return item.departamento.toLowerCase().includes(dep.toLowerCase());
                        }, dep);
                    } else {
                        return items;
                    }
                });

                cf.registerFilter('municipio', function (mun, items) {
                    if (mun != undefined) {
                        if (mun.trim() === '') return items;
                        return items.filter(item => {
                            return item.municipio.toLowerCase().includes(mun.toLowerCase());
                        }, mun);
                    } else {
                        return items;
                    }
                });

                cf.registerFilter('ips', function (ip, items) {
                    if (ip != undefined) {
                        if (ip.trim() === '') return items;
                        return items.filter(item => {
                            return item.ips.toLowerCase().includes(ip.toLowerCase());
                        }, ip);
                    } else {
                        return items;
                    }
                });

                cf.registerFilter('documento', function (doc, items) {
                    if (doc != undefined) {
                        if (doc.trim() === '') return items;
                        return items.filter(item => {
                            return item.documento.toLowerCase().includes(doc.toLowerCase());
                        }, doc);
                    } else {
                        return items;
                    }
                });

                return cf.runFilters();
            },

            filterState(val) {
                let newVal
                if (val == 'Pendientes') newVal = '18'
                if (val == 'Gestionando') newVal = '19'
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    state: newVal
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

            filterTipo(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    tipo: val
                });
            },

            filterRadicado(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    radicado: val
                });
            },

            filterDepartamento(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    departamento: val
                });
            },

            filterMunicipio(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    municipio: val
                });
            },

            filterIPS(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    ips: val
                });
            },

            filterDocumento(val) {
                this.filters = this.$MultiFilters.updateFilters(this.filters, {
                    documento: val
                });
            },

            clearFilter() {
                for (const prop of Object.getOwnPropertyNames(this.form)) {
                    this.form[prop] = '';
                }
                this.filterIPS();
                this.filterState()
                this.filterStartDate()
                this.filterEndDate()
                this.filterTipo()
                this.filterRadicado()
                this.filterDepartamento()
                this.filterMunicipio()
                this.filterDocumento()
            },

            async openPendientes(item) {
                this.loading = true
                this.pendientes = item
                await this.comentariosPublico(item.id);
                await this.comentariosPrivado(item.id);
                await this.devolucion(item.id);
                this.loading = false
                this.dialogAcciones = true
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

            async comentariosPublico(item) {
                await axios.post(`/api/solicitud/showcomentariosPublicos`, {
                        radicaciononline_id: item
                    })
                    .then(res => {
                        this.comentarioPublico = res.data
                    })
            },

            async comentariosPrivado(item) {
                await axios.post(`/api/solicitud/showcomentariosPrivados`, {
                        radicaciononline_id: item
                    })
                    .then(res => {
                        this.comentarioPrivado = res.data
                    })
            },

            async devolucion(item) {
                await axios.post(`/api/solicitud/showDevoluciones`, {
                        radicaciononline_id: item
                    })
                    .then(res => {
                        this.devoluciones = res.data
                    })
            },

            getSolvedFilter() {
                this.loading = true;
                axios.post('/api/solicitud/solucionados', {
                        desde: this.dateDesde,
                        hasta: this.dateHasta,
                        solicitud: this.solicitud,
                        departamento: this.departamento,
                        municipio: this.municipio,
                        documento: this.documento
                    })
                    .then(res => {
                        this.loading = false
                        this.allSolved = res.data;
                    });
            },

            async openSolucionados(item) {
                this.loading = true
                this.solucionados = item
                await this.comentariosPublico(item.id);
                await this.comentariosPrivado(item.id);
                await this.devolucion(item.id);
                this.loading = false
                this.dialogSolucionados = true
            },

            openGestion() {
                this.dialogGestion = true
                for (const prop of Object.getOwnPropertyNames(this.formGestion)) {
                    this.formGestion[prop] = null;
                }
            },

            saveGestion() {
                if (this.formGestion.tipo == '' || this.formGestion.tipo == null ||
                    this.formGestion.tipo == undefined) {
                    this.$alerError('Debe seleccionar tipo de gestión');
                    return
                }
                if (this.formGestion.tipo == 'Devolver') {
                    if (this.formGestion.motivo == '' || this.formGestion.motivo == null ||
                        this.formGestion.motivo == undefined) {
                        this.$alerError('Debe ingresar un motivo');
                        return
                    }
                }
                this.preload = true
                let formData = new FormData();
                formData.append('tipo', this.formGestion.tipo)
                formData.append('motivo', this.formGestion.motivo)
                for (let i = 0; i < this.selected.length; i++) {
                    formData.append(`radicaciononline_id[]`, this.selected[i].id);
                }
                if (Array.isArray(this.formGestion.usuarios)) {
                    for (let i = 0; i < this.formGestion.usuarios.length; i++) {
                        formData.append(`usuario_id[]`, this.formGestion.usuarios[i]);
                    }
                }
                if (Array.isArray(this.formGestion.delusuarios)) {
                    for (let i = 0; i < this.formGestion.delusuarios.length; i++) {
                        formData.append(`delusuario_id[]`, this.formGestion.delusuarios[i]);
                    }
                }
                if (Array.isArray(this.formGestion.alusuarios)) {
                    for (let i = 0; i < this.formGestion.alusuarios.length; i++) {
                        formData.append(`alusuario_id[]`, this.formGestion.alusuarios[i]);
                    }
                }
                axios.post(`/api/solicitud/saveGestion`, formData)
                    .then(res => {
                        this.getPendientes();
                        this.selected = []
                        this.dialogGestion = false
                        this.preload = false
                        swal({
                            title: "¡Ha gestionado la solicitud con exito!",
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

            clearFilterSolved() {
                this.dateDesde = moment().format('YYYY-MM-DD')
                this.dateHasta = moment().format('YYYY-MM-DD')
                this.solicitud = ''
                this.departamento = ''
                this.municipio = ''
                this.documento = ''
            }
        }
    }

</script>
