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
        name: 'aboutus',
        mixins:[Mixin],
        components: {
            HeaderNav,
            FooterNav
        },
        data () {
            return {
                apptip:''
            }
        },
        mounted: function () {
            let _self = this ;

            _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数

            _self.getNewsRecommend('list','',0);
            _self.showNewsDetails();
            _self.getMoreData();

        },
        methods:{
              // 获取新闻
              getNewsRecommend:function (type,id,page,more) {
                let _self = this;
                  if(_self.submitflag){
                      return false ;
                  }
                var $get_more_data = $('.get_more_data');
                var $get_more_action = $('.get_more_action');
                var str = '';
                var $gamelist = $('.game-list');
                var $imgBox_content = $('.imgBox_content'); // 风采内容

                if(more){ // 加载更多数据
                    var curpage = Number($get_more_action.data('page')) ; // 当前页面
                }
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
                                  // $imgBox_content.html( res.data.content);
                                  break;
                              case 'list': // 风采页面列表
                                  if(res.data.list) {
                                      $get_more_action.attr('data-count', res.data.page_count); // 总页数
                                      if(res.data.page_count > 1){ // 总页数
                                          $get_more_action.html('加载更多数据') ;
                                          $get_more_data.show() ;
                                      }else{
                                          $get_more_data.hide() ;
                                      }
                                      if(more) { // 加载更多数据
                                          curpage++ ;
                                          $get_more_action.attr('data-page',curpage) ;
                                          if(curpage == res.data.page_count){
                                              $get_more_action.html('没有更多数据了') ;
                                          }
                                      }
                                      for (var i = 0; i < res.data.list.length; i++) {
                                          str += '<div class="timebox" data-id="'+ res.data.list[i].id +'">' +
                                              '                <div class="timeMain">' +
                                              '                    <div class="imgBox "><img class="img" src="'+ res.data.list[i].cover +'">' +
                                              '                    </div>' +
                                              '                    <h3 class="url">'+ res.data.list[i].title +'</h3>' +
                                              '                    <p>'+ res.data.list[i].subtitle +'</p></div>' +
                                              '   <div class="imgBox_content"> </div> ' +
                                              '</div>';
                                      }
                                      $gamelist.append(str);
                                  }else{
                                      $gamelist.html('<div class="no-data">暂无数据</div>');
                                      $get_more_data.hide() ;
                                  }


                                  break;
                          }


                      }
                  }).catch(res=>{
                      _self.submitflag = false;
                      console.log('获取新闻失败');
                  });
            },

            // 点击显示新闻详情
            showNewsDetails:function () {
              let _self = this;
              $('.game-list').on('click','.timebox',function () {
                  var id = $(this).attr('data-id');
                  if(!id){
                      return false;
                  }
                  _self.$router.push('presencedetails?tip='+_self.apptip+'&id='+id);
              })
          },

          // 加载更多数据
          getMoreData:function () {
              let _self = this;
              let $get_more_action = $('.get_more_action');
              $get_more_action.on('click',function () {
                  $get_more_action.html('加载中...') ;
                  var curpage = Number($(this).data('page')) ;
                  var allcount = Number($(this).data('count')) ; // 总页数

                  if(curpage<1 || curpage >= allcount){ // 没有数据
                      $get_more_action.html('没有更多数据了') ;
                      return false ;
                  }
                  _self.getNewsRecommend('list','',curpage,'more');
              }) ;

          }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .content-center>>> .timebox{background:#fff;width:94%;margin:.8rem auto .5rem;text-align:left}
  .content-center>>> .timebox img{width:100%}
  .content-center>>> .timebox .url{color:#3c3941;font-size:1rem;font-weight:normal;border-bottom:1px dashed #3c3941;transition:all .5s ease;padding-bottom:.5rem;margin:.5rem}
  .content-center>>> .timebox p{color:#ccc;margin:0 .5rem;padding-bottom:1rem}
  .content-center>>> .no-data{color: #656565;font-size: 1.2rem;padding: 1rem;}
</style>
