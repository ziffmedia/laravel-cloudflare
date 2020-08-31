Nova.booting((Vue, router, store) => {
  Vue.component('index-cache-clear', require('./components/IndexField'))
  Vue.component('detail-cache-clear', require('./components/DetailField'))
  Vue.component('form-cache-clear', require('./components/FormField'))
  router.addRoutes([
    {
      name: 'laravel-cloudflare',
      path: '/laravel-cloudflare',
      component: require('./components/Tool').default,
    },
  ])
})
