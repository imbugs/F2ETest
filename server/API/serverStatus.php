<?php
include('./common.php');

/**
 * @overview ���������б�����ԣ������ؽ��
 */

$output = serverStatus($G_ServerList);
echo json_encode($output);
