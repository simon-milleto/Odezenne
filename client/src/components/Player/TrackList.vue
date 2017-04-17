<template>
  <div class="c-player__track-list">
    <audio class="c-player__audio" controls="controls" ref="audio" @timeupdate="updateProgress" @ended="playNextTrack"></audio>
    <div class="c-player__tracks">
      <button class="c-player__scroll-tracks c-player__scroll-tracks--top" v-if="showUp" @click="moveTrackListUp">
        <span class="c-player__scroll-tracks-icon">
          <svg x="0px" y="0px" viewBox="0 0 62.5 34.4" style="enable-background:new 0 0 62.5 34.4;" xml:space="preserve">
            <g transform="translate(0,-952.36218)">
              <path d="M31.3,952.4l-3.3,2.8l-28,24l6.5,7.6l24.8-21.2L56,986.7l6.5-7.6l-28-24C34.5,955.1,31.3,952.4,31.3,952.4z"/>
            </g>
          </svg>
        </span>
      </button>
      <track-item v-for="track in mutableTracks"
                  v-if="track.index < itemsInList && track.index >= 0"
                  :track="track"
                  :isCurrent="track.id === currentTrackId"
                  :key="track.id"
                  @playTrack="playTrack"
                  @pauseTrack="pauseTrack"
                  @changeTrack="changeTrack"></track-item>
      <button class="c-player__scroll-tracks c-player__scroll-tracks--bottom" v-if="showDown" @click="moveTrackListDown">
        <span class="c-player__scroll-tracks-icon">
          <svg x="0px" y="0px" viewBox="0 0 62.5 34.4" style="enable-background:new 0 0 62.5 34.4;" xml:space="preserve">
            <g transform="translate(0,-952.36218)">
              <path d="M31.2,986.7l3.3-2.8l28-24l-6.5-7.6l-24.8,21.2L6.5,952.4L0,960l28,24C28,984,31.2,986.7,31.2,986.7z"/>
            </g>
          </svg>
        </span>
      </button>
    </div>
    <progress-bar :total="totalTime" :current="currentTime" @changeTime="changeTime"></progress-bar>
  </div>
</template>

<script>
  import Track from './Track';
  import Progress from './Progress';
  import AudioAnalyserService from '../../services/AudioAnalyserService';
  import EventBus from '../../eventBus';

  export default {
    name: 'track-list',
    props: ['tracks'],
    components: {
      TrackItem: Track,
      ProgressBar: Progress,
    },
    data() {
      return {
        mutableTracks: this.tracks,
        currentTrackId: '',
        isPlaying: false,
        currentTime: '',
        totalTime: '',
        showUp: false,
        showDown: false,
        itemsInList: 6,
      };
    },
    mounted() {
      const audioAnalyserService = new AudioAnalyserService();
      this.audioAnalyser = audioAnalyserService.initializeAnalyser();

      EventBus.$emit('audioAnalyser:loaded', this.audioAnalyser);

      this.changeTrack(this.mutableTracks[0].id);
      this.showDown = this.mutableTracks.length > this.itemsInList;

      document.addEventListener('keyup', (event) => {
        event.preventDefault();
        if (event.keyCode === 13) {
          if (this.isPlaying === true) {
            this.pauseTrack();
          } else {
            this.playTrack();
          }
        }
      });
    },
    methods: {
      getCurrentTrack() {
        const resolvedTrack = this.mutableTracks.filter(track => track.id === this.currentTrackId);
        return resolvedTrack[0];
      },
      playNextTrack() {
        let currentIndex = 0;
        let nextIndex = 0;
        this.mutableTracks.forEach((track, index) => {
          if (track.id === this.currentTrackId) {
            currentIndex = index;
          }
        });
        nextIndex = currentIndex + 1 === this.mutableTracks ? 0 : currentIndex + 1;
        this.changeTrack(this.mutableTracks[nextIndex].id);
      },
      playTrack() {
        const audio = this.$refs.audio;
        const currentTrack = this.getCurrentTrack();
        audio.play();
        currentTrack.isPlaying = true;
        this.isPlaying = true;
      },
      pauseTrack() {
        const audio = this.$refs.audio;
        const currentTrack = this.getCurrentTrack();
        audio.pause();
        currentTrack.isPlaying = false;
        this.isPlaying = false;
      },
      changeTrack(trackId) {
        const audio = this.$refs.audio;
        this.currentTrackId = trackId;
        this.mutableTracks.forEach((track) => {
          const changedTrack = track;
          if (changedTrack.id === this.currentTrackId) {
            audio.crossOrigin = 'anonymous';
            audio.src = changedTrack.url;
            this.playTrack();
            this.$emit('currentTrackChanged', changedTrack);
          } else {
            changedTrack.isPlaying = false;
          }
        });
      },
      updateProgress() {
        const audio = this.$refs.audio;
        this.currentTime = audio.currentTime;
        this.totalTime = audio.duration;
      },
      changeTime(percent) {
        const audio = this.$refs.audio;
        audio.currentTime = audio.duration / percent;
      },
      moveTrackListDown() {
        this.showUp = false;
        this.showDown = false;
        this.mutableTracks.forEach((track) => {
          const mutableTrack = track;
          mutableTrack.index -= 1;
          if (mutableTrack.index < 0) {
            this.showUp = true;
          }
          if (mutableTrack.index > this.itemsInList) {
            this.showDown = true;
          }
        });
      },
      moveTrackListUp() {
        this.showUp = false;
        this.showDown = false;
        this.mutableTracks.forEach((track) => {
          const mutableTrack = track;
          mutableTrack.index += 1;
          if (mutableTrack.index < 0) {
            this.showUp = true;
          }
          if (mutableTrack.index > this.itemsInList) {
            this.showDown = true;
          }
        });
      },
    },
  };
</script>

<style lang="scss">
  @import '../../assets/scss/01_settings/colors';

  .c-player__track-list {
    position: absolute;
    top: 10%;
    left: -25%;
    width: 350px;
    background-color: $player-primary;
    transform: translateX(-40%);
    box-shadow: 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
  }

  .c-player__audio {
    display: none;
  }

  .c-player__tracks {
    position: relative;
  }

  .c-player__scroll-tracks {
    position: absolute;
    left: 50%;
    padding: 8px 25px;
    background-color: $player-neutral;
    box-shadow: 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);

    &.c-player__scroll-tracks--top {
      transform: translate(-50%, -75%);
    }

    &.c-player__scroll-tracks--bottom {
      transform: translate(-50%, -25%);
    }

    .c-player__scroll-tracks-icon {
      display: block;
      width: 20px;

      path {
        fill: $player-primary;
      }
    }
  }
</style>
