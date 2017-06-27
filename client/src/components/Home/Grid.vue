<template>
    <div class="o-grid o-grid--guttered">
        <div class="o-grid__cell o-grid__cell--6/12">
          <youtube v-for="youtube in youtubeVideos"
                  :youtube="youtube"
                  :width="getRandomSize(300)"
                  :height="getRandomSize(100)"
                  class="element"></youtube>
          <tweet v-for="tweet in tweets"
                  :tweet="tweet"
                  :key="tweet.id"
                  class="element"></tweet>
        </div>
        <div class="o-grid__cell o-grid__cell--6/12">
          <tweet v-for="tweet in fanTweets"
                  :tweet="tweet"
                  class="element"></tweet>
          <soundcloud v-for="song in soundcloudSongs"
                  :soundcloud="song"
                  class="element"></soundcloud>
        </div>
    </div>
</template>

<script>
  import axios from 'axios';
  import config from '../../config';

  import Tweet from '../Social/Tweet';
  import Youtube from '../Social/Youtube';
  import Soundcloud from '../Social/Soundcloud';

  export default {
    name: 'grid',
    data() {
      return {
        tweets: [],
        youtubeVideos: [],
        fanTweets: [],
        soundcloudSongs: [],
        twitterFeedCount: 5,
        twitterFansCount: 5,
      };
    },
    mounted() {
      this.getData();
    },
    methods: {
      getData() {
        const twitterFeed = axios.get(`${config.apiEndpoint}/socials/twitter/feed?count=${this.twitterFeedCount}`);
        const fanTweets = axios.get(`${config.apiEndpoint}/socials/twitter/fans?count=${this.twitterFansCount}`);
        const youtubeVideos = axios.get(`${config.apiEndpoint}/socials/youtube`);
        const soundcloudSongs = axios.get(`${config.apiEndpoint}/socials/soundcloud`);

        Promise.all([twitterFeed, fanTweets, youtubeVideos, soundcloudSongs])
        .then(([respTwitterFeed, respFanTweets, respYoutubeVideos, respSoundcloudSongs]) => {
          this.tweets = respTwitterFeed.data;
          this.fanTweets = respFanTweets.data;
          this.youtubeVideos = respYoutubeVideos.data;
          this.soundcloudSongs = respSoundcloudSongs.data;
        });
      },
      getRandomSize(size) {
        return Math.floor((Math.random() * size) + 300);
      },
    },
    components: {
      Tweet,
      Youtube,
      Soundcloud,
    },
  };
</script>

<style lang="scss">

</style>
