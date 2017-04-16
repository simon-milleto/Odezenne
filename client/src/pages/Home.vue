<template>
  <main>
    <div class="c-scene" v-if="analyserLoaded">
      <grid-scene v-show="showGrid" :analyser="audioAnalyser" :is-animating="showGrid"></grid-scene>
      <circle-scene v-show="!showGrid" :analyser="audioAnalyser" :is-animating="!showGrid"></circle-scene>
    </div>
  </main>
</template>

<script>
  import GridScene from '../scenes/GridScene';
  import CircleScene from '../scenes/CircleScene.vue';

  import EventBus from '../eventBus';

  export default {
    name: 'home',
    data() {
      return {
        showGrid: true,
        analyserLoaded: false,
        audioAnalyser: '',
      };
    },
    mounted() {
      EventBus.$on('audioAnalyser:loaded', (audioAnalyser) => {
        this.audioAnalyser = audioAnalyser;
        this.analyserLoaded = true;
      });

      document.addEventListener('keyup', (event) => {
        event.preventDefault();
        if (event.keyCode === 32) {
          this.toggleGrid();
        }
      });
    },
    components: {
      GridScene,
      CircleScene,
    },
    methods: {
      toggleGrid() {
        this.showGrid = !this.showGrid;
      },
    },
  };
</script>

<style lang="scss">
  .c-scene {
    width: 100vw;
    height: 100vh;

    canvas {
      display: block;
    }
  }
</style>
