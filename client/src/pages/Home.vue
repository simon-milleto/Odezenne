<template>
  <main>
    <div class="o-container">
        <home-filter></home-filter>
        <grid :filter="filter"></grid>
    </div>
  </main>
</template>

<script>
  import Grid from '../components/Home/Grid';
  import HomeFilter from '../components/Home/HomeFilter';

  export default {
    name: 'home',
    data() {
      return {
        filter: ['facebook', 'tweet', 'soundcloud', 'youtube', 'instagram'],
      };
    },
    mounted() {
      this.$on('changeFilter', (filter) => {
        this.filter = filter;
      });
    },
    components: {
      HomeFilter,
      Grid,
    },
  };
</script>

<style lang="scss">

</style>
