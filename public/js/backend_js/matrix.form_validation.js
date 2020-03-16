
$(document).ready(function(){


    //ajax request for check current password and old password  to be match?
    $("#current_pwd").keyup(function(){
        var current_pwd = $("#current_pwd").val();
        $.ajax({
            type:'get',
            url:'/admin/check-pwd',
            data:{current_pwd:current_pwd},
            success:function(resp){
                //alert(resp);
                if(resp=="false"){
                    $("#chkPwd").html("<font color='red'>Current Password is Incorrect</font>");
                }else if(resp=="true"){
                    $("#chkPwd").html("<font color='green'>Current Password is Correct</font>");
                }
            },error:function(){
                alert("Error");
            }
        });
    });
    //ajax


	// $("#delCat").click(function(){
	// 	if(confirm('Are you sure you want to delete this Category?')){
	// 		return true;
	// 	}
	// 	return false;
	// });
	
	$(document).on('click','.deleteCategoryRecord',function(e){
		var id=$(this).attr('rel'); 
		var deleteFunction=$(this).attr('rel1');
		
		swal({
			title:"Are you want to delete this category",
			text:"you will not be able to recover this record again!",
			type:"warning",
			showCloseButton: true,
			showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtontext: "Yes, Delete it!",
		},
		function(){
			window.location.href="/admin/"+deleteFunction+"/"+id;
		}
	);
});


    $('input[type=checkbox],input[type=radio],input[type=file]').uniform();
	
	$('select').select2();
	
	// Form Validation
    $("#basic_validate").validate({
		rules:{
			required:{
				required:true
			},
			email:{
				required:true,
				email: true
			},
			date:{
				required:true,
				date: true
			},
			url:{
				required:true,
				url: true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});



    // Add Category Validation
    $("#add_category").validate({
        rules:{
            category_name:{
                required:true
            },
            description:{
                required:true,
            },
            url:{
                required:true,
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });


	// Add Product Validation
    $("#add_product").validate({
		rules:{
			category_id:{
				required:true
			},
			product_name:{
				required:true
			},
			product_code:{
				required:true,
			},
			product_color:{
				required:true,
			},
			price:{
				required:true,
				number:true
			},
			image:{
				required:true,
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	// edit Product Validation
    $("#edit_product").validate({
		rules:{
			category_id:{
				required:true
			},
			product_name:{
				required:true
			},
			product_code:{
				required:true,
			},
			product_color:{
				required:true,
			},
			price:{
				required:true,
				number:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	// $("#delProduct").click(function(){
	// 	if(confirm('Are you sure you want to delete this Product?')){
	// 		return true;
	// 	}
	// 	return false;
	// });
	$(document).on('click','.deleteRecord',function(e){
		var id=$(this).attr('rel'); 
		var deleteFunction=$(this).attr('rel1');
		
		swal({
			title:"Are you want to delete this product",
			text:"you will not be able to recover this record again!",
			type:"warning",
			showCloseButton: true,
			showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtontext: "Yes, Delete it!",
		},
		function(){
			window.location.href="/admin/"+deleteFunction+"/"+id;
		}
	);
	});
//add files dinamicaly
	$(document).ready(function(){
	    var maxField = 10; //Input fields increment limitation
	    var addButton = $('.add_button'); //Add button selector
	    var wrapper = $('.field_wrapper'); //Input field wrapper
	    var fieldHTML = '<div class="controls field_wrapper" style="margin-left:-2px;"><input type="text" name="sku[]" style="width:120px"/>&nbsp;<input type="text" name="size[]" style="width:120px"/>&nbsp;<input type="text" name="price[]" style="width:120px"/>&nbsp;<input type="text" name="stock[]" style="width:120px"/><a href="javascript:void(0);" class="remove_button" title="Remove field">Remove</a></div>'; //New input field html 
	    var x = 1; //Initial field counter is 1
	    $(addButton).click(function(){ //Once add button is clicked
	        if(x < maxField){ //Check maximum number of input fields
	            x++; //Increment field counter
	            $(wrapper).append(fieldHTML); // Add field html
	        }
	    });
	    $(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
	        e.preventDefault();
	        $(this).parent('div').remove(); //Remove field html
	        x--; //Decrement field counter
	    });
	});
	
	$("#number_validate").validate({
		rules:{
			min:{
				required: true,
				min:10
			},
			max:{
				required:true,
				max:24
			},
			number:{
				required:true,
				number:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});
	
	$("#password_validate").validate({
		rules:{
			old_pasd:{
				required: true,
				minlength:6,
				maxlength:20
			},
            new_pwd:{
				required: true,
				minlength:6,
				maxlength:20
			},
			conf_pwd:{
				required:true,
				minlength:6,
				maxlength:20,
				equalTo:"#pwd"
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

});
