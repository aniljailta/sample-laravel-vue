<template>
        <div class="multiselect-block">
            <multiselect :disabled="isDisabled"
                         :limit="3"
                         :limit-text="limitText"
                         :show-labels="false"
                         @input="onChange"
                         :value="value"
                         :options="datas"
                         :multiple="true"
                         :close-on-select="true"
                         :clear-on-select="false"
                         :hide-selected="true"
                         placeholder="Select"
                         :label="label ? label: (dataType==='objects' ? 'title': 'name')"
                         :track-by="trackBy ? trackBy:'id'">
            </multiselect>
        </div>
</template>

<script type="text/babel">
    import Multiselect from 'vue-multiselect'

    export default {
        data() {
            return {
                options: []
            }
        },
        created() {},
        watch: {},
        props: {
            title: {
                default: 'title'
            },
            value: {
                default: null
            },
            datas: {
                required: true,
                default() {
                    return []
                }
            },
            dataType: {
                default: 'objects'
            },
            label: {
                default: false
            },
            trackBy: {
                default: 'id'
            },
            isDisabled: {
                default: false
            }
        },
        components: {
            Multiselect
        },
        methods: {
            limitText (count) {
                return `and ${count} additional`
            },
            syncDatas() {
                if (!_.isEmpty(this.datas)) {
                    const datasArray = this.dataType === 'objects' ? _.values(this.datas) : this.datas
                    this.options = datasArray
                    if (this.item.value !== null) {
                        const selectedStatuses = this.item.value.split(',')
                         this.value = datasArray.filter(status => status[this.trackBy] !== null ? _.includes(selectedStatuses, status[this.trackBy].toString()) : false)
                    }
                }
            },
            close() {
                let item = _.cloneDeep(this.item)
                item.checked = false
                this.$parent.$emit('update-' + this.item.name, item)
            },
            change(value) {
                let item = _.cloneDeep(this.item)
                item.value = value
                this.$parent.$emit('update-' + this.item.name, item)
            },
            onChange(value) {
                this.$emit('update:value', value)
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style type="text/css" lang="scss" rel="stylesheet/scss">
    .multiselect-block {
        margin-top: 28px;
        margin-bottom: -2px;
    }
    .multiselect__tags {
        border: 1px solid #fff !important;
    }
    .multiselect__tag {
        background-color: #e6e6e6 !important;
        border: 1px solid #adadad !important;
        color: #333 !important;
        border-radius: 3px !important;
        white-space: normal;
    }
    .multiselect__option--highlight {
        color: #333 !important;
        background-color: #e6e6e6 !important;
    }
    .multiselect, .multiselect__input, .multiselect__single {
        font-size: 12px !important;
    }
    .multiselect__tag-icon:after {
        color: #333 !important;
    }
    .multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
        background: #ccc !important;
    }
    .pagination > .active > a, .pagination > .active > a:hover, .pagination > .active > a:focus {
        z-index: 0 !important;
    }
    .btn.active {
        z-index: 0 !important;
    }
</style>