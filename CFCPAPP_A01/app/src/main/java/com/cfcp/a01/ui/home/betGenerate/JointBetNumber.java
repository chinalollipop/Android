package com.cfcp.a01.ui.home.betGenerate;

import com.cfcp.a01.data.BetGameSettingsForRefreshResult;
import com.cfcp.a01.data.UpBetData;

import java.util.ArrayList;
import java.util.List;

import static com.cfcp.a01.ui.home.betGenerate.GenerateMoney.exZ2;
import static com.cfcp.a01.ui.home.betGenerate.GenerateMoney.sxHz;
import static com.cfcp.a01.ui.home.betGenerate.GenerateMoney.sxZ3;
import static com.cfcp.a01.ui.home.betGenerate.GenerateMoney.sxZ6;
import static com.cfcp.a01.ui.home.betGenerate.GenerateMoney.zuDS;

/**
 * 投注时所选号码的拼接
 */
public class JointBetNumber {
    private static String balls;
    private static StringBuilder sb = new StringBuilder();

    //拼接号码
    public static String jointNum(List<UpBetData> mUpdateBet, Integer lottery_id, List<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean> wayGroups, String[] position) {
        int mBetMethodContentID = wayGroups.get(Integer.valueOf(position[0])).getId();
        int mBetMethodDetailsID = wayGroups.get(Integer.valueOf(position[0])).getChildren().get(Integer.valueOf(position[1])).getChildren().get(Integer.valueOf(position[2])).getId();
        switch (lottery_id) {
            //时时彩类
            case 1:
            case 13:
            case 16:
            case 28:
                List<Integer> listQC = new ArrayList<>();
                switch (mBetMethodContentID) {
                    case 1:
                    case 2:
                    case 3:
                    case 8:
                    case 15:
                    case 26:
                    case 61:
                    case 97:
                        switch (mBetMethodDetailsID) {
                            case 7://五星直选单式
                                balls = zuDS(5).toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 6:
                            case 351://四星直选单式
                                balls = zuDS(4).toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 1:
                            case 8:
                            case 142://三星直选单式
                                balls = zuDS(3).toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 13:
                            case 81:
                            case 152://三星混合组选
                                balls = sxHz().toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 2:
                            case 9:
                            case 143://三星组三单式
                                balls = sxZ3().toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 3:
                            case 10:
                            case 144://三星组六单式
                                balls = sxZ6().toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 71:
                            case 73:
                            case 151://三星直选和值
                                balls = mUpdateBet.get(0).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 75:
                            case 80:
                            case 154://三星组选和值
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    listQC.add(mUpdateBet.get(0).getSelectList().get(i) + 1);
                                }
                                balls = listQC.toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 4:
                            case 11://二星直选单式
                                balls = zuDS(2).toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 5:
                            case 12://二星单式组选
                                balls = exZ2().toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 72:
                            case 74://二星直选和值
                                balls = mUpdateBet.get(0).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 76:
                            case 77://二星组选和值
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    listQC.add(mUpdateBet.get(0).getSelectList().get(i) + 1);
                                }
                                balls = listQC.toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 38://五码趣味三星
                                sb.setLength(0);
                                for (int i = 0; i < mUpdateBet.size(); i++) {
                                    if (i == 0 || i == 1) {
                                        sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")
                                                .replaceAll("0", "小").replaceAll("1", "大")).append("|");
                                    } else {
                                        sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")).append("|");
                                    }
                                }
                                if (sb.length() != 0) {
                                    balls = sb.toString().substring(0, sb.toString().length() - 1);
                                }
                                break;
                            case 39:
                            case 40:
                            case 55://趣味玩法
                                sb.setLength(0);
                                for (int i = 0; i < mUpdateBet.size(); i++) {
                                    if (i == 0) {
                                        sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")
                                                .replaceAll("0", "小").replaceAll("1", "大")).append("|");
                                    } else {
                                        sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")).append("|");
                                    }
                                }
                                if (sb.length() != 0) {
                                    balls = sb.toString().substring(0, sb.toString().length() - 1);
                                }
                                break;
                            case 41://五码区间三星
                                sb.setLength(0);
                                for (int i = 0; i < mUpdateBet.size(); i++) {
                                    if (i == 0 || i == 1) {
                                        sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")
                                                .replaceAll("0", "一区").replaceAll("1", "二区").replaceAll("2", "三区").replaceAll("3", "四区").replaceAll("4", "五区")).append("|");
                                    } else {
                                        sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")).append("|");
                                    }
                                }
                                if (sb.length() != 0) {
                                    balls = sb.toString().substring(0, sb.toString().length() - 1);
                                }
                                break;
                            case 42:
                            case 43:
                            case 56://区间玩法
                                sb.setLength(0);
                                for (int i = 0; i < mUpdateBet.size(); i++) {
                                    if (i == 0) {
                                        sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")
                                                .replaceAll("0", "一区").replaceAll("1", "二区").replaceAll("2", "三区").replaceAll("3", "四区").replaceAll("4", "五区")).append("|");
                                    } else {
                                        sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")).append("|");
                                    }
                                }
                                if (sb.length() != 0) {
                                    balls = sb.toString().substring(0, sb.toString().length() - 1);
                                }
                                break;
                            default:
                                sb.setLength(0);
                                for (int i = 0; i < mUpdateBet.size(); i++) {
                                    if (mUpdateBet.get(i).getSelectList().size() != 0) {
                                        sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")).append("|");
                                    }
                                }
                                if (sb.length() != 0) {
                                    balls = sb.toString().substring(0, sb.toString().length() - 1);
                                }
                                break;
                        }
                        break;
                    case 24://大小单双
                        sb.setLength(0);
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            if (mUpdateBet.get(i).getSelectList().size() != 0) {
                                sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")
                                        .replaceAll("0", "大").replaceAll("1", "小").replaceAll("2", "单").replaceAll("3", "双")).append("|");
                            }
                        }
                        if (sb.length() != 0) {
                            balls = sb.toString().substring(0, sb.toString().length() - 1);
                        }
                        break;
                    case 93://任选
                        sb.setLength(0);
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")).append("|");
                        }
                        if (sb.length() != 0) {
                            balls = sb.toString().substring(0, sb.toString().length() - 1);
                        }
                        break;
                    case 100://龙虎和
                        balls = mUpdateBet.get(0).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")
                                .replaceAll("0", "龙").replaceAll("1", "虎").replaceAll("2", "和");
                        break;
                    default://其他通用
                        sb.setLength(0);
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            if (mUpdateBet.get(i).getSelectList().size() != 0) {
                                sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")).append("|");
                            }
                        }
                        if (sb.length() != 0) {
                            balls = sb.toString().substring(0, sb.toString().length() - 1);
                        }
                        break;
                }
                break;
            //11选5类
            case 9:
            case 14:
            case 44:
                switch (mBetMethodContentID) {
                    case 30:
                    case 31:
                    case 32:
                    case 33:
                    case 34:
                    case 35://-------任选单式
                    case 36:
                    case 42:
                        switch (mBetMethodDetailsID) {
                            case 109:
                                balls = mUpdateBet.get(0).getSelectList().toString().replaceAll(",", "").replace("[", "").replace("]", "")
                                        .replace("0", "5单0双").replace("1", "4单1双").replace("2", "3单2双")
                                        .replace("3", "2单3双").replace("4", "1单4双").replace("5", "0单5双");
                                break;
                            case 110:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    if (i == mUpdateBet.get(0).getSelectList().size() - 1) {
                                        sb.append(mUpdateBet.get(0).getSelectList().get(i) + 3);
                                    } else {
                                        sb.append(mUpdateBet.get(0).getSelectList().get(i) + 3).append(" ");
                                    }
                                }
                                balls = sb.toString();
                                break;
                            default:
                                sb.setLength(0);
                                List<String> betNum = new ArrayList<>();
                                StringBuilder sbBetNum = new StringBuilder();
                                for (int i = 0; i < mUpdateBet.size(); i++) {
                                    if (mUpdateBet.get(i).getSelectList().size() == 0) {
                                        betNum.add("");
                                    } else {
                                        for (int j = 0; j < mUpdateBet.get(i).getSelectList().size(); j++) {
                                            if (j == mUpdateBet.get(i).getSelectList().size() - 1) {
                                                sbBetNum.append(mUpdateBet.get(i).getSelectList().get(j) + 1);
                                            } else {
                                                sbBetNum.append(mUpdateBet.get(i).getSelectList().get(j) + 1).append(" ");
                                            }
                                        }
                                        betNum.add(sbBetNum.toString());
                                        sbBetNum.setLength(0);
                                    }
                                }
                                for (int i = 0; i < betNum.size(); i++) {
                                    sb.append(betNum.get(i)).append("|");
                                }
                                if (sb.length() != 0) {
                                    balls = sb.toString().substring(0, sb.toString().length() - 1);
                                }
                                break;
                        }
                        break;
                }
                break;
            //PK10类
            case 10:
            case 19:
            case 49:
                List<Integer> listPK10 = new ArrayList<>();
                switch (mBetMethodContentID) {
                    case 89:
                    case 114:
                    case 117:
                        sb.setLength(0);
                        List<String> betNum = new ArrayList<>();
                        StringBuilder sbBetNum = new StringBuilder();
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            if (mUpdateBet.get(i).getSelectList().size() == 0) {
                                betNum.add("");
                            } else {
                                for (int j = 0; j < mUpdateBet.get(i).getSelectList().size(); j++) {
                                    if (j == mUpdateBet.get(i).getSelectList().size() - 1) {
                                        sbBetNum.append(mUpdateBet.get(i).getSelectList().get(j) + 1);
                                    } else {
                                        sbBetNum.append(mUpdateBet.get(i).getSelectList().get(j) + 1).append(" ");
                                    }
                                }
                                betNum.add(sbBetNum.toString());
                                sbBetNum.setLength(0);
                            }
                        }
                        for (int i = 0; i < betNum.size(); i++) {
                            sb.append(betNum.get(i)).append("|");
                        }
                        if (sb.length() != 0) {
                            balls = sb.toString().substring(0, sb.toString().length() - 1);
                        }
                        break;
                    case 87:
                        switch (mBetMethodDetailsID) {
                            case 175:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    listPK10.add(mUpdateBet.get(0).getSelectList().get(i) + 3);
                                }
                                balls = listPK10.toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 176:
                                sb.setLength(0);
                                for (int i = 0; i < mUpdateBet.size(); i++) {
                                    sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")
                                            .replaceAll("0", "大").replaceAll("1", "小").replaceAll("2", "单").replaceAll("3", "双")).append("|");
                                }
                                if (sb.length() != 0) {
                                    balls = sb.toString().substring(0, sb.toString().length() - 1);
                                }
                                break;
                        }
                        break;
                    case 91:
                        sb.setLength(0);
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "")
                                    .replaceAll("0", "龙").replaceAll("1", "虎").replace("[", "").replace("]", "")).append("|");
                        }
                        if (sb.length() != 0) {
                            balls = sb.toString().substring(0, sb.toString().length() - 1);
                        }
                        break;
                }
                break;
            //快三类
            case 15:
            case 17:
                List<Integer> listKS = new ArrayList<>();
                switch (mBetMethodContentID) {
                    case 65:
                        for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                            listKS.add(mUpdateBet.get(0).getSelectList().get(i) - 1);
                        }
                        for (int j = 0; j < listKS.size(); j++) {
                            if (listKS.get(j) == 0) {
                                listKS.set(j, -2);
                            }
                            if (listKS.get(j) == 1) {
                                listKS.set(j, -3);
                            }
                            if (listKS.get(j) == 2) {
                                listKS.set(j, -4);
                            }
                        }
                        balls = listKS.toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "")
                                .replace("-1", "大").replace("-2", "小").replace("-3", "单").replace("-4", "双");
                        break;
                    case 66:
                    case 68:
                    case 70:
                    case 71:
                    case 72:
                        switch (mBetMethodDetailsID) {
                            case 158:
                            case 161:
                            case 162:
                            case 163:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    listKS.add(mUpdateBet.get(0).getSelectList().get(i) + 1);
                                }
                                balls = listKS.toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 159:
                            case 164:
                                balls = mUpdateBet.get(0).getSelectList().toString().replaceAll(" ", "").replaceAll("0", "通选").replace("[", "").replace("]", "");
                                break;
                            case 160:
                                sb.setLength(0);
                                List<String> betNum = new ArrayList<>();
                                StringBuilder sbBetNum = new StringBuilder();
                                for (int i = 0; i < mUpdateBet.size(); i++) {
                                    for (int j = 0; j < mUpdateBet.get(i).getSelectList().size(); j++) {
                                        sbBetNum.append(mUpdateBet.get(i).getSelectList().get(j) + 1);
                                    }
                                    betNum.add(sbBetNum.toString());
                                    sbBetNum.setLength(0);
                                }
                                sb.append(betNum.get(0)).append("|").append(betNum.get(1));
                                balls = sb.toString();
                                break;
                        }
                        break;
                    case 73:
                        balls = mUpdateBet.get(0).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")
                                .replace("0", "大").replace("1", "小");
                        break;
                    case 74:
                        balls = mUpdateBet.get(0).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")
                                .replace("0", "单").replace("1", "双");
                        break;
                    case 85:
                        for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                            listKS.add(mUpdateBet.get(0).getSelectList().get(i) + 1);
                        }
                        balls = listKS.toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                        break;
                    case 110:
                        switch (mBetMethodDetailsID) {
                            case 378:
                                balls = mUpdateBet.get(0).getSelectList().toString().replace("[", "").replace("]", "").replace("0", "全红");
                                break;
                            case 379:
                                balls = mUpdateBet.get(0).getSelectList().toString().replace("[", "").replace("]", "").replace("0", "全黑");
                                break;
                        }
                        break;
                }
                break;
            //3D
            case 20:
                List<Integer> list3D = new ArrayList<>();
                switch (mBetMethodContentID) {
                    case 48:
                    case 49:
                    case 50:
                    case 51:
                        switch (mBetMethodDetailsID) {
                            case 123:
                                balls = zuDS(3).toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 124:
                                balls = sxZ3().toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 125:
                                balls = sxZ6().toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 126:
                            case 128:
                                balls = zuDS(2).toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 127:
                            case 129:
                                balls = exZ2().toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 130:
                                balls = sxHz().toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 133:
                            case 134:
                            case 135:
                            case 136:
                            case 137:
                            case 138:
                            case 141:
                            case 485:
                                sb.setLength(0);
                                for (int i = 0; i < mUpdateBet.size(); i++) {
                                    if (mUpdateBet.get(i).getSelectList().size() != 0) {
                                        sb.append(mUpdateBet.get(i).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")).append("|");
                                    }
                                }
                                if (sb.length() != 0) {
                                    balls = sb.toString().substring(0, sb.toString().length() - 1);
                                }
                                break;
                            case 139:
                                balls = mUpdateBet.get(0).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                            case 131:
                            case 132:
                                balls = mUpdateBet.get(0).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "");
                                break;
                            case 140:
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    list3D.add(mUpdateBet.get(0).getSelectList().get(i) + 1);
                                }
                                balls = list3D.toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
                                break;
                        }
                        break;
                    case 104:
                        balls = mUpdateBet.get(0).getSelectList().toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "")
                                .replaceAll("0", "龙").replaceAll("1", "虎").replaceAll("2", "和");
                        break;
                }
                break;
            //快乐8
            case 37:

                break;
        }
        return balls;
    }
}
