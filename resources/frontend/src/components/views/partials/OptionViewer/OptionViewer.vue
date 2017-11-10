<template>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-condensed table-hover">
            <thead>
            <tr>
                <th>Option</th>
                <th>Price</th>
                <th>Quantity</th>
                <th class="text-right">Total</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="buildingOption in buildingOptions"
                    is="option-item"
                    v-bind:building-option="buildingOption"
                    :key="buildingOption.optionId">
                </tr>
                <tr v-show="buildingOptions.length == 0">
                    <td class="text-center" colspan="4">No selected options</td>
                </tr>

                <tr v-show="buildingOptions.length > 0">
                    <th colspan="3" class="text-right">Total Price of Options:</th>
                    <th class="text-right nowrap">{{ totalOptions | money }}</th>
                </tr>
            </tbody>
        </table>
    </div>

</template>

<script type="text/babel">
    import OptionItem from './OptionItem.vue'

    export default {
        name: 'option-viewer',
        components: {
            OptionItem
        },
        props: {
            totalOptions: {
                default: null
            },
            buildingOptions: {
                default() { return [] }
            }
        },
        methods: {
        }
    }
</script>

<style type="text/css">
</style>