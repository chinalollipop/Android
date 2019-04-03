package com.cfcp.a01.data;

import android.os.Parcel;
import android.os.Parcelable;


public class DepositTypeResult implements Parcelable {


    /**
     * oPaymentPlatformBankCard : {"orderColumns":{"status":"asc","platform_id":"asc"},"moved":false,"columnSettings":[],"columnTypes":[],"errno":null,"error":null,"validationErrors":{},"throwOnValidation":false,"autoHydrateEntityFromInput":false,"forceEntityHydrationFromInput":false,"autoPurgeRedundantAttributes":false,"autoHashPasswordAttributes":false,"incrementing":true,"timestamps":true,"exists":true}
     * aAllBanks : []
     * sAllBanksJs : []
     * checkUserBankCard : false
     * bSetFundPassword : true
     * iPlatformId : 70
     * aPlatform : {"id":70,"identifier":"gongsi bank","name":"西安松琳通信息科技有限公司-宋军","display_name":"银行卡入款","web":"","ip":"","need_bank":0,"customer_id":null,"customer_key":null,"relay_load_url":"test","load_url":"","test_load_url":"","charset":"","return_url":"test","notify_url":"test","unload_url":"","query_enabled":0,"query_url":"","relay_query_url":"test","check_ip":0,"query_on_callback":0,"status":3,"is_default":0,"type":1,"sequence":5,"notice":"<p><span style=\"font-size: 10px; color: rgb(255, 0, 0);\"><\/span><\/p><p><span style=\"font-size: 10px; color: rgb(255, 0, 0);\"><\/span><\/p><p><span style=\"color: rgb(255, 0, 0); font-size: 10px;\">1、平台收款账号不定期更换，请入款后勿保存账号，若转入已停用账号彩易博概不负责.谢谢！<\/span><\/p><p><span style=\"color: rgb(255, 0, 0); font-size: 10px;\">2、填写金额应当与汇款金额一致，否则充值将无法到账。<\/span><\/p><p><span style=\"color: rgb(255, 0, 0); font-size: 10px;\">3、支付成功后，请点击（我已支付）。如无按照步骤是不会到账的哦！<\/span><\/p>","created_at":"2018-05-08 09:07:12","updated_at":"2018-11-09 16:06:18","payer_name_enabled":1,"everyday_start_time":null,"everyday_end_time":null,"deposit_max_amount":"1000000.00","deposit_min_amount":"10.00","pay_type":0,"is_show_qrcode_url":0,"teminal":0,"grade":"1","icon_type":0,"brief_description":"支持多家银行，转账更便捷","brief_description_color":"#fa0f0f"}
     */

    public String iPlatformId;
    public APlatformBean aPlatform;
    /**
     * aPaymentPlatformBankCard : {"id":25,"platform_name":"西安松琳通信息科技有限公司-宋军","platform_id":70,"platform_identifier":"gongsi bank","bank_card_id":12,"bank_id":3,"bank":"中国农业银行","account_no":"26127101040008389","owner":"西安松琳通信息科技有限公司","email":"左翠华","status":1,"creator_id":68,"creator":"paigu","editor_id":68,"editor":"paigu","created_at":"2018-08-17 16:25:37","updated_at":"2018-09-17 12:49:51","branch":"西安科技二路支行"}
     */

    private APaymentPlatformBankCardBean aPaymentPlatformBankCard;

    public String getiPlatformId() {
        return iPlatformId;
    }

    public void setiPlatformId(String iPlatformId) {
        this.iPlatformId = iPlatformId;
    }

    public APlatformBean getaPlatform() {
        return aPlatform;
    }

    public void setaPlatform(APlatformBean aPlatform) {
        this.aPlatform = aPlatform;
    }

    public APaymentPlatformBankCardBean getAPaymentPlatformBankCard() {
        return aPaymentPlatformBankCard;
    }

    public void setAPaymentPlatformBankCard(APaymentPlatformBankCardBean aPaymentPlatformBankCard) {
        this.aPaymentPlatformBankCard = aPaymentPlatformBankCard;
    }

    public static class APlatformBean implements Parcelable {

        /**
         * id : 70
         * identifier : gongsi bank
         * name : 西安松琳通信息科技有限公司-宋军
         * display_name : 银行卡入款
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
         * sequence : 5
         * notice : <p><span style="font-size: 10px; color: rgb(255, 0, 0);"></span></p><p><span style="font-size: 10px; color: rgb(255, 0, 0);"></span></p><p><span style="color: rgb(255, 0, 0); font-size: 10px;">1、平台收款账号不定期更换，请入款后勿保存账号，若转入已停用账号彩易博概不负责.谢谢！</span></p><p><span style="color: rgb(255, 0, 0); font-size: 10px;">2、填写金额应当与汇款金额一致，否则充值将无法到账。</span></p><p><span style="color: rgb(255, 0, 0); font-size: 10px;">3、支付成功后，请点击（我已支付）。如无按照步骤是不会到账的哦！</span></p>
         * created_at : 2018-05-08 09:07:12
         * updated_at : 2018-11-09 16:06:18
         * payer_name_enabled : 1
         * everyday_start_time : null
         * everyday_end_time : null
         * deposit_max_amount : 1000000.00
         * deposit_min_amount : 10.00
         * pay_type : 0
         * is_show_qrcode_url : 0
         * teminal : 0
         * grade : 1
         * icon_type : 0
         * brief_description : 支持多家银行，转账更便捷
         * brief_description_color : #fa0f0f
         */

