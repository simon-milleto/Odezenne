<template>
  <div class="c-accordion"
       :class="{ 'c-accordion--is-open': isOpen }">
      <slot name="header"></slot>
      <button class="c-accordion__toggle" @click.prevent="toggle">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40; fill:#fff;" xml:space="preserve">
          <path d="M39.7,25.5H25.5v14.2H14.5V25.5H0.3V14.5h14.2V0.3h11.1v14.2h14.2V25.5z"/>
        </svg>
      </button>
    <main class="c-accordion__content-container" :style="{ maxHeight: contentHeight + 'px' }">
      <div class="c-accordion__content" ref="content">
        <slot name="content"></slot>
      </div>
    </main>
  </div>
</template>

<script>
  export default {
    name: 'accordion',
    data() {
      return {
        isOpen: false,
      };
    },
    computed: {
      contentHeight() {
        return this.isOpen ? this.$refs.content.offsetHeight : 0;
      },
    },
    methods: {
      toggle() {
        this.isOpen = !this.isOpen;
        this.$emit('onToggle');
      },
    },
  };
</script>

<style lang="scss">
  @import '../../assets/scss/01_settings/typography';
  @import '../../assets/scss/01_settings/colors';

  .c-accordion {
    &.c-accordion--is-open {
      .c-accordion__toggle {
        transform: rotate(45deg);
      }
    }
  }

  .c-accordion__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .c-accordion__toggle {
    width: 25px;
    height: 25px;
    transition: .3s ease;
    position: absolute;
    right: 25px;
    margin-top: -25px;
  }

  .c-accordion__content-container {
    overflow: hidden;
    transition: .3s ease;
  }

  .c-accordion__toggle path{
    fill: $_white;
  }

</style>
