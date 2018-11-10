package com.hgapp.a6668.homepage.cplist;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.DisplayMetrics;
import android.view.Gravity;
import android.view.View;
import android.view.WindowManager;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;

import com.brioal.swipemenu.view.SwipeMenu;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.util.ArrayListHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.TimeHelper;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.data.AGGameLoginResult;
import com.hgapp.a6668.data.AGLiveResult;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.homepage.HomePageIcon;
import com.hgapp.a6668.homepage.aglist.AGListContract;
import com.hgapp.a6668.homepage.cplist.events.LeftEvents;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;
import butterknife.Unbinder;

public class CPOrderFragment extends HGBaseFragment implements AGListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.llCPOrderAll)
    LinearLayout llCPOrderAll;
    @BindView(R.id.cpOrderLotteryOpen1)
    RecyclerView cpOrderLotteryOpen1;
    @BindView(R.id.cpOrderLotteryOpen2)
    RecyclerView cpOrderLotteryOpen2;
    @BindView(R.id.cpOrderGameList)
    RecyclerView cpList;
    @BindView(R.id.cpOrderListLeft)
    RecyclerView cpOrderListLeft;
    @BindView(R.id.cpOrderListRight)
    RecyclerView cpOrderListRight;
    @BindView(R.id.cpOrderUserMoney)
    TextView cpOrderUserMoney;
    @BindView(R.id.cpOrderTitle)
    TextView cpOrderTitle;
    @BindView(R.id.rightCloseLotteryTime)
    TextView rightCloseLotteryTime;
    @BindView(R.id.rightOpenLotteryTime)
    TextView rightOpenLotteryTime;

    private static List<HomePageIcon> cpGameList = new ArrayList<HomePageIcon>();
    private static List<LeftEvents> cpLeftEventList = new ArrayList<LeftEvents>();
    private static List<String> cpLeftEventList2 = new ArrayList<String>();
    @BindView(R.id.main_swipemenu)
    SwipeMenu mainSwipemenu;
    Unbinder unbinder;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    AGListContract.Presenter presenter;
    private ScheduledExecutorService executorService;
    private onWaitingThread onWaitingThread = new onWaitingThread();
    private ScheduledExecutorService executorEndService;
    private  onWaitingEndThread onWaitingEndThread = new  onWaitingEndThread();
    private int sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
    private int sendEndTime = HGConstant.ACTION_SEND_LEAGUE_TIME_T;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";

    static {
        //注意事项  每次投注成功之后都需要刷新一下用户的金额 ，且是全局的金额都需要变动  需要发送一下全部的 Money  message 去
        cpGameList.add(new HomePageIcon("系统菜单", R.mipmap.home_hgty));
        cpGameList.add(new HomePageIcon("返回大厅", R.mipmap.home_hgty));
        cpGameList.add(new HomePageIcon("北京赛车(PK10)", R.mipmap.home_hgty));
        cpGameList.add(new HomePageIcon("重庆时时彩", R.mipmap.home_vrcp));
        cpGameList.add(new HomePageIcon("极速赛车", R.mipmap.home_qipai));
        cpGameList.add(new HomePageIcon("极速飞艇", R.mipmap.home_hgty));
        cpGameList.add(new HomePageIcon("分分彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("三分彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("五分彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("腾讯二分彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("PC蛋蛋", R.mipmap.home_ag));
        cpGameList.add(new HomePageIcon("江苏鼓宝(快3)", R.mipmap.home_ag));
        cpGameList.add(new HomePageIcon("幸运农场", R.mipmap.home_ag));
        cpGameList.add(new HomePageIcon("广东快乐十分", R.mipmap.home_vrcp));
        cpGameList.add(new HomePageIcon("香港六合彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("极速快三", R.mipmap.home_lhj));
        cpLeftEventList.add(new LeftEvents("两面", "1",false));
        cpLeftEventList.add(new LeftEvents("1-5球", "2",true));
        cpLeftEventList.add(new LeftEvents("前中后", "3",false));
        cpLeftEventList.add(new LeftEvents("两面", "4",false));
        cpLeftEventList.add(new LeftEvents("1-5球", "8",true));
        cpLeftEventList.add(new LeftEvents("前中后", "9",false));
        cpLeftEventList.add(new LeftEvents("两面", "7",false));
        cpLeftEventList.add(new LeftEvents("1-5球", "6",true));
        cpLeftEventList.add(new LeftEvents("前中后", "10",false));
        cpLeftEventList.add(new LeftEvents("两面", "5",false));
        cpLeftEventList2.add("3");
        cpLeftEventList2.add("小");
        cpLeftEventList2.add("单");
        cpLeftEventList2.add("虎");
        cpLeftEventList2.add("龙");
        cpLeftEventList2.add("虎");
        cpLeftEventList2.add("虎");
        cpLeftEventList2.add("龙");

    }

    public static CPOrderFragment newInstance(List<String> param1) {
        CPOrderFragment fragment = new CPOrderFragment();
        Bundle args = new Bundle();
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        Injections.inject(null, fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            userName = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);// 用以判断是电子还是真人
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_order;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        GameLog.log("屏幕的宽度："+cpList.getWidth());
        WindowManager wm = (WindowManager) getContext().getSystemService(Context.WINDOW_SERVICE);
        DisplayMetrics metrics = new DisplayMetrics();
        wm.getDefaultDisplay().getMetrics(metrics);
        /*mScreenWidth = metrics.widthPixels;
        mScreenHeight = metrics.heightPixels;*/
        //mainSwipemenu.setMenuOffset(metrics.widthPixels-Integer.parseInt(SizeUtil.Dp2Px(getContext(),50)+""));
        LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(),LinearLayoutManager.VERTICAL, false);
        cpList.setLayoutManager(gridLayoutManager);
        cpList.setHasFixedSize(true);
        cpList.setNestedScrollingEnabled(false);
        cpList.setAdapter(new CPOrederGameAdapter(getContext(), R.layout.item_cp_order_list, cpGameList));

        LinearLayoutManager linearLayoutManagerLeft = new LinearLayoutManager(getContext(),LinearLayoutManager.VERTICAL, false);
        cpOrderListLeft.setLayoutManager(linearLayoutManagerLeft);
        cpOrderListLeft.setHasFixedSize(true);
        cpOrderListLeft.setNestedScrollingEnabled(false);
        cpOrderListLeft.setAdapter(new CPOrederListLeftGameAdapter(getContext(), R.layout.item_cp_order_left_list, cpLeftEventList));

        LinearLayoutManager linearLayoutManagerRight = new LinearLayoutManager(getContext(),LinearLayoutManager.VERTICAL, false);
        cpOrderListRight.setLayoutManager(linearLayoutManagerRight);
        cpOrderListRight.setHasFixedSize(true);
        cpOrderListRight.setNestedScrollingEnabled(false);
        cpOrderListRight.setAdapter(new CPOrederListRightGameAdapter(getContext(), R.layout.item_cp_order_list, cpGameList));


        LinearLayoutManager cpOrderLotteryOpen11 = new LinearLayoutManager(getContext(),LinearLayoutManager.HORIZONTAL, false);
        cpOrderLotteryOpen1.setLayoutManager(cpOrderLotteryOpen11);
        cpOrderLotteryOpen1.setHasFixedSize(true);
        cpOrderLotteryOpen1.setNestedScrollingEnabled(false);
        cpOrderLotteryOpen1.setAdapter(new Open1GameAdapter(getContext(), R.layout.item_cp_order_open_1, cpLeftEventList));

        LinearLayoutManager cpOrderLotteryOpen22 = new LinearLayoutManager(getContext(),LinearLayoutManager.HORIZONTAL, false);
        cpOrderLotteryOpen2.setLayoutManager(cpOrderLotteryOpen22);
        cpOrderLotteryOpen2.setHasFixedSize(true);
        cpOrderLotteryOpen2.setNestedScrollingEnabled(false);
        cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
    }
    class Open1GameAdapter extends AutoSizeRVAdapter<LeftEvents> {
        private Context context;

        public Open1GameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, LeftEvents data, final int position) {
            switch (data.getEventId()){
                case "1":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_one);
                    break;
                case "2":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_two);
                    break;
                case "3":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_three);
                    break;
                case "4":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_four);
                    break;
                case "5":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_five);
                    break;
                case "6":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_six);
                    break;
                case "7":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_seven);
                    break;
                case "8":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_eight);
                    break;
                case "9":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_nine);
                    break;
                case "10":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_ten);
                    break;
            }

        }
    }

    class Open2GameAdapter extends AutoSizeRVAdapter<String> {
        private Context context;

        public Open2GameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, String data, final int position) {
            holder.setText(R.id.itemOrderOpen2,data);
        }
    }

    class CPOrederListLeftGameAdapter extends AutoSizeRVAdapter<LeftEvents> {
        private Context context;

        public CPOrederListLeftGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, LeftEvents data, final int position) {
            if(data.isEventChecked()){
                holder.setImageResource(R.id.itemOrderLeftListIV,R.drawable.cp_circle_checked);
            }else{
                holder.setImageResource(R.id.itemOrderLeftListIV,R.drawable.cp_circle_normal);
            }
            holder.setText(R.id.itemOrderLeftListTV, data.getEventName());
            holder.setOnClickListener(R.id.itemOrderLeftListTV, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    onRefreshRight(position);
                }
            });
        }
    }

    private void onRefreshRight(int position){
        showMessage("刷新后边的数据");
    }

    class CPOrederListRightGameAdapter extends AutoSizeRVAdapter<HomePageIcon> {
        private Context context;

        public CPOrederListRightGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, HomePageIcon data, final int position) {
            holder.setText(R.id.tv_item_game_name, data.getIconName());
            holder.setOnClickListener(R.id.tv_item_game_name, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //onCpGameItemClick(position);
                }
            });
        }
    }

    class CPOrederGameAdapter extends AutoSizeRVAdapter<HomePageIcon> {
        private Context context;

        public CPOrederGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, HomePageIcon data, final int position) {
            if(position==0){
               TextView tv =  holder.getView(R.id.tv_item_game_name);
                tv.setGravity(Gravity.CENTER);
            }
            holder.setText(R.id.tv_item_game_name, data.getIconName());
            holder.setOnClickListener(R.id.tv_item_game_name, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(position==0){
                        return;
                    }else if(position==1){
                        pop();
                        return;
                    }
                    onCpGameItemClick(position);
                }
            });
        }
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(AGListContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void postGoPlayGameResult(AGGameLoginResult agGameLoginResult) {

    }

    @Override
    public void postCheckAgLiveAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCheckAgGameAccountResult(CheckAgLiveResult checkAgLiveResult) {
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("用户的真人账户：" + personBalance.getBalance_ag());
    }

    @Override
    public void postAGGameResult(List<AGLiveResult> agLiveResult) {
        GameLog.log("游戏列表：" + agLiveResult);
    }

    @Override
    public void postCheckAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCreateAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

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
        EventBus.getDefault().unregister(this);
    }

    public void onBackPressed() {
        if (mainSwipemenu.isMenuShowing()) {
            mainSwipemenu.hideMenu();
        }
    }

    //等待时长
    class onWaitingThread implements Runnable {
        @Override
        public void run() {
            if (sendAuthTime-- <= 0) {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {

                        onSendAuthCode();
                    }
                });
            } else {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        if(rightOpenLotteryTime!=null){
                            rightOpenLotteryTime.setText(TimeHelper.getTimeString(sendAuthTime));
                            //GameLog.log(getString(R.string.n_register_phone_waiting) + sendAuthTime + "s");
                        }
                    }
                });
            }
        }
    }
    class onWaitingEndThread implements Runnable {
        @Override
        public void run() {
            if (sendEndTime-- <= 0) {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {

                        onSendEndCode();
                    }
                });
            } else {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        if(rightCloseLotteryTime!=null){
                            rightCloseLotteryTime.setText(TimeHelper.getTimeString(sendEndTime));
                            //GameLog.log(getString(R.string.n_register_phone_waiting) + sendAuthTime + "s");
                        }
                    }
                });
            }
        }
    }


    private void onSartTime(){
        onSendAuthCode();
        onSendEndCode();
    }

    //计数器，用于倒计时使用
    private void onSendAuthCode() {
        GameLog.log("-----开始-----");
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        executorService = Executors.newScheduledThreadPool(1);
        executorService.scheduleAtFixedRate(onWaitingThread, 0, 1000, TimeUnit.MILLISECONDS);
    }

    //计数器，用于倒计时使用
    private void onSendEndCode() {
        GameLog.log("-----开始-----");
        if(null!=executorEndService){
            executorEndService.shutdownNow();
            executorEndService.shutdown();
            executorEndService = null;
        }
        sendEndTime = HGConstant.ACTION_SEND_LEAGUE_TIME_T;
        executorEndService = Executors.newScheduledThreadPool(1);
        executorEndService.scheduleAtFixedRate(onWaitingEndThread, 0, 1000, TimeUnit.MILLISECONDS);
    }

    @OnClick({R.id.cpOrderTitle,R.id.cpOrderShow,R.id.llCPOrderAll,R.id.cpOrderMenu})
    public void onClickedView(View view ){
        switch (view.getId()){
            case R.id.cpOrderTitle:
            case R.id.cpOrderShow:
                if (mainSwipemenu.isMenuShowing()) {
                    mainSwipemenu.hideMenu();
                } else {
                    mainSwipemenu.showMenu();
                }
                break;
            case R.id.llCPOrderAll:
                if (mainSwipemenu.isMenuShowing()) {
                    mainSwipemenu.hideMenu();
                }
                break;
            case R.id.cpOrderMenu:
                showMessage("开发中。。。");
                break;
        }

    }

    private void onCpGameItemClick(int position) {
        cpOrderTitle.setText(cpGameList.get(position).getIconName());
        GameLog.log("你点击了"+cpGameList.get(position).getIconName());
        if (mainSwipemenu.isMenuShowing()) {
            mainSwipemenu.hideMenu();
        } else {
            mainSwipemenu.showMenu();
        }
        onSartTime();
    }
}
