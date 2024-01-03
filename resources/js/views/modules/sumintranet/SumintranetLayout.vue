<template>
  <v-layout justify-center>
    <v-flex shirnk xs12>
      <router-view />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "SumintranetLayout",
  components: {},
  mounted () {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Sumintranet",
        items: [
          { name: 'Inicio', path: '/sumintranet', can:'enter.sumintranet' },
          { name: 'Gestión Documental', path: '/gestionDocumental', can:'gestionDocumental.sumintranet' },
          { name: 'Directorio', path: '/directorio', can:'directorio.sumintranet' },
          //{ name: 'Certificados', path: '/certificados', can:'certificados.sumintranet' },
          { name: 'Reserva Espacios', path: '/reserveEspacio', can:'reservas'},
          { name: 'Administración', path: '/adminsuminranet', can:'enter.adminsuminranet' },
        //   { name: 'Hoja de vida', path: '/sumintranet/hojadevida', can:'enter.sumintranet'}
        ],
        enabled: true
      });
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
