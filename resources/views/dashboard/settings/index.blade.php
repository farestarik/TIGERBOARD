@extends('layout.master')

@section('page')
 {{__("site.settings")}}
@endsection

@section('link')
{{route('dashboard.settings.index')}}
@endsection


@section('content')

     <div class="row">
        <div class="col-12">
          <div class="card">

            <div class="card-header card-header-style">
              <h3 class="card-title">{{__("site.edit_settings")}}</h3>
            </div>

            <!-- /.card-header -->
            <form action="{{route("dashboard.settings.update", $setting->id)}}" method="POST"  enctype="multipart/form-data">
              @csrf
              @method("PUT")
              <div class="card-body">


                <div class="form-group">
                    <label for="developer">{{__('site.developer')}}</label>
                    <input type="text" style="text-align: center" class="form-control" disabled value="{{$setting->developer}}">
                </div>

                <hr>


                  <div class="form-group">
                      <label for="app_name">{{__('site.app_name')}}</label>
                      <input type="text" style="text-align: center" class="form-control" placeholder="{{__("site.app_name")}}" value="{{$setting->app_name}}" name="app_name">
                  </div>

                  <div class="form-group">
                      <label for="company_name">{{__('site.company_name')}}</label>
                      <input type="text" style="text-align: center" name="company_name" class="form-control" placeholder="{{__("site.company_name")}}" value="{{$setting->company_name}}">
                  </div>

                  <div class="form-group">
                      <label for="commercial_register">{{__('site.commercial_register')}}</label>
                      <input type="number" style="text-align: center" name="commercial_register" class="form-control" placeholder="{{__("site.commercial_register")}}" value="{{$setting->commercial_register}}">
                  </div>
                  <div class="form-group">
                      <label for="tax_num">{{__('site.tax_num')}}</label>
                      <input type="number" style="text-align: center" name="tax_num" class="form-control" placeholder="{{__("site.tax_num")}}" value="{{$setting->tax_num}}">
                  </div>

                  <div class="form-group">
                    <label for="email">{{__('site.email')}}</label>
                    <input type="email" style="text-align: center" class="form-control" placeholder="{{__("site.email")}}" value="{{$setting->email}}" name="email">
                  </div>

                  <div class="form-group">
                    <label for="phone">{{__('site.phone')}}</label>
                    <input type="tel" class="form-control" name="phone" id="phone" value="{{$setting->phone}}" placeholder="{{__('site.enter') . '  ' . __('site.phone')}}">
                  </div>

                  <div class="form-group">
                    <label for="address">{{__('site.address')}}</label>
                    <textarea class="form-control" name="address" id="address" placeholder="{{__('site.enter') . '  ' . __('site.address')}}">{{$setting->address}}</textarea>
                  </div>

                  <div class="form-group">
                    <label for="img">{{__("site.photo")}}</label>
                    <input type="file" class="form-control imageInput" name="img" id="photo">
                  </div>

                  <div class="form-group">
                    <img src="{{$setting->logo_pic}}" width="150" class="img-thumbnail img-preview" height="150px">
                  </div>

                  <hr>


                  <div class="form-group">
                    <label for="max_document_size">{{__('site.max_document_size')}} (MB)</label>
                    <input type="number" name="max_document_size" id="max_document_size" class="form-control" value="{{$setting->max_document_size}}">
                  </div>



                </div>

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{__('site.save')}}</button>
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
    $("#settingsList").addClass("active");
</script>

@endsection
