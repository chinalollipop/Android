package com.hgapp.a0086.data;

import java.util.List;

public class ComPassSearchListResult {
    /**
     * status : 200
     * describe : success
     * timestamp : 20180913063340
     * data : [{"gid":"3381008","dategh":"09-1340844","datetime":"09-13 07:00a","league":"西澳大利亚女子超级联赛","gnum_h":"40844","gnum_c":"40843","team_h":"巴尔卡塔(女) [中]","team_c":"北部瑞德巴克斯(女)","strong":"C","ratio":"0 / 0.5","ratio_mb_str":"","ratio_tg_str":"0 / 0.5","ior_PRH":"2.06","ior_PRC":"1.74","ratio_o":"O3.5 / 4","ratio_u":"U3.5 / 4","ratio_o_str":"大3.5 / 4","ratio_u_str":"小3.5 / 4","ior_POUC":"1.84","ior_POUH":"1.94","ior_PO":"1.93","ior_PE":"1.92","ior_MH":"2.75","ior_MC":"1.95","ior_MN":"4.1","hstrong":"C","hratio":"","hratio_mb_str":"","hratio_tg_str":"","ior_HPRH":"","ior_HPRC":"","hratio_o":"","hratio_u":"","hratio_o_str":"大","hratio_u_str":"小","ior_HPOUH":"","ior_HPOUC":"","ior_HPMH":"","ior_HPMC":"","ior_HPMN":"","more":0,"gidm":"3381008","par_minlimit":3,"par_maxlimit":10}]
     * sign :
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
         * gid : 3381008
         * dategh : 09-1340844
         * datetime : 09-13 07:00a
         * league : 西澳大利亚女子超级联赛
         * gnum_h : 40844
         * gnum_c : 40843
         * team_h : 巴尔卡塔(女) [中]
         * team_c : 北部瑞德巴克斯(女)
         * strong : C
         * ratio : 0 / 0.5
         * ratio_mb_str :
         * ratio_tg_str : 0 / 0.5
         * ior_PRH : 2.06
         * ior_PRC : 1.74
         * ratio_o : O3.5 / 4
         * ratio_u : U3.5 / 4
         * ratio_o_str : 大3.5 / 4
         * ratio_u_str : 小3.5 / 4
         * ior_POUC : 1.84
         * ior_POUH : 1.94
         * ior_PO : 1.93
         * ior_PE : 1.92
         * ior_MH : 2.75
         * ior_MC : 1.95
         * ior_MN : 4.1
         * hstrong : C
         * hratio :
         * hratio_mb_str :
         * hratio_tg_str :
         * ior_HPRH :
         * ior_HPRC :
         * hratio_o :
         * hratio_u :
         * hratio_o_str : 大
         * hratio_u_str : 小
         * ior_HPOUH :
         * ior_HPOUC :
         * ior_HPMH :
         * ior_HPMC :
         * ior_HPMN :
         * more : 0
         * gidm : 3381008
         * par_minlimit : 3
         * par_maxlimit : 10
         */

        private String gid;
        private String dategh;
        private String datetime;
        private String league;
        private String gnum_h;
        private String gnum_c;
        private String team_h;
        private String team_c;
        private String strong;
        private String ratio;
        private String ratio_mb_str;
        private String ratio_tg_str;
        private String ior_PRH;
        private String ior_PRC;
        private String ratio_o;
        private String ratio_u;
        private String ratio_o_str;
        private String ratio_u_str;
        private String ior_POUC;
        private String ior_POUH;
        private String ior_PO;
        private String ior_PE;
        private String ior_MH;
        private String ior_MC;
        private String ior_MN;
        private String hstrong;
        private String hratio;
        private String hratio_mb_str;
        private String hratio_tg_str;
        private String ior_HPRH;
        private String ior_HPRC;
        private String hratio_o;
        private String hratio_u;
        private String hratio_o_str;
        private String hratio_u_str;
        private String ior_HPOUH;
        private String ior_HPOUC;
        private String ior_HPMH;
        private String ior_HPMC;
        private String ior_HPMN;
        private int more;
        private String gidm;
        private int par_minlimit;
        private int par_maxlimit;

