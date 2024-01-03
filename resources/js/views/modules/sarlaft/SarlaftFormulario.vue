<template>
    <div>
        <v-layout wrap align-center justify-center>
            <v-flex>
                <v-card max-height="100%" class="mb-3">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">BUSCAR IDENTIFICACIÓN</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md fluid class="pa-0">
                            <v-layout wrap align-center justify-center>
                                <v-flex xs12>
                                    <v-form @submit.prevent="buscarUsuario">
                                        <v-layout row wrap>
                                            <v-flex xs10>
                                                <v-text-field v-model="documentoUsuario" type="number"
                                                    label="Número de Identificacion">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs2>
                                                <v-btn fab outline small color="success" @click="buscarUsuario"
                                                    @keyup="buscarUsuario">
                                                    <v-icon>search</v-icon>
                                                </v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </v-form>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
                <v-card max-height="50%" class="mb-3" v-if="proveedor.id != null && documentoUsuario != ''">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">FORMULARIO DE CONOCIMIENTO DEL
                            CLIENTE / PROVEEDOR</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-container grid-list-md fluid class="pa-2">
                        <v-layout wrap>
                            <v-flex xs6>
                                <v-select :items="Clase" v-model="informacion.tipo_cliente"
                                    label="¿Tipo cliente o proveedor?" required></v-select>
                            </v-flex>
                            <v-flex xs6>
                                <v-select :items="tipo_solicitud" v-model="informacion.tipo_solicitud"
                                    label="Tipo de solicitud" required></v-select>
                            </v-flex>
                            <v-flex xs4>
                                <v-text-field v-model="informacion.nombre_diligencia" label="Nombre De Quien Diligencia"
                                    v-validate="'required'" required>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4>
                                <v-text-field v-model="informacion.cedula_diligencia" label="Numero de Identificacion" required>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4>
                                <v-text-field v-if="informacion.tipo_cliente !='Cliente Usuario Particular'" v-model="informacion.cargo_diligencia" label="Cargo" required>
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
                <v-card max-height="50%" class="mb-3" v-if="proveedor.id != null && documentoUsuario != ''">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">INFORMACION GENERAL</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <form @submit.prevent="guardarInformacion">
                        <v-card-text>
                            <v-container grid-list-md fluid>
                                <v-layout wrap>
                                    <v-flex xs4>
                                        <v-text-field v-model="tipo_doc" readonly label="Tipo De Documento">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="Num_Doc" label="Numero De Documento">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="nombre" label="Nombre/Razón Social:">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4 v-show="informacion.tipo_cliente == 'Proveedor'">
                                        <v-select :items="Clase_Prov" v-model="informacion.clase"
                                            label="Clase De Proveedor">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4 v-show="informacion.tipo_cliente == 'Proveedor'">
                                        <v-select :items="Sector_Prov" v-model="informacion.sector" label="Sector">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4 v-show="informacion.tipo_cliente == 'Proveedor'">
                                        <v-text-field v-model="informacion.cual_descripcion" label="¿Cual?">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4 v-show="informacion.tipo_cliente != 'Proveedor'">
                                        <v-select :items="Clase_Cli" v-model="informacion.clase"
                                            label="Clase De Cliente">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4 v-show="informacion.tipo_cliente != 'Proveedor'">
                                        <v-select :items="Sector_Cli" v-model="informacion.sector" label="Sector">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4 v-show="informacion.tipo_cliente == 'Cliente'">
                                        <v-text-field v-model="informacion.cual_descripcion" label="¿Cual?">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-text-field v-model="informacion.tipo_bienservicio"
                                            label="Tipo bien/servicio a adquirir o proveer: " required></v-text-field>
                                    </v-flex>
                                    <v-flex xs4 v-show="informacion.tipo_cliente != 'Cliente Usuario Particular'" >
                                        <v-text-field v-model="informacion.direccion" label="Dirección Principal:"
                                            >
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4 v-show="informacion.tipo_cliente != 'Cliente Usuario Particular'">
                                        <v-autocomplete v-model="informacion.municipio_id" append-icon="search"
                                            :items="municipios" label="Buscar Municipio" item-text="Nombre"
                                            item-value="id" v-validate="'required'" >
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4 v-show="informacion.tipo_cliente != 'Cliente Usuario Particular'">
                                        <v-autocomplete v-model="informacion.departamento_id" append-icon="search"
                                            :items="departamento" label="Buscar Deparamentos" item-text="Nombre"
                                            item-value="id" v-validate="'required'" ></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="informacion.email_empresa"
                                            label="Correo Electronico" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="informacion.telefono_empresa"
                                            label="Telefono" required>
                                        </v-text-field>
                                    </v-flex>
                                     <v-flex xs4>
                                        <v-text-field v-model="informacion.numero_contacto" label="Numero De Contacto"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4 v-show="informacion.tipo_cliente != 'Cliente Usuario Particular'">
                                        <v-text-field v-model="informacion.fax" label="Fax"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="info" type="submit">Guardar</v-btn>
                        </v-card-actions>
                    </form>
                </v-card>
                <v-card max-height="50%" class="mb-3" v-if="proveedor.id != null && documentoUsuario != '' && informacion.tipo_cliente !='Cliente Usuario Particular'">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">REPRESENTANTE LEGAL</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <form @submit.prevent="guardarRepresentante">
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout wrap>
                                    <v-flex xs4>
                                        <v-text-field v-model="representante.nombre" label="Nombre Completo:" >
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-select :items="Tipo_Doc" v-model="representante.tipo_doc"
                                            label="Tipo Documento:" v-validate="'required'" >
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="representante.num_doc" label="Numero:" >
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field prepend-icon="calendar_today" label="Fecha De Expedición"
                                            type="date" color="primary" v-model="representante.fecha_exp" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-autocomplete v-model="representante.lugar_exp" append-icon="search"
                                            :items="municipios" label="Buscar Lugar De Expedición" item-text="Nombre"
                                            item-value="id" v-validate="'required'" required></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field prepend-icon="calendar_today" label="Fecha De Nacimiento"
                                            type="date" color="primary" v-model="representante.fecha_nac" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-autocomplete v-model="representante.lugar_nac" append-icon="search"
                                            :items="municipios" label="Buscar Lugar De Nacimiento" item-text="Nombre"
                                            item-value="id" v-validate="'required'" required></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="representante.otra_nacionalidad"
                                            label="¿Tiene Otra Nacionalidad?">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="representante.emali" label="Correo Electronico" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-autocomplete v-model="representante.ciudad_reci" append-icon="search"
                                            :items="municipios" label="Buscar Ciudad De Residencia" item-text="Nombre"
                                            item-value="id" v-validate="'required'" required></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-autocomplete v-model="representante.deparamento_reci" append-icon="search"
                                            :items="departamento" label="Buscar Deparamentos De Residencia"
                                            item-text="Nombre" item-value="id" v-validate="'required'" required>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="representante.direccion_reci"
                                            label="Dirección Principal:" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="representante.pais_reci" label="Pais De Residencia"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="representante.telefono" label="Numero De Contacto"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-label><strong>¿Por Su Cargo O Actividad Maneja Recursos Públicos?</strong>
                                        </v-label>
                                        <v-radio-group v-model="representante.cargo_publico">
                                            <v-radio v-for="n in afirmacion" :key="n.id" :label="`${n.nombre}`"
                                                :value="n.id" color="info" required></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-label><strong>¿Por Su Cargo O Actividad Ejerce Algún Poder Público?</strong>
                                        </v-label>
                                        <v-radio-group v-model="representante.poder_publico">
                                            <v-radio v-for="n in afirmacion" :key="n.id" :label="`${n.nombre}`"
                                                :value="n.id" color="info" required></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-label><strong>¿Por Su Actividad U Oficio, Goza Usted De Reconocimiento
                                                Público
                                                General?</strong></v-label>
                                        <v-radio-group v-model="representante.reconocimento_publico">
                                            <v-radio v-for="n in afirmacion" :key="n.id" :label="`${n.nombre}`"
                                                :value="n.id" color="info" required></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-label><strong>¿Es Usted Sujeto De Obligaciones Tributarias En Otro País O
                                                Grupo
                                                De
                                                Países?</strong></v-label>
                                        <v-radio-group v-model="representante.obli_tibutarias">
                                            <v-radio v-for="n in afirmacion" :key="n.id" :label="`${n.nombre}`"
                                                :value="n.id" color="info" required></v-radio>
                                        </v-radio-group>
                                        <v-textarea label="Indique El Pais"
                                            v-show="parseInt(representante.obli_tibutarias) === 1"
                                            v-model="representante.descripcion_obliga"></v-textarea>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="info" type="submit">Guardar</v-btn>
                        </v-card-actions>
                    </form>
                </v-card>
                <v-card max-height="50%" class="mb-3" v-if="proveedor.id != null && documentoUsuario != '' && informacion.tipo_cliente =='Cliente Usuario Particular'">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">INFORMACION COMPLEMENTARIA</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <form @submit.prevent="guardarRepresentante">
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout wrap>
                                    <v-flex xs4>
                                        <v-text-field prepend-icon="calendar_today" label="Fecha De Expedición"
                                            type="date" color="primary" v-model="representante.fecha_exp" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field prepend-icon="calendar_today" label="Fecha De Nacimiento"
                                            type="date" color="primary" v-model="representante.fecha_nac" required>
                                        </v-text-field>
                                    </v-flex>
                                     <v-flex xs4>
                                        <v-autocomplete v-model="representante.lugar_nac" append-icon="search"
                                            :items="municipios" label="Buscar Lugar De Nacimiento" item-text="Nombre"
                                            item-value="id" v-validate="'required'" required></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="representante.otra_nacionalidad"
                                            label="¿Tiene Otra Nacionalidad?">
                                        </v-text-field>
                                    </v-flex>
                                     <v-flex xs4>
                                        <v-text-field v-model="representante.pais_reci" label="Pais De Residencia"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-autocomplete v-model="representante.deparamento_reci" append-icon="search"
                                            :items="departamento" label="Buscar Deparamentos De Residencia"
                                            item-text="Nombre" item-value="id" v-validate="'required'" required>
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-autocomplete v-model="representante.ciudad_reci" append-icon="search"
                                            :items="municipios" label="Buscar Ciudad De Residencia" item-text="Nombre"
                                            item-value="id" v-validate="'required'" required></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="representante.ocupacion"
                                            label="Ocupación, oficio o profesión">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="representante.donde_trabaja"
                                            label="Empresa donde trabaja">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="representante.emali" label="Correo Electronico" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-label><strong>¿Por Su Cargo O Actividad Maneja Recursos Públicos?</strong>
                                        </v-label>
                                        <v-radio-group v-model="representante.cargo_publico">
                                            <v-radio v-for="n in afirmacion" :key="n.id" :label="`${n.nombre}`"
                                                :value="n.id" color="info" required></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-label><strong>¿Por Su Cargo O Actividad Ejerce Algún Poder Público?</strong>
                                        </v-label>
                                        <v-radio-group v-model="representante.poder_publico">
                                            <v-radio v-for="n in afirmacion" :key="n.id" :label="`${n.nombre}`"
                                                :value="n.id" color="info" required></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-label><strong>¿Por Su Actividad U Oficio, Goza Usted De Reconocimiento
                                                Público
                                                General?</strong></v-label>
                                        <v-radio-group v-model="representante.reconocimento_publico">
                                            <v-radio v-for="n in afirmacion" :key="n.id" :label="`${n.nombre}`"
                                                :value="n.id" color="info" required></v-radio>
                                        </v-radio-group>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-label><strong>¿Es Usted Sujeto De Obligaciones Tributarias En Otro País O
                                                Grupo
                                                De
                                                Países?</strong></v-label>
                                        <v-radio-group v-model="representante.obli_tibutarias">
                                            <v-radio v-for="n in afirmacion" :key="n.id" :label="`${n.nombre}`"
                                                :value="n.id" color="info" required></v-radio>
                                        </v-radio-group>
                                        <v-textarea label="Indique El Pais"
                                            v-show="parseInt(representante.obli_tibutarias) === 1"
                                            v-model="representante.descripcion_obliga"></v-textarea>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="info" type="submit">Guardar</v-btn>
                        </v-card-actions>
                    </form>
                </v-card>
                <v-card max-height="50%" class="mb-3" v-if="proveedor.id != null && documentoUsuario != '' && informacion.tipo_cliente !='Cliente Usuario Particular'">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">IDENTIFICACIÓN DE LOS SOCIOS,
                            ACCIONISTAS O ASOCIADOS </span>
                    </v-card-title>
                    <v-card class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Solo Para Aquellos Que Posean Una
                            Participación Mayor Del 5%</span>
                    </v-card>
                    <form @submit.prevent="guardarSocio">
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout wrap>
                                    <v-flex xs4>
                                        <v-text-field v-model="socio.razon_social"
                                            label="Razón social o Nombre completo" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-select :items="Tipo_Doc" v-model="socio.tipo_doc"
                                            label="Tipo de identificación" required>
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="socio.num_doc" label="Número" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="socio.participacion" label="% Participación" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-select :items="pregunta" v-model="socio.descripcion_expuevincula"
                                            label="¿Es persona públicamente expuesta, o vinculado con una de ellas?"
                                            required>
                                        </v-select>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="info" type="submit">Agregar</v-btn>
                        </v-card-actions>
                    </form>
                    <template>
                        <v-data-table :headers="headers" :items="listSocios" class="mx-2">
                            <template v-slot:items="props">
                                <td class="text-xs-left">{{ props.item.razon_social }}</td>
                                <td class="text-xs-left">{{ props.item.tipo_doc }}</td>
                                <td class="text-xs-left">{{ props.item.num_doc }}</td>
                                <td class="text-xs-left">{{ props.item.participacion }}</td>
                                <td class="text-xs-left">{{ props.item.descripcion_expuevincula }}</td>
                                <td class="text-xs-left">
                                    <v-btn color="error" @click="eliminarSocios(props.item.id)">Eliminar</v-btn>
                                </td>
                            </template>
                        </v-data-table>
                    </template>
                </v-card>
                <v-card max-height="50%" class="mb-3" v-if="proveedor.id != null && documentoUsuario != '' && informacion.tipo_cliente !='Cliente Usuario Particular'">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Conocimiento mejorado de Personas
                            Expuestas Públicamente</span>
                    </v-card-title>
                    <v-card class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Teniendo en cuenta lo anterior,
                            complete
                            la siguiente información si aplica</span>
                    </v-card>
                    <form @submit.prevent="guardarPersonaExpuesta">
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout wrap>
                                    <v-flex xs4>
                                        <v-text-field v-model="persona_expuesta.razon_social"
                                            label="Razón social o Nombre completo" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="persona_expuesta.nacionalidad" label="Nacionalidad"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-select :items="vinvulo" v-model="persona_expuesta.relacion" label="Vinculo/Relación"
                                            required>
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="persona_expuesta.entidad" label="Entidad" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="persona_expuesta.cargo" label="Cargo" required>
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="info" type="submit">Agregar</v-btn>
                        </v-card-actions>
                    </form>
                    <template>
                        <v-data-table :headers="headers2" :items="listPersonaExpuesta" class="mx-2">
                            <template v-slot:items="props">
                                <td class="text-xs-left">{{ props.item.razon_social }}</td>
                                <td class="text-xs-left">{{ props.item.nacionalidad }}</td>
                                <td class="text-xs-left">{{ props.item.relacion }}</td>
                                <td class="text-xs-left">{{ props.item.entidad }}</td>
                                <td class="text-xs-left">{{ props.item.cargo }}</td>
                                <td class="text-xs-left">
                                    <v-btn color="error" @click="eliminarPersonaExpuesta(props.item.id)">Eliminar
                                    </v-btn>
                                </td>
                            </template>
                        </v-data-table>
                    </template>
                </v-card>
                <v-card max-height="50%" class="mb-3" v-if="proveedor.id != null && documentoUsuario != ''">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">INFORMACIÓN FINACIERA</span>
                    </v-card-title>
                    <form @submit.prevent="guardarInformacionFinaciera">
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout wrap>
                                    <v-flex xs4>
                                        <v-text-field v-model="informacionFinaciera.total_activos"
                                            label="Total Activos (último balance)" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="informacionFinaciera.total_pasivos"
                                            label="Total Pasivos (último balance)" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="informacionFinaciera.ingreso_mensual"
                                            label="Ingresos Mensuales (promedio mensual)" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="informacionFinaciera.otros_ingresos"
                                            label="Otros ingresos" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="informacionFinaciera.concepto_ingreso" label="Concepto"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="informacionFinaciera.egresos_mensuales"
                                            label="Egresos Mensuales (promedio mensual)" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="informacionFinaciera.otros_egresos" label="Otros egresos"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="informacionFinaciera.concepto_egreso" label="Concepto"
                                            required>
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="info" type="submit">Guardar</v-btn>
                        </v-card-actions>
                    </form>
                </v-card>
                <v-card max-height="50%" class="mb-3" v-if="proveedor.id != null && documentoUsuario != ''">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">ACTIVIDADES EN OPERACIONAES
                            INTERNACIONALES</span>
                    </v-card-title>
                    <form @submit.prevent="guardarActividadesInternacionales">
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout wrap>
                                    <v-flex xs4>
                                        <v-select :items="pregunta" v-model="actividadesInternacionales.transa_monedaextr"
                                            label="¿Realiza transacciones en moneda extranjera?" required>
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-select :items="actividadCual" v-model="actividadesInternacionales.cual_moneda" label="Cuál?"
                                            required>
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-if="actividadesInternacionales.cual_moneda =='OTRAS'" v-model="actividadesInternacionales.otra_moneda"
                                            label="Indique otras">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-select :items="pregunta" v-model="actividadesInternacionales.produc_extranjeros"
                                            label="¿Posee productos financieros en el exterior?" required>
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-select :items="pregunta" v-model="actividadesInternacionales.cual_prodc"
                                            label="¿Posee cuentas en moneda extranjera?" required>
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field v-model="actividadesInternacionales.pais_operaciones"
                                            label="País de operaciones extranjeras" required></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="info" type="submit">Guardar</v-btn>
                        </v-card-actions>
                    </form>
                </v-card>
                <v-card max-height="50%" class="mb-3" v-if="proveedor.id != null && documentoUsuario != ''">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">DECLARACIÓN DE ORIGEN DE
                            FONDOS</span>
                    </v-card-title>
                    <form @submit.prevent="guardarDeclaracionFondos">
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout wrap>
                                    <v-flex xs12 style="color:black">
                                        <span style="font-size:15px">
                                            1. Los recursos que poseo provienen de las siguientes fuentes: (Detalle
                                            ocupación,
                                            oficio, actividad económica o negocio).
                                        </span>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-textarea outline name="input-7-4" v-model="decalracionFondos.declaracion"
                                            required>
                                        </v-textarea>
                                        <v-flex xs12 style="color:black">
                                            <span style="font-size:15px">
                                                2. Tanto mi actividad, profesión u oficio es licita y la ejerzo dentro
                                                del
                                                marco
                                                legal y los recursos que poseo no provienen de actividades ilícitas de
                                                las
                                                contempladas en el Código Penal Colombiano.
                                            </span>
                                        </v-flex>
                                        <v-flex xs12 style="color:black">
                                            <span style="font-size:15px">
                                                3. La información que he suministrado en la solicitud y en este
                                                documento es
                                                veraz y verificable y me obligo a actualizarla anualmente.
                                            </span>
                                        </v-flex>
                                        <v-flex xs12 style="color:black">
                                            <span style="font-size:15px">
                                                4. Autorizo a Sumimedical SAS para solicitar, consultar, procesar,
                                                suministrar,
                                                reportar o divulgar a cualquier entidad con la que mantenga una relación
                                                comercial vigente o que se encuentre debidamente autorizada para manejar
                                                o
                                                administrar bases de datos, incluidas las entidades gubernamentales, la
                                                información contenida en este Formulario. De igual forma, autorizo a
                                                Sumimedical
                                                SAS a realizar la debida validación en listas restrictivas tanto
                                                nacionales
                                                como
                                                internacionales.
                                            </span>
                                        </v-flex>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </form>
                    <v-card-actions>
                        <v-dialog v-model="dialog" width="500">
                            <template v-slot:activator="{ on }">
                                <v-btn color="success" v-on="on">Acepto y Autorizo
                                </v-btn>
                            </template>
                            <v-card>
                                <v-card-title class="headline red lighten-2 justify-center" style="color:white"
                                    primary-title>
                                    LEER CON ATENCION!
                                </v-card-title>

                                <v-card-text style="color:black">
                                    Una vez autorizado, deberá imprimir el formato, firmar y colocar huella del
                                    representante legal. Adicionalmente, debe adjuntar este formato escaneado con la
                                    documentación anexa solicitada.
                                </v-card-text>
                                <v-divider></v-divider>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="primary" type="submit" flat
                                        @click="guardarDeclaracionFondos(), dialog = false">
                                        Imprimir y Continuar
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-card-actions>
                </v-card>
                <v-card max-height="50%" class="mb-3" v-if="proveedor.id != null && documentoUsuario != ''">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">DOCUMENTACIÓN ANEXA</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md fluid class="pa-0">
                            <v-layout wrap>
                                <v-flex xs12 style="color:black">
                                    <span style="font-size:15px">
                                        Este formato debe ser diligenciado en su totalidad y devuelto a Sumimedical SAS
                                        anexando la siguiente documentación, teniendo en cuenta que según el tipo de
                                        vinculación se podría solicitar documentación adicional:
                                    </span>
                                </v-flex>
                                <v-flex xs4 sm5>
                                    <span style="color:black">1. Registro Único Tributario - RUT</span>
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <input id="registroUnico" ref="registroUnico" type="file" />
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <span style="color:red">Los archivos deben tener un tamaño máximo de 10MB</span>
                                </v-flex>
                                <v-flex xs12 sm5>
                                    <span style="color:black">2. Copia cédula representante legal o persona
                                        natural</span>
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <input id="cedualRepresentante" ref="cedualRepresentante" type="file" />
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <span style="color:red">Los archivos deben tener un tamaño máximo de 10MB</span>
                                </v-flex>
                                <v-flex xs12 sm5>
                                    <span style="color:black">3. Certificado de existencia y representación legal (menor
                                        a
                                        30 días de expedición)</span>
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <input id="certificadoRepresentacion" ref="certificadoRepresentacion" type="file" />
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <span style="color:red">Los archivos deben tener un tamaño máximo de 10MB</span>
                                </v-flex>
                                <v-flex xs12 sm5>
                                    <span style="color:black">4. Decreto de nombramiento y acta de posesión (para
                                        entidades
                                        públicas)</span>
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <input id="decretoPosesion" ref="decretoPosesion" type="file" />
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <span style="color:red">Los archivos deben tener un tamaño máximo de 10MB</span>
                                </v-flex>
                                <v-flex xs12 sm5>
                                    <span style="color:black">5. Certificación bancaria original de la cuenta inscrita
                                        en el
                                        formulario</span>
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <input id="certificacionBancaria" ref="certificacionBancaria" type="file" />
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <span style="color:red">Los archivos deben tener un tamaño máximo de 10MB</span>
                                </v-flex>
                                <v-flex xs12 sm5>
                                    <span style="color:black">6. Formulario Diligenciado</span>
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <input id="formatoDiligenciado" ref="formatoDiligenciado" type="file" />
                                </v-flex>
                                <v-flex xs4 sm3>
                                    <span style="color:red">Los archivos deben tener un tamaño máximo de 10MB</span>
                                </v-flex>
                                <v-flex xs12 style="color:black">
                                    <span style="font-size:15px">
                                        Si al momento de diligenciar el formato se presenta cualquier inquietud, por
                                        favor
                                        comunicarse al (4) 520 1040 - ext. 105
                                    </span>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn color="info" @click="guardarAdjuntos">Guardar Adjuntos</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>

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

