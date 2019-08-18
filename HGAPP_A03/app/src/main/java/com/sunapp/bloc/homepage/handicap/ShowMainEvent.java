package com.sunapp.bloc.homepage.handicap;

public class ShowMainEvent {
    public int showNumber;

    public ShowMainEvent(int showNumber) {
        this.showNumber = showNumber;
    }

    public int getShowNumber() {
        return showNumber;
    }

    public void setShowNumber(int showNumber) {
        this.showNumber = showNumber;
    }
}
