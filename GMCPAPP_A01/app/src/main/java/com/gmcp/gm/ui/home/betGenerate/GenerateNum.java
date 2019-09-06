package com.gmcp.gm.ui.home.betGenerate;

import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.LinearLayout;

import com.gmcp.gm.R;
import com.gmcp.gm.common.adapters.LotteryAdapter;
import com.gmcp.gm.data.BetGameSettingsForRefreshResult;

import java.util.ArrayList;
import java.util.List;

/**
 * 数字区域号码本地生成
 */
public class GenerateNum {

    //初始化选择号码区域
    public static void generateNum(LotteryAdapter lotteryAdapter, Integer lottery_id) {
        lotteryAdapter.setPlacesShow(true);
        lotteryAdapter.setSingleSelection(false);
        lotteryAdapter.removeAllFooterView();
        lotteryAdapter.setKs(0);
        lotteryAdapter.setEleven(0);
        switch (lottery_id) {
            //时时彩类
            case 1:
            case 13:
            case 16:
            case 28:
            case 53:
                lotteryAdapter.setNewData(CqsscGenerateNum.allNum());
                break;
            //11选5类
            case 9:
            case 14:
            case 44:
                lotteryAdapter.setNewData(ElevenGenerateNum.allNum());
                break;
            //PK10类
            case 10:
            case 19:
            case 49:
            case 52:
                lotteryAdapter.setNewData(Pk10GenerateNum.allNum());
                break;
            //快三类
            case 15:
            case 17:
            case 50:
            case 51:
                lotteryAdapter.setNewData(KsGenerateNum.sumKS());
                lotteryAdapter.setKs(lottery_id);
                lotteryAdapter.setPlacesShow(false);
                break;
            //3D
            case 20:
                lotteryAdapter.setNewData(ThreeDGenerateNum.allNum());
                break;
            //快乐8
            case 37:
                lotteryAdapter.setNewData(Happy8GenerateNum.ds());
                break;
        }
    }

