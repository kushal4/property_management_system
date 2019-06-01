<?php

function createDomDoc($html) {
    $doc = new DOMDocument();
    $doc->loadHTML($html);
    return $doc;
}


function deleteChildren($node) {
    while (isset($node->firstChild)) {
        deleteChildren($node->firstChild);
        $node->removeChild($node->firstChild);
    }
}

function setInnerHTML($node, $html) {
    deleteChildren($node);
    if (empty($html)) {
        return;
    }

    $doc = $node->ownerDocument;
    $htmlclip = new DOMDocument();
    $htmlclip->loadHTML('<meta http-equiv="Content-Type" content="text/html;charset=utf-8"><div>' . $html . '</div>');
    $clipNode = $doc->importNode($htmlclip->documentElement->lastChild->firstChild, true);
    while ($item = $clipNode->firstChild) {
        $node->appendChild($item);
    }
}
/*
//https://stackoverflow.com/questions/2087103/how-to-get-innerhtml-of-domnode
function getInnerHTML(DOMNode $node){
    $innerHTML = "";
    $children  = $node->childNodes;

    foreach ($children as $child)
    {
        $innerHTML .= $node->ownerDocument->saveHTML($child);
    }
    return $innerHTML;
}
*/

//https://stackoverflow.com/questions/2087103/how-to-get-innerhtml-of-domnode
function getInnerHTML($node) {
    return implode(array_map([$node->ownerDocument,"saveHTML"],
        iterator_to_array($node->childNodes)));
}

//Generic method to add any attribute to an element
function addAttrToNode ($node, $attrName, $attrValue){
    if ($node != null) {
        $curr_attr = $node->getAttribute($attrName);
        $spacer =($curr_attr=="") ? "" : " ";
        if (($curr_attr !="") || ($attrValue !="") ) {
            $node->setAttribute($attrName, $curr_attr . $spacer . $attrValue);
        }
    }
}

//Adds class to element
function addClassToNode ($node, $className){
    addAttrToNode ($node, 'class', $className);
}

/*
function addClassToNode ($node, $className){
    if ($node != null) {
        $curr_attr = $node->getAttribute('class');
        $spacer =($curr_attr=="") ? "" : " ";
        $node->setAttribute('class', $curr_attr . $spacer . $className);
    }
}
*/

function addScriptPath($parentNode, $scriptPath, $scriptType="application/javascript") {
    $odoc = $parentNode->ownerDocument;
    $script = $odoc->createElement('script');
    $script_type = $odoc->createAttribute('type');
    $script_type->value = $scriptType;
    $script_src = $odoc->createAttribute('src');
    $script_src->value = $scriptPath;
    $script->appendChild($script_type);
    $script->appendChild($script_src);
    $parentNode->appendChild($script);
}
/*
function addCustomType($parentNode, $scriptPath,$app_type) {
    $odoc = $parentNode->ownerDocument;
    $script = $odoc->createElement('script');
    $script_type = $odoc->createAttribute('type');
    $script_type->value =$app_type;
    $script_src = $odoc->createAttribute('src');
	
    $script_src->value = $scriptPath;
    $script->appendChild($script_type);
    $script->appendChild($script_src);
    $parentNode->appendChild($script);
}
*/

function addLinkTag($parentNode, $linkRel, $linkType, $linkPath) {
    $odoc = $parentNode->ownerDocument;
    $link = $odoc->createElement('link');
    $link_rel = $odoc->createAttribute('rel');
    $link_rel->value = $linkRel;
    $link_type = $odoc->createAttribute('type');
    $link_type->value = $linkType;
    $link_href = $odoc->createAttribute('href');
    $link_href->value = $linkPath;
    $link->appendChild($link_rel);
    $link->appendChild($link_type);
    $link->appendChild($link_href);
    $parentNode->appendChild($link);
}

function addStyleSheet($parentNode, $cssPath){
    addLinkTag($parentNode, "stylesheet", "text/css", $cssPath);
}

function addIcon($parentNode, $iconPath){
    addLinkTag($parentNode, "shortcut icon", "image/x-icon", $iconPath);
}

function insertDOMFromFile ($parentNode, $filePath){
    $doc = $parentNode->ownerDocument; //owner $doc of the parentNode
    $file_content = file_get_contents($filePath);

    $localDoc = new DOMDocument(); //create a tmp document to crdeate the widget
    $localDoc->loadHTML($file_content);
    $targetNode = $localDoc->getElementsByTagName("body")->item(0);
    $clipNode = $doc->importNode($targetNode, true);
    $parentNode->appendChild($clipNode);
    //$testnode = $doc->createElement('li');

    //$parentNode->appendChild($testnode);
    //return $targetNode;
    //return $localDoc->saveHTML();
}

