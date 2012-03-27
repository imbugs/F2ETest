<?php
include('./common.php');

/**
 * @overview 检查度武器列表可用性，并返回结果
 */

$output = serverStatus($G_ServerList);
echo json_encode($output);
