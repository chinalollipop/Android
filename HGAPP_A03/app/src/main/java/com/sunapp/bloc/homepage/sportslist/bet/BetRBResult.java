package com.sunapp.bloc.homepage.sportslist.bet;

import java.util.List;

/**
 * 滚球的数据格式
 */
public class BetRBResult {

    /**
     * MID : ["3314922","3314914"]
     * M_Time : 11:12p
     * M_Type : ["1","0"]
     * MB_MID : ["61060","61062"]
     * TG_MID : ["61059","61061"]
     * MB_Team : 波特兰木材
     * TG_Team : 温哥华白帽
     * M_League : 美国职业大联盟
     * MB_Win_Rate_RB : ["2.48",""]
     * TG_Win_Rate_RB : ["2.62",""]
     * M_Flat_Rate_RB : ["3.41",""]
     * M_LetB_RB : ["1","1 / 1.5"]
     * T_LetB_RB : ["1",""]
     * MB_LetB_Rate_RB : ["0.9","0.67"]
     * TG_LetB_Rate_RB : ["1.02","1.32"]
     * MB_Dime_RB : ["O3 / 3.5","O2.5"]
     * TG_Dime_RB : ["U3 / 3.5","U2.5"]
     * MB_Dime_Rate_RB : ["0.96","0.71"]
     * TG_Dime_Rate_RB : ["0.95","1.24"]
     * ShowTypeRB : H
     * ShowTypeHRB : H
     * MB_Win_Rate_RB_H : ["10.01","0.67"]
     * TG_Win_Rate_RB_H : ["1.41","0.67"]
     * M_Flat_Rate_RB_H : ["3.51","0.67"]
     * M_LetB_RB_H : ["0/0.5","1/1.5"]
     * MB_LetB_Rate_RB_H : ["1.15","0.67"]
     * TG_LetB_Rate_RB_H : ["0.78","0.67"]
     * MB_Dime_RB_H : ["O1.5","O1.5/2"]
     * MB_Dime_RB_S_H :
     * TG_Dime_RB_H : ["U1.5","U1.5/2"]
     * TG_Dime_RB_S_H :
     * MB_Dime_Rate_RB_H : ["1.12","0.67"]
     * MB_Dime_Rate_RB_S_H :
     * TG_Dime_Rate_RB_H : ["0.8","0.67"]
     * TG_Dime_Rate_RB_S_H :
     * MB_Ball : 0
     * TG_Ball : 1
     * MB_Inball_HR :
     * TG_Inball_HR :
     * Eventid : ["单",""]
     * Hot : ["双",""]
     * Play : ["1",""]
     * nowSession :
     */

    private String M_Time;
    private String MB_Team;
    private String TG_Team;
    private String M_League;
    private String ShowTypeRB;
    private String ShowTypeHRB;
    private String MB_Dime_RB_S_H;
    private String TG_Dime_RB_S_H;
    private String MB_Dime_Rate_RB_S_H;
    private String TG_Dime_Rate_RB_S_H;
    private String MB_Ball;
    private String TG_Ball;
    private String MB_Inball_HR;
    private String TG_Inball_HR;
    private String nowSession;
    private List<String> MID;
    private List<String> M_Type;
    private List<String> MB_MID;
    private List<String> TG_MID;
    private List<String> MB_Win_Rate_RB;
    private List<String> TG_Win_Rate_RB;
    private List<String> M_Flat_Rate_RB;
    private List<String> M_LetB_RB;
    private List<String> T_LetB_RB;
    private List<String> MB_LetB_Rate_RB;
    private List<String> TG_LetB_Rate_RB;
    private List<String> MB_Dime_RB;
    private List<String> TG_Dime_RB;
    private List<String> MB_Dime_Rate_RB;
    private List<String> TG_Dime_Rate_RB;
    private List<String> MB_Win_Rate_RB_H;
    private List<String> TG_Win_Rate_RB_H;
    private List<String> M_Flat_Rate_RB_H;
    private List<String> M_LetB_RB_H;
    private List<String> MB_LetB_Rate_RB_H;
    private List<String> TG_LetB_Rate_RB_H;
    private List<String> MB_Dime_RB_H;
    private List<String> TG_Dime_RB_H;
    private List<String> MB_Dime_Rate_RB_H;
    private List<String> TG_Dime_Rate_RB_H;
    private List<String> Eventid;
    private List<String> Hot;
    private List<String> Play;

