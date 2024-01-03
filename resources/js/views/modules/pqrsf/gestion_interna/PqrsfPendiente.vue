<template>

    <div>
        <v-card>
            <v-card-title>
                <v-layout row wrap>
                    <span>
                        PENDIENTES
                    </span>
                </v-layout>
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                </v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :loading="loading" :items="allSlope" :search="search">
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.cc }}</td>
                    <td>{{ props.item.Solicitud }}</td>
                    <td>{{ props.item.IPS }}</td>
                    <td>{{ props.item.Email }}</td>
                    <td>
                        <v-chip :class="semaforoTd(props.item)">{{ `${props.item.diasHabiles} DÍA(S)` }}</v-chip>
                    </td>
                    <td>{{ props.item.created_at.split(' ')[0] }}</td>
                    <td>{{ props.item.Canal }}</td>
                    <td>
                        <v-btn :disabled="loading" @click="manage(props.item),
                            pqrsfSubcategories(props.item),
                            pqrsfDetallearticulos(props.item),
                            pqrsfCups(props.item),
                            pqrsfArea(props.item),
                            pqrsfIps(props.item),
                            pqrsfEmpleados(props.item)" color="blue" flat icon>
                            <v-icon>library_books</v-icon>
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

        <v-dialog v-model="gestion" fullscreen hide-overlay persistent transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-flex xs12 text-xs-center flat dark>
                        <v-toolbar-title>GESTIÓN</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-layout row wrap>
                                    <v-flex xs3 v-show="pqrsf.cc">
                                        <v-text-field v-model="pqrsf.cc" readonly label="CEDULA">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3 v-show="pqrsf.doc">
                                        <v-text-field v-model="pqrsf.doc" readonly label="CEDULA">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-text-field v-model="pqrsf.Edad_Cumplida" readonly label="EDAD">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-text-field v-model="pqrsf.Nombre" readonly label="NOMBRE">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-text-field v-model="pqrsf.Apellido" readonly label="APELLIDO">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field v-model="pqrsf.Telefono" readonly label="TELEFONO">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-text-field v-model="pqrsf.Email" readonly label="EMAIL">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-text-field v-model="pqrsf.IPS" readonly label="IPS">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs5>
                                        <v-autocomplete v-model="subcategorias" :items="allSubcategorias"
                                            item-text="Nombre" item-value="id" chips label="SUBCATEGORIA" multiple>
                                            <template v-slot:selection="data">
                                                <v-chip :selected="data.selected" close class="chip--select-multi"
                                                    @input="remove_subcategoria(data.item.id)">
                                                    {{ data.item.Nombre.substring(0,15) }}
                                                </v-chip>
                                            </template>
                                            <template v-slot:item="data">
                                                <template v-if="typeof data.item.Nombre !== 'object'">
                                                    <v-list-tile-content v-text="data.item.Nombre">
                                                    </v-list-tile-content>
                                                </template>
                                                <template v-else>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                        <v-list-tile-sub-title v-html="data.item">
                                                        </v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </template>
                                            </template>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn v-on="on" color="success" fab small dark
                                                    @click="saveSubcategories(pqrsf)">
                                                    <v-icon>add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Agregar Subcategoria</span>
                                        </v-tooltip>
                                    </v-flex>
                                    <v-flex xs5>
                                        <v-autocomplete v-model="detallearticulos" :items="allDetallearticulos"
                                            item-text="Producto" item-value="id" chips label="MEDICAMENTOS" multiple>
                                            <template v-slot:selection="data">
                                                <v-chip :selected="data.selected" close class="chip--select-multi"
                                                    @input="remove_detallearticulo(data.item.id)">
                                                    {{ data.item.Producto.substring(0,15) }}
                                                </v-chip>
                                            </template>
                                            <template v-slot:item="data">
                                                <template v-if="typeof data.item.Producto !== 'object'">
                                                    <v-list-tile-content v-text="data.item.Producto">
                                                    </v-list-tile-content>
                                                </template>
                                                <template v-else>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                        <v-list-tile-sub-title v-html="data.item">
                                                        </v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </template>
                                            </template>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn v-on="on" color="success" fab small dark
                                                    @click="saveDetallearticulos(pqrsf)">
                                                    <v-icon>add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Agregar Medicamento</span>
                                        </v-tooltip>
                                    </v-flex>
                                    <v-flex xs5>
                                        <v-autocomplete v-model="cups" :items="allCups" item-text="Nombre"
                                            item-value="id" chips label="SERVICIOS" multiple>
                                            <template v-slot:selection="data">
                                                <v-chip :selected="data.selected" close class="chip--select-multi"
                                                    @input="remove_cup(data.item.id)">
                                                    {{ data.item.Nombre.substring(0,15) }}
                                                </v-chip>
                                            </template>
                                            <template v-slot:item="data">
                                                <template v-if="typeof data.item.Nombre !== 'object'">
                                                    <v-list-tile-content v-text="data.item.Nombre">
                                                    </v-list-tile-content>
                                                </template>
                                                <template v-else>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                        <v-list-tile-sub-title v-html="data.item">
                                                        </v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </template>
                                            </template>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn v-on="on" color="success" fab small dark
                                                    @click="saveCups(pqrsf)">
                                                    <v-icon>add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Agregar Servicio</span>
                                        </v-tooltip>
                                    </v-flex>
                                    <v-flex xs5>
                                        <v-autocomplete v-model="areas" :items="allAreas" item-text="Nombre"
                                            item-value="id" chips label="AREA" multiple>
                                            <template v-slot:selection="data">
                                                <v-chip :selected="data.selected" close class="chip--select-multi"
                                                    @input="remove_area(data.item.id)">
                                                    {{ data.item.Nombre.substring(0,15) }}
                                                </v-chip>
                                            </template>
                                            <template v-slot:item="data">
                                                <template v-if="typeof data.item.Nombre !== 'object'">
                                                    <v-list-tile-content v-text="data.item.Nombre">
                                                    </v-list-tile-content>
                                                </template>
                                                <template v-else>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                        <v-list-tile-sub-title v-html="data.item">
                                                        </v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </template>
                                            </template>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn v-on="on" color="success" fab small dark
                                                    @click="saveAreas(pqrsf)">
                                                    <v-icon>add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Agregar Area</span>
                                        </v-tooltip>
                                    </v-flex>
                                    <v-flex xs5>
                                        <v-autocomplete v-model="sedes" :items="allSedes" item-text="Nombre"
                                            item-value="id" chips label="IPS AFECTADA" multiple>
                                            <template v-slot:selection="data">
                                                <v-chip :selected="data.selected" close class="chip--select-multi"
                                                    @input="remove_sede(data.item.id)">
                                                    {{ data.item.Nombre.substring(0,15) }}
                                                </v-chip>
                                            </template>
                                            <template v-slot:item="data">
                                                <template v-if="typeof data.item.Nombre !== 'object'">
                                                    <v-list-tile-content v-text="data.item.Nombre">
                                                    </v-list-tile-content>
                                                </template>
                                                <template v-else>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                        <v-list-tile-sub-title v-html="data.item">
                                                        </v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </template>
                                            </template>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn v-on="on" color="success" fab small dark @click="saveIps(pqrsf)">
                                                    <v-icon>add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Agregar IPS</span>
                                        </v-tooltip>
                                    </v-flex>
                                    <v-flex xs12 v-show="activarHide">
                                    </v-flex>
                                    <v-flex xs5 v-show="activar">
                                        <v-autocomplete v-model="empleados" :items="allEmpleados" item-text="Nombre"
                                            item-value="id" chips label="EMPLEADO" multiple>
                                            <template v-slot:selection="data">
                                                <v-chip :selected="data.selected" close class="chip--select-multi"
                                                    @input="remove_empleado(data.item.id)">
                                                    {{ data.item.Nombre.substring(0,15) }}
                                                </v-chip>
                                            </template>
                                            <template v-slot:item="data">
                                                <template v-if="typeof data.item.Nombre !== 'object'">
                                                    <v-list-tile-content v-text="data.item.Nombre">
                                                    </v-list-tile-content>
                                                </template>
                                                <template v-else>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                        <v-list-tile-sub-title v-html="data.item">
                                                        </v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </template>
                                            </template>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs1 v-show="activar">
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn v-on="on" color="success" fab small dark
                                                    @click="saveEmpleado(pqrsf)">
                                                    <v-icon>add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Agregar Empleado</span>
                                        </v-tooltip>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrsfSubcategorias.length > 0">
                                        <v-subheader>SUBCATEGORIA ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrsfSubcategorias" :key="index">
                                                <v-chip label :selected="data.selected" close
                                                    @input="delete_subcategoria(data.id, pqrsf)">
                                                    {{data.Nombre}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrsfDetallearticulo.length > 0">
                                        <v-subheader>MEDICAMENTO ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrsfDetallearticulo" :key="index">
                                                <v-chip label :selected="data.selected" close
                                                    @input="delete_detallearticulo(data.id, pqrsf)">
                                                    {{data.Producto}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrsfcups.length > 0">
                                        <v-subheader>SERVICIO ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrsfcups" :key="index">
                                                <v-chip label :selected="data.selected" close
                                                    @input="delete_cups(data.id, pqrsf)">
                                                    {{data.Nombre}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrareas.length > 0">
                                        <v-subheader>AREA ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrareas" :key="index">
                                                <v-chip label :selected="data.selected" close
                                                    @input="delete_areas(data.id, pqrsf)">
                                                    {{data.Nombre}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrips.length > 0">
                                        <v-subheader>IPS ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrips" :key="index">
                                                <v-chip label :selected="data.selected" close
                                                    @input="delete_ips(data.id, pqrsf)">
                                                    {{data.Nombre}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs12 v-if="pqrempleados.length > 0">
                                        <v-subheader>EMPLEADO ACTUAL</v-subheader>
                                        <v-item-group multiple>
                                            <v-item v-for="(data, index) in pqrempleados" :key="index">
                                                <v-chip label :selected="data.selected" close
                                                    @input="delete_empleados(data.id, pqrsf)">
                                                    {{data.Nombre}}
                                                </v-chip>
                                            </v-item>
                                        </v-item-group>
                                        <v-divider class="my-2"></v-divider>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-select :items="tipoSolicitud" @input="CampoEmpleado(pqrsf.Tiposolicitud)"
                                            v-model="pqrsf.Tiposolicitud" label="TIPO DE SOLICITUD"></v-select>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-select :items="priorizaciones" v-model="pqrsf.Priorizacion"
                                            label="PRIORIZACIÓN"></v-select>
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-select :items="['Si', 'No']" v-model="pqrsf.Reabierta" label="REABIERTA">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-select :items="atributodecalidad" v-model="pqrsf.Atr_calidad"
                                            label="ATRIBUTO DE CALIDAD"></v-select>
                                    </v-flex>
                                    <v-flex xs12
                                        v-if="pqrsf.Tiposolicitud == 'Queja' || pqrsf.Tiposolicitud == 'Reclamo'">
                                        <v-autocomplete :items="deberes" label="DEBER" v-model="pqrsf.deber">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs11
                                        v-if="pqrsf.Tiposolicitud == 'Queja' || pqrsf.Tiposolicitud == 'Reclamo'">
                                        <v-autocomplete :items="derechos" label="DERECHO" v-model="pqrsf.derecho">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn v-on="on" color="primary" fab small dark @click="update(pqrsf)">
                                                    <v-icon>cached</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Actualizar</span>
                                        </v-tooltip>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-textarea label="DESCRIPCIÓN DEL CASO" v-model="pqrsf.Descripcion" readonly>
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 v-for="(GesPqr, index) in pqrsf.gestion_pqrsfs" :key="`r-${index}`">
                                        <v-flex xs12 md12 v-if="GesPqr.name">
                                            <span> <strong>Nombre:</strong> {{ GesPqr.name }}
                                                {{ GesPqr.apellido }}</span>
                                        </v-flex>
                                        <v-flex>
                                            <v-btn v-for="(data, index) in GesPqr.adjuntos_pqrsf" :key="index">
                                                <a :href="`${data.Ruta}`" target="_blank" style="text-decoration:none">
                                                    <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                    {{index+1}}
                                                </a>
                                            </v-btn>
                                        </v-flex>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="warning" @click="dialogCancel = true">Anular</v-btn>
                    <v-btn color="purple" dark @click="assign(pqrsf)">Asignar</v-btn>
                    <v-btn color="success" @click="solve(pqrsf)">Solucionar</v-btn>
                    <v-btn color="error" @click="closeGestion()">Salir</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogCancel" persistent max-width="600px">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title>ANULAR</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-textarea label="MOTIVO" v-model="motivoCancel">
                                        </v-textarea>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="cancel(pqrsf)">Anular</v-btn>
                    <v-btn color="error" @click="closeCancel()">Salir</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogAssign" persistent max-width="600px">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title>ASIGNAR</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <span>Seleccione a quien quiera asignar</span>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-autocomplete v-model="permiso" :items="allPermissions" item-text="name"
                                            item-value="id" chips multiple>
                                            <template v-slot:selection="data">
                                                <v-chip :selected="data.selected" close class="chip--select-multi"
                                                    @input="remove_permission(data.item.id)">
                                                    {{ data.item.name }}
                                                </v-chip>
                                            </template>
                                            <template v-slot:item="data">
                                                <template v-if="typeof data.item.name !== 'object'">
                                                    <v-list-tile-content v-text="data.item.name"></v-list-tile-content>
                                                </template>
                                                <template v-else>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                                        <v-list-tile-sub-title v-html="data.item">
                                                        </v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </template>
                                            </template>
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="saveAssign(pqrsf)">Asignar</v-btn>
                    <v-btn color="error" @click="closeAssign()">Salir</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogSolve" persistent max-width="600px">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title>SOLUCIONAR</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-textarea label="SOLUCIÓN" v-model="motivoSolve">
                                        </v-textarea>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs12>
                                <input id="adjuntos" multiple ref="adjuntos" type="file" />
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" :disabled="loading" @click="saveSolve(pqrsf)">Solucionar</v-btn>
                    <v-btn color="error" :disabled="loading" @click="closeSolve()">Salir</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogProcesando" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su solicitud
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

    </div>
</template>
<script>
    import Swal from 'sweetalert2';
    export default {
        name: "PqrsfPendiente",
        data() {
            return {
                search: '',
                headers: [{
                        text: 'Radicado',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Cedula',
                        align: 'left',
                        value: 'cc'
                    },
                    {
                        text: 'Tipo de solicitud',
                        align: 'left',
                        value: 'Solicitud'
                    },
                    {
                        text: 'IPS',
                        align: 'left',
                        value: 'IPS'
                    },
                    {
                        text: 'Correo',
                        align: 'left',
                        value: 'Email'
                    },
                    {
                        text: 'Semaforo',
                        align: 'left',
                        value: 'diasHabiles'
                    },
                    {
                        text: 'Fecha Generado',
                        align: 'left',
                        value: 'Creado'
                    },
                    {
                        text: 'Canal',
                        align: 'left',
                        value: 'Canal'
                    },
                    {
                        text: '',
                        align: 'Right'
                    }
                ],
                allSlope: [],
                gestion: false,
                pqrsf: [],
                tipoSolicitud: ['Petición', 'Queja', 'Reclamo', 'Sugerencia', 'Felicitación', 'Información',
                    'Solicitud','Deberes'
                ],
                priorizaciones: ['Urgente', 'Prioritaria', 'No Prioritaria'],
                atributodecalidad: ['Accesibilidad', 'Oportunidad', 'Seguridad', 'Pertinencia', 'Continuidad',
                    'Satisfacción del Usuario', 'No Aplica'
                ],
                dialogCancel: false,
                motivoCancel: '',
                loading: false,
                allSubcategorias: [],
                subcategorias: [],
                pqrsfSubcategorias: [],
                allDetallearticulos: [],
                detallearticulos: [],
                pqrsfDetallearticulo: [],
                allCups: [],
                cups: [],
                pqrsfcups: [],
                dialogAssign: false,
                allPermissions: [],
                permiso: [],
                empleados: [],
                allEmpleados: [],
                allSedes: [],
                sedes: [],
                allAreas: [],
                areas: [],
                pqrareas: [],
                pqrips: [],
                pqrempleados: [],
                activar: false,
                activarHide: true,
                dialogSolve: false,
                motivoSolve: '',
                dialogProcesando: false,
                deberes: [
                    'Con las citas, tratamientos terapéuticos, indicaciones médicas, horarios de atención, uso de canales definidos por la organización, normas del sistema de salud y de la institución.',
                    'Con el pago de las cuotas moderadoras y uso racional de los recursos.',
                    'Cumplir con las recomendaciones formuladas por el personal de salud y las recibidas en los programas de Promoción de la salud y Prevención de la enfermedad.',
                    'Con los horarios de atención, visitas y servicios programados.',
                    'Con la Política De No Uso De Tabaco, Licor y Sustancias Psicoactivas Dentro De Las Institución.',
                    'Cuidar y hacer uso racional de los recursos de salud, de las instalaciones, dotaciones y equipamiento entregado.',
                    'Actuar de buena fe ante el sistema de salud',
                    'Procurar el cuidado integral de su salud y de su comunidad',
                    'Suministrar de manera verás, clara, oportuna y suficiente la información que se requiera para efectos del servicio.',
                    'Informar a los responsables y autoridades de todo acto o hecho que afecte el sistema.',
                    'Tratar con respeto al personal que le brinda los servicios de salud durante la atención y a los demás usuarios.',
                    'Expresar con respeto las necesidades tomadas con relación al proceso de atención, ideología de otros.',
                    'Actuar frente al sistema y sus actores de buena fe',
                    'Actuar de manera solidaria ante las situaciones que pongan en peligro la salud o la vida de las personas',
                    'No Aplica'
                ],
                derechos: [
                    'Servicios de salud y tecnologías que permitan la promoción, prevención, diagnóstico, tratamiento, rehabilitación y paliación de forma rápida, oportuna y continua, sin que sean interrumpidos por razones administrativas o económicas.',
                    'Atenciones seguras que permita maximizar los resultados y minimizar los riesgos durante la atención.',
                    'Atenciones con profesionales con experiencia y conocimientos amplios.',
                    'A recibir en todas las etapas de la atención un trato digno, de igualdad, incluyente y equitativo, sin importar su orientación sexual, etnia, edad, idioma, religión creencia, cultura, costumbres, preferencias políticas, origen, condición social o económica.',
                    'A recibir atención en lugares agradables, aseados con un ambiente amigable.',
                    'A recibir la atención en urgencias con oportunidad, sin exigir documento o cancelación de copago previo alguno, ni sea obligatoria la atención en una institución prestadores de servicios de salud de la red definida por la EPS.',
                    'Al no cobro de cuotas moderadoras o copagos cuando se presenten enfermedades catastróficas, o de alto costo.',
                    'Recibir información sobre los canales para presentar las peticiones, quejas, reclamos, solicitudes (PQRSD), así como a recibir respuesta oportuna y de fondo.',
                    'Sobre el estado de salud, diagnósticos, pronóstico, tratamientos, riesgo de estos, mediante un lenguaje claro, comprensible, oportuno, así como lo relacionado con los trámites administrativos,garantizando al usuario que comprenda la información.',
                    'A recibir educación sobre la preservación, el mejoramiento y la promoción de la salud.',
                    'Ser informado oportunamente por su médico tratante sobre la existencia de objeción de conciencia debidamente motivada, en los casos de los procedimientos de interrupción voluntaria del embarazo en las circunstancias despenalizadas por la Corte Constitucional, o de eutanasia. El paciente tiene derecho a que sea gestionada la continuidad de la atención inmediata y eficaz con un profesional no objetor.',
                    'Ser informado sobre los costos de los servicios prestados.',
                    'A obtener la información (cómo funciona el sistema de salud y sus derechos).',
                    'A recibir por escrito las razones por las cuales no se autoriza el servicio, conocer específicamente cuál es la IPS que tiene la obligación del servicio, y recibir acompañamiento para asegurar el goce efectivo de sus derechos.',
                    'Tratamientos o negarse a estos.',
                    'Escoger el profesional o IPS que lo atenderá dentro de las opciones ofertadas.',
                    'Participar en investigaciones o atención por personal en formación.',
                    'A morir dignamente.',
                    'A la donación y transfusión sanguínea.',
                    'A recibir o rehusar apoyo espiritual, moral cualquiera que sea el culto religioso que profese.',
                    'A vivir con dignidad el final de su ciclo vital, permitiéndole la toma decisiones sobre cómo enfrentar la muerte, incluyendo cuidado integral del proceso de muerte o el cuidado paliativo.',
                    'A que los representantes legales del paciente mayor de edad, en caso de inconciencia o incapacidad para decidir, consientan, disientan o rechacen actividades (en el marco del mejor interés).',
                    'Aceptar o rechazar previa información de todos los procedimientos que se le debe realizar.',
                    'Derecho a la total confidencialidad de la historia clínica y datos personales relacionados con el proceso de atención, a excepción de las exigencias legales que lo hagan imprescindible.',
                    'Derecho a tener privacidad al momento de la atención y de lo que se derive de esta.',
                    'A que, en caso de ser adolescente, deba reconocérseles el derecho frente a la reserva y confidencialidad de su historia clínica en el ejercicio de sus derechos sexuales y reproductivos.',
                    'Las inquietudes o dudas, deseos, necesidades y decisiones relacionadas con el proceso de atención',
                    'Ser escuchados y resolver dudas, sobre el proceso de atención',
                    'A suscribir un documento de voluntad anticipada como previsión de no poder tomar decisiones en el futuro, en el cual declare de forma libre, consciente e informada su voluntad respecto a la doma de decisiones sobre el cuidado general de la salud y el cuerpo.',
                    'No Aplica'
                ]
            }
        },
        mounted() {
            this.getSlopeFilter();
            this.showSubcategories();
            this.showDetallearticulo();
            this.showCup();
            this.permissionspqrsf();
            this.getEmpleadosActive();
            this.getSedeproveedore();
            this.getAreas();
        },
        methods: {
            permissionspqrsf() {
                axios.get('/api/pqrsf/permissionpqrsf').then(res => {
                    this.allPermissions = res.data;
                });
            },
            getEmpleadosActive() {
                axios.get('/api/pqrsf/allactive').then(res => {
                    this.allEmpleados = res.data;
                });
            },
            getSedeproveedore() {
                axios.get('/api/pqrsf/getSedePrestadorPqrsf').then(res => {
                    this.allSedes = res.data;
                });
            },
            getAreas() {
                axios.get('/api/pqrsf/allareas').then(res => {
                    this.allAreas = res.data;
                });
            },
            getSlopeFilter() {
                this.$emit("updateCount");
                this.loading = true;
                axios.post('/api/pqrsf/slopeInterna')
                    .then(res => {
                        this.loading = false
                        this.allSlope = res.data;
                    });
            },
            showSubcategories() {
                axios.get('/api/pqrsf/allSubcategorias').then(res => {
                    this.allSubcategorias = res.data;
                });
            },
            pqrsfSubcategories(pqr) {
                axios.post(`/api/pqrsf/pqrsfsubcategorias/${pqr.id}`).then(res => {
                    this.pqrsfSubcategorias = res.data;
                });
            },
            remove_subcategoria(item) {
                this.subcategorias.splice(this.subcategorias.indexOf(item), 1)
                this.subcategorias = [...this.subcategorias]
            },
            manage(pqr) {
                this.pqrsf = pqr
                this.gestion = true
                if (pqr.Tiposolicitud == 'Queja' || pqr.Tiposolicitud == 'Felicitación') {
                    this.activar = true
                    this.activarHide = false
                }
            },
            semaforoTd(pendiente) {
                if (pendiente.diasHabiles >= 1) {
                    return 'error white--text';
                } else {
                    return 'success white--text';
                }
            },
            saveSubcategories(pqrsf) {
                if (this.subcategorias == '') {
                    this.$alerError('Debe ingresar una subcategoria');
                    return
                }
                let formData = new FormData();
                for (let i = 0; i < this.subcategorias.length; i++) {
                    formData.append(`subcategoria[]`, this.subcategorias[i]);
                }
                axios.post(`/api/pqrsf/savesubcategoria/${pqrsf.id}`, formData).then(res => {
                    this.subcategorias = ''
                    this.pqrsfSubcategories(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Subcategoria agregada con exito!'
                    })
                })
            },
            delete_subcategoria(sub, pqrsf) {
                this.data = {
                    subcategoria_id: sub,
                    pqrsf_id: pqrsf.id
                }
                axios.post('/api/pqrsf/deletesubcategoria', this.data).then(res => {
                    this.pqrsfSubcategories(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Subcategoria eliminada con exito!'
                    })
                })
            },
            update(pqrsf) {
                let formData = new FormData();
                formData.append('reabierta', pqrsf.Reabierta)
                formData.append('priorizacion', pqrsf.Priorizacion)
                formData.append('atrcalidad', pqrsf.Atr_calidad)
                formData.append('tiposolicitud', pqrsf.Tiposolicitud)
                formData.append('derecho', pqrsf.derecho)
                formData.append('deber', pqrsf.deber)
                axios.post(`/api/pqrsf/update/${pqrsf.id}`, formData).then(res => {
                    this.getSlopeFilter();
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Pqrsf Actualizada con Exito!'
                    })
                })
            },
            closeCancel() {
                this.dialogCancel = false
                this.motivoCancel = ''
            },
            cancel(pqrsf) {
                this.data = {
                    motivo: this.motivoCancel,
                    paciente: pqrsf.Paciente_id
                }
                swal({
                    title: "¿Está Seguro(a) de anular?",
                    text: "",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.post(`/api/pqrsf/pqrfscancel/${this.pqrsf.id}`, this.data).then(res => {
                            this.$alerSuccess('Pqrsf Anulada con Exito!');
                            this.closeCancel()
                            this.gestion = false
                            this.getSlopeFilter()
                        }).catch(err => {
                            this.$alerError('Debe ingresar un motivo!');
                        })
                    }
                })
            },
            pqrsfDetallearticulos(pqr) {
                axios.post(`/api/pqrsf/pqrsfDetallearticulos/${pqr.id}`).then(res => {
                    this.pqrsfDetallearticulo = res.data;
                });
            },
            showDetallearticulo() {
                axios.get('/api/detallearticulo/all').then(res => {
                    this.allDetallearticulos = res.data;
                });
            },
            delete_detallearticulo(detalle, pqrsf) {
                this.data = {
                    detallearticulo_id: detalle,
                    pqrsf_id: pqrsf.id
                }
                axios.post('/api/pqrsf/deletedetallearticulo', this.data).then(res => {
                    this.pqrsfDetallearticulos(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Medicamento eliminado con exito!'
                    })
                })
            },
            remove_detallearticulo(item) {
                this.detallearticulos.splice(this.detallearticulos.indexOf(item), 1)
                this.detallearticulos = [...this.detallearticulos]
            },
            saveDetallearticulos(pqrsf) {
                if (this.detallearticulos == '') {
                    this.$alerError('Debe ingresar un medicamento');
                    return
                }
                let formData = new FormData();
                for (let i = 0; i < this.detallearticulos.length; i++) {
                    formData.append(`detallearticulo[]`, this.detallearticulos[i]);
                }
                axios.post(`/api/pqrsf/saveDetallearticulos/${pqrsf.id}`, formData).then(res => {
                    this.detallearticulos = ''
                    this.pqrsfDetallearticulos(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Medicamento agregado con exito!'
                    })
                })
            },
            showCup() {
                axios.get('/api/cups/all').then(res => {
                    this.allCups = res.data;
                });
            },
            remove_cup(item) {
                this.cups.splice(this.cups.indexOf(item), 1)
                this.cups = [...this.cups]
            },
            pqrsfCups(pqr) {
                axios.post(`/api/pqrsf/pqrsfCups/${pqr.id}`).then(res => {
                    this.pqrsfcups = res.data;
                });
            },
            delete_cups(cup, pqrsf) {
                this.data = {
                    cup_id: cup,
                    pqrsf_id: pqrsf.id
                }
                axios.post('/api/pqrsf/deletecup', this.data).then(res => {
                    this.pqrsfCups(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Servicio eliminado con exito!'
                    })
                })
            },
            saveCups(pqrsf) {
                if (this.cups == '') {
                    this.$alerError('Debe ingresar un servicio');
                    return
                }
                let formData = new FormData();
                for (let i = 0; i < this.cups.length; i++) {
                    formData.append(`cup[]`, this.cups[i]);
                }
                axios.post(`/api/pqrsf/saveCups/${pqrsf.id}`, formData).then(res => {
                    this.cups = ''
                    this.pqrsfCups(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Servicio agregado con exito!'
                    })
                })
            },
            closeGestion() {
                this.gestion = false
                this.cups = ''
                this.detallearticulos = ''
                this.subcategorias = ''
                this.areas = ''
                this.sedes = ''
                this.empleados = ''
                this.activar = false
                this.activarHide = true
                this.getSlopeFilter();
            },
            assign(pqrsf) {
                axios.get(`/api/pqrsf/validationPqrsf/${pqrsf.id}`).then(res => {
                    if (res.data.resultado == true) {
                        this.dialogAssign = true
                    } else {
                        this.$alerError(res.data.mensaje);
                        return
                    }
                })
            },
            remove_permission(item) {
                this.permiso.splice(this.permiso.indexOf(item), 1)
                this.permiso = [...this.permiso]
            },
            closeAssign() {
                this.dialogAssign = false
                this.permiso = ''
            },
            remove_empleado(item) {
                this.empleados.splice(this.empleados.indexOf(item), 1)
                this.empleados = [...this.empleados]
            },
            remove_sede(item) {
                this.sedes.splice(this.sedes.indexOf(item), 1)
                this.sedes = [...this.sedes]
            },
            remove_area(item) {
                this.areas.splice(this.areas.indexOf(item), 1)
                this.areas = [...this.areas]
            },
            pqrsfArea(pqr) {
                axios.post(`/api/pqrsf/pqrsfAreas/${pqr.id}`).then(res => {
                    this.pqrareas = res.data;
                });
            },
            delete_areas(area, pqrsf) {
                this.data = {
                    area_id: area,
                    pqrsf_id: pqrsf.id
                }
                axios.post('/api/pqrsf/deletearea', this.data).then(res => {
                    this.pqrsfArea(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Area eliminada con exito!'
                    })
                })
            },
            saveAreas(pqrsf) {
                if (this.areas == '') {
                    this.$alerError('Debe ingresar un area');
                    return
                }
                let formData = new FormData();
                for (let i = 0; i < this.areas.length; i++) {
                    formData.append(`area[]`, this.areas[i]);
                }
                axios.post(`/api/pqrsf/saveAreas/${pqrsf.id}`, formData).then(res => {
                    this.areas = ''
                    this.pqrsfArea(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Area agregada con exito!'
                    })
                })
            },
            pqrsfIps(pqr) {
                axios.post(`/api/pqrsf/pqrsfIps/${pqr.id}`).then(res => {
                    this.pqrips = res.data;
                });
            },
            delete_ips(sede, pqrsf) {
                this.data = {
                    sede_id: sede,
                    pqrsf_id: pqrsf.id
                }
                axios.post('/api/pqrsf/deleteips', this.data).then(res => {
                    this.pqrsfIps(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'IPS eliminada con exito!'
                    })
                })
            },
            saveIps(pqrsf) {
                if (this.sedes == '') {
                    this.$alerError('Debe ingresar una IPS');
                    return
                }
                let formData = new FormData();
                for (let i = 0; i < this.sedes.length; i++) {
                    formData.append(`sede[]`, this.sedes[i]);
                }
                axios.post(`/api/pqrsf/saveIps/${pqrsf.id}`, formData).then(res => {
                    this.sedes = ''
                    this.pqrsfIps(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'IPS agregada con exito!'
                    })
                })
            },
            pqrsfEmpleados(pqr) {
                axios.post(`/api/pqrsf/pqrsfEmpleados/${pqr.id}`).then(res => {
                    this.pqrempleados = res.data;
                });
            },
            delete_empleados(empleado, pqrsf) {
                this.data = {
                    empleado_id: empleado,
                    pqrsf_id: pqrsf.id
                }
                axios.post('/api/pqrsf/deletempleado', this.data).then(res => {
                    this.pqrsfEmpleados(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Empleado eliminado con exito!'
                    })
                })
            },
            saveEmpleado(pqrsf) {
                if (this.empleados == '') {
                    this.$alerError('Debe ingresar un empleado');
                    return
                }
                let formData = new FormData();
                for (let i = 0; i < this.empleados.length; i++) {
                    formData.append(`empleado[]`, this.empleados[i]);
                }
                axios.post(`/api/pqrsf/saveEmpleado/${pqrsf.id}`, formData).then(res => {
                    this.empleados = ''
                    this.pqrsfEmpleados(pqrsf);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Empleado agregado con exito!'
                    })
                })
            },
            CampoEmpleado(solicitud) {
                if (solicitud == 'Queja' || solicitud == 'Felicitación') {
                    this.activar = true
                    this.activarHide = false
                } else {
                    this.activar = false
                    this.activarHide = true
                }
            },
            saveAssign(pqrsf) {
                this.dialogProcesando = true
                let formData = new FormData();
                formData.append('documento1', pqrsf.cc)
                formData.append('documento2', pqrsf.doc)
                for (let i = 0; i < this.permiso.length; i++) {
                    formData.append(`permiso[]`, this.permiso[i]);
                }
                axios.post(`/api/pqrsf/assign/${pqrsf.id}`, formData).then(res => {
                        this.dialogProcesando = false
                        this.$alerSuccess('Pqrsf Asignada con Exito!');
                        this.closeCancel()
                        this.dialogAssign = false
                        this.gestion = false
                        this.permiso = ''
                        this.getSlopeFilter()
                    })
                    .catch(err => {
                        this.dialogProcesando = false
                        this.$alerError('¡Debe ingresar todos los campos obligatorios!');
                    });
            },
            closeSolve() {
                this.dialogSolve = false
                this.motivoSolve = ''
                this.$refs.adjuntos.value = ''
            },
            solve(pqrsf) {
                axios.get(`/api/pqrsf/validationPqrsf/${pqrsf.id}`).then(res => {
                    if (res.data.resultado == true) {
                        this.dialogSolve = true
                    } else {
                        this.$alerError(res.data.mensaje);
                        return
                    }
                })
            },
            saveSolve(pqrsf) {
                this.adjuntos = this.$refs.adjuntos.files;
                let formData = new FormData();
                formData.append('respuesta', this.motivoSolve)
                formData.append('pqrsf_id', pqrsf.id)
                formData.append('paciente_id', pqrsf.Paciente_id)
                for (let i = 0; i < this.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.adjuntos[i]);
                }
                const msg = "Esta seguro de enviar esta respuesta al usuario?";
                swal({
                    title: msg,
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        this.dialogProcesando = true
                        axios.post(`/api/pqrsf/solution/${pqrsf.id}`, formData)
                            .then(res => {
                                this.dialogProcesando = false
                                this.closeGestion();
                                this.closeSolve();
                                this.$emit("updateCount");
                                this.$alerSuccess('Solución enviada con exito!');
                            })
                            .catch(err => {
                                this.dialogProcesando = false
                                this.$alerError('¡Debe ingresar todos los campos obligatorios!');
                            });
                    }
                });
            }
        },
    }

</script>
