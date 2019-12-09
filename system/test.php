<?php
session_start();
ob_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
require('../function/MysqliDb.php');

$db = New MysqliDb();
//var_dump($db);
//$db->where ("UsersId", 1);
$user = $db->where ("UsersId", 1)->getOne ("users");
// echo $user['UsersFullName'];

$count = $db->getValue ("users", "count(*)");
echo "{$count} users found";

// $user->id = 1;
// $user->module = basename(dirname(__FILE__));
// // var_dump($user->acess());
// $account = $db->query('SELECT * FROM Users WHERE UsersName = ? AND UsersPassword = ?', 'dongpx', md5('dong'))->fetchArray();
// echo $account['UsersFullName'];

function printUsers () {
    global $db;

    $users = $db->get ("users");
    if ($db->count == 0) {
        echo "<td align=center colspan=4>No users found</td>";
        return;
    }
    foreach ($users as $u) {
        echo "<tr>
            <td>{$u['UsersId']}</td>
            <td>{$u['UsersName']}</td>
            <td>{$u['UsersFullName']}</td>

        </tr>";
    }
}

printUsers();
