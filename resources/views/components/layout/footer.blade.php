<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="footer-content">
                <div class="footer-contact">
                    <h3>Eilinger Stiftung</h3>
                    <p>
                        Seeweg 45<br>
                        8264 Eschenz<br>
                        Schweiz <br><br>
                        <strong>Email:</strong>
                        <a href="mailto:{{ config('mail.from.address') }}" target="_blank">
                            {{ config('mail.from.address') }}
                        </a><br>
                        <br>
                        <strong>{{ __('home.desired_contact') }}</strong>
                    </p>
                </div>

                <div class="footer-links">
                    <h4>{{ __('home.links') }}</h4>
                    <ul>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="{{ route('index', app()->getLocale()) }}">Home</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="{{ route('impressum', app()->getLocale()) }}">Impressum</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a
                                href="{{ route('datenschutz', app()->getLocale()) }}">{{ __('dataprotection.data-protection') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>Eilinger Stiftung</span></strong>. All Rights Reserved
            </div>
        </div>
    </div>
</footer>
