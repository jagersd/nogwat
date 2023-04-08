import { createRouter, createWebHashHistory } from '@ionic/vue-router';
import Home from '../views/Home'
import About from '../views/About'

const routes = [
  {
    path: '/',
    component: Home,
    name: 'Home',
  },
  {
    path: '/about',
    component: About,
    name: 'About'
  },
  {
    path: '/error',
    component: () => import(/* webpackChunkName: "Errors" */ '../views/errors/generic.vue'),
    name: 'Error'
  },
  { 
    path: '/groups',
    component: () => import(/* webpackChunkName: "Groups" */ '../views/Groups.vue'),
    name: 'groups',
    meta: {
      requiresAuth: true
    }
  },
  { 
    path: '/lists',
    component: () => import(/* webpackChunkName: "Lists" */ '../views/Lists.vue'),
    name: 'lists',
    meta: {
      requiresAuth: true
    }
  },
  { 
    path: '/grouphistory/:groupId',
    component: () => import(/* webpackChunkName: "Grouphistory" */ '../views/History.vue'),
    name: 'grouphistory',
    meta: {
      requiresAuth: true
    }
  },
  { 
    path: '/recipes',
    component: () => import(/* webpackChunkName: "Recipes" */ '../views/Recipes.vue'),
    name: 'recipesmenu',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/myrecipes',
    component: () => import(/* webpackChunkName: "Myrecipes" */ '../views/recipeviews/Myrecipes.vue'),
    name: 'myrecipes',
    meta: {
      requiresAuth: true
    }
  },
  {
  path: '/searchrecipes',
  component: () => import(/* webpackChunkName: "Searchrecipes" */ '../views/recipeviews/Searchrecipes.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
  path: '/addrecipe',
  component: () => import(/* webpackChunkName: "Addrecipe" */ '../views/recipeviews/AddRecipe.vue'),
    name: 'AddRecipe',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/myfavorites',
    component: () => import(/* webpackChunkName: "Myfavorites" */ '../views/recipeviews/Myfavorites.vue'),
      meta: {
        requiresAuth: true
      }
    },
  {
    path: '/staritems',
    component: () => import(/* webpackChunkName: "Staritems" */ '../views/Staritems.vue'),
      meta: {
        requiresAuth: true
      }
    },
  {
    path: '/usersettings',
    component: () => import(/* webpackChunkName: "Usetsettings" */ '../views/Usersettings.vue'),
      meta: {
        requiresAuth: true
      }
  },
]

const router = createRouter({
  history: createWebHashHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem('user')
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  if (requiresAuth && to.name !== 'Home' && !loggedIn) next({ name: 'Home' })
  else next()
});

export default router
