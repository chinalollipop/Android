package com.cfcp.a01.ui.main.upgrade;

import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.http.request.AppTextMessageResponseList;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.data.CheckUpgradeResult;

import java.util.LinkedHashMap;
import java.util.Map;

/**
 * Created by Daniel on 2018/7/29.
 */

public class CheckUpdatePresenter implements CheckUpdateContract.Presenter {
    private CheckUpdateContract.View view;
    private ICheckVerUpdateApi api;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CheckUpdatePresenter(CheckUpdateContract.View view, ICheckVerUpdateApi api) {
        this.view = view;
        view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void checkupdate() {
        /*PackageInfo packageInfo =  PackageUtil.getAppPackageInfo(Utils.getContext());
        if(null == packageInfo)
        {
            throw new RuntimeException("检查更新失败，获取不到app版本号");
        }
        String localver = packageInfo.versionName;*/
        Map<String, String> params = new LinkedHashMap<>();
        params.put("packet", "Release");
        params.put("action", "GetLatestRelease");
        params.put("terminal_id", "2");
        subscriptionHelper.add(RxHelper.addSugar(api.checkupdate(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<CheckUpgradeResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<CheckUpgradeResult> response) {
                        if (null == view) {
                            return;
                        }
                        if (response.isSuccess()) {
                            CheckUpgradeResult checkUpgradeResult = response.getData().get(0);
                            view.wantShowMessage(checkUpgradeResult);
                        } else {
                            if (!Check.isEmpty(response.getDescribe())) {
                                view.showMessage(response.getDescribe());
                            } else {
                                view.showMessage("检查更新失败消息");//ResHelper.getString(R.string.str_checkver_fail)
                            }
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.showMessage("检查更新失败消息");
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
        /*view = null;
        api = null;*/
        subscriptionHelper.unsubscribe();
    }
}
