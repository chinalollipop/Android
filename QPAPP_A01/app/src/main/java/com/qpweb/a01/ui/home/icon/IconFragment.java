package com.qpweb.a01.ui.home.icon;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.ChangIconResult;
import com.qpweb.a01.data.ChangeAccountEvent;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.IconEvent;
import com.qpweb.a01.data.NickNameEvent;
import com.qpweb.a01.data.NickNameResult;
import com.qpweb.a01.data.PSignatureResult;
import com.qpweb.a01.ui.home.bind.BindFragment;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.CLipHelper;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.InputMethodUtils;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.Utils;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.math.RoundingMode;
import java.text.DecimalFormat;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import butterknife.Unbinder;

public class IconFragment extends BaseDialogFragment implements IconContract.View {

    @BindView(R.id.iconUserIcon)
    ImageView iconUserIcon;
    @BindView(R.id.iconEditName)
    EditText iconEditName;
    @BindView(R.id.iconEditNameBi)
    ImageView iconEditNameBi;
    @BindView(R.id.iconCopyId)
    TextView iconCopyId;
    @BindView(R.id.iconUserMoney)
    TextView iconUserMoney;
    @BindView(R.id.iconEditUserSignature)
    EditText iconEditUserSignature;
    @BindView(R.id.iconEditUserSignatureBi)
    ImageView iconEditUserSignatureBi;
    @BindView(R.id.iconChangeIcon)
    TextView iconChangeIcon;
    @BindView(R.id.iconBindPhone)
    TextView iconBindPhone;
    @BindView(R.id.iconChangeUser)
    TextView iconChangeUser;
    @BindView(R.id.iconUserId)
    TextView iconUserId;
    @BindView(R.id.iconClose)
    ImageView iconClose;
    private int loginType = 0;//账号登录 0  验证码登录

    //用以控制用户修改昵称和签名 【1-->昵称】 【2 -->签名】 【0 是其他】
    private int changePostion=0;

    IconContract.Presenter presenter;

