$(document).ready(function () {


    $(document).on('click','#btnExport', function (e) {

        fnExcelReport()

        // $("#summaryTable").table2excel({
        //     exclude:".noExl",
        //     filename: "ticketSummary.xls"
        // });

    });


    //getdata
    var token = $('#token').val()
    var table = $('#summaryTable').DataTable({
        responsive: false,
        autoWidth : false,
        processing: true,
        serverSide: true,
        ajax: { 
            url: APP_URL + "api/get-report-summary",
            type: "GET",
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
    

    function fnExcelReport()
    {
        var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
        var textRange; 
        var j=0;
        tab = document.getElementById('summaryTable'); // id of table
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