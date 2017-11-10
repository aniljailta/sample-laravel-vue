import {
  REMOVE_ATTACHMENT
} from '../types'

export default function({ commit }, id) {
  commit(REMOVE_ATTACHMENT, id)
}
