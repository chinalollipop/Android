package com.hfcp.hf.data;

import com.google.gson.annotations.SerializedName;

public class CPBJSCResult {

    /**
     * 501001 : 2.19
     * 501002 : 1.78
     * 501003 : 1.78
     * 501004 : 2.19
     * 501005 : 43.5
     * 501006 : 43.5
     * 501007 : 21.9
     * 501008 : 21.9
     * 501009 : 14.7
     * 501010 : 14.7
     * 501011 : 10.5
     * 501012 : 10.5
     * 501013 : 8.5
     * 501014 : 10.5
     * 501015 : 10.5
     * 501016 : 14.7
     * 501017 : 14.7
     * 501018 : 21.9
     * 501019 : 21.9
     * 501020 : 43.5
     * 501021 : 43.5
     * 501101 : 1.995
     * 501102 : 1.995
     * 501103 : 1.995
     * 501104 : 1.995
     * 501105 : 1.995
     * 501106 : 1.995
     * 501107 : 9.95
     * 501108 : 9.95
     * 501109 : 9.95
     * 501110 : 9.95
     * 501111 : 9.95
     * 501112 : 9.95
     * 501113 : 9.95
     * 501114 : 9.95
     * 501115 : 9.95
     * 501116 : 9.95
     * 501201 : 1.995
     * 501202 : 1.995
     * 501203 : 1.995
     * 501204 : 1.995
     * 501205 : 1.995
     * 501206 : 1.995
     * 501207 : 9.95
     * 501208 : 9.95
     * 501209 : 9.95
     * 501210 : 9.95
     * 501211 : 9.95
     * 501212 : 9.95
     * 501213 : 9.95
     * 501214 : 9.95
     * 501215 : 9.95
     * 501216 : 9.95
     * 501301 : 1.995
     * 501302 : 1.995
     * 501303 : 1.995
     * 501304 : 1.995
     * 501305 : 1.995
     * 501306 : 1.995
     * 501307 : 9.95
     * 501308 : 9.95
     * 501309 : 9.95
     * 501310 : 9.95
     * 501311 : 9.95
     * 501312 : 9.95
     * 501313 : 9.95
     * 501314 : 9.95
     * 501315 : 9.95
     * 501316 : 9.95
     * 501401 : 1.995
     * 501402 : 1.995
     * 501403 : 1.995
     * 501404 : 1.995
     * 501405 : 1.995
     * 501406 : 1.995
     * 501407 : 9.95
     * 501408 : 9.95
     * 501409 : 9.95
     * 501410 : 9.95
     * 501411 : 9.95
     * 501412 : 9.95
     * 501413 : 9.95
     * 501414 : 9.95
     * 501415 : 9.95
     * 501416 : 9.95
     * 501501 : 1.995
     * 501502 : 1.995
     * 501503 : 1.995
     * 501504 : 1.995
     * 501505 : 1.995
     * 501506 : 1.995
     * 501507 : 9.95
     * 501508 : 9.95
     * 501509 : 9.95
     * 501510 : 9.95
     * 501511 : 9.95
     * 501512 : 9.95
     * 501513 : 9.95
     * 501514 : 9.95
     * 501515 : 9.95
     * 501516 : 9.95
     * 501601 : 1.995
     * 501602 : 1.995
     * 501603 : 1.995
     * 501604 : 1.995
     * 501607 : 9.95
     * 501608 : 9.95
     * 501609 : 9.95
     * 501610 : 9.95
     * 501611 : 9.95
     * 501612 : 9.95
     * 501613 : 9.95
     * 501614 : 9.95
     * 501615 : 9.95
     * 501616 : 9.95
     * 501701 : 1.995
     * 501702 : 1.995
     * 501703 : 1.995
     * 501704 : 1.995
     * 501707 : 9.95
     * 501708 : 9.95
     * 501709 : 9.95
     * 501710 : 9.95
     * 501711 : 9.95
     * 501712 : 9.95
     * 501713 : 9.95
     * 501714 : 9.95
     * 501715 : 9.95
     * 501716 : 9.95
     * 501801 : 1.995
     * 501802 : 1.995
     * 501803 : 1.995
     * 501804 : 1.995
     * 501807 : 9.95
     * 501808 : 9.95
     * 501809 : 9.95
     * 501810 : 9.95
     * 501811 : 9.95
     * 501812 : 9.95
     * 501813 : 9.95
     * 501814 : 9.95
     * 501815 : 9.95
     * 501816 : 9.95
     * 501901 : 1.995
     * 501902 : 1.995
     * 501903 : 1.995
     * 501904 : 1.995
     * 501907 : 9.95
     * 501908 : 9.95
     * 501909 : 9.95
     * 501910 : 9.95
     * 501911 : 9.95
     * 501912 : 9.95
     * 501913 : 9.95
     * 501914 : 9.95
     * 501915 : 9.95
     * 501916 : 9.95
     * 502001 : 1.995
     * 502002 : 1.995
     * 502003 : 1.995
     * 502004 : 1.995
     * 502007 : 9.95
     * 502008 : 9.95
     * 502009 : 9.95
     * 502010 : 9.95
     * 502011 : 9.95
     * 502012 : 9.95
     * 502013 : 9.95
     * 502014 : 9.95
     * 502015 : 9.95
     * 502016 : 9.95
     */

