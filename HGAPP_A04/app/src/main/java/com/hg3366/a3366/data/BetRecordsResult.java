package com.hg3366.a3366.data;

import com.google.gson.annotations.SerializedName;

import java.util.List;

public class BetRecordsResult {

    /**
     * ThisWeek : {"1":{"datetime":1544389153,"dateformat":"2018-12-10","week":"星期一"},"2":{"datetime":1544475553,"dateformat":"2018-12-11","week":"星期二"},"3":{"datetime":1544561953,"dateformat":"2018-12-12","week":"星期三"},"4":{"datetime":1544648353,"dateformat":"2018-12-13","week":"星期四"},"5":{"datetime":1544734753,"dateformat":"2018-12-14","week":"星期五"},"6":{"datetime":1544821153,"dateformat":"2018-12-15","week":"星期六"},"7":{"datetime":1544907553,"dateformat":"2018-12-16","week":"星期日"}}
     * LastWeek : {"1":{"datetime":1543784353,"dateformat":"2018-12-03","week":"星期一"},"2":{"datetime":1543870753,"dateformat":"2018-12-04","week":"星期二"},"3":{"datetime":1543957153,"dateformat":"2018-12-05","week":"星期三"},"4":{"datetime":1544043553,"dateformat":"2018-12-06","week":"星期四"},"5":{"datetime":1544129953,"dateformat":"2018-12-07","week":"星期五"},"6":{"datetime":1544216353,"dateformat":"2018-12-08","week":"星期六"},"7":{"datetime":1544302753,"dateformat":"2018-12-09","week":"星期日"}}
     * TodayWeek : 1
     * row : [{"Allnum":"85","AllMoney":"455","AllWin":"-197.120","AllCut":"0.000","bet_time":"1544407630","date":"2018-12-09"},{"Allnum":"8","AllMoney":"8","AllWin":"-0.04800000000000004","AllCut":"0","bet_time":"1544415139"}]
     */

    private ThisWeekBean ThisWeek;
    private ThisWeekBean LastWeek;
    private String TodayWeek;
    private List<ThisWeekBean.RowBean> row;

    public ThisWeekBean getThisWeek() {
        return ThisWeek;
    }

    public void setThisWeek(ThisWeekBean ThisWeek) {
        this.ThisWeek = ThisWeek;
    }

    public ThisWeekBean getLastWeek() {
        return LastWeek;
    }

    public void setLastWeek(ThisWeekBean LastWeek) {
        this.LastWeek = LastWeek;
    }

    public String getTodayWeek() {
        return TodayWeek;
    }

    public void setTodayWeek(String TodayWeek) {
        this.TodayWeek = TodayWeek;
    }

    public List<ThisWeekBean.RowBean> getRow() {
        return row;
    }

    public void setRow(List<ThisWeekBean.RowBean> row) {
        this.row = row;
    }

    public static class ThisWeekBean {
        /**
         * 1 : {"datetime":1544389153,"dateformat":"2018-12-10","week":"星期一"}
         * 2 : {"datetime":1544475553,"dateformat":"2018-12-11","week":"星期二"}
         * 3 : {"datetime":1544561953,"dateformat":"2018-12-12","week":"星期三"}
         * 4 : {"datetime":1544648353,"dateformat":"2018-12-13","week":"星期四"}
         * 5 : {"datetime":1544734753,"dateformat":"2018-12-14","week":"星期五"}
         * 6 : {"datetime":1544821153,"dateformat":"2018-12-15","week":"星期六"}
         * 7 : {"datetime":1544907553,"dateformat":"2018-12-16","week":"星期日"}
         */

        @SerializedName("1")
        private data1Bean data1;
        @SerializedName("2")
        private data1Bean data2;
        @SerializedName("3")
        private data1Bean data3;
        @SerializedName("4")
        private data1Bean data4;
        @SerializedName("5")
        private data1Bean data5;
        @SerializedName("6")
        private data1Bean data6;
        @SerializedName("7")
        private data1Bean data7;

        public data1Bean getdata1() {
            return data1;
        }

        public void setdata1(data1Bean data1) {
            this.data1 = data1;
        }

        public data1Bean getdata2() {
            return data2;
        }

        public void setdata2(data1Bean data2) {
            this.data2 = data2;
        }

        public data1Bean getdata3() {
            return data3;
        }

        public void setdata3(data1Bean data3) {
            this.data3 = data3;
        }

        public data1Bean getdata4() {
            return data4;
        }

        public void setdata4(data1Bean data4) {
            this.data4 = data4;
        }

        public data1Bean getdata5() {
            return data5;
        }

        public void setdata5(data1Bean data5) {
            this.data5 = data5;
        }

        public data1Bean getdata6() {
            return data6;
        }

        public void setdata6(data1Bean data6) {
            this.data6 = data6;
        }

        public data1Bean getdata7() {
            return data7;
        }

        public void setdata7(data1Bean data7) {
            this.data7 = data7;
        }

        public static class data1Bean {
            /**
             * datetime : 1544389153
             * dateformat : 2018-12-10
             * week : 星期一
             */

            private int datetime;
            private String dateformat;
            private String week;
            private String money;

            public int getDatetime() {
                return datetime;
            }

            public void setDatetime(int datetime) {
                this.datetime = datetime;
            }

            public String getDateformat() {
                return dateformat;
            }

            public void setDateformat(String dateformat) {
                this.dateformat = dateformat;
            }

            public String getWeek() {
                return week;
            }

            public void setWeek(String week) {
                this.week = week;
            }

            public String getMoney() {
                return money;
            }

            public void setMoney(String money) {
                this.money = money;
            }
        }



        public static class RowBean {
            /**
             * Allnum : 85
             * AllMoney : 455
             * AllWin : -197.120
             * AllCut : 0.000
             * bet_time : 1544407630
             * date : 2018-12-09
             */

            private String Allnum;
            private String AllMoney;
            private String AllWin;
            private String AllCut;
            private String bet_time;
            private String date;

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

            public String getDate() {
                return date;
            }

            public void setDate(String date) {
                this.date = date;
            }
        }
    }
}
