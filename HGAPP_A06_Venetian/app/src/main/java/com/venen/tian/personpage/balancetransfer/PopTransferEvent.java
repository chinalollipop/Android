package com.venen.tian.personpage.balancetransfer;

public class PopTransferEvent {
    public PopTransferEvent(boolean status, String message) {
        this.status = status;
        this.message = message;
    }

    /**
     * status : true
     * message : Could not find resource
     */

    private boolean status;
    private String message;

    public boolean isStatus() {
        return status;
    }

    public void setStatus(boolean status) {
        this.status = status;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }
}
