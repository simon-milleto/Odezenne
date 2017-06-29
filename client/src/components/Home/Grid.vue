<template>
    <div class="o-grid">
      <transition-group mode="out-in" name="fade" class="o-grid">
        <div v-for="post in posts" 
            :key="getPostId(post)" 
            v-show="showPost(post.type)"
            class="o-grid__cell--6/12 element-wrapper">
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
      </transition-group>
      <div class="o-grid__cell--12/12">
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
    props: ['filter'],
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
        const twitterFeed = axios.get(`${config.apiEndpoint}/socials/twitter/feed${this.twitterPaginationParam}`);
        const fanTweets = axios.get(`${config.apiEndpoint}/socials/twitter/fans${this.twitterFanPaginationParam}`);
        const youtubeVideos = axios.get(`${config.apiEndpoint}/socials/youtube${this.youtubePaginationParam}`);
        const soundcloudSongs = axios.get(`${config.apiEndpoint}/socials/soundcloud`);
        this.loading = true;

        Promise.all([twitterFeed, fanTweets, youtubeVideos, soundcloudSongs])
          .then(([respTwitterFeed, respFanTweets, respYoutubeVideos, respSoundcloudSongs]) => {
            const temp = [];

            if (respTwitterFeed.data.valid) {
              temp.push(...respTwitterFeed.data.tweets);
              this.tweets = respTwitterFeed.data.tweets;
              this.twitterPaginationParam = `?max_id=${this.tweets[this.tweets.length - 1].id}`;
            }
            if (respFanTweets.data.valid) {
              temp.push(...respFanTweets.data.tweets);
              this.fanTweets = respFanTweets.data.tweets;
              this.twitterFanPaginationParam = `?max_id=${this.fanTweets[this.fanTweets.length - 1].id}`;
            }
            if (respYoutubeVideos.data.valid) {
              temp.push(...respYoutubeVideos.data.videos);
              this.youtubeVideos = respYoutubeVideos.data.videos;
              this.youtubePaginationParam = `?page=${respYoutubeVideos.data.nextPage}`;
            }
            if (respSoundcloudSongs.data.valid) {
              temp.push(...respSoundcloudSongs.data.tracks);
              this.soundcloudSongs = respSoundcloudSongs.data.tracks;
            }

            this.shufflePosts(temp);

            if (this.tweets.length > 0 ||
                this.fanTweets.length > 0 ||
                this.youtubeVideos.length > 0 ||
                this.soundcloudSongs.length > 0) {
              this.firstLoaded = true;
              this.$emit('loaded');
            }

            this.loading = false;
            if (this.$refs.infiniteLoading) {
              this.$refs.infiniteLoading.$emit('$InfiniteLoading:loaded');
            }
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
      showPost(type) {
        return this.filter.indexOf(type) !== -1;
      },
      getPostId(post) {
        if (post.type === 'youtube') {
          return post.items[0].id;
        }

        return post.id;
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

<style lang="scss" scoped>
  .element-wrapper{
    position:relative;
    min-height: 300px;
    max-width:100%;
    
    .element{
      max-width:90%;
    }
  }

  .fade-enter-active, .fade-leave-active {
    transition: opacity .4s
  }
  .fade-enter, .fade-leave-to {
    opacity: 0
  }
</style>
