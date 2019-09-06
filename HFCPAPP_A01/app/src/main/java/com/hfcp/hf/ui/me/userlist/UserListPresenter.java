package com.hfcp.hf.ui.me.userlist;

import com.hfcp.hf.CFConstant;
import com.hfcp.hf.common.http.ResponseSubscriber;
import com.hfcp.hf.common.http.RxHelper;
import com.hfcp.hf.common.http.SubscriptionHelper;
import com.hfcp.hf.common.http.request.AppTextMessageResponseList;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.data.UserListResult;

import java.util.HashMap;
import java.util.Map;

import static com.hfcp.hf.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class UserListPresenter implements UserListContract.Presenter {

    private IUserListApi api;
    private UserListContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public UserListPresenter(IUserListApi api, UserListContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getUserList(String parent_id, String end_date) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","GetDirectChildren");
        params.put("parent_id",ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_USER_ID));
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getUserList(params))//CFConstant.PRODUCT_PLATFORM, "User", "Login", username, password, "1"
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<UserListResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<UserListResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getUserListResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                        //view.postLoginResult(response.getData());
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.showMessage(msg);
                        }
                    }
                }));
    }


    @Override
    public void start() {

    }

    @Override
    public void destroy() {

        subscriptionHelper.unsubscribe();
        view = null;
        api = null;
    }


}

