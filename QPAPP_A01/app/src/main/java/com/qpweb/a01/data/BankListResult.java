package com.qpweb.a01.data;

import java.util.List;

public class BankListResult {

    /**
     * bank_list : [{"id":"1","bankname":"中国工商银行"},{"id":"2","bankname":"中国建设银行"},{"id":"3","bankname":"中国农业银行"},{"id":"4","bankname":"中国银行"},{"id":"5","bankname":"交通银行"},{"id":"6","bankname":"招商银行"},{"id":"7","bankname":"中国民生银行"},{"id":"8","bankname":"邮政储蓄银行"},{"id":"9","bankname":"中信银行"},{"id":"10","bankname":"光大银行"},{"id":"11","bankname":"浦发银行"},{"id":"12","bankname":"兴业银行"},{"id":"13","bankname":"华夏银行"},{"id":"14","bankname":"广发银行"},{"id":"15","bankname":"平安银行"},{"id":"16","bankname":"上海银行"},{"id":"17","bankname":"江苏银行"},{"id":"18","bankname":"安徽省农村信用社联合社"},{"id":"19","bankname":"鞍山市商业银行"},{"id":"20","bankname":"包商银行股份有限公司"},{"id":"21","bankname":"北京农村商业银行"},{"id":"22","bankname":"北京顺义银座村镇银行"},{"id":"23","bankname":"北京银行"},{"id":"24","bankname":"渤海银行"},{"id":"25","bankname":"沧州银行"},{"id":"26","bankname":"长安银行"},{"id":"27","bankname":"长沙银行"},{"id":"28","bankname":"常熟农村商业银行"},{"id":"29","bankname":"成都银行"},{"id":"30","bankname":"承德银行"},{"id":"31","bankname":"重庆农村商业银行"},{"id":"32","bankname":"重庆黔江银座村镇银行"},{"id":"33","bankname":"重庆银行股份有限公司"},{"id":"34","bankname":"重庆渝北银座村镇银行"},{"id":"35","bankname":"大连银行"},{"id":"36","bankname":"德阳银行"},{"id":"37","bankname":"德州银行"},{"id":"38","bankname":"东莞农村商业银行"},{"id":"39","bankname":"东莞银行"},{"id":"40","bankname":"东亚银行（中国）有限公司"},{"id":"41","bankname":"东营莱商村镇银行股份有限公司"},{"id":"42","bankname":"东营银行"},{"id":"43","bankname":"鄂尔多斯银行"},{"id":"44","bankname":"福建海峡银行"},{"id":"45","bankname":"福建省农村信用社"},{"id":"46","bankname":"阜新银行结算中心"},{"id":"47","bankname":"富滇银行"},{"id":"48","bankname":"赣州银行"},{"id":"49","bankname":"广东华兴银行"},{"id":"50","bankname":"广东南粤银行股份有限公司"},{"id":"51","bankname":"广东省农村信用社联合社"},{"id":"52","bankname":"广发银行股份有限公司"},{"id":"53","bankname":"广西北部湾银行"},{"id":"54","bankname":"广西农村信用社"},{"id":"55","bankname":"广州农村商业银行"},{"id":"56","bankname":"广州银行"},{"id":"57","bankname":"贵阳银行"},{"id":"58","bankname":"桂林银行股份有限公司"},{"id":"59","bankname":"哈尔滨银行结算中心"},{"id":"60","bankname":"海口联合农村商业银行"},{"id":"61","bankname":"海南省农村信用社"},{"id":"62","bankname":"邯郸市商业银行"},{"id":"63","bankname":"韩亚银行"},{"id":"64","bankname":"汉口银行"},{"id":"65","bankname":"杭州银行"},{"id":"66","bankname":"河北银行股份有限公司"},{"id":"67","bankname":"恒丰银行"},{"id":"68","bankname":"衡水银行"},{"id":"69","bankname":"湖北农信"},{"id":"70","bankname":"湖北银行"},{"id":"71","bankname":"湖州银行"},{"id":"72","bankname":"葫芦岛银行"},{"id":"73","bankname":"黄河农村商业银行"},{"id":"74","bankname":"徽商银行"},{"id":"75","bankname":"吉林农村信用社"},{"id":"76","bankname":"吉林银行"},{"id":"77","bankname":"济宁银行"},{"id":"78","bankname":"嘉兴银行清算中心"},{"id":"79","bankname":"江苏长江商行"},{"id":"80","bankname":"江苏省农村信用社联合社"},{"id":"81","bankname":"江苏银行股份有限公司"},{"id":"82","bankname":"江西赣州银座村镇银行"},{"id":"83","bankname":"江阴农商银行"},{"id":"84","bankname":"锦州银行"},{"id":"85","bankname":"晋城银行"},{"id":"86","bankname":"晋商银行网上银行"},{"id":"87","bankname":"九江银行股份有限公司"},{"id":"88","bankname":"深圳前海微众银行"},{"id":"124","bankname":"盛京银行"},{"id":"125","bankname":"顺德农村商业银行"},{"id":"126","bankname":"四川省联社"},{"id":"127","bankname":"苏州银行"},{"id":"128","bankname":"厦门国际银行"},{"id":"129","bankname":"厦门银行"},{"id":"130","bankname":"台州银行"},{"id":"131","bankname":"太仓农商行"},{"id":"132","bankname":"泰安市商业银行"},{"id":"133","bankname":"天津滨海农村商业银行股份有限公司"},{"id":"134","bankname":"天津农商银行"},{"id":"135","bankname":"天津银行"},{"id":"136","bankname":"威海市商业银行"},{"id":"137","bankname":"潍坊银行"},{"id":"138","bankname":"温州银行"},{"id":"139","bankname":"乌鲁木齐市商业银行"},{"id":"140","bankname":"吴江农村商业银行"},{"id":"141","bankname":"武汉农村商业银行"},{"id":"142","bankname":"西安银行"},{"id":"143","bankname":"新韩银行中国"},{"id":"144","bankname":"邢台银行"},{"id":"145","bankname":"烟台银行"},{"id":"146","bankname":"鄞州银行"},{"id":"147","bankname":"营口银行"},{"id":"148","bankname":"友利银行"},{"id":"149","bankname":"云南省农村信用社"},{"id":"150","bankname":"枣庄银行"},{"id":"151","bankname":"张家港农村商业银行"},{"id":"152","bankname":"张家口银行股份有限公司"},{"id":"153","bankname":"浙江稠州商业银行"},{"id":"154","bankname":"浙江景宁银座村镇银行"},{"id":"155","bankname":"浙江民泰商业银行"},{"id":"156","bankname":"浙江三门银座村镇银行"},{"id":"157","bankname":"浙江省农村信用社"},{"id":"158","bankname":"浙江泰隆商业银行"},{"id":"159","bankname":"浙商银行"},{"id":"160","bankname":"郑州银行"},{"id":"161","bankname":"中原银行"},{"id":"162","bankname":"珠海华润银行清算中心"},{"id":"163","bankname":"自贡市商业银行清算中心"}]
     * user_bank_account : {"Alias":"6494","Bank_Name":"交通银行","Bank_Account":"6494486494848454545","Bank_Address":"64946494"}
     */

