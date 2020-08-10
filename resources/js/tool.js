Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'laravel-cloudflare',
      path: '/laravel-cloudflare',
      component: require('./components/Tool').default,
    },
  ])
})
