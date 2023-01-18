$(document).ready(function () {

    $('#select-regional').on('select2:select', function (e) {
        var data = e.params.data.id;

        table.ajax.url( 'api/get-report-regional/'+data ).load();

    });


    //getdata
    var token = $('#token').val()
    var table = $('#regionalTable').DataTable({
        responsive: false,
        autoWidth : false,
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Red',
                className: 'red'
            },
            {
                text: 'Orange',
                className: 'orange'
            },
            {
                text: 'Green',
                className: 'green'
            }
        ],
        ajax: { 
            url: APP_URL + "api/get-report-regional/0",
            type: "GET",
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
        },
        columns: [
            {data: 'no', name: 'no'},
            {data: 'employee_id', name: 'employee_id'},
            {data: 'employee_name', name: 'employee_name'},
            {data: 'supervisor_id', name: 'supervisor_id'},
            {data: 'supervisor_name', name: 'supervisor_name'},
            {data: 'regional', name: 'regional'},
            {data: 'jenis_ticket', name: 'jenis_ticket'},
            {data: 'sub_feature', name: 'sub_feature'},
            {data: 'ticket_title', name: 'ticket_title'},
            {data: 'ticket_description', name: 'ticket_description'},
            {data: 'ticket_status', name: 'ticket_status'},
            
        ] ,
    })
    

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var token = $('#token').val()
    var selectRegional =   $('#regional_id').select2({
                            placeholder : "Select Regional",
                            ajax: { 
                                url: APP_URL + "api/select-regional",
                                type: "post",
                                dataType: 'json',
                                delay: 250,
                                beforeSend: function(xhr, settings) { 
                                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                                },
                                data: function (params) {
                                return {
                                    _token: CSRF_TOKEN,
                                    search: params.term // search term
                                };
                                },
                                processResults: function (response) {
                                return {
                                    results: $.map(response.data, function (item) {
                                        
                                        return{
                                            text : item.regional_name,
                                            id: item.regional_id
                                        }
                                    })
                                };
                                },
                                cache: true
                            }
                        })

                    selectRegional.data('select2').$selection.css('height', '45px')
                    selectRegional.data('select2').$selection.css('padding-top', '5px')
});