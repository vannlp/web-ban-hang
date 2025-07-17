import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { createVuetify } from 'vuetify'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import 'vue3-easy-data-table/dist/style.css';
import '../css/app.css'
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net-dt'
// import 'datatables.net-dt/css/jquery.dataTables.css'

import { ZiggyVue } from 'ziggy-js'

const vuetify = createVuetify({
    icons: {
        defaultSet: 'mdi', // 👈 dùng mdi
    },
})

DataTable.use(DataTablesLib)

createInertiaApp({
    resolve: name => {
        let pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .component('DataTable', DataTable)
            .mount(el)
    },
})
