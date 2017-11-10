/**
 * Communicates with API server about building packages
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const orderReferenceResource = Vue.resource('/api/order-references{/id}',
    {},
    {
        learningAboutUs: {
            method: 'GET',
            url: '/api/order-references/learning-about-us'
        }
    },
    {
        headers: {
            'X-CSRF-TOKEN': csrfToken()
        }
    }
)

export default {
    get ({id, query}) {
        return orderReferenceResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return orderReferenceResource.save({}, data)
        } else {
            data.append('_method', 'put')
            return orderReferenceResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return orderReferenceResource.delete({id: item.id})
    },
    learningAboutUs ({ params }) {
        return orderReferenceResource.learningAboutUs(params)
    }
}
