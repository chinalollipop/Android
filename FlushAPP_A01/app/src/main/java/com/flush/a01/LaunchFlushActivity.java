package com.flush.a01;

import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.flush.a01.data.AviableDomainParam;
import com.flush.a01.data.DomainAddResult;
import com.flush.a01.data.DomainAllResult;
import com.flush.a01.data.DomainListResult;
import com.flush.a01.data.FlushDomainEvent;
import com.flush.a01.http.DomainUrl;
import com.flush.a01.http.MyHttpClient;
import com.flush.a01.http.request.AppTextMessageResponse;
import com.flush.a01.http.request.AppTextMessageResponseList;
import com.flush.a01.utils.ACache;
import com.flush.a01.utils.AppUtil;
import com.flush.a01.utils.Check;
import com.flush.a01.utils.GameLog;
import com.flush.a01.utils.NetworkUtils;
import com.flush.a01.utils.ToastUtils;
import com.google.gson.Gson;
import com.kongzue.dialog.interfaces.OnDialogButtonClickListener;
import com.kongzue.dialog.interfaces.OnInputDialogButtonClickListener;
import com.kongzue.dialog.util.BaseDialog;
import com.kongzue.dialog.util.DialogSettings;
import com.kongzue.dialog.v3.InputDialog;
import com.kongzue.dialog.v3.MessageDialog;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

public class LaunchFlushActivity extends AppCompatActivity {
    @BindView(R.id.RView)
    RecyclerView RView;
    @BindView(R.id.addNewItem)
    TextView addNewItem;
    @BindView(R.id.appVersion)
    TextView appVersion;
    @BindView(R.id.appFlush)
    TextView appFlush;
    @BindView(R.id.clearCookie)
    TextView clearCookie;
    private boolean ifStop = false;
    //http://admin.qsd0086.com/
    // http://admin.789567111.com/
//    String domainUrl = "http://admin.qsd0086.com/";//  http://admin.qdf6668.com/ 0086 http://admin.qsd0086.com/
    String domainUrl = "http://admin.836298.com/";//
//    String domainUrl = "http://admin.100372.com/";//
//    String domainUrl = "http://admin.77000111.com/";//

    List<DomainAllResult.DataBean> domainListResults = new ArrayList<>();
    DomainAllResult domainUrlList;
    MyHttpClient myHttpClient = new MyHttpClient();
    BetDragonRecordAdapter betDragonRecordAdapter;

    @Subscribe
    public void onMainEvent(FlushDomainEvent flushDomainEvent){
        GameLog.log("更新成功了。。。。。");
        onGetAvailableDomain();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EventBus.getDefault().register(this);
        setContentView(R.layout.activity_flush_launch);
        ButterKnife.bind(this);
        LinearLayoutManager linearRecordLayoutManager = new LinearLayoutManager(this, OrientationHelper.VERTICAL, false);
        RView.setLayoutManager(linearRecordLayoutManager);
        appVersion.setText("版本"+ AppUtil.getPackageInfo(this).versionName);
        initDemon();
    }

    private void initDemon() {
        InputDialog.show(LaunchFlushActivity.this, "提示", "请输入刷水域名地址", "确定", "取消")
                .setInputText(domainUrl)//"http://admin.hgw777.co/"
                .setOnOkButtonClickListener(new OnInputDialogButtonClickListener() {
                    @Override
                    public boolean onClick(BaseDialog baseDialog, View v, String inputStr) {
                        //inputStr 即当前输入的文本
                        domainUrl = inputStr;
                        GameLog.log("用户输入的地址是 " + domainUrl);
                        onGetAvailableDomain();
                        return false;
                    }
                });
    }

