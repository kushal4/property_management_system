/**
 * @author Arnab Ghosh <sensennium.com>
 * @version 1.2.1
 *
 * 
 */

(function ($) {

    'use strict';

    // it only does '%s', and return '' when arguments are undefined
    var sprintf = function (str) {
        var args = arguments,
            flag = true,
            i = 1;

        str = str.replace(/%s/g, function () {
            var arg = args[i++];

            if (typeof arg === 'undefined') {
                flag = false;
                return '';
            }
            return arg;
        });
        return flag ? str : '';
    };

   
    function TableCreator($el, options) {
        //alert("Inside table creator"+$el.attr("id"));
        console.log($el);
        var that=this;
        var content=$("<div/>").attr("id",$el.attr("id")+"_tc");
        var headerdiv=$("<div/>").attr("id",$el.attr("id")+"_tc_hdiv").addClass(options.headingStyle== '' ? '' :options.headingStyle);
        var headerspan=$("<span/>").addClass(options.headingTextStyle== '' ? '' :options.headingStyle).attr("id",$el.attr("id")+"_tc_hdiv_span").html(options.headingText);
        $el.append(content);
        content.append(headerdiv);
        headerdiv.append(headerspan);

        var table_cont_div=$("<div/>").attr("id",$el.attr("id")+"_tc_cdiv").addClass(options.contentStyle== '' ? '' :options.contentStyle);
        $el.append(table_cont_div);
        var content_table=$("<table>").attr("id",$el.attr("id")+"_tc_ctab").addClass(options.contentTableStyle== '' ? '' :options.contentTableStyle);
        //this._telem=content_table;
        table_cont_div.append(content_table);
        var thead=$('<thead/>').addClass(options.tableHeaderStyle);
        $(content_table).append(thead);
        var thr=$("<tr>").attr("id","thr");
        console.log(thr);
        thead.append(thr);
        console.log(thead);
        $(options.tableCols).each(function(k,v){
            var colHeadObj = v;
            //console.log("colHeadObj");
            //console.log(colHeadObj);
            var colHeadText = colHeadObj.text;
            var colHeadCls = colHeadObj.class;
            //console.log(colHeadText);
            thr.append($("<th>").addClass(colHeadCls).html(colHeadText).attr("data-sig",v.colsig));
        })
        var tbody=$('<tbody/>').addClass(options.tableHeaderStyle);
        $(content_table).append(tbody);
        this._telem=tbody;  
        this._tref=content_table;

    }

    TableCreator.prototype = {
        constructor: TableCreator,
         /*functions prefixed with _ is private function */
         _tdata:{},
         _telem:null,
         _tref:null,
         _odd_row_cls:true,
        _init: function () {//private function dont access it from outside
            /*here u can register events or anything after dom has completed*/ 
            var that = this;
        },

        _events: function () {//private function dont access it from outside
           
        },
        createRow:function(data){
            
            if(data.sig==undefined || data.tablerow==undefined){
                console.warn("invalid argument type");
            }
            else{
               
                console.log(this._tdata);
                if(this._tdata[data.sig]==undefined){
                   
                    this._tdata[data.sig]=data.tablerow;
                   
                    data.tablerow.attr("data-rowid",data.sig);
                    console.error(data.tablerow);
                   
                    this._telem.append(data.tablerow);
                    if (this._odd_row_cls) {
                        data.tablerow.addClass("odd_row");
                    } else {
                        data.tablerow.addClass("even_row");
                    }
                    this._odd_row_cls = !this._odd_row_cls;
                    console.log("Data sig:"+data.sig);
                }
                else{
                    console.warn("rowid exists");
                }
              
            }
          

        },
        refreshRow:function(data){
            console.log(data.tablerow);
            if(data.sig==undefined || data.tablerow==undefined){
                console.warn("invalid argument type");
            }
            else{
                if(this._tdata[data.sig]!=undefined){
                    console.log(data.sig);
                    this._tdata[data.sig]=data.tablerow;
                    var $row = this._telem.find("[data-rowid='" + data.sig + "']");
                    $row.replaceWith(data.tablerow);
                    data.tablerow.attr("data-rowid",data.sig);
                   
                    if (this._odd_row_cls) {
                        data.tablerow.addClass("odd_row");
                    } else {
                        data.tablerow.addClass("even_row");
                    }
                    this._odd_row_cls = !this._odd_row_cls;
                }

            }

        },
        clearTable:function(){
            this._telem.find("tr").remove();
            //this._telem.empty();
            this._tdata={};
            this._odd_row_cls = true;
            
        },
        getRow:function(sig){
           return this._tdata[sig];
        },
        hideColumn:function(sig){
            if(sig==undefined){
              console.warn("no sig specified")
            }
            else{
                console.error(sig);
                console.log(this._tref.attr("id"));
                var index = this._tref.find('[data-sig="'+sig+'"]').index();
                var selector=sprintf('td:eq(%s),th:eq(%s)',index,index);
                console.error(selector);
                this._tref.find("tr").find(selector).hide();

                console.error("Index"+index);
               
                //this.t_elem.find("tr").find('td:eq(1),th:eq(1)').remove();

            }

        },
        showColumn:function(sig){
            if(sig==undefined){
                console.warn("no sig specified")
              }
              else{
                console.error(sig);
                console.log(this._tref.attr("id"));
                var index = this._tref.find('[data-sig="'+sig+'"]').index();
                var selector=sprintf('td:eq(%s),th:eq(%s)',index,index);
                console.error(selector);
                this._tref.find("tr").find(selector).show();

                console.error("Index"+index);
               
  
              }
        }


    };

    $.fn.tablecreator = function () {

      
        var option = arguments[0],
            args = arguments,

            value,
            allowedMethods = [
                'createRow', 'clearTable',
                'getRow','hideColumn','showColumn','refreshRow'
            ];
           console.log(this.length);
           
        this.each(function () {
            console.log(this);
            var $this = $(this),
                data = $this.data('tablecreator'),
                options = $.extend({}, $.fn.tablecreator.defaults,
                    $this.data(), typeof option === 'object' && option);

            if (!data) {
                data = new TableCreator($this, options);
                $this.data('tablecreator', data);
            }

            if (typeof option === 'string') {
                if ($.inArray(option, allowedMethods) < 0) {
                    throw 'Unknown method: ' + option;
                }
                value = data[option](args[1]);
            } else {
                data._init();
                if (args[1]) {
                    value = data[args[1]].apply(data, [].slice.call(args, 2));
                }
            }
       });

        return typeof value !== 'undefined' ? value : this;
    };

    $.fn.tablecreator.defaults = {
        headingStyle:'headingstyle',/**style classes appends to header element */
        headingTextStyle:'headingtextstyle',/**style classes appends to header element span */
        headingText:"Steffi",
        contentStyle:'contentstyle',/**style classes appends to content div */
        contentTableStyle:'contentTableStyle',/**style classes appends to content table */
        //tableCols:["column1","column2"],
        tableCols:[{text:'column1', class:'class1',colsig:'colsig1'},{text:'column2', class:'class2',colsig:'colsig2'}],
        tableHeaderStyle:'tableHeaderStyle',
        formatRelativeToParent: undefined,/** */
        /*Exposed outer function example*/
        styler: function () {
            return false;
        },
        textTemplate: function ($elm) {
            return $elm.html();
        },
     
    };
})(jQuery);
