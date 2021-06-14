const path = require('path');

module.exports = {
    publicPath: process.env.NODE_ENV === 'production' ? '/primevue/showcase/' : '/',
    productionSourceMap: false,
    configureWebpack: {
        resolve: {
            alias: {
            'primevue/ripple': path.resolve(__dirname, 'resources/js/components/ripple/Ripple.js'),
            'primevue/useconfirm': path.resolve(__dirname, 'resources/js/components/useconfirm/UseConfirm.js'),
            'primevue/usetoast': path.resolve(__dirname, 'resources/js/components/usetoast/UseToast.js'),
            'primevue/utils': path.resolve(__dirname, 'resources/js/components/utils/Utils.js'),
            'primevue/api': path.resolve(__dirname, 'resources/js/components/api/Api.js'),
            'primevue/button': path.resolve(__dirname, 'resources/js/components/button/Button.vue'),
            'primevue/inputtext': path.resolve(__dirname, 'resources/js/components/inputtext/InputText.vue'),
            'primevue/dialog': path.resolve(__dirname, 'resources/js/components/dialog/Dialog.vue'),
            'primevue/menu': path.resolve(__dirname, 'resources/js/components/menu/Menu.vue'),
            'primevue/dropdown': path.resolve(__dirname, 'resources/js/components/dropdown/Dropdown.vue'),
            'primevue/paginator': path.resolve(__dirname, 'resources/js/components/paginator/Paginator.vue'),
            'primevue/progressbar': path.resolve(__dirname, 'resources/js/components/progressbar/ProgressBar.vue'),
            'primevue/message': path.resolve(__dirname, 'resources/js/components/message/Message.vue'),
            'primevue/tree': path.resolve(__dirname, 'resources/js/components/tree/Tree.vue'),
            'primevue/confirmationeventbus': path.resolve(__dirname, 'resources/js/components/confirmationeventbus/ConfirmationEventBus.js'),
            'primevue/toasteventbus': path.resolve(__dirname, 'resources/js/components/toasteventbus/ToastEventBus.js'),
            'primevue/overlayeventbus': path.resolve(__dirname, 'resources/js/components/overlayeventbus/OverlayEventBus.js'),
            'primevue/terminalservice': path.resolve(__dirname, 'resources/js/components/terminalservice/TerminalService.js')
            },
        },
        output: {
            libraryExport: 'default'
        }
    },
    css: {
        extract: false
    }
}
