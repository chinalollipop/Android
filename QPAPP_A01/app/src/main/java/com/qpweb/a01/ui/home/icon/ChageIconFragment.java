package com.qpweb.a01.ui.home.icon;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.ChangIconResult;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.IconEvent;
import com.qpweb.a01.data.NickNameResult;
import com.qpweb.a01.data.PSignatureResult;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class ChageIconFragment extends BaseDialogFragment implements IconContract.View {

    IconContract.Presenter presenter;
    @BindView(R.id.changeIconRView)
    RecyclerView changeIconRView;
    @BindView(R.id.changeIconSure)
    TextView changeIconSure;
    @BindView(R.id.changeIconClose)
    ImageView changeIconClose;
    int postion=1;
    private static List<ChangeIcon> changeIconsList = new ArrayList<>();
    static {
        changeIconsList.add(new ChangeIcon("1",R.mipmap.icon_v1,false));
        changeIconsList.add(new ChangeIcon("2",R.mipmap.icon_v2,false));
        changeIconsList.add(new ChangeIcon("3",R.mipmap.icon_v3,false));
        changeIconsList.add(new ChangeIcon("4",R.mipmap.icon_v4,false));
        changeIconsList.add(new ChangeIcon("5",R.mipmap.icon_v5,false));
        changeIconsList.add(new ChangeIcon("6",R.mipmap.icon_v6,false));
        changeIconsList.add(new ChangeIcon("7",R.mipmap.icon_v7,false));
    }


    public static ChageIconFragment newInstance() {
        Bundle bundle = new Bundle();
        ChageIconFragment loginFragment = new ChageIconFragment();
        loginFragment.setArguments(bundle);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.change_icon_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 4, OrientationHelper.VERTICAL, false);
        changeIconRView.setLayoutManager(gridLayoutManager);
        changeIconRView.setHasFixedSize(true);
        changeIconRView.setNestedScrollingEnabled(false);
        ChangeIconAdapter changeIconAdapter = new ChangeIconAdapter(R.layout.item_change_icon,changeIconsList);
        changeIconAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                for(int k=0;k<changeIconsList.size();++k){
                    changeIconsList.get(k).setCheck(false);
                }
                postion = position+1;
                GameLog.log("用户选中的位置是 "+postion);
                changeIconsList.get(position).setCheck(true);
                adapter.notifyDataSetChanged();
            }
        });
        changeIconRView.setAdapter(changeIconAdapter);
        String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);
    }


    class ChangeIconAdapter extends BaseQuickAdapter<ChangeIcon, BaseViewHolder> {

        public ChangeIconAdapter(int layoutId, @Nullable List<ChangeIcon> datas) {
            super(layoutId, datas);
        }


        @Override
        protected void convert(BaseViewHolder holder, ChangeIcon data) {
            if(data.isCheck()){
                holder.setBackgroundRes(R.id.itemChangeIconLay, getResources().getIdentifier("icon_item_prick","mipmap",getActivity().getApplicationInfo().packageName));
            }else{
                holder.setBackgroundRes(R.id.itemChangeIconLay, 0);
            }
            holder.setBackgroundRes(R.id.itemChangeIcon, data.getId());

            holder.addOnClickListener(R.id.itemChangeIcon);
        }
    }



    private void onCheckAndSubmit() {
        /*String loginAccounts = loginAccount.getText().toString().trim();
        String loginPwdPwds = loginPwd.getText().toString().trim();
        if (Check.isEmpty(loginAccounts)) {
            showMessage("请输入合法的用户账号");
            return;
        }
        if (Check.isEmpty(loginPwdPwds)) {
            showMessage("请输入验证码");
            return;
        }
        presenter.postLogin("", loginAccounts, loginPwdPwds);*/
    }

    @Override
    public void postChangeIconResult(ChangIconResult changIconResult) {
    }

    @Override
    public void postChangeNickNameResult(NickNameResult nickNameResult) {

    }

    @Override
    public void postChangeSignWordsResult(PSignatureResult pSignatureResult) {

    }

    @Override
    public void setPresenter(IconContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @OnClick({R.id.changeIconSure, R.id.changeIconClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.changeIconSure:
                GameLog.log("当前保存的头像位置是 "+postion);
                EventBus.getDefault().post(new IconEvent(""+postion));
                hide();
                break;
            case R.id.changeIconClose:
                hide();
                break;
        }
    }

}
