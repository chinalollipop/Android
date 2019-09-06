package com.gmcp.gm.ui.me.link;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.gmcp.gm.Injections;
import com.gmcp.gm.R;
import com.gmcp.gm.common.base.BaseFragment;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.utils.CLipHelper;
import com.gmcp.gm.common.utils.Check;
import com.gmcp.gm.common.utils.GameLog;
import com.gmcp.gm.common.widget.NTitleBar;
import com.gmcp.gm.data.RegisterLinkListResult;
import com.gmcp.gm.data.RegisterMeResult;
import com.gmcp.gm.ui.me.register.CancelRegisterEvent;
import com.kongzue.dialog.v3.WaitDialog;

import org.greenrobot.eventbus.EventBus;

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
    @BindView(R.id.registerLink1)
    LinearLayout registerLink1;
    @BindView(R.id.registerLinkRView)
    RecyclerView registerLinkRView;

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
    String is_agent = "1", prize_group_id, Channel, valid_days, series_prize_group_json;
    String type, classic_prize;
    List<RegisterMeResult.AAllPossibleAgentPrizeGroupsBean> aAllPossiblePrizeGroupsBeans = new ArrayList<>();
    List<RegisterMeResult.AAllPossibleAgentPrizeGroupsBean> aAllPossiblePrizeGroupsBeans0 = new ArrayList<>();
    List<RegisterMeResult.AAllPossibleAgentPrizeGroupsBean> aAllPossiblePrizeGroupsBeans1 = new ArrayList<>();


    static List<String> typeOptionsListType = new ArrayList<>();
    static List<String> typeOptionsListChannel = new ArrayList<>();
    static List<String> typeOptionsListTime = new ArrayList<>();

    static {
        typeOptionsListType.add("会员");
        typeOptionsListType.add("代理");

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
    }

    private void initTabView() {
        registerLinkStyle.addTab(registerLinkStyle.newTab().setText("链接开户"));
        registerLinkStyle.addTab(registerLinkStyle.newTab().setText("链接管理"));
        registerLinkStyle.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                hideKeyboard();
                switch (tab.getPosition()) {
                    case 0:
                        registerLink1.setVisibility(View.VISIBLE);
                        registerLinkRView.setVisibility(View.GONE);
                        break;
                    case 1:
                        WaitDialog.show((AppCompatActivity) _mActivity, "加载中...");
                        registerLink1.setVisibility(View.GONE);
                        registerLinkRView.setVisibility(View.VISIBLE);
                        presenter.getFundList();
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

        typeOptionsPickerType = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = typeOptionsListType.get(options1);
                if (options1 == 0) {
                    is_agent = "0";
                } else {
                    is_agent = "1";
                }
                if (is_agent.equals("1")) {
                    aAllPossiblePrizeGroupsBeans = aAllPossiblePrizeGroupsBeans1;
                } else {
                    aAllPossiblePrizeGroupsBeans = aAllPossiblePrizeGroupsBeans0;
                }
                assert aAllPossiblePrizeGroupsBeans != null;
                type = aAllPossiblePrizeGroupsBeans.get(0).getType() + "";
                classic_prize = aAllPossiblePrizeGroupsBeans.get(0).getClassic_prize() + "";
                prize_group_id = aAllPossiblePrizeGroupsBeans.get(0).getId() + "";
                registerLinkFund.setText(aAllPossiblePrizeGroupsBeans.get(0).getPickerViewText());
                typeOptionsPickerFund = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

                    @Override
                    public void onOptionsSelect(int options1, int options2, int options3, View v) {
                        //1、tab做相应的切换
                        // 2、下面做查询数据的请求和展示
                        String text = aAllPossiblePrizeGroupsBeans.get(options1).getPickerViewText();
                        type = aAllPossiblePrizeGroupsBeans.get(options1).getType() + "";
                        classic_prize = aAllPossiblePrizeGroupsBeans.get(options1).getClassic_prize() + "";
                        prize_group_id = aAllPossiblePrizeGroupsBeans.get(options1).getId() + "";
                        series_prize_group_json = "{" + text.replace("--", ":") + "}";
                        registerLinkFund.setText(text);
                    }
                }).build();
                typeOptionsPickerFund.setPicker(aAllPossiblePrizeGroupsBeans);
                registerLinkType.setText(text);
            }
        }).build();
        typeOptionsPickerType.setPicker(typeOptionsListType);

        typeOptionsPickerChannel = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = typeOptionsListChannel.get(options1);
                Channel = text;
                registerLinkChannel.setText(text);
            }
        }).build();
        typeOptionsPickerChannel.setPicker(typeOptionsListChannel);

        typeOptionsPickerTime = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = typeOptionsListTime.get(options1);
                switch (options1) {
                    case 0:
                        valid_days = "1";
                        break;
                    case 1:
                        valid_days = "7";
                        break;
                    case 2:
                        valid_days = "30";
                        break;
                    case 3:
                        valid_days = "90";
                        break;
                    case 4:
                        valid_days = "";
                        break;
                }
                registerLinkTime.setText(text);
            }
        }).build();
        typeOptionsPickerTime.setPicker(typeOptionsListTime);

    }

    //请求数据接口
    private void onRequsetData() {
        String liankQQ = registerLinkQQ.getText().toString().trim();
        if (Check.isEmpty(liankQQ)) {
            showMessage("请填写推广QQ");
            return;
        }

        if ("请选择".equals(registerLinkFund.getText().toString())) {
            showMessage("请选择奖金组");
            return;
        }
        if ("请选择".equals(registerLinkChannel.getText().toString())) {
            showMessage("请选择推广渠道");
            return;
        }
        if ("请选择".equals(registerLinkTime.getText().toString())) {
            showMessage("请选择链接有效期");
            return;
        }
        Map<String, String> map = new HashMap<String, String>();
        map.put(type, classic_prize);
        series_prize_group_json = JSON.toJSONString(map);
        presenter.getRegisterFundGroup(is_agent, prize_group_id, type, Channel, liankQQ, valid_days, series_prize_group_json);
        //RegisterShowDialog.newInstance(new RegisterNameEvent(pwd2,is_agent,liankQQ,account,classic_prize),"").show(getFragmentManager());
        //

    }


    @Override
    public void getFundGroupResult(RegisterMeResult registerLinkResult) {
        //转账前渠道确认
        GameLog.log("设置真实姓名 成功");
        aAllPossiblePrizeGroupsBeans0 = registerLinkResult.getAAllPossiblePrizeGroups();
        aAllPossiblePrizeGroupsBeans1 = registerLinkResult.getAAllPossibleAgentPrizeGroups();
        if (is_agent.equals("1")) {
            aAllPossiblePrizeGroupsBeans = aAllPossiblePrizeGroupsBeans1;
        } else {
            aAllPossiblePrizeGroupsBeans = aAllPossiblePrizeGroupsBeans0;
        }
        typeOptionsPickerFund = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = aAllPossiblePrizeGroupsBeans.get(options1).getPickerViewText();
                type = aAllPossiblePrizeGroupsBeans.get(options1).getType() + "";
                classic_prize = aAllPossiblePrizeGroupsBeans.get(options1).getClassic_prize() + "";
                prize_group_id = aAllPossiblePrizeGroupsBeans.get(options1).getId() + "";
                series_prize_group_json = "{" + text.replace("--", ":") + "}";
                registerLinkFund.setText(text);
            }
        }).build();
        typeOptionsPickerFund.setPicker(aAllPossiblePrizeGroupsBeans);
    }

    @Override
    public void getFundListResult(RegisterLinkListResult registerLinkListResult) {
        WaitDialog.dismiss();
        GameLog.log("获取链接地址 成功");
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        registerLinkRView.setLayoutManager(linearLayoutManager);
        final List<RegisterLinkListResult.ARegisterLinksBean> aRegisterLinksBeans = registerLinkListResult.getARegisterLinks();
        RegisterListAdapter registerListAdapter = new RegisterListAdapter(R.layout.item_register_cl, aRegisterLinksBeans);
        registerListAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                switch (view.getId()) {
                    case R.id.itemRegisterCopy:
                        CLipHelper.copy(getContext(), aRegisterLinksBeans.get(position).getUrl());
                        showMessage("复制成功！");
                        break;
                    case R.id.itemRegisterDelete:
                        WaitDialog.show((AppCompatActivity) _mActivity, "删除中...");
                        presenter.getFundDelete(aRegisterLinksBeans.get(position).getId() + "");
                        break;
                }
            }
        });
        registerLinkRView.setAdapter(registerListAdapter);
    }


    class RegisterListAdapter extends BaseQuickAdapter<RegisterLinkListResult.ARegisterLinksBean, BaseViewHolder> {

        public RegisterListAdapter(int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, RegisterLinkListResult.ARegisterLinksBean data) {
            if ("已关闭".equals(data.getStatus())) {
                holder.setGone(R.id.itemRegisterCopy, false).setGone(R.id.itemRegisterDelete, false);
            }
            holder.setText(R.id.itemRegisterTimeFormatted, data.getSExpireTimeFormatted()).
                    setText(R.id.itemRegisterChannel, data.getIs_agent()).
                    setText(R.id.itemRegisterStatus, data.getStatus()).
                    setText(R.id.itemRegisterUrl, data.getUrl()).
                    setText(R.id.itemRegisterCreated_at, data.getCreated_at()).
                    addOnClickListener(R.id.itemRegisterCopy).
                    addOnClickListener(R.id.itemRegisterDelete);
        }
    }

    @Override
    public void getRegisterFundGroupResult() {
        EventBus.getDefault().post(new CancelRegisterEvent("cancel"));
        showMessage("恭喜您，注册成功！");
        registerLinkStyle.getTabAt(1).select();
//        finish();
    }

    @Override
    public void getFundDeleteResult() {
        WaitDialog.dismiss();
        showMessage("删除成功！");
        presenter.getFundList();
//        finish();
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


    @OnClick({R.id.registerLinkType, R.id.registerLinkChannel, R.id.registerLinkTime, R.id.registerLinkFund, R.id.registerLinkSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.registerLinkType:
                if (!Check.isNull(typeOptionsPickerType))
                    typeOptionsPickerType.show();
                break;
            case R.id.registerLinkChannel:
                if (!Check.isNull(typeOptionsPickerChannel))
                    typeOptionsPickerChannel.show();
                break;
            case R.id.registerLinkTime:
                if (!Check.isNull(typeOptionsPickerTime))
                    typeOptionsPickerTime.show();
                break;
            case R.id.registerLinkFund:
                hideKeyboard();
                if (!Check.isNull(typeOptionsPickerFund))
                    typeOptionsPickerFund.show();
                break;
            case R.id.registerLinkSubmit:
                onRequsetData();
                break;
        }
    }


}
