<template>


    <div>
    <div v-if="loading" style="margin-top: 0.5em">
        <i class="fa fa-cog fa-spin fa-3x fa-fw text-muted"></i>
    </div>

    <form id="upload-files__form" v-show="!loading">
        <input class="form-control" id="upload-files__input" name="upload_files" type="file" ref="uploadInput">
    </form>
    </div>

</template>

<script type="text/babel">
    import 'bootstrap-fileinput'
    import '!style!css!less!bootstrap-fileinput/css/fileinput.css'

    export default {
        data() {
            return {
                loading: true,
                _token: null,
                storable_type: null,
                files: {},
                urls: {
                    list: null,
                    upload: null,
                    delete: null
                }
            }
        },
        created() {
            this._token = window.document.getElementById('_token').content
        },
        computed: {
            initialPreview() {
                return _.map(this.files, 'public_path')
            },
            initialPreviewConfig() {
                var initialPreviewConfig = _.map(this.files, function (el, index) {
                    var itemConf = {
                        caption: el.name,
                        size: el.size,
                        // url: el.public_path + el.name, // used only for delete
                        key: el.id
                    }

                    // el.type === 'text' ||
                    if (el.type === 'image' || el.type === 'pdf' || el.type === 'video' || el.type === 'flash' || el.type === 'audio') {
                        itemConf.type = el.type
                        itemConf.filetype = el.mime
                    } else {
                        itemConf.previewAsData = false
                    }

                    if (el.type === 'image') {
                        itemConf.width = el.width + 'px'
                        itemConf.height = el.height + 'px'
                    }
                    return itemConf
                })

                return initialPreviewConfig
            }
        },
        methods: {
            renderUploader() {
                this.$refs.uploadInput = $(this.$refs.uploadInput).fileinput({
                    previewZoomSettings: {
                        image: { width: 'auto', height: '100%' },
                        html: { width: '100%', height: '100%', 'min-height': '480px' },
                        text: { width: '100%', height: '100%', 'min-height': '480px' },
                        video: { width: 'auto', height: '100%', 'max-width': '100%' },
                        audio: { width: '100%', height: '30px' },
                        flash: { width: 'auto', height: '480px' },
                        object: { width: 'auto', height: '480px' },
                        pdf: { width: '100%', height: '100%', 'min-height': '480px' },
                        other: { width: 'auto', height: '100%', 'min-height': '480px' }
                    },
                    // allowedFileExtensions: ['csv'],
                    showCaption: false,
                    uploadLabel: 'Upload selected files',
                    uploadUrl: this.urls.upload, // server upload action
                    deleteUrl: this.urls.delete, // server delete action
                    uploadAsync: true,
                    showPreview: true,
                    // maxFileCount: 1,
                    // autoReplace: true,
                    // browseOnZoneClick: true,
                    overwriteInitial: false,
                    uploadExtraData: {
                        storable_type: this.storable_type,
                        storable_id: this.storable_id,
                        _token: this._token
                    },
                    deleteExtraData: {
                        _token: this._token,
                        _method: 'DELETE'
                    },
                    initialPreview: this.initialPreview,
                    initialPreviewAsData: true, // allows you to set a raw markup
                    // initialPreviewFileType: 'image', // image is the default and can be overridden in config below
                    initialPreviewConfig: this.initialPreviewConfig
                })
            }
        }
    }
</script>

<style type="text/css">

</style>