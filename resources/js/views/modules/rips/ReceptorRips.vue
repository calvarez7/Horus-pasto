<template>
    <div>
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
        <v-card>
            <v-card-text>
                <v-container grid-list-md text-xs-center>
                    <v-layout row wrap>
                        <v-flex xs12 sm12>
                            <v-text-field
                                readonly
                                label="Archivo .JSON"
                                @click='onPickFile'
                                v-model='fileName'
                                prepend-icon="attach_file"
                                outline
                                clearable
                                @click:clear="clearMessage">
                            </v-text-field>
                            <!-- Hidden -->
                            <input
                                type="file"
                                style="display: none"
                                ref="fileInput"
                                accept="*/*"
                                @change="onFilePicked">
                        </v-flex>
                        <v-flex xs12 sm5 md2>
                            <v-btn class="mb-3" tile color="success" @click="guardar()">
                                Guardar RIPS
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>

        </v-card>
    </div>
</template>
<script>
export default {
    name: 'RipsJson',
    data() {
        return {
            file:null,
            fileName:'',
            preload:false
        }
    },
    methods:{
        guardar(){
            // console.log(this.$refs.fileInput.files[0]);
            this.preload = true;
            const formData = new FormData();
            formData.append('archivo', this.$refs.fileInput.files[0]);
            axios.post('/api/rips/guardar-rips-json',formData).then(res => {
                this.clearMessage();
                this.$refs.fileInput.value = null;
                this.$alerError(res.data);
                this.preload = false;
            }).catch(e => {
                this.$alerError('Error en la estructura del archivo')
                this.preload = false;
            })
        },
        onPickFile () {
            this.$refs.fileInput.click()
        },
        onFilePicked (event) {
            const files = event.target.files
            if (files[0] !== undefined) {
                for (const [key, value] of Object.entries(files)) {
                    this.fileName += value.name
                }
                // this.fileName = files[0].name

            } else {
                this.fileName = ''
            }
        },
        clearMessage () {
            this.fileName = "";
        },
    }
}
</script>
