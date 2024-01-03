<template>
    <v-container style="max-width: 12000px;">
        <v-flex xs12>
            <v-card>
                <v-container grid-list-xs>
                    <v-layout row wrap justify-space-between> <span>
                            <b>Nombre completo paciente:</b>
                            <span style="color: red;"><b>{{datosCita.nombre_paciente}}</b></span> </span> <span>
                            <b>Tipo y número de documento:</b>
                            <span style="color: red;"><b>{{paciente.Tipo_Doc + " " + paciente.Num_Doc}}</b></span>
                        </span> <span>
                            <b>Edad:</b>
                            <span style="color: red;"><b>{{paciente.Edad_Cumplida}}</b></span> </span> <span>
                            <b>Entidad:</b>
                            <span style="color: red;"><b>{{paciente.Ut}}</b></span> </span> <span>
                            <b>Régimen:</b>
                            <span style="color: red;" v-if="paciente.Ut == 'REDVITAL UT'">
                                <b>Magisterio</b>
                            </span> <span style="color: red;" v-if="paciente.Ut == 'MEDIMAS'">
                                <b>{{paciente.tipo_categoria}}</b>
                            </span> </span> <span v-show="paciente.Ut == 'MEDIMAS'">
                            <b>Nivel:</b>
                            <span style="color: red;"><b>{{paciente.nivel}}</b></span> </span> <span>
                            <b>Tipo:</b>
                            <span style="color: red;"><b>{{paciente.Tipo_Afiliado}}</b></span> </span>
                    </v-layout>
                </v-container>
            </v-card>
        </v-flex>
        <v-card>
            <v-container grid-list-xs>
                <v-layout row wrap>
                    <!--Tipo ordenamiento-->
                    <v-form>
                        <v-flex xs12>
                            <VAutocomplete label="Tipo ordenamiento" :items="tipoOrdenamiento" item-value="id"
                                item-text="Nombre" v-model="data.Tiporden_id" @input="tipoOrdenSelected()" />
                        </v-flex>
                    </v-form>
                    <template
                        v-if="data.Tiporden_id == 1 && paciente.tipo_categoria != 'SUBSIDIADO' && paciente.Tipo_Afiliado != 'BENEFICIARIO'">
                        <v-flex xs12>
                            <v-card>
                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout row wrap>
                                            <v-flex xs3>
                                                <VSelect v-model="
                                                        incapacidad.Contingencia
                                                    " :items="[
                                                        'Enf. Comun ',
                                                        'licencia maternidad',
                                                        'Accidente de trabajo',
                                                        'Enf. Profesional',
                                                        'Licencia Paternidad',
                                                        'Accidente (Soat)'
                                                    ]" label="Contingencia" required />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-menu v-if="
                                                        !path.includes(
                                                            'historiaclinica'
                                                        )
                                                    " ref="show_start_date" :close-on-content-click="
                                                        false
                                                    " v-model="show_start_date" :nudge-right="40" :return-value.sync="
                                                        incapacidad.FechaInicio
                                                    " lazy transition="scale-transition" offset-y full-width
                                                    min-width="290px" max-width="290px">
                                                    <template v-slot:activator="{
                                                            on
                                                        }">
                                                        <VTextField v-model="
                                                                incapacidad.FechaInicio
                                                            " label="Fecha Inicial" prepend-icon="event" readonly
                                                            v-on="on" /> </template>
                                                    <v-date-picker color="primary" locale="es" v-model="
                                                            incapacidad.FechaInicio
                                                        " full-width @click="
                                                            $refs.show_start_date.save(
                                                                incapacidad.FechaInicio
                                                            )
                                                        ">
                                                        <VSpacer />
                                                        <v-btn flat color="primary" @click="
                                                                show_start_date = false
                                                            "> Cancelar </v-btn>
                                                        <v-btn flat color="primary" @click="
                                                                $refs.show_start_date.save(
                                                                    incapacidad.FechaInicio
                                                                )
                                                            "> OK </v-btn>
                                                    </v-date-picker>
                                                </v-menu>
                                                <VTextField v-else label="Fecha Inicio" readonly :type="'label'"
                                                    v-model="
                                                        incapacidad.FechaInicio
                                                    " required />
                                            </v-flex>
                                            <v-flex xs1>
                                                <VTextField :type="'number'" label="Cantidad días"
                                                    @input="getFinalDate()" v-model="
                                                        incapacidad.CantidadDias
                                                    " required />
                                            </v-flex>
                                            <v-flex xs3>
                                                <VTextField label="Fecha Final" readonly :type="'label'" v-model="
                                                        incapacidad.FechaFinal
                                                    " required />
                                            </v-flex>
                                            <v-flex xs2>
                                                <VSelect v-model="
                                                        incapacidad.Prorroga
                                                    " :items="['Sí', 'No']" label="Prorrogo" required />
                                            </v-flex>
                                        </v-layout>
                                        <v-layout row wrap>
                                            <v-flex xs12 lg12 v-if="paciente.Ut === 'REDVITAL UT'">
                                                <VAutocomplete label="Labora en:" :items="colegios"
                                                    item-value="NomColegio" item-text="NomColegio" v-model="
                                                        incapacidad.Colegio
                                                    " :loading="loading" :search-input.sync="search" />
                                            </v-flex>
                                            <v-flex xs12 lg12>
                                                <VTextarea name="input-7-1" label="Descripción de incapacidad" value
                                                    v-model="
                                                        incapacidad.Descripcion
                                                    " required />
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn color="primary" round @click="generateInc()">Generar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                    </template>
                    <template v-if="data.Tiporden_id == 8">
                        <v-flex xs12>
                            <v-card>
                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout row wrap>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Esfera</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.esfera_od" label="OD" />
                                                <VTextField v-model="
                                                        optometria.esfera_oi" label="OI" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Cilindro</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.cilindro_od" label="OD" />
                                                <VTextField v-model="
                                                        optometria.cilindro_oi" label="OI" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Eje</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.eje_od" label="OD" />
                                                <VTextField v-model="
                                                        optometria.eje_oi" label="OI" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Adicion</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.adicion_od" label="OD" />
                                                <VTextField v-model="
                                                        optometria.adicion_oi" label="OI" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Prisma Base</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.prisma_od" label="OD" />
                                                <VTextField v-model="
                                                        optometria.prisma_oi" label="OI" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Grados</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.grados_od" label="OD" />
                                                <VTextField v-model="
                                                        optometria.grados_oi" label="OI" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Av Lejos</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.lejos_od" label="OD" />
                                                <VTextField v-model="
                                                        optometria.lejos_oi" label="OI" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Av Cerca</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.cerca_od" label="OD" />
                                                <VTextField v-model="
                                                        optometria.cerca_oi" label="OI" />
                                            </v-flex>
                                        </v-layout>
                                        <v-layout row wrap>
                                            <v-flex xs2>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Tipo Lentes</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.tipo_lente" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Detalle</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.detalle" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Altura</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.altura" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Color y Ttos</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.color" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Dp</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.dp" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>uso Dispositivos</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.dispositivos" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Control</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.control" />
                                            </v-flex>
                                            <v-flex xs3>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Duracion Vigencia</span>
                                                </v-card-title>
                                                <VTextField v-model="
                                                        optometria.vigencia" />
                                            </v-flex>
                                        </v-layout>
                                        <v-layout row wrap>
                                            <v-flex xs12>
                                                <v-card-title class="headline" style="color:black">
                                                    <span>Observacion</span>
                                                </v-card-title>
                                                <VTextarea v-model="
                                                        optometria.observacion" />
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn color="primary" round @click="generateInc()">Generar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                    </template>
                    <template v-if="data.Tiporden_id == 3">
                        <v-flex xs12>
                            <!--tipo orden 3-->
                            <v-flex xs12 sm12 md12>
                                <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                                    <span class="title layout justify-center font-weight-light">Historico de
                                        Medicamentos</span>
                                </v-card-title>
                            </v-flex>
                            <v-data-table :headers="headersHistoricoMedicamentos" :items="historicoMedicamentos"
                                class="elevation-1" item-key="id">
                                <template v-slot:items="props">
                                    <td>{{ props.item.orden }}</td>
                                    <td class="text-xs-center"
                                        v-if="props.item.Estado_id == 1 || props.item.Estado_id == 7 ">
                                        <v-chip color="info" text-color="white">{{ props.item.Estado }}</v-chip>
                                    </td>
                                    <td class="text-xs-center"
                                        v-if="props.item.Estado_id == 17 || props.item.Estado_id == 24 || props.item.Estado_id == 25 || props.item.Estado_id == 26 ">
                                        <v-chip @click="abrirModalNegaciones(props.item.detaarticulordens_id)"
                                            color="red" text-color="white">{{ props.item.Estado }}</v-chip>
                                    </td>
                                    <td class="text-xs-center" v-if="props.item.Estado_id == 50">
                                        <v-chip @click="abrirModalSuspenciones(props.item.detaarticulordens_id)"
                                            color="warning" text-color="white">{{ props.item.Estado }}</v-chip>
                                    </td>
                                    <td class="text-xs-center"
                                        v-if="props.item.Estado_id == 18 | props.item.Estado_id == 19">
                                        <v-chip color="success" text-color="white">{{ props.item.Estado }}
                                        </v-chip>
                                    </td>
                                    <td class="text-xs-left">{{ props.item.Medicamento }}</td>
                                    <td class="text-xs-right">{{ props.item.paginacion }}</td>
                                    <td class="text-xs-right">{{ props.item.Cantidadosis }}</td>
                                    <td class="text-xs-right">{{ props.item.Via }}</td>
                                    <td class="text-xs-right">{{ props.item.Unidadmedida }}</td>
                                    <td class="text-xs-right">{{ props.item.Frecuencia }}</td>
                                    <td class="text-xs-right">{{ props.item.Cantidadpormedico }}</td>
                                    <td class="text-xs-right">{{ props.item.Duracion }}</td>
                                    <td class="text-xs-right">{{ props.item.Observacion }}</td>
                                    <td v-show="props.item.Estado_id == 1 || props.item.Estado_id == 7 ">
                                        <v-btn fab small color="warning" @click="abrirModalSuspender(props.item)">
                                            <v-icon>mdi-message-alert</v-icon>
                                        </v-btn>
                                    </td>
                                    <td
                                        v-show="props.item.Estado_id == 17 || props.item.Estado_id == 24 || props.item.Estado_id == 25 || props.item.Estado_id == 26 || props.item.Estado_id == 50">
                                        <v-btn fab small color="warning" disabled>
                                            <v-icon>mdi-message-alert</v-icon>
                                        </v-btn>
                                    </td>
                                    <td>
                                        <v-btn fab small color="info" @click="conciliarMedicamento()">
                                            <v-icon>assignment_turned_in</v-icon>
                                        </v-btn>
                                    </td>
                                </template>
                            </v-data-table>
                        </v-flex>
                        <v-flex 22>
                            <v-layout row wrap>
                                <v-flex xs9>
                                    <v-autocomplete label="Medicamento" :items="filteredMedicamentos"
                                                    item-text="Nombre" v-model="articulos.medicamento" return-object @change="validar()">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="articulos.Cantidadosis" min="1"
                                                  onkeypress="return event.charCode >= 48" oncopy="return false"
                                                  onpaste="return false" label="Cantidad"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-autocomplete label="Unidad presentación" :disabled="articulos.medicamento.insulina === '1'"
                                                    :items="optionsUnidadPresentacion" v-model="articulos.Unidadmedida">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs2>
                                    <v-autocomplete :items="optionsVia" v-model="articulos.Via" label="Via">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs1>
                                    <v-text-field v-model="articulos.Frecuencia" label="Frecuencia">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-select v-model="articulos.Unidadtiempo" :items="['Horas', 'Días']"
                                              label="Unidad tiempo"></v-select>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="articulos.Duracion"
                                                  label="Duración (dias al mes)">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-select v-model="articulos.NumMeses" label="Número meses" :items="meses"
                                        item-text="accion" item-value="value" hide-details></v-select>
                                </v-flex>
                                <v-flex xs1>
                                    <v-text-field type="number" min="1"
                                                  onkeypress="return event.charCode >= 48" oncopy="return false"
                                                  onpaste="return false" v-model="cantidadMensual"
                                                  :label="articulos.medicamento.insulina === '1'?'Cantidad mensual (UI)':'Cantidad mensual'" readonly></v-text-field>
                                </v-flex>

                                <v-flex xs2 v-if="articulos.medicamento.insulina === '1'">
                                    <v-text-field min="1"
                                                  onkeypress="return event.charCode >= 48" oncopy="return false"
                                                  onpaste="return false" :value="Math.ceil(parseInt((cantidadMensual))/parseInt(articulos.medicamento.contenido)*parseInt(articulos.NumMeses))"
                                                  label="Presentaciones totales" readonly></v-text-field>
                                </v-flex>
                                <v-flex xs2 v-if="articulos.medicamento.insulina === '1'">
                                    <v-text-field type="number" min="1"
                                                  onkeypress="return event.charCode >= 48" oncopy="return false" readonly
                                                  onpaste="return false" :value="articulos.Cantidadpormedico = Math.ceil(parseInt((cantidadMensual))/parseInt(articulos.medicamento.contenido))"
                                                  label="Cantidad mensual médico"></v-text-field>
                                </v-flex>

                                <v-flex xs2 v-else>
                                    <v-text-field type="number" min="1" onkeypress="return event.charCode >= 48"
                                        oncopy="return false" onpaste="return false"
                                        v-model="articulos.Cantidadpormedico" label="Cantidad mensual médico">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="articulos.Observacion" label="Observación"></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-btn color="success" round @click="addArticulo()">Agregar</v-btn>
                            <v-btn color="warning" round @click="clearDataArticulo()">Borrar campos</v-btn>
                        </v-flex>
                    </template>
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
                                <v-btn color="primary" flat @click="dialogNegaciones = false">
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
                    <v-dialog v-model="dialogSuspender" persistent width="500">
                        <v-card>
                            <v-card-title class="headline" style="color: white; background-color: #0074a6;">
                                <span class="title layout justify-center font-weight-light">SUSPENDER MEDICAMENTO</span>
                            </v-card-title>

                            <v-card-text>
                                <v-textarea name="input-7-1" label="Motivo de Suspencion" v-model="orden.suspencion">
                                </v-textarea>
                            </v-card-text>

                            <v-divider></v-divider>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" flat @click="dialogSuspender = false">
                                    Cerrar
                                </v-btn>
                                <v-btn color="primary" flat @click="suspenderMedicamento()">
                                    Suspender
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <template v-if="data.Tiporden_id == 2 || data.Tiporden_id == 4">
                        <v-flex xs12>
                            <v-layout row wrap>
                                <v-flex xs11>
                                    <v-autocomplete label="Procedimiento" :items="filteredProcedimientos"
                                        item-text="full_name" v-model="procedimiento.cup" return-object>
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs1>
                                    <v-text-field label="cantidad" v-model="procedimiento.cantidad"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-menu v-model="menu1" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <v-text-field v-model="procedimiento.date" label="Vigente desde"
                                                prepend-icon="event" readonly persistent-hint hint="formato YYYY-MM-DD"
                                                v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker v-model="procedimiento.date" locale="es" color="primary"
                                            @input="menu1 = false" :min="startDate"></v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="procedimiento.observacion" label="Observación">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-btn color="success" round @click="addProcedimiento()">agregar</v-btn>
                        </v-flex>
                    </template>
                    <template v-if="data.Tiporden_id == 6">
                        <v-flex xs12>
                            <v-layout row wrap>
                                <v-flex xs11>
                                    <VAutocomplete label="Servicios Propios" :items="filteredServiciosPropios"
                                        item-text="full_name" v-model="servicioPropio.service" return-object />
                                </v-flex>
                                <v-flex xs1>
                                    <VTextField label="Cantidad" v-model="servicioPropio.cantidad" />
                                </v-flex>
                                <v-flex xs4>
                                    <VTextField v-model="servicioPropio.observacion" label="Observación" />
                                </v-flex>
                            </v-layout>
                            <v-btn color="success" round @click="addservicioPropio()">Agregar</v-btn>
                        </v-flex>
                    </template>
                    <template v-if="data.Tiporden_id == 7 && articulosOrdered.length === 0">
                        <v-flex xs12>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <VAutocomplete label="Esquemas" :items="esquemas" item-text="fullName" return-object
                                        v-model="esquema" @change="fetchCodeSumiEsquema" />
                                </v-flex>
                                <v-flex xs1>
                                    <v-text-field name="name" label="Ciclo inicial" v-model="esquema.cycleFrom" />
                                </v-flex>
                                <v-flex xs1>
                                    <VTextField label="Total ciclos" v-model="esquema.ciclos" />
                                </v-flex>
                                <v-flex xs4>
                                    <VTextField label="frecuencia" v-model="esquema.frecuenciaRepeat" />
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </template>
                </v-layout>
                <v-layout row wrap v-show="data.Tiporden_id != 1">
                    <v-flex xs12>
                        <!--tipo orden 3-->
                        <v-flex xs12 sm12 md12 v-show="data.Tiporden_id == 3">
                            <v-card-title class="headline" style="color: white; background-color: #0074a6;"> <span
                                    class="title layout justify-center font-weight-light">Pre Ordenamiento</span>
                            </v-card-title>
                        </v-flex>
                        <v-data-table v-show="data.Tiporden_id == 3" :headers="headers" :items="data.articulos"
                            hide-actions class="elevation-1" pagination.sync="pagination" item-key="id" loading="true">
                            <template v-slot:items="props">
                                <td> {{ props.item.nombre }} </td>
                                <td> {{ props.item | PosViaAdmin }} </td>
                                <!--{{ props.item.Cantidadosis }} {{ (props.item.Unidadtiempo == 'Horas')? `${props.item.Unidadmedida} cada ${props.item.Frecuencia} Horas ${props.item.Duracion} dias por ${props.item.NumMeses} meses` : 'Dosis Única' }}-->
                                <td> {{ props.item.Cantidadpormedico }} </td>
                                <td> {{ props.item.Observacion }} </td>
                                <td> {{ props.item.Requiere_autorizacion }} </td>
                                <td>
                                    <v-tooltip bottom v-if="props.item.Requiere_autorizacion == 'SI'">
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on">
                                                <v-icon color="warning">mdi-message-alert</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Aprobación Familiarista</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-btn icon v-on="on" @click="removeArticulo(props)">
                                                <v-icon color="red">mdi-delete-forever</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Eliminar Medicamento</span>
                                    </v-tooltip>
                                </td>
                            </template>
                        </v-data-table>
                    </v-flex>
                    <v-flex xs12>
                        <!--tipo orden 6-->
                        <v-flex xs12 sm12 md12 v-show="data.Tiporden_id == 6">
                            <v-card-title class="headline" style="color: white; background-color: #0074a6;"> <span
                                    class="title layout justify-center font-weight-light">Pre Ordenamiento</span>
                            </v-card-title>
                        </v-flex>
                        <v-data-table v-show="data.Tiporden_id == 6" :headers="headersProcedimientos"
                            :items="data.serviciosPropios" hide-actions class="elevation-1" pagination.sync="pagination"
                            item-key="id" loading="true">
                            <template v-slot:items="props">
                                <td class="text-xs-center"> {{ props.item.codigo }} </td>
                                <td class="text-xs-center"> {{ props.item.nombre }} </td>
                                <td class="text-xs-center"> {{ props.item.auditoria }} </td>
                                <td class="text-xs-center"> {{ props.item.cantidad }} </td>
                                <td class="text-xs-center"> {{ props.item.observacion }} </td>
                                <td class="text-xs-center">
                                    <v-btn color="error" @click="removeServicioPropio(props)" icon>
                                        <v-icon>mdi-delete-forever</v-icon>
                                    </v-btn>
                                </td>
                            </template>
                        </v-data-table>
                        <!--tipo orden 2 o 4-->
                        <v-flex xs12 sm12 md12 v-show="data.Tiporden_id == 2 || data.Tiporden_id == 4">
                            <v-card-title class="headline" style="color: white; background-color: #0074a6;"> <span
                                    class="title layout justify-center font-weight-light">Pre Ordenamiento</span>
                            </v-card-title>
                        </v-flex>
                        <v-data-table v-show="data.Tiporden_id == 2 || data.Tiporden_id == 4"
                            :headers="headersProcedimientos" :items="data.procedimientos" hide-actions
                            class="elevation-1" pagination.sync="pagination" item-key="id" loading="true">
                            <template v-slot:items="props">
                                <td class="text-xs-center"> {{ props.item.codigo }} </td>
                                <td class="text-xs-center"> {{ props.item.nombre }} </td>
                                <td class="text-xs-center"> {{ props.item.auditoria }} </td>
                                <td class="text-xs-center"> {{ props.item.cantidad }} </td>
                                <td class="text-xs-center"> {{ props.item.observacion }} </td>
                                <td class="text-xs-center">
                                    <v-btn small color="error" @click="removeProcedimiento(props)" icon>
                                        <v-icon>mdi-delete-forever</v-icon>
                                    </v-btn>
                                </td>
                            </template>
                        </v-data-table>
                        <!-- tipo orden 7 -->
                        <v-flex xs12 sm12 md12 v-show="data.Tiporden_id == 7">
                            <v-card-title class="headline" style="color: white; background-color: #0074a6;"> <span
                                    class="title layout justify-center font-weight-light">Pre Ordenamiento</span>
                            </v-card-title>
                        </v-flex>
                        <v-data-table v-model="data.codeSumiEsquemaSelected" v-show="data.Tiporden_id == 7"
                            :headers="headersEsquemasTable" :items="mapDetailScheme" hide-actions class="elevation-1"
                            pagination.sync="pagination" item-key="id" select-all loading="true">
                            <template v-slot:headers="props">
                                <tr>
                                    <th>
                                        <VCheckbox color="primary" :input-value="props.all"
                                            :indeterminate="props.indeterminate" hide-details @click.stop="toggleAll" />
                                    </th>
                                    <th v-for="header in props.headers" :key="header.text"> {{ header.text }} </th>
                                </tr>
                            </template>
                            <template v-slot:items="props">
                                <tr :active="props.selected">
                                    <td @click="
                                            props.selected = !props.selected
                                        ">
                                        <VCheckbox color="primary" :input-value="props.selected" primary hide-details />
                                    </td>
                                    <td class="text-xs-center"> {{ props.item.Codigo }} - {{ props.item.Nombre }} </td>
                                    <td class="text-xs-center">
                                        <v-edit-dialog :return-value.sync="props.item.dosis"
                                            @save="dosisFormuladaCalculate(props.item)" lazy>
                                            <v-icon color="warning" small> edit </v-icon> {{ props.item.dosis }}
                                            {{ props.item.unidadMedida }}
                                            <template v-slot:input>
                                                <VTextField single-line v-model="props.item.dosis" /> </template>
                                        </v-edit-dialog>
                                    </td>
                                    <td class="text-xs-center"> {{ props.item.indiceDosis }} {{ bodySurfaceIndex }}
                                    </td>
                                    <td class="text-xs-center">
                                        <v-edit-dialog :return-value.sync="
                                                props.item.dosisFormulada
                                            " lazy>
                                            <v-icon color="warning" small> edit </v-icon>
                                            {{ props.item.dosisFormulada }} {{ props.item.unidadMedida }}
                                            <template v-slot:input>
                                                <VTextField single-line v-model="props.item.dosisFormulada" />
                                            </template>
                                        </v-edit-dialog>
                                    </td>
                                    <td class="text-xs-center"> {{ props.item.via }} </td>
                                    <td class="text-xs-center">
                                        <v-edit-dialog :return-value.sync="
                                                props.item.cantidadAplicaciones
                                            " lazy>
                                            <v-icon color="warning" small> edit </v-icon>
                                            {{ props.item.cantidadAplicaciones }}
                                            <template v-slot:input>
                                                <VTextField single-line v-model="props.item.cantidadAplicaciones" />
                                            </template>
                                        </v-edit-dialog>
                                    </td>
                                    <td class="text-xs-center"> {{ props.item.frecuencia }} </td>
                                    <td class="text-xs-center">
                                        <v-edit-dialog :return-value.sync="
                                                props.item.observaciones
                                            " lazy>
                                            <v-icon color="warning" small> edit </v-icon> {{ props.item.observaciones }}
                                            <template v-slot:input>
                                                <VTextField single-line v-model="
                                                        props.item.observaciones
                                                    " /> </template>
                                        </v-edit-dialog>
                                    </td>
                                    <td class="text-xs-center"> {{ props.item.frecuenciaDuracion }} </td>
                                </tr>
                            </template>
                        </v-data-table>
                    </v-flex>
                    <v-layout row wrap>
                        <VSpacer />
                        <v-btn color="success" dark :loading="ordering" @click="generateOrder()"> Ordenar </v-btn>
                    </v-layout>
                </v-layout>
            </v-container>
        </v-card>
        <v-flex pt-2>
            <!-- ORDENADO -->
            <v-card>
                <v-container grid-list-xs>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <!--tipo orden 1-->
                            <v-data-table v-show="data.Tiporden_id == 1" :headers="headersIncapacidadesOrdered"
                                :items="incapacidadOrdered" hide-actions class="elevation-1"
                                pagination.sync="pagination" item-key="id" loading="true">
                                <template v-slot:items="props">
                                    <td class="text-xs-center"> {{ props.item.Fechainicio }} </td>
                                    <td class="text-xs-center"> {{ props.item.Dias }} </td>
                                    <td class="text-xs-center"> {{ props.item.Fechafinal }} </td>
                                    <td class="text-xs-center"> {{ props.item.Prorroga }} </td>
                                    <td class="text-xs-center"> {{ props.item.Contingencia }} </td>
                                </template>
                                <v-alert v-slot:no-data :value="true" color="error" icon="warning">No hay datos.
                                </v-alert>
                            </v-data-table>
                            <!--tipo orden 3 o 7-->
                            <v-data-table v-show="data.Tiporden_id == 3" :headers="headersArticulosOrdered"
                                :items="articulosOrdered" hide-actions class="elevation-1" pagination.sync="pagination"
                                item-key="id" loading="true">
                                <template v-slot:items="props">
                                    <td class="text-xs-center"> {{ props.item.nombre }} </td>
                                    <td class="text-xs-center"> {{ props.item | PosViaAdmin }} </td>
                                    <!--{{ props.item.Cantidadosis }} {{ (props.item.Unidadtiempo == 'Horas')? `${props.item.Unidadmedida} cada ${props.item.Frecuencia} Horas ${props.item.Duracion} dias por ${props.item.NumMeses} meses` : 'Dosis Única' }}-->
                                    <td class="text-xs-center"> {{ props.item.Cantidadpormedico }} </td>
                                    <td class="text-xs-center"> {{ props.item.Observacion }} </td>
                                    <td class="text-xs-center"> {{ props.item.estado }} </td>
                                    <td class="text-xs-center">
                                        <v-chip color="primary" v-show="props.item.mipres" text-color="white">MIPRES
                                        </v-chip>
                                    </td>
                                    <!-- <td class="text-xs-center">
                                                          <v-btn color="error" @click="removeArticulo(props)" icon>
                                                              <v-icon>clear</v-icon>
                                                          </v-btn>
                                    </td>-->
                                </template>
                                <v-alert v-slot:no-data :value="true" color="error" icon="warning">No hay datos.
                                </v-alert>
                            </v-data-table>
                            <!--tipo orden 2 o 4-->
                            <v-data-table v-show="
                                    data.Tiporden_id == 2 ||
                                        data.Tiporden_id == 4
                                " :headers="headersProcedimientosOrdered" :items="cupsOrdered" hide-actions
                                class="elevation-1" pagination.sync="pagination" item-key="id" loading="true">
                                <template v-slot:items="props">
                                    <td class="text-xs-center"> {{ props.item.codigo }} </td>
                                    <td class="text-xs-center"> {{ props.item.nombre }} </td>
                                    <td class="text-xs-center"> {{ props.item.ubicacion || "No tiene" }} </td>
                                    <td class="text-xs-center"> {{ props.item.observacion }} </td>
                                    <td class="text-xs-center"> {{ props.item.estado }} </td>
                                    <td class="text-xs-center"> {{ props.item.created_at.substring( 0, 10 ) }} </td>
                                    <td class="text-xs-center">
                                        <v-chip color="primary" v-show="props.item.anexo3" text-color="white">ANEXO 3
                                        </v-chip>
                                    </td>
                                </template>
                                <v-alert v-slot:no-data :value="true" color="error" icon="warning">No hay datos.
                                </v-alert>
                            </v-data-table>
                            <!--tipo orden 6-->
                            <v-data-table v-show="data.Tiporden_id == 6" :headers="headersProcedimientosOrdered"
                                :items="serviciosPropiosOrdered" hide-actions class="elevation-1"
                                pagination.sync="pagination" item-key="id" loading="true">
                                <template v-slot:items="props">
                                    <td class="text-xs-center"> {{ props.item.codigo }} </td>
                                    <td class="text-xs-center"> {{ props.item.nombre }} </td>
                                    <td class="text-xs-center"> {{ props.item.ubicacion || "No tiene" }} </td>
                                    <td class="text-xs-center"> {{ props.item.observacion }} </td>
                                    <td class="text-xs-center"> {{ props.item.estado }} </td>
                                    <td class="text-xs-center"> {{ props.item.created_at.substring( 0, 10 ) }} </td>
                                </template>
                                <v-alert v-slot:no-data :value="true" color="error" icon="warning">No hay datos.
                                </v-alert>
                            </v-data-table>
                            <!--tipo orden 7-->
                            <v-card-text v-show="data.Tiporden_id == 7">
                                <v-layout row wrap>
                                    <v-flex xs12> <span><b>Nombre esquema:</b> {{ infoOrder.scheme_name }}</span>
                                        <br /> <span><b>Número ciclo:</b> {{ infoOrder.page }}</span>
                                        <br /> <span><b>Total número ciclo:</b> {{ infoOrder.total_pages }}</span>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                            <v-data-table v-show="data.Tiporden_id == 7" :headers="headersArticulosOrdered"
                                :items="articulosOrdered" hide-actions class="elevation-1" pagination.sync="pagination"
                                item-key="id" loading="true">
                                <template v-slot:items="props">
                                    <td class="text-xs-center"> {{ props.item.nombre }} </td>
                                    <td class="text-xs-center"> {{ props.item | PosViaAdmin }} </td>
                                    <!--{{ props.item.Cantidadosis }} {{ (props.item.Unidadtiempo == 'Horas')? `${props.item.Unidadmedida} cada ${props.item.Frecuencia} Horas ${props.item.Duracion} dias por ${props.item.NumMeses} meses` : 'Dosis Única' }}-->
                                    <td class="text-xs-center"> {{ parseFloat( props.item.Cantidadpormedico ) }} </td>
                                    <td class="text-xs-center"> {{ props.item.Observacion }} </td>
                                    <td class="text-xs-center"> {{ props.item.estado }} </td>
                                    <!-- <td class="text-xs-center">
                                                          <v-btn color="error" @click="removeArticulo(props)" icon>
                                                              <v-icon>clear</v-icon>
                                                          </v-btn>
                                    </td>-->
                                </template>
                                <v-alert v-slot:no-data :value="true" color="error" icon="warning">No hay datos.
                                </v-alert>
                            </v-data-table>
                        </v-flex>
                        <v-layout row wrap>
                            <v-btn color="warning" round v-show="
                                    articulosOrdered.length > 0 &&
                                        data.Tiporden_id == 7
                                " @click="cancelOrderOncologica()"> Cancelar </v-btn>
                            <VSpacer />
                            <v-btn color="primary" dark @click="printPDF()"> Imprimir </v-btn>
                        </v-layout>
                    </v-layout>
                </v-container>
            </v-card>
        </v-flex>
    </v-container>
