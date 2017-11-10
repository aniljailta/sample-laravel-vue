<template>

    <div id="sn">
        <span class="label" :class="labelClass" v-if="finalSerialNumber">
            <router-link :to="{ name: 'building.show', params: { id: id }}" v-if="finalSerialNumber.id !== 'xxxxxxxxx' ">
                {{ finalSerialNumber.shortCode }}-{{ finalSerialNumber.size }}-{{ finalSerialNumber.id }}
            </router-link>
            <span v-else>
                {{ finalSerialNumber.shortCode }}-{{ finalSerialNumber.size }}-{{ finalSerialNumber.id }}
            </span>
        </span>
        <span v-else>
            <span class="label label-default">None</span>
        </span>
    </div>

</template>

<script type="text/babel">
    export default {
        data() {
            return {
            }
        },
        props: {
            id: {
                default: null
            },
            serialNumber: {
                default: null
            },
            buildingModel: {
                default() {
                    return {}
                }
            }
        },
        computed: {
            currentParts() {
                if (this.serialNumber) {
                    let parts = _.zipObject(['shortCode', 'size', 'id'], this.serialNumber.split('-'))
                    return parts
                }
                return null
            },
            newParts() {
                if (this.buildingModel) {
                    let parts = {}
                    parts.shortCode = this.buildingModel.style.shortCode
                    parts.size = '' + _.padStart(this.buildingModel.width, 2, 0) + _.padStart(this.buildingModel.length, 2, 0) + _.padStart(this.buildingModel.wallHeight, 2, 0)

                    if (this.currentParts) {
                        parts.id = this.currentParts.id
                    } else {
                        parts.id = _.padStart('', 9, 'x')
                    }

                    return parts
                }
                return null
            },
            finalSerialNumber() {
                if (!_.isEqual(this.currentParts, this.newParts)) {
                    return this.newParts
                }
                return this.currentParts
            },
            labelClass() {
                if (!_.isEqual(this.currentParts, this.newParts)) {
                    return 'label-warning'
                }
                return 'label-success'
            }
        },
        methods: {
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    #sn {
        span {
            font-size: 100%;
        }

        a {
            font-size: 100%;
            color: white;
        }
    }
</style>