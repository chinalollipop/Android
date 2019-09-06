package com.hfcp.hf.common.utils;

import com.alibaba.fastjson.JSON;
import com.hfcp.hf.CFConstant;
import com.hfcp.hf.data.AllGamesResult;

import java.util.ArrayList;
import java.util.List;

import static com.hfcp.hf.common.utils.Utils.getContext;

/**
 * 获取奖期lottery_id 对应的 名字
 */

public class GetGameName {

    //官方盘
    private static List<AllGamesResult.DataBean.LotteriesBean> GFLottery  = new ArrayList<>();
    //信用盘
    private static List<AllGamesResult.DataBean.LotteriesBean> XYLottery  = new ArrayList<>();
    static {
        GFLottery = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_GUANWANG), AllGamesResult.DataBean.LotteriesBean.class);
        XYLottery = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_XINYONG), AllGamesResult.DataBean.LotteriesBean.class);
    }
    private GetGameName() {
    }

    public static String getGfLotteryName(String lottery_id) {
        String name ="";
        int size = GFLottery.size();
        for(int k=0;k<size;++k){
            if(lottery_id.equals(GFLottery.get(k).getLottery_id()+"")){
                name = GFLottery.get(k).getName();
                return name;
            }
        }
        return name;

    }

    public static String getXYLotteryName(String lottery_id) {
        String name ="";
        int size = GFLottery.size();
        for(int k=0;k<size;++k){
            if(lottery_id.equals(XYLottery.get(k).getId()+"")){
                name = XYLottery.get(k).getName();
                return name;
            }
        }
        return name;
    }

}
