import Vue from 'vue'
import VueRouter from 'vue-router'
import ProductsView from '../views/ProductsView.vue'
import AddProductView from '../views/AddProductView.vue'
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'ProductsView',
    component: ProductsView
  },
  {
    path: '/addproduct',
    name: 'AddProductView',
    component: AddProductView
  },

]

const router = new VueRouter({
  routes
})

export default router
