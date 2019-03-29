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
                    case 18://定位胆
                        sb.setLength(0);
                        List<String> betNum = new ArrayList<>();
                        StringBuilder sbBetNum = new StringBuilder();
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            if (mUpdateBet.get(i).getSelectList().size() == 0) {
                                betNum.add("");
                            } else {
                                for (int j = 0; j < mUpdateBet.get(i).getSelectList().size(); j++) {
                                    sbBetNum.append(mUpdateBet.get(i).getSelectList().get(j));
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
                    case 24://大小单双
                        sb.setLength(0);
                        List<String> betNumQ = new ArrayList<>();
                        StringBuilder sbBetNumQ = new StringBuilder();
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            if (mUpdateBet.get(i).getSelectList().size() == 0) {
                                betNumQ.add("");
                            } else {
                                for (int j = 0; j < mUpdateBet.get(i).getSelectList().size(); j++) {
                                    if (mUpdateBet.get(i).getSelectList().get(j) == 0) {
                                        sbBetNumQ.append(1);
                                    }
                                    if (mUpdateBet.get(i).getSelectList().get(j) == 1) {
                                        sbBetNumQ.append(0);
                                    }
                                    if (mUpdateBet.get(i).getSelectList().get(j) == 2) {
                                        sbBetNumQ.append(3);
                                    }
                                    if (mUpdateBet.get(i).getSelectList().get(j) == 3) {
                                        sbBetNumQ.append(2);
                                    }
                                }
                                betNumQ.add(sbBetNumQ.toString());
                                sbBetNumQ.setLength(0);
                            }
                        }
                        for (int i = 0; i < betNumQ.size(); i++) {
                            sb.append(betNumQ.get(i)).append("|");
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
                        for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                            if (mUpdateBet.get(0).getSelectList().get(i) == 0) {
                                listQC.add(2);
                            }
                            if (mUpdateBet.get(0).getSelectList().get(i) == 1) {
                                listQC.add(0);
                            }
                            if (mUpdateBet.get(0).getSelectList().get(i) == 2) {
                                listQC.add(1);
                            }
                        }
                        balls = listQC.toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "");
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
                                List<Integer> list11X5 = new ArrayList<>();
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    list11X5.add(mUpdateBet.get(0).getSelectList().get(i) - 5);
                                }
                                balls = list11X5.toString().replaceAll("-", "").replaceAll(",", "").replace("[", "").replace("]", "");
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
                                for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                                    if (mUpdateBet.get(0).getSelectList().get(i) == 0) {
                                        listPK10.add(1);
                                    }
                                    if (mUpdateBet.get(0).getSelectList().get(i) == 1) {
                                        listPK10.add(0);
                                    }
                                    if (mUpdateBet.get(0).getSelectList().get(i) == 2) {
                                        listPK10.add(3);
                                    }
                                    if (mUpdateBet.get(0).getSelectList().get(i) == 3) {
                                        listPK10.add(2);
                                    }
                                }
                                balls = listPK10.toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "");
                                break;
                        }
                        break;
                    case 91:
                        sb.setLength(0);
                        List<String> betNumPk10 = new ArrayList<>();
                        StringBuilder sbBetNumPk10 = new StringBuilder();
                        for (int i = 0; i < mUpdateBet.size(); i++) {
                            if (mUpdateBet.get(i).getSelectList().size() == 0) {
                                betNumPk10.add("");
                            } else {
                                for (int j = 0; j < mUpdateBet.get(i).getSelectList().size(); j++) {
                                    if (mUpdateBet.get(i).getSelectList().get(j) == 0) {
                                        sbBetNumPk10.append(1);
                                    } else {
                                        sbBetNumPk10.append(0);
                                    }
                                }
                                betNumPk10.add(sbBetNumPk10.toString());
                                sbBetNumPk10.setLength(0);
                            }
                        }
                        for (int i = 0; i < betNumPk10.size(); i++) {
                            sb.append(betNumPk10.get(i)).append("|");
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
                                balls = mUpdateBet.get(0).getSelectList().toString().replaceAll(" ", "").replaceAll("0", "1").replace("[", "").replace("]", "");
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
                    case 74:
                        for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                            listKS.add(mUpdateBet.get(0).getSelectList().get(i) - 1);
                        }
                        balls = listKS.toString().replace("-", "").replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "");
                        break;
                    case 85:
                    case 110:
                        for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                            listKS.add(mUpdateBet.get(0).getSelectList().get(i) + 1);
                        }
                        balls = listKS.toString().replaceAll(" ", "").replaceAll(",", "|").replace("[", "").replace("]", "");
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
                        for (int i = 0; i < mUpdateBet.get(0).getSelectList().size(); i++) {
                            if (mUpdateBet.get(0).getSelectList().get(i) == 0) {
                                list3D.add(2);
                            }
                            if (mUpdateBet.get(0).getSelectList().get(i) == 1) {
                                list3D.add(0);
                            }
                            if (mUpdateBet.get(0).getSelectList().get(i) == 2) {
                                list3D.add(1);
                            }
                        }
                        balls = list3D.toString().replaceAll(" ", "").replaceAll(",", "").replace("[", "").replace("]", "");
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
