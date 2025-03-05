@extends('layout.master')

@section('page')
 {{__("site.addons")}}
@endsection

@section('link')
{{route('dashboard.addons.index')}}
@endsection

@section('content')

     <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header card-header-style">
                <h3 class="card-title" style="float: left">{{__("site.show_addons")}}</h3>
                <a href="{{ route("dashboard.addons.create") }}"><button style="float:right" class="btn btn-primary" title="{{ __("site.create_addons") }}"><i class="fa fa-plus"></i></button></a>
              <div class="clearfix"></div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form id="searchForm" action="{{route('dashboard.addons.index')}}" method="GET">
                @csrf
                <button style="margin-bottom: 10px" type="button" class="btn btn-warning" id="showFilters">Filters</button>
                <div class="row" id="filters" style="display: none">
                  <div class="form-group col-md-4" style="border:1px solid #ddd">
                    {{-- <label for="registeration_date">Registration Date</label> --}}
                    <div class="form-group">
                      <label for="from">{{__("site.from")}}:</label>
                      <input type="datetime-local" id="from" name="from_to[]" value="{{ isset(request("from_to")[0]) ? request("from_to")[0] :"" }}" class="form-control">
                      <label for="to">{{__("site.to")}}:</label>
                      <input type="datetime-local" id="to" name="from_to[]" value="{{ isset(request("from_to")[1]) ? request("from_to")[1] : "" }}" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <input type="text" class="form-control" value="{{ request("search") ?: "" }}" name="search" placeholder="{{__("site.search")}}">
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">{{__("site.search")}}</button>
                  </div>
                </div>
              </form>
            <div class="table-container">
              <table id="manageaddonsTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{__("site.employee")}}</th>
                        <th>{{__("site.employee_code")}}</th>
                        <th>{{__("site.value")}}</th>
                        <th>{{__("site.date")}}</th>
                        <th>{{__("site.reason")}}</th>
                        <th>{{__("site.options")}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($addons as $addon)
                    <tr>

                        <td>{{$addon->id}}</td>

                        <td><a href="{{route("dashboard.employees.index", ['search'=> $addon->employee->code])}}">{{$addon->employee->name}}</a></td>
                        <td>
                            <a href="{{route("dashboard.employees.index", ['search'=> $addon->employee->code])}}"> <span class="badge badge-success">{{$addon->employee->code}}</span></a>
                        </td>
                        <td>{{$addon->value}}</td>
                        <td>{{$addon->date}}</td>
                        <td>{{$addon->reason}}</td>


                        <td>
                          @if (auth()->user()->hasPermission("update_salaries"))
                          <a href="{{route("dashboard.addons.edit", $addon->id)}}"><button class="btn btn-info"><i class="fa fa-edit"></i></button></a>
                          @else
                          <a href="#"><button class="btn btn-info disabled"><i class="fa fa-edit"></i></button></a>
                          @endif


                          @if (auth()->user()->hasPermission("delete_salaries"))
                          <form action="{{route('dashboard.addons.destroy',$addon->id)}}" method="POST" style="display:inline-block">
                              @csrf
                              @method('DELETE')
                              <button class="btn btn-danger delete" type="submit"><i class="fa fa-trash"></i></button>
                          </form>
                          @else
                          <button class="btn btn-danger disabled" type="button"><i class="fa fa-trash"></i></button>
                          @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>

            </div>
            <!-- /.card-body -->
           <div class="card-footer clearfix">
              {{$addons->appends(request()->query())->links()}}
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

@endsection


@section('js')
<script>
  $(".delete").on("click",function(e){
    e.preventDefault();
    var btn = $(this);
    Swal.fire({
      title: '{{__("site.confirm_delete")}}',
      text: '{{__("site.confirm_delete")}}',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: '{{__("site.confirm")}}',
      cancelButtonText: '{{__("site.cancel")}}'
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          btn.parent('form').submit();
        }
      })
     });
</script>

<script>
    $("#addonsList").addClass("active");
</script>



@endsection
