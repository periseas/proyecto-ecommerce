import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import OrdersView  from '../views/OrdersView.vue'

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeView
    },
    {
        path: '/login',
        name: 'login',
        component: LoginView
    },
    {
        path: '/register',
        path: '/register',
        name: 'register',
        component: RegisterView
    },
    {
        path: '/orders',
        path: '/orders',
        name: 'ordenes',
        component: OrdersView 
    }
]


const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
