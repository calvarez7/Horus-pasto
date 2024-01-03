<template>
    <div>
        <div class="login-root">
            <div class="box-root flex-flex flex-direction--column" style="min-height: 100vh;flex-grow: 1;">
                <div class="loginbackground box-background--white padding-top--64">
                    <div class="loginbackground-gridContainer">
                        <div class="box-root flex-flex" style="grid-area: top / start / 8 / end;">
                            <div class="box-root"
                                style="background-image: linear-gradient(white 0%, rgb(247, 250, 252) 33%); flex-grow: 1;">
                            </div>
                        </div>
                        <div class="box-root flex-flex" style="grid-area: 4 / 2 / auto / 5;">
                            <div class="box-root box-divider--light-all-2 animationLeftRight tans3s"
                                style="flex-grow: 1;"></div>
                        </div>
                        <div class="box-root flex-flex" style="grid-area: 6 / start / auto / 2;">
                            <div class="box-root box-background--blue800" style="flex-grow: 1;"></div>
                        </div>
                        <div class="box-root flex-flex" style="grid-area: 7 / start / auto / 4;">
                            <div class="box-root box-background--blue animationLeftRight" style="flex-grow: 1;">
                            </div>
                        </div>
                        <div class="box-root flex-flex" style="grid-area: 8 / 4 / auto / 6;">
                            <div class="box-root box-background--gray100 animationLeftRight tans3s"
                                style="flex-grow: 1;"></div>
                        </div>
                        <div class="box-root flex-flex" style="grid-area: 2 / 15 / auto / end;">
                            <div class="box-root box-background--cyan200 animationRightLeft tans4s"
                                style="flex-grow: 1;"></div>
                        </div>
                        <div class="box-root flex-flex" style="grid-area: 3 / 14 / auto / end;">
                            <div class="box-root box-background--blue animationRightLeft" style="flex-grow: 1;">
                            </div>
                        </div>
                        <div class="box-root flex-flex" style="grid-area: 4 / 17 / auto / 20;">
                            <div class="box-root box-background--gray100 animationRightLeft tans4s"
                                style="flex-grow: 1;"></div>
                        </div>
                        <div class="box-root flex-flex" style="grid-area: 5 / 14 / auto / 17;">
                            <div class="box-root box-divider--light-all-2 animationRightLeft tans3s"
                                style="flex-grow: 1;"></div>
                        </div>
                    </div>
                </div>
                <div class="box-root padding-top--24 flex-flex flex-direction--column"
                    style="flex-grow: 1; z-index: 9;">
                    <v-flex xs12 sm6 offset-sm3>
                        <v-card>
                            <v-img src="/images/operadores.png"></v-img>
                            <v-card-title primary-title>
                                <v-form>
                                    <v-container grid-list-md>
                                        <v-layout row wrap>
                                            <v-flex xs12 sm12 md4>
                                                <v-text-field type='number' label="Cedula" v-model="operadores.cedula">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md4>
                                                <v-text-field type='email' label="Correo Electronico"
                                                    v-model="operadores.email">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md4>
                                                <v-select label="Sexo" :items="sexo" v-model="operadores.sexo">
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs12 sm12 md4>
                                                <v-text-field type='date' label="Fecha Nacimiento"
                                                    v-model="operadores.fecha_nacimiento" @change="micicloVital()">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md4>
                                                <v-text-field label="Ciclo Vital" v-model="operadores.ciclo_vida"
                                                    placeholder="Mi ciclo" readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md4>
                                                <v-text-field label="Perimetro Abdominal"
                                                    v-model="operadores.perimetro_abdominal"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md4>
                                                <v-text-field type='number' label="Talla(Cm)"
                                                    v-model="operadores.talla"></v-text-field>
                                            </v-flex>

                                            <v-flex xs12 sm12 md4>
                                                <v-text-field type='number' label="Peso(Kg)" v-model="operadores.peso"
                                                    @change="mediaIMC()"></v-text-field>
                                            </v-flex>

                                            <v-flex xs12 sm12 md4>
                                                <v-text-field label="IMC" :value="MiImc" placeholder="imc" readonly>
                                                </v-text-field>
                                            </v-flex>

                                            <!-- <v-flex xs12 sm12 md6 >
                                                <v-text-field label="Clasificación" :value="operadores.clasificacion"
                                                    placeholder="imc" readonly></v-text-field>
                                            </v-flex> -->

                                            <v-flex xs12 sm12 md12>
                                                <v-select label="Realizas al menos 30 min de ejercicio al dia?"
                                                    :items="siNo" v-model="operadores.ejercicio"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-select :items="ejercicio"
                                                    label="Comes frutas o verduras de forma diaria?"
                                                    v-model="operadores.frutas_verduras"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-select label="Tomas tratamientos Para hipertension?" :items="siNo"
                                                    v-model="operadores.hipertension"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-select label="Te han encontrado alguna veces azucar alta?"
                                                    :items="siNo" v-model="operadores.azucar"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-select label="Tienes algun familiar con diabetes?"
                                                    :items="familiaDiabetes" v-model="operadores.diabetes"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-select label="Eres diabetico?" :items="siNo"
                                                    v-model="operadores.eres_diabetico"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-select label="Tienes examen de colesterol reciente?" :items="siNo"
                                                    v-model="operadores.examen_colesterol"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm12 md4 v-if="operadores.examen_colesterol == 'Si'">
                                                <v-text-field type='number'
                                                    label="Colesterol total valor (miligramos/decilitro)?"
                                                    v-model="operadores.total_colesterol"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md4 v-if="operadores.examen_colesterol == 'Si'">
                                                <v-text-field type='number'
                                                    label="Colesterol HDL valor (miligramos/decilitro)?"
                                                    v-model="operadores.colesterol_hdl"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md4 v-if="operadores.examen_colesterol == 'Si'">
                                                <v-text-field type='number'
                                                    label="Triglicéridos valor (miligramos/decilitro)?"
                                                    v-model="operadores.trigliceridos" @change="resultadoLDL()">
                                                </v-text-field>
                                            </v-flex>
                                            <!-- Si marca si se sacan los dos campos del colesterol -->
                                            <v-flex xs12 sm12 md4>
                                                <v-select label="Eres fumador?" :items="siNo"
                                                    v-model="operadores.fumador"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm12 md4>
                                                <v-text-field label="Presión sistólica" type="number" :min="90"
                                                    :max="220" v-model="operadores.presion_sistolica"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md4>
                                                <v-text-field label="Presión diastólica" type="number" :min="50"
                                                    :max="120" v-model="operadores.presion_diastolica"></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-form>
                            </v-card-title>
                            <v-card-actions>
                                <v-btn color="success" @click="validacionPaciente()">Realizar Diagnostico</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                    <v-dialog v-model="dialog" persistent>
                        <v-card>
                            <v-card-title class="headline success" text-color="white">
                                <span class="white--text center">Solicitud procesada con exito!</span>
                            </v-card-title>
                            <v-container fluid>
                                <v-layout row>
                                    <v-flex xs4>
                                        <p>Usted recibira en el correo registrado todo su analisis y prediccion
                                            por parte de nuestro equipo de Gestión del riesgo...
                                        </p>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" dark @click="dialog = false, limpiarCampos()">Cerrar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    moment.locale('es');
    export default {
        name: 'HorusHealthIndex',

        data() {
            return {
                ejercicio: ['Todos los dias', 'No todos los dias'],
                siNo: ['Si', 'No'],
                sexo: ['Femenino', 'Masculino', 'No responde'],
                familiaDiabetes: ['Si padres, hermanos, hijos', 'Si abuela, tios, primos hermanos', 'No'],
                operadores: {
                    cedula: '',
                    examen_colesterol: '',
                    fecha_nacimiento: '',
                    ciclo_vida: '',
                    peso: '',
                    talla: '',
                    clasificacion: '',
                    sexo: '',
                    perimetro_abdominal: '',
                    ejercicio: '',
                    frutas_verduras: '',
                    hipertension: '',
                    azucar: '',
                    diabetes: '',
                    eres_diabetico: '',
                    total_colesterol: '',
                    colesterol_hdl: '',
                    fumador: '',
                    tension_arterial: '',
                    imc: '',
                    trigliceridos: ''
                },
                resultado: {
                    clasificacion: '',
                    clasificacionLDL: '',
                },
                dialog: false,
            };
        },


        computed: {
            MiEdad: function () {
                let hoy = moment();
                let fechaNacimiento = moment(this.operadores.fecha_nacimiento)
                let edad = hoy.diff(fechaNacimiento, 'years')
                return edad
            },
            MiImc: function () {
                let imc = this.operadores.peso / Math.pow(this.operadores.talla / 100, 2);
                let totalImc = parseFloat(imc).toFixed(1);
                return totalImc
            },
            MiLdl: function () {
                let Ldl = (this.operadores.total_colesterol - (this.operadores.trigliceridos / 5) - this
                    .operadores.colesterol_hdl);
                let totaLdl = parseFloat(Ldl).toFixed(1);
                return totaLdl
            }
        },

        methods: {
            micicloVital() {
                let edad = this.MiEdad
                if (parseInt(edad) <= 5) {
                    this.operadores.ciclo_vida = '1ra Infancia';
                } else if (parseInt(edad) >= 6 && parseInt(edad) <= 11) {
                    this.operadores.ciclo_vida = 'Infancia';
                } else if (parseInt(edad) >= 12 && parseInt(edad) <= 17) {
                    this.operadores.ciclo_vida = 'Adolescencia';
                } else if (parseInt(edad) >= 18 && parseInt(edad) <= 28) {
                    this.operadores.ciclo_vida = 'Juventud';
                } else if (parseInt(edad) >= 29 && parseInt(edad) <= 59) {
                    this.operadores.ciclo_vida = 'Adultez';
                } else if (parseInt(edad) >= 60) {
                    this.operadores.ciclo_vida = 'Vejez';
                }
            },
            mediaIMC() {
                let imcTOTAL = this.MiImc
                if (imcTOTAL < 16.0) {
                    this.operadores.clasificacion = "Delgadez severa";
                } else if (imcTOTAL > 16.0 && imcTOTAL < 16.99) {
                    this.operadores.clasificacion = "Delgadez moderada";
                } else if (imcTOTAL > 17.0 && imcTOTAL < 18.49) {
                    this.operadores.clasificacion = "Delgadez aceptable";
                } else if (imcTOTAL > 18.5 && imcTOTAL < 24.99) {
                    this.operadores.clasificacion = "Normal";
                } else if (imcTOTAL > 25.0 && imcTOTAL < 29.99) {
                    this.operadores.clasificacion = "Pre-obeso";
                } else if (imcTOTAL > 30.0 && imcTOTAL < 34.99) {
                    this.operadores.clasificacion = "Obeso tipo I (riesgo moderado)";
                } else if (imcTOTAL > 35.0 && imcTOTAL < 39.99) {
                    this.operadores.clasificacion = "Obeso tipo II (riesgo severo)";
                } else if (imcTOTAL >= 40.0) {
                    this.operadores.clasificacion = "Obeso tipo III (riesgo muy severo)";
                }
            },

            resultadoIMC() {
                let imcTOTAL = this.MiImc
                if (imcTOTAL < 16.0) {
                    this.resultado.clasificacion = "Riesgo Muy severo";
                } else if (imcTOTAL > 16.0 && imcTOTAL < 16.99) {
                    this.resultado.clasificacion = "Riesgo Severo";
                } else if (imcTOTAL > 17.0 && imcTOTAL < 18.49) {
                    this.resultado.clasificacion = "Riesgo Moderado";
                } else if (imcTOTAL > 18.5 && imcTOTAL < 24.99) {
                    this.resultado.clasificacion = "Riesgo Promedio";
                } else if (imcTOTAL > 25.0 && imcTOTAL < 29.99) {
                    this.resultado.clasificacion = "Riesgo Aumentado";
                } else if (imcTOTAL > 30.0 && imcTOTAL < 34.99) {
                    this.resultado.clasificacion = "Riesgo Moderado";
                } else if (imcTOTAL > 35.0 && imcTOTAL < 39.99) {
                    this.resultado.clasificacion = "Riesgo Severo";
                } else if (imcTOTAL >= 40.0) {
                    this.resultado.clasificacion = "Riesgo Muy Severo";
                }
            },

            resultadoLDL() {
                let ldlTOTAL = this.MiLdl
                if (ldlTOTAL > 0 && ldlTOTAL < 100.0) {
                    this.resultado.clasificacionLDL = "Óptimo";
                } else if (ldlTOTAL > 100.0 && ldlTOTAL < 129.99) {
                    this.resultado.clasificacionLDL = "Casi óptimo / Poco mejor que óptimo";
                } else if (ldlTOTAL > 130.0 && ldlTOTAL < 159.99) {
                    this.resultado.clasificacionLDL = "Límite alto";
                } else if (ldlTOTAL > 160.0 && ldlTOTAL < 189.99) {
                    this.resultado.clasificacionLDL = "Alto";
                } else if (ldlTOTAL >= 190.0) {
                    this.resultado.clasificacionLDL = "Muy Alto";
                }
            },

            validacionPaciente() {
                if (this.operadores.examen_colesterol == 'Si') {
                    if (!this.operadores.total_colesterol) {
                        this.$alerError('El campo de colesterol total no puede estar vacio')
                        return
                    }
                    if (!this.operadores.trigliceridos) {
                        this.$alerError('El campo de trigliceridos no puede estar vacio')
                        return
                    }
                    if (!this.operadores.colesterol_hdl) {
                        this.$alerError('El campo de colesterol HDL no puede estar vacio')
                        return
                    }
                } else if (!this.operadores.talla) {
                    this.$alerError('El campo talla no puede estar vacio')
                    return
                } else if (!this.operadores.cedula) {
                    this.$alerError('El campo cedula no puede estar vacio')
                    return
                } else if (!this.operadores.presion_sistolica) {
                    this.$alerError('El campo presión sistólica no puede estar vacio')
                    return
                } else if (!this.operadores.presion_diastolica) {
                    this.$alerError('El campo presión diastólica no puede estar vacio')
                    return
                } else if ((this.operadores.presion_diastolica <= 49)) {
                    if (this.operadores.presion_diastolica >= 121) {
                        this.$alerError('El campo presión diastólica debe ser mayor a 50  y menor a 120')
                        return
                    }
                    this.$alerError('El campo presión diastólica debe ser mayor a 50  y menor a 120')
                    return
                } else if (this.operadores.presion_sistolica <= 89) {
                    this.$alerError('El campo presión sistólica debe ser mayor a 90 y menor a 220')
                    if (this.operadores.presion_sistolica >= 221) {
                        this.$alerError('El campo presión sistólica debe ser mayor a 90 y menor a 220')
                        return
                    }
                    return
                }
                this.realizarDiagnostico()
            },

            realizarDiagnostico() {
                this.operadores.imc = this.MiImc;
                axios.post('/api/miDaignostico', this.operadores)
                    .then(res => {
                        this.$alerSuccess(res.data.mensaje);
                        this.resultadoIMC();
                        this.resultadoLDL();
                        this.dialog = true;
                        this.MiImc();
                        this.mediaIMC()
                        this.MiLdl()
                    })
                    .catch(err => {
                        console.error(err);
                    })
            },
            limpiarCampos() {
                for (const key in this.operadores) {
                    this.operadores[key] = '';
                }
            }
        },
    };

