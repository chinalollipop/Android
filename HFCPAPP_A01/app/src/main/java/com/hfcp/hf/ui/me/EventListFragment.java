package com.hfcp.hf.ui.me;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.hfcp.hf.CFConstant;
import com.hfcp.hf.Injections;
import com.hfcp.hf.R;
import com.hfcp.hf.common.base.BaseFragment;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.common.utils.GameLog;
import com.hfcp.hf.common.widget.NTitleBar;
import com.hfcp.hf.data.LoginResult;
import com.hfcp.hf.data.PersonReportResult;
import com.hfcp.hf.ui.home.texthtml.html.HtmlUtils;
import com.hfcp.hf.ui.me.report.PersonContract;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;

public class EventListFragment extends BaseFragment implements PersonContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    private String typeArgs2, typeArgs3;
    PersonContract.Presenter presenter;
    @BindView(R.id.eventListBack)
    NTitleBar eventListBack;
    @BindView(R.id.eventListRView)
    RecyclerView eventListRView;
    String startTime, endTime;
    List<LoginResult.NoticeListBean> noticeListBeanList;
    public static EventListFragment newInstance(String deposit_mode, String money) {
        EventListFragment betFragment = new EventListFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_event_list;
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
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        eventListRView.setLayoutManager(linearLayoutManager);
        eventListBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        noticeListBeanList = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_EVENTLIST), LoginResult.NoticeListBean.class);

        EventListAdapter eventShowAdapter = new EventListAdapter(R.layout.item_event_list, noticeListBeanList);
        eventShowAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
//                LinearLayout linearLayout =  view.findViewById(R.id.itemDialogLayout);
                TextView webView =  view.findViewById(R.id.itemDialogContent);
                GameLog.log("您点击了子项目是否展示 "+webView.isShown());
                if(webView.isShown()){
                    webView.setVisibility(View.GONE);
                }else{
                    webView.setVisibility(View.VISIBLE);
                }
                /*noticeListBeanList.get(position).setChecked(1);
                adapter.notifyDataSetChanged();*/
            }
        });
        eventListRView.setAdapter(eventShowAdapter);
    }


    class EventListAdapter extends BaseQuickAdapter<LoginResult.NoticeListBean, BaseViewHolder>{

        public EventListAdapter(int layoutResId, @Nullable List<LoginResult.NoticeListBean> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, LoginResult.NoticeListBean item) {
            if(item.getChecked()==1){
                helper.setVisible(R.id.itemDialogContent,true);
            }else{
                helper.setGone(R.id.itemDialogContent,false);
            }
            helper.setText(R.id.itemDialogTitle,item.getTitle()).
                    addOnClickListener(R.id.itemDialogLayout).
                    setText(R.id.itemDialogContent, HtmlUtils.getHtml(getContext(),(TextView)helper.getView(R.id.itemDialogContent),item.getContent())).
                    setText(R.id.itemDialogTime,item.getUpdated_at());
        }
    }


    @Override
    public void getPersonReportResult(PersonReportResult personReportResult) {
    }



    @Override
    public void setPresenter(PersonContract.Presenter presenter) {
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
