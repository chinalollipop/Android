package com.cfcp.a01.data;

import android.os.Parcel;
import android.os.Parcelable;

import com.contrarywind.interfaces.IPickerViewData;

import java.util.List;

public class AllGamesResult {


    /**
     * errno : 0
     * error :
     * data : {"XinYongLotteries":[{"id":50,"name":"北京PK拾","identifier":"BJPK10","sub_title":"20分钟1期"},{"id":1,"name":"重庆时时彩","identifier":"CQSSC","sub_title":"20分钟1期"},{"id":55,"name":"幸运飞艇","identifier":"LkShip","sub_title":"20分钟1期"},{"id":70,"name":"香港六合彩","identifier":"MarkSix","sub_title":"每天 21:40开奖"},{"id":66,"name":"PC蛋蛋","identifier":"PCEgg","sub_title":"20分钟1期"},{"id":10,"name":"江苏骰宝(快3)","identifier":"JSQk3","sub_title":""},{"id":51,"name":"极速赛车","identifier":"FastPK10","sub_title":"每分钟1期"},{"id":2,"name":"官方分分彩","identifier":"FastSSC","sub_title":"每分钟1期"},{"id":60,"name":"广东快乐十分","identifier":"GDHp10","sub_title":""},{"id":61,"name":"重庆幸运农场","identifier":"CQFarm","sub_title":"20分钟1期"},{"id":65,"name":"北京快乐8","identifier":"BJHp8","sub_title":""},{"id":21,"name":"广东11选5","identifier":"GD115","sub_title":"20分钟1期"},{"id":4,"name":"阿里二分彩","identifier":"ALISSC","sub_title":"每2分钟1期"},{"id":5,"name":"腾讯三分彩","identifier":"TXSSC","sub_title":"每3分钟1期"},{"id":6,"name":"百度五分彩","identifier":"BDSSC","sub_title":"每5分钟1期"}],"AvailableLottery":[{"id":50,"name":"幸运飞艇","identifier":"XYFT","sub_title":""},{"id":1,"name":"重庆时时彩","identifier":"CQSSC","sub_title":"每二十分钟一期"},{"id":9,"name":"广东11选5","identifier":"GD115","sub_title":"每二十分钟一期"},{"id":10,"name":"北京PK拾","identifier":"BJPK10","sub_title":"每二十分钟一期"},{"id":13,"name":"Gwffc","identifier":"GWFFC","sub_title":"每一分钟一期"},{"id":14,"name":"Gw115","identifier":"GW115","sub_title":"每二十分钟一期"},{"id":15,"name":"江苏快三","identifier":"JSK3","sub_title":"每二十分钟一期"},{"id":16,"name":"Gw3fc","identifier":"GW3FC","sub_title":"每三分钟一期"},{"id":17,"name":"Gwk3ffc","identifier":"GWK3","sub_title":"每一分钟一期"},{"id":19,"name":"Gwpk10","identifier":"GWPK10","sub_title":""},{"id":20,"name":"Gw3d","identifier":"GW3D","sub_title":"每天一期"},{"id":28,"name":"Gw5fc","identifier":"GW5FC","sub_title":"每五分钟一期"},{"id":37,"name":"北京快乐8","identifier":"BJKL8","sub_title":"每二十分钟一期"},{"id":44,"name":"11选5三分彩","identifier":"GW115SFC","sub_title":""}]}
     * sign : a04e8c712bfae2681aa02abee40bb108
     */

    private int errno;
    private String error;
    private DataBean data;
    private String sign;

    public int getErrno() {
        return errno;
    }

    public void setErrno(int errno) {
        this.errno = errno;
    }

    public String getError() {
        return error;
    }

    public void setError(String error) {
        this.error = error;
    }

    public DataBean getData() {
        return data;
    }

