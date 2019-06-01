<?php
class jqueryui_tabs_widget
{
    private $ownerDoc;
    private $localDoc;
    private $widget_id;
    private $widget_node;
    private $ul_node;

    public function __construct($WidgetID){
        //echo 'The class "' . __CLASS__ . '" was initiated!<br>';
        $localDoc = new DOMDocument(); //create a tmp document to crdeate the widget
        $this->ownerDoc = $localDoc;
        $this->widget_id = $WidgetID;
        $this->ownerDoc->loadHTML('<meta http-equiv="Content-Type" content="text/html;charset=utf-8">');
        $this->widget_node = $this->ownerDoc->createElement('div');
        $this->widget_node->setAttribute('id',$WidgetID);
        $this->ul_node = $this->ownerDoc->createElement('ul');
        $this->widget_node->appendChild($this->ul_node);
        //$this->ownerDoc->appendChild($this->widget_node);
    }
    // Declare  properties

    public $width = 0;

    // Method to insert the tab Widget structure under a node (as a child)
    public function setParent($parentNode){
        $doc = $parentNode->ownerDocument; //owner $doc of the parentNode
        $tabWidgetNode = $this->ownerDoc->getElementById($this->widget_id);
        //echo 'ID if the widget is "' . $tabWidgetNode->getAttribute("id") . '"!<br>';
        //deep copy widget structure from tmp doc to target doc
        $clipNode = $doc->importNode($tabWidgetNode, true);
        $parentNode->appendChild($clipNode);
        $this->ownerDoc = $doc; //NOw that widget has been moved to target document, set that as owner doc
        $this->widget_node = $clipNode;
        $this->ul_node = $this->widget_node->firstChild;
    }

    public function insertTab($tabID, $tabCaption){
        $li_node = $this->ownerDoc->createElement('li');
        $a_node = $this->ownerDoc->createElement('a');
        $a_node->setAttribute('href',"#".$tabID);
        $a_node->textContent = $tabCaption;
        $li_node->appendChild($a_node);
        $this->ul_node->appendChild($li_node);
        $container_node = $this->ownerDoc->createElement('div');
        $container_node->setAttribute('id', $tabID);
        $container_node->setAttribute('role', "tabs-tab-container");
        $this->widget_node->appendChild($container_node);
        return $container_node;
    }

    //Returns DOMNode of the specified Tab container
    public function getTabContainerNode ($tabID){
        return $this->ownerDoc->getElementById($tabID);
    }

    public function addViewtoTabContainer($tabID, $viewID) {
        $viewNode = $this->ownerDoc->createElement('div');
        $viewNode->setAttribute('id', $viewID);
        $viewNode->setAttribute('role', "tabs-tab-view");
        $this->getTabContainerNode($tabID)->appendChild($viewNode);
        //$this->ownerDoc->getElementById ($tabID)->appendChild($viewNode);
        return $viewNode;
    }

    /*
    private function addClassToNode ($node, $className){
        if ($node != null) {
            $curr_attr = $node->getAttribute('class');
            $spacer =($curr_attr=="") ? "" : " ";
            $node->setAttribute('class', $curr_attr . $spacer . $className);
        }
    }*/

    public function addClass($className){
        addClassToNode($this->widget_node, $className);
    }

    public function addTabClass ($tabID, $className){
        //find all Anchors under UL
        $tab_a_list = $this->ul_node->getElementsByTagName ("a");
        $target_anchor=null;
        //find the anchor with the specified tabID
        for($c = 0; $c < $tab_a_list->length; $c++){
            if ($tab_a_list->item($c)->getAttribute("href") == "#".$tabID){
                $target_anchor = $tab_a_list->item($c);
            }
        }
        addClassToNode ($target_anchor, $className);
     }

    private function addClassToNodeID ($nodeID, $className){
        $target_node = $this->ownerDoc->getElementById ($nodeID);
        addClassToNode ($target_node, $className);
    }

    public function addTabContainerClass ($tabID, $className){
        $this->addClassToNodeID ($tabID, $className);
    }

    public function addTabViewClass ($viewID, $className){
        $this->addClassToNodeID ($viewID, $className);
    }

    //Creates a DOMNode of a specfied TagName and inserts under a Tab Container
    public function insertTagIntoTabConatainer($tabID, $tagName){
        $newNode = $this->ownerDoc->createElement($tagName);
        $this->ownerDoc->getElementById($tabID)->appendChild($newNode);
        return $newNode;
    }

    //Inserts a DOMNode under a Tab Container
    public function insertNodeIntoTabConatainer($tabID, $Node){
        $this->ownerDoc->getElementById($tabID)->appendChild($Node);
        //return $newNode;
    }

    //Creates a DOM tree from a well formed HTML
    //and inserts the tree under spefied Tab Container

    private function insertDOMFromFileIntoNode ($node, $filePath){
        $file_content = file_get_contents($filePath);

        $localDoc = new DOMDocument(); //create a tmp document to create the DOM tree from HTML
        $localDoc->loadHTML($file_content);
        $targetNode = $localDoc->getElementsByTagName("body")->item(0);
        $targetNodeInnerHTML = getInnerHTML($targetNode);

        //$clipNode = $this->ownerDoc->importNode($targetNode, true);
        //$node->appendChild($clipNode);
        setInnerHTML($node, $targetNodeInnerHTML);
    }

    public function insertDOMFromFileIntoTabConatainer ($tabID, $filePath){
        $parentNode = $this->ownerDoc->getElementById($tabID);
        $this->insertDOMFromFileIntoNode ($parentNode, $filePath);
    }

    public function insertDOMFromFileIntoTabView ($viewID, $filePath){
        $parentNode = $this->ownerDoc->getElementById($viewID);
        $this->insertDOMFromFileIntoNode ($parentNode, $filePath);
    }


    public function dump(){
        return $this->localDoc->saveHTML();
    }
}//jqueryui_tabs_widget

class jqueryui_accordion_widget
{
    private $ownerDoc;
    private $localDoc;
    private $widget_id;
    private $widget_node;

    public function __construct($WidgetID){
        //echo 'The class "' . __CLASS__ . '" was initiated!<br>';
        $localDoc = new DOMDocument(); //create a tmp document to crdeate the widget
        $this->ownerDoc = $localDoc;
        $this->widget_id = $WidgetID;
        $this->ownerDoc->loadHTML('<meta http-equiv="Content-Type" content="text/html;charset=utf-8">');
        $this->widget_node = $this->ownerDoc->createElement('div');
        $this->widget_node->setAttribute('id',$WidgetID);
        //$this->ownerDoc->appendChild($this->widget_node);
    }
    // Declare  properties

