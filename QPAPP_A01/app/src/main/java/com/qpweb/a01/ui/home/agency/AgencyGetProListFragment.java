package com.qpweb.a01.ui.home.agency;

import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.TextView;

import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.data.DetailListResult;
import com.qpweb.a01.data.ProListResults;
import com.qpweb.a01.utils.Check;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class AgencyGetProListFragment extends BaseDialogFragment {

    @BindView(R.id.proListRView)
    RecyclerView proListRView;
    List<ProListResults> proListResults = new ArrayList<>();
    public static AgencyGetProListFragment newInstance(ArrayList<ProListResults> proListResults) {
        Bundle bundle = new Bundle();
        bundle.putParcelableArrayList("proListResults", proListResults);
        AgencyGetProListFragment loginFragment = new AgencyGetProListFragment();
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.agency_pro_list_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            this.proListResults = getArguments().getParcelableArrayList("proListResults");
        }

    }

    class GetProListDetailAdapter extends BaseQuickAdapter<ProListResults, BaseViewHolder> {

        public GetProListDetailAdapter(int layoutResId, @Nullable List<ProListResults> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, ProListResults item) {
            helper.setText(R.id.itemAProUserName, item.getAdddate()).
                    setText(R.id.itemAProMoney, item.getGold());
        }
    }


    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        proListRView.setLayoutManager(linearLayoutManager);
        if(Check.isNull(proListResults)){
            proListResults = new ArrayList<>();
        }
        /*for(int k=0;k<24;++k){
            ProListResults proListResult = new ProListResults();
            proListResult.setMoney(""+(1+k));
            proListResult.setTime("2019-6-29 "+(1+k));
            proListResults.add(proListResult);
        }*/
        GetProListDetailAdapter detailReportAdapter = new GetProListDetailAdapter(R.layout.item_agency_pro,proListResults);
        if(proListResults.size()==0){
            View viewEm = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
            TextView textView = viewEm.findViewById(R.id.itemNoDate);
            textView.setText("暂时你没有记录，快去分享给好友吧！");
            //textView.setTextColor(Color.parseColor("#C52133"));
            detailReportAdapter.setEmptyView(viewEm);
        }
        proListRView.setAdapter(detailReportAdapter);
    }

    @OnClick({ R.id.proListClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.proListClose:
                hide();
                break;
        }
    }


}
