<template>
  <div v-html="tweet.html" :style="position">
  </div>
</template>

<script>

  export default {
    name: 'tweet',
    props: ['tweet', 'position'],
    mounted() {
      window.twttr.widgets.load();
    },
  };
</script>

<style lang="scss">

</style>
