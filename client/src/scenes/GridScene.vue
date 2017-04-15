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
        particlesPerXLine: 100,
        particlesPerYLine: 50,
        lineSpacement: 10,
      };
    },
    mounted() {
      this.GridScene = new GridScene(
        this.particlesPerXLine,
        this.particlesPerYLine,
        this.lineSpacement,
        this.$refs.canvas,
        this.analyser,
      );
    },
    beforeDestroy() {
      this.GridScene.stopAnimation();
    },
  };
</script>

<style>
</style>
