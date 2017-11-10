<template>

    <tr>
        <td></td>
        <td colspan="2">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Option</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th class="text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="buildingPackageOption in options">
                        <td>{{ buildingPackageOption.option.name }}</td>
                        <td class="nowrap">
                            <div class="form-group">
                                {{ filters.money(buildingPackageOption.unitPrice) }}
                            </div>
                        </td>
                        <td class="text-center nowrap">{{ buildingPackageOption.quantity }}</td>
                        <td class="text-right nowrap">{{ buildingPackageOption.unitPrice * buildingPackageOption.quantity | money }}</td>
                    </tr>
                    <tr v-show="options.length == 0">
                        <td class="text-center" colspan="4">No options.</td>
                    </tr>

                    <tr v-show="options.length > 0">
                        <th colspan="3" class="text-right">Total Price of Options:</th>
                        <th class="text-right">{{ totalOptions | money }}</th>
                    </tr>
                    </tbody>
                </table>
            </div>

        </td>
    </tr>

</template>

<script type="text/babel">
    export default {
        data() {
            return {}
        },
        components: {
        },
        props: {
            options: {
                default() {
                    return []
                }
            }
        },
        computed: {
            totalOptions () {
                if (this.options.length === 0) {
                    return 0
                }

                return _.reduce(this.options, function (memo, option) {
                    return memo + (option.unitPrice * option.quantity)
                }, 0, this)
            }
        },
        methods: {
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>


</style>