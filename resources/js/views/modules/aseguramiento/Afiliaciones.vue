<template>
  <div>
    <v-card>
      <v-card-title>
        <v-toolbar flat color="white">
          <v-toolbar-title>AFILIACIONES PORTABILIDAD ENTRADA</v-toolbar-title>
          <v-divider class="mx-2" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <template v-slot:activator="{ on }">
              <v-btn
                color="primary"
                dark
                v-on="on"
                @click="fecthEstados(), fetchMunicipios(), fetchEntidad()"
              >
                Nuevo
                Afiliado
              </v-btn>
            </template>
            <v-card>
              <v-toolbar dark color="primary">
                <v-btn icon dark @click="dialog = false, clearCampos()">
                  <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Crear Paciente</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                  <v-btn dark flat @click="validacionPaciente()">Guardar</v-btn>
                </v-toolbar-items>
              </v-toolbar>
              <v-form>
                <v-container>
                  <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">
                      Datos
                      Afiliacion Usuarios
                    </span>
                  </v-card-title>
                  <v-layout wrap>
                    <v-flex xs12 md3>
                      <v-select :items="region" v-model="data.Region" label="Region"></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select :items="ut" v-model="data.Ut" label="UT"></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select :items="Tipo_Doc" v-model="data.Tipo_Doc" label="Tipo de Documento"></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field v-model="data.Num_Doc" label="Numero de Documento"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field v-model="data.Primer_Nom" label="Primer Nombre"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field v-model="data.SegundoNom" label="Segundo Nombre"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field v-model="data.Primer_Ape" label="Primer Apellido"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field v-model="data.Segundo_Ape" label="Segundo Apellido"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select :items="sexo" v-model="data.Sexo" label="Sexo"></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field
                        type="date"
                        v-model="data.Fecha_Naci"
                        label="Fecha de Nacimiento"
                      ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field
                        type="date"
                        v-model="data.Fecha_Afiliado"
                        label="Fecha de Afiliacion"
                      ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select :items="parentesco" v-model="data.Parentesco" label="Parentesco"></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select
                        :items="Tipo_Doc"
                        v-model="data.TipoDoc_Cotizante"
                        label="Tipo de Doc. Cotizante"
                      ></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field v-model="data.Doc_Cotizante" label="Documento del Cotizante"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select
                        :items="TipoCotizante"
                        v-model="data.Tipo_Cotizante"
                        label="Tipo de Cotizante"
                      ></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select
                        :items="TipoAfiliado"
                        v-model="data.Tipo_Afiliado"
                        label="Tipo de Afiliado"
                      ></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select
                        :items="estados"
                        item-text="Nombre"
                        item-value="id"
                        v-model="data.estadoPaciente"
                        label="Estado de Afiliacion"
                      ></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select
                        :items="discapacidad"
                        v-model="data.Discapacidad"
                        label="Discapacidad"
                      ></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select
                        :items="gradoDiscapacidad"
                        v-model="data.Grado_Discapacidad"
                        label="Grado de Discapacidad"
                      ></v-select>
                    </v-flex>
                  </v-layout>
                  <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">
                      Datos
                      Geográficos de Afiliación en Hosvital - Fiduprevisora
                    </span>
                  </v-card-title>
                  <v-layout wrap>
                    <v-flex xs12 md3>
                      <v-autocomplete
                        append-icon="search"
                        :items="municipio"
                        item-text="municipio"
                        v-model="data.Mpio_Afiliado"
                        label="Municipio"
                        @change="DatosGeograficos()"
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-autocomplete
                        :items="datosGeo"
                        item-text="codigoDaneM"
                        v-model="data.Dane_Mpio"
                        label="Dane Municipio"
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-autocomplete
                        :items="datosGeo"
                        item-text="codigoDaneD"
                        v-model="data.Dane_Dpto"
                        label="Dane Departamento"
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-autocomplete
                        :items="datosGeo"
                        item-text="departamento"
                        v-model="data.Departamento"
                        label="Departamento"
                      ></v-autocomplete>
                    </v-flex>
                  </v-layout>
                  <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">
                      Datos
                      Geográficos Sedes de Atención en SUMIMEDICAL - RED DE PRESTADORES
                    </span>
                  </v-card-title>
                  <v-layout wrap>
                    <v-flex xs12 md3>
                      <v-select :items="subregion" v-model="data.Subregion" label="SubRegion"></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-autocomplete
                        append-icon="search"
                        :items="municipio"
                        item-text="municipio"
                        item-value="id"
                        v-model="data.municipioAtencion"
                        label="Municipio de Atencion"
                        @change="DatosAtencion()"
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-autocomplete
                        :items="datosAtencion"
                        item-value="departamento_id"
                        item-text="departamento"
                        v-model="data.departamentoAtencion"
                        label="Departamento de Atencion"
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-autocomplete
                        :items="datosAtencion"
                        v-model="data.sedeAtencion"
                        label="Ips de Atencion"
                        autocomplete
                        item-text="IpsAtencion"
                        item-value="idIps"
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select
                        :items="sedeOdonto"
                        v-model="data.Sede_Odontologica"
                        label="Sede Odontologia"
                      ></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field
                        v-model="data.Direccion_Residencia"
                        label="Direccion de Residencia"
                      ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field v-model="data.Mpio_Residencia" label="Municipio de Residencia"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field type="number" v-model="data.Telefono" label="Telefono"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field type="number" v-model="data.Celular1" label="Celular 1"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field type="number" v-model="data.Celular2" label="Celular 2"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field type="email" v-model="data.Correo1" label="Correo 1"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field type="email" v-model="data.Correo2" label="Correo 2"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field v-model="data.Medicofamilia" label="Medico de Familia"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field v-model="data.Etnia" label="Etnia"></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-autocomplete
                        label="Entidad"
                        :items="entidades"
                        v-model="data.entidad_id"
                        item-text="nombre"
                        item-value="entidad"
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select
                        :items="Portabilidad"
                        v-model="data.portabilidad"
                        text="Nombre"
                        value="id"
                        label="Portabilidad"
                      ></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-select
                        :items="ipsPortabilidad"
                        v-model="data.ipsorigen_portabilidad"
                        label="IPS origen Portabilidad"
                      ></v-select>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field
                        v-model="data.telefonoorigen_portabilidad"
                        label="Telefono IPS Portabilidad"
                      ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field
                        v-model="data.correoorigen_portabilidad"
                        label="Correo IPS Portabilidad"
                      ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field
                        type="date"
                        v-model="data.fechaInicio_portabilidad"
                        label="Fecha inicial Portabilidad"
                      ></v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                      <v-text-field
                        type="date"
                        v-model="data.fechaFinal_portabilidad"
                        label="Fecha Final Portabilidad"
                      ></v-text-field>
                    </v-flex>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-flex xs12>
                        <input id="adjunto" ref="adjunto" multiple type="file" />
                      </v-flex>
                    </v-card-actions>
                  </v-layout>
                </v-container>
              </v-form>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </v-card-title>
      <v-tabs fixed-tabs icons-and-text slider-color="blue">
        <v-tab href="#tab-1">
          Activos
          <v-icon>mdi-account</v-icon>
        </v-tab>

        <v-tab href="#tab-2">
          Historico
          <v-icon>mdi-file-document</v-icon>
        </v-tab>

        <v-tab-item :value="'tab-1'">
          <v-card-title>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table :headers="headers" :items="pacientes" :search="search" class="elevation-1">
            <template v-slot:items="props">
              <td class="text-xs-left">{{ props.item.id }}</td>
              <td class="text-xs-left">{{ props.item.Tipo_Doc }}</td>
              <td class="text-xs-left">{{ props.item.Num_Doc }}</td>
              <td class="text-xs-left">{{ props.item.Nombre }}</td>
              <td class="text-xs-left">{{ props.item.Ut }}</td>
              <td class="text-xs-left">{{ props.item.fechaInicio_portabilidad }}</td>
              <td class="text-xs-left">{{ props.item.fechaFinal_portabilidad }}</td>
              <td class="text-xs-left">{{ props.item.ipsorigen_portabilidad }}</td>
              <td>
                <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-btn small text icon color="info" dark v-on="on">
                      <v-icon @click="modal = true, detallepaciente(props.item.id)">visibility</v-icon>
                    </v-btn>
                  </template>
                  <span>Ver Mas</span>
                </v-tooltip>
                <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-btn small text icon color="error" dark v-on="on">
                      <v-icon @click="inactivarpaciente(props.item.id)">person_add_disabled</v-icon>
                    </v-btn>
                  </template>
                  <span>Inactivar</span>
                </v-tooltip>
                <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-btn small text icon color="success" dark v-on="on">
                      <v-badge color="red" rigth>
                        <template v-slot:badge>
                          <span>{{ props.item.conteoServicio[0].total }}</span>
                        </template>
                        <v-icon @click="modelOrdenes(props.item.id)">archive</v-icon>
                      </v-badge>
                    </v-btn>
                  </template>
                  <span>Ordenes</span>
                </v-tooltip>
                <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-btn small text icon color="success" dark v-on="on">
                      <v-badge color="red" rigth>
                        <template v-slot:badge>
                          <span>{{ props.item.conteoMedicamento[0].total }}</span>
                        </template>
                        <v-icon @click="modelMedicamento(props.item.id)">mdi-pill</v-icon>
                      </v-badge>
                    </v-btn>
                  </template>
                  <span>Medicamentos</span>
                </v-tooltip>
              </td>
            </template>
          </v-data-table>
        </v-tab-item>

        <v-tab-item :value="'tab-2'">
          <v-card-title>
            <v-text-field
              v-model="cedulaHistorico"
              append-icon="search"
              label="Cedula del paciente"
              single-line
              hide-details
            ></v-text-field>
            <v-btn dark color="success" @click="buscarHistorico()">Buscar</v-btn>
            <v-btn dark color="error" @click="limpiarHistorico()">Limpiar</v-btn>
          </v-card-title>
          <v-data-table :headers="headers" :items="pacientesHistorico" class="elevation-1">
            <template v-slot:items="props">
              <td class="text-xs-left">{{ props.item.id }}</td>
              <td class="text-xs-left">{{ props.item.Tipo_Doc }}</td>
              <td class="text-xs-left">{{ props.item.Num_Doc }}</td>
              <td class="text-xs-left">{{ props.item.Nombre }}</td>
              <td class="text-xs-left">{{ props.item.Ut }}</td>
              <td class="text-xs-left">{{ props.item.fechaInicio_portabilidad }}</td>
              <td class="text-xs-left">{{ props.item.fechaFinal_portabilidad }}</td>
              <td class="text-xs-left">{{ props.item.ipsorigen_portabilidad }}</td>
              <td>
                <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-btn small text icon color="info" dark v-on="on">
                      <v-icon @click="modal = true, detallepaciente(props.item.id)">visibility</v-icon>
                    </v-btn>
                  </template>
                  <span>Ver Mas</span>
                </v-tooltip>
              </td>
            </template>
          </v-data-table>
        </v-tab-item>
      </v-tabs>

      <v-dialog v-model="modal" persistent max-width="1400">
        <v-card>
          <v-card-title class="headline success" style="color:white" primary-title>
            <span class="title layout justify-center font-weight-light">PACIENTE PORTABILIDAD</span>
          </v-card-title>
          <v-card-text>
            <v-layout wrap align-center justify-center>
              <v-flex xs12>
                <v-layout row wrap>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Region" label="Region"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Ut" label="UT"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Tipo_Doc" label="Tipo de Documento"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Num_Doc" label="Numero de Documento"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Primer_Nom" label="Primer Nombre"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.SegundoNom" label="Segundo Nombre"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Primer_Ape" label="Primer Apellido"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Segundo_Ape" label="Segundo Apellido"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-select :items="sexo" v-model="detallePacientes.Sexo" label="Sexo"></v-select>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Fecha_Naci" label="Fecha de Nacimiento"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.Fecha_Afiliado"
                      label="Fecha de Afiliacion"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Parentesco" label="Parentesco"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.TipoDoc_Cotizante"
                      label="Tipo de Doc. Cotizante"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.Doc_Cotizante"
                      label="Documento del Cotizante"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      :items="TipoCotizante"
                      v-model="detallePacientes.Tipo_Cotizante"
                      label="Tipo de Cotizante"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      :items="TipoAfiliado"
                      v-model="detallePacientes.Tipo_Afiliado"
                      label="Tipo de Afiliado"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.estadoPaciente"
                      label="Estado de Afiliacion"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      :items="discapacidad"
                      v-model="detallePacientes.Discapacidad"
                      label="Discapacidad"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      :items="gradoDiscapacidad"
                      v-model="detallePacientes.Grado_Discapacidad"
                      label="Grado de Discapacidad"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
                <v-card-title class="headline success" style="color:white">
                  <span class="title layout justify-center font-weight-light">
                    Datos
                    Geográficos de Afiliación en Hosvital - Fiduprevisora
                  </span>
                </v-card-title>
                <v-layout wrap>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Mpio_Afiliado" label="Municipio"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Dane_Mpio" label="Dane Municipio"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Dane_Dpto" label="Dane Departamento"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Departamento" label="Departamento"></v-text-field>
                  </v-flex>
                </v-layout>
                <v-card-title class="headline success" style="color:white">
                  <span class="title layout justify-center font-weight-light">
                    Datos
                    Geográficos Sedes de Atención en SUMIMEDICAL - RED DE PRESTADORES
                  </span>
                </v-card-title>
                <v-layout wrap>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Subregion" label="SubRegion"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.municipioAtencion"
                      label="Municipio de Atencion"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.departamentoAtencion"
                      label="Departamento de Atencion"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.sedeAtencion" label="Ips de Atencion"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.Sede_Odontologica"
                      label="Sede Odontologia"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.Direccion_Residencia"
                      label="Direccion de Residencia"
                      readonly
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.Mpio_Residencia"
                      label="Municipio de Residencia"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      type="number"
                      v-model="detallePacientes.Telefono"
                      label="Telefono"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      type="number"
                      v-model="detallePacientes.Celular1"
                      label="Celular 1"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      type="number"
                      v-model="detallePacientes.Celular2"
                      label="Celular 2"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field type="email" v-model="detallePacientes.Correo1" label="Correo 1"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field type="email" v-model="detallePacientes.Correo2" label="Correo 2"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.Medicofamilia"
                      label="Medico de Familia"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.Etnia" label="Etnia"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.entidad_id" label="Entidad"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field v-model="detallePacientes.portabilidad" label="Portabilidad"></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      :items="ipsPortabilidad"
                      v-model="detallePacientes.ipsorigen_portabilidad"
                      label="IPS origen Portabilidad"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.telefonoorigen_portabilidad"
                      label="Telefono IPS Portabilidad"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      v-model="detallePacientes.correoorigen_portabilidad"
                      label="Correo IPS Portabilidad"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      type="date"
                      v-model="detallePacientes.fechaInicio_portabilidad"
                      label="Fecha inicial Portabilidad"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field
                      type="date"
                      v-model="detallePacientes.fechaFinal_portabilidad"
                      label="Fecha Final Portabilidad"
                    ></v-text-field>
                  </v-flex>
                  <v-spacer></v-spacer>
                  <v-card-actions>
                    <v-btn color="primary" fat @click="modal = false">cerrar</v-btn>
                  </v-card-actions>
                </v-layout>
              </v-flex>
            </v-layout>
          </v-card-text>
        </v-card>
      </v-dialog>

      <!-- modal de ordenes en estado 37 -->
      <v-dialog v-model="modalOrdenes" persistent max-width="1400">
        <v-card>
          <v-card-title class="headline success" style="color:white" primary-title>
            <span>ORDENES DE PORTABILIDAD ENTRADA</span>
            <v-spacer></v-spacer>
            <v-btn color="error" fat @click="modalOrdenes = false">cerrar</v-btn>
          </v-card-title>
          <v-data-table :headers="headersOrdenes" :items="ordenes" class="elevation-1">
            <template v-slot:items="props">
              <td class="text-xs-left">{{ props.item.orden }}</td>
              <td class="text-xs-left" v-show="props.item.codigoServicio != null">
                {{ props.item.codigoServicio
                }}
              </td>
              <td class="text-xs-left" v-show="props.item.codigoServicio == null">
                {{
                props.item.codigoMedicamento }}
              </td>
              <td
                class="text-xs-left"
                v-show="props.item.servicio != null"
              >{{ props.item.servicio }}</td>
              <td
                class="text-xs-left"
                v-show="props.item.servicio == null"
              >{{ props.item.medicamento }}</td>
              <td class="text-xs-left" v-show="props.item.fechaServicio == null">
                {{
                props.item.fechaMedicamento }}
              </td>
              <td class="text-xs-left" v-show="props.item.fechaServicio != null">
                {{ props.item.fechaServicio
                }}
              </td>
              <td>
                <v-btn
                  v-if="props.item.servicio != null"
                  color="green"
                  dark
                  @click="Anexo(props.item.cuporden_id, 'anexo3Servicios')"
                >Servicios</v-btn>
                <v-btn
                  v-if="props.item.servicio == null"
                  color="blue"
                  dark
                  @click="Anexo(props.item.detallearticulo_id, 'anexo3Medicamentos')"
                >Medicamento</v-btn>
              </td>
              <td>
                <v-btn color="red" dark @click="modalEstado(props.item)">Pasar a auditoria</v-btn>
              </td>
            </template>
          </v-data-table>
        </v-card>
      </v-dialog>

      <!-- Cambio de estado a pendiente por autorizar -->
      <v-dialog v-model="modalEstados" persistent max-width="500">
        <v-card>
          <v-card-text>
            <v-flex xs12>
              <span>Para pasar la orden a auditoria, debe adjuntar la autorizacion!</span>
            </v-flex>
            <v-flex xs12 sm6>
              <input
                type="file"
                id="file"
                ref="myFiles"
                class="custom-file-input"
                multiple
                v-on:change="onFilePicked"
              />
            </v-flex>
          </v-card-text>
          <v-card-actions>
            <v-btn color="error" fat @click="modalEstados = false">cerrar</v-btn>
            <v-btn color="success" dark @click="cambioEstado(datoOrden)">
              Pasar a
              auditoria
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <template>
        <div class="text-center">
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
    </v-card>
  </div>
