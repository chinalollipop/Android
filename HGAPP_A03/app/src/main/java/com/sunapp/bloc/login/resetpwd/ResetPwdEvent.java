package com.sunapp.bloc.login.resetpwd;

public class ResetPwdEvent {
    private String name;
    private String pwd;

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getPwd() {
        return pwd;
    }

    public void setPwd(String pwd) {
        this.pwd = pwd;
    }

    public ResetPwdEvent(String name, String pwd) {
        this.name = name;
        this.pwd = pwd;
    }
}
