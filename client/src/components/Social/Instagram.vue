<template>
  <div :style="position">
    <iframe scrolling="no" frameborder="no"
        :src="instagram.post_url + 'embed'"></iframe>
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
