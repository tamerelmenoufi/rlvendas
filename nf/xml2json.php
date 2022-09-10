<?php

$xml = simplexml_load_file("http://nf.mohatron.com/API-NFE/api-nfe/gerador/xml/autorizadas/13220928856577000119650010000007851000096008.xml");
echo json_encode($xml);