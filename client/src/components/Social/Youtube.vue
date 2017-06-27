<template>
  <iframe type="text/html" :width="width" :height="height"
  :src="'https://www.youtube.com/embed/' + youtube.items[0].id"
  frameborder="0"/>
</template>

<script>
  export default {
    name: 'youtube',
    props: ['youtube', 'width', 'height'],
    mounted() {
    },
  };
</script>

<style lang="scss">

</style>
