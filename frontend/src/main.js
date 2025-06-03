import './assets/main.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import {Form,Field, ErrorMessage } from 'vee-validate'
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

const app = createApp(App)

app.component('Form', Form)
app.component('Field', Field)
app.component('ErrorMessage', ErrorMessage)

app.use(Toast, {
  position: 'top-right',
  timeout: 3000,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.6,
  
})

app.use(router)

app.mount('#app')