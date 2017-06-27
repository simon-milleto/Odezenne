<template>
  <main>
    <header></header>
    <div class="o-container">
      <div>
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

  export default {
    name: 'blog',
    data() {
      return {
        posts: [],
      };
    },
    mounted() {
      this.loadPosts();
    },
    methods: {
      loadPosts() {
        axios.get(`${config.cmsEndpoint}/posts`)
        .then((response) => {
          console.log('GET Posts : ', response.data);
          this.posts = response.data;
        });
      },
    },
    components: {
      Post,
    },
  };
</script>
