package com.gmcp.gm.data;

import com.google.gson.annotations.SerializedName;

public class CPJSSCResult {

    /**
     * 3217 : 2.19
     * 3218 : 1.78
     * 3219 : 1.78
     * 3220 : 2.19
     * 3201-3211 : 1.988
     * 3201-3212 : 1.988
     * 3201-3213 : 1.988
     * 3201-3214 : 1.988
     * 3201-3215 : 1.988
     * 3201-3216 : 1.988
     * 3201-1 : 9.88
     * 3201-2 : 9.88
     * 3201-3 : 9.88
     * 3201-4 : 9.88
     * 3201-5 : 9.88
     * 3201-6 : 9.88
     * 3201-7 : 9.88
     * 3201-8 : 9.88
     * 3201-9 : 9.88
     * 3201-10 : 9.88
     * 3202-3211 : 1.988
     * 3202-3212 : 1.988
     * 3202-3213 : 1.988
     * 3202-3214 : 1.988
     * 3202-3215 : 1.988
     * 3202-3216 : 1.988
     * 3202-1 : 9.88
     * 3202-2 : 9.88
     * 3202-3 : 9.88
     * 3202-4 : 9.88
     * 3202-5 : 9.88
     * 3202-6 : 9.88
     * 3202-7 : 9.88
     * 3202-8 : 9.88
     * 3202-9 : 9.88
     * 3202-10 : 9.88
     * 3203-3211 : 1.988
     * 3203-3212 : 1.988
     * 3203-3213 : 1.988
     * 3203-3214 : 1.988
     * 3203-3215 : 1.988
     * 3203-3216 : 1.988
     * 3203-1 : 9.88
     * 3203-2 : 9.88
     * 3203-3 : 9.88
     * 3203-4 : 9.88
     * 3203-5 : 9.88
     * 3203-6 : 9.88
     * 3203-7 : 9.88
     * 3203-8 : 9.88
     * 3203-9 : 9.88
     * 3203-10 : 9.88
     * 3204-3211 : 1.988
     * 3204-3212 : 1.988
     * 3204-3213 : 1.988
     * 3204-3214 : 1.988
     * 3204-3215 : 1.988
     * 3204-3216 : 1.988
     * 3204-1 : 9.88
     * 3204-2 : 9.88
     * 3204-3 : 9.88
     * 3204-4 : 9.88
     * 3204-5 : 9.88
     * 3204-6 : 9.88
     * 3204-7 : 9.88
     * 3204-8 : 9.88
     * 3204-9 : 9.88
     * 3204-10 : 9.88
     * 3205-3211 : 1.988
     * 3205-3212 : 1.988
     * 3205-3213 : 1.988
     * 3205-3214 : 1.988
     * 3205-3215 : 1.988
     * 3205-3216 : 1.988
     * 3205-1 : 9.88
     * 3205-2 : 9.88
     * 3205-3 : 9.88
     * 3205-4 : 9.88
     * 3205-5 : 9.88
     * 3205-6 : 9.88
     * 3205-7 : 9.88
     * 3205-8 : 9.88
     * 3205-9 : 9.88
     * 3205-10 : 9.88
     * 3206-3211 : 1.988
     * 3206-3212 : 1.988
     * 3206-3213 : 1.988
     * 3206-3214 : 1.988
     * 3206-1 : 9.88
     * 3206-2 : 9.88
     * 3206-3 : 9.88
     * 3206-4 : 9.88
     * 3206-5 : 9.88
     * 3206-6 : 9.88
     * 3206-7 : 9.88
     * 3206-8 : 9.88
     * 3206-9 : 9.88
     * 3206-10 : 9.88
     * 3207-3211 : 1.988
     * 3207-3212 : 1.988
     * 3207-3213 : 1.988
     * 3207-3214 : 1.988
     * 3207-1 : 9.88
     * 3207-2 : 9.88
     * 3207-3 : 9.88
     * 3207-4 : 9.88
     * 3207-5 : 9.88
     * 3207-6 : 9.88
     * 3207-7 : 9.88
     * 3207-8 : 9.88
     * 3207-9 : 9.88
     * 3207-10 : 9.88
     * 3208-3211 : 1.988
     * 3208-3212 : 1.988
     * 3208-3213 : 1.988
     * 3208-3214 : 1.988
     * 3208-1 : 9.88
     * 3208-2 : 9.88
     * 3208-3 : 9.88
     * 3208-4 : 9.88
     * 3208-5 : 9.88
     * 3208-6 : 9.88
     * 3208-7 : 9.88
     * 3208-8 : 9.88
     * 3208-9 : 9.88
     * 3208-10 : 9.88
     * 3209-3211 : 1.988
     * 3209-3212 : 1.988
     * 3209-3213 : 1.988
     * 3209-3214 : 1.988
     * 3209-1 : 9.88
     * 3209-2 : 9.88
     * 3209-3 : 9.88
     * 3209-4 : 9.88
     * 3209-5 : 9.88
     * 3209-6 : 9.88
     * 3209-7 : 9.88
     * 3209-8 : 9.88
     * 3209-9 : 9.88
     * 3209-10 : 9.88
     * 3210-3211 : 1.988
     * 3210-3212 : 1.988
     * 3210-3213 : 1.988
     * 3210-3214 : 1.988
     * 3210-1 : 9.88
     * 3210-2 : 9.88
     * 3210-3 : 9.88
     * 3210-4 : 9.88
     * 3210-5 : 9.88
     * 3210-6 : 9.88
     * 3210-7 : 9.88
     * 3210-8 : 9.88
     * 3210-9 : 9.88
     * 3210-10 : 9.88
     * 3221-3 : 42.5
     * 3221-4 : 42.5
     * 3221-5 : 21.5
     * 3221-6 : 21.5
     * 3221-7 : 14.5
     * 3221-8 : 14.5
     * 3221-9 : 10.5
     * 3221-10 : 10.5
     * 3221-11 : 8.5
     * 3221-12 : 10.5
     * 3221-13 : 10.5
     * 3221-14 : 14.5
     * 3221-15 : 14.5
     * 3221-16 : 21.5
     * 3221-17 : 21.5
     * 3221-18 : 42.5
     * 3221-19 : 42.5
     */

