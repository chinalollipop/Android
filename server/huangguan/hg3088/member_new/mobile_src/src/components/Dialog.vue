<template>
    <div v-if="showDialog" class="pop_all" :class="pop_more_dis">
        <div class="pop_bg" ></div>
        <div class="Pop-up pop_close" >
            <div class="btn_close pop_cls close_event" @click="closeDialog">
                <i class="fa fa-times"></i>
            </div>
            <div class="pop_title" v-html="dialogTip"></div>
            <div class="pop_text" v-html="dialogTitle"></div>
            <button class="login_btn" @click="closeDialog">确定</button>
        </div>
    </div>
</template>

<script>
    import Mixin from '@/Mixin'

    export default {
        name: 'Dialog',
        mixins:[Mixin],
        props:['pa_dialogtitle'], // 父组件传值给子组件
        data :function() {
            return {
                dialogTime:'',
                delay:3000,
                showDialog:false,
                dialogTip:'',
                dialogTitle:'',
                pop_more_dis:''
            }
        },
        mounted:function(){
            let _self = this;
        },
        methods:{
            //打开弹窗 text 提示文案，title_tip 提示背景标题 , typelogo 提示logo ,对应的类 cla
            setPublicPop:function(text,tip,dis,timer){
                this.dialogTitle = text;
                this.dialogTip = tip;
                this.pop_more_dis = dis;
                this.showDialog = true;
                if(timer){
                    this.delay = timer;
                }
                this.dialogTime = setTimeout(() => this.showDialog = false, this.delay);
            },
            //关闭弹窗
            closeDialog:function(){
                this.showDialog = false;
                clearTimeout(this.dialogTime);
            }
        }
    }
</script>