    @SerializedName("501001")
    private String data_501001;
    @SerializedName("501002")
    private String data_501002;
    @SerializedName("501003")
    private String data_501003;
    @SerializedName("501004")
    private String data_501004;
    @SerializedName("501005")
    private String data_501005;
    @SerializedName("501006")
    private String data_501006;
    @SerializedName("501007")
    private String data_501007;
    @SerializedName("501008")
    private String data_501008;
    @SerializedName("501009")
    private String data_501009;
    @SerializedName("501010")
    private String data_501010;
    @SerializedName("501011")
    private String data_501011;
    @SerializedName("501012")
    private String data_501012;
    @SerializedName("501013")
    private String data_501013;
    @SerializedName("501014")
    private String data_501014;
    @SerializedName("501015")
    private String data_501015;
    @SerializedName("501016")
    private String data_501016;
    @SerializedName("501017")
    private String data_501017;
    @SerializedName("501018")
    private String data_501018;
    @SerializedName("501019")
    private String data_501019;
    @SerializedName("501020")
    private String data_501020;
    @SerializedName("501021")
    private String data_501021;
    @SerializedName("501101")
    private String data_501101;
    @SerializedName("501102")
    private String data_501102;
    @SerializedName("501103")
    private String data_501103;
    @SerializedName("501104")
    private String data_501104;
    @SerializedName("501105")
    private String data_501105;
    @SerializedName("501106")
    private String data_501106;
    @SerializedName("501107")
    private String data_501107;
    @SerializedName("501108")
    private String data_501108;
    @SerializedName("501109")
    private String data_501109;
    @SerializedName("501110")
    private String data_501110;
    @SerializedName("501111")
    private String data_501111;
    @SerializedName("501112")
    private String data_501112;
    @SerializedName("501113")
    private String data_501113;
    @SerializedName("501114")
    private String data_501114;
    @SerializedName("501115")
    private String data_501115;
    @SerializedName("501116")
    private String data_501116;
    @SerializedName("501201")
    private String data_501201;
    @SerializedName("501202")
    private String data_501202;
    @SerializedName("501203")
    private String data_501203;
    @SerializedName("501204")
    private String data_501204;
    @SerializedName("501205")
    private String data_501205;
    @SerializedName("501206")
    private String data_501206;
    @SerializedName("501207")
    private String data_501207;
    @SerializedName("501208")
    private String data_501208;
    @SerializedName("501209")
    private String data_501209;
    @SerializedName("501210")
    private String data_501210;
    @SerializedName("501211")
    private String data_501211;
    @SerializedName("501212")
    private String data_501212;
    @SerializedName("501213")
    private String data_501213;
    @SerializedName("501214")
    private String data_501214;
    @SerializedName("501215")
    private String data_501215;
    @SerializedName("501216")
    private String data_501216;
    @SerializedName("501301")
    private String data_501301;
    @SerializedName("501302")
    private String data_501302;
    @SerializedName("501303")
    private String data_501303;
    @SerializedName("501304")
    private String data_501304;
    @SerializedName("501305")
    private String data_501305;
    @SerializedName("501306")
    private String data_501306;
    @SerializedName("501307")
    private String data_501307;
    @SerializedName("501308")
    private String data_501308;
    @SerializedName("501309")
    private String data_501309;
    @SerializedName("501310")
    private String data_501310;
    @SerializedName("501311")
    private String data_501311;
    @SerializedName("501312")
    private String data_501312;
    @SerializedName("501313")
    private String data_501313;
    @SerializedName("501314")
    private String data_501314;
    @SerializedName("501315")
    private String data_501315;
    @SerializedName("501316")
    private String data_501316;
    @SerializedName("501401")
    private String data_501401;
    @SerializedName("501402")
    private String data_501402;
    @SerializedName("501403")
    private String data_501403;
    @SerializedName("501404")
    private String data_501404;
    @SerializedName("501405")
    private String data_501405;
    @SerializedName("501406")
    private String data_501406;
    @SerializedName("501407")
    private String data_501407;
    @SerializedName("501408")
    private String data_501408;
    @SerializedName("501409")
    private String data_501409;
    @SerializedName("501410")
    private String data_501410;
    @SerializedName("501411")
    private String data_501411;
    @SerializedName("501412")
    private String data_501412;
    @SerializedName("501413")
    private String data_501413;
    @SerializedName("501414")
    private String data_501414;
    @SerializedName("501415")
    private String data_501415;
    @SerializedName("501416")
    private String data_501416;
    @SerializedName("501501")
    private String data_501501;
    @SerializedName("501502")
    private String data_501502;
    @SerializedName("501503")
    private String data_501503;
    @SerializedName("501504")
    private String data_501504;
    @SerializedName("501505")
    private String data_501505;
    @SerializedName("501506")
    private String data_501506;
    @SerializedName("501507")
    private String data_501507;
    @SerializedName("501508")
    private String data_501508;
    @SerializedName("501509")
    private String data_501509;
    @SerializedName("501510")
    private String data_501510;
    @SerializedName("501511")
    private String data_501511;
    @SerializedName("501512")
    private String data_501512;
    @SerializedName("501513")
    private String data_501513;
    @SerializedName("501514")
    private String data_501514;
    @SerializedName("501515")
    private String data_501515;
    @SerializedName("501516")
    private String data_501516;
    @SerializedName("501601")
    private String data_501601;
    @SerializedName("501602")
    private String data_501602;
    @SerializedName("501603")
    private String data_501603;
    @SerializedName("501604")
    private String data_501604;
    @SerializedName("501607")
    private String data_501607;
    @SerializedName("501608")
    private String data_501608;
    @SerializedName("501609")
    private String data_501609;
    @SerializedName("501610")
    private String data_501610;
    @SerializedName("501611")
    private String data_501611;
    @SerializedName("501612")
    private String data_501612;
    @SerializedName("501613")
    private String data_501613;
    @SerializedName("501614")
    private String data_501614;
    @SerializedName("501615")
    private String data_501615;
    @SerializedName("501616")
    private String data_501616;
    @SerializedName("501701")
    private String data_501701;
    @SerializedName("501702")
    private String data_501702;
    @SerializedName("501703")
    private String data_501703;
    @SerializedName("501704")
    private String data_501704;
    @SerializedName("501707")
    private String data_501707;
    @SerializedName("501708")
    private String data_501708;
    @SerializedName("501709")
    private String data_501709;
    @SerializedName("501710")
    private String data_501710;
    @SerializedName("501711")
    private String data_501711;
    @SerializedName("501712")
    private String data_501712;
    @SerializedName("501713")
    private String data_501713;
    @SerializedName("501714")
    private String data_501714;
    @SerializedName("501715")
    private String data_501715;
    @SerializedName("501716")
    private String data_501716;
    @SerializedName("501801")
    private String data_501801;
    @SerializedName("501802")
    private String data_501802;
    @SerializedName("501803")
    private String data_501803;
    @SerializedName("501804")
    private String data_501804;
    @SerializedName("501807")
    private String data_501807;
    @SerializedName("501808")
    private String data_501808;
    @SerializedName("501809")
    private String data_501809;
    @SerializedName("501810")
    private String data_501810;
    @SerializedName("501811")
    private String data_501811;
    @SerializedName("501812")
    private String data_501812;
    @SerializedName("501813")
    private String data_501813;
    @SerializedName("501814")
    private String data_501814;
    @SerializedName("501815")
    private String data_501815;
    @SerializedName("501816")
    private String data_501816;
    @SerializedName("501901")
    private String data_501901;
    @SerializedName("501902")
    private String data_501902;
    @SerializedName("501903")
    private String data_501903;
    @SerializedName("501904")
    private String data_501904;
    @SerializedName("501907")
    private String data_501907;
    @SerializedName("501908")
    private String data_501908;
    @SerializedName("501909")
    private String data_501909;
    @SerializedName("501910")
    private String data_501910;
    @SerializedName("501911")
    private String data_501911;
    @SerializedName("501912")
    private String data_501912;
    @SerializedName("501913")
    private String data_501913;
    @SerializedName("501914")
    private String data_501914;
    @SerializedName("501915")
    private String data_501915;
    @SerializedName("501916")
    private String data_501916;
    @SerializedName("502001")
    private String data_502001;
    @SerializedName("502002")
    private String data_502002;
    @SerializedName("502003")
    private String data_502003;
    @SerializedName("502004")
    private String data_502004;
    @SerializedName("502007")
    private String data_502007;
    @SerializedName("502008")
    private String data_502008;
    @SerializedName("502009")
    private String data_502009;
    @SerializedName("502010")
    private String data_502010;
    @SerializedName("502011")
    private String data_502011;
    @SerializedName("502012")
    private String data_502012;
    @SerializedName("502013")
    private String data_502013;
    @SerializedName("502014")
    private String data_502014;
    @SerializedName("502015")
    private String data_502015;
    @SerializedName("502016")
    private String data_502016;

