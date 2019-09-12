package com.vene.tian.homepage.handicap.saiguo;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
import android.widget.BaseExpandableListAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.bigkoo.pickerview.view.TimePickerView;
import com.vene.tian.Injections;
import com.vene.tian.R;
import com.vene.tian.base.HGBaseFragment;
import com.vene.tian.base.IPresenter;
import com.vene.tian.common.adapters.AutoSizeRVAdapter;
import com.vene.tian.common.util.DateHelper;
import com.vene.tian.common.util.TxtTool;
import com.vene.tian.common.widgets.NExpandableListView;
import com.vene.tian.common.widgets.NTitleBar;
import com.vene.tian.data.BetRecordResult;
import com.vene.tian.data.SaiGuoResult;
import com.vene.tian.homepage.handicap.betnew.CloseBottomEvent;
import com.vene.common.util.Check;
import com.vene.common.util.GameLog;
import com.jcodecraeer.xrecyclerview.XRecyclerView;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class SaiGuoFragment extends HGBaseFragment implements SaiGuoContract.View {

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
    @BindView(R.id.exSaiGuoListView)
    NExpandableListView exListView;
    @BindView(R.id.lvBetRecord2)
    XRecyclerView lvBetRecord;
    @BindView(R.id.betTTop)
    ImageView betTTop;
    @BindView(R.id.tvBetRecordNodataT)
    TextView tvBetRecordNodataT;
    private SaiGuoContract.Presenter presenter;
    private String typeArgs1;
    private String typeArgs2;
    OptionsPickerView gtypeOptionsPicker, checkedOptionsPicker,cancelOptionsPicker;
    TimePickerView pvStartTime;
    TimePickerView pvEndTime;
    int page=0;
    List<SaiGuoResult.DataBean> arrayListDataAll = new ArrayList();

    SaiGuoListAdapter saiGuoListAdapter;
    String gtype ,checked ,cancel,data_start,data_end ;
    static List<String> gtypeList  = new ArrayList<>();
    static  List<String> checkedList = new ArrayList<>();
    static  List<String> cancelList  = new ArrayList<>();
    static {
        gtypeList.add("足球");
        gtypeList.add("篮球");

        checkedList.add("全部");
        checkedList.add("未结注单");
        checkedList.add("已结注单");

        cancelList.add("未取消交易单");
        cancelList.add("取消交易单");



    }
    public static SaiGuoFragment newInstance(String type1, String type2) {
        SaiGuoFragment fragment = new SaiGuoFragment();
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
        return R.layout.fragment_saiguo;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        onPostBetRecord();
        betTTop.setVisibility(View.GONE);
       /* String body = TxtTool.getStates(getContext());
        SaiGuoResult messageRequest = JSON.parseObject(body, SaiGuoResult.class);
       */
    }

    @Override
    public boolean onBackPressedSupport() {
        return true;
    }

    private void onPostBetRecord(){
        gtype = "FT";
        checked = "";
        cancel = "N";
        data_end = getTime(new Date());
        data_start = DateHelper.getToday();
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
                .setType(new boolean[]{true, true, true, false, false, false})// 默认全部显示
                // .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();

        //时间选择器
        pvEndTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                tvBetRecordEndTime.setText(getTime(date));
            }
        })
                .setType(new boolean[]{true, true, true, false, false, false})// 默认全部显示
                //  .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();

        gtypeOptionsPicker = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                String text = gtypeList.get(options1);
                betRrcordGtype.setText(text);
                if(text.equals("足球")){
                    gtype = "FT";
                }else{
                    gtype = "BK";
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
                if(text.equals("未取消交易单")){
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
        /*final LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
        lvBetRecord.setLayoutManager(gridLayoutManager);
        lvBetRecord.setHasFixedSize(true);
        lvBetRecord.setNestedScrollingEnabled(true);
        lvBetRecord.setRefreshProgressStyle(ProgressStyle.BallSpinFadeLoader);
        lvBetRecord.setLoadingMoreProgressStyle(ProgressStyle.BallRotate);
        lvBetRecord.addItemDecoration(new DividerItemDecoration(getContext(),DividerItemDecoration.VERTICAL));*/
        exListView.setOnScrollListener(new AbsListView.OnScrollListener() {

                                           @Override
                                           public void onScrollStateChanged(AbsListView absListView, int i) {
                                               GameLog.log("可视见面 "+absListView.getLastVisiblePosition());
                                               if(absListView.getLastVisiblePosition()>10){//first>=childCount/2
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
                                           public void onScroll(AbsListView absListView, int i, int i1, int i2) {

                                           }
                                       });
        /*lvBetRecord.setOnScrollListener(new RecyclerView.OnScrollListener() {
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
        });*/
       /* lvBetRecord.setLoadingListener(new XRecyclerView.LoadingListener() {
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
        });*/
    }

    private void onSearchBetList(){
        data_start = tvBetRecordStartTime.getText().toString();
        data_end = tvBetRecordEndTime.getText().toString();
        presenter.postSaiGuoList("",gtype,data_start);
    }

    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }
    @Override
    public void postSaiGuoResult(SaiGuoResult message) {
        //betTTop.setVisibility(View.VISIBLE);
        GameLog.log("总共充值多少：" + message.toString());
        tvBetRecordNodataT.setVisibility(View.GONE);
        lvBetRecord.setVisibility(View.VISIBLE);
        arrayListDataAll.clear();
        arrayListDataAll.addAll(message.getData());
        exListView.setAdapter(new MyExpandableAdapter(getContext()));
        int size = exListView.getCount();
        for (int i = 0; i < size; i++) {
            exListView.expandGroup(i);
        }
        /*if(message.getRows().size()==0){
            tvBetRecordNodataT.setVisibility(View.VISIBLE);
            lvBetRecord.setVisibility(View.GONE);
        }else if(message.getRows().size()>0){

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
*/
        String body = TxtTool.getStates(getContext());
        SaiGuoResult messageRequest = JSON.parseObject(body, SaiGuoResult.class);
            if(saiGuoListAdapter ==null){
                saiGuoListAdapter =   new SaiGuoListAdapter(getContext(), R.layout.item_saiguo, message.getData());
                lvBetRecord.setAdapter(saiGuoListAdapter);
            }
            saiGuoListAdapter.notifyDataSetChanged();
    }

    public class MyExpandableAdapter extends BaseExpandableListAdapter {
        private Context mContext;
        private LayoutInflater mLayoutInflater = null;

        public MyExpandableAdapter(Context context) {
            this.mContext = context;
            mLayoutInflater = LayoutInflater.from(mContext);
        }

        // 组的个数
        @Override
        public int getGroupCount() {

            //return groups.length;
            return arrayListDataAll.size();
        }

        @Override
        public long getGroupId(int groupPosition) {

            return groupPosition;
        }

        // 根据组的位置，组的成员个数
        @Override
        public int getChildrenCount(int groupPosition) {
            // 根据groupPosition获取某一个组的长度
            // return children[groupPosition].length;
            return arrayListDataAll.get(groupPosition).getResult().size();
        }

        @Override
        public Object getGroup(int groupPosition) {

            //return groups[groupPosition];
            return arrayListDataAll.get(groupPosition);
        }

        @Override
        public Object getChild(int groupPosition, int childPosition) {

            //return children[groupPosition][childPosition].length();
            return arrayListDataAll.get(groupPosition).getResult().get(childPosition);
        }

        @Override
        public long getChildId(int groupPosition, int childPosition) {

            return childPosition;
        }

        @Override
        public boolean hasStableIds() {

            return false;
        }

        @Override
        public View getGroupView(int groupPosition, boolean isExpanded,
                                 View convertView, ViewGroup parent) {
            GpViewHolder gpViewHolder = null;
            if (convertView == null) {
                convertView = View.inflate(mContext, R.layout.item_saiguo_father, null);
                gpViewHolder = new GpViewHolder();
                gpViewHolder.img = (ImageView) convertView.findViewById(R.id.img);
                gpViewHolder.title = (TextView) convertView.findViewById(R.id.title);
                convertView.setTag(gpViewHolder);
            } else {
                gpViewHolder = (GpViewHolder) convertView.getTag();
            }
            if (isExpanded) {
                gpViewHolder.img.setImageResource(R.mipmap.icon_ex_down);
            } else {
                gpViewHolder.img.setImageResource(R.mipmap.deposit_right);
            }
            gpViewHolder.title.setText(arrayListDataAll.get(groupPosition).getName());
            return convertView;
        }


        @Override
        public boolean isEmpty() {
            //groupArray为相应的父数据
            if (arrayListDataAll != null && arrayListDataAll.size() > 0) {
                return false;
            } else {
                return true;
            }
        }

        @Override
        public View getChildView(final int groupPosition, final int childPosition,
                                 boolean isLastChild, View convertView, ViewGroup parent) {
            //getItemViewType();
            View_Type_1 view_type_1 =null;
            final SaiGuoResult.DataBean.ResultBean resultBean = arrayListDataAll.get(groupPosition).getResult().get(childPosition);
            if(null==convertView){
                convertView = mLayoutInflater.inflate(R.layout.item_saiguo, null);
                view_type_1 = new View_Type_1(convertView);
                convertView.setTag(view_type_1);
            }else{
                view_type_1 = (View_Type_1) convertView.getTag();
            }
            view_type_1.betSaiGuoTime.setText(Html.fromHtml(resultBean.getM_Date()+"<br>"+resultBean.getM_Time()));
            view_type_1.betSaiGuoName.setText(Html.fromHtml(resultBean.getMB_Team()+"<br>"+resultBean.getTG_Team()));
            view_type_1.betSaiGuoHalf.setText(Html.fromHtml(resultBean.getMB_Inball()+"<br>"+resultBean.getTG_Inball()));
            view_type_1.betSaiGuoQuan.setText(Html.fromHtml(resultBean.getMB_Inball_HR()+"<br>"+resultBean.getTG_Inball_HR()));
            return convertView;
        }

        //gpViewHolder.img.setImageResource(R.drawable.qq_kong);
            //GameLog.log(" getChildView "+arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).type);
            //gpViewHolder.title.setText(arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).type);

        @Override
        public boolean isChildSelectable(int groupPosition, int childPosition) {

            return false;
        }

        class GpViewHolder {
            public ImageView img;
            TextView title;
        }

        class View_Type_1{
            public TextView betSaiGuoTime;
            public TextView betSaiGuoName;
            public TextView betSaiGuoHalf;
            public TextView betSaiGuoQuan;
            public View_Type_1(View convertView){
                betSaiGuoTime = (TextView) convertView.findViewById(R.id.betSaiGuoTime);
                betSaiGuoName = (TextView) convertView.findViewById(R.id.betSaiGuoName);
                betSaiGuoHalf = (TextView) convertView.findViewById(R.id.betSaiGuoHalf);
                betSaiGuoQuan = (TextView) convertView.findViewById(R.id.betSaiGuoQuan);
            }
        }
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        tvBetRecordNodataT.setVisibility(View.VISIBLE);
        lvBetRecord.setVisibility(View.GONE);
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void setPresenter(SaiGuoContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @OnClick({R.id.betTTop,R.id.betRrcordGtype,R.id.betRrcordChecked,R.id.betRrcordCancel,R.id.tvBetRecordStartTime, R.id.tvBetRecordEndTime, R.id.tvBetRecordToday, R.id.tvBetRecordLastDay, R.id.tvBetRecordLastWeek, R.id.tvBetRecordLastMonth, R.id.btnBetRecordSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.betTTop:
                exListView.scrollTo(0,0);
                //betTTop.setVisibility(View.GONE);
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
                tvBetRecordStartTime.setText(DateHelper.getToday());
                tvBetRecordEndTime.setText(DateHelper.getToday());
                tvBetRecordToday.setTextColor(getContext().getColor(R.color.title_text));
                tvBetRecordLastDay.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastWeek.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastMonth.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordToday.setBackgroundResource(R.drawable.bg_btn_focus);
                tvBetRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvBetRecordLastDay:
                tvBetRecordStartTime.setText(DateHelper.getYesterday());
                tvBetRecordEndTime.setText(DateHelper.getYesterday());
                tvBetRecordToday.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastDay.setTextColor(getContext().getColor(R.color.title_text));
                tvBetRecordLastWeek.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastMonth.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastDay.setBackgroundResource(R.drawable.bg_btn_focus);
                tvBetRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvBetRecordLastWeek:
                tvBetRecordStartTime.setText(DateHelper.getLastWeek());
                tvBetRecordEndTime.setText(DateHelper.getToday());
                tvBetRecordToday.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastDay.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastWeek.setTextColor(getContext().getColor(R.color.title_text));
                tvBetRecordLastMonth.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_focus);
                tvBetRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvBetRecordLastMonth:
                tvBetRecordStartTime.setText(DateHelper.getCurrentMonthDayBegin());
                tvBetRecordEndTime.setText(DateHelper.getToday());
                tvBetRecordToday.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastDay.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastWeek.setTextColor(getContext().getColor(R.color.n_edittext_pwd));
                tvBetRecordLastMonth.setTextColor(getContext().getColor(R.color.title_text));
                tvBetRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvBetRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_focus);
                break;
            case R.id.btnBetRecordSubmit:
                page = 0;
                //showMessage("加载中，请稍后");
                //showLoadingView();
                onSearchBetList();
                break;
        }
    }

    public class SaiGuoListAdapter extends AutoSizeRVAdapter<BetRecordResult.RowsBean> {
        private Context context;

        public SaiGuoListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final BetRecordResult.RowsBean rowsBean, final int position) {
            holder.setText(R.id.betSaiGuoTime,rowsBean.getBetTime());
            holder.setText(R.id.betSaiGuoName,rowsBean.getBetTime());
            holder.setText(R.id.betSaiGuoHalf,rowsBean.getBetTime());
            holder.setText(R.id.betSaiGuoQuan,rowsBean.getBetTime());
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
