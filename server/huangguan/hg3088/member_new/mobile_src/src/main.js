// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import apiConfig from "../config/api.config.js";
import axios from 'axios'
import VueAxios from 'vue-axios'
import App from './App'
import router from './router'

axios.defaults.withCredentials=true; // 表示请求可以携带cookie
Vue.use(VueAxios, axios);

Vue.config.productionTip = false;
axios.defaults.baseURL = apiConfig.baseURL;
//Vue.config.debug = true;
// 务必在加载 Vue 之后，立即同步设置以下内容
//Vue.config.devtools = true

// 取消上个路由页面未成功的 axios 请求
let axiosPromiseArr=[] //储存cancel token
axios.interceptors.request.use(function (config) {
    // 在发送请求设置cancel token
    config.cancelToken = new axios.CancelToken(cancel => {
        axiosPromiseArr.push({cancel})
    })
    return config;
}, function (error) {
    // 对请求错误的处理
    return Promise.reject(error);
});



/* 未登录时部分路由跳转到登录 */
router.beforeEach((to, from, next) => {
    let memberData = JSON.parse(localStorage.getItem('userData'));
    let time = new Date().getTime();
    let result = '';
    let url_all = window.location;
    let url_par = url_all.search.toLowerCase();
    if(to.path==='/home'){
      if(url_par.indexOf('intr')>0){
        let url_arr = url_par.split('?') ;
        let intrarr = url_arr[1].split('=');
        localStorage.setItem('agent_account',intrarr[1]); // 代理推广码
        //window.location.href = url_all.protocol+'//'+url_all.host+'/#/reg'+url_par; // 跳转到注册页面
        //window.location.href = url_all.protocol+'//'+url_all.host+'/#/home'; // 跳转到首页，hash 模式下
        window.location.href = url_all.protocol+'//'+url_all.host+'/home'; // 跳转到首页
      }
    }
    if (memberData) {
        if (time < memberData.expire) {
            result = memberData.data;
        } else {
            localStorage.removeItem(name);
        }
    }

    // 取消上个路由页面未成功的 axios 请求
    axiosPromiseArr.forEach((ele, index) => {
        ele.cancel()
        delete axiosPromiseArr[index]
    });

    // chrome 置顶
    document.body.scrollTop = 0;
    // firefox 置顶
    document.documentElement.scrollTop = 0;

    if (to.matched.some(record => record.meta.requireAuth)) { // 判断该路由是否需要登录权限
        if (result!='') { // 判断缓存里面是否有 userName  //在登录的时候设置它的值
            next();
        } else {
            next({
                path: '/login',
                // query: {
                //     redirect: to.fullPath
                // } // 将跳转的路由path作为参数，登录成功后跳转到该路由
            })
        }
    } else {
        next();
    }
})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
