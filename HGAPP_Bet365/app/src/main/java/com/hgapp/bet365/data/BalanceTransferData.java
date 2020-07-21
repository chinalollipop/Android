package com.hgapp.bet365.data;


import com.contrarywind.interfaces.IPickerViewData;

public  class BalanceTransferData implements IPickerViewData {
     String id;
     String cnName;
     String enName;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getCnName() {
        return cnName;
    }

    public void setCnName(String cnName) {
        this.cnName = cnName;
    }

    public String getEnName() {
        return enName;
    }

    public void setEnName(String enName) {
        this.enName = enName;
    }

    public BalanceTransferData(String id, String cnName, String enName) {
        this.id = id;
        this.cnName = cnName;
        this.enName = enName;
    }

    @Override
    public String getPickerViewText() {
        return this.cnName;
    }
}