    public $width = 0;

    // Method to insert the tab Widget structure under a node (as a child)
    public function setParent($parentNode){
        $doc = $parentNode->ownerDocument; //owner $doc of the parentNode
        $WidgetNode = $this->ownerDoc->getElementById($this->widget_id);
        //echo 'ID if the widget is "' . $tabWidgetNode->getAttribute("id") . '"!<br>';
        //deep copy widget structure from tmp doc to target doc
        $clipNode = $doc->importNode($WidgetNode, true);
        $parentNode->appendChild($clipNode);
        $this->ownerDoc = $doc; //NOw that widget has been moved to target document, set that as owner doc
        $this->widget_node = $clipNode;
    }

    public function insertTab($tabID, $tabCaption){
        $tab_node = $this->ownerDoc->createElement('h3');
        $tab_node->setAttribute('id', $tabID);
        $tab_node->textContent = $tabCaption;
        $this->widget_node->appendChild($tab_node);
        $container_node = $this->ownerDoc->createElement('div');
        $container_node->setAttribute("class", $tabID);
        $container_node->setAttribute('role', "acc-tab-container");
        $this->widget_node->appendChild($container_node);
        return $tab_node;
    }

    //Returns DOMNode of the specified Tab container
    public function getTabContainerNode ($tabID){
        $tabNode =  $this->ownerDoc->getElementById($tabID);
        return nextElementSibling($tabNode);
    }

    public function addViewtoTabContainer($tabID, $viewID) {
        $viewNode = $this->ownerDoc->createElement('div');
        $viewNode->setAttribute('id', $viewID);
        $viewNode->setAttribute('role', "acc-tab-view");
        $this->getTabContainerNode($tabID)->appendChild($viewNode);
        //$this->ownerDoc->getElementById ($tabID)->appendChild($viewNode);
        return $viewNode;
    }

    //Adds class to Widget
    public function addClass($className){
        addClassToNode($this->widget_node, $className);
    }

    public function addTabClass ($tabID, $className){
        //find all Anchors under UL
        $tab_a_list = $this->ul_node->getElementsByTagName ("a");
        $target_anchor=null;
        //find the anchor with the specified tabID
        for($c = 0; $c < $tab_a_list->length; $c++){
            if ($tab_a_list->item($c)->getAttribute("href") == "#".$tabID){
                $target_anchor = $tab_a_list->item($c);
            }
        }
        addClassToNode ($target_anchor, $className);
    }

    private function addClassToNodeID ($nodeID, $className){
        $target_node = $this->ownerDoc->getElementById ($nodeID);
        addClassToNode ($target_node, $className);
    }

    public function addTabContainerClass ($tabID, $className){
        $this->addClassToNodeID ($tabID, $className);
    }

    public function addTabViewClass ($viewID, $className){
        $this->addClassToNodeID ($viewID, $className);
    }




    //Creates a DOMNode of a specfied TagName and inserts under a Tab Container
    public function insertTagIntoTabConatainer($tabID, $tagName){
        $newNode = $this->ownerDoc->createElement($tagName);
        $this->ownerDoc->getElementById($tabID)->appendChild($newNode);
        return $newNode;
    }

    //Inserts a DOMNode under a Tab Container
    public function insertNodeIntoTabConatainer($tabID, $Node){
        $this->ownerDoc->getElementById($tabID)->appendChild($Node);
        //return $newNode;
    }

    //Creates a DOM tree from a well formed HTML
    //and inserts the tree under spefied Tab Container

    private function insertDOMFromFileIntoNode ($node, $filePath){
        $file_content = file_get_contents($filePath);

        $localDoc = new DOMDocument(); //create a tmp document to create the DOM tree from HTML
        $localDoc->loadHTML($file_content);
        $targetNode = $localDoc->getElementsByTagName("body")->item(0);
        $targetNodeInnerHTML = getInnerHTML($targetNode);

        //$clipNode = $this->ownerDoc->importNode($targetNode, true);
        //$node->appendChild($clipNode);
        setInnerHTML($node, $targetNodeInnerHTML);
    }

    public function insertDOMFromFileIntoTabConatainer ($tabID, $filePath){
        $parentNode = $this->ownerDoc->getElementById($tabID);
        $this->insertDOMFromFileIntoNode ($parentNode, $filePath);
    }

    public function insertDOMFromFileIntoTabView ($viewID, $filePath){
        $parentNode = $this->ownerDoc->getElementById($viewID);
        $this->insertDOMFromFileIntoNode ($parentNode, $filePath);
    }


    public function dump(){
        return $this->localDoc->saveHTML();
    }
    

    public function insertTabSteffi($tabID, $tabCaption){
        $ret=array();
       // $insertTabSteff=new insertTabSteffi();
        $tab_node = $this->ownerDoc->createElement('h3');
       
        
        $tab_node->setAttribute('id', $tabID);
        $tab_node->textContent = $tabCaption;
        $this->widget_node->appendChild($tab_node);
        $container_node = $this->ownerDoc->createElement('div');
       
        
        $container_node->setAttribute("class", $tabID);
        $container_node->setAttribute('role', "acc-tab-container");
        $this->widget_node->appendChild($container_node);
        array_push($ret,$tab_node,$container_node);
        return $ret;
    }
}//jqueryui_accordion_widget


//Creates a Table
/*
class sense_table
{
    private $ownerDoc;
    private $localDoc;
    private $table_id;
    private $table_node;

    public function __construct($WidgetID){
        //echo 'The class "' . __CLASS__ . '" was initiated!<br>';
        $localDoc = new DOMDocument(); //create a tmp document to crdeate the widget
        $this->ownerDoc = $localDoc;
        $this->widget_id = $WidgetID;
        $this->ownerDoc->loadHTML('<meta http-equiv="Content-Type" content="text/html;charset=utf-8">');
        $this->widget_node = $this->ownerDoc->createElement('table');
        $this->widget_node->setAttribute('id',$WidgetID);
        //$this->ownerDoc->appendChild($this->widget_node);
    }

// Method to insert the tab Widget structure under a node (as a child)
    public function setParent($parentNode){
        $doc = $parentNode->ownerDocument; //owner $doc of the parentNode
        $WidgetNode = $this->ownerDoc->getElementById($this->widget_id);
        //echo 'ID if the widget is "' . $tabWidgetNode->getAttribute("id") . '"!<br>';
        //deep copy widget structure from tmp doc to target doc
        $clipNode = $doc->importNode($WidgetNode, true);
        $parentNode->appendChild($clipNode);
        $this->ownerDoc = $doc; //NOw that widget has been moved to target document, set that as owner doc
        $this->widget_node = $clipNode;
    }


}*/ //sense_table