    @SerializedName("3217")
    private String data_3217;
    @SerializedName("3218")
    private String data_3218;
    @SerializedName("3219")
    private String data_3219;
    @SerializedName("3220")
    private String data_3220;
    @SerializedName("3201-3211")
    private String data_32013211;
    @SerializedName("3201-3212")
    private String data_32013212;
    @SerializedName("3201-3213")
    private String data_32013213;
    @SerializedName("3201-3214")
    private String data_32013214;
    @SerializedName("3201-3215")
    private String data_32013215;
    @SerializedName("3201-3216")
    private String data_32013216;
    @SerializedName("3201-1")
    private String data_32011;
    @SerializedName("3201-2")
    private String data_32012;
    @SerializedName("3201-3")
    private String data_32013;
    @SerializedName("3201-4")
    private String data_32014;
    @SerializedName("3201-5")
    private String data_32015;
    @SerializedName("3201-6")
    private String data_32016;
    @SerializedName("3201-7")
    private String data_32017;
    @SerializedName("3201-8")
    private String data_32018;
    @SerializedName("3201-9")
    private String data_32019;
    @SerializedName("3201-10")
    private String data_320110;
    @SerializedName("3202-3211")
    private String data_32023211;
    @SerializedName("3202-3212")
    private String data_32023212;
    @SerializedName("3202-3213")
    private String data_32023213;
    @SerializedName("3202-3214")
    private String data_32023214;
    @SerializedName("3202-3215")
    private String data_32023215;
    @SerializedName("3202-3216")
    private String data_32023216;
    @SerializedName("3202-1")
    private String data_32021;
    @SerializedName("3202-2")
    private String data_32022;
    @SerializedName("3202-3")
    private String data_32023;
    @SerializedName("3202-4")
    private String data_32024;
    @SerializedName("3202-5")
    private String data_32025;
    @SerializedName("3202-6")
    private String data_32026;
    @SerializedName("3202-7")
    private String data_32027;
    @SerializedName("3202-8")
    private String data_32028;
    @SerializedName("3202-9")
    private String data_32029;
    @SerializedName("3202-10")
    private String data_320210;
    @SerializedName("3203-3211")
    private String data_32033211;
    @SerializedName("3203-3212")
    private String data_32033212;
    @SerializedName("3203-3213")
    private String data_32033213;
    @SerializedName("3203-3214")
    private String data_32033214;
    @SerializedName("3203-3215")
    private String data_32033215;
    @SerializedName("3203-3216")
    private String data_32033216;
    @SerializedName("3203-1")
    private String data_32031;
    @SerializedName("3203-2")
    private String data_32032;
    @SerializedName("3203-3")
    private String data_32033;
    @SerializedName("3203-4")
    private String data_32034;
    @SerializedName("3203-5")
    private String data_32035;
    @SerializedName("3203-6")
    private String data_32036;
    @SerializedName("3203-7")
    private String data_32037;
    @SerializedName("3203-8")
    private String data_32038;
    @SerializedName("3203-9")
    private String data_32039;
    @SerializedName("3203-10")
    private String data_320310;
    @SerializedName("3204-3211")
    private String data_32043211;
    @SerializedName("3204-3212")
    private String data_32043212;
    @SerializedName("3204-3213")
    private String data_32043213;
    @SerializedName("3204-3214")
    private String data_32043214;
    @SerializedName("3204-3215")
    private String data_32043215;
    @SerializedName("3204-3216")
    private String data_32043216;
    @SerializedName("3204-1")
    private String data_32041;
    @SerializedName("3204-2")
    private String data_32042;
    @SerializedName("3204-3")
    private String data_32043;
    @SerializedName("3204-4")
    private String data_32044;
    @SerializedName("3204-5")
    private String data_32045;
    @SerializedName("3204-6")
    private String data_32046;
    @SerializedName("3204-7")
    private String data_32047;
    @SerializedName("3204-8")
    private String data_32048;
    @SerializedName("3204-9")
    private String data_32049;
    @SerializedName("3204-10")
    private String data_320410;
    @SerializedName("3205-3211")
    private String data_32053211;
    @SerializedName("3205-3212")
    private String data_32053212;
    @SerializedName("3205-3213")
    private String data_32053213;
    @SerializedName("3205-3214")
    private String data_32053214;
    @SerializedName("3205-3215")
    private String data_32053215;
    @SerializedName("3205-3216")
    private String data_32053216;
    @SerializedName("3205-1")
    private String data_32051;
    @SerializedName("3205-2")
    private String data_32052;
    @SerializedName("3205-3")
    private String data_32053;
    @SerializedName("3205-4")
    private String data_32054;
    @SerializedName("3205-5")
    private String data_32055;
    @SerializedName("3205-6")
    private String data_32056;
    @SerializedName("3205-7")
    private String data_32057;
    @SerializedName("3205-8")
    private String data_32058;
    @SerializedName("3205-9")
    private String data_32059;
    @SerializedName("3205-10")
    private String data_320510;
    @SerializedName("3206-3211")
    private String data_32063211;
    @SerializedName("3206-3212")
    private String data_32063212;
    @SerializedName("3206-3213")
    private String data_32063213;
    @SerializedName("3206-3214")
    private String data_32063214;
    @SerializedName("3206-1")
    private String data_32061;
    @SerializedName("3206-2")
    private String data_32062;
    @SerializedName("3206-3")
    private String data_32063;
    @SerializedName("3206-4")
    private String data_32064;
    @SerializedName("3206-5")
    private String data_32065;
    @SerializedName("3206-6")
    private String data_32066;
    @SerializedName("3206-7")
    private String data_32067;
    @SerializedName("3206-8")
    private String data_32068;
    @SerializedName("3206-9")
    private String data_32069;
    @SerializedName("3206-10")
    private String data_320610;
    @SerializedName("3207-3211")
    private String data_32073211;
    @SerializedName("3207-3212")
    private String data_32073212;
    @SerializedName("3207-3213")
    private String data_32073213;
    @SerializedName("3207-3214")
    private String data_32073214;
    @SerializedName("3207-1")
    private String data_32071;
    @SerializedName("3207-2")
    private String data_32072;
    @SerializedName("3207-3")
    private String data_32073;
    @SerializedName("3207-4")
    private String data_32074;
    @SerializedName("3207-5")
    private String data_32075;
    @SerializedName("3207-6")
    private String data_32076;
    @SerializedName("3207-7")
    private String data_32077;
    @SerializedName("3207-8")
    private String data_32078;
    @SerializedName("3207-9")
    private String data_32079;
    @SerializedName("3207-10")
    private String data_320710;
    @SerializedName("3208-3211")
    private String data_32083211;
    @SerializedName("3208-3212")
    private String data_32083212;
    @SerializedName("3208-3213")
    private String data_32083213;
    @SerializedName("3208-3214")
    private String data_32083214;
    @SerializedName("3208-1")
    private String data_32081;
    @SerializedName("3208-2")
    private String data_32082;
    @SerializedName("3208-3")
    private String data_32083;
    @SerializedName("3208-4")
    private String data_32084;
    @SerializedName("3208-5")
    private String data_32085;
    @SerializedName("3208-6")
    private String data_32086;
    @SerializedName("3208-7")
    private String data_32087;
    @SerializedName("3208-8")
    private String data_32088;
    @SerializedName("3208-9")
    private String data_32089;
    @SerializedName("3208-10")
    private String data_320810;
    @SerializedName("3209-3211")
    private String data_32093211;
    @SerializedName("3209-3212")
    private String data_32093212;
    @SerializedName("3209-3213")
    private String data_32093213;
    @SerializedName("3209-3214")
    private String data_32093214;
    @SerializedName("3209-1")
    private String data_32091;
    @SerializedName("3209-2")
    private String data_32092;
    @SerializedName("3209-3")
    private String data_32093;
    @SerializedName("3209-4")
    private String data_32094;
    @SerializedName("3209-5")
    private String data_32095;
    @SerializedName("3209-6")
    private String data_32096;
    @SerializedName("3209-7")
    private String data_32097;
    @SerializedName("3209-8")
    private String data_32098;
    @SerializedName("3209-9")
    private String data_32099;
    @SerializedName("3209-10")
    private String data_320910;
    @SerializedName("3210-3211")
    private String data_32103211;
    @SerializedName("3210-3212")
    private String data_32103212;
    @SerializedName("3210-3213")
    private String data_32103213;
    @SerializedName("3210-3214")
    private String data_32103214;
    @SerializedName("3210-1")
    private String data_32101;
    @SerializedName("3210-2")
    private String data_32102;
    @SerializedName("3210-3")
    private String data_32103;
    @SerializedName("3210-4")
    private String data_32104;
    @SerializedName("3210-5")
    private String data_32105;
    @SerializedName("3210-6")
    private String data_32106;
    @SerializedName("3210-7")
    private String data_32107;
    @SerializedName("3210-8")
    private String data_32108;
    @SerializedName("3210-9")
    private String data_32109;
    @SerializedName("3210-10")
    private String data_321010;
    @SerializedName("3221-3")
    private String data_32213;
    @SerializedName("3221-4")
    private String data_32214;
    @SerializedName("3221-5")
    private String data_32215;
    @SerializedName("3221-6")
    private String data_32216;
    @SerializedName("3221-7")
    private String data_32217;
    @SerializedName("3221-8")
    private String data_32218;
    @SerializedName("3221-9")
    private String data_32219;
    @SerializedName("3221-10")
    private String data_322110;
    @SerializedName("3221-11")
    private String data_322111;
    @SerializedName("3221-12")
    private String data_322112;
    @SerializedName("3221-13")
    private String data_322113;
    @SerializedName("3221-14")
    private String data_322114;
    @SerializedName("3221-15")
    private String data_322115;
    @SerializedName("3221-16")
    private String data_322116;
    @SerializedName("3221-17")
    private String data_322117;
    @SerializedName("3221-18")
    private String data_322118;
    @SerializedName("3221-19")
    private String data_322119;

