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
            <v-card flat>

                <v-card>
                    <v-card-title>
                        <v-layout row wrap>
                            <span>
                                Prioridad:
                            </span>
                            <span>
                                <v-icon color="error">indeterminate_check_box</v-icon> Alta
                            </span>
                            <span>
                                <v-icon color="yellow">indeterminate_check_box</v-icon> Media
                            </span>
                            <span>
                                <v-icon color="success">indeterminate_check_box</v-icon> Baja
                            </span>
                        </v-layout>
                        <v-combobox v-if="can('admintutelas.enter')" v-model="estado"
                            :items="['Pendiente', 'Gestionado', 'Cierre Temporal']" label="Estado" single-line
                            hide-details @input="getAsignadas()" :disabled="loading"></v-combobox>
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line hide-details>
                        </v-text-field>
                    </v-card-title>
                    <v-dialog v-model="dialog" v-if="dialog" persistent max-width="1000px">
                        <v-card>
                            <v-toolbar flat color="primary" dark>
                                <v-toolbar-title>Historial</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12>
                                            <v-form>
                                                <v-layout row wrap>
                                                    <v-flex xs6>
                                                        <v-text-field v-model="data.Ut" readonly label="ENTIDAD">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-text-field v-model="data.Tdocumento" readonly
                                                            label="TIPO DOC"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Documento" readonly
                                                            label="DOCUMENTO"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-text-field v-model="data.Nombre" readonly label="NOMBRE">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-text-field v-model="data.Apellido" readonly label="APELLIDO">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-text-field v-model="data.Fecha_radica" readonly
                                                            label="FECHA RADICACIÓN"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-text-field v-model="data.Radicado" readonly label="RADICADO">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Direccion" readonly
                                                            label="DIRECCIÓN"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Telefono" readonly label="TELEFONO">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Municipio" readonly
                                                            label="MUNICIPIO"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-text-field v-model="data.Juzgado" readonly label="JUZGADO">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.New_conti" readonly
                                                            label="NUEVO/CONTINUIDAD"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Exclusion" readonly
                                                            label="EXCLUSIÓN"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Integralidad" readonly
                                                            label="INTEGRALIDAD"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-text-field v-model="data.Requerimiento" readonly
                                                            label="TIPO DE REQUERIMIENTO"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 v-if="data.roltutelas != ''">
                                                        <v-subheader>RESPONSABLE</v-subheader>
                                                        <v-item-group multiple>
                                                            <v-item v-for="(data, index) in data.roltutelas"
                                                                :key="index">
                                                                <v-chip label>
                                                                    {{data.NombreRol}}
                                                                </v-chip>
                                                            </v-item>
                                                        </v-item-group>
                                                        <v-divider class="my-2"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.tiposerviciotutelas.length > 0">
                                                        <v-subheader>TIPO DE SERVICIO</v-subheader>
                                                        <v-item-group multiple>
                                                            <v-item v-for="(data, index) in data.tiposerviciotutelas"
                                                                :key="index">
                                                                <v-chip label>
                                                                    {{data.Nombre}}
                                                                </v-chip>
                                                            </v-item>
                                                        </v-item-group>
                                                        <v-divider class="my-2"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.exclusiontutelas.length > 0">
                                                        <v-subheader>EXCLUSIONES</v-subheader>
                                                        <v-item-group multiple>
                                                            <v-item v-for="(data, index) in data.exclusiontutelas"
                                                                :key="index">
                                                                <v-chip label>
                                                                    {{data.Nombre}}
                                                                </v-chip>
                                                            </v-item>
                                                        </v-item-group>
                                                        <v-divider class="my-2"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.medicamentotutelas.length > 0">
                                                        <v-subheader>MEDICAMENTOS</v-subheader>
                                                        <v-item-group multiple>
                                                            <v-item v-for="(data, index) in data.medicamentotutelas"
                                                                :key="index">
                                                                <v-chip label>
                                                                    {{data.Medicamento}}
                                                                </v-chip>
                                                            </v-item>
                                                        </v-item-group>
                                                        <v-divider class="my-2"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.cuptutelas.length > 0">
                                                        <v-subheader>SERVICIOS</v-subheader>
                                                        <v-item-group multiple>
                                                            <v-item v-for="(data, index) in data.cuptutelas"
                                                                :key="index">
                                                                <v-chip label>
                                                                    {{data.Nombrecup}}
                                                                </v-chip>
                                                            </v-item>
                                                        </v-item-group>
                                                        <v-divider class="my-2"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs6 v-show="data.Novedadregistro">
                                                        <v-text-field v-model="data.Novedadregistro" readonly
                                                            label="NOVEDADES Y REGISTRO"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 v-show="data.Medicinalaboral">
                                                        <v-text-field v-model="data.Medicinalaboral" readonly
                                                            label="MEDICINA LABORAL"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 v-show="data.Reembolso">
                                                        <v-text-field v-model="data.Reembolso" readonly
                                                            label="REEMBOLSO"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 v-show="data.Transporte">
                                                        <v-text-field v-model="data.Transporte" readonly
                                                            label="TRANSPORTE"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.gestiondocumental">
                                                        <v-text-field v-model="data.gestiondocumental" readonly
                                                            label="GESTION DOCUMENTAL"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea v-model="data.Observacion" readonly>
                                                            <template v-slot:label>
                                                                <div>
                                                                    OBSERVACIÓN
                                                                </div>
                                                            </template>
                                                        </v-textarea>
                                                        <span v-if="data.created_at"><strong>Fecha:
                                                            </strong>{{ data.created_at.split('.')[0] }} <strong>Nombre:
                                                            </strong>{{ data.NombreObservacion }}
                                                            {{ data.ApellidoObservacion }}</span>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-btn v-for="(data, index) in data.adjuntos_tutelas"
                                                            :key="index">
                                                            <a :href="`${data.Ruta}`" target="_blank"
                                                                style="text-decoration:none">
                                                                <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                                {{index+1}}
                                                            </a>
                                                        </v-btn>
                                                        <v-divider class="my-4"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.respuestatutelas.length > 0">
                                                        <v-layout wrap>
                                                            <v-flex xs12
                                                                v-for="(ResTutela, index) in data.respuestatutelas"
                                                                :key="index">
                                                                <v-flex xs12>
                                                                    <v-text-field readonly
                                                                        :value="ResTutela.tipoactuacion"
                                                                        :label="'TIPO DE ACTUACIÓN'">
                                                                    </v-text-field>
                                                                    <v-textarea readonly :value="ResTutela.Respuesta"
                                                                        :label="`RESPUESTA ${index+1}`">
                                                                    </v-textarea>
                                                                    <span><strong>Fecha:
                                                                        </strong>{{ ResTutela.created_at.split('.')[0] }}
                                                                        <strong>Nombre: </strong>{{ ResTutela.Nombre }}
                                                                        {{ ResTutela.Apellido }}
                                                                        <strong
                                                                            v-if="ResTutela.Responsable">Responsable:
                                                                        </strong> {{ ResTutela.Responsable }}
                                                                    </span>
                                                                </v-flex>
                                                                <v-flex>
                                                                    <v-btn
                                                                        v-for="(data, index) in ResTutela.adjuntos_respuestas"
                                                                        :key="index">
                                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                                            style="text-decoration:none">
                                                                            <v-icon color="dark">mdi-cloud-upload
                                                                            </v-icon>Adjunto {{index+1}}
                                                                        </a>
                                                                    </v-btn>
                                                                </v-flex>
                                                                <v-divider class="my-4"></v-divider>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-card v-if="data.Estado == 'Inadecuado'" class="mb-3">
                                                            <v-toolbar flat color="primary" dark>
                                                                <v-toolbar-title>Inadecuado</v-toolbar-title>
                                                            </v-toolbar>
                                                            <v-container grid-list-md>
                                                                <v-layout wrap>
                                                                    <v-flex xs12>
                                                                        <v-flex xs12>
                                                                            <v-flex xs12>
                                                                                <v-textarea readonly
                                                                                    :value="data.Motivoreasignar"
                                                                                    label="MOTIVO">
                                                                                </v-textarea>
                                                                                <span><strong>Fecha:
                                                                                    </strong>{{ data.Fecharea }}
                                                                                    <strong>Nombre:
                                                                                    </strong>{{ data.NombreR }}
                                                                                    {{ data.ApellidoR}}
                                                                                </span>
                                                                            </v-flex>
                                                                        </v-flex>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                        </v-card>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-autocomplete :items="roltutela" item-text="name"
                                                            item-value="name" label="QUIEN RESPONDE"
                                                            v-model="responsablerespuesta"></v-autocomplete>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-autocomplete v-model="tipoactuacion"
                                                            :items="['ACCION','RESPUESTA','FALLO PRIMERO', 'FALLO SEGUNDO', 'REQUERIMIENTO', 'DESACATO', 'SANCIÓN Y ARRESTO']"
                                                            label="TIPO DE ACTUACIÓN">
                                                        </v-autocomplete>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea v-model="respuesta">
                                                            <template v-slot:label>
                                                                <div>
                                                                    RESPUESTA
                                                                </div>
                                                            </template>
                                                        </v-textarea>
                                                    </v-flex>
                                                    <v-flex xs5 v-if="data.roltutelas != ''">
                                                        <span class="red--text">
                                                            Importante:<br />
                                                            Pacial: todavia esta gestionando<br />
                                                            Final: gestionado
                                                        </span>
                                                        <p></p>
                                                        <v-combobox v-model="tiporespuesta"
                                                            :items="['PARCIAL', 'FINAL']" chips
                                                            label="TIPO DE RESPUESTA">
                                                        </v-combobox>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <input id="adjuntos" multiple ref="adjuntos" type="file" />
                                                    </v-flex>
                                                </v-layout>
                                            </v-form>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-tooltip top
                                    v-if="can('tutela.desvincular') && (data.Estado == 'Reasignada' | data.Estado == 'Pendiente' | data.Estado == 'Inadecuado')">
                                    <template v-slot:activator="{ on }">
                                        <v-btn fab outline color="error" small v-on="on" @click="desvincular()">
                                            <v-icon>person_add_disabled</v-icon>
                                        </v-btn>
                                        <v-divider class="mx-3" vertical></v-divider>
                                    </template>
                                    <span>Desvincular</span>
                                </v-tooltip>
                                <v-btn dark color="teal" @click="alert()"
                                    v-show="(data.Estado !== 'Parcial') && (data.Estado !== 'Cerrado temporal')"
                                    v-if="can('tutela.close')">
                                    Alerta
                                </v-btn>
                                <v-btn dark color="purple"
                                    v-show="(data.Estado !== 'Parcial') && (data.Estado !== 'Cerrado temporal')"
                                    @click="OpenCompartir()">
                                    Compartir
                                </v-btn>
                                <v-btn color="warning" @click="OpenReasignar()">Reasignar</v-btn>
                                <v-btn color="success" v-if="can('tutela.close')" @click="cerrartutela = true">Cerrar
                                    tutela</v-btn>
                                <v-btn color="primary" @click="AgregarRespuesta()">Responder</v-btn>
                                <v-btn color="error" @click="CloseObservaciones()">Salir</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <v-dialog v-model="compartir" persistent max-width="600px">
                        <v-card>
                            <v-toolbar flat color="primary" dark>
                                <v-toolbar-title>Compartir tutela</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12
                                            v-if=" (data.Estado !== 'Parcial') && (data.Estado !== 'Cerrado temporal') ">
                                            <v-subheader>RESPONSABLE ACTUAL</v-subheader>
                                            <v-item-group multiple>
                                                <v-item v-for="(data, index) in data.roltutelas" :key="index">
                                                    <v-chip label>
                                                        {{data.NombreRol}}
                                                    </v-chip>
                                                </v-item>
                                            </v-item-group>
                                            <v-divider class="my-2"></v-divider>
                                        </v-flex>
                                        <v-flex xs12>
                                            <span>Seleccione responsable con quien quiera compartir la tutela</span>
                                            <v-autocomplete v-model="recompartir" :items="responsablestutela"
                                                item-text="fullname" item-value="fullname" chips label="RESPONSABLE"
                                                multiple>
                                                <template v-slot:selection="data">
                                                    <v-chip :selected="data.selected" close class="chip--select-multi"
                                                        @input="remove_responsablecompartir(data.item)">
                                                        {{ data.item.fullname }}
                                                    </v-chip>
                                                </template>
                                                <template v-slot:item="data">
                                                    <template v-if="typeof data.item.fullname !== 'object'">
                                                        <v-list-tile-content v-text="data.item.fullname">
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
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="CloseCompartir()">Cerrar</v-btn>
                                <v-btn color="blue darken-1" flat @click="compartirTutela()">Compartir</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <v-dialog v-model="reasignar" persistent max-width="600px">
                        <v-card>
                            <v-toolbar flat color="primary" dark>
                                <v-toolbar-title>Reasignar tutela</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12
                                            v-if="(data.Estado !== 'Parcial') && (data.Estado !== 'Cerrado temporal') ">
                                            <v-subheader>RESPONSABLE ACTUAL</v-subheader>
                                            <v-item-group multiple>
                                                <v-item v-for="(data, index) in data.roltutelas" :key="index">
                                                    <v-chip label>
                                                        {{data.NombreRol}}
                                                    </v-chip>
                                                </v-item>
                                            </v-item-group>
                                            <v-divider class="my-2"></v-divider>
                                        </v-flex>
                                        <v-flex xs12>
                                            <span>Seleccione responsable a quien quiera reasignar la tutela</span>
                                            <v-autocomplete v-model="Rearesponsable" :items="responsablestutela"
                                                item-text="fullname" item-value="fullname" chips label="RESPONSABLE"
                                                multiple>
                                                <template v-slot:selection="data">
                                                    <v-chip :selected="data.selected" close class="chip--select-multi"
                                                        @input="remove_responsable(data.item)">
                                                        {{ data.item.fullname }}
                                                    </v-chip>
                                                </template>
                                                <template v-slot:item="data">
                                                    <template v-if="typeof data.item.fullname !== 'object'">
                                                        <v-list-tile-content v-text="data.item.fullname">
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
                                        <v-flex>
                                            <v-textarea v-model="motivoreasignar">
                                                <template v-slot:label>
                                                    <div>
                                                        MOTIVO
                                                    </div>
                                                </template>
                                            </v-textarea>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="CloseReasignar()">Cerrar</v-btn>
                                <v-btn color="blue darken-1" flat @click="ReasignarTutela()">Reasignar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <v-dialog v-model="cerrartutela" persistent max-width="600px">
                        <v-card>
                            <v-toolbar flat color="primary" dark>
                                <v-toolbar-title>Cerrar tutela</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs6>
                                            <v-combobox chips v-model="tipocierre"
                                                :items="['Cerrado Temporal', 'Cerrado']" label="TIPO DE CERRADO">
                                            </v-combobox>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-textarea v-model="motivoclosetutela">
                                                <template v-slot:label>
                                                    <div>
                                                        MOTIVO
                                                    </div>
                                                </template>
                                            </v-textarea>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" @click="SalircloseTutela()">Salir</v-btn>
                                <v-btn color="success" @click="CloseTutela()">Cerrar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <v-data-table :headers="headers" :items="Filterdays" :search="search" item-key="index"
                        :loading="loading">
                        <template v-slot:items="asignada">
                            <tr>
                                <td>{{ asignada.item.id }}</td>
                                <td>{{asignada.item.Ut}}</td>
                                <td>{{ asignada.item.created_at.split('.')[0] }}</td>
                                <td>{{asignada.item.Municipio}}</td>
                                <!-- <td>
                                    <v-menu open-on-hover right offset-y>
                                        <template v-slot:activator="{ on }">
                                            <v-icon
                                                v-show=" (asignada.item.Estado != 'Parcial') && (asignada.item.Estado != 'Cerrado temporal') "
                                                v-on="on">message</v-icon>
                                        </template>
                                        <v-list>
                                            <v-list-tile v-for="(data, index) in asignada.item.roltutelas" :key="index">
                                                {{ data.NombreRol }}
                                            </v-list-tile>
                                        </v-list>
                                    </v-menu>
                                </td> -->
                                <td>
                                    <v-badge color="primary" left v-model="showrespuestas">
                                        <template v-slot:badge v-if="asignada.item.respuestatutelas.length > 0">
                                            <span>{{ asignada.item.respuestatutelas.length }}</span>
                                        </template>
                                        <v-icon>alarm</v-icon>
                                    </v-badge>
                                </td>
                                <td>
                                    <v-edit-dialog :return-value.sync="asignada.item.Requerimiento" large lazy
                                        @save="updateRequerimiento(asignada.item.id)" cancel-text="Cancelar"
                                        save-text="Cambiar">
                                        <div>{{ asignada.item.Requerimiento }} <v-icon color="warning" small>edit
                                            </v-icon>
                                        </div>
                                        <template v-slot:input>
                                            <v-autocomplete :disabled="!can('admintutelas.enter')"
                                                :items="allTiporequerimientos" item-text="Nombre" item-value="id"
                                                label="Editar Requerimiento" v-model="newrequerimiento"
                                                :placeholder="asignada.item.Requerimiento"></v-autocomplete>
                                        </template>
                                    </v-edit-dialog>
                                </td>
                                <td>{{ asignada.item.Documento }}</td>
                                <td>
                                    <v-chip v-show="asignada.item.Estado !== 'Cerrado temporal'"
                                        :class="ColorTd(asignada.item)">{{ asignada.item.Cantdays}}</v-chip>
                                </td>
                                <td>
                                    <v-btn @click="VerObservaciones(asignada.item)" :disabled="loading" color="blue"
                                        flat icon>
                                        <v-icon>library_books</v-icon>
                                    </v-btn>
                                </td>
                            </tr>
                        </template>
                        <template v-slot:no-results>
                            <v-alert :value="true" color="error" icon="warning">
                                Su búsqueda de "{{ search }}" no encontró resultados.
                            </v-alert>
                        </template>
                    </v-data-table>
                </v-card>
            </v-card>

        </v-tab-item>
        <v-tab-item :value="'tab-2'">
            <v-card flat>

                <v-card>
                    <v-card-title>
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                        </v-text-field>
                    </v-card-title>

                    <v-dialog v-model="historial" persistent max-width="1000px">
                        <v-card>
                            <v-toolbar flat color="primary" dark>
                                <v-toolbar-title>Historial</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12>
                                            <v-form>
                                                <v-layout row wrap>
                                                    <v-flex xs6>
                                                        <v-text-field v-model="data.Ut" readonly label="ENTIDAD">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs2>
                                                        <v-text-field v-model="data.Tdocumento" readonly
                                                            label="TIPO DOC"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Documento" readonly
                                                            label="DOCUMENTO"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-text-field v-model="data.Nombre" readonly label="NOMBRE">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-text-field v-model="data.Apellido" readonly label="APELLIDO">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-text-field v-model="data.Fecha_radica" readonly
                                                            label="FECHA RADICACIÓN"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6>
                                                        <v-text-field v-model="data.Radicado" readonly label="RADICADO">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Direccion" readonly
                                                            label="DIRECCIÓN"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Telefono" readonly label="TELEFONO">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Municipio" readonly
                                                            label="MUNICIPIO"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-text-field v-model="data.Juzgado" readonly label="JUZGADO">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.New_conti" readonly
                                                            label="NUEVO/CONTINUIDAD"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Exclusion" readonly
                                                            label="EXCLUSIÓN"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-text-field v-model="data.Integralidad" readonly
                                                            label="INTEGRALIDAD"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-text-field v-model="data.Requerimiento" readonly
                                                            label="TIPO DE REQUERIMIENTO"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-subheader>RESPONSABLE</v-subheader>
                                                        <v-item-group multiple>
                                                            <v-item v-for="(data, index) in data.roltutelas"
                                                                :key="index">
                                                                <v-chip label>
                                                                    {{data.NombreRol}}
                                                                </v-chip>
                                                            </v-item>
                                                        </v-item-group>
                                                        <v-divider class="my-2"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.tiposerviciotutelas.length > 0">
                                                        <v-subheader>TIPO DE SERVICIO</v-subheader>
                                                        <v-item-group multiple>
                                                            <v-item v-for="(data, index) in data.tiposerviciotutelas"
                                                                :key="index">
                                                                <v-chip label>
                                                                    {{data.Nombre}}
                                                                </v-chip>
                                                            </v-item>
                                                        </v-item-group>
                                                        <v-divider class="my-2"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.exclusiontutelas.length > 0">
                                                        <v-subheader>EXCLUSIONES</v-subheader>
                                                        <v-item-group multiple>
                                                            <v-item v-for="(data, index) in data.exclusiontutelas"
                                                                :key="index">
                                                                <v-chip label>
                                                                    {{data.Nombre}}
                                                                </v-chip>
                                                            </v-item>
                                                        </v-item-group>
                                                        <v-divider class="my-2"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.medicamentotutelas.length > 0">
                                                        <v-subheader>MEDICAMENTOS</v-subheader>
                                                        <v-item-group multiple>
                                                            <v-item v-for="(data, index) in data.medicamentotutelas"
                                                                :key="index">
                                                                <v-chip label>
                                                                    {{data.Medicamento}}
                                                                </v-chip>
                                                            </v-item>
                                                        </v-item-group>
                                                        <v-divider class="my-2"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.cuptutelas.length > 0">
                                                        <v-subheader>SERVICIOS</v-subheader>
                                                        <v-item-group multiple>
                                                            <v-item v-for="(data, index) in data.cuptutelas"
                                                                :key="index">
                                                                <v-chip label>
                                                                    {{data.Nombrecup}}
                                                                </v-chip>
                                                            </v-item>
                                                        </v-item-group>
                                                        <v-divider class="my-2"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs6 v-show="data.Novedadregistro">
                                                        <v-text-field v-model="data.Novedadregistro" readonly
                                                            label="NOVEDADES Y REGISTRO"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 v-show="data.Medicinalaboral">
                                                        <v-text-field v-model="data.Medicinalaboral" readonly
                                                            label="MEDICINA LABORAL"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 v-show="data.Reembolso">
                                                        <v-text-field v-model="data.Reembolso" readonly
                                                            label="REEMBOLSO"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 v-show="data.Transporte">
                                                        <v-text-field v-model="data.Transporte" readonly
                                                            label="TRANSPORTE"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.gestiondocumental">
                                                        <v-text-field v-model="data.gestiondocumental" readonly
                                                            label="GESTION DOCUMENTAL"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea v-model="data.Observacion" readonly>
                                                            <template v-slot:label>
                                                                <div>
                                                                    OBSERVACIÓN
                                                                </div>
                                                            </template>
                                                        </v-textarea>
                                                        <span v-if="data.created_at">
                                                            <strong>Fecha:</strong> {{ data.created_at.split('.')[0] }}
                                                            <strong>Nombre:</strong> {{ data.NombreObservacion }}
                                                            {{ data.ApellidoObservacion }}</span>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-btn v-for="(data, index) in data.adjuntos_tutelas"
                                                            :key="index">
                                                            <a :href="`${data.Ruta}`" target="_blank"
                                                                style="text-decoration:none">
                                                                <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                                {{index+1}}
                                                            </a>
                                                        </v-btn>
                                                        <v-divider class="my-4"></v-divider>
                                                    </v-flex>
                                                    <v-flex xs12 v-show="data.respuestatutelas.length > 0">
                                                        <v-layout wrap>
                                                            <v-flex xs12
                                                                v-for="(ResTutela, index) in data.respuestatutelas"
                                                                :key="index">
                                                                <v-flex xs12>
                                                                    <v-textarea readonly :value="ResTutela.Respuesta"
                                                                        :label="`RESPUESTA ${index+1}`">
                                                                    </v-textarea>
                                                                    <span><strong>Fecha:
                                                                        </strong>{{ ResTutela.created_at.split('.')[0] }}
                                                                        <strong>Nombre: </strong>{{ ResTutela.Nombre }}
                                                                        {{ ResTutela.Apellido }}
                                                                        <strong
                                                                            v-if="ResTutela.Responsable">Responsable:
                                                                        </strong> {{ ResTutela.Responsable }}
                                                                    </span>
                                                                </v-flex>
                                                                <v-flex>
                                                                    <v-btn
                                                                        v-for="(data, index) in ResTutela.adjuntos_respuestas"
                                                                        :key="index">
                                                                        <a :href="`${data.Ruta}`" target="_blank"
                                                                            style="text-decoration:none">
                                                                            <v-icon color="dark">mdi-cloud-upload
                                                                            </v-icon>Adjunto {{index+1}}
                                                                        </a>
                                                                    </v-btn>
                                                                </v-flex>
                                                                <v-divider class="my-4"></v-divider>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea v-model="data.Motivo_cerrar" readonly>
                                                            <template v-slot:label>
                                                                <div>
                                                                    MOTIVO DE CIERRE
                                                                </div>
                                                            </template>
                                                        </v-textarea>
                                                        <span>
                                                            <strong>Fecha: </strong> {{ data.Fecha_cerrada }}
                                                            <strong>Nombre: </strong> {{ data.NombreCerro }}
                                                            {{ data.ApellidoCerro }}
                                                        </span>
                                                    </v-flex>
                                                </v-layout>
                                            </v-form>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="CerrarVhistorial()">Cerrar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <v-dialog v-model="dialogAlert" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Estamos procesando su solicitud
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>

                    <v-data-table :headers="headers2" :items="cerradas" :search="search">
                        <template v-slot:items="asignada">
                            <td>{{ asignada.item.id }}</td>
                            <td>{{ asignada.item.Nombre }} {{ asignada.item.Apellido }}</td>
                            <td>{{ asignada.item.Tdocumento }}</td>
                            <td>{{ asignada.item.Documento }}</td>
                            <td>{{ asignada.item.Requerimiento }}</td>
                            <td>
                                <v-menu open-on-hover right offset-y>
                                    <template v-slot:activator="{ on }">
                                        <v-icon v-on="on">message</v-icon>
                                    </template>

                                    <v-list>
                                        <v-list-tile v-for="(data, index) in asignada.item.roltutelas" :key="index">
                                            {{ data.NombreRol }}
                                        </v-list-tile>
                                    </v-list>
                                </v-menu>
                            </td>
                            <td>
                                <v-btn @click="VerHistorial(asignada.item)" color="blue" flat icon>
                                    <v-icon>remove_red_eye</v-icon>
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

            </v-card>
        </v-tab-item>
    </v-tabs>

