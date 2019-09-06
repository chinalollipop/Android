package com.gmcp.gm.data;

import com.google.gson.annotations.SerializedName;

public class CPJSFTResult {

    /**
     * 5510101 : 2.2
     * 5510102 : 1.78
     * 5510103 : 1.78
     * 5510104 : 2.2
     * 5510105 : 43.5
     * 5510106 : 43.5
     * 5510107 : 21.9
     * 5510108 : 21.9
     * 5510109 : 14.7
     * 5510110 : 14.7
     * 5510111 : 11.1
     * 5510112 : 11.1
     * 5510113 : 9.1
     * 5510114 : 11.1
     * 5510115 : 11.1
     * 5510116 : 14.7
     * 5510117 : 14.7
     * 5510118 : 21.9
     * 5510119 : 21.9
     * 5510120 : 43.5
     * 5510121 : 43.5
     * 5510201 : 9.95
     * 5510202 : 9.95
     * 5510203 : 9.95
     * 5510204 : 9.95
     * 5510205 : 9.95
     * 5510206 : 9.95
     * 5510207 : 9.95
     * 5510208 : 9.95
     * 5510209 : 9.95
     * 5510210 : 9.95
     * 5510211 : 1.995
     * 5510212 : 1.995
     * 5510213 : 1.995
     * 5510214 : 1.995
     * 5510215 : 1.995
     * 5510216 : 1.995
     * 5510301 : 9.95
     * 5510302 : 9.95
     * 5510303 : 9.95
     * 5510304 : 9.95
     * 5510305 : 9.95
     * 5510306 : 9.95
     * 5510307 : 9.95
     * 5510308 : 9.95
     * 5510309 : 9.95
     * 5510310 : 9.95
     * 5510311 : 1.995
     * 5510312 : 1.995
     * 5510313 : 1.995
     * 5510314 : 1.995
     * 5510315 : 1.995
     * 5510316 : 1.995
     * 5510401 : 9.95
     * 5510402 : 9.95
     * 5510403 : 9.95
     * 5510404 : 9.95
     * 5510405 : 9.95
     * 5510406 : 9.95
     * 5510407 : 9.95
     * 5510408 : 9.95
     * 5510409 : 9.95
     * 5510410 : 9.95
     * 5510411 : 1.995
     * 5510412 : 1.995
     * 5510413 : 1.995
     * 5510414 : 1.995
     * 5510415 : 1.995
     * 5510416 : 1.995
     * 5510501 : 9.95
     * 5510502 : 9.95
     * 5510503 : 9.95
     * 5510504 : 9.95
     * 5510505 : 9.95
     * 5510506 : 9.95
     * 5510507 : 9.95
     * 5510508 : 9.95
     * 5510509 : 9.95
     * 5510510 : 9.95
     * 5510511 : 1.995
     * 5510512 : 1.995
     * 5510513 : 1.995
     * 5510514 : 1.995
     * 5510515 : 1.995
     * 5510516 : 1.995
     * 5510601 : 9.95
     * 5510602 : 9.95
     * 5510603 : 9.95
     * 5510604 : 9.95
     * 5510605 : 9.95
     * 5510606 : 9.95
     * 5510607 : 9.95
     * 5510608 : 9.95
     * 5510609 : 9.95
     * 5510610 : 9.95
     * 5510611 : 1.995
     * 5510612 : 1.995
     * 5510613 : 1.995
     * 5510614 : 1.995
     * 5510615 : 1.995
     * 5510616 : 1.995
     * 5510701 : 9.95
     * 5510702 : 9.95
     * 5510703 : 9.95
     * 5510704 : 9.95
     * 5510705 : 9.95
     * 5510706 : 9.95
     * 5510707 : 9.95
     * 5510708 : 9.95
     * 5510709 : 9.95
     * 5510710 : 9.95
     * 5510711 : 1.995
     * 5510712 : 1.995
     * 5510713 : 1.995
     * 5510714 : 1.995
     * 5510801 : 9.95
     * 5510802 : 9.95
     * 5510803 : 9.95
     * 5510804 : 9.95
     * 5510805 : 9.95
     * 5510806 : 9.95
     * 5510807 : 9.95
     * 5510808 : 9.95
     * 5510809 : 9.95
     * 5510810 : 9.95
     * 5510811 : 1.995
     * 5510812 : 1.995
     * 5510813 : 1.995
     * 5510814 : 1.995
     * 5510901 : 9.95
     * 5510902 : 9.95
     * 5510903 : 9.95
     * 5510904 : 9.95
     * 5510905 : 9.95
     * 5510906 : 9.95
     * 5510907 : 9.95
     * 5510908 : 9.95
     * 5510909 : 9.95
     * 5510910 : 9.95
     * 5510911 : 1.995
     * 5510912 : 1.995
     * 5510913 : 1.995
     * 5510914 : 1.995
     * 5511001 : 9.95
     * 5511002 : 9.95
     * 5511003 : 9.95
     * 5511004 : 9.95
     * 5511005 : 9.95
     * 5511006 : 9.95
     * 5511007 : 9.95
     * 5511008 : 9.95
     * 5511009 : 9.95
     * 5511010 : 9.95
     * 5511011 : 1.995
     * 5511012 : 1.995
     * 5511013 : 1.995
     * 5511014 : 1.995
     * 5511101 : 9.95
     * 5511102 : 9.95
     * 5511103 : 9.95
     * 5511104 : 9.95
     * 5511105 : 9.95
     * 5511106 : 9.95
     * 5511107 : 9.95
     * 5511108 : 9.95
     * 5511109 : 9.95
     * 5511110 : 9.95
     * 5511111 : 1.995
     * 5511112 : 1.995
     * 5511113 : 1.995
     * 5511114 : 1.995
     */

