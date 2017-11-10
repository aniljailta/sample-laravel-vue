<template>

    <div class="file-manager-controls">
        <button class="col-xs-12 btn btn-sm btn-primary" v-if="attachment.canUpload">
            <template v-if="attachment.uploadLabel"><i class="fa fa-upload" aria-hidden="true"></i> {{ attachment.uploadLabel }}</template>
            <template v-else><i class="fa fa-folder-open" aria-hidden="true"></i> Select a file</template>
        </button>
    </div>

</template>

<script type="text/babel">
    export default {
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
            return {}
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