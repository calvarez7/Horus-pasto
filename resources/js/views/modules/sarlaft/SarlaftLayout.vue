<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "sarlaft",
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Sarlaft",
        items: [
           { name: "Formulario", path: "/sarlaft", can: 'sarlaft.enter'},
            { name: "Revision Sarlaft", path: "/sarlaft/revision", can: 'revisionSarlaft.enter'},
        ],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>