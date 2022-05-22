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
    ]
  },
  {
    path: '/my',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        name: 'orders',
        path: 'orders',
        meta: { title: 'Заказы' },
        component: () => import('pages/OrdersListPage.vue'),
      },
      {
        name: 'chat',
        path: 'chat',
        meta: { title: 'Чат' },
        component: () => import('pages/ChatPage.vue'),
      },
    ]
  },
  {
    path: '/my/profile',
    component: () => import('layouts/ProfileLayout.vue'),
    children: [
      {
        name: 'profile',
        path: '',
        component: () => import('pages/ProfilePage.vue'),
      },
    ]
  },
  {
    path: '/test',
    component: () => import('layouts/GuestLayout.vue'),
    children: [
      {
        path: 'blank', component: () => import('pages/BlankPage.vue')
      },
      {
        path: 'pinia', component: () => import('pages/PiniaPage.vue')
      },
      {
        path: 'loop', component: () => import('pages/LoopPage.vue')
      },
      {
        path: 'router', component: () => import('pages/RouterPage.vue')
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
