<?php

if (!function_exists('btc2eur'))
{
    function btc2eur($btc)
    {
        $json = file_get_contents('https://www.coinbase.com/api/v2/prices/EUR/spot?');
        $obj = json_decode($json);
        return round($btc*$obj->data[0]->amount, 2, PHP_ROUND_HALF_DOWN);
    }

    function eur2btc($eur)
    {
        $json = file_get_contents('https://www.coinbase.com/api/v2/prices/EUR/spot?');
        $obj = json_decode($json);
        return round($eur / $obj->data[0]->amount, 8, PHP_ROUND_HALF_UP);
    }
}