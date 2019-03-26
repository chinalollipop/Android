package com.cfcp.a01.ui.home.cplist.bet;

import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.CPInjections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseDialogFragment;
import com.cfcp.a01.common.utils.CalcHelper;
import com.cfcp.a01.common.utils.CombinationHelper;
import com.cfcp.a01.common.utils.DoubleClickHelper;
import com.cfcp.a01.data.CPBetResult;
import com.cfcp.a01.ui.home.cplist.events.CPOrderList;
import com.cfcp.a01.ui.home.cplist.events.CPOrderSuccessEvent;
import com.cfcp.a01.ui.home.cplist.events.CloseLotteryEvent;
import com.cfcp.a01.ui.home.cplist.events.ServiceEvent;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import butterknife.BindView;
import butterknife.OnClick;

public class BetCPOrderDialog extends BaseDialogFragment implements CpBetApiContract.View{
    public static final String PARAM0 = "betResult";
    public static final String PARAM1 = "gold";
    public static final String PARAM2 = "game_code";
    public static final String PARAM3 = "round";
    public static final String PARAM4 = "x_session_token";
    @BindView(R.id.betOrderCp)
    RecyclerView betOrderCp;

    @BindView(R.id.betOrderLM)
    LinearLayout betOrderLM;
    @BindView(R.id.betOrderLMNumber)
    TextView betOrderLMNumber;
    @BindView(R.id.betOrderLMZH)
    TextView betOrderLMZH;
    @BindView(R.id.betOrderLMMoneyOne)
    TextView betOrderLMMoneyOne;
    @BindView(R.id.betOrderLMMoney)
    TextView betOrderLMMoney;

    @BindView(R.id.betOrderCpBottom)
    LinearLayout betOrderCpBottom;
    @BindView(R.id.betOrderCpNumber)
    TextView betOrderCpNumber;
    @BindView(R.id.betOrderCpMoney)
    TextView betOrderCpMoney;
    @BindView(R.id.betOrderCpSubmit)
    Button betOrderCpSubmit;
    @BindView(R.id.betOrderCpCancel)
    Button betOrderCpCancel;
    ArrayList<CPOrderList> betResult;
    CPBetParams cpBetParams;
    String userMoney;
    private String betGold="",betType;

    String fTime, game_code,  round, totalNums,totalMoney,number,typeCode,rtype, x_session_token;
    CpBetApiContract.Presenter presenter;

    public static BetCPOrderDialog newInstance(ArrayList<CPOrderList> cpOrderListArrayList, String gold, String game_code, String round, String x_session_token) {
        Bundle bundle = new Bundle();
        bundle.putParcelableArrayList(PARAM0, cpOrderListArrayList);
        bundle.putString(PARAM1, gold);
        bundle.putString(PARAM2, game_code);
        bundle.putString(PARAM3, round);
        bundle.putString(PARAM4, x_session_token);
        BetCPOrderDialog dialog = new BetCPOrderDialog();
        dialog.setArguments(bundle);
        CPInjections.inject(null,dialog);
        return dialog;
    }

    public static BetCPOrderDialog newInstances(ArrayList<CPOrderList> cpOrderListArrayList,CPBetParams cpBetParams) {
        Bundle bundle = new Bundle();
        bundle.putParcelableArrayList(PARAM0, cpOrderListArrayList);
        bundle.putParcelable(PARAM1, cpBetParams);
        BetCPOrderDialog dialog = new BetCPOrderDialog();
        dialog.setArguments(bundle);
        CPInjections.inject(null,dialog);
        return dialog;
    }

    @Override
    protected int setLayoutId() {
        return R.layout.dialog_bet_order_cp;
    }

