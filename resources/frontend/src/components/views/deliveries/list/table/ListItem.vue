<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">{{ item.id }}</template>

            <template v-if="dimension.id === 'status_id'">
                <delivery-status-label :status="item.status"></delivery-status-label>
            </template>

            <template v-if="dimension.id === 'building_id'">
                <span v-if="item.building">
                    <router-link :to="{ name: 'building.show', params: { id: item.building.id }}" target="_blank">
                        {{ item.building.serialNumber }}
                    </router-link>
                </span>
            </template>

            <template v-if="dimension.id === 'start_location_id'">
                <span v-if="item.startLocation">
                   #{{item.startLocation.id}} - {{ item.startLocation.name }}
                </span>
            </template>

            <template v-if="dimension.id === 'end_location_id'">
                <span v-if="item.endLocation">
                   #{{item.endLocation.id}} - {{ item.endLocation.name }}
                </span>
            </template>

            <template v-if="dimension.id === 'driver_id'">
                <span v-if="item.driver">
                    {{ item.driver.fullName }}
                </span>
            </template>

            <template v-if="dimension.id === 'truck_id'">
                <span v-if="item.truck">
                    {{ item.truck.name }}
                </span>
            </template>

            <template v-if="dimension.id === 'trailer_id'">
                <span v-if="item.trailer">
                    {{ item.trailer.name }}
                </span>
            </template>

            <template v-if="dimension.id === 'priority_id'">
                 {{item.priority.name}}
            </template>

            <template v-if="dimension.id === 'category_id'">
                 {{item.category.name}}
            </template>

            <template v-if="dimension.id === 'distance'">
                 {{item.distance}}
            </template>

            <template v-if="dimension.id === 'confirmed_distance'">
                 {{item.confirmedDistance}}
            </template>

            <template v-if="dimension.id === 'average_drive_speed'">
                 {{item.averageDriveSpeed}}
            </template>

            <template v-if="dimension.id === 'cost'">
                 {{item.cost}}
            </template>

            <template v-if="dimension.id === 'setup_duration'">
                 {{item.setupDuration}}
            </template>

            <template v-if="dimension.id === 'drive_duration'">
                 {{item.driveDuration}}
            </template>

            <template v-if="dimension.id === 'notes'">
                 {{item.notes}}
            </template>

            <template v-if="dimension.id === 'scheduled_date'">
                <span class="label label-default" v-if="item.scheduledDate">
                    {{ filters.moment(item.scheduledDate, 'YYYY-MM-DD', 'MM/DD/YYYY') }}
                </span>
            </template>

            <template v-if="dimension.id === 'completed_date'">
                <span class="label label-default" v-if="item.completedDate">
                    {{ filters.moment(item.completedDate, 'YYYY-MM-DD', 'MM/DD/YYYY') }}
                </span>
            </template>

            <template v-if="dimension.id === 'promised_by_date'">
                <span class="label label-default" v-if="item.promisedByDate">
                    {{ filters.moment(item.promisedByDate, 'YYYY-MM-DD', 'MM/DD/YYYY') }}
                </span>
            </template>

            <template v-if="dimension.id === 'start_time'">
                <span class="label label-default" v-if="item.startTime">
                    {{ filters.moment(item.startTime, 'YYYY-MM-DD', 'MM/DD/YYYY') }}
                </span>
            </template>

            <template v-if="dimension.id === 'end_time'">
                <span class="label label-default" v-if="item.endTime">
                    {{ filters.moment(item.endTime, 'YYYY-MM-DD', 'MM/DD/YYYY') }}
                </span>
            </template>

            <template v-if="dimension.id === 'date_created'">
                <span class="label label-default" v-if="item.createdAt">
                    {{ filters.moment(item.createdAt, 'YYYY-MM-DD', 'MM/DD/YYYY') }}
                </span>
            </template>
        </td>

        <td class="text-center nowrap" v-if="dimensions.id">
            <a class="pointer btn btn-primary btn-xs" v-on:click="openUpdateModal"><i class="fa fa-pencil"></i></a>
            <a class="pointer btn btn-danger btn-xs" v-on:click="openDeleteModal"><i class="fa fa-trash-o"></i></a>
        </td>
    </tr>

</template>

<script type="text/babel">
    import baseListItemCrud from 'src/components/views/_base/ListItems/list/ListItemCrud.vue'
    import DeliveryStatusLabel from 'src/components/views/partials/DeliveryStatusLabel.vue'

    export default {
        extends: baseListItemCrud,
        data() {
            return {}
        },
        components: {
            DeliveryStatusLabel
        },
        created() {}
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>