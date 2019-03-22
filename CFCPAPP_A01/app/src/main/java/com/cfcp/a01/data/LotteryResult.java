package com.cfcp.a01.data;

import java.io.Serializable;
import java.util.List;

public class LotteryResult implements Serializable {

    private String places;
    private List<String> option;
    private List<String> data;

    public List<String> getOption() {
        return option;
    }

    public void setOption(List<String> option) {
        this.option = option;
    }

    public String getPlaces() {
        return places;
    }

    public void setPlaces(String places) {
        this.places = places;
    }

    public List<String> getData() {
        return data;
    }

    public void setData(List<String> data) {
        this.data = data;
    }
}
