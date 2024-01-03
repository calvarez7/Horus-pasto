<template>
    <div>
        <v-container grid-list-md fluid class="pa-0">
            <v-card>
                <v-card-text class="headline success" style="color:white">
                    <h4 style="color:white">Generar Caracterización</h4>
                </v-card-text>
            </v-card>
            <v-flex xs12>
                <v-form @submit.prevent="search_paciente()">
                    <v-card>
                        <v-container grid-list-md>
                            <v-layout row wrap>
                                <v-flex xs10>
                                    <v-text-field v-model="cedula_paciente" label="Número de Documento" autofocus>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-btn @click="search_paciente()" @keyup.enter="search_paciente()" v-if="!paciente.id"
                                        fab outline small color="success">
                                        <v-icon>search</v-icon>
                                    </v-btn>
                                    <v-btn @click="clearFields()" v-if="paciente.id" fab outline small color="error">
                                        <v-icon>clear</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-form>
            </v-flex>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" persistent max-width="1800px" transition="dialog-bottom-transition">
                <v-card>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-card max-height="100%" class="mb-3">
                                <h1 style="text-align: center; background: lightseagreen; color: white;">Informacion del
                                    usuario
                                </h1>
                            </v-card>
                            <!-- formulario -->
                            <v-layout wrap>
                                <v-flex xs6 sm6 md3>
                                    <v-text-field v-model="paciente.Ut" readonly label="Entidad">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs5>
                                    <v-text-field v-show="paciente.Ut == 'REDVITAL UT'" v-model="paciente.NombreIPS"
                                        readonly label="IPS">
                                    </v-text-field>
                                    <v-text-field v-show="paciente.Ut == 'MEDIMAS'" v-model="paciente.Mpio_Afiliado"
                                        readonly label="IPS">
                                    </v-text-field>
                                    <v-text-field v-show="paciente.Ut == 'FERROCARRILES NACIONALES'"
                                        v-model="paciente.Mpio_Afiliado" readonly label="IPS">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6 sm6 md3>
                                    <v-text-field v-model="paciente.tipo_categoria" readonly label="Régimen">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6 sm6 md3>
                                    <v-text-field v-model="paciente.Tipo_Afiliado" label="Tipo" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6 sm6 md3>
                                    <v-text-field v-model="paciente.Primer_Nom" readonly label="Primer Nombre">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6 sm6 md3>
                                    <v-text-field v-model="paciente.SegundoNom" readonly label="Segundo Nombre">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6 sm6 md3>
                                    <v-text-field v-model="paciente.Primer_Ape" readonly label="Primer Apellido">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6 sm6 md3>
                                    <v-text-field v-model="paciente.Segundo_Ape" readonly label="Segundo Apellido">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1>
                                    <v-text-field v-model="paciente.Sexo" readonly label="Sexo">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs5 sm6 md3>
                                    <v-select :items="Tipo_Doc" label="Tipo Documento" v-model="paciente.Tipo_Doc">
                                    </v-select>
                                </v-flex>
                                <v-flex xs5 sm6 md3>
                                    <v-text-field v-model="paciente.Num_Doc" readonly label="Número Documento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6 sm6 md3 v-if="paciente.Fecha_Naci">
                                    <v-text-field v-model="paciente.Fecha_Naci.split(' ')[0]" readonly
                                        label="Fecha Nacimiento"></v-text-field>
                                </v-flex>
                                <v-flex xs5 sm6 md3>
                                    <v-text-field v-model="paciente.Edad_Cumplida" readonly label="Edad">
                                    </v-text-field>
                                </v-flex>
                                <!-- INICIO DE REGISTRO CARACTERIZACION -->
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="sino" v-model="paciente.alergicas"
                                        label="¿Usted ha presentado reacciones alérgicas a las vacunaciones?">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="sino" v-model="paciente.vacunado"
                                        label="¿Usted ha sido vacunado contra COVID-19?"></v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="sino" v-model="paciente.vacunarse"
                                        label="¿Usted desea vacunarse contra el COVID-19?"></v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-autocomplete append-icon="search" label="¿Cuál es su Departamento de residencia?"
                                        :items="departamentos" item-text="departamento" item-value="id" hide-details
                                        v-model="paciente.Departamento" required></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-autocomplete label="¿Cuál es su municipio de residencia?" :items="municipios"
                                        item-text="municipio" item-value="id" hide-details v-model="paciente.Mpio_Atencion"
                                        required></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-select :items="zona" label="zona_vivienda" v-model="paciente.zona_vivienda" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-text-field label="¿Cuál es su dirección?" v-model="paciente.Direccion_Residencia"
                                        required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-text-field label="¿Cuál es su barrio de residencía?" v-model="paciente.residencia"
                                        required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-text-field label="¿Cuál es su número telefónico 1?" v-model="paciente.telefono1"
                                        required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field label="¿Cuál es su número telefónico 2?" v-model="paciente.telefono2"
                                        required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field label="¿A cuál correo electrónico usted puede ser contactado?"
                                        v-model="paciente.correocontacto" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm3 md4>
                                    <v-select :items="miembros"
                                        label="¿Cuántos miembros conforman el hogar? (personas con las que vive, incluye encuestado)"
                                        v-model="paciente.numero_miembros" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs8 sm4 md4>
                                    <v-text-field label="Parentesco del cuidador" v-model="paciente.parentesco" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select :items="vivienda" label="Tipo vivienda" v-model="paciente.tipo_vivienda"
                                        required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="sino" label="¿En su hogar tiene agua potable y alcantarillado? *"
                                        v-model="paciente.hogar_con_agua" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="alimentos"
                                        label="La preparación de alimentos en su hogar se realiza con:"
                                        v-model="paciente.alimentos">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-select :items="sino" label="¿La vivienda cuenta con energía eléctrica? "
                                        v-model="paciente.energia" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs8 sm4 md4>
                                    <v-select :items="habitaciones" label="¿Con cuantas habitaciones cuenta su vivienda?"
                                        v-model="paciente.numero_habitaciones" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-select :items="accesibilidad" label="accesibilidad_vivienda"
                                        v-model="paciente.accesibilidad_vivienda" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-select :items="calificacion" label="seguridad_orden"
                                        v-model="paciente.seguridad_orden" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-select :items="etnico" label="¿A qué Grupo Étnico pertenece?"
                                        v-model="paciente.etnia" required>
                                    </v-select>
                                </v-flex>
                                <!-- Establecer cual es la condicion de discapacidad -->
                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">¿Tiene
                                        alguna condición especial de discapacidad?</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.fisica" label="Física" color="red" value="primary"
                                                hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.auditiva" label="Auditiva" color="orange"
                                                value="secondary" hide-details></v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.visual" label="Visual" color="success"
                                                value="success" hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.sordera" label="Sordoceguera" color="info"
                                                value="Sordoceguera" hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.intelectual" label="Intelectual" color="info"
                                                value="Intelectual" hide-details>
                                            </v-checkbox>
                                            <v-checkbox v-model="paciente.mental" label="Mental" color="info" value="Mental"
                                                hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <!-- Establecer cual es el grado de la discapacidad -->
                                <v-container>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">¿Cúal es el
                                        grado de la discapacidad?</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md6>
                                            <v-radio-group v-model="paciente.Grado_Discapacidad">
                                                <v-radio label="NULA" color="success" value="Nula"
                                                    hide-details></v-radio>
                                                <v-radio label="LEVE" color="info" value="Leve"
                                                    hide-details></v-radio>
                                                <v-radio label="MODERADA" color="orange" value="Moderada"
                                                    hide-details></v-radio>
                                                <v-radio label="SEVERA" color="orange" value="Severa"
                                                    hide-details></v-radio>
                                                <v-radio label="MUY GRAVE" color="red" value="Muy Grave"
                                                    hide-details></v-radio>
                                            </v-radio-group>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <!-- fin -->
                                <v-flex xs12 sm6 md3>
                                    <v-select :items="escolaridad" label="¿Cuál es su nivel de escolaridad ?"
                                        v-model="paciente.Nivelestudio" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md5>
                                    <v-select :items="estrato" label="¿Cuál el número de estrato de su vivienda?"
                                        v-model="paciente.Estrato" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-select :items="estadoCivil" label="¿Cuál es su estado civil?"
                                        v-model="paciente.estado_civil" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-select :items="orientacionsexual" label="orientacion_sexual"
                                        v-model="paciente.orientacion_sexual" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-select :items="oficio" label="Ocupacion u oficio" v-model="paciente.oficio" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-select :items="planificacion" label="planificando_metodos"
                                        v-model="paciente.planificando_metodos" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="noaplica"
                                        label="¿Tiene planeado embarazo en un tiempo inferior a 1 año? (Solo para mujeres)"
                                        v-model="paciente.planeado_embarazo" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select :items="noaplica"
                                        label="¿Se ha realizado Citología en el último año? (solo aplica para mujeres)"
                                        v-model="paciente.citologia_ultimo_ano" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md8>
                                    <v-select :items="noaplica"
                                        label="¿Se ha realizado Mamografía en los últimos 2 años? (solo aplica para mujeres de 50 a 69 años)"
                                        v-model="paciente.tamizaje_Mamografia" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md8>
                                    <v-select :items="noaplica"
                                        label="¿Se ha realizado tamizaje de Cáncer de Próstata en los últimos 5 años? (Aplica para hombres de 50 a 75 años)"
                                        v-model="paciente.tamizaje_Prostata" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select :items="sino" label="¿Practica usted el autocuidado de su salud?"
                                        v-model="paciente.autocuidado_salud" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="sino" label="¿Usted ha sido víctima de algún tipo de violencia?"
                                        v-model="paciente.victima_violencia" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select :items="sino" label="¿Usted ha sido víctima del desplazamiento forzado?"
                                        v-model="paciente.victima_desplazamiento" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="tabaco" label="¿Qué tipo de exposición usted presenta al tabaco?"
                                        v-model="paciente.exposicion_tabaco" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field label="¿Cuántos tabacos consume al día?" type="number"
                                        v-model="paciente.tabacos_al_dia" required>
                                    </v-text-field>
                                </v-flex>
                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">¿Por
                                        cuántos años se ha expuesto al tabaco?</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm6 md6>
                                            <v-radio-group v-model="paciente.expuestotabaco" column>
                                                <v-radio label="NO EXPOSICIÓN" color="red" value="red"></v-radio>
                                                <v-radio label="MENOR A 10 AÑOS" color="red darken-3" value="red darken-3">
                                                </v-radio>
                                                <v-radio label="ENTRE 10 A 20 AÑOS" color="indigo" value="indigo">
                                                </v-radio>
                                                <v-radio label="MÁS DE 20 AÑOS" color="indigo darken-3"
                                                    value="indigo darken-3"></v-radio>
                                            </v-radio-group>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="sino" label="¿Usted consume Sustancias Psicoactivas?"
                                        v-model="paciente.consume_sustancias_psicoactivas" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="psicoactivas"
                                        label="Frecuencia de consumo de sustancias psicoactivas:"
                                        v-model="paciente.Frecuencia_consumo_psicoactivas" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-select :items="sino" label="consume_alcohol" v-model="paciente.consume_alcohol"
                                        required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-select :items="psicoactivas" label="Frecuencia de consumo de alcohol:"
                                        v-model="paciente.Frecuencia_consumo_alcohol" required>
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md12>
                                    <h2>¿Usted practica al menos 150 minutos semanales (aproximadamente dos horas) de
                                        actividad física moderada o al menos 75 minutos semanales de actividad física
                                        intensa?</h2>
                                    <v-select :items="sino" label="" v-model="paciente.actividad_fisica" required>
                                    </v-select>
                                </v-flex>
                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">En su
                                        familia existen antecedentes de Cáncer de:</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_mama" label="MAMA" color="red"
                                                value="X" hide-details style="margin-right: 0;"></v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_prostata" label="PROSTATA"
                                                color="orange" value="X" hide-details style="margin-right: 0;"></v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_pulmon" label="PULMON" color="success"
                                                value="X" hide-details style="margin-right: 0;"></v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_gastrointestinales"
                                                label="GASTROINTESTINALES" color="GASTROINTESTINALES" value="X" hide-details
                                                style="margin-right: 0;">
                                            </v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_cervicouterino" label="CERVICOUTERINO"
                                                color="CERVICOUTERINO" value="X" hide-details style="margin-right: 0;">
                                            </v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_piel" label="PIEL" color="PIEL"
                                                value="X" hide-details style="margin-right: 0;">
                                            </v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_otros" label="OTROS" color="OTROS"
                                                value="X" hide-details style="margin-right: 0;">
                                            </v-checkbox>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">En su
                                        familia existen antecedentes de alguna de las siguientes enfermedades
                                        metabólicas</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.obesidad" label="OBESIDAD" color="red" value="X"
                                                hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.tiroides" label="ENFERMEDADES DE TIROIDES"
                                                color="orange" value="X" hide-details></v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.mellitus" label="DIABETES MELLITUS"
                                                color="success" value="X" hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.dislipidemia" label="DISLIPIDEMIAS" color="info"
                                                value="X" hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">¿Tiene o ha
                                        tenido algunos de los siguientes diagnósticos de salud mental?</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.ansiedad" label="ANSIEDAD" color="red" value="X"
                                                hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.depresion" label="DEPRESION" color="orange"
                                                value="X" hide-details></v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.esquizofrenia" label="ESQUIZOFRENIA"
                                                color="success" value="X" hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.consumopsicoativo"
                                                label="CONSUMO DE SUSTANCIAS PSICOACTIVAS" color="info" value="X"
                                                hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.bipolar" label="TRASTORNO DEL ÁNIMO BIPOLAR"
                                                color="GASTROINTESTINALES" value="X" hide-details>
                                            </v-checkbox>
                                            <v-checkbox v-model="paciente.hiperactividad"
                                                label="DEFICIT DE ATENCION POR HIPERACTIVIDAD" color="CERVICOUTERINO"
                                                value="X" hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.conductaalimentaria"
                                                label="TRASTORNO DE LA CONDUCTA ALIMENTARIA (ANOREXIA, BULIMIA, TRASTORNO POR ATRACÓN, TRASTORNO POR RESTRICCIÓN, OTROS)"
                                                color="PIEL" value="X" hide-details></v-checkbox>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">¿Usted
                                        tiene antecedentes de haber padecido o padece Cáncer de?</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_mama" label="MAMA" color="red"
                                                value="X" hide-details style="margin-right: 0;"></v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_prostata" label="PROSTATA"
                                                color="orange" value="X" hide-details style="margin-right: 0;"></v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_pulmon" label="PULMON" color="success"
                                                value="X" hide-details style="margin-right: 0;"></v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_gastrointestinales"
                                                label="GASTROINTESTINALES" color="GASTROINTESTINALES" value="X" hide-details
                                                style="margin-right: 0;">
                                            </v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_cervicouterino" label="CERVICOUTERINO"
                                                color="CERVICOUTERINO" value="X" hide-details style="margin-right: 0;">
                                            </v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_piel" label="PIEL" color="PIEL"
                                                value="X" hide-details style="margin-right: 0;">
                                            </v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_otros" label="OTROS" color="OTROS"
                                                value="X" hide-details style="margin-right: 0;">
                                            </v-checkbox>
                                        </v-flex>
                                    </v-layout>
                                </v-container>

                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">¿Usted
                                        tiene antecedentes de alguna de las siguientes enfermedades metabólicas?</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_obesidad" label="OBESIDAD" color="red"
                                                value="X" hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_tiroides"
                                                label="ENFERMEDADES DE TIROIDES" color="orange" value="X"
                                                hide-details></v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_mellitus" label="DIABETES MELLITUS"
                                                color="success" value="X" hide-details>
                                            </v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_dislipidemia"
                                                label="DISLIPIDEMIAS (ALTERACION DEL NIVEL DE COLESTEROL O TRIGLICERIDOS EN SANGRE)"
                                                color="info" value="X" hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">¿Usted
                                        tiene antecedentes de alguna de las siguientes enfermedades de Riesgo
                                        Cardiovascular?</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_cardiopatia"
                                                label="CARDIOPATÍAS ISQUÉMICAS (INFARTO AGUDO AL MIOCARDIO, ANGINA DE PECHO, ETC.)"
                                                color="red" value="X" hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_hipertension"
                                                label="HIPERTENSION ARTERIAL" color="orange" value="X" hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_cerebrovascular"
                                                label="ENFERMEDAD CEREBROVASCULAR" color="success" value="X"
                                                hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_arterial"
                                                label="ENFERMEDAD ARTERIAL OCLUSIVA CRONICA" color="info" value="X"
                                                hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_renal"
                                                label="ENFERMEDAD RENAL CRÓNICA" color="GASTROINTESTINALES" value="X"
                                                hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">¿Usted
                                        tiene antecedentes de alguna de las siguientes enfermedades respiratorias?</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_asma" label="ASMA" color="red"
                                                value="X" hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_epoc" label="EPOC" color="orange"
                                                value="X" hide-details></v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedente_bronquitis" label="BRONQUITIS CRONICA"
                                                color="success" value="X" hide-details>
                                            </v-checkbox>
                                            <v-checkbox v-model="paciente.antecedente_apnea"
                                                label="SÍNDROME DE APNEA E HIPOAPNEA DEL SUEÑO (SAHOS)" color="info"
                                                value="X" hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.antecedenterespiratoria_otros" label="OTROS"
                                                color="GASTROINTESTINALES" value="X" hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">¿Usted
                                        tiene condiciones o enfermedades que le causen inmunodeficiencia?</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.condiciones_vih" label="VIH" color="red" value="X"
                                                hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.condiciones_autoinmunes"
                                                label="ENFERMEDADES AUTOINMUNES (LUPUS, ARTRITIS REUMATOIDEA, ESCLEROSIS, ETC.)"
                                                color="orange" value="X" hide-details></v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.condiciones_biologicos"
                                                label="TRATAMIENTO CON BIOLÓGICOS" color="success" value="X"
                                                hide-details></v-checkbox>
                                            <v-checkbox v-model="paciente.condiciones_quimioterapia" label="QUIMIOTERAPIA"
                                                color="info" value="X" hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.condiciones_otros" label="OTROS"
                                                color="GASTROINTESTINALES" value="X" hide-details>
                                            </v-checkbox>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">¿Riesgos?
                                    </h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md12>
                                            <label for="">Medicamentos de uso permanente:</label><br>
                                            <v-textarea v-model="paciente.medicamentos" solo name="input-7-7"
                                                label="Medicamentos de uso permanente">
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs12 sm4 md12>
                                            <label for="">Antecedentes de hospitalización por patologías
                                                crónicas:</label><br>
                                            <v-textarea v-model="paciente.antecedente_hospitalizacion" solo name="input-7-7"
                                                label="Antecedentes de hospitalización por patologías crónicas">
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs12 sm4 md12>
                                            <label for="">Antecedentes personales patológicos</label><br>
                                            <v-textarea v-model="paciente.antecedente_patologico" solo name="input-7-7"
                                                label="Escriba sus antecedentes personales patológicos">
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs12 sm4 md12>
                                            <label for="">Clasificación del riesgo individualizado:</label><br>
                                            <v-textarea v-model="paciente.riesgo_individualizado" solo name="input-7-7"
                                                label="Clasificación del riesgo individualizado">
                                            </v-textarea>
                                        </v-flex>
                                    </v-layout>
                                </v-container>


                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">Grupo de
                                        riesgo
                                        priorizado</h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.alteraciones_nutricionales"
                                                label="Alteraciones Nutricionales" color="info" value="X"
                                                hide-details></v-checkbox>

                                            <v-checkbox v-model="paciente.enfermedades_infecciosas"
                                                label="Enfermedades infecciosas" color="red" value="X"
                                                hide-details></v-checkbox>

                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.trastorno_consumo_sustancias_psicoactivas"
                                                label="Trastorno por el consumo de sustancias psicoactivas" color="orange"
                                                value="X" hide-details></v-checkbox>

                                            <v-checkbox v-model="paciente.enfermedades_cardiovasculares"
                                                label="Enfermedad cardiovascular" color="purple" value="X"
                                                hide-details></v-checkbox>

                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.cancer" label="Cáncer" color="black" value="X"
                                                hide-details>
                                            </v-checkbox>

                                            <v-checkbox v-model="paciente.trastornos_visuales" label="Trastornos visuales"
                                                color="black" value="X" hide-details></v-checkbox>

                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.trastornos_auditivos" label="Trastornos auditivos"
                                                color="info" value="X" hide-details></v-checkbox>

                                            <v-checkbox v-model="paciente.trastornos_salud_bucal"
                                                label="Trastornos de salud bucal" color="orange" value="X"
                                                hide-details></v-checkbox>

                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.problemas_salud_mental"
                                                label="Problemas de salud mental" color="info" value="X"
                                                hide-details></v-checkbox>

                                            <v-checkbox v-model="paciente.zonóticas" label="Enfermedades zonóticas"
                                                color="red" value="X" hide-details></v-checkbox>

                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.accidente_enfermedad_laboral"
                                                label="Accidentes y enfermedades laborales" color="purple" value="X"
                                                hide-details></v-checkbox>

                                            <v-checkbox v-model="paciente.violencias" label="Violencias" color="purple"
                                                value="X" hide-details></v-checkbox>

                                        </v-flex>
                                        <v-flex xs12 sm4 md6>
                                            <v-checkbox v-model="paciente.trastornos_degenarativos_neuropatias_autoinmune"
                                                label="Trastornos degenarativos, neuropatia y enfermedades autoinmunes"
                                                color="info" value="X" hide-details></v-checkbox>

                                        </v-flex>
                                    </v-layout>
                                </v-container>

                                <v-container fluid>
                                    <h2 style="text-align: center; background: lightseagreen; color: white;">Ciclo vital
                                    </h2>
                                    <v-layout row wrap class="mt-1">
                                        <v-flex>
                                            <v-radio-group v-model="paciente.cicloVital">
                                                <v-radio label="Primera Infancia" color="blue" value="primera_infancia"
                                                    required></v-radio>
                                                <div v-if="llenarObligatorio" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                                <v-radio label="Infancia" color="blue" value="infancia" required></v-radio>
                                                <div v-if="llenarObligatorio" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                                <v-radio label="Juventud" color="blue" value="juventud" required></v-radio>
                                                <div v-if="llenarObligatorio" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                                <v-radio label="Adolescencia" color="blue" value="adolescencia"
                                                    required></v-radio>
                                                <div v-if="llenarObligatorio" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                                <v-radio label="Adultez" color="blue" value="Adultez" required></v-radio>
                                                <div v-if="llenarObligatorio" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                                <v-radio label="Vejez" color="blue" value="vejez" required></v-radio>
                                                <div v-if="llenarObligatorio" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                            </v-radio-group>
                                        </v-flex>

                                    </v-layout>
                                </v-container>

                            </v-layout>
                            <!-- fin formulario -->
                        </v-container>
                    </v-card-text>

                    <!-- Botón para verificar el estado del paciente -->
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="info" dark @click="estado = true" round>Estado del paciente</v-btn>
                        <v-btn v-if="!paciente.id_caraterizacion" color="success" dark @click="guardarRegistro()"
                            round>Guardar</v-btn>
                        <v-btn v-else color="warning" dark @click="editarRegistro()" round>Actualizar</v-btn>
                        <v-btn color="red" dark @click="cancelar()" round>Cancelar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <!-- v-dialog sobre el estado del paciente -->
            <v-dialog v-model="estado" width="1000" persistent>
                <v-card>
                    <v-card-title class="headline info lighten-2 justify-center" style="color: black" primary-title>
                        <b>Estado del paciente por ruta de atención</b>
                    </v-card-title>
                    <v-form @submit.prevent="guardarEstadoPaciente()">
                        <v-card-text style="color: black">
                            <v-container>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-menu v-model="menu1" :close-on-content-click="false" :nudge-right="40" lazy
                                            transition="fab-transition" offset-y full-width min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <VTextField v-model="startDate" label="Fecha de la actualización "
                                                    prepend-icon="event" readonly v-on="on" />
                                            </template>
                                            <VDatePicker color="primary" :max="finishDate" v-model="startDate"
                                                @input="menu1 = false" />
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs12 sm12 md12>
                                        <v-select :items="estado_Paciente" label="¿Cuál es el estado del paciente?"
                                            v-model="paciente.estado_Paciente">
                                        </v-select>
                                    </v-flex>
                                    <v-container fluid>
                                        <h2 style="text-align: center; background: lightseagreen; color: white;">¿Definición
                                            de
                                            rutas?
                                        </h2>
                                        <v-layout row wrap class="mt-1">
                                            <v-flex xs12 sm4 md6>
                                                <v-checkbox v-model="paciente.Maternoperinatal" label="Maternoperinatal"
                                                    color="orange" value="X" hide-details>
                                                </v-checkbox>
                                                <div v-if="camposObligatoriosFaltantes" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                                <v-checkbox v-model="paciente.salud_mental" label="Salud Mental"
                                                    color="blue" value="X" hide-details>
                                                </v-checkbox>
                                                <div v-if="camposObligatoriosFaltantes" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                            </v-flex>
                                            <v-flex xs12 sm4 md6>
                                                <v-checkbox v-model="paciente.riesgo_cardiovascular"
                                                    label="Riesgo Cardiovascular" color="info" value="X" hide-details>
                                                </v-checkbox>
                                                <div v-if="camposObligatoriosFaltantes" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                                <v-checkbox v-model="paciente.Oncologicos" label="Oncológicos" color="red"
                                                    value="X" hide-details>
                                                </v-checkbox>
                                                <div v-if="camposObligatoriosFaltantes" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                            </v-flex>
                                            <v-flex xs12 sm4 md6>
                                                <v-checkbox v-model="paciente.nefroproteccion"
                                                    label="Riesgo Nefroprotección" color="info" value="X" hide-details>
                                                </v-checkbox>
                                                <v-checkbox v-model="paciente.respiratorios_cronicos"
                                                    label="Respiratorios Crónicos" color="orange" value="X" hide-details>
                                                </v-checkbox>
                                                <div v-if="camposObligatoriosFaltantes" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                            </v-flex>
                                            <v-flex xs12 sm4 md6>
                                                <v-checkbox v-model="paciente.reumatologicos" label="Reumatológicos"
                                                    color="info" value="X" hide-details>
                                                </v-checkbox>
                                                <div v-if="camposObligatoriosFaltantes" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                                <v-checkbox v-model="paciente.trasmisibles_cronicos"
                                                    label="Trasmisibles Crónicos" color="purble" value="X" hide-details>
                                                </v-checkbox>
                                                <div v-if="camposObligatoriosFaltantes" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                            </v-flex>
                                            <v-flex xs12 sm4 md6>
                                                <v-checkbox v-model="paciente.rehabilitación_integral"
                                                    label="Rehabilitación Integral Funcional" color="info" value="X"
                                                    hide-details>
                                                </v-checkbox>
                                                <div v-if="camposObligatoriosFaltantes" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                                <v-checkbox v-model="paciente.cuidados_paliativos"
                                                    label="Cuidados Paliativos" color="orange" value="X" hide-details>
                                                </v-checkbox>
                                                <div v-if="camposObligatoriosFaltantes" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                            </v-flex>
                                            <v-flex xs12 sm4 md6>
                                                <v-checkbox v-model="paciente.enfermedades_huerfanas"
                                                    label="Enfermedades Huérfanas" color="info" value="X" hide-details>
                                                </v-checkbox>
                                                <div v-if="camposObligatoriosFaltantes" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                                <v-checkbox v-model="paciente.Economia_articular" label="Economía articular"
                                                    color="black" value="X" hide-details>
                                                </v-checkbox>
                                                <div v-if="camposObligatoriosFaltantes" style="color: red">¡Campos
                                                    obligatorios!
                                                </div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                    <v-layout>
                                        <v-spacer></v-spacer>
                                        <v-card-actions>
                                            <v-btn color="warning" dark @click="guardarEstadoPaciente()"
                                                round>Actualizar</v-btn>
                                            <v-btn color="red" dark @click="estado = false" round>Cerrar</v-btn>
                                        </v-card-actions>
                                    </v-layout>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-form>
                </v-card>
            </v-dialog>
            <!-- fin del v-dialog sobre el estado del paciente -->
        </v-container>
    </div>
