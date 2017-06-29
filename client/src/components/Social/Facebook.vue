<template>
  <div class="">
    <iframe scrolling="no" frameborder="0" allowTransparency="true" width="500" height="523" style="border:none;overflow:hidden"
            :src="'https://www.facebook.com/plugins/post.php?href=' + facebook.permalink_url" ></iframe>
  </div>
</template>

<script>
  export default {
    name: 'facebook',
    props: ['facebook', 'position'],
    mounted() {
    },
  };
</script>

<style lang="scss">

</style>
