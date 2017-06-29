<template>
    <div>
        <header>
            <img v-if="!opened" @click="burgerAction" class="burger" src="../assets/images/burger.png">
            <img v-else="opened" @click="burgerAction" class="burger" src="../assets/images/burger-close.png">
            <svg :class="{goWhite: opened}" id="logo_header" data-name="Calque 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 659.99 195.95"><title>odezenne_logotype</title><path d="M120.2,180.05v50.72l-16.37-50.72H90v80.69h11.41V210l16.37,50.72h13.95V180.05Zm308.76,0v50.72l-16.37-50.72H398.76v80.69h11.41V210l16.37,50.72h13.95V180.05Zm278.55,0v80.69h36.65v-10H718.92V225.47H750V215.32H718.92V190.2h25.24V180.05Z" transform="translate(-90 -64.79)"/><path d="M744.16,74.93V64.79H707.51v80.69h36.65v-10H718.92V110.2H750V100.06H718.92V74.93ZM593.59,64.79H551.86V74.93H581.6l-29.74,59.48v11.06h41.73v-10H563.85l29.74-59.59ZM437.94,74.93V64.79H401.29v80.69h36.65v-10H412.7V110.2h31.07V100.06H412.7V74.93Zm-180.89,0h9.45c8.18,0,9.34,8.19,9.34,10V125.3a9.93,9.93,0,0,1-9.8,10.14h-9Zm9.45,70.54a26,26,0,0,0,10.14-2.42c6.92-3.46,10.72-9.8,10.72-17.75V85a24.58,24.58,0,0,0-2.54-9.8c-3.34-6.68-9.91-10.37-18.33-10.37H245.65v80.69ZM101.41,85c0-1.73,1.27-10,9.45-10s9.34,8.19,9.34,10V125.3c0,1.84-1.15,10.14-9.34,10.14s-9.45-8.41-9.45-10.14Zm9.45,60.51c8.41,0,15-3.69,18.33-10.38a24.56,24.56,0,0,0,2.54-9.8V85a24.58,24.58,0,0,0-2.54-9.8c-3.34-6.68-9.91-10.37-18.33-10.37s-15,3.69-18.33,10.37A24.58,24.58,0,0,0,90,85V125.3a24.56,24.56,0,0,0,2.54,9.8c3.34,6.69,9.91,10.38,18.33,10.38" transform="translate(-90 -64.79)"/></svg>
            <cart></cart>
        </header>
        <nav :class="{active: opened}">
            <router-link class="router-link" :to="{ name: 'Home' }" exact @click.native="burgerAction">Giga Timeline</router-link>
            <router-link class="router-link" :to="{ name: 'Checkout' }" @click.native="burgerAction">Shop</router-link>
            <router-link class="router-link" :to="{ name: 'Billetterie' }" @click.native="burgerAction">Billeterie</router-link>
            <router-link class="router-link" :to="{ name: 'Billetterie' }" @click.native="burgerAction">Historique</router-link>
            <router-link class="router-link" :to="{ name: 'Blog' }" @click.native="burgerAction">Blog</router-link>
            <div class="RS">
                <a href="#"><img src="../assets/images/twitter.png"></a>
                <a href="#"><img src="../assets/images/facebook-logo.png"></a>
                <a href="#"><img src="../assets/images/instagram.png"></a>
                <a href="#"><img src="../assets/images/youtube.png"></a>
                <a href="#"><img src="../assets/images/soundcloud.png"></a>
            </div>
        </nav>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import cart from './Ticket/Cart';

  export default {
    name: 'OHeader',
    components: {
      cart,
    },
    data() {
      return {
        opened: false,
      };
    },
    computed: {
      ...mapGetters({
        tickets: 'cartTickets',
      }),
    },
    methods: {
      burgerAction() {
        this.opened = !this.opened;
      },
    },
  };
</script>

<style scoped>
    header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        height: 200px;
        padding: 30px;
        z-index: 12;
    }
    nav {
        transition: all 0.6s ease;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
        background-color: rgba(0,0,0,0.9);
        position: absolute;
        top: -550px;
        width: 100%;
        z-index: -1;
        height: 550px;
        z-index: 10;
    }
    nav>a {
        color: #fff;
        text-decoration: none;
        padding: 17px 0;
        text-transform: uppercase;
    }
    nav a {
        opacity: 0;
    }
    nav.active {
        top: 0;
    }
    nav.active a {
        animation-name: nav-appear;
        animation-duration: 500ms;
        animation-delay: 250ms;
        animation-fill-mode: forwards;
    }
    header>div {
        transition: all 0.6s ease;
    }
    .goWhite {
        color: #fff;
    }
    .RS {
        display: flex;
        justify-content: center;
        padding: 30px 0px 12px 0px;
    }
    .RS img {
        width: 20px;
    }
    .RS>a {
        color: #fff;
        text-decoration: none;
        padding: 10px 15px;
    }
    .RS>a:hover {
        cursor: pointer;
    }
    #logo_header, .panier svg {
        transition: all 0.6s ease;
        height: 100%;
        fill: black;
    }
    #logo_header.goWhite, .panier svg.goWhite {
        fill: #fff;
    }
    .router-link {
        position: relative;
    }
    .router-link::before {
        transition: all 0.4s ease;
        display: block;
        content: '';
        height: 1px;
        position: absolute;
        top:0;
        bottom:2px;
        margin: auto;
        width:0;
        background-color: #fff;
    }
    .router-link.router-link-active::before, .router-link:hover::before {
        width:100%;
    }
    .burger {
        width: 24px;
    }
    .panier {
        display: flex;
        align-items: center;
    }
    .panier span {
        padding-right: 8px;
    }
    .burger:hover, .panier:hover {
        cursor: pointer;
    }
    .panier svg {
        width: 20px;
    }
    .panier.goWhite svg {
        fill: #fff;
    }
    @keyframes nav-appear {
        0% { opacity: 0 }
        100% { opacity: 1 }
    }
</style>
