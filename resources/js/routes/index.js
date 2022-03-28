import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

const routes = [
    {
        path:'/',
        component: require('../components/pages/Plan/Welcome').default,
        name:'welcome',
        meta: {
            title: 'Welcome',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
        },
    },
    {
        path:'/content',
        component: require('../components/pages/content').default,
        name:'content',
        meta: {
            title: 'Content',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
        },
    },
    {
        path:'/dashboard',
        component: require('../components/pages/content/Dashboard').default,
        name:'dashboard',
        meta: {
            title: 'Dashboard',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
        },
    },
    {
        path:'/sms',
        component: require('../components/pages/content/sms').default,
        name:'sms',
        meta: {
            title: 'sms',
            ignoreInMenu: 0,
            displayRight: 0,
            dafaultActiveClass: '',
        },
    },
];
// // This callback runs before every route change, including on page load.
const router = new VueRouter({
    mode:'history',
    routes,
    scrollBehavior() {
        return {
            x: 0,
            y: 0,
        };
    },

});
// function lsTest(){
//     var test = 'test';
//     try {
//         localStorage.setItem(test, test);
//         localStorage.removeItem(test);
//         return true;
//     } catch(e) {
//         return false;
//     }
// }
// var is_cookie_enable = false;
// if(lsTest() === true){
//     is_cookie_enable = true;
// }else{
//     // alert('Please enable cookie to get best experience on our app :)');
// }
//
// if(is_cookie_enable){
//     router.afterEach(to => {
//         window.Intercom("update");
//         let id = to.params.id;
//         let planGroupId = to.params.planGroupId;
//
//         if (typeof id != 'undefined') {
//             let nextRoute = to.name;
//             let isParam = nextRoute.indexOf("/");
//
//             let url = (isParam >= 0) ? nextRoute.substr(0, (isParam)) : to.name;
//             sessionStorage.setItem('LS_ROUTE_KEY', url + '/' + id);
//         }
//         else if(typeof planGroupId != 'undefined'){
//             let planGroupId = to.params.planGroupId;
//
//             let nextRoute = to.name;
//             let isParam = nextRoute.indexOf("/");
//             let url = (isParam >= 0) ? nextRoute.substr(0, (isParam)) : to.name;
//             sessionStorage.setItem('LS_ROUTE_KEY', url + '/' + planGroupId );
//         }else{
//             sessionStorage.setItem('LS_ROUTE_KEY', to.name);
//         }
//         onLoad = 0;
//     });
//
//     router.beforeEach((to, from, next) => {
//         const lastRouteName = (isLoad == 1) ? sessionStorage.getItem('LS_ROUTE_KEY') : 'dashboard';
//         const shouldRedirect = Boolean(lastRouteName && lastRouteName !== 'dashboard' && to.name === 'dashboard');
//         const is_Exist_contract = window.contractID;
//
//         if (is_Exist_contract == -2) {
//             window.contractID = 0;
//             next({name: 'plans'});
//         } else if (is_Exist_contract > 0 || is_Exist_contract == -1) {
//             window.contractID = 0;
//             next({name: 'subscriber-details', params: {id: is_Exist_contract}});
//         } else {
//             if (shouldRedirect) {
//                 if (onLoad == 1 && lastRouteName != 'dashboard') {
//                     let isParam = lastRouteName.indexOf("/");
//                     if (isParam >= 0) {
//                         let param = lastRouteName.substr((isParam + 1));
//                         let url = lastRouteName.substr(0, (isParam));
//
//                         next({name: url, params: {id: param}});
//                     } else {
//                         next({name: lastRouteName});
//                     }
//                 } else if (onLoad == 1 && to.name == 'dashboard') next();
//                 else next();
//             } else next();
//         }
//     });
//
//
//     window.onbeforeunload = function(e) {
//         sessionStorage.setItem('IS_LOAD', 1);
//     };
//     const isLoad = sessionStorage.getItem('IS_LOAD');
//     sessionStorage.setItem('IS_LOAD', 0);
// }

let onLoad = 1;
export default router;
