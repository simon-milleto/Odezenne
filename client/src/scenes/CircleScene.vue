<template>
  <canvas ref="canvas"></canvas>
</template>

<script>
  import CircleScene from './CircleScene';

  export default {
    name: 'circle-scene',
    props: ['analyser'],
    mounted() {
      /* eslint-disable no-new */
      this.CircleScene = new CircleScene(this.$refs.canvas, this.analyser);
    },
    beforeDestroy() {
      this.CircleScene.stopAnimation();
    },
  };
</script>

<style>
</style>
