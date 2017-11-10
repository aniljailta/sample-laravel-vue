export default state => {
    let current = state.current
    /*
    let self = this
    return function (args) {
    }
    */
    return (current.statusId === 'draft' || current.statusId === 'review_needed')
}
