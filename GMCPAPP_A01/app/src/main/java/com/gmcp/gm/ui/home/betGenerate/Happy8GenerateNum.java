package com.gmcp.gm.ui.home.betGenerate;

import com.gmcp.gm.data.LotteryResult;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

/**
 * 北京快乐8生成号码投注区域
 */
class Happy8GenerateNum {

    //任选区域的号码
    static List<LotteryResult> common() {
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        String[] option = {"随机", "大", "小", "单", "双", "清"};
        String[] lotteryNum = {"01", "02", "03", "04", "05", "06", "07", "08", "09", "10",
                "11", "12", "13", "14", "15", "16", "17", "18", "19", "20",
                "21", "22", "23", "24", "25", "26", "27", "28", "29", "30",
                "31", "32", "33", "34", "35", "36", "37", "38", "39", "40",
                "41", "42", "43", "44", "45", "46", "47", "48", "49", "50",
                "51", "52", "53", "54", "55", "56", "57", "58", "59", "60",
                "61", "62", "63", "64", "65", "66", "67", "68", "69", "70",
                "71", "72", "73", "74", "75", "76", "77", "78", "79", "80"};
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("任选");
        lotteryResult.setOption(Arrays.asList(option));
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

    //大小810
    static List<LotteryResult> dx810() {
        String[] lotteryNum = {"大", "810", "小"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("大小10");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //五行
    static List<LotteryResult> wx() {
        String[] lotteryNum = {"金", "木", "水", "火", "土"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("五行");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //奇偶和
    static List<LotteryResult> joh() {
        String[] lotteryNum = {"奇", "偶", "和"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("奇偶和");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //上中下
    static List<LotteryResult> szx() {
        String[] lotteryNum = {"上", "中", "下"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("上中下");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

}
