package com.vene.tian.depositpage.aliqcpay;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.TimePickerView;
import com.vene.tian.Injections;
import com.vene.tian.R;
import com.vene.tian.base.HGBaseFragment;
import com.vene.tian.common.util.DoubleClickHelper;
import com.vene.tian.common.widgets.NTitleBar;
import com.vene.tian.data.DepositAliPayQCCodeResult;
import com.vene.common.util.Check;
import com.squareup.picasso.Picasso;

import java.text.SimpleDateFormat;
import java.util.Date;

import butterknife.BindView;
import butterknife.OnClick;

public class AliQCPayFragment extends HGBaseFragment implements AliQCPayContract.View {

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    DepositAliPayQCCodeResult dataBean;
    @BindView(R.id.tvAliQCPayBack)
    NTitleBar tvAliQCPayBack;
    @BindView(R.id.ivDepositAliQCPayImage)
    ImageView ivDepositAliQCPayImage;
    @BindView(R.id.tvAliQCPayTitle)
    TextView tvAliQCPayTitle;
    @BindView(R.id.tvDepositAliQCPayType)
    TextView tvDepositAliQCPayType;
    @BindView(R.id.tvDepositAliQCPayName)
    TextView tvDepositAliQCPayName;
    @BindView(R.id.etDepositAliQCPayMoney)
    EditText etDepositAliQCPayMoney;
    @BindView(R.id.edDepositAliQCPayOrder)
    TextView edDepositAliQCPayOrder;
    @BindView(R.id.edDepositAliQCPayOrderNumber)
    EditText edDepositAliQCPayOrderNumber;
    @BindView(R.id.tvDepositAliQCPayTime)
    TextView tvDepositAliQCPayTime;
    @BindView(R.id.btnDepositAliQCPaySubmit)
    Button btnDepositAliQCPaySubmit;
    TimePickerView pvStartTime;

    private String payId,payBankUser;
    private String getArgParam1;
    private int getArgParam2;

    private AliQCPayContract.Presenter presenter;
    public static AliQCPayFragment newInstance(DepositAliPayQCCodeResult dataBean,String getArgParam1,int getArgParam2) {
        AliQCPayFragment fragment = new AliQCPayFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM0, dataBean);
        args.putString(ARG_PARAM1, getArgParam1);
        args.putInt(ARG_PARAM2, getArgParam2);
        fragment.setArguments(args);
        Injections.inject(null, fragment);
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
    public int setLayoutId() {
        return R.layout.fragment_aliqcpay;
    }


    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm");
        return format.format(date);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        switch (getArgParam2){
                case 1://银行卡线上
                    break;
                case 2://公司入款
                    break;
                case 3://微信第三方
                    break;
                case 4://支付宝第三方
                    break;
                case 5://QQ第三方
                    break;
                case 6://支付宝扫码
                    tvAliQCPayTitle.setText("支付宝扫一扫，轻松支付");
                    tvDepositAliQCPayType.setText("支付宝姓名");
                    break;
                case 7://支付宝扫码
                    tvAliQCPayTitle.setText("微信扫一扫，轻松支付");
                    tvDepositAliQCPayType.setText("微信姓名");
                    break;
                case 8://云闪付
                    tvAliQCPayTitle.setText("银联扫码|云闪付扫一扫，轻松支付");
                    tvDepositAliQCPayType.setText("姓名");
                    break;
            }
        //时间选择器
        pvStartTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                tvDepositAliQCPayTime.setText(getTime(date));
            }
        })
                .setType(new boolean[]{true, true, true, true, true, false})// 默认全部显示
                // .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();

        tvAliQCPayBack.setMoreText(getArgParam1);
        tvAliQCPayBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });
        Picasso.with(getContext())
                .load(dataBean.getData().get(0).getPhoto_name())
                .placeholder(R.drawable.loading)
                .into(ivDepositAliQCPayImage);
        payId = dataBean.getData().get(0).getId();
        payBankUser = dataBean.getData().get(0).getBank_user();
        if(!Check.isEmpty(dataBean.getData().get(0).getNotice())){
            edDepositAliQCPayOrder.setText(dataBean.getData().get(0).getNotice());
            edDepositAliQCPayOrderNumber.setHint("请输入"+dataBean.getData().get(0).getNotice());
        }
        tvDepositAliQCPayName.setText(dataBean.getData().get(0).getBank_user());
        tvDepositAliQCPayTime.setText(getTime(new Date()));
    }

    private void onCheckThirdMobilePay() {
        String etMoney = etDepositAliQCPayMoney.getText().toString().trim();
        String number = edDepositAliQCPayOrderNumber.getText().toString().trim();
        String tvTime = tvDepositAliQCPayTime.getText().toString().trim();
        if (Check.isEmpty(etMoney)) {
            showMessage("汇款金额必须是整数！");
            return;
        }

        if (Check.isEmpty(number)) {
            showMessage("请输入交易单号！");
            return;
        }


        presenter.postDepositAliPayQCPaySubimt("", payId,  etMoney, tvTime, number,payBankUser);
        //EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(dataBean.getUrl(), thirdBankMoney, dataBean.getUserid(), dataBean.getId(), bankCode), SupportFragment.SINGLETASK));
    }



    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(AliQCPayContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @OnClick(R.id.btnDepositAliQCPaySubmit)
    public void onViewClicked() {
        DoubleClickHelper.getNewInstance().disabledView(btnDepositAliQCPaySubmit);
        onCheckThirdMobilePay();
    }
    @OnClick(R.id.tvDepositAliQCPayTime)
    public void onViewPayTimeClicked(){
        pvStartTime.show();
    }

    @Override
    public void postDepositAliPayQCPaySubimtResult(String message) {
        showMessage(message);
        pop();
    }
}
