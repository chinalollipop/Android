package com.hfcp.hf.data;

import java.util.List;

public class DepositMethodResult {

    private List<AlipayAndWeiXinBean> alipay;
    private List<AlipayAndWeiXinBean> weixin;
    private List<AlipayAndWeiXinBean> bank;
    private List<AlipayAndWeiXinBean> kscz;
    private List<AlipayAndWeiXinBean> yunshanfu;
    public List<AlipayAndWeiXinBean> getAlipay() {
        return alipay;
    }

    public void setAlipay(List<AlipayAndWeiXinBean> alipay) {
        this.alipay = alipay;
    }

    public List<AlipayAndWeiXinBean> getWeixin() {
        return weixin;
    }

    public void setWeixin(List<AlipayAndWeiXinBean> weixin) {
        this.weixin = weixin;
    }

    public List<AlipayAndWeiXinBean> getBank() {
        return bank;
    }

    public void setBank(List<AlipayAndWeiXinBean> bank) {
        this.bank = bank;
    }

    public List<AlipayAndWeiXinBean> getKscz() {
        return kscz;
    }

    public void setKscz(List<AlipayAndWeiXinBean> kscz) {
        this.kscz = kscz;
    }

    public List<AlipayAndWeiXinBean> getYunshanfu() {
        return yunshanfu;
    }

    public void setYunshanfu(List<AlipayAndWeiXinBean> yunshanfu) {
        this.yunshanfu = yunshanfu;
    }

    public static class AlipayAndWeiXinBean {
        /**
         * id : 96
         * identifier : zhifubao
         * name : 他人支付宝1.1/宋军
         * display_name : 支付宝扫码
         * web :
         * ip :
         * need_bank : 0
         * customer_id : null
         * customer_key : null
         * relay_load_url : test
         * load_url :
         * test_load_url :
         * charset :
         * return_url : test
         * notify_url : test
         * unload_url :
         * query_enabled : 0
         * query_url :
         * relay_query_url : test
         * check_ip : 0
         * query_on_callback : 0
         * status : 3
         * is_default : 0
         * type : 1
         * sequence : 0
         * notice :
         * created_at : 2018-07-08 15:26:50
         * updated_at : 2019-02-16 16:05:09
         * payer_name_enabled : 1
         * everyday_start_time : null
         * everyday_end_time : null
         * deposit_max_amount : 1999.99
         * deposit_min_amount : 10.00
         * pay_type : 0
         * is_show_qrcode_url : 0
         * teminal : 0
         * grade : 1;3;5
         * icon_type : 2
         * brief_description : 大额首选--每笔充值贴心回馈1%优惠(北京时间23:30-01:12延迟)
         * brief_description_color : #fa0f0f
         */

        private boolean isChecked;
        private String id;
        private String identifier;
        private String name;
        private String display_name;
        private String web;
        private String ip;
        private int need_bank;
        private Object customer_id;
        private Object customer_key;
        private String relay_load_url;
        private String load_url;
        private String test_load_url;
        private String charset;
        private String return_url;
        private String notify_url;
        private String unload_url;
        private int query_enabled;
        private String query_url;
        private String relay_query_url;
        private int check_ip;
        private int query_on_callback;
        private int status;
        private int is_default;
        private int type;
        private int sequence;
        private String notice;
        private String created_at;
        private String updated_at;
        private int payer_name_enabled;
        private Object everyday_start_time;
        private Object everyday_end_time;
        private String deposit_max_amount;
        private String deposit_min_amount;
        private int pay_type;
        private int is_show_qrcode_url;
        private int teminal;
        private String grade;
        private int icon_type;
        private String link;
        private String brief_description;
        private String brief_description_color;

        public boolean isChecked() {
            return isChecked;
        }

        public void setChecked(boolean checked) {
            isChecked = checked;
        }

        public String getId() {
            return id;
        }

        public void setId(String id) {
            this.id = id;
        }

        public String getIdentifier() {
            return identifier;
        }

        public void setIdentifier(String identifier) {
            this.identifier = identifier;
        }

        public String getName() {
            return name;
        }

        public void setName(String name) {
            this.name = name;
        }

        public String getDisplay_name() {
            return display_name;
        }

        public void setDisplay_name(String display_name) {
            this.display_name = display_name;
        }

        public String getWeb() {
            return web;
        }

        public void setWeb(String web) {
            this.web = web;
        }

        public String getIp() {
            return ip;
        }

        public void setIp(String ip) {
            this.ip = ip;
        }

        public int getNeed_bank() {
            return need_bank;
        }

        public void setNeed_bank(int need_bank) {
            this.need_bank = need_bank;
        }

        public Object getCustomer_id() {
            return customer_id;
        }

        public void setCustomer_id(Object customer_id) {
            this.customer_id = customer_id;
        }

        public Object getCustomer_key() {
            return customer_key;
        }

        public void setCustomer_key(Object customer_key) {
            this.customer_key = customer_key;
        }

