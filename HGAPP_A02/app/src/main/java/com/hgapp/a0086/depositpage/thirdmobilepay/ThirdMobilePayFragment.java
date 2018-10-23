package com.hgapp.a0086.depositpage.thirdmobilepay;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.common.widgets.NTitleBar;
import com.hgapp.a0086.data.DepositThirdQQPayResult;
import com.hgapp.a0086.depositpage.DepositeContract;
import com.hgapp.common.util.Check;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class ThirdMobilePayFragment extends HGBaseFragment {

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.tvThirdMobilePayBack)
    NTitleBar tvThirdMobilePayBack;
    @BindView(R.id.etDepositThirdMobileMoney)
    EditText etDepositThirdMobileMoney;
    @BindView(R.id.tvDepositThirdMobile)
    TextView tvDepositThirdMobile;
    @BindView(R.id.btnDepositThirdMobileSubmit)
    Button btnDepositThirdMobileSubmit;
    private DepositeContract.Presenter presenter;
    DepositThirdQQPayResult.DataBean dataBean;
    private String getArgParam1;
    private int getArgParam2;
    public static ThirdMobilePayFragment newInstance(DepositThirdQQPayResult.DataBean dataBean,String getArgParam1,int argParam2) {
        ThirdMobilePayFragment fragment = new ThirdMobilePayFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM0,dataBean);
        args.putString(ARG_PARAM1,getArgParam1);
        args.putInt(ARG_PARAM2,argParam2);
//        Injections.inject(null,fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            dataBean = getArguments().getParcelable(ARG_PARAM0);
            getArgParam1 = getArguments().getString(ARG_PARAM1);
            getArgParam2 = getArguments().getInt(ARG_PARAM2);
        }
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_thirdmobilepay;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        tvDepositThirdMobile.setText(dataBean.getTitle());
        tvThirdMobilePayBack.setMoreText(getArgParam1);
        tvThirdMobilePayBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });
    }

    private void onCheckThirdMobilePay(){
        String thirdMobileMoney = etDepositThirdMobileMoney.getText().toString().trim();

        if(Check.isEmpty(thirdMobileMoney)){
            showMessage("汇款金额必须是整数！");
            return;
        }
        EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(dataBean.getUrl(),thirdMobileMoney,dataBean.getUserid(),dataBean.getId(),""), SupportFragment.SINGLETASK));
    }

    @OnClick(R.id.btnDepositThirdMobileSubmit)
    public void onViewClicked() {
        onCheckThirdMobilePay();
    }
}
