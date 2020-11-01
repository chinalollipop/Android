package com.hgapp.betnew.homepage.cplist.lottery;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.bigkoo.pickerview.view.TimePickerView;
import com.hgapp.betnew.CPInjections;
import com.hgapp.betnew.R;
import com.hgapp.betnew.base.BaseActivity2;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.common.adapters.AutoSizeRVAdapter;
import com.hgapp.betnew.common.util.DateHelper;
import com.hgapp.betnew.data.CPLotteryListResult;
import com.hgapp.common.util.Check;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class CPLotteryListFragment extends BaseActivity2 implements CPLotteryListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpLotteryName)
    TextView cpLotteryName;
    @BindView(R.id.cpLotteryTime)
    TextView cpLotteryTime;
    @BindView(R.id.cpLotteryList)
    RecyclerView cpLotteryList;
    @BindView(R.id.backHome)
    ImageView backHome;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    CPLotteryListContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";
    TimePickerView pvStartTime;
    OptionsPickerView optionsPickerViewState;
    String gameId = "",gameTime = "";
    private static List<String> cpLeftEventList1 = new ArrayList<String>();
    private static List<String> cpLeftEventList2 = new ArrayList<String>();
    static  List<String> lotteryList  = new ArrayList<String>();
    static {
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
         */
        lotteryList.add("北京赛车");
        lotteryList.add("欢乐生肖");
        lotteryList.add("极速赛车");
        lotteryList.add("极速飞艇");
        lotteryList.add("分分彩");
        lotteryList.add("三分彩");
        lotteryList.add("五分彩");
        lotteryList.add("腾讯二分彩");
        lotteryList.add("PC蛋蛋");
        lotteryList.add("江苏快3");
        lotteryList.add("幸运农场");
        lotteryList.add("快乐十分");
        lotteryList.add("香港六合彩");
        lotteryList.add("极速快三");
        lotteryList.add("幸运飞艇");
    }

   /* public static CPMeFragment newInstance(List<String> param1) {
        CPMeFragment fragment = new CPMeFragment();
        Bundle args = new Bundle();
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        Injections.inject(null, fragment);
        fragment.setArguments(args);
        return fragment;
    }*/

    @Override
    public void onCreate(Bundle savedInstanceState) {
        CPInjections.inject(this,null);
        super.onCreate(savedInstanceState);
       /* if (getArguments() != null) {
            userName = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);
        }*/
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_lottery;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        initDataView();
        onSearchLotteryData();
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
        cpLotteryList.setLayoutManager(gridLayoutManager);
        cpLotteryList.setHasFixedSize(true);
        cpLotteryList.setNestedScrollingEnabled(false);
    }

    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }

    private void onSearchLotteryData(){
        presenter.postCPLotteryList(gameId+"/"+gameTime);
    }

    private void initDataView(){
        gameId ="2";
        gameTime = DateHelper.getToday();
        cpLotteryTime.setText(gameTime);
        //时间选择器
        pvStartTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                gameTime = getTime(date);
                cpLotteryTime.setText(gameTime);
                onSearchLotteryData();
            }
        })
                // .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .setType(new boolean[]{true, true, true, false, false, false})// 默认全部显示
                .build();

        optionsPickerViewState = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                String gameName = lotteryList.get(options1);
                switch (gameName){
                    case "北京赛车":
                        gameId ="51";
                        break;
                    case "欢乐生肖":
                        gameId ="2";
                        break;
                    case "极速赛车":
                        gameId ="189";
                        break;
                    case "极速飞艇":
                        gameId ="222";
                        break;
                    case "幸运飞艇":
                        gameId ="168";
                        break;
                    case "分分彩":
                        gameId ="207";
                        break;
                    case "三分彩":
                        gameId ="407";
                        break;
                    case "五分彩":
                        gameId ="507";
                        break;
                    case "腾讯二分彩":
                        gameId ="607";
                        break;
                    case "PC蛋蛋":
                        gameId ="304";
                        break;
                    case "江苏快3":
                        gameId ="159";
                        break;
                    case "幸运农场":
                        gameId ="47";
                        break;
                    case "快乐十分":
                        gameId ="3";
                        break;
                    case "香港六合彩":
                        gameId ="69";
                        break;
                    case "极速快三":
                        gameId ="384";
                        break;
                }
                onSearchLotteryData();
                cpLotteryName.setText(gameName);
            }
        }).build();
        optionsPickerViewState.setPicker(lotteryList);
        optionsPickerViewState.setSelectOptions (1);
    }

    @OnClick({R.id.backHome,R.id.cpLotteryName,R.id.cpLotteryTime})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.backHome:
                finish();
                break;
            case R.id.cpLotteryName:
                optionsPickerViewState.show();
                break;
            case R.id.cpLotteryTime:
                pvStartTime.show();
                break;
        }
    }

    @Override
    public void postCPLotteryListResult(CPLotteryListResult cpLotteryListResult) {
        if(!Check.isNull(cpLotteryListResult.getData())&&cpLotteryListResult.getData().size()==0){
            showMessage("暂无数据！");
            cpLotteryList.setVisibility(View.GONE);
        }else{
            cpLotteryList.setVisibility(View.VISIBLE);
            cpLotteryList.setAdapter(new LotteryListGameAdapter(getContext(), R.layout.item_cp_lottery, cpLotteryListResult.getData()));

        }
    }


    @Override
    public void setPresenter(CPLotteryListContract.Presenter presenter) {
        this.presenter = presenter;
    }

    class LotteryListGameAdapter extends AutoSizeRVAdapter<CPLotteryListResult.DataBean> {
        private Context context;

        public LotteryListGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final CPLotteryListResult.DataBean data, final int position) {
            cpLeftEventList1.clear();
            cpLeftEventList2.clear();
            holder.setText(R.id.cpLotteryItemTime, data.getTurnNum()+"\n"+data.getOpenTime());
            RecyclerView cpOrderLotteryOpen1 = holder.getView(R.id.cpOrderLotteryOpen1);
            RecyclerView cpOrderLotteryOpen2 = holder.getView(R.id.cpOrderLotteryOpen2);
            LinearLayoutManager cpOrderLotteryOpen11 = new LinearLayoutManager(getContext(), LinearLayoutManager.HORIZONTAL, false);
            cpOrderLotteryOpen1.setLayoutManager(cpOrderLotteryOpen11);
            cpOrderLotteryOpen1.setHasFixedSize(true);
            cpOrderLotteryOpen1.setNestedScrollingEnabled(false);
            LinearLayoutManager cpOrderLotteryOpen22 = new LinearLayoutManager(getContext(), LinearLayoutManager.HORIZONTAL, false);
            cpOrderLotteryOpen2.setLayoutManager(cpOrderLotteryOpen22);
            cpOrderLotteryOpen2.setHasFixedSize(true);
            cpOrderLotteryOpen2.setNestedScrollingEnabled(false);

            String [] dataList = data.getOpenNum().split(",");
            int dataListSize = dataList.length;
            int total= 0;
            for(int i=0;i<dataList.length;++i){
                cpLeftEventList1.add(dataList[i]);
                total += Integer.parseInt(dataList[i]);
            }
            switch (gameId){
                case "2":
                case "207":
                case "407":
                case "507":
                case "607":
                    cpLeftEventList2.add(total+"");
                    cpLeftEventList2.add((total >= 23)?"大":"小");
                    cpLeftEventList2.add((total % 2 ==1)?"单":"双");
                /*if(Integer.parseInt(dataList[0])>Integer.parseInt(dataList[4])){
                    cpLeftEventList2.add("龙");
                }else if(Integer.parseInt(dataList[0])==Integer.parseInt(dataList[4])){
                    cpLeftEventList2.add("和");
                }else{
                    cpLeftEventList2.add("虎");
                }*/
                    cpLeftEventList2.add(Integer.parseInt(dataList[0])>=Integer.parseInt(dataList[4])? Integer.parseInt(dataList[0])>Integer.parseInt(dataList[4])?"龙":"和":"虎");
                    cpOrderLotteryOpen1.setAdapter(new OpenQIUGameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList1));
                    cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                    break;
                case "51":
                case "189":
                case "222":
                case "168"://幸运飞艇 暂无
                    cpLeftEventList2.add(Integer.parseInt(dataList[0])+Integer.parseInt(dataList[1])+"");
                    cpLeftEventList2.add((Integer.parseInt(dataList[0])+Integer.parseInt(dataList[1]))>11?"大":"小");
                    cpLeftEventList2.add(((Integer.parseInt(dataList[0])+Integer.parseInt(dataList[1]))%2 ==1)?"单":"双");
                    cpLeftEventList2.add(Integer.parseInt(dataList[0])>Integer.parseInt(dataList[9])?"龙":"虎");
                    cpLeftEventList2.add(Integer.parseInt(dataList[1])>Integer.parseInt(dataList[8])?"龙":"虎");
                    cpLeftEventList2.add(Integer.parseInt(dataList[2])>Integer.parseInt(dataList[7])?"龙":"虎");
                    cpLeftEventList2.add(Integer.parseInt(dataList[3])>Integer.parseInt(dataList[6])?"龙":"虎");
                    cpLeftEventList2.add(Integer.parseInt(dataList[4])>Integer.parseInt(dataList[5])?"龙":"虎");
                    cpOrderLotteryOpen1.setAdapter(new Open1GameAdapter(getContext(), R.layout.item_cp_order_open_1, cpLeftEventList1));
                    cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                    break;
                case "47":
                case "3":
                    cpLeftEventList2.add(total+"");
                    cpLeftEventList2.add((total >= 84)?total > 84?"大":"和":"小");
                    cpLeftEventList2.add((total % 2 == 1) ? "单":"双");
                    cpLeftEventList2.add((total % 10 >= 5) ? "大":"小");
                    cpOrderLotteryOpen1.setAdapter(new OpenQIUGameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList1));
                    cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                    break;
                case "21"://广东11选5 暂无
                    cpLeftEventList2.add(total+"");
                    cpLeftEventList2.add((total >= 30)?total > 30?"大":"和":"小");
                    cpLeftEventList2.add((total % 2 == 1) ? "单":"双");
                    cpLeftEventList2.add((total % 10 >= 5) ? "大":"小");
                    cpLeftEventList2.add((Integer.parseInt(dataList[0])>Integer.parseInt(dataList[4])) ? "龙":"虎");
                    cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                    break;
                case "65"://"北京快乐8" 暂无
                    break;
                case "159":
                case "384":
                    cpLeftEventList2.add(total+"");
                    cpLeftEventList2.add((total >= 11) ? "大":"小");
                    cpOrderLotteryOpen1.setAdapter(new OpenK3GameAdapter(getContext(), R.layout.item_cp_order_open_1, cpLeftEventList1));
                    cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                    break;
                case "69":
                    //香港六合彩的没有二
                    String lastNums = cpLeftEventList1.get(cpLeftEventList1.size()-1);
                    cpLeftEventList1.remove(cpLeftEventList1.size()-1);
                    cpLeftEventList1.add("+");
                    cpLeftEventList1.add(lastNums);
                    cpOrderLotteryOpen1.setAdapter(new OpenHKQIUGameAdapter(getContext(), R.layout.item_cp_order_hk, cpLeftEventList1));
                    holder.setVisible(R.id.cpOrderLotteryOpen2,false);
                    break;
                case "304":
                    cpLeftEventList2.add(total+"");
                    cpLeftEventList2.add((total > 13) ? "大":"小");
                    cpLeftEventList2.add((total % 2 == 1) ? "单":"双");
                    cpLeftEventList1.add("=");
                    cpLeftEventList1.add(""+total);
                    cpOrderLotteryOpen1.setAdapter(new OpenQIUGameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList1));
                    cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                    break;
            }

        }
    }



    /**
     * 设置图片
     */
    class Open1GameAdapter extends AutoSizeRVAdapter<String> {
        private Context context;

        public Open1GameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, String data, final int position) {
            switch (data){
                case "01":
                case "1":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_one);
                    break;
                case "02":
                case "2":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_two);
                    break;
                case "03":
                case "3":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_three);
                    break;
                case "04":
                case "4":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_four);
                    break;
                case "05":
                case "5":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_five);
                    break;
                case "06":
                case "6":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_six);
                    break;
                case "07":
                case "7":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_seven);
                    break;
                case "08":
                case "8":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_eight);
                    break;
                case "09":
                case "9":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_nine);
                    break;
                case "10":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_ten);
                    break;
            }

        }
    }

    /**
     * 设置六合彩文字+球
     */
    class OpenHKQIUGameAdapter extends AutoSizeRVAdapter<String> {
        private Context context;

        public OpenHKQIUGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, String data, final int position) {
            switch (data){
                /*
                 *  香港六合彩波色
                 *  红波：01,02,07,08,12,13,18,19,23,24,29,30,34,35,40,45,46
                 *  蓝波：03,04,09,10,14,15,20,25,26,31,36,37,41,42,47,48
                 *  绿波：05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49
                 * */
                case "01":
                case "02":
                case "07":
                case "08":
                case "1":
                case "2":
                case "7":
                case "8":
                case "12":
                case "13":
                case "18":
                case "19":
                case "23":
                case "24":
                case "29":
                case "30":
                case "34":
                case "35":
                case "40":
                case "45":
                case "46":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.mipmap.cp_hk_red);
                    break;
                case "03":
                case "04":
                case "09":
                case "3":
                case "4":
                case "9":
                case "10":
                case "14":
                case "15":
                case "20":
                case "25":
                case "26":
                case "31":
                case "36":
                case "37":
                case "41":
                case "42":
                case "47":
                case "48":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.mipmap.cp_hk_blue);
                    break;
                case "05":
                case "06":
                case "5":
                case "6":
                case "11":
                case "16":
                case "17":
                case "21":
                case "22":
                case "27":
                case "28":
                case "32":
                case "33":
                case "38":
                case "39":
                case "43":
                case "44":
                case "49":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.mipmap.cp_hk_green);
                    break;
                default:
                    holder.setBackgroundRes(R.id.itemOrderOpen2,0);

            }

            holder.setText(R.id.itemOrderOpen2,data);
        }
    }

    /**
     * 设置筛子图片
     */
    class OpenK3GameAdapter extends AutoSizeRVAdapter<String> {
        private Context context;

        public OpenK3GameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, String data, final int position) {
            switch (data){
                case "01":
                case "1":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.s_1);
                    break;
                case "02":
                case "2":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.s_2);
                    break;
                case "03":
                case "3":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.s_3);
                    break;
                case "04":
                case "4":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.s_4);
                    break;
                case "05":
                case "5":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.s_5);
                    break;
                case "06":
                case "6":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.s_6);
                    break;
            }

        }
    }


    /**
     * 设置文字+球
     */
    class OpenQIUGameAdapter extends AutoSizeRVAdapter<String> {
        private Context context;

        public OpenQIUGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, String data, final int position) {
            holder.setBackgroundRes(R.id.itemOrderOpen2,R.mipmap.cp_qiu);
            if(data.length()==2){
                if("0".equals(data.substring(0,1))){
                    holder.setText(R.id.itemOrderOpen2,data.substring(1,2));
                }else{
                    holder.setText(R.id.itemOrderOpen2,data);
                }
            }else{
                holder.setText(R.id.itemOrderOpen2,data);
            }
        }
    }

    /**
     * 设置文字
     */
    class Open2GameAdapter extends AutoSizeRVAdapter<String> {
        private Context context;

        public Open2GameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, String data, final int position) {
            if(data.length()==3){
                TextView textView  =  holder.getView(R.id.itemOrderOpen2);
                textView.setTextSize(10);
            }
            holder.setText(R.id.itemOrderOpen2,data);
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
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }
}
