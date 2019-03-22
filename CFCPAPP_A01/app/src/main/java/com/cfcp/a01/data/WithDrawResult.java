package com.cfcp.a01.data;

import com.contrarywind.interfaces.IPickerViewData;
import com.google.gson.annotations.SerializedName;

import java.util.List;

public class WithDrawResult {

    /**
     * iBindedCardsNum : 2
     * bLocked : 0
     * aBankCards : [{"id":2476,"account":"666666666666666611","account_name":"打你哦","bank":"中国工商银行","bank_id":1},{"id":2481,"account":"66666666666666660000","account_name":"打你哦","bank":"中国银行","bank_id":4}]
     * aBanks : {"1":"ICBC","2":"CCB","3":"ABC","4":"BOC","5":"CMB","6":"BCOM","7":"CMBC","8":"ECITIC","9":"SPDB","10":"GDB","11":"PAB","12":"BEA","13":"CIB","14":"HXB","15":"CEBB","16":"PSBC","17":"","18":"","19":"","20":"CZB","21":"","22":"CBB","23":"","24":"","25":"","26":"","27":"","28":"","29":"","30":"","31":"","32":""}
     * iWithdrawLimitNum : 5
     * iMaxWithdrawAmount : 50000
     * iWithdrawalNum : 0
     * iMinWithdrawAmount : 100
     * aUserDepositGame : {"aDepositGame":[{"depositTime":"2019-03-09 10:17:38","depositAmount":1000000,"kaiyuanGameCellScore":0,"projectAmount":0,"betMoney":0,"gameMoney":0,"isEnough":0},{"depositTime":"2019-03-09 10:18:00","depositAmount":20000,"kaiyuanGameCellScore":0,"projectAmount":"106486.0000","betMoney":0,"gameMoney":106486,"isEnough":0}],"bEnough":false,"fNeedGameAmount":913514,"lastWithdrawalFinishTime":0}
     */

    private String iBindedCardsNum;
    private String bLocked;
    private ABanksBean aBanks;
    private String iWithdrawLimitNum;
    private String iMaxWithdrawAmount;
    private String iWithdrawalNum;
    private String iMinWithdrawAmount;
    private AUserDepositGameBean aUserDepositGame;
    private List<ABankCardsBean> aBankCards;

    public String getIBindedCardsNum() {
        return iBindedCardsNum;
    }

    public void setIBindedCardsNum(String iBindedCardsNum) {
        this.iBindedCardsNum = iBindedCardsNum;
    }

    public String getBLocked() {
        return bLocked;
    }

    public void setBLocked(String bLocked) {
        this.bLocked = bLocked;
    }

    public ABanksBean getABanks() {
        return aBanks;
    }

    public void setABanks(ABanksBean aBanks) {
        this.aBanks = aBanks;
    }

    public String getIWithdrawLimitNum() {
        return iWithdrawLimitNum;
    }

    public void setIWithdrawLimitNum(String iWithdrawLimitNum) {
        this.iWithdrawLimitNum = iWithdrawLimitNum;
    }

    public String getIMaxWithdrawAmount() {
        return iMaxWithdrawAmount;
    }

    public void setIMaxWithdrawAmount(String iMaxWithdrawAmount) {
        this.iMaxWithdrawAmount = iMaxWithdrawAmount;
    }

    public String getIWithdrawalNum() {
        return iWithdrawalNum;
    }

    public void setIWithdrawalNum(String iWithdrawalNum) {
        this.iWithdrawalNum = iWithdrawalNum;
    }

    public String getIMinWithdrawAmount() {
        return iMinWithdrawAmount;
    }

    public void setIMinWithdrawAmount(String iMinWithdrawAmount) {
        this.iMinWithdrawAmount = iMinWithdrawAmount;
    }

    public AUserDepositGameBean getAUserDepositGame() {
        return aUserDepositGame;
    }

    public void setAUserDepositGame(AUserDepositGameBean aUserDepositGame) {
        this.aUserDepositGame = aUserDepositGame;
    }

    public List<ABankCardsBean> getABankCards() {
        return aBankCards;
    }

    public void setABankCards(List<ABankCardsBean> aBankCards) {
        this.aBankCards = aBankCards;
    }

    public static class ABanksBean {
        /**
         * 1 : ICBC
         * 2 : CCB
         * 3 : ABC
         * 4 : BOC
         * 5 : CMB
         * 6 : BCOM
         * 7 : CMBC
         * 8 : ECITIC
         * 9 : SPDB
         * 10 : GDB
         * 11 : PAB
         * 12 : BEA
         * 13 : CIB
         * 14 : HXB
         * 15 : CEBB
         * 16 : PSBC
         * 17 :
         * 18 :
         * 19 :
         * 20 : CZB
         * 21 :
         * 22 : CBB
         * 23 :
         * 24 :
         * 25 :
         * 26 :
         * 27 :
         * 28 :
         * 29 :
         * 30 :
         * 31 :
         * 32 :
         */

