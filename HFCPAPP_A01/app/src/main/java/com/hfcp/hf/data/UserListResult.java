package com.hfcp.hf.data;

public class UserListResult {

    /**
     * id : 5523
     * username : daniel03
     * user_type_formatted : 玩家
     * prize_group : 1949
     * children_num : 0
     * register_at : 2019-03-17 09:14:03
     * signin_at :
     * available : 0.000000
     * group_available : 0.000000
     */

    private boolean checked;
    private int id;
    private String username;
    private String user_type_formatted;
    private String prize_group;
    private int children_num;
    private String register_at;
    private String signin_at;
    private String available;
    private String group_available;

    public boolean isChecked() {
        return checked;
    }

    public void setChecked(boolean checked) {
        this.checked = checked;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getUser_type_formatted() {
        return user_type_formatted;
    }

    public void setUser_type_formatted(String user_type_formatted) {
        this.user_type_formatted = user_type_formatted;
    }

    public String getPrize_group() {
        return prize_group;
    }

    public void setPrize_group(String prize_group) {
        this.prize_group = prize_group;
    }

    public int getChildren_num() {
        return children_num;
    }

    public void setChildren_num(int children_num) {
        this.children_num = children_num;
    }

    public String getRegister_at() {
        return register_at;
    }

    public void setRegister_at(String register_at) {
        this.register_at = register_at;
    }

    public String getSignin_at() {
        return signin_at;
    }

    public void setSignin_at(String signin_at) {
        this.signin_at = signin_at;
    }

    public String getAvailable() {
        return available;
    }

    public void setAvailable(String available) {
        this.available = available;
    }

    public String getGroup_available() {
        return group_available;
    }

    public void setGroup_available(String group_available) {
        this.group_available = group_available;
    }
}
