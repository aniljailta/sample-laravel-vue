<template>

    <div v-bind:class="blockClass">
        <div class="text-center loader-block" v-if="with_loader && process.running">
            <div>
                <p class="text-muted" v-show="process.text">{{ process.text }}</p>
                <i class="fa fa-circle-o-notch fa-spin fa-4x text-muted"></i>
            </div>
        </div>

        <div v-if="process.success && mode === 'row'" class="alert alert-success text-center" role="alert">
            <p v-if=" typeof process.success === 'string' ">{{ process.success }}</p>
            <div v-else v-for="messages in process.success">
                <p v-if=" typeof messages === 'string' ">{{ messages }}</p>
                <p v-else v-for="message in messages">{{ message }}</p>
            </div>
        </div>

        <div v-if="process.error && mode === 'row'" class="alert alert-danger text-center" role="alert">
            <div>
                <p v-if=" typeof process.error === 'string' ">{{ process.error }}</p>
                <div v-else v-for="messages in process.error">
                    <p v-if=" typeof messages === 'string' ">{{ messages }}</p>
                    <p v-else v-for="message in messages">{{ message }}</p>
                </div>
            </div>
        </div>

        <div v-if="process.success && mode === 'sweet'" class="sweet-alert showSweetAlert">
            <div class="sa-icon sa-success animate" v-if="with_icon">
                <span class="sa-line sa-tip animateSuccessTip"></span>
                <span class="sa-line sa-long animateSuccessLong"></span>

                <div class="sa-placeholder"></div>
                <div class="sa-fix"></div>
            </div>

            <div class="lead" style="font-size: 12px">
                <p v-if=" typeof process.success === 'string' ">{{ process.success }}</p>
                <div v-else v-for="messages in process.success">
                    <p v-if=" typeof messages === 'string' ">{{ messages }}</p>
                    <p v-else v-for="message in messages">
                        {{ message }}
                    </p>
                </div>
            </div>

            <slot name="error"></slot>
        </div>

        <div v-if="process.error && mode === 'sweet'" class="sweet-alert showSweetAlert">
            <div class="sa-icon sa-error animateErrorIcon" v-if="with_icon">
                        <span class="sa-x-mark animateXMark">
                            <span class="sa-line sa-left"></span>
                            <span class="sa-line sa-right"></span>
                        </span>
            </div>
            <div class="lead" style="font-size: 12px">
                <p v-if=" typeof process.error === 'string' ">{{ process.error }}</p>
                <div v-else v-for="messages in process.error">
                    <p v-if=" typeof messages === 'string' ">{{ messages }}</p>
                    <p v-else v-for="message in messages">
                        {{ message }}
                    </p>
                </div>
            </div>

            <slot name="error"></slot>
        </div>
    </div>

</template>

<script type="text/babel">
    export default {
        name: 'data-process',
        data() {
            return {}
        },
        components: {},
        props: {
            process: {
                default() {
                    return {}
                }
            },
            with_icon: {
                type: Boolean,
                default: true
            },
            with_loader: {
                type: Boolean,
                default: true
            },
            mode: {
                type: String,
                default: 'sweet'
            }
        },
        computed: {
            blockClass() {
                if (this.process.type !== 'data' && this.process.running) {
                    return 'modal-alert view-overlay view-overlay-abs block'
                }

                return 'block-alert'
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .block-alert {
        .sweet-alert.showSweetAlert {
            background-color: inherit;
            width: auto;
            padding: 0;
            border-radius: 5px;
            position: initial;
            left: initial;
            top: initial;
            margin-left: 0;
            margin-top: 0;
            overflow: initial;
            display: block;
            z-index: inherit;
        }
    }
</style>