    @SerializedName("5510101")
    private String data_5510101;
    @SerializedName("5510102")
    private String data_5510102;
    @SerializedName("5510103")
    private String data_5510103;
    @SerializedName("5510104")
    private String data_5510104;
    @SerializedName("5510105")
    private String data_5510105;
    @SerializedName("5510106")
    private String data_5510106;
    @SerializedName("5510107")
    private String data_5510107;
    @SerializedName("5510108")
    private String data_5510108;
    @SerializedName("5510109")
    private String data_5510109;
    @SerializedName("5510110")
    private String data_5510110;
    @SerializedName("5510111")
    private String data_5510111;
    @SerializedName("5510112")
    private String data_5510112;
    @SerializedName("5510113")
    private String data_5510113;
    @SerializedName("5510114")
    private String data_5510114;
    @SerializedName("5510115")
    private String data_5510115;
    @SerializedName("5510116")
    private String data_5510116;
    @SerializedName("5510117")
    private String data_5510117;
    @SerializedName("5510118")
    private String data_5510118;
    @SerializedName("5510119")
    private String data_5510119;
    @SerializedName("5510120")
    private String data_5510120;
    @SerializedName("5510121")
    private String data_5510121;
    @SerializedName("5510201")
    private String data_5510201;
    @SerializedName("5510202")
    private String data_5510202;
    @SerializedName("5510203")
    private String data_5510203;
    @SerializedName("5510204")
    private String data_5510204;
    @SerializedName("5510205")
    private String data_5510205;
    @SerializedName("5510206")
    private String data_5510206;
    @SerializedName("5510207")
    private String data_5510207;
    @SerializedName("5510208")
    private String data_5510208;
    @SerializedName("5510209")
    private String data_5510209;
    @SerializedName("5510210")
    private String data_5510210;
    @SerializedName("5510211")
    private String data_5510211;
    @SerializedName("5510212")
    private String data_5510212;
    @SerializedName("5510213")
    private String data_5510213;
    @SerializedName("5510214")
    private String data_5510214;
    @SerializedName("5510215")
    private String data_5510215;
    @SerializedName("5510216")
    private String data_5510216;
    @SerializedName("5510301")
    private String data_5510301;
    @SerializedName("5510302")
    private String data_5510302;
    @SerializedName("5510303")
    private String data_5510303;
    @SerializedName("5510304")
    private String data_5510304;
    @SerializedName("5510305")
    private String data_5510305;
    @SerializedName("5510306")
    private String data_5510306;
    @SerializedName("5510307")
    private String data_5510307;
    @SerializedName("5510308")
    private String data_5510308;
    @SerializedName("5510309")
    private String data_5510309;
    @SerializedName("5510310")
    private String data_5510310;
    @SerializedName("5510311")
    private String data_5510311;
    @SerializedName("5510312")
    private String data_5510312;
    @SerializedName("5510313")
    private String data_5510313;
    @SerializedName("5510314")
    private String data_5510314;
    @SerializedName("5510315")
    private String data_5510315;
    @SerializedName("5510316")
    private String data_5510316;
    @SerializedName("5510401")
    private String data_5510401;
    @SerializedName("5510402")
    private String data_5510402;
    @SerializedName("5510403")
    private String data_5510403;
    @SerializedName("5510404")
    private String data_5510404;
    @SerializedName("5510405")
    private String data_5510405;
    @SerializedName("5510406")
    private String data_5510406;
    @SerializedName("5510407")
    private String data_5510407;
    @SerializedName("5510408")
    private String data_5510408;
    @SerializedName("5510409")
    private String data_5510409;
    @SerializedName("5510410")
    private String data_5510410;
    @SerializedName("5510411")
    private String data_5510411;
    @SerializedName("5510412")
    private String data_5510412;
    @SerializedName("5510413")
    private String data_5510413;
    @SerializedName("5510414")
    private String data_5510414;
    @SerializedName("5510415")
    private String data_5510415;
    @SerializedName("5510416")
    private String data_5510416;
    @SerializedName("5510501")
    private String data_5510501;
    @SerializedName("5510502")
    private String data_5510502;
    @SerializedName("5510503")
    private String data_5510503;
    @SerializedName("5510504")
    private String data_5510504;
    @SerializedName("5510505")
    private String data_5510505;
    @SerializedName("5510506")
    private String data_5510506;
    @SerializedName("5510507")
    private String data_5510507;
    @SerializedName("5510508")
    private String data_5510508;
    @SerializedName("5510509")
    private String data_5510509;
    @SerializedName("5510510")
    private String data_5510510;
    @SerializedName("5510511")
    private String data_5510511;
    @SerializedName("5510512")
    private String data_5510512;
    @SerializedName("5510513")
    private String data_5510513;
    @SerializedName("5510514")
    private String data_5510514;
    @SerializedName("5510515")
    private String data_5510515;
    @SerializedName("5510516")
    private String data_5510516;
    @SerializedName("5510601")
    private String data_5510601;
    @SerializedName("5510602")
    private String data_5510602;
    @SerializedName("5510603")
    private String data_5510603;
    @SerializedName("5510604")
    private String data_5510604;
    @SerializedName("5510605")
    private String data_5510605;
    @SerializedName("5510606")
    private String data_5510606;
    @SerializedName("5510607")
    private String data_5510607;
    @SerializedName("5510608")
    private String data_5510608;
    @SerializedName("5510609")
    private String data_5510609;
    @SerializedName("5510610")
    private String data_5510610;
    @SerializedName("5510611")
    private String data_5510611;
    @SerializedName("5510612")
    private String data_5510612;
    @SerializedName("5510613")
    private String data_5510613;
    @SerializedName("5510614")
    private String data_5510614;
    @SerializedName("5510615")
    private String data_5510615;
    @SerializedName("5510616")
    private String data_5510616;
    @SerializedName("5510701")
    private String data_5510701;
    @SerializedName("5510702")
    private String data_5510702;
    @SerializedName("5510703")
    private String data_5510703;
    @SerializedName("5510704")
    private String data_5510704;
    @SerializedName("5510705")
    private String data_5510705;
    @SerializedName("5510706")
    private String data_5510706;
    @SerializedName("5510707")
    private String data_5510707;
    @SerializedName("5510708")
    private String data_5510708;
    @SerializedName("5510709")
    private String data_5510709;
    @SerializedName("5510710")
    private String data_5510710;
    @SerializedName("5510711")
    private String data_5510711;
    @SerializedName("5510712")
    private String data_5510712;
    @SerializedName("5510713")
    private String data_5510713;
    @SerializedName("5510714")
    private String data_5510714;
    @SerializedName("5510801")
    private String data_5510801;
    @SerializedName("5510802")
    private String data_5510802;
    @SerializedName("5510803")
    private String data_5510803;
    @SerializedName("5510804")
    private String data_5510804;
    @SerializedName("5510805")
    private String data_5510805;
    @SerializedName("5510806")
    private String data_5510806;
    @SerializedName("5510807")
    private String data_5510807;
    @SerializedName("5510808")
    private String data_5510808;
    @SerializedName("5510809")
    private String data_5510809;
    @SerializedName("5510810")
    private String data_5510810;
    @SerializedName("5510811")
    private String data_5510811;
    @SerializedName("5510812")
    private String data_5510812;
    @SerializedName("5510813")
    private String data_5510813;
    @SerializedName("5510814")
    private String data_5510814;
    @SerializedName("5510901")
    private String data_5510901;
    @SerializedName("5510902")
    private String data_5510902;
    @SerializedName("5510903")
    private String data_5510903;
    @SerializedName("5510904")
    private String data_5510904;
    @SerializedName("5510905")
    private String data_5510905;
    @SerializedName("5510906")
    private String data_5510906;
    @SerializedName("5510907")
    private String data_5510907;
    @SerializedName("5510908")
    private String data_5510908;
    @SerializedName("5510909")
    private String data_5510909;
    @SerializedName("5510910")
    private String data_5510910;
    @SerializedName("5510911")
    private String data_5510911;
    @SerializedName("5510912")
    private String data_5510912;
    @SerializedName("5510913")
    private String data_5510913;
    @SerializedName("5510914")
    private String data_5510914;
    @SerializedName("5511001")
    private String data_5511001;
    @SerializedName("5511002")
    private String data_5511002;
    @SerializedName("5511003")
    private String data_5511003;
    @SerializedName("5511004")
    private String data_5511004;
    @SerializedName("5511005")
    private String data_5511005;
    @SerializedName("5511006")
    private String data_5511006;
    @SerializedName("5511007")
    private String data_5511007;
    @SerializedName("5511008")
    private String data_5511008;
    @SerializedName("5511009")
    private String data_5511009;
    @SerializedName("5511010")
    private String data_5511010;
    @SerializedName("5511011")
    private String data_5511011;
    @SerializedName("5511012")
    private String data_5511012;
    @SerializedName("5511013")
    private String data_5511013;
    @SerializedName("5511014")
    private String data_5511014;
    @SerializedName("5511101")
    private String data_5511101;
    @SerializedName("5511102")
    private String data_5511102;
    @SerializedName("5511103")
    private String data_5511103;
    @SerializedName("5511104")
    private String data_5511104;
    @SerializedName("5511105")
    private String data_5511105;
    @SerializedName("5511106")
    private String data_5511106;
    @SerializedName("5511107")
    private String data_5511107;
    @SerializedName("5511108")
    private String data_5511108;
    @SerializedName("5511109")
    private String data_5511109;
    @SerializedName("5511110")
    private String data_5511110;
    @SerializedName("5511111")
    private String data_5511111;
    @SerializedName("5511112")
    private String data_5511112;
    @SerializedName("5511113")
    private String data_5511113;
    @SerializedName("5511114")
    private String data_5511114;

