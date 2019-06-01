<?php
function type_unit_creator($type){
    $type_unit = "";
    if($type == "len"){
        $type_unit = "Meters";
        return $type_unit;
    }else if($type == "area"){
        $type_unit = "Sq. Meters";
        return $type_unit;
    }
}
?>