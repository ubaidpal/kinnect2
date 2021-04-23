@extends('layouts.blank')
@section('content')
        <!-- Title Bar -->
<div class="title-bar">
    <span>FAQ</span>
</div>


<div class="form-container mt10">
    <form action="">
        <!-- search -->
        <div class="form-search">
    <input type="search" class="form-item faq-search"  id="filter" placeholder="Search for help..." name="filter">
        </div>
    </form>
</div>

<!-- Accordian Container -->
<div class="accordionContainer">
    <!-- Accordian Item -->

    <div class="accordian-item">
        <div class="accordionButton">
            <span class="indicator"></span>
            <h4 class="acrdn-btn-txt">
                How do I switch from top stories to most recent stories on my News Feed?
            </h4>
        </div>
        <div class="accordionContent">
            <div class="accordion-content-txt">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio eius, vero ad facilis.
            </div>
            <ol class="acrdn-list">
                <li>Lorem ipsum dolor sit amet.</li>
                <li>Lorem ipsum dolor sit amet.</li>
                <li>Lorem ipsum dolor sit amet.</li>
            </ol>
            <div class="accordion-content-note">
                Hic maiores iste fuga, tenetur aperiam, minus ipsa dolorum repellendus.
            </div>
        </div>
    </div>
    <!-- Accordian Item -->
    <div class="accordian-item">
        <div class="accordionButton">
            <span class="indicator"></span>
            <h4 class="acrdn-btn-txt">Sample Text</h4>
        </div>
        <div class="accordionContent">
            <div class="accordion-content-txt">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio eius, vero ad facilis.
            </div>
            <ol class="acrdn-list">
                <li>Lorem ipsum dolor sit amet.</li>
                <li>Lorem ipsum dolor sit amet.</li>
                <li>Lorem ipsum dolor sit amet.</li>
            </ol>
            <div class="accordion-content-note">
                Hic maiores iste fuga, tenetur aperiam, minus ipsa dolorum repellendus.
            </div>
        </div>
    </div>
    <!-- Accordian Item -->
    <div class="accordian-item">
        <div class="accordionButton">
            <span class="indicator"></span>
            <h4 class="acrdn-btn-txt">
                How do I switch from top stories to most recent stories on my News Feed?
            </h4>
        </div>
        <div class="accordionContent">
            <div class="accordion-content-txt">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio eius, vero ad facilis.
            </div>
            <ol class="acrdn-list">
                <li>Lorem ipsum dolor sit amet.</li>
                <li>Lorem ipsum dolor sit amet.</li>
                <li>Lorem ipsum dolor sit amet.</li>
            </ol>
            <div class="accordion-content-note">
                Hic maiores iste fuga, tenetur aperiam, minus ipsa dolorum repellendus.
            </div>
        </div>
    </div>
    <!-- Accordian Item -->
    <div class="accordian-item">
        <div class="accordionButton">
            <span class="indicator"></span>
            <h4 class="acrdn-btn-txt">How do I use the left side menu on my home page?</h4>
        </div>
        <div class="accordionContent">
            <div class="accordion-content-txt">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio eius, vero ad facilis.
            </div>
            <ol class="acrdn-list">
                <li>Lorem ipsum dolor sit amet.</li>
                <li>Lorem ipsum dolor sit amet.</li>
                <li>Lorem ipsum dolor sit amet.</li>
            </ol>
            <div class="accordion-content-note">
                Hic maiores iste fuga, tenetur aperiam, minus ipsa dolorum repellendus.
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#filter').keyup(function () {
        var filter = this.value.toLowerCase();
        $('.accordian-item').each(function () {
            var _this = $(this);
            var title = _this.find('h4').text().toLowerCase();

            if (title.indexOf(filter) < 0) {
                _this.hide();
            }
            if (title.indexOf(filter) == 0) {
                _this.show();
            }
        });
    });
</script>
@endsection
