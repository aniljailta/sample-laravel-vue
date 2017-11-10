import apiBuildingPackages from 'src/api/building-packages'
import apiBuildingPackageCategories from 'src/api/building-package-categories'
import apiOptions from 'src/api/options'
import apiBuildingModels from 'src/api/building-models'

const receiveActiveFlags = function() {
    return apiBuildingPackages.activeFlags({})
}

const receiveOptionCategories = function() {
    return apiOptions.categories({})
}

const receiveOptions = function() {
    return apiOptions.get({
        query: {
            per_page: 9999,
            where: {
                is_active: 'yes'
            },
            include: {
                category: true,
                files: true,
                allowable_models: {
                    where: {
                        is_active: 'yes'
                    },
                    fields: ['id']
                }
            }
        }
    })
}

const receiveBuildingModels = function() {
    return apiBuildingModels.get({
        query: {
            where: {
                isActive: 'yes'
            },
            order_by: ['style_id asc', 'width asc', 'length asc'],
            per_page: 99999
        }
    })
}

const receiveBuildingPackageCategories = function() {
    return apiBuildingPackageCategories.get({
        query: {
            where: {
                isActive: 'yes'
            },
            per_page: 99999
        }
    })
}

export default {
    methods: {
        receiveActiveFlags,
        receiveOptionCategories,
        receiveOptions,
        receiveBuildingModels,
        receiveBuildingPackageCategories
    }
}