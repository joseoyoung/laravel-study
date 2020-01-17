{{-- <footer class="footer">
    <ul class="list-inline pull-right locale">
        <li><i class="fa fa-language"></i></li>
        <li class="active"><a href="#">English</a></li>
        <li><a href="#">한국어</a></li>
    </ul>

    <div>
        &copy; {{ date('Y') }} &nbsp; <a href="https://github.com/appkr/l5essential">Laravel 5 Essential</a>
    </div>
</footer> --}}

<footer class="page-footer font-small text-center pt-4">
    <ul class="list-inline text-center locale">
        <li><i class="fa fa-language"></i></li>
        @foreach (['en' => 'English', 'ko' => '한국어'] as $locale => $language)
        <li class="{{ ( $currentLocale ?? '') }}">
            <a href="{{ route('locale', ['locale' => $locale, 'return' => urlencode($currentUrl ?? '')]) }}">
            {{ $language }}
            </a>
        </li>
        @endforeach
    </ul>
    <div>
        &copy; {{ date('Y') }} &nbsp; <a href="https://github.com/appkr/l5essential">Laravel 5 Essential</a>
    </div>
    <br/>
    
</footer>
