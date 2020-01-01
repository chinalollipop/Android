package com.nhg.xhg.homepage.cplist;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.nhg.xhg.CPInjections;
import com.nhg.xhg.HGApplication;
import com.nhg.xhg.R;
import com.nhg.xhg.base.BaseActivity2;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.common.adapters.AutoSizeRVAdapter;
import com.nhg.xhg.common.util.ACache;
import com.nhg.xhg.common.util.HGConstant;
import com.nhg.xhg.common.widgets.CPBottomBar;
import com.nhg.xhg.common.widgets.CPBottomBarTab;
import com.nhg.xhg.common.widgets.MarqueeTextView;
import com.nhg.xhg.data.CPNoteResult;
import com.nhg.xhg.data.PersonBalanceResult;
import com.nhg.xhg.homepage.HomePageIcon;
import com.nhg.xhg.homepage.cplist.bet.betrecords.CPBetRecordsFragment;
import com.nhg.xhg.homepage.cplist.me.CPMeFragment;
import com.nhg.xhg.homepage.cplist.role.CPServiceActivity;
import com.nhg.xhg.login.fastlogin.LoginFragment;
import com.nhg.common.util.Check;
import com.nhg.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.jessyan.retrofiturlmanager.RetrofitUrlManager;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class CPListFragment extends BaseActivity2 implements CPListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpBottomBar)
    CPBottomBar cpBottomBar;
    @BindView(R.id.cpListTitle)
    RelativeLayout cpListTitle;
    @BindView(R.id.cpListImageView)
    ImageView cpListImageView;
    @BindView(R.id.cpPageBulletin)
    MarqueeTextView cpPageBulletin;/*
    @BindView(R.id.cpGameList)
    RecyclerView cpList;
    private static List<HomePageIcon> cpGameList = new ArrayList<HomePageIcon>();*/
    @BindView(R.id.cpTv1)
    TextView cpTv1;
    @BindView(R.id.cpTv2)
    TextView cpTv2;
    @BindView(R.id.cpTv3)
    TextView cpTv3;
    @BindView(R.id.cpTv4)
    TextView cpTv4;
    @BindView(R.id.cpTv5)
    TextView cpTv5;
    @BindView(R.id.cpTv6)
    TextView cpTv6;
    @BindView(R.id.cpTv7)
    TextView cpTv7;
    @BindView(R.id.cpTv8)
    TextView cpTv8;
    @BindView(R.id.cpTv9)
    TextView cpTv9;
    @BindView(R.id.cpTv10)
    TextView cpTv10;
    @BindView(R.id.cpTv11)
    TextView cpTv11;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    CPListContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";

    /*static {
        cpGameList.add(new HomePageIcon("北京赛车", R.mipmap.cp_bjsc));
        cpGameList.add(new HomePageIcon("极速飞艇", R.mipmap.cp_jsft));
        cpGameList.add(new HomePageIcon("重庆时时彩", R.mipmap.cp_cqssc));
        cpGameList.add(new HomePageIcon("极速赛车", R.mipmap.cp_jsfc));
        cpGameList.add(new HomePageIcon("六合彩", R.mipmap.cp_lhc));
        cpGameList.add(new HomePageIcon("分分彩", R.mipmap.cp_ffc));
        cpGameList.add(new HomePageIcon("PC蛋蛋", R.mipmap.cp_pcdd));
        cpGameList.add(new HomePageIcon("快乐十分", R.mipmap.cp_klsfc));
        cpGameList.add(new HomePageIcon("幸运农场", R.mipmap.cp_xync));
        cpGameList.add(new HomePageIcon("江苏快3", R.mipmap.cp_js));
        cpGameList.add(new HomePageIcon("更多", R.mipmap.cp_more));
    }*/
    /*public static CPListFragment newInstance(List<String> param1) {
        CPListFragment fragment = new CPListFragment();
        Bundle args = new Bundle();
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        CPInjections.inject(fragment,null);
        fragment.setArguments(args);
        return fragment;
    }*/

    @Override
    public void onCreate(Bundle savedInstanceState) {
        try {
            CPInjections.inject(this, null);
        }catch (Exception e){
            e.printStackTrace();
        }
        super.onCreate(savedInstanceState);
        /*if (getArguments() != null) {
            userName = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);// 用以判断是电子还是真人
        }*/
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_list;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        //StatusBarUtil.setColor(this, getResources().getColor(R.color.cp_status_bar));
//            StatusBarUtil.setTranslucentForImageView(this,cpListTitle);
       // RetrofitUrlManager.getInstance().putDomain("CpUrl", "http://mc.hg01455.com/");
        /*String isLoginAlread = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_COOKIE_AVIABLE);
        if("false".equals(isLoginAlread)){//如果没有登录过 ，就需要登录
            presenter.postCPLogin(ACache.get(getContext()).getAsString(HGConstant.USERNAME_CP_INFORM));
        }else{
        }*/
        RetrofitUrlManager.getInstance().setGlobalDomain(ACache.get(getContext()).getAsString("homeCPUrl").replace("m.","mc."));
        String[] cptoken = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_COOKIE).split("; ");
        String token="";
        for(int i=0;i<cptoken.length;++i){
            if(cptoken[i].contains("token=")){
                GameLog.log("包含token的地方是 "+cptoken[i]);
                token = cptoken[i].replace("token=","");
                ACache.get(HGApplication.instance().getApplicationContext()).put(HGConstant.APP_CP_X_SESSION_TOKEN,token);
                GameLog.log("彩票的token "+token);
            }
        }
        if(Check.isEmpty(token)){
            showMessage("请重新登录！");
            EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
        }
        presenter.postCPNote(token);
        /*cpBottomBar.postDelayed(new Runnable() {
            @Override
            public void run() {
                presenter.postCPInit();
            }
        },10000);*/