    public String getdata_501001() {
        return data_501001;
    }

    public void setdata_501001(String data_501001) {
        this.data_501001 = data_501001;
    }

    public String getdata_501002() {
        return data_501002;
    }

    public void setdata_501002(String data_501002) {
        this.data_501002 = data_501002;
    }

    public String getdata_501003() {
        return data_501003;
    }

    public void setdata_501003(String data_501003) {
        this.data_501003 = data_501003;
    }

    public String getdata_501004() {
        return data_501004;
    }

    public void setdata_501004(String data_501004) {
        this.data_501004 = data_501004;
    }

    public String getdata_501005() {
        return data_501005;
    }

    public void setdata_501005(String data_501005) {
        this.data_501005 = data_501005;
    }

    public String getdata_501006() {
        return data_501006;
    }

    public void setdata_501006(String data_501006) {
        this.data_501006 = data_501006;
    }

    public String getdata_501007() {
        return data_501007;
    }

    public void setdata_501007(String data_501007) {
        this.data_501007 = data_501007;
    }

    public String getdata_501008() {
        return data_501008;
    }

    public void setdata_501008(String data_501008) {
        this.data_501008 = data_501008;
    }

    public String getdata_501009() {
        return data_501009;
    }

    public void setdata_501009(String data_501009) {
        this.data_501009 = data_501009;
    }

    public String getdata_501010() {
        return data_501010;
    }

    public void setdata_501010(String data_501010) {
        this.data_501010 = data_501010;
    }

    public String getdata_501011() {
        return data_501011;
    }

    public void setdata_501011(String data_501011) {
        this.data_501011 = data_501011;
    }

    public String getdata_501012() {
        return data_501012;
    }

    public void setdata_501012(String data_501012) {
        this.data_501012 = data_501012;
    }

    public String getdata_501013() {
        return data_501013;
    }

    public void setdata_501013(String data_501013) {
        this.data_501013 = data_501013;
    }

    public String getdata_501014() {
        return data_501014;
    }

    public void setdata_501014(String data_501014) {
        this.data_501014 = data_501014;
    }

    public String getdata_501015() {
        return data_501015;
    }

    public void setdata_501015(String data_501015) {
        this.data_501015 = data_501015;
    }

    public String getdata_501016() {
        return data_501016;
    }

    public void setdata_501016(String data_501016) {
        this.data_501016 = data_501016;
    }

    public String getdata_501017() {
        return data_501017;
    }

    public void setdata_501017(String data_501017) {
        this.data_501017 = data_501017;
    }

    public String getdata_501018() {
        return data_501018;
    }

    public void setdata_501018(String data_501018) {
        this.data_501018 = data_501018;
    }

    public String getdata_501019() {
        return data_501019;
    }

    public void setdata_501019(String data_501019) {
        this.data_501019 = data_501019;
    }

    public String getdata_501020() {
        return data_501020;
    }

    public void setdata_501020(String data_501020) {
        this.data_501020 = data_501020;
    }

    public String getdata_501021() {
        return data_501021;
    }

    public void setdata_501021(String data_501021) {
        this.data_501021 = data_501021;
    }

    public String getdata_501101() {
        return data_501101;
    }

    public void setdata_501101(String data_501101) {
        this.data_501101 = data_501101;
    }

    public String getdata_501102() {
        return data_501102;
    }

    public void setdata_501102(String data_501102) {
        this.data_501102 = data_501102;
    }

    public String getdata_501103() {
        return data_501103;
    }

    public void setdata_501103(String data_501103) {
        this.data_501103 = data_501103;
    }

    public String getdata_501104() {
        return data_501104;
    }

    public void setdata_501104(String data_501104) {
        this.data_501104 = data_501104;
    }

    public String getdata_501105() {
        return data_501105;
    }

    public void setdata_501105(String data_501105) {
        this.data_501105 = data_501105;
    }

    public String getdata_501106() {
        return data_501106;
    }

    public void setdata_501106(String data_501106) {
        this.data_501106 = data_501106;
    }

    public String getdata_501107() {
        return data_501107;
    }

    public void setdata_501107(String data_501107) {
        this.data_501107 = data_501107;
    }

