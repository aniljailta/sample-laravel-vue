<template>

    <div>

        <div class="btn-group">
            <div class="form-group">
                <div class="input-group input-group-sm color-picker__form">

                    <div class="input-group-btn"  role="group">
                        <button type="button"
                                class="btn btn-default color-picker__button"
                                v-on:click.prevent="togglePicker"
                                v-bind:style="getStyleColor(color)">
                            <span v-if="color !== null">{{ color.label }}</span>
                            <span v-else>Select a Color</span>
                        </button>
                    </div>

                    <input type="text"
                           class="form-control"
                           field="name"
                           v-bind:class="{'invalid': $v.$error}"
                           v-on:input="updateColor({ name: $event.target.value })"
                           v-bind:value="color ? color.name : ''"
                           v-bind:readonly="!color || color.type !== 'custom'"/>

                    <span v-if="color">
                            <input type="hidden" v-bind:value="color.id"/>
                            <input type="hidden" v-bind:value="color.type"/>
                        </span>

                </div>
                <div v-if="$v.color && $v.color.$dirty && $v.color.required === false" class="alert alert-danger" role="alert">Color is required.</div>
                <div v-if="$v.color && $v.color.$dirty && $v.color.name && $v.color.name.maxLength === false" class="alert alert-danger" role="alert">Max field length for color name is 50.</div>
            </div>
        </div>

        <colorpicker-swatches :colors="colors"
                              :show="show"
                              :picked="color"
                              v-on:hide-swatches="hideSwatches"
                              v-on:select-color="selectColor">
        </colorpicker-swatches>
    </div>

</template>

<script type="text/babel">
    import ColorpickerSwatches from 'components/ui/Colorpicker/ColorpickerSwatches'

    export default {
        components: {
            ColorpickerSwatches
        },
        props: {
            validation: {
                default() {
                    return {
                        color: {}
                    }
                }
            },
            colors: {
                default() { return {} }
            },
            color: {
                default: null
            }
        },
        data() {
            return {
                show: false
            }
        },
        computed: {},
        validations() {
            if (_.isFunction(this.validation)) {
                return this.validation(this)
            }
            return this.validation
        },
        methods: {
            updateColor(params) {
                this.$emit('update-color', params)
                this.$v.$touch()
            },
            togglePicker() {
                if (this.show) {
                    this.hideSwatches()
                } else {
                    this.showSwatches()
                }
            },
            showSwatches() {
                this.show = true
            },
            hideSwatches() {
                this.show = false
            },
            selectColor ({colorId, color}) {
                this.$emit('select-color', color)
                this.show = false
                this.$nextTick(() => {
                    this.$v.$touch()
                })
            },
            getStyleColor (color) {
                var style = {}
                if (color === null) return style

                if (color['hex'] !== null) {
                    style['background'] = color['hex']
                } else if (color.image) {
                    if (color['type'] === 'custom') {
                        style['background-color'] = '#fff'
                        style['background-image'] = 'url("' + color.image.publicPath + '")'
                        style['background-position-y'] = 'center'
                        style['background-position-x'] = 'center'
                        style['background-repeat-y'] = 'no-repeat'
                        style['background-repeat-x'] = 'no-repeat'
                    } else {
                        style['background-image'] = 'url("' + color.image.publicPath + '")'
                        style['background-position-y'] = 'center'
                        style['background-position-x'] = 'center'
                        style['background-repeat-y'] = 'no-repeat'
                        style['background-repeat-x'] = 'no-repeat'
                        style['background-size'] = '100% 100%'
                    }
                }

                return style
            }
        }
    }
</script>

<style type="text/css" scoped>
    .color-picker__form {
        margin: 5px 0 5px 0;
    }
</style>

<style type="text/css">
    .color-picker__button {
        color: #fff !important;
        text-shadow: 1px 1px #0a001f;
    }
</style>