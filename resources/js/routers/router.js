import Vue from 'vue'
import VueRouter from 'vue-router'

import AppUnauthenticated from '../components/AppUnauthenticated'
import AppAuthenticated from '../components/AppAuthenticated'

import AppProduct from '../views/Product/AppProduct'
import AppIndent from '../views/Indent/AppIndent'
import AppHistory from '../views/History/AppHistory'
import AppReceivingReport from '../views/ReceivingReport/AppReceivingReport'
import AppPosting from '../views/Posting/AppPosting'

import AppLogin from '../views/Auth/AppLogin'
import AppLogout from '../views/Auth/AppLogout'


Vue.use(VueRouter)

const routes = [
    {
        path:'/login', component: AppUnauthenticated,
        children: [
            { path: '/login', component: AppLogin },
        ]
    },

    {
        path:'/', component: AppAuthenticated,
        children: [
            { path: '/', redirect: '/posting' },
            { path: '/product', component: AppProduct },
            { path: '/indent', component: AppIndent },
            { path: '/history', component: AppHistory },
            { path: '/receiving-report', component: AppReceivingReport },
            { path: '/posting', component: AppPosting },
            { path: '/logout', component: AppLogout, },
        ],
        meta: { requiresAuth: true }
    },


]

const router = new VueRouter({
    routes,
    hashbang: false,
    mode: 'history',
})

router.beforeEach(async (to, from, next) => {
    // check if the route requires authentication and user is not logged in
    if (to.matched.some(route => route.meta.requiresAuth)) {
        try {
            if(!localStorage.getItem('stockist-data')) {
                next({ path: '/login', replace: true})
                return
            }
        } catch (err) {
            return
        }
    }

    // if logged in redirect to dashboard
    if(to.path === '/login') {
        if(localStorage.getItem('stockist-data')) {
            next({ path: '/', replace: true})
            return
        }
    }

    next()
})

export default router;