    public String getdata_501108() {
        return data_501108;
    }

    public void setdata_501108(String data_501108) {
        this.data_501108 = data_501108;
    }

    public String getdata_501109() {
        return data_501109;
    }

    public void setdata_501109(String data_501109) {
        this.data_501109 = data_501109;
    }

    public String getdata_501110() {
        return data_501110;
    }

    public void setdata_501110(String data_501110) {
        this.data_501110 = data_501110;
    }

    public String getdata_501111() {
        return data_501111;
    }

    public void setdata_501111(String data_501111) {
        this.data_501111 = data_501111;
    }

    public String getdata_501112() {
        return data_501112;
    }

    public void setdata_501112(String data_501112) {
        this.data_501112 = data_501112;
    }

    public String getdata_501113() {
        return data_501113;
    }

    public void setdata_501113(String data_501113) {
        this.data_501113 = data_501113;
    }

    public String getdata_501114() {
        return data_501114;
    }

    public void setdata_501114(String data_501114) {
        this.data_501114 = data_501114;
    }

    public String getdata_501115() {
        return data_501115;
    }

    public void setdata_501115(String data_501115) {
        this.data_501115 = data_501115;
    }

    public String getdata_501116() {
        return data_501116;
    }

    public void setdata_501116(String data_501116) {
        this.data_501116 = data_501116;
    }

    public String getdata_501201() {
        return data_501201;
    }

    public void setdata_501201(String data_501201) {
        this.data_501201 = data_501201;
    }

    public String getdata_501202() {
        return data_501202;
    }

    public void setdata_501202(String data_501202) {
        this.data_501202 = data_501202;
    }

    public String getdata_501203() {
        return data_501203;
    }

    public void setdata_501203(String data_501203) {
        this.data_501203 = data_501203;
    }

    public String getdata_501204() {
        return data_501204;
    }

    public void setdata_501204(String data_501204) {
        this.data_501204 = data_501204;
    }

    public String getdata_501205() {
        return data_501205;
    }

    public void setdata_501205(String data_501205) {
        this.data_501205 = data_501205;
    }

    public String getdata_501206() {
        return data_501206;
    }

    public void setdata_501206(String data_501206) {
        this.data_501206 = data_501206;
    }

    public String getdata_501207() {
        return data_501207;
    }

    public void setdata_501207(String data_501207) {
        this.data_501207 = data_501207;
    }

    public String getdata_501208() {
        return data_501208;
    }

    public void setdata_501208(String data_501208) {
        this.data_501208 = data_501208;
    }

    public String getdata_501209() {
        return data_501209;
    }

    public void setdata_501209(String data_501209) {
        this.data_501209 = data_501209;
    }

    public String getdata_501210() {
        return data_501210;
    }

    public void setdata_501210(String data_501210) {
        this.data_501210 = data_501210;
    }

    public String getdata_501211() {
        return data_501211;
    }

    public void setdata_501211(String data_501211) {
        this.data_501211 = data_501211;
    }

    public String getdata_501212() {
        return data_501212;
    }

    public void setdata_501212(String data_501212) {
        this.data_501212 = data_501212;
    }

    public String getdata_501213() {
        return data_501213;
    }

    public void setdata_501213(String data_501213) {
        this.data_501213 = data_501213;
    }

    public String getdata_501214() {
        return data_501214;
    }

    public void setdata_501214(String data_501214) {
        this.data_501214 = data_501214;
    }

    public String getdata_501215() {
        return data_501215;
    }

    public void setdata_501215(String data_501215) {
        this.data_501215 = data_501215;
    }

    public String getdata_501216() {
        return data_501216;
    }

    public void setdata_501216(String data_501216) {
        this.data_501216 = data_501216;
    }

    public String getdata_501301() {
        return data_501301;
    }

    public void setdata_501301(String data_501301) {
        this.data_501301 = data_501301;
    }

    public String getdata_501302() {
        return data_501302;
    }

    public void setdata_501302(String data_501302) {
        this.data_501302 = data_501302;
    }

    public String getdata_501303() {
        return data_501303;
    }

    public void setdata_501303(String data_501303) {
        this.data_501303 = data_501303;
    }

    public String getdata_501304() {
        return data_501304;
    }

    public void setdata_501304(String data_501304) {
        this.data_501304 = data_501304;
    }

    public String getdata_501305() {
        return data_501305;
    }

    public void setdata_501305(String data_501305) {
        this.data_501305 = data_501305;
    }

    public String getdata_501306() {
        return data_501306;
    }

    public void setdata_501306(String data_501306) {
        this.data_501306 = data_501306;
    }

    public String getdata_501307() {
        return data_501307;
    }

    public void setdata_501307(String data_501307) {
        this.data_501307 = data_501307;
    }

    public String getdata_501308() {
        return data_501308;
    }

    public void setdata_501308(String data_501308) {
        this.data_501308 = data_501308;
    }

    public String getdata_501309() {
        return data_501309;
    }

    public void setdata_501309(String data_501309) {
        this.data_501309 = data_501309;
    }

    public String getdata_501310() {
        return data_501310;
    }

    public void setdata_501310(String data_501310) {
        this.data_501310 = data_501310;
    }

    public String getdata_501311() {
        return data_501311;
    }

    public void setdata_501311(String data_501311) {
        this.data_501311 = data_501311;
    }

    public String getdata_501312() {
        return data_501312;
    }

    public void setdata_501312(String data_501312) {
        this.data_501312 = data_501312;
    }

    public String getdata_501313() {
        return data_501313;
    }

    public void setdata_501313(String data_501313) {
        this.data_501313 = data_501313;
    }

    public String getdata_501314() {
        return data_501314;
    }

    public void setdata_501314(String data_501314) {
        this.data_501314 = data_501314;
    }

    public String getdata_501315() {
        return data_501315;
    }

    public void setdata_501315(String data_501315) {
        this.data_501315 = data_501315;
    }

    public String getdata_501316() {
        return data_501316;
    }

    public void setdata_501316(String data_501316) {
        this.data_501316 = data_501316;
    }

    public String getdata_501401() {
        return data_501401;
    }