class sense_table
{
    private $ownerDoc;
    private $localDoc;
    private $options;

    private $widget_id="";
    
    private $content; //DOMElement
    private $table_node; //DOMElement
    private $curr_row_id;
    private $curr_row_ref;
    private $curr_cell_id;
    private $curr_cell_ref;

    private $heading_content_id;
    
    private $heading_div_class;
    
    private $heading_span_class;

    private $perm_control_obj = NULL; 

    //Options
    private $widgetStyle;
    private $headingStyle;
    private $headingText;
    private $headingTextStyle;
    private $contentStyle;
    private $contentTableStyle;

    //Widget Structure
    private $widget_node; //DOMElement
    private $content_div;
    private $headerdiv;
    private $headerspan;
    private $table_cont_div;

    private $dbconn=NULL;
    private $tbl_data_owner_id=0;

    private function get_option (String $opt_name){
        //echo $opt_name . "</br>";
        if (array_key_exists($opt_name,$this->options)) {
            return $this->options[$opt_name];
        } else {
            return "";
        }
    }

    public function __construct($options){
        $this->options = $options;
        //echo 'The class "' . __CLASS__ . '" was initiated!<br>';
        //echo "Sense Table ID=".$this->get_option("id")."<br>";
        $this->widget_id = $this->get_option("id");
        $this->widgetStyle = $this->get_option("widgetStyle");
        $this->headingStyle = $this->get_option("headingStyle");
        $this->headingText = $this->get_option("headingText");
        $this->headingTextStyle = $this->get_option("headingTextStyle");
        $this->contentStyle = $this->get_option("contentStyle");
        $this->contentTableStyle = $this->get_option("contentTableStyle");
        

        $this->perm_control_obj = new stdClass();

        $localDoc = new DOMDocument(); //create a tmp document to crdeate the widget
        $this->ownerDoc = $localDoc;
        
        $this->ownerDoc->loadHTML('<meta http-equiv="Content-Type" content="text/html;charset=utf-8">');
        $this->widget_node = $this->ownerDoc->createElement('div');
        $this->content_div = $this->ownerDoc->createElement('div');
        ($this->widget_node)->appendChild($this->content_div);
        addAttrToNode ($this->widget_node, 'class', $this->widgetStyle);

        $this->headerdiv = $this->ownerDoc->createElement('div');
        ($this->content_div)->appendChild($this->headerdiv);
        
        $this->headerspan = $this->ownerDoc->createElement('span');
        addAttrToNode ($this->headerspan, 'class', $this->headingTextStyle);

        ($this->headerdiv)->appendChild($this->headerspan);
        addAttrToNode ($this->headerdiv, 'class', $this->headingStyle);

        $this->table_cont_div = $this->ownerDoc->createElement('div');
        addAttrToNode ($this->table_cont_div, 'class', $this->contentStyle);

        ($this->widget_node)->appendChild($this->table_cont_div);
        $this->table_node = $this->ownerDoc->createElement('table');
        ($this->table_cont_div)->appendChild($this->table_node);
        addAttrToNode ($this->table_node, 'class', $this->contentTableStyle);

        if ($this->widget_id != "") {
            ($this->widget_node)->setAttribute('id',$this->widget_id);
            ($this->content_div)->setAttribute('id',$this->widget_id."_tc");
            ($this->headerdiv)->setAttribute('id',$this->widget_id."_tc_hdiv");
            ($this->headerspan)->setAttribute('id',$this->widget_id."_tc_hdiv_span");
            ($this->table_cont_div)->setAttribute('id',$this->widget_id."_tc_cdiv");
            ($this->table_node)->setAttribute('id',$this->widget_id."_tc_ctab");
        }
        
    }

    public function setDB_Conn($db_conn){
        $this->dbconn = $db_conn;
    }

    public function setTableDataUserID($uid){
        $this->tbl_data_owner_id = $uid;
    }

// Method to insert the tab Widget structure under a node (as a child)
    public function setParent(DOMElement $parentNode){
        //if ($this->widget_id == "") {
            $parent_id = $parentNode->getAttribute("id");
            if ($parent_id == "") {
                echo "ERROR: Parent Node must have an Unique ID!!!";
            } else {
                $doc = $parentNode->ownerDocument; //owner $doc of the parentNode
                //$WidgetNode = $this->ownerDoc->getElementById($this->widget_id);
                $WidgetNode = $this->widget_node;
                //echo 'ID if the widget is "' . $tabWidgetNode->getAttribute("id") . '"!<br>';
                //deep copy widget structure from tmp doc to target doc
                $clipNode = $doc->importNode($WidgetNode, true);
                $parentNode->appendChild($clipNode);
                $this->ownerDoc = $doc; //NOw that widget has been moved to target document, set that as owner doc
                $this->widget_node = $clipNode;
        
                $this->content_div = $clipNode->firstChild;
                $this->headerdiv = $this->content_div->firstChild;
                $this->headerspan =  $this->headerdiv->firstChild;
                $this->table_cont_div = $this->content_div->nextSibling;
                $this->table_node = $this->table_cont_div->firstChild;

                if ($this->widget_id == "") {
                    $this->widget_id = $parent_id;
                    ($this->widget_node)->setAttribute('id',$this->widget_id."_w");
                } else {
                    ($this->widget_node)->setAttribute('id',$this->widget_id);
                }

                ($this->content_div)->setAttribute('id',$this->widget_id."_tc");
                ($this->headerdiv)->setAttribute('id',$this->widget_id."_tc_hdiv");
                ($this->headerspan)->setAttribute('id',$this->widget_id."_tc_hdiv_span");
                ($this->table_cont_div)->setAttribute('id',$this->widget_id."_tc_cdiv");
                ($this->table_node)->setAttribute('id',$this->widget_id."_tc_ctab");
            }
        //}
    }

