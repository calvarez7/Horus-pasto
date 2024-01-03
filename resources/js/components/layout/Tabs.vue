<template>
    <v-tabs 
        v-if="menu.enabled"
        height="60"
        fixed-tabs
        class="elevation-5"
        color="secondary"
        slider-color="primary"
        show-arrows>
        <v-flex 
            align-self-center
            pl-5>
            <h2> {{ menu.title }} </h2>
        </v-flex>
            
        <vSpacer />
        <template v-for="item in menu.items">
            <v-tab
                :key="item.name"
                :to="item.path"
                v-if="can(item.can)">
                {{ item.name }}
            </v-tab>
        </template>
    </v-tabs>
</template>

<script>
    import { mapGetters } from 'vuex';
    export default {
        computed:{
            ...mapGetters(['can']),
            menu(){
                return this.$store.state.menu;
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>