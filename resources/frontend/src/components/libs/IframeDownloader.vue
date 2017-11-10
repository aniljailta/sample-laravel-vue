<template>
    <iframe :src="fullUrl" style="display: none" @load="loaded" ref="iframe">
    </iframe>
</template>

<script type="text/babel">
    import jsCookie from 'js-cookie'

    export default {
        data() {
            return {
                token: null
            }
        },
        components: {},
        props: {
            url: {
                type: String,
                default: null
            }
        },
        created() {
            // using cookie for catching the moment, where file is ready (response from server)
            // so we can unlock UI
            this.token = 'doc_dl_' + (new Date()).getTime()
            let interval = setInterval(() => {
                if (jsCookie.get(this.token)) {
                    jsCookie.remove(this.token)
                    clearInterval(interval)
                    this.$emit('download', {url: this.url})
                }
            }, 1000)
        },
        computed: {
            fullUrl() {
                return this.url + '?token=' + this.token
            }
        },
        methods: {
            // iframe should return DOWNLOADable response,
            // if it is not -- meants, that something went wrong
            // show errors here
            loaded(e) {
                let content = this.$refs.iframe.contentDocument.body.innerText
                let messages
                try {
                    let json = JSON.parse(content)
                    if (_.isArray(json)) {
                        messages = json.join('\r\n')
                    }
                } catch (e) {
                    messages = content
                }

                this.$emit('error', {
                    url: this.url,
                    messages
                })
            }
        }
    }
</script>

<style type="text/css">

</style>