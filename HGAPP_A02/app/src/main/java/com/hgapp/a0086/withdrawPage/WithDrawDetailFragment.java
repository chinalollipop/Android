package com.hgapp.a0086.withdrawPage;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseDialogFragment;
import com.hgapp.a0086.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a0086.data.WithdrawResult;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class WithDrawDetailFragment extends HGBaseDialogFragment {

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    @BindView(R.id.dialogWithdrawDetail)
    ImageView dialogWithdrawDetail;
    @BindView(R.id.dialogWithdrawDetailList)
    RecyclerView dialogWithdrawDetailList;
    @BindView(R.id.dialogWithdrawDetailBtn)
    TextView dialogWithdrawDetailBtn;
    private String payId;
    private String getArgParam1;
    private int lastweekday, getArgParam2;
    private boolean isShow = false;
    private WithdrawResult withdrawResult;

    public static WithDrawDetailFragment newInstance(WithdrawResult withdrawResult) {
        WithDrawDetailFragment fragment = new WithDrawDetailFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM1, withdrawResult);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            this.withdrawResult = getArguments().getParcelable(ARG_PARAM1);
        }

    }

    class LineChoiceAdapter extends AutoSizeRVAdapter<WithdrawResult.BetListBean> {
        private Context context;
        public LineChoiceAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final WithdrawResult.BetListBean data, final int position) {
            if(position%2==0){
                holder.setBackgroundColor(R.id.withDrawDetailItemLay,getResources().getColor(R.color.l_view_color));
            }else{
                holder.setBackgroundColor(R.id.withDrawDetailItemLay,getResources().getColor(R.color.register_left));
            }
            holder.setText(R.id.withDrawDetailItemMsg,""+data.getMsg());
            holder.setText(R.id.withDrawDetailItemValue,""+data.getValue());
        }
    }

    @Override
    public int getLayoutResId() {
        return R.layout.dialog_withdraw_detail;
    }


    @Override
    public void initView(View view, @Nullable Bundle savedInstanceState) {
        dialogWithdrawDetailBtn.setText("总计："+withdrawResult.getTotal_bet());
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),1, OrientationHelper.VERTICAL,false);
        dialogWithdrawDetailList.setLayoutManager(gridLayoutManager);
        dialogWithdrawDetailList.setHasFixedSize(true);
        dialogWithdrawDetailList.setNestedScrollingEnabled(false);
        dialogWithdrawDetailList.setAdapter(new LineChoiceAdapter(getContext(),R.layout.dialog_withdraw_detail_item,withdrawResult.getBet_list()));
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }


    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#FF0000'>" + sign + "</font>";
    }


    @Override
    public void onDestroyView() {
        super.onDestroyView();
    }


    @OnClick(R.id.dialogWithdrawDetail)
    public void onViewClicked() {
        hide();
    }
}
