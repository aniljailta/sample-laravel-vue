<template>

    <div class="v-file-uploader text-center" :class="{'attached': attachment.files.length > 0 ? true : false}">
        <data-process :process="dataProcess" :with_loader="true"></data-process>
        <!-- should be v-show because plugin can be initialized correctly via current flow (todo: change it) -->
        <div v-show="!(dataProcess.running && dataProcess.type === 'data')">
            <span v-if="attachment.category">{{ attachment.category.title }}</span>
            <input class="upload-files__input" name="upload_files[]" type="file" ref="uploadInput">
        </div>
    </div>

</template>

<script type="text/babel">
    /*eslint-disable no-unused-vars*/
    import baseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import 'bootstrap-fileinput'
    import '!style!css!less!bootstrap-fileinput/css/fileinput.css'

    import modal from 'bootstrap/js/modal'
    import FileManagerControls from './FileManagerControls.vue'
    import Vue from 'vue'

    const FileManagerControlsConstructor = Vue.extend(FileManagerControls)

    export default {
        extends: baseDataItem,
        data() {
            return {
                _token: null,
                storableType: 'order',
                urls: {
                    list: null,
                    upload: null,
                    delete: null
                }
            }
        },
        components: {
            // FileManagerControls // FileItem
        },
        props: {
            attachment: {
                required: true,
                default() { return {} }
            },
            uploadAsync: {
                type: Boolean,
                default: true
            },
            showCategoryLabel: {
                type: Boolean,
                default: false
            }
        },
        beforeDestroy() {
            this.$refs.uploadInput.fileinput('destroy')
        },
        computed: {},
        methods: {
            updateOrderValidation() {},
            addAttachment() {},
            removeAttachment() {},
            initialPreview(files) {
                return _.map(files, 'publicPath')
            },
            initialPreviewConfig(files) {
                let self = this
                var initialPreviewConfig = _.map(files, function (el, index) {
                    var itemConf = {
                        caption: el.name,
                        size: el.size,
                        url: self.urls.delete + el.id, // used only for delete
                        key: el.id
                    }

                    // el.type === 'text' ||
                    if (el.type === 'image' || el.type === 'pdf' || el.type === 'video' || el.type === 'flash' || el.type === 'audio') {
                        itemConf.type = el.type
                        itemConf.filetype = el.mime
                    } else {
                        // itemConf.previewAsData = false
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
                var initialPreviewThumbTags = _.map(files, (el, index) => {
                    var itemConf = {}
                    if (el.category) {
                        itemConf.CATEGORY_VALUE = el.category.title
                    }
                    return itemConf
                })

                return initialPreviewThumbTags
            },
            renderUploader(newFiles) {
                let self = this
                let initialPreview = this.initialPreview(newFiles)
                let initialPreviewConfig = this.initialPreviewConfig(newFiles)
                let initialPreviewThumbTags = this.initialPreviewThumbTags(newFiles)

                let tFooter = '<div class="file-thumbnail-footer">\n'

                if (this.showCategoryLabel) {
                    tFooter +=
                        '<div style="margin:5px 0">\n' + '<span class="label label-default {CATEGORY_CSS_NEW}">CATEGORY_VALUE</span>\n' + '</div>\n'
                }
                tFooter += '<div class="file-footer-caption" title="{caption}">{caption}{size}</div>\n' + '{progress} {actions}\n' + '</div>'

                let tActionsCanDelete = this.attachment.canDelete ? '{delete} ' : ''
                let tActionsUploadAsync = this.uploadAsync ? '{upload} ' : ''
                let tActions = '{drag}\n' +
                    '<div class="file-actions">\n' +
                    '    <div class="file-footer-buttons">\n' +
                    '        ' + tActionsUploadAsync + tActionsCanDelete + '{zoom} {other}' +
                    '    </div>\n' +
                    '    <div class="clearfix"></div>\n' +
                    '</div>'

                this.$refs.uploadInput = $(this.$refs.uploadInput).fileinput({
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
                    previewSettings: {
                        image: {width: 'auto', height: '160px'},
                        html: {width: '100%', height: '160px'},
                        text: {width: '100%', height: '160px'},
                        video: {width: 'auto', height: '100%', 'max-width': '100%'},
                        audio: {width: '100%', height: '30px'},
                        flash: {width: 'auto', height: '100%', 'max-width': '100%'},
                        object: {height: '100%'},
                        pdf: {width: '100%', height: '160px'},
                        other: {width: '100%', height: '160px'}
                    },
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
                    previewClass: 'attachments-file-preview',
                    // allowedFileExtensions: ['csv'],
                    showCaption: false,
                    showUpload: false,
                    showRemove: false,
                    showBrowse: false,
                    uploadUrl: this.urls.upload, // server upload action
                    deleteUrl: this.urls.delete, // server delete action
                    uploadAsync: this.uploadAsync,
                    // showPreview: true,
                    maxFileCount: 1,
                    autoReplace: true,
                    browseOnZoneClick: true,
                    dropZoneEnabled: this.attachment.canUpload || false,
                    dropZoneTitle: '<small style="color: #aaa">Drag & drop files here &hellip;</small>',
                    dropZoneClickTitle: '<br><small style="color: #aaa">(or click to select {files})</small>',
                    overwriteInitial: true,
                    uploadExtraData: this.getExtra,
                    deleteExtraData: {
                        _token: this._token,
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
                        close: '',
                        footer: tFooter,
                        actions: tActions
                    },
                    fileActionSettings: {
                        showDrag: false
                    },
                    previewThumbTags: {
                        '{CATEGORY_VALUE}': '', // no value
                        '{CATEGORY_CSS_NEW}': 'hide'
                    }
                })

                // add external controls
                this.$refs[this.attachment.categoryId] = new FileManagerControlsConstructor({
                    propsData: {
                        id: this.storableId,
                        attachment: this.attachment
                    },
                    name: this.attachment.categoryId,
                    parent: this
                }).$mount()

                let target = $(this.$refs.uploadInput[0]).parent().find('.file-preview-thumbnails')
                $(this.$refs[this.attachment.categoryId].$el).insertAfter(target)

                this.postRenderUploaderHook()
            },
            postRenderUploaderHook() {},
            getExtra(previewId, index) {
                let generic = {
                    storable_type: this.storableType,
                    storable_id: this.storableId,
                    _token: this._token
                }

                if (_.isUndefined(index)) return generic

                let extra = _.assign(generic, {
                    category_id: this.attachment.categoryId
                })
                return extra
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .file-manager__container {
        .file-preview {
            border: none;
            padding: 0;

            .file-drop-zone {
                margin: 0;
            }
        }
    }
</style>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>