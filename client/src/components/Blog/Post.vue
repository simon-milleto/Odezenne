<template>
  <div class="post-center">
    <div class="c-post">
      <div class="c-post__content" v-html="post.content.rendered"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'post',
  props: ['post'],
};
</script>

<style lang="scss" scoped>
  @import '../../assets/scss/01_settings/colors';
  @import '../../assets/scss/01_settings/typography';
  @import '../../assets/scss/05_objects/grid';

.c-post {
  padding:20px 5px;
  border-bottom: 1px dotted #CCCCCC;
  width:40%;
  margin:auto;
  line-height:25px;
}

.c-post img {
  width:auto;
  height:auto;
}

.c-post p {
  clear:both;
}

img.alignright {
  clear:right;
  float:right;
  margin:1em 0 1em 1em;
}
img.alignleft {
  float:left;
  margin:0 1em 1em 1em;
}
img.aligncenter {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
a img.alignright {
  float:right;
  margin:0 0 1em 1em;
}
a img.alignleft {
  float:left;
  margin:0 1em 1em 0;
}
a img.aligncenter {
  display: block;
  margin-left: auto;
  margin-right: auto
}

</style>