https://stackoverflow.com/questions/20851106/nextsibling-doesnt-work-when-working-with-php-domdocument
function nextElementSibling(DOMNode $node)
{
    while ($node && ($node = $node->nextSibling)) {
        //echo "class: " . $node->getAttribute("class") . "; ";
        if ($node instanceof DOMElement) {
            break;
        }
    }

    return $node;
}

function addAttribToElement(DOMElement $elem, String $attribJSONStr){
    $obj = json_decode($attribJSONStr);
    if ($obj != NULL) {
        foreach($obj as $key=>$value){
            //$new_node->setAttribute($key, $value);
            addAttrToNode ($elem, $key, $value);
            //echo $key . "=>" . $value . "<br>";
        }
    }
}

function addDataToElement(DOMElement $elem, String $dataJSONStr){
    $obj = json_decode($dataJSONStr);
    if ($obj != NULL) {
        foreach($obj as $key=>$value){
            //$new_node->setAttribute($key, $value);
            addAttrToNode ($elem, "data-".$key, $value);
            //echo $key . "=>" . $value . "<br>";
        }
    }
}


function createUserInputElement_old (String $inputType, $htmlElement_ch1, $htmlElement_ch2, $docElement, $db_conn, $permSigPriID, $log){
    $user_id = $_SESSION["user_id"];
    //$log->logfile_writeline("the user ID is:: ".$user_id);
    $elem = $docElement->createElement($inputType);
    $htmlElement_ch1->appendChild($elem);

    $sql_perm_sig = "SELECT * FROM perm_sig WHERE id = ? ";
    $perm_sig_stmt = $db_conn->prepare($sql_perm_sig);
    $perm_sig_stmt->bind_param("i",$permSigPriID);
    $perm_sig_stmt->execute();
    $perm_sig_stmt_result = $perm_sig_stmt->get_result();
    $perm_sig_stmt_result_row = $perm_sig_stmt_result->fetch_assoc();
    $permSigPriID = $perm_sig_stmt_result_row["id"];
    
    //sec-mapping starts
    $seced_permSigPriID = sec_push_val_single_entry ("fld_sig_map", $permSigPriID);
    //sec-mapping ends

    $log->logfile_writeline(__FILE__."---Dumping perm_sign MAP: Begin");
    foreach($seced_permSigPriID_array as $key => $value)
        {
            $log->logfile_writeline($key." : ".$value);
        }
    $log->logfile_writeline(__FILE__."---Dumping perm_sign MAP: End");
    
    $sql_perm_sig = "SELECT * FROM perm_sig WHERE id = ?";
    $perm_sig_stmt = $db_conn->prepare($sql_perm_sig);
    $perm_sig_stmt->bind_param("i",$permSigPriID);
    $perm_sig_stmt->execute();
    $perm_sig_result = $perm_sig_stmt->get_result();
    $perm_sig_row = $perm_sig_result->fetch_assoc();

    $table_name = $perm_sig_row["tbl_name"];
    $field_name = $perm_sig_row["fld_name"];
    //echo "the field name is:: ".$field_name;
    //$log->logfile_writeline(__FILE__."the table name is::".$table_name);
    //$log->logfile_writeline(__FILE__."the field name is::".$field_name);

    $sql_profile = "SELECT * FROM $table_name WHERE user_id = ?";
    $profile_stmt = $db_conn->prepare($sql_profile);
    $profile_stmt->bind_param("i",$user_id);
    $profile_stmt->execute();
    $profile_stmt_result = $profile_stmt->get_result();
    $profile_result_row = $profile_stmt_result->fetch_assoc();
    $pro_fld_name = $profile_result_row[$field_name];
    //echo "the profile field name is:: ",$pro_fld_name;

    //$log->logfile_writeline(__FILE__."*******".$profile_result_row);
    addAttribToElement($elem, '{"id":"'.$seced_permSigPriID.'"}');
    
    addDataToElement($elem, '{"pi":"'.$permSigPriID.'"}');
    
    $child2_id = "edit-".$seced_permSigPriID;
    addAttribToElement($htmlElement_ch2, '{"id":"'.$child2_id.'"}');
    
    addDataToElement($htmlElement_ch2, '{"edit":"'.$seced_permSigPriID.'"}');

    $element = $docElement->getElementById($seced_permSigPriID);
    $element->nodeValue = $pro_fld_name;
    //addAttribToElement($elem, '{"value":"'.$pro_fld_name.'"}');
    
    
    //echo $element->getAttribute('data-pi');

    

    //$logfile->logfile_writeline("the decoded node ID is".$unit_id_mapped);

    //$value = $docElement->getAttribute($name);

    //echo $element->getAttribute('id');
    //echo "the primary ID is:: ".$permSigPriID."</br>";
    //addDataToElement($elem, '{"ps":"'.$perm_sig_stmt_result_row['sig'].'"}');
    return $elem;
}


