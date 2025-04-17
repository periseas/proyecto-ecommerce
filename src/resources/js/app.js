// resources/js/app.js
import { createApp } from 'vue';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

import 'vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.css';

import App from './app.vue'; // el componente raíz de la app
import router from './router/index.js'; // sistema de rutas Vue Router
import AppLayout from './Layout/AppLayout.vue'

// Configuración de Vuetify
const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                colors: {
                primary: '#2288a9',
                secondary: '#3fc1c9',
                },
            },
        },
    },
});

// Crear instancia de Vue y montar

createApp(AppLayout)
    .use(vuetify)
    .use(router)
    .mount('#app') 
