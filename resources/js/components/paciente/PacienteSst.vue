<template>
    <div>
        <v-card color="lighten-1" class="mb-5" height="auto">
            <v-layout row wrap>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Region" v-model="formPaciente.Region"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Ut" v-model="formPaciente.Ut"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Primer Nombre" v-model="formPaciente.Primer_Nom"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Segundo Nombre" v-model="formPaciente.SegundoNom"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Primer Apellido" v-model="formPaciente.Primer_Ape"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Segundo Apellido" v-model="formPaciente.Segundo_Ape"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['TI','CC','RC','CE','PA','CN','SA','PE']" label="Tipo documento" required
                        v-model="formPaciente.Tipo_Doc"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Numero documento" type="number" v-model="formPaciente.Num_Doc">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-radio-group label="Genero biologico" v-model="formPaciente.Sexo">
                        <v-radio key="M" label="Masculino" value="M" color="info">
                        </v-radio>
                        <v-radio key="F" label="Femenino" value="F" color="info"></v-radio>
                    </v-radio-group>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Fecha Nacimiento" v-model="formPaciente.Fecha_Naci" readonly></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select
                        :items="['Sin discapacidad','Fisica','Mental/psiquica','Visual','Auditiva','Sordo - Ceguera']"
                        label="Discapacidad" v-model="formPaciente.Discapacidad"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-radio-group label="Grado discapacidad" v-model="formPaciente.Grado_Discapacidad">
                        <v-radio key="Leve" label="Leve" value="Leve" color="info">
                        </v-radio>
                        <v-radio key="Moderada" label="Moderada" value="Moderada" color="info"></v-radio>
                        <v-radio key="Severa" label="Severa" value="Severa" color="info"></v-radio>
                    </v-radio-group>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Tipo de Afiliado" v-model="formPaciente.Tipo_Afiliado"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select
                        :items="['Indígena','Gitano','Raizal','Palenquero','Negro(a)','Mulato(a)','Afrocolombiano(a)','Afro descendiente']"
                        label="Etnia" required v-model="formPaciente.Etnia"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Labora en" v-model="formPaciente.Laboraen"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-autocomplete :items="departamento" item-text="departamento" item-value="id_departamento"
                        v-model="formPaciente.Dpto_Labora_id" label="Departamento labora">
                    </v-autocomplete>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-autocomplete label="Municipio labora" :items="municipios" item-text="municipio" item-value="id"
                        v-model="formPaciente.Mpio_Labora" required></v-autocomplete>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Direccion Residencia" v-model="formPaciente.Direccion_Residencia">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-autocomplete :items="departamento" item-text="departamento" item-value="id_departamento"
                        v-model="formPaciente.Dpto_Residencia_id" label="Departamento Residencia">
                    </v-autocomplete>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Municipio de Residencia" v-model="formPaciente.Mpio_Residencia">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Telefono" v-model="formPaciente.Telefono"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Correo 1" v-model="formPaciente.Correo1"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Correo 2" v-model="formPaciente.Correo2"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['1','2','3','4','5','6','7','8']" label="Estrato" required
                        v-model="formPaciente.Estrato"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Celular1" v-model="formPaciente.Celular1" type="number" maxlength="10"
                        oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                        onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" min="1"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Celular2" v-model="formPaciente.Celular2"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Num_Hijos" v-model="formPaciente.Num_Hijos"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']" label="RH" required
                        v-model="formPaciente.RH"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select
                        :items="['Bachiller','Tecnico','Tecnologo','Universitario','Posgrado','Maestria','Doctorado']"
                        label="Escolaridad" required v-model="formPaciente.Nivelestudio"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Aseguradora" v-model="formPaciente.Aseguradora"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field label="Ocupacion" v-model="formPaciente.Ocupacion"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-text-field type="number" label="nivel" v-model="formPaciente.nivel"></v-text-field>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-autocomplete label="Entidad afiliada" v-model="formPaciente.entidad_id" :items="entidades"
                        item-text="nombre" item-value="id" required>
                    </v-autocomplete>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['Soltero','Casado','Unión Libre','Separado','Viudo']" label="estado civil"
                        required v-model="formPaciente.estado_civil"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['Coordinador(a)','Directivo(a)','Docente','Orientador(a)','Rector(a)']"
                        label="cargo laboral" required v-model="formPaciente.cargo_laboral"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['0','1 a 2','3 a 4','> 4']" label="composicion familiar" required
                        v-model="formPaciente.composicion_familiar"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['Propia','Arriendo','Familiar','Compartida']" label="vivienda" required
                        v-model="formPaciente.vivienda"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['Ninguna','(1-3)','(4-6)','Mas de  6']" label="personas a cargo" required
                        v-model="formPaciente.personas_a_cargo"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['Carrera administrativa','Provisional','Libre nombramiento','Prestación de servicios','Honorarios'
                                ]" label="tipo contratacion" required v-model="formPaciente.tipo_contratacion">
                    </v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['< 1 año','1-a 5 años ','5-10 años ','10-15 años ','> 15 años']"
                        label="antiguedad en empresa" required v-model="formPaciente.antiguedad_en_empresa"></v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['< 1 año','1-a 5 años','5-10 años','10-15 años','> 15 años']"
                        label="antiguedad cargo actual" required v-model="formPaciente.antiguedad_cargo_actual">
                    </v-select>
                </v-flex>
                <v-flex xs12 sm2 class="px-2">
                    <v-select :items="['Ninguno','(1 - 3)','(4 -6)','> 6']" label="# cursos a cargo" required
                        v-model="formPaciente.numero_cursos_a_cargo"></v-select>
                </v-flex>
            </v-layout>
        </v-card>
        <v-btn color="primary" round @click="pacienteUpdate()">Guardar y seguir</v-btn>

    </div>
