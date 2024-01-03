<template>
    <div>
        <v-container pa-0 fluid grid-list-md>
            <v-layout row wrap>
                <v-flex sm12 md12>
                    <v-card max-height="100%" class="mb-3">
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Buscar paciente</span>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout wrap align-center justify-center>
                                    <v-flex xs12 sm12 md10>
                                        <v-form @submit.prevent="search_paciente()">
                                            <v-layout row wrap>
                                                <v-flex xs8>
                                                    <v-text-field v-model="cedula_paciente" label="Número de Documento">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs2>
                                                    <v-btn @click="search_paciente()" @keyup.enter="search_paciente()"
                                                        v-if="!datitos" fab outline small color="success">
                                                        <v-icon>search</v-icon>
                                                    </v-btn>
                                                    <v-btn @click="clearFields()" v-if="datitos" fab outline small
                                                        color="error">
                                                        <v-icon>clear</v-icon>
                                                    </v-btn>
                                                </v-flex>
                                                <v-btn v-if="can('validacioDerechos.actualizacionMasiva')" color="info"
                                                    dark @click="dialogDescarga = true">Actualización
                                                    Pacientes</v-btn>
                                            </v-layout>
                                        </v-form>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-dialog v-model="dialogDescarga" width="800" persistent>

                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Actualizacion de
                                Pacientes</span>
                        </v-card-title>

                        <v-card-text>
                            <v-row class="justify-center">
                                <v-flex xs12 sm12 md12>
                                    <v-flex xs12>
                                        <v-autocomplete label="Escoger la entidad a actualizar" :items="tipoDescarga"
                                            item-value="id" item-text="Nombre" v-model="tipo_entidad">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm12>
                                        <input type="file" id="file" ref="myFiles" class="custom-file-input"
                                            v-on:change="onFilePicked" accept=".csv" />
                                    </v-flex>
                                    <v-divider></v-divider>
                                    <v-spacer></v-spacer>
                                    <v-flex xs12 sm12>
                                        <span style="color:red">Solo se puede montar un archivo tipo CSV</span>
                                    </v-flex>
                                </v-flex>
                            </v-row>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" dark @click="cerrarDialogDescarga()">
                                Cerrar
                            </v-btn>
                            <v-btn color="info" dark @click="ejecutarProcedimiento(tipo_entidad)">
                                Actualizar
                            </v-btn>
                        </v-card-actions>
                    </v-card>

                </v-dialog>
                <v-flex sm12 md12>
                    <v-flex xs12 v-show="datitos">
                        <v-card max-height="100%" class="mb-3">
                            <v-card-title class="headline success" style="color:white">
                                <span class="title layout justify-center font-weight-light">Información General del
                                    Usuario</span>
                            </v-card-title>
                            <v-divider></v-divider>
                            <v-card-text>
                                <v-container grid-list-md fluid class="pa-0">
                                    <v-layout wrap align-center justify-center>
                                        <v-flex xs12>
                                            <v-form>
                                                <v-layout row wrap>
                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.Tipo_Doc" label="Tipo Documento"
                                                            readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.Num_Doc"
                                                            label="Numero de Documento" readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.Primer_Nom"
                                                            label="Primer Nombre" readonly></v-text-field>
                                                    </v-flex>

                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.SegundoNom"
                                                            label="Segundo Nombre" readonly></v-text-field>
                                                    </v-flex>

                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.Primer_Ape"
                                                            label="Primer Apellido" readonly>
                                                        </v-text-field>
                                                    </v-flex>

                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.Segundo_Ape"
                                                            label="Segundo Apellido" readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.Fecha_Naci"
                                                            label="Fecha de Nacimiento" readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.Celular1" label="Celular 1"
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>

                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.Celular2" label="Celular 2"
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.Correo1" label="Correo 1"
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.Correo2" label="Correo 2"
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 md3>
                                                        <v-text-field v-model="consulta.Direccion_Residencia"
                                                            label="Direccion" readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 md3>
                                                        <v-text-field label="Medico Familiar"
                                                            v-model="consulta.medico_familia" readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-btn color="blue" dark
                                                        v-if="can('validacioDerechos.certificado') && consulta.Ut == 'REDVITAL UT'"
                                                        @click="auditoriaCertificado()">
                                                        Certificado Afiliación
                                                    </v-btn>
                                                    <v-flex xs12 md12>
                                                    <v-chip color="red" text-color="white"
                                                        v-if="consulta.Tienetutela == 1" readonly>
                                                        TIENE TUTELA
                                                    </v-chip>
                                                    <v-chip color="green" text-color="white"
                                                        v-if="consulta.Tienetutela == 0 || consulta.Tienetutela == null"
                                                        readonly>
                                                        NO TIENE TUTELA
                                                    </v-chip>
                                                    <v-chip color="green" text-color="white"
                                                        v-if="consulta.victima_conficto_armado == false || consulta.victima_conficto_armado == null "
                                                        readonly>
                                                        CÓDIGO BLANCO: No
                                                    </v-chip>
                                                    <v-chip color="blue" dark
                                                        v-if="consulta.victima_conficto_armado == true" readonly>
                                                        CÓDIGO BLANCO: Si
                                                    </v-chip>
                                                    <v-chip color="success" dark
                                                        v-if="consulta.portabilidad == false || consulta.portabilidad == null "
                                                        readonly>
                                                        TIENE PORTABILIAD: No
                                                    </v-chip>
                                                    <v-chip color="blue" dark v-if="consulta.portabilidad == true"
                                                        readonly>
                                                        TIENE PORTABILIAD: Si
                                                    </v-chip>
                                                    <v-chip color="red" text-color="white"
                                                        v-if="consulta.domiciliaria == true" readonly>
                                                        PACIENTE DOMICILIARIO: Si
                                                    </v-chip>
                                                    <v-chip color="success" text-color="white"
                                                        v-if="consulta.domiciliaria == false || consulta.domiciliaria == null"
                                                        readonly>
                                                        PACIENTE DOMICILIARIO: No
                                                    </v-chip>
                                                    <v-chip color="success" text-color="white"
                                                        v-if="consulta.ppp == false || consulta.ppp == null"
                                                        readonly>
                                                        PUNTA PIRAMIDE: No
                                                    </v-chip>
                                                    <v-chip color="red" text-color="white"
                                                        v-if="consulta.ppp == true"
                                                        readonly>
                                                        PUNTA PIRAMIDE: Si
                                                    </v-chip>
                                                    <v-chip color="success" text-color="white"
                                                        v-if="consulta.abrazarte == false || consulta.abrazarte == null"
                                                        readonly>
                                                        ABRAZARTE : No
                                                    </v-chip>
                                                    <v-chip color="red" text-color="white"
                                                        v-if="consulta.abrazarte == true"
                                                        readonly>
                                                        ABRAZARTE : Si
                                                    </v-chip>
                                                    <v-chip color="success" text-color="white"
                                                        v-if="consulta.latir_sentido == false || consulta.latir_sentido == null"
                                                        readonly>
                                                        LATIR CON SENTIDO: No
                                                    </v-chip>
                                                    <v-chip color="red" text-color="white"
                                                        v-if="consulta.latir_sentido == true"
                                                        readonly>
                                                        LATIR CON SENTIDO: Si
                                                    </v-chip>
                                                    </v-flex>
                                                </v-layout>
                                                <v-data-table :headers="headers3" :items="desserts3"
                                                    class="elevation-1">
                                                    <template v-slot:items="props">
                                                        <td class="text-xs-right">{{ props.item.Ut}}</td>
                                                        <td class="text-xs-center">
                                                            <v-btn v-if="props.item.Estado_Afiliado==1" color="success">
                                                                {{ props.item.EstadoPaciente}}</v-btn>
                                                            <v-btn v-if="props.item.Estado_Afiliado==27" color="error">
                                                                {{ props.item.EstadoPaciente}}</v-btn>
                                                            <v-btn v-if="props.item.Estado_Afiliado==28" color="info">
                                                                {{ props.item.EstadoPaciente}}</v-btn>
                                                            <v-btn v-if="props.item.Estado_Afiliado==29" color="info">
                                                                {{ props.item.EstadoPaciente}}</v-btn>
                                                        </td>
                                                        <td class="text-xs-right">{{ props.item.Fecha_Afiliado }}</td>
                                                        <td class="text-xs-right">{{ props.item.Tipo_Afiliado }}</td>
                                                        <td class="text-xs-center">{{ props.item.NombreMunicipio }}</td>
                                                        <td class="text-xs-center">{{ props.item.NombreDepartamento }}
                                                        </td>
                                                        <td class="text-xs-center">{{ props.item.NombreIPS }}</td>
                                                    </template>
                                                </v-data-table>
                                            </v-form>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                        </v-card>
                        <v-flex shrink xs12 mr-1>
                            <v-flex xs12 v-show="datitos" v-if="can('validacioDerechos.Beneficiarios')">
                                <v-card max-height="100%" class="mb-3">
                                    <v-card-title class="headline success" style="color:white">
                                        <span class="title layout justify-center font-weight-light">Información General
                                            del Beneficiario / Cotizante</span>
                                    </v-card-title>
                                    <template>
                                        <v-data-table :headers="headers" :items="desserts" class="elevation-1">
                                            <template v-slot:items="props">
                                                <td>{{ props.item.Tipo_Doc }}</td>
                                                <td class="text-xs-right">{{ props.item.Num_Doc }}</td>
                                                <td class="text-xs-right">{{ props.item.NombreCompleto}}</td>
                                                <td class="text-xs-right">
                                                    <v-btn v-if="props.item.Estado_Afiliado==1" color="success">
                                                        {{ props.item.EstadoPaciente}}</v-btn>
                                                    <v-btn v-if="props.item.Estado_Afiliado==27" color="error">
                                                        {{ props.item.EstadoPaciente}}</v-btn>
                                                </td>
                                                <td class="text-xs-right">{{ props.item.Fecha_Afiliado }}</td>
                                                <td class="text-xs-right">{{ props.item.Fecha_Naci }}</td>
                                                <td class="text-xs-right">{{ props.item.Tipo_Afiliado }}</td>
                                                <td class="text-xs-right">{{ props.item.NombreMunicipio }}</td>
                                                <td class="text-xs-right">{{ props.item.NombreDepartamento }}</td>
                                                <td class="text-xs-right">{{ props.item.NombreIPS }}</td>
                                                <td class="text-xs-right">{{ props.item.Telefono }}</td>
                                                <td class="text-xs-right">{{ props.item.Celular1 }}</td>
                                                <td class="text-xs-right">{{ props.item.Celular2 }}</td>
                                                <td class="text-xs-right">{{ props.item.Direccion_Residencia }}</td>
                                            </template>
                                        </v-data-table>
                                    </template>
                                </v-card>
                            </v-flex>
                        </v-flex>
                        <v-flex shrink xs12 mr-1>
                            <v-flex xs12 v-show="datitos" v-if="can('validacioDerechos.Complementos')">
                                <v-card max-height="100%" class="mb-3">
                                    <v-card-title class="headline success" style="color:white">
                                        <span class="title layout justify-center font-weight-light">Datos
                                            Complementarios</span>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container grid-list-md fluid class="pa-0">
                                            <v-layout wrap align-center justify-center>
                                                <v-flex xs12>
                                                    <v-form>
                                                        <v-layout row wrap>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="complementarios.Peso"
                                                                    label="Peso" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="complementarios.Talla"
                                                                    label="Talla" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field label="Tension Arterial" readonly>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="complementarios.RH" label="RH"
                                                                    readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="complementarios.Orden_Judicial"
                                                                    label="Tutela" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 md3>
                                                                <v-text-field v-model="complementarios.portabilidad"
                                                                    label="Portabilidad" readonly></v-text-field>
                                                            </v-flex>
                                                            <v-flex shrink xs12 mr-1>
                                                                <v-flex xs12 v-show="datitos">
                                                                    <v-card max-height="100%" class="mb-3">
                                                                        <v-card-title class="headline success"
                                                                            style="color:white">
                                                                            <span
                                                                                class="title layout justify-center font-weight-light">Patologias
                                                                                Marcadas</span>
                                                                        </v-card-title>
                                                                        <template>
                                                                            <v-data-table :headers="headers1"
                                                                                :items="desserts1" class="elevation-1">
                                                                                <template v-slot:items="props">
                                                                                    <td>{{ props.item.Nombre }}</td>
                                                                                    <td class="text-xs-left">
                                                                                        {{ props.item.Descripcion }}
                                                                                    </td>
                                                                                    <td class="text-xs-left">
                                                                                        {{ props.item.NombreCompleto}}
                                                                                    </td>
                                                                                </template>
                                                                            </v-data-table>
                                                                        </template>
                                                                    </v-card>
                                                                </v-flex>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-form>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                            </v-flex>
                        </v-flex>
                    </v-flex>
                </v-flex>

                <v-flex mt-12 v-show="datitos">
                    <v-card max-height="100%" class="mb-3">
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Barrera de Acceso</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout wrap align-center justify-center>
                                    <v-flex xs12>
                                        <v-form>
                                            <v-layout row wrap>
                                                <v-flex xs12 md12>
                                                    <v-autocomplete v-model="barrera.barrera" :items="barreras"
                                                        label="Barrera de Acceso">
                                                    </v-autocomplete>
                                                </v-flex>
                                                <v-flex xs12 md12>
                                                    <v-textarea v-model="barrera.observacion" label="Observacion">
                                                    </v-textarea>
                                                </v-flex>
                                                <v-flex xs12 md12 text-xs-center>
                                                    <v-btn dark color="success" @click="agregarBarrera()">Agregar
                                                    </v-btn>
                                                </v-flex>
                                                <v-flex xs2 md2>
                                                    <v-menu v-model="activarFechaIncio" :close-on-content-click="false"
                                                        :nudge-right="40" lazy transition="scale-transition" offset-y
                                                        full-width min-width="290px">
                                                        <template v-slot:activator="{ on }">
                                                            <VTextField v-model="filtros.fechaInicio"
                                                                label="Fecha inicio" prepend-icon="event" readonly
                                                                v-on="on" />
                                                        </template>
                                                        <VDatePicker color="primary" locale="es"
                                                            :max="filtros.fechaFinal" no-title
                                                            v-model="filtros.fechaInicio"
                                                            @input="activarFechaIncio = false" />
                                                    </v-menu>
                                                </v-flex>
                                                <v-flex xs2 md2>
                                                    <v-menu v-model="activarFechaFinal" :close-on-content-click="false"
                                                        :nudge-right="40" lazy transition="scale-transition" offset-y
                                                        full-width min-width="290px">
                                                        <template v-slot:activator="{ on }">
                                                            <VTextField v-model="filtros.fechaFinal" label="Fecha final"
                                                                prepend-icon="event" readonly v-on="on" />
                                                        </template>
                                                        <VDatePicker color="primary" locale="es"
                                                            :min="filtros.fechaInicio" no-title
                                                            v-model="filtros.fechaFinal"
                                                            @input="activarFechaFinal = false" />
                                                    </v-menu>
                                                </v-flex>
                                                <v-btn dark color="warning" @click="exportarBarrera()">Exportar
                                                    Excel
                                                </v-btn>
                                            </v-layout>
                                        </v-form>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-data-table :headers="headers5" :items="barrerasAcceso" class="elevation-1">
                            <template v-slot:items="props">
                                <td>{{ props.item.id }}</td>
                                <td>{{ props.item.barrera }}</td>
                                <td class="text-xs-left">{{ props.item.observacion }}</td>
                                <td class="text-xs-left">{{ props.item.usuario}}</td>
                                <td class="text-xs-left">{{ props.item.created_at }}</td>
                                <td class="text-xs-left">
                                    <v-btn v-if="props.item.estado=='Activo'" color="success">
                                        {{ props.item.estado}}</v-btn>
                                    <v-btn v-if="props.item.estado=='Vencida'" color="error">
                                        {{ props.item.estado}}</v-btn>
                                </td>
                                <td>
                                    <v-btn color="info" @click="abrirModalbarrera(props.item.id)">Cerrar Barrer
                                    </v-btn>
                                </td>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>

                <v-dialog v-model="dialogBarrera" width="500" persistent>
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Observacion de cierre</span>
                        </v-card-title>

                        <v-card-text>
                            <v-flex xs12 px-2>
                                <v-textarea label="Observación" v-model="barreraInsatisfecha.observacion">
                                </v-textarea>
                            </v-flex>
                        </v-card-text>

                        <v-divider></v-divider>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" dark @click="dialogBarrera = false">
                                Cerrar
                            </v-btn>
                            <v-btn color="primary" dark @click="solucionar()">
                                Guardar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-flex mt-12 v-show="datitos" v-if="can('validacioDerechos.clasificacion')">
                    <v-card max-height="100%" class="mb-3">
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Clasificación Paciente</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout wrap align-center justify-center>
                                    <v-flex xs12>
                                        <v-form>
                                            <v-layout row wrap>
                                                <v-flex xs12 md12>
                                                    <v-autocomplete v-model="clasificacion.clasificacion_id"
                                                        :items="clasificaciones" item-text="clasificacion_nombre"
                                                        item-value="id" label="Clasificaciones">
                                                    </v-autocomplete>
                                                </v-flex>
                                                <v-flex xs12 md12 text-xs-center>
                                                    <v-btn dark color="success" @click="agregarClasificacion()">Agregar
                                                    </v-btn>
                                                </v-flex>
                                            </v-layout>
                                        </v-form>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-data-table :headers="headers6" :items="clasificacionPaciente" class="elevation-1">
                            <template v-slot:items="props">
                                <td class="text-xs-left">{{ props.item.clasificacion_paciente_id }}</td>
                                <td class="text-xs-left">{{ props.item.clasificacion_nombre }}</td>
                                <td class="text-xs-left">{{ props.item.creado_por }}</td>
                                <td>
                                    <v-btn color="info" @click="eliminarClasificacion(props.item)">Desclasificar
                                    </v-btn>
                                </td>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>

                <v-flex mt-12 v-show="datitos" v-if="can('validacioDerechos.Novedades')">
                    <v-card max-height="100%" class="mb-3">
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Historial de Novedades</span>
                        </v-card-title>
                        <template>
                            <v-data-table :headers="headers4" :items="novedadesTabla" class="elevation-1">
                                <template v-slot:items="props">
                                    <td>{{ props.item.novedadId }}</td>
                                    <td>{{ props.item.tipoNovedad }}</td>
                                    <td class="text-xs-left">{{ props.item.fechaNovedad }}</td>
                                    <td class="text-xs-left">{{ props.item.fechaCreacion}}</td>
                                    <td class="text-xs-left">{{ props.item.fechaActualizacion }}</td>
                                    <td class="text-xs-left">{{ props.item.movtivo }}</td>
                                    <td class="text-xs-left">{{ props.item.usuario }}</td>
                                    <!-- <td class="text-xs-left">
                                        <v-btn v-if="props.item.estado=='Activo'" color="success">
                                            {{ props.item.estado}}</v-btn>
                                        <v-btn v-if="props.item.estado=='Vencida'" color="error">
                                            {{ props.item.estado}}</v-btn>
                                    </td> -->
                                    <!-- <v-btn  color="warning"
                                        :key="index"
                                        :href="adjunto.ruta"
                                        outline
                                        round
                                        small
                                        target="_blank"
                                        v-for="(adjunto, index) in data.adjuntos">
                                    Abrir archivo
                                    /v-btn> -->
                                    <v-layout align-center justify-center>
                                        <v-btn color="warning" icon @click="abrirModal(props.item)">
                                            <v-icon>library_books</v-icon>
                                        </v-btn>
                                    </v-layout>

                                </template>
                            </v-data-table>
                        </template>
                    </v-card>
                </v-flex>

                <v-dialog v-model="modalAdjuntos" width="500" persistent>
                    <v-card>
                        <v-card-title class="headline blue lighten-2 justify-center" style="color:white" primary-title>
                           Adjuntos Novedades
                        </v-card-title>
                        <v-flex xs12 v-if="adjuntos">
                            <v-btn v-for="(data, index) in adjuntos" :key="index" color="info">
                                <v-icon color="white">mdi-cloud-upload
                                </v-icon>
                                <a :href="`${data.ruta}`" target="_blank" style="color:white">
                                    Adjunto
                                    {{data.nombre}}
                                </a>
                            </v-btn>
                        </v-flex>
                        <!-- <pre>{{ adjuntos }}</pre> -->
                        <v-flex xs12 v-if="!adjuntos[0]">
                            <v-layout>
                                <span class="title layout justify-center font-weight-light">
                                     Sin adjuntos
                                </span>
                            </v-layout>
                        </v-flex>
                            <v-layout>
                                <v-spacer></v-spacer>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="primary" @click="modalAdjuntos = false">cerrar</v-btn>
                                </v-card-actions>
                            </v-layout>
                    </v-card>
                </v-dialog>

                <v-dialog v-model="modalResultadoMasivo" width="1500" persistent>
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Resultados de la
                                Actualización - {{ resultado_actualizacion.Fecha_Ejecucion }}</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-lg>
                                <v-layout row wrap>
                                    <v-flex d-flex lg4 sm4 xs12>
                                        <v-card color="#E64F07">
                                            <v-card-text class="pa-0">
                                                <v-container class="pa-0">
                                                    <div class="layout row ma-0">
                                                        <div class="sm4 xs4 flex">
                                                            <div class="layout column ma-0 justify-center align-center">
                                                                <v-icon size="76px" color="white" style="opacity: 0.8;">
                                                                    mdi-checkbox-marked-circle</v-icon>
                                                            </div>
                                                        </div>
                                                        <div class="layout column ma-0 justify-center"
                                                            style="color: white;">
                                                            <span class="caption">Registros Actualizados Cambio Tipo
                                                                Afiliado</span>
                                                            <div class="headline">
                                                                {{resultado_actualizacion.Registros_Actualizados_Cambio_Tipo_Afiliado}}
                                                            </div>
                                                            <span class="caption">En este cargue</span>
                                                        </div>
                                                    </div>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>

                                    <v-flex d-flex lg4 sm4 xs12>
                                        <v-card color="info">
                                            <v-card-text class="pa-0">
                                                <v-container class="pa-0">
                                                    <div class="layout row ma-0">
                                                        <div class="sm4 xs4 flex">
                                                            <div class="layout column ma-0 justify-center align-center">
                                                                <v-icon size="76px" color="white" style="opacity: 0.8;">
                                                                    mdi-checkbox-marked-circle</v-icon>
                                                            </div>
                                                        </div>
                                                        <div class="layout column ma-0 justify-center"
                                                            style="color: white;">
                                                            <span class="caption">Registros Actualizados Cambio Tipo
                                                                Documento
                                                                Afiliado</span>
                                                            <div class="headline">
                                                                {{resultado_actualizacion.Registros_Actualizados_Cambio_Tipo_Documento}}
                                                            </div>
                                                            <span class="caption">En este cargue</span>
                                                        </div>
                                                    </div>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>

                                    <v-flex d-flex lg4 sm4 xs12>
                                        <v-card color="#E64F07">
                                            <v-card-text class="pa-0">
                                                <v-container class="pa-0">
                                                    <div class="layout row ma-0">
                                                        <div class="sm4 xs4 flex">
                                                            <div class="layout column ma-0 justify-center align-center">
                                                                <v-icon size="76px" color="white" style="opacity: 0.8;">
                                                                    mdi-checkbox-marked-circle</v-icon>
                                                            </div>
                                                        </div>
                                                        <div class="layout column ma-0 justify-center"
                                                            style="color: white;">
                                                            <span class="caption">Registros Actualizados Entidad
                                                                Existente</span>
                                                            <div class="headline">
                                                                {{resultado_actualizacion.Registros_Actualizados_Entidad_Existente}}
                                                            </div>
                                                            <span class="caption">En este cargue</span>
                                                        </div>
                                                    </div>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>

                                    <v-flex d-flex lg4 sm4 xs12>
                                        <v-card color="info">
                                            <v-card-text class="pa-0">
                                                <v-container class="pa-0">
                                                    <div class="layout row ma-0">
                                                        <div class="sm4 xs4 flex">
                                                            <div class="layout column ma-0 justify-center align-center">
                                                                <v-icon size="76px" color="white" style="opacity: 0.8;">
                                                                    mdi-checkbox-marked-circle</v-icon>
                                                            </div>
                                                        </div>
                                                        <div class="layout column ma-0 justify-center"
                                                            style="color: white;">
                                                            <span class="caption">Registros Actualizados Proteccion
                                                                Laboral Beneficiario</span>
                                                            <div class="headline">
                                                                {{resultado_actualizacion.Registros_Actualizados_Proteccion_Laboral_Ben}}
                                                            </div>
                                                            <span class="caption">En este cargue</span>
                                                        </div>
                                                    </div>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>

                                    <v-flex d-flex lg4 sm4 xs12>
                                        <v-card color="#E64F07">
                                            <v-card-text class="pa-0">
                                                <v-container class="pa-0">
                                                    <div class="layout row ma-0">
                                                        <div class="sm4 xs4 flex">
                                                            <div class="layout column ma-0 justify-center align-center">
                                                                <v-icon size="76px" color="white" style="opacity: 0.8;">
                                                                    mdi-checkbox-marked-circle</v-icon>
                                                            </div>
                                                        </div>
                                                        <div class="layout column ma-0 justify-center"
                                                            style="color: white;">
                                                            <span class="caption">Registros Actualizados Proteccion
                                                                Laboral Cotizante</span>
                                                            <div class="headline">
                                                                {{resultado_actualizacion.Registros_Actualizados_Proteccion_Laboral_Cot}}
                                                            </div>
                                                            <span class="caption">En este cargue</span>
                                                        </div>
                                                    </div>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>

                                    <v-flex d-flex lg4 sm4 xs12>
                                        <v-card color="info">
                                            <v-card-text class="pa-0">
                                                <v-container class="pa-0">
                                                    <div class="layout row ma-0">
                                                        <div class="sm4 xs4 flex">
                                                            <div class="layout column ma-0 justify-center align-center">
                                                                <v-icon size="76px" color="white" style="opacity: 0.8;">
                                                                    mdi-checkbox-marked-circle</v-icon>
                                                            </div>
                                                        </div>
                                                        <div class="layout column ma-0 justify-center"
                                                            style="color: white;">
                                                            <span class="caption">Registros Actualizados
                                                                Reactivados</span>
                                                            <div class="headline">
                                                                {{resultado_actualizacion.Registros_Actualizados_Reactivados}}
                                                            </div>
                                                            <span class="caption">En este cargue</span>
                                                        </div>
                                                    </div>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>

                                    <v-flex d-flex lg4 sm4 xs12>
                                        <v-card color="#E64F07">
                                            <v-card-text class="pa-0">
                                                <v-container class="pa-0">
                                                    <div class="layout row ma-0">
                                                        <div class="sm4 xs4 flex">
                                                            <div class="layout column ma-0 justify-center align-center">
                                                                <v-icon size="76px" color="white" style="opacity: 0.8;">
                                                                    mdi-checkbox-marked-circle</v-icon>
                                                            </div>
                                                        </div>
                                                        <div class="layout column ma-0 justify-center"
                                                            style="color: white;">
                                                            <span class="caption">Registros Actualizados
                                                                Retirados</span>
                                                            <div class="headline">
                                                                {{resultado_actualizacion.Registros_Actualizados_Retirados}}
                                                            </div>
                                                            <span class="caption">En este cargue</span>
                                                        </div>
                                                    </div>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>

                                    <v-flex d-flex lg4 sm4 xs12>
                                        <v-card color="info">
                                            <v-card-text class="pa-0">
                                                <v-container class="pa-0">
                                                    <div class="layout row ma-0">
                                                        <div class="sm4 xs4 flex">
                                                            <div class="layout column ma-0 justify-center align-center">
                                                                <v-icon size="76px" color="white" style="opacity: 0.8;">
                                                                    mdi-checkbox-marked-circle</v-icon>
                                                            </div>
                                                        </div>
                                                        <div class="layout column ma-0 justify-center"
                                                            style="color: white;">
                                                            <span class="caption">Registros Insertados</span>
                                                            <div class="headline">
                                                                {{resultado_actualizacion.Registros_Insertados}}
                                                            </div>
                                                            <span class="caption">En este cargue</span>
                                                        </div>
                                                    </div>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>

                                    <v-flex d-flex lg4 sm4 xs12>
                                        <v-card color="#E64F07">
                                            <v-card-text class="pa-0">
                                                <v-container class="pa-0">
                                                    <div class="layout row ma-0">
                                                        <div class="sm4 xs4 flex">
                                                            <div class="layout column ma-0 justify-center align-center">
                                                                <v-icon size="76px" color="white" style="opacity: 0.8;">
                                                                    mdi-checkbox-marked-circle</v-icon>
                                                            </div>
                                                        </div>
                                                        <div class="layout column ma-0 justify-center"
                                                            style="color: white;">
                                                            <span class="caption">Registros Actualizados
                                                                Fechas de Nacimiento</span>
                                                            <div class="headline">
                                                                {{resultado_actualizacion.Registros_Actualizados_Fecha_Nacimiento}}
                                                            </div>
                                                            <span class="caption">En este cargue</span>
                                                        </div>
                                                    </div>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>


                                    <v-flex xs12 md12>
                                        <v-textarea
                                            v-model="resultado_actualizacion.Identificacion_Usuarios_Entidad_Existente"
                                            label="Identificacion Usuarios Entidad Existente" readonly></v-textarea>
                                    </v-flex>
                                    <v-spacer></v-spacer>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="error" @click="modalResultadoMasivo = false">cerrar</v-btn>
                                    </v-card-actions>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>

                <template>
                    <div class="text-center">
                        <v-dialog v-model="preload" persistent width="300">
                            <v-card color="primary" dark>
                                <v-card-text>
                                    Tranquilo procesamos tu solicitud !
                                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </div>
                </template>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import {
        mapGetters
    } from "vuex";
    import moment from 'moment';
    import Swal from 'sweetalert2';
    export default {
        data: () => ({
            search: '',
            cedula_paciente: '',
            paciente: '',
            consulta: [],
            dialogBarrera: false,
            dialogDescarga: false,
            modalAdjuntos: false,
            datitos: false,
            pagination: {},
            selected: [],
            complementarios: [],
            barreraInsatisfecha: {},
            barrerasAcceso: [],
            resultado_actualizacion: [],
            files: '',
            preload: false,
            modalResultadoMasivo: false,
            tipo_entidad: null,
            descargar: null,
            ruta: '',
            namefile: 'Seleccionar archivos',
            fileName: 'Formato',
            tipoDescarga: [{
                id: 1,
                Nombre: 'RedVital'
            }, {
                id: 2,
                Nombre: 'UdeA'
            }, {
                id: 3,
                Nombre: 'Ferrocarriles de Antioquia'
            }],
            barrera: {},
            // barreras: [
            //     'Movilidad Reducida ( Silla de Rueda, Baston, Caminador, Muletas)',
            //     'Discapacidad Auditiva',
            //     'Discapacidad Visual',
            //     'Demoras en autorizaciones,',
            //     'Afiliación',
            //     'Falta de Rampa',
            //     'Falta de Salvaescaleras ',
            //     'Falta de Ascensor',
            //     'puerta de ingreso estrechas que no permita acceso a sillas de ruedas.',
            //     'No planta eléctrica',
            //     'Humedades',
            //     'Caida del Software',
            //     'No tanques de agua*',
            //     'Traslado (No autorización de Tiketes aereos, maritimos, Fluviales, Terrestre)',
            //     'Falta de Rutas de Acceso (Estaciones de Metro, Alimentadore, Buses, Etc)',
            //     'Error en la Identificación de la Oferta ',
            //     'Barreras de Imagenen Corporativa( Cuando el usuario considera o tiene mala  referencia de la IPS )',
            //     'Falta de  Recursos Economicos para el traslado',
            //     'Falta de acceso telefónico al contact center',
            //     'Falta de reporte de resultado de laborarios y ayudas Dx'
            // ],
            barreras:[
                'Fisicas','Administrativas','Comunicacíon','Economicas','Tecnologicas','Talento Humano','Distribucíon Geográfica','Reputacional'
            ],
            headers: [{
                    text: 'Tipo Documento'
                },
                {
                    text: 'Numero Documento'
                },
                {
                    text: 'Nombre Completo'
                },
                {
                    text: 'Estado de Afiliacion'
                },
                {
                    text: 'Fecha Afiliación'
                },
                {
                    text: 'Fecha de Nacimiento'
                },
                {
                    text: 'Tipo Afiliado'
                },
                {
                    text: 'Municipio de Atencion'
                },
                {
                    text: 'Departamento de Atención'
                },
                {
                    text: 'Punto de Atencion'
                },
                {
                    text: 'Teléfono'
                },
                {
                    text: 'Celular 1'
                },
                {
                    text: 'Celular 2'
                },
                {
                    text: 'Direccion'
                }
            ],
            headers1: [{
                    text: 'Tipo de Antecendete'
                },
                {
                    text: 'Descripcion'
                },
                {
                    text: 'Medico'
                }
            ],
            headers3: [{
                    text: 'Entidad'
                },
                {
                    text: 'Estado de Afiliacion'
                },
                {
                    text: 'Fecha Afiliación'
                },
                {
                    text: 'Tipo Afiliado'
                },
                {
                    text: 'Municipio de Atencion'
                },
                {
                    text: 'Departamento de Atención'
                },
                {
                    text: 'Punto de Atencion'
                },
            ],
            headers4: [{
                    text: 'id'
                },
                {
                    text: 'Tipo Novedad'
                },
                {
                    text: 'Fecha de novedad'
                },
                {
                    text: 'Fecha de creacion'
                },
                {
                    text: 'Fecha de Actualizacion'
                },
                {
                    text: 'Motivo'
                },
                {
                    text: 'Usuario que registra la novedad'
                },
                {
                    text: 'Adjuntos'
                },
            ],
            headers5: [{
                    text: 'id'
                },
                {
                    text: 'Barrera de acceso'
                }, {
                    text: 'Observación'
                },
                {
                    text: 'Usuario creo'
                },
                {
                    text: 'Fecha de novedad'
                },
                {
                    text: 'Estado'
                },
                {
                    text: 'Ver mas'
                },
            ],
            desserts: [],
            desserts1: [],
            desserts3: [],
            novedadesTabla: [],
            novedades: [],
            idpaciente: '',
            dataNovedad: [],
            adjuntos: [],
            novedadId: '',
            filtros: {
                informe: 3,
                fechaFinal: moment().format('YYYY-MM-DD'),
                fechaInicio: moment().format('YYYY-MM-DD'),
            },
            activarFechaIncio: false,
            activarFechaFinal: false,
            clasificaciones: [],
            clasificacionPaciente: [],
            clasificacion: {
                clasificacion_id: null,
            },
            headers6: [{
                    text: 'Id',
                },
                {
                    text: 'Nombre',
                },
                {
                    text: 'Usuario Marca',
                },
                {
                    text: 'Actions',
                }
            ],
        }),
        computed: {
            ...mapGetters(['can'])
        },
        methods: {
            search_paciente() {
                if (!this.cedula_paciente) {
                    swal({
                        title: "Debe ingresar un cédula",
                        icon: "warning"
                    });
                    return;
                }
                axios.get(`/api/paciente/validacionDerechos/${this.cedula_paciente}`)
                    .then((res) => {
                        this.desserts3 = res.data.entidad;
                        this.novedades = res.data.Novedades;
                        if (res.data.paciente) {
                            this.datitos = true
                            this.consulta = res.data.paciente;
                            this.consultaBeneficiarios();
                            //this.consultaComplementarios();
                            this.consultaPatologias();
                            this.novedadesPaciente();
                            this.listarBarrera();
                            this.listarClasificacion();
                            this.listarClasificacionPaciente();
                        }
                        if (res.data.message) this.showMessage(res.data.message);
                    });
            },
            consultaBeneficiarios() {
                axios.get(`/api/paciente/consultaBeneficiarios/${this.cedula_paciente}`)
                    .then((res) => {
                        this.datitos = true
                        this.desserts = res.data
                    });
            },
            consultaComplementarios() {
                axios.get(`/api/paciente/consultaComplementarios/${this.cedula_paciente}`)
                    .then((res) => {
                        this.datitos = true
                        this.complementarios = res.data.paciente
                    });
            },
            consultaPatologias() {
                axios.get(`/api/paciente/consultaPatologias/${this.cedula_paciente}`)
                    .then((res) => {
                        this.datitos = true
                        this.desserts1 = res.data
                    });
            },
            showMessage(message) {
                swal({
                    title: `${message}`,
                    icon: "warning",
                });
            },
            novedadesPaciente() {
                axios.get(`/api/paciente/novedadesPaciente/${this.cedula_paciente}`)
                    .then((res) => {
                        this.datitos = true
                        this.novedadesTabla = res.data

                    })
            },

            abrirModal(item) {
                this.modalAdjuntos = true;
                this.dataNovedad = item;
                this.adjuntosNotificaciones();
            },
            updateNovedades() {
                this.preload = true;
                axios.post('/api/paciente/updateNovedades', this.dataNovedad)
                    .then(res => {
                        this.$alerSuccess("Se actualizo Corectamente");
                        this.preload = false;
                        this.modal = false;
                        this.novedadesPaciente();
                    }).catch(error => {
                        this.preload = false;
                        this.$alerError("Error al guardar!");
                    });
            },
            adjuntosNotificaciones() {
                this.novedadId = this.dataNovedad.novedadId
                axios.get('/api/paciente/adjuntosNotificaciones/' + this.novedadId).then(res => {
                    this.adjuntos = res.data;
                })

            },
            clearFields() {
                this.cedula_paciente = ''
                this.datos = false
                this.datitos = false
            },

            generalPDF() {
                const data = {
                    type: 'certificadoAfiliado',
                    id: this.consulta.id,
                }
                this.preload = true;
                axios.post("/api/pdf/print-pdf", data, {
                        responseType: "arraybuffer"
                    }).then(res => {
                        this.preload = false;
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("certificado");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    })
                    .catch(err => console.log(err.response));
            },

            auditoriaCertificado() {
                axios.post('/api/redvital/auditoriaCertificado', this.consulta)
                    .then(res => {
                        this.generalPDF()
                        this.$alerSuccess('Certificado Generado en exito!');
                    })
            },

            agregarBarrera() {
                this.preload = true;
                this.barrera.paciente_id = this.consulta.id
                axios.post('/api/paciente/agregarBarreras', this.barrera)
                    .then(res => {
                        this.$alerSuccess(res.data.message);
                        this.listarBarrera();
                        this.preload = false;
                    }).catch(error => {
                        this.preload = false;
                        this.$alerError("Error al guardar!");
                    });
            },

            listarBarrera() {
                this.preload = true;
                axios.get('/api/paciente/listarBarreras/' + this.consulta.id)
                    .then(res => {
                        this.barrerasAcceso = res.data
                        this.preload = false;
                    }).catch(error => {
                        this.preload = false;
                        this.$alerError("Error al listar!");
                    });
            },

            abrirModalbarrera(id) {
                this.dialogBarrera = true,
                    this.barreraInsatisfecha.id = id
            },

            solucionar() {
                this.preload = true;
                axios.post('/api/paciente/solucionarBarreras', this.barreraInsatisfecha)
                    .then(res => {
                        this.$alerSuccess(res.data.message);
                        this.listarBarrera();
                        this.dialogBarrera = false;
                    }).catch(error => {
                        this.preload = false;
                        this.dialogBarrera = false;
                        this.$alerError("Error al cerrar!");
                    });
            },

            exportarBarrera() {
                this.preload = true;
                try {
                    axios({
                        method: 'post',
                        url: '/api/paciente/generarInforme',
                        responseType: 'blob',
                        params: this.filtros
                    }).then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                        });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement('a');

                        a.download =
                            `Barreras${this.filtros.fechaInicio}_${this.filtros.fechaFinal}.xlsx`;
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        this.preload = false;
                    }).catch(err => {
                        this.preload = false,
                            console.log(err)
                    })
                } catch (error) {
                    console.log(error)
                }
            },

            /**
             * Ejecutar Procedimiento novedades
             * @param file number
             * @param object adjuntos
             * @return boolean
             * @author kobatime
             */
            ejecutarProcedimiento: async function (tipo_id) {
                if (!tipo_id) {
                    swal({
                        title: "Debe escoger una entidad",
                        icon: "warning"
                    });
                    return;
                }
                if (this.files.length < 1) {
                    this.$alerError("Debe adjuntar el archivo para el cargue!")
                    return
                }
                this.preload = true
                let formData = new FormData();

                for (var i = 0; i < this.files.length; i++) {
                    let file = this.files[i];
                    formData.append("files[" + i + "]", file);
                }
                await axios.post('/api/paciente/actualizacionMasiva/' + tipo_id, formData).then(res => {
                    this.preload = false;
                    this.resultado_actualizacion = res.data.data[0]
                    this.$alerSuccess(res.data.message);
                    this.cerrarDialogDescarga()
                    this.modalResultadoMasivo = true
                }).catch(err => {
                    this.$alerError(err.response.data.message);
                    this.preload = false;
                });
            },

            /**
             * cerrar modal descarga
             * @author kobatime
             */
            cerrarDialogDescarga() {
                this.dialogDescarga = false;
                this.tipo_entidad = null;
                this.$refs.myFiles.value = null
            },

            onFilePicked() {
                this.files = this.$refs.myFiles.files;
            },

            /**
             * Funcion listar clasificacion
             * @return objetc marcacion
             * @author kobatime
             */
            listarClasificacion() {
                this.loading = true
                axios.get('/api/clasificaciones/getClasificacionActivos')
                    .then(res => {
                        this.loading = false
                        this.clasificaciones = res.data;
                    })
            },

            agregarClasificacion() {
                this.loading = true
                this.clasificacion['paciente_id'] = this.consulta.id
                axios.post('/api/clasificaciones/guardarClasificacionPaciente', this.clasificacion)
                    .then(res => {
                        this.loading = false
                        this.listarClasificacionPaciente()
                        this.clasificacion.clasificacion_id = null,
                            this.$alerSuccess('Se registro correctamente!');
                    }).catch(err => {
                        this.$alerError(err.response.data.mensaje)
                        this.clasificacion.clasificacion_id = null
                    })
            },

            listarClasificacionPaciente() {
                this.loading = true
                axios.get('/api/clasificaciones/getClasificacionPacienteActivos/' + this.consulta.id)
                    .then(res => {
                        this.loading = false
                        this.clasificacionPaciente = res.data;
                    })
            },

            eliminarClasificacion(item) {
                Swal.fire({
                    title: '¿Esta seguro que desea desclasificar al paciente?',
                    text: "Recuerde que despues de desclasificar, debe iniciar nuevamente el proceso de clasificacion",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4caf50',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Desclasificar'
                }).then((result) => {
                    this.loading = true
                    if (result.isConfirmed) {
                        axios.post('/api/clasificaciones/eliminarClasificacionPaciente/' + item
                                .clasificacion_paciente_id)
                            .then(res => {
                                this.loading = false
                                this.$alerSuccess('Se eliminó correctamente!');
                                this.listarClasificacionPaciente()
                            })
                            .catch(err => {
                                this.loading = false,
                                    console.log(err.response)
                            });
                    } else {
                        this.loading = false
                    }
                })
            }
        }
    };

</script>

<style lang="scss" scoped>

</style>
