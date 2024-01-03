<template>
  <div>
    <v-flex shrink xs4 mr-1>
      <v-card max-height="100%" class="mb-3">
        <v-card-title class="headline success" style="color: white">
          <span class="justify-center title layout font-weight-light"
            >Buscar Paciente</span
          >
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-container grid-list-md fluid class="pa-0">
            <v-layout wrap align-center justify-center>
              <v-flex xs12>
                <v-form @submit.prevent="search_paciente()">
                  <v-layout row wrap>
                    <v-flex xs10>
                      <v-text-field
                        v-model="cedula_paciente"
                        label="Número de Documento"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs2>
                      <v-btn
                        @click="search_paciente()"
                        @keyup.enter="search_paciente()"
                        v-if="!paciente.id"
                        fab
                        outline
                        small
                        color="success"
                      >
                        <v-icon>search</v-icon>
                      </v-btn>
                      <v-btn
                        @click="clearFields()"
                        v-if="paciente.id"
                        fab
                        outline
                        small
                        color="error"
                      >
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
    <v-card max-height="100%" class="mb-3" v-if="paciente.id != null">
      <v-card-title class="headline success" style="color: white">
        <span class="justify-center title layout font-weight-light"
          >Información General del Usuario</span
        >
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <template>
          <template>
            <v-btn v-if="paciente.Estado_Afiliado == 1" color="success">
              <div>
                {{ paciente.id }} - {{ paciente.Primer_Nom }} -
                {{ paciente.SegundoNom }} - {{ paciente.Primer_Ape }} -
                {{ paciente.Ut }}
              </div>
            </v-btn>
            <v-btn v-if="paciente.Estado_Afiliado == 27" color="error">
              <div>
                {{ paciente.id }} - {{ paciente.Primer_Nom }} -
                {{ paciente.SegundoNom }} - {{ paciente.Primer_Ape }} -
                {{ paciente.Ut }}
              </div>
            </v-btn>
            <v-btn v-if="paciente.Estado_Afiliado == 28" color="info">
              <div>
                {{ paciente.id }} - {{ paciente.Primer_Nom }} -
                {{ paciente.SegundoNom }} - {{ paciente.Primer_Ape }} -
                {{ paciente.Ut }}
              </div>
            </v-btn>
            <v-btn v-if="paciente.Estado_Afiliado == 29" color="info">
              <div>
                {{ paciente.id }} - {{ paciente.Primer_Nom }} -
                {{ paciente.SegundoNom }} - {{ paciente.Primer_Ape }} -
                {{ paciente.Ut }}
              </div>
            </v-btn>
          </template>
          <v-card>
            <v-card-text>
              <v-container grid-list-md fluid class="pa-0">
                <v-layout wrap align-center justify-center>
                  <v-flex xs12>
                    <v-layout row wrap>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Region"
                          label="Region"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Ut"
                          label="Ut"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Primer_Nom"
                          label="Primer_Nom"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.SegundoNom"
                          label="SegundoNom"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Primer_Ape"
                          label="Primer_Ape"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Segundo_Ape"
                          label="Segundo_Ape"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-select
                          :items="Tipo_Doc"
                          v-model="paciente.Tipo_Doc"
                          label="Tipo_Doc"
                        >
                        </v-select>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Num_Doc"
                          label="Num_Doc"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-select
                          :items="sexo"
                          item-value="id"
                          item-text="nombre"
                          v-model="paciente.Sexo"
                          label="Sexo"
                        ></v-select>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Fecha_Afiliado"
                          label="Fecha_Afiliado"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Fecha_Naci"
                          label="Fecha_Naci"
                          type="date"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Edad_Cumplida"
                          label="Edad_Cumplida"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          :items="[
                            'Sin discapacidad',
                            'Mental/psiquica',
                            'Fisica',
                            'Mental/psíquica',
                            'Sordo - Ceguera',
                            'Visual',
                            'Cognitiva',
                            'Auditiva',
                            'Multiple',
                            'Física',
                          ]"
                          v-model="paciente.Discapacidad"
                          label="Discapacidad"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex
                        xs12
                        md3
                        v-if="paciente.Discapacidad != 'Sin discapacidad'"
                      >
                        <v-autocomplete
                          :items="[
                            'Leve',
                            'Moderada',
                            'Severa',
                            'Grave',
                            'Permanente',
                            'Muy grave',
                          ]"
                          v-model="paciente.Grado_Discapacidad"
                          label="Grado de Discapacidad"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          :items="[
                            'BENEFICIARIO',
                            'COTIZANTE DEPENDIENTE',
                            'COTIZANTE DOCENTE',
                            'COTIZANTE FALLECIDO',
                            'COTIZANTE PENSIONADO',
                            'SUSTITUTO PENSIONAL',
                          ]"
                          v-model="paciente.Tipo_Afiliado"
                          label="Tipo de Afiliado"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          :items="[
                            'HIJO DOCENTE',
                            'CÓNYUGE O COMPAÑERO',
                            'HIJO ADOPTIVO',
                            'HIJO CONYUGE',
                            'HIJO DISCAPACITADO',
                            'HIJO DOCENTE',
                            'NIETO MENOR O IGUAL A 30 DÍAS',
                            'NO APLICA',
                            'OTRO',
                            'PADRE O MADRE',
                          ]"
                          v-model="paciente.Parentesco"
                          label="Parentesco"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.TipoDoc_Cotizante"
                          label="TipoDoc_Cotizante"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Doc_Cotizante"
                          label="Doc_Cotizante"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          :items="[
                            'COTIZANTE DEPENDIENTE',
                            'COTIZANTE DOCENTE',
                            'COTIZANTE FALLECIDO',
                            'COTIZANTE PENSIONADO',
                          ]"
                          v-model="paciente.Tipo_Cotizante"
                          label="Tipo de Cotizante"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-select
                          :items="documento"
                          item-text="nombre"
                          item-value="id"
                          v-model="paciente.Estado_Afiliado"
                          label="Estado_Afiliado"
                        >
                        </v-select>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Mpio_Afiliado"
                          label="Mpio_Afiliado"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          :items="departamentos"
                          item-text="Nombre"
                          v-model="paciente.Departamento"
                          label="Departamento"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Subregion"
                          label="Subregion"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          :items="departamentos"
                          item-text="Nombre"
                          item-value="id"
                          v-model="paciente.Dpto_Atencion"
                          label="Dpto_Atencion"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          :items="municipios"
                          item-text="Nombre"
                          item-value="id"
                          v-model="paciente.Mpio_Atencion"
                          label="Mpio_Atencion"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          :items="sedesproveedores"
                          item-text="NombreIPS"
                          item-value="id"
                          v-model="paciente.IPS"
                          label="IPS"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Sede_Odontologica"
                          label="Sede_Odontologica"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          v-model="paciente.Medicofamilia"
                          :items="miMedicoFamiliar"
                          item-text="Medicofamilia"
                          item-value="id"
                          label="Medico familia"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-select
                          :items="Etnia"
                          v-model="paciente.Etnia"
                          label="Etnia"
                        >
                        </v-select>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Nivel_Sisben"
                          label="Nivel sisben"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Laboraen"
                          label="Labora en"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          :items="municipios"
                          item-text="Nombre"
                          item-value="id"
                          v-model="paciente.Mpio_Labora"
                          label="Municipio donde labora"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Direccion_Residencia"
                          label="Direccion de residencia"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Mpio_Residencia"
                          label="Municipio de residencia"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Telefono"
                          label="Telefono"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Correo1"
                          label="Correo1"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Correo2"
                          label="Correo2"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Estrato"
                          label="Estrato"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Celular1"
                          label="Celular1"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Celular2"
                          label="Celular2"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Sexo_Biologico"
                          label="Sexo biologico"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Tipo_Regimen"
                          label="Tipo de regimen"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Num_Hijos"
                          label="Numero de hijos"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Vivecon"
                          label="Vivecon"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-select
                          :items="RH"
                          v-model="paciente.RH"
                          label="RH"
                        ></v-select>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Nivelestudio"
                          label="Nivel de estudio"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Nombreacompanante"
                          label="Nombre acompanante"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Telefonoacompanante"
                          label="Telefono acompanante"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Nombreresponsable"
                          label="Nombre responsable"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Telefonoresponsable"
                          label="Telefono responsable"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          :items="entidades"
                          item-text="nombre"
                          item-value="id"
                          v-model="paciente.entidad_id"
                          label="Entidad Aseguradora"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Tipovinculacion"
                          label="Tipo vinculacion"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Ocupacion"
                          label="Ocupacion"
                          >d
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.nivel"
                          label="nivel"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.tipo_categoria"
                          label="tipo categoria"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.ut_saliente"
                          label="ut saliente"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.estado_civil"
                          label="estado civil"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.vivienda"
                          label="vivienda"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.portabilidad"
                          label="portabilidad"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.regional"
                          label="regional"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.tipo_vivienda"
                          label="tipo_vivienda"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.zona_vivienda"
                          label="zona_vivienda"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.mascota"
                          label="mascota"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.nombre_pareja"
                          label="nombre_pareja"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.pareja_actual_padre"
                          label="pareja_actual_padre"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-autocomplete
                          :items="municipios"
                          item-text="Nombre"
                          item-value="id"
                          v-model="paciente.municipio_nacimiento"
                          label="Municipio de nacimiento"
                        >
                        </v-autocomplete>
                      </v-flex>
                      <v-autocomplete
                        :items="paises"
                        item-text="nombre"
                        item-value="id"
                        v-model="paciente.pais_nacimiento"
                        label="País de nacimiento"
                      >
                      </v-autocomplete>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.Otradiscapacidad"
                          label="Otradiscapacidad"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-select
                          :items="victimaSINO"
                          item-value="id"
                          item-text="nombre"
                          v-model="paciente.victima_conficto_armado"
                          label="Víctima de conflicto armado"
                        >
                        </v-select>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-select
                          :items="domiciliariaSINO"
                          item-value="id"
                          item-text="nombre"
                          v-model="paciente.domiciliaria"
                          label="Domiciliaria"
                        >
                        </v-select>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.edad_horus"
                          label="edad_horus"
                        >
                        </v-text-field>
                      </v-flex>
                      <v-flex xs12 md3>
                        <v-text-field
                          v-model="paciente.fecha_nacimiento_horus"
                          label="fecha_nacimiento_horus"
                        ></v-text-field>
                      </v-flex>

                      <v-flex>
                        <v-btn color="success" @click="modalNovedades = true">
                          Actualizar Datos
                        </v-btn>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                </v-layout>
                <v-spacer></v-spacer>
              </v-container>
            </v-card-text>
          </v-card>
        </template>
      </v-card-text>
    </v-card>

    <v-dialog v-model="pacienteCreacion" persistent max-width="1200px">
      <v-card>
        <v-card-title class="headline info" style="color: white">
          <span class="justify-center title layout font-weight-light"
            >Crearción de nuevo paciente</span
          >
        </v-card-title>
        <v-card-text>
          <v-container grid-list-md>
            <v-form
              @submit.prevent="guardar_paciente(paciente.Cedula_paciente)"
            >
              <v-layout row wrap>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Region"
                    label="Region"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete :items="UT"
                    v-model="crearPaciente.Ut"
                    label="Ut"
                  ></v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Primer_Nom"
                    label="Primer Nombre"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.SegundoNom"
                    label="Segundo Nombre"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Primer_Ape"
                    label="Primer Apellido"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Segundo_Ape"
                    label="Segundo Apellido"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-select
                    :items="Tipo_Doc"
                    v-model="crearPaciente.Tipo_Doc"
                    label="Tipo Documento"
                  >
                  </v-select>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    type="number"
                    v-model="crearPaciente.Num_Doc"
                    label="Cedula del paciente"
                    readonly
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-select
                    :items="sexo"
                    item-value="id"
                    item-text="nombre"
                    v-model="crearPaciente.Sexo"
                    label="Sexo"
                  ></v-select>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Fecha_Afiliado"
                    label="Fecha Afiliado"
                    type="date"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Fecha_Naci"
                    label="Fecha Nacimiento"
                    type="date"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Edad_Cumplida"
                    label="Edad Cumplida"
                    type="number"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="[
                      'Sin discapacidad',
                      'Mental/psiquica',
                      'Fisica',
                      'Mental/psíquica',
                      'Sordo - Ceguera',
                      'Visual',
                      'Cognitiva',
                      'Auditiva',
                      'Multiple',
                      'Física',
                    ]"
                    v-model="crearPaciente.Discapacidad"
                    label="Discapacidad"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex
                  xs12
                  md3
                  v-if="paciente.Discapacidad != 'Sin discapacidad'"
                >
                  <v-autocomplete
                    :items="[
                      'Leve',
                      'Moderada',
                      'Severa',
                      'Grave',
                      'Permanente',
                      'Muy grave',
                    ]"
                    v-model="crearPaciente.Grado_Discapacidad"
                    label="Grado de Discapacidad"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="[
                      'BENEFICIARIO',
                      'COTIZANTE DEPENDIENTE',
                      'COTIZANTE DOCENTE',
                      'COTIZANTE FALLECIDO',
                      'COTIZANTE PENSIONADO',
                      'SUSTITUTO PENSIONAL',
                    ]"
                    v-model="crearPaciente.Tipo_Afiliado"
                    label="Tipo de Afiliado"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="[
                      'HIJO DOCENTE',
                      'CÓNYUGE O COMPAÑERO',
                      'HIJO ADOPTIVO',
                      'HIJO CONYUGE',
                      'HIJO DISCAPACITADO',
                      'HIJO DOCENTE',
                      'NIETO MENOR O IGUAL A 30 DÍAS',
                      'NO APLICA',
                      'OTRO',
                      'PADRE O MADRE',
                    ]"
                    v-model="crearPaciente.Parentesco"
                    label="Parentesco"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.TipoDoc_Cotizante"
                    label="Tipo Doc Cotizante"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Doc_Cotizante"
                    label="Documento Cotizante"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="[
                      'COTIZANTE DEPENDIENTE',
                      'COTIZANTE DOCENTE',
                      'COTIZANTE FALLECIDO',
                      'COTIZANTE PENSIONADO',
                    ]"
                    v-model="crearPaciente.Tipo_Cotizante"
                    label="Tipo de Cotizante"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-select
                    :items="documento"
                    item-text="nombre"
                    item-value="id"
                    v-model="crearPaciente.Estado_Afiliado"
                    label="Estado Afiliado"
                  ></v-select>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="municipios"
                    item-text="Nombre"
                    item-value="id"
                    v-model="crearPaciente.Mpio_Afiliado"
                    label="Mpio Afiliado"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="departamentos"
                    item-text="Nombre"
                    v-model="crearPaciente.Departamento"
                    label="Departamento"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Subregion"
                    label="Subregion"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="departamentos"
                    item-text="Nombre"
                    item-value="id"
                    v-model="crearPaciente.Dpto_Atencion"
                    label="Dpto Atencion"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="municipios"
                    item-text="Nombre"
                    item-value="id"
                    v-model="crearPaciente.Mpio_Atencion"
                    label="Mpio Atencion"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="sedesproveedores"
                    item-text="NombreIPS"
                    item-value="id"
                    v-model="crearPaciente.IPS"
                    label="IPS"
                  ></v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Sede_Odontologica"
                    label="Sede Odontologica"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    v-model="crearPaciente.Medicofamilia"
                    :items="miMedicoFamiliar"
                    item-text="Medicofamilia"
                    item-value="id"
                    label="Medico familia"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-select
                    :items="Etnia"
                    v-model="crearPaciente.Etnia"
                    label="Etnia"
                  ></v-select>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Nivel_Sisben"
                    label="Nivel Sisben"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Laboraen"
                    label="Laboraen"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="municipios"
                    item-text="Nombre"
                    item-value="Nombre"
                    v-model="crearPaciente.Mpio_Labora"
                    label="Mpio Labora"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Direccion_Residencia"
                    label="Direccion Residencia"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="municipios"
                    item-text="Nombre"
                    item-value="Nombre"
                    v-model="crearPaciente.Mpio_Residencia"
                    label="Mpio Residencia"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Telefono"
                    label="Telefono"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Correo1"
                    label="Correo1"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Correo2"
                    label="Correo2"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Estrato"
                    type="number"
                    label="Estrato"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Celular1"
                    type="number"
                    label="Celular1"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Celular2"
                    type="number"
                    label="Celular2"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="sexo"
                    item-value="id"
                    item-text="nombre"
                    v-model="crearPaciente.Sexo_Biologico"
                    label="Sexo Biologico"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Tipo_Regimen"
                    label="Tipo Regimen"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Num_Hijos"
                    type="number"
                    label="Numero Hijos"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Vivecon"
                    label="Vivecon"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-select
                    :items="RH"
                    v-model="crearPaciente.RH"
                    label="RH"
                  ></v-select>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Nivelestudio"
                    label="Nivel estudio"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Nombreacompanante"
                    label="Nombre acompanante"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Telefonoacompanante"
                    label="Telefono acompanante"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Nombreresponsable"
                    label="Nombre responsable"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Telefonoresponsable"
                    label="Telefono responsable"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Aseguradora"
                    label="Aseguradora"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Tipovinculacion"
                    label="Tipo vinculacion"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Ocupacion"
                    label="Ocupacion"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.nivel"
                    label="nivel"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-autocomplete
                    :items="entidades"
                    item-text="nombre"
                    item-value="id"
                    v-model="crearPaciente.entidad_id"
                    label="Entidad Aseguradora"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.tipo_categoria"
                    label="tipo categoria"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.ut_saliente"
                    label="ut saliente"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.estado_civil"
                    label="estado civil"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.vivienda"
                    label="vivienda"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.portabilidad"
                    label="portabilidad"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.regional"
                    label="regional"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.tipo_vivienda"
                    label="tipo vivienda"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.zona_vivienda"
                    label="zona vivienda"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.mascota"
                    label="mascota"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.nombre_pareja"
                    label="nombre pareja"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.pareja_actual_padre"
                    label="pareja actual padre"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.municipio_nacimiento"
                    label="municipio nacimiento"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.pais_nacimiento"
                    label="pais nacimiento"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.Otradiscapacidad"
                    label="Otra discapacidad"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.victima_conficto_armado"
                    label="victima conficto armado"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.domiciliaria"
                    label="domiciliaria"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.edad_horus"
                    label="edad horus"
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.fecha_nacimiento_horus"
                    type="date"
                    label="fecha nacimiento horus"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 md3>
                  <v-text-field
                    v-model="crearPaciente.medicofamilia2"
                    label="medicofamilia2"
                  >
                  </v-text-field>
                </v-flex>
              </v-layout>
            </v-form>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" dark @click="pacienteCreacion = false"
            >Cerrar</v-btn
          >
          <v-btn color="success" dark @click="guardar_paciente()"
            >Guardar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="modalNovedades" width="500" persistent>
      <v-card>
        <v-card-title
          class="headline red lighten-2 justify-center"
          style="color: white"
          primary-title
        >
          Registrar Movimientos - {{ paciente ? paciente.id : "" }}
        </v-card-title>
        <form @submit.prevent="guardarNovedades">
          <v-card-text style="color: black">
            <v-container>
              <v-layout row wrap>
                <v-flex xs12 sm12 md12>
                  <v-select
                    label="Novedades"
                    :items="novedades"
                    v-model="novedad.tipoId"
                    autocomplete
                    item-text="Nombre"
                    item-value="id"
                  ></v-select>
                </v-flex>
                <v-flex xs12 md5>
                  <v-text-field
                    prepend-icon="calendar_today"
                    v-model="novedad.fecha"
                    label="Fecha De Novedad"
                    type="date"
                    color="primary"
                    required
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs12>
                  <v-textarea
                    outline
                    name="input-7-4"
                    v-model="novedad.causa"
                    label="Causa de la Novedad"
                    required
                  >
                  </v-textarea>
                </v-flex>
                <v-flex xs12>
                  <input
                    type="file"
                    id="file"
                    ref="myFiles"
                    class="custom-file-input"
                    multiple
                    v-on:change="onFilePicked()"
                  />
                </v-flex>
                <v-layout>
                  <v-spacer></v-spacer>
                  <v-card-actions>
                    <v-btn color="success" type="submit"
                      >Guardar Novedad
                    </v-btn>
                  </v-card-actions>
                </v-layout>
              </v-layout>
            </v-container>
          </v-card-text>
        </form>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="modalNovedades = false">cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
  name: "HorusHealthPaciente",

  data() {
    return {
      cedula_paciente: "",
      paciente: {},
      sedesproveedores: [],
      crearPaciente: {
        Region: "Region 8",
        Ut: "",
        Primer_Nom: "",
        SegundoNom: "",
        Primer_Ape: "",
        Segundo_Ape: "",
        Tipo_Doc: "",
        Num_Doc: "",
        Sexo: "",
        Fecha_Afiliado: "",
        Fecha_Naci: "",
        Edad_Cumplida: "",
        Discapacidad: "",
        Grado_Discapacidad: "",
        Tipo_Afiliado: "",
        Parentesco: "",
        TipoDoc_Cotizante: "",
        Doc_Cotizante: "",
        Tipo_Cotizante: "",
        Estado_Afiliado: "",
        Mpio_Afiliado: "",
        Departamento: "",
        Subregion: "",
        Dpto_Atencion: "",
        Mpio_Atencion: "",
        IPS: "",
        Sede_Odontologica: "",
        Medicofamilia: "",
        Etnia: "",
        Nivel_Sisben: "",
        Laboraen: "",
        Mpio_Labora: "",
        Direccion_Residencia: "",
        Mpio_Residencia: "",
        Telefono: "",
        Correo1: "",
        Correo2: "",
        Estrato: "",
        Celular1: "",
        Celular2: "",
        Sexo_Biologico: "",
        Tipo_Regimen: "",
        Num_Hijos: "",
        Vivecon: "",
        RH: "",
        Nivelestudio: "",
        Nombreacompanante: "",
        Telefonoacompanante: "",
        Nombreresponsable: "",
        Telefonoresponsable: "",
        Aseguradora: "",
        Tipovinculacion: "",
        Ocupacion: "",
        nivel: "",
        entidad_id: "",
        tipo_categoria: "",
        ut_saliente: "",
        estado_civil: "",
        vivienda: "",
        portabilidad: "",
        regional: "",
        tipo_vivienda: "",
        zona_vivienda: "",
        mascota: "",
        nombre_pareja: "",
        pareja_actual_padre: "",
        municipio_nacimiento: "",
        pais_nacimiento: "",
        Otradiscapacidad: "",
        victima_conficto_armado: "",
        domiciliaria: "",
        edad_horus: "",
        fecha_nacimiento_horus: "",
      },
      Tipo_Doc: [
        "CC",
        "CE",
        "PA",
        "RC",
        "TI",
        "AS",
        "CD",
        "CN",
        "MS",
        "PE",
        "SC",
        "PT",
      ],
      UT:["REDVITAL UT",
      "FERROCARRILES NACIONALES",
      "UNIVERSIDAD ANTIOQUIA"],
      Etnia: [
        "Indígena",
        "Gitano",
        "Raizal",
        "Palenquero",
        "Negro(a)",
        "Mulato(a)",
        "Afrocolombiano(a)",
        "Afro descendiente",
      ],
      estadoCivil: ["Soltero", "Casado", "Viudo", "Union Libre ", "Separado"],
      RH: ["O+", "O-", "A+", "A-", "B+", "B-", "AB+", "AB-"],
      departamentos: "",
      municipios: "",
      victimaSINO: [
        {
          id: 1,
          nombre: "SI",
        },
        {
          id: 0,
          nombre: "NO",
        },
      ],
      domiciliariaSINO: [
        {
          id: 1,
          nombre: "SI",
        },
        {
          id: 0,
          nombre: "NO",
        },
      ],
      paises: "",
      entidades: [],
      paises: [],
      documento: [
        {
          id: 1,
          nombre: "Activo",
        },
        {
          id: 27,
          nombre: "Retirado",
        },
      ],
      sexo: [
        {
          id: "M",
          nombre: "Masculino",
        },
        {
          id: "F",
          nombre: "Femenino",
        },
      ],
      pacienteCreacion: "",
      modalNovedades: false,
      formPaciente: {},
      novedad: {
        tipoId: null,
        fecha: null,
        fechaCreacion: null,
        causa: null,
      },
      novedades: [],
      files: "",
      novedadId: "",
      miMedicoFamiliar: [],
    };
  },
  computed: {
    ...mapGetters(["can"]),
  },

  created() {
    this.Departamentos();
    this.Municipios();
    this.fetchProveedores();
    this.fecthNovedades();
    this.listarEntidades();
    this.listarPaises();
    this.listarMedicos();
  },

  methods: {
    guardar_paciente() {
      if (!this.formPaciente.Num_Doc) {
        swal("El Número de Documento es Requerido");
        return;
      }
      if (!this.formPaciente.Primer_Nom) {
        swal("El primer nombre es Requerido");
        return;
      }
      if (!this.formPaciente.Primer_Ape) {
        swal("El primer apellido es Requerido");
        return;
      }
      if (!this.formPaciente.Tipo_Doc) {
        swal("El tipo documento es Requerido");
        return;
      }
      if (!this.formPaciente.Sexo) {
        swal("El sexo es Requerido");
        return;
      }
      if (!this.formPaciente.Fecha_Naci) {
        swal("La fecha nacimiento es Requerida");
        return;
      }
      axios
        .post("/api/paciente/guardarPaciente", this.formPaciente)
        .then((res) => {
          if (!res.data.paciente) {
            this.$alerSuccess(res.data.mensaje);
            this.pacienteCreacion = false;
          } else {
            this.$alerError(res.data.mensaje);
          }
        });
    },

    search_paciente() {
      if (!this.cedula_paciente) return;

      axios
        .post(`/api/paciente/verPaciente/${this.cedula_paciente}`)
        .then((res) => {
          if (res.data.paciente) {
            this.paciente = res.data.paciente;
            this.paciente.Dpto_Atencion = parseInt(
              res.data.paciente.Dpto_Atencion
            );
            this.paciente.Mpio_Atencion = parseInt(
              res.data.paciente.Mpio_Atencion
            );
            this.paciente.entidad_id = parseInt(res.data.paciente.entidad_id);
            this.paciente.Aseguradora = parseInt(res.data.paciente.Aseguradora);
            this.paciente.Mpio_Labora = parseInt(res.data.paciente.Mpio_Labora);
            this.paciente.victima_conficto_armado = parseInt(
              res.data.paciente.victima_conficto_armado
            );
            this.paciente.domiciliaria = parseInt(
              res.data.paciente.domiciliaria
            );
            this.paciente.municipio_nacimiento = parseInt(
              res.data.paciente.municipio_nacimiento
            );
            this.paciente.pais_nacimiento = parseInt(
              res.data.paciente.pais_nacimiento
            );
            this.paciente.Medicofamilia = parseInt(
              res.data.paciente.Medicofamilia
            );

            this.listarMedicos();
            //this.listarEntidades()
          } else {
            this.formPaciente.Num_Doc = this.cedula_paciente;
            this.crearPaciente.Num_Doc = this.cedula_paciente;
            this.pacienteCreacion = true;
          }
          if (res.data.message) this.showMessage(res.data.message);
        });
    },

    actualizarDatosPersonales() {
      axios
        .put(
          `/api/paciente/edit_ubicacion_data/${this.paciente.id}`,
          this.paciente
        )
        .then((res) => {
          this.modalNovedades = false;
        });
    },

    listarPaises() {
      axios.get("/api/covid/paises").then((res) => {
        this.paises = res.data;
      });
    },

    Departamentos() {
      axios.get("/api/departamento/all").then((res) => {
        this.departamentos = res.data;
      });
    },

    Municipios() {
      axios.get("/api/municipio/all").then((res) => {
        this.municipios = res.data;
      });
    },

    clearFields() {
      for (const key in this.paciente) {
        this.paciente[key] = "";
        this.cedula_paciente = "";
      }
    },

    fetchProveedores() {
      axios
        .get("/api/sedeproveedore/sedeproveedores")
        .then((res) => {
          this.sedesproveedores = res.data;
        })
        .catch((err) => console.log(err));
    },

    guardar_paciente() {
      if (!this.formPaciente.Num_Doc) {
        swal("El Número de Documento es Requerido");
        return;
      }
      axios.post("/api/paciente/create", this.crearPaciente).then((res) => {
        if (!res.data.estado) {
          this.$alerSuccess(res.data.mensaje);
          this.pacienteCreacion = false;
          this.creaPaciente = false;
          this.clearCrearPaciente();
        } else {
          this.$alerError(res.data.mensaje);
        }
      });
    },

    guardarNovedades: async function () {
      this.preload = true;
      if (!this.novedad.tipoId) {
        swal({
          title: "Novedad",
          text: "Debe Ingresar un tipo de novedad",
          timer: 4000,
          icon: "error",
          buttons: false,
        });
        return;
      }
      if (this.files.length <= 0) {
        swal({
          title: "Novedad",
          text: "Debe adjuntar por lo menos un archivo!",
          timer: 4000,
          icon: "error",
          buttons: false,
        });
        return;
      }
      const data = {
        novedad: this.novedad,
        paciente: this.paciente,
      };
      await axios
        .post("/api/paciente/guardarNovedades", data)
        .then((res) => {
          this.actualizarDatosPersonales();
          this.preload = false;
          this.modalNovedades = false;
          this.novedadId = res.data.Novedades.id;
          this.$alerSuccess("Se ha actualizado el usuario!");
        })
        .catch((error) => {
          this.preload = false;
        });
      await this.guardarAdjuntos(this.novedadId);
    },

    guardarAdjuntos: async function (novedadId) {
      this.preload = true;
      let formData = new FormData();

      for (var i = 0; i < this.files.length; i++) {
        let file = this.files[i];

        formData.append("files[" + i + "]", file);
      }
      await axios
        .post("/api/paciente/guardarAdjuntos/" + novedadId, formData)
        .then((res) => {
          this.preload = false;
          this.$alerSuccess("Adjuntos guardados con exito!");
        })
        .catch((err) => {
          this.$alerError(err.response.data);
          this.preload = false;
        });
    },

    fecthNovedades() {
      axios
        .get(`/api/paciente/novedadesAll`)
        .then((res) => {
          this.novedades = res.data.Novedades;
        })
        .catch((err) => console.log(err.response.data));
    },

    onFilePicked() {
      this.files = this.$refs.myFiles.files;
    },

    listarMedicos() {
      axios
        .get("/api/user/medicoSumi")
        .then((res) => {
          this.miMedicoFamiliar = res.data;
        })
        .catch((e) => {
          console.log(e);
        });
    },

    listarEntidades() {
      axios
        .get("/api/entidades/getEntidades")
        .then((res) => {
          this.entidades = res.data;
        })
        .catch((e) => {
          console.log(e);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
