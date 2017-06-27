<template>
  <main id="list_tweets">
    <tweet v-for="tweet in tweets"
            :tweet="tweet"
            :key="tweet.id"></tweet>
    <tweet v-for="tweet in fan_tweets"
            :tweet="tweet"
            :key="tweet.id"></tweet>
  </main>
</template>

<script>
  import axios from 'axios';
  import config from '../config';

  import Tweet from '../components/Social/Tweet';

  export default {
    name: 'home',
    data() {
      return {
        tweets: [],
        fan_tweets: [],
      };
    },
    mounted() {
      this.getTwitterFeed();
      this.getFansTweets();
    },
    components: {
      Tweet,
    },
    methods: {
      getTwitterFeed() {
        axios.get(`${config.apiEndpoint}/socials/twitterFeed?count=5`)
          .then((response) => {
            this.tweets = response.data;
          });
      },
      getFansTweets() {
        axios.get(`${config.apiEndpoint}/socials/fanTweets?count=5`)
          .then((response) => {
            this.fan_tweets = response.data;
          });
      },
    },
  };
</script>

<style lang="scss">

</style>
