<template>
  <div class="">
    <div class="fb-post" :data-href="facebook.permalink_url" data-width="500" data-show-text="true">
      <blockquote :cite="facebook.permalink_url" class="fb-xfbml-parse-ignore">
        Publié par <a href="https://www.facebook.com/facebook/">Facebook</a> sur&nbsp;
        <a :href="facebook.permalink_url">jeudi 27 août 2015</a>
      </blockquote>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'facebook',
    props: ['facebook', 'position'],
    mounted() {
      /* eslint-disable */
      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.9";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    },
  };
</script>

<style lang="scss">

</style>
