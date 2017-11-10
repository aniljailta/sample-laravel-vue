<template>

    <div class="file-manager-controls">
        <button class="col-xs-12 btn btn-sm btn-primary" v-if="attachment.canUpload">
            <template v-if="attachment.uploadLabel"><i class="fa fa-upload" aria-hidden="true"></i> {{ attachment.uploadLabel }}</template>
            <template v-else><i class="fa fa-folder-open" aria-hidden="true"></i> Select a file</template>
        </button>

        <download-document-button v-if="attachment.canGenerate"
                                  :id="id"
                                  :categoryId="attachment.generateCategoryId"
                                  :downloadLabel="attachment.downloadLabel"
                                  class="col-xs-12"/>
    </div>

</template>

<script type="text/babel">
    import DownloadDocumentButton from '../DownloadDocumentButton.vue'
    export default {
        components: {DownloadDocumentButton},
        props: {
            attachment: {
                required: true,
                default() { return {} }
            },
            id: {
                required: true,
                default: null
            }
        },
        data() {
            return {
                downloadUrl: null
            }
        },
        computed: {},
        methods: {
            upload() {
                // file-drop-zone clickable
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .file-manager-controls {
        width: 100%;
        margin-left: auto;
        margin-right: auto;

        .btn {
            margin-top: 2px;
        }
    }
</style>