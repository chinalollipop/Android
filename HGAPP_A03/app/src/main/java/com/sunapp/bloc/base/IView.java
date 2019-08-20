package com.sunapp.bloc.base;

/**
 * Created by Daniel on 2017/4/17.
 * MVP中的视图层父接口
 */

public interface IView<T> {

    public void setPresenter(T presenter);
}