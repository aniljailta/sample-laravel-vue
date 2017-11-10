import {
    orderCustomBuildOptions,
    orderBuildingDimension
} from './order-building'

const options = state => state.options.list
const optionCategories = state => state.options.categories

const buildingModelID = state => {
    let buildingModel = orderBuildingDimension(state)
    if (!buildingModel || !buildingModel.id) return null
    return _.toInteger(buildingModel.id)
}

const currentAvailableOptions = state => {
    let modelID = buildingModelID(state)
    let allOptions = options(state)

    if (modelID == null) return []
    var availableOptions = _.filter(allOptions, function (item) {
        return _.includes(_.map(item.allowableModels, 'id'), modelID)
    }, this)

    return _.groupBy(availableOptions, option => option.category.name)
}

const currentAvailableCategories = state => {
    let modelID = buildingModelID(state)
    let categories = optionCategories(state)
    let availableOptions = currentAvailableOptions(state)

    if (modelID == null) return []

    var availableCategories = {}
    _.each(availableOptions, function (item, key) {
        let optionCategory = _.find(categories, {name: key})
        if (optionCategory) {
            // Count number of options per category
            // ( empty object with assigning prop (!important for vue) )
            availableCategories[key] = Object.assign({}, optionCategory, {count: item.length})
        }
    }, this)

    return availableCategories
}

const currentRequiredCategories = state => {
    let buildingOptions = orderCustomBuildOptions(state)
    let modelID = buildingModelID(state)
    let categories = optionCategories(state)

    if (modelID == null) return []

    let cats = _.filter(categories, (category) => {
        // search only required categories
        if (category.isRequired === true) {
            // if option already selected - remove from required categories list
            return (_.findIndex(buildingOptions, (buildingOption) => buildingOption.option.categoryId === category.id) === -1)
        }
        return false
    })
    return cats || []
}

const currentLockCategories = state => {
    let categories = optionCategories(state)
    let cats = _.filter(categories, (category) => {
        return !_.isNull(category.qtyLimit)
    })
    return cats || []
}

const currentLockedCategories = state => {
    let lockCategories = currentLockCategories(state)
    let buildingOptions = orderCustomBuildOptions(state)

    return _.filter(lockCategories, (category) => {
        // count selected building option items per category
        let currentCount = _.filter(buildingOptions, (buildingOption) => buildingOption.option.categoryId === category.id).length
        return (category.qtyLimit <= currentCount)
    })
}

const totalCustomOptions = state => {
    let buildingOptions = orderCustomBuildOptions(state)
    if (buildingOptions.length === 0) {
        return 0
    }

    return _.reduce(buildingOptions, function (memo, option) {
        return memo + (option.unitPrice * option.quantity)
    }, 0, this)
}

export default {
    buildingModelID,
    currentAvailableOptions,
    currentAvailableCategories,
    currentRequiredCategories,
    currentLockCategories,
    currentLockedCategories,
    totalCustomOptions
}