<template>
    <v-card>
        <template>
            <v-card>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-layout row wrap>
                                    <v-flex xs3>
                                        <v-text-field prepend-icon="calendar_today" label="Fecha inicio" type="date"
                                            color="primary" v-model="data.fecha_inicio">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field prepend-icon="calendar_today" label="Fecha final" type="date"
                                            color="primary" v-model="data.fecha_fin">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field v-model="data.documento" label="Documento"></v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-autocomplete label="Especialista" :items="especialistas" item-value="id"
                                            item-text="fullname" v-model="data.medico">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-autocomplete label="Servicio" :items="servicios" v-model="data.tipocita">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-select :items="sedesFilter" v-model="data.sede" label="Sede"></v-select>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-btn color="primary" @click="citasImagenes()">Buscar</v-btn>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn text icon color="error" dark v-on="on" @click="clearFilter()">
                                                    <v-icon>clear</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Limpiar</span>
                                        </v-tooltip>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
            </v-card>
        </template>

        <v-card-title v-show="datatable">
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table v-show="datatable" :search="search" :headers="headers" :items="citas" class="elevation-1">
            <template v-slot:headers="props">
                <tr>
                    <th v-if="can('facturacion.imagenologia')">Prioridad</th>
                    <th v-for="header in props.headers" :key="header.text">
                        {{ header.text }}
                    </th>
                </tr>
            </template>
            <template v-slot:items="props">
                <td v-if="can('facturacion.imagenologia')">
                    <v-chip :class="ColorPrioridad(props.item.prioridad_radiologo)">{{ props.item.prioridad_radiologo }}
                    </v-chip>
                </td>
                <td>{{ props.item.Hora_Inicio.split(' ')[0] }}</td>
                <td>{{ props.item.Hora_Inicio | time }}</td>
                <td class="text-xs-right">
                    {{ props.item.Primer_Nom }} {{ props.item.SegundoNom }}
                    {{ props.item.Primer_Ape }} {{ props.item.Segundo_Ape }}
                </td>
                <td>
                    <v-chip class="text-xs-right" :class="ColorTd(props.item.tipo_paciente)">
                        {{ props.item.tipo_paciente }}</v-chip>
                </td>
                <td class="text-xs-right">{{ props.item.Num_Doc }}</td>
                <td class="text-xs-right">{{ props.item.Edad_Cumplida }}</td>
                <td class="text-xs-right">{{ props.item.Tipo_agenda }}</td>
                <td class="justify-center layout px-0">
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="green" dark v-on="on"
                                @click="gestion(props.item), dialogGestion = true">
                                <v-icon>playlist_add</v-icon>
                            </v-btn>
                        </template>
                        <span>Observación</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="primary" dark v-on="on" @click="henfermeria(props.item)">
                                <v-icon>assignment</v-icon>
                            </v-btn>
                        </template>
                        <span>Historia Enfermeria</span>
                    </v-tooltip>
                    <v-tooltip top v-if="can('enfermeria.imagenologia') | can('facturacion.imagenologia')">
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="pink" dark v-on="on" @click="insumos(props.item)">
                                <v-icon>add_box</v-icon>
                            </v-btn>
                        </template>
                        <span>Insumos</span>
                    </v-tooltip>
                    <v-tooltip top v-if="can('enfermeria.imagenologia') | can('tecnologo.imagenologia')">
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="teal" dark v-on="on" @click="eventos(props.item)">
                                <v-icon>how_to_reg</v-icon>
                            </v-btn>
                        </template>
                        <span>Eventos</span>
                    </v-tooltip>
                    <v-tooltip top v-if="can('tecnologo.imagenologia')">
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="purple" dark v-on="on" @click="tecnologo(props.item)">
                                <v-icon>person</v-icon>
                            </v-btn>
                        </template>
                        <span>Estudio</span>
                    </v-tooltip>
                    <v-tooltip top v-if="can('enfermeria.imagenologia') | can('tecnologo.imagenologia')">
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="indigo" dark v-on="on"
                                @click="dialogAccion = true, citapaciente(props.item)">
                                <v-icon>swap_horiz</v-icon>
                            </v-btn>
                        </template>
                        <span>Acción</span>
                    </v-tooltip>
                    <v-tooltip top v-if="can('facturacion.imagenologia')">
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="deep-orange" dark v-on="on" @click="radiologoHistoria(props.item)">
                                <v-icon>storage</v-icon>
                            </v-btn>
                        </template>
                        <span>Historia Medico</span>
                    </v-tooltip>
                    <v-tooltip top v-if="can('facturacion.imagenologia')">
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="blue-grey" dark v-on="on" @click="finalizarAtencion(props.item)">
                                <v-icon>library_add_check</v-icon>
                            </v-btn>
                        </template>
                        <span>Terminar Atención</span>
                    </v-tooltip>
                </td>
            </template>
        </v-data-table>

        <v-dialog v-model="dialogGestion" persistent max-width="1000px">
            <v-card max-height="100%" v-show="true">
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Gestión</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.documento" readonly label="Documento"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.nombre" readonly label="Paciente"></v-text-field>
                        </v-flex>
                        <v-flex xs2 px-1>
                            <v-text-field v-model="datosCita.edad" readonly label="Edad"></v-text-field>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.erp" readonly label="ERP"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.fecha_cita.split('.')[0]" readonly label="Fecha y hora">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.especialista" readonly label="Especialista"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.prioridad" readonly label="Prioridad"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1>
                            <v-text-field v-model="datosCita.tipo_agenda" readonly label="Tipo cita"></v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.fecha_orden" readonly label="Fecha y hora orden">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.tipo_paciente" readonly label="Tipo paciente">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.tecnica" readonly label="Tecnica"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lado" readonly label="lado"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lectura" readonly label="Lectura"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.ubicacion" readonly label="Ubicación"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.cama" readonly label="Cama"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.aislado" readonly label="Aislado"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1 v-if="datosCita.aislado == 'Si'">
                            <v-text-field v-model="datosCita.observacion_aislado" readonly label="Observación aislado">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 v-show="datosCita.observacion.length > 0">
                            <v-flex xs12>
                                <v-toolbar color="primary" dark class="mb-3">
                                    <v-toolbar-title>Observaciones</v-toolbar-title>
                                </v-toolbar>
                            </v-flex>
                            <v-layout wrap>
                                <v-flex xs12 v-for="(obs, index) in datosCita.observacion" :key="index">
                                    <v-flex xs12>
                                        <v-textarea readonly :value="obs.nota" :label="`OBSERVACIÓN ${index+1}`">
                                        </v-textarea>
                                        <span><strong>Fecha: </strong>{{ obs.created_at.split('.')[0] }}
                                            <strong>Nombre: </strong>{{ obs.name }} {{ obs.apellido }}
                                        </span>
                                    </v-flex>
                                    <v-divider class="my-4"></v-divider>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-flex xs12 v-show="datosCita.adjuntos.length > 0">
                            <v-flex xs12>
                                <v-toolbar color="primary" dark class="mb-3">
                                    <v-toolbar-title>Adjuntos</v-toolbar-title>
                                </v-toolbar>
                            </v-flex>
                            <v-layout wrap>
                                <v-flex v-for="(data, index) in datosCita.adjuntos" :key="index">
                                    <v-flex xs12>
                                        <v-btn>
                                            <a :href="`${data.ruta}`" target="_blank" style="text-decoration:none">
                                                <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto {{index+1}}
                                            </a>
                                        </v-btn>
                                    </v-flex>
                                    <span><strong>Fecha: </strong>{{ data.created_at.split('.')[0] }}
                                        <strong>Nombre: </strong>{{ data.name }} {{ data.apellido }}
                                    </span>
                                    <v-divider class="my-4"></v-divider>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-flex xs12 px-1>
                            <v-textarea class="mb-3" v-model="newObservasion" label="Observación"></v-textarea>
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="observacion(datosCita)">
                        <span>Observación</span>
                        <v-icon>edit</v-icon>
                    </v-btn>
                    <v-btn color="error" @click="closeGestion()">
                        <span>Salir</span>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogHistoria" persistent max-width="1000px">
            <v-card max-height="100%" v-show="true">
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Historia Clinica</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.documento" readonly label="Documento"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.nombre" readonly label="Paciente"></v-text-field>
                        </v-flex>
                        <v-flex xs2 px-1>
                            <v-text-field v-model="datosCita.edad" readonly label="Edad"></v-text-field>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.erp" readonly label="ERP"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.fecha_cita.split('.')[0]" readonly label="Fecha y hora">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.especialista" readonly label="Especialista"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.prioridad" readonly label="Prioridad"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1>
                            <v-text-field v-model="datosCita.tipo_agenda" readonly label="Tipo cita"></v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.fecha_orden" readonly label="Fecha y hora orden">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.tipo_paciente" readonly label="Tipo paciente">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.tecnica" readonly label="Tecnica"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lado" readonly label="lado"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lectura" readonly label="Lectura"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.ubicacion" readonly label="Ubicación"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.cama" readonly label="Cama"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.aislado" readonly label="Aislado"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1 v-if="datosCita.aislado == 'Si'">
                            <v-text-field v-model="datosCita.observacion_aislado" readonly label="Observación aislado">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 v-show="notas.length > 0">
                            <v-flex xs12>
                                <v-toolbar color="primary" dark class="mb-3">
                                    <v-toolbar-title>Notas</v-toolbar-title>
                                </v-toolbar>
                            </v-flex>
                            <v-layout wrap>
                                <v-flex xs12 v-for="(nota, index) in notas" :key="index">
                                    <v-flex xs12>
                                        <span>Nota # {{index+1}}</span>
                                    </v-flex>
                                    <v-flex>
                                        <v-container>
                                            <v-layout wrap>
                                                <v-flex xs12 md3 px-1>
                                                    <v-text-field v-model="nota.Temperatura" readonly
                                                        label="Temperatura">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md3 px-1>
                                                    <v-text-field v-model="nota.Saturacionoxigeno" readonly
                                                        label="Saturación de oxigeno">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md3 px-1>
                                                    <v-text-field v-model="nota.Presionarterialmedia" readonly
                                                        label="Presión arterial">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md3 px-1>
                                                    <v-text-field v-model="nota.Frecucardiaca" readonly
                                                        label="Frecuencia cardiaca">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md4 px-1>
                                                    <v-text-field v-model="nota.Frecurespiratoria" readonly
                                                        label="Frecuencia respiratoria">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md4 px-1>
                                                    <v-text-field v-model="nota.peso" readonly label="Peso">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md4 px-1>
                                                    <v-text-field v-model="nota.tasa_filtracion" readonly
                                                        label="Tasa de filtración glomerular">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md12 px-1>
                                                    <v-textarea label="Nota" v-model="nota.nota" readonly>
                                                    </v-textarea>
                                                </v-flex>
                                                <v-flex xs12>
                                                    <span><strong>Fecha: </strong>{{ nota.created_at.split('.')[0] }}
                                                        <strong>Nombre: </strong>{{ nota.name }} {{ nota.apellido }}
                                                    </span>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-flex>
                                    <v-divider class="my-4"></v-divider>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-flex v-if="can('enfermeria.imagenologia')">
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-toolbar color="primary" dark class="mb-3">
                                        <v-toolbar-title>Agregar Nota</v-toolbar-title>
                                    </v-toolbar>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field v-model="enfermeria.temperatura" type="number" label="Temperatura">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field v-model="enfermeria.oxigeno" type="number"
                                        label="Saturación de oxigeno">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field v-model="enfermeria.presion" type="number" label="Presión arterial">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field v-model="enfermeria.frecuenciaCardiaca" type="number"
                                        label="Frecuencia cardiaca"></v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field v-model="enfermeria.frecuenciaRespiratoria" type="number"
                                        label="Frecuencia respiratoria"></v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field v-model="enfermeria.peso" type="number" label="Peso"></v-text-field>
                                </v-flex>
                                <v-flex xs6 px-1>
                                    <v-text-field v-model="enfermeria.filtracion" type="number"
                                        label="Tasa de filtración glomerular"></v-text-field>
                                </v-flex>
                                <v-flex xs12 px-1>
                                    <v-textarea label="Nota" v-model="enfermeria.nota">
                                    </v-textarea>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="saveHenfermeria()" v-if="can('enfermeria.imagenologia')">
                        <span>Guardar</span>
                    </v-btn>
                    <v-btn color="error" @click="closeHenfermeria()">
                        <span>Salir</span>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogInsumos" persistent max-width="1000px">
            <v-card max-height="100%" v-show="true">
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Insumos</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.documento" readonly label="Documento"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.nombre" readonly label="Paciente"></v-text-field>
                        </v-flex>
                        <v-flex xs2 px-1>
                            <v-text-field v-model="datosCita.edad" readonly label="Edad"></v-text-field>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.erp" readonly label="ERP"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.fecha_cita.split('.')[0]" readonly label="Fecha y hora">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.especialista" readonly label="Especialista"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.prioridad" readonly label="Prioridad"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1>
                            <v-text-field v-model="datosCita.tipo_agenda" readonly label="Tipo cita"></v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.fecha_orden" readonly label="Fecha y hora orden">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.tipo_paciente" readonly label="Tipo paciente">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.tecnica" readonly label="Tecnica"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lado" readonly label="lado"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lectura" readonly label="Lectura"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.ubicacion" readonly label="Ubicación"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.cama" readonly label="Cama"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.aislado" readonly label="Aislado"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1 v-if="datosCita.aislado == 'Si'">
                            <v-text-field v-model="datosCita.observacion_aislado" readonly label="Observación aislado">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 v-if="insumosguardados.length > 0">
                            <v-card>
                                <v-toolbar color="primary" flat dark>
                                    <v-flex xs12 text-xs-center>
                                        <v-toolbar-title>Insumos guardados</v-toolbar-title>
                                    </v-flex>
                                </v-toolbar>
                                <v-card-title>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="searchInsumoGuardado" append-icon="search" label="Buscar"
                                        single-line hide-details></v-text-field>
                                </v-card-title>
                                <v-data-table class="elevation-1" :headers="medicamentos" :items="insumosguardados"
                                    item-key="name" :search="searchInsumoGuardado">
                                    <template v-slot:items="props">
                                        <td>{{ props.item.nombre }}</td>
                                        <td>{{ props.item.cantidad }}</td>
                                        <td>
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn text icon color="error" dark v-on="on"
                                                        @click="cambiarEstadoinsumo(props.item)">
                                                        <v-icon>delete</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Eliminar</span>
                                            </v-tooltip>
                                        </td>
                                    </template>
                                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                        Your search for "{{ search }}" found no results.
                                    </v-alert>
                                </v-data-table>
                            </v-card>
                        </v-flex>
                        <v-flex xs12>
                            <v-card>
                                <v-toolbar color="primary" flat dark>
                                    <v-flex xs12 text-xs-center>
                                        <v-toolbar-title>Agregar Insumos</v-toolbar-title>
                                    </v-flex>
                                </v-toolbar>
                                <v-card-title>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text icon color="success" dark v-on="on"
                                                @click="dialogAddinsumo = true">
                                                <v-icon>add</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Agregar insumo</span>
                                    </v-tooltip>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="searchInsumo" append-icon="search" label="Buscar" single-line
                                        hide-details></v-text-field>
                                </v-card-title>
                                <v-data-table class="elevation-1" :headers="medicamentos" :items="detalles"
                                    item-key="name" :search="searchInsumo">
                                    <template v-slot:items="props">
                                        <td>{{ props.item.nombre }}</td>
                                        <td class="text-xs-left">
                                            <v-flex sm4>
                                                <v-text-field v-model="props.item.cantidad_insumo" type="number">
                                                </v-text-field>
                                            </v-flex>
                                        </td>
                                    </template>
                                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                        Your search for "{{ search }}" found no results.
                                    </v-alert>
                                </v-data-table>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="confirmarInsumos()">
                        <span>Guardar</span>
                    </v-btn>
                    <v-btn color="error" @click="closeInsumos()">
                        <span>Salir</span>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogAddinsumo" persistent max-width="800px">
            <v-card>
                <v-card-title class="headline primary" style="color:white">
                    <span class="headline">Crear Insumo</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field label="Nombre" v-model="nombreinsumo"></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success darken-1" flat @click="addInsumo()">Guardar</v-btn>
                    <v-btn color="error darken-1" flat @click="dialogAddinsumo = false, nombreinsumo = ''">Cancelar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogAddmedicamento" persistent max-width="800px">
            <v-card>
                <v-card-title class="headline primary" style="color:white">
                    <span class="headline">Crear Medicamento</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field label="Nombre" v-model="nombremedicamento"></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success darken-1" flat @click="addMedicamento()">Guardar</v-btn>
                    <v-btn color="error darken-1" flat @click="dialogAddmedicamento = false, nombremedicamento = ''">
                        Cancelar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogEvent" persistent max-width="1000px">
            <v-card max-height="100%" v-show="true">
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Eventos</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.documento" readonly label="Documento"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.nombre" readonly label="Paciente"></v-text-field>
                        </v-flex>
                        <v-flex xs2 px-1>
                            <v-text-field v-model="datosCita.edad" readonly label="Edad"></v-text-field>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.erp" readonly label="ERP"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.fecha_cita.split('.')[0]" readonly label="Fecha y hora">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.especialista" readonly label="Especialista"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.prioridad" readonly label="Prioridad"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1>
                            <v-text-field v-model="datosCita.tipo_agenda" readonly label="Tipo cita"></v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.fecha_orden" readonly label="Fecha y hora orden">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.tipo_paciente" readonly label="Tipo paciente">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.tecnica" readonly label="Tecnica"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lado" readonly label="lado"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lectura" readonly label="Lectura"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.ubicacion" readonly label="Ubicación"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.cama" readonly label="Cama"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.aislado" readonly label="Aislado"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1 v-if="datosCita.aislado == 'Si'">
                            <v-text-field v-model="datosCita.observacion_aislado" readonly label="Observación aislado">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-card>
                                <v-card-title>
                                    <v-toolbar flat color="primary" dark>
                                        <v-flex xs12 text-xs-center flat dark>
                                            <v-toolbar-title>Reporte de eventos relacionados con la seguridad del
                                                paciente</v-toolbar-title>
                                        </v-flex>
                                    </v-toolbar>
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <v-layout row wrap>
                                            <v-flex xs6 px-1>
                                                <VAutocomplete :items="alleventos" item-value="id" item-text="nombre"
                                                    label="Tipo de evento" v-model="tipo_evento" />
                                            </v-flex>
                                            <v-flex xs6>
                                                <v-select :items="clasieventos" v-model="clasificacion_evento"
                                                    label="Clasificación del evento">
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs6 px-1>
                                                <v-select :items="allsedes" v-model="sede_ocurrencia"
                                                    label="Sede ocurrencia" />
                                            </v-flex>
                                            <v-flex xs6 px-1>
                                                <v-select :items="allsedes" v-model="sede_reportante"
                                                    label="Sede reportante" />
                                            </v-flex>
                                            <v-flex xs6 px-1>
                                                <v-text-field label="Fecha de ocurrencia" v-model="fecha_ocurrencia"
                                                    type="date">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs6 px-1>
                                                <v-select :items="relacion" v-model="relacionEvento"
                                                    label="Relacionado con" @input="activarCampos()">
                                                </v-select>
                                            </v-flex>
                                            <v-flex v-show="activarMedicamentos">
                                                <v-container>
                                                    <v-layout row wrap>
                                                        <v-flex xs11>
                                                            <VAutocomplete :items="detallemedicamento"
                                                                v-model="medicamento_evento" item-value="id"
                                                                item-text="nombre" label="Medicamento" />
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-tooltip top>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn text icon color="success" dark v-on="on"
                                                                        @click="dialogAddmedicamento = true">
                                                                        <v-icon>add</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Agregar medicamento</span>
                                                            </v-tooltip>
                                                        </v-flex>
                                                        <v-flex xs6 px-1>
                                                            <v-text-field label="Fecha de vencimiento"
                                                                v-model="fecha_vence_medicamento" type="date">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 px-1>
                                                            <v-text-field label="Lote" v-model="lote_medicamento">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 px-1>
                                                            <v-text-field label="Invima" v-model="invima_medicamento">
                                                            </v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-flex>
                                            <v-flex xs12 v-show="activarDispositivos">
                                                <v-container>
                                                    <v-layout row wrap>
                                                        <v-flex xs12>
                                                            <v-text-field label="Dispositivo" v-model="dispositivo">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 px-1>
                                                            <v-text-field label="Referencia"
                                                                v-model="referencia_dispositivo">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 px-1>
                                                            <v-text-field label="Modelo" v-model="modelo_dispositivo">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 px-1>
                                                            <v-text-field label="Serial" v-model="serial_dispositivo">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 px-1>
                                                            <v-text-field label="Invima" v-model="invima_dispositivo">
                                                            </v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-textarea label="Descripción de los hechos"
                                                    v-model="descripcion_hechos">
                                                </v-textarea>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-textarea label="Acciones que se tomaron" v-model="accion_tomada">
                                                </v-textarea>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="savevento()">
                        <span>Guardar</span>
                    </v-btn>
                    <v-btn color="error" @click="closeEvento()">
                        <span>Salir</span>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogTecnologo" persistent max-width="1000px">
            <v-card max-height="100%" v-show="true">
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Toma de estudio</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.documento" readonly label="Documento"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.nombre" readonly label="Paciente"></v-text-field>
                        </v-flex>
                        <v-flex xs2 px-1>
                            <v-text-field v-model="datosCita.edad" readonly label="Edad"></v-text-field>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.erp" readonly label="ERP"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.fecha_cita.split('.')[0]" readonly label="Fecha y hora">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.especialista" readonly label="Especialista"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.prioridad" readonly label="Prioridad"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1>
                            <v-text-field v-model="datosCita.tipo_agenda" readonly label="Tipo cita"></v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.fecha_orden" readonly label="Fecha y hora orden">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.tipo_paciente" readonly label="Tipo paciente">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.tecnica" readonly label="Tecnica"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lado" readonly label="lado"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lectura" readonly label="Lectura"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.ubicacion" readonly label="Ubicación"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.cama" readonly label="Cama"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.aislado" readonly label="Aislado"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1 v-if="datosCita.aislado == 'Si'">
                            <v-text-field v-model="datosCita.observacion_aislado" readonly label="Observación aislado">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 v-show="activarConstrastada">
                            <v-layout row wrap>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Cantidad" v-model="cantidad_tecnica" type="number">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 px-2>
                                    <v-text-field label="Via" v-model="via_tecnica">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 px-2>
                                    <v-text-field label="Peso" v-model="peso_tecnica" type="number">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Tiempo" v-model="tiempo_tecnica" type="number">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="exposicion" label="Exposición" type="number"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="mAs" label="mAs" type="number"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="kv" label="KV" type="number"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="distancia" label="Distancia" type="number"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="foco" label="Foco" type="number"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="totaldosis" label="Total dosis(mSv)" type="number"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="dosisacomulado" readonly label="Acomulado dosis(mSv)"></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea label="Observación" v-model="observacion_tecnologo">
                            </v-textarea>
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="savetecnologo()">
                        <span>Guardar</span>
                    </v-btn>
                    <v-btn color="error" @click="closetecnologo()">
                        <span>Salir</span>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="historiaRadiologo" persistent max-width="1000px">
            <v-card max-height="100%" v-show="true">
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Historia Clinica</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.documento" readonly label="Documento"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.nombre" readonly label="Paciente"></v-text-field>
                        </v-flex>
                        <v-flex xs2 px-1>
                            <v-text-field v-model="datosCita.edad" readonly label="Edad"></v-text-field>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.erp" readonly label="ERP"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.fecha_cita.split('.')[0]" readonly label="Fecha y hora">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.especialista" readonly label="Especialista"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.prioridad" readonly label="Prioridad cita"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1>
                            <v-text-field v-model="datosCita.tipo_agenda" readonly label="Tipo cita"></v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.fecha_orden" readonly label="Fecha y hora orden">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.tipo_paciente" readonly label="Tipo paciente">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.tecnica" readonly label="Tecnica"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lado" readonly label="lado"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lectura" readonly label="Lectura"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.ubicacion" readonly label="Ubicación"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.cama" readonly label="Cama"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.aislado" readonly label="Aislado"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1 v-if="datosCita.aislado == 'Si'">
                            <v-text-field v-model="datosCita.observacion_aislado" readonly label="Observación aislado">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea readonly :value="datosCita.hallazgos" label="Hallazgos">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea readonly :value="datosCita.conclusion" label="Conclusión">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea readonly :value="datosCita.indicacion" label="Indicación">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs12 px-1>
                            <v-textarea label="Tecnica" v-model="datosCita.tecnica_radiologo">
                            </v-textarea>
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="saveTecnica(datosCita)">
                        <span>Guardar</span>
                    </v-btn>
                    <v-btn color="error" @click="historiaRadiologo = false, tecnica = ''">
                        <span>Salir</span>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <template>
            <div class="text-center">
                <v-dialog v-model="dialogAccion" width="555px">
                    <v-card>
                        <v-card-text>
                            <v-card-text align="center" justify="center">
                                <p>¿Donde sigue la atención el paciente?</p>
                            </v-card-text>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn v-if="can('tecnologo.imagenologia')" color="warning" @click="confirmarEnfermeria()">
                                <span>Enfermeria</span>
                            </v-btn>
                            <v-btn v-if="can('enfermeria.imagenologia')" color="warning" @click="confirmarEnfermera()">
                                <span>Tecnologo</span>
                            </v-btn>
                            <v-btn color="success" @click="confirmarTecnologo()">
                                <span>Medico</span>
                            </v-btn>
                            <v-btn color="error" @click="devolverAdmisiones()">
                                <span>Admisiones</span>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </div>
        </template>

        <template>
            <div class="text-center">
                <v-dialog v-model="preload_cita" persistent width="300">
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

        <v-dialog v-model="dialogConfirmarInsumo" persistent max-width="1000px">
            <v-card max-height="100%" v-show="true">
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Confirmar insumos</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-card>
                                <v-card-title>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="searchCInsumo" append-icon="search" label="Buscar"
                                        single-line hide-details></v-text-field>
                                </v-card-title>
                                <v-data-table class="elevation-1" :headers="medicamentos" :items="detalleinsumo"
                                    item-key="name" :search="searchCInsumo">
                                    <template v-slot:items="props">
                                        <td>{{ props.item.nombre }}</td>
                                        <td class="text-xs-left">
                                            <v-flex sm4>
                                                <v-text-field v-model="props.item.cantidad_insumo" type="number">
                                                </v-text-field>
                                            </v-flex>
                                        </td>
                                        <td>
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn text icon color="error" dark v-on="on"
                                                        @click="deleteinsumos(props.item)">
                                                        <v-icon>delete</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Eliminar</span>
                                            </v-tooltip>
                                        </td>
                                    </template>
                                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                        Your search for "{{ search }}" found no results.
                                    </v-alert>
                                </v-data-table>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="saveinsumos()">
                        <span>Guardar</span>
                    </v-btn>
                    <v-btn color="error" @click="closeConfirmarinsumo()">
                        <span>Salir</span>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-card>
