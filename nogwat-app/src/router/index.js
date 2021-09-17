import { createRouter, createWebHistory } from '@ionic/vue-router';
import Home from '../views/Home'
import Login from '../views/Login'

const routes = [
  {
    path: '/',
    component: Home
  },
  // {
  //   path: '/',
  //   redirect: '/login'
  // },
  {
    path: '/login',
    component: Login
  },
  {
    path: '/about',
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue'),
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
