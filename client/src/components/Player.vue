<template>
  <div class="c-player">
    <button class="c-player__play" v-on:click="playPause()">></button>
    <span class="c-player__title">{{currentTrack.title}}</span>
    <span class="c-player__time-bar">
      <span class="c-player__current-time">{{formattedCurrentTime}}</span>
      <span class="c-player__bar"><span class="c-player__progress" v-bind:style="{ width: barRatio + '%' }"></span></span>
      <span class="c-player__total-time">{{currentTrack.formattedDuration}}</span>
    </span>
    <button class="c-player__previous" v-if="itemsInPlaylist > 1" v-on:click="playPrevious()"><<</button>
    <button class="c-player__next" v-if="itemsInPlaylist > 1" v-on:click="playNext()">>></button>
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
        soundcloudClientId: '',
        tracks: [],
        currentTrack: '',
        formattedCurrentTime: '0:00',
        barRatio: 0,
        itemsInPlaylist: 0,
        playlistItemNumber: 1,
      };
    },
    beforeCreate() {
      SC.initialize({
        client_id: 'aeb5b3f63ac0518f8362010439a77ca1',
      });

      axios.get(`${config.apiEndpoint}/settings/soundcloud-client-id`).then((response) => {
        this.soundcloudClientId = response.data;
      });

      axios.get(`${config.apiEndpoint}/tracks`).then((response) => {
        this.tracks = this.formatTracks(response.data);
        this.itemsInPlaylist = response.data.length;
        this.playTrack();
      });
    },
    methods: {
      playPause() {
        player.playPause(this.currentTrack.formattedStreamUrl);

        if (this.currentTrack.isPlaying) {
          clearInterval(this.currentTimeInterval);
          this.currentTrack.isPlaying = false;
        } else {
          this.startCurrentTime();
          this.currentTrack.isPlaying = true;
        }
      },
      playNext() {
        clearInterval(this.currentTimeInterval);
        if (this.playlistItemNumber >= this.itemsInPlaylist) {
          this.playlistItemNumber = 1;
        } else {
          this.playlistItemNumber += 1;
        }
        this.playTrack();
      },
      playPrevious() {
        clearInterval(this.currentTimeInterval);
        if (this.playlistItemNumber <= 1) {
          this.playlistItemNumber = 6;
        } else {
          this.playlistItemNumber -= 1;
        }
        this.playTrack();
      },
      playTrack() {
        this.currentTrack = this.tracks[this.playlistItemNumber - 1];
        this.reset();
        this.currentTrack.isPlaying = true;
        this.startCurrentTime();
        player.play(this.currentTrack.formattedStreamUrl);
      },
      reset() {
        this.currentTrack.currentTimeInSeconds = 0;
        this.barRatio = 0;
        this.formattedCurrentTime = '0:00';
      },
      formatTracks(tracks) {
        const formattedTracks = [];

        tracks.forEach((track) => {
          const formattedTrack = track;
          formattedTrack.formattedStreamUrl = `${track.stream_url}?client_id=${this.soundcloudClientId}`;
          formattedTrack.formattedDuration = this.formatTime(track.total_time);
          formattedTracks.push(formattedTrack);
        });
        return tracks;
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
            (this.currentTrack.currentTimeInSeconds / this.currentTrack.total_time) * 100;

          if (this.currentTrack.currentTimeInSeconds === this.currentTrack.total_time) {
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

  .c-player__play,
  .c-player__previous,
  .c-player__next {
    margin: 0 10px;
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
    /*transition: width 1s linear;*/
  }
</style>