    public String getdata_5510101() {
        return data_5510101;
    }

    public void setdata_5510101(String data_5510101) {
        this.data_5510101 = data_5510101;
    }

    public String getdata_5510102() {
        return data_5510102;
    }

    public void setdata_5510102(String data_5510102) {
        this.data_5510102 = data_5510102;
    }

    public String getdata_5510103() {
        return data_5510103;
    }

    public void setdata_5510103(String data_5510103) {
        this.data_5510103 = data_5510103;
    }

    public String getdata_5510104() {
        return data_5510104;
    }

    public void setdata_5510104(String data_5510104) {
        this.data_5510104 = data_5510104;
    }

    public String getdata_5510105() {
        return data_5510105;
    }

    public void setdata_5510105(String data_5510105) {
        this.data_5510105 = data_5510105;
    }

    public String getdata_5510106() {
        return data_5510106;
    }

    public void setdata_5510106(String data_5510106) {
        this.data_5510106 = data_5510106;
    }

    public String getdata_5510107() {
        return data_5510107;
    }

    public void setdata_5510107(String data_5510107) {
        this.data_5510107 = data_5510107;
    }

    public String getdata_5510108() {
        return data_5510108;
    }

    public void setdata_5510108(String data_5510108) {
        this.data_5510108 = data_5510108;
    }

