<template>

    <div class="v-file-uploader text-center" :class="{'attached': attachment.files ? true : false}">
        <data-process :process="dataProcess" :with_loader="true"></data-process>

        <!-- should be v-show because plugin can be initialized correctly via current flow (todo: change it) -->
        <div v-show="!(dataProcess.running && dataProcess.type === 'data')">
            <input class="upload-files__input" name="upload_files[]" type="file" ref="uploadInput">
        </div>
    </div>

</template>

<script type="text/babel">
    import baseFileManager from 'src/components/views/partials/FileManager/FileManager.vue'

    export default {
        extends: baseFileManager,
        data() {
            return {
                _token: null,
                storableType: 'color',
                storableId: this.attachment.storableId, // TODO: fix
                urls: {
                    list: null,
                    upload: '/api/colors/upload-image/',
                    delete: '/api/files/'
                }
            }
        },
        components: {},
        props: {
            attachment: {
                required: true, default() { return {} }
            },
            uploadAsync: {
                type: Boolean, default: true
            },
            isValid: {
                type: Boolean, default: true
            }
        },
        created() {
            this._token = window.document.getElementById('_token').content
        },
        mounted() {
            if (this.attachment.files) {
                this.renderUploader(this.attachment.files)
            } else {
                this.renderUploader([])
            }

            this.$emit('data-process-update', {running: false})
        },
        beforeDestroy() {
            this.$refs.uploadInput.fileinput('destroy')
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>