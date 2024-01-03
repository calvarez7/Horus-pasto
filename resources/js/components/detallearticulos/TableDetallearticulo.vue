<template>
    <v-layout wrap>

        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogCreateCodigoSumi" v-if="dialogCreateCodigoSumi" persistent max-width="1100px">
            <v-card>
                <form @submit.prevent="saveCodesumi">
                    <v-card-title class="headline primary" style="color:white">
                        <span v-if="save" class="headline">Crear Codigo Sumi</span>
                        <span v-else class="headline">Editar Codigo Sumi</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field label="Codigo Sumi" v-model="form.Codigo" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field label="Producto" v-model="form.Nombre" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-autocomplete label="Tipo" v-model="form.Tipocodesumi_id" :items="tipos"
                                        item-text="Nombre" item-value="tipo_id" required></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-autocomplete label="Grupo" v-model="form.grupo_id" :items="grupos"
                                        item-text="Nombre" item-value="grupo_id"></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-autocomplete label="Linea Base" v-model="form.linea_base_id" :items="lineasBase"
                                        item-text="nombre" item-value="lineaBase_id"></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-autocomplete label="Grupo Terapeutico" v-model="form.grupoterapeutico_id"
                                        :items="gruposTerapeuticos" item-text="nombre" item-value="grupoTerapeutico_id">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-autocomplete label="Sub Grupo Terapeutico" v-model="form.subgrupoterapeutico_id"
                                        :items="subGruposTerapeuticos" item-text="nombre"
                                        item-value="SubGrupoTerapeutico_id"></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm4 md4>

                                    <v-autocomplete label="Unidad Medida" v-model="form.unidadMedida"
                                        :items="unidadMedida" item-text="nombre" item-value="nombre">
                                    </v-autocomplete>

                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-autocomplete label="Forma Farmaceutica" v-model="form.formafarmaceutica_id"
                                        :items="formasFarmaceuticas" item-text="nombre"
                                        item-value="formaFarmaceutica_id"></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-autocomplete label="Vias Administracion" v-model="form.vias"
                                        :items="viasAdministracion" item-text="nombre" item-value="id" multiple>
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-text-field label="Concentracion #1" v-model="form.concentracion1">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-text-field label="Concentracion #2" v-model="form.concentracion2">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-text-field label="Concentracion #3" v-model="form.concentracion3">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-text-field label="Unidad Concentracion" v-model="form.unidad_concentracion">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-text-field label="Frecuencia" v-model="form.Frecuencia" required></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-text-field label="Cantidad Maxima Ordenar" v-model="form.Cantidadmaxordenar"
                                        required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-text-field label="Cantidad Maxima Ordenar dia (insulinas)" v-model="form.cantidad_maxima_ordenar_dia"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm2 md2>
                                    <v-checkbox v-model="form.Requiere_autorizacion" color="primary"
                                        label="Requiere Autorizacion"></v-checkbox>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-autocomplete label="Nivel Ordenamiento" v-model="form.Nivel_Ordenamiento"
                                        :items="['1','2','3','4']" required></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-autocomplete label="Ambito" v-model="form.Ambito"
                                        :items="['AMBULATORIO','HOSPITALARIO','AMBOS']" required></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="salirModales()">Salir</v-btn>
                        <v-btn v-if="save" color="success" type="submit">Guardar</v-btn>
                        <v-btn v-else color="warning" type="submit">Actualizar</v-btn>
                    </v-card-actions>
                </form>
            </v-card>
        </v-dialog>


        <v-dialog v-model="dialogCreateDetalle" v-if="dialogCreateDetalle" persistent max-width="1100px">
            <v-card>
                <v-card-title class="headline primary" style="color:white">
                    <span v-if="saveDetalle" class="headline">Crear Detalle Articulo</span>
                    <span v-else class="headline">Editar Detalle Articulo</span>
                </v-card-title>
                <v-card-text>
                    <v-expansion-panel>
                        <v-expansion-panel-content class="info text-xs-center">
                            <template v-slot:actions>
                                <v-icon color="white">$vuetify.icons.expand</v-icon>
                            </template>
                            <template v-slot:header>
                                <div class="white--text">Detalle</div>
                            </template>
                            <v-card>
                                <form @submit.prevent="saveDetalleCodesumi">
                                    <v-card-text>
                                        <v-layout wrap>
                                            <v-flex xs12 sm6 md6 px-2>
                                                <v-autocomplete :items="cums" item-text="fullname" item-value="id"
                                                    label="CUM" :loading="loading" :search-input.sync="nombre"
                                                    @input="findCum($event)" v-model="formDetalle.Cum_Validacion" />
                                            </v-flex>
                                            <!--                                            <v-flex xs12 sm6 md6 px-2>-->
                                            <!--                                                <v-autocomplete :items="cums" item-text="fullname" item-value="id"-->
                                            <!--                                                    label="CUM" :loading="loading" required :search-input.sync="nombre"-->
                                            <!--                                                    @change="findCum($event)" v-model="formDetalle.Cum_Validacion" />-->
                                            <!--                                            </v-flex>-->
                                            <!--                                            <v-flex xs12 sm12 md12 px-2 v-else>-->
                                            <!--                                                <v-text-field label="CUM" v-model="formDetalle.Cum_Validacion" required-->
                                            <!--                                                    readonly>-->
                                            <!--                                                </v-text-field>-->
                                            <!--                                            </v-flex>-->
                                            <v-flex xs12 sm6 md6 px-2>
                                                <v-text-field label="Titular" v-model="formDetalle.titular" required
                                                    readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md6>
                                                <v-text-field label="Codigo" v-model="formDetalle.Codigo" required
                                                    readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4 px-2>
                                                <v-text-field label="Principio Activo"
                                                    v-model="formDetalle.Principio_Activo" required readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4 px-2>
                                                <v-text-field label="Registro Sanitario"
                                                    v-model="formDetalle.Registro_Sanitario" required readonly>
                                                </v-text-field>
                                            </v-flex>

                                            <v-flex xs12 sm4 md4>
                                                <v-text-field label="Fecha Vencimiento"
                                                    v-model="formDetalle.Fecha_Vencimiento" required readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-text-field label="Presentación Comercial"
                                                    v-model="formDetalle.presentacion_comercial" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12 px-2>
                                                <v-text-field label="Descripción Comercial"
                                                    v-model="formDetalle.Descripcion_Comercial" required readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4 px-2>
                                                <v-text-field label="Estado Registro"
                                                    v-model="formDetalle.Estado_Registro" required readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4>
                                                <v-text-field label="Muestra Medica"
                                                    v-model="formDetalle.Muestra_Medica" required readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4 px-2>
                                                <v-autocomplete label="Clasificacion Riesgo"
                                                    :items="['Tipo I','Tipo IIa','Tipo Ilb','Tipo III','Tipo IV']"
                                                    v-model="formDetalle.clasificacion_riesgo"></v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4 px-2>
                                                <v-text-field label="Marca Dispositivo"
                                                    v-model="formDetalle.marca_dispositivo"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4 px-2>
                                                <v-text-field label="IUM Primer Nivel"
                                                    v-model="formDetalle.ium_primernivel">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4 px-2>
                                                <v-text-field label="IUM Segundo Nivel"
                                                    v-model="formDetalle.ium_segundonivel">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2 px-2>
                                                <v-checkbox v-model="formDetalle.Estado_id" color="primary"
                                                    label="Activo Horus">
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2 px-2>
                                                <v-checkbox v-model="formDetalle.Regulado" color="primary"
                                                    label="Regulado">
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2 px-2>
                                                <v-checkbox v-model="formDetalle.Alto_Costo" color="primary"
                                                    label="Alto Costo">
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2 px-2>
                                                <v-checkbox v-model="formDetalle.Acuerdo_228" color="primary"
                                                    label="Acuerdo 228">
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2>
                                                <v-checkbox v-model="formDetalle.Requiere_autorizacion" color="primary"
                                                    label="Requiere Autorización">
                                                </v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2 px-2>
                                                <v-checkbox v-model="formDetalle.oncologico" color="primary"
                                                    label="Oncologico">
                                                </v-checkbox>
                                            </v-flex>


                                            <v-flex xs12 sm2 md2 px-2>
                                                <v-checkbox v-model="formDetalle.refrigerado" color="primary"
                                                    label="Refrigerado"></v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2>
                                                <v-checkbox v-model="formDetalle.control_especial" color="primary"
                                                    label="Control Especial"></v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2>
                                                <v-checkbox v-model="formDetalle.costoso" color="primary"
                                                    label="Costoso"></v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2>
                                                <v-checkbox v-model="formDetalle.comercial" color="primary"
                                                    label="Comercial"></v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2>
                                                <v-checkbox v-model="formDetalle.generico" color="primary"
                                                    label="Generico"></v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2>
                                                <v-checkbox v-model="formDetalle.critico" color="primary"
                                                            label="Critico"></v-checkbox>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2>
                                                <v-select label="ABC" v-model="formDetalle.abc" :items="['A','B','C']"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4 px-2>
                                                <v-select label="Estado Normativo" :items="['PBS','NO PBS','EXCLUSION']"
                                                    v-model="formDetalle.POS"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4 px-2>
                                                <v-select label="Origen" :items="['NACIONAL','IMPORTADO']"
                                                    v-model="formDetalle.origen" required></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm4 md4 px-2>
                                                <v-text-field label="Precio Maximo" v-model="formDetalle.Precio_maximo"
                                                    type="number">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2>
                                                <v-btn v-if="saveDetalle" color="success" type="submit">Guardar</v-btn>
                                                <v-btn v-else color="warning" type="submit">Actualizar</v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </v-card-text>
                                </form>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    <v-divider class="mx-2" inset vertical></v-divider>
                    <v-expansion-panel v-if="!saveDetalle">
                        <v-expansion-panel-content class="info text-xs-center">
                            <template v-slot:actions>
                                <v-icon color="white">$vuetify.icons.expand</v-icon>
                            </template>
                            <template v-slot:header>
                                <div class="white--text">Precio</div>
                            </template>
                            <v-card>
                                <v-card-text>
                                    <v-card-text>
                                        <v-layout wrap>
                                            <v-flex xs12 sm12 md12 px-2>
                                                <v-autocomplete :items="proveedores" item-text="fullname"
                                                    item-value="id" label="Proveedor" v-model="formDetalle.proveedor" />
                                            </v-flex>
                                            <v-flex xs12 sm2 md2 px-2>
                                                <v-text-field label="Precio Unidad" v-model="formDetalle.precio_unidad"
                                                    type="number" min="1"
                                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode === 44)">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2 px-2>
                                                <v-text-field label="Iva" v-model="formDetalle.iva" type="number"
                                                    min="1"
                                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode === 44)">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2 px-2>
                                                <v-text-field label="Iva Facturación" type="number" min="1"
                                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode === 44)"
                                                    v-model="formDetalle.iva_facturacion" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm2 md2 px-1>
                                                <v-text-field label="Precio Venta" type="number" min="1"
                                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode === 44)"
                                                    v-model="formDetalle.precio_venta" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs2 sm2 md2 px-2>
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn text icon color="success"
                                                            @click="saveDetalleCodesumiPrecio" dark v-on="on">
                                                            <v-icon>add</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Crear Precio</span>
                                                </v-tooltip>
                                            </v-flex>
                                        </v-layout>
                                    </v-card-text>
                                </v-card-text>
                                <v-card-title>
                                    <v-spacer></v-spacer>
                                    <v-text-field :search="searchPrecio" append-icon="search" label="Buscar" single-line
                                        hide-details>
                                    </v-text-field>
                                </v-card-title>
                                <v-data-table :headers="headersPrecio" :items="detallePrecios" :search="searchPrecio">
                                    <template v-slot:items="props">
                                        <td class="text-xs-center">{{ props.item.Nombre }}</td>
                                        <td class="text-xs-center">{{ props.item.precio_unidad }}</td>
                                        <td class="text-xs-center">{{ props.item.iva }}</td>
                                        <td class="text-xs-center">{{ props.item.iva_facturacion }}</td>
                                        <td class="text-xs-center">{{ props.item.precio_venta }}</td>
                                        <td class="text-xs-center">
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn text icon color="warning" @click="openEditPrecio(props.item)"
                                                        dark v-on="on">
                                                        <v-icon>edit</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Editar</span>
                                            </v-tooltip>
                                        </td>
                                    </template>
                                    <template v-slot:no-results>
                                        <v-alert :value="true" color="error" icon="warning">
                                            Your search for "{{ searchPrecio }}" found no results.
                                        </v-alert>
                                    </template>
                                </v-data-table>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="salirModales()">Salir</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogEditPrecio" v-if="dialogEditPrecio" persistent max-width="600px">
            <v-card>
                <v-card-title class="headline primary" style="color:white">
                    <span class="headline">Editar Precio Detalle Articulo</span>
                </v-card-title>
                <v-card-text>
                    <v-layout wrap>
                        <v-flex xs12 sm4 md4 px-2>
                            <v-text-field label="Precio Unidad" v-model="formDetallePrecio.precio_unidad" type="number"
                                min="1"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode === 44)">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm4 md4 px-2>
                            <v-text-field label="Iva" v-model="formDetallePrecio.iva" type="number" min="1"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode === 44)">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm4 md4 px-2>
                            <v-text-field label="Iva Facturación" type="number" min="1"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode === 44)"
                                v-model="formDetallePrecio.iva_facturacion" required>
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm4 md4 px-1>
                            <v-text-field label="Precio Venta" type="number" min="1"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode === 44)"
                                v-model="formDetallePrecio.precio_venta" required>
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="editDetallePrecio()">Guardar</v-btn>
                    <v-btn color="error" @click="closeDetallePrecio()">Salir</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>


        <v-flex>
            <v-card>
                <v-card-title>
                    <v-layout row wrap>
                        <v-flex xs4>
                            <v-btn round v-if="can('codesumi.create')" @click="modalCodesumi(true)" color="primary"
                                dark>
                                <v-icon left>person_add</v-icon>
                                Crear Código Sumi
                            </v-btn>
                        </v-flex>
                        <v-spacer></v-spacer>
                        <v-flex xs4>
                            <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line
                                hide-details></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-data-table :headers="headersMedicamentos" :items="medicamentos" item-key="id" :expand="expand"
                    :search="search">
                    <template v-slot:items="props">
                        <tr>
                            <td class="text-xs-center" @click="props.expanded = !props.expanded">
                                {{ props.item.Codigo }}
                            </td>
                            <td class="text-xs-center" @click="props.expanded = !props.expanded">{{ props.item.Nombre }}
                            </td>
                            <td class="text-xs-center" @click="props.expanded = !props.expanded">
                                {{ props.item.Cantidadmaxordenar }}</td>
                            <td class="text-xs-center" @click="props.expanded = !props.expanded">
                                {{ props.item.Nivel_Ordenamiento }}</td>
                            <td class="text-xs-center" @click="props.expanded = !props.expanded">
                                {{ props.item.nivelportabilidad }}</td>
                            <td class="text-xs-center" @click="props.expanded = !props.expanded">
                                {{ props.item.Requiere_autorizacion }}</td>
                            <td class="text-xs-center" @click="props.expanded = !props.expanded">{{ props.item.estado }}
                            </td>
                            <td class="text-xs-center">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon color="deep-orange" @click="modalCodesumi(false,props.item)"
                                            dark v-on="on">
                                            <v-icon>edit</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Editar</span>
                                </v-tooltip>
                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-btn fab color="success" small
                                            @click="modalDetalle(true,{Codigo:props.item.Codigo})" v-on="on">
                                            <v-icon>add</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Crear detalle</span>
                                </v-tooltip>
                            </td>
                        </tr>
                    </template>
                    <template v-slot:expand="props">
                        <v-card flat>
                            <v-data-table :headers="headersDetalles" :items="props.item.detallearticulos" item-key="id">
                                <template v-slot:items="props">
                                    <tr style="color:#D1072F">
                                        <td class="text-xs-center">{{ props.item.codigo }}</td>
                                        <td class="text-xs-center">{{ props.item.titular }}</td>
                                        <td class="text-xs-center">{{ props.item.Cum_Validacion }}</td>
                                        <td class="text-xs-center">{{ props.item.Estado_Registro }}</td>
                                        <td class="text-xs-center">
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn text icon color="warning" dark v-on="on"
                                                        @click="modalDetalle(false, props.item)">
                                                        <v-icon>edit</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Editar</span>
                                            </v-tooltip>
                                        </td>
                                    </tr>
                                </template>

                            </v-data-table>
                        </v-card>
                    </template>
                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your search for
                        "{{ search }}" found no results.
                    </v-alert>
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
        name: 'TableDetallearticulo',
        data() {
            return {
                preload: false,
                codigoSumi: '',
                loading: false,
                nombre: "",
                grupos: [],
                tipos: [],
                gruposTerapeuticos: [],
                subGruposTerapeuticos: [],
                formasFarmaceuticas: [],
                viasAdministracion: [],
                lineasBase: [],
                unidadMedida: [],
                cums: [],
                dialogCreateCodigoSumi: false,
                dialogCreateDetalle: false,
                save: true,
                saveDetalle: true,
                expand: false,
                titulares: [],
                medicamentos: [],
                search: "",
                searchPrecio: "",
                form: {},
                formDetalle: {},
                proveedores: [],
                detallePrecios: [],
                headersMedicamentos: [{
                        text: "Codigo Sumi",
                        align: 'center',
                        value: "Codigo"
                    },
                    {
                        text: "Nombre",
                        align: 'center',
                        value: "Nombre"
                    },
                    {
                        text: "Cantidad Max.Ordenar",
                        align: 'center',
                        value: "Cantidadmaxordenar"
                    },
                    {
                        text: "Nivel Ordenamiento",
                        align: 'center',
                        value: "Nivel_Ordenamiento"
                    },
                    {
                        text: "Nivel Portabilidad",
                        align: 'center',
                        value: "Nivel_Portabilidad"
                    },
                    {
                        text: "Requiere Autorizacion",
                        align: 'center',
                        value: "Requiere_autorizacion"
                    },
                    {
                        text: "Estado",
                        align: 'center',
                        value: "estado"
                    },
                    {
                        text: "Acciones",
                        align: 'center',
                        value: ""
                    }
                ],
                headersDetalles: [{
                        text: "Codigo",
                        align: 'center',
                        value: "",
                        sortable: false
                    },
                    {
                        text: "Titular",
                        align: 'center',
                        value: "",
                        sortable: false

                    },
                    {
                        text: "Cum",
                        align: 'center',
                        value: "",
                        sortable: false

                    },
                    {
                        text: "Estado Registro",
                        align: 'center',
                        value: "",
                        sortable: false

                    },
                    {
                        text: "Acciones",
                        align: 'center',
                        value: "",
                        sortable: false

                    }
                ],
                headersPrecio: [{
                        text: "Proveedor",
                        align: 'center',
                        value: "Nombre",
                        sortable: true
                    },
                    {
                        text: "Precio Unidad",
                        align: 'center',
                        value: "precio_unidad",
                        sortable: true
                    },
                    {
                        text: "Iva",
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: "Iva Facturación",
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: "Precio de Venta",
                        align: 'center',
                        value: "precio_venta",
                        sortable: true
                    },
                    {
                        text: "",
                        align: 'center'
                    },
                ],
                dialogEditPrecio: false,
                formDetallePrecio: {},
                precioActual: '',
            }
        },
        computed: {
            ...mapGetters(['can'])
        },
        watch: {
            nombre: function () {
                if (this.nombre && this.nombre.length == 4) {
                    this.getCums();
                }
            }
        },
        mounted() {
            this.fetchDetallearticulos();
            this.getGrupos();
            this.getTipos();
            this.getGruposTerapeuticos();
            this.getSubGruposTerapeuticos();
            this.getFormasFarmaceuticas();
            this.getViasAdministracion();
            this.getLineasBase();
            this.getUnidadMedida();
        },
        methods: {
            fetchDetallearticulos() {
                this.preload = true
                axios.get('/api/detallearticulo/getMedicamentos')
                    .then(res => {
                        this.medicamentos = res.data;
                        this.preload = false
                    }).catch(a => {
                        this.preload = false
                    });
            },
            modalCodesumi(save, item = {}) {
                this.dialogCreateCodigoSumi = true
                this.form = item;
                if (item.hasOwnProperty('Codigo')) {
                    this.form.Requiere_autorizacion = (item.Requiere_autorizacion === "SI");
                    this.form.vias = [];
                    item.viaadministracion.forEach(s => {
                        this.form.vias.push(parseInt(s.viadministracion_id))
                    })
                }
                this.save = save;
            },
            saveCodesumi() {
                let tipo = (this.save === true ? 0 : 1)
                // if (this.form.vias !== undefined) {
                //     if (this.form.vias.length === 0) {
                //         this.$alerError("Campo Via Administracion obligatorio");
                //         return;
                //     }
                // } else {
                //     this.$alerError("Campo Via Administracion obligatorio");
                //     return;
                // }
                axios.post('/api/detallearticulo/saveCodesumi/' + tipo, this.form).then(res => {
                    if (res.data.type === 'success') {
                        this.dialogCreateCodigoSumi = false;
                        this.$alerSuccess(res.data.message);
                        this.fetchDetallearticulos();
                        this.form = {};
                    } else {
                        this.$alerError(res.data.message);
                    }
                })
            },
            saveDetalleCodesumi() {
                let tipo = (this.saveDetalle === true ? 0 : 1)
                if (this.saveDetalle) {
                    this.formDetalle.codigoSumi = this.codigoSumi;
                }
                axios.post('/api/detallearticulo/saveDetalle/' + tipo, this.formDetalle).then(
                    res => {
                        if (res.data.type === 'success') {
                            this.dialogCreateCodigoSumi = false;
                            this.dialogCreateDetalle = false
                            this.$alerSuccess(res.data.message);
                            this.fetchDetallearticulos();
                            this.form = {};
                            this.formDetalle = {};
                        } else {
                            this.$alerError(res.data.message);
                        }
                    }
                )
            },
            saveDetalleCodesumiPrecio() {
                if (this.formDetalle.proveedor == undefined || this.formDetalle.precio_unidad == null) {
                    this.$alerError('Hay campos requeridos vacios (Precio Unidad, Proveedor).')
                    return;
                }
                let data = {
                    detallearticulo_id: this.formDetalle.id,
                    sedeproveedore_id: this.formDetalle.proveedor,
                    precio_venta: this.formDetalle.precio_venta,
                    precio_unidad: this.formDetalle.precio_unidad,
                    iva_facturacion: this.formDetalle.iva_facturacion,
                    iva: this.formDetalle.iva
                }
                axios.post('/api/detallearticulo/saveDetallePrecio/', data).then(
                    res => {
                        axios.get('/api/detallearticulo/getDetallePrecio/' + data.detallearticulo_id).then(res => {
                            this.detallePrecios = res.data
                        })
                        this.$alerSuccess('Precio del detalle creado con exito!');
                        this.formDetalle.proveedor = ''
                        this.formDetalle.precio_venta = ''
                        this.formDetalle.precio_unidad = ''
                        this.formDetalle.iva_facturacion = ''
                        this.formDetalle.iva = ''
                    }
                ).catch(err => {
                    this.$alerError('Precio del detalle ya existe!');
                })
            },
            modalDetalle(save, item = {}) {
                console.log(item);
                const estadoId = item.Estado_id;
                this.codigoSumi = (item.Codigo ? item.Codigo : item.codigoSumi);
                this.formDetalle = item;
                if (item.hasOwnProperty('Cum_Validacion')) {
                    axios.get('/api/detallearticulo/getDetallePrecio/' + item.id).then(res => {
                        this.detallePrecios = res.data
                    })
                    this.getProveedores();
                    this.formDetalle.Requiere_autorizacion = (item.Requiere_autorizacion === "SI")
                    this.formDetalle.Estado_id = (parseInt(estadoId) === 1)
                    this.formDetalle.Regulado = (item.Regulado === "SI")
                    this.formDetalle.Alto_Costo = (item.Alto_Costo === "SI")
                    this.formDetalle.Acuerdo_228 = (item.Acuerdo_228 === "SI")
                    this.formDetalle.oncologico = (parseInt(item.oncologico) === 1)
                    this.formDetalle.comercial = (item.comercial === "1");
                    this.formDetalle.refrigerado = (item.refrigerado === "1");
                    this.formDetalle.control_especial = (item.control_especial === "1");
                    this.formDetalle.costoso = (item.costoso === "1");
                    this.formDetalle.generico = (item.generico === "1");
                    this.formDetalle.critico = (item.critico === "1");
                }
                this.dialogCreateDetalle = true
                this.saveDetalle = save;
            },
            getGrupos() {
                axios.get('/api/detallearticulo/getGrupos')
                    .then(res => {
                        this.grupos = res.data
                    });
            },
            getTipos() {
                axios.get('/api/detallearticulo/getTipos')
                    .then(res => {
                        this.tipos = res.data
                    });
            },
            getGruposTerapeuticos() {
                axios.get('/api/detallearticulo/getGruposTerapeuticos')
                    .then(res => {
                        this.gruposTerapeuticos = res.data
                    });
            },
            getProveedores() {
                axios.get('/api/detallearticulo/proveedoresMedicamentos')
                    .then(res => {
                        this.proveedores = res.data.map(pr => ({
                            id: pr.id,
                            fullname: `${pr.Nit} - ${pr.Nombre}`
                        }));
                    });
            },
            getSubGruposTerapeuticos() {
                axios.get('/api/detallearticulo/getSubGruposTerapeuticos')
                    .then(res => {
                        this.subGruposTerapeuticos = res.data
                    });
            },
            getFormasFarmaceuticas() {
                axios.get('/api/detallearticulo/getFormasFarmaceuticas')
                    .then(res => {
                        this.formasFarmaceuticas = res.data
                    });
            },
            getViasAdministracion() {
                axios.get('/api/detallearticulo/getViasAdministracion')
                    .then(res => {
                        this.viasAdministracion = res.data
                    });
            },
            getLineasBase() {
                axios.get('/api/detallearticulo/getLineasBase')
                    .then(res => {
                        this.lineasBase = res.data
                    });
            },
            getUnidadMedida() {
                axios.get('/api/detallearticulo/getUnidadMedida')
                    .then(res => {
                        this.unidadMedida = res.data
                    });
            },
            getCums() {
                this.loading = true
                axios.get('/api/detallearticulo/getCums/' + this.nombre)
                    .then(res => {
                        this.cums = res.data.map((c) => {
                            return {
                                id: c.Cum_Validacion,
                                fullname: `${c.Cum_Validacion} ${c.Producto} (${c.Titular})`
                            }
                        })
                        this.loading = false
                    });
            },
            findCum(item) {
                axios.get('/api/detallearticulo/findCum/' + item).then(res => {
                    this.formDetalle.titular = res.data.Titular
                    this.formDetalle.Codigo = this.codigoSumi + "-" + res.data.titular_id
                    this.formDetalle.Principio_Activo = res.data.Principio_Activo
                    this.formDetalle.Registro_Sanitario = res.data.Registro_Sanitario
                    this.formDetalle.Fecha_Vencimiento = res.data.Fecha_Vencimiento
                    this.formDetalle.presentacion_comercial = res.data.Descripción_Comercial
                    this.formDetalle.Estado_Registro = res.data.Estado_Registro
                    this.formDetalle.Muestra_Medica = res.data.Muestra_Medica
                })
            },
            salirModales() {
                this.medicamentos = [];
                this.dialogCreateDetalle = false;
                this.dialogCreateCodigoSumi = false;
                this.formDetalle = {};
                this.form = {};
                this.fetchDetallearticulos();
            },
            openEditPrecio(item) {
                this.precioActual = item.precio_unidad
                this.dialogEditPrecio = true
                this.formDetallePrecio = item
            },
            editDetallePrecio() {
                if (this.precioActual != this.formDetallePrecio.precio_unidad) {
                    this.formDetallePrecio.precioDiferente = true
                }
                if (this.formDetallePrecio.precio_unidad == '' || this.formDetallePrecio.precio_unidad == null) {
                    this.$alerError('Precio unidad no puede estar vacio!');
                    return
                }
                axios.post('/api/detallearticulo/editDetallePrecio', this.formDetallePrecio).then(
                    res => {
                        this.closeDetallePrecio();
                        this.$alerSuccess('Precio del detalle editado con exito!');
                    }
                ).catch(err => {
                    this.$alerError('Hubo un error!');
                })
            },
            closeDetallePrecio() {
                this.dialogEditPrecio = false
                axios.get('/api/detallearticulo/getDetallePrecio/' + this.formDetallePrecio.detallearticulo_id).then(
                    res => {
                        this.detallePrecios = res.data
                    })
            }

        },
    }

</script>
<style lang="scss" scoped>

</style>
