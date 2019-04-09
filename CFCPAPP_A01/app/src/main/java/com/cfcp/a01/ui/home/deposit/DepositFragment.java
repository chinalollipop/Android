package com.cfcp.a01.ui.home.deposit;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.text.Html;
import android.util.TypedValue;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.http.util.Md5Utils;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.DepositMethodResult;
import com.cfcp.a01.data.DepositTypeResult;
import com.cfcp.a01.data.LoginResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class DepositFragment extends BaseFragment implements DepositContract.View {
    DepositContract.Presenter presenter;
    @BindView(R.id.depositBack)
    NTitleBar depositBack;
    @BindView(R.id.depositUserMoney)
    TextView depositUserMoney;
    @BindView(R.id.depositInputMoneyEt)
    EditText depositInputMoneyEt;
    @BindView(R.id.depositInputMoneyRView)
    RecyclerView depositInputMoneyRView;
    @BindView(R.id.depositMothedTab)
    TabLayout depositMothedTab;
    @BindView(R.id.depositMothedRView)
    RecyclerView depositMothedRView;
    @BindView(R.id.depositSubmit)
    TextView depositSubmit;
    List<DepositMethodResult.AlipayAndWeiXinBean> AlipayList =  new ArrayList<>();

    List<DepositMethodResult.AlipayAndWeiXinBean> WeixinList =  new ArrayList<>();
    List<DepositMethodResult.AlipayAndWeiXinBean> bankList =  new ArrayList<>();

    List<DepositMethodResult.AlipayAndWeiXinBean> yunshanfuList =  new ArrayList<>();
    DepositMothedRViewAdapter depositMothedRViewAdapter;
    //用户输入框值的初始化
    ArrayList<DepositInputEvent> depositInputMoneyList = new ArrayList<>();

    //deposit_mode  1 银行卡或者二维码，2 第三方
    String deposit_mode="1",payment_platform_id="";

    boolean isBankAccount ;

    public static DepositFragment newInstance() {
        DepositFragment loginFragment = new DepositFragment();
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_deposit;
    }

    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#e13f51'>" + sign+"</font>";
    }

    private View tabCustomView(String name){
        View newtab =  LayoutInflater.from(getActivity()).inflate(R.layout.item_tab_deposit,null);
        TextView tv = newtab.findViewById(R.id.tabText);
        tv.setText(name);
        return newtab;
    }


    private void updateTabView(TabLayout.Tab tab, boolean isSelect) {
        //找到自定义视图的控件ID
        TextView  tv_tab = tab.getCustomView().findViewById(R.id.tabText);
        if(isSelect) {
            //设置标签选中
            tv_tab.setSelected(true);
            //选中后字体变大
            tv_tab.setTextSize(TypedValue.COMPLEX_UNIT_PX,getResources().getDimensionPixelSize(R.dimen.sp_18));
            tv_tab.setTextColor(getResources().getColor(R.color.text_bet_issue));
        }else{
            //设置标签取消选中
            tv_tab.setSelected(false);
            //恢复为默认字体大小
            tv_tab.setTextSize(TypedValue.COMPLEX_UNIT_PX,getResources().getDimensionPixelSize(R.dimen.sp_14));
            tv_tab.setTextColor(getResources().getColor(R.color.text_main));
        }
    }


    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        //请求出存款方式
        presenter.getDepositMethod("");
        depositBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });

        //设置适配器
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(),  OrientationHelper.VERTICAL, false);
        depositMothedRView.setLayoutManager(linearLayoutManager);
        depositMothedRView.setHasFixedSize(true);
        depositMothedRView.setNestedScrollingEnabled(false);

        //初始化适配器输入金额的值
        depositInputMoneyList.add(new DepositInputEvent("10",true));
        depositInputMoneyList.add(new DepositInputEvent("100",false));
        depositInputMoneyList.add(new DepositInputEvent("500",false));
        depositInputMoneyList.add(new DepositInputEvent("1000",false));
        depositInputMoneyList.add(new DepositInputEvent("3000",false));
        depositInputMoneyList.add(new DepositInputEvent("5000",false));
        depositInputMoneyList.add(new DepositInputEvent("10000",false));
        depositInputMoneyList.add(new DepositInputEvent("20000",false));

        depositUserMoney.setText(Html.fromHtml("余款："+onMarkRed(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_BALANCE))+"元"));

        depositMothedTab.addTab(depositMothedTab.newTab().setCustomView(tabCustomView("支付宝")));
        depositMothedTab.addTab(depositMothedTab.newTab().setCustomView(tabCustomView("微信")));
        depositMothedTab.addTab(depositMothedTab.newTab().setCustomView(tabCustomView("银行转账")));
        depositMothedTab.addTab(depositMothedTab.newTab().setCustomView(tabCustomView("云闪付")));
       /* depositMothedTab.addTab(depositMothedTab.newTab().setCustomView(tabCustomView("QQ")));
        depositMothedTab.addTab(depositMothedTab.newTab().setCustomView(tabCustomView("银行转账")));
        depositMothedTab.addTab(depositMothedTab.newTab().setCustomView(tabCustomView("在线网银")));*/

        depositMothedTab.addOnTabSelectedListener(new TabLayout.BaseOnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                updateTabView(tab,true);
                payment_platform_id = "";
                switch (tab.getPosition()) {
                    case 0:
                        deposit_mode = "1";
                        if(Check.isNull(AlipayList)){
                            depositMothedRViewAdapter = new DepositMothedRViewAdapter(R.layout.item_deposit_method,AlipayList);
                            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
                            depositMothedRViewAdapter.setEmptyView(view);
                            depositMothedRView.setAdapter(depositMothedRViewAdapter);
                            return;
                        }
                        for(int k=0;k<AlipayList.size();++k){
                            AlipayList.get(k).setChecked(false);
                        }
                        depositMothedRViewAdapter = new DepositMothedRViewAdapter(R.layout.item_deposit_method,AlipayList);
                        depositMothedRViewAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                            @Override
                            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {

                                if(AlipayList.get(position).isChecked()){
                                    AlipayList.get(position).setChecked(false);
                                    payment_platform_id = "";
                                }else{
                                    for(int k=0;k<AlipayList.size();++k){
                                        AlipayList.get(k).setChecked(false);
                                    }
                                    payment_platform_id = AlipayList.get(position).getId();
                                    deposit_mode = AlipayList.get(position).getType()+"";
                                    AlipayList.get(position).setChecked(true);
                                }
                                if(AlipayList.get(position).getDisplay_name().contains("银行")){
                                    isBankAccount = true;
                                }else{
                                    isBankAccount = false;
                                }
                                adapter.notifyDataSetChanged();
                            }
                        });
                        depositMothedRView.setAdapter(depositMothedRViewAdapter);

                        break;
                    case 1:
                        deposit_mode = "1";
                        if(Check.isNull(WeixinList)){
                            depositMothedRViewAdapter = new DepositMothedRViewAdapter(R.layout.item_deposit_method,WeixinList);
                            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
                            depositMothedRViewAdapter.setEmptyView(view);
                            depositMothedRView.setAdapter(depositMothedRViewAdapter);
                            return;
                        }
                        for(int k=0;k<WeixinList.size();++k){
                            WeixinList.get(k).setChecked(false);
                        }
                        depositMothedRViewAdapter  = new DepositMothedRViewAdapter(R.layout.item_deposit_method,WeixinList);
                        depositMothedRViewAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                            @Override
                            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                                if(WeixinList.get(position).isChecked()){
                                    WeixinList.get(position).setChecked(false);
                                    payment_platform_id = "";
                                }else{
                                    for(int k=0;k<WeixinList.size();++k){
                                        WeixinList.get(k).setChecked(false);
                                    }
                                    payment_platform_id = WeixinList.get(position).getId();
                                    deposit_mode = WeixinList.get(position).getType()+"";
                                    WeixinList.get(position).setChecked(true);
                                }
                                if(WeixinList.get(position).getDisplay_name().contains("银行")){
                                    isBankAccount = true;
                                }else{
                                    isBankAccount = false;
                                }
                                adapter.notifyDataSetChanged();
                            }
                        });
                        depositMothedRView.setAdapter(depositMothedRViewAdapter);

                        break;
                    case 2:
                        deposit_mode = "1";
                        if(Check.isNull(bankList)){
                            depositMothedRViewAdapter = new DepositMothedRViewAdapter(R.layout.item_deposit_method,bankList);
                            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
                            depositMothedRViewAdapter.setEmptyView(view);
                            depositMothedRView.setAdapter(depositMothedRViewAdapter);
                            return;
                        }
                        for(int k=0;k<bankList.size();++k){
                            bankList.get(k).setChecked(false);
                        }
                        depositMothedRViewAdapter = new DepositMothedRViewAdapter(R.layout.item_deposit_method,bankList);
                        depositMothedRViewAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                            @Override
                            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {

                                if(bankList.get(position).isChecked()){
                                    bankList.get(position).setChecked(false);
                                    payment_platform_id = "";
                                }else{
                                    for(int k=0;k<bankList.size();++k){
                                        bankList.get(k).setChecked(false);
                                    }
                                    payment_platform_id = bankList.get(position).getId();
                                    deposit_mode = bankList.get(position).getType()+"";
                                    bankList.get(position).setChecked(true);
                                }
                                if(bankList.get(position).getDisplay_name().contains("银行")){
                                    isBankAccount = true;
                                }else{
                                    isBankAccount = false;
                                }
                                adapter.notifyDataSetChanged();
                            }
                        });
                        depositMothedRView.setAdapter(depositMothedRViewAdapter);

                        break;
                    case 3:
                        deposit_mode = "2";
                        if(Check.isNull(yunshanfuList)){
                            depositMothedRViewAdapter = new DepositMothedRViewAdapter(R.layout.item_deposit_method,yunshanfuList);
                            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
                            depositMothedRViewAdapter.setEmptyView(view);
                            depositMothedRView.setAdapter(depositMothedRViewAdapter);
                            return;
                        }
                        for(int k=0;k<yunshanfuList.size();++k){
                            yunshanfuList.get(k).setChecked(false);
                        }
                        depositMothedRViewAdapter = new DepositMothedRViewAdapter(R.layout.item_deposit_method,yunshanfuList);
                        depositMothedRViewAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                            @Override
                            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {

                                if(yunshanfuList.get(position).isChecked()){
                                    yunshanfuList.get(position).setChecked(false);
                                    payment_platform_id = "";
                                }else{
                                    for(int k=0;k<yunshanfuList.size();++k){
                                        yunshanfuList.get(k).setChecked(false);
                                    }
                                    payment_platform_id = yunshanfuList.get(position).getId();
                                    deposit_mode = yunshanfuList.get(position).getType()+"";
                                    yunshanfuList.get(position).setChecked(true);
                                }
                                if(yunshanfuList.get(position).getDisplay_name().contains("银行")){
                                    isBankAccount = true;
                                }else{
                                    isBankAccount = false;
                                }
                                adapter.notifyDataSetChanged();
                            }
                        });
                        depositMothedRView.setAdapter(depositMothedRViewAdapter);

                        break;
                    case 4:
                        break;
                }
            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {
                updateTabView(tab,false);
            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {

            }
        });

        TextView  tv_tab = depositMothedTab.getTabAt(0).getCustomView().findViewById(R.id.tabText);
        //设置标签选中
        tv_tab.setSelected(true);
        //选中后字体变大
        tv_tab.setTextSize(TypedValue.COMPLEX_UNIT_PX,getResources().getDimensionPixelSize(R.dimen.sp_18));
        tv_tab.setTextColor(getResources().getColor(R.color.text_bet_issue));

        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 4, OrientationHelper.VERTICAL, false);
        depositInputMoneyRView.setLayoutManager(gridLayoutManager);
        depositInputMoneyRView.setHasFixedSize(true);
        depositInputMoneyRView.setNestedScrollingEnabled(false);
        DepositInputMoneyRViewAdapter depositInputMoneyRViewAdapter = new DepositInputMoneyRViewAdapter(R.layout.item_deposit_input,depositInputMoneyList);
        depositInputMoneyRView.setAdapter(depositInputMoneyRViewAdapter);
        depositInputMoneyRViewAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                depositInputMoneyEt.setText(depositInputMoneyList.get(position).money);
                for(int k=0;k<8;++k){
                    depositInputMoneyList.get(k).isCheck =false;
                }
                depositInputMoneyList.get(position).isCheck = true;
                adapter.notifyDataSetChanged();
            }
        });
    }

    class DepositInputMoneyRViewAdapter extends BaseQuickAdapter<DepositInputEvent, BaseViewHolder> {

        public DepositInputMoneyRViewAdapter(int layoutId, @Nullable List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final DepositInputEvent data) {
            holder.setText(R.id.itemDepositInputText, data.money).
                    addOnClickListener(R.id.itemDepositInputText);
            if(data.isCheck){
                holder.setTextColor(R.id.itemDepositInputText,getResources().getColor(R.color.text_bet_issue));
                holder.setBackgroundRes(R.id.itemDepositInputText,R.drawable.bg_deposit_input_checked);
            }else{
                holder.setTextColor(R.id.itemDepositInputText,getResources().getColor(R.color.textview_marque));
                holder.setBackgroundRes(R.id.itemDepositInputText,R.drawable.bg_deposit_input);
            }
        }
    }

    class DepositMothedRViewAdapter extends BaseQuickAdapter<DepositMethodResult.AlipayAndWeiXinBean, BaseViewHolder> {

        public DepositMothedRViewAdapter(int layoutId, @Nullable List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final DepositMethodResult.AlipayAndWeiXinBean data) {
            holder.setText(R.id.itemDepositMethodName, data.getDisplay_name()).
                    setText(R.id.itemDepositMethodDesc, data.getBrief_description()).
                    addOnClickListener(R.id.itemDepositMethodLay);
            /*holder.setOnClickListener(R.id.itemDepositMethodLay, new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if(data.isChecked()){
                        data.setChecked(false);
                    }else{
                        for(int k=0;k<AlipayList.size();++k){
                            AlipayList.get(k).setChecked(false);
                        }
                        data.setChecked(true);
                    }
                    notifyDataSetChanged();
                }
            });*/
            if(data.isChecked()){
                holder.setBackgroundRes(R.id.itemDepositMethodChecked,R.mipmap.deposit_method_checked);
            }else{
                holder.setBackgroundRes(R.id.itemDepositMethodChecked,R.mipmap.deposit_method_normal);
            }
        }
    }



    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("================注册页需要消失的================");
        finish();
    }

    private void onSubmit() {
        String uName = depositInputMoneyEt.getText().toString().trim();
        String uPwd = depositInputMoneyEt.getText().toString().trim();

        if (Check.isEmpty(uName)) {
            showMessage("请输入账号");
        }
        if (Check.isEmpty(uPwd)) {
            showMessage("请输入密码");
        }
        uPwd = Md5Utils.getMd5(Md5Utils.getMd5(Md5Utils.getMd5(uName + uPwd)));

        //
    }

    @Override
    public void getDepositMethodResult(DepositMethodResult depositMethodResult) {
        //保存用户登录成功之后的消息
        GameLog.log("支付宝的方式有几种"+depositMethodResult.getAlipay().size());
        GameLog.log("微信的方式有几种"+depositMethodResult.getWeixin().size());
        AlipayList = depositMethodResult.getAlipay();
        WeixinList = depositMethodResult.getWeixin();
        bankList = depositMethodResult.getBank();
        yunshanfuList = depositMethodResult.getYunshanfu();

        depositMothedRViewAdapter = new DepositMothedRViewAdapter(R.layout.item_deposit_method,AlipayList);
        depositMothedRViewAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                if(AlipayList.get(position).isChecked()){
                    payment_platform_id = "";
                    AlipayList.get(position).setChecked(false);
                }else{
                    for(int k=0;k<AlipayList.size();++k){
                        AlipayList.get(k).setChecked(false);
                    }
                    payment_platform_id = AlipayList.get(position).getId();
                    AlipayList.get(position).setChecked(true);
                }

                if(AlipayList.get(position).getDisplay_name().contains("银行")){
                    isBankAccount = true;
                }else{
                    isBankAccount = false;
                }

                adapter.notifyDataSetChanged();
            }
        });
        depositMothedRView.setAdapter(depositMothedRViewAdapter);
    }

    @Override
    public void getDepositVerifyResult(DepositTypeResult depositTypeResult) {
        //转账前渠道确认
        GameLog.log("获取转账前渠道确认接口 成功  "+isBankAccount);
        EventBus.getDefault().post(new StartBrotherEvent(DepositSubmitFragment.newInstance(depositTypeResult.getaPlatform(),depositTypeResult.getAPaymentPlatformBankCard(),
                isBankAccount?"1":"0",depositInputMoneyEt.getText().toString().trim())));
    }


    @Override
    public void setPresenter(DepositContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @OnClick(R.id.depositSubmit)
    public void onViewClicked() {
        //对用户选择方式存款的下一步
        if(Check.isEmpty(deposit_mode)||Check.isEmpty(payment_platform_id)){
            showMessage("请选择支付方式");
        }else{
            presenter.getDepositVerify(deposit_mode,payment_platform_id);
        }
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        if(Check.isNull(AlipayList)){
            presenter.getDepositMethod("");
        }
    }
}
