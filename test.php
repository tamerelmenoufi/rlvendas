<?php

$x = '{"1":{"valor":"30","quantidade":"3"},"2":{"valor":"25","quantidade":"0"},"3":{"valor":"37","quantidade":"2"},"4":{"valor":"57","quantidade":"0"}}';
//echo $x;
$json_decode = json_decode($x);

print_r($json_decode->{'1'});
// die;

foreach ($json_decode as $key => $item) {
    print_r($item->medida);
}
#var_dump($json_decode);

/////////////////////////////////////////////////