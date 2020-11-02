package com.hgapp.betnhg.common.event;

public class LogoutEvent {
    private String eventName;

    public LogoutEvent(String eventName) {
        this.eventName = eventName;
    }

    public String getEventName() {
        return eventName;
    }

    public void setEventName(String eventName) {
        this.eventName = eventName;
    }
}
