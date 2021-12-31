<template>
  <div >
    <HeaderNav pa_showback="" pa_title="" class=""/>

    <div class="content-center">
        <div class="message-list">
            <div v-if="resData.length==0" class="message" style="text-align: center">
              暂无更多公告
            </div>
            <div v-else class="message" v-for="(list,item) in resData" :key="item">
                <div class="message-content">
                    <div>
                    <h3 class="isRead-false">{{actionType=='notice'?'赛事公告':'财务公告'}}</h3>
                    <p class="time isRead-true">{{list.created_time}}</p>
                </div>
                    <p class="isRead-false message-content-without-thumbnail">
                        {{list.notice}}
                    </p>
                </div>
            </div>
        </div>
    </div>

      <FooterNav />
  </div>
</template>

<script>

    //import axios from 'axios'
    import Mixin from '@/Mixin'
    import HeaderNav from '@/components/Header'
    import FooterNav from '@/components/Footer'

    export default {
        name: 'Index',
        mixins:[Mixin],
        components: {
            HeaderNav,
            FooterNav
        },
        data () {
            return {
                actionType:'', // 默认 notice , 站内信 message
                resData:[]
            }
        },
        mounted: function () {
            let _self = this ;
            _self.actionType = _self.$route.query.msg_type?_self.$route.query.msg_type:'notice'; // 获取参数
            _self.getUserEmalis();

        },
        methods:{
          /* 获取公告 */
            getUserEmalis: function () {
                let _self =this;
                if(_self.submitflag){
                    return false ;
                }
                let senddata = {action:_self.actionType};
                _self.submitflag = true;
                return new Promise((resolve, reject)=>{
                    _self.axios({
                        method: 'post',
                        params: senddata,
                        url: _self.ajaxUrl.notice
                    }).then(res=>{
                        if(res){
                            _self.submitflag = false;
                            _self.resData = res.data.data;
                            resolve(res)
                        }
                    }).catch(res=>{
                        _self.submitflag = false;
                        console.log('获取公告失败');
                        reject(res);
                    });
                });
            }

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .message-list{color:#5a5959;width:94%;margin:1rem auto;text-align:left}
    .message{background:#ececec;margin-bottom:1rem;padding:.8rem;border-radius:5px}
    .message-content h3,.message-content .time{display:inline-block}
    .message-content .time{float:right;color:#ccc}
    .message-content .message-content-without-thumbnail{color:#504f4f;padding:1rem 0 0}
</style>