    public String getdata_3217() {
        return data_3217;
    }

    public void setdata_3217(String data_3217) {
        this.data_3217 = data_3217;
    }

    public String getdata_3218() {
        return data_3218;
    }

    public void setdata_3218(String data_3218) {
        this.data_3218 = data_3218;
    }

    public String getdata_3219() {
        return data_3219;
    }

    public void setdata_3219(String data_3219) {
        this.data_3219 = data_3219;
    }

    public String getdata_3220() {
        return data_3220;
    }

    public void setdata_3220(String data_3220) {
        this.data_3220 = data_3220;
    }

    public String getdata_32013211() {
        return data_32013211;
    }

    public void setdata_32013211(String data_32013211) {
        this.data_32013211 = data_32013211;
    }

    public String getdata_32013212() {
        return data_32013212;
    }

    public void setdata_32013212(String data_32013212) {
        this.data_32013212 = data_32013212;
    }

    public String getdata_32013213() {
        return data_32013213;
    }

    public void setdata_32013213(String data_32013213) {
        this.data_32013213 = data_32013213;
    }

    public String getdata_32013214() {
        return data_32013214;
    }

    public void setdata_32013214(String data_32013214) {
        this.data_32013214 = data_32013214;
    }

    public String getdata_32013215() {
        return data_32013215;
    }

