package com.hfcp.hf.ui.home.sidebar;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ExpandableListView;
import android.widget.FrameLayout;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.hfcp.hf.CFConstant;
import com.hfcp.hf.Injections;
import com.hfcp.hf.R;
import com.hfcp.hf.common.base.BaseDialogFragment;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.event.StartBrotherEvent;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.common.utils.Check;
import com.hfcp.hf.common.utils.GameLog;
import com.hfcp.hf.common.utils.GameShipHelper;
import com.hfcp.hf.common.widget.NExpandableListView;
import com.hfcp.hf.data.BalanceResult;
import com.hfcp.hf.data.LoginResult;
import com.hfcp.hf.data.LogoutResult;
import com.hfcp.hf.ui.home.deposit.DepositFragment;
import com.hfcp.hf.ui.home.withdraw.WithDrawFragment;
import com.hfcp.hf.ui.main.MainEvent;
import com.hfcp.hf.ui.me.CaiInfoFragment;
import com.hfcp.hf.ui.me.EventListFragment;
import com.hfcp.hf.ui.me.MeContract;
import com.hfcp.hf.ui.me.bankcard.CardFragment;
import com.hfcp.hf.ui.me.emailbox.EmailBoxFragment;
import com.hfcp.hf.ui.me.game.GameFragment;
import com.hfcp.hf.ui.me.pwd.PwdFragment;
import com.hfcp.hf.ui.me.record.BetRecordFragment;
import com.hfcp.hf.ui.me.record.overbet.TraceListFragment;
import com.hfcp.hf.ui.me.report.PersonFragment;
import com.hfcp.hf.ui.me.report.TeamFragment;
import com.hfcp.hf.ui.me.report.myreport.MyReportFragment;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;

public class SideBarFragment extends BaseDialogFragment implements MeContract.View{
    @BindView(R.id.sidebarFrame)
    FrameLayout sidebarFrame;
    @BindView(R.id.sidebarUser)
    TextView sidebarUser;
    @BindView(R.id.sidebarDeposit)
    LinearLayout sidebarDeposit;
    @BindView(R.id.sidebarWithDraw)
    LinearLayout sidebarWithDraw;
    @BindView(R.id.sidebarRecyView)
    NExpandableListView sidebarRecyView;
    MeContract.Presenter presenter;

    // 数据源
    private String[] groups = {
            "投注记录",
            "报表管理",
            "账号管理",
            //"代理管理",
            "短信公告",
            "开奖结果",
            "返回大厅",
            "退出登录" };
    private String[][] children = {
            { "游戏记录", "追号记录" },
            { "资金明细", "盈亏状态","团队报表", "账变报表", "优惠活动详情" },
            { "开元棋牌余额","乐游棋牌余额","AG游戏余额", "修改密码","银行卡管理",  "我的奖金组"},
            //{ "个人总览", "修改密码", "密码设定","银行卡管理", "资料修改", "彩种信息" , "彩种额度"},
            //{ "团队总览", "用户列表","注册管理", "推广注册"  },
            { "站内短信", "网站公告" },
            {  },
            {  },
            {  }
    };
    private MyExpandableAdapter myExAdapter;
    static {

    }

    public static SideBarFragment newInstance() {
        SideBarFragment sideBarFragment = new SideBarFragment();
        Injections.inject(sideBarFragment, null);
        return sideBarFragment;
    }

   /* @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        Context ctxWithTheme = new ContextThemeWrapper(getActivity().getApplicationContext(),R.style.margintran);
        //通过生成的Context创建一个LayoutInflater
        LayoutInflater localLayoutInflater = inflater.cloneInContext(ctxWithTheme);
        return super.onCreateView(localLayoutInflater, container, savedInstanceState);
    }*/

    @Override
    public int setLayoutId() {
        return R.layout.fragment_sidebar;
    }