    private UserBankAccountBean user_bank_account;
    private List<BankListBean> bank_list;

    public UserBankAccountBean getUser_bank_account() {
        return user_bank_account;
    }

    public void setUser_bank_account(UserBankAccountBean user_bank_account) {
        this.user_bank_account = user_bank_account;
    }

    public List<BankListBean> getBank_list() {
        return bank_list;
    }

    public void setBank_list(List<BankListBean> bank_list) {
        this.bank_list = bank_list;
    }

    public static class UserBankAccountBean {
        /**
         * Alias : 6494
         * Bank_Name : 交通银行
         * Bank_Account : 6494486494848454545
         * Bank_Address : 64946494
         */

        private String Alias;
        private String Bank_Name;
        private String Bank_Account;
        private String Bank_Address;

        public String getAlias() {
            return Alias;
        }

        public void setAlias(String Alias) {
            this.Alias = Alias;
        }

        public String getBank_Name() {
            return Bank_Name;
        }

        public void setBank_Name(String Bank_Name) {
            this.Bank_Name = Bank_Name;
        }

        public String getBank_Account() {
            return Bank_Account;
        }

        public void setBank_Account(String Bank_Account) {
            this.Bank_Account = Bank_Account;
        }

        public String getBank_Address() {
            return Bank_Address;
        }

        public void setBank_Address(String Bank_Address) {
            this.Bank_Address = Bank_Address;
        }
    }

    public static class BankListBean {
        /**
         * id : 1
         * bankname : 中国工商银行
         */

        private String id;
        private String bankname;

        public String getId() {
            return id;
        }

        public void setId(String id) {
            this.id = id;
        }

        public String getBankname() {
            return bankname;
        }

        public void setBankname(String bankname) {
            this.bankname = bankname;
        }
    }
}
