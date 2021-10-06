package com.hgapp.bet365.data;

import com.google.gson.annotations.SerializedName;

import java.util.List;

public class LeagueDetailListDataResults {
    private String status;
    private String describe;
    private String timestamp;
    private String sign;
    private List<LeagueDetailSearchListResult.DataBean> data;

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

    public List<LeagueDetailSearchListResult.DataBean> getData() {
        return data;
    }

    public void setData(List<LeagueDetailSearchListResult.DataBean> data) {
        this.data = data;
    }

    public static class DataBean {

        @SerializedName("@attributes")
        private String _$Attributes185; // FIXME check this code
        private String gtype  = "";
        private String gid = "";
        private String gid_fs = "";
        private String gidm = "";
        private String datetime = "";
        private String re_time = "";
        private String league = "";
        private String gnum_h = "";
        private String gnum_c = "";
        private String team_h = "";
        private String team_c = "";
        private String session = "";
        private String ms = "";
        private String ptype = "";
        private String gopen = "";
        private String recv = "";
        private String strong = "";
        private String sw_RE = "";
        private String ratio_re = "";
        private String ior_REH = "";
        private String ior_REC = "";
        private String sw_ROU = "";
        private String ratio_rouo = "";
        private String ratio_rouu = "";
        private String ior_ROUH = "";
        private String ior_ROUC = "";
        private String sw_RM = "";
        private String ior_RMH = "";
        private String ior_RMC = "";
        private String sw_REO = "";
        private String ior_REOO = "";
        private String ior_REOE = "";
        private String sw_ROUH = "";
        private String ratio_rouho = "";
        private String ratio_rouhu = "";
        private String ior_ROUHO = "";
        private String ior_ROUHU = "";
        private String sw_ROUC = "";
        private String ratio_rouco = "";
        private String ratio_roucu = "";
        private String ior_ROUCO = "";
        private String ior_ROUCU = "";
        private String sw_RPD = "";
        private String ior_RPDH0 = "";
        private String ior_RPDH1 = "";
        private String ior_RPDH2 = "";
        private String ior_RPDH3 = "";
        private String ior_RPDH4 = "";
        private String ior_RPDC0 = "";
        private String ior_RPDC1 = "";
        private String ior_RPDC2 = "";
        private String ior_RPDC3 = "";
        private String ior_RPDC4 = "";
        private String sc_FT_A = "";
        private String sc_FT_H = "";
        private String sc_OT_A = "";
        private String sc_OT_H = "";
        private String sc_H2_A = "";
        private String sc_H2_H = "";
        private String sc_H1_A = "";
        private String sc_H1_H = "";
        private String sc_Q4_A = "";
        private String sc_Q4_H = "";
        private String sc_Q3_A = "";
        private String sc_Q3_H = "";
        private String sc_Q2_A = "";
        private String sc_Q2_H = "";
        private String sc_Q1_A = "";
        private String sc_Q1_H = "";
        private String se_now = "";
        private String se_type = "";
        private String t_status = "";
        private String t_count = "";
        private String sc_new = "";
        private String HalfTime = "";
        private String score_h = "";
        private String score_c = "";
        private String midfield = "";
        private String Live = "";
        private String eventid = "";
        private String eventid_phone = "";
        private String hot = "";
        private String center_tv = "";
        private String ior_RH = "";
        private String ior_RC = "";

        private String ratio_o = "";//大
        private String ratio_u = "";
        private String ior_OUC = "";//大
        private String ior_OUH = "";
        private String ratio_ho = "";
        private String ratio_hu = "";

