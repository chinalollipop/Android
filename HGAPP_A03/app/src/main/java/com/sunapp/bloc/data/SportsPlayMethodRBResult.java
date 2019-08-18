package com.sunapp.bloc.data;


import java.util.List;

public class SportsPlayMethodRBResult {


    /**
     * status : 200
     * describe : success
     * timestamp : 20180712023412
     * data : [{"MID":"3314912","M_Time":"10:37p","M_Type":"1","MB_MID":"61050","TG_MID":"61049","MB_Team":"洛杉矶","TG_Team":"堪萨斯城体育会","M_League":"美国职业大联盟","MB_Win_Rate_RB":"4.96","TG_Win_Rate_RB":"1.67","M_Flat_Rate_RB":"3.66","M_LetB_RB":"0 / 0.5","T_LetB_RB":"","MB_LetB_Rate_RB":"1.01","TG_LetB_Rate_RB":"0.91","MB_Dime_RB":"O2.5 / 3","TG_Dime_RB":"U2.5 / 3","MB_Dime_Rate_RB":"0.92","TG_Dime_Rate_RB":"0.99","ShowTypeRB":"H","ShowTypeHRB":"","MB_Win_Rate_RB_H":"","TG_Win_Rate_RB_H":"","M_Flat_Rate_RB_H":"","M_LetB_RB_H":"","MB_LetB_Rate_RB_H":"","TG_LetB_Rate_RB_H":"","MB_Dime_RB_H":"","MB_Dime_RB_S_H":"","TG_Dime_RB_H":"","TG_Dime_RB_S_H":"","MB_Dime_Rate_RB_H":"","MB_Dime_Rate_RB_S_H":"","TG_Dime_Rate_RB_H":"","TG_Dime_Rate_RB_S_H":"","MB_Ball":"0","TG_Ball":"1","MB_Inball_HR":"0","TG_Inball_HR":"1","Eventid":"单","Hot":"双","Play":"1","nowSession":""}]
     * sign : d2564c5916e894341d9764385e0e06ab
     */

    private String status;
    private String describe;
    private String timestamp;
    private String sign;
    private List<DataBean> data;

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getDescribe() {
        return describe;
    }

    public void setDescribe(String describe) {
        this.describe = describe;
    }

    public String getTimestamp() {
        return timestamp;
    }

