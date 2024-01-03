<template>
    <v-card>
        <referencia-show-dialog :data="data" :showReferenceDialog="showReferenceDialog"
            @closeDialog="showReferenceDialog=false" @gestionar="gestionar" :inPendingPage="inPendingPage">
            <PacienteData ref="PacienteData" :paciente="paciente" :noreadonlyradicado="inPendingPage" showReference />
        </referencia-show-dialog>
        <v-card-text class="pa-0">
            <v-container grid-list-xs fluid class="pa-0">
                <v-layout row wrap>
                    <v-spacer></v-spacer>
                    <v-flex v-if="internalProcess" xs6 pa-3>
                        <v-text-field v-show="showFilter" label="Buscar..." v-model="search"></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                        <v-data-table :loading="loading" :headers="headers" :items="referencias"
                            :pagination.sync="pagination" :search="search">
                            <template v-slot:no-data>
                                <span>No hay referencias</span>
                            </template>
                            <template v-slot:headers="props">
                                <tr>
                                    <template v-for="header in props.headers">
                                        <th v-if="showHeader(header.text)" :key="header.text"
                                            :class="`text-xs-${header.align}`">
                                            {{ header.text }}
                                        </th>
                                    </template>
                                </tr>
                            </template>
                            <template v-slot:items="props">
                                <td class="text-xs-center">{{ props.item.id }}</td>
                                <td class="text-xs-center" v-if="showAnexo()">Anexo{{ props.item.Tipo_anexo }}</td>
                                <td class="text-xs-center">{{ props.item.Num_Doc }}</td>
                                <td class="text-xs-center">{{ props.item | fullnamePaciente }}</td>
                                <td class="text-xs-center">{{ props.item.created_at | date }}</td>
                                <td class="text-xs-center" v-if="props.item.referencia_id == null">{{ props.item | fullnamePrestador }}</td>
                                <td class="text-xs-center" v-if="props.item.referencia_id != null">Sumimedical S.A.S</td>
                                <td class="text-xs-center">{{ props.item | fullcie10s }}</td>
                                <td class="text-xs-center">{{ props.item.tipo_solicitud}}</td>
                                <td class="text-xs-center">
                                    <v-btn color="primary" icon @click="showReference(props.item)">
                                        <v-icon>library_books</v-icon>
                                    </v-btn>
                                    <v-btn color="warning" icon @click="sourceBitacora(props.item)"
                                        v-if="inProcessPage || internalProcess">
                                        <v-icon>forum</v-icon>
                                    </v-btn>
                                    <v-btn color="info" icon @click="Anexo9(props.item.id,'anexo9Referencia')"
                                           v-if="props.item.Tipo_anexo == '9'">
                                        <v-icon>file_download</v-icon>
                                    </v-btn>
                                    <div v-if="can('generar.concurrecia')">
                                        <v-btn v-if="(props.item.Tipo_anexo == '9' || props.item.Tipo_anexo == '3') && (props.item.estadoconcurrencia_id == null )"
                                                    color="success" icon @click="concurrenciaRegistro(props.item)">
                                            <v-icon>mdi-account-settings</v-icon>
                                        </v-btn>
                                    </div>

                                </td>
                            </template>

                        </v-data-table>
                    </v-flex>
                </v-layout>
                <v-layout row justify-center>
                    <v-dialog v-model="dialogConcurrencia" persistent max-width="1200px">
                        <v-card>
                            <v-card-title class="headline success" style="color:white">
                                <span class="title layout justify-center font-weight-light">Registrar
                                    concurrencia</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Auditor" v-model="fullName" readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md8>
                                            <v-text-field label="IPS del paciente" v-model="datosReferencia.name"
                                                readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="IPS del paciente" v-model="datosReferencia.ipsNombre"
                                                readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field
                                                label="Costo atencion"
                                                v-model="concurrencia.costo_atencion"
                                                type="number" min="0"
                                                >
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select
                                                :items="['Consulta Externa', 'Medicina Domiciliaria', 'Prioritaria', 'Programado', 'Referencia', 'Reingreso', 'Urgencias', 'Traslado primario ']"
                                                label="Via de ingreso" v-model="concurrencia.via_ingreso" required>
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md9>
                                            <v-select
                                                :items="['Altas tempranas no pertinentes.', 'Altas voluntarias.', 'Complicación posterior a la realización de procedimiento.', 'Deterioro del estado clínico.', 'Disfuncionalidad de catéteres o sondas (patologías crónicas)', 'Eventos relacionados con uso de medicamentos prescritos', 'Falta oportunidad en procedimiento ambulatorios posterior al alta (diferidas).', 'Infección de sitio operatorio – ISO.', 'No adherencia al tratamiento por parte del paciente.', 'No dispensación medicamento posterior al alta.', 'No ingreso a programas ni captación de gestión de riesgo posterior al alta.',
                                                'No es un reingreso a hospitalización por la misma causa antes de 15 días', 'reingreso por la misma causa o causa asociada antes de 30 dias']"
                                                label="Reingreso hospitalización"
                                                v-model="concurrencia.reingreso_hospitalización15días" required>
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md1>
                                            <v-text-field label="Tipo identificacion" v-model="datosReferencia.Tipo_Doc"
                                                readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md2>
                                            <v-text-field label="Identificacion" v-model="datosReferencia.Num_Doc"
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md7>
                                            <v-text-field label="Nombre completo"
                                                v-model="datosReferencia.Primer_Nom+' '+datosReferencia.SegundoNom+' '+datosReferencia.Primer_Ape+' '+datosReferencia.Segundo_Ape"
                                                readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md1>
                                            <v-text-field label="Años" v-model="datosReferencia.Edad_Cumplida" readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md3>
                                            <v-text-field label="Fecha ingreso" v-model="datosReferencia.created_at"
                                                readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Diagnostico de ingreso"
                                                v-model="cie10sReferencia.codigo+' '+cie10sReferencia.descripcion">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md5>
                                            <v-select :items="[
                                                            'Cesárea',
                                                            'Hospitalización Médica',
                                                            'Hospitalización Quirúrgica',
                                                            'Intermedio Adulto',
                                                            'Intermedio Neonatal',
                                                            'Intermedio Pediatría',
                                                            'Observación ',
                                                            'Otras catastróficas',
                                                            'Parto',
                                                            'UCI Adulto',
                                                            'UCI Neonatal',
                                                            'UCI Pediatría',
                                                            'Urgencias'
                                                        ]" label="Unidad funcional"
                                                v-model="concurrencia.unidad_funcional" required>
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md3>
                                            <v-text-field label="Fecha egreso" v-model="concurrencia.fecha_egreso"
                                                type="date">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select :items="[
                                                            'Muerte en menor de 5 años',
                                                            'Alta',
                                                            'Fuga',
                                                            'Medicina domiciliaria',
                                                            'Muerte Materna',
                                                            'Muerte Perinatal',
                                                            'Muerte por Maltrato',
                                                            'Muerto',
                                                            'Refencia a mayor nivel',
                                                            'Refencia a menor nivel',
                                                            'Referencia a menor tarifa pactada',
                                                            'Salida Voluntaria',
                                                        ]" label="Destino egreso" v-model="concurrencia.destino_egreso"
                                                >
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-autocomplete v-model="concurrencia.cie10_id" append-icon="search"
                                                :items="cieConcat" item-disabled="customDisabled" item-text="nombre"
                                                item-value="id" label="Diagnóstico de egreso">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-autocomplete v-model="concurrencia.cie10_id_egresoAsociado" append-icon="search"
                                                :items="cieConcat" item-disabled="customDisabled" item-text="nombre"
                                                item-value="id" label="Diagnóstico de egreso asociado">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm6 md2>
                                            <v-text-field label="Dias estancia observacion"  @change="total"
                                                v-model="concurrencia.dias_estancia_observacion"
                                                type="number" min="0">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Dias estancia c.intensivo" @change="total"
                                                v-model="concurrencia.dias_estancia_intensivo"
                                                type="number" min="0">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Dias estancia intermedio" @change="total"
                                                v-model="concurrencia.dias_estancia_intermedio"
                                                type="number" min="0">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Dias estancia basico" @change="total"
                                                v-model="concurrencia.dias_estancia_basicos"
                                                type="number" min="0">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Estancia total días"
                                                v-model="concurrencia.estancia_total_dias" readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select :items="[
                                                            'Anestesiología',
                                                            'Cardiología',
                                                            'Cirugía bariátrica',
                                                            'Cirugía cardiovascular',
                                                            'Cirugía de cabeza y cuello',
                                                            'Cirugía de tórax',
                                                            'Cirugía general',
                                                            'Cirugía hepatobiliar',
                                                            'Cirugía infantil',
                                                            'Cirugía maxilofacial',
                                                            'Cirugía oncológica',
                                                            'Cirugía plástica',
                                                            'Cirugía vascular',
                                                            'Coloproctología',
                                                            'Cuidado critico',
                                                            'Dermatología',
                                                            'Endocrinología',
                                                            'Fisiatría',
                                                            'Gastroenterología',
                                                            'Genética',
                                                            'Ginecología',
                                                            'Hematología',
                                                            'Hematoncología',
                                                            'Hemodinamia',
                                                            'Hepatología',
                                                            'Infectología',
                                                            'Inmunología',
                                                            'Medicina del dolor',
                                                            'Medicina general',
                                                            'Medicina Interna',
                                                            'Medicina nuclear',
                                                            'Nefrología',
                                                            'Neumología',
                                                            'Neurocirugía',
                                                            'Neurología',
                                                            'Obstetricia',
                                                            'Oftalmología',
                                                            'Oncología',
                                                            'Ortopedia',
                                                            'Otorrinolaringología',
                                                            'Pediatría',
                                                            'Psiquiatría',
                                                            'Radiología',
                                                            'Radioterapia',
                                                            'Reumatología',
                                                            'Toxicología',
                                                            'Urgentología',
                                                            'Urología',
                                                            'Electrofisiología',
                                                        ]" label="Especialidad tratante"
                                                v-model="concurrencia.especialidad_tratante" >
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Peso" v-model="concurrencia.peso_rn">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Edad gestacional"
                                                v-model="concurrencia.edad_gestacional">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select :items="[
                                                            'AIT',
                                                            'Amenaza de parto pretérmino',
                                                            'Angina inestable',
                                                            'Anticoagulados',
                                                            'Asma',
                                                            'Cáncer de cérvix',
                                                            'Cáncer de próstata',
                                                            'Cáncer de seno',
                                                            'Cetoacidosis diabética',
                                                            'Coma hiperosmolar',
                                                            'DM descompensada',
                                                            'Eclampsia',
                                                            'ECV Hemorrágico',
                                                            'ECV Isquémico',
                                                            'Emergencia HTA',
                                                            'Enfermedad Coronaria Severa',
                                                            'Enfermedad renal Crónica',
                                                            'Enfermedad renal crónica agudizada',
                                                            'EPOC descompensado',
                                                            'Falla de remisión oportuna del RN',
                                                            'IAM',
                                                            'ICC descompensada',
                                                            'Infección del tracto urinario (ITU)',
                                                            'Infecciones neonatales tempranas en madres con antecedente de patología infecciosa evitables como IVU, corioamnionitis, neumonía.',
                                                            'Macrosomía',
                                                            'Neumonía',
                                                            'No dispensación de medicamentos',
                                                            'Pre-eclampsia',
                                                            'RCIU',
                                                            'Recién nacido con TORCH',
                                                            'RN pretérmino',
                                                            'RPM',
                                                            'SDR de RN (TTRN, pulmón húmedo)',
                                                            'TBC congénita',
                                                            'Tétanos neonatal',
                                                            'Trastorno de electrolitos en paciente polimedicado',
                                                            'Trastorno electrolítico con madre diabética',
                                                            'Urgencia dialítica',
                                                            'Urgencia HTA',
                                                        ]" label="Hospitalización evitable"
                                                v-model="concurrencia.hospitalizacion_evitable" >
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select :items="[
                                                            'No aplica',
                                                            'Anticoagulados (Novo)',
                                                            'Anticoagulados - Z921',
                                                            'Anticoagulados (Complicación)',
                                                            'Consumo de sustancias Psicoactivas',
                                                            'Diabetes Mellitus - E109',
                                                            'DM (Descompensada)',
                                                            'DM (Novo)',
                                                            'Enfermedad renal crónica - N189',
                                                            'EPOC - J449',
                                                            'EPOC (Descompensado)',
                                                            'EPOC (Novo)',
                                                            'Hipertensión Arterial - I10x',
                                                            'HTA (Descompensada)',
                                                            'HTA (Novo)',
                                                            'Paciente Polimedicado',
                                                            'Tuberculosis',
                                                            'Victima de violencia sexual',
                                                            'VIH (Hospitalización por patología asociada a VIH)',
                                                            'VIH / Novo',
                                                        ]" label="Reporte patologia dx"
                                                v-model="concurrencia.reporte_patologia_diagnostica" >
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select :items="[
                                                            'Asalto sexual en la institución',
                                                            'Asfixia perinatal',
                                                            'Cirugía en parte equivocada o en paciente equivocado',
                                                            'Cirugías o procedimientos cancelados por factores atribuibles al desempeño de la organización o de los profesionales',
                                                            'Consumo intra - institucional de Psicoactivos',
                                                            'Deterioro del paciente en la clasificación en la escala de Glasgow sin tratamiento',
                                                            'Distocia inadvertida',
                                                            'Entrega equivocada de reportes de laboratorio',
                                                            'Entrega equivocada de un neonato',
                                                            'Error en el diagnostico',
                                                            'Estancia prolongada por no disponibilidad de insumos o medicamentos',
                                                            'Eventos asociados al uso de dispositivos médicos y equipos biomédicos',
                                                            'Eventos postransfusionales',
                                                            'Eventos relacionados con la administración de medicamentos',
                                                            'Eventración o evisceración o dehiscencia de sutura',
                                                            'Extubación o decanulación no programada',
                                                            'Flebitis mecánica - Flebitis en sitios de venopunción',
                                                            'Fuga de pacientes siquiátricos y menores de 14 años hospitalizados',
                                                            'Infección del Sitio Operatorio - ISO',
                                                            'Infección del Torrente Sanguineo Asociado a catéter en UCI',
                                                            'Infección del Tracto Urinario Asociado a Catéter en UCI',
                                                            'Infecciones asociadas a la atención en salud - IAAS (Nosocomiales)',
                                                            'Ingreso no programado a UCI luego de procedimiento que implica la administración de anestesia',
                                                            'Lesión de órgano secundario a procedimiento',
                                                            'Lesiones causadas por caídas institucional',
                                                            'Luxación post - quirúrgica en reemplazo de cadera',
                                                            'Maternas con convulsión intrahospitalaria',
                                                            'Neumonia Asociada a Ventilador Mecanico en UCI',
                                                            'Neumotórax por ventilación mecánica o asociado a paso de catéter venoso central',
                                                            'Otros eventos de seguridad del paciente identificados',
                                                            'Pacientes con diagnóstico que apendicitis que no son atendidos después de 12 horas de realizado el diagnostico',
                                                            'Pacientes con hipotensión severa en post – quirúrgico',
                                                            'Pacientes con infarto en las siguientes 72 horas post – quirúrgico',
                                                            'Pacientes con neumonías broncoaspirativas en pediatría o UCI neonatal',
                                                            'Pacientes con trombosis venosa profunda a quienes no se les realiza control de pruebas de coagulación',
                                                            'Pacientes con úlceras de posición',
                                                            'Pérdida de pertenencias de usuarios',
                                                            'Problemas relacionados con el uso de medicamentos (PRUM)',
                                                            'Quemaduras por equipos biomédicos',
                                                            'Quemaduras por lámparas de fototerapia y para electrocauterio',
                                                            'Reacción adversa a los medicamentos',
                                                            'Reingreso a hospitalización por la misma causa antes de 15 días',
                                                            'Reingreso al servicio de urgencias por misma causa antes de 72 Horas',
                                                            'reingreso por la misma causa o causa asociada antes de 30 dias',
                                                            'Retención de cuerpos extraños en pacientes internados',
                                                            'Retiro accidental o autoretiro de dispositivos invasivos',
                                                            'Revisión de reemplazos articulares por inicio tardío de la rehabilitación',
                                                            'Robo intra – institucional de niños',
                                                            'Ruptura prematura de membranas sin conducta definida',
                                                            'Secuelas post – reanimación',
                                                            'Shock hipovolémico post – parto (Código rojo)',
                                                            'Suicidio de pacientes internados',
                                                        ]" label="Eventos de seguridad"
                                                v-model="concurrencia.eventos_seguridad" >
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select :items="[
                                                            'Bajo Peso al nacer',
                                                            'Hospitalización por EDA en niños de 3 a 5 años',
                                                            'Hospitalización por neumonia en niños y niñas de 3 a 5 años',
                                                            'Muerte Materna',
                                                            'Muerte por Dengue',
                                                            'Muerte por Malaria',
                                                            'Otitis Media Supurativa en menores de 5 años',
                                                        ]" label="Eventos centinelas"
                                                v-model="concurrencia.eventos_centinelas" >
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select :items="[
                                                            'Anomalías congénitas',
                                                            'Hipotiroidismo congénito',
                                                            'Mortalidad EDA <5 años',
                                                            'Mortalidad infantil <1 año',
                                                            'Mortalidad IRA/neumonía <5',
                                                            'Mortalidad IRA/neumonía >65',
                                                            'Mortalidad perinatal',
                                                            'Mortalidad por otra EISP',
                                                            'Sífilis congénita',
                                                            'Sífilis gestacional',
                                                            'VIH/gestante infectada',
                                                            'VIH/mortalidad por VIH-SIDA',
                                                            'VIH/RN vivo gestante infectada',

                                                        ]" label="Eventos de notificacion inmedita"
                                                v-model="concurrencia.eventos_notificacion_inmediata" >
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="descripcion gestion"
                                                v-model="concurrencia.descripicion_gestion_realizada">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select :items="[
                                                            'Alta temprana',
                                                            'Cambios de tratamiento',
                                                            'Intervención temprana y potenciales complicaciones',
                                                            'Otro asegurador',
                                                            'Renegociación de tarifas en insumos, medicamentos, procedimientos y ayudas diagnosticas',
                                                            'Tecnología no realizada',
                                                            'Traslado a IPS con menor tarifa',
                                                            'Traslado a IPS de nivel inferior',
                                                            'Servicios y/o procedimientos diferidos (ambulatorios)',

                                                        ]" label="Costo evitado" v-model="concurrencia.costo_evitado"
                                                >
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Descripcion costo"
                                                v-model="concurrencia.descripcion_costo_evitado">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Valor costo evitado"
                                                v-model="concurrencia.valor_costo_evitado"
                                                type="number" min="0"
                                                >
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select :items="[
                                                            'Pertinencia de estancia',
                                                            'Calidad - seguridad',
                                                            'Oportunidad ',
                                                            'Pertinencia de ayudas diagnosticas',
                                                            'Pertinencia de medicamentos ',
                                                            'Pertinencia de otra ',
                                                            'Pertinencia de procedimientos',
                                                        ]" label="Costo evitable" v-model="concurrencia.costo_evitable"
                                                >
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Descripcion costo"
                                                v-model="concurrencia.descripción_costo_evitable">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Valor costo evitables"
                                                v-model="concurrencia.valor_costo_evitable"
                                                type="number" min="0"
                                                >
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select :items="[
                                                            'Cáncer',
                                                            'Cirugía cardiovascular',
                                                            'Cirugía enfermedades congénitas',
                                                            'Diálisis (CAPD/hemo)',
                                                            'Electrofisiología',
                                                            'Gran quemado',
                                                            'Hemodinamia',
                                                            'Insuficiencia renal',
                                                            'Neurocirugía',
                                                            'Quimioterapia / radioterapia',
                                                            'Remplazos articulares',
                                                            'Trasplante',
                                                            'Trauma mayor',
                                                            'VIH',
                                                        ]" label="Alto costo" v-model="concurrencia.alto_costo"
                                                >
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-select :items="[
                                                            'Dotación',
                                                            'Historia Clínica Y Registros',
                                                            'Infraestructura',
                                                            'Interdependencia',
                                                            'Medicamentos, Dispositivos Médicos e Insumos',
                                                            'Procesos Prioritarios ',
                                                            'Talento Humano',
                                                        ]" label="Incumplimiento habilitación"
                                                v-model="concurrencia.incumplimiento_habilitación" >
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Descripcion habilitación"
                                                v-model="concurrencia.descripcion_habilitación">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Asesoria especialista"
                                                v-model="concurrencia.asesoria_especialista">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-text-field label="Número factura" v-model="concurrencia.numero_factura"
                                                type="number">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-textarea solo name="input-7-4" label="Observaciones de concurrencia"
                                                v-model="concurrencia.lider_concurrencia"></v-textarea>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="red" dark @click="dialogConcurrencia = false">Cerrar</v-btn>
                                <v-btn color="success" dark @click="registrarCocurrencia()">Generar Concurrencia
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-layout>
            </v-container>
        </v-card-text>
        <div v-if="pagination.totalItems > 0" class="text-xs-center pt-2">
            <v-pagination v-show="showFilter" v-model="pagination.page" :length="pages"></v-pagination>
        </div>
    </v-card>
