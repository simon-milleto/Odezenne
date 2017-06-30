<template>
  <div>
    <iframe scrolling="no" frameborder="no" :style="position"
        :src="instagram.post_url + 'embed'" height="430px"></iframe>
  </div>
</template>

<script>
  export default {
    name: 'instagram',
    props: ['instagram', 'position'],
    mounted() {
    },
  };
</script>

<style lang="scss">

</style>
