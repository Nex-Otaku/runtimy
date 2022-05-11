const routes = [
  {
    path: '/home',
    component: () => import('layouts/MainLayout.vue'),
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
    path: '/',
    component: () => import('layouts/DialogPageLayout.vue'),
    children: [
      {
        path: '', component: () => import('pages/NewOrderPage.vue')
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