    public void setData(DataBean data) {
        this.data = data;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public static class DataBean implements Parcelable {
        private List<LotteriesBean> XinYongLotteries;
        private List<LotteriesBean> ThirdGames;
        private List<LotteriesBean> AvailableLottery;

        public List<LotteriesBean> getXinYongLotteries() {
            return XinYongLotteries;
        }

        public void setXinYongLotteries(List<LotteriesBean> XinYongLotteries) {
            this.XinYongLotteries = XinYongLotteries;
        }

        public List<LotteriesBean> getThirdGames() {
            return ThirdGames;
        }

        public void setThirdGames(List<LotteriesBean> ThirdGames) {
            this.ThirdGames = ThirdGames;
        }

        public List<LotteriesBean> getAvailableLottery() {
            return AvailableLottery;
        }

        public void setAvailableLottery(List<LotteriesBean> AvailableLottery) {
            this.AvailableLottery = AvailableLottery;
        }

        public static class LotteriesBean implements Parcelable, IPickerViewData {
            /**
             * id : 50
             * name : 北京PK拾
             * identifier : BJPK10
             * sub_title : 20分钟1期
             */
            private int id;//信用
            private int lottery_id;//官方
            private String name;
            private String identifier;
            private String sub_title;
            private String type;

            public int getId() {
                return id;
            }

            public void setId(int id) {
                this.id = id;
            }

            public int getLottery_id() {
                return lottery_id;
            }

            public void setLottery_id(int lottery_id) {
                this.lottery_id = lottery_id;
            }

            public String getName() {
                return name;
            }

            public void setName(String name) {
                this.name = name;
            }

            public String getIdentifier() {
                return identifier;
            }

            public void setIdentifier(String identifier) {
                this.identifier = identifier;
            }

            public String getSub_title() {
                return sub_title;
            }

            public void setSub_title(String sub_title) {
                this.sub_title = sub_title;
            }

            public String getType() {
                return type;
            }

            public void setType(String type) {
                this.type = type;
            }

            @Override
            public int describeContents() {
                return 0;
            }

            @Override
            public void writeToParcel(Parcel dest, int flags) {
                dest.writeInt(this.lottery_id);
                dest.writeString(this.name);
                dest.writeString(this.identifier);
                dest.writeString(this.sub_title);
                dest.writeString(this.type);
            }

            public LotteriesBean() {
            }

            public LotteriesBean(int lottery_id,String name,String identifier,String sub_title,String type) {
                this.lottery_id = lottery_id;
                this.name = name;
                this.identifier = identifier;
                this.sub_title =  sub_title;
                this.type =  type;
            }

            protected LotteriesBean(Parcel in) {
                this.lottery_id = in.readInt();
                this.name = in.readString();
                this.identifier = in.readString();
                this.sub_title = in.readString();
                this.type = in.readString();
            }

            public static final Parcelable.Creator<LotteriesBean> CREATOR = new Parcelable.Creator<LotteriesBean>() {
                @Override
                public LotteriesBean createFromParcel(Parcel source) {
                    return new LotteriesBean(source);
                }

                @Override
                public LotteriesBean[] newArray(int size) {
                    return new LotteriesBean[size];
                }
            };

            @Override
            public String getPickerViewText() {
                return this.name;
            }
        }


        @Override
        public int describeContents() {
            return 0;
        }

        @Override
        public void writeToParcel(Parcel dest, int flags) {
            dest.writeTypedList(this.XinYongLotteries);
            dest.writeTypedList(this.ThirdGames);
            dest.writeTypedList(this.AvailableLottery);
        }

        public DataBean() {
        }

        protected DataBean(Parcel in) {
            this.XinYongLotteries = in.createTypedArrayList(LotteriesBean.CREATOR);
            this.ThirdGames = in.createTypedArrayList(LotteriesBean.CREATOR);
            this.AvailableLottery = in.createTypedArrayList(LotteriesBean.CREATOR);
        }

        public static final Parcelable.Creator<DataBean> CREATOR = new Parcelable.Creator<DataBean>() {
            @Override
            public DataBean createFromParcel(Parcel source) {
                return new DataBean(source);
            }

            @Override
            public DataBean[] newArray(int size) {
                return new DataBean[size];
            }
        };
    }
}
