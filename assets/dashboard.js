import { createApp } from 'vue';
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from "vuetify";
import dashboard from "./dashboard.vue";

const vuetify = createVuetify({});

const app = createApp(dashboard);

app.use(vuetify);

app.mount('#app');