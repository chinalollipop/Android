package com.sunapp.bloc.personpage.balanceplatform;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.DialogFragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.text.InputType;
import android.view.View;

import com.sunapp.bloc.HGApplication;
import com.sunapp.bloc.Injections;
import com.sunapp.bloc.R;
import com.sunapp.bloc.base.HGBaseFragment;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.common.adapters.AutoSizeRVAdapter;
import com.sunapp.bloc.common.util.ACache;
import com.sunapp.bloc.common.util.GameShipHelper;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.widgets.NTitleBar;
import com.sunapp.bloc.data.BetRecordResult;
import com.sunapp.bloc.data.KYBalanceResult;
import com.sunapp.bloc.data.PersonBalanceResult;
import com.sunapp.bloc.homepage.UserMoneyEvent;
import com.sunapp.common.util.Check;
import com.sunapp.common.util.GameLog;
import com.mylhyl.circledialog.CircleDialog;
import com.mylhyl.circledialog.callback.ConfigInput;
import com.mylhyl.circledialog.params.InputParams;
import com.mylhyl.circledialog.view.listener.OnInputClickListener;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;

public class BalancePlatformFragment extends HGBaseFragment implements BalancePlatformContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.backTitleBalancePlatform)
    NTitleBar backTitleBalancePlatform;
    @BindView(R.id.lvBalancePlatform)
    RecyclerView lvBalancePlatform;
    private BalancePlatformContract.Presenter presenter;
    private String from ="hg";
    private String to ="hg";
    PersonBalanceResult personBalance;
    private String typeArgsHG;
    private String typeArgsCP;
    private String typeArgsAG;

    List<String> balancePlatformList  = new ArrayList<>();
    BalancePlatformAdapter balancePlatformAdapter;
    public static BalancePlatformFragment newInstance(PersonBalanceResult personBalance) {
        BalancePlatformFragment fragment = new BalancePlatformFragment();
        Bundle args = new Bundle();
        args.putParcelable(TYPE1, personBalance);
        fragment.setArguments(args);
        Injections.inject(null, fragment);
        return fragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            personBalance = getArguments().getParcelable(TYPE1);
            if(null!=personBalance){
                typeArgsCP = personBalance.getBalance_cp();
                typeArgsAG = personBalance.getBalance_ag();
                typeArgsHG = personBalance.getBalance_hg();
            }

        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_balanceplatform;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        balancePlatformAdapter = new BalancePlatformAdapter(getContext(),R.layout.item_balance_platform,balancePlatformList);
        LinearLayoutManager mLayoutManager1 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        lvBalancePlatform.setLayoutManager(mLayoutManager1);
        lvBalancePlatform.setHasFixedSize(true);
        lvBalancePlatform.setNestedScrollingEnabled(false);
        lvBalancePlatform.setAdapter(balancePlatformAdapter);
        presenter.postPersonBalance("","");
        presenter.postPersonBalanceCP("","");
        presenter.postPersonBalanceKY("","");
        presenter.postPersonBalanceHG("","");
        presenter.postPersonBalanceVG("","");
        presenter.postPersonBalanceLY("","");
        presenter.postPersonBalanceMG("","");
        presenter.postPersonBalanceAG("","");
        presenter.postPersonBalanceOG("","");
        presenter.postPersonBalanceCQ("","");
        presenter.postPersonBalanceMW("","");
        presenter.postPersonBalanceFG("","");
        backTitleBalancePlatform.setMoreText(GameShipHelper.formatMoney(typeArgsHG));
        backTitleBalancePlatform.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });
    }

    class BalancePlatformAdapter extends AutoSizeRVAdapter<String> {

        private Context context;
        public BalancePlatformAdapter(Context context, int layoutId, List<String> datas){
            super(context, layoutId, datas);
            this.context =  context;
        }

        @Override
        protected void convert(ViewHolder viewHolder, final String sdata, final int postion) {
            if(postion==0){
                viewHolder.setText(R.id.itemBalancePlatformName,"彩票平台");
            }else if(postion==1){
                viewHolder.setText(R.id.itemBalancePlatformName,"AG平台");
            }else if(postion==2){
                viewHolder.setText(R.id.itemBalancePlatformName,"开元棋牌");
            }else  if(postion==3){
                viewHolder.setText(R.id.itemBalancePlatformName,"皇冠棋牌");
            }else  if(postion==4){
                viewHolder.setText(R.id.itemBalancePlatformName,"VG棋牌");
            }else  if(postion==5){
                viewHolder.setText(R.id.itemBalancePlatformName,"乐游棋牌");
            }else if(postion==6){
                viewHolder.setText(R.id.itemBalancePlatformName,"MG电子");
            }else if(postion==7){
                viewHolder.setText(R.id.itemBalancePlatformName,"泛亚电竞");
            }else if(postion==8){
                viewHolder.setText(R.id.itemBalancePlatformName,"OG视讯");
            }else if(postion==9){
                viewHolder.setText(R.id.itemBalancePlatformName,"CQ9电子");
            }else if(postion==10){
                viewHolder.setText(R.id.itemBalancePlatformName,"MW电子");
            }else {
                viewHolder.setText(R.id.itemBalancePlatformName,"FG电子");
            }
            viewHolder.setText(R.id.itemBalancePlatformMoney,sdata);
            viewHolder.setOnClickListener(R.id.itemBalancePlatformIn,new View.OnClickListener(){

                @Override
                public void onClick(View view) {

                    DialogFragment show = new CircleDialog.Builder(getActivity())
                            //添加标题，参考普通对话框
                            .setTitle("提示")
                            .setInputHint("请输入转入金额")//提示
                            .setInputHeight(100)//输入框高度
                            // .setInputCounterColor(color)//最大字符数文字的颜色值
                            .autoInputShowKeyboard()//自动弹出键盘
                            .setNegative("取消", null)
                            .configInput(new ConfigInput() {
                                @Override
                                public void onConfig(InputParams params) {
                                    params.inputType = InputType.TYPE_CLASS_NUMBER;
                                }
                            })
                            .setPositiveInput("确定", new OnInputClickListener() {
                                @Override
                                public void onClick(String text, View v) {
                                    GameLog.log("输入的金额 是“" + text);
                                    if(Check.isEmpty(text)){
                                        return;
                                    }
                                    if (postion == 0) {//彩票转入
                                        presenter.postBanalceTransferCP("", "fundLimitTrans", "hg", "gmcp", GameShipHelper.getIntegerString(text));
                                    } else if (postion == 1){//AG转入
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransfer("", "hg", "ag", GameShipHelper.getIntegerString(text));
                                    }else if(postion == 2){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferKY("", "hg", "ky", GameShipHelper.getIntegerString(text));
                                    }else  if(postion == 3){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferHG("", "hg", "ff", GameShipHelper.getIntegerString(text));
                                    }else if(postion == 4){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferVG("", "hg", "vg", GameShipHelper.getIntegerString(text));
                                    }else if(postion == 5){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferLY("", "hg", "ly", GameShipHelper.getIntegerString(text));
                                    }else if(postion == 6){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferMG("", "hg", "mg", GameShipHelper.getIntegerString(text));
                                    }else  if(postion == 7){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferAG("", "hg", "avia", GameShipHelper.getIntegerString(text));
                                    }else  if(postion == 8){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferOG("", "hg", "og", GameShipHelper.getIntegerString(text));
                                    }else if(postion == 9){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferCQ("", "hg", "cq", GameShipHelper.getIntegerString(text));
                                    }else if(postion == 10){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferMW("", "hg", "mw", GameShipHelper.getIntegerString(text));
                                    }else{
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferFG("", "hg", "fg", GameShipHelper.getIntegerString(text));
                                    }

                                }
                            })
                            .show();

                }
            });

            viewHolder.setOnClickListener(R.id.itemBalancePlatformOut,new View.OnClickListener(){

                @Override
                public void onClick(View view) {

                    new CircleDialog.Builder(getActivity())
                            //添加标题，参考普通对话框
                            .setTitle("提示")
                            .setInputHint("请输入转出金额")//提示
                            .setInputHeight(100)//输入框高度
                           // .setInputCounterColor(color)//最大字符数文字的颜色值
                            .autoInputShowKeyboard()//自动弹出键盘
                            .setNegative("取消",null)
                            .configInput(new ConfigInput() {
                                @Override
                                public void onConfig(InputParams params) {
                                    params.inputType = InputType.TYPE_CLASS_NUMBER;
                                }
                            })
                            .setPositiveInput("确定", new OnInputClickListener() {
                                @Override
                                public void onClick(String text, View v) {
                                    GameLog.log("输入的金额 是“" +text);
                                    if(Check.isEmpty(text)){
                                        return;
                                    }
                                    if(postion==0){//彩票转出
                                        presenter.postBanalceTransferCP("","fundLimitTrans","gmcp","hg", GameShipHelper.getIntegerString(text));
                                    }else if(postion==1){//AG转入
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransfer("","ag","hg",GameShipHelper.getIntegerString(text));
                                    }else if(postion==2){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferKY("", "ky", "hg", GameShipHelper.getIntegerString(text));
                                    }else if(postion==3){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferHG("", "ff", "hg", GameShipHelper.getIntegerString(text));
                                    }else if(postion==4){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferVG("", "vg", "hg", GameShipHelper.getIntegerString(text));
                                    }else if(postion==5){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferLY("", "ly", "hg", GameShipHelper.getIntegerString(text));
                                    }else if(postion==6){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferMG("", "mg", "hg", GameShipHelper.getIntegerString(text));
                                    }else if(postion== 7){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferAG("", "avia", "hg", GameShipHelper.getIntegerString(text));
                                    }else if(postion==8){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferOG("", "og", "hg", GameShipHelper.getIntegerString(text));
                                    }else  if(postion==9){
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferCQ("", "cq", "hg", GameShipHelper.getIntegerString(text));
                                    }else if(postion==10) {
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferMW("", "mw", "hg", GameShipHelper.getIntegerString(text));
                                    }else  {
                                        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                            showMessage("非常抱歉，请您注册真实会员！");
                                            return;
                                        }
                                        presenter.postBanalceTransferFG("", "fg", "hg", GameShipHelper.getIntegerString(text));
                                    }
                                }
                            })
                            .show();

                }
            });
        }

    }


    @Override
    public void postBetRecordResult(BetRecordResult message) {
        GameLog.log("总共充值多少：" + message.getTotal());

    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        //balancePlatformList.clear();
        balancePlatformList.set(1,personBalance.getBalance_ag());
        balancePlatformAdapter.notifyDataSetChanged();
        typeArgsHG = GameShipHelper.formatMoney(personBalance.getBalance_hg());
        backTitleBalancePlatform.setMoreText(typeArgsHG);
        EventBus.getDefault().post(new UserMoneyEvent(typeArgsHG));
        //lvBalancePlatform.setAdapter(new BalancePlatformAdapter(getContext(),R.layout.item_balance_platform,balancePlatformList));
        //balancePlatformAdapter.notifyDataSetInvalidated();
        //presenter.postPersonBalanceKY("","");
    }

    @Override
    public void postPersonBalanceCPResult(KYBalanceResult personBalance) {
        //balancePlatformList.clear();
        balancePlatformList.set(0,personBalance.getGmcp_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        //lvBalancePlatform.setAdapter(new BalancePlatformAdapter(getContext(),R.layout.item_balance_platform,balancePlatformList));
        //balancePlatformAdapter.notifyDataSetInvalidated();
        //presenter.postPersonBalanceKY("","");
    }

    @Override
    public void postPersonBalanceKYResult(KYBalanceResult personBalance) {
        GameLog.log("postPersonBalanceKYResult "+personBalance.toString());
        balancePlatformList.set(2,personBalance.getKy_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        //presenter.postPersonBalanceHG("","");
    }

    @Override
    public void postPersonBalanceHGResult(KYBalanceResult personBalance) {
        GameLog.log("皇冠棋牌的余额 ");
        balancePlatformList.set(3,personBalance.getFf_balance());
        balancePlatformAdapter.notifyDataSetChanged();
    }

    @Override
    public void postPersonBalanceVGResult(KYBalanceResult personBalance) {
        GameLog.log("VG棋牌的余额 ");
        balancePlatformList.set(4,personBalance.getVg_balance());
        balancePlatformAdapter.notifyDataSetChanged();
    }

    @Override
    public void postPersonBalanceLYResult(KYBalanceResult personBalance) {
        balancePlatformList.set(5,personBalance.getLy_balance());
        balancePlatformAdapter.notifyDataSetChanged();
    }

    @Override
    public void postPersonBalanceMGResult(KYBalanceResult personBalance) {
        balancePlatformList.set(6,personBalance.getMg_balance());
        balancePlatformAdapter.notifyDataSetChanged();
    }

    @Override
    public void postPersonBalanceAGResult(KYBalanceResult personBalance) {
        balancePlatformList.set(7,personBalance.getAvia_balance());
        balancePlatformAdapter.notifyDataSetChanged();
    }

    @Override
    public void postPersonBalanceOGResult(KYBalanceResult personBalance) {
        balancePlatformList.set(8,personBalance.getOg_balance());
        balancePlatformAdapter.notifyDataSetChanged();
    }

    @Override
    public void postPersonBalanceCQResult(KYBalanceResult personBalance) {
        balancePlatformList.set(9,personBalance.getCq_balance());
        balancePlatformAdapter.notifyDataSetChanged();
    }

    @Override
    public void postPersonBalanceMWResult(KYBalanceResult personBalance) {
        balancePlatformList.set(10,personBalance.getMw_balance());
        balancePlatformAdapter.notifyDataSetChanged();
    }

    @Override
    public void postPersonBalanceFGResult(KYBalanceResult personBalance) {
        balancePlatformList.set(11,personBalance.getFg_balance());
        balancePlatformAdapter.notifyDataSetChanged();
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        presenter.postPersonBalance("","");
        presenter.postPersonBalanceCP("","");
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Override
    public void setPresenter(BalancePlatformContract.Presenter presenter) {

        this.presenter = presenter;
    }

}
