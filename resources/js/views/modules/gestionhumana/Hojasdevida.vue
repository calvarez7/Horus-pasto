<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-container pt-3 pb-1>
                    <v-layout row>
                        <v-dialog v-model="dialog" persistent max-width="11000px">
                            <v-card>
                                <v-card-title class="headline success" style="color:white">
                                    <span v-if="save" class="headline">Crear Empleado</span>
                                    <span v-else class="headline">Informacion del Empleado</span>
                                </v-card-title>
                                <v-container fluid grid-list-lg>
                                    <v-layout row wrap>
                                        <v-flex xs4>
                                            <v-card color="primary" class="white--text">
                                                <v-layout>
                                                    <v-flex xs5>
                                                        <v-img
                                                            src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460"
                                                            height="200px" contain></v-img>
                                                    </v-flex>
                                                    <v-flex xs7>
                                                        <v-card-title primary-title>
                                                            <div>
                                                                <div class="headline">{{data.Nombre}}</div>
                                                                <div>{{data.tipo_documento}}-{{data.Documento}}</div>
                                                                <div>Area: {{data.Area}}</div>
                                                                <div>Correo: {{data.correo}}</div>
                                                                <div>Celular: {{data.celular}}</div>
                                                                <div>Identificador en el sistema: {{data.id}}</div>
                                                            </div>
                                                        </v-card-title>
                                                    </v-flex>
                                                </v-layout>
                                                <v-divider light></v-divider>
                                                <v-card-actions class="pa-3">
                                                    Valoracion Del Empleado
                                                    <v-spacer></v-spacer>
                                                    <span class="grey--text text--lighten-2 caption mr-2">
                                                        ({{ rating }})
                                                    </span>
                                                    <v-rating v-model="rating" background-color="white"
                                                        color="yellow accent-4" dense half-increments hover size="30">
                                                    </v-rating>
                                                </v-card-actions>
                                            </v-card>
                                        </v-flex>
                                        <v-flex xs8>
                                            <v-card max-height="100%" class="mb-3" v-show="personales">
                                                <v-divider></v-divider>
                                                <v-card-text>
                                                    <v-form>
                                                        <v-container pa-0 fluid grid-list>
                                                            <v-layout row wrap>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-autocomplete :items="tipo_doc"
                                                                        v-model="datosPersonales.tipo_documento"
                                                                        label="Tipo de Documento">
                                                                    </v-autocomplete>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Número de Documento">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.primer_nombre"
                                                                        label="Primer Nombre">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.segundo_nombre"
                                                                        label="Segundo Nombre">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.primer_apellido"
                                                                        label="Primer Apellido">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.segundo_apellido"
                                                                        label="Segundo Apellido">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field type="date"
                                                                        v-model="datosPersonales.fecha_nacimiento"
                                                                        label="Fecha de Nacimiento">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-autocomplete :items="municipios"
                                                                        item-text="municipio" item-value="id"
                                                                        v-model="datosPersonales.lugar_nacimineto"
                                                                        @change="CalcularEdad(datosPersonales.lugar_nacimineto)"
                                                                        label="Lugar de Nacimiento">
                                                                    </v-autocomplete>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.edad"
                                                                        label="Edad">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-autocomplete :items="municipios"
                                                                        item-text="municipio" item-value="id"
                                                                        v-model="datosPersonales.lugar_exp_documento"
                                                                        label="Lugar de Expedicion">
                                                                    </v-autocomplete>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field type="date"
                                                                        v-model="datosPersonales.fecha_exp_documento"
                                                                        label="Fecha de Expedicion">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Grado de Escolaridad">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="EPS">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.genero"
                                                                        label="Genero">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.grupo_sanguineo"
                                                                        label="Grupo Sanguineo">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Grupo Poblacion Especial">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.etnia"
                                                                        label="Etnia">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.discapacidad"
                                                                        label="Discapacidad">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.tipo_discapacidad"
                                                                        label="Tipo de Discapacidad">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.tipo_discapacidad"
                                                                        label="Madre/Padre Cabeza de Familia">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.tipo_discapacidad"
                                                                        label="N° de Presonas a Cargo">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.estado_civil"
                                                                        label="Estado Civil">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.estatura"
                                                                        label="Estatura">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.peso"
                                                                        label="Peso">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.peso"
                                                                        label="IMC">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.peso"
                                                                        label="Categoria IMC">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.peso"
                                                                        label="Medios de Trasnporte">
                                                                    </v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-flex xs12 sm12 md3>
                                                                <v-btn color="warning" @click="updateEmpleado()">
                                                                    Actualizar</v-btn>
                                                            </v-flex>
                                                            <v-flex></v-flex>
                                                        </v-container>
                                                    </v-form>
                                                </v-card-text>
                                            </v-card>
                                            <v-card max-height="100%" class="mb-3" v-show="contacto">
                                                <v-divider></v-divider>
                                                <v-card-text>
                                                    <v-form>
                                                        <v-container pa-0 fluid grid-list>
                                                            <v-layout row wrap>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.tipo_documento"
                                                                        label="Telefono Fijo">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Celular 1">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.primer_nombre"
                                                                        label="Celular 2">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.segundo_nombre"
                                                                        label="Correo Electronico Corporativo">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.primer_apellido"
                                                                        label="Correo Electronico Personal">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.segundo_apellido"
                                                                        label="Direccion de Residencia">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.fecha_nacimiento"
                                                                        label="Barrio">
                                                                    </v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-flex xs12 sm12 md3>
                                                                <v-btn color="warning" @click="updateEmpleado()">
                                                                    Actualizar</v-btn>
                                                            </v-flex>
                                                            <v-data-table :headers="headersContacto" :items="desserts"
                                                                :search="search" hide-actions
                                                                :pagination.sync="pagination" class="elevation-1">
                                                                <template v-slot:items="props">
                                                                    <td>{{ props.item.name }}</td>
                                                                    <td class="text-xs-right">
                                                                        {{ props.item.calories }}</td>
                                                                    <td class="text-xs-right">{{ props.item.fat }}
                                                                    </td>
                                                                    <td class="text-xs-right">{{ props.item.carbs }}
                                                                    </td>
                                                                    <td class="text-xs-right">
                                                                        {{ props.item.protein }}</td>
                                                                    <td class="text-xs-right">{{ props.item.iron }}
                                                                    </td>
                                                                </template>
                                                            </v-data-table>
                                                            <div class="text-xs-center pt-2">
                                                                <v-pagination v-model="pagination.page" :length="pages">
                                                                </v-pagination>
                                                            </div>
                                                            <v-layout>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.lugar_nacimineto"
                                                                        label="Tipo de Vivienda">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.edad"
                                                                        label="Estrato Socioeconomico">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.lugar_exp_documento"
                                                                        label="Municipio">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.fecha_exp_documento"
                                                                        label="Departamento">
                                                                    </v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-layout>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Nombre de Contacto de Emergencia">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Telefono de Contacto Emergencia">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.genero"
                                                                        label="Parentesco del Contacto">
                                                                    </v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-flex xs12 sm12 md3>
                                                                <v-btn color="warning" @click="updateEmpleado()">
                                                                    Actualizar</v-btn>
                                                            </v-flex>
                                                            <v-data-table :headers="headersEmergencia" :items="desserts"
                                                                :search="search" hide-actions
                                                                :pagination.sync="pagination" class="elevation-1">
                                                                <template v-slot:items="props">
                                                                    <td>{{ props.item.name }}</td>
                                                                    <td class="text-xs-right">
                                                                        {{ props.item.calories }}</td>
                                                                    <td class="text-xs-right">{{ props.item.fat }}
                                                                    </td>
                                                                    <td class="text-xs-right">{{ props.item.carbs }}
                                                                    </td>
                                                                    <td class="text-xs-right">
                                                                        {{ props.item.protein }}</td>
                                                                    <td class="text-xs-right">{{ props.item.iron }}
                                                                    </td>
                                                                </template>
                                                            </v-data-table>
                                                            <div class="text-xs-center pt-2">
                                                                <v-pagination v-model="pagination.page" :length="pages">
                                                                </v-pagination>
                                                            </div>
                                                            <v-flex></v-flex>
                                                            <v-flex></v-flex>
                                                        </v-container>
                                                    </v-form>
                                                </v-card-text>
                                            </v-card>
                                            <v-card max-height="100%" class="mb-3" v-show="familiar">
                                                <v-divider></v-divider>
                                                <v-card-text>
                                                    <v-form>
                                                        <v-container pa-0 fluid grid-list>
                                                            <v-layout row wrap>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.tipo_documento"
                                                                        label="Tipo de Documento">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Número de Documento">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.primer_nombre"
                                                                        label="Nombre y Apellidos">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.fecha_nacimiento"
                                                                        label="Fecha de Nacimiento">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.lugar_nacimineto"
                                                                        label="Lugar de Nacimiento">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.edad"
                                                                        label="Edad">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.lugar_exp_documento"
                                                                        label="Genero">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.fecha_exp_documento"
                                                                        label="Escolaridad">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Profesion">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Tipo de Parentesco">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.genero"
                                                                        label="Depende Economicamente del Empleado">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.grupo_sanguineo"
                                                                        label="Vive en la misma cada del empleado">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Conformacion del grupo Familiar">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.etnia"
                                                                        label="Tiene Mascota">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.discapacidad"
                                                                        label="Cuantas Mascotas">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.tipo_discapacidad"
                                                                        label="Cual Animal">
                                                                    </v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-flex xs12 sm12 md3>
                                                                <v-btn color="warning" @click="updateEmpleado()">
                                                                    Actualizar</v-btn>
                                                            </v-flex>
                                                            <v-data-table :headers="headers" :items="desserts"
                                                                :search="search" hide-actions
                                                                :pagination.sync="pagination" class="elevation-1">
                                                                <template v-slot:items="props">
                                                                    <td>{{ props.item.name }}</td>
                                                                    <td class="text-xs-right">
                                                                        {{ props.item.calories }}</td>
                                                                    <td class="text-xs-right">{{ props.item.fat }}
                                                                    </td>
                                                                    <td class="text-xs-right">{{ props.item.carbs }}
                                                                    </td>
                                                                    <td class="text-xs-right">
                                                                        {{ props.item.protein }}</td>
                                                                    <td class="text-xs-right">{{ props.item.iron }}
                                                                    </td>
                                                                </template>
                                                            </v-data-table>
                                                            <div class="text-xs-center pt-2">
                                                                <v-pagination v-model="pagination.page" :length="pages">
                                                                </v-pagination>
                                                            </div>
                                                            <v-flex></v-flex>
                                                            <v-flex></v-flex>
                                                        </v-container>
                                                    </v-form>
                                                </v-card-text>
                                            </v-card>
                                            <v-card max-height="100%" class="mb-3" v-show="formacion">
                                                <v-divider></v-divider>
                                                <v-card-text>
                                                    <v-form>
                                                        <v-container pa-0 fluid grid-list>
                                                            <v-layout row wrap>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.tipo_documento"
                                                                        label="Titulo">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Número de Acta de Grado">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.primer_nombre"
                                                                        label="Fecha de Grado">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.segundo_nombre"
                                                                        label="Institucion">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.primer_apellido"
                                                                        label="Ciudad">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.segundo_apellido"
                                                                        label="Nivel de estudio">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.fecha_nacimiento"
                                                                        label="Fecha de Verificacion del titulo">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.lugar_nacimineto"
                                                                        label="Funcionario que realiza la verificacion">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.edad"
                                                                        label="Fecha de Respuesta de la Institucion">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.lugar_exp_documento"
                                                                        label="Estado del Titulo">
                                                                    </v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-flex xs12 sm12 md3>
                                                                <v-btn color="warning" @click="updateEmpleado()">
                                                                    Actualizar</v-btn>
                                                            </v-flex>
                                                            <v-data-table :headers="headers" :items="desserts"
                                                                :search="search" hide-actions
                                                                :pagination.sync="pagination" class="elevation-1">
                                                                <template v-slot:items="props">
                                                                    <td>{{ props.item.name }}</td>
                                                                    <td class="text-xs-right">
                                                                        {{ props.item.calories }}</td>
                                                                    <td class="text-xs-right">{{ props.item.fat }}
                                                                    </td>
                                                                    <td class="text-xs-right">{{ props.item.carbs }}
                                                                    </td>
                                                                    <td class="text-xs-right">
                                                                        {{ props.item.protein }}</td>
                                                                    <td class="text-xs-right">{{ props.item.iron }}
                                                                    </td>
                                                                </template>
                                                            </v-data-table>
                                                            <div class="text-xs-center pt-2">
                                                                <v-pagination v-model="pagination.page" :length="pages">
                                                                </v-pagination>
                                                            </div>
                                                            <v-flex></v-flex>
                                                            <v-flex></v-flex>
                                                        </v-container>
                                                    </v-form>
                                                </v-card-text>
                                            </v-card>
                                            <v-card max-height="100%" class="mb-3" v-show="laboral">
                                                <v-divider></v-divider>
                                                <v-card-text>
                                                    <v-form>
                                                        <v-container pa-0 fluid grid-list>
                                                            <v-layout row wrap>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.tipo_documento"
                                                                        label="Empresa">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field v-model="datosPersonales.Documento"
                                                                        label="Cargo">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.primer_nombre"
                                                                        label="Fecha de Inicio">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.segundo_nombre"
                                                                        label="Fecha de Finalizacion">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.primer_apellido"
                                                                        label="Principales Funciones">
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12 md3>
                                                                    <v-text-field
                                                                        v-model="datosPersonales.segundo_apellido"
                                                                        label="Observaciones">
                                                                    </v-text-field>
                                                                </v-flex>
                                                            </v-layout>
                                                            <v-flex xs12 sm12 md3>
                                                                <v-btn color="warning" @click="updateEmpleado()">
                                                                    Actualizar</v-btn>
                                                            </v-flex>
                                                            <v-data-table :headers="headers" :items="desserts"
                                                                :search="search" hide-actions
                                                                :pagination.sync="pagination" class="elevation-1">
                                                                <template v-slot:items="props">
                                                                    <td>{{ props.item.name }}</td>
                                                                    <td class="text-xs-right">
                                                                        {{ props.item.calories }}</td>
                                                                    <td class="text-xs-right">{{ props.item.fat }}
                                                                    </td>
                                                                    <td class="text-xs-right">{{ props.item.carbs }}
                                                                    </td>
                                                                    <td class="text-xs-right">
                                                                        {{ props.item.protein }}</td>
                                                                    <td class="text-xs-right">{{ props.item.iron }}
                                                                    </td>
                                                                </template>
                                                            </v-data-table>
                                                            <div class="text-xs-center pt-2">
                                                                <v-pagination v-model="pagination.page" :length="pages">
                                                                </v-pagination>
                                                            </div>
                                                            <v-flex></v-flex>
                                                            <v-flex></v-flex>
                                                        </v-container>
                                                    </v-form>
                                                </v-card-text>
                                            </v-card>
                                            <v-divider></v-divider>
                                            <v-card>
                                                <v-bottom-nav :active.sync="bottomNav" :color="color" :value="true"
                                                    absolute dark shift>
                                                    <v-btn dark
                                                        @click="personales = true, contacto = false, familiar = false, formacion = false, laboral = false">
                                                        <span>Datos Personales</span>
                                                        <v-icon>account_circle</v-icon>
                                                    </v-btn>

                                                    <v-btn dark
                                                        @click="personales = false, contacto = true, familiar = false, formacion = false, laboral = false">
                                                        <span>Informacion de Contacto</span>
                                                        <v-icon>perm_phone_msg</v-icon>
                                                    </v-btn>

                                                    <v-btn dark
                                                        @click="personales = false, contacto = false, familiar = true, formacion = false, laboral = false">
                                                        <span>Informacion Familiar</span>
                                                        <v-icon>groups</v-icon>
                                                    </v-btn>

                                                    <v-btn dark
                                                        @click="personales = false, contacto = false, familiar = false, formacion = true, laboral = false">
                                                        <span>Formacion</span>
                                                        <v-icon>school</v-icon>
                                                    </v-btn>

                                                    <v-btn dark
                                                        @click="personales = false, contacto = false, familiar = false, formacion = false, laboral = true">
                                                        <span>Historial Laboral</span>
                                                        <v-icon>domain</v-icon>
                                                    </v-btn>
                                                </v-bottom-nav>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="error" @click="dialog = false">Cancelar</v-btn>
                                        <v-btn v-if="save" color="success" @click="saveEmpleado()">Guardar
                                        </v-btn>
                                    </v-card-actions>
                                </v-container>
                            </v-card>
                        </v-dialog>
                    </v-layout>
                </v-container>
                <v-card-title>
                    <v-btn round v-if="can('empleado.create')" @click="createEmpleado()" color="primary" dark>
                        <v-icon left>person_add</v-icon>Nuevo Empleado
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-flex sm5 xs12>
                        <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line hide-details>
                        </v-text-field>
                    </v-flex>
                </v-card-title>
                <v-data-table :headers="headersAfuera" :items="empleados" :search="search">
                    <template v-slot:items="props">
                        <td>{{ props.item.id }}</td>
                        <td class="text-xs-right">{{ props.item.Nombre }}</td>
                        <td class="text-xs-right">{{ props.item.Documento }}</td>
                        <td class="text-xs-right">{{ props.item.Area }}</td>
                        <td class="text-xs-right">{{ props.item.correo }}</td>
                        <td class="text-xs-right">{{ props.item.celular }}</td>
                        <td class="text-xs-right" >
                            <v-chip v-if="props.item.Estado_id == 1" color="primary" text-color="white">Activo</v-chip>
                            <v-chip v-if="props.item.Estado_id == 2" color="warning" text-color="white">Inactivo
                            </v-chip>
                            <v-chip v-else-if="props.item.Estado_id == 5" color="red" text-color="white">Pendiente Por
                                Contrato</v-chip>
                            <v-chip v-else-if="props.item.Estado_id == 47" color="success" text-color="white">Contratado
                            </v-chip>
                        </td>
                        <td class="text-xs-right">
                            <v-btn v-if="can('empleado.edit')" fab outline color="warning" small
                                @click="editEmpleado(props.item), fetchDatosPersonales(props.item.id) ">
                                <v-icon>edit</v-icon>
                            </v-btn>
                        </td>
                    </template>
                </v-data-table>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    export default {
        name: 'Empleados',
        data() {
            return {
                empleados: [],
                datosPersonales: [],
                municipios: [],
                personales: true,
                contacto: false,
                familiar: false,
                formacion: false,
                laboral: false,
                tipo_doc: ['CC', 'CE', 'TI', 'PASAPORTE', 'PEPFF', 'RUMV'],
                bottomNav: 0,
                rating: 0.5,
                search: '',
                estado: ['1', '2'],
                headersAfuera: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'right',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Documento',
                        align: 'right',
                        sortable: false,
                        value: 'Documento'
                    },
                    {
                        text: 'Area',
                        align: 'right',
                        sortable: false,
                        value: 'Area'
                    },
                    {
                        text: 'correo',
                        align: 'right',
                        sortable: false,
                        value: 'correo'
                    },
                    {
                        text: 'celular',
                        align: 'right',
                        sortable: false,
                        value: 'celular'
                    },
                    {
                        text: 'Estado',
                        align: 'right',
                        sortable: false,
                        value: 'Estado'
                    },
                    {
                        text: 'Accion',
                        align: 'right',
                        sortable: false,
                        value: ''
                    }
                ],
                save: true,
                dialog: false,
                data: {
                    Nombre: null,
                    Documento: null,
                    Area: '',
                    correo: '',
                    celular: '',
                    tipo_documento: ''
                },

                search: '',
                pagination: {},
                selected: [],
                desserts: [],
                headersContacto: [{
                        text: 'Telefono Fijo',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'Celular 1',
                        value: 'calories'
                    },
                    {
                        text: 'Celular 2',
                        value: 'fat'
                    },
                    {
                        text: 'Correo Corporativo',
                        value: 'carbs'
                    },
                    {
                        text: 'Correo Peronsal',
                        value: 'protein'
                    },
                    {
                        text: 'Direccion',
                        value: 'iron'
                    },
                    {
                        text: 'Barrio',
                        value: 'iron'
                    },
                ],
                headersEmergencia: [{
                        text: 'Nombre Del Contacto',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'Telefono de Contacto',
                        value: 'calories'
                    },
                    {
                        text: 'Parentesco del Contacto',
                        value: 'fat'
                    }
                ],
                headers: [{
                        text: 'Nombre Del Contacto',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'Telefono de Contacto',
                        value: 'calories'
                    },
                    {
                        text: 'Parentesco del Contacto',
                        value: 'fat'
                    }
                ]
            }
        },
        computed: {
            ...mapGetters(['can']),
            color() {
                switch (this.bottomNav) {
                    case 0:
                        return 'success'
                    case 1:
                        return 'info'
                    case 2:
                        return 'success'
                    case 3:
                        return 'info'
                    case 4:
                        return 'success'
                }
            },

            pages() {
                if (this.pagination.rowsPerPage == null ||
                    this.pagination.totalItems == null
                ) return 0

                return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
            }
        },
        mounted() {
            this.fetchEmpleados();
        },
        methods: {
            CalcularEdad(fechaNacimiento) {
                let nacimiento = moment(fechaNacimiento); //Debe estar en formato YYYY-MM-DD
                console.log(fechaNacimiento)
                let hoy = moment();
                let edad = 0;
                if (nacimiento < hoy) {
                    edad = hoy.diff(nacimiento); //Calculamos la diferencia en años
                }
                this.datosPersonales.edad = edad
            },
            fetchEmpleados() {
                axios.get('/api/empleados/getEmpleados')
                    .then(res => {
                        this.empleados = res.data
                    })
                    .catch(err => this.showError(err.response.data))
            },
            fetchMunicipios() {
                axios.get('/api/municipio/lista')
                    .then(res => {
                        this.municipios = res.data
                    })
            },
            fetchDatosPersonales(id) {
                let identificador = id;
                axios.get('/api/empleados/getDatosPersonales/' + identificador)
                    .then(res => {
                        this.datosPersonales = res.data
                        this.CalcularEdad(this.datosPersonales.fecha_nacimiento)
                        this.fetchMunicipios()
                    })
                    .catch(err => this.showError(err.response.data))
            },
            createEmpleado() {
                this.emptyData();
                this.save = true;
                this.dialog = true;
            },
            showError(message) {
                swal({
                    title: "¡No puede ser!",
                    text: `${message.name}`,
                    icon: "warning",
                });
            },
            emptyData() {
                this.data = {
                    Nombre: null,
                    Documento: null,
                    Area: '',
                    correo: '',
                    celular: '',
                    Estado: '',
                }
            },
            saveEmpleado() {
                axios.post('/api/empleados/create', this.datosPersonales)
                    .then(res => {
                        this.emptyData();
                        this.dialog = false;
                        this.fetchEmpleados();
                        swal({
                            title: "Empleado Creado!",
                            text: " ",
                            type: "success",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => this.showError(err.response.data))
            },
            editEmpleado(empleados) {
                this.data = empleados;
                this.save = false;
                this.dialog = true;
            },
            updateEmpleado() {
                axios.put(`/api/empleados/edit/${this.data.id}`, this.data)
                    .then(res => {
                        console.log('empleado', this.data.id);
                        this.emptyData();
                        this.dialog = false;
                        this.fetchEmpleados();
                        swal({
                            title: "¡Empleado Actualizado!",
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => console.log(err.response.data));
            },
        },
    }

</script>
