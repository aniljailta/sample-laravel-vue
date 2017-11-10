<template>

    <div class="v-file-uploader text-center" :class="{'attached': attachment.files.length > 0 ? true : false}">
        <data-process :process="dataProcess" :with_loader="true"></data-process>

        <!-- should be v-show because plugin can be initialized correctly via current flow (todo: change it) -->
        <div v-show="!(dataProcess.running && dataProcess.type === 'data')" class="deposit-receipt__placeholder">
            <span v-if="attachment.category">
                <span v-if="!isValid">&nbsp;<i class="fa fa-exclamation-circle invalid" aria-hidden="true"></i></span>
            </span>
            <input class="upload-files__input" name="upload_files[]" type="file" ref="uploadInput">
        </div>

    </div>

</template>

<script type="text/babel">
    /*eslint-disable no-unused-vars*/
    import baseFileManager from '../partial/FileManager/FileManager.vue'
    import {mapActions, mapGetters} from 'vuex'

    export default {
        extends: baseFileManager,
        data() {
            return {}
        },
        components: {},
        props: {
            attachment: {
                required: true,
                default() { return {} }
            },
            uploadAsync: {
                type: Boolean,
                default: true
            },
            isValid: {
                type: Boolean,
                default: true
            }
        },
        created() {
            this._token = window.document.getElementById('_token').content
        },
        mounted() {
            if (_.size(this.attachment.files) > 0) {
                this.renderUploader(this.attachment.files)
            } else {
                this.renderUploader([])
            }

            this.$emit('data-process-update', {running: false})
        },
        beforeDestroy() {
            this.$refs.uploadInput.fileinput('destroy')
        },
        computed: {
            ...mapGetters({
                orderCurrent: 'dealerOrderForm/orderCurrent'
            }),
            storableId() {
                if (this.orderCurrent.id) return this.orderCurrent.id
                return null
            }
        },
        methods: {
            ...mapActions({
                addAttachment: 'dealerOrderForm/addAttachment',
                removeAttachment: 'dealerOrderForm/removeAttachment'
            }),
            postRenderUploaderHook() {
                // events for sync plugin data with vuex
                // add attachment to store
                // remove attachment from store
                this.$refs.uploadInput.on('fileuploaded', (event, data, previewId, index) => {
                    _.each(data.response.payload, item => {
                        this.addAttachment(item)
                    })
                })

                this.$refs.uploadInput.on('filedeleted', (event, key, jqXHR, extraData) => {
                    this.removeAttachment(key)
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>