    public String getdata_5510109() {
        return data_5510109;
    }

    public void setdata_5510109(String data_5510109) {
        this.data_5510109 = data_5510109;
    }

    public String getdata_5510110() {
        return data_5510110;
    }

    public void setdata_5510110(String data_5510110) {
        this.data_5510110 = data_5510110;
    }

    public String getdata_5510111() {
        return data_5510111;
    }

    public void setdata_5510111(String data_5510111) {
        this.data_5510111 = data_5510111;
    }

    public String getdata_5510112() {
        return data_5510112;
    }

    public void setdata_5510112(String data_5510112) {
        this.data_5510112 = data_5510112;
    }

    public String getdata_5510113() {
        return data_5510113;
    }

    public void setdata_5510113(String data_5510113) {
        this.data_5510113 = data_5510113;
    }

    public String getdata_5510114() {
        return data_5510114;
    }

    public void setdata_5510114(String data_5510114) {
        this.data_5510114 = data_5510114;
    }

    public String getdata_5510115() {
        return data_5510115;
    }

    public void setdata_5510115(String data_5510115) {
        this.data_5510115 = data_5510115;
    }

    public String getdata_5510116() {
        return data_5510116;
    }

    public void setdata_5510116(String data_5510116) {
        this.data_5510116 = data_5510116;
    }

    public String getdata_5510117() {
        return data_5510117;
    }

    public void setdata_5510117(String data_5510117) {
        this.data_5510117 = data_5510117;
    }

    public String getdata_5510118() {
        return data_5510118;
    }

    public void setdata_5510118(String data_5510118) {
        this.data_5510118 = data_5510118;
    }

    public String getdata_5510119() {
        return data_5510119;
    }

    public void setdata_5510119(String data_5510119) {
        this.data_5510119 = data_5510119;
    }

    public String getdata_5510120() {
        return data_5510120;
    }

    public void setdata_5510120(String data_5510120) {
        this.data_5510120 = data_5510120;
    }

    public String getdata_5510121() {
        return data_5510121;
    }

    public void setdata_5510121(String data_5510121) {
        this.data_5510121 = data_5510121;
    }

    public String getdata_5510201() {
        return data_5510201;
    }

    public void setdata_5510201(String data_5510201) {
        this.data_5510201 = data_5510201;
    }

    public String getdata_5510202() {
        return data_5510202;
    }

    public void setdata_5510202(String data_5510202) {
        this.data_5510202 = data_5510202;
    }

    public String getdata_5510203() {
        return data_5510203;
    }

    public void setdata_5510203(String data_5510203) {
        this.data_5510203 = data_5510203;
    }

    public String getdata_5510204() {
        return data_5510204;
    }

    public void setdata_5510204(String data_5510204) {
        this.data_5510204 = data_5510204;
    }

    public String getdata_5510205() {
        return data_5510205;
    }

    public void setdata_5510205(String data_5510205) {
        this.data_5510205 = data_5510205;
    }

    public String getdata_5510206() {
        return data_5510206;
    }

    public void setdata_5510206(String data_5510206) {
        this.data_5510206 = data_5510206;
    }

    public String getdata_5510207() {
        return data_5510207;
    }

    public void setdata_5510207(String data_5510207) {
        this.data_5510207 = data_5510207;
    }

    public String getdata_5510208() {
        return data_5510208;
    }

    public void setdata_5510208(String data_5510208) {
        this.data_5510208 = data_5510208;
    }

    public String getdata_5510209() {
        return data_5510209;
    }

    public void setdata_5510209(String data_5510209) {
        this.data_5510209 = data_5510209;
    }

    public String getdata_5510210() {
        return data_5510210;
    }

    public void setdata_5510210(String data_5510210) {
        this.data_5510210 = data_5510210;
    }

    public String getdata_5510211() {
        return data_5510211;
    }

    public void setdata_5510211(String data_5510211) {
        this.data_5510211 = data_5510211;
    }

    public String getdata_5510212() {
        return data_5510212;
    }

    public void setdata_5510212(String data_5510212) {
        this.data_5510212 = data_5510212;
    }

    public String getdata_5510213() {
        return data_5510213;
    }

    public void setdata_5510213(String data_5510213) {
        this.data_5510213 = data_5510213;
    }

    public String getdata_5510214() {
        return data_5510214;
    }

    public void setdata_5510214(String data_5510214) {
        this.data_5510214 = data_5510214;
    }

    public String getdata_5510215() {
        return data_5510215;
    }

    public void setdata_5510215(String data_5510215) {
        this.data_5510215 = data_5510215;
    }

    public String getdata_5510216() {
        return data_5510216;
    }

    public void setdata_5510216(String data_5510216) {
        this.data_5510216 = data_5510216;
    }

    public String getdata_5510301() {
        return data_5510301;
    }