        private String ratio = "";
        private String hratio = "";
        private String hstrong = "";
        private String description = "";
        private String hratio_mb_str= "";
        private String hratio_tg_str= "";
        private String ior_HRH= "";//让球上半场 主
        private String ior_HRC= "";
        private String hratio_o= "";
        private String hratio_u= "";
        private String hratio_o_str= "";
        private String hratio_u_str= "";
        private String ior_HOUH= "";//得分大小 上半场 小
        private String ior_HOUC= "";
        private String ior_MH= "";//独赢主
        private String ior_MC= "";
        private String ior_MN= "";
        private String ior_HMH= "";//上半场 独赢主
        private String ior_HMC= "";
        private String ior_HMN= "";
        private String ior_EOO= "";//进球 单
        private String ior_EOE= "";
        private String ratio_mb_str= "";
        private String ratio_tg_str= "";
        private boolean ior_RHCheck = false;
        private boolean ior_RCCheck = false;
        private boolean ior_HRHCheck = false;
        private boolean ior_HRCCheck = false;
        private boolean ior_OUCCheck = false;//大
        private boolean ior_OUHCheck = false;
        private boolean ior_HOUHCheck = false;//得分大小 上半场 小
        private boolean ior_HOUCCheck = false;

        private String ratio_hre = "";
        private String ior_HREH = "";
        private String ior_HREC = "";
        private String ratio_hrouo = "";
        private String ior_HROUH = "";
        private String ior_HROUC = "";
        private String ior_RMN = "";
        private String ior_HRMH= "";//上半场 独赢主
        private String ior_HRMC= "";
        private String ior_HRMN= "";

        public String getIor_HRMH() {
            return ior_HRMH;
        }

        public void setIor_HRMH(String ior_HRMH) {
            this.ior_HRMH = ior_HRMH;
        }

        public String getIor_HRMC() {
            return ior_HRMC;
        }

        public void setIor_HRMC(String ior_HRMC) {
            this.ior_HRMC = ior_HRMC;
        }

        public String getIor_HRMN() {
            return ior_HRMN;
        }

        public void setIor_HRMN(String ior_HRMN) {
            this.ior_HRMN = ior_HRMN;
        }

        public String getIor_RMN() {
            return ior_RMN;
        }

        public void setIor_RMN(String ior_RMN) {
            this.ior_RMN = ior_RMN;
        }

        public String getRatio_hre() {
            return ratio_hre;
        }

        public void setRatio_hre(String ratio_hre) {
            this.ratio_hre = ratio_hre;
        }

        public String getIor_HREH() {
            return ior_HREH;
        }

        public void setIor_HREH(String ior_HREH) {
            this.ior_HREH = ior_HREH;
        }

        public String getIor_HREC() {
            return ior_HREC;
        }

        public void setIor_HREC(String ior_HREC) {
            this.ior_HREC = ior_HREC;
        }

        public String getRatio_hrouo() {
            return ratio_hrouo;
        }

        public void setRatio_hrouo(String ratio_hrouo) {
            this.ratio_hrouo = ratio_hrouo;
        }

        public String getIor_HROUH() {
            return ior_HROUH;
        }

        public void setIor_HROUH(String ior_HROUH) {
            this.ior_HROUH = ior_HROUH;
        }

        public String getIor_HROUC() {
            return ior_HROUC;
        }

        public void setIor_HROUC(String ior_HROUC) {
            this.ior_HROUC = ior_HROUC;
        }

        public boolean isIor_OUCCheck() {
            return ior_OUCCheck;
        }

        public void setIor_OUCCheck(boolean ior_OUCCheck) {
            this.ior_OUCCheck = ior_OUCCheck;
        }

        public boolean isIor_OUHCheck() {
            return ior_OUHCheck;
        }

        public void setIor_OUHCheck(boolean ior_OUHCheck) {
            this.ior_OUHCheck = ior_OUHCheck;
        }

        public boolean isIor_HOUHCheck() {
            return ior_HOUHCheck;
        }

        public void setIor_HOUHCheck(boolean ior_HOUHCheck) {
            this.ior_HOUHCheck = ior_HOUHCheck;
        }

        public boolean isIor_HOUCCheck() {
            return ior_HOUCCheck;
        }

