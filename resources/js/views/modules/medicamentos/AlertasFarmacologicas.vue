<template>
    <v-card>
        <v-container grid-list-md fluid>
            <v-layout wrap>
                <v-dialog v-model="dialogEditMensaje" persistent width="800">
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-toolbar-title>Editar Mensaje</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-text-field v-model="mensajesEdit.titulo" label="Titulo">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-textarea v-model="mensajesEdit.Mensaje" label="Mensaje">
                                        </v-textarea>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" dark @click="closedialogEditMensaje()">
                                Cerrar
                            </v-btn>
                            <v-btn color="primary" dark @click="updateMensaje(mensajesEdit.id)">
                                Actualizar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogEditTipo" persistent width="800">
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-toolbar-title>Editar Tipo de Alertas</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-text-field v-model="tipoEdit.Nombre" label="Descripcion">
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" dark @click="closedialogEditipos()">
                                Cerrar
                            </v-btn>
                            <v-btn color="primary" dark @click="updateTipo(tipoEdit.id)">
                                Actualizar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogEditAlerta" persistent width="800">
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-toolbar-title>Editar Alerta</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-autocomplete label="Mensaje*" :items="allMensajes" required
                                            item-text="titulo" item-value="id" hint="Este campo es requerido"
                                            v-model="alertaEdit.mensaje_id">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-autocomplete label="Interaccion*" :items="allprincipioActivo" required
                                            item-text="medicamento" item-value="medicamento"
                                            hint="Este campo es requerido" v-model="alertaEdit.interaccion">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-autocomplete v-model="alertaEdit.tipo_id" :items="alltipos"
                                            item-text="Nombre" item-value="id" label="Tipo de Alerta*"
                                            hint="Este campo es requerido">
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" dark @click="closedialogEditAlerta()">
                                Cerrar
                            </v-btn>
                            <v-btn color="primary" dark @click="updateAlerta(alertaEdit.id)">
                                Actualizar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogEditAlertaMedicamento" persistent width="800">
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-toolbar-title>Editar Alerta Por Medicamento</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-autocomplete label="Mensaje*" :items="allMensajes" required
                                            item-text="titulo" item-value="id" hint="Este campo es requerido"
                                            v-model="alertaEdit.mensaje_id">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-autocomplete v-model="alertaEdit.tipo_id" :items="alltipos"
                                            item-text="Nombre" item-value="id" label="Tipo de Alerta*"
                                            hint="Este campo es requerido">
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" dark @click="closedialogEditAlertaMedicamento()">
                                Cerrar
                            </v-btn>
                            <v-btn color="primary" dark @click="updateAlertaMedicamento(alertaEdit.id)">
                                Actualizar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogEditPrincipal" persistent width="800">
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-toolbar-title>Editar Principal</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-autocomplete label="Principal*" :items="allprincipioActivo" required
                                            item-text="medicamento" item-value="medicamento"
                                            hint="Este campo es requerido" v-model="principalEdit.principal">
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" dark @click="closedialogEditPrincipal()">
                                Cerrar
                            </v-btn>
                            <v-btn color="primary" dark @click="updatePrincipal(principalEdit.id)">
                                Actualizar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogEditCodesumi" persistent width="800">
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-toolbar-title>Editar Medicamento/Producto</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-autocomplete label="Medicamento/Producto*" :items="allCodesumi" required
                                            item-text="medicamento" item-value="medicamento_id"
                                            hint="Este campo es requerido" v-model="codesumiEdit.medicamento_id">
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" dark @click="closedialogEditCodesumi()">
                                Cerrar
                            </v-btn>
                            <v-btn color="primary" dark @click="updateCodesumi(codesumiEdit.id)">
                                Actualizar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-flex xs6>
                    <v-card-text class="px-0">
                        <v-card>
                            <v-card-title>
                                <div class="text-xs-center">
                                    <v-dialog v-model="dialogMensaje" persistent max-width="900px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark v-on="on">
                                                Nuevo Mensaje
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title class="primary white--text">
                                                <span class="headline">Crear Mensaje</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12>
                                                            <v-text-field label="Titulo*" required
                                                                hint="Este campo es requerido" v-model="nombremensaje">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea label="Mensaje*" hint="Este campo es requerido"
                                                                v-model="descripcionmensaje">
                                                            </v-textarea>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red darken-3" dark @click="closeMensaje()">Cancelar
                                                </v-btn>
                                                <v-btn color="green darken-3" dark @click="createMensaje()">Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </div>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search1" append-icon="search" label="Search" single-line
                                    hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="mensajes" :items="allMensajes" :search="search1">
                                <template v-slot:items="props">
                                    <td class="text-xs-center">{{ props.item.id }}</td>
                                    <td class="text-xs-center">{{ props.item.titulo }}</td>
                                    <td class="text-xs-center">{{ props.item.Mensaje }}</td>
                                    <td class="text-xs-center">{{ props.item.name }}</td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == 1">
                                        <v-chip color="info" text-color="white">{{ props.item.estados }}</v-chip>
                                    </td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == 2">
                                        <v-chip color="red" text-color="white">{{ props.item.estados }}</v-chip>
                                    </td>
                                    <td class="text-xs-center">
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark color="warning" small v-on="on"
                                                    @click="editMensaje(props.item)">
                                                    <v-icon>edit</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Editar</span>
                                        </v-tooltip>
                                        <v-tooltip top v-if="props.item.Estado_id == 1">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark color="error" small v-on="on"
                                                    @click="disableMensaje(props.item.id)">
                                                    <v-icon>person_add_disabled</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Inactivar</span>
                                        </v-tooltip>
                                        <v-tooltip top v-if="props.item.Estado_id == 2">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark color="success" v-on="on" small
                                                    @click="enableMensaje(props.item.id)">
                                                    <v-icon>person_add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Activar</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-card-text>
                </v-flex>
                <v-flex xs6>
                    <v-card-text class="px-0">
                        <v-card>
                            <v-card-title>
                                <div class="text-xs-center">
                                    <v-dialog v-model="dialogtipo" persistent max-width="900px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark v-on="on">
                                                Nuevo Tipo
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title class="primary white--text">
                                                <span class="headline">Crear Tipo de Alerta</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12>
                                                            <v-text-field label="Nombre*" required
                                                                hint="Este campo es requerido" v-model="nombreTipo">
                                                            </v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red darken-3" dark @click="closeTipo()">Cancelar
                                                </v-btn>
                                                <v-btn color="green darken-3" dark @click="createTipo()">Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </div>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search2" append-icon="search" label="Search" single-line
                                    hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="tipos" :items="alltipos" :search="search2">
                                <template v-slot:items="props">
                                    <td class="text-xs-center">{{ props.item.id }}</td>
                                    <td class="text-xs-center">{{ props.item.Nombre }}</td>
                                    <td class="text-xs-center">{{ props.item.name }}</td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == 1">
                                        <v-chip color="info" text-color="white">{{ props.item.estados }}</v-chip>
                                    </td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == 2">
                                        <v-chip color="red" text-color="white">{{ props.item.estados }}</v-chip>
                                    </td>
                                    <td class="text-xs-center">
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark color="warning" small v-on="on"
                                                    @click="editTipos(props.item)">
                                                    <v-icon>edit</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Editar</span>
                                        </v-tooltip>
                                        <v-tooltip top v-if="props.item.Estado_id == 1">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark color="error" small v-on="on"
                                                    @click="disableTipos(props.item.id)">
                                                    <v-icon>person_add_disabled</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Inactivar</span>
                                        </v-tooltip>
                                        <v-tooltip top v-if="props.item.Estado_id == 2">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark color="success" v-on="on" small
                                                    @click="enableTipo(props.item.id)">
                                                    <v-icon>person_add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Activar</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-card-text>
                </v-flex>
                <v-flex xs12>
                    <v-card-text class="px-0">
                        <v-card>
                            <v-card-title>
                                <div class="text-xs-center">
                                    <v-dialog v-model="dialogPrincipal" persistent max-width="900px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark v-on="on" @click="getPrincipioActivo()">
                                                Nuevo Principal
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title class="primary white--text">
                                                <span class="headline">Crear Principal</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12>
                                                            <v-autocomplete label="Principal*"
                                                                :items="allprincipioActivo" required
                                                                item-text="medicamento" item-value="medicamento"
                                                                hint="Este campo es requerido" v-model="principal">
                                                            </v-autocomplete>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red darken-3" dark @click="closePrincipal()">Cancelar
                                                </v-btn>
                                                <v-btn color="green darken-3" dark @click="createPrincipal()">Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </div>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search4" append-icon="search" label="Search" single-line
                                    hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="headerPrincipal" :items="allPrincipal" :search="search4">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.principal }}</td>
                                    <td>{{ props.item.name }}</td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == 1">
                                        <v-chip color="info" text-color="white">{{ props.item.estados }}</v-chip>
                                    </td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == 2">
                                        <v-chip color="red" text-color="white">{{ props.item.estados }}</v-chip>
                                    </td>
                                    <td>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn dark color="warning" small v-on="on"
                                                    @click="editPrincipal(props.item)">
                                                    <v-icon>edit</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Editar</span>
                                        </v-tooltip>
                                        <v-tooltip top v-if="props.item.Estado_id == 1">
                                            <template v-slot:activator="{ on }">
                                                <v-btn dark color="error" small v-on="on"
                                                    @click="disablePrincipal(props.item.id)">
                                                    <v-icon>person_add_disabled</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Inactivar</span>
                                        </v-tooltip>
                                        <v-tooltip top v-if="props.item.Estado_id == 2">
                                            <template v-slot:activator="{ on }">
                                                <v-btn dark color="success" v-on="on" small
                                                    @click="enablePrincipal(props.item.id)">
                                                    <v-icon>add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Activar</span>
                                        </v-tooltip>
                                        <v-btn color="primary" dark @click="abrirModal(props.item.id)">
                                            Nueva Alerta
                                        </v-btn>
                                    </td>
                                </template>
                                <template v-slot:no-results>
                                    <v-alert :value="true" color="error" icon="warning">
                                        Your search for "{{ search }}" found no results.
                                    </v-alert>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-card-text>
                </v-flex>
                <div class="text-xs-center">
                    <v-dialog v-model="dialogAlerta" persistent max-width="2000px">
                        <v-card>
                            <v-card-title class="primary white--text">
                                <span class="headline">Crear Nueva Alerta</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12>
                                            <v-autocomplete label="Mensaje*" :items="allMensajes" item-text="titulo"
                                                item-value="id" hint="Este campo es requerido" v-model="mensaje_id">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-autocomplete label="Interaccion" :items="allprincipioActivo" required
                                                item-text="medicamento" item-value="medicamento" v-model="interaccion">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-autocomplete v-model="tipo_id" :items="alltipos" item-text="Nombre"
                                                item-value="id" label="Tipo de Alerta*" hint="Este campo es requerido">
                                            </v-autocomplete>
                                        </v-flex>
                                    </v-layout>
                                    <v-btn color="green darken-3" dark @click="createAlerta()">Agregar
                                    </v-btn>
                                    <v-card-title>
                                        Historico de Alertas
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="search4" append-icon="search" label="Search" single-line
                                            hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headersAlertas" :items="allHistoricoAlertas"
                                        :search="search4">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.id }}</td>
                                            <td class="text-xs-left">{{ props.item.interaccion }}</td>
                                            <td class="text-xs-left">{{ props.item.Mensaje }}</td>
                                            <td class="text-xs-right">{{ props.item.tipoAlerta }}</td>
                                            <td class="text-xs-right">{{ props.item.name }}</td>
                                            <td class="text-xs-center" v-if="props.item.Estado_id == 1">
                                                <v-chip color="info" text-color="white">{{ props.item.estados }}
                                                </v-chip>
                                            </td>
                                            <td class="text-xs-center" v-if="props.item.Estado_id == 2">
                                                <v-chip color="red" text-color="white">{{ props.item.estados }}</v-chip>
                                            </td>
                                            <td class="text-xs-center">
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn fab dark color="warning" small v-on="on"
                                                            @click="editAlerta(props.item)">
                                                            <v-icon>edit</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Editar</span>
                                                </v-tooltip>
                                                <v-tooltip top v-if="props.item.Estado_id == 1">
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn fab dark color="error" small v-on="on"
                                                            @click="disableHistoricoAlertas(props.item.id)">
                                                            <v-icon>person_add_disabled</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Inactivar</span>
                                                </v-tooltip>
                                                <v-tooltip top v-if="props.item.Estado_id == 2">
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn fab dark color="success" v-on="on" small
                                                            @click="enableHistoricoAlertas(props.item.id)">
                                                            <v-icon>person_add</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Activar</span>
                                                </v-tooltip>
                                            </td>
                                        </template>
                                        <template v-slot:no-results>
                                            <v-alert :value="true" color="error" icon="warning">
                                                Your search for "{{ search }}" found no results.
                                            </v-alert>
                                        </template>
                                    </v-data-table>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="red darken-3" dark @click="closeAlerta(),dialogAlerta = false">Cerrar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </div>
                <v-flex xs12>
                    <v-card-text class="px-0">
                        <v-card>
                            <v-card-title>
                                <div class="text-xs-center">
                                    <v-dialog v-model="dialogCodesumi" persistent max-width="900px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark v-on="on" @click="getCodesumi()">
                                                Nuevo Medicamento / Producto
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title class="primary white--text">
                                                <span class="headline">Crear Medicamento / Producto</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12>
                                                            <v-autocomplete label="Medicamento / Producto*"
                                                                :items="allCodesumi" required item-text="medicamento"
                                                                item-value="medicamento_id" hint="Este campo es requerido"
                                                                v-model="codesumi">
                                                            </v-autocomplete>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red darken-3" dark @click="closeCodesumi()">Cancelar
                                                </v-btn>
                                                <v-btn color="green darken-3" dark @click="createCodesumi()">Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </div>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search5" append-icon="search" label="Search" single-line
                                    hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="headerCodesumi" :items="allCodesumiAlertas" :search="search5">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.medicamento }}</td>
                                    <td>{{ props.item.name }}</td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == 1">
                                        <v-chip color="info" text-color="white">{{ props.item.estados }}</v-chip>
                                    </td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == 2">
                                        <v-chip color="red" text-color="white">{{ props.item.estados }}</v-chip>
                                    </td>
                                    <td>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn dark color="warning" small v-on="on"
                                                    @click="editCodesumi(props.item)">
                                                    <v-icon>edit</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Editar</span>
                                        </v-tooltip>
                                        <v-tooltip top v-if="props.item.Estado_id == 1">
                                            <template v-slot:activator="{ on }">
                                                <v-btn dark color="error" small v-on="on"
                                                    @click="disableCodesumi(props.item.id)">
                                                    <v-icon>person_add_disabled</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Inactivar</span>
                                        </v-tooltip>
                                        <v-tooltip top v-if="props.item.Estado_id == 2">
                                            <template v-slot:activator="{ on }">
                                                <v-btn dark color="success" v-on="on" small
                                                    @click="enableCodesumi(props.item.id)">
                                                    <v-icon>add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Activar</span>
                                        </v-tooltip>
                                        <v-btn color="primary" dark @click="abrirModalMedicamento(props.item.id)">
                                            Nueva Alerta
                                        </v-btn>
                                    </td>
                                </template>
                                <template v-slot:no-results>
                                    <v-alert :value="true" color="error" icon="warning">
                                        Your search for "{{ search }}" found no results.
                                    </v-alert>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-card-text>
                </v-flex>
                <div class="text-xs-center">
                    <v-dialog v-model="dialogAlerta" persistent max-width="2000px">
                        <v-card>
                            <v-card-title class="primary white--text">
                                <span class="headline">Crear Nueva Alerta</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12>
                                            <v-autocomplete label="Mensaje*" :items="allMensajes" item-text="titulo"
                                                item-value="id" hint="Este campo es requerido" v-model="mensaje_id">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-autocomplete label="Interaccion" :items="allprincipioActivo" required
                                                item-text="medicamento" item-value="medicamento" v-model="interaccion">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-autocomplete v-model="tipo_id" :items="alltipos" item-text="Nombre"
                                                item-value="id" label="Tipo de Alerta*" hint="Este campo es requerido">
                                            </v-autocomplete>
                                        </v-flex>
                                    </v-layout>
                                    <v-btn color="green darken-3" dark @click="createAlerta()">Agregar
                                    </v-btn>
                                    <v-card-title>
                                        Historico de Alertas
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="search4" append-icon="search" label="Search" single-line
                                            hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headersAlertas" :items="allHistoricoAlertas"
                                        :search="search4">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.id }}</td>
                                            <td class="text-xs-left">{{ props.item.interaccion }}</td>
                                            <td class="text-xs-left">{{ props.item.Mensaje }}</td>
                                            <td class="text-xs-right">{{ props.item.tipoAlerta }}</td>
                                            <td class="text-xs-right">{{ props.item.name }}</td>
                                            <td class="text-xs-center" v-if="props.item.Estado_id == 1">
                                                <v-chip color="info" text-color="white">{{ props.item.estados }}
                                                </v-chip>
                                            </td>
                                            <td class="text-xs-center" v-if="props.item.Estado_id == 2">
                                                <v-chip color="red" text-color="white">{{ props.item.estados }}</v-chip>
                                            </td>
                                            <td class="text-xs-center">
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn fab dark color="warning" small v-on="on"
                                                            @click="editAlerta(props.item)">
                                                            <v-icon>edit</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Editar</span>
                                                </v-tooltip>
                                                <v-tooltip top v-if="props.item.Estado_id == 1">
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn fab dark color="error" small v-on="on"
                                                            @click="disableHistoricoAlertas(props.item.id)">
                                                            <v-icon>person_add_disabled</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Inactivar</span>
                                                </v-tooltip>
                                                <v-tooltip top v-if="props.item.Estado_id == 2">
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn fab dark color="success" v-on="on" small
                                                            @click="enableHistoricoAlertas(props.item.id)">
                                                            <v-icon>person_add</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Activar</span>
                                                </v-tooltip>
                                            </td>
                                        </template>
                                        <template v-slot:no-results>
                                            <v-alert :value="true" color="error" icon="warning">
                                                Your search for "{{ search }}" found no results.
                                            </v-alert>
                                        </template>
                                    </v-data-table>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="red darken-3" dark @click="closeAlerta(),dialogAlerta = false">Cerrar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </div>
                <div class="text-xs-center">
                    <v-dialog v-model="dialogAlertaMedicamento" persistent max-width="2000px">
                        <v-card>
                            <v-card-title class="primary white--text">
                                <span class="headline">Crear Nueva Alerta Por Medicamento</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12>
                                            <v-autocomplete label="Mensaje*" :items="allMensajes" item-text="titulo"
                                                item-value="id" hint="Este campo es requerido" v-model="mensaje_id">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-autocomplete v-model="tipo_id" :items="alltipos" item-text="Nombre"
                                                item-value="id" label="Tipo de Alerta*" hint="Este campo es requerido">
                                            </v-autocomplete>
                                        </v-flex>
                                    </v-layout>
                                    <v-btn color="green darken-3" dark @click="createAlertaMedicamento()">Agregar
                                    </v-btn>
                                    <v-card-title>
                                        Historico de Alertas
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="search4" append-icon="search" label="Search" single-line
                                            hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headersAlertasMedicamentos" :items="allHistoricoAlertas"
                                        :search="search4">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.id }}</td>
                                            <td class="text-xs-left">{{ props.item.Mensaje }}</td>
                                            <td class="text-xs-right">{{ props.item.tipoAlerta }}</td>
                                            <td class="text-xs-right">{{ props.item.name }}</td>
                                            <td class="text-xs-center" v-if="props.item.Estado_id == 1">
                                                <v-chip color="info" text-color="white">{{ props.item.estados }}
                                                </v-chip>
                                            </td>
                                            <td class="text-xs-center" v-if="props.item.Estado_id == 2">
                                                <v-chip color="red" text-color="white">{{ props.item.estados }}</v-chip>
                                            </td>
                                            <td class="text-xs-center">
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn fab dark color="warning" small v-on="on"
                                                            @click="editAlertaMedicamento(props.item)">
                                                            <v-icon>edit</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Editar</span>
                                                </v-tooltip>
                                                <v-tooltip top v-if="props.item.Estado_id == 1">
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn fab dark color="error" small v-on="on"
                                                            @click="disableHistoricoAlertasMedicamento(props.item.id)">
                                                            <v-icon>person_add_disabled</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Inactivar</span>
                                                </v-tooltip>
                                                <v-tooltip top v-if="props.item.Estado_id == 2">
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn fab dark color="success" v-on="on" small
                                                            @click="enableHistoricoAlertasMedicamento(props.item.id)">
                                                            <v-icon>person_add</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Activar</span>
                                                </v-tooltip>
                                            </td>
                                        </template>
                                        <template v-slot:no-results>
                                            <v-alert :value="true" color="error" icon="warning">
                                                Your search for "{{ search }}" found no results.
                                            </v-alert>
                                        </template>
                                    </v-data-table>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="red darken-3" dark @click="closeAlerta(),dialogAlertaMedicamento = false">
                                    Cerrar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </div>
            </v-layout>
        </v-container>
    </v-card>
