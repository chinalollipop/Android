package com.gmcp.gm.ui.home.cplist;

import java.util.List;

public class CPOrderAllResult {
    private List<CPOrderContentListResult> data;
    private String orderAllName;
    private String type;
    private boolean isEventChecked;
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

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public boolean isEventChecked() {
        return isEventChecked;
    }

    public void setEventChecked(boolean eventChecked) {
        isEventChecked = eventChecked;
    }
}
