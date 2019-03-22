package com.cfcp.a01.data;

import java.util.List;

public class BetData {

    /**
     * gameId : 1
     * traceStopValue : 1
     * isTrace : 0
     * orders : {"180417025":1}
     * balls : [{"jsId":0,"multiple":2,"moneyunit":0.1,"ball":"26395","wayId":7,"num":1,"onePrice":1,"viewBalls":"","type":"wuxing.zhixuan.danshi","prizeGroup":1938}]
     * amount : 0.2
     * traceWinStop : 1
     */

    private int gameId;
    private int traceStopValue;
    private int isTrace;
    private Object orders;
    private double amount;
    private int traceWinStop;
    private List<BallsBean> balls;

    public int getGameId() {
        return gameId;
    }

    public void setGameId(int gameId) {
        this.gameId = gameId;
    }

    public int getTraceStopValue() {
        return traceStopValue;
    }

    public void setTraceStopValue(int traceStopValue) {
        this.traceStopValue = traceStopValue;
    }

    public int getIsTrace() {
        return isTrace;
    }

    public void setIsTrace(int isTrace) {
        this.isTrace = isTrace;
    }

    public Object getOrders() {
        return orders;
    }

    public void setOrders(Object orders) {
        this.orders = orders;
    }

    public double getAmount() {
        return amount;
    }

    public void setAmount(double amount) {
        this.amount = amount;
    }

    public int getTraceWinStop() {
        return traceWinStop;
    }

    public void setTraceWinStop(int traceWinStop) {
        this.traceWinStop = traceWinStop;
    }

    public List<BallsBean> getBalls() {
        return balls;
    }

    public void setBalls(List<BallsBean> balls) {
        this.balls = balls;
    }

    public static class BallsBean {
        /**
         * jsId : 0
         * multiple : 2
         * moneyunit : 0.1
         * ball : 26395
         * wayId : 7
         * num : 1
         * onePrice : 1
         * viewBalls :
         * type : wuxing.zhixuan.danshi
         * prizeGroup : 1938
         */

        private int jsId;
        private int multiple;
        private double moneyunit;
        private String ball;
        private int wayId;
        private int num;
        private double onePrice;
        private String viewBalls;
        private String type;
        private int prizeGroup;

        public int getJsId() {
            return jsId;
        }

        public void setJsId(int jsId) {
            this.jsId = jsId;
        }

        public int getMultiple() {
            return multiple;
        }

        public void setMultiple(int multiple) {
            this.multiple = multiple;
        }

        public double getMoneyunit() {
            return moneyunit;
        }

        public void setMoneyunit(double moneyunit) {
            this.moneyunit = moneyunit;
        }

        public String getBall() {
            return ball;
        }

        public void setBall(String ball) {
            this.ball = ball;
        }

        public int getWayId() {
            return wayId;
        }

        public void setWayId(int wayId) {
            this.wayId = wayId;
        }

        public int getNum() {
            return num;
        }

        public void setNum(int num) {
            this.num = num;
        }

        public double getOnePrice() {
            return onePrice;
        }

        public void setOnePrice(double onePrice) {
            this.onePrice = onePrice;
        }

        public String getViewBalls() {
            return viewBalls;
        }

        public void setViewBalls(String viewBalls) {
            this.viewBalls = viewBalls;
        }

        public String getType() {
            return type;
        }

        public void setType(String type) {
            this.type = type;
        }

        public int getPrizeGroup() {
            return prizeGroup;
        }

        public void setPrizeGroup(int prizeGroup) {
            this.prizeGroup = prizeGroup;
        }
    }

}
