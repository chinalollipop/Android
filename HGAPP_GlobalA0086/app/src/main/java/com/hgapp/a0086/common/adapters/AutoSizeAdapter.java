package com.hgapp.a0086.common.adapters;

import android.content.Context;
import android.view.View;
import android.view.ViewGroup;

import com.zhy.adapter.abslistview.CommonAdapter;
import com.zhy.autolayout.utils.AutoUtils;

import java.util.List;

/**
 * Created by Daniel on 2017/7/6.
 */

public abstract class AutoSizeAdapter<T> extends CommonAdapter<T> {
    public AutoSizeAdapter(Context context, int layoutId, List datas) {
        super(context, layoutId, datas);
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        View view = super.getView(position,convertView,parent);
        AutoUtils.auto(view);
        return view;
    }
}
