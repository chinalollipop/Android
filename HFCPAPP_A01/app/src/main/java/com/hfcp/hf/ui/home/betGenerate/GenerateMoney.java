package com.hfcp.hf.ui.home.betGenerate;

import android.content.Context;
import android.text.TextUtils;

import com.hfcp.hf.common.widget.DeleteTipsPop;
import com.hfcp.hf.data.BetGameSettingsForRefreshResult;
import com.hfcp.hf.data.UpBetData;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.HashMap;
import java.util.LinkedHashSet;
import java.util.List;
import java.util.Map;
import java.util.Objects;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * 投注注数计算
 */
public class GenerateMoney {
    private int M = 0;
    private int lotteryId;
    private List<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean> mWayGroups;
    private String[] mPosition;
    private List<UpBetData> mUpdateBet;
    private static String mInput;
    private int mListSecSize;
    private String result;

    //列表选择时生成投注金额
    public GenerateMoney(int lottery_id, List<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean> wayGroups, String[] position, List<UpBetData> updateBet) {
        lotteryId = lottery_id;
        mWayGroups = wayGroups;
        mPosition = position;
        mUpdateBet = updateBet;
    }

    //输入框输入时生成投注金额
    public GenerateMoney(int lottery_id, List<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean> wayGroups, String[] position, String input, int size) {
        lotteryId = lottery_id;
        mWayGroups = wayGroups;
        mPosition = position;
        mInput = input;
        mListSecSize = size;
    }

    //筛选号码时的弹窗
    public String setPopup(Context context) {
        List<String> listEle = new ArrayList<>();
        int optionID = mWayGroups.get(Integer.valueOf(mPosition[0])).getChildren().get(Integer.valueOf(mPosition[1])).getChildren().get(Integer.valueOf(mPosition[2])).getId();
        switch (lotteryId) {
            case 1://时时彩类
            case 13:
            case 16:
            case 28:
            case 53:
                switch (mWayGroups.get(Integer.valueOf(mPosition[0])).getId()) {
                    case 2:
                        new DeleteTipsPop(context, getDifferent(zuD(5), zuDS(5)), interception(5)).showPopupWindow();
                        result = zuDS(5).toString();
                        break;
                    case 97:
                    case 3:
                        new DeleteTipsPop(context, getDifferent(zuD(4), zuDS(4)), interception(4)).showPopupWindow();
                        result = zuDS(4).toString();
                        break;
                    case 8:
                    case 61:
                    case 1:
                        switch (optionID) {
                            case 1:
                            case 8:
                            case 142:
                                new DeleteTipsPop(context, getDifferent(zuD(3), zuDS(3)), interception(3)).showPopupWindow();
                                result = zuDS(3).toString();
                                break;
                            case 13:
                            case 81:
                            case 152:
                                new DeleteTipsPop(context, getDifferent(hhZ3(zuD(3)), sxHz()), interception(3)).showPopupWindow();
                                result = sxHz().toString();
                                break;
                            case 2:
                            case 9:
                            case 143:
                                new DeleteTipsPop(context, getDifferent(hhZ3(zuD(3)), sxZ3()), interception(3)).showPopupWindow();
                                result = sxZ3().toString();
                                break;
                            case 3:
                            case 10:
                            case 144:
                                new DeleteTipsPop(context, getDifferent(hhZ3(zuD(3)), sxZ6()), interception(3)).showPopupWindow();
                                result = sxZ6().toString();
                                break;
                        }
                        break;
                    case 15:
                        switch (optionID) {
                            case 4:
                            case 11:
                                new DeleteTipsPop(context, getDifferent(zuD(2), zuDS(2)), interception(2)).showPopupWindow();
                                result = zuDS(2).toString();
                                break;
                            case 5:
                            case 12:
                                new DeleteTipsPop(context, getDifferent(zuD(2), sxZ2()), interception(2)).showPopupWindow();
                                result = sxZ2().toString();
                                break;
                        }
                        break;
                    case 93:
                        switch (optionID) {
                            case 200:
                                new DeleteTipsPop(context, getDifferent(zuD(2), zuDS(2)), interception(2)).showPopupWindow();
                                result = zuDS(2).toString();
                                break;
                            case 186:
                                new DeleteTipsPop(context, getDifferent(zuD(3), zuDS(3)), interception(3)).showPopupWindow();
                                result = zuDS(3).toString();
                                break;
                            case 187:
                                new DeleteTipsPop(context, getDifferent(zuD(4), zuDS(4)), interception(4)).showPopupWindow();
                                result = zuDS(4).toString();
                                break;
                            case 201:
                                new DeleteTipsPop(context, getDifferent(zuD(2), sxZ2()), interception(2)).showPopupWindow();
                                result = sxZ2().toString();
                                break;
                            case 188:
                                new DeleteTipsPop(context, getDifferent(zuD(3), sxZ3()), interception(3)).showPopupWindow();
                                result = sxZ3().toString();
                                break;
                            case 189:
                                new DeleteTipsPop(context, getDifferent(zuD(3), sxZ6()), interception(3)).showPopupWindow();
                                result = sxZ6().toString();
                                break;
                            case 190:
                                new DeleteTipsPop(context, getDifferent(zuD(3), sxHz()), interception(3)).showPopupWindow();
                                result = sxHz().toString();
                                break;
                        }
                        break;
                }
                break;
            case 20://3d类
                switch (mWayGroups.get(Integer.valueOf(mPosition[0])).getId()) {
                    case 48:
                        switch (optionID) {
                            case 123:
                                new DeleteTipsPop(context, getDifferent(zuD(3), zuDS(3)), interception(3)).showPopupWindow();
                                result = zuDS(3).toString();
                                break;
                            case 124:
                                new DeleteTipsPop(context, getDifferent(zuD(3), sxZ3()), interception(3)).showPopupWindow();
                                result = sxZ3().toString();
                                break;
                            case 125:
                                new DeleteTipsPop(context, getDifferent(zuD(3), sxZ6()), interception(3)).showPopupWindow();
                                result = sxZ6().toString();
                                break;
                            case 130:
                                new DeleteTipsPop(context, getDifferent(zuD(3), sxHz()), interception(3)).showPopupWindow();
                                result = sxHz().toString();
                                break;
                        }
                        break;
                    case 49:
                        switch (optionID) {
                            case 128:
                            case 126:
                                new DeleteTipsPop(context, getDifferent(zuD(2), zuDS(2)), interception(2)).showPopupWindow();
                                result = zuDS(2).toString();
                                break;
                            case 129:
                            case 127:
                                new DeleteTipsPop(context, getDifferent(zuD(2), sxZ2()), interception(2)).showPopupWindow();
                                result = sxZ2().toString();
                                break;
                        }
                        break;
                }
                break;
            case 9:
            case 14:
            case 44://11选5
                switch (mWayGroups.get(Integer.valueOf(mPosition[0])).getId()) {
                    case 30:
                        switch (optionID) {
                            case 95:
                                new DeleteTipsPop(context, listEle, filtrate(3)).showPopupWindow();
                                result = endRepeat.toString();
                                break;
                            case 97:
                                new DeleteTipsPop(context, listEle, comFiltrate(3)).showPopupWindow();
                                result = endCom.toString();
                                break;
                        }
                        break;
                    case 31:
                        switch (optionID) {
                            case 94:
                                new DeleteTipsPop(context, listEle, filtrate(2)).showPopupWindow();
                                result = endRepeat.toString();
                                break;
                            case 96:
                                new DeleteTipsPop(context, listEle, comFiltrate(2)).showPopupWindow();
                                result = endCom.toString();
                                break;
                        }
                        break;
                    case 35:
                        switch (optionID) {
                            case 86:
                                new DeleteTipsPop(context, listEle, comFiltrate(1)).showPopupWindow();
                                result = endCom.toString();
                                break;
                            case 87:
                                new DeleteTipsPop(context, listEle, comFiltrate(2)).showPopupWindow();
                                result = endCom.toString();
                                break;
                            case 88:
                                new DeleteTipsPop(context, listEle, comFiltrate(3)).showPopupWindow();
                                result = endCom.toString();
                                break;
                            case 89:
                                new DeleteTipsPop(context, listEle, comFiltrate(4)).showPopupWindow();
                                result = endCom.toString();
                                break;
                            case 90:
                                new DeleteTipsPop(context, listEle, comFiltrate(5)).showPopupWindow();
                                result = endCom.toString();
                                break;
                            case 91:
                                new DeleteTipsPop(context, listEle, comFiltrate(6)).showPopupWindow();
                                result = endCom.toString();
                                break;
                            case 92:
                                new DeleteTipsPop(context, listEle, comFiltrate(7)).showPopupWindow();
                                result = endCom.toString();
                                break;
                            case 93:
                                new DeleteTipsPop(context, listEle, comFiltrate(8)).showPopupWindow();
                                result = endCom.toString();
                                break;
                        }
                        break;
                }
                break;
            case 10:
            case 19:
            case 49:
            case 52:
                switch (mWayGroups.get(Integer.valueOf(mPosition[0])).getId()) {
                    case 114:
                        new DeleteTipsPop(context, listEle, filtrate(2)).showPopupWindow();
                        result = endRepeat.toString();
                        break;
                    case 117:
                        new DeleteTipsPop(context, listEle, filtrate(3)).showPopupWindow();
                        result = endRepeat.toString();
                        break;
                }
                break;
        }
        return result;
    }

