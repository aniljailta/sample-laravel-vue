<template>

    <tr>
        <td>
            {{ buildingOption.option.name }}
            <color v-if="buildingOption.color"
                   v-bind:color="buildingOption.color"></color>
        </td>
        <td class="nowrap">
            {{ filters.money(buildingOption.unitPrice) }}
        </td>

        <td class="text-left">
            {{ buildingOption.quantity }}
        </td>
        <td class="text-right nowrap">
            {{ totalOption | money }}
        </td>
    </tr>

</template>

<script type="text/babel">
    import Color from './Color.vue'
    import Popover from 'vue-strap/src/Popover.vue'

    export default {
        components: {
            Color,
            Popover
        },
        data() {
            return {}
        },
        props: {
            buildingOption: {
                default() { return {} }
            }
        },
        computed: {
            totalOption() {
                return this.buildingOption.unitPrice * this.buildingOption.quantity
            }
        },
        methods: {}
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .nowrap {
        white-space: nowrap;
    }
</style>

<style type="text/css">
    .option-picker__button-countainer {
        position: relative;
        white-space: nowrap;
    }
</style>