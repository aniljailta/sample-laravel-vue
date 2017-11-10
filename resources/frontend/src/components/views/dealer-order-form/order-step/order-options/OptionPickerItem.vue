<template>

    <tr>
        <td>{{ option.name }}</td>
        <td class="text-right option-picker__price">{{ option.unitPrice | money }}</td>
        <td class="text-center option-picker__button-countainer">
            <button class="btn btn-default btn-xs"
                    type="button"
                    v-bind:disabled="isLockedOption"
                    v-on:click.prevent="addOption(option)">
                <i class="fa fa-plus fa-fw"></i>
            </button>
        </td>
    </tr>

</template>

<script type="text/babel">
    import Popover from 'vue-strap/src/Popover.vue'

    export default {
        components: {
            Popover
        },
        data() {
            return {}
        },
        props: {
            option: {
                default() { return {} }
            }
        },
        computed: {
            isSelectedOption() {
                return (_.findIndex(this.buildingOptions, { optionId: this.option.id }) !== -1)
            },
            isLockedOption() {
                return ((_.findIndex(this.$parent.currentLockedCategories, { id: this.option.categoryId }) !== -1) && !this.isSelectedOption)
            }
        },
        methods: {
            addOption(option) {
                if (this.isLockedOption) return
                this.$bus.$emit('add-order-option', option)
            }
        }
    }
</script>

<style type="text/css">
    .option-picker__button-countainer {
        position: relative;
        white-space: nowrap;
    }
</style>