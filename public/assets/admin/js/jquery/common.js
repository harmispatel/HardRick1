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
            $('#image').removeClass('is-invalid');
            $('#address').removeClass('is-invalid');
            $('#birth_date').removeClass('is-invalid');
            $('#email').removeClass('is-invalid');
           $('#password').removeClass('is-invalid');
           $('#dragsAllergy').removeClass('is-invalid');

            $('#specialists').val('').change();
            $('#Foodallergy').val('').change();
            $('#dragsAllergy').val('').change();
            $('#Chronicdiseases').val('').change();
            $('#pic').attr('src','');
            $('#pic').show();
            $('#pic1').attr('src','');
            $('#pic1').show();
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
            $('#image').removeClass('is-invalid');
            $('#address').removeClass('is-invalid');
            $('#birth_date').removeClass('is-invalid');
            $('#email').removeClass('is-invalid');
            $('#password').removeClass('is-invalid');
            $('#dragsAllergy').removeClass('is-invalid');
            $('#specialists').val('').change();

            $('#Foodallergy').val('').change();
            $('#dragsAllergy').val('').change();
            $('#Chronicdiseases').val('').change();
            $('#pic').attr('src','');
            $('#pic').show();
            $('#pic1').attr('src','');
            $('#pic1').show();
            toastr.clear();
            $.ajax({
                type: "POST",
                url:url,
                dataType: "JSON",
                data: {
                    'id': id,
                },
                success: function(response) {
                    if (response.success)
                    {
                        const datas = response.data;
                        console.log(datas);
                        $('#name').val(datas.name);
                        $('#name_ar').val(datas.name_ar);
                        $('#phone').val(datas.phone);
                        $('#price').val(datas.price);
                        $('#days').val(datas.days);
                        $('#id').val(datas.id);
                        $('#email').val(datas.email);
                        $('#address').val(datas.address);
                        $('#birth_date').val(datas.birth_date);
                        $('#Bloodtype').val(datas.bloodType);
                        $('#averagehour').val(datas.averagehour);
                        $('#certificates').val(datas.certificates);
                        $('#discounts').val(datas.discounts);
                        $('#discount_rule').val(datas.discount_rule);
                        $('#insta_link').val(datas.insta_link);
                        $('#facebook_link').val(datas.facebook_link);
                        $('#yearofexp').val(datas.yearofexp);
                        $('#price_of_ticket').val(datas.price_of_ticket);
                        $('#info').val(datas.info);
                        $('#datefrom').val(datas.from_date);
                        $('#dateto').val(datas.to_date);
                        $('#status').val(datas.status);
                        $('#status_data').val(datas.status_data);
                        $('#addTime').html();
                        $('#addTime').html(datas.html);
                        $('#workHours').remove();
                        $("#specialists").select2({
                            multiple: true,
                          });
                          $('#specialists').val(datas.specialist_id).trigger('change');
                        $("#Foodallergy").select2({
                            multiple: true,
                          });
                          $('#Foodallergy').val(datas.Foodallergy_id).trigger('change');
                          $("#dragsAllergy").select2({
                            multiple: true,
                          });
                          $('#dragsAllergy').val(datas.dragsallergy_id).trigger('change'); 
                           $("#Chronicdiseases").select2({
                            multiple: true,
                          });
                          $('#Chronicdiseases').val(datas.chronicdiseases_id).trigger('change');
                          $('#pic1').html('');
                          $('#pic1').attr('src',datas.clinicImage);
                          $('#pic1').show();
                        $('#pic').html('');
                        $('#pic').attr('src',datas.image);
                        $('#pic').show();

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

    }else if(type==9)
    {
        var  columns = [
            // {data:'checkbox', name:'checkbox', "orderable":false,"bSortable": true},
            {data: 'id', name: 'id'},
            {data: 'specialist', name: 'specialist'},
            {data: 'user', name: 'user'},
            {data: 'description', name: 'description'},
            {data: 'doctor', name: 'doctor'},

        ]
    }else if(type==10)
    {
        var  columns = [
            // {data:'checkbox', name:'checkbox', "orderable":false,"bSortable": true},
            {data: 'id', name: 'id'},
            {data: 'doctor', name: 'doctor'},
            {data: 'username', name: 'username'},
            {data: 'book_date', name: 'book_date'},
            {data: 'book_time', name: 'book_time'},
            {data: 'book_day', name: 'book_day'},
            {data: 'discount', name: 'discount'},
            {data: 'price', name: 'price'},
            {data: 'location', name: 'location'},

        ]

    }else if(type==2){
        var  columns = [
            {data:'checkbox', name:'checkbox', "orderable":false,"bSortable": true},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'user_status', name: 'user_status'},
            {data: 'Image', name: 'Image'},
            {data: 'actions', name: 'actions', "orderable":false,"bSortable": true},

        ]

    }
    else if(type==1){
        var  columns = [
            {data:'checkbox', name:'checkbox', "orderable":false,"bSortable": true},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'user_status', name: 'user_status'},
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
        $('#address').removeClass('is-invalid');
        $('#birth_date').removeClass('is-invalid');
        $('#email').removeClass('is-invalid');
        $('#password').removeClass('is-invalid');
        $('#dragsAllergy').removeClass('is-invalid');



        $('#pic').html('')
        $('#pic').hide();
        
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
                console.log(validationError);
                if (validationError != '') {
                   
                    var nameError = (validationError.name) ? validationError.name : '';
                    var name_arError = (validationError.name_ar) ? validationError.name_ar : '';
                    var phoneError = (validationError.phone) ? validationError.phone : '';
                    var priceError = (validationError.price) ? validationError.price : '';
                    var daysError = (validationError.days) ? validationError.days : '';
                    var imageError = (validationError.image) ? validationError.image : '';
                    var addressError = (validationError.address) ? validationError.address : '';
                    var birth_dateError = (validationError.birth_date) ? validationError.birth_date : '';
                    var emailError = (validationError.email) ? validationError.email : '';
                    var passwordError = (validationError.password) ? validationError.password : '';
                    var dragsError = (validationError.dragsAllergy) ? validationError.dragsAllergy: '';
                    // var passwordError = (validationError.password) ? validationError.password : '';
                    // var passwordError = (validationError.password) ? validationError.password : '';
                    

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

                    // Image Error
                    if (imageError != '') {
                        toastr.error(imageError);
                        $('#image').addClass('is-invalid');
                    }
                      // address Error
                      if (addressError != '') {
                        toastr.error(addressError);
                        $('#address').addClass('is-invalid');
                    }
                     // birth Error
                     if (birth_dateError != '') {
                        toastr.error(birth_dateError);
                        $('#birth_date').addClass('is-invalid');
                    }
                      // Email Error
                      if (emailError != '') {
                        toastr.error(emailError);
                        $('#email').addClass('is-invalid');
                    }
                      // password Error
                      if (passwordError != '') {
                        toastr.error(passwordError);
                        $('#password').addClass('is-invalid');
                    }
                    // Email Error
                    if (dragsError != '') {
                        toastr.error(dragsError);
                        $('#dragsAllergy').addClass('is-invalid');
                    }

                 }
            }
        });

       

       
         
        
}

