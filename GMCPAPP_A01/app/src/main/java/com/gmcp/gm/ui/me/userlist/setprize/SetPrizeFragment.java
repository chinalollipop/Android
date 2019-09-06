package com.gmcp.gm.ui.me.userlist.setprize;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.gmcp.gm.Injections;
import com.gmcp.gm.R;
import com.gmcp.gm.common.base.BaseFragment;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.utils.Check;
import com.gmcp.gm.common.utils.GameLog;
import com.gmcp.gm.common.widget.NTitleBar;
import com.gmcp.gm.data.LoginResult;
import com.gmcp.gm.data.LowerSetDataResult;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import butterknife.BindView;
import butterknife.OnClick;

public class SetPrizeFragment extends BaseFragment implements SetPrizeContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.setPrizeBack)
    NTitleBar setPrizeBack;
    @BindView(R.id.setPrizeNick)
    TextView setPrizeNick;
    @BindView(R.id.setPrizeName)
    TextView setPrizeName;
    @BindView(R.id.setPrizePhone)
    TextView setPrizePhone;
    @BindView(R.id.setPrizeAccountText)
    TextView setPrizeAccountText;
    @BindView(R.id.setPrizeEmail)
    TextView setPrizeEmail;
    @BindView(R.id.setPrizeSubmit)
    TextView setPrizeSubmit;
    private String typeArgs2, typeArgs3;
    SetPrizeContract.Presenter presenter;
    OptionsPickerView typeOptionsPickerFund;
    List<LowerSetDataResult.SAllPossiblePrizeGroupsBean> aAllPossiblePrizeGroupsBeans = new ArrayList<>();
    String series_prize_group_json;
    String type,classic_prize,kickback;
    public static SetPrizeFragment newInstance(String user_id, String money) {
        SetPrizeFragment betFragment = new SetPrizeFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, user_id);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_setprize;
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
        setPrizeBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        presenter.getLowerLevelReport(typeArgs2);
    }

    //请求数据接口
    private void onRequsetData() {
        Map<String, String> map = new HashMap<String, String>();
        map.put(type,classic_prize);
        series_prize_group_json = JSON.toJSONString(map);
        presenter.getRealName(typeArgs2, series_prize_group_json, kickback, "");
    }

    @Override
    public void getRealNameResult(LoginResult loginResult) {
        //转账前渠道确认
        GameLog.log("设置返点成功 成功");
        showMessage("设置成功！");
        finish();
    }

    @Override
    public void getLowerLevelReportResult(LowerSetDataResult lowerSetDataResult) {
        GameLog.log("获取下级的信息 ");
        aAllPossiblePrizeGroupsBeans = lowerSetDataResult.getSAllPossiblePrizeGroups();

        setPrizePhone.setText(lowerSetDataResult.getFRebateLevel());
        setPrizeName.setText(lowerSetDataResult.getAUserPrizeSet().getUsername());
        setPrizeNick.setText(lowerSetDataResult.getAUserPrizeSet().getNickname());
        if(lowerSetDataResult.isBSetable()){
            type = aAllPossiblePrizeGroupsBeans.get(0).getType()+"";
            kickback = aAllPossiblePrizeGroupsBeans.get(0).getWater()+"";
            classic_prize = aAllPossiblePrizeGroupsBeans.get(0).getClassic_prize()+"";
            setPrizeEmail.setText(aAllPossiblePrizeGroupsBeans.get(0).getPickerViewText());
            typeOptionsPickerFund = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

                @Override
                public void onOptionsSelect(int options1, int options2, int options3, View v) {
                    //1、tab做相应的切换
                    // 2、下面做查询数据的请求和展示
                    String text = aAllPossiblePrizeGroupsBeans.get(options1).getPickerViewText();
                    type = aAllPossiblePrizeGroupsBeans.get(options1).getType()+"";
                    kickback = aAllPossiblePrizeGroupsBeans.get(options1).getWater()+"";
                    classic_prize = aAllPossiblePrizeGroupsBeans.get(options1).getClassic_prize()+"";
                    series_prize_group_json = "{"+text.replace("--",":")+"}";
                    setPrizeEmail.setText(text);
                }
            }).build();
            typeOptionsPickerFund.setPicker(aAllPossiblePrizeGroupsBeans);
        }else{
            setPrizeSubmit.setVisibility(View.GONE);
            showMessage("当前用户不可以设置");
        }

    }

    @Override
    public void setPresenter(SetPrizeContract.Presenter presenter) {
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

    @OnClick({R.id.setPrizeSubmit,R.id.setPrizeEmail})
    public void onViewClicked(View view) {
        switch (view.getId()){
            case R.id.setPrizeSubmit:
                onRequsetData();
                break;
            case R.id.setPrizeEmail:
                if(!Check.isNull(typeOptionsPickerFund)) {
                    typeOptionsPickerFund.show();
                }
                break;
        }
    }
}
