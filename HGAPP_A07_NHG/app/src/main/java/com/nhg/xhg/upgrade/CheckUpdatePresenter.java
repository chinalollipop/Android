package com.nhg.xhg.upgrade;

import com.nhg.common.util.Check;
import com.nhg.xhg.common.http.ResponseSubscriber;
import com.nhg.xhg.common.http.request.AppTextMessageResponse;
import com.nhg.xhg.common.util.RxHelper;
import com.nhg.xhg.common.util.SubscriptionHelper;
import com.nhg.xhg.data.CheckUpgradeResult;

/**
 * Created by Daniel on 2018/7/29.
 */

public class CheckUpdatePresenter implements CheckUpdateContract.Presenter {
    private CheckUpdateContract.View view;
    private ICheckVerUpdateApi api;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CheckUpdatePresenter(CheckUpdateContract.View view, ICheckVerUpdateApi api)
    {
        this.view = view;
        view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void checkupdate() {
        /*PackageInfo packageInfo =  PackageUtil.getAppPackageInfo(Utils.getContext());
        if(null == packageInfo)
        {
            Timber.e("检查更新失败，获取不到app版本号");
            throw new RuntimeException("检查更新失败，获取不到app版本号");
        }
        String localver = packageInfo.versionName;*/
        subscriptionHelper.add(RxHelper.addSugar(api.checkupdate())
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CheckUpgradeResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CheckUpgradeResult> response) {
                        if(null == view)
                        {
                            return;
                        }
                        if(response.isSuccess())
                        {
                            CheckUpgradeResult checkUpgradeResult = response.getData();
                            view.setData(checkUpgradeResult);
                            view.setComplete(ACTION);
                        }
                        else
                        {
                            view.setError(ACTION,1);
                            if(view.wantShowMessage())
                            {
                                if(!Check.isEmpty(response.getDescribe()))
                                {
                                    view.showMessage(response.getDescribe());
                                }
                                else
                                {
                                    view.showMessage("检查更新失败消息");//ResHelper.getString(R.string.str_checkver_fail)
                                }
                            }
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(ACTION,0);
                            if(view.wantShowMessage())
                            {
                                view.showMessage("检查更新失败消息");
                            }
                        }
                    }
                }));
    }

    @Override
    public void start() {
      /*  if (null != view)
        {
            checkupdate();
        }*/
    }

    @Override
    public void destroy() {
        view = null;
        api = null;
        subscriptionHelper.unsubscribe();
    }
}
