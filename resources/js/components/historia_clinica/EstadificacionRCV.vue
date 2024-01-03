<template>
    <div>
        <form>
            <v-card color="lighten-1" class="mb-5" height="auto">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">
                        Paraclinicos </span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Resultado creatinina" v-model="paraclinicos.resultadoCreatinina"
                            type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultima creatinina" v-model="paraclinicos.ultimaCreatinina">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Resultado ultima hemoglobina glicosidada"
                            v-model="paraclinicos.resultaGlicosidada" type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultima hemoglobina glicosidada"
                            v-model="paraclinicos.fechaGlicosidada">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Resultado ultima albuminuria" v-model="paraclinicos.resultadoAlbuminuria"
                            type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultima albuminuria" v-model="paraclinicos.fechaAlbuminuria">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Resultado ultimo colesterol total"
                            v-model="paraclinicos.resultadoColesterol" type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultimo colesterol total" v-model="paraclinicos.fechaColesterol">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Resultado ultimo HDL" v-model="paraclinicos.resultadoHdl" type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultimo HDL" v-model="paraclinicos.fechaHdl">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Resultado ultimo LDL" v-model="paraclinicos.resultadoLdl" type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultimo LDL" v-model="paraclinicos.fechaLdl">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Resultado ultimo trigliceridos"
                            v-model="paraclinicos.resultadoTrigliceridos" type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultimo trigliceridos" v-model="paraclinicos.fechaTrigliceridos">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Resultado ultima glicemia serica" v-model="paraclinicos.resultadoGlicemia"
                            type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultima glicemia serica " v-model="paraclinicos.fechaGlicemia">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Resultado ultima PHT" v-model="paraclinicos.resultadoPht" type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultima PHT " v-model="paraclinicos.fechaPht">
                        </v-text-field>
                    </v-flex>
                    <!-- <v-flex xs12 sm6 md4>
                        <v-text-field label="Resultado Hemoglobina" v-model="paraclinicos.resultadoHemoglobina"
                            type="number">
                        </v-text-field>
                    </v-flex> -->
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Albúmina sérica" v-model="paraclinicos.albumina" type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha albúmina sérica " v-model="paraclinicos.fechaAlbumina">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fosforo" v-model="paraclinicos.fosforo" type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultimo fosforo " v-model="paraclinicos.fechaFosforo">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select label="Resultado de EKG" v-model="paraclinicos.resultadoEkg"
                            :items="['Normal','Anormal']">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultimo EKG" v-model="paraclinicos.fechaEkg">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Tasa de filtración glomerular (COCKCROFT - GAULT)"
                            v-model="paraclinicos.glomerular" type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Fecha ultima tasa de filtración glomerular"
                            v-model="paraclinicos.fechaGlomerular">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-checkbox color="success" v-model="paraclinicos.checkboxParaclinicos" value="1"
                            label="Agregar paraclinico">
                        </v-checkbox>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field v-model="paraclinicos.nombreParaclinico"
                            v-if="paraclinicos.checkboxParaclinicos == true" label="Nombre paraclinico">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field type="date" v-model="paraclinicos.fechaParaclinico"
                            v-if="paraclinicos.checkboxParaclinicos == true" label="Fecha">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field type="number" v-model="paraclinicos.resultadoParaclinico"
                            v-if="paraclinicos.checkboxParaclinicos == true" label="Resultado">
                        </v-text-field>
                    </v-flex>
                    <v-flex class="text-xs-center" v-if="paraclinicos.glomerular < 15  && paraclinicos.glomerular > 1 ">
                        <v-chip color="red" text-color="white">Estadio 5</v-chip>
                    </v-flex>
                    <v-flex class="text-xs-center"
                        v-if="paraclinicos.glomerular >= 15  && paraclinicos.glomerular <= 29 ">
                        <v-chip color="red" text-color="white">Estadio 4</v-chip>
                    </v-flex>
                    <v-flex class="text-xs-center"
                        v-if="paraclinicos.glomerular >= 30  && paraclinicos.glomerular <= 44 ">
                        <v-chip color="orange" text-color="white">Estadio 3b</v-chip>
                    </v-flex>
                    <v-flex class="text-xs-center"
                        v-if="paraclinicos.glomerular >= 45  && paraclinicos.glomerular <= 59 ">
                        <v-chip color="yellow" text-color="white">Estadio 3a</v-chip>
                    </v-flex>
                    <v-flex class="text-xs-center"
                        v-if="paraclinicos.glomerular >= 60  && paraclinicos.glomerular <= 89 ">
                        <v-chip color="yellow" text-color="white">Estadio 2</v-chip>
                    </v-flex>
                    <v-flex class="text-xs-center" v-if="paraclinicos.glomerular >= 90 ">
                        <v-chip color="yellow" text-color="white">Estadio 1</v-chip>
                    </v-flex>
                    <v-flex class="text-xs-center" xs12 sm12 md12>
                        <v-btn color="success" round @click="saveParaclinicos()">Guardar paraclinicos</v-btn>
                    </v-flex>

                </v-layout>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">
                        Estratificación Framingham </span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Estadificacion segun la edad" v-model="framingham.edad_puntaje"
                            type="number" readonly>
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Puntaje Colesterol Total vs Edad" v-model="framingham.colesterol_puntaje"
                            type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Puntaje Colesterol HDL" v-model="framingham.colesterol_puntajehdl"
                            type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Puntaje Fumador" v-model="framingham.fumador_puntaje" type="number"
                            readonly>
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select v-model="framingham.tratamiento" :items="['Si','No']"
                            label="¿Tiene tratamiento con medicamentos?">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Puntaje Tension Arterial" v-model="framingham.arterial_puntaje"
                            type="number" readonly>
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm12 md6>
                        <v-text-field label="Total puntos" v-model="framingham.totales" type="number" readonly>
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm12 md6>
                        <v-text-field label="Porccentaje riesgo a 10 años" v-model="framingham.porcentaje" readonly>
                        </v-text-field>
                    </v-flex>
                </v-layout>
                <v-card-actions class="px-3 pb-3">
                    <v-flex class="text-xs-center" v-if="framingham.totales < 17 && datosCita.sexo == 'F'">
                        <v-chip color="green" text-color="white">Estado: Riesgo Bajo(1)</v-chip>
                        <p style="color:white;background-color:#4caf50"> usted tiene un riesgo bajo, lo cual no
                            significa “ausencia de riesgo”. Lo
                            importante es asumir y mantener hábitos y estilos de vida saludables para evitar que con el
                            transcurrir de los años usted pueda sufrir un infarto o una trombosis.</p>
                    </v-flex>
                    <v-flex class="text-xs-center"
                        v-if="framingham.totales >= 17 && framingham.totales <= 19 && datosCita.sexo == 'F'">
                        <v-chip color="yellow" text-color="white">Estado: Riesgo ligero(2)</v-chip>
                        <p style="color:white;background-color:#ffeb3b"> usted tiene un riesgo ligero, lo cual no
                            significa “ausencia de riesgo”. Lo
                            importante es asumir y mantener hábitos y estilos de vida saludables como evitar el tabaco y
                            alcohol, alimentación saludable, controlar su peso y realizar actividad física para evitar
                            que con el
                            transcurrir de los años usted pueda sufrir un infarto o una trombosis.</p>
                    </v-flex>
                    <v-flex class="text-xs-center"
                        v-if="framingham.totales >= 20 && framingham.totales <= 22 && datosCita.sexo == 'F'">
                        <v-chip color="orange" text-color="white">Estado: Riesgo moderado(3)</v-chip>
                        <p style="color:white;background-color:#ff9800"> Usted tiene un riesgo moderado de sufrir un
                            infarto o una trombosis
                            u otro evento cardiovascular en los próximos 10 años por lo cual se recomienda: monitorear
                            su
                            perfil de riesgo cada año, acudir a control médico durante los siguientes seis meses, asumir
                            hábitos y estilos de vida saludables.</p>
                    </v-flex>
                    <v-flex class="text-xs-center"
                        v-if="framingham.totales >= 23 && framingham.totales <= 24 && datosCita.sexo == 'F'">
                        <v-chip color="red" text-color="white">Estado: Riesgo alto(4)</v-chip>
                        <p style="color:white;background-color:red"> Usted tiene un riesgo alto de sufrir un infarto o
                            una trombosis u otro evento
                            cardiovascular en los próximos 10 años. Recomendamos: Monitorear su perfil de riesgo cada
                            seis
                            meses, acudir a control médico durante los siguientes tres meses, asumir hábitos y estilos
                            de vida
                            saludables.</p>
                    </v-flex>
                    <v-flex class="text-xs-center" v-if="framingham.totales >= 25 && datosCita.sexo == 'F'">
                        <v-chip color="red" text-color="white">Estado: Riesgo muy alto(5)</v-chip>
                        <p style="color:white;background-color:red"> Usted tiene un riesgo muy alto de sufrir un infarto
                            o una trombosis en los
                            próximos 10 años. Recomendamos: Monitorear su perfil de riesgo cada seis meses. Acudir a
                            control médico durante los siguientes tres meses. Asumir hábitos y estilos de vida
                            saludables.</p>
                    </v-flex>
                    <v-flex class="text-xs-center"
                        v-if="framingham.colesterol_puntajehdl == null && framingham.colesterol_puntajehdl == null">
                        <v-chip color="grey" text-color="white">Estado: Riesgo sin classificar(6)</v-chip>
                    </v-flex>
                    <v-flex class="text-xs-center" v-if="framingham.totales < 9  && datosCita.sexo == 'M'">
                        <v-chip color="green" text-color="white">Estado: Riesgo Bajo(1)</v-chip>
                        <p style="color:white;background-color:#4caf50"> usted tiene un riesgo bajo, lo cual no
                            significa “ausencia de riesgo”. Lo
                            importante es asumir y mantener hábitos y estilos de vida saludables para evitar que con el
                            transcurrir de los años usted pueda sufrir un infarto o una trombosis.</p>
                    </v-flex>
                    <v-flex class="text-xs-center"
                        v-if="framingham.totales >= 9 && framingham.totales <= 11 && datosCita.sexo == 'M'">
                        <v-chip color="yellow" text-color="white">Estado: Riesgo ligero(2)</v-chip>
                        <p style="color:white;background-color:#ffeb3b"> usted tiene un riesgo ligero, lo cual no
                            significa “ausencia de riesgo”. Lo
                            importante es asumir y mantener hábitos y estilos de vida saludables como evitar el tabaco y
                            alcohol, alimentación saludable, controlar su peso y realizar actividad física para evitar
                            que con el
                            transcurrir de los años usted pueda sufrir un infarto o una trombosis.</p>
                    </v-flex>
                    <v-flex class="text-xs-center"
                        v-if="framingham.totales >= 12 && framingham.totales < 15 && datosCita.sexo == 'M'">
                        <v-chip color="orange" text-color="white">Estado: Riesgo moderado(3)</v-chip>
                        <p style="color:white;background-color:#ff9800"> Usted tiene un riesgo moderado de sufrir un
                            infarto o una trombosis
                            u otro evento cardiovascular en los próximos 10 años por lo cual se recomienda: monitorear
                            su
                            perfil de riesgo cada año, acudir a control médico durante los siguientes seis meses, asumir
                            hábitos y estilos de vida saludables.</p>
                    </v-flex>
                    <v-flex class="text-xs-center"
                        v-if="framingham.totales >= 15 && framingham.totales <= 16 && datosCita.sexo == 'M'">
                        <v-chip color="red" text-color="white">Estado: Riesgo alto(4)</v-chip>
                        <p style="color:white;background-color:red"> Usted tiene un riesgo alto de sufrir un infarto o
                            una trombosis u otro evento
                            cardiovascular en los próximos 10 años. Recomendamos: Monitorear su perfil de riesgo cada
                            seis
                            meses, acudir a control médico durante los siguientes tres meses, asumir hábitos y estilos
                            de vida
                            saludables.</p>
                    </v-flex>
                    <v-flex class="text-xs-center" v-if="framingham.totales >= 17 && datosCita.sexo == 'M'">
                        <v-chip color="red" text-color="white">Estado: Riesgo muy alto(5)</v-chip>
                        <p style="color:white;background-color:red"> Usted tiene un riesgo muy alto de sufrir un infarto
                            o una trombosis en los
                            próximos 10 años. Recomendamos: Monitorear su perfil de riesgo cada seis meses. Acudir a
                            control médico durante los siguientes tres meses. Asumir hábitos y estilos de vida
                            saludables.</p>
                    </v-flex>
                </v-card-actions>
                <v-flex class="text-xs-center" xs12 sm12 md12>
                    <v-btn color="success" round @click="saveFramingham()">Guardar Estratificación Framingham</v-btn>
                </v-flex>

                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">
                        Test de Findrisk </span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Estadificacion segun la edad" v-model="findrisk.edad_puntaje" type="number"
                            readonly>
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Resultado índice masa corporal" v-model="findrisk.indice_corporal"
                            type="number" readonly>
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Perímetro de la cintura medido debajo de las costillas "
                            v-model="findrisk.perimetro_abdominal" type="number" readonly>
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-select label="¿Practica 30 minutos diarios de actividad física?"
                            v-model="findrisk.actividad_fisica" :items="['Si','No']" type="number">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Puntaje actividad física" v-model="findrisk.puntaje_fisica " readonly
                            type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Puntaje consumo de frutas y verduras" v-model="findrisk.frutas_verduras"
                            type="number" readonly>
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md7>
                        <v-select v-model="findrisk.hipertension" :items="['Si','No']"
                            label="¿Ha tomado medicación para la hipertensión con regularidad?">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md5>
                        <v-text-field label="Puntaje medicación para la hipertensión"
                            v-model="findrisk.resultado_hipertension" readonly type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md7>
                        <v-select v-model="findrisk.glucosa" :items="['Si','No']"
                            label="¿Le han encontrado alguna vez niveles altos de glucosa en sangre?">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md5>
                        <v-text-field label="Puntaje glucosa en la sangre" v-model="findrisk.resultado_glucosa" readonly
                            type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md8>
                        <v-select v-model="findrisk.diabetes" :items="['Si','No']"
                            label="¿A algún miembro de su familia le han diagnosticado diabetes (tipo 1 ó 2)?">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md8 v-if="findrisk.diabetes == 'Si'">
                        <v-select v-model="findrisk.parentezco"
                            :items="['Padres','Hermanos','Hijos','Abuelos','Tíos','Primos hermanos']"
                            label="Parentesco">
                        </v-select>
                    </v-flex>
                    <v-flex xs12 sm6 md4>
                        <v-text-field label="Puntaje diagnostico diabetes en la familia"
                            v-model="findrisk.resultado_diabetes" readonly type="number">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md12>
                        <v-text-field label="Puntaje de riesgo total" v-model="findrisk.totales" type="number" readonly>
                        </v-text-field>
                    </v-flex>
                </v-layout>
                <v-card-actions class="px-3 pb-3">
                    <v-flex class="text-xs-center" v-if="findrisk.totales < 10">
                        <v-chip color="green" text-color="white">Riesgo Bajo</v-chip>
                        <p style="color:white;background-color:#4caf50"> Felicitaciones, Usted tiene un riesgo bajo para
                            desarrollar
                            diabetes. Le sugerimos que controle su peso regularmente, consuma frutas y verduras
                            diariamente y practique al menos 30 minutos de actividad física con intensidad moderada por
                            día, para así mantener y mejorar su salud.</p>

                    </v-flex>
                    <v-flex class="text-xs-center" v-if="findrisk.totales >= 10 && findrisk.totales <= 12">
                        <v-chip color="yellow" text-color="black">Riesgo medio</v-chip>
                        <p style="color:black;background-color:#ffeb3b"> Sugerimos que considere aumentar su actividad
                            física, mejorar sus hábitos de alimentación y
                            prestar atención a su peso, para prevenir el desarrollo de diabetes. Los cambios en su
                            estilo de
                            vida pueden prevenir completamente la diabetes o al menos retrasar su inicio hasta edades ya
                            muy avanzadas</p>
                    </v-flex>
                    <v-flex class="text-xs-center" v-if="findrisk.totales > 12 ">
                        <v-chip color="orange" text-color="white">Riesgo alto: </v-chip>
                        <p style="color:white;background-color:#ff9800">
                            Recomendamos cita de control con su médico de familia para realizar pruebas de tamizajes en
                            diabetes y además brindarle
                            educación sobre cómo cambiar sus hábitos de vida para mejorar su salud y riesgo de presentar
                            diabetes.
                        </p>

                    </v-flex>
                </v-card-actions>
                <v-flex class="text-xs-center" xs12 sm12 md12>
                    <v-btn color="success" round @click="savefindrisk()">Guardar Estratificación Findrisk</v-btn>
                </v-flex>
            </v-card>
            <v-dialog v-model="preload_datos" persistent width="300">
                <v-card color="primary" dark>
                    <v-card-text>
                        Estamos procesando su información
                        <v-progress-linear indeterminate color="white" class="mb-0">
                        </v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </form>
        <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
    </div>
