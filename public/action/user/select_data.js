$(document).ready(function () {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var token = $('#token').val()

    // select user
  var selectUser =   $('#supervisor_id').select2({
                    placeholder : "Select Supervisor",
                    dropdownParent: $("#modalAddUser"),
                    ajax: { 
                        url: APP_URL + "api/select-user",
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
    
  selectUser.data('select2').$selection.css('height', '45px')
  selectUser.data('select2').$selection.css('padding-top', '5px')



  // select Role
  var selectRole =   $('#role_id').select2({
                  placeholder : "Select Role",
                  dropdownParent: $("#modalAddUser"),
                  ajax: { 
                      url: APP_URL + "api/select-role",
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
                                  text : item.role_name,
                                  id: item.role_id
                              }
                          })
                        };
                      },
                      cache: true
                  }
              })

              selectRole.data('select2').$selection.css('height', '45px')
              selectRole.data('select2').$selection.css('padding-top', '5px')
  

  var selectOrganization =   $('#organization_id').select2({
                placeholder : "Select organization",
                dropdownParent: $("#modalAddUser"),
                ajax: { 
                    url: APP_URL + "api/select-organization",
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
                                text : item.organization_name,
                                id: item.organization_id
                            }
                        })
                      };
                    },
                    cache: true
                }
            })

            selectOrganization.data('select2').$selection.css('height', '45px')
            selectOrganization.data('select2').$selection.css('padding-top', '5px')



var selectRegional =   $('#regional_id').select2({
              placeholder : "Select regional",
              dropdownParent: $("#modalAddUser"),
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