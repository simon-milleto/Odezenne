<template>
  <router-view></router-view>
    <!--<player></player>-->
</template>

<script>
  import Player from './components/Player/Player';

  export default {
    name: 'app',
    components: {
      Player,
    },
  };
</script>

<style lang="scss">
  @import '~reset-css/_reset';
  @import 'assets/scss/03_generic/button';
  @import 'assets/scss/03_generic/fonts';
  @import 'assets/scss/04_elements/body';
</style>