    public void setdata_501401(String data_501401) {
        this.data_501401 = data_501401;
    }

    public String getdata_501402() {
        return data_501402;
    }

    public void setdata_501402(String data_501402) {
        this.data_501402 = data_501402;
    }

    public String getdata_501403() {
        return data_501403;
    }

    public void setdata_501403(String data_501403) {
        this.data_501403 = data_501403;
    }

    public String getdata_501404() {
        return data_501404;
    }

    public void setdata_501404(String data_501404) {
        this.data_501404 = data_501404;
    }

    public String getdata_501405() {
        return data_501405;
    }

    public void setdata_501405(String data_501405) {
        this.data_501405 = data_501405;
    }

    public String getdata_501406() {
        return data_501406;
    }

    public void setdata_501406(String data_501406) {
        this.data_501406 = data_501406;
    }

    public String getdata_501407() {
        return data_501407;
    }

    public void setdata_501407(String data_501407) {
        this.data_501407 = data_501407;
    }

    public String getdata_501408() {
        return data_501408;
    }

    public void setdata_501408(String data_501408) {
        this.data_501408 = data_501408;
    }

    public String getdata_501409() {
        return data_501409;
    }

    public void setdata_501409(String data_501409) {
        this.data_501409 = data_501409;
    }

    public String getdata_501410() {
        return data_501410;
    }

    public void setdata_501410(String data_501410) {
        this.data_501410 = data_501410;
    }

    public String getdata_501411() {
        return data_501411;
    }

    public void setdata_501411(String data_501411) {
        this.data_501411 = data_501411;
    }

    public String getdata_501412() {
        return data_501412;
    }

    public void setdata_501412(String data_501412) {
        this.data_501412 = data_501412;
    }

    public String getdata_501413() {
        return data_501413;
    }

    public void setdata_501413(String data_501413) {
        this.data_501413 = data_501413;
    }

    public String getdata_501414() {
        return data_501414;
    }

    public void setdata_501414(String data_501414) {
        this.data_501414 = data_501414;
    }

    public String getdata_501415() {
        return data_501415;
    }

    public void setdata_501415(String data_501415) {
        this.data_501415 = data_501415;
    }

    public String getdata_501416() {
        return data_501416;
    }

    public void setdata_501416(String data_501416) {
        this.data_501416 = data_501416;
    }

    public String getdata_501501() {
        return data_501501;
    }

    public void setdata_501501(String data_501501) {
        this.data_501501 = data_501501;
    }

    public String getdata_501502() {
        return data_501502;
    }

    public void setdata_501502(String data_501502) {
        this.data_501502 = data_501502;
    }

    public String getdata_501503() {
        return data_501503;
    }

    public void setdata_501503(String data_501503) {
        this.data_501503 = data_501503;
    }

    public String getdata_501504() {
        return data_501504;
    }

    public void setdata_501504(String data_501504) {
        this.data_501504 = data_501504;
    }

    public String getdata_501505() {
        return data_501505;
    }

    public void setdata_501505(String data_501505) {
        this.data_501505 = data_501505;
    }

    public String getdata_501506() {
        return data_501506;
    }

    public void setdata_501506(String data_501506) {
        this.data_501506 = data_501506;
    }

    public String getdata_501507() {
        return data_501507;
    }

    public void setdata_501507(String data_501507) {
        this.data_501507 = data_501507;
    }

    public String getdata_501508() {
        return data_501508;
    }

    public void setdata_501508(String data_501508) {
        this.data_501508 = data_501508;
    }

    public String getdata_501509() {
        return data_501509;
    }

    public void setdata_501509(String data_501509) {
        this.data_501509 = data_501509;
    }

    public String getdata_501510() {
        return data_501510;
    }

    public void setdata_501510(String data_501510) {
        this.data_501510 = data_501510;
    }

    public String getdata_501511() {
        return data_501511;
    }

    public void setdata_501511(String data_501511) {
        this.data_501511 = data_501511;
    }

    public String getdata_501512() {
        return data_501512;
    }

    public void setdata_501512(String data_501512) {
        this.data_501512 = data_501512;
    }

    public String getdata_501513() {
        return data_501513;
    }

    public void setdata_501513(String data_501513) {
        this.data_501513 = data_501513;
    }

    public String getdata_501514() {
        return data_501514;
    }

    public void setdata_501514(String data_501514) {
        this.data_501514 = data_501514;
    }

    public String getdata_501515() {
        return data_501515;
    }

    public void setdata_501515(String data_501515) {
        this.data_501515 = data_501515;
    }

    public String getdata_501516() {
        return data_501516;
    }

    public void setdata_501516(String data_501516) {
        this.data_501516 = data_501516;
    }

    public String getdata_501601() {
        return data_501601;
    }

    public void setdata_501601(String data_501601) {
        this.data_501601 = data_501601;
    }

    public String getdata_501602() {
        return data_501602;
    }

    public void setdata_501602(String data_501602) {
        this.data_501602 = data_501602;
    }

    public String getdata_501603() {
        return data_501603;
    }

    public void setdata_501603(String data_501603) {
        this.data_501603 = data_501603;
    }

    public String getdata_501604() {
        return data_501604;
    }

    public void setdata_501604(String data_501604) {
        this.data_501604 = data_501604;
    }

    public String getdata_501607() {
        return data_501607;
    }

    public void setdata_501607(String data_501607) {
        this.data_501607 = data_501607;
    }

    public String getdata_501608() {
        return data_501608;
    }

    public void setdata_501608(String data_501608) {
        this.data_501608 = data_501608;
    }

    public String getdata_501609() {
        return data_501609;
    }

    public void setdata_501609(String data_501609) {
        this.data_501609 = data_501609;
    }

    public String getdata_501610() {
        return data_501610;
    }

    public void setdata_501610(String data_501610) {
        this.data_501610 = data_501610;
    }

    public String getdata_501611() {
        return data_501611;
    }

    public void setdata_501611(String data_501611) {
        this.data_501611 = data_501611;
    }

    public String getdata_501612() {
        return data_501612;
    }

    public void setdata_501612(String data_501612) {
        this.data_501612 = data_501612;
    }