    public void setdata_32013215(String data_32013215) {
        this.data_32013215 = data_32013215;
    }

    public String getdata_32013216() {
        return data_32013216;
    }

    public void setdata_32013216(String data_32013216) {
        this.data_32013216 = data_32013216;
    }

    public String getdata_32011() {
        return data_32011;
    }

    public void setdata_32011(String data_32011) {
        this.data_32011 = data_32011;
    }

    public String getdata_32012() {
        return data_32012;
    }

    public void setdata_32012(String data_32012) {
        this.data_32012 = data_32012;
    }

    public String getdata_32013() {
        return data_32013;
    }

    public void setdata_32013(String data_32013) {
        this.data_32013 = data_32013;
    }

    public String getdata_32014() {
        return data_32014;
    }

    public void setdata_32014(String data_32014) {
        this.data_32014 = data_32014;
    }

    public String getdata_32015() {
        return data_32015;
    }

    public void setdata_32015(String data_32015) {
        this.data_32015 = data_32015;
    }

    public String getdata_32016() {
        return data_32016;
    }

    public void setdata_32016(String data_32016) {
        this.data_32016 = data_32016;
    }

    public String getdata_32017() {
        return data_32017;
    }

    public void setdata_32017(String data_32017) {
        this.data_32017 = data_32017;
    }

    public String getdata_32018() {
        return data_32018;
    }

    public void setdata_32018(String data_32018) {
        this.data_32018 = data_32018;
    }

    public String getdata_32019() {
        return data_32019;
    }

    public void setdata_32019(String data_32019) {
        this.data_32019 = data_32019;
    }

    public String getdata_320110() {
        return data_320110;
    }

    public void setdata_320110(String data_320110) {
        this.data_320110 = data_320110;
    }

    public String getdata_32023211() {
        return data_32023211;
    }

    public void setdata_32023211(String data_32023211) {
        this.data_32023211 = data_32023211;
    }

    public String getdata_32023212() {
        return data_32023212;
    }

    public void setdata_32023212(String data_32023212) {
        this.data_32023212 = data_32023212;
    }

    public String getdata_32023213() {
        return data_32023213;
    }

    public void setdata_32023213(String data_32023213) {
        this.data_32023213 = data_32023213;
    }

    public String getdata_32023214() {
        return data_32023214;
    }

    public void setdata_32023214(String data_32023214) {
        this.data_32023214 = data_32023214;
    }

    public String getdata_32023215() {
        return data_32023215;
    }

    public void setdata_32023215(String data_32023215) {
        this.data_32023215 = data_32023215;
    }

    public String getdata_32023216() {
        return data_32023216;
    }

    public void setdata_32023216(String data_32023216) {
        this.data_32023216 = data_32023216;
    }

    public String getdata_32021() {
        return data_32021;
    }

    public void setdata_32021(String data_32021) {
        this.data_32021 = data_32021;
    }

    public String getdata_32022() {
        return data_32022;
    }

    public void setdata_32022(String data_32022) {
        this.data_32022 = data_32022;
    }

    public String getdata_32023() {
        return data_32023;
    }

    public void setdata_32023(String data_32023) {
        this.data_32023 = data_32023;
    }

    public String getdata_32024() {
        return data_32024;
    }

    public void setdata_32024(String data_32024) {
        this.data_32024 = data_32024;
    }

    public String getdata_32025() {
        return data_32025;
    }

    public void setdata_32025(String data_32025) {
        this.data_32025 = data_32025;
    }

    public String getdata_32026() {
        return data_32026;
    }