        private int id;
        private String identifier;
        private String name;
        private String display_name;
        private String web;
        private String ip;
        private int need_bank;
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
        private String deposit_max_amount;
        private String deposit_min_amount;
        private int pay_type;
        private int is_show_qrcode_url;
        private int teminal;
        private String grade;
        private int icon_type;
        private String brief_description;
        private String brief_description_color;

        public int getId() {
            return id;
        }

        public void setId(int id) {
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

        @Override
        public int describeContents() {
            return 0;
        }

        @Override
        public void writeToParcel(Parcel dest, int flags) {
            dest.writeInt(this.id);
            dest.writeString(this.identifier);
            dest.writeString(this.name);
            dest.writeString(this.display_name);
            dest.writeString(this.web);
            dest.writeString(this.ip);
            dest.writeInt(this.need_bank);
            dest.writeString(this.relay_load_url);
            dest.writeString(this.load_url);
            dest.writeString(this.test_load_url);
            dest.writeString(this.charset);
            dest.writeString(this.return_url);
            dest.writeString(this.notify_url);
            dest.writeString(this.unload_url);
            dest.writeInt(this.query_enabled);
            dest.writeString(this.query_url);
            dest.writeString(this.relay_query_url);
            dest.writeInt(this.check_ip);
            dest.writeInt(this.query_on_callback);
            dest.writeInt(this.status);
            dest.writeInt(this.is_default);
            dest.writeInt(this.type);
            dest.writeInt(this.sequence);
            dest.writeString(this.notice);
            dest.writeString(this.created_at);
            dest.writeString(this.updated_at);
            dest.writeInt(this.payer_name_enabled);
            dest.writeString(this.deposit_max_amount);
            dest.writeString(this.deposit_min_amount);
            dest.writeInt(this.pay_type);
            dest.writeInt(this.is_show_qrcode_url);
            dest.writeInt(this.teminal);
            dest.writeString(this.grade);
            dest.writeInt(this.icon_type);
            dest.writeString(this.brief_description);
            dest.writeString(this.brief_description_color);
        }

        public APlatformBean() {
        }

        protected APlatformBean(Parcel in) {
            this.id = in.readInt();
            this.identifier = in.readString();
            this.name = in.readString();
            this.display_name = in.readString();
            this.web = in.readString();
            this.ip = in.readString();
            this.need_bank = in.readInt();
            this.relay_load_url = in.readString();
            this.load_url = in.readString();
            this.test_load_url = in.readString();
            this.charset = in.readString();
            this.return_url = in.readString();
            this.notify_url = in.readString();
            this.unload_url = in.readString();
            this.query_enabled = in.readInt();
            this.query_url = in.readString();
            this.relay_query_url = in.readString();
            this.check_ip = in.readInt();
            this.query_on_callback = in.readInt();
            this.status = in.readInt();
            this.is_default = in.readInt();
            this.type = in.readInt();
            this.sequence = in.readInt();
            this.notice = in.readString();
            this.created_at = in.readString();
            this.updated_at = in.readString();
            this.payer_name_enabled = in.readInt();
            this.deposit_max_amount = in.readString();
            this.deposit_min_amount = in.readString();
            this.pay_type = in.readInt();
            this.is_show_qrcode_url = in.readInt();
            this.teminal = in.readInt();
            this.grade = in.readString();
            this.icon_type = in.readInt();
            this.brief_description = in.readString();
            this.brief_description_color = in.readString();
        }

        public static final Creator<APlatformBean> CREATOR = new Creator<APlatformBean>() {
            @Override
            public APlatformBean createFromParcel(Parcel source) {
                return new APlatformBean(source);
            }

            @Override
            public APlatformBean[] newArray(int size) {
                return new APlatformBean[size];
            }
        };
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.iPlatformId);
        dest.writeParcelable(this.aPlatform, flags);
        dest.writeParcelable(this.aPaymentPlatformBankCard, flags);
    }

    public DepositTypeResult() {
    }

    protected DepositTypeResult(Parcel in) {
        this.iPlatformId = in.readString();
        this.aPlatform = in.readParcelable(APlatformBean.class.getClassLoader());
        this.aPaymentPlatformBankCard = in.readParcelable(APaymentPlatformBankCardBean.class.getClassLoader());
    }

    public static final Creator<DepositTypeResult> CREATOR = new Creator<DepositTypeResult>() {
        @Override
        public DepositTypeResult createFromParcel(Parcel source) {
            return new DepositTypeResult(source);
        }

        @Override
        public DepositTypeResult[] newArray(int size) {
            return new DepositTypeResult[size];
        }
    };

