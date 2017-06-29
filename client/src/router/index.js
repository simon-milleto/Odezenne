import Vue from 'vue';
import Router from 'vue-router';
import axios from 'axios';

import config from '../config';
import Store from '../store/index';

import Home from '../pages/Home';
import Login from '../pages/Login';
import Blog from '../pages/Blog';
import Ticketing from '../pages/Ticketing';
import Checkout from '../pages/Checkout';
import Page404 from '../pages/404';

Vue.use(Router);

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
      meta: { requiresAuth: true },
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
    },
    {
      path: '/blog',
      name: 'Blog',
      component: Blog,
      meta: { requiresAuth: true },
    },
    {
      path: '/billetterie',
      name: 'Billetterie',
      component: Ticketing,
      meta: { requiresAuth: true },
    },
    {
      path: '/billetterie/checkout',
      name: 'Checkout',
      component: Checkout,
      meta: { requiresAuth: true },
    },
    {
      // This one MUST stay in last place as it handles redirection for unmatched routes
      path: '*',
      name: '404',
      component: Page404,
    },
  ],
});

// Setup the login verification
router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // Verify if current state has an email address, if not, redirect to the login page
    if (Store.getters.email === null) {
      next({ path: '/login' });
    } else {
      // Verify if the entered email address exists in the database
      axios.post(`${config.apiEndpoint}/guests/verify`, { email: Store.getters.email })
        .then((response) => {
          if (response.data.validLogin) {
            next();
          } else {
            next({ path: '/login' });
          }
        });
    }
  } else {
    next();
  }
});

export default router;
