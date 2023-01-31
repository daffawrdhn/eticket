$(document).ready(function () {
    var token = $('#token').val()

    setInterval(()=>{
        
    },1000)

    // total ticket
    
    $.ajax({
        type : "GET",
        url : APP_URL + "api/get-sumary-dashboard",
        dataType : "json",
        beforeSend: function(xhr, settings) { 
            xhr.setRequestHeader('Authorization','Bearer ' + token ); 
        },
        success: function (response) {
            $("#ticketTotal").html(response.data.total_ticket)
            $("#ticketProccess").html(response.data.open)
            $("#ticketApprove").html(response.data.approve)
            $("#ticketReject").html(response.data.reject)
        },
    });
    

    
    var dt = new Date();
    function barChart(response){
        var ctx = document.getElementById("myBarChart");
            var myLineChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: response.data.regional,
                datasets: [{
                label: "Ticket",
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "rgba(2,117,216,1)",
                data: response.data.total_ticket,
                }],
            },
            options: {
                scales: {
                xAxes: [{
                    time: {
                    unit: response.data.regional
                    },
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    maxTicksLimit: response.data.xMax
                    }
                }],
                yAxes: [{
                    ticks: {
                    min: 0,
                    max: response.data.yMax,
                    maxTicksLimit: 5
                    },
                    gridLines: {
                    display: true
                    }
                }],
                },
                legend: {
                display: false
                }
            }
            });
    }
    
    var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
    $('#timeBar').html('updated at '+time)
    $('#timePie').html('updated at '+time)
    $.ajax({
        type : "GET",
        url : APP_URL + "api/get-pie-chart",
        dataType : "json",
        beforeSend: function(xhr, settings) { 
            xhr.setRequestHeader('Authorization','Bearer ' + token ); 
        },
        success: function (response) {
            var ctx = document.getElementById("myPieChart");
            var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: response.data.labels,
                datasets: [{
                data: response.data.data,
                backgroundColor: ['blue', 'grey', 'yellow', 'orange', 'red', 'green'],
                }],
            },
            });
        },
        error:function(response){
            if (!response.success) {
                console.log(response.responseJSON.data.error);
            }
        }
    });

    // barchart
    

   $.ajax({
        type : "GET",
        url : APP_URL + "api/get-bar-chart",
        dataType : "json",
        beforeSend: function(xhr, settings) { 
            xhr.setRequestHeader('Authorization','Bearer ' + token ); 
        },
        success: function (response) {
            barChart(response)
        }
    });


});