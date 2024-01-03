<template>
    <v-layout row justify-center>
        <v-flex xs12>
            <v-card>
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs12 md12 lg12>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-data-table :search="search" :headers="headers" :items="listaSolicitudes">
                    <template v-slot:items="props">
                        <td>{{ props.item.id }}</td>
                        <td>{{ props.item.paciente }}</td>
                        <td>{{ props.item.Num_Doc }}</td>
                        <td>{{ props.item.created_at }}</td>
                        <td>{{ props.item.IPS }}</td>
                        <td>
                            <v-chip color="purple" text-color="white">{{ props.item.estado }}</v-chip>
                        </td>
                        <td>{{ props.item.user_crea }}</td>
                        <td>
                            <v-btn depressed small round color="success" @click="cargarDetalles(props.item.id)">Generar
                                Seguimiento</v-btn>
                        </td>
                        <td>
                            <v-btn round small color="info" @click="seguimientoCovi(props.item.id)">Ver seguimiento
                            </v-btn>
                        </td>

                    </template>
                </v-data-table>
                <v-dialog v-model="dialogDetalle" persistent max-width="1900px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Seguimiento del paciente</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <form @submit.prevent="guardarControl">
                                        <v-container>
                                            <v-layout row wrap>
                                                <v-flex xs12 sm4 md4>
                                                    <v-label><strong>Contacto Fallido </strong></v-label>
                                                    <v-radio-group v-model="formSeguimiento.contacto_fallido">
                                                        <v-radio v-for="n in afirmacion" :key="n.id"
                                                            :label="`${n.nombre}`" :value="n.id" color="info" required>
                                                        </v-radio>
                                                    </v-radio-group>
                                                </v-flex>

                                                <v-layout row wrap v-show="formSeguimiento.contacto_fallido == 1">
                                                    <v-flex xs12 sm8 md8>
                                                        <v-select :items="fallido_por" label="Tipo de Contacto Fallido"
                                                            v-model="formSeguimiento.fallido"></v-select>
                                                    </v-flex>
                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Toma de Muestra</strong></v-label>
                                                        <v-radio-group v-model="formSeguimiento.toma_muestra">
                                                            <v-radio v-for="n in afirmacion" :key="n.id"
                                                                :label="`${n.nombre}`" :value="n.id" color="info">
                                                            </v-radio>
                                                        </v-radio-group>
                                                    </v-flex>
                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Destino del Paciente.</strong></v-label>
                                                        <v-radio-group v-model="formSeguimiento.destino_paciente">
                                                            <v-radio v-for="n in destino" :key="n.id"
                                                                :label="`${n.nombre}`" :value="n.id" color="info">
                                                            </v-radio>
                                                        </v-radio-group>
                                                    </v-flex>
                                                    <v-layout row wrap v-show="formSeguimiento.toma_muestra == 1">
                                                        <v-flex xs12 sm4 md4>
                                                            <v-select :items="tipomuestra" label="Tipo de Muestra"
                                                                v-model="formSeguimiento.tipoMuestra"></v-select>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-menu v-model="menuFechaRealizacion"
                                                                :close-on-content-click="false" :nudge-right="40" lazy
                                                                transition="scale-transition" offset-y full-width
                                                                min-width="290px">
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        v-model="formSeguimiento.fecha_realizacion"
                                                                        label="Fecha Realización" prepend-icon="event"
                                                                        readonly v-on="on">
                                                                    </v-text-field>
                                                                </template>
                                                                <v-date-picker
                                                                    v-model="formSeguimiento.fecha_realizacion"
                                                                    @input="menuFechaRealizacion = false">
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </v-flex>

                                                        <v-flex xs12 sm4 md4>
                                                            <v-menu v-model="menuFechaResultado"
                                                                :close-on-content-click="false" :nudge-right="40" lazy
                                                                transition="scale-transition" offset-y full-width
                                                                min-width="290px">
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        v-model="formSeguimiento.fecha_resultado"
                                                                        label="Fecha Resultado" prepend-icon="event"
                                                                        readonly v-on="on">
                                                                    </v-text-field>
                                                                </template>
                                                                <v-date-picker v-model="formSeguimiento.fecha_resultado"
                                                                    @input="menuFechaResultado = false"></v-date-picker>
                                                            </v-menu>
                                                        </v-flex>

                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Resultado</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.resultado">
                                                                <v-radio key="negativo" label="negativo"
                                                                    value="negativo" color="info">
                                                                </v-radio>
                                                                <v-radio key="positivo" label="Positivo"
                                                                    value="positivo" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>

                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Quien toma la muestra</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.quien_toma_muestra">
                                                                <v-radio key="sumimedical" label="Sumimedical"
                                                                    value="sumimedical" color="info"></v-radio>
                                                                <v-radio key="ips_externa" label="IPS Externa"
                                                                    value="ips_externa" color="info"></v-radio>
                                                                <v-radio key="secretaria_salud"
                                                                    label="Secretaria de Salud" value="secretaria_salud"
                                                                    color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>

                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Quien procesa la muestra</strong></v-label>
                                                            <v-radio-group
                                                                v-model="formSeguimiento.quien_procesa_muestra">
                                                                <v-radio key="sumimedical" label="Sumimedical"
                                                                    value="sumimedical" color="info"></v-radio>
                                                                <v-radio key="ips_externa" label="IPS Externa"
                                                                    value="ips_externa" color="info"></v-radio>
                                                                <v-radio key="IPSU" label="IPSU" value="IPSU"
                                                                    color="info"></v-radio>
                                                                <v-radio key="secretaria_salud"
                                                                    label="Secretaria de Salud" value="secretaria_salud"
                                                                    color="info"></v-radio>
                                                            </v-radio-group>
                                                        </v-flex>

                                                        <v-flex xs12 sm12 md12>
                                                            <v-textarea v-model="formSeguimiento.resultado_muestra"
                                                                name="input-7-1" label=" Resultado de Muestra"
                                                                hint="Descripción Resultado de Muestra">
                                                            </v-textarea>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-layout>
                                                <v-layout row wrap v-show="formSeguimiento.contacto_fallido == 0">
                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Estado del Caso</strong></v-label>
                                                        <v-radio-group v-model="formSeguimiento.clasificacion_caso">
                                                            <v-radio key="probable" label="Probable" value="probable"
                                                                color="info">
                                                            </v-radio>
                                                            <v-radio key="descartado" label="Descartado"
                                                                value="descartado" color="info"></v-radio>
                                                            <v-radio key="confirmado" label="Confirmado"
                                                                value="confirmado" color="info"></v-radio>
                                                            <v-radio key="Recuperado" label="Recuperado"
                                                                value="Recuperado" color="info"></v-radio>
                                                            <v-radio key="Positivo Fallecido" label="Positivo Fallecido"
                                                                value="Positivo Fallecido" color="info">
                                                            </v-radio>
                                                        </v-radio-group>
                                                    </v-flex>
                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Caso</strong></v-label>
                                                        <v-radio-group v-model="formSeguimiento.caso">
                                                            <v-radio key="1" label="1" value="1" color="info">
                                                            </v-radio>
                                                            <v-radio key="2" label="2" value="2" color="info">
                                                            </v-radio>
                                                            <v-radio key="3,1" label="3,1" value="3,1" color="info">
                                                            </v-radio>
                                                            <v-radio key="3,2" label="3,2" value="3,2" color="info">
                                                            </v-radio>
                                                            <v-radio key="4" label="4" value="4" color="info">
                                                            </v-radio>
                                                            <v-radio key="5" label="5" value="5" color="info">
                                                            </v-radio>

                                                        </v-radio-group>
                                                    </v-flex>
                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Toma de Muestra</strong></v-label>
                                                        <v-radio-group v-model="formSeguimiento.toma_muestra">
                                                            <v-radio v-for="n in afirmacion" :key="n.id"
                                                                :label="`${n.nombre}`" :value="n.id" color="info">
                                                            </v-radio>
                                                        </v-radio-group>
                                                    </v-flex>
                                                    <v-layout row wrap v-show="formSeguimiento.toma_muestra == 1">
                                                        <v-flex xs12 sm4 md4>
                                                            <v-select :items="tipomuestra" label="Tipo de Muestra"
                                                                v-model="formSeguimiento.tipoMuestra"></v-select>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-menu v-model="menuFechaRealizacion1"
                                                                :close-on-content-click="false" :nudge-right="40" lazy
                                                                transition="scale-transition" offset-y full-width
                                                                min-width="290px">
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        v-model="formSeguimiento.fecha_realizacion"
                                                                        label="Fecha Realización" prepend-icon="event"
                                                                        readonly v-on="on">
                                                                    </v-text-field>
                                                                </template>
                                                                <v-date-picker
                                                                    v-model="formSeguimiento.fecha_realizacion"
                                                                    @input="menuFechaRealizacion1 = false">
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </v-flex>

                                                        <v-flex xs12 sm4 md4>
                                                            <v-menu v-model="menuFechaResultado1"
                                                                :close-on-content-click="false" :nudge-right="40" lazy
                                                                transition="scale-transition" offset-y full-width
                                                                min-width="290px">
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        v-model="formSeguimiento.fecha_resultado"
                                                                        label="Fecha Resultado" prepend-icon="event"
                                                                        readonly v-on="on">
                                                                    </v-text-field>
                                                                </template>
                                                                <v-date-picker v-model="formSeguimiento.fecha_resultado"
                                                                    @input="menuFechaResultado1 = false">
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </v-flex>

                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Resultado</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.resultado">
                                                                <v-radio key="negativo" label="negativo"
                                                                    value="negativo" color="info">
                                                                </v-radio>
                                                                <v-radio key="positivo" label="Positivo"
                                                                    value="positivo" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>

                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Quien toma la muestra</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.quien_toma_muestra">
                                                                <v-radio key="sumimedical" label="Sumimedical"
                                                                    value="sumimedical" color="info"></v-radio>
                                                                <v-radio key="ips_externa" label="IPS Externa"
                                                                    value="ips_externa" color="info"></v-radio>
                                                                <v-radio key="secretaria_salud"
                                                                    label="Secretaria de Salud" value="secretaria_salud"
                                                                    color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>

                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Quien procesa la muestra</strong></v-label>
                                                            <v-radio-group
                                                                v-model="formSeguimiento.quien_procesa_muestra">
                                                                <v-radio key="sumimedical" label="Sumimedical"
                                                                    value="sumimedical" color="info"></v-radio>
                                                                <v-radio key="ips_externa" label="IPS Externa"
                                                                    value="ips_externa" color="info"></v-radio>
                                                                <v-radio key="IPSU" label="IPSU" value="IPSU"
                                                                    color="info"></v-radio>
                                                                <v-radio key="secretaria_salud"
                                                                    label="Secretaria de Salud" value="secretaria_salud"
                                                                    color="info"></v-radio>
                                                            </v-radio-group>
                                                        </v-flex>

                                                        <v-flex xs12 sm12 md12>
                                                            <v-textarea v-model="formSeguimiento.resultado_muestra"
                                                                name="input-7-1" label=" Resultado de Muestra"
                                                                hint="Descripción Resultado de Muestra">
                                                            </v-textarea>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Requiere Hospitalización</strong>
                                                        </v-label>
                                                        <v-radio-group
                                                            v-model="formSeguimiento.requiere_hospitalizacion">
                                                            <v-radio v-for="n in afirmacion" :key="n.id"
                                                                :label="`${n.nombre}`" :value="n.id" color="info">
                                                            </v-radio>
                                                        </v-radio-group>
                                                    </v-flex>
                                                    <v-layout row wrap
                                                        v-show="formSeguimiento.requiere_hospitalizacion == 1">
                                                        <v-flex xs12 sm4 md4>
                                                            <v-menu v-model="menuFechaIngreso"
                                                                :close-on-content-click="false" :nudge-right="40" lazy
                                                                transition="scale-transition" offset-y full-width
                                                                min-width="290px">
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        v-model="formSeguimiento.fecha_ingreso"
                                                                        label="Fecha de Ingreso a Hospital/ Clínica/ Urgencias"
                                                                        prepend-icon="event" readonly v-on="on">
                                                                    </v-text-field>
                                                                </template>
                                                                <v-date-picker v-model="formSeguimiento.fecha_ingreso"
                                                                    @input="menuFechaIngreso = false"></v-date-picker>
                                                            </v-menu>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-text-field label="Nombre de la Institución"
                                                                v-model="formSeguimiento.nombre_institucion">
                                                            </v-text-field>
                                                        </v-flex>

                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Tipo de Hospitalización.</strong></v-label>
                                                            <v-radio-group
                                                                v-model="formSeguimiento.tipo_hospitalizacion">
                                                                <v-radio key="UCI" label="UCI" value="uci" color="info">
                                                                </v-radio>
                                                                <v-radio key="UCE" label="UCE" value="uce" color="info">
                                                                </v-radio>
                                                                <v-radio key="Hospitalizacion" label="Hospitalizacion"
                                                                    value="hospitalizacion" color="info">
                                                                </v-radio>
                                                                <v-radio key="Urgencias" label="Urgencias"
                                                                    value="urgencia" color="info"></v-radio>
                                                            </v-radio-group>
                                                        </v-flex>

                                                        <v-flex xs12 sm4 md4>
                                                            <v-menu v-model="menuFechaSalida"
                                                                :close-on-content-click="false" :nudge-right="40" lazy
                                                                transition="scale-transition" offset-y full-width
                                                                min-width="290px">
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field v-model="formSeguimiento.fecha_salida"
                                                                        label="Fecha de Salida" prepend-icon="event"
                                                                        readonly v-on="on"></v-text-field>
                                                                </template>
                                                                <v-date-picker v-model="formSeguimiento.fecha_salida"
                                                                    @input="menuFechaSalida = false"></v-date-picker>
                                                            </v-menu>
                                                        </v-flex>


                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Estado al Alta</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.estado_alta">
                                                                <v-radio key="Vivo" label="Vivo" value="vivo"
                                                                    color="info"></v-radio>
                                                                <v-radio key="Fallecido" label="Fallecido"
                                                                    value="fallecido" color="info"></v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Asintomático</strong></v-label>
                                                        <v-radio-group v-model="formSeguimiento.asintomatico">
                                                            <v-radio v-for="n in afirmacion" :key="n.id"
                                                                :label="`${n.nombre}`" :value="n.id" color="info">
                                                            </v-radio>
                                                        </v-radio-group>
                                                    </v-flex>
                                                    <v-layout row wrap v-show="formSeguimiento.asintomatico == 0">
                                                        <v-flex xs12 sm4 md4>
                                                            <v-menu v-model="menuFechaInicioSintomas"
                                                                :close-on-content-click="false" :nudge-right="40" lazy
                                                                transition="scale-transition" offset-y full-width
                                                                min-width="290px">
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        v-model="formSeguimiento.fecha_inicio_sintomas"
                                                                        label="Fecha de Inicio de los síntomas"
                                                                        prepend-icon="event" readonly v-on="on">
                                                                    </v-text-field>
                                                                </template>
                                                                <v-date-picker
                                                                    v-model="formSeguimiento.fecha_inicio_sintomas"
                                                                    @input="menuFechaInicioSintomas = false">
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Fiebre Cuantificada Mayor a 38 ºC.</strong>
                                                            </v-label>
                                                            <v-radio-group v-model="formSeguimiento.fiebre_mayor38">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Tos.</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.tos">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Odinofagia.</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.odinofagia">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Alteracion del Olfato.</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.alteracion_olfato">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Alteracion del Gusto.</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.alteracion_gusto">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Anorexia.</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.anorexia">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Cefalea.</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.cefalea">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Fatiga / Adinamia.</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.fatiga_adinamia">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Dificultad Respiratoria.</strong></v-label>
                                                            <v-radio-group
                                                                v-model="formSeguimiento.dificultad_espiratoria">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Somnolencia o deterioro de la
                                                                    conciencia.</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.somnolencia">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Expectoración o Hemoptisis.</strong>
                                                            </v-label>
                                                            <v-radio-group v-model="formSeguimiento.expectoracion">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Vómito intratable o diarrea con
                                                                    deshidratación.</strong></v-label>
                                                            <v-radio-group
                                                                v-model="formSeguimiento.vomito_diarrea_intratable">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Paciente Atendido Adomicilio</strong></v-label>
                                                        <v-radio-group v-model="formSeguimiento.paciente_adomicilio">
                                                            <v-radio v-for="n in afirmacion" :key="n.id"
                                                                :label="`${n.nombre}`" :value="n.id" color="info">
                                                            </v-radio>
                                                        </v-radio-group>
                                                    </v-flex>
                                                    <v-layout row wrap
                                                        v-show="formSeguimiento.paciente_adomicilio == 1">
                                                        <v-flex xs12 sm4 md4>
                                                            <v-text-field label="Frecuencia Respiratoria por minuto."
                                                                v-model="formSeguimiento.frecuencia_respiratoria">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-text-field label="Saturación de Oxígeno."
                                                                v-model="formSeguimiento.saturacion_oxigeno">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <!-- <v-flex xs12 sm4 md4>
                                                            <v-text-field label="Presión Arterial Sistólica." v-model="formSeguimiento.presion_sistolica">
                                                            </v-text-field>
                                                        </v-flex> -->
                                                        <v-flex xs12 sm4 md4>
                                                            <v-text-field label="Pulso por minuto."
                                                                v-model="formSeguimiento.pulso_minuto">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-text-field label="Temperatura."
                                                                v-model="formSeguimiento.temperatura">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Paciente Requiere Oxigenoterapia</strong>
                                                            </v-label>
                                                            <v-radio-group
                                                                v-model="formSeguimiento.requiere_oxigenoterapia">
                                                                <v-radio v-for="n in afirmacion" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-layout row wrap
                                                            v-show="formSeguimiento.requiere_oxigenoterapia == 1">
                                                            <v-flex xs12 sm4 md4>
                                                                <v-label><strong>Cuál es el flujo de oxígeno
                                                                        indicado</strong></v-label>
                                                                <v-radio-group
                                                                    v-model="formSeguimiento.oxigeno_indicado">
                                                                    <v-radio v-for="n in indicado" :key="n.id"
                                                                        :label="`${n.nombre}`" :value="n.id"
                                                                        color="info">
                                                                    </v-radio>
                                                                </v-radio-group>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-layout>
                                                    <v-flex xs12 sm4 md4>
                                                        <v-label><strong>Destino del Paciente.</strong></v-label>
                                                        <v-radio-group v-model="formSeguimiento.destino_paciente">
                                                            <v-radio v-for="n in destino" :key="n.id"
                                                                :label="`${n.nombre}`" :value="n.id" color="info">
                                                            </v-radio>
                                                        </v-radio-group>
                                                    </v-flex>
                                                    <v-layout row wrap
                                                        v-show="formSeguimiento.destino_paciente == 'Estrategia de Oximetrías'">
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Frecuencias</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.frecuencia">
                                                                <v-radio v-for="n in frecuencia" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-layout row wrap
                                                        v-show="formSeguimiento.destino_paciente == 'Seguimiento'">
                                                        <v-flex xs12 sm4 md4>
                                                            <v-label><strong>Frecuencias</strong></v-label>
                                                            <v-radio-group v-model="formSeguimiento.frecuencia">
                                                                <v-radio v-for="n in frecuencia2" :key="n.id"
                                                                    :label="`${n.nombre}`" :value="n.id" color="info">
                                                                </v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-layout>
                                                <v-flex xs12 sm4 md4>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn color="red" dark @click="dialogDetalle = false">Cerrar
                                                        </v-btn>
                                                        <v-btn color="success" dark type="submit">Guardar</v-btn>
                                                    </v-card-actions>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </form>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogverCovi" persistent max-width="1600px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Seguimientos del paciente</span>
                        </v-card-title>
                        <v-card-text>
                            <v-layout>
                                <v-expansion-panel>
                                    <v-expansion-panel-content v-for="(item, index) in covisuguimientos" :key="index">
                                        <template v-slot:header>
                                            <span><b># Seguimiento:</b> {{item.id}}</span>
                                            <span><b>Realizado por:</b> {{item.user_crea}}</span>
                                            <span><b>fecha seguimiento:</b> {{item.created_at}}</span>
                                        </template>
                                        <v-card>
                                            <v-layout row wrap>
                                                <v-flex xs12 md2 v-if="item.contacto_fallido != null">
                                                    <v-text-field class="px-2" v-if="item.contacto_fallido == '0'"
                                                        value="Atendido" label="contacto:" readonly>
                                                    </v-text-field>
                                                    <v-text-field class="px-2" v-if="item.contacto_fallido == '1'"
                                                        value="No contesta" label="contacto:" readonly>
                                                    </v-text-field>
                                                    <v-text-field class="px-2" v-if="item.contacto_fallido == '2'"
                                                        value="Teléfono errado" label="contacto:" readonly>
                                                    </v-text-field>
                                                    <v-text-field class="px-2" v-if="item.contacto_fallido == '3'"
                                                        value="No se encuentra en residencia" label="contacto:"
                                                        readonly>
                                                    </v-text-field>
                                                    <v-text-field class="px-2" v-if="item.contacto_fallido == '4'"
                                                        value="Otros" label="contacto:" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.clasificacion_caso != null">
                                                    <v-text-field class="px-2" v-model="item.clasificacion_caso"
                                                        label="clasificacion_caso:" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.caso != null">
                                                    <v-text-field class="px-2" v-model="item.caso" label="caso:"
                                                        readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.toma_muestra != null">
                                                    <v-text-field class="px-2" v-if="item.toma_muestra == '0'"
                                                        value="No" label="Toma muestra:" readonly>
                                                    </v-text-field>
                                                    <v-text-field class="px-2" v-if="item.toma_muestra == '1'"
                                                        value="Si" label="Toma muestra:" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fecha_realizacion != null">
                                                    <v-text-field class="px-2" v-model="item.fecha_realizacion"
                                                        label="fecha_realizacion:" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fecha_resultado != null">
                                                    <v-text-field class="px-2" v-model="item.fecha_resultado"
                                                        label="fecha_resultado" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fecha_inicio_sintomas != null">
                                                    <v-text-field class="px-2" v-model="item.fecha_inicio_sintomas"
                                                        label="inicio sintomas" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.resultado != null">
                                                    <v-text-field class="px-2" v-model="item.resultado"
                                                        label="resultado" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.quien_toma_muestra != null">
                                                    <v-text-field class="px-2" v-model="item.quien_toma_muestra"
                                                        label="quien_toma_muestra" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.quien_procesa_muestra != null">
                                                    <v-text-field class="px-2" v-model="item.quien_procesa_muestra"
                                                        label="quien_procesa_muestra" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.resultado_muestra != null">
                                                    <v-text-field class="px-2" v-model="item.resultado_muestra"
                                                        label="resultado_muestra" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.requiere_hospitalizacion != null">
                                                    <v-text-field class="px-2" v-model="item.requiere_hospitalizacion"
                                                        label="requiere_hospitalizacion" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fecha_ingreso != null">
                                                    <v-text-field class="px-2" v-model="item.fecha_ingreso"
                                                        label="fecha_ingreso" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.nombre_institucion != null">
                                                    <v-text-field class="px-2" v-model="item.nombre_institucion"
                                                        label="nombre_institucion" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.tipo_hospitalizacion != null">
                                                    <v-text-field class="px-2" v-model="item.tipo_hospitalizacion"
                                                        label="tipo_hospitalizacion" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fecha_salida != null">
                                                    <v-text-field class="px-2" v-model="item.fecha_salida"
                                                        label="fecha_salida" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.estado_alta != null">
                                                    <v-text-field class="px-2" v-model="item.estado_alta"
                                                        label="estado_alta" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.asintomatico != null">
                                                    <v-text-field class="px-2" v-model="item.asintomatico"
                                                        label="asintomatico" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fiebre_mayor38 != null">
                                                    <v-text-field class="px-2" v-model="item.fiebre_mayor38"
                                                        label="fiebre_mayor38" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.tos != null">
                                                    <v-text-field class="px-2" v-model="item.tos" label="tos" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.odinofagia != null">
                                                    <v-text-field class="px-2" v-model="item.odinofagia"
                                                        label="odinofagia" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.alteracion_olfato != null">
                                                    <v-text-field class="px-2" v-model="item.alteracion_olfato"
                                                        label="alteracion_olfato" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.alteracion_gusto != null">
                                                    <v-text-field class="px-2" v-model="item.alteracion_gusto"
                                                        label="alteracion_gusto" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.anorexia != null">
                                                    <v-text-field class="px-2" v-model="item.anorexia" label="anorexia"
                                                        readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.cefalea != null">
                                                    <v-text-field class="px-2" v-model="item.cefalea" label="cefalea"
                                                        readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.fatiga_adinamia != null">
                                                    <v-text-field class="px-2" v-model="item.fatiga_adinamia"
                                                        label="fatiga_adinamia" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.dificultad_espiratoria != null">
                                                    <v-text-field class="px-2" v-model="item.dificultad_espiratoria"
                                                        label="dificultad_espiratoria" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.somnolencia != null">
                                                    <v-text-field class="px-2" v-model="item.somnolencia"
                                                        label="somnolencia" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.expectoracion != null">
                                                    <v-text-field class="px-2" v-model="item.expectoracion"
                                                        label="expectoracion" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.vomito_diarrea_intratable != null">
                                                    <v-text-field class="px-2" v-model="item.vomito_diarrea_intratable"
                                                        label="vomito_diarrea_intratable" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.paciente_adomicilio != null">
                                                    <v-text-field class="px-2" v-model="item.paciente_adomicilio"
                                                        label="paciente_adomicilio" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.frecuencia_respiratoria != null">
                                                    <v-text-field class="px-2" v-model="item.frecuencia_respiratoria"
                                                        label="frecuencia_respiratoria" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.saturacion_oxigeno != null">
                                                    <v-text-field class="px-2" v-model="item.saturacion_oxigeno"
                                                        label="saturacion_oxigeno" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.presion_sistolica != null">
                                                    <v-text-field class="px-2" v-model="item.presion_sistolica"
                                                        label="presion_sistolica" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.pulso_minuto != null">
                                                    <v-text-field class="px-2" v-model="item.pulso_minuto"
                                                        label="pulso_minuto" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.temperatura != null">
                                                    <v-text-field class="px-2" v-model="item.temperatura"
                                                        label="temperatura" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.requiere_oxigenoterapia != null">
                                                    <v-text-field class="px-2" v-model="item.requiere_oxigenoterapia"
                                                        label="requiere_oxigenoterapia" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.oxigeno_indicado != null">
                                                    <v-text-field class="px-2" v-model="item.oxigeno_indicado"
                                                        label="oxigeno_indicado" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.destino_paciente != null">
                                                    <v-text-field class="px-2" v-model="item.destino_paciente"
                                                        label="destino_paciente" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.suguimiento != null">
                                                    <v-text-field class="px-2" v-model="item.suguimiento"
                                                        label="suguimiento" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.frecuencia != null">
                                                    <v-text-field class="px-2" v-model="item.frecuencia"
                                                        label="frecuencia" readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md2 v-if="item.tipoMuestra != null">
                                                    <v-text-field class="px-2" v-model="item.tipoMuestra"
                                                        label="tipoMuestra" readonly></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-card>
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-layout>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="dialogverCovi = false">Close</v-btn>
                            <v-btn color="blue darken-1" flat @click="dialogverCovi = false">Save</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-card>
        </v-flex>
    </v-layout>
