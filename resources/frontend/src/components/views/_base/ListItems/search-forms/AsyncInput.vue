<template>

    <group-item v-on:close="close" v-bind:label="item.name">
        <div class="multiselect-block">
            <multiselect v-bind:style="{ width: blockWidth }"
                         v-model="selectedValues"
                         :show-labels="false"
                         id="ajax"
                         label="name"
                         track-by="name"
                         placeholder="Type to search"
                         :options="autocompleteValues"
                         :multiple="false"
                         :searchable="true"
                         :loading="isLoading"
                         :internal-search="false"
                         :hide-selected="true"
                         :clear-on-select="false"
                         :close-on-select="true"
                         :options-limit="100"
                         :limit="3"
                         @select="change"
                         @search-change="autoComplete">
                <span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
            </multiselect>
        </div>
    </group-item>

</template>

<script type="text/babel">
    import GroupItem from './GroupItem.vue'
    import Multiselect from 'vue-multiselect'
    export default {
        data() {
            return {
                selectedValues: [{name: this.item.value}]
            }
        },
        props: {
            item: {
                required: true,
                default() {
                    return {}
                }
            },
            autocompleteValues: {
                default() {
                    return []
                }
            },
            isLoading: {
                default() {
                    return false
                }
            },
            blockWidth: {
                default: '214px'
            }
        },
        components: {
            GroupItem,
            Multiselect
        },
        methods: {
            close() {
                let item = _.cloneDeep(this.item)
                item.checked = false
                this.$parent.$emit('update-search', item)
            },
            change(value) {
                let item = _.cloneDeep(this.item)
                item.value = value.name
                this.$parent.$emit('update-search', item)
            },
            autoComplete (query) {
                if (query !== '') {
                    this.$emit('fetch-autocomplete', query)
                }
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
    .multiselect__option--highlight {
        color: #333 !important;
        background-color: #e6e6e6 !important;
    }
</style>