</template>
<script>
    import moment from 'moment';
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        data() {
            return {
                preload_datos: false,
                framingham: {
                    edad_puntaje: '',
                    colesterol_total: '',
                    colesterol_puntaje: '',
                    colesterol_hdl: '',
                    colesterol_puntajehdl: '',
                    fumador_puntaje: '',
                    tratamiento: '',
                    arterial_puntaje: '',
                    diabetes_puntaje: '',
                    totales: '',
                    porcentaje: ''
                },
                findrisk: {
                    edad_puntaje: '',
                    indice_corporal: '',
                    perimetro_abdominal: '',
                    actividad_fisica: '',
                    puntaje_fisica: '',
                    frutas_verduras: '',
                    hipertension: '',
                    resultado_hipertension: '',
                    glucosa: '',
                    resultado_glucosa: '',
                    diabetes: '',
                    parentezco: '',
                    resultado_diabetes: '',
                    totales: '',
                },
                paraclinicos: {
                    ultimaCreatinina: '',
                    resultadoCreatinina: '',
                    resultaGlicosidada: '',
                    fechaGlicosidada: '',
                    resultadoAlbuminuria: '',
                    fechaAlbuminuria: '',
                    relacionAlbuminuria: '',
                    fechaAlbuminuriaCreatinuria: '',
                    resultadoColesterol: '',
                    fechaColesterol: '',
                    resultadoHdl: '',
                    fechaHdl: '',
                    resultadoTrigliceridos: '',
                    fechaTrigliceridos: '',
                    resultadoGlicemia: '',
                    fechaGlicemia: '',
                    resultadoPht: '',
                    fechaPht: '',
                    resultadoHemoglobina: '',
                    albumina: '',
                    fosforo: '',
                    glomerular: '',
                    fechaGlomerular: '',
                    resultadoEkg: '',
                    fechaEkg: '',
                    fechaAlbumina: '',
                    fechaFosforo: '',
                    resultadoLdl: '',
                    fechaLdl: '',
                    checkboxParaclinicos: '',
                    nombreParaclinico: '',
                    fechaParaclinico: '',
                    resultadoParaclinico: ''
                },
                dataPaciente: {},
                dataParaclinicos: [],
                fetchData: []
            }
        },
        watch: {
            "framingham.fumador_puntaje": function () {
                this.sumaTotal();
            },
            "framingham.colesterol_puntaje": function () {
                this.colesterol();
                this.sumaTotal();
            },
            "framingham.colesterol_puntajehdl": function () {
                this.calcularColesterolhdl();
                this.sumaTotal();
            },
            "framingham.tratamiento": function () {
                this.arterialPuntaje();
                this.sumaTotal();
            },
            "findrisk.actividad_fisica": function () {
                this.actividadfisica();
                this.sumTotoalF();
            },
            "findrisk.hipertension": function () {
                this.hipertension();
                this.sumTotoalF();
            },
            "findrisk.glucosa": function () {
                this.glucosa();
                this.sumTotoalF();
            },
            "findrisk.diabetes": function () {
                this.diabetes();
                this.sumTotoalF();
            },
            "findrisk.parentezco": function () {
                this.diabetes();
                this.sumTotoalF();
            },
            "findrisk.indice_corporal": function () {
                this.imc();
                this.sumTotoalF();
            },
            "findrisk.perimetro_abdominal": function () {
                this.perimetroabdominal();
                this.sumTotoalF();
            },
            "framingham.porcentaje": function () {
                this.sumaTotal();
                this.porcentaje();
            },
            "paraclinicos.resultadoLdl": function () {
                this.ldl();
            },

            "paraclinicos.glomerular": function () {
                this.glomerular();
            }

        },
        created() {
            this.datosPacientes();
            this.datosParaclinicos();
            this.fetchFramingham();
            this.fetchFindrisk();
        },
        methods: {
            datosPacientes() {
                this.preload_datos = true
                axios.get('/api/historia/datosPacientes/' + this.datosCita.cita_paciente_id)
                    .then(res => {
                        this.dataPaciente = res.data;
                        this.preload_datos = false
                    })
            },

            datosParaclinicos() {
                this.preload_datos = true
                axios.get('/api/historia/datosParaclinicos/' + this.datosCita.paciente_id)
                    .then(res => {
                        this.dataParaclinicos = res.data;
                        this.mostrarParaclinicos();
                        this.fetchDataParaclinicos();
                        this.ldl();
                        this.glomerular();
                        this.preload_datos = false
                    })
            },

            fetchDataParaclinicos() {
                axios.get('/api/historia/fetchDataParaclinicos/' + this.datosCita.cita_paciente_id)
                    .then(res => {
                        this.fetchData = res.data;
                    })
            },

            mostrarParaclinicos() {
                if (this.dataParaclinicos.creatinina == null) {
                    this.paraclinicos.resultadoCreatinina = 0
                } else {

                    this.paraclinicos.resultadoCreatinina = this.dataParaclinicos.creatinina.resultadoCreatinina
                    this.paraclinicos.ultimaCreatinina = this.dataParaclinicos.creatinina.ultimaCreatinina

                }

                if (this.dataParaclinicos.hemoglobina == null) {
                    this.paraclinicos.resultaGlicosidada = 0
                } else {
                    this.paraclinicos.resultaGlicosidada = this.dataParaclinicos.hemoglobina.resultaGlicosidada
                    this.paraclinicos.fechaGlicosidada = this.dataParaclinicos.hemoglobina.fechaGlicosidada
                }

                if (this.dataParaclinicos.albuminuria == null) {
                    this.paraclinicos.resultadoAlbuminuria = 0
                } else {
                    this.paraclinicos.resultadoAlbuminuria = this.dataParaclinicos.albuminuria.resultadoAlbuminuria
                    this.paraclinicos.fechaAlbuminuria = this.dataParaclinicos.albuminuria.fechaAlbuminuria
                }

                if (this.dataParaclinicos.colesterolTotal == null) {
                    this.paraclinicos.resultadoColesterol = 0
                    // this.colesterol();
                } else {
                    this.paraclinicos.resultadoColesterol = this.dataParaclinicos.colesterolTotal.resultadoColesterol
                    this.paraclinicos.fechaColesterol = this.dataParaclinicos.colesterolTotal.fechaColesterol
                    // this.colesterol();
                }

                if (this.dataParaclinicos.hdl == null) {
                    this.paraclinicos.resultadoHdl = 0
                    // this.calcularColesterolhdl();
                } else {
                    this.paraclinicos.resultadoHdl = this.dataParaclinicos.hdl.resultadoHdl
                    this.paraclinicos.fechaHdl = this.dataParaclinicos.hdl.fechaHdl
                    // this.calcularColesterolhdl();
                }


                if (this.dataParaclinicos.trigliceridos == null) {
                    this.paraclinicos.resultadoTrigliceridos = 0
                } else {
                    this.paraclinicos.resultadoTrigliceridos = this.dataParaclinicos.trigliceridos
                        .resultadoTrigliceridos
                    this.paraclinicos.fechaTrigliceridos = this.dataParaclinicos.trigliceridos.fechaTrigliceridos
                }

                if (this.dataParaclinicos.glicemia == null) {
                    this.paraclinicos.resultadoGlicemia = 0
                } else {
                    this.paraclinicos.resultadoGlicemia = this.dataParaclinicos.glicemia.resultadoGlicemia
                    this.paraclinicos.fechaGlicemia = this.dataParaclinicos.glicemia.fechaGlicemia
                }

                if (this.dataParaclinicos.pht == null) {
                    this.paraclinicos.resultadoPht = 0
                } else {
                    this.paraclinicos.resultadoPht = this.dataParaclinicos.pht.resultadoPht
                    this.paraclinicos.fechaPht = this.dataParaclinicos.pht.fechaPht
                }

                if (this.dataParaclinicos.albumina == null) {
                    this.paraclinicos.albumina = 0
                } else {
                    this.paraclinicos.albumina = this.dataParaclinicos.albumina.albumina
                    this.paraclinicos.fechaAlbumina = this.dataParaclinicos.albumina.fechaAlbumina
                }

                if (this.dataParaclinicos.fosforo == null) {
                    this.paraclinicos.fosforo = 0
                } else {
                    this.paraclinicos.fosforo = this.dataParaclinicos.fosforo.fosforo
                    this.paraclinicos.fechaFosforo = this.dataParaclinicos.fosforo.fechaFosforo
                }

            },

            ldl() {
                if (this.dataParaclinicos.colesterolTotal == null || this.dataParaclinicos.hdl == null || this
                    .dataParaclinicos.trigliceridos == null) {
                    this.paraclinicos.resultadoLdl = 0
                } else {
                    if (this.dataParaclinicos.trigliceridos.resultadoTrigliceridos < 400) {
                        this.paraclinicos.resultadoLdl = (this.paraclinicos.resultadoColesterol - (
                            this.paraclinicos.resultadoTrigliceridos / 5) - this.paraclinicos.resultadoHdl)
                        this.paraclinicos.fechaLdl = this.paraclinicos.fechaColesterol
                    } else {
                        this.paraclinicos.resultadoLdl = 0
                    }
                }
            },

            glomerular() {
                if (this.dataParaclinicos.creatinina == null || this.dataPaciente.peso == null) {
                    this.paraclinicos.glomerular = 0
                } else {
                    if (this.datosCita.sexo == 'F') {
                        let tasa = (((140 - this.datosCita.Edad_Cumplida) * (this.dataPaciente
                            .peso * 0.85)) / (72 * this.paraclinicos.resultadoCreatinina))
                        this.paraclinicos.glomerular = tasa.toFixed(2)
                        this.paraclinicos.fechaGlomerular = this.dataParaclinicos.creatinina.ultimaCreatinina
                    } else {
                        let tasa = (((140 - this.datosCita.Edad_Cumplida) * (this.dataPaciente
                            .peso)) / (72 * this.paraclinicos.resultadoCreatinina))
                        this.paraclinicos.glomerular = tasa.toFixed(2)
                        this.paraclinicos.fechaGlomerular = this.dataParaclinicos.creatinina.ultimaCreatinina
                    }
                }
            },

            saveParaclinicos() {
                this.paraclinicos.Citapaciente_id = this.datosCita.cita_paciente_id;
                this.paraclinicos.paciente_id = this.datosCita.paciente_id;

                if (this.dataParaclinicos.colesterolTotal) {
                    this.paraclinicos.idRegistoColesterol = this.dataParaclinicos.colesterolTotal.idRegistoColesterol
                }
                if (this.dataParaclinicos.hdl) {
                    this.paraclinicos.idHdl = this.dataParaclinicos.hdl.idHdl
                }
                if (this.dataParaclinicos.trigliceridos) {
                    this.paraclinicos.idTrigliceridos = this.dataParaclinicos.trigliceridos.idTrigliceridos
                }
                if (this.dataParaclinicos.creatinina) {
                    this.paraclinicos.idCreatina = this.dataParaclinicos.creatinina.idCreatina
                }
                if (this.dataParaclinicos.albuminuria) {
                    this.paraclinicos.idAlbuminuria = this.dataParaclinicos.albuminuria.idAlbuminuria
                }
                if (this.dataParaclinicos.hemoglobina) {
                    this.paraclinicos.idHemoglobina = this.dataParaclinicos.hemoglobina.idHemoglobina
                }
                if (this.dataParaclinicos.glicemia) {
                    this.paraclinicos.idGlicemia = this.dataParaclinicos.glicemia.idGlicemia
                }

                if (this.dataParaclinicos.pht) {
                    this.paraclinicos.idPht = this.dataParaclinicos.pht.idPht
                }

                if (this.dataParaclinicos.albumina) {
                    this.paraclinicos.idAlbumina = this.dataParaclinicos.albumina.idAlbumina
                }

                if (this.dataParaclinicos.fosforo) {
                    this.paraclinicos.idFosforo = this.dataParaclinicos.fosforo.idFosforo
                }
                axios.post('/api/historia/saveParaclinicos', this.paraclinicos)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchDataParaclinicos();
                        this.colesterol();
                        this.calcularColesterolhdl();
                        this.sumaTotal();
                        this.datosParaclinicos();
                    }).catch(err => {
                        this.setError(err.response.data)
                        this.$alerError(err.response.data.error)
                    })
            },

            saveFramingham() {
                this.framingham.Citapaciente_id = this.datosCita.cita_paciente_id;
                this.framingham.paciente_id = this.datosCita.paciente_id;
                axios.post('/api/historia/saveFramingham', this.framingham)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchFramingham();
                    }).catch(err => {
                        this.setError(err.response.data)
                        this.$alerError(err.response.data.error)
                    })
            },

            fetchFramingham() {
                axios.get(`/api/historia/fetchFramingham/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.framingham = res.data;
                        this.calcularPuntajeEdad();
                        this.habitoFumador();
                        this.arterialPuntaje();
                    });
            },

            calcularPuntajeEdad() {
                //mujeres
                if (this.datosCita.sexo == 'F') {
                    if (this.datosCita.Edad_Cumplida >= 20 && this.datosCita.Edad_Cumplida <= 34) {
                        this.framingham.edad_puntaje = -7
                    } else if (this.datosCita.Edad_Cumplida >= 35 && this.datosCita.Edad_Cumplida <= 39) {
                        this.framingham.edad_puntaje = -3
                    } else if (this.datosCita.Edad_Cumplida >= 40 && this.datosCita.Edad_Cumplida <= 44) {
                        this.framingham.edad_puntaje = 0
                    } else if (this.datosCita.Edad_Cumplida >= 45 && this.datosCita.Edad_Cumplida <= 49) {
                        this.framingham.edad_puntaje = 3
                    } else if (this.datosCita.Edad_Cumplida >= 50 && this.datosCita.Edad_Cumplida <= 54) {
                        this.framingham.edad_puntaje = 6
                    } else if (this.datosCita.Edad_Cumplida >= 55 && this.datosCita.Edad_Cumplida <= 59) {
                        this.framingham.edad_puntaje = 8
                    } else if (this.datosCita.Edad_Cumplida >= 60 && this.datosCita.Edad_Cumplida <= 64) {
                        this.framingham.edad_puntaje = 10
                    } else if (this.datosCita.Edad_Cumplida >= 65 && this.datosCita.Edad_Cumplida <= 69) {
                        this.framingham.edad_puntaje = 12
                    } else if (this.datosCita.Edad_Cumplida >= 70 && this.datosCita.Edad_Cumplida <= 74) {
                        this.framingham.edad_puntaje = 14
                    } else if (this.datosCita.Edad_Cumplida >= 75) {
                        this.framingham.edad_puntaje = 16
                    }
                } else {
                    //Hombres
                    if (this.datosCita.Edad_Cumplida >= 20 && this.datosCita.Edad_Cumplida <= 34) {
                        this.framingham.edad_puntaje = -9
                    } else if (this.datosCita.Edad_Cumplida >= 35 && this.datosCita.Edad_Cumplida <= 39) {
                        this.framingham.edad_puntaje = -4
                    } else if (this.datosCita.Edad_Cumplida >= 40 && this.datosCita.Edad_Cumplida <= 44) {
                        this.framingham.edad_puntaje = 0
                    } else if (this.datosCita.Edad_Cumplida >= 45 && this.datosCita.Edad_Cumplida <= 49) {
                        this.framingham.edad_puntaje = 3
                    } else if (this.datosCita.Edad_Cumplida >= 50 && this.datosCita.Edad_Cumplida <= 54) {
                        this.framingham.edad_puntaje = 6
                    } else if (this.datosCita.Edad_Cumplida >= 55 && this.datosCita.Edad_Cumplida <= 59) {
                        this.framingham.edad_puntaje = 8
                    } else if (this.datosCita.Edad_Cumplida >= 60 && this.datosCita.Edad_Cumplida <= 64) {
                        this.framingham.edad_puntaje = 10
                    } else if (this.datosCita.Edad_Cumplida >= 65 && this.datosCita.Edad_Cumplida <= 69) {
                        this.framingham.edad_puntaje = 11
                    } else if (this.datosCita.Edad_Cumplida >= 70 && this.datosCita.Edad_Cumplida <= 74) {
                        this.framingham.edad_puntaje = 12
                    } else if (this.datosCita.Edad_Cumplida >= 75) {
                        this.framingham.edad_puntaje = 13
                    }
                }
            },

            calcularColesterolhdl() {
                this.framingham.colesterol_hdl = parseFloat(this.paraclinicos.resultadoHdl)
                if (this.framingham.colesterol_hdl >= 60) {
                    this.framingham.colesterol_puntajehdl = -1
                } else if (this.framingham.colesterol_hdl >= 50 && this.framingham.colesterol_hdl <= 59) {
                    this.framingham.colesterol_puntajehdl = 0
                } else if (this.framingham.colesterol_hdl >= 40 && this.framingham.colesterol_hdl <= 49) {
                    this.framingham.colesterol_puntajehdl = 1
                } else if (this.framingham.colesterol_hdl >= 25 && this.framingham.colesterol_hdl < 40) {
                    this.framingham.colesterol_puntajehdl = 2
                }

            },

            colesterol() {
                this.framingham.colesterol_total = this.paraclinicos.resultadoColesterol
                if (this.datosCita.sexo == 'F') {
                    if (this.datosCita.Edad_Cumplida >= 20 && this.datosCita.Edad_Cumplida <= 39) {
                        if (this.framingham.colesterol_total > 0 && this.framingham.colesterol_total < 160) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 160 && this.framingham.colesterol_total <= 199) {
                            this.framingham.colesterol_puntaje = 4
                        } else if (this.framingham.colesterol_total >= 200 && this.framingham.colesterol_total <= 239) {
                            this.framingham.colesterol_puntaje = 8
                        } else if (this.framingham.colesterol_total >= 240 && this.framingham.colesterol_total <= 279) {
                            this.framingham.colesterol_puntaje = 11
                        } else if (this.framingham.colesterol_total >= 280) {
                            this.framingham.colesterol_puntaje = 13
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 40 && this.datosCita.Edad_Cumplida <= 49) {
                        if (this.framingham.colesterol_total < 160) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 160 && this.framingham.colesterol_total <= 199) {
                            this.framingham.colesterol_puntaje = 3
                        } else if (this.framingham.colesterol_total >= 200 && this.framingham.colesterol_total <= 239) {
                            this.framingham.colesterol_puntaje = 6
                        } else if (this.framingham.colesterol_total >= 240 && this.framingham.colesterol_total <= 279) {
                            this.framingham.colesterol_puntaje = 8
                        } else if (this.framingham.colesterol_total >= 280) {
                            this.framingham.colesterol_puntaje = 10
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 50 && this.datosCita.Edad_Cumplida <= 59) {
                        if (this.framingham.colesterol_total < 160) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 160 && this.framingham.colesterol_total <= 199) {
                            this.framingham.colesterol_puntaje = 2
                        } else if (this.framingham.colesterol_total >= 200 && this.framingham.colesterol_total <= 239) {
                            this.framingham.colesterol_puntaje = 4
                        } else if (this.framingham.colesterol_total >= 240 && this.framingham.colesterol_total <= 279) {
                            this.framingham.colesterol_puntaje = 5
                        } else if (this.framingham.colesterol_total >= 280) {
                            this.framingham.colesterol_puntaje = 7
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 60 && this.datosCita.Edad_Cumplida <= 69) {
                        if (this.framingham.colesterol_total < 160) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 160 && this.framingham.colesterol_total <= 199) {
                            this.framingham.colesterol_puntaje = 1
                        } else if (this.framingham.colesterol_total >= 200 && this.framingham.colesterol_total <= 239) {
                            this.framingham.colesterol_puntaje = 2
                        } else if (this.framingham.colesterol_total >= 240 && this.framingham.colesterol_total <= 279) {
                            this.framingham.colesterol_puntaje = 3
                        } else if (this.framingham.colesterol_total >= 280) {
                            this.framingham.colesterol_puntaje = 4
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 70) {
                        if (this.framingham.colesterol_total < 160) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 160 && this.framingham.colesterol_total <= 199) {
                            this.framingham.colesterol_puntaje = 1
                        } else if (this.framingham.colesterol_total >= 200 && this.framingham.colesterol_total <= 239) {
                            this.framingham.colesterol_puntaje = 1
                        } else if (this.framingham.colesterol_total >= 240 && this.framingham.colesterol_total <= 279) {
                            this.framingham.colesterol_puntaje = 2
                        } else if (this.framingham.colesterol_total >= 280) {
                            this.framingham.colesterol_puntaje = 2
                        }
                    }
                } else {
                    //Hombres
                    if (this.datosCita.Edad_Cumplida >= 20 && this.datosCita.Edad_Cumplida <= 39) {
                        if (this.framingham.colesterol_total > 0 && this.framingham.colesterol_total < 160) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 160 && this.framingham.colesterol_total <= 199) {
                            this.framingham.colesterol_puntaje = 4
                        } else if (this.framingham.colesterol_total >= 200 && this.framingham.colesterol_total <= 239) {
                            this.framingham.colesterol_puntaje = 7
                        } else if (this.framingham.colesterol_total >= 240 && this.framingham.colesterol_total <= 279) {
                            this.framingham.colesterol_puntaje = 9
                        } else if (this.framingham.colesterol_total >= 280) {
                            this.framingham.colesterol_puntaje = 11
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 40 && this.datosCita.Edad_Cumplida <= 49) {
                        if (this.framingham.colesterol_total < 160) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 160 && this.framingham.colesterol_total <= 199) {
                            this.framingham.colesterol_puntaje = 3
                        } else if (this.framingham.colesterol_total >= 200 && this.framingham.colesterol_total <= 239) {
                            this.framingham.colesterol_puntaje = 5
                        } else if (this.framingham.colesterol_total >= 240 && this.framingham.colesterol_total <= 279) {
                            this.framingham.colesterol_puntaje = 6
                        } else if (this.framingham.colesterol_total >= 280) {
                            this.framingham.colesterol_puntaje = 8
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 50 && this.datosCita.Edad_Cumplida <= 59) {
                        if (this.framingham.colesterol_total < 160) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 160 && this.framingham.colesterol_total <= 199) {
                            this.framingham.colesterol_puntaje = 2
                        } else if (this.framingham.colesterol_total >= 200 && this.framingham.colesterol_total <= 239) {
                            this.framingham.colesterol_puntaje = 3
                        } else if (this.framingham.colesterol_total >= 240 && this.framingham.colesterol_total <= 279) {
                            this.framingham.colesterol_puntaje = 4
                        } else if (this.framingham.colesterol_total >= 280) {
                            this.framingham.colesterol_puntaje = 5
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 60 && this.datosCita.Edad_Cumplida <= 69) {
                        if (this.framingham.colesterol_total < 160) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 160 && this.framingham.colesterol_total <= 199) {
                            this.framingham.colesterol_puntaje = 1
                        } else if (this.framingham.colesterol_total >= 200 && this.framingham.colesterol_total <= 239) {
                            this.framingham.colesterol_puntaje = 1
                        } else if (this.framingham.colesterol_total >= 240 && this.framingham.colesterol_total <= 279) {
                            this.framingham.colesterol_puntaje = 2
                        } else if (this.framingham.colesterol_total >= 280) {
                            this.framingham.colesterol_puntaje = 4
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 70) {
                        if (this.framingham.colesterol_total < 160) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 160 && this.framingham.colesterol_total <= 199) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 200 && this.framingham.colesterol_total <= 239) {
                            this.framingham.colesterol_puntaje = 0
                        } else if (this.framingham.colesterol_total >= 240 && this.framingham.colesterol_total <= 279) {
                            this.framingham.colesterol_puntaje = 1
                        } else if (this.framingham.colesterol_total >= 280) {
                            this.framingham.colesterol_puntaje = 1
                        }
                    }

                }
            },

            habitoFumador() {
                if (this.datosCita.sexo == 'F') {
                    if (this.datosCita.Edad_Cumplida >= 20 && this.datosCita.Edad_Cumplida <= 39) {
                        if (this.dataPaciente.fumador == null) {
                            this.framingham.fumador_puntaje = 0
                        } else {
                            this.framingham.fumador_puntaje = 9
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 40 && this.datosCita.Edad_Cumplida <= 49) {
                        if (this.dataPaciente.fumador == null) {
                            this.framingham.fumador_puntaje = 0
                        } else {
                            this.framingham.fumador_puntaje = 7
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 50 && this.datosCita.Edad_Cumplida <= 59) {
                        if (this.dataPaciente.fumador == null) {
                            this.framingham.fumador_puntaje = 0
                        } else {
                            this.framingham.fumador_puntaje = 4
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 60 && this.datosCita.Edad_Cumplida <= 69) {
                        if (this.dataPaciente.fumador == null) {
                            this.framingham.fumador_puntaje = 0
                        } else {
                            this.framingham.fumador_puntaje = 2
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 70) {
                        if (this.dataPaciente.fumador == null) {
                            this.framingham.fumador_puntaje = 0
                        } else {
                            this.framingham.fumador_puntaje = 1
                        }
                    }
                } else {
                    //Hombres
                    if (this.datosCita.Edad_Cumplida >= 20 && this.datosCita.Edad_Cumplida <= 39) {
                        if (this.dataPaciente.fumador == null) {
                            this.framingham.fumador_puntaje = 0
                        } else {
                            this.framingham.fumador_puntaje = 8
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 40 && this.datosCita.Edad_Cumplida <= 49) {
                        if (this.dataPaciente.fumador == null) {
                            this.framingham.fumador_puntaje = 0
                        } else {
                            this.framingham.fumador_puntaje = 5
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 50 && this.datosCita.Edad_Cumplida <= 59) {
                        if (this.dataPaciente.fumador == null) {
                            this.framingham.fumador_puntaje = 0
                        } else {
                            this.framingham.fumador_puntaje = 3
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 60 && this.datosCita.Edad_Cumplida <= 69) {
                        if (this.dataPaciente.fumador == null) {
                            this.framingham.fumador_puntaje = 0
                        } else {
                            this.framingham.fumador_puntaje = 1
                        }
                    } else if (this.datosCita.Edad_Cumplida >= 70) {
                        if (this.dataPaciente.fumador == null) {
                            this.framingham.fumador_puntaje = 0
                        } else {
                            this.framingham.fumador_puntaje = 1
                        }
                    }

                }
            },

            arterialPuntaje() {
                if (this.datosCita.sexo == 'F') {
                    if (this.dataPaciente.sistolica < 120) {
                        if (this.framingham.tratamiento == 'Si') {
                            this.framingham.arterial_puntaje = 0
                        } else {
                            this.framingham.arterial_puntaje = 0
                        }
                    } else if (this.dataPaciente.sistolica >= 120 && this.dataPaciente.sistolica <= 129) {
                        if (this.framingham.tratamiento == 'Si') {
                            this.framingham.arterial_puntaje = 3
                        } else {
                            this.framingham.arterial_puntaje = 1
                        }
                    } else if (this.dataPaciente.sistolica >= 130 && this.dataPaciente.sistolica <= 139) {
                        if (this.framingham.tratamiento == 'Si') {
                            this.framingham.arterial_puntaje = 4
                        } else {
                            this.framingham.arterial_puntaje = 2
                        }
                    } else if (this.dataPaciente.sistolica >= 140 && this.dataPaciente.sistolica <= 159) {
                        if (this.framingham.tratamiento == 'Si') {
                            this.framingham.arterial_puntaje = 5
                        } else {
                            this.framingham.arterial_puntaje = 3
                        }
                    } else if (this.dataPaciente.sistolica >= 160) {
                        if (this.framingham.tratamiento == 'Si') {
                            this.framingham.arterial_puntaje = 6
                        } else {
                            this.framingham.arterial_puntaje = 4
                        }
                    }
                } else {
                    //Hombres
                    if (this.dataPaciente.sistolica < 120) {
                        if (this.framingham.tratamiento == 'Si') {
                            this.framingham.arterial_puntaje = 0
                        } else {
                            this.framingham.arterial_puntaje = 0
                        }
                    } else if (this.dataPaciente.sistolica >= 120 && this.dataPaciente.sistolica <= 129) {
                        if (this.framingham.tratamiento == 'Si') {
                            this.framingham.arterial_puntaje = 1
                        } else {
                            this.framingham.arterial_puntaje = 0
                        }
                    } else if (this.dataPaciente.sistolica >= 130 && this.dataPaciente.sistolica <= 139) {
                        if (this.framingham.tratamiento == 'Si') {
                            this.framingham.arterial_puntaje = 2
                        } else {
                            this.framingham.arterial_puntaje = 1
                        }
                    } else if (this.dataPaciente.sistolica >= 140 && this.dataPaciente.sistolica <= 159) {
                        if (this.framingham.tratamiento == 'Si') {
                            this.framingham.arterial_puntaje = 2
                        } else {
                            this.framingham.arterial_puntaje = 1
                        }
                    } else if (this.dataPaciente.sistolica >= 160) {
                        if (this.framingham.tratamiento == 'Si') {
                            this.framingham.arterial_puntaje = 3
                        } else {
                            this.framingham.arterial_puntaje = 2
                        }
                    }

                }
            },

            sumaTotal() {
                const total = parseInt(this.framingham.arterial_puntaje) + parseInt(this.framingham.fumador_puntaje) +
                    parseInt(this.framingham.colesterol_puntajehdl) + parseInt(this.framingham.colesterol_puntaje) +
                    parseInt(this.framingham.edad_puntaje)

                this.framingham.totales = total
            },

            savefindrisk() {
                this.findrisk.Citapaciente_id = this.datosCita.cita_paciente_id;
                this.findrisk.paciente_id = this.datosCita.paciente_id;
                axios.post('/api/historia/savefindrisk', this.findrisk)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchFindrisk();
                    }).catch(err => {
                        this.setError(err.response.data)
                        this.$alerError(err.response.data.error)
                    })
            },

            fetchFindrisk() {
                axios.get(`/api/historia/fetchFindrisk/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.findrisk = res.data;
                        this.calcularEdadFindrisk();
                        this.imc();
                        this.perimetroabdominal();
                        this.actividadfisica();
                        this.consumoFrutasVerduras();
                        this.hipertension();
                        this.glucosa();
                        this.diabetes();
                    });
            },

            calcularEdadFindrisk() {
                if (this.datosCita.Edad_Cumplida < 45) {
                    this.findrisk.edad_puntaje = 0
                } else if (this.datosCita.Edad_Cumplida > 45 && this.datosCita.Edad_Cumplida <= 54) {
                    this.findrisk.edad_puntaje = 2
                } else if (this.datosCita.Edad_Cumplida > 55 && this.datosCita.Edad_Cumplida <= 64) {
                    this.findrisk.edad_puntaje = 3
                } else if (this.datosCita.Edad_Cumplida > 64) {
                    this.findrisk.edad_puntaje = 4
                }
            },

            imc() {
                if (this.dataPaciente.imc == null || this.dataPaciente.imc < 25) {
                    this.findrisk.indice_corporal = 0
                } else if (this.dataPaciente.imc >= 25 && this.dataPaciente.imc <= 30) {
                    this.findrisk.indice_corporal = 1
                } else if (this.dataPaciente.imc > 30) {
                    this.findrisk.indice_corporal = 3
                }

            },

            perimetroabdominal() {
                //hombres
                if (this.datosCita.sexo == 'M') {
                    if (this.dataPaciente.abdominal == null || this.dataPaciente.abdominal < 94) {
                        this.findrisk.perimetro_abdominal = 0
                    } else if (this.dataPaciente.abdominal >= 94) {
                        this.findrisk.perimetro_abdominal = 4
                    }
                }
                //mujeres
                else {
                    if (this.dataPaciente.abdominal == null || this.dataPaciente.abdominal < 90) {
                        this.findrisk.perimetro_abdominal = 0
                    } else if (this.dataPaciente.abdominal >= 90) {
                        this.findrisk.perimetro_abdominal = 4
                    }
                }
            },

            actividadfisica() {
                if (this.findrisk.actividad_fisica == 'Si') {
                    this.findrisk.puntaje_fisica = 0
                } else if (this.findrisk.actividad_fisica == 'No') {
                    this.findrisk.puntaje_fisica = 2
                }
            },

            consumoFrutasVerduras() {
                if (this.dataPaciente.frecuencia == 'Diario') {
                    this.findrisk.frutas_verduras = 0
                } else {
                    this.findrisk.frutas_verduras = 1
                }
            },

            hipertension() {
                if (this.findrisk.hipertension == 'Si') {
                    this.findrisk.resultado_hipertension = 2
                } else if (this.findrisk.hipertension == 'No') {
                    this.findrisk.resultado_hipertension = 0
                }
            },

            glucosa() {
                if (this.findrisk.glucosa == 'Si') {
                    this.findrisk.resultado_glucosa = 5
                } else if (this.findrisk.glucosa == 'No') {
                    this.findrisk.resultado_glucosa = 0
                }
            },

            diabetes() {
                if (this.findrisk.diabetes == 'Si' && this.findrisk.parentezco == 'Abuelos') {
                    this.findrisk.resultado_diabetes = 3
                } else if (this.findrisk.diabetes == 'Si' && this.findrisk.parentezco == 'Tíos') {
                    this.findrisk.resultado_diabetes = 3
                } else if (this.findrisk.diabetes == 'Si' && this.findrisk.parentezco == 'Primos hermanos') {
                    this.findrisk.resultado_diabetes = 3
                } else if (this.findrisk.diabetes == 'Si' && this.findrisk.parentezco == 'Hijos') {
                    this.findrisk.resultado_diabetes = 5
                } else if (this.findrisk.diabetes == 'Si' && this.findrisk.parentezco == 'Hermanos') {
                    this.findrisk.resultado_diabetes = 5
                } else if (this.findrisk.diabetes == 'Si' && this.findrisk.parentezco == 'Padres') {
                    this.findrisk.resultado_diabetes = 5
                } else if (this.findrisk.diabetes == 'No') {
                    this.findrisk.resultado_diabetes = 0
                }
            },

            sumTotoalF() {
                const total = parseInt(this.findrisk.edad_puntaje) + parseInt(this.findrisk.indice_corporal) + parseInt(
                        this.findrisk.perimetro_abdominal) +
                    parseInt(this.findrisk.puntaje_fisica) + parseInt(this.findrisk.frutas_verduras) + parseInt(this
                        .findrisk.resultado_hipertension) + parseInt(this.findrisk.resultado_glucosa) +
                    parseInt(this.findrisk.resultado_diabetes)

                this.findrisk.totales = total
            },

            guardarSeguir() {

                if (!this.findrisk.actividad_fisica) {
                    this.$alerError('El campo práctica actividad física es obligatorio!')
                    return;
                }

                if (!this.findrisk.hipertension) {
                    this.$alerError(
                        'El campo ¿Ha tomado medicación para la hipertensión con regularidad? es obligatorio')
                    return;
                }

                if (!this.findrisk.glucosa) {
                    this.$alerError(
                        'El campo ¿Le han encontrado alguna vez niveles altos de glucosa en sangre? es obligatorio')
                    return;
                }

                if (!this.findrisk.diabetes) {
                    this.$alerError(
                        'El campo ¿A algún miembro de su familia le han diagnosticado diabetes (tipo 1 ó 2)? es obligatorio'
                    )
                    return;
                }

                this.$emit('respuestaComponente')
            },

            limpiar() {
                for (const key in this.paraclinicos) {
                    this.paraclinicos[key] = ''
                }
            },

            porcentaje() {
                if (this.datosCita.sexo == 'M') {
                    if (this.framingham.totales >= 0 && this.framingham.totales <= 4) {
                        this.framingham.porcentaje = '1 %'
                    } else if (this.framingham.totales >= 5 && this.framingham.totales <= 6) {
                        this.framingham.porcentaje = '2 %'
                    } else if (this.framingham.totales == 7) {
                        this.framingham.porcentaje = '3 %'
                    } else if (this.framingham.totales == 8) {
                        this.framingham.porcentaje = '4 %'
                    } else if (this.framingham.totales == 9) {
                        this.framingham.porcentaje = '5 %'
                    } else if (this.framingham.totales == 10) {
                        this.framingham.porcentaje = '6 %'
                    } else if (this.framingham.totales == 11) {
                        this.framingham.porcentaje = '8 %'
                    } else if (this.framingham.totales == 12) {
                        this.framingham.porcentaje = '10 %'
                    } else if (this.framingham.totales == 13) {
                        this.framingham.porcentaje = '12 %'
                    } else if (this.framingham.totales == 14) {
                        this.framingham.porcentaje = '16 %'
                    } else if (this.framingham.totales == 15) {
                        this.framingham.porcentaje = '20 %'
                    } else if (this.framingham.totales == 16) {
                        this.framingham.porcentaje = '25 %'
                    } else if (this.framingham.totales >= 17) {
                        this.framingham.porcentaje = '30 %'
                    }
                } else if (this.datosCita.sexo == 'F') {
                    if (this.framingham.totales >= 0 && this.framingham.totales <= 12) {
                        this.framingham.porcentaje = '1 %'
                    } else if (this.framingham.totales >= 13 && this.framingham.totales <= 14) {
                        this.framingham.porcentaje = '2 %'
                    } else if (this.framingham.totales == 15) {
                        this.framingham.porcentaje = '3 %'
                    } else if (this.framingham.totales == 16) {
                        this.framingham.porcentaje = '4 %'
                    } else if (this.framingham.totales == 17) {
                        this.framingham.porcentaje = '5 %'
                    } else if (this.framingham.totales == 18) {
                        this.framingham.porcentaje = '6 %'
                    } else if (this.framingham.totales == 19) {
                        this.framingham.porcentaje = '8 %'
                    } else if (this.framingham.totales == 20) {
                        this.framingham.porcentaje = '11 %'
                    } else if (this.framingham.totales == 21) {
                        this.framingham.porcentaje = '14 %'
                    } else if (this.framingham.totales == 22) {
                        this.framingham.porcentaje = '17 %'
                    } else if (this.framingham.totales == 23) {
                        this.framingham.porcentaje = '22 %'
                    } else if (this.framingham.totales == 24) {
                        this.framingham.porcentaje = '27 %'
                    } else if (this.framingham.totales >= 25) {
                        this.framingham.porcentaje = '30 %'
                    }
                }
            }


        }
    }

</script>
