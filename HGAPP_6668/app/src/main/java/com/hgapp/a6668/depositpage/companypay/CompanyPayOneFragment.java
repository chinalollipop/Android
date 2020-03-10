package com.hgapp.a6668.depositpage.companypay;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.Button;

import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.util.DoubleClickHelper;
import com.hgapp.a6668.common.widgets.GridRvItemDecoration;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.data.DepositBankCordListResult;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class CompanyPayOneFragment extends HGBaseFragment{

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    @BindView(R.id.tvCompanyPayBackOne)
    NTitleBar tvCompanyPayBackOne;
    @BindView(R.id.rvRView)
    RecyclerView rvRView;
    @BindView(R.id.btnDepositCompanyPaySubmit)
    Button btnDepositCompanyPaySubmit;
    DepositBankCordListResult dataBean;
    private String bankId;
    private String getArgParam1;
    private CompanyPayContract.Presenter presenter;
    List<String> stringListBankName  = new ArrayList<String>();
    public static CompanyPayOneFragment newInstance(DepositBankCordListResult dataBean, String getArgParam1) {
        CompanyPayOneFragment fragment = new CompanyPayOneFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM0, dataBean);
        args.putString(ARG_PARAM1, getArgParam1);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            dataBean = getArguments().getParcelable(ARG_PARAM0);
            getArgParam1 = getArguments().getString(ARG_PARAM1);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_companypay_one;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        tvCompanyPayBackOne.setMoreText(getArgParam1);
        tvCompanyPayBackOne.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });

        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),2, OrientationHelper.VERTICAL,false);
        rvRView.setLayoutManager(gridLayoutManager);
        rvRView.addItemDecoration(new GridRvItemDecoration(getContext()));
        rvRView.setAdapter(new RvMylistAdapter(getContext(),R.layout.item_companyone,dataBean.getData()));

        int alSize = dataBean.getData().size();
        for(int i=0;i<alSize;++i){
            stringListBankName.add(dataBean.getData().get(i).getBank_name()+dataBean.getData().get(i).getBank_user());
        }
    }

    class RvMylistAdapter extends AutoSizeRVAdapter<DepositBankCordListResult.DataBean> {
        private Context context;
        public RvMylistAdapter(Context context, int layoutId,List<DepositBankCordListResult.DataBean> datas){
            super(context, layoutId, datas);
            this.context =  context;
        }
        @Override
        protected void convert(ViewHolder holder, final DepositBankCordListResult.DataBean dataBean1, final int position) {

            holder.setOnClickListener(R.id.llItemMySelf, new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            EventBus.getDefault().post(new StartBrotherEvent(CompanyPayTwoFragment.newInstance(dataBean1,getArgParam1), SupportFragment.SINGLETASK));
                            //showMessage("你选中了 "+dataBean1.getBank_account());
                        }
                    });

            holder.setText(R.id.tvItemMyName,dataBean1.getBank_name());
            holder.setText(R.id.ivItemMyUser,dataBean1.getBank_user());

        }


    }




    @OnClick({R.id.btnDepositCompanyPaySubmit})
    public void onViewClicked(View view ) {
        switch (view.getId()){
            case R.id.btnDepositCompanyPaySubmit:
                DoubleClickHelper.getNewInstance().disabledView(btnDepositCompanyPaySubmit);
                break;
        }

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }


}
