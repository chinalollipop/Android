package com.cfcp.a01.ui.home;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseDialogFragment;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.ui.home.texthtml.html.HtmlUtils;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class EventShowDialog extends BaseDialogFragment {

    public static final String PARAM0 = "param0";
    String getParam0 = "";
    @BindView(R.id.eventShowCancel)
    ImageView eventShowCancel;

    /*@BindView(R.id.RecyclerView)
    WebView eventShowRView;*/
    @BindView(R.id.eventShowRView)
    RecyclerView eventShowRView;

    List<LoginResult.NoticeListBean> data = new ArrayList<>();

    public static EventShowDialog newInstance(ArrayList<LoginResult.NoticeListBean> data, String param1) {
        Bundle bundle = new Bundle();
        bundle.putParcelableArrayList(PARAM0, data);
        EventShowDialog dialog = new EventShowDialog();
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    protected int setLayoutId() {
        return R.layout.dialog_event_show;
    }

    @Override
    protected void setEvents(View view, Bundle bundle) {

        data = getArguments().getParcelableArrayList(PARAM0);
        data.get(0).setChecked(1);
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        eventShowRView.setLayoutManager(linearLayoutManager);
        EventShowAdapter eventShowAdapter = new EventShowAdapter(R.layout.item_dialog_event, data);
        eventShowRView.setAdapter(eventShowAdapter);
        eventShowAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
//                LinearLayout linearLayout =  view.findViewById(R.id.itemDialogLayout);
                TextView webView = view.findViewById(R.id.itemDialogContent);
                GameLog.log("您点击了子项目是否展示 " + webView.isShown());
                if (webView.isShown()) {
                    webView.setVisibility(View.GONE);
                } else {
                    webView.setVisibility(View.VISIBLE);
                }
                /*data.get(position).setChecked(1);
                adapter.notifyDataSetChanged();*/
            }
        });
    }

    //联合一起使用新的适配器 https://github.com/CymChad/BaseRecyclerViewAdapterHelper
    class EventShowAdapter extends BaseQuickAdapter<LoginResult.NoticeListBean, BaseViewHolder> {

        public EventShowAdapter(int layoutResId, @Nullable List<LoginResult.NoticeListBean> datas) {
            super(layoutResId, datas);
        }

        @Override
        protected void convert(BaseViewHolder helper, LoginResult.NoticeListBean item) {
            if (item.getChecked() == 1) {
                helper.setVisible(R.id.itemDialogContent, true);
            } else {
                helper.setGone(R.id.itemDialogContent, false);
            }
            helper.setText(R.id.itemDialogTitle, item.getTitle()).
                    addOnClickListener(R.id.itemDialogLayout).
                    setText(R.id.itemDialogContent, HtmlUtils.getHtml(getContext(), (TextView) helper.getView(R.id.itemDialogContent), item.getContent())).
                    setText(R.id.itemDialogTime, item.getUpdated_at().split(" ")[0]);
        }
    }


    @OnClick({R.id.eventShowCancel})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.eventShowCancel:
                this.dismiss();
                break;
        }
    }

    @Override
    public void onDestroyView() {
        //EventBus.getDefault().unregister(this);
        super.onDestroyView();
    }

}
