import { createRouter, createWebHistory } from 'vue-router';
import home from './components/home.vue'
import notFound from './components/not_found.vue';
import check_credit from './components/check_credit.vue';
import credit from './components/credit.vue';
import card from './components/card.vue';
import deposit from './components/deposit.vue';
import login from './components/login.vue';
import register from './components/register.vue';
import admin_panel from './components/admin_panel.vue';
import personal_account from './components/personal_account.vue';
import forgot_password from './components/forgot_password.vue';
import reset_password from './components/reset_password.vue';
import email_verified from './components/email_verified.vue';

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
      path: '/register',
      name: 'register',
      component: register
    },
    {
      path: '/personal_account',
      name: 'personal_account',
      component: personal_account
    },
    {
      path: '/admin_panel',
      name: 'admin_panel',
      component: admin_panel
    },
    {
      path: '/forgot_password',
      name: 'forgot_password',
      component: forgot_password
    },
    {
      path: '/reset_password',
      name: 'reset_password',
      component: reset_password
    },
    {
      path: '/email_verified',
      name: 'email_verified',
      component: email_verified
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'notFound',
      component: notFound
    },
  ]
})

export default router;