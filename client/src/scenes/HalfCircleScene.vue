<template>
  <canvas ref="canvas"></canvas>
</template>

<script>
  import HalfCircleScene from './HalfCircleScene';

  export default {
    name: 'half-circle-scene',
    props: ['analyser', 'is-animating'],
    data() {
      return {
        radius: 3,
        numberOfCircles: 5,
        numberOfParticles: 500,
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
      this.HalfCircleScene = new HalfCircleScene(
        this.$refs.canvas,
        this.analyser,
        this.radius,
        this.numberOfCircles,
        this.numberOfParticles,
      );

      if (this.isAnimating) {
        this.startAnimation();
      }
    },
    methods: {
      startAnimation() {
        this.HalfCircleScene.animateParticles();
      },
      stopAnimation() {
        this.HalfCircleScene.stopAnimation();
      },
    },
    beforeDestroy() {
      this.stopAnimation();
    },
  };
</script>

<style>
</style>
