import { createRouter, createWebHistory } from 'vue-router';
import home from './components/home.vue'
import notFound from './components/not_found.vue';
import check_credit from './components/check_credit.vue';
import credit from './components/credit.vue';
import card from './components/card.vue';
import deposit from './components/deposit.vue';
import login from './components/login.vue';
import admin_panel from './components/admin_panel.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: home,
    },
    {
      path: '/check_credit',
      name: 'check_credit',
      component: check_credit
    },
    {
      path: '/credit',
      name: 'credit',
      component: credit,
    },
    {
      path: '/card',
      name: 'card',
      component: card
    },
    {
      path: '/deposit',
      name: 'deposit',
      component: deposit
    },
    {
      path: '/login',
      name: 'login',
      component: login
    },
    {
      path: '/admin_panel',
      name: 'admin_panel',
      component: admin_panel
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'notFound',
      component: notFound
    },
  ]
})

export default router;