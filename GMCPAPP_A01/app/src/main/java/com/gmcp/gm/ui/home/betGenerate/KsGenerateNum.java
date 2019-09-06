package com.gmcp.gm.ui.home.betGenerate;

import com.gmcp.gm.data.LotteryResult;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

/**
 * 快三生成号码投注区域
 */
class KsGenerateNum {

    //生成和值选项
    static List<LotteryResult> sumKS() {
        String[] lotteryNum = {"3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("和值");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //三同号单选
    static List<LotteryResult> sthKS() {
        String[] lotteryNum = {"111", "222", "333", "444", "555", "666"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("单选");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //三同号通选
    static List<LotteryResult> sthKStx() {
        String[] lotteryNum = {"通选"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("通选");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //二同号单选
    static List<LotteryResult> ethKS() {
        String[] places = {"同号", "不同号"};
        String[] lotteryNum1 = {"11", "22", "33", "44", "55", "66"};
        String[] lotteryNum2 = {"1", "2", "3", "4", "5", "6"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult1 = new LotteryResult();
        lotteryResult1.setPlaces(places[0]);
        lotteryResult1.setData(Arrays.asList(lotteryNum1));
        lotteryResultList.add(lotteryResult1);

        LotteryResult lotteryResult2 = new LotteryResult();
        lotteryResult2.setPlaces(places[1]);
        lotteryResult2.setData(Arrays.asList(lotteryNum2));
        lotteryResultList.add(lotteryResult2);

        return lotteryResultList;
    }

    //二同号复选
    static List<LotteryResult> ethKSfx() {
        String[] lotteryNum = {"11*", "22*", "33*", "44*", "55*", "66*"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("复选");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //三/二/猜必出不同号
    static List<LotteryResult> sbhKS() {
        String[] lotteryNum = {"1", "2", "3", "4", "5", "6"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //大小
    static List<LotteryResult> bs() {
        String[] lotteryNum = {"大", "小"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("大小");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //单双
    static List<LotteryResult> ds() {
        String[] lotteryNum = {"单", "双"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("单双");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //颜色全红
    static List<LotteryResult> ysR() {
        String[] lotteryNum = {"全红"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("颜色");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //颜色全黑
    static List<LotteryResult> ysB() {
        String[] lotteryNum = {"全黑"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("颜色");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

}
