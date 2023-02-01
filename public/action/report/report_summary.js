$(document).ready(function () {
    $("#alert").hide()
    $("#isLoading").hide()
    tableReport();

    setInterval(()=>{
        $('#select-regionalId option:selected').remove();
        $("#start-date").val('');
        $("#end-date").val('');
        $('#summaryTable').DataTable().destroy()
        tableReport()
    },300000)

    $(document).on('click','#select-all', function (e) {
        $('#select-regionalId option:selected').remove();
        $("#start-date").val('');
        $("#end-date").val('');
        $('#summaryTable').DataTable().destroy()
        tableReport()

    });
        // search

    $( "#start-date" ).focusin(function() {
        var endDate = $("#end-date").val();

        if (endDate != null) {

            $(this).attr('max', endDate)

        }
        
    })

    $( "#start-date" ).focusout(function(startDate, endDate) {
        var endDate = $("#end-date").val();
        var startDate = $(this).val();
        if (startDate > endDate) {
            $('#start-date').addClass('is-invalid');
            $('#start-dateFeedback').html('Please Enter the start date < end date')
        }else{
            $('#start-date').removeClass('is-invalid');
        }
    })
    

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
            $('#end-dateFeedback').html('Please Enter the end date > start date')
        }else{
            $('#start-date').removeClass('is-invalid');
            $('#end-date').removeClass('is-invalid');
        }
    })
    
    $(document).on('click', '#searchReport', (e) => {
        $("#alert").hide();
        e.preventDefault();
        var regional = $("#regional-select").val();
        var startDate = $("#start-date").val();
        var endDate = $("#end-date").val();
        var url = 'api/get-report-summary/0'

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
            $('#end-dateFeedback').html('please fill out this field')
        }else if(startDate == '' && endDate != ""){
            $('#start-date').addClass('is-invalid');
            $('#start-dateFeedback').html('please fill out this field')
        }else if(startDate > endDate){
            $('#end-date').addClass('is-invalid');
            $('#end-dateFeedback').html('Please Enter the end date > start date')
        }else{
            $("#isLoading").show();
            setTimeout(() => {
                $("#isLoading").hide();
                $("#search").modal('hide')
            },1000)
            
            $('#summaryTable').DataTable().destroy()
            tableReport(data);
        }
        
    })

    $(document).on('click','#closeSearch', function (e) {
        $('#select-regionalId option:selected').remove();
        $('#start-date').removeClass('is-invalid');
        $('#end-date').removeClass('is-invalid');
        $("#start-date").val('');
        $("#end-date").val('');
        $("#alert").hide()
    });

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

        var token = $('#token').val()
        var table = $('#summaryTable').DataTable({
            responsive: false,
            autoWidth : false,
            processing: true,
            serverSide: true,
            ajax: { 
                url: APP_URL + "api/get-report-summary",
                type: "POST",
                data: datas,
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

    //export data
    $(document).on('click','#btnExport', function (e) {

        var selectRegional = $("#regional_id").val();
        var regionalId = $("#regional-select").val();
        var startDate = $("#start-date").val();
        var endDate = $("#end-date").val();
        var data

        if (selectRegional == null && regionalId == null) {
            var isRegionalId = 0;
        }else if(selectRegional != null && regionalId == null){
            var isRegionalId = selectRegional;
        }else if (selectRegional == null && regionalId != null) {
            var isRegionalId = regionalId;
        }

        data = {
            'regionalId' : isRegionalId,
            'startDate' : startDate,
            'endDate' : endDate
        }

        $.ajax({
            xhrFields: {
                responseType: 'blob',
            },
            type: 'POST',
            url: APP_URL + 'api/export-report-summary',
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