$(document).ready(function () {
    var token = $('#token').val()
    $("#alert").hide()
    $("#isLoading").hide()
    tableReport();
    select();


    setInterval(()=>{
        $("#regional-select").val(null).trigger("change"); 
        $("#start-date").val('');
        $("#end-date").val('');
        $('#regionalTable').DataTable().destroy()
        tableReport()
    },300000)

    
    $(document).on('click','#select-all', function (e) {
        $('#btnExport').attr('regional-id', "0");
        $('#btnExport').attr('start-date', "");
        $('#btnExport').attr('end-date', "");


        $('#regionalTable').DataTable().destroy()
        tableReport()
        
        $("#regional-select").val(null).trigger("change"); 
        $("#start-date").val('');
        $("#end-date").val('');

    });

    $(document).on('click','#closeSearch', function (e) {
        $("#alert").hide()
        $("#regional-select").val(null).trigger("change"); 
        $("#start-date").val('');
        $("#end-date").val('');
    });
    
    

    $(document).on('click','#btnExport', function (e) {

        var regionalId = $(this).attr('regional-id');
        var startDate = $(this).attr('start-date');
        var endDate = $(this).attr('end-date');
        var data


        data = {
            'regionalId' : regionalId,
            'startDate' : startDate,
            'endDate' : endDate
        }


        $.ajax({
            xhrFields: {
                responseType: 'blob',
            },
            type: 'POST',
            url: APP_URL + 'api/export-report-regional',
            data: data,
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + token ); 
            },
            success: function(result, status, xhr) {
        
                var disposition = xhr.getResponseHeader('content-disposition');
                var matches = /"([^"]*)"/.exec(disposition);
                var filename = (matches != null && matches[1] ? matches[1] : 'salary.xlsx');
        
                // The actual download
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
        
                document.body.appendChild(link);
        
                link.click();
                document.body.removeChild(link);
            }
        });
        

    });



    // search

    $( "#end-date" ).focusin(function() {
        var startDate = $("#start-date").val();

        if (startDate != null) {

            $(this).attr('min', startDate)

        }
        
    })

    $( "#end-date" ).focusout(function(endDate, startDate) {
        var startDate = $("#start-date").val();
        var endDate = $(this).val();
        if (endDate < startDate) {
            $('#end-date').addClass('is-invalid');
            $('#end-dateFeedback').html('Please Enter the quit date > date now')
        }else{
            $('#end-date').removeClass('is-invalid');
        }
    })


    $(document).on('click', '#searchReport', (e) => {
        $("#alert").hide();
        e.preventDefault();
        var regional = $("#regional-select").val();
        var startDate = $("#start-date").val();
        var endDate = $("#end-date").val();

        var data = {
            'regional_id' : regional,
            'start_date' : startDate,
            'end_date' : endDate
        }


        if (regional == null && endDate == '' && startDate == '') {
            $("#isLoading").show();
            setTimeout(() => {
                $("#isLoading").hide();
                $("#alert").show()
            },1000)
        }else if(startDate != '' && endDate == ""){
            $('#end-date').addClass('is-invalid');
            $('#end-dateFeedback').html('please fill Add End Date')
        }else if(startDate == '' && endDate != ""){
            $('#start-date').addClass('is-invalid');
            $('#start-dateFeedback').html('please fill Add Start Date')
        }else if(startDate > endDate){
            $('#end-date').addClass('is-invalid');
            $('#end-dateFeedback').html('Please Enter the end date > start date')
        }else{
            $("#isLoading").show();
            setTimeout(() => {
                $("#isLoading").hide();
                $("#search").modal('hide')
            },1000)

            if (regional == null) {
                var regionalId = 0
            }else{
                regionalId = regional
            }

            $('#btnExport').attr('regional-id', regionalId);
            $('#btnExport').attr('start-date', startDate);
            $('#btnExport').attr('end-date', endDate);
            
            $('#regionalTable').DataTable().destroy()
            tableReport(data);
        }
    })

    function tableReport(data = null) { 

        var regionalId
        var startDate
        var endDate 


    if (data == null) {
        regionalId = 0;
        startDate = "";
        endDate = "";
    }else if(data.regional_id == null){
        regionalId = 0
        startDate = data.start_date
        endDate = data.end_date
    }else{
        regionalId = data.regional_id
        startDate = data.start_date
        endDate = data.end_date
    }

    var datas = {
        "regionalId" : regionalId,
        "startDate" : startDate,
        "endDate" : endDate
    }

        //getdata
        var token = $('#token').val()
        var table = $('#regionalTable').DataTable({
            responsive: false,
            autoWidth : false,
            processing: true,
            serverSide: true,
            format: {
                text: {
                    dataType: "text"
                }
            },
            ajax: { 
                url: APP_URL + "api/get-report-regional",
                type: "POST",
                data : datas,
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

        return table;
     }


    function select(){

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
                            
        
        return selectRegional
                    
    }



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
        // tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        // tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        // tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
    
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