package com.hfcp.hf.data;

import java.util.List;

public class TraceDetailResult {

    /**
     * basic : {"id":593,"user_id":5492,"terminal_id":1,"serial_number":"54925C8A078E505D80.61976291","prize_group":"1950","lottery_id":13,"total_issues":5,"finished_issues":1,"canceled_issues":0,"stop_on_won":1,"start_issue":"1903140950","way_id":150,"way":"中三直选复式","bet_number":"4|4|45","coefficient":"0.050","single_amount":"0.2000","amount":"1.0000","prize":"0.000000","status":0,"bought_at":"2019-03-14 15:49:33","updated_at":"2019-03-14 15:49:34","formatted_coefficient":"1角","amount_formatted":"1.00","canceled_amount_formatted":"0.00","open_then_stop":"是","formatted_stop_on_won":"是"}
     * issues : [{"id":15434,"issue":"1903140950","multiple":1,"amount":"0.2000","project_id":419726,"status":1,"bought_at":"2019-03-14 15:49:34","canceled_at":null},{"id":15435,"issue":"1903140951","multiple":1,"amount":"0.2000","project_id":null,"status":0,"bought_at":null,"canceled_at":null},{"id":15436,"issue":"1903140952","multiple":1,"amount":"0.2000","project_id":null,"status":0,"bought_at":null,"canceled_at":null},{"id":15437,"issue":"1903140953","multiple":1,"amount":"0.2000","project_id":null,"status":0,"bought_at":null,"canceled_at":null},{"id":15438,"issue":"1903140954","multiple":1,"amount":"0.2000","project_id":null,"status":0,"bought_at":null,"canceled_at":null}]
     */

    private BasicBean basic;
    private List<IssuesBean> issues;

    public BasicBean getBasic() {
        return basic;
    }

    public void setBasic(BasicBean basic) {
        this.basic = basic;
    }

    public List<IssuesBean> getIssues() {
        return issues;
    }

    public void setIssues(List<IssuesBean> issues) {
        this.issues = issues;
    }

    public static class BasicBean {
        /**
         * id : 593
         * user_id : 5492
         * terminal_id : 1
         * serial_number : 54925C8A078E505D80.61976291
         * prize_group : 1950
         * lottery_id : 13
         * total_issues : 5
         * finished_issues : 1
         * canceled_issues : 0
         * stop_on_won : 1
         * start_issue : 1903140950
         * way_id : 150
         * way : 中三直选复式
         * bet_number : 4|4|45
         * coefficient : 0.050
         * single_amount : 0.2000
         * amount : 1.0000
         * prize : 0.000000
         * status : 0
         * bought_at : 2019-03-14 15:49:33
         * updated_at : 2019-03-14 15:49:34
         * formatted_coefficient : 1角
         * amount_formatted : 1.00
         * canceled_amount_formatted : 0.00
         * open_then_stop : 是
         * formatted_stop_on_won : 是
         */

        private String id;
        private String user_id;
        private String terminal_id;
        private String serial_number;
        private String prize_group;
        private String lottery_id;
        private String total_issues;
        private String finished_issues;
        private String canceled_issues;
        private String stop_on_won;
        private String start_issue;
        private String way_id;
        private String way;
        private String bet_number;
        private String coefficient;
        private String single_amount;
        private String amount;
        private String prize;
        private int status;
        private String bought_at;
        private String updated_at;
        private String formatted_coefficient;
        private String amount_formatted;
        private String canceled_amount_formatted;
        private String jump_open_then_stop;
        private String formatted_stop_on_won;

        public String getId() {
            return id;
        }

        public void setId(String id) {
            this.id = id;
        }

        public String getUser_id() {
            return user_id;
        }

        public void setUser_id(String user_id) {
            this.user_id = user_id;
        }

        public String getTerminal_id() {
            return terminal_id;
        }

        public void setTerminal_id(String terminal_id) {
            this.terminal_id = terminal_id;
        }

        public String getSerial_number() {
            return serial_number;
        }

        public void setSerial_number(String serial_number) {
            this.serial_number = serial_number;
        }

        public String getPrize_group() {
            return prize_group;
        }

        public void setPrize_group(String prize_group) {
            this.prize_group = prize_group;
        }

        public String getLottery_id() {
            return lottery_id;
        }

        public void setLottery_id(String lottery_id) {
            this.lottery_id = lottery_id;
        }

        public String getTotal_issues() {
            return total_issues;
        }

        public void setTotal_issues(String total_issues) {
            this.total_issues = total_issues;
        }

