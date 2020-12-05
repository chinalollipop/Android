package com.hgapp.a0086.data;

import java.util.List;

public class FlowingRecordResult {
    /**
     * total : 6
     * num_per_page : 10
     * currentpage : 0
     * page_count : 1
     * perpage : 6
     * rows : [{"zt":"[进球取消]","LineType":"10","BetTime":"2018-05-05 13:07:41","orderNo":"ROUHAO805053153310056545","Middle":"芬兰乙组联赛","BetScore":"20.00","M_Result":"0.00"},{"zt":"[进球取消]","LineType":"21","BetTime":"2018-05-05 13:00:58","orderNo":"NOOA805054982997509910","Middle":"芬兰乙组联赛","BetScore":"20.00","M_Result":"0.00"}]
     */

    private int total;
    private int num_per_page;
    private int currentpage;
    private int page_count;
    private int perpage;
    private List<RowsBean> rows;

    public int getTotal() {
        return total;
    }

    public void setTotal(int total) {
        this.total = total;
    }

    public int getNum_per_page() {
        return num_per_page;
    }

    public void setNum_per_page(int num_per_page) {
        this.num_per_page = num_per_page;
    }

    public int getCurrentpage() {
        return currentpage;
    }

    public void setCurrentpage(int currentpage) {
        this.currentpage = currentpage;
    }

    public int getPage_count() {
        return page_count;
    }

    public void setPage_count(int page_count) {
        this.page_count = page_count;
    }

    public int getPerpage() {
        return perpage;
    }

    public void setPerpage(int perpage) {
        this.perpage = perpage;
    }

    public List<RowsBean> getRows() {
        return rows;
    }

    public void setRows(List<RowsBean> rows) {
        this.rows = rows;
    }

    public static class RowsBean {
        /**
         * zt : [进球取消]
         * LineType : 10
         * BetTime : 2018-05-05 13:07:41
         * orderNo : ROUHAO805053153310056545
         * Middle : 芬兰乙组联赛
         * BetScore : 20.00
         * M_Result : 0.00
         */

        private String zt;
        private String LineType;
        private String BetTime;
        private String orderNo;
        private String Middle;
        private String BetScore;
        private String M_Result;

        public String getZt() {
            return zt;
        }

        public void setZt(String zt) {
            this.zt = zt;
        }

        public String getLineType() {
            return LineType;
        }

        public void setLineType(String LineType) {
            this.LineType = LineType;
        }

        public String getBetTime() {
            return BetTime;
        }

        public void setBetTime(String BetTime) {
            this.BetTime = BetTime;
        }

        public String getOrderNo() {
            return orderNo;
        }

        public void setOrderNo(String orderNo) {
            this.orderNo = orderNo;
        }

        public String getMiddle() {
            return Middle;
        }

        public void setMiddle(String Middle) {
            this.Middle = Middle;
        }

        public String getBetScore() {
            return BetScore;
        }

        public void setBetScore(String BetScore) {
            this.BetScore = BetScore;
        }

        public String getM_Result() {
            return M_Result;
        }

        public void setM_Result(String M_Result) {
            this.M_Result = M_Result;
        }
    }
}
