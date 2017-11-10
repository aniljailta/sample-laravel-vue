export default state => {
    return state.list.filter(function (option) {
        return option.category.group !== 'order'
    })
}