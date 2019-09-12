package com.venen.tian.data;

import java.util.List;

public class CPLotteryListResult {


    private List<DataBean> data;

    public List<DataBean> getData() {
        return data;
    }

    public void setData(List<DataBean> data) {
        this.data = data;
    }

    public static class DataBean {
        /**
         * openNum : 06,07,01,02,08,10,03,05,09,04
         * n1 : 06
         * n2 : 07
         * n3 : 01
         * n4 : 02
         * n5 : 08
         * n6 : 10
         * n7 : 03
         * n8 : 05
         * n9 : 09
         * n10 : 04
         * openTime : 2018-12-12 23:57:30
         * turnNum : 719725
         */

        private String openNum;
        private String n1;
        private String n2;
        private String n3;
        private String n4;
        private String n5;
        private String n6;
        private String n7;
        private String n8;
        private String n9;
        private String n10;
        private String openTime;
        private String turnNum;

        public String getOpenNum() {
            return openNum;
        }

        public void setOpenNum(String openNum) {
            this.openNum = openNum;
        }

        public String getN1() {
            return n1;
        }

        public void setN1(String n1) {
            this.n1 = n1;
        }

        public String getN2() {
            return n2;
        }

        public void setN2(String n2) {
            this.n2 = n2;
        }

        public String getN3() {
            return n3;
        }

        public void setN3(String n3) {
            this.n3 = n3;
        }

        public String getN4() {
            return n4;
        }

        public void setN4(String n4) {
            this.n4 = n4;
        }

        public String getN5() {
            return n5;
        }

        public void setN5(String n5) {
            this.n5 = n5;
        }

        public String getN6() {
            return n6;
        }

        public void setN6(String n6) {
            this.n6 = n6;
        }

        public String getN7() {
            return n7;
        }

        public void setN7(String n7) {
            this.n7 = n7;
        }

        public String getN8() {
            return n8;
        }

        public void setN8(String n8) {
            this.n8 = n8;
        }

        public String getN9() {
            return n9;
        }

        public void setN9(String n9) {
            this.n9 = n9;
        }

        public String getN10() {
            return n10;
        }

        public void setN10(String n10) {
            this.n10 = n10;
        }

        public String getOpenTime() {
            return openTime;
        }

        public void setOpenTime(String openTime) {
            this.openTime = openTime;
        }

        public String getTurnNum() {
            return turnNum;
        }

        public void setTurnNum(String turnNum) {
            this.turnNum = turnNum;
        }
    }
}