    public function setParent_old(DOMElement $parentNode){
        $doc = $parentNode->ownerDocument; //owner $doc of the parentNode
        //$WidgetNode = $this->ownerDoc->getElementById($this->widget_id);
        $WidgetNode = $this->widget_node;
        //echo 'ID if the widget is "' . $tabWidgetNode->getAttribute("id") . '"!<br>';
        //deep copy widget structure from tmp doc to target doc
        $clipNode = $doc->importNode($WidgetNode, true);
        $parentNode->appendChild($clipNode);
        $this->ownerDoc = $doc; //NOw that widget has been moved to target document, set that as owner doc
        $this->widget_node = $clipNode;
    }

    public function setAttrib(String $attribJSONStr){
        $table_node = $this->widget_node;
        addAttribToElement($table_node, $attribJSONStr);
    }

    public function setData(String $dataJSONStr){
        $table_node = $this->widget_node;
        addDataToElement($table_node, $dataJSONStr);
    }

    public function printRowIDs (){
        $table_node = $this->table_node;
        $table_rows=$table_node->childNodes;//$table_rows is DOMNodeList
        //for ($i = $table_rows->length; --$i >= 0; ) {
        for ($i = 0; $i < $table_rows->length; $i++) {
            $table_row = $table_rows->item($i);
            echo ($table_row->getAttribute('id')."</br>");
        }

    }
    private function getRow(String $row_id){
        $table_node = $this->table_node;
        $table_rows=$table_node->childNodes;//$table_rows is DOMNodeList
        for ($i = $table_rows->length; --$i >= 0; ) {
        //for ($i = 0; $i < $table_rows->length; $i++) {
            $table_row = $table_rows->item($i); //$table_row is DOMNode
            if ($table_row->getAttribute('id') == $row_id){
                //$this->curr_row_id = $row_id;
                return $table_row;
            }
        }
        return NULL; //if the row_id is not found
    }


    public function addRow(){
        $table_node = $this->table_node;
        $table_row = insertElement($table_node, 'tr', "", "");
        $this->curr_row_ref = $table_row;
        return $table_row; //DOMElement
    }

    public function setCurrRowID(String $row_ID){
        ($this->curr_row_ref)->setAttribute('id',$row_ID);
        $this->curr_row_id = $row_ID;
    }

    public function setRowID(DOMElement $table_row, String $row_ID){
        $table_row->setAttribute('id',$row_ID);
        $this->curr_row_id = $row_ID;
    }

    public function addRowWithID(String $row_ID){
        $table_node = $this->table_node;
        $table_row = insertElement($table_node, 'tr', "", "");
        $table_row->setAttribute('id',$row_ID);
        $this->curr_row_ref = $table_row;
        $this->curr_row_id = $row_ID;
        return $table_row; //DOMElement
    }

    public function setCurrRowAttrib(String $attribJSONStr){
        addAttribToElement($this->curr_row_ref, $attribJSONStr);
    }

    public function setRowAttrib(DOMElement $table_row, String $attribJSONStr){
        addAttribToElement($table_row, $attribJSONStr);
    }

    public function addRowWithIDAttrib(String $row_ID, String $attribJSONStr){
        $table_node = $this->table_node;
        $table_row = insertElement($table_node, 'tr', $attribJSONStr, "");
        $table_row->setAttribute('id',$row_ID);
        $this->curr_row_ref = $table_row;
        $this->curr_row_id = $row_ID;
        return $table_row; //DOMElement
    }

    public function setCurrRowData(String $dataJSONStr){
        addDataToElement($this->curr_row_ref, $dataJSONStr);
    }

    public function setRowData(DOMElement $table_row, String $dataJSONStr){
        addDataToElement($table_row, $dataJSONStr);
    }

    public function insertRowBefore(String $ref_row_ID, DOMElement $new_table_row){
        $table_node = $this->table_node;
        //$table_rows=$table_node->childNodes;//$table_rows is DOMNodeList
        $table_row = $this->getRow($ref_row_ID);
        $table_node->insertBefore($new_table_row, $table_row);
    }

    public function insertRowBeforeWithID(String $ref_row_ID, String $new_row_ID){
        $new_row = $this->ownerDoc->createElement('tr');
        $new_row->setAttribute('id',$new_row_ID);
        $this->insertRowBefore($ref_row_ID, $new_row);
    }

    public function insertRowBeforeWithIDAttrib(String $ref_row_ID, String $new_row_ID, String $attribJSONStr){
        $new_row = $this->ownerDoc->createElement('tr');
        $new_row->setAttribute('id',$new_row_ID);
        addAttribToElement($new_row, $attribJSONStr);
        $this->insertRowBefore($ref_row_ID, $new_row);
    }


    public function insertRowAfter(String $ref_row_ID, DOMElement $new_table_row){
        $table_node = $this->table_node;
        $last_row =$table_node->lastChild;
        //$table_rows=$table_node->childNodes;//$table_rows is DOMNodeList
        //$table_row;
        $table_row = $this->getRow($ref_row_ID);

        if ($table_row->isSameNode($last_row)){
            $table_node->appendChild($new_table_row);
        } else {
            $next_row = $table_row->nextSibling;
            $table_node->insertBefore($new_table_row, $next_row);
        }
    }

    public function insertRowAfterWithID(String $ref_row_ID, String $new_row_ID){
        $new_row = $this->ownerDoc->createElement('tr');
        $new_row->setAttribute('id',$new_row_ID);
        $this->insertRowAfter($ref_row_ID, $new_row);
    }

    public function insertRowAfterWithIDAttrib(String $ref_row_ID, String $new_row_ID, String $attribJSONStr){
        $new_row = $this->ownerDoc->createElement('tr');
        $new_row->setAttribute('id',$new_row_ID);
        addAttribToElement($new_row, $attribJSONStr);
        $this->insertRowAfter($ref_row_ID, $new_row);
    }

    public function setCurrRow(String $ref_row_id){
        $this->curr_row_ref = $this->getRow($ref_row_id);
        $this->curr_row_id = $ref_row_id;
        
    }

    private function getCellForRow(String $row_id, String $cell_id){
        $table_row = $this->getRow($row_id);
        $table_cells=$table_row->childNodes;//$table_cells is DOMNodeList

        for ($i = $table_cells->length; --$i >= 0; ) {
        //for ($i = 0; $i < $table_rows->length; $i++) {
            $table_cell = $table_cells->item($i); //$table_row is DOMNode
            if ($table_cell->getAttribute('id') == $cell_id){
                return $table_cell;
            }
        }
        return NULL; //if cell_id is not found
    }



