let countriesManager = {

  dataTable: null,

  init:function(){
    countriesManager.loadData();
  },

  loadData: function(){
    $('#col_countries-table').block()
    $.ajax({
      type: 'GET',
      url: routeGetIndependentCountriesList,
      success: function (result, status, xhr) {
        if(result.success){
          let fields_to_show = result.data.map(function (elem) {
            return elem.fields_to_show
          })
          let tableBody = '';
          for (let i = 0, l = fields_to_show.length; i < l; i++) {
            tableBody += '<tr>' +
                           '<td>' + securityManager.preventXSSAttacks(fields_to_show[i].name) + '</td>' +
                           '<td>' + securityManager.preventXSSAttacks(fields_to_show[i].region) + '</td>' +
                           '<td>' + securityManager.preventXSSAttacks(fields_to_show[i].subregion) + '</td>' +
                           '<td><img src="' + securityManager.preventXSSAttacks(fields_to_show[i].flag) + '" width="40px"></td>' +
                           '<td>' + securityManager.preventXSSAttacks(fields_to_show[i].languages) + '</td>' +
                           '<td>' + securityManager.preventXSSAttacks(fields_to_show[i].population) + '</td>' +
                         '</tr>';

          }
          $('#countries-table_body').append(tableBody);
          countriesManager.dataTable = $('#countries-table').DataTable({
            columnDefs: [ {
              targets: [3, 4],
              orderable: false
            }]
          });
        }else{
          toastr.error(result.message, 'Operation failed!');
        }
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(errorThrown, 'Operation failed!');
      },
      complete:function(){
        $('#col_countries-table').unblock()
      },
    });
  },

}