</template>

<script>
import {
    mapGetters
} from "vuex";
import Swal from 'sweetalert2';
import moment from "moment";
import {
    EventBus
} from "../../../event-bus.js";

export default {
    components: {},
    data() {
        return {
            listaSolicitudes: [],
            dialog: false,
            file: '',
            zona: ['URBANA', 'RURAL'],
            miembros: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17',
                '18', '19', '20'
            ],
            sino: ['SI', 'NO'],
            vivienda: ['PROPIA', 'ARRENDADA', 'FAMILIAR', 'USURFRUCTO'],
            alimentos: ['GAS PIPETA', 'GAS NATURAL', 'ELÉCTRICA', 'LEÑA', 'COMBUSTIBLE'],
            tipoI: ['CC', 'TI', 'CE', 'RC', 'PASAPORTE'],
            accesibilidad: ['FÁCIL', 'DIFÍCIL'],
            calificacion: ['BUENA', 'REGULAR', 'MALA'],
            etnico: ['INDÍGENA', 'AFRODESCENDIENTE', 'RAIZAL', 'ROOM', 'AFROCOLOMBIANO(A)', 'SIN PERTINENCIA ÉTNICA'],
            escolaridad: ['ANALFABETA', 'PRIMARIA', 'BACHILLER(incluye normalista)', 'UNIVERSITARIO',
                'POSTGRADO(Especialización, Maestria etc)'],
            habitaciones: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
            orientacionsexual: ['HETEROSEXUAL', 'HOMOSEXUAL', 'BISEXUAL', 'PANSEXUAL', 'ASEXUAL',
                'NO DESEA CONTESTAR'
            ],
            planificacion: ['ORAL', 'DIU', 'IMPLANTES SUBDERMICOS', 'INYECTABLES MENSUALES',
                'INYECTABLES TRIMESTRALES', 'BARRERAS(preservativo, diafragma, etc)',
                'ANTICONCEPCION QUIRURGICA (tubectomia, vasectomia)', 'NO VIDA SEXUAL', 'NO PLANIFICA'
            ],
            estato: ['1', '2', '3', '4', '5', '6', 'NO SABE'],
            noaplica: ['SI', 'NO', 'NO APLICA'],
            tabaco: ['FUMADOR PASIVO', 'FUMADOR ACTIVO', 'EXFUMADOR', 'NO FUMADOR'],
            psicoactivas: ['DIARIO', 'SEMANAL', 'MENSUAL', 'OCASIONAL', 'NO APLICA'],
            genero: ['Femenino', 'Masculino', 'Intermedio'],
            tipoAfiliado: ['Cotizante', 'Beneficiario'],
            estadoCivil: ['Soltero', 'Casado', 'Viudo', 'Union Libre ', 'Separado'],
            escolaridad: ['Primaria', 'Bachiller', 'Tecnólogo', 'Tecnico', 'Universitario', 'Pos grado'],
            estrato: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
            Tipo_Doc: ['CC', 'CE', 'PA', 'RC', 'TI'],
            oficio: ['ESTUDIANTE', 'AMA DE CASA', 'PENSIONADO', 'DOCENTE', 'DESEMPLEADO',
                'EMPRESARIO', 'TRABAJADOR INDEPENDIENTE', 'EMPLEADO'],
            estado_Paciente: ['ESTABLE', 'CRITICO', 'FALLECIDO'],
            cedula_paciente: "",
            cedula: "",
            caracterizacion: "",
            menu1: "",
            paciente: {
                Telefono: "",
                Celular1: "",
                Celular2: "",
                Correo1: "",
                Correo2: "",
                Edad_Cumplida: "",
                Direccion_Residencia: "",
                estado_civil: "",
                Tipo_Doc: "",
                ut_saliente: '',
                tipo_identificacion: '',
                documento_identificacion: '',
                primer_nombre: '',
                segundo_nombre: '',
                primer_apellido: '',
                segundo_apellido: '',
                genero: '',
                fecha_nacimiento: '',
                edad: '',
                tipo_afiliacion: '',
                numero_hijos: '',
                nivel_escolaridad: '',
                estrato: '',
                Mpio_Residencia: '',
                Mpio_Atencion: '',
                departamento: '',
                departamento_id: '',
                direccion: '',
                telefono: '',
                celular: '',
                correo_electronico: '',
                fecha_solicitud: '',
                tipo_servicio: '',
                tutela: '',
                paciente_id: '',
                zona_vivienda: '',
                conforman_hogar: '',
                numero_miembros: '',
                parentesco: '',
                tipo_vivienda: '',
                hogar_con_agua: '',
                servicio_alcantarillado: '',
                alimentos: '',
                numero_habitaciones: '',
                vivienda_con_energia: '',
                accesibilidad_vivienda: '',
                seguridad_orden: '',
                ingresos_salariales: '',
                orientacion_sexual: '',
                oficio: '',
                docente_vinculacion_laboral: '',
                planificando_metodos: '',
                diagnósticos_salud_mental: '',
                citologia_ultimo_ano: '',
                tamizaje_Mamografia: '',
                planeado_embarazo: '',
                tamizaje_Prostata: '',
                autocuidado_salud: '',
                victima_violencia: '',
                victima_maltrato_fisico: '',
                victima_desplazamiento: '',
                exposicion_tabaco: '',
                tabacos_al_dia: '',
                anos_consume_tabaco: '',
                consume_sustancias_psicoactivas: '',
                Frecuencia_consumo_psicoactivas: '',
                consume_alcohol: '',
                Frecuencia_consumo_alcohol: '',
                cantidad_tragos_consume: '',
                actividad_fisica: '',
                familiares_padecen_cancer: '',
                familiares_enfermedades_metabolicas: '',
                familiares_riesgo_cardiovascular: '',
                antecedentes_padece_cancer: '',
                antecedentes_efermedades_metabolicas: '',
                antecedentes_riesgo_cardiovascular: '',
                antecedentes_enfermedades_respiratorias: '',
                antecedentes_enfermedades_autoinmunes: '',
                antecedentes_enfermedades_trasmisibles: '',
                antecedentes_padecido_covid: '',
                medicamentos: '',
                antecedente_hospitalizacion: '',
                antecedente_patologico: '',
                riesgo_individualizado: '',
                Maternoperinatal: '',
                salud_mental: '',
                riesgo_cardiovascular: '',
                Oncologicos: '',
                nefroproteccion: '',
                respiratorios_cronicos: '',
                reumatologicos: '',
                trasmisibles_cronicos: '',
                rehabilitación_integral: '',
                cuidados_paliativos: '',
                enfermedades_huerfanas: '',
                Economia_articular: '',
                alteraciones_nutricionales: '',
                enfermedades_infecciosas: '',
                trastorno_consumo_sustancias_psicoactivas: '',
                enfermedad_cardiovascular: '',
                cancer: '',
                trastornos_visuales: '',
                trastornos_auditivos: '',
                trastornos_salud_bucal: '',
                problemas_salud_mental: '',
                zonoticas: '',
                accidente_enfermedad_laboral: '',
                trastornos_degenarativos_neuropatias_autoinmune: '',
                violencias: '',
                estado_Paciente: '',
                Mpio_Afiliado: null,
                cicloVital: '',
                acceso_vivienda: '',
                Grado_Discapacidad: '',
            },
            camposObligatorios: ['Maternoperinatal', 'salud_mental', 'riesgo_cardiovascular', 'Oncologicos', 'Oncologicos', 'nefroproteccion', 'respiratorios_cronicos',
                'reumatologicos', 'trasmisibles_cronicos', 'rehabilitación_integral', 'cuidados_paliativos', 'cuidados_paliativos', 'enfermedades_huerfanas', 'Economia_articular'],
            obligatorios: ['primera_infancia', 'infancia', 'adolescencia', 'juventud', 'adultez', 'vejez'],
            camposObligatoriosFaltantes: false,
            llenarObligatorio: false,
            departamentos: [],
            municipios: [],
            estado: false,
            startDate: moment().format('YYYY-MM-DD'),
            finishDate: moment().format('YYYY-MM-DD'),
            estadoPaciente: [],
            headers: [{
                text: 'id',
                align: 'left',
                sortable: false,
                value: 'id'
            },
            {
                text: 'Cédula',
                align: 'left',
                value: 'Num_Doc'
            },
            {
                text: 'Nombre',
                align: 'left',
            },
            {
                text: 'Estado civil',
                align: 'left',
                value: 'carbs'
            },
            {
                text: 'Entidad',
                align: 'left',
                value: 'protein'
            },
            {
                text: 'Actions',
                align: 'left',
                value: 'name',
                sortable: false
            }
            ],

        };
    },
    computed: {
        ...mapGetters(['can']),
    },
    mounted() {
        // this.listarSolicitudes();
        this.fetchMunicipios();
        this.fetchDepartamentos();
    },
    methods: {
        search_paciente() {
            if (!this.cedula_paciente) {
                this.$alerError("Ingresa un número de documento");
                return;
            }
            axios.get(`/api/paciente/showCaracterizacion/${this.cedula_paciente}`)
                .then(res => {
                    if (res.data.paciente.id_caraterizacion) {
                        this.dialog = true;
                        this.paciente = res.data.paciente;
                        this.paciente.Mpio_Atencion = parseInt(res.data.paciente.Mpio_Atencion)
                    } else {
                        Swal.fire({
                            title: res.data.message,
                            icon: 'warning',
                            showCancelButton: true,
                            showActualizar: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Generar Caracterización!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.dialog = true;
                                this.paciente = res.data.paciente;
                            }
                        });
                    }

                })
                //mensaje de error en la pantalla al buscar un paciente que no está afiliado
                .catch(err => {
                    this.$alerError('El paciente no se encuentra registrado en nuestra base de datos');
                });
        },
        showMessage(message) {
            swal({
                title: `${message}`,
                icon: "warning",
            });
        },
        fetchDepartamentos() {
            axios.get("/api/domiciliaria/departamento")
                .then(res => {
                    this.departamentos = res.data;
                })
        },
        fetchMunicipios() {
            axios.get('/api/municipio/lista')
                .then(res => {
                    this.municipios = res.data
                })
        },
        guardarRegistro() {
            this.paciente.paciente_id = this.paciente.id
            axios.post('/api/caracterizacion/registro', this.paciente).then(res => {
                this.$alerSuccess(res.data.message);
                this.dialog = false;
                this.clearFields();
                this.clearRegistro();
            })
        },
        cancelar() {
            this.llenarObligatorio = false;
            this.dialog = false;
        },
        //Funcion para actualizar la caracterización individual
        editarRegistro() {
            this.paciente.paciente_id = this.paciente.id;

            if (this.paciente.cicloVital) { // Comprueba si se ha seleccionado alguna opción en el grupo de ciclo vital
                this.llenarObligatorio = false;
                axios.put(`/api/caracterizacion/update/${this.paciente.id_caraterizacion}`, this.paciente)
                    .then(res => {
                        this.$alerSuccess('Caracterización actualizada');
                        this.dialog = false;
                    })
            } else {
                this.$alerError('Campos obligatorios');
                this.llenarObligatorio = true;
            }
        },
        //Funcion del boton para actualizar y guardar el estado del paciente
        guardarEstadoPaciente() {
            this.paciente.paciente_id = this.paciente.id

            if (this.camposObligatorios.some(campo => this.paciente[campo])) {
                this.camposObligatoriosFaltantes = false;
                axios.put(`/api/caracterizacion/update/${this.paciente.id_caraterizacion}`, this.paciente)
                    .then(res => {
                        this.$alerSuccess('Estado actualizado con éxito');
                        this.estado = false;
                    });
            } else {
                this.$alerError('Campos obligatorios')
                this.camposObligatoriosFaltantes = true;
            }
        },
        clearFields() {
            this.cedula_paciente = "";
            for (const prop of Object.getOwnPropertyNames(this.paciente)) {
                this.paciente[prop] = null;
            }
        },
        clearRegistro() {
            this.cedula_paciente = "";
            for (const prop of Object.getOwnPropertyNames(this.registro)) {
                this.registro[prop] = null;
            }
        },
    }
};

</script>

<style lang="scss" scoped></style>




