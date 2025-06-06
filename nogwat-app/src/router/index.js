import { createRouter, createWebHistory } from '@ionic/vue-router';
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
    component: () => import('../views/errors/generic.vue'),
    name: 'Error'
  },
  { 
    path: '/groups',
    component: () => import('../views/Groups.vue'),
    name: 'groups',
    meta: {
      requiresAuth: true
    }
  },
  { 
    path: '/lists',
    component: () => import('../views/Lists.vue'),
    name: 'Lists',
    meta: {
      requiresAuth: true
    }
  },
  { 
    path: '/grouphistory/:groupId',
    component: () => import('../views/History.vue'),
    name: 'grouphistory',
    meta: {
      requiresAuth: true
    }
  },
  { 
    path: '/recipes',
    component: () => import('../views/Recipes.vue'),
    name: 'recipesmenu',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/myrecipes',
    component: () => import('../views/recipeviews/Myrecipes.vue'),
    name: 'myrecipes',
    meta: {
      requiresAuth: true
    }
  },
  {
  path: '/searchrecipes',
  component: () => import('../views/recipeviews/Searchrecipes.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
  path: '/addrecipe',
  component: () => import('../views/recipeviews/AddRecipe.vue'),
    name: 'AddRecipe',
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/myfavorites',
    component: () => import('../views/recipeviews/Myfavorites.vue'),
      meta: {
        requiresAuth: true
      }
    },
  {
    path: '/staritems',
    component: () => import('../views/Staritems.vue'),
      meta: {
        requiresAuth: true
      }
    },
  {
    path: '/usersettings',
    component: () => import('../views/Usersettings.vue'),
      meta: {
        requiresAuth: true
      }
  },
]

const router = createRouter({
  // Use: createWebHistory(process.env.BASE_URL) in your app
  history: createWebHistory(),
  routes,
});


router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem('user')
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  if (requiresAuth && to.name !== 'Home' && !loggedIn) next({ name: 'Home' })
  else next()
});

export default router
