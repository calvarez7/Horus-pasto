<template>
    <v-container>
        <v-layout row justify-center>
            <v-flex xs12>
                <template>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <table>
                                <tbody>
                                    <tr>
                                        <td rowspan="3">
                                            <img src="images\logo.png" alt="" style="height: 80px;">
                                        </td>
                                        <td rowspan="3">
                                            <h1 style="width: 703px;border-top: 10px;" class="centrar mb-5;">
                                                CONSENTIMIENTO INFORMADO PARA ATENCIÓN BAJO LA MODALIDAD DE TELEMEDICINA
                                            </h1>
                                        </td>
                                        <td style="width: 214px; text-align: center;">
                                            <h3>Código: FO-CE-031</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">
                                            <h3>Versión: 02</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">
                                            <h3>Fecha de aprobación:</h3>
                                            <h5>25/05/2023</h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-layout>
                        <v-container grid-list-md>
                            <v-layout row wrap>
                                <v-flex xs12 md4><b>Fecha de diligenciamiento: {{ dia }}</b></v-flex>
                                <v-flex xs7 offset-xs1><b>Servicio: {{ datos.especialidad }}</b></v-flex>
                            </v-layout>
                        </v-container>

                        <table>
                            <tbody>
                                <tr>
                                    <td rowspan="2" style="width: 130px; text-align: center;">
                                        <h3>Paciente</h3>
                                    </td>
                                    <td style="height: 60px; text-align: center;">{{ datos.Primer_Ape }}</td>
                                    <td style="text-align: center;">{{ datos.Segundo_Ape }}</td>
                                    <td style="text-align: center;">{{ datos.Primer_Nom }}</td>
                                    <td style="text-align: center;">{{ datos.Num_Doc  }}</td>
                                    <td style="text-align: center;">{{ datos.Edad_Cumplida  }}</td>
                                </tr>
                                <tr>
                                    <td style="width: 200px; height: 60px; text-align: center;">
                                        <h3>Primer apellido</h3>
                                    </td>
                                    <td style="width: 200px; height: 60px; text-align: center;">
                                        <h3>Segundo apellido</h3>
                                    </td>
                                    <td style="width: 200px; height: 60px; text-align: center;">
                                        <h3>Nombres completos</h3>
                                    </td>
                                    <td style="width: 200px; height: 60px; text-align: center;">
                                        <h3>Documento</h3>
                                    </td>
                                    <td style="width: 110px; height: 60px; text-align: center;">
                                        <h3>Edad</h3>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </v-container>
                </template>
                <h3>DESCRIPCIÓN DEL PROCEDIMIENTO</h3>
                <p>La telemedicina involucra el uso de tecnología de telecomunicaciones y medios electrónicos para
                    interactuar con usted, revisar su información médica para propósitos de diagnóstico, terapia,
                    seguimiento y/o educación.</p>

                <h3>BENEFICIOS ESPERADOS DEL PROCEDIMIENTO</h3>
                <p>Los beneficios esperados de la atención bajo la modalidad de Telemedicina Interactiva son:
                    Mayor acceso a la consulta médica por ser remota evitando desplazamiento.
                    Protección frente a exposición a posibles contagios de enfermedades infecciosas por otras personas
                    al recibir la atención mediante la modalidad de telemedicina en un sitio diferente a una Institución
                    prestadora de servicios de salud, tal como mi vivienda.
                    Disminuye los costos y riesgos de desplazamiento fuera de su entorno.</p>

                <h3>POSIBLES COMPLICACIONES:</h3>
                <p>La consulta médica a través de la modalidad de Telemedicina Interactiva conlleva a la ausencia de
                    examen físico, lo que puede complicar o dificultar una impresión diagnóstica. Es posible que de
                    acuerdo con los resultados de la valoración médica deba acudir de manera presencial para ser
                    valorado por profesionales de Sumimedical S.A.S. o dirigirse a la red de prestadores de su
                    asegurador. Es deber del paciente seguir y acatar las recomendaciones médicas.
                    En casos excepcionales, la información transmitida puede no ser suficiente (p. ej. Baja resolución
                    de las imágenes) para permitir una toma apropiada de decisiones médicas.
                    Posibles demoras en la evaluación/tratamiento médico debido a deficiencias o fallos en el equipo
                    electrónico
                    En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos y
                    dispone de los medios para el manejo de las complicaciones que lleguen a presentarse.</p>

                <h3>ALTERNATIVAS DISPONIBLES A LA CONSULTA</h3>
                <p>Si usted no acepta esta consulta puede solicitarla en modalidad presencial o dirigirse por sus
                    propios medios al servicio de consulta prioritaria de Sumimedical S.A.S. o en la red de prestadores
                    de su asegurador</p>

                <h3>RIESGO DE NO ACEPTAR LA CONSULTA</h3>
                <p>En caso de negarse probablemente continuará con los síntomas que ahora padece y se puede agravar su
                    estado de salud.</p>

                <h3>¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?</h3>
                <p>En caso de requerir más información al respecto se puede dirigir a la sede donde se encuentre
                    inscrito.</p>

                <h3>RECOMENDACIONES</h3>
                <p>Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de
                    comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer
                    mejor sus condiciones de salud.
                    El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación,
                    enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo
                    o cualquier otra circunstancia que afecte a su salud.</p>

                <h3>DECLARACIÓN DEL PACIENTE / TUTOR / REPRESENTANTE LEGAL</h3>
                <p>Declaro en pleno uso de mis facultades mentales que he recibido una explicación y descripción clara y
                    en lenguaje sencillo sobre el servicio de Consulta Externa bajo la modalidad de Telemedicina que voy
                    a recibir, mediante la cual un Profesional de la Salud, haciendo uso de una herramienta de
                    videollamada, hará una valoración de mi estado de salud, con miras a obtener un concepto médico y a
                    ordenar los medicamentos y/o tecnologías en salud que considere necesarios para mi tratamiento.</p>
                <p>Se me han aclarado todas las dudas y se me han dicho los beneficios, posibles riesgos y
                    complicaciones, así, como las otras alternativas de tratamiento al servicio que voy a recibir, y la
                    posibilidad de que los profesionales de la Salud determinen la necesidad de cambio de modalidad en
                    la atención, según la valoración que hagan de mi estado de salud o por fallas tecnológicas en el
                    proceso de atención que impidan el cabal desarrollo de esta. Igualmente declaro que se me ha
                    informado que para efectos de brindarme atención médica, autorizo expresamente a Sumimedical S.A.S
                    para recolectar, clasificar, almacenar, utilizar, archivar y cualquier otra modalidad de tratamiento
                    a mis Datos Personales, incluyendo mis datos sensibles, conforme a las finalidades establecidas en
                    la Política de Tratamiento de Datos Personales disponible para su consulta en la página web <a
                        href="https://sumimedical.com/politica-de-privacidad/"
                        target="_blank">https://sumimedical.com/politica-de-privacidad/</a> la cual puede ser modificada
                    por la institución sin previo aviso. Estas finalidades incluyen soportar la atención medico
                    asistencial, remitir información a su entidad aseguradora, gestionar procesos de cobro, realizar
                    encuestas de satisfacción; enviar por cualquier canal (correo electrónico, SMS, físico) resultados
                    de exámenes diagnósticos.</p>
                <p>Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la
                    Sumimedical S.A.S. el servicio de consulta externa bajo la modalidad de Telemedicina Interactiva y
                    que el servicio requiere de una conexión a internet estable, lo que puede implicar un consumo de
                    datos celulares o de ancho de banda para el paciente. Entiendo y asumo los riesgos relacionados con
                    la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación
                    derivada de la consulta. SI_x_ NO___ (marque con una X). </p>
                <p>Autorizo el tratamiento de mis datos personales, incluyendo mis datos sensibles, para los fines
                    previamente señalados, así como la posibilidad de grabación y almacenamiento en la nube hacia
                    servidores nacionales o internacionales SI_x_ NO___ (marque con una X).
                    Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier
                    momento antes o durante la realización de la intervención. Consulta, y que, en caso de no aceptar
                    esta modalidad de tratamiento, puedo continuar recibiendo atención médica.</p>

                <h3 v-if="datos.Edad_Cumplida < 19">MENORES DE EDAD E INCAPACES</h3>
                <h3 v-else>MENORES DE EDAD E INCAPACES</h3>
                <p v-if="datos.Edad_Cumplida < 19">En caso de que el paciente sea menor de 18 años, y/o incapaz, este consentimiento será suscrito por
                    su representante legal.
                    Sé que el paciente es incapaz de tomar por sí mismo la decisión de aceptar o rechazar el tratamiento
                    descrito arriba. Por ende, en mi condición de Representante Legal del menor y/o incapaz
                    <b>{{datos.nombrePaciente}} </b>. El médico tratante me ha explicado de forma satisfactoria en qué
                    consiste, cuáles son los objetivos del procedimiento y me han informado los riesgos y los
                    procedimientos alternativos
                    He comprendido todo lo anterior, y por ende Yó <b>{{datos.nombreRepresentante}}</b> con cedula de
                    ciudadanía número: <b>{{datos.docRepresentante}}</b> Otorgo mi Consentimiento en nombre del paciente menor de edad y/o
                    incapaz, para que se le preste el servicio de Consulta Externa bajo la modalidad de Telemedicina
                    Interactiva.
                    SI_x_ NO___ (marque con una X).</p>

                <p v-else>En caso de que el paciente sea menor de 18 años, y/o incapaz, este consentimiento será suscrito por
                    su representante legal.
                    Sé que el paciente es incapaz de tomar por sí mismo la decisión de aceptar o rechazar el tratamiento
                    descrito arriba. Por ende, en mi condición de Representante Legal del menor y/o incapaz
                    _______________________________. El médico tratante me ha explicado de forma satisfactoria en qué
                    consiste, cuáles son los objetivos del procedimiento y me han informado los riesgos y los
                    procedimientos alternativos
                    He comprendido todo lo anterior, y por ende Yó ___________________________________con cedula de
                    ciudadanía número: __________ Otorgo mi Consentimiento en nombre del paciente menor de edad y/o
                    incapaz, para que se le preste el servicio de Consulta Externa bajo la modalidad de Telemedicina
                    Interactiva.
                    SI_x_ NO___ (marque con una X).</p>
                <h3>MANIFESTACIÓN DE VOLUNTAD:</h3>
                <p>En consideración a lo anterior, de manera libre y voluntaria doy mi consentimiento para ser atendido
                    bajo la modalidad de Telemedicina Interactiva; de tal forma que el consentimiento que emito se
                    encuentra exento de cualquier error. En cualquier caso, deseo que se me respeten las siguientes
                    condiciones (si no hay condiciones escríbase “ninguna” y sí no se aceptan algunos de los otros
                    puntos hágase constar): </p>

                <div class="firma mb-5" v-if="!firmado">
                    <h4 class="ml-3">Fima del paciente</h4>
                    <VueSignaturePad width="100%" height="200px" ref="firmaPaciente" />
                    <v-btn text @click="limpiar()">Limpiar</v-btn>
                </div>

                <div class="my-5" v-else>
                    <h2 class="centrar">Este documento ya fue firmado</h2>
                </div>
                <v-btn color="primary" :block="true" @click="submit()" v-if="!firmado">Firmar</v-btn>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
