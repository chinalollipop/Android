package com.gmcp.gm.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.List;

public class BankCardListResult {

    /**
     * iLimitCardsNum : 4
     * aStatus : ["Not In Use","In Use","Deleted","Black List"]
     * bLocked : 0
     * aBankCards : [{"id":2438,"bank_card_id":2402,"user_id":5379,"username":"lincoin01","parent_user_id":5367,"parent_username":"ceshi001","user_forefather_ids":"5367","user_forefathers":"ceshi001","bank_id":4,"bank":"中国银行","province_id":1,"province":"北京","city_id":35,"city":"东城区","branch":"国贸","branch_address":"北京东城区国贸","account_name":"张三三","account":"1234123412341234","status":1,"islock":0,"is_agent":1,"is_tester":0,"locker":null,"lock_time":null,"unlocker":null,"unlock_time":null,"deleted_at":null,"created_at":"2019-02-27 13:38:22","updated_at":"2019-02-27 13:38:22"}]
     */

    private int iLimitCardsNum;
    private int bLocked;
    private List<String> aStatus;
    private List<ABankCardsBean> aBankCards;

    public int getILimitCardsNum() {
        return iLimitCardsNum;
    }

    public void setILimitCardsNum(int iLimitCardsNum) {
        this.iLimitCardsNum = iLimitCardsNum;
    }

    public int getBLocked() {
        return bLocked;
    }

    public void setBLocked(int bLocked) {
        this.bLocked = bLocked;
    }

    public List<String> getAStatus() {
        return aStatus;
    }

    public void setAStatus(List<String> aStatus) {
        this.aStatus = aStatus;
    }

    public List<ABankCardsBean> getABankCards() {
        return aBankCards;
    }

    public void setABankCards(List<ABankCardsBean> aBankCards) {
        this.aBankCards = aBankCards;
    }

    public static class ABankCardsBean implements Parcelable {
        /**
         * id : 2438
         * bank_card_id : 2402
         * user_id : 5379
         * username : lincoin01
         * parent_user_id : 5367
         * parent_username : ceshi001
         * user_forefather_ids : 5367
         * user_forefathers : ceshi001
         * bank_id : 4
         * bank : 中国银行
         * province_id : 1
         * province : 北京
         * city_id : 35
         * city : 东城区
         * branch : 国贸
         * branch_address : 北京东城区国贸
         * account_name : 张三三
         * account : 1234123412341234
         * status : 1
         * islock : 0
         * is_agent : 1
         * is_tester : 0
         * locker : null
         * lock_time : null
         * unlocker : null
         * unlock_time : null
         * deleted_at : null
         * created_at : 2019-02-27 13:38:22
         * updated_at : 2019-02-27 13:38:22
         */

        private  boolean isChecked;

        private int id;
        private int bank_card_id;
        private int user_id;
        private String username;
        private int parent_user_id;
        private String parent_username;
        private String user_forefather_ids;
        private String user_forefathers;
        private int bank_id;
        private String bank;
        private int province_id;
        private String province;
        private int city_id;
        private String city;
        private String branch;
        private String branch_address;
        private String account_name;
        private String account;
        private int status;
        private int islock;
        private int is_agent;
        private int is_tester;
        private String locker;
        private String lock_time;
        private String unlocker;
        private String unlock_time;
        private String deleted_at;
        private String created_at;
        private String updated_at;

        public boolean isChecked() {
            return isChecked;
        }

        public void setChecked(boolean checked) {
            isChecked = checked;
        }

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public int getBank_card_id() {
            return bank_card_id;
        }

        public void setBank_card_id(int bank_card_id) {
            this.bank_card_id = bank_card_id;
        }

        public int getUser_id() {
            return user_id;
        }

        public void setUser_id(int user_id) {
            this.user_id = user_id;
        }

        public String getUsername() {
            return username;
        }

        public void setUsername(String username) {
            this.username = username;
        }

        public int getParent_user_id() {
            return parent_user_id;
        }

        public void setParent_user_id(int parent_user_id) {
            this.parent_user_id = parent_user_id;
        }

        public String getParent_username() {
            return parent_username;
        }

        public void setParent_username(String parent_username) {
            this.parent_username = parent_username;
        }

        public String getUser_forefather_ids() {
            return user_forefather_ids;
        }

        public void setUser_forefather_ids(String user_forefather_ids) {
            this.user_forefather_ids = user_forefather_ids;
        }

        public String getUser_forefathers() {
            return user_forefathers;
        }

        public void setUser_forefathers(String user_forefathers) {
            this.user_forefathers = user_forefathers;
        }

        public int getBank_id() {
            return bank_id;
        }

        public void setBank_id(int bank_id) {
            this.bank_id = bank_id;
        }

        public String getBank() {
            return bank;
        }

        public void setBank(String bank) {
            this.bank = bank;
        }

        public int getProvince_id() {
            return province_id;
        }

        public void setProvince_id(int province_id) {
            this.province_id = province_id;
        }

        public String getProvince() {
            return province;
        }

        public void setProvince(String province) {
            this.province = province;
        }

        public int getCity_id() {
            return city_id;
        }

