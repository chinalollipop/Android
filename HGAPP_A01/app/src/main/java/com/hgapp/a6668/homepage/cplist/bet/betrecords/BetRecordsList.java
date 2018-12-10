package com.hgapp.a6668.homepage.cplist.bet.betrecords;

import com.hgapp.a6668.data.BetRecordsResult;

import java.util.ArrayList;
import java.util.List;

public class BetRecordsList {
     String recordsname;
     List<BetRecordsResult.ThisWeekBean.data1Bean> arrayListData;

    public String getRecordsname() {
        return recordsname;
    }

    public void setRecordsname(String recordsname) {
        this.recordsname = recordsname;
    }

    public List<BetRecordsResult.ThisWeekBean.data1Bean> getArrayListData() {
        return arrayListData;
    }

    public void setArrayListData(List<BetRecordsResult.ThisWeekBean.data1Bean> arrayListData) {
        this.arrayListData = arrayListData;
    }
}
