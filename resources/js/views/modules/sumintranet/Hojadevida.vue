<template>
    <v-container fluid pa-1>
        <v-layout row>
            <v-flex xs12>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Registro y administración hojas de
                        vida</span>
                </v-card-title>
                <v-divider></v-divider>

            </v-flex>
        </v-layout>
        <v-container fluid pa-0>
            <v-tabs centered>
                <v-tabs-slider outline fab color="teal"></v-tabs-slider>
                <v-tab href="#tab-1" color="black--text">
                    Registrar Hoja de vida
                    <v-icon right color="warning">mdi-account-card-details</v-icon>
                </v-tab>
                <v-divider class="mx-2" inset vertical></v-divider>
                <v-tab href="#tab-2">
                    Consultar hoja de vida
                    <v-icon right color="warning">mdi-account-search</v-icon>
                </v-tab>
                <v-tab-item :value="'tab-1'">
                    <!-- <hr color="warning" > -->
                    <v-container fluid pa-0>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-card>
                                    <v-container fluid pa-0>
                                        <v-layout>
                                            <v-flex xs12 md2>

                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs12>
                                                <v-stepper v-model="e6" vertical>
                                                    <v-stepper-step :complete="e6 > 1" step="1" @click="e6 = 1">
                                                        Datos Personales
                                                    </v-stepper-step>

                                                    <v-stepper-content step="1">
                                                        <v-form v-model="valid">
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <img :src="imageUrl" height="101px"
                                                                            width="auto">
                                                                        <v-btn raised color="warning"
                                                                            @click="onPickFile">Cargar foto ...
                                                                        </v-btn>
                                                                        <input type="file" name="" id=""
                                                                            style="display: none" ref="fileInput"
                                                                            accept="image/*" @change="onFilePicked">
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="tipoDoc"
                                                                            v-model="datosPersonales.tipo_documento"
                                                                            :rules="nameRules" label="Tipo documento"
                                                                            required>
                                                                        </v-select>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.Documento"
                                                                            :rules="nameRules" type="number" min="0"
                                                                            label="Número Documento" required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.primer_nombre"
                                                                            :rules="nameRules" label="Primer Nombre"
                                                                            required></v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.segundo_nombre"
                                                                            :rules="nameRules" :counter="10"
                                                                            label="Segundo Nombre" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.primer_apellido"
                                                                            :rules="nameRules" :counter="10"
                                                                            label="Primer Apellido" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.segundo_apellido"
                                                                            :rules="nameRules" :counter="10"
                                                                            label="Segundo Apellido" required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.fecha_nacimiento"
                                                                            :rules="nameRules" :counter="10"
                                                                            label="Fecha Nacimiento" type="date"
                                                                            required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-autocomplete :items="muni" item-value="id"
                                                                            item-text="Nombre"
                                                                            v-model="datosPersonales.lugar_nacimiento"
                                                                            :rules="nameRules" label="Lugar Nacimiento"
                                                                            required>
                                                                        </v-autocomplete>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field v-model="datosPersonales.edad"
                                                                            :rules="nameRules" type="number" min="0"
                                                                            label="Edad" required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-autocomplete :items="muni" item-value="id"
                                                                            item-text="Nombre"
                                                                            v-model="datosPersonales.lugar_exp_documento"
                                                                            :rules="nameRules" label="Lugar Nacimiento"
                                                                            required>
                                                                        </v-autocomplete>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.fecha_exp_documento"
                                                                            :rules="nameRules" type="date"
                                                                            label="Fecha expedición Cédula" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="sexo"
                                                                            v-model="datosPersonales.genero"
                                                                            label="Género" :rules="nameRules" required>
                                                                        </v-select>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="RH" :rules="nameRules"
                                                                            v-model="datosPersonales.grupo_sanguineo"
                                                                            label="Grupo Sanguíneo"></v-select>

                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="poblaEspecial"
                                                                            v-model="datosPersonales.poblacion_especial"
                                                                            label="Grupo Población Especial"
                                                                            :rules="nameRules" required></v-select>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="etnias"
                                                                            v-model="datosPersonales.etnia"
                                                                            :rules="nameRules" label="Etnia" required>
                                                                        </v-select>

                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.discapacidad"
                                                                            :rules="nameRules" :counter="10"
                                                                            label="Discapacidad" required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="tipoDiscapacidades"
                                                                            v-model="datosPersonales.tipo_discapacidad"
                                                                            label="Tipo de Discapacidad" required>
                                                                        </v-select>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.cabeza_familia"
                                                                            :rules="nameRules" :counter="10"
                                                                            label="Madre | padre Cabeza familia"
                                                                            required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="estadoCivil"
                                                                            v-model="datosPersonales.estado_civil"
                                                                            label="Estado Civil" :rules="nameRules"
                                                                            required></v-select>

                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field v-model="datosPersonales.estatura"
                                                                            :rules="nameRules" label="Estatura (Cm)"
                                                                            type="number" min="0" required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field v-model="datosPersonales.peso"
                                                                            :rules="nameRules" label="Peso (Kg)"
                                                                            type="number" min="0" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-btn color="success" @click="guardarDatosPersonales()">
                                                                Guardar Continue
                                                            </v-btn>
                                                            <v-btn flat>Cancel</v-btn>
                                                        </v-form>
                                                    </v-stepper-content>

                                                    <v-stepper-step :complete="e6 > 2" step="2" @click="e6 = 2">
                                                        Información de Contacto
                                                    </v-stepper-step>
                                                    <v-stepper-content step="2">
                                                        <v-form v-model="valid">
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field v-model="datosPersonales.telefono"
                                                                            :rules="nameRules" type="number" min="0"
                                                                            label="telefono" required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field v-model="datosPersonales.celular"
                                                                            :rules="nameRules" type="number"
                                                                            maxlength="10"
                                                                            oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                                            min="1" label="celular 1" required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field v-model="datosPersonales.celular2"
                                                                            :rules="nameRules" type="number" min="0"
                                                                            label="celular 2" required>>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.correo_corporativo"
                                                                            :rules="nameRules" :counter="10"
                                                                            label="Correo corporativo" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field v-model="datosPersonales.correo"
                                                                            :rules="nameRules" :counter="10"
                                                                            label="Correo personal" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.direccion_residencia"
                                                                            :rules="nameRules"
                                                                            label="Direccion residencia" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field v-model="datosPersonales.barrio"
                                                                            :rules="nameRules" label="Barrio" required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-autocomplete :items="vivienda"
                                                                            v-model="datosPersonales.tipo_vivienda"
                                                                            :rules="nameRules" label="Tipo vivienda"
                                                                            required>
                                                                        </v-autocomplete>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-autocomplete :items="estrato"
                                                                            v-model="datosPersonales.estrato"
                                                                            :rules="nameRules" label="Estrato" required>
                                                                        </v-autocomplete>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-autocomplete :items="muni" item-value="id"
                                                                            item-text="Nombre"
                                                                            v-model="datosPersonales.municipio_id"
                                                                            :rules="nameRules" label="Municipio"
                                                                            required>
                                                                        </v-autocomplete>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.contacto_emergencia"
                                                                            :rules="nameRules"
                                                                            label="Contacto emergencia" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.tel_contacto_emergencia"
                                                                            :rules="nameRules"
                                                                            label="Tel contacto emergencia" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.parentesco_contacto"
                                                                            :rules="nameRules" :counter="10"
                                                                            label="Parentesco contacto" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-btn color="success" @click="guardarDatosPersonales()">
                                                                Guardar Continue
                                                            </v-btn>
                                                            <v-btn flat>Cancel</v-btn>
                                                        </v-form>
                                                    </v-stepper-content>

                                                    <v-stepper-step :complete="e6 > 3" step="3" @click="e6 = 3">
                                                        Informacion Familiar
                                                    </v-stepper-step>
                                                    <v-stepper-content step="3">
                                                        <v-form v-model="valid">
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-autocomplete :items="muni" item-value="id"
                                                                            item-text="Nombre"
                                                                            v-model="datosPersonales.familiar_id"
                                                                            :rules="nameRules" label="Parentesco"
                                                                            required>
                                                                        </v-autocomplete>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="tipoDoc"
                                                                            v-model="datosPersonales.tipo_documento"
                                                                            :rules="nameRules" label="Tipo documento"
                                                                            required>
                                                                        </v-select>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.Documento"
                                                                            :rules="nameRules" type="number" min="0"
                                                                            label="Número Documento" required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field v-model="datosPersonales.nombre"
                                                                            :rules="nameRules" label="Nombre y apellido"
                                                                            required></v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.fecha_nacimiento"
                                                                            :rules="nameRules" :counter="10"
                                                                            label="Fecha Nacimiento" type="date"
                                                                            required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field v-model="datosPersonales.edad"
                                                                            :rules="nameRules" type="number" min="0"
                                                                            label="Edad" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="sexo"
                                                                            v-model="datosPersonales.genero"
                                                                            label="Género" :rules="nameRules" required>
                                                                        </v-select>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.escolaridad"
                                                                            :rules="nameRules" label="Escolaridad"
                                                                            required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="sexo"
                                                                            v-model="datosPersonales.profesion"
                                                                            label="Profesion" :rules="nameRules"
                                                                            required>
                                                                        </v-select>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="RH" :rules="nameRules"
                                                                            v-model="datosPersonales.depende_empleado"
                                                                            label="Depende empleado"></v-select>

                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="poblaEspecial"
                                                                            v-model="datosPersonales.vivecon_el_empleado"
                                                                            label="Vivecon el empleado"
                                                                            :rules="nameRules" required></v-select>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <input type="file" name="" id=""
                                                                            label="Adjunto documento">
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-btn color="success" @click="guardarDatosPersonales()">
                                                                Guardar Continue
                                                            </v-btn>
                                                            <v-btn flat>Cancel</v-btn>
                                                        </v-form>
                                                    </v-stepper-content>

                                                    <v-stepper-step :complete="e6 > 4" step="4" @click="e6 = 4">
                                                        Formación (Estudios realizados)
                                                    </v-stepper-step>
                                                    <v-stepper-content step="4">
                                                        <v-form v-model="valid">
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-autocomplete :items="muni" item-value="id"
                                                                            item-text="Nombre"
                                                                            v-model="datosPersonales.profesion_id"
                                                                            :rules="nameRules" label="profesion_id"
                                                                            required>
                                                                        </v-autocomplete>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="tipoDoc"
                                                                            v-model="datosPersonales.numero_acta"
                                                                            :rules="nameRules" label="numero_acta"
                                                                            required>
                                                                        </v-select>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.fecha_grado"
                                                                            :rules="nameRules" type="number" min="0"
                                                                            label="fecha_grado" required>
                                                                        </v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.institucion"
                                                                            :rules="nameRules" label="institucion"
                                                                            required></v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-container>
                                                                <v-layout>
                                                                    <v-flex xs12 md4>
                                                                        <v-autocomplete :items="muni" item-value="id"
                                                                            item-text="Nombre"
                                                                            v-model="datosPersonales.lugar_nacimiento"
                                                                            :rules="nameRules" label="Ciudad" required>
                                                                        </v-autocomplete>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.nivel_estudio"
                                                                            :rules="nameRules" type="number" min="0"
                                                                            label="Nivel estudio" required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4>
                                                                        <v-select :items="sexo"
                                                                            v-model="datosPersonales.fecha_titulo"
                                                                            label="Fecha titulo" :rules="nameRules"
                                                                            required>
                                                                        </v-select>
                                                                    </v-flex>

                                                                    <v-flex xs12 md4>
                                                                        <v-text-field
                                                                            v-model="datosPersonales.escolaridad"
                                                                            :rules="nameRules" label="Adjunto titulo"
                                                                            required>
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                            <v-btn color="success" @click="guardarDatosPersonales()">
                                                                Guardar Continue
                                                            </v-btn>
                                                            <v-btn flat>Cancel</v-btn>
                                                        </v-form>
                                                    </v-stepper-content>

                                                    <v-stepper-step step="5" @click="e6 = 5">Historia Laboral
                                                    </v-stepper-step>
                                                    <v-stepper-content step="5">
                                                        <!-- campos -->
                                                    </v-stepper-content>
                                                </v-stepper>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-tab-item>
                <v-tab-item :value="'tab-2'">
                    <v-card flat>
                        <v-card>
                            <v-card-title>
                                <v-layout row wrap>
                                    <span>
                                        Prioridad:
                                    </span>
                                </v-layout>
                            </v-card-title>
                        </v-card>
                    </v-card>
                </v-tab-item>
            </v-tabs>
        </v-container>
    </v-container>

