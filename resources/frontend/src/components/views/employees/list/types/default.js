let dimensions = [
    {
        name: 'Employee ID',
        id: 'id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },
    {
        name: 'First Name',
        id: 'first_name',
        checked: true,
        order_by: 'first_name',
        query: {
            fields: ['first_name'],
            group_by: ['first_name']
        }
    },
    {
        name: 'Last Name',
        id: 'last_name',
        checked: true,
        order_by: 'last_name',
        query: {
            fields: ['last_name'],
            group_by: ['last_name']
        }
    },
    {
        name: 'Email',
        id: 'email',
        checked: true,
        order_by: 'email',
        query: {
            fields: ['email'],
            group_by: ['email']
        }
    }
]

let searches = [
    {
        name: 'First Name',
        id: 'first_name',
        value: null,
        checked: false,
        query: {
            where: 'first_name'
        }
    },
    {
        name: 'Email',
        id: 'email',
        value: null,
        checked: false,
        query: {
            where: 'email'
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
