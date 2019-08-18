package com.flush.a01.base;


import android.content.Context;
import android.util.AttributeSet;
import android.view.View;


import me.yokeyword.fragmentation.SupportActivity;

/**
 * Created by Daniel on 2018/12/29.
 */

public abstract class BaseMainActivity extends SupportActivity {

    private static final String LAYOUT_LINEARLAYOUT = "LinearLayout";
    private static final String LAYOUT_FRAMELAYOUT = "FrameLayout";
    private static final String LAYOUT_RELATIVELAYOUT = "RelativeLayout";


    @Override
    public View onCreateView(String name, Context context, AttributeSet attrs) {
        View view = null;
       /* if (name.equals(LAYOUT_FRAMELAYOUT)) {
            view = new AutoFrameLayout(context, attrs);
        }

        if (name.equals(LAYOUT_LINEARLAYOUT)) {
            view = new AutoLinearLayout(context, attrs);
        }

        if (name.equals(LAYOUT_RELATIVELAYOUT)) {
            view = new AutoRelativeLayout(context, attrs);
        }*/

        if (view != null) return view;

        return super.onCreateView(name, context, attrs);
    }


}
