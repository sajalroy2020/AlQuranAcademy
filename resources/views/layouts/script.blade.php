<!--   Core JS Files   -->
<script src="{{ asset('dashboard/assets/js/jquery-3.7.0.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/js/dataTables.js')}}"></script>
<script src="{{ asset('dashboard/assets/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/js/core/popper.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/js/core/bootstrap.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/chartjs.min.js')}}"></script>

@stack('script')

<script>
	@if(Session::has('success'))
	    toastr.success("{{ session('success') }}");
	@endif
	@if(Session::has('error'))
	    toastr.error("{{ session('error') }}");
	@endif
	@if(Session::has('info'))
	    toastr.info("{{ session('info') }}");
	@endif
	@if(Session::has('warning'))
	    toastr.warning("{{ session('warning') }}");
	@endif

	@if (@$errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
	@endif
</script>
