<template>

    <div class="panel">
        <div v-if="loading" style="margin-top: 0.5em">
            <i class="fa fa-cog fa-spin fa-3x fa-fw text-muted"></i>
        </div>

        <div v-show="!loading">
            <input class="form-control" id="upload-files__input" name="upload_files[]" type="file" multiple="multiple" ref="uploadInput">
        </div>
    </div>

</template>

<script type="text/babel">
    /*eslint-disable no-unused-vars*/
    import 'bootstrap-fileinput'
    import '!style!css!less!bootstrap-fileinput/css/fileinput.css'

    import modal from 'bootstrap/js/modal'

    export default {
        data() {
            return {
                loading: true,
                token: null
            }
        },
        components: {
        },
        props: {
            options: {
                type: Object,
                default() {
                    return {}
                }
            },
            storable_type: {
                type: String
            },
            storable_id: {
                required: true
            },
            category_id: {
                required: false,
                default: null
            },
            preserve: {
                type: Array
            },
            upload_async: {
                type: Boolean,
                default: true
            },
            files: {
                type: Array,
                default() {
                    return []
                }
            },
            deleteFile: {
                type: Function,
                default() {
                }
            }
        },
        mounted() {
            this.token = window.document.getElementById('_token').content // v1 compiled
            this.renderUploader(this.files)
            this.loading = false
        },
        methods: {
            initialPreview(files) {
                return _.map(files, 'publicPath')
            },
            initialPreviewConfig(files) {
                let self = this

                var initialPreviewConfig = _.map(files, function (el, index) {
                    var itemConf = {
                        caption: el.name,
                        size: el.size,
                        key: el.id
                    }

                    if (self.options.deleteUrl) {
                        itemConf['url'] = self.options.deleteUrl + el.id // used only for delete
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
            },
            initialPreviewThumbTags(files) {
                var initialPreviewThumbTags = _.map(files, function (el, index) {
                    var itemConf = {}
                    if (el.category) {
                        itemConf['CATEGORY_VALUE'] = el.category.title
                    } else {
                        itemConf['CATEGORY_VALUE'] = ''
                    }

                    return itemConf
                })

                return initialPreviewThumbTags
            },
            config(newFiles) {
                let initialPreview = this.initialPreview(newFiles)
                let initialPreviewConfig = this.initialPreviewConfig(newFiles)
                let initialPreviewThumbTags = this.initialPreviewThumbTags(newFiles)

                let tFooter = '<div class="file-thumbnail-footer">\n' +
                        '   <div style="margin:5px 0">\n' +
                        '       <span class="label label-default label-tags {CATEGORY_CSS_NEW}">CATEGORY_VALUE</span>\n' +
                        '   </div>\n' +
                        '    <div class="file-footer-caption" title="{caption}">{caption}{size}</div>\n' +
                        '    {progress} {actions}\n' +
                        '</div>'

                let def = {
                    previewZoomSettings: {
                        image: {width: 'auto', height: '100%'},
                        html: {width: '100%', height: '100%', 'min-height': '480px'},
                        text: {width: '100%', height: '100%', 'min-height': '480px'},
                        video: {width: 'auto', height: '100%', 'max-width': '100%'},
                        audio: {width: '100%', height: '30px'},
                        flash: {width: 'auto', height: '480px'},
                        object: {width: 'auto', height: '480px'},
                        pdf: {width: '100%', height: '100%', 'min-height': '480px'},
                        other: {width: 'auto', height: '100%', 'min-height': '480px'}
                    },
                    previewClass: 'attachments-file-preview',
                    previewFileIcon: '<i class="fa fa-file"></i>',
                    allowedPreviewTypes: ['image', 'text'], // set to empty, null or false to disable preview for all types
                    preferIconicPreview: true,
                    previewFileIconSettings: {
                        'doc': '<i class="fa fa-file-word-o text-primary"></i>',
                        'xls': '<i class="fa fa-file-excel-o text-success"></i>',
                        'ppt': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
                        'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
                        'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
                        'htm': '<i class="fa fa-file-code-o text-info"></i>',
                        'txt': '<i class="fa fa-file-text-o text-info"></i>'
                    },
                    // allowedFileExtensions: ['csv'],
                    showBrowse: false,
                    showCaption: false,
                    showUpload: false,
                    showRemove: false,
                    // uploadUrl: this.urls.upload, // server upload action
                    // deleteUrl: this.urls.delete, // server delete action
                    uploadAsync: this.upload_async,
                    showPreview: true,
                    // maxFileCount: 1,
                    autoReplace: false,
                    // browseOnZoneClick: true,
                    overwriteInitial: false,
                    uploadExtraData: this.getExtra,
                    deleteExtraData: {
                        _token: this.token,
                        _method: 'DELETE'
                    },
                    ajaxDeleteSettings: {
                        type: 'DELETE'
                    },
                    initialPreview: initialPreview,
                    initialPreviewAsData: true, // allows you to set a raw markup
                    // initialPreviewFileType: 'image', // image is the default and can be overridden in config below
                    initialPreviewConfig: initialPreviewConfig,
                    initialPreviewThumbTags: initialPreviewThumbTags,
                    layoutTemplates: {
                        // change button positions (to top)
                        main1: '   <div class="input-group {class}" style="margin-bottom: 0.5em">\n' +
                        '   <div class="input-group-btn">\n' +
                        '       {remove}\n' +
                        '       {cancel}\n' +
                        '       {upload}\n' +
                        '       {browse}\n' +
                        '   </div>\n' +
                        '       {caption}\n' +
                        '    </div>' +
                        '{preview}\n' +
                        '<div class="kv-upload-progress hide"></div>\n',
                        main2: '{remove}\n{cancel}\n{upload}\n{browse}\n{preview}\n<div class="kv-upload-progress hide"></div>',

                        // remove 'x' clear all button
                        close: '',
                        footer: tFooter
                    },
                    fileActionSettings: {
                        showUpload: this.upload_async
                    },
                    previewThumbTags: {
                        '{CATEGORY_VALUE}': '', // no value
                        '{CATEGORY_CSS_NEW}': 'hide'
                    }
                }

                let merged = _.merge(def, this.options)
                return merged
            },
            renderUploader(newFiles) {
                let self = this
                let finalConfig = this.config(newFiles)

                this.$refs.uploadInput = $(this.$refs.uploadInput).fileinput(finalConfig)
                this.$refs.uploadInput.on('filedeleted', function(event, key, jqXHR, extraData) {
                    self.deleteFile(key)
                })
            },
            getExtra(previewId, index) {
                let generic = {
                    storable_type: this.storable_type,
                    storable_id: this.storable_id,
                    preserve: JSON.stringify(this.preserve),
                    _token: this.token
                }

                if (this.category_id) {
                    generic.category_id = this.category_id
                }

                return generic
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>