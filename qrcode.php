<?php

include("./lib/includes.php");

$query = "select * from mesas order by mesa";
$result = mysqli_query($con, $query);
while($d = mysqli_fetch_object($result)){
    echo "Mesa {$d->mesa} - https://app.yobom.com.br/?".md5($d->mesa)."<br>";
}

/////////////////////////////////////////////////