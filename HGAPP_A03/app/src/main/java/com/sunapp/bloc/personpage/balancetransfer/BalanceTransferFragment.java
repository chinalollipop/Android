package com.sunapp.bloc.personpage.balancetransfer;

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

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.sunapp.bloc.HGApplication;
import com.sunapp.bloc.Injections;
import com.sunapp.bloc.R;
import com.sunapp.bloc.base.HGBaseFragment;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.common.adapters.AutoSizeAdapter;
import com.sunapp.bloc.common.util.ACache;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.widgets.CustomPopWindow;
import com.sunapp.bloc.common.widgets.NTitleBar;
import com.sunapp.bloc.data.BalanceTransferData;
import com.sunapp.bloc.data.BetRecordResult;
import com.sunapp.bloc.data.KYBalanceResult;
import com.sunapp.common.util.Check;
import com.sunapp.common.util.GameLog;
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
    @BindView(R.id.BalanceTransferTY)
    TextView BalanceTransferTY;
    @BindView(R.id.BalanceTransferCP)
    TextView BalanceTransferCP;
    @BindView(R.id.BalanceTransferAG)
    TextView BalanceTransferAG;
    @BindView(R.id.BalanceTransferKY)
    TextView BalanceTransferKY;
    @BindView(R.id.BalanceTransferLY)
    TextView BalanceTransferLY;
    @BindView(R.id.BalanceTransferVG)
    TextView BalanceTransferVG;
    @BindView(R.id.BalanceTransferMG)
    TextView BalanceTransferMG;
    @BindView(R.id.BalanceTransferFY)
    TextView BalanceTransferFY;
    @BindView(R.id.BalanceTransferOG)
    TextView BalanceTransferOG;
    @BindView(R.id.BalanceTransferCQ)
    TextView BalanceTransferCQ;
    @BindView(R.id.BalanceTransferMW)
    TextView BalanceTransferMW;
    @BindView(R.id.BalanceTransferFG)
    TextView BalanceTransferFG;
    @BindView(R.id.etBalanceTransferMoney)
    EditText etBalanceTransferMoney;
    private BalanceTransferContract.Presenter presenter;
    LinearLayout popMenuHG,popMenuCP,popMenuAG,popMenuKY,popMenuFF,popMenuVG,popMenuLY,popMenuMG,popMenuAviaG,popMenuOG,popMenuCQ,popMenuMW,popMenuFG;
    TextView popMenuHGtv,popMenuCPtv,popMenuAGtv,popMenuKYtv,popMenuFFtv,popMenuVGtv,popMenuLYtv,popMenuMGtv,popMenuAviaGtv,popMenuOGtv,popMenuCQtv,popMenuMWtv,popMenuFGtv;
    ImageView popMenuHGiv,popMenuCPiv,popMenuAGiv,popMenuKYiv,popMenuFFiv,popMenuVGiv,popMenuLYiv,popMenuMGiv,popMenuAviaGiv,popMenuOGiv,popMenuCQiv,popMenuMWiv,popMenuFGiv;
    private String from ="hg";
    private String to ="hg";
    OptionsPickerView gtypeOptionsPickerIn, gtypeOptionsPickerOut;
    private CustomPopWindow mCustomPopWindowIn;
    private CustomPopWindow mCustomPopWindowOut;
    private String typeArgs;
    static List<String> searchRecordsArrayList  = new ArrayList<>();
    static  List<PopTransferEvent> itemPopTransferList  = new ArrayList<PopTransferEvent>();
    static List<BalanceTransferData> gtypeList  = new ArrayList<BalanceTransferData>();
    static {
        itemPopTransferList.add(new PopTransferEvent(true,"体育余额"));
        itemPopTransferList.add(new PopTransferEvent(false,"彩票余额"));
        itemPopTransferList.add(new PopTransferEvent(false,"AG余额"));

        searchRecordsArrayList.add("100");
        searchRecordsArrayList.add("500");
        searchRecordsArrayList.add("1000");
        searchRecordsArrayList.add("2000");
        searchRecordsArrayList.add("5000");

        gtypeList.add(new BalanceTransferData("0","体育中心","sc"));
        gtypeList.add(new BalanceTransferData("1","体育平台","hg"));
        gtypeList.add(new BalanceTransferData("2","彩票平台","cp"));
        gtypeList.add(new BalanceTransferData("3","AG平台","ag"));
        gtypeList.add(new BalanceTransferData("4","开元棋牌","ky"));
        gtypeList.add(new BalanceTransferData("5","皇冠棋牌","ff"));
        gtypeList.add(new BalanceTransferData("6","VG棋牌","vg"));
        gtypeList.add(new BalanceTransferData("7","乐游棋牌","ly"));
        gtypeList.add(new BalanceTransferData("8","MG电子","mg"));
        gtypeList.add(new BalanceTransferData("9","泛亚电竞","avia"));
        gtypeList.add(new BalanceTransferData("10","OG视讯","og"));
        gtypeList.add(new BalanceTransferData("11","CQ9电子","cq"));
        gtypeList.add(new BalanceTransferData("12","MW电子","mw"));
        gtypeList.add(new BalanceTransferData("13","FG电子","fg"));
    }

    public static BalanceTransferFragment newInstance(String type) {
        BalanceTransferFragment fragment = new BalanceTransferFragment();
        Bundle args = new Bundle();
        args.putString(TYPE, type);
        fragment.setArguments(args);
        Injections.inject(null, (BalanceTransferContract.View)fragment);
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
        initBalance();
        backTitleBalanceTransfer.setMoreText(typeArgs);
        backTitleBalanceTransfer.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });

        gtypeOptionsPickerIn = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                to  = gtypeList.get(options1).getEnName();
                tvBalanceTransferIn.setText(gtypeList.get(options1).getCnName());
                GameLog.log("去那里："+to);
            }
        }).build();
        gtypeOptionsPickerIn.setPicker(gtypeList);

        gtypeOptionsPickerOut = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                from  = gtypeList.get(options1).getEnName();
                tvBalanceTransferOut.setText(gtypeList.get(options1).getCnName());
                GameLog.log("来自那里："+from);
            }
        }).build();
        gtypeOptionsPickerOut.setPicker(gtypeList);

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

    private void initBalance() {
        presenter.postPersonBalanceTY("","");
        presenter.postPersonBalance("","");
        presenter.postPersonBalanceCP("","");
        presenter.postPersonBalanceKY("","");
        presenter.postPersonBalanceHG("","");
        presenter.postPersonBalanceVG("","");
        presenter.postPersonBalanceLY("","");
        presenter.postPersonBalanceMG("","");
        presenter.postPersonBalanceAG("","");
        presenter.postPersonBalanceOG("","");
        presenter.postPersonBalanceCQ("","");
        presenter.postPersonBalanceMW("","");
        presenter.postPersonBalanceFG("","");
    }


    class FlowBalanceTransferAdapter extends com.sunapp.bloc.common.adapters.AutoSizeRVAdapter<String>{

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
    public void postPersonBalanceTYResult(KYBalanceResult personBalance) {
        BalanceTransferTY.setText(personBalance.getSc_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceResult(KYBalanceResult personBalance) {
        BalanceTransferAG.setText(personBalance.getBalance_ag());
        backTitleBalanceTransfer.setMoreText(personBalance.getBalance_hg());
    }

    @Override
    public void postPersonBalanceCPResult(KYBalanceResult personBalance) {
        BalanceTransferCP.setText(personBalance.getGmcp_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceKYResult(KYBalanceResult personBalance) {
        BalanceTransferKY.setText(personBalance.getKy_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceHGResult(KYBalanceResult personBalance) {
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceVGResult(KYBalanceResult personBalance) {
        BalanceTransferVG.setText(personBalance.getVg_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceLYResult(KYBalanceResult personBalance) {
        BalanceTransferLY.setText(personBalance.getLy_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceMGResult(KYBalanceResult personBalance) {
        BalanceTransferMG.setText(personBalance.getMg_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceAGResult(KYBalanceResult personBalance) {
        BalanceTransferFY.setText(personBalance.getAvia_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceOGResult(KYBalanceResult personBalance) {
        BalanceTransferOG.setText(personBalance.getOg_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceCQResult(KYBalanceResult personBalance) {
        BalanceTransferCQ.setText(personBalance.getCq_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceMWResult(KYBalanceResult personBalance) {
        BalanceTransferMW.setText(personBalance.getMw_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceFGResult(KYBalanceResult personBalance) {
        BalanceTransferFG.setText(personBalance.getFg_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        //pop();
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
        if(from.equals("hg")&&to.equals("sc")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferTY("","hg","sc",transferMoney);
        }else if(from.equals("sc")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferTY("","sc","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("ag")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransfer("","hg","ag",transferMoney);
        }else if(from.equals("ag")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransfer("","ag","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("cp")){
            presenter.postBanalceTransferCP("","fundLimitTrans","hg","gmcp",transferMoney);
        }else if(from.equals("cp")&&to.equals("hg")){
            presenter.postBanalceTransferCP("","fundLimitTrans","gmcp","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("ky")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferKY("","hg","ky",transferMoney);
        }else if(from.equals("hg")&&to.equals("ly")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferLY("","hg","ly",transferMoney);
        }else if(from.equals("ky")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferKY("","ky","hg",transferMoney);
        }else if(from.equals("ff")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferHG("","ff","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("ff")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferHG("","hg","ff",transferMoney);
        }else if(from.equals("vg")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferVG("","vg","hg",transferMoney);
        }else if(from.equals("ly")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferLY("","ly","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("vg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferVG("","hg","vg",transferMoney);
        }else if(from.equals("mg")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferMG("","mg","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("mg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferMG("","hg","mg",transferMoney);
        }else if(from.equals("avia")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferAG("","avia","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("avia")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferAG("","hg","avia",transferMoney);
        }else if(from.equals("og")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferOG("","og","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("og")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferOG("","hg","og",transferMoney);
        }else if(from.equals("cq")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferCQ("","cq","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("cq")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferCQ("","hg","cq",transferMoney);
        }else if(from.equals("mw")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferMW("","mw","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("mw")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferMW("","hg","mw",transferMoney);
        }else if(from.equals("fg")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferFG("","fg","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("fg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferFG("","hg","fg",transferMoney);
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
                //showPopMenuOut();
                gtypeOptionsPickerOut.show();
                break;
            case R.id.tvBalanceTransferIn:
                //showPopMenuIn();
                gtypeOptionsPickerIn.show();
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
                    case R.id.popMenuLY:
                        to = "ly";
                        tvBalanceTransferIn.setText("乐游棋牌");
                        showContent = "In 点击 Item菜单7";
                        break;
                    case R.id.popMenuMG:
                        to = "mg";
                        tvBalanceTransferIn.setText("MG电子");
                        showContent = "In 点击 Item菜单8";
                        break;
                    case R.id.popMenuAviaG:
                        to = "avia";
                        tvBalanceTransferIn.setText("泛亚电竞");
                        showContent = "In 点击 Item菜单8";
                        break;
                    case R.id.popMenuOG:
                        to = "og";
                        tvBalanceTransferIn.setText("OG视讯");
                        showContent = "In 点击 Item菜单8";
                        break;
                    case R.id.popMenuCQ:
                        to = "cq";
                        tvBalanceTransferIn.setText("CQ9电子");
                        showContent = "In 点击 Item菜单8";
                        break;
                    case R.id.popMenuMW:
                        to = "mw";
                        tvBalanceTransferIn.setText("MW电子");
                        showContent = "In 点击 Item菜单8";
                        break;
                    case R.id.popMenuFG:
                        to = "fg";
                        tvBalanceTransferIn.setText("FG电子");
                        showContent = "In 点击 Item菜单8";
                        break;
                }
                GameLog.log("转入："+showContent);
                //showMessage(showContent);
                mCustomPopWindowIn.dissmiss();
            }
        };
        popMenuHG = (LinearLayout) contentView.findViewById(R.id.popMenuHG);
        popMenuVG = (LinearLayout) contentView.findViewById(R.id.popMenuVG);
        popMenuLY = (LinearLayout) contentView.findViewById(R.id.popMenuLY);
        popMenuMG = (LinearLayout) contentView.findViewById(R.id.popMenuMG);
        popMenuAviaG = (LinearLayout) contentView.findViewById(R.id.popMenuAviaG);
        popMenuOG = (LinearLayout) contentView.findViewById(R.id.popMenuOG);
        popMenuCQ = (LinearLayout) contentView.findViewById(R.id.popMenuCQ);
        popMenuMW = (LinearLayout) contentView.findViewById(R.id.popMenuMW);
        popMenuFG = (LinearLayout) contentView.findViewById(R.id.popMenuFG);
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
        popMenuLYiv = (ImageView) contentView.findViewById(R.id.popMenuLYiv);
        popMenuMGiv = (ImageView) contentView.findViewById(R.id.popMenuMGiv);
        popMenuAviaGiv = (ImageView) contentView.findViewById(R.id.popMenuAviaGiv);
        popMenuOGiv = (ImageView) contentView.findViewById(R.id.popMenuOGiv);
        popMenuCQiv = (ImageView) contentView.findViewById(R.id.popMenuCQiv);
        popMenuMWiv = (ImageView) contentView.findViewById(R.id.popMenuMWiv);
        popMenuFGiv = (ImageView) contentView.findViewById(R.id.popMenuFGiv);
        popMenuHGtv = (TextView) contentView.findViewById(R.id.popMenuHGtv);
        popMenuCPtv = (TextView) contentView.findViewById(R.id.popMenuCPtv);
        popMenuAGtv = (TextView) contentView.findViewById(R.id.popMenuAGtv);
        popMenuKYtv = (TextView) contentView.findViewById(R.id.popMenuKYtv);
        popMenuFFtv = (TextView) contentView.findViewById(R.id.popMenuFFtv);
        popMenuVGtv = (TextView) contentView.findViewById(R.id.popMenuVGtv);
        popMenuLYtv = (TextView) contentView.findViewById(R.id.popMenuLYtv);
        popMenuMGtv = (TextView) contentView.findViewById(R.id.popMenuMGtv);
        popMenuAviaGtv = (TextView) contentView.findViewById(R.id.popMenuAviaGtv);
        popMenuOGtv = (TextView) contentView.findViewById(R.id.popMenuOGtv);
        popMenuCQtv = (TextView) contentView.findViewById(R.id.popMenuCQtv);
        popMenuMWtv = (TextView) contentView.findViewById(R.id.popMenuMWtv);
        popMenuFGtv = (TextView) contentView.findViewById(R.id.popMenuFGtv);
        popMenuHG.setOnClickListener(listener);
        popMenuCP.setOnClickListener(listener);
        popMenuAG.setOnClickListener(listener);
        popMenuKY.setOnClickListener(listener);
        popMenuFF.setOnClickListener(listener);
        popMenuVG.setOnClickListener(listener);
        popMenuLY.setOnClickListener(listener);
        popMenuMG.setOnClickListener(listener);
        popMenuAviaG.setOnClickListener(listener);
        popMenuOG.setOnClickListener(listener);
        popMenuCQ.setOnClickListener(listener);
        popMenuMW.setOnClickListener(listener);
        popMenuFG.setOnClickListener(listener);
        // if(!Check.isNull(popMenuHGtv)&&!Check.isNull(popMenuCPtv)&&!Check.isNull(popMenuAGtv)){
        switch (to){
            case "hg":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuHGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);
                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "cp":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);
                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "ag":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "ky":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "ff":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "vg":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "ly":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "mg":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "avia":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "og":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "cq":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "mw":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "fg":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_hight));
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
                        showContent = "Out 点击 Item菜单6";
                        break;
                    case R.id.popMenuLY:
                        from = "ly";
                        tvBalanceTransferOut.setText("乐游棋牌");
                        showContent = "Out 点击 Item菜单7";
                        break;
                    case R.id.popMenuMG:
                        from = "mg";
                        tvBalanceTransferOut.setText("MG电子");
                        showContent = "Out 点击 Item菜单8";
                        break;
                    case R.id.popMenuAviaG:
                        from = "avia";
                        tvBalanceTransferOut.setText("泛亚电竞");
                        showContent = "Out 点击 Item菜单9";
                        break;
                    case R.id.popMenuOG:
                        from = "og";
                        tvBalanceTransferOut.setText("OG视讯");
                        showContent = "Out 点击 Item菜单10";
                        break;
                    case R.id.popMenuCQ:
                        from = "cq";
                        tvBalanceTransferOut.setText("CQ9电子");
                        showContent = "Out 点击 Item菜单10";
                        break;
                    case R.id.popMenuMW:
                        from = "mw";
                        tvBalanceTransferOut.setText("MW电子");
                        showContent = "Out 点击 Item菜单10";
                        break;
                    case R.id.popMenuFG:
                        from = "fg";
                        tvBalanceTransferOut.setText("FG电子");
                        showContent = "Out 点击 Item菜单10";
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
        popMenuLY = (LinearLayout) contentView.findViewById(R.id.popMenuLY);
        popMenuMG = (LinearLayout) contentView.findViewById(R.id.popMenuMG);
        popMenuAviaG = (LinearLayout) contentView.findViewById(R.id.popMenuAviaG);
        popMenuOG = (LinearLayout) contentView.findViewById(R.id.popMenuOG);
        popMenuCQ = (LinearLayout) contentView.findViewById(R.id.popMenuCQ);
        popMenuMW = (LinearLayout) contentView.findViewById(R.id.popMenuMW);
        popMenuFG = (LinearLayout) contentView.findViewById(R.id.popMenuFG);
        popMenuHGiv = (ImageView) contentView.findViewById(R.id.popMenuHGiv);
        popMenuCPiv = (ImageView) contentView.findViewById(R.id.popMenuCPiv);
        popMenuAGiv = (ImageView) contentView.findViewById(R.id.popMenuAGiv);
        popMenuKYiv = (ImageView) contentView.findViewById(R.id.popMenuKYiv);
        popMenuFFiv = (ImageView) contentView.findViewById(R.id.popMenuFFiv);
        popMenuVGiv = (ImageView) contentView.findViewById(R.id.popMenuVGiv);
        popMenuLYiv = (ImageView) contentView.findViewById(R.id.popMenuLYiv);
        popMenuMGiv = (ImageView) contentView.findViewById(R.id.popMenuMGiv);
        popMenuAviaGiv = (ImageView) contentView.findViewById(R.id.popMenuAviaGiv);
        popMenuOGiv = (ImageView) contentView.findViewById(R.id.popMenuOGiv);
        popMenuCQiv = (ImageView) contentView.findViewById(R.id.popMenuCQiv);
        popMenuMWiv = (ImageView) contentView.findViewById(R.id.popMenuMWiv);
        popMenuFGiv = (ImageView) contentView.findViewById(R.id.popMenuFGiv);
        popMenuHGtv = (TextView) contentView.findViewById(R.id.popMenuHGtv);
        popMenuCPtv = (TextView) contentView.findViewById(R.id.popMenuCPtv);
        popMenuAGtv = (TextView) contentView.findViewById(R.id.popMenuAGtv);
        popMenuKYtv = (TextView) contentView.findViewById(R.id.popMenuKYtv);
        popMenuFFtv = (TextView) contentView.findViewById(R.id.popMenuFFtv);
        popMenuVGtv = (TextView) contentView.findViewById(R.id.popMenuVGtv);
        popMenuLYtv = (TextView) contentView.findViewById(R.id.popMenuLYtv);
        popMenuMGtv = (TextView) contentView.findViewById(R.id.popMenuMGtv);
        popMenuAviaGtv = (TextView) contentView.findViewById(R.id.popMenuAviaGtv);
        popMenuOGtv = (TextView) contentView.findViewById(R.id.popMenuOGtv);
        popMenuCQtv = (TextView) contentView.findViewById(R.id.popMenuCQtv);
        popMenuMWtv = (TextView) contentView.findViewById(R.id.popMenuMWtv);
        popMenuFGtv = (TextView) contentView.findViewById(R.id.popMenuFGtv);
        popMenuHG.setOnClickListener(listener);
        popMenuCP.setOnClickListener(listener);
        popMenuAG.setOnClickListener(listener);
        popMenuKY.setOnClickListener(listener);
        popMenuFF.setOnClickListener(listener);
        popMenuVG.setOnClickListener(listener);
        popMenuLY.setOnClickListener(listener);
        popMenuMG.setOnClickListener(listener);
        popMenuAviaG.setOnClickListener(listener);
        popMenuOG.setOnClickListener(listener);
        popMenuCQ.setOnClickListener(listener);
        popMenuMW.setOnClickListener(listener);
        popMenuFG.setOnClickListener(listener);
        // if(!Check.isNull(popMenuHGtv)&&!Check.isNull(popMenuCPtv)&&!Check.isNull(popMenuAGtv)){
        switch (from){
            case "hg":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "cp":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "ag":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "ky":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "ff":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "vg":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "ly":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "mg":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "avia":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "og":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "cq":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "mw":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                popMenuFGiv.setBackgroundResource(0);

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_hight));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_normal));

                break;
            case "fg":
                popMenuHG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCP.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuKY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFF.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuVG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuLY.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuAviaG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuOG.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuCQ.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuMW.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuFG.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));

                popMenuHGiv.setBackgroundResource(0);
                popMenuCPiv.setBackgroundResource(0);
                popMenuAGiv.setBackgroundResource(0);
                popMenuKYiv.setBackgroundResource(0);
                popMenuFFiv.setBackgroundResource(0);
                popMenuVGiv.setBackgroundResource(0);
                popMenuLYiv.setBackgroundResource(0);
                popMenuMGiv.setBackgroundResource(0);
                popMenuAviaGiv.setBackgroundResource(0);
                popMenuOGiv.setBackgroundResource(0);
                popMenuCQiv.setBackgroundResource(0);
                popMenuMWiv.setBackgroundResource(0);
                popMenuFGiv.setBackground(getResources().getDrawable(R.mipmap.pop_item));

                popMenuHGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCPtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuKYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFFtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuVGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuLYtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuAviaGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuOGtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuCQtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuMWtv.setTextColor(getResources().getColor(R.color.pop_normal));
                popMenuFGtv.setTextColor(getResources().getColor(R.color.pop_hight));

                break;
        }

    }

}
