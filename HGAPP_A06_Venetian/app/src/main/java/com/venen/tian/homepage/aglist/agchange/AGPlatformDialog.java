package com.venen.tian.homepage.aglist.agchange;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.venen.tian.Injections;
import com.venen.tian.R;
import com.venen.tian.base.IPresenter;
import com.venen.tian.common.util.GameShipHelper;
import com.venen.tian.common.widgets.bottomdialog.NBaseBottomDialog;
import com.venen.tian.data.BetRecordResult;
import com.venen.tian.data.PersonBalanceResult;
import com.venen.common.util.Check;
import com.venen.common.util.GameLog;
import com.venen.common.util.ToastUtils;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import butterknife.Unbinder;

public class AGPlatformDialog extends NBaseBottomDialog implements AGPlatformContract.View {

    public static final String PARAM0 = "param0";
    public static final String PARAM1 = "param1";
    public static final String PARAM2 = "param2";
    @BindView(R.id.hgMoney)
    TextView hgMoney;
    @BindView(R.id.agMoney)
    TextView agMoney;
    @BindView(R.id.etAgGoldInput)
    EditText etAgGoldInput;
    @BindView(R.id.agOut)
    Button agOut;
    @BindView(R.id.agInt)
    Button agInt;
    Unbinder unbinder;
    private String fagMoney ,fhgMoney,fshowtype;
    AGPlatformContract.Presenter presenter;

    public static AGPlatformDialog newInstance(String param0, String param1, String param2) {
        Bundle bundle = new Bundle();
        bundle.putString(PARAM0, param0);
        bundle.putString(PARAM1, param1);
        bundle.putString(PARAM2, param2);
        //bundle.putParcelable(PARAM2, param2);
        AGPlatformDialog dialog = new AGPlatformDialog();
        Injections.inject(null,dialog);
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setCancelable(true);
        //setCanceledOnTouchOutside(true);
    }

    @Override
    public int getLayoutRes() {
        return R.layout.dialog_ag_change;
    }

    @Override
    public void bindView(View v) {
        fagMoney = getArguments().getString(PARAM0);
        fhgMoney = getArguments().getString(PARAM1);
        fshowtype = getArguments().getString(PARAM2);
        agMoney.setText(GameShipHelper.formatMoney(fagMoney));
        hgMoney.setText(GameShipHelper.formatMoney(fhgMoney));
        GameLog.log("当把钱  "+fshowtype);
        if("mg".equals(fshowtype)){
            agOut.setText("MG电子转出");
            agInt.setText("MG电子转入");
            presenter.postMGPersonBalance("","");
        }else{
            presenter.postPersonBalance("","");
        }
        //param2 = getArguments().getParcelable(PARAM2);
    }

    @OnClick({R.id.agOut, R.id.agInt})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.agOut:
                String text = etAgGoldInput.getText().toString();
                if(Check.isEmpty(text)){
                    return;
                }
                if(fshowtype.equals("mg")){
                    presenter.postMGBanalceTransfer("","mg","hg",GameShipHelper.getIntegerString(text));
                }else{
                    presenter.postBanalceTransfer("","ag","hg",GameShipHelper.getIntegerString(text));
                }
                break;
            case R.id.agInt:
                String text2 = etAgGoldInput.getText().toString();
                if(Check.isEmpty(text2)){
                    return;
                }
                if(fshowtype.equals("mg")){
                    presenter.postMGBanalceTransfer("","hg","mg",GameShipHelper.getIntegerString(text2));
                }else{
                    presenter.postBanalceTransfer("","hg","ag", GameShipHelper.getIntegerString(text2));
                }
                break;
        }
    }

    @Override
    public void postBanalceTransferSuccess() {
        GameLog.log("用户转账成功之后 "+ fshowtype);
        if("mg".equals(fshowtype)){
            presenter.postMGPersonBalance("","");
        }else{
            presenter.postPersonBalance("","");
        }
        dismiss();
    }

    @Override
    public void postBetRecordResult(BetRecordResult message) {

    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("转账对话框的展示 "+personBalance.getBalance_ag());
        if(!Check.isNull(hgMoney)&&!Check.isNull(agMoney)){
            hgMoney.setText(GameShipHelper.formatMoney(personBalance.getBalance_hg()));
            agMoney.setText(GameShipHelper.formatMoney(personBalance.getBalance_ag()));
        }
        EventBus.getDefault().post(personBalance);
    }

    @Override
    public void postMGPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("MG转账对话框的展示 "+personBalance.getBalance_ag());
        if(!Check.isNull(hgMoney)&&!Check.isNull(agMoney)){
            hgMoney.setText(GameShipHelper.formatMoney(personBalance.getHg_balance()));
            agMoney.setText(GameShipHelper.formatMoney(personBalance.getMg_balance()));
        }
        EventBus.getDefault().post(personBalance);
    }

    @Override
    public void showMessage(String message) {
        ToastUtils.showLongToast(message);
        //presenter.postPersonBalance("","");
        /*PersonBalanceResult p = new PersonBalanceResult();
        p.setBalance_ag("12344");
        EventBus.getDefault().post(p);*/
    }

    @Override
    public void setStart(int action) {

    }

    @Override
    public void setError(int action, int errcode) {

    }

    @Override
    public void setError(int action, String errString) {

    }

    @Override
    public void setComplete(int action) {

    }

    @Override
    public void setPresenter(AGPlatformContract.Presenter presenter) {
        this.presenter  = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }
}
