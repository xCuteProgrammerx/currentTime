<?php

try {
    $FILE_URL = "numbers.json";

    if (!file_exists($FILE_URL)) {
        return $temp;
    }
    if (!filesize($FILE_URL) > 0) {
        return $temp;
    }

    $fileRead = fopen($FILE_URL, "r");
    $contentString = fread($fileRead, filesize($FILE_URL));
    fclose($fileRead);
    $json =  json_decode($contentString, false);

    if (!$json) {
        return $temp;
    }
    if (!count($json) > 0) {
        return $temp;
    }

    $dateNow = date('Y-m-d', $datetime);
    $timestampNow = strtotime($dateNow);

    $NUMBER = $temp;
    $removed = 0;
    for ($i = 0; $i < count($json); $i++) {
        if(strtotime($json[$i]->date) == false){
            $removed++;
            $json[$i] = null;
        }
        else if ($timestampNow > strtotime($json[$i]->date)) 
        {
            $removed++;
            $json[$i] = null;
        } 
        else if ($timestampNow == strtotime($json[$i]->date)) 
        {
            $NUMBER = $json[$i]->number;
        }
    }

    if($removed>0){
        $newJson = [];
        for ($i = 0; $i < count($json); $i++) {
            if($json[$i]!=null){
                array_push($newJson,$json[$i]);
            }
        }
        $json_encode = json_encode($newJson);
        $fileWrite = fopen($FILE_URL, "w");
        fwrite($fileWrite, $json_encode);
        fclose($fileWrite);
    }

    return $NUMBER;

} catch (Exception $e) {
    return $temp;
}
