package com.hfcp.hf.data;

public class BetDetailResult {
    /**
     * id : 419708
     * user_id : 5492
     * terminal_id : 1
     * serial_number : 54925C89C753047177.78752571
     * trace_id : null
     * prize_group : 1950
     * lottery_id : 1
     * issue : 190314022
     * way_id : 78
     * way : null
     * bet_number : 0123456789|0123456789|0123456789||
     * single_count : 30
     * multiple : 1
     * coefficient : 0.050
     * amount : 3.0000
     * winning_number : 76409
     * prize : 2.9250
     * status : 3
     * bought_at : 2019-03-14 11:15:30
     * title : 定位胆
     * prize_set_formatted : 定位胆 : 一等奖: 0.975元
     * moving_rebate : 0
     * multiple_mode : 1倍，30注，1角
     */

    private int id;
    private int user_id;
    private int terminal_id;
    private String serial_number;
    private Object trace_id;
    private String prize_group;
    private int lottery_id;
    private String issue;
    private int way_id;
    private String way;
    private String bet_number;
    private int single_count;
    private int multiple;
    private String coefficient;
    private String amount;
    private String winning_number;
    private String prize;
    private int status;
    private String bought_at;
    private String title;
    private String prize_set_formatted;
    private int moving_rebate;
    private String multiple_mode;

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

    public Object getTrace_id() {
        return trace_id;
    }

    public void setTrace_id(Object trace_id) {
        this.trace_id = trace_id;
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

    public int getSingle_count() {
        return single_count;
    }

    public void setSingle_count(int single_count) {
        this.single_count = single_count;
    }

    public int getMultiple() {
        return multiple;
    }

    public void setMultiple(int multiple) {
        this.multiple = multiple;
    }

    public String getCoefficient() {
        return coefficient;
    }

    public void setCoefficient(String coefficient) {
        this.coefficient = coefficient;
    }

    public String getAmount() {
        return amount;
    }

    public void setAmount(String amount) {
        this.amount = amount;
    }

    public String getWinning_number() {
        return winning_number;
    }

    public void setWinning_number(String winning_number) {
        this.winning_number = winning_number;
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

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getPrize_set_formatted() {
        return prize_set_formatted;
    }

    public void setPrize_set_formatted(String prize_set_formatted) {
        this.prize_set_formatted = prize_set_formatted;
    }

    public int getMoving_rebate() {
        return moving_rebate;
    }

    public void setMoving_rebate(int moving_rebate) {
        this.moving_rebate = moving_rebate;
    }

    public String getMultiple_mode() {
        return multiple_mode;
    }

    public void setMultiple_mode(String multiple_mode) {
        this.multiple_mode = multiple_mode;
    }
}