</template>
<script>
    import swal from 'sweetalert';
    export default {
        data() {
            return {
                mensajesEdit: [],
                tipoEdit: [],
                alertaEdit: [],
                principalEdit: [],
                codesumiEdit: [],
                dialogEditMensaje: false,
                dialogEditTipo: false,
                dialogEditAlerta: false,
                dialogEditAlertaMedicamento: false,
                dialogEditPrincipal: false,
                dialogEditCodesumi: false,
                dialogMensaje: false,
                dialogtipo: false,
                dialogAlerta: false,
                dialogAlertaMedicamento: false,
                dialogPrincipal: false,
                dialogCodesumi: false,
                search1: '',
                search2: '',
                search3: '',
                search4: '',
                search5: '',
                allMensajes: [],
                alltipos: [],
                allprincipioActivo: [],
                allCodesumi: [],
                allAlertas: [],
                allPrincipal: [],
                allCodesumiAlertas: [],
                allHistoricoAlertas: [],
                nombremensaje: "",
                descripcionmensaje: "",
                nombreTipo: "",
                mensaje_id: "",
                principal: "",
                codesumi: "",
                interaccion: "",
                tipo_id: "",
                alertas_id: "",
                mensajes: [{
                        text: 'id',
                        align: 'center',
                        value: 'id'
                    },
                    {
                        text: 'Titulo',
                        value: 'titulo',
                        align: 'center'
                    },
                    {
                        text: 'Mensaje',
                        value: 'Mensaje',
                        align: 'center'
                    },
                    {
                        text: 'Usuario Creador',
                        value: 'name',
                        align: 'center'
                    },
                    {
                        text: 'Estado',
                        value: 'estado',
                        align: 'center'
                    },
                    {
                        text: 'Acciones',
                        align: 'center'
                    }
                ],
                tipos: [{
                        text: 'id',
                        align: 'center',
                        value: 'id'
                    },
                    {
                        text: 'Tipo de Alerta',
                        value: 'Nombre',
                        align: 'center'
                    },
                    {
                        text: 'Usuario Creador',
                        value: 'name',
                        align: 'center'
                    },
                    {
                        text: 'Estado',
                        value: 'estado',
                        align: 'center'
                    },
                    {
                        text: 'Acciones',
                        align: 'center'
                    }
                ],
                headersAlertasMedicamentos: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Mensaje',
                        value: 'Mensaje',
                        align: 'left'
                    },
                    {
                        text: 'Tipo de Alerta',
                        value: 'tipoAlerta',
                        align: 'center'
                    },
                    {
                        text: 'Usuario Creador',
                        value: 'name',
                        align: 'center'
                    },
                    {
                        text: 'Estado',
                        value: 'Estado_id',
                        align: 'center'
                    },
                    {
                        text: 'Acciones',
                        align: 'center'
                    }
                ],
                headersAlertas: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'interaccion',
                        value: 'interaccion',
                        align: 'left'
                    },
                    {
                        text: 'Mensaje',
                        value: 'Mensaje',
                        align: 'left'
                    },
                    {
                        text: 'Tipo de Alerta',
                        value: 'tipoAlerta',
                        align: 'left'
                    },
                    {
                        text: 'Usuario Creador',
                        value: 'name',
                        align: 'left'
                    },
                    {
                        text: 'Estado',
                        value: 'Estado_id',
                        align: 'left'
                    },
                    {
                        text: 'Acciones',
                        align: 'left'
                    }
                ],

                headerPrincipal: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Principal',
                        value: 'principal',
                        align: 'left'
                    },
                    {
                        text: 'Usuario Creador',
                        value: 'name',
                        align: 'left'
                    },
                    {
                        text: 'Estado',
                        value: 'Estado_id',
                        align: 'center'
                    },
                    {
                        text: 'Acciones',
                        align: 'left'
                    }
                ],

                headerCodesumi: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Medicamento/Producto',
                        value: 'medicamento',
                        align: 'left'
                    },
                    {
                        text: 'Usuario Creador',
                        value: 'name',
                        align: 'left'
                    },
                    {
                        text: 'Estado',
                        value: 'Estado_id',
                        align: 'center'
                    },
                    {
                        text: 'Acciones',
                        align: 'left'
                    }
                ],
            }
        },
        mounted() {
            this.getMensajes();
            this.getTiposAlertas();
            this.getPrincipal();
            this.getCodesumiAlerta();
        },
        methods: {

            editMensaje(item) {
                this.dialogEditMensaje = true
                this.mensajesEdit = item
            },
            editTipos(item) {
                this.dialogEditTipo = true
                this.tipoEdit = item
            },
            editPrincipal(item) {
                this.getPrincipioActivo()
                this.dialogEditPrincipal = true
                this.principalEdit = item
            },
            editCodesumi(item) {
                this.getCodesumi()
                this.dialogEditCodesumi = true
                this.codesumiEdit = item
            },
            editAlerta(item) {
                this.getPrincipioActivo()
                this.dialogEditAlerta = true
                this.alertaEdit = item
                this.alertaEdit.mensaje_id = parseInt(item.mensaje_id)
                this.alertaEdit.tipo_id = parseInt(item.tipo_id)
            },
            editAlertaMedicamento(item) {
                this.getPrincipioActivo()
                this.dialogEditAlertaMedicamento = true
                this.alertaEdit = item
                this.alertaEdit.mensaje_id = parseInt(item.mensaje_id)
                this.alertaEdit.tipo_id = parseInt(item.tipo_id)
            },
            closedialogEditMensaje() {
                this.getMensajes();
                this.dialogEditMensaje = false
            },
            closedialogEditipos() {
                this.getTiposAlertas();
                this.dialogEditTipo = false
            },
            closedialogEditAlerta() {
                this.dialogEditAlerta = false
            },
            closedialogEditAlertaMedicamento() {
                this.dialogEditAlertaMedicamento = false
            },
            closedialogEditPrincipal() {
                this.getPrincipal();
                this.dialogEditPrincipal = false
            },
            closedialogEditCodesumi() {
                this.dialogEditCodesumi = false
            },
            updateMensaje(id) {
                this.mensajesEdit.id = id;
                axios.post("/api/detallearticulo/updateMensaje", this.mensajesEdit)
                    .then(res => {
                        this.getMensajes();
                        this.dialogEditMensaje = false
                        swal({
                            title: "¡Actualizo Mensaje con exito!",
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
                    });
            },
            updateTipo(id) {
                this.tipoEdit.id = id;
                axios.post("/api/detallearticulo/updateTipo", this.tipoEdit)
                    .then(res => {
                        this.getTiposAlertas();
                        this.dialogEditTipo = false
                        swal({
                            title: "¡Actualizo Tipo de Alerta con exito!",
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
                    });
            },

            updateAlertaMedicamento(id) {
                this.alertaEdit.id = id;
                axios.post("/api/detallearticulo/updateAlertaMedicamento", this.alertaEdit)
                    .then(res => {
                        this.getHistorico(this.alertas_id);
                        this.dialogEditAlertaMedicamento = false
                        swal({
                            title: "¡Actualizo con exito!",
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
                    });
            },

            updateAlerta(id) {
                this.alertaEdit.id = id;
                axios.post("/api/detallearticulo/updateAlerta", this.alertaEdit)
                    .then(res => {
                        this.getPrincipal();
                        this.dialogEditAlerta = false
                        swal({
                            title: "¡Actualizo Molecula Principal con exito!",
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
                    });
            },

            updatePrincipal(id) {
                this.principalEdit.id = id;
                axios.post("/api/detallearticulo/updatePrincipal", this.principalEdit)
                    .then(res => {
                        this.getPrincipal();
                        this.dialogEditPrincipal = false
                        swal({
                            title: "¡Actualizo Molecula Principal con exito!",
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
                    });
            },

            updateCodesumi(id) {
                this.codesumiEdit.id = id;
                axios.post("/api/detallearticulo/updateCodesumi", this.codesumiEdit)
                    .then(res => {
                        this.getCodesumiAlerta();
                        this.dialogEditCodesumi = false
                        swal({
                            title: "¡Actualizo Medicamento / Producto con exito!",
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
                    });
            },

            enableMensaje(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez el Mensaje esté habilitado, Aparecerea la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get(`/api/detallearticulo/enableMensaje/` + id)
                            .then(res => {
                                this.getMensajes();
                                this.getAlertas();
                                swal("Mensaje Habilitado!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            enableTipo(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez el Tipo de alerta esté habilitado, Aparecerea la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get(`/api/detallearticulo/enableTipo/` + id)
                            .then(res => {
                                this.getTiposAlertas();
                                this.getAlertas();
                                swal("Tipo de Alerta Habilitado!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            enableCodesumi(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez el Medicamento / Producto esté habilitado, Aparecerea la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get(`/api/detallearticulo/enableCodesumi/` + id)
                            .then(res => {
                                this.getCodesumiAlerta();
                                swal("Medicamento / Producto Habilitada!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            enablePrincipal(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez la Molecula Principal esté habilitado, Aparecerea la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get(`/api/detallearticulo/enablePrincipal/` + id)
                            .then(res => {
                                this.getPrincipal();
                                swal("Molecula Principal Habilitada!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            enableHistoricoAlertasMedicamento(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez la alerta esté habilitado, Aparecerea la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get(`/api/detallearticulo/enableHistoricoAlertasMedicamento/` + id)
                            .then(res => {
                                this.getHistorico(this.alertas_id)
                                swal("Alerta Habilitada!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => {
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
                            });
                    }
                })
            },

            enableHistoricoAlertas(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez la alerta esté habilitado, Aparecerea la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get(`/api/detallearticulo/enableHistoricoAlertas/` + id)
                            .then(res => {
                                this.getHistorico(this.alertas_id)
                                swal("Alerta Habilitada!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => {
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
                            });
                    }
                })
            },

            disableMensaje(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez el mensaje esté deshabilitado, no aparecera la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get("/api/detallearticulo/disableMensaje/" + id)
                            .then(res => {
                                this.getMensajes();

                                swal("Mensaje Deshabilitado!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            disableTipos(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez el tipo esté deshabilitado, no aparecera la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get("/api/detallearticulo/disableTipos/" + id)
                            .then(res => {
                                this.getTiposAlertas();

                                swal("Tipo de Area Deshabilitado!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            disableCodesumi(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez el medicamento / producto esté deshabilitada, no aparecera la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get("/api/detallearticulo/disableCodesumi/" + id)
                            .then(res => {
                                this.getCodesumiAlerta();
                                swal("Medicamento / Producto Deshabilitada!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            disablePrincipal(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez la molecula principal esté deshabilitada, no aparecera la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get("/api/detallearticulo/disablePrincipal/" + id)
                            .then(res => {
                                this.getPrincipal();
                                swal("Molecula Principal Deshabilitada!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            disableHistoricoAlertasMedicamento(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez la alerta esté deshabilitada, no aparecera la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get("/api/detallearticulo/disableHistoricoAlertasMedicamento/" + id)
                            .then(res => {
                                this.getHistorico(this.alertas_id)
                                swal("La alerta esta Deshabilitada!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            disableHistoricoAlertas(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez la alerta esté deshabilitada, no aparecera la alerta farmacologica!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get("/api/detallearticulo/disableHistoricoAlertas/" + id)
                            .then(res => {
                                this.getHistorico(this.alertas_id)
                                swal("La alerta esta Deshabilitada!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            closeMensaje() {
                this.dialogMensaje = false
                this.nombremensaje = ""
                this.descripcionmensaje = ""
            },

            closeTipo() {
                this.dialogtipo = false
                this.nombreTipo = ""
            },

            closeAlerta() {
                this.nombreTipo = "",
                    this.mensaje_id = "",
                    this.interaccion = "",
                    this.tipo_id = ""
            },

            closePrincipal() {
                this.dialogPrincipal = false,
                    this.principal = ""
            },

            closeCodesumi() {
                this.dialogCodesumi = false,
                    this.codesumi = ""
            },

            createTipo() {
                let formData = new FormData();
                formData.append('Nombre', this.nombreTipo)
                axios.post(`/api/detallearticulo/createTipo`, formData)
                    .then(res => {
                        this.closeTipo();
                        this.getTiposAlertas();
                        swal({
                            title: "¡Nuevo Tipo de Alerta creado con exito!",
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
            },

            createAlertaMedicamento() {
                let formData = new FormData();
                formData.append('mensaje_id', this.mensaje_id)
                formData.append('tipo_id', this.tipo_id)
                formData.append('alertas_id', this.alertas_id)
                axios.post(`/api/detallearticulo/createAlertaMedicamento`, formData)
                    .then(res => {
                        this.getHistorico(this.alertas_id);
                        this.closeAlerta();
                        swal({
                            title: "¡Nueva Alerta creada con exito!",
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
            },

            createAlerta() {
                let formData = new FormData();
                formData.append('mensaje_id', this.mensaje_id)
                formData.append('interaccion', this.interaccion)
                formData.append('tipo_id', this.tipo_id)
                formData.append('alertas_id', this.alertas_id)
                axios.post(`/api/detallearticulo/createAlerta`, formData)
                    .then(res => {
                        this.getHistorico(this.alertas_id);
                        this.closeAlerta();
                        swal({
                            title: "¡Nueva Alerta creada con exito!",
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
            },
            createMensaje() {
                let formData = new FormData();
                formData.append('titulo', this.nombremensaje)
                formData.append('mensaje', this.descripcionmensaje)
                axios.post(`/api/detallearticulo/createMensaje`, formData)
                    .then(res => {
                        this.closeMensaje();
                        this.getMensajes();
                        swal({
                            title: "¡Nuevo Mensaje creada con exito!",
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
            },

            createPrincipal() {
                let formData = new FormData();
                formData.append('principal', this.principal)
                axios.post(`/api/detallearticulo/createPrincipal`, formData)
                    .then(res => {
                        this.closePrincipal();
                        this.getPrincipal();
                        swal({
                            title: "¡Nuevo Principal creado con exito!",
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
            },

            getMensajes() {
                axios.get('/api/detallearticulo/getMensajes')
                    .then(res => {
                        this.allMensajes = res.data;
                    });
            },

            getTiposAlertas() {
                axios.get('/api/detallearticulo/getTiposAlertas')
                    .then(res => {
                        this.alltipos = res.data;
                    });
            },

            getPrincipioActivo() {
                axios.get('/api/detallearticulo/getPrincipioActivo')
                    .then(res => {
                        this.allprincipioActivo = res.data;
                    });
            },

            getPrincipal() {
                axios.get('/api/detallearticulo/getPrincipal')
                    .then(res => {
                        this.allPrincipal = res.data;
                    });
            },

            getHistorico(id) {
                axios.get('/api/detallearticulo/getHistorico/' + id)
                    .then(res => {
                        this.allHistoricoAlertas = res.data;
                    });
            },

            getCodesumi() {
                axios.get('/api/detallearticulo/getCodesumi')
                    .then(res => {
                        this.allCodesumi = res.data;
                    });
            },

            getCodesumiAlerta() {
                axios.get('/api/detallearticulo/getCodesumiAlerta')
                    .then(res => {
                        this.allCodesumiAlertas = res.data;
                    });
            },

            createCodesumi() {
                let formData = new FormData();
                formData.append('codesumi', this.codesumi)
                axios.post(`/api/detallearticulo/createCodesumi`, formData)
                    .then(res => {
                        this.closeCodesumi();
                        this.getCodesumiAlerta();
                        swal({
                            title: "¡Nuevo Medicamento / Producto creado con exito!",
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
            },

            abrirModal(items) {
                this.alertas_id = items
                this.dialogAlerta = true
                this.getPrincipioActivo();
                this.getHistorico(this.alertas_id)
            },
            abrirModalMedicamento(items) {
                this.alertas_id = items
                this.dialogAlertaMedicamento = true
                this.getPrincipioActivo();
                this.getHistorico(this.alertas_id)
            },

        }
    }

</script>