    //生成金额
    public int generateMoney() {
        int optionID = mWayGroups.get(Integer.valueOf(mPosition[0])).getChildren().get(Integer.valueOf(mPosition[1])).getChildren().get(Integer.valueOf(mPosition[2])).getId();
        switch (lotteryId) {
            case 1://时时彩类
            case 13:
            case 16:
            case 28:
            case 53:
                switch (mWayGroups.get(Integer.valueOf(mPosition[0])).getId()) {
                    case 2:
                        switch (optionID) {
                            case 68:
                                M = mUpdateBet.get(0).getSelectList().size();
                                for (int i = 1; i < mUpdateBet.size(); i++) {
                                    M *= mUpdateBet.get(i).getSelectList().size();
                                }
                                break;
                            case 7:
                                M = zuDS(5).size();
                                break;
                            case 32:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 5);
                                break;
                            case 31:
                                if (mUpdateBet.get(1).getSelectList().size() > 2) {
                                    for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                        if (mUpdateBet.get(1).getSelectList().contains(mUpdateBet.get(0).getSelectList().get(i))) {
                                            M += combination(mUpdateBet.get(1).getSelectList().size() - 1, 3);
                                        } else {
                                            M += combination(mUpdateBet.get(1).getSelectList().size(), 3);
                                        }
                                    }
                                }
                                break;
                            case 30:
                                if (mUpdateBet.get(0).getSelectList().size() > 1) {
                                    for (int i = 0; i < mUpdateBet.get(1).getSelectList().size(); i++) {
                                        if (mUpdateBet.get(0).getSelectList().contains(mUpdateBet.get(1).getSelectList().get(i))) {
                                            M += combination(mUpdateBet.get(0).getSelectList().size() - 1, 2);
                                        } else {
                                            M += combination(mUpdateBet.get(0).getSelectList().size(), 2);
                                        }
                                    }
                                }
                                break;
                            case 29:
                                M = z2();
                                break;
                            case 28:
                            case 27:
                                M = z4();
                                break;
                        }
                        break;
                    case 97:
                    case 3:
                        switch (optionID) {
                            case 295:
                            case 67:
                                M = mUpdateBet.get(0).getSelectList().size();
                                for (int i = 1; i < mUpdateBet.size(); i++) {
                                    M *= mUpdateBet.get(i).getSelectList().size();
                                }
                                break;
                            case 351:
                            case 6:
                                M = zuDS(4).size();
                                break;
                            case 242:
                            case 26:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 4);
                                break;
                            case 329:
                            case 25:
                                M = z2();
                                break;
                            case 243:
                            case 24:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 2);
                                break;
                            case 330:
                            case 23:
                                M = z4();
                                break;
                        }
                        break;
                    case 8:
                    case 61:
                    case 1:
                        switch (optionID) {
                            case 65:
                            case 150:
                            case 69:
                                M = mUpdateBet.get(0).getSelectList().size();
                                for (int i = 1; i < mUpdateBet.size(); i++) {
                                    M *= mUpdateBet.get(i).getSelectList().size();
                                }
                                break;
                            case 71:
                            case 151:
                            case 73:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sum3dzhix(mUpdateBet.get(0).getSelectList().get(i));
                                }
                                break;
                            case 60:
                            case 62:
                            case 149:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sub3dzx(mUpdateBet.get(0).getSelectList().get(i));
                                }
                                break;
                            case 1:
                            case 8:
                            case 142:
                                M = zuDS(3).size();
                                break;
                            case 16:
                            case 49:
                            case 145:
                                M = (int) arrangement(mUpdateBet.get(0).getSelectList().size());
                                break;
                            case 17:
                            case 50:
                            case 146:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 3);
                                break;
                            case 13:
                            case 81:
                            case 152:
                                M = sxHz().size();
                                break;
                            case 75:
                            case 80:
                            case 154:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sum3dzux(mUpdateBet.get(0).getSelectList().get(i));
                                }
                                break;
                            case 2:
                            case 9:
                            case 143:
                                M = sxZ3().size();
                                break;
                            case 3:
                            case 10:
                            case 144:
                                M = sxZ6().size();
                                break;
                            case 64:
                            case 83:
                            case 153:
                                M = 54;
                                break;
                            case 33:
                            case 48:
                            case 388:
                            case 387:
                            case 385:
                            case 156:
                            case 155:
                            case 393:
                            case 392:
                            case 391:
                            case 54:
                            case 57:
                            case 390:
                            case 389:
                            case 386:
                                M = mUpdateBet.get(0).getSelectList().size();
                                break;
                        }
                        break;
                    case 15:
                        switch (optionID) {
                            case 70:
                            case 66:
                                M = mUpdateBet.get(0).getSelectList().size();
                                for (int i = 1; i < mUpdateBet.size(); i++) {
                                    M *= mUpdateBet.get(i).getSelectList().size();
                                }
                                break;
                            case 59:
                            case 20:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 2);
                                break;
                            case 4:
                            case 11:
                                M = zuDS(2).size();
                                break;
                            case 5:
                            case 12:
                                M = sxZ2().size();
                                break;
                            case 74:
                            case 72:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sum2dzhix(mUpdateBet.get(0).getSelectList().get(i));
                                }
                                break;
                            case 77:
                            case 76:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sum2dzux(mUpdateBet.get(0).getSelectList().get(i));
                                }
                                break;
                            case 63:
                            case 61:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sub2dzx(mUpdateBet.get(0).getSelectList().get(i));
                                }
                                break;
                            case 85:
                            case 84:
                                M = 9;
                                break;
                        }
                        break;
                    case 18:
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            M += mUpdateBet.get(i).getSelectList().size();
                        }
                        break;
                    case 20:
                        switch (optionID) {
                            case 51:
                            case 18:
                            case 34:
                                M = mUpdateBet.get(0).getSelectList().size();
                                break;
                            case 52:
                            case 21:
                            case 35:
                            case 36:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 2);
                                break;
                            case 37:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 3);
                                break;
                        }
                        break;
                    case 24:
                        M = mUpdateBet.get(0).getSelectList().size();
                        for (int i = 1; i < mUpdateBet.size(); i++) {
                            M *= mUpdateBet.get(i).getSelectList().size();
                        }
                        break;
                    case 26:
                        switch (optionID) {
                            case 38:
                            case 39:
                            case 55:
                            case 40:
                            case 41:
                            case 42:
                            case 56:
                            case 43:
                                M = mUpdateBet.get(0).getSelectList().size();
                                for (int i = 1; i < mUpdateBet.size(); i++) {
                                    M *= mUpdateBet.get(i).getSelectList().size();
                                }
                                break;
                            case 44:
                            case 45:
                            case 46:
                            case 47:
                                M = mUpdateBet.get(0).getSelectList().size();
                                break;
                        }
                        break;
                    case 93:
                        switch (optionID) {
                            case 199:
                                for (int i = 0; i < 4; i++) {
                                    for (int j = 1; j < 5; j++) {
                                        if (i < j) {
                                            M += mUpdateBet.get(i).getSelectList().size() * mUpdateBet.get(j).getSelectList().size();
                                        }
                                    }
                                }
                                break;
                            case 179:
                                for (int i = 0; i < 3; i++) {
                                    for (int j = 1; j < 4; j++) {
                                        for (int k = 2; k < 5; k++) {
                                            if (i < j && j < k) {
                                                M += mUpdateBet.get(i).getSelectList().size() * mUpdateBet.get(j).getSelectList().size() * mUpdateBet.get(k).getSelectList().size();
                                            }
                                        }
                                    }
                                }
                                break;
                            case 180:
                                M = mUpdateBet.get(0).getSelectList().size() * mUpdateBet.get(1).getSelectList().size() * mUpdateBet.get(2).getSelectList().size() * mUpdateBet.get(3).getSelectList().size() +
                                        mUpdateBet.get(0).getSelectList().size() * mUpdateBet.get(1).getSelectList().size() * mUpdateBet.get(2).getSelectList().size() * mUpdateBet.get(4).getSelectList().size() +
                                        mUpdateBet.get(0).getSelectList().size() * mUpdateBet.get(1).getSelectList().size() * mUpdateBet.get(3).getSelectList().size() * mUpdateBet.get(4).getSelectList().size() +
                                        mUpdateBet.get(0).getSelectList().size() * mUpdateBet.get(2).getSelectList().size() * mUpdateBet.get(3).getSelectList().size() * mUpdateBet.get(4).getSelectList().size() +
                                        mUpdateBet.get(1).getSelectList().size() * mUpdateBet.get(2).getSelectList().size() * mUpdateBet.get(3).getSelectList().size() * mUpdateBet.get(4).getSelectList().size();
                                break;
                            case 200:
                                M = zuDS(2).size() * (int) combination(mListSecSize, 2);
                                break;
                            case 186:
                                M = zuDS(3).size() * (int) combination(mListSecSize, 3);
                                break;
                            case 187:
                                M = zuDS(4).size() * (int) combination(mListSecSize, 4);
                                break;
                            case 196:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sum2dzhix(mUpdateBet.get(0).getSelectList().get(i)) * combination(mUpdateBet.get(0).getListSec().size(), 2);
                                }
                                break;
                            case 183:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sum3dzhix(mUpdateBet.get(0).getSelectList().get(i)) * combination(mUpdateBet.get(0).getListSec().size(), 3);
                                }
                                break;
                            case 198:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sub2dzx(mUpdateBet.get(0).getSelectList().get(i)) * combination(mUpdateBet.get(0).getListSec().size(), 2);
                                }
                                break;
                            case 185:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sub3dzx(mUpdateBet.get(0).getSelectList().get(i)) * combination(mUpdateBet.get(0).getListSec().size(), 3);
                                }
                                break;
                            case 195:
                                M = (int)(combination(mUpdateBet.get(0).getSelectList().size(), 2) * combination(mUpdateBet.get(0).getListSec().size(), 2));
                                break;
                            case 201:
                                M = sxZ2().size() * (int) combination(mListSecSize, 2);
                                break;
                            case 197:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sum2dzux(mUpdateBet.get(0).getSelectList().get(i)) * combination(mUpdateBet.get(0).getListSec().size(), 2);
                                }
                                break;
                            case 184:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sum3dzux(mUpdateBet.get(0).getSelectList().get(i)) * combination(mUpdateBet.get(0).getListSec().size(), 3);
                                }
                                break;
                            case 181:
                                M = (int) arrangement(mUpdateBet.get(0).getSelectList().size()) * (int) combination(mUpdateBet.get(0).getListSec().size(), 3);
                                break;
                            case 188:
                                M = sxZ3().size() * (int) combination(mListSecSize, 3);
                                break;
                            case 182:
                                M += combination(mUpdateBet.get(0).getSelectList().size(), 3) * combination(mUpdateBet.get(0).getListSec().size(), 3);
                                break;
                            case 189:
                                M = sxZ6().size() * (int) combination(mListSecSize, 3);
                                break;
                            case 190:
                                M = sxHz().size() * (int) combination(mListSecSize, 3);
                                break;
                            case 194:
                                M += combination(mUpdateBet.get(0).getSelectList().size(), 4) * combination(mUpdateBet.get(0).getListSec().size(), 4);
                                break;
                            case 193:
                                M += z2() * combination(mUpdateBet.get(0).getListSec().size(), 4);
                                break;
                            case 192:
                                M += combination(mUpdateBet.get(0).getSelectList().size(), 2) * combination(mUpdateBet.get(0).getListSec().size(), 4);
                                break;
                            case 191:
                                M += z4() * combination(mUpdateBet.get(0).getListSec().size(), 4);
                                break;
                        }
                        break;
                    case 100:
                        M = mUpdateBet.get(0).getSelectList().size();
                        break;
                }
                break;
            case 10://pk10类
            case 19:
            case 49:
            case 52:
                switch (mWayGroups.get(Integer.valueOf(mPosition[0])).getId()) {
                    case 87:
                        M = mUpdateBet.get(0).getSelectList().size();
                        break;
                    case 89:
                    case 91:
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            M += mUpdateBet.get(i).getSelectList().size();
                        }
                        break;
                    case 114:
                        switch (optionID) {
                            case 396:
                            case 398:
                                filtrate(2);
                                M = endRepeat.size();
                                break;
                            default:
                                M = z4();
                                break;
                        }
                        break;
                    case 117:
                        switch (optionID) {
                            case 397:
                            case 399:
                                filtrate(3);
                                M = endRepeat.size();
                                break;
                            default:
                                M = pk10sx();
                                break;
                        }
                        break;
                }
                break;
            case 15://快三类
            case 17:
            case 50:
            case 51:
                switch (mWayGroups.get(Integer.valueOf(mPosition[0])).getId()) {
                    case 65:
                    case 66:
                    case 72:
                    case 73:
                    case 74:
                    case 85:
                    case 110:
                        M = mUpdateBet.get(0).getSelectList().size();
                        break;
                    case 68:
                        switch (optionID) {
                            case 160:
                                M = z4();
                                break;
                            case 161:
                                M = mUpdateBet.get(0).getSelectList().size();
                                break;
                        }
                        break;
                    case 70:
                        if (mUpdateBet.get(0).getSelectList().size() > 2) {
                            M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 3);
                        }
                        break;
                    case 71:
                        if (mUpdateBet.get(0).getSelectList().size() > 1) {
                            M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 2);
                        }
                        break;
                }
                break;
            case 9://11选5类
            case 14:
            case 44:
                switch (mWayGroups.get(Integer.valueOf(mPosition[0])).getId()) {
                    case 30:
                        switch (optionID) {
                            case 95:
                                filtrate(3);
                                M = endRepeat.size();
                                break;
                            case 97:
                                comFiltrate(3);
                                M = endCom.size();
                                break;
                            case 112:
                                M = pk10sx();
                                break;
                            case 108:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 3);
                                break;
                            case 121:
                                if (mUpdateBet.get(0).getSelectList().size() > 1) {
                                    M = mUpdateBet.get(1).getSelectList().size();
                                } else {
                                    M = (int) combination(mUpdateBet.get(1).getSelectList().size(), 2) * mUpdateBet.get(0).getSelectList().size();
                                }
                                break;
                        }
                        break;
                    case 31:
                        switch (optionID) {
                            case 94:
                                filtrate(2);
                                M = endRepeat.size();
                                break;
                            case 96:
                                comFiltrate(2);
                                M = endCom.size();
                                break;
                            case 111:
                                M = z4();
                                break;
                            case 107:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 2);
                                break;
                            case 120:
                                M = mUpdateBet.get(1).getSelectList().size() * mUpdateBet.get(0).getSelectList().size();
                                break;
                        }
                        break;
                    case 32:
                    case 33:
                        M = mUpdateBet.get(0).getSelectList().size();
                        break;
                    case 42:
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            M += mUpdateBet.get(i).getSelectList().size();
                        }
                        break;
                    case 34:
                        switch (optionID) {
                            case 98:
                                M = mUpdateBet.get(0).getSelectList().size();
                                break;
                            case 99:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 2);
                                break;
                            case 100:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 3);
                                break;
                            case 101:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 4);
                                break;
                            case 102:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 5);
                                break;
                            case 103:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 6);
                                break;
                            case 104:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 7);
                                break;
                            case 105:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 8);
                                break;
                        }
                        break;
                    case 35:
                        switch (optionID) {
                            case 86:
                                comFiltrate(1);
                                break;
                            case 87:
                                comFiltrate(2);
                                break;
                            case 88:
                                comFiltrate(3);
                                break;
                            case 89:
                                comFiltrate(4);
                                break;
                            case 90:
                                comFiltrate(5);
                                break;
                            case 91:
                                comFiltrate(6);
                                break;
                            case 92:
                                comFiltrate(7);
                                break;
                            case 93:
                                comFiltrate(8);
                                break;
                        }
                        M = endCom.size();
                        break;
                    case 36:
                        switch (optionID) {
                            case 113:
                                M = mUpdateBet.get(1).getSelectList().size() * mUpdateBet.get(0).getSelectList().size();
                                break;
                            case 114:
                                if (mUpdateBet.get(0).getSelectList().size() == 0 || mUpdateBet.get(0).getSelectList().size() > 3) {
                                    M = 0;
                                } else {
                                    M = (int) combination(mUpdateBet.get(1).getSelectList().size(), 3 - mUpdateBet.get(0).getSelectList().size());
                                }
                                break;
                            case 115:
                                if (mUpdateBet.get(0).getSelectList().size() == 0 || mUpdateBet.get(0).getSelectList().size() > 4) {
                                    M = 0;
                                } else {
                                    M = (int) combination(mUpdateBet.get(1).getSelectList().size(), 4 - mUpdateBet.get(0).getSelectList().size());
                                }
                                break;
                            case 116:
                                if (mUpdateBet.get(0).getSelectList().size() == 0 || mUpdateBet.get(0).getSelectList().size() > 5) {
                                    M = 0;
                                } else {
                                    M = (int) combination(mUpdateBet.get(1).getSelectList().size(), 5 - mUpdateBet.get(0).getSelectList().size());
                                }
                                break;
                            case 117:
                                if (mUpdateBet.get(0).getSelectList().size() == 0 || mUpdateBet.get(0).getSelectList().size() > 6) {
                                    M = 0;
                                } else {
                                    M = (int) combination(mUpdateBet.get(1).getSelectList().size(), 6 - mUpdateBet.get(0).getSelectList().size());
                                }
                                break;
                            case 118:
                                if (mUpdateBet.get(0).getSelectList().size() == 0 || mUpdateBet.get(0).getSelectList().size() > 7) {
                                    M = 0;
                                } else {
                                    M = (int) combination(mUpdateBet.get(1).getSelectList().size(), 7 - mUpdateBet.get(0).getSelectList().size());
                                }
                                break;
                            case 119:
                                if (mUpdateBet.get(0).getSelectList().size() == 0 || mUpdateBet.get(0).getSelectList().size() > 8) {
                                    M = 0;
                                } else {
                                    M = (int) combination(mUpdateBet.get(1).getSelectList().size(), 8 - mUpdateBet.get(0).getSelectList().size());
                                }
                                break;
                        }
                        break;
                }
                break;
            case 20://3D类
                switch (mWayGroups.get(Integer.valueOf(mPosition[0])).getId()) {
                    case 48:
                        switch (optionID) {
                            case 123:
                                M = zuDS(3).size();
                                break;
                            case 124:
                                M = sxZ3().size();
                                break;
                            case 125:
                                M = sxZ6().size();
                                break;
                            case 130:
                                M = sxHz().size();
                                break;
                            case 136:
                                M = mUpdateBet.get(0).getSelectList().size();
                                for (int i = 1; i < mUpdateBet.size(); i++) {
                                    M *= mUpdateBet.get(i).getSelectList().size();
                                }
                                break;
                            case 139:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sum3dzhix(mUpdateBet.get(0).getSelectList().get(i));
                                }
                                break;
                            case 131:
                                M = (int) arrangement(mUpdateBet.get(0).getSelectList().size());
                                break;
                            case 132:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 3);
                                break;
                            case 140:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    M += sum3dzux(mUpdateBet.get(0).getSelectList().get(i));
                                }
                                break;
                        }
                        break;
                    case 49:
                        switch (optionID) {
                            case 126:
                            case 128:
                                M = zuDS(2).size();
                                break;
                            case 127:
                            case 129:
                                M = sxZ2().size();
                                break;
                            case 138:
                            case 137:
                                M = mUpdateBet.get(0).getSelectList().size();
                                for (int i = 1; i < mUpdateBet.size(); i++) {
                                    M *= mUpdateBet.get(i).getSelectList().size();
                                }
                                break;
                            case 134:
                            case 135:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 2);
                                break;
                        }
                        break;
                    case 50:
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            M += mUpdateBet.get(i).getSelectList().size();
                        }
                        break;
                    case 51:
                        switch (optionID) {
                            case 133:
                                M = mUpdateBet.get(0).getSelectList().size();
                                break;
                            case 485:
                                M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 2);
                                break;
                        }
                        break;
                    case 104:
                        M = mUpdateBet.get(0).getSelectList().size();
                        break;
                }
                break;
            case 37://北京快乐8类
                switch (mWayGroups.get(Integer.valueOf(mPosition[0])).getId()) {
                    case 150:
                    case 152:
                    case 153:
                    case 120:
                        M = mUpdateBet.get(0).getSelectList().size();
                        break;
                    case 121:
                        M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 2);
                        break;
                    case 122:
                        M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 3);
                        break;
                    case 123:
                        M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 4);
                        break;
                    case 124:
                        M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 5);
                        break;
                    case 125:
                        M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 6);
                        break;
                    case 126:
                        M = (int) combination(mUpdateBet.get(0).getSelectList().size(), 7);
                        break;
                }
                break;
        }
        return M;
    }

    private static List<List<Integer>> endRepeat;
    private static List<List<Integer>> endCom;
    private List<Integer> excess = new ArrayList<>();

    //11选5、北京赛车单式的号码筛选
    private List<String> baseFiltrate() {
        String input = mInput.replaceAll(" ", ",").replaceAll("[\r\n]", ",").replace("[", "").replace("]", "").replaceAll(" ", "").trim();
        List<String> baseFiltrate = new ArrayList<>(Arrays.asList(input.split(",")));
        List<String> listEle = new ArrayList<>();
        for (int i = 0; i < baseFiltrate.size(); i++) {
            if (!TextUtils.isEmpty(baseFiltrate.get(i))) {
                listEle.add(baseFiltrate.get(i));
            }
        }
        return listEle;
    }

    //11选5、北京赛车直选单式不符合规则的号码
    private String filtrate(int l) {
        endRepeat = new ArrayList<>();
        excess.clear();
        String filtrate;
        if (baseFiltrate().size() < l) {
            filtrate = baseFiltrate().toString().replace("[", "").replace("]", "").replaceAll(",", "").replaceAll(" ", "");
        } else {
            List<List<Integer>> mapExtra = new ArrayList<>();
            for (int i = 0; i < baseFiltrate().size() / l; i++) {
                List<Integer> split = new ArrayList<>();
                for (int j = 0; j < l; j++) {
                    split.add(Integer.valueOf(baseFiltrate().get(l * i + j)));
                }
                mapExtra.add(split);
            }
            List<Integer> repeat = new ArrayList<>();
            for (int i = 0; i < mapExtra.size(); i++) {
                LinkedHashSet<Integer> set = new LinkedHashSet<>(mapExtra.get(i));
                set.addAll(mapExtra.get(i));
                repeat.clear();
                repeat.addAll(set);
                if (repeat.size() != l) {
                    excess.addAll(mapExtra.get(i));
                    mapExtra.remove(i);
                    i = i - 1;
                } else {
                    switch (lotteryId) {
                        case 9:
                        case 14:
                        case 44://11选5
                            for (int k = 0; k < repeat.size(); k++) {
                                if (repeat.get(k) > 11 || repeat.get(k) < 1) {
                                    excess.addAll(repeat);
                                    mapExtra.remove(i);
                                    i = i - 1;
                                }
                            }
                            break;
                        case 10:
                        case 19:
                        case 49:
                        case 52:
                            for (int k = 0; k < repeat.size(); k++) {
                                if (repeat.get(k) > 10 || repeat.get(k) < 1) {
                                    excess.addAll(repeat);
                                    mapExtra.remove(i);
                                    i = i - 1;
                                }
                            }
                            break;
                    }
                }
            }
            LinkedHashSet<List<Integer>> setEnd = new LinkedHashSet<>(mapExtra);
            setEnd.addAll(mapExtra);
            endRepeat.clear();
            endRepeat.addAll(setEnd);
            if (getDif(mapExtra, endRepeat).size() > 0) {
                for (int i = 0; i < getDif(mapExtra, endRepeat).size(); i++) {
                    excess.addAll(getDif(mapExtra, endRepeat).get(i));
                }
            }
            for (int i = 0; i < baseFiltrate().size() % l; i++) {
                excess.add(Integer.valueOf(baseFiltrate().get(baseFiltrate().size() - baseFiltrate().size() % l - i)));
            }
            filtrate = excess.toString().replace("[", "").replace("]", "").replaceAll(",", "").replaceAll(" ", "");
        }
        return filtrate;
    }

    //组选以及任选单式
    private String comFiltrate(int l) {
        endCom = new ArrayList<>();
        excess.clear();
        String comFiltrate;
        filtrate(l);
        //大小排序后去掉相同数组
        for (int i = 0; i < endRepeat.size(); i++) {
            Collections.sort(endRepeat.get(i));
        }
        LinkedHashSet<List<Integer>> setEnd = new LinkedHashSet<>(endRepeat);
        setEnd.addAll(endRepeat);
        endCom.clear();
        endCom.addAll(setEnd);
        if (getDif(endRepeat, endCom).size() > 0) {
            for (int i = 0; i < getDif(endRepeat, endCom).size(); i++) {
                excess.addAll(getDif(endRepeat, endCom).get(i));
            }
        }
        comFiltrate = excess.toString().replace("[", "").replace("]", "").replaceAll(",", "").replaceAll(" ", "");
        return comFiltrate;
    }

    /**
     * 获取两个List的不同元素
     */
    private static List<List<Integer>> getDif(List<List<Integer>> listBig, List<List<Integer>> listSmall) {
        List<List<Integer>> diff = new ArrayList<>();
        //取出不同的数据
        for (List<Integer> key : listBig) {
            if (listSmall.contains(key)) {
                continue;
            }
            diff.add(key);
        }
        //取出重复的数据
        Map<List<Integer>, Integer> map = new HashMap<>();
        for (List<Integer> str : listBig) {
            int i = 0; //定义一个计数器，用来记录重复数据的个数
            if (map.get(str) != null) {
                i = Objects.requireNonNull(map.get(str)) + 1;
            }
            map.put(str, i);
        }
        for (List<Integer> s : map.keySet()) {
            if (Objects.requireNonNull(map.get(s)) > 0) {
                for (int i = 0; i < Objects.requireNonNull(map.get(s)); i++) {
                    diff.add(s);
                }
            }
        }
        return diff;
    }

    //返回11选5的组选以及任选模式下的输入注数
    static List<List<Integer>> comJoint() {
        return endCom;
    }

    //返回直选单式的输入注数
    static List<List<Integer>> endJoint() {
        return endRepeat;
    }

    //得到截取后多余的字符串
    private String interception(int l) {
        String lottery = "";
        if (!TextUtils.isEmpty(mInput)) {
            Matcher m = Pattern.compile("[^0-9]").matcher(mInput);
            String input = m.replaceAll("").trim();
            lottery = input.substring(input.length() - input.length() % l);
        }
        return lottery;
    }

    //计算重庆时时彩五星、四星、三星、二星单式的注数
    private List<String> zuD(int l) {
        List<String> lotteryList = new ArrayList<>();
        if (!TextUtils.isEmpty(mInput)) {
            Matcher m = Pattern.compile("[^0-9]").matcher(mInput);
            String input = m.replaceAll("").trim();
            String lottery = input.substring(0, input.length() - input.length() % l);
            if (!TextUtils.isEmpty(lottery)) {
                for (int i = 0; i < lottery.length() / l; i++) {
                    lotteryList.add(lottery.substring(i * l, (i + 1) * l));
                }
            }
        }
        return lotteryList;
    }

    //计算重庆时时彩五星、四星、三星、二星直选单式去重后的注数
    static List<String> zuDS(int l) {
        List<String> lotteryList = new ArrayList<>();
        if (!TextUtils.isEmpty(mInput)) {
            Matcher m = Pattern.compile("[^0-9]").matcher(mInput);
            String input = m.replaceAll("").trim();
            String lottery = input.substring(0, input.length() - input.length() % l);
            if (!TextUtils.isEmpty(lottery)) {
                for (int i = 0; i < lottery.length() / l; i++) {
                    lotteryList.add(lottery.substring(i * l, (i + 1) * l));
                }
            }
        }
        LinkedHashSet<String> set = new LinkedHashSet<>(lotteryList.size());
        set.addAll(lotteryList);
        lotteryList.clear();
        lotteryList.addAll(set);
        return lotteryList;
    }

    //三星混合组选的注数
    static List<String> sxHz() {
        List<String> list = zuDS(3);
        List<List<Integer>> dataArray = charList(list);
        return removeRepeat(dataArray);
    }

    //三星单式组选的重新排序
    private List<String> hhZ3(List<String> list) {
        List<List<Integer>> hhZ3List = charList(list);
        //每个数组重新大小排序
        for (int i = 0; i < hhZ3List.size(); i++) {
            Collections.sort(hhZ3List.get(i));
        }
        // 合并元素为字符串
        List<String> result = new ArrayList<>();
        for (int idx = 0; idx < hhZ3List.size(); idx++) {
            List<Integer> elem_array = hhZ3List.get(idx);
            String elem = arrayJoinToString(elem_array);
            result.add(elem);
        }
        return result;
    }

    //三星组三单式的注数
    static List<String> sxZ3() {
        List<String> list = zuDS(3);
        List<List<Integer>> dataArray = charList(list);
        // 去掉三个元素不相同的数据
        List<List<Integer>> dataRepeat = new ArrayList<>();
        for (int idx = 0; idx < dataArray.size(); idx++) {
            List<Integer> dataElem = dataArray.get(idx);
            int elem1 = dataElem.get(0);
            int elem2 = dataElem.get(1);
            int elem3 = dataElem.get(2);
            if (elem1 != elem2 && elem1 != elem3 && elem2 != elem3) {
                continue;
            }
            dataRepeat.add(dataElem);
        }
        return removeRepeat(dataRepeat);
    }

    //三星组六单式的注数
    static List<String> sxZ6() {
        List<String> list = zuDS(3);
        List<List<Integer>> dataArray = charList(list);
        // 去掉有两个元素相同的数据
        List<List<Integer>> dataRepeat = new ArrayList<>();
        for (int idx = 0; idx < dataArray.size(); idx++) {
            List<Integer> dataElem = dataArray.get(idx);
            int elem1 = dataElem.get(0);
            int elem2 = dataElem.get(1);
            int elem3 = dataElem.get(2);
            if (elem1 == elem2 || elem1 == elem3 || elem2 == elem3) {
                continue;
            }
            dataRepeat.add(dataElem);
        }
        return removeRepeat(dataRepeat);
    }

    /*
     *列表数据去重
     */
    private static List<String> removeRepeat(List<List<Integer>> dataArray) {
        //大小排序后去掉相同数组
        for (int i = 0; i < dataArray.size(); i++) {
            Collections.sort(dataArray.get(i));
        }
        LinkedHashSet<List<Integer>> set = new LinkedHashSet<>(dataArray.size());
        set.addAll(dataArray);
        dataArray.clear();
        dataArray.addAll(set);
        // 合并元素为字符串
        List<String> result = new ArrayList<>();
        for (int idx = 0; idx < dataArray.size(); idx++) {
            List<Integer> elem_array = dataArray.get(idx);
            String elem = arrayJoinToString(elem_array);
            result.add(elem);
        }
        // 去掉相同数字元素的数据
        String[] same3 = {"000", "111", "222", "333", "444", "555", "666", "777", "888", "999"};
        for (int j = 0; j < 10; j++) {
            result.remove(same3[j]);
        }
        return result;
    }

    //二星组选单式的注数
    static List<String> sxZ2() {
        List<String> list = zuDS(2);
        List<List<Integer>> dataArray = charList(list);
        // 去掉三个元素不相同的数据
        List<List<Integer>> dataRepeat = new ArrayList<>();
        for (int idx = 0; idx < dataArray.size(); idx++) {
            List<Integer> dataElem = dataArray.get(idx);
            int elem1 = dataElem.get(0);
            int elem2 = dataElem.get(1);
            if (elem1 == elem2) {
                continue;
            }
            dataRepeat.add(dataElem);
        }
        return removeRepeat(dataRepeat);
    }

    /**
     * 获取两个List的不同元素
     */
    private static List<String> getDifferent(List<String> listBig, List<String> listSmall) {
        List<String> diff = new ArrayList<>();
        //取出不同的数据
        for (String key : listBig) {
            if (listSmall.contains(key)) {
                continue;
            }
            diff.add(key);
        }
        //取出重复的数据
        Map<String, Integer> map = new HashMap<>();
        for (String str : listBig) {
            int i = 0; //定义一个计数器，用来记录重复数据的个数
            if (map.get(str) != null) {
                i = Objects.requireNonNull(map.get(str)) + 1;
            }
            map.put(str, i);
        }
        for (String s : map.keySet()) {
            if (Objects.requireNonNull(map.get(s)) > 0) {
                for (int i = 0; i < Objects.requireNonNull(map.get(s)); i++) {
                    diff.add(s);
                }
            }
        }
        return diff;
    }

    /*
     *将列表数据拆分,分割每一投注元素成数组
     */
    private static List<List<Integer>> charList(List<String> list) {
        List<List<Integer>> dataArray = new ArrayList<>();
        for (int idx = 0; idx < list.size(); idx++) {
            String data_elem = list.get(idx);
            ArrayList<Integer> data_array = new ArrayList<>();
            for (int n = 0; n < data_elem.length(); n++) {
                int charAt = Integer.valueOf(data_elem.substring(n, n + 1));
                data_array.add(charAt);
            }
            dataArray.add(data_array);
        }
        return dataArray;
    }

    /*
     * 合并元素为字符串
     */
    private static String arrayJoinToString(List<Integer> strAry) {
        StringBuilder sb = new StringBuilder();
        for (int i = 0, len = strAry.size(); i < len; i++) {
            String elem = String.valueOf(strAry.get(i));
            if (i == (len - 1)) {
                sb.append(elem);
            } else {
                sb.append(elem).append("");
            }
        }
        return sb.toString();
    }

    private int z2() {
        int N = 0;
        if (mUpdateBet.get(1).getSelectList().size() > 1) {
            for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                if (mUpdateBet.get(1).getSelectList().contains(mUpdateBet.get(0).getSelectList().get(i))) {
                    N += combination(mUpdateBet.get(1).getSelectList().size() - 1, 2);
                } else {
                    N += combination(mUpdateBet.get(1).getSelectList().size(), 2);
                }
            }
        }
        return N;
    }

    private int z4() {
        int N = 0;
        for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
            for (int j = 0; j < mUpdateBet.get(1).getSelectList().size(); j++) {
                if (!mUpdateBet.get(0).getSelectList().get(i).equals(mUpdateBet.get(1).getSelectList().get(j))) {
                    N++;
                }
            }
        }
        return N;
    }

    private int sum3dzhix(int sum) {
        int N = 0;
        for (int i = 0; i < 10; i++) {
            for (int j = 0; j < 10; j++) {
                for (int k = 0; k < 10; k++) {
                    if (i + j + k == sum) {
                        N++;
                    }
                }
            }
        }
        return N;
    }

    private int sum3dzux(int sum) {
        Integer[] num = {1, 2, 2, 4, 5, 6, 8, 10, 11, 13, 14, 14, 15, 15, 14, 14, 13, 11, 10, 8, 6, 5, 4, 2, 2, 1};
        return num[sum];
    }

    private int sum2dzhix(int sum) {
        int N = 0;
        for (int i = 0; i < 10; i++) {
            for (int j = 0; j < 10; j++) {
                if (i + j == sum) {
                    N++;
                }
            }
        }
        return N;
    }

    private int sum2dzux(int sum) {
        Integer[] num = {1, 1, 2, 2, 3, 3, 4, 4, 5, 4, 4, 3, 3, 2, 2, 1, 1};
        return num[sum];
    }

    private int sub3dzx(int sub) {
        int N = 0;
        for (int i = 0; i < 10; i++) {
            for (int j = 0; j < 10; j++) {
                for (int k = 0; k < 10; k++) {
                    Integer[] min = {i, j, k};
                    if (Collections.max(Arrays.asList(min)) - Collections.min(Arrays.asList(min)) == sub) {
                        N++;
                    }
                }
            }
        }
        return N;
    }

    private int sub2dzx(int sub) {
        int N = 0;
        for (int i = 0; i < 10; i++) {
            for (int j = 0; j < 10; j++) {
                Integer[] min = {i, j};
                if (Collections.max(Arrays.asList(min)) - Collections.min(Arrays.asList(min)) == sub) {
                    N++;
                }
            }
        }
        return N;
    }

    //pk10三星/11选5前三
    private int pk10sx() {
        int N = 0;
        for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
            for (int j = 0; j < mUpdateBet.get(1).getSelectList().size(); j++) {
                for (int k = 0; k < mUpdateBet.get(2).getSelectList().size(); k++) {
                    if (!mUpdateBet.get(0).getSelectList().get(i).equals(mUpdateBet.get(1).getSelectList().get(j))
                            && !mUpdateBet.get(0).getSelectList().get(i).equals(mUpdateBet.get(2).getSelectList().get(k))
                            && !mUpdateBet.get(1).getSelectList().get(j).equals(mUpdateBet.get(2).getSelectList().get(k))) {
                        N++;
                    }
                }
            }
        }
        return N;
    }

    /**
     * 计算阶乘数，即n! = n * (n-1) * ... * 2 * 1
     */
    private double factorial(int n) {
        return (n > 1) ? n * factorial(n - 1) : 1;
    }

    /**
     * 计算排列数，即A(n, m) = n!/(n-m)!
     */
    private double arrangement(int n) {
        return (n >= 2) ? factorial(n) / factorial(n - 2) : 0;
    }

    /**
     * 计算组合数，即C(n, m) = n!/((n-m)! * m!)
     */
    private double combination(int n, int m) {
        return (n >= m) ? factorial(n) / factorial(n - m) / factorial(m) : 0;
    }
}