    public String getdata_501613() {
        return data_501613;
    }

    public void setdata_501613(String data_501613) {
        this.data_501613 = data_501613;
    }

    public String getdata_501614() {
        return data_501614;
    }

    public void setdata_501614(String data_501614) {
        this.data_501614 = data_501614;
    }

    public String getdata_501615() {
        return data_501615;
    }

    public void setdata_501615(String data_501615) {
        this.data_501615 = data_501615;
    }

    public String getdata_501616() {
        return data_501616;
    }

    public void setdata_501616(String data_501616) {
        this.data_501616 = data_501616;
    }

    public String getdata_501701() {
        return data_501701;
    }

    public void setdata_501701(String data_501701) {
        this.data_501701 = data_501701;
    }

    public String getdata_501702() {
        return data_501702;
    }

    public void setdata_501702(String data_501702) {
        this.data_501702 = data_501702;
    }

    public String getdata_501703() {
        return data_501703;
    }

    public void setdata_501703(String data_501703) {
        this.data_501703 = data_501703;
    }

    public String getdata_501704() {
        return data_501704;
    }

    public void setdata_501704(String data_501704) {
        this.data_501704 = data_501704;
    }

    public String getdata_501707() {
        return data_501707;
    }

    public void setdata_501707(String data_501707) {
        this.data_501707 = data_501707;
    }

    public String getdata_501708() {
        return data_501708;
    }

    public void setdata_501708(String data_501708) {
        this.data_501708 = data_501708;
    }

    public String getdata_501709() {
        return data_501709;
    }

    public void setdata_501709(String data_501709) {
        this.data_501709 = data_501709;
    }

    public String getdata_501710() {
        return data_501710;
    }

    public void setdata_501710(String data_501710) {
        this.data_501710 = data_501710;
    }

    public String getdata_501711() {
        return data_501711;
    }

    public void setdata_501711(String data_501711) {
        this.data_501711 = data_501711;
    }

    public String getdata_501712() {
        return data_501712;
    }

    public void setdata_501712(String data_501712) {
        this.data_501712 = data_501712;
    }

    public String getdata_501713() {
        return data_501713;
    }

    public void setdata_501713(String data_501713) {
        this.data_501713 = data_501713;
    }

    public String getdata_501714() {
        return data_501714;
    }

    public void setdata_501714(String data_501714) {
        this.data_501714 = data_501714;
    }

    public String getdata_501715() {
        return data_501715;
    }

    public void setdata_501715(String data_501715) {
        this.data_501715 = data_501715;
    }

    public String getdata_501716() {
        return data_501716;
    }

    public void setdata_501716(String data_501716) {
        this.data_501716 = data_501716;
    }

    public String getdata_501801() {
        return data_501801;
    }

    public void setdata_501801(String data_501801) {
        this.data_501801 = data_501801;
    }

    public String getdata_501802() {
        return data_501802;
    }

    public void setdata_501802(String data_501802) {
        this.data_501802 = data_501802;
    }

    public String getdata_501803() {
        return data_501803;
    }

    public void setdata_501803(String data_501803) {
        this.data_501803 = data_501803;
    }

    public String getdata_501804() {
        return data_501804;
    }

    public void setdata_501804(String data_501804) {
        this.data_501804 = data_501804;
    }

    public String getdata_501807() {
        return data_501807;
    }

    public void setdata_501807(String data_501807) {
        this.data_501807 = data_501807;
    }

    public String getdata_501808() {
        return data_501808;
    }

    public void setdata_501808(String data_501808) {
        this.data_501808 = data_501808;
    }

    public String getdata_501809() {
        return data_501809;
    }

    public void setdata_501809(String data_501809) {
        this.data_501809 = data_501809;
    }

    public String getdata_501810() {
        return data_501810;
    }

    public void setdata_501810(String data_501810) {
        this.data_501810 = data_501810;
    }

    public String getdata_501811() {
        return data_501811;
    }

    public void setdata_501811(String data_501811) {
        this.data_501811 = data_501811;
    }

    public String getdata_501812() {
        return data_501812;
    }

    public void setdata_501812(String data_501812) {
        this.data_501812 = data_501812;
    }

    public String getdata_501813() {
        return data_501813;
    }

    public void setdata_501813(String data_501813) {
        this.data_501813 = data_501813;
    }

    public String getdata_501814() {
        return data_501814;
    }

    public void setdata_501814(String data_501814) {
        this.data_501814 = data_501814;
    }

    public String getdata_501815() {
        return data_501815;
    }

    public void setdata_501815(String data_501815) {
        this.data_501815 = data_501815;
    }

    public String getdata_501816() {
        return data_501816;
    }

    public void setdata_501816(String data_501816) {
        this.data_501816 = data_501816;
    }

    public String getdata_501901() {
        return data_501901;
    }

    public void setdata_501901(String data_501901) {
        this.data_501901 = data_501901;
    }

    public String getdata_501902() {
        return data_501902;
    }

    public void setdata_501902(String data_501902) {
        this.data_501902 = data_501902;
    }

    public String getdata_501903() {
        return data_501903;
    }

    public void setdata_501903(String data_501903) {
        this.data_501903 = data_501903;
    }

    public String getdata_501904() {
        return data_501904;
    }

    public void setdata_501904(String data_501904) {
        this.data_501904 = data_501904;
    }

    public String getdata_501907() {
        return data_501907;
    }

    public void setdata_501907(String data_501907) {
        this.data_501907 = data_501907;
    }

    public String getdata_501908() {
        return data_501908;
    }

    public void setdata_501908(String data_501908) {
        this.data_501908 = data_501908;
    }

    public String getdata_501909() {
        return data_501909;
    }

    public void setdata_501909(String data_501909) {
        this.data_501909 = data_501909;
    }

    public String getdata_501910() {
        return data_501910;
    }

    public void setdata_501910(String data_501910) {
        this.data_501910 = data_501910;
    }

    public String getdata_501911() {
        return data_501911;
    }

    public void setdata_501911(String data_501911) {
        this.data_501911 = data_501911;
    }

    public String getdata_501912() {
        return data_501912;
    }

