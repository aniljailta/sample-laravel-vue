<template>

    <div class="multiselect-block async-component">
        <multiselect v-bind:style="{ width: blockWidth }" v-model="selectedValues" :show-labels="false" id="ajax" label="name" track-by="id" placeholder="Type to search" :options="autocompleteValues" :multiple="false" :searchable="true" :loading="isLoading" :internal-search="false" :hide-selected="true" :clear-on-select="false" :close-on-select="true" :options-limit="100" :limit="3" @select="change" @search-change="autoComplete"><span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
        </multiselect>
    </div>

</template>

<script type="text/babel">
    import Multiselect from 'vue-multiselect'
    export default {
        data() {
            return {
                selectedValues: [this.item]
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
            Multiselect
        },
        methods: {
            close() {

            },
            change(selectedValue) {
                this.$parent.$emit('update-async', selectedValue)
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
    .async-component {
        margin-top: 0;
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