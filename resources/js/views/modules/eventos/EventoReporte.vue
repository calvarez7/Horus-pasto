<template>
    <div>

        <v-dialog v-model="dialogEvento" persistent max-width="500px">
            <v-card>
                <v-toolbar color="redvitalVerde" dark class="mb-3">
                    <v-toolbar-title>Información</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <strong class="subheading">
                                Conozco el documento del paciente ?
                            </strong>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions class="text-center">
                    <v-spacer></v-spacer>
                    <v-btn color="success" dark @click="tipoPaciente = true, dialogEvento = false">Si
                    </v-btn>
                    <v-btn color="warning" dark @click="indiciodeAtencion()">No
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-container v-show="tipoPaciente">
            <v-layout align-center justify-center>
                <v-flex xs12 sm8 md4>
                    <v-card>
                        <v-card-title class="headline primary" style="color:white">
                            <span class="title layout justify-center font-weight-light">Buscar paciente</span>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-0">
                                <v-layout wrap align-center justify-center>
                                    <v-flex xs12>
                                        <v-form @submit.prevent="search_paciente()">
                                            <v-layout row wrap>
                                                <v-flex xs10>
                                                    <v-text-field v-model="cedula_paciente" label="Número de Documento"
                                                        type="number" maxLength="17"
                                                        oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                        onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.keyCode == 13)"
                                                        min="1">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs2>
                                                    <v-btn @click="search_paciente()" @keyup.enter="search_paciente()"
                                                        v-if="!paciente.id" fab outline small color="success">
                                                        <v-icon>search</v-icon>
                                                    </v-btn>
                                                    <v-btn @click="clearFields()" v-if="paciente.id" fab outline small
                                                        color="error">
                                                        <v-icon>clear</v-icon>
                                                    </v-btn>
                                                </v-flex>
                                            </v-layout>
                                        </v-form>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>

        <v-dialog v-model="crearPaciente" persistent max-width="500">
            <v-card>
                <v-toolbar color="redvitalVerde" dark class="mb-3">
                    <v-toolbar-title>Crear Paciente</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <strong class="subheading">
                                El paciente con el documento <strong>{{ cedula_paciente }}</strong> no se encuentra
                                registrado, antes de crear el paciente verifique que el documento este bien digitado!
                            </strong>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions class="text-center">
                    <v-spacer></v-spacer>
                    <v-btn color="success" dark @click="pacienteCreacion = true,  crearPaciente = false">Crear Paciente
                    </v-btn>
                    <v-btn color="error" dark @click="crearPaciente = false">Salir
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="pacienteCreacion" persistent max-width="1200px">
            <v-card>
                <v-card-title class="headline redvitalVerde" style="color:white">
                    <span class="title layout justify-center font-weight-light">Formulario Nuevo Paciente</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm4 md4>
                                <v-autocomplete :items="entidades" label="Entidad Salud" v-model="formPaciente.entidad"
                                    item-text="nombre" item-value="id" return-object>
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm2 md2>
                                <v-select label="Tipo Documento" :items="tipoDoc" v-model="formPaciente.Tipo_Doc">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm3 md3>
                                <v-text-field label="Número Documento" type="number" v-model="formPaciente.Num_Doc"
                                    readonly>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 md2>
                                <v-text-field label="Fecha Nacimiento" type="date" v-model="formPaciente.Fecha_Naci">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm1 md1>
                                <v-select :items="['F','M']" label="Sexo" required v-model="formPaciente.Sexo">
                                </v-select>
                            </v-flex>
                            <v-flex xs12 sm3 md3>
                                <v-text-field label="Primer Nombre" v-model="formPaciente.Primer_Nom">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm3 md3>
                                <v-text-field label="Segundo Nombre" v-model="formPaciente.SegundoNom">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm3 md3>
                                <v-text-field label="Primer Apellido" v-model="formPaciente.Primer_Ape">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm3 md3>
                                <v-text-field label="Segundo Apellido" v-model="formPaciente.Segundo_Ape">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red" dark @click="pacienteCreacion = false">Salir</v-btn>
                    <v-btn color="success" dark @click="guardar_paciente()">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-card max-height="100%" v-show="paciente.id">
            <v-toolbar flat color="success" dark>
                <v-flex xs12 text-xs-center flat dark>
                    <v-toolbar-title>Reporte de eventos relacionados con la seguridad del
                        paciente</v-toolbar-title>
                </v-flex>
            </v-toolbar>
            <v-card-text>
                <v-layout row wrap>
                    <v-flex xs2 px-1>
                        <v-text-field v-model="paciente.Primer_Nom" readonly label="Primer Nombre"></v-text-field>
                    </v-flex>
                    <v-flex xs2 px-1>
                        <v-text-field v-model="paciente.SegundoNom" readonly label="Segundo Nombre"></v-text-field>
                    </v-flex>
                    <v-flex xs2 px-1>
                        <v-text-field v-model="paciente.Primer_Ape" readonly label="Primer Apellido"></v-text-field>
                    </v-flex>
                    <v-flex xs2 px-1>
                        <v-text-field v-model="paciente.Segundo_Ape" readonly label="Segundo Apellido"></v-text-field>
                    </v-flex>
                    <v-flex xs2 px-1>
                        <v-text-field v-model="paciente.Num_Doc" readonly label="Documento"></v-text-field>
                    </v-flex>
                    <v-flex xs1 px-1>
                        <v-text-field v-model="paciente.Edad_Cumplida" readonly label="Edad"></v-text-field>
                    </v-flex>
                    <v-flex xs1 px-1>
                        <v-text-field v-model="paciente.Sexo" readonly label="Sexo"></v-text-field>
                    </v-flex>
                    <v-flex xs1 px-1>
                        <v-text-field v-model="paciente.Tipo_Afiliado" label="Tipo de Afiliado" ></v-text-field>
                    </v-flex>
                    <v-flex xs4 px-1>
                        <v-text-field v-model="paciente.entidad" readonly label="Entidad"></v-text-field>
                    </v-flex>
                    <v-flex xs6 px-1>
                        <VAutocomplete :items="alleventos" item-value="id" item-text="nombre" label="Nombre del suceso"
                            v-model="evento" @change="clearRelacion()" @input="getclasificacion()" />
                    </v-flex>
                    <v-flex xs6 px-1 v-show="evento == 133">
                        <v-text-field label="Cual (Nombre del suceso)" v-model="otro_evento">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs6 px-1 v-show="allclasificacion.length > 0">
                        <VAutocomplete :items="allclasificacion" item-value="id" item-text="nombre"
                            label="Clasificación área" v-model="clasificacion_area" @input="getTipoevento()" />
                    </v-flex>
                    <v-flex xs6 px-1 v-show="alltipoeventos.length > 0">
                        <VAutocomplete :items="alltipoeventos" item-value="id" item-text="nombre" label="Tipo de evento"
                            v-model="tipo_evento" />
                    </v-flex>
                    <!-- <v-flex xs6>
                        <v-select :items="clasieventos" v-model="clasificacion_evento" label="Clasificación del evento">
                        </v-select>
                    </v-flex> -->
                    <v-flex xs6 px-1>
                        <VAutocomplete :items="sedesCompletas" v-model="sede_ocurrencia" item-value="id"
                            item-text="nombre" label="Sede ocurrencia" />
                    </v-flex>
                    <v-flex xs6 px-1>
                        <VAutocomplete :items="sedesCompletas" v-model="sede_reportante" item-value="id"
                            item-text="nombre" label="Sede reportante" />
                    </v-flex>
                    <v-flex xs6 px-1 v-show="sede_ocurrencia == 704">
                        <VAutocomplete :items="socurrencias" v-model="servicio_ocurrencia"
                            label="Servicio de ocurrencia" />
                    </v-flex>
                    <v-flex xs6 px-1 v-show="sede_reportante == 704">
                        <VAutocomplete :items="socurrencias" v-model="servicio_reportante"
                            label="Servicio reportante" />
                    </v-flex>
                    <v-flex xs3 px-1>
                        <v-text-field label="Fecha de ocurrencia" v-model="fecha_ocurrencia" type="date">
                        </v-text-field>
                    </v-flex>
                    <!-- <v-flex xs3 px-1>
                        <v-select label="Clasificación invima" v-model="clasificacion_invima"
                            :items="['Serio', 'No serio', 'No aplica']">
                        </v-select>
                    </v-flex> -->
                    <v-flex xs6 px-1 v-show="evento == 139">
                        <v-select :items="relacion" v-model="relacionEvento" label="Relacionado con"
                            @input="activarCampos()">
                        </v-select>
                    </v-flex>
                    <v-flex v-show="evento == 109">
                        <v-layout row wrap>
                            <v-flex xs12>
                                <VAutocomplete :items="medicamentos" v-model="medicamento_evento" item-value="id"
                                    item-text="Producto" label="Medicamento" />
                            </v-flex>
                            <!-- <v-flex xs6 px-1>
                                <v-text-field label="Fecha de vencimiento" v-model="fecha_vence_medicamento"
                                    type="date">
                                </v-text-field>
                            </v-flex> -->
                            <!-- <v-flex xs3 px-1>
                                <v-text-field label="Lote" v-model="lote_medicamento">
                                </v-text-field>
                            </v-flex> -->
                            <!-- <v-flex xs3 px-1>
                                <v-text-field label="Invima" v-model="invima_medicamento">
                                </v-text-field>
                            </v-flex> -->
                            <v-flex xs1 px-1>
                                <v-text-field label="Dosis" v-model="dosis_medicamento" type="number"
                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-autocomplete label="Unidad de medida" v-model="medida_medicamento"
                                    :items="unidadesmedida">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-autocomplete label="Via de administración" v-model="via_medicamento"
                                    :items="viasadmin">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-text-field label="Frecuencia de administración" v-model="frecuencia_medicamento">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs2 px-1>
                                <v-text-field label="Tiempo de infusión" v-model="infusion_medicamento">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 v-show="activarDispositivos">
                        <v-layout row wrap>
                            <span>Dispositivos</span>
                            <v-flex xs12>
                                <VAutocomplete :items="insumoDispositivo" v-model="dispositivo" item-value="id"
                                    item-text="Producto" label="Dispositivo/Insumo" />
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-text-field label="Referencia" v-model="referencia_dispositivo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-text-field label="Lote" v-model="lote_dispositivo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-text-field label="Invima" v-model="invima_dispositivo">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 v-show="activarEquipoBio">
                        <v-layout row wrap>
                            <span>Equipo biomédico</span>
                            <v-flex xs12>
                                <v-text-field label="Nombre" v-model="nombre_equipo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4>
                                <v-text-field label="Marca" v-model="marca_equipo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-text-field label="Modelo" v-model="modelo_equipo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-text-field label="Serie" v-model="serie_equipo">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12>
                        <v-textarea label="Descripción de los hechos" v-model="descripcion_hechos">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs7>
                        <v-autocomplete :items="user_activos" label="Profesional tratante" item-text="nombre"
                            item-value="nombre" v-model="profesional" />
                    </v-flex>
                    <v-flex xs12>
                        <v-textarea label="Acciones que se tomaron" v-model="accion_tomada">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12>
                        <input id="adjuntos" multiple ref="adjuntos" type="file" />
                    </v-flex>
                </v-layout>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="success" @click="savevento()">
                    <span>Enviar</span>
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-card max-height="100%" v-show="tipoSinPaciente">
            <v-toolbar flat color="success" dark>
                <v-flex xs12 text-xs-center flat dark>
                    <v-toolbar-title>Reporte de eventos relacionados con la seguridad</v-toolbar-title>
                </v-flex>
            </v-toolbar>
            <v-card-text>
                <v-layout row wrap>
                    <v-flex xs6 px-1>
                        <VAutocomplete :items="alleventos" item-value="id" item-text="nombre" label="Nombre del suceso"
                            v-model="evento" @change="clearRelacion()" @input="getclasificacion()" />
                    </v-flex>
                    <v-flex xs6 px-1 v-show="evento == 133">
                        <v-text-field label="Cual (Nombre del suceso)" v-model="otro_evento">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs6 px-1 v-show="allclasificacion.length > 0">
                        <VAutocomplete :items="allclasificacion" item-value="id" item-text="nombre"
                            label="Clasificación área" v-model="clasificacion_area" @input="getTipoevento()" />
                    </v-flex>
                    <v-flex xs6 px-1 v-show="alltipoeventos.length > 0">
                        <VAutocomplete :items="alltipoeventos" item-value="id" item-text="nombre" label="Tipo de evento"
                            v-model="tipo_evento" />
                    </v-flex>
                    <!-- <v-flex xs6 px-1>
                        <VAutocomplete :items="allclasificacion" item-value="id" item-text="nombre"
                            label="Clasificación área" v-model="clasificacion_area" @input="getTipoevento()" />
                    </v-flex> -->
                    <!-- <v-flex xs6 px-1>
                        <VAutocomplete :items="alltipoeventos" item-value="id" item-text="nombre" label="Tipo de evento"
                            v-model="tipo_evento" />
                    </v-flex> -->
                    <!-- <v-flex xs6>
                        <v-select :items="clasieventos" v-model="clasificacion_evento" label="Clasificación del evento">
                        </v-select>
                    </v-flex> -->
                    <v-flex xs6 px-1>
                        <VAutocomplete :items="sedesCompletas" v-model="sede_ocurrencia" item-value="id"
                            item-text="nombre" label="Sede ocurrencia" />
                    </v-flex>
                    <v-flex xs6 px-1>
                        <VAutocomplete :items="sedesCompletas" v-model="sede_reportante" item-value="id"
                            item-text="nombre" label="Sede reportante" />
                    </v-flex>
                    <v-flex xs6 px-1 v-show="sede_ocurrencia == 704">
                        <VAutocomplete :items="socurrencias" v-model="servicio_ocurrencia"
                            label="Servicio de ocurrencia" />
                    </v-flex>
                    <v-flex xs6 px-1 v-show="sede_reportante == 704">
                        <VAutocomplete :items="socurrencias" v-model="servicio_reportante"
                            label="Servicio reportante" />
                    </v-flex>
                    <v-flex xs3 px-1>
                        <v-text-field label="Fecha de ocurrencia" v-model="fecha_ocurrencia" type="date">
                        </v-text-field>
                    </v-flex>
                    <!-- <v-flex xs3 px-1>
                        <v-select label="Red" v-model="red" :items="['Interna', 'Externa']">
                        </v-select>
                    </v-flex> -->
                    <!-- <v-flex xs3 px-1>
                        <v-select label="Clasificación invima" v-model="clasificacion_invima"
                            :items="['Serio', 'No serio', 'No aplica']">
                        </v-select>
                    </v-flex> -->
                    <v-flex xs6 px-1 v-show="evento == 139">
                        <v-select :items="relacion" v-model="relacionEvento" label="Relacionado con"
                            @input="activarCampos()">
                        </v-select>
                    </v-flex>
                    <v-flex v-show="evento == 109">
                        <v-layout row wrap>
                            <v-flex xs12>
                                <VAutocomplete :items="medicamentos" v-model="medicamento_evento" item-value="id"
                                    item-text="Producto" label="Medicamento" />
                            </v-flex>
                            <!-- <v-flex xs6 px-1>
                                <v-text-field label="Fecha de vencimiento" v-model="fecha_vence_medicamento"
                                    type="date">
                                </v-text-field>
                            </v-flex> -->
                            <!-- <v-flex xs3 px-1>
                                <v-text-field label="Lote" v-model="lote_medicamento">
                                </v-text-field>
                            </v-flex> -->
                            <!-- <v-flex xs3 px-1>
                                <v-text-field label="Invima" v-model="invima_medicamento">
                                </v-text-field>
                            </v-flex> -->
                            <v-flex xs1 px-1>
                                <v-text-field label="Dosis" v-model="dosis_medicamento" type="number"
                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-autocomplete label="Unidad de medidada" v-model="medida_medicamento"
                                    :items="unidadesmedida">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-autocomplete label="Via de administración" v-model="via_medicamento"
                                    :items="viasadmin">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-text-field label="Frecuencia de administración" v-model="frecuencia_medicamento">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs2 px-1>
                                <v-text-field label="Tiempo de infusión" v-model="infusion_medicamento">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 v-show="activarDispositivos">
                        <v-layout row wrap>
                            <span>Dispositivos</span>
                            <v-flex xs12>
                                <VAutocomplete :items="insumoDispositivo" v-model="dispositivo" item-value="id"
                                    item-text="Producto" label="Dispositivo/Insumo" />
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-text-field label="Referencia" v-model="referencia_dispositivo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-text-field label="Lote" v-model="lote_dispositivo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs3 px-1>
                                <v-text-field label="Invima" v-model="invima_dispositivo">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 v-show="activarEquipoBio">
                        <v-layout row wrap>
                            <span>Equipo biomédico</span>
                            <v-flex xs12>
                                <v-text-field label="Nombre" v-model="nombre_equipo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4>
                                <v-text-field label="Marca" v-model="marca_equipo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-text-field label="Modelo" v-model="modelo_equipo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs4 px-1>
                                <v-text-field label="Serie" v-model="serie_equipo">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12>
                        <v-textarea label="Descripción de los hechos" v-model="descripcion_hechos">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs7>
                        <v-autocomplete :items="user_activos" label="Profesional tratante" item-text="nombre"
                            item-value="nombre" v-model="profesional" />
                    </v-flex>
                    <v-flex xs12>
                        <v-textarea label="Acciones que se tomaron" v-model="accion_tomada">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12>
                        <input id="adjuntos" multiple ref="adjuntosPaciente" type="file" />
                    </v-flex>
                </v-layout>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="success" @click="savevento()">
                    <span>Enviar</span>
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-dialog v-model="dialogHistory" v-if="dialogHistory" width="700" persistent>
            <v-card>

                <v-card-title class="headline primary" style="color:white" primary-title>
                    Historial
                </v-card-title>

                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs2 px-1>
                            <v-text-field v-model="paciente.Primer_Nom" readonly label="Nombre"></v-text-field>
                        </v-flex>
                        <v-flex xs2 px-1>
                            <v-text-field v-model="paciente.Primer_Ape" readonly label="Apellido"></v-text-field>
                        </v-flex>
                        <v-flex xs2 px-1>
                            <v-text-field v-model="paciente.Num_Doc" readonly label="Documento"></v-text-field>
                        </v-flex>
                        <v-flex xs1 px-1>
                            <v-text-field v-model="paciente.Edad_Cumplida" readonly label="Edad"></v-text-field>
                        </v-flex>
                        <v-flex xs1 px-1>
                            <v-text-field v-model="paciente.Sexo" readonly label="Sexo"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="paciente.entidad" readonly label="Entidad"></v-text-field>
                        </v-flex>
                        <v-expansion-panel>
                            <v-expansion-panel-content v-for="(item, index) in history" :key="index">
                                <template v-slot:header>
                                    <span><b>ID:</b> {{item.id}}</span>
                                    <span><b>Estado:</b>
                                        <v-chip class="success white--text">{{item.estado}}</v-chip>
                                    </span>
                                    <span><b>Fecha de ocurrencia:</b> {{item.fecha_ocurrencia}}</span>
                                </template>
                                <v-card>
                                    <v-container grid-list-md>
                                        <v-layout row wrap>
                                            <v-flex xs12 md12>
                                                <v-text-field label="Nombre del suceso" v-model="item.evento" readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 md12>
                                                <v-text-field label="Sede ocurrencia" v-model="item.sede" readonly>
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-layout>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" flat @click="dialogHistory = false">
                        ok
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>


        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

    </div>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    import Swal from 'sweetalert2';
    export default {
        data() {
            return {
                paciente: {},
                cedula_paciente: '',
                allsedes: [],
                sede_ocurrencia: '',
                sede_reportante: '',
                clasificacion_evento: '',
                fecha_ocurrencia: '',
                relacionEvento: '',
                clasieventos: ['Adverso', 'Incidente', 'Complicación', 'Acción insegura'],
                alleventos: [],
                activarDispositivos: false,
                relacion: ['Dispositivos', 'Equipo biomédico', 'Ambos'],
                evento: '',
                detallemedicamento: '',
                medicamento_evento: '',
                fecha_vence_medicamento: '',
                lote_medicamento: '',
                invima_medicamento: '',
                dispositivo: '',
                referencia_dispositivo: '',
                modelo_dispositivo: '',
                serial_dispositivo: '',
                invima_dispositivo: '',
                medicamentos: [],
                insumoDispositivo: [],
                descripcion_hechos: '',
                accion_tomada: '',
                clasificacion_invima: '',
                dosis_medicamento: '',
                medida_medicamento: '',
                via_medicamento: '',
                frecuencia_medicamento: '',
                infusion_medicamento: '',
                allclasificacion: [],
                clasificacion_area: '',
                alltipoeventos: [],
                tipo_evento: '',
                activarEquipoBio: false,
                nombre_equipo: '',
                marca_equipo: '',
                modelo_equipo: '',
                serie_equipo: '',
                placa_equipo: '',
                otro_evento: '',
                dialogEvento: true,
                tipoPaciente: false,
                tipoSinPaciente: false,
                crearPaciente: false,
                pacienteCreacion: false,
                servicio_reportante: '',
                formPaciente: {},
                tipoDoc: ['CC', 'CE', 'PA', 'RC', 'TI'],
                entidades: [],
                unidadesmedida: ['mg', '%', 'mg/mL', 'UI', 'g', 'g/mL', 'g/mg', 'mcg', 'mcg/mL', 'mcg/dosis',
                    'meq/mL', 'mmol/mL', 'dosis', 'gotas', 'puff', 'mL'
                ],
                viasadmin: ['Oral', 'Dérmica', 'Vaginal', 'Intravenosa', 'Transdérmica', 'Tópica', 'Subcutánea',
                    'Oftálmica', 'Enteral', 'Intramuscular', 'Rectal', 'Inhalatoria', 'Ótica', 'Nasal',
                    'Sublingual',
                    'Intratecal'
                ],
                socurrencias: ['Hospitalización tercer piso', 'Hospitalización cuarto piso',
                    'Hospitalización quinto piso',
                    'Hospitalización sexto piso', 'Unidad de cuidados intensivos (UCI)',
                    'Unidad de cuidados especiales (UCE)',
                    'Cirugía', 'Endoscopia', 'Urgencias', 'Servicio farmacéutico', 'Laboratorio', 'Alimentación',
                    'Centro regulador', 'Quimioterapia', 'Diagnóstico cardiovascular', 'Imagenología', 'Vacunación',
                    'Calidad', 'Epidemiología', 'Auditoría concurrente', 'Servicios generales', 'Gestión logística',
                    'Atención domiciliaria','Trabajo Social','Otros'
                ],
                servicio_ocurrencia: '',
                red: '',
                lote_dispositivo: '',
                dialogHistory: false,
                history: [],
                preload: false,
                users: [],
                profesional: '',
                adjuntos: [],

            }
        },
        computed: {
            ...mapGetters(['can']),
            sedesCompletas() {
                return this.allsedes.map(sede => {
                    return {
                        id: sede.id,
                        nombre: `${sede.Nombre} - ${sede.Direccion}`
                    }
                })
            },
            user_activos() {
                let finded = [];
                this.users.forEach(u => {
                    if (u.estado == 1) {
                        finded.push(u)
                    }
                });
                return finded;
            },
        },
        methods: {
            getUser() {
                axios.get('/api/user/all')
                    .then(res => this.users = res.data.map((us) => {
                        return {
                            id: us.id,
                            nombre: `${us.cedula} - ${us.name} ${us.apellido}`,
                            estado: us.estado_user,
                            name: `${us.name} ${us.apellido}`,
                        }
                    }));
            },
            search_paciente() {
                if (!this.cedula_paciente) return;
                axios.get(`/api/paciente/eventoPaciente/${this.cedula_paciente}`).then((res) => {
                    this.paciente = res.data;
                    this.historial(this.paciente.id)
                    this.sedes();
                    this.geteventos();
                    this.getmedicamentos();
                    this.getinsumoydispositivos();
                    this.getUser();
                }).catch(err => {
                    this.paciente = {}
                    if (err.response.data.Message == 'Paciente no existe en el sistema') {
                        this.crearPaciente = true
                        this.formPaciente.Num_Doc = this.cedula_paciente
                        this.fetchEntidades();
                    } else {
                        this.$alerError(err.response.data.Message)
                    }
                })
            },
            indiciodeAtencion() {
                this.tipoSinPaciente = true
                this.dialogEvento = false
                this.sedes();
                this.geteventos();
                this.getmedicamentos();
                this.getinsumoydispositivos();
                this.getUser();
            },
            fetchEntidades() {
                axios.get('/api/entidades/entidadesNoContrato')
                    .then(res => {
                        this.entidades = res.data;
                    })
            },
            clearFields() {
                this.closeEvento();
                this.cedula_paciente = ''
                this.paciente = {}
                this.$refs.adjuntos.value = ''
                this.$refs.adjuntosPaciente.value = ''
            },
            sedes() {
                axios.get('/api/sedeproveedore/getSedePrestador').then((res) => {
                    this.allsedes = res.data
                })
            },
            geteventos() {
                axios.get('/api/evento/allsumimedical').then(res => {
                    this.alleventos = res.data
                });
            },
            getclasificacion() {
                this.allclasificacion = []
                this.clasificacion_area = ''
                this.alltipoeventos = []
                this.tipo_evento = ''
                axios.get('/api/evento/getclasificacion/' + this.evento).then(res => {
                    this.allclasificacion = res.data
                });
            },
            getTipoevento() {
                axios.get('/api/evento/getTipoevento/' + this.clasificacion_area).then(res => {
                    this.alltipoeventos = res.data
                });
            },
            activarCampos() {
                if (this.relacionEvento == 'Dispositivos') this.activarDispositivos = true, this.activarEquipoBio =
                    false
                if (this.relacionEvento == 'Equipo biomédico') this.activarDispositivos = false, this.activarEquipoBio =
                    true
                if (this.relacionEvento == 'Ambos') this.activarDispositivos = true,
                    this.activarEquipoBio = true
            },
            getmedicamentos() {
                axios.get('/api/detallearticulo/detalle_medicamentos').then(res => {
                    this.medicamentos = res.data
                });
            },
            getinsumoydispositivos() {
                axios.get('/api/detallearticulo/detalle_insumo_dispositivo').then(res => {
                    this.insumoDispositivo = res.data
                });
            },
            async savevento() {
                if (this.evento == 109) {
                    if (
                        this.medicamento_evento == '' ||
                        this.dosis_medicamento == '' ||
                        this.medida_medicamento == '' ||
                        this.via_medicamento == '' ||
                        this.frecuencia_medicamento == ''
                    ) {
                        this.$alerError("Debe llenar todos los campos obligatorios de medicamentos!")
                        return
                    }
                }
                if (this.evento == 139) {
                    if (this.relacionEvento == '') {
                        this.$alerError("Debe seleccionar una opción en el campo relacionado con!")
                        return
                    }
                    if (this.relacionEvento == 'Dispositivos' || this.relacionEvento == 'Ambos') {
                        if (
                            this.dispositivo == '' ||
                            this.referencia_dispositivo == '' ||
                            this.invima_dispositivo == '' ||
                            this.lote_dispositivo == ''
                        ) {
                            this.$alerError("Debe llenar todos los campos obligatorios de dispositivos!")
                            return
                        }
                    }
                    if (this.relacionEvento == 'Equipo biomédico' || this.relacionEvento == 'Ambos') {
                        if (
                            this.nombre_equipo == '' ||
                            this.serie_equipo == '' ||
                            this.modelo_equipo == ''
                        ) {
                            this.$alerError("Debe llenar todos los campos obligatorios de equipo biomédico!")
                            return
                        }
                    }
                }
                if (this.evento == 133) {
                    if (this.otro_evento == '') {
                        this.$alerError("Debe llenar cual (nombre del suceso)")
                        return
                    }
                }
                if (this.sede_ocurrencia == 704) {
                    if (this.servicio_ocurrencia == '') {
                        this.$alerError("Debe llenar el campo servicio de ocurrencia!")
                        return
                    }
                }
                if (this.sede_reportante == 704) {
                    if (this.servicio_reportante == '') {
                        this.$alerError("Debe llenar el campo servicio reportante!")
                        return
                    }
                }
                this.preload = true
                let data = {
                    paciente: this.paciente.id,
                    relacion: this.relacionEvento,
                    nombre_del_suceso: this.evento,
                    clasificacion: this.clasificacion_evento,
                    sede_ocurrencia: this.sede_ocurrencia,
                    sede_reportante: this.sede_reportante,
                    fecha_ocurrencia: this.fecha_ocurrencia,
                    medicamento_evento: this.medicamento_evento,
                    fecha_vence_medicamento: this.fecha_vence_medicamento,
                    lote_medicamento: this.lote_medicamento,
                    invima_medicamento: this.invima_medicamento,
                    dispositivo: this.dispositivo,
                    referencia_dispositivo: this.referencia_dispositivo,
                    modelo_dispositivo: this.modelo_dispositivo,
                    serial_dispositivo: this.serial_dispositivo,
                    invima_dispositivo: this.invima_dispositivo,
                    descripcion_hechos: this.descripcion_hechos,
                    accion_tomada: this.accion_tomada,
                    clasificacion_area: this.clasificacion_area,
                    tipo_evento: this.tipo_evento,
                    clasificacion_invima: this.clasificacion_invima,
                    dosis_medicamento: this.dosis_medicamento,
                    medida_medicamento: this.medida_medicamento,
                    via_medicamento: this.via_medicamento,
                    frecuencia_medicamento: this.frecuencia_medicamento,
                    infusion_medicamento: this.infusion_medicamento,
                    nombre_equipo: this.nombre_equipo,
                    marca_equipo: this.marca_equipo,
                    modelo_equipo: this.modelo_equipo,
                    serie_equipo: this.serie_equipo,
                    placa_equipo: this.placa_equipo,
                    otro_evento: this.otro_evento,
                    servicio_reportante: this.servicio_reportante,
                    servicio_ocurrencia: this.servicio_ocurrencia,
                    lote_dispositivo: this.lote_dispositivo,
                    profesional: this.profesional
                }

                if(this.$refs.adjuntosPaciente.files.length > 0){
                    this.adjuntos = this.$refs.adjuntosPaciente.files;
                }else{
                    this.adjuntos = this.$refs.adjuntos.files;
                }

                let formData = new FormData();
                for (let i = 0; i < this.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.adjuntos[i]);
                }

                await axios.post('/api/evento/create', data).then(res => {
                    formData.append(`eventoadverso_id`, res.data.eventoadverso_id);
                    axios.post('/api/evento/adjuntos', formData);
                    this.closeEvento();
                    this.preload = false
                    this.$alertInfo("Se ha generado un reporte con éxito con el ID " + res.data.eventoadverso_id,"¡Felicitaciones! Ahora haces parte del personal con cultura del reporte, esto nos ayudará a mejorar nuestros procesos.")
                    this.clearFields();
                    this.tipoSinPaciente = false
                    this.dialogEvento = true
                    this.tipoPaciente = false
                }).catch(err => {
                    this.preload = false
                    let msg = '';
                    for (const pro in err.response.data) {
                        if (msg) msg += `${err.response.data[pro]}`
                        else msg = err.response.data[pro]
                    }
                    this.$alerError(msg)
                })
            },
            closeEvento() {
                this.activarEquipoBio = false
                this.activarDispositivos = false
                this.relacionEvento = ''
                this.evento = ''
                this.clasificacion_evento = ''
                this.sede_ocurrencia = ''
                this.sede_reportante = ''
                this.fecha_ocurrencia = ''
                this.medicamento_evento = ''
                this.fecha_vence_medicamento = ''
                this.lote_medicamento = ''
                this.invima_medicamento = ''
                this.dispositivo = ''
                this.referencia_dispositivo = ''
                this.modelo_dispositivo = ''
                this.serial_dispositivo = ''
                this.invima_dispositivo = ''
                this.descripcion_hechos = ''
                this.accion_tomada = ''
                this.accion_tomada = ''
                this.clasificacion_area = ''
                this.tipo_evento = ''
                this.clasificacion_invima = ''
                this.dosis_medicamento = ''
                this.medida_medicamento = ''
                this.via_medicamento = ''
                this.frecuencia_medicamento = ''
                this.infusion_medicamento = ''
                this.allclasificacion = []
                this.alltipoeventos = []
                this.otro_evento = ''
                this.servicio_reportante = ''
                this.servicio_ocurrencia = ''
                this.lote_dispositivo = ''
                this.serie_equipo = ''
                this.modelo_equipo = ''
                this.nombre_equipo = ''
                this.marca_equipo = ''
                this.profesional = ''
            },
            guardar_paciente() {
                if (!this.formPaciente.Num_Doc) {
                    this.$alertInfo("El número de documento es requerido")
                    return;
                }
                if (!this.formPaciente.Primer_Nom) {
                    this.$alertInfo("El primer nombre es requerido.")
                    return;
                }
                if (!this.formPaciente.Primer_Ape) {
                    this.$alertInfo("El primer apellido es requerido.")
                    return;
                }
                if (!this.formPaciente.Tipo_Doc) {
                    this.$alertInfo("El tipo documento es requerido.")
                    return;
                }
                if (!this.formPaciente.Sexo) {
                    this.$alertInfo("El sexo es requerido.")
                    return;
                }
                if (!this.formPaciente.entidad) {
                    this.$alertInfo("La entidad de salud es requerida.")
                    return;
                }
                this.formPaciente.Estado_Afiliado = 27
                this.formPaciente.Ut = this.formPaciente.entidad.nombre
                this.formPaciente.entidad_id = this.formPaciente.entidad.id
                axios.post('/api/paciente/guardarPaciente', this.formPaciente)
                    .then(res => {
                        if (!res.data.paciente) {
                            this.search_paciente()
                            this.$alerSuccess(res.data.mensaje);
                            this.pacienteCreacion = false;
                            this.crearPaciente = false;
                            this.formPaciente = {
                                entidad: '',
                                Sexo: '',
                                Tipo_Doc: '',
                                Primer_Ape: '',
                                Primer_Nom: '',
                                SegundoNom: '',
                                Segundo_Ape: ''
                            }
                        } else {
                            this.$alerError(res.data.mensaje)
                        }
                    })
            },
            historial(paciente) {
                this.preload = true
                axios.post('/api/evento/history', {
                        paciente_id: paciente
                    })
                    .then((res) => {
                        this.preload = false
                        this.history = res.data
                        if (this.history.length > 0) {
                            this.dialogHistory = true
                        }
                    }).catch(err => {
                        this.preload = false
                    })
            },
            clearRelacion() {
                this.relacionEvento = ''
                this.activarDispositivos = false
                this.activarEquipoBio = false
            }

        }

    }

</script>
