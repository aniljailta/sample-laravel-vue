<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">{{ item.id }}</template>
            <template v-if="dimension.id === 'name'">{{ item.name }}</template>
            <template v-if="dimension.id === 'category'">{{ item.category }}</template>
            <template v-if="dimension.id === 'publish_date'">
                <span v-if="item.publishDate && item.publishDate != '0000-00-00 00:00:00'" class="label label-default">
                    {{ filters.moment(item.publishDate, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') }}
                </span>
            </template>
            <template v-if="dimension.id === 'published_at'">
                <span v-if="item.publishedAt" class="label label-default">
                    {{ filters.moment(item.publishedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') }}
                </span>
            </template>
            <template v-if="dimension.id === 'created_at'">
                <span v-if="item.createdAt" class="label label-default">
                    {{ filters.moment(item.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') }}
                </span>
            </template>
            <template v-if="dimension.id === 'updated_at'">
                <span v-if="item.updatedAt" class="label label-default">
                    {{ filters.moment(item.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') }}
                </span>
            </template>
            <template v-if="dimension.id === 'archived_at'">
                <span v-if="item.archivedAt" class="label label-default">
                    {{ filters.moment(item.archivedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') }}
                </span>
            </template>
            <template v-if="dimension.id === 'status'">
                <price-group-status-label :status="item.status" v-if="item.status"></price-group-status-label>
            </template>
            <template v-if="dimension.id === 'notes'">{{ item.notes }}</template>
        </td>

        <td class="text-center nowrap" v-if="dimensions.id">
            <router-link :to="{ name: 'price-group.show', params: { id: item.id }}" class="pointer btn btn-default btn-xs">
                <i class="fa fa-eye"></i>
            </router-link>
            <a class="pointer btn btn-primary btn-xs" v-on:click="openUpdateModal"><i class="fa fa-pencil"></i></a>
            <a class="pointer btn btn-danger btn-xs" v-on:click="openDeleteModal"><i class="fa fa-trash-o"></i></a>
            <a class="pointer btn btn-info btn-xs" title="Price List"
               v-on:click="openPriceListModal"><i class="fa fa-list-ul"></i></a>
            <a class="pointer btn btn-primary btn-xs" title="Publish" v-if="item.status != 'published'"
               v-on:click="openPublishModal"><i class="fa fa-paper-plane-o"></i></a>
        </td>
    </tr>

</template>

<script type="text/babel">
    import baseListItemCrud from 'src/components/views/_base/ListItems/list/ListItemCrud.vue'
    import PriceGroupStatusLabel from 'src/components/views/partials/PriceGroupStatusLabel.vue'

    export default {
        extends: baseListItemCrud,
        data() {
            return {}
        },
        components: {
            PriceGroupStatusLabel
        },
        created() {
        },
        methods: {
            openPublishModal() {
                this.$parent.$parent.$emit('change-entry', {
                    item: _.cloneDeep(this.item),
                    mode: 'publish'
                })
            },
            openPriceListModal() {
                this.$parent.$parent.$emit('change-entry', {
                    item: _.cloneDeep(this.item),
                    mode: 'price_list'
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>