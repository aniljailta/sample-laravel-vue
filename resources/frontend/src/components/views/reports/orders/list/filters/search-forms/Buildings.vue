<template>

    <li class="list-group-item">

        <label>{{ item.name }}</label>
        <div class="div-close">
            <button type="button" class="close" aria-label="Close" v-on:click="close">
                <span aria-hidden="true">remove &times;</span>
            </button>
        </div>

        <div class="filter-selector">
            <div class="input-group-fix">
                <select class="form-control input-sm"
                        initial="off"
                        v-on:change="change($event.target.value)">
                    <option v-bind:value="null" selected="selected"></option>
                    <option v-for="data in datas"
                            v-bind:value="data.id"
                            v-bind:selected="parseInt(item.value) === data.id">
                        #{{ data.id }} {{ data.serialNumber }}
                    </option>
                </select>
            </div>
            <div class="clearfix"></div>
        </div>

    </li>

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