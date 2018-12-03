package com.hgapp.a6668.personpage.balancetransfer;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.StaggeredGridLayoutManager;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;

import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeAdapter;
import com.hgapp.a6668.common.widgets.CustomPopWindow;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.data.BetRecordResult;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class BalanceTransferFragment extends HGBaseFragment implements BalanceTransferContract.View {

    private static final String TYPE = "type";
    @BindView(R.id.backTitleBalanceTransfer)
    NTitleBar backTitleBalanceTransfer;
    @BindView(R.id.rvBalanceTransfer)
    RecyclerView flowBalanceTransfer;
    @BindView(R.id.tvBalanceTransferIn)
    TextView tvBalanceTransferIn;
    @BindView(R.id.tvBalanceTransferOut)
    TextView tvBalanceTransferOut;
    @BindView(R.id.etBalanceTransferMoney)
    EditText etBalanceTransferMoney;
    private BalanceTransferContract.Presenter presenter;
    LinearLayout popMenuHG,popMenuCP,popMenuAG,popMenuKY,popMenuFF,popMenuVG;
    TextView popMenuHGtv,popMenuCPtv,popMenuAGtv,popMenuKYtv,popMenuFFtv,popMenuVGtv;
    ImageView popMenuHGiv,popMenuCPiv,popMenuAGiv,popMenuKYiv,popMenuFFiv,popMenuVGiv;
    private String from ="hg";
    private String to ="hg";
    private CustomPopWindow mCustomPopWindowIn;
    private CustomPopWindow mCustomPopWindowOut;
    private String typeArgs;
    static List<String> searchRecordsArrayList  = new ArrayList<>();
    static  List<PopTransferEvent> itemPopTransferList  = new ArrayList<PopTransferEvent>();
    static {
        itemPopTransferList.add(new PopTransferEvent(true,"体育余额"));
        itemPopTransferList.add(new PopTransferEvent(false,"彩票余额"));
        itemPopTransferList.add(new PopTransferEvent(false,"AG余额"));

        searchRecordsArrayList.add("100");
        searchRecordsArrayList.add("500");
        searchRecordsArrayList.add("1000");
        searchRecordsArrayList.add("2000");
        searchRecordsArrayList.add("5000");

    }

    public static BalanceTransferFragment newInstance(String type) {
        BalanceTransferFragment fragment = new BalanceTransferFragment();
        Bundle args = new Bundle();
        args.putString(TYPE, type);
        fragment.setArguments(args);
        Injections.inject(null, fragment);
        return fragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs = getArguments().getString(TYPE);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_balancetransfer;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        backTitleBalanceTransfer.setMoreText(typeArgs);
        backTitleBalanceTransfer.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });

        RecyclerView.LayoutManager layoutActivityManager = new StaggeredGridLayoutManager(1, StaggeredGridLayoutManager.HORIZONTAL);
        flowBalanceTransfer.setLayoutManager(layoutActivityManager);

        flowBalanceTransfer.setAdapter(new FlowBalanceTransferAdapter(getContext(),R.layout.item_balance_transfer,searchRecordsArrayList));

        /*LayoutInflater mInflater = LayoutInflater.from(getContext());
        for ( int i = 0; i < searchRecordsArrayList.size(); i++) {
            TextView tv = (TextView)mInflater.inflate(
                    R.layout.item_balance_transfer, flowBalanceTransfer, false);
            AutoUtils.auto(tv);
            tv.setText(searchRecordsArrayList.get(i));
            final String str = tv.getText().toString();
            //点击事件
            tv.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {

                    etBalanceTransferMoney.setText("111");
                }
            });
            flowBalanceTransfer.addView(tv);
        }*/

    }


    class FlowBalanceTransferAdapter extends com.hgapp.a6668.common.adapters.AutoSizeRVAdapter<String>{

        private Context context;
        public FlowBalanceTransferAdapter(Context context, int layoutId, List<String> datas){
            super(context, layoutId, datas);
            this.context =  context;
        }
        @Override
        protected void convert(ViewHolder holder,final String  string,final int position) {

            holder.setText(R.id.tvItemBalanceTransfer,string);
            holder.setOnClickListener(R.id.tvItemBalanceTransfer,new View.OnClickListener(){

                @Override
                public void onClick(View view) {
                    etBalanceTransferMoney.setText(string);
                }
            });
        }


    }


    @Override
    public void postBetRecordResult(BetRecordResult message) {
        GameLog.log("总共充值多少：" + message.getTotal());

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        pop();
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Override
    public void setPresenter(BalanceTransferContract.Presenter presenter) {

        this.presenter = presenter;
    }

    private void onCheckTransferMoney(String id){
       String transferMoney =  etBalanceTransferMoney.getText().toString().trim();
       if(Check.isEmpty(transferMoney)){
           showMessage("请输入转换金额");
           return;
       }
        if(from.equals("hg")&&to.equals("ag")){
            presenter.postBanalceTransfer("","hg","ag",transferMoney);
        }else if(from.equals("ag")&&to.equals("hg")){
            presenter.postBanalceTransfer("","ag","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("cp")){
            presenter.postBanalceTransferCP("","fundLimitTrans","hg","cp",transferMoney);
        }else if(from.equals("cp")&&to.equals("hg")){
            presenter.postBanalceTransferCP("","fundLimitTrans","cp","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("ky")){
            presenter.postBanalceTransferKY("","hg","ky",transferMoney);
        }else if(from.equals("ky")&&to.equals("hg")){
            presenter.postBanalceTransferKY("","ky","hg",transferMoney);
        }else if(from.equals("ff")&&to.equals("hg")){
            presenter.postBanalceTransferHG("","ff","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("ff")){
            presenter.postBanalceTransferHG("","hg","ff",transferMoney);
        }else if(from.equals("vg")&&to.equals("hg")){
            presenter.postBanalceTransferVG("","vg","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("vg")){
            presenter.postBanalceTransferVG("","hg","vg",transferMoney);
        }else {
            showMessage("转账方式不支持");
        }


    }


    @OnClick({R.id.btnBalanceTrensferSubmit,R.id.tvBalanceTransferIn, R.id.tvBalanceTransferOut})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.btnBalanceTrensferSubmit:
                onCheckTransferMoney("");
                break;
            case R.id.tvBalanceTransferOut:
                showPopMenuOut();
                break;
            case R.id.tvBalanceTransferIn:
                showPopMenuIn();
                break;

        }
    }


    private void showPopMenuIn(){
        View contentView = LayoutInflater.from(getContext()).inflate(R.layout.pop_menu_out,null);
        //处理popWindow 显示内容
        handleLogicPopMenuIn(contentView);
        //创建并显示popWindow
        /*if(mCustomPopWindow !=null){
            mCustomPopWindow.dissmiss();
        }else{*/
        mCustomPopWindowIn= new CustomPopWindow.PopupWindowBuilder(getContext())
                    .setView(contentView)
                    .enableBackgroundDark(true)
                    .create()
                    .showAsDropDown(tvBalanceTransferIn,0,0);
        //}
    }


    private void showPopMenuOut(){
        View contentView = LayoutInflater.from(getContext()).inflate(R.layout.pop_menu_out,null);
        //处理popWindow 显示内容
        handleLogicPopMenuOut(contentView);
        //创建并显示popWindow
        mCustomPopWindowOut= new CustomPopWindow.PopupWindowBuilder(getContext())
                .setView(contentView)
                .enableBackgroundDark(true)
                .create()
                .showAsDropDown(tvBalanceTransferOut,0,0);

    }

    public class PopTransferInAdapter extends AutoSizeAdapter<PopTransferEvent> {
        private Context context;

        public PopTransferInAdapter(Context context, int layoutId, List<PopTransferEvent> datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(com.zhy.adapter.abslistview.ViewHolder viewHolder,final PopTransferEvent popTransferEvent, final int i) {
            GameLog.log("status: "+popTransferEvent.isStatus()+" postion:"+i);
            if (popTransferEvent.isStatus()) {
                viewHolder.setBackgroundRes(R.id.popMenuHG,R.color.colorPrimary);
                viewHolder.setImageResource(R.id.ivItemPopTransfer, R.mipmap.back);
                viewHolder.setTextColorRes(R.id.tvItemPopTransfer, R.color.pop_hight);
            }else{
                viewHolder.setBackgroundRes(R.id.popMenuHG,R.color.all_bg);
                viewHolder.setImageResource(R.id.ivItemPopTransfer,0);
                viewHolder.setTextColorRes(R.id.tvItemPopTransfer, R.color.pop_normal);
            }
            viewHolder.setText(R.id.tvItemPopTransfer, popTransferEvent.getMessage());
            viewHolder.setOnClickListener(R.id.tvItemPopTransfer, new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    //pop();
                    showMessage(""+popTransferEvent.getMessage());
                    for(int j=0;j<itemPopTransferList.size();++j){
                        itemPopTransferList.get(j).setStatus(false);
                    }
                    itemPopTransferList.get(i).setStatus(true);
                    tvBalanceTransferIn.setText(popTransferEvent.getMessage());
                    notifyDataSetChanged();
                    mCustomPopWindowIn.dissmiss();
                }
            });
        }


    }

    /**
     * 处理弹出显示内容、点击事件等逻辑
     * @param contentView
     */
    private void handleLogicPopMenuInList(View contentView){

        ListView  lvPopTransfer = (ListView) contentView.findViewById(R.id.lvPopTransfer);
        PopTransferInAdapter popTransferInAdapter = new PopTransferInAdapter(getContext(),R.layout.item_pop_transfer,itemPopTransferList);
        lvPopTransfer.setAdapter(popTransferInAdapter);
    }

    private void handleLogicPopMenuIn(View contentView){

        View.OnClickListener listener = new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                /*if(mCustomPopWindow!=null){
                    mCustomPopWindow.dissmiss();
                }*/
                String showContent = "";
                switch (v.getId()){
                    case R.id.popMenuHG:
                        to = "hg";
                        tvBalanceTransferIn.setText("体育余额");
                        showContent = "In点击 Item菜单1";
                        break;
                    case R.id.popMenuCP:
                        to = "cp";
                        tvBalanceTransferIn.setText("彩票余额");
                        showContent = "In 点击 Item菜单2";
                        break;
                    case R.id.popMenuAG:
                        to = "ag";
                        tvBalanceTransferIn.setText("AG余额");
                        showContent = "In 点击 Item菜单3";
                        break;
                    case R.id.popMenuKY:
                        to = "ky";
                        tvBalanceTransferIn.setText("开元棋牌");
                        showContent = "In 点击 Item菜单4";
                        break;
                    case R.id.popMenuFF:
                        to = "ff";
                        tvBalanceTransferIn.setText("皇冠棋牌");
                        showContent = "In 点击 Item菜单5";
                        break;
                    case R.id.popMenuVG:
                        to = "vg";
                        tvBalanceTransferIn.setText("VG棋牌");
                        showContent = "In 点击 Item菜单6";
                        break;
                }
                GameLog.log("转入："+showContent);
                //showMessage(showContent);
                mCustomPopWindowIn.dissmiss();
            }
        };
        popMenuHG = (LinearLayout) contentView.findViewById(R.id.popMenuHG);
        popMenuVG = (LinearLayout) contentView.findViewById(R.id.popMenuVG);
        popMenuCP = (LinearLayout) contentView.findViewById(R.id.popMenuCP);
        popMenuAG = (LinearLayout) contentView.findViewById(R.id.popMenuAG);
        popMenuKY = (LinearLayout) contentView.findViewById(R.id.popMenuKY);
        popMenuFF = (LinearLayout) contentView.findViewById(R.id.popMenuFF);
        popMenuHGiv = (ImageView) contentView.findViewById(R.id.popMenuHGiv);
        popMenuCPiv = (ImageView) contentView.findViewById(R.id.popMenuCPiv);
        popMenuAGiv = (ImageView) contentView.findViewById(R.id.popMenuAGiv);
        popMenuKYiv = (ImageView) contentView.findViewById(R.id.popMenuKYiv);
        popMenuFFiv = (ImageView) contentView.findViewById(R.id.popMenuFFiv);
        popMenuVGiv = (ImageView) contentView.findViewById(R.id.popMenuVGiv);
        popMenuHGtv = (TextView) contentView.findViewById(R.id.popMenuHGtv);
        popMenuCPtv = (TextView) contentView.findViewById(R.id.popMenuCPtv);
        popMenuAGtv = (TextView) contentView.findViewById(R.id.popMenuAGtv);
        popMenuKYtv = (TextView) contentView.findViewById(R.id.popMenuKYtv);
        popMenuFFtv = (TextView) contentView.findViewById(R.id.popMenuFFtv);
        popMenuVGtv = (TextView) contentView.findViewById(R.id.popMenuVGtv);
        popMenuHG.setOnClickListener(listener);
        popMenuCP.setOnClickListener(listener);
        popMenuAG.setOnClickListener(listener);
        popMenuKY.setOnClickListener(listener);
        popMenuFF.setOnClickListener(listener);
        popMenuVG.setOnClickListener(listener);
        // if(!Check.isNull(popMenuHGtv)&&!Check.isNull(popMenuCPtv)&&!Check.isNull(popMenuAGtv)){
        switch (to){
            case "hg":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuHGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "cp":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "ag":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "ky":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "ff":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuVGiv.setBackgroundResource(0);
                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "vg":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                break;
        }

    }

    /**
     * 处理弹出显示内容、点击事件等逻辑
     * @param contentView
     */
    private void handleLogicPopMenuOut(View contentView){


        View.OnClickListener listener = new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                /*if(mCustomPopWindow!=null){
                    mCustomPopWindow.dissmiss();
                }*/
                String showContent = "";
                switch (v.getId()){
                    case R.id.popMenuHG:
                        from = "hg";
                        tvBalanceTransferOut.setText("体育余额");
                        showContent = "Out点击 Item菜单1";
                        break;
                    case R.id.popMenuCP:
                        from = "cp";
                        tvBalanceTransferOut.setText("彩票余额");
                        showContent = "Out 点击 Item菜单2";
                        break;
                    case R.id.popMenuAG:
                        from = "ag";
                        tvBalanceTransferOut.setText("AG余额");
                        showContent = "Out 点击 Item菜单3";
                        break;
                    case R.id.popMenuKY:
                        from = "ky";
                        tvBalanceTransferOut.setText("开元棋牌");
                        showContent = "Out 点击 Item菜单4";
                        break;
                    case R.id.popMenuFF:
                        from = "ff";
                        tvBalanceTransferOut.setText("皇冠棋牌");
                        showContent = "Out 点击 Item菜单5";
                        break;
                    case R.id.popMenuVG:
                        from = "vg";
                        tvBalanceTransferOut.setText("VG棋牌");
                        showContent = "Out 点击 Item菜单5";
                        break;
                }
                GameLog.log("转出："+showContent);
                //showMessage(showContent);
                mCustomPopWindowOut.dissmiss();
            }
        };
        popMenuHG = (LinearLayout) contentView.findViewById(R.id.popMenuHG);
        popMenuCP = (LinearLayout) contentView.findViewById(R.id.popMenuCP);
        popMenuAG = (LinearLayout) contentView.findViewById(R.id.popMenuAG);
        popMenuKY = (LinearLayout) contentView.findViewById(R.id.popMenuKY);
        popMenuFF = (LinearLayout) contentView.findViewById(R.id.popMenuFF);
        popMenuVG = (LinearLayout) contentView.findViewById(R.id.popMenuVG);
        popMenuHGiv = (ImageView) contentView.findViewById(R.id.popMenuHGiv);
        popMenuCPiv = (ImageView) contentView.findViewById(R.id.popMenuCPiv);
        popMenuAGiv = (ImageView) contentView.findViewById(R.id.popMenuAGiv);
        popMenuKYiv = (ImageView) contentView.findViewById(R.id.popMenuKYiv);
        popMenuFFiv = (ImageView) contentView.findViewById(R.id.popMenuFFiv);
        popMenuVGiv = (ImageView) contentView.findViewById(R.id.popMenuVGiv);
        popMenuHGtv = (TextView) contentView.findViewById(R.id.popMenuHGtv);
        popMenuCPtv = (TextView) contentView.findViewById(R.id.popMenuCPtv);
        popMenuAGtv = (TextView) contentView.findViewById(R.id.popMenuAGtv);
        popMenuKYtv = (TextView) contentView.findViewById(R.id.popMenuKYtv);
        popMenuFFtv = (TextView) contentView.findViewById(R.id.popMenuFFtv);
        popMenuVGtv = (TextView) contentView.findViewById(R.id.popMenuVGtv);
        popMenuHG.setOnClickListener(listener);
        popMenuCP.setOnClickListener(listener);
        popMenuAG.setOnClickListener(listener);
        popMenuKY.setOnClickListener(listener);
        popMenuFF.setOnClickListener(listener);
        popMenuVG.setOnClickListener(listener);
       // if(!Check.isNull(popMenuHGtv)&&!Check.isNull(popMenuCPtv)&&!Check.isNull(popMenuAGtv)){
            switch (from){
                case "hg":
                    popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                    popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuHGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                    popMenuCPiv.setBackgroundResource(0);
                    popMenuAGiv.setBackgroundResource(0);
                    popMenuKYiv.setBackgroundResource(0);
                    popMenuFFiv.setBackgroundResource(0);
                    popMenuVGiv.setBackgroundResource(0);
                    popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                    popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    break;
                case "cp":
                    popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                    popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuHGiv.setBackgroundResource(0);
                    popMenuCPiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                    popMenuAGiv.setBackgroundResource(0);
                    popMenuKYiv.setBackgroundResource(0);
                    popMenuFFiv.setBackgroundResource(0);
                    popMenuVGiv.setBackgroundResource(0);
                    popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_hight));
                    popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    break;
                case "ag":
                    popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                    popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuHGiv.setBackgroundResource(0);
                    popMenuCPiv.setBackgroundResource(0);
                    popMenuAGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                    popMenuKYiv.setBackgroundResource(0);
                    popMenuFFiv.setBackgroundResource(0);
                    popMenuVGiv.setBackgroundResource(0);
                    popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                    popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
					popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    break;
                case "ky":
                    popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                    popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuHGiv.setBackgroundResource(0);
                    popMenuCPiv.setBackgroundResource(0);
                    popMenuAGiv.setBackgroundResource(0);
                    popMenuKYiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                    popMenuFFiv.setBackgroundResource(0);
                    popMenuVGiv.setBackgroundResource(0);
                    popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_hight));
                    popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    break;
                case "ff":
                    popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                    popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuHGiv.setBackgroundResource(0);
                    popMenuCPiv.setBackgroundResource(0);
                    popMenuAGiv.setBackgroundResource(0);
                    popMenuKYiv.setBackgroundResource(0);
                    popMenuFFiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                    popMenuVGiv.setBackgroundResource(0);
                    popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_hight));
                    popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    break;
                case "vg":
                    popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                    popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                    popMenuHGiv.setBackgroundResource(0);
                    popMenuCPiv.setBackgroundResource(0);
                    popMenuAGiv.setBackgroundResource(0);
                    popMenuKYiv.setBackgroundResource(0);
                    popMenuFFiv.setBackgroundResource(0);
                    popMenuVGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                    popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                    popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                    break;
            }

    }

}
