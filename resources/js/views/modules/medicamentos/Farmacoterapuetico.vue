<template>
    <div>
        <v-card max-height="100%" class="mb-3">
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Buscar paciente</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container grid-list-md fluid class="pa-0">
                    <v-layout wrap align-center justify-center>
                        <v-flex xs12>
                            <v-form @submit.prevent="fechtMedicamentos()">
                                <v-layout row wrap>
                                    <v-flex xs4>
                                        <v-text-field v-model="cedula_paciente" label="Número de Documento">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs2>
                                        <v-btn @click="fechtMedicamentos()" v-if="cedula_paciente == ''" fab outline
                                            small color="success">
                                            <v-icon>search</v-icon>
                                        </v-btn>
                                        <v-btn @click="clearFields()" v-if="cedula_paciente != ''" fab outline small
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
            <v-flex xs12 sm12 md12>
                <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                    <span class="title layout justify-center font-weight-light">PERFIL FARMACOTERAPEUTICO</span>
                </v-card-title>
            </v-flex>
            <v-expansion-panel focusable>
                <v-expansion-panel-content v-for="(data,index) in historicoMedicamentos" :key="index">
                    <template v-slot:header>
                        <div @click="historicoOrdenes(data)">{{data.medicamentos}}</div>
                    </template>
                    <v-card>
                        <v-data-table :headers="headersHistoricoMedicamentos" :items="historicoOrdens"
                            class="elevation-1" item-key="orden">
                            <template v-slot:items="props">
                                <td>{{ props.item.orden }}</td>
                                <td class="text-xs-center"
                                    v-if="props.item.EstadosOrden == 1 || props.item.EstadosOrden == 7 ">
                                    <v-chip color="info" text-color="white">{{ props.item.Estado }}
                                    </v-chip>
                                </td>
                                <td class="text-xs-center"
                                    v-if="props.item.EstadosOrden == 17 || props.item.EstadosOrden == 24 || props.item.EstadosOrden == 25 || props.item.EstadosOrden == 26 ">
                                    <v-chip @click="abrirModalNegaciones(props.item.detaarticulordens_id)" color="red"
                                        text-color="white">{{ props.item.Estado }}</v-chip>
                                </td>
                                <td class="text-xs-center" v-if="props.item.EstadosOrden == 50">
                                    <v-chip @click="abrirModalSuspenciones(props.item.detaarticulordens_id)"
                                        color="warning" text-color="white">{{ props.item.Estado }}
                                    </v-chip>
                                </td>
                                <td class="text-xs-center"
                                    v-if="props.item.EstadosOrden == 18 | props.item.EstadosOrden == 19">
                                    <v-chip color="success" text-color="white">{{ props.item.Estado }}
                                    </v-chip>
                                <td class="text-xs-center">{{ props.item.Fechaorden }}</td>
                                <td class="text-xs-center">{{ props.item.Cantidadosis }}</td>
                                <td class="text-xs-center">{{ props.item.Via }}</td>
                                <td class="text-xs-center">{{ props.item.Unidadmedida }}</td>
                                <td class="text-xs-center">{{ props.item.Frecuencia }}</td>
                                <td class="text-xs-center">{{ props.item.Cantidadpormedico }}</td>
                                <td class="text-xs-center">{{ props.item.Duracion }}</td>
                                <td class="text-xs-center">{{ props.item.Observacion }}</td>
                                <td class="text-xs-center">
                                    <v-btn v-if="props.item.Datetimeingreso < '2022-04-20 00:00:00.000'" color="blue"
                                        dark v-on="on" @click="printhc()">
                                        <v-icon>assignment_turned_in</v-icon>
                                    </v-btn>
                                    <v-btn v-else color="blue" dark v-on="on" @click="imprimirhc(props.item)">
                                        <v-icon>assignment_turned_in</v-icon>
                                    </v-btn>
                                </td>
                                <td class="text-xs-center">
                                    <v-btn fab small color="warning"
                                        @click="abrirAdherencia(props.item),fetchAdherencia(),fetchUser()">
                                        <v-icon>mdi-message-alert</v-icon>
                                    </v-btn>
                                </td>
                                <td class="text-xs-center">
                                    <v-btn fab small color="red" style="color: white"
                                        @click="abrirSeguridad(props.item),fetchSeguridad(),fetchUser()">
                                        <v-icon>verified_user</v-icon>
                                    </v-btn>
                                </td>
                                <td class="text-xs-center">
                                    <v-btn fab small color="success"
                                        @click="abrirEfectividad(props.item),fetchEfectividad(),fetchUser()">
                                        <v-icon>mode_edit</v-icon>
                                    </v-btn>
                                </td>
                                <td class="text-xs-center">
                                    <v-btn fab small color="info"
                                        @click="abrirNecesidad(props.item),fetchNecesidad(),fetchUser()">
                                        <v-icon>assignment_turned_in</v-icon>
                                    </v-btn>
                                </td>
                                <td class="text-xs-center">
                                    <v-btn fab small color="purple" style="color: white"
                                        @click="abrirOtros(props.item),fetchOtros(),fetchUser()">
                                        <v-icon>find_in_page</v-icon>
                                    </v-btn>
                                </td>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
            <v-dialog v-model="dialogNegaciones" persistent width="500">
                <v-card>
                    <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                        <span class="title layout justify-center font-weight-light">Motivo</span>
                    </v-card-title>

                    <v-card-text>
                        <v-textarea name="input-7-1" label="Motivo de Suspencion" v-model="motivo.negacion">
                        </v-textarea>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" round flat @click="dialogNegaciones = false">
                            Cerrar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialogSuspenciones" persistent width="500">
                <v-card>
                    <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                        <span class="title layout justify-center font-weight-light">MOTIVO</span>
                    </v-card-title>

                    <v-card-text>
                        <v-textarea name="input-7-1" v-model="motivo.suspencion">
                        </v-textarea>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" flat @click="dialogSuspenciones = false">
                            Cerrar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialogabrirAdherencia" persistent width="1300">
                <v-card>
                    <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                        <span class="title layout justify-center font-weight-light">ADHERENCIA</span>
                    </v-card-title>
                    <v-card-text>
                        <v-flex xs12>
                            <v-autocomplete v-model="adherencia.adherente" :items="SiNo" label="¿Adherente?">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12>
                            <v-autocomplete v-model="adherencia.causal" :items="causal"
                                label="Causal de  no Adherencia">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>
                    <v-card-text>
                        <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                            <span class="title layout justify-center font-weight-light">RECOMENDACION PRINCIPAL
                                REALIZADA</span>
                        </v-card-title>
                        <v-flex xs12>
                            <v-autocomplete v-model="adherencia.intervencion_principal" :items="intervencionPrincipal">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>
                    <v-card-text>
                        <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                            <span class="title layout justify-center font-weight-light">INTERVENCION DIRIGIDA</span>
                        </v-card-title>
                        <v-flex xs12>
                            <v-autocomplete v-model="adherencia.intervencion_dirigida" :items="allUsers"
                                item-text="nombre" item-value="id">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success" round @click="saveAdherencia()">
                            Guardar
                        </v-btn>
                        <v-btn color="primary" round @click="dialogabrirAdherencia = false">
                            Cerrar
                        </v-btn>
                    </v-card-actions>
                    <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                        <span class="title layout justify-center font-weight-light">HISTORICO</span>
                    </v-card-title>
                    <v-data-table :headers="headersAdherencia" :items="itemsadherencia" class="elevation-1">
                        <template v-slot:items="props">
                            <td>{{ props.item.id }}</td>
                            <td class="text-xs-right">{{ props.item.Orden_id }}</td>
                            <td class="text-xs-right">{{ props.item.adherente }}</td>
                            <td class="text-xs-left">{{ props.item.causal }}</td>
                            <td class="text-xs-left">{{ props.item.intervencion_principal }}</td>
                            <td class="text-xs-left">{{ props.item.intervencion_dirigida}}</td>
                            <td class="text-xs-left">{{ props.item.Medico}}</td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialogabrirSeguridad" persistent width="1300">
                <v-card>
                    <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                        <span class="title layout justify-center font-weight-light">SEGURIDAD</span>
                    </v-card-title>
                    <v-card-text>
                        <v-flex xs12>
                            <v-autocomplete v-model="seguridad.reaccion_adversa" :items="SiNo"
                                label="¿Reaccion adversa a  Medicamento?">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea v-model="seguridad.reaccion" v-show="seguridad.reaccion_adversa == 'Si'"
                                label="¿Tipo de reaccion?">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs12>
                            <v-autocomplete v-model="seguridad.prmevidenciado " :items="prm" label="PRM Evidenciado">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>
                    <v-card-text>
                        <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                            <span class="title layout justify-center font-weight-light">RECOMENDACION PRINCIPAL
                                REALIZADA</span>
                        </v-card-title>
                        <v-flex xs12>
                            <v-autocomplete v-model="seguridad.intervencion_principal" :items="intervencionPrincipal">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>
                    <v-card-text>
                        <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                            <span class="title layout justify-center font-weight-light">INTERVENCION DIRIGIDA</span>
                        </v-card-title>
                        <v-flex xs12>
                            <v-autocomplete v-model="seguridad.intervencion_dirigida" :items="allUsers"
                                item-text="nombre" item-value="id">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>
                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success" round @click="saveSeguridad()">
                            Guardar
                        </v-btn>
                        <v-btn color="primary" round @click="dialogabrirSeguridad = false">
                            Cerrar
                        </v-btn>
                    </v-card-actions>
                    <v-data-table :headers="headersSeguridad" :items="itemseguridad" class="elevation-1">
                        <template v-slot:items="props">
                            <td>{{ props.item.id }}</td>
                            <td class="text-xs-center">{{ props.item.Orden_id }}</td>
                            <td class="text-xs-center">{{ props.item.reaccion_adversa }}</td>
                            <td class="text-xs-center">{{ props.item.reaccion }}</td>
                            <td class="text-xs-center">{{ props.item.prmevidenciado }}</td>
                            <td class="text-xs-center">{{ props.item.intervencion_principal }}</td>
                            <td class="text-xs-center">{{ props.item.intervencion_dirigida}}</td>
                            <td class="text-xs-center">{{ props.item.Medico}}</td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialogabrirEfectividad" persistent width="1000">
                <v-card>
                    <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                        <span class="title layout justify-center font-weight-light">EFECTIVIDAD</span>
                    </v-card-title>
                    <v-card-text>
                        <v-flex xs12>
                            <v-textarea v-model="efectividad.reaccion" label="¿Resultado clinico?">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs12>
                            <v-autocomplete v-model="efectividad.prmevidenciado " :items="prm" label="PRM Evidenciado">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>
                    <v-card-text>
                        <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                            <span class="title layout justify-center font-weight-light">RECOMENDACION PRINCIPAL
                                REALIZADA</span>
                        </v-card-title>
                        <v-flex xs12>
                            <v-autocomplete v-model="efectividad.intervencion_principal" :items="intervencionPrincipal">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>
                    <v-card-text>
                        <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                            <span class="title layout justify-center font-weight-light">INTERVENCION DIRIGIDA</span>
                        </v-card-title>
                        <v-flex xs12>
                            <v-autocomplete v-model="efectividad.intervencion_dirigida" :items="allUsers"
                                item-text="nombre" item-value="id">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success" round @click="saveEfectividad()">
                            Guardar
                        </v-btn>
                        <v-btn color="primary" round @click="dialogabrirEfectividad = false">
                            Cerrar
                        </v-btn>
                    </v-card-actions>
                    <v-data-table :headers="headersEfectividad" :items="itemsefectividad" class="elevation-1">
                        <template v-slot:items="props">
                            <td>{{ props.item.id }}</td>
                            <td class="text-xs-center">{{ props.item.Orden_id }}</td>
                            <td class="text-xs-center">{{ props.item.prmevidenciado }}</td>
                            <td class="text-xs-center">{{ props.item.reaccion }}</td>
                            <td class="text-xs-center">{{ props.item.intervencion_principal }}</td>
                            <td class="text-xs-center">{{ props.item.intervencion_dirigida}}</td>
                            <td class="text-xs-left">{{ props.item.Medico}}</td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialogabrirNecesidad" persistent width="1300">
                <v-card>
                    <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                        <span class="title layout justify-center font-weight-light">NECESIDAD</span>
                    </v-card-title>

                    <v-card-text>
                        <v-flex xs12>
                            <v-autocomplete v-model="necesidad.problemas_salud" :items="SiNo"
                                label="Problema de Salud no Tratado">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12>
                            <v-autocomplete v-model="necesidad.medicamento_inecesario" :items="SiNo"
                                label="Efecto de Medicamento innecesario">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea v-model="necesidad.reaccion" label="¿Tipo de Problema?">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs12>
                            <v-autocomplete v-model="necesidad.prmevidenciado " :items="prm" label="PRM Evidenciado">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>
                    <v-card-text>
                        <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                            <span class="title layout justify-center font-weight-light">RECOMENDACION PRINCIPAL
                                REALIZADA</span>
                        </v-card-title>
                        <v-flex xs12>
                            <v-autocomplete v-model="necesidad.intervencion_principal" :items="intervencionPrincipal">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>
                    <v-card-text>
                        <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                            <span class="title layout justify-center font-weight-light">INTERVENCION DIRIGIDA</span>
                        </v-card-title>
                        <v-flex xs12>
                            <v-autocomplete v-model="necesidad.intervencion_dirigida" :items="allUsers"
                                item-text="nombre" item-value="id">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success" round @click="saveNecesidad()">
                            Guardar
                        </v-btn>
                        <v-btn color="primary" round @click="dialogabrirNecesidad = false">
                            Cerrar
                        </v-btn>
                    </v-card-actions>
                    <v-data-table :headers="headersNecesidad" :items="itemsnecesidad" class="elevation-1">
                        <template v-slot:items="props">
                            <td>{{ props.item.id }}</td>
                            <td class="text-xs-center">{{ props.item.Orden_id }}</td>
                            <td class="text-xs-center">{{ props.item.problemas_salud }}</td>
                            <td class="text-xs-center">{{ props.item.medicamento_inecesario }}</td>
                            <td class="text-xs-center">{{ props.item.reaccion }}</td>
                            <td class="text-xs-center">{{ props.item.prmevidenciado }}</td>
                            <td class="text-xs-center">{{ props.item.intervencion_principal }}</td>
                            <td class="text-xs-center">{{ props.item.intervencion_dirigida}}</td>
                            <td class="text-xs-center">{{ props.item.Medico}}</td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialogabrirOtros" persistent width="1300">
                <v-card>
                    <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                        <span class="title layout justify-center font-weight-light">OTROS PROBLEMAS</span>
                    </v-card-title>

                    <v-card-text>
                        <v-flex xs12>
                            <v-autocomplete v-model="otros.prmevidenciado " :items="prm" label="PRM Evidenciado">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea v-model="otros.reaccion" label="Descripcion del problema">
                            </v-textarea>
                        </v-flex>

                    </v-card-text>
                    <v-card-text>
                        <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                            <span class="title layout justify-center font-weight-light">INTERVENCION PRINCIPAL
                                REALIZADA</span>
                        </v-card-title>
                        <v-flex xs12>
                            <v-autocomplete v-model="otros.intervencion_principal" :items="intervencionPrincipal">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>
                    <v-card-text>
                        <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                            <span class="title layout justify-center font-weight-light">INTERVENCION DIRIGIDA</span>
                        </v-card-title>
                        <v-flex xs12>
                            <v-autocomplete v-model="otros.intervencion_dirigida" :items="allUsers" item-text="nombre"
                                item-value="id">
                            </v-autocomplete>
                        </v-flex>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success" round @click="saveOtro()">
                            Guardar
                        </v-btn>
                        <v-btn color="primary" round @click="dialogabrirOtros = false">
                            Cerrar
                        </v-btn>
                    </v-card-actions>
                    <v-data-table :headers="headersOtros" :items="itemsotros" class="elevation-1">
                        <template v-slot:items="props">
                            <td>{{ props.item.id }}</td>
                            <td class="text-xs-center">{{ props.item.Orden_id }}</td>
                            <td class="text-xs-center">{{ props.item.reaccion }}</td>
                            <td class="text-xs-center">{{ props.item.prmevidenciado }}</td>
                            <td class="text-xs-center">{{ props.item.intervencion_principal }}</td>
                            <td class="text-xs-center">{{ props.item.intervencion_dirigida}}</td>
                            <td class="text-xs-center">{{ props.item.Medico}}</td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-dialog>
        </v-card>
        <v-dialog v-model="preload_datos" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-divider class="mx-9" vertical>
        </v-divider>
    </div>
