package com.hg3366.a3366.homepage.cplist;

import java.util.List;

public class CPOrderContentListResult {
    private List<CPOrderContentResult> data;
    private String orderContentListName;
    private String showType = "ZI";
    private int showNumber = 2;

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

    public String getShowType() {
        return showType;
    }

    public void setShowType(String showType) {
        this.showType = showType;
    }

    public int getShowNumber() {
        return showNumber;
    }

    public void setShowNumber(int showNumber) {
        this.showNumber = showNumber;
    }
}
