<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Medidas antropometricas</span>
                </v-card-title>
                <v-layout row wrap v-show="datosCita.Especialidad != 'Quimica Farmacologica'">
                    <v-flex xs12 sm6 md1>
                        <v-text-field label="Peso (Kg)" v-model="antropometricas.Peso" type="number" min="1" max="300">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-text-field label="Talla (Cm)" v-model="antropometricas.Talla" type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md2>
                        <v-text-field label="Indice de Masa Corporal" v-model="antropometricas.Imc" type="number" readonly>
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-text-field label="ASC" v-model="antropometricas.ISC" type="number" readonly>
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md2>
                        <v-text-field label="Clasificación" v-model="antropometricas.Clasificacion" readonly>
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md2>
                        <v-text-field label="Perímetro Abdominal" type="number"
                            v-model="antropometricas.Perimetroabdominal">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md2>
                        <v-text-field label="Perímetro Cefálico" type="number"
                            v-show="datosCita.ciclo_vital == '1ra Infancia'" v-model="antropometricas.Perimetrocefalico">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4 v-show="datosCita.ciclo_vital == '1ra Infancia'">
                        <v-select label="Peso para la talla (P/T)" :items="indicador" v-model="antropometricas.peso_talla">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4 v-show="datosCita.ciclo_vital == '1ra Infancia'">
                        <v-text-field label="Clasificación peso para la talla" readonly
                            v-model="antropometricas.clasificacion_peso_talla">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select label="Talla para la edad (T/E)" :items="indicadortalla"
                            v-model="antropometricas.talla_edad">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-text-field label="Clasificación talla para la edad" readonly
                            v-model="antropometricas.clasificacion_talla_edad">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4 v-show="datosCita.ciclo_vital == '1ra Infancia'">
                        <v-select label="Perímetro cefálico para la edad" :items="indicadorcefalico"
                            v-model="antropometricas.cefalico_edad">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4 v-show="datosCita.ciclo_vital == '1ra Infancia'">
                        <v-text-field label="Clasificación perímetro cefálico para la edad" readonly
                            v-model="antropometricas.clasificacion_cefalico_edad">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select label="IMC para la edad" :items="indicadorimc" v-model="antropometricas.imc_edad">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-text-field label="Clasificación IMC para la edad" readonly
                            v-model="antropometricas.clasificacion_imc_edad">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4 v-show="datosCita.ciclo_vital == '1ra Infancia'">
                        <v-select label="Peso para la edad" :items="indicadorpeso" v-model="antropometricas.peso_edad">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4 v-show="datosCita.ciclo_vital == '1ra Infancia'">
                        <v-text-field label="Clasificación peso para la edad" readonly
                            v-model="antropometricas.clasificacion_peso_edad">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3 v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia'
                        || datosCita.ciclo_vital == 'Vejez'">
                        <v-text-field label="Circunferencia brazo" type="number"
                            v-model="antropometricas.circunferencia_brazo">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3 v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia'
                        || datosCita.ciclo_vital == 'Vejez'">
                        <v-text-field label="Circunferencia pantorrilla" type="number"
                            v-model="antropometricas.circunferencia_pantorrilla">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md2 v-show="datosCita.Tipo_agenda == '23'">
                        <v-text-field label="Ganancia esperada" v-model="antropometricas.gananciaesperada">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="saveMedidasAntropometrica()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
                <v-card>
                    <v-data-table :headers="headers" :items="fetchAntropoSignos">
                        <template v-slot:items="props">
                            <td class="text-xs">{{ props.item.id }}</td>
                            <td class="text-xs">{{ props.item.Peso }}</td>
                            <td class="text-xs">{{ props.item.Talla }}</td>
                            <td class="text-xs">{{ props.item.Imc }}</td>
                            <td class="text-xs">{{ props.item.ISC }}</td>
                            <td class="text-xs">{{ props.item.Clasificacion }}</td>
                            <td class="text-xs">{{ props.item.Perimetroabdominal }}</td>
                            <td class="text-xs">{{ props.item.Perimetrocefalico }}</td>
                            <td class="text-xs">{{ props.item.circunferencia_brazo }}</td>
                            <td class="text-xs">{{ props.item.circunferencia_pantorrilla }}</td>
                            <td class="text-xs">{{ props.item.peso_talla }}</td>
                            <td class="text-xs">{{ props.item.clasificacion_peso_talla }}</td>
                            <td class="text-xs">{{ props.item.talla_edad }}</td>
                            <td class="text-xs">{{ props.item.clasificacion_talla_edad }}</td>
                            <td class="text-xs">{{ props.item.cefalico_edad }}</td>
                            <td class="text-xs">{{ props.item.clasificacion_cefalico_edad }}</td>
                            <td class="text-xs">{{ props.item.imc_edad }}</td>
                            <td class="text-xs">{{ props.item.clasificacion_imc_edad }}</td>
                            <td class="text-xs">{{ props.item.peso_edad }}</td>
                            <td class="text-xs">{{ props.item.clasificacion_peso_edad }}</td>
                            <td class="text-xs">{{ props.item.gananciaesperada }}</td>
                            <td class="text-xs">{{ props.item.created_at }}</td>
                        </template>
                        <template v-slot:no-results>
                            <v-alert :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </template>
                    </v-data-table>
                </v-card>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Signos vitales</span>
                </v-card-title>
                <v-layout row wrap v-show="datosCita.Especialidad != 'Quimica Farmacologica'">
                    <v-flex xs12 sm6 md2>
                        <v-select :items="['Sentado', 'Acostado', 'De pie']" label="Posición" v-model="signosVitales.Posicion"
                            chips />
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select :items="['Derecha', 'Izquierda']" label="Lateralidad" v-model="signosVitales.Lateralidad"
                            chips />
                    </v-flex>
                    <v-flex xs12 sm6 md2>
                        <v-text-field label="Sistólica" value type="number" v-model="signosVitales.Presionsistolica">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md2>
                        <v-text-field label="Diastólica" value type="number" v-model="signosVitales.Presiondiastolica">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-text-field label="Presión Arterial Media" value type="number"
                            v-model="signosVitales.Presionarterialmedia" readonly></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-text-field label="Frecuencia cardiaca" type="number" v-model="signosVitales.Frecucardiaca">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-text-field label="Pulsos" type="number" v-model="signosVitales.Pulsos"></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-text-field label="Frecuencia Respiratoria" type="number"
                            v-model="signosVitales.Frecurespiratoria">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md2>
                        <v-text-field label="Temperatura" type="number" v-model="signosVitales.Temperatura">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-text-field label="Saturación de oxígeno" type="number" v-model="signosVitales.Saturacionoxigeno">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-text-field label="Fracción inspiratoria de oxígeno" type="number" v-model="signosVitales.FiO">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="saveSignosVitales()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
                <v-card>
                    <v-data-table :headers="headersVitales" :items="fetchAntropoSignos">
                        <template v-slot:items="props">
                            <td class="text-xs">{{ props.item.id }}</td>
                            <td class="text-xs">{{ props.item.Posicion }}</td>
                            <td class="text-xs">{{ props.item.Lateralidad }}</td>
                            <td class="text-xs">{{ props.item.Presionsistolica }}</td>
                            <td class="text-xs">{{ props.item.Presiondiastolica }}</td>
                            <td class="text-xs">{{ props.item.Presionarterialmedia }}</td>
                            <td class="text-xs">{{ props.item.Frecucardiaca }}</td>
                            <td class="text-xs">{{ props.item.Pulsos }}</td>
                            <td class="text-xs">{{ props.item.Frecurespiratoria }}</td>
                            <td class="text-xs">{{ props.item.Temperatura }}</td>
                            <td class="text-xs">{{ props.item.Saturacionoxigeno }}</td>
                            <td class="text-xs">{{ props.item.FiO }}</td>


                        </template>
                        <template v-slot:no-results>
                            <v-alert :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </template>
                    </v-data-table>
                </v-card>
                <v-card-title class="headline" style="color:white;background-color:#0074a6"
                    v-show="datosCita.Especialidad != 'Quimica Farmacologica'">
                    <span class="title layout justify-center font-weight-light">Examen fisico</span>
                </v-card-title>
                <v-layout row wrap
                    v-show="datosCita.Especialidad != 'Quimica Farmacologica' && datosCita.Tipo_agenda != '536' && datosCita.Tipo_agenda != '4' && datosCita.Tipo_agenda != '181' && datosCita.Tipo_agenda != '525'">

                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Aspecto General" value v-model="examenF.Condiciongeneral"
                            :error-messages="errores.Condiciongeneral">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-checkbox color="success" v-model="examenF.checkboxPielFaneras" value="1"
                            label="Piel y faneras: ">
                        </v-checkbox>
                        <v-textarea name="input-7-1" label="Descripción:" v-model="examenF.PielFaneras"
                            v-if="examenF.checkboxPielFaneras == true">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-checkbox color="success" v-model="examenF.checkboxCcc" value="1" label="Cabeza, cuello y Cara: ">
                        </v-checkbox>
                        <v-select v-model="examenF.Selccc" v-if="examenF.checkboxCcc == true" :items="ccc"
                            label="Seleccione">
                        </v-select>
                        <v-textarea name="input-7-1" label="Cabeza" v-model="examenF.cabeza"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'Cabeza'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Cara:" v-model="examenF.Cara"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Ojos:" v-model="examenF.Ojos"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-select :items="sino" label="Preocupaciones de los cuidadores sobre problemas visuales del niño:"
                            v-model="examenF.visualnino" v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'"
                            v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia'">
                        </v-select>
                        <v-textarea name="input-7-1" label="Agudeza visual ambos ojos:" v-model="examenF.Agudezavisual"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Agudeza visual ojo derecho:" v-model="examenF.AgudezavisualDer"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Conjuntiva:" v-model="examenF.Conjuntiva"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Esclera:" v-model="examenF.Esclera"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Fondo de ojo: descripción de camara anterior"
                            v-model="examenF.OjosfondojosAnt" v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Fondo de ojo: descripción de camara posterior"
                            v-model="examenF.OjosfondojosPost" v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Nariz:" v-model="examenF.Nariz"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Tabique:" v-model="examenF.Tabique"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Cornetes:" v-model="examenF.Cornetes"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Oidos:" v-model="examenF.Oidos"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-select :items="sino" label="Tiene usted o ha tenido algún problema en el oído:"
                            v-model="examenF.problemaOido" v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-select>
                        <v-select :items="sino" label="Cree usted que escucha bien:" v-model="examenF.escuchaBien"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-select>
                        <v-textarea name="input-7-1" label="Descripción pabellón auricular derecho:"
                            v-model="examenF.AuricularDer" v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Descripción pabellón auricular Izquierdo:"
                            v-model="examenF.AuricularIzq" v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Conducto auditivo derecho:" v-model="examenF.ConductoDer"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Membrana timpanica:" v-model="examenF.MembranaTim"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-select v-model="examenF.integra" v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'"
                            :items="sino" label="Integra">
                        </v-select>
                        <v-select v-model="examenF.perforacion" v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'"
                            :items="sino" label="Perforación">
                        </v-select>
                        <v-textarea name="input-7-1" label="Presencia de tubos de ventilación:" v-model="examenF.TubosVen"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Maxilar:" v-model="examenF.Maxilar"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Labios y comisura labial:" v-model="examenF.LabiosComisura"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Mejilla y carrillos:" v-model="examenF.MejillaCarrillo"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Cavidad oral:" v-model="examenF.CavidadOral"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Articulación temporomandibular:"
                            v-model="examenF.ArticulaciónTemporo"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Estructuras dentales:" v-model="examenF.EstructurasDentales"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'cara'">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Cuello:" v-model="examenF.Cuello"
                            v-if="examenF.checkboxCcc == true & examenF.Selccc == 'Cuello'">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-checkbox color="success" v-model="examenF.checkboxCardioPulmonar" value="1"
                            label="Cardiopulmonar: ">
                        </v-checkbox>
                        <v-textarea name="input-7-1" label="Pulmones:" v-model="examenF.Pulmones"
                            v-if="examenF.checkboxCardioPulmonar == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Cardiacos:" v-model="examenF.Cardiacos"
                            v-if="examenF.checkboxCardioPulmonar == true">
                        </v-textarea>
                        <!-- <v-checkbox color="success" v-model="examenF.checkboxTorax" value="1" label="Tórax: ">
                        </v-checkbox> -->
                        <v-textarea name="input-7-1" label="Tórax:" v-model="examenF.Torax"
                            v-if="examenF.checkboxCardioPulmonar == true">
                        </v-textarea>
                        <!-- <v-checkbox color="success" v-model="examenF.checkboxDesTorax" value="1"
                            v-if="examenF.checkboxCardioPulmonar == true" label="Descripción del tórax anterior: ">
                        </v-checkbox> -->
                        <v-textarea name="input-7-1" label="Mamas:" v-model="examenF.Mamas"
                            v-if="examenF.checkboxCardioPulmonar == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Pectorales:" v-model="examenF.Pectorales"
                            v-if="examenF.checkboxCardioPulmonar == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Reja costal anterior:" v-model="examenF.RejaCostal"
                            v-if="examenF.checkboxCardioPulmonar == true">
                        </v-textarea>
                        <!-- <v-checkbox color="success" v-model="examenF.checkboxDesToraxPos" value="1"
                            v-if="examenF.checkboxCardioPulmonar == true" label="Descripción del tórax posterior: ">
                        </v-checkbox> -->
                        <v-textarea name="input-7-1" label="Reja costal posterior:" v-model="examenF.RejaCostalPos"
                            v-if="examenF.checkboxCardioPulmonar == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Desviaciones de la columna:" v-model="examenF.DesvCol"
                            v-if="examenF.checkboxCardioPulmonar == true">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-checkbox color="success" v-model="examenF.checkboxAbdomen" value="1" label="Abdomen: ">
                        </v-checkbox>
                        <v-textarea name="input-7-1" label="Abdomen:" v-model="examenF.Abdomen"
                            v-if="examenF.checkboxAbdomen == true">
                        </v-textarea>
                        <v-text-field type="number" v-show="datosCita.Tipo_agenda == '23'" name="input-7-1"
                            label="Altura uterina:" v-model="examenF.AlturaUterina" v-if="examenF.checkboxAbdomen == true">
                        </v-text-field>
                        <v-text-field type="number" v-show="datosCita.Tipo_agenda == '23'" name="input-7-1"
                            label="Actividad uterina:" v-model="examenF.ActividadUterina"
                            v-if="examenF.checkboxAbdomen == true">
                        </v-text-field>
                        <v-text-field type="number" v-show="datosCita.Tipo_agenda == '23'" name="input-7-1"
                            label="Frecuencia cardiaca fetal:" v-model="examenF.FrecuenciaCardiacaFetal"
                            v-if="examenF.checkboxAbdomen == true">
                        </v-text-field>
                        <v-select v-model="examenF.movimientosFetales" v-show="datosCita.Tipo_agenda == '23'"
                            v-if="examenF.checkboxAbdomen == true" :items="posne" label="Movimientos fetales">
                        </v-select>
                        <v-select v-model="examenF.RuidosPlacentarios" v-show="datosCita.Tipo_agenda == '23'"
                            v-if="examenF.checkboxAbdomen == true" :items="posne" label="Ruidos placentarios">
                        </v-select>
                        <v-checkbox color="success" v-model="examenF.checkboxManiobra" value="1"
                            v-show="datosCita.Tipo_agenda == '23'" v-if="examenF.checkboxAbdomen == true"
                            label="Maniobra de Leopold: ">
                        </v-checkbox>
                        <v-select v-model="examenF.PresentacionFetal" v-show="datosCita.Tipo_agenda == '23'"
                            v-if="examenF.checkboxAbdomen == true & examenF.checkboxManiobra == true" :items="fetal"
                            label="Presentación fetal:">
                        </v-select>
                        <v-select v-model="examenF.DorsoFetal" v-show="datosCita.Tipo_agenda == '23'"
                            v-if="examenF.checkboxAbdomen == true & examenF.checkboxManiobra == true" :items="derizq"
                            label="Dorso fetal:">
                        </v-select>
                        <v-text-field type="number" v-show="datosCita.Tipo_agenda == '23'" name="input-7-1"
                            label="Número de fetos:" v-model="examenF.NumFetos" v-if="examenF.checkboxAbdomen == true">
                        </v-text-field>
                        <v-textarea name="input-7-1" label="Posición uterina:" v-model="examenF.PosUtero"
                            v-if="examenF.checkboxAbdomen == true" v-show="datosCita.Tipo_agenda == '23'">
                        </v-textarea>

                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-checkbox color="success" v-model="examenF.checkboxGenitoUrinario" value="1"
                            label="Genito urinario: ">
                        </v-checkbox>
                        <v-select :items="sino" label="Presencia de alteraciones en genitales internos:"
                            v-model="examenF.alteracionesGenitales" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-select>
                        <v-select :items="sino" label="Presencia de alteraciones en genitales externos:"
                            v-model="examenF.alteracionesGenitalesExternos" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-select>
                        <v-select :items="estados" label="Clasificación De Tanner Vello Pubico :"
                            v-model="examenF.tannerVello" v-if="examenF.checkboxGenitoUrinario == true"
                            v-show="datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        </v-select>
                        <v-textarea name="input-7-1" label="Masculino:" v-model="examenF.Maculino"
                            v-show="datosCita.sexo == 'M'" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Testiculos:" v-model="examenF.Testiculos"
                            v-show="datosCita.sexo == 'M'" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Escroto:" v-model="examenF.Escroto"
                            v-show="datosCita.sexo == 'M'" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Prepucio:" v-model="examenF.Prepucio"
                            v-show="datosCita.sexo == 'M'" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Cordón espermatico:" v-model="examenF.Cordon"
                            v-show="datosCita.sexo == 'M'" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Tacto rectal:" v-model="examenF.TactoRectalHom"
                            v-show="datosCita.sexo == 'M'" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-select :items="estados" label="Clasificación De Tanner Genitales Masculinos:"
                            v-model="examenF.tannerMasculino"
                            v-show="datosCita.sexo == 'M' & datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'"
                            v-if="examenF.checkboxGenitoUrinario == true">
                        </v-select>
                        <v-textarea name="input-7-1" label="Femenino:" v-model="examenF.Femenino"
                            v-show="datosCita.sexo == 'F'" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Especuloscopia:" v-model="examenF.Especuloscopia"
                            v-show="datosCita.sexo == 'F'" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Tacto vaginal:" v-model="examenF.TactoVag"
                            v-show="datosCita.sexo == 'F'" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Involución uterina:" v-model="examenF.Involucion"
                            v-show="datosCita.sexo == 'F' & datosCita.Tipo_agenda == '18'"
                            v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Sangrado uterino:" v-model="examenF.SangradoUter"
                            v-show="datosCita.sexo == 'F'" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Expulsión de tapón mucoso:" v-model="examenF.ExpulTapon"
                            v-show="datosCita.sexo == 'F' & datosCita.Tipo_agenda == '23'"
                            v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>
                        <v-text-field type="number" v-show="datosCita.sexo == 'F' & datosCita.Tipo_agenda == '23'"
                            name="input-7-1" label="Dilatación cuello uterino:" v-model="examenF.Dilatacioncuello"
                            v-if="examenF.checkboxGenitoUrinario == true">
                        </v-text-field>
                        <v-text-field type="number" v-show="datosCita.sexo == 'F' & datosCita.Tipo_agenda == '23'"
                            name="input-7-1" label="Borramiento:" v-model="examenF.Borramiento"
                            v-if="examenF.checkboxGenitoUrinario == true">
                        </v-text-field>
                        <v-text-field type="number" v-show="datosCita.sexo == 'F' & datosCita.Tipo_agenda == '23'"
                            name="input-7-1" label="Estación fetal:" v-model="examenF.Estacion"
                            v-if="examenF.checkboxGenitoUrinario == true">
                        </v-text-field>
                        <v-text-field type="number" v-show="datosCita.sexo == 'F' & datosCita.Tipo_agenda == '18'"
                            name="input-7-1" label="Evaluación de loquios:" v-model="examenF.loquios"
                            v-if="examenF.checkboxGenitoUrinario == true">
                        </v-text-field>
                        <v-textarea name="input-7-1" label="Tacto rectal:" v-model="examenF.Tactorecfem"
                            v-show="datosCita.sexo == 'F'" v-if="examenF.checkboxGenitoUrinario == true">
                        </v-textarea>

                        <v-select :items="estados" label="Clasificación De Tanner Mamas Y Pubis Femenino:"
                            v-model="examenF.tannerFemenino"
                            v-show="datosCita.sexo == 'F' & datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'"
                            v-if="examenF.checkboxGenitoUrinario == true">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-checkbox color="success" v-model="examenF.checkboxPerine" value="1" label="Perine: ">
                        </v-checkbox>
                        <v-select v-model="examenF.DesgarroPerine" v-if="examenF.checkboxPerine == true" :items="desgarro"
                            label="Desgarro del perine:">
                        </v-select>
                        <v-select v-model="examenF.Episiorragia" v-if="examenF.checkboxPerine == true" :items="Episiorragia"
                            label="Episiorrafia:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-checkbox color="success" v-model="examenF.checkboxExtremidades" value="1" label="Extremidades: ">
                        </v-checkbox>
                        <v-textarea name="input-7-1" label="Extremidades:" v-model="examenF.Extremidades"
                            v-if="examenF.checkboxExtremidades == true">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-checkbox color="success" v-model="examenF.checkboxSistemaNervioso" value="1"
                            label="Sistema Nervioso central: ">
                        </v-checkbox>
                        <v-textarea name="input-7-1" label="Sistema Nervioso central:" v-model="examenF.SistemaNervioso"
                            v-if="examenF.checkboxSistemaNervioso == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Evaluación pares craneales:" v-model="examenF.ParesCraneales"
                            v-if="examenF.checkboxSistemaNervioso == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Evaluación marcha:" v-model="examenF.EvaluacionMarcha"
                            v-if="examenF.checkboxSistemaNervioso == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Evaluación tono muscular:"
                            v-model="examenF.EvaluacionTonoMuscular" v-if="examenF.checkboxSistemaNervioso == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Evaluación fuerza:" v-model="examenF.EvaluacionFuerza"
                            v-if="examenF.checkboxSistemaNervioso == true">
                        </v-textarea>
                        <!-- <v-textarea name="input-7-1" label="Evaluación esfera mental:"
                            v-model="examenF.EvaluacionEsfera" v-if="examenF.checkboxSistemaNervioso == true">
                        </v-textarea> -->
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-checkbox color="success" v-model="examenF.checkboxSistemaOsteo" value="1"
                            label="Sistema osteomuscular: ">
                        </v-checkbox>
                        <v-textarea name="input-7-1" label="Descripción Sistema osteomuscular:"
                            v-model="examenF.SistemaOsteo" v-if="examenF.checkboxSistemaOsteo == true">
                        </v-textarea>
                        <v-textarea name="input-7-1" label="Columna vertebral:" v-model="examenF.columna_vertebral"
                            v-if="examenF.checkboxSistemaOsteo == true">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Examen mental" v-model="examenF.examen_mental">
                        </v-textarea>
                    </v-flex>
                </v-layout>
                <v-layout row wrap
                    v-show="datosCita.Tipo_agenda == '536' || datosCita.Tipo_agenda == '4' || datosCita.Tipo_agenda == '181' || datosCita.Tipo_agenda == '525'">

                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Aspecto General" value v-model="examenF.Condiciongeneral"
                            :error-messages="errores.Condiciongeneral">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Cabeza y Cuello" v-model="examenF.Cabezacuello">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Cardiopulmonar" v-model="examenF.Cardiopulmonar">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Extremidades" v-model="examenF.Extremidades">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Reflejo Osteotendinoso" v-model="examenF.Reflejososteotendinos">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Examen de Mama" v-model="examenF.Examenmama">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Ojos y Fondo de Ojos" v-model="examenF.Ojosfondojos">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Abdomen" v-model="examenF.Abdomen">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Pulsos Perifericos" v-model="examenF.Pulsosperifericos">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Piel y Faneras" v-model="examenF.Pielfraneras">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Tactol Rectal" v-model="examenF.Tactoretal">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Agudeza Visual" v-model="examenF.Agudezavisual">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Osteomoscular" v-model="examenF.Osteomuscular">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Neurologicos" v-model="examenF.Neurologico">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Genitourinario" v-model="examenF.Genitourinario">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm12 md12>
                        <v-textarea name="input-7-1" label="Examen Mental" v-model="examenF.Examenmental">
                        </v-textarea>
                    </v-flex>
                </v-layout>
                <v-card-title class="headline" style="color:white;background-color:#0074a6"
                    v-show="datosCita.Especialidad != 'Quimica Farmacologica'"
                    v-if="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                    <span class="title layout justify-center font-weight-light">Valoración del desarrollo</span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia'">
                        <v-select v-model="examenF.motricidadGruesa" :items="alerta" label="Motricidad gruesa:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia'">
                        <v-select v-model="examenF.motricidadFina" :items="alerta" label="Motricidad Fina:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia'">
                        <v-select v-model="examenF.audicionLenguaje" :items="alerta" label="Audicion y Lenguaje:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia'">
                        <v-select v-model="examenF.personalSocial" :items="alerta" label="Personal - social:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.cuidado" :items="adecuada"
                            label="Percepción de los cuidadores del desarrollo del niño:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-text-field type="number" v-model="examenF.escalaDesarrollo"
                            label="Resultado Escala Abreviada de Desarrollo-3:">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-text-field type="number" v-model="examenF.autismo"
                            label="Resultado Test m-Chat (Tamizaje de Autismo):">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-text-field v-model="examenF.rendimiento_escolar" label="Rendimiento escolar:">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-text-field v-model="examenF.test_figura_humana"
                            label="Resultado Test de la figura humana Goodenough Harris (En caso de alteraciones del rendimiento escolar):">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.actividades" :items="sino"
                            label="Demuestren que sus actividades tienen un propósito:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.autocontrol" :items="sino" label="Ejercen autocontrol :">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.comportamientos" :items="sino"
                            label="Exhiben comportamientos fiables, consistentes y pensados :">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.autoeficacia" :items="sino" label="Expresan autoeficacia positiva :">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.independencia" :items="sino" label="Demuestran independencia:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.actividadesProposito" :items="sino"
                            label="Demuestran capacidad de resolución de problemas :">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.controlInterno" :items="sino"
                            label="Exhiben un locus de control interno :">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.funcionesEjecutivas" :items="sino" label="Funciones ejecutivas :">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.Identidad" :items="sino" label="Identidad :">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.valoracionIdentidad" :items="sino"
                            label="Resultado de instrumento de valoración de la identidad :">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.Autonomia" :items="sino" label="Autonomía :">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.valoracionAutonomia" :items="sino"
                            label="Resultado de instrumento valoración de la autonomía :">
                        </v-select>
                    </v-flex>
                </v-layout>

                <v-card-title class="headline" style="color:white;background-color:#0074a6"
                    v-show="datosCita.Especialidad != 'Quimica Farmacologica' && datosCita.Tipo_agenda != 4">
                    <span class="title layout justify-center font-weight-light">Valoración salud auditiva y
                        comunicativa</span>
                </v-card-title>
                <v-layout row wrap v-show="datosCita.Especialidad != 'Quimica Farmacologica' && datosCita.Tipo_agenda != 4">
                    <v-flex xs12 sm6 md6>
                        <v-text-field v-model="examenF.funciones" label="Funciones de la articulación, voz y habla:">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md6>
                        <v-text-field v-model="examenF.desempeñocomunicativo" label="Desempeño comunicativo:">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md12
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-text-field v-model="examenF.resultadoVale" label="Resultado Cuestionario VALE:">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md12>
                        <v-text-field v-model="examenF.factoresOido"
                            label="lista de chequeo de factores de riesgo de las enfermedades del oído, alteraciones auditivas, vestibulares y de la comunicación:">
                        </v-text-field>
                    </v-flex>
                </v-layout>
                <v-card-title class="headline" style="color:white;background-color:#0074a6"
                    v-show="datosCita.Especialidad != 'Quimica Farmacologica' && datosCita.Tipo_agenda != 4">
                    <span class="title layout justify-center font-weight-light">Valoración salud mental</span>
                </v-card-title>
                <v-layout row wrap v-show="datosCita.Especialidad != 'Quimica Farmacologica' && datosCita.Tipo_agenda != 4">
                    <v-flex xs12 sm6 md6>
                        <v-select v-model="examenF.violencia_mental" :items="sino" label="Violencia :">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6>
                        <v-select v-model="examenF.violencia_conflicto" :items="sino" label="Violencia conflicto armado:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6>
                        <v-select v-model="examenF.violencia_sexual" :items="sino" label="Violencia sexual:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md6>
                        <v-select v-model="examenF.lesionesAutoinflingidas" :items="sino" label="Lesiones autoinflingidas:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md12
                        v-show="datosCita.ciclo_vital == '1ra Infancia' || datosCita.ciclo_vital == 'Infancia' || datosCita.ciclo_vital == 'Adolescencia'">
                        <v-select v-model="examenF.riesgosMentalesNinos" :items="sino"
                            label="Tamizaje Reporting Questionnare for children (RQC) Riesgos mentales en niños:">
                        </v-select>
                    </v-flex>

                </v-layout>

                <v-flex xs12 sm12 md12 class="text-xs-center" v-show="datosCita.Especialidad != 'Quimica Farmacologica'">
                    <v-btn color="success" round @click="saveExamenFisico()">Guardar Examen Fisico y seguir</v-btn>
                </v-flex>
                <v-flex xs12 sm12 md12 class="text-xs-center" v-show="datosCita.Especialidad == 'Quimica Farmacologica'">
                    <v-btn color="success" round @click="seguirExamenFisico()">Continuar con la Historia</v-btn>
                </v-flex>
            </v-container>
        </v-form>
    </div>
