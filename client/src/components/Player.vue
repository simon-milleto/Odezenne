<template>
  <div class="c-player">
    <button class="c-player__action" v-on:click="playPause()">></button>
    <span class="c-player__title">{{currentTrack.title}}</span>
    <span class="c-player__time-bar">
      <span class="c-player__current-time">{{formattedCurrentTime}}</span>
      <span class="c-player__bar"><span class="c-player__progress" v-bind:style="{ width: barRatio + '%' }"></span></span>
      <span class="c-player__total-time">{{currentTrack.formattedDuration}}</span>
    </span>
  </div>
</template>

<script>
  import axios from 'axios';
  import Player from 'audio-player';
  import * as SC from 'soundcloud';

  import config from '../config';

  const player = new Player();

  export default {
    name: 'player',
    data() {
      return {
        tracks: [],
        currentTrack: '',
        formattedCurrentTime: '0:00',
        barRatio: 0,
      };
    },
    beforeCreate() {
      SC.initialize({
        client_id: 'aeb5b3f63ac0518f8362010439a77ca1',
      });

      axios.get(`${config.apiEndpoint}/tracks`).then((response) => {
        this.tracks = response.data;
      });

      SC.get('/tracks/305230900').then((track) => {
        this.currentTrack = track;
        const separator = track.stream_url.indexOf('?') === -1 ? '?' : '&';
        this.currentTrack.formattedUrl = `${track.stream_url}${separator}client_id=aeb5b3f63ac0518f8362010439a77ca1`;
        this.currentTrack.isPlaying = true;
        this.currentTrack.formattedDuration = this.formatTime(this.currentTrack.duration / 1000);
        this.currentTrack.currentTimeInSeconds = 0;
        this.startCurrentTime();
        player.play(this.currentTrack.formattedUrl);
      });
    },
    methods: {
      playPause() {
        player.playPause(this.currentTrack.formattedUrl);

        if (this.currentTrack.isPlaying) {
          clearInterval(this.currentTimeInterval);
          this.currentTrack.isPlaying = false;
        } else {
          this.startCurrentTime();
          this.currentTrack.isPlaying = true;
        }
      },
      formatTime(time) {
        const durationMinutes = Math.floor(time / 60);
        const durationSeconds = Math.floor(time - (durationMinutes * 60));
        return `${durationMinutes}:${durationSeconds <= 9 ? '0' : ''}${durationSeconds}`;
      },
      startCurrentTime() {
        this.currentTimeInterval = setInterval(() => {
          this.currentTrack.currentTimeInSeconds += 1;
          this.formattedCurrentTime =
            this.formatTime(this.currentTrack.currentTimeInSeconds);
          this.barRatio =
            (this.currentTrack.currentTimeInSeconds / (this.currentTrack.duration / 1000)) * 100;

          if (this.currentTrack.currentTimeInSeconds === this.currentTrack.duration / 1000) {
            clearInterval(this.currentTimeInterval);
          }
        }, 1000);
      },
    },
  };
</script>

<style lang="scss">
  .c-player {
    position: fixed;
    bottom: 0;
    display: flex;
    align-items: center;
    width: 100%;
    padding: 10px 20px;
    background-color: black;
    color: white;
  }

  .c-player__action {
    color: white;
  }

  .c-player__time-bar {
    display: inline-flex;
    align-items: center;
    margin: 0 50px;
  }

  .c-player__current-time,
  .c-player__total-time {
    margin: 0 10px;
  }

  .c-player__bar {
    display: flex;
    width: 1200px;
    background-color: white;
  }

  .c-player__progress {
    width: 10%;
    height: 3px;
    margin: 3px;
    background-color: black;
    transition: width 1s linear;
  }
</style>
