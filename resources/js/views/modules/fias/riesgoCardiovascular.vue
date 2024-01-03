<template>
    <div>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout>
                <v-flex xs12 sm12 md12>
                    <v-card>
                        <v-card-text class="headline success">
                            <h4 style="color:white">Entidad</h4>
                        </v-card-text>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs12 sm5 md3>
                                    <v-select v-model="consultaFiltrada.entidad" :items="items" item-value="id"
                                        label="Entidad">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm5 md4>
                                    <v-autocomplete v-model="consultaFiltrada.sede_id" append-icon="search"
                                        :items="sedesIPS" item-text="Nombre" item-value="id" label="sedes">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm1 md1>
                                    <v-btn outline fab color="teal" @click="consultar()">
                                        <v-icon>mdi-search-web</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout>
                <v-flex v-for="ref in referencias" :key="ref.color" d-flex lg3 sm6 xs12>
                    <Widget :color="ref.color" :icon="ref.icon" :title="ref.title" :subTitle="ref.subTitle"
                        :supTitle="ref.supTitle" />
                </v-flex>
            </v-layout>
            <v-layout>
                <v-flex xs12 sm12 md12>
                    <v-card>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs12 sm12 md12>
                                    <v-card-title>
                                        <v-icon v-if="can('addUserRcv.add')" large color="green darken-2">
                                            mdi-account-plus
                                        </v-icon>
                                        <v-btn v-if="can('UploadRcv.upload')" color="primary" dark>Carga de Plantilla
                                            <v-icon dark right>backup</v-icon>
                                        </v-btn>
                                        <v-btn v-if="can('reporteRcv.report')" color="success" dark @click="reportRcv">
                                            Reporte
                                            <v-icon dark right>mdi-clipboard-arrow-down</v-icon>
                                        </v-btn>
                                        <v-btn v-if="can('fiasF4.report')" color="purple" dark @click="estadistica()">
                                            Estadistica
                                            <v-icon dark right>mdi-chart-line</v-icon>
                                        </v-btn>
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="search" append-icon="search" label="Search" single-line
                                            hide-details></v-text-field>
                                    </v-card-title>
                                    <div>
                                        <v-card>
                                            <v-card-title>
                                                <v-spacer></v-spacer>
                                                <v-text-field v-model="search" append-icon="search" label="Search"
                                                    single-line hide-details></v-text-field>
                                            </v-card-title>
                                            <v-data-table :headers="headers" :items="rcvData" :search="search">
                                                <template v-slot:items="props">
                                                    <td>{{props.item.Num_Doc}}</td>
                                                    <td>{{props.item.Nombre}}</td>
                                                    <td>{{props.item.dx_hta}}</td>
                                                    <td>{{props.item.dx_dm}}</td>
                                                    <td>{{props.item.clasificacion_imc}}</td>
                                                    <td>{{props.item.tension_arterial_sistolica}}</td>
                                                    <td>{{props.item.tension_arterial_diastolica}}</td>
                                                    <td>
                                                        <v-tooltip top>
                                                            <template v-slot:activator="{ on }">
                                                                <v-btn small text icon color="warning" dark v-on="on">
                                                                    <v-icon @click="modalFias(props.item)">
                                                                        mdi-message-draw
                                                                    </v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>Ver paciente</span>
                                                        </v-tooltip>
                                                        <v-tooltip top>
                                                            <template v-slot:activator="{ on }">
                                                                <v-btn small text icon color="success" dark v-on="on">
                                                                    <v-icon @click="modalHSignos(props.item.Num_Doc)">
                                                                        mdi-loop
                                                                    </v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>Historicos signos vitales</span>
                                                        </v-tooltip>
                                                    </td>
                                                </template>
                                                <template v-slot:no-results>
                                                    <v-alert :value="true" color="error" icon="warning">
                                                        Your search for "{{ search }}" found no results.
                                                    </v-alert>
                                                </template>
                                            </v-data-table>
                                        </v-card>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex>
            </v-layout>
            <!-- MODAL FORMULARIO DE EDICIÓN DE FIAS -->
            <v-layout row justify-center>
                <v-dialog v-model="dialog" persistent max-width="1200px">
                    <v-card>
                        <v-card-text class="headline success">
                            <h4 style="color:white">Form Fias</h4>
                        </v-card-text>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="Cedula" v-model="rcv.Num_Doc" readonly></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field label="IPS" v-model="rcv.Nombre" readonly></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="programa_nefroporteccion"
                                            v-model="rcv.programa_nefroporteccion" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="dx_hta" v-model="rcv.dx_hta" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_dx_hta" v-model="rcv.fecha_dx_hta"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="costo_hta" v-model="rcv.costo_hta" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="dx_dm" v-model="rcv.dx_dm" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_dx_dm" v-model="rcv.fecha_dx_dm"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="costo_dm" v-model="rcv.costo_dm" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="causa_erc" v-model="rcv.causa_erc" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="peso" v-model="rcv.peso" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="talla" v-model="rcv.talla" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="imc" v-model="rcv.imc" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="clasificacion_imc" v-model="rcv.clasificacion_imc"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="perimetro_abdominal" v-model="rcv.perimetro_abdominal"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="tension_arterial_sistolica"
                                            v-model="rcv.tension_arterial_sistolica" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="tension_arterial_diastolica"
                                            v-model="rcv.tension_arterial_diastolica" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="escala_framinghan" v-model="rcv.escala_framinghan"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_escala_framinghan"
                                            v-model="rcv.fecha_escala_framinghan" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="resultado_diabetes_escala_findrisk"
                                            v-model="rcv.resultado_diabetes_escala_findrisk" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_escala_findrisk"
                                            v-model="rcv.fecha_escala_findrisk" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="resultado_rcv_control" v-model="rcv.resultado_rcv_control"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_tamizaje_rcv"
                                            v-model="rcv.fecha_tamizaje_rcv" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="resultado_riesgo_diabetes"
                                            v-model="rcv.resultado_riesgo_diabetes" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_tamizaje_riesgo_diabetes"
                                            v-model="rcv.fecha_tamizaje_riesgo_diabetes" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="escala_kaiser" v-model="rcv.escala_kaiser" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="dislipidemia" v-model="rcv.dislipidemia" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="factores_riesgo" v-model="rcv.factores_riesgo" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="falla_organo_blaco" v-model="rcv.falla_organo_blaco"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="creatinina" v-model="rcv.creatinina" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_creatinina"
                                            v-model="rcv.fecha_creatinina" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="hba1c" v-model="rcv.hba1c" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_hba1c" v-model="rcv.fecha_hba1c"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="albuminuria" v-model="rcv.albuminuria" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_albuminuria"
                                            v-model="rcv.fecha_albuminuria" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="albuminuria_creatinuria"
                                            v-model="rcv.albuminuria_creatinuria" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_albuminuria_creatinuria"
                                            v-model="rcv.fecha_albuminuria_creatinuria" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="colesterol" v-model="rcv.colesterol" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_colesterol"
                                            v-model="rcv.fecha_colesterol" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="hdl" v-model="rcv.hdl" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_hdl" v-model="rcv.fecha_hdl" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="ldl" v-model="rcv.ldl" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_ldl" v-model="rcv.fecha_ldl" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="trigliceridos" v-model="rcv.trigliceridos" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_trigliceridos"
                                            v-model="rcv.fecha_trigliceridos" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="glicemias" v-model="rcv.glicemias" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_glicemia" v-model="rcv.fecha_glicemia"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="pth" v-model="rcv.pth" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_pth" v-model="rcv.fecha_pth" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="tfg" v-model="rcv.tfg" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_tfg" v-model="rcv.fecha_tfg" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="ekg" v-model="rcv.ekg" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_ekg" v-model="rcv.fecha_ekg" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibe_ieca" v-model="rcv.recibe_ieca" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibe_arados" v-model="rcv.recibe_arados" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibe_hipoglicemiantes"
                                            v-model="rcv.recibe_hipoglicemiantes" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="actividad_fisica" v-model="rcv.actividad_fisica" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_prescripcion_actividad_fisica"
                                            v-model="rcv.fecha_prescripcion_actividad_fisica" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="atencion_recibida" v-model="rcv.atencion_recibida"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_educacion" v-model="rcv.fecha_educacion"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="tema" v-model="rcv.tema" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="medicina_general_y_o_experto"
                                            v-model="rcv.medicina_general_y_o_experto" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="medicina_interna_familiar"
                                            v-model="rcv.medicina_interna_familiar" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="medicina_geriatrica" v-model="rcv.medicina_geriatrica"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="oftalmologia" v-model="rcv.oftalmologia" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="nutricion" v-model="rcv.nutricion" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="enfermeria" v-model="rcv.enfermeria" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="trabajo_social" v-model="rcv.trabajo_social" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="dx_erc" v-model="rcv.dx_erc" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="estadio_erc" v-model="rcv.estadio_erc" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_dx_erc_5" v-model="rcv.fecha_dx_erc_5"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="programa_seguimiento_erc_hta_dm"
                                            v-model="rcv.programa_seguimiento_erc_hta_dm" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="tfg_inicio_trr" v-model="rcv.tfg_inicio_trr" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="modo_inicio_trr" v-model="rcv.modo_inicio_trr" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_inicio_trr"
                                            v-model="rcv.fecha_inicio_trr" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="ingreso_unidad_renal" v-model="rcv.ingreso_unidad_renal"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="hemodialisis" v-model="rcv.hemodialisis" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="dosis_dialisis_ktv" v-model="rcv.dosis_dialisis_ktv"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="costo_hemodialisis" v-model="rcv.costo_hemodialisis"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="dialisis_peritoneal" v-model="rcv.dialisis_peritoneal"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="dosis_dialisis_ktv_dpd"
                                            v-model="rcv.dosis_dialisis_ktv_dpd" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="numero_horas_hemodialisis"
                                            v-model="rcv.numero_horas_hemodialisis" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="peritonitis" v-model="rcv.peritonitis" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="costo_dp" v-model="rcv.costo_dp" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="vacuna_hepatitisb" v-model="rcv.vacuna_hepatitisb"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_dx_hepatitisb"
                                            v-model="rcv.fecha_dx_hepatitisb" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_dx_hepatitis_c"
                                            v-model="rcv.fecha_dx_hepatitis_c" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="rerapia_no_dialitica_erc_5"
                                            v-model="rcv.rerapia_no_dialitica_erc_5" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="costo_terapia_erc_5" v-model="rcv.costo_terapia_erc_5"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="hemoglobina" v-model="rcv.hemoglobina" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="albumina_servica" v-model="rcv.albumina_servica" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="fosforo" v-model="rcv.fosforo" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="valoracio_nefrología" v-model="rcv.valoracio_nefrología"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="cancer_activo_12_meses"
                                            v-model="rcv.cancer_activo_12_meses" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="infeccion_cronica_activa_no_tratada"
                                            v-model="rcv.infeccion_cronica_activa_no_tratada" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="no_manifestado_deseo_trasplantarse"
                                            v-model="rcv.no_manifestado_deseo_trasplantarse" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="contraindicacion_esperanza_vida_menor_igual_6_meses"
                                            v-model="rcv.contraindicacion_esperanza_vida_menor_igual_6_meses" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="autocuidado_adherencia_tratamiento_posttrasplante"
                                            v-model="rcv.autocuidado_adherencia_tratamiento_posttrasplante" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="enfermedad_cardiaca_cerebrovascular_vascular"
                                            v-model="rcv.enfermedad_cardiaca_cerebrovascular_vascular" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="contraindicacion_infeccion_vih"
                                            v-model="rcv.contraindicacion_infeccion_vih" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="contraindicacion_infeccion_vhc"
                                            v-model="rcv.contraindicacion_infeccion_vhc" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="contraindicacion_enfermedad_inmunologica_activa"
                                            v-model="rcv.contraindicacion_enfermedad_inmunologica_activa" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="contraindicacion_enfermedad_pulmonar_cronica"
                                            v-model="rcv.contraindicacion_enfermedad_pulmonar_cronica" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="contraindicacion_otras_enfermedades_cronicas"
                                            v-model="rcv.contraindicacion_otras_enfermedades_cronicas" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_realizacion_trasplante"
                                            v-model="rcv.fecha_realizacion_trasplante" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="ips_donde_espera" v-model="rcv.ips_donde_espera" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibido_trasplante_renal"
                                            v-model="rcv.recibido_trasplante_renal" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="eps_realizo_trasplante"
                                            v-model="rcv.eps_realizo_trasplante" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="ips_realizo_trasplante"
                                            v-model="rcv.ips_realizo_trasplante" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="tipo_donante" v-model="rcv.tipo_donante" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="costo_trasplante" v-model="rcv.costo_trasplante" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="presentado_complicacion_con_el_trasplante"
                                            v-model="rcv.presentado_complicacion_con_el_trasplante" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_dx_citomegalovirus"
                                            v-model="rcv.fecha_dx_citomegalovirus" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_dx_infeccion_hongos"
                                            v-model="rcv.fecha_dx_infeccion_hongos" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_dx_presentado_infeccion_tuberculosis"
                                            v-model="rcv.fecha_dx_presentado_infeccion_tuberculosis" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_dx_complicacion_cardiovascular"
                                            v-model="rcv.fecha_dx_complicacion_cardiovascular" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_dx_complicacion_urologica"
                                            v-model="rcv.fecha_dx_complicacion_urologica" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_dx_complicacion_herida_quirurgica"
                                            v-model="rcv.fecha_dx_complicacion_herida_quirurgica" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_primer_dx_cancer"
                                            v-model="rcv.fecha_primer_dx_cancer" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="medicamentos_inmunosupresores"
                                            v-model="rcv.medicamentos_inmunosupresores" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibio_metilprednisolona"
                                            v-model="rcv.recibio_metilprednisolona" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibio_azatriopina" v-model="rcv.recibio_azatriopina"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibio_ciclosporina" v-model="rcv.recibio_ciclosporina"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibio_micofenalato" v-model="rcv.recibio_micofenalato"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibio_tracolimus" v-model="rcv.recibio_tracolimus"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibio_prednisona" v-model="rcv.recibio_prednisona"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibio_mx_no_pos_1" v-model="rcv.recibio_mx_no_pos_1"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibio_mx_no_pos_2" v-model="rcv.recibio_mx_no_pos_2"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="recibio_mx_no_pos_3" v-model="rcv.recibio_mx_no_pos_3"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="episodios_rechazo" v-model="rcv.episodios_rechazo"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_primer_episodios_rechazo_agudo"
                                            v-model="rcv.fecha_primer_episodios_rechazo_agudo" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_retorno_dialisis"
                                            v-model="rcv.fecha_retorno_dialisis" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="numero_trasplantes_renales"
                                            v-model="rcv.numero_trasplantes_renales" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="costo_terapia_postrasplante"
                                            v-model="rcv.costo_terapia_postrasplante" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="meses_prestacion_servicios"
                                            v-model="rcv.meses_prestacion_servicios" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="costo_total" v-model="rcv.costo_total" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="eps_origen" v-model="rcv.eps_origen" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="novedad" v-model="rcv.novedad" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="causa_muerte" v-model="rcv.causa_muerte" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_muerte" v-model="rcv.fecha_muerte"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="codigo_bdua" v-model="rcv.codigo_bdua" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_corte" v-model="rcv.fecha_corte"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_inicio_tratamiento_enfermedad"
                                            v-model="rcv.fecha_inicio_tratamiento_enfermedad" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="Hospitalizacion" v-model="rcv.Hospitalizacion" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field type="date" label="fecha_hospitalizacion"
                                            v-model="rcv.fecha_hospitalizacion" required></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" dark @click="dialog = false">Cerrar</v-btn>
                            <v-btn color="success" dark @click="dialog = false">Actualizar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
            <!-- MODAL FORMULARIO DE EDICIÓN DE FIAS -->
            <v-layout row justify-center>
                <v-dialog fullscreen hide-overlay transition="dialog-bottom-transition" scrollable
                    v-model="modalHistoricoSignos" persistent>
                    <v-card>
                        <v-card-text class="headline success">
                            <h4 style="color:white">Historial Medidas Antopometricas del Paciente
                                {{datosPacienteHistorico.Primer_Nom}} {{datosPacienteHistorico.SegundoNom}}
                                {{datosPacienteHistorico.Primer_Ape}} {{datosPacienteHistorico.Segundo_Ape}} </h4>
                        </v-card-text>
                        <v-card-text>
                            <v-container fluid pa-1 grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12 sm12 md12>
                                        <v-data-table :headers="headersHistorico" :items="allSignosVitales"
                                            :search="search" hide-actions :pagination.sync="pagination"
                                            class="elevation-1">
                                            <template v-slot:items="props">
                                                <td>{{ props.item.Peso }}</td>
                                                <td>{{ props.item.Talla }}</td>
                                                <td>{{ props.item.Imc }}</td>
                                                <td>{{ props.item.Clasificacion }}</td>
                                                <td>{{ props.item.Presionsistolica }}</td>
                                                <td>{{ props.item.Presiondiastolica }}</td>
                                                <td>{{ props.item.Presionarterialmedia }}</td>
                                            </template>
                                        </v-data-table>
                                        <div class="text-xs-center pt-2">
                                            <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" dark @click="modalHistoricoSignos = false">Cerrar</v-btn>
                            <v-btn color="success" dark @click="modalHistoricoSignos = false">Actualizar</v-btn>
                        </v-card-actions>
                    </v-card>

                </v-dialog>
            </v-layout>
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
        </v-container>
        <!-- MODAL DE GRILLA PROGRAMAS ESPECIALES -->
        <v-dialog v-model="dialogEstadistica" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
            <v-card tile>
                <v-toolbar card dark color="primary">
                    <v-btn icon dark @click="dialogEstadistica = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Estadistica Programas especiales</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-card-text>
                    <v-flex xs12 sm12 md12>
                        <iframe title="7 Programas especiales - Menú " width="1324px" height="804"
                            src="https://app.powerbi.com/view?r=eyJrIjoiMDgwNTdjMTYtZmJiMC00ZjEwLTgwNzktOTAyMjgzYjg4M2VkIiwidCI6IjQ4NzRlMWJhLTU2ZGItNDc5Mi1iMDUyLTRlMmUyOWJlMjk5MyJ9"
                            frameborder="0" allowFullScreen="true"></iframe>
                    </v-flex>
                </v-card-text>
                <div style="flex: 1 1 auto;"></div>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    import moment from 'moment';
    import Widget from '../../../components/referencia/Widget.vue'
    import {
        mapGetters
    } from "vuex";
    moment.locale('es');
    export default {
        components: {
            Widget,
        },
        data() {
            return {
                items: ['REDVITAL UT', 'FERROCARRILES NACIONALES'],
                dialog: false,
                preload: false,
                modalHistoricoSignos: false,
                currentPage: 1,
                pages: 1,
                search: '',
                pagination: {},
                selected: [],
                headers: [{
                        text: 'CC - Paciente',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'IPS'
                    },
                    {
                        text: 'hipertenso'
                    },
                    {
                        text: 'Diabetico'
                    },
                    {
                        text: 'IMC'
                    },
                    {
                        text: 'Sistolica'
                    },
                    {
                        text: 'Diastolica'
                    },
                    {
                        text: 'Accion'
                    },
                ],
                headersHistorico: [{
                        text: 'Peso',
                        value: 'Peso'
                    },
                    {
                        text: 'Talla'
                    },
                    {
                        text: 'Imc'
                    },
                    {
                        text: 'Clasificacion'
                    },
                    {
                        text: 'Presion sistolica'
                    },
                    {
                        text: 'Presion diastolica'
                    },
                    {
                        text: 'Presion arterial media'
                    }
                ],
                rcvData: [],
                rcv: {
                    Num_Doc: '',
                    Nombre: '',
                    programa_nefroporteccion: '',
                    dx_hta: '',
                    fecha_dx_hta: '',
                    costo_hta: '',
                    dx_dm: '',
                    fecha_dx_dm: '',
                    costo_dm: '',
                    causa_erc: '',
                    peso: '',
                    talla: '',
                    imc: '',
                    clasificacion_imc: '',
                    perimetro_abdominal: '',
                    tension_arterial_sistolica: '',
                    tension_arterial_diastolica: '',
                    escala_framinghan: '',
                    fecha_escala_framinghan: '',
                    resultado_diabetes_escala_findrisk: '',
                    fecha_escala_findrisk: '',
                    resultado_rcv_control: '',
                    fecha_tamizaje_rcv: '',
                    resultado_riesgo_diabetes: '',
                    fecha_tamizaje_riesgo_diabetes: '',
                    escala_kaiser: '',
                    dislipidemia: '',
                    factores_riesgo: '',
                    falla_organo_blaco: '',
                    creatinina: '',
                    fecha_creatinina: '',
                    hba1c: '',
                    fecha_hba1c: '',
                    albuminuria: '',
                    fecha_albuminuria: '',
                    albuminuria_creatinuria: '',
                    fecha_albuminuria_creatinuria: '',
                    colesterol: '',
                    fecha_colesterol: '',
                    hdl: '',
                    fecha_hdl: '',
                    ldl: '',
                    fecha_ldl: '',
                    trigliceridos: '',
                    fecha_trigliceridos: '',
                    glicemias: '',
                    fecha_glicemia: '',
                    pth: '',
                    fecha_pth: '',
                    tfg: '',
                    fecha_tfg: '',
                    ekg: '',
                    fecha_ekg: '',
                    recibe_ieca: '',
                    recibe_arados: '',
                    recibe_hipoglicemiantes: '',
                    actividad_fisica: '',
                    fecha_prescripcion_actividad_fisica: '',
                    atencion_recibida: '',
                    fecha_educacion: '',
                    tema: '',
                    medicina_general_y_o_experto: '',
                    medicina_interna_familiar: '',
                    medicina_geriatrica: '',
                    oftalmologia: '',
                    nutricion: '',
                    enfermeria: '',
                    trabajo_social: '',
                    dx_erc: '',
                    estadio_erc: '',
                    fecha_dx_erc_5: '',
                    programa_seguimiento_erc_hta_dm: '',
                    tfg_inicio_trr: '',
                    modo_inicio_trr: '',
                    fecha_inicio_trr: '',
                    ingreso_unidad_renal: '',
                    hemodialisis: '',
                    dosis_dialisis_ktv: '',
                    costo_hemodialisis: '',
                    dialisis_peritoneal: '',
                    dosis_dialisis_ktv_dpd: '',
                    numero_horas_hemodialisis: '',
                    peritonitis: '',
                    costo_dp: '',
                    vacuna_hepatitisb: '',
                    fecha_dx_hepatitisb: '',
                    fecha_dx_hepatitis_c: '',
                    rerapia_no_dialitica_erc_5: '',
                    costo_terapia_erc_5: '',
                    hemoglobina: '',
                    albumina_servica: '',
                    fosforo: '',
                    valoracio_nefrología: '',
                    cancer_activo_12_meses: '',
                    infeccion_cronica_activa_no_tratada: '',
                    no_manifestado_deseo_trasplantarse: '',
                    contraindicacion_esperanza_vida_menor_igual_6_meses: '',
                    autocuidado_adherencia_tratamiento_posttrasplante: '',
                    enfermedad_cardiaca_cerebrovascular_vascular: '',
                    contraindicacion_infeccion_vih: '',
                    contraindicacion_infeccion_vhc: '',
                    contraindicacion_enfermedad_inmunologica_activa: '',
                    contraindicacion_enfermedad_pulmonar_cronica: '',
                    contraindicacion_otras_enfermedades_cronicas: '',
                    fecha_realizacion_trasplante: '',
                    ips_donde_espera: '',
                    recibido_trasplante_renal: '',
                    eps_realizo_trasplante: '',
                    ips_realizo_trasplante: '',
                    tipo_donante: '',
                    costo_trasplante: '',
                    presentado_complicacion_con_el_trasplante: '',
                    fecha_dx_citomegalovirus: '',
                    fecha_dx_infeccion_hongos: '',
                    fecha_dx_presentado_infeccion_tuberculosis: '',
                    fecha_dx_complicacion_cardiovascular: '',
                    fecha_dx_complicacion_urologica: '',
                    fecha_dx_complicacion_herida_quirurgica: '',
                    fecha_primer_dx_cancer: '',
                    medicamentos_inmunosupresores: '',
                    recibio_metilprednisolona: '',
                    recibio_azatriopina: '',
                    recibio_ciclosporina: '',
                    recibio_micofenalato: '',
                    recibio_tracolimus: '',
                    recibio_prednisona: '',
                    recibio_mx_no_pos_1: '',
                    recibio_mx_no_pos_2: '',
                    recibio_mx_no_pos_3: '',
                    episodios_rechazo: '',
                    fecha_primer_episodios_rechazo_agudo: '',
                    fecha_retorno_dialisis: '',
                    numero_trasplantes_renales: '',
                    costo_terapia_postrasplante: '',
                    meses_prestacion_servicios: '',
                    costo_total: '',
                    eps_origen: '',
                    novedad: '',
                    causa_muerte: '',
                    fecha_muerte: '',
                    codigo_bdua: '',
                    fecha_corte: '',
                    fecha_inicio_tratamiento_enfermedad: '',
                    Hospitalizacion: '',
                    fecha_hospitalizacion: '',
                },

                referencias: [{
                        color: '#00b297',
                        icon: 'mdi-pill',
                        title: '0',
                        subTitle: 'Hipertension'
                    },
                    {
                        color: '#ff9c36',
                        icon: 'mdi-needle',
                        title: '0',
                        subTitle: 'Diabetes'
                    },
                    {
                        color: '#d300009c',
                        icon: 'mdi-alert-outline',
                        title: '0',
                        subTitle: 'Hta + Dm'
                    },
                    {
                        color: '#0074a6',
                        icon: 'mdi-checkbox-marked-circle-outline',
                        title: '0',
                        subTitle: 'Creatinina',
                    },
                    {
                        color: '#e58b8b',
                        icon: 'mdi-heart-pulse',
                        title: '0',
                        subTitle: 'Sistolica'
                    },
                    {
                        color: '#697bd5c9',
                        icon: 'mdi-lightbulb-on',
                        title: '0',
                        subTitle: 'Diastolica'
                    }
                ],
                dialogEstadistica: false,
                consultaFiltrada: {
                    entidad: '',
                    f_inicio: '',
                    f_final: '',
                    sede_id: ''
                },
                sedesIPS: [],
                historicoSignos: {
                    cedula: ''
                },
                allSignosVitales: [],
                datosPacienteHistorico: []
            }
        },
        mounted() {
            this.sedes();
        },
        computed: {
            ...mapGetters(['can']),
        },
        methods: {
            fiasCacRcv() {
                this.preload = true;
                axios.post('/api/fias/allCacRcv', this.consultaFiltrada)
                    .then(res => {
                        this.rcvData = res.data;
                        this.preload = false;
                    }).catch(e => {
                        console.log(e);
                        this.preload = false;
                    })
            },
            async reportRcv() {
                this.preload = true;
                axios({
                    method: 'post',
                    url: '/api/fias/reportRcv',
                    responseType: 'blob'
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `Prueba.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.preload = false;

                }).catch(err => {
                    this.preload = false;
                    this.showMessage('No hay data')
                })
            },
            modalFias(items) {
                this.dialog = true
                this.rcv = items
            },
            estadistica() {
                this.dialogEstadistica = true
            },
            consultar() {
                axios.post('/api/fias/consulta', this.consultaFiltrada).then(res => {
                    this.referencias[0].title = `${res.data.hipertension}`;
                    this.referencias[1].title = `${res.data.diabetes}`;
                    this.referencias[2].title = `${res.data.htaDm}`;
                    this.referencias[3].title = `${res.data.creatinina}`;
                    this.referencias[4].title = `${res.data.sistolica}`;
                    this.referencias[5].title = `${res.data.diastolica}`;
                });
                this.fiasCacRcv()
            },
            sedes() {
                axios.get('/api/fias/sedes').then(res => {
                    this.sedesIPS = res.data
                })
            },
            modalHSignos(Num_Doc) {
                this.historicoSignos.cedula = Num_Doc
                axios.post('/api/fias/historicoSignos/', this.historicoSignos).then(res => {
                    this.modalHistoricoSignos = true
                    this.allSignosVitales = res.data
                    this.HistorialSignosPaciente()
                });
            },
            HistorialSignosPaciente() {
                axios.post('/api/fias/histoPacienteVital/', this.historicoSignos).then(res => {
                    this.datosPacienteHistorico = res.data
                });
            }
        }
    }

</script>
