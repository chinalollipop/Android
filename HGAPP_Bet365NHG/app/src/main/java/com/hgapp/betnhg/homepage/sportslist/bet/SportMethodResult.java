package com.hgapp.betnhg.homepage.sportslist.bet;

import java.util.List;

public class SportMethodResult {
    /**
     * MID : ["3325314","3325314"]
     * Type : FT
     * M_Time : 04:30a
     * M_Type : 1
     * MB_MID : 71574
     * TG_MID : 71573
     * MB_Team : 佩西加雅加达
     * TG_Team : PSMS棉兰
     * M_League : 印尼甲组联赛
     * ShowTypeR : H
     * MB_Win_Rate : 1.35
     * TG_Win_Rate : 6.31
     * M_Flat_Rate : 4.56
     * MB_Win_Rate_H : 1.83
     * TG_Win_Rate_H : 6.41
     * M_Flat_Rate_H : 2.41
     * rq_dan_list : [{"M_LetB":"1.5","MB_LetB_Rate":"1.08","TG_LetB_Rate":"0.78"}]
     * rq_ban_list : [{"M_LetB_H":"0.5","MB_LetB_Rate_H":"0.83","TG_LetB_Rate_H":"1.01"}]
     * dx_dan_list : [{"MB_Dime":"O2.5 / 3","TG_Dime":"U2.5 / 3","MB_Dime_Rate":"0.82","TG_Dime_Rate":"1.02"}]
     * dx_ban_list : [{"MB_Dime_H":"O1 / 1.5","TG_Dime_H":"U1 / 1.5","MB_Dime_Rate_H":"1.01","TG_Dime_Rate_H":"0.81"}]
     * ds_list : [{"S_Single_Rate":"1.98","S_Double_Rate":"1.93"}]
     * ShowTypeHR : H
     * PD_Show : 0
     * HPD_Show : 0
     * T_Show : 0
     * F_Show : 0
     * Eventid :
     * Hot :
     * Play :
     */

    private String Type;
    private String M_Time;
    private String M_Type;
    private String MB_MID;
    private String TG_MID;
    private String MB_Team;
    private String TG_Team;
    private String M_League;
    private String ShowTypeR;
    private String MB_Win_Rate;
    private String TG_Win_Rate;
    private String M_Flat_Rate;
    private String MB_Win_Rate_H;
    private String TG_Win_Rate_H;
    private String M_Flat_Rate_H;
    private String ShowTypeHR;
    private String PD_Show;
    private String HPD_Show;
    private String T_Show;
    private String F_Show;
    private String Eventid;
    private String Hot;
    private String Play;
    private List<String> MID;
    private List<RqDanListBean> rq_dan_list;
    private List<RqBanListBean> rq_ban_list;
    private List<DxDanListBean> dx_dan_list;
    private List<DxBanListBean> dx_ban_list;
    private List<DsListBean> ds_list;

    public String getType() {
        return Type;
    }

