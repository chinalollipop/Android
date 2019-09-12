package com.sands.corp.data;

import java.util.List;

public class SaiGuoResult {

    /**
     * status : 200
     * describe : success
     * timestamp : 20181013103047
     * data : [{"name":"日本J3联赛","result":[{"MID":"3419122","M_Date":"2018-10-13","M_Time":"00:01a","MB_Team":"藤枝MYFC","TG_Team":"大阪飞脚U23","M_League":"日本J3联赛","MB_Inball":"2","TG_Inball":"1","MB_Inball_HR":"2","TG_Inball_HR":"1","Open":"1"},{"MID":"3419124","M_Date":"2018-10-13","M_Time":"00:01a","MB_Team":"藤枝MYFC","TG_Team":"大阪飞脚U23","M_League":"日本J3联赛","MB_Inball":"2","TG_Inball":"1","MB_Inball_HR":"2","TG_Inball_HR":"1","Open":"1"},{"MID":"3423062","M_Date":"2018-10-13","M_Time":"00:01a","MB_Team":"藤枝MYFC -角球数","TG_Team":"大阪飞脚U23 -角球数","M_League":"日本J3联赛","MB_Inball":"5","TG_Inball":"4","MB_Inball_HR":"4","TG_Inball_HR":"2","Open":"1"},{"MID":"3419126","M_Date":"2018-10-13","M_Time":"04:00a","MB_Team":"SC相模原","TG_Team":"北九州向日葵","M_League":"日本J3联赛","MB_Inball":"1","TG_Inball":"0","MB_Inball_HR":"0","TG_Inball_HR":"0","Open":"1"},{"MID":"3419128","M_Date":"2018-10-13","M_Time":"04:00a","MB_Team":"SC相模原","TG_Team":"北九州向日葵","M_League":"日本J3联赛","MB_Inball":"1","TG_Inball":"0","MB_Inball_HR":"0","TG_Inball_HR":"0","Open":"1"},{"MID":"3423064","M_Date":"2018-10-13","M_Time":"04:00a","MB_Team":"SC相模原 -角球数","TG_Team":"北九州向日葵 -角球数","M_League":"日本J3联赛","MB_Inball":"6","TG_Inball":"5","MB_Inball_HR":"3","TG_Inball_HR":"3","Open":"1"},{"MID":"3419130","M_Date":"2018-10-13","M_Time":"05:00a","MB_Team":"琉球","TG_Team":"福岛联队","M_League":"日本J3联赛","MB_Inball":"3","TG_Inball":"0","MB_Inball_HR":"3","TG_Inball_HR":"0","Open":"1"},{"MID":"3419132","M_Date":"2018-10-13","M_Time":"05:00a","MB_Team":"琉球","TG_Team":"福岛联队","M_League":"日本J3联赛","MB_Inball":"3","TG_Inball":"0","MB_Inball_HR":"3","TG_Inball_HR":"0","Open":"1"},{"MID":"3423066","M_Date":"2018-10-13","M_Time":"05:00a","MB_Team":"琉球 -角球数","TG_Team":"福岛联队 -角球数","M_League":"日本J3联赛","MB_Inball":"7","TG_Inball":"4","MB_Inball_HR":"6","TG_Inball_HR":"0","Open":"1"}]}]
     */

    private String status;
    private String describe;
    private String timestamp;
    private List<DataBean> data;

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getDescribe() {
        return describe;
    }

    public void setDescribe(String describe) {
        this.describe = describe;
    }

    public String getTimestamp() {
        return timestamp;
    }

    public void setTimestamp(String timestamp) {
        this.timestamp = timestamp;
    }

    public List<DataBean> getData() {
        return data;
    }

    public void setData(List<DataBean> data) {
        this.data = data;
    }

