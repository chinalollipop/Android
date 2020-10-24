package com.hgapp.a0086.homepage.online;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.CLipHelper;
import com.hgapp.a0086.common.util.FilterApp;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.widgets.NTitleBar;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.CopyUtil;
import com.hgapp.common.util.PackageUtil;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class ContractFragment extends HGBaseFragment {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    @BindView(R.id.backContract)
    NTitleBar backContract;
    @BindView(R.id.tvContractQQNumber)
    TextView tvContractQQNumber;
    @BindView(R.id.tvContractWXNumber)
    TextView tvContractWXNumber;
    @BindView(R.id.llContractQQ)
    LinearLayout llContractQQ;
    @BindView(R.id.llContractWX)
    LinearLayout llContractWX;
    @BindView(R.id.tvContractWXBg)
    ImageView tvContractWXBg;
    private String cate;
    private String getArgParam1;
    private String getArgParam2;
    private String getArgParam3;
    List<String> fasterPayList = new ArrayList<>();

    public static ContractFragment newInstance(String getArgParam1, String getArgParam2, String getArgParam3) {
        ContractFragment fragment = new ContractFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, getArgParam1);
        args.putString(ARG_PARAM2, getArgParam2);
        args.putString(ARG_PARAM3, getArgParam3);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            getArgParam1 = getArguments().getString(ARG_PARAM1);
            getArgParam2 = getArguments().getString(ARG_PARAM2);
            getArgParam3 = getArguments().getString(ARG_PARAM3);
        }
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_contract;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        backContract.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });
        if (Check.isEmpty(getArgParam1)) {
            backContract.setMoreImage(null);
        }
        backContract.setMoreText(getArgParam1);
        tvContractQQNumber.setText(getArgParam2);
        tvContractWXNumber.setText(getArgParam3);
        String wechat_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL_WECHAT+"_url");
        Picasso.with(getContext())
                .load(wechat_url)
                .placeholder(R.drawable.loading)
                .into(tvContractWXBg);

        /*new Thread(new Runnable() {
            @Override
            public void run() {
                fasterPayList = FilterApp.newInstance().filterApp(getContext(), 2);
            }
        }).start();*/



    }


    @OnClick({R.id.llContractQQ, R.id.llContractWX})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.llContractQQ:
                CLipHelper.copy(getContext(),getArgParam2);
                showMessage("复制成功！");
                /*if(fasterPayList.contains("com.tencent.mobileqq")){
                    //PackageUtil.startAppByPackageName(getContext(), "com.tencent.mobileqq");
                    String tvContractQQ = tvContractQQNumber.getText().toString();
                    if(!Check.isEmpty(tvContractQQ)){
                        PackageUtil.startChatQQ(getContext(), tvContractQQ);
                    }else{
                        PackageUtil.startAppByPackageName(getContext(), "com.tencent.mobileqq");
                    }
                }else{
                    showMessage("手机没有安装QQ，请安装再重试！");
                }*/
                /*for(int k=0;k<fasterPayList.size();++k){

                    if("com.tencent.mm".equals(fasterPayList.get(0).getPackageName())){
                        PackageUtil.startAppByPackageName(getContext(), fasterPayList.get(0).getPackageName());
                    }
                }*/
                break;
            case R.id.llContractWX:
                CLipHelper.copy(getContext(),getArgParam3);
                showMessage("复制成功！");
                /*if(!tvContractWXBg.isShown()){
                    tvContractWXBg.setVisibility(View.VISIBLE);
                }else{
                    tvContractWXBg.setVisibility(View.GONE);
                }*/
                /*if(fasterPayList.contains("com.tencent.mm")){
                    PackageUtil.startAppByPackageName(getContext(), "com.tencent.mm");
                }else{
                    showMessage("手机没有安装微信，请安装再重试！");
                }*/

                break;
        }

    }
}