    public void setTimestamp(String timestamp) {
        this.timestamp = timestamp;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public List<DataBean> getData() {
        return data;
    }

    public void setData(List<DataBean> data) {
        this.data = data;
    }

    public static class DataBean {
        /**
         * MID : 3314912
         * M_Time : 10:37p
         * M_Type : 1
         * MB_MID : 61050
         * TG_MID : 61049
         * MB_Team : 洛杉矶
         * TG_Team : 堪萨斯城体育会
         * M_League : 美国职业大联盟
         * MB_Win_Rate_RB : 4.96
         * TG_Win_Rate_RB : 1.67
         * M_Flat_Rate_RB : 3.66
         * M_LetB_RB : 0 / 0.5
         * T_LetB_RB :
         * MB_LetB_Rate_RB : 1.01
         * TG_LetB_Rate_RB : 0.91
         * MB_Dime_RB : O2.5 / 3
         * TG_Dime_RB : U2.5 / 3
         * MB_Dime_Rate_RB : 0.92
         * TG_Dime_Rate_RB : 0.99
         * ShowTypeRB : H
         * ShowTypeHRB :
         * MB_Win_Rate_RB_H :
         * TG_Win_Rate_RB_H :
         * M_Flat_Rate_RB_H :
         * M_LetB_RB_H :
         * MB_LetB_Rate_RB_H :
         * TG_LetB_Rate_RB_H :
         * MB_Dime_RB_H :
         * MB_Dime_RB_S_H :
         * TG_Dime_RB_H :
         * TG_Dime_RB_S_H :
         * MB_Dime_Rate_RB_H :
         * MB_Dime_Rate_RB_S_H :
         * TG_Dime_Rate_RB_H :
         * TG_Dime_Rate_RB_S_H :
         * MB_Ball : 0
         * TG_Ball : 1
         * MB_Inball_HR : 0
         * TG_Inball_HR : 1
         * Eventid : 单
         * Hot : 双
         * Play : 1
         * nowSession :
         */

        private String MID;
        private String M_Time;
        private String M_Type;
        private String MB_MID;
        private String TG_MID;
        private String MB_Team;
        private String TG_Team;
        private String M_League;
        private String MB_Win_Rate_RB;
        private String TG_Win_Rate_RB;
        private String M_Flat_Rate_RB;
        private String M_LetB_RB;
        private String T_LetB_RB;
        private String MB_LetB_Rate_RB;
        private String TG_LetB_Rate_RB;
        private String MB_Dime_RB;
        private String TG_Dime_RB;
        private String MB_Dime_Rate_RB;
        private String TG_Dime_Rate_RB;
        private String ShowTypeRB;
        private String ShowTypeHRB;
        private String MB_Win_Rate_RB_H;
        private String TG_Win_Rate_RB_H;
        private String M_Flat_Rate_RB_H;
        private String M_LetB_RB_H;
        private String MB_LetB_Rate_RB_H;
        private String TG_LetB_Rate_RB_H;
        private String MB_Dime_RB_H;
        private String MB_Dime_RB_S_H;
        private String TG_Dime_RB_H;
        private String TG_Dime_RB_S_H;
        private String MB_Dime_Rate_RB_H;
        private String MB_Dime_Rate_RB_S_H;
        private String TG_Dime_Rate_RB_H;
        private String TG_Dime_Rate_RB_S_H;
        private String MB_Ball;
        private String TG_Ball;
        private String MB_Inball_HR;
        private String TG_Inball_HR;
        private String Eventid;
        private String Hot;
        private String Play;
        private String nowSession;

        public String getMID() {
            return MID;
        }

        public void setMID(String MID) {
            this.MID = MID;
        }

        public String getM_Time() {
            return M_Time;
        }

        public void setM_Time(String M_Time) {
            this.M_Time = M_Time;
        }

        public String getM_Type() {
            return M_Type;
        }

        public void setM_Type(String M_Type) {
            this.M_Type = M_Type;
        }

        public String getMB_MID() {
            return MB_MID;
        }

        public void setMB_MID(String MB_MID) {
            this.MB_MID = MB_MID;
        }

        public String getTG_MID() {
            return TG_MID;
        }

        public void setTG_MID(String TG_MID) {
            this.TG_MID = TG_MID;
        }

        public String getMB_Team() {
            return MB_Team;
        }

        public void setMB_Team(String MB_Team) {
            this.MB_Team = MB_Team;
        }

        public String getTG_Team() {
            return TG_Team;
        }

        public void setTG_Team(String TG_Team) {
            this.TG_Team = TG_Team;
        }

        public String getM_League() {
            return M_League;
        }

        public void setM_League(String M_League) {
            this.M_League = M_League;
        }

        public String getMB_Win_Rate_RB() {
            return MB_Win_Rate_RB;
        }

        public void setMB_Win_Rate_RB(String MB_Win_Rate_RB) {
            this.MB_Win_Rate_RB = MB_Win_Rate_RB;
        }

        public String getTG_Win_Rate_RB() {
            return TG_Win_Rate_RB;
        }

        public void setTG_Win_Rate_RB(String TG_Win_Rate_RB) {
            this.TG_Win_Rate_RB = TG_Win_Rate_RB;
        }

        public String getM_Flat_Rate_RB() {
            return M_Flat_Rate_RB;
        }

        public void setM_Flat_Rate_RB(String M_Flat_Rate_RB) {
            this.M_Flat_Rate_RB = M_Flat_Rate_RB;
        }

        public String getM_LetB_RB() {
            return M_LetB_RB;
        }

        public void setM_LetB_RB(String M_LetB_RB) {
            this.M_LetB_RB = M_LetB_RB;
        }

        public String getT_LetB_RB() {
            return T_LetB_RB;
        }

        public void setT_LetB_RB(String T_LetB_RB) {
            this.T_LetB_RB = T_LetB_RB;
        }

        public String getMB_LetB_Rate_RB() {
            return MB_LetB_Rate_RB;
        }

        public void setMB_LetB_Rate_RB(String MB_LetB_Rate_RB) {
            this.MB_LetB_Rate_RB = MB_LetB_Rate_RB;
        }

        public String getTG_LetB_Rate_RB() {
            return TG_LetB_Rate_RB;
        }

        public void setTG_LetB_Rate_RB(String TG_LetB_Rate_RB) {
            this.TG_LetB_Rate_RB = TG_LetB_Rate_RB;
        }

        public String getMB_Dime_RB() {
            return MB_Dime_RB;
        }

        public void setMB_Dime_RB(String MB_Dime_RB) {
            this.MB_Dime_RB = MB_Dime_RB;
        }

        public String getTG_Dime_RB() {
            return TG_Dime_RB;
        }

        public void setTG_Dime_RB(String TG_Dime_RB) {
            this.TG_Dime_RB = TG_Dime_RB;
        }

        public String getMB_Dime_Rate_RB() {
            return MB_Dime_Rate_RB;
        }

        public void setMB_Dime_Rate_RB(String MB_Dime_Rate_RB) {
            this.MB_Dime_Rate_RB = MB_Dime_Rate_RB;
        }

        public String getTG_Dime_Rate_RB() {
            return TG_Dime_Rate_RB;
        }

        public void setTG_Dime_Rate_RB(String TG_Dime_Rate_RB) {
            this.TG_Dime_Rate_RB = TG_Dime_Rate_RB;
        }

        public String getShowTypeRB() {
            return ShowTypeRB;
        }

        public void setShowTypeRB(String ShowTypeRB) {
            this.ShowTypeRB = ShowTypeRB;
        }

        public String getShowTypeHRB() {
            return ShowTypeHRB;
        }

        public void setShowTypeHRB(String ShowTypeHRB) {
            this.ShowTypeHRB = ShowTypeHRB;
        }

        public String getMB_Win_Rate_RB_H() {
            return MB_Win_Rate_RB_H;
        }

        public void setMB_Win_Rate_RB_H(String MB_Win_Rate_RB_H) {
            this.MB_Win_Rate_RB_H = MB_Win_Rate_RB_H;
        }

        public String getTG_Win_Rate_RB_H() {
            return TG_Win_Rate_RB_H;
        }

        public void setTG_Win_Rate_RB_H(String TG_Win_Rate_RB_H) {
            this.TG_Win_Rate_RB_H = TG_Win_Rate_RB_H;
        }

        public String getM_Flat_Rate_RB_H() {
            return M_Flat_Rate_RB_H;
        }

        public void setM_Flat_Rate_RB_H(String M_Flat_Rate_RB_H) {
            this.M_Flat_Rate_RB_H = M_Flat_Rate_RB_H;
        }

        public String getM_LetB_RB_H() {
            return M_LetB_RB_H;
        }

        public void setM_LetB_RB_H(String M_LetB_RB_H) {
            this.M_LetB_RB_H = M_LetB_RB_H;
        }

        public String getMB_LetB_Rate_RB_H() {
            return MB_LetB_Rate_RB_H;
        }

        public void setMB_LetB_Rate_RB_H(String MB_LetB_Rate_RB_H) {
            this.MB_LetB_Rate_RB_H = MB_LetB_Rate_RB_H;
        }

        public String getTG_LetB_Rate_RB_H() {
            return TG_LetB_Rate_RB_H;
        }

        public void setTG_LetB_Rate_RB_H(String TG_LetB_Rate_RB_H) {
            this.TG_LetB_Rate_RB_H = TG_LetB_Rate_RB_H;
        }

        public String getMB_Dime_RB_H() {
            return MB_Dime_RB_H;
        }

        public void setMB_Dime_RB_H(String MB_Dime_RB_H) {
            this.MB_Dime_RB_H = MB_Dime_RB_H;
        }

        public String getMB_Dime_RB_S_H() {
            return MB_Dime_RB_S_H;
        }

        public void setMB_Dime_RB_S_H(String MB_Dime_RB_S_H) {
            this.MB_Dime_RB_S_H = MB_Dime_RB_S_H;
        }

        public String getTG_Dime_RB_H() {
            return TG_Dime_RB_H;
        }

        public void setTG_Dime_RB_H(String TG_Dime_RB_H) {
            this.TG_Dime_RB_H = TG_Dime_RB_H;
        }

        public String getTG_Dime_RB_S_H() {
            return TG_Dime_RB_S_H;
        }

        public void setTG_Dime_RB_S_H(String TG_Dime_RB_S_H) {
            this.TG_Dime_RB_S_H = TG_Dime_RB_S_H;
        }

        public String getMB_Dime_Rate_RB_H() {
            return MB_Dime_Rate_RB_H;
        }

        public void setMB_Dime_Rate_RB_H(String MB_Dime_Rate_RB_H) {
            this.MB_Dime_Rate_RB_H = MB_Dime_Rate_RB_H;
        }

        public String getMB_Dime_Rate_RB_S_H() {
            return MB_Dime_Rate_RB_S_H;
        }

        public void setMB_Dime_Rate_RB_S_H(String MB_Dime_Rate_RB_S_H) {
            this.MB_Dime_Rate_RB_S_H = MB_Dime_Rate_RB_S_H;
        }

        public String getTG_Dime_Rate_RB_H() {
            return TG_Dime_Rate_RB_H;
        }

        public void setTG_Dime_Rate_RB_H(String TG_Dime_Rate_RB_H) {
            this.TG_Dime_Rate_RB_H = TG_Dime_Rate_RB_H;
        }

        public String getTG_Dime_Rate_RB_S_H() {
            return TG_Dime_Rate_RB_S_H;
        }

        public void setTG_Dime_Rate_RB_S_H(String TG_Dime_Rate_RB_S_H) {
            this.TG_Dime_Rate_RB_S_H = TG_Dime_Rate_RB_S_H;
        }

        public String getMB_Ball() {
            return MB_Ball;
        }

        public void setMB_Ball(String MB_Ball) {
            this.MB_Ball = MB_Ball;
        }

        public String getTG_Ball() {
            return TG_Ball;
        }

        public void setTG_Ball(String TG_Ball) {
            this.TG_Ball = TG_Ball;
        }

        public String getMB_Inball_HR() {
            return MB_Inball_HR;
        }

        public void setMB_Inball_HR(String MB_Inball_HR) {
            this.MB_Inball_HR = MB_Inball_HR;
        }

        public String getTG_Inball_HR() {
            return TG_Inball_HR;
        }

        public void setTG_Inball_HR(String TG_Inball_HR) {
            this.TG_Inball_HR = TG_Inball_HR;
        }

        public String getEventid() {
            return Eventid;
        }

        public void setEventid(String Eventid) {
            this.Eventid = Eventid;
        }

        public String getHot() {
            return Hot;
        }

        public void setHot(String Hot) {
            this.Hot = Hot;
        }

        public String getPlay() {
            return Play;
        }

        public void setPlay(String Play) {
            this.Play = Play;
        }

        public String getNowSession() {
            return nowSession;
        }

        public void setNowSession(String nowSession) {
            this.nowSession = nowSession;
        }
    }
}