</template>
<script>
    import {
        mapGetters
    } from "vuex";
    export default {
        name: "segumientoCovid",
        data() {
            return {
                fallido_por: ['No contesta', 'Teléfono errado', 'No se encuentra en residencia', 'Otros'],
                tipomuestra: ['PCR', 'Antígeno', 'Serológicas'],
                search: "",
                dialogDetalle: false,
                dialogverCovi: false,
                covisuguimientos: [],
                listaSolicitudes: [],
                menuFechaRealizacion: false,
                menuFechaRealizacion1: false,
                menuFechaResultado: false,
                menuFechaResultado1: false,
                menuFechaIngreso: false,
                menuFechaSalida: false,
                menuFechaInicioSintomas: false,
                idSolicitud: null,
                afirmacion: [{
                    id: 1,
                    nombre: 'SI'
                }, {
                    id: 0,
                    nombre: 'NO'
                }],
                indicado: [{
                    id: '2 lt/min',
                    nombre: '2 lt/min'
                }, {
                    id: '3 lt/min',
                    nombre: '3 lt/min'
                }, {
                    id: '4 lt/min',
                    nombre: '4 lt/min'
                }, {
                    id: '5 lt/min',
                    nombre: '5 lt/min'
                }],
                destino: [{
                    id: 'Seguimiento',
                    nombre: 'Seguimiento'
                }, {
                    id: 'Estrategia de Oximetrías',
                    nombre: 'Estrategia de Oximetrías'
                }, {
                    id: 'Alta del Modelo de Atención Covid',
                    nombre: 'Alta del Modelo de Atención Covid'
                }],
                frecuencia: [{
                        id: 'Cada 4 horas',
                        nombre: 'Cada 4 horas'
                    },
                    {
                        id: 'Cada 2 horas',
                        nombre: 'Cada 2 horas'
                    },
                    {
                        id: 'Cada 1 horas',
                        nombre: 'Cada 1 horas'
                    }
                ],
                frecuencia2: [{
                    id: 'Cada 24 horas',
                    nombre: 'Cada 24 horas'
                }],
                poblacion_riesgo: [],
                formSeguimiento: {
                    detalle_atencion_contingencia_id: null,
                    contacto_fallido: null,
                    clasificacion_caso: null,
                    caso: null,
                    toma_muestra: null,
                    fecha_realizacion: null,
                    fecha_resultado: null,
                    resultado: null,
                    fecha_inicio_sintomas: null,
                    quien_toma_muestra: null,
                    quien_procesa_muestra: null,
                    resultado_muestra: null,
                    requiere_hospitalizacion: null,
                    asintomatico: null,
                    fiebre_mayor38: null,
                    tos: null,
                    odinofagia: null,
                    alteracion_olfato: null,
                    alteracion_gusto: null,
                    anorexia: null,
                    cefalea: null,
                    fatiga_adinamia: null,
                    dificultad_espiratoria: null,
                    somnolencia: null,
                    expectoracion: null,
                    vomito_diarrea_intratable: null,
                    paciente_adomicilio: null,
                    frecuencia_respiratoria: null,
                    saturacion_oxigeno: null,
                    presion_sistolica: null,
                    pulso_minuto: null,
                    temperatura: null,
                    requiere_oxigenoterapia: null,
                    oxigeno_indicado: null,
                    destino_paciente: null,
                    frecuencia: null,
                    tipoMuestra: null,
                    fallido: null,
                    estado_id: 1
                },
                headers: [{
                        text: "# Solicitud",
                        value: "id"
                    },
                    {
                        text: "Paciente",
                        value: "paciente"
                    },
                    {
                        text: "Cédula",
                        value: "Num_Doc"
                    },
                    {
                        text: "Fecha Creacion",
                        value: "Fecha"
                    },
                    {
                        text: "IPS",
                        value: "IPS"
                    },
                    {
                        text: "Estado",
                        value: "estado"
                    },
                    {
                        text: "Usuario Crea",
                        value: "user_crea"
                    }, {
                        text: "Seguimiento",
                        value: ""
                    }, {
                        text: "Ingresados",
                        value: ""
                    }
                ],
            }
        },
        computed: {
            ...mapGetters(['can']),
        },
        methods: {
            seguimientoCovi(id) {
                axios.get('/api/seguimiento/showseguimientos/' + id).then(res => {
                    this.dialogverCovi = true;
                    this.covisuguimientos = res.data;
                })
            },
            listarSolicitudes() {
                axios.get('/api/seguimiento/seguimiento').then(res => {
                    this.listaSolicitudes = res.data;
                })
            },
            cargarDetalles(id) {
                this.formSeguimiento.detalle_atencion_contingencia_id = id;
                this.formSeguimiento.estado_id = 1;
                this.dialogDetalle = true;
            },
            guardarControl() {
                axios.post('/api/seguimiento/control', this.formSeguimiento).then(res => {
                    if (res.status === 200) {
                        this.clearData();
                        this.$alerSuccess(res.data.message);
                        this.listarSolicitudes();
                    }
                })
            },
            clearData() {
                this.poblacion_riesgo = [];
                for (const prop of Object.getOwnPropertyNames(this.formSeguimiento)) {
                    this.formSeguimiento[prop] = null;
                }
                this.dialogDetalle = false;
            }
        },
        mounted() {
            this.listarSolicitudes();
        }
    }

</script>
