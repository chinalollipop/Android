package com.hfcp.hf.data;

import com.hfcp.hf.common.utils.Check;

import java.util.List;

public class MyReportResult {
    /**
     * aTransactions : [{"id":4111460,"serial_number":"54925C8B3D841361C9.57092475","type_id":11,"description":"派发奖金","is_income":1,"trace_id":598,"lottery_id":1,"issue":"190315029","way_id":78,"coefficient":"0.050","project_id":419806,"amount":"0.975000","ablance":"1016206.140000","note":"","created_at":"2019-03-15 13:52:04"},{"id":4111458,"serial_number":"54925C8B389D483961.63080815","type_id":15,"description":"追号返款","is_income":1,"trace_id":598,"lottery_id":1,"issue":null,"way_id":78,"coefficient":"0.050","project_id":null,"amount":"12.000000","ablance":"1016205.165000","note":"","created_at":"2019-03-15 13:31:09"},{"id":4111457,"serial_number":"54925C8B389D2084D6.99523091","type_id":11,"description":"派发奖金","is_income":1,"trace_id":598,"lottery_id":1,"issue":"190315028","way_id":78,"coefficient":"0.050","project_id":419805,"amount":"1.950000","ablance":"1016193.165000","note":"","created_at":"2019-03-15 13:31:09"},{"id":4111456,"serial_number":"54925C8B38987296A5.37054177","type_id":7,"description":"加入游戏","is_income":0,"trace_id":598,"lottery_id":1,"issue":"190315029","way_id":78,"coefficient":"0.050","project_id":419806,"amount":"1.500000","ablance":"1016191.215000","note":"","created_at":"2019-03-15 13:31:04"},{"id":4111455,"serial_number":"54925C8B38986C1020.49793172","type_id":6,"description":"投注解冻","is_income":1,"trace_id":598,"lottery_id":1,"issue":"190315029","way_id":78,"coefficient":"0.050","project_id":null,"amount":"1.500000","ablance":"1016192.715000","note":"","created_at":"2019-03-15 13:31:04"},{"id":4111454,"serial_number":"54925C8B3897AD8396.59014539","type_id":7,"description":"加入游戏","is_income":0,"trace_id":598,"lottery_id":1,"issue":"190315028","way_id":78,"coefficient":"0.050","project_id":419805,"amount":"1.500000","ablance":"1016191.215000","note":"","created_at":"2019-03-15 13:31:03"},{"id":4111453,"serial_number":"54925C8B38978AF8A7.57808771","type_id":6,"description":"投注解冻","is_income":1,"trace_id":598,"lottery_id":1,"issue":"190315028","way_id":78,"coefficient":"0.050","project_id":null,"amount":"1.500000","ablance":"1016192.715000","note":"","created_at":"2019-03-15 13:31:03"},{"id":4111451,"serial_number":"54925C8B376B312BF1.83134533","type_id":11,"description":"派发奖金","is_income":1,"trace_id":635,"lottery_id":49,"issue":"20190315004","way_id":177,"coefficient":"0.050","project_id":419804,"amount":"0.975000","ablance":"1016191.215000","note":"","created_at":"2019-03-15 13:26:03"},{"id":4111449,"serial_number":"54925C8B3608CFDD08.96783487","type_id":15,"description":"追号返款","is_income":1,"trace_id":635,"lottery_id":49,"issue":null,"way_id":177,"coefficient":"0.050","project_id":null,"amount":"1.000000","ablance":"1016190.240000","note":"","created_at":"2019-03-15 13:20:08"},{"id":4111448,"serial_number":"54925C8B3608A574E1.27364222","type_id":11,"description":"派发奖金","is_income":1,"trace_id":635,"lottery_id":49,"issue":"20190315003","way_id":177,"coefficient":"0.050","project_id":419803,"amount":"0.975000","ablance":"1016189.240000","note":"","created_at":"2019-03-15 13:20:08"},{"id":4111447,"serial_number":"54925C8B360489BF47.07684741","type_id":7,"description":"加入游戏","is_income":0,"trace_id":635,"lottery_id":49,"issue":"20190315004","way_id":177,"coefficient":"0.050","project_id":419804,"amount":"1.000000","ablance":"1016188.265000","note":"","created_at":"2019-03-15 13:20:04"},{"id":4111446,"serial_number":"54925C8B36048052A7.10130359","type_id":6,"description":"投注解冻","is_income":1,"trace_id":635,"lottery_id":49,"issue":"20190315004","way_id":177,"coefficient":"0.050","project_id":null,"amount":"1.000000","ablance":"1016189.265000","note":"","created_at":"2019-03-15 13:20:04"},{"id":4111445,"serial_number":"54925C8B3603D6C530.10584440","type_id":7,"description":"加入游戏","is_income":0,"trace_id":635,"lottery_id":49,"issue":"20190315003","way_id":177,"coefficient":"0.050","project_id":null,"amount":"1.000000","ablance":"1016189.265000","note":"","created_at":"2019-03-15 13:20:03"},{"id":4111444,"serial_number":"54925C8B3603CE42A9.99169201","type_id":6,"description":"投注解冻","is_income":1,"trace_id":635,"lottery_id":49,"issue":"20190315003","way_id":177,"coefficient":"0.050","project_id":null,"amount":"1.000000","ablance":"1016189.265000","note":"","created_at":"2019-03-15 13:20:03"},{"id":4111441,"serial_number":"54925C8B34D95C6385.21450049","type_id":15,"description":"追号返款","is_income":1,"trace_id":636,"lottery_id":49,"issue":null,"way_id":177,"coefficient":"0.050","project_id":null,"amount":"3.000000","ablance":"1016188.265000","note":"","created_at":"2019-03-15 13:15:05"},{"id":4111440,"serial_number":"54925C8B34D93B8518.40972121","type_id":11,"description":"派发奖金","is_income":1,"trace_id":636,"lottery_id":49,"issue":"20190315002","way_id":177,"coefficient":"0.050","project_id":419802,"amount":"0.975000","ablance":"1016185.265000","note":"","created_at":"2019-03-15 13:15:05"},{"id":4111438,"serial_number":"54925C8B33ABEED364.65293591","type_id":11,"description":"派发奖金","is_income":1,"trace_id":null,"lottery_id":49,"issue":"20190315001","way_id":177,"coefficient":"0.050","project_id":419795,"amount":"0.975000","ablance":"1016184.290000","note":"","created_at":"2019-03-15 13:10:03"},{"id":4111437,"serial_number":"54925C8B33ABBA6A02.18482482","type_id":7,"description":"加入游戏","is_income":0,"trace_id":636,"lottery_id":49,"issue":"20190315002","way_id":177,"coefficient":"0.050","project_id":419802,"amount":"1.000000","ablance":"1016183.315000","note":"","created_at":"2019-03-15 13:10:03"},{"id":4111436,"serial_number":"54925C8B33ABA71DB3.95659989","type_id":6,"description":"投注解冻","is_income":1,"trace_id":636,"lottery_id":49,"issue":"20190315002","way_id":177,"coefficient":"0.050","project_id":null,"amount":"1.000000","ablance":"1016184.315000","note":"","created_at":"2019-03-15 13:10:03"},{"id":4111434,"serial_number":"54925C8B2614DDC500.68808207","type_id":11,"description":"派发奖金","is_income":1,"trace_id":597,"lottery_id":1,"issue":"190315024","way_id":78,"coefficient":"0.050","project_id":419801,"amount":"2.925000","ablance":"1016183.315000","note":"","created_at":"2019-03-15 12:12:04"}]
     * begin_date : 2019-03-14
     * end_date : 2019-03-18
     * count : 319
     * iPage : 1
     * iPageSize : 20
     * bTotalExpenses : -6
     * bTotalRevenue : 31.75
     */