    private void onCheckNetWork(){
        InputDialog.show(LaunchFlushActivity.this, "提示", "请检查您输入的网址是否正常！！！", "确定", "取消")
                .setInputText(domainUrl)//"http://admin.hgw777.co/"
                .setOnOkButtonClickListener(new OnInputDialogButtonClickListener() {
                    @Override
                    public boolean onClick(BaseDialog baseDialog, View v, String inputStr) {
                        //inputStr 即当前输入的文本
                        domainUrl = inputStr;
                        GameLog.log("用户输入的地址是 " + domainUrl);
                        onGetAvailableDomain();
                        return false;
                    }
                });
    }

    //获取可用域名
    public void onGetAvailableDomain() {
        /**
         * https://hg00086.firebaseapp.com/y/hg6668.ini     6668的域名地址
         * https://hg00086.firebaseapp.com/y/hg0086.ini     0086的域名地址
         * https://hg00086.firebaseapp.com/ym.conf
         */
        if (!NetworkUtils.isConnected()) {
            ToastUtils.showLongToast("无网络连接！");
        }
        AviableDomainParam aviableDomainParam = new AviableDomainParam();
        aviableDomainParam.setAction("viewAcc");

        myHttpClient.executeGet(domainUrl + "app/agents/downdata_receive/wateraccount.php?action=viewAcc", new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
                e.printStackTrace();
                RView.post(new Runnable() {
                               @Override
                               public void run() {
                                   showMessage("请求失败，请检查网络是否正常！");
                               }
                           });
                /*String demainUrl = ACache.get(getApplicationContext()).getAsString("app_demain_url");
                enterMain();*/
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText = response.body().string();
                RView.post(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("返回的数据：" + responseText);
                        if(responseText.contains("403 Forbidden")){
                            showMessage("请检查你的网络是否正常，此处不要连接VPN！！！");
                            return;
                        }
                        if(responseText.contains("<html")){
                            //showMessage("");
                            onCheckNetWork();
                            return;
                        }
                        domainUrlList = new Gson().fromJson(responseText, DomainAllResult.class);
                        if(!Check.isNull(domainUrlList.getData())){
                            domainListResults.clear();
                            domainListResults.addAll(domainUrlList.getData());
                        }
                        betDragonRecordAdapter = new BetDragonRecordAdapter(R.layout.item_flush_data, domainListResults);
                        RView.setAdapter(betDragonRecordAdapter);
                        betDragonRecordAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                            @Override
                            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                                switch (view.getId()){
                                    case R.id.itemJoin:
                                        EditText itemDomain = view.findViewById(R.id.itemDomain);
                                        EditText itemName = view.findViewById(R.id.itemName);
                                        EditText itemPwd = view.findViewById(R.id.itemPwd);
                                        showMessage(itemDomain.getText().toString());
                                        //https://hga030.com/&nameEx=laobb020&passwdEx=qaz123&
                                        /*String data = itemDomain.getText().toString().trim()+"&nameEx="+itemName.getText().toString().trim()+
                                                "&passwdEx="+itemPwd.getText().toString().trim()+
                                                "&uidEx=61sk9abhm21757787l187808&cookie=gamePoint_21757787=2019-04-02%2A0%2A0;hide_notice=;protocolstr=https;OddType=;_ga=GA1.2.239557531.1564639244;_gid=;_gat_UA=";
                                        inJoin(position,data);*/
                                        break;
                                    case R.id.itemDelete:
                                        if(Check.isNull(domainListResults.get(position).getID())||"新增".equals(domainListResults.get(position).getID())){
                                            showMessage("此项数据无法删除！");
                                            return;
                                        }
                                        deleItem(position,domainListResults.get(position).getID());
                                        break;
                                }
                            }
                        });
                        if (domainUrlList.getStatus()==200) {
                        }else{
                            betDragonRecordAdapter.setEmptyView(showNoData(domainUrlList.getMessage()));
                            showMessage(domainUrlList.getMessage());
                        }
                    }
                });

            }
        });

       /* myHttpClient.execute(domainUrl+"app/agents/downdata_receive/wateraccount.php",aviableDomainParam, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
                String demainUrl =  ACache.get(getApplicationContext()).getAsString("app_demain_url");
                enterMain();
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText =  response.body().string();
                if(response.isSuccessful()){
                    onGetSuccessDomain(responseText);
                }
            }
        });*/
    }

    private void inJoin(int postion,String urldata,final String domainUrls){
        //showMessage("不确定"+domainListResults.get(position).getID());
        myHttpClient.executeGet(domainUrl + "app/agents/downdata_receive/wateraccount.php?action=addAcc&addAcc="+urldata, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
               /* String demainUrl = ACache.get(getApplicationContext()).getAsString("app_demain_url");
                enterMain();*/
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText = response.body().string();
                RView.post(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("返回的数据：" + responseText);
                        try {
                            DomainAddResult domainUrlList = new Gson().fromJson(responseText, DomainAddResult.class);
                            if (domainUrlList.getStatus() == 200) {
                                showMessage(domainUrlList.getMessage());
                                String editurl =  domainUrl + "app/agents/downdata_receive/wateraccount.php?action=addAcc&addAcc=";
                                ACache.get(getApplicationContext()).put("app_demain_url_s",editurl);
                                ACache.get(getApplicationContext()).put("app_demain_url",domainUrls);
                                enterMain();
                            } else {
                                showMessage(domainUrlList.getMessage());
                            }
                        }catch (Exception e){
                            e.printStackTrace();
                        }
                    }
                });

            }
        });
    }


    //删除刷水账号
    private void deleItem(final int position,final String id){
        MessageDialog.show(this, "删除此域名", "确定要删除吗？", "确定")
        .setOkButton(new OnDialogButtonClickListener() {
            @Override
            public boolean onClick(BaseDialog baseDialog, View v) {
                myHttpClient.executeGet(domainUrl + "app/agents/downdata_receive/wateraccount.php?action=delAcc&id="+id, new Callback() {
                    @Override
                    public void onFailure(Call call, final IOException e) {
                        String demainUrl = ACache.get(getApplicationContext()).getAsString("app_demain_url");
                        enterMain();
                    }

                    @Override
                    public void onResponse(Call call, Response response) throws IOException {
                        final String responseText = response.body().string();
                        domainUrlList = new Gson().fromJson(responseText, DomainAllResult.class);
                        RView.post(new Runnable() {
                            @Override
                            public void run() {
                                GameLog.log("返回的数据：" + responseText);
                                domainListResults.remove(position);
                                betDragonRecordAdapter.notifyItemChanged(position);
                                showMessage(domainUrlList.getMessage());
                            }
                        });

                    }
                });
                return false;
            }
        });
    }

    private View showNoData(String message) {
        View view = LayoutInflater.from(this).inflate(R.layout.item_card_nodata, null);
        TextView textView = view.findViewById(R.id.itemNoDate);
//        textView.setText("当前查询条件下暂无查询数据");
        textView.setText(message);
        textView.setTextColor(Color.parseColor("#C52133"));
        return view;
    }


    class BetDragonRecordAdapter extends BaseQuickAdapter<DomainAllResult.DataBean, BaseViewHolder> {

        public BetDragonRecordAdapter(int layoutResId, @Nullable List<DomainAllResult.DataBean> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(final BaseViewHolder helper, final DomainAllResult.DataBean item) {

            if("新增".equals(item.getID())){
                helper.setTextColor(R.id.itemId,getResources().getColor(R.color.notificationError));
            }
            helper.setText(R.id.itemId, item.getID()).
                    setText(R.id.itemDomain, item.getDatasite()).
                    setText(R.id.itemName, item.getName()).
                    setText(R.id.itemPwd, item.getPasswd()).
//                    addOnClickListener(R.id.itemJoin).
                    addOnClickListener(R.id.itemDelete);
            helper.setOnClickListener(R.id.itemJoin, new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    EditText itemDomain = helper.getView(R.id.itemDomain);
                    EditText itemName = helper.getView(R.id.itemName);
                    EditText itemPwd = helper.getView(R.id.itemPwd);
                    if(Check.isEmpty(itemDomain.getText().toString().trim())||"http://".equals(itemDomain.getText().toString().trim())){
                        showMessage("请检查URL地址是否正确！");
                        return;
                    }
                    if(Check.isEmpty(itemName.getText().toString().trim())){
                        showMessage("账户不能为空！");
                        return;
                    }
                    if(Check.isEmpty(itemPwd.getText().toString().trim())){
                        showMessage("密码不能为空！");
                        return;
                    }
                    String data="",editurl="";
                    data= "&nameEx="+itemName.getText().toString().trim()+
                            "&passwdEx="+itemPwd.getText().toString().trim()+"";
                    if(!Check.isEmpty(item.getID())&&item.getID().equals("新增")){
                        //inJoin(0,data,itemDomain.getText().toString().trim());
                        editurl =  domainUrl + "app/agents/downdata_receive/wateraccount.php?appRefer=14&action=addAcc"+data;
                    }else{
                        editurl =  domainUrl + "app/agents/downdata_receive/wateraccount.php?appRefer=14&action=edtAcc&id="+item.getID()+""+data;
                    }
                    ACache.get(getApplicationContext()).put("app_demain_url",itemDomain.getText().toString());
                    ACache.get(getApplicationContext()).put("app_demain_url_s",editurl);
                    enterMain();
                    //showMessage("我时间紧");
                }
            });
        }
    }



    private void enterMain() {
        startActivity(new Intent(LaunchFlushActivity.this, MainActivity.class));
        //finish();
    }

    private synchronized void postDomain(final String demain) {
        myHttpClient.executeGet(demain + "api/answer.php", new Callback() {//
            @Override
            public void onFailure(Call call, final IOException e) {
                GameLog.log("request url error: " + e.toString());
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText = response.body().string();
                if (ifStop) {
                    GameLog.log("====================2=======================");
                    return;
                }
                if (response.isSuccessful()) {
                    ifStop = true;
                    ACache.get(getApplicationContext()).put("app_demain_url", demain);
                    enterMain();
                }
            }
        });
    }

    private void onGetSuccessDomain(String responseText) {
        try {
            DomainUrl domainUrl = new Gson().fromJson(responseText, DomainUrl.class);
            final List<DomainUrl.ListBean> domains = domainUrl.getList();
            for (int k = 0; k < domains.size(); ++k) {
                if (ifStop) {
                    return;
                }
                postDomain(domains.get(k).getUrl());
            }
        } catch (Exception e) {
            GameLog.log("request url : " + e.toString());
        }
    }

    @OnClick({R.id.addNewItem, R.id.appFlush, R.id.clearCookie})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.addNewItem:
                addNewItem();
                break;
            case R.id.appFlush:
                initDemon();
                break;
            case R.id.clearCookie:
                clearCookie();
                break;
        }
    }

    private void addNewItem() {
        DomainAllResult.DataBean domainListResult = new DomainAllResult.DataBean();
        domainListResult.setID("新增");
        domainListResult.setName("");
        domainListResult.setPasswd("");
        domainListResult.setDatasite("http://");
        /*domainListResults.add(domainListResult);
        BetDragonRecordAdapter betDragonRecordAdapter = new BetDragonRecordAdapter(R.layout.item_flush_data, domainListResults);
        RView.setAdapter(betDragonRecordAdapter);*/
        betDragonRecordAdapter.addData(domainListResult);
        betDragonRecordAdapter.notifyDataSetChanged();
    }

    private void clearCookie() {
        MessageDialog.show(this,"删除全部缓存和Cookie?","可能会导致正在使用的cookie失效！")
                .setOkButton("删除")
                .setCancelButton("取消")
                .setOnOkButtonClickListener(new OnDialogButtonClickListener() {
                    @Override
                    public boolean onClick(BaseDialog baseDialog, View v) {
                        showMessage("清理缓存成！");
                        return false;
                    }
                });
    }

    public void showMessage(String message)
    {
        ToastUtils.showLongToast(message);
    }
}
