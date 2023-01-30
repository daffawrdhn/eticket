$(document).ready(function () {
    $("#alert").hide()
    $("#isLoading").hide()
    tableReport();

    setInterval(()=>{
        table.draw()
    },300000)

    $('#select-regional').on('select2:select', function (e) {
        var data = e.params.data.id;
        var url = 'api/get-report-summary/'+data
        $('#summaryTable').DataTable().destroy()
        tableReport(url)

    });

    $(document).on('click','#select-all', function (e) {
        var url = 'api/get-report-summary/0'
        $('#summaryTable').DataTable().destroy()
        tableReport(url)

    });


    $(document).on('click','#btnExport', function (e) {

        fnExcelReport()

        // $("#summaryTable").table2excel({
        //     exclude:".noExl",
        //     filename: "ticketSummary.xls"
        // });

    });

        // search

    $( "#end_date" ).focusin(function() {
        var startDate = $("#start-date").val();

        if (startDate != null) {

            $(this).attr('min', startDate)

        }
        
    })

    $( "#end_date" ).focusout(function(endDate, startDate) {
        var startDate = $("#start-date").val();
        var endDate = $(this).val();
        if (endDate < startDate) {
            $('#end_date').addClass('is-invalid');
            $('#end_dateFeedback').html('Please Enter the quit date > date now')
        }else{
            $('#end_date').removeClass('is-invalid');
        }
    })
    
    $(document).on('click', '#searchReport', (e) => {
        $("#alert").hide();
        e.preventDefault();
        var regional = $("#regional-select").val();
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        var url = 'api/get-report-summary/0'

        var data = {
            'regional_id' : regional,
            'start_date' : startDate,
            'end_date' : endDate
        }

        if (regional == null || endDate == '' || startDate == '') {
            $("#isLoading").show();
            setTimeout(() => {
                $("#isLoading").hide();
                $("#alert").show()
            },1000)
        }else{
            $("#isLoading").show();
            setTimeout(() => {
                $("#isLoading").hide();
                $("#search").modal('hide')
            },1000)
            
            $('#summaryTable').DataTable().destroy()
            tableReport(url, data);
        }
        
    })

    function tableReport(url, data = null) { 

        if (url == null) {
            url = 'api/get-report-summary/0'
            
        }

        if ( data == null) {
            data = {
                'regional_id' : 0   
            }
        }


        var token = $('#token').val()
        var table = $('#summaryTable').DataTable({
            responsive: false,
            autoWidth : false,
            processing: true,
            serverSide: true,
            ajax: { 
                url: APP_URL + url,
                type: "POST",
                data: data,
                dataType: 'json',
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                },
            },
            columns: [
                {data: 'regional_name', name: 'regional_name'},
                {data: 'open', name: 'open'},
                {data: 'approve', name: 'approve'},
                {data: 'reject', name: 'supervisor_name'},
                {data: 'total_ticket', name: 'regional'},
            ] ,
        })

        return table;
     }

    //getdata
    
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

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var token = $('#token').val()
    var selectRegional =   $('#regional-select').select2({
                            placeholder : "Select Regional",
                            dropdownParent: $("#search"),
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
        tab = document.getElementById('summaryTable'); // id of table
        console.log(tab);
        for(j = 0 ; j < tab.rows.length ; j++) 
        {     
            tab_text=tab_text+tab.rows[j].innerHTML+"hallo</tr>";
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