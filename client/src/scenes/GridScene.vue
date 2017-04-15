<template>
  <canvas ref="canvas"></canvas>
</template>

<script>
  import GridScene from './GridScene';

  export default {
    name: 'grid-scene',
    data() {
      return {
        particlesPerLine: 150,
        lineSpacement: 10,
      };
    },
    mounted() {
      /* eslint-disable no-new */
      new GridScene(this.particlesPerLine, this.lineSpacement, this.$refs.canvas);
    },
  };
</script>

<style>
</style>
