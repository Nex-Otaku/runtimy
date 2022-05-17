const routes = [
  {
    path: '/home',
    component: () => import('layouts/GuestLayout.vue'),
    children: [
      {
        path: '', component: () => import('pages/IndexPage.vue')
      },
      {
        path: 'login', component: () => import('pages/LoginPage.vue')
      }
    ]
  },
  {
    path: '/dialog',
    component: () => import('layouts/DialogPageLayout.vue'),
    children: [
      {
        path: 'new-order', component: () => import('pages/NewOrderPage.vue')
      },
      {
        path: 'pinia', component: () => import('pages/PiniaPage.vue')
      },
      {
        path: 'loop', component: () => import('pages/LoopPage.vue')
      },
    ]
  },
  {
    path: '/my',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: 'orders', component: () => import('pages/OrdersListPage.vue')
      },
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
