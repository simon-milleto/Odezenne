import Vue from 'vue';
import Router from 'vue-router';

import Player from '../pages/Player';
import Home from '../pages/Home';
import Ticketing from '../pages/Ticketing';
import Checkout from '../pages/Checkout';

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
    },
    {
      path: '/player',
      name: 'Player',
      component: Player,
    },
    {
      path: '/billetterie',
      name: 'Billetterie',
      component: Ticketing,
    },
    {
      path: '/checkout',
      name: 'Checkout',
      component: Checkout,
    },
  ],
});