    public void setdata_32026(String data_32026) {
        this.data_32026 = data_32026;
    }

    public String getdata_32027() {
        return data_32027;
    }

    public void setdata_32027(String data_32027) {
        this.data_32027 = data_32027;
    }

    public String getdata_32028() {
        return data_32028;
    }

    public void setdata_32028(String data_32028) {
        this.data_32028 = data_32028;
    }

    public String getdata_32029() {
        return data_32029;
    }

    public void setdata_32029(String data_32029) {
        this.data_32029 = data_32029;
    }

    public String getdata_320210() {
        return data_320210;
    }

    public void setdata_320210(String data_320210) {
        this.data_320210 = data_320210;
    }

    public String getdata_32033211() {
        return data_32033211;
    }

    public void setdata_32033211(String data_32033211) {
        this.data_32033211 = data_32033211;
    }

    public String getdata_32033212() {
        return data_32033212;
    }

    public void setdata_32033212(String data_32033212) {
        this.data_32033212 = data_32033212;
    }

    public String getdata_32033213() {
        return data_32033213;
    }

    public void setdata_32033213(String data_32033213) {
        this.data_32033213 = data_32033213;
    }

    public String getdata_32033214() {
        return data_32033214;
    }

    public void setdata_32033214(String data_32033214) {
        this.data_32033214 = data_32033214;
    }

    public String getdata_32033215() {
        return data_32033215;
    }

    public void setdata_32033215(String data_32033215) {
        this.data_32033215 = data_32033215;
    }

    public String getdata_32033216() {
        return data_32033216;
    }

    public void setdata_32033216(String data_32033216) {
        this.data_32033216 = data_32033216;
    }

    public String getdata_32031() {
        return data_32031;
    }

    public void setdata_32031(String data_32031) {
        this.data_32031 = data_32031;
    }

    public String getdata_32032() {
        return data_32032;
    }

    public void setdata_32032(String data_32032) {
        this.data_32032 = data_32032;
    }

    public String getdata_32033() {
        return data_32033;
    }

    public void setdata_32033(String data_32033) {
        this.data_32033 = data_32033;
    }

    public String getdata_32034() {
        return data_32034;
    }

    public void setdata_32034(String data_32034) {
        this.data_32034 = data_32034;
    }

    public String getdata_32035() {
        return data_32035;
    }

    public void setdata_32035(String data_32035) {
        this.data_32035 = data_32035;
    }

    public String getdata_32036() {
        return data_32036;
    }

    public void setdata_32036(String data_32036) {
        this.data_32036 = data_32036;
    }

    public String getdata_32037() {
        return data_32037;
    }

    public void setdata_32037(String data_32037) {
        this.data_32037 = data_32037;
    }

    public String getdata_32038() {
        return data_32038;
    }

    public void setdata_32038(String data_32038) {
        this.data_32038 = data_32038;
    }

    public String getdata_32039() {
        return data_32039;
    }

    public void setdata_32039(String data_32039) {
        this.data_32039 = data_32039;
    }

    public String getdata_320310() {
        return data_320310;
    }

    public void setdata_320310(String data_320310) {
        this.data_320310 = data_320310;
    }

    public String getdata_32043211() {
        return data_32043211;
    }

    public void setdata_32043211(String data_32043211) {
        this.data_32043211 = data_32043211;
    }

    public String getdata_32043212() {
        return data_32043212;
    }

    public void setdata_32043212(String data_32043212) {
        this.data_32043212 = data_32043212;
    }

    public String getdata_32043213() {
        return data_32043213;
    }

    public void setdata_32043213(String data_32043213) {
        this.data_32043213 = data_32043213;
    }

    public String getdata_32043214() {
        return data_32043214;
    }

    public void setdata_32043214(String data_32043214) {
        this.data_32043214 = data_32043214;
    }

    public String getdata_32043215() {
        return data_32043215;
    }

    public void setdata_32043215(String data_32043215) {
        this.data_32043215 = data_32043215;
    }

    public String getdata_32043216() {
        return data_32043216;
    }

    public void setdata_32043216(String data_32043216) {
        this.data_32043216 = data_32043216;
    }

    public String getdata_32041() {
        return data_32041;
    }

    public void setdata_32041(String data_32041) {
        this.data_32041 = data_32041;
    }

    public String getdata_32042() {
        return data_32042;
    }

    public void setdata_32042(String data_32042) {
        this.data_32042 = data_32042;
    }

    public String getdata_32043() {
        return data_32043;
    }

    public void setdata_32043(String data_32043) {
        this.data_32043 = data_32043;
    }

    public String getdata_32044() {
        return data_32044;
    }

    public void setdata_32044(String data_32044) {
        this.data_32044 = data_32044;
    }

    public String getdata_32045() {
        return data_32045;
    }

    public void setdata_32045(String data_32045) {
        this.data_32045 = data_32045;
    }

    public String getdata_32046() {
        return data_32046;
    }

    public void setdata_32046(String data_32046) {
        this.data_32046 = data_32046;
    }

    public String getdata_32047() {
        return data_32047;
    }

    public void setdata_32047(String data_32047) {
        this.data_32047 = data_32047;
    }

    public String getdata_32048() {
        return data_32048;
    }

    public void setdata_32048(String data_32048) {
        this.data_32048 = data_32048;
    }

    public String getdata_32049() {
        return data_32049;
    }

