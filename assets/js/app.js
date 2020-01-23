import Vue from 'vue';
import App from './components/App';
import UserList from './components/UserList';
import UserAdd from './components/UserAdd';
import Router from 'vue-router'
import { shallowMount } from '@vue/test-utils'
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
  { path: '/userlist', component: UserList },
  { path: '/adduser', name:'UserAdd', component: UserAdd, props:true }
]
/**
 * Router Vue object
 * @type Router
 */
const router = new Router({
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
