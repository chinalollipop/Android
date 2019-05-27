package com.cfcp.a01.ui.home.cplist;

import android.os.Bundle;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;

import com.cfcp.a01.CPInjections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseDialogFragment;
import com.cfcp.a01.data.AllGamesResult;
import com.cfcp.a01.data.CPBetResult;
import com.cfcp.a01.ui.home.cplist.bet.CpBetApiContract;
import com.cfcp.a01.ui.home.cplist.events.CPOrderList;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;

public class CPTitleDialog extends BaseDialogFragment implements CpBetApiContract.View{
    public static final String PARAM0 = "betResult";
    public static final String PARAM1 = "gold";
    public static final String PARAM2 = "game_code";
    public static final String PARAM3 = "round";
    public static final String PARAM4 = "x_session_token";
    @BindView(R.id.dialogTitleCp)
    RecyclerView dialogTitleCp;

    ArrayList<AllGamesResult.DataBean.LotteriesBean> XinYongLotteries;
    CpBetApiContract.Presenter presenter;

    public static CPTitleDialog newInstance(ArrayList<CPOrderList> cpOrderListArrayList, String gold, String game_code, String round, String x_session_token) {
        Bundle bundle = new Bundle();
        bundle.putParcelableArrayList(PARAM0, cpOrderListArrayList);
        bundle.putString(PARAM1, gold);
        bundle.putString(PARAM2, game_code);
        bundle.putString(PARAM3, round);
        bundle.putString(PARAM4, x_session_token);
        CPTitleDialog dialog = new CPTitleDialog();
        dialog.setArguments(bundle);
        CPInjections.inject(null,dialog);
        return dialog;
    }

    public static CPTitleDialog newInstances(ArrayList<AllGamesResult.DataBean.LotteriesBean> XinYongLotteries) {
        Bundle bundle = new Bundle();
        bundle.putParcelableArrayList(PARAM0, XinYongLotteries);
        CPTitleDialog dialog = new CPTitleDialog();
        dialog.setArguments(bundle);
        CPInjections.inject(null,dialog);
        return dialog;
    }

    @Override
    protected int setLayoutId() {
        return R.layout.dialog_title_cp;
    }

    class CPTitleGameAdapter extends BaseQuickAdapter<AllGamesResult.DataBean.LotteriesBean,BaseViewHolder> {

        public CPTitleGameAdapter( int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, AllGamesResult.DataBean.LotteriesBean data) {
            holder.setText(R.id.tv_item_game_name, data.getName()).addOnClickListener(R.id.tv_item_game_name);
        }
    }


    @Override
    protected void setEvents(View view, Bundle bundle) {
        XinYongLotteries =  getArguments().getParcelableArrayList(PARAM0);
        GridLayoutManager gridLayoutManager= new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
        dialogTitleCp.setLayoutManager(gridLayoutManager);
        CPTitleGameAdapter cpOrederGameAdapter  = new CPTitleGameAdapter(R.layout.item_cp_order_list,XinYongLotteries);
        cpOrederGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                hide();
               // onCpGameItemClick(XinYongLotteries.get(position));
            }
        });
        dialogTitleCp.setAdapter(cpOrederGameAdapter);
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void postCpBetResult(CPBetResult betResult) {
    }


    @Override
    public void setPresenter(CpBetApiContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
    }

}
