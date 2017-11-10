<template>

    <div>
        <div v-for="optionCategory in currentAvailableCategories">
            <option-expander v-bind:option-category="optionCategory" v-on:toggle-category="togglePicker"></option-expander>

            <div class="option-picker__swatches" v-if="optionCategory.name == showCategory">
                <div class="option-picker__swatches__box">

                    <table class="table table-striped table-condensed table-bordered">
                        <thead>
                        <tr>
                            <th>Option</th>
                            <th class="text-right option-picker__price">Price</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr is="option-item"
                            v-if="showCategory !== null"
                            v-for="(option, option_id) in currentAvailableOptions[showCategory]"
                            v-bind:option="option"
                            v-bind:building-options="buildingOptions"
                            :key="option_id">
                        </tr>
                        </tbody>
                    </table>

                </div>
                <div class="pull-right" style="margin: 0.5em;">
                    <button type="button" class="close" style="font-size: 12px" aria-label="Close" v-on:click.prevent="hidePicker()">
                        <span aria-hidden="true">&times;</span> Hide
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import OptionItem from './OptionpickerItem.vue'
    import OptionExpander from './OptionpickerExpander.vue'

    export default {
        components: {
            OptionItem,
            OptionExpander
        },
        data() {
            return {
                showCategory: null
            }
        },
        props: {
            buildingModel: {
                default: {}
            },
            // to see/control already selected options
            buildingOptions: {
                default() { return [] }
            },
            options: {
                default() { return [] }
            },
            optionCategories: {
                default() { return [] }
            }
        },
        mounted() {
            // v1 compiled
            this.$on('option-picker-toggle-category', this.togglePicker)
            this.$on('option-picker-show-category', this.showPicker)
            this.$on('option-picker-hide-category', this.hidePicker)

            this.$parent.$emit('option-picker-ready')
        },
        computed: {
            buildingModelID() {
                if (_.isUndefined(this.buildingModel.id)) return null
                return _.toInteger(this.buildingModel.id)
            },
            currentAvailableCategories() {
                if (this.buildingModelID == null) return []

                var vm = this
                var currentAvailableCategories = {}

                _.each(this.currentAvailableOptions, function (item, key) {
                    let optionCategory = _.find(vm.optionCategories, { name: key })
                    if (optionCategory) {
                        // Count number of options per category
                        // ( empty object with assigning prop (!important for vue) )
                        currentAvailableCategories[key] = Object.assign({}, optionCategory, {count: item.length})
                    }
                }, this)

                return currentAvailableCategories
            },
            currentAvailableOptions() {
                if (this.buildingModelID == null) return []

                var buildingModelID = this.buildingModelID
                var currentAvailableOptions = _.filter(this.options, function (item) {
                    return _.includes(_.map(item.allowableModels, 'id'), buildingModelID)
                }, this)

                return _.groupBy(currentAvailableOptions, option => option.category.name)
            },
            currentRequiredCategories() {
                let cats = _.filter(this.optionCategories, (category) => {
                    // search only required categories
                    if (category.isRequired === true) {
                        // if option already selected - remove from required categories list
                        return (_.findIndex(this.buildingOptions, (buildingOption) => buildingOption.option.categoryId === category.id) === -1)
                    }
                    return false
                })
                return cats || []
            },
            currentLockCategories() {
                let cats = _.filter(this.optionCategories, (category) => {
                    return !_.isNull(category.qtyLimit)
                })
                return cats || []
            },
            currentLockedCategories() {
                return _.filter(this.currentLockCategories, (category) => {
                    // count selected building option items per category
                    let currentCount = _.filter(this.buildingOptions, (buildingOption) => buildingOption.option.categoryId === category.id).length
                    return (category.qtyLimit <= currentCount)
                })
            }
        },
        methods: {
            handlerClick(categoryId, category) {
                this.hidePicker()
            },
            showPicker(name) {
                this.showCategory = name
            },
            hidePicker() {
                this.showCategory = null
            },
            togglePicker(optionCategory) {
                if (this.showCategory === optionCategory.name) {
                    this.$emit('option-picker-hide-category')
                } else {
                    this.$emit('option-picker-show-category', optionCategory.name)
                }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .option-picker__swatches {
        /* overflow: hidden;
        overflow-y: auto; */
        margin-bottom: 0.5em;
        width: 100%;
        background-color: #fff;
    }

    .option-picker__swatches__box {
        padding: 0.5em;
        /* overflow: hidden;*/
        display: flex;
        flex-flow: row wrap;
        justify-content: flex-start;
    }

    .option-picker__button-countainer {
        position: relative;
        white-space: nowrap;
    }

    .option-picker__price {
        white-space: nowrap;
    }

    .popover {
        max-width: none;
    }
</style>