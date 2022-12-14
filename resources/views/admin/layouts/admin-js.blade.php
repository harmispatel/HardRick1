<!-- Vendor JS Files -->
<script src="{{ asset('public/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('public/assets/vendor/chart.js/chart.min.js') }}"></script>
<script src="{{ asset('public/assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('public/assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('public/assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('public/assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('public/assets/vendor/js/main.js') }}"></script>

{{-- Jquery --}}
<script src="{{ asset('public/assets/admin/js/jquery/jquery.min.js') }}"></script>

{{-- common js --}}
<script src="{{ asset('public/assets/admin/js/jquery/common.js') }}"></script>

{{-- Sweet Alert --}}
<script src="{{ asset('public/assets/vendor/js/sweet-alert.js') }}"></script>

{{-- Toastr --}}
<script src="{{ asset('public/assets/admin/js/toastr/toastr.min.js') }}"></script>

{{-- ckeditor --}}
<script src="//cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>


{{-- Data Table --}}
<script src="{{ asset('public/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

{{-- Jquery UI --}}
<script src="{{ asset('public/assets/vendor/js/jquery-ui.js') }}"></script>


{{-- select 2 --}}
<script src="{{ asset('public/assets/admin/js/select2/select2.min.js') }}"></script>

{{-- timepicker --}}
<script src="{!! asset('/public/plugins/timepicker/jquery.timepicker.js') !!}"></script>

{{-- autocomplete address --}}

<script type="text/javascript" src="http://maps.google.com/maps/api/js?key={{get_site_setting('api_key')}}&sensor=false&libraries=places&language=en-AU"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        width:370
    });
    
});
function select2Refresh(){
  $('.select2').select2();

}
$('#master').on('click', function(e) {
    if ($(this).is(':checked', true)) {
        $(".sub_chk").prop('checked', true);
    } else {
        $(".sub_chk").prop('checked', false);
    }
});

$('input.timepicker').timepicker({
        timeFormat: 'h:i A',
        step: 1,
        maxTime: '24:00', 
        startTime: '00:00',
        dynamic: true,
        dropdown: true,
        scrollbar: true
    }); 
</script>

<script type="text/javascript">
    // autocomplete adderess using google map api

function initialize() {
    var address = (document.getElementById('address'));
    var autocomplete = new google.maps.places.Autocomplete(address);
    autocomplete.setTypes(['geocode']);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            return;
        }

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
        }
        jQuery("#lat").val(place.geometry.location.lat());
        jQuery("#long").val(place.geometry.location.lng());
    });
}

$( document ).ready(function() {  
    google.maps.event.addDomListener(window, 'load', initialize);
});     

</script>