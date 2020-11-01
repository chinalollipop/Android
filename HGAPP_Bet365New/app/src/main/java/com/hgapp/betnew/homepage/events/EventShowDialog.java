package com.hgapp.betnew.homepage.events;

import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

import com.hgapp.betnew.R;
import com.hgapp.betnew.base.HGBaseDialogFragment;
import com.hgapp.betnew.homepage.handicap.ShowMainEvent;

import org.greenrobot.eventbus.EventBus;

import butterknife.BindView;
import butterknife.OnClick;

public class EventShowDialog extends HGBaseDialogFragment {

    public static final String PARAM0 = "param0";
    @BindView(R.id.eventShowText)
    TextView eventShowText;
    String getParam0 = "";

    public static EventShowDialog newInstance(String param0, String param1) {
        Bundle bundle = new Bundle();
        bundle.putString(PARAM0, param0);
        EventShowDialog dialog = new EventShowDialog();
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    protected int getLayoutResId() {
        return R.layout.dialog_event_show;
    }

    @Override
    protected void initView(View view, Bundle bundle) {
        //EventBus.getDefault().register(this);
        getParam0 = getArguments().getString(PARAM0);
        eventShowText.setText(getParam0);
    }

    @OnClick({R.id.eventShowCancel, R.id.eventShowSuccess})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.eventShowCancel:
                EventBus.getDefault().post(new ShowMainEvent(0));
                this.dismiss();
                break;
            case R.id.eventShowSuccess:
                EventBus.getDefault().post(new ShowMainEvent(0));
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
