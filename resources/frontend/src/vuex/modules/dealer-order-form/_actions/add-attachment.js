import {
  ADD_ATTACHMENT
} from '../types'

import camelCaseObjectKeys from 'src/helpers/camel-case-converter'

export default function({ commit }, attachment) {
  commit(ADD_ATTACHMENT, camelCaseObjectKeys(attachment))
}
