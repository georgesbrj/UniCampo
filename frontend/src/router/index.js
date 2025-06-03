import { createRouter, createWebHistory } from 'vue-router'
import ClientsView from '@/views/ClientsView.vue'
import ClientCreateView from '@/views/ClientCreateView.vue'
 
const routes = [
  {
    path: '/',
    name: 'clients',
    component: ClientsView
  },
  {
    path: '/clientes/novo',
    name: 'client-create',
    component: ClientCreateView
  },
  {
    path: '/clientes/:id',
    name: 'client-edit',
    component: ClientCreateView,
    props: true
  }
 
]

const router = createRouter({
  history: createWebHistory(import.meta.env.VITE_BASE_URL),
  routes
})

export default router