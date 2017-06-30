<template>
  <main>
    <div class="o-container">
      <div>
        <loader v-if="!isLoaded"></loader>
        <post v-for="post in posts"
              :post="post"
              :key="post.id"></post>
      </div>
    </div>
  </main>
</template>

<script>
  import axios from 'axios';
  import config from '../config';

  import Post from '../components/Blog/Post';
  import Loader from '../components/Loader';

  export default {
    name: 'blog',
    data() {
      return {
        posts: [],
        isLoaded: false,
      };
    },
    mounted() {
      this.loadPosts();
    },
    methods: {
      loadPosts() {
        axios.get(`${config.cmsEndpoint}/posts`)
        .then((response) => {
          this.posts = response.data;
          this.isLoaded = true;
        });
      },
    },
    components: {
      Post,
      Loader,
    },
  };
</script>
