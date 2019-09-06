package com.cfcp.a01.ui.home.deposit;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.http.Client;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.CLipHelper;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.DoubleClickHelper;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.DepositH5Result;
import com.cfcp.a01.data.DepositTypeResult;
import com.cfcp.a01.ui.home.texthtml.html.HtmlUtils;
import com.kongzue.dialog.v3.WaitDialog;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;

public class DepositSubmitFragment extends BaseFragment implements DepositSubmitContract.View {

    private static final String TYPE0 = "type0";
    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    private DepositTypeResult.APlatformBean typeArgs1;
    private DepositTypeResult.APaymentPlatformBankCardBean aPaymentPlatformBankCardBean;
    private String typeArgs2,typeArgs3;
    DepositSubmitContract.Presenter presenter;
    @BindView(R.id.depositNextBack)
    NTitleBar depositNextBack;
    @BindView(R.id.depositNextBankMidLay)
    LinearLayout depositNextBankMidLay;
    @BindView(R.id.depositNextTypeImg)
    ImageView depositNextTypeImg;
    @BindView(R.id.depositNextTypeName)
    TextView depositNextTypeName;
    @BindView(R.id.depositNextTypeAName)
    TextView depositNextTypeAName;
    @BindView(R.id.depositNextTypeANameCopy)
    TextView depositNextTypeANameCopy;
    @BindView(R.id.depositNextBankName)
    TextView depositNextBankName;
    @BindView(R.id.depositNextBankAccount)
    TextView depositNextBankAccount;
    @BindView(R.id.depositNextBankAccountCopy)
    TextView depositNextBankAccountCopy;
    @BindView(R.id.depositNextBankNumber)
    TextView depositNextBankNumber;
    @BindView(R.id.depositNextBankNumberCopy)
    TextView depositNextBankNumberCopy;
    @BindView(R.id.depositNextBankNextName)
    TextView depositNextBankNextName;
    @BindView(R.id.depositNextBankNextNameCopy)
    TextView depositNextBankNextNameCopy;
    @BindView(R.id.depositNextBankMothed)
    LinearLayout depositNextBankMothed;
    @BindView(R.id.depositNextTypeBg)
    ImageView depositNextTypeBg;
    @BindView(R.id.depositNextTypeText)
    TextView depositNextTypeText;
    @BindView(R.id.depositNextSubmit)
    TextView depositNextSubmit;
    @BindView(R.id.depositNextInputName)
    EditText depositNextInputName;
    @BindView(R.id.depositNextInputNameLay)
    LinearLayout depositNextInputNameLay;
    @BindView(R.id.depositNextInputMoney)
    EditText depositNextInputMoney;

