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
        $('#reportSlaTable').DataTable().destroy()
        tableReport()
    },300000)

    
    $(document).on('click','#select-all', function (e) {
        $('#btnExport').attr('regional-id', "0");
        $('#btnExport').attr('start-date', "");
        $('#btnExport').attr('end-date', "");


        $('#reportSlaTable').DataTable().destroy()
        tableReport()
        
        $("#regional-select").val(null).trigger("change"); 
        $("#start-date").val('');
        $("#end-date").val('');

    });


    $('#regional-select').on("select2:select", function(e) { 
        e.preventDefault();
        var value = e.params.data.id;
        console.log(value);
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

    function tableReport(data = null) { 
        regionalId = data == null ? 0 : data

        datas = {
            'regionalId' : regionalId
        }
        //getdata
        var token = $('#token').val()
        var table = $('#reportSlaTable').DataTable({
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
                url: APP_URL + "api/get-report-sla",
                type: "POST",
                data : datas,
                dataType: 'json',
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                },
            },
            columns: [
                {data: 'ticket_id', name: 'ticket_id'},
                {data: 'employee_id', name: 'employee_id'},
                {data: 'regional_name', name: 'regional_name'},
                {data: 'submited_date', name: 'submited_date'},
                {data: 'approve1_date', name: 'approve1_date'},
                {data: 'approve2_date', name: 'approve2_date'},
                {data: 'approve3_date', name: 'approve3_date'},
                {data: 'final_approve_date', name: 'final_approve_date'},
                {data: 'reject_date', name: 'riject_date'},
                {data: 'in_progress', name: 'in_progress'},
                {data: 'is_done', name: 'is_done'},
                {data: 'sla_total', name: 'sla_total'},
                
            ] ,
        })

        return table;
     }


    function select(){

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var token = $('#token').val()
        var selectRegional =   $('#regional-select').select2({
                                placeholder : "Select Regional",
                                dropdownParent: false,
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