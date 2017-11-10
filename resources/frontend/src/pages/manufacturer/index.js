import administrator from './administrator'
import employee from './employee'

const userRoutes = (userRole) => {
    switch (userRole) {
        case 'administrator':
            return administrator
        case 'officeAdmin':
            return []
        case 'employee':
            return employee
        case 'plant':
            return []
        case 'delivery':
            return []
        case 'accounting':
            return []
        case 'dealer':
            return []
        case 'customer':
            return []
        default:
            return []
    }
}

export default userRoutes