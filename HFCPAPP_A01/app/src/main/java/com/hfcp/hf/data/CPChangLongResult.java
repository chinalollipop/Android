package com.hfcp.hf.data;

import java.util.List;

public class CPChangLongResult {
    private List<ListBean> list;

    public List<ListBean> getList() {
        return list;
    }

    public void setList(List<ListBean> list) {
        this.list = list;
    }

    public static class ListBean {
        /**
         * playId : 1312
         * playName : 单
         * gameId : 1
         * playCateId : 3
         * playCateName : 第二球
         * count : 3
         */

        private int playId;
        private String playName;
        private String gameId;
        private int playCateId;
        private String playCateName;
        private int count;

        public int getPlayId() {
            return playId;
        }

        public void setPlayId(int playId) {
            this.playId = playId;
        }

        public String getPlayName() {
            return playName;
        }

        public void setPlayName(String playName) {
            this.playName = playName;
        }

        public String getGameId() {
            return gameId;
        }

        public void setGameId(String gameId) {
            this.gameId = gameId;
        }

        public int getPlayCateId() {
            return playCateId;
        }

        public void setPlayCateId(int playCateId) {
            this.playCateId = playCateId;
        }

        public String getPlayCateName() {
            return playCateName;
        }

        public void setPlayCateName(String playCateName) {
            this.playCateName = playCateName;
        }

        public int getCount() {
            return count;
        }

        public void setCount(int count) {
            this.count = count;
        }
    }
}
