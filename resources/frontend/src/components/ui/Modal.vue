<template>

    <div v-if="show & draggable" class="modal-container" :class="modalClass" :style="modalStyle" v-drag:draggable
         style="position: absolute">
        <slot></slot>
    </div>

    <div v-else-if="show & !draggable" class="modal-mask" :style="maskStyle">
        <transition name="modal">
            <div class="modal-container" :class="modalClass" :style="modalStyle">
                <slot></slot>
            </div>
        </transition>
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
            draggable: {
                type: Boolean,
                default: false
            },
            modalClass: {
                type: String,
                default: ''
            },
            modalStyle: {
                type: Object,
                default: null
            },
            maskStyle: {
                type: Object,
                default: null
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
            }
        },
        mounted() {
            /* document.addEventListener('keydown', (e) => {
                if (this.show && e.keyCode === 27) {
                    this.closeModalMethod()
                }
            })*/
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
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" src="src/assets/modal.scss"></style>
<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    * {
        box-sizing: border-box;
    }

    .modal-container {
        margin-bottom: 3em;
    }
</style>