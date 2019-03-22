package com.cfcp.a01.ui.home.betGenerate;

import com.cfcp.a01.data.LotteryResult;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

/**
 * 3D生成投注区域号码
 */
class ThreeDGenerateNum {

    //公用方法
    private static List<LotteryResult> common(String[] places) {
        String[] option = {"全", "大", "小", "奇", "偶", "清"};
        String[] lotteryNum = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            lotteryResult.setPlaces(aPlaces);
            lotteryResult.setOption(Arrays.asList(option));
            lotteryResult.setData(Arrays.asList(lotteryNum));
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //生成全部选择项
    static List<LotteryResult> allNum() {
        String[] places = {"百位", "十位", "个位"};
        return common(places);
    }

    //直选和值
    static List<LotteryResult> sumS() {
        String[] lotteryNum = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("直选和值");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //组三
    static List<LotteryResult> z3() {
        String[] places = {"组三"};
        return common(places);
    }

    //组六
    static List<LotteryResult> z6() {
        String[] places = {"组六"};
        return common(places);
    }

    //组选和值
    static List<LotteryResult> sumZS() {
        String[] lotteryNum = {"1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("组选和值");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //二星后二直选
    static List<LotteryResult> h2z() {
        String[] places = {"十位", "个位"};
        return common(places);
    }

    //二星前二直选
    static List<LotteryResult> q2z() {
        String[] places = {"百位", "十位"};
        return common(places);
    }

    //后二、前二组选
    static List<LotteryResult> hqz() {
        String[] places = {"组选"};
        return common(places);
    }

    //不定位
    static List<LotteryResult> bdw() {
        String[] places = {"不定位"};
        return common(places);
    }

    //龙虎和公用方法
    private static List<LotteryResult> lAndH(String places) {
        String[] lotteryNum = {"龙", "虎", "和"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces(places);
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //百十
    static List<LotteryResult> bs() {
        String places = "百：十";
        return lAndH(places);
    }

    //百个
    static List<LotteryResult> bg() {
        String places = "百：个";
        return lAndH(places);
    }

    //十个
    static List<LotteryResult> sg() {
        String places = "十：个";
        return lAndH(places);
    }
}
