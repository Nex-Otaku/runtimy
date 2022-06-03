const routes = [
  {
    path: '/',
    component: () => import('layouts/GuestLayout.vue'),
    children: [
      {
        name: 'login-phone',
        path: '',
        component: () => import('pages/LoginPhonePage.vue'),
        meta: {
          roles: ['guest'],
        }
      },
      {
        name: 'login-pincode',
        path: 'pincode',
        component: () => import('pages/LoginPincodePage.vue'),
        meta: {
          roles: ['guest'],
        }
      },
      {
        name: 'fast-login',
        path: 'fast-login',
        component: () => import('pages/FastLoginPage.vue'),
        meta: {
          roles: ['guest'],
        }
      },
    ]
  },
  {
    path: '/home',
    component: () => import('layouts/GuestLayout.vue'),
    children: [
      {
        path: '',
        component: () => import('pages/IndexPage.vue'),
        meta: {
          roles: ['guest'],
        }
      },
      {
        path: 'login',
        component: () => import('pages/LoginPage.vue'),
        meta: {
          roles: ['guest'],
        }
      }
    ]
  },
  {
    path: '/dialog',
    component: () => import('layouts/DialogPageLayout.vue'),
    children: [
      {
        path: 'new-order',
        component: () => import('pages/NewOrderPage.vue'),
        meta: {
          roles: ['customer'],
        }
      },
      {
        name: 'view-order',
        path: 'view-order/:id',
        component: () => import('pages/ViewOrderPage.vue'),
        meta: {
          roles: ['customer'],
        }
      },
      {
        name: 'edit-order',
        path: 'edit-order/:id',
        component: () => import('pages/EditOrderPage.vue'),
        meta: {
          roles: ['customer'],
        }
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
        component: () => import('pages/OrdersListPage.vue'),
        meta: {
          roles: ['customer'],
          title: 'Заказы',
        }
      },
      {
        name: 'chat',
        path: 'chat',
        component: () => import('pages/ChatPage.vue'),
        meta: {
          roles: ['customer'],
          title: 'Чат',
        }
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
        meta: {
          roles: ['customer', 'courier'],
        }
      },
    ]
  },
  {
    path: '/test',
    component: () => import('layouts/GuestLayout.vue'),
    children: [
      {
        path: 'blank',
        component: () => import('pages/BlankPage.vue'),
        meta: {
          roles: ['guest'],
        }
      },
      {
        path: 'pinia',
        component: () => import('pages/PiniaPage.vue'),
        meta: {
          roles: ['guest'],
        }
      },
      {
        path: 'loop',
        component: () => import('pages/LoopPage.vue'),
        meta: {
          roles: ['guest'],
        }
      },
      {
        path: 'router',
        component: () => import('pages/RouterPage.vue'),
        meta: {
          roles: ['guest'],
        }
      },
      {
        name: 'counter1',
        path: 'counter1',
        component: () => import('pages/CounterFirstPage.vue'),
        meta: {
          roles: ['guest'],
        }
      },
      {
        name: 'counter2',
        path: 'counter2',
        component: () => import('pages/CounterSecondPage.vue'),
        meta: {
          roles: ['guest'],
        }
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
