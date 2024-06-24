let countriesManager = {

  dataTable: null,
  pieChart: null,
  modalCountriesDatatable: null,

  init:function(){
    countriesManager.loadData();
  },

  loadData: function(){
    $('#col_countries-population-table').block()
    $.ajax({
      type: 'GET',
      url: routeGetCountriesPopulationData,
      success: function (result, status, xhr) {
        if(result.success){
          let tableBody = '';
          for (let i = 0, l = result.data.length; i < l; i++) {
            tableBody += '<tr>' +
                         '<td>' + securityManager.preventXSSAttacks(result.data[i].region_name) + '</td>' +
                         '<td>' + securityManager.preventXSSAttacks(result.data[i].count_value) + '</td>' +
                         '<td>' + securityManager.preventXSSAttacks(result.data[i].avg_population) + '</td>' +
                         '<td>' + securityManager.preventXSSAttacks(result.data[i].population_level_name) + '</td>' +
                         '</tr>';
          }
          $('#countries_population-table_body').append(tableBody);
          countriesManager.dataTable = $('#countries_population-table').DataTable({
            order: [2, 'desc'],
          });
          countriesManager.initHighcharts(result.data);
        }else{
          toastr.error(errorThrown, 'Operation failed!');
        }
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(errorThrown, 'Operation failed!');
      },
      complete:function(){
        $('#col_countries-population-table').unblock();
      },
    });
  },

  getRegionsDetail: function(row){
    let region = row[0];
    $('#countries_population_levels-row').block();
    $.ajax({
      type: 'GET',
      url: routeGetRegionDetails.replace('regionName', region),
      success: function (result, status, xhr) {
        if(result.success){
          let tableBody = '';
          let fields_to_show = result.data.map(function (elem) {
            return elem.fields_to_show
          })
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

          $('#countries-table_body').html('').append(tableBody);
          if(!countriesManager.modalCountriesDatatable){
            countriesManager.modalCountriesDatatable = $('#countries-table').DataTable({
              columnDefs: [
                {
                  targets: [3, 4],
                  orderable: false
                },
                {
                  targets: [1],
                  visible: false
                }
              ]
            })
          }else{
            countriesManager.modalCountriesDatatable.draw();
          }
          $('#regionsModalLabel').html(region);
        }else{
          toastr.error(result.message, 'Operation failed!');
        }
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(errorThrown, 'Operation failed!');
      },
      complete:function(){
        $('#countries_population_levels-row').unblock()
        $('#regionsModal').modal('show')
      },
    });
  },

  initHighcharts: function(data){
    let percentages = countriesManager.calculatePercentages(data);
    Highcharts.chart('col_countries-population-highcharts', {
        chart: {
            type: 'pie'
        },
        credits: false,
        title: {
            text: 'Countries average population by region',
            align: 'center'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            name: 'Countries average population by region',
            colorByPoint: true,
            data: percentages
        }]
    });
  },

  calculatePercentages: function(data){
    let total = Object.values(data).reduce((acc,val) =>{
      acc += val.avg_population
      return acc;
    },0);

    let percentages = Object.keys(data).reduce((acc,key) => {
      let val = data[key].avg_population;
      acc[key] = {
        'y' : Number((val/total*100).toFixed(2)),
        'name' : data[key].region_name
      }
      return acc
    },{})
    return Object.values(percentages);
  }

}
