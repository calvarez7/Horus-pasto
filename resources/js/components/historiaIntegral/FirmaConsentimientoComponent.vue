<template>
    <v-card>
        <v-card-title class="headline info" style="color:white">
            <span class="title layout justify-center font-weight-light">{{ titulo }}</span>
        </v-card-title>

        <v-card-text>
            <v-container fluid>
                <v-layout row wrap align-center>
                    <!-- <v-flex xs12>
                        <v-flex xs12>
                            <v-select :items="['Paciente','Representante Legal']" v-model="criterios.firmante"
                                label="¿Firma el Paciente o el Representante legal?"></v-select>
                        </v-flex>
                        <v-flex xs12>
                            <v-Text-Field v-if="criterios.firmante == 'Representante Legal'"
                                v-model="criterios.documentoRepresentante"
                                label="Numero de Documento del Representante Legal"></v-Text-Field>
                        </v-flex>
                        <v-flex xs12 v-if="cup_id == 12020">
                            <v-select :items="['Inserción','Retiro']" v-model="criterios.aceptacion"
                                label="Procedimiento"></v-select>
                        </v-flex>
                        <v-flex xs12>
                            <span><b>DESCRIPCIÓN DEL PROCEDIMIENTO:</b></span>
                        </v-flex>
                        <v-flex xs12>
                            <span>El dispositivo intrauterino es un dispositivo de distintos materiales, recubierto por
                                metales que suele contener medicamentos, que se coloca dentro
                                del útero con fines anticonceptivos y/o como tratamiento de algunas metrorragias. La
                                colocación de este dispositivo y del modelo serán indicados
                                en consulta ambulatoria por el ginecólogo, quien indicará los controles posteriores que
                                debe tener, así como la duración de este.
                            </span>
                        </v-flex>
                        <br>
                        <v-flex xs12>
                            <span><b>RIESGOS DEL PROCEDIMIENTO:</b></span>
                        </v-flex>
                        <v-flex xs12>
                            <span>Permitir que el paciente una decisión libre y autónoma sobre su salud
                                reproductiva.
                            </span>
                        </v-flex>
                        <br>
                        <v-flex xs12>
                            <span><b>BENEFICIOS DEL PROCEDIMIENTO:</b></span>
                        </v-flex>
                        <v-flex xs12>
                            <span>En el momento de la inserción:
                                <ul>
                                    <li> Dolor </li>
                                    <li>Perforación uterina</li>
                                    <li>Infección en un periodo menor de un mes, pasado este periodo de tiempo la
                                        infección se debe a otras causas y no al dispositivo intrauterino.</li>
                                </ul>
                                En la evolución:
                                <ul>
                                    <li>Gestación (1-3%) si esta se produce, existe un mayor riesgo de aborto y de
                                        embarazo ectópico. La tasa real de fracaso como método
                                        anticonceptivo es mayor en el primer año, entre 1-3%.</li>
                                    <li>Descenso y expulsión, puede ser asintomático o cursar con dolor o sangrado.</li>
                                    <li>Alteraciones menstruales, aumento de la cantidad y/o duración del sangrado
                                        menstrual, manchado intermenstrual, así como la disminucióne
                                        e incluso ausencia de menstruación con los dispositivos intrauterinos con
                                        medicación.</li>
                                    <li>Dolor</li>
                                    <li>Migración a cavidad abdominal con las complicaciones subsiguientes.</li>
                                </ul>
                                En su extracción:
                                <ul>
                                    <li>Pérdida de referencia de los hilos y la rotura con retención de un fragmento.
                                    </li>
                                </ul>
                            </span>
                        </v-flex>
                        <br>
                        <v-flex xs12>
                            <span><b>ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO:</b></span>
                        </v-flex>
                        <v-flex xs12>
                            <span>Existen alternativas dentro de los métodos de planificación variados como son:
                                <ul>
                                    <li>Contraceptivos orales. </li>
                                    <li>Contraceptivos de barrera (condón y diafragma).</li>
                                    <li>Contraceptivos de Aplicación intramuscular mensuales o trimestrales.</li>
                                    <li>Contraceptivos hormonales mediante implante subdérmico</li>
                                    <li>Cada uno de estos métodos con sus posibles beneficios y complicaciones de
                                        acuerdo con la edad del paciente y las comorbilidades, para lo
                                        cual se deben tener en cuenta los criterios de elegibilidad.</li>
                                </ul>
                            </span>
                        </v-flex>

                        <br>
                        <v-flex xs12>
                            <span><b>RIESGO DE NO ACEPTAR EL PROCEDIMIENTO:</b></span>
                        </v-flex>
                        <v-flex xs12>
                            <span>En caso de no realizarse el procedimiento o intervención se pueden presentar embarazos
                                no deseados ni planeados y, en usuarios con patologías
                                de comorbilidad de base como hipertensión, diabetes, patologías cardiacas, hereditarias,
                                puede generar complicaciones graves en el estado de
                                salud del usuario. En caso de no retirarse el implante subdérmico en el tiempo indicado,
                                este no tendrá el mismo efecto anticonceptivo por lo tanto
                                es posible que se presente un embarazo no deseado.
                            </span>
                        </v-flex>

                        <br>
                        <v-flex xs12>
                            <span><b>¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?</b></span>
                        </v-flex>
                        <v-flex xs12>
                            <span>En caso de requerir más información al respecto de su tratamiento se puede dirigir al
                                médico tratante o al personal médico y de enfermería
                                presente.
                            </span>
                        </v-flex>

                        <br>
                        <v-flex xs12>
                            <span><b>RECOMENDACIONES:</b></span>
                        </v-flex>
                        <v-flex xs12>
                            <span>Es necesario que informe al personal médico cualquier alteración propia de su salud,
                                la existencia de comorbilidades, tales como hipertensión,
                                diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
                                El paciente debe advertir de las posibles alergias
                                medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares,
                                existencia de prótesis marca pasos, medicaciones que esté
                                recibiendo o cualquier otra circunstancia que afecte a su salud.
                            </span>
                        </v-flex>

                        <br>
                        <v-flex xs12>
                            <span><b>DECLARACIÓN DE CONSENTIMIENTO INFORMADO:</b></span>
                        </v-flex>
                        <v-flex xs12>
                            <span v-if="criterios.firmante == 'Representante Legal'">En caso de realización de
                                procedimiento en menor de edad o persona en condición de
                                discapacidad,
                            </span>
                            <v-Text-Field v-if="criterios.firmante == 'Representante Legal'"
                                v-model="criterios.nombre_representante" label="Nombre del Representante Legal">
                            </v-Text-Field>
                            <span>
                                <b>a)</b> Declaro que he entendido las condiciones y objetivos del procedimiento que se
                                me va a
                                practicar, los cuidados que debo tener antes y después,
                                manifiesto que la información recibida ha sido en un lenguaje claro y sencillo, y me ha
                                dado la oportunidad de preguntar y resolver las dudas a
                                satisfacción; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento
                                que me indican, por lo que manifiesto sentirme
                                satisfecho(a) con la información recibida:
                            </span>
                            <v-autocomplete :items="['SI','NO']" v-model="criterios.declaracion_a">
                            </v-autocomplete>
                            <span>
                                <b>b)</b> El profesional me ha planteado la posibilidad de participar en estudios
                                investigativos que adelanta la institución con fines de mejoramiento
                                continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán
                                empleados guardando estricta confidencialidad,
                                asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en
                                el proceso con fines exclusivamente académicos, por lo
                                que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo:
                            </span>
                            <v-autocomplete :items="['SI','NO']" v-model="criterios.declaracion_b">
                            </v-autocomplete>
                            <span>
                                <b>c)</b> Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al
                                equipo de
                                salud de la Sumimedical S.A.S. para que se me realice el
                                procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado
                                por mi médico tratante. Entiendo y asumo los riesgos
                                relacionados con la realización de este y autorizo a que se tomen las medidas necesarias
                                ante cualquier complicación derivada del procedimiento.
                            </span>
                            <v-autocomplete :items="['SI','NO']" v-model="criterios.declaracion_c">
                            </v-autocomplete>
                        </v-flex> -->
                        <br>
                        <v-flex xs12>
                            <span><b>NOTA:</b></span>
                        </v-flex>
                        <v-flex xs12>
                            <span> como paciente, usted tiene derecho a retractarse de este consentimiento informado en
                                cualquier momento antes o durante la realización de
                                la intervención.
                            </span>
                        </v-flex>

                        <v-flex xs12>
                            <v-select :items="['Si','No']" v-model="criterios.aceptacion"
                            label="¿Acepta el Consenimiento Informado?"></v-select>
                        </v-flex>
                        <br>
                        <v-flex xs12 v-if="criterios.aceptacion == 'No'">
                            <span><b>DISENTIMIENTO O DESISTIMIENTO INFORMADO:</b></span>
                        </v-flex>
                        <v-flex xs12 v-if="criterios.aceptacion == 'No'">
                            <span> En el presente documento manifiesto que he sido informado sobre mi condición, las
                                eventuales complicaciones y/o riesgos que se deriven, los
                                beneficios de los procedimientos planeados, de manera que se puedan tomar decisiones
                                informadas; no obstante, reunido con el profesional<v-Text-Field
                                    v-model="criterios.nombre_profesional">
                                </v-Text-Field> decido de forma libre y consciente no aceptar/revocar la realización del
                                procedimiento propuesto, haciéndome responsable de las consecuencias que puedan
                                derivarse de esta decisión.
                            </span>
                        </v-flex>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card-text>

        <v-card-text class="texto-centrado">
            <div>
                <video ref="video" playsinline autoplay></video>
                <canvas ref="canvas" :width="camara.width" :height="camara.height"></canvas>
            </div>
            <v-btn @click="tomarFoto()" color="primary">Tomar foto</v-btn>
        </v-card-text>

        <!-- Firma obligatoria -->
        <v-card-text>
            <div class="firma" v-if="criterios.aceptacion != null">
                <h4 class="ml-3">Fima del paciente o Representante Legal</h4>
                <VueSignaturePad width="100%" :customStyle="customStyle" height="200px" ref="firmaPaciente" />
                <v-btn text @click="limpiarFirma($refs.firmaPaciente)">Limpiar</v-btn>
            </div>
        </v-card-text>

        <!-- Firma Opcional -->
        <!-- <v-card-text>
            <div class="firma">
                <h4 class="ml-3">Fima del acudiente (Opcional)</h4>
                <VueSignaturePad width="100%" height="200px" ref="firmaAcudiente" />
                <v-btn text @click="limpiarFirma($refs.firmaAcudiente)">Limpiar</v-btn>
            </div>
        </v-card-text> -->

        <v-card-actions>
            <v-btn @click="submit()" dark color="success">Guardar</v-btn>
        </v-card-actions>

    </v-card>

