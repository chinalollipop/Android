package com.hgapp.a6668.personpage.betrecord;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.DividerItemDecoration;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.text.Html;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.bigkoo.pickerview.view.TimePickerView;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.util.CLipHelper;
import com.hgapp.a6668.common.util.DateHelper;
import com.hgapp.a6668.common.util.GameShipHelper;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.data.BetRecordResult;
import com.hgapp.a6668.homepage.handicap.betnew.CloseBottomEvent;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.jcodecraeer.xrecyclerview.ProgressStyle;
import com.jcodecraeer.xrecyclerview.XRecyclerView;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class BetRecordFragment extends HGBaseFragment implements BetRecordContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    @BindView(R.id.tvBetRecordBack)
    NTitleBar tvBetRecordBack;
    @BindView(R.id.betRrcordGtype)
    TextView betRrcordGtype;
    @BindView(R.id.betRrcordChecked)
    TextView betRrcordChecked;
    @BindView(R.id.betRrcordCancel)
    TextView betRrcordCancel;
    @BindView(R.id.tvBetRecordStartTime)
    TextView tvBetRecordStartTime;
    @BindView(R.id.tvBetRecordEndTime)
    TextView tvBetRecordEndTime;
    @BindView(R.id.tvBetRecordToday)
    TextView tvBetRecordToday;
    @BindView(R.id.tvBetRecordLastDay)
    TextView tvBetRecordLastDay;
    @BindView(R.id.tvBetRecordLastWeek)
    TextView tvBetRecordLastWeek;
    @BindView(R.id.tvBetRecordLastMonth)
    TextView tvBetRecordLastMonth;
    @BindView(R.id.btnBetRecordSubmit)
    Button btnBetRecordSubmit;
    /*@BindView(R.id.lvBetRecord)
    NListView lvBetRecord;*/
    @BindView(R.id.lvBetRecord2)
    XRecyclerView lvBetRecord;
    @BindView(R.id.betTTop)
    ImageView betTTop;
    @BindView(R.id.tvBetRecordNodataT)
    TextView tvBetRecordNodataT;
    private BetRecordContract.Presenter presenter;
    private String typeArgs1;
    private String typeArgs2;
    OptionsPickerView gtypeOptionsPicker, checkedOptionsPicker,cancelOptionsPicker;
    TimePickerView pvStartTime;
    TimePickerView pvEndTime;
    int page=0;
    List<BetRecordResult.RowsBean> rowsBeanList = new ArrayList();
    RecordListAdapter recordListAdapter;
    String gtype ,checked ,cancel,data_start,data_end ;
    String tinme;
    boolean isNow;
    static List<String> gtypeList  = new ArrayList<>();
    static  List<String> checkedList = new ArrayList<>();
    static  List<String> cancelList  = new ArrayList<>();
    static {
        gtypeList.add("足球");
        gtypeList.add("篮球");
        gtypeList.add("冠军");

        checkedList.add("全部");
        checkedList.add("未结注单");
        checkedList.add("已结注单");

        cancelList.add("有效注单");
        cancelList.add("无效注单");



    }
    public static BetRecordFragment newInstance(String type1,String type2) {
        BetRecordFragment fragment = new BetRecordFragment();
        Bundle args = new Bundle();
        args.putString(TYPE1, type1);
        args.putString(TYPE2, type2);
        fragment.setArguments(args);
        Injections.inject(null, fragment);
        return fragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs1 = getArguments().getString(TYPE1);
            typeArgs2 = getArguments().getString(TYPE2);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_bet_record;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        onPostBetRecord();

    }

    private void onPostBetRecord(){
        gtype = "FT";
        checked = "";
        cancel = "N";
        tinme= getTime2(new Date());
        GameLog.log("当前的时间："+tinme);
        if(Integer.parseInt("12")>Integer.parseInt(tinme)){
            isNow = false;
            data_end = DateHelper.getYesterday()+" 23:59";
            data_start = DateHelper.getYesterday()+" 00:00";
        }else{
            isNow = true;
            data_end = DateHelper.getToday()+" 23:59";
            data_start = DateHelper.getToday()+" 00:00";
        }
        tvBetRecordStartTime.setText(data_start);
        tvBetRecordEndTime.setText(data_end);
        tvBetRecordBack.setMoreText(typeArgs2);
        tvBetRecordBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
                EventBus.getDefault().post(new CloseBottomEvent());
            }
        });

        //时间选择器
        pvStartTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                tvBetRecordStartTime.setText(getTime(date));
            }
        })
                .setType(new boolean[]{true, true, true, true, true, false})// 默认全部显示
                // .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();
        //时间选择器
        pvEndTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                tvBetRecordEndTime.setText(getTime(date));
            }
        })
                .setType(new boolean[]{true, true, true, true, true, false})// 默认全部显示
                //  .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();
        Calendar ca = Calendar.getInstance();// 得到一个Calendar的实例
        //ca.setTime(new Date()); // 设置时间为当前时间
        //ca.set(2011, 11, 17);// 月份是从0开始的，所以11表示12月
