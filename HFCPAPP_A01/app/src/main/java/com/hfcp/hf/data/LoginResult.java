package com.hfcp.hf.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.List;

public class LoginResult implements Parcelable {


    /**
     * id : 5379
     * is_agent : 1
     * username : lincoin01
     * parent_id : 5367
     * forefather_ids : 5367
     * parent : ceshi001
     * forefathers : ceshi001
     * account_id : 5367
     * prize_group : 1950
     * blocked : 0
     * portrait_code : 1
     * name : 张三三
     * nickname : lincoin01
     * email : null
     * mobile :
     * is_tester : 0
     * qq :
     * skype : null
     * bet_coefficient : null
     * login_ip : 119.92.13.181
     * signin_at : 2019-02-23 13:00:42
     * register_at : 2019-02-11 15:39:35
     * fund_password_exist : false
     * abalance : 1020.0000
     * token : 1a043083621f0a59eaa3f0550234d7fa7f6a8014
     * NoticeList : [{"id":112,"category_id":2,"title":"【升级通知】升级维护通知2019年01月10日03:00-08:00","summary":"","content":"","search_text":"彩易博\u200b升级维护","author_id":59,"author":"admin","auditor_id":59,"auditor":"admin","is_top":0,"status":1,"need_read_log":1,"read_count":888,"created_at":"2019-01-10 02:25:18","updated_at":"2019-01-10 02:25:18","sequence":0},{"id":99,"category_id":2,"title":"【重要通知】请认准 彩易博 官方平台","summary":"【重要通知】请认准 彩易博 官方平台","content":"","search_text":"","author_id":59,"author":"admin","auditor_id":68,"auditor":"paigu","is_top":1,"status":1,"need_read_log":1,"read_count":1,"created_at":"2018-06-28 21:27:40","updated_at":"2018-12-11 13:58:14","sequence":1}]
     */

    private int id;
    private int is_agent;
    private String username;
    private int parent_id;
    private String forefather_ids;
    private String parent;
    private String forefathers;
    private int account_id;
    private String prize_group;
    private int blocked;
    private int portrait_code;
    private String name;
    private String nickname;
    private String email;
    private String mobile;
    private int is_tester;
    private String qq;
    private String chat_domain;
    private String skype;
    private String bet_coefficient;
    private String login_ip;
    private String signin_at;
    private String register_at;
    private boolean fund_password_exist;
    private String abalance;
    private String token;
    private List<NoticeListBean> NoticeList;

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getIs_agent() {
        return is_agent;
    }

