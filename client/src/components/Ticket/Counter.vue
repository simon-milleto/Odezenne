<template>
  <div class="c-counter">
    <button class="c-counter__button" @click="minus">-</button>
    <span class="c-counter__value">{{ number }}</span>
    <button class="c-counter__button" @click="plus">+</button>
  </div>
</template>

<script>
  export default {
    name: 'counter',
    props: ['number'],
    methods: {
      minus() {
        this.$emit('onMinus');
      },
      plus() {
        this.$emit('onPlus');
      },
    },
  };
</script>

<style lang="scss">
  @import '../../assets/scss/01_settings/typography';

  .c-counter {
    display: flex;
    align-items: center;
  }

  .c-counter__button {
    font-size: $font-big;
    line-height: $font-big;
  }

  .c-counter__value {
    margin: 0 15px;
    font-size: $font-big;
    font-weight: bold;
  }
</style>
