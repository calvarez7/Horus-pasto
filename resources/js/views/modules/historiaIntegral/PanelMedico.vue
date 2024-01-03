<template>
    <div>
        <v-container pa-0 fluid grid-list>
            <v-card height="55px">
                <v-bottom-nav :active.sync="bottomNav" :value="true" absolute>
                    <v-btn color="primary" flat
                        @click="individuales = true, grupales = false, ocupacionales = false, noProgramada = false, asistenciaEducativa = false, demandaInducina = false, individualesCitas()">
                        <span class="material-icons-outlined">Individual</span>
                        <v-icon>mdi-account-box</v-icon>
                    </v-btn>

                    <v-btn color="primary" flat
                        @click="noProgramada = true, individuales = false, grupales = false, ocupacionales = false, asistenciaEducativa = false, demandaInducina = false, citasNoProgramadas()">
                        <span class="material-icons-outlined">No programadas</span>
                        <v-icon>mdi-alarm-bell</v-icon>
                    </v-btn>

                    <v-btn color="primary" flat
                        @click="grupales = true, individuales = false, ocupacionales = false, noProgramada = false, asistenciaEducativa = false, demandaInducina = false, sedesAgendas() ">
                        <span class="material-icons-outlined">Grupales</span>
                        <v-icon>wc</v-icon>
                    </v-btn>

                    <v-btn color="primary" flat
                        @click="asistenciaEducativa = true, grupales = false, individuales = false, ocupacionales = false, noProgramada = false, demandaInducina = false">
                        <span class="material-icons-outlined">Asistencia Educativa</span>
                        <v-icon>wc</v-icon>
                    </v-btn>

                    <v-btn color="primary" flat
                        @click="demandaInducina = true, asistenciaEducativa = false, grupales = false, individuales = false, ocupacionales = false, noProgramada = false">
                        <span class="material-icons-outlined">Demanda Inducida</span>
                        <v-icon>mdi-account-convert</v-icon>
                    </v-btn>

                    <v-btn color="primary" flat
                        @click="ocupacionales = true, individuales = false, grupales = false, noProgramada = false, asistenciaEducativa = false, demandaInducina = false, ocupacionalesCitas()">
                        <span class="material-icons-outlined">Ocupacionales</span>
                        <v-icon>mdi-worker</v-icon>
                    </v-btn>
                </v-bottom-nav>
            </v-card>

            <v-layout row wrap>
                <v-flex xs12 sm12 md12>
                    <v-card max-height="100%" class="mb-3" v-show="individuales">
                        <v-card-title class="headline success" style="color:white">
                            <span class="justify-center title layout font-weight-light">Historia Integral</span>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-title>
                            Bienvenid@ - <span> - <b>{{userLogin}}</b></span>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                            </v-text-field>
                        </v-card-title>
                        <!-- ATENCION INDIVIDUAL -->
                        <v-data-table :headers="headers" :items="citas" :search="search" hide-actions>
                            <template v-slot:items="props">
                                <td>{{props.item.cita_paciente_id}}</td>
                                <td>{{props.item.Hora_Inicio | atencion}}</td>
                                <td>{{ props.item.Primer_Nom}} {{ props.item.SegundoNom}} {{ props.item.Primer_Ape}}
                                    {{ props.item.Segundo_Ape}}</td>
                                <td>{{ props.item.Tipo_Doc }}</td>
                                <td>{{ props.item.Num_Doc }}</td>
                                <td v-if="props.item.Sexo == 'M'">Masculino</td>
                                <td v-if="props.item.Sexo == 'F'">Femenino</td>
                                <td>{{ props.item.Edad_Cumplida }}</td>
                                <td>{{ props.item.consultorio }}</td>
                                <td v-if="props.item.salud_ocupacional != null">{{ props.item.salud_ocupacional }}</td>
                                <td>{{ props.item.Especialidad }}</td>
                                <td v-if="props.item.salud_ocupacional == null">{{ props.item.Tipo_agenda }}</td>
                                <td>{{ props.item.Estado }}</td>
                                <td>{{ props.item.marcacion }}</td>
                                <td>
                                    <div class="text-xs-center">
                                        <v-tooltip bottom v-if="props.item.CP_Estado_id == 4">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="success" v-on="on"
                                                    @click="Newatender(props.item)">
                                                    <v-icon dark>mdi-account</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Atender Paciente</span>
                                        </v-tooltip>
                                        <v-tooltip bottom v-if="props.item.CP_Estado_id == 8">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="warning" v-on="on"
                                                    @click="atender(props.item)">
                                                    <v-icon dark>mdi-account</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Atender</span>
                                        </v-tooltip>
                                        <v-tooltip bottom
                                            v-if="(props.item.CP_Estado_id == 8) || (props.item.CP_Estado_id == 4)">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="red" v-on="on"
                                                    @click="pacienteInasiste(props.item)">
                                                    <v-icon dark>mdi-account-off</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Inasistio</span>
                                        </v-tooltip>
                                        <v-tooltip bottom v-if="props.item.CP_Estado_id == 9">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="primary" v-on="on"
                                                    @click="modalHistoria(props.item)">
                                                    <v-icon dark>mdi-content-paste</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Imprimir</span>
                                        </v-tooltip>
                                    </div>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-flex xs12 sm12 md12>
                    <v-card max-height="100%" class="mb-3" v-show="noProgramada">
                        <v-card-title class="headline success" style="color:white">
                            <span class="justify-center title layout font-weight-light">Citas no Programadas</span>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-form>
                                <v-container pa-0 fluid grid-list>
                                    <v-layout row wrap>
                                        <v-flex xs12 sm12 md3>
                                            <v-text-field :error-messages="errores.cedula_paciente"
                                                v-model="citanoProgramadas.cedula_paciente" label="Número de Documento">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm12 md3>
                                            <v-autocomplete label="Cup" :items="cups" item-text="Nombre" item-value="id"
                                                v-model="citanoProgramadas.Cup_id" required></v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm12 md3>
                                            <v-autocomplete :items="especialidad" label="Especialidad de la atencion"
                                                append-icon="search" item-text="Nombre" item-value="id"
                                                v-model="citanoProgramadas.especialidad" @change="fetchActividades()">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm12 md3>
                                            <v-autocomplete :items="actividades" v-model="citanoProgramadas.actividad"
                                                item-text="nombreActividad" item-value="tipoAgenda"
                                                label="Actividad de la consulta">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm12 md3>
                                            <v-autocomplete :items="sedes" label="Sede atención" append-icon="search"
                                                item-text="nombre" item-value="id"
                                                v-model="citanoProgramadas.lugar_atencion">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-btn color="success" class="ma-2 white--text" @click="atenderNoprogramada()">
                                            Atender Paciente No Programado
                                            <v-icon right dark>send</v-icon>
                                        </v-btn>
                                    </v-layout>
                                </v-container>
                            </v-form>

                        </v-card-text>

                        <v-card-title>
                            Bienvenid@ - <span> - <b>{{userLogin}}</b></span>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                            </v-text-field>
                        </v-card-title>
                        <!-- ATENCION NO PROGRAMADA -->
                        <v-data-table :headers="headersNoProgramada" :items="NoProgramadas" hide-actions
                            :search="search">
                            <template v-slot:items="props">
                                <td>{{props.item.cita_paciente_id}}</td>
                                <td>{{props.item.created_at | atencion}}</td>
                                <td>{{ props.item.Primer_Nom}} {{ props.item.SegundoNom}}
                                    {{ props.item.Primer_Ape}}
                                    {{ props.item.Segundo_Ape}}</td>
                                <td>{{ props.item.Tipo_Doc }}</td>
                                <td>{{ props.item.Num_Doc }}</td>
                                <td>{{ props.item.Edad_Cumplida }}</td>
                                <td v-if="props.item.Sexo == 'M'">Masculino</td>
                                <td v-if="props.item.Sexo == 'F'">Femenino</td>
                                <td>{{ props.item.Especialidad }}</td>
                                <td>{{ props.item.Tipo_agenda }}</td>
                                <td>{{ props.item.Estado }}</td>
                                <td>
                                    <div class="text-xs-center">
                                        <v-tooltip bottom v-if="props.item.CP_Estado_id == 8">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="warning" v-on="on"
                                                    @click="atender(props.item)">
                                                    <v-icon dark>mdi-account</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Atender</span>
                                        </v-tooltip>
                                        <v-tooltip bottom v-if="props.item.CP_Estado_id != 9">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="red" v-on="on"
                                                    @click="pacienteInasiste(props.item)">
                                                    <v-icon dark>mdi-account-off</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Inasistio</span>
                                        </v-tooltip>
                                        <v-tooltip bottom v-if="props.item.CP_Estado_id == 9">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="primary" v-on="on"
                                                    @click="modalHistoria(props.item)">
                                                    <v-icon dark>mdi-content-paste</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>imprimir</span>
                                        </v-tooltip>
                                    </div>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-flex xs12 sm12 md12 lg12 xl12>
                    <v-card max-height="100%" class="mb-3" v-show="ocupacionales">
                        <v-card-title class="headline success" style="color:white">
                            <span class="justify-center title layout font-weight-light">Historia Ocupacional</span>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-title>
                            Bienvenid@ - <span> - <b>{{userLogin}}</b></span>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                            </v-text-field>
                        </v-card-title>
                        <!-- CONTROL DE ATENCION OCUPACIONAL -->
                        <v-data-table :headers="headersOcupacionales" :items="citasOcupacionales"
                            item-key="datosCita.cita_paciente_id" :search="search">
                            <template v-slot:items="props">
                                <td>{{props.item.Hora_Inicio | atencion}}</td>
                                <td>{{ props.item.Primer_Nom}} {{ props.item.SegundoNom}} {{ props.item.Primer_Ape}}
                                    {{ props.item.Segundo_Ape}}</td>
                                <td>{{ props.item.Tipo_Doc }}</td>
                                <td>{{ props.item.Num_Doc }}</td>
                                <td v-if="props.item.Sexo == 'M'">Masculino</td>
                                <td v-if="props.item.Sexo == 'F'">Femenino</td>
                                <td v-if="props.item.salud_ocupacional != null">{{ props.item.salud_ocupacional }}</td>
                                <td v-if="props.item.salud_ocupacional == null">{{ props.item.Tipo_agenda }}</td>
                                <td>{{ props.item.Estado }}</td>
                                <td>
                                    <div class="text-xs-center">
                                        <v-tooltip bottom v-if="props.item.CP_Estado_id == 4">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="success" v-on="on"
                                                    @click="atender(props.item)">
                                                    <v-icon dark>mdi-account</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Atender</span>
                                        </v-tooltip>
                                        <v-tooltip bottom v-if="props.item.CP_Estado_id == 8">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="warning" v-on="on"
                                                    @click="atender(props.item)">
                                                    <v-icon dark>mdi-account</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Atender</span>
                                        </v-tooltip>
                                        <v-tooltip bottom v-if="props.item.CP_Estado_id != 9">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="red" v-on="on"
                                                    @click="pacienteInasiste(props.item)">
                                                    <v-icon dark>mdi-account-off</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Inasistio</span>
                                        </v-tooltip>
                                        <v-tooltip bottom v-if="props.item.CP_Estado_id == 9">
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="primary" v-on="on"
                                                    @click="printhc(props.item.cita_paciente_id)">
                                                    <v-icon dark>mdi-content-paste</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>imprimir</span>
                                        </v-tooltip>
                                    </div>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout>
                <v-flex xs12 sm12 md12 lg12 xl12>
                    <v-card max-height="100%" class="mb-3" v-show="asistenciaEducativa">
                        <v-card-title class="headline success" style="color:white">
                            <span class="justify-center title layout font-weight-light">Asistencia Educativa</span>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-title>
                            Bienvenid@ - <span> - <b>{{userLogin}}</b></span>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="asistencia.documento" label="Ingrese el documento del paciente"
                                @keyup.enter="consultarPaciente()">
                            </v-text-field>
                        </v-card-title>
                        <v-container grid-list-md fluid class="pa-0" v-if="can('reporte.asistencia')">
                            <v-card-title class="headline success" style="color:white">
                                <span class="justify-center title layout font-weight-light">Reporte asistencia
                                    educativa</span>
                            </v-card-title>
                            <v-card-text>
                                <v-layout row wrap>
                                    <v-flex xs12 sm12 md6>
                                        <v-text-field v-model="reporteEducativas.fecha_inicial" type="date"
                                            label="Fecha inicial">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm12 md6>
                                        <v-text-field v-model="reporteEducativas.fecha_final" type="date"
                                            label="Fecha final">
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" rounded @click="reporteAsistenciaEducativa()">
                                    Descargar reporte
                                </v-btn>
                            </v-card-actions>
                        </v-container>
                    </v-card>
                </v-flex>
            </v-layout>

            <!-- DEMANDA INDUCIDA -->
            <!-- <v-layout row wrap> -->
            <v-flex xs12 sm12 md12 lg12 xl12>
                <v-card max-height="100%" class="mb-3" v-show="demandaInducina">
                    <v-card-title class="headline success" style="color:white">
                        <span class="justify-center title layout font-weight-light">Demanda Inducida</span>
                    </v-card-title>
                    <v-container grid-list-xl justify-end>
                        <v-layout row wrap>
                            <v-flex xs8 offset-xs8>
                                <v-text-field label="Ingrese el Documento del Paciente...." v-model="buscarAfiliado"
                                    append-icon="search" @keyup.enter="demandaInducidaPaciente()" clearable
                                    @click:clear="clearData">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                    <v-form v-if="pacienteDemandaInducida.Num_Doc != ''">
                        <v-card-title class="headline primary" style="color:white">
                            <span class="justify-center title layout font-weight-light">Datos del afiliado</span>
                        </v-card-title>
                        <v-card-title>
                            <form>
                                <v-layout row wrap>
                                    <v-toolbar dark color="success">
                                        <v-toolbar-title style="font-size: 12px;"><b style="color:blue">Tipo Doc:</b>
                                            {{pacienteDemandaInducida.Tipo_Doc}}</v-toolbar-title>
                                        <v-toolbar-title style="font-size: 12px;"><b style="color:blue">DOCUMENTO:</b>
                                            {{pacienteDemandaInducida.Num_Doc}}</v-toolbar-title>
                                        <v-toolbar-title style="font-size: 12px;"><b style="color:blue">CICLO VITAL:</b>
                                            {{pacienteDemandaInducida.ciclo_vida}}
                                        </v-toolbar-title>
                                        <v-toolbar-title style="font-size: 12px;"><b style="color:blue">TUTELA:</b>
                                            {{pacienteDemandaInducida.Tienetutela == true?'SI':'NO'}}
                                        </v-toolbar-title>
                                        <v-toolbar-title style="font-size: 12px;"><b style="color:blue">CÓDIGO
                                                BLANCO:</b>
                                            {{pacienteDemandaInducida.victima_conficto_armado == true?'SI':'NO'}}
                                        </v-toolbar-title>
                                        <v-toolbar-title style="font-size: 12px;"><b style="color:blue">PORTABILIAD:</b>
                                            {{pacienteDemandaInducida.portabilidad == true?'SI':'NO'}}
                                        </v-toolbar-title>
                                        <v-toolbar-title style="font-size: 12px;"><b style="color:blue">EDAD:</b>
                                            {{pacienteDemandaInducida.Edad_Cumplida}}
                                        </v-toolbar-title>
                                        <v-toolbar-title style="font-size: 12px;"><b style="color:blue">SEXO:</b>
                                            {{pacienteDemandaInducida.Sexo == 'F'?'Femenino': 'Masculino'}}
                                        </v-toolbar-title>
                                        <v-spacer></v-spacer>
                                        <v-btn small flat>
                                            {{ pacienteDemandaInducida.Primer_Nom }}
                                            {{ pacienteDemandaInducida.Primer_Ape }}{{ pacienteDemandaInducida.Segundo_Ape }}
                                            <v-flex ml-2>
                                                <v-avatar size="40">
                                                    <v-icon dark>mdi-account</v-icon>
                                                </v-avatar>
                                            </v-flex>
                                        </v-btn>
                                    </v-toolbar>
                                    <v-layout>
                                        <v-form>
                                            <v-container fluid>
                                                <v-layout row wrap>
                                                    <v-flex xs12 sm6 md2>
                                                        <v-text-field readonly
                                                            v-model="pacienteDemandaInducida.Departamento"
                                                            label="Departamento"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md3>
                                                        <v-text-field readonly
                                                            v-model="pacienteDemandaInducida.Municipio_Atencion"
                                                            label="Municipio atención"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md5>
                                                        <v-text-field readonly
                                                            v-model="pacienteDemandaInducida.Punto_Atencion"
                                                            label="IPS asignada">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md2>
                                                        <v-text-field readonly
                                                            v-model="pacienteDemandaInducida.Fecha_Naci"
                                                            label="Fecha Nacimiento"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md5>
                                                        <v-text-field readonly
                                                            v-model="pacienteDemandaInducida.Direccion_Residencia"
                                                            label="Direcciónn"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md2>
                                                        <v-text-field readonly
                                                            v-model="pacienteDemandaInducida.Telefono" label="Teléfono">
                                                        </v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-form>
                                    </v-layout>


                                    <v-flex xs12>
                                        <!-- GUARDAR ANTECEDENTES PERSONALES -->
                                        <v-container grid-list-xs>
                                            <v-layout row wrap>
                                                <v-container fluid grid-list-xl>
                                                    <v-layout wrap align-center>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-autocomplete v-model="pacienteDemandaInducida.cie10_id"
                                                                append-icon="search" :items="cieConcat"
                                                                item-disabled="customDisabled" item-text="nombre"
                                                                label="Antecedentes personales">
                                                            </v-autocomplete>
                                                        </v-flex>

                                                        <v-flex xs12 sm6 md6>
                                                            <v-textarea solo name="input-1-1"
                                                                label="Escribir Descripcion Antecedente"
                                                                v-model="pacienteDemandaInducida.Descripcion">
                                                            </v-textarea>
                                                        </v-flex>

                                                        <v-flex xs12 sm1 md1>
                                                            <v-btn fab dark color="success"
                                                                @click="guardarAntecedentesPersonales()">
                                                                <v-icon dark>add</v-icon>
                                                            </v-btn>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-layout>
                                        </v-container>

                                        <v-card-title primary-title>
                                            <v-flex xs12 sm12 d-flex>
                                                <v-data-table :items="allAntecedentesPaciente"
                                                    :headers="headerAntecedentePaciente" hide-actions
                                                    class="elevation-1">
                                                    <template v-slot:items="props">
                                                        <td>{{ props.item.created_at }}</td>
                                                        <td class="text-xs">{{ props.item.medico }}</td>
                                                        <td class="text-xs">{{ props.item.patologias }}</td>
                                                        <td class="text-xs">
                                                            <v-tooltip top>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn text icon color="red" dark v-on="on">
                                                                        <v-icon
                                                                            @click="deleteAntecedente(props.item.id)">
                                                                            mdi-delete-forever
                                                                        </v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Historial</span>
                                                            </v-tooltip>
                                                        </td>
                                                    </template>
                                                </v-data-table>
                                            </v-flex>
                                        </v-card-title>
                                        <v-card-title class="headline primary" style="color:white">
                                            <span class="justify-center title layout font-weight-light">Programa a
                                                direccionar</span>
                                        </v-card-title>
                                        <v-form>
                                            <v-container fluid>
                                                <v-layout row wrap>
                                                    <v-flex xs1 sm1 md1>
                                                        <v-checkbox
                                                            v-model="pacienteDemandaInducida.demanda_inducida_efectiva"
                                                            label="Efectiva" color='primary'></v-checkbox>
                                                    </v-flex>
                                                    <v-flex xs12 sm12 md3>
                                                        <v-autocomplete
                                                            v-model="pacienteDemandaInducida.tipoDemandaInducida"
                                                            :items='tiposDemandaInducida' label="Tipo demanda inducida">
                                                        </v-autocomplete>
                                                    </v-flex>
                                                    <v-flex xs12 sm12 md3>
                                                        <v-autocomplete
                                                            v-model="pacienteDemandaInducida.programaremitido"
                                                            :items='listaDemandaInducida'
                                                            label="Programa de demanda inducida"
                                                            @change="limpiarListaInducida()">
                                                        </v-autocomplete>
                                                    </v-flex>
                                                    <v-flex xs12 sm12 md4
                                                        v-if="pacienteDemandaInducida.programaremitido == 'RIESGO CARDIOVASCULAR'">
                                                        <v-text-field type='date'
                                                            v-model="pacienteDemandaInducida.fecha_dx_riesgocardiovascular"
                                                            label="Fecha dx riesgo cardio vascular"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm12 md5
                                                        v-if="pacienteDemandaInducida.programaremitido == 'EVENTOS SALUD PUBLICA'">
                                                        <v-textarea solo name="input-7-4"
                                                            v-model="pacienteDemandaInducida.descripcion_evento_saludpublica"
                                                            label="Descripcion de eventos de salud publica">
                                                        </v-textarea>
                                                    </v-flex>
                                                    <v-flex xs12 sm12 md5
                                                        v-if="pacienteDemandaInducida.programaremitido == 'OTROS PROGRAMAS'">
                                                        <v-textarea solo name="input-7-4"
                                                            v-model="pacienteDemandaInducida.descripcion_otro_programa"
                                                            label="Descripcion de otro programa">
                                                        </v-textarea>
                                                    </v-flex>
                                                    <v-flex>
                                                        <v-textarea solo name="input-7-4"
                                                            v-model="pacienteDemandaInducida.observacion"
                                                            label="Observaciones">
                                                        </v-textarea>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-form>
                                    </v-flex>
                                </v-layout>
                            </form>

                        </v-card-title>
                        <div class="text-xs-center">
                            <v-btn round color="success" dark @click="saveDemandaInducida()">Generar demanda
                                inducida</v-btn>
                        </div>
                    </v-form>
                </v-card>
            </v-flex>
            <!-- </v-layout> -->

            <v-layout>
                <v-flex xs12 sm12 md12 lg12 xl12>
                    <v-card max-height="100%" class="mb-3" v-show="grupales">
                        <v-card-title class="headline success" style="color:white">
                            <span class="justify-center title layout font-weight-light">Historia Integral
                                Grupales</span>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-title>
                            Bienvenid@ - <span> - <b>{{userLogin}}</b></span>
                            <v-spacer></v-spacer>
                            <v-flex xs3 sm3 md3>
                                <v-autocomplete v-model="grup.sede" :items="sedesAgenda" append-icon="search"
                                    item-text="Nombre" item-value="id" @change="grupalesCitas(grup.sede)" label="Sede">
                                </v-autocomplete>
                            </v-flex>
                            <v-spacer></v-spacer>
                            <v-flex xs3 sm3 md3>
                                <v-text-field v-model="searchDocumentoGrupales" append-icon="search"
                                    label="Documento del Paciente" @change="grupalesCitas(searchDocumentoGrupales)">
                                </v-text-field>
                            </v-flex>
                            <v-spacer></v-spacer>
                        </v-card-title>
                        <v-expansion-panel>
                            <v-expansion-panel-content v-for="(citas, index) in citasGrupos" :key="index">
                                <template v-slot:header>
                                    <v-btn color="primary">
                                        <div>{{citas.Especialidad}} ({{citas.Tipo_agenda}}) - {{citas.Hora_Inicio}}
                                            hasta {{citas.Hora_Final}}
                                            ({{citas.sede}}-{{citas.consultorio}})</div>
                                    </v-btn>
                                </template>
                                <v-card>
                                    <!-- CONTROL DE ATENCION GRUPALES -->
                                    <v-card-title>
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="searchGrupales" append-icon="search" label="Search"
                                            single-line hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headersGrupales" :items="citas.citas"
                                        :search="searchGrupales" class="elevation-1" hide-actions>
                                        <template v-slot:items="props">
                                            <td>{{props.item.cita_paciente_id}}</td>
                                            <td>{{ props.item.Primer_Nom}} {{ props.item.SegundoNom}}
                                                {{ props.item.Primer_Ape}}
                                                {{ props.item.Segundo_Ape}}</td>
                                            <td>{{ props.item.Tipo_Doc}}</td>
                                            <td>{{ props.item.Num_Doc}}</td>
                                            <td v-if="props.item.Sexo == 'M'">Masculino</td>
                                            <td v-if="props.item.Sexo == 'F'">Femenino</td>
                                            <td>{{ props.item.Hora_Inicio }}</td>
                                            <td>{{ props.item.Estado }}</td>
                                            <td>
                                                <div class="text-xs-center">
                                                    <v-tooltip bottom v-if="props.item.CP_Estado_id == 4">
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn fab dark small color="success" v-on="on"
                                                                @click="atender(props.item)">
                                                                <v-icon dark>mdi-account</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Atender</span>
                                                    </v-tooltip>
                                                    <v-tooltip bottom v-if="props.item.CP_Estado_id == 8">
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn fab dark small color="warning" v-on="on"
                                                                @click="atender(props.item)">
                                                                <v-icon dark>mdi-account</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Atender</span>
                                                    </v-tooltip>
                                                    <v-tooltip bottom v-if="props.item.CP_Estado_id != 9">
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn fab dark small color="red" v-on="on"
                                                                @click="pacienteInasiste(props.item)">
                                                                <v-icon dark>mdi-account-off</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Inasistio</span>
                                                    </v-tooltip>
                                                    <v-tooltip bottom v-if="props.item.CP_Estado_id == 9">
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn fab dark small color="primary" v-on="on"
                                                                @click="dialogHistoriaPrint(props.item)">
                                                                <v-icon dark>mdi-content-paste</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>imprimir</span>
                                                    </v-tooltip>
                                                </div>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-card>
                </v-flex>
            </v-layout>

            <v-dialog v-if="dialogAtender" v-model="dialogAtender" persistent fullscreen hide-overlay
                transition="dialog-bottom-transition">
                <v-card>
                    <v-toolbar dark color="success">

                        <!-- <v-btn icon dark @click="closeHistoria()">
                            <v-icon>close</v-icon>
                        </v-btn> -->
                        <v-toolbar-title style="font-size: 12px;">HC: {{datosCita.cita_paciente_id}}</v-toolbar-title>
                        <v-toolbar-title style="font-size: 12px;">Ciclo vital: {{datosCita.ciclo_vital}}
                        </v-toolbar-title>
                        <v-toolbar-title style="font-size: 12px;">Cedula: {{datosCita.cedula}}</v-toolbar-title>
                        <v-toolbar-title style="font-size: 12px;">Edad: {{datosCita.Edad_Cumplida}}</v-toolbar-title>
                        <v-toolbar-title style="font-size: 12px;">Genero:
                            {{datosCita.sexo == 'F'? 'Femenino':'Masculino'}}</v-toolbar-title>
                        <v-toolbar-title style="font-size: 12px;">Código blanco:
                            {{datosCita.victima_conficto_armado == true ?'Si':'No'}}</v-toolbar-title>
                        <v-toolbar-title style="font-size: 12px;">Paciente Domiciliario:
                            {{datosCita.domiciliaria == true ?'Si':'No'}}</v-toolbar-title>
                        <v-toolbar-title style="font-size: 12px;">Paciente en Portabilidad:
                            {{datosCita.portabilidad == true ?'Si':'No'}}</v-toolbar-title>

                        <v-spacer></v-spacer>
                        <v-btn color="warning" @click="closeHistoria()">En espera<v-icon right dark>close</v-icon></v-btn>
                        <v-spacer></v-spacer>
                        <!-- <v-toolbar-title v-if="valueDeterminate <= 100">
                            <v-btn color="primary" round small dark @click="historicoAtencion()">
                                Mi Historico de esta Atención
                                <v-icon right dark>mdi-content-save-all</v-icon>
                            </v-btn>
                        </v-toolbar-title> -->
                        <v-toolbar-title v-if="valueDeterminate >= 100">
                            <!-- <v-btn color="primary" round small dark @click="closeHistoria()"
                                v-if="comp == 'OrdenamientoComponent'">
                                Regresar sin cerrar la historia
                                <v-icon right dark>mdi-arrow-left-box</v-icon>
                            </v-btn> -->
                            <v-btn color="warning" round small dark @click="ordenamiento()"
                                v-if="(comp != 'OrdenamientoComponent') && (!datosCita.salud_ocupacional)">
                                Ordenar
                                <v-icon right dark>mdi-comment-text</v-icon>
                            </v-btn>
                            <v-btn color="red" round small dark @click="finalizarConsulta()">
                                Finalizar consulta
                                <v-icon right dark>mdi-content-save-all</v-icon>
                            </v-btn>
                        </v-toolbar-title>
                        <v-btn small flat>
                            {{ datosCita.nombre_paciente }}
                            <v-flex ml-2>
                                <v-avatar size="40">
                                    <v-icon dark>mdi-account</v-icon>
                                </v-avatar>
                            </v-flex>
                        </v-btn>
                    </v-toolbar>
                    <template v-if="comp != 'OrdenamientoComponent'">
                        <v-layout row wrap>
                            <v-flex xs12 sm12 md8>
                                <v-layout row style="margin-left: 24px; margin-right: 24px;"><span
                                        style="position: unset;margin-block-start: 8px"><b>{{valueDeterminate}}%</b></span>
                                    <v-progress-linear v-model="valueDeterminate"></v-progress-linear>
                                </v-layout>
                            </v-flex>
                            <v-flex xs12 sm12 md4>
                                <v-flex xs12 sm12 md12>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn flat icon color="warning" v-on="on" @click="historicoClinico()">
                                                <v-badge overlap color="primary">
                                                    <template v-if="conteoHistoria" v-slot:badge>
                                                        <span dark>{{conteoHistoria.count}}</span>
                                                    </template>
                                                    <v-icon>mdi-account-search</v-icon>
                                                </v-badge>
                                            </v-btn>
                                        </template>
                                        <span>Historal Clinico</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn flat icon color="pink" v-on="on" @click="historicoFarmacologia()">
                                                <v-badge overlap color="primary">
                                                    <template v-if="conteoFarmacologico" v-slot:badge>
                                                        <span dark>{{conteoFarmacologico.count}}</span>
                                                    </template>
                                                    <v-icon>mdi-medical-bag</v-icon>
                                                </v-badge>
                                            </v-btn>
                                        </template>
                                        <span>Farmacología</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn flat icon color="success" v-on="on" @click="historicoExamenes()">
                                                <v-badge overlap color="primary">
                                                    <template v-if="conteoServicios" v-slot:badge>
                                                        <span dark>{{conteoServicios.count}}</span>
                                                    </template>
                                                    <v-icon>mdi-book-open-page-variant</v-icon>
                                                </v-badge>
                                            </v-btn>
                                        </template>
                                        <span>Servicios</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn flat icon color="blue" v-on="on" @click="historicoIncapacidades()">
                                                <v-badge overlap color="primary">
                                                    <template v-if="conteoIncapacidades" v-slot:badge>
                                                        <span dark>{{conteoIncapacidades.count}}</span>
                                                    </template>
                                                    <v-icon>accessibility</v-icon>
                                                </v-badge>
                                            </v-btn>
                                        </template>
                                        <span>Incapacidades</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn flat icon color="red" v-on="on" @click="historicoAlertas()">
                                                <v-badge overlap color="primary">
                                                    <template v-if="conteoAlertas" v-slot:badge>
                                                        <span dark>{{conteoAlertas.count}}</span>
                                                    </template>
                                                    <v-icon>dangerous</v-icon>
                                                </v-badge>
                                            </v-btn>
                                        </template>
                                        <span>Alertas</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn flat icon color="blue" v-on="on" @click="resultadoProdiagnostico()">
                                                <v-badge overlap color="blue">
                                                    <v-icon>mdi-file-powerpoint-box</v-icon>
                                                </v-badge>
                                            </v-btn>
                                        </template>
                                        <span>Resultados Prodiagnostico</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn flat icon color="purple" v-on="on" @click="resultadoLaboratorio()">
                                                <v-badge overlap color="primary">
                                                    <v-icon>mdi-test-tube</v-icon>
                                                    <template v-if="conteoLaboratorio" v-slot:badge>
                                                        <span dark>{{conteoLaboratorio.count}}</span>
                                                    </template>
                                                </v-badge>
                                            </v-btn>
                                        </template>
                                        <span>Resultados Laboratorio</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn flat icon color="green" v-on="on" @click="enlaseDinamica()">
                                                <v-badge overlap color="success">
                                                    <v-icon>mdi-disqus</v-icon>
                                                </v-badge>
                                            </v-btn>
                                        </template>
                                        <span>Dinamica</span>
                                    </v-tooltip>
                                </v-flex>
                            </v-flex>
                        </v-layout>
                    </template>
                    <component v-if="dialogAtender" :is='comp' :datosCita="datosCita" @porcentaje="porcentaje" />
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogHistorico" v-model="dialogHistorico" persistent width="1500">
                <component :is='comph' :datosCita="datosCita" @respuestaComponente="dialogHistorico=false" />
            </v-dialog>

            <v-dialog v-model="dialogPreguntaAtender" persistent max-width="490">
                <v-card>
                    <v-card-title class="headline warning" style="color:white">
                        <span class="justify-center title layout font-weight-light">¿Esta Seguro en Atender el
                            Paciente?</span>
                    </v-card-title>
                    <v-card-text>
                        <v-flex row warp>
                            <v-flex xs12>
                                <center class="justify-center title layout font-weight-light">Recuerde que la historia
                                    debe
                                    ser completada en su totalidad!</center>
                            </v-flex>
                        </v-flex>
                        <v-flex row warp>
                            <v-flex xs12>
                                <h3 class="justify-center font-weight-light">Seleccione el tipo de cita que
                                    desea atender</h3>
                            </v-flex>
                            <v-flex xs12>
                                <v-autocomplete :items="newactividad" v-model="cita.actividad" item-text="Tipo_agenda"
                                    item-value="tipoAgenda">
                                </v-autocomplete>
                            </v-flex>
                        </v-flex>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" dark @click="dialogPreguntaAtender = false">Cerrar</v-btn>
                        <v-btn color="primary" dark @click="dialog = false, validadorCita(datos,cita.actividad)">
                            Atender!</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialogPreguntaCups" persistent max-width="1000">
                <v-card>
                    <v-card-title class="headline warning" style="color:white">
                        <span class="justify-center title layout font-weight-light">¿Esta Seguro en Atender al
                            Paciente?</span>
                    </v-card-title>
                    <v-card-text>
                        <v-flex row warp>
                            <v-flex xs12>
                                <center class="justify-center title layout font-weight-light">Recuerde que la historia
                                    debe
                                    ser completada en su totalidad!</center>
                            </v-flex>
                        </v-flex>
                        <v-flex row warp>
                            <v-flex xs12>
                                <h3 class="justify-center font-weight-light">Seleccione una orden</h3>
                            </v-flex>
                            <v-flex xs12>
                                <v-autocomplete :items="necesitaOrden" v-model="ordenNoprogramada.cuporden_id"
                                    item-text="NombreMostrar" item-value="cuporden_id">
                                </v-autocomplete>
                            </v-flex>
                        </v-flex>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" dark @click="dialogPreguntaCups = false, clearFilter()">Cerrar</v-btn>
                        <v-btn color="primary" dark @click="dialogPreguntaCups = false, validarEspecialidad()">
                            Atender!</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-if="dialogAsistenciaEducativa" v-model="dialogAsistenciaEducativa" persistent fullscreen
                hide-overlay transition="dialog-bottom-transition">
                <v-card>
                    <v-toolbar dark color="success">
                        <v-btn icon dark @click="closeAsistenciaEducativa()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title style="font-size: 12px;">Documento: {{datosPaciente.Num_Doc}}</v-toolbar-title>
                        <v-toolbar-title style="font-size: 12px;">Ciclo vital: {{datosPaciente.ciclo_vital}}
                        </v-toolbar-title>
                        <v-toolbar-title style="font-size: 12px;"><b style="color:red">Tutela:</b>
                            {{datosPaciente.Tienetutela == true?'SI':'NO'}}
                        </v-toolbar-title>
                        <v-toolbar-title style="font-size: 12px;">Edad: {{datosPaciente.Edad_Cumplida}}
                        </v-toolbar-title>
                        <v-toolbar-title style="font-size: 12px;">Sexo:
                            {{datosPaciente.sexo == 'F'?datosPaciente.sexo == 'Femenino': 'Masculino'}}
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn small flat>
                            {{ datosPaciente.Primer_Nom }} {{ datosPaciente.Primer_Ape }}{{ datosPaciente.Segundo_Ape }}
                            <v-flex ml-2>
                                <v-avatar size="40">
                                    <v-icon dark>mdi-account</v-icon>
                                </v-avatar>
                            </v-flex>
                        </v-btn>
                    </v-toolbar>
                    <v-spacer></v-spacer>
                    <v-form>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm12 md3>
                                    <v-text-field v-model="datosPaciente.fecha" label="FECHA" type="date">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md3>
                                    <v-text-field v-model="datosPaciente.Tipo_Doc" label="Tipo Documento" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md3>
                                    <v-text-field v-model="datosPaciente.Num_Doc" label="Documento" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md3>
                                    <v-text-field v-model="datosPaciente.Primer_Nom" label="Primer nombre" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md3 v-if="datosPaciente.SegundoNom != null">
                                    <v-text-field v-model="datosPaciente.SegundoNom" label="Segundo nombre" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md3>
                                    <v-text-field v-model="datosPaciente.Primer_Ape" label="Primer apellido" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md3>
                                    <v-text-field v-model="datosPaciente.Segundo_Ape" label="Segundo apellido" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md3>
                                    <v-text-field v-model="datosPaciente.Edad_Cumplida" label="Edad" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md3>
                                    <v-text-field v-model="datosPaciente.Ut" label="Asegurador" readonly></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md4>
                                    <v-text-field v-model="datosPaciente.NombreIPS" label="NOMBRE IPS" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md5>
                                    <v-autocomplete label="Tipo de educación" :items="cupfilter" item-text="Nombre"
                                        item-value="id" return-object v-model="datosPaciente.cup_id" required>
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm12 md3>
                                    <v-text-field v-model="datosPaciente.tema" label="Tema">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" @click="registrar()">Guardar registro</v-btn>
                            </v-card-actions>
                        </v-container>
                    </v-form>

                </v-card>
            </v-dialog>

            <v-dialog v-model="preloasistenciaEscolar" persistent width="300">
                <v-card color="primary" dark>
                    <v-card-text>
                        Estamos procesando su información
                        <v-progress-linear indeterminate color="white" class="mb-0">
                        </v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-container>

        <div class="text-xs-center">
            <v-dialog v-model="dialogHistoriaPrint" width="900">
                <v-card>
                    <v-card-title class="headline primary" style="color:white" primary-title>
                        Enviar historia clinica por correo o imprimir: {{datosAfiliado.Nombre_Paciente}}
                    </v-card-title>

                    <v-card-text>
                        <v-flex xs12 sm6 md5>
                            <v-text-field v-model="datosAfiliado.Correo1" label="Email"></v-text-field>
                        </v-flex>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" dark @click="dialogHistoriaPrint = false">Cancelar</v-btn>
                        <v-btn color="success" dark @click="imprimirhc(datosAfiliado)">imprimir</v-btn>
                        <v-btn color="primary" @click="SendEmail(datosAfiliado)" round>Enviar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>
    </div>