</template>

<script>
    import PacienteData from '../../components/paciente/PacienteData.vue'
    import ReferenciaShowDialog from '../../components/referencia/ReferenciaShowDialog.vue'
    import moment from 'moment';
    import {
        mapGetters
    } from 'vuex';
    moment.locale('es');

    export default {
        name: 'ReferenciaTable',
        components: {
            PacienteData,
            ReferenciaShowDialog
        },
        props: {
            referencias: Array,
            inProcessPage: Boolean,
            inPendingPage: Boolean,
            internalProcess: Boolean,
            loading: Boolean,
            showFilter: Boolean,
        },
        data: () => {
            return {
                cie10s: [],
                datosReferencia: [],
                cie10sReferencia: {
                    codigo: '',
                    descripcion: ''
                },
                proveedores: [],
                concurrencia: {
                    id_concurrencia: '',
                    cie10_id: '',
                    cie10_id_egresoAsociado: '',
                    ips_basica: '',
                    paciente_id: '',
                    ips_atencion: '',
                    costo_atencion: '',
                    via_ingreso: '',
                    reingreso_hospitalización15días: '',
                    unidad_funcional: '',
                    fecha_egreso: '',
                    destino_egreso: '',
                    dias_estancia_observacion: 0,
                    dias_estancia_intensivo: 0,
                    dias_estancia_intermedio: 0,
                    dias_estancia_basicos: 0,
                    estancia_total_dias: '',
                    especialidad_tratante: '',
                    peso_rn: '',
                    edad_gestacional: '',
                    hospitalizacion_evitable: '',
                    reporte_patologia_diagnostica: '',
                    eventos_seguridad: '',
                    eventos_centinelas: '',
                    eventos_notificacion_inmediata: '',
                    descripicion_gestion_realizada: '',
                    costo_evitado: '',
                    descripcion_costo_evitado: '',
                    valor_costo_evitado: '',
                    costo_evitable: '',
                    descripción_costo_evitable: '',
                    valor_costo_evitable: '',
                    alto_costo: '',
                    incumplimiento_habilitación: '',
                    descripcion_habilitación: '',
                    asesoria_especialista: '',
                    numero_factura: '',
                    lider_concurrencia: '',
                },
                dialogConcurrencia: false,
                data: {
                    Tipo_anexo: null,
                    cie10s: [],
                },
                headers: [{
                        text: 'Id',
                        value: 'id',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Anexo',
                        value: 'Tipo_anexo',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Identificación',
                        value: 'Num_Doc',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Nombre',
                        value: 'string',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Registro',
                        value: 'string',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'IPS',
                        value: 'string',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Diagnósticos',
                        value: 'string',
                        align: 'center',
                        sortable: false,
                        width: "500"
                    },
                    {
                        text: 'Tipo Solicitud',
                        value: 'tipo_solicitud',
                        align: 'center',
                        sortable: false,
                        width: "500"
                    },
                    {
                        text: 'Gestión',
                        value: 'string',
                        align: 'center',
                        sortable: false
                    }
                ],
                paciente: {

                },
                pagination: {
                    rowsPerPage: 20,
                },
                search: '',
                showReferenceDialog: false
            }
        },
        computed: {
            ...mapGetters(['can']),
            pages() {
                if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0

                return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
            },
            ...mapGetters(['fullName', 'UserEmail']),
            cieConcat() {
                return this.cie10Array = this.cie10s.map(cie => {
                    return {
                        id: cie.id,
                        codigo: cie.Codigo_CIE10,
                        nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                        customDisabled: false
                    };
                });
            },
        },
        filters: {
            fullnamePaciente: (item) => {
                return `${item.Primer_Nom || ''} ${item.SegundoNom || ''} ${item.Primer_Ape || ''} ${item.Segundo_Ape || ''}`
            },
            fullnamePrestador: (item) => {
                return `${item.name || ''} ${item.apellido || ''}`
            },
            fullcie10s: (item) => {
                let str = '';
                item.cie10s.forEach(cie => str = str.concat(`${cie.Codigo_CIE10} - ${cie.Descripcion}, `));

                if (str) str = str.slice(0, -2);

                return str;
            },
            date: (date) => {
                return moment(date).format('lll')
            }
        },
        watch: {
            referencias() {
                this.pagination.totalItems = this.referencias.length;
            }
        },
        methods: {
            total(){
                let p = Number(this.concurrencia.dias_estancia_observacion)+
                        Number(this.concurrencia.dias_estancia_intensivo)+
                        Number(this.concurrencia.dias_estancia_intermedio)+
                        Number(this.concurrencia.dias_estancia_basicos)
                 return this.concurrencia.estancia_total_dias = p
            },
            registrarCocurrencia() {
                if (this.concurrencia.via_ingreso == "") {
                    swal({
                        title: "Por favor ingrese via de ingreso!",
                        icon: "error",
                        buttons: true
                    });
                    return;
                }
                if (this.concurrencia.reingreso_hospitalización15días == "") {
                    swal({
                        title: "Por favor ingrese reingreso hospitalización!",
                        icon: "error",
                        buttons: true
                    });
                    return;
                }
                if (this.concurrencia.unidad_funcional == "") {
                    swal({
                        title: "Por favor ingrese unidad funcional!",
                        icon: "error",
                        buttons: true
                    });
                    return;
                }
                axios.post('/api/referencia/registrarCocurrencia', this.concurrencia).then(res => {
                        this.dialogConcurrencia = false;
                        this.clearConcurrencia();
                        this.$emit('updateReferencia', true);

                    });
            },
            clearConcurrencia() {
                for (const prop of Object.getOwnPropertyNames(this.concurrencia)) {
                    this.concurrencia[prop] = "";
                }
            },
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },
            concurrenciaRegistro(referencia) {
                this.clearConcurrencia();
                this.datosReferencia = referencia;
                this.cie10sReferencia.codigo = referencia.cie10s[0].Codigo_CIE10;
                this.cie10sReferencia.descripcion = referencia.cie10s[0].Descripcion;
                this.dialogConcurrencia = true;
                this.concurrencia.paciente_id = referencia.paciente_id;
                this.concurrencia.ips_basica = referencia.ips;
                this.concurrencia.id_concurrencia = referencia.id;
                this.concurrencia.ips_atencion = this.datosReferencia.name;
            },
            showHeader(header) {
                if (header == 'Anexo') {
                    if (this.internalProcess) return true
                    return false
                }
                return true;
            },
            showAnexo() {
                if (this.internalProcess) return true
                return false
            },
            showReference(reference) {
                this.paciente = {
                    id: reference.id,
                    Primer_Nom: reference.Primer_Nom,
                    Primer_Ape: reference.Primer_Ape,
                    Tipo_Doc: reference.Tipo_Doc,
                    Num_Doc: reference.Num_Doc,
                    Edad_Cumplida: reference.Edad_Cumplida,
                    Telefono: reference.Telefono,
                    Celular1: reference.Celular1,
                    Celular2: reference.Celular2,
                    Correo1: reference.Correo1,
                    Correo2: reference.Correo2,
                    rzeuz: reference.rzeuz,
                    paciente: reference.paciente_id,
                }
                this.data = {
                    Tipo_anexo: reference.Tipo_anexo,
                    cie10s: reference.cie10s,
                    adjuntos: reference.adjuntos,
                    tipo_solicitud: reference.tipo_solicitud,
                    referenciaid: reference.id,
                    cita_paciente: reference.referencia_id,

                }
                this.showReferenceDialog = true;
            },
            gestionar(gestion) {

                if (this.paciente.hasOwnProperty('rzeuz')) {
                    let data = {
                        rzeuz: this.paciente.rzeuz,
                        tipo_solicitud: gestion
                    }
                    axios.post(`/api/referencia/gestion/${this.paciente.id}`, data)
                        .then(res => {
                            this.showReferenceDialog = false;
                            this.goToBitacora(res.data.gestion);
                        })
                        .catch(err => console.log(err.response))
                }
            },
            sourceBitacora(data = null) {
                axios.post(`/api/referencia/gestion/${data.id}`, data)
                    .then(res => {
                        this.showReferenceDialog = false;
                        this.goToBitacora(res.data.gestion);
                    })
                    .catch(err => console.log(err.response))
            },
            goToBitacora(gestion) {

                this.$router.push(`/referencia/en_proceso/gestion/${gestion}`)
            },
            sedeProveedores() {
                axios.get('/api/sedeproveedore/sedeproveedores')
                    .then((res) => {
                        this.proveedores = res.data
                    })
                    .catch((err) => console.log(err));
            },
            async Anexo9(id,tipo){
                const pdf = {
                    type: 'Anexo',
                    id: id,
                    tipos: tipo,
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
            },
        },
        mounted() {
            console.log(this);
            this.sedeProveedores();
            this.fetchCie10s();
        }

    }

</script>

<style lang="scss" scoped>

</style>
