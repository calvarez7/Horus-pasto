<template>
  <v-layout justify-center>
    <v-flex shirnk xs12>
      <router-view />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "humanaLayout",
  components: {},
  mounted () {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Portal Gestion Humana",
        items: [
          { name: 'Administracion de Expedientes', path: '/gestionhumana', can:'hojadevida.enter' },
          { name: 'Perfil de cargo', path: '/gestionhumana/cargos', can:'perfildecargo.enter' },
          { name: 'Contratacion', path: '/gestionhumana/contratacion', can:'contratos.enter' },
          { name: 'Induccion y reinduccion', path: '/gestionhumana/induccion', can:'induccion.enter' },
          { name: 'Procesos Diciplinarios', path: '/gestionhumana/procesosdiciplinarios', can:'procesosdiciplinarios.enter' },
          { name: 'Bienestar Laboral', path: '/gestionhumana/bienestar', can:'bienestarlaboral.enter' },


        ],
        enabled: true
      });
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
