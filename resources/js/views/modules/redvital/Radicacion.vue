<template>
    <v-content>

        <v-layout v-show="formulario" class="mt-5 my-5">
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm12 md10>
                        <v-card class="elevation-12">
                            <v-toolbar dark color="redvitalVerde">
                                <v-flex class="text-xs-center" flat dark>
                                    <v-toolbar-title>Radicación
                                    </v-toolbar-title>
                                </v-flex>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs6 sm2 md2>
                                            <v-text-field readonly v-model="paciente.Num_Doc" label="Documento"
                                                required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6 sm3 md3>
                                            <v-text-field readonly v-model="paciente.Primer_Nom" label="Nombre"
                                                required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6 sm3 md3>
                                            <v-text-field readonly v-model="paciente.Primer_Ape" label="Primer Apellido"
                                                required></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 sm4 md4>
                                            <v-text-field readonly v-model="paciente.Segundo_Ape"
                                                label="Segundo Apellido" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm8 md8>
                                            <v-text-field readonly v-model="paciente.IPS" label="Sede" required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm4 md4>
                                            <v-text-field v-model="paciente.Telefono" label="Telefono" required
                                                type="number"
                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="7" min="1">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm2 md2>
                                            <v-text-field v-model="paciente.celular" label="Celular" type="number"
                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="10" min="1">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm2 md2>
                                            <v-text-field v-model="paciente.celular2" label="Celular 2" type="number"
                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="10" min="1">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm4 md4>
                                            <v-text-field label="Email" v-model="paciente.correo" required type="email">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm4 md4>
                                            <v-text-field label="Dirección" v-model="paciente.Direccion_Residencia"
                                                required type="email">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm12 md12>
                                            <v-autocomplete :items="tipoSolicitudes" @click="getTipos" return-object
                                                item-text="nombre" label="Tipo de Solicitud" v-model="tipoSolicitud"
                                                multiple @input="cantidadAdjuntos(), infoTipoSolicitud()">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm12 md12 v-for="(item, index) in cantidadAdjunto" :key="index">
                                            <v-textarea :label="'Observación ' + item.nombre" v-model="item.observacion"
                                                :rules="maxObservacion" :counter="300" outline>
                                            </v-textarea>
                                            <input :id="item.id" multiple ref="adjuntos" type="file" />
                                            <span><strong>adjunte {{ item.nombre }}</strong></span>
                                            <v-spacer class="mt-2"></v-spacer>
                                        </v-flex>
                                        <v-flex xs12 sm12 md12>
                                            <v-checkbox v-model="checkbox" color="redvitalAzul"
                                                label="Con el diligenciamiento de este formato autorizo expresamente el uso de mis datos personales según Ley 1581 de 2012">
                                            </v-checkbox>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="redvitalVerde" dark @click="enviarFormulario()">Enviar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-layout>

        <v-layout class="mt-5 my-5" v-show="consultarEstado">
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm12 md10>
                        <v-card class="elevation-12">

                            <v-card>
                                <v-bottom-nav :value="true" color="transparent">
                                    <v-btn color="primary" flat @click="informacion()">
                                        <span>Información</span>
                                        <v-icon>info</v-icon>
                                    </v-btn>
                                    <v-btn color="primary" flat @click="openRadicado()">
                                        <span>Radicado</span>
                                        <v-icon>checklist</v-icon>
                                    </v-btn>
                                    <v-btn color="primary" flat @click="openOrden()">
                                        <span>Ordenes</span>
                                        <v-icon>local_pharmacy</v-icon>
                                    </v-btn>
                                </v-bottom-nav>
                            </v-card>

                            <v-card v-show="showinformacion" class="py-4">
                                <v-layout row wrap>
                                    <v-flex xs12 sm12 md6 px-2>
                                        <v-layout row wrap class="py-4">
                                            <v-flex xs12 md12 px-2>
                                                <v-card class="mx-auto" max-width="500">
                                                    <v-card-title>
                                                        <v-icon large left>
                                                            ballot
                                                        </v-icon>
                                                        <span class="title font-weight-light">IPS</span>
                                                    </v-card-title>

                                                    <v-card-text>
                                                        <p> <strong>Nombre: </strong> {{ paciente.IPS }} </p>
                                                        <p> <strong>Dirección: </strong> {{ paciente.direccionIPS }}</p>
                                                        <p> <strong>Telefono: </strong> {{ paciente.telefonoIPS }} -
                                                            {{ paciente.telefono2IPS }}</p>
                                                    </v-card-text>
                                                </v-card>

                                                <v-spacer class="py-2"></v-spacer>

                                                <v-card class="mx-auto" max-width="500"
                                                    v-show="paciente.nombreMedicoFamilia">
                                                    <v-card-title>
                                                        <v-icon large left>
                                                            person
                                                        </v-icon>
                                                        <span class="title font-weight-light">Medico de Familia</span>
                                                    </v-card-title>

                                                    <v-card-text>
                                                        <p> <strong>Nombre: </strong> {{ paciente.nombreMedicoFamilia }}
                                                            {{  paciente.apellidoMedicoFamilia }}</p>
                                                        <p>
                                                            <v-text-field label="Correo" readonly
                                                                v-model="paciente.Correo1">
                                                            </v-text-field>
                                                        </p>
                                                    </v-card-text>
                                                </v-card>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex xs12 sm12 md6 px-2>
                                        <v-card>
                                            <v-toolbar color="primary" flat dark>
                                                <v-flex xs12 text-xs-center>
                                                    <v-toolbar-title>Actualizar datos de contacto</v-toolbar-title>
                                                </v-flex>
                                            </v-toolbar>
                                            <v-layout row wrap>
                                                <v-flex xs12 md12 text-xs-justify>
                                                    <span>Para nosotros es de vital importancia mantener los datos de
                                                        contacto actualizados
                                                        por si requerimos contactarnos con usted, muchas gracias.
                                                    </span>
                                                </v-flex>
                                                <v-flex xs12 md12 px-2>
                                                    <v-text-field label="Correo" v-model="paciente.Correo1">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md6 px-2>
                                                    <v-text-field label="Celular" type="number" maxlength="10"
                                                        v-model="paciente.Celular1"
                                                        oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                        onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                        min="1">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md6 px-2>
                                                    <v-text-field label="Telefono" type="number"
                                                        v-model="paciente.Telefono"
                                                        onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                        oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                        maxlength="7" min="1">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md12 px-2>
                                                    <v-text-field label="Dirección"
                                                        v-model="paciente.Direccion_Residencia">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md12 px-2>
                                                    <v-checkbox color="redvitalAzul" v-model="habeas"
                                                        label="Con el diligenciamiento de este formato autorizo expresamente el uso de mis datos personales según Ley 1581 de 2012">
                                                    </v-checkbox>
                                                </v-flex>
                                                <v-flex xs12 md3 px-1>
                                                    <v-btn small dark color="redvitalVerde" @click="updateDatos()">
                                                        Actualizar
                                                    </v-btn>
                                                </v-flex>
                                            </v-layout>
                                        </v-card>
                                    </v-flex>
                                </v-layout>
                            </v-card>

                            <v-card v-show="showRadicado">
                                <v-card-title>
                                    <v-text-field label="Desde (Fecha Radicación)" v-model="fechaDesde" type="date"
                                        color="primary" hide-details>
                                    </v-text-field>
                                    <v-btn hide-details small color="success" dark @click="getRadicado()">Buscar
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="searchRadicado" append-icon="search"
                                        label="Filtrar por (Radicado, Solicitud)" single-line hide-details>
                                    </v-text-field>
                                </v-card-title>
                                <v-card-text>
                                    <v-data-table :loading="loading" :headers="headersRadicado" :items="allradicados"
                                        :search="searchRadicado">
                                        <template v-slot:items="props">
                                            <td class="text-xs-left">{{ props.item.id }}</td>
                                            <td class="text-xs-left">{{ props.item.nombreSolicitud }}</td>
                                            <td class="text-xs-left">{{ props.item.created_at.split('.')[0] }}</td>
                                            <td class="text-xs-left">{{ props.item.estado | estadoRadicado }}</td>
                                            <td>
                                                <v-btn small color="primary" dark @click="openSolicitud(props.item)">Ver
                                                </v-btn>
                                            </td>
                                        </template>
                                        <template v-slot:no-results>
                                            <v-alert :value="true" color="error" icon="warning">
                                                Su búsqueda de "{{ searchRadicado }}" no encontró resultados.
                                            </v-alert>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>

                            <v-card v-show="showOrden">
                                <v-card-title>
                                    <v-flex xs6 md6 sm6>
                                        <v-select v-model="tipoOrden" label="Tipo"
                                            :items="[{id:0,Nombre:'MEDICAMENTOS'},{id:1,Nombre:'SERVICIOS'},{id:2,Nombre:'INCAPACIDADES'}]"
                                            item-text="Nombre" item-value="id" @change="Ordenes = []" hide-details>
                                        </v-select>
                                    </v-flex>
                                    <v-btn hide-details small color="success" dark @click="getOrdenes()">Buscar
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="searchOrden" append-icon="search"
                                        label="Filtrar por (# Orden)" single-line hide-details>
                                    </v-text-field>
                                </v-card-title>
                                <v-card-text v-show="tipoOrden === 0">
                                    <v-data-table :loading="loading" :headers="headersMedicamento" :items="Ordenes"
                                        :search="searchOrden" rowsPerPageText="Filas por página"
                                        :rows-per-page-items=[10]>
                                        <template v-slot:items="props">
                                            <tr>
                                                <td class="text-xs-center">{{ props.item.id }}</td>
                                                <td class="text-xs-center">{{ props.item.Fechaorden | date }}
                                                </td>
                                                <td class="text-xs-center">{{ props.item.paginacion }}</td>
                                                <td class="text-xs-center"
                                                    v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7'">
                                                    <v-chip color="primary" text-color="white">Disponible
                                                    </v-chip>
                                                </td>
                                                <td class="text-xs-center">
                                                    <v-btn outline color="danger" round small
                                                        @click="print(props.item, 1)">Imprimir
                                                    </v-btn>
                                                </td>
                                            </tr>
                                        </template>
                                        <template v-slot:no-results>
                                            <v-alert :value="true" color="error" icon="warning">
                                                Su búsqueda de "{{ searchOrden }}" no encontró resultados.
                                            </v-alert>
                                        </template>
                                    </v-data-table>
                                </v-card-text>

                                <v-card-text v-show="tipoOrden === 1">
                                    <v-data-table :loading="loading" :headers="headersServicio" :items="Ordenes"
                                        :search="searchOrden" rowsPerPageText="Filas por página"
                                        :rows-per-page-items=[10]>
                                        <template v-slot:items="props">
                                            <tr>
                                                <td class="text-xs-center">{{props.item.orden}}</td>
                                                <td class="text-xs-center" v-if="props.item.FechaOrdenamiento">
                                                    {{props.item.FechaOrdenamiento.substring(0,10)}}</td>
                                                <td class="text-xs-center">{{props.item.Nombre}}</td>
                                                <td class="text-xs-center"
                                                    v-if="props.item.Estado_id == '1' || props.item.Estado_id == '7'">
                                                    <v-chip color="primary" text-color="white">Disponible
                                                    </v-chip>
                                                </td>
                                                <td class="text-xs-center">
                                                    <v-btn outline color="danger" round small
                                                        @click="imprimir(props.item.orden)">Imprimir
                                                    </v-btn>
                                                </td>
                                            </tr>
                                        </template>
                                        <template v-slot:no-results>
                                            <v-alert :value="true" color="error" icon="warning">
                                                Su búsqueda de "{{ searchOrden }}" no encontró resultados.
                                            </v-alert>
                                        </template>
                                    </v-data-table>
                                </v-card-text>

                                <v-card-text v-show="tipoOrden === 2">
                                    <v-data-table :loading="loading" :headers="headersIncapacidades" :items="Ordenes"
                                        :search="searchOrden" rowsPerPageText="Filas por página"
                                        :rows-per-page-items=[10]>
                                        <template v-slot:items="props">
                                            <td class="text-xs-center">{{ props.item.Orden_id }}</td>
                                            <td class="text-xs-center">{{ props.item.Dias }}</td>
                                            <td class="text-xs-center">{{ props.item.Fechainicio }}</td>
                                            <td class="text-xs-center">{{ props.item.Fechafinal }}</td>
                                            <td class="text-xs-center">
                                                <v-chip v-if="props.item.Estado === 'Activo'" class="ma-2" color="green"
                                                    text-color="white">
                                                    <v-icon dark small>done </v-icon>Disponible
                                                </v-chip>
                                                <v-chip v-if="props.item.Estado === 'Anulado'" class="ma-2" color="red"
                                                    text-color="white">
                                                    <v-icon dark small>close</v-icon>Anulado
                                                </v-chip>
                                            </td>
                                            <td class="text-xs-center">
                                                <v-btn outline color="danger" round small
                                                    v-if="props.item.Estado == 'Activo'"
                                                    @click="imprimirIncapacidad(props.item)">Imprimir
                                                </v-btn>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card-text>

                            </v-card>

                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-layout>

        <v-layout row justify-center>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm12 md6>
                        <v-dialog v-model="mensajechat" persistent max-width="500px">
                            <v-card>
                                <v-toolbar color="redvitalVerde" dark class="mb-3">
                                    <v-toolbar-title>Importante</v-toolbar-title>
                                </v-toolbar>
                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <strong class="subheading">
                                                <p>
                                                    Señor(a) {{ paciente.Primer_Nom }} {{ paciente.Primer_Ape }}
                                                    {{ paciente.Segundo_Ape }}
                                                    <p>
                                                        Es importante conocer las observaciones del
                                                        servicio,
                                                        pues nos permite implementar las acciones de mejoras para cada
                                                        día
                                                        fortalecer
                                                        la atención a nuestros usuarios. En este modulo usted puede
                                                        consultar el
                                                        estado de
                                                        su radicación o ir al formulario para realizar la radicación.
                                                    </p>
                                                    Que desea hacer ?
                                            </strong>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions class="text-center">
                                    <v-spacer></v-spacer>
                                    <v-btn color="info" dark @click="openEstado()">Consultar</v-btn>
                                    <v-btn color="red" dark @click="openFormulario()">Radicar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-layout>

        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogRadicado" width="500" persistent>
            <v-card>

                <v-card-title class="headline redvitalVerde" style="color:white" primary-title>
                    Exito!
                </v-card-title>

                <v-card-text>
                    <p>
                        Sr(a) {{ this.paciente.Primer_Nom }} su solicitud fue radicada con
                        éxito, la respuesta será enviada a su correo electrónico dando cumplimiento a
                        los tiempos de oportunidad.
                    </p>
                    <p v-for="(item, index) in radicados" :key="index">
                        <v-chip label color="redvitalAzul" text-color="white">
                            <v-icon left>label</v-icon>Radicado # {{ item.radicado }}
                        </v-chip>
                        <span>Solicitud {{ item.solicitud }}</span>
                    </p>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" flat @click="dialogRadicado = false, goHome()">
                        ok
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogInfo" width="500" persistent>
            <v-card>

                <v-card-title class="headline redvitalAzul" style="color:white" primary-title>
                    Información!
                </v-card-title>

                <v-card-text>
                    <p>
                        Sr(a) {{ this.paciente.Primer_Nom }} debe tener en cuenta que para su
                        solicitud debe facilitar los siguientes archivos.
                    </p>
                    <p v-for="(item, index) in tipoSolicitud" :key="index">
                        <v-chip label color="warning" text-color="white">
                            <v-icon left>label</v-icon>{{ item.nombre }}
                        </v-chip>
                        <span>{{ item.descripcion }}</span>
                    </p>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" flat @click="dialogInfo = false">
                        ok
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogSolicitud" v-if="dialogSolicitud" persistent width="1000">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title>Radicado # {{ solicitud.id }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs6>
                                <v-text-field v-model="solicitud.nombreSolicitud" readonly label="Tipo Solicitud">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-textarea v-model="solicitud.descripcion" readonly label="Observación">
                                </v-textarea>
                                <span><strong>Fecha: </strong>
                                    {{ solicitud.created_at.split('.')[0] }}</span>
                            </v-flex>
                            <v-flex>
                                <v-btn v-for="(adjuntoR, index) in solicitud.adjuntoradicado" :key="index"
                                    :disabled="search_adjunto">
                                    <a @click="consultarAdjunto(adjuntoR.ruta)">
                                        <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                        {{index+1}}
                                    </a>
                                </v-btn>
                                <v-divider class="my-4"></v-divider>
                            </v-flex>
                            <v-flex xs12 v-for="(comenpublic, i) in comentarioPublico" :key="`res1-${i}`">
                                <v-flex xs12>
                                    <v-flex xs12 v-if="i < 1">
                                        <v-toolbar color="primary" dark class="mb-4">
                                            <v-toolbar-title>Observaciones</v-toolbar-title>
                                        </v-toolbar>
                                    </v-flex>
                                    <v-textarea v-model="comenpublic.motivo" readonly>
                                        <template v-slot:label>
                                            <div>
                                                MOTIVO
                                            </div>
                                        </template>
                                    </v-textarea>
                                    <span><strong v-if="comenpublic.name">Nombre: </strong> {{ comenpublic.name }}
                                        {{ comenpublic.apellido }}
                                        <strong v-if="!comenpublic.name">Paciente </strong>
                                        <strong>Fecha: </strong> {{ comenpublic.created_at.split('.')[0] }}
                                    </span>
                                    <v-flex>
                                        <v-btn v-for="(data, index3) in comenpublic.adjuntosgestion" :key="index3"
                                            :disabled="search_adjunto">
                                            <a @click="consultarAdjunto(data.ruta)">
                                                <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                {{index3+1}}
                                            </a>
                                        </v-btn>
                                    </v-flex>
                                    <v-divider class="my-2"></v-divider>
                                </v-flex>
                            </v-flex>
                            <v-flex v-if="solicitud.estado != 'Cerrado'">
                                <v-flex xs12>
                                    <v-toolbar color="primary" dark class="mb-5">
                                        <v-toolbar-title>Nueva Observación</v-toolbar-title>
                                    </v-toolbar>
                                </v-flex>
                                <v-flex xs12>
                                    <v-textarea v-model="observacion">
                                        <template v-slot:label>
                                            <div>
                                                Observación
                                            </div>
                                        </template>
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12>
                                    <input id="adjuntos" multiple ref="adjuntos" type="file" />
                                </v-flex>
                            </v-flex>
                            <v-flex v-if="solicitud.estado == 'Cerrado'">
                                <v-flex xs12>
                                    <v-toolbar color="success" dark class="mb-4">
                                        <v-toolbar-title>Solución</v-toolbar-title>
                                    </v-toolbar>
                                </v-flex>
                                <v-flex v-for="(res, i) in solicitud.gestion" :key="`res-${i}`">
                                    <v-textarea v-model="res.motivo" readonly>
                                        <template v-slot:label>
                                            <div>
                                                RESPUESTA
                                            </div>
                                        </template>
                                    </v-textarea>
                                    <span><strong>Nombre: </strong> {{ res.name }}
                                        {{ res.apellido }}
                                        <strong>Fecha: </strong> {{ res.created_at.split('.')[0] }}
                                    </span>
                                    <v-flex>
                                        <v-btn v-for="(datag, index7) in res.adjuntosgestion" :key="index7"
                                            :disabled="search_adjunto">
                                            <a @click="consultarAdjunto(datag.ruta)">
                                                <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                {{index7+1}}
                                            </a>
                                        </v-btn>
                                    </v-flex>
                                </v-flex>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="dialogSolicitud = false">
                        Salir
                    </v-btn>
                    <v-btn color="success" @click="comentar()" v-if="solicitud.estado != 'Cerrado'">
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-content>
</template>
<script>
    import Vue from 'vue';
    import moment from "moment";
    moment.locale("es");
    import Swal from 'sweetalert2';
    import {
        AdjuntoService
    } from '../../../services';
    export default {
        name: "Radicacion",
        props: {
            paciente: Object
        },
        data() {
            return {
                formulario: false,
                consultarEstado: false,
                adjuntos: [],
                radicado: '',
                mensajechat: true,
                tipoSolicitudes: [],
                tipoSolicitud: [],
                cantidadAdjunto: [],
                radicados: [],
                dialogRadicado: false,
                preload: false,
                dialogInfo: false,
                observaciones: [],
                maxObservacion: [
                    v => v.length <= 300 || 'Maximo de caracteres 300',
                ],
                showinformacion: false,
                checkbox: false,
                habeas: false,
                showRadicado: false,
                showOrden: false,
                allradicados: [],
                searchRadicado: '',
                loading: false,
                fechaDesde: moment().format('YYYY-MM-DD'),
                solicitud: [],
                dialogSolicitud: false,
                search_adjunto: false,
                comentarioPublico: [],
                observacion: '',
                tipoOrden: 0,
                Ordenes: [],
                searchOrden: '',
                medicamentos: [],
                headersRadicado: [{
                        text: 'Radicado',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Solicitud',
                        align: 'left',
                        value: 'nombreSolicitud',
                        sortable: false
                    },
                    {
                        text: 'Fecha',
                        align: 'left',
                        value: 'created_at'
                    },
                    {
                        text: 'Estado',
                        align: 'left',
                        value: 'estado',
                        sortable: false
                    },
                    {
                        text: '',
                        align: 'left',
                        sortable: false
                    }
                ],
                headersMedicamento: [{
                        text: "Número Orden",
                        align: "center",
                        value: "id"
                    },
                    {
                        text: "Fecha",
                        align: "center",
                        value: ""
                    },
                    {
                        text: "Página",
                        align: "center",
                        value: ""
                    },
                    {
                        text: "Estado",
                        align: "center",
                        value: "",
                        sortable: false
                    },
                    {
                        text: "",
                        align: "center",
                        value: "",
                        sortable: false
                    }
                ],
                headersServicio: [{
                        text: "# Orden",
                        align: 'center',
                        value: "orden"
                    },
                    {
                        text: "Fecha",
                        align: 'center',
                        sortable: false,
                        value: "Fechaorden"
                    },
                    {
                        text: "Nombre",
                        align: 'center',
                        value: "Nombre"
                    },
                    {
                        text: "Estado",
                        align: 'center',
                        value: "Estado",
                        sortable: false
                    },
                    {
                        text: "",
                        align: 'center',
                        sortable: false
                    }
                ],
                headersIncapacidades: [{
                        text: '# Orden',
                        align: "center",
                        value: "Orden_id"
                    },
                    {
                        text: 'Días',
                        align: "center"
                    },
                    {
                        text: 'F. Inicio',
                        align: "center",
                        value: 'Fechainicio'
                    },
                    {
                        text: 'F. Final',
                        align: "center",
                        sortable: false
                    },
                    {
                        text: 'Estado',
                        align: "center",
                        value: 'Estado',
                        sortable: false
                    },
                    {
                        text: "",
                        align: 'center',
                        sortable: false
                    }
                ],
            }
        },
        filters: {
            estadoRadicado(estado) {
                switch (estado) {
                    case 'Cerrado':
                        return "Solucionado";
                    case 'Parcial':
                        return "Gestionando"
                    default:
                        return "Pendiente"
                }
            },
            date: Fechaorden => {
                return moment(Fechaorden).format("LL");
            },
        },
        methods: {
            getTipos() {
                axios.get('/api/redvital/getTipoSolicitud')
                    .then(res => {
                        this.tipoSolicitudes = res.data.map(ts => {
                            return {
                                id: ts.id,
                                nombre: ts.nombre,
                                descripcion: ts.descripcion,
                                observacion: ''
                            }
                        })
                    });
            },
            cantidadAdjuntos() {
                this.cantidadAdjunto = this.tipoSolicitud
            },
            goHome() {
                this.$router.push("/gestion")
            },
            openEstado() {
                this.consultarEstado = true
                this.showinformacion = true
                this.mensajechat = false
            },
            openFormulario() {
                this.mensajechat = false
                Swal.fire({
                    title: 'Aviso',
                    text: 'Señor usuario recuerde que para recibir respuesta a su solicitud debe actualizar sus datos de contacto actuales.',
                    icon: 'info',
                    allowOutsideClick: false
                })
                this.formulario = true
            },
            async enviarFormulario() {
                if (this.checkbox == false) {
                    swal({
                        title: "¡Debe aceptar el uso de sus datos personales según Ley 1581 de 2012!",
                        text: ` `,
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return
                }
                if (this.paciente.correo == '' |
                    this.paciente.correo == undefined |
                    this.paciente.celular == '' |
                    this.paciente.celular == undefined |
                    this.paciente.Direccion_Residencia == '' |
                    this.paciente.Direccion_Residencia == undefined) {
                    this.$alerError(
                        "No actualizo los datos obligatorios de contacto, revise celular, email o dirección")
                    return;
                }
                var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                if (!regex.test(this.paciente.correo)) {
                    swal({
                        title: "Debe ingresar un email valido",
                        icon: "warning"
                    });
                    return;
                }
                if (this.tipoSolicitud.length == 0) {
                    this.$alerError("Debe seleccionar un tipo de solicitud!")
                    return;
                }
                this.adjuntos = this.$refs.adjuntos
                let validationAdjunto = false;
                for (let index = 0; index < this.adjuntos.length; index++) {
                    let filesize = 0;
                    for (let i = 0; i < this.adjuntos[index].files.length; i++) {
                        filesize += this.adjuntos[index].files[i].size
                        if (filesize > 8000000) {
                            let solicitud = this.tipoSolicitud.find(s => s.id == this.adjuntos[index].id);
                            Swal.fire({
                                title: 'Error',
                                text: `Los adjuntos de la solicitud ${solicitud.nombre} superan el peso de 8MB.`,
                                icon: 'error',
                                allowOutsideClick: false
                            })
                            validationAdjunto = true
                            return;
                        }
                    }
                }
                if (validationAdjunto == false) {
                    this.preload = true
                    let error = false;
                    for (let index = 0; index < this.adjuntos.length; index++) {
                        let solicitud = this.tipoSolicitud.find(s => s.id == this.adjuntos[index].id);
                        let formData = new FormData();
                        formData.append('paciente_id', this.paciente.id);
                        formData.append('documento', this.paciente.Num_Doc);
                        formData.append('descripcion', solicitud.observacion);
                        formData.append('solicitud_id', solicitud.id);
                        formData.append('solicitud_nombre', solicitud.nombre);
                        for (let i = 0; i < this.adjuntos[index].files.length; i++) {
                            if (this.radicados.length == 0) {
                                formData.append(this.adjuntos[index].id + '[]',
                                    this.adjuntos[index].files[i]);
                            } else {
                                let find = this.radicados.find(r => r.solicitud_id == this.adjuntos[index].id)
                                if (!find) {
                                    formData.append(this.adjuntos[index].id + '[]',
                                        this.adjuntos[index].files[i]);
                                }
                            }
                        }
                        await axios.post('/api/redvital/radicacion', formData).then((res) => {
                            this.radicados.push(res.data);
                        }).catch((err) => {
                            error = true;
                        })
                    }
                    if (error == true) {
                        this.preload = false
                        Swal.fire({
                            icon: 'warning',
                            title: "¡No puede ser!",
                            text: `Error de radicación, comunicate con el área de atención al usuario!`,
                        })
                    } else if (error == false) {
                        this.paciente.Correo1 = this.paciente.correo
                        this.paciente.Celular1 = this.paciente.celular
                        this.paciente.Celular2 = this.paciente.celular2
                        axios.put(`/api/redvital/updatepaciente/${this.paciente.id}`, this.paciente)
                            .then((res) => {
                                if (res.data.message) {
                                    this.preload = false
                                    this.dialogRadicado = true
                                    this.paciente.correo = ''
                                    this.paciente.celular = ''
                                    this.paciente.celular2 = ''
                                }
                            }).catch((err) => {
                                this.preload = false
                                Swal.fire({
                                    icon: 'warning',
                                    title: "¡No puede ser!",
                                    text: `Error de radicación, comunicate con el área de atención al usuario!`,
                                })
                            })
                    }
                }
            },
            infoTipoSolicitud() {
                if (this.tipoSolicitud.length > 0) {
                    this.dialogInfo = true;
                }
            },
            informacion() {
                this.showOrden = false
                this.showRadicado = false
                this.showinformacion = true
            },
            openRadicado() {
                this.showOrden = false
                this.showinformacion = false
                this.showRadicado = true
            },
            openOrden() {
                this.showinformacion = false
                this.showRadicado = false
                this.showOrden = true
            },
            updateDatos() {
                if (this.paciente.Correo1 == null |
                    this.paciente.Celular1 == null |
                    this.paciente.Direccion_Residencia == null |
                    this.paciente.Correo1 == '' |
                    this.paciente.Celular1 == '' |
                    this.paciente.Direccion_Residencia == '') {
                    this.$alerError("No ha llenado los datos obligatorios!")
                    return;
                }
                if (this.habeas == false) {
                    this.$alerError("Debe aceptar el uso de sus datos personales según Ley 1581 de 2012!")
                    return
                }
                if (this.paciente.Correo1) {
                    var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                    if (!regex.test(this.paciente.Correo1)) {
                        this.$alerError("Debe ingresar un correo valido")
                        return;
                    }
                }
                this.preload = true
                axios.put(`/api/redvital/updatepaciente/${this.paciente.id}`, this.paciente)
                    .then((res) => {
                        this.$alerSuccess('Datos de contacto actualizados con exito!')
                        this.preload = false
                        this.habeas = false
                    }).catch((err) => {
                        this.preload = false
                    })

            },
            getRadicado() {
                if (this.fechaDesde == '') {
                    this.$alerError("Debe ingresar la fecha desde que desea consultar radicados!")
                    return;
                }
                let data = {
                    paciente_id: this.paciente.id,
                    fecha: this.fechaDesde
                }
                axios.post('/api/redvital/getRadicadosPaciente', data)
                    .then(res => {
                        this.allradicados = res.data
                    });
            },
            openSolicitud(item) {
                this.solicitud = item
                this.dialogSolicitud = true
                this.comentariosPublico();
            },
            async consultarAdjunto(ruta) {
                this.search_adjunto = true
                try {
                    let adj = await AdjuntoService.get(ruta);
                    let blob = new Blob([adj[1]], {
                        type: adj[0]
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');
                    a.href = url;
                    window.open(a.href, "_blank");
                    this.search_adjunto = false
                } catch (error) {
                    this.search_adjunto = false
                }
            },
            async comentariosPublico() {
                await axios.post(`/api/solicitud/showcomentariosPublicos`, {
                        radicaciononline_id: this.solicitud.id
                    })
                    .then(res => {
                        this.comentarioPublico = res.data
                    })
            },
            closeAcciones() {
                this.dialogSolicitud = false
                this.$refs.adjuntos.value = ''
                this.observacion = ''
            },
            comentar() {
                this.adjuntos = this.$refs.adjuntos.files
                let filesize = 0;
                for (let index = 0; index < this.adjuntos.length; index++) {
                    filesize += this.adjuntos[index].size
                    if (filesize > 8000000) {
                        Swal.fire({
                            title: 'Error',
                            text: `Los adjuntos de la solicitud superan el peso de 8MB.`,
                            icon: 'error',
                            allowOutsideClick: false
                        })
                        return;
                    }
                }
                if (this.observacion == undefined | this.observacion == '') {
                    this.$alerError('El campo motivo es obligatorio.')
                    return;
                }
                this.preload = true
                let formData = new FormData();
                formData.append('radicaciononline_id', this.solicitud.id)
                formData.append('motivo', this.observacion)
                formData.append('paciente_id', this.solicitud.paciente_id)
                for (let i = 0; i < this.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.adjuntos[i]);
                }
                axios.post(`/api/redvital/comentar`, formData)
                    .then(res => {
                        this.getRadicado();
                        this.preload = false
                        this.closeAcciones();
                        swal({
                            title: "¡Ha agregado la observación con exito!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    }).catch(err => {
                        this.preload = false
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        swal({
                            title: msg,
                            text: " ",
                            type: "error",
                            icon: "error",
                        });
                    })
            },
            getOrdenes() {
                this.preload = true;
                this.Ordenes = [];
                if (this.tipoOrden === 0) {
                    axios.get('/api/redvital/ordenMedicamentos/' + this.paciente.Num_Doc).then(res => {
                        this.Ordenes = res.data;
                        if (this.Ordenes.length <= 0) {
                            Swal.fire({
                                title: 'Aviso',
                                text: 'Señor(a) usuario, en el momento no cuenta con fórmula vigente. Le recomendamos consultar con su médico de atención primaria en caso que requiera continuidad de su tratamiento.',
                                icon: 'info',
                                allowOutsideClick: false
                            })
                        }
                        this.preload = false;
                    }).catch(e => {
                        console.log(e);
                        this.preload = false;
                    })
                } else if (this.tipoOrden === 1) {
                    axios.get('/api/redvital/ordenservicios/' + this.paciente.Num_Doc).then(res => {
                        this.Ordenes = res.data;
                        if (this.Ordenes.length <= 0) {
                            Swal.fire({
                                title: 'Aviso',
                                text: 'Señor(a) usuario, en el momento no cuenta con orden de servicio vigente.',
                                icon: 'info',
                                allowOutsideClick: false
                            })
                        }
                        this.preload = false;
                    }).catch(e => {
                        console.log(e);
                        this.preload = false;
                    })
                } else if (this.tipoOrden === 2) {
                    axios.get('/api/redvital/incapacidades/' + this.paciente.Num_Doc).then(res => {
                        this.Ordenes = res.data;
                        this.preload = false;
                    }).catch(e => {
                        console.log(e);
                        this.preload = false;
                    })
                }
            },
            async print(medicamentos, type) {
                let object = Object.assign({}, medicamentos);
                let objectMipres = Object.assign({}, medicamentos);
                if (type === 1) {
                    const changeCant = deta => ({
                        ...deta,
                        Cantidadpormedico: deta.Cantidadmensualdisponible
                    });
                    await axios.post("/api/redvital/printMedicamentos", {
                            Orden_id: object.id
                        })
                        .then(res => {
                            let obj = res.data.filter(s => !s.mipres);
                            let objMipres = res.data.filter(s => parseInt(s.mipres) === 1);
                            object.detaarticulordens = obj.map(changeCant);
                            objectMipres.detaarticulordens = objMipres.map(changeCant);
                        })
                }

                if (object.paginacion.split('/')[1] === object.paginacion.split('/')[0]) {
                    Swal.fire({
                        title: 'Aviso',
                        text: 'Señor(a) usuario, esta es su última fórmula vigente. Le recomendamos consultar con su médico de atención primaria en caso que requiera continuidad de su tratamiento.',
                        icon: 'info',
                        allowOutsideClick: false
                    })
                }

                if (object.detaarticulordens.length > 0) {
                    var pdf = {};
                    this.fillData(object);
                    pdf = this.getPDFFormula(object);
                    pdf.type = 'formula';
                    axios
                        .post("/api/redvital/print-pdf_redvital", pdf, {
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

                if (objectMipres.detaarticulordens.length > 0) {
                    var pdfMipres = {};
                    this.fillData(objectMipres);
                    pdfMipres = this.getPDFFormula(objectMipres);
                    pdfMipres.type = 'formula';
                    pdfMipres.mensaje = 'M I P R E S';
                    axios
                        .post("/api/redvital/print-pdf_redvital", pdfMipres, {
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

            },
            fillData(autorizacion) {
                this.medicamentos = [];
                autorizacion.detaarticulordens.forEach(med => {
                    let object = {
                        id: med.codesumi_id,
                        nombre: med.medicamento,
                        Cantidadosis: med.Cantidadosis,
                        Unidadmedida: med.Unidadmedida,
                        Via: med.Via,
                        Frecuencia: med.Frecuencia,
                        Unidadtiempo: med.Unidadtiempo,
                        Duracion: med.Duracion,
                        Cantidadmensual: med.Cantidadmensual,
                        NumMeses: med.NumMeses,
                        Observacion: med.Observacion,
                        Cantidadpormedico: med.Cantidadpormedico,
                        PosViaAdmin: med.Via
                    };

                    this.medicamentos.push(object);
                });
            },
            getPDFFormula(medicamentos) {
                return (this.object = {
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
                    orden_id: medicamentos.id,
                    type: "pendiente",
                    medicamentos: this.medicamentos,
                    cita_paciente_id: medicamentos.Cita_id
                });
            },
            imprimir(orden) {
                axios.get('/api/redvital/printServicios/' + orden).then(res => {
                    let hojas = {};
                    let hojasAnexo3 = {};
                    res.data.forEach(s => {
                        hojas[s.Estado] = {
                            'orden_id': s.Orden_id,
                            mensaje: 'N O  V A L I D A',
                            type: "otros",
                            servicios: [],
                            Num_Doc: s.cedula
                        };
                        hojasAnexo3[s.Estado] = {
                            'orden_id': s.Orden_id,
                            mensaje: 'A N E X O  3',
                            type: "otros",
                            servicios: [],
                            Num_Doc: s.cedula
                        };
                    });

                    res.data.forEach(s => {
                        for (const key in hojas) {
                            if (s.Estado == key) {
                                if (!s.anexo3) {
                                    hojas[s.Estado].servicios.push({
                                        codigo: s.Codigo,
                                        nombre: s.Nombre,
                                        cantidad: s.Cantidad,
                                        observacion: s.Observacionorden,
                                        ubicacion: s.ubicacion,
                                        direccion: s.direccion,
                                        telefono: s.telefono,
                                    });
                                } else {
                                    hojasAnexo3[s.Estado].servicios.push({
                                        codigo: s.Codigo,
                                        nombre: s.Nombre,
                                        cantidad: s.Cantidad,
                                        observacion: s.Observacionorden,
                                        ubicacion: s.ubicacion,
                                        direccion: s.direccion,
                                        telefono: s.telefono,
                                    });
                                }
                            }
                        }
                    });

                    for (const key in hojas) {
                        if (hojas[key].servicios.length) {
                            this.getPDF(hojas[key]);
                        }
                        if (hojasAnexo3[key].servicios.length) {
                            this.getPDF(hojasAnexo3[key]);
                        }
                    }
                    this.preload = false;
                }).catch(e => {
                    console.log(e);
                    this.preload = false;
                })
            },
            async getPDF(pdf) {
                this.preload = true;
                axios
                    .post("/api/redvital/print-pdf_redvital", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                        this.preload = false;
                    })
                    .catch(err => {
                        console.log(err.response);
                        this.preload = false;
                    });

            },
            imprimirIncapacidad(inc) {
                let pdf = this.getPDFIncap(inc);
                axios
                    .post("/api/redvital/print-pdf_redvital", pdf, {
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
            },
            getPDFIncap(inc) {
                return (this.pdf = {
                    type: "incapacidad",
                    FechaInicio: inc.Fechainicio,
                    Nombre: inc.Nombre,
                    Edad_Cumplida: inc.Edad_Cumplida,
                    Sexo: inc.Sexo,
                    medico_ordena: inc.Medicoordeno,
                    Celular: inc.Celular,
                    Correo: inc.Correo,
                    Num_Doc: inc.Cedula,
                    cita_paciente_id: inc.citaPaciente_id,
                    orden_id: inc.Orden_id,
                    provincapacidad: inc.Provincapacidad,
                    Fechacreaincapacidad: inc.Fechacreaincapacidad,
                    hj: inc.hj,
                    afilia: inc.afilia,
                    Ut: inc.Ut,
                    ips1: inc.ips1,
                    ma: inc.ma,
                    TTipod: inc.TTipod,
                    Proveedor: inc.Proveedor,
                    dx_principal: inc.Diagnostico.substring(0, 4),
                    CantidadDias: inc.Dias,
                    FechaFinal: inc.Fechafinal,
                    Prorroga: inc.Prorroga,
                    tel: inc.tel,
                    TextCie10: inc.Diagnostico.substring(0, 4),
                    Contingencia: inc.Contingencia,
                    Descripcion: inc.Descripcion,
                    Laboraen: inc.Laboraen,
                    Firma: inc.Firma,
                    especialidad_medico: inc.especialidad_medico,
                    Rmedico: inc.Rmedico,
                    SendEmail: false
                });
            },
        }
    }

</script>
