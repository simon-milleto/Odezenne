<template>
    <div class="o-grid">
      <div v-for="post in posts" class="o-grid__cell--6/12 element-wrapper">
        <youtube v-if="post.type === 'youtube'"
                :youtube="post"
                :position="getPosition() + '' + getRandomSize()"
                class="element"></youtube>
        <tweet v-if="post.type === 'tweet'"
                :tweet="post"
                :position="getPosition()"
                class="element"></tweet>
        <soundcloud v-if="post.type === 'soundcloud'"
                :soundcloud="post"
                :position="getPosition()"
                class="element"></soundcloud>
      </div>
      <div>
        <infinite-loading v-if="firstLoaded" :on-infinite="onInfinite" ref="infiniteLoading"></infinite-loading>
      </div>
    </div>
</template>

<script>
  import axios from 'axios';
  import { shuffle } from 'lodash';
  import InfiniteLoading from 'vue-infinite-loading';
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
        posts: [],
        youtubePaginationParam: '',
        twitterPaginationParam: '',
        twitterFanPaginationParam: '',
        firstLoaded: false,
        loading: false,
      };
    },
    mounted() {
      this.getData();
    },
    methods: {
      getData() {
        console.log('getData');
        const twitterFeed = axios.get(`${config.apiEndpoint}/socials/twitter/feed${this.twitterPaginationParam}`);
        const fanTweets = axios.get(`${config.apiEndpoint}/socials/twitter/fans${this.twitterFanPaginationParam}`);
        const youtubeVideos = axios.get(`${config.apiEndpoint}/socials/youtube${this.youtubePaginationParam}`);
        const soundcloudSongs = axios.get(`${config.apiEndpoint}/socials/soundcloud`);
        this.loading = true;
        Promise.all([twitterFeed, fanTweets, youtubeVideos, soundcloudSongs])
        .then(([respTwitterFeed, respFanTweets, respYoutubeVideos, respSoundcloudSongs]) => {
          this.tweets = respTwitterFeed.data;
          this.twitterPaginationParam = `?max_id=${respTwitterFeed.data[respTwitterFeed.data.length - 1].id}`;
          this.fanTweets = respFanTweets.data;
          this.twitterFanPaginationParam = `?max_id=${respFanTweets.data[respFanTweets.data.length - 1].id}`;
          this.youtubeVideos = respYoutubeVideos.data.videos;
          this.youtubePaginationParam = `?page=${respYoutubeVideos.data.nextPage}`;
          this.soundcloudSongs = respSoundcloudSongs.data;

          const temp = [...respTwitterFeed.data,
            ...respFanTweets.data,
            ...respYoutubeVideos.data.videos,
            ...respSoundcloudSongs.data];
          this.shufflePosts(temp);

          this.firstLoaded = true;
          this.loading = false;
          this.$refs.infiniteLoading.$emit('$InfiniteLoading:loaded');
        });
      },
      onInfinite() {
        if (!this.loading) {
          this.getData();
        }
      },
      shufflePosts(temp) {
        const shuffledTemp = shuffle(temp);
        this.posts.push(...shuffledTemp);
      },
      getRandomSize() {
        const width = Math.floor((Math.random() * 100) + 500);
        const height = (width * 9) / 16;
        return `width:${width}px;height:${height}px;`;
      },
      getPosition() {
        const top = Math.floor((Math.random() * 10) + 1);
        const left = Math.floor((Math.random() * 10) + 1);
        return `margin-top:${top}%;margin-left:${left}%;`;
      },
    },
    components: {
      Tweet,
      Youtube,
      Soundcloud,
      InfiniteLoading,
    },
  };
</script>

<style lang="scss">
.element-wrapper{
  position:relative;
  min-height: 300px;
  max-width:100%;
  .element{
    max-width:90%;
  }
}
</style>
