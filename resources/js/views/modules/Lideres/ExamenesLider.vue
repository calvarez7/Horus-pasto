<template>
    <div>
        <v-dialog v-model="dialog" max-width="1400px">
            <v-card>
                
            </v-card>
        </v-dialog>
        <v-tabs centered color="primary" dark icons-and-text>
            <v-tabs-slider color="yellow"></v-tabs-slider>

            <v-tab href="#tab-1" @click="all('1')">
                Pendientes
                <v-icon>account_box</v-icon>
            </v-tab>

            <v-tab href="#tab-2" @click="all('2')">
                Realizados
                <v-icon>account_box</v-icon>
            </v-tab>

            <v-tab href="#tab-3" @click="all('3')">
                Seguimientos
                <v-icon>account_box</v-icon>
            </v-tab>

            <v-tab-item v-for="i in 3" :key="i" :value="'tab-' + i">
                <v-card flat>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                    </v-text-field>
                    <template>
                        <v-data-table :headers="headers" :items="examenes" class="elevation-1" :search="search">
                            <template v-slot:items="props" activator="{ on }">
                                <td>{{ props.item.id }}</td>
                                <td class="text-xs-left">{{ props.item.tipo_documento}}</td>
                                <td class="text-xs-left">{{ props.item.Documento }}</td>
                                <td class="text-xs-left">{{ props.item.Nombre }}</td>
                                <td class="text-xs-left">{{ props.item.Area }}</td>
                                <td>
                                    <v-btn color="primary" @click="informacionMostrar(props.item)">
                                        Examinar</v-btn>
                                </td>
                            </template>
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
                headers: [{
                        text: 'ID Registro',
                        sortable: false,
                    },

                    {
                        text: 'Tipo Documento',
                    },
                    {
                        text: 'Documento',
                        value: 'Documento'
                    },
                    {
                        text: 'Nombre'
                    },
                    {
                        text: 'Cargo',
                    },
                    {
                        text: 'Acciones',
                    },
                ],
                examenes: [],
            }

        },
        methods: {
            all(tipo = "1") {
                axios.get('/api/empleados/pendientes').then(res => {
                    this.examenes = res.data;
                });
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
            adjuntosRevision(idRevision) {
                axios.get('/api/sarlaft/adjuntosRevision/' + idRevision).then(res => {
                    this.adjuntoRevision = res.data;
                })
            },
            guardarAdjuntosrevision(idRevision) {
                this.preload = true;
                const data = this.$refs.adjuntoRevision.files
                let formData = new FormData();
                for (let i = 0; i < data.length; i++) {
                    formData.append(`adjuntoRevision[]`, data[i]);
                }


                axios.post('/api/sarlaft/guardarAdjuntosrevision/' + idRevision, formData).then(res => {
                    this.preload = false;
                    this.$alerSuccess("Adjuntos guardados con exito!");

                }).catch(error => {
                    this.preload = false;
                    this.$alerError("Error al guardar!");
                });
            },
            updateRevsion() {
                axios.put('/api/sarlaft/updateRevision/' + this.idSarlaft)
                    .then(res => {
                        this.$alerSuccess("Se Cerro la revision con exito!");
                        this.preload = false;
                        this.dialog = false;
                    }).catch(error => {
                        this.preload = false;
                        this.$alerError("Error al guardar!");
                        this.dialog = false;
                    });
                this.all("Vinculacion Primera Vez");

            },
            envioCorreo() {
                axios.post('/api/sarlaft/envioCorreo/' + this.adjuntos).then(res => {})
            },
            informacionMostrar(item) {
                this.dialog = true;
                // this.idSarlaft = item.idSarlaft

                // //informacion General
                // this.cedula = item.cedula
                // this.tipo_doc = item.nit
                // this.name = item.name + ' ' + item.apellido
                // this.tipo_cliente = item.clase
                // this.sector = item.sector
                // this.cual_descripcion = item.cual_descripcion
                // this.tipo_bienservicio = item.tipo_bienservicio
                // this.direccion = item.direccion
                // this.Municipio = item.municipio
                // this.Departamento = item.departamento
                // this.email_empresa = item.email_empresa
                // this.telefono_empresa = item.telefono_empresa
                // this.numero_contacto = item.numero_contacto
                // this.fax = item.fax
                // this.cargo_diligencia = item.cargo_diligencia
                // this.cedula_diligencia = item.cedula_diligencia
                // this.nombre_diligencia = item.nombre_diligencia
                // this.tipo_solicitud = item.tipo_solicitud
                // this.tipo_cliente = item.tipo_cliente

                // //Representante legal
                // this.Representante = item.nombre
                // this.tipo_documento = item.tipo_documento
                // this.num_doc = item.num_doc
                // this.fecha_exp = item.fecha_exp
                // this.Municipio_repre = item.Municipio_repre
                // this.fecha_nac = item.fecha_nac
                // this.Municipio_nacimiento = item.Municipio_nacimiento
                // this.otra_nacionalidad = item.otra_nacionalidad
                // this.emali = item.emali
                // this.ciudad_reci = item.Municipio_recidencia
                // this.deparamento_reci = item.deparamento_reci
                // this.direccion_reci = item.direccion_reci
                // this.pais_reci = item.pais_reci
                // this.telefono = item.telefono
                // if (item.cargo_publico == 0) {
                //     this.cargo_publico = 'NO'
                // } else {
                //     this.cargo_publico = 'SI'
                // }
                // if (item.poder_publico == 0) {
                //     this.poder_publico = 'NO'
                // } else {
                //     this.poder_publico = 'SI'
                // }
                // if (item.reconocimento_publico == 0) {
                //     this.reconocimento_publico = 'NO'
                // } else {
                //     this.reconocimento_publico = 'SI'
                // }
                // if (item.obli_tibutarias == 0) {
                //     this.obli_tibutarias = 'NO'
                // } else {
                //     this.obli_tibutarias = 'SI'
                // }
                // this.descripcion_obliga = item.descripcion_obliga

                // //socios
                // this.tipodoc_socio = item.tipodoc_socio
                // this.razon_social = item.razon_social
                // this.documento_socio = item.documento_socio
                // this.participacion = item.participacion
                // this.descripcion_expuevincula = item.descripcion_expuevincula

                // //personas expuestas
                // this.nombre_social = item.nombre_social
                // this.nacionalidad_expuesta = item.nacionalidad_expuesta
                // this.relacion_expuesta = item.relacion_expuesta
                // this.entidad_expuesta = item.entidad_expuesta
                // this.cargo_social = item.cargo_social

                // //informacion financiera
                // this.total_activos = item.total_activos
                // this.total_pasivos = item.total_pasivos
                // this.ingreso_mensual = item.ingreso_mensual
                // this.otros_ingresos = item.otros_ingresos
                // this.concepto_ingreso = item.concepto_ingreso
                // this.egresos_mensuales = item.egresos_mensuales
                // this.otros_egresos = item.otros_egresos
                // this.concepto_egreso = item.concepto_egreso

                // //operaciones internacionales
                // this.transa_monedaextr = item.transa_monedaextr
                // this.cual_moneda = item.cual_moneda
                // this.otra_moneda = item.otra_moneda
                // this.produc_extranjeros = item.produc_extranjeros
                // this.cual_prodc = item.cual_prodc
                // this.pais_operaciones = item.pais_operaciones

                // //declaracion
                // this.declaracion = item.declaracion
                // this.consultarRevision(item.idSarlaft)


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
            this.all();
        }
    }

</script>
