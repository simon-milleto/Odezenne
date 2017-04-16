<template>
  <div class="c-player" v-if="tracksLoaded">
    <div class="c-player__main-cover">
      <img class="c-player__cover" :src="mainCoverUrl">
    </div>
    <track-list :tracks="tracks" @currentTrackChanged="changeTrackInformation"></track-list>
    <div class="c-player__track-information">
      <h2 class="c-player__artist">{{artistName}}</h2>
      <h3 class="c-player__title">{{trackName}}</h3>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';
  import config from '../../config';

  import TrackList from './TrackList';

  export default {
    name: 'player',
    components: {
      TrackList,
    },
    data() {
      return {
        soundcloudClientId: '',
        tracks: [],
        tracksLoaded: false,
        mainCoverUrl: '',
        artistName: '',
        trackName: '',
      };
    },
    beforeCreate() {
      axios.get(`${config.apiEndpoint}/settings/soundcloud-client-id`)
        .then((response) => {
          this.soundcloudClientId = response.data;
          this.getTracks();
        });
    },
    methods: {
      getTracks() {
        axios.get(`${config.apiEndpoint}/tracks`)
          .then((response) => {
            this.formatTracks(response.data);
          });
      },
      formatTracks(tracks) {
        this.tracks = [];

        tracks.forEach((track, index) => {
          const formattedTrack = {
            id: track.id,
            title: track.title,
            artist: track.artist,
            url: `${track.stream_url}?client_id=${this.soundcloudClientId}`,
            artwork: track.artwork_url ? track.artwork_url : '',
            isPlaying: false,
            index,
          };

          this.tracks.push(formattedTrack);
          this.tracksLoaded = true;
        });
      },
      changeTrackInformation(track) {
        this.mainCoverUrl = track.artwork;
        this.artistName = track.artist;
        this.trackName = track.title;
      },
    },
  };
</script>

<style lang="scss">
  @import '../../assets/scss/01_settings/colors';

  .c-player {
    font-family: 'Work Sans', sans-serif;
  }

  .c-player__main-cover {
    position: absolute;
    top: 15%;
    left: 35%;
    width: 35vw;
    height: 35vw;
    transform: translateX(-50%);
  }

  .c-player__cover {
    width: 100%;
  }

  .c-player__track-information {
    position: absolute;
    top: 15%;
    right: 50px;
    max-width: 40%;
    text-align: right;
  }

  .c-player__artist {
    margin-bottom: 50px;
    color: $player-primary;
    font-size: 8rem;
    font-weight: 800;
    letter-spacing: 1.5rem;
    line-height: 12rem;
  }

  .c-player__title {
    font-size: 3rem;
    font-weight: 600;
    line-height: 5rem;
  }
</style>
