package com.qpweb.a01.ui.home.icon;

public class ChangeIcon {
    private String name;
    private int id;
    private boolean check;

    public ChangeIcon(String name, int id, boolean check) {
        this.name = name;
        this.id = id;
        this.check = check;
    }

    public ChangeIcon() {
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public boolean isCheck() {
        return check;
    }

    public void setCheck(boolean check) {
        this.check = check;
    }
}
