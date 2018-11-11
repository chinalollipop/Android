package com.hgapp.a6668.homepage.cplist;

import java.util.List;

public class CPOrderContentListResult {
    private List<CPOrderContentResult> data;
    private String orderContentListName;

    public List<CPOrderContentResult> getData() {
        return data;
    }

    public void setData(List<CPOrderContentResult> data) {
        this.data = data;
    }

    public String getOrderContentListName() {
        return orderContentListName;
    }

    public void setOrderContentListName(String orderContentListName) {
        this.orderContentListName = orderContentListName;
    }
}
