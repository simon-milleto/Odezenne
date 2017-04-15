<template>
  <div class="c-player" :class="{'c-player--is-visible': isPlaying}">
    <audio controls="controls" ref="audio" v-on:timeupdate="updateProgress"></audio>
    <div class="c-player__controls">
      <button class="c-player__play" v-on:click="playPause"><span class="material-icons">play_arrow</span></button>
      <div class="c-player__progress" ref="progress" v-on:click="changeTime">
        <div class="c-player__progress-bar" v-bind:style="{ width: progress + '%' }"></div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';

  import config from '../config';

  export default {
    name: 'player',
    data() {
      return {
        soundcloudClientId: '',
        tracks: [],
        isPlaying: false,
        progress: 0,
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
          this.initializeAudio();
        });
      },
      formatTracks(tracks) {
        this.tracks = [];

        tracks.forEach((track) => {
          const formattedTrack = {
            title: track.title,
            author: track.artist,
            url: `${track.stream_url}?client_id=${this.soundcloudClientId}`,
            pic: track.artwork_url ? track.artwork_url : '',
          };

          this.tracks.push(formattedTrack);
        });
      },
      initializeAudio() {
        const audio = this.$refs.audio;
        audio.crossOrigin = 'anonymous';
        audio.src = this.tracks[0].url;
      },
      playPause() {
        const audio = this.$refs.audio;
        if (audio.paused) {
          audio.play();
        } else {
          audio.pause();
        }
      },
      updateProgress() {
        const audio = this.$refs.audio;
        this.progress = (audio.currentTime / audio.duration) * 100;
      },
      changeTime(event) {
        const audio = this.$refs.audio;
        const mouseX = event.pageX;
        const progress = this.$refs.progress.getBoundingClientRect();
        const barX = progress.left;
        const percent = progress.width / (mouseX - barX);
        audio.currentTime = audio.duration / percent;
      },
    },
  };
</script>

<style lang="scss">
  .c-player {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;

    audio {
      display: none;
    }
  }

  .c-player__controls {
    display: flex;
  }

  .c-player__play {
    padding: 10px;
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;

    &:focus {
      outline: none;
    }
  }

  .c-player__progress {
    flex-grow: 1;
  }

  .c-player__progress-bar {
    height: 100%;
    background-color: rgba(255, 255, 255, .1);
    transition: width .5s linear;
  }
</style>
