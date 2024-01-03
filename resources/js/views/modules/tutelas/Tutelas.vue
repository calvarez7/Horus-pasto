<template>
  <v-card>
    <v-card-title>
        <v-btn color="primary" round @click="AbrirMarcaPa()">Marcar paciente</v-btn>
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        prepend-icon="search"
        :label="`Buscar — ${isEditing ? 'Editable' : 'Bloqueado'}`"
        single-line
        hide-details
        persistent-hint
        :readonly="!isEditing"
        v-on:keyup.enter="getPacientes()"
      ></v-text-field>
      <v-slide-x-reverse-transition
            mode="out-in"
          >
            <v-icon
              :key="`icon-${isEditing}`"
              :color="isEditing ? 'success' : 'error'"
              @click="isEditing = !isEditing, search = '', getPacientes()"
              v-text="isEditing ? 'mdi-check-outline' : 'close'"
            ></v-icon>
          </v-slide-x-reverse-transition>
    </v-card-title>
    <v-dialog v-model="dialog" persistent max-width="800px">
      <v-card>
          <v-toolbar flat color="primary" dark>
            <v-toolbar-title>Marcar paciente</v-toolbar-title>
          </v-toolbar>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-form @submit.prevent="search_paciente()">
                  <v-layout row wrap>
                      <v-flex xs10>
                        <v-text-field v-model="cedula_paciente" label="Número de Documento">
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
                      <v-flex xs2>
                        <v-text-field v-model="paciente.Tipo_Doc" readonly label="Tipo Doc"></v-text-field>
                      </v-flex>
                      <v-flex xs3>
                        <v-text-field v-model="paciente.Num_Doc" readonly label="Documento"></v-text-field>
                      </v-flex>
                      <v-flex xs4>
                        <v-text-field v-model="paciente.Primer_Nom" readonly label="Nombre"></v-text-field>
                      </v-flex>
                      <v-flex xs3>
                        <v-text-field v-model="paciente.Primer_Ape" readonly label="Apellido"></v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-form>
                </v-flex>
              </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="CerrarM()">Cerrar</v-btn>
          <v-btn color="blue darken-1" flat @click="MarcarP()">Marcar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
     <v-dialog v-model="gestion" persistent max-width="1200px">
      <v-card>
        <v-toolbar flat color="primary" dark>
            <v-toolbar-title>Gestión Tutela</v-toolbar-title>
          </v-toolbar>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-form method="post" autocomplete="off">
                  <v-layout row wrap>
                      <v-flex xs6>
                        <v-text-field v-model="data.Ut" readonly label="ENTIDAD"></v-text-field>
                      </v-flex>
                      <v-flex xs2>
                        <v-text-field v-model="data.Tipo_Doc" readonly label="TIPO DOC"></v-text-field>
                      </v-flex>
                      <v-flex xs4>
                        <v-text-field v-model="data.Num_Doc" readonly label="DOCUMENTO"></v-text-field>
                      </v-flex>
                      <v-flex xs6>
                        <v-text-field v-model="data.Primer_Nom" readonly label="NOMBRE"></v-text-field>
                      </v-flex>
                      <v-flex xs6>
                        <v-text-field v-model="data.Primer_Ape" readonly label="APELLIDO"></v-text-field>
                      </v-flex>
                      <v-flex xs6>
                        <v-text-field
                          type="date"
                          v-model="fecharadicacion"
                          label="FECHA RADICACIÓN"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs6>
                        <v-text-field
                          v-model="radicado"
                          label="RADICADO"
                          required
                        ></v-text-field>
                      </v-flex>
                       <v-flex xs4>
                        <v-text-field
                          v-model="direccion"
                          label="DIRECCIÓN"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs4>
                        <v-text-field
                          v-model="telefono"
                          label="TELEFONO"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs4>
                        <v-autocomplete
                          :items="allMunicipios"
                          item-text="Nombre"
                          item-value="id"
                          label="MUNICIPIO"
                          v-model="allMunicipios.id"
                        ></v-autocomplete>
                      </v-flex>
                      <v-flex xs12>
                        <v-autocomplete
                          :items="allJuzgados"
                          item-text="Nombre"
                          item-value="id"
                          label="JUZGADO"
                          v-model="allJuzgados.id"
                        ></v-autocomplete>
                      </v-flex>
                      <v-flex xs4>
                        <v-select
                          v-model="newconti"
                          :items="['NUEVO', 'CONTINUIDAD']"
                          label="NUEVO/CONTINUIDAD"
                          required
                        ></v-select>
                      </v-flex>
                      <v-flex xs4>
                        <v-select
                          v-model="exclusion"
                          :items="['SI', 'NO']"
                          label="EXCLUSIÓN"
                          required
                        ></v-select>
                      </v-flex>
                      <v-flex xs4>
                        <v-select
                          v-model="integralidad"
                          :items="['SI', 'NO']"
                          label="INTEGRALIDAD"
                          required
                        ></v-select>
                      </v-flex>
                      <v-flex xs12>
                        <v-autocomplete
                          :items="allTiporequerimientos"
                          item-text="Nombre"
                          item-value="id"
                          label="TIPO REQUERIMIENTO"
                          v-model="requerimiento"
                        ></v-autocomplete>
                      </v-flex>
                      <v-flex xs12>
                        <v-autocomplete
                          v-model="responsable"
                          :items="responsablestutela"
                          item-text="fullname"
                          item-value="fullname"
                          chips
                          label="RESPONSABLE"
                          multiple
                        >
                          <template v-slot:selection="data">
                            <v-chip
                              :selected="data.selected"
                              close
                              class="chip--select-multi"
                              @input="remove_responsable(data.item)"
                            >
                              {{ data.item.fullname }}
                            </v-chip>
                          </template>
                          <template v-slot:item="data">
                            <template v-if="typeof data.item.fullname !== 'object'">
                              <v-list-tile-content v-text="data.item.fullname"></v-list-tile-content>
                            </template>
                            <template v-else>
                              <v-list-tile-content>
                                <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                <v-list-tile-sub-title v-html="data.item"></v-list-tile-sub-title>
                              </v-list-tile-content>
                            </template>
                          </template>
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12>
                        <v-autocomplete
                          v-model="tipo_servicio"
                          :items="itemsTipoServicio"
                          chips
                          label="TIPO SERVICIO"
                          multiple
                        >
                          <template v-slot:selection="data">
                            <v-chip
                              :selected="data.selected"
                              close
                              class="chip--select-multi"
                              @input="remove_servicio(data.item)"
                            >
                              {{ data.item }}
                            </v-chip>
                          </template>
                          <template v-slot:item="data">
                            <template v-if="typeof data.item !== 'object'">
                              <v-list-tile-content v-text="data.item"></v-list-tile-content>
                            </template>
                            <template v-else>
                              <v-list-tile-content>
                                <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                <v-list-tile-sub-title v-html="data.item"></v-list-tile-sub-title>
                              </v-list-tile-content>
                            </template>
                          </template>
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 v-show="ActivarCampos('OTROS')">
                        <v-combobox
                          v-model="otro_tiposervicio"
                          chips
                          label="OTROS SERVICIOS"
                          multiple
                        >
                          <template v-slot:selection="{ attrs, item }">
                            <v-chip
                              v-bind="attrs"
                              close
                              @input="remove(item)"
                            >
                              {{ item }}&nbsp;
                            </v-chip>
                          </template>
                        </v-combobox>
                      </v-flex>
                      <v-flex xs6 v-show="ActivarCampos('NOVEDADES Y REGISTRO')">
                        <v-autocomplete
                          :items="['AFILIACION', 'DESAFILIACION']"
                          label="NOVEDADES Y REGISTRO"
                          required
                          v-model="novedadesregistro"
                        ></v-autocomplete>
                      </v-flex>
                      <v-flex xs12 v-show="ActivarCampos('EXCLUSION')">
                        <v-autocomplete
                          :items="['TRATAMIENTO INFERTILIDAD', 'TRATAMIENTO ESTETICO', 'TRATAMIENTO EXPERIMENTAL', 'TRATAMIENTO MEDICO-QUIRURGICO REALIZADO EN EL EXTERIOR',
                         'MEDICAMENTO NO AUTORIZADO POR INVIMA', 'TECNOLOGIA SIN EVIDENCIA CIENTIFICA', 'ORTODONCIA', 'IMPLANTOLOGIA DENTAL',
                         'DISPOSITIVO PROTESICO CAVIDAD ORAL', 'BLANQUEAMIENTO DENTAL', 'INSTITUCIONES NO HABILITADAS EN SISTEMA DE SALUD',
                         'ARTICULO SUNTUARIO', 'COSMETICOS', 'VITAMINAS', 'LIQUIDOS PARA LENTES DE CONTACTO', 'TRATAMIENTO CAPILAR', 'SHAMPOO',
                         'JABON', 'ENJUAGUE BUCAL', 'CREMA DENTAL', 'CEPILLO', 'SEDA DENTAL', 'ELEMENTOS DE ASEO', 'LECHES', 'CREMA HIDRATANTE',
                         'ANTISOLAR', 'DROGAS PARA LA MEMORIA', 'EDULCORANTES O SUSTITUTOS DE LA SAL', 'ANOREXIGENOS', 'SERVICIOS FUERA DEL AMBITO DE SALUD',
                         'CALZADO ORTOPEDICO', 'PAÑALES', 'TOALLAS HIGIENICAS']"
                          label="EXCLUSION"
                                      v-model="exclusiones"
                          chips
                          multiple
                        >
                          <template v-slot:selection="data">
                            <v-chip
                              :selected="data.selected"
                              close
                              class="chip--select-multi"
                              @input="remove_exclusiones(data.item)"
                            >
                              {{ data.item }}
                            </v-chip>
                          </template>
                          <template v-slot:item="data">
                            <template v-if="typeof data.item !== 'object'">
                              <v-list-tile-content v-text="data.item"></v-list-tile-content>
                            </template>
                            <template v-else>
                              <v-list-tile-content>
                                <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                <v-list-tile-sub-title v-html="data.item"></v-list-tile-sub-title>
                              </v-list-tile-content>
                            </template>
                          </template>
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs6 v-show="ActivarCampos('GESTION DOCUMENTAL')">
                        <v-autocomplete
                          :items="['DERECHO DE PETICION NO CONTESTADO', 'SOLICITUD HISTORIA CLINICA']"
                          label="GESTION DOCUMENTAL"
                          required
                          v-model="gestiondocumental"
                        ></v-autocomplete>
                      </v-flex>
                      <v-flex xs12 v-show="ActivarCampos('SERVICIOS')">
                        <v-autocomplete
                          :items="allCups"
                          item-text="fullname"
                          item-value="id"
                          label="SERVICIOS"
                          v-model="Cups"
                          chips
                          multiple
                        >
                          <template v-slot:selection="data">
                            <v-chip
                              :selected="data.selected"
                              close
                              class="chip--select-multi"
                              @input="remove_cups(data.item)"
                            >
                              {{ data.item.fullname }}
                            </v-chip>
                          </template>
                          <template v-slot:item="data">
                            <template v-if="typeof data.item.fullname !== 'object'">
                              <v-list-tile-content v-text="data.item.fullname"></v-list-tile-content>
                            </template>
                            <template v-else>
                              <v-list-tile-content>
                                <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                <v-list-tile-sub-title v-html="data.item"></v-list-tile-sub-title>
                              </v-list-tile-content>
                            </template>
                          </template>
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 v-show="ActivarCampos('MEDICAMENTOS')">
                        <v-autocomplete
                          :items="allMedicamentos"
                          item-text="Producto"
                          item-value="id"
                          label="MEDICAMENTOS"
                          v-model="medicamentos"
                          chips
                          multiple
                        >
                          <template v-slot:selection="data">
                            <v-chip
                              :selected="data.selected"
                              close
                              class="chip--select-multi"
                              @input="remove_medicamentos(data.item)"
                            >
                              {{ data.item.NombreSumi }}
                            </v-chip>
                          </template>
                          <template v-slot:item="data">
                            <template v-if="typeof data.item.NombreSumi !== 'object'">
                              <v-list-tile-content v-text="data.item.NombreSumi"></v-list-tile-content>
                            </template>
                            <template v-else>
                              <v-list-tile-content>
                                <v-list-tile-title v-html="data.item"></v-list-tile-title>
                                <v-list-tile-sub-title v-html="data.item"></v-list-tile-sub-title>
                              </v-list-tile-content>
                            </template>
                          </template>
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs6 v-show="ActivarCampos('MEDICINA LABORAL')">
                        <v-autocomplete
                          v-model="medicinalaboral"
                          :items="['CALIFICACION PERDIDA CAPACIDAD', 'INCAPACIDAD MAXIMA', 'OTRO']"
                          label="MEDICINA LABORAL"
                          required
                        ></v-autocomplete>
                      </v-flex>
                      <v-flex xs6 v-show="ActivarCampos('MEDICINA LABORAL') && OtroMedicinalaboral('OTRO')">
                        <v-text-field
                          v-model="medicinalaboralotro"
                          label="OTRO MEDICINA LABORAL"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs6 v-show="ActivarCampos('REEMBOLSO')">
                        <v-autocomplete
                          v-model="reembolso"
                          :items="['TRANSPORTE', 'SERVICIOS', 'MEDICAMENTOS', 'OTRO']"
                          label="REEMBOLSO"
                          required
                        ></v-autocomplete>
                      </v-flex>
                      <v-flex xs6 v-show="ActivarCampos('REEMBOLSO') && OtroReembolso('OTRO')">
                        <v-text-field
                          v-model="reembolsotro"
                          label="OTRO REEMBOLSO"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs6 v-show="ActivarCampos('TRANSPORTE')">
                        <v-autocomplete
                          v-model="transporte"
                          :items="['TRANSPORTE INTER URBANO', 'TRANSPORTE AEREO', 'TRANSPORTE FLUVIAL', 'TRANSPORTE TERRESTRE', 'OTRO']"
                          label="TRANSPORTE"
                          required
                        ></v-autocomplete>
                      </v-flex>
                      <v-flex xs6 v-show="ActivarCampos('TRANSPORTE') && OtroTransporte('OTRO')">
                        <v-text-field
                          v-model="transporteotro"
                          label="OTRO TRANSPORTE"
                          required
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12>
                        <v-textarea
                            color="teal"
                            v-model="observacion"
                          >
                          <template v-slot:label>
                              <div>
                                OBSERVACIÓN
                              </div>
                          </template>
                        </v-textarea>
                      </v-flex>
                      <v-flex xs12>
                        <input
                          id="adjuntos"
                          multiple
                          ref="adjuntos"
                          type="file"/>
                        </v-flex>
                    </v-layout>
                  </v-form>
                </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="Closegestion()">Cerrar</v-btn>
          <v-btn color="blue darken-1" :disabled="save" flat @click="SaveGestion()">Asignar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-data-table
      :headers="headers"
      :items="allPacientes"
      hide-actions
      class="elevation-1"
      :loading="loading"
    >
      <template v-slot:items="props">
        <td>{{ props.item.id }}</td>
        <td>{{ props.item.Primer_Nom }} {{ props.item.SegundoNom }} {{props.item.Primer_Ape}} {{ props.item.Segundo_Ape }}</td>
        <td>{{ props.item.Num_Doc }}</td>
        <td>{{ props.item.Tipo_Afiliado }}</td>
        <td>
          <v-btn @click="OpenGestionT(props.item)" :disabled="loading" color="blue" class="white--text">
            Acciones
            <v-icon right dark>send</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
    <div class="text-xs-center pt-2">
      <v-pagination v-model="pagination.page" :length="pages" @input="getPacientes" :total-visible="7"></v-pagination>
    </div>
  </v-card>