//        RetrofitUrlManager.getInstance().putDomain("CpUrl", "http://mc.hg50080.com/#/home/");
        cpBottomBar
                .addItem(new CPBottomBarTab(getContext(), R.drawable.cp_tab_home, getString(R.string.str_title_homepage)))
                .addItem(new CPBottomBarTab(getContext(), R.drawable.cp_tab_record, getString(R.string.cp_title_record)))
                .addItem(new CPBottomBarTab(getContext(), R.drawable.cp_tab_me, getString(R.string.str_title_person)))
                .addItem(new CPBottomBarTab(getContext(), R.drawable.cp_tab_service, getString(R.string.str_title_withdraw)));
        //cpBottomBar.getItem(1).setUnreadCount(9);
        cpBottomBar.setOnTabSelectedListener(new CPBottomBar.OnTabSelectedListener() {
            @Override
            public void onTabSelected(int position, int prePosition) {
                switch (position) {
                    case 0:
                        GameLog.log("当前选择的事");
                        String homeUrl = ACache.get(getContext()).getAsString("homeTYUrl");
                        GameLog.log("onTabSelected "+homeUrl);
                        if(!Check.isNull(homeUrl)) {
                            RetrofitUrlManager.getInstance().setGlobalDomain(homeUrl);
                        }
                        finish();
                        break;
                    case 1:
                        Intent intent  = new Intent(getContext(),CPBetRecordsFragment.class);
                        intent.putExtra("gameId","51");
                        intent.putExtra("gameName","北京赛车");
                        startActivity(intent);
                        break;
                    case 2:
                        Intent intent2  = new Intent(getContext(),CPMeFragment.class);
                        intent2.putExtra("gameId","51");
                        intent2.putExtra("gameName","北京赛车");
                        startActivity(intent2);
                       // EventBus.getDefault().post(new StartBrotherEvent(CPMeFragment.newInstance(Arrays.asList("", "", "", "")), SupportFragment.SINGLETASK));
                        break;
                    case 3:
                        Intent intent6 = new Intent(getContext(),CPServiceActivity.class);
                        intent6.putExtra("gameId","51");
                        intent6.putExtra("gameName",titleName);
                        startActivity(intent6);
                        break;
                }
            }

            @Override
            public void onTabUnselected(int position) {
                GameLog.log("++++++++++++++++++++++++++ " + position);
            }

            @Override
            public void onTabReselected(int position) {
                if (position == 0) {
                    String homeUrl = ACache.get(getContext()).getAsString("homeTYUrl");
                    GameLog.log("onTabReselected "+homeUrl);
                    if(!Check.isNull(homeUrl)) {
                        RetrofitUrlManager.getInstance().setGlobalDomain(homeUrl);
                    }
                    finish();
                } else if (position == 2) {
                    Intent intent2  = new Intent(getContext(),CPMeFragment.class);
                    intent2.putExtra("gameId","51");
                    intent2.putExtra("gameName","北京赛车");
                    startActivity(intent2);
                    //EventBus.getDefault().post(new StartBrotherEvent(CPMeFragment.newInstance(Arrays.asList("", "", "", "")), SupportFragment.SINGLETASK));
                }else if(position == 1){
                    Intent intent  = new Intent(getContext(),CPBetRecordsFragment.class);
                    intent.putExtra("gameId","51");
                    intent.putExtra("gameName","北京赛车");
                    startActivity(intent);
                }else{
                    Intent intent6 = new Intent(getContext(),CPServiceActivity.class);
                    intent6.putExtra("gameId","51");
                    intent6.putExtra("gameName",titleName);
                    startActivity(intent6);
                }
                GameLog.log("----------------------------- " + position);
            }
        });
        CPNoteResult noticeResult = JSON.parseObject(ACache.get(getContext()).getAsString(HGConstant.USERNAME_CP_HOME_NOTICE), CPNoteResult.class);
        if (!Check.isNull(noticeResult)&&!Check.isNull(noticeResult.getData())) {
            List<String> stringList = new ArrayList<String>();
            int size = noticeResult.getData().size();
            for (int i = 0; i < size; ++i) {
                stringList.add(noticeResult.getData().get(i).getComment());
            }
            GameLog.log("本地的公告 "+stringList);
            if(stringList.size()==1){
                stringList.add(noticeResult.getData().get(0).getComment());
            }
            cpPageBulletin.setContentList(stringList);
        }
        /*cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.VERTICAL,5,getContext().getColor(R.color.textview_normal),8));
        cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.HORIZONTAL,5,getContext().getColor(R.color.textview_normal),8));*/
       /* GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),3, OrientationHelper.VERTICAL,false);
        cpList.setLayoutManager(gridLayoutManager);
        cpList.setHasFixedSize(true);
        cpList.setNestedScrollingEnabled(false);
        cpList.addItemDecoration(new GridRvItemDecoration2(getContext()));
        cpList.setAdapter(new LotteryPageGameAdapter(getContext(),R.layout.item_cp_hall,cpGameList));*/
    }

    @OnClick({R.id.cpTv1, R.id.cpTv2, R.id.cpTv3, R.id.cpTv4, R.id.cpTv5, R.id.cpTv6, R.id.cpTv7, R.id.cpTv8, R.id.cpTv9, R.id.cpTv10, R.id.cpTv11})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.cpTv1:
                /** 北京赛车    game_code 51
                 *  重庆时时彩    game_code 2
                 *  极速赛车    game_code 189
                 *  极速飞艇    game_code 222
                 *  分分彩    game_code 207
                 *  三分彩    game_code 407
                 *  五分彩    game_code 507
                 *  腾讯二分彩    game_code 607
                 *  PC蛋蛋    game_code 304
                 *  江苏快3    game_code 159
                 *  幸运农场    game_code 47
                 *  快乐十分    game_code 3
                 *  香港六合彩  game_code 69
                 *  极速快三    game_code 384
                 *
                 */