        public void setCity_id(int city_id) {
            this.city_id = city_id;
        }

        public String getCity() {
            return city;
        }

        public void setCity(String city) {
            this.city = city;
        }

        public String getBranch() {
            return branch;
        }

        public void setBranch(String branch) {
            this.branch = branch;
        }

        public String getBranch_address() {
            return branch_address;
        }

        public void setBranch_address(String branch_address) {
            this.branch_address = branch_address;
        }

        public String getAccount_name() {
            return account_name;
        }

        public void setAccount_name(String account_name) {
            this.account_name = account_name;
        }

        public String getAccount() {
            return account;
        }

        public void setAccount(String account) {
            this.account = account;
        }

        public int getStatus() {
            return status;
        }

        public void setStatus(int status) {
            this.status = status;
        }

        public int getIslock() {
            return islock;
        }

        public void setIslock(int islock) {
            this.islock = islock;
        }

        public int getIs_agent() {
            return is_agent;
        }

        public void setIs_agent(int is_agent) {
            this.is_agent = is_agent;
        }

        public int getIs_tester() {
            return is_tester;
        }

        public void setIs_tester(int is_tester) {
            this.is_tester = is_tester;
        }

        public String getLocker() {
            return locker;
        }

        public void setLocker(String locker) {
            this.locker = locker;
        }

        public String getLock_time() {
            return lock_time;
        }

        public void setLock_time(String lock_time) {
            this.lock_time = lock_time;
        }

        public String getUnlocker() {
            return unlocker;
        }

        public void setUnlocker(String unlocker) {
            this.unlocker = unlocker;
        }

        public String getUnlock_time() {
            return unlock_time;
        }

        public void setUnlock_time(String unlock_time) {
            this.unlock_time = unlock_time;
        }

        public String getDeleted_at() {
            return deleted_at;
        }

        public void setDeleted_at(String deleted_at) {
            this.deleted_at = deleted_at;
        }

        public String getCreated_at() {
            return created_at;
        }

        public void setCreated_at(String created_at) {
            this.created_at = created_at;
        }

        public String getUpdated_at() {
            return updated_at;
        }

        public void setUpdated_at(String updated_at) {
            this.updated_at = updated_at;
        }

        @Override
        public int describeContents() {
            return 0;
        }

        @Override
        public void writeToParcel(Parcel dest, int flags) {
            dest.writeByte(this.isChecked ? (byte) 1 : (byte) 0);
            dest.writeInt(this.id);
            dest.writeInt(this.bank_card_id);
            dest.writeInt(this.user_id);
            dest.writeString(this.username);
            dest.writeInt(this.parent_user_id);
            dest.writeString(this.parent_username);
            dest.writeString(this.user_forefather_ids);
            dest.writeString(this.user_forefathers);
            dest.writeInt(this.bank_id);
            dest.writeString(this.bank);
            dest.writeInt(this.province_id);
            dest.writeString(this.province);
            dest.writeInt(this.city_id);
            dest.writeString(this.city);
            dest.writeString(this.branch);
            dest.writeString(this.branch_address);
            dest.writeString(this.account_name);
            dest.writeString(this.account);
            dest.writeInt(this.status);
            dest.writeInt(this.islock);
            dest.writeInt(this.is_agent);
            dest.writeInt(this.is_tester);
            dest.writeString(this.locker);
            dest.writeString(this.lock_time);
            dest.writeString(this.unlocker);
            dest.writeString(this.unlock_time);
            dest.writeString(this.deleted_at);
            dest.writeString(this.created_at);
            dest.writeString(this.updated_at);
        }

        public ABankCardsBean() {
        }

        protected ABankCardsBean(Parcel in) {
            this.isChecked = in.readByte() != 0;
            this.id = in.readInt();
            this.bank_card_id = in.readInt();
            this.user_id = in.readInt();
            this.username = in.readString();
            this.parent_user_id = in.readInt();
            this.parent_username = in.readString();
            this.user_forefather_ids = in.readString();
            this.user_forefathers = in.readString();
            this.bank_id = in.readInt();
            this.bank = in.readString();
            this.province_id = in.readInt();
            this.province = in.readString();
            this.city_id = in.readInt();
            this.city = in.readString();
            this.branch = in.readString();
            this.branch_address = in.readString();
            this.account_name = in.readString();
            this.account = in.readString();
            this.status = in.readInt();
            this.islock = in.readInt();
            this.is_agent = in.readInt();
            this.is_tester = in.readInt();
            this.locker = in.readString();
            this.lock_time = in.readString();
            this.unlocker = in.readString();
            this.unlock_time = in.readString();
            this.deleted_at = in.readString();
            this.created_at = in.readString();
            this.updated_at = in.readString();
        }

        public static final Parcelable.Creator<ABankCardsBean> CREATOR = new Parcelable.Creator<ABankCardsBean>() {
            @Override
            public ABankCardsBean createFromParcel(Parcel source) {
                return new ABankCardsBean(source);
            }

            @Override
            public ABankCardsBean[] newArray(int size) {
                return new ABankCardsBean[size];
            }
        };
    }
}
