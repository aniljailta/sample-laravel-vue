import getOrderIsEditable from './order-is-editable'

const attachments = state => {
    return state.attachments
}

const getRtoSignaturePendingStatus = state => {
    let sAttachments = attachments(state)

    return _.findIndex(sAttachments, {'categoryId': 'complete_order_documents', 'rtoSignaturePending': true}) !== -1
}

const countRequiredAttachments = state => {
    let size = _.size(attachmentsMap(state))
    return size
}

const countUploadedAttachments = state => {
    let size = _.size(_.filter(attachmentsMap(state), placeholder => placeholder.files.length > 0))
    return size
}

const attachmentsPerCategory = state => {
    let sAttachments = attachments(state)
    if (_.size(sAttachments) === 0) return {}
    return _.keyBy(sAttachments, 'categoryId')
}

// search each 'required' category and return object with list of state of existance attachments
const attachedCategories = state => {
    let sAttachments = attachments(state)
    // let sOrder = state.order
    let requiredCategories = {
        signedOrderDocuments: _.findIndex(sAttachments, ['categoryId', 'signed_order_documents']) !== -1,
        signedBuildingConfiguration: _.findIndex(sAttachments, ['categoryId', 'signed_building_configuration']) !== -1,
        signedNeighborRelease: _.findIndex(sAttachments, ['categoryId', 'signed_neighbor_release']) !== -1,
        signedDepositReceipt: _.findIndex(sAttachments, ['categoryId', 'signed_deposit_receipt']) !== -1,
        driverLicense: _.findIndex(sAttachments, ['categoryId', 'driver_license']) !== -1,
        eSignedOrderDocuments: _.findIndex(sAttachments, ['categoryId', 'e_signed_order_documents']) !== -1
    }

    return requiredCategories
}

const attachmentsMap = state => {
    let files = attachments(state)
    let initials = requiredAttachments(state)
    let map = []

    // attach files to initials
    _.each(initials, initial => {
        let files = _.filter(files, {categoryId: initial.categoryId})
        if (!_.isEmpty(files)) {
            initial.files = files
        }

        initial.key = ''.concat(initial.categoryId, Date.now(), _.random(1024))
        // attach initial to map
        map.push(initial)
    })

    // attach files to map
    _.each(files, file => {
        let mapped = _.find(map, {categoryId: file.categoryId})
        if (mapped) {
            mapped.files.push(file)
            mapped.id = file.id
            mapped.key = ''.concat(file.id, Date.now())
        } else {
            // don't attach unmapped categories
            /*
            map.push({
                categoryId: file.categoryId,
                category: file.category,
                files: [file],
                id: file.id,
                key: ''.concat(file.id, Date.now())
            })
            */
        }
    })

    return map
}

// describe initial-required attachments
const requiredAttachments = state => {
    let order = state.order
    let building = state.building
    let fileCategories = state.files.categories
    let docs = []
    let orderIsEditable = getOrderIsEditable(state)

    if (order.signatureMethodId === 'manual') {
        docs.push({
            categoryId: 'signed_order_documents',
            generateCategoryId: 'unsigned_order_documents',
            type: 'pdf',
            canGenerate: orderIsEditable,
            canUpload: orderIsEditable,
            canDelete: orderIsEditable,
            generateLabel: 'Generate unsigned document',
            downloadLabel: 'Download unsigned document'
        })
    }

    if (building.saleType === 'custom-order') {
        docs.push({
            categoryId: 'signed_building_configuration',
            generateCategoryId: 'building_configuration',
            type: 'pdf',
            canUpload: orderIsEditable,
            canGenerate: orderIsEditable,
            canDelete: orderIsEditable,
            generateLabel: 'Generate Building Configuration',
            downloadLabel: 'Download Building Configuration'
        })
    }

    if (order.paymentType !== 'cash') {
        docs.push({
            categoryId: 'driver_license',
            type: 'photo',
            canUpload: orderIsEditable,
            canDelete: orderIsEditable
        })
    }

    /*
    if (order.deliveryRemarks.mustCrossNeighboringProperty === true) {
        docs.push({
            categoryId: 'signed_neighbor_release',
            generateCategoryId: 'neighbor_release',
            type: 'pdf',
            canUpload: true,
            canGenerate: true,
            required: true
        })
    }
    */

    _.each(docs, doc => {
        let fileCategory = _.find(fileCategories, {id: doc.categoryId})
        if (fileCategory) {
            doc.category = fileCategory
        }
        doc.files = []
    })
    return docs
}

