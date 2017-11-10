<template>
    <tr>
        <td>
            <img class="img-thumbnail" v-bind:src="fileOption.publicPath" width="200">
        </td>
       
        <td class="text-left">
            {{ fileOption.location ? fileOption.location.verifiedLat : '-' }}
        </td>
        <td class="text-right nowrap">
            {{ fileOption.location ? fileOption.location.verifiedLong : '-' }}
        </td>
    </tr>

</template>

<script type="text/babel">

    export default {
        components: {
        },
        data() {
            return {}
        },
        props: {
            fileOption: {
                default() { return {} }
            }
        },
        methods: {}
    }
</script>
