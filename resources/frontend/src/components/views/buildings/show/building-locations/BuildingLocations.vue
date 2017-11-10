<template>

    <div class="no-footer">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed table-hover mb-none no-footer">
                <thead>
                    <tr>
                        <th class="text-left">Location</th>
                        <th class="text-left">Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(location, index_id) in buildingLocations"
                        is="building-location-item"
                        v-bind:location="location"
                        v-bind:editable="isEditable(index_id, location)"
                        :key="index_id">
                    </tr>

                    <tr v-show="buildingLocations.length == 0">
                        <td class="text-center" colspan="3">Location list is empty.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>

<script type="text/babel">
    import BuildingLocationItem from './BuildingLocationItem.vue'

    export default {
        data() {
            return {
            }
        },
        components: {
            BuildingLocationItem
        },
        props: {
            buildingLocations: {
                default() {
                    return {}
                }
            }
        },
        computed: {
        },
        methods: {
            isEditable(index, status) {
                let size = _.size(this.buildingLocations)
                return (index !== 0 && index === (size - 1))
            },
            refresh() {
                this.$parent.refresh('building-locations')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>