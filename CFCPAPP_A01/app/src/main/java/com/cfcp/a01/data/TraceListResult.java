package com.cfcp.a01.data;

import java.util.List;

public class TraceListResult {


    /**
     * traces : [{"id":593,"user_id":5492,"terminal_id":1,"serial_number":"54925C8A078E505D80.61976291","prize_group":"1950","lottery_id":13,"total_issues":5,"finished_issues":1,"canceled_issues":0,"stop_on_won":1,"start_issue":"1903140950","way_id":150,"way":"中三直选复式","bet_number":"4|4|45","coefficient":"0.050","single_amount":"0.2000","amount":"1.0000","prize":"0.000000","status":0,"bought_at":"2019-03-14 15:49:33","updated_at":"2019-03-14 15:49:34"},{"id":592,"user_id":5492,"terminal_id":1,"serial_number":"54925C8A078E365584.58624320","prize_group":"1950","lottery_id":13,"total_issues":5,"finished_issues":1,"canceled_issues":0,"stop_on_won":1,"start_issue":"1903140950","way_id":150,"way":"中三直选复式","bet_number":"4|5|47","coefficient":"0.050","single_amount":"0.2000","amount":"1.0000","prize":"0.000000","status":0,"bought_at":"2019-03-14 15:49:33","updated_at":"2019-03-14 15:49:34"},{"id":591,"user_id":5492,"terminal_id":1,"serial_number":"54925C8A078E1DB3C3.95093775","prize_group":"1950","lottery_id":13,"total_issues":5,"finished_issues":1,"canceled_issues":2,"stop_on_won":1,"start_issue":"1903140950","way_id":150,"way":"中三直选复式","bet_number":"3|3|3","coefficient":"0.050","single_amount":"0.1000","amount":"0.5000","prize":"0.000000","status":0,"bought_at":"2019-03-14 15:49:33","updated_at":"2019-03-14 15:50:24"},{"id":590,"user_id":5492,"terminal_id":1,"serial_number":"54925C8A078DF093D2.33516454","prize_group":"1950","lottery_id":13,"total_issues":5,"finished_issues":1,"canceled_issues":0,"stop_on_won":1,"start_issue":"1903140950","way_id":150,"way":"中三直选复式","bet_number":"6|7|7","coefficient":"0.050","single_amount":"0.1000","amount":"0.5000","prize":"0.000000","status":0,"bought_at":"2019-03-14 15:49:33","updated_at":"2019-03-14 15:49:34"}]
     * begin_date : 2019-03-14
     * end_date : 2019-03-15
     * count : 4
     * page : 0
     * pagesize : 20
     */

    private String begin_date;
    private String end_date;
    private int count;
    private String page;
    private String pagesize;
    private List<TracesBean> traces;

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

    public String getPage() {
        return page;
    }

    public void setPage(String page) {
        this.page = page;
    }

    public String getPagesize() {
        return pagesize;
    }

    public void setPagesize(String pagesize) {
        this.pagesize = pagesize;
    }

    public List<TracesBean> getTraces() {
        return traces;
    }

    public void setTraces(List<TracesBean> traces) {
        this.traces = traces;
    }

    public static class TracesBean {
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
         */

        private int id;
        private int user_id;
        private int terminal_id;
        private String serial_number;
        private String prize_group;
        private int lottery_id;
        private int total_issues;
        private int finished_issues;
        private int canceled_issues;
        private int stop_on_won;
        private String start_issue;
        private int way_id;
        private String way;
        private String bet_number;
        private String coefficient;
        private String single_amount;
        private String amount;
        private String prize;
        private int status;
        private String bought_at;
        private String updated_at;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public int getUser_id() {
            return user_id;
        }

        public void setUser_id(int user_id) {
            this.user_id = user_id;
        }

        public int getTerminal_id() {
            return terminal_id;
        }

        public void setTerminal_id(int terminal_id) {
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

        public int getLottery_id() {
            return lottery_id;
        }

        public void setLottery_id(int lottery_id) {
            this.lottery_id = lottery_id;
        }

        public int getTotal_issues() {
            return total_issues;
        }

        public void setTotal_issues(int total_issues) {
            this.total_issues = total_issues;
        }

        public int getFinished_issues() {
            return finished_issues;
        }

        public void setFinished_issues(int finished_issues) {
            this.finished_issues = finished_issues;
        }

        public int getCanceled_issues() {
            return canceled_issues;
        }

        public void setCanceled_issues(int canceled_issues) {
            this.canceled_issues = canceled_issues;
        }

        public int getStop_on_won() {
            return stop_on_won;
        }

        public void setStop_on_won(int stop_on_won) {
            this.stop_on_won = stop_on_won;
        }

        public String getStart_issue() {
            return start_issue;
        }

        public void setStart_issue(String start_issue) {
            this.start_issue = start_issue;
        }

        public int getWay_id() {
            return way_id;
        }

        public void setWay_id(int way_id) {
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
    }
}
