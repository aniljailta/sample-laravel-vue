<template>

    <div>
        <validator name="validator"
                   @valid="revalidate(true)"
                   @invalid="revalidate(false)">
            
            <div class="row">
                <div class="col-xs-4">
                    <div class="color-picker-picked"
                         v-on:click.prevent="togglePicker"
                         v-bind:style="getStyleColor(picked)">
                        <span v-if="picked !== null">{{ picked.label }}</span>
                        <span v-else>Select a Color</span>
                    </div>
                </div>
        
                <div class="col-xs-8">
                    <button class="btn btn-default"
                            style="margin-bottom: 0.5em"
                            v-on:click.prevent="togglePicker"
                            v-on:keydown.esc="togglePicker">
                        <i class="fa fa-eyedropper" aria-hidden="true"></i> Choose Color
                    </button>
                    <button class="btn btn-default"
                            style="margin-bottom: 0.5em"
                            v-on:click.prevent="goToStep('next')">Next<i class="fa fa-arrow-right fa-fw"></i>
                    </button>
        
                    <input type="text"
                           name="picked"
                           autocomplete="off"
                           class="form-control"
                           v-on:input="updateColorName($event.target.value)"
                           v-bind:value="picked ? picked.name : ''"
                           v-bind:disabled="!picked || picked.type !== 'custom' "
                           v-validate:color_name="validateRules.color_name"/>
                    <input type="hidden"
                           initial="off"
                           v-bind:value="picked"
                           v-validate:picked="validateRules.picked">
                </div>
            </div>

            <colorpicker-swatches :colors="colors" :show="show" :picked="picked" :select-color="selectColor"></colorpicker-swatches>

            <div v-if="$validator.picked && $validator.picked.cond_required" class="alert alert-danger" role="alert">Body color is required.</div>
            <div v-if="$validator.color_name && $validator.color_name.color_name" class="alert alert-danger" role="alert">Max field length for Color Name is 50</div>

            <div v-if="picked && picked.type === 'custom'" class="alert alert-info" role="alert">Enter the Color code or leave it empty.</div>

        </validator>
    </div>
</template>

<script type="text/babel">
    import ColorpickerSwatches from './ColorpickerSwatches'
    import vueValidate from 'src/mixins/vue-validate'
    import condRequired from 'src/validators/cond_required'
    import colorName from 'src/validators/color_name'

    export default {
        mixins: [vueValidate],
        components: {
            ColorpickerSwatches
        },
        data () {
            return {
                picked: null,
                show: false
            }
        },
        methods: {
            updateColorName() {
            },
            selectColor (colorId, color) {
                this.picked = color
                this.hideSwatches()
            },
            showSwatches() {
                this.show = true
            },
            hideSwatches() {
                this.show = false
            },
            togglePicker() {
                if (this.show) {
                    this.hideSwatches()
                } else {
                    this.showSwatches()
                }
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
            },
            goToStep() {
            },
            revalidate() {
            }
        },
        validators: {
            cond_required: condRequired,
            color_name: colorName
        }
    }
</script>

<style type="text/css">
</style>