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
            <v-card>
                <v-card-text class="headline cyan" style="color:white">
                    <h4 style="color:white">EXCEL A .JSON</h4>
                </v-card-text>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs10 sm10>
                            <v-text-field
                                readonly
                                label="Archivo Excel"
                                @click='onPickFile(1)'
                                v-model='fileName'
                                prepend-icon="attach_file"
                                outline
                                clearable
                                @click:clear="clearMessage()">
                            </v-text-field>
                            <!-- Hidden -->
                            <input
                                type="file"
                                style="display: none"
                                ref="fileInput"
                                accept="*/*"
                                @change="onFilePicked($event,1)">
                        </v-flex>
                        <v-flex xs1 px-2>
                            <v-btn color="warning" :loading="loading" round @click="conversor(1)">Descargar
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
        <hr>
        <br>
        <v-flex xs12>
            <v-card>
                <v-card-text class="headline green" style="color:white">
                    <h4 style="color:white">.JSON A EXCEL</h4>
                </v-card-text>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs10 sm10>
                            <v-text-field
                                readonly
                                label="Archivo .JSON"
                                @click='onPickFile(2)'
                                v-model='fileName2'
                                prepend-icon="attach_file"
                                outline
                                clearable
                                @click:clear="clearMessage()">
                            </v-text-field>
                            <!-- Hidden -->
                            <input
                                type="file"
                                style="display: none"
                                ref="fileInput2"
                                accept="*/*"
                                @change="onFilePicked($event,2)">
                        </v-flex>
                        <v-flex xs1 px-2>
                            <v-btn color="warning" :loading="loading" round @click="conversor(2)">Descargar
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>

    </v-layout>
</template>
<script>
export default {
    name: 'Conversores',
    data: () => {
        return {
            loading:false,
            fileName:'',
            fileName2:'',
            preload:false
        }
    },
    methods:{
        onPickFile (tipo) {
            if(tipo === 1){
                this.$refs.fileInput.click();
            }else{
                this.$refs.fileInput2.click();
            }
        },
        onFilePicked (event,tipo) {
            const files = event.target.files
            if (files[0] !== undefined) {
                for (const [key, value] of Object.entries(files)) {
                    if(tipo === 1){
                        this.fileName += value.name
                    }else{
                        this.fileName2 += value.name
                    }
                }
            } else {
                if(tipo === 1){
                    this.fileName = "";
                }else{
                    this.fileName2 = ""
                }
            }
        },
        clearMessage () {
            this.fileName = "";
            this.fileName2 = ""
        },
        conversor(tipo){
            this.preload = true;
            const formData = new FormData();
            if(tipo === 1){
                formData.append('archivo', this.$refs.fileInput.files[0]);
            }else{
                formData.append('archivo', this.$refs.fileInput2.files[0]);
            }
            axios.post('/api/rips/conversor/'+tipo,formData,{
                responseType: (tipo === 1?'text':'blob')
            }).then(res => {
                switch (tipo){
                    case 1:
                        const jsn = JSON.stringify(res.data);
                        let blob = new Blob([jsn], {
                            type: "application/json"
                        });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement('a');
                        a.download = 'ArchivoGenerado.json';
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        break;
                    case 2:
                        let blob2 = new Blob([res.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        });
                        let url2 = window.URL.createObjectURL(blob2);
                        let a2 = document.createElement('a');

                        a2.download = `RegistroExcel.xlsx`;
                        a2.href = url2;
                        document.body.appendChild(a2);
                        a2.click();
                        document.body.removeChild(a2);
                        break;
                }
                this.$refs.fileInput.value = null;
                this.$refs.fileInput2.value = null;
                this.clearMessage();
                this.loading = false;
                this.preload = false;
            }).catch(e => {
                this.clearMessage();
                this.$refs.fileInput.value = null;
                this.$refs.fileInput2.value = null;
                this.preload = false;
                this.loading = false;
                this.$alerError("Error en la estructura del archivo.");
            })
        }
    }
}
</script>
