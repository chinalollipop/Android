<template>
  <div >
    <HeaderNav pa_showback="" pa_title="" class=""/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center deposit contact_us">
        <div class="tab">
            <div class="deposit-nav contactus_nav">
                <div class="item">
                    <i class="bank_img qq_icon"></i><span>&nbsp;QQ客服 <strong class="qq_number copy_text_qq">{{baseSettingData.service_qq}}</strong></span>
                    <a v-if="tplnameSecList.indexOf(tpl_name)>=0" class="add_contanct_btn add_qq_contanct_btn right copy" data-clipboard-target=".copy_text_qq">复制号码</a>
                    <a v-else class="add_contanct_btn add_qq_contanct_btn right" @click="openNewGame('mqqwpa://im/chat?chat_type=wpa&uin='+baseSettingData.service_qq+'&version=1&src_type=web&web_src=oicqzone.com','','no')">开始聊天</a>
                    <!--  或者 http://wpa.qq.com/msgrd?v=3&uin=59901788&site=qq&menu=yes  -->
                </div>
                <div v-if="tpl_name!=='wnsr/'" class="item">
                    <i class="bank_img wechat_icon"></i><span>&nbsp;微信公众号： <strong class="wechat_number copy_text_wechat">{{baseSettingData.service_wechat}}</strong></span>
                    <a v-if="baseSettingData.service_wechat" class="add_contanct_btn add_wechat_contanct_btn right copy" data-clipboard-target=".copy_text_wechat">复制号码</a> <!-- @click="checkAction" -->
                    <!--<a v-else class="add_contanct_btn add_wechat_contanct_btn  right" @click="checkAction">开始聊天</a>-->
                </div>

            </div>

            <div v-if="tpl_name!=='wnsr/'" class="server-img " v-show="checkStatus"> <img v-if="baseSettingData.server_wechat_code" :src="baseSettingData.server_wechat_code" alt="微信公众号"> </div>
        </div>
    </div>

      <FooterNav />
  </div>
</template>

<script>
    import ClipboardJS from 'clipboard';

    import Mixin from '@/Mixin'
    import HeaderNav from '@/components/Header'
    import FooterNav from '@/components/Footer'
    import Dialog from '@/components/Dialog'

    export default {
        name: 'contactus',
        mixins:[Mixin],
        components: {
            HeaderNav,
            FooterNav,
            Dialog
        },
        data () {
            return {

            }
        },
        mounted: function () {

            this.$nextTick(()=>{
                this.copyTextAction();
            });
        },
        methods:{
            // 复制号码
            copyTextAction: function () {
                let _self = this;
                $('.contactus_nav').find('.copy').each(function (num) {
                    //console.log(num+'==');
                    let clipboard = new ClipboardJS(this, {
                        text: function () {
                            return $(this).prev().text();
                        }
                    });
                    clipboard.on('success', function (e) {
                        //console.log(e);
                        _self.$refs.autoDialog.setPublicPop('复制成功');
                        e.clearSelection();
                    });
                    clipboard.on('error', function (e) {
                        //console.log(e);
                        _self.$refs.autoDialog.setPublicPop('请选择“拷贝”进行复制');
                    });
                });
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .deposit-nav .item:after{display: none;}
    .add_contanct_btn {height: 3rem;line-height: 3rem;width: 6.3rem;text-align: center;border: 1px solid #EEE;margin-top: .85rem;border-radius: 5px;}
    .server-img {text-align: right;transition:500ms; -webkit-transition:500ms;}
    .server-img img{width: 10rem;}
    .deposit .tab .item{padding: .3rem 1%;}
</style>