    public void setdata_32049(String data_32049) {
        this.data_32049 = data_32049;
    }

    public String getdata_320410() {
        return data_320410;
    }

    public void setdata_320410(String data_320410) {
        this.data_320410 = data_320410;
    }

    public String getdata_32053211() {
        return data_32053211;
    }

    public void setdata_32053211(String data_32053211) {
        this.data_32053211 = data_32053211;
    }

    public String getdata_32053212() {
        return data_32053212;
    }

    public void setdata_32053212(String data_32053212) {
        this.data_32053212 = data_32053212;
    }

    public String getdata_32053213() {
        return data_32053213;
    }

    public void setdata_32053213(String data_32053213) {
        this.data_32053213 = data_32053213;
    }

    public String getdata_32053214() {
        return data_32053214;
    }

    public void setdata_32053214(String data_32053214) {
        this.data_32053214 = data_32053214;
    }

    public String getdata_32053215() {
        return data_32053215;
    }

    public void setdata_32053215(String data_32053215) {
        this.data_32053215 = data_32053215;
    }

    public String getdata_32053216() {
        return data_32053216;
    }

    public void setdata_32053216(String data_32053216) {
        this.data_32053216 = data_32053216;
    }

    public String getdata_32051() {
        return data_32051;
    }

    public void setdata_32051(String data_32051) {
        this.data_32051 = data_32051;
    }

    public String getdata_32052() {
        return data_32052;
    }

    public void setdata_32052(String data_32052) {
        this.data_32052 = data_32052;
    }

    public String getdata_32053() {
        return data_32053;
    }

    public void setdata_32053(String data_32053) {
        this.data_32053 = data_32053;
    }

    public String getdata_32054() {
        return data_32054;
    }

    public void setdata_32054(String data_32054) {
        this.data_32054 = data_32054;
    }

    public String getdata_32055() {
        return data_32055;
    }

    public void setdata_32055(String data_32055) {
        this.data_32055 = data_32055;
    }

    public String getdata_32056() {
        return data_32056;
    }

    public void setdata_32056(String data_32056) {
        this.data_32056 = data_32056;
    }

    public String getdata_32057() {
        return data_32057;
    }

    public void setdata_32057(String data_32057) {
        this.data_32057 = data_32057;
    }

    public String getdata_32058() {
        return data_32058;
    }

    public void setdata_32058(String data_32058) {
        this.data_32058 = data_32058;
    }

    public String getdata_32059() {
        return data_32059;
    }

    public void setdata_32059(String data_32059) {
        this.data_32059 = data_32059;
    }

    public String getdata_320510() {
        return data_320510;
    }

    public void setdata_320510(String data_320510) {
        this.data_320510 = data_320510;
    }

    public String getdata_32063211() {
        return data_32063211;
    }

    public void setdata_32063211(String data_32063211) {
        this.data_32063211 = data_32063211;
    }

    public String getdata_32063212() {
        return data_32063212;
    }

    public void setdata_32063212(String data_32063212) {
        this.data_32063212 = data_32063212;
    }

    public String getdata_32063213() {
        return data_32063213;
    }

    public void setdata_32063213(String data_32063213) {
        this.data_32063213 = data_32063213;
    }

    public String getdata_32063214() {
        return data_32063214;
    }

    public void setdata_32063214(String data_32063214) {
        this.data_32063214 = data_32063214;
    }

    public String getdata_32061() {
        return data_32061;
    }

    public void setdata_32061(String data_32061) {
        this.data_32061 = data_32061;
    }

    public String getdata_32062() {
        return data_32062;
    }

    public void setdata_32062(String data_32062) {
        this.data_32062 = data_32062;
    }

    public String getdata_32063() {
        return data_32063;
    }

    public void setdata_32063(String data_32063) {
        this.data_32063 = data_32063;
    }

    public String getdata_32064() {
        return data_32064;
    }

    public void setdata_32064(String data_32064) {
        this.data_32064 = data_32064;
    }

    public String getdata_32065() {
        return data_32065;
    }

    public void setdata_32065(String data_32065) {
        this.data_32065 = data_32065;
    }

    public String getdata_32066() {
        return data_32066;
    }

    public void setdata_32066(String data_32066) {
        this.data_32066 = data_32066;
    }

    public String getdata_32067() {
        return data_32067;
    }

    public void setdata_32067(String data_32067) {
        this.data_32067 = data_32067;
    }

    public String getdata_32068() {
        return data_32068;
    }

    public void setdata_32068(String data_32068) {
        this.data_32068 = data_32068;
    }

    public String getdata_32069() {
        return data_32069;
    }

    public void setdata_32069(String data_32069) {
        this.data_32069 = data_32069;
    }

    public String getdata_320610() {
        return data_320610;
    }

    public void setdata_320610(String data_320610) {
        this.data_320610 = data_320610;
    }

    public String getdata_32073211() {
        return data_32073211;
    }

    public void setdata_32073211(String data_32073211) {
        this.data_32073211 = data_32073211;
    }

    public String getdata_32073212() {
        return data_32073212;
    }

    public void setdata_32073212(String data_32073212) {
        this.data_32073212 = data_32073212;
    }

    public String getdata_32073213() {
        return data_32073213;
    }

    public void setdata_32073213(String data_32073213) {
        this.data_32073213 = data_32073213;
    }