</template>



<script>
    export default {
        name: "",
        data() {
            return {
                preload_datos: false,
                cedula_paciente: '',
                dialogSuspenciones: false,
                dialogNegaciones: false,
                dialogabrirAdherencia: false,
                dialogabrirSeguridad: false,
                dialogabrirEfectividad: false,
                dialogabrirNecesidad: false,
                dialogabrirOtros: false,
                SiNo: ['Si', 'No'],
                dosis: ['Ninguna', '1 a 2 veces', '3 a 5 veces', '6 a 10 veces', 'Mas de 10 veces'],
                porcentaje: ['95%-100%. NO OLVIDA DOSIS', '85%-94%. OLVIDA 1 A 2 DOSIS', '65%-84%. OLVIDA 3 A 5 DOSIS',
                    '64%-30%. OLVIDA 6 A 10 DOSIS', '< 30% OLVIDA MAS DE 10 DOSIS'
                ],
                causal: ['APARICION DE REACCION ADVERSA', 'NO COMPRENSION DE ESQUEMA DE TRATAMIENTO',
                    'NO DIMENSIONA RIESGOS DE OMITIR TRATAMIENTO', 'NO ENTREGA DE MEDICAMENTOS EN FARMACIA',
                    'NO GESTIONA O RECLAMA LOS MEDICAMENTOS', 'OLVIDOS EVENTUALES',
                    'OMITE DOSIS POR RETRASO EN LA TOMA', 'OMITE DOSIS POR VOLUNTAD PROPIA PARA DESCANSAR',
                    'PACIENTE SIN HORARIO DE TOMA DE MEDICAMENTOS DEFINIDO', 'PÉRDIDA CITA DE CONTROL',
                    'PRESENCIA DE COOMORBILIDADES', 'PROBLEMA CON AFILIACION EAPB',
                    'PROBLEMA CON AUTORIZACION DE ENTREGA DE MEDICAMENTOS'
                ],
                intervencionPrincipal: [
                    'EDUCACION A PACIENTE SOBRE ADHERENCIA AL TRATAMIENTO, FORMA DE USO Y ADMINISTRACION DEL MEDICAMENTO',
                    'VALORAR AJUSTE DE DOSIS', 'VALORAR INICIO DE TRATAMIENTO', 'VALORAR CAMBIO DE TRATAMIENTO',
                    'VALORAR RETIRO DE MEDICAMENTO', 'VALORAR RIESGO/BENEFICIO DE TRATAMIENTO',
                    'GENERACIÓN DE HORARIO DE TOMA DE MEDICAMENTOS',
                    'RETROALIMENTACION A PERSONAL DE ENFERMERIA SOBRE USO ADECUADO DE MEDICAMENTO',
                    'SOLICITUD DE PARACLÍNICOS DE CONTROL', 'VALORACION CLINICA DEL PROBLEMA DE SALUD NO TRATADO',
                    'VALORACION POR PSICOLOGIA', 'VALORACION POR TRABAJO SOCIAL'
                ],
                intervencionDirigida: ['Servicio Farmaceutico'],
                prm: ['ADMINISTRACION O USO ERRONEO DEL MEDICAMENTO', 'ALMACENAMIENTO INADECUADO', 'AUTOMEDICACION',
                    'CONDICIONES CLINICAS DEL PACIENTE', 'DOSIS, PAUTA Y/O DURACION NO ADECUADA', 'DUPLICIDAD',
                    'ERROR DE ADMINISTRACION', 'ERROR DE DISPENSACION', 'ERROR DE PRESCRIPCION',
                    'ERROR DE TRANSCRIPCION', 'PROBLEMA DE CALIDAD', 'FALLO TERAPEUTICO', 'FALTA DE ADHERENCIA',
                    'FALTA DE PARACLINICOS DE CONTROL',
                    'INTERACCION', 'MEDICAMENTO CONTRAINDICADO', 'NO CUMPLE INDICACION INVIMA O UNIRS',
                    'PROBLEMAS DE SALUD INSUFICIENTEMENTE TRATADO',
                    'REACCION ADVERSA'
                ],
                motivo: [],
                adherencia: {},
                itemsadherencia: [],
                itemseguridad: [],
                itemsefectividad: [],
                itemsnecesidad: [],
                itemsotros: [],
                seguridad: {},
                necesidad: {},
                efectividad: {},
                adherenciaFarmaco: {
                    criterio_quimico: '',
                    dejado_medicamento: '',
                    dias_notomomedicamento: '',
                    finsemana_notomomedicamento: '',
                    finsemana_olvidomedicamento: '',
                    hora_indicada: '',
                    olvido_medicamento: '',
                    porcentaje: '',
                },
                otros: {},
                search: '',
                selected: [],
                historicoOrdens: [],
                historicoMedicamentos: [],
                headersHistoricoMedicamentos: [{
                        text: '# Orden',
                        align: "center",
                        value: 'orden',
                        sortable: true,
                    },
                    {
                        text: 'Estado',
                        align: "center",
                        value: 'Estado',
                        sortable: true,
                    },
                    {
                        text: 'Mes',
                        align: "center",
                        value: 'Fechaorden',
                        sortable: true,
                    },
                    {
                        text: 'Dosis',
                        align: "center",
                    },
                    {
                        text: 'Via',
                        align: "center",
                    },
                    {
                        text: 'Unidad Medida',
                        align: "center",
                    },
                    {
                        text: 'Frecuencia',
                        align: "center",
                    },
                    {
                        text: 'Cantidad Ordenada',
                        align: "center",
                    },
                    {
                        text: 'Duracion',
                        align: "center",
                    },
                    {
                        text: 'Observacion',
                        align: "center",
                    },
                    {
                        text: 'Imprimir Historia',
                        align: "center",
                    },
                    {
                        text: 'Adherente',
                        align: "center",
                    },
                    {
                        text: 'Seguro',
                        align: "center",
                    },
                    {
                        text: 'Efectivo',
                        align: "center",
                    },
                    {
                        text: 'Necesario',
                        align: "center",
                    },
                    {
                        text: 'Otro Problemas',
                        align: "center",
                    }
                ],

                headersAdherencia: [{
                        text: 'ID',
                    },
                    {
                        text: 'Orden Afectada',
                    },
                    {
                        text: 'Adherente',
                    },
                    {
                        text: 'Causal de no Adherencia',
                    },
                    {
                        text: 'Recomendacion Principal',
                    },
                    {
                        text: 'Intervencion Dirigida',
                    },
                    {
                        text: 'Usuario Registra',
                    }
                ],
                headersSeguridad: [{
                        text: 'ID',
                    },
                    {
                        text: 'Orden Afectada',
                    },
                    {
                        text: 'Reaccion Adversa',
                    },
                    {
                        text: 'Tipo',
                    },
                    {
                        text: 'PRM',
                    },
                    {
                        text: 'Recomendacion Principal',
                    },
                    {
                        text: 'Intervencion Dirigida',
                    },
                    {
                        text: 'Usuario Registra',
                    }
                ],
                headersEfectividad: [{
                        text: 'ID',
                    },
                    {
                        text: 'Orden Afectada',
                    },
                    {
                        text: 'RPM',
                    },
                    {
                        text: 'Resultado Clinico',
                    },
                    {
                        text: 'Recomendacion Principal',
                    },
                    {
                        text: 'Intervencion Dirigida',
                    },
                    {
                        text: 'Usuario Registra',
                    }
                ],
                headersNecesidad: [{
                        text: 'ID',
                    },
                    {
                        text: 'Orden Afectada',
                    },
                    {
                        text: 'Problemas de salud no tratado',
                    },
                    {
                        text: 'Medicamento Inecesario',
                    },
                    {
                        text: 'Tipo',
                    },
                    {
                        text: 'PRM',
                    },
                    {
                        text: 'Recomendacion Principal',
                    },
                    {
                        text: 'Intervencion Dirigida',
                    },
                    {
                        text: 'Usuario Registra',
                    }
                ],
                headersOtros: [{
                        text: 'ID',
                    },
                    {
                        text: 'Orden Afectada',
                    },
                    {
                        text: 'Reaccion',
                    },
                    {
                        text: 'PRM',
                    },
                    {
                        text: 'Recomendacion Principal',
                    },
                    {
                        text: 'Intervencion Dirigida',
                    },
                    {
                        text: 'Usuario Registra',
                    }
                ],
                allUsers: []
            }
        },

        methods: {
            fechtMedicamentos() {
                if (!this.cedula_paciente) {
                    swal({
                        title: "Debe ingresar un cédula",
                        icon: "warning"
                    });
                    return;
                }
                this.preload_datos = true
                axios.get('/api/historia/FechthistoricoMedicamentos/' + this.cedula_paciente).then((res) => {
                    this.historicoMedicamentos = res.data;
                    this.preload_datos = false
                });
            },
            abrirModalNegaciones(items) {
                this.dialogNegaciones = true
                axios.get('/api/historia/motivoNegacion/' + items).then((res) => {
                    this.motivo = res.data;
                });
            },

            abrirModalSuspenciones(items) {

                this.dialogSuspenciones = true
                axios.get('/api/historia/motivoSuspencion/' + items).then((res) => {
                    this.motivo = res.data;
                });
            },

            abrirAdherencia(items) {
                this.dialogabrirAdherencia = true
                this.adherencia.medico_id = items.UserId
                this.adherencia.Citapaciente_id = items.cita_paciente
                this.adherencia.paciente_id = items.Paciente_id
                this.adherencia.detaarticulordens_id = items.detaarticulordens_id
                this.adherencia.codesumi_id = items.id
            },

            abrirSeguridad(items) {
                this.dialogabrirSeguridad = true
                this.seguridad.medico_id = items.UserId
                this.seguridad.Citapaciente_id = items.cita_paciente
                this.seguridad.paciente_id = items.Paciente_id
                this.seguridad.detaarticulordens_id = items.detaarticulordens_id
                this.seguridad.codesumi_id = items.id
            },

            abrirEfectividad(items) {
                this.dialogabrirEfectividad = true
                this.efectividad.medico_id = items.UserId
                this.efectividad.Citapaciente_id = items.cita_paciente
                this.efectividad.paciente_id = items.Paciente_id
                this.efectividad.detaarticulordens_id = items.detaarticulordens_id
                this.efectividad.codesumi_id = items.id
            },
            abrirNecesidad(items) {
                this.dialogabrirNecesidad = true
                this.necesidad.medico_id = items.UserId
                this.necesidad.Citapaciente_id = items.cita_paciente
                this.necesidad.paciente_id = items.Paciente_id
                this.necesidad.detaarticulordens_id = items.detaarticulordens_id
                this.necesidad.codesumi_id = items.id
            },

            abrirOtros(items) {
                this.dialogabrirOtros = true
                this.otros.medico_id = items.UserId
                this.otros.Citapaciente_id = items.cita_paciente
                this.otros.paciente_id = items.Paciente_id
                this.otros.detaarticulordens_id = items.detaarticulordens_id
                this.otros.codesumi_id = items.id
            },

            saveAdherencia() {
                this.preload_datos = true
                axios.post('/api/historia/saveAdherenciaFarmacologicaNoHistoria', this.adherencia).then((res) => {
                    this.$alertHistoria(res.data.message);
                    this.preload_datos = false
                    this.fetchAdherencia();
                    this.clearAdhrencia();
                });
            },

            fetchAdherencia() {
                this.preload_datos = true
                axios.post('/api/historia/fetchAdherencia', this.adherencia).then((
                    res) => {
                    this.itemsadherencia = res.data;
                    this.preload_datos = false
                });
            },

            clear() {
                this.adherencia = {},
                    this.seguridad = {},
                    this.efectividad = {},
                    this.necesidad = {},
                    this.otros = {}
            },

            saveSeguridad() {
                this.preload_datos = true
                axios.post('/api/historia/saveSeguridad', this.seguridad).then((res) => {
                    this.$alertHistoria(res.data.message);
                    this.preload_datos = false
                    this.fetchSeguridad();
                    this.clear();
                });
            },

            fetchSeguridad() {
                this.preload_datos = true
                axios.post('/api/historia/fetchSeguridad', this.seguridad).then((
                    res) => {
                    this.itemseguridad = res.data;
                    this.preload_datos = false
                });
            },

            saveEfectividad() {
                this.preload_datos = true
                axios.post('/api/historia/saveEfectividad', this.efectividad).then((res) => {
                    this.$alertHistoria(res.data.message);
                    this.preload_datos = false
                    this.fetchEfectividad();
                    this.clear();
                });
            },

            fetchEfectividad() {
                this.preload_datos = true
                axios.post('/api/historia/fetchEfectividad', this.efectividad).then((
                    res) => {
                    this.itemsefectividad = res.data;
                    this.preload_datos = false
                });
            },

            saveNecesidad() {
                this.preload_datos = true
                axios.post('/api/historia/saveNecesidad', this.necesidad).then((res) => {
                    this.$alertHistoria(res.data.message);
                    this.preload_datos = false
                    this.fetchNecesidad();
                    this.clear();
                });
            },

            fetchNecesidad() {
                this.preload_datos = true
                axios.post('/api/historia/fetchNecesidad', this.necesidad).then((
                    res) => {
                    this.itemsnecesidad = res.data;
                    this.preload_datos = false
                });
            },

            saveOtro() {
                this.preload_datos = true
                axios.post('/api/historia/saveOtro', this.otros).then((res) => {
                    this.$alertHistoria(res.data.message);
                    this.preload_datos = false
                    this.fetchOtros();
                    this.clear();
                });
            },

            fetchOtros() {
                this.preload_datos = true
                axios.post('/api/historia/fetchOtros', this.otros).then((
                    res) => {
                    this.itemsotros = res.data;
                    this.preload_datos = false
                });
            },
            clearFields() {
                this.cedula_paciente = '',
                    this.historicoOrdens = [],
                    this.historicoMedicamentos = []
            },

            historicoOrdenes(items) {
                this.preload_datos = true
                axios.post('/api/historia/historicoOrdenes', items).then((res) => {
                    this.historicoOrdens = res.data;
                    this.preload_datos = false
                });
            },

            fetchUser() {
                this.preload_datos = true
                axios.post('/api/historia/fetchUser').then((
                    res) => {
                    this.allUsers = res.data;
                    this.preload_datos = false
                });
            },

            printhc() {
                if (this.history) {
                    let pdf = [];
                    pdf = this.dataHistoria;
                    pdf.type = "historia";
                    axios.post("/api/pdf/print-pdf", pdf, {
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
                }
            },

            imprimirhc(items) {
                let pdf = [];
                pdf = items;
                pdf.cita_paciente_id = items.cita_paciente;
                pdf.type = "historiaintegral";
                axios.post("/api/historia/imprimirhc", pdf, {
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


        }
    }

</script>
