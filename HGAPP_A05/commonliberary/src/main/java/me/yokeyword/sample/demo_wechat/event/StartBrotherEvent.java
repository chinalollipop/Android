package me.yokeyword.sample.demo_wechat.event;

import me.yokeyword.fragmentation.SupportFragment;

/**
 * Created by YoKeyword on 16/6/30.
 */
public class StartBrotherEvent {
    public SupportFragment targetFragment;
    //默认为标准启动模式
    public int launchmode = SupportFragment.STANDARD;
    public StartBrotherEvent(SupportFragment targetFragment) {
        this.targetFragment = targetFragment;
    }

    public StartBrotherEvent(SupportFragment targetFragment,int launchmode) {
        this.targetFragment = targetFragment;
        this.launchmode = launchmode;
    }
}