</template>
<script>
export default {
    name: "",
    props: {
        datosCita: Object
    },
    data() {
        return {
            examenF: {
                checkboxCcc: false,
                cabeza: '',
                Cara: '',
                Cuello: '',
                Ojos: '',
                Condiciongeneral: '',
                Selccc: '',
                Agudezavisual: '',
                AgudezavisualDer: '',
                AgudezavisualIzq: '',
                Conjuntiva: '',
                Esclera: '',
                OjosfondojosAnt: '',
                OjosfondojosPost: '',
                Nariz: '',
                Tabique: '',
                Cornetes: '',
                Oidos: '',
                AuricularDer: '',
                AuricularIzq: '',
                ConductoDer: '',
                MembranaTim: '',
                integra: '',
                perforacion: '',
                TubosVen: '',
                Maxilar: '',
                LabiosComisura: '',
                MejillaCarrillo: '',
                CavidadOral: '',
                ArticulaciónTemporo: '',
                EstructurasDentales: '',
                checkboxTorax: false,
                Torax: '',
                checkboxDesTorax: false,
                Mamas: '',
                Pectorales: '',
                RejaCostal: '',
                checkboxDesToraxPos: false,
                RejaCostalPos: '',
                DesvCol: '',
                checkboxCardioPulmonar: '',
                Pulmones: '',
                Cardiacos: '',
                checkboxAbdomen: false,
                Abdomen: '',
                AlturaUterina: '',
                ActividadUterina: '',
                FrecuenciaCardiacaFetal: '',
                movimientosFetales: '',
                RuidosPlacentarios: '',
                checkboxManiobra: false,
                PresentacionFetal: '',
                NumFetos: '',
                PosUtero: '',
                Tacto: '',
                checkboxGenitoUrinario: '',
                Maculino: '',
                Testiculos: '',
                Escroto: '',
                Prepucio: '',
                Cordon: '',
                TactoRectalHom: '',
                Femenino: '',
                Especuloscopia: '',
                TactoVag: '',
                Involucion: '',
                SangradoUter: '',
                ExpulTapon: '',
                Dilatacioncuello: '',
                Borramiento: '',
                Estacion: '',
                loquios: '',
                Tactorecfem: '',
                TemTono: '',
                checkboxPerine: false,
                DesgarroPerine: '',
                Episiorragia: '',
                checkboxExtremidades: false,
                Extremidades: '',
                checkboxSistemaNervioso: false,
                SistemaNervioso: '',
                ParesCraneales: '',
                EvaluacionMarcha: '',
                EvaluacionTonoMuscular: '',
                EvaluacionFuerza: '',
                EvaluacionEsfera: '',
                checkboxPielFaneras: false,
                PielFaneras: '',
                checkboxSistemaOsteo: false,
                SistemaOsteo: '',
                motricidadGruesa: '',
                motricidadFina: '',
                audicionLenguaje: '',
                personalSocial: '',
                cuidado: '',
                escalaDesarrollo: '',
                autismo: '',
                resultadoVale: '',
                actividades: '',
                autocontrol: '',
                comportamientos: '',
                autoeficacia: '',
                independencia: '',
                actividadesProposito: '',
                controlInterno: '',
                funcionesEjecutivas: '',
                Identidad: '',
                valoracionIdentidad: '',
                Autonomia: '',
                valoracionAutonomia: '',
                visualnino: '',
                problemaOido: '',
                escuchaBien: '',
                factoresOido: '',
                riesgosMentalesNinos: '',
                lesionesAutoinflingidas: '',
                alteracionesGenitales: '',
                alteracionesGenitalesExternos: '',
                tannerMasculino: '',
                tannerFemenino: '',
                tannerVello: '',
                funciones: '',
                desempeñocomunicativo: '',
                violencia_mental: '',
                violencia_conflicto: '',
                violencia_sexual: '',
                rendimiento_escolar: '',
                test_figura_humana: '',
                columna_vertebral: '',
                examen_mental: '',

                // Osteomuscular: '',
                // Extremidades: '',
                // Pulsosperifericos: '',
                // Neurologico: '',
                // Reflejososteotendinos: '',
                // Pielfraneras: '',
                // Genitourinario: '',
                // Examenmama: '',
                // Tactoretal: '',
                // Examenmental: ''
            },
            antropometricas: {
                Citapaciente_id: '',
                paciente_id: '',
                Peso: '',
                Talla: '',
                Imc: '',
                ISC: '',
                Clasificacion: '',
                Perimetroabdominal: '',
                Perimetrocefalico: '',
                circunferencia_brazo: '',
                circunferencia_pantorrilla: '',
                peso_talla: '',
                clasificacion_peso_talla: '',
                talla_edad: '',
                clasificacion_talla_edad: '',
                cefalico_edad: '',
                clasificacion_cefalico_edad: '',
                imc_edad: '',
                clasificacion_imc_edad: '',
                peso_edad: '',
                clasificacion_peso_edad: '',

            },
            signosVitales: {
                Citapaciente_id: '',
                paciente_id: '',
                Posicion: '',
                Lateralidad: '',
                Presionsistolica: '',
                Presiondiastolica: '',
                Presionarterialmedia: '',
                Frecucardiaca: '',
                Pulsos: '',
                Frecurespiratoria: '',
                Temperatura: '',
                Saturacionoxigeno: '',
                FiO: '',

            },
            errores: {
                Condiciongeneral: ''

            },
            ccc: ['Cabeza', 'cara', 'Cuello'],
            fetal: ['Cefálica', 'Podalica', 'Transversal'],
            posne: ['Positivo', 'Negativo'],
            desgarro: ['suturado', 'No suturado'],
            estados: ['Estado 1', 'Estado 2', 'Estado 3', 'Estado 4', 'Estado 5'],
            alerta: ['Alerta', 'Medio', 'Medio Alto', 'Alto'],
            adecuada: ['Adecuada', 'No adecuada'],
            derizq: ['Derecha', 'Izquierda'],
            Episiorragia: ['Mediana', 'Mediana lateral'],
            perforacion: ['Central', 'periferica'],
            sino: ['Si', 'No'],
            indicador: ['> +3', '> +2 a ≤ +3', '> +1 a ≤ +2', '≥ -1 a ≤ +1', '≥ -2 a < -1', '< -2 a ≥ -3', '< -3'],
            indicadortalla: ['≥ -1', '≥ -2 a < -1', '< -2'],
            indicadorcefalico: ['> +2', '≥ -2 a ≤ 2', '< -2'],
            indicadorimc: ['> +3', '> +2 a ≤ +3', '> +1 a ≤ +2', '≤ +1', '> +2', '≥ -1 a ≤ +1', '≥-2 a < -1',
                '< -2'],
            indicadorpeso: ['> +1', '≥ -1 a ≤ +1', '≥ -2 a < -1', '< -2'],
            headers: [{
                text: 'id',
                value: 'name'
            },
            {
                text: 'Peso',
            },
            {
                text: 'Talla',
            },
            {
                text: 'Imc',
            },
            {
                text: 'Asc',
            },
            {
                text: 'Clasificado',
            },
            {
                text: 'P-abdominal',
            },
            {
                text: 'P-cefálico',
            },
            {
                text: 'Circunferencia brazo'
            },
            {
                text: 'Circunferencia pantorrilla'
            },
            {
                text: 'Peso para la talla'
            },
            {
                text: 'Clasificación peso para la talla'
            },
            {
                text: 'Talla para la edad'
            },
            {
                text: 'Clasificación talla para la edad'
            },
            {
                text: 'Perímetro cefálico para la edad'
            },
            {
                text: 'Clasificación perímetro cefálico para la edad'
            },
            {
                text: 'IMC para la edad'
            },
            {
                text: 'Clasificación IMC para la edad'
            },
            {
                text: 'Peso para la edad'
            },
            {
                text: 'Clasificación peso para la edad'
            },
            {
                text: 'Ganancia esperada',
            }, {
                text: 'Fecha',
            }

            ],
            headersVitales: [{
                text: 'id',
                value: 'name'
            },
            {
                text: 'Posición',
            },
            {
                text: 'Lateralidad',
            },
            {
                text: 'Sistólica',
            },
            {
                text: 'Diastólica',
            },
            {
                text: 'Arterial Media',
            },
            {
                text: 'Frecuencia cardiaca',
            },
            {
                text: 'Pulsos',
            },
            {
                text: 'Frecuencia respiratoria',
            },
            {
                text: 'Temperatura',
            },
            {
                text: 'Saturación',
            },
            {
                text: 'Fracción inspirada de oxígeno',
            }

            ],
            fetchAntropoSignos: [],
        }
    },
    watch: {
        "antropometricas.Peso": function () {
            this.calcularIMC();
        },
        "antropometricas.Talla": function () {
            this.calcularIMC();
        },
        "antropometricas.Imc": function () {
            this.calcularIMC();
        },
        "antropometricas.Clasificacion": function () {
            this.calcularIMC();
        },
        "signosVitales.Presionsistolica": function () {
            this.calcularMediaPresion();
        },
        "signosVitales.Presiondiastolica": function () {
            this.calcularMediaPresion();
        },
        "antropometricas.peso_talla": function () {
            this.calcularPT();
        },
        "antropometricas.talla_edad": function () {
            this.calcularTE();
        },
        "antropometricas.cefalico_edad": function () {
            this.calcularPCE();
        },
        "antropometricas.imc_edad": function () {
            this.calcularIMCE();
        },
        "antropometricas.peso_edad": function () {
            this.calcularPeso();
        }
    },
    created() {
        this.fetchExamenFisico();
        this.getExamenFisicos()
    },
    methods: {
        saveMedidasAntropometrica() {
            if (!this.antropometricas.Peso) {
                this.$alerError('El campo peso es obligatorio!')
                return;
            } else if (!this.antropometricas.Talla) {
                this.$alerError("El campo talla es obligatorio!");
                return
            } else if (this.antropometricas.Peso <= 0 || this.antropometricas.Peso >= 300) {
                this.$alerError('En el peso debe ingresar un valor correcto.')
                return;
            } else if (this.antropometricas.Perimetroabdominal <= 0 && (this.datosCita.Tipo_agenda == '536' || this
                .datosCita.Tipo_agenda == '4' || this.datosCita.Tipo_agenda == '181' || this.datosCita
                    .Tipo_agenda == '525')) {
                this.$alerError('En el perimetro abdominal no puede ser ni 0, ni estas vacio.')
                return;
            } else if (this.antropometricas.Talla <= 10 || this.antropometricas.Talla >= 300) {
                this.$alerError('En la talla debe ingresar un valor correcto.')
                return;
            }
            this.antropometricas.paciente_id = this.datosCita.paciente_id;
            this.antropometricas.Citapaciente_id = this.datosCita.cita_paciente_id;
            axios.post('/api/historia/saveAntropometricas', this.antropometricas)
                .then(res => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.fetchExamenFisico();
                    // this.clear();
                })
        },
        saveSignosVitales() {
            if (!this.signosVitales.Presionsistolica) {
                this.$alerError('El campo sistóloca es obligatorio!')
                return;
            } else if (!this.signosVitales.Presiondiastolica) {
                this.$alerError("El campo diastólica es obligatorio!");
                return
            } else if (this.signosVitales.Presionsistolica <= 10 || this.signosVitales.Presionsistolica >= 300) {
                this.$alerError('En la presión sistólica se debe ingresar un valor correcto.')
                return;
            } else if (this.signosVitales.Presiondiastolica <= 10 || this.signosVitales.Presiondiastolica >= 250) {
                this.$alerError('En la presión diastólica se debe ingresar un valor correcto.')
                return;
            }
            // SOLO DEBE APLICARSE A MEDICINA GENERAL Y PRENATAL
            if (this.datosCita.Tipo_agenda == '23' || this.datosCita.Tipo_agenda == '1') {
                if (this.signosVitales.Frecurespiratoria <= 0 || this.signosVitales.Frecurespiratoria >= 100) {
                    this.$alerError('En la frecuencia respiratoria se debe ingresar un valor correcto.')
                    return;
                } else if (this.signosVitales.Frecucardiaca <= 30 || this.signosVitales.Frecucardiaca >= 300) {
                    this.$alerError('En la frecuencia cardiaca se debe ingresar un valor correcto.')
                    return;
                } else if (this.signosVitales.Saturacionoxigeno <= 30 || this.signosVitales.Saturacionoxigeno >=
                    100) {
                    this.$alerError('En la saturación se debe ingresar un valor correcto.')
                    return;
                }
            }
            this.signosVitales.paciente_id = this.datosCita.paciente_id;
            this.signosVitales.Citapaciente_id = this.datosCita.cita_paciente_id;
            axios.post('/api/historia/saveSignosVitales', this.signosVitales)
                .then(res => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.fetchExamenFisico();
                    // this.clear();
                })
        },
        saveExamenFisico() {
            if (!this.examenF.Condiciongeneral && this.datosCita.Especialidad != "Quimica Farmacologica") {
                this.$alerError('El aspecto general del paciente es requerido!')
                return;
            }
            if (this.datosCita.Tipo_agenda == "536" || this.datosCita.Tipo_agenda == "4" || this.datosCita
                .Tipo_agenda == "181" || this.datosCita.Tipo_agenda == "525") {
                if (!this.antropometricas.Peso) {
                    this.$alerError('El peso del paciente es requerido!')
                    return;
                }
                if (!this.antropometricas.Talla) {
                    this.$alerError('La talla del paciente es requerido!')
                    return;
                }

                if (!this.antropometricas.Perimetroabdominal) {
                    this.$alerError('El perimetro abdominal del paciente es requerido!')
                    return;
                }

                if (!this.signosVitales.Presionsistolica) {
                    this.$alerError('La presión sistólica del paciente es requerida!')
                    return;
                }

                if (!this.signosVitales.Presiondiastolica) {
                    this.$alerError('La presión diastólica del paciente es requerida!')
                    return;
                }

            }
            this.examenF.paciente_id = this.datosCita.paciente_id;
            this.examenF.Citapaciente_id = this.datosCita.cita_paciente_id;
            this.examenF.especialidad = this.datosCita.Especialidad;
            axios.post('/api/historia/saveExamenFisico', this.examenF)
                .then(res => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.guardarSeguir();
                }).catch(err => {
                    this.setError(err.response.data)
                    this.$alerError(err.response.data.error)
                })
        },
        fetchExamenFisico() {
            axios.get(`/api/historia/fetchAntropoSignos/${this.datosCita.paciente_id}`)
                .then(res => {
                    this.fetchAntropoSignos = res.data;
                });
        },
        getExamenFisicos() {
            axios.get(`/api/historia/fetchExamenFisicos/${this.datosCita.cita_paciente_id}`)
                .then(res => {
                    this.examenF = res.data;
                });
        },
        calcularIMC() {
            if (this.datosCita.Edad_Cumplida > 5) {
                this.antropometricas.ISC = Math.sqrt(this.antropometricas.Peso * this.antropometricas.Talla / 3600)
                    .toFixed(3);

                this.antropometricas.Imc = (this.antropometricas.Peso / Math.pow(this.antropometricas.Talla / 100, 2)).toFixed(1);

                if (this.antropometricas.Imc < 16.0) {
                    this.antropometricas.Clasificacion = "Delgadez severa";
                } else if (this.antropometricas.Imc >= 16.0 && this.antropometricas.Imc <= 16.99) {
                    this.antropometricas.Clasificacion = "Delgadez moderada";
                } else if (this.antropometricas.Imc >= 17.0 && this.antropometricas.Imc <= 18.49) {
                    this.antropometricas.Clasificacion = "Delgadez aceptable";
                } else if (this.antropometricas.Imc >= 18.5 && this.antropometricas.Imc <= 24.99) {
                    this.antropometricas.Clasificacion = "Normal";
                } else if (this.antropometricas.Imc >= 25.0 && this.antropometricas.Imc <= 29.99) {
                    this.antropometricas.Clasificacion = "Pre-obeso";
                } else if (this.antropometricas.Imc >= 30.0 && this.antropometricas.Imc <= 34.99) {
                    this.antropometricas.Clasificacion = "Obeso tipo I (riesgo moderado)";
                } else if (this.antropometricas.Imc >= 35.0 && this.antropometricas.Imc <= 39.99) {
                    this.antropometricas.Clasificacion = "Obeso tipo II (riesgo severo)";
                } else if (this.antropometricas.Imc >= 40.0) {
                    this.antropometricas.Clasificacion = "Obeso tipo III (riesgo muy severo)";
                }
            }
        },

            // calcularIMC() {
            //     if (this.datosCita.Edad_Cumplida > 5) {
            //         this.antropometricas.ISC = Math.sqrt(this.antropometricas.Peso * this.antropometricas.Talla / 3600)
            //             .toFixed(3)
            //     }

            //     if (this.datosCita.Edad_Cumplida > 5) {
            //         this.antropometricas.Imc = this.antropometricas.Peso / Math.pow(this.antropometricas.Talla / 100,
            //             2).toFixed(3);

            //         this.antropometricas.Imc = parseFloat(this.antropometricas.Imc).toFixed(1);
            //         if (this.antropometricas.Imc < 16.0) {
            //             this.antropometricas.Clasificacion = "Delgadez severa";
            //         } else if (this.antropometricas.Imc > 16.0 && this.antropometricas.Imc < 16.99) {
            //             this.antropometricas.Clasificacion = "Delgadez moderada";
            //         } else if (this.antropometricas.Imc > 17.0 && this.antropometricas.Imc < 18.49) {
            //             this.antropometricas.Clasificacion = "Delgadez aceptable";
            //         } else if (this.antropometricas.Imc > 18.5 && this.antropometricas.Imc < 24.99) {
            //             this.antropometricas.Clasificacion = "Normal";
            //         } else if (this.antropometricas.Imc >= 25.0 && this.antropometricas.Imc < 29.99) {
            //             this.antropometricas.Clasificacion = "Pre-obeso";
            //         } else if (this.antropometricas.Imc > 30.0 && this.antropometricas.Imc < 34.99) {
            //             this.antropometricas.Clasificacion = "Obeso tipo I (riesgo moderado)";
            //         } else if (this.antropometricas.Imc > 35.0 && this.antropometricas.Imc < 39.99) {
            //             this.antropometricas.Clasificacion = "Obeso tipo II (riesgo severo)";
            //         } else if (this.antropometricas.Imc >= 40.0) {
            //             this.antropometricas.Clasificacion = "Obeso tipo III (riesgo muy severo)";
            //         }
            //     }
            // },
            calcularMediaPresion() {
            this.signosVitales.Presionarterialmedia =
                (parseInt(this.signosVitales.Presiondiastolica) * 2 +
                    parseInt(this.signosVitales.Presionsistolica)) /
                3;
            this.signosVitales.Presionarterialmedia = Number.parseFloat(
                this.signosVitales.Presionarterialmedia
            ).toFixed(1);
        },
        clear() {
            this.antropometricas.Peso = '',
                this.antropometricas.Talla = '',
                this.antropometricas.Imc = '',
                this.antropometricas.ISC = '',
                this.antropometricas.Clasificacion = '',
                this.antropometricas.Perimetroabdominal = '',
                this.antropometricas.Perimetrocefalico = '',
                this.signosVitales.Posicion = '',
                this.signosVitales.Lateralidad = '',
                this.signosVitales.Presionsistolica = '',
                this.signosVitales.Presiondiastolica = '',
                this.signosVitales.Presionarterialmedia = '',
                this.signosVitales.Frecucardiaca = '',
                this.signosVitales.Pulsos = '',
                this.signosVitales.Frecurespiratoria = '',
                this.signosVitales.Temperatura = '',
                this.signosVitales.Saturacionoxigeno = ''

        },
        setError(errors) {
            for (const key in this.errores) {
                if (key in errors) {
                    this.errores[key] = errors[key].join(',')
                }
            }
        },
        guardarSeguir() {
            this.$emit('respuestaComponente')
        },

        calcularPT() {
            if (this.antropometricas.peso_talla == "> +3") {
                this.antropometricas.clasificacion_peso_talla = "Obesidad";
            } else if (this.antropometricas.peso_talla == "> +2 a ≤ +3") {
                this.antropometricas.clasificacion_peso_talla = "Sobrepeso";
            } else if (this.antropometricas.peso_talla == "> +1 a ≤ +2") {
                this.antropometricas.clasificacion_peso_talla = "Riesgo de Sobrepeso";
            } else if (this.antropometricas.peso_talla == "≥ -1 a ≤ +1") {
                this.antropometricas.clasificacion_peso_talla = "Peso Adecuado para la Talla";
            } else if (this.antropometricas.peso_talla == "≥ -2 a < -1") {
                this.antropometricas.clasificacion_peso_talla = "Riesgo de Desnutrición Aguda";
            } else if (this.antropometricas.peso_talla == "< -2 a ≥ -3") {
                this.antropometricas.clasificacion_peso_talla = "Desnutrición Aguda Moderada*";
            } else if (this.antropometricas.peso_talla == "< -3") {
                this.antropometricas.clasificacion_peso_talla = "Desnutrición Aguda Severa*";
            }
        },

        calcularTE() {
            if (this.antropometricas.talla_edad == "≥ -1") {
                this.antropometricas.clasificacion_talla_edad = "Talla Adecuada para la Edad.";
            } else if (this.antropometricas.talla_edad == "≥ -2 a < -1") {
                this.antropometricas.clasificacion_talla_edad = "Riesgo de Talla Baja";
            } else if (this.antropometricas.talla_edad == "< -2") {
                this.antropometricas.clasificacion_talla_edad = "Talla Baja para la Edad o Retraso en Talla";
            }
        },

        calcularPCE() {
            if (this.antropometricas.cefalico_edad == "> +2") {
                this.antropometricas.clasificacion_cefalico_edad = "Factor de Riesgo para el Neurodesarrollo";
            } else if (this.antropometricas.cefalico_edad == "≥ -2 a ≤ 2") {
                this.antropometricas.clasificacion_cefalico_edad = "Normal";
            } else if (this.antropometricas.cefalico_edad == "< -2") {
                this.antropometricas.clasificacion_cefalico_edad = "Factor de Riesgo para el Neurodesarrollo";
            }
        },

        calcularIMCE() {
            if (this.antropometricas.imc_edad == "> +3") {
                this.antropometricas.clasificacion_imc_edad = "Obesidad";
            } else if (this.antropometricas.imc_edad == "> +2 a ≤ +3") {
                this.antropometricas.clasificacion_imc_edad = "Sobrepeso";
            } else if (this.antropometricas.imc_edad == "> +1 a ≤ +2" & this.datosCita.ciclo_vital ==
                '1ra Infancia') {
                this.antropometricas.clasificacion_imc_edad = "Riesgo de Sobrepeso";
            } else if (this.antropometricas.imc_edad == "≤ +1") {
                this.antropometricas.clasificacion_imc_edad = "No Aplica (Verificar con P/T)";
            } else if (this.antropometricas.imc_edad == "> +2") {
                this.antropometricas.clasificacion_imc_edad = "Obesidad";
            } else if (this.antropometricas.imc_edad == "≥ -1 a ≤ +1") {
                this.antropometricas.clasificacion_imc_edad = "IMC Adecuado para la Edad";
            } else if (this.antropometricas.imc_edad == "≥-2 a < -1") {
                this.antropometricas.clasificacion_imc_edad = "Riesgo de Delgadez";
            } else if (this.antropometricas.imc_edad == "< -2") {
                this.antropometricas.clasificacion_imc_edad = "Delgadez";
            } else if (this.antropometricas.imc_edad == "> +1 a ≤ +2" & this.datosCita.ciclo_vital ==
                'Adolescencia') {
                this.antropometricas.clasificacion_imc_edad = "Sobrepeso";
            } else if (this.antropometricas.imc_edad == "> +1 a ≤ +2" & this.datosCita.ciclo_vital == 'Infancia') {
                this.antropometricas.clasificacion_imc_edad = "Sobrepeso";
            }
        },

        calcularPeso() {
            if (this.antropometricas.peso_edad == "> +1") {
                this.antropometricas.clasificacion_peso_edad = "No Aplica (Verificar con IMC/E)";
            } else if (this.antropometricas.peso_edad == "≥ -1 a ≤ +1") {
                this.antropometricas.clasificacion_peso_edad = "Peso Adecuado para la Edad";
            } else if (this.antropometricas.peso_edad == "≥ -2 a < -1") {
                this.antropometricas.clasificacion_peso_edad = "Riesgo de Desnutrición Global.";
            } else if (this.antropometricas.peso_edad == "< -2") {
                this.antropometricas.clasificacion_peso_edad = "Desnutrición Global.";
            }
        },

        seguirExamenFisico() {
            this.$emit('respuestaComponente')
        }
    }
}

</script>
