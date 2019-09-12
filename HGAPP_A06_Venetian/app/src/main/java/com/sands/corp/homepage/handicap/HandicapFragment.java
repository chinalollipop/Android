package com.sands.corp.homepage.handicap;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.ExpandableListView;
import android.widget.ImageView;
import android.widget.TextView;

import com.bigkoo.pickerview.view.OptionsPickerView;
import com.sands.corp.Injections;
import com.sands.corp.R;
import com.sands.corp.base.HGBaseFragment;
import com.sands.corp.base.IPresenter;
import com.sands.corp.common.http.Client;
import com.sands.corp.common.util.ACache;
import com.sands.corp.common.util.HGConstant;
import com.sands.corp.common.widgets.NExpandableListView;
import com.sands.corp.data.SportsListResult;
import com.sands.corp.homepage.UserMoneyEvent;
import com.sands.corp.homepage.handicap.betnew.BetNewFragment;
import com.sands.corp.homepage.handicap.betnew.CloseBottomEvent;
import com.sands.corp.homepage.handicap.leaguedetail.zhbet.ZHBetViewManager;
import com.sands.corp.homepage.handicap.saiguo.SaiGuoFragment;
import com.sands.corp.homepage.online.OnlineFragment;
import com.sands.corp.homepage.sportslist.SportsListContract;
import com.sands.common.util.Check;
import com.sands.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class HandicapFragment extends HGBaseFragment implements SportsListContract.View{

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.ivHandBack)
    ImageView ivHandBack;
    @BindView(R.id.tvHandicapUserName)
    TextView tvHandicapUserName;
    @BindView(R.id.tvHandicapUserMoney)
    TextView tvHandicapUserMoney;
    @BindView(R.id.exListView)
    NExpandableListView exListView;

    @BindView(R.id.tvHandicapName)
    TextView tvHandicapName;
    @BindView(R.id.tvHandicapRule)
    TextView tvHandicapRule;

    @BindView(R.id.btn_login_1)
    Button btnLogin1;
    @BindView(R.id.btn_login_2)
    Button btnLogin2;
    @BindView(R.id.btn_login_3)
    Button btnLogin3;
    @BindView(R.id.btn_login_4)
    Button btnLogin4;
    @BindView(R.id.btn_login_5)
    Button btnLogin5;
    @BindView(R.id.btn_login_6)
    Button btnLogin6;
    /**
     * cate    FT_RB  足球滚球、FT 足球今日赛事 足球早盘 、BK_RB 篮球滚球、BK 篮球今日赛事 篮球早盘
     * <p>
     * <p>
     * FT	足球今日赛事，滚球
     * FU	足球早盘
     * BK	篮球今日赛事，滚球
     * BU	篮球早盘
     */
    private String cate;
    /**
     * type H 主队独赢 C 客队独赢 N 和局  C 滚球大小-小  H 滚球大小-大 C 球队得分大小-主队 H 球队得分大小-客队 H 主队让球 C 客队让球
     */
    private String type;
    /**
     * active=1&       1 足球滚球、今日赛事, 11 足球早餐，2 篮球滚球、今日赛事, 22 篮球早餐
     */
    private String active;

    /**
     * 单双玩法投注传参
     * ODD 单
     * EVEN 双
     */
    private String rtype;
    /**
     * （全场大小、半场大小、球队得分大小） OUH 大，OUC 小，ROUH 球队得分大小-大，ROUC 球队得分大小-小
     * 今日赛事
     * 大小单场 & 大小半场
     * 主队 大 OUH
     * 客队 小 OUC
     * * 滚球赛事
     * 大小单场 & 大小半场
     * 主队 大 ROUH
     * 客队 小 ROUC
     * 足球
     篮球
     网球
     排球
     羽毛球
     棒球
     其它
     */
    private String wtype;

    // 数据源
    private String[] groups = { "滚球赛事", "今日赛事", "早盘赛事" };
    /*private String[][] children = {
            { "足球（0）", "篮球 / 美式足球（0）", "网球（0）", "排球（0）", "羽毛球（0）", "棒球（0）", "其它（0）" },
            { "足球（0）", "篮球 / 美式足球（0）", "网球（0）", "排球（0）", "羽毛球（0）", "棒球（0）", "其它（0）" },
            { "足球（0）", "篮球 / 美式足球（0）", "网球（0）", "排球（0）", "羽毛球（0）", "棒球（0）", "其它（0）" }
    };*/
    /*private String[][] children = {
            { "足球", "篮球 / 美式足球", "网球", "排球", "羽毛球", "棒球", "其它" },
            { "足球", "篮球 / 美式足球", "网球", "排球", "羽毛球", "棒球", "其它" },
            { "足球", "篮球 / 美式足球", "网球", "排球", "羽毛球", "棒球", "其它" }
    };*/
    private String[][] children = {
            { "足球（0）", "篮球 / 美式足球（0）" },
            { "足球（0）", "篮球 / 美式足球（0）" },
            { "足球（0）", "篮球 / 美式足球（0）" }
    };
    private String userName,userMoney;

    OptionsPickerView optionsPickerViewState;

    SportsListContract.Presenter presenter;

    private int resource = 1;
    private MyExpandableAdapter myExAdapter;
    static List<String> stateList  = new ArrayList<String>();
    static {
        stateList.add("香港盘");
        stateList.add("马来盘");
        stateList.add("印尼盘");
        stateList.add("欧洲盘");
    }

    public static HandicapFragment newInstance(String param1,String param2) {
        HandicapFragment fragment = new HandicapFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1,param1);
        args.putString(ARG_PARAM2,param2);
        Injections.inject(null,fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            userName = getArguments().getString(ARG_PARAM1);
            userMoney = getArguments().getString(ARG_PARAM2);
        }
    }

    public void onPostData() {
        /**
         *
         足球:
         早盘
         var_api.php?appRefer=13&type=FU&more=
         今日
         var_api.php?appRefer=13&type=FT&more=s
         滚球
         var_api.php?appRefer=13&type=FT&more=r

         篮球：
         早盘
         var_api.php?appRefer=13&type=BU&more=
         今日
         var_api.php?appRefer=13&type=BK&more=s
         滚球
         var_api.php?appRefer=13&type=BK&more=r
         */
        //滚球
        presenter.postSportsListFTr(null,"FT","RB","league","");
        presenter.postSportsListBKr(null,"BK","RB","league","");

        //今日
        presenter.postSportsListFTs(null,"FT","FT","league","");
        presenter.postSportsListBKs(null,"BK","FT","league","");

        //早盘
        presenter.postSportsListFU(null,"FT","FU","league","");
        presenter.postSportsListBU(null,"BK","FU","league","");


    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_handicap;
    }

    @Override
    public void onAttach(Context context) {
        super.onAttach(context);
        EventBus.getDefault().register(this);
    }

    @Subscribe
    public void onEventMain(CloseBottomEvent closeBottomEvent){
        BottombarViewManager.getSingleton().onShowView(getActivity(),this,"","","");
    }

    @Subscribe
    public void onEventMain(UserMoneyEvent userMoneyEvent){
        userMoney = userMoneyEvent.money;
        tvHandicapUserMoney.setText(userMoneyEvent.money);
    }

    @Override
    public void onDetach() {
        super.onDetach();
        EventBus.getDefault().unregister(this);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        BottombarViewManager.getSingleton().onShowView(getActivity(),this,"","","");
        try {
            onPostData();
        }catch (Exception  e){
            e.printStackTrace();
        }
        tvHandicapUserName.setText(userName);
        tvHandicapUserMoney.setText(userMoney);
        myExAdapter = new MyExpandableAdapter(getContext(), groups, children);

        exListView.setAdapter(myExAdapter);

        exListView.setOnChildClickListener(new ExpandableListView.OnChildClickListener() {

            @Override
            public boolean onChildClick(ExpandableListView parent, View v,
                                        int groupPosition, int childPosition, long id) {

                //showMessage(groups[groupPosition]+children[groupPosition][childPosition]);
                String message = children[groupPosition][childPosition].split("（")[0]+"-"+groups[groupPosition];
                ACache.get(getContext()).put(HGConstant.USER_CURRENT_POSITION,"1");
                switch (groupPosition){
                    case 0:
                        switch (childPosition){
                            case 0:
                                cate = "FT_RB";
                                active = "1";
                                type = "1";
                                //EventBus.getDefault().post(new StartBrotherEvent(SportsListFragment.newInstance(cate,active,type,userMoney), SupportFragment.SINGLETASK));
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"1",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));

                                break;
                            case 1:
                                cate = "BK_RB";
                                active = "2";
                                type = "2";
                                //EventBus.getDefault().post(new StartBrotherEvent(SportsListFragment.newInstance(cate,active,type,userMoney), SupportFragment.SINGLETASK));
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"1",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));

                                break;
                            case 2:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"1",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));

                                break;
                            case 3:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"1",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                            case 4:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"1",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                            case 5:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"1",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                            case 6:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"1",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                        }

                        break;
                    case 1:
                        switch (childPosition){
                            case 0:
                                cate = "FT";
                                active = "1";
                                type = "3";
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"2",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));

                                //EventBus.getDefault().post(new StartBrotherEvent(SportsListFragment.newInstance(cate,active,type,userMoney), SupportFragment.SINGLETASK));
                                break;
                            case 1:
                                cate = "BK";
                                active = "2";
                                type = "4";
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"2",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));

                                //EventBus.getDefault().post(new StartBrotherEvent(SportsListFragment.newInstance(cate,active,type,userMoney), SupportFragment.SINGLETASK));
                                break;
                            case 2:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"2",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                            case 3:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"2",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                            case 4:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"2",children[groupPosition][childPosition].split("（")[0],cate,active,type,children[groupPosition][childPosition])), SupportFragment.SINGLETASK));
                                break;
                            case 5:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"2",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                            case 6:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"2",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                        }
                        break;
                    case 2:
                        switch (childPosition){
                            case 0:
                                cate = "FT";
                                active = "11";
                                type = "5";
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"3",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));

                                //EventBus.getDefault().post(new StartBrotherEvent(SportsListFragment.newInstance(cate,active,type,userMoney), SupportFragment.SINGLETASK));
                                break;
                            case 1:
                                cate = "BK";
                                active = "22";
                                type = "6";
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"3",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));

                               // EventBus.getDefault().post(new StartBrotherEvent(SportsListFragment.newInstance(cate,active,type,userMoney), SupportFragment.SINGLETASK));
                                break;
                            case 2:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"3",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                            case 3:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"3",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                            case 4:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"3",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                            case 5:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"3",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                            case 6:
                                EventBus.getDefault().post(new StartBrotherEvent(BetNewFragment.newInstance(Arrays.asList(userName,userMoney,"3",children[groupPosition][childPosition].split("（")[0],cate,active,type)), SupportFragment.SINGLETASK));
                                break;
                        }
                        break;
                    case 3:

                        break;
                    case 4:

                        break;
                    case 5:

                        break;
                    case 6:

                        break;
                }

                return true;
            }
        });
        exListView.expandGroup(1,false);
        exListView.setOnGroupExpandListener(new ExpandableListView.OnGroupExpandListener() {
            @Override
            public void onGroupExpand(int groupPosition) {
                for (int i = 0; i < groups.length; i++) {
                    if (groupPosition != i) {
                        exListView.collapseGroup(i);
                    }
                }
            }
        });


       /* optionsPickerViewState = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                resource = options1;
                tvHandicapName.setText(stateList.get(options1));
            }
        }).build();
        optionsPickerViewState.setPicker(stateList);*/

    }

    @Override
    public void onVisible() {
        super.onVisible();
        ZHBetViewManager.getSingleton().onCloseView();
    }

    @Override
    public boolean onBackPressedSupport() {
        return true;
    }

    //FT_RB  足球滚球、FT 足球今日赛事 足球早盘 、BK_RB 篮球滚球、BK 篮球今日赛事 篮球早盘
    // 1 足球滚球、今日赛事, 11 足球早餐，2 篮球滚球、今日赛事, 22 篮球早餐
    @OnClick({R.id.ivHandBack,R.id.tvHandicapName,R.id.tvHandicapRule,R.id.btn_login_1, R.id.btn_login_2, R.id.btn_login_3, R.id.btn_login_4, R.id.btn_login_5, R.id.btn_login_6})
    public void onViewClicked(View view) {
        String cate = "";
        String active = "";
        String type = "";
        switch (view.getId()) {
            case R.id.ivHandBack:
                BottombarViewManager.getSingleton().onCloseView();
                pop();
                break;
            case R.id.tvHandicapName:
                BottombarViewManager.getSingleton().onCloseView();
                EventBus.getDefault().post(new StartBrotherEvent(SaiGuoFragment.newInstance(userMoney,userMoney ), SupportFragment.SINGLETASK));
                //optionsPickerViewState.show();
                break;

            case R.id.tvHandicapRule:
                BottombarViewManager.getSingleton().onCloseView();
                EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney, Client.baseUrl()+"template/sportroul.php?tip=app")));
                break;

            case R.id.btn_login_1:
                cate = "FT_RB";
                active = "1";
                type = "1";
                break;
            case R.id.btn_login_2:
                cate = "BK_RB";
                active = "2";
                type = "2";
                break;
            case R.id.btn_login_3:
                cate = "FT";
                active = "1";
                type = "3";
                break;
            case R.id.btn_login_4:
                cate = "BK";
                active = "2";
                type = "4";
                break;
            case R.id.btn_login_5:
                cate = "FT";
                active = "11";
                type = "5";
                break;
            case R.id.btn_login_6:
                cate = "BK";
                active = "22";
                type = "6";
                break;
        }
        //EventBus.getDefault().post(new StartBrotherEvent(SportsListFragment.newInstance(cate,active,type), SupportFragment.SINGLETASK));
    }

    /**
     *
     足球:
     早盘
     var_api.php?appRefer=13&type=FU&more=
     今日
     var_api.php?appRefer=13&type=FT&more=s
     滚球
     var_api.php?appRefer=13&type=FT&more=r

     篮球：
     早盘
     var_api.php?appRefer=13&type=BU&more=
     今日
     var_api.php?appRefer=13&type=BK&more=s
     滚球
     var_api.php?appRefer=13&type=BK&more=r
     */

    @Override
    public void postSportsListResultResult(SportsListResult sportsListResult) {

    }

    @Override
    public void postSportsListResultResultFTs(SportsListResult sportsListResult) {
        int kkszie = 0;
        if(Check.isNull(sportsListResult.getData())){
            kkszie =0;
        }else {
            int listSize = sportsListResult.getData().size();
            for (int k = 0; k < listSize; ++k) {
                kkszie += sportsListResult.getData().get(k).getNum();
            }
        }
        GameLog.log("今日足球：postSportsListResultResultFTs "+kkszie);
        children[1][0]="足球（"+kkszie+"）";
        myExAdapter.notifyDataSetInvalidated();
    }
    @Override
    public void postSportsListResultResultBKs(SportsListResult sportsListResult) {
        int kkszie = 0;
        if(Check.isNull(sportsListResult.getData())){
            kkszie =0;
        }else {
            int listSize = sportsListResult.getData().size();
            for (int k = 0; k < listSize; ++k) {
                kkszie += sportsListResult.getData().get(k).getNum();
            }
        }
        GameLog.log("今日篮球：postSportsListResultResultBKs "+kkszie);
        children[1][1]="篮球 / 美式足球（"+kkszie+"）";
        myExAdapter.notifyDataSetInvalidated();
    }

    @Override
    public void postSportsListResultResultFTr(SportsListResult sportsListResult) {
        int kkszie = 0;
        if(Check.isNull(sportsListResult.getData())){
            kkszie =0;
        }else {
            int listSize = sportsListResult.getData().size();
            for (int k = 0; k < listSize; ++k) {
                kkszie += sportsListResult.getData().get(k).getNum();
            }
        }
        GameLog.log("滚球足球：postSportsListResultResultFTr "+kkszie);
        children[0][0]="足球（"+kkszie+"）";
        myExAdapter.notifyDataSetInvalidated();
    }
    @Override
    public void postSportsListResultResultBKr(SportsListResult sportsListResult) {
        int kkszie = 0;
        if(Check.isNull(sportsListResult.getData())){
            kkszie =0;
        }else {
            int listSize = sportsListResult.getData().size();
            for (int k = 0; k < listSize; ++k) {
                kkszie += sportsListResult.getData().get(k).getNum();
            }
        }
        GameLog.log("滚球篮球：postSportsListResultResultBKr "+kkszie);
        children[0][1]="篮球 / 美式足球（"+kkszie+"）";
        myExAdapter.notifyDataSetInvalidated();
    }

    @Override
    public void postSportsListResultResultFU(SportsListResult sportsListResult) {
        int kkszie = 0;
        if(Check.isNull(sportsListResult.getData())){
            kkszie =0;
        }else {
            int listSize = sportsListResult.getData().size();
            for (int k = 0; k < listSize; ++k) {
                kkszie += sportsListResult.getData().get(k).getNum();
            }
        }
        GameLog.log("早盘足球：postSportsListResultResultFU "+kkszie);
        children[2][0]="足球（"+kkszie+"）";
        myExAdapter.notifyDataSetInvalidated();
    }
    @Override
    public void postSportsListResultResultBU(SportsListResult sportsListResult) {
        int kkszie = 0;
        if(Check.isNull(sportsListResult.getData())){
            kkszie =0;
        }else {
            int listSize = sportsListResult.getData().size();
            for (int k = 0; k < listSize; ++k) {
                kkszie += sportsListResult.getData().get(k).getNum();
            }
        }
        GameLog.log("早盘篮球：postSportsListResultResultBU "+kkszie);
        children[2][1]="篮球 / 美式足球（"+kkszie+"）";
        myExAdapter.notifyDataSetInvalidated();
    }

    @Override
    public void setPresenter(SportsListContract.Presenter presenter) {

        this.presenter = presenter;
    }

    protected List<IPresenter> presenters()
    {
       return Arrays.asList((IPresenter) presenter);
    }
}
