package com.hgapp.a6668.homepage.cplist;

import java.util.List;

public class CPOrderAllResult {
    private List<CPOrderContentListResult> data;
    private String orderAllName;

    public List<CPOrderContentListResult> getData() {
        return data;
    }

    public void setData(List<CPOrderContentListResult> data) {
        this.data = data;
    }

    public String getOrderAllName() {
        return orderAllName;
    }

    public void setOrderAllName(String orderAllName) {
        this.orderAllName = orderAllName;
    }
}
