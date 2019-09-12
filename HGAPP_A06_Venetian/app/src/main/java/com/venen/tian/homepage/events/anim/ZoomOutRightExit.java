package com.venen.tian.homepage.events.anim;

import android.animation.ObjectAnimator;
import android.view.View;
import android.view.View.MeasureSpec;

public class ZoomOutRightExit extends BaseAnimatorSet {
	public ZoomOutRightExit() {
		duration = 1000;
	}

	@Override
	public void setAnimation(View view) {
		view.measure(MeasureSpec.UNSPECIFIED, MeasureSpec.UNSPECIFIED);
		int w = view.getMeasuredWidth();
		int h = view.getMeasuredHeight();
		animatorSet.playTogether(//
				ObjectAnimator.ofFloat(view, "scaleX", 1, 0.475f, 0.1f),//
				ObjectAnimator.ofFloat(view, "scaleY", 1, 0.475f, 0.1f),//
				ObjectAnimator.ofFloat(view, "rotationY", 0, 90),
				ObjectAnimator.ofFloat(view, "translationX", 0, -42, w),//
				ObjectAnimator.ofFloat(view, "translationY", 0, 60, -h),
				ObjectAnimator.ofFloat(view, "alpha", 1, 1, 0));
	}
}
