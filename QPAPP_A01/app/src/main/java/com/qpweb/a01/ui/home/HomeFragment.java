package com.qpweb.a01.ui.home;

import android.content.Context;
import android.content.Intent;
import android.media.AudioManager;
import android.media.MediaPlayer;
import android.media.SoundPool;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.view.animation.LinearInterpolator;
import android.view.animation.RotateAnimation;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.jude.rollviewpager.RollPagerView;
import com.qpweb.a01.Injections;
import com.qpweb.a01.LaunchActivity;
import com.qpweb.a01.MainActivity;
import com.qpweb.a01.R;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.event.StartBrotherEvent;
import com.qpweb.a01.data.BannerResult;
import com.qpweb.a01.data.ChangeAccountEvent;
import com.qpweb.a01.data.IconEvent;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.LogoutResult;
import com.qpweb.a01.data.MusicBgEvent;
import com.qpweb.a01.data.NickNameEvent;
import com.qpweb.a01.data.NoticeResult;
import com.qpweb.a01.data.RefreshMoneyResult;
import com.qpweb.a01.data.SignTodayResult;
import com.qpweb.a01.data.WinNewsResult;
import com.qpweb.a01.http.Client;
import com.qpweb.a01.ui.home.agency.AgencyFragment;
import com.qpweb.a01.ui.home.bank.BindCardEvent;
import com.qpweb.a01.ui.home.bank.BindCardFragment;
import com.qpweb.a01.ui.home.bind.BindFragment;
import com.qpweb.a01.ui.home.deposit.DepositFragment;
import com.qpweb.a01.ui.home.fastlogout.LogoutFragment;
import com.qpweb.a01.ui.home.fenhong.DividendFragment;
import com.qpweb.a01.ui.home.hongbao.HBaoFragment;
import com.qpweb.a01.ui.home.icon.IconFragment;
import com.qpweb.a01.ui.home.imme.ImmeFragment;
import com.qpweb.a01.ui.home.notice.NoticeFragment;
import com.qpweb.a01.ui.home.set.SetFragment;
import com.qpweb.a01.ui.home.set.SetPwdEvent;
import com.qpweb.a01.ui.home.set.SetPwdFragment;
import com.qpweb.a01.ui.home.withdraw.WithDrawFragment;
import com.qpweb.a01.ui.loginhome.LoginHomeFragment;
import com.qpweb.a01.ui.loginhome.sign.RedFragment;
import com.qpweb.a01.ui.loginhome.sign.SignTodayFragment;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.DoubleClickHelper;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.ToastUtils;
import com.qpweb.a01.utils.Utils;
import com.qpweb.a01.widget.MarqueeTextView;
import com.qpweb.a01.widget.RollPagerViewManager;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Random;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;


public class HomeFragment extends SupportFragment implements HomeContract.View, View.OnTouchListener {

    @BindView(R.id.homeAccountLogo)
    ImageView homeAccountLogo;
    @BindView(R.id.homeAccountName)
    TextView homeAccountName;
    @BindView(R.id.homeAccountNumber)
    TextView homeAccountNumber;
    @BindView(R.id.homeUserMoney)
    TextView homeUserMoney;
    @BindView(R.id.homeHBao)
    ImageView homeHBao;
    @BindView(R.id.homeCheck)
    ImageView homeCheck;
    @BindView(R.id.homeRegent)
    ImageView homeRegent;

    @BindView(R.id.homeKy)
    ImageView homeKy;
    @BindView(R.id.homeVg)
    ImageView homeVg;
    @BindView(R.id.homeBy)
    ImageView homeBy;
    @BindView(R.id.homeHg)
    ImageView homeHg;
    @BindView(R.id.rViewData)
    RecyclerView rViewData;
    @BindView(R.id.homePop)
    ImageView homePop;
    @BindView(R.id.homeRollpageView)
    RollPagerView homeRollpageView;
    @BindView(R.id.homeWinNews)
    MarqueeTextView homeWinNews;
    @BindView(R.id.homeSetting)
    ImageView homeSetting;
    @BindView(R.id.homeDeposit)
    ImageView homeDeposit;
    @BindView(R.id.homeUserCenter)
    ImageView homeUserCenter;
    @BindView(R.id.homeActivity)
    ImageView homeActivity;
    @BindView(R.id.homeGirls)
    ImageView homeGirls;
    @BindView(R.id.homeGirlsFDC)
    ImageView homeGirlsFDC;
    @BindView(R.id.payFish)
    ImageView payFish;
    @BindView(R.id.homeService)
    ImageView homeService;
    @BindView(R.id.homeShare)
    ImageView homeShare;
    @BindView(R.id.homeShuaXin)
    ImageView homeShuaXin;
    @BindView(R.id.homeWithDraw)
    LinearLayout homeWithDraw;

