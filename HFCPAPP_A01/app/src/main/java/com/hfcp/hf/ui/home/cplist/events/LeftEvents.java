package com.hfcp.hf.ui.home.cplist.events;

public class LeftEvents {
    private String eventName;
    private String eventId;
    private boolean eventChecked;

    public LeftEvents(String eventName, String eventId, boolean eventChecked) {
        this.eventName = eventName;
        this.eventId = eventId;
        this.eventChecked = eventChecked;
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

    public boolean isEventChecked() {
        return eventChecked;
    }

    public void setEventChecked(boolean eventChecked) {
        this.eventChecked = eventChecked;
    }
}