</template>
<script>
    import {
        mapGetters
    } from 'vuex';
    import moment from 'moment';
    export default {
        data() {
            return {
                responsablerespuesta: '',
                tipoactuacion: '',
                tiporespuesta: 'PARCIAL',
                search: '',
                headers: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'ENTIDAD ',
                        align: 'left',
                        value: 'Ut'
                    },
                    {
                        text: 'Fecha asignada',
                        align: 'left',
                        value: 'created_at'
                    },
                    {
                        text: 'Municipio',
                        align: 'left'
                    },
                    {
                        text: 'Radicado',
                        align: 'left',
                        value: 'Radicado'
                    },
                    {
                        text: 'Tipo requerimiento',
                        align: 'left',
                        value: 'Requerimiento'
                    },
                    {
                        text: 'Documento',
                        align: 'left',
                        value: 'Documento'
                    },
                    {
                        text: 'Tiempo cumplimiento',
                        align: 'left',
                        value: 'Cantdays'
                    },
                    {
                        text: 'Accion',
                        align: 'left'
                    }
                ],
                headers2: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'Nombre'
                    },
                    {
                        text: 'Tipo de documento',
                        align: 'left',
                        value: 'Tdocumento'
                    },
                    {
                        text: 'Documento',
                        align: 'left',
                        value: 'Documento'
                    },
                    {
                        text: 'Tipo de requerimiento',
                        align: 'left',
                        value: 'Requerimiento'
                    },
                    {
                        text: 'Responsable',
                        align: 'left'
                    },

                    {
                        text: 'Historial',
                        align: 'left'
                    }
                ],
                asignadas: [],
                cerradas: [],
                compartir: false,
                dialog: false,
                historial: false,
                reasignar: false,
                cerrartutela: false,
                showrespuestas: true,
                data: {
                    Tdocumento: "",
                    Documento: "",
                    Nombre: "",
                    Apellido: "",
                    Telefono: "",
                    Direccion: "",
                    Municipio: "",
                    Radicado: "",
                    Juzgado: "",
                    Exclusion: "",
                    Integralidad: "",
                    Tipo_requerimiento: "",
                    Tipo_servicio: [],
                    Observacion: "",
                    exclusiontutelas: [],
                    tiposerviciotutelas: [],
                    medicamentotutelas: [],
                    cuptutelas: [],
                    adjuntos: [],
                    respuestatutelas: [],
                    roltutelas: []
                },
                dataC: {
                    Tdocumento: "",
                    Documento: "",
                    Nombre: "",
                    Apellido: "",
                    Telefono: "",
                    Direccion: "",
                    Municipio: "",
                    Radicado: "",
                    Juzgado: "",
                    Exclusion: "",
                    Integralidad: "",
                    Tipo_requerimiento: "",
                    Tipo_servicio: "",
                    Observacion: ""
                },
                respuesta: "",
                Rearesponsable: [],
                motivoreasignar: "",
                motivoclosetutela: "",
                recompartir: [],
                responsablestutela: [],
                estado: "Pendiente",
                loading: false,
                roles: [],
                tipocierre: "Cerrado Temporal",
                dialogAlert: false,
                allTiporequerimientos: [],
                newrequerimiento: ''
            }
        },
        mounted() {
            this.getAsignadas();
            this.getCerradas();
            this.getResponsables();
            this.getRoles();
            this.getTiporequerimiento();
        },
        computed: {
            ...mapGetters(['can']),
            Filterdays() {
                return this.asignadas.map(asignada => {
                    let days = this.DiasRestantes(asignada)
                    let Cantdays = ''
                    if (days < 0) {
                        Cantdays = 'INMEDIATO'
                    } else {
                        Cantdays = (days) ? `${days} DÍA(S)` : 'INMEDIATO'
                    }
                    return {
                        ...asignada,
                        Cantdays
                    }
                })
            },
            roltutela() {
                let rolesUser = this.roles
                let rolesTutela = this.data.roltutelas
                let findRoles = []
                const ADMIN = [
                    'Juridica',
                    'Tutelas Gestion de Solicitudes',
                    'Dirección Medica'
                ]
                rolesTutela.forEach(tutela => {
                    let finded = rolesUser.find(user => user.name == tutela.NombreRol)
                    if (finded) findRoles.push(finded)
                });
                ADMIN.forEach(admin => {
                    let finded = rolesUser.find(user => user.name == admin)
                    if (finded) {
                        let findedRole = findRoles.find(role => role && role.name == finded.name)
                        if (!findedRole) findRoles.push({
                            name: admin
                        })
                    }
                })
                if (findRoles.length == 1) this.responsablerespuesta = findRoles[0].name;
                return findRoles
            }
        },
        methods: {
            getTiporequerimiento() {
                axios.get('/api/tiporequerimiento/all')
                    .then(res => {
                        this.allTiporequerimientos = res.data;
                    });
            },
            getResponsables() {
                axios.get('/api/tutelas/showresponsables').then(res => {
                    this.responsablestutela = res.data.map(responsable => {
                        return {
                            id: responsable.id,
                            fullname: responsable.NombreRol
                        };
                    });
                });
            },
            getRoles() {
                axios.get('/api/tutelas/roles')
                    .then(res => {
                        this.roles = res.data;
                    });
            },
            remove_responsable(item) {
                this.Rearesponsable.splice(this.Rearesponsable.indexOf(item), 1)
                this.Rearesponsable = [...this.Rearesponsable]
            },
            remove_responsablecompartir(item) {
                this.recompartir.splice(this.recompartir.indexOf(item), 1)
                this.recompartir = [...this.recompartir]
            },
            getAsignadas() {
                this.loading = true;
                let formData = new FormData();
                formData.append("estado", this.estado);
                axios.post('/api/tutelas/asignados', formData, {})
                    .then(res => {
                        this.loading = false
                        this.asignadas = res.data;
                    });
            },
            getCerradas() {
                axios.get('/api/tutelas/cerradas')
                    .then(res => {
                        this.cerradas = res.data;
                    });
            },
            DiasRestantes(asignadas) {
                let fechaAsignado = moment(asignadas.created_at);
                let fechaActual = moment();
                let diferenciadays = fechaActual.diff(fechaAsignado, 'days');
                let Total = asignadas.DiasTr - diferenciadays;
                return Total;
            },
            ColorTd(asignada) {
                if (this.DiasRestantes(asignada) == 1) {
                    return 'yellow black--text';
                } else if (this.DiasRestantes(asignada) >= 2) {
                    return 'success white--text';
                } else {
                    return 'error white--text';
                }
            },
            VerObservaciones(asignadas) {
                this.showrespuestas = false;
                this.data = asignadas;
                this.dialog = true;
            },
            CloseObservaciones() {
                this.tiporespuesta = "PARCIAL"
                this.responsablerespuesta = ""
                this.showrespuestas = true
                this.respuesta = ""
                this.$refs.adjuntos.value = ""
                this.tipoactuacion = ""
                this.dialog = false;
            },
            VerHistorial(asignadas) {
                this.data = asignadas;
                this.historial = true;
            },
            CerrarVhistorial() {
                this.historial = false;
            },
            AgregarRespuesta() {
                if (this.responsablerespuesta == "") {
                    swal({
                        title: "¡Debe seleccionar quien responde!",
                        text: ` `,
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    })
                    return
                }
                this.showrespuestas = true
                this.data.adjuntos = this.$refs.adjuntos.files;
                let formData = new FormData();
                formData.append('tutelaid', this.data.id)
                formData.append('respuesta', this.respuesta)
                formData.append('responsablerespuesta', this.responsablerespuesta)
                formData.append('tiporespuesta', this.tiporespuesta)
                formData.append('tipoactuacion', this.tipoactuacion)
                for (let i = 0; i < this.data.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.data.adjuntos[i]);
                }
                if (this.responsablerespuesta == "Coordinacion Farmacia") {
                    if (this.$refs.adjuntos.value == "" && this.tiporespuesta == "FINAL") {
                        swal({
                            title: "¡Debe adjuntar un archivo!",
                            text: ` `,
                            timer: 2000,
                            icon: "error",
                            buttons: false
                        })
                        return
                    }
                }
                axios.post(`/api/tutelas/respuestas`, formData, {})
                    .then(res => {
                        this.getAsignadas();
                        this.getCerradas();
                        swal({
                            title: "¡Respuesta guardada con exito!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.CloseObservaciones();
                    })
                    .catch(err => {
                        swal({
                            title: "¡Debe ingresar todos los campos para respuesta!",
                            text: ` `,
                            timer: 2000,
                            icon: "error",
                            buttons: false
                        })
                    });
            },
            CloseTutela() {
                let formData = new FormData();
                formData.append('tutelaid', this.data.id)
                formData.append('tipocierre', this.tipocierre)
                formData.append('motivoclosetutela', this.motivoclosetutela)
                swal({
                    title: 'Esta seguro de cerrar la tutela?',
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/tutelas/cerrartutela`, formData, {})
                            .then(res => {
                                this.getAsignadas();
                                this.getCerradas();
                                swal({
                                    title: "¡Tutela cerrada con exito!",
                                    text: ` `,
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.SalircloseTutela();
                                this.CloseObservaciones();
                            })
                            .catch(err => {
                                swal({
                                    title: "¡Debe ingresar un motivo!",
                                    text: ` `,
                                    timer: 2000,
                                    icon: "error",
                                    buttons: false
                                })
                            });
                    }
                });
            },
            OpenReasignar() {
                this.reasignar = true
            },
            CloseReasignar() {
                this.Rearesponsable = []
                this.motivoreasignar = ""
                this.reasignar = false
            },
            ReasignarTutela() {
                let formData = new FormData();
                formData.append('tutelaid', this.data.id)
                formData.append('Num_Doc', this.data.Documento)
                formData.append('estado', this.data.Estado)
                for (let i = 0; i < this.Rearesponsable.length; i++) {
                    formData.append(`responsable[]`, this.Rearesponsable[i]);
                }
                formData.append('motivo', this.motivoreasignar)
                const msg = "Esta seguro de reasignar la tutela?";
                swal({
                    title: msg,
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        this.dialogAlert = true
                        axios.post(`/api/tutelas/reasignar`, formData, {}).then(res => {
                                this.dialogAlert = false
                                this.getAsignadas();
                                this.showrespuestas = true
                                this.getCerradas();
                                swal({
                                    title: "¡Tutela reasignada con exito!",
                                    text: ` `,
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.CloseReasignar();
                                this.CloseObservaciones();
                            })
                            .catch(err => {
                                this.dialogAlert = false
                                swal({
                                    title: "¡Debe ingresar los campos obligatorios!",
                                    text: ` `,
                                    timer: 2000,
                                    icon: "error",
                                    buttons: false
                                })
                            });
                    }
                });
            },
            SalircloseTutela() {
                this.motivoclosetutela = ""
                this.cerrartutela = false
            },
            OpenCompartir() {
                this.compartir = true
            },
            CloseCompartir() {
                this.recompartir = []
                this.compartir = false
            },
            compartirTutela() {
                let encuentra = false;
                for (let i = 0; i < this.recompartir.length; i++) {
                    let compartir = this.recompartir[i]
                    for (let j = 0; j < this.data.roltutelas.length; j++) {
                        let responsable = this.data.roltutelas[j].NombreRol
                        if (compartir == responsable) {
                            encuentra = true;
                            break;
                        }
                    }
                    if (encuentra == true) {
                        swal({
                            title: "¡La Tutela ya esta compartida con ese responsable!",
                            text: ` `,
                            timer: 2000,
                            icon: "error",
                            buttons: false
                        });
                        break;
                    }
                }
                if (encuentra == false) {
                    let formData = new FormData();
                    formData.append('tutelaid', this.data.id)
                    formData.append('Num_Doc', this.data.Documento)
                    for (let i = 0; i < this.recompartir.length; i++) {
                        formData.append(`responsable[]`, this.recompartir[i]);
                    }
                    const msg = "Esta seguro de compartir la tutela?";
                    swal({
                        title: msg,
                        icon: "info",
                        buttons: ["Cancelar", "Confirmar"],
                        dangerMode: true
                    }).then(willDelete => {
                        if (willDelete) {
                            axios.post(`/api/tutelas/compartir`, formData, {}).then(res => {
                                    this.getAsignadas();
                                    this.showrespuestas = true
                                    this.getCerradas();
                                    swal({
                                        title: "¡Tutela compartida con exito!",
                                        text: ` `,
                                        timer: 2000,
                                        icon: "success",
                                        buttons: false
                                    });
                                    this.CloseCompartir();
                                    this.CloseObservaciones();
                                })
                                .catch(err => {
                                    swal({
                                        title: "¡Debe ingresar los campos obligatorios!",
                                        text: ` `,
                                        timer: 2000,
                                        icon: "error",
                                        buttons: false
                                    })
                                });
                        }
                    });
                }
            },
            alert() {
                this.responsable = this.data.roltutelas
                let formData = new FormData();
                formData.append('documento', this.data.Documento)
                formData.append('tutelaid', this.data.id)
                for (let i = 0; i < this.responsable.length; i++) {
                    formData.append(`responsable[]`, this.responsable[i].NombreRol);
                }
                swal({
                    title: "¿Está Seguro(a) de enviar la alerta?",
                    text: "",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        this.dialogAlert = true
                        axios.post('/api/tutelas/alert', formData).then(res => {
                            this.dialogAlert = false
                            this.$alerSuccess('Alerta enviada con Exito!');
                        });
                    }
                })
            },
            desvincular() {
                swal({
                    title: "¿Está seguro(a) de desvincular?",
                    text: "",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        this.dialogAlert = true
                        axios.post('/api/tutelas/desvincular', {
                            tutelaid: this.data.id
                        }).then(res => {
                            this.dialogAlert = false
                            this.CloseObservaciones();
                            this.getAsignadas();
                            this.getCerradas();
                            this.$alerSuccess('Tutela desvinculada con exito!');
                        }).catch(err => {
                            this.dialogAlert = false
                        });
                    }
                })
            },
            updateRequerimiento(item) {
                let data = {
                    tutela_id: item,
                    requerimiento_id: this.newrequerimiento
                }
                axios.post('/api/tutelas/updateRequerimiento', data).then(res => {
                    this.newrequerimiento = ''
                    this.getAsignadas();
                    this.getCerradas();
                    this.$alerSuccess('Requerimiento actualizado con exito!');
                })
            }
        }
    }

</script>
