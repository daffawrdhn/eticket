$(document).ready(function () {

    $('#select-regional').on('select2:select', function (e) {
        var data = e.params.data.id;

        table.ajax.url( 'api/get-report-regional/'+data ).load();

    });

    $(document).on('click','#select-all', function (e) {

        table.ajax.url( 'api/get-report-regional/0').load();

    });

    $(document).on('click','#btnExport', function (e) {

        // fnExcelReport()

        $("#regionalTable").table2excel({
            filename: "Table.xls"
        });

    });


    //getdata
    var token = $('#token').val()
    var table = $('#regionalTable').DataTable({
        responsive: false,
        autoWidth : false,
        processing: true,
        serverSide: true,
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
            {data: 'date', name: 'date'},
            
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

                    selectRegional.data('select2').$selection.css('height', '40px')
                    selectRegional.data('select2').$selection.css('padding-top', '5px')


    function fnExcelReport()
    {
        var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
        var textRange; 
        var j=0;
        tab = document.getElementById('regionalTable'); // id of table
        console.log(tab);
        for(j = 0 ; j < tab.rows.length ; j++) 
        {     
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
        }
    
        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
    
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE "); 
    
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus(); 
            sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
        }  
        else                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  
    
        return (sa);
    }
});