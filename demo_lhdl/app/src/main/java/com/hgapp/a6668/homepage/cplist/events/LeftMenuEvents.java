package com.hgapp.a6668.homepage.cplist.events;

public class LeftMenuEvents {
    private String eventName;
    private String eventId;

    public LeftMenuEvents(String eventName, String eventId) {
        this.eventName = eventName;
        this.eventId = eventId;
    }

    public String getEventName() {
        return eventName;
    }

    public void setEventName(String eventName) {
        this.eventName = eventName;
    }

    public String getEventId() {
        return eventId;
    }

    public void setEventId(String eventId) {
        this.eventId = eventId;
    }

    @Override
    public String toString() {
        return "LeftMenuEvents{" +
                "eventName='" + eventName + '\'' +
                ", eventId='" + eventId + '\'' +
                '}';
    }
}
