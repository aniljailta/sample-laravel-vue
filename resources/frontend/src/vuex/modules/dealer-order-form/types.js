/**
 * Order
 */

const SET_STATE = 'DEALER_ORDER_FORM_SET_STATE'
const SYNC_START = 'DEALER_ORDER_FORM_SYNC_START'
const SYNC_SUCCESS = 'DEALER_ORDER_FORM_SYNC_SUCCESS'
const SYNC_FAILURE = 'DEALER_ORDER_FORM_SYNC_FAILURE'
const SYNC_UPDATE = 'DEALER_ORDER_FORM_SYNC_UPDATE'

const PDF_GEN_START = 'DEALER_ORDER_FORM_PDF_GEN_START'
const PDF_GEN_SUCCESS = 'DEALER_ORDER_FORM_PDF_GEN_SUCCESS'
const PDF_GEN_FAILURE = 'DEALER_ORDER_FORM_PDF_GEN_FAILURE'

const UPDATE_DEALER = 'DEALER_ORDER_FORM_UPDATE_DEALER'

const UPDATE_CUSTOMER = 'DEALER_ORDER_FORM_UPDATE_CUSTOMER'

const UPDATE_BUILDING = 'DEALER_ORDER_FORM_UPDATE_BUILDING'
const ADD_BUILDING_CUSTOM_OPTION = 'DEALER_ORDER_FORM_ADD_BUILDING_CUSTOM_OPTION'
const REMOVE_BUILDING_CUSTOM_OPTION = 'DEALER_ORDER_FORM_REMOVE_BUILDING_CUSTOM_OPTION'
const UPDATE_BUILDING_CUSTOM_OPTION = 'DEALER_ORDER_FORM_UPDATE_BUILDING_CUSTOM_OPTION'
const INCREASE_BUILDING_CUSTOM_OPTION = 'DEALER_ORDER_FORM_INCREASE_BUILDING_CUSTOM_OPTION'
const DECREASE_BUILDING_CUSTOM_OPTION = 'DEALER_ORDER_FORM_DECREASE_BUILDING_CUSTOM_OPTION'

const UPDATE_ORDER_OPTIONS = 'DEALER_ORDER_FORM_UPDATE_ORDER_OPTIONS'

const UPDATE_ORDER = 'DEALER_ORDER_FORM_UPDATE_ORDER'

const UPDATE_RENTER = 'DEALER_ORDER_FORM_UPDATE_RENTER'
const UPDATE_SUMMARY = 'DEALER_ORDER_FORM_UPDATE_SUMMARY'
const UPDATE_VALIDATION = 'DEALER_ORDER_FORM_UPDATE_VALIDATION'

const ADD_ATTACHMENT = 'DEALER_ORDER_FORM_ADD_ATTACHMENT'
const REMOVE_ATTACHMENT = 'DEALER_ORDER_FORM_REMOVE_ATTACHMENT'

export default {
    SET_STATE,
    SYNC_START,
    SYNC_SUCCESS,
    SYNC_FAILURE,
    SYNC_UPDATE,

    PDF_GEN_START,
    PDF_GEN_SUCCESS,
    PDF_GEN_FAILURE,

    UPDATE_DEALER,
    UPDATE_CUSTOMER,

    UPDATE_BUILDING,
    ADD_BUILDING_CUSTOM_OPTION,
    REMOVE_BUILDING_CUSTOM_OPTION,
    UPDATE_BUILDING_CUSTOM_OPTION,
    INCREASE_BUILDING_CUSTOM_OPTION,
    DECREASE_BUILDING_CUSTOM_OPTION,

    UPDATE_ORDER_OPTIONS,

    UPDATE_ORDER,
    UPDATE_RENTER,
    UPDATE_SUMMARY,
    UPDATE_VALIDATION,

    ADD_ATTACHMENT,
    REMOVE_ATTACHMENT
}