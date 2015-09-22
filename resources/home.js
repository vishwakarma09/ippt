$(document).ready(function(){
	InitSearchBox();
	InitTblCompanies();
});

function InitTblCompanies(){
	$('#TblCompany').remove();
	//Check if wrapper exists and then delete
	if(('#TblInnerAttributeValue_wrapper').length)
	{
		$('#TblCompany_wrapper').remove();
	}
	var thead = '<table id = '+'TblCompany'+' width=95%>';
	
	thead += '<thead>';
	thead += '<tr>' ;
	thead += '<th>File</th>';
	thead += '<th>View</th>';
	thead += '<th>Download</th>';
	thead += '<th>Share</th>';
	thead += '</tr>';
	thead += '</thead>';
	
	thead += '</table>';
	
	$('#DivCompany').append(thead);
	
	var AttributeTable = $('#TblCompany').dataTable( {
		"ajax": {
			"url": "ajax/AjaxInventoryAttributeController.php?cservice=GetCompanyListings",
			"dataSrc": "demo"
		},
    	"lengthMenu": [  25, 50, 100, 500 ],
    	"iDisplayLength": 25,
		"aoColumnDefs": [
		         		{ 'bSortable': false, 'aTargets': [3,4,5] },
		         		{ "sClass": "DataTblColAlignLeft",'aTargets': [0,1] }
		         		]
	});
	
    //Calling rebinding table atributes
    RebindTblCompanies(); 
}

function RebindTblCompanies(){
    $('#TblCompany tbody').on( 'click', 'button', function () {
		var event=$(this).attr('id');
		var attr = $(this).parents('tr').find("td:eq(0)").text();
		var attrv = $(this).parents('tr').find("td:eq(1)").text();
		
		if(event=='BtnDeleteAttr'){
			DeleteAttribute(attr,attrv);
		}
		else if(event=='BtnEditAttr'){
	    	ShowAttributeForm(true, 'u', attr, attrv);
		}else{
			$("#hdnAttributeCodeForPopup").val(attr);
			$('#DivAttributevalueDetails').empty();
	      	LoadAttributeValueDiv(true, attr);
	      	InitTblAttrValue(attr);
		}
	});
    
    var $input = $('<button id="BtnAddTag" name="BtnAddTag" style="float: right; margin:5px;">Add New Attribute</button>');
    $input.appendTo($("#TblCompany_filter"));
    
    $("#BtnAddTag").on('click',function(){
		ShowAttributeForm(true, 'i');
	});
}

function InitSearchBox(){
$("#wooSel").select2({
		  placeholder: "Search a company",
		  ajax: {
			url: "http://localhost/edgar/ajax/search.php",
			dataType: 'json',
			delay: 250,
			data: function (params) {
			  return {
				q: params.term, // search term
				page: params.page
			  };
			},
			id: function(data){return data.items.id},
			processResults: function (data, page) {
			  // parse the results into the format expected by Select2.
			  // since we are using custom formatting functions we do not need to
			  // alter the remote JSON data
			  /*
			  return {
				results: data.items
			  };
			  */
			  
			  // you should map the id and text attributes on version 4.0
			  
                var select2Data = $.map(data.items, function (obj) {
                    obj.id = obj.id;
                    obj.text = obj.company;
                    return obj;
                });

                return {
                    results: select2Data,
                    pagination: {
                        more: data.more
                    }
                };
			},
			cache: true
		  },
		  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		  minimumInputLength: 1,
		  templateResult: formatRepo, // omitted for brevity, see the source of this page
		  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
		}).on("change", function(e){
				console.log($(this).text());
				location.assign($(this).text());
		});
}

function LaunchProduct(){
		alert("launch it!");	
	}
	
  function formatRepo (repo) {
    if (repo.loading) return repo.text;
	
    var markup = '<div class="clearfix">' +
    repo.company +
	'</div>';
	
    return markup;
  }

  function formatRepoSelection (repo) {
   // return repo.cityID || repo.cityDesc;
   return repo.company;
  }