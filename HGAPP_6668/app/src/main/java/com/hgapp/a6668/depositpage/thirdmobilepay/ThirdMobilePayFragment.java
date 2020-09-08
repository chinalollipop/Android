package com.hgapp.a6668.depositpage.thirdmobilepay;

import android.content.Context;
import android.os.Bundle;
import android.os.Parcelable;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.StaggeredGridLayoutManager;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.data.DepositThirdQQPayResult;
import com.hgapp.a6668.depositpage.DepositeContract;
import com.hgapp.a6668.personpage.balancetransfer.BalanceTransferFragment;
import com.hgapp.a6668.personpage.balancetransfer.PopTransferEvent;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class ThirdMobilePayFragment extends HGBaseFragment {

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.tvThirdMobilePayBack)
    NTitleBar tvThirdMobilePayBack;
    @BindView(R.id.etDepositThirdMobileMoney)
    EditText etDepositThirdMobileMoney;
    @BindView(R.id.rvBalanceTransfer)
    RecyclerView flowBalanceTransfer;
    @BindView(R.id.tvDepositThirdMobile)
    TextView tvDepositThirdMobile;
    @BindView(R.id.btnDepositThirdMobileSubmit)
    Button btnDepositThirdMobileSubmit;
    private DepositeContract.Presenter presenter;

    private String getArgParam1;
    private int getArgParam2;

    private String useId,id,url;
    OptionsPickerView gtypeOptionsPickerIn;
    ArrayList<DepositThirdQQPayResult.DataBean> dataBean;
    static List<String> searchRecordsArrayList  = new ArrayList<>();
    static {

        searchRecordsArrayList.add("100");
        searchRecordsArrayList.add("300");
        searchRecordsArrayList.add("500");
        searchRecordsArrayList.add("800");
        searchRecordsArrayList.add("1000");
        searchRecordsArrayList.add("2000");
        searchRecordsArrayList.add("3000");
        searchRecordsArrayList.add("5000");
    }
    public static ThirdMobilePayFragment newInstance(DepositThirdQQPayResult dataBean,String getArgParam1,int argParam2) {
        ThirdMobilePayFragment fragment = new ThirdMobilePayFragment();
        Bundle args = new Bundle();
        args.putParcelableArrayList(ARG_PARAM0, (ArrayList<? extends Parcelable>) dataBean.getData());
        args.putString(ARG_PARAM1,getArgParam1);
        args.putInt(ARG_PARAM2,argParam2);
//        Injections.inject(null,fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            dataBean = getArguments().getParcelableArrayList(ARG_PARAM0);
            getArgParam1 = getArguments().getString(ARG_PARAM1);
            getArgParam2 = getArguments().getInt(ARG_PARAM2);
        }
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_thirdmobilepay;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        tvThirdMobilePayBack.setMoreText(getArgParam1);
        tvThirdMobilePayBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });

        if(dataBean.size()>=2){
            tvDepositThirdMobile.setText("请点击此处选择支付渠道 ⇊▼");
        }else{
            etDepositThirdMobileMoney.setHint("大于"+dataBean.get(0).getMinCurrency()+"小于"+dataBean.get(0).getMaxCurrency());
            tvDepositThirdMobile.setText(dataBean.get(0).getTitle());
            id = dataBean.get(0).getId();
            url = dataBean.get(0).getUrl();
            useId = dataBean.get(0).getUserid();
        }

        gtypeOptionsPickerIn = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                tvDepositThirdMobile.setText(dataBean.get(options1).getTitle()+" ⇊▼");
                GameLog.log("充值方式："+dataBean.get(options1).getTitle());
                //showMessage("金额必须大于"+dataBean.get(options1).getMinCurrency()+"小于"+dataBean.get(options1).getMaxCurrency());
                etDepositThirdMobileMoney.setHint("大于"+dataBean.get(options1).getMinCurrency()+"小于"+dataBean.get(options1).getMaxCurrency());
                id = dataBean.get(options1).getId();
                url = dataBean.get(options1).getUrl();
                useId = dataBean.get(options1).getUserid();
            }
        }).build();
        gtypeOptionsPickerIn.setPicker(dataBean);
        GridLayoutManager layoutActivityManager = new GridLayoutManager(getContext(),4, OrientationHelper.VERTICAL,false);
        //RecyclerView.LayoutManager layoutActivityManager = new StaggeredGridLayoutManager(1, StaggeredGridLayoutManager.HORIZONTAL);
        flowBalanceTransfer.setLayoutManager(layoutActivityManager);

        flowBalanceTransfer.setAdapter(new FlowBalanceTransferAdapter(getContext(),R.layout.item_balance_transfer,searchRecordsArrayList));

    }

    class FlowBalanceTransferAdapter extends com.hgapp.a6668.common.adapters.AutoSizeRVAdapter<String>{

        private Context context;
        public FlowBalanceTransferAdapter(Context context, int layoutId, List<String> datas){
            super(context, layoutId, datas);
            this.context =  context;
        }
        @Override
        protected void convert(ViewHolder holder, final String  string, final int position) {

            holder.setText(R.id.tvItemBalanceTransfer,string);
            holder.setOnClickListener(R.id.tvItemBalanceTransfer,new View.OnClickListener(){

                @Override
                public void onClick(View view) {
                    etDepositThirdMobileMoney.setText(string);
                }
            });
        }


    }

    private void onCheckThirdMobilePay(){
        String thirdMobileMoney = etDepositThirdMobileMoney.getText().toString().trim();

        if(Check.isEmpty(thirdMobileMoney)){
            showMessage("汇款金额必须是整数！");
            return;
        }
        if("请点击此处选择支付渠道 ⇊▼".equals(tvDepositThirdMobile.getText().toString().trim())){
            showMessage("亲，记得请选择支付渠道哦！");
            return;
        }
        if(Check.isEmpty(url)){
            showMessage("充值方式错误，请联系管理员！");
            return;
        }

        EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(url,thirdMobileMoney,useId,id,""), SupportFragment.SINGLETASK));
    }

    @OnClick({R.id.btnDepositThirdMobileSubmit,R.id.tvDepositThirdMobile})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.btnDepositThirdMobileSubmit:
                onCheckThirdMobilePay();
                break;
            case R.id.tvDepositThirdMobile:
                hideSoftInput();
                gtypeOptionsPickerIn.show();
                break;
        }
    }

}
