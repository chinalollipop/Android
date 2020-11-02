package me.yokeyword.fragmentation.anim;

import android.os.Parcel;
import android.os.Parcelable;

import com.example.commonliberary.R;


/**
 * Created by YoKeyword on 16/2/5.
 */
public class LoginAnimator extends FragmentAnimator implements Parcelable{

    public LoginAnimator() {
        enter = R.anim.abc_fade_in;
        exit = R.anim.abc_fade_out;
        popEnter = 0;
        popExit = 0;
    }

    protected LoginAnimator(Parcel in) {
        super(in);
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        super.writeToParcel(dest, flags);
    }

    @Override
    public int describeContents() {
        return 0;
    }

    public static final Creator<LoginAnimator> CREATOR = new Creator<LoginAnimator>() {
        @Override
        public LoginAnimator createFromParcel(Parcel in) {
            return new LoginAnimator(in);
        }

        @Override
        public LoginAnimator[] newArray(int size) {
            return new LoginAnimator[size];
        }
    };
}