    public static IconFragment newInstance() {
        Bundle bundle = new Bundle();
        IconFragment loginFragment = new IconFragment();
        loginFragment.setArguments(bundle);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.icon_fragment;
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
    public void onEventMain(IconEvent iconEvent){
        String icon = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT_ICON);
        GameLog.log("用户当前头像"+icon+" 修改之后的图片 "+iconEvent.getPostion());
        if(!icon.equals(iconEvent.getPostion())){
            onChangeIcon(iconEvent.getPostion());
            presenter.postChangeIcon("","avatarid_save",iconEvent.getPostion());
            ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT_ICON,""+iconEvent.getPostion());
        }
    }

    private void onChangeIcon(String postion){
        switch (postion){
            case "1":
                iconUserIcon.setBackground(getResources().getDrawable(R.mipmap.icon_v1));
                break;
            case "2":
                iconUserIcon.setBackground(getResources().getDrawable(R.mipmap.icon_v2));
                break;
            case "3":
                iconUserIcon.setBackground(getResources().getDrawable(R.mipmap.icon_v3));
                break;
            case "4":
                iconUserIcon.setBackground(getResources().getDrawable(R.mipmap.icon_v4));
                break;
            case "5":
                iconUserIcon.setBackground(getResources().getDrawable(R.mipmap.icon_v5));
                break;
            case "6":
                iconUserIcon.setBackground(getResources().getDrawable(R.mipmap.icon_v6));
                break;
            case "7":
                iconUserIcon.setBackground(getResources().getDrawable(R.mipmap.icon_v7));
                break;
        }
    }

    private String getString2Pt(String money){
        DecimalFormat df = new DecimalFormat("0.00");
        //DecimalFormat df = new DecimalFormat("#0.00");//与上一行代码的区别是：#表示如果不存在则显示为空，0表示如果没有则该位补0.
        //DecimalFormat df = new DecimalFormat("#,###.00"); //将数据转换成以3位逗号隔开的字符串，并保留两位小数
        df.setRoundingMode(RoundingMode.FLOOR);//不四舍五入
        GameLog.log("需要格式化的值是 "+money);
        return df.format(Double.parseDouble(money));
    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        String postion = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT_ICON);
        GameLog.log("用户的头像位置是"+postion);
        if(Check.isNull(postion)){
            postion = "5";
            GameLog.log("用户的头像位置是"+postion);
        }
        onChangeIcon(postion);
        String NickName = ACache.get(getContext()).getAsString("NickName");
        String PersonalizedSignature = ACache.get(getContext()).getAsString("PersonalizedSignature");
        String money = ACache.get(getContext()).getAsString("Money");
        String ID = ACache.get(getContext()).getAsString("ID");
        iconEditName.setText(NickName);
        iconUserMoney.setText(getString2Pt(money));
        iconUserId.setText(ID);
        iconEditUserSignature.setText(PersonalizedSignature);
        iconEditName.setOnFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(View v, boolean hasFocus) {
                if(hasFocus){
                    changePostion =1;
                }
            }
        });
        iconEditUserSignature.setOnFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(View v, boolean hasFocus) {
                if(hasFocus){
                    changePostion =2;
                }
            }
        });
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
//        hide();
        GameLog.log("修改的icon "+changIconResult.getAvatarId());
    }

    @Override
    public void postChangeNickNameResult(NickNameResult nickNameResult) {
        ACache.get(getContext()).put("NickName",nickNameResult.getNickName());
        EventBus.getDefault().post(new NickNameEvent(nickNameResult.getNickName()));
    }

    @Override
    public void postChangeSignWordsResult(PSignatureResult pSignatureResult) {
        ACache.get(getContext()).put("PersonalizedSignature",pSignatureResult.getPersonalizedSignature());
    }

    @Override
    public void setPresenter(IconContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @OnClick({R.id.iconFlayBg,R.id.iconUserIcon, R.id.iconEditNameBi, R.id.iconCopyId, R.id.iconUserMoney, R.id.iconEditUserSignatureBi, R.id.iconChangeIcon, R.id.iconBindPhone, R.id.iconChangeUser, R.id.iconClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.iconFlayBg:
                GameLog.log("当前点击的位置上是 "+changePostion);
                if(changePostion==0){
                }else if(changePostion==1){//昵称
                    String NickName = ACache.get(getContext()).getAsString("NickName");
                    String nickName = iconEditName.getText().toString().trim();
                    iconEditName.clearFocus();
                    if(!nickName.equals(NickName)){
                        presenter.postChangeNickName("","nickname_save",nickName);
                    }
                }else if(changePostion==2){//签名
                    String PersonalizedSignature = ACache.get(getContext()).getAsString("PersonalizedSignature");
                    String PersonalizedSignature2 = iconEditUserSignature.getText().toString().trim();
                    iconEditUserSignature.clearFocus();
                    if(!PersonalizedSignature2.equals(PersonalizedSignature)){
                        presenter.postChangeSignWords("","personalizedsignature_save",PersonalizedSignature2);
                    }
                }

                iconUserIcon.requestFocus();
                changePostion =0;
                hideKeyboard();
                break;
            case R.id.iconUserIcon:
                break;
            case R.id.iconEditNameBi:
                iconEditName.requestFocus();
                iconEditName.setClickable(true);
                iconEditName.setSelection(iconEditName.getText().toString().length());
                InputMethodUtils.showSoftInput(iconEditName);
                break;
            case R.id.iconCopyId:
                CLipHelper.copy(Utils.getContext(),iconUserId.getText().toString().replace("ID:",""));
                showMessage("复制成功！");
                break;
            case R.id.iconUserMoney:
                break;
            case R.id.iconEditUserSignatureBi:
                iconEditUserSignature.requestFocus();
                iconEditUserSignature.setClickable(true);
                iconEditUserSignature.setSelection(iconEditUserSignature.getText().toString().length());
                InputMethodUtils.showSoftInput(iconEditUserSignature);
                break;
            case R.id.iconChangeIcon:
                ChageIconFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.iconBindPhone:
                hide();
                BindFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.iconChangeUser:
                ACache.get(getContext()).put("isChangeUser","YES");
                EventBus.getDefault().post(new ChangeAccountEvent());
                break;
            case R.id.iconClose:
                hide();
                break;
        }
    }
}
