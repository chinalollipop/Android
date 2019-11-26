package com.sands.corp.data;

import java.util.List;

public class SignTodayResults {

    /**
     * rows : [{"signdate":"2019-11-25","status":"0"},{"signdate":"2019-11-26","status":"0"},{"signdate":"2019-11-27","status":"0"},{"signdate":"2019-11-28","status":"0"},{"signdate":"2019-11-29","status":"0"},{"signdate":"2019-11-30","status":"0"},{"signdate":"2019-12-01","status":"0"}]
     * attendanceDay : [3,5,7]
     * standardmoney : 1001
     * maxstandardMoney : 888
     * curweekday : 0
     * lastweekday : 0
     */

    private String standardmoney;
    private String maxstandardMoney;
    private String curweekday;
    private int lastweekday;
    private List<RowsBean> rows;
    private List<Integer> attendanceDay;

    public String getStandardmoney() {
        return standardmoney;
    }

    public void setStandardmoney(String standardmoney) {
        this.standardmoney = standardmoney;
    }

    public String getMaxstandardMoney() {
        return maxstandardMoney;
    }

    public void setMaxstandardMoney(String maxstandardMoney) {
        this.maxstandardMoney = maxstandardMoney;
    }

    public String getCurweekday() {
        return curweekday;
    }

    public void setCurweekday(String curweekday) {
        this.curweekday = curweekday;
    }

    public int getLastweekday() {
        return lastweekday;
    }

    public void setLastweekday(int lastweekday) {
        this.lastweekday = lastweekday;
    }

    public List<RowsBean> getRows() {
        return rows;
    }

    public void setRows(List<RowsBean> rows) {
        this.rows = rows;
    }

    public List<Integer> getAttendanceDay() {
        return attendanceDay;
    }

    public void setAttendanceDay(List<Integer> attendanceDay) {
        this.attendanceDay = attendanceDay;
    }

    public static class RowsBean {
        /**
         * signdate : 2019-11-25
         * status : 0
         */

        private String signdate;
        private String status;

        public String getSigndate() {
            return signdate;
        }

        public void setSigndate(String signdate) {
            this.signdate = signdate;
        }

        public String getStatus() {
            return status;
        }

        public void setStatus(String status) {
            this.status = status;
        }
    }
}
