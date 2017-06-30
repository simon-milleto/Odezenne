<template>
    <transition name="fade">
        <div class=page-loader>
            <div class="loader-container">
                <img src="../assets/images/mouche.gif" class="loader" ref="loader">
                <span class="loading"><span>.</span><span>.</span><span>.</span></span>
            </div>
        </div>
    </transition>
</template>

<script>
  export default {
    name: 'loader',
    data() {
      return {
      };
    },
    mounted() {
      if (this.$refs.loader) {
        const loader = this.$refs.loader;
        loader.style.top = `${Math.floor((Math.random() * 50) + 1)}px`;
        loader.style.left = `${Math.floor((Math.random() * 150) + 1)}px`;
        this.moveFly();
      }
    },
    methods: {
      moveFly() {
        setTimeout(() => {
          if (this.$refs.loader) {
            const loader = this.$refs.loader;
            loader.style.top = `${Math.floor((Math.random() * 50) + 1)}px`;
            loader.style.left = `${Math.floor((Math.random() * 150) + 1)}px`;
            this.moveFly();
          }
        }, 400);
      },
    },
  };
</script>

<style scoped>
    .page-loader {
        display: flex;
        position: absolute;
        width: calc(100% - 100px);
        height: calc(100vh);
        top: 0;
        align-items: center;
        justify-content: center;
    }
    .loader-container {
        position: relative;
        height: 100px;
        width: 200px;
    }
    .loader-container span {
        width:100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: flex-end;
        margin-top: 50px;
    }
    .loader {
        transition: all 0.8s ease;
        width: 50px;
        animation-name: fly;
        animation-duration: 50s;
        position: absolute;
    }
    .loading {
        font-size: 1.1rem;
        font-weight: 700;
    }
    .loading span:nth-child(1),
    .loading span:nth-child(2),
    .loading span:nth-child(3)
    {
        animation-name: loading;
        animation-duration: 1s;
        animation-iteration-count: infinite;
        font-size: 1.1rem;
        width: 18px;
        padding-left: 15px;
        font-weight: 700;
    }
    .loading span:nth-child(1) {
        animation-delay: 200ms;
    }
    .loading span:nth-child(2) {
        animation-delay: 300ms;
    }
    .loading span:nth-child(3) {
        animation-delay: 400ms;
    }
    @keyframes loading {
        from {
            opacity: 1
        }
        to {
            opacity: 0
        }
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.3s;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

</style>
