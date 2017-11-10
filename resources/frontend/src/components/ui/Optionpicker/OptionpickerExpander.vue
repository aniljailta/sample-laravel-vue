<template>

    <button type="button"
            class="btn btn-block option-picker__toggle-button"
            v-bind:class="styleClass"
            v-on:click.prevent="togglePicker(optionCategory)">
        {{ optionCategory.name }} Options
        <span class="badge">{{ optionCategory.count }}</span>
        <span class="label" v-show="isRequired">* required</span>
    </button>

</template>

<script type="text/babel">
    export default {
        components: {},
        data() {
            return {}
        },
        props: {
            optionCategory: {
                default() { return {} }
            }
        },
        computed: {
            styleClass() {
                if (this.isRequired) return 'btn-danger'
                if (this.optionCategory.name === this.showCategory) return 'btn-primary'
                return 'btn-default'
            },
            showCategory() {
                return this.$parent.showCategory
            },
            isRequired() {
                return (_.findIndex(this.$parent.currentRequiredCategories, { id: this.optionCategory.id }) !== -1)
            }
        },
        methods: {
            togglePicker(optionCategory) {
                this.$emit('toggle-category', optionCategory)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .option-picker__toggle-button {
        margin-bottom: 0.3em;
    }
</style>