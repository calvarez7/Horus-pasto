<template>
    <v-layout>
        <template>
            <div class="text-xs-center">
                <v-dialog v-model="dialog" width="500">
                    <v-card>
                        <v-card-title class="headline">
                            Agendar Cita
                        </v-card-title>
                        <v-card-text>
                            Asignar cita de tipo <b> {{ actividad_selected }} </b> al usuario
                            <b>{{ paciente.Primer_Nom }} {{ paciente.SegundoNom }} {{ paciente.Primer_Ape}}
                                {{ paciente.Segundo_Ape }}</b> identificado con <b>{{ paciente.Tipo_Doc }}</b> N°
                            <b>{{ paciente.Num_Doc }}</b> el día <b>{{ fecha_selected }}</b> a las
                            <b>{{ data.hora_inicio | time}}</b> en la sede <b>{{ sede_selected }}</b>,
                            <b>{{ data.consultorio }}</b> con el médico <b>{{ medico_selected }}</b>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn flat @click="dialog = false">
                                cancelar
                            </v-btn>
                            <v-btn color="primary" flat @click="agendarCita()">
                                Aceptar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </div>
        </template>
        <v-flex shrink xs4 mr-1>
            <v-card max-height="100%" class="mb-3">
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Buscar Paciente</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-md fluid class="pa-0">
                        <v-layout wrap align-center justify-center>
                            <v-flex xs12>
                                <v-form @submit.prevent="search_paciente()">
                                    <v-layout row wrap>
                                        <v-flex xs10>
                                            <v-text-field v-model="cedula_paciente" label="Número de Documento">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs2>
                                            <v-btn @click="search_paciente()" @keyup.enter="search_paciente()"
                                                v-if="!paciente.id" fab outline small color="success">
                                                <v-icon>search</v-icon>
                                            </v-btn>
                                            <v-btn @click="clearFields()" v-if="paciente.id" fab outline small
                                                color="error">
                                                <v-icon>clear</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-form>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
            </v-card>
            <v-flex>
                <v-expansion-panel>
                    <v-expansion-panel-content>
                        <template v-slot:header>
                            <div>
                                <v-badge right>
                                    <template v-slot:badge>
                                        <span v-if="cita_pendiente.Tipo_agenda">{{ allcita_pendiente.length }}</span>
                                    </template>
                                    <span>Cita Pendiente</span>
                                </v-badge>
                            </div>
                        </template>
                        <v-divider></v-divider>
                        <v-dialog v-model="motivoCancelar" persistent max-width="600px">
                            <v-card>
                                <v-card-title>
                                    <span class="headline">Motivo</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs12>
                                                <v-textarea v-model="cancelar.motivo" name="input-7-1"
                                                    label="Motivo por el cual se cancela la cita" value=""></v-textarea>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" flat @click="motivoCancelar = false">Cerrar</v-btn>
                                    <v-btn v-if="can('cita.cancel')" color="blue darken-1" flat @click="cancelarCita()">
                                        Enviar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-card>
                            <v-card-text>
                                <v-flex xs12 v-if="cita_pendiente.Tipo_agenda">
                                    <v-list two-line subheader>
                                        <v-menu offset-x>
                                            <template v-slot:activator="{ on }">
                                                <v-btn color="success" v-on="on" flat icon absolute right>
                                                    <v-icon>more_vert</v-icon>
                                                </v-btn>
                                            </template>
                                            <v-list>
                                                <!--<v-list-tile @click="confirmarCita()">
                                                    <v-list-tile-title>Confirmar<v-icon right color="success">check</v-icon></v-list-tile-title>
                                                </v-list-tile> -->
                                                <v-list-tile v-if="can('cita.cancel')"
                                                    @click="openMotivo(cita_pendiente)">
                                                    <v-list-tile-title>Cancelar
                                                        <v-icon right color="error">clear</v-icon>
                                                    </v-list-tile-title>
                                                </v-list-tile>
                                                <v-list-tile @click="printPDF(cita_pendiente.id)">
                                                    <v-list-tile-title>Generar
                                                        <v-icon right color="success">assignment_turned_in</v-icon>
                                                    </v-list-tile-title>
                                                </v-list-tile>
                                            </v-list>
                                        </v-menu>
                                        <span class="headline layout justify-center">
                                            {{ cita_pendiente.Tipo_agenda }}</span>
                                        <p class="text-md-center">{{ cita_pendiente.Especialidad }}</p>
                                        <v-list-tile avatar>
                                            <v-list-tile-content>
                                                <v-list-tile-title>Fecha</v-list-tile-title>
                                                <v-list-tile-sub-title>{{ cita_pendiente.Fecha | fecha_cita_pendiente}}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile avatar>
                                            <v-list-tile-content>
                                                <v-list-tile-title>Hora de la Cita</v-list-tile-title>
                                                <v-list-tile-sub-title>
                                                    {{ cita_pendiente.Hora_Inicio | hora_cita_pendiente}}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile avatar>
                                            <v-list-tile-content>
                                                <v-list-tile-title>Sede</v-list-tile-title>
                                                <v-list-tile-sub-title>{{ cita_pendiente.Sede }}</v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile avatar>
                                            <v-list-tile-content>
                                                <v-list-tile-title>Médico</v-list-tile-title>
                                                <v-list-tile-sub-title>{{ cita_pendiente.Nombre_medico }}
                                                    {{ cita_pendiente.Apellido_medico }}</v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                    <v-layout>
                                        <v-spacer></v-spacer>
                                        <v-btn flat small color="primary" @click="todaspendientesmodal = true">ver más!
                                        </v-btn>
                                    </v-layout>
                                    <v-dialog v-model="todaspendientesmodal" width="80%">
                                        <v-card>
                                            <v-card-title class="headlines lighten-2" primary-title>Citas Pendientes
                                            </v-card-title>
                                            <v-card-text>
                                                <v-data-table :headers="headerCitaPendientes" :items="allcita_pendiente"
                                                    class="elevation-1" hide-actions>
                                                    <template v-slot:items="props">
                                                        <td>{{ props.item.Especialidad }}</td>
                                                        <td class="text-xs-center">{{ props.item.Tipo_agenda }}</td>
                                                        <td class="text-xs-center">
                                                            {{ props.item.Fecha | fecha_cita_pendiente }}</td>
                                                        <td class="text-xs-center">
                                                            {{ props.item.Hora_Inicio | hora_cita_pendiente}}</td>
                                                        <td class="text-xs-center">{{ props.item.Sede }}</td>
                                                        <td class="text-xs-center">{{ props.item.Nombre_medico }}
                                                            {{ props.item.Apellido_medico}}</td>
                                                        <td class="text-xs-center">{{ props.item.User_asgina}}
                                                            {{ props.item.ApUser_asgina}}</td>
                                                        <td class="text-xs-center">
                                                            <!--<v-btn fab color="success" outline small @click="confirmarCita(props.item)">
                                                                    <v-icon color="success">check</v-icon>
                                                                </v-btn>-->
                                                            <v-btn fab color="error" outline small
                                                                v-if="can('cita.cancel')"
                                                                @click="openMotivo(props.item)">
                                                                <v-icon color="error">clear</v-icon>
                                                            </v-btn>
                                                            <v-btn fab color="success" outline small
                                                                @click="printPDF(props.item.id)">
                                                                <v-icon color="success">assignment_turned_in</v-icon>
                                                            </v-btn>
                                                        </td>
                                                    </template>
                                                </v-data-table>
                                            </v-card-text>
                                            <v-divider></v-divider>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="primary" flat @click="todaspendientesmodal = false">Cerrar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-flex>
                                <h3 v-else class="layout justify-center">Ninguna</h3>
                            </v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                    <v-expansion-panel-content>
                        <template v-slot:header>
                            <div>
                                <v-badge right>
                                    <template v-slot:badge>
                                        <span v-if="cita_anteriores.Tipo_agenda">{{ allcita_anteriores.length }}</span>
                                    </template>
                                    <span>Citas Atendidas y Canceladas</span>
                                </v-badge>
                            </div>
                        </template>
                        <v-divider></v-divider>
                        <v-card>
                            <v-card-text>
                                <v-flex xs12 v-if="cita_anteriores.Tipo_agenda">
                                    <v-list two-line subheader>
                                        <v-menu offset-x>
                                            <template v-slot:activator="{ on }">
                                                <v-btn color="success" v-on="on" flat icon absolute right>
                                                    <v-icon>more_vert</v-icon>
                                                </v-btn>
                                            </template>
                                        </v-menu>
                                        <span class="headline layout justify-center">
                                            {{ cita_anteriores.Tipo_agenda }}</span>
                                        <p class="text-md-center">{{ cita_anteriores.Especialidad }}</p>
                                        <v-list-tile avatar>
                                            <v-list-tile-content>
                                                <v-list-tile-title>Fecha</v-list-tile-title>
                                                <v-list-tile-sub-title>
                                                    {{ cita_anteriores.Fecha | fecha_cita_anteriores}}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile avatar>
                                            <v-list-tile-content>
                                                <v-list-tile-title>Hora de la Cita</v-list-tile-title>
                                                <v-list-tile-sub-title>
                                                    {{ cita_anteriores.Hora_Inicio | hora_cita_anteriores }}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile avatar>
                                            <v-list-tile-content>
                                                <v-list-tile-title>Sede</v-list-tile-title>
                                                <v-list-tile-sub-title>{{ cita_anteriores.Sede }}
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile avatar>
                                            <v-list-tile-content>
                                                <v-list-tile-title>Médico</v-list-tile-title>
                                                <v-list-tile-sub-title>{{ cita_anteriores.Nombre_medico }}
                                                    {{ cita_anteriores.Apellido_medico }}</v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                    <v-layout>
                                        <v-spacer></v-spacer>
                                        <v-btn flat small color="primary" @click="todasanterioresmodal = true">ver más!
                                        </v-btn>
                                    </v-layout>
                                    <v-dialog v-model="todasanterioresmodal" width="80%">
                                        <v-card>
                                            <v-card-title class="headlines lighten-2" primary-title>Cita Anteriores
                                            </v-card-title>
                                            <v-card-text>
                                                <v-data-table :headers="headercitaanteriores"
                                                    :items="allcita_anteriores" class="elevation-1" hide-actions>
                                                    <template v-slot:items="props">
                                                        <td>{{ props.item.Especialidad }}</td>
                                                        <td class="text-xs-center">{{ props.item.Tipo_agenda }}</td>
                                                        <td class="text-xs-center">
                                                            {{ props.item.Fecha | fecha_cita_anteriores }}</td>
                                                        <td class="text-xs-center">
                                                            {{ props.item.Hora_Inicio | hora_cita_anteriores }}</td>
                                                        <td class="text-xs-center">{{ props.item.Sede }}</td>
                                                        <td class="text-xs-center">{{ props.item.Nombre_medico }}
                                                            {{ props.item.Apellido_medico}}</td>
                                                        <td class="text-xs-center" v-if="props.item.User_asgina">
                                                            {{ props.item.User_asgina}}
                                                            {{ props.item.ApUser_asgina}}</td>
                                                        <td class="text-xs-center" v-else>Paciente</td>
                                                        <td class="text-xs-center">
                                                            <v-chip v-if="props.item.Nom_estado === 'Atendida'"
                                                                class="ma-2" color="green" text-color="white">
                                                                {{ props.item.Nom_estado }}
                                                            </v-chip>
                                                            <v-chip v-else-if="props.item.Nom_estado === 'Cancelada'"
                                                                class="ma-2" color="red" text-color="white"
                                                                @click="showMotivo(props.item)">
                                                                {{ props.item.Nom_estado}}
                                                            </v-chip>
                                                            <v-chip v-else class="ma-2" color="orange"
                                                                text-color="white">
                                                                {{ props.item.Nom_estado}}
                                                            </v-chip>
                                                        </td>
                                                        <td class="text-xs-center">
                                                            <!--<v-btn fab color="success" outline small @click="confirmarCita(props.item)">
                                                                    <v-icon color="success">check</v-icon>
                                                                </v-btn>-->
                                                        </td>
                                                    </template>
                                                </v-data-table>
                                            </v-card-text>
                                            <v-divider></v-divider>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="primary" flat @click="todasanterioresmodal = false">Cerrar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-flex>
                                <h3 v-else class="layout justify-center">Ninguna</h3>
                            </v-card-text>
                        </v-card>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-flex>

            <v-dialog v-model="dialogshowMotivo" v-if="dialogshowMotivo" max-width="400px">
                <v-card>
                    <v-card-text>
                        <div><span><strong>Motivo:</strong> {{ descripcionMotivo.Motivo }}</span></div>
                        <span v-if="descripcionMotivo.fechaCancelo"><strong>Fecha:</strong>
                            {{ descripcionMotivo.fechaCancelo.split('.')[0] }}</span>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <v-flex mt-3>
                <v-card>
                    <v-card-title>
                        <span class="title layout justify-center font-weight-light">Fecha disponible</span>
                    </v-card-title>
                    <v-divider></v-divider>

                    <v-card-text>
                        <v-container>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-date-picker v-model="fecha" :allowed-dates="diasDisponibles" :events="diasEvents"
                                        :show-current="false" event-color="green lighten-1" locale="es" color="success">
                                    </v-date-picker>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-flex>
        <v-flex shrink xs8 ml-1>
            <v-card max-height="100%" class="mb-3">
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Datos Paciente</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-md fluid class="pa-0">
                        <v-layout wrap>
                            <v-flex xs2>
                                <v-text-field v-model="paciente.entidad" readonly label="Entidad"></v-text-field>
                            </v-flex>
                            <v-flex xs3>
                                <v-text-field v-model="paciente.Tipo_Afiliado" label="Tipo" readonly></v-text-field>
                            </v-flex>
                            <v-flex xs3>
                                <v-text-field v-model="paciente.Primer_Nom" readonly label="Nombre"></v-text-field>
                            </v-flex>
                            <v-flex xs3>
                                <v-text-field v-model="paciente.Primer_Ape" readonly label="Apellido"></v-text-field>
                            </v-flex>
                            <v-flex xs1>
                                <v-text-field v-model="paciente.Sexo" readonly label="Sexo"></v-text-field>
                            </v-flex>
                            <v-flex xs1>
                                <v-select :items="Tipo_Doc" label="Tipo Documento" v-model="paciente.Tipo_Doc">
                                </v-select>
                            </v-flex>
                            <v-flex xs3>
                                <v-text-field v-model="paciente.Num_Doc" readonly label="Número Documento">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs3 v-if="paciente.Fecha_Naci">
                                <v-text-field v-model="paciente.Fecha_Naci.split(' ')[0]" readonly
                                    label="Fecha Nacimiento"></v-text-field>
                            </v-flex>
                            <v-flex xs1>
                                <v-text-field v-model="paciente.Edad_Cumplida" readonly label="Edad"></v-text-field>
                            </v-flex>
                            <v-flex xs4>
                                <v-text-field v-model="paciente.Departamento" readonly label="Departamento">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4>
                                <v-text-field v-model="paciente.Mpio_Afiliado" readonly label="Municipio">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4>
                                <v-text-field v-model="paciente.Telefono" label="Telefono"></v-text-field>
                            </v-flex>
                            <v-flex xs4>
                                <v-text-field v-model="paciente.Celular1" label="Celular" type="number" maxlength="10"
                                oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                min="1"></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-text-field v-model="paciente.Celular2" label="Celular 2 (opcional)"></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-text-field v-model="paciente.Correo1" label="Correo"></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-text-field v-model="paciente.Correo2" label="Correo 2 (opcional)"></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-text-field v-model="paciente.Direccion_Residencia" label="Direccion Residencia">
                                </v-text-field>
                            </v-flex>

                        </v-layout>
                        <v-layout row wrap v-show="paciente.id">
                            <!-- <v-layout row wrap>
                                <v-flex xs6>
                                    <span class="error--text"><span style="color:black">ENTIDAD :</span> {{ paciente.Ut }}</span><br />
                                    <span class="error--text">Punto de atención : {{ paciente.NombreIPS }}</span><br />
                                    <span class="error--text">Médico de familia : {{paciente.Medicofamilia}}</span>
                                </v-flex>
                            </v-layout> -->
                            <v-spacer></v-spacer>
                            <v-btn color="warning" @click="actualizarDatosPersonales()" round>Actualizar</v-btn>
                        </v-layout>
                    </v-container>
                </v-card-text>
            </v-card>
            <v-card max-height="100%" v-show="true">
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Agenda Disponible Médico</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3 px-1>
                            <!-- especialidad -->
                            <v-autocomplete v-model="especialidad_selected"
                                :items="especialidades.filter(obj=>obj.id >= 63 && obj.id <=67 || obj.id == 72 || obj.id == 73)" item-text="Nombre"
                                item-value="id" label="Especialidades" @input="fetchSedes(), campoCup()">
                            </v-autocomplete>
                        </v-flex>
                        <!-- sede -->
                        <v-flex xs4 px-1>
                            <v-select v-model="sede_selected" :items="sedes" label="Sede" item-text="Nombre"
                                item-value="id" @input="fetchAgendas()"></v-select>
                        </v-flex>
                        <v-flex xs5 px-1 v-show="campoCup()">
                            <v-autocomplete v-model="data.cup" :items="cups" item-text="nombre" item-value="id"
                                label="Cup" @input="orden(data.cup)"></v-autocomplete>
                        </v-flex>
                        <v-flex xs4 px-1 v-show="campoCup()">
                            <v-text-field v-model="orden_id" readonly label="Orden"></v-text-field>
                        </v-flex>
                        <v-flex xs5 px-1>
                            <v-autocomplete v-model="actividad_selected" :items="actividades" item-text="name"
                                item-value="et_id" label="Actividad" @input="fetchAgendas()"></v-autocomplete>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-select v-model="medico_selected" :items="medicosSede" label="Médico"></v-select>
                        </v-flex>
                        <v-flex xs3 px-1 v-show="campoCup()">
                            <v-select :items="tipospaciente" label="Tipo paciente" v-model="data.tipo_paciente">
                            </v-select>
                        </v-flex>
                        <v-flex xs3 px-1 v-show="campoCup()">
                            <v-select :items="lados" label="Lado" v-model="data.lado"></v-select>
                        </v-flex>
                        <v-flex xs3 px-1 v-show="campoCup()">
                            <v-select :items="prioridades" label="Prioridad" v-model="data.prioridad"></v-select>
                        </v-flex>
                        <v-flex xs3 px-1 v-show="campoCup()">
                            <v-select :items="lecturas" label="Lectura" v-model="data.lectura"></v-select>
                        </v-flex>
                        <v-flex xs3 px-1 v-show="campoCup()">
                            <v-select :items="tecnicas" label="Tecnica" v-model="data.tecnica"></v-select>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="data.tipo_paciente == 'Hospitalario' || data.tipo_paciente == 'Urgencias'">
                            <v-text-field label="Ubicación" v-model="data.ubicacion"></v-text-field>
                        </v-flex>
                        <v-flex xs3 px-1
                            v-if="data.tipo_paciente == 'Hospitalario' || data.tipo_paciente == 'Urgencias'">
                            <v-text-field label="Cama" v-model="data.cama"></v-text-field>
                        </v-flex>
                        <v-flex xs2 px-1
                            v-if="data.tipo_paciente == 'Hospitalario' || data.tipo_paciente == 'Urgencias'">
                            <v-select :items="['Si', 'No']" label="Aislado" v-model="data.aislado"></v-select>
                        </v-flex>
                        <v-flex xs12 px-1 v-if="data.aislado == 'Si' & data.tipo_paciente == 'Hospitalario' ||
                       data.aislado == 'Si' & data.tipo_paciente == 'Urgencias'">
                            <v-text-field label="Observación aislado" v-model="data.obs_aislado"></v-text-field>
                        </v-flex>
                        <v-flex xs5 px-2 v-show="campoCup()">
                            <v-text-field label="Fecha y hora orden" type="datetime-local"
                                format-value="yyyy-MM-ddTHH:mm" color="primary" v-model="data.fecha_orden">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs3>
                            <!-- fecha -->
                            <v-menu v-model="datePicker" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <v-combobox v-model="data.fecha_solicitada" label="Fecha solicitada"
                                        prepend-icon="event" readonly v-on="on"></v-combobox>
                                </template>
                                <v-date-picker color="primary" locale="es" v-model="data.fecha_solicitada" :min="today"
                                    :show-current="false" @input="agendaSolicitada()"></v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs9 px-2>
                            <v-text-field label="Observación" v-model="data.observaciones">
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-dialog v-model="pacienteCreacion" persistent max-width="1200px">
                        <v-card>
                            <v-card-title class="headline info" style="color:white">
                                <span class="title layout justify-center font-weight-light">Crearción de nuevo
                                    paciente</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-form @submit.prevent="guardar_paciente()">
                                        <v-layout wrap>
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Region" v-model="formPaciente.Region" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Ut" v-model="formPaciente.Ut" required></v-text-field>
                                            </v-flex> -->
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Primer Nombre" :rules="nameRules"
                                                    v-model="formPaciente.Primer_Nom">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Segundo Nombre" v-model="formPaciente.SegundoNom"
                                                    required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Primer Apellido" v-model="formPaciente.Primer_Ape"
                                                    required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Segundo Apellido"
                                                    v-model="formPaciente.Segundo_Ape" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select label="Tipo Doc" :items="tipoDoc"
                                                    v-model="formPaciente.Tipo_Doc" required>
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Número Doc" type="number"
                                                    v-model="formPaciente.Num_Doc" readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select :items="['F','M']" label="Sexo" required
                                                    v-model="formPaciente.Sexo"></v-select>
                                            </v-flex>
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Fecha_Afiliado" v-model="formPaciente.Fecha_Afiliado"
                                                    required></v-text-field>
                                            </v-flex> -->
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Fecha Nacimiento" v-model="formPaciente.Fecha_Naci"
                                                    type="date">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Discapacidad" v-model="formPaciente.Discapacidad"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Grado Discapacidad"
                                                    v-model="formPaciente.Grado_Discapacidad" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select :items="['BENEFICIARIO','COTIZANTE', 'SUBSIDIADO']"
                                                    label="Tipo Afiliado" required v-model="formPaciente.Tipo_Afiliado">
                                                </v-select>
                                            </v-flex>
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Orden_Judicial" v-model="formPaciente.Orden_Judicial"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Num_Folio" v-model="formPaciente.Num_Folio" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Fecha_Emision" v-model="formPaciente.Fecha_Emision"
                                                    required></v-text-field>
                                            </v-flex> -->
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Parentesco" v-model="formPaciente.Parentesco"
                                                    required>
                                                </v-text-field>
                                            </v-flex> -->
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="TipoDoc_Cotizante"
                                                    v-model="formPaciente.TipoDoc_Cotizante" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Doc_Cotizante" v-model="formPaciente.Doc_Cotizante"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Tipo_Cotizante" v-model="formPaciente.Tipo_Cotizante"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Estado_Afiliado" v-model="formPaciente.Estado_Afiliado"
                                                    required></v-text-field>
                                            </v-flex> -->
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Dane_Mpio" v-model="formPaciente.Dane_Mpio" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Mpio_Afiliado" v-model="formPaciente.Mpio_Afiliado"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Dane_Dpto" v-model="formPaciente.Dane_Dpto" required>
                                                </v-text-field>
                                            </v-flex> -->
                                            <v-flex xs12 sm3 md3>
                                                <v-autocomplete append-icon="search" label="Departamento..."
                                                    :items="departamento" item-text="departamento" hide-details
                                                    v-model="formPaciente.Departamento"></v-autocomplete>
                                            </v-flex>
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Subregion" v-model="formPaciente.Subregion" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Dpto_Atencion" v-model="formPaciente.Dpto_Atencion"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Mpio_Atencion" v-model="formPaciente.Mpio_Atencion"
                                                    required></v-text-field>
                                            </v-flex> -->
                                            <v-flex xs12 sm6 md6>
                                                <v-autocomplete append-icon="search" label="IPS atención..."
                                                    :items="sedesprestadores" item-text="Nombre" item-value="id"
                                                    hide-details v-model="formPaciente.IPS"></v-autocomplete>
                                            </v-flex>
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Sede_Odontologica"
                                                    v-model="formPaciente.Sede_Odontologica" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Medicofamilia" v-model="formPaciente.Medicofamilia"
                                                    required></v-text-field>
                                            </v-flex> -->
                                            <v-flex xs12 sm3 md3>
                                                <v-select label="Etnia" :items="etnia" v-model="formPaciente.Etnia"
                                                    required>
                                                </v-select>
                                            </v-flex>
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Nivel_Sisben" v-model="formPaciente.Nivel_Sisben"
                                                    required></v-text-field>
                                            </v-flex> -->
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Donde Labora" v-model="formPaciente.Laboraen"
                                                    required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-autocomplete append-icon="search" label="Mpio donde Labora"
                                                    :items="municipios" item-text="municipio" item-value="id"
                                                    hide-details v-model="formPaciente.Mpio_Labora"></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Direccion Residencia"
                                                    v-model="formPaciente.Direccion_Residencia" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-autocomplete append-icon="search" label="Mpio Residencia"
                                                    :items="municipios" item-text="municipio" item-value="id"
                                                    hide-details v-model="formPaciente.Mpio_Residencia">
                                                </v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Telefono" v-model="formPaciente.Telefono" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Correo1" v-model="formPaciente.Correo1" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Correo2" v-model="formPaciente.Correo2" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select :items="nhijos" label="Estrato" v-model="formPaciente.Estrato"
                                                    required>
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Celular1" v-model="formPaciente.Celular1" required type="number" maxlength="10"
                                                oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                min="1">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Celular2" v-model="formPaciente.Celular2" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Sexo Biologico"
                                                    v-model="formPaciente.Sexo_Biologico" required></v-text-field>
                                            </v-flex>
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Tipo_Regimen" v-model="formPaciente.Tipo_Regimen"
                                                    required></v-text-field>
                                            </v-flex> -->
                                            <v-flex xs12 sm3 md3>
                                                <v-select :items="nhijos" label="Num Hijos"
                                                    v-model="formPaciente.Num_Hijos" required>
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Vive con" v-model="formPaciente.Vivecon" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select :items="RH" label="RH" v-model="formPaciente.RH" required>
                                                </v-select>
                                            </v-flex>
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Tienetutela" v-model="formPaciente.Tienetutela"
                                                    required></v-text-field>
                                            </v-flex> -->
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Nivel de estudio"
                                                    v-model="formPaciente.Nivelestudio" required></v-text-field>
                                            </v-flex>
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Nombreacompanante"
                                                    v-model="formPaciente.Nombreacompanante" required></v-text-field>
                                            </v-flex> -->
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="Telefonoacompanante"
                                                    v-model="formPaciente.Telefonoacompanante" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Nombreresponsable"
                                                    v-model="formPaciente.Nombreresponsable" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Telefonoresponsable"
                                                    v-model="formPaciente.Telefonoresponsable" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Aseguradora" v-model="formPaciente.Aseguradora"
                                                    required></v-text-field>
                                            </v-flex> -->
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Tipo vinculacion"
                                                    v-model="formPaciente.Tipovinculacion" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="Ocupacion" v-model="formPaciente.Ocupacion"
                                                    required>
                                                </v-text-field>
                                            </v-flex>
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="nivel" v-model="formPaciente.nivel" required>
                                                </v-text-field>
                                            </v-flex> -->
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-select :items="ocupacional" label="Entidad Salud" v-model="formPaciente.entidad_id"
                                                item-text="nombre" item-value="id" required>
                                                </v-select>
                                            </v-flex> -->
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="vlr_upc" v-model="formPaciente.vlr_upc" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="fecha_ini_cont" v-model="formPaciente.fecha_ini_cont"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="fecha_fin_cont" v-model="formPaciente.fecha_fin_cont"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="valor_cont_cap" v-model="formPaciente.valor_cont_cap"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="valot_cont_pyp" v-model="formPaciente.valot_cont_pyp"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="sem_cot" v-model="formPaciente.sem_cot" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="tipo_categoria" v-model="formPaciente.tipo_categoria"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="ut_saliente" v-model="formPaciente.ut_saliente"
                                                    required></v-text-field>
                                            </v-flex> -->
                                            <v-flex xs12 sm3 md3>
                                                <v-select :items="estadoCivil" label="Estado civil"
                                                    v-model="formPaciente.estado_civil" required></v-select>
                                            </v-flex>
                                            <!-- <v-flex xs12 sm3 md3>
                                                <v-text-field label="dx" v-model="formPaciente.dx" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="cups" v-model="formPaciente.cups" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="cums" v-model="formPaciente.cums" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="propios" v-model="formPaciente.propios" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="f_solicitud" v-model="formPaciente.f_solicitud"
                                                    required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="anexo" v-model="formPaciente.anexo" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="ruta" v-model="formPaciente.ruta" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="represa" v-model="formPaciente.represa" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="justifica_represa"
                                                    v-model="formPaciente.justifica_represa" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-text-field label="observacion_contratista"
                                                    v-model="formPaciente.observacion_contratista" required></v-text-field>
                                            </v-flex> -->
                                            <v-flex xs12 sm3 md3>
                                                <v-select
                                                    :items="['Coordinador(a)','Directivo(a)','Docente','Orientador(a)','Rector(a)']"
                                                    label="cargo laboral" required v-model="formPaciente.cargo_laboral">
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select :items="['0','1 a 2','3 a 4','> 4']"
                                                    label="composicion familiar" required
                                                    v-model="formPaciente.composicion_familiar"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select :items="['Propia','Arriendo','Familiar','Compartida']"
                                                    label="vivienda" required v-model="formPaciente.vivienda">
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select :items="['Ninguna','(1-3)','(4-6)','Mas de  6']"
                                                    label="personas a cargo" required
                                                    v-model="formPaciente.personas_a_cargo"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select :items="['Carrera administrativa ','Provisional ','Libre nombramiento ','Prestación de servicios','Honorarios'
                                ]" label="tipo contratacion" required v-model="formPaciente.tipo_contratacion">
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select
                                                    :items="['< 1 año','1-a 5 años ','5-10 años ','10-15 años ','> 15 años']"
                                                    label="antiguedad en empresa" required
                                                    v-model="formPaciente.antiguedad_en_empresa"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select
                                                    :items="['< 1 año','1-a 5 años','5-10 años','10-15 años','> 15 años']"
                                                    label="antiguedad cargo actual" required
                                                    v-model="formPaciente.antiguedad_cargo_actual"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm3 md3>
                                                <v-select :items="['Ninguno ','(1 - 3)','(4 -6) ','> 6']"
                                                    label="# cursos a cargo" required
                                                    v-model="formPaciente.numero_cursos_a_cargo"></v-select>
                                            </v-flex>
                                        </v-layout>
                                    </v-form>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="red" dark @click="pacienteCreacion = false">Cerrar</v-btn>
                                <v-btn color="success" dark @click="guardar_paciente()">Guardar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <!-- <v-dialog v-model="creaPaciente" persistent max-width="500">
                        <v-card>
                             <v-card-title class="headline info" style="color:white">
                                <span class="title layout justify-center font-weight-light">Creacción de nuevo paciente</span>
                            </v-card-title>
                                <v-card-text style="color:red"><b>El paciente con la cédula ingresada no se encuentra registrado!</b></v-card-text>
                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn v-if="can('crear.pacientes')" color="success"  dark @click="pacienteCreacion = true,  creaPaciente = false">Crear paciente</v-btn>
                            <v-btn color="red"  dark @click="creaPaciente = false">Cerar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog> -->
                    <v-container grid-list-md fluid class="pa-0">
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
                        <template>
                            <v-data-table :headers="headers" :items="agendaDisponible" item-key="name"
                                class="elevation-1" color="primary" :rows-per-page-items="[20,30,50]"
                                rows-per-page-text="Citas por página">
                                <template v-slot:items="props">
                                    <td class="text-xs-center">{{ props.item.hora_inicio | time }}</td>
                                    <td class="text-xs-center">{{ props.item.consultorio }}</td>
                                    <td class="text-xs-center">
                                        <v-btn v-show="paciente.id && can('cita.toAssign')" color="success" fab outline
                                            small @click="asignarCita(props.item)">
                                            <v-icon>how_to_reg</v-icon>
                                        </v-btn>
                                    </td>
                                </template>
                            </v-data-table>
                        </template>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    import moment from 'moment';
    import Swal from 'sweetalert2';
    export default {
        name: 'AsignacionCita',
        components: {},
        data() {
            return {
                ocupacional: [],
                nameRules: [v => !!v || 'Este campo es is required'],
                RH: ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'],
                estadoCivil: ['Soltero', 'Casado', 'Viudo', 'Union Libre ', 'Separado'],
                nhijos: ['1', '2', '3', '4', '5', '6', '7', '8'],
                etnia: ['Indígena', 'Gitano', 'Raizal', 'Palenquero', 'Negro(a)', 'Mulato(a)', 'Afrocolombiano(a)',
                    'Afro descendiente'
                ],
                departamento: [],
                municipios: [],
                sedesprestadores: [],
                tipoDoc: ['CC', 'CE', 'PA', 'RC', 'TI'],
                formPaciente: {
                    Region: null,
                    Ut: null,
                    Primer_Nom: null,
                    SegundoNom: null,
                    Primer_Ape: null,
                    Segundo_Ape: null,
                    Tipo_Doc: null,
                    Num_Doc: null,
                    Sexo: null,
                    Fecha_Afiliado: null,
                    Fecha_Naci: null,
                    Edad_Cumplida: null,
                    Discapacidad: null,
                    Grado_Discapacidad: null,
                    Tipo_Afiliado: null,
                    Orden_Judicial: null,
                    Num_Folio: null,
                    Fecha_Emision: null,
                    Parentesco: null,
                    TipoDoc_Cotizante: null,
                    Doc_Cotizante: null,
                    Tipo_Cotizante: null,
                    Estado_Afiliado: 1,
                    Dane_Mpio: null,
                    Mpio_Afiliado: null,
                    Dane_Dpto: null,
                    Departamento: null,
                    Subregion: null,
                    Dpto_Atencion: null,
                    Mpio_Atencion: null,
                    IPS: null,
                    Sede_Odontologica: null,
                    Medicofamilia: null,
                    Etnia: null,
                    Nivel_Sisben: null,
                    Laboraen: null,
                    Mpio_Labora: null,
                    Direccion_Residencia: null,
                    Mpio_Residencia: null,
                    Telefono: null,
                    Correo1: null,
                    Correo2: null,
                    Estrato: null,
                    Celular1: null,
                    Celular2: null,
                    Sexo_Biologico: null,
                    Tipo_Regimen: null,
                    Num_Hijos: null,
                    Vivecon: null,
                    RH: null,
                    Tienetutela: null,
                    Nivelestudio: null,
                    Nombreacompanante: null,
                    Telefonoacompanante: null,
                    Nombreresponsable: null,
                    Telefonoresponsable: null,
                    Aseguradora: null,
                    Tipovinculacion: null,
                    Ocupacion: null,
                    nivel: null,
                    entidad_id: 4,
                    vlr_upc: null,
                    fecha_ini_cont: null,
                    fecha_fin_cont: null,
                    valor_cont_cap: null,
                    valot_cont_pyp: null,
                    sem_cot: null,
                    tipo_categoria: null,
                    ut_saliente: null,
                    estado_civil: null,
                    dx: null,
                    cups: null,
                    cums: null,
                    propios: null,
                    f_solicitud: null,
                    anexo: null,
                    ruta: null,
                    represa: null,
                    justifica_represa: null,
                    observacion_contratista: null,
                    cargo_laboral: null,
                    composicion_familiar: null,
                    vivienda: null,
                    personas_a_cargo: null,
                    tipo_contratacion: null,
                    antiguedad_en_empresa: null,
                    antiguedad_cargo_actual: null,
                    numero_cursos_a_cargo: null,
                },
                pacienteCreacion: false,
                creaPaciente: false,
                DialogPaciente: false,
                Tipo_Doc: ['CC', 'CE', 'PA', 'RC', 'TI'],
                lados: ['Derecho', 'Izquierdo'],
                prioridades: ['Urgente', 'Normal'],
                lecturas: ['Si', 'No'],
                tecnicas: ['Constrastada', 'Simple'],
                tipospaciente: ['Ambulatorio', 'Hospitalario', 'Urgencias'],
                motivoCancelar: false,
                dialog: false,
                message: '',
                citas: {},
                todaspendientesmodal: false,
                todasanterioresmodal: false,
                headerCitaPendientes: [{
                        text: 'Especialidad',
                        align: 'center',
                        sortable: false,
                        value: 'Especialidad'
                    },
                    {
                        text: 'Actividad',
                        align: 'center',
                        sortable: false,
                        value: 'Tipo_agenda'
                    },
                    {
                        text: 'Fecha',
                        align: 'center',
                        sortable: false,
                        value: 'Fecha'
                    },
                    {
                        text: 'Hora De la cita ',
                        align: 'center',
                        sortable: false,
                        value: 'Hora_Inicio'
                    },
                    {
                        text: 'Sede',
                        align: 'center',
                        sortable: false,
                        value: 'Sede'
                    },
                    {
                        text: 'Nombre medico',
                        align: 'center',
                        sortable: false,
                        value: 'Nombre_medico'
                    },
                    {
                        text: 'Usuario Que Asigno',
                        align: 'center',
                        sortable: false,
                        value: 'User_asgina'
                    },
                    {
                        text: '',
                        align: 'center',
                        sortable: false,
                        value: ''
                    },
                ],
                headercitaanteriores: [{
                        text: 'Especialidad',
                        align: 'center',
                        sortable: false,
                        value: 'Especialidad'
                    },
                    {
                        text: 'Actividad',
                        align: 'center',
                        sortable: false,
                        value: 'Tipo_agenda'
                    },
                    {
                        text: 'Fecha',
                        align: 'center',
                        sortable: false,
                        value: 'Fecha'
                    },
                    {
                        text: 'Hora De la cita ',
                        align: 'center',
                        sortable: false,
                        value: 'Hora_Inicio'
                    },
                    {
                        text: 'Sede',
                        align: 'center',
                        sortable: false,
                        value: 'Sede'
                    },
                    {
                        text: 'Nombre del medico',
                        align: 'center',
                        sortable: false,
                        value: 'Nombre_medico'
                    },
                    {
                        text: 'Usuario que cancela o asigna',
                        align: 'center',
                        sortable: false,
                        value: 'User_asgina'
                    },
                    {
                        text: 'Estado',
                        align: 'center',
                        sortable: false,
                        value: 'Nom_estado'
                    },
                    {
                        text: '',
                        align: 'center',
                        sortable: false,
                        value: ''
                    },
                ],
                headers: [{
                        text: 'Hora',
                        align: 'center',
                        sortable: false,
                        value: 'Agenda_id'
                    },
                    {
                        text: 'Consultorio',
                        sortable: false,
                        align: 'center',
                        value: 'consultorio'
                    },
                    {
                        text: '',
                        align: 'center',
                        value: ''
                    },
                ],
                today: moment().format('YYYY-MM-DD'),
                agendas: [],
                cedula_paciente: '',
                medico_selected: null,
                sede_selected: null,
                especialidad_selected: null,
                actividad_selected: null,
                fecha_selected: null,
                tipos_agenda: null,
                datePicker: false,
                datePicker2: false,
                dateSolicitada: false,
                fecha: null,
                cancelar: {
                    motivo: '',
                    Paciente_id: null,
                    Detallecita_id: null,
                },
                confirmar: {
                    Paciente_id: null,
                    Detallecita_id: null,
                },
                paciente: {
                    Primer_Nom: '',
                    Primer_Ape: '',
                    Tipo_Doc: '',
                    Num_Doc: '',
                    Edad_Cumplida: '',
                    Tipo_Afiliado: ''
                },
                ubicacion: {
                    Telefono: '',
                    Celular1: '',
                    Celular2: '',
                    Correo1: '',
                    Correo2: '',
                    Direccion_Residencia: ''
                },
                data: {
                    cita_id: null,
                    hora_inicio: null,
                    consultorio: null,
                    Paciente_id: null,
                    fecha_solicitada: null,
                    observaciones: null,
                    cup: '',
                    lado: '',
                    prioridad: '',
                    lectura: '',
                    tecnica: '',
                    tipo_paciente: '',
                    ubicacion: '',
                    cama: '',
                    aislado: '',
                    obs_aislado: '',
                    fecha_orden: null
                },
                cita_pendiente: {
                    Hora_Inicio: '',
                    Consultorio: '',
                    Sede: '',
                    Direccion_Sede: '',
                    Nombre_medico: '',
                    Apellido_medico: '',
                    Especialidad: '',
                    Tipo_agenda: '',
                },
                cita_anteriores: {
                    Hora_Inicio: '',
                    Consultorio: '',
                    Sede: '',
                    Direccion_Sede: '',
                    Nombre_medico: '',
                    Apellido_medico: '',
                    Usuario_Asigna: '',
                    Especialidad: '',
                    Tipo_agenda: '',
                },
                cancelarcita: {},
                allcita_pendiente: [],
                especialidades: [],
                sedes: [],
                preload_cita: false,
                cups: [],
                orden_id: '',
                dialogshowMotivo: false,
                descripcionMotivo: {}
            }
        },
        filters: {
            fecha_cita_pendiente(fecha) {
                return moment(fecha).format('dddd, D MMMM, YYYY');
            },
            fecha_cita_anteriores(fecha) {
                return moment(fecha).format('dddd, D MMMM, YYYY');
            },
            hora_cita_anteriores(Hora_Inicio) {
                return moment(Hora_Inicio).format('HH:mm');
            },
            hora_cita_pendiente(Hora_Inicio) {
                return moment(Hora_Inicio).format('HH:mm');
            },
            time(date) {
                return moment(date).format('HH:mm:ss');
            }
        },
        computed: {
            ...mapGetters(['can']),
            agendaDisponible() {
                let citasEnable = [];
                let citas = [];
                for (let i = 0; i < this.agendas.length; i++) {
                    let citas = [];
                    let medico = `${this.agendas[i].nombreMedico} ${this.agendas[i].apellidoMedico}`
                    if (medico === this.medico_selected &&
                        this.agendas[i].fecha == this.fecha) {
                        citas = this.agendas[i].citas.map(cita => {
                            return {
                                id: cita.id,
                                hora_inicio: cita.Hora_Inicio,
                                consultorio: this.agendas[i].nombreConsultorio
                            }
                        });
                        citasEnable = citasEnable.concat(citas);
                    }
                }
                return citasEnable.sort((a, b) => moment(a.hora_inicio) - moment(b.hora_inicio));
            },
            sedesDisponible() {
                return this.agendas.filter((agenda) => {
                    if (agenda.Especialidad == this.especialidad_selected) return true;
                    return false;
                }).map((agenda) => agenda.Sede);
            },
            medicosSede() {
                return this.agendas.map((agenda) => `${agenda.nombreMedico} ${agenda.apellidoMedico}`)
            },
            especialiadesq() {
                return this.agendas.map(agenda => agenda.Especialidad)
            },
            actividades() {
                return this.especialidades.filter(e => this.especialidad_selected === e.id && this.sede_selected == e
                    .sede)
            }
        },
        mounted() {
            moment.locale('es');
            this.fetchDepartamentos();
            this.fetchMunicipios();
            this.fetchSedePrestadores();
            this.fetchEntidades();
        },
        methods: {
            fetchEntidades() {
                axios.get('/api/entidades/entidadesOcpucacionales')
                    .then(res => {
                        this.ocupacional = res.data;
                    })
            },
            fetchSedePrestadores() {
                axios.get('/api/prestador/all')
                    .then((res) => {
                        this.sedesprestadores = res.data
                    })
                    .catch((err) => console.log(err));
            },
            fetchMunicipios() {
                axios.get('/api/municipio/lista')
                    .then(res => {
                        this.municipios = res.data
                    })
            },
            fetchDepartamentos() {
                axios.get('/api/domiciliaria/departamento')
                    .then((res) => {
                        this.departamento = res.data
                    })
                    .catch((err) => console.log(err));
            },
            guardar_paciente() {
                if (!this.formPaciente.Num_Doc) {
                    swal("El Número de Documento es Requerido")
                    return;
                }
                if (!this.formPaciente.Primer_Nom) {
                    swal("El primer nombre es Requerido")
                    return;
                }
                if (!this.formPaciente.Primer_Ape) {
                    swal("El primer apellido es Requerido")
                    return;
                }
                if (!this.formPaciente.Tipo_Doc) {
                    swal("El tipo documento es Requerido")
                    return;
                }
                if (!this.formPaciente.Sexo) {
                    swal("El sexo es Requerido")
                    return;
                }
                if (!this.formPaciente.Fecha_Naci) {
                    swal("La fecha nacimiento es Requerida")
                    return;
                }
                axios.post('/api/paciente/guardarPaciente', this.formPaciente)
                    .then(res => {
                        if (!res.data.paciente) {
                            this.$alerSuccess(res.data.mensaje);
                            this.pacienteCreacion = false;
                            this.creaPaciente = false;
                            this.clearCrearPaciente();
                            this.search_paciente();
                        } else {
                            this.$alerError(res.data.mensaje)
                        }

                    })
            },
            fetchEspecialidades() {
                axios.get(`/api/agenda/agendaDisponible/especialidades`)
                    .then((res) => {
                        this.especialidades = res.data
                    });
            },
            campoCup() {
                if (this.especialidad_selected == 58 && this.cups == "") {
                    swal({
                        title: "¡Información!",
                        text: 'No se puede asignar cita de Imagenologia, no tiene cup aprobado.',
                        timer: 4000,
                        icon: "info",
                        buttons: false
                    });
                    return false;
                } else if (this.especialidad_selected == 58) return true;
            },
            orden(cup) {
                this.orden_id = cup.split('-')[1]
            },
            fetchSedes() {
                axios.post(`/api/agenda/agendaDisponible/sedes`, {
                        especialidad: this.especialidad_selected
                    })
                    .then((res) => {
                        this.sedes = res.data
                    });
            },
            fetchAgendas() {
                this.datePicker = false;
                if (this.especialidad_selected && this.sede_selected && this.actividad_selected) {
                    axios.post('/api/agenda/agendaDisponible', {
                            fecha: this.fecha,
                            sede: this.sede_selected,
                            tipo_agenda: this.actividad_selected
                        })
                        .then(res => {
                            this.agendas = res.data
                        });
                }
            },
            search_paciente() {
                if (!this.cedula_paciente) return;

                this.fetchEspecialidades();
                axios.post(`/api/paciente/verPaciente/${this.cedula_paciente}`)
                    .then((res) => {
                        if (res.data.paciente) {
                            console.log('cr',res.data);
                            this.paciente = res.data.paciente;
                            this.sede_selected = this.paciente.IPS;
                            this.citaPendiente(res.data.paciente.id);
                            this.citasanteriores(res.data.paciente.id);
                            this.cupsPaciente(res.data.paciente.id)
                        } else {
                            this.formPaciente.Num_Doc = this.cedula_paciente;
                            this.pacienteCreacion = true
                        }
                        if (res.data.message) this.showMessage(res.data.message);
                    });
            },
            citaPendiente(Paciente_id) {
                axios.post(`/api/cita/citaspendientes`, {
                        Paciente_id
                    })
                    .then((res) => {
                        if (res.data[0]) {
                            this.cita_pendiente = res.data[0];
                            this.allcita_pendiente = res.data;
                        } else {
                            this.cita_pendiente = {
                                Hora_Inicio: '',
                                Consultorio: '',
                                Sede: '',
                                Nombre_medico: '',
                                Apellido_medico: '',
                                Especialidad: '',
                                Tipo_agenda: '',
                            };
                            this.allcita_pendiente = null;
                        }

                    });
            },
            citasanteriores(Paciente_id) {
                axios.post(`/api/cita/citasanteriores`, {
                        Paciente_id
                    })
                    .then((res) => {
                        if (res.data[0]) {
                            this.cita_anteriores = res.data[0];
                            this.allcita_anteriores = res.data;
                        } else {
                            this.cita_anteriores = {
                                Hora_Inicio: '',
                                Consultorio: '',
                                Sede: '',
                                Nombre_medico: '',
                                Apellido_medico: '',
                                Especialidad: '',
                                Usuario_Asigna: '',
                                Tipo_agenda: '',
                            };
                            this.allcita_anteriores = null;
                        }

                    });
            },
            cupsPaciente(Paciente_id) {
                axios.get(`/api/cita/cupspaciente/${Paciente_id}`)
                    .then((res) => {
                        this.cups = res.data.map(cup => {
                            return {
                                id: `${cup.id}-${cup.Orden_id}`,
                                nombre: cup.Nombre
                            };
                        });
                    })
            },
            asignarCita(cita) {

                this.dialog = true;
                this.data.cita_id = cita.id;
                this.data.hora_inicio = cita.hora_inicio;
                this.data.consultorio = cita.consultorio;
                this.data.Paciente_id = this.paciente.id;
                this.fecha_selected = moment(this.fecha).format('dddd, D MMMM, YYYY');
            },
            actualizarDatosPersonales() {
                axios.put(`/api/paciente/edit_ubicacion_data/${this.paciente.id}`, this.paciente)
                    .then((res) => {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            icon: 'success',
                            title: 'Datos actualizados!'
                        });
                    })

            },
            openMotivo(cita = undefined) {
                this.cancelarcita = cita;
                this.cancelar = {
                    id: cita.id,
                    motivo: '',
                    Paciente_id: null,
                    Detallecita_id: null,
                }
                this.motivoCancelar = true;

            },
            cancelarCita() {
                if (this.cancelar.motivo.length < 20) {
                    swal("El motivo debe ser mayor a 20 caracteres")
                    return;
                }
                this.cancelar.Paciente_id = this.paciente.id;
                this.motivoCancelar = false;
                axios.put(`/api/cita/cancelar/${this.cancelar.id}`, this.cancelar)
                    .then((res) => {
                        swal({
                            title: "¡Cita Cancelada!",
                            text: `${res.data.message}`,
                            timer: 3000,
                            icon: "success",
                            buttons: false
                        });
                        this.cancelarcita = {};
                        this.citaPendiente(this.paciente.id);
                    })
            },
            confirmarCitaModal(cita) {
                this.confirmar.Paciente_id = this.paciente.id;
                swal({
                        title: "¿Confirmar Cita?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((confirm) => {
                        if (confirm) {
                            axios.put(`/api/cita/confirmar/${this.cita.id}`, this.confirmar)
                                .then((res) => {
                                    swal({
                                        title: "¡Cita confirmada!",
                                        text: `${res.data.message}`,
                                        timer: 3000,
                                        icon: "success",
                                        buttons: false
                                    });
                                    this.citaPendiente(this.paciente.id);
                                })
                        }

                    });
            },
            confirmarCita() {
                this.confirmar.Paciente_id = this.paciente.id;
                swal({
                        title: "¿Confirmar Cita?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((confirm) => {
                        if (confirm) {
                            axios.put(`/api/cita/confirmar/${this.cita_pendiente.id}`, this.confirmar)
                                .then((res) => {
                                    swal({
                                        title: "¡Cita confirmada!",
                                        text: `${res.data.message}`,
                                        timer: 3000,
                                        icon: "success",
                                        buttons: false
                                    });
                                    this.citaPendiente(this.paciente.id);
                                }).catch((err) => console.log(err.response))
                        }

                    });
            },
            agendarCita() {
                this.preload_cita = true;
                axios.put(`/api/cita/asignarcita/${this.data.cita_id}`, this.data)
                    .then((res) => {
                        this.dialog = false;
                        this.clearFields();
                        // this.printPDF();
                        this.fetchAgendas();

                        this.citaPendiente(this.paciente.id);
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 10000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            icon: 'success',
                            title: `${res.data.message}`,
                        });
                        this.preload_cita = false

                    })
                    .catch((err) => this.showMessage(err.response.data.message))
            },
            showMessage(message) {
                this.preload_cita = false
                swal({
                    title: `${message}`,
                    icon: "warning",
                });
            },
            clearFields() {
                this.cedula_paciente = '',
                    this.medico_selected = null,
                    this.sede_selected = null,
                    this.actividad_selected = null,
                    this.especialidad_selected = null,
                    this.fecha_selected = null,
                    this.fecha = moment().format('YYYY-MM-DD'),
                    this.paciente = {
                        Primer_Nom: '',
                        Primer_Ape: '',
                        Tipo_Doc: '',
                        Num_Doc: '',
                        Edad_Cumplida: '',
                        Tipo_Afiliado: '',
                    }
                this.cedula_paciente = '';
                this.ubicacion = {
                    Telefono: '',
                    Celular1: '',
                    Celular2: '',
                    Correo1: '',
                    Correo2: '',
                }
                this.data = {
                    Paciente_id: null,
                    cita_id: null,
                    hora_inicio: null,
                    consultorio: null,
                    fecha_solicitada: moment().format('YYYY-MM-DD'),
                    cup: '',
                    lado: '',
                    prioridad: '',
                    lectura: '',
                    ubicacion: '',
                    cama: '',
                    aislado: '',
                    obs_aislado: '',
                    fecha_orden: ''
                }
                this.cita_pendiente = {
                    Hora_Inicio: '',
                    Consultorio: '',
                    Sede: '',
                    Nombre_medico: '',
                    Apellido_medico: '',
                    Especialidad: '',
                    Tipo_agenda: '',
                }
                this.cita_anteriores = {
                    Hora_Inicio: '',
                    Consultorio: '',
                    Sede: '',
                    Nombre_medico: '',
                    Apellido_medico: '',
                    Usuario_Asigna: '',
                    Especialidad: '',
                    Tipo_agenda: '',
                }
                this.cancelar = {
                    motivo: '',
                    Paciente_id: null,
                    Detallecita_id: null,
                }
                this.cups = []
                this.orden_id = ''
            },
            agendaSolicitada() {
                this.dateSolicitada = false;
                this.fecha = this.data.fecha_solicitada;
                this.fetchAgendas();
            },
            diasDisponibles(val) {
                let dia = null;
                this.agendas.forEach((agenda) => {
                    if (agenda.fecha == val) {
                        dia = val;
                    }
                })
                if (dia) return dia
            },
            diasEvents(val) {
                let dia = null;
                let many = false;
                this.agendas.forEach((agenda) => {
                    if (agenda.fecha == val) {
                        dia = val;

                        let medico = `${agenda.nombreMedico} ${agenda.apellidoMedico}`
                        if (medico === this.medico_selected) {
                            if (agenda.citas[0].Hora_Inicio.substring(0, 10) === dia) many = true;
                        }

                    }
                })

                if (dia) {
                    if (many) return ['green lighten-1', 'red']
                    else return true
                }
                return false
            },
            printPDF(cita) {
                const pdf = {
                type: 'cita',
                id: cita
            }
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
            // getPDFCitas(cita) {
            //
            //     console.log("cita", cita)
            //
            //     if (!this.paciente.Primer_Nom)
            //         this.paciente.Primer_Nom = '';
            //
            //     if (!this.paciente.SegundoNom)
            //         this.paciente.SegundoNom = '';
            //
            //     if (!this.paciente.Primer_Ape)
            //         this.paciente.Primer_Ape = ';'
            //
            //     if (!this.paciente.Segundo_Ape)
            //         this.paciente.Segundo_Ape = '';
            //
            //     this.message =
            //         `Asignar cita de tipo ${ cita.Tipo_agenda } al usuario ${ this.paciente.Primer_Nom } ${ this.paciente.SegundoNom } ${ this.paciente.Primer_Ape } ${ this.paciente.Segundo_Ape } identificado con ${ this.paciente.Tipo_Doc } N° ${ this.paciente.Num_Doc } el día ${ cita.Fecha } a las ${ moment(cita.Hora_Inicio).format('HH:mm:ss') } en la sede ${ cita.Sede }, ${ cita.Consultorio } con el médico ${ cita.Nombre_medico } ${ cita.Apellido_medico }`;
            //     return (this.citas = {
            //         Primer_Nom: this.paciente.Primer_Nom,
            //         Segundo_Nom: this.paciente.SegundoNom,
            //         Primer_Ape: this.paciente.Primer_Ape,
            //         Segundo_Ape: this.paciente.Segundo_Ape,
            //         Tipo_Doc: this.paciente.Tipo_Doc,
            //         Num_Doc: this.paciente.Num_Doc,
            //         Edad_Cumplida: this.paciente.Edad_Cumplida,
            //         Sexo: this.paciente.Sexo,
            //         IPS: this.paciente.IPS,
            //         Direccion_Residencia: this.paciente.Direccion_Residencia,
            //         Correo: this.paciente.Correo,
            //         Telefono: this.paciente.Telefono,
            //         Tipo_Afiliado: this.paciente.Tipo_Afiliado,
            //         Estado_Afiliado: this.paciente.Estado_Afiliado,
            //         Direccion_Sede: cita.Direccion_Sede,
            //         Tipo_cita: cita.Tipo_agenda,
            //         type: "cita",
            //         holi: this.message,
            //     });
            // },
            showMotivo(motivo) {
                this.dialogshowMotivo = true
                this.descripcionMotivo = motivo
            },
            clearCrearPaciente() {
                for (const prop of Object.getOwnPropertyNames(this.formPaciente)) {
                    this.formPaciente[prop] = "";
                }
            },
        }
    }

</script>

<style lang="scss" scoped>

</style>