</template>
<script>
  export default {
    name: 'Tutelas',
    data () {
      return {
        loading: false,
        save: false,
        pagination: {},
        novedadesregistro: '',
        search: '',
        allPacientes:[],
        allJuzgados: [],
        allMunicipios: [],
        allTiporequerimientos: [],
        allCups: [],
        headers: [
                    {
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'Primer_Nom'
                    },
                    {
                        text: 'Documento',
                        align: 'left',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'Tipo afiliado',
                        align: 'left',
                        value: 'Tipo_Afiliado'
                    },
                    {
                        text: 'Acciones',
                        align: 'left',
                        value: ''
                    }
        ],
        data:{
          Primer_Nom: '',
          Primer_Ape: '',
          Tipo_Doc: '',
          Num_Doc: ''
        },
        dialog: false,
        gestion: false,
        cedula_paciente: "",
        paciente: {
          Primer_Nom: "",
          Primer_Ape: "",
          Tipo_Doc: "",
          Num_Doc: ""
        },
        requerimiento: "",
        responsable: [],
        observacion: "",
        tipo_servicio: [],
        adjuntos: [],
        direccion: "",
        integralidad: "",
        exclusion: "",
        juzgado: "",
        radicado: "",
        municipio: "",
        telefono: "",
        fecharadicacion: "",
        newconti: "",
        exclusiones:[],
        gestiondocumental: "",
        allMedicamentos: [],
        medicinalaboral: "",
        medicinalaboralotro: "",
        reembolso: "",
        reembolsotro: "",
        transporte: "",
        transporteotro: "",
        otro_tiposervicio: [],
        Cups: [],
        medicamentos: [],
        responsablestutela: [],
        isEditing: false
      }
    },
    computed:{
      itemsTipoServicio(){
        let items = ['NOVEDADES Y REGISTRO','EXCLUSION', 'GESTION DOCUMENTAL', 'HOSPITALIZACION', 'SERVICIOS', 'MEDICAMENTOS',
                     'MEDICINA LABORAL', 'REEMBOLSO', 'REINTEGRO LABORAL', 'TRANSPORTE', 'OTROS'];

        if(this.tipo_servicio == []) return items

        if(this.ActivarCampos('OTROS')){
          this.tipo_servicio = ['OTROS']
        }
          return items
      },
      pages () {
        if (this.pagination.rowsPerPage == null ||
          this.pagination.totalItems == null
        ) return 0

        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    mounted () {
      this.getPacientes();
      this.getJuzgados();
      this.getMunicipios();
      this.getTiporequerimiento();
      this.getCups();
      this.getMedicamentos();
      this.getResponsables();
    },
    methods: {
      getResponsables() {
               axios.get('/api/tutelas/showresponsables').then(res => {
                    this.responsablestutela = res.data.map(responsable => {
                        return {
                            id: responsable.id,
                            fullname: responsable.NombreRol
                        };
                    });
                });
      },
      getPacientes(page = 1){
          if(this.search != ""){
            this.isEditing = false;
          }
          this.loading = true;
          let formData = new FormData();
            formData.append("search", this.search);
            axios.post('/api/tutelas/all?page='+page , formData, {}
            ).then(res => {
              this.allPacientes = res.data.data;
              this.loading = false
              this.pagination = {
                totalItems: res.data.total,
                rowsPerPage: res.data.per_page,
                page: res.data.current_page
              }
            });
      },
      getJuzgados(){
        axios.get('/api/juzgado/all')
        .then(res => {
          this.allJuzgados = res.data;
        });
      },
      getMunicipios(){
        axios.get('/api/municipio/all')
        .then(res => {
          this.allMunicipios = res.data;
        });
      },
      getMedicamentos(){
        axios.get('/api/detallearticulo/all')
        .then(res => {
          this.allMedicamentos = res.data;
        });
      },
      getTiporequerimiento(){
        axios.get('/api/tiporequerimiento/all')
        .then(res => {
          this.allTiporequerimientos = res.data;
        });
      },
      getCups(){
               axios.get('/api/cups/all').then(res => {
                    this.allCups = res.data.map(cups => {
                        return {
                            id: cups.id,
                            fullname: `${cups.Codigo} - ${cups.Nombre}`
                        };
                    });
                });
      },
      ActivarCampos(msg){
        let found = this.tipo_servicio.find(tipo => tipo == msg);
        if (found) return true;
        return false;
      },
      OtroMedicinalaboral(msg){
        return this.medicinalaboral == msg || false;
      },
      OtroReembolso(msg){
        return this.reembolso == msg || false;
      },
      OtroTransporte(msg){
        return this.transporte == msg || false;
      },
      AbrirMarcaPa(){
        this.dialog = true;
      },
      search_paciente() {
      if (!this.cedula_paciente) return;
            axios
              .get(`/api/tutelas/activo/${this.cedula_paciente}`)
                  .then(res => {
                        if (res.data.paciente) {
                            this.paciente = res.data.paciente;
                        }
                        if (res.data.message) this.showMessage(res.data.message);
                  });
      },
      remove (item) {
        this.otro_tiposervicio.splice(this.otro_tiposervicio.indexOf(item), 1)
        this.otro_tiposervicio = [...this.otro_tiposervicio]
      },
      remove_responsable (item) {
        this.responsable.splice(this.responsable.indexOf(item), 1)
        this.responsable = [...this.responsable]
      },
      remove_servicio (item) {
        const index = this.tipo_servicio.indexOf(item)
        if (index >= 0) this.tipo_servicio.splice(index, 1)
      },
      remove_exclusiones (item) {
        const index = this.exclusiones.indexOf(item)
        if (index >= 0) this.exclusiones.splice(index, 1)
      },
      remove_cups (item) {
        const index = this.allCups.indexOf(item)
        if (index >= 0) this.allCups.splice(index, 1)
      },
      remove_medicamentos (item) {
        const index = this.allMedicamentos.indexOf(item)
        if (index >= 0) this.allMedicamentos.splice(index, 1)
      },
      CerrarM(){
      (this.cedula_paciente = ""),
                (this.paciente = {
                    Primer_Nom: "",
                    Primer_Ape: "",
                    Tipo_Doc: "",
                    Num_Doc: ""
                });
      this.cedula_paciente = "";
      this.dialog = false;
    },
    clearFields() {
      (this.cedula_paciente = ""),
                (this.paciente = {
                    Primer_Nom: "",
                    Primer_Ape: "",
                    Tipo_Doc: "",
                    Num_Doc: ""
                });
      this.cedula_paciente = "";
    },
    showMessage(message) {
      swal({
        title: `${message}`,
        icon: "warning"
      });
    },
    MarcarP(){
      if (this.cedula_paciente == "" || this.paciente.Primer_Nom == ""){
        swal({
          title: "¡Ingrese un numero de documento!",
          text: ` `,
          timer: 2000,
          icon: "error",
          buttons: false
        });
       return;
      }
      const msg = "Esta seguro de marcar el paciente " + this.paciente.Primer_Nom +" "+ this.paciente.Primer_Ape
       + " con numero de documento "+ this.paciente.Num_Doc;
                swal({
                    title: msg,
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(async willDelete => {
                    if (willDelete) {
                        axios
                          .put(`/api/tutelas/marcacion/${this.paciente.id}`, this.paciente)
                              .then(res => {
                                this.getPacientes();
                                  swal({
                                    title: "¡Paciente marcado con exito!",
                                    text: ` `,
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                  });
                                this.CerrarM();
                                });
                      }
                });
    },
    OpenGestionT(paciente){
      this.data = paciente;
      this.gestion = true;
    },
    Closegestion(){
      this.gestion = false
      this.ClearGestion();
    },
    ClearGestion(){
      this.data = {}
      this.$refs.adjuntos.value = ""
      this.fecharadicacion = ""
      this.radicado = ""
      this.direccion = ""
      this.telefono = ""
      this.allMunicipios.id = ""
      this.allJuzgados.id = ""
      this.newconti = ""
      this.exclusion = ""
      this.integralidad = ""
      this.requerimiento = ""
      this.responsable = []
      this.tipo_servicio = []
      this.otro_tiposervicio = []
      this.exclusiones = []
      this.Cups = []
      this.medicamentos = []
      this.medicinalaboral = ""
      this.medicinalaboralotro = ""
      this.reembolso = ""
      this.reembolsotro = ""
      this.transporte = ""
      this.transporteotro = ""
      this.observacion = ""
      this.gestiondocumental = ""
      this.novedadesregistro = ""
    },
    SaveGestion(){
      this.save = true;
      this.data.adjuntos = this.$refs.adjuntos.files;
      let formData = new FormData();
      formData.append('pacienteid',this.data.id)
      formData.append('fecharadicacion',this.fecharadicacion)
      formData.append('radicado',this.radicado)
      formData.append('direccion',this.direccion)
      formData.append('telefono',this.telefono)
      formData.append('municipio',this.allMunicipios.id)
      formData.append('juzgado',this.allJuzgados.id)
      formData.append('Newconti',this.newconti)
      formData.append('exclusion',this.exclusion)
      formData.append('integralidad',this.integralidad)
      formData.append('requerimiento',this.requerimiento)
      for (let i = 0; i < this.responsable.length; i++) {
        formData.append(`responsable[]`, this.responsable[i]);
      }
      for (let i = 0; i < this.tipo_servicio.length; i++) {
        formData.append(`tiposervicio[]`, this.tipo_servicio[i]);
      }
      for (let i = 0; i < this.otro_tiposervicio.length; i++) {
        formData.append(`otrotiposervicio[]`, this.otro_tiposervicio[i]);
      }
      formData.append('novedadregistro',this.novedadesregistro)
      for (let i = 0; i < this.exclusiones.length; i++) {
        formData.append(`exclusiones[]`, this.exclusiones[i]);
      }
      formData.append('gestiondocumental',this.gestiondocumental)
      for (let i = 0; i < this.Cups.length; i++) {
        formData.append(`servicios[]`, this.Cups[i]);
      }
      for (let i = 0; i < this.medicamentos.length; i++) {
        formData.append(`medicamentos[]`, this.medicamentos[i]);
      }
      formData.append('medicinalaboral',this.medicinalaboral)
      formData.append('medicinalaboralotro',this.medicinalaboralotro)
      formData.append('reembolso',this.reembolso)
      formData.append('reembolsotro',this.reembolsotro)
      formData.append('transporte',this.transporte)
      formData.append('transporteotro',this.transporteotro)
      formData.append('observacion',this.observacion)
      for (let i = 0; i < this.data.adjuntos.length; i++) {
        formData.append(`adjuntos[]`, this.data.adjuntos[i]);
      }
      axios.post(`/api/tutelas/store`, formData, {
          })
            .then(res =>{
              this.save = false;
              swal({
                title: "¡Tutela asignada con exito!",
                text: ` `,
                timer: 2000,
                icon: "success",
                buttons: false
              });
              this.Closegestion();
            })
            .catch(err =>{
                this.save = false;
                swal({
                title: "¡Debe ingresar los campos obligatorios!",
                text: ` `,
                timer: 2000,
                icon: "error",
                buttons: false
              })
            });
    }
  }
  }
</script>