    public void setIs_agent(int is_agent) {
        this.is_agent = is_agent;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public int getParent_id() {
        return parent_id;
    }

    public void setParent_id(int parent_id) {
        this.parent_id = parent_id;
    }

    public String getForefather_ids() {
        return forefather_ids;
    }

    public void setForefather_ids(String forefather_ids) {
        this.forefather_ids = forefather_ids;
    }

    public String getParent() {
        return parent;
    }

    public void setParent(String parent) {
        this.parent = parent;
    }

    public String getForefathers() {
        return forefathers;
    }

    public void setForefathers(String forefathers) {
        this.forefathers = forefathers;
    }

    public int getAccount_id() {
        return account_id;
    }

    public void setAccount_id(int account_id) {
        this.account_id = account_id;
    }

    public String getPrize_group() {
        return prize_group;
    }

    public void setPrize_group(String prize_group) {
        this.prize_group = prize_group;
    }

    public int getBlocked() {
        return blocked;
    }

    public void setBlocked(int blocked) {
        this.blocked = blocked;
    }

    public int getPortrait_code() {
        return portrait_code;
    }

    public void setPortrait_code(int portrait_code) {
        this.portrait_code = portrait_code;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getNickname() {
        return nickname;
    }

    public void setNickname(String nickname) {
        this.nickname = nickname;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getMobile() {
        return mobile;
    }

    public void setMobile(String mobile) {
        this.mobile = mobile;
    }

    public int getIs_tester() {
        return is_tester;
    }

    public void setIs_tester(int is_tester) {
        this.is_tester = is_tester;
    }

    public String getQq() {
        return qq;
    }

    public void setQq(String qq) {
        this.qq = qq;
    }

    public String getChat_domain() {
        return chat_domain;
    }

    public void setChat_domain(String chat_domain) {
        this.chat_domain = chat_domain;
    }

    public String getSkype() {
        return skype;
    }

    public void setSkype(String skype) {
        this.skype = skype;
    }

    public String getBet_coefficient() {
        return bet_coefficient;
    }

    public void setBet_coefficient(String bet_coefficient) {
        this.bet_coefficient = bet_coefficient;
    }

    public String getLogin_ip() {
        return login_ip;
    }

    public void setLogin_ip(String login_ip) {
        this.login_ip = login_ip;
    }

    public String getSignin_at() {
        return signin_at;
    }

    public void setSignin_at(String signin_at) {
        this.signin_at = signin_at;
    }

    public String getRegister_at() {
        return register_at;
    }

    public void setRegister_at(String register_at) {
        this.register_at = register_at;
    }

    public boolean isFund_password_exist() {
        return fund_password_exist;
    }

    public void setFund_password_exist(boolean fund_password_exist) {
        this.fund_password_exist = fund_password_exist;
    }

    public String getAbalance() {
        return abalance;
    }

    public void setAbalance(String abalance) {
        this.abalance = abalance;
    }

    public String getToken() {
        return token;
    }

    public void setToken(String token) {
        this.token = token;
    }

    public List<NoticeListBean> getNoticeList() {
        return NoticeList;
    }

    public void setNoticeList(List<NoticeListBean> NoticeList) {
        this.NoticeList = NoticeList;
    }

    public static class NoticeListBean implements Parcelable {
        /**
         * id : 112
         * category_id : 2
         * title : 【升级通知】升级维护通知2019年01月10日03:00-08:00
         * summary :
         * content :
         * search_text : 彩易博​升级维护
         * author_id : 59
         * author : admin
         * auditor_id : 59
         * auditor : admin
         * is_top : 0
         * status : 1
         * need_read_log : 1
         * read_count : 888
         * created_at : 2019-01-10 02:25:18
         * updated_at : 2019-01-10 02:25:18
         * sequence : 0
         */
        private int checked ;

        private int id;
        private int category_id;
        private String title;
        private String summary;
        private String content;
        private String search_text;
        private int author_id;
        private String author;
        private int auditor_id;
        private String auditor;
        private int is_top;
        private int status;
        private int need_read_log;
        private int read_count;
        private String created_at;
        private String updated_at;
        private int sequence;


        public int getChecked() {
            return checked;
        }

        public void setChecked(int ischecked) {
            this.checked = ischecked;
        }

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public int getCategory_id() {
            return category_id;
        }

        public void setCategory_id(int category_id) {
            this.category_id = category_id;
        }

        public String getTitle() {
            return title;
        }

        public void setTitle(String title) {
            this.title = title;
        }

        public String getSummary() {
            return summary;
        }

        public void setSummary(String summary) {
            this.summary = summary;
        }

        public String getContent() {
            return content;
        }

        public void setContent(String content) {
            this.content = content;
        }

        public String getSearch_text() {
            return search_text;
        }

        public void setSearch_text(String search_text) {
            this.search_text = search_text;
        }

        public int getAuthor_id() {
            return author_id;
        }

        public void setAuthor_id(int author_id) {
            this.author_id = author_id;
        }

        public String getAuthor() {
            return author;
        }

        public void setAuthor(String author) {
            this.author = author;
        }

        public int getAuditor_id() {
            return auditor_id;
        }

        public void setAuditor_id(int auditor_id) {
            this.auditor_id = auditor_id;
        }

        public String getAuditor() {
            return auditor;
        }

        public void setAuditor(String auditor) {
            this.auditor = auditor;
        }

        public int getIs_top() {
            return is_top;
        }

        public void setIs_top(int is_top) {
            this.is_top = is_top;
        }

        public int getStatus() {
            return status;
        }

        public void setStatus(int status) {
            this.status = status;
        }

        public int getNeed_read_log() {
            return need_read_log;
        }

        public void setNeed_read_log(int need_read_log) {
            this.need_read_log = need_read_log;
        }

        public int getRead_count() {
            return read_count;
        }

        public void setRead_count(int read_count) {
            this.read_count = read_count;
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

        public int getSequence() {
            return sequence;
        }

        public void setSequence(int sequence) {
            this.sequence = sequence;
        }

        @Override
        public int describeContents() {
            return 0;
        }

        @Override
        public void writeToParcel(Parcel dest, int flags) {
            dest.writeInt(this.checked);
            dest.writeInt(this.id);
            dest.writeInt(this.category_id);
            dest.writeString(this.title);
            dest.writeString(this.summary);
            dest.writeString(this.content);
            dest.writeString(this.search_text);
            dest.writeInt(this.author_id);
            dest.writeString(this.author);
            dest.writeInt(this.auditor_id);
            dest.writeString(this.auditor);
            dest.writeInt(this.is_top);
            dest.writeInt(this.status);
            dest.writeInt(this.need_read_log);
            dest.writeInt(this.read_count);
            dest.writeString(this.created_at);
            dest.writeString(this.updated_at);
            dest.writeInt(this.sequence);
        }

        public NoticeListBean() {
        }

        protected NoticeListBean(Parcel in) {
            this.checked = in.readInt();
            this.id = in.readInt();
            this.category_id = in.readInt();
            this.title = in.readString();
            this.summary = in.readString();
            this.content = in.readString();
            this.search_text = in.readString();
            this.author_id = in.readInt();
            this.author = in.readString();
            this.auditor_id = in.readInt();
            this.auditor = in.readString();
            this.is_top = in.readInt();
            this.status = in.readInt();
            this.need_read_log = in.readInt();
            this.read_count = in.readInt();
            this.created_at = in.readString();
            this.updated_at = in.readString();
            this.sequence = in.readInt();
        }

        public static final Parcelable.Creator<NoticeListBean> CREATOR = new Parcelable.Creator<NoticeListBean>() {
            @Override
            public NoticeListBean createFromParcel(Parcel source) {
                return new NoticeListBean(source);
            }

            @Override
            public NoticeListBean[] newArray(int size) {
                return new NoticeListBean[size];
            }
        };
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeInt(this.id);
        dest.writeInt(this.is_agent);
        dest.writeString(this.username);
        dest.writeInt(this.parent_id);
        dest.writeString(this.forefather_ids);
        dest.writeString(this.parent);
        dest.writeString(this.forefathers);
        dest.writeInt(this.account_id);
        dest.writeString(this.prize_group);
        dest.writeInt(this.blocked);
        dest.writeInt(this.portrait_code);
        dest.writeString(this.name);
        dest.writeString(this.nickname);
        dest.writeString(this.email);
        dest.writeString(this.mobile);
        dest.writeInt(this.is_tester);
        dest.writeString(this.qq);
        dest.writeString(this.chat_domain);
        dest.writeString(this.skype);
        dest.writeString(this.bet_coefficient);
        dest.writeString(this.login_ip);
        dest.writeString(this.signin_at);
        dest.writeString(this.register_at);
        dest.writeByte(this.fund_password_exist ? (byte) 1 : (byte) 0);
        dest.writeString(this.abalance);
        dest.writeString(this.token);
        dest.writeTypedList(this.NoticeList);
    }

    public LoginResult() {
    }

    protected LoginResult(Parcel in) {
        this.id = in.readInt();
        this.is_agent = in.readInt();
        this.username = in.readString();
        this.parent_id = in.readInt();
        this.forefather_ids = in.readString();
        this.parent = in.readString();
        this.forefathers = in.readString();
        this.account_id = in.readInt();
        this.prize_group = in.readString();
        this.blocked = in.readInt();
        this.portrait_code = in.readInt();
        this.name = in.readString();
        this.nickname = in.readString();
        this.email = in.readString();
        this.mobile = in.readString();
        this.is_tester = in.readInt();
        this.qq = in.readString();
        this.chat_domain = in.readString();
        this.skype = in.readString();
        this.bet_coefficient = in.readString();
        this.login_ip = in.readString();
        this.signin_at = in.readString();
        this.register_at = in.readString();
        this.fund_password_exist = in.readByte() != 0;
        this.abalance = in.readString();
        this.token = in.readString();
        this.NoticeList = in.createTypedArrayList(NoticeListBean.CREATOR);
    }

    public static final Parcelable.Creator<LoginResult> CREATOR = new Parcelable.Creator<LoginResult>() {
        @Override
        public LoginResult createFromParcel(Parcel source) {
            return new LoginResult(source);
        }

        @Override
        public LoginResult[] newArray(int size) {
            return new LoginResult[size];
        }
    };
}
