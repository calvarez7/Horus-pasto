<template>
    <div>
        <v-dialog v-model="dialog" max-width="1400px">
            <v-card>
                <form @submit.prevent="guardarRevision">
                    <v-card-title class="success" style="color:white">
                        <span class="title layout justify-center ">VERIFICACIÓN
                            DE LA INFORMACIÓN</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-card-title style="color:black">
                                <span class="title layout justify-center ">INFORMACION GENERAL</span>
                            </v-card-title>
                            <v-layout wrap>
                                <v-flex xs4>
                                    <v-text-field v-model="tipo_cliente" readonly label="¿Tipo cliente o proveedor?">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="nombre_diligencia" readonly
                                                  label="Nombre De Quien Diligencia">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="cedula_diligencia" readonly label="Numero de Identificacion">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="cargo_diligencia" readonly label="Cargo">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="tipo_doc" readonly label="Tipo De Documento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="cedula" readonly label="Numero De Documento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="name" readonly label="Nombre/Razón Social:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="tipo_cliente" readonly label="Tipo Cliente:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="sector" readonly label="Sector:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="cual_descripcion" readonly label="¿Cual?:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="tipo_bienservicio" readonly
                                                  label="Tipo bien/servicio a adquirir o proveer:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="direccion" readonly label="Dirección Principal:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="Municipio" readonly label="Municipio:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="Departamento" readonly label="Departamento:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="email_empresa" readonly label="Correo Electronico:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="telefono_empresa" readonly label="Telefono:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="numero_contacto" readonly label="Numero De Contacto:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="fax" readonly label="Fax:">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-card-title style="color:black" v-if="tipo_cliente !='Cliente Usuario Particular'">
                                <span class="title layout justify-center ">REPRESENTANTE LEGAL</span>
                            </v-card-title>
                            <v-card-title style="color:black" v-if="tipo_cliente =='Cliente Usuario Particular'">
                                <span class="title layout justify-center ">INFORMACION COMPLEMENTARIA</span>
                            </v-card-title>
                            <v-layout wrap>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="Representante" readonly label="Nombre Completo:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="tipo_documento" readonly label="Tipo Documento:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="num_doc" readonly label="Numero:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="fecha_exp" readonly label="Fecha De Expedición">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="Municipio_repre" readonly label="Lugar De Expedición">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="fecha_nac" readonly label="Fecha De Nacimiento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="Municipio_nacimiento" readonly label="Lugar De Nacimiento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="otra_nacionalidad" readonly
                                                  label="¿Tiene Otra Nacionalidad?">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="emali" readonly label="Correo Electronico">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="ciudad_reci" readonly label="Ciudad De Residencia">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="deparamento_reci" readonly
                                                  label="Departamento De Residencia">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="direccion_reci" readonly label="Dirección Principal:">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="pais_reci" readonly label="Pais De Residencia">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4 v-if="tipo_cliente !='Cliente Usuario Particular'">
                                    <v-text-field v-model="telefono" readonly label="Numero De Contacto">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="cargo_publico" readonly
                                                  label="¿Por Su Cargo O Actividad Maneja Recursos Públicos?" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="poder_publico" readonly
                                                  label="¿Por Su Cargo O Actividad Ejerce Algún Poder Público?" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="reconocimento_publico" readonly
                                                  label="¿Por Su Actividad U Oficio, Goza Usted De Reconocimiento Público General?"
                                                  required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="obli_tibutarias" readonly
                                                  label="¿Es Usted Sujeto De Obligaciones Tributarias En Otro País O Grupo De Países?"
                                                  required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="descripcion_obliga" readonly label="Indique El Pais"
                                                  required>
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-card-title style="color:black" v-if="tipo_cliente !='Cliente Usuario Particular'">
                                <span class="title layout justify-center ">IDENTIFICACIÓN DE LOS SOCIOS,
                                    ACCIONISTAS O ASOCIADOS</span>
                            </v-card-title>
                            <v-data-table :headers="headers1" :items="desserts" class="mx-2"
                                          v-if="tipo_cliente !='Cliente Usuario Particular'">
                                <template v-slot:items="props">
                                    <td class="text-xs-left">{{ props.item.razon_social }}</td>
                                    <td class="text-xs-left">{{ props.item.tipodoc_socio }}</td>
                                    <td class="text-xs-left">{{ props.item.documento_socio }}</td>
                                    <td class="text-xs-left">{{ props.item.participacion }}</td>
                                    <td class="text-xs-left">{{ props.item.descripcion_expuevincula }}</td>
                                </template>
                            </v-data-table>
                            <v-card-title style="color:black" v-if="tipo_cliente !='Cliente Usuario Particular'">
                                <span class="title layout justify-center ">CONOCIMIENTO MEJORADO DE PERSONAS EXPUESTAS
                                    PÚBLICAMENTE</span>
                            </v-card-title>
                            <v-data-table :headers="headers2" :items="desserts" class="mx-2"
                                          v-if="tipo_cliente !='Cliente Usuario Particular'">
                                <template v-slot:items="props">
                                    <td class="text-xs-left">{{ props.item.nombre_social}}</td>
                                    <td class="text-xs-left">{{ props.item.nacionalidad_expuesta}}</td>
                                    <td class="text-xs-left">{{ props.item.relacion_expuesta}}</td>
                                    <td class="text-xs-left">{{ props.item.entidad_expuesta}}</td>
                                    <td class="text-xs-left">{{ props.item.cargo_social}}</td>
                                </template>
                            </v-data-table>
                            <v-card-title style="color:black">
                                <span class="title layout justify-center ">INFORMACIÓN FINACIERA</span>
                            </v-card-title>
                            <v-layout wrap>
                                <v-flex xs4>
                                    <v-text-field v-model="total_activos" readonly
                                                  label="Total Activos (último balance)">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="total_pasivos" readonly
                                                  label="Total Pasivos (último balance)">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="ingreso_mensual" readonly
                                                  label="Ingresos Mensuales (promedio mensual)">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="otros_ingresos" readonly label="Otros ingresos">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="concepto_ingreso" readonly label="Concepto de ingresos">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="egresos_mensuales" readonly
                                                  label="Egresos Mensuales (promedio mensual)">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="otros_egresos" readonly label="Otros egresos">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="concepto_egreso" readonly label="Concepto de egresos">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-card-title style="color:black">
                                <span class="title layout justify-center ">ACTIVIDADES EN OPERACIONAES
                                    INTERNACIONALES</span>
                            </v-card-title>
                            <v-layout wrap>
                                <v-flex xs4>
                                    <v-text-field v-model="transa_monedaextr" readonly
                                                  label="¿Realiza transacciones en moneda extranjera?">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="cual_moneda" readonly label="¿Cuál?">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="otra_moneda" readonly label="Indique otras">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="produc_extranjeros" readonly
                                                  label="¿Posee productos financieros en el exterior?">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="cual_prodc" readonly
                                                  label="¿Posee cuentas en moneda extranjera?">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-text-field v-model="pais_operaciones" readonly
                                                  label="País de operaciones extranjeras">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-card-title style="color:black">
                                <span class="title layout justify-center ">DECLARACIÓN DE ORIGEN DE
                                    FONDOS</span>
                            </v-card-title>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-textarea outline name="input-7-4" v-model="declaracion" readonly>
                                    </v-textarea>
                                </v-flex>
                            </v-layout>
                            <v-flex xs12>
                                <v-btn v-for="(data, index) in adjuntos" :key="index" color="info">
                                    <v-icon color="white">mdi-cloud-upload
                                    </v-icon>
                                    <a :href="`${data.ruta}`" target="_blank" style="color:white">
                                        Adjunto
                                        {{data.nombre}}
                                    </a>
                                </v-btn>
                            </v-flex>
                        </v-container>
                        <v-card-title class="success" style="color:white">
                            <span class="title layout justify-center ">REVISION</span>
                        </v-card-title>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-textarea v-model="revision.revision" outline name="input-7-4"
                                            label="Resultado de la verificación" required>
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                        <small>*Este ítem es exclusivo de Sumimedical SAS</small>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-flex>
                                <input id="adjuntoRevision" multiple ref="adjuntoRevision" type="file" accept=".pdf" />
                            </v-flex>
                            <v-btn color="success" @click="guardarRevision()">Guardar Revision</v-btn>
                        </v-card-actions>
                        <v-flex xs12 v-for="(historicoRevision, index) in historicoRevision" :key="historicoRevision.id">
                            <v-card-title style="color:black" v-if="historicoRevision != null">
                                <span class="title layout justify-center ">REVISION # {{index+1}}</span>
                            </v-card-title>
                            <v-layout wrap v-if="historicoRevision != null">
                                <v-flex xs12>
                                    <v-textarea outline name="input-7-4" v-model="historicoRevision.revision" readonly>
                                    </v-textarea>
                                    <span> <strong>Hora y Fecha: </strong>{{historicoRevision.FechaRevision}}</span>
                                    <span><strong>Usuario que revisa:
                                    </strong>{{historicoRevision.name +' '+ historicoRevision.apellido}}</span>
                                    <v-spacer></v-spacer>
                                    <v-flex xs12 v-for="(data, index) in historicoRevision.adjuntos" :key="historicoRevision.revision_sarlafts_id">
                                        <v-btn color="info"  v-if="data.ruta != null">
                                            <v-icon color="white">mdi-cloud-upload
                                            </v-icon>
                                            <a :href="`${data.ruta}`" target="_blank" style="color:white">
                                                Adjunto
                                                {{data.nombre}}
                                            </a>
                                        </v-btn>
                                    </v-flex>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="info" @click="dialog = false">Cerrar</v-btn>
                        <v-btn color="indigo lighten-2" dark @click="envioCorreo(adjuntos)">Enviar
                            autorizacion por correo</v-btn>
                        <v-btn color="purple lighten-2" dark @click="imprimir(idSarlaft)">Imprimir Nuevamente</v-btn>
                    </v-card-actions>
                </form>
            </v-card>
        </v-dialog>
        <v-tabs centered color="primary" dark icons-and-text>
            <v-tabs-slider color="yellow"></v-tabs-slider>

            <v-tab href="#tab-1" @click="revisionBuscar(13)">
                Revisados
                <v-icon>account_box</v-icon>
            </v-tab>
            <v-tab href="#tab-2" @click="revisionBuscar(1)">
                Pendientes por Notificar
                <v-icon>account_box</v-icon>
            </v-tab>

            <v-tab-item v-for="i in 2" :key="i" :value="'tab-' + i">
                <v-card flat>
                    <template>
                        <v-card-title>
                            <v-text-field v-model="search" append-icon="search" label="Buscar.." single-line hide-details>
                            </v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="desserts" :search="search">
                            <template v-slot:items="props" activator="{ on }">
                                <td>{{ props.item.idSarlaft }}</td>
                                <td class="text-xs-left">{{ props.item.name +' '+ props.item.apellido}}</td>
                                <td class="text-xs-left">{{ props.item.cedula }}</td>
                                <td class="text-xs-left">{{ props.item.tipo_cliente }}</td>
                                <td class="text-xs-left">{{ props.item.email_empresa }}</td>
                                <td class="text-xs-left">{{ props.item.Fecharealizacion }}</td>
                                <td v-if="i==1">
                                    <v-btn color="primary"
                                           @click="informacionMostrar(props.item), adjuntosSarlafts()">
                                        REVISAR</v-btn>
                                </td>
                                <td v-if="i==2">
                                    <v-btn color="info" @click="envioCorreo(props.item.idSarlaft)">
                                        Notificar</v-btn>
                                </td>
                            </template>
                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </v-data-table>
                    </template>
                </v-card>
            </v-tab-item>
        </v-tabs>
    </div>
