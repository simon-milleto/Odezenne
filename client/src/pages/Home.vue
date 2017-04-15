<template>
  <div class="c-scene">
    <grid-scene></grid-scene>
  </div>
</template>

<script>
  import GridScene from '../scenes/GridScene';

  export default {
    name: 'home',
    components: {
      GridScene,
    },
  };
</script>

<style lang="scss">
  .c-scene {
    width: 100vw;
    height: 100vh;
  }
</style>
