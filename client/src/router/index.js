import Vue from 'vue';
import Router from 'vue-router';

import Home from '../pages/Home';
import Ticketing from '../pages/Ticketing';

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
      path: '/billetterie',
      name: 'Billetterie',
      component: Ticketing,
    },
  ],
});
