<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady">
            <div class="form-group">
                <!-- common -->
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-4">
                        <label for="firstName" class="control-label"> First Name</label>
                        <div class="form-group">
                            <input id="firstName" type="text" class="form-control" v-model="curItem.firstName" placeholder="First Name">
                        </div>
                        
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label for="lastName" class="control-label"> Last Name</label>
                        <div class="form-group">
                            <input id="lastName" type="text" class="form-control" v-model="curItem.lastName" placeholder="Last Name">
                        </div>
                        
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label for="email_id" class="control-label">Email</label>
                        <div class="form-group">
                            <input id="email_id" type="email" class="form-control" v-model="curItem.email" placeholder="Email">
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiEmployees from 'src/api/employees'
    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        name: 'employee-form',
        extends: BaseDataItem,
        data() {
            return {
                // data dependency
                curItem: {}
            }
        },
        components: {},
        computed: {
           id() {
                if (!_.isUndefined(this.item.id)) {
                    return this.item.id
                }
                return null
            }
        },
        methods: {
            save({ item, data }) {
                return apiEmployees.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let item = {
                    id: this.curItem.id,
                    firstName: this.curItem.firstName,
                    lastName: this.curItem.lastName,
                    email: this.curItem.email
                }
                if (this.curItem.id) item.id = this.curItem.id

                let form = objectToFormData(convertKeys.toSnake(item))

                this.run({text: 'Saving..', type: 'form'})
                return this.save({item: item, data: form})
                    .then(data => {
                        self.$emit('data-process-update', {
                            running: false,
                            success: data.msg
                        })
                        self.$emit('item-saved')
                    })
                    .catch(response => {
                        self.$emit('data-failed', response)
                    })
            },
            initData() {
                if (this.id) {
                    apiEmployees.get({
                        id: this.id
                    })
                        .then(response => {
                            return this.initDependencies().then(() => response)
                        })
                        .then(response => {
                            let item = response.data
                            this.curItem = _.cloneDeep(item)
                        })
                        .then(() => {
                            this.$emit('data-ready')
                        })
                        .catch(response => {
                            this.$emit('data-failed', response)
                        })
                } else {
                    this.initDependencies().then(() => {
                        this.$emit('data-ready')
                    })
                }
            },
            initDependencies() {
                return new Promise((resolve) => { resolve() })
            }
        }
    }
</script>