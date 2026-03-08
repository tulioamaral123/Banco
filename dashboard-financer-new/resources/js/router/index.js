import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/login',
      component: () => import('../pages/auth/Login.vue'),
    },
    {
      path: '/',
      component: () => import('../layouts/AppLayout.vue'),
      children: [
        { path: '',              redirect: '/dashboard' },
        { path: 'dashboard',     name: 'dashboard',           component: () => import('../pages/dashboard/Index.vue')      },
        { path: 'investimentos', name: 'investimentos.index', component: () => import('../pages/investimentos/Index.vue')  },
        { path: ':entity',       name: 'entity.index',        component: () => import('../pages/entity/Index.vue')         },
        { path: ':entity/create',      name: 'entity.create', component: () => import('../pages/entity/Create.vue') },
        { path: ':entity/:id/edit',    name: 'entity.edit',   component: () => import('../pages/entity/Edit.vue')   },
      ],
    },
  ],
})

export default router