    public String getdata_32073214() {
        return data_32073214;
    }

    public void setdata_32073214(String data_32073214) {
        this.data_32073214 = data_32073214;
    }

    public String getdata_32071() {
        return data_32071;
    }

    public void setdata_32071(String data_32071) {
        this.data_32071 = data_32071;
    }

    public String getdata_32072() {
        return data_32072;
    }

    public void setdata_32072(String data_32072) {
        this.data_32072 = data_32072;
    }

    public String getdata_32073() {
        return data_32073;
    }

    public void setdata_32073(String data_32073) {
        this.data_32073 = data_32073;
    }

    public String getdata_32074() {
        return data_32074;
    }

    public void setdata_32074(String data_32074) {
        this.data_32074 = data_32074;
    }

    public String getdata_32075() {
        return data_32075;
    }

    public void setdata_32075(String data_32075) {
        this.data_32075 = data_32075;
    }

    public String getdata_32076() {
        return data_32076;
    }

    public void setdata_32076(String data_32076) {
        this.data_32076 = data_32076;
    }

    public String getdata_32077() {
        return data_32077;
    }

    public void setdata_32077(String data_32077) {
        this.data_32077 = data_32077;
    }

    public String getdata_32078() {
        return data_32078;
    }

    public void setdata_32078(String data_32078) {
        this.data_32078 = data_32078;
    }

    public String getdata_32079() {
        return data_32079;
    }

    public void setdata_32079(String data_32079) {
        this.data_32079 = data_32079;
    }

    public String getdata_320710() {
        return data_320710;
    }

    public void setdata_320710(String data_320710) {
        this.data_320710 = data_320710;
    }

    public String getdata_32083211() {
        return data_32083211;
    }

    public void setdata_32083211(String data_32083211) {
        this.data_32083211 = data_32083211;
    }

    public String getdata_32083212() {
        return data_32083212;
    }

    public void setdata_32083212(String data_32083212) {
        this.data_32083212 = data_32083212;
    }

    public String getdata_32083213() {
        return data_32083213;
    }

    public void setdata_32083213(String data_32083213) {
        this.data_32083213 = data_32083213;
    }

    public String getdata_32083214() {
        return data_32083214;
    }

    public void setdata_32083214(String data_32083214) {
        this.data_32083214 = data_32083214;
    }

    public String getdata_32081() {
        return data_32081;
    }

    public void setdata_32081(String data_32081) {
        this.data_32081 = data_32081;
    }

    public String getdata_32082() {
        return data_32082;
    }

    public void setdata_32082(String data_32082) {
        this.data_32082 = data_32082;
    }

    public String getdata_32083() {
        return data_32083;
    }

    public void setdata_32083(String data_32083) {
        this.data_32083 = data_32083;
    }

    public String getdata_32084() {
        return data_32084;
    }

    public void setdata_32084(String data_32084) {
        this.data_32084 = data_32084;
    }

    public String getdata_32085() {
        return data_32085;
    }

    public void setdata_32085(String data_32085) {
        this.data_32085 = data_32085;
    }

    public String getdata_32086() {
        return data_32086;
    }

    public void setdata_32086(String data_32086) {
        this.data_32086 = data_32086;
    }

    public String getdata_32087() {
        return data_32087;
    }

    public void setdata_32087(String data_32087) {
        this.data_32087 = data_32087;
    }

    public String getdata_32088() {
        return data_32088;
    }

    public void setdata_32088(String data_32088) {
        this.data_32088 = data_32088;
    }

    public String getdata_32089() {
        return data_32089;
    }

    public void setdata_32089(String data_32089) {
        this.data_32089 = data_32089;
    }

    public String getdata_320810() {
        return data_320810;
    }

    public void setdata_320810(String data_320810) {
        this.data_320810 = data_320810;
    }

    public String getdata_32093211() {
        return data_32093211;
    }

    public void setdata_32093211(String data_32093211) {
        this.data_32093211 = data_32093211;
    }

    public String getdata_32093212() {
        return data_32093212;
    }

    public void setdata_32093212(String data_32093212) {
        this.data_32093212 = data_32093212;
    }

    public String getdata_32093213() {
        return data_32093213;
    }

    public void setdata_32093213(String data_32093213) {
        this.data_32093213 = data_32093213;
    }

    public String getdata_32093214() {
        return data_32093214;
    }

    public void setdata_32093214(String data_32093214) {
        this.data_32093214 = data_32093214;
    }

    public String getdata_32091() {
        return data_32091;
    }

    public void setdata_32091(String data_32091) {
        this.data_32091 = data_32091;
    }

    public String getdata_32092() {
        return data_32092;
    }

    public void setdata_32092(String data_32092) {
        this.data_32092 = data_32092;
    }

    public String getdata_32093() {
        return data_32093;
    }

    public void setdata_32093(String data_32093) {
        this.data_32093 = data_32093;
    }

    public String getdata_32094() {
        return data_32094;
    }

    public void setdata_32094(String data_32094) {
        this.data_32094 = data_32094;
    }

    public String getdata_32095() {
        return data_32095;
    }

    public void setdata_32095(String data_32095) {
        this.data_32095 = data_32095;
    }

    public String getdata_32096() {
        return data_32096;
    }

