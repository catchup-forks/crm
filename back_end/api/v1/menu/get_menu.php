<?php
/**
 * Created by PhpStorm.
 * User: mrdai
 * Date: 2017/5/11
 * Time: 21:40
 * Des: 获取当前用户的菜单数据
 */

$menu_data = $GLOBALS['sql']->query("SELECT id,title,name,url FROM menu");
$menu_json = array();

//    构建父菜单
foreach ($menu_data as $item) {
    if (strlen($item['name']) === 1) {
        if (!$GLOBALS['permission']->has_permission('index/home') && $item['name'] === '1' ||
            !$GLOBALS['permission']->has_permission('patient') && $item['name'] === '2' ||
            !$GLOBALS['permission']->has_permission('data') && $item['name'] === '3' ||
            !$GLOBALS['permission']->has_permission('info') && $item['name'] === '4'
        ) continue; //无权查看控制台
        array_push($menu_json, array(
            'title' => $item['title'],
            'name' => $item['name'],
            'url' => $item['url'],
            'child' => array()
        ));
    }
}

//    构建子菜单
foreach ($menu_data as $item) {
    if (preg_match('/\d-\d+/', $item['name'])) {
        $rootName = $item['name'][0];
        for ($i = 0; $i < count($menu_json); $i++) {
            if ($menu_json[$i]['name'] === $rootName) {
                if (!$GLOBALS['permission']->has_permission('t_report_data') && $item['name'] === '3-1' ||
                    !$GLOBALS['permission']->has_permission('c_report_data') && $item['name'] === '3-2' ||
                    !$GLOBALS['permission']->has_permission('info_doctor') && $item['name'] === '4-1' ||
                    !$GLOBALS['permission']->has_permission('info_disease') && $item['name'] === '4-2' ||
                    !$GLOBALS['permission']->has_permission('info_media') && $item['name'] === '4-3' ||
                    !$GLOBALS['permission']->has_permission('info_advisory') && $item['name'] === '4-4'
                ) continue;
                array_push($menu_json[$i]['child'], $item);
            }
        }
    }
}

print_r(json_encode($menu_json));
exit();