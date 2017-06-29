<template>
  <div class="">
    <iframe type="text/html" :style="position"
    :src="'https://www.youtube.com/embed/' + youtube.items[0].id"
    frameborder="0"/>
  </div>
</template>

<script>
  export default {
    name: 'youtube',
    props: ['youtube', 'width', 'height', 'position'],
    mounted() {
    },
  };
</script>

<style lang="scss">

</style>