    public String getM_Time() {
        return M_Time;
    }

    public void setM_Time(String M_Time) {
        this.M_Time = M_Time;
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

    public String getMB_Dime_RB_S_H() {
        return MB_Dime_RB_S_H;
    }

    public void setMB_Dime_RB_S_H(String MB_Dime_RB_S_H) {
        this.MB_Dime_RB_S_H = MB_Dime_RB_S_H;
    }

    public String getTG_Dime_RB_S_H() {
        return TG_Dime_RB_S_H;
    }

    public void setTG_Dime_RB_S_H(String TG_Dime_RB_S_H) {
        this.TG_Dime_RB_S_H = TG_Dime_RB_S_H;
    }

    public String getMB_Dime_Rate_RB_S_H() {
        return MB_Dime_Rate_RB_S_H;
    }

    public void setMB_Dime_Rate_RB_S_H(String MB_Dime_Rate_RB_S_H) {
        this.MB_Dime_Rate_RB_S_H = MB_Dime_Rate_RB_S_H;
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

    public String getNowSession() {
        return nowSession;
    }

    public void setNowSession(String nowSession) {
        this.nowSession = nowSession;
    }

    public List<String> getMID() {
        return MID;
    }

    public void setMID(List<String> MID) {
        this.MID = MID;
    }

    public List<String> getM_Type() {
        return M_Type;
    }

    public void setM_Type(List<String> M_Type) {
        this.M_Type = M_Type;
    }

    public List<String> getMB_MID() {
        return MB_MID;
    }

    public void setMB_MID(List<String> MB_MID) {
        this.MB_MID = MB_MID;
    }

    public List<String> getTG_MID() {
        return TG_MID;
    }

    public void setTG_MID(List<String> TG_MID) {
        this.TG_MID = TG_MID;
    }

    public List<String> getMB_Win_Rate_RB() {
        return MB_Win_Rate_RB;
    }

    public void setMB_Win_Rate_RB(List<String> MB_Win_Rate_RB) {
        this.MB_Win_Rate_RB = MB_Win_Rate_RB;
    }

    public List<String> getTG_Win_Rate_RB() {
        return TG_Win_Rate_RB;
    }

    public void setTG_Win_Rate_RB(List<String> TG_Win_Rate_RB) {
        this.TG_Win_Rate_RB = TG_Win_Rate_RB;
    }

    public List<String> getM_Flat_Rate_RB() {
        return M_Flat_Rate_RB;
    }

    public void setM_Flat_Rate_RB(List<String> M_Flat_Rate_RB) {
        this.M_Flat_Rate_RB = M_Flat_Rate_RB;
    }

    public List<String> getM_LetB_RB() {
        return M_LetB_RB;
    }

    public void setM_LetB_RB(List<String> M_LetB_RB) {
        this.M_LetB_RB = M_LetB_RB;
    }

    public List<String> getT_LetB_RB() {
        return T_LetB_RB;
    }

    public void setT_LetB_RB(List<String> T_LetB_RB) {
        this.T_LetB_RB = T_LetB_RB;
    }

    public List<String> getMB_LetB_Rate_RB() {
        return MB_LetB_Rate_RB;
    }

    public void setMB_LetB_Rate_RB(List<String> MB_LetB_Rate_RB) {
        this.MB_LetB_Rate_RB = MB_LetB_Rate_RB;
    }

    public List<String> getTG_LetB_Rate_RB() {
        return TG_LetB_Rate_RB;
    }

    public void setTG_LetB_Rate_RB(List<String> TG_LetB_Rate_RB) {
        this.TG_LetB_Rate_RB = TG_LetB_Rate_RB;
    }

    public List<String> getMB_Dime_RB() {
        return MB_Dime_RB;
    }

    public void setMB_Dime_RB(List<String> MB_Dime_RB) {
        this.MB_Dime_RB = MB_Dime_RB;
    }

    public List<String> getTG_Dime_RB() {
        return TG_Dime_RB;
    }

    public void setTG_Dime_RB(List<String> TG_Dime_RB) {
        this.TG_Dime_RB = TG_Dime_RB;
    }

    public List<String> getMB_Dime_Rate_RB() {
        return MB_Dime_Rate_RB;
    }

    public void setMB_Dime_Rate_RB(List<String> MB_Dime_Rate_RB) {
        this.MB_Dime_Rate_RB = MB_Dime_Rate_RB;
    }

    public List<String> getTG_Dime_Rate_RB() {
        return TG_Dime_Rate_RB;
    }

    public void setTG_Dime_Rate_RB(List<String> TG_Dime_Rate_RB) {
        this.TG_Dime_Rate_RB = TG_Dime_Rate_RB;
    }

    public List<String> getMB_Win_Rate_RB_H() {
        return MB_Win_Rate_RB_H;
    }

    public void setMB_Win_Rate_RB_H(List<String> MB_Win_Rate_RB_H) {
        this.MB_Win_Rate_RB_H = MB_Win_Rate_RB_H;
    }

    public List<String> getTG_Win_Rate_RB_H() {
        return TG_Win_Rate_RB_H;
    }

    public void setTG_Win_Rate_RB_H(List<String> TG_Win_Rate_RB_H) {
        this.TG_Win_Rate_RB_H = TG_Win_Rate_RB_H;
    }

    public List<String> getM_Flat_Rate_RB_H() {
        return M_Flat_Rate_RB_H;
    }

    public void setM_Flat_Rate_RB_H(List<String> M_Flat_Rate_RB_H) {
        this.M_Flat_Rate_RB_H = M_Flat_Rate_RB_H;
    }

    public List<String> getM_LetB_RB_H() {
        return M_LetB_RB_H;
    }

    public void setM_LetB_RB_H(List<String> M_LetB_RB_H) {
        this.M_LetB_RB_H = M_LetB_RB_H;
    }

    public List<String> getMB_LetB_Rate_RB_H() {
        return MB_LetB_Rate_RB_H;
    }

    public void setMB_LetB_Rate_RB_H(List<String> MB_LetB_Rate_RB_H) {
        this.MB_LetB_Rate_RB_H = MB_LetB_Rate_RB_H;
    }

    public List<String> getTG_LetB_Rate_RB_H() {
        return TG_LetB_Rate_RB_H;
    }

    public void setTG_LetB_Rate_RB_H(List<String> TG_LetB_Rate_RB_H) {
        this.TG_LetB_Rate_RB_H = TG_LetB_Rate_RB_H;
    }

    public List<String> getMB_Dime_RB_H() {
        return MB_Dime_RB_H;
    }

    public void setMB_Dime_RB_H(List<String> MB_Dime_RB_H) {
        this.MB_Dime_RB_H = MB_Dime_RB_H;
    }

    public List<String> getTG_Dime_RB_H() {
        return TG_Dime_RB_H;
    }

    public void setTG_Dime_RB_H(List<String> TG_Dime_RB_H) {
        this.TG_Dime_RB_H = TG_Dime_RB_H;
    }

    public List<String> getMB_Dime_Rate_RB_H() {
        return MB_Dime_Rate_RB_H;
    }

    public void setMB_Dime_Rate_RB_H(List<String> MB_Dime_Rate_RB_H) {
        this.MB_Dime_Rate_RB_H = MB_Dime_Rate_RB_H;
    }

    public List<String> getTG_Dime_Rate_RB_H() {
        return TG_Dime_Rate_RB_H;
    }

    public void setTG_Dime_Rate_RB_H(List<String> TG_Dime_Rate_RB_H) {
        this.TG_Dime_Rate_RB_H = TG_Dime_Rate_RB_H;
    }

    public List<String> getEventid() {
        return Eventid;
    }

    public void setEventid(List<String> Eventid) {
        this.Eventid = Eventid;
    }

    public List<String> getHot() {
        return Hot;
    }

    public void setHot(List<String> Hot) {
        this.Hot = Hot;
    }

    public List<String> getPlay() {
        return Play;
    }

    public void setPlay(List<String> Play) {
        this.Play = Play;
    }

    @Override
    public String toString() {
        return "BetNewResult{" +
                "M_Time='" + M_Time + '\'' +
                ", MB_Team='" + MB_Team + '\'' +
                ", TG_Team='" + TG_Team + '\'' +
                ", M_League='" + M_League + '\'' +
                ", ShowTypeRB='" + ShowTypeRB + '\'' +
                ", ShowTypeHRB='" + ShowTypeHRB + '\'' +
                ", MB_Dime_RB_S_H='" + MB_Dime_RB_S_H + '\'' +
                ", TG_Dime_RB_S_H='" + TG_Dime_RB_S_H + '\'' +
                ", MB_Dime_Rate_RB_S_H='" + MB_Dime_Rate_RB_S_H + '\'' +
                ", TG_Dime_Rate_RB_S_H='" + TG_Dime_Rate_RB_S_H + '\'' +
                ", MB_Ball='" + MB_Ball + '\'' +
                ", TG_Ball='" + TG_Ball + '\'' +
                ", MB_Inball_HR='" + MB_Inball_HR + '\'' +
                ", TG_Inball_HR='" + TG_Inball_HR + '\'' +
                ", nowSession='" + nowSession + '\'' +
                ", MID=" + MID +
                ", M_Type=" + M_Type +
                ", MB_MID=" + MB_MID +
                ", TG_MID=" + TG_MID +
                ", MB_Win_Rate_RB=" + MB_Win_Rate_RB +
                ", TG_Win_Rate_RB=" + TG_Win_Rate_RB +
                ", M_Flat_Rate_RB=" + M_Flat_Rate_RB +
                ", M_LetB_RB=" + M_LetB_RB +
                ", T_LetB_RB=" + T_LetB_RB +
                ", MB_LetB_Rate_RB=" + MB_LetB_Rate_RB +
                ", TG_LetB_Rate_RB=" + TG_LetB_Rate_RB +
                ", MB_Dime_RB=" + MB_Dime_RB +
                ", TG_Dime_RB=" + TG_Dime_RB +
                ", MB_Dime_Rate_RB=" + MB_Dime_Rate_RB +
                ", TG_Dime_Rate_RB=" + TG_Dime_Rate_RB +
                ", MB_Win_Rate_RB_H=" + MB_Win_Rate_RB_H +
                ", TG_Win_Rate_RB_H=" + TG_Win_Rate_RB_H +
                ", M_Flat_Rate_RB_H=" + M_Flat_Rate_RB_H +
                ", M_LetB_RB_H=" + M_LetB_RB_H +
                ", MB_LetB_Rate_RB_H=" + MB_LetB_Rate_RB_H +
                ", TG_LetB_Rate_RB_H=" + TG_LetB_Rate_RB_H +
                ", MB_Dime_RB_H=" + MB_Dime_RB_H +
                ", TG_Dime_RB_H=" + TG_Dime_RB_H +
                ", MB_Dime_Rate_RB_H=" + MB_Dime_Rate_RB_H +
                ", TG_Dime_Rate_RB_H=" + TG_Dime_Rate_RB_H +
                ", Eventid=" + Eventid +
                ", Hot=" + Hot +
                ", Play=" + Play +
                '}';
    }
}
