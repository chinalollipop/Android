package com.hgapp.betnhg.common.event;

import me.yokeyword.fragmentation.SupportFragment;

public class StartBrotherWithPopEvent {
    public SupportFragment targetFragment;

    public StartBrotherWithPopEvent(SupportFragment targetFragment) {
        this.targetFragment = targetFragment;
    }
}
