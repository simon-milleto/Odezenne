<template>
  <div class="c-player" v-if="tracks.length > 0" :class="{'c-player--is-visible': isPlaying}">
    <a-player :music="tracks" ref="player" :autoplay="true" :mode="'random'" v-on:playing="onPlaying"></a-player>
    <button class="c-player__button" v-on:click="previous">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 22 20" style="enable-background:new 0 0 24 24;" xml:space="preserve"><g><path d="M12.116,18.316c0.445-0.243,0.711-0.69,0.711-1.197v-2.823l6.883,4.453c0.228,0.147,0.482,0.221,0.738,0.221   c0.223,0,0.446-0.056,0.653-0.169c0.445-0.243,0.711-0.69,0.711-1.197V6.395c0-0.507-0.266-0.954-0.711-1.197   c-0.446-0.243-0.966-0.222-1.391,0.052l-6.884,4.452V6.88c0-0.507-0.266-0.954-0.711-1.197c-0.445-0.242-0.965-0.223-1.391,0.052   l-7.915,5.119c-0.39,0.252-0.623,0.68-0.623,1.144c0,0.464,0.233,0.892,0.623,1.144l7.915,5.12   C11.151,18.54,11.671,18.559,12.116,18.316z M2.904,12c0-0.223,0.107-0.421,0.295-0.542l7.915-5.119   c0.201-0.13,0.448-0.14,0.659-0.025c0.211,0.115,0.337,0.327,0.337,0.567v3.481c0,0.131,0.072,0.252,0.187,0.315   c0.116,0.063,0.256,0.057,0.366-0.014l7.437-4.81c0.202-0.13,0.449-0.14,0.659-0.025c0.211,0.115,0.337,0.327,0.337,0.567v11.21   c0,0.24-0.126,0.452-0.337,0.567c-0.211,0.115-0.458,0.105-0.659-0.025l-7.436-4.81c-0.059-0.038-0.127-0.057-0.195-0.057   c-0.059,0-0.118,0.014-0.171,0.044c-0.116,0.063-0.187,0.183-0.187,0.315v3.482c0,0.24-0.126,0.452-0.337,0.567   c-0.211,0.115-0.458,0.106-0.659-0.025l-7.915-5.12C3.011,12.42,2.904,12.223,2.904,12z"/></g></svg>
    </button>
    <button class="c-player__button" v-on:click="next">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 22 20" style="enable-background:new 0 0 24 24;" xml:space="preserve"><g><path d="M13.275,18.264l7.915-5.12c0.39-0.252,0.622-0.679,0.623-1.144c0-0.464-0.233-0.892-0.623-1.144l-7.915-5.119   c-0.426-0.275-0.946-0.295-1.391-0.052c-0.445,0.243-0.711,0.69-0.711,1.197v2.822L4.289,5.251   C3.864,4.976,3.344,4.956,2.898,5.198C2.453,5.44,2.187,5.888,2.187,6.395v11.21c0,0.507,0.266,0.954,0.711,1.197   c0.207,0.113,0.431,0.169,0.653,0.169c0.256,0,0.51-0.074,0.738-0.221l6.883-4.453v2.823c0,0.507,0.266,0.954,0.711,1.197   C12.329,18.559,12.849,18.54,13.275,18.264z M20.801,12.542l-7.915,5.12c-0.201,0.131-0.448,0.14-0.659,0.025   c-0.211-0.115-0.337-0.327-0.337-0.567v-3.482c0-0.131-0.072-0.252-0.187-0.315c-0.054-0.029-0.112-0.044-0.171-0.044   c-0.068,0-0.135,0.019-0.195,0.057L3.9,18.147c-0.202,0.13-0.449,0.14-0.659,0.025c-0.211-0.115-0.337-0.327-0.337-0.567V6.395   c0-0.24,0.126-0.452,0.337-0.567C3.451,5.713,3.698,5.722,3.9,5.853l7.437,4.81c0.11,0.071,0.25,0.077,0.366,0.014   c0.116-0.063,0.187-0.183,0.187-0.315V6.88c0-0.24,0.126-0.452,0.337-0.567c0.211-0.115,0.459-0.105,0.659,0.025l7.915,5.119   c0.188,0.121,0.295,0.319,0.295,0.542C21.096,12.223,20.989,12.42,20.801,12.542z"/></g></svg>
    </button>
  </div>
</template>

<script>
  import axios from 'axios';
  import VueAplayer from 'vue-aplayer';

  import config from '../config';

  export default {
    name: 'player',
    data() {
      return {
        soundcloudClientId: '',
        tracks: [],
        tracksAreLoaded: false,
        isPlaying: false,
      };
    },
    beforeCreate() {
      axios.get(`${config.apiEndpoint}/settings/soundcloud-client-id`).then((response) => {
        this.soundcloudClientId = response.data;
        this.getTracks();
      });
    },
    methods: {
      getTracks() {
        axios.get(`${config.apiEndpoint}/tracks`).then((response) => {
          this.formatTracks(response.data);
        });
      },
      formatTracks(tracks) {
        this.tracksAreLoaded = false;
        this.tracks = [];

        tracks.forEach((track) => {
          const formattedTrack = {
            title: track.title,
            author: track.artist,
            url: `${track.stream_url}?client_id=${this.soundcloudClientId}`,
            pic: track.artwork_url ? track.artwork_url : '',
          };

          this.tracks.push(formattedTrack);
          this.tracksAreLoaded = true;
        });
      },
      onPlaying() {
        this.isPlaying = true;
      },
      previous() {
        const aplayer = this.$refs.player.control;
        if (aplayer.playIndex === 0) {
          aplayer.setMusic(this.tracks.length - 1);
        } else {
          aplayer.setMusic(aplayer.playIndex - 1);
        }
      },
      next() {
        const aplayer = this.$refs.player.control;
        if (aplayer.playIndex === this.tracks.length - 1) {
          aplayer.setMusic(0);
        } else {
          aplayer.setMusic(aplayer.playIndex + 1);
        }
      },
    },
    components: {
      'a-player': VueAplayer,
    },
  };
</script>

<style lang="scss">
  .c-player {
    position: fixed;
    bottom: 0;
    display: flex;
    align-items: flex-end;
    width: 100%;
    background-color: #263238;
    color: white;
    transform: translateY(100%);
    transition: transform .5s ease;

    &.c-player--is-visible {
      transform: translateY(0);
    }
  }

  .c-player__button {
    margin: 4px 10px;
    color: #fff;

    &:last-of-type {
      margin-right: 50px;
    }

    svg {
      height: 20px;
      fill: #fff;
    }
  }

  .aplayer.aplayer-withlist {
    width: 100%;
    margin: 0;
    border-radius: 0;
    box-shadow: none;

    .aplayer-info {
      border-bottom: 0 !important;
    }

    .aplayer-controller .aplayer-volume-wrap,
    .aplayer-controller .aplayer-icon-mode,
    .aplayer-controller .aplayer-icon-menu,
    .aplayer-list {
      display: none !important;
    }

    .aplayer-pic::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,.3);
    }
  }
</style>
