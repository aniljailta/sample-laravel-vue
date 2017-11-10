<template>
    <div v-show="false">
        <iframe-downloader v-for="(handler, index) in handlers" :key="handler.url"
                           :url="handler.url"
                           @download="reset"
                           @error="error"/>
    </div>
</template>

<script type="text/babel">
    import IframeDownloader from 'src/components/libs/IframeDownloader.vue'

    export default {
        data() {
            return {
                handlers: []
            }
        },
        components: {IframeDownloader},
        props: {},
        created() {
            this.$bus.$on('dofDownload', this.download)
            this.$bus.$on('dofOrderLoaded', this.destroyAll)
        },
        methods: {
            download(handler) {
                this.handlers.push(handler)
            },
            reset(params) {
                this.getHandler(params.url).reset()
                this.destroyIframe(params.url)
            },
            error(params) {
                this.getHandler(params.url).error(params.messages)
                this.destroyIframe(params.url)
            },
            getHandler(url) {
                return _.find(this.handlers, {url})
            },
            destroyIframe(url) {
                let index = _.findIndex(this.handlers, {url})
                this.handlers.splice(index, 1)
            },
            destroyAll(params) {
                this.handlers = []
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>