<template>

    <div>
        <div class="btn-group">
            <div class="form-group">
                <div class="input-group input-group-sm color-picker__form">

                    <div class="input-group-btn" role="group">
                        <button type="button"
                                class="btn btn-default color-picker__button"
                                v-bind:style="getStyleColor(color)">
                            <span v-if="color !== null">{{ color.label }}</span>
                            <span v-else>Select a Color</span>
                        </button>
                    </div>

                    <input type="text"
                           autocomplete="off"
                           class="form-control"
                           v-bind:value="color ? color.name : ''"
                           v-bind:readonly="true"/>
                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    export default {
        components: {},
        props: {
            color: {
                default: null
            }
        },
        data() {
            return {}
        },
        created() {
            if (this.color) {
                let currentColor = this.color
                let foundColor = _.find(this.colors, function(el) {
                    return el.id === parseInt(currentColor.id)
                }, this)

                if (foundColor) {
                    currentColor = { ...foundColor, ...currentColor }
                }

                this.color = currentColor
            }
        },
        computed: {},
        methods: {
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