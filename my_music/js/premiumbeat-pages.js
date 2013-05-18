function Pager(tableName, itemsPerPage) {
    this.tableName = tableName;
    this.itemsPerPage = itemsPerPage;
    this.currentPage = 1;
    this.pages = 0;
    this.inited = false;
    
    this.showRecords = function(from, to) {        
        var rows = document.getElementById(tableName).rows;
        // i starts from 1 to skip table header row
        for (var i = 1; i < rows.length; i++) {
            if (i < from || i > to)  
                rows[i].style.display = 'none';
            else
                rows[i].style.display = '';
        }
    }
    
    this.showPage = function(pageNumber) {
    	if (! this.inited) {
    		alert("not inited");
    		return;
    	}

        var oldPageAnchor = document.getElementById('pg'+this.currentPage);
        oldPageAnchor.className = 'pg-normal';
        
        this.currentPage = pageNumber;
        var newPageAnchor = document.getElementById('pg'+this.currentPage);
        newPageAnchor.className = 'pg-selected';
        
        var from = (pageNumber - 1) * itemsPerPage + 1;
        var to = from + itemsPerPage - 1;
        this.showRecords(from, to);
    }   
    
    this.prev = function() {
        if (this.currentPage > 1)
            this.showPage(this.currentPage - 1);
    }
    
    this.next = function() {
        if (this.currentPage < this.pages) {
            this.showPage(this.currentPage + 1);
        }
    }                        
    
    this.init = function() {
        var rows = document.getElementById(tableName).rows;
        var records = (rows.length - 1); 
        this.pages = Math.ceil(records / itemsPerPage);
        this.inited = true;
    }

    this.showPageNav = function(pagerName, positionId) {
    	if (! this.inited) {
    		alert("not inited");
    		return;
    	}
    	var element = document.getElementById(positionId);
    	
    	var pagerHtml = '<span onclick="' + pagerName + '.prev();" class="pg-normal"> <img style="width:38px;height:13px;vertical-align:bottom;" src="my_music/previous_page.png" title="&#171;" alt=" &#171;&#171; " /> </span> | ';
        for (var page = 1; page <= this.pages; page++) 
            pagerHtml += '<span style="font-weight:bold;" id="pg' + page + '" class="pg-normal" onclick="' + pagerName + '.showPage(' + page + ');">' + page + '</span> | ';
        pagerHtml += '<span onclick="'+pagerName+'.next();" class="pg-normal"> <img style="width:38px;height:13px;vertical-align:bottom;" src="my_music/next_page.png" title="&#187;" alt=" &#187;&#187; " /></span>';            
        
        element.innerHTML = pagerHtml;
    }
}
function regenerate()
{
	window.location.reload()
}

function regenerate2()
{
	if (document.layers)
	{
		appear()
		setTimeout("window.onresize=regenerate",450)
	}
}

function changetext(whichcontent)
{
	if (document.all||document.getElementById)
	{
		cross_el=document.getElementById? document.getElementById("descriptions"):document.all.descriptions
		cross_el.innerHTML='<div style="font-family:Arial Narrow Bold;font-size:small;">'+whichcontent+'</div>'
	}
	else if (document.layers)
	{
		document.d1.document.d2.document.write('<div style="font-family:Arial Narrow Bold;font-size:small;">'+whichcontent+'</div>')
		document.d1.document.d2.document.close()
	}

}

function appear()
{
	document.d1.visibility='show'
}