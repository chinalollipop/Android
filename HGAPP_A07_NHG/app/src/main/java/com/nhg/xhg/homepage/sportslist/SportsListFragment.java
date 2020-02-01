package com.nhg.xhg.homepage.sportslist;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.widget.ListView;

import com.nhg.common.util.GameLog;
import com.nhg.xhg.Injections;
import com.nhg.xhg.R;
import com.nhg.xhg.base.HGBaseFragment;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.data.SportsListResult;
import com.zhy.adapter.abslistview.ViewHolder;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class SportsListFragment extends HGBaseFragment implements SportsListContract.View{

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    private static final String ARG_PARAM4 = "param4";
    @BindView(R.id.lvSportList)
    ListView lvSportList;
    private SportsListContract.Presenter presenter;
    /**
     * cate    FT_RB  足球滚球、FT 足球今日赛事 足球早盘 、BK_RB 篮球滚球、BK 篮球今日赛事 篮球早盘
     *
     *
     FT	足球今日赛事，滚球
     FU	足球早盘
     BK	篮球今日赛事，滚球
     BU	篮球早盘
     *
     */
    private String cate;
    /**
     * active=1&       1 足球滚球、今日赛事, 11 足球早餐，2 篮球滚球、今日赛事, 22 篮球早餐
     */
    private String active;
    private String type;
    private String userMoney;
    public static SportsListFragment newInstance(String cate,String active,String type,String userMoney) {
        SportsListFragment fragment = new SportsListFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1,cate);
        args.putString(ARG_PARAM2,active);
        args.putString(ARG_PARAM3,type);
        args.putString(ARG_PARAM4,userMoney);
        fragment.setArguments(args);
        Injections.inject(null,fragment);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            cate = getArguments().getString(ARG_PARAM1);
            active = getArguments().getString(ARG_PARAM2);
            type =  getArguments().getString(ARG_PARAM3);
            userMoney = getArguments().getString(ARG_PARAM4);
        }
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_sportslist;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        //     FT	足球今日赛事，滚球
        //     FU	足球早盘
        //     BK	篮球今日赛事，滚球
        //     BU	篮球早盘
        //cate    FT_RB  足球滚球、FT 足球今日赛事 足球早盘 、BK_RB 篮球滚球、BK 篮球今日赛事 篮球早盘
        String stype = "";
        String more = "";
        switch (type){
            case "1":
                stype = "FT";
                more = "r";
                break;
            case "2":
                stype = "BK";
                more = "r";
                break;
            case "3":
                stype = "FT";
                more = "s";
                break;
            case "4":
                stype = "BK";
                more = "s";
                break;
            case "5":
                stype = "FU";
                break;
            case "6":
                stype = "BU";

                break;
        }
        GameLog.log("请求的数据类型type：【"+stype+" 】more : "+more);
        presenter.postSportsList("",stype,more);
    }

    private void onCheckThirdMobilePay(){
       /* String thirdBankMoney = etDepositThirdBankMoney.getText().toString().trim();

        if(Check.isEmpty(thirdBankMoney)){
            showMessage("汇款金额必须是整数！");
            return;
        }*/
        //llBet.setVisibility(View.VISIBLE);
        //EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(dataBean.getUrl(),thirdBankMoney,dataBean.getUserid(),dataBean.getId(),bankCode), SupportFragment.SINGLETASK));
    }

    /*@OnClick(R.id.btnDepositThirdBankSubmit)
    public void onViewClicked() {
        onCheckThirdMobilePay();
    }*/

    @Override
    public void setPresenter(SportsListContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public void postSportsListResultResult(SportsListResult sportsListResult) {

        GameLog.log("盘口列表的数据状态："+sportsListResult.getData().size());
        if(sportsListResult.getData().size()>0){
            lvSportList.setAdapter(new SportsListAdapter(getContext(),R.layout.item_sports,sportsListResult.getData()));
        }else{
            showMessage("盘口暂无数据，请稍后！");
        }

    }

    @Override
    public void postSportsListResultResultFU(SportsListResult sportsListResult) {

    }

    @Override
    public void postSportsListResultResultFTs(SportsListResult sportsListResult) {

    }

    @Override
    public void postSportsListResultResultFTr(SportsListResult sportsListResult) {

    }

    @Override
    public void postSportsListResultResultBU(SportsListResult sportsListResult) {

    }

    @Override
    public void postSportsListResultResultBKs(SportsListResult sportsListResult) {

    }

    @Override
    public void postSportsListResultResultBKr(SportsListResult sportsListResult) {

    }


    public class SportsListAdapter extends com.nhg.xhg.common.adapters.AutoSizeAdapter<SportsListResult.DataBean> {
        private Context context;

        public SportsListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final SportsListResult.DataBean dataBean, final int position) {
           /* holder.setOnClickListener(R.id.llSportsItem, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //presenter.postSportsPlayMethod("",dataBean.getType(),"s",dataBean.getMID());
                    EventBus.getDefault().post(new StartBrotherEvent(BetFragment.newInstance(dataBean.getM_League(),dataBean.getType(),dataBean.getMID(),cate,active,type,userMoney), SupportFragment.SINGLETASK));
                    //presenter.postSportsPlayMethod("","FT","s","3286634");
                }
            });
            holder.setText(R.id.tvSportsLeague, dataBean.getM_League());
            holder.setText(R.id.tvSportsTime, dataBean.getM_Time());
            holder.setText(R.id.tvSportsMBTeam, dataBean.getMB_Team());
            holder.setText(R.id.tvSportsTGTeam, dataBean.getTG_Team());*/

        }
    }

    @OnClick(R.id.ivSportListBack)
    public void onClickView(){
        finish();
    }

}
