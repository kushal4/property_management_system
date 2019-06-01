<?php
function show_flats(&$ref_array, $prop_topo_root_id, $prop_id, $conn, $log){
    $sql_excel_prop = "SELECT * FROM prop_topo WHERE prop_id = ? AND parent_id = ? ";
    $sql_excel_prop_temp = $conn->prepare($sql_excel_prop);
    $sql_excel_prop_temp->bind_param("ii",$prop_id, $prop_topo_root_id);
    $sql_excel_prop_temp->execute();
    $sql_excel_prop_temp_result = $sql_excel_prop_temp->get_result();

    $sql_excel_prop_row=$sql_excel_prop_temp_result->fetch_all(MYSQLI_ASSOC);

            foreach ($sql_excel_prop_row as $key => $value) {
                $ref_array_sub = array();
                if($value["unit"] == 1 ){
                    $ref_array_sub["id"] = $value["id"];
                    $ref_array_sub["node_name"] = $value["node_name"];
                    array_push($ref_array,$ref_array_sub);  
                    $log->logfile_writeline("the flat IDs are".$value["id"]);  
                }else if($value["unit"] == 0 ){
                    show_flats($ref_array, $value["id"], $prop_id, $conn, $log);
                }   
            }   
}
?>