        @SerializedName("1")
        private String _$1;
        @SerializedName("2")
        private String _$2;
        @SerializedName("3")
        private String _$3;
        @SerializedName("4")
        private String _$4;
        @SerializedName("5")
        private String _$5;
        @SerializedName("6")
        private String _$6;
        @SerializedName("7")
        private String _$7;
        @SerializedName("8")
        private String _$8;
        @SerializedName("9")
        private String _$9;
        @SerializedName("10")
        private String _$10;
        @SerializedName("11")
        private String _$11;
        @SerializedName("12")
        private String _$12;
        @SerializedName("13")
        private String _$13;
        @SerializedName("14")
        private String _$14;
        @SerializedName("15")
        private String _$15;
        @SerializedName("16")
        private String _$16;
        @SerializedName("17")
        private String _$17;
        @SerializedName("18")
        private String _$18;
        @SerializedName("19")
        private String _$19;
        @SerializedName("20")
        private String _$20;
        @SerializedName("21")
        private String _$21;
        @SerializedName("22")
        private String _$22;
        @SerializedName("23")
        private String _$23;
        @SerializedName("24")
        private String _$24;
        @SerializedName("25")
        private String _$25;
        @SerializedName("26")
        private String _$26;
        @SerializedName("27")
        private String _$27;
        @SerializedName("28")
        private String _$28;
        @SerializedName("29")
        private String _$29;
        @SerializedName("30")
        private String _$30;
        @SerializedName("31")
        private String _$31;
        @SerializedName("32")
        private String _$32;

        public String get_$1() {
            return _$1;
        }

        public void set_$1(String _$1) {
            this._$1 = _$1;
        }

        public String get_$2() {
            return _$2;
        }

        public void set_$2(String _$2) {
            this._$2 = _$2;
        }

        public String get_$3() {
            return _$3;
        }

        public void set_$3(String _$3) {
            this._$3 = _$3;
        }

        public String get_$4() {
            return _$4;
        }

        public void set_$4(String _$4) {
            this._$4 = _$4;
        }

        public String get_$5() {
            return _$5;
        }

        public void set_$5(String _$5) {
            this._$5 = _$5;
        }

        public String get_$6() {
            return _$6;
        }

        public void set_$6(String _$6) {
            this._$6 = _$6;
        }

        public String get_$7() {
            return _$7;
        }

        public void set_$7(String _$7) {
            this._$7 = _$7;
        }

        public String get_$8() {
            return _$8;
        }

        public void set_$8(String _$8) {
            this._$8 = _$8;
        }

        public String get_$9() {
            return _$9;
        }

        public void set_$9(String _$9) {
            this._$9 = _$9;
        }

        public String get_$10() {
            return _$10;
        }

        public void set_$10(String _$10) {
            this._$10 = _$10;
        }

        public String get_$11() {
            return _$11;
        }

        public void set_$11(String _$11) {
            this._$11 = _$11;
        }

        public String get_$12() {
            return _$12;
        }

        public void set_$12(String _$12) {
            this._$12 = _$12;
        }

        public String get_$13() {
            return _$13;
        }

        public void set_$13(String _$13) {
            this._$13 = _$13;
        }

        public String get_$14() {
            return _$14;
        }

        public void set_$14(String _$14) {
            this._$14 = _$14;
        }

        public String get_$15() {
            return _$15;
        }

        public void set_$15(String _$15) {
            this._$15 = _$15;
        }

        public String get_$16() {
            return _$16;
        }

        public void set_$16(String _$16) {
            this._$16 = _$16;
        }

        public String get_$17() {
            return _$17;
        }

        public void set_$17(String _$17) {
            this._$17 = _$17;
        }

        public String get_$18() {
            return _$18;
        }

        public void set_$18(String _$18) {
            this._$18 = _$18;
        }

        public String get_$19() {
            return _$19;
        }

        public void set_$19(String _$19) {
            this._$19 = _$19;
        }

        public String get_$20() {
            return _$20;
        }

        public void set_$20(String _$20) {
            this._$20 = _$20;
        }

        public String get_$21() {
            return _$21;
        }

        public void set_$21(String _$21) {
            this._$21 = _$21;
        }

        public String get_$22() {
            return _$22;
        }

        public void set_$22(String _$22) {
            this._$22 = _$22;
        }

        public String get_$23() {
            return _$23;
        }

        public void set_$23(String _$23) {
            this._$23 = _$23;
        }

        public String get_$24() {
            return _$24;
        }

        public void set_$24(String _$24) {
            this._$24 = _$24;
        }

        public String get_$25() {
            return _$25;
        }

        public void set_$25(String _$25) {
            this._$25 = _$25;
        }

        public String get_$26() {
            return _$26;
        }

        public void set_$26(String _$26) {
            this._$26 = _$26;
        }

        public String get_$27() {
            return _$27;
        }

        public void set_$27(String _$27) {
            this._$27 = _$27;
        }

        public String get_$28() {
            return _$28;
        }

        public void set_$28(String _$28) {
            this._$28 = _$28;
        }

        public String get_$29() {
            return _$29;
        }

