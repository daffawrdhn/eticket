class Table{
    constructor(data){
        this.column = data.column
        this.url = data.url
        this.tableId = data.tableId

    }
    
    
    dataTable(token, data){
        return $(this.tableId).DataTable({
            responsive: true,
            autoWidth : false,
            processing: true,
            serverSide: true,
            ajax: { 
                url: this.url,
                type: "POST",
                dataType: 'json',
                data:data,
                beforeSend: function(xhr, settings) { 
                    xhr.setRequestHeader('Authorization','Bearer ' + token ); 
                },
            },
            columns: this.column  
        });
    }
}