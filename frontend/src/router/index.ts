import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '@/views/LoginView.vue'
import CallbackView from '@/views/CallbackView.vue'
import ContactsView from '@/views/ContactsView.vue'
import ReportsView from '@/views/ReportsView.vue'

const routes = [
  { path: '/login', component: LoginView },
  { path: '/callback', component: CallbackView },
  { path: '/contacts', component: ContactsView, meta: { requiresAuth: true } },
  { path: '/dashboard', component: ReportsView, meta: { requiresAuth: true } },
  { path: '/', redirect: '/contacts' }
]

export const router = createRouter({
  history: createWebHistory(),
  routes
})

// Guard global
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')

  if (to.meta.requiresAuth && !token) {
    // Se a rota exige auth e não há token → manda para login
    next('/login')
  } else if ((to.path === '/login' || to.path === '/callback') && token) {
    // Se já está logado e tenta voltar pro login → manda pra contacts
    next('/contacts')
  } else {
    next()
  }
})