    @Override
    public void setEvents(View view,@Nullable Bundle savedInstanceState) {
        setCancelable(true);
        EventBus.getDefault().register(this);
        //sidebarFrame.getBackground().setAlpha(200);
        if(Check.isNull(presenter)){
            presenter = Injections.inject(this,null);
        }
        presenter.getBalance();
        sidebarUser.setText(""+ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT)+" 余额："+
                GameShipHelper.formatMoney(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_BALANCE))+" 元");
        myExAdapter = new MyExpandableAdapter(getContext(), groups, children);
        sidebarRecyView.setAdapter(myExAdapter);
        sidebarRecyView.setOnGroupClickListener(new ExpandableListView.OnGroupClickListener() {
            @Override
            public boolean onGroupClick(ExpandableListView parent, View v, int groupPosition, long id) {
                switch (groupPosition){
                    case 4:
                        //showMessage("开奖结果");
                        //展示开奖结果 且 关闭投注界面
                        EventBus.getDefault().post(new MainEvent(3));
                        EventBus.getDefault().post(new LotteryResultEvent("LotteryResult"));
                        hide();
                        break;
                    case 5:
                        //showMessage("返回大厅");
                        EventBus.getDefault().post(new BackHomeEvent("BackHome"));
                        hide();
                        break;
                    case 6:
                        //showMessage("退出登录");
                        presenter.postLogout("");
                        break;
                }
                return false;
            }
        });
        sidebarRecyView.setOnChildClickListener(new ExpandableListView.OnChildClickListener() {
            @Override
            public boolean onChildClick(ExpandableListView parent, View v, int groupPosition, int childPosition, long id) {
                switch (groupPosition){
                    case 0:
                        switch (childPosition){
                            case 0:
                                hide();
                                //showMessage("游戏记录");
                                EventBus.getDefault().post(new StartBrotherEvent(BetRecordFragment.newInstance("","")));
                                break;
                            case 1:
                                hide();
                                //showMessage("追号记录");
                                EventBus.getDefault().post(new StartBrotherEvent(TraceListFragment.newInstance("","")));
                                break;
                        }
                        break;
                    case 1:
                        switch (childPosition){
                            case 0:
                                hide();
                                EventBus.getDefault().post(new StartBrotherEvent(MyReportFragment.newInstance("1","")));
                                //showMessage("充值记录");
                                break;
                            case 1:
                                //showMessage("个人报表");
                                hide();
                                //EventBus.getDefault().post(new StartBrotherEvent(PersonFragment.newInstance("","")));
                                EventBus.getDefault().post(new StartBrotherEvent(TeamFragment.newInstance("","person")));
                                break;
                            case 2:
                                //showMessage("团队报表");
                                hide();
                                EventBus.getDefault().post(new StartBrotherEvent(TeamFragment.newInstance("","team")));
                                break;
                            case 3:
                                hide();
                                //showMessage("账变报表");
                                EventBus.getDefault().post(new StartBrotherEvent(MyReportFragment.newInstance("0","")));
                                break;
                            case 4:
                                //showMessage("优惠活动详情");
                                hide();
                                EventBus.getDefault().post(new MainEvent(2));
                                break;
                        }
                        break;
                    case 2:
                        switch (childPosition){
                            case 0:
                                //showMessage("个人总览");
                                //EventBus.getDefault().post(new StartBrotherEvent(InfoFragment.newInstance("","")));
                                EventBus.getDefault().post(new StartBrotherEvent(GameFragment.newInstance(children[groupPosition][childPosition],"KaiyuanGame")));
                                hide();
                                break;
                            case 1:
                                hide();
                                EventBus.getDefault().post(new StartBrotherEvent(GameFragment.newInstance(children[groupPosition][childPosition],"LeyouGame")));
                                //showMessage("修改密码");
                                break;
                            case 2:
                                hide();
                                EventBus.getDefault().post(new StartBrotherEvent(GameFragment.newInstance(children[groupPosition][childPosition],"AgGame")));
                                //showMessage("密码设定");
                                //EventBus.getDefault().post(new StartBrotherEvent(PwdFragment.newInstance("1","")));
                                break;
                            case 3:
                                hide();
                                EventBus.getDefault().post(new StartBrotherEvent(PwdFragment.newInstance("1","")));
                                //showMessage("银行卡管理");
                                break;
                            case 4:
                                hide();
                                EventBus.getDefault().post(new StartBrotherEvent(CardFragment.newInstance("","")));
                                //showMessage("资料修改");
                                break;
                            case 5:
                                hide();
                                EventBus.getDefault().post(new StartBrotherEvent(CaiInfoFragment.newInstance("2","")));
                                //showMessage("彩种信息");
                                break;
                        }
                        break;
                    case 3:
                        switch (childPosition){
                            case 0:
                                //showMessage("站内短信");
                                hide();
                                EventBus.getDefault().post(new StartBrotherEvent(EmailBoxFragment.newInstance("","")));
                                break;
                            case 1:
                                hide();
                                //showMessage("网站公告");
                                EventBus.getDefault().post(new StartBrotherEvent(EventListFragment.newInstance("","")));
                                break;
                        }
                        //以下是代理管理的内容
                        /*switch (childPosition){
                            case 0:
                                showMessage("团队总览");
                                break;
                            case 1:
                                showMessage("用户列表");
                                //EventBus.getDefault().post(new StartBrotherEvent(UserListFragment.newInstance("2","")));
                                break;
                            case 2:
                                //showMessage("注册管理");
                                hide();
                                EventBus.getDefault().post(new StartBrotherEvent(RegisterMeFragment.newInstance("",""), SupportFragment.SINGLETASK));
                                break;
                            case 3:
                                showMessage("推广注册");
                                break;
                        }*/
                        break;
                    case 4:
                        switch (childPosition){
                            case 0:
                                //showMessage("站内短信");
                                hide();
                                EventBus.getDefault().post(new StartBrotherEvent(EmailBoxFragment.newInstance("","")));
                                break;
                            case 1:
                                hide();
                                //showMessage("网站公告");
                                EventBus.getDefault().post(new StartBrotherEvent(EventListFragment.newInstance("","")));
                                break;
                        }
                        break;

                }
                return false;
            }
        });
        sidebarRecyView.setOnGroupExpandListener(new ExpandableListView.OnGroupExpandListener() {
            @Override
            public void onGroupExpand(int groupPosition) {
                for (int i = 0; i < groups.length; i++) {
                    if (groupPosition != i) {
                        sidebarRecyView.collapseGroup(i);
                    }
                }
            }
        });
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        hide();
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("================注册页需要消失的================");
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    @OnClick({R.id.sidebarFrame,R.id.sidebarUser, R.id.sidebarDeposit, R.id.sidebarWithDraw})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.sidebarFrame:
                hide();
                break;
            case R.id.sidebarUser:
                break;
            case R.id.sidebarDeposit:
                hide();
                EventBus.getDefault().post(new StartBrotherEvent(DepositFragment.newInstance(), SupportFragment.SINGLETASK));
                break;
            case R.id.sidebarWithDraw:
                hide();
                /*if(Check.isEmpty(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_NAME))){
                    EventBus.getDefault().post(new StartBrotherEvent(CardFragment.newInstance("","")));
                }else {
                }*/
                EventBus.getDefault().post(new StartBrotherEvent(WithDrawFragment.newInstance("", ""), SupportFragment.SINGLETASK));
                break;
        }
    }

    @Override
    public void postLogoutResult(LogoutResult logoutResult) {
        //退出登录的逻辑  发送消息
        EventBus.getDefault().post(new LogoutResult("您已登出!"));
        hide();
    }

    @Override
    public void getBalanceResult(BalanceResult balanceResult) {
        sidebarUser.setText(""+ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT)+" 余额："+
                GameShipHelper.formatMoney(balanceResult.getAvailable())+" 元");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_BALANCE,balanceResult.getAvailable());
    }

    @Override
    public void setPresenter(MeContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

}
