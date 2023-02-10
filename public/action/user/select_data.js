$(document).ready(function () {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var token = $('#token').val()


    selectRegional('#regional_id', token, CSRF_TOKEN, $("#modalAddUser"))
    selectRegional('#regional-select', token, CSRF_TOKEN, false)

    select('#supervisor_id', 'select-user', $("#modalAddUser"), 'select supervisor')
    select('#role_id', 'select-role', $("#modalAddUser"), 'select role')
    select('#organization_id', 'select-organization', $("#modalAddUser"), 'select organization')


    function select(elementId, url, dropDown, placeholder) { 
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var token = $('#token').val()
      var select =   $(elementId).select2({
              placeholder : placeholder,
              dropdownParent: dropDown,
              ajax: { 
                  url: APP_URL + 'api/'+ url,
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
                              text : item.text,
                              id: item.id
                          }
                      })
                    };
                  },
                  cache: true
              }
          })

      select.data('select2').$selection.css('height', '45px')
      select.data('select2').$selection.css('padding-top', '5px')

      return select
    }

});


function selectRegional(selectId, token, CSRF_TOKEN, dropDown){
  var selectRegional =   $(selectId).select2({
      placeholder : "Select regional",
      dropdownParent: dropDown,
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


  return selectRegional
}