</template>

<script>
    import {
        mapGetters
    } from "vuex";
    import Swal from "sweetalert2";
    import moment from "moment";
    export default {
        name: "hojadevida",
        data() {
            return {
                estrato: ["1", "2", "3", "4", "5", "6", "7", "8"],
                vivienda: ["Rural", "Urbana"],
                tipoDiscapacidades: [
                    "Discapacidad física",
                    "Discapacidad sensorial",
                    "Discapacidad intelectual",
                    "Discapacidad psíquica",
                    "Discapacidad visceral",
                    "Discapacidad múltiple",
                    "Ninguna",
                ],
                estadoCivil: ["Soltero", "Casado", "Union Libre", "Separado"],
                etnias: ["Mestizos", "Blancos", "Afrocolombianos", "Indígenas "],
                RH: ["O+", "O-", "A+", "A-", "B+", "B-", "AB+", "AB-"],
                poblaEspecial: [
                    "Población infantil abandonada",
                    "Menores desvinculados del conflicto armado",
                    "Población infantil vulnerable bajo protección en instituciones diferentes al ICBF.",
                    "Población en condiciones de desplazamiento forzado",
                    "Comunidades Indígenas",
                    "Población desmovilizada",
                    "Personas mayores en centros de protección",
                    "Población rural migratoria",
                    "Población ROM",
                    "Personas incluidas en el programa de protección a testigos",
                ],
                sexo: ["Masculino", "Femenino"],
                muni: [],
                tipoDoc: ["CC", "CE", "PA", "RC", "TI"],
                datosPersonales: {
                    Documento: null,
                    Nombre: "",
                    correo: "",
                    celular: "",
                    eps_id: "",
                    area_id: "",
                    tipo_documento: "",
                    primer_nombre: "",
                    segundo_nombre: "",
                    primer_apellido: "",
                    segundo_apellido: "",
                    fecha_nacimiento: "",
                    lugar_nacimiento: "",
                    edad: "",
                    lugar_exp_documento: "",
                    fecha_exp_documento: "",
                    nombre_documento: "",
                    ruta_documento: "",
                    genero: "",
                    grupo_sanguineo: "",
                    poblacion_especial: "",
                    etnia: "",
                    discapacidad: "",
                    tipo_discapacidad: "",
                    cabeza_familia: "",
                    estado_civil: "",
                    estatura: "",
                    peso: "",
                    telefono: "",
                    celular2: "",
                    correo_corporativo: "",
                    direccion_residencia: "",
                    barrio: "",
                    tipo_vivienda: "",
                    estrato: "",
                    municipio_id: "",
                    contacto_emergencia: "",
                    tel_contacto_emergencia: "",
                    parentesco_contacto: "",
                    nombre_foto: "",
                    ruta_foto: "",
                    //Datos Familia
                    familiar_id: "",
                    empleado_id: "",
                    tipo_documento: "",
                    num_documento: "",
                    nombre: "",
                    fecha_nacimiento: "",
                    edad: "",
                    genero: "",
                    escolaridad: "",
                    profesion: "",
                    depende_empleado: "",
                    vivecon_el_empleado: "",
                    nombre_documento: "",
                    ruta_documento: "",
                },
                e6: 1,
                imageUrl: "",
                image: null,
                valid: false,
                firstname: "",
                lastname: "",
                nameRules: [(v) => !!v || "Este campo es requerido"],
                email: "",
                emailRules: [
                    (v) => !!v || "E-mail is required",
                    (v) => /.+@.+/.test(v) || "No es un correo Valido",
                ],
                numRules: [(v) => !!v || "Este campo es requerido"],
            };
        },
        computed: {
            ...mapGetters(["can"]),
        },

        created() {},
        mounted() {
            moment.locale("es");
            this.municipios();
        },
        methods: {
            //
            guardarDatosPersonales() {
                console.log("dad", this.datosPersonales);
                if ((this.datosPersonales.Documento = null)) {
                    this.$alerSuccess("La cédula es requerida!");
                }
                axios
                    .post("/api/intranet/create", this.datosPersonales)
                    .then((res) => {
                        this.$alerSuccess("Anexo generado con exito!");
                        // this.e6 = 2
                    })
                    .catch(() => console.log("Error"));
            },
            municipios() {
                axios.get("/api/intranet/municipios").then((res) => {
                    this.muni = res.data;
                });
            },
            onPickFile() {
                this.$refs.fileInput.click();
            },
            onFilePicked(event) {
                const files = event.target.files;
                let filename = files[0].name;
                if (filename.lastIndexOf(".") <= 0) {
                    return this.$alerSuccess("eror!");
                }
                const fileReader = new FileReader();
                fileReader.addEventListener("load", () => {
                    this.imageUrl = fileReader.result;
                });
                fileReader.readAsDataURL(files[0]);
                this.image = files[0];
            },
        },
    };

</script>

<style>
</style>
