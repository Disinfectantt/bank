import { createStore } from 'vuex'

const store = createStore({
  state () {
    return {
      isAuth: false,
      isAdmin: false
    }
  }
})

export default store;