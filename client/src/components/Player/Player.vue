<template>
  <div class="c-player" v-if="tracksLoaded">
    <div class="c-player__container">
      <div class="c-player__main-cover">
        <img class="c-player__cover" :src="mainCoverUrl">
      </div>
      <track-list :tracks="tracks" @currentTrackChanged="changeTrackInformation"></track-list>
    </div>
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

  .c-player__container {
    position: absolute;
    top: 25%;
    left: 50%;
    transform: translate(-50%, -25%);
  }

  .c-player__main-cover {
    width: 60vh;
    max-width: 50vw;
    transform: translateX(-25%);
    box-shadow: 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
  }

  .c-player__cover {
    width: 100%;
  }

  .c-player__track-information {
    position: absolute;
    top: 10%;
    right: 5vw;
    width: 900px;
    max-width: 40%;
    font-size: 3vw;
  }

  .c-player__artist {
    margin-bottom: 50px;
    color: $player-primary;
    font-size: 2.2em;
    font-weight: 800;
    letter-spacing: .5em;
    line-height: 1.2em;
    word-break: break-all;
    text-transform: uppercase;
  }

  .c-player__title {
    font-size: .8em;
    font-weight: 600;
    line-height: 1.8em;
    text-align: right;
  }
</style>
