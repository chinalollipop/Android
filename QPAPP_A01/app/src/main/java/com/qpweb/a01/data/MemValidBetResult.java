package com.qpweb.a01.data;

public class MemValidBetResult {
    /**
     * needToBet : 0
     */

    private double needToBet;
    /**
     * user_bank_account : {"Alias":"马克打野","Bank_Name":"中国银行","Bank_Account":"6222222222222222222","Bank_Address":"没空看"}
     */

    private UserBankAccountBean user_bank_account;

    public double getNeedToBet() {
        return needToBet;
    }

    public void setNeedToBet(double needToBet) {
        this.needToBet = needToBet;
    }

    public UserBankAccountBean getUser_bank_account() {
        return user_bank_account;
    }

    public void setUser_bank_account(UserBankAccountBean user_bank_account) {
        this.user_bank_account = user_bank_account;
    }


    public static class UserBankAccountBean {
        /**
         * Alias : 马克打野
         * Bank_Name : 中国银行
         * Bank_Account : 6222222222222222222
         * Bank_Address : 没空看
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
}