    private function getCell(String $cell_id){
        //$table_row = $this->curr_row_ref;
        return $this->getCellForRow($this->curr_row_id, $cell_id);
        /*
        $table_cells=$table_row->childNodes;//$table_cells is DOMNodeList

        for ($i = $table_cells->length; --$i >= 0; ) {
        //for ($i = 0; $i < $table_rows->length; $i++) {
            $table_cell = $table_cells->item($i); //$table_row is DOMNode
            if ($table_cell->getAttribute('id') == $cell_id){
                return $table_cell;
            }
        }
        */
    }

    public function addCell(string $innerHTML, bool $head=FALSE){
        //$table_node = $this->table_node;
        $table_row = $this->curr_row_ref;
        if ($head) {
            $table_cell = insertElement($table_row, 'th', "", $innerHTML);
        } else {
            $table_cell = insertElement($table_row, 'td', "", $innerHTML);
        }

        $this->curr_cell_ref = $table_cell;
        return $table_cell; //DOMElement
    }

    public function addCellWithID(String $cell_ID, String $innerHTML){
        $table_cell = $this->addCell($innerHTML);
        if ($table_cell != NULL) {
            $table_cell->setAttribute('id',$cell_ID);
        }
        return $table_cell; //DOMElement
    }

    public function addCellWithIDAttrib(String $cell_ID, String $attribJSONStr, String $innerHTML){
        //$table_row = $this->curr_row_ref;
        //$table_cell = insertElement($table_row, 'td', "", "");
        $table_cell = $this->addCellWithID($cell_ID, $innerHTML);
        if ($table_cell != NULL) {
            addAttribToElement($this->curr_cell_ref, $attribJSONStr);
        }
        return $table_cell; //DOMElement
    }

    public function insertElementIntoCell(DOMElement $cell_ref, DOMElement $elem, String $ID="", String $attribJSONStr="", String $attribDataStr="" ) {
        if ($ID!=""){
            $elem->setAttribute('id',$ID);
        }
        if ($attribJSONStr!="") {
            addAttribToElement($elem, $attribJSONStr);
        }
        if ($attribDataStr!=""){
            addDataToElement($elem, $attribDataStr);
        }
        ($cell_ref)->appendChild($elem);
    }


    public function insertElementIntoCurrCell(DOMElement $elem, String $ID="", String $attribJSONStr="", String $attribDataStr="" ) {
        $this->insertElementIntoCell($this->curr_cell_ref, $elem, $ID, $attribJSONStr, $attribDataStr);
        //($this->curr_cell_ref)->appendChild($elem);
    }

    public function insertUserInputElementIntoCurrCell(DOMElement $elem, String $ID="", String $attribJSONStr="", String $attribDataStr="", String $attribCheckStr="" ) {
        $this->insertElementIntoCell($this->curr_cell_ref, $elem, $ID, $attribJSONStr, $attribDataStr);
        $elem_id = $elem->getAttribute('id');
        //echo "Elem ID: $elem_id";
        addDataToElement($elem, '{"i":"'.$elem_id.'"}'); 
        $obj = json_decode($attribCheckStr);
        if ($obj != NULL) {
            foreach($obj as $key=>$value){
                //$new_node->setAttribute($key, $value);
                if ($key=="ajax"){
                    addAttribToElement($elem, '{"class":"sta-'.$this->widget_id.'"}');
                }
                if ($key=="type"){
                    addDataToElement($elem, '{"t":"'.$value.'"}');
                }
                if ($key=="type_check"){
                    addDataToElement($elem, '{"tc":"'.$value.'"}');
                }
                if ($key=="minval_check"){
                    addDataToElement($elem, '{"mv1c":"'.$value.'"}');
                }
                if ($key=="minval"){
                    addDataToElement($elem, '{"mv1":"'.$value.'"}');
                }
                if ($key=="maxval_check"){
                    addDataToElement($elem, '{"mv2c":"'.$value.'"}');
                }
                if ($key=="maxval"){
                    addDataToElement($elem, '{"mv2":"'.$value.'"}');
                }
                if ($key=="minlen_check"){
                    addDataToElement($elem, '{"ml1c":"'.$value.'"}');
                }
                if ($key=="minlen"){
                    addDataToElement($elem, '{"ml1":"'.$value.'"}');
                }
                if ($key=="maxlen_check"){
                    addDataToElement($elem, '{"ml2c":"'.$value.'"}');
                }
                if ($key=="maxlen"){
                    addDataToElement($elem, '{"ml2":"'.$value.'"}');
                }
            }


        }
        //($this->curr_cell_ref)->appendChild($elem);
    }

    public function setCurrCellID(String $cell_ID){
        $table_cell = $this->curr_cell_ref;
        $table_cell->setAttribute('id', $cell_ID);
    }

    public function setCellID(DOMElement $tbl_cell, String $cell_ID){
        $tbl_cell->setAttribute('id',$cell_ID);
    }

    public function setCurrCellAttrib(String $attribJSONStr){
        //$table_cell = $this->curr_cell_ref;
        addAttribToElement($this->curr_cell_ref, $attribJSONStr);
    }

    public function setCellAttrib( DOMElement $tbl_cell, String $attribJSONStr){
        //$table_cell = $this->curr_cell_ref;
        addAttribToElement($tbl_cell, $attribJSONStr);
    }

    public function setCurrCellData(String $dataJSONStr){
        addDataToElement($this->curr_cell_ref, $dataJSONStr);
    }

    public function setCellData(DOMElement $tbl_cell, String $dataJSONStr){
        addDataToElement($tbl_cell, $dataJSONStr);
    }

    public function printCellIDs (){
        $table_row = $this->curr_row_ref;
        $table_cells=$table_row->childNodes;//$table_cells is DOMNodeList
        for ($i = 0; $i < $table_cells->length; $i++) {
            $table_cell = $table_cells->item($i);
            echo ($table_cell->getAttribute('id')."</br>");
        }
    }

    public function insertCellBefore(String $ref_cell_ID, DOMElement $new_table_cell){
        $table_row = $this->curr_row_ref;
        //$table_rows=$table_node->childNodes;//$table_rows is DOMNodeList
        $table_cell = $this->getCell($ref_cell_ID);
        $table_row->insertBefore($new_table_cell, $table_cell);
    }

    public function insertCellBeforeWithID(String $ref_cell_ID, String $new_cell_ID){
        $new_table_cell = $this->ownerDoc->createElement('td');
        $new_table_cell->setAttribute('id',$new_cell_ID);
        $this->insertCellBefore($ref_cell_ID, $new_table_cell);
    }

