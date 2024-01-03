<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-stepper v-model="e6" vertical>
                <v-stepper-step :complete="e6 > 1" editable :edit-icon="$vuetify.icons.complete" step="1">
                    ACTUALIZACIÓN DATOS DEL PACIENTE
                </v-stepper-step>
                <v-stepper-content step="1">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Region" v-model="formPaciente.Region"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Ut" v-model="formPaciente.Ut"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Primer_Nom" v-model="formPaciente.Primer_Nom"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="SegundoNom" v-model="formPaciente.SegundoNom"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Primer_Ape" v-model="formPaciente.Primer_Ape"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Segundo_Ape" v-model="formPaciente.Segundo_Ape"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['TI','CC','RC','CE','PA','CN','SA','PE']" label="Tipo documento"
                                    required v-model="formPaciente.Tipo_Doc"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Numero documento" type="number" v-model="formPaciente.Num_Doc">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-radio-group label="Genero biologico" v-model="formPaciente.Sexo">
                                    <v-radio key="M" label="Masculino" value="M" color="info">
                                    </v-radio>
                                    <v-radio key="F" label="Femenino" value="F" color="info"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Fecha_Naci" type="date" v-model="formPaciente.Fecha_Naci"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select
                                    :items="['Sin discapacidad','Fisica','Mental/psiquica','Visual','Auditiva','Sordo - Ceguera']"
                                    label="Discapacidad" v-model="formPaciente.Discapacidad"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-radio-group label="Grado discapacidad" v-model="formPaciente.Grado_Discapacidad">
                                    <v-radio key="Leve" label="Leve" value="Leve" color="info">
                                    </v-radio>
                                    <v-radio key="Moderada" label="Moderada" value="Moderada" color="info"></v-radio>
                                    <v-radio key="Severa" label="Severa" value="Severa" color="info"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Tipo_Afiliado" v-model="formPaciente.Tipo_Afiliado"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-autocomplete label="IPS atención" :items="entidades" v-model="formPaciente.IPS"
                                    item-text="nombre" item-value="id" required></v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select
                                    :items="['Indígena','Gitano','Raizal','Palenquero','Negro(a)','Mulato(a)','Afrocolombiano(a)','Afro descendiente']"
                                    label="Etnia" required v-model="formPaciente.Etnia"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Laboraen" v-model="formPaciente.Laboraen"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-autocomplete label="Municipio labora" :items="municipios" item-text="municipio"
                                    item-value="id" v-model="formPaciente.Mpio_Labora" required></v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Direccion_Residencia" v-model="formPaciente.Direccion_Residencia">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Mpio_Residencia" v-model="formPaciente.Mpio_Residencia">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Telefono" v-model="formPaciente.Telefono"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Correo1" v-model="formPaciente.Correo1"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Correo2" v-model="formPaciente.Correo2"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['1','2','3','4','5','6','7','8']" label="Estrato" required
                                    v-model="formPaciente.Estrato"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Celular1" v-model="formPaciente.Celular1" type="number" maxlength="10"
                                oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                min="1"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Celular2" v-model="formPaciente.Celular2"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Num_Hijos" v-model="formPaciente.Num_Hijos"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']" label="RH"
                                    required v-model="formPaciente.RH"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select
                                    :items="['Bachiller','Tecnico','Tecnologo','Universitario','Posgrado','Maestria','Doctorado']"
                                    label="Escolaridad" required v-model="formPaciente.Nivelestudio"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Aseguradora" v-model="formPaciente.Aseguradora"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Ocupacion" v-model="formPaciente.Ocupacion"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="nivel" v-model="formPaciente.nivel"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-autocomplete label="Entidad afiliada" v-model="formPaciente.entidad_id"
                                    :items="entidades" item-text="nombre" item-value="id" required>
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['Soltero','Casado','Unión Libre','Separado','Viudo']" label="estado civil"
                                    required v-model="formPaciente.estado_civil"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['Coordinador(a)','Directivo(a)','Docente','Orientador(a)','Rector(a)']" label="cargo laboral"
                                    required v-model="formPaciente.cargo_laboral"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['0','1 a 2','3 a 4','> 4']" label="composicion familiar"
                                    required v-model="formPaciente.composicion_familiar"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['Propia','Arriendo','Familiar','Compartida']" label="vivienda"
                                    required v-model="formPaciente.vivienda"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['Ninguna','(1-3)','(4-6)','Mas de  6']" label="personas a cargo"
                                    required v-model="formPaciente.personas_a_cargo"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['Carrera administrativa ','Provisional ','Libre nombramiento ','Prestación de servicios','Honorarios'
                                ]" label="tipo contratacion"
                                    required v-model="formPaciente.tipo_contratacion"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['< 1 año','1-a 5 años ','5-10 años ','10-15 años ','> 15 años']" label="antiguedad en empresa"
                                    required v-model="formPaciente.antiguedad_en_empresa"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['< 1 año','1-a 5 años','5-10 años','10-15 años','> 15 años']" label="antiguedad cargo actual"
                                    required v-model="formPaciente.antiguedad_cargo_actual"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select :items="['Ninguno ','(1 - 3)','(4 -6) ','> 6']" label="# cursos a cargo"
                                    required v-model="formPaciente.numero_cursos_a_cargo"></v-select>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextAtencion()">Guardar y seguir</v-btn>
                </v-stepper-content>
                <v-stepper-step :complete="e6 > 2" editable :edit-icon="$vuetify.icons.complete" step="2">
                    ATENCIÓN
                </v-stepper-step>
                <v-stepper-content step="2">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-select
                                :items="motivos"
                                v-model="data.Motivoconsulta"
                                label="Selecciona el tipo de consulta">
                            </v-select>
                        </v-container>
                    </v-card>
                    <v-btn color="primary" round @click="nextAntecedentesPersonales()">Guardar y seguir</v-btn>
                </v-stepper-content>
                <v-stepper-step @click="fetchAntecedentePersonal(), fetchAntecedentes()" :complete="e6 > 3" editable
                    :edit-icon="$vuetify.icons.complete" step="3">ANTECEDENTES
                    PERSONALES</v-stepper-step>
                <v-stepper-content step="3">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm4 d-flex>
                                            <v-autocomplete v-model="antecedent.cie10_id" append-icon="search" :items="cieConcat"
                                                item-disabled="customDisabled" item-text="nombre" item-value="id"
                                                label="Diagnóstico">
                                            </v-autocomplete>
                                            <!-- <v-autocomplete label="Selecciona Antecedentes" :items="antecedentes"
                                                item-text="Nombre" item-value="id" v-model="antecedent.antecedente">
                                            </v-autocomplete> -->
                                        </v-flex>

                                        <v-flex xs12 sm6 d-flex>
                                            <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                                v-model="antecedent.descripcion">
                                            </v-textarea>
                                        </v-flex>

                                        <v-flex xs12 sm1 d-flex>
                                            <v-btn fab dark color="success" @click="guardarAntecedente()">
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
                                    <v-data-table :items="antecedentePaci" :headers="antecedenteP" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.created_at }}</td>
                                            <td class="text-xs">{{ props.item.name }}</td>
                                            <td class="text-xs">{{ props.item.Nombre }}</td>
                                            <td class="text-xs">{{ props.item.cie }}</td>
                                            <td class="text-xs">{{ props.item.Descripcion }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                    <v-btn color="primary" round @click="nextParentesco()">Seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step @click="fetchAntecedenteFamiliar(), fetchAntecedentes(), fetchParentesco()"
                    :complete="e6 > 4" editable :edit-icon="$vuetify.icons.complete" step="4">ANTECEDENTES
                    FAMILIARES</v-stepper-step>
                <v-stepper-content step="4">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-autocomplete v-model="antecedent.cie10_id" append-icon="search" :items="cieConcat"
                                                item-disabled="customDisabled" item-text="nombre" item-value="id"
                                                label="Diagnóstico">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-autocomplete label="Selecciona Familiar" :items="parentesco"
                                                item-text="Nombre" item-value="id" v-model="antecedent.familiar">
                                            </v-autocomplete>
                                        </v-flex>

                                        <v-flex xs12 sm5 d-flex>
                                            <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                                v-model="antecedent.descripcion">
                                            </v-textarea>
                                        </v-flex>

                                        <v-flex xs12 sm1 d-flex>
                                            <v-btn fab dark color="success" @click="guardarAntecedentefamiliar()">
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
                                    <v-data-table :items="antecedenteFami" :headers="familiare" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.created_at }}</td>
                                            <td class="text-xs">{{ props.item.name }}</td>
                                            <td class="text-xs">{{ props.item.Nombre }}</td>
                                            <td class="text-xs">{{ props.item.familiar }}</td>
                                            <td class="text-xs">{{ props.item.Descripcion }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                    <v-btn color="primary" round @click="nextAntecedentesOcupacionales()">Seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 5" editable :edit-icon="$vuetify.icons.complete" step="5">
                    ANTECEDENTES OCUPACIONALES
                </v-stepper-step>
                <v-stepper-content step="5">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm6 class="px-2">
                                <v-text-field label="Empresa" v-model="antecedenteOcupacional.antecedente_empresa">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 class="px-2">
                                <v-text-field label="Cargo" v-model="antecedenteOcupacional.antecedente_cargo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm1 class="px-2">
                                <v-select :items="['x', '']" label="F" v-model="antecedenteOcupacional.f"></v-select>
                            </v-flex>
                            <v-flex xs12 sm1 class="px-2">
                                <v-select :items="['x', '']" label="Q" v-model="antecedenteOcupacional.q"></v-select>
                            </v-flex>
                            <v-flex xs12 sm1 class="px-2">
                                <v-select :items="['x', '']" label="B" v-model="antecedenteOcupacional.b"></v-select>
                            </v-flex>
                            <v-flex xs12 sm1 class="px-2">
                                <v-select :items="['x', '']" label="Erg" v-model="antecedenteOcupacional.erg"></v-select>
                            </v-flex>
                            <v-flex xs12 sm1 class="px-2">
                                <v-select :items="['x', '']" label="Psic" v-model="antecedenteOcupacional.psic"></v-select>
                            </v-flex>
                            <v-flex xs12 sm1 class="px-2">
                                <v-select :items="['x', '']" label="Mec" v-model="antecedenteOcupacional.mec"></v-select>
                            </v-flex>
                            <v-flex xs12 sm1 class="px-2">
                                <v-select :items="['x', '']" label="Elec" v-model="antecedenteOcupacional.elec"></v-select>
                            </v-flex>
                            <v-flex xs12 sm1 class="px-2">
                                <v-select :items="['x', '']" label="Otro" v-model="antecedenteOcupacional.otro"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Tiempo" v-model="antecedenteOcupacional.tiempo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field label="Uso E.P.P" v-model="antecedenteOcupacional.uso_e_p_p">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs12 md12 class="px-2">
                                <v-radio-group label="HA SUFRIDO ACCIDENTES DE TRABAJO?"
                                    v-model="antecedenteOcupacional.accidentes_trabajo" row>
                                    <v-radio label="Si" value="si" color="success"></v-radio>
                                    <v-radio label="No" value="no" color="red"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-layout row wrap v-if="antecedenteOcupacional.accidentes_trabajo == 'si'">
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="fecha" type="date"
                                        v-model="antecedenteOcupacional.fecha_accidentes">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="empresa" v-model="antecedenteOcupacional.empresa_accidentes">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="causa" v-model="antecedenteOcupacional.causa">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="tipo_lesion" v-model="antecedenteOcupacional.tipo_lesion">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3 class="px-2">
                                    <v-text-field label="parte_afectada"
                                        v-model="antecedenteOcupacional.parte_afectada">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="dias_incap" v-model="antecedenteOcupacional.dias_incap">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="secuelas" v-model="antecedenteOcupacional.secuelas">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs12 md12 class="px-2">
                                <v-radio-group label="HA SUFRIDO ENFERMEDADES PROFESIONALES?"
                                    v-model="antecedenteOcupacional.enfermedades_profesionales" row>
                                    <v-radio label="Si" value="SI" color="success"></v-radio>
                                    <v-radio label="No" value="NO" color="red"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-layout row wrap v-if="antecedenteOcupacional.enfermedades_profesionales == 'SI'">
                                <v-flex xs12 sm3 class="px-2">
                                    <v-text-field label="fecha" type="date" v-model="antecedenteOcupacional.fecha">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="empresa" v-model="antecedenteOcupacional.empresa">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm5 class="px-2">
                                    <v-text-field label="diagnostico" v-model="antecedenteOcupacional.diagnostico">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="reubicacion" v-model="antecedenteOcupacional.reubicacion">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3 class="px-2">
                                    <v-text-field label="indemnización" v-model="antecedenteOcupacional.indemnizacion">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-layout>
                        <v-flex xs2 offset-xs5 offset-md2 offset-lg9>
                            <v-btn round dark color="success" @click="addAntecedente()">
                                <v-icon>add</v-icon> Agregar
                            </v-btn>
                        </v-flex>
                    </v-card>
                    <v-card>
                        <v-data-table :headers="headers" :items="antOcupacional" class="elevation-1">
                            <template v-slot:items="props">
                                <td>{{ props.item.antecedente_empresa}}</td>
                                <td class="text-xs-right">{{ props.item.antecedente_cargo}}</td>
                                <td class="text-xs-right">{{ props.item.f }}</td>
                                <td class="text-xs-right">{{ props.item.q }}</td>
                                <td class="text-xs-right">{{ props.item.b }}</td>
                                <td class="text-xs-right">{{ props.item.erg }}</td>
                                <td class="text-xs-right">{{ props.item.psic }}</td>
                                <td class="text-xs-right">{{ props.item.mec }}</td>
                                <td class="text-xs-right">{{ props.item.elec }}</td>
                                <td class="text-xs-right">{{ props.item.otro }}</td>
                                <td class="text-xs-right">{{ props.item.tiempo }}</td>
                                <td class="text-xs-right">{{ props.item.uso_e_p_p }}</td>
                                <td class="text-xs-right">{{ props.item.accidentes_trabajo }}</td>
                                <td class="text-xs-right">{{ props.item.fecha_accidentes }}</td>
                                <td class="text-xs-right">{{ props.item.empresa_accidentes }}</td>
                                <td class="text-xs-right">{{ props.item.causa }}</td>
                                <td class="text-xs-right">{{ props.item.tipo_lesion }}</td>
                                <td class="text-xs-right">{{ props.item.parte_afectada }}</td>
                                <td class="text-xs-right">{{ props.item.dias_incap }}</td>
                                <td class="text-xs-right">{{ props.item.secuelas }}</td>
                                <td class="text-xs-right">{{ props.item.enfermedades_profesionales }}</td>
                                <td class="text-xs-right">{{ props.item.fecha }}</td>
                                <td class="text-xs-right">{{ props.item.empresa }}</td>
                                <td class="text-xs-right">{{ props.item.diagnostico }}</td>
                                <td class="text-xs-right">{{ props.item.reubicacion }}</td>
                                <td class="text-xs-right">{{ props.item.indemnizacion }}</td>
                            </template>
                            <template v-slot:pageText="props">
                                Lignes {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                            </template>
                        </v-data-table>
                    </v-card>
                    <v-btn color="primary" round @click="nextSincondicionar()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 6" editable :edit-icon="$vuetify.icons.complete" step="6">
                    VACUNAS
                </v-stepper-step>
                <v-stepper-content step="6">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md12 class="px-2">
                                <v-radio-group label="VACUNAS" v-model="medicoSaludOcupacional.vacunas" row>
                                    <v-radio label="REFIERE" value="si" color="success"></v-radio>
                                    <v-radio label="NO REFIERE" value="no" color="red"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-layout row wrap v-if="medicoSaludOcupacional.vacunas == 'si'">
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="HEPATITIS" v-model="medicoSaludOcupacional.hepatitis">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="DOSIS" v-model="medicoSaludOcupacional.dosis_v">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="ULTIMA DOSIS" v-model="medicoSaludOcupacional.ultima_dosis">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="T_T" v-model="medicoSaludOcupacional.t_t">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3 class="px-2">
                                    <v-text-field label="T_D" v-model="medicoSaludOcupacional.t_d">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="DOSIS" v-model="medicoSaludOcupacional.dosi">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3 class="px-2">
                                    <v-text-field label="OTRAS" v-model="medicoSaludOcupacional.otras">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3 class="px-2">
                                    <v-text-field label="FECHAS" type="date" v-model="medicoSaludOcupacional.Fechas">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 class="px-2">
                                    <v-textarea
                                        name="input-7-1"
                                        label="OBSERVACIONES"
                                        hint="Hint text"
                                        v-model="medicoSaludOcupacional.observacion_vacunas">
                                    </v-textarea>
                                </v-flex>
                            </v-layout>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextGineco()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 7" editable :edit-icon="$vuetify.icons.complete" step="7"
                    v-if="formPaciente.Sexo == 'F'">
                    GINECOOBSTÉTRICOS
                </v-stepper-step>
                <v-stepper-content step="7">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md12 class="px-2">
                                <v-radio-group label="ginecoobstetricos" v-model="medicoSaludOcupacional.ginecoobstetricos" row>
                                    <v-radio label="APLICA" value="si" color="success"></v-radio>
                                    <v-radio label="NO APLICA" value="no" color="red"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-layout row wrap v-if="medicoSaludOcupacional.ginecoobstetricos == 'si'">
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="MENARQUIA" v-model="medicoSaludOcupacional.menarquia">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="CICLOS" v-model="medicoSaludOcupacional.ciclos">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="FUM" v-model="medicoSaludOcupacional.fum">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="G" v-model="medicoSaludOcupacional.g">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3 class="px-2">
                                    <v-text-field label="P" v-model="medicoSaludOcupacional.p">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 class="px-2">
                                    <v-text-field label="C" v-model="medicoSaludOcupacional.c">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="A" v-model="medicoSaludOcupacional.a">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="V" v-model="medicoSaludOcupacional.v">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="FUP" v-model="medicoSaludOcupacional.fup">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs12 md12 class="px-2">
                                <v-radio-group label="planificacion" v-model="medicoSaludOcupacional.planificacion" row>
                                    <v-radio label="SI" value="si" color="success"></v-radio>
                                    <v-radio label="NO" value="no" color="red"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-layout row wrap v-if="medicoSaludOcupacional.planificacion == 'si'">
                                <v-flex xs12 sm2 class="px-2">
                                    <v-radio-group label="metodo" v-model="medicoSaludOcupacional.metodo" row>
                                        <v-radio label="ANTICONCEPTIVOS ORALES " value="si-1" color="success"></v-radio>
                                        <v-radio label="SUBDERMICO" value="si-2" color="orange"></v-radio>
                                        <v-radio label="DIU" value="si-3" color="yellow"></v-radio>
                                        <v-radio label="PRESERVATIVO" value="si-4" color="blue"></v-radio>
                                        <v-radio label="INYECTABLE MENSUAL" value="si-5" color="pink"></v-radio>
                                        <v-radio label="INYECTABLE TRIMESTRAL" value="si-6" color="primary"></v-radio>
                                        <v-radio label="DEFINITIVO" value="si-7" color="warning"></v-radio>
                                        <v-radio label="MIRENA" value="si-8" color="info"></v-radio>
                                        <v-radio label="ANILLO VAGINAL" value="si-9" color="coffe"></v-radio>
                                        <v-radio label="VASECTOMIA" value="si-10" color="purple"></v-radio>
                                    </v-radio-group>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="ULTIMA CITOLOGIA" v-model="medicoSaludOcupacional.ultima_citologia">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 class="px-2">
                                    <v-text-field label="RESULTADO" v-model="medicoSaludOcupacional.resultado">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 class="px-2">
                                    <v-select v-model="medicoSaludOcupacional.tratada" :items="si_no" label="TRATADA"></v-select>
                                </v-flex>
                            </v-layout>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextHabitos()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 8" editable :edit-icon="$vuetify.icons.complete" step="8">
                    HÁBITOS
                </v-stepper-step>
                <v-stepper-content step="8">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md2 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.fuma" :items="si_no" label="Fuma"></v-select>
                            </v-flex>
                            <v-flex xs12 md2 class="px-2" v-if="medicoSaludOcupacional.fuma == 'SI'">
                                <v-select v-model="medicoSaludOcupacional.anos_fumador" :items="anosF" label="Años de Fumador">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md2 class="px-2" v-if="medicoSaludOcupacional.fuma == 'SI'">
                                <v-select v-model="medicoSaludOcupacional.cigarrillos_al_dia" :items="cigarrillos"
                                    label="Cigarrillos al día"></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.fumo" :items="si_no" label="Fumo"></v-select>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2" v-if="medicoSaludOcupacional.fumo == 'SI'">
                                <v-text-field label="Hace cuanto no fuma" v-model="medicoSaludOcupacional.hace_cuanto_no_fuma">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.alcohol" :items="si_no" label="Alcohol"></v-select>
                            </v-flex>
                            <v-layout row wrap v-if="medicoSaludOcupacional.alcohol == 'SI'">
                                <v-flex xs12 sm3 class="px-2">
                                    <v-select v-model="medicoSaludOcupacional.frecuencia" :items="copas" label="Frecuencia">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm3 class="px-2">
                                    <v-select v-model="medicoSaludOcupacional.tipo" :items="tipos" label="Tipo"></v-select>
                                </v-flex>
                            </v-layout>
                            <v-flex xs12 sm3 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.sustancias_psico_activas" :items="si_no"
                                    label="Sustancias Psico Activas"></v-select>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2" v-if="medicoSaludOcupacional.sustancias_psico_activas == 'SI'">
                                <v-text-field label="Cuál(es)" v-model="medicoSaludOcupacional.cuales"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.practica_deporte" :items="si_no"
                                    label="Practica Deporte o ejercicio?"></v-select>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2" v-if="medicoSaludOcupacional.practica_deporte == 'SI'">
                                <v-text-field label="Cuál" v-model="medicoSaludOcupacional.cual"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm3 class="px-2" v-if="medicoSaludOcupacional.practica_deporte == 'SI'">
                                <v-select v-model="medicoSaludOcupacional.regularidad" :items="regular" label="Regularidad">
                                </v-select>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextRevisionSistemas()">perro</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 9" editable :edit-icon="$vuetify.icons.complete" step="9">
                    REVISIÓN DE SISTEMAS
                </v-stepper-step>
                <v-stepper-content step="9">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.cabeza_y_orl" :items="refiere" label="Cabeza y orl">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.sistema_neurologico" :items="refiere"
                                    label="Sistema neurologico"></v-select>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.sistema_cardiopulmonar" :items="refiere"
                                    label="Sistema cardiopulmonar"></v-select>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.sistema_digestivo" :items="refiere" label="Sistema digestivo">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="sistema_musculo_esqueletico"
                                    v-model="medicoSaludOcupacional.sistema_musculo_esqueletico">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="sistema_genitourinario" v-model="medicoSaludOcupacional.sistema_genitourinario">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="piel_y_faneras" v-model="medicoSaludOcupacional.piel_y_faneras">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="otros" v-model="medicoSaludOcupacional.otros">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextCsalus()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 10" editable :edit-icon="$vuetify.icons.complete" step="10">
                    ESPIROMETRIA
                </v-stepper-step>
                <v-stepper-content step="10">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md12 class="px-2">
                                <v-radio-group label="ESPIROMETRIA" v-model="medicoSaludOcupacional.espirometria" row>
                                    <v-radio label="SI" value="si" color="success"></v-radio>
                                    <v-radio label="NO" value="no" color="red"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs12 class="px-2" v-if="medicoSaludOcupacional.espirometria == 'si'">
                                <v-textarea
                                    name="input-7-1"
                                    label="Si espirometría"
                                    v-model="medicoSaludOcupacional.espirometria_si">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12 class="px-2" v-if="medicoSaludOcupacional.espirometria == 'no'">
                                <v-textarea
                                    name="input-7-1"
                                    label="No espirometría"
                                    v-model="medicoSaludOcupacional.espirometria_no">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextCndiciones()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 11" editable :edit-icon="$vuetify.icons.complete" step="11">
                    CONDICIONES DE SALUD
                </v-stepper-step>
                <v-stepper-content step="11">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md2 class="px-2">
                                <v-select
                                :items="secretarias"
                                item-text="nombre" item-value="nombre"
                                v-model="medicoSaludOcupacional.nombre_de_la_empresa" label="Nombre de la empresa">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-select :items="['Coordinador(a)','Directivo(a)','Docente','Orientador(a)','Rector(a)']" label="cargo laboral"
                                    required v-model="medicoSaludOcupacional.cargo"></v-select>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-select :items="['< 1 año','1-a 5 años','5-10 años','10-15 años','> 15 años']" label="antiguedad"
                                    required v-model="medicoSaludOcupacional.antiguedad"></v-select>
                            </v-flex>
                            <v-flex xs12 md2 class="px-2">
                                <v-select :items="tipoExamen" v-model="medicoSaludOcupacional.tipoExamen" label="tipoExamen">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="factoresRiesgo" v-model="medicoSaludOcupacional.factoresRiesgo"
                                    label="factoresRiesgo"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.antecedentesenfermedad" :items="si_no"
                                    label="antecedentes de enfermedad">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="origenEnfermedades" v-model="medicoSaludOcupacional.origenEnfermedades"
                                    label="origenEnfermedades"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.antecedentedetrabajo" :items="si_no"
                                    label="antecedente de accidente de trabajo">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.antecedenteenfermedadlaboral" :items="si_no"
                                    label="antecedente de enfermedad laboral">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="enfermedadComun" v-model="medicoSaludOcupacional.enfermedadComun"
                                    label="enfermedadComun"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.antecedentesfamiliares" :items="si_no"
                                    label="antecedentes familiares">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaAntecedente" v-model="medicoSaludOcupacional.patologiaAntecedente"
                                    label="patologiaAntecedente"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="Corporal" v-model="medicoSaludOcupacional.masaCorporal" label="masaCorporal">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaOsteomuscular" v-model="medicoSaludOcupacional.patologiaOsteomuscular"
                                    label="patologiaOsteomuscular"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaCardiovascular" v-model="medicoSaludOcupacional.patologiaCardiovascular"
                                    label="patologiaCardiovascular"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaMetabolica" v-model="medicoSaludOcupacional.patologiaMetabolica"
                                    label="patologiaMetabolica"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="infecciososParasitaria" v-model="medicoSaludOcupacional.infecciososParasitaria"
                                    label="infecciososParasitaria"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaSangre" v-model="medicoSaludOcupacional.patologiaSangre"
                                    label="patologiaSangre"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="trastronosMentales" v-model="medicoSaludOcupacional.trastronosMentales"
                                    label="trastronosMentales"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaNervioso" v-model="medicoSaludOcupacional.patologiaNervioso"
                                    label="patologiaNervioso"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaOjo" v-model="medicoSaludOcupacional.patologiaOjo" label="patologiaOjo">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaOido" v-model="medicoSaludOcupacional.patologiaOido" label="patologiaOido">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaRespiratorio" v-model="medicoSaludOcupacional.patologiaRespiratorio"
                                    label="patologiaRespiratorio"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaDigestivo" v-model="medicoSaludOcupacional.patologiaDigestivo"
                                    label="patologiaDigestivo"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaPiel" v-model="medicoSaludOcupacional.patologiaPiel" label="patologiaPiel">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="patologiaUrinario" v-model="medicoSaludOcupacional.patologiaUrinario"
                                    label="patologiaUrinario"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="malformacionCongenitas" v-model="medicoSaludOcupacional.malformacionCongenitas"
                                    label="malformacionCongenitas"></v-select>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-select :items="diagnosticoNeoplasicos" v-model="medicoSaludOcupacional.diagnosticos_neoplasicos"
                                    label="Diagnosticos Neoplasicos"></v-select>
                            </v-flex>

                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextExamenfisico()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 12" editable :edit-icon="$vuetify.icons.complete" step="12">
                    EXAMEN FÍSICO
                </v-stepper-step>
                <v-stepper-content step="12">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm12 class="px-2">
                                <v-textarea outline name="input-7-4" label="CONDICIONES GENERALES"
                                    v-model="medicoSaludOcupacional.condiciones_generales"></v-textarea>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-select v-model="medicoSaludOcupacional.dominancia_motora" :items="motora"
                                    label="DOMINANCIA MOTORA"></v-select>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="TALLA" type="number" @change="totalIMC" min="0" v-model="medicoSaludOcupacional.talla">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="PESO" type="number" @change="totalIMC" min="0" v-model="medicoSaludOcupacional.peso">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="FC" v-model="medicoSaludOcupacional.f_c">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="FR" v-model="medicoSaludOcupacional.f_r">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="IMC" v-model="medicoSaludOcupacional.imc" readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 class="px-2">
                                <v-text-field type="number" label="perimetro abdominal" v-model="medicoSaludOcupacional.perimetroabdominal">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs2 px-1>
                                    <v-text-field label="Presión / Sistólica" value type="number"
                                        v-model="medicoSaludOcupacional.presionsistolica"></v-text-field>
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <v-text-field label="Presión / Diastólica" value type="number"
                                        v-model="medicoSaludOcupacional.presiondiastolica"></v-text-field>
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <v-text-field label="Presión Arterial Media" value v-model="medicoSaludOcupacional.presionarterialmedia" readonly></v-text-field>
                                </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="TA" v-model="medicoSaludOcupacional.t">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="CABEZA" v-model="medicoSaludOcupacional.cabeza">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="OJOS" v-model="medicoSaludOcupacional.ojos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="FONDO DE OJOS" v-model="medicoSaludOcupacional.fondo_de_ojos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="OIDOS" v-model="medicoSaludOcupacional.oidos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="OTOSCOPIA" v-model="medicoSaludOcupacional.otoscopia">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="NARIZ" v-model="medicoSaludOcupacional.nariz">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="BOCA" v-model="medicoSaludOcupacional.boca">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="DENTADURA" v-model="medicoSaludOcupacional.dentadura">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="FARINGE" v-model="medicoSaludOcupacional.faringe">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="CUELLO" v-model="medicoSaludOcupacional.cuello">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="MAMAS" v-model="medicoSaludOcupacional.mamas">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="TORAX" v-model="medicoSaludOcupacional.torax">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="CORAZON" v-model="medicoSaludOcupacional.corazon">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="PULMONES" v-model="medicoSaludOcupacional.pulmones">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="COLUMNA" v-model="medicoSaludOcupacional.columna">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="ABDOMEN" v-model="medicoSaludOcupacional.abdomen">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="GENITALES EXTERNOS" v-model="medicoSaludOcupacional.genitales_externos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="MIEMBROS SUPERIORES" v-model="medicoSaludOcupacional.miembros_sup">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="MIEMBROS INFERIORES" v-model="medicoSaludOcupacional.miembros_inf">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="REFLEJOS" v-model="medicoSaludOcupacional.reflejos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="MOTILIDAD" v-model="medicoSaludOcupacional.motilidad">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="FUERZA MUSCULAR" v-model="medicoSaludOcupacional.fuerza_muscular">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="MARCHA" v-model="medicoSaludOcupacional.marcha">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-text-field label="PIEL Y FRANELAS" v-model="medicoSaludOcupacional.piel_faneras">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 class="px-2">
                                <v-textarea
                                    name="input-7-1"
                                    label="AMPLIACIÓN DE HALLAZGOS"
                                    hint="Hint text"
                                    v-model="medicoSaludOcupacional.ampliacion_hallazgos"
                                    >
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextDiagnosticos()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 13" @click="fetchCie10s()" editable :edit-icon="$vuetify.icons.complete"
                    step="13">
                    DIAGNÓSTICOS
                </v-stepper-step>
                <v-stepper-content step="13">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm6 class="px-2">
                                <v-autocomplete v-model="Cie10_id" append-icon="search" :items="cieConcat"
                                    item-disabled="customDisabled" item-text="nombre" item-value="id"
                                    label="Diagnóstico">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-select :items="['Impresión diagnóstica', 'Confirmado nuevo','Confirmado repetido']"
                                    label="Tipo Diagnóstico" v-model="Tipodiagnostico" chips></v-select>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-autocomplete v-if="Tipodiagnostico != 'Impresión diagnóstica' && Tipodiagnostico"
                                    :items="['Oncológicos','Anticoagulados','Salud Mental','Nefroprotección ','Respiratorios',
                                    'Reumatologicos','Tramisibles Crónicas','Gestantes','Enfermedades Huerfanas','Trasplantados',
                                    'Polimedicados','Pacientes Tutela','Paciente Alto Costo','Domiciliario','Primera Infancia','Infancia',
                                    'Adolescencia','Juventud','Riesgo Cardiovascular','Grupal Curso Psicoprofilactico',
                                    'Apoyo Lactancia Materna','Detección Temprana Cáncer Cuello Uterino','Riesgo Cardiovascular',
                                    'Planificación Familiar','Atención Preconcepcional','Promocion De Alimentacion Y Nutricion','Prenatal',
                                    'Atención Del Posparto','Atención Del Recien Nacido','Detección Temprana Cancer De Mama', 'Adultez', 'Vejez'
						            ]" label="Marcar paciente" v-model="Marcapaciente" attach multiple chips></v-autocomplete>
                            </v-flex>
                            <v-btn color="primary" round dark v-on:click="addDiagnostico()">Ingresar Diagnóstico</v-btn>
                            <v-flex xs12 sm12>
                                <v-data-table :headers="headersDiagnostico" :items="Diagnostico" :search="search">
                                    <template v-slot:items="props">
                                        <tr :active="props.selected" @click="props.selected = !props.selected">
                                            <td>
                                                <v-checkbox color="primary" v-model="selectedItems" hide-details
                                                    :value="props.item">
                                                </v-checkbox>
                                            </td>
                                            <td>{{ props.item.Codigo }}</td>
                                            <td class="text-xs-center">{{ props.item.Tipodiagnostico }}</td>
                                            <td class="text-xs-center">
                                                <v-select :items="props.item.Marcapaciente" label="Marcar paciente"
                                                    v-model="props.item.Marcapaciente" attach multiple chips></v-select>
                                            </td>
                                            <td class="text-xs-center">
                                                <v-btn fab utline color="error" small @click="removeDiagnostico(props)">
                                                    <v-icon>clear</v-icon>
                                                </v-btn>
                                            </td>
                                        </tr>
                                    </template>
                                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your search
                                        for
                                        "{{ search }}"
                                        found no results.</v-alert>
                                </v-data-table>
                            </v-flex>
                            <v-btn color="primary" round dark v-on:click="submitDiagnostico()">Guardar y seguir</v-btn>
                        </v-layout>
                    </v-card>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 14" editable :edit-icon="$vuetify.icons.complete" step="14">
                    CONCEPTO MÉDICO DE APTITUD LABORAL
                </v-stepper-step>
                <v-stepper-content step="14">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md8 class="px-2">
                                <v-autocomplete :items="aptitud" v-model="medicoSaludOcupacional.aptitud_laboral_medico"
                                    label="CONCEPTO MÉDICO DE APTITUD LABORAL – MEDICO OCUPACIONAL">
                                </v-autocomplete>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextEpidemia()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 15" editable :edit-icon="$vuetify.icons.complete" step="15">
                    SISTEMAS DE VIGILANCIA EPIDEMIOLÓGICO
                </v-stepper-step>
                <v-stepper-content step="15">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md8 class="px-2">
                                <v-autocomplete :items="epidemiologico" v-model="medicoSaludOcupacional.vigilancia_epidemiologico"
                                    label="SISTEMAS DE VIGILANCIA EPIDEMIOLÓGICO">
                                </v-autocomplete>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextConducta()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 16" editable :edit-icon="$vuetify.icons.complete" step="16">
                    CONDUCTAS
                </v-stepper-step>
                <v-stepper-content step="16">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm6 class="px-2">
                                <v-textarea label="Plan de manejo" v-model="conducta.Planmanejo">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-textarea label="RECOMENDACIONES Y/O RESTRICCIONES LABORALES" v-model="conducta.Recomendaciones">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-autocomplete :items="[
                                'Control', 'Interconsulta', 'Urgencias', 'Consulta externa prioritaria', 'No aplica'
								]" label="Destino del paciente" append-icon="search" v-model="conducta.Destinopaciente"></v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm5>
                                <v-autocomplete :items="[
									'Consulta SST', 'No aplica'
								]" label="Finalidad" append-icon="search" v-model="conducta.Finalidad"></v-autocomplete>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="guardarConducta()">Guardar y terminar</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 17" editable :edit-icon="$vuetify.icons.complete" step="17">
                    FIRMA DEL PACIENTE
                </v-stepper-step>
                <v-stepper-content step="17">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm12 onmouseover>
                                <VueSignaturePad ref="signaturePad" />
                            </v-flex>
                            <v-flex xs12 sm12>
                                <v-card>
                                    <v-btn color="error" large @click="undo">Borrar</v-btn>
                                    <v-btn color="success" large @click="guardarFirma()">Guardar</v-btn>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </v-stepper-content>

            </v-stepper>

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
        </v-flex>
    </v-layout>
