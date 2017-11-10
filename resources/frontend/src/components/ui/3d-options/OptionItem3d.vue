<template>
    <div :data-id="buildingItem['3dModel'].dataId"
         class="col-md-3 col-xs-4 item"
         draggable="true">
            <div :style="{ 'background-image': 'url(' + buildingItem['3dModel'].icon + ')' }"></div>
            <span>{{buildingItem.name}}</span><br/>
            <span>$ {{buildingItem.unitPrice }}</span>
    </div>

</template>

<script type="text/babel">
    export default {
        data() {
            return {}
        },
        props: {
            buildingItem: {
                default() {
                    return {
                        '3dModel': null
                    }
                }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .item {
        display: inline-block;
        vertical-align: bottom;
        font-size: 12px;
        margin-bottom: 10px;
        text-align: center;

        > div {
            width: 80px;
            height: 80px;
            background-size: cover;
            margin: 0 auto;
        }
    }
</style>

<style type="text/css">

</style>