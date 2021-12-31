<template>
  <div id="app">
<!--
in-out :新元素先进入过渡，完成之后当前元素过渡离开。
out-in :当前元素先进行过渡离开，离开完成后新元素过渡进入。
-->
    <!--<transition name="fade" mode="out-in" >-->
      <router-view/>
    <!--</transition>-->
  </div>
</template>

<script>
import '../static/fonts/font-awesome.min.css'
import '../static/css/common.css'
//require ('../static/css/'+ (JSON.parse(localStorage.getItem('baseSetData'))?JSON.parse(localStorage.getItem('baseSetData')).tpl_name.replace('views/',''):'') +'iphone.css')

//import axios from 'axios'
import Mixin from '@/Mixin'

export default {
  name: 'App',
    mixins:[Mixin],
    data () {
        return {

        }
    },
    mounted: function () {
        let _self = this;

        _self.addTitle();

        if(!this.memberData){
            if(localStorage.getItem('baseStatus') !='1'){ // 首次打开没有获取到配置信息
                _self.getBaseSetting(_self.reloadFirst());
            }
        }
    },
    methods:{
      reloadFirst:function(){
          //console.log('222'+this.$route.path)
        setTimeout(()=>{
            location.reload();
        },1000)
      },
      // 添加标题等
      addTitle:function () {
          let _self = this;
          window.document.title = _self.company_name;

          let $head = document.head || document.getElementsByTagName('head')[0];
          let link = document.createElement('link');
          let link_1 = document.createElement('link');
          let link_2 = document.createElement('link');
          link.id = 'img_favicon';
          link.rel = 'shortcut icon';
          link.type = 'image/x-icon';
          link.href = './static/images/'+_self.tpl_name+'favicon.ico';

          link_1.rel = 'apple-touch-icon-precomposed';
          link_1.sizes = '72x72';
          link_1.href = './static/images/'+_self.tpl_name+'add-logo.png'; // 添加到桌面

          link_2.rel = 'stylesheet';
          link_2.type = 'text/css';
          link_2.href = './static/css/'+_self.tpl_name+'iphone.css?v='+Math.random(); // 添加到桌面

          $head.appendChild(link);
          $head.appendChild(link_1);
          $head.appendChild(link_2);

      }
    }
}
</script>

<style>
  /* 路由切换过渡效果 */
  .fade-enter {opacity:0;}
  .fade-leave{opacity:1;}
  .fade-enter-active{transition:opacity .2s;}
  .fade-leave-active{opacity:0;transition:opacity .2s;}

</style>
