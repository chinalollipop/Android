package com.hgapp.a0086.homepage.cplist.quickbet;


import com.hgapp.a0086.common.util.SubscriptionHelper;

/**
 * Created by Daniel on 2017/5/31.
 */

public class QuickBetPresenter implements QuickBetContract.Presenter {
    private QuickBetContract.View view;
    private IQuickBetApi api;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public QuickBetPresenter(IQuickBetApi api,QuickBetContract.View view)
    {
        this.view = view;
        view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void logout() {

    }

    @Override
    public void start() {

    }

    @Override
    public void destroy() {
       /* subscriptionHelper.unsubscribe();
        view = null;
        api = null;*/
    }
}
