<template>

    <div class="color-picker__swatches" v-if="show">
        <div class="color-picker__swatches__box">

            <div class="color-picker__swatches-el" v-for="(color, color_id) in colors">
                <div class="color-picker__swatches__color-it"
                     v-on:click="selectColor({color_id, color})"
                     v-bind:style="getStyleColor(color)">

                    <span>{{ color.label  }}</span>
                    <div class="color-picker__swatches__pick" v-show="picked && color.id == picked.id" style="color: #fbe8aa">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="pull-right" style="margin: 0.5em;">
            <button type="button" class="close" style="font-size: 12px" aria-label="Close" v-on:click.prevent="hideSwatches()">
                <span aria-hidden="true">&times;</span> Close
            </button>
        </div>
    </div>

</template>

<script type="text/babel">
    export default {

        data () {
            return {
            }
        },
        props: {
            picked: {
                default: null
            },
            show: {
                type: Boolean,
                default: false
            },
            colors: {
                // type: Object,
                // default: {},
                required: true
            }
        },
        methods: {
            selectColor(color) {
                this.$emit('select-color', color)
            },
            hideSwatches() {
                this.$emit('hide-swatches')
            },
            getStyleColor (color) {
                return this.$parent.getStyleColor(color)
            }
        }
    }
</script>

<style type="text/css">
    .color-picker__swatches {
        overflow: hidden;
        position: absolute;
        z-index: 999;
        font-size: 12px;
        color: #ffffff;
        text-shadow: 1px 1px #0a001f;
        width: 320px;
        overflow-y: auto;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .12), 0 2px 5px rgba(0, 0, 0, .16);
    }

    .color-picker__swatches__box {
        padding: 0.5em;
        overflow: hidden;
        display: flex;
        flex-flow: row wrap;
        justify-content: flex-start;
    }

    .color-picker__swatches-el {
        width: 33%;
    }

    .color-picker__swatches__color-it {
        height: 4em;
        cursor: pointer;
        margin: 0.3em;
        padding: 3px;
        overflow: hidden;
        -ms-border-radius: 2px 2px 0 0;
        -moz-border-radius: 2px 2px 0 0;
        -o-border-radius: 2px 2px 0 0;
        -webkit-border-radius: 2px 2px 0 0;
        border-radius: 2px 2px 0 0;
        background: #ffffff no-repeat;
    }

    .color-picker__swatches__pick {
        fill: rgb(255, 255, 255);
        display: block;
        float: right;
    }
</style>