<template>
  <div class="c-player__progress">
    <div class="c-player__times">
      <span class="c-player__time">{{formattedCurrent}}</span>
      <span class="c-player__time">{{formattedTotal}}</span>
    </div>
    <div class="c-player__progress-bar" ref="progress" @click="changeTime">
      <div class="c-player__progress-bar-filler" :style="{ width: progress + '%' }"></div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'progress',
    props: ['total', 'current'],
    computed: {
      progress: {
        cache: false,
        get() {
          return (this.current / this.total) * 100;
        },
      },
      formattedTotal: {
        cache: false,
        get() {
          return this.formatTime(this.total);
        },
      },
      formattedCurrent: {
        cache: false,
        get() {
          return this.formatTime(this.current);
        },
      },
    },
    methods: {
      changeTime(event) {
        const mouseX = event.pageX;
        const progress = this.$refs.progress.getBoundingClientRect();
        const barX = progress.left;
        const percent = progress.width / (mouseX - barX);

        this.$emit('changeTime', percent);
      },
      formatTime(time) {
        let formattedMinutes = '00';
        let formattedSeconds = '00';

        if (time) {
          const minutes = Math.floor(time / 60);
          formattedMinutes = minutes < 10 ? `0${minutes}` : minutes;
          const seconds = Math.floor(time - (minutes * 60));
          formattedSeconds = seconds < 10 ? `0${seconds}` : seconds;
        }

        return `${formattedMinutes}:${formattedSeconds}`;
      },
    },
  };
</script>

<style lang="scss">
  @import '../../assets/scss/01_settings/colors';

  .c-player__progress {
    position: relative;
    margin-top: 50px;
  }

  .c-player__times {
    display: flex;
    justify-content: space-between;
    padding-bottom: 25px;
  }

  .c-player__time {
    padding: 5px;
    color: $player-neutral;
  }

  .c-player__progress-bar {
    position: absolute;
    bottom: -20px;
    left: -20px;
    right: -20px;
    cursor: pointer;
  }

  .c-player__progress-bar-filler {
    width: 0;
    height: 40px;
    background-color: $player-neutral;
    box-shadow: 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
  }
</style>
