package com.hgapp.a0086.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.ArrayList;
import java.util.List;

public class LeagueDetailSearchListResult implements Parcelable {

    /**
     * status : 200
     * describe : success
     * timestamp : 20180820220410
     * data : [{"gid":"3336288","league":"厄瓜多尔甲组联赛","M_Type":"","M_Time":"","M_Date":"","showretime":"下  43:20'","gnum_h":"10472","gnum_c":"10471","team_h":"国家报队","team_c":"瓜亚基尔城","strong":"H","ratio":"0 / 0.5","ratio_mb_str":"0 / 0.5","ratio_tg_str":"","ior_RH":"1.43","ior_RC":"0.57","ratio_o":"O1.5","ratio_u":"U1.5","ratio_o_str":"大1.5","ratio_u_str":"小1.5","ior_OUH":"0.39","ior_OUC":"1.86","eventid":"86CCB8CCBABCBABCBDBCBBBCBBBCB387CAC8CCCCCDCDCBA9B3","hot":"","play":"Y","more":null,"all":"20","lastestscore_h":"","lastestscore_c":"C","score_h":"0","score_c":"1"}]
     * sign : 7c52f3c2239495f41bff196e9eb8bb77
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
         * gid : 3336288
         * league : 厄瓜多尔甲组联赛
         * M_Type :
         * M_Time :
         * M_Date :
         * showretime : 下  43:20'
         * gnum_h : 10472
         * gnum_c : 10471
         * team_h : 国家报队
         * team_c : 瓜亚基尔城
         * strong : H
         * ratio : 0 / 0.5
         * ratio_mb_str : 0 / 0.5
         * ratio_tg_str :
         * ior_RH : 1.43
         * ior_RC : 0.57
         * ratio_o : O1.5
         * ratio_u : U1.5
         * ratio_o_str : 大1.5
         * ratio_u_str : 小1.5
         * ior_OUH : 0.39
         * ior_OUC : 1.86
         * eventid : 86CCB8CCBABCBABCBDBCBBBCBBBCB387CAC8CCCCCDCDCBA9B3
         * hot :
         * play : Y
         * more : null
         * all : 20
         * lastestscore_h :
         * lastestscore_c : C
         * score_h : 0
         * score_c : 1
         */

        private String gid;
        private String league;
        private String M_Type;
        private String M_Time;
        private String M_Date;
        private String showretime;
        private String gnum_h;
        private String gnum_c;
        private String team_h;
        private String team_c;
        private String strong;
        private String ratio;
        private String ratio_mb_str;
        private String ratio_tg_str;
        private String ior_RH;
        private String ior_RC;
        private String ratio_o;
        private String ratio_u;
        private String ratio_o_str;
        private String ratio_u_str;
        private String ior_OUH;
        private String ior_OUC;
        private String eventid;
        private String redcard_h;
        private String redcard_c;
        private String hot;
        private String play;
        private Object more;
        private String all;
        private String lastestscore_h;
        private String lastestscore_c;
        private String score_h;
        private String score_c;


        private String toqualify;// 会晋级开关
        private String bookings;// 罚牌开关
        private Object corners;// 角球开关
        private String goalsou;// 大小开关
        private String handicaps;// 让球开关
        private String eps;// 特优赔率开关

        private String hgid;
        private String hstrong;
        private String hratio;
        private String hratio_mb_str;
        private String hratio_tg_str;
        private String ior_HRH;//让球上半场 主
        private String ior_HRC;
        private String hratio_o;
        private String hratio_u;
        private String hratio_o_str;
        private String hratio_u_str;
        private String ior_HOUH;//得分大小 上半场 小
        private String ior_HOUC;
        private String ior_MH;//独赢主
        private String ior_MC;
        private String ior_MN;
        private String ior_HMH;//上半场 独赢主
        private String ior_HMC;
        private String ior_HMN;
        private String ior_EOO;//进球 单
        private String ior_EOE;


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

        public Object getCorners() {
            return corners;
        }