        public String getFinished_issues() {
            return finished_issues;
        }

        public void setFinished_issues(String finished_issues) {
            this.finished_issues = finished_issues;
        }

        public String getCanceled_issues() {
            return canceled_issues;
        }

        public void setCanceled_issues(String canceled_issues) {
            this.canceled_issues = canceled_issues;
        }

        public String getStop_on_won() {
            return stop_on_won;
        }

        public void setStop_on_won(String stop_on_won) {
            this.stop_on_won = stop_on_won;
        }

        public String getStart_issue() {
            return start_issue;
        }

        public void setStart_issue(String start_issue) {
            this.start_issue = start_issue;
        }

        public String getWay_id() {
            return way_id;
        }

        public void setWay_id(String way_id) {
            this.way_id = way_id;
        }

        public String getWay() {
            return way;
        }

        public void setWay(String way) {
            this.way = way;
        }

        public String getBet_number() {
            return bet_number;
        }

        public void setBet_number(String bet_number) {
            this.bet_number = bet_number;
        }

        public String getCoefficient() {
            return coefficient;
        }

        public void setCoefficient(String coefficient) {
            this.coefficient = coefficient;
        }

        public String getSingle_amount() {
            return single_amount;
        }

        public void setSingle_amount(String single_amount) {
            this.single_amount = single_amount;
        }

        public String getAmount() {
            return amount;
        }

        public void setAmount(String amount) {
            this.amount = amount;
        }

        public String getPrize() {
            return prize;
        }

        public void setPrize(String prize) {
            this.prize = prize;
        }

        public int getStatus() {
            return status;
        }

        public void setStatus(int status) {
            this.status = status;
        }

        public String getBought_at() {
            return bought_at;
        }

        public void setBought_at(String bought_at) {
            this.bought_at = bought_at;
        }

        public String getUpdated_at() {
            return updated_at;
        }

        public void setUpdated_at(String updated_at) {
            this.updated_at = updated_at;
        }

        public String getFormatted_coefficient() {
            return formatted_coefficient;
        }

        public void setFormatted_coefficient(String formatted_coefficient) {
            this.formatted_coefficient = formatted_coefficient;
        }

        public String getAmount_formatted() {
            return amount_formatted;
        }

        public void setAmount_formatted(String amount_formatted) {
            this.amount_formatted = amount_formatted;
        }

        public String getCanceled_amount_formatted() {
            return canceled_amount_formatted;
        }

        public void setCanceled_amount_formatted(String canceled_amount_formatted) {
            this.canceled_amount_formatted = canceled_amount_formatted;
        }

        public String getJump_Open_then_stop() {
            return jump_open_then_stop;
        }

        public void setJump_Open_then_stop(String jump_open_then_stop) {
            this.jump_open_then_stop = jump_open_then_stop;
        }

        public String getFormatted_stop_on_won() {
            return formatted_stop_on_won;
        }

        public void setFormatted_stop_on_won(String formatted_stop_on_won) {
            this.formatted_stop_on_won = formatted_stop_on_won;
        }
    }

    public static class IssuesBean {
        /**
         * id : 15434
         * issue : 1903140950
         * multiple : 1
         * amount : 0.2000
         * project_id : 419726
         * status : 1
         * bought_at : 2019-03-14 15:49:34
         * canceled_at : null
         */

        private String id;
        private String issue;
        private String multiple;
        private String amount;
        private String project_id;
        private int status;
        private String bought_at;
        private String canceled_at;

        public String getId() {
            return id;
        }

        public void setId(String id) {
            this.id = id;
        }

        public String getIssue() {
            return issue;
        }

        public void setIssue(String issue) {
            this.issue = issue;
        }

        public String getMultiple() {
            return multiple;
        }

        public void setMultiple(String multiple) {
            this.multiple = multiple;
        }

        public String getAmount() {
            return amount;
        }

        public void setAmount(String amount) {
            this.amount = amount;
        }

        public String getProject_id() {
            return project_id;
        }

        public void setProject_id(String project_id) {
            this.project_id = project_id;
        }

        public int getStatus() {
            return status;
        }

        public void setStatus(int status) {
            this.status = status;
        }

        public String getBought_at() {
            return bought_at;
        }

        public void setBought_at(String bought_at) {
            this.bought_at = bought_at;
        }

        public String getCanceled_at() {
            return canceled_at;
        }

        public void setCanceled_at(String canceled_at) {
            this.canceled_at = canceled_at;
        }
    }
}
