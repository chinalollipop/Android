package com.cfcp.a01.ui.me.game;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.TextView;

import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.GameQueueMoneyResult;
import com.kongzue.dialog.v3.WaitDialog;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class GameFragment extends BaseFragment implements GameContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.gameBack)
    NTitleBar gameBack;

    @BindView(R.id.gameName)
    TextView gameName;

    @BindView(R.id.gamesMsg)
    TextView gamesMsg;
    @BindView(R.id.gamefTotalMoney)
    TextView gamefTotalMoney;
    @BindView(R.id.gamefFreeMoney)
    TextView gamefFreeMoney;

    private String typeArgs1, typeArgs2;
    GameContract.Presenter presenter;

    public static GameFragment newInstance(String gameName, String gameAction) {
        GameFragment betFragment = new GameFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, gameName);
        args.putString(TYPE3, gameAction);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_game;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs1 = getArguments().getString(TYPE2);
            typeArgs2 = getArguments().getString(TYPE3);
        }
    }


    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        gameBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        gameName.setText("查询"+typeArgs1);
        presenter.getLowerLevelReport(typeArgs2);
    }

    //请求数据接口
    private void onRequsetData() {
        WaitDialog.show((AppCompatActivity) _mActivity,"加载中...");
        presenter.getPlayOutWithMoney(typeArgs2);
    }

    @Override
    public void getPlayOutWithMoneyResult(GameQueueMoneyResult gameQueueMoneyResult) {
        //转账前渠道确认
        GameLog.log("设置真实姓名 成功");
        WaitDialog.dismiss();
        showMessage("转入平台成功！");
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        WaitDialog.dismiss();
    }

    @Override
    public void getLowerLevelReportResult(GameQueueMoneyResult gameQueueMoneyResult) {
        GameLog.log("获取余额成功了 ");
        gamesMsg.setText(gameQueueMoneyResult.getSMsg());
        gamefTotalMoney.setText(gameQueueMoneyResult.getFTotalMoney());
        gamefFreeMoney.setText(gameQueueMoneyResult.getFFreeMoney());
    }

    @Override
    public void setPresenter(GameContract.Presenter presenter) {
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

    @OnClick(R.id.gameWithdraw)
    public void onViewClicked() {
        onRequsetData();
    }
}
