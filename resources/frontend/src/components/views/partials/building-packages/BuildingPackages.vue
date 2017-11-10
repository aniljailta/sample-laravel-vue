<template>

    <div class="" style="padding-top: 5px;">
        <ul class="list-group list-container" v-show="countPackages">
            <building-package-item v-for="(buildingPackage, buildingPackageIndex) in buildingPackages"
                                   v-on:select="selectBuildingPackage"
                                   :key="buildingPackageIndex"
                                   :item="buildingPackage">
            </building-package-item>
        </ul>
    </div>

</template>

<script type="text/babel">
    import BuildingPackageItem from './BuildingPackageItem.vue'

    export default {
        data() {
            return {
                process: {
                    text: 'Loading..',
                    running: false,
                    success: null,
                    error: null
                }
            }
        },
        components: {
            BuildingPackageItem
        },
        props: {
            item: {
                required: true
            }
        },
        computed: {
            buildingPackages() {
                return this.item.packages
            },
            countPackages() {
                return _.size(this.buildingPackages)
            }
        },
        methods: {
            selectBuildingPackage(item) {
                this.$emit('select-building-package', item)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .list-container {
        /* max-height: 320px;
        overflow: auto; */
        border: 1px solid #eee;
    }
</style>