    public void setdata_32096(String data_32096) {
        this.data_32096 = data_32096;
    }

    public String getdata_32097() {
        return data_32097;
    }

    public void setdata_32097(String data_32097) {
        this.data_32097 = data_32097;
    }

    public String getdata_32098() {
        return data_32098;
    }

    public void setdata_32098(String data_32098) {
        this.data_32098 = data_32098;
    }

    public String getdata_32099() {
        return data_32099;
    }

    public void setdata_32099(String data_32099) {
        this.data_32099 = data_32099;
    }

    public String getdata_320910() {
        return data_320910;
    }

    public void setdata_320910(String data_320910) {
        this.data_320910 = data_320910;
    }

    public String getdata_32103211() {
        return data_32103211;
    }

    public void setdata_32103211(String data_32103211) {
        this.data_32103211 = data_32103211;
    }

    public String getdata_32103212() {
        return data_32103212;
    }

    public void setdata_32103212(String data_32103212) {
        this.data_32103212 = data_32103212;
    }

    public String getdata_32103213() {
        return data_32103213;
    }

    public void setdata_32103213(String data_32103213) {
        this.data_32103213 = data_32103213;
    }

    public String getdata_32103214() {
        return data_32103214;
    }

    public void setdata_32103214(String data_32103214) {
        this.data_32103214 = data_32103214;
    }

    public String getdata_32101() {
        return data_32101;
    }

    public void setdata_32101(String data_32101) {
        this.data_32101 = data_32101;
    }

    public String getdata_32102() {
        return data_32102;
    }

    public void setdata_32102(String data_32102) {
        this.data_32102 = data_32102;
    }

    public String getdata_32103() {
        return data_32103;
    }

    public void setdata_32103(String data_32103) {
        this.data_32103 = data_32103;
    }

    public String getdata_32104() {
        return data_32104;
    }

    public void setdata_32104(String data_32104) {
        this.data_32104 = data_32104;
    }

    public String getdata_32105() {
        return data_32105;
    }

    public void setdata_32105(String data_32105) {
        this.data_32105 = data_32105;
    }

    public String getdata_32106() {
        return data_32106;
    }

    public void setdata_32106(String data_32106) {
        this.data_32106 = data_32106;
    }

    public String getdata_32107() {
        return data_32107;
    }

    public void setdata_32107(String data_32107) {
        this.data_32107 = data_32107;
    }

    public String getdata_32108() {
        return data_32108;
    }

    public void setdata_32108(String data_32108) {
        this.data_32108 = data_32108;
    }

    public String getdata_32109() {
        return data_32109;
    }

    public void setdata_32109(String data_32109) {
        this.data_32109 = data_32109;
    }

    public String getdata_321010() {
        return data_321010;
    }

    public void setdata_321010(String data_321010) {
        this.data_321010 = data_321010;
    }

    public String getdata_32213() {
        return data_32213;
    }

    public void setdata_32213(String data_32213) {
        this.data_32213 = data_32213;
    }

    public String getdata_32214() {
        return data_32214;
    }

    public void setdata_32214(String data_32214) {
        this.data_32214 = data_32214;
    }

    public String getdata_32215() {
        return data_32215;
    }

    public void setdata_32215(String data_32215) {
        this.data_32215 = data_32215;
    }

    public String getdata_32216() {
        return data_32216;
    }

    public void setdata_32216(String data_32216) {
        this.data_32216 = data_32216;
    }

    public String getdata_32217() {
        return data_32217;
    }

    public void setdata_32217(String data_32217) {
        this.data_32217 = data_32217;
    }

    public String getdata_32218() {
        return data_32218;
    }

    public void setdata_32218(String data_32218) {
        this.data_32218 = data_32218;
    }

    public String getdata_32219() {
        return data_32219;
    }

    public void setdata_32219(String data_32219) {
        this.data_32219 = data_32219;
    }

    public String getdata_322110() {
        return data_322110;
    }

    public void setdata_322110(String data_322110) {
        this.data_322110 = data_322110;
    }

    public String getdata_322111() {
        return data_322111;
    }

    public void setdata_322111(String data_322111) {
        this.data_322111 = data_322111;
    }

    public String getdata_322112() {
        return data_322112;
    }

    public void setdata_322112(String data_322112) {
        this.data_322112 = data_322112;
    }

    public String getdata_322113() {
        return data_322113;
    }

    public void setdata_322113(String data_322113) {
        this.data_322113 = data_322113;
    }

    public String getdata_322114() {
        return data_322114;
    }

    public void setdata_322114(String data_322114) {
        this.data_322114 = data_322114;
    }

    public String getdata_322115() {
        return data_322115;
    }

    public void setdata_322115(String data_322115) {
        this.data_322115 = data_322115;
    }

    public String getdata_322116() {
        return data_322116;
    }

    public void setdata_322116(String data_322116) {
        this.data_322116 = data_322116;
    }

    public String getdata_322117() {
        return data_322117;
    }

    public void setdata_322117(String data_322117) {
        this.data_322117 = data_322117;
    }

    public String getdata_322118() {
        return data_322118;
    }

    public void setdata_322118(String data_322118) {
        this.data_322118 = data_322118;
    }

    public String getdata_322119() {
        return data_322119;
    }

    public void setdata_322119(String data_322119) {
        this.data_322119 = data_322119;
    }
}
