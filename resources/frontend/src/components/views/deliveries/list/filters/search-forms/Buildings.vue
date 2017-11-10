<template>

    <group-item v-on:close="close" v-bind:label="item.name">
        <select class="form-control input-sm" initial="off" v-on:change="change($event.target.value)">
            <option v-bind:value="null" selected="selected"></option>
            <option v-for="data in datas"
                    v-bind:value="data.id"
                    v-bind:selected="parseInt(item.value) === data.id">
                #{{ data.id }} {{ data.serialNumber }}
            </option>
        </select>
    </group-item>

</template>

<script type="text/babel">
    import Select from 'src/components/views/_base/ListItems/search-forms/Select.vue'

    export default {
        extends: Select,
        data() {
            return {}
        }
    }
</script>