    public static DepositSubmitFragment newInstance(DepositTypeResult.APlatformBean aPlatformBean,DepositTypeResult.APaymentPlatformBankCardBean aPaymentPlatformBankCardBean,String deposit_mode, String money) {
        DepositSubmitFragment loginFragment = new DepositSubmitFragment();
        Bundle args = new Bundle();
        args.putParcelable(TYPE0, aPaymentPlatformBankCardBean);
        args.putParcelable(TYPE1, aPlatformBean);
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        loginFragment.setArguments(args);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_deposit_submit;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            aPaymentPlatformBankCardBean = getArguments().getParcelable(TYPE0);
            typeArgs1 = getArguments().getParcelable(TYPE1);
            typeArgs2 = getArguments().getString(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        depositNextBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        if(typeArgs2.equals("1")&&!Check.isNull(aPaymentPlatformBankCardBean)){
            depositNextBankMothed.setVisibility(View.VISIBLE);
            depositNextBankName.setText(aPaymentPlatformBankCardBean.getBank());
            depositNextBankAccount.setText(aPaymentPlatformBankCardBean.getOwner());
            depositNextBankNumber.setText(aPaymentPlatformBankCardBean.getAccount_no());
            depositNextBankNextName.setText(aPaymentPlatformBankCardBean.getBranch());
            depositNextTypeANameCopy.setVisibility(View.GONE);
        }else{
            depositNextBankMothed.setVisibility(View.GONE);
        }
        depositNextInputMoney.setText(typeArgs3);
        if(typeArgs1.getPayer_name_enabled()==1){//1 需要姓名 0 不需要
            depositNextInputNameLay.setVisibility(View.VISIBLE);
        }else{
            depositNextInputNameLay.setVisibility(View.GONE);
        }
        depositNextTypeAName.setText(typeArgs1.getDisplay_name());
        switch (typeArgs1.getIcon_type()){
            case 0://银行卡

                depositNextTypeName.setText("银行转账");
                depositNextTypeImg.setImageDrawable(getResources().getDrawable(R.mipmap.deposit_union_code));
                break;
            case 5://云闪付
                depositNextTypeName.setText("银行转账");
                depositNextTypeImg.setImageDrawable(getResources().getDrawable(R.mipmap.deposit_union_code));
                break;
            case 1://微信
                depositNextTypeName.setText("微信转账");
                depositNextTypeImg.setImageDrawable(getResources().getDrawable(R.mipmap.deposit_wechat));
                break;
            case 2://支付宝扫码
                depositNextTypeName.setText("支付宝转账");
                depositNextTypeImg.setImageDrawable(getResources().getDrawable(R.mipmap.deposit_ali));
                break;
        }
        if(Check.isEmpty(typeArgs1.getNotice())){
            depositNextBankMidLay.setVisibility(View.GONE);
            depositNextInputNameLay.setVisibility(View.GONE);
        }else{
            depositNextTypeText.setText(HtmlUtils.getHtml(getContext(),depositNextTypeText,typeArgs1.getNotice()));
        }
    }


    private void onSubmit() {
        DoubleClickHelper.getNewInstance().disabledView(depositNextSubmit);
        String money = depositNextInputMoney.getText().toString().trim();
        if(Check.isEmpty(money)){
            showMessage("请输入金额");
            return;
        }
        String name = depositNextInputName.getText().toString().trim();
        if(typeArgs1.getPayer_name_enabled()==1){
            if(Check.isEmpty(name)){
                showMessage("请输入存款姓名");
                return;
            }
        }else{
            name = "";
        }
        WaitDialog.show((AppCompatActivity)getActivity(), "提交中...");
        if(typeArgs1.getPay_type()==1){
            //pop();
            //http://api.dh5588.com/service?payment_platform_id=153&amount=10&deposit_mode=2&action=Payment&step=3&packet=Fund&terminal_id=2&token=68e7a0fd65b9c3d7872afad467aacd8c36528a64
            String url = Client.baseUrl()+"service?payment_platform_id="+typeArgs1.getId()+"&amount="+money+"&deposit_mode="+ typeArgs1.getType()+
                    "&action=Payment&step=3&packet=Fund&terminal_id=2&token="+ ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
            EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(url,typeArgs1.getDisplay_name(),"","",""), SupportFragment.SINGLETASK));
        }else {
            presenter.getDepositSubmit(typeArgs1.getType() + "", typeArgs1.getId() + "", name, money);
        }
    }


    @Override
    public void getDepositSubmitResult(DepositH5Result depositH5Result) {
        WaitDialog.dismiss();
        //转账前渠道确认
        GameLog.log("充值成功 渠道确认 成功");
        if (Check.isNull(depositH5Result)){
            showMessage("充值成功！");
            finish();
        }else{
            if(!Check.isEmpty(depositH5Result.getSGotoUrl())){
                pop();
                EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(depositH5Result.getSGotoUrl(),typeArgs1.getDisplay_name(),"","",""), SupportFragment.SINGLETASK));
                //finish();
            }
        }

    }

    @Override
    public void setPresenter(DepositSubmitContract.Presenter presenter) {
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

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        WaitDialog.dismiss();
    }

    @OnClick({R.id.depositNextTypeANameCopy, R.id.depositNextBankAccountCopy, R.id.depositNextBankNumberCopy, R.id.depositNextBankNextNameCopy, R.id.depositNextSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.depositNextTypeANameCopy:
                CLipHelper.copy(getActivity(),depositNextTypeAName.getText().toString());
                showMessage("复制成功！");
                break;
            case R.id.depositNextBankAccountCopy:
                CLipHelper.copy(getActivity(),depositNextBankAccount.getText().toString());
                showMessage("复制成功！");
                break;
            case R.id.depositNextBankNumberCopy:
                CLipHelper.copy(getActivity(),depositNextBankNumber.getText().toString());
                showMessage("复制成功！");
                break;
            case R.id.depositNextBankNextNameCopy:
                CLipHelper.copy(getActivity(),depositNextBankNextName.getText().toString());
                showMessage("复制成功！");
                break;
            case R.id.depositNextSubmit:
                onSubmit();
                break;
        }
    }
}