    private String begin_date;
    private String end_date;
    private int count;
    private String iPage;
    private String iPageSize;
    private String bTotalExpenses;
    private String bTotalRevenue;
    private List<ATransactionsBean> aTransactions;

    public String getBegin_date() {
        return begin_date;
    }

    public void setBegin_date(String begin_date) {
        this.begin_date = begin_date;
    }

    public String getEnd_date() {
        return end_date;
    }

    public void setEnd_date(String end_date) {
        this.end_date = end_date;
    }

    public int getCount() {
        return count;
    }

    public void setCount(int count) {
        this.count = count;
    }

    public String getIPage() {
        return iPage;
    }

    public void setIPage(String iPage) {
        this.iPage = iPage;
    }

    public String getIPageSize() {
        return iPageSize;
    }

    public void setIPageSize(String iPageSize) {
        this.iPageSize = iPageSize;
    }

    public String getBTotalExpenses() {
        return bTotalExpenses;
    }

    public void setBTotalExpenses(String bTotalExpenses) {
        this.bTotalExpenses = bTotalExpenses;
    }

    public String getBTotalRevenue() {
        return bTotalRevenue;
    }

    public void setBTotalRevenue(String bTotalRevenue) {
        this.bTotalRevenue = bTotalRevenue;
    }

    public List<ATransactionsBean> getATransactions() {
        return aTransactions;
    }

