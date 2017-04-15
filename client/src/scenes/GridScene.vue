<template>
  <canvas ref="canvas"></canvas>
</template>

<script>
  import GridScene from './GridScene';

  export default {
    name: 'grid-scene',
    props: ['analyser'],
    data() {
      return {
        particlesPerLine: 150,
        lineSpacement: 10,
      };
    },
    mounted() {
      /* eslint-disable no-new */
      this.GridScene =
        new GridScene(this.particlesPerLine, this.lineSpacement, this.$refs.canvas, this.analyser);
    },
    beforeDestroy() {
      this.GridScene.stopAnimation();
    },
  };
</script>

<style>
</style>
