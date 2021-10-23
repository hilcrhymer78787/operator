import colors from 'vuetify/es5/util/colors'

export default {
    // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
    ssr: false,

    // Global page headers: https://go.nuxtjs.dev/config-head
    head: {
        titleTemplate: '%s - nuxt',
        title: 'operator',
        meta: [
            { charset: 'utf-8' },
            { name: 'viewport', content: 'width=device-width, initial-scale=1' },
            { hid: 'description', name: 'description', content: '' },
            { name: 'format-detection', content: 'telephone=no' },
            { name: 'apple-mobile-web-app-capable', content: 'yes' },
            { name: 'apple-mobile-web-app-status-bar-style', content: 'white' },
        ],
        link: [
            { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
        ]
    },
    server: {
        host: '0.0.0.0'
    },
    router: {
        mode: 'hash',
        middleware: 'authenticated'
    },
    // Global CSS: https://go.nuxtjs.dev/config-css
    css: [
        '@/assets/common.scss',
    ],

    // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
    plugins: [
        { src: '~/plugins/constants.js' },
    ],

    // Auto import components: https://go.nuxtjs.dev/config-components
    components: true,

    // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
    buildModules: [
        // https://go.nuxtjs.dev/typescript
        '@nuxt/typescript-build',
        // https://go.nuxtjs.dev/vuetify
        '@nuxtjs/vuetify',
        '@nuxtjs/moment',
        'cookie-universal-nuxt',
    ],

    // Modules: https://go.nuxtjs.dev/config-modules
    modules: [
        // https://go.nuxtjs.dev/axios
        '@nuxtjs/axios',
        // https://go.nuxtjs.dev/pwa
        '@nuxtjs/pwa',
    ],

    // Axios module configuration: https://go.nuxtjs.dev/config-axios
    axios: {
        // baseURL: 'http://192.168.3.5:8000'
        baseURL: 'http://localhost:8000'
        // baseURL: 'https://operator-back.magicgifted.com/operator/laravel/public'
    },

    // PWA module configuration: https://go.nuxtjs.dev/pwa
    manifest: {
        name: 'operator',
        lang: 'ja',
        short_name: 'operator',
        title: 'operator',
        background_color: '#00ccaf',
        color: 'white',
    },

    // Vuetify module configuration: https://go.nuxtjs.dev/config-vuetify
    vuetify: {
        customVariables: ['~/assets/variables.scss'],
        theme: {
            themes: {
                light: {
                    main: "#000066",
                    sub: "#f3920b",
                    danger: "#fd7171",
                }
            }
        }
    },

    // Build Configuration: https://go.nuxtjs.dev/config-build
    build: {
    }
}
