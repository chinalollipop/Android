package com.hgapp.m8.personpage.balancetransfer;

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
import com.hgapp.m8.HGApplication;
import com.hgapp.m8.Injections;
import com.hgapp.m8.R;
import com.hgapp.m8.base.HGBaseFragment;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.common.adapters.AutoSizeAdapter;
import com.hgapp.m8.common.util.ACache;
import com.hgapp.m8.common.util.HGConstant;
import com.hgapp.m8.common.widgets.CustomPopWindow;
import com.hgapp.m8.common.widgets.NTitleBar;
import com.hgapp.m8.data.BalanceTransferData;
import com.hgapp.m8.data.BetRecordResult;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.hgapp.m8.data.KYBalanceResult;
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
    @BindView(R.id.BalanceTransferRefresh)
    TextView BalanceTransferRefresh;
    @BindView(R.id.BalanceTransferOneBack)
    TextView BalanceTransferOneBack;
    @BindView(R.id.tvBalanceTransferIn)
    TextView tvBalanceTransferIn;
    @BindView(R.id.tvBalanceTransferOut)
    TextView tvBalanceTransferOut;
    @BindView(R.id.BalanceTransferTY)
    TextView BalanceTransferTY;
    @BindView(R.id.BalanceTransferKL)
    TextView BalanceTransferKL;
    @BindView(R.id.BalanceTransferKLOneBack)
    TextView BalanceTransferKLOneBack;
    @BindView(R.id.BalanceTransferCPOneBack)
    TextView BalanceTransferCPOneBack;
    @BindView(R.id.BalanceTransferAGOneBack)
    TextView BalanceTransferAGOneBack;
    @BindView(R.id.BalanceTransferKYOneBack)
    TextView BalanceTransferKYOneBack;
    @BindView(R.id.BalanceTransferLYOneBack)
    TextView BalanceTransferLYOneBack;
    @BindView(R.id.BalanceTransferVGOneBack)
    TextView BalanceTransferVGOneBack;
    @BindView(R.id.BalanceTransferMGOneBack)
    TextView BalanceTransferMGOneBack;
    @BindView(R.id.BalanceTransferFYOneBack)
    TextView BalanceTransferFYOneBack;
    @BindView(R.id.BalanceTransferOGOneBack)
    TextView BalanceTransferOGOneBack;
    @BindView(R.id.BalanceTransferCQOneBack)
    TextView BalanceTransferCQOneBack;
    @BindView(R.id.BalanceTransferMWOneBack)
    TextView BalanceTransferMWOneBack;
    @BindView(R.id.BalanceTransferFGOneBack)
    TextView BalanceTransferFGOneBack;
    @BindView(R.id.BalanceTransferBBINOneBack)
    TextView BalanceTransferBBINOneBack;
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
    @BindView(R.id.BalanceTransferBBIN)
    TextView BalanceTransferBBIN;
    @BindView(R.id.BalanceTransferDS)
    TextView BalanceTransferDS;
    @BindView(R.id.etBalanceTransferMoney)
    EditText etBalanceTransferMoney;
    private BalanceTransferContract.Presenter presenter;
    LinearLayout popMenuHG,popMenuCP,popMenuAG,popMenuKY,popMenuFF,popMenuVG,popMenuLY,popMenuMG,popMenuAviaG,popMenuOG,popMenuCQ,popMenuMW;
    TextView popMenuHGtv,popMenuCPtv,popMenuAGtv,popMenuKYtv,popMenuFFtv,popMenuVGtv,popMenuLYtv,popMenuMGtv,popMenuAviaGtv,popMenuOGtv,popMenuCQtv,popMenuMWtv;
    ImageView popMenuHGiv,popMenuCPiv,popMenuAGiv,popMenuKYiv,popMenuFFiv,popMenuVGiv,popMenuLYiv,popMenuMGiv,popMenuAviaGiv,popMenuOGiv,popMenuCQiv,popMenuMWiv;
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

        gtypeList.add(new BalanceTransferData("1","体育余额","hg"));
        gtypeList.add(new BalanceTransferData("2","彩票余额","cp"));
        gtypeList.add(new BalanceTransferData("3","AG余额","ag"));
        gtypeList.add(new BalanceTransferData("4","开元棋牌","ky"));
        gtypeList.add(new BalanceTransferData("5","快乐棋牌","kl"));
        gtypeList.add(new BalanceTransferData("6","VG棋牌","vg"));
        gtypeList.add(new BalanceTransferData("7","乐游棋牌","ly"));
        gtypeList.add(new BalanceTransferData("8","MG电子","mg"));
        gtypeList.add(new BalanceTransferData("9","泛亚电竞","avia"));
        gtypeList.add(new BalanceTransferData("10","OG视讯","og"));
        gtypeList.add(new BalanceTransferData("11","CQ9电子","cq"));
        gtypeList.add(new BalanceTransferData("12","MW电子","mw"));
        gtypeList.add(new BalanceTransferData("13","FG电子","fg"));
        gtypeList.add(new BalanceTransferData("14","BBIN视讯","bbin"));
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


    private void onCPBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransferCP("","","hg","gmcp",BalanceTransferCPTextInt+"");
        }
    }

    private void onAGBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransfer("","hg","ag",BalanceTransferCPTextInt+"");
        }
    }

    private void onKYBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransferKY("","hg","ky",BalanceTransferCPTextInt+"");
        }
    }

    private void onLYBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransferLY("","hg","ly",BalanceTransferCPTextInt+"");
        }
    }

    private void onVGBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransferVG("","hg","vg",BalanceTransferCPTextInt+"");
        }
    }

    private void onKLBack(){
        String BalanceTransferKLText = BalanceTransferTY.getText().toString();
        int BalanceTransferKLTextInt =  Integer.parseInt(BalanceTransferKLText.split("\\.")[0]);
        if(BalanceTransferKLTextInt>0){
            presenter.postBanalceTransferHG("","hg","kl",BalanceTransferKLTextInt+"");
        }
    }

    private void onMGBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransferMG("","hg","mg",BalanceTransferCPTextInt+"");
        }
    }


    private void onFYBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransferAG("","hg","avia",BalanceTransferCPTextInt+"");
        }
    }

    private void onOGBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransferOG("","hg","og",BalanceTransferCPTextInt+"");
        }
    }

    private void onCQBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransferCQ("","hg","cq",BalanceTransferCPTextInt+"");
        }
    }

    private void onMWBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransferMW("","hg","mw",BalanceTransferCPTextInt+"");
        }
    }

    private void onFGBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransferFG("","hg","fg",BalanceTransferCPTextInt+"");
        }
    }

    private void onBBINBack(){
        String BalanceTransferCPText = BalanceTransferTY.getText().toString();
        int BalanceTransferCPTextInt =  Integer.parseInt(BalanceTransferCPText.split("\\.")[0]);
        if(BalanceTransferCPTextInt>0){
            presenter.postBanalceTransferBBIN("","hg","bbin",BalanceTransferCPTextInt+"");
        }
    }

    private void onOneBack(){
        if(Integer.parseInt(BalanceTransferAG.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransfer("","ag","hg",Integer.parseInt(BalanceTransferAG.getText().toString().split("\\.")[0])+"");
        }

        if(Integer.parseInt(BalanceTransferCP.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferCP("","","gmcp","hg",Integer.parseInt(BalanceTransferCP.getText().toString().split("\\.")[0])+"");
        }
        if(Integer.parseInt(BalanceTransferKY.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferKY("","ky","hg",Integer.parseInt(BalanceTransferKY.getText().toString().split("\\.")[0])+"");
        }
        if(Integer.parseInt(BalanceTransferLY.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferLY("","ly","hg",Integer.parseInt(BalanceTransferLY.getText().toString().split("\\.")[0])+"");
        }
        if(Integer.parseInt(BalanceTransferVG.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferVG("","vg","hg",Integer.parseInt(BalanceTransferVG.getText().toString().split("\\.")[0])+"");
        }
        if(Integer.parseInt(BalanceTransferKL.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferHG("","kl","hg",Integer.parseInt(BalanceTransferKL.getText().toString().split("\\.")[0])+"");
        }
        if(Integer.parseInt(BalanceTransferMG.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferMG("","mg","hg",Integer.parseInt(BalanceTransferMG.getText().toString().split("\\.")[0])+"");
        }
        if(Integer.parseInt(BalanceTransferFY.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferAG("","avia","hg",Integer.parseInt(BalanceTransferFY.getText().toString().split("\\.")[0])+"");
        }
        if(Integer.parseInt(BalanceTransferOG.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferOG("","og","hg",Integer.parseInt(BalanceTransferOG.getText().toString().split("\\.")[0])+"");
        }
        if(Integer.parseInt(BalanceTransferCQ.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferCQ("","cq","hg",Integer.parseInt(BalanceTransferCQ.getText().toString().split("\\.")[0])+"");
        }
        if(Integer.parseInt(BalanceTransferFG.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferFG("","fg","hg",Integer.parseInt(BalanceTransferFG.getText().toString().split("\\.")[0])+"");
        }
        if(Integer.parseInt(BalanceTransferBBIN.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferBBIN("","bbin","hg",Integer.parseInt(BalanceTransferBBIN.getText().toString().split("\\.")[0])+"");
        }
        if(Integer.parseInt(BalanceTransferMW.getText().toString().split("\\.")[0])>0){
            presenter.postBanalceTransferMW("","mw","hg",Integer.parseInt(BalanceTransferMW.getText().toString().split("\\.")[0])+"");
        }
    }

    private void initBalance() {
        //presenter.postPersonBalanceTY("","");
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
        presenter.postPersonBalanceBBIN("","");
    }

    class FlowBalanceTransferAdapter extends com.hgapp.m8.common.adapters.AutoSizeRVAdapter<String>{

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
        BalanceTransferTY.setText(personBalance.getBalance_hg());
    }

    @Override
    public void postPersonBalanceCPResult(KYBalanceResult personBalance) {
        if(!Check.isNull(personBalance.getGmcp_balance())) {
            BalanceTransferCP.setText(personBalance.getGmcp_balance());
        }
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceKYResult(KYBalanceResult personBalance) {
        BalanceTransferKY.setText(personBalance.getKy_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceHGResult(KYBalanceResult personBalance) {
        BalanceTransferKL.setText(personBalance.getFf_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceVGResult(KYBalanceResult personBalance) {
        BalanceTransferVG.setText(personBalance.getVg_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceLYResult(KYBalanceResult personBalance) {
        BalanceTransferLY.setText(personBalance.getLy_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceMGResult(KYBalanceResult personBalance) {
        BalanceTransferMG.setText(personBalance.getMg_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceAGResult(KYBalanceResult personBalance) {
        BalanceTransferFY.setText(personBalance.getAvia_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceOGResult(KYBalanceResult personBalance) {
        BalanceTransferOG.setText(personBalance.getOg_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceCQResult(KYBalanceResult personBalance) {
        BalanceTransferCQ.setText(personBalance.getCq_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceMWResult(KYBalanceResult personBalance) {
        BalanceTransferMW.setText(personBalance.getMw_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceFGResult(KYBalanceResult personBalance) {
        BalanceTransferFG.setText(personBalance.getFg_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
    }

    @Override
    public void postPersonBalanceBBINResult(KYBalanceResult personBalance) {
        BalanceTransferBBIN.setText(personBalance.getBbin_balance());
        backTitleBalanceTransfer.setMoreText(personBalance.getHg_balance());
        BalanceTransferTY.setText(personBalance.getHg_balance());
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
            presenter.postBanalceTransferCP("","fundLimitTrans","hg","cp",transferMoney);
        }else if(from.equals("cp")&&to.equals("hg")){
            presenter.postBanalceTransferCP("","fundLimitTrans","cp","hg",transferMoney);
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
        }else if(from.equals("kl")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferHG("","kl","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("kl")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferHG("","hg","kl",transferMoney);
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
        }else if(from.equals("bbin")&&to.equals("hg")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferBBIN("","bbin","hg",transferMoney);
        }else if(from.equals("hg")&&to.equals("bbin")){
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                showMessage("非常抱歉，请您注册真实会员！");
                return;
            }
            presenter.postBanalceTransferBBIN("","hg","bbin",transferMoney);
        }else {
            showMessage("转账方式不支持");
        }

    }

    @OnClick({R.id.BalanceTransferCPOneBack,R.id.BalanceTransferAGOneBack,R.id.BalanceTransferKYOneBack,R.id.BalanceTransferLYOneBack,R.id.BalanceTransferVGOneBack,
            R.id.BalanceTransferMGOneBack,R.id.BalanceTransferFYOneBack,R.id.BalanceTransferOGOneBack,R.id.BalanceTransferCQOneBack,R.id.BalanceTransferMWOneBack,
            R.id.BalanceTransferFGOneBack,R.id.BalanceTransferBBINOneBack,R.id.BalanceTransferKLOneBack,
            R.id.BalanceTransferRefresh,R.id.BalanceTransferOneBack,R.id.btnBalanceTrensferSubmit,R.id.tvBalanceTransferIn, R.id.tvBalanceTransferOut})
    public void onViewClicked(View view) {
        try {
            switch (view.getId()) {
                case R.id.BalanceTransferCPOneBack:
                    onCPBack();
                    break;
                case R.id.BalanceTransferAGOneBack:
                    onAGBack();
                    break;
                case R.id.BalanceTransferKYOneBack:
                    onKYBack();
                    break;
                case R.id.BalanceTransferLYOneBack:
                    onLYBack();
                    break;
                case R.id.BalanceTransferVGOneBack:
                    onVGBack();
                    break;
                case R.id.BalanceTransferKLOneBack:
                    onKLBack();
                    break;
                case R.id.BalanceTransferMGOneBack:
                    onMGBack();
                    break;
                case R.id.BalanceTransferFYOneBack:
                    onFYBack();
                    break;
                case R.id.BalanceTransferOGOneBack:
                    onOGBack();
                    break;
                case R.id.BalanceTransferCQOneBack:
                    onCQBack();
                    break;
                case R.id.BalanceTransferMWOneBack:
                    onMWBack();
                    break;
                case R.id.BalanceTransferFGOneBack:
                    onFGBack();
                    break;
                case R.id.BalanceTransferBBINOneBack:
                    onBBINBack();
                    break;
                case R.id.BalanceTransferRefresh:
                    initBalance();
                    break;
                case R.id.BalanceTransferOneBack:
                    onOneBack();
                    break;
                case R.id.btnBalanceTrensferSubmit:
                    onCheckTransferMoney("");
                    break;
                case R.id.tvBalanceTransferOut:
                    //showPopMenuOut();
                    gtypeOptionsPickerOut.show();
                    break;
                case R.id.tvBalanceTransferIn:
                    gtypeOptionsPickerIn.show();
                    //showPopMenuIn();
                    break;
            }
        }catch (Exception e){
            GameLog.log("转账的异常信息是："+e);
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
                        to = "kl";
                        tvBalanceTransferIn.setText("快乐棋牌");
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

                break;
            case "kl":
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
                        from = "kl";
                        tvBalanceTransferOut.setText("快乐棋牌");
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

                break;
            case "kl":
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

                break;
        }

    }

}
