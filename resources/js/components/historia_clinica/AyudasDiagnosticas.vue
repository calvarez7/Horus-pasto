<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Urocultivo" v-model="ayudasDiagnosticas.urocultivo"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.urocultivo != null">
                    <v-textarea outlined name="input-7-4" label="Resultado Urocultivo" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Hemograma" v-model="ayudasDiagnosticas.hemograma"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.hemograma != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Glicema" v-model="ayudasDiagnosticas.glicema"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.glicema != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Hemoclasificación" v-model="ayudasDiagnosticas.hemoclasificación">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.hemoclasificación != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="VIH" v-model="ayudasDiagnosticas.vih"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.vih != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Antigeno Hep B" v-model="ayudasDiagnosticas.antigeno_hep_b"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.antigeno_hep_b != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="IgG Rubeola" v-model="ayudasDiagnosticas.igg_rubeola"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.igg_rubeola != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="toxo IgG" v-model="ayudasDiagnosticas.toxo_igg"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.toxo_igg != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="toxo IgM" v-model="ayudasDiagnosticas.toxo_igm"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.toxo_igm != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Ecografia obstétrica" v-model="ayudasDiagnosticas.ecografia_obstétrica">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.ecografia_obstétrica != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="VDRL" v-model="ayudasDiagnosticas.vdrl"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.vdrl != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="PTOG 75 gr (sem 24 y 28)"
                        v-model="ayudasDiagnosticas.ptog_75_gr_sem_24_28">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.ptog_75_gr_sem_24_28 != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Estreptococo B (sem 35 y 37"
                        v-model="ayudasDiagnosticas.estreptococo_b_sem_35_37">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.estreptococo_b_sem_35_37 != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Se ordena Gota gruesa mensual?"
                        v-model="ayudasDiagnosticas.ordena_gota_gruesa_mensual"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.ordena_gota_gruesa_mensual != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="CCV" v-model="ayudasDiagnosticas.ccv"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.ccv != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Calidad de la muestra CCV"
                        v-model="ayudasDiagnosticas.calidad_muestra_ccv">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Microorganismos en CCV"
                        v-model="ayudasDiagnosticas.microorganismos_en_ccv">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Otros hallazgos no neoplasicos en la CCV"
                        v-model="ayudasDiagnosticas.otros_hallazgos_neoplasicos_ccv"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Anormalidades en celulas escamosas en CCV"
                        v-model="ayudasDiagnosticas.anormalidades_celulas_escamosas_ccv"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Anormalidades en celulas glandulares"
                        v-model="ayudasDiagnosticas.anormalidades_celulas_glandulares"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="ADN VPH" v-model="ayudasDiagnosticas.adn_vph"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.adn_vph != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Colposcopia" v-model="ayudasDiagnosticas.colposcopia"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.colposcopia != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Biopsia de Cervix" v-model="ayudasDiagnosticas.biopsia_cervix">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.biopsia_cervix != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Mamografia" v-model="ayudasDiagnosticas.mamografia"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2 v-if="ayudasDiagnosticas.mamografia != null">
                    <v-textarea outlined name="input-7-4" label="Resultado mamografia" v-model="ayudasDiagnosticas.resultado_mamografia">
                    </v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Ecogradía de Mamas" v-model="ayudasDiagnosticas.ecogradia_mamas">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2 v-if="ayudasDiagnosticas.ecogradia_mamas != null">
                    <v-textarea outlined name="input-7-4" label="Resultado ecografía" v-model="ayudasDiagnosticas.resultado_ecografia">
                    </v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Biopsia de Mama" v-model="ayudasDiagnosticas.biopsia_mama"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md2 v-if="ayudasDiagnosticas.biopsia_mama != null">
                    <v-textarea outlined name="input-7-4" label="Resultado de Biopsia de Mama"
                        v-model="ayudasDiagnosticas.resultado_biopsia_mama">
                    </v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Hemoglobina Glicosilada"
                        v-model="ayudasDiagnosticas.hemoglobina_glicosilada">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.hemoglobina_glicosilada != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="IgG G Varicela" v-model="ayudasDiagnosticas.igg_g_varicela"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.igg_g_varicela != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Prueba de Embarazo" v-model="ayudasDiagnosticas.prueba_embarazo">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.prueba_embarazo != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Prueba Rapida Treponemica"
                        v-model="ayudasDiagnosticas.prueba_rapida_treponemica">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.prueba_rapida_treponemica != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Prueba Rapida HB" v-model="ayudasDiagnosticas.prueba_rapida_hb">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.prueba_rapida_hb != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Prueba Rapida VIH" v-model="ayudasDiagnosticas.prueba_rapida_vih">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.prueba_rapida_vih != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="TSH" v-model="ayudasDiagnosticas.tsh"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.tsh != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="PSA" v-model="ayudasDiagnosticas.psa"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.psa != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Colesterol HDL" v-model="ayudasDiagnosticas.colesterol_hdl"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.colesterol_hdl != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Colesterol Total" v-model="ayudasDiagnosticas.colesterol_total">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.colesterol_total != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Trigliceridos" v-model="ayudasDiagnosticas.trigliceridos"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.trigliceridos != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Uroanalisis" v-model="ayudasDiagnosticas.uroanalisis"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.uroanalisis != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Creatinina" v-model="ayudasDiagnosticas.creatinina"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.creatinina != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Albuminuria" v-model="ayudasDiagnosticas.albuminuria"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.albuminuria != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Creatinina/Albuminuria"
                        v-model="ayudasDiagnosticas.creatinina_albuminuria">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.creatinina_albuminuria != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Paratormona" v-model="ayudasDiagnosticas.paratormona"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.paratormona != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Fosforo" v-model="ayudasDiagnosticas.fosforo"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.fosforo != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="EKG" v-model="ayudasDiagnosticas.ekg"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.ekg != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1" label="Ecocardiograma" v-model="ayudasDiagnosticas.ecocardiograma"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.ecocardiograma != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md2>
                    <v-checkbox color="success" value="1"
                        label="Tamizaje Sangre oculta en materia fecal por inmunoquimica?"
                        v-model="ayudasDiagnosticas.tamizaje_sangre_culta_materia_inmunoquimica"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3 v-if="ayudasDiagnosticas.tamizaje_sangre_culta_materia_inmunoquimica != null">
                    <v-textarea outlined name="input-7-4" label="Resultado" v-model="ayudasDiagnosticas.resultado"></v-textarea>
                </v-flex>
            </v-layout>
        </v-container>
    </v-form>
</template>
<script>
    export default {
        name: "",
        data() {
            return {
                ayudasDiagnosticas: {}
            }
        },
    }

</script>
