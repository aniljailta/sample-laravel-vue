let dimensions = [
    {
        name: 'Price Group ID',
        id: 'id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },
    {
        name: 'Name',
        id: 'name',
        checked: true,
        order_by: 'name',
        query: {
            fields: ['name'],
            group_by: ['name']
        }
    },
    {
        name: 'Category',
        id: 'category',
        checked: true,
        order_by: 'category',
        query: {
            fields: ['category'],
            group_by: ['category']
        }
    },
    {
        name: 'Date Scheduled',
        id: 'publish_date',
        checked: true,
        order_by: 'publish_date',
        query: {
            fields: ['publish_date'],
            group_by: ['publish_date']
        }
    },
    {
        name: 'Date Published',
        id: 'published_at',
        checked: true,
        order_by: 'published_at',
        query: {
            fields: ['published_at'],
            group_by: ['published_at']
        }
    },
    {
        name: 'Date Created',
        id: 'created_at',
        checked: true,
        order_by: 'created_at',
        query: {
            fields: ['created_at'],
            group_by: ['created_at']
        }
    },
    {
        name: 'Date Updated',
        id: 'updated_at',
        checked: true,
        order_by: 'updated_at',
        query: {
            fields: ['updated_at'],
            group_by: ['updated_at']
        }
    },
    {
        name: 'Date Archived',
        id: 'archived_at',
        checked: true,
        order_by: 'archived_at',
        query: {
            fields: ['archived_at'],
            group_by: ['archived_at']
        }
    },
    {
        name: 'Status',
        id: 'status',
        checked: true,
        order_by: 'status',
        query: {
            fields: ['status'],
            group_by: ['status']
        }
    },
    {
        name: 'Note',
        id: 'notes',
        checked: true,
        order_by: 'notes',
        query: {
            fields: ['notes'],
            group_by: ['notes']
        }
    }
]

let searches = [
    {
        name: 'Name',
        id: 'name',
        value: null,
        checked: false,
        query: {
            where: 'name'
        }
    },
    {
        name: 'Category',
        id: 'category',
        value: null,
        checked: false,
        query: {
            where: 'category'
        }
    },
    {
        name: 'Status',
        id: 'status',
        value: null,
        checked: false,
        query: {
            where: 'status'
        }
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
