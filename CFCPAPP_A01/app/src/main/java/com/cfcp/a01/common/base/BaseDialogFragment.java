package com.cfcp.a01.common.base;

import android.os.Bundle;
import android.support.annotation.CallSuper;
import android.support.annotation.Nullable;
import android.support.v4.app.DialogFragment;
import android.support.v4.app.FragmentManager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;

import com.cfcp.a01.R;
import com.cfcp.a01.common.utils.InputMethodUtils;
import com.cfcp.a01.common.utils.Timber;
import com.cfcp.a01.common.utils.ToastUtils;
import com.zhy.autolayout.utils.AutoUtils;

import java.util.List;

import butterknife.ButterKnife;
import butterknife.Unbinder;

/**
 * Created by Daniel on 2018/7/4.
 */

public abstract class BaseDialogFragment extends DialogFragment implements IMessageView{
    private Unbinder unbinder;
    private final boolean LOG_ON = true;
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setStyle(DialogFragment.STYLE_NO_TITLE, R.style.QPDialog);
        setCancelable(false);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        getDialog().getWindow().requestFeature(Window.FEATURE_NO_TITLE);
        getDialog().setCanceledOnTouchOutside(false);
        if(LOG_ON)
        {
            Timber.d("onCreateView " + this.getClass().getSimpleName());
        }
        View v = inflater.inflate(setLayoutId(), container, false);
        unbinder = ButterKnife.bind(this,v);
        AutoUtils.auto(v);
        return v;
    }

    @Override
    public void showMessage(String message) {
        ToastUtils.showLongToast(message);
    }

    public void show(FragmentManager fragmentManager) {
        show(fragmentManager, "dialogfragment");
    }

    public void hide()
    {
        dismiss();
    }


    @CallSuper
    @Override
    public void onViewCreated(View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view,savedInstanceState);
        if(LOG_ON)
        {
            Timber.d("onViewCreated " + this.getClass().getSimpleName());
        }
        setEvents(view,savedInstanceState);
        //初始化控制器
        if(null != presenters())
        {
            for(IPresenter presenter : presenters())
            {
                if(null != presenter)
                {
                    presenter.start();
                }
            }
        }
    }

    @Override
    public void onStart()
    {
        super.onStart();
        if(LOG_ON)
        {
            Timber.d("onStart"+ this.getClass().getSimpleName());
        }
    }
    @Override
    public void onResume()
    {
        super.onResume();
        if(LOG_ON)
        {
            Timber.d("onResume"+ this.getClass().getSimpleName());
        }
    }
    @Override
    public void onPause()
    {
        super.onPause();
        if(LOG_ON)
        {
            Timber.d("onPause"+ this.getClass().getSimpleName());
        }
    }
    @Override
    public void onStop()
    {
        super.onStop();
        if(LOG_ON)
        {
            Timber.d("onStop"+ this.getClass().getSimpleName());
        }
    }

    @CallSuper
    @Override
    public void onDestroyView() {
        super.onDestroyView();
        if(null!=unbinder){
            unbinder.unbind();
        }
        if(LOG_ON)
        {
            Timber.d("onDestroyView"+ this.getClass().getSimpleName());
        }
        //销毁控制器
        if(null != presenters())
        {
            for(IPresenter presenter : presenters())
            {
                if(null != presenter)
                {
                    presenter.destroy();
                }
            }
        }
    }

    protected void showKeyboard()
    {
        InputMethodUtils.showSoftInput(getView());
    }
    protected void hideKeyboard()
    {
        InputMethodUtils.hideSoftInput(getView());
    }
    protected void hideKeyboard(View view)
    {
        InputMethodUtils.hideSoftInput(view);
    }
    /**
     * 如果要让{@code PNBaseFragment}管理控制器，请复写此方法
     * @return
     */
    protected List<IPresenter> presenters()
    {
        return null;
    }
    protected abstract int setLayoutId();
    protected abstract void setEvents(View view,Bundle bundle);
}