        private int isChecked;
        private String all="";
        private String order_method="";
        private String action="";
        private String toqualify="";// 会晋级开关
        private String bookings="";// 罚牌开关
        private String corners="";// 角球开关
        private String goalsou="";// 大小开关
        private String handicaps="";// 让球开关
        private String eps="";// 特优赔率开关
        private List<LeagueDetailListDataResults.DataBean> gameData;
        public List<LeagueDetailListDataResults.DataBean> getGameData() {
            return gameData;
        }

        public void setGameData(List<LeagueDetailListDataResults.DataBean> gameData) {
            this.gameData = gameData;
        }
        public String getAll() {
            return all;
        }

        public void setAll(String all) {
            this.all = all;
        }

        public String getOrder_method() {
            return order_method;
        }

        public void setOrder_method(String order_method) {
            this.order_method = order_method;
        }

        public String getAction() {
            return action;
        }

        public void setAction(String action) {
            this.action = action;
        }

        public String getToqualify() {
            return toqualify;
        }

        public void setToqualify(String toqualify) {
            this.toqualify = toqualify;
        }

        public String getBookings() {
            return bookings;
        }

        public void setBookings(String bookings) {
            this.bookings = bookings;
        }

        public String getCorners() {
            return corners;
        }

        public void setCorners(String corners) {
            this.corners = corners;
        }

        public String getGoalsou() {
            return goalsou;
        }

        public void setGoalsou(String goalsou) {
            this.goalsou = goalsou;
        }

        public String getHandicaps() {
            return handicaps;
        }

        public void setHandicaps(String handicaps) {
            this.handicaps = handicaps;
        }

        public String getEps() {
            return eps;
        }

        public void setEps(String eps) {
            this.eps = eps;
        }

        public int getIsChecked() {
            return isChecked;
        }

        public void setIsChecked(int isChecked) {
            this.isChecked = isChecked;
        }

        public String getGid() {
            return gid;
        }

        public void setGid(String gid) {
            this.gid = gid;
        }

        public String getDategh() {
            return dategh;
        }

        public void setDategh(String dategh) {
            this.dategh = dategh;
        }

        public String getDatetime() {
            return datetime;
        }

        public void setDatetime(String datetime) {
            this.datetime = datetime;
        }

        public String getLeague() {
            return league;
        }

        public void setLeague(String league) {
            this.league = league;
        }

        public String getGnum_h() {
            return gnum_h;
        }

        public void setGnum_h(String gnum_h) {
            this.gnum_h = gnum_h;
        }

        public String getGnum_c() {
            return gnum_c;
        }

        public void setGnum_c(String gnum_c) {
            this.gnum_c = gnum_c;
        }

        public String getTeam_h() {
            return team_h;
        }

        public void setTeam_h(String team_h) {
            this.team_h = team_h;
        }

        public String getTeam_c() {
            return team_c;
        }

        public void setTeam_c(String team_c) {
            this.team_c = team_c;
        }

        public String getStrong() {
            return strong;
        }

        public void setStrong(String strong) {
            this.strong = strong;
        }

        public String getRatio() {
            return ratio;
        }

        public void setRatio(String ratio) {
            this.ratio = ratio;
        }

        public String getRatio_mb_str() {
            return ratio_mb_str;
        }

        public void setRatio_mb_str(String ratio_mb_str) {
            this.ratio_mb_str = ratio_mb_str;
        }

        public String getRatio_tg_str() {
            return ratio_tg_str;
        }

        public void setRatio_tg_str(String ratio_tg_str) {
            this.ratio_tg_str = ratio_tg_str;
        }

        public String getIor_PRH() {
            return ior_PRH;
        }

        public void setIor_PRH(String ior_PRH) {
            this.ior_PRH = ior_PRH;
        }

        public String getIor_PRC() {
            return ior_PRC;
        }

        public void setIor_PRC(String ior_PRC) {
            this.ior_PRC = ior_PRC;
        }

        public String getRatio_o() {
            return ratio_o;
        }

        public void setRatio_o(String ratio_o) {
            this.ratio_o = ratio_o;
        }

        public String getRatio_u() {
            return ratio_u;
        }

        public void setRatio_u(String ratio_u) {
            this.ratio_u = ratio_u;
        }

        public String getRatio_o_str() {
            return ratio_o_str;
        }

        public void setRatio_o_str(String ratio_o_str) {
            this.ratio_o_str = ratio_o_str;
        }

        public String getRatio_u_str() {
            return ratio_u_str;
        }

        public void setRatio_u_str(String ratio_u_str) {
            this.ratio_u_str = ratio_u_str;
        }

