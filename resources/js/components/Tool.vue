<template>
    <form autocomplete="off" novalidate="true">
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
            <clear-cache-button
                tabindex="0"
                button-type="primary"
                :urls="urlArray"
                :notifications="false"
                @purgeSuccess="success"
                @purgeError="error"
            ></clear-cache-button>
        </div>
    </form>
</template>

<script>
import ClearCacheButton from './ClearCacheButton'

export default {
    components: {
        'clear-cache-button': ClearCacheButton
    },
    data() {
        return {
            urls: [],
            invalidUrls: []
        }
    },
    computed: {
        invalidUrlsMessage: function () {
            let length = this.invalidUrls.length;
            return length ? (length === 1 ? '1 URL above is' : length + ' URLs above are') + ' invalid.' : '';
        },
        urlArray: function () {
            return this.urls.length ? this.urls.split('\n') : [];
        }
    },
    methods: {
        success: function(data) {
            if (data && data.purgedUrls) {
                if (data.errorMessage) {
                    this.error(data.errorMessage)
                } else {
                    if (this.urlArray.length === data.purgedUrls.length) {
                        this.$toasted.show('All URLs purged successfully.', { type: 'success' })
                        this.invalidUrls = [];
                    } else {
                        if (data.purgedUrls.length > 0) {
                            this.$toasted.show(`${data.purgedUrls.length} URL${data.purgedUrls.length === 1 ? '' : 's'} purged successfully.`, { type: 'success' })
                        }
                        if (this.urlArray.length > data.purgedUrls.length) {
                            this.invalidUrls = this.urlArray.filter(n => !data.purgedUrls.includes(n));
                            this.urls = this.invalidUrls.join('\n');
                        }
                    }
                }
            } else {
                this.error('Server error: response does not have data.')
            }
        },
        error: function (message) {
            this.$toasted.show(message, { type: 'error' });
        }
    }
}
</script>

<style>
/* Scoped Styles */
</style>
