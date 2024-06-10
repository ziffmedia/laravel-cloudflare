<template>
  <div>
    <div class="mb-8">
      <Heading title="Purge Cache">Cloudflare: Purge Cache</Heading>

      <div class="help-text help-text mt-2 mb-2">
        Enter URLs below, use &lt;enter&gt; to delimit URLs.
      </div>

      <div class="w-full">
        <textarea
          ref="textInput"
          id="purgeUrl"
          type="textarea"
          v-model="urlsString"
          placeholder="www.example.com/page (don't include http:// or https://)"
          class="w-full form-control form-input form-input-bordered"
          :class="invalidUrls.length ? 'border-danger' : ''"
          style="min-height:10rem;"
        ></textarea>

        <div v-if="invalidUrlsMessage" class="help-text error-text mt-2 text-danger">
          {{ invalidUrlsMessage }}
        </div>


      </div>
    </div>

    <div class="flex items-center">
      <DefaultButton
        @click.prevent="handlePurgeUrls"
      >
        Purge Urls
      </DefaultButton>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isSubmitted: false,
      urlsString: '',
      invalidUrls: []
    }
  },

  computed: {
    invalidUrlsMessage: function () {
      return this.invalidUrls.length ?
        (this.invalidUrls.length === 1 ? '1 URL above is' : this.invalidUrls.length + ' URLs above are') + ' invalid.'
        : '';
    },

    urls: function () {
      return (this.urlsString.length ? this.urlsString.split('\n') : []).map(url => url.trim()).filter(url => url.length > 0);
    }
  },

  methods: {
    handlePurgeUrls () {
      const urls = this.urls

      if (urls.length === 0) {
        Nova.warning('No URLs provided to purge')

        return
      }

      Nova.request().post('/nova-vendor/cloudflare/purge-urls', { urls })
        .then(resp => {
          const message = (resp.data.purgedUrls.length === urls.length)
            ? 'All urls successfully purged'
            : 'The following URLs were purged:<br>' + resp.data.purgedUrls.join('<br>')

          Nova.success(message)
        })
        .catch(err => {
          Nova.error('There was an error purging the URLs.')

          console.error('Error from api', err)
        })

      this.urlsString = ''
    }
  }
}
</script>

<style>
/* Scoped Styles */
</style>
