import { createRouter, createWebHistory } from '@ionic/vue-router';
import Home from '../views/Home'
import About from '../views/About'

const routes = [
  {
    path: '/',
    component: Home,
    name: Home
  },
  {
    path: '/about',
    component: About,
    name: About
  },
  { 
    path: '/groups',
    component: () => import(/* webpackChunkName: "Groups" */ '../views/Groups.vue'),
    beforeEnter: (to, from, next) => {
      const loggedIn = localStorage.getItem('user')
      if (to.name !== 'logi' && !loggedIn) next({ name: 'Home' })
      else next()
    }
  },
  { 
    path: '/lists',
    component: () => import(/* webpackChunkName: "Lists" */ '../views/Lists.vue'),
    beforeEnter: (to, from, next) => {
      const loggedIn = localStorage.getItem('user')
      if (to.name !== 'Home' && !loggedIn) next({ name: 'Home' })
      else next()
    }
  },
  { 
    path: '/recipes',
    component: () => import(/* webpackChunkName: "Lists" */ '../views/Recipes.vue'),
    beforeEnter: (to, from, next) => {
      const loggedIn = localStorage.getItem('user')
      if (to.name !== 'Home' && !loggedIn) next({ name: 'Home' })
      else next()
    }
  },
  {
    path: '/myrecipes',
    component: () => import(/* webpackChunkName: "Lists" */ '../views/recipeviews/Myrecipes.vue'),
    beforeEnter: (to, from, next) => {
      const loggedIn = localStorage.getItem('user')
      if (to.name !== 'Home' && !loggedIn) next({ name: 'Home' })
      else next()
    }
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