        public void setIor_HOUCCheck(boolean ior_HOUCCheck) {
            this.ior_HOUCCheck = ior_HOUCCheck;
        }

        public boolean isIor_RHCheck() {
            return ior_RHCheck;
        }

        public void setIor_RHCheck(boolean ior_RHCheck) {
            this.ior_RHCheck = ior_RHCheck;
        }

        public boolean isIor_RCCheck() {
            return ior_RCCheck;
        }

        public void setIor_RCCheck(boolean ior_RCCheck) {
            this.ior_RCCheck = ior_RCCheck;
        }

        public boolean isIor_HRHCheck() {
            return ior_HRHCheck;
        }

        public void setIor_HRHCheck(boolean ior_HRHCheck) {
            this.ior_HRHCheck = ior_HRHCheck;
        }

        public boolean isIor_HRCCheck() {
            return ior_HRCCheck;
        }

        public void setIor_HRCCheck(boolean ior_HRCCheck) {
            this.ior_HRCCheck = ior_HRCCheck;
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

        public String getDescription() {
            return description;
        }

        public void setDescription(String description) {
            this.description = description;
        }

        public String getRatio() {
            return ratio;
        }

        public void setRatio(String ratio) {
            this.ratio = ratio;
        }

        public String getHratio() {
            return hratio;
        }

        public void setHratio(String hratio) {
            this.hratio = hratio;
        }

        public String getHstrong() {
            return hstrong;
        }

        public void setHstrong(String hstrong) {
            this.hstrong = hstrong;
        }

        public String getGid_fs() {
            return gid_fs;
        }

        public void setGid_fs(String gid_fs) {
            this.gid_fs = gid_fs;
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

        public String getIor_OUC() {
            return ior_OUC;
        }

        public void setIor_OUC(String ior_OUC) {
            this.ior_OUC = ior_OUC;
        }

        public String getIor_OUH() {
            return ior_OUH;
        }

        public void setIor_OUH(String ior_OUH) {
            this.ior_OUH = ior_OUH;
        }

        public String getRatio_ho() {
            return ratio_ho;
        }

        public void setRatio_ho(String ratio_ho) {
            this.ratio_ho = ratio_ho;
        }

        public String getRatio_hu() {
            return ratio_hu;
        }

        public void setRatio_hu(String ratio_hu) {
            this.ratio_hu = ratio_hu;
        }

        public String getIor_HOUC() {
            return ior_HOUC;
        }

        public void setIor_HOUC(String ior_HOUC) {
            this.ior_HOUC = ior_HOUC;
        }

        public String getIor_HOUH() {
            return ior_HOUH;
        }

        public void setIor_HOUH(String ior_HOUH) {
            this.ior_HOUH = ior_HOUH;
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

        public String get_$Attributes185() {
            return _$Attributes185;
        }

        public void set_$Attributes185(String _$Attributes185) {
            this._$Attributes185 = _$Attributes185;
        }

        public String getGtype() {
            return gtype;
        }

        public void setGtype(String gtype) {
            this.gtype = gtype;
        }

        public String getGid() {
            return gid;
        }

        public void setGid(String gid) {
            this.gid = gid;
        }

        public String getGidm() {
            return gidm;
        }

        public void setGidm(String gidm) {
            this.gidm = gidm;
        }

        public String getDatetime() {
            return datetime;
        }

        public void setDatetime(String datetime) {
            this.datetime = datetime;
        }

        public String getRe_time() {
            return re_time;
        }

        public void setRe_time(String re_time) {
            this.re_time = re_time;
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

        public String getSession() {
            return session;
        }

        public void setSession(String session) {
            this.session = session;
        }

        public String getMs() {
            return ms;
        }

        public void setMs(String ms) {
            this.ms = ms;
        }

        public String getPtype() {
            return ptype;
        }

        public void setPtype(String ptype) {
            this.ptype = ptype;
        }

        public String getGopen() {
            return gopen;
        }

        public void setGopen(String gopen) {
            this.gopen = gopen;
        }

        public String getRecv() {
            return recv;
        }

        public void setRecv(String recv) {
            this.recv = recv;
        }

        public String getStrong() {
            return strong;
        }

        public void setStrong(String strong) {
            this.strong = strong;
        }

        public String getSw_RE() {
            return sw_RE;
        }

        public void setSw_RE(String sw_RE) {
            this.sw_RE = sw_RE;
        }

        public String getRatio_re() {
            return ratio_re;
        }

        public void setRatio_re(String ratio_re) {
            this.ratio_re = ratio_re;
        }

        public String getIor_REH() {
            return ior_REH;
        }

        public void setIor_REH(String ior_REH) {
            this.ior_REH = ior_REH;
        }

        public String getIor_REC() {
            return ior_REC;
        }

        public void setIor_REC(String ior_REC) {
            this.ior_REC = ior_REC;
        }

        public String getSw_ROU() {
            return sw_ROU;
        }

        public void setSw_ROU(String sw_ROU) {
            this.sw_ROU = sw_ROU;
        }

        public String getRatio_rouo() {
            return ratio_rouo;
        }

        public void setRatio_rouo(String ratio_rouo) {
            this.ratio_rouo = ratio_rouo;
        }

        public String getRatio_rouu() {
            return ratio_rouu;
        }

        public void setRatio_rouu(String ratio_rouu) {
            this.ratio_rouu = ratio_rouu;
        }

        public String getIor_ROUH() {
            return ior_ROUH;
        }

        public void setIor_ROUH(String ior_ROUH) {
            this.ior_ROUH = ior_ROUH;
        }

        public String getIor_ROUC() {
            return ior_ROUC;
        }

        public void setIor_ROUC(String ior_ROUC) {
            this.ior_ROUC = ior_ROUC;
        }

        public String getSw_RM() {
            return sw_RM;
        }

        public void setSw_RM(String sw_RM) {
            this.sw_RM = sw_RM;
        }

        public String getIor_RMH() {
            return ior_RMH;
        }

        public void setIor_RMH(String ior_RMH) {
            this.ior_RMH = ior_RMH;
        }

        public String getIor_RMC() {
            return ior_RMC;
        }

        public void setIor_RMC(String ior_RMC) {
            this.ior_RMC = ior_RMC;
        }

        public String getSw_REO() {
            return sw_REO;
        }

        public void setSw_REO(String sw_REO) {
            this.sw_REO = sw_REO;
        }

        public String getIor_REOO() {
            return ior_REOO;
        }

        public void setIor_REOO(String ior_REOO) {
            this.ior_REOO = ior_REOO;
        }

        public String getIor_REOE() {
            return ior_REOE;
        }

        public void setIor_REOE(String ior_REOE) {
            this.ior_REOE = ior_REOE;
        }

        public String getSw_ROUH() {
            return sw_ROUH;
        }

        public void setSw_ROUH(String sw_ROUH) {
            this.sw_ROUH = sw_ROUH;
        }

        public String getRatio_rouho() {
            return ratio_rouho;
        }

        public void setRatio_rouho(String ratio_rouho) {
            this.ratio_rouho = ratio_rouho;
        }

        public String getRatio_rouhu() {
            return ratio_rouhu;
        }

        public void setRatio_rouhu(String ratio_rouhu) {
            this.ratio_rouhu = ratio_rouhu;
        }

        public String getIor_ROUHO() {
            return ior_ROUHO;
        }

        public void setIor_ROUHO(String ior_ROUHO) {
            this.ior_ROUHO = ior_ROUHO;
        }

        public String getIor_ROUHU() {
            return ior_ROUHU;
        }

        public void setIor_ROUHU(String ior_ROUHU) {
            this.ior_ROUHU = ior_ROUHU;
        }

        public String getSw_ROUC() {
            return sw_ROUC;
        }

        public void setSw_ROUC(String sw_ROUC) {
            this.sw_ROUC = sw_ROUC;
        }

        public String getRatio_rouco() {
            return ratio_rouco;
        }

        public void setRatio_rouco(String ratio_rouco) {
            this.ratio_rouco = ratio_rouco;
        }

        public String getRatio_roucu() {
            return ratio_roucu;
        }

        public void setRatio_roucu(String ratio_roucu) {
            this.ratio_roucu = ratio_roucu;
        }

        public String getIor_ROUCO() {
            return ior_ROUCO;
        }

        public void setIor_ROUCO(String ior_ROUCO) {
            this.ior_ROUCO = ior_ROUCO;
        }

        public String getIor_ROUCU() {
            return ior_ROUCU;
        }

        public void setIor_ROUCU(String ior_ROUCU) {
            this.ior_ROUCU = ior_ROUCU;
        }

        public String getSw_RPD() {
            return sw_RPD;
        }

        public void setSw_RPD(String sw_RPD) {
            this.sw_RPD = sw_RPD;
        }

        public String getIor_RPDH0() {
            return ior_RPDH0;
        }

        public void setIor_RPDH0(String ior_RPDH0) {
            this.ior_RPDH0 = ior_RPDH0;
        }

        public String getIor_RPDH1() {
            return ior_RPDH1;
        }

        public void setIor_RPDH1(String ior_RPDH1) {
            this.ior_RPDH1 = ior_RPDH1;
        }

        public String getIor_RPDH2() {
            return ior_RPDH2;
        }

        public void setIor_RPDH2(String ior_RPDH2) {
            this.ior_RPDH2 = ior_RPDH2;
        }

        public String getIor_RPDH3() {
            return ior_RPDH3;
        }

        public void setIor_RPDH3(String ior_RPDH3) {
            this.ior_RPDH3 = ior_RPDH3;
        }

        public String getIor_RPDH4() {
            return ior_RPDH4;
        }

        public void setIor_RPDH4(String ior_RPDH4) {
            this.ior_RPDH4 = ior_RPDH4;
        }

        public String getIor_RPDC0() {
            return ior_RPDC0;
        }

        public void setIor_RPDC0(String ior_RPDC0) {
            this.ior_RPDC0 = ior_RPDC0;
        }

        public String getIor_RPDC1() {
            return ior_RPDC1;
        }

        public void setIor_RPDC1(String ior_RPDC1) {
            this.ior_RPDC1 = ior_RPDC1;
        }

        public String getIor_RPDC2() {
            return ior_RPDC2;
        }

        public void setIor_RPDC2(String ior_RPDC2) {
            this.ior_RPDC2 = ior_RPDC2;
        }

        public String getIor_RPDC3() {
            return ior_RPDC3;
        }

        public void setIor_RPDC3(String ior_RPDC3) {
            this.ior_RPDC3 = ior_RPDC3;
        }

        public String getIor_RPDC4() {
            return ior_RPDC4;
        }

        public void setIor_RPDC4(String ior_RPDC4) {
            this.ior_RPDC4 = ior_RPDC4;
        }

        public String getSc_FT_A() {
            return sc_FT_A;
        }

        public void setSc_FT_A(String sc_FT_A) {
            this.sc_FT_A = sc_FT_A;
        }

        public String getSc_FT_H() {
            return sc_FT_H;
        }

        public void setSc_FT_H(String sc_FT_H) {
            this.sc_FT_H = sc_FT_H;
        }

        public String getSc_OT_A() {
            return sc_OT_A;
        }

        public void setSc_OT_A(String sc_OT_A) {
            this.sc_OT_A = sc_OT_A;
        }

        public String getSc_OT_H() {
            return sc_OT_H;
        }

        public void setSc_OT_H(String sc_OT_H) {
            this.sc_OT_H = sc_OT_H;
        }

        public String getSc_H2_A() {
            return sc_H2_A;
        }

        public void setSc_H2_A(String sc_H2_A) {
            this.sc_H2_A = sc_H2_A;
        }

        public String getSc_H2_H() {
            return sc_H2_H;
        }

        public void setSc_H2_H(String sc_H2_H) {
            this.sc_H2_H = sc_H2_H;
        }

        public String getSc_H1_A() {
            return sc_H1_A;
        }

        public void setSc_H1_A(String sc_H1_A) {
            this.sc_H1_A = sc_H1_A;
        }

        public String getSc_H1_H() {
            return sc_H1_H;
        }

        public void setSc_H1_H(String sc_H1_H) {
            this.sc_H1_H = sc_H1_H;
        }

        public String getSc_Q4_A() {
            return sc_Q4_A;
        }

        public void setSc_Q4_A(String sc_Q4_A) {
            this.sc_Q4_A = sc_Q4_A;
        }

        public String getSc_Q4_H() {
            return sc_Q4_H;
        }

        public void setSc_Q4_H(String sc_Q4_H) {
            this.sc_Q4_H = sc_Q4_H;
        }

        public String getSc_Q3_A() {
            return sc_Q3_A;
        }

        public void setSc_Q3_A(String sc_Q3_A) {
            this.sc_Q3_A = sc_Q3_A;
        }

        public String getSc_Q3_H() {
            return sc_Q3_H;
        }

        public void setSc_Q3_H(String sc_Q3_H) {
            this.sc_Q3_H = sc_Q3_H;
        }

        public String getSc_Q2_A() {
            return sc_Q2_A;
        }

        public void setSc_Q2_A(String sc_Q2_A) {
            this.sc_Q2_A = sc_Q2_A;
        }

        public String getSc_Q2_H() {
            return sc_Q2_H;
        }

        public void setSc_Q2_H(String sc_Q2_H) {
            this.sc_Q2_H = sc_Q2_H;
        }

        public String getSc_Q1_A() {
            return sc_Q1_A;
        }

        public void setSc_Q1_A(String sc_Q1_A) {
            this.sc_Q1_A = sc_Q1_A;
        }

        public String getSc_Q1_H() {
            return sc_Q1_H;
        }

        public void setSc_Q1_H(String sc_Q1_H) {
            this.sc_Q1_H = sc_Q1_H;
        }

        public String getSe_now() {
            return se_now;
        }

        public void setSe_now(String se_now) {
            this.se_now = se_now;
        }

        public String getSe_type() {
            return se_type;
        }

        public void setSe_type(String se_type) {
            this.se_type = se_type;
        }

        public String getT_status() {
            return t_status;
        }

        public void setT_status(String t_status) {
            this.t_status = t_status;
        }

        public String getT_count() {
            return t_count;
        }

        public void setT_count(String t_count) {
            this.t_count = t_count;
        }

        public String getSc_new() {
            return sc_new;
        }

        public void setSc_new(String sc_new) {
            this.sc_new = sc_new;
        }

        public String getHalfTime() {
            return HalfTime;
        }

        public void setHalfTime(String HalfTime) {
            this.HalfTime = HalfTime;
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

        public String getMidfield() {
            return midfield;
        }

        public void setMidfield(String midfield) {
            this.midfield = midfield;
        }

        public String getLive() {
            return Live;
        }

        public void setLive(String Live) {
            this.Live = Live;
        }

        public String getEventid() {
            return eventid;
        }

        public void setEventid(String eventid) {
            this.eventid = eventid;
        }

        public String getEventid_phone() {
            return eventid_phone;
        }

        public void setEventid_phone(String eventid_phone) {
            this.eventid_phone = eventid_phone;
        }

        public String getHot() {
            return hot;
        }

        public void setHot(String hot) {
            this.hot = hot;
        }

        public String getCenter_tv() {
            return center_tv;
        }

        public void setCenter_tv(String center_tv) {
            this.center_tv = center_tv;
        }

    }
}
