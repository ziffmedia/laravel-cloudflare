Nova.booting((Vue, router, store) => {
  Vue.component('index-cache-clear', require('./components/IndexField').default)
  Vue.component('detail-cache-clear', require('./components/DetailField').default)
  Vue.component('form-cache-clear', require('./components/FormField').default)
})