    //更改选择号码区域
    public static void generateNum(int lottery_id, LotteryAdapter lotteryAdapter, List<BetGameSettingsForRefreshResult.DataBean.WayGroupsBean> wayGroups, String[] position, View view, RecyclerView rvLottery, LinearLayout llLotteryInput) {
        int mBetMethodContentID = wayGroups.get(Integer.valueOf(position[0])).getId();
        int mBetMethodDetailsID = wayGroups.get(Integer.valueOf(position[0])).getChildren().get(Integer.valueOf(position[1])).getChildren().get(Integer.valueOf(position[2])).getId();
        lotteryAdapter.setPlacesShow(true);
        lotteryAdapter.setSingleSelection(false);
        rvLottery.setVisibility(View.VISIBLE);
        llLotteryInput.setVisibility(View.GONE);
        llLotteryInput.findViewById(R.id.rv_top).setVisibility(View.GONE);
        lotteryAdapter.removeAllFooterView();
        lotteryAdapter.setKs(0);
        lotteryAdapter.setEleven(0);
        lotteryAdapter.setHappy8(0);
        switch (lottery_id) {
            case 1://时时彩
            case 13:
            case 16:
            case 28:
            case 53:
                List<Integer> listSec = new ArrayList<>();
                switch (mBetMethodContentID) {
                    case 2:
                        switch (mBetMethodDetailsID) {
                            case 68:
                                lotteryAdapter.setNewData(CqsscGenerateNum.allNum());
                                break;
                            case 7:
                                rvLottery.setVisibility(View.GONE);
                                llLotteryInput.setVisibility(View.VISIBLE);
                                break;
                            case 32:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z120());
                                break;
                            case 31:
                            case 30:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z60());
                                break;
                            case 29:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z20());
                                break;
                            case 28:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z10());
                                break;
                            case 27:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z5());
                                break;
                        }
                        break;
                    case 1:
                    case 8:
                    case 61:
                        switch (mBetMethodDetailsID) {
                            case 65:
                                lotteryAdapter.setNewData(CqsscGenerateNum.frontS());
                                break;
                            case 150:
                                lotteryAdapter.setNewData(CqsscGenerateNum.middleS());
                                break;
                            case 69:
                                lotteryAdapter.setNewData(CqsscGenerateNum.behindS());
                                break;
                            case 71:
                            case 73:
                            case 151:
                                lotteryAdapter.setNewData(CqsscGenerateNum.sumS());
                                break;
                            case 1:
                            case 2:
                            case 3:
                            case 8:
                            case 9:
                            case 10:
                            case 13:
                            case 81:
                            case 142:
                            case 143:
                            case 144:
                            case 152:
                                rvLottery.setVisibility(View.GONE);
                                llLotteryInput.setVisibility(View.VISIBLE);
                                break;
                            case 60:
                            case 62:
                            case 149:
                                lotteryAdapter.setNewData(CqsscGenerateNum.spanS());
                                break;
                            case 16:
                            case 49:
                            case 145:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z3S());
                                break;
                            case 17:
                            case 50:
                            case 146:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z6S());
                                break;
                            case 75:
                            case 80:
                            case 154:
                                lotteryAdapter.setNewData(CqsscGenerateNum.sumZS());
                                break;
                            case 64:
                            case 83:
                            case 153:
                                lotteryAdapter.setNewData(CqsscGenerateNum.bdS());
                                lotteryAdapter.setPlacesShow(false);
                                lotteryAdapter.setSingleSelection(true);
                                break;
                            case 33:
                            case 54:
                            case 156:
                                lotteryAdapter.setNewData(CqsscGenerateNum.mantissaS());
                                break;
                            case 48:
                            case 57:
                            case 155:
                                lotteryAdapter.setNewData(CqsscGenerateNum.specialS());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 388:
                            case 390:
                            case 393:
                                lotteryAdapter.setNewData(CqsscGenerateNum.bzS());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 387:
                            case 389:
                            case 392:
                                lotteryAdapter.setNewData(CqsscGenerateNum.szS());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 385:
                            case 386:
                            case 391:
                                lotteryAdapter.setNewData(CqsscGenerateNum.dzS());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                        }
                        break;
                    case 3:
                    case 97:
                        switch (mBetMethodDetailsID) {
                            case 295:
                                lotteryAdapter.setNewData(CqsscGenerateNum.frontF());
                                break;
                            case 67:
                                lotteryAdapter.setNewData(CqsscGenerateNum.behindF());
                                break;
                            case 6:
                            case 351:
                                rvLottery.setVisibility(View.GONE);
                                llLotteryInput.setVisibility(View.VISIBLE);
                                break;
                            case 26:
                            case 242:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z24());
                                break;
                            case 25:
                            case 329:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z60());
                                break;
                            case 24:
                            case 243:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z6());
                                break;
                            case 23:
                            case 330:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z20());
                                break;
                        }
                        break;
                    case 15:
                        switch (mBetMethodDetailsID) {
                            case 70:
                                lotteryAdapter.setNewData(CqsscGenerateNum.behindSe());
                                break;
                            case 20:
                            case 59:
                                lotteryAdapter.setNewData(CqsscGenerateNum.zS());
                                break;
                            case 66:
                                lotteryAdapter.setNewData(CqsscGenerateNum.frontSe());
                                break;
                            case 4:
                            case 5:
                            case 11:
                            case 12:
                                rvLottery.setVisibility(View.GONE);
                                llLotteryInput.setVisibility(View.VISIBLE);
                                break;
                            case 72:
                            case 74:
                                lotteryAdapter.setNewData(CqsscGenerateNum.sumZSe());
                                break;
                            case 77:
                            case 76:
                                lotteryAdapter.setNewData(CqsscGenerateNum.sumZuSe());
                                break;
                            case 63:
                                lotteryAdapter.setNewData(CqsscGenerateNum.spanBSe());
                                break;
                            case 61:
                                lotteryAdapter.setNewData(CqsscGenerateNum.spanFSe());
                                break;
                            case 84:
                            case 85:
                                lotteryAdapter.setNewData(CqsscGenerateNum.bdS());
                                lotteryAdapter.setPlacesShow(false);
                                lotteryAdapter.setSingleSelection(true);
                                break;
                        }
                        break;
                    case 18:
                        lotteryAdapter.setNewData(CqsscGenerateNum.allNum());
                        break;
                    case 20:
                        lotteryAdapter.setNewData(CqsscGenerateNum.bdw());
                        break;
                    case 24:
                        switch (mBetMethodDetailsID) {
                            case 58:
                                lotteryAdapter.setNewData(CqsscGenerateNum.h2bsds());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 53:
                                lotteryAdapter.setNewData(CqsscGenerateNum.h3bsds());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 19:
                                lotteryAdapter.setNewData(CqsscGenerateNum.q2bsds());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 22:
                                lotteryAdapter.setNewData(CqsscGenerateNum.q3bsds());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                        }
                        break;
                    case 26:
                        switch (mBetMethodDetailsID) {
                            case 38:
                                lotteryAdapter.setNewData(CqsscGenerateNum.wmqwsx());
                                break;
                            case 39:
                                lotteryAdapter.setNewData(CqsscGenerateNum.smqwsx());
                                break;
                            case 55:
                                lotteryAdapter.setNewData(CqsscGenerateNum.h3qwex());
                                break;
                            case 40:
                                lotteryAdapter.setNewData(CqsscGenerateNum.q3qwex());
                                break;
                            case 41:
                                lotteryAdapter.setNewData(CqsscGenerateNum.wmqjsx());
                                break;
                            case 42:
                                lotteryAdapter.setNewData(CqsscGenerateNum.smqjsx());
                                break;
                            case 56:
                                lotteryAdapter.setNewData(CqsscGenerateNum.h3qjex());
                                break;
                            case 43:
                                lotteryAdapter.setNewData(CqsscGenerateNum.q3qjex());
                                break;
                            case 44:
                                lotteryAdapter.setNewData(CqsscGenerateNum.yffs());
                                break;
                            case 45:
                                lotteryAdapter.setNewData(CqsscGenerateNum.hscs());
                                break;
                            case 46:
                                lotteryAdapter.setNewData(CqsscGenerateNum.sxbx());
                                break;
                            case 47:
                                lotteryAdapter.setNewData(CqsscGenerateNum.sjfc());
                                break;
                        }
                        break;
                    case 93:
                        switch (mBetMethodDetailsID) {
                            case 179:
                            case 180:
                            case 199:
                                lotteryAdapter.setNewData(CqsscGenerateNum.allNum());
                                break;
                            case 186:
                            case 187:
                            case 188:
                            case 189:
                            case 190:
                            case 200:
                            case 201:
                                rvLottery.setVisibility(View.GONE);
                                llLotteryInput.setVisibility(View.VISIBLE);
                                llLotteryInput.findViewById(R.id.rv_top).setVisibility(View.VISIBLE);
                                break;
                            case 196:
                                lotteryAdapter.setNewData(CqsscGenerateNum.sumZSe());
                                lotteryAdapter.setPlacesShow(false);
                                lotteryAdapter.addFooterView(view);
                                break;
                            case 183:
                                lotteryAdapter.setNewData(CqsscGenerateNum.sumS());
                                lotteryAdapter.setPlacesShow(false);
                                lotteryAdapter.addFooterView(view);
                                break;
                            case 185:
                            case 198:
                                lotteryAdapter.setNewData(CqsscGenerateNum.spanRXe());
                                lotteryAdapter.addFooterView(view);
                                break;
                            case 195:
                                lotteryAdapter.setNewData(CqsscGenerateNum.zS());
                                lotteryAdapter.addFooterView(view);
                                break;
                            case 197:
                                lotteryAdapter.setNewData(CqsscGenerateNum.sumZuSe());
                                lotteryAdapter.setPlacesShow(false);
                                lotteryAdapter.addFooterView(view);
                                break;
                            case 184:
                                lotteryAdapter.setNewData(CqsscGenerateNum.sumZS());
                                lotteryAdapter.setPlacesShow(false);
                                lotteryAdapter.addFooterView(view);
                                break;
                            case 181:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z3S());
                                lotteryAdapter.addFooterView(view);
                                break;
                            case 182:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z6S());
                                lotteryAdapter.addFooterView(view);
                                break;
                            case 194:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z24());
                                lotteryAdapter.addFooterView(view);
                                break;
                            case 193:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z60());
                                lotteryAdapter.addFooterView(view);
                                break;
                            case 192:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z6());
                                lotteryAdapter.addFooterView(view);
                                break;
                            case 191:
                                lotteryAdapter.setNewData(CqsscGenerateNum.z20());
                                lotteryAdapter.addFooterView(view);
                                break;
                        }
                        //设置重庆时时彩任选模式下的底部FootView
                        switch (wayGroups.get(Integer.valueOf(position[0])).getChildren().get(Integer.valueOf(position[1])).getId()) {
                            case 94:
                                listSec.clear();
                                listSec.add(3);
                                listSec.add(4);
                                break;
                            case 95:
                                listSec.clear();
                                listSec.add(2);
                                listSec.add(3);
                                listSec.add(4);
                                break;
                            case 96:
                                listSec.clear();
                                listSec.add(1);
                                listSec.add(2);
                                listSec.add(3);
                                listSec.add(4);
                                break;
                        }
                        lotteryAdapter.setListSec(listSec);
                        CqsscGenerateNum.rvOps((RecyclerView) llLotteryInput.findViewById(R.id.rv_top), listSec);
                        break;
                    case 100:
                        switch (mBetMethodDetailsID) {
                            case 352:
                                lotteryAdapter.setNewData(CqsscGenerateNum.wq());
                                break;
                            case 353:
                                lotteryAdapter.setNewData(CqsscGenerateNum.wb());
                                break;
                            case 354:
                                lotteryAdapter.setNewData(CqsscGenerateNum.ws());
                                break;
                            case 355:
                                lotteryAdapter.setNewData(CqsscGenerateNum.wg());
                                break;
                            case 356:
                                lotteryAdapter.setNewData(CqsscGenerateNum.qb());
                                break;
                            case 357:
                                lotteryAdapter.setNewData(CqsscGenerateNum.qs());
                                break;
                            case 358:
                                lotteryAdapter.setNewData(CqsscGenerateNum.qg());
                                break;
                            case 359:
                                lotteryAdapter.setNewData(CqsscGenerateNum.bs());
                                break;
                            case 360:
                                lotteryAdapter.setNewData(CqsscGenerateNum.bg());
                                break;
                            case 361:
                                lotteryAdapter.setNewData(CqsscGenerateNum.sg());
                                break;
                        }
                        break;
                }
                break;
            case 10://PK10
            case 19:
            case 49:
            case 52:
                switch (mBetMethodContentID) {
                    case 87:
                        switch (mBetMethodDetailsID) {
                            case 175:
                                lotteryAdapter.setNewData(Pk10GenerateNum.sumGY());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 176:
                                lotteryAdapter.setNewData(Pk10GenerateNum.sumBS());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                        }
                        break;
                    case 89:
                        lotteryAdapter.setNewData(Pk10GenerateNum.allNum());
                        break;
                    case 91:
                        lotteryAdapter.setNewData(Pk10GenerateNum.lh());
                        lotteryAdapter.setPlacesShow(false);
                        break;
                    case 114:
                        switch (mBetMethodDetailsID) {
                            case 172:
                                lotteryAdapter.setNewData(Pk10GenerateNum.gy());
                                break;
                            case 394:
                                lotteryAdapter.setNewData(Pk10GenerateNum.h2());
                                break;
                            case 396:
                            case 398:
                                rvLottery.setVisibility(View.GONE);
                                llLotteryInput.setVisibility(View.VISIBLE);
                                break;
                        }
                        break;
                    case 117:
                        switch (mBetMethodDetailsID) {
                            case 173:
                                lotteryAdapter.setNewData(Pk10GenerateNum.gyj());
                                break;
                            case 395:
                                lotteryAdapter.setNewData(Pk10GenerateNum.h3());
                                break;
                            case 397:
                            case 399:
                                rvLottery.setVisibility(View.GONE);
                                llLotteryInput.setVisibility(View.VISIBLE);
                                break;
                        }
                        break;
                }
                break;
            case 15://快三类
            case 17:
            case 50:
            case 51:
                lotteryAdapter.setKs(lottery_id);
                switch (mBetMethodContentID) {
                    case 65:
                        lotteryAdapter.setNewData(KsGenerateNum.sumKS());
                        lotteryAdapter.setPlacesShow(false);
                        break;
                    case 66:
                        switch (mBetMethodDetailsID) {
                            case 158:
                                lotteryAdapter.setNewData(KsGenerateNum.sthKS());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 159:
                                lotteryAdapter.setNewData(KsGenerateNum.sthKStx());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                        }
                        break;
                    case 68:
                        switch (mBetMethodDetailsID) {
                            case 160:
                                lotteryAdapter.setNewData(KsGenerateNum.ethKS());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 161:
                                lotteryAdapter.setNewData(KsGenerateNum.ethKSfx());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                        }
                        break;
                    case 70:
                    case 71:
                    case 85:
                        lotteryAdapter.setNewData(KsGenerateNum.sbhKS());
                        break;
                    case 72:
                        lotteryAdapter.setNewData(KsGenerateNum.sthKStx());
                        lotteryAdapter.setPlacesShow(false);
                        break;
                    case 73:
                        lotteryAdapter.setNewData(KsGenerateNum.bs());
                        lotteryAdapter.setPlacesShow(false);
                        break;
                    case 74:
                        lotteryAdapter.setNewData(KsGenerateNum.ds());
                        lotteryAdapter.setPlacesShow(false);
                        break;
                    case 110:
                        switch (mBetMethodDetailsID) {
                            case 378:
                                lotteryAdapter.setNewData(KsGenerateNum.ysR());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 379:
                                lotteryAdapter.setNewData(KsGenerateNum.ysB());
                                lotteryAdapter.setPlacesShow(false);
                                break;
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
                        switch (mBetMethodDetailsID) {
                            case 95:
                            case 97:
                                rvLottery.setVisibility(View.GONE);
                                llLotteryInput.setVisibility(View.VISIBLE);
                                break;
                            case 112:
                                lotteryAdapter.setNewData(ElevenGenerateNum.threeNum());
                                break;
                            case 108:
                                lotteryAdapter.setNewData(ElevenGenerateNum.threeZNum());
                                break;
                            case 121:
                                lotteryAdapter.setNewData(ElevenGenerateNum.DNum());
                                lotteryAdapter.setEleven(mBetMethodDetailsID);
                                break;
                        }
                        break;
                    case 31:
                        switch (mBetMethodDetailsID) {
                            case 94:
                            case 96:
                                rvLottery.setVisibility(View.GONE);
                                llLotteryInput.setVisibility(View.VISIBLE);
                                break;
                            case 111:
                                lotteryAdapter.setNewData(ElevenGenerateNum.twoNum());
                                break;
                            case 107:
                                lotteryAdapter.setNewData(ElevenGenerateNum.twoZNum());
                                break;
                            case 120:
                                lotteryAdapter.setNewData(ElevenGenerateNum.DNum());
                                lotteryAdapter.setEleven(mBetMethodDetailsID);
                                break;
                        }
                        break;
                    case 32:
                        lotteryAdapter.setNewData(ElevenGenerateNum.threeZNum());
                        break;
                    case 33:
                        switch (mBetMethodDetailsID) {
                            case 109:
                                lotteryAdapter.setNewData(ElevenGenerateNum.spice());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 110:
                                lotteryAdapter.setNewData(ElevenGenerateNum.midPosition());
                                break;
                        }
                        break;
                    case 42:
                        lotteryAdapter.setNewData(ElevenGenerateNum.allNum());
                        break;
                    case 34:
                        switch (mBetMethodDetailsID) {
                            case 98:
                                lotteryAdapter.setNewData(ElevenGenerateNum.rx11());
                                break;
                            case 99:
                                lotteryAdapter.setNewData(ElevenGenerateNum.rx22());
                                break;
                            case 100:
                                lotteryAdapter.setNewData(ElevenGenerateNum.rx33());
                                break;
                            case 101:
                                lotteryAdapter.setNewData(ElevenGenerateNum.rx44());
                                break;
                            case 102:
                                lotteryAdapter.setNewData(ElevenGenerateNum.rx55());
                                break;
                            case 103:
                                lotteryAdapter.setNewData(ElevenGenerateNum.rx65());
                                break;
                            case 104:
                                lotteryAdapter.setNewData(ElevenGenerateNum.rx75());
                                break;
                            case 105:
                                lotteryAdapter.setNewData(ElevenGenerateNum.rx85());
                                break;
                        }
                        break;
                    case 35:
                        rvLottery.setVisibility(View.GONE);
                        llLotteryInput.setVisibility(View.VISIBLE);
                        break;
                    case 36:
                        lotteryAdapter.setNewData(ElevenGenerateNum.DNum());
                        lotteryAdapter.setEleven(mBetMethodDetailsID);
                        break;
                }
                break;
            case 20://3D类
                switch (mBetMethodContentID) {
                    case 48:
                        switch (mBetMethodDetailsID) {
                            case 123:
                            case 124:
                            case 125:
                            case 130:
                                rvLottery.setVisibility(View.GONE);
                                llLotteryInput.setVisibility(View.VISIBLE);
                                break;
                            case 136:
                                lotteryAdapter.setNewData(ThreeDGenerateNum.allNum());
                                break;
                            case 139:
                                lotteryAdapter.setNewData(ThreeDGenerateNum.sumS());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                            case 131:
                                lotteryAdapter.setNewData(ThreeDGenerateNum.z3());
                                break;
                            case 132:
                                lotteryAdapter.setNewData(ThreeDGenerateNum.z6());
                                break;
                            case 140:
                                lotteryAdapter.setNewData(ThreeDGenerateNum.sumZS());
                                lotteryAdapter.setPlacesShow(false);
                                break;
                        }
                        break;
                    case 49:
                        switch (mBetMethodDetailsID) {
                            case 126:
                            case 127:
                            case 128:
                            case 129:
                                rvLottery.setVisibility(View.GONE);
                                llLotteryInput.setVisibility(View.VISIBLE);
                                break;
                            case 138:
                                lotteryAdapter.setNewData(ThreeDGenerateNum.h2z());
                                break;
                            case 137:
                                lotteryAdapter.setNewData(ThreeDGenerateNum.q2z());
                                break;
                            case 134:
                            case 135:
                                lotteryAdapter.setNewData(ThreeDGenerateNum.hqz());
                                break;
                        }
                        break;
                    case 50:
                        lotteryAdapter.setNewData(ThreeDGenerateNum.allNum());
                        break;
                    case 51:
                        lotteryAdapter.setNewData(ThreeDGenerateNum.bdw());
                        break;
                    case 104:
                        switch (mBetMethodDetailsID) {
                            case 372:
                                lotteryAdapter.setNewData(ThreeDGenerateNum.bs());
                                break;
                            case 373:
                                lotteryAdapter.setNewData(ThreeDGenerateNum.bg());
                                break;
                            case 374:
                                lotteryAdapter.setNewData(ThreeDGenerateNum.sg());
                                break;
                        }
                        break;
                }
                break;
            case 37://北京快乐8类
                lotteryAdapter.setPlacesShow(false);
                switch (mBetMethodContentID) {
                    case 150:
                        switch (mBetMethodDetailsID) {
                            case 427:
                                lotteryAdapter.setNewData(Happy8GenerateNum.ds());
                                break;
                            case 428:
                                lotteryAdapter.setNewData(Happy8GenerateNum.dx810());
                                break;
                            case 431:
                                lotteryAdapter.setNewData(Happy8GenerateNum.wx());
                                break;
                        }
                        break;
                    case 152:
                        lotteryAdapter.setNewData(Happy8GenerateNum.joh());
                        break;
                    case 153:
                        lotteryAdapter.setNewData(Happy8GenerateNum.szx());
                        break;
                    case 120:
                    case 121:
                    case 122:
                    case 123:
                    case 124:
                    case 125:
                    case 126:
                        lotteryAdapter.setNewData(Happy8GenerateNum.common());
                        lotteryAdapter.setPlacesShow(true);
                        lotteryAdapter.setHappy8(lottery_id);
                        break;
                }
                break;
        }
    }
}
