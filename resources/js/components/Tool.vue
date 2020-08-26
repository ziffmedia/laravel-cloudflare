<template>
    <form autocomplete="off" method="post" action="/cache-tool/{urls}" @submit="submitUrls" novalidate="true">
        <div class="mb-8">
            <h1 class="mb-3 text-90 font-normal text-2xl">Cache Purge</h1>
            <div class="card">
                <div class="flex border-b border-40">
                    <div class="w-1/5 px-8 py-6">
                        <label for="purgeUrl" class="inline-block text-80 pt-2 leading-tight">URLs</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <textarea ref="textInput" id="purgeUrl" type="textarea" v-model="urls" placeholder="https://www.example.com/page" class="w-full form-control form-input form-input-bordered" :class="invalidUrls.length ? 'border-danger' : ''" style="min-height:10rem;"></textarea>
                        <div v-if="invalidUrlsMessage" class="help-text error-text mt-2 text-danger">{{ invalidUrlsMessage }}</div>
                        <div class="help-text help-text mt-2">Enter URLs above, use &lt;enter&gt; to delimit URLs.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end">
            <button tabindex="0" type="submit" class="btn btn-default btn-primary inline-flex items-center relative">
                Clear Cache
            </button>
        </div>
    </form>
</template>

<script>
export default {
    data() {
        return {
            urls: [],
            validUrls: [],
            invalidUrls: []
        }
    },
    computed: {
        invalidUrlsMessage: function () {
            let length = this.invalidUrls.length;
            return length ? (length === 1 ? '1 URL above is' : length + ' URLs above are') + ' invalid.' : '';
        }
    },
    methods: {
        submitUrls: function (e) {
            e.preventDefault();

            if (!this.urls.length) { return; }

            let urlArray = this.urls.split('\n');
            let self = this;
            let request = {
                'urls': urlArray
            };

            Nova.request().post('/nova-vendor/laravel-cloudflare/purge', request)
            .then(resp => {
                self.processResponse(resp);
            })
        },
        processResponse: function(resp) {
            if (resp.data && resp.data.validUrls) {
                this.validUrls = resp.data.validUrls;
                this.invalidUrls = resp.data.invalidUrls;

                if (resp.data.errorMessage) {
                    this.$toasted.show('Server error: ' + resp.data.errorMessage, { type: 'error' })
                } else if (this.validUrls.length) {
                    let message = (this.invalidUrls.length === 0 ? 'All URLs' : (this.validUrls.length === 1 ? '1 URL' : this.validUrls.length + ' URLs')) + ' successfully purged.';
                    this.$toasted.show(message, { type: 'success' })
                    this.urls = this.invalidUrls.join('\n');
                }
            } else {
                this.$toasted.show('Server error: response does not have data.', { type: 'error' });
            }
        }
    }
}
</script>

<style>
/* Scoped Styles */
</style>