    public function insertCellBeforeWithIDAttrib(String $ref_cell_ID, String $new_cell_ID, String $attribJSONStr){
        $new_table_cell = $this->ownerDoc->createElement('td');
        $new_table_cell->setAttribute('id',$new_cell_ID);
        addAttribToElement($new_table_cell, $attribJSONStr);
        $this->insertCellBefore($ref_cell_ID, $new_table_cell);
    }

    public function insertCellAfter(String $ref_cell_ID, DOMElement $new_table_cell){
        $table_row = $this->curr_row_ref;
        $last_cell =$table_row->lastChild;
        $table_cell = $this->getCell($ref_cell_ID);

        if ($table_cell->isSameNode($last_cell)){
            $table_row->appendChild($new_table_cell);
        } else {
            $next_cell = $table_cell->nextSibling;
            $table_row->insertBefore($new_table_cell, $next_cell);
        }
    }

    public function insertCellAfterWithID(String $ref_cell_ID, String $new_cell_ID){
        $new_cell = $this->ownerDoc->createElement('td');
        $new_cell->setAttribute('id',$new_cell_ID);
        $this->insertCellAfter($ref_cell_ID, $new_cell);
    }

    public function insertCellAfterWithIDAttrib(String $ref_cell_ID, String $new_cell_ID, String $attribJSONStr){
        $new_cell = $this->ownerDoc->createElement('td');
        $new_cell->setAttribute('id',$new_cell_ID);
        addAttribToElement($new_cell, $attribJSONStr);
        $this->insertCellAfter($ref_cell_ID, $new_cell);
    }

    public function addControElement($PermSig, $log){
        
        //echo "addControElement::plain Sig=".$PermSig;
        $log->logfile_writeline("addControElement::plain Sig=" .$PermSig);
        $secedPermSigPriID = sec_push_val_single_entry ("perm_sig_map", $PermSig);
        //echo "addControElement::sec Sig=".$secedPermSigPriID;
        $log->logfile_writeline("addControElement::sec Sig=" .$secedPermSigPriID);

        $perm_def = new stdClass();
        $perm_def->sec = $secedPermSigPriID;
        $perm_def->targets = array();
        $this->perm_control_obj->$PermSig = $perm_def;
        //var_dump($perm_def);
        $perm_def_str = var_export($this->perm_control_obj, true);
        $log->logfile_writeline("perm_def array ".$perm_def_str);
        $log->logfile_writeline("Dumping Perm Sig Sec Map");

        $perm_sig_sec_map_str = dump_sec_map("perm_sig_map", $marker="\n");
        $log->logfile_writeline($perm_sig_sec_map_str);
        $control_div = $this->ownerDoc->createElement('div');
        $control_div->setAttribute('id', $secedPermSigPriID);
        addAttribToElement($control_div, '{"class":"pc"}');
        addDataToElement($control_div, '{"s":"e"}');

        $control_edit_span = $this->ownerDoc->createElement('img');
        $control_edit_span->setAttribute('id', "edit-".$secedPermSigPriID);
        $control_edit_span->setAttribute('src', "themes/images/pen16.png");
        $control_edit_span->setAttribute('class', "edit_style");
        $control_edit_span->textContent = "Edit";
        $control_div->appendChild($control_edit_span);

        $control_save_span = $this->ownerDoc->createElement('img');
        $control_save_span->setAttribute('id', "save-".$secedPermSigPriID);
        $control_save_span->setAttribute('src', "themes/images/save16.png");
        $control_save_span->setAttribute('class', "save_style");
        addAttribToElement($control_save_span, '{"style":"display:none"}');
        $control_save_span->textContent = "Save";
        $control_div->appendChild($control_save_span);
        
        return $control_div;
    }

    function fill_UserInput_value($userInput_sec_map, $datatbl_key_search_condition, $log=NULL){
        //$log->logfile_writeline("getting inside fill_form_value");

        //var_dump ($this->perm_control_obj);
        //echo "<br><br>";


        foreach($userInput_sec_map as $key => $value)
        {
            //$log->logfile_writeline($key." : ".$value);
            $search_str="user_id=".$this->tbl_data_owner_id;
            if ($datatbl_key_search_condition!=NULL){
                $search_str = $datatbl_key_search_condition;
            }

            $fld_data_json_str = get_data_tbl_fld_value_from_db_by_fld_sig ($this->dbconn, $value, $this->tbl_data_owner_id, $search_str);
            $fld_data_obj = json_decode($fld_data_json_str);

            /*
            $sql_perm_sig = "SELECT * FROM field_map WHERE id = ?";
            $perm_sig_stmt = $this->dbconn->prepare($sql_perm_sig);
            $perm_sig_stmt->bind_param("i",$value);
            $perm_sig_stmt->execute();
            $perm_sig_result = $perm_sig_stmt->get_result();
            $perm_sig_row = $perm_sig_result->fetch_assoc();
    
            $table_name = $perm_sig_row["tbl_name"];
            $field_name = $perm_sig_row["fld_name"];
            //$log->logfile_writeline("The table name is:: ".$table_name);
            //$log->logfile_writeline("The filed name is:: ".$field_name);
    
            $sql_profile = "SELECT * FROM $table_name WHERE user_id = ?";
            $profile_stmt = $this->dbconn->prepare($sql_profile);
            $profile_stmt->bind_param("i",$user_id);
            $profile_stmt->execute();
            $profile_stmt_result = $profile_stmt->get_result();
            $profile_result_row = $profile_stmt_result->fetch_assoc();
            $pro_fld_val = $profile_result_row[$field_name];
            */

            //var_dump($fld_data_obj);
            //echo "<br>fill_UserInput_value: END<br><br>";

            
            $pro_fld_val = $fld_data_obj->val;
            $pro_fld_sig = $fld_data_obj->fld_sig;
            //echo "bbbbb";
            //echo $pro_fld_val."<br>";
            //echo $pro_fld_sig."=".$pro_fld_val."<br>";
            $element = $this->ownerDoc->getElementById($key);
            
            //var_dump($lbl_element);
            //echo gettype("the type of the variable is:: ".$pro_fld_val).'<br>';
            //echo ("the field value is:: ".$pro_fld_val).'<br>';
            $lbl_element = $this->ownerDoc->getElementById("lbl-".$key);
            $span_elem = $lbl_element->firstChild;
            /*
            if($pro_fld_val == 0){
                $lbl_element->textContent = "vvv";
            }*/
            if($pro_fld_val === NULL) {
                addAttribToElement($element, '{"value":""}');
                
                //addAttribToElement($lbl_element, '{"value":""}');
                //$lbl_element->textContent = "";
                addAttribToElement($span_elem, '{"value":" "}');
            }else{
                addAttribToElement($element, '{"value":"'.$pro_fld_val.'"}');
                addAttribToElement($element, '{"style":"display:none"}');
                if($pro_fld_val != NULL){
                    $span_elem->textContent = $pro_fld_val;
                }else{
                    $span_elem->textContent = " ";
                }
            }


            $pro_fld_perm_sig = $fld_data_obj->perm_sig;
            //echo "<br> Fld_sig=".$key."; Perm Sig=".$pro_fld_perm_sig."br>";
            //var_dump ( ($this->perm_control_obj)->$pro_fld_perm_sig);
            //echo "<br><br>";
            (($this->perm_control_obj)->$pro_fld_perm_sig)->targets[$key] = NULL;



    
            //$element = $doc->getElementById($key);
            //$element->nodeValue = $pro_fld_val;
            
        }
        //var_dump ($this->perm_control_obj);
        //echo "<br><br>";
    }