import moment from "moment";
    export default {

        data() {
            return {
                cita_paciente_id: null,
                documento: null,
                firmado: false,
                dia: null,
                datos:{}
            }
        },

        created() {
            this.cita_paciente_id = this.$route.query.cita_id
            this.documento = this.$route.query.documento
            this.dia = moment(new Date()).format('YYYY-MM-D HH:mm')
            this.consultar()
        },

        methods: {

            async submit() {
                try {
                    const firma_paciente = this.$refs.firmaPaciente.saveSignature();
                    if (firma_paciente.isEmpty) {
                        this.$toast.warning('Se debe de llenar la firma')
                        return false;
                    }
                    const request = {
                        'firma': firma_paciente.data,
                        'token': 'KMkm5PyrELKB2jnLMKyBgi8WgPwSNizSwwxJXBuY'
                    }
                    const response = await axios.post('/api/cita/firmar-consentimiento/' + this.cita_paciente_id +
                        '/' + this.documento, request);
                    this.firmado = true;
                    this.limpiar();
                } catch (error) {
                    console.log(error.response)
                }
            },

            async consultar() {
                try {
                    const {
                        data
                    } = await axios.get('/api/cita/consultar/' + this.cita_paciente_id +
                        '?token=KMkm5PyrELKB2jnLMKyBgi8WgPwSNizSwwxJXBuY')
                    if (data.firma_consentimiento_time) {
                        this.firmado = true;
                    }
                    this.datos = data
                } catch (error) {
                    console.log(error.response)
                    console.log(error)
                }
            },

            limpiar() {
                this.$refs.firmaPaciente.clearSignature();
            }
        }
    }

</script>
<style>
    .firma {
        border: 2.5px solid #78909C;
        border-radius: 5px;
    }

    .centrar {
        text-align: center;
    }

    p {
        text-align: justify;
    }

</style>
