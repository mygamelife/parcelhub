@extends('layouts.app')

@section('content')
	<div class="container">
		<section class="section">
			<div class="columns">
				<div class="column is-one-quarter">
					@include('components.side-menu')
				</div>
				<div class="column">
					<inbounds-excel-wizard>
					</inbounds-excel-wizard>
				</div>	
			</div>
		</section>
	</div>
@endsection