<div class="container-fluid bg-light my-6 mt-0" id="home">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 py-6 pb-0 pt-lg-0">
                    <h3 class="text-primary mb-3">Merhaba, ben</h3>
                    <h1 class="display-3 mb-3">{{$slider->title}}</h1>
                    <h2 class="typed-text-output d-inline"></h2>
                    <div class="typed-text d-none">Web Designer, Web Developer, Front End Developer, Apps Designer, Apps Developer</div>
                    <div class="d-flex align-items-center pt-5">
                        <a href="{{$slider->link}}" class="btn btn-primary py-3 px-4 me-5">Linkedin Profili</a>
                        <button type="button" class="btn-play" data-bs-toggle="modal"
                            data-src="{{$slider->video_link}}" data-bs-target="#videoModal">
                            <span></span>
                        </button>
                        <h5 class="ms-4 mb-0 d-none d-sm-block">Video Oynat</h5>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img class="img-fluid" src="{{asset($slider->image)}}" alt="">
                </div>
            </div>
        </div>
    </div>

      <!-- Video Modal Start -->
      <div class="modal modal-video fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Youtube Video</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- 16:9 aspect ratio -->
                    <div class="ratio ratio-16x9">
                        <iframe class="embed-responsive-item" src="" id="video" allowfullscreen allowscriptaccess="always"
                            allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Video Modal End -->