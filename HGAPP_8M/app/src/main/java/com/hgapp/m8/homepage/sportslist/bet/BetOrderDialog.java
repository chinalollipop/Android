package com.hgapp.m8.homepage.sportslist.bet;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.hgapp.m8.R;
import com.hgapp.m8.base.HGBaseDialogFragment;
import com.hgapp.m8.common.util.ACache;
import com.hgapp.m8.common.util.HGConstant;

import butterknife.BindView;
import butterknife.OnClick;

public class BetOrderDialog extends HGBaseDialogFragment {

    public static final String PARAM0 = "param0";
    public static final String PARAM1 = "param1";
    public static final String PARAM2 = "param2";
    @BindView(R.id.tvBetOrderMoney)
    TextView tvBetOrderMoney;
    @BindView(R.id.etBetGoldInput)
    EditText etBetGoldInput;
    @BindView(R.id.tvBetOrderMoneyLimit)
    TextView tvBetOrderMoneyLimit;
    @BindView(R.id.btnBetOrder)
    Button btnBetOrder;
    private String getParam1;
    OrderNumber param2;
    private String userMoney;

    public static BetOrderDialog newInstance(String param0,String param1,OrderNumber param2) {
        Bundle bundle = new Bundle();
        bundle.putString(PARAM0, param0);
        bundle.putString(PARAM1, param1);
        bundle.putParcelable(PARAM2, param2);
        BetOrderDialog dialog = new BetOrderDialog();
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
    protected int getLayoutResId() {
        return R.layout.dialog_bet_order;
    }

    @Override
    protected void initView(View view, Bundle bundle) {
        userMoney = getArguments().getString(PARAM0);
        tvBetOrderMoney.setText(userMoney);
        getParam1 =  getArguments().getString(PARAM1);
        param2 =  getArguments().getParcelable(PARAM2);
        tvBetOrderMoneyLimit.setText("每注最小金额"+ACache.get(getContext()).getAsString(HGConstant.USERNAME_BUY_MIN)+"元");
    }

    @OnClick(R.id.btnBetOrder)
    public void onViewClicked() {
        String inputMoney = etBetGoldInput.getText().toString().trim();
        if(inputMoney.compareTo("20")>=1){
            hide();
            param2.setGold(inputMoney);
           // BetOrderSubmitDialog.newInstance(userMoney,getParam1,param2,null).show(getFragmentManager());
        }else{
            showMessage("请输入有效金额");
        }

    }
}