<script>
    import Swal from 'sweetalert2';
    import {
        mapGetters
    } from "vuex";
    import moment from "moment";
    import {
        EventBus
    } from "../../../event-bus.js";

    export default {
        components: {},
        data() {
            return {
                dialog: false,
                idSarlaft: '',
                listSocios: [],
                listPersonaExpuesta: [],
                informacion: {
                    tipo_solicitud: null,
                    tipo_cliente: null,
                    clase: null,
                    municipio_id: null,
                    sector: null,
                    cual_descripcion: null,
                    tipo_bienservicio: null,
                    direccion: null,
                    nombre_diligencia: null,
                    cedula_diligencia: null,
                    cargo_diligencia: null,
                    departamento_id: null,
                    email_empresa: null,
                    telefono_empresa: null,
                    fax: null,
                    numero_contacto: null,
                    user_id: null,
                },
                representante: {
                    nombre: null,
                    tipo_doc: null,
                    num_doc: null,
                    fecha_exp: null,
                    fecha_nac: null,
                    lugar_nac: null,
                    lugar_exp: null,
                    otra_nacionalidad: null,
                    emali: null,
                    ciudad_reci: null,
                    deparamento_reci: null,
                    direccion_reci: null,
                    pais_reci: null,
                    telefono: null,
                    cargo_publico: null,
                    poder_publico: null,
                    reconocimento_publico: null,
                    obli_tibutarias: null,
                    descripcion_obliga: null,
                    sarlafs_id: null,
                    ocupacion: null,
                    donde_trabaja: null,
                },
                socio: {
                    razon_social: null,
                    tipo_doc: null,
                    num_doc: null,
                    participacion: null,
                    descripcion_expuevincula: null,
                },
                persona_expuesta: {
                    razon_social: null,
                    nacionalidad: null,
                    relacion: null,
                    entidad: null,
                    cargo: null,
                },
                informacionFinaciera: {
                    total_activos: null,
                    total_pasivos: null,
                    ingreso_mensual: null,
                    otros_ingresos: null,
                    concepto_ingreso: null,
                    egresos_mensuales: null,
                    otros_egresos: null,
                    concepto_egreso: null,
                },
                actividadesInternacionales: {
                    transa_monedaextr: null,
                    cual_moneda: null,
                    otra_moneda: null,
                    produc_extranjeros: null,
                    cual_prodc: null,
                    pais_operaciones: null,
                },
                decalracionFondos: {
                    declaracion: null,
                },
                datosProveedor: null,
                documentoUsuario: null,
                proveedor: '',
                headers: [{
                        text: 'Razón social o Nombre completo',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'Tipo de identificación',
                        value: 'calories'
                    },
                    {
                        text: 'Número',
                        value: 'fat'
                    },
                    {
                        text: '% Participación',
                        value: 'carbs'
                    },
                    {
                        text: '¿ Es persona públicamente expuesta, o vinculado con una de ellas?',
                        value: 'protein'
                    },
                    {
                        text: '',
                        value: ''
                    },
                ],
                headers2: [{
                        text: 'Razón social o Nombre completo',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'Nacionalidad',
                        value: 'Nacionalidad'
                    },
                    {
                        text: 'Vinculo/Relación',
                        value: 'Vinculo/Relación'
                    },
                    {
                        text: 'Entidad',
                        value: 'Entidad'
                    },
                    {
                        text: 'Cargo',
                        value: 'Cargo'
                    },
                    {
                        text: '',
                        value: ''
                    },
                ],
                afirmacion: [{
                    id: 1,
                    nombre: 'SI'
                }, {
                    id: 0,
                    nombre: 'NO'
                }],
                Clase: ['Proveedor', 'Cliente', 'Cliente Usuario Particular'],
                tipo_solicitud: ['Vinculacion Primera Vez', 'Actualizacion Novedad', 'Actualizacion Anual'],
                Tipo_Doc: ['CC', 'CE', 'NIT', 'PA'],
                tipo_doc: null,
                actividadCual :['IMPORTACIONES','EXPORTACIONES', "TRANSFERENCIAS","INVERSIONES","OTRAS" ],
                vinvulo:['REPRESENTANTE LEGAL','MIEMBRO JUNTA DIRECTIVA','ACCIONISTA'],
                nombre: null,
                Num_Doc: null,
                pregunta : ['SI','NO'],
                Clase_Prov: ['PROVEEDOR DE PRODUCTOS', 'PROVEEDOR DE SERVICIOS', 'PROVEEDOR DE RECURSOS', 'N/A'],
                Sector_Prov: ['COMERCIO', 'CONSTRUCCION', 'FINANCIERO', 'INDUSTRIAL', 'SERVICIOS', 'SALUD',
                    'TRANSPORTE', 'OTROS'
                ],
                Cual: "",
                Clase_Cli: ['SERVICIOS DE SALUD', 'OTROS', 'N/A'],
                Sector_Cli: ['COMERCIO', 'CONSTRUCCION', 'FINANCIERO', 'INDUSTRIAL', 'SERVICIOS', 'SALUD', 'TRANSPORTE',
                    'OTROS'
                ],
                data: {
                    departamento_id: "",
                    Municipio_id: "",
                    fecha_expedicion: moment().format("YYYY-MM-DD"),
                    fecha_nacimiento: moment().format("YYYY-MM-DD"),
                },
                municipios: '',
                departamento: [],
                preload: false

            };
        },
        mounted() {
            this.fetchMunicipios();
            this.fetchDepartamento();
        },
        methods: {
            guardarInformacion() {
                this.preload = true;
                axios.post('/api/user/guardarInformacion/', this.informacion)
                    .then(res => {
                        this.$alerSuccess("Informacion General Guardado con exito!");
                        this.preload = false;
                        this.idSarlaft = res.data.id
                    }).catch(er => {
                        this.preload = false;
                        this.$alerError("Error al guardar!");
                    });
            },
            guardarRepresentante() {
                this.preload = true;
                this.representante.sarlafs_id = this.idSarlaft;
                axios.post('/api/user/guardarRepresentante/', this.representante)
                    .then(res => {
                        this.$alerSuccess("Representante Legal guardado con exito!");
                        this.preload = false;
                    }).catch(er => {
                        this.preload = false;
                        this.$alerError("Error al guardar!");
                    });
            },
            clearSocio() {
                for (const prop of Object.getOwnPropertyNames(this.socio)) {
                    this.socio[prop] = null;
                }
            },
            guardarSocio() {
                this.socio.sarlafs_id = this.idSarlaft
                axios.post('/api/user/guardarSocio/', this.socio)
                    .then(res => {
                        this.$alerSuccess("Socio guardado con exito!");
                        this.clearSocio();
                        this.listSocios = res.data
                    })
            },

            eliminarSocios(id) {
                axios.get('/api/user/eliminarSocios/' + id, this.listSocios)
                    .then(res => {
                        this.listSocios = res.data
                    })
            },
            clearPersonaExpuesta() {
                for (const prop of Object.getOwnPropertyNames(this.persona_expuesta)) {
                    this.persona_expuesta[prop] = null;
                }
            },
            guardarPersonaExpuesta() {
                this.persona_expuesta.sarlafs_id = this.idSarlaft
                axios.post('/api/user/guardarPersonaExpuesta/', this.persona_expuesta)
                    .then(res => {
                        this.$alerSuccess("Persona Expuesta guardado con exito!");
                        this.clearPersonaExpuesta();
                        this.listPersonaExpuesta = res.data
                    })
            },

            eliminarPersonaExpuesta(id) {
                axios.get('/api/user/eliminarPersonaExpuesta/' + id, this.listPersonaExpuesta)
                    .then(res => {
                        this.listPersonaExpuesta = res.data
                    })
            },
            guardarInformacionFinaciera() {
                this.preload = true;
                this.informacionFinaciera.sarlafs_id = this.idSarlaft
                axios.post('/api/user/guardarInformacionFinaciera/', this.informacionFinaciera)
                    .then(res => {
                        this.$alerSuccess("Informacion Financiera guardado con exito!");
                        this.preload = false;
                    }).catch(er => {
                        this.preload = false;
                        this.$alerError("Error al guardar!");
                    });
            },
            guardarActividadesInternacionales() {
                this.preload = true;
                this.actividadesInternacionales.sarlafs_id = this.idSarlaft
                axios.post('/api/user/guardarActividadesInternacionales/', this.actividadesInternacionales)
                    .then(res => {
                        this.$alerSuccess("Actividad Internacional guardada con exito!");
                        this.preload = false;
                    }).catch(error => {
                        this.preload = false;
                        this.$alerError("Error al guardar!");
                    });
            },
            guardarDeclaracionFondos() {
                this.preload = true;
                this.decalracionFondos.sarlafs_id = this.idSarlaft
                axios.post('/api/user/guardarDeclaracionFondos/', this.decalracionFondos)
                    .then(res => {
                        this.$alerSuccess("Declaracion de fondos aceptada y guardada con exito!");
                        this.preload = false;
                        this.imprimir(this.idSarlaft);
                    }).catch(error => {
                        this.preload = false;
                        this.$alerError("Error al guardar!");
                    });
            },
            buscarUsuario() {
                if (!this.documentoUsuario) {
                    this.$alerError('Debe ingresar una identificación');
                    return;
                }
                axios.get('/api/user/proveedor/' + this.documentoUsuario).then(res => {
                    if (res.data.paciente) {
                        this.proveedor = res.data.paciente;
                        this.informacion.user_id = this.proveedor.id
                        this.tipo_doc = this.proveedor.nit
                        this.Num_Doc = this.proveedor.cedula
                        this.nombre = this.proveedor.name + " " + this.proveedor.apellido
                    } else {
                        this.$alerError(res.data.message);
                    }
                })
            },
            fetchMunicipios() {
                axios.get('/api/municipio/all')
                    .then(res => this.municipios = res.data);
            },
            fetchDepartamento() {
                axios.get('/api/departamento/all')
                    .then(res => this.departamento = res.data);
            },
            saveIdentificacionSocios() {
                axios.post().then()
            },
            guardarAdjuntos() {
                this.preload = true;
                let formData = new FormData();
                formData.append(`registroUnico`, this.$refs.registroUnico.files[0]);
                formData.append(`cedualRepresentante`, this.$refs.cedualRepresentante.files[0]);
                formData.append(`certificadoRepresentacion`, this.$refs.certificadoRepresentacion.files[0]);
                formData.append(`decretoPosesion`, this.$refs.decretoPosesion.files[0]);
                formData.append(`certificacionBancaria`, this.$refs.certificacionBancaria.files[0]);
                formData.append(`formatoDiligenciado`, this.$refs.formatoDiligenciado.files[0]);

                axios.post('/api/user/guardarAdjuntos/' + this.idSarlaft, formData).then(res => {
                    this.preload = false;
                    this.$alerSuccess("Adjuntos guardados con exito!");

                }).catch(error => {
                    this.preload = false;
                    this.$alerError("Error al guardar!");
                });
            },
            async imprimir(id) {
                const pdf = {
                    type: 'Sarlafts',
                    id: id
                }
                axios
                    .post("/api/pdf/print-pdf", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    })
                    .catch(err => console.log(err.response));
            }
        }
    };

</script>
<style lang="scss" scoped>
</style>
