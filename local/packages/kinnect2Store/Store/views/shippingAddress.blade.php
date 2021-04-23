@extends('Store::layouts.default-extend')
@section('content')
        <!-- Post Div-->
@include('Store::includes.store-banner')

<div class="mainCont">

    @include('Store::includes.store-order-leftside')
    <style>
        input[type="text"] {
            margin-left: 2px;
            width: 99%;
        }

        .cssPopup_overlay {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            transition: opacity 500ms;
            visibility: hidden;
            opacity: 0;
        }

        .cssPopup_overlay:target {
            visibility: visible;
            opacity: 1;
            z-index: 5;
        }

        .cssPopup_popup {
            margin: 10% auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            width: 50%;
            position: relative;
            transition: all 5s ease-in-out;
        }

        .cssPopup_popup h2 {
            margin-top: 0;
            color: #333;
            font-family: Tahoma, Arial, sans-serif;
        }

        .cssPopup_popup .cssPopup_close {
            position: absolute;
            top: 3px;
            right: 10px;
            transition: all 200ms;
            font-size: 30px;
            font-weight: bold;
            text-decoration: none;
            color: #333;
        }

        .cssPopup_popup .cssPopup_close:hover {
            color: #06D85F;
        }

        .cssPopup_popup .cssPopup_content {
            max-height: 30%;
            overflow: auto;
        }

        @media screen and (max-width: 700px) {
            .cssPopup_box {
                width: 70%;
            }

            .cssPopup_popup {
                width: 70%;
            }
        }
    </style>
    <div class="product-Analytics">
        @foreach($addresses as $address)

        @endforeach
    </div>
</div>
<script>

</script>
@endsection
