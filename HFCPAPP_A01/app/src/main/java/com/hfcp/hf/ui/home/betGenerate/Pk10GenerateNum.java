package com.hfcp.hf.ui.home.betGenerate;

import com.hfcp.hf.data.LotteryResult;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

/**
 * PK10生成号码投注区域
 */
class Pk10GenerateNum {

    //公用方法
    private static List<LotteryResult> common(String[] places) {
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum = {"1", "2", "3", "4", "5", "6", "7", "8", "9", "10"};
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
        String[] places = {"冠军", "亚军", "季军", "第四名", "第五名", "第六名", "第七名", "第八名", "第九名", "第十名"};
        return common(places);
    }

    //冠亚和值
    static List<LotteryResult> sumGY() {
        String[] lotteryNum = {"3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("冠亚和值");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //大小单双
    static List<LotteryResult> sumBS() {
        String[] lotteryNum = {"大", "小", "单", "双"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("大小单双");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //龙虎
    static List<LotteryResult> lh() {
        String[] places = {"1V10", "2V9", "3V8", "4V7", "5V6"};
        String[] lotteryNum = {"龙", "虎"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            lotteryResult.setPlaces(aPlaces);
            lotteryResult.setData(Arrays.asList(lotteryNum));
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //二星复式冠亚
    static List<LotteryResult> gy() {
        String[] places = {"冠军", "亚军"};
        return common(places);
    }

    //二星复式后二
    static List<LotteryResult> h2() {
        String[] places = {"第九名", "第十名"};
        return common(places);
    }

    //二星复式冠亚季
    static List<LotteryResult> gyj() {
        String[] places = {"冠军", "亚军", "季军"};
        return common(places);
    }

    //二星复式后三
    static List<LotteryResult> h3() {
        String[] places = {"第八名", "第九名", "第十名"};
        return common(places);
    }
}
