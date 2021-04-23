@extends('layouts.default-extend')
@section('content')
<!-- Post Div-->
@include('includes.user-profile-banner')		

<div class="mainCont">
@include('includes.main-left-side')
<div class="profile-content">    
	<div class="content-gray-title mb10">
        <h4>Personal Information</h4>
    </div>
    <div class="details-list">
				<div class="detail-item">
					<div class="dtl-item">
						<span>First Name &ast;</span>
					</div>
					<div class="dtl-value">
						<span>Paul</span>
					</div>
				</div>

				<div class="detail-item">
					<div class="dtl-item">
						<span>Last Name &ast;</span>
					</div>
					<div class="dtl-value">
						<span>Smith</span>
					</div>
				</div>

				<div class="detail-item">
					<div class="dtl-item">
						<span>Gender &ast;</span>
					</div>
					<div class="dtl-value">
						<span>Male</span>
					</div>
				</div>

				<div class="detail-item">
					<div class="dtl-item">
						<span>Birthday</span>
					</div>
					<div class="dtl-value">
						<span>April 17&comma; 1975</span>
					</div>
				</div>
			</div>
    <div class="content-gray-title mb10">
        <h4>About</h4>
    </div>
    <p class="formating">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras dictum mauris sed fermentum viverra. Fusce molestie quam nec justo luctus porttitor. Mauris id velit semper enim eleifend porttitor. Nam vitae augue dui. Suspendisse suscipit eros dolor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Interdum et malesuada fames ac ante ipsum primis in faucibus. In quis posuere justo. Aliquam mollis aliquet orci id maximus. Aliquam tincidunt auctor nibh, eget iaculis odio fermentum a.
	</p>        
</div>
@include('includes.ads-right-side')
</div>
@endsection