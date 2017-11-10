import administrator from './administrator'

const userRoutes = (userRole) => {
    switch (userRole) {
        case 'administrator':
            return administrator
        case 'accounting':
            return []
        case 'support':
            return []
        default:
            return []
    }
}

export default userRoutes