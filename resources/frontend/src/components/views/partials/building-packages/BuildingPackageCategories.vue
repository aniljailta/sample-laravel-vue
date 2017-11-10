<template>

    <div class="col-md-12 col-xs-12">

        <h4 class="list-group-item-heading">Building packages:</h4>

        <div class="btn-group btn-group" data-toggle="buttons" v-if="!dataProcess.running">
            <button class="btn btn-default"
                    v-on:click="currentCategory = null"
                    v-bind:class="{ 'active': currentCategory === null }">
                Total <span class="badge">{{ countPackages }} building packages in {{ countCategories }} categories</span>
            </button>
            <button class="btn btn-default"
                    v-bind:class="{ 'active': currentCategory !== null }"
                    v-if="currentCategory">
                {{ currentCategory.name }} <span class="badge">{{ currentCategory.count }}</span>
            </button>
        </div>

        <data-process :process="dataProcess" :with_loader="true" :mode="'row'"></data-process>

        <div class="text-center" style="margin: 0.5em 0 0.5em 0" v-show="canReloadList">
            <button class="btn btn-sm btn-default" v-on:click="reloadBuildingPackages">Re-load Building Packages</button>
        </div>

        <div class="row list-group list-container">
            <building-package-placeholder v-if="placeholder && !currentCategory" v-on:next-step="nextStep"></building-package-placeholder>

            <template v-if="countPackages">
                <building-package-category-item v-for="(category, categoryIndex) in packagesPerCategory" :key="categoryIndex"
                                                v-bind:item="category"
                                                v-on:show-category="showCategory"
                                                v-if="!currentCategory">
                </building-package-category-item>
                <building-packages v-if="currentCategory"
                                   v-bind:item="currentCategory"
                                   v-on:select-building-package="selectBuildingPackage">
                </building-packages>
            </template>
        </div>
    </div>

</template>

<script type="text/babel">
    import baseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import BuildingPackageCategoryItem from './BuildingPackageCategoryItem.vue'
    import BuildingPackagePlaceholder from './BuildingPackagePlaceholder.vue'
    import BuildingPackages from './BuildingPackages.vue'

    export default {
        extends: baseDataItem,
        data() {
            return {
                dataProcess: {
                    type: 'form',
                    running: false
                },
                currentCategory: null
            }
        },
        components: {
            BuildingPackageCategoryItem,
            BuildingPackagePlaceholder,
            BuildingPackages
        },
        props: {
            buildingPackages: {
                required: true
            },
            placeholder: {
                default: false
            },
            reloadList: {
                default: false
            }
        },
        created() {
            // this.reloadBuildingPackages()
        },
        computed: {
            countPackages() {
                return _.size(this.buildingPackages)
            },
            countCategories() {
                return _.size(this.packagesPerCategory)
            },
            packagesPerCategory() {
                let categories = {}

                _.each(this.buildingPackages, function (el, index) {
                    let category = {}
                    if (_.isNil(el.category)) {
                        return false
                    } else {
                        if (_.isUndefined(categories[el.category.id])) {
                            category = _.cloneDeep(el.category)
                            category.count = 0
                            category.packages = []
                        } else {
                            category = categories[el.category.id]
                        }
                    }

                    category.packages.push(el)
                    category.count ++
                    categories[category.id] = category
                })
                return categories
            },
            canReloadList() {
                return (!this.dataProcess.running && (this.dataProcess.error || this.countPackages === 0) && this.reloadList)
            }
        },
        methods: {
            nextStep() {
                this.$emit('go-to-step', 'next')
            },
            showCategory(item) {
                this.currentCategory = item
            },
            selectBuildingPackage(item) {
                this.$emit('select-building-package', item)
            },
            reloadBuildingPackages() {
                this.$emit('reload-building-packages')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .list-container {
        padding: 5px 10px 0 10px;
    }
</style>