<?php // JSON verisini PHP dizisine dönüştür
$data = json_decode($site_setting, true);

// Telefon numarasını al
$telefon = array_column(array_filter($data, function($item) {
    return $item['setting_key'] === 'telefon';
}), 'setting_value')[0] ?? null;

$mail = array_column(array_filter($data, function($item) {
    return $item['setting_key'] === 'mail';
}), 'setting_value')[0] ?? null;


// Sonuç ?>


<div class="container-xxl pb-5" id="contact">
        <div class="container py-5">
            <div class="row g-5 mb-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="col-lg-6">
                    <h1 class="display-5 mb-0">İletişime Geç</h1>
                </div>
           
            </div>
            <div class="row g-5">
                <div class="col-lg-5 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
  
                    <hr class="w-100">
                    <p class="mb-2">Beni ara:</p>
                    <h3 class="fw-bold"><a href="tel:{{$telefon}}">{{$telefon}}</h3></a>
                    <hr class="w-100">
                    <p class="mb-2">Mail gönder:</p>
                    <h3 class="fw-bold"><a href="mailto:{{$mail}}">{{$mail}}</a></h3>
                    <hr class="w-100">
                    <p class="mb-2">Takip Et:</p>
                    <div class="d-flex pt-2">
                        @foreach ($social_medias as $social_media)
                        <a class="btn btn-square btn-primary me-2" href="{{$social_media->link}}"><i class="fab {{$social_media->icon}}"></i></a>

                        @endforeach
                        <!-- <a class="btn btn-square btn-primary me-2" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-primary me-2" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-square btn-primary me-2" href=""><i class="fab fa-linkedin-in"></i></a> -->
                    </div>
                </div>
                <div class="col-lg-7 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <p class="mb-4">
                      Benimle sorularınız,fikirleriniz,projeleriniz hakkında iletişime geçebilirsiniz..</p>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" placeholder="Your Name">
                                    <label for="name">İsim</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" placeholder="Your Email">
                                    <label for="email">Mail Adresi</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" placeholder="Subject">
                                    <label for="subject">Konu Başlığı</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="message" style="height: 100px"></textarea>
                                    <label for="message">Mesaj</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary py-3 px-5" type="submit">Mesaj Gönder</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>