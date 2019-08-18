package com.sunapp.bloc.homepage.handicap.betnew;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.sunapp.bloc.Injections;
import com.sunapp.bloc.R;
import com.sunapp.bloc.base.HGBaseFragment;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.common.adapters.AutoSizeAdapter;
import com.sunapp.bloc.common.util.ACache;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.data.SportsListResult;
import com.sunapp.bloc.homepage.sportslist.SportsListContract;
import com.sunapp.common.util.Check;
import com.sunapp.common.util.GameLog;
import com.zhy.adapter.abslistview.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;

public class LeagueListFragment extends HGBaseFragment implements SportsListContract.View{

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.tvLeagueName)
    TextView tvLeagueName;
    @BindView(R.id.lvLeagueList)
    com.sunapp.bloc.common.widgets.NListView lvLeagueList;
    LeagueListAdapter leagueListAdapter;

    // 数据源
    //private String[] groups = {"足球", "篮球", "网球", "排球", "羽毛球", "棒球", "其它"};

    private String getArgParam1, getArgParam2;
    SportsListContract.Presenter presenter;
    OptionsPickerView optionsPickerViewState;
    private int resource = 1;
    static List<String> groups = new ArrayList<String>();
    static List<String> stateList = new ArrayList<String>();

    static {
        stateList.add("香港盘");
        stateList.add("马来盘");
        stateList.add("印尼盘");
        stateList.add("欧洲盘");

        groups.add("足球（0）");
        groups.add("篮球 / 美式足球（0）");
        /*groups.add("网球");
        groups.add("排球");
        groups.add("羽毛球");
        groups.add("棒球");
        groups.add("其它");*/
    }

    public static LeagueListFragment newInstance(List<String> param1) {
        LeagueListFragment fragment = new LeagueListFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1.get(0));
        //args.putStringArrayList(ARG_PARAM1,(ArrayList<String>) param1);
        args.putString(ARG_PARAM2, param1.get(1));
        Injections.inject(null,fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            getArgParam1 = getArguments().getString(ARG_PARAM1);
            getArgParam2 = getArguments().getString(ARG_PARAM2);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_league_list;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

    }


    @Override
    public void onVisible() {
        super.onVisible();
        if(getArgParam1.equals("1")){
            tvLeagueName.setText("滚球赛事");

            //滚球
            presenter.postSportsListFTr(null,"FT","RB","league","");
            presenter.postSportsListBKr(null,"BK","RB","league","");

        }else if(getArgParam1.equals("2")){
            tvLeagueName.setText("今日赛事");
            //今日
            presenter.postSportsListFTs(null,"FT","FT","league","");
            presenter.postSportsListBKs(null,"BK","FT","league","");

        }else if(getArgParam1.equals("3")){
            tvLeagueName.setText("早盘赛事");
            //早盘
            presenter.postSportsListFU(null,"FT","FU","league","");
            presenter.postSportsListBU(null,"BK","FU","league","");

        }
        leagueListAdapter = new LeagueListAdapter(getContext(),R.layout.item_leagulistff, groups);
        lvLeagueList.setAdapter(leagueListAdapter);
        optionsPickerViewState = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                resource = options1;
            }
        }).build();
        optionsPickerViewState.setPicker(stateList);




    }

    @Override
    public void postSportsListResultResult(SportsListResult sportsListResult) {

    }


    @Override
    public void postSportsListResultResultFTr(SportsListResult sportsListResult) {
        int kkszie = 0;
        if(Check.isNull(sportsListResult.getData())){
            kkszie =0;
        }else{
            int listSize = sportsListResult.getData().size();
            for(int k=0;k<listSize;++k){
                kkszie += sportsListResult.getData().get(k).getNum();
            }
        }
        GameLog.log("滚球足球：postSportsListResultResultFTr "+kkszie);
        groups.remove(0);
        groups.add(0,"足球（"+kkszie+"）");
        leagueListAdapter.notifyDataSetInvalidated();

    }

    @Override
    public void postSportsListResultResultBKr(SportsListResult sportsListResult) {
        int kkszie = 0;
        if(Check.isNull(sportsListResult.getData())){
            kkszie =0;
        }else{
            int listSize = sportsListResult.getData().size();
            for(int k=0;k<listSize;++k){
                kkszie += sportsListResult.getData().get(k).getNum();
            }
        }
        GameLog.log("滚球篮球：postSportsListResultResultBKr "+kkszie);
        groups.remove(1);
        groups.add(1,"篮球 / 美式足球（"+kkszie+"）");
        leagueListAdapter.notifyDataSetInvalidated();
    }


    @Override
    public void postSportsListResultResultFTs(SportsListResult sportsListResult) {
        int kkszie = 0;
        if(Check.isNull(sportsListResult.getData())){
            kkszie =0;
        }else{
            int listSize = sportsListResult.getData().size();
            for(int k=0;k<listSize;++k){
                kkszie += sportsListResult.getData().get(k).getNum();
            }
        }

        GameLog.log("今日足球：postSportsListResultResultFTs "+kkszie);
        groups.remove(0);
        groups.add(0,"足球（"+kkszie+"）");
        leagueListAdapter.notifyDataSetInvalidated();
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
        GameLog.log("今日篮球：postSportsListResultResultFTs "+kkszie);
        groups.remove(1);
        groups.add(1,"篮球 / 美式足球（"+kkszie+"）");
        leagueListAdapter.notifyDataSetInvalidated();
    }

    @Override
    public void postSportsListResultResultFU(SportsListResult sportsListResult) {
        int kkszie = 0;
        if(Check.isNull(sportsListResult.getData())){
            kkszie =0;
        }else{
            int listSize = sportsListResult.getData().size();
            for(int k=0;k<listSize;++k){
                kkszie += sportsListResult.getData().get(k).getNum();
            }
        }
        GameLog.log("早盘足球：postSportsListResultResultFU "+kkszie);
        groups.remove(0);
        groups.add(0,"足球（"+kkszie+"）");
        leagueListAdapter.notifyDataSetInvalidated();
    }

    @Override
    public void postSportsListResultResultBU(SportsListResult sportsListResult) {
        int kkszie = 0;
        if(Check.isNull(sportsListResult.getData())){
            kkszie =0;
        }else{
            int listSize = sportsListResult.getData().size();
            for(int k=0;k<listSize;++k){
                kkszie += sportsListResult.getData().get(k).getNum();
            }
        }
        GameLog.log("早盘篮球：postSportsListResultResultBU "+kkszie);
        groups.remove(1);
        groups.add(1,"篮球 / 美式足球（"+kkszie+"）");
        leagueListAdapter.notifyDataSetInvalidated();
    }


    @Override
    public void setPresenter(SportsListContract.Presenter presenter) {
        this.presenter = presenter;
    }

    protected List<IPresenter> presenters()
    {
        return Arrays.asList((IPresenter) presenter);
    }

    public class LeagueListAdapter extends AutoSizeAdapter<String> {
        private Context context;

        public LeagueListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final String dataList, final int position) {
            holder.setText(R.id.child_title,dataList);
            onDrawable((ImageView) holder.getView(R.id.child_img),position);
            holder.setOnClickListener(R.id.child_title, new View.OnClickListener() {
                @Override
                public void onClick(View view) {

                    //拯救银河系的代码在此
                    String type ="";
                    if(getArgParam1.equals("1")&&position==0){
                        type = "1";
                    }else if(getArgParam1.equals("1")&&position==1){
                        type = "2";
                    }else if(getArgParam1.equals("2")&&position==0){
                        type = "3";
                    }else if(getArgParam1.equals("2")&&position==1){
                        type = "4";
                    }else if(getArgParam1.equals("3")&&position==0){
                        type = "5";
                    }else if(getArgParam1.equals("3")&&position==1){
                        type = "6";
                    }
                    GameLog.log("当前点击的位置是：position === "+ getArgParam1 + " 值是 ===  "+dataList.split("（")[0] );
                    EventBus.getDefault().post(new LeagueSearchEvent(getArgParam1,dataList.split("（")[0],type));
                    ACache.get(getContext()).put(HGConstant.USER_CURRENT_POSITION,"1");
                    //showMessage("您点击了 "+dataList);
                    //presenter.postSportsPlayMethod("",dataBean.getType(),"s",dataBean.getMID());
                   // EventBus.getDefault().post(new StartBrotherEvent(BetFragment.newInstance(dataBean.getM_League(),dataBean.getType(),dataBean.getMID(),cate,active,type,userMoney), SupportFragment.SINGLETASK));
                    //presenter.postSportsPlayMethod("","FT","s","3286634");
                }
            });

        }

        private void onDrawable(ImageView img ,int childPosition){

            switch (childPosition){
                case 0:
                    img.setImageResource(R.mipmap.icon_ex_soccer_off);
                    break;
                case 1:
                    img.setImageResource(R.mipmap.icon_ex_basketball_off);
                    break;
                case 2:
                    img.setImageResource(R.mipmap.icon_ex_tennis_off);
                    break;
                case 3:
                    img.setImageResource(R.mipmap.icon_ex_group);
                    break;
                case 4:
                    img.setImageResource(R.mipmap.icon_ex_badminton);
                    break;
                case 5:
                    img.setImageResource(R.mipmap.icon_ex_baseball);
                    break;
                case 6:
                    img.setImageResource(R.mipmap.icon_ex_bowling);
                    break;
            }
        }
    }

}