    private RollPagerViewManager rollPagerViewManager;
    private ScheduledExecutorService executorService;
    List<String> stringList = new ArrayList<String>();
    HomeContract.Presenter presenter;
    private static List<HomePageIcon> homeGameList = new ArrayList<HomePageIcon>();
    //通过用户名是否为空来判断是否登录成功
    private String accountName ="";
    LoginResult loginResult;

    MediaPlayer mediaPlayer;
    SoundPool soundPool;
    HashMap<Integer, Integer> soundPoolMap;
    int currStreanId=0;
    Animation shakeX;
    //用户签到红包
    SignTodayResult signTodayResult;
    static {
      //  homeGameList.add(new HomePageIcon("体育投注",R.mipmap.home_hall_fishicon,0));
        homeGameList.add(new HomePageIcon("龙虎斗",R.mipmap.home_hall_dkicon,900));
        homeGameList.add(new HomePageIcon("炸金花",R.mipmap.home_hall_220,220));
        homeGameList.add(new HomePageIcon("极速炸金花",R.mipmap.home_hall_230,230));
        homeGameList.add(new HomePageIcon("抢庄牛牛",R.mipmap.home_hall_830,830));
        homeGameList.add(new HomePageIcon("看三张牛牛",R.mipmap.home_hall_890,890));
        homeGameList.add(new HomePageIcon("看四张抢庄牛牛",R.mipmap.home_hall_8150,8150));
        homeGameList.add(new HomePageIcon("百人牛牛",R.mipmap.home_hall_930,930));
        homeGameList.add(new HomePageIcon("通比牛牛",R.mipmap.home_hall_870,870));
        homeGameList.add(new HomePageIcon("百家乐",R.mipmap.home_hall_910,910));
        homeGameList.add(new HomePageIcon("斗地主",R.mipmap.home_hall_610,610));
        homeGameList.add(new HomePageIcon("红黑大战",R.mipmap.home_hall_950,950));
        homeGameList.add(new HomePageIcon("德州扑克",R.mipmap.home_hall_620,620));
        homeGameList.add(new HomePageIcon("十三水",R.mipmap.home_hall_630,630));
        homeGameList.add(new HomePageIcon("宝石消消乐",R.mipmap.home_hall_8180,8180));
        homeGameList.add(new HomePageIcon("二八杠",R.mipmap.home_hall_720,720));
        homeGameList.add(new HomePageIcon("二人麻将",R.mipmap.home_hall_740,740));
        homeGameList.add(new HomePageIcon("二十一点",R.mipmap.home_hall_600,600));
        homeGameList.add(new HomePageIcon("抢庄牌九",R.mipmap.home_hall_730,730));
        homeGameList.add(new HomePageIcon("三公",R.mipmap.home_hall_860,860));
        homeGameList.add(new HomePageIcon("森林舞会",R.mipmap.home_hall_920,920));
        homeGameList.add(new HomePageIcon("血战到底",R.mipmap.home_hall_8120,8120));
        homeGameList.add(new HomePageIcon("跑得快",R.mipmap.home_hall_999,999));
        homeGameList.add(new HomePageIcon("血流成河",R.mipmap.home_hall_1000,1000));
    }