        public void setCorners(Object corners) {
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

        public String getHgid() {
            return hgid;
        }

        public void setHgid(String hgid) {
            this.hgid = hgid;
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

        public String getIor_HRH() {
            return ior_HRH;
        }

        public void setIor_HRH(String ior_HRH) {
            this.ior_HRH = ior_HRH;
        }

        public String getIor_HRC() {
            return ior_HRC;
        }

        public void setIor_HRC(String ior_HRC) {
            this.ior_HRC = ior_HRC;
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

        public String getIor_HOUH() {
            return ior_HOUH;
        }

        public void setIor_HOUH(String ior_HOUH) {
            this.ior_HOUH = ior_HOUH;
        }

        public String getIor_HOUC() {
            return ior_HOUC;
        }

        public void setIor_HOUC(String ior_HOUC) {
            this.ior_HOUC = ior_HOUC;
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

        public String getIor_HMH() {
            return ior_HMH;
        }

        public void setIor_HMH(String ior_HMH) {
            this.ior_HMH = ior_HMH;
        }

        public String getIor_HMC() {
            return ior_HMC;
        }

        public void setIor_HMC(String ior_HMC) {
            this.ior_HMC = ior_HMC;
        }

        public String getIor_HMN() {
            return ior_HMN;
        }

        public void setIor_HMN(String ior_HMN) {
            this.ior_HMN = ior_HMN;
        }

        public String getIor_EOO() {
            return ior_EOO;
        }

        public void setIor_EOO(String ior_EOO) {
            this.ior_EOO = ior_EOO;
        }

        public String getIor_EOE() {
            return ior_EOE;
        }

        public void setIor_EOE(String ior_EOE) {
            this.ior_EOE = ior_EOE;
        }

        public String getGid() {
            return gid;
        }

        public void setGid(String gid) {
            this.gid = gid;
        }

        public String getLeague() {
            return league;
        }

        public void setLeague(String league) {
            this.league = league;
        }

        public String getM_Type() {
            return M_Type;
        }

        public void setM_Type(String M_Type) {
            this.M_Type = M_Type;
        }

        public String getM_Time() {
            return M_Time;
        }

        public void setM_Time(String M_Time) {
            this.M_Time = M_Time;
        }

        public String getM_Date() {
            return M_Date;
        }

        public void setM_Date(String M_Date) {
            this.M_Date = M_Date;
        }

        public String getShowretime() {
            return showretime;
        }

        public void setShowretime(String showretime) {
            this.showretime = showretime;
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

        public String getIor_RH() {
            return ior_RH;
        }

        public void setIor_RH(String ior_RH) {
            this.ior_RH = ior_RH;
        }

        public String getIor_RC() {
            return ior_RC;
        }

        public void setIor_RC(String ior_RC) {
            this.ior_RC = ior_RC;
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

        public String getIor_OUH() {
            return ior_OUH;
        }

        public void setIor_OUH(String ior_OUH) {
            this.ior_OUH = ior_OUH;
        }

        public String getIor_OUC() {
            return ior_OUC;
        }

        public void setIor_OUC(String ior_OUC) {
            this.ior_OUC = ior_OUC;
        }

        public String getEventid() {
            return eventid;
        }

        public void setEventid(String eventid) {
            this.eventid = eventid;
        }

        public String getRedcard_h() {
            return redcard_h;
        }

        public void setRedcard_h(String redcard_h) {
            this.redcard_h = redcard_h;
        }

        public String getRedcard_c() {
            return redcard_c;
        }

        public void setRedcard_c(String redcard_c) {
            this.redcard_c = redcard_c;
        }

        public String getHot() {
            return hot;
        }

        public void setHot(String hot) {
            this.hot = hot;
        }

        public String getPlay() {
            return play;
        }

        public void setPlay(String play) {
            this.play = play;
        }

        public Object getMore() {
            return more;
        }

        public void setMore(Object more) {
            this.more = more;
        }

        public String getAll() {
            return all;
        }

        public void setAll(String all) {
            this.all = all;
        }

        public String getLastestscore_h() {
            return lastestscore_h;
        }

        public void setLastestscore_h(String lastestscore_h) {
            this.lastestscore_h = lastestscore_h;
        }

        public String getLastestscore_c() {
            return lastestscore_c;
        }

        public void setLastestscore_c(String lastestscore_c) {
            this.lastestscore_c = lastestscore_c;
        }

        public String getScore_h() {
            return score_h;
        }

        public void setScore_h(String score_h) {
            this.score_h = score_h;
        }

        public String getScore_c() {
            return score_c;
        }

        public void setScore_c(String score_c) {
            this.score_c = score_c;
        }
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.status);
        dest.writeString(this.describe);
        dest.writeString(this.timestamp);
        dest.writeString(this.sign);
        dest.writeList(this.data);
    }

    public LeagueDetailSearchListResult() {
    }

    protected LeagueDetailSearchListResult(Parcel in) {
        this.status = in.readString();
        this.describe = in.readString();
        this.timestamp = in.readString();
        this.sign = in.readString();
        this.data = new ArrayList<DataBean>();
        in.readList(this.data, DataBean.class.getClassLoader());
    }

    public static final Parcelable.Creator<LeagueDetailSearchListResult> CREATOR = new Parcelable.Creator<LeagueDetailSearchListResult>() {
        @Override
        public LeagueDetailSearchListResult createFromParcel(Parcel source) {
            return new LeagueDetailSearchListResult(source);
        }

        @Override
        public LeagueDetailSearchListResult[] newArray(int size) {
            return new LeagueDetailSearchListResult[size];
        }
    };
}
