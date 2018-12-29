package com.qpweb.a01.base.event;

import me.yokeyword.fragmentation.SupportFragment;

public class StartBrotherEvent {
    public SupportFragment targetFragment;
    //默认为标准启动模式
    public int launchmode = SupportFragment.STANDARD;

    public StartBrotherEvent(SupportFragment targetFragment) {
        this.targetFragment = targetFragment;
    }

    public StartBrotherEvent(SupportFragment targetFragment, int launchmode) {
        this.targetFragment = targetFragment;
        this.launchmode = launchmode;
    }
}
