<template>
  <main>
    <loader v-if="!loaded"></loader>
    <div class="o-container">
        <home-filter v-if="loaded"></home-filter>
        <grid :filter="filter" @loaded="isLoaded"></grid>
    </div>
  </main>
</template>

<script>
  import Grid from '../components/Home/Grid';
  import HomeFilter from '../components/Home/HomeFilter';
  import Loader from '../components/Loader';

  export default {
    name: 'home',
    data() {
      return {
        filter: ['facebook', 'tweet', 'soundcloud', 'youtube', 'instagram'],
        loaded: false,
      };
    },
    methods: {
      isLoaded() {
        this.loaded = true;
      },
    },
    mounted() {
      this.$on('changeFilter', (filter) => {
        this.filter = filter;
      });
    },
    components: {
      HomeFilter,
      Grid,
      Loader,
    },
  };
</script>

<style lang="scss">
</style>
