package com.nhg.xhg.homepage.cplist.bet.betrecords;

import java.util.List;

public class BetRecordsList {
     String recordsname;
     List<dataBean> arrayListData;

    public String getRecordsname() {
        return recordsname;
    }

    public void setRecordsname(String recordsname) {
        this.recordsname = recordsname;
    }

    public List<dataBean> getArrayListData() {
        return arrayListData;
    }

    public void setArrayListData(List<dataBean> arrayListData) {
        this.arrayListData = arrayListData;
    }

    public static class dataBean {


        /**
         * Allnum : 8                       数量
         * AllMoney : 8                     金额
         * AllWin : -0.04800000000000004    输赢
         * AllCut : 0
         * bet_time : 1544415139            时间
         */

        private String Allnum;
        private String AllMoney;
        private String AllWin;
        private String AllCut;
        private String bet_time;
        private String time;

        public String getAllnum() {
            return Allnum;
        }

        public void setAllnum(String Allnum) {
            this.Allnum = Allnum;
        }

        public String getAllMoney() {
            return AllMoney;
        }

        public void setAllMoney(String AllMoney) {
            this.AllMoney = AllMoney;
        }

        public String getAllWin() {
            return AllWin;
        }

        public void setAllWin(String AllWin) {
            this.AllWin = AllWin;
        }

        public String getAllCut() {
            return AllCut;
        }

        public void setAllCut(String AllCut) {
            this.AllCut = AllCut;
        }

        public String getBet_time() {
            return bet_time;
        }

        public void setBet_time(String bet_time) {
            this.bet_time = bet_time;
        }

        public String getTime() {
            return time;
        }

        public void setTime(String time) {
            this.time = time;
        }
    }

}
