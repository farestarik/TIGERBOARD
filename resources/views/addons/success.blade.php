@if (session()->has('success'))
<script>
    Swal.fire({
        title: '{{__("site.done")}} !',
        text: '{{session()->get("success")}}',
        icon: 'success',
        confirmButtonText: '{{__("site.ok")}}'
     });
</script>
{{session()->forget('success')}}
@endif

@if (session()->has('synced'))
<script>
    Swal.fire({
        title: '{{__("site.done")}} !',
        text: '{{session()->get("synced")}}',
        // icon: 'fingerprint',
        imageUrl: "{{asset('pics/fingerprint.png')}}",
        confirmButtonText: '{{__("site.ok")}}'
     });
</script>
{{session()->forget('synced')}}
@endif