    function assign_UserInput_perm_control($log=NULL){
        //$log->logfile_writeline("getting inside fill_form_value");

        //var_dump ($this->perm_control_obj);
        //echo "<br><br>";


        foreach($this->perm_control_obj as $key => $value) {
            //echo "assign_UserInput_perm_control::key=".$key.":: BEGIN<br>";
            $perm_def=$value;
            $perm_control_sec_id = $perm_def->sec;
            foreach($perm_def->targets as $key => $value) {
                //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; assign_UserInput_perm_control::targets=".$key."<br>";
                $element = $this->ownerDoc->getElementById($key);
                $lbl_element = $this->ownerDoc->getElementById("lbl-".$key);
                addAttribToElement($element, '{"class":"pt-'.$perm_control_sec_id.'"}');
                addAttribToElement($lbl_element, '{"class":"lbl-pt-'.$perm_control_sec_id.'"}');
                addAttribToElement($lbl_element, '{"class":"span_generic_style_class"}');

            }
            //echo "assign_UserInput_perm_control::key=".$key.":: END<br>";

        }
    }


}





/*
class perm_sig_sec_def
{
    private $Member_sec;
    private $target = array ();

    public function __construct($sec){
        $this->Member_sec = $sec;
    }
}
*/

function get_data_tbl_fld_value_from_db_by_fld_sig ($conn, $fld_map_sig, $user_id, $datatbl_key_search_condition) {

    $ret_obj["ret_code"]= 0;
    $ret_obj["val"] = "";
    /*
    $ret_obj["ret_msg"] = "";
    
    $ret_obj["user_id"] = 0;
    $ret_obj["mod_date"] ="";
    $ret_obj["mod_by"] = 0;
    $ret_obj["fld_sig"] = "";
    $ret_obj["fld_type"] = "";
    $ret_obj["perm_sig"] = "";
    $ret_obj["min"] = 0;
    $ret_obj["max"] = 0;
    $ret_obj["mandatory"] = 0;
    $ret_obj["sp_char"] = "";
    $ret_obj["active"] = 0;
    $ret_obj["secured"] = 0;
*/
    $ret_msg="";
    $ret_code = 0;
    $ret_fld_val="";
    $ret_fld_user_id=0;
    $ret_fld_mod_date="";
    $ret_fld_mod_by = 0;
    $ret_fld_sig=0;

    $field_map_fld_type = "";
    $field_map_perm_sig = "";
    $field_map_min = 0;
    $field_map_max = 0;
    $field_map_mandatory = 0;
    $field_map_sp_char = "";
    $field_map_active = 0;
    $field_map_secured = 0;

    $fld_type = "string";

    $ret_msg .= "Fetching Field Map for ".$fld_map_sig."<br>";
    $map_sql = "SELECT * FROM field_map where pri_sig='".$fld_map_sig."'";
    $ret_msg .= $map_sql."<br>";
    $result = $conn->query($map_sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ret_code=0;
   
        //$fld_map_id = $row["id"];
        $field_map_datatbl_tblname = $row["tbl_name"];
        $field_map_datatbl_fldname = $row["fld_name"];
        $field_map_fld_type = $row["fld_type"];
        $field_map_perm_sig = $row["perm_sig"];
        $field_map_min = $row["min"];
        $field_map_max = $row["max"];
        $field_map_mandatory = $row["mandatory"];
        $field_map_sp_char = $row["sp_char"];
        $field_map_active = $row["active"];
        $field_map_secured = $row["secured"];
        
        $fp_tblname = $row["fp_name"]; //Data is to be stored here
        if (strpos($fp_tblname, 'int') !== false) {
            $fld_type = "num";
        }

        $ret_msg .= "field_map_datatbl_tblname=".$field_map_datatbl_tblname."<br>";
        $ret_msg .= "field_map_datatbl_fldname=".$field_map_datatbl_fldname."<br>";
        $ret_msg .= "fp_tblname=".$fp_tblname."<br>";

        //Get the FP_id from Data Table
        $ret_msg .= "Fetching ".$field_map_datatbl_tblname.".".$field_map_datatbl_fldname." for condition ".$datatbl_key_search_condition."<br>";
        $sql_data_tbl = "SELECT ".$field_map_datatbl_fldname." FROM ".$field_map_datatbl_tblname." WHERE ". $datatbl_key_search_condition;
        $ret_msg .= $sql_data_tbl."<br>";
        $result = $conn->query($sql_data_tbl);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $ret_code=0;
            $fp_id = $row[$field_map_datatbl_fldname];
            $ret_msg .= "fp_id=".$fp_id."<br>";
            if (($fp_id==NULL) | ($fp_id=="")) {
                if ($fld_type=="num"){
                    $ret_fld_val = 0;
                } else {
                    $ret_fld_val = "";
                }
                $ret_fld_user_id=$user_id;
                $ret_fld_mod_date="";
                $ret_fld_mod_by = 0;
                $ret_fld_sig=$fld_map_sig;
            } else {
                //Get the data from FP table
                $ret_msg .= "Fetching Data from ".$fp_tblname." for ID ".$fp_id."<br>";
                $sql_fp = "SELECT * FROM ".$fp_tblname." WHERE id = ". $fp_id;
                $ret_msg .= $sql_fp."<br>";
                $result = $conn->query($sql_fp);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $ret_code=0;
                    $fld_val = $row["val"];
                    $ret_msg .= "fld_val=".$fld_val."<br>";
                    $fld_sig_from_fp = $row["fld_id"];
                    $user_id_from_fp = $row["user_id"];

                    //Check if field ID from field_map table matches with field ID in FP table
                    if ($fld_sig_from_fp != $fld_map_sig) {
                        $ret_code=4;
                        $ret_msg .= "Data corrupted!!! [". $fld_map_sig.", ".$fld_sig_from_fp."]";
                    }
                    //Check if the data retrieved from FP really belongs to the intended user
                    else if ($user_id_from_fp != $user_id) {
                        $ret_code=5;
                        $ret_msg .= "Data ownership violation!!! [". $user_id.", ".$user_id_from_fp."]";
                    }
                    else {
                        $ret_fld_val=$fld_val;
                        $ret_fld_user_id=$user_id_from_fp;
                        $ret_fld_mod_date=$row["mod_date"];
                        $ret_fld_mod_by = $row["mod_by"];
                        $ret_fld_sig=$fld_sig_from_fp;
                    }
                } else {
                    $ret_code=3;
                    //$ret_msg = $conn->error;
                }
            }
        } else {
            $ret_code=2;
            //$ret_msg = $conn->error;
        }
    } else {
        $ret_code=1;
        //$ret_msg = $conn->error;
    }

    $ret_obj["ret_code"]= $ret_code;
    $ret_obj["val"] = $ret_fld_val;
