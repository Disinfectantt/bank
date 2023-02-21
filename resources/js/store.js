import { createStore } from 'vuex'

const store = createStore({
  state () {
    return {
      isAuth: false,
      isAdmin: false,
      email: ''
    }
  }
})

export default store;