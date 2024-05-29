<!-- Footer: Start -->
<footer class="landing-footer bg-body footer-text">
    <div class="footer-top">
        <div class="container">
            <div class="row gx-0 gy-4 g-md-5">
                <div class="col-lg-5">
                    <a href="landing-page.html" class="app-brand-link mb-4">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="{{ config('app.name') }}">
                    </a>
                    <p class="footer-text footer-logo-description mb-4">
                        Social reviews management platform for businesses and brands.
                    </p>
                    <form class="footer-form">
                        <label for="footer-email" class="small">Subscribe to newsletter</label>
                        <div class="d-flex mt-1">
                            <input
                                type="email"
                                class="form-control rounded-0 rounded-start-bottom rounded-start-top"
                                id="footer-email"
                                placeholder="Your email" />
                            <button
                                type="submit"
                                class="btn btn-primary shadow-none rounded-0 rounded-end-bottom rounded-end-top">
                                Subscribe
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <h6 class="footer-title mb-4">Company</h6>
                    <ul class="list-unstyled">

                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <h6 class="footer-title mb-4">Pages</h6>
                    <ul class="list-unstyled">

                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="footer-title mb-4">Download our app</h6>
                    <a href="javascript:void(0);" class="d-block footer-link mb-3 pb-2"
                    ><img src="{{ asset('assets/img/front-pages/landing-page/apple-icon.png') }}" alt="apple icon"
                        /></a>
                    <a href="javascript:void(0);" class="d-block footer-link"
                    ><img src="{{ asset('assets/img/front-pages/landing-page/google-play-icon.png') }}" alt="google play icon"
                        /></a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom py-3">
        <div
            class="container d-flex flex-wrap justify-content-between flex-md-row flex-column text-center text-md-start">
            <div class="mb-2 mb-md-0">
            <span class="footer-text"
            >©
              <script>
                document.write(new Date().getFullYear());
              </script>
            </span>
                <a href="https://pixinvent.com" target="_blank" class="fw-medium text-white footer-link">SPromoter</a>
            </div>
            <div>
                <a href="https://github.com/pixinvent" class="footer-link me-3" target="_blank">
                    <img
                        src="{{ asset('assets/img/front-pages/icons/github-light.png') }}"
                        alt="github icon"
                        data-app-light-img="front-pages/icons/github-light.png"
                        data-app-dark-img="front-pages/icons/github-dark.png" />
                </a>
                <a href="https://www.facebook.com/pixinvents/" class="footer-link me-3" target="_blank">
                    <img
                        src="{{ asset('assets/img/front-pages/icons/facebook-light.png') }}"
                        alt="facebook icon"
                        data-app-light-img="front-pages/icons/facebook-light.png"
                        data-app-dark-img="front-pages/icons/facebook-dark.png" />
                </a>
                <a href="https://twitter.com/pixinvents" class="footer-link me-3" target="_blank">
                    <img
                        src="{{ asset('assets/img/front-pages/icons/twitter-light.png') }}"
                        alt="twitter icon"
                        data-app-light-img="front-pages/icons/twitter-light.png"
                        data-app-dark-img="front-pages/icons/twitter-dark.png" />
                </a>
                <a href="https://www.instagram.com/pixinvents/" class="footer-link" target="_blank">
                    <img
                        src="{{ asset('assets/img/front-pages/icons/instagram-light.png') }}"
                        alt="google icon"
                        data-app-light-img="front-pages/icons/instagram-light.png"
                        data-app-dark-img="front-pages/icons/instagram-dark.png" />
                </a>
            </div>
        </div>
    </div>
</footer>
<!-- Footer: End -->
