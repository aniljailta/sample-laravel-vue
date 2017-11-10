const orderOptions = state => {
    let list = state.list || []
    return list.filter(option => option.category.group === 'order')
}

export default orderOptions