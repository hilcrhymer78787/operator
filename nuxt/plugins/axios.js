export default function ({ $axios, $cookies }) {
    $axios.onRequest((config) => {
        config.headers.token = $cookies.get("token")
        return config
    })
    $axios.onError((error) => {
        if (error.response) {
            console.error(error.response);
            if (error.response.status == 429) {
                alert('一定時間にアクセスが集中したため、しばらくアクセスできません')
            }
        }
    })
}