    public void setdata_5510301(String data_5510301) {
        this.data_5510301 = data_5510301;
    }

    public String getdata_5510302() {
        return data_5510302;
    }

    public void setdata_5510302(String data_5510302) {
        this.data_5510302 = data_5510302;
    }

    public String getdata_5510303() {
        return data_5510303;
    }

    public void setdata_5510303(String data_5510303) {
        this.data_5510303 = data_5510303;
    }

    public String getdata_5510304() {
        return data_5510304;
    }

    public void setdata_5510304(String data_5510304) {
        this.data_5510304 = data_5510304;
    }

    public String getdata_5510305() {
        return data_5510305;
    }

    public void setdata_5510305(String data_5510305) {
        this.data_5510305 = data_5510305;
    }

    public String getdata_5510306() {
        return data_5510306;
    }

    public void setdata_5510306(String data_5510306) {
        this.data_5510306 = data_5510306;
    }

    public String getdata_5510307() {
        return data_5510307;
    }

    public void setdata_5510307(String data_5510307) {
        this.data_5510307 = data_5510307;
    }

    public String getdata_5510308() {
        return data_5510308;
    }

    public void setdata_5510308(String data_5510308) {
        this.data_5510308 = data_5510308;
    }

    public String getdata_5510309() {
        return data_5510309;
    }

    public void setdata_5510309(String data_5510309) {
        this.data_5510309 = data_5510309;
    }

    public String getdata_5510310() {
        return data_5510310;
    }

    public void setdata_5510310(String data_5510310) {
        this.data_5510310 = data_5510310;
    }

    public String getdata_5510311() {
        return data_5510311;
    }

    public void setdata_5510311(String data_5510311) {
        this.data_5510311 = data_5510311;
    }

    public String getdata_5510312() {
        return data_5510312;
    }

    public void setdata_5510312(String data_5510312) {
        this.data_5510312 = data_5510312;
    }

    public String getdata_5510313() {
        return data_5510313;
    }

    public void setdata_5510313(String data_5510313) {
        this.data_5510313 = data_5510313;
    }

    public String getdata_5510314() {
        return data_5510314;
    }

    public void setdata_5510314(String data_5510314) {
        this.data_5510314 = data_5510314;
    }

    public String getdata_5510315() {
        return data_5510315;
    }

    public void setdata_5510315(String data_5510315) {
        this.data_5510315 = data_5510315;
    }

    public String getdata_5510316() {
        return data_5510316;
    }

    public void setdata_5510316(String data_5510316) {
        this.data_5510316 = data_5510316;
    }

    public String getdata_5510401() {
        return data_5510401;
    }

    public void setdata_5510401(String data_5510401) {
        this.data_5510401 = data_5510401;
    }

    public String getdata_5510402() {
        return data_5510402;
    }

    public void setdata_5510402(String data_5510402) {
        this.data_5510402 = data_5510402;
    }

    public String getdata_5510403() {
        return data_5510403;
    }

    public void setdata_5510403(String data_5510403) {
        this.data_5510403 = data_5510403;
    }

    public String getdata_5510404() {
        return data_5510404;
    }

    public void setdata_5510404(String data_5510404) {
        this.data_5510404 = data_5510404;
    }

    public String getdata_5510405() {
        return data_5510405;
    }

    public void setdata_5510405(String data_5510405) {
        this.data_5510405 = data_5510405;
    }

    public String getdata_5510406() {
        return data_5510406;
    }

    public void setdata_5510406(String data_5510406) {
        this.data_5510406 = data_5510406;
    }

    public String getdata_5510407() {
        return data_5510407;
    }

    public void setdata_5510407(String data_5510407) {
        this.data_5510407 = data_5510407;
    }

    public String getdata_5510408() {
        return data_5510408;
    }

    public void setdata_5510408(String data_5510408) {
        this.data_5510408 = data_5510408;
    }

    public String getdata_5510409() {
        return data_5510409;
    }

    public void setdata_5510409(String data_5510409) {
        this.data_5510409 = data_5510409;
    }

    public String getdata_5510410() {
        return data_5510410;
    }

    public void setdata_5510410(String data_5510410) {
        this.data_5510410 = data_5510410;
    }

    public String getdata_5510411() {
        return data_5510411;
    }

    public void setdata_5510411(String data_5510411) {
        this.data_5510411 = data_5510411;
    }

    public String getdata_5510412() {
        return data_5510412;
    }

    public void setdata_5510412(String data_5510412) {
        this.data_5510412 = data_5510412;
    }

    public String getdata_5510413() {
        return data_5510413;
    }

    public void setdata_5510413(String data_5510413) {
        this.data_5510413 = data_5510413;
    }

    public String getdata_5510414() {
        return data_5510414;
    }

    public void setdata_5510414(String data_5510414) {
        this.data_5510414 = data_5510414;
    }

    public String getdata_5510415() {
        return data_5510415;
    }

    public void setdata_5510415(String data_5510415) {
        this.data_5510415 = data_5510415;
    }

    public String getdata_5510416() {
        return data_5510416;
    }

    public void setdata_5510416(String data_5510416) {
        this.data_5510416 = data_5510416;
    }

    public String getdata_5510501() {
        return data_5510501;
    }

    public void setdata_5510501(String data_5510501) {
        this.data_5510501 = data_5510501;
    }

    public String getdata_5510502() {
        return data_5510502;
    }

    public void setdata_5510502(String data_5510502) {
        this.data_5510502 = data_5510502;
    }

