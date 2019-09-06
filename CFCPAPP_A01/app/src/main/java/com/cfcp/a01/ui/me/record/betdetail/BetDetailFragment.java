package com.cfcp.a01.ui.me.record.betdetail;

import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.BetDetailResult;
import com.cfcp.a01.ui.me.record.BetDropEvent;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class BetDetailFragment extends BaseFragment implements BetDetailContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.betDetailUserName)
    TextView betDetailUserName;
    @BindView(R.id.betDetailSerialNumber)
    TextView betDetailSerialNumber;
    @BindView(R.id.betDetaillottery_id)
    TextView betDetaillotteryId;
    @BindView(R.id.betDetailmultiple_mode)
    TextView betDetailmultipleMode;
    @BindView(R.id.betDetailissue)
    TextView betDetailissue;
    @BindView(R.id.betDetailbought_at)
    TextView betDetailboughtAt;
    @BindView(R.id.betDetailprize)
    TextView betDetailprize;
    @BindView(R.id.betDetailwinning_number)
    TextView betDetailwinningNumber;
    @BindView(R.id.betDetailtitle)
    TextView betDetailtitle;
    @BindView(R.id.betDetailamount)
    TextView betDetailamount;
    @BindView(R.id.betDetailstatus)
    TextView betDetailstatus;
    @BindView(R.id.betDetailrepeal)
    TextView betDetailrepeal;
    @BindView(R.id.betDetailbet_number)
    TextView betDetailbetNumber;
    @BindView(R.id.betDetailtitle2)
    TextView betDetailtitle2;
    @BindView(R.id.betDetailbet_number2)
    TextView betDetailbetNumber2;
    @BindView(R.id.betDetailmultiple)
    TextView betDetailmultiple;
    @BindView(R.id.betDetailprize_set_formatted)
    TextView betDetailprizeSetFormatted;
    @BindView(R.id.betDetailprize_group)
    TextView betDetailprizeGroup;
    Unbinder unbinder;
    private String typeArgs1, typeArgs2;
    BetDetailContract.Presenter presenter;
    @BindView(R.id.betDetailBack)
    NTitleBar betDetailBack;

    public static BetDetailFragment newInstance(String deposit_mode, String money) {
        BetDetailFragment betFragment = new BetDetailFragment();
        Bundle args = new Bundle();
        args.putString(TYPE1, deposit_mode);
        args.putString(TYPE2, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_record_bet_detail;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs1 = getArguments().getString(TYPE1);
            typeArgs2 = getArguments().getString(TYPE2);
        }
    }


    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }

    private void onDataRequest() {
        if(Check.isNull(presenter)){
            presenter = Injections.inject(this, null);
        }
        presenter.getProjectDetail(typeArgs1);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        betDetailUserName.setText(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT));
        onDataRequest();
        betDetailBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
    }

    @Override
    public void getProjectDetailResult(BetDetailResult betDetailResult) {
        //获取详情
        betDetailSerialNumber.setText(betDetailResult.getSerial_number());
        switch (betDetailResult.getLottery_id()){
            case 49:
            case 48:
                betDetaillotteryId.setText("幸运飞艇");
                break;
            case 52:
                betDetaillotteryId.setText("北京赛车五分彩");
                break;
            case 1:
                betDetaillotteryId.setText("欢乐生肖");
                break;
            case 9:
                betDetaillotteryId.setText("广东11选5");
                break;
            case 10:
                betDetaillotteryId.setText("北京PK10");
                break;
            case 13:
                betDetaillotteryId.setText("分分彩");
                break;
            case 14:
                betDetaillotteryId.setText("11选5");
                break;
            case 15:
                betDetaillotteryId.setText("江苏快三");
                break;
            case 16:
                betDetaillotteryId.setText("三分彩");
                break;
            case 50:
                betDetaillotteryId.setText("快三五分彩");
                break;
            case 51:
                betDetaillotteryId.setText("快三三分彩");
                break;
            case 17:
                betDetaillotteryId.setText("快三分分彩");
                break;
            case 19:
                betDetaillotteryId.setText("极速PK10");
                break;
            case 20:
                betDetaillotteryId.setText("极速3D");
                break;
            case 28:
                betDetaillotteryId.setText("五分彩");
                break;
            case 30:
                betDetaillotteryId.setText("安徽快三");
                break;
            case 37:
                betDetaillotteryId.setText("北京快乐8");
                break;
            case 44:
                betDetaillotteryId.setText("11选5三分彩");
                break;
        }
        betDetailmultipleMode.setText(betDetailResult.getMultiple_mode());
        betDetailissue.setText(betDetailResult.getIssue());
        betDetailboughtAt.setText(betDetailResult.getBought_at());
        betDetailprize.setText(betDetailResult.getPrize());
        betDetailwinningNumber.setText(betDetailResult.getWinning_number());
        betDetailtitle.setText(betDetailResult.getWay());
        betDetailamount.setText(betDetailResult.getAmount());
        switch (betDetailResult.getStatus()){
            case 0:
                betDetailrepeal.setVisibility(View.VISIBLE);
                betDetailstatus.setText("待开奖");
                betDetailstatus.setTextColor(Color.parseColor("#2c77ba"));
                break;
            case 1:
                betDetailstatus.setTextColor(Color.parseColor("#908e8e"));
                betDetailstatus.setText("已撤销");
                break;
            case 2:
                betDetailstatus.setText("未中奖");
                break;
            case 3:
                betDetailstatus.setText("已中奖");
        }
        //betDetailstatus.setText(betDetailResult.getStatus());
//        betDetailrepeal.setText(betDetailResult.getTrace_id());
        betDetailbetNumber.setText(betDetailResult.getBet_number());
        betDetailtitle2.setText(betDetailResult.getWay());
        betDetailbetNumber2.setText(betDetailResult.getBet_number());
        betDetailmultiple.setText(betDetailResult.getMultiple()+"");
        betDetailprizeSetFormatted.setText(betDetailResult.getPrize_set_formatted());
        betDetailprizeGroup.setText(betDetailResult.getPrize_group());

    }

    @Override
    public void getProjectDropResult(BetDetailResult betDetailResult) {
        showMessage("撤销此注单成功");
        EventBus.getDefault().post(new BetDropEvent(typeArgs1,"撤销此注单成功"));
        finish();
    }

    @Override
    public void setPresenter(BetDetailContract.Presenter presenter) {
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

    @OnClick(R.id.betDetailrepeal)
    public void onViewClicked() {
        presenter.getProjectDrop(typeArgs1);
    }
}
