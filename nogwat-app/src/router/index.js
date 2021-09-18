import { createRouter, createWebHistory } from '@ionic/vue-router';
import Home from '../views/Home'

const routes = [
  {
    path: '/',
    component: Home
  },
  { 
    path: '/dashboard',
    component: () => import(/* webpackChunkName: "Dashboard" */ '../views/Dashboard.vue'),
    beforeEnter: (to, from, next) => {
      const loggedIn = localStorage.getItem('user')
      if (to.name !== 'Login' && !loggedIn) next({ name: 'Login' })
      else next()
    }
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
