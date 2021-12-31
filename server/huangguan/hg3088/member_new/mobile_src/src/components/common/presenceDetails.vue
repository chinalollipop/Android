<template>
  <div >
    <HeaderNav pa_showback="" pa_title="" :class="apptip=='app' && 'hide-cont'"/>

    <!-- 中间内容 -->
    <div class="content-center">

      <div class="about_us game-list" >

      </div>

      <div class="get_more_data" style="display: none;"><a class="get_more_action" data-page="1">加载更多数据</a></div>

    </div>

      <FooterNav :class="apptip=='app' && 'hide-cont'" />
  </div>
</template>

<script>

    //import axios from 'axios'
    import Mixin from '@/Mixin'
    import HeaderNav from '@/components/Header'
    import FooterNav from '@/components/Footer'

    export default {
        name: 'presence',
        mixins:[Mixin],
        components: {
            HeaderNav,
            FooterNav
        },
        data () {
            return {
                apptip:'',
                id:''
            }
        },
        mounted: function () {
            let _self = this ;

            _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数
            _self.id = _self.$route.query.id?_self.$route.query.id:''; // 获取参数

            _self.getNewsRecommend('content',_self.id);


        },
        methods:{
              // 获取新闻
              getNewsRecommend:function (type,id,page,more) {
                let _self = this;
                  if(_self.submitflag){
                      return false ;
                  }
                var str = '';
                var $gamelist = $('.game-list');

                  _self.submitflag = true;

                  _self.axios({
                      method: 'post',
                      params: {action: type, id: id, page: page}, // thumb : 首页缩略图，内容页: content&id=1 ,太阳城分彩分页 10条/页  action=list&page=0
                      url: _self.ajaxUrl.artice
                  }).then(re=>{
                      let res = re.data;
                      if(res.data){
                          _self.submitflag = false;
                          switch (type){
                              case 'content': // 详情
                                  str += '<div class="timebox " ><p class="title">'+ res.data.title +'</p>'+ res.data.content +'</div>';
                                  $gamelist.html(str);
                                  break;
                          }

                      }
                  }).catch(res=>{
                      _self.submitflag = false;
                      console.log('获取新闻失败');
                  });
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .content-center>>> .timebox{background:#fff;width:94%;margin:.8rem auto .5rem;text-align:left}
  .content-center>>> .timebox img{width:100%;height: auto;}
  .content-center>>> .timebox .url{color:#3c3941;font-size:1rem;font-weight:normal;border-bottom:1px dashed #3c3941;transition:all .5s ease;padding-bottom:.5rem;margin:.5rem}
  .content-center>>> .timebox p{color:#3e3e3e;margin:0 .5rem;padding-bottom:1rem}
  .content-center>>> .timebox p.title {color: #f00;font-size: 1.3rem;text-align: center;padding: 10px 0;}
</style>
