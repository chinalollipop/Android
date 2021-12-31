let base_SettingData = JSON.parse(localStorage.getItem('baseSetData'));
let tplName = ''; // 不存在就取根目录
if(base_SettingData){
    tplName = (base_SettingData.tpl_name).replace('views/','');
}
//console.log(tplName)
import Vue from 'vue'
import Router from 'vue-router'
//import Index from '@/components/0086/Index'
// import Login from '@/components/0086/Login'
// import Reg from '@/components/0086/Reg'
//import gustLogin from '@/components/common/gustLogin'
// import Promo from '@/components/0086/Promo'
// import Agent from '@/components/0086/Agent'
import agentsIndex from '@/components/common/agentsIndex'
import forgetPwd from '@/components/common/forgetPwd'
import ContactUs from '@/components/common/ContactUs'
import aboutUs from '@/components/common/aboutUs'
import presenceDetails from '@/components/common/presenceDetails'
import Help from '@/components/common/Help'
import moreMessage from '@/components/common/moreMessage'
import appDownload from '@/components/common/appDownload'
import appInstallation from '@/components/common/appInstallation'
import sportRoul from '@/components/sport/sportRoul'
import gameResult from '@/components/sport/gameResult'
import Sport from '@/components/sport/Sport'
import sportList from '@/components/sport/sportList'
import newCate from '@/components/sport/newCate'
import moreCate from '@/components/sport/moreCate'
import listGames from '@/components/common/listGames'
import Games  from '@/components/common/Games'
import gamesWin  from '@/components/common/gamesWin'
import upGraded from '@/components/common/upGraded'
// import myAccount from '@/components/0086/myAccount'
import Deposit from '@/components/common/Deposit'
// import depositSec from '@/components/0086/depositSec'
import withDraw from '@/components/common/withDraw'
import bankCard from '@/components/common/bankCard'
import setRealName from '@/components/common/setRealName'
import betRecord from '@/components/common/betRecord'
import depositRecord from '@/components/common/depositRecord'
import myDetail from '@/components/common/myDetail'
// import Tran from '@/components/0086/Tran'
import platForm from '@/components/common/platForm'
import mainTenance from '@/components/common/mainTenance'

Vue.use(Router)

/**
 * 重写路由的push方法
 */
const routerPush = Router.prototype.push
Router.prototype.push = function push(location) {
    return routerPush.call(this, location).catch(error=> error)
}

export default new Router({
  mode: 'history',// 去掉浏览器地址栏默认 # 符号,兼容app 打包
  routes: [
    {
      path: '/',
      redirect: 'home' // 重定向到首页
    },
    {
      path: '/home',
      name: 'index',
      component:resolve => require(['@/components/'+tplName+'Index'],resolve) // 这种也是按需加载
      //component(resolve){ require(['@/components/'+tplName+'Index'],resolve)}
    },
    {
        path: '/login',
        name: 'login',
        //component: Login
        component:resolve => require(['@/components/'+tplName+'Login'],resolve)
    },
    {
        path: '/reg',
        name: 'reg',
        //component: Reg
        component:resolve => require(['@/components/'+tplName+'Reg'],resolve)
    },
      {
          path: '/gustlogin',
          name: 'gustlogin',
          //component: gustLogin
          component:resolve => require(['@/components/'+tplName+'gustLogin'],resolve)
      },
      {
          path: '/promo',
          name: 'promo',
          //component: Promo
          component:resolve => require(['@/components/'+tplName+'Promo'],resolve)
      },
      {
          path: '/agent',
          name: 'agent',
          //component: Agent
          component:resolve => require(['@/components/'+tplName+'Agent'],resolve)
      },
      {
          path: '/agentsindex',
          name: 'agentsindex',
          component: agentsIndex
      },
      {
          path: '/forgetpwd',
          name: 'forgetpwd',
          component: forgetPwd
      },
      {
          path: '/contactus',
          name: 'contactus',
          component: ContactUs
      },
      {
          path: '/aboutus',
          name: 'aboutus',
          component: aboutUs
      },
      {
          path: '/presencedetails',
          name: 'presencedetails',
          component: presenceDetails
      },
      {
          path: '/help',
          name: 'help',
          component: Help
      },
      {
          path: '/moremessage',
          name: 'moremessage',
          component: moreMessage
      },
      {
          path: '/appdownload',
          name: 'appdownload',
          component: appDownload
      },
    {
      path: '/appinstallation',
      name: 'appinstallation',
      component: appInstallation
    },
      {
          path: '/sportroul',
          name: 'sportroul',
          component: sportRoul,
      },
      {
          path: '/gameresult',
          name: 'gameresult',
          component: gameResult,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/sport',
          name: 'sport',
          component: Sport,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/sportlist',
          name: 'sportlist',
          component: sportList,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/newcate',
          name: 'newcate',
          component: newCate,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/morecate',
          name: 'morecate',
          component: moreCate,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/listgames',
          name: 'listgames',
          component: listGames,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/games',
          name: 'games',
          component: Games,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/gameswin',
          name: 'gameswin',
          component: gamesWin,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },

      {
          path: '/upgraded',
          name: 'upgraded',
          component: upGraded
      },
      {
          path: '/myaccount',
          name: 'myaccount',
          //component: myAccount,
          component:resolve => require(['@/components/'+tplName+'myAccount'],resolve),
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/deposit',
          name: 'deposit',
          component: Deposit,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/depositsec',
          name: 'depositsec',
          //component: depositSec,
          component:resolve => require(['@/components/'+tplName+'depositSec'],resolve),
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/withdraw',
          name: 'withdraw',
          component: withDraw,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/bankcard',
          name: 'bankcard',
          component: bankCard,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/setrealname',
          name: 'setrealname',
          component: setRealName,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/betrecord',
          name: 'betrecord',
          component: betRecord,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/depositrecord',
          name: 'depositrecord',
          component: depositRecord,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/mydetail',
          name: 'mydetail',
          component: myDetail,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/platform',
          name: 'platform',
          component: platForm,
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/tran',
          name: 'tran',
          //component: Tran,
          component:resolve => require(['@/components/'+tplName+'Tran'],resolve),
          meta:{requireAuth:true} // 配置路由拦截，进入前需要登录
      },
      {
          path: '/maintenance',
          name: 'maintenance',
          component: mainTenance
      }

  ]
})

