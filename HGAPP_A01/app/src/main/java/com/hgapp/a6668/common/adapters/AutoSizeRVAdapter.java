package com.hgapp.a6668.common.adapters;

import android.content.Context;
import android.view.View;

import com.zhy.adapter.recyclerview.CommonAdapter;
import com.zhy.adapter.recyclerview.base.ViewHolder;
import com.zhy.autolayout.utils.AutoUtils;

import java.util.List;

/**
 * Created by AK on 2017/8/15.
 */

public abstract class AutoSizeRVAdapter<T> extends CommonAdapter<T>{
    public AutoSizeRVAdapter(Context context, int layoutId, List<T> datas) {
        super(context, layoutId, datas);
    }

    /*@Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        ViewHolder viewHolder  = super.onCreateViewHolder(parent, viewType);
        AutoUtils.auto(viewHolder.getConvertView());
        return viewHolder;
    }*/

    @Override
    public void onViewHolderCreated(ViewHolder holder, View itemView) {
        super.onViewHolderCreated(holder, itemView);
        AutoUtils.auto(itemView);
    }
}
