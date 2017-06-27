<template>
  <div>
<iframe :id="'sc-widget_' + soundcloud.id" width="100%" height="166" scrolling="no" frameborder="no"
        :src="'https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F' + soundcloud.id + '&show_artwork=true'"></iframe>
  </div>
</template>

<script>

  export default {
    name: 'soundcloud',
    props: ['soundcloud'],
    mounted() {
    },
  };
</script>

<style lang="scss">

</style>