function createUserInputElement ($inputType, $docElement, $db_conn, $permSigPriID, &$seced_permSigPriID, $log){
    $element = array();
    $user_id = $_SESSION["user_id"];
    //echo "<br>".__FILE__."::createUserInputElement::".$permSigPriID."; gettype(inputType)=".gettype($inputType)."<br>";
    if (gettype($inputType)=="string") {
        $inputSpec = json_decode($inputType);
    } else if (gettype($inputType)=="object") {
        $inputSpec = $inputType;
        //var_dump ($inputSpec->callbackParam);
    }
    $elem = NULL; 
    if ($inputSpec == NULL) {
        $elem = $docElement->createElement($inputType);
    }else {
        $controlElem = $inputSpec->{'elemTag'};
        //echo "****".$controlElem."******"."</br>";
        $elem = $docElement->createElement($controlElem);
        if (property_exists($inputSpec, 'class')) {
            $attribElem = $inputSpec->{'class'};
            //echo "****".$attribElem."******"."</br>";
            addAttribToElement($elem, '{"class":"'.$attribElem.'"}');
        }
        //$cb_func = $inputSpec->{'test_func'};
        if (property_exists($inputSpec, 'callback')) {
            //echo "Prop Exists<br>";
            $cb_func  = $inputSpec->{'callback'};
            if (is_callable($cb_func)) {
                if (property_exists($inputSpec, 'callbackParam')) {
                    $cb_param = $inputSpec->{'callbackParam'};
                    call_user_func($cb_func, $docElement, $elem, $cb_param);
                } else {
                    call_user_func($cb_func, $docElement, $elem);
                }
            }
        } else 
        {
            //echo "Nope<br>";
        }
    }
    //sec-mapping starts
    $seced_permSigPriID = sec_push_val_single_entry ("fld_sig_map", $permSigPriID);
    addAttribToElement($elem, '{"id":"'.$seced_permSigPriID.'"}');
    addDataToElement($elem, '{"pi":"'.$permSigPriID.'"}'); //for experiment
    addAttribToElement($elem, '{"class":"generic_cell_4_style"}');

    $element["elem_id"] = $seced_permSigPriID;
    $element["element_obj"] = $elem;
    return $element;
}

//Generic method to add any element under a Node
function insertElement  (DOMElement $parentNode, $elemName, String $attribJSONStr, String $innerHtmlStr){
    
    $doc = $parentNode->ownerDocument; //owner $doc of the parentNode
    $new_node = $doc->createElement($elemName);
    $parentNode->appendChild($new_node);

    addAttribToElement($new_node, $attribJSONStr);

    setInnerHTML($new_node, $innerHtmlStr);
    return $new_node; //DOMElement
}

//Adds a DIV under a Node
function insertPanel  (DOMElement $parentNode, $attribJSONStr, $innerHtmlStr){
    return insertElement  ($parentNode, 'div', $attribJSONStr, $innerHtmlStr);
}

//Adds a page section layout
function insertPageSection  (DOMElement $parentNode, $headingStr, $attribJSONStr){
    $ps_node = insertPanel  ($parentNode, $attribJSONStr,"");

    //$obj = json_decode($attribJSONStr);
    //$doc = $parentNode->ownerDocument; //owner $doc of the parentNode

    //$ps_node = $doc->createElement('div');
    $ps_node->setAttribute("role", "PageSection");
    $ps_heading = insertPanel  ($ps_node, '{"role": "PageSectionHeading"}',"");
    setInnerHTML($ps_heading, $headingStr);
    $parentNode->appendChild($ps_node);
    /*
    foreach($obj as $key=>$value){
        $ps_node->setAttribute($key, $value);
        //echo $key . "=>" . $value . "<br>";
    }
    */
    return $ps_node;
}


?>
