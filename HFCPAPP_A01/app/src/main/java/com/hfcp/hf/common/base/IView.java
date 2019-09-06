package com.hfcp.hf.common.base;

/**
 * Created by Daniel on 2018/4/17.
 * MVP中的视图层父接口
 */

public interface IView<T> {

    void setPresenter(T presenter);
}
