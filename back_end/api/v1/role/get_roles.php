<?php
/**
 * Created by PhpStorm.
 * User: mrdai
 * Date: 2017/5/11
 * Time: 22:16
 * Des: 获取role列表
 */

//检查当前用户是否有管理权限组的权限
if (!$GLOBALS['permission']->has_permission('system_role')) error_handler(48001);

$role_name_list = $GLOBALS['sql']->query("SELECT id, role_name, state, fixed FROM role");
print_r(json_encode(array(
    'role_list' => $role_name_list
)));
exit();
