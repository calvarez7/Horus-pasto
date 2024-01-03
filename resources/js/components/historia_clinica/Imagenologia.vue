<template>
    <v-layout row wrap>
        <template>
            <div class="text-center">
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

        <v-flex xs12>
            <v-stepper v-model="e6" vertical>
                <v-stepper-step :complete="e6 > 1" editable :edit-icon="$vuetify.icons.complete" step="1">
                    IMAGENOLOGÍA
                </v-stepper-step>

                <v-stepper-content step="1">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>

                            <v-layout>
                                <v-flex xs12 md9 v-show="plantilla">
                                    <v-autocomplete label="Plantilla" :items="plantillas" @change="plantillaSelect()"
                                        item-text="nombre" item-value="id" v-model="selectPlantilla">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 md3>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text icon color="primary" dark v-on="on" @click="informacionCita()">
                                                <v-icon>list</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Gestión</span>
                                    </v-tooltip>
                                </v-flex>
                            </v-layout>

                            <v-flex v-if="this.campos_plantilla[0] !== null">
                                <v-flex v-for="(campo, index) in campos_plantilla" :key="index">
                                    <v-textarea label="Indicacion" v-model="campo.indicacion">
                                    </v-textarea>
                                    <v-textarea label="Tecnica" value="" v-model="campo.tecnica">
                                    </v-textarea>
                                    <v-textarea label="Hallazgos" value="" v-model="campo.hallazgos">
                                    </v-textarea>
                                    <v-textarea label="Conclusión" value="" v-model="campo.conclusion">
                                    </v-textarea>
                                    <v-select :items="['Urgente','Normal']" v-model="campo.prioridad" label="Prioridad">
                                    </v-select>
                                </v-flex>
                            </v-flex>

                            <v-dialog v-model="dialogGestion" persistent max-width="1000px">
                                <v-card max-height="100%" v-show="true">
                                    <v-toolbar color="primary" flat dark>
                                        <v-flex xs12 text-xs-center>
                                            <v-toolbar-title>Gestión</v-toolbar-title>
                                        </v-flex>
                                    </v-toolbar>
                                    <v-divider></v-divider>
                                    <v-card-text>
                                        <v-layout row wrap>
                                            <v-flex xs3 px-1>
                                                <v-text-field v-model="datosCita.documento" readonly label="Documento">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs4 px-1>
                                                <v-text-field v-model="datosCita.nombre" readonly label="Paciente">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs2 px-1>
                                                <v-text-field v-model="datosCita.edad" readonly label="Edad">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs3 px-1>
                                                <v-text-field v-model="datosCita.erp" readonly label="ERP">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs4 px-1 v-if="datosCita.fecha_cita">
                                                <v-text-field v-model="datosCita.fecha_cita.split('.')[0]" readonly
                                                    label="Fecha y hora">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs4 px-1>
                                                <v-text-field v-model="datosCita.especialista" readonly
                                                    label="Especialista"></v-text-field>
                                            </v-flex>
                                            <v-flex xs4 px-1>
                                                <v-text-field v-model="datosCita.prioridad" readonly label="Prioridad">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 px-1>
                                                <v-text-field v-model="datosCita.tipo_agenda" readonly
                                                    label="Tipo cita"></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 px-1>
                                                <v-text-field v-model="datosCita.fecha_orden" readonly
                                                    label="Fecha y hora orden">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs6 px-1>
                                                <v-text-field v-model="datosCita.tipo_paciente" readonly
                                                    label="Tipo paciente">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs4 px-1>
                                                <v-text-field v-model="datosCita.tecnica" readonly label="Tecnica">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs4 px-1>
                                                <v-text-field v-model="datosCita.lado" readonly label="lado">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs4 px-1>
                                                <v-text-field v-model="datosCita.lectura" readonly label="Lectura">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs4 px-1
                                                v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                                                <v-text-field v-model="datosCita.ubicacion" readonly label="Ubicación">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs4 px-1
                                                v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                                                <v-text-field v-model="datosCita.cama" readonly label="Cama">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs4 px-1
                                                v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                                                <v-text-field v-model="datosCita.aislado" readonly label="Aislado">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 px-1 v-if="datosCita.aislado == 'Si'">
                                                <v-text-field v-model="datosCita.observacion_aislado" readonly
                                                    label="Observación aislado">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 v-if="Object.keys(estudio).length > 0">
                                                <v-flex xs12>
                                                    <v-toolbar color="primary" dark class="mb-3">
                                                        <v-toolbar-title>Estudio tecnologo</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-layout wrap>
                                                    <v-flex xs3 px-2>
                                                        <v-text-field v-model="estudio.kv" readonly label="Kv">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3 px-2>
                                                        <v-text-field v-model="estudio.cantidad" readonly
                                                            label="Cantidad">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3 px-2>
                                                        <v-text-field v-model="estudio.distancia" readonly
                                                            label="Distancia">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3 px-2>
                                                        <v-text-field v-model="estudio.exposicion" readonly
                                                            label="Exposición">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3 px-2>
                                                        <v-text-field v-model="estudio.foco" readonly label="Foco">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3 px-2>
                                                        <v-text-field v-model="estudio.mas" readonly label="mAs">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3 px-2>
                                                        <v-text-field v-model="estudio.peso" readonly label="Peso">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3 px-2>
                                                        <v-text-field v-model="estudio.tiempo" readonly label="Tiempo">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3 px-2>
                                                        <v-text-field v-model="estudio.via" readonly label="Via">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3 px-2>
                                                        <v-text-field v-model="estudio.total_dosis" readonly
                                                            label="Total dosis">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea label="Observación" v-model="estudio.observacion"
                                                            readonly>
                                                        </v-textarea>
                                                    </v-flex>
                                                    <v-flex xs12 v-if="estudio.created_at">
                                                        <span><strong>Fecha:
                                                            </strong>{{ estudio.created_at.split('.')[0] }}
                                                            <strong>Nombre: </strong>{{ estudio.name }}
                                                            {{ estudio.apellido }}
                                                        </span>
                                                    </v-flex>
                                                    <v-divider class="my-4"></v-divider>
                                                </v-layout>
                                            </v-flex>
                                            <v-flex xs12 v-show="notas.length > 0">
                                                <v-flex xs12>
                                                    <v-toolbar color="primary" dark class="mb-3">
                                                        <v-toolbar-title>Notas enfermeria</v-toolbar-title>
                                                    </v-toolbar>
                                                </v-flex>
                                                <v-layout wrap>
                                                    <v-flex xs12 v-for="(nota, index) in notas" :key="index">
                                                        <v-flex xs12>
                                                            <span>Nota # {{index+1}}</span>
                                                        </v-flex>
                                                        <v-flex>
                                                            <v-container>
                                                                <v-layout wrap>
                                                                    <v-flex xs12 md3 px-1>
                                                                        <v-text-field v-model="nota.Temperatura"
                                                                            readonly label="Temperatura">
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md3 px-1>
                                                                        <v-text-field v-model="nota.Saturacionoxigeno"
                                                                            readonly label="Saturación de oxigeno">
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md3 px-1>
                                                                        <v-text-field
                                                                            v-model="nota.Presionarterialmedia" readonly
                                                                            label="Presión arterial">
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md3 px-1>
                                                                        <v-text-field v-model="nota.Frecucardiaca"
                                                                            readonly label="Frecuencia cardiaca">
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4 px-1>
                                                                        <v-text-field v-model="nota.Frecurespiratoria"
                                                                            readonly label="Frecuencia respiratoria">
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4 px-1>
                                                                        <v-text-field v-model="nota.peso" readonly
                                                                            label="Peso">
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md4 px-1>
                                                                        <v-text-field v-model="nota.tasa_filtracion"
                                                                            readonly
                                                                            label="Tasa de filtración glomerular">
                                                                        </v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12 md12 px-1>
                                                                        <v-textarea label="Nota" v-model="nota.nota"
                                                                            readonly>
                                                                        </v-textarea>
                                                                    </v-flex>
                                                                    <v-flex xs12>
                                                                        <span><strong>Fecha:
                                                                            </strong>{{ nota.created_at.split('.')[0] }}
                                                                            <strong>Nombre: </strong>{{ nota.name }}
                                                                            {{ nota.apellido }}
                                                                        </span>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                        </v-flex>
                                                        <v-divider class="my-4"></v-divider>
                                                    </v-flex>
                                                </v-layout>
                                            </v-flex>
                                            <v-flex xs12 v-show="datosCita.observacion">
                                                <v-layout wrap>
                                                    <v-flex xs12 v-for="(obs, index) in datosCita.observacion"
                                                        :key="index">
                                                        <v-flex xs12 v-if="index < 1">
                                                            <v-toolbar color="primary" dark class="mb-3">
                                                                <v-toolbar-title>Observaciones</v-toolbar-title>
                                                            </v-toolbar>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea readonly :value="obs.nota"
                                                                :label="`OBSERVACIÓN ${index+1}`">
                                                            </v-textarea>
                                                            <span><strong>Fecha:
                                                                </strong>{{ obs.created_at.split('.')[0] }}
                                                                <strong>Nombre: </strong>{{ obs.name }}
                                                                {{ obs.apellido }}
                                                            </span>
                                                        </v-flex>
                                                        <v-divider class="my-4"></v-divider>
                                                    </v-flex>
                                                </v-layout>
                                            </v-flex>
                                            <v-flex xs12 v-show="datosCita.adjuntos">
                                                <v-layout wrap>
                                                    <v-flex xs12 v-for="(data, index) in datosCita.adjuntos"
                                                        :key="index">
                                                        <v-flex xs12 v-if="index < 1">
                                                            <v-toolbar color="primary" dark class="mb-3">
                                                                <v-toolbar-title>Adjuntos</v-toolbar-title>
                                                            </v-toolbar>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-btn>
                                                                <a :href="`${data.ruta}`" target="_blank"
                                                                    style="text-decoration:none">
                                                                    <v-icon color="dark">mdi-cloud-upload</v-icon>
                                                                    Adjunto {{index+1}}
                                                                </a>
                                                            </v-btn>
                                                            <span><strong>Fecha:
                                                                </strong>{{ data.created_at.split('.')[0] }}
                                                                <strong>Nombre: </strong>{{ data.name }}
                                                                {{ data.apellido }}
                                                            </span>
                                                        </v-flex>
                                                        <v-divider class="my-4"></v-divider>
                                                    </v-flex>
                                                </v-layout>
                                            </v-flex>
                                            <v-flex xs12 px-1>
                                                <v-textarea class="mb-3" v-model="newObservasion" label="Observación">
                                                </v-textarea>
                                            </v-flex>
                                        </v-layout>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="warning" @click="dialogDevolver = true">
                                            <span>Devolver</span>
                                            <v-icon>fast_rewind</v-icon>
                                        </v-btn>
                                        <v-btn color="success" @click="observacion(datosCita)">
                                            <span>Observación</span>
                                            <v-icon>edit</v-icon>
                                        </v-btn>
                                        <v-btn color="error" @click="dialogGestion = false, newObservasion = ''">
                                            <span>Salir</span>
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>

                            <template>
                                <div class="text-center">
                                    <v-dialog v-model="dialogDevolver" width="260">
                                        <v-card>
                                            <v-card-text align="center" justify="center">
                                                <p>¿Donde desea devolver la atención?</p>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-btn color="success" @click="devolverAdmision(datosCita)">
                                                    <span>Admisiones</span>
                                                </v-btn>
                                                <v-btn color="primary" @click="devoolverTecnologo(datosCita)">
                                                    <span>Tecnologo</span>
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </div>
                            </template>

                        </v-container>
                    </v-card>
                    <v-btn color="primary" round @click="submit()">Guardar y seguir</v-btn>
                </v-stepper-content>
            </v-stepper>
        </v-flex>
    </v-layout>
