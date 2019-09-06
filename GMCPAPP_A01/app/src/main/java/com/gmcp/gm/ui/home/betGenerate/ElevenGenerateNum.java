package com.gmcp.gm.ui.home.betGenerate;

import com.gmcp.gm.data.LotteryResult;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

/**
 * 十一选五生成号码投注区域
 */
class ElevenGenerateNum {

    //公用方法
    private static List<LotteryResult> common(String[] places) {
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum = {"1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11"};
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
        String[] places = {"一位", "二位", "三位", "四位", "五位"};
        return common(places);
    }

    //三码
    static List<LotteryResult> threeNum() {
        String[] places = {"一位", "二位", "三位"};
        return common(places);
    }

    //前三组选/不定位
    static List<LotteryResult> threeZNum() {
        String[] places = {"前三"};
        return common(places);
    }

    //前三、二组选胆拖/任选模式
    static List<LotteryResult> DNum() {
        String[] places = {"胆码", "拖码"};
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum = {"1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            lotteryResult.setPlaces(aPlaces);
            if (aPlaces.equals("拖码")){
                lotteryResult.setOption(Arrays.asList(option));
            }
            lotteryResult.setData(Arrays.asList(lotteryNum));
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //二码
    static List<LotteryResult> twoNum() {
        String[] places = {"一位", "二位"};
        return common(places);
    }

    //前二组选
    static List<LotteryResult> twoZNum() {
        String[] places = {"前二"};
        return common(places);
    }

    //趣味型定单双
    static List<LotteryResult> spice() {
        String[] lotteryNum = {"5单0双", "4单1双", "3单2双", "2单3双", "1单4双", "0单5双"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("定单双");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //趣味型猜中位
    static List<LotteryResult> midPosition() {
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum = {"3", "4", "5", "6", "7", "8", "9"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("猜中位");
        lotteryResult.setOption(Arrays.asList(option));
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //任选复式1中1
    static List<LotteryResult> rx11() {
        String[] places = {"选1中1"};
        return common(places);
    }

    //任选复式2中2
    static List<LotteryResult> rx22() {
        String[] places = {"选2中2"};
        return common(places);
    }

    //任选复式3中3
    static List<LotteryResult> rx33() {
        String[] places = {"选3中3"};
        return common(places);
    }

    //任选复式4中4
    static List<LotteryResult> rx44() {
        String[] places = {"选4中4"};
        return common(places);
    }

    //任选复式5中5
    static List<LotteryResult> rx55() {
        String[] places = {"选5中5"};
        return common(places);
    }

    //任选复式6中5
    static List<LotteryResult> rx65() {
        String[] places = {"选6中5"};
        return common(places);
    }

    //任选复式7中5
    static List<LotteryResult> rx75() {
        String[] places = {"选7中5"};
        return common(places);
    }

    //任选复式8中5
    static List<LotteryResult> rx85() {
        String[] places = {"选8中5"};
        return common(places);
    }

}
