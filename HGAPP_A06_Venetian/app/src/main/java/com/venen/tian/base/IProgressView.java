package com.venen.tian.base;

/**
 * Created by Daniel on 2017/5/19.
 * 可显示进度的视图，适用于多动作，即适用于视图存在多个需要显示进度的动作
 * 如果只有一个动作，不需要区分动作，则可将{@code action}指定为0
 */

public interface IProgressView {
    public void setStart(int action);
    public void setError(int action,int errcode);
    public void setError(int action,String  errString);
    public void setComplete(int action);
}
