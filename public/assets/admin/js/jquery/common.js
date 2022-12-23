// tostr message code

const { Alert } = require("bootstrap");
const { defaultsDeep } = require("lodash");

    // Toastr Options
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
         timeOut: 10000
    }

    
    
   
    function deletedata(type, id, url, tableIdName) {
        $.ajaxSetup({   
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        swal({
            title: "Are you sure You want to Delete It ?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDeleteData) => {
            if (willDeleteData) {
                if(type == 2){
                    id = [];
                    $('input[name=case]:checked').each(function(i)
                    {
                        id[i] = $(this).val();

                    });
                    if (id.length <=0) {
                        alert("Please select row."); 
                        return flase;
                    }
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        'id': id,
                        'type': type
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.success)
                        {
                            swal(response.message, {
                                icon: "success",
                            });
                            $(tableIdName).DataTable().ajax.reload();
                        }
                        else
                        {
                            swal(response.message, "", "error");
                        }
                    }
                });
            } else {
                swal("Cancelled", "", "error");
            }
        }); 
    }

    function openaddmodel(name, id, url, formName, labelName, modalName) {
        $.ajaxSetup({   
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if (id == 0) {
            $(formName).trigger('reset');
            $('#name').removeClass('is-invalid');
            $('#name_ar').removeClass('is-invalid');  
            $('#phone').removeClass('is-invalid');
            $('#price').removeClass('is-invalid');
            $('#days').removeClass('is-invalid');

            toastr.clear();
            $(labelName).text(name); 
            $(modalName).modal('show');

        } else {
            
            $(formName).trigger('reset');
            $('#name').removeClass('is-invalid');
            $('#name_ar').removeClass('is-invalid'); 
            $('#phone').removeClass('is-invalid');
            $('#price').removeClass('is-invalid');
            $('#days').removeClass('is-invalid');

            toastr.clear();
            $.ajax({
                type: "POST",
                url:url,
                dataType: "JSON",
                data: {
                    // '_token': "csrf_token()",
                    'id': id,
                },
                success: function(response) {
                    if (response.success)
                    {
                        const datas = response.data;
                    $default_image = "/public/image/default-image.jpeg";
                    var images = (datas.image) ? datas.image : default_image;
                    console.log(images);
                        $('#name').val(datas.name);
                        $('#name_ar').val(datas.name_ar);
                        $('#phone').val(datas.phone);
                        $('#price').val(datas.price);
                        $('#days').val(datas.days);
                        $('#id').val(datas.id);
                        $('#Image').html('')
                        $('#Image').show();

                    } else {
                        toastr.error(response.message);
                    }
                }
            }); 
            $(labelName).text(name); 
            $(modalName).modal('show');
        }
    }



 // Function for load all Data
function loadData(type,tableIdName,url)
{
    if(type==3 || type==4 || type==5){
       
        var  columns = [
            {data:'checkbox', name:'checkbox', "orderable":false,"bSortable": true},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'name_ar', name: 'name_ar'},
            {data: 'actions', name: 'actions', "orderable":false,"bSortable": true},

        ]
    }
    else if(type==11)
    {
        var  columns = [
            {data:'checkbox', name:'checkbox', "orderable":false,"bSortable": true},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'phone', name: 'phone'},
            {data: 'actions', name: 'actions', "orderable":false,"bSortable": true},

        ]
    }else if(type==8)
    {
        var  columns = [
            {data:'checkbox', name:'checkbox', "orderable":false,"bSortable": true},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'price', name: 'price'},
            {data: 'days', name: 'days'},
            {data: 'actions', name: 'actions', "orderable":false,"bSortable": true},

        ]

    }else if(type==7)
    {
        
        var  columns = [
            {data:'checkbox', name:'checkbox', "orderable":false,"bSortable": true},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'name_ar', name: 'name_ar'},
            {data: 'Image', name: 'Image'},
            {data: 'actions', name: 'actions', "orderable":false,"bSortable": true},

        ]

    }
    var dataTable = $(tableIdName).DataTable();
    dataTable.destroy();
     dataTable = $(tableIdName).DataTable({
        processing: true,
        serverSide: true,
        ajax:url,
        "order": [0, 'desc'],
        columns    
    });
}

// save Chronic-diseases
    function saveUpdateData(type,modelname,formName,tableIdName, url)
    {
        
        var dType = type;
        if (dType == 'add') {
            
            var redirectUrl = url ;
        } else {
            var redirectUrl = url;

        }
        myFormData = new FormData(document.getElementById(formName));
        $('#name').removeClass('is-invalid');
        $('#name_ar').removeClass('is-invalid');
        $('#phone').removeClass('is-invalid');
        $('#price').removeClass('is-invalid');
        $('#days').removeClass('is-invalid');
        $('#Image').html('')
        $('#Image').hide();
        
        toastr.clear();
        
        $.ajax({
            type: "POST",
            url: redirectUrl,
            data: myFormData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "JSON",
            success: function(response)
            {
            
                if (response.success)
                {
                    $(formName).trigger('reset');
                    $(modelname).modal('hide');
                    toastr.success(response.message);
                    $(tableIdName).DataTable().ajax.reload();
                  
                }
                else
                {
                    $(formName).trigger('reset');
                    $(modelname).modal('hide');
                    toastr.error(response.message);
                }
            },
            error: function(response) {
                var validationError = (response?.responseJSON?.errors) ? response.responseJSON.errors : '';

                if (validationError != '') {
                    var nameError = (validationError.name) ? validationError.name : '';
                    var name_arError = (validationError.name_ar) ? validationError.name_ar : '';
                    var phoneError = (validationError.phone) ? validationError.phone : '';
                    var priceError = (validationError.price) ? validationError.price : '';
                    var daysError = (validationError.days) ? validationError.days : '';
                    

                    // Name Error
                    if (nameError != '') {
                        $('#name').addClass('is-invalid');
                        toastr.error(nameError);
                    }

                    // Name Arabic Error
                    if (name_arError != '') {
                        toastr.error(name_arError);
                        $('#name_ar').addClass('is-invalid');
                    }

                     // phone Error
                     if (phoneError != '') {
                        toastr.error(phoneError);
                        $('#phone').addClass('is-invalid');
                    }

                    // price Error
                    if (priceError != '') {
                        toastr.error(priceError);
                        $('#price').addClass('is-invalid');
                    }
                    // days Error
                    if (daysError != '') {
                        toastr.error(daysError);
                        $('#days').addClass('is-invalid');
                    }

                 }
            }
        });

       
        // $('#masters').on('click', function(e) {
        //     if($(this).is(':checked',true))  
        //     {
        //        $(".sub_chk").prop('checked', true);  
        //     } else {  
        //        $(".sub_chk").prop('checked',false);  
        //     }  
        //    });

      
         
        
}