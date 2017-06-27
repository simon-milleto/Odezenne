<template>
  <main id="list_tweets">
    <tweet v-for="tweet in tweets"
            :tweet="tweet"
            :key="tweet.id"></tweet>
    <tweet v-for="tweet in fanTweets"
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
        fanTweets: [],
        twitterFeedCount: 5,
        twitterFansCount: 5,
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
        axios.get(`${config.apiEndpoint}/socials/twitter/feed?count=${this.twitterFeedCount}`)
          .then((response) => {
            this.tweets = response.data;
          });
      },
      getFansTweets() {
        axios.get(`${config.apiEndpoint}/socials/twitter/fans?count=${this.twitterFansCount}`)
          .then((response) => {
            this.fanTweets = response.data;
          });
      },
    },
  };
</script>

<style lang="scss">

</style>
