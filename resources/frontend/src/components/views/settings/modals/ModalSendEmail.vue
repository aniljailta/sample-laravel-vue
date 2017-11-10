<template>

    <div>
        <modal :show="show"
               :modal-class="modalClass"
               :modal-style="modalStyle"
               :mask-style="maskStyle">

            <div>
                <div class="panel-heading">
                    <h2 class="panel-title">Send test email</h2>
                </div>
                <div class="panel-body modal-body">
                    <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>To:</label>
                                <input type="text"
                                       class="form-control"
                                       :class="{'invalid': $v.emailForTest.$error}"
                                       :value="emailForTest"
                                       @input="emailForTest = $event.target.value"
                                       @blur="$v.emailForTest.$touch"
                                       placeholder="email@example.com">
                            </div>
                            <div v-if="$v.emailForTest.$dirty && $v.emailForTest.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            <div v-if="$v.emailForTest.$dirty && $v.emailForTest.email === false" class="alert alert-danger" role="alert">Enter correct email address.</div>
                        </div>
                    </div>

                    <div class="panel panel-default preview" v-if="preview">
                        <div class="panel-heading"><b>Subject:</b> {{ preview.subject }}</div>
                        <div class="panel-heading"><b>To:</b> <span v-for="to in preview.to">{{ to.address }}</span></div>
                        <div class="panel-body" v-html="preview.body"></div>
                    </div>
                </div>
                <div class="panel-footer" style="text-align: center">
                    <button type="button" class="btn btn-default" v-on:click="close">Close</button>
                    <button type="button" class="btn btn-primary" v-on:click="send" v-bind:disabled="dataProcess.running">Send</button>
                </div>
            </div>

        </modal>
    </div>

</template>

<script type="text/babel">
    import Modal from 'src/components/ui/Modal.vue'
    import DataProcess from 'src/components/ui/DataProcess.vue'
    import DataProcessMixin from 'src/mixins/vue-data-process'

    import apiCompanySettings from 'src/api/company-settings'
    import {required, email} from 'vuelidate/lib/validators'

    export default {
        mixins: [DataProcessMixin],
        validations: {
            emailForTest: {required, email}
        },
        data() {
            return {
                emailForTest: null,
                preview: null,
                dataProcess: {
                    running: false
                },
                modalClass: 'col-md-5 col-sm-7 col-xs-9',
                modalStyle: {
                    float: 'none',
                    padding: '0'
                },
                maskStyle: {
                    position: 'fixed'
                }
            }
        },
        components: {
            Modal,
            DataProcess
        },
        props: {
            show: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            send() {
                this.run({text: 'Sending email..', type: 'send-preview'})
                return apiCompanySettings.testEmail({email: this.emailForTest})
                    .then(response => {
                        this.$emit('data-process-update', {
                            running: false,
                            success: response.data.message
                        })
                        this.$emit('item-saved')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            close() {
                this.$emit('close')
            }
        },
        created() {
            /*
            apiCompanySettings.testEmail({
                item: this.item,
                params: {preview: true}
            }).then(response => {
                this.$emit('data-process-update', {running: false})
                this.preview = response.data
            }).catch(response => {
                this.$emit('data-failed', response)
            })
            */
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .preview .panel-heading {
        padding-top: 3px !important;
        padding-bottom: 3px !important;
    }
</style>