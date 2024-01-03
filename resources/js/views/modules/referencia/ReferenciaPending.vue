<template>
    <div>
        <v-layout row wrap>
            <v-flex xs12>
                <RouterView @referencia="counter" />
            </v-flex>
        </v-layout>
        <v-layout row wrap justify-center>
            <v-flex xs12>
                <v-bottom-nav
                    class="buttom-nav-anexo"
                    color="primary"
                    :value="true"
                    fixed
                    dark>
                    <v-btn dark :to="anexo_2" exact>
                        <v-badge color="red">
                            <template v-if="count._a2" v-slot:badge>
                                <span>{{ count._a2 }}</span>
                            </template>
                            <span>ANEXO 2</span>
                        </v-badge>
                    </v-btn>

                    <v-btn dark :to="anexo_3" exact>
                        <v-badge color="red">
                            <template v-if="count._a3" v-slot:badge>
                                <span>{{ count._a3 }}</span>
                            </template>
                            <span>ANEXO 3</span>
                        </v-badge>
                    </v-btn>

                    <v-btn dark :to="anexo_9" exact>
                        <v-badge color="red">
                            <template v-if="count._a9" v-slot:badge>
                                <span>{{ count._a9 }}</span>
                            </template>
                            <span>ANEXO 9</span>
                        </v-badge>
                    </v-btn>
                </v-bottom-nav>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    export default {
        name: 'ReferenciaPending',
        data: () => {
            return {
                anexo_2: '/referencia/pendientes/anexo_2',
                anexo_3: '/referencia/pendientes/anexo_3',
                anexo_9: '/referencia/pendientes/anexo_9',
                count: {
                    count_a2: 0,
                    count_a3: 0,
                    count_a9: 0
                }
            }
        },
        methods:{
            counter(anexo){
                this.count = {
                    _a2: anexo._a2,
                    _a3: anexo._a3,
                    _a9: anexo._a9
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .buttom-nav-anexo {
        width: 30% !important;
        margin: 0 35% !important;
        border-radius: 40px;
        /* border-radius: 25px 25px 0 0; */
    }
</style>