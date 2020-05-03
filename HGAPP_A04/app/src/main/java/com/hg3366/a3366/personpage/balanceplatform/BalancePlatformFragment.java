package com.hg3366.a3366.personpage.balanceplatform;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.DialogFragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.text.InputType;
import android.view.View;

import com.hg3366.a3366.HGApplication;
import com.hg3366.a3366.Injections;
import com.hg3366.a3366.R;
import com.hg3366.a3366.base.HGBaseFragment;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.common.adapters.AutoSizeRVAdapter;
import com.hg3366.a3366.common.util.ACache;
import com.hg3366.a3366.common.util.GameShipHelper;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.a3366.common.widgets.NTitleBar;
import com.hg3366.a3366.data.BalanceTransferData;
import com.hg3366.a3366.data.BetRecordResult;
import com.hg3366.a3366.data.KYBalanceResult;
import com.hg3366.a3366.data.PersonBalanceResult;
import com.hg3366.a3366.homepage.UserMoneyEvent;
import com.hg3366.common.util.Check;
import com.hg3366.common.util.GameLog;
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
    static List<BalanceTransferData> gtypeList  = new ArrayList<BalanceTransferData>();
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
        balancePlatformList.add("加载中");
        balancePlatformList.add("加载中");
        /*balancePlatformList.add("加载中");
        gtypeList.add(new BalanceTransferData("110","皇冠体育","sc"));*/
        gtypeList.add(new BalanceTransferData("0","彩票平台","cp"));
        gtypeList.add(new BalanceTransferData("1","AG平台","ag"));
        gtypeList.add(new BalanceTransferData("2","开元棋牌","ky"));
        gtypeList.add(new BalanceTransferData("3","快乐棋牌","kl"));
        gtypeList.add(new BalanceTransferData("4","VG棋牌","vg"));
        gtypeList.add(new BalanceTransferData("5","乐游棋牌","ly"));
        gtypeList.add(new BalanceTransferData("6","MG电子","mg"));
        gtypeList.add(new BalanceTransferData("7","泛亚电竞","avia"));
        gtypeList.add(new BalanceTransferData("8","OG视讯","og"));
        gtypeList.add(new BalanceTransferData("9","CQ9电子","cq"));
        gtypeList.add(new BalanceTransferData("10","MW电子","mw"));
        gtypeList.add(new BalanceTransferData("11","FG电子","fg"));
        gtypeList.add(new BalanceTransferData("12","BBIN视讯","bbin"));
        gtypeList.add(new BalanceTransferData("13","雷火电竞","fire"));
        balancePlatformAdapter = new BalancePlatformAdapter(getContext(),R.layout.item_balance_platform,balancePlatformList);
        LinearLayoutManager mLayoutManager1 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        lvBalancePlatform.setLayoutManager(mLayoutManager1);
        lvBalancePlatform.setHasFixedSize(true);
        lvBalancePlatform.setNestedScrollingEnabled(false);
        lvBalancePlatform.setAdapter(balancePlatformAdapter);
        //presenter.postPersonBalanceTY("","");
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
        presenter.postPersonBalanceBBIN("","");
        presenter.postPersonBalanceFire("","");
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
            viewHolder.setText(R.id.itemBalancePlatformName,gtypeList.get(postion).getCnName());
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

                                    switch (gtypeList.get(postion).getEnName()){
                                        case "bbin":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferBBIN("", "hg", "bbin", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "sc":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferTY("", "hg", "sc", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "cp":
                                            presenter.postBanalceTransferCP("", "fundLimitTrans", "hg", "gmcp", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "ag":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransfer("", "hg", "ag", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "ky":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferKY("", "hg", "ky", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "kl":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferHG("", "hg", "kl", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "vg":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferVG("", "hg", "vg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "ly":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferLY("", "hg", "ly", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "mg":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferMG("", "hg", "mg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "avia":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferAG("", "hg", "avia", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "og":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferOG("", "hg", "og", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "cq":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferCQ("", "hg", "cq", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "mw":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferMW("", "hg", "mw", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "fg":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferFG("", "hg", "fg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "fire":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferFire("", "hg", "fire", GameShipHelper.getIntegerString(text));
                                            break;
                                    }
                                    /*if (postion == 0) {//彩票转入
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
                                        presenter.postBanalceTransferHG("", "hg", "kl", GameShipHelper.getIntegerString(text));
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
                                    }else   if(postion == 8){
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
                                    }*/

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
                                    switch (gtypeList.get(postion).getEnName()){
                                        case "bbin":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferBBIN("", "bbin", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "sc":
                                            presenter.postBanalceTransferTY("","sc","hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "cp":
                                            presenter.postBanalceTransferCP("","fundLimitTrans","gmcp","hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "ag":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransfer("","ag","hg",GameShipHelper.getIntegerString(text));
                                            break;
                                        case "ky":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferKY("", "ky", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "kl":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferHG("", "kl", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "vg":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferVG("", "vg", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "ly":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferLY("", "ly", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "mg":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferMG("", "mg", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "avia":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferAG("", "avia", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "og":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferOG("", "og", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "cq":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferCQ("", "cq", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "mw":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferMW("", "mw", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "fg":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferFG("", "fg", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                        case "fire":
                                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                                showMessage("非常抱歉，请您注册真实会员！");
                                                return;
                                            }
                                            presenter.postBanalceTransferFire("", "fire", "hg", GameShipHelper.getIntegerString(text));
                                            break;
                                    }

                                    /*if(postion==0){//彩票转出
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
                                        presenter.postBanalceTransferHG("", "kl", "hg", GameShipHelper.getIntegerString(text));
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
                                    }*/
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

    private void onSetMoreText(KYBalanceResult kyBalanceResult){
        typeArgsHG = GameShipHelper.formatMoney(kyBalanceResult.getHg_balance());
        backTitleBalancePlatform.setMoreText(typeArgsHG);
        EventBus.getDefault().post(new UserMoneyEvent(typeArgsHG));
    }

    @Override
    public void postPersonBalanceTYResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(0,kyBalanceResult.getSc_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceCPResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(0,kyBalanceResult.getGmcp_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(1,kyBalanceResult.getBalance_ag());
        balancePlatformAdapter.notifyDataSetChanged();
        typeArgsHG = GameShipHelper.formatMoney(kyBalanceResult.getBalance_hg());
        backTitleBalancePlatform.setMoreText(typeArgsHG);
        EventBus.getDefault().post(new UserMoneyEvent(typeArgsHG));
    }

    @Override
    public void postPersonBalanceKYResult(KYBalanceResult kyBalanceResult) {
        GameLog.log("postPersonBalanceKYResult "+kyBalanceResult.toString());
        balancePlatformList.set(2,kyBalanceResult.getKy_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceHGResult(KYBalanceResult kyBalanceResult) {
        GameLog.log("皇冠棋牌的余额 ");
        balancePlatformList.set(3,kyBalanceResult.getFf_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceVGResult(KYBalanceResult kyBalanceResult) {
        GameLog.log("VG棋牌的余额 ");
        balancePlatformList.set(4,kyBalanceResult.getVg_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceLYResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(5,kyBalanceResult.getLy_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceMGResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(6,kyBalanceResult.getMg_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceAGResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(7,kyBalanceResult.getAvia_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceOGResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(8,kyBalanceResult.getOg_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceCQResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(9,kyBalanceResult.getCq_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceMWResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(10,kyBalanceResult.getMw_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceFGResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(11,kyBalanceResult.getFg_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceBBINResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(12,kyBalanceResult.getBbin_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void postPersonBalanceFireResult(KYBalanceResult kyBalanceResult) {
        balancePlatformList.set(13,kyBalanceResult.getFire_balance());
        balancePlatformAdapter.notifyDataSetChanged();
        onSetMoreText(kyBalanceResult);
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
       /* presenter.postPersonBalance("","");
        presenter.postPersonBalanceCP("","");*/
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
