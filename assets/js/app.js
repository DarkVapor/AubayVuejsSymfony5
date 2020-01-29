import Vue from 'vue';
import App from './components/App';
import UserList from './components/UserList';
import UserAdd from './components/UserAdd';
import Login from './components/Login';
import Router from 'vue-router'
import { shallowMount } from '@vue/test-utils'
import VueCookies from 'vue-cookies'

Vue.use(VueCookies)
Vue.$cookies.config('30d')
/**
 * Routing
 */
Vue.use(Router);
shallowMount(App, {
  stubs: ['router-link', 'router-view']
})
/**
 * Route List
 * @type Array
 */
const routes = [
  { path: '/userlist', name:"UserList", component: UserList },
  { path: '/adduser', name:'UserAdd', component: UserAdd, props:true },
  { path: '/login', name:'Login', component: Login}
]
/**
 * Router Vue object
 * @type Router
 */
export const router = new Router({
  routes:routes
})
/**
 * 
 * Application Start
 */
const app = new Vue({
    router:router,
    el: '#app',
    render: h => h(App)
});

export const VueInstance = Vue;