    public static class DataBean {
        /**
         * name : 日本J3联赛
         * result : [{"MID":"3419122","M_Date":"2018-10-13","M_Time":"00:01a","MB_Team":"藤枝MYFC","TG_Team":"大阪飞脚U23","M_League":"日本J3联赛","MB_Inball":"2","TG_Inball":"1","MB_Inball_HR":"2","TG_Inball_HR":"1","Open":"1"},{"MID":"3419124","M_Date":"2018-10-13","M_Time":"00:01a","MB_Team":"藤枝MYFC","TG_Team":"大阪飞脚U23","M_League":"日本J3联赛","MB_Inball":"2","TG_Inball":"1","MB_Inball_HR":"2","TG_Inball_HR":"1","Open":"1"},{"MID":"3423062","M_Date":"2018-10-13","M_Time":"00:01a","MB_Team":"藤枝MYFC -角球数","TG_Team":"大阪飞脚U23 -角球数","M_League":"日本J3联赛","MB_Inball":"5","TG_Inball":"4","MB_Inball_HR":"4","TG_Inball_HR":"2","Open":"1"},{"MID":"3419126","M_Date":"2018-10-13","M_Time":"04:00a","MB_Team":"SC相模原","TG_Team":"北九州向日葵","M_League":"日本J3联赛","MB_Inball":"1","TG_Inball":"0","MB_Inball_HR":"0","TG_Inball_HR":"0","Open":"1"},{"MID":"3419128","M_Date":"2018-10-13","M_Time":"04:00a","MB_Team":"SC相模原","TG_Team":"北九州向日葵","M_League":"日本J3联赛","MB_Inball":"1","TG_Inball":"0","MB_Inball_HR":"0","TG_Inball_HR":"0","Open":"1"},{"MID":"3423064","M_Date":"2018-10-13","M_Time":"04:00a","MB_Team":"SC相模原 -角球数","TG_Team":"北九州向日葵 -角球数","M_League":"日本J3联赛","MB_Inball":"6","TG_Inball":"5","MB_Inball_HR":"3","TG_Inball_HR":"3","Open":"1"},{"MID":"3419130","M_Date":"2018-10-13","M_Time":"05:00a","MB_Team":"琉球","TG_Team":"福岛联队","M_League":"日本J3联赛","MB_Inball":"3","TG_Inball":"0","MB_Inball_HR":"3","TG_Inball_HR":"0","Open":"1"},{"MID":"3419132","M_Date":"2018-10-13","M_Time":"05:00a","MB_Team":"琉球","TG_Team":"福岛联队","M_League":"日本J3联赛","MB_Inball":"3","TG_Inball":"0","MB_Inball_HR":"3","TG_Inball_HR":"0","Open":"1"},{"MID":"3423066","M_Date":"2018-10-13","M_Time":"05:00a","MB_Team":"琉球 -角球数","TG_Team":"福岛联队 -角球数","M_League":"日本J3联赛","MB_Inball":"7","TG_Inball":"4","MB_Inball_HR":"6","TG_Inball_HR":"0","Open":"1"}]
         */

        private String name;
        private List<ResultBean> result;

        public String getName() {
            return name;
        }

        public void setName(String name) {
            this.name = name;
        }

        public List<ResultBean> getResult() {
            return result;
        }

        public void setResult(List<ResultBean> result) {
            this.result = result;
        }

        public static class ResultBean {
            /**
             * MID : 3419122
             * M_Date : 2018-10-13
             * M_Time : 00:01a
             * MB_Team : 藤枝MYFC
             * TG_Team : 大阪飞脚U23
             * M_League : 日本J3联赛
             * MB_Inball : 2
             * TG_Inball : 1
             * MB_Inball_HR : 2
             * TG_Inball_HR : 1
             * Open : 1
             */

            private String MID;
            private String M_Date;
            private String M_Time;
            private String MB_Team;
            private String TG_Team;
            private String M_League;
            private String MB_Inball;
            private String TG_Inball;
            private String MB_Inball_HR;
            private String TG_Inball_HR;
            private String Open;

            public String getMID() {
                return MID;
            }

            public void setMID(String MID) {
                this.MID = MID;
            }

            public String getM_Date() {
                return M_Date;
            }

            public void setM_Date(String M_Date) {
                this.M_Date = M_Date;
            }

            public String getM_Time() {
                return M_Time;
            }

            public void setM_Time(String M_Time) {
                this.M_Time = M_Time;
            }

            public String getMB_Team() {
                return MB_Team;
            }

            public void setMB_Team(String MB_Team) {
                this.MB_Team = MB_Team;
            }

            public String getTG_Team() {
                return TG_Team;
            }

            public void setTG_Team(String TG_Team) {
                this.TG_Team = TG_Team;
            }

            public String getM_League() {
                return M_League;
            }

            public void setM_League(String M_League) {
                this.M_League = M_League;
            }

            public String getMB_Inball() {
                return MB_Inball;
            }

            public void setMB_Inball(String MB_Inball) {
                this.MB_Inball = MB_Inball;
            }

            public String getTG_Inball() {
                return TG_Inball;
            }

            public void setTG_Inball(String TG_Inball) {
                this.TG_Inball = TG_Inball;
            }

            public String getMB_Inball_HR() {
                return MB_Inball_HR;
            }

            public void setMB_Inball_HR(String MB_Inball_HR) {
                this.MB_Inball_HR = MB_Inball_HR;
            }

            public String getTG_Inball_HR() {
                return TG_Inball_HR;
            }

            public void setTG_Inball_HR(String TG_Inball_HR) {
                this.TG_Inball_HR = TG_Inball_HR;
            }

            public String getOpen() {
                return Open;
            }

            public void setOpen(String Open) {
                this.Open = Open;
            }
        }
    }
}
