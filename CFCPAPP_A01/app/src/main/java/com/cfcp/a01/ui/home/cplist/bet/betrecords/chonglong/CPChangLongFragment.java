package com.cfcp.a01.ui.home.cplist.bet.betrecords.chonglong;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;

import com.cfcp.a01.CPInjections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseActivity2;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.data.CPChangLongResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.kongzue.dialog.v3.WaitDialog;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class CPChangLongFragment extends BaseActivity2 implements CpChangLongContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpChangLongList)
    RecyclerView cpChangLongList;
    @BindView(R.id.cpChangLongbackHome)
    ImageView cpChangLongbackHome;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    CpChangLongContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String gameId = "";
    private String dzTitileName = "";

    int page = 1;
    int pageTotal = 1;
    BetChangLongItemGameAdapter cpOrederContentGameAdapter = null;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        CPInjections.inject(this, null);
        super.onCreate(savedInstanceState);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_changlong;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        Intent intent = getIntent();
        gameId = intent.getStringExtra("gameId");
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
        cpChangLongList.setLayoutManager(gridLayoutManager);
        cpChangLongList.setHasFixedSize(true);
        cpChangLongList.setNestedScrollingEnabled(false);
        presenter.getCpBetRecords(gameId);
        WaitDialog.show((AppCompatActivity) getContext(), "加载中...");

    }


    @OnClick({R.id.cpChangLongbackHome})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.cpChangLongbackHome:
                //刷新用户余额
                finish();
                break;
        }
    }


    class BetChangLongItemGameAdapter extends BaseQuickAdapter<CPChangLongResult.ListBean, BaseViewHolder> {

        public BetChangLongItemGameAdapter(int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, CPChangLongResult.ListBean data) {
            holder.setText(R.id.cpChangLongTime, data.getPlayCateName() + "-" + data.getPlayName());
            holder.setText(R.id.cpChangLongNumber, data.getCount() + "");
        }
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(CpChangLongContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#ff0000'>" + sign + "</font>";
    }


    @Override
    public void getBetRecordsResult(CPChangLongResult cpChangLongResult) {
        WaitDialog.dismiss();
        if (!Check.isNull(cpChangLongResult.getList()) && cpChangLongResult.getList().size() > 0) {
            if (null == cpOrederContentGameAdapter) {
                cpOrederContentGameAdapter = new BetChangLongItemGameAdapter(R.layout.item_cp_changlong, cpChangLongResult.getList());
                cpChangLongList.setAdapter(cpOrederContentGameAdapter);
            }
            cpOrederContentGameAdapter.notifyDataSetChanged();
        } else {
            showMessage("暂无数据！");
        }

    }
}