        public void set_$29(String _$29) {
            this._$29 = _$29;
        }

        public String get_$30() {
            return _$30;
        }

        public void set_$30(String _$30) {
            this._$30 = _$30;
        }

        public String get_$31() {
            return _$31;
        }

        public void set_$31(String _$31) {
            this._$31 = _$31;
        }

        public String get_$32() {
            return _$32;
        }

        public void set_$32(String _$32) {
            this._$32 = _$32;
        }
    }

    public static class AUserDepositGameBean {
        /**
         * aDepositGame : [{"depositTime":"2019-03-09 10:17:38","depositAmount":1000000,"kaiyuanGameCellScore":0,"projectAmount":0,"betMoney":0,"gameMoney":0,"isEnough":0},{"depositTime":"2019-03-09 10:18:00","depositAmount":20000,"kaiyuanGameCellScore":0,"projectAmount":"106486.0000","betMoney":0,"gameMoney":106486,"isEnough":0}]
         * bEnough : false
         * fNeedGameAmount : 913514
         * lastWithdrawalFinishTime : 0
         */

        private boolean bEnough;
        private String fNeedGameAmount;
        private String lastWithdrawalFinishTime;
        private List<ADepositGameBean> aDepositGame;

        public boolean isBEnough() {
            return bEnough;
        }

        public void setBEnough(boolean bEnough) {
            this.bEnough = bEnough;
        }

        public String getFNeedGameAmount() {
            return fNeedGameAmount;
        }

        public void setFNeedGameAmount(String fNeedGameAmount) {
            this.fNeedGameAmount = fNeedGameAmount;
        }

        public String getLastWithdrawalFinishTime() {
            return lastWithdrawalFinishTime;
        }

        public void setLastWithdrawalFinishTime(String lastWithdrawalFinishTime) {
            this.lastWithdrawalFinishTime = lastWithdrawalFinishTime;
        }

        public List<ADepositGameBean> getADepositGame() {
            return aDepositGame;
        }

        public void setADepositGame(List<ADepositGameBean> aDepositGame) {
            this.aDepositGame = aDepositGame;
        }

        public static class ADepositGameBean {
            /**
             * depositTime : 2019-03-09 10:17:38
             * depositAmount : 1000000
             * kaiyuanGameCellScore : 0
             * projectAmount : 0
             * betMoney : 0
             * gameMoney : 0
             * isEnough : 0
             */

            private String depositTime;
            private String depositAmount;
            private String kaiyuanGameCellScore;
            private String projectAmount;
            private String betMoney;
            private String gameMoney;
            private String isEnough;

            public String getDepositTime() {
                return depositTime;
            }

            public void setDepositTime(String depositTime) {
                this.depositTime = depositTime;
            }

            public String getDepositAmount() {
                return depositAmount;
            }

            public void setDepositAmount(String depositAmount) {
                this.depositAmount = depositAmount;
            }

            public String getKaiyuanGameCellScore() {
                return kaiyuanGameCellScore;
            }

            public void setKaiyuanGameCellScore(String kaiyuanGameCellScore) {
                this.kaiyuanGameCellScore = kaiyuanGameCellScore;
            }

            public String getProjectAmount() {
                return projectAmount;
            }

            public void setProjectAmount(String projectAmount) {
                this.projectAmount = projectAmount;
            }

            public String getBetMoney() {
                return betMoney;
            }

            public void setBetMoney(String betMoney) {
                this.betMoney = betMoney;
            }

            public String getGameMoney() {
                return gameMoney;
            }

            public void setGameMoney(String gameMoney) {
                this.gameMoney = gameMoney;
            }

            public String getIsEnough() {
                return isEnough;
            }

            public void setIsEnough(String isEnough) {
                this.isEnough = isEnough;
            }
        }
    }

    public static class ABankCardsBean implements IPickerViewData {
        /**
         * id : 2476
         * account : 666666666666666611
         * account_name : 打你哦
         * bank : 中国工商银行
         * bank_id : 1
         */

        private String id;
        private String account;
        private String account_name;
        private String bank;
        private String bank_id;

        public String getId() {
            return id;
        }

        public void setId(String id) {
            this.id = id;
        }

        public String getAccount() {
            return account;
        }

        public void setAccount(String account) {
            this.account = account;
        }

        public String getAccount_name() {
            return account_name;
        }

        public void setAccount_name(String account_name) {
            this.account_name = account_name;
        }

        public String getBank() {
            return bank;
        }

        public void setBank(String bank) {
            this.bank = bank;
        }

        public String getBank_id() {
            return bank_id;
        }

        public void setBank_id(String bank_id) {
            this.bank_id = bank_id;
        }

        @Override
        public String getPickerViewText() {
            if(account_name.length()>=3){
                return this.bank+" 尾号："+this.account.substring(account.length()-4)+"[**"+this.account_name.substring(account_name.length()-1)+"]";
            }else{
                return this.bank+" 尾号："+this.account.substring(account.length()-4)+"[*"+this.account_name.substring(account_name.length()-1)+"]";
            }
        }
    }
}