</script>

<style lang="scss" scoped>
    * {
        padding: 0;
        margin: 0;
        color: #1a1f36;
        box-sizing: border-box;
        word-wrap: break-word;
        font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Ubuntu, sans-serif;
    }

    body {
        min-height: 100%;
        background-color: #ffffff;
    }

    h1 {
        letter-spacing: -1px;
    }

    a {
        color: #5469d4;
        text-decoration: unset;
    }

    .login-root {
        background: #fff;
        display: flex;
        width: 100%;
        min-height: 100vh;
        overflow: hidden;
    }

    .loginbackground {
        min-height: 692px;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        top: 0;
        z-index: 0;
        overflow: hidden;
    }

    .flex-flex {
        display: flex;
    }

    .align-center {
        align-items: center;
    }

    .center-center {
        align-items: center;
        justify-content: center;
    }

    .box-root {
        box-sizing: border-box;
    }

    .flex-direction--column {
        -ms-flex-direction: column;
        flex-direction: column;
    }

    .loginbackground-gridContainer {
        display: -ms-grid;
        display: grid;
        -ms-grid-columns: [start] 1fr [left-gutter] (86.6px)[16] [left-gutter] 1fr [end];
        grid-template-columns: [start] 1fr [left-gutter] repeat(16, 86.6px) [left-gutter] 1fr [end];
        -ms-grid-rows: [top] 1fr [top-gutter] (64px)[8] [bottom-gutter] 1fr [bottom];
        grid-template-rows: [top] 1fr [top-gutter] repeat(8, 64px) [bottom-gutter] 1fr [bottom];
        justify-content: center;
        margin: 0 -2%;
        transform: rotate(-12deg) skew(-12deg);
    }

    .box-divider--light-all-2 {
        box-shadow: inset 0 0 0 2px #e3e8ee;
    }

    .box-background--blue {
        background-color: #5469d4;
    }

    .box-background--white {
        background-color: #ffffff;
    }

    .box-background--blue800 {
        background-color: #212d63;
    }

    .box-background--gray100 {
        background-color: #e3e8ee;
    }

    .box-background--cyan200 {
        background-color: #7fd3ed;
    }

    .padding-top--64 {
        padding-top: 64px;
    }

    .padding-top--24 {
        padding-top: 24px;
    }

    .padding-top--48 {
        padding-top: 48px;
    }

    .padding-bottom--24 {
        padding-bottom: 24px;
    }

    .padding-horizontal--48 {
        padding: 48px;
    }

    .padding-bottom--15 {
        padding-bottom: 15px;
    }


    .flex-justifyContent--center {
        -ms-flex-pack: center;
        justify-content: center;
    }

    .formbg {
        margin: 0px auto;
        width: 100%;
        max-width: 448px;
        background: white;
        border-radius: 4px;
        box-shadow: rgba(60, 66, 87, 0.12) 0px 7px 14px 0px, rgba(0, 0, 0, 0.12) 0px 3px 6px 0px;
    }

    span {
        display: block;
        font-size: 20px;
        line-height: 28px;
        color: #1a1f36;
    }

    label {
        margin-bottom: 10px;
    }

    .reset-pass a,
    label {
        font-size: 14px;
        font-weight: 600;
        display: block;
    }

    .reset-pass>a {
        text-align: right;
        margin-bottom: 10px;
    }

    .grid--50-50 {
        display: grid;
        grid-template-columns: 50% 50%;
        align-items: center;
    }

    .field input {
        font-size: 16px;
        line-height: 28px;
        padding: 8px 16px;
        width: 100%;
        min-height: 44px;
        border: unset;
        border-radius: 4px;
        outline-color: rgb(84 105 212 / 0.5);
        background-color: rgb(255, 255, 255);
        box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px,
            rgba(0, 0, 0, 0) 0px 0px 0px 0px,
            rgba(0, 0, 0, 0) 0px 0px 0px 0px,
            rgba(60, 66, 87, 0.16) 0px 0px 0px 1px,
            rgba(0, 0, 0, 0) 0px 0px 0px 0px,
            rgba(0, 0, 0, 0) 0px 0px 0px 0px,
            rgba(0, 0, 0, 0) 0px 0px 0px 0px;
    }

    input[type="submit"] {
        background-color: rgb(84, 105, 212);
        box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px,
            rgba(0, 0, 0, 0) 0px 0px 0px 0px,
            rgba(0, 0, 0, 0.12) 0px 1px 1px 0px,
            rgb(84, 105, 212) 0px 0px 0px 1px,
            rgba(0, 0, 0, 0) 0px 0px 0px 0px,
            rgba(0, 0, 0, 0) 0px 0px 0px 0px,
            rgba(60, 66, 87, 0.08) 0px 2px 5px 0px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
    }

    .field-checkbox input {
        width: 20px;
        height: 15px;
        margin-right: 5px;
        box-shadow: unset;
        min-height: unset;
    }

    .field-checkbox label {
        display: flex;
        align-items: center;
        margin: 0;
    }

    a.ssolink {
        display: block;
        text-align: center;
        font-weight: 600;
    }

    .footer-link span {
        font-size: 14px;
        text-align: center;
    }

    .listing a {
        color: #697386;
        font-weight: 600;
        margin: 0 10px;
    }

    .animationRightLeft {
        animation: animationRightLeft 2s ease-in-out infinite;
    }

    .animationLeftRight {
        animation: animationLeftRight 2s ease-in-out infinite;
    }

    .tans3s {
        animation: animationLeftRight 3s ease-in-out infinite;
    }

    .tans4s {
        animation: animationLeftRight 4s ease-in-out infinite;
    }

    @keyframes animationLeftRight {
        0% {
            transform: translateX(0px);
        }

        50% {
            transform: translateX(1000px);
        }

        100% {
            transform: translateX(0px);
        }
    }

    @keyframes animationRightLeft {
        0% {
            transform: translateX(0px);
        }

        50% {
            transform: translateX(-1000px);
        }

        100% {
            transform: translateX(0px);
        }
    }

</style>
