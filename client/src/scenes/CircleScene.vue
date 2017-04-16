<template>
  <canvas ref="canvas"></canvas>
</template>

<script>
  import CircleScene from './CircleScene';

  export default {
    name: 'circle-scene',
    props: ['analyser', 'is-animating'],
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
      this.CircleScene = new CircleScene(
        this.$refs.canvas,
        this.analyser,
      );

      if (this.isAnimating) {
        this.startAnimation();
      }
    },
    methods: {
      startAnimation() {
        this.CircleScene.animateParticles();
      },
      stopAnimation() {
        this.CircleScene.stopAnimation();
      },
    },
    beforeDestroy() {
      this.stopAnimation();
    },
  };
</script>

<style>
</style>
