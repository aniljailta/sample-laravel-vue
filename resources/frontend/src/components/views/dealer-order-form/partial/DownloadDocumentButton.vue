<template>
    <button type="button"
            class="btn btn-default" :class="{ 'disabled': generating }"
            :disabled="generating"
            @click.stop="download">
        <i class="fa fa-file-pdf-o" aria-hidden="true" v-if="!generating"></i>
        <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true" v-show="generating"></i>
        <u>{{ downloadLabel }}</u>
    </button>
</template>

<script type="text/babel">
    /*global swal*/
    import urlTemplate from 'url-template'
    import apiOrders from 'src/api/orders'

    export default {
        data() {
            return {
                generating: false,
                url: null
            }
        },
        components: {},
        props: {
            id: {
                type: String,
                default: null
            },
            categoryId: {
                type: String,
                default: null
            },
            downloadLabel: {
                type: String,
                default: null
            }
        },
        methods: {
            reset() {
                this.url = null
                this.generating = false
            },
            error(messages) {
                this.reset()
                swal({
                    title: 'Warning',
                    text: messages,
                    type: 'warning',
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Close'
                })
            },
            download() {
                let self = this
                this.$bus.$emit('dofGenerateDocument', {
                    validate(rootVm) {
                        // revalidate all data across the form
                        // just to be sure that all data is saved/or noticed for user
                        let validDeps = rootVm.$validateDeps('order', {$touch: true})
                        let validStep = rootVm.$validate('order', {$touch: true})
                        return (validStep && validDeps)
                    },
                    before() {
                        self.generating = true
                    },
                    stop(payload) {
                        self.generating = false

                        if (payload) {
                            if (_.isObject(payload.message)) {
                                payload = _.values(payload.message)
                            }
                            let error = _.isArray(payload) ? payload.join('\r\n') : payload
                            swal('Error', error, 'error')
                        }
                    },
                    success: self.onSuccess
                })
            },
            onSuccess(response) {
                let url = urlTemplate.parse(apiOrders.actions.generateDocument.url).expand({
                    id: this.id,
                    categoryId: this.categoryId
                })
                this.url = url

                // return object callbacks for handlers outside this component,
                // required for avoid flow corruption after save order (reload)
                return {
                    url,
                    reset: this.reset,
                    error: this.error
                }
                /*
                this.generating = false
                if (response.data.files) {
                    let file = _.first(response.data.files)
                    if (file.directPath) {
                        // create iframe with content-disposition = attachment
                        // for transparently download file
                        let iframe = document.createElement('iframe')
                        iframe.src = file.directPath + '/download'
                        iframe.style.display = 'none'
                        document.body.appendChild(iframe)

                        // better to remove, but there is no time for handle 'save' dialog
                        // document.body.removeChild(iframe)
                    } else {
                        let iframe = document.createElement('iframe')
                        iframe.src = file.directPath + '/download'
                        iframe.style.display = 'none'
                        document.body.appendChild(iframe)
                    }
                }
                */
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .btn {
        white-space: normal;
    }
</style>