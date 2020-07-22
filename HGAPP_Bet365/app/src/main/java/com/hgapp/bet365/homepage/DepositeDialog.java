package com.hgapp.bet365.homepage;

import android.view.View;
import android.widget.TextView;

import com.hgapp.bet365.R;
import com.hgapp.bet365.common.widgets.bottomdialog.NBaseBottomDialog;
import com.hgapp.bet365.data.MessageTopEvent;
import com.hgapp.bet365.homepage.handicap.ShowMainEvent;

import org.greenrobot.eventbus.EventBus;

import butterknife.BindView;
import butterknife.OnClick;

public class DepositeDialog extends NBaseBottomDialog {
    @BindView(R.id.dialogHomeDeposite)
    TextView dialogHomeDeposite;
    @BindView(R.id.dialogHomeWithDraw)
    TextView dialogHomeWithDraw;

    public static DepositeDialog newInstance(){

        DepositeDialog depositeDialog = new DepositeDialog();
        return depositeDialog;
    }
    @Override
    public int getLayoutRes() {
        return R.layout.dialog_home_depositw_show;
    }

    @Override
    public void bindView(View v) {



    }

    @OnClick({R.id.dialogHomeDeposite,R.id.dialogHomeWithDraw})
    public void onViewClicked(View view) {

        switch (view.getId()){
            case R.id.dialogHomeWithDraw:
                EventBus.getDefault().post(new MessageTopEvent(2,"HomeWithDraw"));
                this.dismiss();
                break;
            case R.id.dialogHomeDeposite:
                EventBus.getDefault().post(new MessageTopEvent(2,"HomeDeposite"));
                this.dismiss();
                break;
        }
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().post(new ShowMainEvent(0));
    }
}