    public void setdata_501912(String data_501912) {
        this.data_501912 = data_501912;
    }

    public String getdata_501913() {
        return data_501913;
    }

    public void setdata_501913(String data_501913) {
        this.data_501913 = data_501913;
    }

    public String getdata_501914() {
        return data_501914;
    }

    public void setdata_501914(String data_501914) {
        this.data_501914 = data_501914;
    }

    public String getdata_501915() {
        return data_501915;
    }

    public void setdata_501915(String data_501915) {
        this.data_501915 = data_501915;
    }

    public String getdata_501916() {
        return data_501916;
    }

    public void setdata_501916(String data_501916) {
        this.data_501916 = data_501916;
    }

    public String getdata_502001() {
        return data_502001;
    }

    public void setdata_502001(String data_502001) {
        this.data_502001 = data_502001;
    }

    public String getdata_502002() {
        return data_502002;
    }

    public void setdata_502002(String data_502002) {
        this.data_502002 = data_502002;
    }

    public String getdata_502003() {
        return data_502003;
    }

    public void setdata_502003(String data_502003) {
        this.data_502003 = data_502003;
    }

    public String getdata_502004() {
        return data_502004;
    }

    public void setdata_502004(String data_502004) {
        this.data_502004 = data_502004;
    }

    public String getdata_502007() {
        return data_502007;
    }

    public void setdata_502007(String data_502007) {
        this.data_502007 = data_502007;
    }

    public String getdata_502008() {
        return data_502008;
    }

    public void setdata_502008(String data_502008) {
        this.data_502008 = data_502008;
    }

    public String getdata_502009() {
        return data_502009;
    }

    public void setdata_502009(String data_502009) {
        this.data_502009 = data_502009;
    }

    public String getdata_502010() {
        return data_502010;
    }

    public void setdata_502010(String data_502010) {
        this.data_502010 = data_502010;
    }

    public String getdata_502011() {
        return data_502011;
    }

    public void setdata_502011(String data_502011) {
        this.data_502011 = data_502011;
    }

    public String getdata_502012() {
        return data_502012;
    }

    public void setdata_502012(String data_502012) {
        this.data_502012 = data_502012;
    }

    public String getdata_502013() {
        return data_502013;
    }

    public void setdata_502013(String data_502013) {
        this.data_502013 = data_502013;
    }

    public String getdata_502014() {
        return data_502014;
    }

    public void setdata_502014(String data_502014) {
        this.data_502014 = data_502014;
    }

    public String getdata_502015() {
        return data_502015;
    }

    public void setdata_502015(String data_502015) {
        this.data_502015 = data_502015;
    }

    public String getdata_502016() {
        return data_502016;
    }

    public void setdata_502016(String data_502016) {
        this.data_502016 = data_502016;
    }

