<template>
  <div id="app">
    <router-view></router-view>
    <player></player>
  </div>
</template>

<script>
  import Player from './components/Player';

  export default {
    name: 'app',
    components: {
      Player,
    },
  };
</script>

<style>
  body {
    margin: 0;
  }

  #app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
  }
</style>
