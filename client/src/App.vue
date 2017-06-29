<template>
  <div>
    <o-header v-if="currentRoute != '/login'"></o-header>
    <transition name="fade" mode="out-in">
      <router-view></router-view>
    </transition>
  </div>
</template>

<script>
  import OHeader from './components/Header';

  export default {
    name: 'app',
    data() {
      return {
        currentRoute: this.$route.path,
      };
    },
    watch: {
      $route(to, from) {
        this.currentRoute = from.path;
      },
    },
    components: {
      OHeader,
    },
  };
</script>

<style lang="scss">
  @import '~reset-css/_reset';
  @import 'assets/scss/03_generic/button';
  @import 'assets/scss/04_elements/body';

  .fade-enter-active, .fade-leave-active {
    transition: opacity .4s
  }
  .fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0
  }
</style>
