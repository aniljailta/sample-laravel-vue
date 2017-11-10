<template>

        <div class="Typeahead">
            <i class="fa fa-spinner fa-spin form-control-feedback" v-if="loading"></i>
            <template v-else>
                <i class="fa fa-search form-control-feedback" v-show="isEmpty"></i>
                <i class="fa fa-times form-control-feedback" v-show="isDirty" @click="reset"></i>
            </template>

            <input type="text"
                   class="form-control"
                   :class="{'invalid': $parent.$v.inventoryBuilding.$error}"
                   placeholder="Building Serial Number"
                   autocomplete="off"
                   v-model="query"
                   @keydown.down="down"
                   @keydown.up="up"
                   @keydown.enter="hit"
                   @keydown.esc="reset"
                   @input="update"/>

            <ul v-show="hasItems">
                <li v-for="(item, index_id) in items" :class="activeClass(index_id)" @mousedown="hit" @mousemove="setActive(index_id)">
                    <span class="name" v-text="item.serialNumber"></span>
                    <span class="screen-name">${{ item.totalPrice }}</span>
                </li>
            </ul>

            <div v-if="message" class="alert alert-danger" role="alert">{{ message }}</div>
            <div v-if="$parent.$v.inventoryBuilding.serial.$dirty && !$parent.$v.inventoryBuilding.serial.required" class="alert alert-danger" role="alert">
                You need to select a building.
            </div>
        </div>

</template>

<script type="text/babel">
    import {mapActions, mapGetters} from 'vuex'
    import vueTypeahead from 'vue-typeahead'

    export default {
        name: 'serial-typeahead',
        extends: vueTypeahead,
        data: function () {
            return {
                message: null,
                src: '/api/dealer-inventory-search',
                limit: 10,
                minChars: 1
            }
        },
        computed: {
            ...mapGetters({
                dealer: 'dealerOrderForm/orderDealerID',
                buildingCondition: 'dealerOrderForm/orderBuildingCondition'
            }),
            data() {
                return {
                    query: this.query,
                    dealer: this.dealer,
                    usedRtoOwner: this.buildingCondition
                }
            }
        },
        methods: {
            ...mapActions({
                updateOrderBuilding: 'dealerOrderForm/updateOrderBuilding'
            }),
            update () {
                this.message = null
                this.cancel()

                if (!this.query) {
                    return this.reset()
                }

                if (this.minChars && this.query.length < this.minChars) {
                    return
                }

                this.loading = true

                this.fetch()
                    .then((response) => {
                        if (response && this.query) {
                            let data = response.data
                            data = this.prepareResponseData ? this.prepareResponseData(data) : data
                            this.items = this.limit ? data.slice(0, this.limit) : data
                            this.current = -1
                            this.loading = false

                            if (this.selectFirst) {
                                this.down()
                            }
                        }
                    })
                    .catch((response) => {
                        this.message = response.body.message
                    })
            },
            onHit: function (item) {
                this.items = []
                this.loading = false

                this.query = item.serialNumber
                this.updateOrderBuilding({serial: item.serialNumber})
                // this.$parent.validate('fields', ['serial'])
            }
        }
    }
</script>

<style type="text/css">

</style>