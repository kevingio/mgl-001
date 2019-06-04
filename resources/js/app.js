window.Vue = require('vue');
window.axios = require('axios');

import Vuetify from 'vuetify'
import router from './routers/router.js'
import axios from 'axios'
import VueSwal from 'vue-swal'
import FullScreen from 'vue-fullscreen'

import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify, {
    iconfont: 'md',
    // override colors
    theme: {
        primary: "#db0000",
        black: "#030303",
        white: "#FFFFFF",
        blue: "#2B3A4A",
        darkblue: "#263442",
    }
});

Vue.use(VueSwal);
Vue.use(FullScreen);

Vue.prototype.$getToken = function() {
    const data = JSON.parse(localStorage.getItem('stockist-data'))
    return data.access_token
}

Vue.prototype.$getUser = function() {
    const data = JSON.parse(localStorage.getItem('stockist-data'))
    return data.user
}

Vue.prototype.$successAlert = function(item, type) {
    var status = 'deleted'
    switch (type) {
        case 0:
            status = 'edited'
            break;
        case 1:
            status = 'created'
            break;
        default:
    }
    let text = item + " has been " + status + "!"
    swal({
        title: "Success!",
        text: text,
        icon: "success",
        button: "Tutup",
    });
}

Vue.prototype.$errorAlert = function() {
    swal({
        title: "Whoops",
        text: "Something went wrong!",
        icon: "error",
        button: "Close",
    });
}

import AppRoot from './AppRoot'
var root = document.getElementById("app");
if(root){
    const app = new Vue({
        el: '#app',
        router,
        components: {
            AppRoot,
        },
    });
}

import AppOffline from './AppOffline'
var offline = document.getElementById("offline");
if(offline){
    const appOffline = new Vue({
        el: '#offline',
        components: {
            AppOffline
        },
    });
}
