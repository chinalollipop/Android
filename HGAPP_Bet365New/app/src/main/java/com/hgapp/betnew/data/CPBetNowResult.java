package com.hgapp.betnew.data;

import com.google.gson.annotations.SerializedName;

public class CPBetNowResult {

    /**
     * 207 : {"totalMoney":60,"totalNums":24,"gameId":"207"}
     * 69 : {"totalMoney":112,"totalNums":111,"gameId":"69"}
     * 304 : {"totalMoney":88,"totalNums":16,"gameId":"304"}
     * 51 : {"totalMoney":98,"totalNums":22,"gameId":"51"}
     */
    /** 北京赛车    game_code 51
     *  重庆时时彩    game_code 2
     *  极速赛车    game_code 189
     *  极速飞艇    game_code 222
     *  分分彩    game_code 207
     *  三分彩    game_code 407
     *  五分彩    game_code 507
     *  腾讯二分彩    game_code 607
     *  PC蛋蛋    game_code 304
     *  江苏快3    game_code 159
     *  幸运农场    game_code 47
     *  快乐十分    game_code 3
     *  香港六合彩  game_code 69
     *  极速快三    game_code 384
     *
     */

    @SerializedName("51")
    private dataBean data51;
    @SerializedName("2")
    private dataBean data2;
    @SerializedName("189")
    private dataBean data189;
    @SerializedName("168")
    private dataBean data168;
    @SerializedName("222")
    private dataBean data222;
    @SerializedName("207")
    private dataBean data207;
    @SerializedName("407")
    private dataBean data407;
    @SerializedName("507")
    private dataBean data507;
    @SerializedName("607")
    private dataBean data607;

    @SerializedName("304")
    private dataBean data304;
    @SerializedName("159")
    private dataBean data159;
    @SerializedName("47")
    private dataBean data47;
    @SerializedName("3")
    private dataBean data3;
    @SerializedName("69")
    private dataBean data69;
    @SerializedName("384")
    private dataBean data384;

    public dataBean getData51() {
        return data51;
    }

    public void setData51(dataBean data51) {
        this.data51 = data51;
    }

    public dataBean getData2() {
        return data2;
    }

    public void setData2(dataBean data2) {
        this.data2 = data2;
    }

    public dataBean getData189() {
        return data189;
    }

    public void setData189(dataBean data189) {
        this.data189 = data189;
    }

    public dataBean getData168() {
        return data168;
    }

    public void setData168(dataBean data168) {
        this.data168 = data168;
    }

    public dataBean getData222() {
        return data222;
    }

    public void setData222(dataBean data222) {
        this.data222 = data222;
    }

    public dataBean getData207() {
        return data207;
    }

    public void setData207(dataBean data207) {
        this.data207 = data207;
    }

    public dataBean getData407() {
        return data407;
    }

    public void setData407(dataBean data407) {
        this.data407 = data407;
    }

    public dataBean getData507() {
        return data507;
    }

    public void setData507(dataBean data507) {
        this.data507 = data507;
    }

    public dataBean getData607() {
        return data607;
    }

    public void setData607(dataBean data607) {
        this.data607 = data607;
    }

    public dataBean getData304() {
        return data304;
    }

    public void setData304(dataBean data304) {
        this.data304 = data304;
    }

    public dataBean getData159() {
        return data159;
    }

    public void setData159(dataBean data159) {
        this.data159 = data159;
    }

    public dataBean getData47() {
        return data47;
    }

    public void setData47(dataBean data47) {
        this.data47 = data47;
    }

    public dataBean getData3() {
        return data3;
    }

    public void setData3(dataBean data3) {
        this.data3 = data3;
    }

    public dataBean getData69() {
        return data69;
    }

    public void setData69(dataBean data69) {
        this.data69 = data69;
    }

    public dataBean getData384() {
        return data384;
    }

    public void setData384(dataBean data384) {
        this.data384 = data384;
    }

    public static class dataBean {
        /**
         * totalMoney : 60
         * totalNums : 24
         * gameId : 207
         */

        private String totalMoney;
        private String totalNums;
        private String gameId;

        public String getTotalMoney() {
            return totalMoney;
        }

        public void setTotalMoney(String totalMoney) {
            this.totalMoney = totalMoney;
        }

        public String getTotalNums() {
            return totalNums;
        }

        public void setTotalNums(String totalNums) {
            this.totalNums = totalNums;
        }

        public String getGameId() {
            return gameId;
        }

        public void setGameId(String gameId) {
            this.gameId = gameId;
        }
    }


}