</template>
<script>
    import moment from "moment";
    import swal from "sweetalert";
    import {
        Cie10Service,
        CodeSumiService,
        CupService,
        TipoOrdenService
    }
    from "../../services";
    export default {
        props: {
            datosCita: Object,
        },
        data: () => ({
            datosMedicamentos : {},
            dialogSuspender: false,
            dialogHistorico: false,
            dialogSuspenciones: false,
            dialogNegaciones: false,
            dialogConciliacion: false,
            motivo: [],
            orden: [],
            compoM: null,
            startDate: moment().format("YYYY-MM-DD"),
            articulos: {
                medicamento: {},
                Cantidadosis: "1",
                Unidadmedida: "",
                Frecuencia: "0",
                Via: "ORAL",
                Unidadtiempo: "Dosis Única",
                Duracion: "",
                Cantidadmensual: "",
                NumMeses: "1",
                Cantidadpormedico: "",
                Observacion: "",
            },
            articulosOrdered: [],
            bodySurfaceIndex: 0,
            cie10s: [],
            cita_paciente: null,
            codeSumiEsquema: [],
            colegios: [],
            cups: [],
            cupsOrdered: [],
            data: {
                Tiporden_id: null,
                articulos: [],
                procedimientos: [],
                serviciosPropios: [],
                codeSumiEsquemaSelected: [],
            },
            enable: true,
            esquema: {
                ciclos: null,
                frecuenciaRepeat: null,
                cycleFrom: null,
            },
            ordering: false,
            esquemas: [],
            incap: {},
            optometria: {
                esfera_od: "",
                esfera_oi: "",
            },
            incapacidad: {
                FechaInicio: null,
                CantidadDias: 0,
                FechaFinal: null,
                Prorroga: "",
                Cie10: "",
                TextCie10: "",
                Contingencia: "",
                Descripcion: "",
                Colegio: "",
            },
            incapacidadOrdered: [],
            infoOrder: {},
            filterMedicamentosByNo: [],
            headersHistoricoMedicamentos: [{
                    text: '# Orden',
                    align: "center",
                },
                {
                    text: 'Estado',
                    align: "center",
                },
                {
                    text: 'Medicamento',
                    align: "center",
                },
                {
                    text: 'Mes',
                    align: "center",
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
                    text: 'Unidad de Tiempo',
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
                    text: 'Suspender',
                    align: "center",
                },
                {
                    text: 'Reformular',
                    align: "center",
                }
            ],
            headersEsquemasTable: [{
                text: "Codigo - Medicamento",
                align: "center",
                value: "Codigo",
            }, {
                text: "dosis",
                align: "center",
                value: "dosis",
            }, {
                text: "indice dosis",
                align: "center",
                value: "indiceDosis",
            }, {
                text: "dosis formulada",
                align: "center",
                value: "dosisFormulada",
            }, {
                text: "via",
                align: "center",
                value: "via",
            }, {
                text: "cantidad aplicaciones",
                align: "center",
                value: "cantidadAplicaciones",
            }, {
                text: "frecuencia",
                align: "center",
                value: "frecuencia",
            }, {
                text: "Observación",
                align: "center",
                value: "observaciones",
            }, {
                text: "Frecuencia duración",
                align: "center",
                value: "observaciones",
            }, ],
            headersIncapacidadesOrdered: [{
                text: "Fecha inicio",
                align: "center",
                value: "Fechainicio",
            }, {
                text: "Dias",
                align: "center",
                value: "Dias",
            }, {
                text: "Fecha final",
                align: "center",
                value: "Fechafinal",
            }, {
                text: "Prorroga",
                align: "center",
                value: "Prorroga",
            }, {
                text: "Contingencia",
                align: "center",
                value: "Contingencia",
            }, ],
            headersProcedimientos: [{
                text: "Código",
                align: "center",
                value: "codigo",
                sortable: false,
            }, {
                text: "Descripción",
                align: "center",
                value: "descripcion",
                sortable: false,
            }, {
                text: "Req. Auditoria",
                align: "center",
                value: "auditoria",
                sortable: false,
            }, {
                text: "Cantidad",
                align: "center",
                value: "cantidad",
                sortable: false,
            }, {
                text: "Observación",
                align: "center",
                value: "observacion",
                sortable: false,
            }, {
                text: "",
                align: "center",
                value: "",
            }, ],
            headersProcedimientosOrdered: [{
                text: "Código",
                align: "center",
                value: "codigo",
                sortable: false,
            }, {
                text: "Descripción",
                align: "center",
                value: "descripcion",
                sortable: false,
            }, {
                text: "Ubicación",
                align: "center",
                value: "ubicacion",
                sortable: false,
            }, {
                text: "Observación",
                align: "center",
                value: "Observacionorden",
                sortable: false,
            }, {
                text: "Estado actual",
                align: "center",
                value: "estado",
                sortable: false,
            }, {
                text: "Vigente desde",
                align: "center",
                value: "estado",
                sortable: false,
            }, ],
            headersArticulosOrdered: [{
                text: "Medicamento",
                align: "center",
                value: "nombre",
                sortable: false,
            }, {
                text: "Descripción",
                align: "center",
                value: "Cantidadosis",
                sortable: false,
            }, {
                text: "Cantidad mensual",
                align: "center",
                value: "Cantidadpormedico",
                sortable: false,
            }, {
                text: "Observación",
                align: "center",
                value: "Observacion",
                sortable: false,
            }, {
                text: "Estado",
                align: "center",
                value: "estado",
                sortable: false,
            }, {
                text: "",
                align: "center",
                value: "",
                sortable: false,
            }, ],
            headers: [{
                text: "Medicamento",
                align: "center",
                value: "nombre",
                sortable: false,
            }, {
                text: "Descripción",
                align: "center",
                value: "Cantidadosis",
                sortable: false,
            }, {
                text: "Cantidad mensual",
                align: "center",
                value: "Cantidadpormedico",
                sortable: false,
            }, {
                text: "Observación",
                align: "center",
                value: "Observacion",
                sortable: false,
            }, {
                text: "Requiere autorización",
                align: "center",
                value: "Requiere_autorizacion",
                sortable: false,
            }, {
                text: "Accion",
                sortable: false,
            }, ],
            loading: false,
            medicamentos: [],
            medicamentosByYes: [],
            medicamentosByNo: [],
            menu1: false,
            meses: [{
                accion: "1",
                value: "1",
            }, {
                accion: "2",
                value: "2",
            }, {
                accion: "3",
                value: "3",
            }, {
                accion: "4",
                value: "4",
            }, {
                accion: "5",
                value: "5",
            }, {
                accion: "6",
                value: "6",
            }, ],
            paciente: {},
            order_id: "",
            object: {},
            optionsUnidadPresentacion: ["Ampolla", "Aplicaciones", "Bolsa", "Capsula", "Comprimido", "Gotas",
                "Implante", "Inhalaciones", "Litro", "Mililitros", "Parche", "Sobre", "Supositorio",
                "Tableta", "Unidades", "Unidad Internacional", "Vial",
            ],
            optionsVia: ["Oral", "Intramuscular", "Intravenosa", "Subcutánea", "Sublingual", "Inhalatoria",
                "Transdérmica", "Dérmica", "Nasal", "Oftálmica", "Ótica", "Tópica", "Rectal", "Vaginal",
                "Enjuague", "Intratectual", "Enteral"
            ],
            path: "",
            procedimiento: {
                cup: {},
                cantidad: "",
                observacion: "",
                date: moment().format("YYYY-MM-DD"),
            },
            PosViaAdmin: "",
            recomendation: "",
            search: "",
            serviciosPropiosOrdered: [],
            historicoMedicamentos: [],
            servicios: [],
            serviciosByYes: [],
            serviciosByNo: [],
            serviciosAnexo: [],
            servicioPropio: {
                service: {},
                cantidad: "",
                observacion: "",
            },
            serviciosPropios: [],
            show_start_date: false,
            recomendacion: {},
            tipoOrdenamiento: [],
            ubicaciones: [],
            yearago: null,
        }),
        filters: {
            PosViaAdmin: (item) => {
                if (item.Unidadtiempo == "Horas")
                    return `${item.Cantidadosis} ${item.Unidadmedida} ${item.Via} cada ${item.Frecuencia} Horas por ${item.Duracion} días`;
                else return `${item.Cantidadosis} ${item.Unidadmedida} ${item.Via} cada
                    ${item.Frecuencia} Días por ${item.Duracion} días`;
            },
        },
        watch: {
            search() {
                if (this.search && this.search.length == 3) this.fetchColegios();
            },
        },
        computed: {
            cantidadMensual() {
                if (this.articulos.Unidadtiempo == "Horas") {
                    this.articulos.Cantidadmensual = Math.round((24 / this.articulos.Frecuencia) * this.articulos
                        .Cantidadosis * this.articulos.Duracion);
                } else {
                    this.articulos.Cantidadmensual = Math.round((this.articulos.Duracion / this.articulos.Frecuencia) *
                        this.articulos.Cantidadosis);
                }
                return this.articulos.Cantidadmensual || 0;
            },
            filteredMedicamentos() {
                let less = this.articulosOrdered.map((articulo) => articulo.codesumi_id);
                less = less.concat(this.data.articulos.map((articulo) => articulo.id));
                return this.medicamentos.filter((med) => {
                    if (!less.find((l) => l == med.id)) return med;
                });
            },
            filteredProcedimientos() {
                let less = this.cupsOrdered.map((proc) => proc.cup_id);
                less = less.concat(this.data.procedimientos.map((proc) => proc.id));
                return this.cups.filter((cup) => {
                    if (!less.find((l) => l == cup.id)) return cup;
                    return false;
                }).map((cup) => {
                    return {
                        ...cup,
                        full_name: `${cup.Codigo} - ${cup.Nombre}`,
                    };
                });
            },
            filteredServiciosPropios() {
                let less = this.serviciosPropiosOrdered.map((serv) => serv.Serviciopropio_id);
                less = less.concat(this.data.serviciosPropios.map((serv) => serv.id));
                return this.serviciosPropios.filter((servicio) => {
                    if (!less.find((l) => l == servicio.id)) return servicio;
                    return false;
                }).map((servicio) => {
                    return {
                        ...servicio,
                        full_name: `${servicio.Codigo} - ${servicio.Nombre}`,
                    };
                });
            },
            mapDetailScheme() {
                return this.codeSumiEsquema.map((detail) => {
                    if (detail.frecuenciaDuracion) {
                        detail.frecuenciaDuracion = detail.frecuenciaDuracion.split(" ");
                        detail.frecuenciaDuracion[1] = detail.dosisFormulada;
                        detail.frecuenciaDuracion[detail.frecuenciaDuracion.length - 1] = detail.diasAplicacion;
                        detail.frecuenciaDuracion = detail.frecuenciaDuracion.join(" ");
                    }
                    return detail;
                });
            },
        },
        created() {
            this.datosPacienteOrdenamiento();
            this.fetchCie10s();
            this.fetchTipoOrdenamiento();
            this.getLocalStorage();
        },
        methods: {
            addArticulo: async function () {
                if (!this.articulos.Cantidadpormedico) {
                    this.$alerError('El campo cantidad mensual medico es obligatorio!')
                    return;
                }

                if (this.articulos.medicamento.hasOwnProperty("id") && this.articulos.Cantidadosis && this.articulos
                    .Unidadmedida && this.articulos.Via && this.articulos.Unidadtiempo && this.articulos
                    .Cantidadmensual && this.articulos.NumMeses && this.articulos.Cantidadpormedico) {
                    let medicamento = await this.getDetaMedicamentos(this.articulos);
                    if (medicamento) {
                        let msg =
                            `Existen Ordenes activas para ése medicamento por la cantidad ${medicamento.Cantidadpormedico} en la siguiente fecha ${medicamento.Fechaorden}. La cantidad máxima es de ${medicamento.Cantidadmaxordenar}`;
                        swal({
                            title: "Error",
                            text: msg,
                            type: "success",
                            icon: "info",
                            buttons: ["Cancelar", "Confirmar"],
                            dangerMode: true,
                        })
                        //     .then(async (willDelete) => {
                        //     if (willDelete) {
                        //         this.pushArticulo(medicamento.Cantidadpormedico);
                        //     }
                        // });
                    } else {
                        this.pushArticulo();
                    }
                }
            },
            addProcedimiento: async function () {
                if (this.procedimiento.cup.hasOwnProperty("id") && this.procedimiento.cantidad > 0) {
                    let service = await this.getDetaServicios(this.procedimiento);
                    if (service) {
                        let msg =
                            `Existen Ordenes activas para ése procedimiento por la cantidad ${service.Cantidad} en la siguiente fecha ${service.created_at}. La cantidad máxima es de ${service.Cantidadmaxordenar}`;
                        swal({
                            title: "Error",
                            text: msg,
                            type: "success",
                            icon: "info",
                            // buttons: ["Cancelar", "Confirmar"],
                            dangerMode: true,
                        })
                        //     .then(async (willDelete) => {
                        //     if (willDelete) {
                        //         this.pushProcedimiento(service.Cantidad);
                        //     }
                        // });
                    } else {
                        this.pushProcedimiento();
                    }
                }
            },
            addservicioPropio: async function () {
                if (this.servicioPropio.service.hasOwnProperty("id") && this.servicioPropio.cantidad > 0) {
                    let service = await this.getDetaServiciosPropios(this.servicioPropio);
                    if (service) {
                        let msg =
                            `Existen Ordenes activas para ése servicio por la cantidad ${service.Cantidad} en la siguiente fecha ${service.created_at}. La cantidad máxima es de ${service.Cantidadmaxordenar}`;
                        swal({
                            title: "¿Está Seguro de añadir?",
                            text: msg,
                            type: "success",
                            icon: "info",
                            buttons: ["Cancelar", "Confirmar"],
                            dangerMode: true,
                        }).then(async (willDelete) => {
                            if (willDelete) {
                                this.pushServicioPropio(service.Cantidad);
                            }
                        });
                    } else {
                        this.pushServicioPropio();
                    }
                }
            },
            cancelOrderOncologica: async function () {
                try {
                    let isSure = await this.$alertQuestion("¿Segur@ que desea cancelar este esquema?");
                    if (isSure.value) {
                        let {
                            data
                        } = await axios.post(`/api/orden/cancelEsquema/${this.articulosOrdered[0].Orden_id}`);
                        this.infoOrder = {};
                        this.articulosOrdered = [];
                        this.tipoOrdenSelected();
                        this.$alerSuccess(data.message);
                    }
                } catch (error) {
                    console.error(error);
                }
            },
            clearData() {
                this.data = {
                    Tiporden_id: this.data.Tiporden_id,
                    articulos: [],
                    procedimientos: [],
                    serviciosPropios: [],
                    codeSumiEsquemaSelected: [],
                };
            },
            clearDataArticulo() {
                this.articulos = {
                    medicamento: "",
                    Cantidadosis: "1",
                    Unidadmedida: "",
                    Via: "ORAL",
                    Frecuencia: "0",
                    Unidadtiempo: "Dosis Única",
                    Duracion: "1",
                    Cantidadmensual: "",
                    NumMeses: "1",
                    Cantidadpormedico: "",
                    Observacion: "",
                };
            },
            clearDataProcedimiento() {
                this.procedimiento.cup = {};
                this.procedimiento.cantidad = "";
                this.procedimiento.observacion = "";
                this.procedimiento.date = moment().format("YYYY-MM-DD");
            },
            clearDataServicioPropio() {
                this.servicioPropio.service = {};
                this.servicioPropio.cantidad = "";
                this.servicioPropio.observacion = "";
            },
            clearPropsBy() {
                this.medicamentosByNo = [];
                this.medicamentosByYes = [];
                this.serviciosAnexo = [];
                this.serviciosByNo = [];
                this.serviciosByYes = [];
                this.servicioPropioByNo = [];
                this.servicioPropioByYes = [];
            },
            fetchBodySurfaceIndex: async function () {
                await axios.get(`/api/historiapaciente/${this.datosCita.cita_paciente_id}/examen_fisico`).then((
                    res) => {
                    this.bodySurfaceIndex = res.data.ISC;
                });
            },
            dosisFormuladaCalculate(detail) {
                if (detail.indiceDosis === "ISC") detail.dosisFormulada = (detail.dosis * this.bodySurfaceIndex)
                    .toFixed(1);
                else detail.dosisFormulada = detail.dosis;
            },
            fetchTipoOrdenamiento() {
                this.path = this.$route.path;
                if (this.path.includes("historiaclinica")) {
                    this.incapacidad.FechaInicio = moment().format("YYYY-MM-DD");
                }
                this.cita_paciente = this.datosCita.cita_paciente_id;
                axios.get("/api/tipoOrden/all").then((res) => {
                    this.tipoOrdenamiento = res.data;
                });
            },
            fetchCie10s: async function () {
                try {
                    this.cie10s = await Cie10Service.all();
                } catch (error) {
                    console.log(error);
                }
            },
            fetchCodeSumiEsquema: async function () {
                try {
                    this.codeSumiEsquema = [];
                    this.data.codeSumiEsquemaSelected = [];
                    this.codeSumiEsquema = await CodeSumiService.getCodeSumiEsquemas(this.esquema.id);
                    this.data.codeSumiEsquemaSelected = this.codeSumiEsquema.map((detail) => {
                        if (detail.indiceDosis === "ISC") detail.dosisFormulada = (detail.dosis * this
                            .bodySurfaceIndex).toFixed(1);
                        else detail.dosisFormulada = detail.dosis;
                        if (detail.frecuenciaDuracion) {
                            detail.frecuenciaDuracion = detail.frecuenciaDuracion.split(" ");
                            detail.frecuenciaDuracion[1] = detail.dosisFormulada;
                            detail.frecuenciaDuracion[detail.frecuenciaDuracion.length - 1] = detail
                                .diasAplicacion;
                            detail.frecuenciaDuracion = detail.frecuenciaDuracion.join(" ");
                        }
                        return detail;
                    });
                } catch (error) {
                    console.log(error);
                }
            },
            fetchCupsOtherService: async function () {
                try {
                    this.cups = await CupService.getCupsOtherServiceByUserRoleLevel();
                } catch (error) {
                    console.log(error);
                }
            },
            fetchCupsInterconsultas: async function () {
                await axios.get("/api/cups/interconsultas/" + this.cita_paciente).then((res) => {
                    this.cups = res.data;
                });
            },
            fetchEnableEsquemas: async function () {
                try {
                    let esquemas = await TipoOrdenService.getEnableEsquemas();
                    this.esquemas = esquemas.map((code) => ({
                        ...code,
                        cycleFrom: 1,
                        fullName: `${code.abrevEsquema} - ${code.nombreEsquema}`,
                    }));
                } catch (error) {
                    console.log(error);
                }
            },
            fetchEnableEsquemasOrdered: async function () {
                await axios.get(`/api/orden/getOrderedScheme/${this.datosCita.cita_paciente_id}`).then((res) => {
                    this.articulosOrdered = [];
                    if (res.data.hasOwnProperty("id")) {
                        this.articulosOrdered = res.data.detaarticulordens.map((art) => art);
                    } else if (res.data.length > 0) {
                        res.data.forEach((data) => {
                            let detailItems = data.detaarticulordens.map((art) => art);
                            detailItems.forEach((detailItem) => {
                                let found = this.articulosOrdered.find((articulo) =>
                                    articulo.codesumi_id == detailItem.codesumi_id);
                                if (!found) this.articulosOrdered.push(detailItem);
                            });
                        });
                        this.infoOrder = {
                            scheme_name: res.data[0].scheme_name,
                            page: res.data[0].page,
                            total_pages: res.data[0].total_pages,
                        };
                    }
                    this.order_id = res.data.id;
                });
            },
            fetchMedicamentos: async function () {
                await axios.get("/api/codesumi/medicamentosumi/" + this.cita_paciente).then((res) => {
                    this.medicamentos = res.data;
                });
            },
            fetchMedicamentosOrdered: async function () {
                this.articulosOrdered = [];
                await axios.get(`/api/orden/getOrderedCodesumi/${this.datosCita.cita_paciente_id}`).then((res) => {
                    res.data.forEach((data) => {
                        data.detaarticulordens.forEach((s) => {
                            let objIndex = this.articulosOrdered.findIndex((obj) => obj
                                .nombre === s.nombre);
                            if (objIndex === -1) {
                                this.articulosOrdered.push(s);
                            }
                        });
                    });
                    // else this.articulosOrdered = [];
                    // this.order_id = res.data.id;
                });
            },
            fetchColegios() {
                this.loading = true;
                axios.post("/api/colegio/getColegioByName", {
                    nombre: this.search,
                }).then((res) => {
                    this.colegios = res.data;
                    this.loading = false;
                });
            },
            fetchCupsOrdered() {
                axios.post(`/api/orden/getOrderedCup/${this.datosCita.cita_paciente_id}`, {
                    Tiporden_id: this.data.Tiporden_id,
                }).then((res) => {
                    this.cupsOrdered = res.data;
                    if (res.data.length > 0) {
                        this.order_id = this.cupsOrdered[0].hasOwnProperty("Orden_id") ? this.cupsOrdered[0]
                            .Orden_id : null;
                        res.data.forEach((s) => {
                            this.imprimirRecomendacion(s.codigo);
                        });
                    } else {
                        this.order_id = null;
                    }
                });
            },
            fetchIncOrdenred() {
                axios.get(`/api/orden/getOrderedCie/${this.datosCita.cita_paciente_id}`).then((res) => {
                    this.incapacidadOrdered = res.data;
                });
            },
            fetchServiciosPropios: async function () {
                await axios.get("/api/codepropio/serviciosPropios").then((res) => {
                    this.serviciosPropios = res.data;
                });
            },
            fetchServiciosPropiosOrdered() {
                axios.post(`/api/orden/getOrderedServicioPropio/${this.datosCita.cita_paciente_id}`, {
                    Tiporden_id: this.data.Tiporden_id,
                }).then((res) => {
                    this.serviciosPropiosOrdered = res.data;
                    if (res.data.length > 0) this.order_id = this.serviciosPropiosOrdered[0].hasOwnProperty(
                        "Orden_id") ? this.serviciosPropiosOrdered[0].Orden_id : null;
                    else this.order_id = null;
                });
            },
            generateInc() {
                if (this.incapacidad.Contingencia == "") {
                    swal({
                        title: "Incapacidad",
                        text: "Por favor ingrese una contingencia!",
                        timer: 2000,
                        icon: "error",
                        buttons: false,
                    });
                    return;
                }
                if (this.incapacidad.FechaInicio == "") {
                    swal({
                        title: "Por favor ingrese una fecha de inicio!Incapacidad",
                        text: "",
                        timer: 2000,
                        icon: "error",
                        buttons: false,
                    });
                    return;
                }
                if (this.incapacidad.FechaFinal == "") {
                    swal({
                        title: "Por favor ingrese una fecha final!",
                        text: "Incapacidad",
                        timer: 2000,
                        icon: "error",
                        buttons: false,
                    });
                    return;
                }
                if (this.incapacidad.Prorroga == "") {
                    swal({
                        title: "Por favor ingrese una prorroga!",
                        text: "Incapacidad",
                        timer: 2000,
                        icon: "error",
                        buttons: false,
                    });
                    return;
                }
                if (this.paciente.Ut === "REDVITAL UT" && this.incapacidad.Colegio == "") {
                    swal({
                        title: "Por favor ingrese un colegio!",
                        text: "Incapacidad",
                        timer: 2000,
                        icon: "error",
                        buttons: false,
                    });
                    return;
                }
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Se guardará la incapacidad!",
                    type: "success",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then(async (willDelete) => {
                    if (willDelete) {
                        let incapacidad = this.getIncapacidad();
                        this.data = {
                            ...this.data,
                            ...incapacidad,
                        };
                        await axios.post(
                            `/api/orden/citapaciente/${this.datosCita.cita_paciente_id}/create`, this
                            .data).then((res) => {
                            this.order_id = res.data.orden_id;
                            this.fetchIncOrdenred();
                            swal("¡Orden creada de manera exitosa!", {
                                timer: 2000,
                                icon: "success",
                                buttons: false,
                            });
                        }).catch((err) => {
                            this.showError(err.response.data.message);
                        });
                    }
                });
            },
            generateOrder: async function () {
                if (this.data.Tiporden_id == 7 && this.data.codeSumiEsquemaSelected.length === 0) {
                    return this.$alerError("No hay medicamentos seleccionados");
                }
                if (this.data.Tiporden_id == 3 && this.data.articulos.length === 0) {
                    return this.$alerError("No hay medicamentos seleccionados");
                }
                if (this.data.Tiporden_id == 4 && this.data.procedimientos.length === 0) {
                    return this.$alerError("No hay servicios seleccionados");
                }
                if (this.data.Tiporden_id == 6 && this.data.serviciosPropios.length === 0) {
                    return this.$alerError("No hay servicios seleccionados");
                }
                if (this.data.Tiporden_id == 2 && this.data.procedimientos.length === 0) {
                    return this.$alerError("No hay servicios seleccionados");
                }
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Se realizará la creación de la orden!",
                    type: "success",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        this.ordering = true;
                        axios.post(`/api/orden/citapaciente/${this.cita_paciente}/create`, {
                            ...this.data,
                            frecuenciaRepeat: this.esquema.frecuenciaRepeat,
                            nombreEsquema: this.esquema.nombreEsquema,
                            biografia: this.esquema.biografia,
                            cycleFrom: this.esquema.cycleFrom,
                            ciclos: this.esquema.ciclos,
                            biografia: this.esquema.biografia,
                        }).then((res) => {
                            this.ordering = false;
                            if (this.data.Tiporden_id == 3) {
                                this.fetchMedicamentosOrdered();
                            } else if (this.data.Tiporden_id == 6) {
                                this.fetchServiciosPropiosOrdered();
                            } else if (this.data.Tiporden_id == 7) {
                                this.fetchEnableEsquemasOrdered();
                                this.codeSumiEsquema = [];
                                this.esquema = {
                                    ciclos: null,
                                    frecuenciaRepeat: null,
                                };
                            } else {
                                this.fetchCupsOrdered();
                            }
                            this.clearData();
                            if (res.data.ubicaciones) {
                                this.ubicaciones = res.data.ubicaciones;
                            }
                            swal("¡Orden creada de manera exitosa!", {
                                timer: 2000,
                                icon: "success",
                                buttons: false,
                            });
                        }).catch((err) => this.showError(err.response.data.message));
                    }
                });
            },
            getFinalDate() {
                this.enable = true;
                if (this.incapacidad.CantidadDias <= 0) {
                    swal({
                        title: "Incapacidad",
                        text: "Por favor ingrese una cantidad de días mayor a 0",
                        timer: 2000,
                        icon: "error",
                        buttons: false,
                    });
                    this.enable = false;
                    this.incapacidad.FechaFinal = null;
                }
                if (this.incapacidad.Contingencia != "licencia maternidad" && this.incapacidad.CantidadDias > 30) {
                    swal({
                        title: "Incapacidad",
                        text: "Por favor ingrese una cantidad de días menor o igual a 30 días!",
                        timer: 2000,
                        icon: "error",
                        buttons: false,
                    });
                    this.enable = false;
                    this.incapacidad.FechaFinal = null;
                }
                if (this.enable == true) {
                    this.incapacidad.FechaFinal = moment(this.incapacidad.FechaInicio).add(this.incapacidad
                        .CantidadDias - 1, "d").format("YYYY-MM-DD");
                }
            },
            getIncapacidad() {
                // let object = this.cie10s.find((cie) => cie.Codigo_CIE10 == this.paciente.Cie10_id);
                return (this.incap = {
                    Fechainicio: this.incapacidad.FechaInicio,
                    Dias: this.incapacidad.CantidadDias,
                    Fechafinal: moment(this.incapacidad.FechaFinal).format(),
                    Prorroga: this.incapacidad.Prorroga,
                    Cedula: this.paciente.Num_Doc,
                    Cie10_id: this.paciente.Cie10_id,
                    // Cie10_id: object.id,
                    Contingencia: this.incapacidad.Contingencia,
                    Descripcion: this.incapacidad.Descripcion,
                    Laboraen: this.incapacidad.Colegio,
                });
            },
            getPDFFormula(numero, page, total, order_id) {
                return (this.object = {
                    Primer_Nom: this.paciente.Primer_Nom,
                    Segundo_Nom: this.paciente.SegundoNom,
                    Primer_Ape: this.paciente.Primer_Ape,
                    Segundo_Ape: this.paciente.Segundo_Ape,
                    Tipo_Doc: this.paciente.Tipo_Doc,
                    Num_Doc: this.paciente.Num_Doc,
                    Edad_Cumplida: this.paciente.Edad_Cumplida,
                    Sexo: this.paciente.Sexo,
                    IPS: this.paciente.NombreIPS,
                    Direccion_Residencia: this.paciente.Direccion_Residencia,
                    Correo: this.paciente.Correo1,
                    Celular: this.paciente.Telefono,
                    Tipo_Afiliado: this.paciente.Tipo_Afiliado,
                    Estado_Afiliado: this.paciente.Estado_Afiliado,
                    medico_ordena: this.paciente.medicoordeno,
                    dx_principal: this.paciente.Cie10_id,
                    orden_id: order_id,
                    type: "formula",
                    medicamentos: this.filterMedicamentosByNo,
                    cita_paciente_id: this.cita_paciente,
                    page,
                    numero,
                    total,
                });
            },
            getPDFIncap() {
                return (this.object = {
                    type: "incapacidad",
                    Primer_Nom: this.paciente.Primer_Nom,
                    Segundo_Nom: this.paciente.SegundoNom,
                    Primer_Ape: this.paciente.Primer_Ape,
                    Segundo_Ape: this.paciente.Segundo_Ape,
                    Nombre: `${this.paciente.Primer_Nom} ${this.paciente.SegundoNom} ${this.paciente.Primer_Ape} ${this.paciente.Segundo_Ape} `,
                    Tipo_Doc: this.paciente.Tipo_Doc,
                    Num_Doc: this.paciente.Num_Doc,
                    Edad_Cumplida: this.paciente.Edad_Cumplida,
                    Sexo: this.paciente.Sexo,
                    IPS: this.paciente.NombreIPS,
                    Direccion_Residencia: this.paciente.Direccion_Residencia,
                    Correo: this.paciente.Correo1,
                    Celular: this.paciente.Telefono,
                    Tipo_Afiliado: this.paciente.Tipo_Afiliado,
                    Estado_Afiliado: this.paciente.Estado_Afiliado,
                    medico_ordena: this.paciente.medicoordeno,
                    dx_principal: this.paciente.Cie10_id,
                    orden_id: this.order_id,
                    cita_paciente_id: this.cita_paciente,
                    FechaInicio: this.incapacidad.FechaInicio,
                    CantidadDias: this.incapacidad.CantidadDias,
                    hj: this.incapacidad.hj,
                    FechaFinal: this.incapacidad.FechaFinal,
                    Prorroga: this.incapacidad.Prorroga,
                    afilia: this.incapacidad.afilia,
                    Firma: this.paciente.Firma,
                    especialidad_medico: this.paciente.especialidad_medico,
                    Rmedico: this.paciente.Rmedico,
                    Ut: this.incapacidad.Ut,
                    ips1: this.incapacidad.ips1,
                    ma: this.incapacidad.ma,
                    TTipod: this.incapacidad.TTipod,
                    Cie10: this.incapacidad.Cie10,
                    Contingencia: this.incapacidad.Contingencia,
                    Descripcion: this.incapacidad.Descripcion,
                    // TextCie10: this.incapacidad.TextCie10
                    TextCie10: this.paciente.Cie10_id,
                });
            },
            getPDFAnexos() {
                return (this.object = {
                    Primer_Nom: this.paciente.Primer_Nom,
                    Segundo_Nom: this.paciente.SegundoNom,
                    Primer_Ape: this.paciente.Primer_Ape,
                    Segundo_Ape: this.paciente.Segundo_Ape,
                    Tipo_Doc: this.paciente.Tipo_Doc,
                    Num_Doc: this.paciente.Num_Doc,
                    Edad_Cumplida: this.paciente.Edad_Cumplida,
                    Sexo: this.paciente.Sexo,
                    IPS: this.paciente.NombreIPS,
                    Direccion_Residencia: this.paciente.Direccion_Residencia,
                    Correo: this.paciente.Correo1,
                    Celular: this.paciente.Telefono,
                    Tipo_Afiliado: this.paciente.Tipo_Afiliado,
                    Estado_Afiliado: this.paciente.Estado_Afiliado,
                    medico_ordena: this.paciente.medicoordeno,
                    dx_principal: this.paciente.Cie10_id,
                    cita_paciente_id: this.cita_paciente,
                    orden_id: this.order_id,
                    id: this.procedimiento.id,
                    type: "Anexo",
                    tipos: 'anexo3Servicios',
                });
            },
            getPDFOtros() {
                return (this.object = {
                    Primer_Nom: this.paciente.Primer_Nom,
                    Segundo_Nom: this.paciente.SegundoNom,
                    Primer_Ape: this.paciente.Primer_Ape,
                    Segundo_Ape: this.paciente.Segundo_Ape,
                    Tipo_Doc: this.paciente.Tipo_Doc,
                    Num_Doc: this.paciente.Num_Doc,
                    Edad_Cumplida: this.paciente.Edad_Cumplida,
                    Sexo: this.paciente.Sexo,
                    IPS: this.paciente.NombreIPS,
                    Direccion_Residencia: this.paciente.Direccion_Residencia,
                    Correo: this.paciente.Correo1,
                    Celular: this.paciente.Telefono,
                    Tipo_Afiliado: this.paciente.Tipo_Afiliado,
                    Estado_Afiliado: this.paciente.Estado_Afiliado,
                    medico_ordena: this.paciente.medicoordeno,
                    dx_principal: this.paciente.Cie10_id,
                    cita_paciente_id: this.cita_paciente,
                    orden_id: this.order_id,
                    type: "otros",
                    servicios: this.serviciosByNo,
                });
            },
            getPosViaAdmin(medicamento) {
                if (medicamento.Unidadtiempo == "Horas") return `${medicamento.Cantidadosis} ${medicamento.Unidadmedida} ${medicamento.Via} cada ${medicamento.Frecuencia} Horas X ${medicamento.Duracion} dias por ${
                        medicamento.NumMeses > 1 ? `
			$ {
				medicamento.NumMeses
			}
			meses ` : `
			$ {
				medicamento.NumMeses
			}
			mes `
                    }`;
                else
                    return `${medicamento.Cantidadosis} ${medicamento.Unidadmedida} ${medicamento.Via} Única vez ${medicamento.Cantidadpormedico}`;
            },
            getServicioPropio() {
                this.servicioPropioByNo = [];
                this.servicioPropioByYes = [];
                this.serviciosPropiosOrdered.forEach((servicioPropio) => {
                    if (servicioPropio.Estado_id != 15) {
                        this.servicioPropioByNo.push(servicioPropio);
                    } else {
                        this.servicioPropioByYes.push(servicioPropio);
                    }
                });
            },
            getPDFRecomendation() {
                return (this.recomendacion = {
                    Primer_Nom: this.paciente.Primer_Nom,
                    Segundo_Nom: this.paciente.SegundoNom,
                    Primer_Ape: this.paciente.Primer_Ape,
                    Segundo_Ape: this.paciente.Segundo_Ape,
                    Tipo_Doc: this.paciente.Tipo_Doc,
                    Num_Doc: this.paciente.Num_Doc,
                    Edad_Cumplida: this.paciente.Edad_Cumplida,
                    Sexo: this.paciente.Sexo,
                    IPS: this.paciente.IPS,
                    Direccion_Residencia: this.paciente.Direccion_Residencia,
                    Correo: this.paciente.Correo1,
                    Celular: this.paciente.Telefono,
                    Tipo_Afiliado: this.paciente.Tipo_Afiliado,
                    Estado_Afiliado: this.paciente.Estado_Afiliado,
                    medico_ordena: this.paciente.medicoordeno,
                    dx_principal: this.paciente.Cie10_id,
                    orden_id: this.order_id,
                    type: "observacion",
                    observaciones: this.recomendation,
                    cita_paciente_id: this.cita_paciente,
                });
            },
            getDetaArticulo: async function () {
                this.medicamentosByNo = [];
                this.medicamentosByYes = [];
                this.articulosOrdered.forEach((medicamento) => {
                    medicamento.PosViaAdmin = this.getPosViaAdmin(medicamento);
                    if (medicamento.Estado_id != 15) {
                        this.medicamentosByNo.push(medicamento);
                    } else {
                        this.medicamentosByYes.push(medicamento);
                    }
                });
            },
            getCupOrden: async function () {
                this.serviciosByNo = [];
                this.serviciosByYes = [];
                this.serviciosAnexo = [];
                this.cupsOrdered.forEach((procedimiento) => {
                    if (procedimiento.Estado_id == 37 && procedimiento.created_at.substring(0, 10) ==
                        moment().format("YYYY-MM-DD")) {
                        this.serviciosAnexo.push(procedimiento);
                    } else if (procedimiento.Estado_id != 15 && procedimiento.created_at.substring(0, 10) ==
                        moment().format("YYYY-MM-DD")) {
                        this.serviciosByNo.push(procedimiento);
                    } else {
                        this.serviciosByYes.push(procedimiento);
                    }
                });
            },
            getConducta() {
                axios.post(`/api/conducta/${this.datosCita.cita_paciente_id}/getConductaByCita`).then((res) => {
                    if (res.data.Recomendaciones) {
                        this.recomendation = res.data.Recomendaciones;
                    }
                }).catch((err) => console.log(err.response));
            },
            getLocalStorage() {
                let Diagnostico = JSON.parse(localStorage.getItem("Diagnostico"));
                if (Diagnostico) {
                    let object = Diagnostico.find((diag) => diag.Esprimario == true);
                    this.paciente.Cie10_id = object.Codigo;
                }
            },
            getDetaMedicamentos: async function (articulo) {
                let days = articulo.medicamento.Frecuencia;
                let Ago = moment().subtract(days, "d").format("YYYY-MM-DD");
                let Next = moment().add(days, "d").format("YYYY-MM-DD");
                let medicamento = {};
                await axios.post("/api/orden/getDetaArticuloOrden", {
                    Codesumi_id: articulo.medicamento.id,
                    Num_Doc: this.paciente.Num_Doc,
                    Frecuencia: days,
                    Ago: Ago,
                    Next: Next,
                }).then((res) => {
                    medicamento = res.data[0];
                    if (medicamento) {
                        const reducer = (acum, {
                            Cantidadpormedico
                        }) => acum + parseInt(Cantidadpormedico);
                        medicamento.Cantidadpormedico = res.data.reduce(reducer, 0);
                    }
                });
                return medicamento;
            },
            getDetaServicios: async function (service) {
                let days = service.cup.Peridiocidad;
                let Ago = moment().subtract(days, "d").format("YYYY-MM-DD");
                let Next = moment().add(days, "d").format("YYYY-MM-DD");
                let procedimiento = {};
                await axios.post("/api/orden/getCupOrden", {
                    Cup_id: service.cup.id,
                    Num_Doc: this.paciente.Num_Doc,
                    Peridiocidad: days,
                    Ago: Ago,
                    Next: Next,
                }).then((res) => {
                    procedimiento = res.data[0];
                    if (procedimiento) {
                        const reducer = (acum, {
                            Cantidad
                        }) => acum + parseInt(Cantidad);
                        procedimiento.Cantidad = res.data.reduce(reducer, 0);
                    }
                });
                return procedimiento;
            },
            getDetaServiciosPropios: async function (service) {
                let days = service.service.Frecuencia;
                let Ago = moment().subtract(days, "d").format("YYYY-MM-DD");
                let Next = moment().add(days, "d").format("YYYY-MM-DD");
                let servicioPropio = {};
                await axios.post("/api/orden/getServicioPropio", {
                    Serviciopropio_id: service.service.id,
                    Num_Doc: this.paciente.Num_Doc,
                    Peridiocidad: days,
                    Ago: Ago,
                    Next: Next,
                }).then((res) => {
                    servicioPropio = res.data[0];
                    if (servicioPropio) {
                        const reducer = (acum, {
                            Cantidad
                        }) => acum + parseInt(Cantidad);
                        servicioPropio.Cantidad = res.data.reduce(reducer, 0);
                    }
                });
                return servicioPropio;
            },
            print: async function (pdf) {
                await axios.post("/api/pdf/print-pdf", pdf, {
                    responseType: "arraybuffer",
                }).then(async (res) => {
                    let blob = new Blob([res.data], {
                        type: "application/pdf",
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");
                }).catch((err) => console.log(err.response));
            },
            printPDF: async function () {
                if (this.articulosOrdered.length == 0 && this.cupsOrdered.length == 0 && this.servicioPropio
                    .length == 0 && this.data.Tiporden_id != 1) {
                    swal({
                        title: "Impresión",
                        text: "No se encuentran servicios o articulos que imprimir",
                        icon: "warning",
                    });
                    return;
                } else if (this.data.Tiporden_id == 1 && this.incapacidadOrdered.length == 0) {
                    swal({
                        title: "Impresión",
                        text: "No se encuentran incapacidades que imprimir",
                        icon: "warning",
                    });
                    return;
                } else {
                    await this.clearPropsBy();
                    await this.separateByAutorization();
                    let pdf = {};
                    let print = false;
                    switch (this.data.Tiporden_id) {
                        case 1:
                            pdf = await this.getPDFIncap();
                            await this.print(pdf);
                            if (this.recomendation) {
                                this.printRecomendation();
                            }
                            break;
                        case 3:
                            await axios.get(`/api/orden/getOrderedCodesumi/${this.datosCita.cita_paciente_id}`)
                                .then((res) => {
                                    res.data.forEach((data) => {
                                        let obj = {
                                            Num_Doc: this.paciente.Num_Doc,
                                            type: "formula",
                                            orden_id: data.id,
                                            medicamentos: [],
                                            mensaje: "F O R M U L A  M E D I C A",
                                            fecha_solicitud: data.Fechaorden,
                                        };
                                        let objMypres = {
                                            Num_Doc: this.paciente.Num_Doc,
                                            type: "formula",
                                            orden_id: data.id,
                                            medicamentos: [],
                                            mensaje: "M I P R E S",
                                            fecha_solicitud: data.Fechaorden,
                                        };
                                        let objAnexo3 = {
                                            type: 'Anexo',
                                            tipos: 'anexo3Medicamentos',
                                            medicamentos: [],
                                            id: data.id
                                        }
                                        data.detaarticulordens.forEach((s) => {
                                            if (parseInt(s.Estado_id) !== 15) {
                                                if (parseInt(s.Estado_id) === 37) {
                                                    objAnexo3.id = s.id,
                                                        objAnexo3.medicamentos.push({
                                                            Codigo: s.Codigo,
                                                            nombre: s.nombre,
                                                            Via: s.Via,
                                                            Cantidadosis: s.Cantidadosis,
                                                            Unidadmedida: s.Unidadmedida,
                                                            Frecuencia: s.Frecuencia,
                                                            Unidadtiempo: s.Unidadtiempo,
                                                            Duracion: s.Duracion,
                                                            Cantidadmensual: s
                                                                .Cantidadmensual,
                                                            Cantidadpormedico: s
                                                                .Cantidadpormedico,
                                                            Observacion: s.Observacion
                                                        })
                                                } else if (!s.mipres) {
                                                    obj.medicamentos.push({
                                                        Codigo: s.Codigo,
                                                        nombre: s.nombre,
                                                        Via: s.Via,
                                                        Cantidadosis: s.Cantidadosis,
                                                        Unidadmedida: s.Unidadmedida,
                                                        Frecuencia: s.Frecuencia,
                                                        Unidadtiempo: s.Unidadtiempo,
                                                        Duracion: s.Duracion,
                                                        Cantidadmensual: s
                                                            .Cantidadmensual,
                                                        Cantidadpormedico: s
                                                            .Cantidadpormedico,
                                                        Observacion: s.Observacion
                                                    })
                                                    if (parseInt(s.Estado_id) === 15) {
                                                        obj.mensaje =
                                                            'S I N  A U T O R I Z A C I O N'
                                                    }

                                                } else {
                                                    objMypres.medicamentos.push({
                                                        Codigo: s.Codigo,
                                                        nombre: s.nombre,
                                                        Via: s.Via,
                                                        Cantidadosis: s.Cantidadosis,
                                                        Unidadmedida: s.Unidadmedida,
                                                        Frecuencia: s.Frecuencia,
                                                        Unidadtiempo: s.Unidadtiempo,
                                                        Duracion: s.Duracion,
                                                        Cantidadmensual: s
                                                            .Cantidadmensual,
                                                        Cantidadpormedico: s
                                                            .Cantidadpormedico,
                                                        Observacion: s.Observacion
                                                    })
                                                }
                                            }
                                        });
                                        if (obj.medicamentos.length > 0) {
                                            this.print(obj);
                                        }
                                        if (objMypres.medicamentos.length > 0) {
                                            this.print(objMypres);
                                        }
                                        if (objAnexo3.medicamentos.length > 0) {
                                            this.print(objAnexo3);
                                        }

                                    });
                                });
                            // if (this.medicamentosByNo.length == 0) {
                            //     swal({
                            //         title: "Impresión",
                            //         text: "Se generó la orden pero los medicamentos requieren auditoria",
                            //         icon: "info"
                            //     });
                            //
                            //     break;
                            // }
                            // let ordens = [];
                            // await axios
                            //     .get(
                            //         `/api/orden/citapaciente/${
                            //         this.cita_paciente
                            //     }/ordens`
                            //     )
                            //     .then(res => (ordens = res.data));
                            // let [page, numMeses] = ordens[0].paginacion.split("/");
                            // let cant = ordens.length;
                            // this.getConducta();
                            // for (let i = 0; i < cant; i++) {
                            //     this.filterMedicamentosByNo = this.medicamentosByNo.filter(
                            //         medi =>
                            //         parseInt(page) <= parseInt(medi.NumMeses)
                            //     );
                            //     pdf = await this.getPDFFormula(
                            //         i + 1,
                            //         page,
                            //         numMeses,
                            //         ordens[i].id
                            //     );
                            //     page++;
                            //     await this.print(pdf);
                            // }
                            //
                            // if (this.recomendation) {
                            //     this.printRecomendation();
                            // }
                            break;
                        case 2:
                        case 4:
                            if (this.serviciosAnexo.length != 0) {
                                this.getConducta();
                                this.setUbicacion();
                                this.serviciosAnexo.forEach((ser) => {
                                    let pdf = {
                                        type: 'Anexo',
                                        tipos: 'anexo3Servicios',
                                        id: ser.id
                                    };
                                    this.print(pdf);
                                })
                            }
                            if (this.serviciosByNo.length == 0) {
                                swal({
                                    title: "Impresión",
                                    text: "Se generó la orden pero los servicios requieren auditoria o no estan vigentes",
                                    icon: "info",
                                });
                            }
                            this.getConducta();
                            this.setUbicacion();
                            let printPagesCup = {};
                            this.serviciosByNo.forEach((ser) => {
                                if (!printPagesCup.hasOwnProperty(ser.Ubicacion_id)) printPagesCup[ser
                                    .Ubicacion_id] = [];
                                printPagesCup[ser.Ubicacion_id].push(ser);
                            });
                            for (const p in printPagesCup) {
                                this.serviciosByNo = printPagesCup[p];
                                pdf = await this.getPDFOtros();
                                await this.print(pdf);
                            }
                            if (this.recomendation) {
                                this.printRecomendation();
                            }
                            break;
                        case 6:
                            if (this.servicioPropioByNo.length == 0) {
                                swal({
                                    title: "Impresión",
                                    text: "Se generó la orden pero los servicios requieren auditoria o no estan vigentes",
                                    icon: "info",
                                });
                            }
                            this.getConducta();
                            console.log("servicioPropioByNo", this.servicioPropioByNo);
                            let printPagesService = {};
                            this.servicioPropioByNo.forEach((ser) => {
                                if (!printPagesService.hasOwnProperty(ser.Ubicacion_id)) printPagesService[
                                    ser.Ubicacion_id] = [];
                                printPagesService[ser.Ubicacion_id].push(ser);
                            });
                            for (const p in printPagesService) {
                                this.serviciosByNo = printPagesService[p];
                                pdf = await this.getPDFOtros();
                                await this.print(pdf);
                            }
                            if (this.recomendation) {
                                this.printRecomendation();
                            }
                            break;
                    }
                }
            },
            printRecomendation() {
                let pdf = {};
                pdf = this.getPDFRecomendation();
                axios.post("/api/pdf/print-pdf", pdf, {
                    responseType: "arraybuffer",
                }).then((res) => {
                    let blob = new Blob([res.data], {
                        type: "application/pdf",
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");
                });
            },
            pushArticulo(cantidad = 0) {
                if(this.articulos.medicamento.insulina === "1"){
                    if(parseInt(this.articulos.Cantidadosis) <= parseInt(this.articulos.medicamento.cantidad_maxima_ordenar_dia)){
                        if (
                        parseInt(this.articulos.medicamento.Cantidadmaxordenar) >=
                        parseInt(this.articulos.Cantidadpormedico) + parseInt(cantidad)
                    ) {
                    if (this.articulos.Unidadtiempo == "Horas") {
                        if (
                            !this.articulos.Frecuencia ||
                            this.articulos.Frecuencia <= 0 ||
                            !this.articulos.Duracion ||
                            this.articulos.Duracion > 30
                        ) {
                            return;
                        }
                    }
                    this.data.articulos.push({
                        id: this.articulos.medicamento.id,
                        nombre: this.articulos.medicamento.Nombre,
                        Cantidadosis: this.articulos.Cantidadosis,
                        Unidadmedida: this.articulos.Unidadmedida,
                        Via: this.articulos.Via,
                        Frecuencia: this.articulos.Frecuencia,
                        Unidadtiempo: this.articulos.Unidadtiempo,
                        Duracion: this.articulos.Duracion,
                        Cantidadmensual: this.articulos.Cantidadmensual,
                        NumMeses: this.articulos.NumMeses,
                        Observacion: this.articulos.Observacion,
                        Requiere_autorizacion: this.articulos.medicamento
                            .Requiere_autorizacion,
                        Cantidadpormedico: this.articulos.Cantidadpormedico
                    });
                    this.clearDataArticulo();
                        } else {
                        swal({
                            title: "Cantidad formulada no permitida",
                            text: "Ingresar una cantidad menor en el campo 'cantidad mensual médico'",
                            icon: "error"
                        });
                    }
                    }else{
                        swal({
                            title: "Cantidad formulada no permitida",
                            text: "Ingresar una cantidad menor en el campo 'Cantidad'",
                            icon: "error"
                        });
                    }
                }else{
                    if (
                        parseInt(this.articulos.medicamento.Cantidadmaxordenar) >=
                        parseInt(this.articulos.Cantidadpormedico) + parseInt(cantidad)
                    ) {
                        if (this.articulos.Unidadtiempo == "Horas") {
                            if (
                                !this.articulos.Frecuencia ||
                                this.articulos.Frecuencia <= 0 ||
                                !this.articulos.Duracion ||
                                this.articulos.Duracion > 30
                            ) {
                                return;
                            }
                        }
                        this.data.articulos.push({
                            id: this.articulos.medicamento.id,
                            nombre: this.articulos.medicamento.Nombre,
                            Cantidadosis: this.articulos.Cantidadosis,
                            Unidadmedida: this.articulos.Unidadmedida,
                            Via: this.articulos.Via,
                            Frecuencia: this.articulos.Frecuencia,
                            Unidadtiempo: this.articulos.Unidadtiempo,
                            Duracion: this.articulos.Duracion,
                            Cantidadmensual: this.articulos.Cantidadmensual,
                            NumMeses: this.articulos.NumMeses,
                            Observacion: this.articulos.Observacion,
                            Requiere_autorizacion: this.articulos.medicamento
                                .Requiere_autorizacion,
                            Cantidadpormedico: this.articulos.Cantidadpormedico
                        });
                        this.clearDataArticulo();
                    } else {
                        swal({
                            title: "Cantidad formulada no permitida",
                            text: "Ingresar una cantidad menor en el campo 'cantidad mensual médico'",
                            icon: "error"
                        });
                    }
                }
            },
            pushProcedimiento(cantidad = 0) {
                if (parseInt(this.procedimiento.cup.Cantidadmaxordenar) >= parseInt(this.procedimiento.cantidad) +
                    parseInt(cantidad)) {
                    this.data.procedimientos.push({
                        id: this.procedimiento.cup.id,
                        codigo: this.procedimiento.cup.Codigo,
                        nombre: this.procedimiento.cup.Nombre,
                        auditoria: this.procedimiento.cup.Requiere_auditoria,
                        cantidad: this.procedimiento.cantidad,
                        observacion: this.procedimiento.observacion,
                        date: this.procedimiento.date,
                    });
                    this.clearDataProcedimiento();
                } else {
                    swal({
                        title: "Cantidad formulada no permitida",
                        text: "Ingresar una cantidad menor en el campo 'cantidad'",
                        icon: "error",
                    });
                }
            },
            pushServicioPropio(cantidad = 0) {
                if (parseInt(this.servicioPropio.service.Cantidadmaxordenar) >= parseInt(this.servicioPropio.cantidad) +
                    parseInt(cantidad)) {
                    this.data.serviciosPropios.push({
                        id: this.servicioPropio.service.id,
                        codigo: this.servicioPropio.service.Codigo,
                        nombre: this.servicioPropio.service.Nombre,
                        auditoria: this.servicioPropio.service.Requiere_autorizacion,
                        nivelordenamiento: this.servicioPropio.service.Nivel_ordenamiento,
                        cantidad: this.servicioPropio.cantidad,
                        observacion: this.servicioPropio.observacion,
                    });
                    this.clearDataServicioPropio();
                } else {
                    swal({
                        title: "Cantidad formulada no permitida",
                        text: "Ingresar una cantidad menor en el campo 'cantidad'",
                        icon: "error",
                    });
                }
            },
            removeArticulo(articulo) {
                this.data.articulos.splice(articulo.index, 1);
            },
            removeProcedimiento(procedimientos) {
                this.data.procedimientos.splice(procedimientos.index, 1);
            },
            removeServicioPropio(servicioPropio) {
                this.data.serviciosPropios.splice(servicioPropio.index, 1);
            },
            separateByAutorization: async function () {
                if (this.data.Tiporden_id == 2 || this.data.Tiporden_id == 4) await this.getCupOrden();
                if (this.data.Tiporden_id == 3) await this.getDetaArticulo();
                if (this.data.Tiporden_id == 6) await this.getServicioPropio();
            },
            setUbicacion() {
                let ubicacion = " ";
                if (this.ubicaciones) {
                    this.data.procedimientos.map((procedimiento) => {
                        let object = this.ubicaciones.find((ubicacion) => ubicacion.tarifacup_id ==
                            procedimiento.id);
                        procedimiento.cantidad = procedimiento.cantidad;
                        procedimiento.id = procedimiento.id;
                        procedimiento.descripcion = procedimiento.descripcion;
                        procedimiento.codigo = procedimiento.codigo;
                        procedimiento.nombre = procedimiento.nombre;
                        procedimiento.observacion = procedimiento.observacion;
                        if (object) {
                            procedimiento.ubicacion = `${object.nombre_prestador}`;
                            procedimiento.direccion = `${object.direccion}`;
                            procedimiento.telefono = `${object.telefono}`;
                        } else {
                            procedimiento.ubicacion = ubicacion;
                            procedimiento.direccion = ubicacion;
                            procedimiento.telefono = ubicacion;
                        }
                    });
                } else {
                    this.data.procedimientos.map((procedimiento) => {
                        procedimiento.cantidad = procedimiento.cantidad;
                        procedimiento.id = procedimiento.id;
                        procedimiento.descripcion = procedimiento.descripcion;
                        procedimiento.codigo = procedimiento.codigo;
                        procedimiento.nombre = procedimiento.nombre;
                        procedimiento.observacion = procedimiento.observacion;
                        procedimiento.ubicacion = ubicacion;
                        procedimiento.direccion = ubicacion;
                        procedimiento.telefono = ubicacion;
                    });
                }
            },
            showError(message) {
                swal({
                    title: "¡No puede ser!",
                    text: `${message}`,
                    icon: "warning",
                });
            },
            toggleAll() {
                if (this.data.codeSumiEsquemaSelected.length) this.data.codeSumiEsquemaSelected = [];
                else this.data.codeSumiEsquemaSelected = this.codeSumiEsquema.slice();
            },
            tipoOrdenSelected: async function () {
                switch (this.data.Tiporden_id) {
                    case 1:
                        this.fetchIncOrdenred();
                        //this.fetchColegios()
                        break;
                    case 2:
                        this.fetchCupsInterconsultas();
                        this.fetchCupsOrdered();
                        break;
                    case 3:
                        await this.fetchMedicamentosOrdered();
                        this.fetchMedicamentos();
                        this.historicoOrdenes();
                        break;
                    case 4:
                        this.fetchCupsOtherService();
                        this.fetchCupsOrdered();
                        break;
                    case 6:
                        this.fetchServiciosPropios();
                        this.fetchServiciosPropiosOrdered();
                        break;
                    case 7:
                        await this.fetchBodySurfaceIndex();
                        this.fetchEnableEsquemasOrdered();
                        this.fetchEnableEsquemas();
                        break;
                    default:
                        break;
                }
            },
            imprimirRecomendacion(codigo) {
                axios.get("/api/orden/recomendaciones/" + codigo).then((res) => {
                    if (res.data.file.length > 0) {
                        res.data.file.forEach((s) => {
                            const a = document.createElement("a");
                            a.href = s.nombre;
                            a.download = s.nombre.split("/").pop();
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        });
                    }
                });
            },
            datosPacienteOrdenamiento() {
                axios.post('/api/historia/datosPaciente', this.datosCita).then((res) => {
                    this.paciente = res.data;
                });
            },

            historicoOrdenes() {
                axios.post('/api/historia/historicoOrdenes', this.datosCita).then((res) => {
                    this.historicoMedicamentos = res.data;
                });
            },


            abrirModalSuspender(items) {
                console.log(items)
                this.dialogSuspender = true
                this.orden = items
            },

            suspenderMedicamento() {
                this.dialogSuspender = true
                axios.post('/api/historia/suspenderMedicamento', this.orden).then((res) => {
                    this.$alerSuccess('Suspendido con Exito');
                    this.historicoOrdenes();
                    this.dialogSuspender = false
                }).catch(err => {
                    this.$alerError('El motivo debe contener al menos 5 caracteres.');
                })

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

            conciliarMedicamento(items) {
                this.compoM = 'OrdenamientoConciliacionComponent'
                this.dialogConciliacion = true
                this.datosMedicamentos = items
            },
            validar() {
                if (this.articulos.medicamento.insulina === '1') {
                    this.articulos.Unidadmedida = "Unidad Internacional"
                }
            }
        },
    };

</script>
<style lang="stylus" scoped>
    .container {
        max-width: inherit;
    }

</style>