</template>
<script>
import Swal from "sweetalert2";
export default {
  data() {
    return {
      entrada_activa: false,
      entrada_historico: false,
      dialog: false,
      modal: false,
      files: "",
      modalOrdenes: false,
      modalEstados: false,
      preload: false,
      search: "",
      cedulaHistorico: null,
      data: {},
      datoOrden: {},
      region: [
        "Región 1",
        "Región 2",
        "Región 3",
        "Región 4",
        "Región 5",
        "Región 6",
        "Región 7",
        "Región 8",
        "Región 9",
        "Región 10"
      ],
      ut: [
        "UNION TEMPORAL TOLIHUILA",
        "COSMITET LTDA CORPORACION DE SERVICIOS MEDICOS INTERNANCIONALES THEM Y CIA LTDA",
        "UNION TEMPORAL SALUDSUR2",
        "MEDISALUD UT",
        "UNION TEMPORAL DEL NORTE REGION CINCO",
        "ORGANIZACIÓN CLINICA GENERAL DEL NORTE S.A.",
        "UT RED INTEGRADA FOSCAL - CUB",
        "REDVITAL UT",
        "COSMITET LTDA. CORPORACION DE SERVICIOS MEDICOS INTERNACIONALES THEM Y CIA LTDA",
        "UNIÓN TEMPORAL SERVISALUD SAN JOSÉ"
      ],
      Tipo_Doc: ["RC", "TI", "CC", "CE", "PA", "CN", "PE", "SA"],
      sexo: ["M", "F"],
      parentesco: [
        "Padre o Madre",
        "Hijo Docente",
        "NO APLICA",
        "Conyuge o Compañero",
        "Hijo Conyuge",
        "Hijo Adoptivo",
        "Hijo Discapacitado",
        "Otro",
        "Nieto menor o igual a 30 días"
      ],
      TipoCotizante: [
        "Cotizante docente",
        "Cotizante Fallecido",
        "Cotizante Pensionado",
        "Beneficiario",
        "Sustituto Pensional"
      ],
      TipoAfiliado: [
        "BENEFICIARIO",
        "COTIZANTE",
        "Cotizante Fallecido",
        "Sustituto Pensional",
        "Cotizante Pensionado",
        "Cotizante Dependiente"
      ],
      estados: [],
      discapacidad: [
        "Sin discapacidad",
        "Mental/psíquica",
        "Física",
        "Auditiva",
        "Visual",
        "Sordo - Ceguera"
      ],
      gradoDiscapacidad: ["Moderada", "Leve", "Severa"],
      municipio: [],
      datosGeo: [],
      datosAtencion: [],
      Portabilidad: [
        {
          text: "SI",
          value: 1
        },
        {
          text: "NO",
          value: 2
        }
      ],
      subregion: [
        "Nordeste",
        "Suroeste",
        "Occidente",
        "Valle de Aburra",
        "Magdalena Medio",
        "Norte",
        "Oriente",
        "Urabá",
        "Bajo Cauca",
        "PuertosNacionales",
        "PtosSantander",
        "PtosAntioquia",
        "Santanderes",
        "Antioquia"
      ],
      sedeOdonto: [
        "SUMIMEDICAL QUIBDO",
        "SUMIMEDICAL S.A.S SEDE APARTADO",
        "SUMIMEDICAL S.A.S SEDE BELLO",
        "SUMIMEDICAL S.A.S SEDE CAUCASIA",
        "SUMIMEDICAL S.A.S SEDE COPACABANA",
        "SUMIMEDICAL S.A.S SEDE ENVIGADO",
        "SUMIMEDICAL S.A.S SEDE ESTADIO",
        "SUMIMEDICAL S.A.S SEDE ITAGUI",
        "SUMIMEDICAL S.A.S SEDE PUERTO BERRIO",
        "SUMIMEDICAL S.A.S SEDE RIONEGRO",
        "SUMIMEDICAL S.A.S SEDE TURBO",
        "SUMIMEDICAL S.A.S SEDE VILLANUEVA",
        "SUMIMEDICAL SEDE BOLIVIA",
        "SUMIMEDICAL SEDE CALDAS",
        "SUMIMEDICAL SEDE CHIGORODO",
        "NO APLICA"
      ],
      entidades: [],
      tipoCategoria: ["1", "2", "3"],
      ipsPortabilidad: [
        "UNION TEMPORAL TOLIHUILA",
        "COSMITET LTDA CORPORACION DE SERVICIOS MEDICOS INTERNANCIONALES THEM Y CIA LTDA",
        "UNION TEMPORAL SALUDSUR2",
        "MEDISALUD UT",
        "UNION TEMPORAL DEL NORTE REGION CINCO",
        "ORGANIZACIÓN CLINICA GENERAL DEL NORTE S.A.",
        "UT RED INTEGRADA FOSCAL - CUB",
        "REDVITAL UT",
        "COSMITET LTDA. CORPORACION DE SERVICIOS MEDICOS INTERNACIONALES THEM Y CIA LTDA",
        "UNIÓN TEMPORAL SERVISALUD SAN JOSÉ"
      ],
      pacientes: [],
      pacientesHistorico: [],
      detallePacientes: [],
      adjunto: [],
      headers: [
        {
          text: "Id Paciente",
          align: "left",
          value: "id"
        },
        {
          text: "Tipo Doc",
          value: "Tipo_Doc"
        },
        {
          text: "# Documento",
          value: "Num_Doc"
        },
        {
          text: "Paciente",
          value: "Nombre"
        },
        {
          text: "Ut",
          value: "Ut"
        },
        {
          text: "Fecha Inicia",
          value: "fechaInicio_portabilidad"
        },
        {
          text: "Fecha Final",
          value: "fechaFinal_portabilidad"
        },
        {
          text: "Ips Portabilidad",
          value: "ipsorigen_portabilidad"
        }
      ],
      ordenes: [],
      headersOrdenes: [
        {
          text: "Orden",
          align: "left",
          value: "id"
        },
        {
          text: "codigo",
          value: "codigoServicio"
        },
        {
          text: "Servicio / Medicamento",
          value: "servicio"
        },
        {
          text: "Fecha orden",
          value: "fechaServicio"
        },
        {
          text: "Imprimir"
        },
        {
          text: "Acciones"
        }
      ]
    };
  },
  created() {
    this.fechtPacientes();
  },
  methods: {
    fecthEstados() {
      axios.get("/api/paciente/fetchEstados").then(res => {
        this.estados = res.data;
      });
    },
    fetchMunicipios() {
      axios.get("/api/municipio/lista").then(res => {
        this.municipio = res.data;
      });
    },
    DatosGeograficos() {
      axios
        .get("/api/municipio/DatosGeograficos/" + this.data.Mpio_Afiliado)
        .then(res => {
          this.datosGeo = res.data;
          this.data.Dane_Mpio = res.data.codigoDaneM;
        });
    },
    DatosAtencion() {
      axios
        .get("/api/municipio/DatosGeosede/" + this.data.municipioAtencion)
        .then(res => {
          this.datosAtencion = res.data;
        });
    },

    getUser() {
      axios.get("/api/user/all").then(
        res =>
          (this.users = res.data.map(us => {
            return {
              id: us.id,
              nombre: `${us.cedula} - ${us.name} ${us.apellido}`,
              estado: us.estado_user
            };
          }))
      );
    },

    fetchEntidad() {
      axios.get("/api/paciente/fetchEntidad").then(res => {
        this.entidades = res.data;
      });
    },
    validacionPaciente() {
      if (!this.data.Tipo_Doc) {
        this.$alerError("Campo de tipo de Documento no puede estar vacio");
        return;
      } else if (!this.data.Region) {
        this.$alerError("Campo de Region no puede estar vacio");
        return;
      } else if (!this.data.Num_Doc) {
        this.$alerError("Campo Numero de documento no puede estar vacio");
        return;
      } else if (!this.data.Ut) {
        this.$alerError("Campo Ut no puede estar vacio");
        return;
      } else if (!this.data.Primer_Nom) {
        this.$alerError("Campo Primer Nombre No puede estar vacio");
        return;
      } else if (!this.data.Primer_Ape) {
        this.$alerError("Campo Primer Apellido No puede estar vacio");
        return;
      } else if (!this.data.Segundo_Ape) {
        this.$alerError("Campo Segundo ApellidoNo puede estar vacio");
        return;
      } else if (!this.data.Sexo) {
        this.$alerError("Campo sexo No puede estar vacio");
        return;
      } else if (!this.data.Fecha_Afiliado) {
        this.$alerError("Campo fecha de afiliacion No puede estar vacio");
        return;
      } else if (!this.data.estadoPaciente) {
        this.$alerError("Campo estado del paciente No puede estar vacio");
        return;
      } else if (!this.data.sedeAtencion) {
        this.$alerError("Campo IPS de atencion No puede estar vacio");
        return;
      } else if (!this.data.entidad_id) {
        this.$alerError("Campo entidad No puede estar vacio");
        return;
      } else if (!this.data.portabilidad) {
        this.$alerError("Campo Portabilidad No puede estar vacio");
        return;
      } else if (!this.data.ipsorigen_portabilidad) {
        this.$alerError("Campo Ips de portabilidad No puede estar vacio");
        return;
      } else if (!this.data.telefonoorigen_portabilidad) {
        this.$alerError(
          "Campo telefono de la ips portabilidad No puede estar vacio"
        );
        return;
      } else if (!this.data.correoorigen_portabilidad) {
        this.$alerError(
          "Campo correo de la ips portabilidad No puede estar vacio"
        );
        return;
      } else if (!this.data.fechaInicio_portabilidad) {
        this.$alerError(
          "Campo fecha de inicio de la portabilidad No puede estar vacio"
        );
        return;
      } else if (!this.data.fechaFinal_portabilidad) {
        this.$alerError(
          "Campo fecha final de portabilidad No puede estar vacio"
        );
        return;
      }
      this.guardarPacientePortabilidad();
    },
    guardarPacientePortabilidad() {
      this.preload = true;
      axios
        .post("/api/paciente/guardarPacientePortabilidad", this.data)
        .then(res => {
          this.preload = false;
          this.$alerSuccess(res.data.mensaje);
          this.dialog = false;
          this.fechtPacientes();
          this.clearCampos();
        })
        .catch(error => {
          this.dialog = false;
          this.$alerError("Error al guardar!");
        });
    },

    fechtPacientes() {
      this.preload = true;
      axios
        .get("/api/paciente/fechtPacientes")
        .then(res => {
          this.preload = false;
          this.pacientes = res.data;
        })
        .catch(err => console.log(err.response));
    },

    detallepaciente(id) {
      this.preload = true;
      axios
        .get("/api/paciente/detallepaciente/" + id)
        .then(res => {
          this.preload = false;
          this.detallePacientes = res.data;
        })
        .catch(err => console.log(err.response));
    },
    inactivarpaciente(id) {
      Swal.fire({
        title: "Esta seguro que desea inactivar el paciente ?",
        text:
          "Recuerde que despues de inactivar el paciente, solo puede activarlo con autorizacion",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#4caf50",
        cancelButtonColor: "#d33",
        confirmButtonText: "Inactivar"
      }).then(result => {
        this.preload = true;
        if (result.isConfirmed) {
          axios
            .get("/api/paciente/inactivarpaciente/" + id)
            .then(res => {
              this.preload = false;
              this.$alerSuccess(res.data.mensaje);
              this.fechtPacientes();
            })
            .catch(err => console.log(err.response));
        } else {
          this.preload = false;
        }
      });
    },
    clearCampos() {
      for (const key in this.data) {
        this.data[key] = "";
      }
    },
    modelOrdenes(id) {
      this.preload = true;
      axios
        .get("/api/paciente/buscarOrdenes/" + id)
        .then(res => {
          this.preload = false;
          this.ordenes = res.data;
          this.modalOrdenes = true;
        })
        .catch(err => console.log(err.response));
    },

    modelMedicamento(id) {
      this.preload = true;
      axios
        .get("/api/paciente/buscarMedicamentos/" + id)
        .then(res => {
          this.preload = false;
          this.ordenes = res.data;
          this.modalOrdenes = true;
        })
        .catch(err => console.log(err.response));
    },

    async Anexo(id, tipo) {
      const pdf = {
        type: "Anexo",
        id: id,
        tipos: tipo
      };
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
    },

    modalEstado(datos) {
      this.datoOrden = datos;
      this.modalEstados = true;
    },

    onFilePicked() {
      this.files = this.$refs.myFiles.files;
    },

    async cambioEstado(item) {
      const items = item;
      if (this.files.length > 0) {
        let formData = new FormData();

        for (var i = 0; i < this.files.length; i++) {
          let file = this.files[i];

          formData.append("files[" + i + "]", file);
        }

        await axios
          .post(`/api/file/setFiles/${items.citapaciente_id}`, formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          })
          .then(function() {
            console.log("SUCCESS!!");
          })
          .catch(err => {
            console.log(err.response);
          });
      } else {
        return this.$alerError("Debe adjuntar la autorizacion");
      }
      this.preload = true;
      axios
        .post("/api/paciente/cambioEstado", items)
        .then(res => {
          this.preload = false;
          this.$alerSuccess(res.data.mensaje);
          this.fechtPacientes();
          this.modalEstados = false;
          this.modalOrdenes = false;
        })
        .catch(err => console.log(err.response));
    },

    buscarHistorico() {
      if (this.cedulaHistorico == null) {
        return this.$alerError("Debe digitar una cedula");
      }
      this.preload = true;
      axios
        .get("/api/paciente/portabilidadHistorico/" + this.cedulaHistorico)
        .then(res => {
          this.preload = false;
          this.pacientesHistorico = res.data;
          if(this.pacientesHistorico.length <= 0) {
            return this.$alerError(
              "Paciente no tiene historico de portabilidad"
            );
          }
        })
        .catch(err => console.log(err.response));
    },

    limpiarHistorico() {
      this.pacientesHistorico = [];
      this.cedulaHistorico = null;
    }
  }
};
</script>