        public String getIor_POUC() {
            return ior_POUC;
        }

        public void setIor_POUC(String ior_POUC) {
            this.ior_POUC = ior_POUC;
        }

        public String getIor_POUH() {
            return ior_POUH;
        }

        public void setIor_POUH(String ior_POUH) {
            this.ior_POUH = ior_POUH;
        }

        public String getIor_PO() {
            return ior_PO;
        }

        public void setIor_PO(String ior_PO) {
            this.ior_PO = ior_PO;
        }

        public String getIor_PE() {
            return ior_PE;
        }

        public void setIor_PE(String ior_PE) {
            this.ior_PE = ior_PE;
        }

        public String getIor_MH() {
            return ior_MH;
        }

        public void setIor_MH(String ior_MH) {
            this.ior_MH = ior_MH;
        }

        public String getIor_MC() {
            return ior_MC;
        }

        public void setIor_MC(String ior_MC) {
            this.ior_MC = ior_MC;
        }

        public String getIor_MN() {
            return ior_MN;
        }

        public void setIor_MN(String ior_MN) {
            this.ior_MN = ior_MN;
        }

        public String getHstrong() {
            return hstrong;
        }

        public void setHstrong(String hstrong) {
            this.hstrong = hstrong;
        }

        public String getHratio() {
            return hratio;
        }

        public void setHratio(String hratio) {
            this.hratio = hratio;
        }

        public String getHratio_mb_str() {
            return hratio_mb_str;
        }

        public void setHratio_mb_str(String hratio_mb_str) {
            this.hratio_mb_str = hratio_mb_str;
        }

        public String getHratio_tg_str() {
            return hratio_tg_str;
        }

        public void setHratio_tg_str(String hratio_tg_str) {
            this.hratio_tg_str = hratio_tg_str;
        }

        public String getIor_HPRH() {
            return ior_HPRH;
        }

        public void setIor_HPRH(String ior_HPRH) {
            this.ior_HPRH = ior_HPRH;
        }

        public String getIor_HPRC() {
            return ior_HPRC;
        }

        public void setIor_HPRC(String ior_HPRC) {
            this.ior_HPRC = ior_HPRC;
        }

        public String getHratio_o() {
            return hratio_o;
        }

        public void setHratio_o(String hratio_o) {
            this.hratio_o = hratio_o;
        }

        public String getHratio_u() {
            return hratio_u;
        }

        public void setHratio_u(String hratio_u) {
            this.hratio_u = hratio_u;
        }

        public String getHratio_o_str() {
            return hratio_o_str;
        }

        public void setHratio_o_str(String hratio_o_str) {
            this.hratio_o_str = hratio_o_str;
        }

        public String getHratio_u_str() {
            return hratio_u_str;
        }

        public void setHratio_u_str(String hratio_u_str) {
            this.hratio_u_str = hratio_u_str;
        }

        public String getIor_HPOUH() {
            return ior_HPOUH;
        }

        public void setIor_HPOUH(String ior_HPOUH) {
            this.ior_HPOUH = ior_HPOUH;
        }

        public String getIor_HPOUC() {
            return ior_HPOUC;
        }

        public void setIor_HPOUC(String ior_HPOUC) {
            this.ior_HPOUC = ior_HPOUC;
        }

        public String getIor_HPMH() {
            return ior_HPMH;
        }

        public void setIor_HPMH(String ior_HPMH) {
            this.ior_HPMH = ior_HPMH;
        }

        public String getIor_HPMC() {
            return ior_HPMC;
        }

        public void setIor_HPMC(String ior_HPMC) {
            this.ior_HPMC = ior_HPMC;
        }

        public String getIor_HPMN() {
            return ior_HPMN;
        }

        public void setIor_HPMN(String ior_HPMN) {
            this.ior_HPMN = ior_HPMN;
        }

        public int getMore() {
            return more;
        }

        public void setMore(int more) {
            this.more = more;
        }

        public String getGidm() {
            return gidm;
        }

        public void setGidm(String gidm) {
            this.gidm = gidm;
        }

        public int getPar_minlimit() {
            return par_minlimit;
        }

        public void setPar_minlimit(int par_minlimit) {
            this.par_minlimit = par_minlimit;
        }

        public int getPar_maxlimit() {
            return par_maxlimit;
        }

        public void setPar_maxlimit(int par_maxlimit) {
            this.par_maxlimit = par_maxlimit;
        }
    }
}
