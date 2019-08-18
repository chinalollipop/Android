package com.qpweb.a01.ui.home.deposit;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.text.Spannable;
import android.text.SpannableString;
import android.view.View;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.qpweb.a01.Injections;
import com.qpweb.a01.MainActivity;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.DepositAliPayQCCodeResult;
import com.qpweb.a01.data.DepositBankCordListResult;
import com.qpweb.a01.data.DepositListResult;
import com.qpweb.a01.data.DepositThirdBankCardResult;
import com.qpweb.a01.data.DepositThirdQQPayResult;
import com.qpweb.a01.data.IconEvent;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.DateHelper;
import com.qpweb.a01.utils.DoubleClickHelper;
import com.qpweb.a01.utils.GameLog;

import org.angmarch.views.NiceSpinner;
import org.angmarch.views.OnSpinnerItemSelectedListener;
import org.angmarch.views.SpinnerTextFormatter;
import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class DepositFragment extends BaseDialogFragment implements DepositContract.View {

    @BindView(R.id.depositRView)
    RecyclerView depositRView;

    DepositContract.Presenter presenter;
    @BindView(R.id.depositBanklay)
    LinearLayout depositBanklay;
    @BindView(R.id.depositQClay)
    LinearLayout depositQClay;
    @BindView(R.id.depositQCImge)
    ImageView depositQCImge;
    @BindView(R.id.depositQCNameLeft)
    TextView depositQCNameLeft;
    @BindView(R.id.depositQCName)
    TextView depositQCName;
    @BindView(R.id.depositQCMomeLeft)
    TextView depositQCMomeLeft;
    @BindView(R.id.depositQCMome)
    EditText depositQCMome;
    @BindView(R.id.depositQCTime)
    NiceSpinner depositQCTime;
    @BindView(R.id.depositQCMoney)
    EditText depositQCMoney;
    @BindView(R.id.depositQCSubmit)
    TextView depositQCSubmit;
    @BindView(R.id.depositEditBankMoney)
    TextView depositEditBankMoney;
    @BindView(R.id.depositClear)
    ImageView depositClear;
    @BindView(R.id.depositMoneyRView)
    RecyclerView depositMoneyRView;
    @BindView(R.id.depositEditBankSubmit)
    TextView depositEditBankSubmit;
    @BindView(R.id.depositEditOnline)
    TextView depositEditOnline;
    @BindView(R.id.depositClose)
    ImageView depositClose;
    @BindView(R.id.flayBg)
    FrameLayout flayBg;

    ArrayList<DepositInputEvent> depositInputMoneyList = new ArrayList<>();
    ArrayList<DepositListResult> depositListResult = new ArrayList<>();
    @BindView(R.id.depositEditBankAccount)
    EditText depositEditBankAccount;
    @BindView(R.id.depositEditBankName)
    NiceSpinner depositEditBankName;
    @BindView(R.id.depositEditBankNumber)
    TextView depositEditBankNumber;
    @BindView(R.id.depositEditBankAdds)
    TextView depositEditBankAdds;
    @BindView(R.id.depositEditBankType)
    NiceSpinner depositEditBankType;
    @BindView(R.id.depositEditBankTime)
    NiceSpinner depositEditBankTime;
    @BindView(R.id.depositEditBankMemo)
    EditText depositEditBankMemo;
    String payId;//银行存款的id
    String bankName;//银行存款的公司+名字
    String onlineApi ="";
    List<String> stringListTime  = new ArrayList<String>();
    static List<String> stringListChannel  = new ArrayList<String>();//从0开始的
    static {
        stringListChannel.add("银行柜台");
        stringListChannel.add("ATM现金");
        stringListChannel.add("ATM卡转");
        stringListChannel.add("网银转账");
        stringListChannel.add("其他");
    }

    public static DepositFragment newInstance() {
        Bundle bundle = new Bundle();
        DepositFragment loginFragment = new DepositFragment();
        loginFragment.setArguments(bundle);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.deposit_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }

    @Subscribe
    public void onEventMain(IconEvent iconEvent) {
    }


    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        depositInputMoneyList.add(new DepositInputEvent("10", false));
        depositInputMoneyList.add(new DepositInputEvent("100", true));
        depositInputMoneyList.add(new DepositInputEvent("500", false));
        depositInputMoneyList.add(new DepositInputEvent("1000", false));
        depositInputMoneyList.add(new DepositInputEvent("3000", false));
        depositInputMoneyList.add(new DepositInputEvent("5000", false));
        depositInputMoneyList.add(new DepositInputEvent("10000", false));
        depositInputMoneyList.add(new DepositInputEvent("20000", false));
        presenter.postLogin("", "", "");
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
        depositRView.setLayoutManager(gridLayoutManager);
        depositRView.setHasFixedSize(true);
        depositRView.setNestedScrollingEnabled(false);

        GridLayoutManager gridLayoutManager1 = new GridLayoutManager(getContext(), 4, OrientationHelper.VERTICAL, false);
        depositMoneyRView.setLayoutManager(gridLayoutManager1);
        depositMoneyRView.setHasFixedSize(true);
        depositMoneyRView.setNestedScrollingEnabled(false);

        DepositInputMoneyRViewAdapter depositInputMoneyRViewAdapter = new DepositInputMoneyRViewAdapter(R.layout.item_deposit_input, depositInputMoneyList);
        depositMoneyRView.setAdapter(depositInputMoneyRViewAdapter);
        depositInputMoneyRViewAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                depositEditBankMoney.setText(depositInputMoneyList.get(position).money);
                for (int k = 0; k < 8; ++k) {
                    depositInputMoneyList.get(k).isCheck = false;
                }
                depositInputMoneyList.get(position).isCheck = true;
                adapter.notifyDataSetChanged();
            }
        });

        depositEditBankType.attachDataSource(stringListChannel);
        depositEditBankType.setOnSpinnerItemSelectedListener(new OnSpinnerItemSelectedListener() {
            @Override
            public void onItemSelected(NiceSpinner parent, View view, int position, long id) {
                // This example uses String, but your type can be any
                depositEditBankType.setText(stringListChannel.get(position));
                GameLog.log("当前选中的事 "+position +" "+stringListChannel.get(position));
            }
        });

        stringListTime.add(DateHelper.getYesterday6());
        stringListTime.add(DateHelper.getYesterday5());
        stringListTime.add(DateHelper.getYesterday4());
        stringListTime.add(DateHelper.getYesterday3());
        stringListTime.add(DateHelper.getYesterday2());
        stringListTime.add(DateHelper.getYesterday());
        stringListTime.add(DateHelper.getToday());

        /*Calendar cd = Calendar.getInstance();
        SimpleDateFormat FORMATE = new SimpleDateFormat("yyyy-MM-dd");
        cd.add(Calendar.DAY_OF_YEAR, -6);
        DateHelper.getToday();
        stringListTime.add(FORMATE.format(cd.getTime()));
        cd.add(Calendar.DAY_OF_WEEK, -5);
        stringListTime.add(FORMATE.format(cd.getTime()));
        cd.add(Calendar.DAY_OF_WEEK, -4);
        stringListTime.add(FORMATE.format(cd.getTime()));
        cd.add(Calendar.DAY_OF_WEEK, -3);
        stringListTime.add(FORMATE.format(cd.getTime()));
        cd.add(Calendar.DAY_OF_WEEK, -2);
        stringListTime.add(FORMATE.format(cd.getTime()));
        cd.add(Calendar.DAY_OF_WEEK, -1);
        stringListTime.add(FORMATE.format(cd.getTime()));
        cd.add(Calendar.DAY_OF_WEEK, -0);
        stringListTime.add(FORMATE.format(cd.getTime()));*/
        //银行卡存款日期选择
        depositEditBankTime.attachDataSource(stringListTime);
        depositEditBankTime.setOnSpinnerItemSelectedListener(new OnSpinnerItemSelectedListener() {
            @Override
            public void onItemSelected(NiceSpinner parent, View view, int position, long id) {
                // This example uses String, but your type can be any
                depositEditBankTime.setText(stringListTime.get(position));
            }
        });
        depositEditBankTime.setText(getTime(new Date()));
        //扫码日期选择
        depositQCTime.attachDataSource(stringListTime);
        depositQCTime.setOnSpinnerItemSelectedListener(new OnSpinnerItemSelectedListener() {
            @Override
            public void onItemSelected(NiceSpinner parent, View view, int position, long id) {
                // This example uses String, but your type can be any
                depositQCTime.setText(stringListTime.get(position));
            }
        });
        depositQCTime.setText(getTime(new Date()));

    }

    class DepositInputMoneyRViewAdapter extends BaseQuickAdapter<DepositInputEvent, BaseViewHolder> {

        public DepositInputMoneyRViewAdapter(int layoutId, @Nullable List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final DepositInputEvent data) {
            holder.setText(R.id.itemDepositInputText, data.money).
                    addOnClickListener(R.id.itemDepositInputText);
            if (data.isCheck) {
                holder.setBackgroundRes(R.id.itemDepositInputText, R.mipmap.box_in);
                holder.setTextColor(R.id.itemDepositInputText, getResources().getColor(R.color.color_translucent));
            } else {
                holder.setTextColor(R.id.itemDepositInputText, getResources().getColor(R.color.bg_login_text));
                holder.setBackgroundRes(R.id.itemDepositInputText, R.mipmap.box_out);
            }
        }
    }


    private void onCheckQCAndSubmit() {
        String money = depositQCMoney.getText().toString().trim();
        String mome = depositQCMome.getText().toString().trim();
        String bankTime = depositQCTime.getText().toString().trim();
        //String bankName = depositEditBankName.getText().toString().trim();
        /*String bankNumber = depositEditBankNumber.getText().toString().trim();
        String bankadds = depositEditBankAdds.getText().toString().trim();*/
        if (Check.isEmpty(money)||Double.parseDouble(money)<Double.parseDouble("100")) {
            super.showMessage("汇款金额须大于100元！");
            return;
        }
        if (Check.isEmpty(mome)) {
            showMessage("请输入交易单号！");
            return;
        }
        presenter.postDepositAliPayQCPaySubimt("", payId,  money, bankTime, mome,bankName);
    }

    private void onCheckAndSubmit() {
        String money = depositEditBankMoney.getText().toString().trim();
        String account = depositEditBankAccount.getText().toString().trim();
        String bankType = depositEditBankType.getText().toString().trim();
        String bankTime = depositEditBankTime.getText().toString().trim();
        String bankMome = depositEditBankMemo.getText().toString().trim();
        //String bankName = depositEditBankName.getText().toString().trim();
        /*String bankNumber = depositEditBankNumber.getText().toString().trim();
        String bankadds = depositEditBankAdds.getText().toString().trim();*/
        if (Check.isEmpty(money)||Double.parseDouble(money)<Double.parseDouble("100")) {
            super.showMessage("汇款金额须大于100元！");
            return;
        }
        if (Check.isEmpty(account)) {
            showMessage("请输入存款人姓名");
            return;
        }
        presenter.postDepositCompanyPaySubimt("",payId, account, bankType,money,bankTime,bankMome,bankName);
    }

    @Override
    public void postLoginResult(List<DepositListResult> depositListResults) {
//        hide();
        this.depositListResult = (ArrayList<DepositListResult>) depositListResults;
        depositListResult.get(0).setCheck(true);
        onListenerDeposit(depositListResult.get(0).getId(), depositListResult.get(0).getBankid(), depositListResult.get(0).getApi());
        DepositAdapter depositAdapter = new DepositAdapter(R.layout.item_deposit, depositListResult);
        depositRView.setAdapter(depositAdapter);
        depositAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                for (int k = 0; k < depositListResult.size(); ++k) {
                    depositListResult.get(k).isCheck = false;
                }
                depositListResult.get(position).isCheck = true;
                onListenerDeposit(depositListResult.get(position).getId(), depositListResult.get(position).getBankid(),  depositListResult.get(position).getApi());
                adapter.notifyDataSetChanged();
            }
        });
    }

    private void onListenerDeposit(int id, String bankid, String api) {
        String payId = id + "";
        GameLog.log("当前支付的ID是： " + id);
        depositEditOnline.setVisibility(View.GONE);
        switch (id) {
            case 0://快速充值
                //直接跳转到支付页面
                onlineApi = api;
                depositBanklay.setVisibility(View.GONE);
                depositEditOnline.setVisibility(View.VISIBLE);
                depositQClay.setVisibility(View.GONE);
                //EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(api,"","","",""), SupportFragment.SINGLETASK));
                break;
            case 1://银行卡线上
                presenter.postDepositThirdBankCard("");
                break;
            case 2://公司入款
                presenter.postDepositBankCordList("");
                break;
            case 3://微信第三方
                presenter.postDepositThirdWXPay("");
                break;
            case 4://支付宝第三方
                presenter.postDepositThirdAliPay("");
                break;
            case 5://QQ第三方
                presenter.postDepositThirdQQPay("");
                break;
            case 6://支付宝扫码
                presenter.postDepositAliPayQCCode("", bankid);
                break;
            case 7://微信扫码
                presenter.postDepositWechatQCCode("", bankid);
                break;
        }
    }

    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }

    @Override
    public void postDepositBankCordListResult(DepositBankCordListResult message) {
        depositBanklay.setVisibility(View.VISIBLE);
        depositQClay.setVisibility(View.GONE);
        final List<DepositBankCordListResult.DataBean> dataBeanList = message.getData();
        if(dataBeanList.size()==1){
            dataBeanList.addAll(dataBeanList);
        }
        SpinnerTextFormatter textFormatter = new SpinnerTextFormatter<DepositBankCordListResult.DataBean>() {
            @Override
            public Spannable format(DepositBankCordListResult.DataBean dataBean) {
                return new SpannableString(dataBean.getBank_name());
            }
        };
        depositEditBankName.setSpinnerTextFormatter(textFormatter);
        depositEditBankName.setSelectedTextFormatter(textFormatter);
        depositEditBankName.setOnSpinnerItemSelectedListener(new OnSpinnerItemSelectedListener() {
            @Override
            public void onItemSelected(NiceSpinner parent, View view, int position, long id) {
                // This example uses String, but your type can be any
                DepositBankCordListResult.DataBean dataBean = (DepositBankCordListResult.DataBean)depositEditBankName.getSelectedItem();
                payId = dataBean.getId();
                bankName = dataBean.getBank_name()+"-"+dataBean.getBank_user();
                depositEditBankName.setText(dataBean.getBank_name());
                depositEditBankNumber.setText(dataBean.getBank_account());
                depositEditBankAdds.setText(dataBean.getBank_addres());
                GameLog.log("当前银行选中的事" +dataBean.getBank_name()+" position:"+position);
            }
        });
        depositEditBankName.attachDataSource(dataBeanList);
        payId = dataBeanList.get(0).getId();
        bankName = dataBeanList.get(0).getBank_name()+"-"+dataBeanList.get(0).getBank_user();
        depositEditBankName.setText(dataBeanList.get(0).getBank_name());
        depositEditBankNumber.setText(dataBeanList.get(0).getBank_account());
        depositEditBankAdds.setText(dataBeanList.get(0).getBank_addres());
        depositEditBankType.setText("银行柜台");
        depositEditBankTime.setText(getTime(new Date()));

       /* if (mPopupWindowList == null){
            mPopupWindowList = new PopupWindowList(depositEditBankName.getContext());
        }
        mPopupWindowList.setAnchorView(depositEditBankName);
        mPopupWindowList.setItemData(stringListChannel);
        mPopupWindowList.setModal(true);
        mPopupWindowList.show();
        mPopupWindowList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                depositEditBankType.setText(stringListChannel.get(position));
                mPopupWindowList.hide();
            }
        });*/

        /*optionsPickerViewChanel = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                depositEditBankType.setText(stringListChannel.get(options1));
            }
        }).build();
        optionsPickerViewChanel.setPicker(stringListChannel);*/


    }

    @Override
    public void postDepositAliPayQCCodeResult(DepositAliPayQCCodeResult depositAliPayQCCodeResult) {
        depositBanklay.setVisibility(View.GONE);
        depositQClay.setVisibility(View.VISIBLE);
        payId = depositAliPayQCCodeResult.getData().getId();
        bankName = depositAliPayQCCodeResult.getData().getBank_user();
        depositQCName.setText(bankName);
        /*if(bankName.contains("支付宝")){
            depositQCNameLeft.setText("支付宝姓名：");
        }else if(bankName.contains("微信")){
            depositQCNameLeft.setText("微信姓名：");
        }*/
        depositQCMomeLeft.setText(depositAliPayQCCodeResult.getData().getNotice()+":");
        depositQCMome.setHint(depositAliPayQCCodeResult.getData().getNotice());




        depositQCTime.setText(getTime(new Date()));
        Glide.with(DepositFragment.this).load(depositAliPayQCCodeResult.getData().getPhoto_name()).apply(new RequestOptions().fitCenter()).into(depositQCImge);
    }

    @Override
    public void postDepositThirdBankCardResult(DepositThirdBankCardResult message) {

    }

    @Override
    public void postDepositThirdWXPayResult(DepositThirdQQPayResult message) {
        depositBanklay.setVisibility(View.GONE);
        depositQClay.setVisibility(View.VISIBLE);
    }

    @Override
    public void postDepositThirdAliPayResult(DepositThirdQQPayResult message) {

    }

    @Override
    public void postDepositThirdQQPayResult(DepositThirdQQPayResult message) {

    }

    @OnClick({R.id.depositEditOnline, R.id.depositClear, R.id.depositEditBankSubmit,R.id.depositQCSubmit, R.id.depositClose,R.id.depositEditBankType})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.depositEditOnline:
                Intent intent = new Intent(getContext(), MainActivity.class);
                intent.putExtra("app_url",onlineApi);
                startActivity(intent);
                break;
            case R.id.depositQCSubmit:
                DoubleClickHelper.getNewInstance().disabledView(depositQCSubmit);
                onCheckQCAndSubmit();
                break;
            case R.id.depositClear:
                depositEditBankMoney.setText("请输入充值金额");
                break;
            case R.id.depositEditBankSubmit:
                DoubleClickHelper.getNewInstance().disabledView(depositEditBankSubmit);
                onCheckAndSubmit();
                break;
            case R.id.depositClose:
                hide();
                break;
            case R.id.depositEditBankType:
                /*BottomMenu.show((AppCompatActivity)getActivity(), stringListChannel, new OnMenuItemClickListener() {
                    @Override
                    public void onClick(String text, int index) {
                        depositEditBankType.setText(stringListChannel.get(index));
                        GameLog.log("你选中了哪个"+stringListChannel.get(index));
                    }
                });*/
                break;
        }
    }


    class DepositAdapter extends BaseQuickAdapter<DepositListResult, BaseViewHolder> {
        public DepositAdapter(int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final DepositListResult data) {
            if (data.isCheck) {
                holder.setBackgroundRes(R.id.itemDeposit, R.mipmap.box_in);
            } else {
                holder.setBackgroundRes(R.id.itemDeposit, R.mipmap.box_out);
            }
            holder.setText(R.id.itemDeposit, data.getTitle()).addOnClickListener(R.id.itemDeposit);
            ;
        }
    }

    @Override
    public void setPresenter(DepositContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

}
