<!-- APP首次引用时候进来需要，避免报错 -->
<template>

</template>

<script>

    export default {
        name: 'Index',
        //mixins:[Mixin],
        components: {

        },
        data () {
            return {
                apptip:''
            }
        },
        mounted: function () {
            let _self = this ;
            _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数
        },
        methods:{

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