</template>
<script>
    import moment from "moment";
    import Swal from 'sweetalert2';
    import {
        mapGetters
    } from "vuex";
    import ControlHistoria from '../../../components/historiaIntegral/controlHistoria.vue'
    import OrdenamientoComponent from '../../../components/historiaIntegral/OrdenamientoNew.vue'
    import ComponentHistorialClinico from '../../../components/historia_clinica/HistorialClinico.vue'
    import ComponentHistorialExamenes from '../../../components/historia_clinica/HistorialExamenes.vue'
    import ComponenteHistorialMedicamentos from '../../../components/historia_clinica/HistorialMedicamentos.vue'
    import ComponenteHistorialIncapacidades from '../../../components/historia_clinica/HistorialIncapacidades.vue'
    import ComponentHistoricoAtencion from '../../../components/historia_clinica/HistorialActividades.vue'
    import ComponenteHistorialAlertas from '../../../components/historia_clinica/HistorialAlertas.vue'
    import ComponenteHistoriaLaboratorio from '../../../components/historia_clinica/HistoriaLaboratorio.vue'
    import VagoutLayout from '../vagout/VagoutLayout.vue';
    import axios from 'axios';

    export default {
        components: {
            ControlHistoria,
            ComponentHistorialClinico,
            ComponentHistorialExamenes,
            ComponenteHistorialMedicamentos,
            ComponenteHistorialIncapacidades,
            OrdenamientoComponent,
            ComponentHistoricoAtencion,
            ComponenteHistorialAlertas,
            ComponenteHistoriaLaboratorio,
            VagoutLayout
        },
        data() {
            return {
                tiposDemandaInducida: ['DEMANDA INDUCIDA TELEFONICA', 'DEMANDA INDUCIDA PRESENCIAL',
                    'REMITIDO POR MEDICO O ENFERMERA', 'DEMANDA INDUCIDA ESPONTANEA', 'REFERIDO GESTORES'
                ],

                pacienteDemandaInducida: {
                    observacion: '',
                    Departamento: '',
                    municipio_nacimiento: '',
                    Primer_Nom: '',
                    SegundoNom: '',
                    Primer_Ape: '',
                    Segundo_Ape: '',
                    IPS: '',
                    Tipo_Doc: '',
                    Num_Doc: '',
                    Edad_Cumplida: '',
                    Fecha_Naci: '',
                    Sexo: '',
                    Direccion_Residencia: '',
                    Telefono: '',
                    tipoDemandaInducida: '',
                    programaremitido: '',
                    fecha_dx_riesgocardiovascular: '',
                    descripcion_evento_saludpublica: '',
                    descripcion_grupo_riesgo: '',
                    demanda_inducida_efectiva: false,
                },
                programaDemandaInducida: ['VACUNACION', 'CONSULTA PRIMERA INFANCIA  (0-5 años)',
                    'CONSULTA INFANCIA (6-11años)',
                    'CONSULTA ADOLESCENCIA (12-17 años)', 'CONSULTA JUVENTUD (18-28 años)',
                    'CONSULTA ADULTEZ (29-59 años)',
                    'CONSULTA VEJEZ (60 años o más)', 'PLANIFICACIÓN FAMILIAR',
                    'PRECONCEPCIONAL', 'CONTROL PRENATAL', 'CONSULTA NUTRICION PRENATAL', 'ASESORIA  LACTANCIA',
                    'CURSO PSICOPROFILACTICO',
                    'CITOLOGIA', 'MAMOGRAFIA', 'CONTROL POSPARTO', 'CONTROL RECIEN NACIDO', 'SALUD ORAL',
                    'TALLERES EDUCATIVOS', 'RIESGO CARDIOVASCULAR',
                    'EVENTOS SALUD PUBLICA', 'OTROS PROGRAMAS'
                ],
                DialogResultados: false,
                preload: false,
                valueDeterminate: 0,
                preloadmedico: false,
                dialogAtender: false,
                dialogPreguntaAtender: false,
                dialogPreguntaCups: false,
                dialogHistorico: false,
                searchDocumentoGrupales: '',
                comp: null,
                comph: null,
                userLogin: '',
                citas: [],
                newactividad: [],
                datos: {},
                cita: {
                    actividad: '',
                },
                ordenNoprogramada: {
                    cuporden_id: null,
                },
                citanoProgramadas: {
                    cedula_paciente: '',
                    Cup_id: '',
                    lugar_atencion: '',
                    especialidad: '',
                    actividad: '',
                },
                cups: [],
                sedes: [],
                allAntecedentesPaciente: [],
                grup: {
                    sede: '',
                },
                search: '',
                sedesAgenda: [],
                paciente: [],
                actividades: [],
                especialidad: [],
                citasGrupos: [],
                cupfilter: [],
                citasOcupacionales: [],
                NoProgramadas: [],
                individuales: true,
                noProgramada: false,
                ocupacionales: false,
                demandaInducina: false,
                grupales: false,
                asistenciaEducativa: false,
                bottomNav: 0,
                buscarAfiliado: '',
                headersGrupales: [{
                        text: 'ID',
                        align: 'left',
                        value: 'cita_paciente_id'
                    }, {
                        text: 'Paciente',
                        align: 'left',
                        value: 'Primer_Nom'
                    },
                    {
                        text: 'Tipo Documento',
                        value: 'Tipo_Doc',
                        align: 'left',
                    },
                    {
                        text: 'Numero Documento',
                        value: 'Num_Doc',
                        align: 'left',
                    },
                    {
                        text: 'Sexo',
                        value: 'Sexo',
                        align: 'left',
                    },
                    {
                        text: 'Hora Cita',
                        align: 'left',
                        value: 'Hora_Inicio'
                    },
                    {
                        text: 'Estado',
                        align: 'left',
                        value: 'Estado'
                    },
                    {
                        text: 'Acciones',
                        align: 'left',
                    }
                ],

                headerAntecedentePaciente: [

                    {
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                ],
                headers: [{
                        text: 'id',
                        sortable: false,
                    },
                    {
                        text: 'Hora',
                        sortable: false,
                    },
                    {
                        text: 'Nombre',
                        sortable: false,
                    },
                    {
                        text: 'Tipo Doc',
                        sortable: false,
                    },
                    {
                        text: 'Documento',
                        sortable: false,
                    },
                    {
                        text: 'Sexo',
                        sortable: false,
                    },
                    {
                        text: 'Edad',
                        sortable: false,
                    },
                    {
                        text: 'Sede',
                        sortable: false,
                    },
                    {
                        text: 'Especialidad',
                        sortable: false,
                    },
                    {
                        text: 'Actividad',
                        sortable: false,
                    },
                    {
                        text: 'Estado',
                        sortable: false,
                    },
                    {
                        text: 'Aciones',
                        value: 'Aciones',
                        align: 'rigth',
                    }
                ],
                headersOcupacionales: [{
                        text: 'Hora',
                        sortable: false,
                    },
                    {
                        text: 'Nombre',
                        sortable: false,
                    },
                    {
                        text: 'Tipo Doc',
                        sortable: false,
                    },
                    {
                        text: 'Documento',
                        sortable: false,
                    },
                    {
                        text: 'Especialidad',
                        sortable: false,
                    },
                    {
                        text: 'Estado',
                        sortable: false,
                    },
                    {
                        text: 'Aciones',
                        value: 'Aciones',
                        align: 'rigth',
                    }
                ],
                headersNoProgramada: [{
                        text: 'id',
                        align: 'left',
                    },
                    {
                        text: 'Hora',
                        align: 'left',
                    },
                    {
                        text: 'Paciente',
                        align: 'left'
                    },
                    {
                        text: 'Tipo Documento',
                        value: 'Tipo_Doc',
                        align: 'left',
                        sortable: false,
                    },
                    {
                        text: 'Numero Documento',
                        value: 'Num_Doc',
                        align: 'left',
                    },
                    {
                        text: 'Edad',
                        value: 'Edad_Cumplida',
                        align: 'left',
                    },
                    {
                        text: 'Sexo',
                        value: 'Sexo',
                        align: 'left',
                    },
                    {
                        text: 'Especialidad',
                        align: 'left',
                    },
                    {
                        text: 'Actividad',
                        align: 'left',
                    },
                    {
                        text: 'Estado',
                        align: 'left',
                    },
                    {
                        text: 'Acciones',
                        align: 'left',
                    }
                ],
                cie10s: [],
                datosCita: {},
                conteoHistoria: [],
                conteoFarmacologico: [],
                conteoServicios: [],
                conteoIncapacidades: [],
                conteoAlertas: [],
                conteoLaboratorio: [],
                errores: {
                    cedula_paciente: '',
                },
                ordenCups: [],
                necesitaOrden: [],
                data: [],
                asistencia: {
                    documento: '',
                    fecha: '',
                    cup_id: '',
                },
                dialogAsistenciaEducativa: false,
                datosPaciente: {
                    fecha: '',
                    Tipo_Doc: '',
                    Num_Doc: '',
                    Primer_Nom: '',
                    SegundoNom: '',
                    Primer_Ape: '',
                    Segundo_Ape: '',
                    Edad_Cumplida: '',
                    Ut: '',
                    NombreIPS: '',
                    cup_id: '',
                    tema: ''
                },
                preloasistenciaEscolar: false,
                cupsTemas: [],
                searchGrupales: '',
                reporteEducativas: {
                    fecha_inicial: '',
                    fecha_final: ''
                },
                dialogHistoriaPrint: false,
                datosAfiliado: {},
                otrosantePaciente: [],
            }
        },
        created() {
            this.UserLogin();
            this.individualesCitas();
            this.fetchCups();
            this.sedesSumimedical();
            this.fetchEspecialidades();
            this.getcups();
            this.fetchCie10s();
        },
        filters: {
            time: time => {
                moment.locale("es");
                return moment(time).format("HH:mm:ss");
            },
            atencion: function (value) {
                let horaAtencion = value.substring(11, 19)
                return horaAtencion
            },

        },
        computed: {
            ...mapGetters(['can']),
            cieConcat() {
                return this.cie10Array = this.cie10s.map(cie => {
                    return {
                        id: cie.id,
                        codigo: cie.Codigo_CIE10,
                        nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                        customDisabled: false
                    };
                });
            },
            listaDemandaInducida: function () {
                if (this.pacienteDemandaInducida.ciclo_vida === '1ra Infancia') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA PRIMERA INFANCIA  (0-5 años)',
                        'CONTROL RECIEN NACIDO',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Infancia') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA INFANCIA (6-11años)',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Adolescencia' && this
                    .pacienteDemandaInducida.Sexo === 'M') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA ADOLESCENCIA (12-17 años)',
                        'SALUD ORAL',
                        'PRECONCEPCIONAL',
                        'PLANIFICACIÓN FAMILIAR',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Adolescencia' && this
                    .pacienteDemandaInducida.Sexo === 'F') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA ADOLESCENCIA (12-17 años)',
                        'PLANIFICACIÓN FAMILIAR',
                        'PRECONCEPCIONAL',
                        'CONTROL PRENATAL',
                        'CONSULTA NUTRICION PRENATAL',
                        'ASESORIA  LACTANCIA',
                        'CURSO PSICOPROFILACTICO',
                        'CONTROL POSPARTO',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Juventud' && this.pacienteDemandaInducida
                    .Sexo === 'F') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA JUVENTUD (18-28 años)',
                        'PLANIFICACIÓN FAMILIAR',
                        'PRECONCEPCIONAL',
                        'CONTROL PRENATAL',
                        'CONSULTA NUTRICION PRENATAL',
                        'ASESORIA  LACTANCIA',
                        'CURSO PSICOPROFILACTICO',
                        'CITOLOGIA',
                        'CONTROL POSPARTO',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Juventud' && this.pacienteDemandaInducida
                    .Sexo === 'M') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA JUVENTUD (18-28 años)',
                        'SALUD ORAL',
                        'PRECONCEPCIONAL',
                        'PLANIFICACIÓN FAMILIAR',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Adultez' && this.pacienteDemandaInducida
                    .Sexo === 'F') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA ADULTEZ (29-59 años)',
                        'PLANIFICACIÓN FAMILIAR',
                        'PRECONCEPCIONAL',
                        'CONTROL PRENATAL',
                        'CONSULTA NUTRICION PRENATAL',
                        'ASESORIA  LACTANCIA',
                        'CURSO PSICOPROFILACTICO',
                        'CITOLOGIA',
                        'MAMOGRAFIA',
                        'CONTROL POSPARTO',
                        'CONTROL RECIEN NACIDO',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Adultez' && this.pacienteDemandaInducida
                    .Sexo === 'M') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA ADULTEZ (29-59 años)',
                        'PLANIFICACIÓN FAMILIAR',
                        'PRECONCEPCIONAL',
                        'CURSO PSICOPROFILACTICO',
                        'TAMIZAJE PROSTATA',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Vejez' && this.pacienteDemandaInducida
                    .Sexo === 'F') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA VEJEZ (60 años o más)',
                        'CITOLOGIA',
                        'MAMOGRAFIA',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Vejez' && this.pacienteDemandaInducida
                    .Sexo === 'M') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA VEJEZ (60 años o más)',
                        'PLANIFICACIÓN FAMILIAR',
                        'PRECONCEPCIONAL',
                        'TAMIZAJE PROSTATA',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                }
            }

        },
        methods: {
            limpiarListaInducida() {
                this.pacienteDemandaInducida.fecha_dx_riesgocardiovascular = '';
                this.pacienteDemandaInducida.descripcion_evento_saludpublica = '';
                this.pacienteDemandaInducida.descripcion_grupo_riesgo = '';
                this.pacienteDemandaInducida.observacion = '';
            },
            demandaInducidaPaciente() {
                axios.get(`/api/historia/demandaInducida/${this.buscarAfiliado}`).then(res => {
                    console.log('paciente', res.data);
                    this.pacienteDemandaInducida = res.data,
                        this.fetchAntecedentes();
                })
            },

            deleteAntecedente(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El antecedente será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/antePersonal/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchAntecedentes();
                });
            },
            modalHistoria(datos) {
                this.datosAfiliado = datos
                this.dialogHistoriaPrint = true;
            },

            UserLogin() {
                axios.get('/api/historia/UserLogin/')
                    .then(res => {
                        this.userLogin = res.data;
                    })
                    .catch(err => console.log(err.response));
            },

            individualesCitas() {
                this.citas = []
                axios.post("/api/historia/citasIndividales").then(res => {
                    res.data.forEach(cita => {
                        if (cita.cita_paciente_id) {
                            this.citas.push(cita);
                        }
                    });
                    let status = this.citas.find(c => c.CP_Estado_id == 8);
                    if (status) {
                        this.citaPacienteId = 1;
                    }
                }).catch(err => {
                    console.error(err)
                })
            },
            limpiarAntecedentesInducida() {
                this.pacienteDemandaInducida.cie10_id = ''
                this.pacienteDemandaInducida.Descripcion = ''
            },
            guardarAntecedentesPersonales() {
                if (!this.pacienteDemandaInducida.cie10_id) {
                    swal("La patologia es requerida!")
                    return;
                } else if (!this.pacienteDemandaInducida.Descripcion) {
                    swal("La descripcion de la patología es requerida!")
                    return;
                }
                this.preloadmedico = true
                let data = {
                    paciente_id: this.pacienteDemandaInducida.paciente_id,
                    patologias: this.pacienteDemandaInducida.cie10_id,
                    descricion_demanda_inducida: this.pacienteDemandaInducida.Descripcion
                }
                axios.post("/api/historia/saveAntecedentesPersonal", data)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchAntecedentes();
                        this.preloadmedico = false;
                        this.limpiarAntecedentesInducida()
                    })
                    .catch(err => console.log(err));
            },
            clearData() {
                for (const key in this.pacienteDemandaInducida) {
                    this.pacienteDemandaInducida[key] = ''
                }
                this.buscarAfiliado = ''
            },

            grupalesCitas() {
                let items = {
                    sede: this.grup.sede,
                    paciente: this.searchDocumentoGrupales
                };
                this.preloadmedico = true
                this.citasGrupos = []
                axios.post("/api/historia/citasGrupales", items).then(res => {
                    this.citasGrupos = res.data
                    this.preloadmedico = false
                }).catch(err => {
                    this.preloadmedico = false
                })
            },

            ocupacionalesCitas() {
                this.preloadmedico = true
                this.citasOcupacionales = []
                axios.post("/api/historia/citasOcupacionales").then(res => {
                    this.citasOcupacionales = res.data
                    this.preloadmedico = false
                }).catch(err => {
                    this.preloadmedico = false
                })
            },

            citasNoProgramadas() {
                this.preloadmedico = true
                axios.post("/api/historia/citasNoProgramadas").then(res => {
                    this.NoProgramadas = res.data
                    this.preloadmedico = false
                }).catch(err => {
                    this.preloadmedico = false
                })

            },

            async atender(items) {
                await this.loadData(items);
                if (items.CP_Estado_id == 8) {
                    this.dialogAtender = true;
                    this.comp = 'ControlHistoria'
                    this.notificacionesFarmacologicos();
                    this.notificacionesServicios();
                    this.notificacionesIncapacidades();
                    this.notificacionesAlertas();
                    this.notificacionesHistorias();
                    this.notificacionesLaboratorio();
                    this.dialogHistorico = true;
                    this.comph = 'ComponenteHistorialAlertas';
                } else {
                    Swal.fire({
                        title: 'Esta seguro de atender el paciente?',
                        text: "Recuerde que la historia debe ser completada en su totalidad!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4caf50',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Atender!'
                    }).then(async result => {
                        console.log(result)
                        if (result.isConfirmed == true) {
                            await axios.post('/api/historia/atenderPaciente/', items).then(res => {
                                    this.dialogAtender = true;
                                    this.comp = 'ControlHistoria'
                                    this.notificacionesFarmacologicos();
                                    this.notificacionesServicios();
                                    this.notificacionesIncapacidades();
                                    this.notificacionesAlertas();
                                    this.notificacionesHistorias();
                                    this.notificacionesLaboratorio();
                                    this.dialogHistorico = true;
                                    this.comph = 'ComponenteHistorialAlertas';
                                })
                                .catch(err => console.log(err.response));
                        }
                    })

                }
            },

            searchPaciente() {
                axios.post('/api/paciente/searchPaciente', this.citanoProgramadas)
                    .then((res) => {
                        if (res.data.message) {
                            this.showMessage(res.data.message);
                        } else if (res.data) {
                            this.paciente = res.data;
                            Swal.fire({
                                title: 'Esta seguro de atender el paciente ' + this.paciente
                                    .Nombre + '?',
                                text: "Recuerde que la historia debe ser completada en su totalidad!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#4caf50',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Atender!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.validarCups()
                                }
                            })

                        }
                    }).catch(err => {
                        this.setError(err.response.data)
                    })

            },


            atenderNoprogramada() {
                if (!this.citanoProgramadas.cedula_paciente) {
                    this.$alerError('Campo de Número de Documento no puede estar vacio')
                    return
                } else if (!this.citanoProgramadas.Cup_id) {
                    this.$alerError('Campo Cup no puede estar vacio')
                    return
                } else if (!this.citanoProgramadas.especialidad) {
                    this.$alerError('Campo Especialidad no puede estar vacio')
                    return
                } else if (!this.citanoProgramadas.lugar_atencion) {
                    this.$alerError('Campo sede de atención no puede estar vacio')
                    return
                }
                this.searchPaciente()
            },

            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },

            loadData(items) {
                let ciclo_vida = ''
                if (parseInt(items.Edad_Cumplida) <= 5) {
                    ciclo_vida = '1ra Infancia';
                } else if (parseInt(items.Edad_Cumplida) >= 6 && parseInt(items.Edad_Cumplida) <= 11) {
                    ciclo_vida = 'Infancia';
                } else if (parseInt(items.Edad_Cumplida) >= 12 && parseInt(items.Edad_Cumplida) <= 17) {
                    ciclo_vida = 'Adolescencia';
                } else if (parseInt(items.Edad_Cumplida) >= 18 && parseInt(items.Edad_Cumplida) <= 28) {
                    ciclo_vida = 'Juventud';
                } else if (parseInt(items.Edad_Cumplida) >= 29 && parseInt(items.Edad_Cumplida) <= 59) {
                    ciclo_vida = 'Adultez';
                } else if (parseInt(items.Edad_Cumplida) >= 60) {
                    ciclo_vida = 'Vejez';
                }
                this.datosCita = {
                    nombre_paciente: items.Nombre_Paciente,
                    cita_paciente_id: items.cita_paciente_id,
                    paciente_id: items.paciente_id,
                    Edad_Cumplida: items.Edad_Cumplida,
                    Especialidad: items.Especialidad,
                    Tipo_agenda: items.tipoAgenda,
                    salud_ocupacional: items.salud_ocupacional,
                    sexo: items.Sexo,
                    ciclo_vital: ciclo_vida,
                    cedula: items.Num_Doc,
                    marcacion: items.marcacion,
                    victima_conficto_armado: items.victima_conficto_armado
                };
                this.porcentaje();
            },

            fetchAntecedentes() {
                this.preloasistenciaEscolar = true
                const data = {
                    paciente_id: this.pacienteDemandaInducida.paciente_id
                }
                axios.post("/api/historia/fetchAntecedentePersonal", data)
                    .then(res => {
                        this.allAntecedentesPaciente = res.data;
                        this.preloasistenciaEscolar = false
                    });
            },

            loadDataNoProgramada(citaPaciente, especialidadNombre, tipoAgendaNombre) {
                let ciclo_vida = ''
                if (parseInt(this.paciente.Edad_Cumplida) <= 5) {
                    ciclo_vida = '1ra Infancia';
                } else if (parseInt(this.paciente.Edad_Cumplida) >= 6 && parseInt(this.paciente
                        .Edad_Cumplida) <= 11) {
                    ciclo_vida = 'Infancia';
                } else if (parseInt(this.paciente.Edad_Cumplida) >= 12 && parseInt(this.paciente
                        .Edad_Cumplida) <= 17) {
                    ciclo_vida = 'Adolescencia';
                } else if (parseInt(this.paciente.Edad_Cumplida) >= 18 && parseInt(this.paciente
                        .Edad_Cumplida) <= 28) {
                    ciclo_vida = 'Juventud';
                } else if (parseInt(this.paciente.Edad_Cumplida) >= 29 && parseInt(this.paciente
                        .Edad_Cumplida) <= 59) {
                    ciclo_vida = 'Adultez';
                } else if (parseInt(this.paciente.Edad_Cumplida) >= 60) {
                    ciclo_vida = 'Vejez';
                }
                this.datosCita = {
                    nombre_paciente: this.paciente.Nombre,
                    cita_paciente_id: citaPaciente,
                    paciente_id: this.paciente.paciente_id,
                    Edad_Cumplida: this.paciente.Edad_Cumplida,
                    Especialidad: especialidadNombre,
                    Tipo_agenda: tipoAgendaNombre,
                    sexo: this.paciente.Sexo,
                    ciclo_vital: ciclo_vida,
                    cedula: this.paciente.Num_Doc,
                    victima_conficto_armado: this.paciente.victima_conficto_armado
                }
                this.porcentaje();
                this.dialogAtender = true;
                this.comp = 'ControlHistoria'
                this.notificacionesHistorias();
                this.notificacionesFarmacologicos();
                this.notificacionesServicios();
                this.notificacionesIncapacidades();
                this.notificacionesAlertas();
                this.notificacionesLaboratorio();
                this.clearFilter();


            },

            historicoAtencion() {
                this.dialogHistorico = true;
                this.comph = 'ComponentHistoricoAtencion';
            },

            historicoClinico() {
                this.dialogHistorico = true;
                this.comph = 'ComponentHistorialClinico';
            },

            historicoExamenes() {
                this.dialogHistorico = true;
                this.comph = 'ComponentHistorialExamenes';
            },

            historicoFarmacologia() {
                this.dialogHistorico = true;
                this.comph = 'ComponenteHistorialMedicamentos';
            },

            historicoAlertas() {
                this.dialogHistorico = true;
                this.comph = 'ComponenteHistorialAlertas';
            },

            historicoIncapacidades() {
                this.dialogHistorico = true;
                this.comph = 'ComponenteHistorialIncapacidades';
            },

            resultadoLaboratorio() {
                this.dialogHistorico = true;
                this.comph = 'ComponenteHistoriaLaboratorio';
            },

            closeHistoria() {
                this.comp = null;
                this.dialogAtender = false;
                if (this.bottomNav == 0) {
                    this.individualesCitas();
                }
                if (this.bottomNav == 1) {
                    this.citasNoProgramadas();
                }
                if (this.bottomNav == 2) {
                    this.grupalesCitas(this.grup.sede, this.searchDocumentoGrupales);
                }
                if (this.bottomNav == 3) {
                    this.ocupacionalesCitas();
                }

            },

            notificacionesHistorias() {
                axios.post('api/paciente/conteoHistoria', this.datosCita).then((res) => {
                    this.conteoHistoria = res.data;
                });
            },

            notificacionesFarmacologicos() {
                axios.post('api/paciente/conteoMedicamentos', this.datosCita).then((res) => {
                    this.conteoFarmacologico = res.data;
                });
            },

            notificacionesServicios() {
                axios.post('api/paciente/conteoServicios', this.datosCita).then((res) => {
                    this.conteoServicios = res.data;
                });
            },

            notificacionesIncapacidades() {
                axios.post('api/paciente/conteoIncapacidades', this.datosCita).then((res) => {
                    this.conteoIncapacidades = res.data;
                });
            },

            notificacionesAlertas() {
                axios.post('api/paciente/conteoAlertas', this.datosCita).then((res) => {
                    this.conteoAlertas = res.data;
                });
            },

            notificacionesLaboratorio() {
                axios.post('api/paciente/conteoLaboratorio', this.datosCita).then((res) => {
                    this.conteoLaboratorio = res.data;
                });
            },

            porcentaje(elementos, comp) {
                let datos = {
                    components: elementos,
                    component: comp
                }
                axios.post(`api/historia/savePorcentaje/${this.datosCita.cita_paciente_id}`, datos).then((
                    res) => {
                    let data = res.data
                    if (data.porcentaje) {
                        if (Number.parseFloat(data.porcentaje) == 100) {
                            this.valueDeterminate = 100;
                        } else {
                            let porcentaje = Number.parseFloat(data.porcentaje).toFixed(2);
                            this.valueDeterminate = porcentaje
                        }
                    }
                });
            },

            fetchCups() {
                axios.get('/api/cups/all')
                    .then(res => this.cups = res.data.map((cup) => {
                        return {
                            id: cup.id,
                            Nombre: `${cup.Codigo} - ${cup.Nombre}`
                        }
                    }));
            },

            sedesSumimedical() {
                axios.get('/api/helpdesk/sedes').then(res => {
                    this.sedes = res.data;
                })
            },

            sedesAgendas() {
                axios.get('/api/agenda/sedesAgendas').then(res => {
                    this.sedesAgenda = res.data;
                    this.grupalesCitas();
                })
            },

            fetchEspecialidades() {
                axios.get('/api/especialidad/all/').
                then(res => {
                    this.especialidad = res.data;
                });
            },

            fetchActividades() {
                axios.get('/api/especialidad/tipoactividad/' + this.citanoProgramadas.especialidad)
                    .then(res => (this.actividades = res.data));
            },

            showMessage(message) {
                swal({
                    title: `${message}`,
                    icon: "warning",
                });
            },

            setError(errors) {
                for (const key in this.errores) {
                    if (key in errors) {
                        this.errores[key] = errors[key].join(',')
                    }
                }
            },

            clearError() {
                for (const key in this.errores) {
                    this.errores[key] = ''
                }
            },

            clearFilter() {
                for (const key in this.citanoProgramadas) {
                    this.citanoProgramadas[key] = '';
                }

            },

            pacienteInasiste(id) {
                this.clearError();
                Swal.fire({
                    title: "¿Está Seguro(a)?",
                    text: "Se cancela la cita por inasistencia!",
                    icon: "warning",
                    input: "textarea",
                    inputPlaceholder: "Motivo",
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: "Confirmar",
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value) {
                                id.Observacionesinasistido = value;
                                this.preloadmedico = true
                                axios.post('/api/historia/pacienteInasiste', id)
                                    .then(res => (
                                        this.preloadmedico = false,
                                        this.$alerSuccess(res.data.message),
                                        this.individualesCitas()
                                    )).catch(err => {
                                        this.preloadmedico = false,
                                            this.$alerError(err.response.data
                                                .Observacionesinasistido)
                                    });
                            } else {
                                resolve('El Campo Motivo es Requrido')
                            }
                            this.citasNoProgramadas();
                        })
                    }
                })
            },

            printHistoriaClinica() {
                this.dialogHistoriaPrint = true
            },

            imprimirhc(imprimir) {
                let pdf = [];
                pdf = imprimir;
                pdf.type = "historiaintegral";
                axios
                    .post("/api/historia/imprimirhc", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    });

            },

            finalizarConsulta() {
                Swal.fire({
                    title: 'Esta seguro de finalizar la consulta?',
                    text: "Recuerde que una vez finalizada no se reabre la historia!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4caf50',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Finalizar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(`api/historia/finalizarConsulta/${this.datosCita.cita_paciente_id}`)
                            .then(res => {
                                if(res.data.message ==='La historia no cuenta con un Diagnostico de consulta!'){
                                    this.$alerError('<span style="color:#000">' + res.data
                                    .message +
                                    '<span>');
                                }
                                else{
                                    this.$alertHistoria('<span style="color:#fff">' + res.data
                                    .message +
                                    '<span>');
                                this.closeHistoria();
                                this.ocupacionalesCitas();
                                }

                            })
                            .catch(err => console.log(err.response));
                    }
                })
            },

            ordenamiento() {
                this.comp = 'OrdenamientoComponent';
            },

            printhc(id) {
                let pdf = {
                    type: "historiasst",
                    id: id
                };
                axios
                    .post("/api/pdf/print-pdf", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    });
            },

            Newatender(items) {
                this.datos = items
                this.dialogPreguntaAtender = true
                this.cita.actividad = items.tipoAgenda
                axios.post("/api/historia/getActividad", items).then(res => {
                    this.newactividad = res.data
                });

            },

            validadorCita(items, id) {
                items.tipoAgenda = id
                axios.post("/api/historia/cambioActividad", items).then(res => {
                    this.atenderNew(items)
                    this.dialogPreguntaAtender = false
                    this.cita.actividad = ''
                });
            },

            async atenderNew(items) {
                await this.loadData(items);
                await axios.post('/api/historia/atenderPaciente', items).then(res => {
                        this.dialogAtender = true;
                        this.comp = 'ControlHistoria'
                        this.notificacionesFarmacologicos();
                        this.notificacionesServicios();
                        this.notificacionesIncapacidades();
                        this.notificacionesAlertas();
                        this.notificacionesHistorias();
                        this.notificacionesLaboratorio();
                        this.dialogHistorico = true;
                        this.comph = 'ComponenteHistorialAlertas';
                    })
                    .catch(err => console.log(err.response));
            },

            consultarPaciente() {
                if (!this.asistencia.documento) {
                    this.$alerError("Debes ingresar el documento del paciente")
                    return;
                }
                this.preloasistenciaEscolar = true;
                axios.get(`/api/paciente/trascripciones/${this.asistencia.documento}`)
                    .then(res => {
                        if (res.data.paciente) {
                            this.preloasistenciaEscolar = false;
                            this.datosPaciente = res.data.paciente;
                            this.dialogAsistenciaEducativa = true;
                        }
                        if (res.data.message) this.showMessage(res.data.message);
                        this.preloasistenciaEscolar = false;
                    });
            },

            closeAsistenciaEducativa() {
                this.dialogAsistenciaEducativa = false
                this.asistencia.documento = ''
            },

            solicitudCups() {
                axios.post('/api/especialidad/especialidadNoProgramada', this.citanoProgramadas)
                    .then((res) => {
                        this.necesitaOrden = res.data
                        if (this.necesitaOrden == false) {
                            this.$alerError(
                                "Para atender esta especialidad se requiere una orden activa en el sistema. El paciente no cuenta con una orden activa!"
                            )
                            this.clearFilter()
                        } else {
                            this.dialogPreguntaCups = true
                        }
                    })
            },

            validarCups() {
                axios.get(`/api/especialidad/especialidadCita/${this.citanoProgramadas.especialidad}`)
                    .then((res) => {
                        this.necesitaOrden = res.data
                        if (this.necesitaOrden == true) {
                            this.solicitudCups()
                        } else if (this.necesitaOrden == false) {
                            this.validarEspecialidad()
                        }
                    })
            },

            validarEspecialidad() {
                axios.post('/api/historia/CitasnoProgramada/' + this.paciente.paciente_id, this
                        .citanoProgramadas)
                    .then((res) => {
                        let citaPaciente = res.data[0].id
                        let especialidadNombre = res.data[1].Nombre
                        let tipoAgendaNombre = res.data[2].id
                        if (this.ordenNoprogramada.cuporden_id != null) {
                            this.citanoProgramadas.citaPaciente = citaPaciente
                            this.citanoProgramadas.cuporden_id = this.ordenNoprogramada.cuporden_id
                            axios.post('/api/especialidad/guardarOrden', this.citanoProgramadas)
                                .then((res) => {
                                    this.ordenNoprogramada.cuporden_id = null
                                }).catch(err => console.log(err.response));
                        }
                        this.loadDataNoProgramada(citaPaciente, especialidadNombre, tipoAgendaNombre);
                    }).catch(err => console.log(err.response));
            },

            limpiarDatosDemandaInducida() {
                this.pacienteDemandaInducida.tipoDemandaInducida = ''
                this.pacienteDemandaInducida.programaremitido = ''
                this.pacienteDemandaInducida.fecha_dx_riesgocardiovascular = ''
                this.pacienteDemandaInducida.descripcion_evento_saludpublica = ''
                this.pacienteDemandaInducida.descripcion_grupo_riesgo = ''
                this.pacienteDemandaInducida.observacion = ''
            },

            // guardarOrden() {
            //     this.citanoProgramadas.citaPaciente = this.datosCita
            //     this.citanoProgramadas.cuporden_id = this.ordenNoprogramada.cuporden_id
            //     axios.post('/api/especialidad/guardarOrden', this.citanoProgramadas)
            //         .then((res) => {
            //             this.validarEspecialidad()
            //             this.ordenNoprogramada.cuporden_id = ''
            //         }).catch(err => console.log(err.response));
            // },

            getcups() {
                axios.get('/api/cups/allEducacion').then(res => {
                    this.cupfilter = res.data;
                });
            },

            registrar() {
                if (!this.datosPaciente.fecha) {
                    this.$alerError("El campo fecha es requerido!");
                    return
                } else if (!this.datosPaciente.cup_id) {
                    this.$alerError("El campo tipo de educación es requerido!");
                    return
                }
                axios.post('/api/asistencia/saveAsistencia', this.datosPaciente).then(res => {
                    this.$alerSuccess(res.data.message);
                    this.limpiarcampos();
                    this.dialogAsistenciaEducativa = false;
                });
            },

            limpiarcampos() {
                this.asistencia.documento = '',
                    this.datosPaciente.fecha = '',
                    this.datosPaciente.Tipo_Doc = '',
                    this.datosPaciente.Num_Doc = '',
                    this.datosPaciente.Primer_Nom = '',
                    this.datosPaciente.SegundoNom = '',
                    this.datosPaciente.Primer_Ape = '',
                    this.datosPaciente.Segundo_Ape = '',
                    this.datosPaciente.Edad_Cumplida = '',
                    this.datosPaciente.Ut = '',
                    this.datosPaciente.NombreIPS = '',
                    this.datosPaciente.cup_id = ''
            },

            resultadoProdiagnostico() {
                window.open("https://prodiagnostico.hiruko.com.co/login", "ventana1",
                    "width=800,height=800,scrollbars=NO");
            },

            enlaseDinamica() {
                window.open("http://192.168.0.30:8887", "ventana1", "width=800,height=800,scrollbars=NO");
            },

            // imprimirhc

            reporteAsistenciaEducativa() {
                this.preload = true;
                axios({
                    method: 'post',
                    url: '/api/asistencia/reporteAsistencia',
                    responseType: 'blob',
                    params: this.reporteEducativas
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `ReporteAsistenciaEducativa.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.preload = false
                    this.limpiarCamposReporte()
                }).catch(err => {
                    this.preload = false,
                        console.log(err)
                })
            },

            limpiarCamposReporte() {
                this.reporteEducativas.fecha_inicial = ''
                this.reporteEducativas.fecha_final = ''
            },

            SendEmail(imprimir) {
                let pdf = [];
                pdf = imprimir;
                pdf.type = "historiaintegral";
                axios.post("/api/historia/enviarHC", pdf, {
                    responseType: "arraybuffer"
                }).then((res) => {
                    let blob = new Blob([res.data], {
                        type: "application/pdf"
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");
                });
            },

            saveDemandaInducida() {
                this.preload = true;

                let data = {
                    paciente_id: this.pacienteDemandaInducida.id,
                    tipoDemandaInducida: this.pacienteDemandaInducida.tipoDemandaInducida,
                    programaremitido: this.pacienteDemandaInducida.programaremitido,
                    fecha_dx_riesgocardiovascular: this.pacienteDemandaInducida.fecha_dx_riesgocardiovascular,
                    descripcion_evento_saludpublica: this.pacienteDemandaInducida.descripcion_evento_saludpublica,
                    descripcion_grupo_riesgo: this.pacienteDemandaInducida.descripcion_grupo_riesgo,
                    demanda_inducida_efectiva: this.pacienteDemandaInducida.demanda_inducida_efectiva,
                    observaciones: this.pacienteDemandaInducida.observacion
                }
                if (!data.observaciones) {
                    return this.$alerError('El campo "observaciones" es requerido.');
                }
                if (data.tipoDemandaInducida == undefined) {
                    return this.$alerError("Debe seleccionar un tipo de demanda inducida!");
                }
                if (data.tipoDemandaInducida == undefined) {
                    this.$alerError(
                        "Debe seleccionar un tipo de demanda inducida!"
                    )
                    return
                }
                if (data.programaremitido == undefined) {
                    this.$alerError(
                        "Debe seleccionar un programa de demanda inducida!"
                    )
                    return
                }
                if (data.riesgocardiovascular == true && data.fecha_dx_riesgocardiovascular == undefined) {
                    this.$alerError(
                        "Debe registrar la fecha del dx!"
                    )
                    return
                }
                if (data.eventoSaludPublica == true && data.descripcion_evento_saludpublica == undefined) {
                    this.$alerError(
                        "Debe registrar la descripcion del evento de salud publica!"
                    )
                    return
                }
                if (data.otrogrupoderiesgo == true && data.descripcion_grupo_riesgo.length >= 0) {
                    this.$alerError(
                        "Debe registrar la descripcion del otro grupo de riesgo!"
                    )
                    return
                }

                axios.post("/api/historia/saveDemandaInducida", data).then(res => {
                    this.$alerSuccess(res.data.mensaje);
                    this.limpiarDatosDemandaInducida();
                    this.clearData();
                }).catch(err => {
                    this.preload = false,
                        console.log(err)
                });
            }
        }
    }

</script>