    @Override
    protected void setEvents(View view, Bundle bundle) {
        betResult =  getArguments().getParcelableArrayList(PARAM0);
        cpBetParams = getArguments().getParcelable(PARAM1);
        betGold = cpBetParams.getGold();
        betType = cpBetParams.getType();
        game_code = cpBetParams.getGame_code();
        round =  cpBetParams.getRound();
        fTime = cpBetParams.getfTime();
        x_session_token =  cpBetParams.getX_session_token();
        typeCode = cpBetParams.getTypeCode();
        rtype = cpBetParams.getRtype();
        if("HKSXL".equals(betType)){
            int sXize = betResult.size();
            List<String> dataString = new ArrayList<>();
            String nameData="";
            String nameData1="";
            String gouName="";
            String gouRote="";
            String otherRote="";
            for(int k=0;k<sXize;++k){
                dataString.add(betResult.get(k).gName.split(" - ")[1]);
                nameData+= betResult.get(k).gName.split(" - ")[1].replace("尾","")+",";
                nameData1 = betResult.get(k).gName.split(" - ")[0];
                if(betResult.get(k).gName.split(" - ")[1].equals("狗")||betResult.get(k).gName.split(" - ")[1].replace("尾","").equals("0")){
                    gouRote= betResult.get(k).rate;
                }else{
                    otherRote= betResult.get(k).rate;
                }
            }
            String [] dataL = nameData.split(",");
            //CombinationHelper.arrangementSelect(dataL, 2);
            /*int[] num = new int[]{1,2,3,4,5,6};
            try {
                CombineUtils.print(CombineUtils.combine(num,3));
            } catch (Exception e) {
                e.printStackTrace();
            }*/
            CombinationHelper.combinationSelect(dataL, Integer.parseInt(cpBetParams.getTypeNumber()));
            List<String> dta = CombinationHelper.newDataList();
            int ssDta = dta.size();
            ArrayList<CPOrderList> newBetListData = new ArrayList<>();
            for(int k=0;k<ssDta;++k){
                if(dta.get(k).contains("狗")||dta.get(k).contains("0")){
                    newBetListData.add(new CPOrderList(""+k,k+"",nameData1+" "+dta.get(k).replace("[","").replace("]",""),gouRote,""));
                }else{
                    newBetListData.add(new CPOrderList(""+k,k+"",nameData1+" "+dta.get(k).replace("[","").replace("]",""),otherRote,""));
                }
            }
            //newBetListData(betResult,2);
             /*
            combine(nameData.toCharArray(),0,nameData.length());
            String []dayaList = data.split("_");
            int sizedd = dayaList.length;
            ArrayList<CPOrderList> betResultBak = new ArrayList<>();
            for(int i=0;i<sizedd;++i){
                CPOrderList cpOrderList = new CPOrderList(betResult.get(i).position,betResult.get(i).gid,dayaList[i],betResult.get(i).rate,betResult.get(i).otherName);
                betResultBak.add(cpOrderList);
            }
            GameLog.log("最后的值是 "+dataString);
*/
             /*nameData = nameData.substring(0,nameData.length()-1);
            totalNums = cpBetParams.getTypeNumber();
            totalMoney = CalcHelper.multiplyString(betGold,totalNums)+"";
            betOrderLM.setVisibility(View.VISIBLE);
            betOrderCp.setVisibility(View.GONE);
            betOrderCpBottom.setVisibility(View.GONE);
            betOrderLMNumber.setText(nameData1+"【"+nameData+"】");
            betOrderLMZH.setText("组合数："+cpBetParams.getTypeNumber());
            betOrderLMMoneyOne.setText("单注金额："+betGold);
            betOrderLMMoney.setText("总金额："+totalMoney);*/

            totalNums = cpBetParams.getTypeNumber();
            totalMoney = CalcHelper.multiplyString(betGold,ssDta+"")+"";
            betOrderLM.setVisibility(View.GONE);
            betOrderCp.setVisibility(View.VISIBLE);
            betOrderCpBottom.setVisibility(View.VISIBLE);
            betOrderCpNumber.setText(ssDta+"");
            betOrderCpMoney.setText(totalMoney);
            LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
            betOrderCp.setLayoutManager(gridLayoutManager);
            betOrderCp.setHasFixedSize(true);
            betOrderCp.setNestedScrollingEnabled(false);
            betOrderCp.setAdapter(new ZHBetListAdapter( R.layout.item_order_cp,newBetListData));

        }else if("LM".equals(betType)||"HKLM".equals(betType)){
            totalNums = cpBetParams.getTypeNumber();
            totalMoney = CalcHelper.multiplyString(betGold,totalNums)+"";
            betOrderLM.setVisibility(View.VISIBLE);
            betOrderCp.setVisibility(View.GONE);
            betOrderCpBottom.setVisibility(View.GONE);
            int size = betResult.size();
            number ="";
            for(int i=0;i<size;++i){
                number += betResult.get(i).getGid()+",";
            }
            number = number.substring(0,number.length()-1);
            betOrderLMNumber.setText(cpBetParams.getTypeName()+"【"+number+"】");
            betOrderLMZH.setText("组合数："+cpBetParams.getTypeNumber());
            betOrderLMMoneyOne.setText("单注金额："+betGold);
            betOrderLMMoney.setText("总金额："+totalMoney);
        }else if("HKHX".equals(betType)){
            totalNums = cpBetParams.getTypeNumber();
            totalMoney = CalcHelper.multiplyString(betGold,totalNums)+"";
            betOrderLM.setVisibility(View.VISIBLE);
            betOrderCp.setVisibility(View.GONE);
            betOrderCpBottom.setVisibility(View.GONE);
            int size = betResult.size();
            number ="";
            for(int i=0;i<size;++i){
                number += betResult.get(i).getgName()+",";
            }
            number = number.substring(0,number.length()-1);
            betOrderLMNumber.setText(cpBetParams.getTypeName()+"-合肖"+size+"【"+number+"】");
            betOrderLMZH.setText("组合数："+cpBetParams.getTypeNumber());
            betOrderLMMoneyOne.setText("单注金额："+betGold);
            betOrderLMMoney.setText("总金额："+totalMoney);
        }else if("HKZXBZ".equals(betType)){
            totalNums = cpBetParams.getTypeNumber();
            totalMoney = CalcHelper.multiplyString(betGold,totalNums)+"";
            betOrderLM.setVisibility(View.VISIBLE);
            betOrderCp.setVisibility(View.GONE);
            betOrderCpBottom.setVisibility(View.GONE);
            int size = betResult.size();
            number ="";
            for(int i=0;i<size;++i){
                number += betResult.get(i).getgName()+",";
            }
            number = number.substring(0,number.length()-1);
            betOrderLMNumber.setText(cpBetParams.getTypeName()+" - "+size+"【"+number+"】");
            betOrderLMZH.setText("组合数："+cpBetParams.getTypeNumber());
            betOrderLMMoneyOne.setText("单注金额："+betGold);
            betOrderLMMoney.setText("总金额："+totalMoney);
        }else{
            totalNums = betResult.size()+"";
            totalMoney = CalcHelper.multiplyString(betGold,totalNums)+"";
            betOrderLM.setVisibility(View.GONE);
            betOrderCp.setVisibility(View.VISIBLE);
            betOrderCpBottom.setVisibility(View.VISIBLE);
            betOrderCpNumber.setText(totalNums);
            betOrderCpMoney.setText(totalMoney);
            LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
            betOrderCp.setLayoutManager(gridLayoutManager);
            betOrderCp.setHasFixedSize(true);
            betOrderCp.setNestedScrollingEnabled(false);
            betOrderCp.setAdapter(new ZHBetListAdapter(R.layout.item_order_cp,betResult));
        }

       /* betGold =  getArguments().getString(PARAM1);
        game_code =  getArguments().getString(PARAM2);
        round =  getArguments().getString(PARAM3);
        x_session_token =  getArguments().getString(PARAM4);*/
        EventBus.getDefault().register(this);

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void postCpBetResult(CPBetResult betResult) {
        showMessage("投注成功！");
        EventBus.getDefault().post(new CPOrderSuccessEvent());
        hide();
    }


    @Override
    public void setPresenter(CpBetApiContract.Presenter presenter) {
        this.presenter = presenter;
    }

    public class ZHBetListAdapter extends BaseQuickAdapter<CPOrderList, BaseViewHolder> {

        public ZHBetListAdapter( int layoutId, List datas) {
            super(layoutId, datas);
        }
        @Override
        protected void convert(BaseViewHolder holder, final CPOrderList rowsBean) {
            holder.setText(R.id.itemZH1,  "【"+rowsBean.getgName()+"】@"+rowsBean.getRate()+" X "+betGold);
        }
    }

    //标记
    private String onMarkRed(String sign){
        return " <font color='#C9270B'>" + sign+"</font>";
    }

    @OnClick({R.id.betOrderCpCancel,R.id.betOrderCpSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.betOrderCpCancel:
                hide();
                break;
            case R.id.betOrderCpSubmit:
                int size = betResult.size();
                number="";
                ArrayList<BetParam.BetdataBean.BetBeanBean> beanArrayList = new ArrayList<>();
                BetParam.BetdataBean betParam = new BetParam.BetdataBean();
                betParam.setBetSrc(CFConstant.PRODUCT_PLATFORM);
                betParam.setFtime(fTime);
                betParam.setGameId(game_code);
                betParam.setTotalMoney(totalMoney);
                betParam.setTotalNums(totalNums);
                betParam.setTurnNum(round);
                DoubleClickHelper.getNewInstance().disabledView(betOrderCpSubmit);
                if("LM".equals(betType)){
                    for(int i=0;i<size;++i){
                        number += betResult.get(i).getGid()+",";
                    }
                    number = number.substring(0,number.length()-1);
                    BetParam.BetdataBean.BetBeanBean betBeanBean= new BetParam.BetdataBean.BetBeanBean();
                    betBeanBean.setMoney(betGold);
                    betBeanBean.setOdds(rtype);
                    betBeanBean.setPlayId(typeCode);
                    betBeanBean.setRebate("0");
                    betBeanBean.setBetInfo(number);
                    beanArrayList.add(betBeanBean);
                    betParam.setBetBean(beanArrayList);
                    presenter.postCpBets(game_code,  round, totalNums,totalMoney,"",null, JSON.toJSONString(betParam));
                }else if("HKLM".equals(betType)||"HKHX".equals(betType)||"HKZXBZ".equals(betType)||"HKGG".equals(betType)||"HKSXL".equals(betType)){
                    for(int i=0;i<size;++i){
                        number += betResult.get(i).getGid()+",";
                    }
                    number = number.substring(0,number.length()-1);
                    BetParam.BetdataBean.BetBeanBean betBeanBean= new BetParam.BetdataBean.BetBeanBean();
                    betBeanBean.setMoney(betGold);
                    betBeanBean.setOdds(rtype);
                    betBeanBean.setPlayId(typeCode);
                    betBeanBean.setRebate("0");
                    betBeanBean.setBetInfo(number);
                    beanArrayList.add(betBeanBean);
                    betParam.setBetBean(beanArrayList);
                    presenter.postCpBets(game_code,  round, totalNums,totalMoney,"",null, JSON.toJSONString(betParam));
                    //presenter.postCpBetsHK(game_code,  round, totalNums,totalMoney,number,betGold,typeCode,rtype, x_session_token);
                }else if("HK".equals(betType)){
                    Map data = new HashMap<>();
                    for(int i=0;i<size;++i){
                        BetParam.BetdataBean.BetBeanBean betBeanBean= new BetParam.BetdataBean.BetBeanBean();
                        betBeanBean.setMoney(betGold);
                        betBeanBean.setOdds(betResult.get(i).getRate());
                        betBeanBean.setPlayId(betResult.get(i).getGid());
                        betBeanBean.setRebate("0");
                        betBeanBean.setBetInfo("");
                        beanArrayList.add(betBeanBean);
                        //number += "betBean["+betResult.get(i).getPosition()+"][ip_"+betResult.get(i).getGid()+"]: "+betGold+"\n";
                        data.put("betBean["+betResult.get(i).getPosition()+"][ip_"+betResult.get(i).getGid()+"]",betGold);
                    }
                    betParam.setBetBean(beanArrayList);
                    presenter.postCpBets(game_code,  round, totalNums,totalMoney,"",data, JSON.toJSONString(betParam));
                    //presenter.postCpBetsHKMap(game_code,  round, totalNums,totalMoney,"",data, x_session_token);
                }else{
                    Map data = new HashMap<>();

                    for(int i=0;i<size;++i){
                        //number += "betBean["+betResult.get(i).getPosition()+"][ip_"+betResult.get(i).getGid()+"]: "+betGold+"\n";
                        BetParam.BetdataBean.BetBeanBean betBeanBean= new BetParam.BetdataBean.BetBeanBean();
                        betBeanBean.setMoney(betGold);
                        betBeanBean.setOdds(betResult.get(i).getRate());
                        betBeanBean.setPlayId(betResult.get(i).getGid());
                        betBeanBean.setRebate("0");
                        betBeanBean.setBetInfo("");
                        beanArrayList.add(betBeanBean);
                        data.put("betBean["+i+"][playId]",betResult.get(i).getGid());
                        data.put("betBean["+i+"][odds]",betResult.get(i).getRate());
                        data.put("betBean["+i+"][rebate]","0");
                        data.put("betBean["+i+"][money]",betGold);
                    }

                    betParam.setBetBean(beanArrayList);
                    presenter.postCpBets(game_code,  round, totalNums,totalMoney,"",data, JSON.toJSONString(betParam));
                }
                break;
        }
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    @Subscribe
    public void onEventMain(CloseLotteryEvent closeLotteryEvent){
        showMessage("已封盘，请稍后下注！");
        hide();
    }

    @Subscribe
    public void onEventMain(ServiceEvent serviceEvent){
        showMessage(serviceEvent.getMsg());
        hide();
    }
}