</template>

<script>
export default {
    data() {
        return {
            idSarlaft: null,
            idRevision: null,
            historicoRevision: [],
            historicoNotificacion: [],
            search:'',
            headers: [{
                text: 'ID Registro',
                sortable: false,
            },

                {
                    text: 'Nombre del Proveedor',
                    align: 'left',
                    value:'idSarlaft'
                },
                {
                    text: 'CEDULA / NIT',
                    value:'cedula'

                },
                {
                    text: 'CLIENTE / PROVEEDOR',
                    value: 'name'

                },

                {
                    text: 'Correo Electronico',

                },
                {
                    text: 'Fecha de llenado',

                },
            ],
            headers1: [{
                text: 'Razón social o Nombre completo',
                align: 'left',
                sortable: false,
            },
                {
                    text: 'Tipo de identificación',
                },
                {
                    text: 'Número',
                },
                {
                    text: '% Participación',
                },
                {
                    text: '¿ Es persona públicamente expuesta, o vinculado con una de ellas?',
                },
            ],
            headers2: [{
                text: 'Razón social o Nombre completo',
                align: 'left',
                sortable: false,
            },
                {
                    text: 'Nacionalidad',
                },
                {
                    text: 'Vinculo/Relación',
                },
                {
                    text: 'Entidad',
                },
                {
                    text: 'Cargo',
                },
            ],
            revision: {
                revision: null,
            },
            desserts: [],
            adjuntos: [],
            adjuntoRevision: [],
            dialog: false,
            //Informacion General
            name: null,
            cedula: null,
            tipo_doc: null,
            tipo_cliente: null,
            sector: null,
            cual_descripcion: null,
            tipo_bienservicio: null,
            direccion: null,
            Municipio: null,
            Departamento: null,
            email_empresa: null,
            Fecharealizacion: null,
            telefono_empresa: null,
            numero_contacto: null,
            fax: null,
            cargo_diligencia: null,
            cedula_diligencia: null,
            nombre_diligencia: null,
            tipo_solicitud: null,

            //representante legal
            Representante: null,
            tipo_documento: null,
            num_doc: null,
            fecha_exp: null,
            Municipio_repre: null,
            fecha_nac: null,
            Municipio_nacimiento: null,
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

            //socios
            razon_social: null,
            tipodoc_socio: null,
            documento_socio: null,
            participacion: null,
            descripcion_expuevincula: null,

            //personas expuestas
            nombre_social: null,
            nacionalidad_expuesta: null,
            relacion_expuesta: null,
            entidad_expuesta: null,
            cargo_social: null,

            //informacion financiera
            total_activos: null,
            total_pasivos: null,
            ingreso_mensual: null,
            otros_ingresos: null,
            concepto_ingreso: null,
            egresos_mensuales: null,
            otros_egresos: null,
            concepto_egreso: null,

            //operaciones internacionales
            transa_monedaextr: null,
            cual_moneda: null,
            otra_moneda: null,
            produc_extranjeros: null,
            cual_prodc: null,
            pais_operaciones: null,

            //declaracion
            declaracion: null,

            enableEmail: false


        }
    },
    methods: {
        revisionBuscar(tipo) {
            axios.get('/api/sarlaft/revisionBuscar/' + tipo).then(res => {
                this.desserts = res.data;
            });
        },
        consultarRevision() {
            axios.get('/api/sarlaft/consultaRevision/' + this.idSarlaft).then(res => {
                this.historicoRevision = res.data;
            })
    },
        guardarRevision() {
            this.preload = true;
            this.revision.sarlafs_id = this.idSarlaft
            axios.post('/api/sarlaft/guardarRevision/', this.revision)
                .then(res => {
                    this.guardarAdjuntosrevision(res.data.idRevision)
                    this.$alerSuccess("Revision guardada con exito!");
                    this.preload = false;
                }).catch(error => {
                this.preload = false;
                this.$alerError("Error al guardar!");
            });

        },
        adjuntosSarlafts() {
            axios.get('/api/sarlaft/adjuntosSarlafts/' + this.idSarlaft).then(res => {
                this.adjuntos = res.data;
            })

        },
        adjuntosRevision(idRevision){
            console.log(idRevision)
            axios.get('/api/sarlaft/adjuntosRevision/' + idRevision).then(res => {
                this.adjuntoRevision = res.data;})
        },
        guardarAdjuntosrevision(idRevision) {
            this.preload = true;
            const data = this.$refs.adjuntoRevision.files
            let formData = new FormData();
            for (let i = 0; i < data.length; i++){
                formData.append(`adjuntoRevision[]`, data[i]);}


            axios.post('/api/sarlaft/guardarAdjuntosrevision/' + idRevision, formData).then(res => {
                this.preload = false;
                this.$alerSuccess("Adjuntos guardados con exito!");

            }).catch(error => {
                this.preload = false;
                this.$alerError("Error al guardar!");
            });
        },
        updateNotifiacion(idSarlaft) {
            axios.put('/api/sarlaft/updateNotifiacion/' + idSarlaft)
                .then(res => {
                    this.$alerSuccess("Se Cerro la revision con exito!");
                    this.preload = false;
                    this.dialog = false;
                }).catch(error => {
                this.preload = false;
                this.$alerError("Error al guardar!");
                this.dialog = false;
            });

        },
        envioCorreo(idSarlaft) {
            axios.post('/api/sarlaft/envioCorreo/' + idSarlaft ).then(res => {
                this.$alerSuccess("Se Envio el correo con exito!");
                this.preload = false;
                this.dialog = false;
            }).catch(error => {
                this.preload = false;
                this.$alerError("Error al enviar!");
                this.dialog = false;
            })
            this.updateNotifiacion(idSarlaft);
            this.revisionBuscar(1);
        },
        informacionMostrar(item) {
            this.dialog = true;
            this.idSarlaft = item.idSarlaft

            //informacion General
            this.cedula = item.cedula
            this.tipo_doc = item.nit
            this.name = item.name + ' ' + item.apellido
            this.tipo_cliente = item.clase
            this.sector = item.sector
            this.cual_descripcion = item.cual_descripcion
            this.tipo_bienservicio = item.tipo_bienservicio
            this.direccion = item.direccion
            this.Municipio = item.municipio
            this.Departamento = item.departamento
            this.email_empresa = item.email_empresa
            this.Fecharealizacion=item.Fecharealizacion
            this.telefono_empresa = item.telefono_empresa
            this.numero_contacto = item.numero_contacto
            this.fax = item.fax
            this.cargo_diligencia = item.cargo_diligencia
            this.cedula_diligencia = item.cedula_diligencia
            this.nombre_diligencia = item.nombre_diligencia
            this.tipo_solicitud = item.tipo_solicitud
            this.tipo_cliente = item.tipo_cliente

            //Representante legal
            this.Representante = item.nombre
            this.tipo_documento = item.tipo_documento
            this.num_doc = item.num_doc
            this.fecha_exp = item.fecha_exp
            this.Municipio_repre = item.Municipio_repre
            this.fecha_nac = item.fecha_nac
            this.Municipio_nacimiento = item.Municipio_nacimiento
            this.otra_nacionalidad = item.otra_nacionalidad
            this.emali = item.emali
            this.ciudad_reci = item.Municipio_recidencia
            this.deparamento_reci = item.deparamento_reci
            this.direccion_reci = item.direccion_reci
            this.pais_reci = item.pais_reci
            this.telefono = item.telefono
            if (item.cargo_publico == 0) {
                this.cargo_publico = 'NO'
            } else {
                this.cargo_publico = 'SI'
            }
            if (item.poder_publico == 0) {
                this.poder_publico = 'NO'
            } else {
                this.poder_publico = 'SI'
            }
            if (item.reconocimento_publico == 0) {
                this.reconocimento_publico = 'NO'
            } else {
                this.reconocimento_publico = 'SI'
            }
            if (item.obli_tibutarias == 0) {
                this.obli_tibutarias = 'NO'
            } else {
                this.obli_tibutarias = 'SI'
            }
            this.descripcion_obliga = item.descripcion_obliga

            //socios
            this.tipodoc_socio = item.tipodoc_socio
            this.razon_social = item.razon_social
            this.documento_socio = item.documento_socio
            this.participacion = item.participacion
            this.descripcion_expuevincula = item.descripcion_expuevincula

            //personas expuestas
            this.nombre_social = item.nombre_social
            this.nacionalidad_expuesta = item.nacionalidad_expuesta
            this.relacion_expuesta = item.relacion_expuesta
            this.entidad_expuesta = item.entidad_expuesta
            this.cargo_social = item.cargo_social

            //informacion financiera
            this.total_activos = item.total_activos
            this.total_pasivos = item.total_pasivos
            this.ingreso_mensual = item.ingreso_mensual
            this.otros_ingresos = item.otros_ingresos
            this.concepto_ingreso = item.concepto_ingreso
            this.egresos_mensuales = item.egresos_mensuales
            this.otros_egresos = item.otros_egresos
            this.concepto_egreso = item.concepto_egreso

            //operaciones internacionales
            this.transa_monedaextr = item.transa_monedaextr
            this.cual_moneda = item.cual_moneda
            this.otra_moneda = item.otra_moneda
            this.produc_extranjeros = item.produc_extranjeros
            this.cual_prodc = item.cual_prodc
            this.pais_operaciones = item.pais_operaciones

            //declaracion
            this.declaracion = item.declaracion
            this.consultarRevision(item.idSarlaft)


        },

        show() {
            this.dialog = true
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
    },
    mounted() {
        this.revisionBuscar(13);
    }
}

</script>