    public static HomeFragment newInstance(LoginResult loginResult) {
        HomeFragment homeFragment = new HomeFragment();
        Injections.inject(homeFragment, null);
        Bundle args = new Bundle();
        args.putParcelable("LoginResult", loginResult);
        homeFragment.setArguments(args);
        return homeFragment;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_home, container, false);
        ButterKnife.bind(this, view);
        if (getArguments() != null) {
            loginResult = getArguments().getParcelable("LoginResult");
        }
        setEvents(savedInstanceState);
        return view;
    }


    class HomaPageGameAdapter extends BaseQuickAdapter<HomePageIcon, BaseViewHolder> {
        public HomaPageGameAdapter( int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(final BaseViewHolder holder, final HomePageIcon data) {
           /* holder.setOnTouchListener(R.id.iv_item_game_icon, new View.OnTouchListener() {
                @Override
                public boolean onTouch(View view, MotionEvent event) {
                    ImageView imageView = (ImageView)holder.getView(R.id.iv_item_game_icon);
                    switch (event.getAction()) {
                        case MotionEvent.ACTION_DOWN:
                            imageView.setScaleX(1.1f);
                            imageView.setScaleY(1.1f);
                            break;
                        case MotionEvent.ACTION_UP:
                            imageView.setScaleX((float) 1.0);
                            imageView.setScaleY((float) 1.0);
                            break;
                    }
                    return false;
                }
            });*/
            holder.setBackgroundRes(R.id.iv_item_game_icon,data.getIconId());
            holder.addOnClickListener(R.id.iv_item_game_icon);
        }
    }


    @Subscribe
    public void onEventMain(IconEvent iconEvent){
        onChangeIcon(iconEvent.getPostion());
        GameLog.log("首页头像的修改位置是" +iconEvent.getPostion());
    }

    @Subscribe
    public void onEventMain(ChangeAccountEvent changeAccountEvent){
        GameLog.log("用户切换账号了 " );
        Intent intent = new Intent(getContext(), LaunchActivity.class);
        startActivity(intent);
        getActivity().finish();
    }

    @Subscribe
    public void onEventMain(SetPwdEvent setPwdEvent){
        loginResult.setIsBindFundPassWord("1");
        GameLog.log("用户设置了 资金密码" );

    }
    @Subscribe
    public void onEventMain(BindCardEvent bindCardEvent){
        loginResult.setIsBindCard("1");
        GameLog.log("用户设置了 银行卡" );

    }

    private void onChangeIcon(String postion){
        switch (postion){
            case "1":
                homeAccountLogo.setBackground(getResources().getDrawable(R.mipmap.icon_v1));
                break;
            case "2":
                homeAccountLogo.setBackground(getResources().getDrawable(R.mipmap.icon_v2));
                break;
            case "3":
                homeAccountLogo.setBackground(getResources().getDrawable(R.mipmap.icon_v3));
                break;
            case "4":
                homeAccountLogo.setBackground(getResources().getDrawable(R.mipmap.icon_v4));
                break;
            case "5":
                homeAccountLogo.setBackground(getResources().getDrawable(R.mipmap.icon_v5));
                break;
            case "6":
                homeAccountLogo.setBackground(getResources().getDrawable(R.mipmap.icon_v6));
                break;
            case "7":
                homeAccountLogo.setBackground(getResources().getDrawable(R.mipmap.icon_v7));
                break;
        }
    }

    private void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        if(Check.isNull(loginResult)){
            loginResult = JSON.parseObject(ACache.get(getContext()).getAsString("loginResult"), LoginResult.class);
        }
        if(Check.isEmpty(loginResult.getPhone())){
            //添加绑定手机号的逻辑  未绑定就一直显示
            ImmeFragment.newInstance().show(getFragmentManager());
        }
        initSounds();
        playSoud(new Random().nextInt(4),-1);
        String postion = loginResult.getAvatarId();
        GameLog.log("首页用户的头像位置是 "+postion);
        if(Check.isEmpty(postion)){
            postion = "5";
        }
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT_ICON,postion);
        onChangeIcon(postion);
        String nickName = ACache.get(getContext()).getAsString("NickName");
        if(!Check.isNull(loginResult)){
            ACache.get(getContext()).put("NickName",loginResult.getNickName()+"");
            ACache.get(getContext()).put("OnlineServer",loginResult.getOnlineServer());
            ACache.get(getContext()).put("Money",loginResult.getMoney());
            ACache.get(getContext()).put("ID",loginResult.getID());
            ACache.get(getContext()).put("PersonalizedSignature",loginResult.getPersonalizedSignature()+"");
            homeAccountNumber.setText(loginResult.getID());
            homeAccountName.setText(loginResult.getNickName());
            homeUserMoney.setText(loginResult.getMoney());
        }else if(!Check.isNull(nickName)){
            homeAccountName.setText(nickName);
            String userMoney = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT_MONEY);
            homeUserMoney.setText(userMoney);
        }
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),2, OrientationHelper.HORIZONTAL,false);
        rViewData.setLayoutManager(gridLayoutManager);
        rViewData.setHasFixedSize(true);
        rViewData.setNestedScrollingEnabled(false);

        RotateAnimation animation= new RotateAnimation(0,360f, Animation.RELATIVE_TO_SELF,0.5f,Animation.RELATIVE_TO_SELF,0.5f);
        animation.setDuration(2000);
        animation.setFillAfter(true);
        animation.setInterpolator(new LinearInterpolator());
        animation.setRepeatMode(Animation.RESTART);
        animation.setRepeatCount(Animation.INFINITE);
        Animation shake = AnimationUtils.loadAnimation(getContext(), R.anim.shake_y);
        shakeX = AnimationUtils.loadAnimation(getContext(), R.anim.rotate_clockwise);
        shake.setRepeatCount(Animation.INFINITE);
        shake.setRepeatMode(Animation.RESTART);
        homeGirls.startAnimation(shake);
        if (homeShare != null) {
            homeShare.startAnimation(animation);
        }
        List<String> stringList = new ArrayList<String>();
        stringList.add("欢迎来到姚记棋牌！");
        stringList.add("欢迎来到姚记棋牌！");
        homeWinNews.setContentList(stringList);
        HomaPageGameAdapter homaPageGameAdapter = new HomaPageGameAdapter(R.layout.item_game_hall,homeGameList);
        homaPageGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                if(homeGameList.get(position).getId()==999||homeGameList.get(position).getId()==1000){
                    showMessage("敬请期待！");
                    return;
                }
                goToPayGame(homeGameList.get(position).getId());
            }
        });
