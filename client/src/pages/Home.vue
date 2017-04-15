<template>
  <main>
    <div class="c-scene" v-if="analyserLoaded">
      <grid-scene v-if="showGrid" :analyser="audioAnalyser"></grid-scene>
      <circle-scene v-else="" :analyser="audioAnalyser"></circle-scene>
    </div>
  </main>
</template>

<script>
  import GridScene from '../scenes/GridScene';
  import CircleScene from '../scenes/CircleScene.vue';

  import AudioAnalyserService from '../services/AudioAnalyserService';

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
      const audioAnalyserService = new AudioAnalyserService();
      this.audioAnalyser = audioAnalyserService.initializeAnalyser();
      this.analyserLoaded = true;

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
  }
</style>
