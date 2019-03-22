package com.cfcp.a01.data;

import com.contrarywind.interfaces.IPickerViewData;

import java.util.List;

public class BankListResult {

    private List<ABanksBean> aBanks;

    public List<ABanksBean> getABanks() {
        return aBanks;
    }

    public void setABanks(List<ABanksBean> aBanks) {
        this.aBanks = aBanks;
    }


    public static class ABanksBean implements IPickerViewData {
        /**
         * id : 1
         * serial_number : 1
         * name : 中国工商银行
         * identifier : ICBC
         * mode : 2
         * card_type : Collection Card
         * code_length :
         * min_load : 100.00
         * max_load : 45000.00
         * url : https://mybank.icbc.com.cn/icbc/perbank/index.jsp
         * help_url :
         * logo :
         * status : 1
         * sequence : 210
         * notice : 工商银行：当实际充值金额≥300时，平台根据用户实际消耗的手续费进行返送；
         * deposit_notice :
         * fee_valve : 100.00
         * fee_expressions : x>=100&&x<1000&&y=x*0.1/100;x>=1000&&x<2000&&y=1;x>=2000&&x<2100&&y=x*0.1/100;x>=2100&&x<3211&&y=121
         * fee_switch : 1
         * created_at : null
         * updated_at : 2015-05-17 14:52:29
         */

        private int id;
        private int serial_number;
        private String name;
        private String identifier;
        private int mode;
        private String card_type;
        private String code_length;
        private String min_load;
        private String max_load;
        private String url;
        private String help_url;
        private String logo;
        private int status;
        private int sequence;
        private String notice;
        private String deposit_notice;
        private String fee_valve;
        private String fee_expressions;
        private int fee_switch;
        private Object created_at;
        private String updated_at;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public int getSerial_number() {
            return serial_number;
        }

        public void setSerial_number(int serial_number) {
            this.serial_number = serial_number;
        }

        public String getName() {
            return name;
        }

        public void setName(String name) {
            this.name = name;
        }

        public String getIdentifier() {
            return identifier;
        }

        public void setIdentifier(String identifier) {
            this.identifier = identifier;
        }

        public int getMode() {
            return mode;
        }

        public void setMode(int mode) {
            this.mode = mode;
        }

        public String getCard_type() {
            return card_type;
        }

        public void setCard_type(String card_type) {
            this.card_type = card_type;
        }

        public String getCode_length() {
            return code_length;
        }

        public void setCode_length(String code_length) {
            this.code_length = code_length;
        }

        public String getMin_load() {
            return min_load;
        }

        public void setMin_load(String min_load) {
            this.min_load = min_load;
        }

        public String getMax_load() {
            return max_load;
        }

        public void setMax_load(String max_load) {
            this.max_load = max_load;
        }

        public String getUrl() {
            return url;
        }

        public void setUrl(String url) {
            this.url = url;
        }

        public String getHelp_url() {
            return help_url;
        }

        public void setHelp_url(String help_url) {
            this.help_url = help_url;
        }

        public String getLogo() {
            return logo;
        }

        public void setLogo(String logo) {
            this.logo = logo;
        }

        public int getStatus() {
            return status;
        }

        public void setStatus(int status) {
            this.status = status;
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

        public String getDeposit_notice() {
            return deposit_notice;
        }

        public void setDeposit_notice(String deposit_notice) {
            this.deposit_notice = deposit_notice;
        }

        public String getFee_valve() {
            return fee_valve;
        }

        public void setFee_valve(String fee_valve) {
            this.fee_valve = fee_valve;
        }

        public String getFee_expressions() {
            return fee_expressions;
        }

        public void setFee_expressions(String fee_expressions) {
            this.fee_expressions = fee_expressions;
        }

        public int getFee_switch() {
            return fee_switch;
        }

        public void setFee_switch(int fee_switch) {
            this.fee_switch = fee_switch;
        }

        public Object getCreated_at() {
            return created_at;
        }

        public void setCreated_at(Object created_at) {
            this.created_at = created_at;
        }

        public String getUpdated_at() {
            return updated_at;
        }

        public void setUpdated_at(String updated_at) {
            this.updated_at = updated_at;
        }

        @Override
        public String getPickerViewText() {
            return this.name;
        }
    }


}
