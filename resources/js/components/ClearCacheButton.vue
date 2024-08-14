<template>
    <button ref="button" @click.prevent="purgeCache" class="relative btn px-4 border btn-default" :class="buttonClasses" style="min-width:200px;">
        <div class="absolute h-6" :class="buttonType === 'default' ? 'text-70' : ''" style="top: 5px;">
            <svg v-if="isSuccess" class="h-5" :class="buttonType === 'default' ? 'text-success' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
            <svg v-else-if="isError" class="h-5" :class="buttonType === 'default' ? 'text-error' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>
            <svg v-else class="h-6" :class="isLoading ? 'animate-rotation' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" ><path fill="currentColor" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"></path></svg>
        </div>
        <span v-if="label" class="ml-8">{{ label }}</span>
    </button>
</template>

<script>
export default {
    props: {
        buttonType: {
            type: String,
            default: 'default'
        },
        label: {
            type: String,
            default: 'Clear Cache'
        },
        notifications: {
            type: Boolean,
            default: true
        },
        tags: {
            type: Array,
            default: () => {
                return []
            }
        },
        urls: {
            type: Array,
            default: () => {
                return []
            }
        },
    },
    data: function () {
        return {
            isLoading: false,
            isSuccess: false,
            isError: false,
            resetTimeout: 6000
        }
    },
    computed: {
        buttonClasses: function () {
            switch(this.buttonType) {
                case 'primary':
                    return 'btn-primary';
                default:
                    return 'bg-white font-normal border-60 text-80 hover:bg-40 hover:border-70';
            }
        }
    },
    methods: {
        purgeCache: function() {
            if (this.isLoading || (!this.urls.length && !this.tags.length)) { return; }

            let self = this;
            let request = {
                'urls': this.urls,
                'tags': this.tags
            };
            this.isLoading = true;
            this.isSuccess = false;
            this.isError = false;

            Nova.request().post('/nova-vendor/laravel-cloudflare/purge', request)
                .then(resp => {
                    if (resp.data) {
                        self.purgeSuccess(resp.data);
                    } else {
                        self.purgeError('Invalid Server Response');
                    }

                    self.isLoading = false;
                })
                .catch(resp => {
                    self.isLoading = false;
                })
        },
        purgeSuccess: function(data) {
            this.isSuccess = true;
            this.showNotification('Cache cleared successfully!', 'success');
            this.$emit('purgeSuccess', data);
            setTimeout(() => this.reset(), this.resetTimeout);
        },
        purgeError: function (message) {
            this.isError = true;
            this.showNotification(message, 'error');
            this.$emit('purgeError', message);
            setTimeout(() => this.reset(), this.resetTimeout);
        },
        showNotification: function(message, type) {
            if (this.notifications) {
                Nova.success(message, { type: type });
            }
        },
        reset: function() {
            this.isError = false;
            this.isLoading = false;
            this.isSuccess = false;
            this.$refs.button.blur();
            this.$emit('purgeReset', true);
        }
    },
}
</script>
