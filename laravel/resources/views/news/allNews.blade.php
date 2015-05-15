@extends('app')
@extends('header.header')
@extends('nav.searchNav')
@extends('nav.privateNav')
@extends('nav.publicNav')


@section('content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Alle Änderungen und Ereignisse</h3>        
    </div><!-- /.box-header -->
    <div class="box-body pad">
        <table class="table table-striped">
            <thead>
                <tr>                    
                    <th>ID</th>
                    <th>Zeitpunkt</th>
                    <th>Gruppe</th>
                    <th>Benuter</th>
                    <th>Rechte des Benuter</th>
                    <th>Art des Ereignisses </th>
                    <th>Zusatz Info</th>                  
                </tr>
            </thead>
            <tbody>
                @foreach ($allNews as $news)
                <tr>
                    <td>{{$news->id}}</td>                   
                </tr>
                @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div>
<div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap"><div class="row"><div class="col-sm-6"><div class="dataTables_length" id="example1_length"><label>Show <select name="example1_length" aria-controls="example1" class="form-control input-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div></div><div class="col-sm-6"><div id="example1_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control input-sm" placeholder="" aria-controls="example1"></label></div></div></div><div class="row"><div class="col-sm-12"><table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                    <thead>
                      <tr role="row"><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending" style="width: 76px;">Rendering engine</th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 100px;">Browser</th><th class="sorting_desc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 85px;" aria-sort="descending">Platform(s)</th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 64px;">Engine version</th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 42px;">CSS grade</th></tr>
                    </thead>
                    <tbody>
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                      
                    <tr role="row" class="odd">
                        <td class="">Misc</td>
                        <td>IE Mobile</td>
                        <td class="sorting_1">Windows Mobile 6</td>
                        <td>-</td>
                        <td>C</td>
                      </tr><tr role="row" class="even">
                        <td class="">Trident</td>
                        <td>Internet Explorer 7</td>
                        <td class="sorting_1">Win XP SP2+</td>
                        <td>7</td>
                        <td>A</td>
                      </tr><tr role="row" class="odd">
                        <td class="">Trident</td>
                        <td>AOL browser (AOL desktop)</td>
                        <td class="sorting_1">Win XP</td>
                        <td>6</td>
                        <td>A</td>
                      </tr><tr role="row" class="even">
                        <td class="">Gecko</td>
                        <td>Netscape Browser 8</td>
                        <td class="sorting_1">Win 98SE+</td>
                        <td>1.7</td>
                        <td>A</td>
                      </tr><tr role="row" class="odd">
                        <td class="">Gecko</td>
                        <td>Firefox 1.0</td>
                        <td class="sorting_1">Win 98+ / OSX.2+</td>
                        <td>1.7</td>
                        <td>A</td>
                      </tr><tr role="row" class="even">
                        <td class="">Gecko</td>
                        <td>Firefox 1.5</td>
                        <td class="sorting_1">Win 98+ / OSX.2+</td>
                        <td>1.8</td>
                        <td>A</td>
                      </tr><tr role="row" class="odd">
                        <td class="">Gecko</td>
                        <td>Firefox 2.0</td>
                        <td class="sorting_1">Win 98+ / OSX.2+</td>
                        <td>1.8</td>
                        <td>A</td>
                      </tr><tr role="row" class="even">
                        <td class="">Gecko</td>
                        <td>Netscape Navigator 9</td>
                        <td class="sorting_1">Win 98+ / OSX.2+</td>
                        <td>1.8</td>
                        <td>A</td>
                      </tr><tr role="row" class="odd">
                        <td class="">Gecko</td>
                        <td>Seamonkey 1.1</td>
                        <td class="sorting_1">Win 98+ / OSX.2+</td>
                        <td>1.8</td>
                        <td>A</td>
                      </tr><tr role="row" class="even">
                        <td class="">Gecko</td>
                        <td>Mozilla 1.7</td>
                        <td class="sorting_1">Win 98+ / OSX.1+</td>
                        <td>1.7</td>
                        <td>A</td>
                      </tr></tbody>
                    <tfoot>
                      <tr><th rowspan="1" colspan="1">Rendering engine</th><th rowspan="1" colspan="1">Browser</th><th rowspan="1" colspan="1">Platform(s)</th><th rowspan="1" colspan="1">Engine version</th><th rowspan="1" colspan="1">CSS grade</th></tr>
                    </tfoot>
                  </table></div></div><div class="row"><div class="col-sm-5"><div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div></div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="example1_paginate"><ul class="pagination"><li class="paginate_button previous disabled" id="example1_previous"><a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0">Previous</a></li><li class="paginate_button active"><a href="#" aria-controls="example1" data-dt-idx="1" tabindex="0">1</a></li><li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="2" tabindex="0">2</a></li><li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="3" tabindex="0">3</a></li><li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="4" tabindex="0">4</a></li><li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="5" tabindex="0">5</a></li><li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="6" tabindex="0">6</a></li><li class="paginate_button next" id="example1_next"><a href="#" aria-controls="example1" data-dt-idx="7" tabindex="0">Next</a></li></ul></div></div></div></div>
                </div><!-- /.box-body -->
              </div>

<script src="../../plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript">
      $(function () {      
        $('#example2').dataTable({
          "bPaginate": true,
          "bLengthChange": false,
          "bFilter": false,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false
        });
      });
    </script>
@endsection