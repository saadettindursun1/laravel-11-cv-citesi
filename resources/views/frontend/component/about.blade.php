<div class="container-xxl py-6" id="about">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="d-flex align-items-center mb-5">
                      <h3 class="lh-base mb-0">{{$about->title}}</h3>
                    </div>
                    <p class="mb-4">
                    {{$about->content}}
                   </p>
          
                    <a class="btn btn-primary py-3 px-5 mt-3" href="">İletişime Geç</a>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                   <img src="{{asset($about->image)}}" class="img-fluid rounded" alt="">
                  
                </div>
            </div>
        </div>
    </div>