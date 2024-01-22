import axios from "axios";
const CancelToken = axios.CancelToken;
let setLoginInfoByTokenCancel = null;

export const state = () => ({
    loginInfo: null,
    rootRock: false,
    questions: [],
    quizzes: [],
})

export const mutations = {
    setLoginInfo(state, loginInfo) {
        state.loginInfo = loginInfo
    },
    setRootRock(state, rootRock) {
        state.rootRock = rootRock
    },
    setQuestions(state, questions) {
        state.questions = questions
    },
    setQuizzes(state, quizzes) {
        state.quizzes = quizzes
    },
}

export const actions = {
    async setLoginInfoByToken({ commit, dispatch }) {
        if (setLoginInfoByTokenCancel) {
            setLoginInfoByTokenCancel()
        }
        const requestConfig = {
            url: `/api/user/bearer_authentication`,
            method: "GET",
            cancelToken: new CancelToken(c => {
                setLoginInfoByTokenCancel = c
            }),
        };
        this.$axios(requestConfig)
            .then((res) => {
                // console.log(res.data)
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
            .catch((err) => {
                if(!err.response)return
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
            .finally(() => { })
    },
    async getQuiz({ commit }) {
        await this.$axios
            .get(`/api/quiz/read`)
            .then((res) => {
                console.log(res.data);
                commit('setQuizzes', res.data)
            })
            .finally(() => { })
    },
    async lineMessage({ }, text) {
        this.$axios
            .post(`/api/line/message?text=${text}`)
            .then((res) => {
                console.log(res.data)
            })
            .catch((res) => {
                console.log(res)
            })
    },
    async lineTodayWorker() {
        this.$axios
            .post(`/api/line/today_worker`)
            .then((res) => {
                console.log(res.data)
            })
            .catch((res) => {
                console.log(res)
            })
    },
}