package com.cfcp.a01.common.http;


import java.util.ArrayList;

import rx.Subscription;


/**
 * Created by daniel on 2017/5/15.
 * 取消订阅的辅助类
 */

public class SubscriptionHelper {

    private ArrayList<Subscription> subscriptions = new ArrayList<>();

    public void add(Subscription subscription) {
        subscriptions.add(subscription);
    }

    public boolean isEmpty() {
        return subscriptions.isEmpty();
    }

    public void unsubscribe() {
        if (!subscriptions.isEmpty()) {
            for (Subscription subscription : subscriptions) {
                if (null != subscription && !subscription.isUnsubscribed()) {
                    subscription.unsubscribe();
                }
            }
        }
        subscriptions.clear();
    }
}
