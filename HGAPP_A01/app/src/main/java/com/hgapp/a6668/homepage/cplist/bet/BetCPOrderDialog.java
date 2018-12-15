package com.hgapp.a6668.homepage.cplist.bet;

import android.content.Context;
import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.hgapp.a6668.CPInjections;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseDialogFragment;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.util.CalcHelper;
import com.hgapp.a6668.data.CPBetResult;
import com.hgapp.a6668.homepage.cplist.events.CPOrderList;
import com.hgapp.a6668.homepage.cplist.events.CPOrderSuccessEvent;
import com.hgapp.a6668.homepage.cplist.events.CloseLotteryEvent;
import com.hgapp.a6668.homepage.cplist.events.ServiceEvent;
import com.hgapp.a6668.homepage.handicap.BottombarViewManager;
import com.hgapp.a6668.homepage.handicap.leaguedetail.CalosEvent;
import com.hgapp.a6668.personpage.betrecord.BetRecordFragment;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class BetCPOrderDialog extends HGBaseDialogFragment implements CpBetApiContract.View{
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

    String game_code,  round, totalNums,totalMoney,number,typeCode,rtype, x_session_token;
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



    int F(int n)//函数返回一个数对应的Fibonacci数
    {
        if (n == 0 || n == 1)//递归边界
            return 1;
        return F(n - 1) + F(n - 2);//递归公式
    }

    public List<String> permutation(char[] s, int from, int to) {
        List<String> result = new ArrayList<>();
        if (to < 1)
            return result;
        if (from == to) {
            result.add(new String(s));
        } else {
            for (int i = from; i < to; i++) {
                if (isSwap(s, from, i)) {
                    swap(s, i, from); //交换前缀，使其产生下一个前缀
                    result.addAll(permutation(s, from + 1, to));
                    swap(s, from, i); //将前缀换回，继续做上一个前缀的排列
                }
            }
        }
        return result;
    }

    public void swap(char[] s, int i, int j) {
        if (i == j) {
            return;
        }
        char tmp = s[i];
        s[i] = s[j];
        s[j] = tmp;
    }

//判断当前"i"处的字符是否之前已经出现过，出现过则返回false，不交换
    private boolean isSwap(char[] list, int start, int i) {
        for(int k = start; k<i;k++) {
            if (list[k] == list[i]) return false;
        }
        return true;
    }

    private ArrayList<CPOrderList> newBetListData(ArrayList<CPOrderList> betResult){
        ArrayList<CPOrderList> newBetListData = new ArrayList<>();
        String data="";
        int size = betResult.size();
        String  name1= "";
        List<String> dataString = new ArrayList<>();
        String nameData="";
        for(int k=0;k<size;++k){
            name1 = betResult.get(k).gName.split(" - ")[0];
            dataString.add(betResult.get(k).gName.split(" - ")[1]);
            nameData+= betResult.get(k).gName.split(" - ")[1];
        }

        switch (size){
            case 2:
                CPOrderList cpOrderList = null;
                if(betResult.get(0).rate.compareTo(betResult.get(1).rate)>0){
                    cpOrderList = new CPOrderList(betResult.get(0).position,betResult.get(0).gid,betResult.get(0).gName+","+betResult.get(1).gName.split(" - ")[1],betResult.get(1).rate,betResult.get(0).otherName);
                }else{
                    cpOrderList = new CPOrderList(betResult.get(0).position,betResult.get(0).gid,betResult.get(0).gName+","+betResult.get(1).gName.split(" - ")[1],betResult.get(0).rate,betResult.get(0).otherName);
                }
                newBetListData.add(cpOrderList);
                break;
            case 3:
                for(int i=0;i<size-1;i++){
                    for(int j=0;j<size-2;j++){
                        CPOrderList cpOrderList1 = null;
                        if(betResult.get(i).rate.compareTo(betResult.get(j).rate)>0){
                            cpOrderList1 = new CPOrderList(betResult.get(i).position,betResult.get(i).gid,betResult.get(i).gName+","+betResult.get(i).gName.split(" - ")[1],betResult.get(i).rate,betResult.get(i).otherName);
                        }else{
                            cpOrderList1 = new CPOrderList(betResult.get(j).position,betResult.get(j).gid,betResult.get(j).gName+","+betResult.get(j).gName.split(" - ")[1],betResult.get(j).rate,betResult.get(j).otherName);
                        }
                        newBetListData.add(cpOrderList1);
                    }
                }
                break;
        }

        return newBetListData;
    }




    String  FList(List<String> dataList,int n)//函数返回一个数对应的Fibonacci数
    {

        String data="";
        int size = dataList.size();
        switch (size){
            case 2:
                data= dataList.get(0)+","+dataList.get(1);
                break;
            case 3:
                data= dataList.get(0)+","+dataList.get(1)+"_"+dataList.get(0)+","+dataList.get(2)+"_"+dataList.get(1)+","+dataList.get(2);
                break;
        }

        return data;//递归公式
    }

        @Override
    protected int getLayoutResId() {
        return R.layout.dialog_bet_order_cp;
    }

    @Override
    protected void initView(View view, Bundle bundle) {
        betResult =  getArguments().getParcelableArrayList(PARAM0);
        cpBetParams = getArguments().getParcelable(PARAM1);
        betGold = cpBetParams.getGold();
        betType = cpBetParams.getType();
        game_code = cpBetParams.getGame_code();
        round =  cpBetParams.getRound();
        x_session_token =  cpBetParams.getX_session_token();
        typeCode = cpBetParams.getTypeCode();
        rtype = cpBetParams.getRtype();
        if("HKSXL".equals(betType)){
            int sXize = betResult.size();
            List<String> dataString = new ArrayList<>();
            String nameData="";
            String nameData1="";
            for(int k=0;k<sXize;++k){
                dataString.add(betResult.get(k).gName.split(" - ")[1]);
                nameData+= betResult.get(k).gName.split(" - ")[1].replace("尾","")+",";
                nameData1 = betResult.get(k).gName.split(" - ")[0];
            }
             String data =  FList(dataString,2);
             /*newBetListData(betResult);
//            combine(nameData.toCharArray(),0,nameData.length());
            String []dayaList = data.split("_");
            int sizedd = dayaList.length;
            ArrayList<CPOrderList> betResultBak = new ArrayList<>();
            for(int i=0;i<sizedd;++i){
                CPOrderList cpOrderList = new CPOrderList(betResult.get(i).position,betResult.get(i).gid,dayaList[i],betResult.get(i).rate,betResult.get(i).otherName);
                betResultBak.add(cpOrderList);
            }
            GameLog.log("最后的值是 "+dataString);
*/          nameData = nameData.substring(0,nameData.length()-1);
            totalNums = cpBetParams.getTypeNumber();
            totalMoney = CalcHelper.multiply(betGold,totalNums)+"";
            betOrderLM.setVisibility(View.VISIBLE);
            betOrderCp.setVisibility(View.GONE);
            betOrderCpBottom.setVisibility(View.GONE);
            betOrderLMNumber.setText(nameData1+"【"+nameData+"】");
            betOrderLMZH.setText("组合数："+cpBetParams.getTypeNumber());
            betOrderLMMoneyOne.setText("单注金额："+betGold);
            betOrderLMMoney.setText("总金额："+totalMoney);

            /*totalNums = cpBetParams.getTypeNumber();
            totalMoney = CalcHelper.multiply(betGold,totalNums)+"";
            betOrderLM.setVisibility(View.GONE);
            betOrderCp.setVisibility(View.VISIBLE);
            betOrderCpBottom.setVisibility(View.VISIBLE);
            betOrderCpNumber.setText(totalNums);
            betOrderCpMoney.setText(totalMoney);
            LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
            betOrderCp.setLayoutManager(gridLayoutManager);
            betOrderCp.setHasFixedSize(true);
            betOrderCp.setNestedScrollingEnabled(false);
            betOrderCp.setAdapter(new ZHBetListAdapter(getContext(), R.layout.item_order_cp,betResultBak));*/

        }else if("LM".equals(betType)||"HKLM".equals(betType)){
            totalNums = cpBetParams.getTypeNumber();
            totalMoney = CalcHelper.multiply(betGold,totalNums)+"";
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
            totalMoney = CalcHelper.multiply(betGold,totalNums)+"";
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
            totalMoney = CalcHelper.multiply(betGold,totalNums)+"";
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
            totalMoney = CalcHelper.multiply(betGold,totalNums)+"";
            betOrderLM.setVisibility(View.GONE);
            betOrderCp.setVisibility(View.VISIBLE);
            betOrderCpBottom.setVisibility(View.VISIBLE);
            betOrderCpNumber.setText(totalNums);
            betOrderCpMoney.setText(totalMoney);
            LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
            betOrderCp.setLayoutManager(gridLayoutManager);
            betOrderCp.setHasFixedSize(true);
            betOrderCp.setNestedScrollingEnabled(false);
            betOrderCp.setAdapter(new ZHBetListAdapter(getContext(), R.layout.item_order_cp,betResult));
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
        showMessage(betResult.getMsg());
        EventBus.getDefault().post(new CPOrderSuccessEvent());
        hide();
    }

    @Override
    public void setStart(int action) {

    }

    @Override
    public void setError(int action, int errcode) {

    }

    @Override
    public void setError(int action, String errString) {

    }

    @Override
    public void setComplete(int action) {

    }

    @Override
    public void setPresenter(CpBetApiContract.Presenter presenter) {
        this.presenter = presenter;
    }

    public class ZHBetListAdapter extends AutoSizeRVAdapter<CPOrderList> {
        private Context context;

        public ZHBetListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }
        @Override
        protected void convert(ViewHolder holder, final CPOrderList rowsBean, int position) {
            holder.setText(R.id.itemZH1,  "【"+rowsBean.getgName()+"】@"+rowsBean.getRate()+" X "+betGold);
            /*TextView textView2 =  holder.getView(R.id.itemZH2);
            TextView textView3 =  holder.getView(R.id.itemZH3);
            textView2.setText(Html.fromHtml(rowsBean.s_mb_team+onMarkRed(rowsBean.sign)+rowsBean.s_tg_team));
            textView3.setText(Html.fromHtml(onMarkRed(rowsBean.s_m_place)+"@"+onMarkRed(rowsBean.w_m_rate)));
            holder.setText(R.id.itemZH4, "" );
            holder.setVisible(R.id.itemZHClear,false);*/
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

                if("LM".equals(betType)){
                    for(int i=0;i<size;++i){
                        number += betResult.get(i).getGid()+",";
                    }
                    number = number.substring(0,number.length()-1);
                    presenter.postCpBetsLM(game_code,  round, totalNums,totalMoney,number,betGold,typeCode, x_session_token);
                }else if("HKLM".equals(betType)||"HKHX".equals(betType)||"HKZXBZ".equals(betType)||"HKGG".equals(betType)||"HKSXL".equals(betType)){
                    for(int i=0;i<size;++i){
                        number += betResult.get(i).getGid()+",";
                    }
                    number = number.substring(0,number.length()-1);
                    presenter.postCpBetsHK(game_code,  round, totalNums,totalMoney,number,betGold,typeCode,rtype, x_session_token);
                }else if("HK".equals(betType)){
                    Map data = new HashMap<>();
                    for(int i=0;i<size;++i){
                        //number += "betBean["+betResult.get(i).getPosition()+"][ip_"+betResult.get(i).getGid()+"]: "+betGold+"\n";
                        data.put("betBean["+betResult.get(i).getPosition()+"][ip_"+betResult.get(i).getGid()+"]",betGold);
                    }
                    presenter.postCpBetsHKMap(game_code,  round, totalNums,totalMoney,"",data, x_session_token);
                }else{
                    Map data = new HashMap<>();
                    for(int i=0;i<size;++i){
                        //number += "betBean["+betResult.get(i).getPosition()+"][ip_"+betResult.get(i).getGid()+"]: "+betGold+"\n";
                        data.put("betBean["+betResult.get(i).getPosition()+"][ip_"+betResult.get(i).getGid()+"]",betGold);
                    }
                    presenter.postCpBets(game_code,  round, totalNums,totalMoney,"",data, x_session_token);
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