</template>
<script>
    export default {
        name: 'consentimientoInformadoComponent',
        props: {
            cita_paciente_id: Number,
            cup_id: Number,
        },

        data() {
            return {
                camara: {
                    width: 500,
                    height: 300
                },
                customStyle: {
                    border: 'black 3px solid',
                    'background-color': '#D7D7D7',
                },
                criterios: {
                    aceptacion: null,
                    firmante: null,
                    documentoRepresentante: null,
                },
                titulo: null
            }
        },

        mounted() {
            this.iniciarCamara();
        },

        watch: {
            cup_id() {
                if (this.cup_id == 8490 || this.cup_id == 12020 || this.cup_id == 4072) {
                    this.titulo =
                        'CONSENTIMIENTO INFORMADO PARA LA INSERCIÓN O RETIRO DE DISPOSITIVO INTRAUTERINO (DIU)'
                }
            },
        },
        methods: {

            async submit() {
                try {
                    const firma_paciente = this.$refs.firmaPaciente.saveSignature();
                    // const firma_acudiente = this.$refs.firmaAcudiente.saveSignature();

                    if (this.criterios.aceptacion == null) {
                        return this.$alerError('Debe aceptar o no, el consentimiento informado');
                    }

                    if (this.criterios.firmante == 'Representante Legal') {
                        if (this.criterios.documentoRepresentante == null) {
                            return this.$alerError('Debe digitar la cedula de representante legal');
                        }
                    }

                    if (!this.$refs.canvas.toDataURL()) {
                        console.log('La foto es obligatoria');
                        return false;
                    }

                    if (firma_paciente.isEmpty) {
                        console.log('La firma del paciente es obligatoria');
                        return false;
                    }

                    const request = {
                        'cita_paciente_id': this.cita_paciente_id,
                        'foto': this.$refs.canvas.toDataURL(),
                        'firma_paciente': firma_paciente.data,
                        'aceptacion_consentimiento': this.criterios.aceptacion,
                        'firmante': this.criterios.firmante,
                        'numero_documento_representante': this.criterios.documentoRepresentante,
                        // 'firma_acudiente': firma_acudiente.isEmpty ? null : firma_acudiente.data,
                    }


                    const response = await axios.post('/api/paciente/guardar-foto-firma', request);
                    this.limpiar();
                    this.$emit('submit');
                } catch (error) {

                }
            },

            async iniciarCamara() {
                try {
                    const opciones = {
                        audio: false,
                        video: {
                            width: this.camara.width,
                            height: this.camara.height
                        }
                    }
                    const stream = await navigator.mediaDevices.getUserMedia(opciones);
                    this.streming(stream);
                } catch (error) {

                }
            },

            streming(stream) {
                window.stream = stream;
                this.$refs.video.srcObject = stream;
            },

            tomarFoto() {
                const context = this.$refs.canvas.getContext('2d');
                context.drawImage(this.$refs.video, 0, 0, this.camara.width, this.camara.height);
            },

            limpiarFirma(pad) {
                pad.clearSignature();
            },

            limpiar() {
                this.$refs.firmaPaciente.clearSignature();
                // this.$refs.firmaAcudiente.clearSignature();
            }
        }
    }

</script>
<style>
    .firma {
        border: 2.5px solid #78909C;
        border-radius: 5px;
    }

    .texto-centrado {
        text-align: center;
    }

</style>