/*
    $ret_obj["ret_msg"] = $ret_msg;
    
    $ret_obj["user_id"] = $ret_fld_user_id;
    $ret_obj["mod_date"] = $ret_fld_mod_date;
    $ret_obj["mod_by"] = $ret_fld_mod_by;
    $ret_obj["fld_sig"] = $ret_fld_sig;
    $ret_obj["fld_type"] = $field_map_fld_type;
    $ret_obj["perm_sig"] = $field_map_perm_sig;
    $ret_obj["min"] = $field_map_min;
    $ret_obj["max"] = $field_map_max;
    $ret_obj["mandatory"] = $field_map_mandatory;
    $ret_obj["sp_char"] = $field_map_sp_char;
    $ret_obj["active"] = $field_map_active;
    $ret_obj["secured"] = $field_map_secured;
    */

    //return json_encode($ret_obj);
    return ($ret_obj);

}

//input: fld_sig, fld_val, $user_id, keyname, keyval, mod_by
function update_data_tbl_fld_value_in_db_by_fld_sig ($conn, $fld_map_sig, $fld_val, $fp_user_id, $fp_mod_by, $datatbl_key_search_condition) {

    $ret_msg="";
    $ret_code = 0;
    $fld_type = "string";

    $ret_msg .= "Fetching Field Map for ".$fld_map_sig."<br>";
    $map_sql = "SELECT * FROM field_map where pri_sig='".$fld_map_sig."'";
    $ret_msg .= $map_sql."<br>";
    $result = $conn->query($map_sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ret_code=0;
   
        //$fld_map_id = $row["id"];
        $datatbl_tblname = $row["tbl_name"];
        $datatbl_fldname = $row["fld_name"];
        $fp_tblname = $row["fp_name"]; //Data is to be stored here

        if (strpos($fp_tblname, 'int') !== false) {
            $fld_type = "num";
        }

        //$ret_msg .= "fld_map_id=".$fld_map_id."<br>";
        $ret_msg .= "datatbl_tblname=".$datatbl_tblname."<br>";
        $ret_msg .= "datatbl_fldname=".$datatbl_fldname."<br>";
        $ret_msg .= "fp_tblname=".$fp_tblname."<br>";

        //Save the value into target FP table
        $ret_msg .= "Inserting Value <b>".$fld_val."</b> into <i>".$fp_tblname."</i><br>";
        $bind_spec = "iiss";
        if ($fld_type=="num"){
            $bind_spec = "iisi";
        }

        $fp_sql = "INSERT INTO ".$fp_tblname." (user_id, mod_by, fld_id, val) VALUES (?, ?, ?, ?)";
        $fp_sql_temp = $conn->prepare($fp_sql);
        if($fp_sql_temp){
            //echo "prepare success<br>";
            $bind_temp = $fp_sql_temp->bind_param($bind_spec,$fp_user_id, $fp_mod_by, $fld_map_sig, $fld_val);
            if($bind_temp){
                //echo "bind success<br>";
                $exe_temp = $fp_sql_temp->execute(); 
                if($exe_temp){
                    //echo "execution sucess<br>";
                }else{
                    //echo "execution failed<br>";
                }
            }else{
                //echo "bind failed<br>";
            }

        }else{
            //echo "prepare failed<br>";
        }
        
        $ret_msg .= $fp_sql."<br>";
        $last_fp_id = 0;
        //if ($conn->query($fp_sql) === TRUE) {
        if ($exe_temp) {
            $ret_code=0;
            $last_fp_id = $conn->insert_id;
            $ret_msg .= "New record created successfully in FP with ID ".$last_fp_id."<br>";
            //Save FP_id into tagret data table
            $ret_msg .= "Updating ".$datatbl_tblname.".".$datatbl_fldname." with value ".$last_fp_id."<br>";
            $sql_data_tbl = "UPDATE ".$datatbl_tblname." SET ".$datatbl_fldname."=".$last_fp_id. " WHERE ". $datatbl_key_search_condition;
            $ret_msg .= $sql_data_tbl."<br>";
            if ($conn->query($sql_data_tbl) === TRUE) {
                $ret_code=0;
                $ret_msg .= "Record updated successfully in ".$datatbl_tblname.".".$datatbl_fldname." with value ".$last_fp_id."<br>";
            } else {
                $ret_code=3;
                $ret_msg = $conn->error;
            }

        } else {
            //echo "Error: " . $sql . "<br>" . $conn->error;
            echo "getting inside this(error)<br>";
            $ret_code=2;
            $ret_msg = $conn->error;
        }
    } else {
        $ret_code=1;
        $ret_msg = $conn->error;
        //echo "0 results";
    }

    $ret_obj["ret_code"]= $ret_code;
    $ret_obj["ret_msg"]= $ret_msg;
    return $ret_obj;
}


?>