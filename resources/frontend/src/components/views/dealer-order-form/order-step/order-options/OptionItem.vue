<template>

    <tr>
        <td>
            {{ option.name }}
        </td>
        <td class="nowrap">
            <div v-if="editable" class="btn-group">
                <div class="form-group">
                    <div class="input-group input-group-xs" style="width: 75px">
                        <span class="input-group-addon">$</span>
                        <input number
                               type="number"
                               class="form-control"
                               initial="off"
                               v-on:input="updateOption(option, { unitPrice: parseFloat($event.target.value) })"
                               v-bind:value="option.unitPrice"/>
                    </div>
                </div>
            </div>
            <span v-else>{{ filters.money(option.unitPrice) }}</span>
        </td>

        <td class="text-left">
            <div class="btn-group">
                <div class="form-group">
                    <div class="input-group input-group-xs" style="width: 100px">
                        <div class="input-group-btn btn-group-xs" role="group">
                            <button type="button" class="btn btn-default" v-on:click="increaseOption(option)">
                                <i class="fa fa-plus fa-fw"></i>
                            </button>
                        </div>
                        <input number
                               type="number"
                               class="form-control"
                               initial="off"
                               style="width: 50px"
                               v-bind:min="option.minQuantity || null"
                               v-on:input="updateOption(option, { quantity: parseInt($event.target.value) })"
                               v-bind:value="option.quantity"/>
                        <div class="input-group-btn btn-group-xs" role="group">
                            <button type="button" class="btn btn-default"
                                    v-on:click="decreaseOption(option)"
                                    v-bind:disabled="!canDecrease">
                                <i class="fa fa-minus fa-fw"></i>
                            </button>
                            <button type="button" class="btn btn-default"
                                    v-on:click="removeOption(option)"
                                    v-bind:disabled="!canRemove">
                                <i class="fa fa-times fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <span v-if="option.minQuantity" class="label label-default nowrap">Min. quantity: {{ option.minQuantity }}</span>
        </td>
        <td class="text-right nowrap">{{ option.unitPrice * option.quantity | money }}</td>

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
            editable: {
                default: false
            },
            validation: {
                default() {
                    return {}
                }
            },
            option: {
                default() { return {} }
            },
            orderModel: {
                default() { return {} }
            }
        },
        created() {
            if (!_.isUndefined(this.color)) {
                this.$watch('color', this.onChangeColor, {
                    deep: true
                })
            }
        },
        computed: {
            opt() {
                return this.option
            },
            canDecrease() {
                if (this.opt.quantity <= this.opt.minQuantity) {
                    return false
                }
                if (_.isArray(this.opt.parentOptions) && this.opt.parentOptions.length >= this.opt.quantity) {
                    return false
                }
                return true
            },
            canRemove() {
                if (_.isArray(this.opt.parentOptions)) {
                    return (this.opt.parentOptions.length === 0)
                }
                return true
            }
        },
        methods: {
            validate() {
                if (this.$refs.colorPicker) {
                    this.$refs.colorPicker.$v.$touch()
                    return !this.$refs.colorPicker.$v.$error
                }
                return true
            },
            updateOption(option, params) {
                this.$bus.$emit('update-order-option', option, params)
            },
            removeOption(option) {
                this.$bus.$emit('remove-order-option', option)
            },
            increaseOption(option) {
                this.$bus.$emit('increase-order-option', option)
            },
            decreaseOption(option) {
                this.$bus.$emit('decrease-order-option', option)
            }
        }
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