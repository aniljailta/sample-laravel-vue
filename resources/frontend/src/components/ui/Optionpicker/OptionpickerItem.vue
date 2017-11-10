<template>
    <tr>
        <td>{{ option.name }}</td>
        <td class="text-right option-picker__price">{{ option.unitPrice | money }}</td>
        <td class="text-center option-picker__button-countainer">
            <popover effect="scale" v-bind:header="false" placement="top" trigger="click" v-if="file">
                <div slot="content">
                    <pop-img ref="pop-img" :source="file" :src="file.publicPath"></pop-img>
                </div>
                <button class="btn btn-default btn-xs" type="button"><i class="fa fa-camera fa-fw"></i></button>
            </popover>
            <button class="btn btn-default btn-xs"
                    type="button"
                    v-bind:disabled="isLockedOption"
                    v-on:click.prevent="addCustomOption(option)">
                <i class="fa fa-plus fa-fw"></i>
            </button>
        </td>
    </tr>

</template>

<script type="text/babel">
    import Popover from 'vue-strap/src/Popover.vue'
    import PopImg from 'src/components/ui/PopImg.vue'

    export default {
        components: {
            Popover,
            PopImg
        },
        data() {
            return {}
        },
        props: {
            buildingOptions: {
                default() { return [] }
            },
            option: {
                default() { return {} }
            }
        },
        computed: {
            file() {
                if (this.option.files && this.option.files.length > 0) {
                    return _.last(this.option.files)
                }
                return null
            },
            isSelectedOption() {
                return (_.findIndex(this.buildingOptions, { optionId: this.option.id }) !== -1)
            },
            isLockedOption() {
                return ((_.findIndex(this.$parent.currentLockedCategories, { id: this.option.categoryId }) !== -1) && !this.isSelectedOption)
            }
        },
        methods: {
            addCustomOption() {
                if (this.isLockedOption) return
                this.$parent.addCustomOption(this.option)
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