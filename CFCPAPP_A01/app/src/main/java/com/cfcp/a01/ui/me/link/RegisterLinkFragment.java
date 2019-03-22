package com.cfcp.a01.ui.me.link;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.RegisterMeResult;
import com.cfcp.a01.ui.me.register.CancelRegisterEvent;
import com.cfcp.a01.ui.me.register.RegisterNameEvent;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import butterknife.BindView;
import butterknife.OnClick;

public class RegisterLinkFragment extends BaseFragment implements RegisterLinkContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.registerLinkBack)
    NTitleBar registerLinkBack;
    @BindView(R.id.registerLinkChannel)
    TextView registerLinkChannel;
    @BindView(R.id.registerLinkType)
    TextView registerLinkType;
    @BindView(R.id.registerLinkTime)
    TextView registerLinkTime;
    @BindView(R.id.registerLinkFund)
    TextView registerLinkFund;
    @BindView(R.id.registerLinkQQ)
    EditText registerLinkQQ;
    @BindView(R.id.registerLinkStyle)
    TabLayout registerLinkStyle;
    @BindView(R.id.registerLinkSubmit)
    TextView registerLinkSubmit;
    private String typeArgs2, typeArgs3;
    RegisterLinkContract.Presenter presenter;
    OptionsPickerView typeOptionsPickerType;
    OptionsPickerView typeOptionsPickerChannel;
    OptionsPickerView typeOptionsPickerTime;
    OptionsPickerView typeOptionsPickerFund;
    String is_agent="0",series_prize_group_json;
    String type,classic_prize;
    List<RegisterMeResult.AAllPossibleAgentPrizeGroupsBean> aAllPossiblePrizeGroupsBeans = new ArrayList<>();


    static List<String> typeOptionsListType  = new ArrayList<>();
    static List<String> typeOptionsListChannel  = new ArrayList<>();
    static List<String> typeOptionsListTime  = new ArrayList<>();
    static {
        typeOptionsListType.add("代理");
        typeOptionsListType.add("会员");

        typeOptionsListChannel.add("论坛");
        typeOptionsListChannel.add("QQ群");

        typeOptionsListTime.add("1天");
        typeOptionsListTime.add("7天");
        typeOptionsListTime.add("30天");
        typeOptionsListTime.add("90天");
        typeOptionsListTime.add("永久有效");

    }
    public static RegisterLinkFragment newInstance(String deposit_mode, String money) {
        RegisterLinkFragment betFragment = new RegisterLinkFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_register_link;
    }


    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs2 = getArguments().getString(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }


    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        registerLinkBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        presenter.getFundGroup();
        initTabView();
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    private void initTabView() {
        registerLinkStyle.addTab(registerLinkStyle.newTab().setText("链接开户"));
        registerLinkStyle.addTab(registerLinkStyle.newTab().setText("链接管理"));
        registerLinkStyle.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                hideKeyboard();
                switch (tab.getPosition()){
                    case 0:
                        break;
                    case 1:
                        break;
                }

            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {

            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {

            }
        });

        typeOptionsPickerType = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = typeOptionsListType.get(options1);
                if(options1==0){
                    is_agent = "0";
                }else{
                    is_agent = "1";
                }
                registerLinkType.setText(text);
            }
        }).build();
        typeOptionsPickerType.setPicker(typeOptionsListType);

        typeOptionsPickerChannel= new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = typeOptionsListChannel.get(options1);
                if(options1==0){
                    is_agent = "0";
                }else{
                    is_agent = "1";
                }
                registerLinkChannel.setText(text);
            }
        }).build();
        typeOptionsPickerChannel.setPicker(typeOptionsListChannel);

        typeOptionsPickerTime= new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = typeOptionsListTime.get(options1);
                if(options1==0){
                    is_agent = "0";
                }else{
                    is_agent = "1";
                }
                registerLinkTime.setText(text);
            }
        }).build();
        typeOptionsPickerTime.setPicker(typeOptionsListTime);

    }

    @Subscribe
    public void onEventMain(RegisterNameEvent registerNameEvent){
        Map<String, String> map = new HashMap<String, String>();
        map.put(type,classic_prize);
        series_prize_group_json = JSON.toJSONString(map);
        //presenter.getRegisterFundGroup(is_agent,"2",registerNameEvent.nickName, registerNameEvent.accountName, registerNameEvent.pwd, series_prize_group_json);
    }

    //请求数据接口
    private void onRequsetData() {
        String liankQQ = registerLinkQQ.getText().toString().trim();
        if (Check.isEmpty(liankQQ)) {
            showMessage("请填写推广QQ");
            return;
        }

        if("选择其他奖金组".equals(registerLinkFund.getText().toString())){
            showMessage("请选择奖金组");
            return;
        }
        //RegisterShowDialog.newInstance(new RegisterNameEvent(pwd2,is_agent,liankQQ,account,classic_prize),"").show(getFragmentManager());
        //

    }


    @Override
    public void getFundGroupResult(RegisterMeResult registerLinkResult) {
        //转账前渠道确认
        GameLog.log("设置真实姓名 成功");
        aAllPossiblePrizeGroupsBeans = registerLinkResult.getAAllPossibleAgentPrizeGroups();
        typeOptionsPickerFund = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = aAllPossiblePrizeGroupsBeans.get(options1).getPickerViewText();
                type = aAllPossiblePrizeGroupsBeans.get(options1).getType()+"";
                classic_prize = aAllPossiblePrizeGroupsBeans.get(options1).getClassic_prize()+"";
                series_prize_group_json = "{"+text.replace("--",":")+"}";
                registerLinkFund.setText(text);
            }
        }).build();
        typeOptionsPickerFund.setPicker(aAllPossiblePrizeGroupsBeans);

    }

    @Override
    public void getRegisterFundGroupResult() {
        EventBus.getDefault().post(new CancelRegisterEvent("cancel"));
        showMessage("恭喜您，注册成功！");
        finish();
    }

    @Override
    public void setPresenter(RegisterLinkContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
    }


    @OnClick({R.id.registerLinkType,R.id.registerLinkChannel,R.id.registerLinkTime, R.id.registerLinkFund, R.id.registerLinkSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.registerLinkType:
                typeOptionsPickerType.show();
                break;
            case R.id.registerLinkChannel:
                typeOptionsPickerChannel.show();
                break;
            case R.id.registerLinkTime:
                typeOptionsPickerTime.show();
                break;
            case R.id.registerLinkFund:
                hideKeyboard();
                typeOptionsPickerFund.show();
                break;
            case R.id.registerLinkSubmit:
                onRequsetData();
                break;
        }
    }


}