//                startActivity(new Intent(getContext(),CPOrderFragment.class));
                Intent intent  = new Intent(getContext(),CPOrderFragment.class);
                intent.putExtra("gameId","51");
                intent.putExtra("gameName","北京赛车");
                startActivity(intent);
                //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("51", "北京赛车", "111"))));
                break;
            case R.id.cpTv2:
                Intent intent2  = new Intent(getContext(),CPOrderFragment.class);
                intent2.putExtra("gameId","222");
                intent2.putExtra("gameName","极速飞艇");
                startActivity(intent2);
                //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("222", "极速飞艇", "222"))));
                break;
            case R.id.cpTv3:
                Intent intent3  = new Intent(getContext(),CPOrderFragment.class);
                intent3.putExtra("gameId","2");
                intent3.putExtra("gameName","欢乐生肖");
                startActivity(intent3);
                //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("2", "重庆时时彩", "333"))));
                break;
            case R.id.cpTv4:
                Intent intent4  = new Intent(getContext(),CPOrderFragment.class);
                intent4.putExtra("gameId","189");
                intent4.putExtra("gameName","极速赛车");
                startActivity(intent4);
                //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("189", "极速赛车", "444"))));
                break;
            case R.id.cpTv5:
                Intent intent5  = new Intent(getContext(),CPOrderFragment.class);
                intent5.putExtra("gameId","69");
                intent5.putExtra("gameName","香港六合彩");
                startActivity(intent5);
                //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("69", "香港六合彩", "555"))));
                break;
            case R.id.cpTv6:
                Intent intent6  = new Intent(getContext(),CPOrderFragment.class);
                intent6.putExtra("gameId","207");
                intent6.putExtra("gameName","分分彩");
                startActivity(intent6);
                //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("207", "分分彩", "666"))));
                break;
            case R.id.cpTv7:
                Intent intent7  = new Intent(getContext(),CPOrderFragment.class);
                intent7.putExtra("gameId","304");
                intent7.putExtra("gameName","PC蛋蛋");
                startActivity(intent7);
                //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("304", "PC蛋蛋", "777"))));
                break;
            case R.id.cpTv8:
                Intent intent8  = new Intent(getContext(),CPOrderFragment.class);
                intent8.putExtra("gameId","3");
                intent8.putExtra("gameName","快乐十分");
                startActivity(intent8);
                //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("3", "快乐十分", "888"))));
                break;
            case R.id.cpTv9:
                Intent intent9  = new Intent(getContext(),CPOrderFragment.class);
                intent9.putExtra("gameId","47");
                intent9.putExtra("gameName","幸运农场");
                startActivity(intent9);
                //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("47", "幸运农场", "999"))));
                break;
            case R.id.cpTv10:
                Intent intent10  = new Intent(getContext(),CPOrderFragment.class);
                intent10.putExtra("gameId","159");
                intent10.putExtra("gameName","江苏快3");
                startActivity(intent10);
                //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("159", "江苏快3", "101010"))));
                break;
            case R.id.cpTv11:
                startActivity(new Intent(getContext(),CPHallFragment.class));
                break;
        }
    }

    @Override
    public void postCPNoteResult(CPNoteResult cpNoteResult) {

        if (!Check.isNull(cpNoteResult)) {
            ACache.get(getContext()).put(HGConstant.USERNAME_CP_HOME_NOTICE,JSON.toJSONString(cpNoteResult));
            List<String> stringList = new ArrayList<String>();
            int size = cpNoteResult.getData().size();
            for (int i = 0; i < size; ++i) {
                stringList.add(cpNoteResult.getData().get(i).getComment());
            }
            if(stringList.size()==1){
                stringList.add(cpNoteResult.getData().get(0).getComment());
            }
            GameLog.log("服务器的公告");
            cpPageBulletin.setContentList(stringList);
        }
    }

    class LotteryPageGameAdapter extends AutoSizeRVAdapter<HomePageIcon> {
        private Context context;

        public LotteryPageGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, HomePageIcon data, final int position) {
            holder.setText(R.id.tv_item_game_name, data.getIconName());
            holder.setBackgroundRes(R.id.iv_item_game_icon, data.getIconId());
            holder.setOnClickListener(R.id.ll_home_main_show, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //onHomeGameItemClick(position);
                    startActivity(new Intent(getContext(),CPOrderFragment.class));
                    //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("111", "222", "333"))));
                }
            });
        }
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(CPListContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Subscribe
    public void onPersonBalanceResult(PersonBalanceResult personBalanceResult) {
        GameLog.log("通过发送消息得的的数据" + personBalanceResult.getBalance_ag());
        agMoney = personBalanceResult.getBalance_ag();
        hgMoney = personBalanceResult.getBalance_hg();
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        String homeUrl = ACache.get(getContext()).getAsString("homeTYUrl");
        GameLog.log("彩票小时之后的 体育的域名"+homeUrl);
        if(!Check.isNull(homeUrl)) {
            RetrofitUrlManager.getInstance().setGlobalDomain(homeUrl);
        }
        EventBus.getDefault().unregister(this);
    }
}
