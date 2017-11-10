<template>

    <section class="panel-featured">
        <header class="panel-heading panel-with-actions" v-if="curItem.id">
            <h2 class="panel-title">
                <span>Employee # {{ curItem.id }}</span>
            </h2>
            <div class="clearfix"></div>
        </header>

        <div class="panel-body">
            <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

            <template v-if="dataIsReady">
                <!-- BUILDING DETAILS -->
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <section class="panel sub-panel">
                            <header class="panel-heading">
                                <h4 class="panel-title">Employee Details</h4>
                            </header>
                            <div class="panel-body">
                                
                                <dl class="dl-horizontal mb-none">
                                    <dt>id #</dt>
                                    <dd><span class="label label-info">{{ curItem.id || '-' }}</span></dd>

                                    <dt>First Name</dt>
                                    <dd>
                                        <span v-if="curItem.firstName">{{ curItem.firstName }}</span>
                                        <span v-else>-</span>
                                    </dd>

                                    <dt>Last Name</dt>
                                    <dd>
                                        <span v-if="curItem.lastName">{{ curItem.lastName }}</span>
                                        <span v-else>-</span>
                                    </dd>

                                    <dt>Email</dt>
                                    <dd>{{ curItem.email || '-' }}</dd>
                                </dl>
                            </div>
                            
                        </section>

                    </div>
                    
                </div>
            </template>
        </div>

        <modal-update v-if="modal !== null && modal.mode === 'edit' " :show="true"
                      v-on:close="closeModalUpdate"
                      v-on:saved="refresh"
                      :item="modal !== null && modal.mode === 'edit' ? modal.item : null"></modal-update>
    </section>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiEmployees from 'src/api/employees'

    export default {
        name: 'building-show',
        extends: BaseDataItem,
        data() {
            return {
                id: null,
                modal: null,
                curItem: {}
            }
        },
        components: {},
        computed: {},
        methods: {
            openUpdateModal() {
                this.modal = {
                    mode: 'edit',
                    item: _.cloneDeep(this.curItem)
                }
            },
            refresh(part) {
                if (!part) {
                    this.run({type: 'refresh'})
                    this.initData(this.id)
                    return
                }
            },
            initData() {
                this.run({text: 'Loading..'})
                this.curItem = {}
                let id = this.$route.params.id
                return apiEmployees.get({
                    id: id
                }).then((response) => {
                    let item = response.data
                    this.id = id
                    this.curItem = _.cloneDeep(item)
                }).then(() => {
                    this.$emit('data-ready')
                }).catch((response) => {
                    this.$emit('data-failed', response)
                })
            }
        },
        watch: {
            '$route': function() {
                this.run({type: 'data'})
                this.initData()
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .row {
        margin-bottom: 0.5em;
    }
</style>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .action-list {
        padding-top: 3px;
    }
</style>