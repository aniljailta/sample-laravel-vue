<template>
    <div>
        <div class="col-md-12" v-for="buildingOption in buildingOptions" v-if="!buildingOption.icon">
            <render-non2d v-bind:building-options="buildingOption"></render-non2d>
        </div>
        <div :data-id="buildingOption.dataId" class="item" draggable="true" v-for="buildingOption in buildingOptions" v-if="buildingOption.icon">
            <div :style="{ 'background-image': 'url(' + buildingOption.icon + ')' }" ></div>
            <span>{{buildingOption.name}}</span></br>
            <span>${{buildingOption.unitPrice}}</span>
        </div>
    </div>
</template>

<script type="text/babel">
    import RenderNon2d from 'src/components/ui/3d-options/RenderNon2d.vue'

    export default {
        components: {
            RenderNon2d
        },
        data() {
            return {}
        },
        props: {
            menuitem: {
                default() { return [] }
            },
            buildingOptions: {
                default() { return [] }
            }
        },
        created() {
            console.log(this.buildingOptions)
        }

    }
</script>