    public String getdata_5510503() {
        return data_5510503;
    }

    public void setdata_5510503(String data_5510503) {
        this.data_5510503 = data_5510503;
    }

    public String getdata_5510504() {
        return data_5510504;
    }

    public void setdata_5510504(String data_5510504) {
        this.data_5510504 = data_5510504;
    }

    public String getdata_5510505() {
        return data_5510505;
    }

    public void setdata_5510505(String data_5510505) {
        this.data_5510505 = data_5510505;
    }

    public String getdata_5510506() {
        return data_5510506;
    }

    public void setdata_5510506(String data_5510506) {
        this.data_5510506 = data_5510506;
    }

    public String getdata_5510507() {
        return data_5510507;
    }

    public void setdata_5510507(String data_5510507) {
        this.data_5510507 = data_5510507;
    }

    public String getdata_5510508() {
        return data_5510508;
    }

    public void setdata_5510508(String data_5510508) {
        this.data_5510508 = data_5510508;
    }

    public String getdata_5510509() {
        return data_5510509;
    }

    public void setdata_5510509(String data_5510509) {
        this.data_5510509 = data_5510509;
    }

    public String getdata_5510510() {
        return data_5510510;
    }

    public void setdata_5510510(String data_5510510) {
        this.data_5510510 = data_5510510;
    }

    public String getdata_5510511() {
        return data_5510511;
    }

    public void setdata_5510511(String data_5510511) {
        this.data_5510511 = data_5510511;
    }

    public String getdata_5510512() {
        return data_5510512;
    }

    public void setdata_5510512(String data_5510512) {
        this.data_5510512 = data_5510512;
    }

    public String getdata_5510513() {
        return data_5510513;
    }

    public void setdata_5510513(String data_5510513) {
        this.data_5510513 = data_5510513;
    }

    public String getdata_5510514() {
        return data_5510514;
    }

    public void setdata_5510514(String data_5510514) {
        this.data_5510514 = data_5510514;
    }

    public String getdata_5510515() {
        return data_5510515;
    }

    public void setdata_5510515(String data_5510515) {
        this.data_5510515 = data_5510515;
    }

    public String getdata_5510516() {
        return data_5510516;
    }

    public void setdata_5510516(String data_5510516) {
        this.data_5510516 = data_5510516;
    }

    public String getdata_5510601() {
        return data_5510601;
    }

    public void setdata_5510601(String data_5510601) {
        this.data_5510601 = data_5510601;
    }

    public String getdata_5510602() {
        return data_5510602;
    }

    public void setdata_5510602(String data_5510602) {
        this.data_5510602 = data_5510602;
    }

    public String getdata_5510603() {
        return data_5510603;
    }

    public void setdata_5510603(String data_5510603) {
        this.data_5510603 = data_5510603;
    }

    public String getdata_5510604() {
        return data_5510604;
    }

    public void setdata_5510604(String data_5510604) {
        this.data_5510604 = data_5510604;
    }

    public String getdata_5510605() {
        return data_5510605;
    }

    public void setdata_5510605(String data_5510605) {
        this.data_5510605 = data_5510605;
    }

    public String getdata_5510606() {
        return data_5510606;
    }

    public void setdata_5510606(String data_5510606) {
        this.data_5510606 = data_5510606;
    }

    public String getdata_5510607() {
        return data_5510607;
    }

    public void setdata_5510607(String data_5510607) {
        this.data_5510607 = data_5510607;
    }

    public String getdata_5510608() {
        return data_5510608;
    }

    public void setdata_5510608(String data_5510608) {
        this.data_5510608 = data_5510608;
    }

    public String getdata_5510609() {
        return data_5510609;
    }

    public void setdata_5510609(String data_5510609) {
        this.data_5510609 = data_5510609;
    }

    public String getdata_5510610() {
        return data_5510610;
    }

    public void setdata_5510610(String data_5510610) {
        this.data_5510610 = data_5510610;
    }

    public String getdata_5510611() {
        return data_5510611;
    }

    public void setdata_5510611(String data_5510611) {
        this.data_5510611 = data_5510611;
    }

    public String getdata_5510612() {
        return data_5510612;
    }

    public void setdata_5510612(String data_5510612) {
        this.data_5510612 = data_5510612;
    }

    public String getdata_5510613() {
        return data_5510613;
    }

    public void setdata_5510613(String data_5510613) {
        this.data_5510613 = data_5510613;
    }

    public String getdata_5510614() {
        return data_5510614;
    }

    public void setdata_5510614(String data_5510614) {
        this.data_5510614 = data_5510614;
    }

    public String getdata_5510615() {
        return data_5510615;
    }

    public void setdata_5510615(String data_5510615) {
        this.data_5510615 = data_5510615;
    }

    public String getdata_5510616() {
        return data_5510616;
    }

    public void setdata_5510616(String data_5510616) {
        this.data_5510616 = data_5510616;
    }

    public String getdata_5510701() {
        return data_5510701;
    }

    public void setdata_5510701(String data_5510701) {
        this.data_5510701 = data_5510701;
    }

    public String getdata_5510702() {
        return data_5510702;
    }

    public void setdata_5510702(String data_5510702) {
        this.data_5510702 = data_5510702;
    }

    public String getdata_5510703() {
        return data_5510703;
    }

    public void setdata_5510703(String data_5510703) {
        this.data_5510703 = data_5510703;
    }

    public String getdata_5510704() {
        return data_5510704;
    }