</template>

<script>
    import {
        mapGetters
    } from "vuex";
    import Swal from 'sweetalert2';
    import moment from "moment";
    moment.locale("es");
    export default {
        data() {
            return {
                sedesFilter: ['Magisterio Clinica Victoriana', 'Magisterio VILLANUEVA'],
                data: {
                    fecha_inicio: moment().format("YYYY-MM-DD"),
                    fecha_fin: moment().format("YYYY-MM-DD"),
                    documento: '',
                    medico: '',
                    sede: '',
                    tipocita: ''
                },
                especialistas: [],
                datatable: false,
                search: '',
                headers: [{
                        text: "Fecha",
                        value: "Hora_Inicio",
                        align: "center",
                        sortable: true
                    },
                    {
                        text: "Hora",
                        value: "Hora_Inicio",
                        align: "center",
                        sortable: true
                    },
                    {
                        text: "Paciente",
                        value: "Primer_Nom",
                        align: "center"
                    },
                    {
                        text: "Tipo",
                        value: "tipo_paciente",
                        align: "center"
                    },
                    {
                        text: "Cédula",
                        value: "Num_Doc",
                        align: "center"
                    },
                    {
                        text: "Edad",
                        value: "Edad_Cumplida",
                        align: "center"
                    },
                    {
                        text: "Tipo Cita",
                        value: "Tipo_Cita",
                        align: "center"
                    },
                    {
                        text: "Acciones",
                        value: "",
                        align: "center"
                    }
                ],
                citas: [],
                dialogGestion: false,
                datosCita: {
                    nombre: '',
                    documento: '',
                    edad: '',
                    especialista: '',
                    erp: '',
                    fecha_cita: '',
                    cita_id: '',
                    paciente_id: '',
                    motivo: '',
                    cita_paciente_id: '',
                    tecnica: '',
                    prioridad: '',
                    lado: '',
                    lectura: '',
                    observacion: [],
                    adjuntos: []
                },
                newObservasion: '',
                preload_cita: false,
                observ: {},
                dialogHistoria: false,
                enfermeria: {
                    temperatura: '',
                    oxigeno: '',
                    presion: '',
                    frecuenciaCardiaca: '',
                    frecuenciaRespiratoria: '',
                    peso: '',
                    filtracion: '',
                    nota: ''
                },
                dialogInsumos: false,
                searchInsumo: '',
                selected: [],
                medicamentos: [{
                        text: "Nombre",
                        value: "nombre",
                        align: "left",
                        sortable: true
                    },
                    {
                        text: "Cantidad",
                        align: "left",
                        sortable: false
                    },
                    {
                        text: "",
                        align: "left",
                        sortable: false
                    }
                ],
                detalles: [],
                insumo_enfermera: [],
                dialogEvent: false,
                relacion: ['Medicamentos', 'Dispositivos', 'Ambos'],
                dialogConfirmarInsumo: false,
                searchCInsumo: '',
                detalleinsumo: [],
                alleventos: [],
                allsedes: ['Magisterio Clinica Victoriana', 'Magisterio VILLANUEVA'],
                activarMedicamentos: false,
                activarDispositivos: false,
                detallemedicamento: [],
                clasieventos: ['Adverso', 'Incidente', 'Complicación', 'Acción insegura'],
                relacionEvento: '',
                tipo_evento: '',
                clasificacion_evento: '',
                sede_ocurrencia: '',
                sede_reportante: '',
                fecha_ocurrencia: '',
                medicamento_evento: '',
                fecha_vence_medicamento: '',
                lote_medicamento: '',
                invima_medicamento: '',
                dispositivo: '',
                referencia_dispositivo: '',
                modelo_dispositivo: '',
                serial_dispositivo: '',
                invima_dispositivo: '',
                descripcion_hechos: '',
                accion_tomada: '',
                dialogTecnologo: false,
                activarConstrastada: false,
                cantidad_tecnica: '',
                via_tecnica: '',
                peso_tecnica: '',
                tiempo_tecnica: '',
                exposicion: '',
                mAs: '',
                kv: '',
                distancia: '',
                foco: '',
                totaldosis: '',
                observacion_tecnologo: '',
                dialogAccion: false,
                dosisacomulado: '',
                cita: {},
                notas: [],
                dialogAddinsumo: false,
                nombreinsumo: '',
                nombremedicamento: '',
                dialogAddmedicamento: false,
                historiaRadiologo: false,
                searchInsumoGuardado: '',
                insumosguardados: [],
                tipocitas: [],
                servicios: ['Ecografia','Tomografia', 'Radiografia ', 'Mamografia', 'Ginecologica', 
                'Doppler', 'Intervencionista', 'Obstetrica', 'Biopsias', 'Drenajes', 'Ecocardiografia']

            }
        },
        mounted() {
            this.radiologo();
            this.getinsumos();
            this.tipoCita();
        },
        filters: {
            time(date) {
                return moment(date).format('HH:mm:ss');
            }
        },
        computed: {
            ...mapGetters(['can'])
        },
        methods: {
            radiologo() {
                axios.get('/api/imagenologia/radiologos').then(res => {
                    this.especialistas = res.data.map(espe => {
                        return {
                            id: espe.id,
                            fullname: `${espe.name} ${espe.apellido}`
                        };
                    });
                });
            },
            tipoCita() {
                axios.get('/api/imagenologia/tipoCita').then(res => {
                    this.tipocitas = res.data
                });
            },
            getinsumos() {
                axios.get('/api/imagenologia/insumos').then(res => {
                    this.detalles = res.data
                });
            },
            getmedicamentos() {
                axios.get('/api/imagenologia/medicamentos').then(res => {
                    this.detallemedicamento = res.data
                });
            },
            geteventos() {
                axios.get('/api/evento/all').then(res => {
                    this.alleventos = res.data
                });
            },
            clearFilter() {
                this.data = {
                    fecha_inicio: moment().format("YYYY-MM-DD"),
                    fecha_fin: moment().format("YYYY-MM-DD"),
                    documento: '',
                    medico: '',
                    sede: '',
                    tipocita: ''
                }
            },
            citasImagenes() {
                axios.post(`/api/imagenologia/allgestion`, this.data).then(res => {
                    this.citas = res.data
                    if (this.citas !== '') this.datatable = true;
                }).catch(err => {
                    let msg = '';
                    for (const pro in err.response.data) {
                        if (msg) msg += `${err.response.data[pro]}`
                        else msg = err.response.data[pro]
                    }
                    this.$alerError(msg);
                })
            },
            gestion(datos) {
                this.datosCita = {
                    nombre: `${datos.Primer_Nom} ${datos.Primer_Ape} ${datos.Segundo_Ape}`,
                    documento: datos.Num_Doc,
                    edad: datos.Edad_Cumplida,
                    especialista: `${datos.nameEspecialista} ${datos.apellidoEspecialista}`,
                    erp: datos.Ut,
                    fecha_cita: datos.Hora_Inicio,
                    cita_id: datos.id,
                    paciente_id: datos.paciente_id,
                    cita_paciente_id: datos.cita_paciente_id,
                    tecnica: datos.tecnica,
                    prioridad: datos.prioridad,
                    lado: datos.lado,
                    lectura: datos.lectura,
                    observacion: datos.observacion,
                    adjuntos: datos.adjuntos_cita,
                    tipo_paciente: datos.tipo_paciente,
                    tipo_agenda: datos.Tipo_agenda,
                    fecha_orden: datos.fecha_orden,
                    ubicacion: datos.ubicacion,
                    cama: datos.cama,
                    aislado: datos.aislado,
                    observacion_aislado: datos.observacion_aislado,
                    hallazgos: datos.Hallazgos,
                    conclusion: datos.Conclusion,
                    indicacion: datos.Indicacion,
                    notaclaratoria: datos.Notaclaratoria,
                    tecnica_radiologo: datos.tecnica_radiologo,
                    prioridad_r: datos.prioridad_radiologo
                }
            },
            closeGestion() {
                this.dialogGestion = false
                this.citasImagenes();
            },
            observacion(citas) {
                let caracteres = this.newObservasion.length > 20
                if (caracteres == false) {
                    this.$alerError('Debe ingresar una observación mayor a 20 caracteres!');
                    return
                }
                this.observ = {
                    nota: this.newObservasion,
                    cita_paciente_id: citas.cita_paciente_id,
                    tipo: 'admin'
                }
                this.preload_cita = true
                axios.post('/api/cita/observacion', this.observ).then(res => {
                    this.newObservasion = ''
                    this.preload_cita = false
                    this.closeGestion();
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
                        title: 'Observación agregada con exito!'
                    })
                }).catch(err => {
                    this.preload_cita = false
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
            henfermeria(datos) {
                this.dialogHistoria = true;
                this.gestion(datos);
                this.notasEnfermeria();
            },
            notasEnfermeria() {
                let datos = {
                    cita_paciente_id: this.datosCita.cita_paciente_id
                }
                axios.post('/api/imagenologia/notasEnfermeria', datos).then(res => {
                    this.notas = res.data
                })
            },
            closeHenfermeria() {
                this.enfermeria = {
                    temperatura: '',
                    oxigeno: '',
                    presion: '',
                    frecuenciaCardiaca: '',
                    frecuenciaRespiratoria: '',
                    peso: '',
                    filtracion: '',
                    nota: ''
                }
                this.dialogHistoria = false;
            },
            saveHenfermeria() {
                let datos = {
                    cita_paciente_id: this.datosCita.cita_paciente_id,
                    filtracion: this.enfermeria.filtracion,
                    frecuenciaCardiaca: this.enfermeria.frecuenciaCardiaca,
                    frecuenciaRespiratoria: this.enfermeria.frecuenciaRespiratoria,
                    nota: this.enfermeria.nota,
                    oxigeno: this.enfermeria.oxigeno,
                    peso: this.enfermeria.peso,
                    presion: this.enfermeria.presion,
                    temperatura: this.enfermeria.temperatura,
                    tipo: 'historia'
                }
                this.preload_cita = true
                axios.post('/api/imagenologia/saveEnfermeria', datos).then(res => {
                    this.closeHenfermeria();
                    this.preload_cita = false
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
                        title: 'Historia guardada con exito!'
                    })
                }).catch(err => {
                    this.preload_cita = false
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
            insumos(datos) {
                this.getinsumoguardados(datos);
                this.dialogInsumos = true;
                this.gestion(datos);
            },
            addInsumo() {
                if (this.nombreinsumo == '' || this.nombreinsumo == null) return
                let insumo = {
                    nombre: this.nombreinsumo
                };
                axios.post('/api/imagenologia/createinsumo', insumo).then(res => {
                    this.nombreinsumo = ''
                    this.dialogAddinsumo = false
                    this.getinsumos();
                    this.$alerSuccess("Insumo creado con exito!")
                })
            },
            deleteinsumos(insumo) {
                let id_insumo = this.detalleinsumo.find(x => x.id == insumo.id);
                let eliminar = this.detalleinsumo.indexOf(id_insumo);
                this.detalleinsumo.splice(eliminar, 1);

                let det = this.detalles.find(x => x.id == insumo.id);
                det.cantidad_insumo = ''

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
                    title: 'Insumo eliminado con exito!'
                })
            },
            confirmarInsumos() {
                this.dialogConfirmarInsumo = true
                let a = this.detalles
                for (let i = 0; i < a.length; i++) {
                    if (a[i].cantidad_insumo) {
                        this.detalleinsumo.push(a[i]);
                    }
                }
            },
            closeConfirmarinsumo() {
                this.dialogConfirmarInsumo = false
                this.detalleinsumo = []
                this.searchCInsumo = ''
            },
            saveinsumos() {
                let data = {
                    obj: this.detalleinsumo,
                    cita_paciente: this.datosCita.cita_paciente_id
                }
                if (data.obj.length == 0) return
                axios.post('/api/imagenologia/saveInsumos', data).then(res => {
                    this.closeInsumos();
                    this.$alerSuccess("Insumos guardados con exito!")
                }).catch(err => {
                    let msg = '';
                    for (const pro in err.response.data) {
                        if (msg) msg += `${eerr.response.data[pro]}`
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
            closeInsumos() {
                this.detalles = []
                this.dialogInsumos = false
                this.getinsumos();
                this.closeConfirmarinsumo()
                this.searchInsumo = ''
            },
            eventos(datos) {
                this.gestion(datos);
                this.dialogEvent = true
                this.geteventos();
                this.getmedicamentos();
            },
            addMedicamento() {
                if (this.nombremedicamento == '' || this.nombremedicamento == null) return
                let medicament = {
                    nombre: this.nombremedicamento
                };
                axios.post('/api/imagenologia/createmedicamento', medicament).then(res => {
                    this.nombremedicamento = ''
                    this.dialogAddmedicamento = false
                    this.getmedicamentos();
                    this.$alerSuccess("Medicamento creado con exito!")
                })
            },
            ColorTd(tipo) {
                if (tipo == 'Hospitalario') {
                    return 'yellow black--text';
                } else if (tipo == 'Ambulatorio') {
                    return 'success white--text';
                } else {
                    return 'error white--text';
                }
            },
            activarCampos() {
                if (this.relacionEvento == 'Medicamentos') this.activarMedicamentos = true, this.activarDispositivos =
                    false
                if (this.relacionEvento == 'Dispositivos') this.activarDispositivos = true, this.activarMedicamentos =
                    false
                if (this.relacionEvento == 'Ambos') this.activarMedicamentos = true, this.activarDispositivos = true
            },
            savevento() {
                let data = {
                    cita_paciente_id: this.datosCita.cita_paciente_id,
                    relacion: this.relacionEvento,
                    tipoevento: this.tipo_evento,
                    clasificacion: this.clasificacion_evento,
                    sede_ocurrencia: this.sede_ocurrencia,
                    sede_reportante: this.sede_reportante,
                    fecha_ocurrencia: this.fecha_ocurrencia,
                    medicamento_evento: this.medicamento_evento,
                    fecha_vence_medicamento: this.fecha_vence_medicamento,
                    lote_medicamento: this.lote_medicamento,
                    invima_medicamento: this.invima_medicamento,
                    dispositivo: this.dispositivo,
                    referencia_dispositivo: this.referencia_dispositivo,
                    modelo_dispositivo: this.modelo_dispositivo,
                    serial_dispositivo: this.serial_dispositivo,
                    invima_dispositivo: this.invima_dispositivo,
                    descripcion_hechos: this.descripcion_hechos,
                    accion_tomada: this.accion_tomada
                }
                axios.post('/api/imagenologia/saveEventos', data).then(res => {
                    this.closeEvento();
                    this.$alerSuccess("Evento guardado con exito!")
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
            closeEvento() {
                this.activarMedicamentos = false
                this.activarDispositivos = false
                this.dialogEvent = false
                this.relacionEvento = ''
                this.tipo_evento = ''
                this.clasificacion_evento = ''
                this.sede_ocurrencia = ''
                this.sede_reportante = ''
                this.fecha_ocurrencia = ''
                this.medicamento_evento = ''
                this.fecha_vence_medicamento = ''
                this.lote_medicamento = ''
                this.invima_medicamento = ''
                this.dispositivo = ''
                this.referencia_dispositivo = ''
                this.modelo_dispositivo = ''
                this.serial_dispositivo = ''
                this.invima_dispositivo = ''
                this.descripcion_hechos = ''
                this.accion_tomada = ''
            },
            tecnologo(datos) {
                this.acomuladoDosis(datos);
                if (datos.tecnica == "Constrastada") this.activarConstrastada = true
                this.dialogTecnologo = true;
                this.gestion(datos);
            },
            closetecnologo() {
                this.activarConstrastada = false
                this.dialogTecnologo = false;
                this.cantidad_tecnica = "",
                    this.via_tecnica = "",
                    this.peso_tecnica = "",
                    this.tiempo_tecnica = "",
                    this.exposicion = "",
                    this.mAs = "",
                    this.kv = "",
                    this.distancia = "",
                    this.foco = "",
                    this.totaldosis = "",
                    this.observacion_tecnologo = ""
            },
            savetecnologo() {
                if (this.datosCita.tecnica == "Constrastada") {
                    if (this.peso_tecnica == "") {
                        this.$alerError("Peso es obligatorio");
                        return
                    }
                    if (this.tiempo_tecnica == "") {
                        this.$alerError("Tiempo es obligatorio");
                        return
                    }
                }
                let data = {
                    cita_paciente_id: this.datosCita.cita_paciente_id,
                    cantidad: this.cantidad_tecnica,
                    via: this.via_tecnica,
                    peso: this.peso_tecnica,
                    tiempo: this.tiempo_tecnica,
                    exposicion: this.exposicion,
                    mas: this.mAs,
                    kv: this.kv,
                    distancia: this.distancia,
                    foco: this.foco,
                    totaldosis: this.totaldosis,
                    observacion: this.observacion_tecnologo
                }
                axios.post('/api/imagenologia/saveEstudio', data).then(res => {
                    this.$alerSuccess("Estudio guardado con exito!");
                    this.closetecnologo();
                }).catch(err => {
                    let msg = '';
                    for (const pro in err.response.data) {
                        if (msg) msg += `${err.response.data[pro]}`
                        else msg = err.response.data[pro]
                    }
                    this.$alerError(msg);
                })
            },
            acomuladoDosis(datos) {
                let data = {
                    paciente_id: datos.paciente_id
                }
                axios.post('/api/imagenologia/acomuladoDosis', data).then(res => {
                    this.dosisacomulado = res.data
                })
            },
            citapaciente(datos) {
                this.cita = {
                    id: datos.id,
                    Paciente_id: datos.paciente_id
                }
            },
            devolverAdmisiones() {
                swal({
                    title: "¿Está Seguro?",
                    text: "Sera devuelto a admisiones",
                    type: "success",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(async willDelete => {
                    if (willDelete) {
                        axios.put(`/api/imagenologia/devolverAdmisiones/${this.cita.id}`, this.cita).then(
                            res => {
                                this.dialogAccion = false
                                this.citasImagenes();
                                this.$alerSuccess("Devuelto a admisiones con exito!");
                            })
                    }
                });
            },
            confirmarEnfermera() {
                swal({
                    title: "¿Está Seguro?",
                    text: "El paciente seguira su atención",
                    type: "success",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(async willDelete => {
                    if (willDelete) {
                        axios.put(`/api/imagenologia/confirmarEnfermeria/${this.cita.id}`, this.cita).then(
                            res => {
                                this.dialogAccion = false
                                this.citasImagenes();
                                this.$alerSuccess("Paciente sigue su atención con exito!");
                            })
                    }
                });
            },
            confirmarTecnologo() {
                swal({
                    title: "¿Está Seguro?",
                    text: "El paciente seguira su atención",
                    type: "success",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(async willDelete => {
                    if (willDelete) {
                        axios.put(`/api/imagenologia/confirmarTecnologo/${this.cita.id}`, this.cita).then(
                            res => {
                                this.dialogAccion = false
                                this.citasImagenes();
                                this.$alerSuccess("Paciente sigue su atención con exito!");
                            })
                    }
                });
            },
            confirmarEnfermeria() {
                const msg = "Esta seguro de enviar a enfermeria?";
                swal({
                    title: msg,
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.put(`/api/cita/confirmar/${this.cita.id}`, this.cita)
                            .then((res) => {
                                this.dialogAccion = false
                                swal({
                                    title: "¡Cita enviada a enfermeria!",
                                    text: `${res.data.message}`,
                                    timer: 3000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.citasImagenes();
                            })
                    }
                });
            },
            radiologoHistoria(datos) {
                this.historiaRadiologo = true;
                this.gestion(datos);
            },
            saveTecnica(datos) {
                if (!datos.tecnica_radiologo) return;
                let data = {
                    citapaciente: datos.cita_paciente_id,
                    tecnica: datos.tecnica_radiologo
                }
                axios.put(`/api/imagenologia/saveTecnica`, data).then(res => {
                    this.historiaRadiologo = false;
                    this.citasImagenes();
                    this.$alerSuccess("Tecnica guardada con exito!");
                })
            },
            ColorPrioridad(prioridad) {
                if (prioridad == 'Urgente') {
                    return 'error white--text';
                } else {
                    return 'success white--text';
                }
            },
            getinsumoguardados(datos) {
                let data = {
                    citapaciente: datos.cita_paciente_id
                }
                axios.post('/api/imagenologia/insumosGuardados', data).then(res => {
                    this.insumosguardados = res.data
                })
            },
            finalizarAtencion(datos) {
                swal({
                    title: "¿Está Seguro?",
                    text: "El paciente finalizara su atención",
                    type: "success",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(async willDelete => {
                    if (willDelete) {
                        axios.put(`/api/cita/update-state-atendida/${datos.cita_paciente_id}`).then(res => {
                            this.citasImagenes();
                            this.$alerSuccess("Atención finalizada con exito!");
                        });
                    }
                });
            },
            cambiarEstadoinsumo(insumo) {
                axios.put(`/api/imagenologia/cambiarEstadoinsumo/${insumo.id}`).then(res => {
                    this.dialogInsumos = false
                    this.$alerSuccess("Insumo eliminado con exito!");
                });
            }

        }
    }

</script>
