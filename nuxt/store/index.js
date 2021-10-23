export const state = () => ({
    loginInfo: null,
})

export const mutations = {
    setLoginInfo(state, loginInfo) {
        state.loginInfo = loginInfo
    },
}

export const actions = {
    async setLoginInfo({ commit }, form) {
        const email = form.email
        const password = form.password
        await this.$axios.get(`/api/user/read?email=${email}&password=${password}`)
            .then((res) => {
                this.$cookies.set("token", res.data.token, {
                    maxAge: 60 * 60 * 24 * 30,
                });
                commit('setLoginInfo', res.data)
            })
    },
    setLoginInfoByToken({ commit, dispatch }) {
        this.$axios.get(`/api/user/read?token=${this.$cookies.get("token")}`)
            .then((res) => {
                if (res.data.errorMessage) {
                    // トークンが有効ではない
                    dispatch('logout')
                } else {
                    // トークンが有効
                    if (this.$cookies.get("token")) {
                        if ($nuxt.$route.name == 'login') {
                            $nuxt.$router.push("/");
                        }
                        commit('setLoginInfo', res.data)
                    }
                }
            })
            .catch(() => {
                dispatch('logout')
            })
    },
    logout({ commit }) {
        this.$cookies.remove("token");
        if ($nuxt.$route.name != 'login') {
            $nuxt.$router.push("/login");
        }
        commit('setLoginInfo', false)
    },
}