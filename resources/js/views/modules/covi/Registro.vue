<template>
    <v-container fluid pa-1 grid-list-md>
        <v-layout row wrap>

            <v-flex xs12 md12>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Registro de seguimiento covid-19</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card>
                    <v-form @submit.prevent="search_paciente('seguimiento')">
                        <v-container fluid pa-4>
                            <v-layout>
                                <v-flex xs12 md4>
                                    <v-text-field v-model="cedula_paciente" label="Número de Documento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md4>
                                    <v-btn @click="search_paciente('seguimiento')"
                                        @keyup.enter="search_paciente('seguimiento')" v-if="!paciente.id" fab outline
                                        small color="success">
                                        <v-icon>search</v-icon>
                                    </v-btn>
                                    <v-btn @click="clearFields()" v-if="paciente.id" fab outline small color="error">
                                        <v-icon>clear</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-form>
                </v-card>
            </v-flex>
        </v-layout>
        <!-- v-show="paciente.id" -->
        <v-layout grow pa-0 v-show="paciente.id">
            <v-flex xs12>
                <v-card max-height="100%" class="mb-3">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Formulario de Registro</span>
                    </v-card-title>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex xs1>
                                <v-text-field v-model="paciente.Tipo_Doc" readonly label="Tipo Doc">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs1>
                                <v-text-field v-model="paciente.Num_Doc" readonly label="N° Doc">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs3>
                                <v-text-field v-model="nombrePaciente" readonly
                                    label="Nombres y apellidos del paciente">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs2>
                                <v-text-field v-model="paciente.Fecha_Naci" readonly label="F. Nacimiento">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs1>
                                <v-text-field v-model="paciente.Edad_Cumplida" label="Edad">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs3>
                                <v-select :items="medida" label="Unidad de medida de la edad"
                                    v-model="formRegistro.medida_edad"></v-select>
                            </v-flex>
                            <v-flex xs1>
                                <v-text-field v-model="paciente.Sexo" label="Sexo" readonly>
                                </v-text-field>
                            </v-flex>
                            <!-- <v-flex xs1 md2>
                                    <v-select v-model="paciente.Tipo_Afiliado" label="Plan Salud">
                                    </v-select>
                                </v-flex> -->
                            <!-- <v-flex xs1>
                                <v-select :items="estadoCivil" label="Estado civil"
                                    v-model="paciente.estado_civil" readonly></v-select>
                            </v-flex>
                            <v-flex xs1 md3>
                                <v-text-field v-model="paciente.Laboraen" label="Ocupación" readonly>
                                </v-text-field>
                            </v-flex> -->
                            <v-flex xs1 md3>
                                <v-text-field v-model="paciente.Correo1" label="Correo" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs3 md3>
                                <v-text-field v-model="paciente.NombreIPS" label="Punto Atencion" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs1 md2>
                                <v-text-field v-model="paciente.Tipo_Afiliado" label="Tipo Afiliado" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs2>
                                <v-text-field v-model="paciente.Telefono" label="Telefono" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs1>
                                <v-text-field readonly v-model="paciente.Celular1" label="Celular" type="number" maxlength="10"
                                    oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" min="1">
                                </v-text-field>
                            </v-flex>
                            <!-- <v-flex xs3>
                                <v-text-field v-model="paciente.Direccion_Residencia" label="Direccion_Residencia" readonly>
                                </v-text-field>
                            </v-flex> -->
                            <!-- <v-btn v-show="tipoRegistro === 'seguimiento'" color="warning"
                                @click="actualizarDatosPersonales()" round>Actualizar
                            </v-btn> -->
                            <form @submit.prevent="guardarRegistro">
                                <v-layout row wrap>
                                    <v-flex xs4>
                                        <v-autocomplete v-model="formRegistro.cie10s" append-icon="search" chips
                                            multiple :items="cie10s" item-disabled="customDisabled"
                                            :item-text="cie10s => cie10s.Codigo_CIE10+' '+cie10s.Descripcion"
                                            item-value="id" label="Diagnóstico">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-autocomplete v-model="formRegistro.pais_nacionalidad_id" append-icon="search"
                                            :items="paises" item-text="nombre" item-value="id" label="nacionalidad">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-autocomplete v-model="formRegistro.pais_ocurrencia_id" append-icon="search"
                                            :items="paises" item-text="nombre" item-value="id"
                                            label="pais ocurrencia caso">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-autocomplete v-model="formRegistro.municipio_procedencia_id" append-icon="search"
                                        :items="municipios" item-text="municipio" item-value="id"
                                        label="municipio de procedencia">
                                    </v-autocomplete>
                                    <v-flex xs12 sm6 md3>
                                        <v-select :items="areaocurerncia" label="area_ocurrencia_caso"
                                            v-model="formRegistro.area_ocurrencia_caso"></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="localidad_ocurrencia_caso"
                                            v-model="formRegistro.localidad_ocurrencia_caso"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="barrio_ocurrencia_caso"
                                            v-model="formRegistro.barrio_ocurrencia_caso"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-select :items="zona" label="zona"
                                            v-model="formRegistro.zona"></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-autocomplete v-model="formRegistro.ocupacion_id"
                                            append-icon="search" :items="ocupaciones" item-text="ocupacion"
                                            item-value="id" label="ocupacion paciente">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-select :items="regimen" label="tipo regimen salud"
                                            v-model="formRegistro.tipo_regimen_salud"></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="administradora_planes_beneficios"
                                            v-model="paciente.Ut"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md2>
                                        <v-select :items="etnias" label="Pertenencia étnica"
                                            v-model="formRegistro.pertenencia_etnica"></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md2>
                                        <v-select :items="estrato" label="estrato" v-model="formRegistro.estrato">
                                        </v-select>
                                    </v-flex>
                                    <!-- Grupos poblacionales -->
                                    <v-label>Seleccione los grupos poblacionales a los que pertenece el paciente
                                    </v-label>
                                    <v-container fluid>
                                        <v-layout row wrap>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.discapacitados" label="discapacitados"
                                                    color="red" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.Migrantes" label="Migrantes"
                                                    color="red" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.Gestantes" label="Gestantes"
                                                    color="indigo" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.Sem_de_gestacion"
                                                    label="Sem_de_gestacion" color="indigo" value="x" hide-details>
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.desplazados" label="desplazados"
                                                    color="orange" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.carcelarios" label="carcelarios"
                                                    color="orange" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.indigentes" label="indigentes"
                                                    color="primary" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.poblacion_infantil_ICBF"
                                                    label="poblacion_infantil_ICBF" color="pink" value="x" hide-details>
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.madres_comunitarias"
                                                    label="madres_comunitarias" color="success" value="x" hide-details>
                                                </v-checkbox>
                                                <v-checkbox v-model="formRegistro.desmovilizados" label="desmovilizados"
                                                    color="info" value="x" hide-details></v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.centros_psiquiatricos"
                                                    label="centros_psiquiatricos" color="warning" value="x"
                                                    hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.victimas_violencia_armada"
                                                    label="victimas_violencia_armada" color="error" value="x"
                                                    hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.otros_grupos_poblacionales"
                                                    label="otros_grupos_poblacionales" color="warning" value="x"
                                                    hide-details></v-checkbox>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                    <v-flex xs12 sm6 md3>
                                        <v-select :items="fuente" label="fuente" v-model="formRegistro.fuente">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-autocomplete v-model="formRegistro.municipio_residencia_paciente_id"
                                            append-icon="search" :items="municipios" item-text="municipio"
                                            item-value="id" label="municipio residencia paciente">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="direccion_residencia"
                                            v-model="formRegistro.direccion_residencia"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="fecha_consulta" type="date"
                                            v-model="formRegistro.fecha_consulta"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="fecha_inicio_sintomas" type="date"
                                            v-model="formRegistro.fecha_inicio_sintomas"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-select :items="clasificacion" label="clasificacion"
                                            v-model="formRegistro.clasificacion"></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-select :items="sino" label="hospitalizado"
                                            v-model="formRegistro.hospitalizado"></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3 v-if="formRegistro.hospitalizado == 'SI'">
                                        <v-text-field label="fecha_hospitalizacion" type="date"
                                            v-model="formRegistro.fecha_hospitalizacion"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-select :items="condicionfinal" label="condicion final"
                                            v-model="formRegistro.condicion_final"></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3 v-if="formRegistro.condicion_final == 'Muerto'">
                                        <v-text-field label="fecha_defuncion" type="date"
                                            v-model="formRegistro.fecha_defuncion"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3 v-if="formRegistro.condicion_final == 'Muerto'">
                                        <v-text-field label="numero_certificado_defuncion"
                                            v-model="formRegistro.numero_certificado_defuncion"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3 v-if="formRegistro.condicion_final == 'Muerto'">
                                        <v-text-field label="causa_basica_muerte"
                                            v-model="formRegistro.causa_basica_muerte"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="telefono" type="number" v-model="formRegistro.telefono"></v-text-field>
                                    </v-flex>
                                    <!-- ESPACIO EXCLUSICO PARA USO DE LOS ENTES TERRITORIALES -->
                                    <v-flex xs12 sm6 md3>
                                        <v-select
                                            :items="clasificacionCaso"
                                            label="Seguimiento y clasificación final del caso"
                                            v-model="formRegistro.clasificacion_final_caso">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-select
                                            :items="sino"
                                            label="Viajó a áreas de circulación del virus?"
                                            v-model="formRegistro.Viajo_circulacion_virus">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-select
                                            :items="sino"
                                            label="El viaje fue en el territorio nacional?"
                                            v-model="formRegistro.viajo_territorio_nacional">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3 v-if="formRegistro.viajo_territorio_nacional == 'SI'">
                                        <v-autocomplete
                                            v-model="formRegistro.municipio_viajo_id"
                                            append-icon="search"
                                            :items="municipios"
                                            item-text="municipio"
                                            item-value="id"
                                            label="¿Dónde(municipio donde viajo)?">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-select
                                            :items="sino"
                                            label="El viaje fue internacional?"
                                            v-model="formRegistro.viajo_internacional">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3 v-if="formRegistro.viajo_internacional == 'SI'">
                                        <v-autocomplete v-model="formRegistro.pais_viajo_id" append-icon="search"
                                            :items="paises" item-text="nombre" item-value="id" label="Donde?">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm6 md5>
                                        <v-select
                                            :items="sino"
                                            label="¿Tuvo contacto estrecho en los últimos 14 días con un caso probable o confirmado con infección respiratoria aguda grave por virus nuevo?"
                                            v-model="formRegistro.tuvo_contacto_estrecho">
                                        </v-select>
                                    </v-flex>
                                    <!-- ¿Reporta alguno de los siguientes síntomas? -->
                                    <v-label>¿Reporta alguno de los siguientes síntomas?
                                    </v-label>
                                    <v-container fluid>
                                        <v-layout row wrap>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.tos" label="tos"
                                                    color="red" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.fiebre" label="fiebre"
                                                    color="red" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.dolor_garganta" label="dolor_garganta"
                                                    color="indigo" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.dificultad_respiratoria"
                                                    label="dificultad_respiratoria" color="indigo" value="x" hide-details>
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.fatiga_adinamia" label="fatiga_adinamia"
                                                    color="orange" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.rinorrea" label="rinorrea"
                                                    color="orange" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.conjuntivitis" label="conjuntivitis"
                                                    color="primary" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.cefalea"
                                                    label="cefalea" color="pink" value="x" hide-details>
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.diarrea"
                                                    label="diarrea" color="success" value="x" hide-details>
                                                </v-checkbox>
                                                <v-checkbox v-model="formRegistro.perdida_de_olfato" label="perdida_de_olfato"
                                                    color="info" value="x" hide-details></v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.otros_sintomas"
                                                    label="otros_sintomas" color="warning" value="x"
                                                    hide-details></v-checkbox>
                                                <v-flex xs12 sm6 md8 v-if="formRegistro.otros_sintomas == 'x'">
                                                    <v-text-field label="¿Cuáles otros?"
                                                        v-model="formRegistro.cuales_otros"></v-text-field>
                                                </v-flex>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>

                                    <!-- ANTECEDENTES CLÍNICOS -->
                                    <v-label>ANTECEDENTES CLÍNICOS</v-label>
                                    <v-container fluid>
                                        <v-layout row wrap>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.asma" label="asma"
                                                    color="red" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.epoc" label="epoc"
                                                    color="red" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.diabetes" label="diabetes"
                                                    color="indigo" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.vih"
                                                    label="vih" color="indigo" value="x" hide-details>
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.enfermedad_cardiaca" label="enfermedad_cardiaca"
                                                    color="orange" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.cancer" label="cancer"
                                                    color="orange" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.malnutricion" label="malnutricion"
                                                    color="primary" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.obesidad"
                                                    label="obesidad" color="pink" value="x" hide-details>
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.insuficiencia_renal"
                                                    label="insuficiencia_renal" color="success" value="x" hide-details>
                                                </v-checkbox>
                                                <v-checkbox v-model="formRegistro.medicamentos_inmunosupresores" label="medicamentos_inmunosupresores"
                                                    color="info" value="x" hide-details></v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.fumador"
                                                    label="fumador" color="success" value="x" hide-details>
                                                </v-checkbox>
                                                <v-checkbox v-model="formRegistro.hipertensión" label="hipertensión"
                                                    color="info" value="x" hide-details></v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.tuberculosis"
                                                    label="tuberculosis" color="success" value="x" hide-details>
                                                </v-checkbox>
                                                <v-checkbox v-model="formRegistro.otros" label="otros"
                                                    color="info" value="x" hide-details></v-checkbox>
                                                <v-flex xs12 sm6 md8 v-if="formRegistro.otros == 'x'">
                                                    <v-text-field label="¿Cuáles otros?"
                                                        v-model="formRegistro.otros_antecedentes"></v-text-field>
                                                </v-flex>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>

                                    <!-- DIAGNÓSTICO Y TRATAMIENTO -->
                                    <v-label>DIAGNÓSTICO Y TRATAMIENTO (Si se tomó de radiografía de tórax ¿Qué hallazgo presentaron?)</v-label>
                                    <v-container fluid>
                                        <v-layout row wrap>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.infiltrado_alveolar_neumonia" label="infiltrado_alveolar_neumonia"
                                                    color="red" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.infiltrados_intesticiales" label="infiltrados_intesticiales"
                                                    color="red" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.infiltrados_basales_vidrio_esmerilado" label="infiltrados_basales_vidrio_esmerilado"
                                                    color="indigo" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.ninguno"
                                                    label="ninguno" color="indigo" value="x" hide-details>
                                                </v-checkbox>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>

                                    <!-- Servicio en el que se hospitalizó -->
                                    <v-label>Servicio en el que se hospitalizó</v-label>
                                    <v-container fluid>
                                        <v-layout row wrap>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox
                                                    v-model="formRegistro.servicio_hospitalizo"
                                                    label="Hospitalización general"
                                                    color="red"
                                                    value="X"
                                                    hide-details>
                                                </v-checkbox>
                                                <v-checkbox
                                                    v-model="formRegistro.uci"
                                                    label="UCI"
                                                    color="indigo darken-3"
                                                     value="SI"
                                                    hide-details>
                                                </v-checkbox>
                                                <v-text-field
                                                    v-if="formRegistro.uci == 'SI'"
                                                    label="Fecha de Ingreso a UCI (dd/mm/aaaa)"
                                                    type="date"
                                                    v-model="formRegistro.fecha_ingreso_uci">
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>

                                    <!-- Si hubo complicaciones ¿Cuáles se presentaron? -->
                                    <v-label>Si hubo complicaciones ¿Cuáles se presentaron?</v-label>
                                    <v-container fluid>
                                        <v-layout row wrap>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.derrame_pleural" label="derrame_pleural"
                                                    color="red" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.derrame_pericardico" label="derrame_pericardico"
                                                    color="red" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.miocarditis" label="miocarditis"
                                                    color="indigo" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.septicemia"
                                                    label="septicemia" color="septicemia" value="x" hide-details>
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox v-model="formRegistro.falla_respiratoria" label="falla_respiratoria"
                                                    color="orange" value="x" hide-details></v-checkbox>
                                                <v-checkbox v-model="formRegistro.otro" label="Otro"
                                                    color="orange" value="x" hide-details></v-checkbox>
                                                <v-flex xs12 sm6 md8 v-if="formRegistro.otro == 'x'">
                                                    <v-text-field label="¿Cuáles otros?"
                                                        v-model="formRegistro.otra_complicacion"></v-text-field>
                                                </v-flex>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                    <v-flex xs12 sm2 md2>
                                        <v-select
                                            :items="vacuna"
                                            label="Tipo de Vacuna contra Covid"
                                            v-model="formRegistro.tipo_vacuna">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 sm2 md2 v-if="formRegistro.tipo_vacuna != 'NINGUNA'">
                                        <v-text-field
                                            type="date"
                                            label="Primera dosis de Vacuna contra Covid"
                                            v-model="formRegistro.primera_dosis">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm2 md2 v-if="formRegistro.tipo_vacuna != 'NINGUNA'">
                                        <v-text-field
                                            type="date"
                                            label="Segunda dosis de Vacuna contra Covid"
                                            v-model="formRegistro.segunda_dosis">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-textarea
                                        v-model="formRegistro.observacionesregistro"
                                        name="input-7-1"
                                        label="Observaciones"
                                        ></v-textarea>
                                    </v-flex>

                                    <!-- ¿INDICACIONES? -->
                                    <v-label>¿INDICACIONES?
                                    </v-label>
                                    <v-container fluid>
                                        <v-layout row wrap>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox
                                                    v-model="formRegistro.esperarencasa"
                                                    label="Se Recomienda Esperar En Casa Y Se Informa Signos Y Sintomas De Alarma"
                                                    color="black"
                                                    value="x" hide-details>
                                                </v-checkbox>
                                                <v-checkbox
                                                    v-model="formRegistro.asignarconsulta"
                                                    label="Se Asignará Consulta Médica"
                                                    color="red" value="x" hide-details>
                                                </v-checkbox>
                                                <v-checkbox
                                                    v-model="formRegistro.seguimientotelefonico"
                                                    label="Se Realizará Seguimiento Telefónico Para Confirmación De Estado De Salud Por Parte Del Personal Médico Del Contratista"
                                                    color="blue" value="x" hide-details>
                                                </v-checkbox>
                                                <v-checkbox
                                                    v-model="formRegistro.consultaprioritaria"
                                                    label="Se Requiere Consulta Prioritaria"
                                                    color="indigo"
                                                    value="x" hide-details>
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm4 md3>
                                                <v-checkbox
                                                    v-model="formRegistro.teleorientacion"
                                                    label="Teleorientación Salud Mental"
                                                    color="orange"
                                                    value="x" hide-details>
                                                </v-checkbox>
                                                <v-checkbox
                                                    v-model="formRegistro.otrasindicaciones"
                                                    label="Otros"
                                                    color="green"
                                                    value="x" hide-details>
                                                </v-checkbox>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                    <v-flex xs12 sm2 md2>
                                        <v-select
                                            :items="sino"
                                            label="Requiere Prueba"
                                            v-model="formRegistro.requiere_prueba">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 sm2 md2 v-if="formRegistro.requiere_prueba == 'SI'">
                                        <v-select
                                            :items="tipoprueba"
                                            label="Tipo de prueba"
                                            v-model="formRegistro.tipo_prueba">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 sm2 md2 v-if="formRegistro.requiere_prueba == 'SI'">
                                        <v-select
                                            :items="modalidaprueba"
                                            label="Modalidad de la Toma de muestra"
                                            v-model="formRegistro.modalida_prueba">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 sm2 md2>
                                        <v-select
                                            :items="destinopaciente"
                                            label="Destino del paciente"
                                            v-model="formRegistro.destino_paciente">
                                        </v-select>
                                    </v-flex>
                                </v-layout>
                                    <v-card-actions>
                                        <v-btn color="success" type="submit">Guardar Registro COVID-19</v-btn>
                                    </v-card-actions>
                            </form>
                        </v-layout>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    import Swal from 'sweetalert2';
    import moment from 'moment';
    import {
        EventBus
    } from "../../../event-bus";
    export default {
        data() {
            return {
                vacuna: ['NINGUNA','MODERNA','SINOVAC','PFIZER&BIONTECH','CENTRO GAMALEYA (SPUTNIK V)','JOHNSON&JOHNSON','ASTRAZENECA','NO SE HA APLICADO'],
                destinopaciente: ['Seguimiento','Oximetrías','Hospitalización','Hospitalización UCI/UCE','Alta descartado', 'Alta recuperado', 'Alta fallecido'],
                tipoprueba: ['Antígeno','PCR','Anticuerpos'],
                modalidaprueba: ['Presencial','Domiciliaria'],
                estadoCivil: ['Soltero', 'Casado', 'Viudo', 'Union Libre ', 'Separado'],
                clasificacionCaso: ['No aplica','Conf. Por laboratorio','Conf. Clínica',
                'Conf. Nexo epidemiológico','Descartado','Otra actualización','Descartado por Dejar en blanco'],
                zona: ['Urbana', 'Rural'],
                areaocurerncia: ['Cabecera municipal', 'Centro poblado', 'Rural disperso'],
                regimen: ['P. Excepción', 'E. Especial', 'C. Contributivo', 'S. Subsidiado', 'N. No Asegurado',
                    'I. Indeterminado/ pendiente'],
                condicionfinal: ['Vivo', 'Muerto', 'No sabe, no responde'],
                clasificacion: ['Sospechoso', 'Probable', 'Conf. por laboratorio', 'Conf. Clínica','Conf. nexo epidemiológico'],
                fuente: ['Notificación rutinaria', 'Búsqueda activa Inst.', 'Vigilancia Intensificada',
                    'Búsqueda activa com.', 'Investigaciones'
                ],
                estrato: ['1', '2', '3', '4', '5', '6'],
                etnias: ['sin pertenencia étnica','Indígena', 'Rom, Gitano', 'Raiza', 'Palenquero', 'Negro, mulato afro colombiano', 'Otro'],
                medida: ['Años', 'Días', 'Minutos', 'Meses', 'Horas', 'No aplica'],
                sino: ['SI', 'NO'],
                radioGroup: 1,
                tipoRegistro: '',
                nameFile: '',
                file: '',
                cedula_paciente: '',
                paciente: {
                    id: null,
                    Primer_Nom: null,
                    Primer_Ape: null,
                    Tipo_Doc: null,
                    Num_Doc: null,
                    Edad_Cumplida: null,
                },
                entidad: '',
                paises: [],
                municipios: [],
                nombrePaciente: '',
                cie10s: [],
                ocupaciones: [],
                formRegistro: {
                    paciente_id: null,
                    cita_paciente_id: null,
                    cie10s: [12519],
                    medida_edad: 'Años',
                    pais_nacionalidad_id: 47,
                    pais_ocurrencia_id: 47,
                    municipio_procedencia_id: 1,
                    area_ocurrencia_caso: 'Cabecera municipal',
                    localidad_ocurrencia_caso: null,
                    barrio_ocurrencia_caso: null,
                    zona: 'Urbana',
                    ocupacion_id: null,
                    tipo_regimen_salud: 'P. Excepción',
                    pertenencia_etnica: 'sin pertenencia étnica',
                    estrato: null,
                    discapacitados: null,
                    Migrantes: null,
                    Gestantes: null,
                    Sem_de_gestacion: null,
                    desplazados: null,
                    carcelarios: null,
                    indigentes: null,
                    poblacion_infantil_ICBF: null,
                    madres_comunitarias: null,
                    desmovilizados: null,
                    centros_psiquiatricos: null,
                    victimas_violencia_armada: null,
                    otros_grupos_poblacionales: null,
                    fuente: 'Notificación rutinaria',
                    municipio_residencia_paciente_id: null,
                    direccion_residencia: null,
                    fecha_consulta: null,
                    fecha_inicio_sintomas: null,
                    clasificacion: 'Sospechoso',
                    hospitalizado: null,
                    fecha_hospitalizacion: null,
                    condicion_final: 'Vivo',
                    fecha_defuncion: null,
                    numero_certificado_defuncion: null,
                    causa_basica_muerte: null,
                    telefono: null,
                    clasificacion_final_caso: null,
                    Viajo_circulacion_virus: null,
                    viajo_territorio_nacional: null,
                    municipio_viajo_id: null,
                    viajo_internacional: null,
                    pais_viajo_id: null,
                    tuvo_contacto_estrecho: null,
                    tos: null,
                    fiebre: null,
                    dolor_garganta: null,
                    dificultad_respiratoria: null,
                    fatiga_adinamia: null,
                    rinorrea: null,
                    conjuntivitis: null,
                    cefalea: null,
                    diarrea: null,
                    perdida_de_olfato: null,
                    otros_sintomas: null,
                    cuales_otros: null,
                    asma: null,
                    epoc: null,
                    diabetes: null,
                    vih: null,
                    enfermedad_cardiaca: null,
                    cancer: null,
                    malnutricion: null,
                    obesidad: null,
                    insuficiencia_renal: null,
                    medicamentos_inmunosupresores: null,
                    fumador: null,
                    hipertensión: null,
                    tuberculosis: null,
                    otros: null,
                    otros_antecedentes: null,
                    infiltrado_alveolar_neumonia: null,
                    infiltrados_intesticiales: null,
                    infiltrados_basales_vidrio_esmerilado: null,
                    ninguno: null,
                    servicio_hospitalizo: null,
                    uci:null,
                    fecha_ingreso_uci: null,
                    derrame_pleural: null,
                    derrame_pericardico: null,
                    miocarditis: null,
                    septicemia: null,
                    observacionesregistro: null,
                    falla_respiratoria: null,
                    otro: null,
                    otra_complicacion: null,
                    requiere_prueba: null,
                    tipo_prueba: null,
                    modalida_prueba: null,
                    destino_paciente: null,
                    esperarencasa: null,
                    asignarconsulta: null,
                    seguimientotelefonico: null,
                    consultaprioritaria: null,
                    teleorientacion: null,
                    otrasindicaciones: null,
                    tipo_vacuna: 'NINGUNA',
                    primera_dosis: null,
                    segunda_dosis: null,
                }
            }
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
        },
        mounted() {
            moment.locale('es');
            this.fetchCie10s();
            this.fetchPaises();
            this.fetchMunicipios();
            this.fetchOcupaciones();
        },
        methods: {
            fetchOcupaciones(){
                axios.get('/api/covid/ocupaciones')
                .then((res) => {
                    this.ocupaciones = res.data
                })
                .catch((err) => console.log(err));
            },
            fetchPaises() {
                axios.get('/api/covid/paises')
                    .then((res) => {
                        this.paises = res.data
                    })
                    .catch((err) => console.log(err));
            },
            search_paciente(tipo) {
                this.tipoRegistro = tipo;
                if (!this.cedula_paciente) return;

                axios.get(`/api/paciente/pacienteRutacovi/${this.cedula_paciente}`)
                    .then((res) => {
                        if (res.data.message ) {
                            this.$alerError(res.data.message)
                                 return;
                        }
                        if (res.data.paciente.estadoRegistro == 1 ) {
                            this.$alerError('El paciente se encuentra en estado de admision covid')
                                 return;
                        }if (res.data.paciente.estadoRegistro == 43) {
                            this.$alerError('El paciente esta en seguimiento covid')
                                 return;
                        }if (res.data.paciente.estadoRegistro == 44 || res.data.paciente.estadoRegistro == null) {
                                this.paciente = res.data.paciente;
                                this.nombrePaciente = res.data.paciente.Primer_Nom + (res.data.paciente.SegundoNom ?
                                        ' ' + res.data.paciente.SegundoNom : '') + ' ' + res.data.paciente.Primer_Ape +
                                    ' ' + res.data.paciente.Segundo_Ape
                                this.sede_selected = this.paciente.IPS;
                                this.paciente = res.data.paciente;
                                this.formRegistro.paciente_id = res.data.paciente.id;
                            }
                    });
            },
            clearFields() {
                this.nombrePaciente = '',
                    this.entidad = '',
                    this.tipoRegistro = '',
                    this.cedula_paciente = '',
                    this.paciente = {
                        Primer_Nom: '',
                        Primer_Ape: '',
                        Tipo_Doc: '',
                        Num_Doc: '',
                        Edad_Cumplida: ''
                    }
                for (const prop of Object.getOwnPropertyNames(this.formRegistro)) {
                    this.formRegistro[prop] = null;
                }
                this.formRegistro.cie10s = [12519];
            },
            fetchMunicipios() {
                axios.get('/api/municipio/lista')
                    .then(res => {
                        this.municipios = res.data
                    })
            },
            guardarRegistro() {
                if (this.formRegistro.medida_edad == null) {
                    this.$alerError('La lista deplegable de Unidad de medida edad es REQUERIDAD');
                    return;
                }
                if(this.formRegistro.area_ocurrencia_caso == null){
                    this.$alerError('El campo area_ocurrencia_caso es requerido')
                    return;
                }
                if(this.formRegistro.estrato == null){
                    this.$alerError('El campo estrato es requerido')
                    return;
                }
                if(this.formRegistro.localidad_ocurrencia_caso == null){
                    this.$alerError('El campo localidad_ocurrencia_caso es requerido')
                    return;
                }
                if(this.formRegistro.barrio_ocurrencia_caso == null){
                    this.$alerError('El campo barrio_ocurrencia_caso es requerido')
                    return;
                }
                if(this.formRegistro.zona == null){
                    this.$alerError('El campo null es requerido')
                    return;
                }
                if(this.formRegistro.tipo_regimen_salud == null){
                    this.$alerError('El campo tipo_regimen_salud es requerido')
                    return;
                }
                if(this.formRegistro.pertenencia_etnica == null){
                    this.$alerError('El campo pertenencia_etnica es requerido')
                    return;
                }
                if(this.formRegistro.direccion_residencia == null){
                    this.$alerError('El campo direccion_residencia es requerido')
                    return;
                }
                if(this.formRegistro.fecha_consulta == null){
                    this.$alerError('El campo fecha_consulta es requerido')
                    return;
                }
                if(this.formRegistro.ocupacion_id == null){
                    this.$alerError('El campo Ocupación es requerido')
                    return;
                }
                if(this.formRegistro.fecha_inicio_sintomas == null){
                    this.$alerError('El campo fecha_inicio_sintomas es requerido')
                    return;
                }
                if(this.formRegistro.clasificacion == null){
                    this.$alerError('El campo clasificacion es requerido')
                    return;
                }
                if(this.formRegistro.telefono == null){
                    this.$alerError('El campo telefono es requerido')
                    return;
                }
                if (this.formRegistro.destino_paciente == null) {
                    this.$alerError('Seleccione el destino del paciente.')
                    return;
                }
                axios.post('/api/covid/registro', this.formRegistro).then(res => {
                    this.$alerSuccess(res.data.message);
                    this.clearFields();

                    this.$router.push(
                        "/covi/ingreso?cita_paciente=" + res.data.cita_paciente_id
                    );
                    EventBus.$emit("call-order", res.data.paciente);
                })
            },
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },
        }
    }

</script>

<style>

</style>
