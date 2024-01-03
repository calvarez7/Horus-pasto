<template>
  <v-layout justify-center>
    <v-flex shirnk xs12>
      <router-view />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "EmpalmeLayout",
  components: {},
  mounted () {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Modulo de Empalme",
       items: [
           { name: "Registro", path: "/empalme/registro", can: 'empalme.enter' }
        ],
        enabled: true
      });
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