</template>

<script>
    export default {
        name: 'HorusHealthPacienteSst',
        props: {
            datosCita: Object
        },

        data() {
            return {
                formPaciente: {
                    idpaciente: null,
                    Primer_Nom: null,
                    SegundoNom: null,
                    Primer_Ape: null,
                    Segundo_Ape: null,
                    Tipo_Doc: null,
                    Num_Doc: null,
                    Sexo: null,
                    Fecha_Afiliado: null,
                    Fecha_Naci: null,
                    Discapacidad: null,
                    Grado_Discapacidad: null,
                    Tipo_Afiliado: null,
                    Nivelestudio: null,
                    Etnia: null,
                    Nivel_Sisben: null,
                    Ocupacion: null,
                    Laboraen: null,
                    Mpio_Labora: null,
                    Telefono: null,
                    Correo1: null,
                    Estrato: null,
                    Celular1: null,
                    RH: null,
                    entidad_id: null,
                    Direccion_Residencia: null,
                    tipo_categoria: null,
                    tipo_contratacion: null,
                    antiguedad_en_empresa: null,
                    Dpto_Labora_id: null,
                    Dpto_Residencia_id: null,
                },
                entidades: [],
                municipios: [],
                departamento:[]
            };
        },

        created() {
            this.firstPaciente();
            this.listEntidades();
            this.fetchMunicipios();
            this.fetchDepartamentos();

        },

        methods: {
            firstPaciente() {
                axios.get(`/api/cita/${this.datosCita.cita_paciente_id}/datospaciente`)
                    .then(res => {
                        this.formPaciente = res.data;
                        this.formPaciente.Mpio_Labora = parseInt(res.data.Mpio_Labora)
                        this.formPaciente.entidad_id = parseInt(res.data.entidad_id)
                    });
            },

            pacienteUpdate() {
                axios.post(`/api/cita/updatepaciente/${this.formPaciente.id}`, this.formPaciente)
                    .then(res => {
                        this.$alerSuccess("Paciente actualizado con exito!");
                        this.$emit('respuestaComponente');
                    });
            },
            listEntidades() {
                axios.get('/api/entidades/getEntidades').then(res => {
                    this.entidades = res.data;
                })
            },

            fetchMunicipios() {
                axios.get('/api/municipio/lista')
                    .then(res => {
                        this.municipios = res.data
                    })
            },

            fetchDepartamentos() {
                axios.get('/api/domiciliaria/departamento')
                    .then((res) => {
                        this.departamento = res.data
                    })
                    .catch((err) => console.log(err));
            },
        },
    };

</script>

<style lang="scss" scoped>

</style>
