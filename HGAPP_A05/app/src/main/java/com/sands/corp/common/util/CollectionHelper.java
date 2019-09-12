package com.sands.corp.common.util;

import java.util.Collection;

/**
 * Created by Nereus on 2017/9/21.
 */

public final class CollectionHelper {

    public static boolean isEmpty(Collection collection)
    {
        return null == collection|| collection.isEmpty();
    }
}