// describe initial deposit receipt attachment
const depositReceiptAttachment = state => {
    let files = attachments(state)
    let fileCategories = state.files.categories
    let orderIsEditable = ([
        'draft',
        'review_needed',
        'signature_pending',
        'signed'
    ].indexOf(state.current.statusId) !== -1)

    let initial = {
        categoryId: 'signed_deposit_receipt',
        generateCategoryId: 'deposit_receipt',
        canUpload: orderIsEditable,
        canDelete: orderIsEditable,
        files: []
    }

    initial.key = ''.concat(initial.categoryId, Date.now(), _.random(1024))
    let existedFiles = _.filter(files, {categoryId: initial.categoryId})
    if (!_.isEmpty(existedFiles)) {
        initial.files = existedFiles
        initial.key = ''.concat(_.first(existedFiles).id, Date.now())
    }

    let categoryData = _.find(fileCategories, {id: initial.categoryId})
    if (categoryData) {
        initial.category = categoryData
    }

    return initial
}

// describe initial neightbor release form
const neighborReleaseAttachment = state => {
    let files = attachments(state)
    let fileCategories = state.files.categories
    let orderIsEditable = (state.current.statusId !== 'sale_generated' || state.current.statusId !== 'cancelled')

    let initial = {
        categoryId: 'signed_neighbor_release',
        generateCategoryId: 'neighbor_release',
        canUpload: orderIsEditable,
        canDelete: orderIsEditable,
        files: []
    }

    initial.key = ''.concat(initial.categoryId, Date.now(), _.random(1024))
    let existedFiles = _.filter(files, {categoryId: initial.categoryId})
    if (!_.isEmpty(existedFiles)) {
        initial.files = existedFiles
        initial.key = ''.concat(_.first(existedFiles).id, Date.now())
    }

    let categoryData = _.find(fileCategories, {id: initial.categoryId})
    if (categoryData) {
        initial.category = categoryData
    }

    return initial
}

// describe e-esigned order attachment
const eSignedOrderDocumentsAttachment = state => {
    let files = attachments(state)
    let fileCategories = state.files.categories
    let allowToEsignEmbed = !(['draft', 'review_needed', 'signature_pending'].indexOf(state.current.statusId) === -1)
    let allowToEsignEmail = !(['draft', 'review_needed'].indexOf(state.current.statusId) === -1)

    let initial = {
        categoryId: 'e_signed_order_documents',
        files: [],
        allowToEsignEmbed,
        allowToEsignEmail
    }

    initial.key = ''.concat(initial.categoryId, Date.now(), _.random(1024))
    let existedFiles = _.filter(files, {categoryId: initial.categoryId})
    if (!_.isEmpty(existedFiles)) {
        initial.files = existedFiles
        initial.key = ''.concat(_.first(existedFiles).id, Date.now())
        initial.allowToEsignEmbed = false
        initial.allowToEsignEmail = false
    }

    let categoryData = _.find(fileCategories, {id: initial.categoryId})
    if (categoryData) {
        initial.category = categoryData
    }

    return initial
}

export default {
    countRequiredAttachments,
    countUploadedAttachments,
    attachedCategories,
    attachments,
    attachmentsPerCategory,
    attachmentsMap,
    requiredAttachments,
    depositReceiptAttachment,
    neighborReleaseAttachment,
    eSignedOrderDocumentsAttachment,
    getRtoSignaturePendingStatus
}