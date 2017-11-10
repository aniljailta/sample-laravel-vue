<template>
    <div v-show="show"
         class="modal-container"
         transition="modal"
         style="float: none; padding: 0; position: fixed"
         v-bind:class="class"
         v-bind:style="style"
         v-on:mousedown="startDrag"
         v-on:touchstart="startDrag"
         v-on:mousemove="onDrag"
         v-on:touchmove="onDrag"
         v-on:mouseup="stopDrag"
         v-on:touchend="stopDrag"
         v-on:mouseleave="stopDrag">
         <slot></slot>
    </div>
</template>

<script type="text/babel">
    export default {
        name: 'Modal',
        props: {
            show: {
                type: Boolean,
                default: false,
                requred: true
            },
            class: {
                type: String,
                default: ''
            },
            openModalCallback: {
                type: Function,
                default: function() {
                }
            },
            closeModalCallback: {
                type: Function,
                default: function() {
                }
            },
            closeModalMethod: {
                type: Function,
                default: function() {
                }
            }
        },
        data() {
            return {
                dragging: false,
                // record drag start point
                position: { x: 0, y: 0 }
            }
        },
        computed: {
            style() {
                return {
                    top: this.position.y + 'px',
                    left: this.position.x + 'px'
                }
            }
        },
        mounted() {
            document.addEventListener('keydown', (e) => {
                if (this.show && e.keyCode === 27) {
                    this.closeModalMethod()
                }
            })

            console.log(this.$el)
        },
        watch: {
            show(newVal, oldVal) {
                if (newVal === true) {
                    this.openModalCallback()
                }

                if (newVal === false) {
                    this.closeModalCallback()
                }
            }
        },
        methods: {
            startDrag: function (e) {
                console.log('start_drag')
                e = e.changedTouches ? e.changedTouches[0] : e
                this.dragging = true
                this.position.x = e.pageX
                this.position.y = e.pageY
            },
            onDrag: function (e) {
                if (this.dragging) {
                    console.log('on_drag')
                    e = e.changedTouches ? e.changedTouches[0] : e
                    this.position.x = e.pageX
                    this.position.y = e.pageY
                }
            },
            stopDrag: function () {
                if (this.dragging) {
                    console.log('stop_drag')
                    this.dragging = false
                }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    * {
        box-sizing: border-box;
    }
</style>