</template>
<script>
    // import {
    //     EventBus
    // } from '../../../../event-bus.js';
    import Swal from 'sweetalert2';

    export default {
        props: {
            datosCita: Object
        },
        created() {
            this.removeLocalStorage();
            this.getRoute();
            this.getLocalStorage();
        },
        data() {
            return {
                e6: 1,
                citaPaciente: '',
                cie10Array: [],
                plantillas: [],
                selectPlantilla: '',
                campos_plantilla: [],
                data: {},
                plantilla: true,
                datosCita: {},
                dialogGestion: false,
                newObservasion: '',
                validacion: false,
                dialogDevolver: false,
                notas: [],
                estudio: [],
                preload: false
            }
        },
        computed: {
            cie10Concat() {
                return this.cie10Array.map(cie => {
                    return {
                        id: cie.id,
                        codigo: cie.Codigo_CIE10,
                        nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`
                    }
                });
            }
        },
        mounted() {
            this.fetchCie10s();
            this.plantillasRadiologo();
            this.fetchImagnenologia();
        },
        methods: {
            fetchImagnenologia() {
                axios.get('/api/imagenologia/' + this.citaPaciente + '/imagenologia')
                    .then(res => {
                        this.campos_plantilla = res.data;
                        if (this.campos_plantilla[0] !== null) {
                            this.plantilla = false
                        }
                    });
            },

            async submit() {

                this.campos_plantilla.forEach(cam => {
                    this.validacion = false;
                    if (!cam.indicacion) {
                        swal({
                            title: "Debe asignar una indicación",
                            icon: "warning"
                        });
                        return;
                    }
                    if (!cam.hallazgos) {
                        swal({
                            title: "Debe asignar un hallazgo",
                            icon: "warning"
                        });
                        return;
                    }

                    if (!cam.conclusion) {
                        swal({
                            title: "Debe asignar una conclusión",
                            icon: "warning"
                        });
                        return;
                    }

                    if (!cam.prioridad) {
                        swal({
                            title: "Debe elegir una prioridad",
                            icon: "warning"
                        });
                        return;
                    }
                    this.validacion = true;
                    this.data = {
                        indicacion: cam.indicacion,
                        hallazgos: cam.hallazgos,
                        conclusion: cam.conclusion,
                        notaclaratoria: cam.notaclaratoria,
                        tecnica: cam.tecnica,
                        prioridad: cam.prioridad
                    }

                })

                if (this.validacion == true) {
                    axios.post(`/api/imagenologia/create/${this.datosCita.cita_paciente_id}`, this.data)
                        .then(res => {
                            this.e6 = 3;
                            localStorage.setItem("Imagenologia", JSON.stringify(this.data));
                            swal({
                                title: "¡Historia Almacenada con Exito!",
                                text: `${res.data.message}`,
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                        });
                }

            },
            getRoute() {
                this.citaPaciente = this.$route.query.cita_paciente;
            },
            getLocalStorage() {
                let imagenologia = JSON.parse(localStorage.getItem("Imagenologia"));
                if (imagenologia) {
                    this.campos_plantilla = imagenologia;
                } else {
                    this.fetchImagnenologia();
                }
            },
            removeLocalStorage() {
                localStorage.removeItem("Imagenologia");
            },
            setCie10() {
                let object = this.cie10s.find(cie => cie.id == this.data.Cie10_id);
                this.paciente.Cie10_id = object.Codigo_CIE10;
            },
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10Array = res.data;
                });
            },

            plantillasRadiologo() {
                axios.post('/api/imagenologia/plantillas').then(res => {
                    this.plantillas = res.data;
                });
            },

            plantillaSelect() {
                let plantilla = {
                    id: this.selectPlantilla
                }
                axios.post('/api/imagenologia/plantillas', plantilla).then(res => {
                    this.campos_plantilla = res.data;
                });
            },

            informacionCita() {
                this.preload = true
                let data = {
                    id: this.datosCita.cita_paciente_id,
                    cita_paciente_id: this.datosCita.cita_paciente_id
                }
                axios.post('/api/imagenologia/notasEnfermeria', data).then(res => {
                    this.notas = res.data
                });
                axios.post('/api/imagenologia/estudioTecnologo', data).then(res => {
                    this.estudio = res.data
                });
                axios.post('/api/imagenologia/informacionCita', data).then(res => {
                    let data = res.data
                    data.forEach(inf => {
                        this.datosCita = {
                            nombre: `${inf.Primer_Nom} ${inf.Primer_Ape} ${inf.Segundo_Ape}`,
                            documento: inf.Num_Doc,
                            edad: inf.Edad_Cumplida,
                            especialista: `${inf.nameEspecialista} ${inf.apellidoEspecialista}`,
                            erp: inf.Ut,
                            fecha_cita: inf.Hora_Inicio,
                            cita_id: inf.id,
                            paciente_id: inf.paciente_id,
                            cita_paciente_id: inf.cita_paciente_id,
                            tecnica: inf.tecnica,
                            prioridad: inf.prioridad,
                            lado: inf.lado,
                            lectura: inf.lectura,
                            observacion: inf.observacion,
                            adjuntos: inf.adjuntos_cita,
                            tipo_paciente: inf.tipo_paciente,
                            tipo_agenda: inf.Tipo_agenda,
                            fecha_orden: inf.fecha_orden,
                            ubicacion: inf.ubicacion,
                            cama: inf.cama,
                            aislado: inf.aislado,
                            observacion_aislado: inf.observacion_aislado
                        }
                    });
                    this.preload = false
                    this.dialogGestion = true
                }).catch(err => {
                    this.preload = false
                })
            },

            observacion(citas) {
                let caracteres = this.newObservasion.length > 20
                if (caracteres == false) {
                    this.$alerError('Debe ingresar una observación mayor a 20 caracteres!');
                    return
                }
                this.observ = {
                    nota: this.newObservasion,
                    cita_paciente_id: citas.cita_paciente_id,
                    tipo: 'admin'
                }
                axios.post('/api/cita/observacion', this.observ).then(res => {
                    this.newObservasion = ''
                    this.informacionCita();
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Observación agregada con exito!'
                    })
                })
            },

            devolverAdmision(cita) {
                let paciente = {
                    Paciente_id: cita.paciente_id
                }
                swal({
                    title: "¿Está Seguro?",
                    text: "Sera devuelto a admisiones",
                    type: "success",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(async willDelete => {
                    if (willDelete) {
                        axios.put(`/api/imagenologia/devolverAdmisiones/${cita.cita_id}`, paciente).then(
                            res => {
                                localStorage.removeItem("PacienteCedula");
                                EventBus.$emit("disable-layout-hc");
                                localStorage.removeItem("citapaciente_id");
                                this.$router.push("/medico");
                                this.$alerSuccess("Devuelto a admisiones con exito!");
                            })
                    }
                });
            },

            devoolverTecnologo(cita) {
                let paciente = {
                    Paciente_id: cita.paciente_id
                }
                const msg = "Esta seguro de devolver a tecnologo?";
                swal({
                    title: msg,
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.put(`/api/imagenologia/confirmarEnfermeria/${cita.cita_id}`, paciente)
                            .then((res) => {
                                localStorage.removeItem("PacienteCedula");
                                EventBus.$emit("disable-layout-hc");
                                localStorage.removeItem("citapaciente_id");
                                this.$router.push("/medico");
                                this.$alerSuccess("Devuelto a tecnologo con exito!");
                            })
                    }
                });
            }
        }
    }

</script>
<style scoped>

</style>
