package com.hfcp.hf.ui.me.emailbox;

import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.TextView;

import com.hfcp.hf.Injections;
import com.hfcp.hf.R;
import com.hfcp.hf.common.base.BaseFragment;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.utils.GameLog;
import com.hfcp.hf.common.widget.NTitleBar;
import com.hfcp.hf.data.EmailBoxListResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;

public class EmailBoxFragment extends BaseFragment implements EmailBoxContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    private String typeArgs2, typeArgs3;
    EmailBoxContract.Presenter presenter;
    @BindView(R.id.emailListBack)
    NTitleBar emailListBack;
    @BindView(R.id.emailListBackRView)
    RecyclerView emailListBackRView;
    String startTime, endTime;
    List<EmailBoxListResult.ListBean> noticeListBeanList;
    public static EmailBoxFragment newInstance(String deposit_mode, String money) {
        EmailBoxFragment betFragment = new EmailBoxFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_email_box;
    }


    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs2 = getArguments().getString(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }



    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        presenter.getPersonReport("","");
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        emailListBackRView.setLayoutManager(linearLayoutManager);
        emailListBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
    }


    class EmailListAdapter extends BaseQuickAdapter<EmailBoxListResult.ListBean, BaseViewHolder>{

        public EmailListAdapter(int layoutResId, @Nullable List<EmailBoxListResult.ListBean> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, EmailBoxListResult.ListBean item) {
            helper.setText(R.id.itemEmailTitle,item.getMsg_title()).
                    setText(R.id.itemEmailTime,item.getCreated_at());
        }
    }

    @Override
    public void getPersonReportResult(EmailBoxListResult emailBoxListResult) {

        GameLog.log("收件箱的数据正常");
        noticeListBeanList = emailBoxListResult.getList();
        EmailListAdapter emailListAdapter = new EmailListAdapter(R.layout.item_email_list, noticeListBeanList);
        if(noticeListBeanList.size()==0){
            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
            TextView textView = view.findViewById(R.id.itemNoDate);
            textView.setText("当前查询条件下暂无数据");
            textView.setTextColor(Color.parseColor("#C52133"));
            emailListAdapter.setEmptyView(view);
        }
        emailListBackRView.setAdapter(emailListAdapter);
    }

    @Override
    public void setPresenter(EmailBoxContract.Presenter presenter) {
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

}
