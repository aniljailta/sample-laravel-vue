/**
 * Communicates with API server about building history
 */

import Vue from 'vue'
import { csrfToken } from './_config'

const QrCodeResource = Vue.resource('/api/qr-codes',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

const QrCodeStatusResource = Vue.resource('/api/qr-codes/status',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

const QrCodeImageUploadResource = Vue.resource('/api/qr-codes/files',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

const QrCodeImageUploadLocationResource = Vue.resource('/api/qr-codes/location-files',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

const QrCodelatLongUpdate = Vue.resource('/api/qr-codes/update-lat-long',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

export default {
  get ({ buildingId, query }) {
    return QrCodeResource.get({ buildingId, ...query })
  },
  getByIdentifier({ query }) {
    return Vue.http.get('/api/qr-codes/get-by-identifier', {
            params: query
        })
  },
  getByIdentifierLocation({ query }) {
    return Vue.http.get('/api/qr-codes/get-by-identifier-location', {
            params: query
        })
  },
  uploadFiles({ identifier, data }) {
      return QrCodeImageUploadResource.save({
        identifier: identifier
      }, data)
  },
  uploadLocationFiles({ data }) {
      return QrCodeImageUploadLocationResource.save({ }, data)
  },
  confirmlatlong({ data }) {
      return QrCodelatLongUpdate.save({ }, data)
  },
  save ({ item, data }) {
      return QrCodeResource.save({
        building_id: item.buildingId
      }, data)
  },
  savestatus ({ item, data }) {
      return QrCodeStatusResource.save({
        building_id: item.buildingId
      }, data)
  },
  delete({ item }) {
    return QrCodeResource.delete({
      building_id: item.buildingId,
      id: item.id
    })
  }
}
