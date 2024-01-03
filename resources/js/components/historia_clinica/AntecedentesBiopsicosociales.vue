<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Antecedentes sexuales y
                        reproductivos</span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex xs12 sm6 md3 v-show="datosCita.ciclo_vital != '1ra Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxOrientacionSex" value="1"
                            label="Orientación sexual">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.orientacion_sex"
                            v-if="biopsicosocial.checkboxOrientacionSex == true" :items="orientacionsex" label="Tipo">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3 v-show="datosCita.ciclo_vital !='1ra Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxIdentidadGenero" value="1"
                            label="Identidad de género">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.identidad_genero"
                            v-if="biopsicosocial.checkboxIdentidadGenero == true" :items="identidadgenero" label="Tipo">
                        </v-select>
                        <v-select v-model="biopsicosocial.identidad_generoTransgenero"
                            v-if="biopsicosocial.checkboxIdentidadGenero == true & biopsicosocial.identidad_genero =='Transgenero'"
                            :items="Transgenero" label="Tipo">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3 v-show="datosCita.ciclo_vital !='1ra Infancia'" v-if="datosCita.sexo =='M'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxEdadEsperma" value="1"
                            label="Espermarquia">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.Espermarquia"
                            v-if="biopsicosocial.checkboxEdadEsperma == true" :items="sinoaplica"
                            label="Si / No / No aplica">
                        </v-select>
                        <v-text-field type="number" v-model="biopsicosocial.edad_esperma"
                            v-if="biopsicosocial.checkboxEdadEsperma == true & biopsicosocial.Espermarquia =='Si' "
                            label="Edad">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3 v-show="datosCita.ciclo_vital !='1ra Infancia'" v-if="datosCita.sexo =='F'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxEdadMenarquia" value="1"
                            label="Menarquia">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.Menarquia" v-if="biopsicosocial.checkboxEdadMenarquia == true"
                            :items="sinoaplica" label="Si / No / No aplica">
                        </v-select>
                        <v-text-field type="number" v-model="biopsicosocial.edad_menarquia"
                            v-if="biopsicosocial.checkboxEdadMenarquia == true & biopsicosocial.Menarquia =='Si' "
                            label="Edad">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3 v-show="datosCita.ciclo_vital !='1ra Infancia'" v-if="datosCita.sexo =='F'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxCicloMenstrual" value="1"
                            label="Ciclos mentruales">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.Ciclos" v-if="biopsicosocial.checkboxCicloMenstrual == true"
                            :items="sinoaplica" label="Si / No / No aplica">
                        </v-select>
                        <v-select v-model="biopsicosocial.CiclosMnestruales"
                            v-if="biopsicosocial.checkboxCicloMenstrual == true & biopsicosocial.Ciclos == 'Si'"
                            :items="ciclos" label="Regular / Irregular">
                        </v-select>

                    </v-flex>
                    <v-flex xs12 sm6 md3 v-show="datosCita.ciclo_vital !='1ra Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxIncioSexual" value="1"
                            label="Inicio de relaciones sexuales">
                        </v-checkbox>
                        <v-text-field type="number" v-model="biopsicosocial.inicio_sexual"
                            v-if="biopsicosocial.checkboxIncioSexual == true" label="Edad">
                        </v-text-field>

                    </v-flex>
                    <v-flex xs12 sm6 md3 v-show="datosCita.ciclo_vital !='1ra Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxNumeroRelaciones" value="1"
                            label="Número de compañeros sexuales">
                        </v-checkbox>
                        <v-text-field type="number" v-model="biopsicosocial.numero_relaciones"
                            v-if="biopsicosocial.checkboxNumeroRelaciones == true" label="Cuantos">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3 v-show="datosCita.ciclo_vital !='1ra Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxActivoSexual" value="1"
                            label="Activo sexualmente">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.activo_sexual"
                            v-if="biopsicosocial.checkboxActivoSexual == true" :items="sino" label="Si / No">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3
                        v-show="datosCita.ciclo_vital !='1ra Infancia' & datosCita.ciclo_vital !='Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxDificultadesRelaciones" value="1"
                            label="¿Tiene dificultades durante las relaciones sexuales">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.dificultades_relaciones"
                            v-if="biopsicosocial.checkboxDificultadesRelaciones == true" :items="sino" label="Si / No">
                        </v-select>
                        <v-textarea name="input-7-1" v-model="biopsicosocial.Descripciondificultad"
                            v-if="biopsicosocial.checkboxDificultadesRelaciones == true & biopsicosocial.dificultades_relaciones =='Si'"
                            label="Descripción">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3
                        v-show="datosCita.ciclo_vital != '1ra Infancia' & datosCita.ciclo_vital != 'Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxUsoAnticonceptivo" value="1"
                            label="¿Usa usted algún métodos anticonceptivos?">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.uso_anticonceptivos"
                            v-if="biopsicosocial.checkboxUsoAnticonceptivo == true" :items="sino" label="Si / No">
                        </v-select>
                        <v-select v-model="biopsicosocial.tipo_anticonceptivos"
                            v-if="biopsicosocial.checkboxUsoAnticonceptivo == true & biopsicosocial.uso_anticonceptivos=='Si'"
                            :items="tipoanticonceptivo" label="Tipo">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3
                        v-show="datosCita.ciclo_vital !='1ra Infancia' & datosCita.ciclo_vital !='Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxConocimientoIts" value="1"
                            label="¿Tiene conocimiento en enfermedades de transmisión sexual?">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.conocimiento_its"
                            v-if="biopsicosocial.checkboxConocimientoIts == true" :items="sino" label="Si / No">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxSufridoIts" value="1"
                            label="¿Ha sufrido alguna enfermedad de transmisión sexual?">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.sufrido_its" v-if="biopsicosocial.checkboxSufridoIts == true"
                            :items="sino" label="Si / No">
                        </v-select>
                        <v-textarea name="input-7-1" v-model="biopsicosocial.CualIts"
                            v-if="biopsicosocial.checkboxSufridoIts == true & biopsicosocial.sufrido_its =='Si'"
                            label="Cual">
                        </v-textarea>
                        <v-text-field type="date" v-model="biopsicosocial.fecha_enfermedadIts"
                            v-if="biopsicosocial.checkboxSufridoIts == true & biopsicosocial.sufrido_its =='Si'"
                            label="Fecha enfermedad">
                        </v-text-field>
                        <v-textarea name="input-7-1" v-model="biopsicosocial.TratamientoIts"
                            v-if="biopsicosocial.checkboxSufridoIts == true & biopsicosocial.sufrido_its =='Si'"
                            label="Tratamiento">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md3
                        v-show="datosCita.ciclo_vital !='1ra Infancia' && datosCita.ciclo_vital !='Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxTransmisionSexual" value="1"
                            label="¿Utiliza protección para la prevencion de enfermedades de transmision sexual y/o VIH?">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.trasnmision_sexual"
                            v-if="biopsicosocial.checkboxTransmisionSexual == true" :items="sino" label="Si / No">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3 v-show="datosCita.ciclo_vital !='1ra Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxDerechosSexuales" value="1"
                            label="¿Tiene conocimiento sobre sus derechos sexuales y reproductivos?">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.derechos_sexuales"
                            v-if="biopsicosocial.checkboxDerechosSexuales == true" :items="sino" label="Derechos">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3
                        v-show="datosCita.ciclo_vital !='1ra Infancia' & datosCita.ciclo_vital !='Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxDecisionesSexRep" value="1"
                            label="¿Toma usted  decisiones alrededor de su sexualidad y reproducción?">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.decisionesSexRep"
                            v-if="biopsicosocial.checkboxDecisionesSexRep == true" :items="sino" label="Si / No">
                        </v-select>
                        <!-- <v-select v-model="biopsicosocial.tipodecisionesSexRep"
                            v-if="biopsicosocial.checkboxDecisionesSexRep == true & biopsicosocial.decisionesSexRep=='Si'"
                            :items="decisionesRep" label="Tipo">
                        </v-select> -->
                    </v-flex>
                    <v-flex xs12 sm6 md3
                        v-show="datosCita.ciclo_vital !='1ra Infancia' & datosCita.ciclo_vital !='Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxVictimaIdentidadgenero" value="1"
                            label="¿Ha sido victima de identidad de Genero?">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.victima_identidadgenero"
                            v-if="biopsicosocial.checkboxVictimaIdentidadgenero == true" :items="sino" label="">
                        </v-select>
                        <v-select v-model="biopsicosocial.tipo_victimagenero"
                            v-if="biopsicosocial.checkboxVictimaIdentidadgenero == true & biopsicosocial.victima_identidadgenero=='Si'"
                            :items="tipovictimaidentigenero" label="Tipo">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3
                        v-show="datosCita.ciclo_vital !='1ra Infancia' & datosCita.ciclo_vital !='Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxVictimaGenero" value="1"
                            label="¿Ha sido victima de violencia contra la mujer y/o violencia de género?">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.victima_genero"
                            v-if="biopsicosocial.checkboxVictimaGenero == true" :items="sino" label="Si / No">
                        </v-select>
                        <v-select v-model="biopsicosocial.tipo_victima_violencia_genero"
                            v-if="biopsicosocial.checkboxVictimaGenero == true & biopsicosocial.victima_genero=='Si'"
                            :items="tipovictimagenero" label="Tipo">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3  v-show="datosCita.ciclo_vital !='1ra Infancia' & datosCita.ciclo_vital !='Infancia'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxSignosViolencia" value="1"
                            label="¿Ha sido víctima de violencia física y sexual en algún momento de su vida?">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.violencia"
                            v-if="biopsicosocial.checkboxSignosViolencia == true" :items="sino" label="Si / No">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxPresenciaMutilacion" value="1"
                            label="¿Ha sido víctima de mutilación genital?">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.PresenciaMutilacion"
                            v-if="biopsicosocial.checkboxPresenciaMutilacion == true" :items="sino" label="Si / No">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3
                        v-show="datosCita.ciclo_vital !='Juventud' & datosCita.ciclo_vital !='Adultez' & datosCita.ciclo_vital !='Vejez'">
                        <v-checkbox color="success" v-model="biopsicosocial.checkboxMatrimonioInfantil" value="1"
                            label="¿Ha sido víctima de matrimonio forzoso o en etapa infantil?">
                        </v-checkbox>
                        <v-select v-model="biopsicosocial.MatrimonioInfantil"
                            v-if="biopsicosocial.checkboxMatrimonioInfantil == true" :items="sino" label="Si / No">
                        </v-select>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-card-title primary-title class="headline" style="color:white;background-color:#00a632">
                            <span class="title layout justify-center font-weight-light">Antecedentes
                                biopsicosociales</span>
                        </v-card-title>
                    </v-flex>
                     <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6"
                         v-show="datosCita.ciclo_vital =='1ra Infancia' || datosCita.ciclo_vital =='Infancia' || datosCita.ciclo_vital =='Adolescencia'">
                            <span class="title layout justify-center font-weight-light">Desarrollo y Aprendizaje </span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6 md4
                        v-show="datosCita.ciclo_vital =='1ra Infancia' || datosCita.ciclo_vital =='Infancia' || datosCita.ciclo_vital =='Adolescencia'">
                        <v-select v-model="biopsicosocial.rendimiento" :items="escolar" label="Rendimiento escolar ">
                        </v-select>
                    </v-flex>
                    <!-- <v-flex xs12 sm6 md8
                        v-show="datosCita.ciclo_vital =='1ra Infancia' || datosCita.ciclo_vital =='Infancia' || datosCita.ciclo_vital =='Adolescencia'">
                        <v-text-field v-model="biopsicosocial.testAlteraciones" label="Resultado Test de la figura humana Goodenough Harris (En caso de alteraciones del rendimiento escolar)">
                        </v-text-field>
                    </v-flex> -->
                     <v-flex xs12 sm6 md2
                        v-show="datosCita.ciclo_vital =='1ra Infancia' || datosCita.ciclo_vital =='Infancia' || datosCita.ciclo_vital =='Adolescencia'">
                        <v-select v-model="biopsicosocial.aprendizaje" :items="escolar" label=" Aptitud de aprendizaje ">
                        </v-select>
                    </v-flex>
                      <v-flex xs12 sm6 md2
                        v-show="datosCita.ciclo_vital =='1ra Infancia' || datosCita.ciclo_vital =='Infancia' || datosCita.ciclo_vital =='Adolescencia'">
                        <v-select v-model="biopsicosocial.actitudAula" :items="escolar" label=" Actitud en el aula ">
                        </v-select>
                    </v-flex>
                     <v-flex xs12 sm6 md6
                        v-show="datosCita.ciclo_vital =='1ra Infancia' || datosCita.ciclo_vital =='Infancia' || datosCita.ciclo_vital =='Adolescencia'">
                        <v-select v-model="biopsicosocial.relacionamiento" :items="escolar" label="Relacionamiento con compañeros y docentes">
                        </v-select>
                    </v-flex>
                    <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Ecomapa </span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6 md2
                        v-show="datosCita.Tipo_agenda !='63'">
                        <v-select v-model="biopsicosocial.trabaja" :items="sino" label="¿Trabaja?">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.iglesia" :items="sino" label="¿Asiste a la iglesia?">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select v-model="biopsicosocial.ClubDeportivo" :items="sino"
                            label="¿Pertenece a algún club deportivo?">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Amigos" :items="sino" label="¿Comparte con sus amigos?">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Asiste_colegio" :items="sino" label="¿Asiste al colegio?">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select v-model="biopsicosocial.Comparte_Vecinos" :items="sino"
                            label="¿Comparte con sus vecinos?">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md5>
                        <v-select v-model="biopsicosocial.Club_Social" :items="sino"
                            label="¿Pertenece a algún club social o cultural?">
                        </v-select>
                        <v-textarea v-model="biopsicosocial.Cual_club" v-if="biopsicosocial.Club_Social == 'Si'"
                            label="¿Cuál?">
                        </v-textarea>
                        <v-textarea v-model="biopsicosocial.Observacion_club" v-if="biopsicosocial.Club_Social == 'Si'"
                            label="Observación">
                        </v-textarea>
                    </v-flex>
                </v-layout>
                <div v-show="datosCita.Edad_Cumplida >= 8">
                    <v-card-title class="headline" style="color:white;background-color:#0074a6">
                        <span class="title layout justify-center font-weight-light">Apgar familiar</span>
                    </v-card-title>
                    <v-layout row wrap>
                        <v-flex xs12 sm6 md3 v-for="c in arrCheckBox" :key="c.id">
                            <label style="font-size: 8px;">
                                <h2>{{c.nombre}}</h2>
                            </label>
                            <v-radio-group style="font-size: 8px" v-model="apgarFamiliar[c.model]">
                                <v-radio color="red" v-for="n in c.options" :key="n.val" :label="n.nombre"
                                    :value="n.val"></v-radio>
                            </v-radio-group>
                        </v-flex>
                        <v-flex xs12 sm12 md12 style="text-align: center">
                            <label style="font-size: large"><b>Resultado:{{resultadoApgar}}</b></label>
                        </v-flex>
                        <v-flex xs12 sm12 md12 style="text-align: center">
                            <v-btn color="success" round @click="saveResultado()">Guardar Resultado</v-btn>
                        </v-flex>
                        <v-flex xs12 sm12 md12>
                            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                                <span class="title layout justify-center font-weight-light">Resultados</span>
                            </v-card-title>
                            <v-data-table :headers="headerApgar" :items="apgarfamiliares" class="elevation-1">
                                <template v-slot:items="props">
                                    <td class="text-xs">{{ props.item.id }}</td>
                                    <td class="text-xs">{{ props.item.valor_ayuda_familia | clasificacion}}</td>
                                    <td class="text-xs">{{ props.item.valor_familia_habla_con_usted | clasificacion}}
                                    </td>
                                    <td class="text-xs">{{ props.item.valor_cosas_nuevas | clasificacion}}</td>
                                    <td class="text-xs">{{ props.item.valor_le_gusta_familia_hace | clasificacion}}</td>
                                    <td class="text-xs">{{ props.item.valor_le_gusta_familia_comparte | clasificacion}}
                                    </td>
                                    <td class="text-xs">{{ props.item.resultado }}</td>
                                    <td class="text-xs">{{ props.item.name }}</td>
                                </template>
                                <template v-slot:pageText="props">
                                    Lignes {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                                </template>
                            </v-data-table>
                        </v-flex>
                    </v-layout>
                </div>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Familiograma</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select v-model="biopsicosocial.Vinculos" :items="familiograma" label="Vinculos:">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select v-model="biopsicosocial.Relacion" :items="afectiva" label="Relación afectiva">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select v-model="biopsicosocial.TipoFamilia" :items="tipofamilia" label="Tipo familia">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field type="number" v-model="biopsicosocial.hijos_conforman"
                            label="Número de hijos que conforman la familia:">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-textarea v-model="biopsicosocial.cuantas_familia" label="Responsables ingresos familia:">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select v-model="biopsicosocial.problemasSalud" :items="sino"
                            label="¿Problemas salud/enfermedad?">
                        </v-select>
                        <v-textarea v-model="biopsicosocial.cualsalud" v-if="biopsicosocial.problemasSalud == 'Si'"
                            label="¿Cuál?">
                        </v-textarea>
                        <v-textarea v-model="biopsicosocial.Observacion_Salud"
                            v-if="biopsicosocial.problemasSalud == 'Si'" label="Observación">
                        </v-textarea>
                    </v-flex>
                </v-layout>
                <v-layout row wrap v-show="datosCita.ciclo_vital !='1ra Infancia' & datosCita.ciclo_vital !='Infancia'">
                    <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Actividad economica</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-text-field type="number" v-model="biopsicosocial.actividad_laboral"
                            label="Edad de inicio de su actividad laboral:">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md9>
                        <v-select v-model="biopsicosocial.alteraciones" :items="sino"
                            label="¿Sufre usted alteraciones temporales, permanentes o agravadas del estado de salud, ocasionadas por la labor o por la exposición al medio ambiente de trabajo? ">
                        </v-select>
                        <v-textarea v-model="biopsicosocial.descripcion_actividad"
                            v-if="biopsicosocial.alteraciones == 'Si'" label="¿Cuál">
                        </v-textarea>
                    </v-flex>
                </v-layout>
                <v-layout row wrap v-show="datosCita.Tipo_agenda == 23">
                    <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Escala de riesgo biopsicosocial
                                Prenatal</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-text-field type="number" v-model="biopsicosocial.historia_repro"
                            label="Edad historia reproductiva">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-text-field type="number" v-model="biopsicosocial.Paridad" label="Paridad">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.cesarea" :items="sino" label="Cesárea previa">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.preeclampsia" :items="sino"
                            label="Preeclampsia o hipertensión">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select v-model="biopsicosocial.Abortos_Recurrentes" :items="sino"
                            label="Abortos recurrentes o infertilidad">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md5>
                        <v-select v-model="biopsicosocial.Hemorragia_Pos" :items="sino"
                            label="Hemorragia postparto o remoción manual de placenta">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-text-field type="number" v-model="biopsicosocial.Peso_recien" label="Peso del recién nacido">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select v-model="biopsicosocial.Mortalidad_fetal" :items="sino"
                            label="Mortalidad fetal tardia o neonatal temprama">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select v-model="biopsicosocial.Trabajo_parto" :items="sino"
                            label="Trabajo de parto anormal o dificultoso">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select v-model="biopsicosocial.Cirugia_Gineco" :items="sino"
                            label="Cirugia ginecológica previa">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Renal" :items="sino" label="Enfermedad renal crónica">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Diabetes_Gestacional" :items="sino"
                            label="Diabetes gestacional">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Diabetes_Preconcepcional" :items="sino"
                            label="Diabetes preconcepcional">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Hemorragia" :items="sino" label="Hemorragia">
                        </v-select>
                        <v-text-field type="number" v-model="biopsicosocial.semanas_hemorragia"
                            v-if="biopsicosocial.Hemorragia == 'Si'" label="Ingresar las semanas de embarazo">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Anemia" :items="sino" label="Anemia">
                        </v-select>
                        <v-text-field type="number" v-model="biopsicosocial.valor_anemia"
                            v-if="biopsicosocial.Anemia == 'Si'" label="Digite el valor de la hemoglobina">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Embarazo_prolongado" :items="sino"
                            label="Embarazo prolongado">
                        </v-select>
                        <v-text-field type="number" v-model="biopsicosocial.semanas_prolongado"
                            v-if="biopsicosocial.Embarazo_prolongado == 'Si'" label="Digite las semanas de embarazo">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Hiper_arterial" :items="sino" label="Hipertensión arterial">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Polihidramnios" :items="sino" label="Polihidramnios">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Embarazo_multiple" :items="sino" label="Embarazo múltiple">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Presente_frente" :items="sino"
                            label="Presentación de frente o transversa">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Isoinmunización" :items="sino" label="Isoinmunización">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.Ansiedad_severa" :items="sino" label="Ansiedad severa">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-select v-model="biopsicosocial.familiar_inadecuado" :items="sino"
                            label="Soporte social familiar inadecuado">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm12 md12 style="text-align: center">
                        <v-textarea v-model="biopsicosocial.Resultadopre" readonly label="Resultado">
                        </v-textarea>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-btn color="success" round @click="saveBiopsicosocial()">Guardar y seguir</v-btn>
        </v-form>
    </div>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchBiopsicosocial();
            this.fetchApgarFamiliar();
            this.fetchlistaApgar();
        },
        data() {
            return {
                cesarea: '',
                preeclampsia: '',
                abortos:'',
                hemorragiapos:'',
                mortalidad:'',
                trabajo: '',
                cirugia: '',
                renal: '',
                diabetes: '',
                pre: '',
                hiper: '',
                poli: '',
                multi: '',
                presente: '',
                iso: '',
                ansiedad: '',
                inadecuado: '',
                repro: '',
                paridad: '',
                recien: '',
                semhemo: '',
                anemia: '',
                prolongado: '',
                biopsicosocial: {
                    citapaciente_id: '',
                    paciente_id: '',
                    checkboxOrientacionSex: '',
                    checkboxIdentidadGenero: '',
                    checkboxIncioSexual: '',
                    checkboxNumeroRelaciones: '',
                    checkboxUsoAnticonceptivo: '',
                    checkboxEdadMenarquia: '',
                    checkboxEdadEsperma: '',
                    checkboxActivoSexual: '',
                    checkboxDificultadesRelaciones: '',
                    checkboxConocimientoIts: '',
                    checkboxTransmisionSexual: '',
                    checkboxDerechosSexuales: '',
                    checkboxDecisionesSexRep: '',
                    checkboxPreocupacionSalud: '',
                    checkboxProblemasDesarroInfantil: '',
                    checkboxVictimaGenero: '',
                    checkboxVictimaIdentidadgenero: '',
                    orientacion_sex: '',
                    identidad_genero: '',
                    inicio_sexual: '',
                    numero_relaciones: '',
                    activo_sexual: '',
                    edad_esperma: '',
                    edad_menarquia: '',
                    uso_anticonceptivos: '',
                    dificultades_relaciones: '',
                    conocimiento_its: '',
                    trasnmision_sexual: '',
                    derechos_sexuales: '',
                    victima_identidadgenero: '',
                    victima_genero: '',
                    violencia: '',
                    checkboxSignosViolencia: '',
                    problemas_desarrolloinfantil: '',
                    preocupacion_salud: '',
                    tipo_victimagenero: '',
                    tipo_victima_violencia_genero: '',
                    checkboxPresenciaMutilacion: false,
                    PresenciaMutilacion: '',
                    checkboxMatrimonioInfantil: false,
                    MatrimonioInfantil: '',
                    identidad_generoTransgenero: '',
                    Espermarquia: '',
                    checkboxEdadMenarquia: false,
                    Menarquia: '',
                    checkboxSufridoIts: false,
                    CualIts: '',
                    TratamientoIts: '',
                    fecha_enfermedadIts: '',
                    Descripciondificultad: '',
                    checkboxDuermeBien: '',
                    checkboxCuantasHoras: '',
                    checkboxTiempoJuego: '',
                    checkboxActivadFisica: '',
                    duerme_bien: '',
                    cuantas_horas: '',
                    tiempo_juego: '',
                    actividad_fisica: '',
                    checkboxBañoDia: '',
                    cuantas_baño: '',
                    checkboxCuidadoBucal: '',
                    cuidado_bucal: '',
                    checkboxControlVesical: '',
                    cuidado_vesical: '',
                    checkboxCaracteristicasOrina: '',
                    caracteristicas_orina: '',
                    checkboxControlRectal: '',
                    cuidado_rectal: '',
                    checkboxCaracteristicasdeposiciones: '',
                    caracteristicas_deposiciones: '',
                    checkboxExposicionTv: '',
                    exposicion_tv: '',
                    tipo_anticonceptivos: '',
                    checkboxCicloMenstrual: '',
                    Ciclos: '',
                    // antecedentes biopsicosociales
                    trabaja: '',
                    iglesia: '',
                    ClubDeportivo: '',
                    Amigos: '',
                    Asiste_colegio: '',
                    Comparte_Vecinos: '',
                    Club_Social: '',
                    Cual_club: '',
                    Observacion_club: '',
                    Ayuda_Familia: '',
                    Habla_Familia: '',
                    Cosas_nuevas: '',
                    Familia_Cuando: '',
                    Familia_Tiempo: '',
                    CiclosMnestruales: '',
                    Resultado: '',
                    Vinculos: '',
                    Relacion: '',
                    problemasSalud: '',
                    cualsalud: '',
                    Observacion_Salud: '',
                    TipoFamilia: '',
                    cuantas_familia: '',
                    hijos_conforman: '',
                    actividad_laboral: '',
                    alteraciones: '',
                    historia_repro: '',
                    Paridad: '',
                    cesarea: '',
                    preeclampsia: '',
                    Abortos_Recurrentes: '',
                    Hemorragia_Pos: '',
                    Peso_recien: '',
                    Mortalidad_fetal: '',
                    Trabajo_parto: '',
                    Cirugia_Gineco: '',
                    Renal: '',
                    Diabetes_Gestacional: '',
                    Hemorragia: '',
                    semanas_hemorragia: '',
                    Anemia: '',
                    valor_anemia: '',
                    Embarazo_prolongado: '',
                    semanas_prolongado: '',
                    Hiper_arterial: '',
                    Polihidramnios: '',
                    Embarazo_multiple: '',
                    Presente_frente: '',
                    Isoinmunización: '',
                    Ansiedad_severa: '',
                    familiar_inadecuado: '',
                    Resultadopre: '',
                    Aviso: '',
                    tipodecisionesSexRep: '',
                    sufrido_its: '',
                    descripcion_actividad: '',
                    rendimiento:'',
                    aprendizaje:'',
                    actitudAula:'',
                    relacionamiento:'',
                    testAlteraciones:'',
                },
                orientacionsex: ['Heterosexual', 'Homosexual', 'Bisexual', 'Pansexual', 'Asexual', 'otro',
                    'No desea contestar'
                ],
                identidadgenero: ['Hombre', 'Mujer', 'Transgenero',
                    'Genero Neutro', 'Agenero', 'No desea contestar'
                ],
                escolar:['Bueno','Malo', 'regular'],
                sino: ['Si', 'No'],
                sinoaplica: ['Si', 'No', 'No aplica'],
                tipoanticonceptivo: ['Inyectable trimestral', 'Inyectable mensual', 'Oral', 'DIU',
                    'Implante Subdermico', 'Preservativo de Barrera( Condon y/o Diafragma)', 'Tubectomia',
                    'Vasectomia', 'No vida Sexual'
                ],
                derechosexuales: ['Autonomia', 'Maternidad Planeada', 'Paternidad Planeada',
                    'Interrupcion Voluntaria del Embarazo'
                ],
                tipovictimaidentigenero: ['Sexismo', 'Homofobia ', 'Transfobia'],
                tipovictimagenero: ['Explotacion Sexual', 'Explotacion Comercial (ESCI)', 'Violencia Sexual'],
                decisionesRep: ['autonomía', 'maternidad o paternidad planeada',
                    'interrupción voluntaria del embarazo'
                ],
                ApgarFamiliar: ['Siempre', 'Casi siempre', 'Nunca', 'Casi nunca'],
                ciclos: ['Regular', 'Irregular'],
                familiograma: ['Noviazgo', 'Esposos', 'Unión libre', 'Divorciados', 'Separados', 'Viudo','No aplica'],
                afectiva: ['Conflictiva', 'Dominante', 'Repulsiva', 'Distante', 'Estresante', 'Satisfactoria'],
                tipofamilia: ['Nuclear', 'Extensa', 'Monoparental', 'Biparental', 'Reconstituida',
                    'Personas sin familia'
                ],
                Transgenero: ['Travesti', 'Transformista', 'Drag queen', 'Hombres trans', 'Mujeres trans'],
                apgarfamiliares: [],
                arrCheckBox: [{
                        id: 1,
                        nombre: 'Estoy contento de pensar que puedo recurrir a mi familia en busca de ayuda cuando algo me preocupa.',
                        model: 'ayuda_familia',
                        options: [{
                            nombre: 'SIEMPRE',
                            val: 4
                        }, {
                            nombre: 'CASI SIEMPRE',
                            val: 2,
                        }, {
                            nombre: 'ALGUNAS VECES',
                            val: 1,
                        }, {
                            nombre: 'CASI NUNCA',
                            val: 0
                        }]
                    },
                    {
                        id: 2,
                        nombre: 'Estoy satisfecho con el modo que tiene mi familia de hablar las cosas conmigo y de cómo compartimos los problemas.',
                        model: 'familia_habla_con_usted',
                        options: [{
                            nombre: 'SIEMPRE',
                            val: 4
                        }, {
                            nombre: 'CASI SIEMPRE',
                            val: 2
                        }, {
                            nombre: 'ALGUNAS VECES',
                            val: 1
                        }, {
                            nombre: 'CASI NUNCA',
                            val: 0
                        }]
                    },
                    {
                        id: 3,
                        nombre: 'Me agrada pensar que mi familia acepta y apoya mis deseos de llevar a cabo nuevas actividades o seguir una nueva dirección.',
                        model: 'cosas_nuevas',
                        options: [{
                            nombre: 'SIEMPRE',
                            val: 4
                        }, {
                            nombre: 'CASI SIEMPRE',
                            val: 2
                        }, {
                            nombre: 'ALGUNAS VECES',
                            val: 1
                        }, {
                            nombre: 'CASI NUNCA',
                            val: 0
                        }]
                    },
                    {
                        id: 4,
                        nombre: 'Me satisface el modo que tiene mi familia de expresar su afecto y cómo responde a mis emociones, como cólera, tristeza y amor.',
                        model: 'le_gusta_familia_hace',
                        options: [{
                                nombre: 'SIEMPRE',
                                val: 4
                            }, {
                                nombre: 'CASI SIEMPRE',
                                val: 2
                            },
                            {
                                nombre: 'ALGUNAS VECES',
                                val: 1
                            },
                            {
                                nombre: 'CASI NUNCA',
                                val: 0
                            }
                        ]
                    },
                    {
                        id: 5,
                        nombre: 'Me satisface la forma en que mi familia y yo pasamos el tiempo juntos.',
                        model: 'le_gusta_familia_comparte',
                        options: [{
                                nombre: 'SIEMPRE',
                                val: 4
                            }, {
                                nombre: 'CASI SIEMPRE',
                                val: 2
                            },
                            {
                                nombre: 'ALGUNAS VECES',
                                val: 1
                            },
                            {
                                nombre: 'CASI NUNCA',
                                val: 0
                            }
                        ]
                    },
                    {
                        id: 6,
                        nombre: 'NOTA: si el resultado esta entre 17 y 20 es normal, entre 16-13 disfunción leve, entre 12 y 10 disfunción moderada y menor o igual a 9 es disfunción grave.',


                    }
                ],
                result: ['Funcionalidad normal', 'Disfunción moderada', 'Disfunción grave'],
                apgarFamiliar: {
                    ayuda_familia: '',
                    familia_habla_con_usted: '',
                    cosas_nuevas: '',
                    le_gusta_familia_hace: '',
                    le_gusta_familia_comparte: '',
                    resultado: 0
                },
                headerApgar: [{
                        text: 'id',
                    },
                    {
                        text: 'Ayuda la familia',
                    },
                    {
                        text: 'Comparte problemas',
                    },
                    {
                        text: 'Hacen cosas nueva',
                    },
                    {
                        text: 'Apoyo familiar',
                    },
                    {
                        text: 'Tiempo familiar',
                    },
                    {
                        text: 'Resultado',
                    },
                    {
                        text: 'Registrado por',
                    },
                ],
            }
        },
        filters: {
            clasificacion(item) {
                if (parseInt(item) == 0) {
                    return 'CASI NUNCA'
                } else if (parseInt(item) == 1) {
                    return 'ALGUNAS VECES'
                } else if (parseInt(item) == 2) {
                    return 'CASI SIEMPRE'
                } else {
                    return 'SIEMPRE'
                }
            }
        },
        computed: {
            resultadoApgar() {
                return parseInt(this.apgarFamiliar.ayuda_familia + this.apgarFamiliar.familia_habla_con_usted +
                    this.apgarFamiliar.cosas_nuevas + this.apgarFamiliar.le_gusta_familia_hace + this.apgarFamiliar
                    .le_gusta_familia_comparte)
            },
        },
        watch: {
            "biopsicosocial.cesarea"() {
                this.calcularResultadPrenatal();
            },
            "biopsicosocial.preeclampsia": function () {
                this.calcularResultadPrenatal();
            },
            "biopsicosocial.Abortos_Recurrentes": function () {
               this.calcularResultadPrenatal();
            },
            "biopsicosocial.Hemorragia_Pos": function () {
               this.calcularResultadPrenatal();
            },
            "biopsicosocial.Mortalidad_fetal": function () {
                this.calcularResultadPrenatal();
             },
             "biopsicosocial.Mortalidad_fetal": function () {
               this.calcularResultadPrenatal();
            },
            "biopsicosocial.Trabajo_parto": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.Cirugia_Gineco": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.Renal": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.Diabetes_Gestacional": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.Diabetes_Preconcepcional": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.Hiper_arterial": function () {
                 this.calcularResultadPrenatal();
             },
            "biopsicosocial.Polihidramnios": function () {
                 this.calcularResultadPrenatal();
             },
            "biopsicosocial.Embarazo_multiple": function () {
                this.calcularResultadPrenatal();
            },
            "biopsicosocial.Presente_frente": function () {
              this.calcularResultadPrenatal();
            },
             "biopsicosocial.Isoinmunización": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.Ansiedad_severa": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.familiar_inadecuado": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.historia_repro": function () {
                this.calcularResultadPrenatal();
            },
             "biopsicosocial.Paridad": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.Peso_recien": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.semanas_hemorragia": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.Anemia": function () {
                 this.calcularResultadPrenatal();
             },
             "biopsicosocial.Embarazo_prolongado": function () {
                this.calcularResultadPrenatal();
             }

        },
        methods: {
            saveBiopsicosocial() {
                this.biopsicosocial.paciente_id = this.datosCita.paciente_id;
                this.biopsicosocial.citapaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveBiopsicosocial', this.biopsicosocial)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchBiopsicosocial();
                        this.$emit('respuestaComponente');
                    })
            },
            fetchBiopsicosocial() {
                axios.get(`/api/historia/fetchBiopsicosocial/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.biopsicosocial = res.data;
                    });
            },
            saveResultado() {
                this.apgarFamiliar.resultado = this.resultadoApgar
                this.apgarFamiliar.citapaciente_id = this.datosCita.cita_paciente_id;
                this.apgarFamiliar.paciente_id = this.datosCita.paciente_id;
                axios.post('/api/historia/saveApgarFamiliar', this.apgarFamiliar)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchApgarFamiliar();
                    })
            },
            fetchApgarFamiliar() {
                axios.get(`/api/historia/fetchApgarFamiliar/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.apgarfamiliares = res.data;
                    });
            },
            fetchlistaApgar() {
                axios.get(`/api/historia/fetchlistaApgar/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.listaApgarfamiliar = res.data;
                        this.apgarFamiliar.ayuda_familia = parseInt(res.data.valor_ayuda_familia);
                        this.apgarFamiliar.familia_habla_con_usted = parseInt(res.data
                            .valor_familia_habla_con_usted);
                        this.apgarFamiliar.cosas_nuevas = parseInt(res.data.valor_cosas_nuevas);
                        this.apgarFamiliar.le_gusta_familia_hace = parseInt(res.data.valor_le_gusta_familia_hace);
                        this.apgarFamiliar.le_gusta_familia_comparte = parseInt(res.data
                            .valor_le_gusta_familia_comparte);
                    });
            },
            calcularResultadPrenatal() {
                this.biopsicosocial.cesarea =='Si'?this.cesarea = 1:this.cesarea = 0
                this.biopsicosocial.preeclampsia == 'Si'?this.preeclampsia = 1:this.preeclampsia = 0
                this.biopsicosocial.Abortos_Recurrentes == 'Si'? this.abortos = 1: this.abortos = 0
                this.biopsicosocial.Hemorragia_Pos == 'Si'?this.hemorragiapos = 1:this.hemorragiapos = 0
                this.biopsicosocial.Mortalidad_fetal == 'Si'?this.mortalidad = 1:this.mortalidad = 0
                this.biopsicosocial.Trabajo_parto == 'Si'?this.trabajo = 1:this.trabajo = 0
                this.biopsicosocial.Cirugia_Gineco == 'Si'? this.cirugia = 1: this.cirugia = 0
                this.biopsicosocial.Renal == 'Si'?this.renal = 2:this.renal = 0
                this.biopsicosocial.Diabetes_Gestacional == 'Si'?this.diabetes = 2:this.diabetes = 0
                this.biopsicosocial.Diabetes_Preconcepcional == 'Si'?this.pre = 3:this.pre = 0
                this.biopsicosocial.Hiper_arterial == 'Si'?this.hiper = 2:this.hiper = 0
                this.biopsicosocial.Polihidramnios == 'Si'?this.poli = 2:this.poli = 0
                this.biopsicosocial.Embarazo_multiple == 'Si'?this.multi = 3:this.multi = 0
                this.biopsicosocial.Presente_frente == 'Si'?this.presente = 3:this.presente = 0
                this.biopsicosocial.Isoinmunización == 'Si'?this.iso = 3:this.iso = 0
                this.biopsicosocial.Ansiedad_severa == 'Si'?this.ansiedad = 1:this.ansiedad = 0
                this.biopsicosocial.familiar_inadecuado == 'Si'?this.inadecuado = 1:this.inadecuado = 0
                this.resultadoPrenatal();

            },

            resultadoPrenatal(){
                let resultado = parseInt(this.cesarea + this.preeclampsia + this.abortos + this.hemorragiapos + this.mortalidad + this.trabajo + this.cirugia + this.renal
                + this.diabetes + this.pre + this.hiper + this.poli + this.multi + this.presente + this.iso +  this.ansiedad + this.inadecuado + this.repro +  this.paridad
                + this.recien + this.semhemo + this.anemia + this.prolongado)
                if(resultado >= 3){
                    this.biopsicosocial.Resultadopre = 'Alto riesgo biopsicosocial'
                }else if (resultado < 3){
                    this.biopsicosocial.Resultadopre = 'Sin riesgo biopsicosocial'
                }
            }


        }
    }

</script>
