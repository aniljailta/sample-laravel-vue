<template>

    <div class="item col-xs-4 col-md-3">
        <div class="thumbnail" ref="thumbnail">
            <div style="width: 100%; height: 80%; position: relative;" class="pointer" @click="showCategory(item)">
                <img class="image-preview"
                     :src="image.publicPath"
                     :alt="item.name">
            </div>

            <div class="caption">
                <h4 class="group inner list-group-item-heading">{{ item.name }}</h4>
                <span class="group inner list-group-item-text">{{ item.description }}</span>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import Popover from 'uiv/src/components/popover/Popover.vue'

    export default {
        data() {
            return {
            }
        },
        components: {
            Popover
        },
        props: {
            item: {
                default() {
                    return {}
                }
            }
        },
        computed: {
            image() {
                let image = {}
                if (_.isArray(this.item.files) && this.item.files.length > 0) {
                    image = _.cloneDeep(_.last(this.item.files))
                    return image
                }

                return {
                    publicPath: null,
                    previewHeight: 256,
                    previewWidth: 256,
                    width: 256,
                    height: 256
                }
            }
        },
        methods: {
            showCategory(item) {
                this.$emit('show-category', item)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

    div.thumbnail {
        text-align: center;
        margin-bottom: 0px;
        // padding: 0;
        padding: 3px;
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        border-radius: 0px;

        /* trying to set the same height for all elements */
        height: 200px;
        max-width: 200px;

        .caption {
            height: 20%;
            max-height: 20%;
            overflow-y: auto;
            padding: 0;
        }

        .image-preview {
            max-height: 100%;
            max-width: 100%;
            padding: 3px;

            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }
    }

    .show-button {
        height: 20%;
        padding: 0;

        button {
            height: 100%;
            border-radius: 0px !important;
        }
    }

    .item {
        padding: 0.3em 5px 0 5px;
    }

    .list-group-item-heading, .list-group-item-text {
        margin: 0.2em 0 0 0 !important;
    }

    .item.list-group-item {
        float: none;
        width: 100%;
        background-color: #fff;
        margin-bottom: 10px;
    }
    .item.list-group-item:nth-of-type(odd):hover,.item.list-group-item:hover {
        background: #428bca;
    }

    .item.list-group-item .list-group-image {
        margin-right: 10px;
    }
    .item.list-group-item div.thumbnail {
        margin-bottom: 0px;
    }
    .item.list-group-item .caption {
        padding: 9px 9px 0px 9px;
    }
    .item.list-group-item:nth-of-type(odd) {
        background: #eeeeee;
    }

    .item.list-group-item:before, .item.list-group-item:after {
        display: table;
        content: " ";
    }

    .item.list-group-item img {
        float: left;
    }
    .item.list-group-item:after {
        clear: both;
    }
    .list-group-item-text {
        margin: 0 0 11px;
    }
</style>