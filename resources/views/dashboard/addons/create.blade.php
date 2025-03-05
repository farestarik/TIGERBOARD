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
                <h3 class="card-title" style="float: left">{{__("site.create_addons")}}</h3>
                <a href="{{ route("dashboard.addons.index") }}"><button style="float:right" class="btn btn-primary" title="{{ __("site.show_addons") }}"><i class="fa fa-eye"></i></button></a>
              <div class="clearfix"></div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form action="{{route("dashboard.addons.store")}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="employee_id">{{__('site.employee')}}</label>
                    <select class="form-control" name="employee_id" id="employee_id" data-live-search="true">
                        <option value="">{{__("site.choose")}}</option>
                        @foreach ($employees as $employee)
                        <option value="{{$employee->id}}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>

<hr>
                <div class="form-group">
                    <label for="value">{{__('site.value')}}</label>
                    <input type="text" class="form-control" name="value" id="value" value="{{old("value")}}" placeholder="{{__('site.enter') . '  ' . __('site.value')}}">
                </div>
<hr>
                <div class="form-group">
                    <label for="date">{{__('site.date')}}</label>
                    <input type="date" class="form-control timestamp" name="date" id="date" value="{{old("date")}}" placeholder="{{__('site.enter') . '  ' . __('site.date')}}">
                </div>

                <div class="form-group">
                    <label for="reason">{{__('site.reason')}}</label>
                    <textarea class="form-control" name="reason" id="reason" placeholder="{{__('site.enter') . '  ' . __('site.reason')}}">{{old("reason")}}</textarea>
                </div>





                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{__('site.submit')}}</button>
              </div>
              </div>
              </form>
            </div>
            <!-- /.card-body -->

          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

@endsection


@section('js')

<script>
    $(function() {
        // $( ".timestamp" ).datepicker();
    });
</script>


<script>
    $("#addonsList").addClass("active");
    $("#employee_id").selectpicker();
</script>


<script src="{{ asset("js/custom/addons.js") }}" defer></script>

@endsection
