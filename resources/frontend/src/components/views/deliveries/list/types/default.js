import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Delivery ID',
        id: 'id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },
    {
        name: 'Status',
        id: 'status_id',
        checked: true,
        order_by: 'status_id',
        query: {
            fields: ['status_id'],
            group_by: ['status_id']
        }
    },
    {
        name: 'Building ID',
        id: 'building_id',
        checked: true,
        order_by: 'building.serial_number',
        query: {
            fields: ['building_id'],
            include: {
                building: {
                    fields: ['id', 'serial_number']
                }
            },
            leftJoinRelation: ['building'],
            group_by: ['building.serial_number']
        }
    },
    {
        name: 'Start Location',
        id: 'start_location_id',
        checked: true,
        order_by: 'start_location.name',
        query: {
            fields: ['start_location_id'],
            include: {
                start_location: {
                    fields: ['id', 'name']
                }
            },
            leftJoinRelation: ['start_location'],
            group_by: ['start_location_id']
        }
    },
    {
        name: 'End Location',
        id: 'end_location_id',
        checked: true,
        order_by: 'end_location.name',
        query: {
            fields: ['end_location_id'],
            include: {
                end_location: {
                    fields: ['id', 'name']
                }
            },
            leftJoinRelation: ['end_location'],
            group_by: ['end_location_id']
        }
    },
    {
        name: 'Driver ID',
        id: 'driver_id',
        checked: true,
        order_by: 'driver_id',
        query: {
            fields: ['driver_id'],
            include: {
                driver: {
                    fields: ['id', 'first_name', 'last_name']
                }
            },
            leftJoinRelation: ['driver'],
            group_by: ['driver.id']
        }
    },
    {
        name: 'Truck ID',
        id: 'truck_id',
        checked: true,
        order_by: 'truck.name',
        query: {
            fields: ['truck_id'],
            include: {
                truck: {
                    fields: ['id', 'name']
                }
            },
            leftJoinRelation: ['truck'],
            group_by: ['truck.name']
        }
    },
    {
        name: 'Trailer ID',
        id: 'trailer_id',
        checked: true,
        order_by: 'trailer.name',
        query: {
            fields: ['trailer_id'],
            include: {
                trailer: {
                    fields: ['id', 'name']
                }
            },
            leftJoinRelation: ['trailer'],
            group_by: ['trailer.name']
        }
    },
    {
        name: 'Priority',
        id: 'priority_id',
        checked: true,
        order_by: 'priority_id',
        query: {
            fields: ['priority_id'],
            group_by: ['priority_id']
        }
    },
    {
        name: 'Category',
        id: 'category_id',
        checked: false,
        order_by: 'category_id',
        query: {
            fields: ['category_id'],
            group_by: ['category_id']
        }
    },
    {
        name: 'Distance (Mph)',
        id: 'distance',
        checked: false,
        order_by: 'distance',
        query: {
            fields: ['distance'],
            group_by: ['distance']
        }
    },
    {
        name: 'Confirm Distance',
        id: 'confirmed_distance',
        checked: false,
        order_by: 'confirmed_distance',
        query: {
            fields: ['confirmed_distance'],
            group_by: ['confirmed_distance']
        }
    },
    {
        name: 'Average Drive Speed (Mph)',
        id: 'average_drive_speed',
        checked: false,
        order_by: 'average_drive_speed',
        query: {
            fields: ['average_drive_speed'],
            group_by: ['average_drive_speed']
        }
    },

    {
        name: 'Cost',
        id: 'cost',
        checked: false,
        order_by: 'cost',
        query: {
            fields: ['cost'],
            group_by: ['cost']
        }
    },
    {
        name: 'Setup Duration (Minutes)',
        id: 'setup_duration',
        checked: false,
        order_by: 'setup_duration',
        query: {
            fields: ['setup_duration'],
            group_by: ['setup_duration']
        }
    },
    {
        name: 'Drive Duration (Hours)',
        id: 'drive_duration',
        checked: false,
        order_by: 'drive_duration',
        query: {
            fields: ['drive_duration'],
            group_by: ['drive_duration']
        }
    },
    {
        name: 'Notes',
        id: 'notes',
        checked: false,
        order_by: 'notes',
        query: {
            fields: ['notes'],
            group_by: ['notes']
        }
    },
    {
        name: 'Scheduled Date',
        id: 'scheduled_date',
        checked: true,
        order_by: 'scheduled_date',
        query: {
            fields: ['scheduled_date'],
            group_by: ['scheduled_date']
        }
    },
    {
        name: 'Completed Date',
        id: 'completed_date',
        checked: false,
        order_by: 'completed_date',
        query: {
            fields: ['completed_date'],
            group_by: ['completed_date']
        }
    },
    {
        name: 'Promised By Date',
        id: 'promised_by_date',
        checked: false,
        order_by: 'promised_by_date',
        query: {
            fields: ['promised_by_date'],
            group_by: ['promised_by_date']
        }
    },
    {
        name: 'Start Time',
        id: 'start_time',
        checked: false,
        order_by: 'start_time',
        query: {
            fields: ['start_time'],
            group_by: ['start_time']
        }
    },
    {
        name: 'End Time',
        id: 'end_time',
        checked: false,
        order_by: 'end_time',
        query: {
            fields: ['end_time'],
            group_by: ['end_time']
        }
    },
    {
        name: 'Date Created',
        id: 'date_created',
        checked: true,
        order_by: 'created_at',
        query: {
            fields: ['created_at'],
            group_by: ['created_at']
        }
    }
]

let searches = [
    {
        name: 'Status',
        id: 'status_id',
        value: 'pending,draft',
        checked: true
    },
    {
        name: 'Building',
        id: 'building_id',
        value: null,
        checked: false,
        query: {
            where: 'building_id'
        }
    },
    {
        name: 'Start Location',
        id: 'start_location_id',
        value: null,
        checked: false
    },
    {
        name: 'End Location',
        id: 'end_location_id',
        value: null,
        checked: false
    },
    {
        name: 'Scheduled Date',
        id: 'scheduled_date',
        parse(value) {
            let between = []
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD'))
            return {between}
        },
        checked: false,
        query: {
            where: 'scheduled_date'
        }
    },
    {
        name: 'Driver ID',
        id: 'driver_id',
        value: null,
        checked: false
    },
    {
        name: 'Truck ID',
        id: 'truck_id',
        value: null,
        checked: false
    },
    {
        name: 'Trailer ID',
        id: 'trailer_id',
        value: null,
        checked: false
    },
    {
        name: 'Priority',
        id: 'priority_id',
        value: null,
        checked: false
    }

]

let totals = [
    {
        name: 'Rows',
        id: 'totalRows',
        type: 'unit',
        checked: null,
        selectable: false,
        value: null
    }
]

export default {
    dimensions,
    searches,
    totals
}
