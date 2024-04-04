<?php
// By Henry Newton

header('Content-Type: application/json; charset=utf-8');
$res = $this->db->query("select json from calendars where id=$1",
                        $id);
echo $res[0]["json"];
?>