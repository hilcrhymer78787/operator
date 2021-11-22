export const state = () => ({
    loginInfo: null,
    questions:[],
})

export const mutations = {
    setLoginInfo(state, loginInfo) {
        state.loginInfo = loginInfo
    },
    setQuestions(state, questions) {
        state.questions = questions
    },
}

export const actions = {
    async setLoginInfoByToken({ commit, dispatch }) {
        this.$axios.get(`/api/user/bearer_authentication`)
            .then((res) => {
                console.log(res.data)
                var loginInfo = res.data
                if (res.data.errorMessage) {
                    // トークンが有効ではない
                    dispatch('logout')
                } else {
                    // トークンが有効
                    if (this.$cookies.get("token")) {
                        if (!$nuxt.$route.name) {
                            $nuxt.$router.push("/member");
                        }
                        if ($nuxt.$route.name == 'login') {
                            $nuxt.$router.push("/member");
                        }
                        if ($nuxt.$route.name == 'admin' && !loginInfo.authority) {
                            $nuxt.$router.push("/member");
                        }
                        commit('setLoginInfo', loginInfo)
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
    async getQuestion({ commit }) {
        await this.$axios
        .get(`/api/question/read`)
        .then((res) => {
            commit('setQuestions', res.data)
        })
        .finally(() => {})
    },
}