    public void setdata_5510704(String data_5510704) {
        this.data_5510704 = data_5510704;
    }

    public String getdata_5510705() {
        return data_5510705;
    }

    public void setdata_5510705(String data_5510705) {
        this.data_5510705 = data_5510705;
    }

    public String getdata_5510706() {
        return data_5510706;
    }

    public void setdata_5510706(String data_5510706) {
        this.data_5510706 = data_5510706;
    }

    public String getdata_5510707() {
        return data_5510707;
    }

    public void setdata_5510707(String data_5510707) {
        this.data_5510707 = data_5510707;
    }

    public String getdata_5510708() {
        return data_5510708;
    }

    public void setdata_5510708(String data_5510708) {
        this.data_5510708 = data_5510708;
    }

    public String getdata_5510709() {
        return data_5510709;
    }

    public void setdata_5510709(String data_5510709) {
        this.data_5510709 = data_5510709;
    }

    public String getdata_5510710() {
        return data_5510710;
    }

    public void setdata_5510710(String data_5510710) {
        this.data_5510710 = data_5510710;
    }

    public String getdata_5510711() {
        return data_5510711;
    }

    public void setdata_5510711(String data_5510711) {
        this.data_5510711 = data_5510711;
    }

    public String getdata_5510712() {
        return data_5510712;
    }

    public void setdata_5510712(String data_5510712) {
        this.data_5510712 = data_5510712;
    }

    public String getdata_5510713() {
        return data_5510713;
    }

    public void setdata_5510713(String data_5510713) {
        this.data_5510713 = data_5510713;
    }

    public String getdata_5510714() {
        return data_5510714;
    }

    public void setdata_5510714(String data_5510714) {
        this.data_5510714 = data_5510714;
    }

    public String getdata_5510801() {
        return data_5510801;
    }

    public void setdata_5510801(String data_5510801) {
        this.data_5510801 = data_5510801;
    }

    public String getdata_5510802() {
        return data_5510802;
    }

    public void setdata_5510802(String data_5510802) {
        this.data_5510802 = data_5510802;
    }

    public String getdata_5510803() {
        return data_5510803;
    }

    public void setdata_5510803(String data_5510803) {
        this.data_5510803 = data_5510803;
    }

    public String getdata_5510804() {
        return data_5510804;
    }

    public void setdata_5510804(String data_5510804) {
        this.data_5510804 = data_5510804;
    }

    public String getdata_5510805() {
        return data_5510805;
    }

    public void setdata_5510805(String data_5510805) {
        this.data_5510805 = data_5510805;
    }

    public String getdata_5510806() {
        return data_5510806;
    }

    public void setdata_5510806(String data_5510806) {
        this.data_5510806 = data_5510806;
    }

    public String getdata_5510807() {
        return data_5510807;
    }

    public void setdata_5510807(String data_5510807) {
        this.data_5510807 = data_5510807;
    }

    public String getdata_5510808() {
        return data_5510808;
    }

    public void setdata_5510808(String data_5510808) {
        this.data_5510808 = data_5510808;
    }

    public String getdata_5510809() {
        return data_5510809;
    }

    public void setdata_5510809(String data_5510809) {
        this.data_5510809 = data_5510809;
    }

    public String getdata_5510810() {
        return data_5510810;
    }

    public void setdata_5510810(String data_5510810) {
        this.data_5510810 = data_5510810;
    }

    public String getdata_5510811() {
        return data_5510811;
    }

    public void setdata_5510811(String data_5510811) {
        this.data_5510811 = data_5510811;
    }

    public String getdata_5510812() {
        return data_5510812;
    }

    public void setdata_5510812(String data_5510812) {
        this.data_5510812 = data_5510812;
    }

    public String getdata_5510813() {
        return data_5510813;
    }

    public void setdata_5510813(String data_5510813) {
        this.data_5510813 = data_5510813;
    }

    public String getdata_5510814() {
        return data_5510814;
    }

    public void setdata_5510814(String data_5510814) {
        this.data_5510814 = data_5510814;
    }

    public String getdata_5510901() {
        return data_5510901;
    }

    public void setdata_5510901(String data_5510901) {
        this.data_5510901 = data_5510901;
    }

    public String getdata_5510902() {
        return data_5510902;
    }

    public void setdata_5510902(String data_5510902) {
        this.data_5510902 = data_5510902;
    }

    public String getdata_5510903() {
        return data_5510903;
    }

    public void setdata_5510903(String data_5510903) {
        this.data_5510903 = data_5510903;
    }

    public String getdata_5510904() {
        return data_5510904;
    }

    public void setdata_5510904(String data_5510904) {
        this.data_5510904 = data_5510904;
    }

    public String getdata_5510905() {
        return data_5510905;
    }

    public void setdata_5510905(String data_5510905) {
        this.data_5510905 = data_5510905;
    }

    public String getdata_5510906() {
        return data_5510906;
    }

    public void setdata_5510906(String data_5510906) {
        this.data_5510906 = data_5510906;
    }

    public String getdata_5510907() {
        return data_5510907;
    }

    public void setdata_5510907(String data_5510907) {
        this.data_5510907 = data_5510907;
    }

    public String getdata_5510908() {
        return data_5510908;
    }

    public void setdata_5510908(String data_5510908) {
        this.data_5510908 = data_5510908;
    }

    public String getdata_5510909() {
        return data_5510909;
    }