</template>
<script>
    import Swal from 'sweetalert2';
    import {
        EventBus
    } from '../../../../event-bus.js';
    export default {
        created() {
            this.citaPaciente = this.$route.query.cita_paciente;
            this.antecedent.cita_paciente = this.$route.query.cita_paciente;
            this.removeLocalStorage();
            this.getLocalStorage();
        },
        data() {
            return {
                motivos: ['Examenes ocupacionales periódicos', 'Examenes ocupacionales ingreso',
                    'Examenes ocupacionales egreso', 'Examenes ocupacionales post incapacidad',
                    'Examenes ocupacionales reubicación', 'Exámenes ocupacionales para Evento deportivos',
                    'Exámenes ocupacionales para evento Folclórico'],
                refiere: ['Refiere', 'No refiere'],
                motora: ['Derecha', 'Izquierda', 'Ambidiestro'],
                regular: ['diario', '1-3 veces por semana', '1 vez a la semana', 'nunca'],
                tipos: ['RON', 'CERVEZA', 'WHISKY', 'VINO', 'AGUARDIENTE', 'OTROS'],
                copas: ['1-3 COPAS', '3-6 COPAS', 'HASTA EMBRIGARSE'],
                cigarrillos: ['1-5', '5-10', '>10'],
                anosF: ['<1', '1-5', '>5'],
                si_no: ['SI', 'NO'],
                aptitud: ['Sin Recomendaciones Laborales', 'Con Recomendaciones  Laborales', 'Sin Restricciones  Laborales', 'Con Restricciones Laborales'],
                tipoExamen: ['Ingreso', 'Egreso', 'Periodico', 'Deportivo', 'Post incapacidad', 'Reintegro laboral', ],
                factoresRiesgo: [
                    'Riesgo Físico',
                    'Riesgo Químico',
                    'Riesgo Biológico',
                    'Riesgo de Carga Física y Psicosocial',
                    'Riesgo de Inseguridad',
                    'Riesgo del Medio Ambiente Físico y Social',
                    'Riesgo de Saneamiento Ambiental.',
                ],
                diagnosticoNeoplasicos: [
                    'Sin patologías ',
                    'Lipomas',
                    'Neoplasias benignas',
                    'Neoplasias malignas ',
                    'Neoplasias malignas de mamá y genitales',
                    'Miomatosis',
                ],
                origenEnfermedades: ['Origen Común', 'Origen Laboral'],
                enfermedadComun: [
                    'Patológicos',
                    'Psiquiatricos',
                    'Quirúrgicos',
                    'Alérgicos',
                    'Traumaticos',
                    'Farmacologicos',
                    'Ninguna',
                ],
                patologiaAntecedente: [
                    'Patología osteomuscular',
                    'Patología cardiovascular',
                    'Diagnósticos infecciosos o parasitaria',
                    'Diagnósticos neoplasicos',
                    'Patología  de  sangre y organos hematopoyeticos',
                    'Trastornos mentales y del comportamiento',
                    'Patología del sistema nervioso',
                    'Patología de ojo y sus anexos',
                    'Patología de oido y la apofisis mastoides',
                    'Patología del sistema respiratorio',
                    'Patología del aparato digestivo',
                    'Patología de la piel o tejido subcutaneo',
                    'Patología del aparato urinario',
                    'Malformaciones congénitas, deformidades y anomalías cromosomicas',
                ],
                Corporal: [
                    'Bajo ',
                    'Normal ',
                    'Sobrepeso ',
                    'Obesidad Tipo I',
                    'Obesidad Tipo II',
                    'Obesidad Tipo III',
                ],
                patologiaOsteomuscular: [
                    'Sin Patología osteomuscular',
                    'Esguines y torceduras del tobillo ',
                    'Costocondritis',
                    'Osteoporosis ',
                    'Fibromialgía',
                    'Mialgía',
                    'Trastornos de los tejidos blandos ',
                    'Neuralgía Y Neuritis No Especificada ',
                    'Fascitis Plantar ',
                    'Epicondilitis Lateral  ',
                    'Espolón calcaneo ',
                    'Epicondilitis Media',
                    'Tendinitis rotuliana ',
                    'Entesopatias del miembro inferior ',
                    'Tendinitis De Bíceps ',
                    'Síndrome Del Manguito Rotador ',
                    'Tendinitis radial ',
                    'Bursopatía ',
                    'Sinovitis y tenosinovitis',
                    'Dedo en gatillo',
                    'Ganglión',
                    'Otros Trastornos De Los Músculos ',
                    'Lumbago/Lumbalgía Mecánica ',
                    'Cervicalgía ',
                    'Dorsalgía ',
                    'Otras Dorsopatias No Clasificadas En Otras',
                    'Trastornos De Los Discos Intervertebrales ',
                    'Trastorno De Disco Lumbar con radiculopatia ',
                    'Trastorno Del Disco Cervical ',
                    'Osteocondrosis Espinal',
                    'Espondilosis ',
                    'Escoliosis ',
                    'Cifosis y lordosis ',
                    'Trastornos articulares ',
                    'trastornos aTrastornos De Los Ligamentos ',
                    'Trastornos  De La Rodilla ',
                    'Deformidad en varo/ en Flexión ',
                    'Muñeca om pie caido ',
                    'Pie Plano ',
                    'Otras Patologías Óseas Sin Especificar',
                    'Hallux Valgus',
                    'Artrosis Degenerativa',
                    'Poliartrosis ',
                    'Artralgias ',
                    'Gota ',
                    'Artritis ',
                    'Síndrome Del Túnel Del Carpo',
                ],
                patologiaCardiovascular: [
                    'Sin Patología Vascular',
                    'Soplo Cardiaco No Especificado',
                    'Bradicardia, no especificada ',
                    'Hipotensión',
                    'Linfadenopatía ',
                    'Otros trastornos de la venas ',
                    'Varices en otros sitios ',
                    'Hemorroides',
                    'Varices Sin Ulceración',
                    'Telangiectasia  Hereditaria',
                    'otras enfermedades vasculares periféricas ',
                    'Aterosclerosis',
                    'Aneurisma cerebral ',
                    'Insuficiencia Cardiaca',
                    'Arritmia Cardiaca Sin Especificar',
                    'Taquicardia',
                    'Estenosis de la válvula aortica ',
                    'pericarditis',
                    'Enfermedad cardiaca izquémica ',
                    'trombosis ',
                    'Otros Trastornos De La Conducción',
                    'Bloqueo De La Rama Izquierda Y Bloqueo Atrio Ventricular',
                    'Infarto Agudo De Miocardio',
                    'Angina de pecho ',
                    'Hipertensión Arterial',
                    'Miocarditis ',
                    'Otras cardiopatias reumáticas',
                ],
                patologiaMetabolica: [
                    'Sin patología metabólica',
                    'otros desordenes metabolicos ',
                    ' Dislipidemia',
                    'Tejido adiposo localizado ',
                    'Menopausia',
                    'Hiperprolactinemia',
                    'Hiperparatiroidismo',
                    'Hipoglicemia ',
                    'Diabetes mellitus tipo II',
                    'Diabetes  mellitus tipo I',
                    'Otros desórdenes de la tiroides',
                    'Otros bocios no tóxicos',
                    'Tirotoxicosis(Hipertiroidismo)',
                    '(Hipotiroidismo)',

                ],
                infecciososParasitaria: [
                    'Sin patología Infecciosa o Parasitaria ',
                    'otras patologias infecciosas ',
                    'Virus del papiloma ',
                    'Secuela Poliomelitis ',
                    'Toxoplasmosis ',
                    'enfermedad del chagas ',
                    'VIH',
                    'Poliomelitis',
                    'Eszema herpetico ',
                    'Sifilis ',
                    'Dengue Clasico ',
                    'Tuberculosis ',
                    'Gastroentreritis' ,
                ],
                patologiaSangre: [
                    'Sin patología de la sangre y de los órganos hematopoyéticos',
                    'Sarcoidosis ',
                    'leucemia y reacción leucemoide',
                    'Púrpura y hemorragias',
                    'Otros defectos de la coagulación',
                    'Hemofilia',
                    'Aplasia y otras anemias',
                    'Anemia hemolítica',
                    'Anemia nutricional',
                    'Síndrome anémico',
                ],
                trastronosMentales: [
                    'Sin patologia de trastornos mentales',
                    'Temblor Esencial',
                    'Insomnio',
                    'Trastornos del sueño ',
                    'Trastorno de la ingestion de alimentos ',
                    'Trastorno de estrés grave y de adpatación ',
                    'Trastorno obsesivo-compulsivo ',
                    'Trastorno De Ansiedad(fóbicos)',
                    'Distimia',
                    'Trastorno Depresivo Recurrente',
                    'Depresión',
                    'Trastorno Bipolar Afectivo',
                    'Otros Trastornos Mentales De Comportamiento',
                    'Alcoholismo ',
                    'Trastorno mental y de la personalidad',
                    'Demencia  sin especificar ',
                    'Farmacodependencia',
                ],
                patologiaNervioso: [
                    'Sin Patología del Sistema Nervioso',
                    'Mareo ',
                    'Páralisis de Bell ',
                    'Encefalopatia no especificada ',
                    'Paraplejía Sin Especificar ',
                    'Hemiplejia sin especificar  ',
                    'Miastenia gravis y trastornos neuromusculares ',
                    'Otros trastornos del sistema nervioso periferico ',
                    'Polineuropatía ',
                    'Neuralgía del trigemino ',
                    'Sindrome de apnea-hipoapnea',
                    'Ataques de isquemia cerebral transitoria',
                    'Otros sindromes de cefalea ',
                    'Migraña ',
                    'Movimientos Extrapiramidales ',
                    'Otras atrofias neuro-musculares ',
                    'esclerosis multiple ',
                    'TICS',
                    'Enfermedad de Parkinson' ,
                ],
                patologiaOjo: [
                    'Sin Patología del ojo y sus anexos ',
                    'Trauma ocular ',
                    'Queratosis Folicular ',
                    'Nistagmo ',
                    'Disminucion de l agudeza visual ',
                    'Ceguera y baja Visión',
                    'Defectos del campo visual',
                    'Ambliopía',
                    'Fotofobia',
                    'Otros trastornos  de refraccion ',
                    'Presbicia',
                    'Astigmatismo',
                    'Miopía',
                    'Hipermetropía',
                    'Estrabismo',
                    'Neuritis optica ',
                    'Glaucoma',
                    'Trastosnos de la retina ',
                    'Catarata, no especificada',
                    'Catarata senil',
                    'Queratocono',
                    'Pterigio',
                    'Conjuntivitis ',
                    'Sindrome de sequedad Ocular',
                    'Blefaroptosis / Xantelesmas ',
                    'Blefaritis',
                    'Chalazion(orzuelo)' ,
                ],
                patologiaOido: [
                    'Sin patologias del oido y de la apófisis mastoide ',
                    'Acufenos ',
                    'Otras perdidas de la audición / trastorno del oído  ',
                    'Otalgía ',
                    'Presbiacusia ',
                    'Hipoacusia neurosensorial ',
                    'Pérdida Del Oído Conductivo Unilateral',
                    'Pérdida Del Oído Conductiva Bilateral',
                    'Vertigo  No Especificado ',
                    'Otosclerosis',
                    'Enf. Meniere',
                    'Trastornos del sistema vestibular ',
                    'Perforacion del timpano ',
                    'Otitis ',
                    'Cerumen ',
                ],
                patologiaRespiratorio: [
                    'Sin Patología Del Sistema Respiratorio',
                    'Disfonia ',
                    'Asma',
                    'EPOC',
                    'Nodulos/páralisis  de las cuerdas vocales y la laringe ',
                    'Polipo de las cuerdas vocales ',
                    'Pólipo Nasal',
                    'Rinitis Alérgica Y Vasomotora',
                    'Bronquitis  aguda ',
                    'Bronconeumonía',
                    'Trastornos respiratorios (gripe-virus)',
                    'Laringitis',
                    'Faringitis Aguda',
                    'Amigdalitis',
                    'Sinusitis',
                ],
                patologiaDigestivo: [
                    'Sin Patologías Del Aparato Digestivo',
                    'Enf . del sistema digestivo ',
                    'Anodoncia parcial / Alteracion en los dientes',
                    'Colelitiasis',
                    'Colecistitis',
                    'Otras enfermedades del higado ',
                    'Cirrosis',
                    'Hernias',
                    'Estreñimiento',
                    'Diarrea/trastorno funcional ',
                    'Síndrome Del Intestino Irritable',
                    'Dispepsia ',
                    'Gastritis Aguda',
                    'Gastritis Crónica',
                    'Ulcera gastrica ',
                    'Enfermedades del esofago' ,
                ],
                patologiaPiel: [
                    'Sin patología de la piel y el tejido Subcutáneo',
                    'Ulcera/cicatriz de la piel ',
                    'Lupus eritematoso ',
                    'Cloasma ',
                    'Vitiligio',
                    'Rosacea ',
                    'Acne ',
                    'Alopecia Areata ',
                    'Eritema multiforme ',
                    'Uña Golpeada ',
                    'Urticaria ',
                    'Psoriasis',
                    'Otras Dermatitis',
                    'Dermatitis Alérgica De Contacto',
                    'Quiste pilomidal ',
                    'Herpes zoster ',
                    'Absceso Cutaneo ',
                    'Pitiriasis versicolor/hipertricosis ',
                ],
                patologiaUrinario: [
                    'Sin patología del aparato genitourinario',
                    'Embarazo Alto Riesgo',
                    'Embarazo Bajo Riesgo',
                    'Dismenorrea ',
                    'Enf. Quistica del riñon ',
                    'Diabetes en el embarazo ',
                    'Hemorragia uterina anormal ',
                    'Prolapso genital femenino ',
                    'Endometriosis',
                    'Trastornos de la mama',
                    'Trastornos de la  Próstata',
                    'IVU',
                    'Trastornos de la vejiga/utero/vagina',
                    'Cistitis ',
                    'Urolitiasis ',
                    'Insuficiencia renal',
                    'Hidrocele ',
                    'Ovario poliquístico',
                    'Ca. de próstata' ,
                ],
                malformacionCongenitas: [
                    'Sin malformaciones congénitas deformidades y anomalías cromosómicas',
                    'Labio de porino',
                    'Sindactilia',
                    'Ectropion congenito',
                    'Microtia',
                    'Defectos / ausencia de miembro No Especificado',
                ],
                epidemiologico: ['sistema de vigilancia epidemiológico osteomuscular',
                    'sistema de vigilancia epidemiológico voz', 'sistema de vigilancia epidemiológico psicosocial',
                    'no aplica'
                ],
                entidades: [],
                preload: false,
                e6: 1,
                headers: [{
                        text: 'antecedente_cargo',
                        value: 'antecedente_cargo'
                    },
                    {
                        text: 'f',
                        value: 'f'
                    },
                    {
                        text: 'q',
                        value: 'q'
                    },
                    {
                        text: 'b',
                        value: 'b'
                    },
                    {
                        text: 'erg',
                        value: 'erg'
                    },
                    {
                        text: 'psic',
                        value: 'psic'
                    },
                    {
                        text: 'mec',
                        value: 'mec'
                    },
                    {
                        text: 'elec',
                        value: 'elec'
                    },
                    {
                        text: 'otro',
                        value: 'otro'
                    },
                    {
                        text: 'tiempo',
                        value: 'tiempo'
                    },
                    {
                        text: 'uso_e_p_p',
                        value: 'uso_e_p_p'
                    },
                    {
                        text: 'accidentes_trabajo',
                        value: 'accidentes_trabajo'
                    },
                    {
                        text: 'fecha_accidentes',
                        value: 'fecha_accidentes'
                    },
                    {
                        text: 'empresa_accidentes',
                        value: 'empresa_accidentes'
                    },
                    {
                        text: 'causa',
                        value: 'causa'
                    },
                    {
                        text: 'tipo_lesion',
                        value: 'tipo_lesion'
                    },
                    {
                        text: 'parte_afectada',
                        value: 'parte_afectada'
                    },
                    {
                        text: 'dias_incap',
                        value: 'dias_incap'
                    },
                    {
                        text: 'secuelas',
                        value: 'secuelas'
                    },
                    {
                        text: 'enfermedades_profesionales',
                        value: 'enfermedades_profesionales'
                    },
                    {
                        text: 'fecha',
                        value: 'fecha'
                    },
                    {
                        text: 'empresa',
                        value: 'empresa'
                    },
                    {
                        text: 'diagnostico',
                        value: 'diagnostico'
                    },
                    {
                        text: 'reubicacion',
                        value: 'reubicacion'
                    },
                    {
                        text: 'indemnizacion',
                        value: 'indemnizacion'
                    }
                ],
                data: {
                    Motivoconsulta: '',
                    Enfermedadactual: ''
                },
                antecedent: {
                    antecedente: '',
                    cie10_id: '',
                    descripcion: '',
                    cita_paciente: '',
                    familiar: ''
                },
                formPaciente: {
                    idpaciente: null,
                    Primer_Nom: null,
                    SegundoNom: null,
                    Primer_Ape: null,
                    Segundo_Ape: null,
                    Tipo_Doc: null,
                    Num_Doc: null,
                    Sexo: null,
                    Fecha_Afiliado: null,
                    Fecha_Naci: null,
                    Discapacidad: null,
                    Grado_Discapacidad: null,
                    Tipo_Afiliado: null,
                    IPS: null,
                    Nivelestudio: null,
                    Etnia: null,
                    Nivel_Sisben: null,
                    Ocupacion: null,
                    Laboraen: null,
                    Mpio_Labora: null,
                    Telefono: null,
                    Correo1: null,
                    Estrato: null,
                    Celular1: null,
                    RH: null,
                    entidad_id: null,
                    Direccion_Residencia: null,
                    tipo_categoria: null,
                    tipo_contratacion: null,
                    antiguedad_en_empresa: null,
                },
                antecedenteOcupacional: {
                    antecedente_empresa: '',
                    antecedente_cargo: '',
                    f: '',
                    q: '',
                    b: '',
                    erg: '',
                    psic: '',
                    mec: '',
                    elec: '',
                    otro: '',
                    tiempo: '',
                    Uso_E_P_P: '',
                    accidentes_trabajo: '',
                    accidentes_trabajo: '',
                    fecha_accidentes: '',
                    empresa_accidentes: '',
                    causa: '',
                    tipo_lesion: '',
                    parte_afectada: '',
                    dias_incap: '',
                    secuelas: '',
                    enfermedades_profesionales: '',
                    enfermedades_profesionales: '',
                    fecha: '',
                    empresa: '',
                    diagnostico: '',
                    reubicacion: '',
                    indemnizacion: '',
                },
                citaPaciente: 0,
                antecedentes: [],
                antecedentePaci: [],
                antecedenteP: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Descripción'
                    }
                ],
                parentesco: [],
                antecedenteFami: [],
                familiare: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Parentesco'
                    },
                    {
                        text: 'Descripción'
                    }
                ],
                Cie10_id: '',
                cie10Array: [],
                cie10s: [],
                Tipodiagnostico: '',
                municipios: [],
                secretarias: [],
                headersDiagnostico: [{
                        text: "Marcar Principal",
                        align: "center",
                        value: "marcar"
                    },
                    {
                        text: "Diagnóstico",
                        align: "center",
                        value: "diagnostico"
                    },
                    {
                        text: "Tipo Diagnóstico",
                        align: "center",
                        value: "tipo_diagnostico"
                    },
                    {
                        text: "Marca",
                        align: "center",
                        value: "marca"
                    },
                    {
                        text: "",
                        align: "center",
                        value: ""
                    }
                ],
                antOcupacional: [],
                Diagnostico: [],
                search: '',
                Marcapaciente: [],
                selectedItems: {},
                DiagnosticoSaved: [],
                conducta: {
                    Planmanejo: '',
                    Recomendaciones: '',
                    Destinopaciente: '',
                    Finalidad: ''
                },
                medicoSaludOcupacional: {
                    f: '',
                    q: '',
                    b: '',
                    erg: '',
                    psic: '',
                    mec: '',
                    elec: '',
                    otro: '',
                    tiempo: '',
                    uso_e_p_p: '',
                    accidentes_trabajo: '',
                    fecha: '',
                    causa: '',
                    tipo_lesion: '',
                    parte_afectada: '',
                    dias_incap: '',
                    secuelas: '',
                    enfermedades_profesionales: '',
                    fecha: '',
                    empresa: '',
                    diagnostico: '',
                    reubicacion: '',
                    indemnizacion: '',
                    vacunas: '',
                    hepatitis: '',
                    dosis_v: '',
                    ultima_dosis: '',
                    t_t: '',
                    t_d: '',
                    dosi: '',
                    otras: '',
                    observacion_vacunas: '',
                    Fechas: '',
                    ginecoobstetricos: '',
                    menarquia: '',
                    ciclos: '',
                    fum: '',
                    g: '',
                    p: '',
                    c: '',
                    a: '',
                    v: '',
                    fup: '',
                    planificacion: '',
                    metodo: '',
                    ultima_citologia: '',
                    resultado: '',
                    tratada: '',
                    fuma: '',
                    fumo: '',
                    anos_fumador: '',
                    cigarrillos_al_dia: '',
                    hace_cuanto_no_fuma: '',
                    alcohol: '',
                    frecuencia: '',
                    tipo: '',
                    sustancias_psico_activas: '',
                    cuales: '',
                    practica_deporte: '',
                    cual: '',
                    regularidad: '',
                    cabeza_y_orl: 'No refiere',
                    sistema_neurologico: 'No refiere',
                    sistema_cardiopulmonar: 'No refiere',
                    sistema_digestivo: 'No refiere',
                    sistema_musculo_esqueletico: 'No refiere',
                    sistema_genitourinario: 'No refiere',
                    piel_y_faneras: 'No refiere',
                    otros: 'No refiere',
                    condiciones_generales: '',
                    dominancia_motora: '',
                    espirometria: '',
                    espirometria_si: '',
                    espirometria_no: '',
                    talla: '',
                    peso: '',
                    kg_p_a: '',
                    f_c: '',
                    f_r: '',
                    imc: '',
                    perimetroabdominal: '',
                    presionsistolica: '',
                    presiondiastolica: '',
                    presionarterialmedia: '',
                    t: '',
                    c_: '',
                    sato2: '',
                    cabeza: '',
                    ojos: '',
                    fondo_de_ojos: '',
                    oidos: '',
                    otoscopia: '',
                    nariz: '',
                    boca: '',
                    dentadura: '',
                    faringe: '',
                    cuello: '',
                    mamas: '',
                    torax: '',
                    corazon: '',
                    pulmones: '',
                    columna: '',
                    abdomen: '',
                    genitales_externos: '',
                    miembros_sup: '',
                    miembros_inf: '',
                    reflejos: '',
                    motilidad: '',
                    fuerza_muscular: '',
                    marcha: '',
                    piel_faneras: '',
                    ampliacion_hallazgos: '',
                    firma_medico_examinador: '',
                    firma_trabajador: '',
                    aptitud_laboral_medico: '',
                    vigilancia_epidemiologico: '',
                    tipoExamen: '',
                    nombre_de_la_empresa: '',
                    cargo: '',
                    antiguedad: '',
                    factoresRiesgo: '',
                    origenEnfermedades: '',
                    enfermedadComun: '',
                    patologiaAntecedente: '',
                    masaCorporal: '',
                    patologiaOsteomuscular: '',
                    patologiaCardiovascular: '',
                    patologiaMetabolica: '',
                    infecciososParasitaria: '',
                    patologiaSangre: '',
                    trastronosMentales: '',
                    patologiaNervioso: '',
                    patologiaOjo: '',
                    patologiaOido: '',
                    patologiaRespiratorio: '',
                    patologiaDigestivo: '',
                    patologiaPiel: '',
                    patologiaUrinario: '',
                    malformacionCongenitas: '',
                    diagnosticos_neoplasicos: '',
                    antecedentesenfermedad: '',
                    antecedentedetrabajo: '',
                    antecedenteenfermedadlaboral: '',
                    antecedentesfamiliares: '',
                }
            }
        },

        computed: {
            cieConcat() {
                if (this.cie10s !== undefined) {
                    return this.cie10Array = this.cie10s.map(cie => {
                        return {
                            id: cie.id,
                            codigo: cie.Codigo_CIE10,
                            nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                            customDisabled: false
                        };
                    });
                }
            }
        },
        watch: {
            "medicoSaludOcupacional.presionsistolica": function () {
                this.calcularMediaPresion();
            },
            "medicoSaludOcupacional.presiondiastolica": function () {
                this.calcularMediaPresion();
            }
        },

        mounted() {
            this.fetchAntecedentesOpucacionales();
            this.fetchOcupacionale();
            this.fetchCie10s();
            this.firstPaciente();
            this.fetchMunicipios();
            this.fetchSecretaria();
        },

        methods: {
            calcularMediaPresion() {
                this.medicoSaludOcupacional.presionarterialmedia =
                    (parseInt(this.medicoSaludOcupacional.presiondiastolica) * 2 +
                        parseInt(this.medicoSaludOcupacional.presionsistolica)) /
                    3;
                this.medicoSaludOcupacional.presionarterialmedia = Number.parseFloat(
                    this.medicoSaludOcupacional.presionarterialmedia
                ).toFixed(1);
            },
            fetchMunicipios() {
                axios.get('/api/municipio/lista')
                    .then(res => {
                        this.municipios = res.data
                    })
            },
            fetchSecretaria() {
                axios.get('/api/municipio/secretaria')
                    .then(res => {
                        console.log('secre', res.data);
                        this.secretarias = res.data
                    })
            },

            undo() {
                this.$refs.signaturePad.clearSignature();
            },
            saveFirma() {
                this.preload = true;
                const {
                    isEmpty,
                    data
                } = this.$refs.signaturePad.saveSignature();
                if (!isEmpty) {
                    let formData = new FormData();
                    formData.append("file", data);
                    axios.post('/api/cita/firmaPaciente/' + this.citaPaciente, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(res => {
                        this.imprimir();
                        this.e6 = 18
                        this.$alerSuccess(res.data);
                        this.$refs.signaturePad.clearSignature();
                    }).catch(err => {
                        this.preload = false;
                        console.log(err.response)
                    })
                } else {
                    this.$alerError('Debe ingresar una firma');
                    this.preload = false;
                }
            },
            firstPaciente() {
                axios.get(`/api/cita/${this.citaPaciente}/datospaciente`)
                    .then(res => {
                        this.formPaciente = res.data;
                    });
            },
            pacienteUpdate() {
                axios.post(`/api/cita/updatepaciente/${this.formPaciente.id}`, this.formPaciente)
                    .then(res => {
                        this.$alerSuccess("Paciente actualizado con exito!");
                    });
            },

            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },

            addDiagnostico() {
                if (this.Cie10_id) {
                    this.cie10Array.find(cie10 => {
                        if (cie10.id == this.Cie10_id) {
                            this.Codigo = cie10.codigo;
                        }
                    });
                }
                if (
                    this.Cie10_id &&
                    this.Tipodiagnostico != "Impresión diagnóstica" &&
                    this.Marcapaciente.length > 0
                ) {
                    this.Diagnostico.push({
                        Cie10_id: this.Cie10_id,
                        Tipodiagnostico: this.Tipodiagnostico,
                        Marcapaciente: this.Marcapaciente,
                        Esprimario: false,
                        Codigo: this.Codigo
                    });
                    this.disable(this.Cie10_id);
                    this.clearDataArticulo();
                } else if (
                    this.Cie10_id &&
                    this.Tipodiagnostico == "Impresión diagnóstica" &&
                    this.Marcapaciente.length == 0
                ) {
                    this.Diagnostico.push({
                        Cie10_id: this.Cie10_id,
                        Tipodiagnostico: this.Tipodiagnostico,
                        Esprimario: false,
                        Codigo: this.Codigo
                    });
                    this.disable(this.Cie10_id);
                    this.clearDataArticulo();
                }
            },

            totalIMC() {
                let p = Number(this.medicoSaludOcupacional.peso) / Math.pow(this.medicoSaludOcupacional.talla / 100, 2);
                this.medicoSaludOcupacional.imc = parseFloat(p).toFixed(1);
            },

            removeDiagnostico(diagnostico) {
                this.Diagnostico.splice(diagnostico.index, 1);
                if (diagnostico.item.id != null) {
                    axios
                        .get(`/api/cie10/deleteDiagnostic/${diagnostico.item.id}`)
                        .then(res => localStorage.removeItem("Diagnostico"));
                }
                this.cie10Array.map(cie10 => {
                    if (cie10.id == diagnostico.item.Cie10_id) {
                        cie10.customDisabled = false
                    }
                });
            },

            fetchDiagnostico() {
                axios.get(`/api/cie10/diagnostico/${this.citaPaciente}`).then(res => {
                    this.DiagnosticoSaved = res.data.cie10;
                    this.Diagnostico = this.DiagnosticoSaved;
                });
            },

            clearDataArticulo() {
                this.Cie10_id = '';
                this.Tipodiagnostico = '';
                this.Marcapaciente = '';
                this.Codigo = '';
            },
            clearAntecedenteOcupacional() {
                for (const prop of Object.getOwnPropertyNames(this.antecedenteOcupacional)) {
                    this.antecedenteOcupacional[prop] = "";
                }
            },

            disable(id) {
                this.cie10Array.map(cie10 => {
                    if (cie10.id == id) {
                        cie10.customDisabled = true
                    }
                });
            },

            fetchAtencion() {
                axios.get('/api/cita/' + this.citaPaciente + '/motivo')
                    .then(res => {
                        this.data = res.data;
                    });
            },

            guardarMotivoConsulta() {
                if (!this.data.Motivoconsulta) {
                    swal("Motivo consulta es REQUERIDO y debe ser mayor a 5 caracteres")
                    return;
                } else if (this.data.Motivoconsulta.length < 5) {
                    swal("Motivo consulta debe ser mayor a 5 caracteres")
                    return;
                }
                this.preload = true;
                axios.post('/api/cita/' + this.citaPaciente + '/motivo', this.data).then((res) => {
                    this.preload = false;
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'center-end',
                        background: '#4caf50',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: false,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: '<span style="color:#fff">Motivo Guardado con exito<span>'
                    });
                    localStorage.setItem("Atencion", JSON.stringify(this.data));
                    this.fetchAntecedentes();
                    this.fetchAntecedentePersonal();
                });

            },

            fetchAntecedentes() {
                axios.get("/api/antecedente/all").then(res => (this.antecedentes = res.data));
            },

            guardarAntecedente() {
                if (!this.antecedent.descripcion) {
                      Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Es requerido la descripción del antecedente!<span>'
                    })
                    return;
                }
                this.preload = true;
                axios.post("/api/pacienteantecedente/create", this.antecedent)
                    .then(res => {
                        this.preload = false;
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'center-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: '<span style="color:#fff">Antecedentes Agregado con exito<span>'
                        });
                        this.clearAntecedente();
                        this.fetchAntecedentePersonal();
                    })
                    .catch(err => console.log(err.response.data));
            },

            fetchAntecedentePersonal() {
                let data = {
                    citaPaciente_id: this.citaPaciente
                }
                axios.post("/api/pacienteantecedente/antecedentes1", data)
                    .then(res => {
                        this.antecedentePaci = res.data
                    });
            },

            nextParentesco() {
                this.e6 = 4;
                this.fetchParentesco();
                this.fetchAntecedenteFamiliar();
            },

            clearAntecedente() {
                this.antecedent.antecedente = ''
                this.antecedent.descripcion = ''
                this.antecedent.familiar = ''
            },

            fetchParentesco() {
                axios.get("/api/parentesco/all").then(res => (this.parentesco = res.data));
            },

            guardarAntecedentefamiliar() {
                this.preload = true;
                axios.post("/api/parentescoantecedente/create", this.antecedent)
                    .then(res => {
                        this.preload = false;
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'center-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: '<span style="color:#fff">Antecedentes Agregado con exito<span>'
                        });
                        this.clearAntecedente();
                        this.fetchAntecedenteFamiliar();
                    })
                    .catch(err => console.log(err.response.data));
            },

            fetchAntecedenteFamiliar() {
                let data = {
                    citaPaciente_id: this.citaPaciente
                }
                axios.post("/api/parentescoantecedente/familiares", data)
                    .then(res => {
                        this.antecedenteFami = res.data
                    });
            },

            getLocalStorage() {
                let Diagnostico = JSON.parse(localStorage.getItem("Diagnostico"));
                if (Diagnostico) {
                    this.Diagnostico = Diagnostico;
                    this.Diagnostico.forEach(diag => {
                        this.disable(diag.Cie10_id);
                    });
                } else {
                    this.fetchDiagnostico();
                }

                let motivo = JSON.parse(localStorage.getItem("Atencion"));
                if (motivo) {
                    this.data = motivo;
                } else {
                    this.fetchAtencion();
                }

                let conducta = JSON.parse(localStorage.getItem("Conducta"));
                if (conducta) {
                    this.conducta = conducta;
                } else {
                    this.fetchConducta();
                }

                let antecedenteOcupacional = JSON.parse(localStorage.getItem("antecedenteOcupacional"));
                if (antecedenteOcupacional) {
                    this.antecedenteOcupacional = antecedenteOcupacional;
                } else {
                    this.fetchAntecedentesOpucacionales();
                }
            },

            removeLocalStorage() {
                localStorage.removeItem("Atencion");
                localStorage.removeItem("Diagnostico");
                localStorage.removeItem("Conducta");
                localStorage.removeItem("antecedenteOcupacional");
            },

            submitDiagnostico() {
                if (this.Diagnostico.length == 0 || !this.selectedItems) {
                    swal({
                        title: "Debe asignar un diagnostico principal",
                        icon: "warning"
                    });
                    return;
                }
                var diag = [];
                this.Diagnostico.forEach(diagnostico => {
                    if (diagnostico.Cie10_id == this.selectedItems.Cie10_id) {
                        diagnostico.Esprimario = true;
                    }
                    if (diagnostico.id == null) {
                        diag.push(diagnostico);
                    }
                });
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El diagnóstico será almacenado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios
                            .post(`/api/cie10/create/${this.citaPaciente}`, diag)
                            .then(res => {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'center-end',
                                    background: '#4caf50',
                                    showConfirmButton: false,
                                    timer: 1000,
                                    timerProgressBar: false,
                                    onOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })
                                Toast.fire({
                                    icon: 'success',
                                    title: '<span style="color:#fff">Diagnostico guardado con exito<span>'
                                });
                                localStorage.setItem(
                                    "Diagnostico",
                                    JSON.stringify(this.Diagnostico)
                                );
                                this.nextConcepto()
                            })
                            .catch(err => console.log(err.response.data));
                    }
                });
            },

            guardarConducta() {
                if (this.conducta.Planmanejo == undefined) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Plan de manejo debe ser minimo de 5 caracteres !<span>'
                    })
                    return;
                } else if (this.conducta.Recomendaciones == undefined) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Recomendaciones de consulta debe ser minimo de 5 caracteres!<span>'
                    })
                    return;
                } else if (!this.conducta.Destinopaciente) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Destino del paciente es requerido!<span>'
                    })
                    return;
                } else if (!this.conducta.Finalidad) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">La FINALIDAD es requerida!<span>'
                    })
                    return;
                }
                this.preload = true;
                axios
                    .post("/api/conducta/" + this.citaPaciente + "/final", this.conducta)
                    .then(res => {
                        this.preload = false;
                        this.e6 = 17;
                        localStorage.setItem("Conducta", JSON.stringify(this.conducta));
                        swal({
                            title: res.data.message,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        // EventBus.$emit("call-order");
                    });
            },

            fetchConducta() {
                axios.post('/api/conducta/' + this.citaPaciente + '/getConductaByCita')
                    .then(res => {
                        if (res.data) {
                            this.conducta = res.data
                        }
                    })
                    .catch(err => console.log(err.response));
            },
            listEntidades() {
                axios.get('/api/entidades/getEntidades').then(res => {
                    this.entidades = res.data;
                    this.dialog = true;
                })
            },
            saveAntecedentesOpucacionales() {
                axios.post('/api/cita/saveAntecedentesOpucacionales/' + this.citaPaciente, this.antecedenteOcupacional)
                    .then((res) => {
                        localStorage.setItem("antecedenteOcupacional", JSON.stringify(this.antecedenteOcupacional));
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'center-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: '<span style="color:#fff">Guardado con exito!<span>'
                        });
                        this.clearAntecedenteOcupacional()
                        this.fetchAntecedentesOpucacionales()
                    })
            },

            fetchAntecedentesOpucacionales() {
                axios.post('/api/cita/getAntecedentesOpucacionales/' + this.citaPaciente)
                    .then(res => {
                        if (res.data) {
                            this.antOcupacional = res.data
                        }
                    })
                    .catch(err => console.log(err.response));
            },
            saveOcupacional() {
                let clasificacionPresion = "Normal";

                if (
                    this.medicoSaludOcupacional.presionsistolica > 180 ||
                    this.medicoSaludOcupacional.presiondiastolica > 120
                ) {
                    clasificacionPresion = "CRISIS DE HIPERTENSION";
                } else if (
                    this.medicoSaludOcupacional.presionsistolica >= 140 ||
                    this.medicoSaludOcupacional.presiondiastolica >= 90
                ) {
                    clasificacionPresion = "PRESION ARTERIAL ALTA (NIVEL 2)";
                } else if (
                    this.medicoSaludOcupacional.presionsistolica >= 130 ||
                    this.medicoSaludOcupacional.presiondiastolica >= 80
                ) {
                    clasificacionPresion = "PRESION ARTERIAL ALTA (NIVEL 1)";
                } else if (
                    this.medicoSaludOcupacional.presionsistolica >= 120 &&
                    this.medicoSaludOcupacional.presiondiastolica < 80
                ) {
                    clasificacionPresion = "ELEVADA";
                } else if (
                    this.medicoSaludOcupacional.presionsistolica < 120 &&
                    this.medicoSaludOcupacional.presiondiastolica < 80
                ) {
                    clasificacionPresion = "NORMAL";
                }
                axios.post('/api/cita/saveSaludocupacional/' + this.citaPaciente, this.medicoSaludOcupacional).then((res) => {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'center-end',
                        background: '#4caf50',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: false,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: '<span style="color:#fff">Guardado con exito!<span>'
                    });
                })
            },

            async fetchOcupacionale() {
               const general = await axios.post('/api/cita/getSaludocupacional/' + this.citaPaciente);
                this.medicoSaludOcupacional = general.data;
                this.medicoSaludOcupacional.cabeza_y_orl = (general.data.cabeza_y_orl?general.data.cabeza_y_orl:"No refiere")
                this.medicoSaludOcupacional.sistema_neurologico = (general.data.sistema_neurologico?general.data.sistema_neurologico:"No refiere")
                this.medicoSaludOcupacional.sistema_cardiopulmonar = (general.data.sistema_cardiopulmonar?general.data.sistema_cardiopulmonar:"No refiere")
                this.medicoSaludOcupacional.sistema_digestivo = (general.data.sistema_digestivo?general.data.sistema_digestivo:"No refiere")
                this.medicoSaludOcupacional.sistema_musculo_esqueletico = (general.data.sistema_musculo_esqueletico?general.data.sistema_musculo_esqueletico:"No refiere")
                this.medicoSaludOcupacional.sistema_genitourinario = (general.data.sistema_genitourinario?general.data.sistema_genitourinario:"No refiere")
                this.medicoSaludOcupacional.piel_y_faneras = (general.data.piel_y_faneras?general.data.piel_y_faneras:"No refiere")
                this.medicoSaludOcupacional.otros = (general.data.otros?general.data.otros:"No refiere")
                // condiciones generales
                this.medicoSaludOcupacional.cabeza = (general.data.cabeza?general.data.cabeza:"Normal")
                this.medicoSaludOcupacional.ojos = (general.data.ojos?general.data.ojos:"Normal")
                this.medicoSaludOcupacional.fondo_de_ojos = (general.data.fondo_de_ojos?general.data.fondo_de_ojos:"Normal")
                this.medicoSaludOcupacional.oidos = (general.data.oidos?general.data.oidos:"Normal")
                this.medicoSaludOcupacional.otoscopia = (general.data.otoscopia?general.data.otoscopia:"Normal")
                this.medicoSaludOcupacional.nariz = (general.data.nariz?general.data.nariz:"Normal")
                this.medicoSaludOcupacional.boca = (general.data.boca?general.data.boca:"Normal")
                this.medicoSaludOcupacional.dentadura = (general.data.dentadura?general.data.dentadura:"Normal")
                this.medicoSaludOcupacional.faringe = (general.data.faringe?general.data.faringe:"Normal")
                this.medicoSaludOcupacional.cuello = (general.data.cuello?general.data.cuello:"Normal")
                this.medicoSaludOcupacional.mamas = (general.data.mamas?general.data.mamas:"Normal")
                this.medicoSaludOcupacional.torax = (general.data.torax?general.data.torax:"Normal")
                this.medicoSaludOcupacional.corazon = (general.data.corazon?general.data.corazon:"Normal")
                this.medicoSaludOcupacional.pulmones = (general.data.pulmones?general.data.pulmones:"Normal")
                this.medicoSaludOcupacional.columna = (general.data.columna?general.data.columna:"Normal")
                this.medicoSaludOcupacional.abdomen = (general.data.abdomen?general.data.abdomen:"Normal")
                this.medicoSaludOcupacional.genitales_externos = (general.data.genitales_externos?general.data.genitales_externos:"Normal")
                this.medicoSaludOcupacional.miembros_sup = (general.data.miembros_sup?general.data.miembros_sup:"Normal")
                this.medicoSaludOcupacional.miembros_inf = (general.data.miembros_inf?general.data.miembros_inf:"Normal")
                this.medicoSaludOcupacional.reflejos = (general.data.reflejos?general.data.reflejos:"Normal")
                this.medicoSaludOcupacional.motilidad = (general.data.motilidad?general.data.motilidad:"Normal")
                this.medicoSaludOcupacional.fuerza_muscular = (general.data.fuerza_muscular?general.data.fuerza_muscular:"Normal")
                this.medicoSaludOcupacional.marcha = (general.data.marcha?general.data.marcha:"Normal")
                this.medicoSaludOcupacional.piel_faneras = (general.data.piel_faneras?general.data.piel_faneras:"Normal")
            },

            nextAtencion() {
                this.pacienteUpdate();
                this.e6 = 2
            },
            nextAntecedentesPersonales() {
                this.guardarMotivoConsulta();
                this.e6 = 3
            },
            nextAntecedentesOcupacionales() {
                this.e6 = 5
            },
            nextSincondicionar() {
                this.e6 = 6
            },
            addAntecedente() {
                this.saveAntecedentesOpucacionales()
            },
            nextGineco() {
                if (this.formPaciente.Sexo == 'F') {
                    this.e6 = 7
                    this.saveOcupacional()
                } else {
                    this.e6 = 8
                    this.saveOcupacional()
                }

            },
            nextHabitos() {
                this.e6 = 8
                this.saveOcupacional()
            },
            nextRevisionSistemas() {
                this.e6 = 9
                this.saveOcupacional()
            },
            nextCndiciones(){
                this.e6 = 11
                this.saveOcupacional()
            },
            nextCsalus() {
                this.e6 = 10
                this.saveOcupacional()
            },
            nextExamenfisico() {
                this.e6 = 12
                this.saveOcupacional()
            },
            nextDiagnosticos() {
                this.e6 = 13
                this.saveOcupacional()
            },
            nextConcepto() {
                this.e6 = 14
            },
            nextEpidemia() {
                this.e6 = 15
                this.saveOcupacional()
            },
            nextConducta() {
                this.e6 = 16
                this.saveOcupacional()
            },
            guardarFirma() {
                this.e6 = 17
                this.saveFirma()
            }

        }
    }

</script>
<style lang="scss" scoped>
    #signature {
        border: double 3px transparent;
        border-radius: 5px;
        background-image: linear-gradient(white, white),
            radial-gradient(circle at top left, #4bc5e8, #9f6274);
        background-origin: border-box;
        background-clip: content-box, border-box;
    }

</style>
