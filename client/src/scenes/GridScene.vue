<template>
  <canvas ref="canvas"></canvas>
</template>

<script>
  import GridScene from './GridScene';

  export default {
    name: 'grid-scene',
    props: ['analyser', 'is-animating'],
    data() {
      return {
        particlesPerXLine: 100,
        particlesPerYLine: 50,
        lineSpacement: 10,
      };
    },
    /* Can't use arrow function here because of problem with watch and this binding */
    /* eslint-disable object-shorthand */
    /* eslint-disable func-names */
    watch: {
      isAnimating: function (newValue) {
        if (newValue) {
          this.startAnimation();
        } else {
          this.stopAnimation();
        }
      },
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
    methods: {
      startAnimation() {
        this.GridScene.animateParticles();
      },
      stopAnimation() {
        this.GridScene.stopAnimation();
      },
    },
    beforeDestroy() {
      this.stopAnimation();
    },
  };
</script>

<style>
</style>