//        ca.add(Calendar.YEAR, -1); // 年份减1
//        ca.add(Calendar.MONTH, -1);// 月份减1
//        ca.add(Calendar.DATE, -1);// 日期减
        ca.add(Calendar.HOUR, -12);
        pvStartTime.setDate(ca);
        pvEndTime.setDate(ca);
        gtypeOptionsPicker = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                String text = gtypeList.get(options1);
                betRrcordGtype.setText(text);
                if(text.equals("足球")){
                    gtype = "FT";
                }else if(text.equals("篮球")){
                    gtype = "BK";
                }else{
                    gtype = "FS";
                }

            }
        }).build();
        gtypeOptionsPicker.setPicker(gtypeList);

        checkedOptionsPicker = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                String text = checkedList.get(options1);
                betRrcordChecked.setText(text);
                if(text.equals("全部")){
                    checked = "";
                }else if(text.equals("未结注单")){
                    checked = "N";
                }else{
                    checked = "Y";
                }
            }
        }).build();
        checkedOptionsPicker.setPicker(checkedList);

        cancelOptionsPicker = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                String text = cancelList.get(options1);
                betRrcordCancel.setText(text);
                if(text.equals("无效注单")){
                    cancel = "N";
                }else{
                    cancel = "Y";
                }
            }
        }).build();
        cancelOptionsPicker.setPicker(cancelList);

        /*if ("today".equals(typeArgs1)) {
            presenter.postBetToday("", "FT", "0");
        } else {
            presenter.postBetHistory("", "FT", "0");
        }*/
        final LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
        lvBetRecord.setLayoutManager(gridLayoutManager);
        lvBetRecord.setHasFixedSize(true);
        lvBetRecord.setNestedScrollingEnabled(true);
        lvBetRecord.setRefreshProgressStyle(ProgressStyle.BallSpinFadeLoader);
        lvBetRecord.setLoadingMoreProgressStyle(ProgressStyle.BallRotate);
        lvBetRecord.addItemDecoration(new DividerItemDecoration(getContext(),DividerItemDecoration.VERTICAL));
        lvBetRecord.setOnScrollListener(new RecyclerView.OnScrollListener() {
            @Override
            public void onScrollStateChanged(RecyclerView recyclerView, int newState) {
                super.onScrollStateChanged(recyclerView, newState);
                int childCount = gridLayoutManager.getChildCount();
                int first = gridLayoutManager.findLastVisibleItemPosition();
                GameLog.log("当前可见的位置是："+first);
                if(first >= 10){//first>=childCount/2
                    if(!Check.isNull(betTTop)){
                        betTTop.setVisibility(View.VISIBLE);
                    }
                }else{
                    if(!Check.isNull(betTTop)){
                        betTTop.setVisibility(View.GONE);
                    }
                }
            }

            @Override
            public void onScrolled(RecyclerView recyclerView, int dx, int dy) {
                super.onScrolled(recyclerView, dx, dy);
            }
        });
        lvBetRecord.setLoadingListener(new XRecyclerView.LoadingListener() {
            @Override
            public void onRefresh() {
                page =0;
                onSearchBetList();
            }

            @Override
            public void onLoadMore() {
                ++page;
                onSearchBetList();
            }
        });
    }

    private void onSearchBetList(){
        data_start = tvBetRecordStartTime.getText().toString();
        data_end = tvBetRecordEndTime.getText().toString();
        presenter.postBetRecordList("",gtype,checked,cancel,data_start,data_end,page+"");
    }

    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm");
        return format.format(date);
    }
    public static String getTime2(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("HH");
        return format.format(date);
    }
    @Override
    public void postBetRecordResult(BetRecordResult message) {
        GameLog.log("总共充值多少：" + message.getTotal());
        if(Check.isNull(message.getRows())){
            if(rowsBeanList.size()>0){
                lvBetRecord.setNoMore(true);
            }else{
                tvBetRecordNodataT.setVisibility(View.VISIBLE);
                lvBetRecord.setVisibility(View.GONE);
            }
        }else if(message.getRows().size()==0){
            tvBetRecordNodataT.setVisibility(View.VISIBLE);
            lvBetRecord.setVisibility(View.GONE);
        }else if(message.getRows().size()>0){
            tvBetRecordNodataT.setVisibility(View.GONE);
            lvBetRecord.setVisibility(View.VISIBLE);
            if(page == 0){
                rowsBeanList.clear();
                lvBetRecord.refreshComplete();
            }else{
                if(page >= message.getPage_count()-1){
                    lvBetRecord.setNoMore(true);
                    GameLog.log("无更多数据完成");
                }else{
                    lvBetRecord.loadMoreComplete();
                    GameLog.log("加载更多完成");
                }
            }
            rowsBeanList.addAll(message.getRows());

            if(recordListAdapter ==null){
                recordListAdapter =   new RecordListAdapter(getContext(), R.layout.item_bet_record, rowsBeanList);
                lvBetRecord.setAdapter(recordListAdapter);
            }
            recordListAdapter.notifyDataSetChanged();
        }
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Override
    public void setPresenter(BetRecordContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @OnClick({R.id.betTTop,R.id.betRrcordGtype,R.id.betRrcordChecked,R.id.betRrcordCancel,R.id.tvBetRecordStartTime, R.id.tvBetRecordEndTime, R.id.tvBetRecordToday, R.id.tvBetRecordLastDay, R.id.tvBetRecordLastWeek, R.id.tvBetRecordLastMonth, R.id.btnBetRecordSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.betTTop:
                lvBetRecord.scrollToPosition(0);
                betTTop.setVisibility(View.GONE);
                break;
            case R.id.betRrcordGtype:
                gtypeOptionsPicker.show();
                break;
            case R.id.betRrcordChecked:
                checkedOptionsPicker.show();
                break;
            case R.id.betRrcordCancel:
                cancelOptionsPicker.show();
                break;
            case R.id.tvBetRecordStartTime:
                pvStartTime.show();
                break;
            case R.id.tvBetRecordEndTime:
                pvEndTime.show();
                break;
            case R.id.tvBetRecordToday:
                GameLog.log("是否是当天 "+isNow+tinme);
                if(isNow){
                    data_start = DateHelper.getToday()+" 00:00";
                    data_end = DateHelper.getToday()+" 23:59";
                }else{
                    data_start = DateHelper.getYesterday()+" 00:00";
                    data_end = DateHelper.getYesterday()+" 23:59";
                }
                tvBetRecordStartTime.setText(data_start);
                tvBetRecordEndTime.setText(data_end);
                tvBetRecordToday.setTextColor(getResources().getColor(R.color.title_text));
                tvBetRecordLastDay.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastWeek.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastMonth.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordToday.setBackgroundResource(R.drawable.bg_btn_focus);
                tvBetRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvBetRecordLastDay:
                GameLog.log("是否是当天 "+isNow);
                if(!isNow){
                    data_start = DateHelper.getYesterday2()+" 00:00";
                    data_end = DateHelper.getYesterday2()+" 23:59";
                }else{
                    data_start = DateHelper.getYesterday()+" 00:00";
                    data_end = DateHelper.getYesterday()+" 23:59";
                }
                tvBetRecordStartTime.setText(data_start);
                tvBetRecordEndTime.setText(data_end);
                tvBetRecordToday.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastDay.setTextColor(getResources().getColor(R.color.title_text));
                tvBetRecordLastWeek.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastMonth.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastDay.setBackgroundResource(R.drawable.bg_btn_focus);
                tvBetRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvBetRecordLastWeek:
                if(!isNow){
                    data_start = DateHelper.getLastWeek2()+" 00:00";
                    data_end = DateHelper.getYesterday()+" 23:59";
                }else{
                    data_start = DateHelper.getLastWeek()+" 00:00";
                    data_end = DateHelper.getToday()+" 23:59";
                }
                tvBetRecordStartTime.setText(data_start);
                tvBetRecordEndTime.setText(data_end);
                tvBetRecordToday.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastDay.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastWeek.setTextColor(getResources().getColor(R.color.title_text));
                tvBetRecordLastMonth.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_focus);
                tvBetRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvBetRecordLastMonth:
                if(!isNow){
                    data_start = DateHelper.getCurrentMonthDayBegin()+" 00:00";
                    data_end = DateHelper.getYesterday()+" 23:59";
                }else{
                    data_start = DateHelper.getCurrentMonthDayBegin()+" 00:00";
                    data_end = DateHelper.getToday()+" 23:59";
                }
                tvBetRecordStartTime.setText(data_start);
                tvBetRecordEndTime.setText(data_end);
                tvBetRecordToday.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastDay.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastWeek.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastMonth.setTextColor(getResources().getColor(R.color.title_text));
                tvBetRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_focus);
                break;
            case R.id.btnBetRecordSubmit:
                page = 0;
                onSearchBetList();
                break;
        }
    }


    public class RecordListAdapter extends AutoSizeRVAdapter<BetRecordResult.RowsBean> {
        private Context context;

        public RecordListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(final ViewHolder holder, final BetRecordResult.RowsBean rowsBean, final int position) {
            holder.setText(R.id.betRecordItemData,rowsBean.getBetTime());
            holder.setText(R.id.betRecordItemTime,rowsBean.getOrderNo());
            if(!Check.isEmpty(rowsBean.getM_League())){
                holder.setText(R.id.betRecordItem1,rowsBean.getM_League());
                TextView textView2 =  holder.getView(R.id.betRecordItem2);
                /*String  item2 = rowsBean.getVs_team_id1()+onMarkGreen("VS")+rowsBean.getVs_team_id2();
                textView2.setText(Html.fromHtml(item2));*/
                textView2.setText(rowsBean.getTitle());
                String textCont3 =
                        rowsBean.getVs_team_name1()+onMarkGreen(rowsBean.getVs_or_let_ball_num())+rowsBean.getVs_team_name2()+onMarkRed(rowsBean.getCorner_num());//+ onMarkRed(rowsBean.getBet_content())
                TextView textView3 =  holder.getView(R.id.betRecordItem3);
                textView3.setText(Html.fromHtml(textCont3));
                String textCont4 ="";
                if(Check.isEmpty(rowsBean.getFirst_half())){
                    if(Check.isEmpty(rowsBean.getIsDanger())){
                        textCont4 = onMarkRed(rowsBean.getBet_content())+"@"+ onMarkRed(rowsBean.getBet_rate());
                    }else{
                        textCont4 = onMarkRed(rowsBean.getBet_content())+"@"+ onMarkRed(rowsBean.getBet_rate())+onMarkGreen(rowsBean.getIsDanger());
                    }
                }else{
                    if(Check.isEmpty(rowsBean.getIsDanger())){
                        textCont4 = onMarkRed(rowsBean.getBet_content()+rowsBean.getFirst_half())+"@"+ onMarkRed(rowsBean.getBet_rate());
                    }else{
                        textCont4 = onMarkRed(rowsBean.getBet_content()+rowsBean.getFirst_half())+"@"+ onMarkRed(rowsBean.getBet_rate())+onMarkGreen(rowsBean.getIsDanger());
                    }
                }
                TextView textView4 =  holder.getView(R.id.betRecordItem4);
                textView4.setText(Html.fromHtml(textCont4));
            }else{
                int sizeTemp  = rowsBean.getMiddle().size();
                StringBuilder stringBuilder = new StringBuilder();
                for(int k=0;k<sizeTemp;++k){
                    stringBuilder.append(rowsBean.getMiddle().get(k).getM_League()).append("<br>")
                            .append(rowsBean.getMiddle().get(k).getVs_team_name1()).append(onMarkGreen(rowsBean.getMiddle().get(k).getVs_or_let_ball_num())).append(rowsBean.getMiddle().get(k).getVs_team_name2()).append("<br>")
                            .append(onMarkRed(rowsBean.getMiddle().get(k).getBet_content())).append("@").append(onMarkRed(rowsBean.getMiddle().get(k).getBet_rate())).append("<br>");
                }
                TextView textView1 =  holder.getView(R.id.betRecordItem1);
                textView1.setText(Html.fromHtml(stringBuilder.toString()));
                holder.setVisible(R.id.betRecordItem2,false);
                holder.setVisible(R.id.betRecordItem3,false);
                holder.setVisible(R.id.betRecordItem4,false);
            }
            holder.setText(R.id.betRecordItemMoney, GameShipHelper.formatNumber(rowsBean.getBetScore()+""));
            String money = GameShipHelper.formatNumber(rowsBean.getM_Result());
            TextView betRecordItemWin =  holder.getView(R.id.betRecordItemWin);
            if(money.compareTo("0")>=0){
                if(rowsBean.getChecked().equals("1")){          //如果checked为1 且first_half为上半场  比分用 corner_num 否则 比分用 font_a
                    if(!Check.isEmpty(rowsBean.getFirst_half())){
                        betRecordItemWin.setText(Html.fromHtml(onMarkRed(rowsBean.getCorner_num()+"<br>赢<br>"+money)));
                    }else{
                        betRecordItemWin.setText(Html.fromHtml(onMarkRed(rowsBean.getFont_a()+"<br>赢<br>"+money)));
                    }
                }else{
                    betRecordItemWin.setText(Html.fromHtml(onMarkRed("赢<br>"+money)));
                }
            }else{
                if(Check.isNumericNull(money)){
                    betRecordItemWin.setText("");
                }else{
                    if(rowsBean.getChecked().equals("1")){      //如果checked为1 且first_half为上半场  比分用 corner_num 否则 比分用 font_a
                        if(!Check.isEmpty(rowsBean.getFirst_half())){
                            betRecordItemWin.setText(Html.fromHtml(onMarkGreen(rowsBean.getCorner_num()+"<br>输<br>"+money)));
                        }else{
                            betRecordItemWin.setText(Html.fromHtml(onMarkGreen(rowsBean.getFont_a()+"<br>输<br>"+money)));
                        }
                    }else{
                        betRecordItemWin.setText(Html.fromHtml(onMarkGreen("输<br>"+money)));
                    }
                }
            }
            holder.setOnLongClickListener(R.id.betRecordItemTime, new View.OnLongClickListener() {
                @Override
                public boolean onLongClick(View view) {
                    TextView textView = holder.getView(R.id.betRecordItemTime);
                    CLipHelper.copy(getContext(),textView.getText().toString());
                    showMessage("复制成功！");
                    return false;
                }
            });

            //holder.setText(R.id.betRecordItemWin,GameShipHelper.formatNumber(rowsBean.getM_Result()));
        }
    }
    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#C9270B'>" + sign+"</font>";
    }

    //标记为绿色
    private String onMarkGreen(String sign){
        return " <font color='#536DFE'>" + sign+"</font>";
    }

}
