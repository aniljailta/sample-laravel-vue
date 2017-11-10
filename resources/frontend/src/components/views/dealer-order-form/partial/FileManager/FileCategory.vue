<template>

    <div class="col-xs-12" style="margin-top: 0.5em;">
        <div class="form-group">
            <label for="file-category">Category</label>
            <select id="file-category"
                    name="file_category"
                    class="form-control input-sm"
                    initial="off"
                    v-bind:disabled="uploaded"
                    v-bind:value="categoryId"
                    v-on:change.prevent="changeCategory($event.target.value)">
                <option v-for="(category, index) in fileCategories" v-bind:value="category.id">{{ category.title }}</option>
            </select>
        </div>
    </div>

</template>

<script type="text/babel">
    import {mapGetters} from 'vuex'

    export default {
        data() {
            return {
                uploaded: false,
                categoryId: 'other'
            }
        },
        computed: {
            ...mapGetters({
                fileCategories: 'dealerOrderForm/files/categories'
            })
        },
        methods: {
            changeCategory(categoryId) {
                this.categoryId = categoryId
            }
        }
    }
</script>

<style type="text/css">
    .form-group {
        margin-bottom: 15px!important;
    }
</style>