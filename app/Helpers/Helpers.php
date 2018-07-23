<?php

function Prompt($data, $message, $url)
{
    if ($data) {
        return redirect($url)->with([
                'message' => $message . "成功",
                'icon' => '6'
            ]
        );
    } else {
        return redirect($url)->with([
                'message' => $message . "失败",
                'icon' => '5'
            ]
        );
    }
}