    public static class APaymentPlatformBankCardBean implements Parcelable {
        /**
         * id : 25
         * platform_name : 西安松琳通信息科技有限公司-宋军
         * platform_id : 70
         * platform_identifier : gongsi bank
         * bank_card_id : 12
         * bank_id : 3
         * bank : 中国农业银行
         * account_no : 26127101040008389
         * owner : 西安松琳通信息科技有限公司
         * email : 左翠华
         * status : 1
         * creator_id : 68
         * creator : paigu
         * editor_id : 68
         * editor : paigu
         * created_at : 2018-08-17 16:25:37
         * updated_at : 2018-09-17 12:49:51
         * branch : 西安科技二路支行
         */

        private int id;
        private String platform_name;
        private int platform_id;
        private String platform_identifier;
        private int bank_card_id;
        private int bank_id;
        private String bank;
        private String account_no;
        private String owner;
        private String email;
        private int status;
        private int creator_id;
        private String creator;
        private int editor_id;
        private String editor;
        private String created_at;
        private String updated_at;
        private String branch;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public String getPlatform_name() {
            return platform_name;
        }

        public void setPlatform_name(String platform_name) {
            this.platform_name = platform_name;
        }

        public int getPlatform_id() {
            return platform_id;
        }

        public void setPlatform_id(int platform_id) {
            this.platform_id = platform_id;
        }

        public String getPlatform_identifier() {
            return platform_identifier;
        }

        public void setPlatform_identifier(String platform_identifier) {
            this.platform_identifier = platform_identifier;
        }

        public int getBank_card_id() {
            return bank_card_id;
        }

        public void setBank_card_id(int bank_card_id) {
            this.bank_card_id = bank_card_id;
        }

        public int getBank_id() {
            return bank_id;
        }

        public void setBank_id(int bank_id) {
            this.bank_id = bank_id;
        }

        public String getBank() {
            return bank;
        }

        public void setBank(String bank) {
            this.bank = bank;
        }

        public String getAccount_no() {
            return account_no;
        }

        public void setAccount_no(String account_no) {
            this.account_no = account_no;
        }

        public String getOwner() {
            return owner;
        }

        public void setOwner(String owner) {
            this.owner = owner;
        }

        public String getEmail() {
            return email;
        }

        public void setEmail(String email) {
            this.email = email;
        }

        public int getStatus() {
            return status;
        }

        public void setStatus(int status) {
            this.status = status;
        }

        public int getCreator_id() {
            return creator_id;
        }

        public void setCreator_id(int creator_id) {
            this.creator_id = creator_id;
        }

        public String getCreator() {
            return creator;
        }

        public void setCreator(String creator) {
            this.creator = creator;
        }

        public int getEditor_id() {
            return editor_id;
        }

        public void setEditor_id(int editor_id) {
            this.editor_id = editor_id;
        }

        public String getEditor() {
            return editor;
        }

        public void setEditor(String editor) {
            this.editor = editor;
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

        public String getBranch() {
            return branch;
        }

        public void setBranch(String branch) {
            this.branch = branch;
        }

        @Override
        public int describeContents() {
            return 0;
        }

        @Override
        public void writeToParcel(Parcel dest, int flags) {
            dest.writeInt(this.id);
            dest.writeString(this.platform_name);
            dest.writeInt(this.platform_id);
            dest.writeString(this.platform_identifier);
            dest.writeInt(this.bank_card_id);
            dest.writeInt(this.bank_id);
            dest.writeString(this.bank);
            dest.writeString(this.account_no);
            dest.writeString(this.owner);
            dest.writeString(this.email);
            dest.writeInt(this.status);
            dest.writeInt(this.creator_id);
            dest.writeString(this.creator);
            dest.writeInt(this.editor_id);
            dest.writeString(this.editor);
            dest.writeString(this.created_at);
            dest.writeString(this.updated_at);
            dest.writeString(this.branch);
        }

        public APaymentPlatformBankCardBean() {
        }

        protected APaymentPlatformBankCardBean(Parcel in) {
            this.id = in.readInt();
            this.platform_name = in.readString();
            this.platform_id = in.readInt();
            this.platform_identifier = in.readString();
            this.bank_card_id = in.readInt();
            this.bank_id = in.readInt();
            this.bank = in.readString();
            this.account_no = in.readString();
            this.owner = in.readString();
            this.email = in.readString();
            this.status = in.readInt();
            this.creator_id = in.readInt();
            this.creator = in.readString();
            this.editor_id = in.readInt();
            this.editor = in.readString();
            this.created_at = in.readString();
            this.updated_at = in.readString();
            this.branch = in.readString();
        }

        public static final Creator<APaymentPlatformBankCardBean> CREATOR = new Creator<APaymentPlatformBankCardBean>() {
            @Override
            public APaymentPlatformBankCardBean createFromParcel(Parcel source) {
                return new APaymentPlatformBankCardBean(source);
            }

            @Override
            public APaymentPlatformBankCardBean[] newArray(int size) {
                return new APaymentPlatformBankCardBean[size];
            }
        };
    }
}
