<template>
    <v-container fluid pa-2 ma-0>
        <v-layout>
            <v-flex>
                <v-card>
                    <v-toolbar flat color="white">
                        <v-layout row>
                            <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                                <template v-slot:activator="{ on }">
                                    <v-btn round color="primary" dark v-on="on" v-if="can('crear.directorio')">
                                        Crear Empleado
                                    </v-btn>
                                </template>
                                <v-card>
                                    <v-toolbar dark color="primary">
                                        <v-btn icon dark @click="Salir()">
                                            <v-icon>close</v-icon>
                                        </v-btn>
                                        <v-toolbar-title>Crear Empleado</v-toolbar-title>
                                    </v-toolbar>
                                    <v-divider></v-divider>
                                    <v-card class="mb-5">
                                        <v-card-text>
                                            <v-container grid-list-md pt-0 pb-0>
                                                <v-form>
                                                    <v-layout wrap>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.primer_nombre"
                                                                label="Primer Nombre" type="text" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.segundo_nombre"
                                                                label="segundo Nombre" type="text" required>
                                                            </v-text-field>
                                                        </v-flex>

                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.primer_apellido"
                                                                label="Primer Apellido" type="text" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.segundo_apellido"
                                                                label="Segundo Apellido" type="text" required>
                                                            </v-text-field>
                                                        </v-flex>

                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field type="date"
                                                                v-model="empleado.fecha_nacimiento"
                                                                label="Fecha de Expedicion" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-autocomplete v-model="empleado.lugar_nacimineto"
                                                                :items="municipio" label="Lugar de Nacimiento"
                                                                item-text="Nombre" item-value="lugar_nacimiento">
                                                            </v-autocomplete>
                                                        </v-flex>

                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field type="number" v-model="empleado.Documento"
                                                                label="Documento" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-select :items="documento"
                                                                v-model="empleado.tipo_documento" label="Tipo Documneto"
                                                                required>
                                                            </v-select>
                                                        </v-flex>

                                                        <v-text-field type="date" v-model="empleado.fecha_exp_documento"
                                                            label="Fecha de Expedicion" required>
                                                        </v-text-field>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-autocomplete v-model="empleado.lugar_exp_documento"
                                                                :items="municipio" label="Lugar de Expedicion"
                                                                item-text="Nombre" item-value="Nombre">
                                                            </v-autocomplete>
                                                        </v-flex>

                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.correo_corporativo"
                                                                type="email" label="Correo Corporativo" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.correo" type="email"
                                                                label="Correo" required>
                                                            </v-text-field>
                                                        </v-flex>

                                                        <v-flex xs3 sm3 md3>
                                                            <v-select :items="sangre" v-model="empleado.grupo_sanguineo"
                                                                label="Grupo Sanguineo" required>
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-autocomplete v-model="empleado.eps_id" :items="Eps"
                                                                label="Eps" item-text="nombre" item-value="id">
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-select :items="Genero" v-model="empleado.genero"
                                                                label="Genero" required>
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-select :items="estrato" v-model="empleado.estrato"
                                                                label="Estrato" item-text="nombre" item-value="id"
                                                                required>
                                                            </v-select>
                                                        </v-flex>

                                                        <v-flex xs6 sm6 md6>
                                                            <v-select :items="etnias" v-model="empleado.etnia"
                                                                label="Etia" required>
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.peso" type="number"
                                                                label="Peso" required suffix="Kg">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.estatura" type="number"
                                                                label="Estatura" required suffix="Cm">
                                                            </v-text-field>
                                                        </v-flex>

                                                        <v-flex xs3 sm3 md3>
                                                            <v-autocomplete v-model="empleado.municipio_id"
                                                                :items="municipio" label="Municipio Residencia"
                                                                item-text="Nombre" item-value="id">
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.direccion_residencia"
                                                                label="Direccion" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.barrio" label="Barrio"
                                                                required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.tipo_vivienda"
                                                                label="Tipo Vivienda" required>
                                                            </v-text-field>
                                                        </v-flex>

                                                        <v-flex xs3 sm3 md3>
                                                            <v-select :items="estadocivil"
                                                                v-model="empleado.estado_civil" label="Estadio Civil"
                                                                required>
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.celular" label="Celular"
                                                                type="number" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.celular2" label="Celular2"
                                                                type="number" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.telefono" type="number"
                                                                label="Telefeno" required>
                                                            </v-text-field>
                                                        </v-flex>

                                                        <v-flex xs3 sm3 md3>
                                                            <v-select :items="cabezaFamilia"
                                                                v-model="empleado.cabeza_familia" label="Cabeza Familia"
                                                                required>
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-autocomplete v-model="empleado.Estado_id"
                                                                :items="estados" label="Estado" item-text="Nombre"
                                                                item-value="id">
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-autocomplete v-model="empleado.area_id" :items="Area"
                                                                label="Area" item-text="Nombre" item-value="id">
                                                            </v-autocomplete>
                                                        </v-flex>
                                                        <v-flex xs3 sm3 md3>
                                                            <v-text-field v-model="empleado.salario" type="number"
                                                                label="Salario" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs4 sm4 md4>
                                                            <v-select :items="contacto" v-model="empleado.contacto"
                                                                label="Contacto Emergencia" required>
                                                            </v-select>
                                                        </v-flex>

                                                        <v-flex xs4 sm4 md4>
                                                            <v-select :items="discapacidad"
                                                                v-model="empleado.discapacidad" label="Discapacidad"
                                                                required>
                                                            </v-select>
                                                        </v-flex>
                                                        <v-flex xs4 sm4 md4>
                                                            <v-autocomplete v-if="empleado.discapacidad === 'Si'"
                                                                v-model="empleado.tipo_discapacidad"
                                                                :items="discapacidades" label="Tipo Discapacidad">
                                                            </v-autocomplete>
                                                        </v-flex>

                                                        <v-flex xs4 sm4 md4 v-if="empleado.contacto === 'Si'">
                                                            <v-text-field v-model="empleado.contacto_emergencia"
                                                                label="Nombre" type="text" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs4 sm4 md4 v-if="empleado.contacto === 'Si'">
                                                            <v-text-field v-model="empleado.tel_contacto_emergencia"
                                                                label="Telefono" type="number" required>
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs4 sm4 md4 v-if="empleado.contacto === 'Si'">
                                                            <v-text-field v-model="empleado.parentesco_contacto"
                                                                label="Parentesco" type="text" required>
                                                            </v-text-field>
                                                        </v-flex>

                                                        <v-flex xs12 md12 sm12>
                                                            <v-btn color="success" round block dark
                                                                @click="Guardardirectorio()">
                                                                Guardar
                                                            </v-btn>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-form>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                </v-card>
                            </v-dialog>
                        </v-layout>
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line
                            hide-details>
                        </v-text-field>
                    </v-toolbar>
                    <v-data-table :search="search" :headers="headers" :items="datosDirectorio" :expand="expand"
                        item-key="Nombre">
                        <template v-slot:items="props">
                            <tr>
                                <td>
                                    <v-btn fab small @click="familiares(props.item)"
                                        v-if="props.item.idusuario === nameUser || can('editar.directorio')">
                                        <v-icon @click="props.expanded = !props.expanded">expand_more</v-icon>
                                    </v-btn>
                                </td>
                                <td>{{ props.item.Nombre }}</td>
                                <td class="text-xs-center">
                                    {{ props.item.correo_corporativo }}</td>
                                <td class="text-xs-center">{{ props.item.Nombre_area }}</td>
                                <td class="text-left">
                                    <v-btn v-show="can('editar.directorio')" v-if="props.item.Nombre_estado == 'Activo'"
                                        color="success" round v-model="cambioestado.Nombre_estado"
                                        @click="cambiar(props.item)">
                                        {{props.item.Nombre_estado}}
                                    </v-btn>
                                    <v-btn v-show="can('editar.directorio')"
                                        v-if="props.item.Nombre_estado == 'Inactivo'" color="red" dark round
                                        v-model="cambioestado.Nombre_estado" @click="cambiar(props.item)">
                                        {{props.item.Nombre_estado}}
                                    </v-btn>
                                </td>
                                <td class="text-xs-center d-flex align-center">
                                    <v-tooltip bottom>
                                        <template  v-slot:activator="{ on }">
                                            <v-btn fab small @click="editar(props.item),dialogempleado=true"
                                                v-show="props.item.idusuario == nameUser || can('editar.directorio')"
                                                v-on="on" color="warning">
                                                <v-icon>edit</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Editar Empleado</span>
                                    </v-tooltip>

                                    <v-dialog v-model="dialogempleado" persistent max-width="1200px">
                                        <v-card>
                                            <v-toolbar color="success">
                                                <v-toolbar-title>Editar Empleado</v-toolbar-title>
                                            </v-toolbar>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-form>
                                                        <v-layout wrap>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.primer_nombre"
                                                                    label="Primer Nombre" type="text" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.segundo_nombre"
                                                                    label="segundo Nombre" type="text" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.primer_apellido"
                                                                    label="Primer Apellido" type="text" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.segundo_apellido"
                                                                    label="Segundo Apellido" type="text" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs6 sm6 md6>
                                                                <v-text-field v-model="editarempleado.fecha_nacimiento"
                                                                    label="Fecha de Nacimiento" type="date" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs6 sm6 md6>
                                                                <v-autocomplete
                                                                    v-model="editarempleado.lugar_nacimineto"
                                                                    :items="municipio" label="Lugar de Nacimiento"
                                                                    item-text="Nombre" item-value="lugar_nacimiento">
                                                                </v-autocomplete>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field type="number"
                                                                    v-model="editarempleado.Documento" label="Documento"
                                                                    required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-select :items="documento"
                                                                    v-model="editarempleado.tipo_documento"
                                                                    label="Tipo Documneto" required>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field type="date"
                                                                    v-model="editarempleado.fecha_exp_documento"
                                                                    label="Fecha de Expedicion" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-autocomplete
                                                                    v-model="editarempleado.lugar_exp_documento"
                                                                    :items="municipio" label="Lugar de Expedicion"
                                                                    item-text="Nombre" item-value="Nombre">
                                                                </v-autocomplete>
                                                            </v-flex>

                                                            <v-flex xs6 sm6 md6>
                                                                <v-text-field
                                                                    v-model="editarempleado.correo_corporativo"
                                                                    type="email" label="Correo Corporativo" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs6 sm6 md6>
                                                                <v-text-field v-model="editarempleado.correo"
                                                                    type="email" label="Correo" required>
                                                                </v-text-field>
                                                            </v-flex>

                                                            <v-flex xs3 sm3 md3>
                                                                <v-select :items="sangre"
                                                                    v-model="editarempleado.grupo_sanguineo"
                                                                    label="Grupo Sanguineo" required>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-autocomplete v-model="editarempleado.eps_id"
                                                                    :items="Eps" label="Eps" item-text="nombre"
                                                                    item-value="id">
                                                                </v-autocomplete>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-select :items="Genero"
                                                                    v-model="editarempleado.genero" label="Genero"
                                                                    required>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-select :items="estrato"
                                                                    v-model="editarempleado.estrato" label="Estrato"
                                                                    item-text="nombre" item-value="id" required>
                                                                </v-select>
                                                            </v-flex>

                                                            <v-flex xs6 sm6 md6>
                                                                <v-select :items="etnias" v-model="editarempleado.etnia"
                                                                    label="Etia" required>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.peso" type="text"
                                                                    label="Peso" required suffix="Kg">
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.estatura"
                                                                    type="text" label="Estatura" required suffix="Cm">
                                                                </v-text-field>
                                                            </v-flex>

                                                            <v-flex xs3 sm3 md3>
                                                                <v-autocomplete v-model="editarempleado.municipio_id"
                                                                    :items="municipio" label="Municipio Residencia"
                                                                    item-text="Nombre" item-value="id">
                                                                </v-autocomplete>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field
                                                                    v-model="editarempleado.direccion_residencia"
                                                                    label="Direccion" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.barrio"
                                                                    label="Barrio" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.tipo_vivienda"
                                                                    label="Tipo Vivienda" required>
                                                                </v-text-field>
                                                            </v-flex>

                                                            <v-flex xs3 sm3 md3>
                                                                <v-select :items="estadocivil"
                                                                    v-model="editarempleado.estado_civil"
                                                                    label="Estadio Civil" required>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.celular"
                                                                    label="Celular" type="number" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.celular2"
                                                                    label="Celular2" type="number" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.telefono"
                                                                    type="nunber" label="Telefeno" required>
                                                                </v-text-field>
                                                            </v-flex>

                                                            <v-flex xs3 sm3 md3>
                                                                <v-select :items="cabezaFamilia"
                                                                    v-model="editarempleado.cabeza_familia"
                                                                    label="Cabeza Familia" required>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-autocomplete v-model="editarempleado.Estado_id"
                                                                    :items="estados" label="Estado" item-text="Nombre"
                                                                    item-value="id">
                                                                </v-autocomplete>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-autocomplete v-model="editarempleado.area_id"
                                                                    :items="Area" label="Area" item-text="Nombre"
                                                                    item-value="id">
                                                                </v-autocomplete>
                                                            </v-flex>
                                                            <v-flex xs3 sm3 md3>
                                                                <v-text-field v-model="editarempleado.salario"
                                                                    type="nunber" label="Salario" required>
                                                                </v-text-field>
                                                            </v-flex>

                                                            <v-flex xs6 sm6 md6>
                                                                <v-select :items="discapacidad"
                                                                    v-model="editarempleado.discapacidad"
                                                                    label="Discapacidad" required>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs6 sm6 md6>
                                                                <v-autocomplete
                                                                    v-model="editarempleado.tipo_discapacidad"
                                                                    :items="discapacidades" label="Tipo Discapacidad">
                                                                </v-autocomplete>
                                                            </v-flex>

                                                            <v-flex xs4 sm4 md4>
                                                                <v-text-field
                                                                    v-model="editarempleado.contacto_emergencia"
                                                                    label="Nombre" type="text" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-text-field
                                                                    v-model="editarempleado.tel_contacto_emergencia"
                                                                    label="Telefono" type="number" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-text-field
                                                                    v-model="editarempleado.parentesco_contacto"
                                                                    label="Parentesco" type="text" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-form>
                                                </v-container>
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red" dark @click="dialogempleado = false">
                                                    Cancel</v-btn>
                                                <v-btn color="success" dark @click="actualizarEmpleado()">Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>

                                    <v-tooltip bottom>
                                        <template  v-slot:activator="{ on }">
                                            <v-btn color="success" dark class="mb-2" v-on="on" icon v-if="props.item.idusuario === nameUser || can('editar.directorio')" @click="dialogfamilia=true">
                                                <v-icon>group_add</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Agregar Familiar</span>
                                    </v-tooltip>

                                    <v-dialog v-model="dialogfamilia" persistent max-width="700px">
                                        <v-card>
                                            <v-toolbar color="success">
                                                <v-toolbar-title>Agregar Familiar</v-toolbar-title>
                                            </v-toolbar>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-form>
                                                        <v-layout wrap>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-text-field v-model="familias.nombre" label="Nombre" type="text" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-select :items="tipoFamiliar" v-model="familias.familiar_id" label="Tipo de Familiar"
                                                                    item-text="nombre" item-value="id" required>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-select :items="documento" v-model="familias.tipo_documento" label="Tipo Documneto"
                                                                    required>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-text-field v-model="familias.num_documento" label="Documento" type="number" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-text-field v-model="familias.fecha_nacimiento" label="Fecha Nacimiento" type="date" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-select :items="Genero" v-model="familias.genero" label="Genero" required>
                                                                </v-select>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-text-field v-model="familias.escolaridad" label="Escolaridad" type="text" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-text-field v-model="familias.profesion" label="Profesion" type="text" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-text-field v-model="familias.depende_empleado" label="Depende del Empleado" type="text"
                                                                    required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-text-field v-model="familias.vivecon_el_empleado" label="Vive con el Empleado"
                                                                    type="text" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                            <v-flex xs4 sm4 md4>
                                                                <v-text-field v-model="familias.edad" label="Edad" type="number" required>
                                                                </v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-form>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red" dark @click="dialogfamilia = false">Cancel
                                                </v-btn>
                                                <v-btn color="success" dark @click="agregarfamiliar(props.item)">
                                                    Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </td>
                            </tr>
                        </template>
                        <template v-slot:expand="props">
                            <v-container container fluid ml-4 pl-4 pa-2>
                                <v-layout wrap fuid>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Primer Nombre: {{props.item.primer_nombre}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Segundo Nombre: {{props.item.segundo_nombre}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Primer Apellido: {{props.item.primer_apellido}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Segundo Apellido: {{props.item.segundo_apellido}}
                                    </v-flex>

                                    <v-flex xs3 md3 sm3 pa-1>
                                        Tipo Documento: {{props.item.tipo_documento}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Documento: {{props.item.Documento}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Lugar de Expedicion: {{props.item.lugar_exp_documento}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Fecha Nacimiento: {{props.item.fecha_nacimiento}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Lugar Nacimiento: {{props.item.lugar_nacimineto}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Genero: {{props.item.genero}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Grupo Sanguineo: {{props.item.grupo_sanguineo}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Etnia: {{props.item.etnia}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Discapacidad: {{props.item.discapacidad}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Tipo Discapacidad: {{props.item.tipo_discapacidad}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Cabeza de Familia: {{props.item.cabeza_familia}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        EStado Civil: {{props.item.estado_civil}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Estatura: {{props.item.estatura}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Peso: {{props.item.peso}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Telefono: {{props.item.telefono}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Celular2: {{props.item.celular2}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Direccion: {{props.item.direccion_residencia}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Barrio: {{props.item.barrio}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Tipo Vivienda: {{props.item.tipo_vivienda}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Estrato: {{props.item.estrato}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Municipio Residencia: {{props.item.Nombre_municipio}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Salario: {{props.item.salario}}
                                    </v-flex>
                                    <v-flex xs3 md3 sm3 pa-1>
                                        Estado: {{props.item.Nombre_estado}}
                                    </v-flex>

                                    <v-flex xs12 md12 sm12>
                                        <div class="text-xs-center">
                                            <h3 class="headline mb-0 pt-3 pb-2">Informacion contacto Emergencia
                                            </h3>
                                        </div>
                                    </v-flex>

                                    <v-flex xs4 md4 sm4>
                                        Fecha Nacimiento: {{props.item.contacto_emergencia}}
                                    </v-flex>
                                    <v-flex xs4 md4 sm4>
                                        Fecha Nacimiento: {{props.item.tel_contacto_emergencia}}
                                    </v-flex>
                                    <v-flex xs4 md4 sm4>
                                        Fecha Nacimiento: {{props.item.parentesco_contacto}}
                                    </v-flex>
                                </v-layout>
                                <v-flex xs12 md12 sm12>
                                    <div class="text-xs-center">
                                        <h3 class="headline mb-0 pt-3">Informacion Familiares</h3>
                                    </div>
                                </v-flex>
                                <v-flex xs12 md12 sm12 pr-5 mr-2>
                                    <v-data-table :headers="titulofamiliares" :items="datosFamilia" class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.nombre_Familiar }}</td>
                                            <td>{{ props.item.tipo_documento }}</td>
                                            <td>{{ props.item.num_documento }}</td>
                                            <td>{{ props.item.fecha_nacimiento }}</td>
                                            <td>{{ props.item.genero }}</td>
                                            <td>{{ props.item.edad }}</td>
                                            <td>{{ props.item.escolaridad }}</td>
                                            <td>{{ props.item.profesion }}</td>
                                            <td>{{ props.item.depende_empleado }}</td>
                                            <td>{{ props.item.vivecon_el_empleado }}</td>
                                            <td>
                                                <v-dialog v-model="dialogfamiliaE" persistent max-width="500px">
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn fab small @click="editarfamiliar(props.item)"
                                                            v-if="props.item.idusuario === nameUser || can('editar.directorio')"
                                                            v-on="on" color="warning">
                                                            <v-icon>edit</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <v-card>
                                                        <v-toolbar color="success">
                                                            <v-toolbar-title>Editar Familiar</v-toolbar-title>
                                                        </v-toolbar>
                                                        <v-card-text>
                                                            <v-container grid-list-md>
                                                                <v-form>
                                                                    <v-layout wrap>
                                                                        <v-flex xs4 sm4 md4>
                                                                            <v-text-field
                                                                                v-model="Editarfamilias.nombre"
                                                                                label="Nombre" type="text" required>
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                        <v-flex xs4 sm4 md4>
                                                                            <v-select :items="tipoFamiliar"
                                                                                v-model="Editarfamilias.familiar_id"
                                                                                label="Tipo de Familiar"
                                                                                item-text="nombre" item-value="id"
                                                                                required>
                                                                            </v-select>
                                                                        </v-flex>
                                                                        <v-flex xs4 sm4 md4>
                                                                            <v-select :items="documento"
                                                                                v-model="Editarfamilias.tipo_documento"
                                                                                label="Tipo Documneto" required>
                                                                            </v-select>
                                                                        </v-flex>
                                                                        <v-flex xs4 sm4 md4>
                                                                            <v-text-field
                                                                                v-model="Editarfamilias.num_documento"
                                                                                label="Documento" type="number"
                                                                                required>
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                        <v-flex xs6 sm6 md6>
                                                                            <v-text-field
                                                                                v-model="Editarfamilias.fecha_nacimiento"
                                                                                label="Fecha de Nacimiento" type="date"
                                                                                required>
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                        <v-flex xs4 sm4 md4>
                                                                            <v-autocomplete v-model="Editarfamilias.genero"
                                                                    :items="Genero" label="Generos" item-text="nombre"
                                                                    item-value="id">
                                                                </v-autocomplete>

                                                                        </v-flex>
                                                                        <v-flex xs4 sm4 md4>
                                                                            <v-text-field
                                                                                v-model="Editarfamilias.escolaridad"
                                                                                label="Escolaridad" type="text"
                                                                                required>
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                        <v-flex xs4 sm4 md4>
                                                                            <v-text-field
                                                                                v-model="Editarfamilias.profesion"
                                                                                label="Profesion" type="text" required>
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                        <v-flex xs4 sm4 md4>
                                                                            <v-text-field
                                                                                v-model="Editarfamilias.depende_empleado"
                                                                                label="Depende del Empleado" type="text"
                                                                                required>
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                        <v-flex xs4 sm4 md4>
                                                                            <v-text-field
                                                                                v-model="Editarfamilias.vivecon_el_empleado"
                                                                                label="Vive con el Empleado" type="text"
                                                                                required>
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                        <v-flex xs4 sm4 md4>
                                                                            <v-text-field v-model="Editarfamilias.edad"
                                                                                label="Edad" type="number" required>
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                    </v-layout>
                                                                </v-form>
                                                            </v-container>
                                                        </v-card-text>
                                                        <v-card-actions>
                                                            <v-spacer></v-spacer>
                                                            <v-btn color="red" dark @click="dialogfamiliaE = false">
                                                                Cancel
                                                            </v-btn>
                                                            <v-btn color="success" dark @click="actualizarfamiliar()">
                                                                Guardar
                                                            </v-btn>
                                                        </v-card-actions>
                                                    </v-card>
                                                </v-dialog>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-container>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import Swal from "sweetalert2";
    import {
        mapGetters
    } from "vuex";
    export default {
        data() {
            return {
                d: '',
                search: "",
                modalfechaexpedicion: false,
                fecha: false,
                dialogempleado: false,
                dialog: false,
                dialogfamilia: false,
                fechanacimientofamilia: false,
                dialogodirectorio: false,
                modalfechaexpedicion: false,
                directorio: false,
                expand: false,
                fechanacimiento: false,
                fechanacimientos: false,

                headers: [{
                        sortable: false,
                    },
                    {
                        text: "Nombre",
                        sortable: false,
                        value: "Nombre"
                    },
                    {
                        text: "Corre Corporativo (g)",
                        value: "correo_corporativo",
                        align: "center",
                        sortable: false,

                    },
                    {
                        text: "Area",
                        value: "Area",
                        align: "center",
                        sortable: false,

                    },
                    {
                        text: " ",
                        sortable: false,
                    }
                ],
                datosFamilia: [],
                titulofamiliares: [{
                        text: "Nombre",
                        sortable: false,
                        value: "nombre"
                    },
                    {
                        text: "Tipo Documento",
                        sortable: false,
                        value: "tipo_documento"
                    },
                    {
                        text: "Documento",
                        sortable: false,
                        value: "num_documento"
                    },
                    {
                        text: "Fecha Nacimiento",
                        sortable: false,
                        value: "fecha_nacimiento"
                    },
                    {
                        text: "Genero",
                        sortable: false,
                        value: "genero"
                    },
                    {
                        text: "Edad",
                        sortable: false,
                        value: "edad"
                    },
                    {
                        text: "Escolaridad",
                        sortable: false,
                        value: "escolaridad"
                    },
                    {
                        text: "Profecion",
                        sortable: false,
                        value: "profesion"
                    },
                    {
                        text: "Depende del Empleado",
                        sortable: false,
                        value: "profesion"
                    },
                    {
                        text: "Vive con Empleado",
                        sortable: false,
                        value: "profesion"
                    },
                ],
                datosDirectorio: [],
                empleado: {
                    fecha_exp_documento: "",
                    fecha_nacimiento: ""
                },
                municipio: [],
                Eps: [],
                estados: [],
                Area: [],
                familias: {},
                tipoFamiliar: [],
                estadocivil: ["solter@", "casad@", "divorciad@", "Viod@"],
                etnias: ["Ninguna", "Inginena", "Gitan", "Afroamericano", "Araizal"],
                documento: ["CC", "CE", "PA", "NIT"],
                sangre: ["A+", "A-", "AB+", "AB-", "B+", "B-", "O+", "O-"],
                estrato: [{
                        id: 1,
                        nombre: "1"
                    },
                    {
                        id: 2,
                        nombre: "2"
                    },
                    {
                        id: 3,
                        nombre: "3"
                    },
                    {
                        id: 4,
                        nombre: "4"
                    },
                    {
                        id: 5,
                        nombre: "5"
                    },
                    {
                        id: 6,
                        nombre: "6"
                    }
                ],
                discapacidades: [
                    "Ninguna",
                    "Discapacidad Fisica",
                    "Discapacidad Auditiva",
                    "Discapacidad Visual",
                    "Discapacidad Sordomudo",
                    "Discapacidad Intelectual",
                    "Discapacidad Psicosocial",
                    "Discapacidad Multiple"
                ],
                cabezaFamilia: ["Si", "No"],
                discapacidad: ["Si", "No"],
                contacto: ["Si", "No"],
                familia: ["Si", "No"],
                Genero: ["Masculino","Femenino", "otro"],
                nivel: [
                    "Preescolar",
                    "educación básica primaria",
                    "básica secundaria",
                    "educación media",
                    "Educación post-universitaria"
                ],
                editarempleado: {
                    fecha_exp_documento: "",
                    fecha_nacimiento: ""
                },
                editarempleadofechanacimiento: false,
                editarempleadofechaexpedicion: false,
                dialogfamiliaE: false,
                Editarfamilias: {},
                cambioestado: {},
                familiar: {},

            }
        },
        mounted() {
            /* Directorio */
            this.empleados();
            this.Municipio();
            this.eps();
            this.area();
            this.Estado();
            this.tipofamiliar();
        },
        computed: {
            ...mapGetters(['can']),
            // ...mapState(['user']),
            ...mapGetters(['fullName', 'can', 'avatar', 'UserEmail', 'id']),
            nameUser() {
                return this.id;
            }
        },
        methods: {
            empleados() {
                axios.get("api/intranet/directorio").then(res => {
                    this.datosDirectorio = res.data;
                });
            },
            Municipio() {
                axios.get("api/municipio/all").then(res => {
                    this.municipio = res.data;
                });
            },
            eps() {
                axios.get("api/intranet/eps").then(res => {
                    this.Eps = res.data;
                });
            },
            area() {
                axios.get("api/intranet/area").then(res => {
                    this.Area = res.data;
                });
            },
            Estado() {
                axios.get("api/estado/all").then(res => {
                    this.estados = res.data;
                });
            },
            tipofamiliar() {
                axios.get("api/intranet/tipoFamilia").then(res => {
                    this.tipoFamiliar = res.data;
                });
            },
            Guardardirectorio() {
                axios
                    .post("/api/intranet/dialogo", this.empleado)
                    .then(res => {
                        swal({
                            title: "EMPLEADO CREADO CON EXITO!",
                            text: "  ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.dialog = false;
                        this.empleados();
                        this.limpiarCamposdirectorio();
                    })
                    .catch(e => {
                        let errors = e.response.data.errors;
                        let mensaje = "ERROR NO ENCANTRADO!";

                        if (errors.hasOwnProperty("Nombre")) {
                            mensaje = errors.Nombre[0];
                        } else if (errors.hasOwnProperty("Documento")) {
                            mensaje = errors.Documento[0];
                        } else if (errors.hasOwnProperty("correo")) {
                            mensaje = errors.correo[0];
                        } else if (errors.hasOwnProperty("celular")) {
                            mensaje = errors.celular[0];
                        } else if (errors.hasOwnProperty("eps_id")) {
                            mensaje = errors.eps_id[0];
                        } else if (errors.hasOwnProperty("estado_id")) {
                            mensaje = errors.estado_id[0];
                        } else if (errors.hasOwnProperty("correo_corporativo")) {
                            mensaje = errors.correo_corporativo[0];
                        } else if (errors.hasOwnProperty("area_id")) {
                            mensaje = errors.area_id[0];
                        } else if (errors.hasOwnProperty("lugar_nacimiento")) {
                            mensaje = errors.lugar_nacimineto[0];
                        } else if (errors.hasOwnProperty("grupo_sanguineo")) {
                            mensaje = errors.grupo_sanguineo[0];
                        } else if (errors.hasOwnProperty("etnia")) {
                            mensaje = errors.etnia[0];
                        } else if (errors.hasOwnProperty("fecha_nacimiento")) {
                            mensaje = errors.fecha_nacimiento[0];
                        } else if (errors.hasOwnProperty("tipo_discapacidad")) {
                            mensaje = errors.tipo_discapacidad[0];
                        } else if (errors.hasOwnProperty("cabeza_familia")) {
                            mensaje = errors.cabeza_familia[0];
                        } else if (errors.hasOwnProperty("estado_civil")) {
                            mensaje = errors.estado_civil[0];
                        } else if (errors.hasOwnProperty("estatura")) {
                            mensaje = errors.estatura[0];
                        } else if (errors.hasOwnProperty("peso")) {
                            mensaje = errors.peso[0];
                        } else if (errors.hasOwnProperty("direccion_residencia")) {
                            mensaje = errors.direccion_residencia[0];
                        } else if (errors.hasOwnProperty("barrio")) {
                            mensaje = errors.barrio[0];
                        } else if (errors.hasOwnProperty("tipo_vivienda")) {
                            mensaje = errors.tipo_vivienda[0];
                        } else if (errors.hasOwnProperty("estrato")) {
                            mensaje = errors.estrato[0];
                        } else if (errors.hasOwnProperty("municipio_id")) {
                            mensaje = errors.municipio_id[0];
                        } else if (errors.hasOwnProperty("salario")) {
                            mensaje = errors.salario[0];
                        } else if (errors.hasOwnProperty("lugar_exp_documento")) {
                            mensaje = errors.lugar_exp_documento[0];
                        } else if (errors.hasOwnProperty("fecha_exp_documento")) {
                            mensaje = errors.fecha_exp_documento[0];
                        } else if (errors.hasOwnProperty("genero")) {
                            mensaje = errors.genero[0];
                        } else if (errors.hasOwnProperty("correo_corporativo")) {
                            mensaje = errors.correo_corporativo[0];
                        } else if (errors.hasOwnProperty("celular")) {
                            mensaje = errors.celular[0];
                        } else if (errors.hasOwnProperty("lugar_nacimineto")) {
                            mensaje = errors.lugar_nacimineto[0];
                        }
                        swal({
                            title: "¡Error!",
                            text: mensaje,
                            type: "error",
                            timer: 3000,
                            icon: "error",
                            buttons: false
                        });
                    });
            },

            familiares(item) {
                this.empleado.id = item.id
                axios.get(`/api/intranet/lista/${this.empleado.id}`).then(res => {
                    this.datosFamilia = res.data;
                })
            },
            limpiarCamposdirectorio() {
                this.empleado.celular = "";
                this.empleado.correo = "";
                this.empleado.correo_corporativo = "";
                this.empleado.eps_id = "";
                this.empleado.area_id = "";
                this.empleado.tipo_documento = "";
                this.empleado.primer_nombre = "";
                this.empleado.segundo_nombre = "";
                this.empleado.primer_apellido = "";
                this.empleado.segundo_apellido = "";
                this.empleado.lugar_exp_documento = "";
                this.empleado.fecha_exp_documento = "";
                this.empleado.genero = "";
                this.empleado.grupo_sanguineo = "";
                this.empleado.etnia = "";
                this.empleado.fecha_nacimiento = "";
                this.empleado.tipo_discapacidad = "";
                this.empleado.lugar_exp_documento = "";
                this.empleado.lugar_nacimineto = "";
                this.empleado.cabeza_familia = "";
                this.empleado.estado_civil = "";
                this.empleado.estatura = "";
                this.empleado.peso = "";
                this.empleado.direccion_residencia = "";
                this.empleado.barrio = "";
                this.empleado.tipo_vivienda = "";
                this.empleado.estrato = "";
                this.empleado.municipio_id = "";
                this.empleado.salario = "";
                this.empleado.telefono = "";
                this.empleado.nombres = "";
                this.empleado.familiar_id = "";
                this.empleado.tipo_documentos = "";
                this.empleado.num_documento = "";
                this.empleado.fecha_nacimiento = "";
                this.empleado.generos = "";
                this.empleado.escolaridad = "";
                this.empleado.profesion = "";
                this.empleado.depende_empleado = "";
                this.empleado.vivecon_el_empleado = "";
                this.empleado.empleado_id = "";
                this.empleado.edad = "";
                // this.empleado.nombre_documento = "";
            },
            agregarfamiliar(dato) {
                this.familias.empleado_id = dato.id;
                axios.post(`/api/intranet/agregar/${this.familias.empleado_id}`, this.familias)
                    .then(res => {
                        swal({
                            title: "FAMILIAR AGREADO CON EXITO!",
                            text: "  ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.dialogfamilia = false;
                        this.closefamilia();
                    }).catch(e => {
                        let errors = e.response.data.errors;
                        let mensaje = "ERROR NO ENCANTRADO!";

                        if (errors.hasOwnProperty("nombre")) {
                            mensaje = errors.nombre[0];
                        } else if (errors.hasOwnProperty("familiar_id")) {
                            mensaje = errors.familiar_id[0];
                        } else if (errors.hasOwnProperty("tipo_documento")) {
                            mensaje = errors.tipo_documento[0];
                        } else if (errors.hasOwnProperty("num_documento")) {
                            mensaje = errors.num_documento[0];
                        } else if (errors.hasOwnProperty("fecha_nacimiento")) {
                            mensaje = errors.fecha_nacimiento[0];
                        } else if (errors.hasOwnProperty("genero")) {
                            mensaje = errors.genero[0];
                        } else if (errors.hasOwnProperty("escolaridad")) {
                            mensaje = errors.escolaridad[0];
                        } else if (errors.hasOwnProperty("profesion")) {
                            mensaje = errors.profesion[0];
                        } else if (errors.hasOwnProperty("depende_empleado")) {
                            mensaje = errors.depende_empleado[0];
                        } else if (errors.hasOwnProperty("vivecon_el_empleado")) {
                            mensaje = errors.vivecon_el_empleado[0];
                        } else if (errors.hasOwnProperty("edad")) {
                            mensaje = errors.edad[0];
                        }
                        swal({
                            title: "¡Error!",
                            text: mensaje,
                            type: "error",
                            timer: 3000,
                            icon: "error",
                            buttons: false
                        });
                    });
            },
            closefamilia() {
                this.familias.nombre = "";
                this.familias.familiar_id = "";
                this.familias.tipo_documento = "";
                this.familias.num_documento = "";
                this.familias.fecha_nacimiento = "";
                this.familias.genero = "";
                this.familias.escolaridad = "";
                this.familias.profesion = "";
                this.familias.depende_empleado = "";
                this.familias.vivecon_el_empleado = "";
                this.familias.edad = "";
            },
            editarfamiliar(item) {
                this.Editarfamilias.id = item.id;
                this.Editarfamilias.nombre = item.nombre_Familiar;
                this.Editarfamilias.familiar_id = item.familiar_id;
                this.Editarfamilias.tipo_documento = item.tipo_documento;
                this.Editarfamilias.num_documento = item.num_documento;
                this.Editarfamilias.fecha_nacimiento = item.fecha_nacimiento;
                this.Editarfamilias.genero = item.genero;
                this.Editarfamilias.escolaridad = item.escolaridad;
                this.Editarfamilias.profesion = item.profesion;
                this.Editarfamilias.depende_empleado = item.depende_empleado;
                this.Editarfamilias.vivecon_el_empleado = item.vivecon_el_empleado;
                this.Editarfamilias.edad = item.edad;
                this.dialogfamiliaE = true;
            },
            actualizarfamiliar() {
                axios.put(`/api/intranet/editarfamiliar/${this.Editarfamilias.id}`, this.Editarfamilias)
                    .then(res => {
                        swal({
                            title: "CACTUALIZADO CON EXITO!",
                            text: "  ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.dialogfamiliaE = false;

                    })
            },
            editar(items) {
                this.editarempleado.id = items.id;
                this.editarempleado.primer_nombre = items.primer_nombre;
                this.editarempleado.segundo_nombre = items.segundo_nombre;
                this.editarempleado.primer_apellido = items.primer_apellido;
                this.editarempleado.segundo_apellido = items.segundo_apellido;
                this.editarempleado.Documento = items.Documento;
                this.editarempleado.Nombre = items.Nombre;
                this.editarempleado.Nombre_area = items.Nombre_area;
                this.editarempleado.Nombre_eps = items.Nombre_eps;
                this.editarempleado.Nombre_estado = items.Nombre_estado;
                // this.editarempleado.Nombre_municipio = items.Nombre_municipio;
                this.editarempleado.barrio = items.barrio;
                this.editarempleado.cabeza_familia = items.cabeza_familia;
                this.editarempleado.celular = items.celular;
                this.editarempleado.celular2 = items.celular2;
                this.editarempleado.contacto_emergencia = items.contacto_emergencia;
                this.editarempleado.correo = items.correo;
                this.editarempleado.correo_corporativo = items.correo_corporativo;
                this.editarempleado.direccion_residencia = items.direccion_residencia;
                this.editarempleado.discapacidad = items.discapacidad;
                this.editarempleado.estado_civil = items.estado_civil;
                this.editarempleado.estatura = items.estatura;
                this.editarempleado.estrato = items.estrato;
                this.editarempleado.etnia = items.etnia;
                this.editarempleado.familia = items.familia;
                this.editarempleado.fecha_exp_documento = items.fecha_exp_documento;
                this.editarempleado.fecha_nacimiento = items.fecha_nacimiento;
                this.editarempleado.genero = items.genero;
                this.editarempleado.grupo_sanguineo = items.grupo_sanguineo;
                this.editarempleado.area_id = items.idarea;
                this.editarempleado.eps_id = items.ideps;
                this.editarempleado.Estado_id = items.idestado;
                this.editarempleado.municipio_id = items.idmunici;
                this.editarempleado.lugar_exp_documento = items.lugar_exp_documento;
                this.editarempleado.lugar_nacimineto = items.lugar_nacimineto;
                this.editarempleado.nombre_documento = items.nombre_documento;
                this.editarempleado.nombre_foto = items.nombre_foto;
                this.editarempleado.parentesco_contacto = items.parentesco_contacto;
                this.editarempleado.peso = items.peso;
                this.editarempleado.ruta_documento = items.ruta_documento;
                this.editarempleado.ruta_foto = items.ruta_foto;
                this.editarempleado.salario = items.salario;
                this.editarempleado.tel_contacto_emergencia = items.tel_contacto_emergencia;
                this.editarempleado.telefono = items.telefono;
                this.editarempleado.tipo_discapacidad = items.tipo_discapacidad;
                this.editarempleado.tipo_documento = items.tipo_documento;
                this.editarempleado.tipo_vivienda = items.tipo_vivienda;
                this.dialogempleado = true;
            },
            actualizarEmpleado() {
                axios.put(`/api/intranet/editarempleado/${this.editarempleado.id}`, this.editarempleado).then(() => {
                    swal({
                        title: "ACTUALIZADO CON EXITO!",
                        text: "  ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                    this.empleados();
                    this.dialogempleado = false;
                })
            },
            Salir() {
                this.empleado.celular = "";
                this.empleado.Documento = "";
                this.empleado.correo = "";
                this.empleado.correo_corporativo = "";
                this.empleado.eps_id = "";
                this.empleado.area_id = "";
                this.empleado.tipo_documento = "";
                this.empleado.primer_nombre = "";
                this.empleado.segundo_nombre = "";
                this.empleado.primer_apellido = "";
                this.empleado.segundo_apellido = "";
                this.empleado.lugar_exp_documento = "";
                this.empleado.fecha_exp_documento = "";
                this.empleado.genero = "";
                this.empleado.grupo_sanguineo = "";
                this.empleado.etnia = "";
                this.empleado.fecha_nacimiento = "";
                this.empleado.tipo_discapacidad = "";
                this.empleado.lugar_exp_documento = "";
                this.empleado.lugar_nacimineto = "";
                this.empleado.cabeza_familia = "";
                this.empleado.estado_civil = "";
                this.empleado.estatura = "";
                this.empleado.peso = "";
                this.empleado.direccion_residencia = "";
                this.empleado.barrio = "";
                this.empleado.tipo_vivienda = "";
                this.empleado.estrato = "";
                this.empleado.municipio_id = "";
                this.empleado.salario = "";
                this.empleado.telefono = "";
                this.empleado.nombres = "";
                this.empleado.familiar_id = "";
                this.empleado.tipo_documento = "";
                this.empleado.num_documento = "";
                this.empleado.fecha_nacimientos = "";
                this.empleado.generos = "";
                this.empleado.escolaridad = "";
                this.empleado.profesion = "";
                this.empleado.depende_empleado = "";
                this.empleado.vivecon_el_empleado = "";
                this.empleado.empleado_id = "";
                this.empleado.edad = "";
                this.dialog = false;
            },
            cambiar(id) {
                this.cambioestado.Nombre_estado = id.idestado;
                this.cambioestado.id = id.id;

                Swal.fire({
                    title: "¿Está Seguro(a)?",
                    text: "Se cancela la Solicitud!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Guardar"
                }).then(result => {
                    if (result.isConfirmed) {
                        axios.put(`/api/intranet/cambiarestado/${this.cambioestado.id}`, this.cambioestado)
                            .then(res => {
                                this.empleados();
                            });
                        Swal.fire(" ", "El estado se modifico con exito.", "success");
                    }
                });
            },
        }
    }

</script>

<style>

</style>
