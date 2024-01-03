<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-stepper v-model="e6" vertical>
                <v-stepper-step :complete="e6 > 1" editable :edit-icon="$vuetify.icons.complete" step="1">
                    MC Y EA
                    <small>Motivo de consulta y enfermedad actual</small>
                </v-stepper-step>
                <v-stepper-content step="1">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-md fluid class="pa-0">
                            <v-textarea name="input-7-1" label="Motivo de Consulta" value=""
                                v-model="data.Motivoconsulta"></v-textarea>
                            <v-textarea name="input-7-1" label="Enfermedad Actual" value=""
                                v-model="data.Enfermedadactual"></v-textarea>
                            <v-textarea name="input-7-1" label="Resultados de Ayudas Diagnosticas" value=""
                                v-model="data.Resultayudadiagnostica"></v-textarea>
                        </v-container>
                    </v-card>
                    <v-btn color="primary" round @click="guardarMotivoConsulta()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 2" editable :edit-icon="$vuetify.icons.complete" step="2">Revisión por
                    sistema</v-stepper-step>
                <v-stepper-content step="2">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-form>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout row wrap>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="data.checkboxOftalmologico"
                                            label="Oftalmologico:"></v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxOftalmologico == true"
                                            v-model="data.Oftalmologico"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="data.checkboxGenitourinario"
                                            label="Genitourinario:"></v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxGenitourinario == true"
                                            v-model="data.Genitourinario"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary"
                                            v-model="data.checkboxOtorrinoralingologico" label="Otorrinoralingologico:">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxOtorrinoralingologico == true"
                                            v-model="data.Otorrinoralingologico"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="data.checkboxLinfatico"
                                            label="Linfatico:">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxLinfatico == true"
                                            v-model="data.Linfatico"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="data.checkboxOsteomioarticular"
                                            label="Osteomioarticular:">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxOsteomioarticular == true"
                                            v-model="data.Osteomioarticular"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="data.checkboxNeurologico"
                                            label="Neurologico:"></v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxNeurologico == true"
                                            v-model="data.Neurologico"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="data.checkboxCardiovascular"
                                            label="Cardiovascular:"></v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxCardiovascular == true"
                                            v-model="data.Cardiovascular"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="data.checkboxTegumentario"
                                            label="Tegumentario:"></v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxTegumentario == true"
                                            v-model="data.Tegumentario"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="data.checkboxRespiratorio"
                                            label="Respiratorio:"></v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxRespiratorio == true"
                                            v-model="data.Respiratorio"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="data.checkboxEndocrinologico"
                                            label="Endocrinologico:">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxEndocrinologico == true"
                                            v-model="data.Endocrinologico"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="data.checkboxGastrointestinal"
                                            label="Gastrointestinal:">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxGastrointestinal == true"
                                            v-model="data.Gastrointestinal"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="data.checkboxNorefiere"
                                            label="Norefiere:">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" v-if="data.checkboxNorefiere == true"
                                            v-model="data.Norefiere"></v-textarea>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                        <v-btn color="primary" round @click="guardarRevisionsisitema()">Guardar</v-btn>
                    </v-card>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 3" editable :edit-icon="$vuetify.icons.complete" step="3">Antecedentes
                    Personales</v-stepper-step>
                <v-stepper-content step="3">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm4 d-flex>
                                            <v-autocomplete v-model="antecedentesP.Cie10_id" append-icon="search"
                                                :items="cieConcat" item-disabled="customDisabled" item-text="nombre"
                                                item-value="id" label="Diagnóstico">
                                            </v-autocomplete>
                                        </v-flex>

                                        <v-flex xs12 sm6 d-flex>
                                            <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                                v-model="antecedentesP.descripcion">
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
                                    <v-data-table :items="antecedentePaci" :headers="hola" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.created_at }}</td>
                                            <td class="text-xs">{{ props.item.name }}</td>
                                            <td class="text-xs">{{ props.item.cie }}</td>
                                            <td class="text-xs">{{ props.item.Descripcion }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                    <v-btn color="primary" round @click="antecedentePersonales()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 4" editable :edit-icon="$vuetify.icons.complete" step="4">Antecedentes
                    Farmacologicos</v-stepper-step>
                <v-stepper-content step="4">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-form>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout>
                                    <v-flex xs12 md6>
                                        <VAutocomplete :items="listArticuloWithout" item-text="Nombre"
                                            :label="'Medicamento'" :loading="loading" return-object required
                                            :search-input.sync="article" v-model="antecedentesFarma.data" />
                                    </v-flex>
                                    <v-flex xs12 md3 v-if="antecedentesFarma.data">
                                        <v-text-field v-model="antecedentesFarma.data.Via_Administracion" label="Via"
                                            readonly>
                                        </v-text-field>
                                    </v-flex>

                                    <v-flex xs12 md3 v-if="antecedentesFarma.data">
                                        <v-text-field v-model="antecedentesFarma.data.Forma_Farmaceutica" readonly
                                            label="Presentación"></v-text-field>
                                    </v-flex>

                                    <v-flex xs12 md2 v-if="antecedentesFarma.data">
                                        <v-text-field v-model="antecedentesFarma.utiempo" label="U-Tiempo">
                                        </v-text-field>
                                    </v-flex>

                                    <v-flex xs12 md2>
                                        <v-text-field v-model="antecedentesFarma.frecuencia" label="Frecuencia">
                                        </v-text-field>
                                    </v-flex>

                                    <v-flex xs12 md2>
                                        <v-select :items="sino" v-model="antecedentesFarma.alergia" label="Alergia">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs12 sm1 d-flex>
                                        <v-btn fab dark color="success" @click="guardarAntecedenteFarmacologico()">
                                            <v-icon dark>add</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                                <v-flex xs12 v-if="antecedentesFarma.alergia == 'SI'">
                                    <v-textarea outline name="input-7-4" label="Observación Alergia"
                                        v-model="antecedentesFarma.observacionAlergia">
                                    </v-textarea>
                                </v-flex>
                            </v-container>
                        </v-form>
                        <v-card>
                            <v-card-title primary-title>
                                <v-flex xs12 sm12 d-flex>
                                    <v-data-table :items="antecedenteFarmacologia" :headers="hola" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.created_at }}</td>
                                            <td class="text-xs">{{ props.item.Producto }}</td>
                                            <td class="text-xs">{{ props.item.utiempo }}</td>
                                            <td class="text-xs">{{ props.item.frecuencia }}</td>
                                            <td class="text-xs">{{ props.item.alergia }}</td>
                                            <td class="text-xs">{{ props.item.observacionAlergia }}</td>
                                            <td class="text-xs">
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn text icon color="red" dark v-on="on">
                                                            <v-icon @click="inabilitarFarmaco(props.item.id)">
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
                        </v-card>
                    </v-card>
                    <v-btn color="primary" round @click="antecedenteMedicamentos()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 5" editable :edit-icon="$vuetify.icons.complete" step="5">Antecedentes
                    Toxicologicos</v-stepper-step>
                <v-stepper-content step="5">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-form>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout row wrap>
                                    <v-flex xs12 sm6 md4>
                                        <v-checkbox value="1" color="primary" v-model="toxico.checkboxMedicamentos"
                                            label="Medicamentos:"></v-checkbox>
                                        <v-textarea name="input-7-1" v-if="toxico.checkboxMedicamentos == true"
                                            v-model="toxico.medicamento"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md4>
                                        <v-checkbox value="1" color="primary" v-model="toxico.checkboxAlimentarios"
                                            label="Alimentarios:"></v-checkbox>
                                        <v-textarea name="input-7-1" v-if="toxico.checkboxAlimentarios == true"
                                            v-model="toxico.Alimentarios"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md4>
                                        <v-checkbox value="1" color="primary"
                                            v-model="toxico.checkboxSPA" label="SPA:">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" v-if="toxico.checkboxSPA == true"
                                            v-model="toxico.SPA"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md4>
                                        <v-checkbox value="1" color="primary" v-model="toxico.checkboxAlcohol"
                                            label="Alcohol:">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" v-if="toxico.checkboxAlcohol == true"
                                            v-model="toxico.Alcohol"></v-textarea>
                                    </v-flex>

                                    <v-flex xs12 sm6 md4>
                                        <v-checkbox value="1" color="primary" v-model="toxico.checkboxOtrasSustacias"
                                            label="OtrasSustacias:">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" v-if="toxico.checkboxOtrasSustacias == true"
                                            v-model="toxico.OtrasSustacias"></v-textarea>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                    </v-card>
                    <v-btn color="primary" round @click="antecedenteToxicologicos()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 6" editable :edit-icon="$vuetify.icons.complete" step="6">Antecedentes
                    Familiares</v-stepper-step>
                <v-stepper-content step="6">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-autocomplete v-model="antecedentesP.Cie10_idFami" append-icon="search"
                                                :items="cieConcat" item-disabled="customDisabled" item-text="nombre"
                                                item-value="id" label="Diagnóstico">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-autocomplete label="Selecciona Familiar" :items="parentesco"
                                                item-text="Nombre" item-value="id" v-model="antecedentesP.familiar">
                                            </v-autocomplete>
                                        </v-flex>

                                        <v-flex xs12 sm5 d-flex>
                                            <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                                v-model="antecedentesP.descripcion">
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
                                            <td class="text-xs">{{ props.item.cie }}</td>
                                            <td class="text-xs">{{ props.item.familiar }}</td>
                                            <td class="text-xs">{{ props.item.Descripcion }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                        <v-btn color="primary" round @click="guardarParentesco()">Guardar y seguir</v-btn>
                    </v-card>
                </v-stepper-content>

                <v-stepper-step v-if="data.Sexo == 'F'" :complete="e6 > 7" editable :edit-icon="$vuetify.icons.complete"
                    step="7">ANTECEDENTES
                    GINECO OBSTÉTRICOS</v-stepper-step>
                <v-stepper-content step="7" v-if="data.Sexo == 'F'">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-form>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout row wrap>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success"
                                            v-model="gineco.checkboxFechaultimamenstruacion" label="FUM">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="FUM"
                                            v-if="gineco.checkboxFechaultimamenstruacion"
                                            v-model="gineco.Fechaultimamenstruacion"></v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxGestaciones"
                                            label="Gestante">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="Gestante" v-if="gineco.checkboxGestaciones"
                                            v-model="gineco.Gestaciones"></v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxPartos"
                                            label="Partos">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="Partos" v-if="gineco.checkboxPartos"
                                            v-model="gineco.Partos">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxAbortoprovocado"
                                            label="Aborto provocado">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="Aborto provocado"
                                            v-if="gineco.checkboxAbortoprovocado" v-model="gineco.Abortoprovocado">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxAbortoespontaneo"
                                            label="Aborto espontáneo">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="Aborto espontáneo"
                                            v-if="gineco.checkboxAbortoespontaneo" v-model="gineco.Abortoespontaneo">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxMortinato"
                                            label="Mortinato">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="Mortinato" v-if="gineco.checkboxMortinato"
                                            v-model="gineco.Mortinato"></v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxGestante"
                                            label="Gestante">
                                        </v-checkbox>
                                        <v-text-field label="Fecha Problable Parto" type="date"
                                            v-if="gineco.checkboxGestante" v-model="gineco.Gestantefechaparto">
                                        </v-text-field>
                                        <v-text-field label="Numero Control Prenatal" v-if="gineco.checkboxGestante"
                                            v-model="gineco.Gestantenumeroctrlprenatal"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <span></span>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxEutoxico"
                                            label="Eutoxico">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="Eutoxico" v-if="gineco.checkboxEutoxico"
                                            v-model="gineco.Eutoxico">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxCesaria"
                                            label="Cesareas">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="Cesareas" v-if="gineco.checkboxCesaria"
                                            v-model="gineco.Cesaria">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success"
                                            v-model="gineco.checkboxCancercuellouterino" label="Cáncer Cuello Uterino">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="Cáncer Cuello Uterino"
                                            v-if="gineco.checkboxCancercuellouterino"
                                            v-model="gineco.Cancercuellouterino"></v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxCitologia"
                                            label="Última Citologia">
                                        </v-checkbox>
                                        <v-text-field label="Última Citologia" v-if="gineco.checkboxCitologia"
                                            v-model="gineco.Ultimacitologia"></v-text-field>
                                        <v-textarea name="input-7-1" label="Resultado" v-if="gineco.checkboxCitologia"
                                            v-model="gineco.Resultado"></v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxMenarquia"
                                            label="Menarquia">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="Menarquia" v-if="gineco.checkboxMenarquia"
                                            v-model="gineco.Menarquia"></v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxCiclos"
                                            label="Ciclos">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="Ciclos" v-if="gineco.checkboxCiclos"
                                            v-model="gineco.Ciclos">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="success" v-model="gineco.checkboxRegulares"
                                            label="Regulares">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" label="Regulares" v-if="gineco.checkboxRegulares"
                                            v-model="gineco.Regulares"></v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm12 md12>
                                        <v-textarea name="input-7-1" label="Observacion" value
                                            v-model="gineco.Observaciongineco"></v-textarea>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                    </v-card>
                    <v-btn color="primary" round @click="guardarAntecedentesgineco()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 8" editable :edit-icon="$vuetify.icons.complete" step="8">ESTILOS DE
                    VIDA</v-stepper-step>
                <v-stepper-content step="8">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-form>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout row wrap>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="estiloP.checkboxDietasaludable"
                                            label="Dieta:"></v-checkbox>
                                        <v-textarea name="input-7-1" v-if="estiloP.checkboxDietasaludable == true"
                                            v-model="estiloP.Dietasaludable">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="estiloP.checkboxSuenoreparador"
                                            label="Alteraciones del Sueño">
                                        </v-checkbox>
                                        <v-textarea name="input-7-1" v-if="estiloP.checkboxSuenoreparador == true"
                                            v-model="estiloP.Suenoreparador">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary"
                                            v-model="estiloP.checkboxDuermemenoseishoras"
                                            label="Duerme Menos de 6 Horas:"></v-checkbox>
                                        <v-textarea name="input-7-1" value="1"
                                            v-if="estiloP.checkboxDuermemenoseishoras == true"
                                            v-model="estiloP.Duermemenoseishoras">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="estiloP.checkboxAltonivelestres"
                                            label="Maneja Altos Niveles de Estres:"></v-checkbox>
                                        <v-textarea name="input-7-1" v-if="estiloP.checkboxAltonivelestres == true"
                                            v-model="estiloP.Altonivelestres"></v-textarea>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="estiloP.checkboxActividadfisica"
                                            label="Actividad Física:">
                                        </v-checkbox>
                                        <v-text-field label="Actividad Física" v-if="estiloP.checkboxActividadfisica"
                                            v-model="estiloP.Actividadfisica"></v-text-field>
                                        <v-text-field label="Duración" v-if="estiloP.checkboxActividadfisica"
                                            v-model="estiloP.Duracion"></v-text-field>
                                        <v-text-field label="Frecuencia en la Semana"
                                            v-if="estiloP.checkboxActividadfisica" v-model="estiloP.Frecuenciasemana">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="estiloP.checkboxFuma"
                                            label="Exposición Humo de Tabaco:">
                                        </v-checkbox>
                                        <v-text-field v-if="estiloP.checkboxFuma" v-model="estiloP.Fumainicio"
                                            label="Inicio de Fumar:"></v-text-field>
                                        <v-text-field v-if="estiloP.checkboxFuma" v-model="estiloP.Fumadorpasivo"
                                            label="Fumador Pasivo:">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary" v-model="estiloP.checkboxTomalicor"
                                            label="Toma Licor:"></v-checkbox>
                                        <v-select v-if="estiloP.checkboxTomalicor"
                                            :items="['Ocasional','Mensual', 'Semanal', 'Diario']" label="Frecuencia"
                                            v-model="estiloP.Licorfrecuencia" chips />
                                    </v-flex>

                                    <v-flex xs12 sm6 md3>
                                        <v-checkbox value="1" color="primary"
                                            v-model="estiloP.checkboxConsumopsicoactivo" label="Consumo SPA:">
                                        </v-checkbox>
                                        <v-text-field v-if="estiloP.checkboxConsumopsicoactivo" label="Fecha de Inicio"
                                            type="date" v-model="estiloP.Consumopsicoactivo"></v-text-field>
                                        <v-text-field v-if="estiloP.checkboxConsumopsicoactivo" label="Cantidad"
                                            v-model="estiloP.Psicoactivocantidad"></v-text-field>
                                    </v-flex>

                                    <v-flex xs12 sm12 md12>
                                        <v-textarea name="input-7-1" label="Observación"
                                            v-model="estiloP.Estilovidaobservaciones"></v-textarea>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                        <v-btn color="primary" round @click="guardarEstilovida()">Guardar y seguir</v-btn>
                    </v-card>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 9" editable :edit-icon="$vuetify.icons.complete" step="9">MEDIDAS
                    ANTROPOMÉTRICAS</v-stepper-step>
                <v-stepper-content step="9">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Peso (Kg)" v-model="examenF.Peso" type="number"></v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Talla (Centimetros)" v-model="examenF.Talla" type="number">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="IMC" v-model="examenF.Imc" type="number" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="ASC" v-model="examenF.ISC" type="number" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Clasificación" v-model="examenF.Clasificacion" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6 px-1>
                                    <v-text-field label="Perímetro Abdominal" type="number"
                                        v-model="examenF.Perimetroabdominal"></v-text-field>
                                </v-flex>
                                <v-flex xs6 px-1>
                                    <v-text-field v-if="Perimetrocefalico" label="Perímetro Cefálico" type="number"
                                        v-model="examenF.Perimetrocefalico"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                    <v-btn round color="primary" @click="guardarAntropometricas()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 10" editable :edit-icon="$vuetify.icons.complete" step="10">SIGNOS
                    VITALES
                </v-stepper-step>
                <v-stepper-content step="10">
                    <v-card color="lighten-1" class="mb-5">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs4 px-1>
                                    <v-text-field label="Frecuencia cardiaca" type="number"
                                        v-model="examenF.Frecucardiaca"></v-text-field>
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <v-text-field label="Pulsos" type="number" v-model="examenF.Pulsos"></v-text-field>
                                </v-flex>
                                <v-flex xs4 px-1>
                                    <v-text-field label="Frecuencia Respiratoria" type="number"
                                        v-model="examenF.Frecurespiratoria"></v-text-field>
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <v-text-field label="Temperatura" type="number" v-model="examenF.Temperatura">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 px-1>
                                    <v-text-field label="Sat. O2" type="number" v-model="examenF.Saturacionoxigeno">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row wrap>
                                <v-flex xs2 px-1>
                                    <v-select :items="['Sentado','Acostado','De pie']" label="Posición"
                                        v-model="examenF.Posicion" chips />
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <v-select :items="['Derecha','Izquierda']" label="Lateralidad"
                                        v-model="examenF.Lateralidad" chips />
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Presión / Sistólica" value type="number"
                                        v-model="examenF.Presionsistolica"></v-text-field>
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <v-text-field label="Presión / Diastólica" value type="number"
                                        v-model="examenF.Presiondiastolica"></v-text-field>
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <v-text-field label="Presión Arterial Media" value type="number"
                                        v-model="examenF.Presionarterialmedia"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                    <v-btn color="primary" round @click="guardarSignosVitales()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 11" editable :edit-icon="$vuetify.icons.complete" step="11">EXAMEN
                    FÍSICO
                </v-stepper-step>
                <v-stepper-content step="11">
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-textarea name="input-7-1" label="Condiciones Generales" value
                                v-model="examenF.Condiciongeneral"></v-textarea>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.a" label="Cabeza Cuello"></v-switch>
                                <v-text-field label="Cabeza Cuello" v-show="examenF.a" v-model="examenF.Cabezacuello">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.b" label="Ojos y Fondo de Ojos"></v-switch>
                                <v-text-field label="Ojos y Fondo de Ojos" v-show="examenF.b"
                                    v-model="examenF.Ojosfondojos">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.c" label="Agudeza Visual"></v-switch>
                                <v-text-field label="Agudeza Visual" v-show="examenF.c" v-model="examenF.Agudezavisual">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.d" label="Cardiopulmonar"></v-switch>
                                <v-text-field label="Cardiopulmonar" v-show="examenF.d"
                                    v-model="examenF.Cardiopulmonar">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.e" label="Abdomen"></v-switch>
                                <v-text-field label="Abdomen" v-show="examenF.e" v-model="examenF.Abdomen">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.f" label="Osteomuscular"></v-switch>
                                <v-text-field label="Osteomuscular" v-show="examenF.f" v-model="examenF.Osteomuscular">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.g" label="Extreminades"></v-switch>
                                <v-text-field label="Extreminades" v-show="examenF.g" v-model="examenF.Extremidades">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.h" label="Pulsos Periféricos"></v-switch>
                                <v-text-field label="Pulsos Periféricos" v-show="examenF.h"
                                    v-model="examenF.Pulsosperifericos"></v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.i" label="Neurológicos"></v-switch>
                                <v-text-field label="Neurológicos" v-show="examenF.i" v-model="examenF.Neurologico">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.j" label="Reflejos Osteotendinosos">
                                </v-switch>
                                <v-text-field label="Reflejos Osteotendinosos" v-show="examenF.j"
                                    v-model="examenF.Reflejososteotendinos"></v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.k" label="Piel y Faneras"></v-switch>
                                <v-text-field label="Piel y Faneras" v-show="examenF.k" v-model="examenF.Pielfraneras">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.l" label="Genitourinario"></v-switch>
                                <v-text-field label="Genitourinario" v-show="examenF.l"
                                    v-model="examenF.Genitourinario">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.o" label="Examen de Mama"></v-switch>
                                <v-text-field label="Examen de Mama" v-show="examenF.o" v-model="examenF.Examenmama">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.p" label="Tacto Rectal"></v-switch>
                                <v-text-field label="Tacto Rectal" v-show="examenF.p" v-model="examenF.Tactoretal">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-switch color="primary" v-model="examenF.q" label="Examen Mental"></v-switch>
                                <v-text-field label="Examen Mental" v-show="examenF.q" v-model="examenF.Examenmental">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                    <v-btn color="primary" round @click="guardarExamenFisico()">Guardar</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 12" editable :edit-icon="$vuetify.icons.complete" step="12">DIAGNÓSTICOS
                </v-stepper-step>
                <v-stepper-content step="12">
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm6>
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
						'Polimedicados','Pacientes Tutela','Paciente Alto Costo','Domiciliario','Primera Infancia','Infancia ',
						'Adolescencia ','Juventud ','Riesgo Cardiovascular','Grupal Curso Psicoprofilactico',
						'Apoyo Lactancia Materna ','Detección Temprana Cáncer Cuello Uterino ','Riesgo Cardiovascular',
						'Planificación Familiar','Atención Preconcepcional','Promocion De Alimentacion Y Nutricion','Prenatal',
						'Atención Del Posparto','Atención Del Recien Nacido','Detección Temprana Cancer De Mama', 'Adultez', 'Vejez'
						]" label="Marcar paciente" v-model="Marcapaciente" attach multiple chips></v-autocomplete>
                            </v-flex>
                        </v-layout>
                    </v-container>

                    <v-btn color="primary" round dark v-on:click="addDiagnostico()">Ingresar Diagnóstico</v-btn>

                    <v-data-table :headers="headers" :items="Diagnostico" :search="search">
                        <template v-slot:items="props">
                            <tr>
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
                        <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your search for
                            "{{ search }}"
                            found no results.</v-alert>
                    </v-data-table>

                    <v-btn color="success" round dark v-on:click="submit()">Aceptar</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 13" editable :edit-icon="$vuetify.icons.complete" step="13">FINAL
                </v-stepper-step>

                <v-stepper-content step="13">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-textarea name="input-7-1" label="Plan de manejo" value="" v-model="finalH.Planmanejo">
                            </v-textarea>
                            <v-textarea name="input-7-1" label="Recomendaciones" auto-grow
                                v-model="finalH.Recomendaciones" value></v-textarea>
                            <v-layout row wrap>
                                <v-flex xs12 sm4>
                                    <v-autocomplete :items="[
									'RIA Primera Infancia','RIA Infancia','RIA Adolescencia','RIA Juventud', 'RIA Adulto',
									'RIA Adulto Mayor','RIA Materno Perinatal', 'Control', 'Interconsulta', 'Domiciliaria',
									'Urgencias', 'Hospitalización (Remision)'
								]" label="Destino del paciente" append-icon="search" v-model="finalH.Destinopaciente"></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm4 v-show="finalH.Destinopaciente == 'Hospitalización (Remision)'">
                                    <vAutocomplete :items="especialidades" label="Especialidad:"
                                        no-finalH-text="No se encuentra especialidad con ese nombre"
                                        v-model="finalH.Especialidad_remi" />
                                </v-flex>
                                <v-flex xs12 sm4>
                                    <v-autocomplete :items="[
									'Atención del parto, del embarazo y posparto', 'Atención del recién nacido',
									'Atención planificación familiar', 'Atención crecimiento y desarrollo',
									'Atención joven Sano', 'Detención alteraciones del embarazo',
									'Detención alteraciones del adulto', 'Detención alteraciones agudeza Visual',
									'Enfermedad Profesional', 'Teleconsulta', 'No aplica'
								]" label="Finalidad" append-icon="search" v-model="finalH.Finalidad"></v-autocomplete>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                    <v-btn color="primary" @click="guardarConducta()">Guardar y terminar</v-btn>
                    <v-btn color="dark" round @click="printRecomendation()">Descargar recomendaciones</v-btn>
                </v-stepper-content>

            </v-stepper>
            <template>
                <div class="text-center">
                    <v-dialog v-model="preload_motivo" persistent width="300">
                        <v-card color="success" dark>
                            <v-card-text>
                                Guardado con Exito !
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
            // this.removeLocalStorage();
            this.citaPaciente = this.$route.query.cita_paciente;
            this.getLocalStorage();
        },
        data() {
            return {
                preload_motivo: false,
                e6: 1,
                data: {
                    Motivoconsulta: '',
                    Enfermedadactual: '',
                    Resultayudadiagnostica: '',
                    Oftalmologico: '',
                    Genitourinario: '',
                    Otorrinoralingologico: '',
                    Linfatico: '',
                    Osteomioarticular: '',
                    Neurologico: '',
                    Cardiovascular: '',
                    Tegumentario: '',
                    Respiratorio: '',
                    Endocrinologico: '',
                    Gastrointestinal: '',
                    Norefiere: '',
                    checkboxOftalmologico: '',
                    checkboxGenitourinario: '',
                    checkboxOtorrinoralingologico: '',
                    checkboxLinfatico: '',
                    checkboxOsteomioarticular: '',
                    checkboxNeurologico: '',
                    checkboxCardiovascular: '',
                    checkboxTegumentario: '',
                    checkboxRespiratorio: '',
                    checkboxEndocrinologico: '',
                    checkboxGastrointestinal: '',
                    checkboxNorefiere: '',
                    paciente_id: ''
                },
                antecedentesFarma: {
                    data: '',
                    utiempo: '',
                    frecuencia: '',
                    alergia: '',
                },
                loading: false,
                article: '',
                listArticuloWithout: [],
                antecedentesP: {
                    antecedente: "",
                    descripcion: "",
                    cita_paciente: "",
                    familiar: "",
                    Cie10_id: "",
                    Cie10_idFami: ""
                },
                toxico: {
                    checkboxMedicamentos: '',
                    medicamento: '',
                    checkboxAlimentarios: '',
                    Alimentarios: '',
                    checkboxSPA: '',
                    SPA: '',
                    checkboxAlcohol: '',
                    Alcohol: '',
                    checkboxOtrasSustacias: '',
                    OtrasSustacias : '',
                },
                estiloP: {
                    checkboxDietasaludable: '',
                    Dietasaludable: '',
                    checkboxSuenoreparador: '',
                    Suenoreparador: '',
                    checkboxDuermemenoseishoras: '',
                    Duermemenoseishoras: '',
                    checkboxAltonivelestres: '',
                    Altonivelestres: '',
                    checkboxActividadfisica: '',
                    Actividadfisica: '',
                    Duracion: '',
                    Frecuenciasemana: '',
                    checkboxFuma: '',
                    Fumainicio: '',
                    Fumadorpasivo: '',
                    checkboxTomalicor: '',
                    Licorfrecuencia: '',
                    checkboxConsumopsicoactivo: '',
                    Consumopsicoactivo: '',
                    Psicoactivocantidad: '',
                    Estilovidaobservaciones: '',
                },
                gineco: {
                    Fechaultimamenstruacion: "",
                    Gestaciones: "",
                    Partos: "",
                    Abortoprovocado: "",
                    Abortoespontaneo: "",
                    Mortinato: "",
                    Gestantefechaparto: "",
                    Gestantenumeroctrlprenatal: "",
                    Eutoxico: "",
                    Cesaria: "",
                    Cancercuellouterino: "",
                    Ultimacitologia: "",
                    Resultado: "",
                    Menarquia: "",
                    Ciclos: "",
                    Regulares: "",
                    Observaciongineco: "",
                    checkboxFechaultimamenstruacion: "",
                    checkboxGestaciones: "",
                    checkboxPartos: "",
                    checkboxAbortoprovocado: "",
                    checkboxAbortoespontaneo: "",
                    checkboxMortinato: "",
                    checkboxGestante: "",
                    checkboxEutoxico: "",
                    checkboxCesaria: "",
                    checkboxCancercuellouterino: "",
                    checkboxCitologia: "",
                    checkboxMenarquia: "",
                    checkboxCiclos: "",
                    checkboxRegulares: "",
                },
                Perimetrocefalico: false,
                examenF: {
                    Peso: "",
                    Talla: "",
                    Imc: "",
                    ISC: "",
                    Clasificacion: "",
                    Perimetroabdominal: "",
                    Perimetrocefalico: "",
                    Frecucardiaca: "",
                    Pulsos: "",
                    Frecurespiratoria: "",
                    Temperatura: "",
                    Saturacionoxigeno: "",
                    Posicion: "",
                    Lateralidad: "",
                    Presionsistolica: "",
                    Presiondiastolica: "",
                    Presionarterialmedia: "",
                    Condiciongeneral: "",
                    Cabezacuello: "",
                    Ojosfondojos: "",
                    Agudezavisual: "",
                    Cardiopulmonar: "",
                    Abdomen: "",
                    Osteomuscular: "",
                    Extremidades: "",
                    Pulsosperifericos: "",
                    Neurologico: "",
                    Reflejososteotendinos: "",
                    Pielfraneras: "",
                    Genitourinario: "",
                    Examenmama: "",
                    Tactoretal: "",
                    Examenmental: ""
                },
                antecedentePaci: [],
                antecedenteFarmacologia: [],
                antecedenteFami: [],
                antecedentes: [],
                parentesco: [],
                pacienteantecedente: [],
                parentescoantecedentes: [],
                hola: [{
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
                citaPaciente: 0,
                cie10s: [],
                cie10Array: [],
                search: "",
                Cie10_id: "",
                Codigo: "",
                Tipodiagnostico: "",
                Marcapaciente: [],
                Diagnostico: [],
                DiagnosticoSaved: [],
                selectedItems: [],
                antecedentesRCVM: [
                    "Hipertension Arterial",
                    "Dislipidemia",
                    "Diabetes Mellitus",
                    "Enfermedad Cardiovascular"
                ],
                headers: [{
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
                GlicemiaBolean: false,
                HemoglobinaglicosiladaBolean: false,
                ColesteroltotalBolean: false,
                ColesterolhdlBolean: false,
                ColesterolldlBolean: false,
                TrigliceridosBolean: false,
                ProteinuriaBolean: false,
                UroanalisisBolean: false,
                MicroalbuminuriaBolean: false,
                CreatininaBolean: false,
                rcv1: {
                    Glicemiafecha: '',
                    Glicemia: '',
                    Hemoglobinaglicosilada: '',
                    Hemoglobinafecha: '',
                    Colesteroltotal: '',
                    Colesteroltotalfecha: '',
                    Colesterolhdl: '',
                    Colesterolhdlfecha: '',
                    Colesterolldl: '',
                    Colesterolldlfecha: '',
                    Trigliceridos: '',
                    Trigliceridosfecha: '',
                    Proteinuria: '',
                    Proteinuriafecha: '',
                    Uroanalisis: '',
                    Uroanalisisfecha: '',
                    Microalbuminuria: '',
                    Microalbuminuriafecha: '',
                    Creatinina: '',
                    Creatininafecha: '',
                    Disminuciondetfg: '',
                    Resultadoframinghan: '',
                    Cumplemetaterapeutica: '',
                    Pacienteadherente: '',
                    Pacientecontrolado: '',
                    Insulinorequiriente: '',
                    Signosdealarma: '',
                    Hospitalizacionesrecientes: '',
                    Valoracionpornutricion: '',
                    Valoracionporpsicologia: '',
                    Asisteatallergrupaleducativo: '',
                    Periodicidadproximocontrol: '',
                    Proximocontrolcon: '',
                },
                items: ['SI', 'NO'],
                sino: ['SI', 'NO'],
                Diagnostico: '',
                finalH: {
                    Planmanejo: "",
                    Recomendaciones: "",
                    Destinopaciente: "",
                    Finalidad: "",
                    Especialidad_remi: ""
                },
                especialidades: [
                    'ALERGOLOGIA',
                    'ANESTESIOLOGIA',
                    'AUDIOLOGIA',
                    'BIOENERGETICA',
                    'CARDIOLOGIA',
                    'CIRUGIA BARIATRICA',
                    'CIRUGIA CARDIOVASCULAR',
                    'CIRUGIA GENERAL',
                    'CIRUGIA MAXILOFACIAL',
                    'CIRUGIA PLASTICA',
                    'COLOPROCTOLOGIA',
                    'COORDINACION MEDICA',
                    'DERMATOLOGIA',
                    'ENDOCRINOLOGIA',
                    'GINECOLOGIA Y OBSTETRICIA',
                    'HEMATOLOGIA',
                    'INFECTOLOGIA',
                    'MASTOLOGIA',
                    'MEDICINA DEL DEPORTE',
                    'MEDICINA DEL DOLOR',
                    'MEDICINA DEL TRABAJO',
                    'MEDICINA FAMILIAR',
                    'MEDICINA FISICA Y REHABILITACION',
                    'MEDICINA GENERAL',
                    'MEDICINA INTERNA',
                    'NEFROLOGIA',
                    'NEUMOLOGIA',
                    'NEUROCIRUGIA',
                    'NEUROLOGIA',
                    'OFTALMOLOGIA',
                    'ONCOLOGIA CLINICA',
                    'ORTOPEDIA Y TRAUMATOLOGIA',
                    'OTORRINOLARINGOLOGIA',
                    'PEDIATRIA',
                    'PSIQUIATRIA',
                    'REUMATOLOGIA',
                    'SALUD FAMILIAR',
                    'TOXICOLOGIA CLINICA',
                    'UROLOGIA',
                ],
            }
        },
        computed: {
            cieConcat() {
                return this.cie10Array = this.cie10s.map(cie => {
                    return {
                        id: cie.id,
                        codigo: cie.Codigo_CIE10,
                        nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                        customDisabled: false
                    };
                });
            }
        },
        mounted() {
            this.fetchAnamnesis();
            this.fetchAntecedentes();
            this.fetchParentesco();
            this.fetchAntecedentePersonal();
            this.fetchAntecedenteFamiliar();
            this.fetchLifeStyle();
            this.fetchGineco();
            this.fetchToxicologicos();
            this.fetchExamen();
            this.fetchCie10s();
            this.fetchDiagnostico();
            this.fetchConducta();
        },
        watch: {
            "examenF.Peso": function () {
                this.calcularIMC();
            },
            "examenF.Talla": function () {
                this.calcularIMC();
            },
            "examenF.Presionsistolica": function () {
                this.calcularMediaPresion();
            },
            "examenF.Presiondiastolica": function () {
                this.calcularMediaPresion();
            },
            article: function () {
                if (this.article && this.article.length >= 3) {
                    this.fetchArticulosWithout();
                }
            },
        },

        methods: {
            fetchArticulosWithout() {
                this.loading = true;
                axios.post(`/api/bodega/articulo/allMedicamentos`, {
                        article: this.article,
                    })
                    .then(res => {
                        this.listArticuloWithout = res.data;
                        this.loading = false;
                    });
            },

            fetchAnamnesis() {
                axios.get('/api/cita/' + this.citaPaciente + '/motivo')
                    .then(res => {
                        this.data = res.data;
                        this.fetchAntecedenteFarmacologia()
                    });
            },

            guardarMotivoConsulta() {
                if (!this.data.Motivoconsulta) {
                    swal("Motivo consulta es requerida! y debe ser mayor a 5 caracteres")
                    return;
                } else if (this.data.Motivoconsulta.length < 5) {
                    swal("Motivo consulta debe ser mayor a 5 caracteres")
                    return;
                }

                if (!this.data.Enfermedadactual) {
                    swal("Enfermedad actual es requerida! y debe ser mayor a 20 caracteres")
                    return;
                } else if (this.data.Enfermedadactual.length < 20) {
                    swal("Enfermedad actual debe ser mayor a 20 caracteres")
                    return;
                }
                this.preload_motivo = true;
                axios.post('/api/cita/' + this.citaPaciente + '/motivo', this.data).then((res) => {
                    this.preload_motivo = false;
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
                    this.e6 = 2;
                    // localStorage.setItem("MotivoConsulta", JSON.stringify(this.data));
                    //EventBus.$emit('change_disabled_list_history','ANTECEDENTES')
                });

            },

            guardarRevisionsisitema() {
                this.preload_motivo = true;
                axios.post('/api/cita/' + this.citaPaciente + '/revisionsistema', this.data).then((res) => {
                    this.preload_motivo = false;
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
                        title: '<span style="color:#fff">Revision por Sistema Guardado con exito<span>'
                    });
                    this.e6 = 3;
                    // localStorage.setItem("MotivoConsulta", JSON.stringify(this.data));
                })
            },

            getLocalStorage() {
                let motivo = JSON.parse(localStorage.getItem("MotivoConsulta"));
                if (motivo) {
                    this.data = motivo;
                } else {
                    this.fetchAnamnesis();
                }
            },

            guardarAntecedente() {
                if (!this.antecedentesP.Cie10_id) {
                    swal("Debe seleccionar un Dx!")
                    return;
                } else if (!this.antecedentesP.descripcion) {
                    swal("La descripción del Dx es requerida!")
                    return;
                }
                this.preload_motivo = true;
                this.antecedentesP.cita_paciente = this.citaPaciente
                axios.post("/api/pacienteantecedente/create", this.antecedentesP)
                    .then(res => {
                        this.preload_motivo = false;
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
                        this.fetchAntecedentePersonal();
                        this.clearAntecedente();
                    })
                    .catch(err => console.log(err.response.antecedentesP));
            },

            antecedentePersonales() {
                this.e6 = 4;
            },

            guardarAntecedentefamiliar() {
                this.preload_motivo = true;
                this.antecedentesP.cita_paciente = this.citaPaciente
                axios.post("/api/parentescoantecedente/create", this.antecedentesP)
                    .then(res => {
                        this.preload_motivo = false;
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
                    .catch(err => console.log(err.response.antecedentesP));
            },

            clearAntecedente() {
                this.antecedentesP.Cie10_idFami = "";
                this.antecedentesP.familiar = "";
                this.antecedentesP.descripcion = "";
                this.antecedentesP.Cie10_id = "";
            },

            clearAntecedenteFarmacologico() {
                this.antecedentesFarma.data = "";
                this.antecedentesFarma.utiempo = "";
                this.antecedentesFarma.frecuencia = "";
                this.antecedentesFarma.alergia = "";
            },

            fetchAntecedentes() {
                axios.get("/api/antecedente/all").then(res => (this.antecedentes = res.data));
            },

            fetchParentesco() {
                axios
                    .get("/api/parentesco/all")
                    .then(res => (this.parentesco = res.data));
            },

            fetchAntecedentePersonal() {
                const data = {
                    citaPaciente_id: this.citaPaciente
                }
                axios.post("/api/pacienteantecedente/antecedentes1", data)
                    .then(res => {
                        this.antecedentePaci = res.data
                    });
            },

            fetchAntecedenteFarmacologia() {
                const farm = {
                    paciente_id: this.data.paciente_id
                }
                axios.post("/api/pacienteantecedente/antecedentesFarmacologia", farm)
                    .then(res => {
                        this.antecedenteFarmacologia = res.data
                    });
            },

            fetchAntecedenteFamiliar() {
                const data = {
                    citaPaciente_id: this.citaPaciente
                }
                axios.post("/api/parentescoantecedente/familiares", data)
                    .then(res => {
                        this.antecedenteFami = res.data
                    });
            },
            guardarParentesco() {
                if (this.data.Sexo == 'F') {
                    this.e6 = 7;
                } else {
                    this.e6 = 8
                }

            },

            // removeLocalStorage() {
            //     localStorage.removeItem("MotivoConsulta");
            // },

            guardarEstilovida() {
                this.preload_liveStyle = true
                axios.post("/api/estilovida/" + this.citaPaciente + "/create", this.estiloP)
                    .then(res => {
                        this.preload_liveStyle = false;
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
                            title: '<span style="color:#fff">Estilo de vida Guardado con exito<span>'
                        });
                        this.e6 = 9;
                    });
            },

            fetchLifeStyle() {
                axios.get('/api/estilovida/' + this.citaPaciente + '/lifeStyle')
                    .then(res => {
                        if (res.data.estilovida) {
                            this.estiloP = res.data.estilovida;
                        }
                    });
            },

            guardarAntecedentesgineco() {
                axios
                    .post("/api/gineco/" + this.citaPaciente + "/create", this.gineco)
                    .then(res => {
                        this.e6 = 8;
                    });
            },

            fetchGineco() {
                axios.get('/api/gineco/' + this.citaPaciente + '/gineco')
                    .then(res => {
                        this.gineco = res.data;
                    });
            },

            calcularMediaPresion() {
                this.examenF.Presionarterialmedia =
                    (parseInt(this.examenF.Presiondiastolica) * 2 +
                        parseInt(this.examenF.Presionsistolica)) /
                    3;
                this.examenF.Presionarterialmedia = Number.parseFloat(
                    this.examenF.Presionarterialmedia
                ).toFixed(1);
            },

            calcularIMC() {
                if (this.data.Edad_Cumplida > 5) {
                    this.examenF.ISC = Math.sqrt(this.examenF.Peso * this.examenF.Talla / 3600).toFixed(3)
                }

                if (this.data.Edad_Cumplida > 17) {
                    this.examenF.Imc = this.examenF.Peso / Math.pow(this.examenF.Talla / 100, 2);

                    this.examenF.Imc = parseFloat(this.examenF.Imc).toFixed(1);
                    if (this.examenF.Imc < 16.0) {
                        this.examenF.Clasificacion = "Delgadez severa";
                    } else if (this.examenF.Imc > 16.0 && this.examenF.Imc < 16.99) {
                        this.examenF.Clasificacion = "Delgadez moderada";
                    } else if (this.examenF.Imc > 17.0 && this.examenF.Imc < 18.49) {
                        this.examenF.Clasificacion = "Delgadez aceptable";
                    } else if (this.examenF.Imc > 18.5 && this.examenF.Imc < 24.99) {
                        this.examenF.Clasificacion = "Normal";
                    } else if (this.examenF.Imc > 25.0 && this.examenF.Imc < 29.99) {
                        this.examenF.Clasificacion = "Pre-obeso";
                    } else if (this.examenF.Imc > 30.0 && this.examenF.Imc < 34.99) {
                        this.examenF.Clasificacion = "Obeso tipo I (riesgo moderado)";
                    } else if (this.examenF.Imc > 35.0 && this.examenF.Imc < 39.99) {
                        this.examenF.Clasificacion = "Obeso tipo II (riesgo severo)";
                    } else if (this.examenF.Imc >= 40.0) {
                        this.examenF.Clasificacion = "Obeso tipo III (riesgo muy severo)";
                    }
                } else if (this.data.Edad_Cumplida < 5) {
                    this.Perimetrocefalico = true;
                }
            },

            guardarAntropometricas() {
                if (!this.examenF.Peso) {
                    swal("El peso es requerida!!")
                    return;
                } else if (!this.examenF.Talla) {
                    swal("La talla es requerida!")
                    return;
                }
                this.examenF.Imc = parseInt(this.examenF.Imc);
                this.examenF.Peso = this.examenF.Peso.replace(/[,;]/g, '.');
                axios.post(
                        "/api/examenfisico/antropometricas/" + this.citaPaciente,
                        this.examenF
                    )
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
                            title: '<span style="color:#fff">Antopometricas Guardadas con exito<span>'
                        });
                        this.e6 = 10;
                    });
            },

            guardarSignosVitales() {
                if (!this.examenF.Posicion) {
                    swal("La posición es requerida!")
                    return;
                } else if (!this.examenF.Lateralidad) {
                    swal("La lateralidad es requerida!")
                    return;
                } else if (!this.examenF.Presionsistolica) {
                    swal("La presion sistolica es requerida!")
                    return;
                } else if (!this.examenF.Presiondiastolica) {
                    swal("La presion diastolica es requerida!")
                    return;
                }
                let clasificacionPresion = "Normal";

                if (
                    this.examenF.Presionsistolica > 180 ||
                    this.examenF.Presiondiastolica > 120
                ) {
                    clasificacionPresion = "CRISIS DE HIPERTENSION";
                } else if (
                    this.examenF.Presionsistolica >= 140 ||
                    this.examenF.Presiondiastolica >= 90
                ) {
                    clasificacionPresion = "PRESION ARTERIAL ALTA (NIVEL 2)";
                } else if (
                    this.examenF.Presionsistolica >= 130 ||
                    this.examenF.Presiondiastolica >= 80
                ) {
                    clasificacionPresion = "PRESION ARTERIAL ALTA (NIVEL 1)";
                } else if (
                    this.examenF.Presionsistolica >= 120 &&
                    this.examenF.Presiondiastolica < 80
                ) {
                    clasificacionPresion = "ELEVADA";
                } else if (
                    this.examenF.Presionsistolica < 120 &&
                    this.examenF.Presiondiastolica < 80
                ) {
                    clasificacionPresion = "NORMAL";
                }
                this.examenF.Clasificacionpresion = clasificacionPresion;

                axios.post("/api/examenfisico/signosvitales/" + this.citaPaciente, this.examenF)
                    .then(res => {
                        this.preload_examensistema = false;
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
                            title: '<span style="color:#fff">Signos vitales Guardados con exito<span>'
                        });
                        this.e6 = 11;
                    });
            },

            guardarExamenFisico() {
                axios.post(
                        "/api/examenfisico/" + this.citaPaciente + "/examenfisico",
                        this.examenF
                    )
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
                            title: '<span style="color:#fff">Examen Fisico Guardados con exito<span>'
                        });
                        this.e6 = 12;
                    });
            },

            fetchExamen() {
                axios.get('/api/examenfisico/' + this.citaPaciente + '/getExamenFisico')
                    .then(res => {
                        if (res.data.examen) {
                            this.examenF = res.data.examen;
                        }

                    });
            },

            addDiagnostico() {

                if (this.Cie10_id) {
                    this.cie10Array.find(cie10 => {
                        if (cie10.id == this.Cie10_id) {
                            this.Codigo = cie10.codigo;
                            // this.Diagnostico[i].Codigo = cie10.codigo;
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

            clearDataArticulo() {
                this.Cie10_id = "";
                this.Tipodiagnostico = "";
                this.Marcapaciente = "";
                this.Codigo = "";
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
            },

            submit() {
                if (this.Diagnostico.length === 0) {
                    return this.$alerError('Se requiere un diagnostico!')
                }

                this.Diagnostico[0].Esprimario = true;

                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El diagnóstico será almacenado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/cie10/create/${this.citaPaciente}`, this.Diagnostico)
                            .then(res => {
                                localStorage.setItem(
                                    "Diagnostico",
                                    JSON.stringify(this.Diagnostico),
                                    this.e6 = 13
                                );
                            })
                            .catch(err => console.log(err.response.data));
                    }
                });
            },

            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },

            fetchDiagnostico() {
                axios.get(`/api/cie10/diagnostico/${this.citaPaciente}`).then(res => {
                    this.DiagnosticoSaved = res.data.cie10;
                    this.Diagnostico = this.DiagnosticoSaved;
                });
            },

            disable(id) {
                // let i = this.Diagnostico.length - 1;
                this.cie10Array.map(cie10 => {
                    if (cie10.id == id) {
                        cie10.customDisabled = true
                        // this.Diagnostico[i].Codigo = cie10.codigo;
                    }
                });
            },

            guardarConducta() {
                if (!this.finalH.Planmanejo) {
                    swal("Plan de manejo es requerido! y debe ser mayor a 5 caracteres")
                    return;
                }else if (this.finalH.Planmanejo.length < 5) {
                    swal("Motivo consulta debe ser mayor a 5 caracteres")
                    return;
                }else if (!this.finalH.Recomendaciones) {
                    swal("La recomendación del paciente es requerida!")
                    return;
                }else if (!this.finalH.Destinopaciente) {
                    swal("Destino del paciente es requerido!")
                    return;
                } else if (this.finalH.Destinopaciente == 'Hospitalización (Remision)' && !this.finalH
                    .Especialidad_remi) {
                        swal("Debe colocar una especialidad para generar una remision!")
                    return;
                } else if (!this.finalH.Finalidad) {
                    swal("La finalidad es requerida!")
                    return;
                }
                axios.post("/api/conducta/" + this.citaPaciente + "/final", this.finalH)
                    .then(res => {
                        swal({
                            title: res.data.message,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        EventBus.$emit("send-recommendation", this.finalH);
                        EventBus.$emit("call-order");
                        this.e6 = 14;
                    });
            },

            printRecomendation() {
                if (!this.finalH.Recomendaciones) {
                    swal({
                        title: "Impresión observaciones",
                        text: "Se requiere una recomendación.",
                        icon: "error"
                    });
                } else {
                    axios.get(`/api/paciente/getPacienteWithCita/${this.citaPaciente}`)
                        .then(res => {
                            this.paciente = res.data;
                            if (this.paciente.id) {
                                var pdf = {};
                                pdf = this.getPDFRecomendation();
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
                        })
                        .catch(err => this.showError(err.response.data));
                }
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
                    IPS: this.paciente.NombreIPS,
                    Direccion_Residencia: this.paciente.Direccion_Residencia,
                    Correo: this.paciente.Correo,
                    Telefono: this.paciente.Telefono,
                    Tipo_Afiliado: this.paciente.Tipo_Afiliado,
                    Estado_Afiliado: this.paciente.Estado_Afiliado,
                    orden_id: this.order_id,
                    type: "observacion",
                    observaciones: this.data.Recomendaciones,
                    cita_paciente_id: this.citaPaciente
                });
            },

            fetchConducta() {
                axios.post('/api/conducta/' + this.citaPaciente + '/getConductaByCita')
                    .then(res => {
                        if (res.data) {
                            this.finalH = res.data
                        }
                    })
                    .catch(err => console.log(err.response));
            },

            guardarAntecedenteFarmacologico() {
                if (!this.antecedentesFarma.utiempo) {
                    swal("El campo U-Tiempo es requerida!")
                    return;
                } else if (!this.antecedentesFarma.frecuencia) {
                    swal("El campo Frecuencia es requerida!D")
                    return;
                } else if (!this.antecedentesFarma.alergia) {
                    swal("El campo Alergia es requerida!D")
                    return;
                }
                this.preload_motivo = true;
                this.antecedentesFarma.citaPaciente_id = this.citaPaciente
                axios.post('/api/cita/antecedenteFarmacologico', this.antecedentesFarma).then((res) => {
                    this.preload_motivo = false;
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
                        title: '<span style="color:#fff">Antecedente farmacologico Guardado con exito<span>'
                    });
                    this.clearAntecedenteFarmacologico();
                    this.fetchAntecedenteFarmacologia();
                });
            },

            inabilitarFarmaco(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El farmaco será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/cita/farmaco/${id}`)
                            .then(res => {
                                console.log('peon', res.data)
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchAntecedenteFarmacologia();
                });
            },

            antecedenteMedicamentos() {
                this.e6 = 5;
            },

            antecedenteToxicologicos(){
                this.preload_motivo = true;
                this.toxico.citaPaciente_id = this.citaPaciente
                axios.post('/api/cita/antecedenteToxico', this.toxico).then((res) => {
                    this.preload_motivo = false;
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
                        title: '<span style="color:#fff">Antecedente Toxicologicos Guardado con exito<span>'
                    });
                    this.e6 = 6;
                });
            },

            fetchToxicologicos() {
                axios.get('/api/pacienteantecedente/' + this.citaPaciente + '/getToxicologicos')
                    .then(res => {
                        this.toxico = res.data
                    });
            },
        }
    }

</script>
<style scoped>

</style>
