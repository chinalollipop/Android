package com.sunapp.bloc.homepage.noticelist;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ListView;

import com.sunapp.bloc.R;
import com.sunapp.bloc.base.HGBaseFragment;
import com.sunapp.bloc.common.widgets.NTitleBar;
import com.sunapp.bloc.data.NoticeResult;
import com.zhy.adapter.abslistview.ViewHolder;

import java.util.List;

import butterknife.BindView;

public class NoticeListFragment extends HGBaseFragment{

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    @BindView(R.id.backNoticeList)
    NTitleBar backNoticeList;
    @BindView(R.id.lvNoticeList)
    ListView lvNoticeList;
    private String cate;
    private String active;
    private String type;
    NoticeResult noticeResult;
    public static NoticeListFragment newInstance(NoticeResult noticeResult, String active, String type) {
        NoticeListFragment fragment = new NoticeListFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM1,noticeResult);
        args.putString(ARG_PARAM2,active);
        args.putString(ARG_PARAM3,type);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            noticeResult = getArguments().getParcelable(ARG_PARAM1);
            active = getArguments().getString(ARG_PARAM2);
            type =  getArguments().getString(ARG_PARAM3);
        }
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_noticelist;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        backNoticeList.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });
        lvNoticeList.setAdapter(new SportsListAdapter(getContext(),R.layout.item_notice,noticeResult.getData()));
    }

    public class SportsListAdapter extends com.sunapp.bloc.common.adapters.AutoSizeAdapter<NoticeResult.DataBean> {
        private Context context;

        public SportsListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final NoticeResult.DataBean dataBean, final int position) {
            if(position==0){
                holder.setBackgroundRes(R.id.tvContentCount,R.drawable.notice_item_default);
            }else{
                holder.setBackgroundRes(R.id.tvContentCount,R.drawable.notice_item_normal);
            }

            holder.setText(R.id.tvContentCount, position+1+"");
            holder.setText(R.id.tvContent, dataBean.getNotice());
        }
    }

}