        public String getRelay_load_url() {
            return relay_load_url;
        }

        public void setRelay_load_url(String relay_load_url) {
            this.relay_load_url = relay_load_url;
        }

        public String getLoad_url() {
            return load_url;
        }

        public void setLoad_url(String load_url) {
            this.load_url = load_url;
        }

        public String getTest_load_url() {
            return test_load_url;
        }

        public void setTest_load_url(String test_load_url) {
            this.test_load_url = test_load_url;
        }

        public String getCharset() {
            return charset;
        }

        public void setCharset(String charset) {
            this.charset = charset;
        }

        public String getReturn_url() {
            return return_url;
        }

        public void setReturn_url(String return_url) {
            this.return_url = return_url;
        }

        public String getNotify_url() {
            return notify_url;
        }

        public void setNotify_url(String notify_url) {
            this.notify_url = notify_url;
        }

        public String getUnload_url() {
            return unload_url;
        }

        public void setUnload_url(String unload_url) {
            this.unload_url = unload_url;
        }

        public int getQuery_enabled() {
            return query_enabled;
        }

        public void setQuery_enabled(int query_enabled) {
            this.query_enabled = query_enabled;
        }

        public String getQuery_url() {
            return query_url;
        }

        public void setQuery_url(String query_url) {
            this.query_url = query_url;
        }

        public String getRelay_query_url() {
            return relay_query_url;
        }

        public void setRelay_query_url(String relay_query_url) {
            this.relay_query_url = relay_query_url;
        }

        public int getCheck_ip() {
            return check_ip;
        }

        public void setCheck_ip(int check_ip) {
            this.check_ip = check_ip;
        }

        public int getQuery_on_callback() {
            return query_on_callback;
        }

        public void setQuery_on_callback(int query_on_callback) {
            this.query_on_callback = query_on_callback;
        }

        public int getStatus() {
            return status;
        }

        public void setStatus(int status) {
            this.status = status;
        }

        public int getIs_default() {
            return is_default;
        }

        public void setIs_default(int is_default) {
            this.is_default = is_default;
        }

        public int getType() {
            return type;
        }

        public void setType(int type) {
            this.type = type;
        }

        public int getSequence() {
            return sequence;
        }

        public void setSequence(int sequence) {
            this.sequence = sequence;
        }

        public String getNotice() {
            return notice;
        }

        public void setNotice(String notice) {
            this.notice = notice;
        }

        public String getCreated_at() {
            return created_at;
        }

        public void setCreated_at(String created_at) {
            this.created_at = created_at;
        }

        public String getUpdated_at() {
            return updated_at;
        }

        public void setUpdated_at(String updated_at) {
            this.updated_at = updated_at;
        }

        public int getPayer_name_enabled() {
            return payer_name_enabled;
        }

        public void setPayer_name_enabled(int payer_name_enabled) {
            this.payer_name_enabled = payer_name_enabled;
        }

        public Object getEveryday_start_time() {
            return everyday_start_time;
        }

        public void setEveryday_start_time(Object everyday_start_time) {
            this.everyday_start_time = everyday_start_time;
        }

        public Object getEveryday_end_time() {
            return everyday_end_time;
        }

        public void setEveryday_end_time(Object everyday_end_time) {
            this.everyday_end_time = everyday_end_time;
        }

        public String getDeposit_max_amount() {
            return deposit_max_amount;
        }

        public void setDeposit_max_amount(String deposit_max_amount) {
            this.deposit_max_amount = deposit_max_amount;
        }

        public String getDeposit_min_amount() {
            return deposit_min_amount;
        }

        public void setDeposit_min_amount(String deposit_min_amount) {
            this.deposit_min_amount = deposit_min_amount;
        }

        public int getPay_type() {
            return pay_type;
        }

        public void setPay_type(int pay_type) {
            this.pay_type = pay_type;
        }

        public int getIs_show_qrcode_url() {
            return is_show_qrcode_url;
        }

        public void setIs_show_qrcode_url(int is_show_qrcode_url) {
            this.is_show_qrcode_url = is_show_qrcode_url;
        }

        public int getTeminal() {
            return teminal;
        }

        public void setTeminal(int teminal) {
            this.teminal = teminal;
        }

        public String getGrade() {
            return grade;
        }

        public void setGrade(String grade) {
            this.grade = grade;
        }

        public int getIcon_type() {
            return icon_type;
        }

        public void setIcon_type(int icon_type) {
            this.icon_type = icon_type;
        }

        public String getLink() {
            return link;
        }

        public void setLink(String link) {
            this.link = link;
        }

        public String getBrief_description() {
            return brief_description;
        }

        public void setBrief_description(String brief_description) {
            this.brief_description = brief_description;
        }

        public String getBrief_description_color() {
            return brief_description_color;
        }

        public void setBrief_description_color(String brief_description_color) {
            this.brief_description_color = brief_description_color;
        }
    }


}
