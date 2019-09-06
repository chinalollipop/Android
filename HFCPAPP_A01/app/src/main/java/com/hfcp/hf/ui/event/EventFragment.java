package com.hfcp.hf.ui.event;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.hfcp.hf.Injections;
import com.hfcp.hf.R;
import com.hfcp.hf.common.base.BaseFragment;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.http.Client;
import com.hfcp.hf.common.utils.Check;
import com.hfcp.hf.common.utils.GameLog;
import com.hfcp.hf.data.CouponResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;

//优惠活动
public class EventFragment extends BaseFragment implements EventContract.View{
    EventContract.Presenter presenter;
    @BindView(R.id.activityRView)
    RecyclerView activityRView;
    List<CouponResult.DataBean> dataBeanList;
    public static EventFragment newInstance() {
        EventFragment activityFragment = new EventFragment();
        Injections.inject(activityFragment, null);
        return activityFragment;
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_activity;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
//        EventBus.getDefault().register(this);
        if(Check.isNull(presenter)){
            presenter =  Injections.inject(this, null);
        }
        presenter.getCoupon("","","");
    }


    @Override
    public void onDestroyView() {
        super.onDestroyView();
//        EventBus.getDefault().unregister(this);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        //showMessage("开奖结果界面");
        //EventBus.getDefault().post(new MainEvent(0));
    }

    @Override
    public void getCouponResult(CouponResult couponResult) {
        dataBeanList =couponResult.getData();
        GameLog.log("优惠活动列表大小 "+couponResult.getData().size());
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
        activityRView.setLayoutManager(linearLayoutManager);/*
        activityRView.setHasFixedSize(true);
        activityRView.setNestedScrollingEnabled(false);*/
        EventAdapter eventAdapter = new EventAdapter(R.layout.item_event,dataBeanList);
        activityRView.setAdapter(eventAdapter);
        eventAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                if(dataBeanList.get(position).isShow()){
                    dataBeanList.get(position) .setShow(false);
                }else{
                    dataBeanList.get(position) .setShow(true);
                }
                adapter.notifyItemChanged(position);
            }
        });
    }

    class EventAdapter extends BaseQuickAdapter<CouponResult.DataBean, BaseViewHolder> {

        public EventAdapter(int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final CouponResult.DataBean data) {
            //holder.setText(R.id.itemEventText,data.getContent());
            if(data.getPic_url().contains("http")){
                GameLog.log("图片地址Pic_url 是："+data.getPic_url());
                GameLog.log("图片地址Redirect是："+data.getRedirect_url());
                Glide.with(EventFragment.this).load(data.getPic_url()).apply(new RequestOptions().fitCenter()).into((ImageView) holder.getView(R.id.itemEventId));
                Glide.with(EventFragment.this).load(data.getRedirect_url()).apply(new RequestOptions().fitCenter()).into((ImageView) holder.getView(R.id.itemEventText));
            }else {
                String url = Client.baseUrl().replace("api.", "");
                GameLog.log("图片地址是：" + url + data.getPic_url().substring(1));
                GameLog.log("图片地址Redirect是：" + url + data.getRedirect_url().substring(1));
                Glide.with(EventFragment.this).load(url + data.getPic_url().substring(1)).apply(new RequestOptions().fitCenter()).into((ImageView) holder.getView(R.id.itemEventId));
                Glide.with(EventFragment.this).load(url + data.getRedirect_url().substring(1)).apply(new RequestOptions().fitCenter()).into((ImageView) holder.getView(R.id.itemEventText));
            }

            if(data.isShow()){
                holder.setVisible(R.id.itemEventText,true);
            }else{
                holder.setGone(R.id.itemEventText,false);
            }
            holder.addOnClickListener(R.id.itemEventId);
        }
    }

    @Override
    public void setPresenter(EventContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }
}
