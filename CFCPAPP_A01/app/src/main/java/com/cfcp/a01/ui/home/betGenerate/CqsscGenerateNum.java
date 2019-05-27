package com.cfcp.a01.ui.home.betGenerate;

import android.support.v7.widget.RecyclerView;
import android.view.View;

import com.cfcp.a01.common.adapters.LotteryBottomAdapter;
import com.cfcp.a01.data.LotteryResult;
import com.cfcp.a01.ui.home.bet.OptionalSizeEvent;
import com.chad.library.adapter.base.BaseQuickAdapter;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

/**
 * 重庆时时彩生成投注区域号码
 */
class CqsscGenerateNum {

    //任选模式顶部View公用方法
    static void rvOps(RecyclerView rv, final List<Integer> listSec) {
        String[] ops = {"万位", "千位", "百位", "十位", "个位"};
        final LotteryBottomAdapter mLotteryBottomAdapter = new LotteryBottomAdapter(Arrays.asList(ops));
        rv.setAdapter(mLotteryBottomAdapter);
        mLotteryBottomAdapter.setSelect(listSec);
        final int listSize = listSec.size();
        mLotteryBottomAdapter.setOnItemClickListener(new BaseQuickAdapter.OnItemClickListener() {
            @Override
            public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                if (listSec.contains(position)) {
                    for (int i = 0; i < listSec.size(); i++) {
                        if (position == listSec.get(i) && listSec.size() > listSize) {
                            listSec.remove(i);
                            i = i - 1;
                        }
                    }
                } else {
                    listSec.add(position);
                }
                OptionalSizeEvent optionalSizeEvent = new OptionalSizeEvent();
                optionalSizeEvent.setSize(listSec.size());
                optionalSizeEvent.setListSec(listSec);
                EventBus.getDefault().post(optionalSizeEvent);
                mLotteryBottomAdapter.notifyDataSetChanged();
            }
        });
        OptionalSizeEvent optionalSizeEvent = new OptionalSizeEvent();
        optionalSizeEvent.setSize(listSec.size());
        optionalSizeEvent.setListSec(listSec);
        EventBus.getDefault().post(optionalSizeEvent);
    }

    //公用方法
    private static List<LotteryResult> common(String[] places) {
        String[] option = {"全", "大", "小", "单", "双", "清"};
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
        String[] places = {"万位", "千位", "百位", "十位", "个位"};
        return common(places);
    }

    //五星组选120
    static List<LotteryResult> z120() {
        String[] places = {"组选120"};
        return common(places);
    }

    //五星组选60/30/-前四/后四组选12
    static List<LotteryResult> z60() {
        String[] places = {"二重号", "单号"};
        return common(places);
    }

    //五星组选20-前四/后四组选4
    static List<LotteryResult> z20() {
        String[] places = {"三重号", "单号"};
        return common(places);
    }

    //五星组选10
    static List<LotteryResult> z10() {
        String[] places = {"三重号", "二重号"};
        return common(places);
    }

    //五星组选5
    static List<LotteryResult> z5() {
        String[] places = {"四重号", "单号"};
        return common(places);
    }

    //前四直选复式
    static List<LotteryResult> frontF() {
        String[] places = {"万位", "千位", "百位", "十位"};
        return common(places);
    }

    //后四直选复式
    static List<LotteryResult> behindF() {
        String[] places = {"千位", "百位", "十位", "个位"};
        return common(places);
    }

    //前四/后四组选24
    static List<LotteryResult> z24() {
        String[] places = {"组选24"};
        return common(places);
    }

    //前四/后四组选6
    static List<LotteryResult> z6() {
        String[] places = {"二重号"};
        return common(places);
    }

    //前三直选复式
    static List<LotteryResult> frontS() {
        String[] places = {"万位", "千位", "百位"};
        return common(places);
    }

    //中三直选复式
    static List<LotteryResult> middleS() {
        String[] places = {"千位", "百位", "十位"};
        return common(places);
    }

    //后三直选复式
    static List<LotteryResult> behindS() {
        String[] places = {"百位", "十位", "个位"};
        return common(places);
    }

    //前三/中三/后三直选和值
    static List<LotteryResult> sumS() {
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("直选和值");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResult.setOption(Arrays.asList(option));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //前三/中三/后三直选跨度
    static List<LotteryResult> spanS() {
        String[] places = {"直选跨度"};
        return common(places);
    }

    //前三/中三/后三组三
    static List<LotteryResult> z3S() {
        String[] places = {"组三"};
        return common(places);
    }

    //前三/中三/后三组六
    static List<LotteryResult> z6S() {
        String[] places = {"组六"};
        return common(places);
    }

    //前三/中三/后三组选和值
    static List<LotteryResult> sumZS() {
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum = {"1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("组选和值");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResult.setOption(Arrays.asList(option));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //前三/中三/后三包胆
    static List<LotteryResult> bdS() {
        String[] places = {"包胆"};
        return common(places);
    }

    //前三/中三/后三和值尾数
    static List<LotteryResult> mantissaS() {
        String[] places = {"和值尾数"};
        return common(places);
    }

    //前三/中三/后三特殊号码
    static List<LotteryResult> specialS() {
        String[] lotteryNum = {"豹子", "顺子", "对子"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("特殊号码");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //前三/中三/后三豹子
    static List<LotteryResult> bzS() {
        String[] lotteryNum = {"豹子"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("特殊号码");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //前三/中三/后三特殊顺子
    static List<LotteryResult> szS() {
        String[] lotteryNum = {"顺子"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("特殊号码");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //前三/中三/后三特殊对子
    static List<LotteryResult> dzS() {
        String[] lotteryNum = {"对子"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("特殊号码");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //二星后二复式
    static List<LotteryResult> behindSe() {
        String[] places = {"十位", "个位"};
        return common(places);
    }

    //二星前二复式
    static List<LotteryResult> frontSe() {
        String[] places = {"万位", "千位"};
        return common(places);
    }

    //二星直选和值
    static List<LotteryResult> sumZSe() {
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("直选和值");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResult.setOption(Arrays.asList(option));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //二星后二跨度
    static List<LotteryResult> spanBSe() {
        String[] places = {"后二跨度"};
        return common(places);
    }

    //二星前二跨度
    static List<LotteryResult> spanFSe() {
        String[] places = {"前二跨度"};
        return common(places);
    }

    //二星组选
    static List<LotteryResult> zS() {
        String[] places = {"组选"};
        return common(places);
    }

    //二星组选和值
    static List<LotteryResult> sumZuSe() {
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum = {"1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        LotteryResult lotteryResult = new LotteryResult();
        lotteryResult.setPlaces("组选和值");
        lotteryResult.setData(Arrays.asList(lotteryNum));
        lotteryResult.setOption(Arrays.asList(option));
        lotteryResultList.add(lotteryResult);
        return lotteryResultList;
    }

    //不定位
    static List<LotteryResult> bdw() {
        String[] places = {"不定位"};
        return common(places);
    }

    //后二大小单双
    static List<LotteryResult> h2bsds() {
        String[] places = {"十位", "个位"};
        String[] lotteryNum = {"大", "小", "单", "双"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            lotteryResult.setPlaces(aPlaces);
            lotteryResult.setData(Arrays.asList(lotteryNum));
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //后三大小单双
    static List<LotteryResult> h3bsds() {
        String[] places = {"百位", "十位", "个位"};
        String[] lotteryNum = {"大", "小", "单", "双"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            lotteryResult.setPlaces(aPlaces);
            lotteryResult.setData(Arrays.asList(lotteryNum));
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //前二大小单双
    static List<LotteryResult> q2bsds() {
        String[] places = {"万位", "千位"};
        String[] lotteryNum = {"大", "小", "单", "双"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            lotteryResult.setPlaces(aPlaces);
            lotteryResult.setData(Arrays.asList(lotteryNum));
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //前三大小单双
    static List<LotteryResult> q3bsds() {
        String[] places = {"万位", "千位", "百位"};
        String[] lotteryNum = {"大", "小", "单", "双"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            lotteryResult.setPlaces(aPlaces);
            lotteryResult.setData(Arrays.asList(lotteryNum));
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //一帆风顺
    static List<LotteryResult> yffs() {
        String[] places = {"一帆风顺"};
        return common(places);
    }

    //好事成双
    static List<LotteryResult> hscs() {
        String[] places = {"好事成双"};
        return common(places);
    }

    //三星报喜
    static List<LotteryResult> sxbx() {
        String[] places = {"三星报喜"};
        return common(places);
    }

    //四季发财
    static List<LotteryResult> sjfc() {
        String[] places = {"四季发财"};
        return common(places);
    }

    //五码趣味三星
    static List<LotteryResult> wmqwsx() {
        String[] places = {"万位", "千位", "百位", "十位", "个位"};
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum1 = {"小(0-4)", "大(5-9)"};
        String[] lotteryNum2 = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            if (aPlaces.equals("万位") || aPlaces.equals("千位")) {
                lotteryResult.setData(Arrays.asList(lotteryNum1));
            } else {
                lotteryResult.setData(Arrays.asList(lotteryNum2));
                lotteryResult.setOption(Arrays.asList(option));
            }
            lotteryResult.setPlaces(aPlaces);
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //四码趣味三星
    static List<LotteryResult> smqwsx() {
        String[] places = {"千位", "百位", "十位", "个位"};
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum1 = {"小(0-4)", "大(5-9)"};
        String[] lotteryNum2 = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            if (aPlaces.equals("千位")) {
                lotteryResult.setData(Arrays.asList(lotteryNum1));
            } else {
                lotteryResult.setData(Arrays.asList(lotteryNum2));
                lotteryResult.setOption(Arrays.asList(option));
            }
            lotteryResult.setPlaces(aPlaces);
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //后三趣味二星
    static List<LotteryResult> h3qwex() {
        String[] places = {"百位", "十位", "个位"};
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum1 = {"小(0-4)", "大(5-9)"};
        String[] lotteryNum2 = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            if (aPlaces.equals("百位")) {
                lotteryResult.setData(Arrays.asList(lotteryNum1));
            } else {
                lotteryResult.setData(Arrays.asList(lotteryNum2));
                lotteryResult.setOption(Arrays.asList(option));
            }
            lotteryResult.setPlaces(aPlaces);
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //前三趣味二星
    static List<LotteryResult> q3qwex() {
        String[] places = {"万位", "千位", "百位"};
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum1 = {"小(0-4)", "大(5-9)"};
        String[] lotteryNum2 = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            if (aPlaces.equals("万位")) {
                lotteryResult.setData(Arrays.asList(lotteryNum1));
            } else {
                lotteryResult.setData(Arrays.asList(lotteryNum2));
                lotteryResult.setOption(Arrays.asList(option));
            }
            lotteryResult.setPlaces(aPlaces);
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //五码区间三星
    static List<LotteryResult> wmqjsx() {
        String[] places = {"万位", "千位", "百位", "十位", "个位"};
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum1 = {"一区(0,1)", "二区(2,3)", "三区(4,5)", "四区(6,7)", "五区(8,9)"};
        String[] lotteryNum2 = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            if (aPlaces.equals("万位") || aPlaces.equals("千位")) {
                lotteryResult.setData(Arrays.asList(lotteryNum1));
            } else {
                lotteryResult.setData(Arrays.asList(lotteryNum2));
                lotteryResult.setOption(Arrays.asList(option));
            }
            lotteryResult.setPlaces(aPlaces);
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //四码区间三星
    static List<LotteryResult> smqjsx() {
        String[] places = {"千位", "百位", "十位", "个位"};
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum1 = {"一区(0,1)", "二区(2,3)", "三区(4,5)", "四区(6,7)", "五区(8,9)"};
        String[] lotteryNum2 = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            if (aPlaces.equals("千位")) {
                lotteryResult.setData(Arrays.asList(lotteryNum1));
            } else {
                lotteryResult.setData(Arrays.asList(lotteryNum2));
                lotteryResult.setOption(Arrays.asList(option));
            }
            lotteryResult.setPlaces(aPlaces);
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //后三区间二星
    static List<LotteryResult> h3qjex() {
        String[] places = {"百位", "十位", "个位"};
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum1 = {"一区(0,1)", "二区(2,3)", "三区(4,5)", "四区(6,7)", "五区(8,8)"};
        String[] lotteryNum2 = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            if (aPlaces.equals("百位")) {
                lotteryResult.setData(Arrays.asList(lotteryNum1));
            } else {
                lotteryResult.setData(Arrays.asList(lotteryNum2));
                lotteryResult.setOption(Arrays.asList(option));
            }
            lotteryResult.setPlaces(aPlaces);
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
    }

    //前三区间二星
    static List<LotteryResult> q3qjex() {
        String[] places = {"万位", "千位", "百位"};
        String[] option = {"全", "大", "小", "单", "双", "清"};
        String[] lotteryNum1 = {"一区(0,1)", "二区(2,3)", "三区(4,5)", "四区(6,7)", "五区(8,8)"};
        String[] lotteryNum2 = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9"};
        List<LotteryResult> lotteryResultList = new ArrayList<>();
        for (String aPlaces : places) {
            LotteryResult lotteryResult = new LotteryResult();
            if (aPlaces.equals("万位")) {
                lotteryResult.setData(Arrays.asList(lotteryNum1));
            } else {
                lotteryResult.setData(Arrays.asList(lotteryNum2));
                lotteryResult.setOption(Arrays.asList(option));
            }
            lotteryResult.setPlaces(aPlaces);
            lotteryResultList.add(lotteryResult);
        }
        return lotteryResultList;
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

    //万千
    static List<LotteryResult> wq() {
        String places = "万：千";
        return lAndH(places);
    }

    //万百
    static List<LotteryResult> wb() {
        String places = "万：百";
        return lAndH(places);
    }

    //万十
    static List<LotteryResult> ws() {
        String places = "万：十";
        return lAndH(places);
    }

    //万个
    static List<LotteryResult> wg() {
        String places = "万：个";
        return lAndH(places);
    }

    //千百
    static List<LotteryResult> qb() {
        String places = "千：百";
        return lAndH(places);
    }

    //千十
    static List<LotteryResult> qs() {
        String places = "千：十";
        return lAndH(places);
    }

    //千个
    static List<LotteryResult> qg() {
        String places = "千：个";
        return lAndH(places);
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

    //任选直选二跨度
    static List<LotteryResult> spanRXe() {
        String[] places = {"直选跨度"};
        return common(places);
    }
}