    public void setType(String Type) {
        this.Type = Type;
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

    public String getShowTypeR() {
        return ShowTypeR;
    }

    public void setShowTypeR(String ShowTypeR) {
        this.ShowTypeR = ShowTypeR;
    }

    public String getMB_Win_Rate() {
        return MB_Win_Rate;
    }

    public void setMB_Win_Rate(String MB_Win_Rate) {
        this.MB_Win_Rate = MB_Win_Rate;
    }

    public String getTG_Win_Rate() {
        return TG_Win_Rate;
    }

    public void setTG_Win_Rate(String TG_Win_Rate) {
        this.TG_Win_Rate = TG_Win_Rate;
    }

    public String getM_Flat_Rate() {
        return M_Flat_Rate;
    }

    public void setM_Flat_Rate(String M_Flat_Rate) {
        this.M_Flat_Rate = M_Flat_Rate;
    }

    public String getMB_Win_Rate_H() {
        return MB_Win_Rate_H;
    }

    public void setMB_Win_Rate_H(String MB_Win_Rate_H) {
        this.MB_Win_Rate_H = MB_Win_Rate_H;
    }

    public String getTG_Win_Rate_H() {
        return TG_Win_Rate_H;
    }

    public void setTG_Win_Rate_H(String TG_Win_Rate_H) {
        this.TG_Win_Rate_H = TG_Win_Rate_H;
    }

    public String getM_Flat_Rate_H() {
        return M_Flat_Rate_H;
    }

    public void setM_Flat_Rate_H(String M_Flat_Rate_H) {
        this.M_Flat_Rate_H = M_Flat_Rate_H;
    }

    public String getShowTypeHR() {
        return ShowTypeHR;
    }

    public void setShowTypeHR(String ShowTypeHR) {
        this.ShowTypeHR = ShowTypeHR;
    }

    public String getPD_Show() {
        return PD_Show;
    }

    public void setPD_Show(String PD_Show) {
        this.PD_Show = PD_Show;
    }

    public String getHPD_Show() {
        return HPD_Show;
    }

    public void setHPD_Show(String HPD_Show) {
        this.HPD_Show = HPD_Show;
    }

    public String getT_Show() {
        return T_Show;
    }

    public void setT_Show(String T_Show) {
        this.T_Show = T_Show;
    }

    public String getF_Show() {
        return F_Show;
    }

    public void setF_Show(String F_Show) {
        this.F_Show = F_Show;
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

    public List<String> getMID() {
        return MID;
    }

    public void setMID(List<String> MID) {
        this.MID = MID;
    }

    public List<RqDanListBean> getRq_dan_list() {
        return rq_dan_list;
    }

    public void setRq_dan_list(List<RqDanListBean> rq_dan_list) {
        this.rq_dan_list = rq_dan_list;
    }

    public List<RqBanListBean> getRq_ban_list() {
        return rq_ban_list;
    }

    public void setRq_ban_list(List<RqBanListBean> rq_ban_list) {
        this.rq_ban_list = rq_ban_list;
    }

    public List<DxDanListBean> getDx_dan_list() {
        return dx_dan_list;
    }

    public void setDx_dan_list(List<DxDanListBean> dx_dan_list) {
        this.dx_dan_list = dx_dan_list;
    }

    public List<DxBanListBean> getDx_ban_list() {
        return dx_ban_list;
    }

    public void setDx_ban_list(List<DxBanListBean> dx_ban_list) {
        this.dx_ban_list = dx_ban_list;
    }

    public List<DsListBean> getDs_list() {
        return ds_list;
    }

    public void setDs_list(List<DsListBean> ds_list) {
        this.ds_list = ds_list;
    }

    public static class RqDanListBean {
        /**
         * M_LetB : 1.5
         * MB_LetB_Rate : 1.08
         * TG_LetB_Rate : 0.78
         */

        private String M_LetB;
        private String MB_LetB_Rate;
        private String TG_LetB_Rate;

        private String ShowTypeR;

        public String getShowTypeR() {
            return ShowTypeR;
        }

        public void setShowTypeR(String showTypeR) {
            ShowTypeR = showTypeR;
        }

        public String getM_LetB() {
            return M_LetB;
        }

        public void setM_LetB(String M_LetB) {
            this.M_LetB = M_LetB;
        }

        public String getMB_LetB_Rate() {
            return MB_LetB_Rate;
        }

        public void setMB_LetB_Rate(String MB_LetB_Rate) {
            this.MB_LetB_Rate = MB_LetB_Rate;
        }

        public String getTG_LetB_Rate() {
            return TG_LetB_Rate;
        }

        public void setTG_LetB_Rate(String TG_LetB_Rate) {
            this.TG_LetB_Rate = TG_LetB_Rate;
        }
    }

    public static class RqBanListBean {
        /**
         * M_LetB_H : 0.5
         * MB_LetB_Rate_H : 0.83
         * TG_LetB_Rate_H : 1.01
         */

        private String M_LetB_H;
        private String MB_LetB_Rate_H;
        private String TG_LetB_Rate_H;
        private String ShowTypeHR;

        public String getShowTypeHR() {
            return ShowTypeHR;
        }

        public void setShowTypeHR(String showTypeHR) {
            ShowTypeHR = showTypeHR;
        }

        public String getM_LetB_H() {
            return M_LetB_H;
        }

        public void setM_LetB_H(String M_LetB_H) {
            this.M_LetB_H = M_LetB_H;
        }

        public String getMB_LetB_Rate_H() {
            return MB_LetB_Rate_H;
        }

        public void setMB_LetB_Rate_H(String MB_LetB_Rate_H) {
            this.MB_LetB_Rate_H = MB_LetB_Rate_H;
        }

        public String getTG_LetB_Rate_H() {
            return TG_LetB_Rate_H;
        }

        public void setTG_LetB_Rate_H(String TG_LetB_Rate_H) {
            this.TG_LetB_Rate_H = TG_LetB_Rate_H;
        }
    }

    public static class DxDanListBean {
        /**
         * MB_Dime : O2.5 / 3
         * TG_Dime : U2.5 / 3
         * MB_Dime_Rate : 0.82
         * TG_Dime_Rate : 1.02
         */

        private String MB_Dime;
        private String TG_Dime;
        private String MB_Dime_Rate;
        private String TG_Dime_Rate;

        public String getMB_Dime() {
            return MB_Dime;
        }

        public void setMB_Dime(String MB_Dime) {
            this.MB_Dime = MB_Dime;
        }

        public String getTG_Dime() {
            return TG_Dime;
        }

        public void setTG_Dime(String TG_Dime) {
            this.TG_Dime = TG_Dime;
        }

        public String getMB_Dime_Rate() {
            return MB_Dime_Rate;
        }

        public void setMB_Dime_Rate(String MB_Dime_Rate) {
            this.MB_Dime_Rate = MB_Dime_Rate;
        }

        public String getTG_Dime_Rate() {
            return TG_Dime_Rate;
        }

        public void setTG_Dime_Rate(String TG_Dime_Rate) {
            this.TG_Dime_Rate = TG_Dime_Rate;
        }
    }

    public static class DxBanListBean {
        /**
         * MB_Dime_H : O1 / 1.5
         * TG_Dime_H : U1 / 1.5
         * MB_Dime_Rate_H : 1.01
         * TG_Dime_Rate_H : 0.81
         */

        private String MB_Dime_H;
        private String TG_Dime_H;
        private String MB_Dime_Rate_H;
        private String TG_Dime_Rate_H;

        public String getMB_Dime_H() {
            return MB_Dime_H;
        }

        public void setMB_Dime_H(String MB_Dime_H) {
            this.MB_Dime_H = MB_Dime_H;
        }

        public String getTG_Dime_H() {
            return TG_Dime_H;
        }

        public void setTG_Dime_H(String TG_Dime_H) {
            this.TG_Dime_H = TG_Dime_H;
        }

        public String getMB_Dime_Rate_H() {
            return MB_Dime_Rate_H;
        }

        public void setMB_Dime_Rate_H(String MB_Dime_Rate_H) {
            this.MB_Dime_Rate_H = MB_Dime_Rate_H;
        }

        public String getTG_Dime_Rate_H() {
            return TG_Dime_Rate_H;
        }

        public void setTG_Dime_Rate_H(String TG_Dime_Rate_H) {
            this.TG_Dime_Rate_H = TG_Dime_Rate_H;
        }
    }

    public static class DsListBean {
        /**
         * S_Single_Rate : 1.98
         * S_Double_Rate : 1.93
         */

        private String S_Single_Rate;
        private String S_Double_Rate;

        public String getS_Single_Rate() {
            return S_Single_Rate;
        }

        public void setS_Single_Rate(String S_Single_Rate) {
            this.S_Single_Rate = S_Single_Rate;
        }

        public String getS_Double_Rate() {
            return S_Double_Rate;
        }

        public void setS_Double_Rate(String S_Double_Rate) {
            this.S_Double_Rate = S_Double_Rate;
        }
    }
}
