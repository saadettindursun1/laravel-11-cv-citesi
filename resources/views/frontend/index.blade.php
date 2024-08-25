@extends("frontend.layouts.app")
@section("content")
<div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" class="scrollspy-example" tabindex="0">
     
   
     @include('frontend.component.slider')
     @include('frontend.component.about')
     @include('frontend.component.service')
     @include('frontend.component.skills')
     @include('frontend.component.portfolio')
     @include('frontend.component.feedback')
     @include('frontend.component.blog')
     @include('frontend.component.contact')
    
    </div>



    @endsection

