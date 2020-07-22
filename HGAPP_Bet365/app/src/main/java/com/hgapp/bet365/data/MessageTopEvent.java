package com.hgapp.bet365.data;

public class MessageTopEvent {
    public int prePosition;
    public  String EventMessage;

    public MessageTopEvent(){}

    public MessageTopEvent(String eventMessage) {
        EventMessage = eventMessage;
    }

    public MessageTopEvent(int prePosition, String eventMessage) {
        this.prePosition = prePosition;
        EventMessage = eventMessage;
    }

    public String getEventMessage() {
        return EventMessage;
    }

    public void setEventMessage(String eventMessage) {
        EventMessage = eventMessage;
    }

    public int getPrePosition() {
        return prePosition;
    }

    public void setPrePosition(int prePosition) {
        this.prePosition = prePosition;
    }
}