    public void setdata_5510909(String data_5510909) {
        this.data_5510909 = data_5510909;
    }

    public String getdata_5510910() {
        return data_5510910;
    }

    public void setdata_5510910(String data_5510910) {
        this.data_5510910 = data_5510910;
    }

    public String getdata_5510911() {
        return data_5510911;
    }

    public void setdata_5510911(String data_5510911) {
        this.data_5510911 = data_5510911;
    }

    public String getdata_5510912() {
        return data_5510912;
    }

    public void setdata_5510912(String data_5510912) {
        this.data_5510912 = data_5510912;
    }

    public String getdata_5510913() {
        return data_5510913;
    }

    public void setdata_5510913(String data_5510913) {
        this.data_5510913 = data_5510913;
    }

    public String getdata_5510914() {
        return data_5510914;
    }

    public void setdata_5510914(String data_5510914) {
        this.data_5510914 = data_5510914;
    }

    public String getdata_5511001() {
        return data_5511001;
    }

    public void setdata_5511001(String data_5511001) {
        this.data_5511001 = data_5511001;
    }

    public String getdata_5511002() {
        return data_5511002;
    }

    public void setdata_5511002(String data_5511002) {
        this.data_5511002 = data_5511002;
    }

    public String getdata_5511003() {
        return data_5511003;
    }

    public void setdata_5511003(String data_5511003) {
        this.data_5511003 = data_5511003;
    }

    public String getdata_5511004() {
        return data_5511004;
    }

    public void setdata_5511004(String data_5511004) {
        this.data_5511004 = data_5511004;
    }

    public String getdata_5511005() {
        return data_5511005;
    }

    public void setdata_5511005(String data_5511005) {
        this.data_5511005 = data_5511005;
    }

    public String getdata_5511006() {
        return data_5511006;
    }

    public void setdata_5511006(String data_5511006) {
        this.data_5511006 = data_5511006;
    }

    public String getdata_5511007() {
        return data_5511007;
    }

    public void setdata_5511007(String data_5511007) {
        this.data_5511007 = data_5511007;
    }

    public String getdata_5511008() {
        return data_5511008;
    }

    public void setdata_5511008(String data_5511008) {
        this.data_5511008 = data_5511008;
    }

    public String getdata_5511009() {
        return data_5511009;
    }

    public void setdata_5511009(String data_5511009) {
        this.data_5511009 = data_5511009;
    }

    public String getdata_5511010() {
        return data_5511010;
    }

    public void setdata_5511010(String data_5511010) {
        this.data_5511010 = data_5511010;
    }

    public String getdata_5511011() {
        return data_5511011;
    }

    public void setdata_5511011(String data_5511011) {
        this.data_5511011 = data_5511011;
    }

    public String getdata_5511012() {
        return data_5511012;
    }

    public void setdata_5511012(String data_5511012) {
        this.data_5511012 = data_5511012;
    }

    public String getdata_5511013() {
        return data_5511013;
    }

    public void setdata_5511013(String data_5511013) {
        this.data_5511013 = data_5511013;
    }

    public String getdata_5511014() {
        return data_5511014;
    }

    public void setdata_5511014(String data_5511014) {
        this.data_5511014 = data_5511014;
    }

    public String getdata_5511101() {
        return data_5511101;
    }

    public void setdata_5511101(String data_5511101) {
        this.data_5511101 = data_5511101;
    }

    public String getdata_5511102() {
        return data_5511102;
    }

    public void setdata_5511102(String data_5511102) {
        this.data_5511102 = data_5511102;
    }

    public String getdata_5511103() {
        return data_5511103;
    }

    public void setdata_5511103(String data_5511103) {
        this.data_5511103 = data_5511103;
    }

    public String getdata_5511104() {
        return data_5511104;
    }

    public void setdata_5511104(String data_5511104) {
        this.data_5511104 = data_5511104;
    }

    public String getdata_5511105() {
        return data_5511105;
    }

    public void setdata_5511105(String data_5511105) {
        this.data_5511105 = data_5511105;
    }

    public String getdata_5511106() {
        return data_5511106;
    }

    public void setdata_5511106(String data_5511106) {
        this.data_5511106 = data_5511106;
    }

    public String getdata_5511107() {
        return data_5511107;
    }

    public void setdata_5511107(String data_5511107) {
        this.data_5511107 = data_5511107;
    }

    public String getdata_5511108() {
        return data_5511108;
    }

    public void setdata_5511108(String data_5511108) {
        this.data_5511108 = data_5511108;
    }

    public String getdata_5511109() {
        return data_5511109;
    }

    public void setdata_5511109(String data_5511109) {
        this.data_5511109 = data_5511109;
    }

    public String getdata_5511110() {
        return data_5511110;
    }

    public void setdata_5511110(String data_5511110) {
        this.data_5511110 = data_5511110;
    }

    public String getdata_5511111() {
        return data_5511111;
    }

    public void setdata_5511111(String data_5511111) {
        this.data_5511111 = data_5511111;
    }

    public String getdata_5511112() {
        return data_5511112;
    }

    public void setdata_5511112(String data_5511112) {
        this.data_5511112 = data_5511112;
    }

    public String getdata_5511113() {
        return data_5511113;
    }

    public void setdata_5511113(String data_5511113) {
        this.data_5511113 = data_5511113;
    }

    public String getdata_5511114() {
        return data_5511114;
    }

    public void setdata_5511114(String data_5511114) {
        this.data_5511114 = data_5511114;
    }
}