    @Override
    public String toString() {
        return "CPBJSCResult{" +
                "data_501001='" + data_501001 + '\'' +
                ", data_501002='" + data_501002 + '\'' +
                ", data_501003='" + data_501003 + '\'' +
                ", data_501004='" + data_501004 + '\'' +
                ", data_501005='" + data_501005 + '\'' +
                ", data_501006='" + data_501006 + '\'' +
                ", data_501007='" + data_501007 + '\'' +
                ", data_501008='" + data_501008 + '\'' +
                ", data_501009='" + data_501009 + '\'' +
                ", data_501010='" + data_501010 + '\'' +
                ", data_501011='" + data_501011 + '\'' +
                ", data_501012='" + data_501012 + '\'' +
                ", data_501013='" + data_501013 + '\'' +
                ", data_501014='" + data_501014 + '\'' +
                ", data_501015='" + data_501015 + '\'' +
                ", data_501016='" + data_501016 + '\'' +
                ", data_501017='" + data_501017 + '\'' +
                ", data_501018='" + data_501018 + '\'' +
                ", data_501019='" + data_501019 + '\'' +
                ", data_501020='" + data_501020 + '\'' +
                ", data_501021='" + data_501021 + '\'' +
                ", data_501101='" + data_501101 + '\'' +
                ", data_501102='" + data_501102 + '\'' +
                ", data_501103='" + data_501103 + '\'' +
                ", data_501104='" + data_501104 + '\'' +
                ", data_501105='" + data_501105 + '\'' +
                ", data_501106='" + data_501106 + '\'' +
                ", data_501107='" + data_501107 + '\'' +
                ", data_501108='" + data_501108 + '\'' +
                ", data_501109='" + data_501109 + '\'' +
                ", data_501110='" + data_501110 + '\'' +
                ", data_501111='" + data_501111 + '\'' +
                ", data_501112='" + data_501112 + '\'' +
                ", data_501113='" + data_501113 + '\'' +
                ", data_501114='" + data_501114 + '\'' +
                ", data_501115='" + data_501115 + '\'' +
                ", data_501116='" + data_501116 + '\'' +
                ", data_501201='" + data_501201 + '\'' +
                ", data_501202='" + data_501202 + '\'' +
                ", data_501203='" + data_501203 + '\'' +
                ", data_501204='" + data_501204 + '\'' +
                ", data_501205='" + data_501205 + '\'' +
                ", data_501206='" + data_501206 + '\'' +
                ", data_501207='" + data_501207 + '\'' +
                ", data_501208='" + data_501208 + '\'' +
                ", data_501209='" + data_501209 + '\'' +
                ", data_501210='" + data_501210 + '\'' +
                ", data_501211='" + data_501211 + '\'' +
                ", data_501212='" + data_501212 + '\'' +
                ", data_501213='" + data_501213 + '\'' +
                ", data_501214='" + data_501214 + '\'' +
                ", data_501215='" + data_501215 + '\'' +
                ", data_501216='" + data_501216 + '\'' +
                ", data_501301='" + data_501301 + '\'' +
                ", data_501302='" + data_501302 + '\'' +
                ", data_501303='" + data_501303 + '\'' +
                ", data_501304='" + data_501304 + '\'' +
                ", data_501305='" + data_501305 + '\'' +
                ", data_501306='" + data_501306 + '\'' +
                ", data_501307='" + data_501307 + '\'' +
                ", data_501308='" + data_501308 + '\'' +
                ", data_501309='" + data_501309 + '\'' +
                ", data_501310='" + data_501310 + '\'' +
                ", data_501311='" + data_501311 + '\'' +
                ", data_501312='" + data_501312 + '\'' +
                ", data_501313='" + data_501313 + '\'' +
                ", data_501314='" + data_501314 + '\'' +
                ", data_501315='" + data_501315 + '\'' +
                ", data_501316='" + data_501316 + '\'' +
                ", data_501401='" + data_501401 + '\'' +
                ", data_501402='" + data_501402 + '\'' +
                ", data_501403='" + data_501403 + '\'' +
                ", data_501404='" + data_501404 + '\'' +
                ", data_501405='" + data_501405 + '\'' +
                ", data_501406='" + data_501406 + '\'' +
                ", data_501407='" + data_501407 + '\'' +
                ", data_501408='" + data_501408 + '\'' +
                ", data_501409='" + data_501409 + '\'' +
                ", data_501410='" + data_501410 + '\'' +
                ", data_501411='" + data_501411 + '\'' +
                ", data_501412='" + data_501412 + '\'' +
                ", data_501413='" + data_501413 + '\'' +
                ", data_501414='" + data_501414 + '\'' +
                ", data_501415='" + data_501415 + '\'' +
                ", data_501416='" + data_501416 + '\'' +
                ", data_501501='" + data_501501 + '\'' +
                ", data_501502='" + data_501502 + '\'' +
                ", data_501503='" + data_501503 + '\'' +
                ", data_501504='" + data_501504 + '\'' +
                ", data_501505='" + data_501505 + '\'' +
                ", data_501506='" + data_501506 + '\'' +
                ", data_501507='" + data_501507 + '\'' +
                ", data_501508='" + data_501508 + '\'' +
                ", data_501509='" + data_501509 + '\'' +
                ", data_501510='" + data_501510 + '\'' +
                ", data_501511='" + data_501511 + '\'' +
                ", data_501512='" + data_501512 + '\'' +
                ", data_501513='" + data_501513 + '\'' +
                ", data_501514='" + data_501514 + '\'' +
                ", data_501515='" + data_501515 + '\'' +
                ", data_501516='" + data_501516 + '\'' +
                ", data_501601='" + data_501601 + '\'' +
                ", data_501602='" + data_501602 + '\'' +
                ", data_501603='" + data_501603 + '\'' +
                ", data_501604='" + data_501604 + '\'' +
                ", data_501607='" + data_501607 + '\'' +
                ", data_501608='" + data_501608 + '\'' +
                ", data_501609='" + data_501609 + '\'' +
                ", data_501610='" + data_501610 + '\'' +
                ", data_501611='" + data_501611 + '\'' +
                ", data_501612='" + data_501612 + '\'' +
                ", data_501613='" + data_501613 + '\'' +
                ", data_501614='" + data_501614 + '\'' +
                ", data_501615='" + data_501615 + '\'' +
                ", data_501616='" + data_501616 + '\'' +
                ", data_501701='" + data_501701 + '\'' +
                ", data_501702='" + data_501702 + '\'' +
                ", data_501703='" + data_501703 + '\'' +
                ", data_501704='" + data_501704 + '\'' +
                ", data_501707='" + data_501707 + '\'' +
                ", data_501708='" + data_501708 + '\'' +
                ", data_501709='" + data_501709 + '\'' +
                ", data_501710='" + data_501710 + '\'' +
                ", data_501711='" + data_501711 + '\'' +
                ", data_501712='" + data_501712 + '\'' +
                ", data_501713='" + data_501713 + '\'' +
                ", data_501714='" + data_501714 + '\'' +
                ", data_501715='" + data_501715 + '\'' +
                ", data_501716='" + data_501716 + '\'' +
                ", data_501801='" + data_501801 + '\'' +
                ", data_501802='" + data_501802 + '\'' +
                ", data_501803='" + data_501803 + '\'' +
                ", data_501804='" + data_501804 + '\'' +
                ", data_501807='" + data_501807 + '\'' +
                ", data_501808='" + data_501808 + '\'' +
                ", data_501809='" + data_501809 + '\'' +
                ", data_501810='" + data_501810 + '\'' +
                ", data_501811='" + data_501811 + '\'' +
                ", data_501812='" + data_501812 + '\'' +
                ", data_501813='" + data_501813 + '\'' +
                ", data_501814='" + data_501814 + '\'' +
                ", data_501815='" + data_501815 + '\'' +
                ", data_501816='" + data_501816 + '\'' +
                ", data_501901='" + data_501901 + '\'' +
                ", data_501902='" + data_501902 + '\'' +
                ", data_501903='" + data_501903 + '\'' +
                ", data_501904='" + data_501904 + '\'' +
                ", data_501907='" + data_501907 + '\'' +
                ", data_501908='" + data_501908 + '\'' +
                ", data_501909='" + data_501909 + '\'' +
                ", data_501910='" + data_501910 + '\'' +
                ", data_501911='" + data_501911 + '\'' +
                ", data_501912='" + data_501912 + '\'' +
                ", data_501913='" + data_501913 + '\'' +
                ", data_501914='" + data_501914 + '\'' +
                ", data_501915='" + data_501915 + '\'' +
                ", data_501916='" + data_501916 + '\'' +
                ", data_502001='" + data_502001 + '\'' +
                ", data_502002='" + data_502002 + '\'' +
                ", data_502003='" + data_502003 + '\'' +
                ", data_502004='" + data_502004 + '\'' +
                ", data_502007='" + data_502007 + '\'' +
                ", data_502008='" + data_502008 + '\'' +
                ", data_502009='" + data_502009 + '\'' +
                ", data_502010='" + data_502010 + '\'' +
                ", data_502011='" + data_502011 + '\'' +
                ", data_502012='" + data_502012 + '\'' +
                ", data_502013='" + data_502013 + '\'' +
                ", data_502014='" + data_502014 + '\'' +
                ", data_502015='" + data_502015 + '\'' +
                ", data_502016='" + data_502016 + '\'' +
                '}';
    }
}
