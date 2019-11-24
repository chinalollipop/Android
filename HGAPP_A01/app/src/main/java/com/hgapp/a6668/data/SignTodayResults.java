package com.hgapp.a6668.data;

import java.util.List;

public class SignTodayResults {

    /**
     * rows : [{"signdate":"2019-11-18","status":"0"},{"signdate":"2019-11-19","status":"0"},{"signdate":"2019-11-20","status":"0"},{"signdate":"2019-11-21","status":"0"},{"signdate":"2019-11-22","status":"0"},{"signdate":"2019-11-23","status":"0"},{"signdate":"2019-11-24","status":"0"}]
     * curweekday : 0
     * lastweekday : 0
     */

    private int curweekday;
    private int lastweekday;
    private List<RowsBean> rows;

    public int getCurweekday() {
        return curweekday;
    }

    public void setCurweekday(int curweekday) {
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

    public static class RowsBean {
        /**
         * signdate : 2019-11-18
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
