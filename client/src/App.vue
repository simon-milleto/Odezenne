<template>
  <div>
    <o-header></o-header>
    <router-view></router-view>
  </div>
</template>

<script>
  import OHeader from './components/Header';

  export default {
    name: 'app',
    components: {
      OHeader,
    },
  };
</script>

<style lang="scss">
  @import '~reset-css/_reset';
  @import 'assets/scss/03_generic/button';
  @import 'assets/scss/04_elements/body';
</style>
