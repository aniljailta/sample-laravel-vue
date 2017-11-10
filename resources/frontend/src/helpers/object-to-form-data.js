'use strict'

let {FormData} = window
let {toString} = Object.prototype

import RecursiveIterator from 'recursive-iterator'

/**
 * Returns type of anything
 * @param {Object} any
 * @returns {String}
 */
function getType(any) {
    return toString.call(any).slice(8, -1)
}
/**
 * Converts path to FormData name
 * @param {Array} path
 * @returns {String}
 */
function toName(path) {
    let array = path.map((part) => `[${part}]`)
    array[0] = path[0]
    return array.join('')
}

/**
 * Converts object to FormData
 * @param {Object} object
 * @returns {FormData}
 */
export default function (object) {
    if (!_.isObject(object)) {
        throw new TypeError('Argument must be object')
    }

    let form = new FormData()
    let iterator = new RecursiveIterator(object, 0, true)

    let appendToForm = function (path, node, filename) {
        let name = toName(path)
        if (_.isUndefined(filename)) {
            form.append(name, node)
        } else {
            form.append(name, node, filename)
        }
    }

    iterator.onStepInto = function ({parent, node}) {
        let type = getType(node)
        switch (type) {
            case 'Array':
                return true // step into
            case 'Object':
                return true // step into
            case 'FileList':
                return true // step into
            default:
                return false // prevent step into
        }
    }

    for (let {node, path} of iterator) {
        var type = getType(node)
        switch (type) {
            case 'Array':
                if (node.length === 0) {
                    appendToForm(path, null)
                }
                break
            case 'Object':
                break
            case 'FileList':
                break
            case 'File':
                appendToForm(path, node)
                break
            case 'Blob':
                appendToForm(path, node, node.name)
                break
            default:
                appendToForm(path, node)
                break
        }
    }

    return form
}