function assign_doc(assigndoc,askdId)
       {
        $.ajaxSetup({   
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: 'Askdoctor-change',
            data: { assigndoc:assigndoc, askdId:askdId },
            success: function(response)
            {   
                console.log(response); return false;
                
           }
       });

       }
       function statusData(val)
       {                                   
           if(val == 1)
           {
               var currdate = new Date();
               var dd = currdate.getDate();
               var mm = currdate.getMonth() + 1;
               var y = currdate.getFullYear();
               var fromdate = mm + '/' + dd + '/' + y;
                 
               var newdate = new Date(fromdate);
               newdate.setDate(newdate.getDate() + 7);
               var dd1 = newdate.getDate();
               var mm1 = newdate.getMonth() + 1;
               var y1 = newdate.getFullYear();
               var fromdate11 = mm1+ '/' + dd1 + '/' + y1;                                  
          
               // $('#datefrom').datepicker({
               //     format: "mm/dd/yyyy",
               //     todayHighlight: true,
               //     startDate: fromdate,
               //     endDate: fromdate11,
               //     autoclose: true,
               //     minDate:fromdate
                   
               // });                  
        
         
              console.log(fromdate);
              console.log(fromdate11);
              $('#datefrom').val(fromdate);
              $('#dateto').val(fromdate11);
   
               // $('#datefrom').datepicker('setDate', fromdate);                                             
               // $('#dateto').datepicker('setDate', fromdate11);
           }                                            
           else if(val == 2)
           {
            var currdate = new Date();
            var dd = currdate.getDate();
            var mm = currdate.getMonth() + 1;
            var y = currdate.getFullYear();
            var fromdate = mm + '/' + dd + '/' + y;
            currdate.setMonth(currdate.getMonth() + 1);
        
            var dd1 = currdate.getDate();
            var mm1 = currdate.getMonth() + 1;
            var y1 = currdate.getFullYear();
            var fromdate11 = mm1+ '/' + dd1 + '/' + y1;
                
            
   
              $('#datefrom').val(fromdate);
              $('#dateto').val(fromdate11);
                                
               
           }
           else if(val == 3)
           {
               var currdate = new Date();
               var dd = currdate.getDate();
               var mm = currdate.getMonth() + 1;
               var y = currdate.getFullYear();
               var fromdate = mm + '/' + dd + '/' + y;
                 
               var newdate = new Date(fromdate);
               newdate.setDate(newdate.getDate());
               var dd1 = newdate.getDate();
               var mm1 = newdate.getMonth() + 1;
               var y1 = newdate.getFullYear() + 1;
               var fromdate11 = mm1+ '/' + dd1 + '/' + y1;
                          
               // $('#datefrom').datepicker({
               //     format: "mm/dd/yyyy",
               //     todayHighlight: true,
               //     startDate: fromdate,
               //     endDate: fromdate11,
               //     autoclose: true,
               // });
              
               console.log(fromdate);
              console.log(fromdate11);
              
              $('#datefrom').val(fromdate);
              $('#dateto').val(fromdate11);
   
               // $('#datefrom').datepicker('minDate', fromdate);                                         
               // $('#datefrom').datepicker('setDate', fromdate);
               // $('#dateto').datepicker('setDate', fromdate11);
   
           }
       }

    
       function removeTime(id){
        jQuery("#time"+id).remove();
    } 
    function timePickerRefresh(){
        $('input.timepicker').timepicker({
            timeFormat: 'h:i A',
            step: 1,
            maxTime: '24:00', 
            startTime: '00:00',
            dynamic: true,
            dropdown: true,
            scrollbar: true
        })
    }
    function removeTimeUpdate(id){
        jQuery.ajax({
            url: "delete-time",  
            type: 'post',  
            data: {'id':id },  
                success: function (data) {
                jQuery("#times"+id).remove();
            }
        });
    }
   