    public void setATransactions(List<ATransactionsBean> aTransactions) {
        this.aTransactions = aTransactions;
    }

    public static class ATransactionsBean {
        /**
         * id : 4111460
         * serial_number : 54925C8B3D841361C9.57092475
         * type_id : 11
         * description : 派发奖金
         * is_income : 1
         * trace_id : 598
         * lottery_id : 1
         * issue : 190315029
         * way_id : 78
         * coefficient : 0.050
         * project_id : 419806
         * amount : 0.975000
         * ablance : 1016206.140000
         * note :
         * created_at : 2019-03-15 13:52:04
         */

        private int id;
        private String serial_number;
        private int type_id;
        private String description;
        private int is_income;
        private int trace_id;
        private int lottery_id;
        private String issue;
        private int way_id;
        private String coefficient;
        private String project_id="";
        private String amount;
        private String ablance;
        private String note;
        private String created_at;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public String getSerial_number() {
            return serial_number;
        }

        public void setSerial_number(String serial_number) {
            this.serial_number = serial_number;
        }

        public int getType_id() {
            return type_id;
        }

        public void setType_id(int type_id) {
            this.type_id = type_id;
        }

        public String getDescription() {
            return description;
        }

        public void setDescription(String description) {
            this.description = description;
        }

        public int getIs_income() {
            return is_income;
        }

        public void setIs_income(int is_income) {
            this.is_income = is_income;
        }

        public int getTrace_id() {
            return trace_id;
        }

        public void setTrace_id(int trace_id) {
            this.trace_id = trace_id;
        }

        public int getLottery_id() {
            return lottery_id;
        }

        public void setLottery_id(int lottery_id) {
            this.lottery_id = lottery_id;
        }

        public String getIssue() {
            return issue;
        }

        public void setIssue(String issue) {
            this.issue = issue;
        }

        public int getWay_id() {
            return way_id;
        }

        public void setWay_id(int way_id) {
            this.way_id = way_id;
        }

        public String getCoefficient() {
            return coefficient;
        }

        public void setCoefficient(String coefficient) {
            this.coefficient = coefficient;
        }

        public String getProject_id() {
            return Check.isEmpty(project_id)?"":project_id;
        }

        public void setProject_id(String project_id) {
            this.project_id = project_id;
        }

        public String getAmount() {
            return amount;
        }

        public void setAmount(String amount) {
            this.amount = amount;
        }

        public String getAblance() {
            return ablance;
        }

        public void setAblance(String ablance) {
            this.ablance = ablance;
        }

        public String getNote() {
            return note;
        }

        public void setNote(String note) {
            this.note = note;
        }

        public String getCreated_at() {
            return created_at;
        }

        public void setCreated_at(String created_at) {
            this.created_at = created_at;
        }
    }
}
