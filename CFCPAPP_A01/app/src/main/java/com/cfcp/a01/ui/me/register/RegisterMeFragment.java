package com.cfcp.a01.ui.me.register;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.view.View;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.LinearLayout;
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

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import butterknife.BindView;
import butterknife.OnClick;

public class RegisterMeFragment extends BaseFragment implements RegisterMeContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.registerMeBack)
    NTitleBar registerMeBack;
    @BindView(R.id.registerMeType)
    TextView registerMeType;
    @BindView(R.id.registerMeNick)
    EditText registerMeNick;
    @BindView(R.id.registerMeAccount)
    EditText registerMeAccount;
    @BindView(R.id.registerMePwd)
    EditText registerMePwd;
    @BindView(R.id.registerMePwd2)
    EditText registerMePwd2;
    @BindView(R.id.registerMeInfrom)
    CheckBox registerMeInfrom;
    @BindView(R.id.registerMeStyle)
    TabLayout registerMeStyle;
    @BindView(R.id.registerMeSetFundLay1)
    LinearLayout registerMeSetFundLay1;
    @BindView(R.id.registerMeSetFund)
    TextView registerMeSetFund;
    @BindView(R.id.registerMeSetFundLay2)
    LinearLayout registerMeSetFundLay2;
    @BindView(R.id.registerMeSubmit)
    TextView registerMeSubmit;
    private String typeArgs2, typeArgs3;
    RegisterMeContract.Presenter presenter;
    OptionsPickerView typeOptionsPicker;
    OptionsPickerView typeOptionsPickerFund;
    String is_agent="0",prize_group_id,series_prize_group_json;
    String type,classic_prize;
    List<RegisterMeResult.AAllPossibleAgentPrizeGroupsBean> aAllPossiblePrizeGroupsBeans = new ArrayList<>();
    List<RegisterMeResult.AAllPossibleAgentPrizeGroupsBean> aAllPossiblePrizeGroupsBeans0 = new ArrayList<>();
    List<RegisterMeResult.AAllPossibleAgentPrizeGroupsBean> aAllPossiblePrizeGroupsBeans1 = new ArrayList<>();


    static List<String> typeOptionsList  = new ArrayList<>();
    static {
        typeOptionsList.add("代理");
        typeOptionsList.add("会员");

    }
    public static RegisterMeFragment newInstance(String deposit_mode, String money) {
        RegisterMeFragment betFragment = new RegisterMeFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_register_me;
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
        registerMeBack.setBackListener(new View.OnClickListener() {
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
        registerMeStyle.addTab(registerMeStyle.newTab().setText("配额奖金组"));
        registerMeStyle.addTab(registerMeStyle.newTab().setText("其他奖金组"));
        registerMeStyle.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                hideKeyboard();
                switch (tab.getPosition()){
                    case 0:
                        registerMeSetFundLay1.setVisibility(View.VISIBLE);
                        registerMeSetFundLay2.setVisibility(View.GONE);
                        break;
                    case 1:
                        registerMeSetFundLay1.setVisibility(View.GONE);
                        registerMeSetFundLay2.setVisibility(View.VISIBLE);
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

        typeOptionsPicker = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = typeOptionsList.get(options1);
                if(options1==0){
                    is_agent = "0";
                }else{
                    is_agent = "1";
                }
                if(is_agent.equals("1")){
                    aAllPossiblePrizeGroupsBeans = aAllPossiblePrizeGroupsBeans1;
                }else{
                    aAllPossiblePrizeGroupsBeans = aAllPossiblePrizeGroupsBeans0;
                }
                type = aAllPossiblePrizeGroupsBeans.get(0).getType()+"";
                classic_prize = aAllPossiblePrizeGroupsBeans.get(0).getClassic_prize()+"";
                prize_group_id =aAllPossiblePrizeGroupsBeans.get(0).getId()+"";
                registerMeSetFund.setText(aAllPossiblePrizeGroupsBeans.get(0).getPickerViewText());
                typeOptionsPickerFund = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

                    @Override
                    public void onOptionsSelect(int options1, int options2, int options3, View v) {
                        //1、tab做相应的切换
                        // 2、下面做查询数据的请求和展示
                        String text = aAllPossiblePrizeGroupsBeans.get(options1).getPickerViewText();
                        type = aAllPossiblePrizeGroupsBeans.get(options1).getType()+"";
                        classic_prize = aAllPossiblePrizeGroupsBeans.get(options1).getClassic_prize()+"";
                        prize_group_id = aAllPossiblePrizeGroupsBeans.get(options1).getId()+"";
                        series_prize_group_json = "{"+text.replace("--",":")+"}";
                        registerMeSetFund.setText(text);
                    }
                }).build();
                typeOptionsPickerFund.setPicker(aAllPossiblePrizeGroupsBeans);

                registerMeType.setText(text);
            }
        }).build();
        typeOptionsPicker.setPicker(typeOptionsList);

    }

    @Subscribe
    public void onEventMain(RegisterNameEvent registerNameEvent){
        Map<String, String> map = new HashMap<String, String>();
        map.put(type,classic_prize);
        series_prize_group_json = JSON.toJSONString(map);
        presenter.getRegisterFundGroup(is_agent,prize_group_id,"2",registerNameEvent.nickName, registerNameEvent.accountName, registerNameEvent.pwd, series_prize_group_json);
    }

    //请求数据接口
    private void onRequsetData() {
        String nick = registerMeNick.getText().toString().trim();
        String account = registerMeAccount.getText().toString().trim();
        String pwd = registerMePwd.getText().toString().trim();
        String pwd2 = registerMePwd2.getText().toString().trim();
        String  ischecked = registerMeInfrom.isChecked()?"1":"0";
        if (Check.isEmpty(nick)) {
            showMessage("请填写昵称");
            return;
        }
        if (Check.isEmpty(account)) {
            showMessage("请输入登录账户");
            return;
        }
        if (Check.isEmpty(pwd)) {
            showMessage("请输入登录密码");
            return;
        }
        if (Check.isEmpty(pwd2)) {
            showMessage("请输入确认密码");
            return;
        }

        if (!pwd2.equals(pwd)) {
            showMessage("两次输入的密码不一致");
            return;
        }

        if("选择其他奖金组".equals(registerMeSetFund.getText().toString())){
            showMessage("请选择奖金组");
            return;
        }
        RegisterShowDialog.newInstance(new RegisterNameEvent(pwd2,is_agent,nick,account,classic_prize),"").show(getFragmentManager());
        //

    }


    @Override
    public void getFundGroupResult(RegisterMeResult registerMeResult) {
        //转账前渠道确认
        GameLog.log("设置真实姓名 成功");
        aAllPossiblePrizeGroupsBeans0 = registerMeResult.getAAllPossibleAgentPrizeGroups();
        aAllPossiblePrizeGroupsBeans1 = registerMeResult.getAAllPossiblePrizeGroups();
        if(is_agent.equals("1")){
            aAllPossiblePrizeGroupsBeans =  aAllPossiblePrizeGroupsBeans1;
        }else{
            aAllPossiblePrizeGroupsBeans =  aAllPossiblePrizeGroupsBeans0;
        }
        typeOptionsPickerFund = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = aAllPossiblePrizeGroupsBeans.get(options1).getPickerViewText();
                type = aAllPossiblePrizeGroupsBeans.get(options1).getType()+"";
                classic_prize = aAllPossiblePrizeGroupsBeans.get(options1).getClassic_prize()+"";
                prize_group_id = aAllPossiblePrizeGroupsBeans.get(options1).getId()+"";
                series_prize_group_json = "{"+text.replace("--",":")+"}";
                registerMeSetFund.setText(text);
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
    public void setPresenter(RegisterMeContract.Presenter presenter) {
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


    @OnClick({R.id.registerMeType, R.id.registerMeSetFund, R.id.registerMeSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.registerMeType:
                typeOptionsPicker.show();
                break;
            case R.id.registerMeSetFund:
                hideKeyboard();
                typeOptionsPickerFund.show();
                break;
            case R.id.registerMeSubmit:
                onRequsetData();
                break;
        }
    }


}
