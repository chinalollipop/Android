package com.hfcp.hf.data;

import java.io.Serializable;
import java.util.List;

/**
 * Created by Colin on 2019/2/19 1248.
 * event类
 * 更新投注注数及金额
 */

public class UpBetData implements Serializable {

    private List<Integer> selectList;//选择号码列表
    private List<Integer> listSec;//有FootView时的选择列表

    public List<Integer> getSelectList() {
        return selectList;
    }

    public void setSelectList(List<Integer> selectList) {
        this.selectList = selectList;
    }

    public List<Integer> getListSec() {
        return listSec;
    }

    public void setListSec(List<Integer> listSec) {
        this.listSec = listSec;
    }
}
