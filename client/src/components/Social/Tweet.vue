<template>
  <div v-html="tweet.html">
  </div>
</template>

<script>

  export default {
    name: 'tweet',
    props: ['tweet'],
    mounted() {
      window.twttr.widgets.load();
    },
  };
</script>

<style lang="scss">

</style>