//        View view = LayoutInflater.from(getContext()).inflate(R.layout.item_game_hall_header, null);
//        view.setLayoutParams(new ViewGroup.LayoutParams(200,400));
//        homaPageGameAdapter.addHeaderView(view);
        rViewData.setAdapter(homaPageGameAdapter);

        homeKy.setOnTouchListener(this);
        homeVg.setOnTouchListener(this);
        homeBy.setOnTouchListener(this);
        homeHg.setOnTouchListener(this);
        presenters();
        if(Check.isNull(presenter)){
            presenter = Injections.inject(this, null);
        }
        presenter.postBanner("");
        //presenter.postNotice("","2");
        presenter.postWinNews("",System.currentTimeMillis()+"");
        presenter.postRefreshMoney("");
        if(loginResult.getHaveLyAccount().equals("0")){
            GameLog.log("创建了乐游账号");
            presenter.postNeedLyId("");
        }
        presenter.postSignToday("","","");
        executorService = Executors.newScheduledThreadPool(1);
        onDeplayView();
        /*executorService.scheduleAtFixedRate(new Runnable() {
            @Override
            public void run() {
                onDeplayView();
                //presenter.postWinNews("",System.currentTimeMillis()+"");
            }
        }, 0, 5000, TimeUnit.MILLISECONDS);*/
    }

    private void onDeplayView(){
        //homeGirlsFDC.setVisibility(View.GONE);
        GameLog.log("onDeplayView是否展示了数据呀");
        homeGirlsFDC.setVisibility(View.GONE);
        homeGirls.postDelayed(new Runnable() {
            @Override
            public void run() {
                GameLog.log(" VISIBLE 是否展示了数据呀");
                homeGirlsFDC.setVisibility(View.VISIBLE);
                homeGirls.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log(" GONE是否展示了数据呀");
                        onDeplayView();
                    }
                },5000);
            }
        },15000);
    }

    /**
     * start other BrotherFragment
     */
    @Subscribe
    public void startBrother(StartBrotherEvent event) {
        start(event.targetFragment, event.launchmode);
    }

    private void goToPayGame(int gameId){
        String uid = loginResult.getOid();
        String url = Client.baseUrl()+"api/lyqp/index.php?uid="+uid+"&gameId="+gameId+"&appRefer="+QPConstant.PRODUCT_PLATFORM;
        Intent intent = new Intent(getContext(), MainActivity.class);
        intent.putExtra("app_url",url);
        startActivity(intent);
    }

    @OnClick({R.id.homeGeneralize,R.id.homeAccountLogo,R.id.payFish,R.id.homeAccountName, R.id.homeHBao, R.id.homeCheck, R.id.homeRegent, R.id.homePop,
            R.id.homeDeposit,R.id.homeSetting, R.id.homeUserCenter, R.id.homeActivity, R.id.homeService, R.id.homeWithDraw,
            R.id.homeHg,R.id.homeVg,R.id.homeKy,R.id.homeBy,R.id.homePlus,R.id.homeShuaXin})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.homeGeneralize:
                ACache.get(getContext()).put("promotion_link",loginResult.getPromotion_link());
                ACache.get(getContext()).put("promotion_qrcode_link",loginResult.getPromotion_qrcode_link());
                AgencyFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.homeAccountLogo:
                IconFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.payFish:
                goToPayGame(510);
                /*String uid = loginResult.getOid();
                String url = Client.baseUrl()+"api/lyqp/index.php?uid="+uid+"&gameId=230";
                Intent intent = new Intent(getContext(), MainActivity.class);
                intent.putExtra("app_url",url);
                startActivity(intent);*/
                //presenter.postPayGame("",uid,"230");
                break;
            case R.id.homeAccountName:
                if(Check.isEmpty(accountName)){
                    EventBus.getDefault().post(new StartBrotherEvent(LoginHomeFragment.newInstance(), SupportFragment.SINGLETASK));
                }else{
                    LogoutFragment.newInstance(loginResult).show(getFragmentManager());
                }
                break;
            case R.id.homeHBao:
                HBaoFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.homeCheck://投资分红
                DividendFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.homeRegent:
                //签到红包

                break;
            case R.id.homePop:
                SignTodayFragment.newInstance(signTodayResult).show(getFragmentManager());
                break;
            case R.id.homeSetting:
                SetFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.homeDeposit:
            case R.id.homePlus:
                DepositFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.homeShuaXin:
                homeShuaXin.startAnimation(shakeX);
                presenter.postRefreshMoney("");
                break;
            case R.id.homeUserCenter:
                String url;
                if(!Check.isNull(loginResult)){
                    url= loginResult.getOnlineServer();
                }else{
                    url= ACache.get(getContext()).getAsString("OnlineServer");
                }
                Intent intent = new Intent(getContext(), MainActivity.class);
                intent.putExtra("app_url",url);
                startActivity(intent);
                break;
            case R.id.homeActivity:
                DoubleClickHelper.getNewInstance().disabledView(homeActivity);
                presenter.postNotice("","2");
                break;
            case R.id.homeService:
                //StrongBoxFragment.newInstance().show(getFragmentManager());
                //先绑定手机号之后才能绑卡
                /*if(Check.isEmpty(loginResult.getPhone())){
                    //添加绑定手机号的逻辑  未绑定就一直显示
                    BindFragment.newInstance().show(getFragmentManager());
                    return;
                }*/
                if(Check.isEmpty(loginResult.getPhone())){
                    showMessage("亲，绑定手机号码有红包领取哟~");
                    BindFragment.newInstance().show(getFragmentManager());
                }else {
                    BindCardFragment.newInstance().show(getFragmentManager());
                }
                break;
            case R.id.homeWithDraw:
                //取款先判断是否绑定过手机号码
                if(Check.isEmpty(loginResult.getPhone())){
                    showMessage("亲，绑定手机号码有红包领取哟~");
                    BindFragment.newInstance().show(getFragmentManager());
                }else if(loginResult.getIsBindCard().equals("0")){
                    showMessage("亲，请先设置绑定银行卡~");
                    BindCardFragment.newInstance().show(getFragmentManager());
                }else if(loginResult.getIsBindFundPassWord().equals("0")){
                    showMessage("亲，请先设置资金密码~");
                    SetPwdFragment.newInstance().show(getFragmentManager());
                }else{
                    WithDrawFragment.newInstance().show(getFragmentManager());
                }
                //ExChangeFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.homeHg:
                showMessage("去皇冠了");
                break;
            case R.id.homeVg:
                showMessage("去VG棋牌");
                break;
            case R.id.homeKy:
                showMessage("去开元棋牌了");
                break;
            case R.id.homeBy:
                showMessage("去捕鱼了");
                break;
        }
    }

    @Override
    public void postBannerResult(BannerResult bannerResult) {
        GameLog.log("获取banner的拖 ");
        rollPagerViewManager  = new RollPagerViewManager(homeRollpageView, bannerResult.getData());
        //rollPagerViewManager.testImagesLocal(null);
        rollPagerViewManager.testImagesNet(null,null);

    }

    @Override
    public void postNoticeResult(List<NoticeResult> noticeResult) {
        GameLog.log("获取公告信息 "+noticeResult.get(0).getTitle());
        ACache.get(getContext()).put("QP_Notice", JSON.toJSONString(noticeResult.get(0)));
        NoticeFragment.newInstance().show(getFragmentManager());
    }

    @Override
    public void postWinNewsResult(WinNewsResult winNewsResult) {
        stringList.clear();
        stringList.add(winNewsResult.getData());
        stringList.add(winNewsResult.getData());
        homeWinNews.setVisibility(View.VISIBLE);
        homeWinNews.setContentList(stringList);
        homeWinNews.postDelayed(new Runnable() {
            @Override
            public void run() {
                //homeWinNews.setVisibility(View.GONE);
            }
        },5000);

    }

    @Override
    public void postLogoutResult(String logoutResult) {
        showMessage(logoutResult);
        accountName = "";
        homeAccountLogo.setImageDrawable(getResources().getDrawable(R.mipmap.home_gold));
        homeAccountName.setText("请先登录");
        homeUserMoney.setText("0");
    }

    @Override
    public void postRefreshMoneyResult(RefreshMoneyResult refreshMoneyResult) {
        homeShuaXin.clearAnimation();
        GameLog.log("刷新用户的余额");
        ACache.get(getContext()).put("Money",refreshMoneyResult.getLy_balance());
        homeUserMoney.setText(refreshMoneyResult.getLy_balance());
    }

    @Override
    public void postSignTodayResult(SignTodayResult signTodayResult) {
        this.signTodayResult  = signTodayResult;
        String days_1,days_2,days_3,days_4,days_5,days_6,days_7,currentDays,currentGetRed="0";
        days_1 = signTodayResult.getSign_days_1();
        days_2 = signTodayResult.getSign_days_2();
        days_3 = signTodayResult.getSign_days_3();
        days_4 = signTodayResult.getSign_days_4();
        days_5 = signTodayResult.getSign_days_5();
        days_6 = signTodayResult.getSign_days_6();
        days_7 = signTodayResult.getSign_days_0();
        currentDays = signTodayResult.getCurrent_week_day();
        switch (currentDays){
            case "1":
                currentGetRed = days_1;
                break;
            case "2":
                currentGetRed = days_2;
                break;
            case "3":
                currentGetRed = days_3;
                break;
            case "4":
                currentGetRed = days_4;
                break;
            case "5":
                currentGetRed = days_5;
                break;
            case "6":
                currentGetRed = days_6;
                break;
            case "0":
                currentGetRed = days_7;
                break;
        }
        if(currentGetRed.equals("0")){
            SignTodayFragment.newInstance(signTodayResult).show(getFragmentManager());
        }
    }

    @Override
    public void showMessage(String message) {
        ToastUtils.showLongToast(message);
    }

    @Override
    public void setPresenter(HomeContract.Presenter presenter) {
        this.presenter = presenter;
    }

    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
        if (null != executorService) {
            GameLog.log("关闭计数任务跑马灯");
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        soundPool.stop(currStreanId);
        mediaPlayer.stop();
        mediaPlayer.release();
        mediaPlayer = null;
    }

    @Subscribe
    public void onEventMain(NickNameEvent NickNameEvent) {
        homeAccountName.setText(NickNameEvent.getNickName());
    }

    @Subscribe
    public void onEventMain(RefreshMoneyEvent refreshMoneyEvent) {
        if(!Check.isEmpty(refreshMoneyEvent.getBindPhone())){
            loginResult.setPhone(refreshMoneyEvent.getBindPhone());
        }
        presenter.postRefreshMoney("");
        presenter.postSignToday("","","");
    }


    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("================首页获取到消息了================");
        this.loginResult = loginResult;
        accountName = loginResult.getUserName();
        homeAccountLogo.setImageDrawable(getResources().getDrawable(R.mipmap.home_login_account));
        homeAccountName.setText(accountName);
        homeUserMoney.setText(loginResult.getMoney());
    }

    @Subscribe
    public void onEventMain(LogoutResult loginResult) {
        GameLog.log("================用户退出了================");
        accountName = "";
        this.loginResult = null;
        homeAccountLogo.setImageDrawable(getResources().getDrawable(R.mipmap.home_login_account));
        homeAccountName.setText("请先登录");
        homeUserMoney.setText("0");
    }

    @Subscribe
    public void onEventMain(MusicBgEvent musicBgEvent) {
        GameLog.log("用户设置了背景音乐 "+musicBgEvent.getPostion());
        if(musicBgEvent.getPostion()==0){
            //soundPool.stop(currStreanId);
            mediaPlayer.pause();
        }else{
            playSoud(musicBgEvent.getPostion(),1);
        }
    }

    private void initSounds() {
        soundPool = new SoundPool(4,AudioManager.STREAM_MUSIC,100);
        soundPoolMap = new HashMap<Integer, Integer>();
        soundPoolMap.put(0, soundPool.load(Utils.getContext(), R.raw.bgm1, 1));
        soundPoolMap.put(1, soundPool.load(Utils.getContext(), R.raw.bgm1, 1));
        soundPoolMap.put(2, soundPool.load(Utils.getContext(), R.raw.bgm2, 1));
        soundPoolMap.put(3, soundPool.load(Utils.getContext(), R.raw.bgm3, 1));
    }

    //播放声音的方法
    private void playSoud(int sound, int loop) {
        switch (sound){
            case 0:
            case 1:
                mediaPlayer = MediaPlayer.create(Utils.getContext(), R.raw.bgm1);
                break;
            case 2:
                mediaPlayer = MediaPlayer.create(Utils.getContext(), R.raw.bgm2);
                break;
            case 3:
                mediaPlayer = MediaPlayer.create(Utils.getContext(), R.raw.bgm3);
                break;
        }
        AudioManager am = (AudioManager) Utils.getContext().getSystemService(Context.AUDIO_SERVICE);//获取当前音量
        float streamVolumeCurrent = am.getStreamVolume(AudioManager.STREAM_MUSIC);
        float streamVolumeMax = am.getStreamMaxVolume(AudioManager.STREAM_MUSIC);//计算获得系统最大音量
        float volume = streamVolumeCurrent/streamVolumeMax;//计算得到的播放音量
        //currStreanId = soundPool.play(soundPoolMap.get(sound), volume, volume, 1, loop, 1.0f);
        GameLog.log("播放的音乐是 "+sound);
        mediaPlayer.start();
        mediaPlayer.setVolume(0.2f, 0.2f);
        mediaPlayer.setLooping(true);
    }

    @Override
    public boolean onTouch(View view, MotionEvent event) {
        switch (event.getAction()) {
            case MotionEvent.ACTION_DOWN:
                if (view.getId() == R.id.homeBy) {
                        homeBy.setScaleX(1.1f);
                        homeBy.setScaleY(1.1f);
                        break;
                }else if(view.getId() == R.id.homeHg) {
                    homeHg.setScaleX(1.1f);
                    homeHg.setScaleY(1.1f);
                }else if(view.getId() == R.id.homeVg) {
                    homeVg.setScaleX(1.1f);
                    homeVg.setScaleY(1.1f);
                }else if(view.getId() == R.id.homeKy) {
                    homeKy.setScaleX(1.1f);
                    homeKy.setScaleY(1.1f);
                }
                break;
            case MotionEvent.ACTION_UP:
                if (view.getId() == R.id.homeBy) {
                    homeBy.setScaleX((float) 0.95);
                    homeBy.setScaleY((float) 0.95);
                }else if(view.getId() == R.id.homeHg) {
                    homeHg.setScaleX((float) 0.95);
                    homeHg.setScaleY((float) 0.95);
                }else if(view.getId() == R.id.homeVg) {
                    homeVg.setScaleX((float) 0.95);
                    homeVg.setScaleY((float) 0.95);
                }else if(view.getId() == R.id.homeKy) {
                    homeKy.setScaleX((float) 0.95);
                    homeKy.setScaleY((float) 0.95);
                }
                break;
        }
        return false;
    }
}
