<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                <span class="title layout justify-center font-weight-light">Antecedentes ginecoobstetricos</span>
            </v-card-title>
            <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxMenarquia" value="1" label="Menarquia">
                    </v-checkbox>
                    <v-select v-model="ginecologico.presente_menarquia" v-if="ginecologico.checkboxMenarquia == true"
                        :items="sino" label="Presente">
                    </v-select>
                    <v-text-field type="number" v-model="ginecologico.edad"
                        v-if="ginecologico.presente_menarquia == 'Si' & ginecologico.checkboxMenarquia == true"
                        label="Edad">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxCiclos" value="1"
                        label="Ciclos Menstruales "></v-checkbox>
                    <v-select v-model="ginecologico.clasificacion_ciclos" v-if="ginecologico.checkboxCiclos == true"
                        :items="clasificacion" label="Clasificación">
                    </v-select>
                    <v-select v-model="ginecologico.frecuencia_ciclos" v-if="ginecologico.checkboxCiclos == true"
                        :items="frecuencia" label="Frecuencia">
                    </v-select>
                    <v-select v-model="ginecologico.duracion_ciclos" v-if="ginecologico.checkboxCiclos == true"
                        :items="duracion" label="Duracion">
                    </v-select>
                </v-flex>
                <!--  <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxFlujovaginal" value="1"
                        label="Flujo Vaginal "></v-checkbox>
                    <v-select v-model="ginecologico.presente_flujovaginal"
                        v-if="ginecologico.checkboxFlujovaginal == true" :items="sino" label="Presente">
                    </v-select>
                    <v-textarea name="input-7-1" v-model="ginecologico.descripcion_flujovaginal"
                        v-if="ginecologico.checkboxFlujovaginal == true" label="Descripcion">
                    </v-textarea>
                    <v-textarea name="input-7-1" v-model="ginecologico.tratamiento_flujovaginal"
                        v-if="ginecologico.checkboxFlujovaginal == true" label="Tratamiento">
                    </v-textarea>
                    <v-text-field type="date" v-model="ginecologico.fecha_flujovaginal"
                        v-if="ginecologico.checkboxFlujovaginal == true & ginecologico.presente_flujovaginal=='Si'"
                        label="Fecha Diagnostico">
                    </v-text-field>
                </v-flex> -->

                <v-flex xs12 sm6 md3>
                    <v-text-field type="date" v-model="ginecologico.fecha_ultimaMenstruacion" :items="sino"
                        label="Fecha última menstruación">
                    </v-text-field>
                </v-flex>

                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxTransmisionsexual" value="1"
                        label="Antecedentes de ITS"></v-checkbox>
                    <v-select v-model="ginecologico.presente_transmisionsexual"
                        v-if="ginecologico.checkboxTransmisionsexual == true" :items="sino" label="Presente">
                    </v-select>
                    <v-textarea name="input-7-1" v-model="ginecologico.descripcion_transmisionsexual"
                        v-if="ginecologico.checkboxTransmisionsexual == true & ginecologico.presente_transmisionsexual == 'Si'" label="Descripcion"
                        placeholder="Recuerde especificar la fecha de contagio, el tipo de ITS y el tratamiento para la ITS">
                    </v-textarea>
                    <!-- <v-textarea name="input-7-1" v-model="ginecologico.tratamiento_transmisionsexual"
                        v-if="ginecologico.checkboxTransmisionsexual == true" label="Tratamiento">
                    </v-textarea>
                    <v-text-field type="date" v-model="ginecologico.fecha_transmisionsexual"
                        v-if="ginecologico.checkboxTransmisionsexual == true & ginecologico.presente_transmisionsexual=='Si'"
                        label="Fecha Diagnostico">
                    </v-text-field> -->
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxPrimerarelacion" value="1"
                        label="Primera relación sexual"></v-checkbox>
                    <v-text-field type="number" v-model="ginecologico.EdadPrimera"
                        v-if="ginecologico.checkboxPrimerarelacion == true" :items="sino" label="Edad">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxMetodoanticonceptivo" value="1"
                        label="Metodo anticonceptivo"></v-checkbox>
                    <v-select v-model="ginecologico.presente_metodoanticonceptivo"
                        v-if="ginecologico.checkboxMetodoanticonceptivo == true" :items="sino" label="Presente">
                    </v-select>
                    <v-select v-model="ginecologico.descripcion_metodoanticonceptivo"
                        v-if="ginecologico.checkboxMetodoanticonceptivo == true & ginecologico.presente_metodoanticonceptivo == 'Si' " :items="descripcionmetodo"
                        label="Descripcion">
                    </v-select>
                    <v-select v-model="ginecologico.tipo_metodoanticonceptivo"
                        v-if="ginecologico.checkboxMetodoanticonceptivo == true & ginecologico.descripcion_metodoanticonceptivo=='Barrera'"
                        :items="tipometodo1" label="Tipo">
                    </v-select>
                    <v-select v-model="ginecologico.tipo_metodoanticonceptivo"
                        v-if="ginecologico.checkboxMetodoanticonceptivo == true & ginecologico.descripcion_metodoanticonceptivo=='Quirurgicos Reversibles'"
                        :items="tipometodo2" label="Tipo">
                    </v-select>
                    <v-select v-model="ginecologico.tipo_metodoanticonceptivo"
                        v-if="ginecologico.checkboxMetodoanticonceptivo == true & ginecologico.descripcion_metodoanticonceptivo=='Definitivos'"
                        :items="tipometodo3" label="Tipo">
                    </v-select>
                    <v-textarea name="input-7-1" v-model="ginecologico.tratamiento_metodoanticonceptivo"
                        v-if="ginecologico.checkboxMetodoanticonceptivo == true & ginecologico.presente_metodoanticonceptivo == 'Si'" label="Tratamiento">
                    </v-textarea>
                    <v-text-field type="date" v-model="ginecologico.fecha_metodoanticonceptivo"
                        v-if="ginecologico.checkboxMetodoanticonceptivo == true & ginecologico.presente_metodoanticonceptivo=='Si'"
                        label="Fecha">
                    </v-text-field>

                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxTratamientoinfertilidad" value="1"
                        label="Antecedentes de tratamiento infertilidad: ">
                    </v-checkbox>
                    <v-select v-model="ginecologico.presente_tratamientoinfertilidad"
                        v-if="ginecologico.checkboxTratamientoinfertilidad == true" :items="sino" label="Presente">
                    </v-select>
                    <v-textarea name="input-7-1" v-model="ginecologico.tratamiento_tratamientoinfertilidad"
                        v-if="ginecologico.checkboxTratamientoinfertilidad == true & ginecologico.presente_tratamientoinfertilidad=='Si'" label="Tratamiento">
                    </v-textarea>
                    <v-text-field type="date" v-model="ginecologico.fecha_tratamientoinfertilidad"
                        v-if="ginecologico.checkboxTratamientoinfertilidad == true & ginecologico.presente_tratamientoinfertilidad=='Si'"
                        label="Fecha tratamiento">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxAutoexamensenos" value="1"
                        label="Practica el autoexamen de senos?:"></v-checkbox>
                    <v-select v-model="ginecologico.presente_autoexamensenos"
                        v-if="ginecologico.checkboxAutoexamensenos == true" :items="sino" label="Presente">
                    </v-select>
                    <v-select v-model="ginecologico.frecuencia_autoexamensenos"
                        v-if="ginecologico.checkboxAutoexamensenos == true & ginecologico.presente_autoexamensenos =='Si'" :items="frecuenciautoseno"
                        label="Frecuencia">
                    </v-select>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxCitologia" value="1"
                        label="Citología cervicouterina"></v-checkbox>
                    <v-text-field type="date" v-model="ginecologico.fecha_citologia"
                        v-if="ginecologico.checkboxCitologia == true" label="Fecha última citología">
                    </v-text-field>
                    <v-textarea name="input-7-1" v-model="ginecologico.resultado_citologia"
                        v-if="ginecologico.checkboxCitologia == true" label="Resultado">
                    </v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxMamografia" value="1" label="Mamografía">
                    </v-checkbox>
                    <v-text-field type="date" v-model="ginecologico.fecha_mamografia"
                        v-if="ginecologico.checkboxMamografia == true" label="Fecha última mamografía">
                    </v-text-field>
                    <v-textarea name="input-7-1" v-model="ginecologico.resultado_mamografia"
                        v-if="ginecologico.checkboxMamografia == true" label="Resultado">
                    </v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxProcedimientocuellouterino" value="1"
                        label="Procedimientos en el Cuello Uterino:  ">
                    </v-checkbox>
                    <v-select v-model="ginecologico.presente_procedimientocuellouterino"
                        v-if="ginecologico.checkboxProcedimientocuellouterino == true" :items="sino" label="Presente">
                    </v-select>
                    <v-select v-model="ginecologico.tratamiento_procedimientocuellouterino"
                        v-if="ginecologico.checkboxProcedimientocuellouterino == true & ginecologico.presente_procedimientocuellouterino=='Si' " :items="trataminetouterino"
                        label="Tratamiento">
                    </v-select>
                    <v-text-field type="date" v-model="ginecologico.fecha_procedimientocuellouterino"
                        v-if="ginecologico.checkboxProcedimientocuellouterino == true & ginecologico.presente_procedimientocuellouterino=='Si'"
                        label="Fecha procedimiento">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="ginecologico.checkboxOtrotipotratamiento" value="1"
                        label="Otro Tipo de Tratamiento"></v-checkbox>
                    <v-textarea name="input-7-1" v-model="ginecologico.tratamiento_otrotipotratamiento"
                        v-if="ginecologico.checkboxOtrotipotratamiento == true" label="Otro">
                    </v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md6>
                    <v-text-field type="date" v-model="ginecologico.fecha_ultimoparto"
                        label="Fecha ùltimo parto">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md6>
                     <v-select v-model="ginecologico.planea_embarazo"
                        :items="sino"
                        label="¿Planea embarazo antes de 1 año?:">
                    </v-select>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-text-field type="number" v-model="ginecologico.gesta" label="Gestas (G)">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-text-field type="number" v-model="ginecologico.parto" label="Partos (P)">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md1>
                    <v-text-field type="number" v-model="ginecologico.aborto" label="Aborto (A)">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md1>
                    <v-text-field type="number" v-model="ginecologico.vivos" label="Vivos (V)">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md1>
                    <v-text-field type="number" v-model="ginecologico.cesarea" label="Cesarea (C)">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md1>
                    <v-text-field type="number" v-model="ginecologico.mortinato" label="Mortinato (M)">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-text-field type="number" v-model="ginecologico.ectopicos" label="Ectopicos (E)">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md1>
                    <v-text-field type="number" v-model="ginecologico.molas" label="Molas">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md1>
                    <v-text-field type="number" v-model="ginecologico.gemelos" label="Gemelos">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm12 md12>
                    <v-checkbox class="title layout justify-center font-weight-light" color="success"
                        v-model="ginecologico.checkbox_gestante" value="1" label="Marcar como gestante"></v-checkbox>
                </v-flex>
                <v-container grid-list-md fluid class="pa-0">
                    <v-flex xs12>
                        <v-layout row wrap v-if="ginecologico.checkbox_gestante == true">
                            <v-flex xs12 sm12 md12>
                                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                                    <span class="title layout justify-center font-weight-light">Gestantes</span>
                                </v-card-title>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field type="date" v-model="ginecologico.fecha_ultimaMenstruacion"
                                    label="Fecha Ultima Menstruacion">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field type="text" v-model="ginecologico.gestacionalfum"
                                    label="Edad Gestacional por FUM">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field type="text" v-model="ginecologico.fecha_probable "
                                    label="Fecha Probable de parto">
                                </v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6 md4>
                                <v-select v-model="ginecologico.embarazodeseado" :items="sino" label="Embarazo Deseado">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-select v-model="ginecologico.embarazoplaneado" :items="sino"
                                    label="Embarazo Planeado">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-select v-model="ginecologico.embarazoaceptado" :items="sino"
                                    label="Embarazo Aceptado">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-checkbox color="success" v-model="ginecologico.checkboxEco1" value="1"
                                    label="Ecografía 1er trimestre:  ">
                                </v-checkbox>
                                <v-text-field type="date" v-model="ginecologico.fecha_pimeraeco"
                                    v-if="ginecologico.checkboxEco1 == true" label="Fecha ecografía">
                                </v-text-field>
                                <v-text-field type="number" v-model="ginecologico.gestacionaleco1"
                                    v-if="ginecologico.checkboxEco1 == true"
                                    label="Edad Gestacional por Ecografia del 1er Trimestre">
                                </v-text-field>
                                <v-textarea name="input-7-1" v-model="ginecologico.descripcioneco1"
                                    v-if="ginecologico.checkboxEco1 == true" label="Descripción">
                                </v-textarea>
                            </v-flex>

                            <v-flex xs12 sm6 md4>
                                <v-checkbox color="success" v-model="ginecologico.checkboxEco2" value="1"
                                    label="Ecografía 2do trimestre:  ">
                                </v-checkbox>
                                <v-text-field type="date" v-model="ginecologico.fecha_segundaeco"
                                    v-if="ginecologico.checkboxEco2 == true" label="Fecha ecografía">
                                </v-text-field>
                                <v-text-field type="number" v-model="ginecologico.gestacionaleco2"
                                    v-if="ginecologico.checkboxEco2 == true"
                                    label="Edad Gestacional por Ecografia del 2do Trimestre">
                                </v-text-field>
                                <v-textarea name="input-7-1" v-model="ginecologico.descripcioneco2"
                                    v-if="ginecologico.checkboxEco2 == true" label="Descripción">
                                </v-textarea>
                            </v-flex>

                            <v-flex xs12 sm6 md4>
                                <v-checkbox color="success" v-model="ginecologico.checkboxEco3" value="1"
                                    label="Ecografía 3er trimestre:  ">
                                </v-checkbox>
                                <v-text-field type="date" v-model="ginecologico.fecha_terceraeco"
                                    v-if="ginecologico.checkboxEco3 == true" label="Fecha ecografía">
                                </v-text-field>
                                <v-text-field type="number" v-model="ginecologico.gestacionaleco3"
                                    v-if="ginecologico.checkboxEco3 == true"
                                    label="Edad Gestacional por Ecografia del 3er Trimestres">
                                </v-text-field>
                                <v-textarea name="input-7-1" v-model="ginecologico.descripcioneco3"
                                    v-if="ginecologico.checkboxEco3 == true" label="Descripción">
                                </v-textarea>
                            </v-flex>
                            <!-- <v-flex xs12 sm6 md3>
                                <v-text-field type="number" v-model="ginecologico.intergenesico"
                                    label="Intervalo intergenesico">
                                </v-text-field>
                            </v-flex> -->
                            <v-flex xs12 sm12 md6>
                                <v-text-field type="number" v-model="ginecologico.gestacionalcaptacion"
                                    label="Semanas gestacionales a la captacion">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-select v-model="ginecologico.periodo_interginesico" :items="interginesico"
                                    label="Periodo Intergenesico" @change="periodoInterginesico()">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                                <v-textarea name="input-7-1" v-model="ginecologico.descripcion_interginesico_corto"
                                    v-if="ginecologico.periodo_interginesico == 'Corto' || ginecologico.periodo_interginesico == 'Largo'"
                                    label="Descripción Periodo Interginesico">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-flex>

                </v-container>

            </v-layout>

            <v-layout row wrap v-if="ginecologico.checkbox_gestante == true">
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout row wrap>
                                        <v-flex xs4>
                                            <v-select v-model="gestante.patologias" :items="patologias"
                                                label="Patologias relacionadas con gestación"></v-select>
                                        </v-flex>
                                        <v-flex xs2>
                                            <v-select v-model="gestante.presente" :items="sino" label="Presente">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs2>
                                            <v-text-field type="date" v-model="gestante.fecha_patologia"
                                                label="Fecha de Diagnostico">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs2>
                                            <v-btn fab dark color="success" @click="Gestanteginecologico()">
                                                <v-icon dark>add</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                        <v-card>
                            <v-card-title primary-title>
                                <v-flex xs12 sm12 d-flex>
                                    <v-data-table :items="fetchgestante" :headers="headerGestantes" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.id }}</td>
                                            <td class="text-xs">{{ props.item.patologias }}</td>
                                            <td class="text-xs">{{ props.item.presente }}</td>
                                            <td class="text-xs">{{ props.item.fecha_patologia }}</td>
                                            <td class="text-xs">{{ props.item.name }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                </v-flex>
            </v-layout>

            <v-layout row wrap v-if="ginecologico.checkbox_gestante == true">
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                                <span class="title layout justify-center font-weight-light">Evaluación exposición a
                                    violencias:</span>
                            </v-card-title>
                            <v-layout row wrap>
                                <v-container>
                                    <v-layout row wrap>
                                        <v-flex xs12>
                                            <v-select v-model="ginecologico.violencia1" :items="sino"
                                                label="¿Durante el último año, ha sido humillada, menospreciada, insultada o amenazada por su pareja?">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-select v-model="ginecologico.violencia2" :items="sino"
                                                label="¿Durante el último año, fue golpeada, bofeteada, pateada, o lastimada físicamente de otra manera?">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-select v-model="ginecologico.violencia3" :items="sino"
                                                label="¿Durante el último año, fue forzada a tener relaciones sexuales?">
                                            </v-select>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                        <v-flex xs12 sm12 md12>
                            <v-checkbox class="title layout justify-center font-weight-light" color="success"
                                v-model="ginecologico.checkbox_puerpera" value="1" label="Marcar como puerpera">
                            </v-checkbox>
                        </v-flex>
                    </v-card>
                </v-flex>

            </v-layout>

            <v-layout row wrap v-if="ginecologico.checkbox_puerpera == true">
                <v-flex xs12>
                    <v-card-title class="headline" style="color:white;background-color:#0074a6">
                        <span class="title layout justify-center font-weight-light">Puerpera</span>
                    </v-card-title>
                </v-flex>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-card color="lighten-1" class="mb-5" height="auto">
                            <v-flex xs12 sm12 md12>
                                <v-text-field type="number" v-model="ginecologico.semananacimineto"
                                    label="Semana al nacimiento">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-textarea name="input-7-1" v-model="ginecologico.inconvenienteslactancia"
                                    label="Inconvenientes e inquietudes sobre la lactancia">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12>
                                <v-textarea name="input-7-1" v-model="ginecologico.planlactanciaretorno"
                                    label="Planes de la madre para continuar con la lactancia durante el retorno al trabajo o estudios">
                                </v-textarea>
                            </v-flex>
                            <!-- <v-container grid-list-xs>
                                <v-layout row wrap>
                                    <v-container fluid grid-list-xl>
                                        <v-layout row wrap>
                                            <v-flex xs4>
                                                <v-select v-model="gestacion.patologias" :items="patologiasActuales"
                                                    label="Patologias"></v-select>
                                            </v-flex>
                                            <v-flex xs2>
                                                <v-select v-model="gestacion.presente" :items="sino" label="Presente">
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs2>
                                                <v-btn fab dark color="success" @click="Gestacionginecologico()">
                                                    <v-icon dark>add</v-icon>
                                                </v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-layout>
                            </v-container>
                            <v-card>
                                <v-card-title primary-title>
                                    <v-flex xs12 sm12 md12>
                                        <v-data-table :items="fetchgestacion" :headers="headerGestacion" hide-actions
                                            class="elevation-1">
                                            <template v-slot:items="props">
                                                <td>{{ props.item.id }}</td>
                                                <td class="text-xs">{{ props.item.patologias }}</td>
                                                <td class="text-xs">{{ props.item.presente }}</td>
                                                <td class="text-xs">{{ props.item.name }}</td>
                                                <td>{{ props.item.created_at }}</td>
                                            </template>
                                        </v-data-table>
                                    </v-flex>
                                </v-card-title>
                            </v-card> -->
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-layout>
        </v-container>
        <v-btn color="success" round @click="saveGinecologico()">Guardar y seguir</v-btn>
    </v-form>
</template>
<script>
    import moment from 'moment';
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        data() {
            return {
                prueba: '',
                fecha_inicio: '',
                gestante: {
                    patologias: '',
                    presente: '',
                    fecha_patologia: '',
                    paciente_id: ''
                },
                interginesico: ['Corto', 'Largo', 'No aplica'],
                gestacion: {
                    patologias: '',
                    presente: '',
                    paciente_id: ''
                },
                ginecologico: {
                    checkboxMenarquia: false,
                    presente_menarquia: '',
                    edad: '',
                    checkboxCiclos: false,
                    clasificacion_ciclos: '',
                    frecuencia_ciclos: '',
                    duracion_ciclos: '',
                    fecha_ultimaMenstruacion: '',
                    checkboxPrimerarelacion: '',
                    EdadPrimera: '',
                    checkboxTransmisionsexual: false,
                    presente_transmisionsexual: '',
                    descripcion_transmisionsexual: '',
                    tratamiento_transmisionsexual: '',
                    fecha_transmisionsexual: '',
                    checkboxMetodoanticonceptivo: false,
                    presente_metodoanticonceptivo: '',
                    descripcion_metodoanticonceptivo: '',
                    tipo_metodoanticonceptivo: '',
                    tratamiento_metodoanticonceptivo: '',
                    fecha_metodoanticonceptivo: '',
                    checkboxTratamientoinfertilidad: false,
                    presente_tratamientoinfertilidad: '',
                    tratamiento_tratamientoinfertilidad: '',
                    fecha_tratamientoinfertilidad: '',
                    checkboxAutoexamensenos: false,
                    presente_autoexamensenos: '',
                    frecuencia_autoexamensenos: '',
                    checkboxCitologia: false,
                    fecha_citologia: '',
                    resultado_citologia: '',
                    checkboxMamografia: false,
                    fecha_mamografia: '',
                    resultado_mamografia: '',
                    checkboxProcedimientocuellouterino: false,
                    presente_procedimientocuellouterino: '',
                    tratamiento_procedimientocuellouterino: '',
                    fecha_procedimientocuellouterino: '',
                    checkboxOtrotipotratamiento: false,
                    tratamiento_otrotipotratamiento: '',
                    checkbox_gestante: false,
                    gesta: '',
                    parto: '',
                    aborto: '',
                    vivos: '',
                    cesarea: '',
                    mortinato: '',
                    ectopicos: '',
                    molas: '',
                    gemelos: '',
                    gestacionalfum: '',
                    fecha_probable: '',
                    embarazodeseado: '',
                    gestacionaleco1: '',
                    descripcioneco1: '',
                    embarazoplaneado: '',
                    gestacionaleco2: '',
                    fecha_segundaeco: '',
                    embarazoaceptado: '',
                    checkboxEco1: false,
                    fecha_pimeraeco: '',
                    checkboxEco2: false,
                    descripcioneco2: '',
                    checkboxEco3: false,
                    fecha_terceraeco: '',
                    gestacionaleco3: '',
                    descripcioneco3: '',
                    ultimamestruacion: '',
                    probableparto: '',
                    intergenesico: '',
                    gestacionalcaptacion: '',
                    semananacimineto: '',
                    inconvenienteslactancia: '',
                    periodo_interginesico: '',
                    descripcion_interginesico_corto: '',
                    descripcion_interginesico_largo: '',
                    checkbox_puerpera: false,
                    violencia1: '',
                    violencia2: '',
                    violencia3: '',
                    planlactanciaretorno: '',
                    citapaciente_id: '',
                    fecha_ultimoparto: '',
                    planea_embarazo: ''
                },
                sino: ['Si', 'No'],
                frecuencia: ['15 días', '21 días', '28 días', '30 días', 'Otros', ],
                clasificacion: ['Regulares', 'Inregulares'],
                duracion: ['1 Dias', '2 Dias', '3 Dias', '4 Dias', '5 Dias', '6 Dias', '7 Dias', '9 Dias', '10 Dias'],
                descripcionmetodo: ['Orales', 'Inyectables', 'Barrera', 'Mecanicos', 'Subdermicos',
                    'Quirurgicos Reversibles', 'Definitivos'
                ],
                tipometodo1: ['Condones', 'Diagfracma', 'DIU'],
                tipometodo2: ['Vasectomia', 'Tubectomia'],
                tipometodo3: ['Histerectomia'],
                frecuenciautoseno: ['Diario', 'Semanal', 'Quincenal', 'Mensual', 'Nunca'],
                trataminetouterino: ['Cauterizacion', 'Histerectomia', 'Conizacion', 'Radicacion', 'Otro'],
                patologias: ['Hipertension Inducida por el Embarazo', 'Sifilis Gestacional Congenita',
                    'Placenta previa', 'Abruptio', 'Ant. Hemorragia posparto', 'Ant. Depresion posparto',
                    'Complicaciones durante el parto', 'Ruptura Prematura de Membrana (RPM)',
                    'Antecedente Parto prematuro', 'Malformaciones congenitas', 'RN Bajo peso', 'Macrosomicos',
                    'Incompatibilidad RH', 'Multiparidad', 'La paciente tuvo consulta preconcepcional','epigastralgia',
                    'Diabetes Pre-Gestacional', 'Hipoglicemias', 'Eclampsia','Edema en cara o miembros superiores e inferiores',
                    'Pre-Eclampsia', 'Sindrome de Hellp', 'Hiperemesis','Nulipara','Antecedentes de IVU ','Cefaleas','Hemorroides','Nauseas', 'Vomito'
                ],
                patologiasActuales: ['Presencia movimientos fetales', 'Antecedentes de IVU ',
                    'Edema en cara o miembros superiores e inferiores', 'Cefaleas', 'Hemorroides',
                    'Presencia epigastralgia', 'Nulipara', 'Multipara', 'Nauseas', 'Vomito'
                ],
                fetchgestante: [],
                headerGestantes: [{
                        text: 'id',
                    },
                    {
                        text: 'Patologia',
                    },
                    {
                        text: 'Presente',
                    },
                    {
                        text: 'Fecha DX',
                    },
                    {
                        text: 'Medico',
                    },
                ],
                fetchgestacion: [],
                headerGestacion: [{
                        text: 'id',
                    },
                    {
                        text: 'Patologia',
                    },
                    {
                        text: 'Presente',
                    },
                    {
                        text: 'Medico',
                    },
                    {
                        text: 'Fecha',
                    },
                ],
            }

        },
        created() {
            this.fetchGinecologico();
            this.fetchGestante();
            this.fetchGestacion();

        },
        watch: {
            "ginecologico.fecha_ultimaMenstruacion": function () {
                this.calcularfechaProbableParto();
                this.calcularEdadGestacional();
            }

        },
        methods: {
            saveGinecologico() {

                this.ginecologico.citapaciente_id = this.datosCita.cita_paciente_id;
                // console.log(this.ginecologicos)
                axios.post('/api/historia/saveGinecologico', this.ginecologico)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                    })
            },
            fetchGinecologico() {
                axios.get(`/api/historia/fetchGinecologico/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.ginecologico = res.data;
                    });
            },
            Gestanteginecologico() {
                this.gestante.paciente_id = this.datosCita.paciente_id;
                axios.post('/api/historia/saveGestanteGinecologico', this.gestante)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchGestante();
                        this.clear();
                    })
            },
            fetchGestante() {
                axios.get(`/api/historia/fetchGestanteGinecologico/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.fetchgestante = res.data;
                    });
            },
            Gestacionginecologico() {
                this.gestacion.paciente_id = this.datosCita.paciente_id;
                axios.post('/api/historia/saveGestacionGinecologico', this.gestacion)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchGestacion();
                        this.clear();
                    })
            },
            fetchGestacion() {
                axios.get(`/api/historia/fetchGestacionGinecologico/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.fetchgestacion = res.data;
                    });
            },
            periodoInterginesico() {
                this.ginecologico.descripcion_interginesico_corto = '',
                    this.ginecologico.descripcion_interginesico_largo = ''
            },
            calcularfechaProbableParto() {
                let fecha_inicio = moment(this.ginecologico.fecha_ultimaMenstruacion).add(7, 'days');
                let fecha1 = moment(fecha_inicio).subtract(3, 'months').calendar();
                let fecha2 = moment(fecha1).add(12, 'months').calendar();
                this.ginecologico.fecha_probable = fecha2;

            },
            calcularEdadGestacional() {
                let fecha1 = moment(this.ginecologico.fecha_ultimaMenstruacion);
                let fecha2 = moment();
                let fecha3 = moment(fecha2).diff(moment(fecha1), 'days', false)-2;
                this.ginecologico.gestacionalfum = ((fecha3) / 7).toFixed(1)
                let decimals = +this.ginecologico.gestacionalfum.toString().replace(/^[^\.]+/,'0');

                if (decimals == 0.7) {
                    this.ginecologico.gestacionalfum = (((fecha3) / 7)+ 0.3).toFixed(1)
                }
                else{
                    this.ginecologico.gestacionalfum = this.ginecologico.gestacionalfum
                }

            },
            clear() {
                this.gestante.patologias = '',
                    this.gestante.presente = '',
                    this.gestante.fecha_patologia = '',
                    this.gestacion.patologias = '',
                    this.gestacion.presente = ''
            }
        }
    }

</script>
