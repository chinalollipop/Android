package com.hgapp.betnhg.homepage;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;

import com.hgapp.betnhg.HGApplication;
import com.hgapp.betnhg.Injections;
import com.hgapp.betnhg.R;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.common.adapters.AutoSizeRVAdapter;
import com.hgapp.betnhg.common.event.LogoutEvent;
import com.hgapp.betnhg.common.http.Client;
import com.hgapp.betnhg.common.util.ACache;
import com.hgapp.betnhg.common.util.GameShipHelper;
import com.hgapp.betnhg.common.util.HGConstant;
import com.hgapp.betnhg.common.widgets.GridRvItemDecoration;
import com.hgapp.betnhg.common.widgets.bottomdialog.NBaseBottomDialog;
import com.hgapp.betnhg.data.CPResult;
import com.hgapp.betnhg.data.NoticeResult;
import com.hgapp.betnhg.data.PersonBalanceResult;
import com.hgapp.betnhg.data.PersonInformResult;
import com.hgapp.betnhg.data.QipaiResult;
import com.hgapp.betnhg.homepage.handicap.ShowMainEvent;
import com.hgapp.betnhg.homepage.noticelist.NoticeListFragment;
import com.hgapp.betnhg.homepage.online.ContractFragment;
import com.hgapp.betnhg.homepage.online.OnlineFragment;
import com.hgapp.betnhg.login.fastlogin.LoginFragment;
import com.hgapp.betnhg.personpage.PersonContract;
import com.hgapp.betnhg.personpage.accountcenter.AccountCenterFragment;
import com.hgapp.betnhg.personpage.balancetransfer.BalanceTransferFragment;
import com.hgapp.betnhg.personpage.betrecord.BetRecordFragment;
import com.hgapp.betnhg.personpage.bindingcard.BindingCardFragment;
import com.hgapp.betnhg.personpage.depositrecord.DepositRecordFragment;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class MeDialog extends NBaseBottomDialog implements PersonContract.View{
    private  List<HomePageIcon> myList = new ArrayList<HomePageIcon>();
    @Nullable
    @BindView(R.id.rvHomepageMe)
    RecyclerView rvHomepageMe;
    String personMoney,personJoinDay,logout;
    PersonContract.Presenter presenter;
    private NoticeResult noticeResultList;
    public static MeDialog newInstance(){
        Bundle bundle = new Bundle();
        MeDialog depositeDialog = new MeDialog();
        Injections.inject(null,depositeDialog);
        depositeDialog.setArguments(bundle);
        return depositeDialog;
    }
    @Override
    public int getLayoutRes() {
        return R.layout.dialog_home_me_show;
    }

    private void initDJ(){

        myList.add(new HomePageIcon("额度转换",R.mipmap.icon_my_transfer,1,"transfer"));
        myList.add(new HomePageIcon("流水记录",R.mipmap.icon_my_running_record,8,"running_record"));
        myList.add(new HomePageIcon("银行卡",R.mipmap.icon_my_bank_card,2,"bank_card"));
        //myList.add(new HomePageIcon("平台余额",R.mipmap.icon_my_deal_c,4));
        //myList.add(new HomePageIcon("站内信",R.mipmap.icon_my_message,5));
        myList.add(new HomePageIcon("账户中心",R.mipmap.icon_my_psersion,6,"account_center"));
        myList.add(new HomePageIcon("投注记录",R.mipmap.icon_my_deal_record,7,"deal_record"));

        myList.add(new HomePageIcon("代理加盟",R.mipmap.icon_my_agent,11,"agent"));
        myList.add(new HomePageIcon("联系我们",R.mipmap.icon_my_contract,10,"contract"));
        myList.add(new HomePageIcon("公告",R.mipmap.icon_my_gonggao,11,"gonggao"));

    }

    @Override
    public void bindView(View v) {
        logout =  ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGOUT);//
        if(!Check.isNull(presenter)) {
            //presenter.getPersonBalance("", "");
            if(!Check.isNull(logout)&&!"true".equals(logout)){
                presenter.getPersonInform("");
                presenter.postNoticeList("");
            }
        }
        initDJ();
        String userName = ACache.get(getContext()).getAsString("userName");
        personMoney = GameShipHelper.formatMoney(ACache.get(getContext()).getAsString("userMoney"));
        if(!Check.isEmpty(userName)){
            myList.add(new HomePageIcon("退出",R.mipmap.icon_my_logout,11,"logout"));
        }
        GameLog.log("用户是否注销了 true 为是 --> 【"+logout+"】用户的名字是【 "+userName+"】 用户的余额为 【"+personMoney+"】");
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
        rvHomepageMe.setLayoutManager(gridLayoutManager);
        rvHomepageMe.addItemDecoration(new GridRvItemDecoration(getContext()));
        SimeAdapter     simeAdapter= new SimeAdapter(getContext(),R.layout.me_item,myList);
        rvHomepageMe.setAdapter(simeAdapter);


    }

    @Override
    public void postNoticeListResult(NoticeResult noticeResult) {
        this.noticeResultList =noticeResult;
    }

    @Override
    public void postPersonInformResult(PersonInformResult personInformResult) {

        GameLog.log("获取个人信息资料成功！");
        personMoney = GameShipHelper.formatMoney(personInformResult.getBalance_hg());
        personJoinDay = personInformResult.getJoinDays();
        ACache.get(getContext()).put("userMoney",personMoney);
        ACache.get(getContext()).put("personJoinDay",personJoinDay);
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {

    }

    @Override
    public void postQipaiResult(QipaiResult qipaiResult) {

    }

    @Override
    public void postHgQipaiResult(QipaiResult qipaiResult) {

    }

    @Override
    public void postPersonLogoutResult(String message) {
        showMessage(message);
        //EventBus.getDefault().post(new StartBrotherEvent(MainFragment.newInstance("person_to_home",""), SupportFragment.SINGLETASK));
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_STATUS+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT), "0");
        //ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT, "");
        ACache.get(getContext()).put(HGConstant.APP_CP_COOKIE,"");
        ACache.get(getContext()).put(HGConstant.USERNAME_ALIAS, "");
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGOUT, "true");
        ACache.get(getContext()).put("userName","");
        ACache.get(getContext()).put("userMoney","");
        this.dismiss();
        EventBus.getDefault().post(new LogoutEvent(message));
    }



    @Override
    public void postCPResult(CPResult cpResult) {

    }

    @Override
    public void setStart(int action) {

    }

    @Override
    public void setError(int action, int errcode) {

    }

    @Override
    public void setError(int action, String errString) {

    }

    @Override
    public void setComplete(int action) {

    }

    @Override
    public void setPresenter(PersonContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    class SimeAdapter extends AutoSizeRVAdapter<HomePageIcon> {
        private Context context;
        public SimeAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder helper, final HomePageIcon data, final int position) {
            helper.setText(R.id.id_main_item_gamenum,data.getIconName());
            helper.setBackgroundRes(R.id.id_main_item,data.getIconId());

            helper.setOnClickListener(R.id.id_main_item_line, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    onHomeGameItemClick(data.getIconNameTitle());
                }
            });

        }
    }

    private void onHomeGameItemClick(String iconNameTitle) {
        if(Check.isNull(logout)||"true".equals(logout)){
            showMessage("请先登录");
            this.dismiss();
            EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
            return;
        }
        switch (iconNameTitle){
            case "transfer":
                this.dismiss();
                EventBus.getDefault().post(new StartBrotherEvent(BalanceTransferFragment.newInstance(personMoney), SupportFragment.SINGLETASK));

                break;
            case "running_record":
                this.dismiss();
                EventBus.getDefault().post(new StartBrotherEvent(DepositRecordFragment.newInstance("T",personMoney), SupportFragment.SINGLETASK));
                break;
            case "bank_card":
                this.dismiss();
                if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                EventBus.getDefault().post(new StartBrotherEvent(BindingCardFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                break;
            case "account_center":
                this.dismiss();
                if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                if(Check.isEmpty(personJoinDay)){
                    personJoinDay = ACache.get(getContext()).getAsString("personJoinDay");
                }
                EventBus.getDefault().post(new StartBrotherEvent(AccountCenterFragment.newInstance(personMoney,personJoinDay)));
                break;
            case "deal_record":
                this.dismiss();
                EventBus.getDefault().post(new StartBrotherEvent(BetRecordFragment.newInstance("today",personMoney), SupportFragment.SINGLETASK));

                break;
            case "contract":
                this.dismiss();
                EventBus.getDefault().post(new StartBrotherEvent(ContractFragment.newInstance(personMoney,
                        ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL_QQ),
                        ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL_WECHAT))));
                break;
            case "gonggao":
                if(Check.isNull(noticeResultList)) {
                    presenter.postNoticeList("");
                }else{
                    EventBus.getDefault().post(new StartBrotherEvent(NoticeListFragment.newInstance(noticeResultList,"","")));
                }
                this.dismiss();
                break;
            case "logout":
                if(Check.isNull(presenter)){
                    presenter = Injections.inject(null, this);
                }
                presenter.logOut();
                break;
            case "agent":
                this.dismiss();
                EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(personMoney, Client.baseUrl()+"agent?appRefer=14&tip=app")));

                break;
        }
    }

   /* @OnClick({R.id.dialogHomeDeposite,R.id.dialogHomeWithDraw})
    public void onViewClicked(View view) {

        switch (view.getId()){
            case R.id.dialogHomeWithDraw:
                EventBus.getDefault().post(new MessageTopEvent(4,"HomeWithDraw"));
                this.dismiss();
                break;
            case R.id.dialogHomeDeposite:
                EventBus.getDefault().post(new MessageTopEvent(2,"HomeDeposite"));
                this.dismiss();
                break;
        }
    }*/

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().post(new ShowMainEvent(0));
    }
}
