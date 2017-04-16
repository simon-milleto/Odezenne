<template>
  <div class="c-player__track" @click="changeTrackStatus">
    <button class="c-player__action"><span class="c-player__play-icon" :class="{ 'c-player__play-icon--is-playing': track.isPlaying }"></span></button>
    <span class="c-player__list-title">{{track.title}}</span>
  </div>
</template>

<script>
  export default {
    name: 'track',
    props: ['track', 'is-current'],
    methods: {
      changeTrackStatus() {
        if (this.track.isPlaying) {
          this.$emit('pauseTrack');
        } else if (this.isCurrent) {
          this.$emit('playTrack');
        } else {
          this.$emit('changeTrack', this.track.id);
        }
      },
    },
  };
</script>

<style lang="scss">
  @import '../../assets/scss/01_settings/colors';

  .c-player__track {
    display: flex;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, .25);
    cursor: pointer;
  }

  .c-player__action {
    margin-right: 20px;
  }

  .c-player__play-icon {
    width: 15px;
    height: 15px;
    display: flex;
    clip-path: polygon(0% 0%, 100% 50%, 100% 50%, 0% 100%);
    cursor: pointer;

    &:active {
      &:before, &:after {
        transition: none;
      }
    }

    &:before, &:after {
      content: '';
      display: block;
      width: 50%;
      background-color: $player-neutral;
    }

    &:after {
      margin-left: 0;
    }

    &, &:after {
      transition: all 250ms ease;
    }

    &.c-player__play-icon--is-playing {
      clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%);

      &:after {
        margin-left: 12.5%;
      }
    }
  }

  .c-player__list-title {
    color: $player-neutral;
    line-height: 1.6rem;
  }
</style>
