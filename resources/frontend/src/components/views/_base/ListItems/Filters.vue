<template>

    <div class="list-tools">
        <h4>Filters
            <small><a v-on:click="show = !show" class="pointer">{{ show === true ? 'Hide' : 'Show' }}</a></small>
        </h4>

        <div class="report-tools" v-show="show">
            <div class="clearfix" v-if="dimensions.length > 0">
                <div class="report-tool-title"><h5>Dimensions</h5></div>
                <div class="report-tool">
                    <dimensions ref="dimensions" v-bind:items="dimensions" v-on:update-dimension="updateDimension"></dimensions>
                </div>
            </div>

            <div class="clearfix" v-if="selectableTotals.length > 0">
                <div class="report-tool-title"><h5>Totals</h5></div>
                <div class="report-tool">
                    <totals ref="totals" v-bind:items="selectableTotals" v-on:update-total="updateTotal"></totals>
                </div>
            </div>

            <div class="clearfix" v-if="searches.length > 0">
                <div class="report-tool-title"><h5>Search by</h5></div>
                <div class="report-tool">
                    <searches ref="searches" v-bind:items="searches" v-on:update-search="updateSearch"></searches>
                </div>
            </div>

            <search-form ref="searchForm"
                         v-show="countSearches"
                         v-on:data-ready="depReady"
                         v-on:data-failed="depFailed"
                         v-bind:searches="searches">
            </search-form>
        </div>
        <div class="pull-right text-right filters__row-counter-right">
            <label class="typo__label">Row Count</label>
            <multiselect :style="{ width: '214px' }"
                         v-model="value"
                         :options="options"
                         :searchable="false"
                         :close-on-select="true"
                         @select="selectCount"
                         :show-labels="false"
                         placeholder="Select"></multiselect>
        </div>

    </div>

</template>

<script type="text/babel">
    import Dimensions from './filters/Dimensions.vue'
    import Totals from './filters/Totals.vue'
    import Searches from './filters/Searches.vue'
    import SearchForm from './filters/SearchForm.vue'
    // mixings
    import DataProcessMixin from 'src/mixins/vue-data-process'

    import Multiselect from 'vue-multiselect'

    export default {
        mixins: [DataProcessMixin],
        data() {
            return {
                show: false,
                value: 'System Default',
                options: ['System Default', '10', '50', '100', '1000']
            }
        },
        components: {
            Dimensions,
            Totals,
            Searches,
            SearchForm,
            Multiselect
        },
        props: {
            dimensions: {
                default() { return [] }
            },
            totals: {
                default() { return [] }
            },
            searches: {
                default() { return [] }
            }
        },
        computed: {
            countSearches() {
                return _.size(_.filter(this.searches, { 'checked': true }))
            },
            selectableTotals() {
                return _.filter(this.totals, function (item) {
                    return (_.isUndefined(item.selectable) || item.selectable === true)
                })
            }
        },
        methods: {
            depReady() {
                this.$emit('data-ready')
            },
            depFailed(response) {
                this.$parent.$emit('data-failed', response)
            },
            updateDimension(item) {
                this.$parent.$emit('update-dimension', item)
            },
            updateTotal(item) {
                this.$parent.$emit('update-total', item)
            },
            updateSearch(item) {
                this.$parent.$emit('update-search', item)
            },
            selectCount(value) {
                this.$parent.$emit('update-per-page', value)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .filters__row-counter-right {
        .multiselect__spinner {
            width: 20px;
        }
        .multiselect__select {
            width: 20px;
            padding: 4px 0px;
        }
        .multiselect__tags {
            padding-right: 25px;
        }

        .typo__label {
            margin-top: 5px;
            margin-bottom: 0px;
        }

        .multiselect__single {
            text-align: right;
        }

        .multiselect__element {
            text-align: right;
        }
    }
</style>