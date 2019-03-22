package com.cfcp.a01.common.base;


import android.content.Context;
import android.util.AttributeSet;
import android.view.View;

import me.yokeyword.fragmentation.SupportActivity;

/**
 * Created by Daniel on 2018/12/29.
 */

public abstract class BaseMainActivity extends SupportActivity {

    @Override
    public View onCreateView(String name, Context context, AttributeSet attrs) {
        View view = null;

        if (view != null) return view;

        return super.onCreateView(name, context, attrs);
    }


}
